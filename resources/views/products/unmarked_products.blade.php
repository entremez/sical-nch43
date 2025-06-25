@extends('layouts.default')

@section('title', 'Productos asignados a OSE sin marcar')

@section('content')
<div class="flex justify-center mt-10">
  <div class="w-full md:w-5/6 bg-white p-6 rounded-lg shadow">
    <h1 class="text-2xl font-bold mb-6">Productos asignados a OSE sin marcar</h1>

    <div id="mensaje-exito" class="hidden mb-4 bg-green-100 text-green-800 px-4 py-2 rounded">
      OSE marcada correctamente.
    </div>

    <table class="w-full border text-sm" id="tabla-unmarked">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-3 py-2">Modelo</th>
          <th class="px-3 py-2">Marca</th>
          <th class="px-3 py-2">Denominación</th>
          <th class="px-3 py-2">Cantidad</th>
          <th class="px-3 py-2">Guía</th>
          <th class="px-3 py-2">OSE</th>
          <th class="px-3 py-2">Acción</th>
        </tr>
      </thead>
      <tbody>
        @foreach($productos as $product)
          @foreach($product->serviceOrders as $order)
            <tr id="fila-producto-{{ $order->id }}">
              <td class="px-3 py-2">{{ $product->model->name }}</td>
              <td class="px-3 py-2">{{ $product->model->brand->name }}</td>
              <td class="px-3 py-2">{{ $product->model->designation->name }}</td>
              <td class="px-3 py-2 text-center">{{ $product->quantity }}</td>
              <td class="px-3 py-2">{{ $product->movement->dispatch_guide ?? '—' }}</td>
              <td class="px-3 py-2">{{ $order->order_number }}</td>
              <td class="px-3 py-2">
                <button class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm marcar-ose"
                        data-ose-id="{{ $order->id }}">
                  Marcar
                </button>
              </td>
            </tr>
          @endforeach
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
  const tabla = $('#tabla-unmarked').DataTable({
    language: {
      url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
    }
  });

  // AJAX para marcar
  $('#tabla-unmarked').on('click', '.marcar-ose', function () {
    const oseId = $(this).data('ose-id');
    const fila = $(this).closest('tr');

    $.ajax({
      url: `/service-orders/${oseId}/mark`,
      method: 'PATCH',
      data: {
        _token: '{{ csrf_token() }}'
      },
      success: function () {
        tabla.row(fila).remove().draw();
        $('#mensaje-exito').removeClass('hidden').text(`OSE ${oseId} marcada como completada.`);
      },
      error: function () {
        alert('Hubo un error al marcar la OSE.');
      }
    });
  });
});
</script>
@endsection
