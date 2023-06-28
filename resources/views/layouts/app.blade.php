<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Seudamgo') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="front">
    <div id="main">
        @include('home.top')
        @include('home.header')

        @if(Request::is('/'))
            @include('home.slider-wrapper')
            @livewire('frontend.homepage.booking')
{{--
            @include('home.category')
            @include('home.parallax')
            @include('home.advertising')
            @include('home.feedback')
            @include('home.partner')
--}}
        @else 
            <div class="container mt-4">
                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </div>
        @endif

        @include('home.footer')
        @include('home.bottom')
        @include('home.chat')
    </div>

    <!--<a href="#" id="toTop" style="display: inline;"><span id="toTopHover" style="opacity: 0;"></span></a>-->

    @livewireScripts
</body>
</html>
