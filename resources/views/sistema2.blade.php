@foreach ($certificados as $certificado)
    <div class="container">
                            <strong>Certificado:</strong> {{ $certificado->certificado }}<br>
                            <strong>Fecha:</strong> {{ $certificado->fecha }}<br>
                            <strong>QR:</strong> {{ $certificado->qr }}<br>
                            <strong>Denominación:</strong> {{ $certificado->producto }}<br>
                            <strong>Fabricante:</strong> {{ $certificado->fabrica }}<br>
                            <strong>Dirección fábrica:</strong> {{ $certificado->direccion_fabrica }}<br>
                            <strong>Dirección bodega:</strong> {{ $certificado->direccion }}<br>
                            <strong>Marca:</strong> {{ $certificado->marca }}<br>
                            <strong>Modelo:</strong> {{ $certificado->modelo }}<br>
                            <strong>Contacto:</strong> {{ $certificado->contacto }}<br>
    </div>
    <a href="{{ route('sistema2', $certificado->certificado) }}">Muestreo</a><br><br>
    
@endforeach