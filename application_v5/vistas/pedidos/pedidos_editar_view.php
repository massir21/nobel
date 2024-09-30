<?php setlocale(LC_MONETARY, 'es_ES'); ?>
<div class="card card-flush">
    <form id="form" action="<?php echo base_url(); ?>pedidos/gestion/actualizar_pedido/<?php echo $registros[0]['id_pedido'] ?>" role="form" method="post" name="form" onsubmit="return EsOk();">
        <div class="card-header align-items-center border justify-content-between p-10">
            <h4 class="fw-bolder text-gray-900 fs-2qx">PEDIDO: <?php echo $registros[0]['id_pedido'] ?></h4>
            <div class="text-sm-end">
                <div class="text-sm-end fw-semibold fs-4 text-muted">
                    <div>REALIZADO POR: <?= $registros[0]['nombre_centro'] ?></div>
                    <div>FECHA PEDIDO: <?= $registros[0]['fecha_pedido_ddmmaaaa'] . " " . $registros[0]['hora_pedido'] ?></div>
                    <div>ESTADO: <?= $registros[0]['estado'] ?></div>
                    <?= ($registros[0]['fecha_entrega'] != "") ? '<div class="text-gray-700">FECHA ENTREGA: '. $registros[0]['fecha_entrega_ddmmaaaa'] . " " . $registros[0]['hora_entrega'].'</div>' : '';?>
                </div>
            </div>
        </div>
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
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
                    <div class="w-auto ms-3">
                        <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                            <select name="estado" id="estado" class="form-select form-select-solid w-auto" requiered />
                            <option value="Sin Entregar" <?= ($registros[0]['estado'] == "Sin Entregar") ? "selected" : '' ?>>Sin Entregar</option>
                            <option value="Entregado" <?= ($registros[0]['estado'] == "Entregado") ? "selected" : '' ?>>Entregado</option>
                            <option value="Sin Terminar" <?= ($registros[0]['estado'] == "Sin Terminar") ? "selected" : '' ?>>Sin Terminar</option>
                            <option value="Facturado" <?= ($registros[0]['estado'] == "Facturado") ? "selected" : '' ?>>Facturado</option>
                            </select>
                        <?php } else {
                            echo '<h4>' . $registros[0]['estado'] . '</h4>';
                        } ?>
                    </div>
                </div>
        </div>
        <div class="card-body py-6">
            <div class="table-responsive">
                <table id="myTable1" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                    <thead class="">
                        <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                            <th style="display: none;">ID</th>
                            <th>Nombre Familia</th>
                            <th>Nombre Producto</th>
                            <th>PVP</th>
                            <th>P.Franquiciado<br>sin IVA</th>
                            <th>Stock</th>
                            <th>Cantidad<br> Pedida</th>
                            <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                                <th>Cantidad<br> Entregada</th>
                                <th>Entregar</th>
                            <?php } else { ?>
                                <th>Cantidad<br> Entregada</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($productos_pedido)) {
                            if ($productos_pedido != 0) {
                                foreach ($productos_pedido as $key => $row) { ?>
                                    <tr <?= ($registros[0]['estado'] == "Sin Terminar" && $row['cantidad_entregada'] < $row['cantidad']) ? 'style="background: #fad7e4 !important;"' : ''?>>
                                        <td style="display: none;">
                                            <?php echo $row['id'] ?>
                                            <input type="hidden" name="id[]" value="<?php echo $row['id'] ?>">
                                        </td>
                                        <td <?= ($registros[0]['estado'] == "Sin Terminar" && $row['cantidad_entregada'] < $row['cantidad']) ?'style="background: #fad7e4 !important;"' : ''?>>
                                            <?php echo $row['nombre_familia'] ?>
                                        </td>
                                        <td>
                                            <?php echo $row['nombre_producto'] ?>
                                            <input type="hidden" name="id_productos[]" value="<?php echo $row['id_producto'] ?>">
                                        </td>
                                        <td class="text-end">
                                            <?php echo number_format($row['pvp'], 2, ",", "."); ?>
                                        </td>
                                        <td class="text-end">
                                            <?php echo number_format($row['precio_franquiciado_sin_iva'], 2, ",", "."); ?>
                                        </td>
                                        <td >
                                            <?php echo $row['cantidad_stock'] ?>
                                        </td>
                                        <td >
                                            <?php echo $row['cantidad'] ?>
                                        </td>
                                        <?php if ($this->session->userdata('id_perfil') > 0) { ?>
                                            <td >
                                                <?php echo $row['cantidad_entregada']; ?>
                                            </td>
                                        <?php } ?>
                                        <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                                            <td >
                                                <?php echo $row['cantidad_entregada']; ?>
                                            </td>
                                            <td class="pe-3">
                                                <input type="number" name="cantidad_entregada[]" value="0" class="form-control form-control-solid form-control-sm" />
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php }
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
            <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                <div class="w-100 border-top mt-5 pt-5">
                    <button type="submit" class="btn btn-info text-inverse-info">ACTUALIZAR PEDIDO</button>
                </div>
            <?php } ?>
        </div>
    </form>
</div>
<script type="text/javascript">
    function EsOk() {
        if (document.form.estado.value == "Sin Entregar") {
            alert("PARA ACTUALIZAR EL PEDIDO EL ESTADO NO PUEDE SER: Sin Entregar");
            return false;
        } else {
            if (confirm('¿HA VERICADO EL PEDIDO Y DESEA ACTUALIZARLO CON LOS DATOS INDICADOS?')) {
                return true;
            } else {
                return false;
            }
        }
    }
</script>