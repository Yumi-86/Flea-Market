<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\profile;
use App\Models\Like;

class MylistTest extends TestCase
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
    }

    public function test_only_liked_products_are_shown()
    {
        $this->actingAs($this->user);

        $likedProducts = Product::factory()->count(2)->create();
        $notLikedProducts = Product::factory()->count(3)->create();

        foreach($likedProducts as $likedProduct){
            Like::create([
                'user_id' => $this->user->id,
                'product_id' => $likedProduct->id,
            ]);
        }

        $response = $this->get(route('items.top', ['tab' => 'mylist']));

        foreach($likedProducts as $likedProduct) {
            $response->assertSeeText($likedProduct->name);
        }
        
        foreach($notLikedProducts as $notLikedProduct) {
            $response->assertDontSeeText($notLikedProduct->name);
        }
    }

    public function test_sold_label_is_shown_for_purchased_products()
    {
        $this->actingAs($this->user);

        $soldProduct = Product::factory()->create([
            'selling_status' => true, 
        ]);

        Like::create([
            'user_id' => $this->user->id,
            'product_id' => $soldProduct->id,
        ]);

        $response = $this->get(route('items.top', ['tab' => 'mylist']));

        $response->assertSeeText('Sold');
    }

    public function test_own_products_are_not_shown_in_liked_products()
    {
        $this->actingAs($this->user);

        $ownProduct = Product::factory()->create([
            'user_id' => $this->user->id,
        ]);

        Like::create([
            'user_id' => $this->user->id,
            'product_id' => $ownProduct->id,
        ]);

        $response = $this->get(route('items.top', ['tab' => 'mylist']));

        $response->assertDontSeeText($ownProduct->name);
    }

    public function test_guest_sees_nothing_on_liked_products_page()
    {
        $product = Product::factory()->create();

        $response = $this->get(route('items.top', ['tab' => 'mylist']));

        $response->assertStatus(200);
        $response->assertDontSeeText($product->name); 
    }
}
