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
        <div class="card-title w-100 justify-content-end flex-wrap">

            <div class="m-1">
            <label for="" class="form-label">Desde</label>
                <input type="date" id="fecha_desde" name="fecha_desde" class="form-control form-control-solid w-auto" placeholder="Fecha desde" />
            </div>

            <div class="m-1">
                <label for="" class="form-label">Hasta</label>
                <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?=date('Y-m-d')?>" class="form-control form-control-solid w-auto" placeholder="Fecha hasta" />

            </div>

            <div class="m-1">
                <label for="" class="form-label">Fecha</label>
                <input type="date" id="fecha_rellamada" name="fecha_rellamada" class="form-control form-control-solid w-auto" placeholder="Fecha rellamada" />
            </div>
            <div class="m-1">
                <label for="" class="form-label">Estado</label>
                <select name="filter_estado" id="filter_estado" data-control="select2"  data-placeholder="Estado..." class="form-select form-select-solid w-auto">
                    <option value="">Cualquier estado</option>
                    <option value="pendiente" selected >Pendiente</option>
                    <option value="realizada">Realizada</option>
                    <option value="anulada">Anulada</option>
                </select>
            </div>
            <div class="m-1 w-250px">
                <label for="" class="form-label">Cliente</label>
                <select name="id_cliente" id="id_cliente" class="form-select form-select-solid" data-placeholder="Cliente...">
                    <option value="">Cualquier cliente</option>
                </select>
                <script type="text/javascript">
                    $("#id_cliente").select2({
                        language: "es",
                        minimumInputLength:3,
                        allowClear: true,
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
            <?php /*
            <div class="m-1 w-250px">
                <label for="" class="form-label">Empleado</label>
                <select name="id_usuario_empleado" id="id_usuario_empleado" class="form-select form-select-solid" data-control="select2"  data-placeholder="Empleado...">
                    <option value="">Cualquier empelado</option>
                    <?php foreach ($empleados as $rs) { ?>
                        <option value="<?php echo $rs['id_usuario']; ?>"><?php echo $rs['nombre'] . ' ' . $rs['apellidos']; ?></option>
                    <?php } ?>
                </select>
            </div>
            */ ?>

            <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                <div class="m-1">
                    <label for="" class="form-label">Centro</label>
                    <select name="id_centro" id="id_centro" data-control="select2"  data-placeholder="Centros..." class="form-select form-select-solid w-auto">
                        <option value="">Todos</option>
                        <option value="1">Central</option>
                        <?php if (isset($centros_todos)) {
                            if ($centros_todos != 0) {
                                foreach ($centros_todos as $key => $row) {
                                    if ($row['id_centro'] > 1 ) { ?>
                                        <option value='<?php echo $row['id_centro']; ?>' >
                                                <?php echo $row['nombre_centro']; ?>
                                        </option>
                                    <?php }
                                }
                            }
                        } ?>
                    </select>
                </div>
            <?php } else{ ?>
                <input type="hidden" name="id_centro" id="id_centro" value="<?=$this->session->userdata('id_centro_usuario')?>">
            <?php } ?>
            <div class="m-1">
                <button type="button" class="btn btn-icon btn-warning mt-9" id="filtersearch" data-bs-toggle="tooltip" title="Aplicar filtros"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </div>

    <div class="card-body pt-6">
        <div class="table-responsive">
            <table id="tabla_rellamadas" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th>Fecha <i class="fas fa-phone"></i></th>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Fecha Cita</th>
                        <th>Servicio</th>
                        <th>Familia</th>
                        <th>Empleado</th>
                        <th>Centro</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold"></tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-rellamada" aria-labelledby="modal-rellamadaLabel" data-bs-focus="false" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center text-uppercase" id="exampleModalLabel">Editar Rellamada</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="">Comentarios:</label>
                    <textarea name="comentarios" id="comentarios_modal" class="form-control" rows="5"></textarea>
                </div>
                <div class="mb-3">
                    <label for="">Estado:</label>
                    <select name="estado" id="estado_modal" class="form-select">
                        <option value="pendiente">Pendiente</option>
                        <option value="realizada">Realizada</option>
                        <option value="anulada">Anulada</option>
                    </select>
                </div>
            </div>
            <input type="hidden" id="id_rellamada_modal" value="">
            <div class="modal-footer p-2 justify-content-center">
                <button type="button" class="btn btn-sm btn-secondary text-inverse-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-sm btn-primary text-inverse-primary" id="actualizar_rellamada">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-rellamada-copiar" aria-labelledby="modal-rellamada-copiarLabel" data-bs-focus="false" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center text-uppercase" id="exampleModalLabel">Agendar Rellamada vinculada</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="">Comentarios para la nueva rellamada:</label>
                    <textarea name="copiar_comentarios" id="copiar_comentarios_modal" class="form-control" rows="5"></textarea>
                </div>
                <div class="mb-3">
                    <label for="">Nueva fecha:</label>
                    <input type="date" name="copiar_fecha_rellamada" id="copiar_fecha_rellamada" class="form-control">
                </div>
            </div>
            <input type="hidden" id="id_copiar_rellamada_modal" value="">
            <div class="modal-footer p-2 justify-content-center">
                <button type="button" class="btn btn-sm btn-secondary text-inverse-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-sm btn-primary text-inverse-primary" id="btn_copiar_rellamada">Crear</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-rellamada-nueva" aria-labelledby="modal-rellamada-nuevaLabel" data-bs-focus="false" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center text-uppercase" id="exampleModalLabel">Nueva Rellamada</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="" class="form-label">Cliente</label>
                    <select name="nueva_id_cliente" id="nueva_id_cliente" class="form-select form-select-solid" data-placeholder="Cliente...">
                    </select>
                    <script type="text/javascript">
                        $("#nueva_id_cliente").select2({
                            language: "es",
                            minimumInputLength:3,
                            allowClear: true,
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

                <div class="mb-3">
                    <label for="">Servicio:</label>
                    <select name="nueva_id_servicio" id="nueva_id_servicio" class="form-select form-select-solid" data-control="select2" data-placeholder="Servicio...">
                        <option value="">Vacío si no se especifica</option>
                        <?php foreach ($servicios as $key => $value) { ?>
                            <option value="<?=$value['id_servicio']?>"><?=$value['nombre_servicio']?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="">Comentarios:</label>
                    <textarea name="nueva_comentarios" id="nueva_comentarios_modal" class="form-control" rows="5"></textarea>
                </div>
                <div class="mb-3">
                    <label for="">Fecha:</label>
                    <input type="date" name="nueva_fecha_rellamada" id="nueva_fecha_rellamada" class="form-control">
                </div>
            </div>
            <div class="modal-footer p-2 justify-content-center">
                <button type="button" class="btn btn-sm btn-secondary text-inverse-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-sm btn-primary text-inverse-primary" id="btn_nueva_rellamada">Crear</button>
            </div>
        </div>
    </div>
</div>

<script>
   
    var tabla_rellamadas = $("#tabla_rellamadas").DataTable({
        info: true,
        paging: true,
        ordering: true,
        searching: true,
        stateSave: false,
        processing: true,
        serverSide: true,
        scrollX: true,
        autoWidth: false,
        order: [
            [0, "desc"],
            [1, "desc"]
        ],
        pageLength: 50,
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Todos"],
        ],
        columns: [{
                // 0
                name: "fecha_rellamada",
                data: "fecha_rellamada",
                render: function(data, type, row) {
                    var html = row.fecha_rellamada;
                    return html
                }
            },
            {
                //1
                titlee: "",
                name: "cliente",
                data: "cliente",
                render: function(data, type, row) {
                    var html = `<a class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6" href="<?php echo base_url(); ?>clientes/historial/ver/${row.id_cliente}">${row.cliente}</a>`;
                    return html
                }
            },
            {
                //2
                titlee: "",
                name: "telefono",
                data: "telefono",
                render: function(data, type, row, meta) {
                    var html = `<span class="text-muted fw-semibold fs-6" id="id${meta.row}" onclick="copiarAlPortapapeles(${meta.row})" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Copiar teléfono">${row.telefono}</span>`;
                    return html;
                }
            },
            {
                //3
                titlee: "",
                name: "fecha_cita",
                data: "fecha_cita",
                className: "text-nowrap",
                render: function(data, type, row) {
                    return row.fecha_cita;
                }
            },
            {
                // 4
                titlee: "",
                name: "nombre_servicio",
                data: "nombre_servicio",
                className: "text-nowrap",
                render: function(data, type, row) {
                    var html = row.nombre_servicio
                    return html;
                }
            },
            {
                // 5
                titlee: "",
                name: "nombre_familia",
                data: "nombre_familia",
                className: "text-nowrap",
                render: function(data, type, row) {
                    var html = row.nombre_familia
                    return html;
                }
            },
            {
                //6
                titlee: "",
                name: "empleado",
                data: "empleado",
                render: function(data, type, row) {
                    var html = row.empleado;
                    return html
                }
            },
            {
                //7
                titlee: "",
                name: "nombre_centro",
                data: "nombre_centro",
                render: function(data, type, row) {
                    var html = row.nombre_centro;
                    return html
                }
            },
            {
                //8
                titlee: "estado",
                name: "estado",
                data: "estado",
                render: function(date, type, row, meta) {
                    var html = row.estado;
                    if(row.comentarios != ''){
                        html += `<i class="fa-comment fa-comments fa-solid ms-3 rounded-circle text-info" data-bs-toggle="tooltip" title="${row.comentarios}"></i>`;
                    }
                    return html
                }
            }
            ,{
                // 8
                titlee: "",
                name: "",
                data: "",
                render: function(data, type, row) {
                    var html = '<div class="btn-group">';
                    html += `<button type="button" class="btn btn-sm btn-icon btn-warning" data-edit data-bs-toggle="tooltip" title="Editar Rellamada"><i class="fa-regular fa-pen-to-square"></i></button>`;
                    html += `<button type="button" class="btn btn-sm btn-icon btn-info" data-copiar data-bs-toggle="tooltip" title="Agendar rellamada vinculada"><i class="fa-regular fa-calendar"></i></button>`;
                    html += `</div>`;
                    return html
                }
            },
        ],
        columnDefs: [{
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                visible: true,
            },
            {
                targets: ["_all"],
                visible: false,
            },
            /*{
                targets: [-2],
                orderable: false,
            },
            {
                targets: ['col_id', 'col_validez'],
                className: 'text-center'
            },*/
            {
                targets: ['col_aceptado', 'col_desc', 'col_presu_sin_desc', 'col_presu'],
                className: 'text-end'
            }
        ],
        ajax: {
            url: "<?php echo base_url(); ?>Rellamadas/get_rellamadas",
            type: "GET",
            datatype: "json",
            data: function(data) {
                var id_cliente = $('[name="id_cliente"]').val();
                var fecha_desde = $('[name="fecha_desde"]').val();
                var fecha_hasta = $('[name="fecha_hasta"]').val();
                var fecha_rellamada = $('[name="fecha_rellamada"]').val();
                var id_usuario_empleado = $('[name="id_usuario_empleado"]').val();
                var id_centro = $('[name="id_centro"]').val();
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
                if (fecha_rellamada != "") {
                    data.fecha_rellamada = fecha_rellamada;
                }
                if (id_usuario_empleado != "") {
                    data.id_usuario_empleado = id_usuario_empleado;
                }
                if (id_centro != "") {
                    data.id_centro = id_centro;
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
                title: 'Rellamadas',
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

    var buttons = new $.fn.dataTable.Buttons(tabla_rellamadas, {
        buttons: [{
            text: "Excel",
            extend: "excelHtml5",
            title: 'Rellamadas',
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
            title: 'Rellamadas',
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
        tabla_rellamadas.search($(this).val()).draw();
    });
    $('#filtersearch').on('click', function() {   
        tabla_rellamadas.draw();
    });;
    
    function copiarAlPortapapeles(id_elemento) {
        id = "id" + id_elemento;
        var aux = document.createElement("input");
        aux.setAttribute("value", document.getElementById(id).innerHTML);
        document.body.appendChild(aux);
        aux.select();
        document.execCommand("copy");
        document.body.removeChild(aux);

        var copyButton = document.getElementById(id);
            var tooltip = bootstrap.Tooltip.getInstance(copyButton);
            tooltip.setContent({ '.tooltip-inner': '¡Copiado!' });
            tooltip.show();

            // Restaurar tooltip a "Copiar" después de 2 segundos
            setTimeout(function() {
                tooltip.setContent({ '.tooltip-inner': 'Copiar teléfono' });
            }, 2000);

    }

    $(document).on('click', '[data-edit]', function(event) {
        var button = $(this);
        var data = tabla_rellamadas.row(button.parents("tr")).data();
        $('#comentarios_modal').val(data.comentarios)
        $('#estado_modal').val(data.estado)
        $('#id_rellamada_modal').val(data.id_rellamada)
        $('#modal-rellamada').modal('show');
    });

    $(document).on('click', '#actualizar_rellamada', function(event) {
        var comentarios = $('#comentarios_modal').val()
        var id_rellamada = $('#id_rellamada_modal').val()
		var estado = $('#estado_modal').val()
        var formData = new FormData();
		formData.append("id_rellamada", id_rellamada);
		formData.append("estado", estado);
        formData.append("comentarios", comentarios);
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			method: 'post',
			url: '<?php echo base_url() ?>Rellamadas/actualizarRellamada',
			data: formData,
			processData: false,
			contentType: false,
			success: function(resp) {
				if (resp == false) {
					Swal.fire({
						title: 'Error',
						willClose: function() {},
					});
				} else {
					Swal.fire({
						title: 'Actualizado',
						willClose: function() {
							tabla_rellamadas.draw();
                            $('#modal-rellamada').modal('hide');
						},
					});
				}
			},
			error: function() {
				Swal.fire({
					type: 'error',
					title: 'Oops...',
					text: 'Ha ocurrido un error'
				})
			}
		})
    })

    $(document).on('click', '[data-copiar]', function(event) {
        var button = $(this);
        var data = tabla_rellamadas.row(button.parents("tr")).data();
        $('#copiar_comentarios_modal').val('')
        $('#copiar_fecha_rellamada').val(data.fecha_rellamada)
        $('#id_copiar_rellamada_modal').val(data.id_rellamada)
        $('#modal-rellamada-copiar').modal('show');
    });

    $(document).on('click', '#btn_copiar_rellamada', function(event) {
        var comentarios = $('#copiar_comentarios_modal').val()
        var id_rellamada = $('#id_copiar_rellamada_modal').val()
		var fecha_rellamada = $('#copiar_fecha_rellamada').val()
        var formData = new FormData();
		formData.append("parent", id_rellamada);
		formData.append("fecha_rellamada", fecha_rellamada);
        formData.append("comentarios", comentarios);
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			method: 'post',
			url: '<?php echo base_url() ?>Rellamadas/crearRellamadaVinculada',
			data: formData,
			processData: false,
			contentType: false,
			success: function(resp) {
				if (resp == false) {
					Swal.fire({
						title: 'Error',
						willClose: function() {},
					});
				} else {
					Swal.fire({
						title: 'Rellamada agendada',
						willClose: function() {
							tabla_rellamadas.draw();
                            $('#modal-rellamada-copiar').modal('hide');
						},
					});
				}
			},
			error: function() {
				Swal.fire({
					type: 'error',
					title: 'Oops...',
					text: 'Ha ocurrido un error'
				})
			}
		})
    })

    $(document).on('click', '#nueva_rellamada', function(event) {
        var button = $(this);
        $('#nueva_comentarios_modal').val('')
        $('#nueva_fecha_rellamada').val('')
        $('#nueva_id_cliente').val('')
        $('#modal-rellamada-nueva').modal('show');
    });

    $(document).on('click', '#btn_nueva_rellamada', function(event) {
        var comentarios = $('#nueva_comentarios_modal').val()
        var id_cliente = $('#nueva_id_cliente').val()
        var id_servicio = $('#nueva_id_servicio').val()
		var fecha_rellamada = $('#nueva_fecha_rellamada').val()
        if(id_cliente == '' || fecha_rellamada == ''){
            Swal.fire({
			title: 'Indica un cliente y una fecha',
			    willClose: function() {
						},
			});
            return;
        }
        var formData = new FormData();
		formData.append("id_cliente", id_cliente);
		formData.append("id_servicio", id_servicio);
        formData.append("comentarios", comentarios);
        formData.append("fecha_rellamada", fecha_rellamada);
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			method: 'post',
			url: '<?php echo base_url() ?>Rellamadas/crearRellamada',
			data: formData,
			processData: false,
			contentType: false,
			success: function(resp) {
				if (resp == false) {
					Swal.fire({
						title: 'Error',
						willClose: function() {},
					});
				} else {
					Swal.fire({
						title: 'Rellamada agendada',
						willClose: function() {
							tabla_rellamadas.draw();
                            $('#modal-rellamada-nueva').modal('hide');
						},
					});
				}
			},
			error: function() {
				Swal.fire({
					type: 'error',
					title: 'Oops...',
					text: 'Ha ocurrido un error'
				})
			}
		})
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