<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title"></div>
        <form id="form" action="<?php echo base_url(); ?>avisos/recordatorios" role="form" method="post" name="form">
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
                <div class="w-auto">
                    <label for="" class="form-label">Fecha</label>
                    <input type="date" id="fecha" name="fecha" value="<?php if (isset($fecha)) {echo $fecha;} ?>" class="form-control form-control-solid w-auto" placeholder="Fecha" />
                </div>
                <div class="w-auto  ms-3">
                    <button type="button" class="btn btn-info btn-icon text-inverse-info" onclick="Buscar();"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">
            <table id="myTable1" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold text-uppercase gs-0">
                        <th style="display: none;">-</th>
                        <th>Fecha</th>
                        <th>Saldo Inicial</th>
                        <th>C.Efectivo</th>
                        <th>C.Tarjeta</th>
                        <th>C.TPV2</th>
                        <th>C.PayPal</th>
                        <th>C.Transferencia</th>
                        <th>C.Financiado</th>
                        <?php if ($this->session->userdata('id_centro_usuario') == 9) { ?>
                            <th>C.Habitación</th>
                        <?php } ?>
                        <th>D.Efectivo</th>
                        <th>D.Tarjeta</th>
                        <th>D.TPV2</th>
                        <th>D.PayPal</th>
                        <th>D.Transferencia</th>
                        <th>D.Financiado</th>
                        <?php if ($this->session->userdata('id_centro_usuario') == 9) { ?>
                            <th>D.Habitación</th>
                        <?php } ?>
                        <th>Efectivo(m)</th>
                        <th>Tarjeta(m)</th>
                        <th>Transferencia(m)</th>
                        <?php if ($this->session->userdata('id_centro_usuario') == 9) { ?>
                            <th>Habitación(m)</th>
                        <?php } ?>
                        <th>Empleado</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold text-nowrap">
                    <?php if (isset($registros)) {
                        if ($registros != 0) {
                            foreach ($registros as $key => $row) { ?>
                                <?php $sw_marca = 0; ?>
                                <?php if (round($row['descuadre_efectivo'], 2) != 0.00) {
                                    $sw_marca = 1;
                                } ?>
                                <?php if (round($row['descuadre_tarjeta'], 2) != 0.00) {
                                    $sw_marca = 1;
                                } ?>
                                <?php if (round($row['descuadre_tpv2'], 2) != 0.00) {
                                    $sw_marca = 1;
                                } ?>
                                <?php if (round($row['descuadre_paypal'], 2) != 0.00) {
                                    $sw_marca = 1;
                                } ?>
                                <?php if (round($row['descuadre_transferencia'], 2) != 0.00) {
                                    $sw_marca = 1;
                                } ?>
                                <?php if (round($row['descuadre_financiado'], 2) != 0.00) {
                                    $sw_marca = 1;
                                } ?>
                                <?php if (round($row['descuadre_habitacion'], 2) != 0.00) {
                                    $sw_marca = 1;
                                } ?>

                                <?php if ($sw_marca == 0) {
                                    $color_estado = "#e0ffd4";
                                } else {
                                    $color_estado = "#fad7e4";
                                } ?>
                                <tr style="border-color:<?php echo $color_estado; ?>; border-width: 2px;">
                                    <td style="display: none;">
                                        <?php echo $row['fecha_creacion_aaaammdd']; ?>
                                    </td>
                                    <?php $cad = explode("@", $row['email']) ?>
                                    <td>
                                    <a class="btn btn-secondary btn-sm btn-text text-nowrap" href="#" onclick="CierreCaja('<?php echo $row['fecha_creacion_ddmmaaaa'] ?>-<?php echo  str_replace(':', '-', $row['hora']); ?>-<?php echo str_replace(' ','_', $row['empleado']); ?>-<?php echo $cad[0] ?>-<?php echo $cad[1] ?>' )"><?php echo $row['fecha_creacion_ddmmaaaa'].' '. $row['hora']; ?></a>
                                    </td>
                                    <td class="text-end">
                                        <?php echo round($row['saldo_inicial'], 2) . " €"; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php echo round($row['saldo_cierre_efectivo'], 2) . " €"; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php echo round($row['saldo_cierre_tarjeta'], 2) . " €"; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php echo round($row['saldo_cierre_tpv2'], 2) . " €"; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php echo round($row['saldo_cierre_paypal'], 2) . " €"; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php echo round($row['saldo_cierre_transferencia'], 2) . " €"; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php echo round($row['saldo_cierre_financiado'], 2) . " €"; ?>
                                    </td>
                                    <?php if ($this->session->userdata('id_centro_usuario') == 9) { ?>
                                        <td class="text-end">
                                            <?php echo round($row['saldo_cierre_habitacion'], 2) . " €"; ?>
                                        </td>
                                    <?php } ?>
                                    <td class="text-end">
                                        <?php echo round($row['descuadre_efectivo'], 2) . " €"; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php echo round($row['descuadre_tarjeta'], 2) . " €"; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php echo round($row['descuadre_tpv2'], 2) . " €"; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php echo round($row['descuadre_paypal'], 2) . " €"; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php echo round($row['descuadre_transferencia'], 2) . " €"; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php echo round($row['descuadre_financiado'], 2) . " €"; ?>
                                    </td>
                                    <?php if ($this->session->userdata('id_centro_usuario') == 9) { ?>
                                        <td class="text-end">
                                            <?php echo round($row['descuadre_habitacion'], 2) . " €"; ?>
                                        </td>
                                    <?php } ?>
                                    <td class="text-end">
                                        <?php echo round(($row['saldo_cierre_efectivo'] + $row['descuadre_efectivo']), 2) . " €"; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php echo round(($row['saldo_cierre_tarjeta'] + $row['descuadre_tarjeta']), 2) . " €"; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php echo round(($row['saldo_cierre_transferencia'] + $row['descuadre_transferencia']), 2) . " €"; ?>
                                    </td>
                                    <?php if ($this->session->userdata('id_centro_usuario') == 9) { ?>
                                        <td class="text-end">
                                            <?php echo round(($row['saldo_cierre_habitacion'] + $row['descuadre_habitacion']), 2) . " €"; ?>
                                        </td>
                                    <?php } ?>
                                    <td style="text-align: center;">
                                        <?php if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 4) { ?>
                                            <?php echo $row['empleado'] . "<br><span style='font-size: 12px;'>(" . $row['nombre_centro'] . ")</span>"; ?>
                                        <?php } else { ?>
                                            <?php echo $row['empleado']; ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php }
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    function Buscar() {
        document.location.href = "<?php echo base_url(); ?>caja/cierres/listado/0/" + document.getElementById("fecha").value;
    }
    function CierreCaja(fecha) {
        var posicion_x;
        var posicion_y;
        var ancho = 565;
        var alto = 700;
        posicion_x = (screen.width / 2) - (ancho / 2);
        posicion_y = (screen.height / 2) - (alto / 2);
        window.open("<?php echo base_url(); ?>caja/saldocierre/0/" + fecha, "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
    }
</script>