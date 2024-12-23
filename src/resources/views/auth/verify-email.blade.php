@extends('layouts.default')

@section('content')
<style>
    .verify-email {
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
<div class="verify-email" id="content">
    <h1>メール確認が必要です</h1>
        <p>アカウントを利用するには、メールを確認してください。</p>
        @if (session('status'))
            <p style="color: green;">{{ session('status') }}</p>
        @endif
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit">確認メールを再送信</button>
        </form>
</div>
@endsection