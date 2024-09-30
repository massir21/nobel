<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">Recibo</h1>
    <div class="card card-flush m-5">
        <div class="card-body p-5">
            <h3 class="text-center"><?php echo $cliente[0]['nombre'] . " " . $cliente[0]['apellidos']; ?></h3>
            <div class="alert alert-primary">Comprueba los datos del cliente en su ficha antes de hacer el recibo</div>
            <form name="form" id="form" action="<?php echo base_url(); ?>dietario/recibo_crear/<?php echo $cliente[0]['id_cliente'] ?>/<?php echo $id_centro_facturar ?>" method="POST">
                <div class="table-responsive">
                    <table id="conceptos" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                        <thead class="">
                            <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                                <th style="display: none;">ID</th>
                                <th></th>
                                <th>Fecha</th>
                                <th>Emp.</th>
                                <th>Serv/Prod</th>
                                <th>Euros</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 fw-semibold">
                            <?php
                            $total_importe = 0;
                            $total_templos = 0;
                            $concepto = "";
                            $sw_citas = 0;
                            $sw_citas_online = 0;
                            if (isset($registros)) {
                                if ($registros != 0) {
                                    foreach ($registros as $key => $row) {
                                        $importe_pagado = $row['importe_euros'] - $row['descuento_euros'] - ($row['importe_euros'] * $row['descuento_porcentaje'] / 100);
                            ?>
                                        <tr style="background: <?php echo $row['color_estado']; ?>;">
                                            <td style="display: none;"><?php echo $row['id_dietario']; ?></td>
                                            <td>
                                                <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                                                    <input class="form-check-input" type="checkbox" name="marcar[]" value="<?php echo $row['id_dietario']; ?>" onchange="desbloquearCampos(this)"/>
                                                </div>
                                            </td>
                                            <td><?= ($row['hora'] != "") ? $row['fecha_hora_concepto_ddmmaaaa_abrv'] . "<br>" . $row['hora'] : "-" ?></td>
                                            <td><?php echo $row['empleado']; ?></td>
                                            <td>
                                                <?php if ($row['servicio'] != "") {
                                                    echo $row['servicio'];
                                                    $concepto = $row['servicio'];
                                                } ?>
                                                <?php if ($row['producto'] != "") {
                                                    echo $row['producto'];
                                                    $concepto = $row['producto'];
                                                    if ($row['cantidad'] > 1) {
                                                        echo "<br>(cantidad: " . $row['cantidad'] . ")";
                                                    }
                                                } ?>

                                                <?php if ($row['id_presupuesto'] != "") {
                                                    echo $row['nro_presupuesto'];
                                                    $concepto = $row['nro_presupuesto'];
                                                } ?>

                                                <?php if ($row['carnet'] != "") {
                                                    echo $row['carnet'];
                                                    $concepto = $row['carnet'];
                                                    if ($row['recarga'] == 1) {
                                                        echo " (Recarga)";
                                                    }
                                                    if ($row['codigo_pack_online'] != "") {
                                                        echo "<br>(Pack-online: " . $row['codigo_pack_online'] . ")";
                                                    }
                                                } ?>
                                                <?php if ($row['pago_a_cuenta'] == 1) {
                                                    echo "Pago a cuenta"; ?>
                                                    <button type="button" class="btn btn-icon btn-outline btn-outline-dark btn-sm blocked" data-bs-toggle="tooltip" title="Asignar a presupuesto" onClick="mostrar_modal_presupuesto(<?php echo $row['id_dietario']; ?>, <?= $importe_pagado ?>)" disabled><i class="fa-2x fa-file-pdf fas"></i></button>
                                                    
                                                <?php } ?>
                                                <input type="hidden" name="presupuestosrelacionados[]" id="presupuestosrel_<?php echo $row['id_dietario']; ?>" class="blocked" disabled>
                                            </td>
                                            <td class="text-end">
                                                <?php if ($row['id_tipo'] != "") { ?>
                                                    <?php if ($row['id_tipo'] == 99) {
                                                        $row['importe_euros'] = $row['pvp_carnet'];
                                                    } ?>
                                                    <?php if ($row['id_tipo'] < 99) {
                                                        if ($row['recarga'] == 0) {
                                                            $row['importe_euros'] = $row['pvp_carnet'];
                                                        }
                                                    }
                                                    ?>
                                                <?php } ?>

                                                <?php

                                                if ($importe_pagado != 0) { ?>
                                                    <?php echo number_format($importe_pagado, 2, ",", ".") . '€';
                                                    if ($importe_pagado != 0) {
                                                        $total_importe += $importe_pagado;
                                                    } ?>
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                                <span class="small text-muted d-block" id="presupuestosreltext_<?php echo $row['id_dietario']; ?>"></span>
                                            </td>
                                            <td class="text-uppercase"><?php echo $row['estado']; ?></td>

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
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <?php $color = "warning";
                $xdato = "Completos";
                $xestado = "";
                if ($cliente[0]['empresa'] == "" or $cliente[0]['cif_nif'] == "" or $cliente[0]['direccion_facturacion'] == "" or $cliente[0]['codigo_postal_facturacion'] == "" or $cliente[0]['localidad_facturacion'] == "" or $cliente[0]['provincia_facturacion'] == "") {
                    $color = "warning";
                    $xdato = "Incompletos";
                    $xestado = "disabled";
                } ?>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-sm btn-secondary text-inverse-secondary" type="button" onclick="Cerrar();">Cancelar</button>
                        <?php if (isset($registros) && $registros != 0) { ?>
                            <button class="btn btn-sm btn-info text-inverse-info" type="button" onclick="DatosFacturacion();">Datos del cliente <?php echo $xdato; ?></button>
                            <button class="btn btn-sm btn-primary text-inverse-primary" type="button" onclick="GenerarRecibo();" <?php echo $xestado; ?>>Generar Recibo de los Conceptos Marcados</button>
                        <?php } ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modal-presupuestos" tabindex="-1" aria-labelledby="modal-presupuestosLabel" aria-hidden="true" data-focus-on="input:first">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title w-100 text-center text-uppercase" id="presupuestosLabel">Repartir </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <form>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Presupuesto</th>
                                        <th>Total</th>
                                        <th>Asignar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($presupuestos_cliente as $p => $presupuesto) { ?>
                                        <tr>
                                            <td><?= $presupuesto['nro_presupuesto'] ?></td>
                                            <td><?= $presupuesto['total_aceptado'] ?></td>
                                            <td>
                                                <input type="number" class="form-control border border-gray-300 text-end asignar" min="0" max="<?= $presupuesto['total_aceptado'] ?>" name="asignar[]" onchange="verificarSuma(this)" onkeyup="actualizarSuma()">
                                            </td>
                                            <input type="hidden" name="id_presupuesto[]" value="<?=$presupuesto['id_presupuesto']?>">
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <input type="hidden" name="id_dietario" id="id_dietario_modal" value="">
                            <input type="hidden" name="maxlinea" id="maxlinea" value="">
                        </form>
                    </div>
                </div>
                <div class="modal-footer p-1">
                    <button type="button" class="btn btn-sm btn-warning text-inverse-warning" onclick="guardarAsignaciones()" id="asignar" disabled>Relacionar presupuestos</button>
                    <button type="button" class="btn btn-sm btn-secondary text-inverse-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function desbloquearCampos(checkbox) {
            var fila = $(checkbox).closest('tr');
            fila.find('.blocked').prop('disabled', !checkbox.checked);
        }

        function mostrar_modal_presupuesto(id_dietario, max) {
            $('#modal-presupuestos').find('[name="id_dietario"]').val(id_dietario)
            $('#modal-presupuestos').find('[name="maxlinea"]').val(max)
            $('#presupuestosLabel').html('Repartir los ' + parseFloat(max) + '€ entre los presupuestos disponibles')
            $('#modal-presupuestos').find('[name="asignar[]"]').val(0)
            $('#modal-presupuestos').modal('show');
        }

        function actualizarSuma() {
            var maxLinea = parseFloat(document.getElementById('maxlinea').value);
            var btn_asignar = document.getElementById('asignar');
            var suma = 0;
            var asignarInputs = document.getElementsByClassName('asignar');
            for (var i = 0; i < asignarInputs.length; i++) {
                suma += parseFloat(asignarInputs[i].value) || 0;
            }

            if (suma > maxLinea) {
                Swal.fire('La suma de los valores excede el valor del pago');
                for (var i = 0; i < asignarInputs.length; i++) {
                    asignarInputs[i].value = asignarInputs[i].getAttribute('data-valor-anterior');
                }
            } else {
                for (var i = 0; i < asignarInputs.length; i++) {
                    asignarInputs[i].setAttribute('data-valor-anterior', asignarInputs[i].value);
                }
                btn_asignar.disabled = suma !== maxLinea;
            }
        }

        function guardarAsignaciones() {
            var asignaciones = [];
            var asignarInputs = document.getElementsByClassName('asignar');
            var idPresupuestoInputs = document.getElementsByName('id_presupuesto[]');
            for (var i = 0; i < asignarInputs.length; i++) {
                var asignarValue = parseFloat(asignarInputs[i].value) || 0;
                if (asignarValue > 0) {
                    var idPresupuestoValue = parseFloat(idPresupuestoInputs[i].value);
                    var asignacion = {};
                    asignacion[idPresupuestoValue] = asignarValue;
                    asignaciones.push(asignacion);
                }
            }
            var asignacionesFormateadas = asignaciones.map(obj => 'Presupuesto: ' + Object.keys(obj)[0] + ' => ' + obj[Object.keys(obj)[0]] + ' €').join('<br>');
            var stAsignaciones =  asignaciones.map(obj => Object.keys(obj)[0] + ':' + obj[Object.keys(obj)[0]]).join('|');
            var idDietario = document.getElementById('id_dietario_modal').value;

            document.getElementById('presupuestosrel_'+idDietario).value = stAsignaciones;
            document.getElementById('presupuestosreltext_'+idDietario).innerHTML = asignacionesFormateadas;
            $('#modal-presupuestos').modal('hide');
        }

        function verificarSuma(input) {
            input.setAttribute('data-valor-anterior', input.value);
            actualizarSuma();
        }

        function Cerrar() {
            window.opener.location.reload();
            window.close();
        }
        function stripHtml(html) {
            var doc = new DOMParser().parseFromString(html, 'text/html');
            return doc.body.textContent || "";
        }
        function GenerarRecibo() {
            var rows = document.getElementsByName('marcar[]');
            var des = document.getElementById("conceptos");
            var selectedRows = [];
            var mensaje = "<hr>";
            for (var i = 0, l = rows.length; i < l; i++) {
                if (rows[i].checked) {
                    selectedRows.push(rows[i]);
                    precio = des.rows[i + 1].cells[5].innerHTML;
                    //precio = precio.replace("<br>", " ");
                    precio = precio.trim();
                    //precio = precio.replace(/^\s+|\s+$/g, "");
                    celda = des.rows[i + 1].cells[4].innerHTML;
                    celda = stripHtml(celda);
                    celda = celda.replace("<br>", " ");
                    celda = celda.replace("&nbsp;", "");
                    celda = celda.trim();
                    celda = celda.replace(/^\s+|\s+$/g, "");
                    mensaje += celda + ": "+ precio + "<hr>\n";
                }
            }
            if (selectedRows.length > 0) {
                Swal.fire({
                    title: '¿DESEA GENERAR EL RECIBO CON LOS CONCEPTOS SELECCIONADOS?',
                    html: mensaje,
                    showCancelButton: true,
                    confirmButtonText: 'Si, generar',
                    showLoaderOnConfirm: true,
                    onBeforeOpen: () => {
                        location.reload();
                    },

                }).then((result) => {
                    if (result.value) {
                        document.form.submit();
                    }
                })

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'DEBE DE MARCAR AL MENOS UN CONCEPTO PARA GENERAR EL RECIBO',
                })
            }
        }

        function DatosFacturacion() {
            var url = "<?php echo base_url(); ?>dietario/datos_facturacion/<?php echo $cliente[0]['id_cliente']; ?>?recibo=1";
            openwindow('datos_facturacion', url, 600, 500);
        }
    </script>

</body>

</html>