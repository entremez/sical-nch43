<form action="{{ route('preview') }}" method="post">
    @csrf
    <label for="id_proceso">Id proceso (OSE)</label>
    <input required type="text" name="id_proceso" value="{{ isset($id_proceso)?$id_proceso:'' }}"><br>
    <label for="cliente">Cliente</label>
    <input required type="text" name="cliente" value="{{ isset($cliente)?$cliente:'' }}"><br>
    <label for="cliente">Contacto</label>
    <input required type="text" name="contacto" value="{{ isset($contacto)?$contacto:'' }}"><br>
    <label for="cliente">Dirección muestreo</label>
    @if (isset($direccion_fabrica))
        <select required name="direccion" id="direccion">
            <option value="">Seleccionar...</option>
            <option value="{{ $direccion_fabrica }}">Fábrica: {{ $direccion_fabrica }}</option>
            <option value="{{ $direccion }}">Bodega: {{ $direccion }}</option>
        </select>
    @else
        <input required type="text" name="direccion" value="{{ isset($direccion)?$direccion:'' }}">
    @endif
    <br>
    
    <label for="sistema">Sistema de certificación</label>
    @php
        $once = isset($sistema) && $sistema == '011'?'selected':'';
        $doce = isset($sistema) && $sistema == '012'?'selected':'';
        $trece = isset($sistema) && $sistema == '013'?'selected':'';
        $veintiuno = isset($sistema) && $sistema == '021'?'selected':'';
        $veintidos = isset($sistema) && $sistema == '022'?'selected':'';
        $treintiuno = isset($sistema) && $sistema == '031'?'selected':'';
        $sesentiuono = isset($sistema) && $sistema == '061'?'selected':'';
    @endphp
    <select name="sistema" id="sistema" required>
        <option value="">Seleccionar...</option>
        <option value="011" {{ $once }} >011</option>
        <option value="012" {{ $doce }} >012</option>
        <option value="013" {{ $trece }} >013</option>
        <option value="021" {{ $veintiuno }} >021</option>
        <option value="022" {{ $veintidos }} >022</option>
        <option value="031" {{ $treintiuno }} >031</option>
        <option value="061" {{ $sesentiuono }} >061</option>
    </select><br>
    <label for="marca">Marca</label>
    <input required type="text" name="marca" value="{{ isset($marca)?$marca:'' }}"><br>
    <label for="modelo">Modelo</label>
    <input required type="text" name="modelo" value="{{ isset($modelo)?$modelo:'' }}"><br>
    <label for="lote">Lote</label>
    <input required type="text" name="lote" value="{{ isset($lote)?$lote:'' }}"><br>
    <label for="muestras">Muestras</label>
    <input required type="number" name="muestras" value="{{ isset($muestras)?$muestras:'' }}"><br>
    <label for="denominacion">Dernominación técnica</label>
    <input required type="text" name="denominacion" value="{{ isset($denominacion)?$denominacion:'' }}"><br>
    <label for="fecha">Fecha de muestreo</label>
    <input required type="date" name="fecha" value="{{ isset($fecha)?$fecha:Carbon\Carbon::now()->format('Y-m-d') }}"><br>
    <label for="sicalito">Sello identificación 1ra muestra (sicalito)</label>
    <input required type="number" name="sicalito" value="{{ isset($sicalito)?$sicalito:'' }}" placeholder="XXXX">-
    <input required type="number" name="year" value="{{ Carbon\Carbon::now()->year }}"><br>
    <label for="coordenadas">Coordenadas iniciales</label>
    <input required type="number" name="row" value="{{ isset($row)?$row:'' }}" placeholder="150" id="row"><span> / </span>
    <input required type="number" name="column" value="{{ isset($column)?$column:'' }}" placeholder="12" id="column"><input type="checkbox" id="random" onclick="randomize()">Coordenadas aleatorias <br>
    <label>Orden toma de muestras</label><br>
    @php
        $ltr = $rtl = $utd = $dtu = $ftb = $btf = $comment = '';
    if(isset($comentarios)){
        $ltr = $comentarios[0] == "ltr"?'checked':'';
        $rtl = $comentarios[0] == "rtl"?'checked':'';
        $utd = $comentarios[1] == "utd"?'checked':'';
        $dtu = $comentarios[1] == "dtu"?'checked':'';
        $ftb = $comentarios[2] == "ftb"?'checked':'';
        $btf = $comentarios[2] == "btf"?'checked':'';
        $comment = isset($comentarios[3])?$comentarios[3]:'';
    }
    @endphp
<!--
    <label for="disposicion">Disposición del lote</label><br>

    <select name="disposicion" id="disposicion" required>
        <option value="">Seleccionar...</option>
        <option value="">Unidades</option>
        <option value="">Cajas</option>
        <option value="">Bolsa</option>
        <option value="">Display</option>
    </select><br>
-->

    <input type="radio" id="ltr" name="equis" value="ltr" required {{ $ltr }} />
    <label for="ltr">Izquierda a derecha</label>
    <input type="radio" id="rtl" name="equis" value="rtl" required {{ $rtl }} />
    <label for="rtl">Derecha a izquierda</label><br>
    <input type="radio" id="utd" name="ye" value="utd" required {{ $utd }} />
    <label for="utd">Arriba a abajo</label>
    <input type="radio" id="dtu" name="ye" value="dtu" required {{ $dtu }} />
    <label for="dtu">Abajo a arriba</label><br>
    <input type="radio" id="ftb" name="zeta" value="ftb" required {{ $ftb }} />
    <label for="ftb">Frente al fondo</label>
    <input type="radio" id="btf" name="zeta" value="btf" required {{ $btf }} />
    <label for="btf">Fondo al frente</label><br>
    <label for="comentarios">Comentarios</label><br>
    <textarea name="comentarios" id="comentarios" rows="4" cols="50">{{ $comment }}</textarea>


    <br>
    <input type="submit" value="Generar coordenadas">
</form>

<script>
    function randomize() {
        const ischecked = document.getElementById('random').checked;
        if (ischecked) {
            document.getElementById('row').value = (Math.floor(Math.random() * 250)) + 1;
            document.getElementById('column').value = (Math.floor(Math.random() * 20)) + 1;
        } else {
            document.getElementById('row').value = '';
            document.getElementById('column').value = '';
        }
    }
</script>