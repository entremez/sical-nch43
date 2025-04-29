@extends('layouts.default')

@section('title', 'Etapa I. Datos y generar coordenadas.')

@section('content')

    @php
        $once = isset($sistema) && $sistema == '011'?'selected':'';
        $doce = isset($sistema) && $sistema == '012'?'selected':'';
        $trece = isset($sistema) && $sistema == '013'?'selected':'';
        $veintiuno = isset($sistema) && $sistema == '021'?'selected':'';
        $veintidos = isset($sistema) && $sistema == '022'?'selected':'';
        $treintiuno = isset($sistema) && $sistema == '031'?'selected':'';
        $sesentiuono = isset($sistema) && $sistema == '061'?'selected':'';

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


<div class="flex justify-center mt-4">



    <form id="form" action="{{ route('preview') }}" method="post" class="w-full md:w-1/2 bg-white p-6 rounded-lg shadow" onsubmit="checkForm(event)">
      @csrf

      @if(!empty($error))
        <div class="mb-4 red-text bg-blue-500 text-white px-4 py-3 rounded-lg">
          {{ $error }}
        </div>
      @endif

      <nav class="text-gray-600 text-sm mb-4" aria-label="Breadcrumb">
        <ol class="list-reset flex items-center space-x-2">
          <li>
            <a href="{{ route('home') }}" class="text-blue-600 hover:underline">Inicio</a>
          </li>
          <li>
            <span class="mx-1">/</span>
          </li>
          <li class="text-gray-500" aria-current="page">Etapa I. Solicitud y generación de coordenadas</li>
        </ol>
      </nav>

      <h1 class="text-3xl font-bold mb-4">Etapa I. Solicitud y generación de coordenadas</h1>
      <div class="mb-4">
        <label for="id_proceso" class="block font-medium mb-1">Id proceso (OSE)</label>
        <input required type="text" name="id_proceso" value="{{ $id_proceso ?? '' }}" class="w-full border rounded p-2">
      </div>
  
      <div class="mb-4">
        <label for="cliente" class="block font-medium mb-1">Cliente</label>
        <input required type="text" name="cliente" value="{{ $cliente ?? '' }}" class="w-full border rounded p-2">
      </div>
  
      <div class="mb-4">
        <label for="contacto" class="block font-medium mb-1">Contacto</label>
        <input required type="text" name="contacto" value="{{ $contacto ?? '' }}" class="w-full border rounded p-2">
      </div>
  
      <div class="mb-4">
        <label for="direccion" class="block font-medium mb-1">Dirección de muestreo</label>
        @if (isset($direccion_fabrica))
          <select required name="direccion" id="direccion" class="w-full border rounded p-2">
            <option value="">Seleccionar...</option>
            <option value="{{ $direccion_fabrica }}">Fábrica: {{ $direccion_fabrica }}</option>
            <option value="{{ $direccion }}">Bodega: {{ $direccion }}</option>
          </select>
        @else
          <input required type="text" name="direccion" value="{{ $direccion ?? '' }}" class="w-full border rounded p-2">
        @endif
      </div>
  
      <div class="mb-4">
        <label for="sistema" class="block font-medium mb-1">Sistema de certificación</label>
        <select name="sistema" id="sistema" required class="w-full border rounded p-2">
          <option value="">Seleccionar...</option>
          <option value="011" {{ $once }}>011</option>
          <option value="012" {{ $doce }}>012</option>
          <option value="013" {{ $trece }}>013</option>
          <option value="021" {{ $veintiuno }}>021</option>
          <option value="022" {{ $veintidos }}>022</option>
          <option value="031" {{ $treintiuno }}>031</option>
          <option value="061" {{ $sesentiuono }}>061</option>
        </select>
      </div>
  
      <div class="mb-4">
        <label for="marca" class="block font-medium mb-1">Marca</label>
        <input required type="text" name="marca" value="{{ $marca ?? '' }}" class="w-full border rounded p-2">
      </div>
  
      <div class="mb-4">
        <label for="modelo" class="block font-medium mb-1">Modelo</label>
        <input required type="text" name="modelo" value="{{ $modelo ?? '' }}" class="w-full border rounded p-2">
      </div>
  
      <div class="mb-4">
        <label for="lote" class="block font-medium mb-1">Lote</label>
        <input required id="lote" type="text" name="lote" value="{{ $lote ?? '' }}" class="w-full border rounded p-2">
      </div>
  
      <div class="mb-4">
        <label for="muestras" class="block font-medium mb-1">Muestras</label>
        <input required type="number" name="muestras" value="{{ $muestras ?? '' }}" class="w-full border rounded p-2">
      </div>
  
      <div class="mb-4">
        <label for="denominacion" class="block font-medium mb-1">Denominación técnica</label>
        <input required type="text" name="denominacion" value="{{ $denominacion ?? '' }}" class="w-full border rounded p-2">
      </div>
  
      <div class="mb-4">
        <label for="fecha" class="block font-medium mb-1">Fecha de muestreo</label>
        <input required type="date" name="fecha" value="{{ $fecha ?? Carbon\Carbon::now()->format('Y-m-d') }}" class="w-full border rounded p-2" min="2025-04-24">
      </div>
  
      <div class="mb-4">
        <label class="block font-medium mb-1">Sello identificación (sicalito)</label>
        <div class="flex gap-2">
          <input required type="number" name="sicalito" value="{{ $sicalito ?? '' }}" placeholder="XXXX" class="w-full border rounded p-2">
          <span>-</span>
          <input required type="number" name="year" value="{{ Carbon\Carbon::now()->year }}" class="w-full border rounded p-2">
        </div>
      </div>
  
      <div class="mb-4">
        <label for="coordenadas" class="block font-medium mb-1">Coordenadas iniciales</label>
        <div class="flex items-center gap-2">
          <input required type="number" id="row" name="row" value="{{ $row ?? '' }}" placeholder="150" class="w-1/2 border rounded p-2">
          <span>/</span>
          <input required type="number" id="column" name="column" value="{{ $column ?? '' }}" placeholder="12" class="w-1/2 border rounded p-2">
        </div>
        <div class="mt-2">
          <input type="checkbox" id="random" onclick="randomize()">
          <label for="random" class="ml-2 text-sm">Coordenadas aleatorias</label>
        </div>
      </div>
  
      <div class="mb-4">
        <!-- Orden toma de muestras -->
            <label class="block font-semibold mt-6 mb-2">Orden toma de muestras</label>

            <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <input type="radio" id="ltr" name="equis" value="ltr" required {{ $ltr }} class="mr-1">
                <label for="ltr">Izquierda a derecha</label>
            </div>
            <div>
                <input type="radio" id="rtl" name="equis" value="rtl" required {{ $rtl }} class="mr-1">
                <label for="rtl">Derecha a izquierda</label>
            </div>

            <div>
                <input type="radio" id="utd" name="ye" value="utd" required {{ $utd }} class="mr-1">
                <label for="utd">Arriba a abajo</label>
            </div>
            <div>
                <input type="radio" id="dtu" name="ye" value="dtu" required {{ $dtu }} class="mr-1">
                <label for="dtu">Abajo a arriba</label>
            </div>

            <div>
                <input type="radio" id="ftb" name="zeta" value="ftb" required {{ $ftb }} class="mr-1">
                <label for="ftb">Frente al fondo</label>
            </div>
            <div>
                <input type="radio" id="btf" name="zeta" value="btf" required {{ $btf }} class="mr-1">
                <label for="btf">Fondo al frente</label>
            </div>
            </div>

      </div>
  
      <div class="mb-4">
        <label for="comentarios" class="block font-medium mb-1">Comentarios</label>
        <textarea name="comentarios" id="comentarios" rows="4" class="w-full border rounded p-2">{{ $comment }}</textarea>
      </div>
  
      <div class="text-center">
        <input type="submit" value="Generar coordenadas" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition" >
      </div>
    </form>
  </div>
  
@endsection

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

    function checkForm(e) {
      //Dado que en SCP pueden venir texto y números, se valida que sea un número para enviar al generador de muestras
      e.preventDefault();
      const inputQty = document.getElementById('lote');
      if (isFinite(Number(inputQty.value))) {
        document.getElementById('form').submit();
      } else {
        inputQty.focus();
        alert("El campo 'Lote' debe ser un número. De existir más texto, dejar en comentarios.");
      } 
      
    }
</script>