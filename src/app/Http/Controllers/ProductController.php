<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->searchKeyword($request->keyword);

        if (Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }
        if ($request->tab === 'mylist') {
            if (Auth::check()) {
                $productIds = Auth::user()->likes()->pluck('product_id');
                $query->whereIn('id', $productIds);
            } else {
                $query->whereRaw('0 = 1');
            }
        }
        $products = $query->latest()->get();

        return view('items.index', compact('products'));
    }
    public function show(Product $product)
    {
        $isLiked = false;
        if (Auth::check()) {
            $isLiked = Auth::user()->likes()->where('product_id', $product->id)->exists();
        }

        $comments = $product->comments()->with('user')->latest()->get();

        return view('items.show', compact('product', 'isLiked', 'comments'));
    }
}
