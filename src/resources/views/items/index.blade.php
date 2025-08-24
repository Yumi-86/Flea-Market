@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/items_index.css') }}">
@endsection

@section('content')
<div class="product-page">
    <div class="tab-menu">
        <div class="tab-menu__items">
            <a href="{{ url('/?' .http_build_query(['tab' => null, 'keyword' => request('keyword')])) }}" class="tab-menu__item {{ request('tab') !== 'mylist' ? 'active' : '' }}">おすすめ</a>
            <a href="{{ url('/?' . http_build_query(['tab' => 'mylist', 'keyword' => request('keyword')])) }}" class="tab-menu__item {{ request('tab') === 'mylist' ? 'active' : '' }}">マイリスト</a>
        </div>
        <div class="tab-menu__spacer"></div>
    </div>
    <div class="product-grid">
        @foreach($products as $product)
        <a href="{{ route('items.show', $product->id) }}">
            <div class="product-card">
                <div class="product-card__image-wrapper">
                    <img src="{{ $product->product_image ? asset('storage/' . $product->product_image) : asset('images/placeholder.jpg') }}" alt="商品画像" class="product-card__image">
                    @if($product->is_sold)
                    <div class="product-card__sold">Sold</div>
                    @endif
                </div>
                <p class="product-card__name">{{ $product->name }}</p>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endsection