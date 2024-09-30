<!DOCTYPE html>
<html lang="es">
<!-- BEGIN HEAD -->

<head>
    <title><?= SITETITLE ?></title>
    <meta charset="utf-8" />
    <style>
        table td {
            font-size: 15px;
        }

        #watermark {
            position: fixed;
            top: 87%;
            width: 100%;
            text-align: justify;
            z-index: -1000;
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
        <table style="width: 100%; padding: 5px;">
            <tr>
                <td style="width: 30%; text-align: center">
                <img src="<?= $logo ?>" style="width: 150px;">
                </td>
                <!-- <td style="width: 70%; vertical-align: top; text-align: right;">
            <b><?php echo $centro[0]['direccion_completa']; ?>            </b>
          </td>-->
            </tr>
        </table>
        <table style="width: 100%; padding: 5px;">
            <tr>
                <td style="width: 15%; vertical-align: middle;">
                    <div style="border-bottom: 1px solid #000;"></div>
                </td>
                <td style="width: 70%; vertical-align: top; text-align: center;">
                    <b>PRESUPUESTO PARA <?php echo $datos['nombreCliente'] ?></b>
                </td>
                <td style="width: 15%; vertical-align: middle;">
                    <div style="border-bottom: 1px solid #000;"></div>
                </td>
            </tr>
        </table>
        <table style="width: 100%; padding: 5px;">
            <tr>
                <td style="width: 15%; vertical-align: middle;">
                    <!--<div style="border-bottom: 1px solid #000;"></div>-->
                </td>
                <td style="width: 100%; vertical-align: top;">
                    Este presupuesto personalizado tiene una duración de 30 días a partir de su emisión.<br>Productos recomendados por <?php echo $datos['nombreEmpleado'] ?> el <?php setlocale(LC_TIME, "es_ES");
                                                                                                                                                                                echo date('d ');
                                                                                                                                                                                echo strftime(" %B de %Y"); ?>.
                </td>
                <td style="width: 15%; vertical-align: middle;">
                    <!-- <div style="border-bottom: 1px solid #000;"></div>-->
                </td>
            </tr>
        </table>
        <br>
        <?php if ($datos['productoNombre1'] != "") { ?>
            <table style="width: 100%; padding: 5px; padding-bottom: 20px;">
                <tr>
                    <td style="width: 50%;" colspan="2"><b>PRODUCTOS</b></td>
                    <td style="width: 10%; text-align: center;"><b>CANTIDAD</b></td>
                    <td style="width: 10%; text-align: center;"><b>PVP €</b></td>
                    <td style="width: 10%; text-align: center;"><b>DTO %</b></td>
                    <td style="width: 20%; text-align: center;"><b>PVP FINAL €</b></td>
                </tr>
                <?php for ($x = 1; $x <= 15; $x++) {
                    if (isset($datos['productoNombre' . $x]) && $datos['productoNombre' . $x] != "") {
                ?>
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 5px;" colspan="2">
                                <?php echo $datos['productoNombre' . $x]; ?>
                            </td>
                            <td style="text-align: center; border: 1px solid #ddd; padding: 5px; vertical-align: top;">
                                <?php echo $datos['productoCantidad' . $x]; ?>
                            </td>
                            <td style="text-align: center; border: 1px solid #ddd; padding: 5px; vertical-align: top;">
                                <?php echo $datos['productoPrecio' . $x]; ?>
                            </td>
                            <td style="text-align: center; border: 1px solid #ddd; padding: 5px; vertical-align: top;">
                                <?php if ($datos['productoDescuento' . $x] != "0") {
                                    echo $datos['productoDescuento' . $x];
                                } ?>
                            </td>
                            <td style="text-align: center; border: 1px solid #ddd; padding: 5px; vertical-align: top;">
                                <?php if ($datos['productoDescuento' . $x] != "0") {
                                    $descuento = 100;
                                    $descuentoproducto = ($datos['productoDescuento' . $x]);
                                    $DescuentoTotal = intval($descuento) - intval($descuentoproducto);
                                    $suma = ($datos['productoPrecio' . $x] * $DescuentoTotal) / 100;
                                    echo number_format($datos['productoCantidad' . $x] * $suma, 2, '.', ',');
                                } else {
                                    echo number_format($datos['productoCantidad' . $x] * $datos['productoPrecio' . $x], 2, '.', ',');
                                }
                                ?>
                            </td>
                        </tr>
                <?php }
                } ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-end">Subtotal</td>
                    <td style="border: 1px solid #ddd; padding: 5px; text-align: center;">
                        <?php echo number_format($TotalProductos, 2, '.', ','); ?>
                    </td>
                </tr>
            </table>
        <?php } ?>
        <br>
        <br>
        <?php if ($datos['servicioNombre1'] != "") { ?>
            <table style="width: 100%; padding: 5px; padding-bottom: 20px;">
                <tr>
                    <td style="width: 50%;" colspan="2"><b>SERVICIOS</b></td>
                    <td style="width: 10%; text-align: center;"><b>CANTIDAD</b></td>
                    <td style="width: 10%; text-align: center;"><b>PVP €</b></td>
                    <td style="width: 10%; text-align: center;"><b>DTO %</b></td>
                    <td style="width: 20%; text-align: center;"><b>PVP FINAL €</b></td>
                </tr>
                <?php for ($x = 1; $x <= 15; $x++) {
                    if ($datos['servicioNombre' . $x] != "") {
                ?>
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 5px;" colspan="2">
                                <?php echo $datos['servicioNombre' . $x]; ?>
                            </td>
                            <td style="text-align: center; border: 1px solid #ddd; padding: 5px; vertical-align: top;">
                                <?php echo $datos['servicioCantidad' . $x]; ?>
                            </td>
                            <td style="text-align: center; border: 1px solid #ddd; padding: 5px; vertical-align: top;">
                                <?php echo $datos['servicioPrecio' . $x]; ?>
                            </td>
                            <td style="text-align: center; border: 1px solid #ddd; padding: 5px; vertical-align: top;">
                                <?php if ($datos['servicioDescuento' . $x] != "0") {
                                    echo $datos['servicioDescuento' . $x];
                                } ?>
                            </td>
                            <td style="text-align: center; border: 1px solid #ddd; padding: 5px; vertical-align: top;">
                                <?php if ($datos['servicioDescuento' . $x] != "0") {
                                    $descuento = 100;
                                    $descuentoservicio = ($datos['servicioDescuento' . $x]);
                                    $DescuentoTotal = intval($descuento) - intval($descuentoservicio);
                                    $suma = ($datos['servicioPrecio' . $x] * $DescuentoTotal) / 100;
                                    echo number_format($datos['servicioCantidad' . $x] * $suma, 2, '.', ',');
                                } else {
                                    echo number_format($datos['servicioCantidad' . $x] * $datos['servicioPrecio' . $x], 2, '.', ',');
                                }
                                ?>
                            </td>
                        </tr>
                <?php }
                } ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-end">Subtotal</td>
                    <td style="border: 1px solid #ddd; padding: 5px; text-align: center;">
                        <?php echo number_format($TotalServicios, 2, '.', ','); ?>
                    </td>
                </tr>
            </table>
        <?php } ?>
        <p id="watermark" style="font-size: 10px; text-align: justify; ">
            <?php if (isset($centro[0]['empresa'])) {
                echo $centro[0]['empresa'];
            } ?>
             es el Responsable del tratamiento de los datos personales
            del Interesado y le informa que estos datos serán
            tratados de conformidad con lo dispuesto en las normativas vigentes en protección de
            datos personales, el Reglamento (UE) 2016/679 y la Ley Orgánica 15/1999, con la finalidad
            de prestación de servicios profesionales y comunicación sobre productos y servicios.
            Los datos se conservarán mientras exista un interés mutuo para la finalidad descrita.
            Así mismo, se cederán los datos para cumplir con la finalidad del tratamiento y con
            las obligaciones legales que pudieran derivarse de la relación contractual.
            El Interesado puede ejercer sus derechos de acceso, rectificación, portabilidad y
            supresión de sus datos y a la limitación u oposición a su tratamiento dirigiendo un
            escrito a: administracion@clinicadentalnobel.es<br>Los descuentos/promociones de este presupuesto sólo son aplicables a la aceptación integra del tratamiento. En caso de no realizarse la totalidad por causas que no sean de índole medica, se aplicaran los precios de tarifa.
        </p>
    </div>
</body>

</html>