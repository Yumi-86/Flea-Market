<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product;
use App\Http\Requests\ShippingAddressRequest;

class AddressController extends Controller
{
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
