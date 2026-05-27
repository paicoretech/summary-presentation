@extends('dashboard.authBase')

@section('content')

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-md-6">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/img/logo_login.png') }}" class="img-fluid mb-3 logo-centered" alt="{{ env('APP_NAME') }}">
            <h3 class="welcome-label">@lang('auth.welcome')</h3>
        </div>
        <div class="card p-4 border-0 s login-card">
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <input class="form-control control-user" type="text" placeholder="@lang('auth.user')" name="email" value="{{ old('email') }}" required autofocus>
                    
                    <div class="mb-3">
                        <input class="form-control control-password" type="password" placeholder="@lang('auth.password')" name="password" required>
                    </div>
                    <div class="d-flex text-center justify-content-center mb-4 ">
                        <a href="{{ route('password.request') }}" class="text-muted forgot-password">@lang('auth.forgot_password')</a>
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-dark-red btn-block btn-login" type="submit">@lang('auth.login')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
@endsection
