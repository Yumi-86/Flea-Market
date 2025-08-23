<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Product;
use App\Models\category;
use App\Models\Purchase;

class PurchaseTest extends TestCase
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
            ->has(Profile::factory())
            ->create();
        
        $this->product = Product::factory([
            'selling_status' => false,
        ])->create();
    }

    public function test_logged_in_user_can_purchase_products()
    {
        $this->actingAs($this->user)
            ->post(route('purchases.store', $this->product->id),[
                'payment_method' => 'コンビニ支払い',
                'shipping_postal_code' => $this->user->profile->postal_code,
                'shipping_address' => $this->user->profile->address,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('purchases', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
        ]);
    }

    public function test_sold_label_is_shown_for_purchased_products()
    {
        $this->actingAs($this->user)
            ->post(route('purchases.store', $this->product->id),[
                'payment_method' => 'コンビニ支払い',
                'shipping_postal_code' => $this->user->profile->postal_code,
                'shipping_address' => $this->user->profile->address,
            ])
            ->assertRedirect();

        $this->get(route('items.index'))
            ->assertStatus(200)
            ->assertSeeText('Sold');
    }

    public function test_products_will_be_displayed_in_buy_list_on_profile_after_purchase()
    {
        $this->actingAs($this->user)
            ->post(route('purchases.store', $this->product->id), [
                'payment_method' => 'コンビニ支払い',
                'shipping_postal_code' => $this->user->profile->postal_code,
                'shipping_address' => $this->user->profile->address,
            ])
            ->assertRedirect();

        $this->get(route('mypage', ['tab' => 'buy']))
            ->assertStatus(200)
            ->assertSeeText('Sold')
            ->assertSee($this->product->name);
    }
}
