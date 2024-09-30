<style>                        
    .dataTables_filter {
            text-align: right;
    }
</style>
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
<div class="card card-flush">
    <div class="card-header align-items-end border-bottom py-5 gap-2 gap-md-5">
        <div class="card-title">
            <form id="form" action="<?php echo base_url();?>stock/anadir" role="form" method="post" name="form">        
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
                    <div class="w-auto">
                        <label class="form-label">Producto (Stock actual)</label> 
                        <select name="productos_elegir" id="productos_elegir" class="form-select form-select-solid" data-placeholder="Elegir ..."></select>
                        <script type="text/javascript">
                            $("#productos_elegir").select2({
                                language: "es",
                                minimumInputLength: 4,
                                ajax: {
                                    delay: 0,
                                    url: function (params) {
                                        return '<?php echo RUTA_WWW; ?>/productos/jsonselect2/'+ params.term;
                                    },
                                    dataType: 'json',
                                    processResults: function (data) {
                                        return {
                                            results: data
                                        };
                                    }
                                }
                            });    
                        </script>

                    </div>
                    <div class="w-auto">
                        <label class="form-label">Cantidad a Introducir</label>
                        <input name="cantidad" id="cantidad" type="number" class="form-control form-control-solid w-auto" value="" required/>
                    </div>
                    <div class="w-auto  ms-3">
                        <button type="button" class="btn btn-info text-inverse-info">Añadir stock</button>
                    </div>         
                </div>                
            </form>      
        </div>
    </div>
</div>
<div class="card card-flush mt-6">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="stock_introducido">
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
            <div class="w-auto  ms-3">
                <button type="button" class="btn btn-info btn-icon text-inverse-info" id="filterbutton"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">    
            <table id="stock_introducido" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th>Fecha Introducido</th>
                        <th>Familia</th>
                        <th>Producto</th>
                        <th>Cantidad</th>                        
                        <th>Borrar</th>                        
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                        <?php /* if (isset($registros)) { if ($registros != 0) { foreach ($registros as $key => $row) { ?>
                        <tr>
                            <td style="display: none;">
                                <?php echo $row['fecha_introducido_aaaammdd'] ?>
                            </td>                            
                            <td style="text-align: center;">                            
                                <?php echo $row['fecha_introducido_ddmmaaaa'] ?>
                            </td>
                            <td style="text-align: left;">                            
                                <?php echo $row['nombre_familia']." - ".$row['nombre_producto'] ?>
                            </td>
                            <td style="text-align: center;">                            
                                <?php echo $row['cantidad'] ?>
                            </td>                            
                            <td style="text-align: center;">                            
                                <span class="label label-sm label-danger">
                                    <a href="#" onclick="Borrar(<?php echo $row['id_stock_introducido'] ?>);" style="color: #fff; font-weight: bold;">X</a>
                                </span>                            
                            </td>                            
                        </tr>
                        <?php } } } */ ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    function Borrar(id_stock_introducido) {
        if(confirm("¿DESEA BORRAR EL STOCK INTRODUCIDO?")) {
            document.location.href="<?php echo base_url();?>stock/borrar/"+id_stock_introducido;
        }
        return false;             
    }    
    var stock_introducido = $("#stock_introducido").DataTable({
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
                    name: "fecha_creacion",
                    data: "fecha_creacion",
                    render: function (data, type, row){
                        return moment(new Date(row.fecha_creacion)).format('DD-MM-YYYY HH:ss');
                    }
                },
                {
                    // 1
                    name: "nombre_familia",
                    data: "nombre_familia",
                },
                {
                    // 1
                    name: "nombre_producto",
                    data: "nombre_producto",
                },
                {
                    //2
                    name: "cantidad",
                    data: "cantidad",
                },
                {
                    //3
                    name: "",
                    data: "",
                    render: function(data, type, row){
                        var html = `<button class="btn btn-sm btn-icon btn-danger" onclick="Borrar(${row.id_stock_introducido});"><i class="fa-solid fa-trash"></i></button>`;
                        return html
                    }
                },
            ],
            columnDefs: [
                {
                    targets: [0, 1, 2, 3, 4],
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
                url: "<?php echo base_url(); ?>Stock/get_stock_introducido",
                type: "GET",
                datatype: "json",
                data: function(data) {
                    var fecha_desde = $('[name="fecha_desde"]').val();
                    var fecha_hasta = $('[name="fecha_hasta"]').val();
                   //var id_centro = $('[name="id_centro"]').val();
                    if (fecha_desde != "") {
                        data.fecha_desde = fecha_desde;
                    }
                    if (fecha_hasta != "") {
                        data.fecha_hasta = fecha_hasta;
                    }
                   // if (id_centro != "") {
                    //    data.id_centro = id_centro;
                    //}
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
            stock_introducido.draw();
        });
</script>