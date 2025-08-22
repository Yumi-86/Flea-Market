<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Like;

class ItemSearchFunctionTest extends TestCase
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
    
    public function test_can_search_products_by_partial_name()
    {
        $keyword = 'Apple';

        $matchingProduct = Product::factory()->create([
            'name' => 'Apple iPhone',
        ]);

        $nonMatchingProduct = Product::factory()->create([
            'name' => 'Samsung Galaxy',
        ]);

        $response = $this->get(route('items.top', ['keyword' => $keyword]));

        $response->assertSeeText($matchingProduct->name);
        $response->assertDontSeeText($nonMatchingProduct->name);
    }

    public function test_search_keyword_is_preserved_in_mylist()
    {
        $keyword = 'Apple';

        $this->actingAs($this->user);

        $matchingProduct = Product::factory()->create([
            'name' => 'Apple Watch',
        ]);

        Like::create([
            'user_id' => $this->user->id,
            'product_id' => $matchingProduct->id,
        ]);

        $this->get(route('items.top', ['keyword' => $keyword]))
            ->assertSeeText($matchingProduct->name);

        $response = $this->get(route('items.top', [
            'tab' => 'mylist',
            'keyword' => $keyword
        ]));

        $response->assertSeeText($matchingProduct->name);
    }
}
