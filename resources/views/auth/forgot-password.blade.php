@extends('adminlte::auth.auth-page')

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@section('auth_header', __('adminlte::adminlte.forgot_message'))

@section('auth_body')
    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    
    <form action="{{ route('password.email') }}" method="post">
        @csrf

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="row">
            <div class="col-5 offset-2">
                <a href="{{ route('login') }}" 
                    class="btn btn-block btn-secondary {{ config('adminlte.classes_auth_btn', 'btn-flat') }}">
                    <span class="fas fa-undo-alt"></span>
                    {{ __('adminlte::adminlte.back_login') }}</a>
            </div>
            <div class="col-5">
                <button type=submit class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
                    <span class="fas fa-sign-in-alt"></span>
                    {{ __('adminlte::adminlte.forgot_submit') }}
                </button>
            </div>
        </div>
    </form>
@stop

@section('auth_footer')
    <x-adminlte-callout theme="info">
        {{ __('adminlte::adminlte.forgot_hint') }}
        {{session('status')}}
    </x-adminlte-callout>
@stop