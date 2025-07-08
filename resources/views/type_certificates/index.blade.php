@extends('layouts.default')

@section('title', 'Certificados de Tipo')

@section('content')
<div class="max-w-5xl mx-auto mt-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Certificados de Tipo</h1>
        <a href="{{ route('type-certificates.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Nuevo certificado</a>
    </div>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <table class="w-full border bg-white shadow rounded">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="p-2">N° Certificado</th>
                <th class="p-2">Cliente</th>
                <th class="p-2">Fábrica</th>
                <th class="p-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($typeCertificates as $cert)
                <tr class="border-t">
                    <td class="p-2">{{ $cert->certificate }}</td>
                    <td class="p-2">{{ $cert->client }}</td>
                    <td class="p-2">{{ $cert->factory }}</td>
                    <td class="p-2 space-x-2">
                        <a href="{{ route('type-certificates.show', $cert) }}" class="text-blue-600">Ver</a>
                        <a href="{{ route('type-certificates.edit', $cert) }}" class="text-yellow-600">Editar</a>
                        <form action="{{ route('type-certificates.destroy', $cert) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este certificado?')">
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
