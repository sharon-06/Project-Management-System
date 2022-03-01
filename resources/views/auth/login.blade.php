@extends('layouts.app')

@section('content')
<!-- <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <label class="form-check-label" for="remember">
                                         {{ __('Remember Me') }}
                                    </label>
                                    &nbsp;<input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                            <div class="col-md-8 mt-3 offset-md-4">
                                <a href="{{ route('register') }}">{{ __('I am a new user') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->


<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <form method="POST" action="{{ route('login') }}" class="box">
                    @csrf
                    <img src="{{ asset('/public/image/ceckin.png') }}" class="" style="opacity: .8;height: 170px;width: auto;">
                    <!-- <h1>{{ __('Login') }}</h1> -->
                    <p class="text-muted"> Please enter your login and password!</p> 
                    
                    <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('E-Mail Address') }}"> 
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
 
                    <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="{{ __('Password') }}">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <label class="forgot text-muted" for="remember">
                         {{ __('Remember Me') }}
                    </label>
                    &nbsp;<input class="forgot text-muted" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <input type="submit" name="" value="{{ __('Login') }}">
                    @if (Route::has('password.request'))
                        <a class="forgot text-muted" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                    <br>
                    <!-- <a  class="forgot text-muted"  href="{{ route('register') }}">{{ __('I am a new user') }}</a> -->
                </form>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    body {
    margin: 0;
    padding: 0;
    font-family: sans-serif;
    background: linear-gradient(to right, #b92b27, #1565c0)
}

.text-muted {
    color: #f8f9fa!important;
}

.card {
    margin-bottom: 20px;
    border: none
}

.box {
    width: 500px;
    padding: 40px;
    position: absolute;
    top: 50%;
    left: 50%;
    background: #f8f9fa85;
    text-align: center;
    transition: 0.25s;
    margin-top: 100px
}

.box input[type="text"],
.box input[type="email"],
.box input[type="password"] {
    border: 0;
    background: none;
    display: block;
    margin: 20px auto;
    text-align: center;
    border: 2px solid #3498db;
    padding: 10px 10px;
    width: 250px;
    outline: none;
    color: white;
    border-radius: 24px;
    transition: 0.25s
}

.box h1 {
    color: white;
    text-transform: uppercase;
    font-weight: 500
}

.box input[type="text"]:focus,
.box input[type="email"]:focus,
.box input[type="password"]:focus {
    width: 300px;
    border-color: #2ecc71
}

.box input[type="submit"] {
    border: 0;
    background: none;
    display: block;
    margin: 20px auto;
    text-align: center;
    border: 2px solid #2ecc71;
    padding: 14px 40px;
    outline: none;
    color: white;
    border-radius: 24px;
    transition: 0.25s;
    cursor: pointer
}

.box input[type="submit"]:hover {
    background: #2ecc71
}

.forgot {
    text-decoration: underline
}

ul.social-network {
    list-style: none;
    display: inline;
    margin-left: 0 !important;
    padding: 0
}

ul.social-network li {
    display: inline;
    margin: 0 5px
}

.social-network a.icoFacebook:hover {
    background-color: #3B5998
}

.social-network a.icoTwitter:hover {
    background-color: #33ccff
}

.social-network a.icoGoogle:hover {
    background-color: #BD3518
}

.social-network a.icoFacebook:hover i,
.social-network a.icoTwitter:hover i,
.social-network a.icoGoogle:hover i {
    color: #fff
}

a.socialIcon:hover,
.socialHoverClass {
    color: #44BCDD
}

.social-circle li a {
    display: inline-block;
    position: relative;
    margin: 0 auto 0 auto;
    border-radius: 50%;
    text-align: center;
    width: 50px;
    height: 50px;
    font-size: 20px
}

.social-circle li i {
    margin: 0;
    line-height: 50px;
    text-align: center
}

.social-circle li a:hover i,
.triggeredHover {
    transform: rotate(360deg);
    transition: all 0.2s
}

.social-circle i {
    color: #fff;
    transition: all 0.8s;
    transition: all 0.8s
}
</style>
@endsection
