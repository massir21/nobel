<!DOCTYPE html>
<html lang="es">
<head>
        <meta charset="utf-8" />
        <title><?= SITETITLE ?></title>
        <script>        
        $(function() {          
          
          $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: '<Ant',
            nextText: 'Sig>',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
            weekHeader: 'Sm',
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''        
        };
        </script>
        <style>            
            .dataTables_filter {
                text-align: right;        
            }
            #carnets_del_cliente {
                display: none;                
            }
        </style>
    </head>        
    <body style="background: #fff !important;">
<div>
<div>
<div>
  <!-- BEGIN SAMPLE FORM PORTLET-->
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase">
            Pago en Templos: <?php echo $registros[0]['cliente']; ?>
          </span>
      </div>      
    </div>
<form id="form_carnets" action="<?php echo base_url();?>dietario/pagotemplos/comprobar_carnet/<?php echo $id_cliente; ?>/<?php echo $hoy_aaaammdd; ?>" role="form" method="post" name="form_carnets">
<?php if (isset($registros)) { if ($registros != 0) { ?>
    <div class="portlet-body" style="padding-top: 0px;">
        <div style="padding: 5px;">
            <b>Paso 1. Elige los servicios que deseas cobrar.</b>
        </div>
        <table id="myTable1"class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">            
            <th style="display: none;">ID</th>
            <th>Marcar</th>
            <th>Servicios</th>
            <th>Templos</th>            
          </tr>
        </thead>
        <tbody class="text-gray-700 fw-semibold">
          <?php $total_templos=0; ?>
          <?php if (isset($registros)) { if ($registros != 0) { foreach ($registros as $key => $row) { ?>
          <?php if ($row['carnet'] != "") { $row['color_estado']="#dee8fd"; } ?>
          <tr style="background: <?php echo $row['color_estado']; ?>;">
            <td style="display: none;">
                <?php echo $row['id_dietario']; ?>
            </td>
            <td style="text-align: center;">
                <?php $seleciona_servicio=""; if (isset($servicios_marcados)) {
                    foreach ($servicios_marcados as $cada) {
                        if ($cada == $row['id_dietario']) {
                            $seleciona_servicio = "checked";
                        }
                    }
                } ?>
                <input type="checkbox" class="form-control form-control-solid" id="servicios_marcados" name="servicios_marcados[]" value="<?php echo $row['id_dietario']; ?>" <?php if ($seleciona_servicio=="checked") { echo "checked"; } ?> />
            </td>
            <td style="text-align: center; width: 80%;">
                <?php if ($row['servicio'] != "") { echo $row['servicio']; } ?>
                <?php if ($row['producto'] != "") { echo $row['producto']; } ?>
                <?php if ($row['carnet'] != "") { echo $row['carnet']; } ?>
                <?php echo "<div style='font-size: 12px;'>".$row['fecha_hora_concepto_ddmmaaaa_abrev2']."</div>"; ?>
            </td>
            <td class="text-end">
                <?php if ($row['templos'] > 0) { ?>
                    <?php echo round($row['templos'],2); if ($row['templos'] > 0) { $total_templos+=$row['templos']; } ?>
                <?php } else { ?>
                    -
                <?php } ?>
            </td>            
          </tr>
          <?php } } } ?>          
        </tbody>
        <tfoot>
            <tr>
                <td style="text-align: right; padding: 8px;" colspan="2"><b>TEMPLOS TOTALES</b></td>
                <td style="text-align: right; padding: 8px;"><?php echo round($total_templos,2); ?></td>
            </tr>
        </tfoot>
      </table>
        <?php if (isset($mensaje_descuento)) { if ($mensaje_descuento != "") { ?>            
            <div class="alert alert-warning" style="width: 100%; color: #000;">
            <?php echo $mensaje_descuento; ?>
            </div>            
        <?php }} ?>
        <div style="overflow: hidden; margin: 0px !important;">
        <?php if (isset($notas_cobrar)) { if ($notas_cobrar != 0) { ?>
        <div style="width: 100%;">                
            <table id="tabla_notas" class="table table-striped table-hover table-bordered" style="background: #feeea3; border: 0px;">
              <thead>
                <tr>
                  <th style="display: none;"></th>
                  <th style="width: 1%; border: 0px;">Finalizar</th>                              
                  <th style="border: 0px;">NOTAS COBROS CARNET: <?php echo $carnet_cobrar; ?></th>
                </tr>
              </thead>
              <tbody class="text-gray-700 fw-semibold">
                <?php if (isset($notas_cobrar)) { if ($notas_cobrar != 0) { foreach ($notas_cobrar as $key => $row) { ?>
                <tr style="background: #feeea3; border: 0px;">
                  <td style="display: none;">
                    <?php echo $row['fecha_creacion_aaaammdd'] ?>
                  </td>
                  <td style="text-align: center; border: 0px; padding: 4px;" onclick="FinalizarNota(this,'<?php echo $row['id_nota_cobrar'] ?>');">
                    <input type="checkbox" id="id_nota_cobrar" value="<?php echo $row['id_nota_cobrar'] ?>" class="form-control form-control-solid" />
                  </td>                              
                  <td style="border: 0px; padding: 4px;">
                    <?php echo $row['fecha_creacion_ddmmaaaa'] ?><br>                            
                    <a href="#" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="<?php echo $row['nota'] ?>" style="color: blue;">
                        <?php echo substr($row['nota'], 0, 85); ?> ...
                    </a>                            
                  </td>                                      
                </tr>
                <?php } } } ?>
              </tbody>
            </table>
            <div id="borrar_nota" style="display: none;"></div>
        </div>
        <?php } } ?>                
        </div>
        <div style="padding: 5px;">
            <b>Paso 2. Selecciona el/los carnet/s para el pago.</b>
        </div>
        <?php if (isset($estado_carnet) && $puedo_pagar==0) { ?>                        
            <?php if ($estado_carnet == 0) {  ?>
            <div class="alert alert-danger" style="width: 98%;">
                No existe el carnet indicado
            </div>
            <?php } ?>
            <?php if ($estado_carnet == 1) {  ?>
            <div class="alert alert-success" style="width: 98%;">
                Carnet Válido, añadido.
            </div>
            <?php } ?>
            <?php if ($estado_carnet == 2) {  ?>
            <div class="alert alert-danger" style="width: 98%;">
                El carnet no dispone de ningún servicio de los pendientes de pago.
            </div>
            <?php } ?>
            <?php if ($estado_carnet == 3) {  ?>
            <div class="alert alert-danger" style="width: 98%;">
                El carnet no dispone de templos
            </div>                                            
            <?php } ?>
            <?php if ($estado_carnet == 4) {  ?>
            <div class="alert alert-danger" style="width: 98%;">
                El carnet sólo tiene algunos de los servicios necesarios para el pago.
            </div>                                            
            <?php } ?>            
        <?php } ?>
        <div style="overflow: hidden; margin-top: 10px; margin-bottom: 0px;">
            <div style="width: 100%; background: #f5f7ff; overflow: hidden; padding: 10px; padding-bottom: 0px;">
                <?php if ($puedo_pagar==0) { ?>                 
                    <div style="float: left;">
                        <input type="text" class="form-control form-control-solid" name="codigo" style="width: 140px;" placeholder="Nº Carnet" />
                    </div>
                    <div style="float: left; margin-left: 5px;">
                        <input class="btn btn-info text-inverse-info" type="button" value="Comprobar" onclick="Comprobar();" />                        
                    </div>
                    <div style="float: right; margin-left: 5px;">                        
                        <a href="#" onclick="VerCarnetsCliente();" style="font-size: 13px;">Ver/Ocultar Carnets Cliente</a>
                    </div>
                    <div id="carnets_del_cliente">                        
                        <table id="myTable1" class="table table-striped table-hover table-bordered">
                            <thead>
                              <tr>                                
                                <th>Carnet</th>
                                <th>Cliente</th>
                                <th>Templos</th>
                                <th>T. Disponibles</th>                                
                                <th>Notas</th>
                              </tr>
                            </thead>
                            <tbody class="text-gray-700 fw-semibold">
                              <?php if (isset($carnets_cliente)) { if ($carnets_cliente != 0) { foreach ($carnets_cliente as $key => $row) { ?>
                              <?php if ($row['id_tipo']==99 || $row['templos_disponibles'] > 0) { ?>                              
                              <tr>                                
                                <td style="text-align: center;">              
                                    <a href="#" onclick="VerCarnetsPagos(<?php echo $row['id_carnet'] ?>);">
                                        <b>
                                        <?php echo $row['codigo']; ?>
                                        </b>                                        
                                    </a>
                                    <a href="javascript:document.form_carnets.codigo.value='<?php echo $row['codigo']; ?>'; VerCarnetsCliente();" style="color: green;">elegir</a>
                                </td>
                                <td>
                                    <?php echo $row['cliente']; ?>
                                </td>
                                <!--<td style="text-align: center;">              
                                  <?php echo $row['tipo']; ?>
                                </td>            -->
                                <td style="text-align: center;">              
                                  <?php if ($row['id_tipo']!=99) { echo round($row['templos'],2,1)." templos"; } else { echo "Especial"; } ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php if ($row['id_tipo']!=99) { echo round($row['templos_disponibles'],2,1); } else { echo "-"; } ?>              
                                </td>                                
                                <td style="text-align: left;">              
                                  <?php echo $row['notas']; ?>
                                </td>                                
                              </tr>
                              <?php } ?>          
                              <?php } } } ?>          
                            </tbody>
                          </table>
                            <hr>
                    </div>                
                <?php } else { ?>
                <div class="alert alert-success" style="width: 98%;">
                    Ya puedes proceder con el pago.
                </div>
                <?php } ?>
            </div>
            <div style="width: 100%; background: #f5f7ff; overflow: hidden; padding: 10px; padding-top: 0px;">
                <?php $total_templos_carnets=0; if (isset($carnets_elegidos)) { if ($carnets_elegidos != 0) { ?>
                <table id="datos" class="table table-striped table-hover table-bordered">
                    <tr>
                        <th>Carnet</th>
                        <th>T. Disponibles</th>
                        <th>Recargar</th>
                        <th>Borrar</th>
                    </tr>
                    <?php if (isset($carnets_elegidos)) { if ($carnets_elegidos != 0) { foreach ($carnets_elegidos as $key => $row) { ?>
                    <tr>
                        <td>
                            <b><?php echo $row['codigo']; ?></b>
                            <br>
                            <?php echo $row['cliente']; ?>
                            <?php if ($row['notas']!="") { echo "<br>Notas: ".$row['notas']; } ?>
                        </td>
                        <td style="text-align: center;">
                            <?php if ($row['id_tipo']==99) { ?>
                                Especial
                            <?php } else { ?>
                                <?php echo $row['templos_disponibles']; $total_templos_carnets+=$row['templos_disponibles']; ?>
                            <?php } ?>                                                        
                        </td>                    
                        <td style="text-align: center;">
                            <?php if ($row['id_tipo']==99) { ?>
                            -
                            <?php } else { ?>
                            <input type="number" id="templos_recarga" min="0.5" max="19.5" step="0.5" value="" style="width: 60px;" />
                            <span class="label label-sm label-success">
                                <a href="#" onclick="RecargaCarnet(document.getElementById('templos_recarga').value,<?php echo $row['id_carnet']; ?>);" style="color: #fff; font-weight: bold;" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Recargar carnet">
                                +
                                </a>
                            </span>                                                  
                            <?php } ?>            
                        </td>
                        <td style="text-align: center;">
                            <span class="label label-sm label-danger">
                                <a href="<?php echo base_url();?>dietario/pagotemplos/ver/<?php echo $id_cliente; ?>/<?php echo $hoy_aaaammdd; ?>/<?php echo $row['id']; ?>" style="color: #fff; font-weight: bold;">
                                X
                                </a>
                            </span>                                                  
                        </td>
                    </tr>
                    <?php } } } ?>
                </table>
                <?php } } ?>            
            </div>
        </div>
        <?php if ($puedo_pagar==1) { ?> 
        <div style="text-align: center; margin-top: 20px;">
            <input type="button" class="btn btn-primary text-inverse-primary" onclick="MarcarPagoTemplos();" value="Pagar los servicios Marcados">
        </div>
        <?php } else { if ($accion != "ver") { ?>          
        <div class="alert alert-danger">
            <center>            
            <?php if ($templos_por_pagar>0 && $templos_por_pagar <= 11) { ?>
                <div>Te faltan <?php echo $templos_por_pagar ?> templos
                <br>
                ó
                    <br>
                    Puedes indicar un carnet que cubra el servicio a pagar.
                </div>
            <?php } ?>
            <?php if ($templos_por_pagar>11) { ?>
                <div>
                    Debes de comprar un carnet porque te faltan <?php echo $templos_por_pagar; ?> templos
                    <br>
                    ó
                    <br>
                    Puedes indicar un carnet que cubra el servicio a pagar.
                </div>
            <?php } ?>
            </center>
        </div>
        <?php }} ?>
        </form>
    </div>
  </div>
  <!-- END SAMPLE FORM PORTLET-->
<?php } else { ?>
    <div class="alert alert-danger">
        <center>
        No hay servicios para pagar con templos
        </center>
    </div>
    <div style="text-align: center; margin-top: 20px;">
        <input type="button" class="btn btn-warning text-inverse-warning" onclick="Cerrar();" value="Terminar" />
    </div>
<?php }} ?>
</div>
</div>
</div>
<!-- END CONTENT BODY -->
</body>
</html>
<script>
    <?php if (isset($registros)) { if ($registros != 0) { ?>
        <?php if ($puedo_pagar==0) { ?> 
        document.getElementById("carnets_del_cliente").style.display = "none";
        <?php } ?>
    <?php }} ?>
    function MarcarPagoTemplos() {
        formulario = document.getElementsByName("servicios_marcados[]");
        sw=0;
        for(var i=0; i<formulario.length; i++) {            
            if(formulario[i].checked) {
                sw = 1;
            } 
        }
        if (sw==1) {
            document.form_carnets.action = '<?php echo base_url();?>dietario/pagotemplos/marcarpago/<?php echo $id_cliente; ?>/<?php echo $hoy_aaaammdd; ?>';
            document.form_carnets.submit();
            return true;
        }
        else {
            alert("DEBES DE INDICAR AL MENOS UN SERVICIO PARA EL PAGO");
            return false;
        }        
    }
    function Comprobar() {
        formulario = document.getElementsByName("servicios_marcados[]");
        sw=0;
        for(var i=0; i<formulario.length; i++) {            
            if(formulario[i].checked) {
                sw = 1;
            } 
        }
        if (sw==1) {
            document.form_carnets.submit();
            return true;
        }
        else {
            alert("DEBES DE INDICAR AL MENOS UN SERVICIO PARA EL PAGO");
            return false;
        }
    }
    function Cerrar() {                
        window.opener.location.reload();        
        window.close();
    }
    function RecargaCarnet(templos,id_carnet) {
        if (templos < 0.5 || templos > 19.5) {
            alert("El número de templos está fuera de rango");
            return false;
        }
        else {
            sessionStorage.setItem("recarga_templos", "true");      
            document.location.href='<?php echo base_url();?>dietario/recargar_carnet/<?php echo $id_cliente; ?>/<?php echo $hoy_aaaammdd; ?>/'+id_carnet+'/'+templos;
        }
        // PagoEfectivo();
    }
    window.onload = function() {
        var reloading = sessionStorage.getItem("recarga_templos");
        if (reloading) {
            sessionStorage.removeItem("recarga_templos");
            PagoEfectivo();
        }
    }
    function VerCarnetsCliente() {        
        if (document.getElementById("carnets_del_cliente").style.display == "none") {
            document.getElementById("carnets_del_cliente").style.display = "block";
        }
        else {
            document.getElementById("carnets_del_cliente").style.display = "none";
        }        
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
    function PagoEfectivo() {    
        var posicion_x; 
        var posicion_y;
        var ancho=750;
        var alto=570;
        posicion_x=(screen.width/2)-(ancho/2);
        posicion_y=(screen.height/2)-(alto/2);
        window.open("<?php echo base_url();?>dietario/pagoeuros/ver_recargas/<?php echo $id_cliente; ?>/<?php echo $hoy_aaaammdd ?>", "_blank", "toolbar=no,scrollbars=no,resizable=no,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);
    }
    function FinalizarNota(fila,id_nota_cobrar) {        
        if (confirm("¿DESEA FINALIZAR LA NOTA DE COBRO MARCADA?")) {            
            document.getElementById("tabla_notas").deleteRow(fila.parentNode.rowIndex);
            $.ajax({url: "<?php echo base_url();?>clientes/finalizar_una_nota_cobrar/"+id_nota_cobrar, success: function(result){
                $("#borrar_nota").html(result);
            }});
            var f = document.getElementById("tabla_notas").rows.length;
            if (f==1) {
                document.getElementById("tabla_notas").deleteRow(0);
            }
        }        
    }
    $( document ).tooltip({
      position: {
        my: "center bottom-20",
        at: "center top",
        using: function( position, feedback ) {
          $( this ).css( position );
          $( "<div>" )
            .addClass( "arrow" )
            .addClass( feedback.vertical )
            .addClass( feedback.horizontal )
            .appendTo( this );
        }
      }
    });
    <?php if ($accion == "marcarpago") { ?>
        Cerrar();
    <?php } ?>    
</script> 