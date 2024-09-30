<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">Ficha <?php echo $cliente[0]['nombre'] . " " . $cliente[0]['apellidos']; ?> (<?php echo $fecha_completa; ?>)</h1>
    <div class="card card-flush m-5">
        <div class="card-body p-5">
            <div class="d-flex align-items-end py-5 gap-2 gap-md-5 justify-content-center mb-5">
                <button class="btn btn-primary text-inverse-primary" onclick="NuevoServicio();">Servicio</button>
                <button class="btn btn-info text-inverse-info" onclick="NuevoProducto();">Producto</button>
                <!--button class="btn btn-warning text-inverse-warning ms-3" onclick="CarnetUnico();">Carnet Único</button>
                <button class="btn btn-success text-inverse-success ms-3" onclick="NuevoCarnet();">Carnet</button-->
            </div>
            <div class="table-responsive border p-4 mb-5">
                <table id="myTable1" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                    <thead class="">
                        <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                            <th style="display: none;">ID</th>
                            <th>Fecha</th>
                            <th>Emp.</th>
                            <th>Serv/Prod/Carnet</th>
                            <th>Euros</th>
                            <th>Templos</th>
                            <th>Estado</th>
                            <th></th>
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
                                foreach ($registros as $key => $row) { ?>
                                    <tr>
                                        <td style="display: none;"><?php echo $row['id_dietario']; ?></td>
                                        <td>
                                            <?php if ($row['hora'] != "") {
                                                echo $row['fecha_hora_concepto_ddmmaaaa_abrv'] . "<br>" . $row['hora'];
                                            } else {
                                                echo "-";
                                            } ?>
                                        </td>
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
                                            <?php if ($row['importe_euros'] > 0) { ?>
                                                <?php echo number_format($row['importe_euros'], 2, ",", ".") . '€';
                                                if ($row['importe_euros'] > 0) {
                                                    $total_importe += $row['importe_euros'];
                                                } ?>
                                            <?php } else { ?>
                                                -
                                            <?php } ?>
                                        </td>
                                        <td class="text-end">
                                            <?php if ($row['templos'] > 0) { ?>
                                                <?php echo round($row['templos'], 2);
                                                if ($row['templos'] > 0) {
                                                    $total_templos += $row['templos'];
                                                } ?>
                                            <?php } else { ?>
                                                -
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php echo $row['estado']; ?>
                                        </td>
                                        <td>
                                            <?php if ($this->session->userdata('id_perfil') != 1 && $this->session->userdata('id_perfil') != 2) { ?>
                                                <?php if ($row['id_pedido'] == 0) {
                                                    $sw_citas = 1; ?>
                                                    <button class="btn btn-sm btn-icon btn-danger" onclick="Eliminar('<?php echo $row['id_dietario']; ?>','<?php echo addslashes($concepto); ?>');" data-bs-toggle="tooltip" title="Borrar"><i class="fa-solid fa-trash"></i></button>
                                                <?php } ?>
                                                <?php if ($row['id_pedido'] > 0) {
                                                    $sw_citas_online = 1; ?>
                                                    <strong>ON-LINE</strong>
                                                    <?php if ($row['tipo_pago'] == "#templos" && isset($row['carnets_pagos'][0]['codigo'])) {
                                                        echo "<br>"; ?>
                                                        <?php foreach ($row['carnets_pagos'] as $dato) { ?>
                                                            <a href="#" onclick="VerCarnetsPagos(<?php echo $dato['id_carnet'] ?>);"><b><?php echo $dato['codigo'] ?></b></a>
                                                            &nbsp;
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>

                                            <?php } else { ?>

                                                <?php if ($row['id_pedido'] == 0) {
                                                    $sw_citas = 1; ?>
                                                    -
                                                <?php } ?>
                                                <?php if ($row['id_pedido'] > 0) {
                                                    $sw_citas_online = 1; ?>
                                                    <strong>ON-LINE aa</strong>
                                                    <?php if ($row['tipo_pago'] == "#templos" && isset($row['carnets_pagos'][0]['codigo'])) { ?>
                                                        <?php foreach ($row['carnets_pagos'] as $dato) { ?>
                                                            |
                                                            <a href="#" onclick="VerCarnetsPagos(<?php echo $dato['id_carnet'] ?>);"><b><?php echo $dato['codigo'] ?></b></a>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>

                                            <?php } ?>
                                        </td>
                                    </tr>
                        <?php }
                            }
                        } else {
                            echo '<tr><td colspan="7" class="text-center">No hay ningún concepto pendiente de pago</td></tr>';
                        } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right; padding: 8px;"><b>TOTALES</b></td>
                            <td style="text-align: right; padding: 8px;"><?php echo number_format($total_importe, 2, ",", ".") . '€'; ?></td>
                            <td style="text-align: right; padding: 8px;"><?php echo round($total_templos, 2); ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <?php if (isset($notas_cobrar)) {
                if ($notas_cobrar != 0) { ?>
                    <div class="table-responsive border p-4 mb-5">
                        <table id="tabla_notas" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                            <thead class="">
                                <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                                    <th style="display: none;"></th>
                                    <th style="width: 1%; border: 0px;">Finalizar</th>
                                    <th style="border: 0px;">NOTAS CLIENTE PARA SUS COBROS</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 fw-semibold">
                                <?php if (isset($notas_cobrar)) {
                                    if ($notas_cobrar != 0) {
                                        foreach ($notas_cobrar as $key => $row) { ?>
                                            <tr style="background: #feeea3; border: 0px;">
                                                <td style="display: none;">
                                                    <?php echo $row['fecha_creacion_aaaammdd'] ?>
                                                </td>
                                                <td style="text-align: center; border: 0px; padding: 4px;" onclick="FinalizarNota(this,'<?php echo $row['id_nota_cobrar'] ?>');">
                                                    <input type="checkbox" id="id_nota_cobrar" value="<?php echo $row['id_nota_cobrar'] ?>" class="form-control form-control-solid" />
                                                </td>
                                                <td style="border: 0px; padding: 4px;">
                                                    <?php echo $row['fecha_creacion_ddmmaaaa'] ?><br>
                                                    <a href="#" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="<?php echo $row['nota'] ?>" style="color: blue;">
                                                        <?php echo substr($row['nota'], 0, 85); ?> ...
                                                    </a>
                                                </td>
                                            </tr>
                                <?php }
                                    }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                    <div id="borrar_nota" style="display: none;"></div>
            <?php }
            } ?>

            <div class="row">
                <?php if ($saldo_cliente > 0) { ?>
                            <div class="alert alert-primary d-flex p-5">
                                <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center text-uppercase">SALDO DISPONIBLE: <?php echo number_format($saldo_cliente, 2, ',', '.') . "€"; ?></div>
                            </div>
                        <?php } ?>
                <div class="col-md-12 text-center">
                    <button class="btn btn-sm btn-secondary text-inverse-secondary m-2" type="button" onclick="Cerrar();">Cerrar sin Cambios</button>
                    <?php if (isset($registros) && $registros != 0) {
                        if ($sw_citas == 1) { ?>
                            <button type="button" class="btn btn-sm btn-info text-inverse-info" onclick="PagoEfectivo();">Efectivo o Tarjeta</button>
                            <!--button type="button" class="btn btn-sm btn-success text-inverse-success" onclick="PagoTemplos();">Templos</button-->
                        <?php }
                        if ($saldo_cliente > 0) { ?>
                            <button type="button" class="btn btn-sm btn-outline btn-outline-info" onclick="usarSaldo();">Saldo</button>
                       <?php }
                        if ($sw_citas_online == 1) { ?>
                            <button type="button" class="btn btn-sm btn-primary text-inverse-primary" onclick="CompletarOnline();">Completar citas online</button>
                        <?php }
                        if (isset($row['id_ticket']) && isset($ticket_ultimo[0]['fecha_creacion_aaaammdd'])) { ?>
                            <a href="<?php echo base_url(); ?>dietario/ver_ticket/<?php echo $ticket_ultimo[0]['id_ticket'] ?>" target="_blank" class="btn btn-sm btn-warning text-inverse-warning" onclick="Cerrar();">Imprmir Ticket <?php echo $ticket_ultimo[0]['fecha_creacion_aaaammdd']; ?></a>
                        <?php }
                    } else {
                        if (isset($ticket_ultimo[0]['fecha_creacion_aaaammdd'])) { ?>
                            <a href="<?php echo base_url(); ?>dietario/ver_ticket/<?php echo $ticket_ultimo[0]['id_ticket'] ?>" target="_blank" class="btn btn-sm btn-warning text-inverse-warning" onclick="Cerrar();">Imprmir Ticket <?php echo $ticket_ultimo[0]['fecha_creacion_aaaammdd']; ?></a>
                    <?php }
                    } ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function NuevoCarnet() {
            var posicion_x;
            var posicion_y;
            var ancho = 600;
            var alto = 500;
            posicion_x = (screen.width / 2) - (ancho / 2);
            posicion_y = (screen.height / 2) - (alto / 2);
            window.open("<?php echo base_url(); ?>carnets/gestion/nueva_venta/0/0/<?php echo $id_cliente; ?>", "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
        }
        //17/04/20
        function CarnetUnico() {
            var posicion_x;
            var posicion_y;
            var ancho = 600;
            var alto = 800;
            posicion_x = (screen.width / 2) - (ancho / 2);
            posicion_y = (screen.height / 2) - (alto / 2);
            //window.open("<?php echo base_url(); ?>carnets/gestion/recarga_unico/0/0/0", "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);
            window.open("<?php echo base_url(); ?>carnets/recargar_unico/0/<?php echo $id_cliente; ?>", "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
        }
        //Fin
        function NuevoServicio() {
            var posicion_x;
            var posicion_y;
            var ancho = 800;
            var alto = 600;
            posicion_x = (screen.width / 2) - (ancho / 2);
            posicion_y = (screen.height / 2) - (alto / 2);
            window.open("<?php echo base_url(); ?>agenda/citas/nuevo/0/0/<?php echo $hoy_aaaammdd ?>/null/<?php echo $id_cliente; ?>", "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
        }

        function NuevoProducto() {
            var posicion_x;
            var posicion_y;
            var ancho = 600;
            var alto = 650;
            posicion_x = (screen.width / 2) - (ancho / 2);
            posicion_y = (screen.height / 2) - (alto / 2);
            window.open("<?php echo base_url(); ?>productos/dietario/vender/<?php echo $id_cliente; ?>", "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
        }

        function PagoEfectivo() {
            var posicion_x;
            var posicion_y;
            var ancho = 750;
            var alto = 570;
            posicion_x = (screen.width / 2) - (ancho / 2);
            posicion_y = (screen.height / 2) - (alto / 2);
            window.open("<?php echo base_url(); ?>dietario/pagoeuros/ver/<?php echo $id_cliente; ?>/<?php echo $hoy_aaaammdd ?>", "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
        }

        function PagoTemplos() {
            var posicion_x;
            var posicion_y;
            var ancho = 750;
            var alto = 800;
            posicion_x = (screen.width / 2) - (ancho / 2);
            posicion_y = (screen.height / 2) - (alto / 2);
            window.open("<?php echo base_url(); ?>dietario/pagotemplos/ver/<?php echo $id_cliente; ?>/<?php echo $hoy_aaaammdd ?>", "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
        }

        function CompletarOnline() {
            Swal.fire({
                html: '¿DESEA MARCAR COMO PAGADAS LAS CITAS ONLINE?',
                showCancelButton: true,
                confirmButtonText: 'Si, borrar',
                showLoaderOnConfirm: true,
                onBeforeOpen: () => {},

            }).then((result) => {
                if (result.value) {
                    document.location.href = '<?php echo base_url(); ?>dietario/completar_citas_online/<?php echo $id_cliente; ?>/<?php echo $hoy_aaaammdd ?>';
                }
            })

        }

        function Eliminar(id_dietario, concepto) {
            Swal.fire({
                html: '¿DESEA BORRAR EL CONCEPTO: ' + concepto.toUpperCase() + '?',
                showCancelButton: true,
                confirmButtonText: 'Si, borrar',
                showLoaderOnConfirm: true,
                onBeforeOpen: () => {},

            }).then((result) => {
                if (result.value) {
                    document.location.href = '<?php echo base_url(); ?>dietario/borrar_conceptos/<?php echo $id_cliente; ?>/<?php echo $hoy_aaaammdd ?>/' + id_dietario;
                }
            })
        }

        function Cerrar() {
            window.opener.location.reload();
            window.close();
        }

        function FinalizarNota(fila, id_nota_cobrar) {
            /*if (confirm("¿DESEA FINALIZAR LA NOTA DE COBRO MARCADA?")) {
                document.getElementById("tabla_notas").deleteRow(fila.parentNode.rowIndex);
                $.ajax({
                    url: "<?php echo base_url(); ?>clientes/finalizar_una_nota_cobrar/" + id_nota_cobrar,
                    success: function(result) {
                        $("#borrar_nota").html(result);
                    }
                });
                var f = document.getElementById("tabla_notas").rows.length;
                if (f == 1) {
                    document.getElementById("tabla_notas").deleteRow(0);
                }
            }*/

            Swal.fire({
                html: '¿DESEA FINALIZAR LA NOTA DE COBRO MARCADA?',
                showCancelButton: true,
                confirmButtonText: 'Si, borrar',
                showLoaderOnConfirm: true,
                onBeforeOpen: () => {},

            }).then((result) => {
                if (result.value) {
                    document.getElementById("tabla_notas").deleteRow(fila.parentNode.rowIndex);
                    $.ajax({
                        url: "<?php echo base_url(); ?>clientes/finalizar_una_nota_cobrar/" + id_nota_cobrar,
                        success: function(result) {
                            $("#borrar_nota").html(result);
                        }
                    });
                    var f = document.getElementById("tabla_notas").rows.length;
                    if (f == 1) {
                        document.getElementById("tabla_notas").deleteRow(0);
                    }
                }
            })
        }

        function VerCarnetsPagos(id_carnet) {
            var posicion_x;
            var posicion_y;
            var ancho = 640;
            var alto = 480;
            posicion_x = (screen.width / 2) - (ancho / 2);
            posicion_y = (screen.height / 2) - (alto / 2);
            window.open("<?php echo base_url(); ?>dietario/carnets_pago/ver/" + id_carnet, "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
        }
        $(document).tooltip({
            position: {
                my: "center bottom-20",
                at: "center top",
                using: function(position, feedback) {
                    $(this).css(position);
                    $("<div>")
                        .addClass("arrow")
                        .addClass(feedback.vertical)
                        .addClass(feedback.horizontal)
                        .appendTo(this);
                }
            }
        });
    </script>

</body>

</html>