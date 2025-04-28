<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="copyright" content="Copyright (c) 2024 Grzegorz Lis">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Decorative' }}</title>
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700&display=swap" rel="stylesheet">
    <style>
        @media (max-width: 768px) {
            .flex-col-mobile {
                flex-direction: column;
            }
            .w-full-mobile {
                width: 100%;
            }
            .mb-4-mobile {
                margin-bottom: 1rem;
            }
        }

        @media print {
            @page { margin: 0; }
            body { margin: 1.6cm; }
        }
    </style>

    <!-- Scripts -->
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>
<body class="font-lato">
    <div class="min-h-screen bg-gray-100">
        
        <main class="bg-gray-100">
            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
</body>
</html>