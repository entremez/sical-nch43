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
            <label for="laboratorio_id" class="block mb-1">Laboratorio</label>
            <select name="laboratorio_id" id="laboratorio_id" class="select2 w-full border rounded" required></select>
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

        <div id="caracteristicas-dinamicas" class="mb-6 space-y-4"></div>

        <div class="mb-6">
            <label for="modelo_id" class="block mb-1">Agregar modelo(s)</label>
            <select id="modelo_id" class="select2-modelos w-full border rounded"></select>
            
            <div id="modelos-seleccionados" class="mt-4 space-y-2">
                <!-- Listado de modelos seleccionados -->
            </div>
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
                        results: data.map(item => ({ id: item.id, text: item.text }))
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
                        results: data.map(item => ({ id: item.id, text: item.text }))
                    }),
                    cache: true
                }
            });

            $('#laboratorio_id').select2({
                placeholder: 'Buscar laboratorio',
                ajax: {
                    url: '{{ route("api.laboratorios") }}',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({ q: params.term }),
                    processResults: data => ({
                        results: data
                    }),
                    cache: true
                }
            });


        $('#protocolo_id').select2({
            placeholder: 'Seleccione un protocolo',
            ajax: {
                url: '{{ route("api.protocolos") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        area: $('#area').val(),
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            templateResult: function (data) {
                if (!data.id) return data.text;
                const option = $(`<span data-nombre="${data.name}">${data.text}</span>`);
                return option;
            },
            templateSelection: function (data) {
                // Set attribute on selection for later retrieval
                setTimeout(() => {
                    const selectedOption = $('#protocolo_id').find(':selected');
                    selectedOption.attr('data-nombre', data.name); // <-- aquí se guarda
                }, 10);
                return data.text;
            }
        });


            $('#area').on('change', function () {
                $('#protocolo_id').val(null).trigger('change');
                $('#caracteristicas-dinamicas').empty();
            });

            $('#protocolo_id').on('select2:select', function (e) {
                const data = e.params.data;
                const codigo = data.name; // <-- accede directamente al campo enviado en el response
                const container = document.getElementById('caracteristicas-dinamicas');
                container.innerHTML = '';

                if (!codigo) return;

                fetch(`/api/caracteristicas-del-protocolo/${codigo}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.error) {
                            container.innerHTML = `<p class="text-red-500">${data.error}</p>`;
                            return;
                        }
                        container.insertAdjacentHTML('beforeend', `<div class="text-xl">Carácterísticas técnicas</div>`);
                        data.forEach((field, index) => {
                            const unidad = field.unidad ? ` [${field.unidad}]` : '';
                            const label = `<label class="block font-semibold">${field.nombre}${unidad} ${field.obligatorio ? '<span class="text-red-500">*</span>' : ''}</label>`;
                            let input = '';

                            switch (field.tipo) {
                                case 'text':
                                    input = `<input type="text" name="caracteristicas[${field.id}]" class="w-full border px-3 py-1" ${field.obligatorio ? 'required' : ''}>`;
                                    break;
                                case 'number':
                                    input = `<input type="number" name="caracteristicas[${field.id}]" class="w-full border px-3 py-1" ${field.obligatorio ? 'required' : ''}>`;
                                    break;
                                case 'checkbox':
                                    input = `<input type="checkbox" name="caracteristicas[${field.id}]" class="mr-2">`;
                                    break;
                                case 'select':
                                    let opcionesArray = [];

                                    try {
                                        opcionesArray = JSON.parse(field.opciones || '[]');
                                    } catch (e) {
                                        console.error('Error al parsear opciones:', e);
                                    }

                                    const opciones = [`<option value="">Seleccionar...</option>`]
                                        .concat(opcionesArray.map(opt => `<option value="${opt}">${opt}</option>`))
                                        .join('');
                                    input = `<select name="caracteristicas[${field.id}]" class="w-full border px-3 py-1" ${field.obligatorio ? 'required' : ''}>${opciones}</select>`;
                                    break;
                            }

                            container.insertAdjacentHTML('beforeend', `<div>${label}${input}</div>`);
                        });
                    })
                    .catch(() => {
                        container.innerHTML = `<p class="text-red-500">Error al cargar campos.</p>`;
                    });
            });
        });

        let modelosAgregados = new Set();

        $('#modelo_id').select2({
            placeholder: 'Buscar modelo de producto',
            ajax: {
                url: '{{ route("api.modelos") }}',
                dataType: 'json',
                delay: 250,
                data: params => ({ q: params.term }),
                processResults: data => ({
                    results: data.map(item => ({
                        id: item.id,
                        text: `${item.marca} - ${item.modelo} (${item.denominacion})`
                    }))
                }),
                cache: true
            }
        }).on('select2:select', function (e) {
            const modelo = e.params.data;
            if (modelosAgregados.has(modelo.id)) return;

            modelosAgregados.add(modelo.id);

            const div = document.createElement('div');
            div.classList.add('flex', 'items-center', 'justify-between', 'bg-gray-100', 'px-3', 'py-2', 'rounded');
            div.dataset.id = modelo.id;

            div.innerHTML = `
                <span>${modelo.text}</span>
                <input type="hidden" name="modelos[]" value="${modelo.id}">
                <button type="button" class="text-red-600 remove-modelo ml-4">❌</button>
            `;

            document.getElementById('modelos-seleccionados').appendChild(div);
        });

        $(document).on('click', '.remove-modelo', function () {
            const div = this.closest('div[data-id]');
            const id = div.dataset.id;

            modelosAgregados.delete(id);
            div.remove();
        });


    </script>
@endpush
