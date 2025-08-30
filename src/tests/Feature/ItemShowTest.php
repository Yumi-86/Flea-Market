<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Profile;

class ItemShowTest extends TestCase
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
            ->has(Profile::factory())
            ->create();
    }

    public function test_product_detail_displays_all_required_information()
    {
        $imagePath = 'product_images/test-product.jpg';

        $product = Product::factory()->create([
            'product_image' => $imagePath,
            'name' => 'Test Product',
            'brand' => 'Test Brand',
            'price' => 12345,
            'description' => 'This is a test product.',
            'condition' => '良好',
            'selling_status' => false,
        ]);

        $categories = Category::factory()->count(3)->create();
        $product->categories()->attach($categories->pluck('id'));

        Like::factory()->count(7)->create([
            'product_id' => $product->id,
        ]);

        $comments = Comment::factory()->count(8)->create([
            'product_id' => $product->id,
            'user_id' => $this->user->id,
        ]);

        $response = $this->get(route('items.show', $product->id));

        $response->assertSee($imagePath);
        $response->assertSeeText($product->name);
        $response->assertSeeText($product->brand);
        $response->assertSeeText(number_format($product->price));
        $response->assertSeeText($product->description);
        $response->assertSeeText($product->condition);

        $response->assertSeeText((string) $product->likes()->count());

        $response->assertSeeText((string) $product->comments()->count());

        foreach ($comments as $comment) {
            $response->assertSeeText($comment->user->name);
            $response->assertSeeText($comment->content);
        }

        foreach ($categories as $category) {
            $response->assertSeeText($category->name);
        }
    }

    public function test_multiple_categories_are_displayed_in_product_detail()
    {
        $product = Product::factory()->create();
        $categories = Category::factory()->count(3)->create();
        $product->categories()->attach($categories->pluck('id'));

        $response = $this->get(route('items.show', $product->id));

        foreach ($categories as $category) {
            $response->assertSeeText($category->name);
        }
    }

}
