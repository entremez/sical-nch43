@extends('layouts.default')

@section('title', 'Registrar nuevo modelo de producto')

@section('content')

<div class="flex justify-center mt-10">
  <div class="w-full md:w-1/2 bg-white p-6 rounded-lg shadow">

    @if(session('success'))
      <div class="mb-4 bg-green-500 text-white px-4 py-3 rounded-lg">
        {{ session('success') }}
      </div>
    @endif

    @if($errors->any())
      <div class="mb-4 bg-red-500 text-white px-4 py-3 rounded-lg">
        <ul class="list-disc list-inside">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <h1 class="text-3xl font-bold mb-6">Registrar nuevo modelo</h1>

    <form action="{{ route('models.store') }}" method="POST">
      @csrf
      <!-- Denominación -->
      <div class="mb-4">
        <label for="designation_id" class="block text-gray-700 font-medium mb-2">Denominación</label>
        <div id="destination-add" class="text-sm mt-2 hidden">
          <span class="text-gray-500">¿No encontraste la denominación?</span>
          <button type="button" class="text-blue-600 hover:underline ml-2">Agregar nueva denominación</button>
        </div>
        <select id="designation_id" name="designation_id"
          class="select2 w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </select>
      </div>

      <!-- Marca -->
      <div class="mb-4">
        <label for="brand_id" class="block text-gray-700 font-medium mb-2">Marca</label>        
        <div id="brand-add" class="text-sm mt-2 hidden">
          <span class="text-gray-500">¿No encontraste la marca?</span>
          <button type="button" class="text-blue-600 hover:underline ml-2">Agregar nueva marca</button>
        </div>
        <select id="brand_id" name="brand_id"
                class="select2 w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </select>

      </div>

      <div class="mb-4">
        <label for="name" class="block text-gray-700 font-medium mb-2">Nombre del modelo</label>
        <input type="text" name="name" id="name"
               class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
               placeholder="Ej: PL-123" required>
      </div>

      <div class="mb-6">
        <label for="description" class="block text-gray-700 font-medium mb-2">Descripción</label>
        <textarea name="description" id="description"
                  class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                  rows="3"
                  placeholder="Opcional..."></textarea>
      </div>

      <div class="flex justify-end">
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
          Guardar modelo
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

