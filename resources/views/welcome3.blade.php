<!DOCTYPE html>
<html lang="es">
  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">

      <title>NCh43</title>
      <style>
        html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}.border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}.text-center{text-align:center}.text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}.text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}.text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}.text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}.text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}.text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}.dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}.dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}.dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}.dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.dark\:text-gray-500{--tw-text-opacity:1;color:#6b7280;color:rgba(107,114,128,var(--tw-text-opacity))}}
        @page{ margin-top: 90px; }
        #header { position: fixed; top: -50px; left: 0; width: 100%; }
        .imgHeader { width: 100px; float: left; }
        .imgSign { aspect-ratio: 1/1; width: 100px; margin-left: 100px; }
        .txtHeader { float: right; font-size: .8rem; }
        table { table-layout: fixed; width: 100%; border-collapse: collapse; border: 1px solid black; font-size: .6rem; text-transform: uppercase; }
        td { border: 1px solid black; padding: 5px; width: 25%; }
        input[type=checkbox] { position: relative; top: 0.5rem; }
      </style>

      <script src="https://cdn.tailwindcss.com"></script>
    </head>

  <body>
    <div id="header">
      <img class="imgHeader" src="{{ public_path('logo.png') }}" alt="logo sical" />
      <p class="txtHeader">RCP-1-2 | Versión: 15 | 24.04.2025</p>
    </div>
    <div class="container">
      <h2>PLAN DE MUESTREO</h2>
        <table>
          <tr>
            <td>Identificación del proceso</td>
            <td>{{ $id_proceso}}</td>
            <td>Fecha del muestreo</td>
            <td>{{ Carbon\Carbon::parse($fecha)->format('d-m-Y') }}</td>
          </tr>
          <tr>
            <td>Cliente</td>
            <td>{{ $cliente }}</td>
            <td>Contacto</td>
            <td>{{ $contacto }}</td>
          </tr>
          <tr>
            <td>Dirección</td>
            <td colspan="3">{{ $direccion }}</td>
          </tr>
            <tr>
            <td>Producto</td>
            <td colspan="3">{{ $denominacion }}</td>
          </tr>
          <tr>
            <td>Marca(s)</td>
            <td colspan="3">{{ $marcas }}</td>
          </tr>
          <tr>
            <td>Modelo(s)</td>
            <td colspan="3">{{ $modelos }}</td>
          </tr>
          <tr>
            <td>Tamaño lote</td>
            <td>{{ $lote }}</td>
            <td>Tamaño de muestra</td>
            <td>{{ $muestras }}</td>
          </tr>
          <tr>
            <td>Código sistema de certificación</td>
            <td>{{ $sistema }}</td>
            <td>Norma de muestreo</td>
            <td>NCh43</td>
          </tr>
          <tr>
            @php
              $ltr = $comentarios[0] == "ltr"?'X':'';
              $rtl = $comentarios[0] == "rtl"?'X':'';
              $utd = $comentarios[1] == "utd"?'X':'';
              $dtu = $comentarios[1] == "dtu"?'X':'';
              $ftb = $comentarios[2] == "ftb"?'X':'';
              $btf = $comentarios[2] == "btf"?'X':'';
              $comment = $comentarios[3] != ''? $comentarios[3]:'-';
            @endphp
            <td>Orden de toma de muestras</td>
            <td style="border-right: none!important;">
              <table style="border:none;">
                <tr style="border:none;" >
                  <td style="width:7%; text-align:center; border:none;">
                    {{ $ltr }}
                  </td>
                  <td  style="width:93%; border:none;">
                    <label for="productos">Izquierda a derecha</label>
                  </td>
                </tr>
                <tr style="border:none;" >
                  <td style="width:7%; text-align:center; border:none;">
                    {{ $rtl }}
                  </td>
                  <td  style="width:93%; border:none;">
                    <label for="productos">Derecha a izquierda</label>
                  </td>
                </tr>
              </table>
            </td>
            <td>
              <table style="border:none;">
                <tr style="border:none;" >
                  <td style="width:7%; text-align:center; border:none;">
                    {{ $utd }}
                  </td>
                  <td  style="width:93%; border:none;">
                    <label for="productos">arriba a abajo</label>
                  </td>
                </tr>
                <tr style="border:none;" >
                  <td style="width:7%; text-align:center; border:none;">
                    {{ $dtu }}
                  </td>
                  <td  style="width:93%; border:none;">
                    <label for="productos">abajo a arriba</label>
                  </td>
                </tr>
              </table> 
            </td>
            <td>
              <table style="border:none;">
                <tr style="border:none;" >
                  <td style="width:7%; text-align:center; border:none;">
                    {{ $ftb }}
                  </td>
                  <td  style="width:93%; border:none;">
                    <label for="productos">frente a fondo</label>
                  </td>
                </tr>
                <tr style="border:none;" >
                  <td style="width:7%; text-align:center; border:none;">
                    {{ $btf }}
                  </td>
                  <td  style="width:93%; border:none;">
                    <label for="productos">fondo a frente</label>
                  </td>
                </tr>
              </table> 
            </td>
          </tr>
          <tr>
            <td>Comentarios</td>
            <td colspan="3" style="min-height: 80px;">{{ $comment }}</td>
          </tr>
          <tr>
            <td>Nombre y firma responsable del muestreo</td>
            <td colspan="3"><img class="imgSign" src="{{ public_path($firma) }}" alt="firma" /></td>
          </tr>
        </table>
        <br>
        <table>
          <tr>
            <td>Coordenada inicial</td>
            <td>{{ $inicial }}</td>
            <td>Coordenada final</td>
            <td>{{ $final }}</td>
          </tr>
        </table>

        {{-- ====== NUEVO: normalización de $sicalito ====== --}}
        @php
          // Puede venir ya como array PHP, como JSON de array, o como escalar
          $sicalitoRaw = $sicalito ?? null;
          $decoded = is_array($sicalitoRaw) ? $sicalitoRaw : json_decode($sicalitoRaw, true);
          $sicalitoIsArray = is_array($decoded);

          if ($sicalitoIsArray) {
              $sicalitos = array_values($decoded);
          } else {
              // Solo números (por si viene con texto)
              $sicalitoBase = (int) preg_replace('/\D/', '', (string) $sicalitoRaw);
          }

          $anioActual = $year;
          $aux = 1; // contador de muestras válidas
        @endphp
        {{-- =============================================== --}}

        <table style="margin-top: .7rem;">
          <thead>
            <td style="text-align: center;width:20px">Muestra N°</td>
            <td style="text-align: center;width:20%">N° tabla</td>
            <td style="text-align: center;width:20%">Sello identificador</td>
            <td style="text-align: center;width:42%">Trazabilidad</td>
          </thead>
          @foreach(json_decode($data) as $key => $coordenada)
            @if($coordenada->valido)
              <tr>
                <td style="text-align: center;">{{ $aux }}</td>
                <td style="text-align: center;">{{ $coordenada->valor_final }}</td>

                {{-- Aquí la lógica: si es array, usa el índice; si no, suma individualmente --}}
                <td style="text-align: center;">
                  @if($sicalitoIsArray)
                    {{ $sicalitos[$aux - 1] ?? '' }}
                  @else
                    {{ $sicalitoBase + $aux - 1 }}-{{ $anioActual }}
                  @endif
                </td>

                <td style="text-align: center;">{{ $trazabilidad[$aux - 1] }}</td>
              </tr>
              @php $aux++; @endphp
            @endif
          @endforeach
        </table>

    </div>
  </body>
</html>
