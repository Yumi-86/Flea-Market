<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use Refreshdatabase;
    
    public function test_validation_error_when_email_is_missing_on_login()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    public function test_validation_error_when_password_is_missing_on_login()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    public function test_login_fails_with_invalid_credentials()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません']);
    }

    public function test_user_with_verified_email_and_profile_is_redirected_to_home()
    {
        $user = User::factory([
            'email_verified_at' => now(),
        ])->has(Profile::factory())->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('items.top'));
    }
    public function test_user_without_verified_email_is_redirected_to_email_verification()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('verification.notice'));
    }

    public function test_user_with_verified_email_but_no_profile_is_redirected_to_profile_setup()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('profile.create'));
    }
}
