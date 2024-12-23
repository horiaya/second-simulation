<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\DoneController;
use Laravel\Fortify\Fortify;
use App\Http\Controllers\Auth\CustomRegisteredUserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\StoreOwner\StoreOwnerController;
use App\Http\Controllers\StoreOwner\OwnerReservationController;
use App\Http\Controllers\Admin\AdminController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;



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

Route::get('/thanks', function () {return view('thanks');})->name('thanks');
Route::get('/verify-email', function () {return view('auth.verify-email');});
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('logout.on.login.page')
    ->name('login');
//Route::get('/login', function () {return view('auth.login');})->name('login');
Route::post('/register', [CustomRegisteredUserController::class, 'store'])->name('register');

// Fortify の登録ビューカスタマイズ
Fortify::registerView(function () {return view('auth.register');});
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');


// 店舗代表者の登録（管理者専用）
Route::get('/store-owner/create', [CustomRegisteredUserController::class, 'indexStoreOwner'])
    ->middleware(['auth', 'role:admin'])->name('store-owner.register');
Route::post('/store-owner/create', [CustomRegisteredUserController::class, 'storeOwner'])
    ->middleware(['auth', 'role:admin']);

// 管理者の登録（管理者専用）
Route::get('/admin/new-admin', [CustomRegisteredUserController::class, 'indexAdmin'])
    ->middleware(['auth', 'role:admin'])->name('admin.register');
Route::post('/admin/new-admin', [CustomRegisteredUserController::class, 'storeAdmin'])
    ->middleware(['auth', 'role:admin']);

// メール認証関連
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', '認証リンクを送信しました！');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    $user = User::findOrFail($id);
    if (! hash_equals(sha1($user->getEmailForVerification()), $hash)) {
        abort(403, '無効なリンクです。');
    }
    if ($user->hasVerifiedEmail()) {
        return redirect()->route('thanks')->with('status', 'すでにメール認証が完了しています。');
    }
    $user->markEmailAsVerified();
    Auth::login($user);

    return redirect('/thanks')->with('status', 'メール認証が完了しました。');
})->middleware('signed')->name('verification.verify');

//店舗代表者用ルート
Route::middleware(['auth', 'role:store_owner'])->prefix('store-owners')->group(function () {
    Route::resource('shops', StoreOwnerController::class)->except(['show']);
    Route::resource('reservations', OwnerReservationController::class)->only(['index', 'update']);
});

//管理者用ルート
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/index', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin-email', function () {return view('admin.admin-email');});
    Route::get('/admin/store-owners/{id}', [AdminController::class, 'show'])->name('admin.detail');
    Route::delete('/admin/store-owners/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
    Route::get('admin/store-owner/create', [CustomRegisteredUserController::class, 'indexStoreOwner'])->name('admin.indexStoreOwner');
    Route::post('admin/store-owner/store', [CustomRegisteredUserController::class, 'storeOwner'])->name('admin.storeOwner');
    Route::get('admin/admin/create', [CustomRegisteredUserController::class, 'indexAdmin'])->name('admin.indexAdmin');
    Route::post('admin/admin/store', [CustomRegisteredUserController::class, 'storeAdmin'])->name('admin.storeAdmin');
});


Route::middleware(['auth', 'verified', 'role:user'])->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('home');
    Route::get('/detail/{id}', [ShopController::class, 'show'])->name('shop.detail');
    Route::get('/myPage', [MyPageController::class, 'index'])->name('mypage');
    Route::get('/myPage/detail/{id}', [MyPageController::class, 'myPage'])->name('detail.myPage');
    Route::post('/favorites/toggle/{shopId}', [FavoriteController::class, 'toggleFavorite'])->name('favorites.toggle');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::delete('/reservations/{id}', [ReservationController::class, 'delete'])->name('reservations.delete');
    Route::get('/edit/{id}', [ReservationController::class, 'edit'])->name('reservations.edit');
    Route::post('/update/{id}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::get('/done', [DoneController::class, 'index'])->name('done');
});
