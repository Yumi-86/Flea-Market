<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Product;
use App\Models\Purchase;

class ProfileTest extends TestCase
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

        $this->user = User::factory()
            ->has(Profile::factory([
                'profile_image' => 'test.png',
                'postal_code' => '111-1111',
                'address' => '東京都',
                'building' => 'tokyoビル',
            ]))
            ->create();
    }
    
    public function test_profile_page_shows_user_info()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('mypage'));

        $response->assertStatus(200)
            ->assertSee($this->user->name)
            ->assertSee('test.png');
    }

    public function test_profile_page_shows_sell_products()
    {
        $this->actingAs($this->user);

        $product = Product::factory()->for($this->user)->create([
            'name' => 'テスト商品',
        ]);

        $response = $this->get(route('mypage', ['tab' => 'sell']));

        $response->assertStatus(200)
            ->assertSee($product->name);
    }

    public function test_profile_page_shows_buy_products()
    {
        $this->actingAs($this->user);

        $product = Product::factory()->create([
            'name' => '購入済み商品',
        ]);

        Purchase::create([
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'shipping_name' => $this->user->name,
            'shipping_postal_code' => $this->user->profile->postal_code,
            'shipping_address' => $this->user->profile->address,
            'shipping_building' => $this->user->profile->building,
            'price' => $product->price,
            'payment_method' => 'カード支払い',
        ]);

        $response = $this->get(route('mypage', ['tab' => 'buy']));

        $response->assertStatus(200)
            ->assertSee($product->name);
    }

    public function test_previously_set_values_are_initially_entered_for_each_editing_item()
    {
        $this->actingAs($this->user);
        
        $response = $this->get(route('profile.edit'));

        $response->assertStatus(200)
            ->assertSee('test.png')
            ->assertSee('value="' . e($this->user->name) . '"', false)
            ->assertSee('value="' . e($this->user->profile->postal_code) . '"', false)
            ->assertSee('value="' . e($this->user->profile->address) . '"', false)
            ->assertSee('value="' . e($this->user->profile->building) . '"', false);
    }
}
