@extends('layouts.default')

@section('title', 'Editar certificado')

@section('content')
<div class="flex justify-center mt-4">
    <form action="{{ route('certificates.update', $certificate->id) }}" method="POST" class="w-full md:w-1/2 bg-white p-6 rounded-lg shadow">
        @csrf
        @method('PUT')

        <h1 class="text-2xl font-bold mb-4">Detalles del certificado Nº {{ $certificate->certificado }} (QR {{ $certificate->qr }})</h1>

        <div class="mb-4">
            <label class="block font-medium mb-1">Cliente</label>
            <input type="text" name="cliente" value="{{ $certificate->cliente }}" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Contacto</label>
            <input type="text" name="contacto" value="{{ $certificate->contacto }}" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Dirección</label>
            <input type="text" name="direccion" value="{{ $certificate->direccion }}" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Fábrica</label>
            <input type="text" name="fabrica" value="{{ $certificate->fabrica }}" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Dirección fábrica</label>
            <input type="text" name="direccion_fabrica" value="{{ $certificate->direccion_fabrica }}" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Producto</label>
            <input type="text" name="producto" value="{{ $certificate->producto }}" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Marca(s)</label>
            <input type="text" name="marca" value="{{ $certificate->marca }}" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Modelo(s)</label>
            <input type="text" name="modelo" value="{{ $certificate->modelo }}" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Activo</label>
            <select name="active" class="w-full border rounded p-2">
                <option value="1" {{ $certificate->active == 1 ? 'selected' : '' }}>Sí</option>
                <option value="0" {{ $certificate->active == 0 ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="text-center">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Guardar cambios</button>
        </div>
    </form>
</div>
@endsection

