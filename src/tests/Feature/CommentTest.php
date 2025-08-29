<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Product;

class CommentTest extends TestCase
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

    public function test_logged_in_user_can_submit_comments()
    {
        $this->actingAs($this->user);
        
        $this->post(route('comment.store', $this->product), [
                'content' => 'テストコメント'
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'product_id' => $this->product->id,
            'user_id' => $this->user->id,
            'content' => 'テストコメント'
        ]);

        $this->get(route('items.show', $this->product))
            ->assertSee(['<p class="commnents__nmb">1</p>'], false);
    }

    public function test_users_cannot_submit_comments_before_logging_in()
    {
        $this->get(route('items.show', $this->product))
            ->assertStatus(200)
            ->assertDontSeeText('コメントを送信する')
            ->assertSee(['<p class="commnents__nmb">0</p>'], false);
    }

    public function test_validation_error_if_no_comment_is_entered()
    {
        $this->actingAs($this->user)
            ->post(route('comment.store', $this->product), [
                'content' => ''
            ])
            ->assertSessionHasErrors(['content' => 'コメント内容を入力してください']);

        $this->get(route('items.show', $this->product))
            ->assertSee(['<p class="commnents__nmb">0</p>'], false);

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_validation_error_if_comment_is_longer_than_255_characters()
    {
        $longText = str_repeat('あ', 256);

        $this->actingAs($this->user);
            
        $this->post(route('comment.store', $this->product), [
            'content' => $longText,
        ])
            ->assertSessionHasErrors(['content' => 'コメントは255文字以内で入力してください']);

        $this->assertDatabaseCount('comments', 0);

        $this->get(route('items.show', $this->product))
            ->assertSee(['<p class="commnents__nmb">0</p>'], false);
    }
}
