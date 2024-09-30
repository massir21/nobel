<STYLE>
    .dataTables_filter { text-align: right; }
</STYLE>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<script>
    //20/10/20
function elegir(id,respuesta){    
       xtexto='t_'+id;
       xnotas="notas_"+id;
       //alert ('llego '+xnotas);
       if (respuesta=='si')
          document.getElementById(xtexto).style.display="block";
       else{
          document.getElementById(xnotas).value="";
          document.getElementById(xtexto).style.display="none";
       }
}
//Fin
</script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.10.0/js/lightbox.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.10.0/css/lightbox.css" rel="stylesheet"/>
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
                xGestión de Clientes
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
    <div class="row form-group text-center">
    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapse_form_cliente" aria-expanded="false" aria-controls="collapse_form_cliente">Editar
</button>
    </div>
    <div class="collapse" id="collapse_form_cliente">
      <!-- BEGIN FORMULARIO -->
  <form id="form" action="<?php echo base_url();?>clientes/gestion/actualizar/<?php echo $registros[0]['id_cliente'] ?>" role="form" method="post" name="form" style="background: #f1f4f7; padding: 10px;">
     <div class="row mb-5 border-bottom">
      <div class="col-md-4">
        <label class="form-label">Nombre</label>
        <div class="input-icon">
          <i class="fa fa-user"></i>
          <input name="nombre" class="form-control form-control-solid" type="text" value="<?php if (isset($registros)) { echo $registros[0]['nombre']; } ?>" placeholder="" required />
        </div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Apellidos</label>
        <div class="input-icon">
          <i class="fa fa-user"></i>
          <input name="apellidos" class="form-control form-control-solid" type="text" value="<?php if (isset($registros)) { echo $registros[0]['apellidos']; } ?>" placeholder="" required/>
        </div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Email</label>
          <div class="input-icon">
            <i class="fa fa-envelope"></i>
            <input name="email" class="form-control form-control-solid" type="email" value="<?php if (isset($registros)) { echo $registros[0]['email']; } ?>" placeholder="" />
          </div>
      </div>
    </div>
     <div class="row mb-5 border-bottom">
      <div class="col-md-3">
        <label class="form-label">Teléfono</label>
          <div class="input-icon">
            <i class="fa fa-phone"></i>
              <input name="telefono" class="form-control form-control-solid" type="number" value="<?php if (isset($registros)) { echo $registros[0]['telefono']; } ?>" placeholder="" required/>
            </div>
      </div>
      <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
        <label class="form-label">Dirección</label>
          <div class="input-icon">
            <i class="fa fa-envelope"></i>
            <input name="direccion" class="form-control form-control-solid" type="text" value="<?php if (isset($registros)) { echo $registros[0]['direccion']; } ?>" placeholder="" />
          </div>
      </div>
      <div class="col-md-3">
        <label class="form-label">Código Postal</label>
          <div class="input-icon">
            <i class="fa fa-envelope"></i>
            <input name="codigo_postal" class="form-control form-control-solid" type="text" value="<?php if (isset($registros)) { echo $registros[0]['codigo_postal']; } ?>" placeholder="" />
          </div>
      </div>
    </div>
     <div class="row mb-5 border-bottom">
        <div class="col-md-3">              
            <label for="fecha_nacimiento"><h5>Fecha de Nacimiento</h5></label>
            <input class="form-control form-control-solid" id="fecha_nacimiento" name="fecha_nacimiento" type="date" value="<?php if (isset($registros[0]['fecha_nacimiento_aaaammdd'])) { echo $registros[0]['fecha_nacimiento_aaaammdd']; } ?>">
        </div>
        <div class="col-md-2">              
            <label for="fecha_nacimiento"><h5>D.N.I</h5></label>
            <input class="form-control form-control-solid" id="dni" name="dni" type="text" value="<?php if (isset($registros[0]['dni'])) { echo $registros[0]['dni']; } ?>" maxlength="9" style="text-transform:uppercase">
        </div>
      <div class="col-md-7" style="text-align: center;">
        <label class="form-label">Notas</label>
        <textarea class="form-control form-control-solid" name="notas"><?php if (isset($registros[0]['notas'])) { echo $registros[0]['notas']; } ?></textarea>        
      </div>
    </div>
     <div class="row mb-5 border-bottom">
      <div class="col-md-12" style="text-align: center;">
        <input class="form-control form-control-solid" name="no_quiere_publicidad" type="checkbox" value="1" <?php if (isset($registros[0]['no_quiere_publicidad'])) { if ($registros[0]['no_quiere_publicidad']==1) { echo "checked"; } } ?> />
        NO quiero recibir publicidad
        &nbsp;&nbsp;&nbsp;
        <input type="checkbox" name="recordatorio_sms" value="1" <?php if (isset($registros[0]['recordatorio_sms'])) { if ($registros[0]['recordatorio_sms'] == 1) { echo "checked"; } } ?>> Recordatorio SMS
        &nbsp;&nbsp;&nbsp;
        <input type="checkbox" name="recordatorio_email" value="1" <?php if (isset($registros[0]['recordatorio_email'])) { if ($registros[0]['recordatorio_email'] == 1) { echo "checked"; } } ?>> Recordatorio Email
        <!-- 27/05/20 Ver Activo -->
        <?php if ($this->session->userdata('id_perfil') == 0) { ?>
        &nbsp;&nbsp;&nbsp;
        <input type="checkbox" name="activo" value="1" <?php if (isset($registros[0]['activo'])) { if ($registros[0]['activo'] == 1) { echo "checked"; } } ?>> Activo
        <?php } ?>
        <!-- Fin -->
      </div>
    </div>
    <h4><b>Datos de Facturación</b></h4>
     <div class="row mb-5 border-bottom">
      <div class="col-md-4">
        <label class="form-label">Nombre o Empresa</label>          
        <input name="empresa" class="form-control form-control-solid" type="text" value="<?php if (isset($registros)) { echo $registros[0]['empresa']; } ?>" />          
      </div>
      <div class="col-md-2">
        <label class="form-label">CIF o NIF</label>        
        <input name="cif_nif" class="form-control form-control-solid" type="text" value="<?php if (isset($registros)) { echo $registros[0]['cif_nif']; } ?>" />          
      </div>
      <div class="col-md-4">
        <label class="form-label">Dirección</label>        
        <input name="direccion_facturacion" class="form-control form-control-solid" type="text" value="<?php if (isset($registros)) { echo $registros[0]['direccion_facturacion']; } ?>" />          
      </div>
      <div class="col-md-2">
        <label class="form-label">Código Postal</label>
        <input name="codigo_postal_facturacion" class="form-control form-control-solid" type="text" value="<?php if (isset($registros)) { echo $registros[0]['codigo_postal_facturacion']; } ?>" />
      </div>
    </div>
     <div class="row mb-5 border-bottom">
      <div class="col-md-4">
        <label class="form-label">Localidad</label>          
        <input name="localidad_facturacion" class="form-control form-control-solid" type="text" value="<?php if (isset($registros)) { echo $registros[0]['localidad_facturacion']; } ?>" />        
      </div>      
      <div class="col-md-4">
        <label class="form-label">Provincia</label>
        <input name="provincia_facturacion" class="form-control form-control-solid" type="text" value="<?php if (isset($registros)) { echo $registros[0]['provincia_facturacion']; } ?>" />
      </div>      
    </div>
    <?php if ($this->session->userdata('id_perfil') == 0) { ?>
    <script type="text/javascript" src="<?php echo RUTA_WWW ?>/recursos/tinymce/js/tinymce/tinymce.min.js"></script>    
    <script>tinymce.init({ selector:'textarea#notificaciones', language: 'es', menubar: false, });</script>
    <hr>
    <h4><b>Notificaciones Personalizadas</b></h4>
     <div class="row mb-5 border-bottom">
      <div class="col-md-12" style="text-align: center;">        
        <textarea class="form-control form-control-solid" id="notificaciones" name="notificaciones"><?php if (isset($registros[0]['notificaciones'])) { echo $registros[0]['notificaciones']; } ?></textarea>
      </div>
    </div>
    <script>
        $("iframe").attr("title", "");        
    </script>
    <?php } ?>
     <div class="row mb-5 border-bottom">
      <div class="col-md-12" style="text-align: center;">
        <input  class="btn btn-primary text-inverse-primary margin-top-20" type="submit" value="GUARDAR" />
      </div>
    </div>
  </form>
  <!-- END FORMULARIO -->
    </div>
    <div class="panel with-nav-tabs panel-default">
        <div class="panel-heading">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab1default" data-toggle="tab">Historial Actual</a></li>
                <li><a href="#tab2default" data-toggle="tab">Historial Antiguo</a></li>
                <li><a href="#tab3default" data-toggle="tab">Carnets</a></li>
                <li><a href="#tab9default" data-toggle="tab">Fichas</a></li>
                <li><a href="#tab8default" data-toggle="tab">Asociados</a></li>
                <li><a href="#tab4default" data-toggle="tab">Otros Datos</a></li>
                <li><a href="#tab5default" data-toggle="tab">Notas Citas</a></li>
                <li><a href="#tab6default" data-toggle="tab">Notas Cobrar</a></li>
                <li><a href="#tab7default" data-toggle="tab">Saldo</a></li>
            </ul>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab1default">
                <!-- 11/05/20 -->
                   <div class="row mb-5 border-bottom">
                  <div class="col-md-4">
                    <label class="form-label">Desde</label>
                    <div class="input-icon">
                        <input name="fecha_desde" id="fecha_desde" type="date" value="" />
                     </div>
                   </div> 
                   <div class="col-md-4">
                    <label class="form-label">Hasta</label>
                    <div class="input-icon">
                        <input name="fecha_hasta" id="fecha_hasta" type="date" value="" />
                     </div>
                   </div> 
                    <div class="col-md-4">
                        <button class="btn btn-info text-inverse-info">
                            <a href="#" onclick="Exportar(<?php echo $registros[0]['id_cliente']; ?>)" style="color: #fff; text-decoration: none;">
                            Exportar CSV
                            </a>
                        </button>
                    </div>
                </div>
                <!-- Fin -->
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
                                   <!-- 19/06/20 -->
                                  <?php if ($row['id_ticket']>0 && $row['estado']=="Pagado") { ?>
                                    <br>
                                    (<b><a href="<?php echo base_url();?>dietario/ver_ticket/<?php echo $row['id_ticket'] ?>" target="_blank">ver ticket</a></b>)
                                <?php } ?>
                                  <!-- fin 19/06/20 -->
                                  <?php if ($row['id_pedido'] > 0) { ?>
                                      <img src="<?php echo base_url();?>recursos/images/online.png" />
                                  <?php } ?> 
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
                                  <?php if ($row['estado']=="Pagado" || $row['estado']=="Devuelto") { ?>
                                  <br>
                                  <div style="text-align: center; font-size: 11px;">
                                      <a href="#" onclick="Facturacion(<?php echo $row['id_cliente']; ?>,<?php echo $row['id_centro']; ?>);" style="color: red;">Generar Factura</a>
                                  </div>
                                  <?php if ($row['id_ticket']== 0 && $row['estado']=="Pagado") { ?>
                                      - <a href="#" onclick="Generarticket(<?php echo $row['id_cliente']; ?>,<?php echo $row['id_centro']; ?>);" style="color: red;">Generar Ticket</a>
                                  <?php } ?>
                                  <?php } ?>
                              </td>
                              <td class="text-end">
                                <?php if ($row['notas_pago_descuento']!="") { ?>
                                  <span style="cursor: pointer; cursor: hand;" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="<?php echo $row['notas_pago_descuento']; ?>">
                                <?php } ?>
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
                                  <?php if ($row['templos']>0 && $row['tipo_pago']=="#templos") { ?>
                                      <?php echo round($row['templos'],0); 
                                      $total_templos+=$row['templos']; 
                                      if ($row['foto_templo']!=null){ //20/05/20 Ver FOTO TEMPLO
                                        echo "<br>";
                                        ?>
                                       <!-- <img src="<<?php echo base_url().'recursos/foto/'.$row['foto_templo']; ?>" height="30px" width="30px" onclick="ver_foto('<?php echo $row['foto_templo']; ?>')" /> -->
                                        <a href="<?php echo base_url().'recursos/foto/'.$row['foto_templo']; ?>" data-lightbox="smile">  <img height="42" width="42" src="<?php echo base_url().'recursos/foto/'.$row['foto_templo']; ?>"> </a>
                                     <?php   
                                        }
                                     ?>
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
                                      <?php if ($row['estado']=="Pagado" && $row['id_pedido']==0) { ?>
                                        <?php if ($row['id_carnet'] > 0 && $row['recarga'] == 0) { ?>
                                        <br><a href="#" onclick="javascript:DevolucionCarnet('<?php echo $row['id_dietario'] ?>');" style="font-size: 12px; color: gray;"><b>(Devolver)</b></a>
                                        <?php } else { ?>
                                        <br><a href="#" onclick="javascript:Devolucion('<?php echo $row['id_dietario'] ?>');" style="font-size: 12px; color: gray;"><b>(Devolver)</b></a>
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
                <!-- Fichas 19/10/20        **************** Fichas************************* -->
                   <div class="tab-pane fade" id="tab9default">
                     <h3>Fichas</h3>
                     <?php
                     $completo="si";
                       if (isset($registros)){ //Datos del Cliente
                          if ($registros[0]['nombre']=="") { 
                             $nombre="";
                             $completo="no";
                          }
                          else {
                            $nombre=$registros[0]['nombre']; 
                          }
                          if ($registros[0]['apellidos']=="") { 
                             $apellidos="";
                             $completo="no";
                          }
                          else {
                            $apellidos=$registros[0]['apellidos']; 
                          }  
                          if ($registros[0]['dni']=="") { 
                             $dni="";
                             $completo="no";
                          }
                          else {
                            $dni=$registros[0]['dni']; 
                          }
                          if ($registros[0]['fecha_nacimiento_aaaammdd']=="") { 
                             $fecha_nacimiento_aaaammdd="";
                             $completo="no";
                          }
                          else {
                            $fecha_nacimiento_aaaammdd=$registros[0]['fecha_nacimiento_aaaammdd']; 
                          }
                         if ($registros[0]['telefono']=="") { 
                             $telefono="";
                             $completo="no";
                          }
                          else {
                            $telefono=$registros[0]['telefono']; 
                          }
                          if ($registros[0]['sexo']=="") { 
                             $sexo="";
                             $completo="no";
                          }
                          else {
                            $sexo=$registros[0]['sexo']; 
                          }
                         if ($registros[0]['ocupacion']=="") { 
                             $ocupacion="";
                             $completo="no";
                          }
                          else {
                            $ocupacion=$registros[0]['ocupacion']; 
                          }
                          if ($completo=="no"){
                             $xcolor="red";
                             $contenido="Incompleto";
                          }
                          else{
                             $xcolor="#337ab7";
                             $contenido="Completo";
                          }
                       } //if registro
                     ?>
                     <!--
                         <div class="row">
                             <div class="col-md-3">
                                 <h4 class="btn btn-primary">Datos Personales</h4>
                             </div>
                             <div class="col-md-3">
                                 <p style="color:<?php echo $xcolor; ?>; font-weight: bold;"><?php echo $contenido; ?></p>
                             </div>
                             <div class="col-md-3">
                             <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapse_form_ficha_cliente" aria-expanded="false" aria-controls="collapse_form_ficha_cliente">Editar
                              </button>
                             </div>
                             <div class="col-md-3">
                               <p style="color: blue; font-weight: bold;"><?php echo $registros[0]['fecha_modificacion_ddmmaaaa']; ?></p>
                             </div>
                         </div>
                         -->
                        <div>
                         <table class="table table-hover">
                         <thead>
                          <th>Secci&oacute;n</th>
                          <th>Estado</th>
                          <th>Acci&oacute;n</th>
                          <th>Fec. Actualizaci&oacute;n</th>
                          <th>Usuario</th>
                         </thead>
                         <tbody class="text-gray-700 fw-semibold">
                         <tr>
                         <td><strong>Datos personales</strong></td>
                         <td><span style="color:<?php echo $xcolor; ?>; font-weight: bold;"><?php echo $contenido; ?></span></td>
                         <td><button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapse_form_ficha_cliente" aria-expanded="false" aria-controls="collapse_form_ficha_cliente">Editar
                              </button>
                         </td>
                         <td><?php echo $registros[0]['fecha_modificacion_ddmmaaaa']; ?></td>
                         <td><?php echo $registros[0]['modificador']; ?></td>
                         </tr>
                         </tbody>
                         </table>
                        </div> 
                     <div class="collapse" id="collapse_form_ficha_cliente">
                          <!-- BEGIN FORMULARIO -->
                      <form id="form" action="<?php echo base_url();?>clientes/gestion/actualizar/<?php echo $registros[0]['id_cliente'] ?>" role="form" method="post" name="form" style="background: #f1f4f7; padding: 10px;">
                         <div class="row mb-5 border-bottom">
                          <div class="col-md-4">
                            <label class="form-label">Nombre *</label>
                            <div class="input-icon">
                              <i class="fa fa-user"></i>
                              <input name="nombre" class="form-control form-control-solid" type="text" value="<?php if (isset($registros)) { echo $registros[0]['nombre']; } ?>" placeholder="" required />
                            </div>
                          </div>
                          <div class="col-md-4">
                            <label class="form-label">Apellidos *</label>
                            <div class="input-icon">
                              <i class="fa fa-user"></i>
                              <input name="apellidos" class="form-control form-control-solid" type="text" value="<?php if (isset($registros)) { echo $registros[0]['apellidos']; } ?>" placeholder="" required/>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <label class="form-label">Email</label>
                              <div class="input-icon">
                                <i class="fa fa-envelope"></i>
                                <input name="email" class="form-control form-control-solid" type="email" value="<?php if (isset($registros)) { echo $registros[0]['email']; } ?>" placeholder="" />
                              </div>
                          </div>
                        </div>
                         <div class="row mb-5 border-bottom">
                          <div class="col-md-3">
                            <label class="form-label">Teléfono *</label>
                              <div class="input-icon">
                                <i class="fa fa-phone"></i>
                                  <input name="telefono" class="form-control form-control-solid" type="number" value="<?php if (isset($registros)) { echo $registros[0]['telefono']; } ?>" placeholder="" required/>
                                </div>
                          </div>
                          <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
                            <label class="form-label">Dirección</label>
                              <div class="input-icon">
                                <i class="fa fa-envelope"></i>
                                <input name="direccion" class="form-control form-control-solid" type="text" value="<?php if (isset($registros)) { echo $registros[0]['direccion']; } ?>" placeholder="" />
                              </div>
                          </div>
                          <div class="col-md-3">
                            <label class="form-label">Código Postal</label>
                              <div class="input-icon">
                                <i class="fa fa-envelope"></i>
                                <input name="codigo_postal" class="form-control form-control-solid" type="text" value="<?php if (isset($registros)) { echo $registros[0]['codigo_postal']; } ?>" placeholder="" />
                              </div>
                          </div>
                        </div>
                         <div class="row mb-5 border-bottom">
                            <div class="col-md-3">              
                                <label for="fecha_nacimiento"><h5>Fecha de Nacimiento *</h5></label>
                                <input class="form-control form-control-solid" id="fecha_nacimiento" name="fecha_nacimiento" type="date" value="<?php if (isset($registros[0]['fecha_nacimiento_aaaammdd'])) { echo $registros[0]['fecha_nacimiento_aaaammdd']; } ?>">
                            </div>
                            <div class="col-md-2">              
                                <label for="fecha_nacimiento"><h5>D.N.I *</h5></label>
                                <input class="form-control form-control-solid" id="dni" name="dni" type="text" value="<?php if (isset($registros[0]['dni'])) { echo $registros[0]['dni']; } ?>" maxlength="9" style="text-transform:uppercase" required="">
                            </div>
                             <div class="col-md-4">              
                                <label for="sexo"><h5>Sexo *</h5></label>
                                <select name="sexo" id="sexo" class="form-control form-control-solid" >
                                  <option value="Femenino" <?php if($registros[0]['sexo']=="Femenino"){ ?>selected="" <?php } ?>>Femenino</option>
                                  <option value="Masculino" <?php if($registros[0]['sexo']=="Masculino"){ ?>selected="" <?php } ?>>Masculino</option>
                                </select>
                            </div>
                            <div class="col-md-3">              
                                <label for="ocupacion"><h5>Ocupaci&oacute;n *</h5></label>
                                <input class="form-control form-control-solid" id="ocupacion" name="ocupacion" type="text" value="<?php if (isset($registros[0]['ocupacion'])) { echo $registros[0]['ocupacion']; } ?>" required="">
                            </div>
                        </div>
                          <div class="row mb-5 border-bottom">
                          <div class="col-md-12">
                            <label class="form-label">Notas</label>
                            <textarea class="form-control form-control-solid" name="notas"><?php if (isset($registros[0]['notas'])) { echo $registros[0]['notas']; } ?></textarea>        
                          </div>
                        </div>
                         <div class="row mb-5 border-bottom">
                          <div class="col-md-12" style="text-align: center;">
                            <input  class="btn btn-primary text-inverse-primary margin-top-20" type="submit" value="GUARDAR" />
                          </div>
                        </div>
                      </form>
                      <!-- END FORMULARIO -->
                     </div>
                     <!-- Ficha Salud, añadir -->
                        <?php if ($completo=="si"){ ?>
                         <div>
                           <h4>Datos de Salud</h4> 
                           <a class="btn btn-transparent green btn-outline btn-circle btn-sm active"
                           data-toggle="collapse" data-target="#collapse_form_datos_salud" aria-expanded="false" aria-controls="collapse_form_datos_salud" >
                           <i class="fa fa-plus" ></i>
                           </a>
                         </div>
                        <?php } ?>
                        <!-- Lista fichas de salud -->
                        <?php if(isset($salud)){
                           if ($count($salud)>0){
                            ?>
                           <div>
                         <table class="table table-hover">
                         <thead>
                          <th>Acci&oacute;n</th>
                          <th>Fec/Creaci&oacute;n</th>
                          <th>Creador</th>
                          <th>Fec/Modificaci&oacute;n</th>
                          <th>Modificado</th>
                         </thead>
                         <tbody class="text-gray-700 fw-semibold">
                        <?php foreach ($salud as $ficha){
                            $hoy=Date('Y-m-d');
                            $fecha_actual=new DateTime($hoy);
                            $fecha_ultima=new DateTime($ficha['fecha_creacion']);
                            $diferencia=$fecha_ultima->diff($fecha_actual);
                            $endias=$diferencia->days;
                            ?>
                        <tr>
                           <td><button class="btn btn-primary" onclick="Ver_FichaSalud('<?php echo $ficha['id']; ?>','ver')">Ver</button> / 
                           <?php
                             if ($endias<180){
                            ?>    
                           <button class="btn btn-primary" onclick="Ver_FichaSalud('<?php echo $ficha['id']; ?>','editar')">Editar</button>
                           <?php
                            }
                            else{
                            ?>    
                            <button class="btn btn-primary" onclick="Ver_FichaSalud('<?php echo $ficha['id']; ?>','clonar')">Clonar</button>
                            <?php
                              }
                            ?>  
                           </td>
                           <td><?php echo date('d/m/Y',strtotime($ficha['fecha_creacion']) ); ?></td>
                            <td><?php echo $ficha['creador']; ?></td> 
                            <td><?php echo date('d/m/Y',strtotime($ficha['fecha_modificacion']) ); ?></td>
                            <td><?php echo $ficha['modificador']; ?></td>
                        </tr>    
                        <?php } ?>
                         </tbody>
                         </table>
                         </div>
                        <?php } }?>
                        <!-- Fin lista de ficha de salud-->
                     <!-- para rellenar datos de salud -->
                      <div class="collapse" id="collapse_form_datos_salud">
                          <!-- BEGIN FORMULARIO -->
                      <form id="form" action="<?php echo base_url();?>clientes/salud/nuevo/<?php echo $registros[0]['id_cliente'] ?>" role="form" method="post" name="form2" style="background: #f1f4f7; padding: 10px;" onsubmit="return EsOk();">
                         <!--- ******************* campo ************************* -->
                         <div class="row mb-5 border-bottom">
                          <div class="col-md-4">
                            <label class="form-label">Enfermedades Pasadas</label>  
                          </div>
                          <div class="col-md-4">
                             <label class="radio-inline"><input type="radio" name="pasadas" id="pasadas1" value="no" onclick="elegir('pasadas','no')"  checked>no</label>
                             <label class="radio-inline"><input type="radio" name="pasadas"id="pasadas2" value="si" onclick="elegir('pasadas','si')"  >si: Cáncer, hepatitis, ...</label>
                          </div>
                          <div class="col-md-4" id="t_pasadas" style="display: none;">
                            <label class="form-label">Cuáles, cuándo se curó</label>
                              <textarea class="form-control form-control-solid" name="notas_pasadas" id="notas_pasadas">  </textarea>      
                          </div>
                        </div>
                        <!--- ******************* campo ************************* -->
                         <div class="row mb-5 border-bottom">
                          <div class="col-md-4">
                            <label class="form-label">Enfermedades Actuales</label>  
                          </div>
                          <div class="col-md-4">
                             <label class="radio-inline"><input type="radio" name="actuales" id="actuales1" value="no" onclick="elegir('actuales','no')"  checked>no</label>
                             <label class="radio-inline"><input type="radio" name="actuales"id="actuales2" value="si" onclick="elegir('actuales','si')"  >si: Cáncer, VIH, hepatitis, autoinmunes, diabetes, tensión, ...</label>
                          </div>
                          <div class="col-md-4" id="t_actuales" style="display: none;">
                            <label class="form-label">Desde cuándo</label>
                              <textarea class="form-control form-control-solid" name="notas_actuales" id="notas_actuales">  </textarea>      
                          </div>
                        </div>
                        <!--- ******************* campo ************************* -->
                         <div class="row mb-5 border-bottom">
                          <div class="col-md-4">
                            <label class="form-label">Toma Medicamentos Actualmente</label>  
                          </div>
                          <div class="col-md-4">
                             <label class="radio-inline"><input type="radio" name="medicamentos" id="medicamentos1" value="no" onclick="elegir('medicamentos','no')"  checked>no</label>
                             <label class="radio-inline"><input type="radio" name="medicamentos"id="medicamentos2" value="si" onclick="elegir('medicamentos','si')"  >si: Aspirina, diuréticos, corticoides, antiinflamatorios, antibióticos, antidepresivos, ansiolíticos, somníferos,...</label>
                          </div>
                          <div class="col-md-4" id="t_medicamentos" style="display: none;">
                            <label class="form-label">Desde cuándo, cantidad</label>
                              <textarea class="form-control form-control-solid" name="notas_medicamentos" id="notas_medicamentos">  </textarea>      
                          </div>
                        </div>
                        <!--- ******************* campo ************************* -->
                         <div class="row mb-5 border-bottom">
                          <div class="col-md-4">
                            <label class="form-label">Toma Suplementos Actualmente</label>  
                          </div>
                          <div class="col-md-4">
                             <label class="radio-inline"><input type="radio" name="suplementos" id="suplementos1" value="no" onclick="elegir('suplementos','no')"  checked>no</label>
                             <label class="radio-inline"><input type="radio" name="suplementos"id="suplementos2" value="si" onclick="elegir('suplementos','si')"  >si: Vitaminas, diuréticos, homeopatía,...</label>
                          </div>
                          <div class="col-md-4" id="t_suplementos" style="display: none;">
                            <label class="form-label">Cuales, frecuencia, cantidad</label>
                              <textarea class="form-control form-control-solid" name="notas_suplementos" id="notas_suplementos">  </textarea>      
                          </div>
                        </div>
                        <!--- ******************* campo ************************* -->
                         <div class="row mb-5 border-bottom">
                          <div class="col-md-4">
                            <label class="form-label">Intervenciones Quir&uacute;rgicas</label>  
                          </div>
                          <div class="col-md-4">
                             <label class="radio-inline"><input type="radio" name="intervenciones" id="intervenciones1" value="no" onclick="elegir('intervenciones','no')"  checked>no</label>
                             <label class="radio-inline"><input type="radio" name="intervenciones"id="intervenciones2" value="si" onclick="elegir('intervenciones','si')"  >si: cirugía general, cirugía estética o plástica de reconstrucción, fracturas, esguinces,...</label>
                          </div>
                          <div class="col-md-4" id="t_intervenciones" style="display: none;">
                            <label class="form-label">Qué, cuándo dónde</label>
                              <textarea class="form-control form-control-solid" name="notas_intervenciones" id="notas_intervenciones">  </textarea>      
                          </div>
                        </div>
                        <!--- ******************* campo ************************* -->
                         <div class="row mb-5 border-bottom">
                          <div class="col-md-4">
                            <label class="form-label">Implantes o Dispositivos</label>  
                          </div>
                          <div class="col-md-4">
                             <label class="radio-inline"><input type="radio" name="implantes" id="implantes1" value="no" onclick="elegir('implantes','no')"  checked>no</label>
                             <label class="radio-inline"><input type="radio" name="implantes"id="implantes2" value="si" onclick="elegir('implantes','si')"  >si</label>
                          </div>
                          <div class="col-md-4" id="t_implantes" style="display: none;">
                            <label class="form-label">Cuál y dónde</label>
                              <textarea class="form-control form-control-solid" name="notas_implantes" id="notas_implantes">  </textarea>      
                          </div>
                        </div>
                        <!--- ******************* campo ************************* -->
                         <div class="row mb-5 border-bottom">
                          <div class="col-md-4">
                            <label class="form-label">Alergias</label>  
                          </div>
                          <div class="col-md-4">
                             <label class="radio-inline"><input type="radio" name="alergias" id="alergias1" value="no" onclick="elegir('alergias','no')"  checked>no</label>
                             <label class="radio-inline"><input type="radio" name="alergias"id="alergias2" value="si" onclick="elegir('alergias','si')"  >si</label>
                          </div>
                          <div class="col-md-4" id="t_alergias" style="display: none;">
                            <label class="form-label">Cuales</label>
                              <textarea class="form-control form-control-solid" name="notas_alergias" id="notas_alergias">  </textarea>      
                          </div>
                        </div>
                        <!--- ******************* campo ************************* -->
                         <div class="row mb-5 border-bottom">
                          <div class="col-md-4">
                            <label class="form-label">Fumador</label>  
                          </div>
                          <div class="col-md-8">
                             <label class="radio-inline"><input type="radio" name="fumador" id="fumador1" value="nunca"  checked>Nunca</label>
                             <label class="radio-inline"><input type="radio" name="fumador" id="fumador2" value="rara vez"  >Rara vez</label>
                             <label class="radio-inline"><input type="radio" name="fumador"id="fumador3" value="habitual" >Habitual</label>
                             <label class="radio-inline"><input type="radio" name="fumador"id="fumador4" value="mucho" >Mucho</label>
                          </div>
                          <!--
                          <div class="col-md-4" id="t_fumador" style="display: none;">
                            <label class="form-label">Notas</label>
                              <textarea class="form-control form-control-solid" name="notas_fumador" id="notas_fumador">  </textarea>      
                          </div>
                          -->
                        </div>
                        <!--- ******************* campo ************************* -->
                         <div class="row mb-5 border-bottom">
                          <div class="col-md-4">
                            <label class="form-label">Alcohol</label>  
                          </div>
                          <div class="col-md-8">
                             <label class="radio-inline"><input type="radio" name="alcohol" id="alcohol1" value="nunca"  checked>Nunca</label>
                             <label class="radio-inline"><input type="radio" name="alcohol" id="alcohol2" value="rara vez"  >Rara vez</label>
                             <label class="radio-inline"><input type="radio" name="alcohol"id="alcohol3" value="habitual" >Habitual</label>
                             <label class="radio-inline"><input type="radio" name="alcohol"id="alcohol4" value="mucho" >Mucho</label>
                          </div>
                          <!--
                          <div class="col-md-4" id="t_alcohol" style="display: none;">
                            <label class="form-label">Notas</label>
                              <textarea class="form-control form-control-solid" name="notas_alcohol" id="notas_alcohol">  </textarea>      
                          </div>
                          -->
                        </div>
                        <!--- ******************* campo ************************* -->
                         <div class="row mb-5 border-bottom">
                          <div class="col-md-4">
                            <label class="form-label">Drogas</label>  
                          </div>
                          <div class="col-md-4">
                             <label class="radio-inline"><input type="radio" name="drogas" id="drogas1" value="no" onclick="elegir('drogas','no')"  checked>no</label>
                             <label class="radio-inline"><input type="radio" name="drogas"id="drogas2" value="si" onclick="elegir('drogas','si')"  >si</label>
                          </div>
                          <div class="col-md-4" id="t_drogas" style="display: none;">
                            <label class="form-label">Cuáles y con qué frecuencia</label>
                              <textarea class="form-control form-control-solid" name="notas_drogas" id="notas_drogas">  </textarea>      
                          </div>
                        </div>
                        <!--- ******************* campo ************************* -->
                         <div class="row mb-5 border-bottom">
                          <div class="col-md-4">
                            <label class="form-label">Anticonceptivos</label>  
                          </div>
                          <div class="col-md-4">
                             <label class="radio-inline"><input type="radio" name="anticonceptivos" id="anticonceptivos1" value="no" onclick="elegir('anticonceptivos','no')"  checked>no</label>
                             <label class="radio-inline"><input type="radio" name="anticonceptivos"id="anticonceptivos2" value="si" onclick="elegir('anticonceptivos','si')"  >si</label>
                          </div>
                          <div class="col-md-4" id="t_anticonceptivos" style="display: none;">
                            <label class="form-label">Desde cuándo</label>
                              <textarea class="form-control form-control-solid" name="notas_anticonceptivos" id="notas_anticonceptivos">  </textarea>      
                          </div>
                        </div>
                        <!--- ******************* campo ************************* -->
                         <div class="row mb-5 border-bottom">
                          <div class="col-md-4">
                            <label class="form-label">Embarazada o cree estarlo</label>  
                          </div>
                          <div class="col-md-4">
                             <label class="radio-inline"><input type="radio" name="embarazada" id="embarazada1" value="no" onclick="elegir('embarazada','no')"  checked>no</label>
                             <label class="radio-inline"><input type="radio" name="embarazada"id="embarazada2" value="si" onclick="elegir('embarazada','si')"  >si</label>
                          </div>
                          <div class="col-md-4" id="t_embarazada" style="display: none;">
                            <label class="form-label">Notas</label>
                              <textarea class="form-control form-control-solid" name="notas_embarazada" id="notas_embarazada">  </textarea>      
                          </div>
                        </div>
                        <!--- ******************* Firma ************************* -->
                         <div class="row mb-5 border-bottom">    
                          <div class="col-md-4">
                            <label class="form-label">Firma del cliente</label>          
                            <div class="firma" style="position: relative; width: 300px; height: 150px; -moz-user-select: none; -webkit-user-select: none; -ms-user-select: none; user-select: none;">
                                <canvas id="signature-pad" class="signature-pad" style="position: absolute; left: 0; top: 0; width: 300px; height: 150px; background-color: white;"></canvas>
                                <input type="hidden" name="firma_img" id="firma_img" value="" />
                            </div>
                            <span class="label label-sm label-danger">
                                <a href="#" id="clear" onclick="signaturePad.clear();return false;" style="color: #fff; font-weight: bold;">Borrar Firma</a>
                            </span>
                          </div>
                        </div>
                          <div class="row mb-5 border-bottom">
                          <div class="col-md-12" style="text-align: center;">
                            <input  class="btn btn-primary text-inverse-primary margin-top-20" type="submit" value="GUARDAR" />
                          </div>
                        </div>
                       </form>
                     </div>   
                    <!-- fin de rellenar datos de salud -->
                   </div>
                <!-- Fin -->
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
                <!-- 11/04/20 asociados -->
                <div class="tab-pane fade" id="tab8default">
                    <div style="text-align: left; margin-bottom: 5px;">
                       <div style="float: left; width: 78%;">
                    <input type="text" id="cliente" name="id_cliente"  />
                    <script type="text/javascript">
                    $(document).ready(function() {
                        $("#cliente").tokenInput("<?php echo RUTA_WWW; ?>/clientes/json/", {
                            hintText: "Elegir un cliente...",
                            noResultsText: "Sin resultados",
                            searchingText: "Buscando...",
                            minChars: 3,
                            tokenLimit: 1,
                            <?php if (isset($cliente_elegido)) { if ($cliente_elegido[0]['id_cliente'] > 0) { ?>
                            prePopulate: [
                                {id: <?php echo $cliente_elegido[0]['id_cliente'] ?>, name: "<?php echo $cliente_elegido[0]['nombre']." ".$cliente_elegido[0]['apellidos']." (".$cliente_elegido[0]['telefono'].")"; ?>" }
                            ]
                            <?php }} ?>
                        });
                    });
                    </script>
                </div> 
                <a href="#" onclick="anadir_socio(<?php echo $registros[0]['id_cliente']; ?>)" class="btn btn-primary text-inverse-primary margin-top-20" style="margin: 0px !important;"/>Añadir Asociado</a>
                      <!--  <a href="<?php echo base_url();?>clientes/nueva_nota_cobrar/<?php echo $id_cliente; ?>" class="btn btn-primary text-inverse-primary margin-top-20" style="margin: 0px !important;"/>Crear Nota</a> -->
                    </div>
                    <form id="form_asociados" action="#" method="post" name="form_asociados">
                        <table id="logs" class="table table-striped table-hover table-bordered">
                          <thead>
                            <tr>
                              <th>Cliente</th>
                              <th>Acción</th>
                            </tr>
                          </thead>
                          <tbody class="text-gray-700 fw-semibold">
                            <?php if (isset($asociados)) { if ($asociados != 0) { foreach ($asociados as $key => $row) { ?>
                            <tr>
                              <td>
                                <?php echo $row['cliente'] ?><br>
                              </td>
                              <?php if ($this->session->userdata('id_perfil') == 0 OR  $this->session->userdata('id_perfil') == 3 ) { ?>
                              <td style="text-align: center;">
                                <span class="label label-sm label-danger">
                                  <a href="#" onclick="BorrarAsociado(<?php echo $row['id_cliente'] ?>,<?php echo $row['id_asociado'] ?>);" style="color: #fff; font-weight: bold;">Borrar</a>
                                </span>
                              </td>
                              <?php } ?>
                            </tr>
                            <?php } } } ?>
                          </tbody>
                        </table>
                    </form>
                </div>
                <!-- fin -->
            </div>
        </div>
    </div>
  <!-- END FORMULARIO -->
</div>
<!-- END CONTENT BODY -->
<script>
//21/10/20 Fira Canvas
var canvas = document.getElementById('signature-pad');
// Adjust canvas coordinate space taking into account pixel ratio,
// to make it look crisp on mobile devices.
// This also causes canvas to be cleared.
function resizeCanvas() {
    // When zoomed out to less than 100%, for some very strange reason,
    // some browsers report devicePixelRatio as less than 1
    // and only part of the canvas is cleared then.
    var ratio =  Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
}
//window.onresize = resizeCanvas;
//resizeCanvas();
var signaturePad = new SignaturePad(canvas, {
  backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
});
function EsOk() {
    if (!signaturePad.isEmpty()) {
        var data = signaturePad.toDataURL('image/png');
        document.getElementById('firma_img').value = data;
        $(document).ready(function()
        {
            $("#mostrarmodal").modal("show");
        });
    }    
    return true;
}
//Fin 21/10/20 Canvas
//11/04/20 Asociados
function BorrarAsociado(id_cliente,id_asociado){
    //alert ('Borrar '+id_cliente+' '+id_asociado);
    document.location.href="<?php echo base_url();?>clientes/borrar_asociado/"+id_cliente+"/"+id_asociado;
    return false;
}
function anadir_socio(id_cliente){
    id_asociado=document.getElementById('cliente').value;
   if (id_asociado=="" || id_asociado==0 || id_asociado=="0")
       return false;
    else
       document.location.href="<?php echo base_url();?>clientes/nuevo_asociado/"+id_cliente+"/"+id_asociado;
    return false;
}
//Fin
//11/05/20
function Exportar(id_cliente){
    //fecha_desde=document.getElementById('fecha_desde').value;
    //fecha_hasta=document.getElementById('facha_hasta').value;
    var fecha_desde = $('#fecha_desde').val();
    var fecha_hasta = $('#fecha_hasta').val();
    console.log('Fechas '+fecha_desde+' '+fecha_hasta);
    if (fecha_desde=="" || fecha_hasta=="")
      document.location.href="<?php echo base_url();?>clientes/historial_csv/"+id_cliente;
    else{
      console.log('Fechas '+fecha_desde+' '+fecha_hasta);
      document.location.href="<?php echo base_url();?>clientes/historial_csv/"+id_cliente+"/"+fecha_desde+"/"+fecha_hasta;
    }   
    return false;
}
//Fin 11/05/20
    // 21/10/20 Ficha Salud **************************** Ficha Salud ***************
    function Ver_FichaSalud(id_ficha_salud,accion) {
        var posicion_x; 
        var posicion_y;
        var ancho=1000;
        var alto=800;
        posicion_x=(screen.width/2)-(ancho/2);
        posicion_y=(screen.height/2)-(alto/2);
        window.open("<?php echo base_url();?>clientes/ver_ficha/"+id_ficha_salud+"/"+accion, "_blank", "toolbar=no,scrollbars=no,resizable=no,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);
    }
   //Fin 21/10/20 Fin ************************** Ficha Salud *****************************
    function DevolucionCarnet(id_dietario) {
        var posicion_x; 
        var posicion_y;
        var ancho=600;
        var alto=450;
        posicion_x=(screen.width/2)-(ancho/2);
        posicion_y=(screen.height/2)-(alto/2);
        window.open("<?php echo base_url();?>dietario/devolucion_carnet/"+id_dietario, "_blank", "toolbar=no,scrollbars=no,resizable=no,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);
    }
    function Devolucion(id_dietario) {
        var posicion_x; 
        var posicion_y;
        var ancho=600;
        var alto=450;
        posicion_x=(screen.width/2)-(ancho/2);
        posicion_y=(screen.height/2)-(alto/2);
        window.open("<?php echo base_url();?>dietario/devoluciones/index/0/"+id_dietario, "_blank", "toolbar=no,scrollbars=no,resizable=no,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);
    }
    function Generarticket(id_cliente,id_centro_facturar) {
        var posicion_x;
        var posicion_y;
        var ancho=800;
        var alto=600;
        posicion_x=(screen.width/2)-(ancho/2);
        posicion_y=(screen.height/2)-(alto/2);
        window.open("<?php echo base_url();?>dietario/generarticket/"+id_cliente+"/"+id_centro_facturar, "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);        
    }
    function Facturacion(id_cliente,id_centro_facturar) {
        var posicion_x;
        var posicion_y;
        var ancho=800;
        var alto=600;
        posicion_x=(screen.width/2)-(ancho/2);
        posicion_y=(screen.height/2)-(alto/2);
        window.open("<?php echo base_url();?>dietario/facturacion/"+id_cliente+"/"+id_centro_facturar, "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);        
    }
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
