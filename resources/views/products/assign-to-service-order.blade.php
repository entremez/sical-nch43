@extends('layouts.default')

@section('title', 'Asignar productos a OSE')

@section('content')

<div class="flex justify-center mt-10">
  <div class="w-full md:w-3/4 bg-white p-6 rounded-lg shadow">

    <h1 class="text-2xl font-bold mb-6">Asignar productos a una orden de servicio</h1>

    {{-- Filtro por guía --}}
    <form method="GET" action="{{ route('products.assign') }}" class="mb-6">
      <div class="flex gap-4 items-end">
        <div class="flex-1">
          <label class="block text-gray-700 font-medium mb-2">
            Buscar por una o más guías de despacho (separadas por coma)
          </label>
          <input type="text" name="guide" value="{{ $guide ?? '' }}" class="w-full px-3 py-2 border rounded"
                 placeholder="Ej: 78954,78955,SCP-001">
        </div>
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
          Buscar
        </button>
      </div>
    </form>

    @if($products->isNotEmpty())
      <form method="POST" action="{{ route('products.assign.store') }}">
        @csrf

        {{-- Selección de OSE --}}
        <div class="mb-4">
          <label class="block text-gray-700 font-medium mb-2">Asignar a OSE</label>
          <select name="service_order_id" required
                  class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Seleccionar una orden</option>
            @foreach($serviceOrders as $ose)
              <option value="{{ $ose->id }}">{{ $ose->order_number }}</option>
            @endforeach
          </select>
        </div>

        {{-- Tabla de productos --}}
        <table class="table-auto w-full text-left border-collapse mb-6" id="tabla-productos">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2 text-center">Seleccionar</th>
              <th class="px-4 py-2">Guía de despacho</th>
              <th class="px-4 py-2">Modelo</th>
              <th class="px-4 py-2">Marca</th>
              <th class="px-4 py-2">Denominación</th>
              <th class="px-4 py-2">Cantidad</th>
              <th class="px-4 py-2">Ubicación</th>
              <th class="px-4 py-2">Asignación</th>
              <th class="px-4 py-2">Desagregar</th>
            </tr>
          </thead>
          <tbody>
            @foreach($products as $product)
              <tr id="fila-{{ $product->id }}" class="border-t">
                <td class="px-4 py-2 text-center">
                  <input type="checkbox" name="products[]" value="{{ $product->id }}">
                </td>
                <td class="px-4 py-2">{{ $product->movement->dispatch_guide ?? '—' }}</td>
                <td class="px-4 py-2">{{ $product->model->name }}</td>
                <td class="px-4 py-2">{{ $product->model->brand->name }}</td>
                <td class="px-4 py-2">{{ $product->model->designation->name }}</td>
                <td class="px-4 py-2">{{ $product->quantity }}</td>
                <td class="px-4 py-2">
                  @php
                    $lastMovementProduct = $product->movementProducts->sortByDesc('id')->first();
                    $lastType = $lastMovementProduct?->movement?->movement_type;
                    $location = match ($lastType) {
                        'despacho_interno' => 'Fuera de bodega / Laboratorio',
                        'ingreso_externo', 'ingreso_interno' => 'En bodega',
                        default => 'En bodega - Nunca ha salido',
                    };
                  @endphp
                  {{ $location }}
                </td>

                <td class="px-4 py-2">
                  @if($product->serviceOrders->isNotEmpty())
                    <div class="text-sm text-gray-700">
                      Asignado a: {{ $product->serviceOrders->pluck('order_number')->join(', ') }}
                    </div>
                  @else
                    <span class="text-gray-500">No asignado</span>
                  @endif
                </td>
                <td class="px-4 py-2 text-center">
                @if($product->quantity > 1 && $product->serviceOrders->isEmpty())
                    <button type="button"
                            class="text-sm text-blue-600 hover:underline"
                            onclick="abrirDesagregar({{ $product->id }}, '{{ $product->movement->dispatch_guide }}', {{ $product->quantity }})">
                    Desagregar
                    </button>
                @elseif($product->quantity <= 1)
                    <span class="text-gray-400 text-sm italic">No divisible</span>
                @else
                    <span class="text-gray-400 text-sm italic">Asignado</span>
                @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <div class="flex justify-end mt-4">
          <button type="submit"
                  class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded shadow">
            Asignar productos seleccionados
          </button>
        </div>
      </form>
    @elseif($guide)
      <p class="text-gray-500">No se encontraron productos con esas guías.</p>
    @endif

  </div>
</div>

@endsection

<div id="modal-desagregar"
     class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">

  <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg mx-4">
    <h2 class="text-xl font-bold mb-4">Desagregar producto</h2>

    <p class="mb-2 text-sm text-gray-600" id="desagregar-guia-info"></p>
    <p class="mb-4 text-sm text-gray-600">Cantidad total: <span id="cantidad-original"></span></p>

    <label class="block text-gray-700 font-medium mb-2">Divisiones (separadas por coma)</label>
    <input type="text" id="divisiones-input"
           class="w-full px-3 py-2 border border-gray-300 rounded mb-4"
           placeholder="Ej: 5,3 para dividir en 5 y 3">

    <div class="flex justify-end gap-4">
      <button type="button" onclick="cerrarModalDesagregar()" class="text-gray-600 hover:underline">Cancelar</button>
      <button type="button" onclick="procesarDesagregar()" class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700">
        Aplicar
      </button>
    </div>
  </div>
</div>



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

  let productoActualId = null;
  let cantidadDisponible = 0;
  let filaOriginal = null;


function abrirDesagregar(id, guia, cantidad) {
  productoActualId = id;
  cantidadDisponible = cantidad;
  filaOriginal = document.getElementById(`fila-${id}`);

  document.getElementById('desagregar-guia-info').innerText = `Producto de guía: ${guia}`;
  document.getElementById('cantidad-original').innerText = cantidad;
  document.getElementById('divisiones-input').value = '';

  document.getElementById('modal-desagregar').classList.remove('hidden');
  document.getElementById('modal-desagregar').classList.add('flex');
}

function cerrarModalDesagregar() {
  document.getElementById('modal-desagregar').classList.remove('flex');
  document.getElementById('modal-desagregar').classList.add('hidden');
  productoActualId = null;
  cantidadDisponible = 0;
  filaOriginal = null;
}

function procesarDesagregar() {
  const input = document.getElementById('divisiones-input').value;
  const partes = input.split(',').map(n => parseInt(n.trim())).filter(n => !isNaN(n) && n > 0);
  const suma = partes.reduce((a, b) => a + b, 0);

  if (suma !== cantidadDisponible) {
    alert(`La suma de las divisiones debe ser exactamente ${cantidadDisponible}`);
    return;
  }
  

  const tbody = document.querySelector('#tabla-productos tbody');
  const originalHTML = filaOriginal.innerHTML;

    $('#tabla-productos').append(`
    <input type="hidden" name="divisiones_${productoActualId}" value="${partes.join(',')}">
    `);

  // Eliminar fila original
  filaOriginal.remove();

  // Insertar nuevas filas
  partes.forEach((cantidad, index) => {
    const nuevaFila = document.createElement('tr');
    const idDesagregado = `${productoActualId}-${index}`;
    nuevaFila.id = `fila-${idDesagregado}`;
    nuevaFila.innerHTML = originalHTML;

    // Actualizar checkbox id/value
    const checkbox = nuevaFila.querySelector('input[type=checkbox]');
    checkbox.value = idDesagregado;

    // Actualizar cantidad
    const cantidadCell = nuevaFila.querySelectorAll('td')[5];
    cantidadCell.innerText = cantidad;

    // Marcar como desagregado
    const asignacionCell = nuevaFila.querySelectorAll('td')[6];
    asignacionCell.innerHTML = '<em class="text-sm text-gray-500">Desagregado</em>';

    // Quitar el botón "Desagregar"
    const desagregarCell = nuevaFila.querySelectorAll('td')[7];
    desagregarCell.innerHTML = '<span class="text-gray-400 text-sm italic">—</span>';

    tbody.appendChild(nuevaFila);
  });

  cerrarModalDesagregar();
}

</script>


@endsection
