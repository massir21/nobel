<?php setlocale(LC_MONETARY, 'es_ES'); ?>
<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title">
            <h1>A continnuación indica los productos y la cantidad que deseas pedir:</h1>
        </div>
    </div>
    <div class="card-body pt-6">
        <form id="form" action="<?php echo base_url(); ?>pedidos/gestion/anadir_producto/<?php echo $id_pedido ?>" role="form" method="post" name="form">
            <div class="row mb-5 border-bottom align-items-end py-5">
                <div class="col-md-6 mb-5">
                    <label class="form-label">Producto (Stock actual)</label>
                    <select id="id_producto" name="id_producto" class="form-select form-select-solid" data-placeholder="Elegir ..." required></select>
                    <script type="text/javascript">
                        $("#id_producto").select2({
                            language: "es",
                            minimumInputLength: 4,
                            ajax: {
                                delay: 0,
                                url: function(params) {
                                    return '<?php echo RUTA_WWW; ?>/productos/json/' + params.term;
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
                <div class="col-md-3 mb-5">
                    <label class="form-label">Cantidad a pedir</label>
                    <input name="cantidad" class="form-control form-control-solid" type="number" value="" required />
                </div>
                <div class="col-md-3 mb-5">
                    <button type="submit" class="btn btn-info text-inverse-info btn-icon  margin-top-20" data-bs-target="#stack-cliente" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Añadir al Pedido"><i class="fas fa-plus"></i></button>
                </div>
            </div>
        </form>

        <form id="form_productos" action="<?php echo base_url(); ?>pedidos/gestion/guardar/<?php echo $id_pedido ?>" role="form" method="post" name="form_productos">
            <div class="table-responsive mb-5">
                <table id="myTable1" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                    <thead class="">
                        <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                            <th style="display: none;">ID</th>
                            <th>Nombre Familia</th>
                            <th>Nombre Producto</th>
                            <th>PVP</th>
                            <th>Precio franquiciado sin IVA</th>
                            <th>Stock</th>
                            <th>Pedidos</th>
                            <th>Borrar</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($productos_pedido)) {
                            if ($productos_pedido != 0) {
                                foreach ($productos_pedido as $key => $row) { ?>
                                    <tr>
                                        <td style="display: none;">
                                            <?php echo $row['id'] ?>
                                            <input name="id[]" class="form-control form-control-solid" type="hidden" value="<?php echo $row['id'] ?>" />
                                        </td>
                                        <td>
                                            <?php echo $row['nombre_familia'] ?>
                                        </td>
                                        <td>
                                            <?php echo $row['nombre_producto'] ?>
                                        </td>
                                        <td class="text-end">
                                            <?php echo number_format($row['pvp'], 2, ",", "."); ?>
                                        </td>
                                        <td class="text-end">
                                            <?php echo  number_format($row['precio_franquiciado_sin_iva'], 2, ",", "."); ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $row['cantidad_stock'] ?>
                                        </td>
                                        <td style="text-align: center; width: 80px;">
                                            <input name="cantidad[]" class="form-control form-control-solid" type="number" value="<?php echo $row['cantidad'] ?>" required />
                                        </td>
                                        <td style="text-align: center;">
                                            <span class="label label-sm label-danger">
                                                <a href="#" onclick="BorrarItem(<?php echo $row['id'] ?>,<?php echo $row['id_pedido'] ?>,'<?php echo $row['nombre_producto'] ?>');" style="color: #fff; font-weight: bold;">X</a>
                                            </span>
                                        </td>
                                    </tr>
                        <?php }
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-info text-inverse-info margin-top-20" type="submit">Guardar pedido y enviar más adelante</button>
                    <button class="btn btn-primary text-inverse-primary margin-top-20" type="buttom" onclick="Enviar();" >Enviar pedido</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- END CONTENT BODY -->
<script type="text/javascript">
    function Enviar() {
        if (confirm('¿HA VERIFICADO EL PEDIDO Y DESEA ENVIARLO?')) {
            document.form_productos.action = "/pedidos/gestion/enviar/<?php echo $id_pedido ?>";
            document.form_productos.submit();
            return true;
        } else {
            return false;
        }
    }

    function BorrarItem(id, id_pedido, producto) {
        if (confirm("¿DESEA BORRAR EL PRODUCTO " + producto + "?")) {
            document.location.href = "<?php echo base_url(); ?>pedidos/gestion/borrar_producto_pedido/" + id_pedido + "/" + id;
        }
        return false;
    }
</script>