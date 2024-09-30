<?php if (isset($estado) && $estado > 0) { ?>
        <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
            <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE GUARDÓ CORRECTAMENTE</div>
            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="fa-times fas fs-3 text-primary"></i>
            </button>
        </div>
<?php } ?>
<?php if (isset($borrado)) { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE BORRÓ CORRECTAMENTE</div>
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
                <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="ventas_online">
            </div>
        </div>
        <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
            <div class="w-auto">
                <label for="" class="form-label">Fecha desde</label>
                <input type="date" id="fecha" name="fecha_desde" value="<?= (isset($fecha_desde)) ? $fecha_desde : '' ?>" class="form-control form-control-solid w-auto" placeholder="Fecha desde" />
            </div>
            <div class="w-auto ms-3">
                <label for="" class="form-label">Fecha hasta</label>
                <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?= (isset($fecha_hasta)) ? $fecha_hasta: ''?>" class="form-control form-control-solid w-auto" placeholder="Fecha hasta" />
            </div>
            <?php if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 4) { ?>
            <div class="w-auto ms-3">
                <label for="" class="form-label">Centro:</label>
                <select name="id_centro" id="id_centro" class="form-select form-select-solid w-auto">
                <option value="">Todos</option>
                    <?php if (isset($centros_todos)) {
                        if ($centros_todos != 0) {
                            foreach ($centros_todos as $key => $row) {
                                if ($row['id_centro'] > 1) { ?>
                                    <option value='<?php echo $row['id_centro']; ?>' <?php if (isset($id_centro)) {if ($row['id_centro'] == $id_centro) {echo "selected";}} ?>>
                                        <?php echo $row['nombre_centro']; ?>
                                    </option>
                                <?php }
                            }
                        }
                    } ?>
                </select>
            </div>
            <?php } else { ?>
                <input type="hidden" name="id_centro" id="id_centro" value="<?php echo $this->session->userdata('id_centro_usuario'); ?>" />
            <?php } ?>
            <div class="w-auto  ms-3">
                <button type="button" class="btn btn-info btn-icon text-inverse-info" id="filterbutton"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">              
            <table id="ventas_online" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th>Centro</th>
                        <th>Fecha Venta</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th class="sorting_disabled" style="width: 5%">Borrar</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php /*if (isset($registros)) { if ($registros != 0) { foreach ($registros as $key => $row) { ?>
                    <tr>
                        <td style="display: none;">
                            <?php echo $row['fecha_consumo_aaaammdd'] ?>
                        </td>
                        <?php if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 4) { ?>
                        <td>
                                <?php echo $row['nombre_centro'] ?>
                        </td>
                        <?php } ?>
                        <td style="text-align: center;">                            
                            <?php echo $row['fecha_consumo_ddmmaaaa'] ?>
                        </td>
                        <td style="text-align: left;">                            
                            <?php echo $row['nombre_familia']." - ".$row['nombre_producto'] ?>
                        </td>
                        <td style="text-align: center;">                            
                            <?php echo $row['cantidad_consumida'] ?>
                        </td>
                        <?php if ($this->session->userdata('id_perfil') != 0 && $this->session->userdata('id_perfil') != 4) { ?>
                        <td style="text-align: center;">                            
                            <span class="label label-sm label-danger">
                                <a href="#" onclick="Borrar(<?php echo $row['id_venta_online'] ?>);" style="color: #fff; font-weight: bold;">X</a>
                            </span>                            
                        </td>
                        <?php } ?>
                    </tr>
                    <?php } } } */?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- END SAMPLE FORM PORTLET-->
</div>
</div>
</div>
<!-- END CONTENT BODY -->
<script>
    function Borrar(id_venta_online) {
        if(confirm("¿DESEA BORRAR EL REGISTRO INDICADO?")) {
            document.location.href="<?php echo base_url();?>ventas_online/borrar/"+id_venta_online+"/<?php echo $id_centro ?>";
        }
        return false;             
    }
    $(document).on("click", "#filterbutton", function () {
        ventas_online.draw();
    });
    var ventas_online = $("#ventas_online").DataTable({
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
                    // 2
                    name: "nombre_familia",
                    data: "nombre_familia",
                },
                {
                    // 3
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
                    name: "",
                    data: "",
                    render: function(data, type, row){
                        var html = `<button class="btn btn-sm btn-icon btn-danger" onclick="Borrar(${row.id_venta_online});"><i class="fa-solid fa-trash"></i></button>`;
                        return html
                    }
                },
            ],
            columnDefs: [
                {
                    targets: [0, 1, 2, 3, 4, 5],
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
                url: "<?php echo base_url(); ?>Ventas_online/get_ventas_online",
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
            ventas_online.draw();
        });
        <?php if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 4) { ?>
            ventas_online.columns([-1]).visible(false);
        <?php }?>
        <?php if ($this->session->userdata('id_perfil') != 0 && $this->session->userdata('id_perfil') != 4) { ?>
            ventas_online.columns([0]).visible(false);
        <?php } ?>
</script>