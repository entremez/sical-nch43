@extends('layouts.default')

@section('title', 'Asignar protocolos a categorías')

@section('content')
<div class="max-w-5xl mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-4">Asignar protocolos a categorías</h1>

    <form method="POST" action="{{ route('protocols.assign') }}">
        @csrf

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block mb-1">Área</label>
                <select name="area_id" id="area_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Seleccionar área...</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->id }}" data-area="{{ $area->descripcion }}">{{ $area->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1">Categoría</label>
                <select name="category_id" id="category_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Seleccionar categoría...</option>
                    {{-- Las opciones serán cargadas dinámicamente --}}
                </select>
            </div>
        </div>

        <div id="protocolos-list" class="bg-white shadow rounded p-4">
            <h2 class="text-lg font-semibold mb-2">Protocolos disponibles</h2>
            <div class="max-h-80 overflow-y-auto border rounded p-2" id="protocolos-container">
                {{-- Se cargarán los protocolos dinámicamente --}}
            </div>
        </div>

        <div class="mt-4">
            <button class="bg-green-600 text-white px-4 py-2 rounded">Asignar protocolos</button>
        </div>
    </form>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    const areaSelect = document.getElementById('area_id');
    const categorySelect = document.getElementById('category_id');
    const protocolosContainer = document.getElementById('protocolos-container');

    areaSelect.addEventListener('change', function () {
        const areaId = this.value;

        // Cargar categorías
        categorySelect.innerHTML = '<option value="">Cargando...</option>';
        fetch(`/api/categorias-por-area/${areaId}`)
            .then(res => res.json())
            .then(data => {
                categorySelect.innerHTML = '<option value="">Seleccionar categoría...</option>';
                data.forEach(category => {
                    const opt = document.createElement('option');
                    opt.value = category.id;
                    opt.textContent = category.name;
                    categorySelect.appendChild(opt);
                });
            });
    });

    categorySelect.addEventListener('change', function () {
    const areaId = areaSelect;
    const selectedOption = areaId.options[areaId.selectedIndex];
    const dataArea = selectedOption.getAttribute('data-area');

    const categoryId = this.value;

    if (!areaId || !categoryId) return;

    protocolosContainer.innerHTML = '<p class="text-gray-500">Cargando protocolos...</p>';

    fetch(`/api/protocolos-por-area-categoria?area_id=${dataArea}&category_id=${categoryId}`)
        .then(res => res.json())
        .then(data => {
            protocolosContainer.innerHTML = '';
            data.forEach(p => {
                const checkbox = `
                    <div class="flex items-center mb-2">
                        <input 
                            type="checkbox"
                            name="protocol_ids[]"
                            value="${p.id}"
                            id="protocol_${p.id}"
                            ${p.checked ? 'checked' : ''}
                            class="mr-2 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring focus:ring-blue-200"
                        >
                        <label for="protocol_${p.id}" class="text-sm text-gray-700">${p.text}</label>
                    </div>
                `;
                protocolosContainer.insertAdjacentHTML('beforeend', checkbox);
            });
        });
});

});
</script>

