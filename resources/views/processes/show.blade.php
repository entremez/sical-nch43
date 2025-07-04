@extends('layouts.default')

@section('title', 'Procesos detalle')

@section('content')

    <div class="max-w-4xl mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-4">Detalle del proceso</h1>

        <div class="space-y-2">
            <p><strong>Nombre:</strong> {{ $process->name }}</p>
            <p><strong>Documento de aplicación:</strong> {{ $process->aplicationDocument->name ?? '-' }}</p>
        </div>

        <h2 class="text-xl font-semibold mt-6 mb-2">Actividades planificadas</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
        @endif

        <form id="form-actividades" action="{{ route('plan-activities.mass-update', $process) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <table class="w-full border bg-white rounded shadow mt-6">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="p-2">Actividad</th>
                        <th class="p-2">Estado</th>
                        <th class="p-2">Archivos existentes</th>
                        <th class="p-2">Subir nuevos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($process->planActivities as $plan)
                        <tr class="border-t align-top">
                            <td class="p-2">{{ $plan->activity->name }}</td>

                            <td class="p-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="completed[{{ $plan->id }}]" value="1"
                                        {{ $plan->is_completed ? 'checked' : '' }} class="mr-2">
                                    Marcar como completada
                                </label>
                                @if($plan->is_completed)
                                    <p class="text-xs text-green-600">Finalizada el {{ \Carbon\Carbon::parse($plan->completed_at)->format('d-m-Y') }}</p>
                                @endif
                            </td>

                            <td class="p-2 text-sm">
                            @foreach($plan->files as $file)
                                <div class="mb-1 flex items-center justify-between file-entry" data-file-id="{{ $file->id }}">
                                    <div>
                                        <a href="{{ asset('storage/'.$file->path) }}" target="_blank" class="text-blue-600 underline">
                                            {{ $file->document_name ?? $file->original_name }}
                                        </a>
                                        @if($file->document_date)
                                            <span class="text-gray-500 text-xs">({{ \Carbon\Carbon::parse($file->document_date)->format('d-m-Y') }})</span>
                                        @endif
                                    </div>
                                    <button type="button"
                                        class="text-red-500 hover:text-red-700 ml-2 delete-file-btn"
                                        data-file-id="{{ $file->id }}"
                                        data-url="{{ route('plan-files.destroy', $file) }}"
                                        title="Eliminar archivo">
                                        🗑️
                                    </button>
                                </div>
                            @endforeach


                            </td>

                            <td class="p-2">
                                <div id="upload-area-{{ $plan->id }}" class="space-y-2">
                                    <div class="flex flex-col gap-1">
                                        <input type="file" name="files[{{ $plan->id }}][]" multiple>
                                        <!-- Por cada archivo subido, se pueden agregar campos dinámicamente con JS -->
                                        <div class="mt-1">
                                            <label class="block text-sm">Nombre de documento</label>
                                            <input type="text" name="names[{{ $plan->id }}][0]" class="w-full border rounded px-2 py-1">
                                        </div>
                                        <div class="mt-1">
                                            <label class="block text-sm">Fecha del documento</label>
                                            <input type="date" name="dates[{{ $plan->id }}][0]" class="w-full border rounded px-2 py-1">
                                        </div>
                                    </div>
                                </div>
                                <!-- (Opcional) JS para añadir más sets de campos -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-6 text-right">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Guardar todos los cambios</button>
            </div>

        </form>

        <div class="mt-6">
            <a href="{{ route('processes.index') }}" class="text-blue-600">← Volver al listado</a>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form-actividades');

    form.addEventListener('submit', function (e) {

        let isValid = true;
        let errorMessages = [];

        const fileInputs = form.querySelectorAll('input[type="file"]');

        fileInputs.forEach(fileInput => {
            const planIdMatch = fileInput.name.match(/\[(\d+)\]/);
            if (!planIdMatch) return;

            const planId = planIdMatch[1];
            const files = fileInput.files;

            if (files.length > 0) {
                const nameInput = form.querySelector(`input[name="names[${planId}][0]"]`);
                const dateInput = form.querySelector(`input[name="dates[${planId}][0]"]`);

                const name = nameInput?.value?.trim();
                const date = dateInput?.value?.trim();

                if (!name || !date) {
                    isValid = false;
                    errorMessages.push(`Actividad ${planId}: falta nombre o fecha para archivo subido.`);
                }
            }
        });

        if (!isValid) {
            alert(errorMessages.join('\n'));
            e.preventDefault();
            return false;
        }
    });
});
</script>


@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-file-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const url = this.dataset.url;
            const fileId = this.dataset.fileId;
            const container = document.querySelector(`.file-entry[data-file-id="${fileId}"]`);

            if (confirm('¿Eliminar este archivo?')) {
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        container.remove();
                    } else {
                        alert('Error al eliminar el archivo.');
                    }
                })
                .catch(() => {
                    alert('Error de conexión.');
                });
            }
        });
    });
});
</script>
