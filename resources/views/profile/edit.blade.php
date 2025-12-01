@extends('layouts.default')

@section('title', 'Editar usuarios y firmas')

@section('content')



    <form method="POST" action="{{ route('profile.firmas.update') }}"
          class="bg-white p-6 mt-8 rounded-lg shadow max-w-4xl mx-auto">
        @csrf
        @method('PUT')

        <h2 class="text-xl font-semibold mb-4">Firmas por usuario</h2>

        <table class="w-full border-collapse text-sm mb-6">
            <thead>
                <tr class="border-b bg-gray-100">
                    <th class="p-2 text-left">Usuario</th>
                    <th class="p-2 text-left">Email</th>
                    <th class="p-2 text-left">Firma</th>
                    <th class="p-2 text-left">Vista previa</th>
                    <th class="p-2 text-left">Nueva firma</th>
                </tr>
            </thead>
            <tbody>

                @foreach($users as $u)
                    @php
                        $signArr = $u->sign ? json_decode($u->sign, true) : null;
                        $currentInitials = $signArr[0] ?? '';
                        $currentFile     = $signArr[1] ?? '';
                    @endphp

                    <tr class="border-b">
                        <td class="p-2">{{ $u->name }}</td>
                        <td class="p-2">{{ $u->email }}</td>

                        <td class="p-2">
                            <select
                                name="users[{{ $u->id }}][sign]"
                                class="border rounded px-2 py-1 w-full"
                                data-preview="#preview-{{ $u->id }}"
                            >
                                <option value="">-- Sin firma --</option>
                                @foreach ($firmas as $f)
                                    @php
                                        $valueJson = json_encode([$f['initials'], $f['file']]);
                                    @endphp

                                    <option value="{{ $valueJson }}"
                                            data-file="{{ $f['file'] }}"
                                            @selected($currentInitials === $f['initials'])
                                    >
                                        {{ $f['initials'] }} ({{ $f['file'] }})
                                    </option>
                                @endforeach
                            </select>
                        </td>

                        <td class="p-2">
                            @if($currentFile)
                                <img id="preview-{{ $u->id }}"
                                     src="{{ asset($currentFile) }}"
                                     class="h-20 border rounded">
                            @else
                                <img id="preview-{{ $u->id }}"
                                     class="h-12 border rounded">
                            @endif
                        </td>
                        <td class="p-2 text-gray-600 italic">
                            <a href="{{ route('profile.firma.create', $u->id) }}"
                            class="px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 inline-block text-center">
                                Agregar nueva
                            </a>
                        </td>
                    </tr>

                @endforeach
            </tbody>
        </table>

        <button type="submit"
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
            Guardar cambios
        </button>
    </form>


    {{-- Script de preview para cada usuario --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const baseUrl = "{{ asset('') }}";

            document.querySelectorAll("select[data-preview]").forEach(select => {
                select.addEventListener("change", function() {

                    const previewId = this.dataset.preview;
                    const img = document.querySelector(previewId);
                    const option = this.selectedOptions[0];
                    const file = option.dataset.file ?? null;

                    img.src = file ? `${baseUrl}/${file}` : "";
                });
            });
        });
    </script>

@endsection

