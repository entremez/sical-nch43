@extends('layouts.default')
@section('title', 'Detalle del certificado')
@section('content')
<div class="max-w-4xl mx-auto mt-6 space-y-6">

    <h1 class="text-2xl font-bold">Certificado {{ $certificate->certificado }}</h1>

    <div class="bg-white p-4 rounded shadow space-y-2">
        <p><strong>Cliente:</strong> {{ $certificate->cliente }}</p>
        <p><strong>Contacto:</strong> {{ $certificate->contacto }}</p>
        <p><strong>Dirección:</strong> {{ $certificate->direccion }}</p>
        <p><strong>Fábrica:</strong> {{ $certificate->fabrica }}</p>
        <p><strong>Dirección fábrica:</strong> {{ $certificate->direccion_fabrica }}</p>
        <p><strong>Área:</strong> {{ $certificate->area }}</p>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-3">Modelos asociados</h2>

        <table class="w-full border mb-4">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2">Marca</th>
                    <th class="p-2">Producto</th>
                    <th class="p-2">Modelo</th>
                    <th class="p-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($certificate->productModels as $model)
                    <tr class="border-t">
                        <td class="p-2">{{ $model->brand->name }}</td>
                        <td class="p-2">{{ $model->designation->name }}</td>
                        <td class="p-2">{{ $model->name }}</td>
                        <td class="p-2 space-x-2">
                            <form action="{{ route('certificates.detach-model', [$certificate, $model]) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button class="text-red-600 text-sm">Eliminar</button>
                            </form>
                            <a href="{{ route('product-models.edit', $model) }}" class="text-yellow-600 text-sm">Editar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <form action="{{ route('certificates.attach-model', $certificate) }}" method="POST" class="space-y-2">
            @csrf
            <label class="block text-sm">Agregar modelo existente</label>
            <select name="product_model_id" class="w-full border rounded p-1">
                @foreach($availableModels as $model)
                    <option value="{{ $model->id }}">
                        {{ $model->brand->name }} - {{ $model->designation->name }} - {{ $model->name }}
                    </option>
                @endforeach
            </select>
            <button class="bg-blue-600 text-white px-3 py-1 rounded text-sm">Agregar modelo</button>
        </form>
    </div>

    <a href="{{ route('certificates.index') }}" class="text-blue-600">← Volver al listado</a>
</div>
@endsection
