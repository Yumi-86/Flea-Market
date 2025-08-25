<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Product;
use App\Models\Purchase;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Support\Facades\Auth;
use illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function create(Product $product)
    {
        $user = Auth::user();
        $profile = $user->profile;
        return view('purchases.create', compact('product', 'user', 'profile'));
    }

    public function store(PurchasesRequest $request, Product $product)
    {
        $user = Auth::user();

        Stripe::setApiKey(config('stripe.secret'));

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
            'success_url' => route('purchase.success', [
                'product_id' => $product->id,
                'shipping_postal_code' => $request->shipping_postal_code,
                'shipping_address' => $request->shipping_address,
                'shipping_building' => $request->shipping_building,
                'payment_method' => $request->payment_method,
            ]),
            'cancel_url' => route('purchase.cancel'),
        ];

        if ($request->payment_method === 'card') {
            $sessionData['payment_method_types'] = ['card'];
        } elseif ($request->payment_method === 'konbini') {
            $sessionData['payment_method_types'] = ['konbini'];
        } else {
            return back()->withErrors(['payment_method' => '支払い方法が不正です']);
        }

        $session = Session::create($sessionData);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $user = Auth::user();
        $product = Product::findOrFail($request->product_id);

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

        return view('purchases.complete');
    }
}
