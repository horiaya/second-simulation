@extends('layouts.default')
<style>
    header {
        display: none;
    }
</style>
@section('content')
<div class="store-representative">
    <h1>新規管理者の作成</h1>
    <div class="admin-index__remove">
        <a href="{{ route('admin.index') }}">戻る</a>
    </div>
    <form method="POST" action="{{ route('admin.storeAdmin') }}">
    @csrf
        <div class="store-representative__input">
            <table>
                <tr>
                    <th>管理者の名前</th>
                    <td><input type="text" name="name" placeholder="名前"></td>
                </tr>
                <tr>
                    <th>メールアドレス</th>
                    <td><input type="email" name="email" placeholder="メールアドレス"></td>
                </tr>
                <tr>
                    <th>パスワード</th>
                    <td><input type="password" name="password" placeholder="パスワード"></td>
                </tr>
                <tr>
                    <th>再度パスワード確認</th>
                    <td><input type="password" name="password_confirmation" placeholder="パスワード確認"></td>
                </tr>
            </table>
        </div>
        <button type="submit">登録</button>
    </form>
</div>
@endsection