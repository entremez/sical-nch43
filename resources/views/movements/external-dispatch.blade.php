@extends('layouts.default')

@section('title', 'Despacho externo')

@section('content')

<div class="flex justify-center mt-10">
  <div class="w-full md:w-5/6 bg-white p-6 rounded-lg shadow">

    <h1 class="text-3xl font-bold mb-6">Nuevo despacho externo</h1>

    <form method="POST" action="{{ route('movements.storeExternalDispatch') }}">
      @csrf

      {{-- Cliente externo --}}
      <div class="mb-4">
        <label for="client_id" class="block text-gray-700 font-medium mb-2">Cliente externo</label>
        <select name="client_id" id="client_select"
                class="w-full px-3 py-2 border rounded"></select>
      </div>

      {{-- Empresa emisora --}}
      <div class="mb-4">
        <label for="company" class="block text-gray-700 font-medium mb-2">Empresa emisora</label>
        <select name="company" id="company"
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
        <label for="dispatch_guide" class="block text-gray-700 font-medium mb-2">Guía de despacho</label>
        <input type="text" name="dispatch_guide" id="dispatch_guide"
               class="w-full px-3 py-2 border border-gray-300 rounded" required>
      </div>

      {{-- Comentarios --}}
      <div class="mb-4">
        <label for="comments" class="block text-gray-700 font-medium mb-2">Comentarios</label>
        <input type="text" name="comments" id="comments"
               class="w-full px-3 py-2 border border-gray-300 rounded">
      </div>

      {{-- Buscar y agregar productos --}}
      <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Buscar productos</label>
        <button type="button" onclick="fetchClientProducts()"
                class="mt-2 bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
          Buscar productos
        </button>
      </div>

      {{-- Contenedor productos --}}
      <div id="product-container">
        {{-- Productos agregados dinámicamente --}}
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script>
$(document).ready(function() {
  $('#client_select').select2({
    placeholder: 'Buscar cliente...',
    ajax: {
      url: '/clientes/search', // Asume que existe esta ruta
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        return {
          results: data.map(c => ({ id: c.id, text: c.name }))
        };
      }
    }
  });
});

function fetchClientProducts() {
  const clientId = $('#client_select').val();
  console.log(clientId);
  if (!clientId) return;
  fetch(`/clients/${clientId}/dispatchable-products`)
    .then(res => res.json())
    .then(data => {
      const container = document.getElementById('product-container');
      container.innerHTML = '';

      if (data.length === 0) {
        container.innerHTML = '<p class="text-gray-500">No hay productos disponibles para despacho.</p>';
        return;
      }

      const block = document.createElement('div');
      block.classList.add('mb-6');
      block.innerHTML = `
        <h2 class="text-xl font-semibold mb-2">Productos del cliente</h2>
        <table class="w-full border text-left">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-3 py-2">Marca</th>
              <th class="px-3 py-2">Modelo</th>
              <th class="px-3 py-2">Denominación</th>
              <th class="px-3 py-2">Cantidad</th>
              <th class="px-3 py-2">Guía de ingreso</th>
              <th class="px-3 py-2">Ubicación</th>
              <th class="px-3 py-2">Comentarios</th>
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
                <td class="px-3 py-2">${prod.entry_guide}</td>
                <td class="px-3 py-2">${prod.location}</td>
                <td class="px-3 py-2">${prod.comments || ''}</td>
                <td class="px-3 py-2 text-center">
                  <input type="checkbox" name="products[]" value="${prod.id}" checked>
                </td>
              </tr>
            `).join('')}
          </tbody>
        </table>
      `;
      container.appendChild(block);
    });
}
</script>
@endsection
