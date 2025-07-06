@extends('layouts.default')

@section('title', 'Plantillas de Características Técnicas')

@section('content')
<div class="max-w-6xl mx-auto mt-8">
    <h1 class="text-2xl font-bold mb-6">Plantillas de Características Técnicas</h1>

    @foreach($categories as $category)
        <div class="mb-6 border rounded-lg p-5 bg-white shadow-md">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ $category->name }}
                    <span class="text-sm text-gray-500">({{ $category->area->nombre ?? 'Sin área' }})</span>
                </h2>
                <a href="{{ route('characteristic-templates.edit-category', $category) }}" class="text-sm text-blue-600 underline hover:text-blue-800">
                    Editar campos
                </a>
            </div>

            @if ($category->templates->count())
                <table class="w-full table-auto text-sm border mt-3">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="border px-3 py-2 text-left">Nombre</th>
                            <th class="border px-3 py-2 text-left">Tipo</th>
                            <th class="border px-3 py-2 text-left">Unidad</th>
                            <th class="border px-3 py-2 text-left">Opciones</th>
                            <th class="border px-3 py-2 text-center">Obligatorio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($category->templates as $template)
                            <tr class="border-t">
                                <td class="px-3 py-2">{{ $template->nombre }}</td>
                                <td class="px-3 py-2 capitalize">{{ $template->tipo }}</td>
                                <td class="px-3 py-2">{{ $template->unidad ?? '-' }}</td>
                                <td class="px-3 py-2">
@if($template->tipo === 'select')
    {{ implode(', ', json_decode($template->opciones, true) ?? []) }}
@else
    -
@endif

                                </td>
                                <td class="px-3 py-2 text-center">
                                    {{ $template->obligatorio ? '✅' : '❌' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-500 italic mt-2">No hay campos definidos aún.</p>
            @endif
        </div>
    @endforeach
</div>
@endsection
