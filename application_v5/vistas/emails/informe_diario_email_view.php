<!doctype html>
<html lang="es">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <style>
        body {
            font-family: Inter, Helvetica, "sans-serif";
            font-size: 12px;
        }

        table {
            font-family: Inter, Helvetica, "sans-serif";
            font-size: 12px;
        }

        .fondo-fila {
            background-color: #fff1d5;
        }

        th {
            height: 30px;
        }

        td {
            padding: 5px;
        }

        .label {
            background: #aaa;
            padding: 2px;
        }

        .titulo-informe {
            text-transform: uppercase;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }

        .titulo-seccion {
            text-transform: uppercase;
            font-size: 14px;
            margin-top: 30px;
            font-weight: bold;
        }

        .cabecera-tabla {
            background: #1e1e2d;
            color: #fff;
        }

        .sin-datos {
            text-align: left;
        }
    </style>
    <title><?= SITETITLE ?></title>
</head>

<body>
    <div style="padding: 30px;">
        <p class="titulo-informe">
            INFORME PARA <?php echo $centro[0]['nombre_centro'] ?> - <?php echo $fecha_dia ?>
        </p>
        <hr>
        <!-- ------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------ -->
        <?php /*<p class="titulo-seccion">
            Carnets Especiales. No coincide el Pago con el Precio Total de cada Servicio
        </p>
        <?php if (isset($carnets_especiales)) {
            if ($carnets_especiales != 0) { ?>
                <table style="width: 100%;">
                    <thead class="cabecera-tabla">
                        <tr>
                            <th>Fecha / Hora</th>
                            <th>Cliente</th>
                            <th>Nº Carnet</th>
                            <th>Empleado</th>
                            <th>Pagado</th>
                            <th>Precio Servicios</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($carnets_especiales)) {
                            if ($carnets_especiales != 0) {
                                $i = 0;
                                foreach ($carnets_especiales as $key => $row) { ?>
                                    <?php if ($i % 2 == 0) { ?>
                                        <tr>
                                        <?php } else { ?>
                                        <tr class="fondo-fila">
                                        <?php } ?>
                                        <td style="text-align: center; font-size: 11px;">
                                            <?php echo $row['fecha_hora_concepto_ddmmaaaa_abrv']; ?><br>
                                            <?php echo $row['hora']; ?>
                                        </td>
                                        <td style="text-align: left;">
                                            <?php echo $row['cliente']; ?>
                                        </td>
                                        <td style="text-align: left;">
                                            <?php echo $row['carnet']; ?>
                                            <div style="text-transform: lowercase;">
                                                <?php echo $row['notas_carnet']; ?>
                                            </div>
                                        </td>
                                        <td style="text-align: left;">
                                            <?php echo $row['empleado']; ?>
                                        </td>
                                        <td class="text-end">
                                            <?php if ($row['notas_pago_descuento'] != "") { ?>
                                                <span style="cursor: pointer; cursor: hand;" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="<?php echo $row['notas_pago_descuento']; ?>">
                                                <?php } ?>
                                                <?php if ($row['tipo_pago'] != "#templos") { ?>
                                                    <?php echo number_format($row['importe_total_final'], 2, ',', '.') . "€"; ?>
                                                    <?php if ($row['descuento_euros'] > 0) {
                                                        echo "<br><span class='label label-default' style='font-size: 11px; color: #fff;'>Dto. " . round($row['descuento_euros'], 2) . " €</span>";
                                                    } ?>
                                                    <?php if ($row['descuento_porcentaje'] > 0) {
                                                        echo "<br><span class='label label-default' style='font-size: 11px; color: #fff;'>Dto. " . round($row['descuento_porcentaje'], 2) . "%</span>";
                                                    } ?>
                                                <?php } else { ?>
                                                    <?php echo "0,00€"; ?>
                                                <?php } ?>
                                                <?php if ($row['notas_pago_descuento'] != "") { ?>
                                                </span>
                                            <?php } ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo number_format($row['precio_servicios'], 2, ',', '.') . "€"; ?>
                                        </td>
                                        </tr>
                                        <?php $i++; ?>
                            <?php }
                            }
                        } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p>No hay datos para esta sección</p>
        <?php }
        } ?>
        */ ?>

        <!-- ------------------------------------------------------------------------------ -->
        <!-- --- FACTUACION DIARIA Y TOTAL MES -------------------------------------------- -->
        <!-- ------------------------------------------------------------------------------ -->
        <p class="titulo-seccion">
            Facturación Diaria
        </p>
        <?php if (isset($facturacion_manana)) {
            if ($facturacion_manana != 0) { ?>
                <table class="table table-striped table-hover table-bordered" style="width: 100%;">
                    <thead class="cabecera-tabla">
                        <tr>
                            <th></th>
                            <th>Efectivo</th>
                            <th>Tarjeta</th>
                            <th>Transferencia</th>
                            <th>Paypal</th>
                            <th>Tpv2</th>
                            <th>Financiado</th>
                            <th>Total</th>
                            <th></th>
                            <th>Total mes</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <tr>
                            <td>
                                <b>Mañana</b>
                            </td>
                            <td style="text-align: right">
                                <?php echo number_format($total_efectivo_manana, 2, ",", ".") . " €"; ?>
                            </td>
                            <td style="text-align: right">
                                <?php echo number_format($total_tarjeta_manana, 2, ",", ".") . " €"; ?>
                            </td>
                            <!-- 24/03/20 -->
                            <td style="text-align: right">
                                <?php echo number_format($total_transferencia_manana, 2, ",", ".") . " €"; ?>
                            </td>
                            <!-- 05/03/23 -->
                            <td style="text-align: right">
                                <?php echo number_format($total_paypal_manana, 2, ",", ".") . " €"; ?>
                            </td>
                            <!-- 05/03/23 -->
                            <td style="text-align: right">
                                <?php echo number_format($total_tpv2_manana, 2, ",", ".") . " €"; ?>
                            </td>
                            <td style="text-align: right">
                                <?php echo number_format($total_financiado_manana, 2, ",", ".") . " €"; ?>
                            </td>
                            <?php if ($id_centro == 9) { ?>
                                <td style="text-align: right">
                                    <?php echo number_format($total_habitacion_manana, 2, ",", ".") . " €"; ?>
                                </td>
                            <?php } ?>
                            <td style="text-align: right">
                                <?php echo number_format($total_manana, 2, ",", ".") . " €"; ?>
                            </td>
                            <td>
                                <?php if ($total_cierre_manana > 0) {
                                    echo "<span style='color: blue;'>Sobra: ";
                                } ?>
                                <?php if ($total_cierre_manana < 0) {
                                    echo "<span style='color: red;'>Faltan: ";
                                } ?>
                                <?php if ($total_cierre_manana == 0) {
                                    echo "<span style='color: green;'>Cuadra ";
                                } ?>
                                <?php echo number_format($total_cierre_manana, 2, ",", ".") . " €"; ?>
                            </td>
                            <td style="text-align: right">
                                <?php echo number_format($mes_facturacion_manana, 2, ",", ".") . " €"; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Tarde</b>
                            </td>
                            <td style="text-align: right">
                                <?php echo number_format($total_efectivo_tarde, 2, ",", ".") . " €"; ?>
                            </td>
                            <td style="text-align: right">
                                <?php echo number_format($total_tarjeta_tarde, 2, ",", ".") . " €"; ?>
                            </td>
                            <!-- 24/03/20 -->
                            <td style="text-align: right">
                                <?php echo number_format($total_transferencia_tarde, 2, ",", ".") . " €"; ?>
                            </td>
                            <!-- 05/03/23 -->
                            <td style="text-align: right">
                                <?php echo number_format($total_paypal_tarde, 2, ",", ".") . " €"; ?>
                            </td>
                            <!-- 05/03/23 -->
                            <td style="text-align: right">
                                <?php echo number_format($total_tpv2_tarde, 2, ",", ".") . " €"; ?>
                            </td>
                            <td style="text-align: right">
                                <?php echo number_format($total_financiado_tarde, 2, ",", ".") . " €"; ?>
                            </td>
                            <?php if ($id_centro == 9) { ?>
                                <td style="text-align: right">
                                    <?php echo number_format($total_habitacion_tarde, 2, ",", ".") . " €"; ?>
                                </td>
                            <?php } ?>
                            <td style="text-align: right">
                                <?php echo number_format($total_tarde, 2, ",", ".") . " €"; ?>
                            </td>
                            <td>
                                <?php if ($total_cierre_tarde > 0) {
                                    echo "<span style='color: blue;'>Sobra: ";
                                } ?>
                                <?php if ($total_cierre_tarde < 0) {
                                    echo "<span style='color: red;'>Faltan: ";
                                } ?>
                                <?php if ($total_cierre_tarde == 0) {
                                    echo "<span style='color: green;'>Cuadra ";
                                } ?>
                                <?php echo number_format($total_cierre_tarde, 2, ",", ".") . " €</span>"; ?>
                            </td>

                            <td style="text-align: right">
                                <?php echo number_format($mes_facturacion_tarde, 2, ",", ".") . " €"; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Total</b>
                            </td>
                            <td style="text-align: right">
                                <?php echo number_format($total_facturacion_efectivo, 2, ",", ".") . " €"; ?>
                            </td>
                            <td style="text-align: right">
                                <?php echo number_format($total_facturacion_tarjeta, 2, ",", ".") . " €"; ?>
                            </td>
                            <!-- 24/03/20 -->
                            <td style="text-align: right">
                                <?php echo number_format($total_facturacion_transferencia, 2, ",", ".") . " €"; ?>
                            </td>
                            <!-- 05/03/23 -->
                            <td style="text-align: right">
                                <?php echo number_format($total_facturacion_paypal, 2, ",", ".") . " €"; ?>
                            </td>
                            <!-- 05/03/23 -->
                            <td style="text-align: right">
                                <?php echo number_format($total_facturacion_tpv2, 2, ",", ".") . " €"; ?>
                            </td>
                            <td style="text-align: right">
                                <?php echo number_format($total_facturacion_financiado, 2, ",", ".") . " €"; ?>
                            </td>
                            <?php if ($id_centro == 9) { ?>
                                <td style="text-align: right">
                                    <?php echo number_format($total_facturacion_habitacion, 2, ",", ".") . " €"; ?>
                                </td>
                            <?php } ?>
                            <td style="text-align: right">
                                <b><?php echo number_format($total_facturacion, 2, ",", ".") . " €"; ?></b>
                            </td>
                            <td>
                                <?php echo ""; ?>
                            </td>
                            <td style="text-align: right">
                                <?php echo number_format($mes_facturacion_total, 2, ",", ".") . " €"; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <?php } else { ?>
                <div style="color: red;">Sin datos</div>
        <?php }
        } ?>
        <hr>
        <!-- ------------------------------------------------------------------------------ -->
        <!-- --- LINEAS CON DESCUENTOS ---------------------------------------------------- -->
        <!-- ------------------------------------------------------------------------------ -->
        <p class="titulo-seccion">
            Líneas del Dietario con Descuentos
        </p>
        <?php if (isset($lineas_con_descuento)) {
            if ($lineas_con_descuento != 0) { ?>
                <table style="width: 100%;">
                    <thead class="cabecera-tabla">
                        <tr>
                            <th>Fecha / Hora</th>
                            <th>Cliente</th>
                            <th>Ser/Prod/Carnet.</th>
                            <th>Empleado</th>
                            <th>Pagado</th>
                            <th>Notas Descuento</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($lineas_con_descuento)) {
                            if ($lineas_con_descuento != 0) {
                                $i = 0;
                                foreach ($lineas_con_descuento as $key => $row) { ?>
                                    <?php if ($i % 2 == 0) { ?>
                                        <tr>
                                        <?php } else { ?>
                                        <tr class="fondo-fila">
                                        <?php } ?>
                                        <td style="text-align: center; font-size: 11px;">
                                            <?php echo $row['fecha_hora_concepto_ddmmaaaa_abrv']; ?><br>
                                            <?php echo $row['hora']; ?>
                                        </td>
                                        <td style="text-align: left;">
                                            <?php echo $row['cliente']; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php if ($row['servicio'] != "") {
                                                echo $row['servicio'];
                                                if ($row['codigo_proveedor'] != "") {
                                                    echo "<br>" . $row['codigo_proveedor'];
                                                }
                                            } ?>
                                            <?php if ($row['producto'] != "") {
                                                echo $row['producto'];
                                                if ($row['cantidad'] > 1) {
                                                    echo "<br>(cantidad: " . $row['cantidad'] . ")";
                                                }
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
                                        </td>
                                        <td style="text-align: left;">
                                            <?php echo $row['empleado']; ?>
                                        </td>
                                        <td class="text-end">
                                            <?php echo number_format($row['importe_total_final'], 2, ',', '.') . "€"; ?>
                                        </td>
                                        <td style="text-align: left;">
                                            <?php if ($row['descuento_euros'] > 0) {
                                                echo "<span class='label' style='font-size: 11px; color: #fff;'>Dto. " . round($row['descuento_euros'], 2) . " €</span>";
                                            } ?>
                                            <?php if ($row['descuento_porcentaje'] > 0) {
                                                echo "<span class='label' style='font-size: 11px; color: #fff;'>Dto. " . round($row['descuento_porcentaje'], 2) . "%</span>";
                                            } ?>
                                            <br>
                                            <?php echo $row['notas_pago_descuento']; ?>
                                        </td>
                                        </tr>
                                        <?php $i++; ?>
                            <?php }
                            }
                        } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p class="sin-datos">No hay datos para esta sección</p>
        <?php }
        } ?>
        <hr>
        <!-- ------------------------------------------------------------------------------ -->
        <!-- --- DEVOLUCIONES ------------------------------------------------------------- -->
        <!-- ------------------------------------------------------------------------------ -->
        <p class="titulo-seccion">
            Devoluciones
        </p>
        <?php if (isset($devoluciones)) {
            if ($devoluciones != 0) { ?>
                <table style="width: 100%;">
                    <thead class="cabecera-tabla">
                        <tr>
                            <th>Fecha / Hora</th>
                            <th>Cliente</th>
                            <th>Ser/Prod/Carnet.</th>
                            <th>Empleado</th>
                            <th>Devolución</th>
                            <th>Templos</th>
                            <th>Motivo Devolución</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($devoluciones)) {
                            if ($devoluciones != 0) {
                                $i = 0;
                                foreach ($devoluciones as $key => $row) { ?>
                                    <?php if ($i % 2 == 0) { ?>
                                        <tr>
                                        <?php } else { ?>
                                        <tr class="fondo-fila">
                                        <?php } ?>
                                        <td style="text-align: center; font-size: 11px;">
                                            <?php echo $row['fecha_hora_concepto_ddmmaaaa_abrv']; ?><br>
                                            <?php echo $row['hora']; ?>
                                        </td>
                                        <td style="text-align: left;">
                                            <?php echo $row['cliente']; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php if ($row['servicio'] != "") {
                                                echo $row['servicio'];
                                                if ($row['codigo_proveedor'] != "") {
                                                    echo "<br>" . $row['codigo_proveedor'];
                                                }
                                            } ?>
                                            <?php if ($row['producto'] != "") {
                                                echo $row['producto'];
                                                if ($row['cantidad'] > 1) {
                                                    echo "<br>(cantidad: " . $row['cantidad'] . ")";
                                                }
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
                                        </td>
                                        <td style="text-align: left;">
                                            <?php echo $row['empleado']; ?>
                                        </td>
                                        <td class="text-end">
                                            <?php echo number_format($row['importe_total_final'], 2, ',', '.') . "€"; ?>
                                        </td>
                                        <td class="text-end">
                                            <?php echo round($row['templos'], 1); ?>
                                        </td>
                                        <td style="text-align: left;">
                                            <?php echo $row['motivo_devolucion']; ?>
                                        </td>
                                        </tr>
                                        <?php $i++; ?>
                            <?php }
                            }
                        } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p class="sin-datos">No hay datos para esta sección</p>
        <?php }
        } ?>
        <hr>
        <!-- ------------------------------------------------------------------------------ -->
        <!-- --- MOVIMIENTOS DE CAJA ------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------ -->
        <p class="titulo-seccion">
            Movimientos de Caja
        </p>
        <?php if (isset($movimientos_caja)) {
            if ($movimientos_caja != 0) { ?>
                <table style="width: 100%;">
                    <thead class="cabecera-tabla">
                        <tr>
                            <th>Fecha</th>
                            <th>Concepto</th>
                            <th>Importe</th>
                            <th>Empleado</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($movimientos_caja)) {
                            if ($movimientos_caja != 0) {
                                $i = 0;
                                foreach ($movimientos_caja as $key => $row) { ?>
                                    <?php if ($i % 2 == 0) { ?>
                                        <tr>
                                        <?php } else { ?>
                                        <tr class="fondo-fila">
                                        <?php } ?>
                                        <td style="width: 100px; text-align: center;">
                                            <?php echo "<span style='font-size: 0px;'>" . $row['fecha_creacion_aaaammdd_hhss'] . "</span>" . $row['fecha_creacion_ddmmaaaa'] . "<br>" . $row['hora']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['concepto'] ?>
                                        </td>
                                        <td style="width: 100px; text-align: right;">
                                            <?php echo round($row['cantidad'], 2) . " €"; ?>
                                        </td>
                                        <td style="width: 180px; text-align: center;">
                                            <?php echo $row['empleado'] ?>
                                        </td>
                                        </tr>
                                        <?php $i++; ?>
                            <?php }
                            }
                        } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p class="sin-datos">No hay datos para esta sección</p>
        <?php }
        } ?>
        <hr>
        <!-- ------------------------------------------------------------------------------ -->
        <!-- --- DESCUADRES DE CAJA ------------------------------------------------------- -->
        <!-- ------------------------------------------------------------------------------ -->
        <p class="titulo-seccion">
            Descuadres de Caja
        </p>
        <?php if (isset($descuadres_caja)) {
            if ($descuadres_caja != 0) { ?>
                <table style="width: 100%;">
                    <thead class="cabecera-tabla">
                        <tr>
                            <th>Fecha<br>Cierre</th>
                            <th>Saldo<br>Inicial</th>
                            <th>Cierre<br>Efectivo</th>
                            <th>Cierre<br>Tarjeta</th>
                            <?php if ($this->session->userdata('id_centro_usuario') == 9) { ?>
                                <th>Cierre<br>Habitación</th>
                            <?php } ?>
                            <th>Descuadre<br>Efectivo</th>
                            <th>Descuadre<br>Tarjeta</th>
                            <?php if ($this->session->userdata('id_centro_usuario') == 9) { ?>
                                <th>Descuadre<br>Habitación</th>
                            <?php } ?>
                            <th>Efectivo<br>(manual)</th>
                            <th>Tarjeta<br>(manual)</th>
                            <?php if ($this->session->userdata('id_centro_usuario') == 9) { ?>
                                <th>Habitación<br>(manual)</th>
                            <?php } ?>
                            <th>Empleado</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($descuadres_caja)) {
                            if ($descuadres_caja != 0) {
                                $i = 0;
                                foreach ($descuadres_caja as $key => $row) { ?>
                                    <?php $sw_marca = 0; ?>
                                    <?php if (round($row['descuadre_efectivo'], 2) != 0.00) {
                                        $sw_marca = 1;
                                    } ?>
                                    <?php if (round($row['descuadre_tarjeta'], 2) != 0.00) {
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
                                    <?php if ($i % 2 == 0) { ?>
                                        <tr>
                                        <?php } else { ?>
                                        <tr class="fondo-fila">
                                        <?php } ?>
                                        <td style="width: 110px; text-align: center;">
                                            <?php echo $row['fecha_creacion_ddmmaaaa'] . "<br>" . $row['hora']; ?>
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
                                        <?php if ($this->session->userdata('id_centro_usuario') == 9) { ?>
                                            <td class="text-end">
                                                <?php echo round(($row['saldo_cierre_habitacion'] + $row['descuadre_habitacion']), 2) . " €"; ?>
                                            </td>
                                        <?php } ?>
                                        <td style="text-align: center;">
                                            <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                                                <?php echo $row['empleado'] . "<br><span style='font-size: 12px;'>(" . $row['nombre_centro'] . ")</span>"; ?>
                                            <?php } else { ?>
                                                <?php echo $row['empleado']; ?>
                                            <?php } ?>
                                        </td>
                                        </tr>
                                        <?php $i++; ?>
                            <?php }
                            }
                        } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p class="sin-datos">No hay datos para esta sección</p>
        <?php }
        } ?>
        <hr>
        <!-- ------------------------------------------------------------------------------ -->
        <!-- --- LINEAS SIN PRESUPUESTO ---------------------------------------------------- -->
        <!-- ------------------------------------------------------------------------------ -->
        <p class="titulo-seccion">
            Líneas del Dietario sin Presupuesto
        </p>
        <?php if (isset($lineas_sin_presupuesto)) {
            if ($lineas_sin_presupuesto != 0) { ?>
                <table style="width: 100%;">
                    <thead class="cabecera-tabla">
                        <tr>
                            <th>Fecha / Hora</th>
                            <th>Cliente</th>
                            <th>Ser/Prod/Carnet.</th>
                            <th>Empleado</th>
                            <th>Presupuesto</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($lineas_sin_presupuesto)) {
                            if ($lineas_sin_presupuesto != 0) {
                                $i = 0;
                                foreach ($lineas_sin_presupuesto as $key => $row) { 
                                    if($row['estado'] != 'Anulada' && $row['estado'] != 'No vino'){?>
                                        <?=($i % 2 == 0)?'<tr>':'<tr class="fondo-fila">'?>
                                        <td style="text-align: center; font-size: 11px;">
                                            <?php echo $row['fecha_hora_concepto_ddmmaaaa_abrv']; ?><br>
                                            <?php echo $row['hora']; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $row['cliente']; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php if ($row['servicio'] != "") {
                                                echo $row['servicio'];
                                                if ($row['codigo_proveedor'] != "") {
                                                    echo "<br>" . $row['codigo_proveedor'];
                                                }
                                            } ?>
                                            <?php if ($row['producto'] != "") {
                                                echo $row['producto'];
                                                if ($row['cantidad'] > 1) {
                                                    echo "<br>(cantidad: " . $row['cantidad'] . ")";
                                                }
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
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $row['empleado']; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $row['nro_presupuesto']; ?>
                                        </td>
                                        </tr>
                                        <?php $i++; ?>
                                    <?php }
                                }
                            }
                        } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p class="sin-datos">No hay datos para esta sección</p>
        <?php }
        } ?>
        <hr>
        <!-- ------------------------------------------------------------------------------ -->
        <!-- --- PRESUPUESTOS ------------------------------------------------------------- -->
        <!-- ------------------------------------------------------------------------------ -->
        <p class="titulo-seccion">
            Presupuestos
        </p>
        <table style="width: 100%;">
            <thead class="cabecera-tabla">
                <tr>
                    <th colspan="2">Presupuestos creados</th>
                    <th colspan="2">Presupuestos aceptados</th>
                    <th colspan="2">Presupuestos De Repetición</th>
                </tr>
                <tr>
                    <th>Cantidad</th>
                    <th>Valor (€)</th>
                    <th>Cantidad</th>
                    <th>Valor (€)</th>
                    <th>Cantidad</th>
                    <th>Valor (€)</th>
                </tr>
                
            </thead>
            <tbody class="text-gray-700 fw-semibold">
                <tr class="fondo-fila">
                    <td style="text-align: center; font-size: 11px;">
                        <?php echo $nro_pres_creados; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php echo number_format($valor_pres_creados, 2, ",", ".") . " €"; ?>
                    </td>
                    <td style="text-align: center; font-size: 11px;">
                        <?php echo $nro_pres_aceptados; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php echo number_format($valor_pres_aceptados, 2, ",", ".") . " €"; ?>
                    </td>
                    <td style="text-align: center; font-size: 11px;">
                        <?php echo $nro_pres_repet; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php echo number_format($valor_pres_repet, 2, ",", ".") . " €"; ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <hr>
        
        <?php if (!empty($presupuestos_aseguradoras)){ ?>
        <p class="titulo-seccion">
            Presupuestos con aseguradoras
        </p>
        <table style="width: 100%;">
            <thead class="cabecera-tabla">
                <tr>
                    <th>Número presupuesto</th>
                    <th>Cliente</th>
                    <th>Aseguradora</th>
                    <th>Tarjeta paciente</th>
                    <th>Presupuesto aseguradora</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 fw-semibold">
                <?php foreach ( $presupuestos_aseguradoras as $presupuesto_aseguradoras ){ ?>
                <tr class="fondo-fila">
                    <td style="text-align: center; font-size: 11px;">
                        <?php echo $presupuesto_aseguradoras['nro_presupuesto']; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php echo $presupuesto_aseguradoras['cliente']; ?>
                    </td>
                    <td style="text-align: center; font-size: 11px;">
                        <?php echo $presupuesto_aseguradoras['nombre_aseguradora']; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php if (isset($presupuesto_aseguradoras['documentos_seguro'][0]['file_tarjeta'])) { ?>
                        <a href="<?php echo base_url($presupuesto_aseguradoras['documentos_seguro'][0]['file_tarjeta']); ?>" download>Descargar</a>
                        <?php } ?>
                    </td>
                    <td style="text-align: center;">
                        <?php if (isset($presupuesto_aseguradoras['documentos_seguro'][0]['file_presupuesto'])) { ?>
                        <a href="<?php echo base_url($presupuesto_aseguradoras['documentos_seguro'][0]['file_presupuesto']); ?>" download>Descargar</a>
                        <?php } ?>    
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <hr>
        <?php } ?>
        
        <!-- ------------------------------------------------------------------------------ -->
        <!-- --- PRODUCCION ------------------------------------------------------------- -->
        <!-- ------------------------------------------------------------------------------ -->
        <p class="titulo-seccion">
            Produccion en citas
        </p>
        <table style="width: 100%;">
            <thead class="cabecera-tabla">
                <tr>
                    <th>Total citas</th>
                    <th>PVP citas (€)</th>
                    <th>Descuento aplicado (€)</th>
                    <th>Importe cobrado (€)</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 fw-semibold">
                <tr class="fondo-fila">
                    <td style="text-align: center; font-size: 11px;">
                        <?php echo $nro_citas_produccion; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php echo number_format($pvp_citas_produccion, 2, ",", ".") . " €"; ?>
                    </td>
                    <td style="text-align: center; font-size: 11px;">
                        <?php echo number_format($dto_citas_produccion, 2, ",", ".") . " €"; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php echo number_format($cobrado_citas_produccion, 2, ",", ".") . " €"; ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <hr>

        <!-- ------------------------------------------------------------------------------ -->
        <!-- --- CITAS DOCTORES ----------------------------------------------------------- -->
        <!-- ------------------------------------------------------------------------------ -->
        <p class="titulo-seccion">
            Citas de doctores
        </p>
        <?php if (isset($doctores) && !empty($doctores)) { ?>
            <table style="width: 100%;">
                <thead class="cabecera-tabla">
                    <tr>
                        <th>Empleado</th>
                        <th>Nº citas</th>
                        <th>Importe cobrado (€)</th>
                        <th>Pacientes únicos</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php foreach ($doctores as $key => $value) { ?>
                        <tr class="fondo-fila">
                            <td style="text-align: center; font-size: 11px;">
                                <?php echo strtoupper($value['empleado']); ?>
                            </td>
                            <td style="text-align: center;">
                                <?php echo count($value['citas']);?>
                            </td>
                            <td style="text-align: center; font-size: 11px;">
                                <?php echo number_format(array_sum($value['citas']), 2, ",", ".") . " €"; ?>
                            </td>
                            <td style="text-align: center;">
                                <?php echo count($value['pacientes']); ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else{ ?>
            <p class="sin-datos">No hay datos para esta sección</p>
        <?php } ?>
        <hr>

        <!-- ------------------------------------------------------------------------------ -->
        <!-- --- CITAS ANULADAS O NO VINO ------------------------------------------------- -->
        <!-- ------------------------------------------------------------------------------ -->
        <p class="titulo-seccion">
            Citas Anuladas o No Vino
        </p>
        <?php if (isset($anuladas_no_vino)) {
            if ($anuladas_no_vino != 0) { ?>
                <table style="width: 100%;">
                    <thead class="cabecera-tabla">
                        <tr>
                            <th>Fecha / Hora</th>
                            <th>Cliente</th>
                            <th>Servicio</th>
                            <th>Empleado</th>
                            <th>Importe</th>
                            <th>Notas</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($anuladas_no_vino)) {
                            if ($anuladas_no_vino != 0) {
                                $i = 0;
                                foreach ($anuladas_no_vino as $key => $row) { ?>
                                    <?php if ($i % 2 == 0) { ?>
                                        <tr>
                                        <?php } else { ?>
                                        <tr class="fondo-fila">
                                        <?php } ?>
                                        <td style="text-align: center; font-size: 11px;">
                                            <?php echo $row['fecha_hora_concepto_ddmmaaaa_abrv']; ?><br>
                                            <?php echo $row['hora']; ?>
                                        </td>
                                        <td style="text-align: left;">
                                            <?php echo $row['cliente']; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php if ($row['servicio'] != "") {
                                                echo $row['servicio'];
                                                if ($row['codigo_proveedor'] != "") {
                                                    echo "<br>" . $row['codigo_proveedor'];
                                                }
                                            } ?>
                                            <?php if ($row['producto'] != "") {
                                                echo $row['producto'];
                                                if ($row['cantidad'] > 1) {
                                                    echo "<br>(cantidad: " . $row['cantidad'] . ")";
                                                }
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
                                        </td>
                                        <td style="text-align: left;">
                                            <?php echo $row['empleado']; ?>
                                        </td>
                                        <td class="text-end">
                                            <?php echo number_format($row['importe_total_final'], 2, ',', '.') . "€"; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <div style="text-transform: uppercase;">
                                                <?php echo $row['estado']; ?>
                                            </div>
                                            <div style="text-transform: lowercase;">
                                                <?php echo $row['observaciones']; ?>
                                            </div>
                                        </td>
                                        </tr>
                                        <?php $i++; ?>
                            <?php }
                            }
                        } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p class="sin-datos">No hay datos para esta sección</p>
        <?php }
        } ?>
        <hr>
        

        <!-- ------------------------------------------------------------------------------ -->
        <!-- --------- REGISTROS EN CAJA CON NOTAS DE COBRO PENDIENTES   ------------------ -->
        <!-- ------------------------------------------------------------------------------ -->
        <p class="titulo-seccion">
            Registros en caja con NOTAS DE COBRO PENDIENTES
        </p>
        <?php if (isset($caja_notas_pago)) {
            if ($caja_notas_pago != 0 && !empty($caja_notas_pago)) { ?>
                <table style="width: 100%;">
                    <thead class="cabecera-tabla">
                        <tr>
                            <th>Fecha / Hora</th>
                            <th>Cliente</th>
                            <th>Empleado</th>
                            <th>Tipo de pago</th>
                            <th>Carnet</th>
                            <th>Nota</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($caja_notas_pago)) {
                            if ($caja_notas_pago != 0) {
                                $i = 0;
                                foreach ($caja_notas_pago as $key => $row) { ?>
                                    <?php if ($i % 2 == 0) { ?>
                                        <tr>
                                        <?php } else { ?>
                                        <tr class="fondo-fila">
                                        <?php } ?>
                                        <td style="text-align: left; vertical-align: top">
                                            <?php echo date('d-m-Y H:i:s', strtotime($row['fecha_pagado'])); ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: top">
                                            <?php echo $row['cliente']; ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: top">
                                            <?php echo $row['empleado']; ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: top">
                                            <?php echo $row['tipo_pago']; ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: top">
                                            <?php echo $row['id_carnet']; ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: top">
                                            <?php echo $row['nota']; ?>
                                        </td>
                                        </tr>
                                        <?php $i++; ?>
                            <?php }
                            }
                        } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p class="sin-datos">No hay datos para esta sección</p>
        <?php }
        } ?>
    </div>

</body>

</html>