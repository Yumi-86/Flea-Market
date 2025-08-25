@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/purchases.css') }}">
@endsection

@section('content')
<form method="POST" action="{{ route('purchase.store', $product) }}" class="purchase-form">
    @csrf

    <div class="purchase-page">

        <div class="purchase-page__form-area">
            <div class="product-summary">
                <img src="{{ $product->product_image ? asset('storage/' . $product->product_image) : asset('images/placeholder.png') }}" alt="商品画像" class="product-summary__image">
                <div class="product-summary__info">
                    <h2 class="product-summary__name">{{ $product->name }}</h2>
                    <p class="product-summary__price">¥{{ number_format($product->price) }}</p>
                </div>
            </div>

            <div class="form__payment">
                <div class="form__payment-header">
                    <h3>支払い方法</h3>
                </div>
                <select name="payment_method" class="form__payment-select" required>
                    <option value="" hidden {{ old('payment_method') ? '' : 'selected' }}>選択してください</option>
                    <option value="コンビニ支払い" {{ old('payment_method') == 'コンビニ支払い' ? 'selected' : '' }}>コンビニ払い</option>
                    <option value="カード支払い" {{ old('payment_method') == 'カード支払い' ? 'selected' : '' }}>カード支払い</option>
                </select>
                @error('payment_method')
                <span class="form__payment-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form__shipping">
                <div class="form__shipping-header">
                    <h3>配送先</h3>
                    <a href="{{ route('address.edit', $product) }}" class="form__shipping-edit">変更する</a>
                </div>
                <div class="form__shipping-address">
                    <p>〒{{ optional($profile)->postal_code ?? '未登録' }}</p>
                    <p>{{ optional($profile)->address ?? '住所未登録' }}{{ optional($profile)->building }}</p>
                </div>
                @error('shipping_postal_code')
                <span class="form__shipping-error">{{ $message }}</span>
                @enderror
                @error('shipping_address')
                <span class="form__shipping-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="purchase-page__summary">
            <table>
                <tr>
                    <th>商品代金</th>
                    <td>¥{{ number_format($product->price) }}</td>
                </tr>
                <tr>
                    <th>支払い方法</th>
                    <td id="selected-method">未選択</td>
                </tr>
            </table>

            <button type="submit" class="purchase-page__btn purchase-page__btn--submit btn btn--primary">
                購入する
            </button>
        </div>
    </div>
</form>
<script>
    document.querySelector('.form__payment-select')?.addEventListener('change', function() {
        const selected = this.value || '未選択';
        document.getElementById('selected-method').textContent = selected;
    });
</script>

@endsection