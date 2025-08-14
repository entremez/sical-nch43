@extends('layouts.default')

@section('title', 'Etapa II. Registro de trazabilidad.')

@section('content')

    <form action="{{ route('end') }}" method="post" class="w-full md:w-1/2 mx-auto bg-white p-6 mt-8 rounded-lg shadow">

        <nav class="text-gray-600 text-sm mb-4" aria-label="Breadcrumb">
            <ol class="list-reset flex items-center space-x-2">
                <li>
                    <a href="{{ route('home') }}" class="text-blue-600 hover:underline">Inicio</a>
                </li>
                <li>
                    <span class="mx-1">/</span>
                </li>
                <li class="text-gray-500" aria-current="page">
                    <a href="{{ route('searchWithId', $id) }}" class="text-blue-600 hover:underline">Etapa I. Solicitud y
                        generación de coordenadas</a>
                </li>
                <li>
                    <span class="mx-1">/</span>
                </li>
                <li class="text-gray-500" aria-current="page">Trazabilidad de muestras</li>
            </ol>
        </nav>

        @csrf

        <h1 class="text-2xl font-bold mb-4">Trazabilidad de muestras</h1>

        <div class="mb-4">
            <label for="id_proceso" class="block font-medium mb-1">Id proceso (OSE)</label>
            <input type="text" id="id_proceso" name="id_proceso" value="{{ $muestreo->id_proceso }}" disabled
                class="w-full border rounded p-2 bg-gray-100">
        </div>

        <div class="mb-4">
            <label for="marca" class="block font-medium mb-1">Marca</label>
            <input type="text" id="marca" name="marca" value="{{ $muestreo->marca }}" disabled
                class="w-full border rounded p-2 bg-gray-100">
        </div>

        <div class="mb-4">
            <label for="modelo" class="block font-medium mb-1">Modelo</label>
            <input type="text" id="modelo" name="modelo" value="{{ $muestreo->modelo }}" disabled
                class="w-full border rounded p-2 bg-gray-100">
        </div>

        <div class="mb-4">
            <label for="lote" class="block font-medium mb-1">Lote</label>
            <input type="text" id="lote" name="lote" value="{{ $muestreo->lote }}" disabled
                class="w-full border rounded p-2 bg-gray-100">
        </div>

        <div class="mb-4">
            <label for="muestras" class="block font-medium mb-1">Muestras</label>
            <input type="number" id="muestras" name="muestras" value="{{ $muestreo->muestras }}" disabled
                class="w-full border rounded p-2 bg-gray-100">
        </div>

        <div class="mb-4">
            <label for="denominacion" class="block font-medium mb-1">Denominación técnica</label>
            <input type="text" id="denominacion" name="denominacion" value="{{ $muestreo->denominacion }}" disabled
                class="w-full border rounded p-2 bg-gray-100">
        </div>

        <div class="mb-4">
            <label for="fecha" class="block font-medium mb-1">Fecha de muestreo</label>
            <input type="date" id="fecha" name="fecha" value="{{ $muestreo->fecha }}" disabled
                class="w-full border rounded p-2 bg-gray-100">
        </div>



        @php
            // Normaliza sicalito a array
            $raw = $muestreo->sicalito;
            $decoded = is_array($raw) ? $raw : json_decode($raw, true);

            if (is_array($decoded)) {
                $esArray = true;
                $correlativos = array_values($decoded);
                $convertirAlGuardar = false; // ya es array
            } else {
                // No era array: generamos uno desde sicalito inicial y cantidad de muestras
                $start = (int) preg_replace('/\D/', '', (string) $muestreo->sicalito);
                $count = (int) $muestreo->muestras;
                $correlativos = $count > 0 ? range($start, $start + $count - 1) : [];
                $esArray = true; // << mostrará la columna Sicalitos
                $convertirAlGuardar = true; // << para que el controlador lo guarde convertido
            }
        @endphp

        <h2 class="text-xl font-semibold mb-2 mt-6">Trazabilidad</h2>

        @php
            $muestras = collect(json_decode($muestreo->muestreo, true))
                ->where('valido', true)
                ->values();
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
                    <input type="text" id="index_{{ $key }}" value="{{ $key + 1 }}" disabled
                        class="border rounded p-2 bg-gray-100 text-center">

                    <input type="text" name="sicalito[]" id="sicalito_{{ $key }}" value="{{ $correlativos[$key].'-'.$muestreo->year }}"
                        class="border rounded p-2">

                    <input type="text" id="valor_{{ $key }}" value="{{ $muestra['valor_final'] }}" disabled
                        class="border rounded p-2 bg-gray-100">

                    <input type="text" name="trazabilidad[]" id="trazabilidad_{{ $key }}"
                        placeholder="Fecha, número de serie o sello" required class="border rounded p-2">

                    <div class="flex items-center">
                        <input type="radio" name="repetir" id="repetir_{{ $key }}"
                            onclick="getValue({{ $key }})" class="mr-2">
                        <label for="repetir_{{ $key }}" class="text-sm">Repetir</label>
                    </div>
                </div>
            @endforeach
        </div>

        <input type="hidden" name="id" id="id" value="{{ $muestreo->id }}">

        <div class="text-center mt-6">
            <input type="submit" value="Generar registro plan de muestreo"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
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
