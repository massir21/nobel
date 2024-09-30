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
            <div id="buttons"></div>
        </div>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">
            <table id="tabla_comisiones" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Familia</th>
                        <th>Artículo</th>
                        <th>Tipo comisión</th>
                        <th>Comisión</th>
                        <th>Importe desde</th>
                        <th>Importe hasta</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold"></tbody>
            </table>
        </div>
    </div>
    <form name="form_delete_comision_modal" id="delete_comision_modal" action="" method="post">
        <input type="hidden" id="delete_borrado" name="borrado" value="1" />
        <input type="hidden" id="delete_id_comision" name="id_comision" value="" />
        <input type="hidden" id="delete_accion" name="accion" value="delete" />
    </form>
</div>

<div id="add_comision_modal" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-focus-on="input:first">
    <div class="modal-dialog">
        <div class="modal-content">
            <form name="form_add_comision_modal" id="form_add_comision_modal" action="Usuarios/add_comision" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">AÑADIR NUEVA COMISIÓN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-5 border-bottom">
                        <div class="col-12">
                            <label class="form-label">Comisión sobre</label>
                            <select name="item" id="new_item" class="form-control form-control-solid" onchange="loadfamilias(this.value)">
                                <option value=""></option>
                                <option value="producto">Producto</option>
                                <option value="servicio">Servicio</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Familia</label>
                            <select name="id_familia_item" id="new_id_familia_item" class="form-control form-control-solid" onchange="loaditemsfamilia(this.value, '#new_item')" data-control="select2">
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Artículo</label>
                            <select name="id_item" id="new_id_item" class="form-control form-control-solid" data-control="select2">
                                <option value="0">Cualquier</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Tipo de comisión</label>
                            <select name="tipo" id="new_tipo" class="form-control form-control-solid">
                                <option value="fijo">Precio fijo (€)</option>
                                <option value="porcentaje">Porcentaje (%)</option>
                                <option value="tramo">Por tramo</option>
                            </select>
                        </div>

                        <div class="col-12" id="comisionestramo_nuevo" style="display:none">
                            <label class="form-label">Importe desde</label>
                            <input type="number" step=".01" class="form-control form-control-solid" name="importe_desde" id="importe_desde_new" placeholder="Desde (€)" />

                            <label class="form-label">Importe hasta</label>
                            <input type="number" step=".01" class="form-control form-control-solid" name="importe_hasta" id="importe_hasta_new" placeholder="Hasta (€)" />
                        </div>

                        <div class="col-12">
                            <label class="form-label">Valor de comisión</label>
                            <input type="number" step=".5" name="comision" id="new_comision" class="form-control form-control-solid">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="sendform('#form_add_comision_modal')">Añadir Comisión</button>
                </div>
                <input type="hidden" name="id_usuario" value="<?= $usuario[0]['id_usuario'] ?>" />
                <input type="hidden" name="accion" value="add" />
            </form>
        </div>
    </div>
</div>

<div id="edit_comision_modal" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-focus-on="input:first">
    <div class="modal-dialog">
        <div class="modal-content">
            <form name="form_edit_comision_modal" id="form_edit_comision_modal" action="Usuarios/edit_comision" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">EDITAR COMISIÓN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-5 border-bottom">
                        <div class="col-12">
                            <label class="form-label">Comisión sobre</label>
                            <select name="item" id="edit_item" class="form-control form-control-solid" onchange="loadfamilias(this.value)">
                                <option value=""></option>
                                <option value="producto">Producto</option>
                                <option value="servicio">Servicio</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Familia</label>
                            <select name="id_familia_item" id="edit_id_familia_item" class="form-control form-control-solid" onchange="loaditemsfamilia(this.value, '#edit_item')" data-control="select2">
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Artículo</label>
                            <select name="id_item" id="edit_id_item" class="form-control form-control-solid" data-control="select2">
                                <option value="0">Cualquier</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Tipo de comisión</label>
                            <select name="tipo" id="edit_tipo" class="form-control form-control-solid">
                                <option value="fijo">Precio fijo (€)</option>
                                <option value="porcentaje">Porcentaje (%)</option>
                                <option value="tramo">Por tramo</option>
                            </select>
                        </div>

                        <div class="col-12" id="comisionestramo_editar" style="display:none">
                            <label class="form-label">Importe desde</label>
                            <input type="number" step=".01" class="form-control form-control-solid" name="importe_desde" id="edit_importe_desde" placeholder="Desde (€)" />

                            <label class="form-label">Importe hasta</label>
                            <input type="number" step=".01" class="form-control form-control-solid" name="importe_hasta" id="edit_importe_hasta" placeholder="Hasta (€)" />
                        </div>

                        <div class="col-12">
                            <label class="form-label">Valor de comisión</label>
                            <input type="number" step=".5" name="comision" id="edit_comision" class="form-control form-control-solid">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="sendform('#form_edit_comision_modal')">Actualizar Comisión</button>
                </div>
                <input type="hidden" name="id_comision" value="" />
                <input type="hidden" name="id_usuario" value="<?= $usuario[0]['id_usuario'] ?>" />
                <input type="hidden" name="accion" value="edit" />
            </form>
        </div>
    </div>
</div>

<script>
    function loadfamilias(value, selected = '') {
        var parametros = {
            "tipoitem": value,
        };
        $.ajax({
            data: parametros,
            url: '<?php echo base_url(); ?>Usuarios/load_familias',
            type: 'post',
            beforeSend: function() {
                $("[name='id_familia_item']").html('');
                var option = $('<option>').text('Selecciona una opción').val('');
                $('[name="id_familia_item"]').append(option);
            },
            success: function(response) {
                var select = $("[name='id_familia_item']");
                var response = $.parseJSON(response);
                $.each(response, function(index, item) {
                    if (parametros.tipoitem == 'producto') {
                        var option = $('<option>').text(item.nombre_familia).val(item.id_familia_producto);
                        if (selected != '' && selected == item.id_familia_producto) {
                            option.attr('selected', 'selected')
                        }
                    } else {
                        var option = $('<option>').text(item.nombre_familia).val(item.id_familia_servicio);
                        if (selected != '' && selected == item.id_familia_servicio) {
                            option.attr('selected', 'selected')
                        }
                    }


                    $('[name="id_familia_item"]').append(option);
                });
                $("[name='id_familia_item']").select2();
            }
        });
    }

    function loaditemsfamilia(value, item, selected = '') {
        var parametros = {
            "familia": value,
            "tipoitem": $(item).val()
        };
        $.ajax({
            data: parametros,
            url: '<?php echo base_url(); ?>Usuarios/load_items_familias',
            type: 'post',
            beforeSend: function() {
                $("[name='id_item']").html('');
                var option = $('<option>').text('Selecciona una opción').val('');
                $('[name="id_item"]').append(option);
            },
            success: function(response) {
                var select = $("[name='id_item']");
                var response = $.parseJSON(response);
                console.log(selected)
                $.each(response, function(index, item) {
                    if (parametros.tipoitem == 'producto') {
                        var option = $('<option>').text(item.nombre_producto).val(item.id_producto);
                        if (selected != '' && selected == item.id_producto) {
                            option.attr('selected', 'selected')
                        }
                    } else {
                        var option = $('<option>').text(item.nombre_servicio).val(item.id_servicio);
                        if (selected != '' && selected == item.id_servicio) {
                            option.attr('selected', 'selected')
                        }
                    }
                    $('[name="id_item"]').append(option);
                });
                $("[name='id_item']").select2();
            }
        });
    }

    function sendform(form) {
        var dataString = $(form).serialize();
        $.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>Usuarios/manage_comision',
            data: dataString,
            success: function(response) {
                var response = $.parseJSON(response);
                if (response.error > 0) {
                    Swal.fire(response.msn)
                } else {
                    tabla_comisiones.draw();
                    $('.modal').modal('hide');
                    Swal.fire(response.msn)
                }
            }
        });
    }

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

    var tabla_comisiones = $("#tabla_comisiones").DataTable({
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
                name: "id_comision",
                data: "id_comision",
            },{
                //0
                titlee: "",
                name: "item",
                data: "item",
            },
            {
                // 1
                titlee: "",
                name: "nombre_familia",
                data: "nombre_familia",
            },
            {
                //2
                titlee: "",
                name: "nombre_item",
                data: "nombre_item",
            },
            {
                //3
                titlee: "Recordatorio",
                name: "tipo",
                data: "tipo",
            },
            {
                //4
                titlee: "",
                name: "comision",
                data: "comision",
            },
            {
                //5
                titlee: "",
                name: "importe_desde",
                data: "importe_desde",
            },
            {
                //6
                titlee: "",
                name: "importe_hasta",
                data: "importe_hasta",
            },
            {
                //7
                titlee: "",
                name: "",
                data: "",
                render: function(data, type, row) {
                    var html = `<button type="button" class="btn btn-sm btn-icon btn-warning" data-edit ><i class="fa-regular fa-pen-to-square"></i></button>`;
                    return html
                }
            },
            {
                //8
                titlee: "",
                name: "",
                data: "",
                render: function(data, type, row) {
                    var html = `<button type="button" class="btn btn-sm btn-icon btn-danger" data-del><i class="fa-solid fa-trash"></i></button>`;
                    return html
                }
            },
        ],
        columnDefs: [{
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8],
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
        ],
        ajax: {
            url: "<?php echo base_url(); ?>Usuarios/get_comisiones_user",
            type: "GET",
            datatype: "json",
            data: function(data) {
                var tipo = $('[name="tipo_filter"]').val();
                var item = $('[name="item_filter"]').val();
                var id_usuario = $('[name="id_usuario"]').val();
                if (tipo != "") {
                    data.tipo = tipo;
                }
                if (item != "") {
                    data.item = item;
                }
                if (id_usuario != "") {
                    data.id_usuario = id_usuario;
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

    var buttons = new $.fn.dataTable.Buttons(tabla_comisiones, {
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
            }, {
                text: "Exportar CSV",
                extend: "csvHtml5",
                title: 'Comisiones',
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
            }
        ]
    }).container().appendTo($('#buttons'));

    $('[data-table-search]').on('input', function() {
        tabla_comisiones.search($(this).val()).draw();
    })


    $(document).on('click', '[data-edit]', function(event) {
        var button = $(this);
        var data = tabla_comisiones.row(button.parents("tr")).data();
        var modal = $('#edit_comision_modal')
        $('#edit_item').val(data.item);
        loadfamilias(data.item, data.id_familia_item)
        loaditemsfamilia(data.id_familia_item, '#edit_item', data.id_item)
        $('#edit_tipo').val(data.tipo);
        modal.find('[name="id_comision"]').val(data.id_comision);
        $('#edit_comision').val(data.comision);
        $('#edit_importe_desde').val(data.importe_desde);
        $('#edit_importe_hasta').val(data.importe_hasta);
        if (data.tipo == 'tramo') {
            $('#comisionestramo_editar').slideDown('slow');
        } else {
            $('#comisionestramo_editar').slideUp('slow');
        }
        modal.modal('show')
    })

    $(document).on('click', '[data-del]', function() {
        if (confirm("¿DESEA MARCAR COMO BORRADO EL REGISTRO?")) {
            var button = $(this);
            var data = tabla_comisiones.row(button.parents("tr")).data();
            $('#delete_id_comision').val(data.id_comision);
            sendform('#delete_comision_modal');
        }
        return false;
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