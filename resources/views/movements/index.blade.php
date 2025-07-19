@extends('layouts.default')

@section('title', 'Movimientos de productos')

@section('content')

<div class="flex justify-center mt-10">
  <div class="w-full md:w-2/3 bg-white p-6 rounded-lg shadow">

    <h1 class="text-3xl font-bold mb-6">Registrar movimiento de productos</h1>

    @php
      use Illuminate\Support\Facades\Route;
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

      {{-- INGRESOS --}}
      <div class="bg-white border border-blue-800 rounded-xl shadow p-6">
        <h2 class="text-xl font-bold text-blue-800 mb-4 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Entrada de productos
        </h2>
        <div class="flex flex-col gap-4">
          <a href="{{ route('movements.create', ['type' => 'ingreso_externo']) }}"
            class="flex items-center justify-center gap-2 text-white bg-blue-800 hover:bg-blue-900 font-semibold py-4 rounded-xl transition duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 17l-4 4m0 0l-4-4m4 4V3" />
            </svg>
            Ingreso desde cliente externo
          </a>
          <a href="{{ route('movements.create', ['type' => 'ingreso_interno']) }}"
            class="flex items-center justify-center gap-2 text-white bg-blue-700 hover:bg-blue-800 font-semibold py-4 rounded-xl transition duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 17l-4 4m0 0l-4-4m4 4V3" />
            </svg>
            Ingreso desde laboratorio u OCP
          </a>
        </div>
      </div>

      {{-- DESPACHOS --}}
      <div class="bg-white border border-orange-500 rounded-xl shadow p-6">
        <h2 class="text-xl font-bold text-orange-500 mb-4 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7l4-4m0 0l4 4m-4-4v18" />
          </svg>
          Salida de productos
        </h2>
        <div class="flex flex-col gap-4">
          <a href="{{ route('movements.create', ['type' => 'despacho_interno']) }}"
            class="flex items-center justify-center gap-2 text-white bg-orange-500 hover:bg-orange-600 font-semibold py-4 rounded-xl transition duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7l4-4m0 0l4 4m-4-4v18" />
            </svg>
            Despacho a laboratorio u OCP
          </a>
          <a href="{{ route('movements.create', ['type' => 'despacho_externo']) }}"
            class="flex items-center justify-center gap-2 text-white bg-orange-600 hover:bg-orange-700 font-semibold py-4 rounded-xl transition duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7l4-4m0 0l4 4m-4-4v18" />
            </svg>
            Despacho a cliente externo
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