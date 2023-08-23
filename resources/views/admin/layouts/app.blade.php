<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/sass/app_backend.scss', 'resources/css/app_backend.css', 'resources/js/app_backend.js'])

    @livewireStyles
</head>
<body>
    <div id="app">
        @include('admin.layouts.navbar')

        <!--Main layout-->
        <!--<main style="margin-top: 58px;">-->
            <div class="container-fluid pt-3 mt-5">
                @include('admin.layouts.flash-message')
                {{ $slot }}
            </div>
        <!--</main>-->
    </div>

    @livewireScripts
</body>
</html>
