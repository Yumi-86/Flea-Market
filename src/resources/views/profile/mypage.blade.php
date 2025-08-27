@extends('layouts.app') 

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/mypage.css') }}"> 
@endsection 

@section('content') 
<div class="mypage">
    <div class="mypage-header">
        <div class="mypage-header__user-info">
            <div class="user-info__image"> 
                <img src="{{ $profile->profile_image ? asset('storage/' . $profile->profile_image) : asset('images/default-user.png') }}" alt="プロフィール画像"> 
            </div>
            <div class="user-info__name">
                <h2>{{ $user->name }}</h2>
            </div>
        </div>
        <div class="mypage-header__link"> 
            <a href="{{ route('profile.edit') }}" class="mypage-header__link-edit">プロフィールを編集</a> 
        </div>
    </div>
    <div class="mypage__content">
        <div class="mypage__tabs"> 
            <a href="{{ route('mypage') }}?tab=sell" class="{{ $activeTab === 'sell' ? 'active' : '' }}">出品した商品</a> 
            <a href="{{ route('mypage') }}?tab=buy" class="{{ $activeTab === 'buy' ? 'active' : '' }}">購入した商品</a> 
        </div> 
        
        @if ($activeTab === 'sell') 
        <div class="mypage__product-list product-grid"> 
            @forelse ($sellProducts as $product) 
            <a href="{{ route('items.show', $product->id) }}">
                <div class="product-card">
                    <div class="product-card__image-wrapper"> 
                        <img src="{{ $product->product_image ?? asset('images/placeholder.png') }}" alt="商品画像" class="product-card__image"> 
                        @if($product->is_sold) 
                        <div class="product-card__sold">Sold</div> 
                        @endif 
                    </div>
                    <p class="product-card__name">{{ $product->name }}</p>
                </div>
            </a> 
            @empty 
            <p class="product-grid__empty">出品した商品がありません。</p> 
            @endforelse 
        </div> 
        
        @elseif ($activeTab === 'buy') 
        <div class=" product-list product-grid"> 
            @forelse ($buyProducts as $purchase) 
            @if ($purchase->product) 
            <a href="{{ route('items.show', $purchase->product->id) }}">
                <div class="product-card">
                    <div class="product-card__image-wrapper"> 
                        <img src="{{ $purchase->product->product_image ?? asset('images/placeholder.png') }}" alt="商品画像" class="product-card__image"> 
                    </div>
                    <p class="product-card__name">{{ $purchase->product->name }}</p>
                </div>
            </a> 
            @endif 
            @empty 
            <p class="product-grid__empty">購入した商品がありません。</p> 
            @endforelse 
        </div> 
        @endif
    </div>
</div> 
@endsection