@extends('layouts.default')

@section('title', 'Grupos de actividades por solicitud')

@section('content')

    <div class="max-w-3xl mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-4">Editar actividades para "{{ $document->name }}"</h1>

        <form method="POST" action="{{ route('aplication-documents.update-activities', $document->id) }}">
            @csrf @method('PUT')

            <div class="space-y-2">
                @foreach($activities as $activity)
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="activities[]" value="{{ $activity->id }}"
                                   class="mr-2"
                                   {{ $document->activities->contains($activity->id) ? 'checked' : '' }}>
                            {{ $activity->name }}
                        </label>
                    </div>
                @endforeach
            </div>

            <button class="mt-4 bg-green-600 text-white px-4 py-2 rounded">Guardar</button>
            <a href="{{ route('aplication-documents.index') }}" class="ml-4 text-blue-600">Cancelar</a>
        </form>
    </div>


@endsection