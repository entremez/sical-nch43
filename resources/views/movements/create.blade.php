@extends('layouts.default')

@section('title', 'Nuevo movimiento de productos')

@section('content')

<div class="flex justify-center mt-10">
  <div class="w-full md:w-2/3 bg-white p-6 rounded-lg shadow">

    <h1 class="text-3xl font-bold mb-6">Nuevo movimiento: {{ ucwords(str_replace('_', ' ', $type)) }}</h1>

    @if($errors->any())
      <div class="mb-4 bg-red-500 text-white px-4 py-3 rounded-lg">
        <ul class="list-disc list-inside">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('movements.store') }}">
      @csrf

      <input type="hidden" name="tipo_movimiento" value="{{ $type }}">

      {{-- Cliente --}}
        <div class="mb-4">
        <label for="cliente_id" class="block text-gray-700 font-medium mb-2">Cliente</label>
        <select id="cliente_id" name="cliente_id"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </select>
        </div>

      {{-- Guía de despacho (sólo en ingresos o despachos externos) --}}
      @if(str_contains($type, 'externo'))
        <div class="mb-4">
          <label for="guia_despacho" class="block text-gray-700 font-medium mb-2">Guía de despacho</label>
          <input type="text" name="guia_despacho" id="guia_despacho"
                 class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                 placeholder="N° de guía">
        </div>
      @endif

      {{-- OSE relacionada, salvo si es ingreso externo --}}
      @if($type =! 'ingreso_externo')
        <div class="mb-4">
            <label for="service_order_id" class="block text-gray-700 font-medium mb-2">Orden de servicio (OSE)</label>
            <select name="service_order_id" id="service_order_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Sin asignar</option>
            @foreach($oses as $ose)
                <option value="{{ $ose->service_order_id }}">{{ $ose->order_number }}</option>
            @endforeach
            </select>
        </div>
      @endif

        {{-- Empresa --}}
        <div class="mb-4">
        <label for="empresa" class="block text-gray-700 font-medium mb-2">Empresa receptora</label>
        <select name="empresa" id="empresa"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                required>
            <option value="">Seleccionar empresa</option>
            <option value="Sical Ingenieros">Sical Ingenieros</option>
            <option value="Silab Ingenieros LE">Silab Ingenieros LE</option>
            <option value="Silab Ingenieros LC">Silab Ingenieros LC</option>
            <option value="Ingelab S.A.">Ingelab S.A.</option>
        </select>
        </div>

      {{-- Bodega --}}
      <div class="mb-6">
        <label for="bodega" class="block text-gray-700 font-medium mb-2">Comentarios</label>
        <input type="text" name="bodega" id="bodega"
               class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-medium mb-2">Agregar modelos</label>
            <div id="brand-add" class="text-sm mt-2">
                <span class="text-gray-500">¿No encontraste el modelo?</span>
                <a  href="{{ route('models.create') }}" target="_blank" class="text-blue-600 hover:underline ml-2">Agregar nuevo modelo</a>
            </div>
            <div class="flex items-center gap-4">
                <select id="modelo_select" class="w-full px-3 py-2 border border-gray-300 rounded">
                <option value="">Buscar modelo...</option>
                </select>
                <button type="button" onclick="agregarModelo()"
                        class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                Agregar
                </button>
            </div>
        </div>

        <div class="mt-6">
            <h2 class="text-lg font-semibold mb-2">Modelos agregados</h2>
            <table class="w-full text-left border border-gray-300 rounded" id="tablaModelos">
                <thead class="bg-gray-100">
                <tr>
                    <th class="px-3 py-2">Denominación</th>
                    <th class="px-3 py-2">Marca</th>
                    <th class="px-3 py-2">Modelo</th>
                    <th class="px-3 py-2">Cantidad</th>
                    <th class="px-3 py-2">Eliminar</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>


      <div class="flex justify-end mt-3">
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
          Guardar movimiento
        </button>
      </div>

    </form>

  </div>
</div>

@endsection

<script>
function agregarModelo() {
  const modelo = $('#modelo_select').select2('data')[0];
  if (!modelo) return;

  const rowId = `modelo_${modelo.id}`;

  if (document.getElementById(rowId)) return alert('Ya fue agregado');

  $('#tablaModelos tbody').append(`
    <tr id="${rowId}">
      <td class="px-3 py-2">
        ${modelo.text}
        <input type="hidden" name="modelos[${modelo.id}][modelo_id]" value="${modelo.id}">
      </td>
      <td class="px-3 py-2">${modelo.denominacion}</td>
      <td class="px-3 py-2">${modelo.marca}</td>
      <td class="px-3 py-2">
        <input type="number" name="modelos[${modelo.id}][cantidad]" value="1" min="1"
               class="w-20 border rounded px-2 py-1 text-center">
      </td>
      <td class="px-3 py-2">
        <button type="button" onclick="eliminarModelo('${rowId}')"
                class="text-red-600 hover:underline">Eliminar</button>
      </td>
    </tr>
  `);

  $('#modelo_select').val(null).trigger('change');
}

function eliminarModelo(id) {
  document.getElementById(id)?.remove();
}
</script>