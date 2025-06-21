@extends('layouts.default')

@section('title', 'Despacho externo por OSE')

@section('content')

<div class="flex justify-center mt-10">
  <div class="w-full md:w-5/6 bg-white p-6 rounded-lg shadow">

    <h1 class="text-3xl font-bold mb-6">Nuevo despacho externo</h1>

    <form method="POST" action="{{ route('movements.storeExternalDispatch') }}">
      @csrf

      {{-- Cliente externo --}}
      <div class="mb-4">
        <label for="cliente_id" class="block text-gray-700 font-medium mb-2">Cliente externo</label>
        <select name="cliente_id" id="cliente_id"
                class="w-full px-3 py-2 border rounded" required>
          <option value="">Seleccione...</option>
          @foreach($clientes as $cliente)
            <option value="{{ $cliente->id }}">{{ $cliente->name }}</option>
          @endforeach
        </select>
      </div>

      {{-- Empresa emisora --}}
      <div class="mb-4">
        <label for="empresa" class="block text-gray-700 font-medium mb-2">Empresa emisora</label>
        <select name="empresa" id="empresa"
                class="w-full px-3 py-2 border rounded" required>
          <option value="">Seleccionar empresa</option>
          <option value="Sical Ingenieros">Sical Ingenieros</option>
          <option value="Silab Ingenieros LE">Silab Ingenieros LE</option>
          <option value="Silab Ingenieros LC">Silab Ingenieros LC</option>
          <option value="Ingelab S.A.">Ingelab S.A.</option>
        </select>
      </div>

      {{-- Guía de despacho --}}
      <div class="mb-4">
        <label for="guia_despacho" class="block text-gray-700 font-medium mb-2">Guía de despacho</label>
        <input type="text" name="guia_despacho" id="guia_despacho"
               class="w-full px-3 py-2 border border-gray-300 rounded" required>
      </div>

      {{-- Comentarios --}}
      <div class="mb-4">
        <label for="bodega" class="block text-gray-700 font-medium mb-2">Comentarios</label>
        <input type="text" name="bodega" id="bodega"
               class="w-full px-3 py-2 border border-gray-300 rounded">
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

      {{-- Productos por OSE --}}
      <div id="contenedor-oses">
        {{-- Aquí se insertan las tablas generadas dinámicamente --}}
      </div>

      <div class="flex justify-end mt-6">
        <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow">
          Guardar despacho
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

  fetch(`/api/ose/${oseId}/products`)
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
            ${data.map(prod => `
              <tr class="border-t">
                <td class="px-3 py-2">${prod.model.brand.name}</td>
                <td class="px-3 py-2">${prod.model.name}</td>
                <td class="px-3 py-2">${prod.model.designation.name}</td>
                <td class="px-3 py-2">${prod.quantity}</td>
                <td class="px-3 py-2 text-center">
                  <input type="checkbox" name="products[]" value="${prod.id}" checked>
                </td>
              </tr>
            `).join('')}
          </tbody>
        </table>
      `;

      container.appendChild(tabla);
    });
}
</script>
@endsection
