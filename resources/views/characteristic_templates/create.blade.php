@extends('layouts.default')

@section('title', 'Agregar características técnicas')

@section('content')
<div class="max-w-4xl mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-4">Agregar características técnicas</h1>

    <form method="POST" action="{{ route('characteristic-templates.store') }}">
        @csrf

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-4">
            <label for="category_id" class="block mb-1">Categoría</label>
            <select name="category_id" id="category_id" class="w-full border rounded px-3 py-2" required>
                <option value="">Seleccionar categoría...</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div id="fields-container" class="space-y-4">
            <!-- Campos agregados dinámicamente -->
        </div>

        <button type="button" id="add-field" class="bg-gray-200 px-4 py-2 rounded">+ Agregar campo</button>

        <div class="mt-6">
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded">Guardar todos</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let fieldIndex = 0;

function createField(index) {
    return `
        <div class="field-group grid grid-cols-5 gap-4 border rounded p-3 relative">
            <div>
                <input type="text" name="fields[${index}][nombre]" placeholder="Nombre" class="border px-2 py-1 w-full" required>
            </div>

            <div>
                <input type="text" name="fields[${index}][unidad]" placeholder="Unidad (opcional)" class="border px-2 py-1 w-full">
            </div>

            <div>
                <select name="fields[${index}][tipo]" class="field-type border px-2 py-1 w-full" data-index="${index}">
                    <option value="text">Texto</option>
                    <option value="number">Número</option>
                    <option value="checkbox">Casilla</option>
                    <option value="select">Lista</option>
                </select>
            </div>

            <div>
                <input type="text" name="fields[${index}][opciones]" placeholder="Opciones (separadas por comas)" class="border px-2 py-1 w-full opciones-input hidden">
            </div>

            <div class="flex items-center space-x-2">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="fields[${index}][obligatorio]" class="mr-2"> Obligatorio
                </label>
                <button type="button" class="remove-field text-red-600 font-bold ml-2">❌</button>
            </div>
        </div>
    `;
}

document.getElementById('add-field').addEventListener('click', function () {
    const container = document.getElementById('fields-container');
    container.insertAdjacentHTML('beforeend', createField(fieldIndex++));
});

// Delegar eventos
document.addEventListener('change', function (e) {
    if (e.target.classList.contains('field-type')) {
        const index = e.target.dataset.index;
        const opcionesInput = e.target.closest('.field-group').querySelector('.opciones-input');
        if (e.target.value === 'select') {
            opcionesInput.classList.remove('hidden');
        } else {
            opcionesInput.classList.add('hidden');
        }
    }
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-field')) {
        e.target.closest('.field-group').remove();
    }
});

document.getElementById('category_id').addEventListener('change', function () {
    const categoryId = this.value;
    const container = document.getElementById('fields-container');
    container.innerHTML = ''; // limpiar los anteriores
    fieldIndex = 0;

    if (!categoryId) return;

    fetch(`/api/characteristics-by-category/${categoryId}`)
        .then(res => res.json())
        .then(data => {
            data.forEach(field => {
                const index = fieldIndex++;
                const isSelect = field.tipo === 'select';
                const opciones = field.opciones ? field.opciones.join(', ') : '';

                const fieldHtml = `
                    <div class="field-group grid grid-cols-5 gap-4 border rounded p-3 relative">
                        <div>
                            <input type="text" name="fields[${index}][nombre]" value="${field.nombre}" class="border px-2 py-1 w-full" required>
                        </div>
                        <div>
                            <input type="text" name="fields[${index}][unidad]" value="${field.unidad ?? ''}" class="border px-2 py-1 w-full">
                        </div>
                        <div>
                            <select name="fields[${index}][tipo]" class="field-type border px-2 py-1 w-full" data-index="${index}">
                                <option value="text" ${field.tipo === 'text' ? 'selected' : ''}>Texto</option>
                                <option value="number" ${field.tipo === 'number' ? 'selected' : ''}>Número</option>
                                <option value="checkbox" ${field.tipo === 'checkbox' ? 'selected' : ''}>Casilla</option>
                                <option value="select" ${field.tipo === 'select' ? 'selected' : ''}>Lista</option>
                            </select>
                        </div>
                        <div>
                            <input type="text" name="fields[${index}][opciones]" value="${opciones}" class="border px-2 py-1 w-full opciones-input ${isSelect ? '' : 'hidden'}">
                        </div>
                        <div class="flex items-center space-x-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="fields[${index}][obligatorio]" ${field.obligatorio ? 'checked' : ''} class="mr-2"> Obligatorio
                            </label>
                            <button type="button" class="remove-field text-red-600 font-bold ml-2">❌</button>
                        </div>
                    </div>
                `;

                container.insertAdjacentHTML('beforeend', fieldHtml);
            });
        });
});

</script>
@endpush
