<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Profile;

class ItemSellTest extends TestCase
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

    public function test_user_can_create_a_product_with_valid_data()
    {
        Storage::fake('public');
        
        $categories = Category::factory()->count(3)->create();

        $this->actingAs($this->user);

        $formData = [
            'product_image' => UploadedFile::fake()->image('test.png'),
            'categories' => $categories->pluck('id')->toArray(),
            'name' => 'テスト商品',
            'brand' => 'test brand',
            'description' => 'これはテスト用の商品説明です。',
            'price' => 5000,
            'condition' => '良好',
        ];

        $response = $this->post(route('items.store'), $formData);

        $response->assertStatus(302);

        $product = Product::first();

        Storage::disk('public')->assertExists($product->product_image);


        $this->assertDatabaseHas('products', [
            'product_image' => $product->product_image,
            'name' => 'テスト商品',
            'brand' => 'test brand',
            'description' => 'これはテスト用の商品説明です。',
            'price' => 5000,
            'condition' => '良好',
            'user_id' => $this->user->id,
        ]);

        $product = Product::first();
        $this->assertCount(3, $product->categories);
        $this->assertEqualsCanonicalizing(
            $categories->pluck('id')->toArray(),
            $product->categories->pluck('id')->toArray()
        );
    }
}
