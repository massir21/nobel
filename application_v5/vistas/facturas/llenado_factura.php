
<div class="col-md-12">
  <div class="card">
    <div class="card-body">
      <div class="row">
      <div class="col-md-5">
        Paciente:
        <select id="select_cliente" class="form-select form-select-solid" 
        data-placeholder="Cliente" data-error="Selecciona un cliente.">'
        </select>
      </div>
      
      <div class="col-md-5">
        Centro emisor de factura:
        <select id="select_centro" class="form-select form-select-solid" 
        data-placeholder="Centro" data-error="Selecciona un centro." disabled>
        <?php 
        foreach($centros as $c){?>
          <option value="<?= $c['id_centro'] ?>" 
          <?php if($this->session->userdata('id_centro_usuario')==$c['id_centro']){echo "selected";} ?> >
            <?= $c['nombre_centro'] ?>
          </option>
          <?php } ?>
        </select>
      </div>
      <div class="col-md-2"><br>
      <a type="submit" class="btn btn-info btn-icon text-inverse-info" id="cargar_conceptos"><i class="fas fa-search"></i></a>
      </div> 
      </div>
    </div>
  </div>
</div>

<div class="col-md-12" id="contenedor_presupuestos" style="margin-top: 20px;">
  <div class="card">
    <div class="card-body" id="contenedor_presupuestos_body">
  </div>
</div>




<script>

function consultar_conceptos_cliente(){
      var id_cliente = $("#select_cliente").val();
      if(id_cliente==null){ 
        Swal.fire({
          icon: 'error',
          title: 'No fue seleccionado el cliente',
        }); return;}
      var centro = $("#select_centro").val();
      $("#contenedor_presupuestos").fadeOut(500,function(){
        $.post("./servicios_pagados/"+id_cliente+"/"+centro,function(r){
          $("#contenedor_presupuestos_body").html(r);
          $("#contenedor_presupuestos").fadeIn(500);
        })
      })
    }

    
  $(document).ready(function () {
    //creacion select de clientes
    $("#select_cliente").select2({
        language: "es",
        minimumInputLength: 4,
        ajax: {
            delay: 0,
            url: function (params) {
                return '<?php echo RUTA_WWW; ?>/clientes/json/' + params.term.replace(/ /g, "_").replace(/ /g, "_");
            },
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: data
                };
            }
        }
    });

    //consulta de servicios del cliente al ser seleccionado
    $("#cargar_conceptos").click(function(){
      consultar_conceptos_cliente();
    })
    //consultar al cambiar de cliente
    $("#select_cliente").change(function(){
      consultar_conceptos_cliente();
    })
    

  });
</script>