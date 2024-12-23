@extends('layouts.default')

@section('content')
<div class="done">
    <div class="done__content">
        <p>ご予約ありがとうございます</p>
        <a class="done__remove" href="{{ route('home') }}">
            <button>戻る</button>
        </a>
    </div>
</div>
@endsection