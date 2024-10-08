<!DOCTYPE html>
<html lang="es">
<!-- BEGIN HEAD -->

<head>
    <title><?= SITETITLE ?></title>
    <meta charset="utf-8" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap');

        * {
            margin: 0;
            padding: 0
        }

        @page {
            size: A4;
            margin: 1cm 0;

            @top-left {
                content: element(header);
            }

            @bottom-left {
                content: element(footer);
            }
        }

        body {
            margin: 0;
            padding: 0;
            color: #000000;
            font-family: 'Montserrat', sans-serif;
            font-size: 10pt;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        hr {
            margin: 1cm 0;
            height: 0;
            border: 0;
            border-top: 1mm solid #eeeeee;
        }

        header {
            margin-top: -1cm;
            padding: .5cm 1cm;
            background-color: #f5f8fa;
        }

        header .headerSection {
            width: 100%;
        }

        header .headerSection h3 {
            margin: 0 .75cm 0 0;
            color: #626262;
        }

        header .headerSection div:last-of-type h3:last-of-type {
            margin-top: .5cm;
        }


        header .headerSection div p {
            margin-top: 2px;
        }

        header h1,
        header h2,
        header h3,
        header p {
            margin: 0;
        }

        header .invoiceDetails,
        header .invoiceDetails h2 {
            text-align: right;
            font-size: 1rem;
            text-transform: none;
        }

        header h2,
        header h3 {
            text-transform: uppercase;
        }

        header hr {
            margin: .5cm 0 .5cm 0;
        }

        main {
            padding: 1cm;
        }

        main table {
            width: 100%;
            border-collapse: collapse;
        }

        main table thead th {
            height: 1cm;
            color: #626262;
        }

        main table thead th:nth-of-type(2),
        main table thead th:nth-of-type(3),
        main table thead th:last-of-type {
            width: 2.5cm;
        }

        main table tbody td {
            padding: 2mm 0;
        }

        main table thead th:last-of-type,
        main table tbody td:last-of-type {
            text-align: right;
        }

        main table th {
            text-align: left;
        }

        main table.summary {
            width: calc(40% + 2cm);
            margin-left: 60%;
            margin-top: .5cm;
        }

        main table.summary tr.total {
            font-weight: bold;
            background-color: #60D0E4;
        }

        main table.summary th {
            padding: 4mm 0 4mm 1cm;
        }

        main table.summary td {
            padding: 4mm 2cm 4mm 0;
            border-bottom: 0;
        }

        aside {

            padding: 0 2cm .5cm 2cm;
        }

        aside>div {

            justify-content: space-between;
        }

        aside>div>div {
            width: 45%;
        }

        aside>div>div ul {
            list-style-type: none;
            margin: 0;
        }

        footer {
            position: fixed;
            bottom: -2cm;
            left: 0px;
            right: 0px;
            height: 2cm;
            background-color: #f5f8fa;
            font-size: 8pt;
            padding-top: .3cm;
            text-align: center;

        }

        footer a:first-child {
            font-weight: bold;
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .table-striped tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        .table-striped th,
        .table-striped td {
            padding-left: 3px;
            padding-right: 3px;
        }

        .table-striped tbody td:first-child(),
        .table-striped tbody tr:first-child() {
            padding-left: .3cm;
        }

        .table-striped tbody td:last-child(),
        .table-striped tbody tr:last-child() {
            padding-right: .3cm;
        }

        .tabla-dientes td {
            padding: 1px;
        }

        .page_number:after {
            content: counter(page);
        }
    </style>
</head>
<?php
$logo = FCPATH . '/assets_v5/media/logos/logo-dorado-sm.png'; //'/recursos/logo-templos.png';
$type = pathinfo($logo, PATHINFO_EXTENSION);
$data = file_get_contents($logo);
$logo = 'data:image/' . $type . ';base64,' . base64_encode($data);

function get_diente_imagen($die)
{
    $logo = FCPATH . '/assets_v5/media/dientes/' . $die . '.png';
    $type = pathinfo($logo, PATHINFO_EXTENSION);
    $data = file_get_contents($logo);
    return 'data:image/' . $type . ';base64,' . base64_encode($data);

    //return '/assets_v5/media/dientes/' . $die . '.png';
}
function mostrar_un_diente($die, $orientac, $dientes_seleccionados)
{
    $bgSelect = '';
    $pos = strpos($dientes_seleccionados, ',' . $die);
    if ($pos !== false) {
        $bgSelect = ' background: #CCCCCC; ';
    }
    $return_mostrar_un_diente = "<td style='width: 15px; text-align: center; {$bgSelect}'>\n";
    if ($orientac == 'supe') {
        $return_mostrar_un_diente .= "<img src='" . get_diente_imagen('raiz-diente-' . $die) . "' vspace='0' style='max-height: 18px;display:block;'>\n";
    }
    $return_mostrar_un_diente .= "<img src='" . get_diente_imagen('diente-' . $die) . "' vspace='0' style='max-height: 10px;'>\n";
    if ($orientac == 'bajo') {
        $return_mostrar_un_diente .= "<img src='" . get_diente_imagen('raiz-diente-' . $die) . "' vspace='0' style='max-height: 18px;'>\n";
    }
    $return_mostrar_un_diente .= "</td>\n";

    return $return_mostrar_un_diente;
}

function mostrar_dientes($desde, $hasta, $sentido, $alignTabla, $orientac, $dientes_seleccionados)
{
    $mostrar_dientes = "<table class='tabla-dientes' align='{$alignTabla}'><tr>\n";
    if ($sentido == -1) {
        for ($i = $desde; $i >= $hasta; --$i) {
            $mostrar_dientes .= mostrar_un_diente($i, $orientac, $dientes_seleccionados);
        }
    } else {
        for ($i = $desde; $i <= $hasta; ++$i) {
            $mostrar_dientes .= mostrar_un_diente($i, $orientac, $dientes_seleccionados);
        }
    }
    $mostrar_dientes .= "</tr></table>\n";
    return $mostrar_dientes;
}
?>

<body>
<footer>
        <span>
            <?= (isset($centro[0]['razon_social_centro']) && $centro[0]['razon_social_centro'] != '') ? $centro[0]['razon_social_centro'] . ' - ' : '' ?>
            <?= (isset($centro[0]['cif_centro'])) ? $centro[0]['cif_centro'] . ' - ' : '' ?>
            <?= (isset($centro[0]['direccion_centro'])) ? $centro[0]['direccion_centro'] . ' - ' : '' ?> pag. <span class="page_number"></span>
        </span>
</footer>
<div style="width: 100%; text-align: center;">


    <header>
        <table class="headerSection">
            <tr>
                <td class="logoAndName">
                    <img src="<?= $logo ?>" style=" height:2.5cm;" />
                </td>
                <td class="invoiceDetails">
                    <h2>PRESUPUESTO #<?= $registro[0]['nro_presupuesto'] ?></h2>
                    <br>
                    <p>Válido hasta <?= date('d-m-Y', strtotime($registro[0]['fecha_validez'])) ?></p>
                </td>
            </tr>
        </table>
        <hr />
        <table class="headerSection">
            <tr>
                <td>
                    <h3>Paciente</h3>
                    <p>
                        <b><?php echo $registro[0]['nombre'] . ' ' . $registro[0]['apellidos'] ?></b>
                        <?php if ($registro[0]['dni'] != '') { ?>
                            <br>
                            DNI: <?php echo $registro[0]['dni'] ?>
                        <?php } ?>
                        <?php if ($registro[0]['telefono'] != '') { ?>
                            <br>
                            Teléfono: <?php echo $registro[0]['telefono'] ?>
                        <?php } ?>
                    </p>
                </td>
                <td>
                    <h3>Doctor</h3>
                    <p>
                        <b><?php echo $doctor[0]['nombre'] . ' ' . $doctor[0]['apellidos'] ?></b>
                        <?php if ($doctor[0]['n_colegiado'] != '') { ?>
                            <br>
                            Nº colegiado: <?php echo $doctor[0]['n_colegiado'] ?>
                        <?php } ?>
                    </p>
                </td>
            </tr>
        </table>
    </header>

    <div style="padding-top: .5cm; margin-bottom: -0.5cm;">
        <?php
        $dientes_seleccionados = '';
        if (count($servicios_items) > 0) {
            foreach ($servicios_items as $i => $value) {
                if ($value['dientes'] != '') {
                    $dientes_seleccionados .= ',' . $value['dientes'];
                }
            }
        }

        if ($dientes_seleccionados != '') { ?>
            <br>
            <table class="table tabla-dientes" align="center" style="width: 70%; margin-bottom: 20px;">
                <tr>
                    <td><?php echo mostrar_dientes(18, 11, -1, 'right', 'supe', $dientes_seleccionados); ?></td>
                    <td width="20"></td>
                    <td><?php echo mostrar_dientes(21, 28, 1, 'left', 'supe', $dientes_seleccionados); ?></td>
                </tr>
                <tr>
                    <td><?php echo mostrar_dientes(55, 51, -1, 'right', 'supe', $dientes_seleccionados); ?></td>
                    <td width="20"></td>
                    <td><?php echo mostrar_dientes(61, 65, 1, 'left', 'supe', $dientes_seleccionados); ?></td>
                </tr>
                <tr>
                    <td><?php echo mostrar_dientes(85, 81, -1, 'right', 'bajo', $dientes_seleccionados); ?></td>
                    <td width="20"></td>
                    <td><?php echo mostrar_dientes(71, 75, 1, 'left', 'bajo', $dientes_seleccionados); ?></td>
                </tr>
                <tr>
                    <td><?php echo mostrar_dientes(48, 41, -1, 'right', 'bajo', $dientes_seleccionados); ?></td>
                    <td width="20"></td>
                    <td><?php echo mostrar_dientes(31, 38, 1, 'left', 'bajo', $dientes_seleccionados); ?></td>
                </tr>
            </table>
        <?php } ?>
    </div>
    <main>
        <?php
        $subtotalitems = 0;
        $totalitems = 0;
        $base = 0;
        $descuentoaplicado = 0;

        if (count($servicios_items) > 0) { ?>
            <table class="table table-striped" style="width: 100%; margin-bottom: 20px;">
                <thead>
                <tr>
                    <th style="text-align: left">Servicio</th>
                    <th style="text-align: center">Cantidad</th>
                    <th style="text-align: center">Dientes</th>
                    <th style="text-align: right">PVP(€)</th>
                    <th style="text-align: right">Dto(%)</th>
                    <th style="text-align: right">Dto(€)</th>
                    <th style="text-align: right">Total</th>
                </tr>
                </thead>
                <tbody>
                <?php

                if (count($servicios) > 0) {
                    foreach ($servicios as $s => $dtoarray) {
                        foreach ($dtoarray as $s => $value) { ?>
                            <tr>
                                <td style="width: 40%; text-align: left;"><?php echo strtoupper($value['nombre_item']); ?></td>
                                <td style="text-align: center;"><?= $value['cantidad'] ?></td>
                                <td style="text-align: center;"><?= $value['dientes'] ?></td>
                                <td style="text-align: right;"><?= number_format($value['pvp'], 2, ',', '.'); ?></td>
                                <td style="text-align: right;"><?= number_format($value['dto'], 2, ',', '.'); ?></td>
                                <td style="text-align: right;"><?= number_format($value['dto_euros'], 2, ',', '.'); ?></td>
                                <td style="text-align: right;"><?= euros($value['coste']); ?>
                                </td>
                            </tr>
                            <?php
                            $base += ($value['pvp'] * $value['cantidad']);
                            $descuentoaplicado += (($value['pvp'] * $value['cantidad']) - $value['coste']);
                            $subtotalitems += $value['pvp'] * $value['cantidad']; //$value['coste'];
                            $totalitems += $value['coste'];
                        }
                    }
                } ?>

                <?php /* if ($ver_item_entrada == true) { ?>
                    <tr>
                        <td style="width: 40%; text-align: left;"><?php echo strtoupper(empty($item_entrada['entrada']) ? 'MENSUALIDAD' : $item_entrada['entrada']); ?></td>
                        <td style="text-align: center;"><?=$item_entrada['cantidad'] ?></td>
                        <td style="text-align: center;"></td>
                        <td style="text-align: right;"><?= number_format($item_entrada['pvp'] / $item_entrada['cantidad'], 2, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($item_entrada['dto'] , 2, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($item_entrada['dto_euros'] / $item_entrada['cantidad'], 2, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= euros($item_entrada['coste']); ?></td>
                    </tr>
                    <?php
                    $base += $item_entrada['pvp'];
                    $descuentoaplicado += ($item_entrada['pvp'] - $item_entrada['coste']);
                    $subtotalitems += $item_entrada['pvp'];
                    $totalitems += $item_entrada['coste'];
                } */?>

                <?php
                if(count($listaentradas)){
                    foreach($listaentradas as $familia => $entradaIndividual){
                        $nombreItem=$entradaIndividual['entradaobject']['nombre_item'];
                        $nombreItem=str_ireplace("MENSUALIDAD","",$nombreItem);
                        $nombreItem=str_ireplace("ENTRADA","",$nombreItem);
                        $nombreItem=trim($nombreItem);
                        if(empty($nombreItem)){
                            $nombreItem="MENSUALIDAD";
                        }
                        ?>
                        <tr>
                            <td style="width: 40%; text-align: left;"><?php echo $nombreItem; ?></td>
                            <td style="text-align: center;"><?= $entradaIndividual['cantidad'];?></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: right;"><?= number_format($entradaIndividual['pvp'],2,',','.');?></td>
                            <td style="text-align: right;"><?= number_format($entradaIndividual['dto'],2,',','.');?></td>
                            <td style="text-align: right;"><?= number_format($entradaIndividual['dto_euros'],2,',','.');?></td>
                            <td style="text-align: right;"><?= euros($entradaIndividual['coste']); ?></td>
                        </tr>
                    <?php
                        $base += $entradaIndividual['pvp'];
                        $descuentoaplicado += ($entradaIndividual['pvp'] - $entradaIndividual['coste']);
                        $subtotalitems += $entradaIndividual['pvp'];
                        $totalitems += $entradaIndividual['coste'];
                    }
                }
                ?>


                <?php if (count($padres) > 0) {
                    foreach ($padres as $k => $dientes) {
                        $param['id_servicio'] = $k;
                        $padre = $this->Servicios_model->leer_servicios($param)[0];
                        foreach ($dientes as $d => $diente) {
                            $descuento = $diente['pvp'] - $diente['coste'];
                            $porcentaje_descuento =  $diente['dto']; //($descuento / $diente['pvp']) * 100;
                            $euros_descuento = $diente['dto_euros'];
                            ?>
                            <tr>
                                <td style="width: 40%; text-align: left;"><?php echo strtoupper($padre['nombre_servicio']); ?></td>
                                <td style="text-align: center;"><?php echo $diente['unidades'];?></td>
                                <td style="text-align: center;"><?= $d ?></td>
                                <td style="text-align: right;"><?= number_format($diente['pvp'], 2, ',', '.'); ?></td>
                                <td style="text-align: right;"><?= number_format($porcentaje_descuento, 2, ',', '.'); ?></td>
                                <td style="text-align: right;"><?= number_format($euros_descuento, 2, ',', '.'); ?></td>
                                <td style="text-align: right;"><?= euros($diente['coste']); ?>
                                </td>
                            </tr>
                            <?php
                            $base += $diente['pvp'];
                            $descuentoaplicado += $descuento;
                            $subtotalitems += $diente['pvp'];
                            $totalitems += $diente['coste'];
                        }
                    }
                } ?>
                </tbody>
            </table>
        <?php } ?>


        <?php
        $dto_euros = 0;
        $dto_100 = 0;
        $dto_100_euros = 0;
        $com_cuota = 0;
        $importecomision = 0;
        $precioCuota = 0;
        ?>
        <table class="table table-striped" style="width: 100%;">
            <tbody>
            <?php /* if($descuentoaplicado > 0) {?>
                        <tr>
                            <td style="text-align: right; width: 75%;">Coste de servicios</td>
                            <td style="text-align: right; width: 25%;"><?= euros($base) ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: right; width: 75%;">Descuento</td>
                            <td style="text-align: right; width: 25%;"><?= euros($descuentoaplicado) ?></td>
                        </tr>
                    <?php } */ ?>
            <tr>
                <td style="text-align: right; width: 75%;">Subtotal</td>
                <td style="text-align: right; width: 25%;"><?= euros($subtotalitems) ?></td>
            </tr>
            <?php if ($registro[0]['dto_euros'] > 0) {
                $dto_euros = $registro[0]['dto_euros'];
                $totalitems = $totalitems - $dto_euros; ?>
                <tr>
                    <td style="text-align: right; width: 75%;">Dto</td>
                    <td style="text-align: right; width: 25%;"><?= euros($registro[0]['dto_euros']) ?></td>
                </tr>
            <?php } else if ($registro[0]['dto_100'] > 0) {
                $dto_100 = $registro[0]['dto_100'];
                $dto_100_euros = $dto_100 / 100 * $totalitems;
                $totalitems = $totalitems - $dto_100_euros;
                ?>
                <tr>
                    <td style="text-align: right; width: 75%;">Dto <?= $registro[0]['dto_100'] ?>%</td>
                    <td style="text-align: right; width: 25%;"><?= euros($dto_100_euros) ?></td>
                </tr>
            <?php } else if ($subtotalitems - $totalitems > 0) {
                $totaldescuento = $subtotalitems - $totalitems;
                $porcentaje_descuento = ($totaldescuento / $subtotalitems) * 100; ?>
                <tr>
                    <td style="text-align: right; width: 75%;">Dto (<?= number_format($porcentaje_descuento, 2, ',', '.') ?>%)</td>
                    <td style="text-align: right; width: 10%;"><?= euros($totaldescuento) ?></td>
                </tr>
            <?php } ?>
            <?php if ($registro[0]['mostrar_financiacion'] != 0) {
                $preciototalfinal = $registro[0]['n_cuotas'] . ' cuotas de ' . euros($registro[0]['totalcuota']); ?>
                <tr>
                    <td style="text-align: right; width: 75%;">Anticipo (€)</td>
                    <td style="text-align: right; width: 25%;"><?= euros($registro[0]['anticipo_financiacion']) ?></td>
                </tr>
                <tr>
                    <td style="text-align: right; width: 75%;">Comisión financiación <?= $registro[0]['com_cuota'] ?>%</td>
                    <td style="text-align: right; width: 25%;"><?= euros($importecomision) ?></td>
                </tr>
            <?php } else {
                $preciototalfinal = euros($totalitems);
            }
            $preciototalfinal = euros($totalitems);?>
            <tr>
                <td style="text-align: right; width: 75%;">Total</td>
                <?php if ($registro[0]['mostrar_financiacion'] != 0) { ?>
                    <td style="text-align: right; width: 25%;"><?= $preciototalfinal ?></td>
                <?php } else { ?>
                    <td style="text-align: right; width: 25%;font-size:1.5rem; font-weight:bold;"><?= $preciototalfinal ?></td>
                <?php } ?>
            </tr>
            </tbody>
        </table>

        <?php if ($registro[0]['mostrar_financiacion'] != 0) { ?>
            <table class="table table-striped" style="width: 100%;margin-top: 1rem;">
                <tbody>
                <tr>
                    <td style="text-align: center;"><span style="font-size:1rem;">El tratamiento completo por <span style="font-size:2rem; font-weight:bold;"><?= euros($registro[0]['totalcuota']) ?></span> cada cuota <small>(<?= $registro[0]['n_cuotas'] ?> cuotas)*</small></span></td>
                </tr>
                </tbody>
            </table>
        <?php } ?>

        <?php if ($registro[0]['mostrar_obs'] != 0 && $registro[0]['estado_relacionado'] != '') { ?>
            <hr />
            <div style="text-align: left;">
                <span style="font-size: 14px; text-align: justify; "><?= $registro[0]['estado_relacionado'] ?></span>
            </div>
        <?php } ?>
    </main>
    <aside>
        <hr />
        <div>
            <p id="watermark" style="font-size: 9px; text-align: justify; ">
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
                escrito a: administracion@clinicadentalnobel.es<br>Los descuentos/promociones de este presupuesto sólo son aplicables a la aceptación integra del tratamiento. En caso de no realizarse la totalidad por causas que no sean de índole medica, se aplicaran los precios de tarifa.<br>*Financiación sujeta a condiciones personales del paciente. Oferta no vinculante.<br>En caso de inicio del tratamiento indicado en el presupuesto, se considerará tácitamente aceptado el presupuesto y todas las condiciones descritas en el mismo.
            </p>
        </div>
        
        <?php if ( !empty($notas_servicios) ) { ?>
        <div style="font-size: 9px; text-align: left;">
            <?php
            foreach ( $notas_servicios as $nota ){
                echo '<p>'.$nota.'</p>';    
            }
            ?>
        </div>
        <?php } ?>
    </aside>

</div>
</body>

</html>