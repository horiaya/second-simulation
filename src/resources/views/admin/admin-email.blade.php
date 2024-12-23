@extends('layouts.default')

@section('content')
<style>
    header {
        display: none;
    }
    .admin-email p {
        font-size: 20px;
    }
    .admin-email form {
        margin-top: 20px;
    }
    .admin-email button {
        font-size: 18px;
    }
    .admin-email {
        text-align: center;
    }
    /* レスポンシブ (480px以下)*/
    @media screen and (max-width: 480px){
        .verify-email h1{
            font-size: 20px;
        }
        .verify-email p{
            font-size: 13px;
        }
    }
</style>
<div class="admin-email" id="content">
    <h1>登録完了</h1>
        <p>登録者宛にメールを送信しました</p>
        @if (session('status'))
            <p style="color: green;">{{ session('status') }}</p>
        @endif
        <a href="{{ route('admin.index') }}">管理画面へ戻る</a>
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit">確認メールを再送信</button>
        </form>
</div>
@endsection