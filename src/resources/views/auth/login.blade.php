@extends('layouts.default')

@section('content')
    <div class="auth__content">
        <h2 class="auth__title">Login</h2>
        @if (count($errors) > 0)
        <p class="form__error-content">入力に問題があります</p>
        @endif
        <form class="auth__form" action="/login" method="post" novalidate>
            @csrf
            <div class="auth__form-group">
                <div class="auth__form-input">
                    <i class="fa-solid fa-envelope"></i><input type="email" name="email" placeholder="Email" value="{{ old('email') }}" autocomplete="off"/>
                    @if ($errors->has('email'))
                        <p class="form__error">{{ $errors->first('email') }}</p>
                    @endif
                </div>
                <div class="auth__form-input">
                    <i class="fa-solid fa-key"></i><input type="password" name="password" placeholder="Password" value="{{ old('password') }}" />
                    @if ($errors->has('password'))
                        <p class="form__error">{{ $errors->first('password') }}</p>
                    @endif
                </div>
            </div>
            <div class="form-submit">
                <button class="auth__form-submit" type="submit">ログイン</button>
            </div>
        </form>
    </div>
@endsection