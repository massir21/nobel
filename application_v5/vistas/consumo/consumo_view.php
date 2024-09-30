<style>
    .dataTables_filter {
        text-align: right;
    }
</style>
<?php if (isset($estado) && $estado == 1) { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE GUARDÓ CORRECTAMENTE</div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="fa-times fas fs-3 text-primary"></i>
        </button>
    </div>
<?php } ?>
<?php if (isset($borrado) && $borrado == 1) { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE BORRÓ CORRECTAMENTE</div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="fa-times fas fs-3 text-primary"></i>
        </button>
    </div>
<?php } ?>
<?php setlocale(LC_MONETARY, 'es_ES'); ?>
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
        <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
            <?php if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 4) { ?>
                <div class="w-auto ms-3">
                    <label for="" class="form-label">Centro:</label>
                    <select name="id_centro" id="id_centro" class="form-select form-select-solid w-auto">
                        <option value="">Todos</option>
                        <?php if (isset($cecentros_todosntros)) {
                            if ($centros_todos != 0) {
                                foreach ($centros_todos as $key => $row) {
                                    if ($row['id_centro'] > 1) { ?>
                                        <option value='<?php echo $row['id_centro']; ?>' <?=(isset($id_centro) && $row['id_centro'] == $id_centro) ? "selected": ''?>><?php echo $row['nombre_centro']; ?></option>
                                    <?php }
                                }
                            }
                        } ?>
                    </select>
                </div>
            <?php } else { ?>
                <input type="hidden" name="id_centro" id="id_centro" value="<?php echo $this->session->userdata('id_centro_usuario'); ?>" />
            <?php } ?>
            
        </div>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">
            <table id="tabla_consumos" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <?php //<th style="display: none;"></th> ?>
                        <th>Centro</th>
                        <th>Fecha Consumo</th>
                        <th>Familia</th>
                        <th>Producto</th>
                        <th>Cantidad Consumida</th>
                        <th>Nota</th>
                        <th>Cabina</th>
                        <th>Borrar</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    function Borrar(id_consumo) {
        <?php if ($this->session->userdata('id_perfil') != 0 && $this->session->userdata('id_perfil') != 4) { ?>
        if (confirm("¿DESEA BORRAR EL REGISTRO INDICADO?")) {
            document.location.href = "<?php echo base_url(); ?>consumo/borrar/" + id_consumo + "/<?php echo $id_centro ?>";
        }
        return false;
        <?php } else { ?>
            alert('no pudes realizar esta acción');
            return false;
        <?php } ?>
    }
    function FiltroCentros() {
        document.location.href = "<?php echo base_url(); ?>consumo/index/" + document.getElementById("id_centro").value;
    }
    var tabla_consumos = $("#tabla_consumos").DataTable({
            info: true,
            paging: true,
            ordering: true,
            searching: true,
            stateSave: false,
            processing: true,
            serverSide: true,
            scrollX: true,
            autoWidth: false,
            order: [1, "desc"],
            pageLength: 50,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "Todos"],
            ],
            columns: [{
                    //0
                    name: "nombre_centro",
                    data: "nombre_centro",
                },
                {
                    // 1
                    name: "fecha_creacion",
                    data: "fecha_creacion",
                    render: function (data, type, row){
                        return moment(new Date(row.fecha_creacion)).format('DD-MM-YYYY HH:ss');
                    }
                },
                {
                    //2
                    name: "nombre_familia",
                    data: "nombre_familia",
                },
                {
                    //3
                    name: "nombre_producto",
                    data: "nombre_producto",
                },
                {
                    //4
                    name: "cantidad_consumida",
                    data: "cantidad_consumida",
                },
                {
                    //5
                    name: "nota",
                    data: "nota",
                },
                {
                    //6
                    name: "cabina",
                    data: "cabina",
                },
                {
                    //7
                    name: "",
                    data: "",
                    render: function(data, type, row){
                        var html = `<button class="btn btn-sm btn-icon btn-danger" onclick="Borrar(${row.id_consumo});"><i class="fa-solid fa-trash"></i></button>`;
                        return html
                    }
                },
            ],
            columnDefs: [
                {
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
                url: "<?php echo base_url(); ?>Consumo/get_consumos",
                type: "GET",
                datatype: "json",
                data: function(data) {
                    var id_centro = $('[name="id_centro"]').val();
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
        $(document).on("change", "#id_centro", function () {
            tabla_consumos.draw();
        });
        <?php if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 4) { ?>
            tabla_consumos.columns([0,7]).visible(false);
        <?php } ?>
</script>