@extends('layouts.app') 

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/profile.css') }}"> 
@endsection 

@section('content') 
<div class="address-page page">
    <div class="address-page__inner page__inner">
        <h1 class="address-page__heading page__heading"> 住所の変更 </h1>
        <form action="{{ route('address.update', $product) }}" method="post" class="address-page__form page__form"> @csrf 
            <div class="form__group">
                <div class="form__header">郵便番号</div> 
                <input type="text" class="form__input" name="shipping_postal_code" value="{{ old('shipping_postal_code', $shippingData['shipping_postal_code'] ?? '') }}"> 
                @error('shipping_postal_code') 
                <span class="form__error">{{ $message }}</span> 
                @enderror
            </div>

            <div class="form__group">
                <div class="form__header">住所</div> 
                <input type="text" class="form__input" name="shipping_address" value="{{ old('shipping_address', $shippingData['shipping_address'] ?? '') }}"> 
                @error('shipping_address') 
                <span class="form__error">{{ $message }}</span> 
                @enderror
            </div>

            <div class="form__group">
                <div class="form__header">建物名</div> 
                <input type="text" class="form__input" name="shipping_building" value="{{ old('shipping_building', $shippingData['shipping_building'] ?? '') }}"> 
                @error('shipping_building') 
                <span class="form__error">{{ $message }}</span> 
                @enderror
            </div> 

            <button type="submit" class="form__btn btn btn--primary">更新する</button>
        </form>
    </div>
</div> 
@endsection