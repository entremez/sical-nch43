@extends('layouts.default')

@section('title', 'Recepción de productos')

@section('content')
    <div class="max-w-6xl mx-auto p-6 bg-white rounded shadow">
        <h1 class="text-2xl font-bold mb-6">Recepción de productos del movimiento #{{ $movement->id }}</h1>

        <form method="POST" action="{{ route('receptions.store', $movement) }}" enctype="multipart/form-data">
            @csrf

            @foreach ($products as $product)
                <div class="border p-4 rounded mb-6">
                    <h2 class="text-lg font-semibold mb-2">Producto: {{ $product->model->brand->name }}
                        {{ $product->model->name }}</h2>

                    <label class="block mb-1 font-medium">Informe de recepción</label>
                    <textarea name="products[{{ $product->id }}][report]" class="w-full border rounded p-2 mb-3"></textarea>

                    <label class="block mb-1 font-medium">Imágenes</label>
                    <input type="file" name="products[{{ $product->id }}][images][]" accept="image/*" multiple class="mb-2" />
                    <small class="text-gray-500">Puedes tomar una foto o elegir una desde tu dispositivo.</small>



                </div>
            @endforeach

            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
                Guardar recepción
            </button>
        </form>
    </div>
@endsection
