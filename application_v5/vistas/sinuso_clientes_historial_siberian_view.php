<STYLE>
    .dataTables_filter { text-align: right; }
</STYLE>
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <!-- BEGIN PAGE BAR -->
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
              <a href="<?php echo base_url();?>site" style="font-size: 20px;">Panel de Control</a>
              <i class="fa fa-angle-right"></i>
            </li>
            <li>
              <a href="<?php echo base_url();?>clientes" style="font-size: 20px;">
                Gestión de Clientes
              </a>
              <i class="fa fa-angle-right"></i>
            </li>
            <li style="font-size: 20px;">
                <strong>Historial</strong>
            </li>
        </ul>
    </div>
    <!-- END PAGE BAR -->
    <!-- END PAGE HEADER-->
  <!-- BEGIN FORMULARIO -->
     <div class="row mb-5 border-bottom">
     <div class="col-md-2">
        <label class="form-label">Fecha Alta</label>
        <div class="input-icon">
          <?php if (isset($registros)) { echo $registros[0]['fecha_creacion_ddmmaaaa']; } ?>
        </div>
      </div>
      <div class="col-md-2">
        <label class="form-label">Nombre</label>
        <div class="input-icon">
          <b><?php if (isset($registros)) { echo $registros[0]['nombre']; } ?></b>
        </div>
      </div>
      <div class="col-md-3">
        <label class="form-label">Apellidos</label>
        <div class="input-icon">
          <b><?php if (isset($registros)) { echo $registros[0]['apellidos']; } ?></b>
        </div>
      </div>
      <div class="col-md-1">
        <label class="form-label">DNI</label>
        <div class="input-icon">
          <b><?php if (isset($registros)) { echo $registros[0]['dni']; } ?></b>
        </div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Email</label>
          <div class="input-icon">
            <?php if (isset($registros)) {
                if ($registros[0]['email']=="") { echo "N/D"; }
                else { echo $registros[0]['email']; }
            }
            ?>
          </div>
      </div>
    </div>
     <div class="row mb-5 border-bottom">
      <div class="col-md-2">
        <label class="form-label">Nacimiento</label>
          <div class="input-icon">
            <?php if (isset($registros)) {
                if ($registros[0]['fecha_nacimiento_ddmmaaaa']=="") { echo "N/D"; }
                else { echo $registros[0]['fecha_nacimiento_ddmmaaaa']; }
            }
            ?>
          </div>
      </div>
      <div class="col-md-3">
        <label class="form-label">Teléfono</label>
          <div class="input-icon">
            <?php if (isset($registros)) {
                if ($registros[0]['telefono']=="") { echo "N/D"; }
                else { echo $registros[0]['telefono']; }
            }
            ?>
            </div>
      </div>
      <div class="col-md-5">
        <label class="form-label">Dirección</label>
          <div class="input-icon">
            <?php if (isset($registros)) {
                if ($registros[0]['direccion']=="") { echo "N/D"; }
                else { echo $registros[0]['direccion']; }
            }
            ?>
          </div>
      </div>
      <div class="col-md-2">
        <label class="form-label">Código Postal</label>
          <div class="input-icon">
            <?php if (isset($registros)) {
                if ($registros[0]['codigo_postal']=="") { echo "N/D"; }
                else { echo $registros[0]['codigo_postal']; }
            }
            ?>
          </div>
      </div>
    </div>
    <h4><b>Datos de Facturación</b></h4>
     <div class="row mb-5 border-bottom">
      <div class="col-md-4">
        <label class="form-label">Nombre o Empresa</label> 
        <div class="input-icon">         
        <?php echo ($registros[0]['empresa'] != '') ? $registros[0]['empresa'] : "---" ;?> 
        </div>         
      </div>
      <div class="col-md-2">
        <label class="form-label">CIF o NIF</label>        
        <div class="input-icon">         
        <?php echo ($registros[0]['cif_nif'] != '') ? $registros[0]['cif_nif'] : "---" ;?> </div>          
      </div>
      <div class="col-md-4">
        <label class="form-label">Dirección</label>        
        <div class="input-icon">         
        <?php echo ($registros[0]['direccion_facturacion'] != '') ? $registros[0]['direccion_facturacion'] : "---" ;?> </div>         
      </div>
      <div class="col-md-2">
        <label class="form-label">Código Postal</label>
        <div class="input-icon">         
        <?php echo ($registros[0]['codigo_postal_facturacion'] != '') ? $registros[0]['codigo_postal_facturacion'] : "---" ;?> </div>
      </div>
    </div>
     <div class="row mb-5 border-bottom">
      <div class="col-md-4">
        <label class="form-label">Localidad</label>          
        <div class="input-icon">         
        <?php echo ($registros[0]['localidad_facturacion'] != '') ? $registros[0]['localidad_facturacion'] : "---" ;?> </div>      
      </div>      
      <div class="col-md-4">
        <label class="form-label">Provincia</label>
        <div class="input-icon">         
        <?php echo ($registros[0]['provincia_facturacion'] != '') ? $registros[0]['provincia_facturacion'] : "---" ;?> </div>
      </div>      
    </div>
     <div class="row mb-5 border-bottom">
      <div class="col-md-12" style="text-align: center;">
        <label class="form-label">Notas</label>
        <textarea class="form-control form-control-solid" name="notas" readonly><?php if (isset($registros[0]['notas'])) { echo $registros[0]['notas']; } ?></textarea>
      </div>
    </div>
     <div class="row mb-5 border-bottom">
      <div class="col-md-12" style="text-align: center;">
        <?php if (isset($registros[0]['no_quiere_publicidad'])) { if ($registros[0]['no_quiere_publicidad']==1) { echo "<strong style='color:#f00;'>NO quiero recibir publicidad</strong>"; } else { echo "SI quiero recibir publicidad"; }} ?>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <?php if ($existe_firma) { ?>
            <b>
                <a href="<?php echo base_url();?>clientes/ver_firma_lopd/<?php echo $registros[0]['id_cliente']; ?>" target="_blank">Ver PDF de la firma</a>
            </b>
        <?php } else { ?>
            <a href="<?php echo base_url();?>clientes/proteccion_de_datos/<?php echo $registros[0]['id_cliente']; ?>" class="btn btn-info text-inverse-info">
                Firmar Protección de Datos
            </a>
        <?php } ?>
      </div>
    </div>
    <div class="panel with-nav-tabs panel-default">
        <div class="panel-heading">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab1default" data-toggle="tab">Historial Actual</a></li>
                <li><a href="#tab2default" data-toggle="tab">Historial Antiguo</a></li>
                <li><a href="#tab3default" data-toggle="tab">Carnets</a></li>
                <li><a href="#tab4default" data-toggle="tab">Otros Datos</a></li>
                <li><a href="#tab5default" data-toggle="tab">Notas Citas</a></li>
                <li><a href="#tab6default" data-toggle="tab">Notas Cobrar</a></li>
                <li><a href="#tab7default" data-toggle="tab">Saldo</a></li>
            </ul>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab1default">
                    <?php if (isset($historial)) { if ($historial != 0) { ?>
                    <table id="myTable3" class="table table-striped table-hover table-bordered">
                    <thead>
                      <tr>
                        <th style="display: none;">ID</th>
                        <th>Fecha - Hora</th>
                        <th>Centro</th>
                        <th>Emp.</th>
                        <th>Serv/Prod/Carnet</th>
                        <th>Euros</th>
                        <th>Templos</th>
                        <th>Estado</th>
                      </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                      <?php $total_importe=0; $total_templos=0; ?>
                      <?php if (isset($historial)) { if ($historial != 0) { foreach ($historial as $key => $row) { ?>
                      <tr style="background: <?php echo $row['color_estado']; ?>;">
                        <td style="display: none;">
                            <?php echo $row['fecha_hora_concepto_aaaammdd']." ".$row['hora']; ?>
                        </td>
                        <td style="text-align: center; font-size: 11px; background: <?php echo $row['color_estado']; ?> !important;">
                            <?php echo $row['fecha_hora_concepto_ddmmaaaa']; ?><br>
                            <?php echo $row['hora']; ?>
                        </td>
                        <td style="text-align: center;">
                            <?php echo $row['nombre_centro']; ?>
                        </td>
                        <td style="text-align: center;">
                            <?php echo $row['empleado']; ?>
                        </td>
                        <td style="text-align: center; <?php if ($row['producto'] != "") { echo "background: #fad7e4;"; } ?> <?php if ($row['descuento_euros']>0 || $row['descuento_porcentaje']>0) { echo "background: #dda6fa;"; } ?> <?php if ($row['codigo_proveedor'] != "") { echo "background: #f9ca8e;"; } ?>">
                            <?php if ($row['servicio'] != "") { echo $row['servicio']; if ($row['codigo_proveedor'] != "") { echo "<br>".$row['codigo_proveedor']; }} ?>
                            <?php if ($row['producto'] != "") { echo $row['producto']; if ($row['cantidad']>1) { echo "<br>(cantidad: ".$row['cantidad'].")"; }} ?>
                            <?php if ($row['carnet'] != "") {
                                    if ($row['servicio'] != "") { echo "<br>"; }
                                    echo $row['carnet'];
                                }
                                if ($row['recarga']==1) {
                                    echo " (Recarga)";
                                }
                                if ($row['codigo_pack_online']!="") {
                                    echo "<br>(Pack-online: ".$row['codigo_pack_online'].")";
                                }
                            ?>
                        </td>
                        <td class="text-end">
                            <?php if ($row['tipo_pago']!="#templos") { ?>
                                <?php echo number_format($row['importe_total_final'], 2, ',', '.')."€"; ?>
                                <?php if ($row['descuento_euros']>0) { echo "<br><span class='label label-default' style='font-size: 11px; color: #fff;'>Dto. ".round($row['descuento_euros'],2)." €</span>"; } ?>
                                <?php if ($row['descuento_porcentaje']>0) { echo "<br><span class='label label-default' style='font-size: 11px; color: #fff;'>Dto. ".round($row['descuento_porcentaje'],2)."%</span>"; }?>
                                <?php $total_importe+=$row['importe_total_final']; ?>
                            <?php } else { ?>
                                <?php echo "0,00€"; ?>
                            <?php } ?>
                        </td>
                        <td class="text-end">
                            <?php if ($row['templos']>0 && $row['tipo_pago']=="templos") { ?>
                                <?php echo round($row['templos'],0); $total_templos+=$row['templos']; ?>
                                <?php $total_templos+=$row['templos']; ?>
                            <?php } else { ?>
                                -
                            <?php } ?>
                        </td>
                        <td style="text-align: center;">
                            <?php if ($row['estado']=="Pagado" || $row['estado']=="Devuelto") { ?>
                                <?php $tipo_pago_label=$row['tipo_pago']; ?>
                                <?php $tipo_pago_label = str_replace("#efectivo", "<span style='font-size: 11px;' class='label label-info'>efectivo</span> ", $tipo_pago_label); ?>
                                <?php $tipo_pago_label = str_replace("#tarjeta", "<span style='font-size: 11px;' class='label label-success'>tarjeta</span> ", $tipo_pago_label); ?>
                                <?php $tipo_pago_label = str_replace("#habitacion", "<span style='font-size: 11px;' class='label label-primary'>habitación</span> ", $tipo_pago_label);?>
                                <?php $tipo_pago_label = str_replace("#templos", "<span style='font-size: 11px;' class='label label-warning'>templos</span> ", $tipo_pago_label); ?>
                                <?php echo $row['estado']."<br>".$tipo_pago_label; ?>
                                <?php if ($row['tipo_pago']=="#templos" && isset($row['carnets_pagos'][0]['codigo'])) { ?>
                                    <?php foreach ($row['carnets_pagos'] as $dato) { ?>
                                        |
                                        <a href="#" onclick="VerCarnetsPagos(<?php echo $dato['id_carnet'] ?>);"><b><?php echo $dato['codigo'] ?></b></a>
                                    <?php } ?>
                                <?php } ?>
                            <?php } else { ?>
                                <?php echo $row['estado']; ?>
                                <?php if ($row['estado']=="Anulada" || $row['estado']=="No vino") { ?>
                                    <?php echo "<br>".$row['observaciones']; ?>
                                <?php } ?>
                            <?php } ?>
                        </td>
                      </tr>
                      <?php } } } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" style="text-align: right; padding: 8px;"><b>TOTALES</b></td>
                            <td style="text-align: right; padding: 8px;"><?php echo  number_format($total_importe, 2, ",", ".").'€';?></td>
                            <td style="text-align: right; padding: 8px;"><?php echo round($total_templos,0); ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                  </table>
                    <?php } else { ?>
                        <div style="margin: 20px; text-align: center;">
                            No hay historial actual para este cliente
                        </div>
                    <?php }} ?>
                </div>
                <div class="tab-pane fade" id="tab2default">
                    <?php if (isset($historial_antiguo)) { if ($historial_antiguo != 0) { ?>
                    <table id="myTable4" class="table table-striped table-hover table-bordered">
                    <thead>
                      <tr>
                            <th>Nº Fact</th>
                            <th>Fecha</th>
                            <th>Centro</th>
                            <th>Importe</th>
                            <th>Cod Art.</th>
                            <th>Artículo</th>
                            <th>Cantidad</th>
                      </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                      <?php $total_importe=0; ?>
                      <?php if (isset($historial_antiguo)) { if ($historial_antiguo != 0) { foreach ($historial_antiguo as $key => $row) { ?>
                      <tr>
                            <td style="text-align: center;">
                                    <?php echo $row['numfac']; ?>
                            </td>
                            <td style="text-align: center;">
                                    <?php echo $row['fecfac']; ?>
                            </td>
                            <td style="text-align: center;">
                                    <?php echo $row['nombre_centro']; ?>
                            </td>
                            <td class="text-end">
                                    <?php echo $row['totfac']. "€"; $total_importe+=$row['totfac']; ?>
                            </td>
                            <td style="text-align: center;">
                                    <?php echo $row['codart']; ?>
                            </td>
                            <td style="text-align: left;">
                                    <?php echo $row['desart']; ?>
                            </td>
                            <td style="text-align: center;">
                                    <?php echo $row['cant']; ?>
                            </td>
                      </tr>
                      <?php } } } ?>
                    </tbody>
                    <tfoot>
                            <tr>
                                <td colspan="2" style="text-align: right; padding: 8px;"><b>TOTALES</b></td>
                                <td style="text-align: right; padding: 8px;"><?php echo number_format($total_importe, 2, ",", ".").'€'; ?></td>                
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                    </tfoot>
                    </table>
                    <?php } else { ?>
                        <div style="margin: 20px; text-align: center;">
                                No hay historial antiguo para este cliente
                        </div>
                    <?php }} ?>
                </div>
                <div class="tab-pane fade" id="tab3default">
                    <table id="myTable1" class="table table-striped table-hover table-bordered">
                        <thead>
                          <tr>
                            <th>Carnet</th>
                            <th>Tipo</th>
                            <th>T. Disponibles</th>
                            <th>Centro</th>
                            <th>Precio</th>
                          </tr>
                        </thead>
                        <tbody class="text-gray-700 fw-semibold">
                          <?php if (isset($carnets)) { if ($carnets != 0) { foreach ($carnets as $key => $row) { ?>
                          <tr>
                            <td>
                                <a href="<?php echo base_url();?>carnets/detalle/gestion/<?php echo $row['id_carnet']; ?>" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Ver detalle del Carnet">
                                    <a onclick="VerCarnetsPagos(<?php echo $row['id_carnet'] ?>);"><b><?php echo $row['codigo'] ?></b></a>
                                </a>
                            </td>
                            <td style="text-align: center;">
                              <?php echo $row['tipo']; ?>
                              <?php if ($row['codigo_pack_online']) { echo "<br>(pack-online: ".$row['codigo_pack_online'].")"; } ?>
                            </td>
                            <!--<td style="text-align: center;">
                              <?php //if ($row['id_tipo']!=99) { echo $row['templos']; } else { echo "-"; } ?>
                            </td>-->
                            <td style="text-align: center;">
                                <?php if ($row['id_tipo']!=99) { echo $row['templos_disponibles']; } else { echo "-"; } ?>
                            </td>
                            <td style="text-align: left;">
                              <?php echo $row['nombre_centro']; ?>
                            </td>
                            <td class="text-end">
                              <?php if ($row['id_tipo']!=99) { echo number_format($row['precio'], 2, ',', '.')."€"; }
                              else { echo number_format($row['precio'], 2, ',', '.')."€"; } ?>
                            </td>
                          </tr>
                          <?php } } } ?>
                        </tbody>
                      </table>
                </div>
                <div class="tab-pane fade" id="tab4default">
                    <table class="table table-striped table-hover table-bordered">
                        <tbody class="text-gray-700 fw-semibold">
                        <tr>
                            <td style="width: 35%;">Número de Citas</td>
                            <td><?php echo $citas_totales; ?></td>
                        </tr>
                        <tr>
                            <td>Facturación Total</td>
                            <td><?php echo number_format($facturacion_total, 2, ',', '.')." €"; ?></td>
                        </tr>
                        <tr>
                            <td>Facturación Total Antigua</td>
                            <td><?php echo number_format($facturacion_total_antigua, 2, ',', '.')." €"; ?></td>
                        </tr>
                        <tr>
                            <td>Total Descuentos €</td>
                            <td><?php echo number_format($descuentos_total, 2, ',', '.')." €"; ?></td>
                        </tr>
                        <tr>
                            <td>Número Productos Comprados</td>
                            <td><?php echo $productos_comprados; ?></td>
                        </tr>
                        <tr>
                            <td>Rentabilidad</td>
                            <td><?php echo number_format($rentabilidad, 2, ',', '.')."%"; ?></td>
                        </tr>
                        <tr>
                            <td>Citas No Vino</td>
                            <td><?php echo $citas_no_vino; ?> / <?php echo number_format($importe_no_vino, 2, ',', '.')." €"; ?></td>
                        </tr>
                        <tr>
                            <td>Citas Anuladas</td>
                            <td><?php echo $citas_anuladas; ?> / <?php echo number_format($importe_anuladas, 2, ',', '.')." €"; ?></td>
                        </tr>
                        <tr>
                            <td>Frecuencia Visitas Totales</td>
                            <td>
                                <?php echo number_format($frecuencia_annos, 2, ',', '.')." veces año"; ?>
                                &nbsp;&nbsp;/&nbsp;&nbsp;
                                <?php echo number_format($frecuencia_mes, 2, ',', '.')." veces mes"; ?>
                                &nbsp;&nbsp;/&nbsp;&nbsp;
                                <?php echo number_format($frecuencia_semana, 2, ',', '.')." veces semana"; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Visitas por periodos</td>
                            <td>
                                <?php echo "Último año: ".number_format($frecuencia_ultimo_anno, 0, ',', '.')." veces"; ?>
                                <br>
                                <?php echo "Últimos 3 meses: ".number_format($frecuencia_ultimo_3_mes, 0, ',', '.')." veces"; ?>
                                <br>
                                <?php echo "Último mes: ".number_format($frecuencia_ultimo_mes, 0, ',', '.')." veces"; ?>
                                <br>
                                <?php echo "Última semana: ".number_format($frecuencia_ultimo_semana, 0, ',', '.')." veces"; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Frecuencia Visitas Históricas</td>
                            <td>
                                <?php echo number_format($frecuencia_annos_historica, 2, ',', '.')." veces año"; ?>
                                &nbsp;&nbsp;/&nbsp;&nbsp;
                                <?php echo number_format($frecuencia_mes_historica, 2, ',', '.')." veces mes"; ?>
                                &nbsp;&nbsp;/&nbsp;&nbsp;
                                <?php echo number_format($frecuencia_semana_historica, 2, ',', '.')." veces semana"; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Empleados Favoritos</td>
                            <td>
                                <table>
                                    <?php if (isset($empleados_favoritos)) { if ($empleados_favoritos != 0) { foreach ($empleados_favoritos as $key => $row) { ?>
                                    <tr>
                                        <td style="padding-right: 5px;"><?php echo $row['nombre']." ".$row['apellidos']; ?></td>
                                        <td style="padding-right: 5px;"> | <?php echo $row['veces']; ?> veces</td>
                                    </tr>
                                    <?php }}} ?>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>Carnets Vendidos</td>
                            <td>
                                <table>
                                    <?php if (isset($carnets_vendidos)) { if ($carnets_vendidos != 0) { $suma_total_precio=0; foreach ($carnets_vendidos as $key => $row) { ?>
                                    <tr>
                                        <td style="padding-right: 5px;"><?php echo $row['descripcion']; ?></td>
                                        <td style="padding-right: 5px;"> | <?php echo $row['numero']; ?> veces</td>
                                        <td style="padding-right: 5px;"> | <?php $suma_total_precio+=$row['total_precio']; echo number_format($row['total_precio'], 2, ',', '.'); ?> €</td>
                                    </tr>
                                    <?php }}} ?>
                                    <?php if (isset($carnets_vendidos)) { if ($carnets_vendidos != 0) { ?>
                                    <tr>
                                        <td style="padding-right: 5px;"></td>
                                        <td style="padding-right: 5px; text-align: right;">Total: </td>
                                        <td style="padding-right: 5px; text-align: left;"> | <?php echo number_format($suma_total_precio, 2, ',', '.'); ?> €</td>
                                    </tr>
                                    <?php }} ?>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>Pago Con Templos</td>
                            <td><?php echo number_format($pago_con_templos, 2, ',', '.'); ?> templos</td>
                        </tr>
                        <tr>
                            <td>Valor Unitario del Templo</td>
                            <td><?php echo number_format($valor_unitario_templo, 2, ',', '.'); ?> €</td>
                        </tr>
                        <tr>
                            <td>Pago Dinero</td>
                            <td><?php echo number_format($facturacion_servicios_productos_recargas_total, 2, ',', '.')." €"; ?></td>
                        </tr>
                        <tr>
                            <td>% Servicios Pagados con Templos</td>
                            <td><?php echo number_format($porcentaje_pagado_templos, 2, ',', '.')."%"; ?></td>
                        </tr>
                        <tr>
                            <td>Centros Visitados</td>
                            <td>
                                <table>
                                    <?php if (isset($centros_visitados)) { if ($centros_visitados != 0) { foreach ($centros_visitados as $key => $row) { ?>
                                    <tr>
                                        <td style="padding-right: 5px;"><?php echo $row['nombre_centro']; ?></td>
                                        <td style="padding-right: 5px;"> | <?php echo $row['veces']; ?> veces</td>
                                    </tr>
                                    <?php }}} ?>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>Servicios Realizados</td>
                            <td>
                                <table>
                                    <?php if (isset($servicios_realizados)) { if ($servicios_realizados != 0) { foreach ($servicios_realizados as $key => $row) { ?>
                                    <tr>
                                        <td style="padding-right: 5px;"><?php echo $row['nombre_familia']." - ".$row['nombre_servicio']; ?></td>
                                        <td style="padding-right: 5px;"> | <?php echo $row['veces']; ?> veces</td>
                                    </tr>
                                    <?php }}} ?>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>Antelación Anulaciones</td>
                            <td>
                                <table>
                                    <tr>
                                        <td>Estado</td>
                                        <td>Fecha Cita</td>
                                        <td>Horas antes de la cita</td>
                                    </tr>
                                    <?php if (isset($antelacion_anulaciones)) { if ($antelacion_anulaciones != 0) { foreach ($antelacion_anulaciones as $key => $row) { ?>
                                    <tr>
                                        <td style="padding-right: 5px; <?php if ($row['horas']<3) { echo 'color: red;'; } ?>"><?php echo $row['estado']." | "; ?></td>
                                        <td style="padding-right: 5px; <?php if ($row['horas']<3) { echo 'color: red;'; } ?>"><?php echo $row['fecha_cita']; ?></td>
                                        <td style="padding-right: 5px; <?php if ($row['horas']<3) { echo 'color: red;'; } ?>"> | <?php echo number_format($row['horas'], 2, ',', '.'); ?> h.</td>
                                    </tr>
                                    <?php }}} ?>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="tab5default">
                    <div style="text-align: right; margin-bottom: 5px;">
                        <a href="<?php echo base_url();?>clientes/nueva_nota_cita/<?php echo $id_cliente; ?>" class="btn btn-primary text-inverse-primary margin-top-20" style="margin: 0px !important;"/>Crear Nota</a>
                    </div>
                    <form id="form_notas_citas" action="<?php echo base_url();?>clientes/finalizar_notas_citas/<?php echo $id_cliente; ?>" method="post" name="form_notas_citas">
                        <table id="logs" class="table table-striped table-hover table-bordered">
                          <thead>
                            <tr>
                              <th style="display: none;"></th>
                              <th>Marcar</th>
                              <th>Creación - Nota</th>
                              <th>Recepcionista</th>
                              <th>Estado</th>
                              <th>Recep. Finalizó</th>
                              <th>Finalización</th>
                              <th class="sorting_disabled" style="width: 1%"></th>
                              <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                              <th class="sorting_disabled" style="width: 1%"></th>
                              <?php } ?>
                            </tr>
                          </thead>
                          <tbody class="text-gray-700 fw-semibold">
                            <?php if (isset($notas_citas)) { if ($notas_citas != 0) { foreach ($notas_citas as $key => $row) { ?>
                            <tr>
                              <td style="display: none;">
                                <?php echo $row['fecha_creacion_aaaammdd'] ?>
                              </td>
                              <td style="text-align: center;">
                                <input type="checkbox" name="citas[]" value="<?php echo $row['id_nota_cita'] ?>" class="form-control form-control-solid" />
                              </td>
                              <td>
                                <?php echo $row['fecha_creacion_ddmmaaaa'] ?><br>
                                <?php echo $row['nota'] ?>
                              </td>
                              <td>
                                <?php echo $row['usuario_creacion'] ?>
                              </td>
                              <td>
                                <?php echo $row['estado'] ?>
                              </td>
                              <td style="text-align: center;">
                                <?php echo $row['usuario_finalizacion'] ?>
                              </td>
                              <td style="text-align: center;">
                                <?php echo $row['fecha_finalizacion_ddmmaaaa'] ?>
                              </td>
                              <td style="text-align: center;">
                                <span class="label label-sm label-primary">
                                  <a href="<?php echo base_url();?>clientes/editar_nota_cita/<?php echo $row['id_nota_cita'] ?>" style="color: #fff; font-weight: bold;">Editar</a>
                                </span>
                              </td>
                              <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                              <td style="text-align: center;">
                                <span class="label label-sm label-danger">
                                  <a href="#" onclick="BorrarNotaCita(<?php echo $row['id_nota_cita'] ?>);" style="color: #fff; font-weight: bold;">Borrar</a>
                                </span>
                              </td>
                              <?php } ?>
                            </tr>
                            <?php } } } ?>
                          </tbody>
                        </table>
                        <div style="text-align: center; margin-bottom: 5px;">
                            <input type="submit" class="btn btn-info text-inverse-info margin-top-20" value="Finalizar las Notas Marcardas" />
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="tab6default">
                    <div style="text-align: right; margin-bottom: 5px;">
                        <a href="<?php echo base_url();?>clientes/nueva_nota_cobrar/<?php echo $id_cliente; ?>" class="btn btn-primary text-inverse-primary margin-top-20" style="margin: 0px !important;"/>Crear Nota</a>
                    </div>
                    <form id="form_notas_cobrar" action="<?php echo base_url();?>clientes/finalizar_notas_cobrar/<?php echo $id_cliente; ?>" method="post" name="form_notas_cobrar">
                        <table id="logs" class="table table-striped table-hover table-bordered">
                          <thead>
                            <tr>
                              <th style="display: none;"></th>
                              <th>Marcar</th>
                              <th>Creación - Nota</th>
                              <th>Recepcionista</th>
                              <th>Estado</th>
                              <th>Recep. Finalizó</th>
                              <th>Finalización</th>
                              <th>Carnet</th>
                              <th class="sorting_disabled" style="width: 1%"></th>
                              <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                              <th class="sorting_disabled" style="width: 1%"></th>
                              <?php } ?>
                            </tr>
                          </thead>
                          <tbody class="text-gray-700 fw-semibold">
                            <?php if (isset($notas_cobrar)) { if ($notas_cobrar != 0) { foreach ($notas_cobrar as $key => $row) { ?>
                            <tr>
                              <td style="display: none;">
                                <?php echo $row['fecha_creacion_aaaammdd'] ?>
                              </td>
                              <td style="text-align: center;">
                                <input type="checkbox" name="cobros[]" value="<?php echo $row['id_nota_cobrar'] ?>" class="form-control form-control-solid" />
                              </td>
                              <td>
                                <?php echo $row['fecha_creacion_ddmmaaaa'] ?><br>
                                <?php echo $row['nota'] ?>
                              </td>
                              <td>
                                <?php echo $row['usuario_creacion'] ?>
                              </td>
                              <td>
                                <?php echo $row['estado'] ?>
                              </td>
                              <td style="text-align: center;">
                                <?php echo $row['usuario_finalizacion'] ?>
                              </td>
                              <td style="text-align: center;">
                                <?php echo $row['fecha_finalizacion_ddmmaaaa'] ?>
                              </td>
                              <td style="text-align: center;">
                                <?php echo $row['carnet'] ?>
                              </td>
                              <td style="text-align: center;">
                                <span class="label label-sm label-primary">
                                  <a href="<?php echo base_url();?>clientes/editar_nota_cobrar/<?php echo $row['id_nota_cobrar'] ?>" style="color: #fff; font-weight: bold;">Editar</a>
                                </span>
                              </td>
                              <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                              <td style="text-align: center;">
                                <span class="label label-sm label-danger">
                                  <a href="#" onclick="BorrarNotaCobrar(<?php echo $row['id_nota_cobrar'] ?>);" style="color: #fff; font-weight: bold;">Borrar</a>
                                </span>
                              </td>
                              <?php } ?>
                            </tr>
                            <?php } } } ?>
                          </tbody>
                        </table>
                        <div style="text-align: center; margin-bottom: 5px;">
                            <input type="submit" class="btn btn-info text-inverse-info margin-top-20" value="Finalizar las Notas Marcardas" />
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="tab7default">
                        <div class="text-center" style="padding-bottom: 15px;">
                            <h4><b>SALDO ACTUAL: <?php echo number_format($saldo, 2, ',', '.')."€"; ?></b></h4>
                        </div>
                        <table id="saldos" class="table table-striped table-hover table-bordered">
                          <thead>
                            <tr>
                              <th style="display: none;"></th>
                              <th>Fecha - Hora</th>
                              <th>Importe</th>
                              <th>Tipo Pago</th>
                              <th>Motivo</th>
                            </tr>
                          </thead>
                          <tbody class="text-gray-700 fw-semibold">
                            <?php if (isset($lista_saldos)) { if ($lista_saldos != 0) { foreach ($lista_saldos as $key => $row) { ?>
                            <?php if ($row['importe'] >= 0) { $color="#e0ffd4"; } else { $color="#f8d7dd"; } ?>
                            <tr style="background: <?php echo $color ?>">
                              <td style="display: none;">
                                <?php echo $row['fecha_creacion_aaaammdd'] ?>
                              </td>
                              <td style="text-align: center; width: 150px; background: <?php echo $color ?> !important;">
                                <?php echo $row['fecha_creacion_ddmmaaaa'] ?>
                              </td>
                              <td style="text-align: center;">
                                <?php echo number_format($row['importe'], 2, ',', '.')."€"; ?>
                              </td>
                              <td>
                                <?php echo $row['tipo_pago'] ?>
                              </td>
                              <td>
                                <?php echo $row['motivo'] ?>
                                <?php
                                  if ($row['tipo_pago'] == "#liquidacion")
                                  {
                                    echo $row['empleado']."<br> Fecha dietario: ".$row['fecha_hora_concepto'];
                                  }
                                ?>
                              </td>
                            </tr>
                            <?php } } } ?>
                          </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
  <!-- END FORMULARIO -->
</div>
<!-- END CONTENT BODY -->
<script>
    function VerCarnetsPagos(id_carnet) {
        var posicion_x;
        var posicion_y;
        var ancho=640;
        var alto=480;
        posicion_x=(screen.width/2)-(ancho/2);
        posicion_y=(screen.height/2)-(alto/2);
        window.open("<?php echo base_url();?>dietario/carnets_pago/ver/"+id_carnet, "_blank", "toolbar=no,scrollbars=no,resizable=no,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);
    }
</script>
<script>
  function BorrarNotaCita(id_nota_cita) {
    if(confirm("¿Desea borrar la nota de cita indicada?")) {
      document.location.href="<?php echo base_url();?>clientes/borrar_nota_cita/"+id_nota_cita;
    }
    return false;
  }
  function BorrarNotaCobrar(id_nota_cobrar) {
    if(confirm("¿Desea borrar la nota para cobrar indicada?")) {
      document.location.href="<?php echo base_url();?>clientes/borrar_nota_cobrar/"+id_nota_cobrar;
    }
    return false;
  }
  $( document ).ready(function() {
    $('#saldos').DataTable({
      "order": [[ 0, "desc" ]],
      "language": {
        "lengthMenu": " _MENU_ registros",
        "zeroRecords": "No se encontraron datos",
        "info": "Mostrar página _PAGE_ de _PAGES_",
        "infoEmpty": "No hay registros disponibles",
        "infoFiltered": "(filtrado sobre un total de _MAX_ registros)",
        "sSearch": "Buscar: "
      },
    });
  });
</script>
