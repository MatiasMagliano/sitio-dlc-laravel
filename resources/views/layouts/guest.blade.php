<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="stylesheet" href="https://dlc-laravel.ddns.net/vendor/fontawesome-free/css/all.min.css">
        <link rel="stylesheet" href="https://dlc-laravel.ddns.net/vendor/adminlte/dist/css/adminlte.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link rel="shortcut icon" href="https://dlc-laravel.ddns.net/favicons/favicon.ico">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body>
        <div class="inicio">
            {{ $slot }}
        </div>

        <script src="https://dlc-laravel.ddns.net/vendor/jquery/jquery.min.js"></script>
        <script src="https://dlc-laravel.ddns.net/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('js/jquery-countdown.js') }}"></script>
        <script src="{{ asset('js/jquery.backstretch.min.js') }}"></script>
        <script src="{{ asset('js/scripts.js') }}"></script>
    </body>
</html>
