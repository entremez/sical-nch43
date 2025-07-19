@extends('layouts.default')

@section('title', 'Ingreso interno por OSE')

@section('content')

<div class="flex justify-center mt-10">
  <div class="w-full md:w-5/6 bg-white p-6 rounded-lg shadow">

    <h1 class="text-3xl font-bold mb-6">Nuevo ingreso interno</h1>

    <form method="POST" action="{{ route('movements.storeInternalEntry')}}"> {{--   --}}
      @csrf

      {{-- Cliente interno --}}
      <div class="mb-4">
        <label for="cliente_id_internal" class="block text-gray-700 font-medium mb-2">Cliente interno</label>
        <select name="cliente_id_internal" id="cliente_id_internal"
                class="w-full px-3 py-2 border rounded" required>
          <option value="">Seleccione...</option>
          @foreach($clientes as $cliente)
            <option value="{{ $cliente->id }}">{{ $cliente->name }}</option>
          @endforeach
        </select>
      </div>

      {{-- Buscar y agregar OSE --}}
      <div class="mb-4">
        <label for="ose_select" class="block text-gray-700 font-medium mb-2">Buscar OSE</label>
        <select id="ose_select" class="w-full px-3 py-2 border rounded">
          <option value="">Buscar orden de servicio...</option>
          @foreach($oses as $ose)
            <option value="{{ $ose->id }}">{{ $ose->order_number }}</option>
          @endforeach
        </select>
        <button type="button" onclick="agregarOSE()"
                class="mt-2 bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
          Agregar OSE
        </button>
      </div>

      {{-- Tabla de productos por OSE --}}
      <div id="contenedor-oses">
        {{-- Se llenará dinámicamente --}}
      </div>

      <div class="flex justify-end mt-6">
        <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow">
          Guardar movimiento
        </button>
      </div>

    </form>

  </div>
</div>

@endsection

@section('scripts')
<script>
const osesAgregadas = new Set();

function agregarOSE() {
  const select = document.getElementById('ose_select');
  const oseId = select.value;
  const oseText = select.options[select.selectedIndex].text;

  if (!oseId || osesAgregadas.has(oseId)) return;

  osesAgregadas.add(oseId);

  fetch(`/api/ose/${oseId}/products-entry`)
    .then(res => res.json())
    .then(data => {
      const container = document.getElementById('contenedor-oses');
      const tabla = document.createElement('div');
      tabla.classList.add('mb-6');

      tabla.innerHTML = `
        <h2 class="text-xl font-semibold mb-2">OSE ${oseText}</h2>
        <input type="hidden" name="oses[]" value="${oseId}">
        <table class="w-full border text-left">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-3 py-2">Marca</th>
              <th class="px-3 py-2">Modelo</th>
              <th class="px-3 py-2">Denominación</th>
              <th class="px-3 py-2">Cantidad</th>
              <th class="px-3 py-2">Seleccionar</th>
            </tr>
          </thead>
          <tbody>
          ${data.map(prod => {
            const inLab = prod.location	 === 'laboratorio';
            const disabled = inLab ? '' : 'disabled';
            const tooltip = inLab ? '' : 'title="Producto se encuentra en bodega"';
            const styleRow = inLab ? '' : 'opacity-50 text-gray-500';

            return `
              <tr class="border-t ${styleRow}">
                <td class="px-3 py-2">${prod.model.brand.name}</td>
                <td class="px-3 py-2">${prod.model.name}</td>
                <td class="px-3 py-2">${prod.model.designation.name}</td>
                <td class="px-3 py-2">${prod.quantity}</td>
                <td class="px-3 py-2 text-center">
                  <input type="checkbox" name="products[]" value="${prod.id}" ${disabled} ${tooltip} ${inLab ? 'checked' : ''}>
                </td>
              </tr>
            `;
          }).join('')}
          </tbody>
        </table>
      `;

      container.appendChild(tabla);
    });
}
</script>
@endsection
