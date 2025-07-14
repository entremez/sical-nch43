@extends('layouts.default')

@section('title', 'Movimientos de productos')

@section('content')

<div class="flex justify-center mt-10">
  <div class="w-full md:w-2/3 bg-white p-6 rounded-lg shadow">

    <h1 class="text-3xl font-bold mb-6">Registrar movimiento de productos</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

      {{-- INGRESOS --}}
      <div class="bg-gray-50 p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Entrada de productos a bodega</h2>
        <div class="flex flex-col gap-3">
          <a href="{{ route('movements.create', ['type' => 'ingreso_externo']) }}"
            class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow text-center">
            Desde cliente externo (con guía de despacho)
          </a>
          <a href="{{ route('movements.create', ['type' => 'ingreso_interno']) }}"
            class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded shadow text-center">
            Desde OCP o LAB
          </a>
        </div>
      </div>

      {{-- DESPACHOS --}}
      <div class="bg-gray-50 p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Salida de productos desde bodega</h2>
        <div class="flex flex-col gap-3">
          <a href="{{ route('movements.create', ['type' => 'despacho_interno']) }}"
            class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded shadow text-center">
            Hacia OCP o LAB
          </a>
          <a href="{{ route('movements.create', ['type' => 'despacho_externo']) }}"
            class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded shadow text-center">
            Hacia cliente externo (con guía de despacho)
          </a>
        </div>
      </div>

    </div>

    <h1 class="text-3xl font-semibold my-6">Historial de movimientos</h1>
    <div class="flex justify-center mt-10">
      <div class="w-full bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-3xl font-bold">Listado individual de productos</h1>
          <form method="GET" action="{{ route('movements.index') }}">
            <input type="hidden" name="hideDispatched" value="{{ isset($hideDispatched) && $hideDispatched ? '0' : '1' }}">
            <button type="submit"
              class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
              {{ isset($hideDispatched) && $hideDispatched ? 'Mostrar despachados' : 'Ocultar despachados' }}
            </button>
          </form>
        </div>

        <div class="w-full">
          <table id="productTable" class="w-full text-sm border rounded table-fixed">
            <thead class="bg-gray-100 text-left">
              <tr>
                <th class="px-3 py-2">Marca</th>
                <th class="px-3 py-2">Modelo</th>
                <th class="px-3 py-2">Denominación</th>
                <th class="px-3 py-2">Cantidad</th>
                <th class="px-3 py-2">Ubicación</th>
                <th class="px-3 py-2">Guía ingreso</th>
                <th class="px-3 py-2 w-72">Historial</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($products as $p)
              <tr class="{{ $p['dispatched'] ? 'bg-red-50 text-gray-500' : '' }}">
                <td class="px-3 py-2">{{ $p['brand'] }}</td>
                <td class="px-3 py-2">{{ $p['model'] }}</td>
                <td class="px-3 py-2">{{ $p['designation'] }}</td>
                <td class="px-3 py-2 text-center">{{ $p['quantity'] }}</td>
                <td class="px-3 py-2">{{ $p['location'] }}</td>
                <td class="px-3 py-2">{{ $p['guide'] }}</td>
                <td class="px-3 py-2 whitespace-pre-line text-xs w-72">{{ $p['history'] }}</td>
              </tr>
              @endforeach
            </tbody>
            </table>
        </div>
      </div>
    </div>

  </div>
</div>


@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    const tabla = $('#productTable').DataTable({
      paging: true,
      ordering: true,
      searching: true,
      autoWidth: false,
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
      }
    });

  });
</script>
@endsection