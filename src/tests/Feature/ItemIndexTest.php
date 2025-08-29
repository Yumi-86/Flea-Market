<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;

class ItemIndexTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_all_products_can_be_displayed()
    {
        $products = Product::factory()->count(5)->create();

        $response = $this->get(route('items.top'));

        foreach ($products as $product) {
            $response->assertSee($product->name);
        }

        $response->assertStatus(200);
    }

    public function test_sold_label_is_shown_for_purchased_products()
    {
        $soldProduct = Product::factory()->create([
            'selling_status' => true,
        ]);

        $response = $this->get(route('items.top'));

        $response->assertSee('Sold');
        $response->assertSee($soldProduct->name);
    }

    public function test_my_own_product_are_not_showing_up()
    {
        $this->actingAs($this->user);

        $ownProducts = Product::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'name' => 'OWN_PRODUCT_' . Str::random(10),
        ]);

        $otherProducts = Product::factory()->count(3)->create([
            'name' => 'OTHER_PRODUCT_' . Str::random(10),
        ]);

        $response = $this->get(route('items.top'));

        foreach($ownProducts as $ownProduct) {
            $response->assertDontSee($ownProduct->name);
        }

        foreach($otherProducts as $otherProduct) {
            $response->assertSee($otherProduct->name);
        }
    }
}
