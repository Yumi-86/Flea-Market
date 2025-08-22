<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Auth\Events\Verified;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Middleware\CustomRedirectIfVerified;
use App\Models\Product;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);
Route::middleware(['auth'])->group(function () {
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
    ->middleware('throttle:6,1')
    ->name('verification.send');
});
Route::middleware(['web', 'auth', CustomRedirectIfVerified::class])->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');
});

Route::get('/', [ProductController::class, 'index'])->name('items.top');
Route::get('/item/{product}', [ProductController::class, 'show'])->name('items.show');

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'create'])->name('profile.create');
    Route::post('/mypage/profile', [ProfileController::class, 'store'])->name('profile.store');
});

Route::middleware('auth', 'verified', 'profile.complete')->group(function () {
    Route::post('/item/{product}/comment', [CommentController::class, 'store'])->name('comment.store');
    Route::post('/item/{product}/like', [LikeController::class, 'store'])->name('likes.store');
    Route::delete('/item/{product}/like', [LikeController::class, 'destroy'])->name('likes.destroy');

    Route::get('/purchase/{product}', [PurchaseController::class, 'create'])->name('purchase.create');
    Route::post('/purchase/{product}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/stripe/success', [PurchaseController::class, 'success'])->name('purchase.success');
    Route::get('/purchase/cancel/{product}', function (Product $product) {
        return redirect()->route('items.show', $product)->with('error', '決済がキャンセルされました。');
    })->name('purchase.cancel');

    Route::get('/purchase/{product}/address/edit', [AddressController::class, 'edit'])->name('address.edit');
    Route::post('/purchase/{product}/address', [AddressController::class, 'update'])->name('address.update');

    Route::get('/sell', [ProductController::class, 'create'])->name('items.create');
    Route::post('/sell', [ProductController::class, 'store'])->name('items.store');

    Route::get('/mypage', [ProfileController::class, 'show'])->name('mypage');
    Route::get('/mypage/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/mypage/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});