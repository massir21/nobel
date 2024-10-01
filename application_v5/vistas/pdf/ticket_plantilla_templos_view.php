<!DOCTYPE html>
<html lang="es">

<head>
    <title><?= SITETITLE ?></title>
    <meta charset="utf-8" />
    <style>
        @page {
            margin-top: 0px;
            margin-bottom: 0px;
        }
    </style>
</head>
<?php
$logo = FCPATH . '/recursos/logo-templos.png';
$type = pathinfo($logo, PATHINFO_EXTENSION);
$data = file_get_contents($logo);
$logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>

<body>
    <div style="width: 100%; text-align: center;">
        <br />
        <img src="<?= $logo ?>" style="width: 150px;">
        <br />
        <?php echo $ticket[0]['direccion_completa']; ?>
        <br />
        ================================
        <br />
        <?php echo $ticket[0]['fecha_creacion_aaaammdd_hhmmss']; ?>
        <br />
        Le atendió: <?php echo $ticket[0]['atendido_por']; ?>
        <br />
        ================================
        <br />
        <table style="width: 108%;">
            <tr>
                <td>Concepto</td>
                <td>Templos</td>
            </tr>
            <?php $templos = 0;
            $total = 0;
            $total_templos = 0;
            $devolucion = 0;
            if (isset($conceptos)) {
                if ($conceptos != 0) {
                    foreach ($conceptos as $key => $row) { ?>
                        <tr>
                            <td style="vertical-align: top;">
                                <?php
                                if ($row['servicio'] != "") {
                                    echo $row['servicio_completo'];
                                    if ($row['codigo_proveedor'] != "") {
                                        echo "<br>" . $row['codigo_proveedor'];
                                    }
                                } ?>
                                <?php if ($row['producto'] != "") {
                                    echo $row['producto'];
                                } ?>
                                <?php if ($row['carnet'] != "") {
                                    if ($row['servicio'] != "") {
                                        echo "<br>";
                                    }
                                    echo $row['carnet'];
                                }
                                if ($row['recarga'] == 1) {
                                    echo " (Recarga)";
                                }
                                if ($row['codigo_pack_online'] != "") {
                                    echo "<br>(Pack-online: " . $row['codigo_pack_online'] . ")";
                                }
                                ?>
                                <?php if ($row['tipo_pago'] == "#templos" && isset($row['carnets_pagos'][0]['codigo'])) {
                                    $c = 0; ?>
                                    <?php foreach ($row['carnets_pagos'] as $dato) {
                                        if ($c > 0) {
                                            if (in_array($dato['codigo'], $array_codigos)) {
                                                //nada
                                            } else {
                                                $array_codigos[$c] = $dato['codigo'];
                                                //$c++;
                                                $disponible[$c] = $dato['templos_disponibles'];
                                                $gastado[$c] = $row['templos'];
                                                $c++;
                                            }
                                        } else {
                                            $array_codigos[0] = $dato['codigo'];
                                            $disponible[0] = $dato['templos_disponibles'];
                                            $gastado[0] = $row['templos'];
                                            $c++;
                                        }
                                    ?>
                                        <div><?php echo $dato['codigo'] . ' Templos: ' . $dato['templos_detalle']; ?></div>
                                    <?php } ?>
                                <?php } ?>
                                <?php if ($row['descuento_euros'] > 0) {
                                    echo "<br>(Dto. " . round($row['descuento_euros'], 2) . " €)";
                                } ?>
                                <?php if ($row['descuento_porcentaje'] > 0) {
                                    echo "<br>(Dto. " . round($row['descuento_porcentaje'], 2) . "%)";
                                } ?>
                            </td>
                            <td style="vertical-align: top;">
                                <?php if ($row['tipo_pago'] != "#templos") { ?>
                                    <?php $total += $row['importe_total_final']; ?>
                                    <?php echo number_format($row['importe_total_final'], 2, ',', '.') . ""; ?>
                                <?php } else { ?>
                                    <?php $total += 0; ?>
                                    <?php
                                    $templos = 1;
                                    //Original echo "0,00";
                                    echo $row['templos'];
                                    $total_templos = $total_templos + $row['templos'];
                                    ?>
                                <?php } ?>
                            </td>
                            <?php if ($row['estado'] == "Devuelto") {
                                $devolucion = 1;
                            } ?>
                        </tr>
            <?php }
                }
            } ?>
        </table>
        <?php
        if ($total_templos > 0) {
        ?>
            <p>Total templos gastados: <?php echo $total_templos; ?></p>
        <?php
        }
        $xhay = (is_array($array_codigos)) ? count($array_codigos) : 0;
        for ($i = 0; $i < $xhay; $i++) {
        ?>
            <p style="font-size: 10px; font-weight: bold; text-align: center;"><?php echo "Carnet: " . $array_codigos[$i]; ?>
                <?php
                $anterior = $disponible[$i] + $gastado[$i];
                $actual = $disponible[$i];
                ?>
                <!-- <p style="font-size: 8px; font-weight: bold;"><?php echo 'Saldo Anterior: ' . $anterior . "T        Saldo Actual: " . $actual . "T" . "<br>"; ?></p> -->
                <?php echo " Saldo Actual: " . $actual . "T" . "<br>"; ?>
            </p>
        <?php
        }
        ?>
        <?php if ($templos == 1) { ?>
            <?php if ($devolucion == 0) { ?>
                <p style="text-align: center;">
                    *Pago realizado en templos*<br><br>
                    <span style="font-size: 10px; font-weight: bold;"> Revisa tu historial en https://clientes.templodelmasaje.com</span>
                </p>
            <?php } else { ?>
                <p style="text-align: center;">
                    *Devolución en templos*
                </p>
            <?php } ?>
            <?php if (isset($carnets_pago)) {
                echo "<br>" . $carnets_pago;
            } ?>
        <?php } ?>
        <br />
        ================================
        <br />
        <table style="width: 100%; font-size: 15px;">
            <tr>
                <?php if ($devolucion == 0) { ?>
                    <td><b>Total</b></td>
                <?php } else { ?>
                    <td><b>Devolución</b></td>
                <?php } ?>
                <td style="text-align: right; padding-right: -7px;">
                    <b>
                        <?php echo number_format($total, 2, ',', '.') . ""; ?>
                    </b>
                </td>
            </tr>
        </table>
        <br />
        ================================
        <br>
        * Este documento no es una factura *
        <br />
        ================================
        <br />
        <?php
        //13/04/20
        if ($templos == 1) { //Se pagó con templos
        ?>
            <p style="font-size: 9px; font-weight: bold; text-align: justify; ">Las devoluciones de productos se harán dentro de un plazo máximo de 14 días naturales siempre que el producto esté sin abrir salvo defecto del fabricante.
                <br>
                Las devoluciones de Carnets de Templos se harán dentro de un plazo máximo de 14 días naturales. Se devuelve importe íntegro, siempre y cuando no se haya utilizado para pagar ningún servicio ni usado para hacer una reserva en ninguno de los centros Templo del Masaje. En caso de devoluciones de Carnets de Templos usados, se devolverá el importe de los templos no usados, descontando los Templos usados a precio de normal (sin descuento de Carnet).
                <br>
                Los Carnets de Templos no tienen fecha de caducidad, sirven para todos los servicios de la tarifa general de precios vigente y pueden ser compartidos.
                <br>
                Si cancelas con menos de 3 horas de antelación (o no vienes y no nos avisas), te cobraremos el servicio reservado íntegro de tu Carnet de Templos o con dinero en tu siguiente visita. Para citas de duración superior a 90 minutos, las cancelaciones tendrán que ser con 24 horas de antelación.
            </p>
        <?php
        } // Fin pagó con Templos.
        ?>
</body>

</html>