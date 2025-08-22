<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
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
    public function test_user_can_logout_successfully()
    {
        $resonse = $this->actingAs($this->user)
                        ->post('/logout');

        $resonse->assertRedirect('/login');

        $this->assertGuest();
    }
}
