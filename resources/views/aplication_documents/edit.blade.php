@extends('layouts.default')

@section('title', 'Grupos de Actividades - Editar')

@section('content')

    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-4">{{ isset($document) ? 'Editar' : 'Crear' }} Grupos</h1>

        <form action="{{ isset($document) ? route('documents.update', $document) : route('documents.store') }}" method="POST" class="space-y-4">
            @csrf
            @if(isset($document)) @method('PUT') @endif

            <div>
                <label class="block">Nombre:</label>
                <input type="text" name="name" value="{{ old('name', $document->name ?? '') }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block">Descripción:</label>
                <textarea name="description" class="w-full border rounded px-3 py-2">{{ old('description', $document->description ?? '') }}</textarea>
            </div>

            <button class="bg-green-500 text-white px-4 py-2 rounded">Guardar</button>
        </form>
    </div>


@endsection