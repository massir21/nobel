<style>
    .dataTables_filter {
        text-align: right;
    }

    .swal2-container.swal2-center.swal2-backdrop-show {
        z-index: 99999;
    }
</style>
<div class="card card-flush">
    <div class="card-header align-items-center py-5">
        <div class="card-toolbar justify-content-center w-100">
            <button class="m-1 btn btn-danger text-inverse-danger" onclick="CierreCaja();">Cierre Caja</button>
            <button class="m-1 btn btn-primary text-inverse-primary" onclick="NuevoServicio();">Servicio</button>

            <button class="m-1 btn btn-info text-inverse-info" onclick="NuevoProducto();">Producto</button>
            <?php /*
            <button class="m-1 btn btn-warning text-inverse-warning" onclick="CarnetUnico();">Carnet Único</button>
            <button class="m-1 btn btn-success text-inverse-success" onclick="NuevoCarnet();">Carnet</button>
            <button class="m-1 btn btn-outline btn-outline-success" onclick="NuevoRecarga();">Recarga</button>
            */ ?>
            <button class="m-1 btn btn-secondary text-inverse-secondary " onclick="Devolucion();">Devolución</button>
            <button class="m-1 btn btn-outline btn-outline-info " onclick="PagoCuenta();" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Realizar un pago a cuenta por el cliente">Pago a cuenta</button>
        </div>

        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="dietario">
            </div>
        </div>
        <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
            <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                <div class="w-auto ms-3">
                    <label for="" class="form-label">Centro:</label>
                    <select name="id_centro" id="id_centro" class="form-select form-select-solid w-auto" onchange="document.form.submit();">
                        <option value="">Todos</option>
                        <?php if (isset($centros_todos)) {
                            if ($centros_todos != 0) {
                                foreach ($centros_todos as $key => $row) {
                                    if ($row['id_centro'] > 1) { ?>
                                        <option value='<?php echo $row['id_centro']; ?>' <?php if (isset($id_centro)) {
                                                                                                if ($row['id_centro'] == $id_centro) {
                                                                                                    echo "selected";
                                                                                                }
                                                                                            } ?>>
                                            <?php echo $row['nombre_centro']; ?>
                                        </option>
                        <?php }
                                }
                            }
                        } ?>
                    </select>
                </div>
            <?php } else { ?>
                <input type="hidden" name="id_centro" id="id_centro" value="<?php echo $this->session->userdata('id_perfil'); ?>" />
            <?php } ?>
            <div class="w-auto">
                <label for="" class="form-label">Tipo de dato</label>
                <select name="tipo_filtro" id="tipo_filtro" class="form-select form-select-solid w-auto">
                    <option value="">Todos</option>
                    <option value="tipo_pago">Pagos</option>
                    <option value="tipo_cita_presupuesto">Citas de presupuesto</option>
                    <option value="tipo_cita_no_presupuesto">Citas sin presupuesto</option>
                </select>
            </div>
            <div class="w-auto">
                <label for="" class="form-label">Fecha</label>
                <input type="date" id="fecha" name="fecha" value="<?php if (isset($hoy_aaaammdd)) {
                                                                        echo $hoy_aaaammdd;
                                                                    } ?>" class="form-control form-control-solid w-auto" placeholder="Fecha desde" required />
            </div>
            <div class="w-auto  ms-3">
                <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                    <button type="button" class="btn btn-info btn-icon text-inverse-info" onclick="NuevoDiaFiltroCentro();"><i class="fas fa-search"></i></button>
                <?php } else { ?>
                    <button type="button" class="btn btn-info text-inverse-info" onclick="NuevoDia();"><i class="fas fa-search"></i></button>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">
            <table id="dietario" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th style="display: none;"></th>
                        <th>Fecha / Hora</th>
                        <th>Cliente</th>
                        <th>Concepto</th>
                        <th>Empleado</th>
                        <th>Euros</th>
                        <th>Templos</th>
                        <th>Estado</th>
                        <th style="display: none;"></th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php if (isset($dietario)) {
                        if ($dietario != 0) {
                            foreach ($dietario as $key => $row) { 
                                
                                if($row['importe_total_final'] > 0 || $row['estado'] == 'Cierre Caja' || $row['estado'] == 'Devuelto'){
                                    $datatipo = 'tipo_pago';
                                }elseif($row['nro_presupuesto'] != ''){
                                    $datatipo = 'tipo_cita_presupuesto';
                                }else{
                                    $datatipo = 'tipo_cita_no_presupuesto';
                                }?>


                                <tr style="background: <?php echo $row['color_estado']; ?>;">
                                    <td style="display: none;">
                                        
                                        <?php echo $row['fecha_hora_concepto_aaaammdd'] . " " . $row['hora']; ?>
                                    </td>
                                    <td>
                                        <?php if ($row['tipo_pago'] == "#templos") { ?>
                                            <a class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6" href="<?php echo base_url(); ?>dietario/ticket_today/<?php echo $row['fecha_hora_concepto_aaaammdd'] . "/" . $row['id_cliente']; ?>" target="_blank"><?php echo $row['fecha_hora_concepto_ddmmaaaa_abrv']; ?> <?php echo $row['hora']; ?></a>
                                        <?php } else { ?>
                                            <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6"><?= $row['fecha_hora_concepto_ddmmaaaa_abrv'] ?> <?php echo $row['hora']; ?></span>
                                        <?php } ?>
                                        <?php if ($row['id_ticket'] > 0 && $row['estado'] == "Pagado") { ?>
                            <!--                <a class="btn btn-sm btn-icon btn-warning" href="<?php echo base_url(); ?>dietario/ver_ticket/<?php echo $row['id_ticket'] ?>" target="_blank" data-bs-toggle="tooltip" title="Ver ticket"><i class="fas fa-eye"></i></a>-->
                                        <?php } ?>
                                        <?php if ($row['id_ticket'] == 0 && $row['estado'] == "Pagado") { ?>
                            <!--                <button type="button" onclick="Generarticket(<?php echo $row['id_cliente']; ?>,<?php echo $row['id_centro']; ?>);" class="btn btn-icon btn-sm btn-primary" data-bs-toggle="tooltip" title="Generar ticket"><i class="fas fa-ticket"></i></button>-->
                                        <?php } ?>
                                        <?php if ($row['id_pedido'] > 0) { ?>
                                            <i class="fa fa-globe"></i>
                                        <?php } ?>

                                        <?php if ($datatipo != 'tipo_pago' && $row['estado_cita'] == 'Programada' && $this->session->userdata('id_perfil') == 0) { ?>
                                            <button type="button" class="btn btn-sm btn-icon btn-info ms-3" data-bs-toggle="tooltip" title="Editar cita" onclick="CitasEditar(<?php echo $row['id_cita']; ?>);"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                        <?php } ?>
                                    </td>
                                    <?php if ($row['estado'] != "Cierre Caja") { ?>
                                        <td style="text-align: center;">
                                            <a href="#" class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6" onclick="FichaCliente(<?php echo $row['id_cliente']; ?>,'<?php echo $row['fecha_hora_concepto_aaaammdd']; ?>');" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="<?php echo $row['observaciones']; ?>"><?php echo $row['cliente']; ?></a>
                                            <?php if ($row['estado'] == "Pagado" || $row['estado'] == "Devuelto") { ?>
                                                <button type="button" class="btn btn-sm btn-icon btn-dark" data-bs-toggle="tooltip" title="Generar Recibo" onclick="Facturacion(<?php echo $row['id_cliente']; ?>,<?php echo $row['id_centro']; ?>);"><i class="fas fa-file-invoice"></i></button>
                                            <?php } ?>
                                        </td>
                                        <td style="text-align: center; <?php if ($row['producto'] != "") {
                                                                            echo "background: #fad7e4;";
                                                                        } ?> <?php if ($row['descuento_euros'] > 0 || $row['descuento_porcentaje'] >  0) {
                                                                                    echo "background: #dda6fa;";
                                                                                } ?> <?php if ($row['codigo_proveedor'] != "") {
                                                                                                                                                            echo "background: #f9ca8e;";
                                                                                                                                                        } ?>">
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
                                                echo strtoupper($row['carnet']);
                                            }
                                            if ($row['recarga'] == 1) {
                                                echo " (Recarga)";
                                            }
                                            if ($row['codigo_pack_online'] != "") {
                                                echo "<br>(Pack-online: " . $row['codigo_pack_online'] . ")";
                                            }
                                            ?>
                                            <?php if ($row['pago_a_cuenta'] == 1) {
                                                echo "Pago a cuenta";
                                            } ?>
                                            <?php if ($row['id_presupuesto'] > 1) {
                                                echo "Presupuesto " . $row['nro_presupuesto'];
                                            } ?>
                                            <?php
                                            if(isset($row['dientes']) && !empty($row['dientes'])){
                                                $dent=explode(",",$row['dientes']);
                                                if(count($dent)) {
                                                    echo " <i>(".( count($dent)>1 ? "Piezas: ": "Pieza: ");
                                                    foreach($dent as $k=>$dd){
                                                        $dent[$k]="#".$dd;
                                                    }
                                                    echo implode(",",$dent);
                                                    echo ") </i>";
                                                }

                                            }
                                            ?>
                                            <?php
                                            if(isset($row['observaciones']) && !empty(trim($row['observaciones']))){
                                                ?>
                                                &nbsp;<span class="badge badge-secondary ms-2" data-bs-toggle="tooltip" aria-label="Comentarios" data-bs-original-title="<?php
                                                echo $row['observaciones'];?>"><i class="fa fa-comments text-dark"></i></span>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $row['empleado']; ?>
                                        </td>
                                        <td class="text-end">
                                            <?php if ($row['notas_pago_descuento'] != "") { ?>
                                                <span style="cursor: pointer; cursor: hand;" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="<?php echo $row['notas_pago_descuento']; ?>">
                                                <?php } ?>
                                                <?php if ($row['tipo_pago'] != "#templos") { ?>
                                                    <?php echo number_format($row['importe_total_final'], 2, ',', '.') . "€"; ?>
                                                    <?php if ($row['tipo_pago_saldo'] == "#liquidacion") {
                                                        echo "<br><span class='badge badge-primary' style='font-size: 11px; '>Saldo. " . round($row['importe_saldo'], 2) . " €</span>";
                                                    } ?>
                                                    <?php if ($row['descuento_euros'] > 0) {
                                                        echo "<br><span class='badge badge-success' style='font-size: 11px; '>Dto. " . round($row['descuento_euros'], 2) . " €</span>";
                                                    } ?>
                                                    <?php if ($row['descuento_porcentaje'] > 0) {
                                                        echo "<br><span class='badge badge-dark' style='font-size: 11px; '>Dto. " . round($row['descuento_porcentaje'], 2) . "%</span>";
                                                    } ?>
                                                <?php } else { ?>
                                                    <?php echo "0,00€"; ?>
                                                <?php } ?>
                                                <?php if ($row['notas_pago_descuento'] != "") { ?>
                                                </span>
                                            <?php } ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php if ($row['templos'] > 0 && $row['tipo_pago'] == "#templos") { ?>
                                                <?php echo round($row['templos'], 2);
                                                if ($row['foto_templo'] != null && $row['foto_templo'] != '') { ?>
                                                    <a href="<?php echo base_url() . 'recursos/foto/' . $row['foto_templo']; ?>" data-lightbox="smile"> <img height="42" width="42" src="<?php echo base_url() . 'recursos/foto/' . $row['foto_templo']; ?>"> </a>
                                                <?php } ?>
                                            <?php } else { ?> - <?php } ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php 
                                             $class = "badge badge-warning text-uppercase border-0 d-block mx-auto my-2";
                                             if ($row['estado'] == "Pagado" || $row['estado'] == "Devuelto") { ?>

                                                <?php echo $row['estado']; ?>

                                                <?php $tipo_pago_label = $row['tipo_pago'];
                                                $tipo_pago_array = explode('#', $row['tipo_pago']);
                                               
                                                foreach ($tipo_pago_array as $tp => $tipop) {
                                                    if ($tipop != '') {
                                                        switch ($tipop) {
                                                            case 'efectivo':
                                                                $class = "badge badge-info text-uppercase border-0 d-block mx-auto my-2";
                                                            case 'tarjeta':
                                                                $class = "badge badge-success text-uppercase border-0 d-block mx-auto my-2";
                                                                break;
                                                            case 'habitacion':
                                                                $class = "badge badge-primary text-uppercase border-0 d-block mx-auto my-2";
                                                                break;
                                                            case 'templos':
                                                                $class = "badge badge-warning text-uppercase border-0 d-block mx-auto my-2";
                                                                break;
                                                            case 'transferencia':
                                                                $class = "badge btn btn-outline btn-outline-info text-uppercase border-0 d-block mx-auto my-2";
                                                                break;
                                                            case 'paypal':
                                                                $class = "badge badge-secondary text-uppercase border-0 d-block mx-auto my-2";
                                                                break;
                                                            case 'tpv2':
                                                                $class = "badge btn btn-outline btn-outline-primary text-uppercase border-0 d-block mx-auto my-2";
                                                                break;
                                                            case 'financiado':
                                                                $class = "badge badge-primary text-uppercase border-0 d-block mx-auto my-2";
                                                                break;
                                                            default:
                                                                $class = "badge badge-success text-uppercase border-0 d-block mx-auto my-2";
                                                                break;
                                                        } ?>

                                                        <?php if ($tipop == "efectivo" || $tipop == "tarjeta" || $tipop == "transferencia" || $tipop == "tpv2" || $tipop == "habitacion" || $tipop == "financiado" || $tipop == "paypal") { ?>

                                                            <button type="button" class="<?= $class ?>" onclick="CambioEfectivoTarjeta(<?php echo $row['id_dietario']; ?>)"><?= $tipop ?></button>
                                                        <?php } else { ?>
                                                        <?php echo /*$row['estado'] . */ '<br><span class="' . $class . '">' . $tipop  . '</span>';
                                                        } ?>

                                                        <?php if ($tipop == "templos" && isset($row['carnets_pagos'][0]['codigo'])) { ?>
                                                            <?php foreach ($row['carnets_pagos'] as $dato) { ?> | <button type="button" class="btn btn-sm btn-link" onclick="VerCarnetsPagos(<?php echo $dato['id_carnet'] ?>);"><?php echo strtoupper($dato['codigo']); ?></button>
                                                            <?php } ?>
                                                        <?php } ?>

                                                        <?php if ($row['estado'] == "Devuelto") { ?>
                                                            <?php echo "<br>Motivo: " . $row['motivo_devolucion']; ?>
                                                <?php }
                                                    }
                                                } ?>
                                                <?php if ($row['estado'] == "Pagado" && $row['id_pedido'] == 0) { ?>
                                                    <?php if ($row['id_carnet'] > 0 && $row['recarga'] == 0) { ?>
                                                        <a href="#" class="btn btn-sm btn-icon btn-warning" onclick="javascript:DevolucionCarnet('<?php echo $row['id_dietario'] ?>');"><i class="fas fa-trash"></i></a>
                                                    <?php } elseif($row['tipo_pago'] != '#Presupuesto' && $row['devuelto'] == 0){ ?>
                                                        <a href="#" class="btn btn-sm btn-icon btn-warning" onclick="javascript:Devolucion('<?php echo $row['id_dietario'] ?>');"><i class="fas fa-trash"></i></a>
                                                    <?php } ?>


                                                    <?php if ($row['pago_a_cuenta'] == 1 && $row['justificante_pagado'] != '') { ?>
                                                        <button type="button" class="btn btn-sm btn-info p-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Ver justificante de pago" data-ver-justificante="<?php echo $row['justificante_pagado']; ?>">Ver justificante <i class="fa-solid fa-file fs-6"></i></button>
                                                    <?php } ?>


                                                <?php }
                                            } else { ?>
                                                <?php if ($row['estado'] == 'Pendiente justificante') { ?>
                                                    <button type="button" class="<?= $class ?>" data-add-justificante="<?php echo $row['id_dietario']; ?>" data-presupuesto-justificante="<?php echo $row['id_presupuesto']; ?>"><?= $row['estado'] ?></button>
                                                <?php } else { ?>
                                                    <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6"><?php echo $row['estado']; ?></span>
                                                <?php } ?>

                                                <?php if ($row['estado'] == "Anulada" || $row['estado'] == "No vino") { ?>
                                                    <?php //echo "<br>".$row['observaciones']; 
                                                    ?>
                                                <?php } ?>
                                            <?php } ?>
                                        </td>
                                    <?php } else { ?>
                                        <td>-</td>
                                        <td>-</td>
                                        <td><b>Cierre de Caja</b></td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                    <?php } ?>

                                    <td style="display: none;">
                                        <?=$datatipo?>
                                    </td>
                                </tr>
                    <?php }
                        }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>


    function CierreCaja() {
        var pendientes = [];
        $('#dietario').DataTable().rows().eq(0).each(function (index) {
            var row = $('#dietario').DataTable().row(index);
            var data = row.data();
            console.log(data)
            var cellestado = row.data()[7]; 
            var cellHtml1 = formatHora(row.data()[0]); 
            var cellHtml2 = stripHtml(row.data()[1]); 
            var cellHtml3 = stripHtml(row.data()[2]); 
            var cellHtml4 = stripHtml(row.data()[3]);

            if ((cellestado.toLowerCase().includes('pendiente') || cellestado.toLowerCase().includes('no pagado')) && cellHtml1 !== false) {
                pendientes.push([cellHtml1, cellHtml3, cellHtml4]);
            }
           
           
        });
        //console.log(pendientes)
        if (pendientes.length > 0) {
            var pendientesInvertido = pendientes.slice().reverse();
            var tableHtml = '<div class="table-responsive"><table class="table table-sm table-striped"><thead><tr><th class="text-nowrap">Hora</th><th>Nombre</th><th>Tratamiento</th></tr></thead><tbody>';

            tableHtml += pendientesInvertido.map(item => `<tr><td class="text-nowrap">${item[0]}</td><td>${item[1]}</td><td>${item[2]}</td></tr>`).join('');

            tableHtml += '</tbody></table></div>';

            Swal.fire({
                title: '<h4>¿Quieres cerrar caja sin cobrar las citas pendientes?</h4>',
                html: tableHtml,
                showCancelButton: true,
                confirmButtonText: 'Cerrar caja',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    openwindow('cierra_caja', '<?php echo base_url(); ?>caja/cierre', 600, 700);
                } else {
                    // Cancelar la acción
                    Swal.fire('Acción Cancelada', '', 'info');
                }
            });
        } else {
           openwindow('cierra_caja', '<?php echo base_url(); ?>caja/cierre', 600, 700);
        }
    }

    function stripHtml(html) {
        var doc = new DOMParser().parseFromString(html, 'text/html');
        return doc.body.textContent || "";
    }

    // Función para formatear la hora
    function formatHora(fechaString) {
        var fecha = new Date(fechaString);
        var fechaActual = new Date();
        var esMismoDia = fecha.getDate() === fechaActual.getDate() && fecha.getMonth() === fechaActual.getMonth() && fecha.getFullYear() === fechaActual.getFullYear();
        if (esMismoDia) {
            var hora = fecha.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            return hora;
        }else{
            return false;
        }
    }


    function NuevoProducto() {
        openwindow('nuevo_producto', '<?php echo base_url(); ?>productos/dietario/vender/<?= (isset($id_cliente)) ? $id_cliente : '' ?>', 600, 650);
    }
    <?php /* 
    function NuevoCarnet() {
        var posicion_x;
        var posicion_y;
        var ancho = 600;
        var alto = 500;
        var ventanaActual = window;
    var posicionVentanaActual = {
        x: ventanaActual.screenX || ventanaActual.screenLeft || 0,
        y: ventanaActual.screenY || ventanaActual.screenTop || 0
    };

    // Calcular la posición centrada en la pantalla activa
    posicion_x = posicionVentanaActual.x + (ventanaActual.innerWidth - ancho) / 2;
    posicion_y = posicionVentanaActual.y + (ventanaActual.innerHeight - alto) / 2;
        window.open("<?php echo base_url(); ?>carnets/gestion/nueva_venta/0/0/0", "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
    }
    
    function CarnetUnico() {
        var posicion_x;
        var posicion_y;
        var ancho = 600;
        var alto = 400;
        var ventanaActual = window;
    var posicionVentanaActual = {
        x: ventanaActual.screenX || ventanaActual.screenLeft || 0,
        y: ventanaActual.screenY || ventanaActual.screenTop || 0
    };

    // Calcular la posición centrada en la pantalla activa
    posicion_x = posicionVentanaActual.x + (ventanaActual.innerWidth - ancho) / 2;
    posicion_y = posicionVentanaActual.y + (ventanaActual.innerHeight - alto) / 2;
        //window.open("<?php echo base_url(); ?>carnets/gestion/recarga_unico/0/0/0", "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);
        window.open("<?php echo base_url(); ?>carnets/recargar_unico/", "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
    }
    
    function NuevoRecarga() {
        var posicion_x;
        var posicion_y;
        var ancho = 600;
        var alto = 300;
        var ventanaActual = window;
    var posicionVentanaActual = {
        x: ventanaActual.screenX || ventanaActual.screenLeft || 0,
        y: ventanaActual.screenY || ventanaActual.screenTop || 0
    };

    // Calcular la posición centrada en la pantalla activa
    posicion_x = posicionVentanaActual.x + (ventanaActual.innerWidth - ancho) / 2;
    posicion_y = posicionVentanaActual.y + (ventanaActual.innerHeight - alto) / 2;
        window.open("<?php echo base_url(); ?>carnets/recargar/gestion", "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
    }
    */ ?>

    function NuevoServicio() {
        openwindow('nuevo_servicio', '<?php echo base_url(); ?>agenda/citas/nuevo/0/0/<?php echo $hoy_aaaammdd; ?>/null/<?= (isset($id_cliente)) ? $id_cliente : '' ?>', 600, 650);
    }

    function Devolucion(id_dietario) {
        if (typeof id_dietario == 'undefined') {
            id_dietario = 0;
        }
        var url = "<?php echo base_url(); ?>dietario/devoluciones/index/0/" + id_dietario;
        openwindow('devolucion', url, 600, 450);
    }

    function PagoCuenta() {
        var url = "<?php echo base_url(); ?>dietario/pago_a_cuenta";
        openwindow('pago_cuenta', url, 800, 450);
    }

    function DevolucionCarnet(id_dietario) {
        var url = "<?php echo base_url(); ?>dietario/devolucion_carnet/" + id_dietario;
        openwindow('devolucion_carnet', url, 600, 450);
    }

    function FichaCliente(id_cliente, fecha) {
        var url = "<?php echo base_url(); ?>dietario/ficha/ver/" + id_cliente + "/" + fecha;
        openwindow('ficha_cliente', url, 800, 650);
    }

    function Facturacion(id_cliente, id_centro_facturar) {
        var url = "<?php echo base_url(); ?>dietario/facturacion/" + id_cliente + "/" + id_centro_facturar;
        openwindow('facturacion', url, 800, 650);
    }

    function VerCarnetsPagos(id_carnet) {
        var url = "<?php echo base_url(); ?>dietario/carnets_pago/ver/" + id_carnet;
        openwindow('ver_carnet_pagos', url, 640, 480);
    }
    function Generarticket(id_cliente, id_centro_facturar) {
        var url = "<?php echo base_url(); ?>dietario/generarticket/" + id_cliente + "/" + id_centro_facturar;
        openwindow('generat_ticket', url, 800, 600);
    }

    function NuevoDia() {
        document.location.href = "<?php echo base_url(); ?>dietario/index/dia/" + document.getElementById("fecha").value;
    }

    function NuevoDiaFiltroCentro() {
        document.location.href = "<?php echo base_url(); ?>dietario/index/dia/" + document.getElementById("fecha").value + "/" + document.getElementById("id_centro").value;
    }

    function CambioEfectivoTarjeta(id_dietario) {
        //04/03/20
        var id_centro = document.getElementById('id_centro').value;
        var url = "<?php echo base_url(); ?>dietario/cambio_efectivo_tarjeta/" + id_dietario + "/" + "/" + id_centro;
        openwindow('cambio_efectivo_tarjeta', url, 640, 480);
    }






    $(document).on('click', '[data-ver-justificante]', function() {
		var nuevaPestana = window.open('<?php echo base_url(); ?>' + $(this).attr('data-ver-justificante'), '_blank');
		nuevaPestana.focus();
	})

    $('#tipo_filtro').change(function() {
			var table = $('#dietario').DataTable();
			table.search(this.value).draw();
	})
    
    function CitasEditar(id_cita) {
        var url = "<?php echo base_url(); ?>agenda/citas/editar/" + id_cita;
        openwindow('citas_editar', url, 800, 620);
    }
</script>



<?php

$this->load->view($this->config->item('template_dir') . '/justificante_pago_popup');

?>

