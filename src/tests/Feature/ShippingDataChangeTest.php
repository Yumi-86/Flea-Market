<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Product;
use App\Models\Purchase;

class ShippingDataChangeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    protected $user;
    protected $product;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()
            ->has(Profile::factory([
                'postal_code' => '111-1111',
                'address' => '東京都葛飾区1-1',
            ]))
            ->create();

        $this->product = Product::factory()->create();
    }

    public function test_ui_reflects_changed_shipping_address()
    {
        $this->actingAs($this->user);

        $this->post(route('address.update', $this->product), [
            'shipping_postal_code' => '222-2222',
            'shipping_address' => '神奈川県横浜市2-2',
        ])->assertRedirect(route('purchase.create', $this->product));

        $this->get(route('purchase.create', $this->product))
            ->assertSee('222-2222')
            ->assertSee('神奈川県横浜市2-2');
    }

    public function test_db_links_purchase_with_changed_shipping_address()
    {
        $this->actingAs($this->user);
        $this->post(route('address.update', $this->product), [
            'shipping_postal_code' => '222-2222',
            'shipping_address' => '神奈川県横浜市2-2',
        ])->assertRedirect(route('purchase.create', $this->product));

        $this->post(route('purchase.store', $this->product), [
            'payment_method' => 'コンビニ支払い',
            'shipping_postal_code' => '222-2222',
            'shipping_address' => '神奈川県横浜市2-2',
        ])->assertRedirect();

        $this->assertDatabaseHas('purchases', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'shipping_postal_code' => '222-2222',
            'shipping_address' => '神奈川県横浜市2-2',
        ]);
    }
}
