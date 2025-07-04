
@extends('layouts.default')

@section('title', 'Procesos ingresados')

@section('content')


    <div class="max-w-6xl mx-auto mt-8">



        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Procesos</h1>
            <div class="flex gap-4">
                <form method="GET" action="{{ route('processes.index') }}">
                    <label for="estado" class="text-sm mr-2">Estado:</label>
                    <select name="estado" id="estado" onchange="this.form.submit()" class="border rounded px-2 py-1">
                        <option value="">Todos</option>
                        <option value="terminado" {{ request('estado') === 'terminado' ? 'selected' : '' }}>Terminado</option>
                        <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    </select>
                </form>
                <a href="{{ route('processes.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Nuevo proceso
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif


<table class="w-full border bg-white rounded shadow">
    <thead class="bg-gray-100 text-left">
        <tr>
            <th class="p-2">ID</th>
            <th class="p-2">Nombre</th>
            <th class="p-2">Documento de aplicación</th>
            <th class="p-2">Estado</th> <!-- Nueva columna -->
            <th class="p-2">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($processes as $process)
            @php
                $total = $process->planActivities->count();
                $completadas = $process->planActivities->where('is_completed', true)->count();
            @endphp
            <tr class="border-t">
                <td class="p-2">{{ $process->id }}</td>
                <td class="p-2">{{ $process->name }}</td>
                <td class="p-2">{{ $process->aplicationDocument->name ?? '-' }}</td>
                <td class="p-2">
                    @if($total === 0)
                        <span class="text-gray-500">Sin actividades</span>
                    @elseif($completadas === $total)
                        <span class="text-green-600 font-semibold">Terminado</span>
                        <span class="text-sm text-gray-600">({{ $completadas }} / {{ $total }})</span>
                    @else
                        <span class="text-yellow-600 font-semibold">Pendiente</span>
                        <span class="text-sm text-gray-600">({{ $completadas }} / {{ $total }})</span>
                    @endif
                </td>
                <td class="p-2 space-x-2">
                    <a href="{{ route('processes.show', $process) }}" class="text-blue-600">Ver</a>
                    <a href="{{ route('processes.edit', $process) }}" class="text-yellow-600">Editar</a>
                    @if($process->planActivities->count() > 0 && $process->planActivities->every(fn($a) => $a->is_completed))
                        <a href="{{ route('certificate.create', ['process' => $process->id]) }}" class="text-green-600">
                            Emitir certificado
                        </a>
                    @endif
                    <form action="{{ route('processes.destroy', $process) }}" method="POST" class="inline"
                          onsubmit="return confirm('¿Eliminar este proceso?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach


                @if($processes->isEmpty())
                    <tr>
                        <td colspan="4" class="p-4 text-center text-gray-500">No hay procesos registrados.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

@endsection