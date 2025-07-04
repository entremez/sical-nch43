@extends('layouts.default')

@section('title', 'Modelos de producto')

@section('content')
<div class="max-w-5xl mx-auto mt-8">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Modelos de producto</h1>
        <a href="{{ route('product-models.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Nuevo modelo</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <table class="w-full border bg-white rounded shadow">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 text-left">Modelo</th>
                <th class="p-2 text-left">Marca</th>
                <th class="p-2 text-left">Denominación</th>
                <th class="p-2 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productModels as $model)
                <tr class="border-t">
                    <td class="p-2">{{ $model->name }}</td>
                    <td class="p-2">{{ $model->brand->name ?? '-' }}</td>
                    <td class="p-2">{{ $model->designation->name ?? '-' }}</td>
                    <td class="p-2 space-x-2">
                        <a href="{{ route('product-models.edit', $model) }}" class="text-yellow-600">Editar</a>
                        <form action="{{ route('product-models.destroy', $model) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este modelo?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-4 text-center text-gray-500">No hay modelos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
