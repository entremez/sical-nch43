@extends('layouts.default')

@section('title', 'Actividades de evaluación - Listado')

@section('content')

    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-4">Actividades de evaluación</h1>

        @if(session('success'))
            <div class="bg-green-200 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <a href="{{ route('activities.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Crear nueva actividad</a>

        <table class="w-full mt-4 table-auto border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Nombre</th>
                    <th class="px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activities as $doc)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $doc->id }}</td>
                        <td class="px-4 py-2">{{ $doc->name }}</td>
                        <td class="px-4 py-2 flex gap-2">
                            <a href="{{ route('activities.show', $doc) }}" class="text-blue-500">Ver</a>
                            <a href="{{ route('activities.edit', $doc) }}" class="text-yellow-500">Editar</a>
                            <form action="{{ route('activities.destroy', $doc) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
                                @csrf @method('DELETE')
                                <button class="text-red-500">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection