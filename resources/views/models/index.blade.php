@extends('layouts.default')

@section('title', 'Modelos de productos')

@section('content')

<div class="flex justify-center mt-10">
  <div class="w-full md:w-2/3 bg-white p-6 rounded-lg shadow">

    <h1 class="text-3xl font-bold mb-6">Modelos registrados</h1>

    <div class="mb-6 flex justify-end">
      <a href="{{ route('models.create') }}"
         class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
        Nuevo modelo
      </a>
    </div>

    <form method="GET" action="{{ route('models.index') }}" class="mb-4">
      <div class="flex items-center gap-4">
        <input type="text" name="search" placeholder="Buscar modelo..."
              value="{{ request('search') }}"
              class="px-3 py-2 border border-gray-300 rounded w-full md:w-1/2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
          Buscar
        </button>
      </div>
    </form>

    <table class="table-auto w-full text-left border-collapse">
      <thead>
        <tr class="bg-gray-100">
          <th class="px-4 py-2">Nombre</th>
          <th class="px-4 py-2">Marca</th>
          <th class="px-4 py-2">Denominación</th>
          <th class="px-4 py-2">Descripción</th>
          <th class="px-4 py-2">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($models as $model)
          <tr class="border-t">
            <td class="px-4 py-2">{{ $model->name }}</td>
            <td class="px-4 py-2">{{ $model->brand->name }}</td>
            <td class="px-4 py-2">{{ $model->designation->name }}</td>
            <td class="px-4 py-2">{{ $model->description ?? '—' }}</td>
            <td class="px-4 py-2">
              <div class="flex gap-2">
                <a href="{{ route('models.edit', $model->id) }}" class="text-blue-600 hover:underline">Editar</a>
                <form action="{{ route('models.destroy', $model->id) }}" method="POST"
                      onsubmit="return confirm('¿Seguro que quieres eliminar este modelo?')">
                  @csrf
                  @method('DELETE')
                  <button class="text-red-600 hover:underline">Eliminar</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center py-4 text-gray-500">No hay modelos registrados.</td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <div class="mt-4">
      {{ $models->links() }}
    </div>

  </div>
</div>

@endsection
