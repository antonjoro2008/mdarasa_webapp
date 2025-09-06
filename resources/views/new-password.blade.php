@extends('layouts.app')

@section('content')
<div>
    <div class="row">
        <div class="col-sm-12">
            <a class="navbar-brand" href="/">
                <img src="{{ url('/images/logo.png')}}" class="logo mt-12" alt="MDARASA">
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="container content">
                <div class="row justify-content-center">
                    <div class="col-md-8 login-admin">
                        <div class="card full-width">
                            <div class="card-header">
                                <i class="fa fa-lock login-fa"></i>
                                {{ __('Forgot Password?') }}
                            </div>

                            <div class="card-body">
                                <form method="POST" action="{{ url('/password/new') }}">
                                    @csrf

                                    <div class="form-group row">
                                        <label for="email"
                                            class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>

                                        <div class="col-md-6">
                                            <input id="email" type="text"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" required autocomplete="email"
                                                placeholder="Enter your Registered Email Address" autofocus>
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="token"
                                            class="col-md-4 col-form-label text-md-right">{{ __('Code') }}</label>

                                        <div class="col-md-6">
                                            <input id="token" type="text"
                                                class="form-control @error('token') is-invalid @enderror" name="token"
                                                value="{{ old('token') }}" required autocomplete="token"
                                                placeholder="Enter the Received Code" autofocus>
                                            @error('token')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="newPassword" class="col-md-4 col-form-label text-md-right">
                                            {{ __('New Password') }}
                                        </label>

                                        <div class="col-md-6">
                                            <input id="newPassword" type="password"
                                                class="form-control @error('newPassword') is-invalid @enderror"
                                                name="newPassword" value="{{ old('token') }}" required
                                                autocomplete="newPassword" placeholder="Enter New Password" autofocus>
                                            @error('newPassword')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="confPassword" class="col-md-4 col-form-label text-md-right">
                                            {{ __('Confirm Password') }}
                                        </label>

                                        <div class="col-md-6">
                                            <input id="confPassword" type="password"
                                                class="form-control @error('confPassword') is-invalid @enderror"
                                                name="confPassword" value="{{ old('confPassword') }}" required
                                                autocomplete="token" placeholder="Confirm your Password" autofocus>
                                            @error('confPassword')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-8 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Set New Password') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
