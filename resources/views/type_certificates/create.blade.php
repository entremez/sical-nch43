@extends('layouts.default')

@section('title', 'Nuevo Certificado de Tipo')

@section('content')
<div class="max-w-3xl mx-auto mt-8">
    <h1 class="text-xl font-bold mb-4">Nuevo Certificado de Tipo</h1>

    <form action="{{ route('type-certificates.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block">Número de Certificado</label>
            <input type="text" name="certificado" class="w-full border rounded px-3 py-2" required>
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
            <input type="text" name="test_report_number" class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block">Fecha del Informe</label>
            <input type="date" name="test_report_date" class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block">Área</label>
            <input type="number" name="area" class="w-full border rounded px-3 py-2" required>
        </div>

        <button class="bg-green-600 text-white px-4 py-2 rounded">Guardar</button>
    </form>
</div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
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
                            text: item.nombre + ' — ' + item.direccion
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
                            text: item.nombre + ' — ' + item.direccion
                        }))
                    }),
                    cache: true
                }
            });
        });
    </script>
@endpush
