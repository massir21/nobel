<?php if (isset($estado)) {
    if ($estado > 0) { ?>
        <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
            <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE GUARDÓ CORRECTAMENTE</div>
            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="fa-times fas fs-3 text-primary"></i>
            </button>
        </div>
    <?php } else { ?>
        <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
            <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">NO SE HA PODIDO REALIZAR EL REGISTRO DE DATOS</div>
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

<?php if (isset($actionno)) { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center"><?= $actionno ?></div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="fa-times fas fs-3 text-primary"></i>
        </button>
    </div>
<?php } ?>

<style>
    .dataTables_filter {
        text-align: right;
    }
</style>
<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title w-100 justify-content-between">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="myTable1">
            </div>
            <?php /*
            <div class="d-flex align-items-center position-relative my-1">
            <select name="filter_estado" id="filter_estado" class="form-select form-select-solid w-auto">
                <option value="">Cualquier estado</option>
                <option value="Borrador">Borrador</option>
                <option value="Pendiente">Pendiente</option>
                <option value="Aceptado parcial">Aceptado parcial</option>
                <option value="Aceptado">Aceptado</option>
                <option value="Rechazado">Rechazado</option>
            </select>
            </div>
            */ ?>
            <div class="my-1">
            <select name="id_cliente" id="id_cliente" class="form-select form-select-solid w-250px" data-placeholder="Cliente...">
                <?php if (isset($cliente_elegido) && $cliente_elegido[0]['id_cliente'] > 0) { ?>
                    <option value="<?= $cliente_elegido[0]['id_cliente'] ?>" selected><?= $cliente_elegido[0]['nombre'] . ' ' . $cliente_elegido[0]['apellidos'] . ' (' . $cliente_elegido[0]['telefono'] . ')'; ?></option>
                <?php } ?>
            </select>
            <script type="text/javascript">
                $("#id_cliente").select2({
                    language: "es",
                    minimumInputLength: 4,
                    ajax: {
                        delay: 0,
                        url: function(params) {
                            return '<?php echo RUTA_WWW; ?>/clientes/json/' + params.term.replace(/ /g, "_");
                        },
                        dataType: 'json',
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        }
                    }
                });
            </script>
            </div>
            <div id="buttons"></div>
        </div>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">
            <table id="tabla_presupuestos" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th>Fecha / Hora</th>
                        <th>Presupuesto</th>
                        <th>Cliente</th>
                        <th>Concepto</th>
                        <th>Euros</th>
                        <th>Empleado</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold"></tbody>
            </table>
        </div>
    </div>
</div>


<script>
    $('#new_tipo').on('change', function() {
        var tipocomision = document.getElementById('new_tipo').value
        if (tipocomision == 'tramo') {
            $('#comisionestramo_nuevo').slideDown('slow');
        } else {
            $('#comisionestramo_nuevo').slideUp('slow');
        }
    })

    $('#edit_tipo').on('change', function() {
        var tipocomision = document.getElementById('edit_tipo').value
        if (tipocomision == 'tramo') {
            $('#comisionestramo_editar').slideDown('slow');
        } else {
            $('#comisionestramo_editar').slideUp('slow');
        }
    })


    function PagoPresupuesto() {
        var posicion_x;
        var posicion_y;
        var ancho = 800;
        var alto = 800;
        posicion_x = (screen.width / 2) - (ancho / 2);
        posicion_y = (screen.height / 2) - (alto / 2);
        window.open("<?php echo base_url(); ?>presupuestos/pago_presupuesto", "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
    }

    var tabla_presupuestos = $("#tabla_presupuestos").DataTable({
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
                // 0
                titlee: "",
                name: "fecha_creacion",
                data: "fecha_creacion",
                render: function(data, type, row) {
                    return row.f_creacion
                }
            },
            {
                //1
                titlee: "",
                name: "nro_presupuesto",
                data: "nro_presupuesto",
                render: function(data, type, row) {
                    return row.nro_presupuesto;
                }
            },
            {
                //2
                titlee: "",
                name: "cliente",
                data: "cliente",
                render: function(data, type, row) {
                    return row.cliente
                }
            },
            {
                // 3
                titlee: "",
                name: "concepto",
                data: "concepto",
                render: function(data, type, row) {
                    return row.concepto
                }
            },
            {
                // 4
                titlee: "",
                name: "cantidad",
                data: "cantidad",
                render: function(data, type, row) {
                    return row.cantidad
                }
            },
            {
                // 5
                name: "usuario",
                data: "usuario",
                render: function(data, type, row) {
                    return row.usuario
                }
            },
            {
                // 6
                name: "estado",
                data: "estado",
                render: function(data, type, row) {
                    return row.estado
                }
            },
            {
                // 7
                titlee: "",
                name: "",
                data: "",
                render: function(data, type, row) {
                    var html = '';
                    html += `<button type="button" class="btn btn-sm btn-icon btn-info" data-ver data-bs-toggle="tooltip" title="Detalle del presupuesto"><i class="fa fa-eye"></i></button>`;
                    if (row.estado == 'Borrador') {
                        html += `<button type="button" class="btn btn-sm btn-icon btn-warning" data-edit data-bs-toggle="tooltip" title="Editar presupuesto"><i class="fa-regular fa-pen-to-square"></i></button>`;
                    } else {
                        html += `<button type="button" class="btn btn-sm btn-icon btn-warning" data-duplicar data-bs-toggle="tooltip" title="Duplicar presupuesto"><i class="fas fa-clone"></i></button>`;
                        html += `<button type="button" class="btn btn-sm btn-icon btn-warning" data-clonar data-bs-toggle="tooltip" title="Duplicar presupuesto"><i class="fas fa-clone"></i></button>`;
                        if (row.estado == 'Pendiente') {
                            html += `<button type="button" class="btn btn-sm btn-icon btn-primary" data-estado data-bs-toggle="tooltip" title="Gestionar estado"><i class="fas fa-exclamation-triangle"></i></button>`;
                        }
                        <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                            if (row.estado != 'Pendiente') {
                                html += `<button type="button" class="btn btn-sm btn-icon btn-primary" data-estado data-bs-toggle="tooltip" title="Gestionar estado"><i class="fas fa-exclamation-triangle"></i></button>`;
                            }
                        <?php } ?>
                        html += `<button type="button" class="btn btn-sm btn-icon btn-info" data-pdf data-bs-toggle="tooltip" title="Ver presupuesto"><i class="fas fa-file-pdf"></i></button>`;
                    }
                    return html
                }
            }
        ],
        columnDefs: [{
                targets: [0, 1, 2, 3, 4, 5, 6, 7],
                visible: true,
            },
            {
                targets: ["_all"],
                visible: false,
            },
            {
                targets: [-2],
                orderable: false,
            },
            /* { targets: ['col_id','col_validez'], className: 'text-center' },
             { targets: ['col_aceptado','col_desc','col_presu_sin_desc','col_presu'], className: 'text-end' },*/
        ],
        ajax: {
            url: "<?php echo base_url(); ?>Presupuestos/get_presupuestos_pagos",
            type: "GET",
            datatype: "json",
            data: function(data) {
                var id_cliente = $('[name="id_cliente"]').val();
                var fecha_desde = $('[name="fecha_desde"]').val();
                var fecha_hasta = $('[name="fecha_hasta"]').val();
                var estado = $('[name="filter_estado"]').val();
                if (id_cliente != "") {
                    data.id_cliente = id_cliente;
                }
                if (fecha_desde != "") {
                    data.fecha_desde = fecha_desde;
                }
                if (fecha_hasta != "") {
                    data.fecha_hasta = fecha_hasta;
                }
                if (estado != "") {
                    data.estado = estado;
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
            buttons: [{
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
            }, ],
            dom: {
                button: {
                    className: "btn",
                },
            },
        },
        headerCallback: function(thead, data, start, end, display) {},
        createdRow: function(row, data, dataIndex) {},
        drawCallback: function(settings) {},
        initComplete: function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    'trigger': 'hover'
                })
            })
        },
    });

    var buttons = new $.fn.dataTable.Buttons(tabla_presupuestos, {
        buttons: [{
            text: "Excel",
            extend: "excelHtml5",
            title: 'Pagos presupuestos',
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
            text: "CSV",
            extend: "csvHtml5",
            title: 'Pagos presupuestos',
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
        }]
    }).container().appendTo($('#buttons'));

    $('[data-table-search]').on('input', function() {
        tabla_presupuestos.search($(this).val()).draw();
    });

    $('#filter_estado').on('change', function() {
        tabla_presupuestos.search($(this).val()).draw();
    });
    $('#id_usuario').on('change', function() {
        tabla_presupuestos.search($(this).val()).draw();
    });
    $('#id_cliente').on('change', function() {
	    tabla_presupuestos.search($(this).val()).draw();
    });

    $(document).on('click', '[data-pdf]', function(event) {
        var button = $(this);
        var data = tabla_presupuestos.row(button.parents("tr")).data();
        var url = '<?= base_url() ?>presupuestos/ver_pdf/' + data.id_presupuesto;
        //window.location.href = url;  
        var posicion_x;
        var posicion_y;
        var ancho = 600;
        var alto = 450;
        posicion_x = (screen.width / 2) - (ancho / 2);
        posicion_y = (screen.height / 2) - (alto / 2);
        window.open(url, "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
    })

    $(document).on('click', '[data-edit]', function(event) {
        var button = $(this);
        var data = tabla_presupuestos.row(button.parents("tr")).data();
        var url = '<?= base_url() ?>presupuestos/editar_presupuesto/' + data.id_presupuesto;
        window.location.href = url;
    });

    $(document).on('click', '[data-clonar]', function(event) {
        var button = $(this);
        var data = tabla_presupuestos.row(button.parents("tr")).data();
        var url = '<?= base_url() ?>presupuestos/duplicar_presupuesto/' + data.id_presupuesto;
        window.location.href = url;
    });

    $(document).on('click', '[data-duplicar]', function(event) {
        if (confirm('Seguro desea duplicar este presupuesto?\n\nSe generará un nuevo presupuesto con los mismos datos y podrá editarlos ..')) {
            var button = $(this);
            var data = tabla_presupuestos.row(button.parents("tr")).data();
            var url = '<?= base_url() ?>presupuestos/duplicar_presupuesto_nuevo/' + data.id_presupuesto;
            window.location.href = url;
        }
    });

    $(document).on('click', '[data-ver]', function(event) {
        var button = $(this);
        var data = tabla_presupuestos.row(button.parents("tr")).data();
        var url = '<?= base_url() ?>presupuestos/ver_detalle/' + data.id_presupuesto;

        $('#modal-presupuesto .modal-title').html('Detalle del presupuesto');
        $('#modal-presupuesto .modal-body').html('<i class="fas fa-sync fa-spin"></i>');
        $('#modal-presupuesto').modal('show');

        $.get(url, function(data) {
            $('#modal-presupuesto .modal-body').html(data);
        });
    });

    $(document).on('click', '[data-estado]', function(event) {
        var button = $(this);
        var data = tabla_presupuestos.row(button.parents("tr")).data();
        var url = '<?= base_url() ?>Presupuestos/gestionar_estado/' + data.id_presupuesto;
        window.location.href = url;
    })

    $.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons flex-wrap';
    var oldExportAction = function(self, e, dt, button, config) {
        if (button[0].className.indexOf('buttons-excel') >= 0) {
            if ($.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)) {
                $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);
            } else {
                $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
            }
        } else if (button[0].className.indexOf('buttons-print') >= 0) {
            $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
        }
    };
    var newExportAction = function(e, dt, button, config) {
        var self = this;
        var oldStart = dt.settings()[0]._iDisplayStart;
        dt.one('preXhr', function(e, s, data) {
            data.start = 0;
            data.length = 2147483647;
            dt.one('preDraw', function(e, settings) {
                oldExportAction(self, e, dt, button, config);
                dt.one('preXhr', function(e, s, data) {
                    settings._iDisplayStart = oldStart;
                    data.start = oldStart;
                });
                setTimeout(dt.ajax.reload, 0);
                return false;
            });
        });
        dt.ajax.reload();
    };
</script>