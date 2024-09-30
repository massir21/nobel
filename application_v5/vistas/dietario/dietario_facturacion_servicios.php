<?php $color = "warning";
  $xdato = "Completos";
  $xestado = "";
  if ($cliente[0]['empresa'] == "" or $cliente[0]['cif_nif'] == "" or $cliente[0]['direccion_facturacion'] == "" or $cliente[0]['codigo_postal_facturacion'] == "" or $cliente[0]['localidad_facturacion'] == "" or $cliente[0]['provincia_facturacion'] == "") {
    $color = "warning";
    $xdato = "Incompletos";
    $xestado = "disabled";
  }
  ?>

<style>
    .tableFixHead          { overflow: auto; height: 90vh; padding: 0px;}
.tableFixHead thead th { position: sticky; top: 0; z-index: 1; background-color: white; }

/* Just common table stuff. Really. */
table  { border-collapse: collapse; width: 100%; }
th, td { padding: 8px 16px; }
th     { background:#eee; }
.tr_sel{ background-color: #DEFFCA;}
.menosmenos{ color:red;}
.masmas{ color:green;}

  </style>
<div class="row" style="display: none;">
  <h2 style="text-align: right;">Saldo pendiente de facturar <br><span id="saldo_cliente"><?= $saldo_cliente ?></span> €</h2>
</div>
<form name="form" id="form" 
action="<?php echo base_url(); ?>dietario/factura_crear/<?php echo $cliente[0]['id_cliente'] ?>/<?php echo $id_centro_facturar ?>/2" 
method="POST">


  <!-- DATOS DE FACTURA -->
    <!-- fecha_emision -->
    <input type="hidden" name="id_cliente" value="<?= $id_cliente ?>">
    <input type="hidden" name="id_centro" value="<?= $id_centro_facturar ?>">
    <!-- numero_factura -->
    <input type="hidden" id="factura_importe" name="importe" value="0">
    <input type="hidden" id="factura_descuento" name="descuento" value="0">
    <input type="hidden" id="factura_iva" name="iva" value="0">
    <input type="hidden" name="irpf" value="0">
    <input type="hidden" id="factura_total" name="total" value="0">
    <input type="hidden" name="id_usuario_creacion" value="<?= $id_usuario ?>">
    <!-- fecha_facturacion -->
    <input type="hidden" name="id_usuario_modificacion" value="<?= $id_usuario ?>">
    <!-- fecha_modificacion -->


  <div class="table-responsive tableFixHead" style=" max-height: 45vh;">
    <table id="conceptos" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
      <thead class=""  >
        <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
          <th></th>
          <th>Serv/Prod</th>
          <th>PVP</th>
          <th>Descuento.</th>
          <th>IVA</th>
          <th>Total</th>
          <th style="display: none;">Facturar</th>
        </tr>
      </thead>
      <tbody class="text-gray-700 fw-semibold tbody_conceptos" style="margin-top: 20px;">
        <?php
        $total_importe = 0;
        $total_templos = 0;
        $sw_citas = 0;
        $sw_citas_online = 0;
        //show_array($servicios);
        if (isset($servicios)){
          if ($servicios != 0) {
            $ii=0;
            foreach($servicios as $s){
              if($s->facturado>0){ continue;}
              if($s->pvp==0){ continue;}
              //$descuento = $s->descuento_euros + ($s->pvp * $s->descuento_porcentaje / 100);
              //ok aqui funciona cuando se especifica desde el cobro pero que pasa cuando el duescuento viene desde el presupuesto
              $descuento = $s->descuento_euros+($s->importe_euros*($s->descuento_porcentaje/100));
              $importe_pagado = ($s->importe_euros) - $descuento;
              
              if($s->id_presupuesto!=0){
                $descuento = $s->pvp-$s->importe_euros;
                $importe_pagado = ($s->pvp) - $descuento;
              }
              $s->descuento_porcentaje = round($descuento/$s->pvp*100,2);
              
              $iva = $s->iva;
              
              
              
        ?>
              <tr>
                <td>
                  <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                    <input class="form-check-input check_concepto" type="checkbox" name="marcar[]" 
                    importe='<?= $importe_pagado ?>'
                    iva='<?= $s->iva ?>'
                    descuento_porcentaje='<?= $s->descuento_porcentaje ?>'
                    id_dietario='<?= $s->id_dietario ?>' />
                  </div>
                </td>
                <td>
                  <?php $concepto = $s->nombre_servicio."(".$s->nombre_familia.")"; ?>
                  <?= $concepto.$s->descuento_porcentaje ?>
                </td>
                <td>
                  <?= number_format($s->pvp, 2, ",", ".") . '€' ?>
                </td>
                <td>
                  <?= number_format($descuento, 2, ",", ".") . '€' ?>
                </td>
                <td>
                  <?= number_format($iva, 2, ",", ".") . '€' ?>
                </td>
                <td class="text-end">
                  <?php 
                  if ($importe_pagado != 0) { ?>
                    <?php echo number_format($importe_pagado, 2, ",", ".") . '€';
                    if ($importe_pagado != 0) {
                      $total_importe += $importe_pagado;
                    } ?>
                  <?php } else { ?>
                    -
                  <?php }?>
                </td>
                <td class="facturar_<?= $s->id_dietario ?>" style="text-align: right; display: none;">0</td>
              </tr>
              <tr style="display: none;">
                <td colspan="8">
                  descripcion
                  <input type="hidden" class="input_concepto_<?= $s->id_dietario ?>" disabled="disabled"
                  name="conceptos[<?= $ii ?>][descripcion]" value="<?= $concepto ?>">
                  importe
                  <input type="hidden" class="input_concepto_<?= $s->id_dietario ?>" disabled="disabled"
                  name="conceptos[<?= $ii ?>][importe]"  id="input_importe_<?= $s->id_dietario ?>" value="<?= $s->pvp ?>">
                  iva 
                  <input type="hidden" class="input_concepto_<?= $s->id_dietario ?>" disabled="disabled"
                  name="conceptos[<?= $ii ?>][iva]" value="<?= $s->iva ?>">
                  iva_euros
                  <input type="hidden" class="input_iva_conceptos input_concepto_<?= $s->id_dietario ?>" disabled="disabled"
                  name="conceptos[<?= $ii ?>][iva_euros]"  id="input_iva_<?= $s->id_dietario ?>" value="0">
                  irpf
                  <input type="hidden" class="input_concepto_<?= $s->id_dietario ?>" disabled="disabled"
                  name="conceptos[<?= $ii ?>][irpf]" value='0'>
                  irpf_euros
                  <input type="hidden" class="input_concepto_<?= $s->id_dietario ?>" disabled="disabled"
                  name="conceptos[<?= $ii ?>][irpf_euros]"  value='0'>
                  descuento_euros
                  <input type="hidden" class="input_descuento_conceptos input_concepto_<?= $s->id_dietario ?>" disabled="disabled"
                  name="conceptos[<?= $ii ?>][descuento_euros]" id="input_descuento_<?= $s->id_dietario ?>" value="<?= $descuento ?>">
                  descuento_porcentaje
                  <input type="hidden" class="input_concepto_<?= $s->id_dietario ?>" disabled="disabled"
                  name="conceptos[<?= $ii ?>][descuento_porcentaje]" value="<?= $s->descuento_porcentaje ?>">
                  total
                  <input type="hidden" class="input_concepto_<?= $s->id_dietario ?>" disabled="disabled"
                  name="conceptos[<?= $ii ?>][total]" id="input_total_<?= $s->id_dietario ?>" value="0">
                  id_usuario_creacion
                  <input type="hidden" class="input_concepto_<?= $s->id_dietario ?>" disabled="disabled"
                  name="conceptos[<?= $ii ?>][id_usuario_creacion]"  value="<?= $id_usuario ?>">
                  id_usuario_modificacion
                  <input type="hidden" class="input_concepto_<?= $s->id_dietario ?>" disabled="disabled"
                  name="conceptos[<?= $ii ?>][id_usuario_modificacion]"  value="<?= $id_usuario ?>">
                  <input type="hidden" class="input_concepto_<?= $s->id_dietario ?>" disabled="disabled"
                  name="conceptos[<?= $ii ?>][id_dietario]"  value="<?= $s->id_dietario ?>">
                  <?php echo "***".$ii."***";  ?>
                </td>
              </tr>
          <?php $ii++; }
          }
        } ?>
      </tbody>
    </table>
  </div>

  
  <div class="row" style="margin-top: 10px;">
  <h2 style="text-align: right;">Total factura <br><span id="total_factura">0</span> € <br><br></h2>
  <div class="col-sm-6" style="display: none;"><label>
    <input type="checkbox" id="check_pago_cuenta"> Agregar saldo restante como pago a cuenta</label>
    
                  <input type="hidden" class="input_concepto_0" disabled="disabled"
                  name="conceptos[<?= $ii ?>][descripcion]" value="Pago a cuenta">
                  
                  <input type="hidden" class="input_concepto_0" disabled="disabled"
                  name="conceptos[<?= $ii ?>][importe]"  id="input_importe_0" value="0">
                   
                  <input type="hidden" class="input_concepto_0" disabled="disabled"
                  name="conceptos[<?= $ii ?>][iva]" value="0">
                  
                  <input type="hidden" class="input_iva_conceptos input_concepto_0" disabled="disabled"
                  name="conceptos[<?= $ii ?>][iva_euros]" value="0">
                  
                  <input type="hidden" class="input_concepto_0" disabled="disabled"
                  name="conceptos[<?= $ii ?>][irpf]" value='0'>
                  
                  <input type="hidden" class="input_concepto_0" disabled="disabled"
                  name="conceptos[<?= $ii ?>][irpf_euros]"  value='0'>
                  
                  <input type="hidden" class="input_descuento_conceptos input_concepto_0" disabled="disabled"
                  name="conceptos[<?= $ii ?>][descuento_euros]" value="0">
                  
                  <input type="hidden" class="input_concepto_0" disabled="disabled"
                  name="conceptos[<?= $ii ?>][descuento_porcentaje]" value="0">
                  
                  <input type="hidden" class="input_concepto_0" disabled="disabled"
                  name="conceptos[<?= $ii ?>][total]" id="input_total_0" value="0">
                  
                  <input type="hidden" class="input_concepto_0" disabled="disabled"
                  name="conceptos[<?= $ii ?>][id_usuario_creacion]"  value="<?= $id_usuario ?>">
                  
                  <input type="hidden" class="input_concepto_0" disabled="disabled"
                  name="conceptos[<?= $ii ?>][id_usuario_modificacion]"  value="<?= $id_usuario ?>">

                  <input type="hidden" class="input_concepto_0" disabled="disabled"
                  name="conceptos[<?= $ii ?>][id_dietario]"  value="0">
  </div>
  
  
  <div class="col-md-5">
  <div class="alert alert-primary">Comprueba los datos de facturación del cliente</div>
  </div>
  <div class="col-md-3">
    <?php if(isset($servicios) && $servicios != 0 && $xdato=="Incompletos"){ ?>
      <button class="btn btn-warning text-inverse-info" type="button" onclick="DatosFacturacion();">Capturar Datos de Facturacion </button>
    <?php }if (isset($servicios) && $servicios != 0 && $xdato=="Completos") { ?>
        <button class="btn btn-info text-inverse-info" type="button" onclick="DatosFacturacion();"> Actualizar Datos de Facturacion </button>
      <?php } ?>
  </div>

    <div class="col-md-4" style="text-align: right;">
      <?php if (isset($servicios) && $servicios != 0 && $xdato=="Completos") { ?>
        <button class="btn btn-primary text-inverse-primary" type="button" onclick="GenerarFactura();" <?php echo $xestado; ?>>Generar Factura de los Conceptos Marcados</button>
      <?php } ?>
    </div>
  </div>
  


</form>




<script>
  $(document).ready(function() {
    $("#check_pago_cuenta").change(function(){
      
      if ($(this).is(":checked")) {
        var saldo = parseFloat( $("#saldo_cliente").html() );
        $(".input_concepto_0").attr('disabled',false);
        //$("#input_importe_0").val(saldo);
        $("#input_total_0").val(saldo);
        $("#saldo_cliente").html(0);
        $("#total_factura").html(parseFloat($("#total_factura").html())+saldo);

        $("#saldo_cliente").addClass('menosmenos');
        $("#total_factura").addClass('masmas');
        setTimeout(function(){$("#saldo_cliente").removeClass('menosmenos');$("#total_factura").removeClass('masmas');},500)

      }else{
        var saldo = parseFloat( $("#input_total_0").val() );
        $(".input_concepto_0").attr('disabled',true);
        $("#saldo_cliente").html(parseFloat($("#saldo_cliente").html())+saldo);
        $("#total_factura").html(parseFloat($("#total_factura").html())-saldo);

        $("#saldo_cliente").addClass('masmas');
        $("#total_factura").addClass('menosmenos');
        setTimeout(function(){$("#saldo_cliente").removeClass('masmas');$("#total_factura").removeClass('menosmenos');},500)
      }
      totales()

    })
    $(".check_concepto").change(function(){
      var id_item = $(this).attr('id_dietario');
      //var saldo = parseFloat( $("#saldo_cliente").html() );
      var saldo=0;
      var importe = parseFloat($(this).attr('importe'));
      var facturar = $(".facturar_"+id_item);
      var inputs = $(".input_concepto_"+id_item);
      
      var iva = parseFloat($(this).attr('iva'))/100;
      var descuento_porcentaje = parseFloat($(this).attr('descuento_porcentaje'))/100;
      //si esta seleccionado este checkbox restaremos el saldo mas el importe de lo contrario se lo sumaremos
      if ($(this).is(":checked")) {
        $(this).parent('div').parent('td').parent('tr').addClass('tr_sel');
        /*
        if(saldo<=0){
          $(this).prop('checked', false);
          Swal.fire({
            icon: 'error',
            title: 'saldo insuficiente',
          });
          return;
        }

        if(saldo-importe<0){
          importe=saldo;
        }
        */
        
        //$("#saldo_cliente").html(saldo-importe);
        $("#total_factura").html(parseFloat($("#total_factura").html())+importe);
        $("#saldo_cliente").addClass('menosmenos');
        $("#total_factura").addClass('masmas');
        setTimeout(function(){$("#saldo_cliente").removeClass('menosmenos');$("#total_factura").removeClass('masmas');},500)
        facturar.html(importe);
        inputs.attr('disabled',false);
        //calculo de importe, iva y descuento
        if(iva!=0){ $("#input_iva_"+id_item).val(importe/(1+iva)); }
        //if(descuento_porcentaje!=0){ $("#input_descuento_"+id_item).val((importe-parseFloat($("#input_iva_"+id_item).val()))/(1+descuento_porcentaje)); }
        //$("#input_importe_"+id_item).val(importe-parseFloat($("#input_descuento_"+id_item).val())-parseFloat($("#input_iva_"+id_item).val()));
        $("#input_total_"+id_item).val(importe);
      }else{
        $(this).parent('div').parent('td').parent('tr').removeClass('tr_sel');
        if(saldo==0){
          importe = parseFloat(facturar.html());
        }
        $("#saldo_cliente").html(saldo+importe);
        $("#total_factura").html(parseFloat($("#total_factura").html())-importe);
        $("#saldo_cliente").addClass('masmas');
        $("#total_factura").addClass('menosmenos');
        setTimeout(function(){$("#saldo_cliente").removeClass('masmas');$("#total_factura").removeClass('menosmenos');},500)
        facturar.html('0');
        inputs.attr('disabled',true);
        if($("#check_pago_cuenta").is(":checked")){
          $("#check_pago_cuenta").click();
        }
      }
      
      totales();
    })

    
  });

  function totales(){
    
      var total = parseFloat($("#total_factura").html());
      
      var iva=0;
      $('input.input_iva_conceptos:not([disabled])').each(function(){ iva+=parseFloat($(this).val()); })
      var descuento=0
      $('input.input_descuento_conceptos:not([disabled])').each(function(){ descuento+=parseFloat($(this).val()); })
      $("#factura_iva").val(iva);
      $("#factura_descuento").val(descuento);
      $("#factura_total").val(total);
      $("#factura_importe").val(total-descuento-iva);
      
    }
    function stripHtml(html) {
    var doc = new DOMParser().parseFromString(html, 'text/html');
    return doc.body.textContent || "";
  }

  function GenerarFactura() {
    var rows = document.getElementsByName('marcar[]');
    var des = document.getElementById("conceptos");
    var selectedRows = [];
    var mensaje = "<hr>";
    for (var i = 0, l = rows.length; i < l; i++) {
      if (rows[i].checked) {
        selectedRows.push(rows[i]);
      }
    }
    if (selectedRows.length > 0) {
      Swal.fire({
        title: '¿DESEA GENERAR LA FACTURA?',
        html: mensaje,
        showCancelButton: true,
        confirmButtonText: 'Si, generar',
        showLoaderOnConfirm: true,
        onBeforeOpen: () => {},

      }).then((result) => {
        if (result.value) {
          document.form.submit();
        }
      })

    } else {
      Swal.fire({
        icon: 'error',
        title: 'DEBE DE MARCAR AL MENOS UN CONCEPTO PARA FACTURAR',
      })
    }
  }

  function DatosFacturacion() {
    var url = "<?php echo base_url(); ?>dietario/datos_facturacion/<?php echo $cliente[0]['id_cliente']; ?>";
    openwindow('datos_facturacion', url, 600, 500);
  }
</script>