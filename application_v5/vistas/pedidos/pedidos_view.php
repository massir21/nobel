<style>
    .dataTables_filter {
        text-align: right;
    }
</style>
<?php if (isset($estado)) {
    if ($estado == 1) { ?>
        <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
            <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL PEDIDO FUÉ ENVIADO CORRECTAMENTE</div>
            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="fa-times fas fs-3 text-primary"></i>
            </button>
        </div>
    <?php }
    if ($estado == 2) { ?>
        <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
            <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL PEDIDO FUÉ ACTUALIZADO CORRECTAMENTE</div>
            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="fa-times fas fs-3 text-primary"></i>
            </button>
        </div>
    <?php }
    if ($estado == 3) { ?>
        <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
            <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL PEDIDO FUÉ GUARDADO CORRECTAMENTE</div>
            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="fa-times fas fs-3 text-primary"></i>
            </button>
        </div>
<?php }
} ?>
<?php if (isset($borrado)) { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL PEDIDO SE BORRÓ CORRECTAMENTE</div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="fa-times fas fs-3 text-primary"></i>
        </button>
    </div>
<?php } ?>
<?php setlocale(LC_MONETARY, 'es_ES'); ?>
<div class="card card-flush">
    <?php if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 4) { ?>
        <div class="card-header align-items-end py-5 gap-2 gap-md-5">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="myTable3">
                </div>
            </div>
            <form id="form" action="<?php echo base_url(); ?>pedidos" role="form" method="post" name="form">
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
                    <div class="w-auto ms-3">
                        <label for="" class="form-label">Centro:</label>
                        <select name="id_centro" id="id_centro" class="form-select form-select-solid w-auto" <?php /*onchange="document.form.submit();"*/?>>
                            <option value="">Todos</option>
                            <?php if (isset($centros)) {
                                if ($centros != 0) {
                                    foreach ($centros as $key => $row) {
                                        if ($row['id_centro'] > 1) { ?>
                                            <option value='<?php echo $row['id_centro']; ?>' <?= (isset($id_centro) && $row['id_centro'] == $id_centro) ? "selected" : '' ?>>
                                                <?php echo $row['nombre_centro']; ?>
                                            </option>
                            <?php }
                                    }
                                }
                            } ?>
                        </select>
                    </div>
                    <div class="w-auto ms-3">
                        <label for="" class="form-label">Estado:</label>
                        <select name="estado" id="estado" class="form-select form-select-solid w-auto" <?php /*onchange="document.form.submit();"*/?>>
                            <option value=''>Todos</option>
                            <option value='Entregado' <?=(isset($estado) && $estado == "Entregado")?"selected":''?>>Entregado</option>
                            <option value='Facturado' <?=(isset($estado) && $estado == "Facturado")?"selected":''?>>Facturado</option>
                            <option value='Sin Entregar'<?=(isset($estado) && $estado == "Sin Entregar") ?"selected":''?>>Sin Entregar</option>
                            <option value='Sin Enviar' <?=(isset($estado) && $estado == "Sin Enviar")?"selected":''?>>Sin Enviar</option>
                            <option value='Sin Terminar'<?=(isset($estado) && $estado == "Sin Terminar")?"selected":''?>>Sin Terminar</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    <?php } else { ?>
            <input type="hidden" name="id_centro" id="id_centro" value="<?php echo $this->session->userdata('id_centro_usuario'); ?>" />
    <?php } ?>
    <div class="card-body pt-6">
        <div class="table responsive">
            <table id="tablapedidos" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th>Nº Pedido</th>
                        <th>Centro</th>
                        <th>Fecha pedido</th>
                        <th>Fecha entrega</th>
                        <th>Total factura</th>
                        <th>Estado</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php /*if (isset($registros)) {
                        if ($registros != 0) {
                            foreach ($registros as $key => $row) { ?>
                                <tr>
                                    <td style="display: none;"><?php echo $row['id_pedido'] ?></td>
                                    <td><?php echo $row['id_pedido'] ?></td>
                                    <td><?php echo $row['nombre_centro'] ?></td>
                                    <td><?php echo $row['fecha_pedido_ddmmaaaa'] . " " . $row['hora_pedido'] ?></td>
                                    <td><?= ($row['fecha_entrega'] != "") ? $row['fecha_entrega_ddmmaaaa'] . " " . $row['hora_entrega'] : "N/D" ?></td>
                                    <td class="text-end">
                                        <?php echo number_format($row['total_factura'], 2, ",", "."); ?>
                                    </td>
                                    <td>
                                        <?php switch ($row['estado']) {
                                            case 'Sin Entregar':
                                                $span = '<span class="badge d-block fs-4 badge-danger text-uppercase">' . $row['estado'] . '</span>';
                                                break;
                                            case 'Sin Enviar':
                                                $span = '<span class="badge d-block fs-4 badge-warning text-uppercase">' . $row['estado'] . '</span>';
                                                break;
                                            case 'Entregado':
                                                $span = '<span class="badge d-block fs-4 badge-success text-uppercase">' . $row['estado'] . '</span>';
                                                break;
                                            case 'Facturado':
                                                $span = '<span class="badge d-block fs-4 badge-info text-uppercase">' . $row['estado'] . '</span>';
                                                break;
                                            default:
                                                $span = '<span class="badge d-block fs-4 badge-secondary text-uppercase">' . $row['estado'] . '</span>';
                                                break;
                                        }
                                        echo $span; ?>
                                    </td>
                                    <?php if ($this->session->userdata('id_perfil') != 4) { ?>
                                        <td>
                                            <?php if ($row['estado'] == "Sin Enviar") { ?>
                                                <a class="btn btn-sm btn-icon btn-warning" href="<?php echo base_url(); ?>pedidos/gestion/editar_pedido/<?php echo $row['id_pedido'] ?>"><i class="fa-regular fa-pen-to-square"></i></a>
                                            <?php } else { ?>
                                                <a class="btn btn-sm btn-icon btn-info" href="<?php echo base_url(); ?>pedidos/gestion/editar/<?php echo $row['id_pedido'] ?>"><i class="fas fa-eye"></i></a>
                                            <?php } ?>
                                        </td>
                                    <?php } ?>
                                    <td>
                                        <?php if ($this->session->userdata('id_perfil') == 0 || ($this->session->userdata('id_perfil') == 3 && $row['estado'] == "Sin Enviar")) { ?>
                                            <button class="btn btn-sm btn-icon btn-danger" onclick="Borrar(<?php echo $row['id_pedido'] ?>);"><i class="fa-solid fa-trash"></i></button>
                                        <?php } ?>
                                    </td>
                                </tr>
                    <?php }
                        }
                    } */?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    function Borrar(id_pedido) {
        if (confirm("¿DESEA BORRAR EL PEDIDO Nº" + id_pedido + "?")) {
            document.location.href = "<?php echo base_url(); ?>pedidos/gestion/borrar/" + id_pedido;
        }
        return false;
    }
    var base_url = '<?=base_url()?>';
    var tablapedidos = $("#tablapedidos").DataTable({
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
                titlee: "id_pedido",
                name: "id_pedido",
                data: "id_pedido",
            },
            {
                // 1
                titlee: "nombre_centro",
                name: "nombre_centro",
                data: "nombre_centro",
            },
            {
                //2
                titlee: "fecha_pedido",
                name: "fecha_pedido",
                data: "fecha_pedido",
                render: function(data, type, row, meta) {
                    var html = row.fecha_pedido_ddmmaaaa + " " + row.hora_pedido;
                    return html;
                }
            },
            {
                //3
                titlee: "fecha_entrega",
                name: "fecha_entrega",
                data: "fecha_entrega",
                render: function(data, type, row, meta) {
                    var html = (row.fecha_entrega != "" && row.fecha_entrega != null) ? row.fecha_entrega_ddmmaaaa+" "+row.hora_entrega : "N/D";
                    return html;
                }
            },
            {
                //4
                titlee: "total_factura",
                name: "total_factura",
                data: "total_factura",
                /*render: function(date, type, row, meta) {
                }*/
            },
            {
                //5
                titlee: "estado",
                name: "estado",
                data: "estado",
                render: function(data, type, row, meta) {
                    var span = '';
                    switch (row.estado) {
                        case 'Sin Entregar':
                            span = '<span class="badge d-block fs-4 badge-danger text-uppercase">';
                            break;
                        case 'Sin Enviar':
                            span = '<span class="badge d-block fs-4 badge-warning text-uppercase">';
                            break;
                        case 'Entregado':
                            span = '<span class="badge d-block fs-4 badge-success text-uppercase">';
                            break;
                        case 'Facturado':
                            span = '<span class="badge d-block fs-4 badge-info text-uppercase">';
                            break;
                        default:
                            span = '<span class="badge d-block fs-4 badge-secondary text-uppercase">';
                            break;
                    }
                    var html = span+row.estado+'</span>';
                    return html;
                }
            },
            {
                //6
                titlee: "",
                name: "",
                data: "",
                render: function(data, type, row, meta) {
                    var html = '';
                    if (row.estado == "Sin Enviar") {
                        html = `<a class="btn btn-sm btn-icon btn-warning" href="${base_url}pedidos/gestion/editar_pedido/${row.id_pedido}"><i class="fa-regular fa-pen-to-square"></i></a>`;
                    } else {
                        html = `<a class="btn btn-sm btn-icon btn-info" href="${base_url}pedidos/gestion/editar/${row.id_pedido}"><i class="fas fa-eye"></i></a>`;
                    } 
                    return html;
                }
            },
            {
                //7
                titlee: "",
                name: "",
                data: "",
                render: function(data, type, row, meta) {
                    var html = ``;
                    if(row.estado == "Sin Enviar") {
                        var html = `<button class="btn btn-sm btn-icon btn-danger" onclick="Borrar(${row.id_pedido});"><i class="fa-solid fa-trash"></i></button>`;
                    }
                    return html;
                }
            },
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
                targets: [-1],
                orderable: false,
            },
        ],
        ajax: {
            url: "<?php echo base_url(); ?>Pedidos/get_pedidos",
            type: "GET",
            datatype: "json",
            data: function(data) {
                var fecha_desde = $('[name="fecha_desde"]').val();
                var fecha_hasta = $('[name="fecha_hasta"]').val();
                var id_centro = $('[name="id_centro"]').val();
                var estado = $('[name="estado"]').val();
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
        dom:
            /*"<'row'" +
            				"<'col-sm-6 d-flex align-items-center justify-conten-start'f>" +
            				"<'col-sm-6 d-flex align-items-center justify-content-end'>" +
            				">" +*/
            "<'table-responsive'tr>" +
            "<'row'" +
            "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'li>" +
            "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
            ">",
        headerCallback: function(thead, data, start, end, display) {},
        createdRow: function(row, data, dataIndex) {},
        drawCallback: function(settings) {},
        initComplete: function() {},
    });
    <?php /*if ($this->session->userdata('id_perfil') != 4 && $this->session->userdata('id_perfil') != 0) { ?>
        tablapedidos.columns([6]).visible(false);
    <?php } */?>
    $(document).on("change", "#estado, #id_centro", function() {
        tablapedidos.draw();
    });
</script>