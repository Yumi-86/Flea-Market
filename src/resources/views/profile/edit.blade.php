@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-page page">
    <div class="profile-page__inner page-inner">
        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
        <h2 class="profile-page__heading page__heading">プロフィール設定</h2>

        <form action="{{ route('profile.update') }}" class="profile-page__form page__form" novalidate enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="profile-form__image-section">
                <img src="{{ 
                    $profile->profile_image ? 
                        asset('storage/' . $profile->profile_image) : 
                        asset('images/default-user.png') 
                    }}" alt="プロフィール画像" class="profile__image-preview" id="imagePreview">
                <label for="profile_image" class="profile-form__image-label">画像を選択する</label>
                <input type="file" name="profile_image" id="profile_image" class="profile-form__image-input" accept=".jpg,.jpeg,.png">
                @error('profile_image')
                <div class="profile-form__error form__error">{{ $message }}</div>
                @enderror
            </div>

            <div class="profile-form__group form__group">
                <label for="name" class="form__input-label">ユーザー名</label>
                <input type="text" name="name" class="profile-form__input form__input" value="{{ old('name', $user->name ?? '') }}" id="name">
                @error('name')
                <div class="profile-form__error form__error">{{ $message }}</div>
                @enderror
            </div>
            <div class="profile-form__group form__group">
                <label for="postal_code" class="form__input-label">郵便番号</label>
                <input type="text" name="postal_code" class="profile-form__input form__input" value="{{ old('postal_code', $profile->postal_code ?? '') }}" id="postal_code">
                @error('postal_code')
                <div class="profile-form__error form__error">{{ $message }}</div>
                @enderror
            </div>
            <div class="profile-form__group form__group">
                <label for="address" class="form__input-label">住所</label>
                <input type="text" name="address" class="profile-form__input form__input" value="{{ old('address', $profile->address ?? '') }}" id="address">
                @error('address')
                <div class="profile-form__error form__error">{{ $message }}</div>
                @enderror
            </div>
            <div class="profile-form__group form__group">
                <label for="building" class="form__input-label">建物名</label>
                <input type="text" name="building" class="profile-form__input form__input" value="{{ old('building', $profile->building ?? '') }}" id="building">
                @error('building')
                <div class="profile-form__error form__error">{{ $message }}</div>
                @enderror
            </div>
            <div class="profile-form__btn btn btn--primary">登録する</div>
        </form>
    </div>
</div>
@section('js')
<script>
    document.getElementById('profile_image').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const preview = document.getElementById('imagePreview');
            preview.src = URL.createObjectURL(file);
        }
    });
</script>
@endsection
@endsection