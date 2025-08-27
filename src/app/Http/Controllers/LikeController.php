<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Like;

class LikeController extends Controller
{
    public function store(Product $product)
    {
        $user = Auth::user();

        if (!$user->likes()->where('product_id', $product->id)->exists()) {
            Like::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
        }
        return back();
    }

    public function destroy(Product $product)
    {
        $user = Auth::user();

        $like = $user->likes()->where('product_id', $product->id)->first();

        if ($like) {
            $like->delete();
        }

        return back();
    }
}
