<form action="{{ route('end') }}" method="post">
    @csrf
    <label for="id_proceso">Id proceso (OSE)</label>
    <input type="text"  value="{{ $muestreo->id_proceso }}" disabled><br>
    <label for="marca">Marca</label>
    <input type="text"  value="{{ $muestreo->marca }}"  disabled><br>
    <label for="modelo">Modelo</label>
    <input type="text"  value="{{ $muestreo->modelo }}" disabled><br>
    <label for="lote">Lote</label>
    <input type="text"  value="{{ $muestreo->lote }}"  disabled><br>
    <label for="muestras">Muestras</label>
    <input type="number"  value="{{ $muestreo->muestras }}" id="muestras" disabled><br>
    <label for="denominacion">Dernominación técnica</label>
    <input type="text"  value="{{ $muestreo->denominacion }}" disabled><br>
    <label for="fecha">Fecha de muestreo</label>
    <input type="date"  value="{{ $muestreo->fecha }}" disabled><br>
    <label for="sicalito">Sello identificación 1ra muestra (sicalito)</label>
    <input type="text"  value="{{ isset($muestreo->sicalito)?$muestreo->sicalito:'' }}" placeholder="XXXX" disabled>-
    <input type="number"  value="{{ Carbon\Carbon::now()->year }}" disabled><br>

    <h2>Trazabilidad</h2>
    @php
        $muestras = collect(json_decode($muestreo->muestreo, true))->where('valido', true)->values();
    @endphp

    @foreach ($muestras as $key => $muestra)
    <div id="{{$key}}">
        <input type="text" value="{{ $key+1 }}" disabled>
        <input type="text" value="{{ $muestra['valor_final'] }}" disabled>
        <input type="text" name="trazabilidad[]" value="" required>
        <input type="radio" name="repetir" onclick="getValue({{$key}})">Repetir
    </div>
    @endforeach
    <input type="hidden" name="id" value="{{ $muestreo->id }}">
    <input type="submit" value="Generar registro plan de  muestreo">
</form>

<script>
    function getValue(id){
        const valor = document.getElementById(id).children[2].value;
        const muestras = document.getElementById('muestras').value;
        for (var i = id+1; i < muestras; i++) {
            document.getElementById(i).children[2].value = valor;
        }
    }
</script>