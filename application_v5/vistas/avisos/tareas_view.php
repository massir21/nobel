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
                <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="tabla_tareas">
                
            </div>
            <div id="buttons"></div>
        </div>
        <div class="card-title w-100 justify-content-end flex-wrap">

            <div class="m-1">
                <div class="input-group mb-3">
                    <span class="input-group-text border-0">Desde</span>
                    <input type="date" id="filter_fecha_desde" name="filter_fecha_desde" value="<?= (isset($fecha_desde)) ? $fecha_desde : '' ?>" class="form-control form-control-solid w-auto" placeholder="Fecha desde" required />
                </div>
            </div>

            <div class="m-1">
                <div class="input-group mb-3">
                    <span class="input-group-text border-0">Hasta</span>
                    <input type="date" id="filter_fecha_hasta" name="filter_fecha_hasta" value="<?= (isset($fecha_hasta)) ? $fecha_hasta : '' ?>" class="form-control form-control-solid w-auto" placeholder="Fecha hasta" required />
                </div>
            </div>

            <?php if($this->session->userdata('id_perfil') == 0){ ?>
            <div class="m-1">
                <div class="input-group mb-3">
                    <span class="input-group-text border-0">Centro:</span>
                    <select name="filter_id_centro" id="filter_id_centro" class="form-select form-select-solid w-auto" onchange="NuevoDiaFiltroCentro();">
                        <option value="">Todos</option>
                        <option value="1">Central</option>
                        <?php if (isset($centros_todos)) {
                            if ($centros_todos != 0) {
                                foreach ($centros_todos as $key => $row) {
                                    if ($row['id_centro'] > 1 ) { ?>
                                        <option value='<?php echo $row['id_centro']; ?>' <?=(isset($id_centro) &&($row['id_centro'] == $id_centro)) ?"selected":'';?>>
                                                <?php echo $row['nombre_centro']; ?>
                                        </option>
                                    <?php }
                                }
                            }
                        } ?>
                    </select>
                </div>
            </div>
            <?php } else{ echo form_hidden('filter_id_centro');} ?>
            

            <div class="m-1">
                <div class="input-group mb-3">
                    <span class="input-group-text border-0">Estado</span>
                    <select name="filter_estado" id="filter_estado" class="form-select form-select-solid w-auto">
                        <option value="">Cualquier estado</option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="Finalizado">Finalizado</option>
                    </select>
                </div>
            </div>
        </div>
    </div>


    <div class="card-body pt-6" id="content">
        <div class="table-responsive">
            <div class="table-responsive">
                <table id="tabla_tareas" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                    <thead class="">
                        <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                            <th>Fecha</th>
                            <th>Creador</th>
                            <th>Para</th>
                            <th>Titulo</th>
                            <th>Ejecución</th>
                            <th>Modificación</th>
                            <th>Estado</th>
                            <th>Centro</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php /*if (isset($registros)) {
                            if ($registros != 0) {
                                $i = 0;
                                foreach ($registros as $key => $row) {
                                    if ($row['estado'] == "Pendiente") {
                                        $color = "background: #ffffff; font-weight: bold;";
                                        $estado = "Pendiente";
                                    } else {
                                        $color = "background: #f2f2f2; text-decoration:line-through;";
                                        $estado = "Finalizado";
                                    } ?>
                                    <tr id="fila<?php echo $i; ?>" style=" <?php echo $color; ?>">
                                        <td style="display: none;"><?php echo $row['fecha_creacion']; ?></td>
                                        <td><?php echo $row['fecha_creacion']; ?></td>
                                        <td><?php echo $row['usuario_creador']; ?></td>
                                        <td><?php echo $row['quienes']; ?></td>
                                        <td><?php echo $row['titulo']; ?></td>
                                        <td><?php echo $row['fecha_ejecucion']; ?></td>
                                        <td><?php echo $row['fecha_modificacion']; ?></td>
                                        <td><?php echo $row["estado"]; ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-icon btn-info" data-ver data-bs-toggle="tooltip" title="Ver registro de actividad" onclick="ver_registro('<?php echo $row['id']; ?>')"><i class="fa fa-eye"></i></button>
                                                <button type=button" class="btn btn-sm btn-icon btn-warning" title="Editar tarea" onclick="editar('<?php echo $row['id']; ?>')"><i class="fa-regular fa-pen-to-square"></i></button>
                                            </div>
                                        </td>

                                    </tr>
                        <?php $i++;
                                }
                            }
                        } */?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    var tabla_tareas = $("#tabla_tareas").DataTable({
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
                titlee: "Fecha / Hora",
                name: "fecha_creacion",
                data: "fecha_creacion",
                render: function(date, type, row) {
                    return moment(new Date(row.fecha_creacion)).format('DD-MM-YYYY HH:mm:ss');
                }
            },
            {
                // 1
                titlee: "Creador",
                name: "usuario_creador",
                data: "usuario_creador",
            },
            {
                //2
                titlee: "Asignado a",
                name: "quienes",
                data: "quienes",
            },
            {
                //3
                titlee: "Título",
                name: "titulo",
                data: "titulo",
            },
            {
                //4
                titlee: "Fecha / Hora",
                name: "fecha_ejecucion",
                data: "fecha_ejecucion",
                render: function(date, type, row) {
                    return moment(new Date(row.fecha_ejecucion)).format('DD-MM-YYYY');
                }
            },
            {
                //5
                titlee: "Fecha / Hora",
                name: "fecha_modificacion",
                data: "fecha_modificacion",
                render: function(date, type, row) {
                    return moment(new Date(row.fecha_modificacion)).format('DD-MM-YYYY HH:mm:ss');
                }
            },
            {
                //6
                titlee: "Estado",
                name: "estado",
                data: "estado",
            },
            {
                //7
                titlee: "Centro",
                name: "nombre_centro",
                data: "nombre_centro",
            },
            {
                //8
                titlee: "",
                name: "",
                data: "",
                render: function(data, type, row) {
                    var html = '<div class="btn-group">';
                    html += `<button type="button" class="btn btn-sm btn-icon btn-info" data-ver data-bs-toggle="tooltip" title="Ver registro de actividad" onclick="ver_registro(${row.id})"><i class="fa fa-eye"></i></button>`;
                    html += `<button type=button" class="btn btn-sm btn-icon btn-warning" title="Editar tarea"  onclick="editar(${row.id})"><i class="fa-regular fa-pen-to-square"></i></button>`;
                    html += `</div>`;
                    return html
                }
            },
            {
                //9
                titlee: "",
                name: "",
                data: "",
                render: function(data, type, row) {
                    var html = '';
                    if(row.id_creador == <?=$this->session->userdata('id_usuario')?>){
                        html = `<a class="btn btn-sm btn-icon btn-danger" href="#" onclick="Borrar(${row.id});"><i class="fa-solid fa-trash"></i></a>`;
                    }
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
            {
                targets: [-2],
                orderable: false,
            },
        ],
        ajax: {
            url: "<?php echo base_url(); ?>Avisos/get_tareas",
            type: "GET",
            datatype: "json",
            data: function(data) {
                var fecha_desde = $('[name="filter_fecha_desde"]').val();
                var fecha_hasta = $('[name="filter_fecha_hasta"]').val();
                var id_centro = $('[name="filter_id_centro"]').val();
                var estado = $('[name="filter_estado"]').val();
                if (fecha_desde != "") {
                    data.fecha_desde = fecha_desde;
                }
                if (fecha_hasta != "") {
                    data.fecha_hasta = fecha_hasta;
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
        initComplete: function() {},
    });

    var buttons = new $.fn.dataTable.Buttons(tabla_tareas, {
        buttons: [{
            text: "Excel",
            extend: "excelHtml5",
            title: 'Presupuestos',
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
            title: 'Presupuestos',
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

    $(document).on("change", "#filter_fecha_desde, #filter_fecha_hasta, #filter_id_centro, #filter_estado", function() {
        tabla_tareas.draw();
    });

    function NuevaTarea() {
        var posicion_x;
        var posicion_y;
        var ancho = 565;
        var alto = 700;
        posicion_x = (screen.width / 2) - (ancho / 2);
        posicion_y = (screen.height / 2) - (alto / 2);
        window.open("<?php echo base_url(); ?>avisos/tareas_nueva/nueva", "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
    }

    function ver_registro(id_tarea) {
        var posicion_x;
        var posicion_y;
        var ancho = 650;
        var alto = 700;
        posicion_x = (screen.width / 2) - (ancho / 2);
        posicion_y = (screen.height / 2) - (alto / 2);
        window.open("<?php echo base_url(); ?>avisos/iteraciones_tareas/editar/" + id_tarea, "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
    }

    function editar(id_tarea) {
        var posicion_x;
        var posicion_y;
        var ancho = 565;
        var alto = 700;
        posicion_x = (screen.width / 2) - (ancho / 2);
        posicion_y = (screen.height / 2) - (alto / 2);
        window.open("<?php echo base_url(); ?>avisos/editar_tarea/editar/" + id_tarea, "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
    }

    function Borrar(id_tarea) {
        Swal.fire({
            html: '¿DESEA MARCAR LA TAREA COMO BORRADA?',
            showCancelButton: true,
            confirmButtonText: 'Si, borrar',
            showLoaderOnConfirm: true,
            onBeforeOpen: () => {},

        }).then((result) => {
            if (result.value) {
                var url = '<?= base_url() ?>avisos/borrar_tarea/' + id_tarea;
                window.location.href = url;
            }
        })
    }

    /*function copiarAlPortapapeles(id_elemento) {
        id = "id" + id_elemento;
        var aux = document.createElement("input");
        aux.setAttribute("value", document.getElementById(id).innerHTML);
        document.body.appendChild(aux);
        aux.select();
        document.execCommand("copy");
        document.body.removeChild(aux);
    }

    function cambiar(i, id_aviso) {
        idfila = document.getElementById('fila' + i);
        idestado = document.getElementById('estado' + i);
        idvalorestado = document.getElementById('valorestado' + i);
        estado = idvalorestado.value;
        $.get('<?php echo base_url(); ?>agenda/cambiar_aviso/' + id_aviso + "/" + estado, function(data, status) {
            console.log('Cambio estado');
        });
        if (estado == 0) {
            color = "background: #f2f2f2; text-decoration:line-through;";
            idfila.style = color;
            idestado.innerHTML = "Enviado";
            idvalorestado.value = "1"
        } else {
            color = "background: #ffffff; font-weight: bold;";
            idfila.style = color;
            idestado.innerHTML = "Pendiente";
            idvalorestado.value = "0"
        }
    }

    function laapi(i) {
        telefono = '34' + document.getElementById('idtelefono' + i).value;
        mensaje = document.getElementById('idmensaje' + i).value;
        document.location.href = "https://api.whatsapp.com/send?phone=" + telefono + "&text=" + mensaje, "_blank";
    }

    function NuevoDiaFiltroCentro() {
        valor = document.getElementById('idotroestado').value;
        document.location.href = "<?php echo base_url(); ?>agenda/leer_avisos_citas/" + document.getElementById("id_centro").value + "/" + valor;
    }

    function otroestado() {
        valor = document.getElementById('idotroestado').value;
        document.location.href = "<?php echo base_url(); ?>avisos/leer_tareas/" + valor;
    }*/
</script>