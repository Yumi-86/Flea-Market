<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Profile;

class LikeTest extends TestCase
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
    public function test_can_register_product_as_liked_by_pressing_like_icon()
    {
        $this->actingAs($this->user);

        $this->get(route('items.show', $this->product))
            ->assertSee(['<p class="liked__nmb">0</p>'], false);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
        ]);

        $this->post(route('likes.store', $this->product))->assertRedirect();

        $this->assertDatabaseHas('likes', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
        ]);

        $this->get(route('items.show', $this->product))
            ->assertSee(['<p class="liked__nmb">1</p>'], false);
    }

    public function test_icon_color_change_by_pressing_like_icon()
    {
        $this->actingAs($this->user);

        $this->post(route('likes.store', $this->product))->assertRedirect();

        $response = $this->get(route('items.show', $this->product))
            ->assertStatus(200);

        $this->assertMatchesRegularExpression('/item-dtl__liked--destroy/', $response->getContent());
    }

    public function test_can_remove_like_by_pressing_like_icon_again()
    {
        $this->actingAs($this->user);

        $this->post(route('likes.store', $this->product))->assertRedirect();

        $this->assertDatabaseHas('likes', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
        ]);

        $response = $this->get(route('items.show', $this->product))
            ->assertStatus(200)
            ->assertSee(['<p class="liked__nmb">1</p>'], false);

        $this->assertMatchesRegularExpression('/item-dtl__liked--destroy/', $response->getContent());

        $this->delete(route('likes.destroy', $this->product))->assertRedirect();

        $this->assertDatabaseMissing('likes', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
        ]);

        $response = $this->get(route('items.show', $this->product))
            ->assertStatus(200)
            ->assertSee(['<p class="liked__nmb">0</p>'], false);

        $this->assertMatchesRegularExpression('/item-dtl__liked(?!-)/', $response->getContent());
    }
}
