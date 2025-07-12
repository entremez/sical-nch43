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
    <table id="movimientos" class="w-full text-sm border rounded overflow-hidden">
      <thead class="bg-gray-100 text-left">
        <tr>
          <th></th>
          <th class="px-3 py-2">Fecha</th>
          <th class="px-3 py-2">Tipo</th>
          <th class="px-3 py-2">Guía</th>
          <th class="px-3 py-2">Empresa</th>
          <th class="px-3 py-2 text-center"># Productos</th>
          <th class="px-3 py-2 text-center"># Disponibles</th>
        </tr>
      </thead>
      <tbody>
        @foreach($movements as $movement)
          <tr data-id="{{ $movement->id }}">
            <td class="text-center"><button class="expand-btn text-blue-600 font-bold">+</button></td>
            <td class="px-3 py-2">{{ $movement->created_at->format('d-m-Y H:i') }}</td>
            <td class="px-3 py-2">{{ ucfirst(str_replace('_', ' ', $movement->movement_type)) }}</td>
            <td class="px-3 py-2">{{ $movement->dispatch_guide ?? '—' }}</td>
            <td class="px-3 py-2">{{ $movement->company ?? '—' }}</td>
            <td class="px-3 py-2 text-center">{{ $movement->products->count() }}</td>
            <td class="px-3 py-2 text-center">
              {{ $movement->products->whereNull('external_dispatch_id')->whereNull('expanded')->count() }}
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

  </div>
</div>


@endsection

@section('scripts')
<script>
  $(document).ready(function () {
  const tabla = $('#movimientos').DataTable({
    paging: true,
    ordering: true,
    searching: true,
    language: {
      url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
    }
  });

  // ✅ Aquí está el cambio crítico:
  $('#movimientos tbody').off('click', '.expand-btn').on('click', '.expand-btn', function () {
    console.log('Expand button clicked');
    const tr = $(this).closest('tr');
    const row = tabla.row(tr);
    const movementId = tr.data('id');

    if (row.child.isShown()) {
      row.child.hide();
      $(this).text('+');
    } else {
      fetch(`/api/movements/${movementId}/products`)
        .then(res => res.json())
        .then(data => {
          const detalle = `
            <table class="w-full text-xs bg-gray-50 mt-2 rounded">
              <thead>
                <tr>
                  <th class="px-2 py-1">Marca</th>
                  <th class="px-2 py-1">Modelo</th>
                  <th class="px-2 py-1">Denominación</th>
                  <th class="px-2 py-1">Cantidad</th>
                  <th class="px-2 py-1">Estado</th>
                </tr>
              </thead>
              <tbody>
                ${data.map(p => {
                  console.log('Product:', p);
                  const estado = p.external_dispatch_id
                    ? '<span class="text-red-600">Despachado al cliente</span>'
                    : p.expanded
                      ? '<span class="text-blue-600">Expandido</span>'
                      : '<span class="text-green-600">Disponible</span>';

                  return `
                    <tr>
                      <td class="px-2 py-1">${p.model.brand.name}</td>
                      <td class="px-2 py-1">${p.model.name}</td>
                      <td class="px-2 py-1">${p.model.designation.name}</td>
                      <td class="px-2 py-1 text-center">${p.quantity}</td>
                      <td class="px-2 py-1 text-center">${estado}</td>
                    </tr>
                  `;
                }).join('')}
              </tbody>
            </table>
          `;
          row.child(detalle).show();
          $(this).text('−');
        });
    }
  });
});

</script>
@endsection