<?php if (isset($estado)) {
    if ($estado > 0) { ?>
        <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
            <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE GUARDÓ CORRECTAMENTE</div>
            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="fa-times fas fs-3 text-primary"></i>
            </button>
        </div>
    <?php } else { ?>
        <div class="alert alert-danger display-hide" style="display: block; text-align: center;">
            <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">YA EXISTE UN CLIENTE CON EL MISMO EMAIL EN EL SISTEMA<br><a href="javascript:history.back();">Volver</a></div>
            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="fa-times fas fs-3 text-primary"></i>
            </button>
        </div>
<?php }
} ?>
<?php if (isset($borrado)) { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE BORRÓ CORRECTAMENTE</div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="fa-times fas fs-3 text-primary"></i>
        </button>
    </div>
<?php } ?>
<?php if (isset($estado_fusion)) {
    if ($estado_fusion == 1) { ?>
        <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
            <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center"> LA FUSIÓN DE LOS CLIENTES MARCADOS SE HA REALIZADO CORRECTAMENTE: <a href="<?php echo base_url(); ?>recursos/logs/fusiones_clientes.log" target="_blank">VER LOG CON MÁS INFORMACIÓN DEL PROCESO</a></div>
            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="fa-times fas fs-3 text-primary"></i>
            </button>
        </div>
<?php }
} ?>
<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="tabla_clientes">
            </div>

        </div>
        <div id="buttons"></div>
        
		<div class="card-title w-100 justify-content-end flex-wrap">

            <div class="m-1">
                <div class="input-group mb-3">
                    <span class="input-group-text">Desde</span>
                    <input type="date" id="fecha_desde" name="fecha_desde" value="<?= (isset($fecha_desde)) ? $fecha_desde : '' ?>" class="form-control form-control-solid w-auto" placeholder="Fecha desde" required />
                </div>
            </div>

            <div class="m-1">
                <div class="input-group mb-3">
                    <span class="input-group-text">Hasta</span>
                    <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?= (isset($fecha_hasta)) ? $fecha_hasta : '' ?>" class="form-control form-control-solid w-auto" placeholder="Fecha hasta" required />
                </div>
            </div>

			<div class="m-1">
                <div class="input-group mb-3">
                    <span class="input-group-text">Importe</span>
                    <input type="number" id="minimo" name="minimo" value="<?= (isset($minimo)) ? $minimo : '' ?>" class="form-control form-control-solid w-auto" placeholder="Importe minimo total" required />
                </div>
            </div>
		</div>
    </div>
    <div class="card-body pt-6">
        <form id="form_marcar" action="<?php echo base_url(); ?>clientes/fusionar" method="post" name="form_marcar">
            <div class="table-responsive">
                <table id="tabla_clientes" class="table align-middle table-striped table-row-dashed fs-6 gy-5"></table>
            </div>
        </form>
    </div>
</div>
<script>
    window.onload = function() {
        <?php
        if (isset($logs)) {
            foreach ($logs as $log) {
                echo 'console.log("' . $log . '");';
            }
        }
        ?>
    }

    var tabla_clientes = $("#tabla_clientes").DataTable({
        info: true,
        paging: true,
        ordering: true,
        searching: true,
        stateSave: false,
        processing: true,
        serverSide: true,
        scrollX: true,
        autoWidth: false,
        order: [0, "desc"],
        pageLength: 50,
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Todos"],
        ],
        columns: [{
                //0
                title: "#",
                name: "id_cliente",
                data: "id_cliente",
                render: function(data, type, row) {
                	return row.id_cliente;
                }
            },
            {
                //1
                title: "Cliente",
                name: "cliente",
                data: "cliente",
                render: function(data, type, row) {
                    var html = `<a class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6" href="<?php echo base_url(); ?>clientes/historial/ver/${row.id_cliente}">${row.cliente}</a>`;
                    return html
                }
            },
            {
                // 2
                title: "DNI",
                name: "dni",
                data: "dni",
                render: function(data, type, row) {
                    var html = row.dni;
                    return html
                }
            },
            {
                // 3
                title: "Email",
                name: "email",
                data: "email",
                render: function(data, type, row) {
                    var html = row.email;
                    return html
                }
            },
            {
                // 4
                title: "Teléfono",
                name: "telefono",
                data: "telefono",
                render: function(data, type, row) {
                    var html = row.telefono;
                    return html
                }
            },

            {
                // 5
                title: "Importe",
                name: "total_importe",
                data: "total_importe",
                render: function(data, type, row) {
                    var html = row.total_importe;
                    return html
                }
            }
        ],

        columnDefs: [{
                targets: [0, 1, 2, 3, 4, 5],
                visible: true,
            },
            {
                targets: ["_all"],
                visible: false,
            },
            /*{
                targets: [-2],
                orderable: false,
            },*/
        ],

        ajax: {
            url: "<?php echo base_url(); ?>Gestion/get_pacientes347",
            type: "GET",
            datatype: "json",
            data: function(data) {
                var fecha_desde = $('#fecha_desde').val();
                var fecha_hasta = $('#fecha_hasta').val();
				var minimo = $('#minimo').val();
                if (fecha_desde != "") {
                    data.fecha_desde = fecha_desde;
                }
                if (fecha_hasta != "") {
                    data.fecha_hasta = fecha_hasta;
                }
				if (minimo != "") {
                    data.minimo = minimo;
                }
            },
        },
        language: {
            "sProcessing": "Procesando...",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfoEmpty": "No hay resultados",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "sLengthMenu": "<div class=\"\">_MENU_</div>",
            "sSearch": "<div class=\"\">_INPUT_</div>",
            "sSearchPlaceholder": "Escribe para buscar...",
            "sInfo": "_START_ de _END_ (_TOTAL_ total)",
            "oPaginate": {
                "sPrevious": "",
                "sNext": ""
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
        dom: "<'table-responsive'tr>" +
            "<'row'" +
            "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'li>" +
            "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
            ">",
        buttons: {
            buttons: [
                {
                text: "Exportar Excel",
                extend: "excelHtml5",
                title: 'Comisiones',
                className: "btn btn-warning text-inverse-warning",
                attr: {
                    "data-tooltip": "Exportar tabla en excel",
                    "data-placement": "auto",
                    title: "Exportar tabla en excel",
                },
                exportOptions: {
                    columns: ":not(.noexp)",
                    orthogonal: "export",
                },
            },
            ],
            dom: {
                button: {
                    className: "btn",
                },
            },
        },
        headerCallback: function(thead, data, start, end, display) {},
        createdRow: function(row, data, dataIndex) {
            $(row).attr('id', 'fila' + data.id_cliente);
        },
        drawCallback: function(settings) {
            $('thead tr').addClass('text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0')
        },
        initComplete: function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    'trigger': 'hover'
                })
            })
        },
    });

    var buttons = new $.fn.dataTable.Buttons(tabla_clientes, {
        buttons: [
            {
            text: "Exportar Excel",
            extend: "excelHtml5",
            title: 'Pacienter 347',
            className: "btn btn-warning text-inverse-warning",
            attr: {
                "data-tooltip": "Exportar tabla en excel",
                "data-placement": "auto",
                title: "Exportar tabla en excel",
            },
            exportOptions: {
                columns: ":not(.noexp)",
                orthogonal: "export",
            },
        }, {
            text: "Exportar CSV",
            extend: "csvHtml5",
            title: 'Pacientes347',
            className: "btn btn-warning text-inverse-warning",
            attr: {
                "data-tooltip": "Exportar tabla en CSV",
                "data-placement": "auto",
                title: "Exportar tabla en CSV",
            },
            exportOptions: {
                columns: ":not(.noexp)",
                orthogonal: "export",
            },
        }        ]
    }).container().appendTo($('#buttons'));

    $('[data-table-search]').on('input', function() {
        tabla_clientes.search($(this).val()).draw();
    });

    
	$('#minimo').on('change', function() {
        tabla_clientes.draw();
    });
	$('#fecha_desde').on('change', function() {
        tabla_clientes.draw();
    });
	$('#fecha_hasta').on('change', function() {
        tabla_clientes.draw();
    });
</script>