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
        <?php echo $conceptos[0]['direccion_completa']; ?>
        <br />
        ================================
        <br />
        <?php echo $conceptos[0]['fecha_creacion_aaaammdd_hhmmss']; ?>
        <br />
        Le atendió: <?php echo $conceptos[0]['atendido_por']; ?>
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
                                echo $row['servicio_completo']; ?>
                                <div><?php echo $row['codigo']; ?></div>
                            </td>
                            <td style="vertical-align: top;">
                                <?php
                                $templos = 1;
                                echo $row['templos'];
                                $total_templos = $total_templos + $row['templos'];
                                ?>
                            </td>
                        </tr>
            <?php }
                }
            } ?>
        </table>
        <p>Total templos gastados:<?php echo $total_templos; ?></p>
        <p style="font-size: 8px; font-weight: bold;"><?php echo "Carnet: " . $conceptos[0]['codigo'] . " Saldo Actual: " . $conceptos[0]['templos_disponibles'] . "T" . "<br>"; ?></p>
        <br />
        ================================
        <br />
        <?php if ($templos == 1) { ?>
            <?php if ($devolucion == 0) { ?>
                <p style="text-align: center;">
                    *Pago realizado en templos*
                </p>
            <?php } else { ?>
                <p style="text-align: center;">
                    *Devolución en templos*
                </p>
            <?php } ?>
            <p style="text-align: center; font-size: 8px;">
                <b>Revisa tu Historial</b>
            </p>
            <p style="font-size: 8px;">
                <b>https://clientes.templodelmasaje.com</b>
            </p>
        <?php } ?>
        <br />
        ================================
        <br />
        <!--
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
                -->
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
        <br>
        <br>
        <br>
        <br><br>
        -
    </div>
</body>

</html>