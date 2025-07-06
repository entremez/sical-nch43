@extends('layouts.default')

@section('title', 'Nuevo Certificado de Tipo')

@section('content')
<div class="max-w-3xl mx-auto mt-8">
    <h1 class="text-xl font-bold mb-4">Nuevo Certificado de Tipo</h1>

    <form action="{{ route('type-certificates.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="scope" class="block">Ámbito</label>
            <select name="scope" id="scope" class="w-full rounded px-3 py-2" required>
                <option value="">Seleccionar ámbito...</option>
                @foreach($scopes as $label)
                    <option value="{{ $label->id }}">{{ $label->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block">Solicitud de certificación</label>
            <input type="text" name="solicitud" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block">Fecha de solicitud de certificación</label>
            <input type="date" name="fecha_solicitud" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block">Número de Certificado Tipo</label>
            <input type="text" name="certificado" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block">Fecha del certificado</label>
            <input type="date" name="fecha_certificado" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block">N° SEC certificado tipo</label>
            <input type="number" name="sec" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Cliente</label>
            <select name="cliente_id" id="cliente_id" class="select2 w-full border rounded" required></select>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Fábrica</label>
            <select name="fabrica_id" id="fabrica_id" class="select2 w-full border rounded" required></select>
        </div>

        <div class="mb-4">
            <label class="block">N° Informe de Ensayo</label>
            <input type="text" name="test_report_number" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block">Fecha del Informe</label>
            <input type="date" name="test_report_date" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label for="area" class="block">Área</label>
            <select name="area" id="area" class="w-full rounded px-3 py-2" required>
                <option value="">Seleccionar área...</option>
                @foreach($areas as $key => $label)
                    <option value="{{ $label->id }}">{{ $label->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block">Denominación comercial</label>
            <input type="text" name="denominacion_comercial" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label for="protocolo_id" class="block">Protocolo asociado</label>
            <select id="protocolo_id" name="protocolo_id" class="w-full rounded px-3 py-2" required></select>
        </div>

        <button class="bg-green-600 text-white px-4 py-2 rounded">Guardar</button>
    </form>
</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#cliente_id').select2({
                placeholder: 'Buscar cliente',
                ajax: {
                    url: '{{ route("api.clientes") }}',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({ q: params.term }),
                    processResults: data => ({
                        results: data.map(item => ({
                            id: item.id,
                            text: item.text  // ← usa directamente el texto que ya viene armado
                        }))
                    }),
                    cache: true
                }
            });


            $('#fabrica_id').select2({
                placeholder: 'Buscar fábrica',
                ajax: {
                    url: '{{ route("api.fabricas") }}',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({ q: params.term }),
                    processResults: data => ({
                        results: data.map(item => ({
                            id: item.id,
                            text: item.text  // ← usa el campo formateado desde backend
                        }))
                    }),
                    cache: true
                }
            });

        });

        $('#protocolo_id').select2({
            placeholder: 'Seleccione un protocolo',
            ajax: {
                url: '{{ route("api.protocolos") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        area: $('#area').val(), // Valor actual del área seleccionada
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        // Resetear protocolo al cambiar el área
        $('#area').on('change', function () {
            $('#protocolo_id').val(null).trigger('change');
        });
    </script>
@endpush
