@extends('layouts.default')

@section('title', 'Productos sin OSE asignada')

@section('content')

<div class="flex justify-center mt-10">
  <div class="w-full md:w-5/6 bg-white p-6 rounded-lg shadow">

    <h1 class="text-2xl font-bold mb-6">Productos no asignados a ninguna OSE</h1>

    <table id="tabla-productos" class="table-auto w-full text-left border-collapse">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-4 py-2">ID</th>
          <th class="px-4 py-2">Modelo</th>
          <th class="px-4 py-2">Marca</th>
          <th class="px-4 py-2">Denominación</th>
          <th class="px-4 py-2">Cantidad</th>
          <th class="px-4 py-2">Guía de despacho</th>
        </tr>
      </thead>
      <tbody>
        @foreach($products as $product)
          <tr class="border-t">
            <td class="px-4 py-2">{{ $product->id }}</td>
            <td class="px-4 py-2">{{ $product->model->name }}</td>
            <td class="px-4 py-2">{{ $product->model->brand->name }}</td>
            <td class="px-4 py-2">{{ $product->model->designation->name }}</td>
            <td class="px-4 py-2">{{ $product->quantity }}</td>
            <td class="px-4 py-2">{{ $product->movement->dispatch_guide ?? '—' }}</td>
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
    $('#tabla-productos').DataTable({
      paging: true,
      ordering: true,
      searching: true,
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
      }
    });
  });
</script>
@endsection
