@extends('layouts.default')

@section('title', 'Editar modelo')

@section('content')
<div class="max-w-xl mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-4">Editar modelo de producto</h1>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <form action="{{ route('product-models.update', $productModel) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-semibold">Nombre del modelo</label>
            <input type="text" name="name" value="{{ old('name', $productModel->name) }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Marca</label>
            <select name="brand_id" class="w-full border rounded px-3 py-2" required>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" @selected($brand->id == $productModel->brand_id)>{{ $brand->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Denominación</label>
            <select name="designation_id" class="w-full border rounded px-3 py-2" required>
                @foreach($designations as $designation)
                    <option value="{{ $designation->id }}" @selected($designation->id == $productModel->designation_id)>{{ $designation->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="text-right">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar cambios</button>
        </div>
    </form>
</div>
@endsection
