<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Te Acerco Salud</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "secondary": "#2ECC71",
                        "accent": "#F39C12",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                        "success": "#28A745",
                        "warning": "#FFC107",
                        "danger": "#DC3545",
                        "neutral-text": "#617589",
                        "neutral-text-dark": "#90a4b8",
                        "body-text": "#111418",
                        "body-text-dark": "#f0f2f4",
                        "border-light": "#f0f2f4",
                        "border-dark": "#2a3b4c",
                        "card-light": "#ffffff",
                        "card-dark": "#1a2734",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24
        }
    </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-body-text dark:text-body-text-dark"
    x-data="adminSpaApp()" @popstate.window="handlePopState" @spa-navigate.window="navigateTo($event.detail.url, $event.detail.options)">
    <div class="relative flex h-auto min-h-screen w-full flex-col overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">

            @include('components.topbar', ['user' => auth()->user(), 'type' => 'admin'])

            <div class="flex flex-1">
                @include('components.sidebar', [
                    'user' => auth()->user(),
                    'type' => 'admin',
                    'currentRoute' => Route::currentRouteName(),
                    'useSpa' => true,
                ])

                <main class="flex-1 p-4 sm:p-6 lg:p-10">
                    @include('components.spa-loader')

                    <div id="spa-content" class="mx-auto max-w-7xl">
                        @yield('spa-content')
                    </div>
                </main>
            </div>
        </div>
    </div>


</body>

</html>
