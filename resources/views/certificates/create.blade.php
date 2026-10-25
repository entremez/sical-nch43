@extends('layouts.default')

@section('title', 'Nuevo certificado')

@section('content')
<div class="flex justify-center mt-4">
    <form action="{{ route('certificates.store') }}" method="POST" class="w-full md:w-1/2 bg-white p-6 rounded-lg shadow">
        @csrf

        <h1 class="text-2xl font-bold mb-4">Nuevo certificado</h1>

        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <p class="mb-4 text-sm text-gray-600">Los campos marcados con * son obligatorios.</p>

        <div class="mb-4">
            <label class="block font-medium mb-1">Certificado N° *</label>
            <input type="text" name="certificado" value="{{ old('certificado') }}" placeholder="E-022-03-12345" required class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">QR</label>
            <input type="text" name="qr" value="{{ old('qr') }}" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Cliente *</label>
            <input type="text" name="cliente" value="{{ old('cliente') }}" required class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Contacto</label>
            <input type="text" name="contacto" value="{{ old('contacto') }}" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Dirección</label>
            <input type="text" name="direccion" value="{{ old('direccion') }}" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Fábrica</label>
            <input type="text" name="fabrica" value="{{ old('fabrica') }}" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Dirección fábrica</label>
            <input type="text" name="direccion_fabrica" value="{{ old('direccion_fabrica') }}" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Producto *</label>
            <input type="text" name="producto" value="{{ old('producto') }}" required class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Marca(s) *</label>
            <input type="text" name="marca" value="{{ old('marca') }}" required class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Modelo(s) *</label>
            <input type="text" name="modelo" value="{{ old('modelo') }}" required class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Activo</label>
            <select name="active" class="w-full border rounded p-2">
                <option value="1" {{ old('active', '1') == '1' ? 'selected' : '' }}>Sí</option>
                <option value="0" {{ old('active') == '0' ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="text-center">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Crear certificado</button>
        </div>
    </form>
</div>
@endsection
