
@extends('layouts.default')

@section('title', 'Nueva solicitud')

@section('content')

    <div class="max-w-3xl mx-auto mt-10">
        <h1 class="text-xl font-bold mb-4">Crear Proceso</h1>

        <form action="{{ route('processes.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block">Nombre del proceso</label>
                <input type="text" name="name" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div>
                <label class="block mb-1">Tipo de documento de aplicación</label>
                <select name="aplication_document_id" id="aplication_document_id" class="w-full border rounded px-3 py-2">
                    @foreach($aplicationDocuments as $doc)
                        <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1">Actividades de evaluación</label>
                <div id="activities-container">
                @foreach($activities as $activity)
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="activities[]" value="{{ $activity->id }}" class="activity-checkbox mr-2">
                            {{ $activity->name }}
                        </label>
                    </div>
                @endforeach
                </div>
            </div>

            <button class="bg-green-600 text-white px-4 py-2 rounded">Crear</button>
        </form>
    </div>


@endsection

<script>
document.addEventListener('DOMContentLoaded', () => {
    const documentSelect = document.getElementById('aplication_document_id');
    const checkboxes = document.querySelectorAll('.activity-checkbox');

    function updateActivities(aplicationDocumentId) {
        fetch(`/aplication-documents/${aplicationDocumentId}/activities`)
            .then(res => res.json())
            .then(checkedIds => {
                checkboxes.forEach(cb => {
                    cb.checked = checkedIds.includes(parseInt(cb.value));
                });
            })
            .catch(err => console.error('Error al cargar actividades:', err));
    }

    // Primera carga
    if (documentSelect.value) {
        updateActivities(documentSelect.value);
    }

    documentSelect.addEventListener('change', () => {
        updateActivities(documentSelect.value);
    });
});
</script>



