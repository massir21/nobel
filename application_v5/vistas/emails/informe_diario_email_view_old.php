<!doctype html>
<html lang="es">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <style>
        body {
            font-family: Inter, Helvetica,"sans-serif";
            font-size: 12px;
        }

        table {
            font-family: Inter, Helvetica,"sans-serif";
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
        <p class="titulo-seccion">
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
        <!-- ------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------ -->
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
        <!-- ------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------ -->
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
        <!-- ------------------------------------------------------------------------------ -->
        <!-- --------- RECARGAS MISMO CLIENTE, DÍA Y MISMO MONTO   ------------------ -->
        <!-- ------------------------------------------------------------------------------ -->
        <p class="titulo-seccion">
            Recargas mismo Cliente y misma cantidad.
        </p>
        <?php if (isset($recarga_carnet)) {
            if ($recarga_carnet != 0) { ?>
                <table style="width: 100%;">
                    <thead class="cabecera-tabla">
                        <tr>
                            <th>Fecha / Hora</th>
                            <th>Id Carnet</th>
                            <th>Cliente</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($recarga_carnet)) {
                            if ($recarga_carnet != 0) {
                                $i = 0;
                                foreach ($recarga_carnet as $key => $row) { ?>
                                    <?php if ($i % 2 == 0) { ?>
                                        <tr>
                                        <?php } else { ?>
                                        <tr class="fondo-fila">
                                        <?php } ?>
                                        <td style="text-align: left; vertical-align: top">
                                            <?php echo $row['fecha_hora']; ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: top">
                                            <?php echo $row['id_carnet']; ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: top">
                                            <?php echo $row['cliente']; ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: top">
                                            <?php echo $row['monto']; ?>
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
        <!-- ------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------ -->
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
        <!-- ------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------ -->
        <p class="titulo-seccion">
            Servicios pagados con un carnet de un cliente diferente al de la cita
        </p>
        <?php if (isset($servicios_otros_carnets)) {
            if ($servicios_otros_carnets != 0) { ?>
                <table style="width: 100%;">
                    <thead class="cabecera-tabla">
                        <tr>
                            <th>Fecha / Hora</th>
                            <th>Cliente</th>
                            <th>Servicio</th>
                            <th>Empleado</th>
                            <th>Templos</th>
                            <th>Propietario Carnet</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($servicios_otros_carnets)) {
                            if ($servicios_otros_carnets != 0) {
                                $i = 0;
                                foreach ($servicios_otros_carnets as $key => $row) { ?>
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
                                            <?php echo number_format($row['templos'], 2, ',', '.'); ?>
                                        </td>
                                        <td style="text-align: left; text-transform: uppercase;">
                                            <?php echo $row['propietario_carnet']; ?>
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
        <!-- ------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------ -->
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
        <!-- ------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------ -->
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
        <!-- ------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------ -->
        <p class="titulo-seccion">
            Cambio en número de carnets realizados por el Máster
        </p>
        <?php if (isset($cambios_carnets)) {
            if ($cambios_carnets != 0) { ?>
                <table style="width: 100%;">
                    <thead class="cabecera-tabla">
                        <tr>
                            <th style="width: 50%;">Código Nuevo</th>
                            <th style="width: 50%;">Código Anterior</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($cambios_carnets)) {
                            if ($cambios_carnets != 0) {
                                $i = 0;
                                foreach ($cambios_carnets as $key => $row) { ?>
                                    <?php if ($i % 2 == 0) { ?>
                                        <tr>
                                        <?php } else { ?>
                                        <tr class="fondo-fila">
                                        <?php } ?>
                                        <td style="text-align: center;">
                                            <?php echo $row['codigo_nuevo'] ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $row['codigo_anterior'] ?>
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
        <!-- ------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------ -->
        <p class="titulo-seccion">
            Carnets sin pasar por caja que han sido creados
        </p>
        <?php if (isset($carnets_sin_pasar_caja)) {
            if ($carnets_sin_pasar_caja != 0) { ?>
                <table style="width: 100%;">
                    <thead class="cabecera-tabla">
                        <tr>
                            <th>Empleado</th>
                            <th>Nº Carnet</th>
                            <th>Tipo</th>
                            <th>Templos / Servicios</th>
                            <th>Cliente</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($carnets_sin_pasar_caja)) {
                            if ($carnets_sin_pasar_caja != 0) {
                                $i = 0;
                                foreach ($carnets_sin_pasar_caja as $key => $row) { ?>
                                    <?php if ($i % 2 == 0) { ?>
                                        <tr>
                                        <?php } else { ?>
                                        <tr class="fondo-fila">
                                        <?php } ?>
                                        <td style="text-align: left; vertical-align: top">
                                            <?php echo $row['empleado'] ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: top">
                                            <?php echo $row['codigo'] ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: top">
                                            <?php echo $row['tipo'] ?>
                                        </td>
                                        <td style="text-align: left; vertical-align: top">
                                            <?php
                                            if (isset($row['servicios'])) {
                                                echo $row['servicios'];
                                            } else {
                                                echo "<center>" . round($row['templos'], 1) . "</center>";
                                            }
                                            ?>
                                        </td>
                                        <td style="text-align: left; vertical-align: top">
                                            <?php echo $row['cliente'] ?>
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
        <!-- ------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------ -->
        <p class="titulo-seccion">
            Ajuste de templos en carnets sin pasar por caja
        </p>
        <?php if (isset($carnets_ajustes_templos)) {
            if ($carnets_ajustes_templos != 0) { ?>
                <table style="width: 100%;">
                    <thead class="cabecera-tabla">
                        <tr>
                            <th>Empleado</th>
                            <th>Nº Carnet</th>
                            <th>Recarga</th>
                            <th>Cliente</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($carnets_ajustes_templos)) {
                            if ($carnets_ajustes_templos != 0) {
                                $i = 0;
                                foreach ($carnets_ajustes_templos as $key => $row) { ?>
                                    <?php if ($i % 2 == 0) { ?>
                                        <tr>
                                        <?php } else { ?>
                                        <tr class="fondo-fila">
                                        <?php } ?>
                                        <td style="text-align: left; vertical-align: top">
                                            <?php echo $row['empleado'] ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: top">
                                            <?php echo $row['codigo'] ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: top">
                                            <?php echo round($row['recarga'], 1) ?>
                                        </td>
                                        <td style="text-align: left; vertical-align: top">
                                            <?php echo $row['cliente'] ?>
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
        <!-- ------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------ -->
        <p class="titulo-seccion">
            Servicios añadidos a carnets sin pasar por caja
        </p>
        <?php if (isset($carnets_ajustes_servicios)) {
            if ($carnets_ajustes_servicios != 0) { ?>
                <table style="width: 100%;">
                    <thead class="cabecera-tabla">
                        <tr>
                            <th>Empleado</th>
                            <th>Nº Carnet</th>
                            <th>Servicio</th>
                            <th>Cliente</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($carnets_ajustes_servicios)) {
                            if ($carnets_ajustes_servicios != 0) {
                                $i = 0;
                                foreach ($carnets_ajustes_servicios as $key => $row) { ?>
                                    <?php if ($i % 2 == 0) { ?>
                                        <tr>
                                        <?php } else { ?>
                                        <tr class="fondo-fila">
                                        <?php } ?>
                                        <td style="text-align: left; vertical-align: top">
                                            <?php echo $row['empleado'] ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: top">
                                            <?php echo $row['codigo'] ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: top">
                                            <?php echo $row['nombre_servicio'] ?>
                                        </td>
                                        <td style="text-align: left; vertical-align: top">
                                            <?php echo $row['cliente'] ?>
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
        <!-- ------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------ -->
        <p class="titulo-seccion">
            Cambios de Saldo de forma manual
        </p>
        <?php if (isset($cambios_saldo_manual)) {
            if ($cambios_saldo_manual != 0) { ?>
                <table style="width: 100%;">
                    <thead class="cabecera-tabla">
                        <tr>
                            <th>Empleado</th>
                            <th>Saldo Anterior</th>
                            <th>Saldo Nuevo</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($cambios_saldo_manual)) {
                            if ($cambios_saldo_manual != 0) {
                                $i = 0;
                                foreach ($cambios_saldo_manual as $key => $row) { ?>
                                    <?php if ($i % 2 == 0) { ?>
                                        <tr>
                                        <?php } else { ?>
                                        <tr class="fondo-fila">
                                        <?php } ?>
                                        <td style="text-align: left; vertical-align: top">
                                            <?php echo $row['empleado'] ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: top">
                                            <?php echo number_format($row['saldo_inicial_anterior'], 2, ',', '.') . "€"; ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: top">
                                            <?php echo number_format($row['saldo_inicial_nuevo'], 2, ',', '.') . "€"; ?>
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
        <!-- ------------------------------------------------------------------------------ -->
        <!-- --------- REGISTROS EN CAJA CON NOTAS DE COBRO PENDIENTES   ------------------ -->
        <!-- ------------------------------------------------------------------------------ -->
        <p class="titulo-seccion">
            Registros en caja con NOTAS DE COBRO PENDIENTES
        </p>
        <?php if (isset($caja_notas_pago)) {
            if ($caja_notas_pago != 0) { ?>
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
    <!-- Anexo -->
    <div class="row">
        <div class="col-md-12">
            <h4><b>Facturación</b></h4>

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
                                <?php if ($id_centro == 9) { ?>
                                    <th>Habitación</th>
                                <?php } ?>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
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
                            </tr>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <div style="color: red;">Sin datos</div>
            <?php }
            } ?>

        </div>
    </div> <!-- row -->
    <!-- Fin Anexo -->
</body>

</html>