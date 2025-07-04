@extends('layouts.default')
@section('title', 'Listado de certificados')

@section('content')
<div class="max-w-5xl mx-auto mt-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Certificados</h1>
        <a href="{{ route('certificates.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Nuevo certificado</a>
    </div>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <table class="w-full border bg-white shadow rounded">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="p-2">N°</th>
                <th class="p-2">Cliente</th>
                <th class="p-2">Producto</th>
                <th class="p-2">Modelos</th>
                <th class="p-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($certificates as $certificate)
                <tr class="border-t align-top">
                    <td class="p-2">{{ $certificate->certificado }}</td>
                    <td class="p-2">{{ $certificate->cliente }}</td>

                    {{-- Producto (muestra la primera denominación si hay al menos un modelo asociado) --}}
                    <td class="p-2">
                        {{ optional($certificate->productModels->first()?->designation)->name ?? '-' }}
                    </td>

                    {{-- Modelos: uno por línea --}}
                    <td class="p-2 whitespace-pre-line">{{ $certificate->model_list }}</td>


                    <td class="p-2 space-x-2">
                        <a href="{{ route('certificates.show', $certificate) }}" class="text-blue-600">Ver</a>
                        <a href="{{ route('certificates.edit', $certificate) }}" class="text-yellow-600">Editar</a>
                        <form action="{{ route('certificates.destroy', $certificate) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
