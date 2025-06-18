import './bootstrap';
import DataTable from 'datatables.net-dt';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

window.$ = $;
window.jQuery = $; // <-- esto habilita '$' globalmente






$(document).ready(function () {
  $('#tabla-muestreos').DataTable({
    paging: true,
    pageLength: 10,
    searching: true,
    ordering: true,
    order: [[1, 'desc']]
  });
});

  $(document).ready(function () {
    $('#tabla-movimientos').DataTable({
      paging: true,
      pageLength: 10,
      ordering: true,
      order: [[1, 'desc']],
      searching: true,
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
      }
    });
  });

//Autocompletar marcas
$(document).ready(function () {
  if ($('#brand_id').length) {
    $('#brand_id').select2({
      placeholder: 'Buscar marca...',
      ajax: {
        url: '/brands/search',
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return { q: params.term };
        },
        processResults: function (data) {
          if (data.length === 0) {
            $('#brand-add').removeClass('hidden');
          } else {
            $('#brand-add').addClass('hidden');
          }

          return {
            results: data.map(item => ({
              id: item.id,
              text: item.name
            }))
          };
        }
      },
      minimumInputLength: 2
    });
  }
});

//Autocompletar denominaciones
$(document).ready(function () {
  if ($('#designation_id').length) {
    $('#designation_id').select2({
      placeholder: 'Buscar denominación...',
      ajax: {
        url: '/denomination/search',
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return { q: params.term };
        },
        processResults: function (data) {
          if (data.length === 0) {
            $('#destination-add').removeClass('hidden');
          } else {
            $('#destination-add').addClass('hidden');
          }

          return {
            results: data.map(item => ({
              id: item.id,
              text: item.name
            }))
          };
        }
      },
      minimumInputLength: 2
    });
  }
});

//Autocompletar clientes
$(document).ready(function () {
  if ($('#cliente_id').length) {
    $('#cliente_id').select2({
      placeholder: 'Buscar cliente...',
      ajax: {
        url: '/clientes/search',
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return { q: params.term };
        },
        processResults: function (data) {
          return {
            results: data.map(item => ({
              id: item.id,
              text: item.name
            }))
          };
        }
      },
      minimumInputLength: 2
    });
  }
});

//agregar modelo en movimientos

$('#modelo_select').select2({
  placeholder: 'Buscar modelo...',
  ajax: {
    url: '/modelos/search',
    dataType: 'json',
    delay: 250,
    data: params => ({ q: params.term }),
    processResults: data => ({
      results: data.map(item => ({
        id: item.id,
        text: item.name,
        marca: item.brand?.name ?? '',
        denominacion: item.designation?.name ?? ''
      }))
    })
  },
  minimumInputLength: 2,

  templateResult: function (item) {
    if (!item.id) return item.text;
    return $(`<div>
      <div class="font-semibold">${item.text}</div>
      <div class="text-sm ">${item.marca} – ${item.denominacion}</div>
    </div>`);
  },

  templateSelection: function (item) {
    return item.marca ? `${item.text} (${item.marca})` : item.text;
  }
});


let productoActualId = null;
let cantidadDisponible = 0;
let filaOriginal = null;

function abrirDesagregar(id, guia, cantidad) {
  productoActualId = id;
  cantidadDisponible = cantidad;

  $('#desagregar-guia-info').text(`Producto ID ${id} – Guía: ${guia}`);
  $('#cantidad-original').text(cantidad);
  $('#divisiones-input').val('');
  filaOriginal = $(`#fila-${id}`);
  $('#modal-desagregar').removeClass('hidden flex').addClass('flex');
}

function cerrarModalDesagregar() {
  $('#modal-desagregar').addClass('hidden').removeClass('flex');
  productoActualId = null;
  cantidadDisponible = 0;
  filaOriginal = null;
}

function procesarDesagregar() {
  const input = $('#divisiones-input').val();
  const divisiones = input.split(',').map(n => parseInt(n.trim())).filter(n => !isNaN(n) && n > 0);
  const suma = divisiones.reduce((acc, val) => acc + val, 0);

  if (suma !== cantidadDisponible) {
    alert(`La suma debe ser exactamente ${cantidadDisponible}`);
    return;
  }

  const filaBase = filaOriginal.clone();
  filaOriginal.remove();

  divisiones.forEach((cantidad, index) => {
    const nuevaFila = filaBase.clone();
    nuevaFila.find('input[type="checkbox"]').val(`${productoActualId}-${index}`);
    nuevaFila.find('td').eq(5).text(cantidad); // actualiza cantidad
    nuevaFila.find('td').eq(6).html('<em>Desagregado</em>');
    $('#tabla-productos tbody').append(nuevaFila);
  });

  cerrarModalDesagregar();
}





