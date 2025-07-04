@extends('layouts.default')

@section('title', 'Grupos de Actividades - Detalle')

@section('content')

    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-4">Detalle del grupo</h1>

        <p><strong>Nombre:</strong> {{ $document->name }}</p>
        <p><strong>Descripción:</strong> {{ $document->description ?? '—' }}</p>

        <a href="{{ route('documents.index') }}" class="text-blue-500 mt-4 inline-block">Volver</a>
    </div>


@endsection