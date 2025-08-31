<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Product;

class PaymentMethodTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testPaymentMethodSelectionReflectsInSummary()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->browse(function (Browser $browser) use ($user, $product) {
            $browser->loginAs($user)
                ->visit("/purchase/{$product->id}")
                ->assertSee($product->name)

                ->select('payment_method', 'カード支払い')

                ->waitForText('カード支払い', 2, '#selected-method')
                ->assertSeeIn('#selected-method', 'カード支払い');
        });
    }
}
