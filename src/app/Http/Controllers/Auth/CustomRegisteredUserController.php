<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\VerifyEmail;


class CustomRegisteredUserController extends Controller
{
    use PasswordValidationRules;

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Actions\Fortify\CreateNewUser  $creator
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(RegisterRequest $request, CreateNewUser $creator)
    {
        $user = $creator->create(array_merge($request->validated(), ['role' => 'user']));

        // メール認証を送信
        $user->sendEmailVerificationNotification();
        // 会員登録後に自動ログインをしない
        Auth::logout();

        // セッションメッセージを設定
        session()->flash('status', '登録が完了しました！メールを確認してください。');

        // リダイレクト処理
        return redirect('/verify-email');
    }

    // 店舗代表者の登録フォーム（管理者専用）
    public function indexStoreOwner()
    {
        return view('admin.store-representatives');
    }

    // 店舗代表者の登録処理（管理者専用）
    public function storeOwner(RegisterRequest $request, CreateNewUser $creator)
    {
        $user = $creator->create(array_merge($request->validated(), ['role' => 'store_owner']));

        $user->sendEmailVerificationNotification();

        Auth::logout();

        session()->flash('status', '登録が完了しました！');

        return redirect('/admin-email');
    }

    // 管理者の登録フォーム（管理者専用）
    public function indexAdmin()
    {
        return view('admin.new-admin');
    }

    // 管理者の登録処理（管理者専用）
    public function storeAdmin(RegisterRequest $request, CreateNewUser $creator)
    {
        $user = $creator->create(array_merge($request->validated(), ['role' => 'admin']));

        return redirect('/admin/index')->with('success', '管理者が登録されました');
    }
}
