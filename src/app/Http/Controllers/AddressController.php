<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\ShippingAddressRequest;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function edit(Product $product)
    {
        $user = Auth::user();
        $profile = optional($user->profile);

        $shippingData = session('shipping_address', [
            'postal_code' => $profile->postal_code ?? '',
            'address' => $profile->address ?? '',
            'building' => $profile->building ?? '',
        ]);

        return view('purchases.address', compact('user', 'product', 'shippingData'));
    }

    public function update(ShippingAddressRequest $request, Product $product)
    {
        $validated = $request->validated();

        session([
            'shipping_postal_code' => $validated['shipping_postal_code'],
            'shipping_address'     => $validated['shipping_address'],
            'shipping_building'    => $validated['shipping_building'] ?? null,
        ]);

        return redirect()
            ->route('purchase.create', $product)
            ->with('message', '住所を変更しました');
    }
}
