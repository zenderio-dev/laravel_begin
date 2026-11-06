<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @include('partials.header')
    <main class="container" id="main">
        @if (session('success'))
            <div class="notice success" role="status">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>
    @include('partials.footer')
</body>
</html>
