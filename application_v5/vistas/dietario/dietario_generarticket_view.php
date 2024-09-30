<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title><?= SITETITLE ?></title>
    <?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">Conceptos disponibles de <?php echo $cliente[0]['nombre'] . " " . $cliente[0]['apellidos']; ?></h1>
    <div class="card card-flush m-5">
        <div class="card-body p-5">
            <form name="form" id="form" action="<?php echo base_url(); ?>dietario/ticket_crear/<?php echo $cliente[0]['id_cliente'] ?>/<?php echo $id_centro_facturar ?>" method="POST">
                <div class="table-responsive">
                    <table id="conceptos" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                        <thead class="">
                            <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                                <th style="display: none;">ID</th>
                                <th></th>
                                <th>Fecha</th>
                                <th>Emp.</th>
                                <th>Serv/Prod/Carnet</th>
                                <th>Euros</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 fw-semibold">
                            <?php
                            $total_importe = 0;
                            $total_templos = 0;
                            $concepto = "";
                            $sw_citas = 0;
                            $sw_citas_online = 0; ?>
                            <?php if (isset($registros)) {
                                if ($registros != 0) {
                                    foreach ($registros as $key => $row) { ?>
                                        <tr style="background: <?php echo $row['color_estado']; ?>;">
                                            <td style="display: none;"><?php echo $row['id_dietario']; ?></td>
                                            <td>
                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox" name="marcar[]" value="<?php echo $row['id_dietario']; ?>" />
                                                </div>
                                            </td>
                                            <td><?php if ($row['hora'] != "") {
                                                    echo $row['fecha_hora_concepto_ddmmaaaa_abrv'] . "<br>" . $row['hora'];
                                                } else {
                                                    echo "-";
                                                } ?></td>
                                            <td><?php echo $row['empleado']; ?></td>
                                            <td>
                                                <?php if ($row['servicio'] != "") {
                                                    echo $row['servicio'];
                                                    $concepto = $row['servicio'];
                                                }
                                                if ($row['producto'] != "") {
                                                    echo $row['producto'];
                                                    $concepto = $row['producto'];
                                                    if ($row['cantidad'] > 1) {
                                                        echo "<br>(cantidad: " . $row['cantidad'] . ")";
                                                    }
                                                }
                                                if ($row['carnet'] != "") {
                                                    echo $row['carnet'];
                                                    $concepto = $row['carnet'];
                                                    if ($row['recarga'] == 1) {
                                                        echo " (Recarga)";
                                                    }
                                                    if ($row['codigo_pack_online'] != "") {
                                                        echo "<br>(Pack-online: " . $row['codigo_pack_online'] . ")";
                                                    }
                                                }
                                                if ($row['pago_a_cuenta'] == 1) {
                                                    echo "Pago a cuenta";
                                                } ?>
                                            </td>
                                            <td class="text-end">
                                                <?php if ($row['id_tipo'] != "") {
                                                    if ($row['id_tipo'] == 99) {
                                                        $row['importe_euros'] = $row['pvp_carnet'];
                                                    }
                                                    if ($row['id_tipo'] < 99) {
                                                        if ($row['recarga'] == 0) {
                                                            $row['importe_euros'] = $row['pvp_carnet'];
                                                        }
                                                    }
                                                }
                                                if ($row['importe_euros'] != 0) {
                                                    echo number_format($row['importe_euros'], 2, ",", ".") . '€';
                                                    if ($row['importe_euros'] != 0) {
                                                        $total_importe += $row['importe_euros'];
                                                    }
                                                } ?>
                                            </td>
                                            <td>
                                                <?php if ($row['id_pedido'] == 0) {
                                                    $sw_citas = 1;
                                                }
                                                if ($row['id_pedido'] > 0) {
                                                    $sw_citas_online = 1;
                                                    echo '<strong>ON-LINE</strong>';
                                                } ?>
                                            </td>
                                        </tr>
                                <?php }
                                }
                            } else { ?>
                                <tr>
                                    <td colspan="6" class="text-center">Ningun dato en esta tabla</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" style="text-align: right; padding: 8px;"><b>TOTALES</b></td>
                                <td style="text-align: right; padding: 8px;"><?php echo number_format($total_importe, 2, ",", ".") . '€'; ?></td>
                                <!--<td style="text-align: right; padding: 8px;"><?php echo round($total_templos, 2); ?></td>-->
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-sm btn-secondary text-inverse-secondary" type="button" onclick="Cerrar();">Cancelar</button>
                        <button class="btn btn-sm btn-primary text-inverse-primary" type="button" onclick="GenerarTicket();">Generar Ticket de los Conceptos Marcados</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<script>
    function Cerrar() {
        window.opener.location.reload();
        window.close();
    }

    function GenerarTicket() {
        var rows = document.getElementsByName('marcar[]');
        var des = document.getElementById("conceptos");
        var selectedRows = [];
        var mensaje = "";
        for (var i = 0, l = rows.length; i < l; i++) {
            if (rows[i].checked) {
                selectedRows.push(rows[i]);
                precio = des.rows[i + 1].cells[5].innerHTML;
                precio = precio.replace("<br>", " ");
                precio = precio.trim();
                precio = precio.replace(/^\s+|\s+$/g, "");
                celda = des.rows[i + 1].cells[4].innerHTML;
                celda = celda.replace("<br>", " ");
                celda = celda.replace("&nbsp;", "");
                celda = celda.trim();
                celda = celda.replace(/^\s+|\s+$/g, "");
                mensaje += precio + " - " + celda + "\n";
            }
        }
        if (selectedRows.length > 0) {
            if (confirm("¿DESEA GENERAR EL TICKET CON LOS CONCEPTOS SELECCIONADOS?\n\n" + mensaje)) {
                document.form.submit();
            }
        } else {
            alert("DEBE DE MARCAR AL MENOS UN CONCEPTO PARA GENERAR UN TICKET");
        }
    }
</script>
</body>
</html>