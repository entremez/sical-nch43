import './bootstrap';

import $ from 'jquery';

import DataTable from 'datatables.net-dt';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

$(document).ready(function () {
    $('#tabla-muestreos').DataTable({
      paging: true,
      pageLength: 10,
      searching: true,
      ordering: true,
      order: [[1, 'desc']]
    });
  });
