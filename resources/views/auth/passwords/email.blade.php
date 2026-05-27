@extends('dashboard.authBase')

@section('content')

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-md-6">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/img/logo_login.png') }}" class="img-fluid mb-3 logo-centered" alt="{{ env('APP_NAME') }}">
            <h3 class="welcome-label">@lang('auth.recover_password')</h3>
        </div>
         <!-- Success Message -->
         @if (session('status'))
            <div class="alert alert-success text-center" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <div class="card p-4 border-0 s login-card">
            <div class="card-body email-template">
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <input class="form-control control-user control-user-reset @error('email') is-invalid @enderror" type="text" placeholder="@lang('auth.email')" name="email" autocomplete="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="d-flex text-center justify-content-center mb-4 ">
                        <a href="{{ route('login') }}" class="text-muted forgot-password">@lang('auth.back_to_login')</a>
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-dark-red btn-block btn-login" type="submit">@lang('auth.send_email')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
@endsection
