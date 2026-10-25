@extends('layouts.default')

@section('title', 'Certificados sistema 2')

@section('content')

@if(session('success'))
  <div class="mb-4 red-text bg-blue-500 text-white px-4 py-3 rounded-lg">
    {{ session('success') }}
  </div>
@endif

<h1 class="text-2xl font-bold mb-6">Certificados sistema 2</h1>

<!-- Formulario de Filtros y Búsqueda -->
<div class="mb-4 flex space-x-4">
  <form action="{{ route('certificates.index') }}" method="GET" class="flex items-center space-x-4">
    <div>
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por cliente, producto o certificado" 
             class="border rounded p-2">
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
      Filtrar
    </button>
  </form>

  @if(auth()->user()->canManageCertificates())
    <a href="{{ route('certificates.create') }}"
       class="inline-flex items-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
      Nuevo certificado
    </a>
  @endif
</div>

<div class="overflow-x-auto">
  <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
    <thead class="bg-gray-100 text-left text-gray-600">
      <tr>
        <th class="px-4 py-2">Certificado N°</th>
        <th class="px-4 py-2">Cliente</th>
        <th class="px-4 py-2">Producto</th>
        <th class="px-4 py-2">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($certificados as $certificado)
        <tr class="border-t">
          <td class="px-4 py-2">{{ $certificado->certificado }}</td>
          <td class="px-4 py-2">{{ $certificado->cliente }}</td>
          <td class="px-4 py-2">{{ $certificado->producto }}</td>
          <td class="px-4 py-2 space-x-2">
            <a href="{{ route('certificates.show', $certificado->id) }}"
               class="inline-flex items-center bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition text-sm">
              ✏️
            </a>

            <a href="{{ route('sistema2', $certificado->certificado) }}"
                class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
               Generar muestreo
             </a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<!-- Paginación -->
<div class="mt-4">
  {{ $certificados->appends(request()->query())->links() }}
</div>

@endsection
