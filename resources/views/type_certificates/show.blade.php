@extends('layouts.default')

@section('title', 'Detalle Certificado de Tipo')

@section('content')
<div class="max-w-3xl mx-auto mt-8">
    <h1 class="text-xl font-bold mb-4">Certificado de Tipo: {{ $typeCertificate->certificado }}</h1>

    <p><strong>Cliente:</strong> {{ $typeCertificate->cliente_id }}</p>
    <p><strong>Fábrica:</strong> {{ $typeCertificate->fabrica_id }}</p>
    <p><strong>Informe:</strong> {{ $typeCertificate->test_report_number }} ({{ $typeCertificate->test_report_date }})</p>
    <p><strong>Área:</strong> {{ $typeCertificate->area }}</p>

    <div class="mt-4">
        <a href="{{ route('type-certificates.edit', $typeCertificate) }}" class="text-blue-600">Editar</a>
    </div>
</div>
@endsection
