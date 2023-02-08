@extends('layouts.app')

@section('content')
<div class="hp-title">
    <div class="logo"><img src="/img/icon/logo_brown.png" alt="logo"></div>
    <h2 class="title">家計簿アプリ</h2>
</div>
<div class="form-area login-form">
    <h2>{{ __('ログイン') }}</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="メールアドレス">
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="パスワード">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-check" style="height: 50px; text-align: center;">
            <input class="form-check-input" type="checkbox" style="width: auto;" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">
                {{ __('ログイン状態を維持する') }}
            </label>
        </div>

        <div><input type="submit" class="submit" name="submit" value="{{ __('ログイン') }}"></div>
        
        <div class="text-links">
            <div><a href="{{ route('register') }}">新規登録はこちら</a></div>
            <div>
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{ __('パスワードを忘れてしまった場合はこちら') }}
                </a>
            </div>
        </div>
    </form>
</div><!-- form-area -->
@endsection
