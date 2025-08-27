@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/purchase_complete.css') }}">
@endsection

@section('content')
<div class="thank-you-page">
    <h2>ご購入ありがとうございました！</h2>
    <p>購入した商品はマイページからご確認いただけます。</p>
    <a href="{{ route('mypage') }}">マイページへ</a>
</div>
@endsection