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
$logo = FCPATH . '/assets_v5\media\logos\logo-dorado-sm.png';
$type = pathinfo($logo, PATHINFO_EXTENSION);
$data = file_get_contents($logo);
$logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>
<body>
    <div style="width: 100%; margin: -20px !important;">
        <table style="width: 100%; padding: 5px;">
            <tr>
                <td style="width: 30%; text-align: center">
                    <img src="<?=$logo?>" style="width: 150px;" />
                </td>
                <td style="width: 70%; vertical-align: top; text-align: right;">
                    <b><?php echo $centro[0]['direccion_completa']; ?> </b>
                </td>
            </tr>
        </table>
        <table style="width: 100%; padding: 5px;">
            <tr>
                <td style="width: 75%; vertical-align: middle;">
                    <div style="border-bottom: 1px solid #000;"></div>
                </td>
                <td style="width: 20%; vertical-align: top; text-align: center;">
                    <b>Nº FACTURA</b>
                </td>
                <td style="width: 5%; vertical-align: middle;">
                    <div style="border-bottom: 1px solid #000;"></div>
                </td>
            </tr>
            <tr>
                <td style="width: 75%; vertical-align: middle;">
                    <div style="border-bottom: 1px solid #000;"></div>
                </td>
                <td style="width: 20%; vertical-align: top; text-align: center;">
                    <b><?php echo $factura[0]['numero_factura']; ?></b>
                </td>
                <td style="width: 5%; vertical-align: middle;">
                    <div style="border-bottom: 1px solid #000;"></div>
                </td>
            </tr>
        </table>
        <table style="width: 100%; padding: 5px;">
            <tr>
                <td colspan="4" style="text-transform: uppercase;">
                    <b><?php echo (isset($cliente[0]['empresa'])) ? $cliente[0]['empresa'] : '';?> - NIF: <?php echo (isset($cliente[0]['cif_nif'])) ? $cliente[0]['cif_nif'] : ''; ?></b>
                </td>
            </tr>
            <tr>
                <td style="width: 11%;">Dirección: </td>
                <td style="width: 57%;"><?php echo (isset($cliente[0]['direccion_facturacion'])) ? $cliente[0]['direccion_facturacion'] : ''; ?></td>
                <td style="width: 17%;">Fecha Emisión: </td>
                <td style="width: 15%;"><?php echo (isset($factura[0]['fecha_emision_ddmmaaaa'])) ? $factura[0]['fecha_emision_ddmmaaaa'] : ''; ?></td>
            </tr>
            <tr>
                <td>Población: </td>
                <td>
                    <?php echo (isset($cliente[0]['localidad_facturacion'])) ? $cliente[0]['localidad_facturacion'] : ''; ?>
                    -
                    <?php echo (isset($cliente[0]['codigo_postal_facturacion'])) ? $cliente[0]['codigo_postal_facturacion'] : ''; ?>
                </td>
            </tr>
            <tr>
                <td>Provincia: </td>
                <td>
                    <?php echo (isset($cliente[0]['provincia_facturacion'])) ? $cliente[0]['provincia_facturacion'] : ''; ?>
                </td>
            </tr>
            <tr>
                <td>Teléfonos: </td>
                <td><?php echo (isset($cliente[0]['telefono'])) ? $cliente[0]['telefono'] : ''; ?></td>
            </tr>
        </table>
        <table style="width: 100%; padding: 5px; padding-bottom: 20px;">
            <tr>
                <td style="width: 80%;" colspan="2"><b>CONCEPTOS</b></td>
                <td style="width: 20%; text-align: right;"><b>IMPORTE</b></td>
            </tr>
            <?php if (isset($conceptos)) {
                if ($conceptos != 0) {
                    $mostrar_frase = "no"; //20/10/20
                    foreach ($conceptos as $key => $row) {
                        $importe = ($row['total'] + $row['descuento_euros']) / (($row['iva'] / 100) + 1);
                        $descuento = ($row['descuento_euros'] / (($row['iva'] / 100) + 1)) * -1;
                        //20/10/20
                        if ($row['iva'] == 0) {
                            $mostrar_frase = "si";
                        }
                        //Fin
            ?>
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 5px;" colspan="2">
                                <?php echo $row['descripcion']; ?>
                                <br>
                                Forma pago: <?php echo str_replace("#", " ", $row['tipo_pago']); ?>
                                <br>
                                Fecha: <?php echo $row['fecha_hora_concepto']; ?>
                            </td>
                            <td style="text-align: right; border: 1px solid #ddd; padding: 5px; vertical-align: top;">
                                <?php echo number_format($importe, 2, ',', '.'); ?> €
                            </td>
                        </tr>
                        <?php if ($row['descuento_euros'] > 0) { ?>
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 5px;" colspan="2">
                                    <span style="font-size: 13px;">
                                        <?php if ($row['descuento_euros'] > 0) { ?>
                                            Descuento <?php echo number_format($row['descuento_porcentaje'], 2, ',', '.'); ?>%
                                            (<?php echo number_format($row['descuento_euros'], 2, ',', '.'); ?>€)
                                            <br>
                                        <?php } ?>
                                    </span>
                                </td>
                                <td style="text-align: right; border: 1px solid #ddd; padding: 5px; vertical-align: top;">
                                    <?php echo number_format($descuento, 2, ',', '.'); ?> €
                                </td>
                            </tr>
                        <?php } ?>
            <?php }
                }
            } ?>
            <tr>
                <td></td>
                <td class="text-end">Subtotal</td>
                <td style="border: 1px solid #ddd; padding: 5px; text-align: right;">
                    <?php echo number_format($factura[0]['importe'], 2, ',', '.'); ?> €
                </td>
            </tr>
            <?php if (isset($ivas_desglose)) {
                if ($ivas_desglose != 0) {
                    foreach ($ivas_desglose as $key => $row) { ?>
                        <tr>
                            <td></td>
                            <td class="text-end">I.V.A. <?php echo round($row['iva'], 2) . "%"; ?></td>
                            <td style="border: 1px solid #ddd; padding: 5px; text-align: right;">
                                <?php echo number_format($row['iva_suma'], 2, ',', '.'); ?> €
                            </td>
                        </tr>
            <?php }
                }
            } ?>
            <tr>
                <td style="width: 60%;">
                </td>
                <td class="text-end">
                    <b>TOTAL</b>
                </td>
                <td style="border: 1px solid #000; background: #000; padding: 5px; color: #fff; text-align: right;">
                    <b><?php echo number_format($factura[0]['total'], 2, ',', '.'); ?> €</b>
                </td>
            </tr>
        </table>
        <?php
        //20/20/20
        if ($mostrar_frase == "si") {
        ?>
            <p style="text-align: center;">
                Factura exenta de IVA en virtud del Artic. 20.3 de la Ley 37/1992.
            </p>
        <?php } ?>
        <p id="watermark" style="font-size: 10px;">
            <?php if (isset($centro[0]['razon_social_centro'])) {
                echo $centro[0]['razon_social_centro'];
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
            escrito a: info@templodelmasaje.com