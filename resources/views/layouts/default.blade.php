<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Selección de muestras al azar de acuerdo con Norma Chilena NCh43">
    <meta name="keywords" content="Selección de muestras al azar, nch43, muestras">
    <meta name="author" content="mez">
    
    <title>@yield('title')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">


    <!-- Styles -->
    <style>
        /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */
        html {
            line-height: 1.15;
            -webkit-text-size-adjust: 100%
        }

        body {
            margin: 0
        }

        a {
            background-color: transparent
        }

        [hidden] {
            display: none
        }

        html {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji;
            line-height: 1.5
        }

        *,
        :after,
        :before {
            box-sizing: border-box;
            border: 0 solid #e2e8f0
        }

        a {
            color: inherit;
            text-decoration: inherit
        }

        svg,
        video {
            display: block;
            vertical-align: middle;
        }

        video {
            max-width: 100%;
            height: auto
        }

        .bg-white {
            --bg-opacity: 1;
            background-color: #fff;
            background-color: rgba(255, 255, 255, var(--bg-opacity))
        }

        .bg-gray-100 {
            --bg-opacity: 1;
            background-color: #f7fafc;
            background-color: rgba(247, 250, 252, var(--bg-opacity))
        }

        .border-gray-200 {
            --border-opacity: 1;
            border-color: #edf2f7;
            border-color: rgba(237, 242, 247, var(--border-opacity))
        }

        .border-t {
            border-top-width: 1px
        }

        .flex {
            display: flex
        }

        .grid {
            display: grid
        }

        .hidden {
            display: none
        }

        .items-center {
            align-items: center
        }

        .justify-center {
            justify-content: center
        }

        .font-semibold {
            font-weight: 600
        }

        .h-5 {
            height: 1.25rem
        }

        .h-8 {
            height: 2rem
        }

        .h-16 {
            height: 4rem
        }

        .text-sm {
            font-size: .875rem
        }

        .text-lg {
            font-size: 1.125rem
        }

        .leading-7 {
            line-height: 1.75rem
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto
        }

        .ml-1 {
            margin-left: .25rem
        }

        .mt-2 {
            margin-top: .5rem
        }

        .mr-2 {
            margin-right: .5rem
        }

        .ml-2 {
            margin-left: .5rem
        }

        .mt-4 {
            margin-top: 1rem
        }

        .ml-4 {
            margin-left: 1rem
        }

        .mt-8 {
            margin-top: 2rem
        }

        .ml-12 {
            margin-left: 3rem
        }

        .-mt-px {
            margin-top: -1px
        }

        .max-w-6xl {
            max-width: 72rem
        }

        .min-h-screen {
            min-height: 100vh
        }

        .overflow-hidden {
            overflow: hidden
        }

        .p-6 {
            padding: 1.5rem
        }

        .py-4 {
            padding-top: 1rem;
            padding-bottom: 1rem
        }

        .px-6 {
            padding-left: 1.5rem;
            padding-right: 1.5rem
        }

        .pt-8 {
            padding-top: 2rem
        }

        .fixed {
            position: fixed
        }

        .relative {
            position: relative
        }

        .top-0 {
            top: 0
        }

        .right-0 {
            right: 0
        }

        .shadow {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1), 0 1px 2px 0 rgba(0, 0, 0, .06)
        }

        .text-center {
            text-align: center
        }

        .text-gray-200 {
            --text-opacity: 1;
            color: #edf2f7;
            color: rgba(237, 242, 247, var(--text-opacity))
        }

        .text-gray-300 {
            --text-opacity: 1;
            color: #e2e8f0;
            color: rgba(226, 232, 240, var(--text-opacity))
        }

        .text-gray-400 {
            --text-opacity: 1;
            color: #cbd5e0;
            color: rgba(203, 213, 224, var(--text-opacity))
        }

        .text-gray-500 {
            --text-opacity: 1;
            color: #a0aec0;
            color: rgba(160, 174, 192, var(--text-opacity))
        }

        .text-gray-600 {
            --text-opacity: 1;
            color: #718096;
            color: rgba(113, 128, 150, var(--text-opacity))
        }

        .text-gray-700 {
            --text-opacity: 1;
            color: #4a5568;
            color: rgba(74, 85, 104, var(--text-opacity))
        }

        .text-gray-900 {
            --text-opacity: 1;
            color: #1a202c;
            color: rgba(26, 32, 44, var(--text-opacity))
        }

        .underline {
            text-decoration: underline
        }

        .antialiased {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale
        }

        .w-5 {
            width: 1.25rem
        }

        .w-8 {
            width: 2rem
        }

        .w-auto {
            width: auto
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr))
        }

        @media (min-width:640px) {
            .sm\:rounded-lg {
                border-radius: .5rem
            }

            .sm\:block {
                display: block
            }

            .sm\:items-center {
                align-items: center
            }

            .sm\:justify-start {
                justify-content: flex-start
            }

            .sm\:justify-between {
                justify-content: space-between
            }

            .sm\:h-20 {
                height: 5rem
            }

            .sm\:ml-0 {
                margin-left: 0
            }

            .sm\:px-6 {
                padding-left: 1.5rem;
                padding-right: 1.5rem
            }

            .sm\:pt-0 {
                padding-top: 0
            }

            .sm\:text-left {
                text-align: left
            }

            .sm\:text-right {
                text-align: right
            }
        }

        @media (min-width:768px) {
            .md\:border-t-0 {
                border-top-width: 0
            }

            .md\:border-l {
                border-left-width: 1px
            }

            .md\:grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        @media (min-width:1024px) {
            .lg\:px-8 {
                padding-left: 2rem;
                padding-right: 2rem
            }
        }

        @media (prefers-color-scheme:dark) {
            .dark\:bg-gray-800 {
                --bg-opacity: 1;
                background-color: #2d3748;
                background-color: rgba(45, 55, 72, var(--bg-opacity))
            }

            .dark\:bg-gray-900 {
                --bg-opacity: 1;
                background-color: #1a202c;
                background-color: rgba(26, 32, 44, var(--bg-opacity))
            }

            .dark\:border-gray-700 {
                --border-opacity: 1;
                border-color: #4a5568;
                border-color: rgba(74, 85, 104, var(--border-opacity))
            }

            .dark\:text-white {
                --text-opacity: 1;
                color: #fff;
                color: rgba(255, 255, 255, var(--text-opacity))
            }

            .dark\:text-gray-400 {
                --text-opacity: 1;
                color: #cbd5e0;
                color: rgba(203, 213, 224, var(--text-opacity))
            }

            .dark\:text-gray-500 {
                --tw-text-opacity: 1;
                color: #6b7280;
                color: rgba(107, 114, 128, var(--tw-text-opacity))
            }
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>

    {{-- Aquí se insertarán los estilos adicionales --}}
    @stack('styles')

    <!-- jQuery y Select2 desde CDN -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    
</head>

<body class="bg-gray-100">

    <header >
            <nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('home') }}">
                                    <img src="{{ asset('logo.png') }}" alt="logo" width="100">
                                </a>
                            </div>
            
                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                                    Inicio
                                </x-nav-link>
                            </div>

                            <div class="hidden sm:flex sm:ms-6 sm:items-center">
                                <div x-data="{ open: false }" >
                                    <button @click="open = !open"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        OCP
                                        <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 7l5 5 5-5"/>
                                        </svg>
                                    </button>

                                    <div x-show="open"
                                        @click.away="open = false"
                                        x-transition
                                        class="absolute z-50 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                        <div class="py-1">
                                            <x-dropdown-link :href="route('documents.index')">Documentos</x-dropdown-link>
                                            <x-dropdown-link :href="route('activities.index')">Actividades</x-dropdown-link>
                                            <x-dropdown-link :href="route('aplication-documents.index')">Documentos de aplicación</x-dropdown-link>
                                            <x-dropdown-link :href="route('processes.create')">Crear proceso</x-dropdown-link>
                                            <x-dropdown-link :href="route('processes.index')">Listado proceso</x-dropdown-link>
                                            <x-dropdown-link :href="route('aplication-documents.index')">Actividades por solicitud</x-dropdown-link>
                                            <div class="hidden sm:flex sm:items-center">
                                                <div x-data="{ open: false }">
                                                    <button @click="open = !open"
                                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                        Certificados
                                                        <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M5 7l5 5 5-5" />
                                                        </svg>
                                                    </button>

                                                    <div x-show="open"
                                                        @click.away="open = false"
                                                        x-transition
                                                        class="absolute z-50 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                                        <div class="py-1">
                                                            <x-dropdown-link :href="route('certificates.index')">Listado</x-dropdown-link>
                                                            <x-dropdown-link :href="route('certificates.create')">Nuevo certificado</x-dropdown-link>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="hidden sm:flex sm:items-center">
                                                <div x-data="{ open: false }">
                                                    <button @click="open = !open"
                                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                        Productos
                                                        <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M5 7l5 5 5-5" />
                                                        </svg>
                                                    </button>

                                                    <div x-show="open"
                                                        @click.away="open = false"
                                                        x-transition
                                                        class="absolute z-50 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                                        <div class="py-1">
                                                            <x-dropdown-link :href="route('product-models.index')">Listado</x-dropdown-link>
                                                            <x-dropdown-link :href="route('product-models.create')">Nuevo producto</x-dropdown-link>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="hidden sm:flex sm:items-center">
                                                <div x-data="{ open: false }">
                                                    <button @click="open = !open"
                                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                        Certificados tipo
                                                        <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M5 7l5 5 5-5" />
                                                        </svg>
                                                    </button>

                                                    <div x-show="open"
                                                        @click.away="open = false"
                                                        x-transition
                                                        class="absolute z-50 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                                        <div class="py-1">
                                                            <x-dropdown-link :href="route('type-certificates.index')">Listado</x-dropdown-link>
                                                            <x-dropdown-link :href="route('type-certificates.create')">Crear</x-dropdown-link>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="hidden sm:flex sm:items-center">
                                                <div x-data="{ open: false }">
                                                    <button @click="open = !open"
                                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                        Certificados controles
                                                        <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M5 7l5 5 5-5" />
                                                        </svg>
                                                    </button>

                                                    <div x-show="open"
                                                        @click.away="open = false"
                                                        x-transition
                                                        class="absolute z-50 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                                        <div class="py-1">
                                                            <x-dropdown-link :href="route('type-certificates.index')">Listado</x-dropdown-link>
                                                            <x-dropdown-link :href="route('type-certificates.create')">Crear</x-dropdown-link>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="hidden sm:flex sm:ms-6 sm:items-center">
                                <div x-data="{ open: false }" >
                                    <button @click="open = !open"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        Configuraciones
                                        <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 7l5 5 5-5"/>
                                        </svg>
                                    </button>

                                    <div x-show="open"
                                        @click.away="open = false"
                                        x-transition
                                        class="absolute z-50 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                        <div class="py-1">
                                            <x-dropdown-link :href="route('characteristic-templates.index')">Asiganr caracterísitcas técnicas a categorías</x-dropdown-link>
                                            <x-dropdown-link :href="route('protocols.assign')">Asignar protocolos a categorías</x-dropdown-link>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(Auth::user()->role_id != 3)
                                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                    <x-nav-link :href="route('certificates.index')" :active="request()->routeIs('certificates.index')">
                                    Sistema 2
                                    </x-nav-link>
                                </div>
                            @endif
                        </div>
            
                        @if(Auth::user() != null)
                        <!-- Settings Dropdown -->
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>{{ Auth::user()->name }}</div>
            
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
            
                                <x-slot name="content">
                                <!--
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Profile') }}
                                    </x-dropdown-link>
                                -->
            
                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
            
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                        @endif
            
                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            
                <!-- Responsive Navigation Menu -->

            </nav>
      </header>

    <div class="w-full px-4">
        @yield('content')
    </div>


    <footer class="bg-gray-100 border-t py-4 mt-6 shadow-inner">
        <div class="container mx-auto text-center text-sm text-gray-600 px-4">
          {{ date('Y') }} | Sical Ingenieros S.A.
        </div>
      </footer>
    {{-- Aquí se insertarán los scripts adicionales --}}
    @stack('scripts') 
</body>
</html>
