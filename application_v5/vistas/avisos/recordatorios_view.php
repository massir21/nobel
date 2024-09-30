<style>
    .dataTables_filter {
        text-align: right;
    }
</style>
<?php if ($estado == 1) { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE GUARDÓ CORRECTAMENTE</div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="fa-times fas fs-3 text-primary"></i>
        </button>
    </div>
<?php } ?>
<?php if ($borrado == 1) { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE BORRÓ CORRECTAMENTE</div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="fa-times fas fs-3 text-primary"></i>
        </button>
    </div>
<?php } ?>
<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="estadistica_usuarios">
                </div>
            </div>
        </div>
        <form id="form" action="<?php echo base_url(); ?>avisos/recordatorios" role="form" method="post" name="form">
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
                <div class="w-auto">
                    <label for="" class="form-label">Fecha desde</label>
                    <input type="date" id="fecha" name="fecha_desde" value="<?= (isset($fecha_desde)) ? $fecha_desde : '' ?>" class="form-control form-control-solid w-auto" placeholder="Fecha desde" />
                </div>
                <div class="w-auto ms-3">
                    <label for="" class="form-label">Fecha hasta</label>
                    <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?php if (isset($fecha_hasta)) {
                                                                                        echo $fecha_hasta;
                                                                                    } ?>" class="form-control form-control-solid w-auto" placeholder="Fecha hasta" />
                </div>
                <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                    <div class="w-auto ms-3">
                        <label for="" class="form-label">Centro:</label>
                        <select name="id_centro" id="id_centro" class="form-select form-select-solid w-auto" onchange="NuevoDiaFiltroCentro();">
                            <option value="">Todos</option>
                            <?php if (isset($centros)) {
                                if ($centros != 0) {
                                    foreach ($centros as $key => $row) {
                                        if ($row['id_centro'] > 1) { ?>
                                            <option value='<?php echo $row['id_centro']; ?>' <?php if (isset($id_centro)) {
                                                                                                    if ($row['id_centro'] == $id_centro) {
                                                                                                        echo "selected";
                                                                                                    }
                                                                                                } ?>>
                                                <?php echo $row['nombre_centro']; ?>
                                            </option>
                            <?php }
                                    }
                                }
                            } ?>
                        </select>
                    </div>
                <?php } ?>
                <div class="w-auto  ms-3">
                    <button type="button" class="btn btn-info btn-icon text-inverse-info" id="filterbutton"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">
            <table id="tabla_recordatorios" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th>Fecha / Hora</th>
                        <th>Creador</th>
                        <th>Estado</th>
                        <th>Recordatorio</td>
                            <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                        <th>Centro</td>
                        <?php } ?>
                        <th></th>
                        <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                            <th></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody class="fs-6 fw-semibold text-gray-600">
                    <?php /* if (isset($registros)) {
                        if ($registros != 0) {
                            foreach ($registros as $key => $row) { ?>
                                <tr <?php //if ($row['estado'] == "Realizado") { cho "style='background: #e0ffd4 !important;'";}  ?>>
                                    <td style="display: none;">
                                        <?php echo $row['fecha_hora_aaaammdd_hhss']; ?>
                                    </td>
                                    <td <?php if ($row['estado'] == "Realizado") {
                                            echo "style='background: #e0ffd4 !important;'";
                                        } ?>>
                                        <?php echo $row['fecha_hora_ddmmaaaa_hhss']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['usuario_creador']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['estado']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['recordatorio']; ?>
                                    </td>
                                    <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                                        <td>
                                            <?php echo $row['nombre_centro']; ?>
                                        </td>
                                    <?php } ?>
                                    <td>
                                        <a class="btn btn-sm btn-icon btn-warning" href="<?php echo base_url(); ?>avisos/editar_recordatorio/<?php echo $row['id_recordatorio'] ?>"><i class="fa-regular fa-pen-to-square"></i></a>
                                    </td>
                                    <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                                        <td>
                                            <a class="btn btn-sm btn-icon btn-danger" href="#" onclick="Borrar(<?php echo $row['id_recordatorio'] ?>);"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    <?php } ?>
                                </tr>
                    <?php }
                        }
                    } ?>*/?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- END CONTENT BODY -->
    <script>
        function Borrar(id_recordatorio) {
            if (confirm("¿DESEA BORRAR EL RECORDATORIO?")) {
                document.location.href = "<?php echo base_url(); ?>avisos/borrar_recordatorio/" + id_recordatorio;
            }
            return false;
        }
        var tabla_recordatorios = $("#tabla_recordatorios").DataTable({
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
                    name: "fecha_hora_ddmmaaaa_hhss",
                    data: "fecha_hora_ddmmaaaa_hhss",
                    render: function (date, type, row){
                       return  moment(new Date(row.fecha_hora_ddmmaaaa_hhss)).format('DD-MM-YYYY HH:mm:ss');
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
                    titlee: "Estado",
                    name: "estado",
                    data: "estado",
                },
                {
                    //3
                    titlee: "Recordatorio",
                    name: "recordatorio",
                    data: "recordatorio",
                },
                {
                    //4
                    titlee: "Centro",
                    name: "nombre_centro",
                    data: "nombre_centro",
                },
                {
                    //5
                    titlee: "",
                    name: "",
                    data: "",
                    render: function(data, type, row){
                        var html = `<a class="btn btn-sm btn-icon btn-warning" href="<?php echo base_url(); ?>avisos/editar_recordatorio/${row.id_recordatorio}"><i class="fa-regular fa-pen-to-square"></i></a>`;
                        return html
                    }
                },
                {
                    //6
                    titlee: "",
                    name: "",
                    data: "",
                    render: function(data, type, row){
                        var html = `<a class="btn btn-sm btn-icon btn-danger" href="#" onclick="Borrar(${row.id_recordatorio});"><i class="fa-solid fa-trash"></i></a>`;
                        return html
                    }
                },
            ],
            columnDefs: [
                {
                    targets: [0, 1, 2, 3, 4, 5, 6],
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
                url: "<?php echo base_url(); ?>Avisos/get_recordatorios",
                type: "GET",
                datatype: "json",
                data: function(data) {
                    var fecha_desde = $('[name="fecha_desde"]').val();
                    var fecha_hasta = $('[name="fecha_hasta"]').val();
                    var id_centro = $('[name="id_centro"]').val();
                    if (fecha_desde != "") {
                        data.fecha_desde = fecha_desde;
                    }
                    if (fecha_hasta != "") {
                        data.fecha_hasta = fecha_hasta;
                    }
                    if (id_centro != "") {
                        data.id_centro = id_centro;
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
			dom:/*"<'row'" +
				"<'col-sm-6 d-flex align-items-center justify-conten-start'f>" +
				"<'col-sm-6 d-flex align-items-center justify-content-end'>" +
				">" +*/
				"<'table-responsive'tr>" +
				"<'row'" +
				"<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'li>" +
				"<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
				">",
            headerCallback: function( thead, data, start, end, display ) {
            },
            createdRow: function(row, data, dataIndex) { 
            },
            drawCallback: function(settings) {
            },
            initComplete: function() {},
        });
        $(document).on("click", "#filterbutton", function () {
            tabla_recordatorios.draw();
        });
    </script>