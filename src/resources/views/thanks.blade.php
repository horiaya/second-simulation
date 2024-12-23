@extends('layouts.default')
<style>
    .thanks__content {
    background-color: white;
    width:550px;
    text-align: center;
    margin:0 auto;
    padding:80px;
    box-shadow: 1px 1px 4px;
    }
    .thanks__message {
        text-align: center;
        font-weight:normal;
    }
    .thanks__login-remove{
        background-color:blue;
        width:130px;
        color:white;
        border-radius:3px;
        padding:5px 10px;
        text-decoration: none;
    }
    /* レスポンシブ (768px以下)*/
    @media screen and (max-width: 768px) {
        .thanks__content {
            width:400px;
            padding:40px;
            font-size: 10px;
        }
    }
    /* レスポンシブ (480px以下)*/
    @media screen and (max-width: 480px) {
        .thanks__content {
            width:200px;
            padding:10px;
            font-size: 5px;
        }
    }
</style>
@section('content')
<div class="thanks__content">
    <h2 class="thanks__message">会員登録ありがとうございます</h2>
    <a class="thanks__login-remove" href="{{ route('login') }}">ログインする</a>
</div>
@endsection