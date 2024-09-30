
<style>
    .f_seleccionada{
        background-color: #E3E3E3 !important;
    }
</style>


<?php if (isset($estado)) {
    if ($estado > 0) { ?>
        <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
            <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE GUARDÓ CORRECTAMENTE
            </div>
            <button type="button"
                    class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                    data-bs-dismiss="alert">
                <i class="fa-times fas fs-3 text-primary"></i>
            </button>
        </div>
    <?php }
} ?>
<?php if (isset($borrado)) { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE BORRÓ CORRECTAMENTE</div>
        <button type="button"
                class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                data-bs-dismiss="alert">
            <i class="fa-times fas fs-3 text-primary"></i>
        </button>
    </div>
<?php } ?>

<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="card-title">

            </div>
        </div>
        <div class="card-toolbar flex-row-fluid justify-content-start gap-5">
            <div class="ws-auto ms-3">
                <label class="form-label">Fecha factura desde:</label>
                <input type="date" class="form-control form-control-solid w-250px ps-12"
                       placeholder="Selecciona una fecha" id="txtFechaFacturaDesde">
            </div>
            <div class="ws-auto ms-3">
                <label class="form-label">Fecha factura hasta:</label>
                <input type="date" class="form-control form-control-solid w-250px ps-12"
                       placeholder="Selecciona una fecha" id="txtFechaFacturaHasta">
            </div>
            <div class="w-auto ms-3">
                <label class="form-label">Centro:</label>
                <select name="id_centro" id="id_centro" class="form-select form-select-solid w-auto">
                    <option value="">Todos</option>
                    <?php if (isset($centros_todos)) {
                        if ($centros_todos != 0) {
                            foreach ($centros_todos as $key => $row) {
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

            <div class="w-auto ms-3">
                <label class="form-label">Proveedor:</label>
                <select name="proveedor_id" id="proveedor_id" class="form-select form-select-solid w-auto">
                    <option value="">Todos</option>
                    <?php if (isset($proveedor) && !empty($proveedor)): ?>
                        <?php if (count($proveedor) > 0): ?>
                            <?php foreach ($proveedor as $key => $row): ?>
                                <option value="<?php echo $row['id_proveedor'] ?>"><?php echo $row['nombreProveedor'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body pt-6" id="content">
        <div class="table-responsive">
            <div class="table-responsive" id="tablalistadoFacturasX">
               <?php /* <table id="myTable2"
                       class="table align-middle table-striped table-row-dashed fs-6 gy-5 tblListadoGestionFacturas">
                    <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th style="display: none;"></th>
                        <th>Centro</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Doctor</th>
                        <th>Nota</th>
                        <th>Total</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                    <?php if (isset($registros) && !empty($registros)): ?>
                        <?php if (count($registros) > 0): ?>
 <?php */ ?>
                            <?php $this->load->view('gestion/facturas/componentes/listado_detalle_factura', ['registros' => $registros]); ?>
                <?php /*
                        <?php endif; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
 */ ?>
            </div>
        </div>
    </div>
</div>
<script>

    function BorrarFactura(id_gestion_factura) {
        if (confirm("¿Desea borrar esta factura?")) {
            document.location.href = "<?php echo base_url(); ?>facturas/gestion/borrar/" + id_gestion_factura;
        }
        return false;
    }

    $(document).on('change', '.check_gf', function () {
        id_factura = $(this).attr('id_factura');
        if($(this).is(':checked')){
            $(".th_factura_"+id_factura).addClass('f_seleccionada');
            $.post('./facturas/check_gestion_factura/'+id_factura+'/1',function(){})
        }else{
            $(".th_factura_"+id_factura).removeClass('f_seleccionada');
            $.post('./facturas/check_gestion_factura/'+id_factura+'/0',function(){})
        }
        console.log(".th_factura_"+id_factura);
    });

    $(document).on('change', '#txtFechaFacturaDesde', function () {
        getFiltrado();
    });

    $(document).on('change', '#txtFechaFacturaHasta', function () {
        getFiltrado();
    });

    $(document).on('change', '#id_centro', function () {
        getFiltrado();
    });

    $(document).on('change', '#proveedor_id', function () {
        getFiltrado();
    });

    function getFiltrado() {
        var datos = {
            fechaFacturaDesde: $("#txtFechaFacturaDesde").val(),
            fechaFacturaHasta: $("#txtFechaFacturaHasta").val(),
            centroId: $("#id_centro option:selected").val(),
            proveedorId: $("#proveedor_id option:selected").val()
        }

        $.ajax({
            url: '<?php echo base_url() ?>/facturas/getListadoFiltradoGestionFacturas',
            type: 'POST',
            dataType: 'html',
            data: datos,
            success: function (data) {
               // $(".tblListadoGestionFacturas tbody").empty().append(data);
                jQuery("#tablalistadoFacturasX").empty().append(data);
                jQuery("#myTable2").dataTable();
            }
        })
    }
</script>