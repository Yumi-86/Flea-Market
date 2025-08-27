<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Product;
use App\Models\Purchase;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function create(Product $product)
    {
        $user = Auth::user();
        $profile = $user->profile;
        return view('purchases.create', compact('product', 'user', 'profile'));
    }

    public function store(PurchaseRequest $request, Product $product)
    {
        $user = Auth::user();

        if($user && $request->payment_method === 'コンビニ支払い')  {
            DB::transaction(function () use ($user, $product, $request) {
                $product->update(['selling_status' => true]);

                Purchase::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'shipping_name' => $user->name,
                    'shipping_postal_code' => $request->shipping_postal_code,
                    'shipping_address' => $request->shipping_address,
                    'shipping_building' => $request->shipping_building,
                    'price' => $product->price,
                    'payment_method' => $request->payment_method,
                ]);
            });
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $sessionData = [
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => $product->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('purchase.cancel', $product),
            'metadata' => [
                'user_id' => $user->id,
                'product_id' => $product->id,
                'shipping_name' => $user->name,
                'price' => $product->price,
                'shipping_postal_code' => $request->shipping_postal_code,
                'shipping_address' => $request->shipping_address,
                'shipping_building' => $request->shipping_building,
                'payment_method' => $request->payment_method,
            ],
        ];

        $paymentMethodMap = [
            'カード支払い' => 'card',
            'コンビニ支払い' => 'konbini',
        ];

        $stripePaymentMethod = $paymentMethodMap[$request->payment_method] ?? null;

        if (!$stripePaymentMethod) {
            return back()->withErrors(['payment_method' => '支払い方法が不正です']);
        }

        $sessionData['payment_method_types'] = [$stripePaymentMethod];

        $session = Session::create($sessionData);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::retrieve($request->get('session_id'));
        $metadata = $session->metadata;

        DB::transaction(function () use ($metadata) {
            $product = Product::findOrFail($metadata->product_id);
            $product->update(['selling_status' => true]);

            Purchase::create([
                'user_id' => $metadata->user_id,
                'product_id' => $metadata->product_id,
                'shipping_name' => $metadata->shipping_name,
                'shipping_postal_code' => $metadata->shipping_postal_code,
                'shipping_address' => $metadata->shipping_address,
                'shipping_building' => $metadata->shipping_building,
                'price' => $metadata->price,
                'payment_method' => $metadata->payment_method,
            ]);
        });

        return view('purchases.complete');
    }
}
