@extends('layouts.default')

@section('title', 'Movimientos (rápido)')

@section('content')
<div 
    x-data="{ mode: null }" 
    class="min-h-[calc(100vh-4rem)] w-full flex flex-col items-stretch justify-center gap-4 p-4 md:p-6"
>
    {{-- Encabezado --}}
    <div class="text-center mb-2">
        <h1 class="text-2xl font-bold">Movimientos rápidos</h1>
        <p class="text-gray-600 text-sm">Elige una acción</p>
    </div>

    {{-- Versión completa visible en md+ --}}
    <div class="hidden md:flex justify-center mb-2">
        <a href="{{ route('movements.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded">
            Ir a la versión completa
        </a>
    </div>

    {{-- Zona móvil: botones gigantes --}}
    <div class="md:hidden flex-1 grid grid-rows-2 gap-4">

        {{-- BLOQUE RECIBIR --}}
        <div class="relative">
            <template x-if="mode === null">
                <button 
                    @click="mode = 'receive'"
                    class="w-full h-full min-h-44 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-2xl shadow flex items-center justify-center text-xl p-6 transition-all"
                >
                    Recibir con guía (externo)
                </button>
            </template>

            <template x-if="mode === 'receive'">
                <div class="grid grid-cols-2 gap-3 h-full">
                    <a href="{{ route('movements.create', ['type' => 'ingreso_externo']) }}"
                       class="h-full bg-blue-700 hover:bg-blue-800 text-white font-semibold rounded-2xl shadow flex items-center justify-center text-center text-lg p-4">
                        Recibir desde cliente externo
                    </a>
                    <a href="{{ route('movements.create', ['type' => 'ingreso_interno']) }}"
                       class="h-full bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-2xl shadow flex items-center justify-center text-center text-lg p-4">
                        Recibir desde laboratorio / OCP
                    </a>

                    {{-- Botón volver (ocupa toda la fila) --}}
                    <button @click="mode = null"
                            class="col-span-2 mt-2 h-12 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl">
                        ← Volver
                    </button>
                </div>
            </template>
        </div>

        {{-- BLOQUE ENTREGAR --}}
        <div class="relative">
            <template x-if="mode === null">
                <button 
                    @click="mode = 'deliver'"
                    class="w-full h-full min-h-44 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-2xl shadow flex items-center justify-center text-xl p-6 transition-all"
                >
                    Entregar sin guía (interno)
                </button>
            </template>

            <template x-if="mode === 'deliver'">
                <div class="grid grid-cols-2 gap-3 h-full">
                    <a href="{{ route('movements.create', ['type' => 'despacho_interno']) }}"
                       class="h-full bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-2xl shadow flex items-center justify-center text-center text-lg p-4">
                        Entregar sin guía (interno)
                    </a>
                    <a href="{{ route('movements.create', ['type' => 'despacho_externo']) }}"
                       class="h-full bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-2xl shadow flex items-center justify-center text-center text-lg p-4">
                        Entregar con guía (externo)
                    </a>

                    {{-- Botón volver (ocupa toda la fila) --}}
                    <button @click="mode = null"
                            class="col-span-2 mt-2 h-12 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl">
                        ← Volver
                    </button>
                </div>
            </template>
        </div>
    </div>

    {{-- Link a versión completa en mobile también, por si se requiere --}}
    <div class="md:hidden mt-4 text-center">
        <a href="{{ route('movements.index') }}" class="underline text-sm text-gray-600">
            Ir a la versión completa
        </a>
    </div>
</div>
@endsection

@section('scripts')
    {{-- Alpine.js si no lo tienes cargado globalmente --}}
    <script src="//unpkg.com/alpinejs" defer></script>
@endsection
