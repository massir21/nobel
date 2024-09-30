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
                <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="myTable1">
            </div>

        </div>
        <div id="buttons"></div>
        <?php /*
        <form id="form_buscar" action="<?php echo base_url(); ?>clientes" role="form" method="post" name="form_buscar">
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
                <div class="w-auto">
                    <label for="" class="form-label">Buscar paciente</label>
                    <input type="text" name="buscar" value="<?php if (isset($buscar)) {echo $buscar;} ?>" class="form-control form-control-solid w-auto" />
                </div>
                <div class="w-auto  ms-3">
                    <button type="submit" class="btn btn-info btn-icon text-inverse-info"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form> */ ?>
    </div>
    <div class="card-body pt-6">
        <form id="form_marcar" action="<?php echo base_url(); ?>clientes/fusionar" method="post" name="form_marcar">
            <div class="table-responsive">
                <table id="tabla_clientes" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                    <?php /*<thead class="">
                        <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                            <th style="display: none;">ID</th>
                            <?php if ($this->session->userdata('id_perfil') == 3 || $this->session->userdata('id_perfil') == 0) { ?>
                                <th>Fusionar</th>
                            <?php } ?>
                            <th>Nombre y apellidos</th>
                            <th style="width: 130px;">Alta</th>
                            <th>Último Centro</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Cod Postal</th>
                            <th class="sorting_disabled" style="width: 5%">Editar</th>
                            <?php if ($this->session->userdata('id_perfil') != 1 && $this->session->userdata('id_perfil') != 2) { ?>
                                <th class="sorting_disabled" style="width: 5%">Borrar</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($registros)) {
                            if ($registros != 0) {
                                foreach ($registros as $key => $row) { ?>
                                    <tr id="fila<?php echo $row['id_cliente'] ?>">
                                        <td style="display: none;">
                                            <?php echo $row['id_cliente'] ?>
                                        </td>
                                        <?php if ($this->session->userdata('id_perfil') == 3 || $this->session->userdata('id_perfil') == 0) { 
                                            $fila = "fila" . $row['id_cliente'];?>
                                            <td>
                                                <label class="form-check form-check-custom form-check-inline form-check-solid">
                                                    <input class="form-check-input" name="marcados[]" type="checkbox" value="<?php echo $row['id_cliente'] ?>" onclick="Marcar(this,'<?=$fila?>');">
                                                </label>
                                            </td>
                                        <?php } ?>
                                        <td>
                                            <a class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6" href="<?php echo base_url(); ?>clientes/historial/ver/<?php echo $row['id_cliente']; ?>"><?php echo $row['nombre'] . " " . $row['apellidos'] ?></a>
                                        </td>
                                        <td>
                                            <?php echo $row['fecha_creacion_ddmmaaaa'] ?>
                                        </td>
                                        <td>
                                            <?php echo $row['ultimo_centro'] ?>
                                        </td>
                                        <td>
                                            <?php echo $row['email'] ?>
                                        </td>
                                        <td>
                                            <?php echo $row['telefono'] ?>
                                        </td>
                                        <td>
                                            <?php echo $row['codigo_postal'] ?>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-icon btn-info" href="<?php echo base_url(); ?>clientes/historial/ver/<?php echo $row['id_cliente']; ?>" data-bs-toggle="tooltip" title="Ficha del cliente"><i class="fas fa-clipboard-user"></i></a>
                                            <a class="btn btn-sm btn-icon btn-warning" href="<?php echo base_url(); ?>clientes/gestion/editar/<?php echo $row['id_cliente'] ?>" data-bs-toggle="tooltip" title="Editar cliente"><i class="fa-regular fa-pen-to-square"></i></a>
                                        </td>
                                        <?php if ($this->session->userdata('id_perfil') != 1 && $this->session->userdata('id_perfil') != 2) { ?>
                                            <td>
                                            <button class="btn btn-sm btn-icon btn-danger" onclick="Borrar(<?php echo $row['id_cliente'] ?>);" data-bs-toggle="tooltip" title="Borrar cliente"><i class="fa-solid fa-trash"></i></button>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php }
                            }
                        } ?>
                    </tbody> */ ?>
                </table>
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
                    <?php if ($this->session->userdata('id_perfil') == 3 || $this->session->userdata('id_perfil') == 2 || $this->session->userdata('id_perfil') == 0) { ?>
                        var html = `<div class="form-check form-check-solid form-switch form-check-custom fv-row mb-2 p-2 ">
							<input class="form-check-input w-35px h-20px" type="checkbox"  name="marcados[]"value="${row.id_cliente}" onclick="Marcar(this,'fila${row.id_cliente}');"><label class="form-label ms-5"></label>
						</div>`;
                        return html;
                    <?php } else { ?>
                        return row.id_cliente;
                    <?php } ?>
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
                title: "Alta",
                name: "fecha_creacion",
                data: "fecha_creacion",
                render: function(data, type, row) {
                    var html = row.fecha_creacion;
                    return html
                }
            },
            {
                // 3
                title: "Ultimo centro",
                name: "nombre_centro",
                data: "nombre_centro",
                render: function(data, type, row) {
                    return row.nombre_centro
                }
            },
            {
                // 4
                titlee: "",
                name: "email",
                data: "email",
                render: function(data, type, row) {
                    var html = row.email;
                    return html
                }
            },
            {
                // 5
                title: "Teléfono",
                name: "telefono",
                data: "telefono",
                render: function(data, type, row) {
                    var html = row.telefono;
                    return html
                }
            },

            {
                // 6
                title: "codigo_postal",
                name: "codigo_postal",
                data: "codigo_postal",
                render: function(data, type, row) {
                    var html = row.codigo_postal;
                    return html
                }
            },
            {
                // 7
                title: "",
                name: "",
                data: "",
                render: function(data, type, row) {
                    var html = '';
                    html += `<a class="btn btn-sm btn-icon btn-info" href="<?php echo base_url(); ?>clientes/historial/ver/${row.id_cliente}" data-bs-toggle="tooltip" title="Ficha del cliente"><i class="fas fa-clipboard-user"></i></a>`;
                    <?php
                    // CHAINS 20240219 - El doctor no puede editar perfil
                    if ($this->session->userdata('id_perfil') != 1 && $this->session->userdata('id_perfil') != 2
                        && $this->session->userdata('id_perfil') != 6) {
                        ?>
                        html += `<a class="btn btn-sm btn-icon btn-warning" href="<?php echo base_url(); ?>clientes/gestion/editar/${row.id_cliente}" data-bs-toggle="tooltip" title="Editar cliente"><i class="fa-regular fa-pen-to-square"></i></a>`;
                    <?php } ?>
                    return html;
                }
            },
            { // 8
                title: "",
                name: "",
                data: "",
                render: function(data, type, row) {
                    <?php
                    if( $this->session->userdata('id_perfil') == 6) {
                        // CHAINS 20240219 - El doctor no puede borrar perfil
                    ?>
                        return '';
                        <?php
                    }
                    else
                    if ($this->session->userdata('id_perfil') != 1 && $this->session->userdata('id_perfil') != 2) { ?>
                        var html = `<button class="btn btn-sm btn-icon btn-danger" onclick="Borrar(${row.id_cliente});" data-bs-toggle="tooltip" title="Borrar cliente"><i class="fa-solid fa-trash"></i></button>`;
                        return html
                    <?php } elseif($this->session->userdata('id_perfil') != 1){ ?>
                        var html = `<a class="btn btn-sm btn-icon btn-warning" href="<?php echo base_url(); ?>clientes/gestion/editar/${row.id_cliente}" data-bs-toggle="tooltip" title="Editar cliente"><i class="fa-regular fa-pen-to-square"></i></a>`;
                        return html;
                    <?php }else{?>
                        return '';
                    
                    <?php } ?>
                }
            }
        ],

        columnDefs: [{
                targets: [0, 1, 2, 3, <?php if($this->session->userdata('id_perfil')!=6){
                        // CHAINS 20240219 - El doctor no puede ver email ni telefono
                        ?> 4, 5, <?php
                } ?> 6, 7, 8],
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
            url: "<?php echo base_url(); ?>Clientes/get_clientes",
            type: "GET",
            datatype: "json",
            data: function(data) {
                var id_cliente = $('[name="id_cliente"]').val();
                var fecha_desde = $('#fecha_desde_presupuestos').val();
                var fecha_hasta = $('#fecha_hasta_presupuestos').val();
                var fecha_validez = $('[name="fecha_validez"]').val();
                var id_usuario = $('[name="id_usuario"]').val();
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
                if (fecha_validez != "") {
                    data.fecha_validez = fecha_validez;
                }
                if (id_usuario != "") {
                    data.id_usuario = id_usuario;
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
            buttons: [
                <?php
                if($this->session->userdata('id_perfil')==6){
                // CHAINS 20240219 - El doctor no puede exportar
                }
                else{
                ?>
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
            }, <?php
                }
                ?>
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
            <?php
            if($this->session->userdata('id_perfil')==6){
                 // CHAINS 20240219 - El doctor no puede exportar
            }
            else{
                ?>
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
            <?php
            }
            ?>

        ]
    }).container().appendTo($('#buttons'));

    $('[data-table-search]').on('input', function() {
        tabla_clientes.search($(this).val()).draw();
    });

    $('#filter_estado').on('change', function() {
        tabla_clientes.search($(this).val()).draw();
    });


    function Borrar(id_cliente) {
        if (confirm("¿Desea borrar el cliente?")) {
            document.location.href = "<?php echo base_url(); ?>clientes/gestion/borrar/" + id_cliente;
        }
        return false;
    }

    function Marcar(elemento, fila) {
        if (elemento.checked) {
            document.getElementById(fila).style.background = "yellow";
        } else {
            document.getElementById(fila).style.background = 'transparent';
        }
    }

    function Fusionar() {
        var inputElems = document.getElementsByTagName("input"),
            count = 0;
        for (var i = 0; i < inputElems.length; i++) {
            if (inputElems[i].type === "checkbox" && inputElems[i].checked === true) {
                count++;
            }
        }
        // .. Si se marca más de un check
        if (count > 1) {
            Swal.fire({
                text: "¿DESEAS FUSIONAR LOS CLIENTES INDICADOS?\n\nSE RECOMIENDA REALIZAR UN BACKUP DE LA BASE DE DATOS ANTES.",
                showCancelButton: true,
                confirmButtonText: 'Si, fusionar',
                cancelButtonText: 'Cancelar',
                showLoaderOnConfirm: true,

            }).then((result) => {
                if (result.value) {
                    document.form_marcar.submit();
                    return true;
                } else {
                    return false;
                }
            });


        } else {
            Swal.fire("DEBES DE MARCAR AL MENOS DOS CLIENTES PARA FUSIONAR");
            return false;
        }
    }
</script>