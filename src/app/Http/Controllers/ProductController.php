<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\ProductRequest;

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
    public function create() 
    {
        $categories = Category::all();
        return view('items.sell', compact('categories'));
    }
    public function store(ProductRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        $path = null;
        if ($request->hasFile('product_image')) {
            $path = $request->file('product_image')->store('product_images', 'public');
        }

        $product = $user->products()->create([
            'product_image' => $path,
            'name' => $validated['name'],
            'brand' => $validated['brand'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'condition' => $validated['condition'],
            'selling_status' => false,
        ]);
        
        if (isset($validated['categories'])) {
            $product->categories()->attach($validated['categories']);
        }

        return redirect()->route('mypage', ['tab' => 'sell'])
            ->with('status', '商品を出品しました！');
    }
}
