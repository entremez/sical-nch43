@extends('layouts.default')

@section('title', 'Editar datos de texto completo')

@section('content')

@php
        $once = isset($texto->sistema) && $texto->sistema == '011'?'selected = "true"':'';
        $doce = isset($texto->sistema) && $texto->sistema == '012'?'selected = "true"':'';
        $trece = isset($texto->sistema) && $texto->sistema == '013'?'selected = "true"':'';
        $veintiuno = isset($texto->sistema) && $texto->sistema == '021'?'selected = "true"':'';
        $veintidos = isset($texto->sistema) && $texto->sistema == '022'?'selected = "true"':'';
        $treintiuno = isset($texto->sistema) && $texto->sistema == '031'?'selected = "true"':'';
        $sesentiuono = isset($texto->sistema) && $texto->sistema == '061'?'selected = "true"':'';
        $ltr = $rtl = $utd = $dtu = $ftb = $btf = $comment = '';
    if(json_decode($texto->comentarios)){
        $ltr = json_decode($texto->comentarios)[0] == "ltr"?'checked':'';
        $rtl = json_decode($texto->comentarios)[0] == "rtl"?'checked':'';
        $utd = json_decode($texto->comentarios)[1] == "utd"?'checked':'';
        $dtu = json_decode($texto->comentarios)[1] == "dtu"?'checked':'';
        $ftb = json_decode($texto->comentarios)[2] == "ftb"?'checked':'';
        $btf = json_decode($texto->comentarios)[2] == "btf"?'checked':'';
        $comment = isset(json_decode($texto->comentarios)[3])?json_decode($texto->comentarios)[3]:'';
    }

@endphp

<form  method="POST" action={{ route('update', $texto) }} class="bg-white p-6 mt-8 rounded-lg shadow max-w-4xl mx-auto">
    @csrf

    <input type="hidden" name="id" value="{{ $texto->id }}">

    {{-- Mensajes de error --}}
    <h1 class="text-2xl font-bold mb-6">Editar datos de muestreo</h1>

    {{-- Datos generales --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold mb-1">Cliente</label>
            <input type="text" name="cliente" value="{{ old('cliente', $texto->cliente) }}" class="form-input w-full">
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Contacto</label>
            <input type="text" name="contacto" value="{{ old('contacto', $texto->contacto) }}" class="form-input w-full">
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Dirección</label>
            <input type="text" name="direccion" value="{{ old('direccion', $texto->direccion) }}" class="form-input w-full">
        </div>
        <div>
            <label for="sistema" class="block font-medium mb-1">Sistema de certificación</label>
            <select name="sistema" id="sistema" required class="w-full border rounded p-2">
            <option value="">Seleccionar...</option>
            <option value="011" {{ $once }}>011</option>
            <option value="012" {{ $doce }}>012</option>
            <option value="013" {{ $trece }}>013</option>
            <option value="021" {{ $veintiuno }}>021</option>
            <option value="022" {{ $veintidos }}>022</option>
            <option value="031" {{ $treintiuno }}>031</option>
            <option value="061" {{ $sesentiuono }}>061</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Marca</label>
            <input type="text" name="marca" value="{{ old('marca', $texto->marca) }}" class="form-input w-full">
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Modelo</label>
            <input type="text" name="modelo" value="{{ old('modelo', $texto->modelo) }}" class="form-input w-full">
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Lote</label>
            <input type="text" name="lote" value="{{ old('lote', $texto->lote) }}" class="form-input w-full">
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Muestras</label>
            <input type="number" name="muestras" id="muestras" value="{{ old('muestras', $texto->muestras) }}"  disabled class="form-input w-full">
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Denominación</label>
            <input type="text" name="denominacion" value="{{ old('denominacion', $texto->denominacion) }}" class="form-input w-full">
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Fecha</label>
            <input type="date" name="fecha" value="{{ old('fecha', $texto->fecha) }}" class="form-input w-full">
        </div>
    </div>

    {{-- Comentarios codificados --}}
      <div class="mb-4">
        <!-- Orden toma de muestras -->
            <label class="block font-semibold mt-6 mb-2">Orden toma de muestras</label>

            <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <input type="radio" id="ltr" name="equis" value="ltr" required {{ $ltr }} class="mr-1">
                <label for="ltr">Izquierda a derecha</label>
            </div>
            <div>
                <input type="radio" id="rtl" name="equis" value="rtl" required {{ $rtl }} class="mr-1">
                <label for="rtl">Derecha a izquierda</label>
            </div>

            <div>
                <input type="radio" id="utd" name="ye" value="utd" required {{ $utd }} class="mr-1">
                <label for="utd">Arriba a abajo</label>
            </div>
            <div>
                <input type="radio" id="dtu" name="ye" value="dtu" required {{ $dtu }} class="mr-1">
                <label for="dtu">Abajo a arriba</label>
            </div>

            <div>
                <input type="radio" id="ftb" name="zeta" value="ftb" required {{ $ftb }} class="mr-1">
                <label for="ftb">Frente al fondo</label>
            </div>
            <div>
                <input type="radio" id="btf" name="zeta" value="btf" required {{ $btf }} class="mr-1">
                <label for="btf">Fondo al frente</label>
            </div>
            </div>

      </div>

    {{-- Comentarios --}}
    <div class="mt-8">
        <label class="block text-sm font-semibold mb-2">Comentarios</label>
        <textarea name="comentarios" rows="4" class="form-textarea w-full">{{ old('comentarios', $comment) }}</textarea>
    </div>

    <h2 class="text-xl font-semibold mb-2 mt-6">Trazabilidad</h2>

    @php
        $muestras = collect(json_decode($texto->muestreo, true))->where('valido', true)->values();
        $trazabilidad = json_decode($texto->trazabilidad);

        // Normaliza sicalito a array
        $raw = $texto->sicalito;
        $decoded = is_array($raw) ? $raw : json_decode($raw, true);

        if (is_array($decoded)) {
            $esArray = true;
            $correlativos = array_values($decoded);
            $convertirAlGuardar = false; // ya es array
        } else {
            // No era array: generamos uno desde sicalito inicial y cantidad de muestras
            $start = (int) preg_replace('/\D/', '', (string) $texto->sicalito);
            $count = (int) $texto->muestras;
            $correlativos = $count > 0 ? range($start, $start + $count - 1) : [];
            $esArray = true; // << mostrará la columna Sicalitos
            $convertirAlGuardar = true; // << para que el controlador lo guarde convertido
        }
    @endphp

    <div class="space-y-4">
        <div class="grid grid-cols-5 gap-2 font-semibold text-gray-700 mb-2 mt-4">
            <div>#</div>
            <div>Sicalitos</div>
            <div>Número tabla</div>
            <div>Trazabilidad</div>
            <div></div>
        </div>
        @foreach ($muestras as $key => $muestra)
                <div id="{{ $key }}" class="grid grid-cols-5 gap-2 items-center fila-muestra"
                    data-index="{{ $key }}">
            <input type="text" id="index_{{ $key }}" value="{{ $key + 1 }}" disabled class="border rounded p-2 bg-gray-100 text-center">

            <input type="text" name="sicalito[]" id="sicalito_{{ $key }}" value="{{ $correlativos[$key] }}"
                        class="border rounded p-2">

            <input type="text" id="valor_{{ $key }}" value="{{ $muestra['valor_final'] }}" disabled class="border rounded p-2 bg-gray-100">
            <input type="text" name="trazabilidad[]" id="trazabilidad_{{ $key }}" placeholder="Fecha, número de serie o sello" required class="border rounded p-2" value="{{ isset($trazabilidad[$key]) ? $trazabilidad[$key] : '' }}">
            <div class="flex items-center">
                <input type="radio" name="repetir" id="repetir_{{ $key }}" onclick="getValue({{ $key }})" class="mr-2">
                <label for="repetir_{{ $key }}" class="text-sm">Repetir</label>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-8 flex justify-end items-center">
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg shadow">
            Guardar cambios
        </button>

        <a href="{{ route('delete', $texto->id) }}"
        onclick="return confirm('¿Estás seguro que deseas eliminar este registro? Esta acción no se puede deshacer.');"
        class="ml-3 bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg shadow">
            Eliminar registro
        </a>
    </div>

</form>
@endsection


<script>
    function getValue(idx) {
        // Fila origen
        const row = document.getElementById(String(idx));
        if (!row) return;

        // Toma SIEMPRE el input de trazabilidad dentro de la fila
        const source = row.querySelector('input[name="trazabilidad[]"]');
        if (!source) return;

        const valor = source.value;

        // Itera todas las filas que siguen
        const rows = document.querySelectorAll('.fila-muestra');
        for (let i = idx + 1; i < rows.length; i++) {
            const nextRow = document.getElementById(String(i));
            if (!nextRow) continue;
            const target = nextRow.querySelector('input[name="trazabilidad[]"]');
            if (target && !target.disabled) {
                target.value = valor;
            }
        }
    }
</script>