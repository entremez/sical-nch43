<form action="{{ route('search') }}" method="post">
    @csrf
    <input type="text" id="search" name="search" placeholder="Buscar">
</form>
<h2>Últimos muestreos</h2>

@if($muestreos->count()==0)
<p>No hay muestreos registrados</p>
@else
    <table>
        <thead>
            <tr>
                <th>Id proceso</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($muestreos as $muestreo)

            <tr>
                <td>{{ $muestreo->id_proceso }}</td>
                <td>{{ Carbon\Carbon::parse($muestreo->fecha)->format('d/m/Y') }}</td>
                <td>
                    @if($muestreo->muestreo==null)
                    <a href="{{ route('searchWithId', $muestreo->id) }}">
                        <img src="{{ asset('sample.png') }}" alt="path" width="50">
                    </a>
                    @elseif($muestreo->muestreo!=null && $muestreo->trazabilidad==null)
                    <div style="display: flex; justify-content: space-between;">
                        <a href="{{ route('searchWithId', $muestreo->id) }}">
                            <img src="{{ asset('sample.png') }}" alt="path" width="50">
                        </a>
                        <a href="{{ route('track', $muestreo->id) }}">
                            <img src="{{ asset('material-management.png') }}" alt="path" width="50">
                        </a>
                    </div>
                    @elseif($muestreo->muestreo!=null && $muestreo->trazabilidad!=null)
                    <a href="{{ route('toPdf', $muestreo->id) }}">
                        <img src="{{ asset('pdf.png') }}" alt="path" width="50">
                    </a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif