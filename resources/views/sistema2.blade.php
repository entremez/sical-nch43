@extends('layouts.default')

@section('title', 'Certificados sistema 2')

@section('content')

<h2 class="text-2xl font-bold text-gray-800 mb-4 mt-8">Certificados sistema 2</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
    @foreach ($certificados as $certificado)
        <div class="bg-white border rounded-2xl shadow hover:shadow-lg transition overflow-hidden">
            <div class="bg-blue-100 px-6 py-4">
                <h2 class="text-lg font-semibold text-blue-800">
                    Certificado {{ $certificado->certificado }}
                </h2>
                <!--
                <p class="text-sm text-blue-700">
                    Fecha: <span class="font-medium">{{ $certificado->fecha }}</span>
                </p>
                -->
            </div>
            <div class="p-6 space-y-2 text-sm text-gray-700">
                <p><strong>QR:</strong> {{ $certificado->qr }}</p>
                <p><strong>Denominación:</strong> {{ $certificado->producto }}</p>
                <p><strong>Fabricante:</strong> {{ $certificado->fabrica }}</p>
                <p><strong>Dir. fábrica:</strong> {{ $certificado->direccion_fabrica }}</p>
                <p><strong>Dir. bodega:</strong> {{ $certificado->direccion }}</p>
                <p><strong>Marca:</strong> {{ $certificado->marca }}</p>
                <p><strong>Modelo:</strong> {{ $certificado->modelo }}</p>
                <p><strong>Contacto:</strong> {{ $certificado->contacto }}</p>
            </div>
            <div class="px-6 pb-4 pt-2">
                <a href="{{ route('sistema2', $certificado->certificado) }}"
                   class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                    Generar muestreo
                </a>
            </div>
        </div>
    @endforeach
</div>


@endsection
