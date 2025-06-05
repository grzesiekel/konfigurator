<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="copyright" content="Copyright (c) 2024 Grzegorz Lis">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/img/favicon/favicon-t.ico" type="image/x-icon">
    <title>{{ config('app.title') }}</title>
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700&display=swap" rel="stylesheet">
    <style>
        .loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    
    .loader {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
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