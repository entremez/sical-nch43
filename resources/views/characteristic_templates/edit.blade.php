@extends('layouts.default')

@section('title', 'Editar Plantilla')

@section('content')
<div class="max-w-xl mx-auto mt-8">
    <h1 class="text-xl font-bold mb-4">Editar Plantilla</h1>

    <form action="{{ route('characteristic-templates.update', $template) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block">Nombre</label>
            <input type="text" name="nombre" class="w-full border rounded px-3 py-2" value="{{ $template->nombre }}" required>
        </div>

        <div class="mb-4">
            <label class="block">Categoría</label>
            <select name="category_id" class="w-full border rounded px-3 py-2" required>
                <option value="">Seleccionar...</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $category->id == $template->category_id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">Actualizar</button>
    </form>
</div>
@endsection
