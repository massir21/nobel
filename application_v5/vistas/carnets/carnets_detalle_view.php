<?php if (isset($ok)) {
    if ($ok > 0) { ?>
        <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
            <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">LOS DATOS SE ACTUALIZARON CORRECTAMENTE</div>
            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="fa-times fas fs-3 text-primary"></i>
            </button>
        </div>
<?php }
} ?>
<?php if (isset($carnet_existe)) { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center"> EL NÚMERO DE CARNET: <?php echo $nuevo_codigo_carnet ?> YA EXISTE. DEBES DE ESPECIFICAR OTRO DIFERENTE</div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="fa-times fas fs-3 text-primary"></i>
        </button>
    </div>
<?php } ?>


<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title">
            Detalle del Carnet <?php echo $carnet[0]['codigo']; ?>
        </div>
    </div>
    <div class="card-body pt-6">
        <div class="border p-5 mb-5">
            <div>Vendido por <?php if ($carnet[0]['empleado'] != "") {
                                    echo $carnet[0]['empleado'];
                                } else {
                                    echo "Tienda Online";
                                } ?></div>
            <div>Vendido en <?php echo $carnet[0]['nombre_centro']; ?></div>
            <div>Precio venta: <?php if ($carnet[0]['id_tipo'] != 99) {
                                    echo number_format($carnet[0]['precio'], 2, ',', '.');
                                } else {
                                    echo number_format($carnet[0]['precio'], 2, ',', '.');
                                } ?>€</div>
            <div>Precio servicios: <?php if ($carnet[0]['id_tipo'] != 99) {
                                        echo (isset($carnet[0]['precio_servicios']) && $carnet[0]['precio_servicios'] != '') ? number_format($carnet[0]['precio_servicios'], 2, ',', '.'): '--';
                                    } else {
                                        echo number_format($carnet[0]['precio_servicios'], 2, ',', '.');
                                    } ?>€</div>
            <div>Vendido el <?php echo $carnet[0]['fecha_vendido']; ?></div>
        </div>

        <form id="form_carnets" action="<?php echo base_url(); ?>carnets/detalle/guardar/<?= (isset($carnet[0]['id_carnet'])) ? $carnet[0]['id_carnet'] : '' ?>" role="form" method="post" name="form_carnets">
            <div class="row mb-5 border-bottom">
                <div class="col-md-6 col-lg-3  mb-5">
                    <?php if ($this->session->userdata('id_perfil') == 0) { ?>

                        <label class="form-label">Historial del Carnet</label>
                        <input type="text" name="codigo_carnet" class="form-control form-control-solid" value="<?php echo $carnet[0]['codigo']; ?>" style="width: 50%;" required>
                        <span>(<?= $carnet[0]['tipo'] . ($carnet[0]['codigo_pack_online'] != "") ? " - Pack Online: " . $carnet[0]['codigo_pack_online'] : '' ?>)</span>

                    <?php } else { ?>

                        <label class="form-label">Historial del Carnet <?php echo $carnet[0]['codigo']; ?></label>
                        <span>(<?php echo $carnet[0]['tipo'] . ($carnet[0]['codigo_pack_online'] != "") ? " - Pack Online: " . $carnet[0]['codigo_pack_online'] : '' ?>)</span>
                        <input type="hidden" name="activo_online" value="<?php echo $carnet[0]['activo_online']; ?>" />

                    <?php } ?>
                    <label class="form-label">Templos Disponibles: <?php echo $carnet[0]['templos_disponibles']; ?></label>
                </div>

                <div class="col-md-6 col-lg-3  mb-5">
                    <label class="form-label">Notas</label>
                    <textarea name="notas" placeholder="Notas sobre el carnet" class="form-control form-control-solid"><?php echo $carnet[0]['notas']; ?></textarea>
                </div>
                <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                    <div class="col-md-6 col-lg-3  mb-5">
                        <label class="form-label">Disponible online</label>
                        <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                            <input class="form-check-input w-45px h-30px" type="checkbox" id="activo_online" name="activo_online" value="1" <?= ($carnet[0]['activo_online'] == 1) ? "checked" : '' ?> />
                            <label for="">Activar Online</label>
                        </div>
                    </div>
                <?php } else { ?>
                    <input type="hidden" name="activo_online" value="<?php echo $carnet[0]['activo_online']; ?>" />
                <?php } ?>

                <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                    <div class="col-md-6 col-lg-3  mb-5">
                        <label class="form-label">Precio</label>
                        <input name="precio" class="form-control form-control-solid" type="number" step="0.01" value="<?= (isset($carnet)) ? $carnet[0]['precio'] : '0' ?>" style="text-align: right; width: 100px;" required />
                    </div>
                <?php } ?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary text-inverse-primary" type="submit">Actualizar</button>
                </div>
            </div>
        </form>
    </div>

    <div class="card-body pt-6">
        <?php 
         $t_recargas = 0;
         $t_gastado = 0;
         if ($carnet[0]['id_tipo'] == 99) { ?>
            <div class="table-responsive my-5">
                <table id="myTable2" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                    <thead class="">
                        <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                            <th style="display: none;">ID</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Servicio</th>
                            <th>Valor €</th>
                            <th>Empleado</th>
                            <th>Centro</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($carnets_servicios)) {
                            if ($carnets_servicios != 0) {
                                foreach ($carnets_servicios as $key => $row) { ?>
                                    <tr style="background: <?php if ($row['borrado'] == 0) {
                                                                echo $row['color_servicio'];
                                                            } else {
                                                                echo '#ddd';
                                                            } ?> !important">
                                        <td style="display: none;">
                                            <?php echo $row['fecha_modificacion_aaaammdd']; ?>
                                        </td>
                                        <td style="background: <?php if ($row['borrado'] == 0) {
                                                                    echo $row['color_servicio'];
                                                                } else {
                                                                    echo '#ddd';
                                                                } ?> !important; text-align: center;">
                                            <?php if ($row['estado_servicio'] == "Gastado") { ?>
                                                <?php echo $row['fecha_modificacion_ddmmaaa']; ?><br>
                                                <?php echo $row['estado_servicio']; ?>
                                            <?php } else { ?>
                                                <?php if ($row['borrado'] == 0) {
                                                    echo $row['estado_servicio'];
                                                } else {
                                                    echo "Quitado";
                                                } ?>
                                            <?php } ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $row['cliente']; ?>
                                        </td>
                                        <td style="text-align: left;">
                                            <?php echo $row['nombre_familia'] . " - " . $row['nombre_servicio'] . " (" . $row['duracion'] . " min)"; ?>
                                        </td>
                                        <td class="text-end">
                                            <?php echo (isset($row['pvp']) && $row['pvp'] != '') ? number_format($row['pvp'], 2, ',', '.'). '€': '--'; ?>
                                        </td>
                                        <td style="text-align: left;">
                                            <?php if ($row['estado_servicio'] == "Gastado") { ?>
                                                <?php echo $row['empleado']; ?>
                                            <?php } else { ?>
                                                <?php if ($row['borrado'] == 1) {
                                                    echo $row['empleado'];
                                                } ?>
                                            <?php } ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php if ($row['estado_servicio'] == "Gastado") { ?>
                                                <?php echo $row['centro_servicio']; ?>
                                            <?php } else { ?>
                                                <?php if ($row['borrado'] == 1) {
                                                    echo $row['centro_servicio'];
                                                } ?>
                                            <?php } ?>
                                        </td>
                                    </tr>
                        <?php }
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <div class="row my-5 border-bottom">
                <div class="col-md-4">
                    <label class="form-label">Desde</label>
                    <input name="fecha_desde" id="fecha_desde" type="date" value="" class="form-control form-control-solid" />
                </div>

                <div class="col-md-4">
                    <label class="form-label">Hasta</label>
                    <input name="fecha_hasta" id="fecha_hasta" type="date" value="" class="form-control form-control-solid" />
                </div>

                <div class="col-md-4">
                    <button type="button" onclick="Exportar(<?php echo $carnet[0]['id_carnet']; ?>)" class="btn btn-warning text-inverse-warning">Exportar CSV</button>
                </div>
            </div>
            <div class="table-responsive mb-5">
                <table id="myTable2" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                    <thead class="">
                        <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                            <th style="display: none;">ID</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Servicio</th>
                            <th>Templos</th>
                            <th>Tipo</th>
                            <th>Empleado</th>
                            <th>Centro</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($carnets_historial)) {
                            if ($carnets_historial != 0) {
                               
                                foreach ($carnets_historial as $key => $row) { ?>
                                    <tr style="background: <?php if ($row['templos'] > 0) {
                                                                echo "#e0ffd4";
                                                            } else {
                                                                echo "#faf1d7";
                                                            } ?>;">
                                        <td style="display: none;">
                                            <?php echo $row['fecha_concepto_aaaammdd']; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $row['fecha_concepto_ddmmaaaa']; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $row['cliente']; ?>
                                        </td>
                                        <td style="text-align: left;">
                                            <?php if ($row['carnet_especial'] == "") { ?>
                                                <?php echo $row['nombre_familia'] . " - " . $row['nombre_servicio'] . " (" . $row['duracion'] . " min)"; ?>
                                            <?php } else { ?>
                                                <?php echo $row['carnet_especial']; ?>
                                            <?php } ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo round($row['templos'], 2); ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php if ($row['templos'] > 0) {
                                                echo "gastado";
                                            } else {
                                                echo "devuelto";
                                            }
                                            $t_gastado = $t_gastado + (round($row['templos'], 2));
                                            ?>
                                        </td>
                                        <td style="text-align: left;">
                                            <?php
                                            if ($row['empleado'] != "") {
                                                echo $row['empleado'];
                                            } else {
                                                echo "Anulación Online";
                                            }
                                            ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $row['nombre_centro']; ?>
                                        </td>
                                    </tr>
                        <?php }
                            }
                        } ?>
                        <?php if (isset($carnets_ajustes)) {
                            if ($carnets_ajustes != 0) {
                                foreach ($carnets_ajustes as $key => $row) { ?>
                                    <tr style="background: #e0ffd4;">
                                        <td style="display: none;">
                                            <?php echo $row['fecha_aaaammdd'] ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $row['fecha'] ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php if (isset($carnet[0]['cliente'])) {
                                                echo $carnet[0]['cliente'];
                                            } ?>
                                        </td>
                                        <td style="text-align: center;">
                                            -
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo round($row['templos_disponibles'] - $row['templos_disponibles_anteriores'], 2);
                                            $t_recargas = $t_recargas + (round($row['templos_disponibles'] - $row['templos_disponibles_anteriores'], 2));
                                            ?>
                                        </td>
                                        <td style="text-align: center;">
                                            recarga.
                                        </td>
                                        <td style="text-align: center;">
                                            <?php
                                            if ($row['empleado'] != "") {
                                                echo $row['empleado'];
                                            } else {
                                                echo "Recarga Online";
                                            }
                                            ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $row['nombre_centro'] ?>
                                        </td>
                                    </tr>
                        <?php }
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>

        <div class="alert alert-primary text-center">
            <strong>Total Recargas: <?php echo $t_recargas; ?> Total Gastado: <?php echo $t_gastado; ?> Disponible: <?php echo $t_recargas - $t_gastado; ?></strong>
        </div>
    </div>
</div>

<script>
    //12/05/20
    function Exportar(id_carnet) {
        var fecha_desde = $('#fecha_desde').val();
        var fecha_hasta = $('#fecha_hasta').val();
        console.log('Fechas ' + fecha_desde + ' ' + fecha_hasta);
        if (fecha_desde == "" || fecha_hasta == "")
            document.location.href = "<?php echo base_url(); ?>carnets/historial_csv/" + id_carnet;
        else {
            console.log('Fechas ' + fecha_desde + ' ' + fecha_hasta);
            document.location.href = "<?php echo base_url(); ?>carnets/historial_csv/" + id_carnet + "/" + fecha_desde + "/" + fecha_hasta;
        }
        return false;
    }
    //Fin 11/05/20
</script>