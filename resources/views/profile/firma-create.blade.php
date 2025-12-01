@extends('layouts.default')

@section('title', 'Agregar nueva firma')

@section('content')

<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">

    <h1 class="text-2xl font-semibold mb-6">Agregar firma para: {{ $user->name }}</h1>

    {{-- Mensajes de error --}}
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-700 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('profile.firma.store', $user->id) }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-5">

        @csrf

        {{-- Iniciales --}}
        <div>
            <label class="block mb-1 font-semibold text-gray-700">
                Iniciales
            </label>
            <input type="text" name="initials"
                   class="w-full border rounded p-2"
                   maxlength="5"
                   placeholder="Ej: CI"
                   required>
        </div>

        {{-- Upload de imagen --}}
        <div>
            <label class="block mb-1 font-semibold text-gray-700">
                Imagen de la firma
            </label>

            <input type="file"
                   name="file"
                   accept="image/*"
                   class="w-full border rounded p-2"
                   onchange="previewImage(event)"
                   required>

            <div class="mt-3">
                <img id="preview" class="h-32 border rounded hidden">
            </div>
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('profile.edit') }}"
               class="px-3 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                ← Volver
            </a>

            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Guardar firma
            </button>
        </div>

    </form>
</div>

{{-- Script para vista previa --}}
<script>
    function previewImage(event) {
        const img = document.getElementById('preview');
        img.src = URL.createObjectURL(event.target.files[0]);
        img.classList.remove('hidden');
    }
</script>

@endsection
