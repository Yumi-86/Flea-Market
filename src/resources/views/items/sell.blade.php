@extends('layouts.app') 

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/items_sell.css') }}"> 
@endsection 

@section('content') 
<div class="sell-page page">
    <div class="sell-page__inner page__inner">

        <h1 class="sell-page__title">商品の出品</h1>

        <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="sell-form"> @csrf

            <div class="sell-form__group sell-form__group--image">
                <p class="sell-form__label">商品画像</p>
                <label for="product_image" class="sell-form__image-label">
                    <span class="sell-form__image-text">画像を選択する</span>
                    <img id="image-preview" class="sell-form__image-preview" src="#" alt="プレビュー画像" style="display: none;" />
                    <input type="file" id="product_image" name="product_image" class="sell-form__image-input" accept="image/*">
                </label>

                @error('product_image')
                <div class="sell-form__error form__error">{{ $message }}</div>
                @enderror
            </div>

            <div class="sell-form__group">
                <h2 class="sell-form__heading">商品の詳細</h2>

                <div class="sell-form__categories">
                    <p class="sell-form__label">カテゴリー</p>
                    <div class="sell-form__items">
                        @foreach ($categories as $category)
                        <label class="sell-form__category">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="sell-form__category-input" hidden>
                            <span class="sell-form__category-label">{{ $category->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('categories')
                    <div class="sell-form__error form__error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="sell-form__condition">
                    <label for="condition" class="sell-form__label">商品の状態</label>
                    <select name="condition" id="condition" class="sell-form__select">
                        <option value="" hidden>選択してください</option>
                        <option value="良好">良好</option>
                        <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                        <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                        <option value="状態が悪い">状態が悪い</option>
                    </select>
                    @error('condition')
                    <div class="sell-form__error form__error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="sell-form__group">
                <h2 class="sell-form__heading">商品名と説明</h2>

                <label class="sell-form__label">商品名</label>
                <input type="text" name="name" class="sell-form__input form__input">
                @error('name')
                <div class="sell-form__error form__error">{{ $message }}</div>
                @enderror

                <label class="sell-form__label">ブランド名</label>
                <input type="text" name="brand" class="sell-form__input form__input">
                @error('brand')
                <div class="sell-form__error form__error">{{ $message }}</div>
                @enderror

                <label class="sell-form__label">商品の説明</label>
                <textarea name="description" class="sell-form__textarea form__input" rows="4"></textarea>
                @error('description')
                <div class="sell-form__error form__error">{{ $message }}</div>
                @enderror

                <label class="sell-form__label">販売価格</label>
                <div class="sell-form__input-wrapper">
                    <input type="number" name="price" id="price" placeholder="0" min="1" class="sell-form__input sell-form__input--price form__input">
                    @error('price')
                    <div class="sell-form__error form__error">{{ $message }}</div>
                    @enderror
                </div>

            </div>
            <button type="submit" class="sell-form__submit-btn btn btn--primary">出品する</button>
        </form>
    </div>
</div>
<script>
    const input = document.getElementById('product_image');
    const preview = document.getElementById('image-preview');
    const placeholderText = document.querySelector('.sell-form__image-text');

    input.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholderText.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection