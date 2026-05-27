@extends('dashboard.authBase')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-md-6">
        <!-- Logo and Title -->
        <div class="text-center mb-4">
            <img src="{{ asset('assets/img/logo_login.png') }}" class="img-fluid mb-3 logo-centered" alt="{{ env('APP_NAME') }}">
            <h3 class="welcome-label">@lang('auth.reset_password')</h3>
        </div>

        <!-- Reset Password Card -->
        <div class="card p-4 border-0 login-card">
            <div class="card-body">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <!-- Hidden field for the reset token -->
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email Input Field -->
                    <div class="form-group mb-3">
                        <input id="email" type="email" class="form-control control-user @error('email') is-invalid @enderror" 
                               name="email" placeholder="@lang('auth.email')" value="{{ $email ?? old('email') }}" required autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Password Input Field -->

                        <input id="password" type="password" class="form-control control-user @error('password') is-invalid @enderror" 
                               name="password" placeholder="@lang('auth.new_password')" required autocomplete="new-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    <!-- Confirm Password Field -->
                    <div class="form-group mb-4">
                        <input id="password-confirm" type="password" class="form-control control-password" 
                               name="password_confirmation" placeholder="@lang('auth.confirm_password')" required autocomplete="new-password">
                    </div>

                    <!-- Redirect to Login Link -->
                    <div class="d-flex text-center justify-content-center mb-4">
                        <a href="{{ route('login') }}" class="text-muted forgot-password">@lang('auth.back_to_login')</a>
                    </div>

                    <!-- Submit Button -->
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
<!-- Custom JavaScript can be added here if needed -->
@endsection
