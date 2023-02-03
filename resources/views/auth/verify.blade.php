@extends('layouts.app')

@section('content')
<!-- パスワードをリセットするための
リンクメール送信完了の画面 -->
<div class="form-area pass-form">
    <h2>{{ __('パスワードリセット') }}</h2>
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="form-summary">
            <p>パスワード再設定用のメールを送信し、パスワードを再設定します。</p>
            <p>登録していたメールアドレスを入力して下さい。</p>
        </div>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <div>
            <h3>{{ __('メールアドレス') }}</h3>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="test@test.co.jp">
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div>
            <input type="submit" class="submit" name="submit" value="送信">
        </div>
    </form>    
</div><!-- form-area -->
@endsection
