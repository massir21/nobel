<?php if ($this->session->flashdata('mensaje') != '') { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center text-uppercase"><?php echo $this->session->flashdata('mensaje'); ?></div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="fa-times fas fs-3 text-primary"></i>
        </button>
    </div>
<?php } ?>
<?php setlocale(LC_MONETARY, 'es_ES'); ?>
<div class="card card-flush mt-6">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="cupones_descuento">
            </div>
        </div>
        <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
            <div class="w-auto">
                <label for="" class="form-label">Fecha desde</label>
                <input type="date" id="fecha" name="fecha_desde" value="<?= (isset($fecha_desde)) ? $fecha_desde : '' ?>" class="form-control form-control-solid w-auto" placeholder="Fecha desde" />
            </div>
            <div class="w-auto ms-3">
                <label for="" class="form-label">Fecha hasta</label>
                <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?= (isset($fecha_hasta)) ? $fecha_hasta : '' ?>" class="form-control form-control-solid w-auto" placeholder="Fecha hasta" />
            </div>
            <div class="w-auto  ms-3">
                <button type="button" class="btn btn-info btn-icon text-inverse-info" id="filterbutton"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">
            <table id="cupones_descuento" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th>Código cupón</th>
                        <th>Descuento (€)</th>
                        <th>Descuento (%)</th>
                        <th>Validez</th>
                        <th>En uso</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php /*if (isset($cupones)) {
                            if ($cupones != 0) {
                                foreach ($cupones as $key => $row) { ?>
                                    <tr>
                                        <td style="display: none;">
                                            <?php echo $row['id_cupon'] ?>
                                        </td>
                                        <td>
                                            <?php echo $row['codigo_cupon'] ?>
                                        </td>
                                        <td>
                                            <?php echo $row['descuento_euros'] ?>
                                        </td>
                                        <td>
                                            <?php echo $row['descuento_porcentaje'] ?>
                                        </td>
                                        <td>
                                            <?php echo $row['fecha_desde'] . ' -> ' . $row['fecha_hasta']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['valido'] ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <span class="label label-sm label-primary">
                                                <a href="<?php echo base_url(); ?>cupones/editar/<?php echo $row['id_cupon'] ?>" style="color: #fff; font-weight: bold;">Editar</a>
                                            </span>
                                        </td>
                                        <td style="text-align: center;">
                                            <span class="label label-sm label-danger">
                                                <span onclick="Borrar(<?php echo $row['id_cupon'] ?>);" style="color: #fff; font-weight: bold;cursor:pointer">Borrar</span>
                                            </span>
                                        </td>
                                    </tr>
                        <?php }
                            }
                        } */ ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<form id="borrar_cupon" action="<?php echo base_url() . 'cupones/borrar_cupon'; ?>" role="form" method="post" name="form" style="display: none;">
    <input type="text" name="id_cupon" id="id_cupon">
</form>
<script>
    function Borrar(id_cupon) {
        if (confirm("¿Desea borrar el Cupón indicado?")) {
            $('#id_cupon').attr('value', id_cupon);
            document.forms[0].submit();
        }
        return false;
    }
    var base_url = '<?= base_url() ?>';
    var cupones_descuento = $("#cupones_descuento").DataTable({
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
                name: "codigo_cupon",
                data: "codigo_cupon",
            },
            {
                // 1
                name: "descuento_euros",
                data: "descuento_euros",
            },
            {
                // 2
                name: "descuento_porcentaje",
                data: "descuento_porcentaje",
            },
            {
                // 3
                name: "fecha_desde",
                data: "fecha_desde",
                render: function(data, type, row) {
                    return moment(new Date(row.fecha_desde)).format('DD-MM-YYYY HH:mm') + ' -> ' + moment(new Date(row.fecha_hasta)).format('DD-MM-YYYY HH:mm');
                }
            },
            {
                //4
                name: "valido",
                data: "valido",
            },
            {
                //5
                name: "",
                data: "",
                render: function(data, type, row) {
                    var html = `<a class="btn btn-sm btn-icon btn-warning" href="${base_url}cupones/editar/${row.id_cupon}"><i class="fa-regular fa-pen-to-square"></i></a>`;
                    return html
                }
            },
            {
                //6
                name: "",
                data: "",
                render: function(data, type, row) {
                    var html = `<button class="btn btn-sm btn-icon btn-danger" onclick="Borrar(${row.id_cupon});"><i class="fa-solid fa-trash"></i></button>`;
                    return html
                }
            },
        ],
        columnDefs: [{
                targets: [0, 1, 2, 3, 4, 5, 6],
                visible: true,
            },
            {
                targets: ["_all"],
                visible: false,
            },
            {
                targets: [4, -1],
                orderable: false,
            },
        ],
        ajax: {
            url: "<?php echo base_url(); ?>Cupones/get_cupones",
            type: "GET",
            datatype: "json",
            data: function(data) {
                var fecha_desde = $('[name="fecha_desde"]').val();
                var fecha_hasta = $('[name="fecha_hasta"]').val();
                if (fecha_desde != "") {
                    data.fecha_desde = fecha_desde;
                }
                if (fecha_hasta != "") {
                    data.fecha_hasta = fecha_hasta;
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
    $(document).on("click", "#filterbutton", function() {
        cupones_descuento.draw();
    });
</script>
</div>
<!-- END CONTENT BODY -->