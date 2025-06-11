@extends('layouts.default')

@section('title', 'Sistema de registro de planes de muestreo según NCh43')

@section('content')

<div class="flex justify-center mt-10">

  <div class="w-full md:w-1/2 bg-white p-6 rounded-lg shadow">

    @if (session('error'))
      <div class="mb-4 red-text bg-blue-500 text-white px-4 py-3 rounded-lg">
        {{ session('error') }}
      </div>
    @endif

    <h1 class="text-3xl font-bold mb-4">Sistema de registro de planes de muestreo según NCh43</h1>

    @if(Auth::user()->role_id != 3)
      <h2 class="text-2xl font-semibold my-4">1. Ingresar proceso o buscar en SCP</h2>

      <div class="mb-6">
        <label class="block text-gray-700 font-medium mb-2">Ingresar proceso manual</label>
        <a href="{{ route('input') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
          Ingresar
        </a>
      </div>

      <form action="{{ route('search') }}" method="post" onsubmit="revisarInputs()" id="ose">
        @csrf
        <div class="mb-4">
          <label for="search_scp" class="block text-gray-700 font-medium mb-2">Ingresar proceso registrado en SCP</label>
          <input
            required
            id="search_scp"
            name="search"
            type="text"
            placeholder="SCP-X-0001-2025"
            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
        </div>
        <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
          Buscar
        </button>
      </form>


        @if($muestreos_iniciados->count() == 0 && $muestreos_finalizados->count() == 0)
          <h2 class="text-2xl font-semibold my-4">No existen registrados.</h2>
        @else
          <h2 class="text-2xl font-semibold my-4">2. Coordenadas generadas.</h2>
          <div class="overflow-x-auto">
            <table class="table-auto w-full text-left border-collapse">
              <thead>
                <tr class="bg-gray-100">
                  <th class="px-4 py-2">Id proceso</th>
                  <th class="px-4 py-2">Fecha</th>
                  <th class="px-4 py-2">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach($muestreos_iniciados as $muestreo)
                  <tr class="border-t">
                    <td class="px-4 py-2">{{ $muestreo->id_proceso }}</td>
                    <td class="px-4 py-2">{{ Carbon\Carbon::parse($muestreo->fecha)->format('d/m/Y') }}</td>
                    <td class="px-4 py-2">
                      <div class="flex space-x-4 items-center">
                          <a href="{{ route('searchWithId', $muestreo->id) }}">
                            <img src="{{ asset('sample.png') }}" alt="Muestreo" width="40">
                          </a>
                          <a href="{{ route('track', $muestreo->id) }}">
                            <img src="{{ asset('material-management.png') }}" alt="Trazabilidad" width="40">
                          </a>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
    @endif
      <h2 class="text-2xl font-semibold my-4">3. Registro de muestreos</h2>
      <div class="overflow-x-auto w-full" >
        <table id="tabla-muestreos" class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-100">
              <th class="px-4 py-2">Id proceso</th>
              <th class="px-4 py-2">Fecha</th>
              <th class="px-4 py-2">Ver registro</th>
            </tr>
          </thead>
          <tbody>
            @foreach($muestreos_finalizados as $muestreo)
              <tr class="border-t">
                <td class="px-4 py-2">{{ $muestreo->id_proceso }}</td>
                <td class="px-4 py-2">{{ Carbon\Carbon::parse($muestreo->fecha)->format('d/m/Y') }}</td>
                <td class="px-4 py-2">
                  <div class="flex space-x-4 items-center">
                    <a href="{{ route('toPdf', $muestreo->id) }}">
                        <img src="{{ asset('pdf.png') }}" alt="path" width="50">
                    </a>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>


   

  </div>
</div>
@endsection

@section('scripts')
<script>
  function revisarInputs() {
    event.preventDefault();
    const inputs = document.querySelectorAll('input[type="text"]');
    const algunoConTexto = Array.from(inputs).some(input => input.value.trim() !== '');

    if (algunoConTexto) {
      document.getElementById('ose').submit();
    } else {
      alert("Se debe ingresar los datos del proceso.");
    }
  }


</script>


@endsection
