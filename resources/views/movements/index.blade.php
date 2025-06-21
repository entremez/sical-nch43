@extends('layouts.default')

@section('title', 'Movimientos de productos')

@section('content')

<div class="flex justify-center mt-10">
  <div class="w-full md:w-2/3 bg-white p-6 rounded-lg shadow">

    <h1 class="text-3xl font-bold mb-6">Registrar movimiento de productos</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

      {{-- INGRESOS --}}
      <div class="bg-gray-50 p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Ingresos</h2>
        <div class="flex flex-col gap-3">
          <a href="{{ route('movements.create', ['type' => 'ingreso_externo']) }}"
            class="bg-green-500 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow text-center">
            Ingreso externo
          </a>
          <a href="{{ route('movements.create', ['type' => 'ingreso_interno']) }}"
             class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded shadow text-center">
            Ingreso interno
          </a>
        </div>
      </div>

      {{-- DESPACHOS --}}
      <div class="bg-gray-50 p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Despachos</h2>
        <div class="flex flex-col gap-3">
          <a href="{{ route('movements.create', ['type' => 'despacho_externo']) }}"
             class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded shadow text-center">
            Despacho externo
          </a>
          <a href="{{ route('movements.create', ['type' => 'despacho_interno']) }}"
             class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded shadow text-center">
            Despacho interno
          </a>
        </div>
      </div>

    </div>

    <h1 class="text-3xl font-semibold my-6">Historial de movimientos</h1>

<table id="movimientos" class="table-auto w-full border">
  <thead>
    <tr>
      <th></th> <!-- Columna para botón -->
      <th>Fecha</th>
      <th>Cliente</th>
      <th>Tipo</th>
      <th>Guía</th>
    </tr>
  </thead>
  <tbody>
    @foreach($movements as $movement)
      <tr data-id="{{ $movement->id }}">
        <td><button class="expand-btn">+</button></td>
        <td>{{ $movement->created_at->format('d/m/Y') }}</td>
        <td>{{ $movement->customer->name }}</td>
        <td>{{ ucwords(str_replace('_', ' ', $movement->movement_type)) }}</td>
        <td>{{ $movement->dispatch_guide ?? '—' }}</td>
      </tr>
    @endforeach
  </tbody>
</table>


  </div>
</div>


@endsection