<?php if (isset($msn_estado)) {
    if ($msn_estado > 0) { ?>
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

<div class="card card-flush">

    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title w-100 justify-content-between">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="tabla_liquidacion">
            </div>
            <div id="buttons"></div>
        </div>
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

            <div class="m-1" style="min-width: 200px;">
                <select name="id_usuario" id="id_usuario" class="form-select form-select-solid" data-control="select2" data-placeholder="Elegir ...">
                    <option value="">Todos los usuarios</option>
                    <?php foreach ($usuarios as $rs) { ?>
                        <option value="<?php echo $rs['id_usuario']; ?>"><?php echo $rs['nombre'] . ' ' . $rs['apellidos'] . ' (' . $rs['nombre_centro'] . ')'; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">
            <table id="tabla_liquidacion" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th class="col_id">#</th>
                        <th>Mes</th>
                        <th>Empleado</th>
                        <th class="col_validez">Citas hasta</th>
                        <th class="col_presu">Total</th>
                        <th class="col_aceptado">Fecha liquidacion</th>
                        <th class="col_fecha">Us. liquidación</th>
                        <th></th>
                        <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                            <th></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold"></tbody>
            </table>
        </div>
    </div>
</div>



<div class="modal fade" id="modal-liquidacion" aria-labelledby="modal-liquidacionLabel" data-bs-focus="false" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer p-1">
                <button type="button" class="btn btn-sm btn-secondary text-inverse-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    var valorpagar = 0;
    var inputpagar = '';
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
    //array php de centros a javascript
    var centros = <?php echo json_encode($centros); ?>;


    var tabla_liquidacion = $("#tabla_liquidacion").DataTable({
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
                titlee: "",
                name: "id_liquidacion",
                data: "id_liquidacion",
                render: function(data, type, row) {
                    return row.id_liquidacion;
                }
            },
            {
                //1
                titlee: "",
                name: "mes",
                data: "mes",
                render: function(data, type, row) {
                    return row.mes;
                }
            },
            {
                //2
                titlee: "",
                name: "empleado",
                data: "empleado",
                render: function(data, type, row) {
                        console.log(row)
                        return row.empleado + ' (' + row.nombre_centro_usuario + ')';

                }
            },
            {
                // 3
                titlee: "",
                name: "fecha_hasta",
                data: "fecha_hasta",
                render: function(data, type, row) {
                    var html = row.fecha_hasta;
                    return html
                }
            },
            {
                // 4
                name: "total",
                data: "total",
                render: function(data, type, row) {
                    var html = row.total;
                    return html
                }
            },
            {
                // 5
                name: "fecha_creacion",
                data: "fecha_creacion",
                render: function(data, type, row) {
                    var html = row.fecha_creacion;
                    return html
                }
            },
            {
                // 2
                titlee: "",
                name: "usuario_liquidacion",
                data: "usuario_liquidacion",
                render: function(data, type, row) {
                    var html = row.usuario_liquidacion;
                    return html;
                }
            },
            {
                // 6
                name: "",
                data: "",
                render: function(data, type, row) {
                    var html = `<button type="button" class="btn btn-sm btn-icon btn-warning" data-ver data-bs-toggle="tooltip" title="Detalle de la liquidacion"><i class="fa-solid fa-eye"></i></button>`;
                    if (row.estado == 0) {
                        html += `<button type="button" class="btn btn-sm btn-icon btn-primary" data-archivar data-bs-toggle="tooltip" title="Archivar"><i class="fa-solid fa-archive"></i></button>`;
                    }
                    <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                        if (row.estado == 1) {
                            html += `<button type="button" class="btn btn-icon btn-outline btn-outline-info btn-sm" data-desarchivar data-bs-toggle="tooltip" title="Desarchivar"><i class="fa-solid fa-edit"></i></button>`;
                        }
                    <?php } ?>
                    return html
                }
            },
            <?php if ($this->session->userdata('id_perfil') == 0) { ?> {
                    // 7
                    titlee: "",
                    name: "",
                    data: "",
                    render: function(data, type, row) {
                        var html = `<button type="button" class="btn btn-sm btn-icon btn-danger" data-del data-bs-toggle="tooltip" title="Eliminar liquidacion"><i class="fa-solid fa-trash"></i></button>`;
                        return html
                    }
                },
            <?php } ?>
        ],
        columnDefs: [{
                targets: [0, 1, 2, 3, 4, 5, 6, 7 <?= ($this->session->userdata('id_perfil') == 0) ? ', 8' : '' ?>],
                visible: true,
            },
            {
                targets: ["_all"],
                visible: false,
            },
            {
                targets: [-1],
                orderable: false,
            },
            {
                targets: ['col_id', 'col_validez'],
                className: 'text-center'
            },
            {
                targets: ['col_aceptado', 'col_desc', 'col_presu_sin_desc', 'col_presu'],
                className: 'text-end'
            },
        ],
        ajax: {
            url: "<?php echo base_url(); ?>Liquidaciones/get_liquidaciones",
            type: "GET",
            datatype: "json",
            data: function(data) {
                console.log(data)
                var fecha_desde = $('[name="fecha_desde"]').val();
                var fecha_hasta = $('[name="fecha_hasta"]').val();
                var id_usuario = $('[name="id_usuario"]').val();
                if (fecha_desde != "") {
                    data.fecha_desde = fecha_desde;
                }
                if (fecha_hasta != "") {
                    data.fecha_hasta = fecha_hasta;
                }
                if (id_usuario != "") {
                    data.id_usuario = id_usuario;
                }
            },
            error: function(xhr, error, code) {
                console.error(xhr);
                console.error(code);
                console.error(error);
            }
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
        drawCallback: function(settings) {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    'trigger': 'hover'
                })
            })
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
    console.log(tabla_liquidacion)
    var buttons = new $.fn.dataTable.Buttons(tabla_liquidacion, {
        buttons: [{
            text: "Excel",
            extend: "excelHtml5",
            title: 'Liquidaciones',
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
            title: 'Liquidaciones',
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
        tabla_liquidacion.search($(this).val()).draw();
    });
    $('#filter_estado').on('change', function() {
        tabla_liquidacion.draw();
    });
    $('#id_usuario').on('change', function() {
        tabla_liquidacion.draw();
    });
    $('#id_cliente').on('change', function() {
        tabla_liquidacion.draw();
    });
    $('#fecha_desde').on('change', function() {
        tabla_liquidacion.draw();
    });
    $('#fecha_hasta').on('change', function() {
        tabla_liquidacion.draw();
    });


    $(document).on('click', '[data-ver]', function(event) {
        var button = $(this);
        var data = tabla_liquidacion.row(button.parents("tr")).data();
        var url = '<?= base_url() ?>Liquidaciones/ver_detalle/' + data.id_liquidacion;
        $('#modal-liquidacion .modal-title').html('Detalle de la liquidacion');
        $('#modal-liquidacion .modal-body').html('<i class="fas fa-sync fa-spin"></i>');
        document.querySelectorAll('.tooltip').forEach(function(element) {
            element.remove();
        });
        $('#modal-liquidacion').modal('show');
        $.get(url, function(data) {
            $('#modal-liquidacion .modal-body').html(data);
        });
    });

    $(document).on('click', '[data-archivar]', function() {
        var button = $(this);
        Swal.fire({
            title: 'Archivar liquidación',
            html: `¿Desea archivar la liquidación? No podrá añadir nuevas citas`,
            showCancelButton: true,
            confirmButtonText: 'Si, archivar liquidación',
            showLoaderOnConfirm: true
        }).then((result) => {
            if (result.isConfirmed) {
                var data = tabla_liquidacion.row(button.parents("tr")).data();
                $.ajax({
                    url: '<?= base_url() ?>Liquidaciones/archivarLiquidacion',
                    method: 'POST',
                    data: {
                        id_liquidacion: data.id_liquidacion,
                        estado: 1
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Archivando',
                            text: 'Por favor, espera...',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            onBeforeOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        if (response.success) {

                            Swal.fire({
                                type: 'success',
                                title: 'Liquidacion archivada.',
                                willClose: function() {
                                    tabla_liquidacion.draw();
                                },
                            });
                        } else {
                            Swal.fire({
                                title: 'Error al archivar la liquidación. Inténtelo de nuevo o recargue la página.',
                                type: 'error',
                                willClose: function() {},
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Error en la solicitud AJAX'
                        });
                    }
                });
            }
            return false;
        });
    });

    $(document).on('click', '[data-desarchivar]', function() {
        var button = $(this);
        Swal.fire({
            title: 'Desarchivar liquidación',
            html: `¿Desea desarchivar la liquidación? Podrá añadir nuevas citas`,
            showCancelButton: true,
            confirmButtonText: 'Si, desarchivar liquidación',
            showLoaderOnConfirm: true
        }).then((result) => {
            if (result.isConfirmed) {
                var data = tabla_liquidacion.row(button.parents("tr")).data();
                $.ajax({
                    url: '<?= base_url() ?>Liquidaciones/archivarLiquidacion',
                    method: 'POST',
                    data: {
                        id_liquidacion: data.id_liquidacion,
                        estado: 0
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Realizando la acción',
                            text: 'Por favor, espera...',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            onBeforeOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        if (response.success) {

                            Swal.fire({
                                type: 'success',
                                title: 'Liquidacion disponible.',
                                willClose: function() {
                                    tabla_liquidacion.draw();
                                },
                            });
                        } else {
                            Swal.fire({
                                title: 'Error al desarchivar la liquidación. Inténtelo de nuevo o recargue la página.',
                                type: 'error',
                                willClose: function() {},
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Error en la solicitud AJAX'
                        });
                    }
                });
            }
            return false;
        });
    });

    $(document).on('click', '[data-del]', function() {
        var button = $(this);
        Swal.fire({
            title: 'Borrar liquidación',
            html: `Desea marcar como borrado la liquidación?`,
            showCancelButton: true,
            confirmButtonText: 'Si, borrar liquidación',
            showLoaderOnConfirm: true
        }).then((result) => {
            if (result.isConfirmed) {
                var data = tabla_liquidacion.row(button.parents("tr")).data();
                $.ajax({
                    url: '<?= base_url() ?>Liquidaciones/borrarLiquidacion',
                    method: 'POST',
                    data: {
                        id_liquidacion: data.id_liquidacion
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Borrando',
                            text: 'Por favor, espera...',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            onBeforeOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        if (response.success) {

                            Swal.fire({
                                type: 'success',
                                title: 'Liquidacion borrada. Las citas relacionadas con esta liquidación estan disponibles para aparecer en las búsquedas de citas.',
                                willClose: function() {
                                    tabla_liquidacion.draw();
                                },
                            });
                        } else {
                            Swal.fire({
                                title: 'Error al borrar la liquidación. Inténtelo de nuevo o recargue la página.',
                                type: 'error',
                                willClose: function() {},
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Error en la solicitud AJAX'
                        });
                    }
                });
            }
            return false;
        });
    });



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