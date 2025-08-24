@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/items_show.css') }}">
@endsection

@section('content')
<div class="item-dtl__page">
    <div class="item-dtl__image-wrapper">
        <img src="{{ $product->product_image ?? asset('images/placeholder.jpg') }}" alt="商品画像" class="item-dtl__image">
        @if($product->is_sold)
        <div class="product-card__sold">Sold</div>
        @endif
    </div>
    <div class="item-dtl__info">
        <h1>{{ $product->name }}</h1>
        <p class="item-dtl__brand">ブランド: {{ $product->brand ?? 'なし' }}</p>
        <p class="item-dtl__price">¥{{ number_format($product->price) }}（税込）</p>
        <div class="item-dtl__icon">
            <form method="POST" action="{{ route($isLiked ? 'likes.destroy' : 'likes.store', $product) }}" class="item-dtl__liked-form">
                @csrf
                @if($isLiked)
                @method('DELETE')
                <button type="submit" class="item-dtl__liked--destroy"></button>
                @else
                <button type="submit" class="item-dtl__liked"></button>
                @endif
                <p class="liked__nmb">{{ $product->likes->count() }}</p>
            </form>
            <div class="item-dtl__comment">
                <a href="#comment-form" class="item-dtl__comment-pic" title="コメント欄へ移動"></a>
                <p class="commnents__nmb">{{ $comments->count() }}</p>
            </div>
        </div>
        <form action="{{ route('purchase.create', $product) }}" class="item-dtl__btn">
            @csrf
            <button type="submit" class="btn item-dtl__btn item-dtl__btn--buy">購入手続きへ</button>
        </form>
        <h3 class="item-dtl__section-ttl">商品説明</h3>
        <p class="item-dtl__description">{{ $product->description }}</p>
        <h3 class="item-dtl__section-ttl">商品の情報</h3>
        <div class=" item-dtl__table">
            <table class="table__inner">
                <tr class="table__row">
                    <th class="table__header">カテゴリ</th>
                    <td class="table__content table__content--category">
                        @foreach($product->categories as $category)
                        <span class="category-tag">{{ $category->name }}</span>
                        @endforeach
                    </td>
                </tr>
                <tr class="table__row">
                    <th class="table__header">商品の状態</th>
                    <td class="table__content table__content--condition">
                        <span>{{ $product->condition }}</span>
                    </td>
                </tr>
            </table>
        </div>
        <div class="comment">
            <h3 class="comment__heading">コメント( {{ $product->comments->count() }} )</h3>
            @foreach($comments as $comment)
            <div class="comment__group">
                <div class="comment__user-info">
                    <img src="{{ $comment->user->profile_image ?? asset('images/default-user.png') }}" alt="プロフィール画像" class="comment__user-image">
                    <p class="comment__user-name">{{ $comment->user->name }}</p>
                </div>
                <div class="comment__body">
                    <p class="comment__content">{{ $comment->content }}</p>
                </div>
            </div>
            @endforeach
            <p class="comment__adding">商品へのコメント</p>
            <form action="{{ route('comment.store', $product) }}" method="post" class="comment__adding-form" id="comment-form"> @csrf
                @auth
                <textarea name="content" class="comment__adding-txt" rows="8"></textarea>
                <div class="comment__adding-btn">
                    <button class="comment__adding-btn--submit btn" type="submit">コメントを送信する</button>
                </div>
                @error('content')
                <div class="comment__error"> {{ $message }} </div>
                @enderror
                @endauth
                @guest
                <textarea name="content" class="comment__adding-txt" rows="8" disabled>コメント機能を利用するにはログインが必要です。</textarea>
                @endguest
            </form>
        </div>
    </div>
</div>
@endsection