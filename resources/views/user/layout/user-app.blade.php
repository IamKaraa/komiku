<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'KOMIKU' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/header.css', 'resources/js/header.js'])

    @stack('styles')
</head>
<body class="antialiased" style="background-color: #f5f5f5;">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        @include('user.layout.header')

        <!-- Main Content -->
        <main class="flex-1">
            @yield('content')
        </main>

        <!-- Footer -->
        @include('user.layout.footer')
    </div>

    @stack('scripts')
</body>
</html>
