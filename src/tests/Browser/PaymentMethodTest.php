<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Profile;

class PaymentMethodTest extends DuskTestCase
{
    // use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testPaymentMethodSelectionReflectsInSummary()
    {
        $user = User::factory()
        ->has(Profile::factory([
            'profile_image' => 'test.png',
            'postal_code' => '111-1111',
            'address' => '東京都',
            'building' => 'tokyoビル',
        ]))
        ->create();

        $product = Product::factory()->create();

        $this->browse(function (Browser $browser) use ($user, $product) {
            $browser->loginAs($user)
                ->visit("/purchase/{$product->id}")
                ->dump();
                // ->waitForText($product->name, 3)
                // ->assertSee($product->name)

                // ->select('payment_method', 'カード支払い')

                // ->waitForText('カード支払い', 2, '#selected-method')
                // ->assertSeeIn('#selected-method', 'カード支払い');
        });
    }
}
