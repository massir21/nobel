<style>
  .custom-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.5s ease-in-out;
  }

  .card-subtitle-small {
    font-size: 1.0rem;
    /* Ajusta el tamaño según tus preferencias */
  }

  .card-amount {
    font-size: 2.5rem;
    /* Ajusta el tamaño según tus preferencias */
    font-weight: bold;
    color: #4CAF50;
    /* Color verde de Material Design */
  }

  .card-amount-bills {
    font-size: 2.5rem;
    /* Ajusta el tamaño según tus preferencias */
    font-weight: bold;
    color: #424242;
    /* Color rojo de Material Design */
  }


  .card-amount-earnings {
    font-size: 2.5rem;
    /* Ajusta el tamaño según tus preferencias */
    font-weight: bold;
    color: #2196F3;
    /* Color rojo de Material Design */
  }

  .loader_balance {
    position: absolute;
    width: 100%;
    height: 100%;
    top: -20px;
    left: -20px;
    text-align: center;
    background-color: rgba(255, 255, 255, 0.5);
    z-index: 1;
    display: none;
  }

  .tipo_gasto {
    /*cursor: pointer;*/
  }

  .tipo_gasto:hover {
    /*background-color:#f5f5f5;*/
  }

  /* ocultamos los elementos que no queremos ver al imprimir */
  @media print {
    .filtro {
      display: none;
    }

    .app-header {
      display: none;
    }
  }
</style>
<div class="loader_balance"></div>
<form method="post" action="./agregar_objetivo" id="form_alta_objetivo">

  <div class="card card-flush filtro">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
      <div class="card-title">
        <div class="card-title">

        </div>
      </div>
      <div class="card-toolbar flex-row-fluid justify-content-start gap-5">
      <div class="col-md-12 text-center"><h2>Agregar objetivo</h2></div>
        <div class="col-md-2">
          <label class="form-label">Centro:</label>
          <select name="id_centro" id="centro" class="form-select form-select-solid">
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

        <div class="col-md-1">
          <label class="form-label">Mes:</label>
          <select name="mes" id="mes" class="form-select form-select-solid">
            <?php if (isset($todos_meses)) {
              foreach ($todos_meses as $mes_r) { ?>
                <option value='<?php echo $mes_r; ?>' <?php if ($mes_r == $mes) echo "selected"; ?>>
                  <?php echo mesletra($mes_r); ?>
                </option>
            <?php }
            } ?>

          </select>
        </div>

        <div class="col-md-1">
          <label class="form-label">Año:</label>
          <select name="ano" id="anio" class="form-select form-select-solid">
            <?php if (isset($todos_anios)) {
              foreach ($todos_anios as $ano_r) { ?>
                <option value='<?php echo $ano_r; ?>' <?php if ($ano_r == $ano) echo "selected"; ?>>
                  <?php echo $ano_r; ?>
                </option>
            <?php }
            } ?>
          </select>
        </div>
        <div class="col-md-2" style="text-align: right;"><label class="form-label">Facturación (€):</label>
          <input type="number" step="0.01" name="facturacion" class="form-control" value="0.00" style="text-align: right;" />
        </div>
        <div class="col-md-2" style="text-align: right;"><label class="form-label">Rentabilidad (%):</label>
        <input type="number" step="1" name="rentabilidad" class="form-control" value="0" style="text-align: right;" />
        </div>
        
        <div class="col-md-1">
          <br>
          <button type="submit" class="btn btn-success pull-right" id="agregar"><i class="bi bi-calendar-plus"></i></button>
        </div>
      </div>

      <!--<div class="w-auto ms-3">
                <label class="form-label">Proveedor:</label>
                <select name="proveedor_id" id="proveedor_id" class="form-select form-select-solid w-auto">
                    <option value="">Todos</option>
                    <?php if (isset($proveedor) && !empty($proveedor)) : ?>
                        <?php if (count($proveedor) > 0) : ?>
                            <?php foreach ($proveedor as $key => $row) : ?>
                                <option value="<?php echo $row['id_proveedor'] ?>"><?php echo $row['nombreProveedor'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </select>
            </div>-->
    </div>
  </div>
</form>




  <div id="tabla_objetivos"></div>






  <form method="post" action="./editar_objetivo" id="form_editar_objetivo">



   <<!-- Button trigger modal -->
  <!-- Modal -->
  <div
    class="modal fade" id="modal_editar_objetivo" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"aria-hidden="true"
  >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitleId">
            Editar objetivo
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="container-fluid">
            <input type="hidden" name="id_objetivo" id="edit_id_objetivo">
            <div class="row">
            <div class="col-md-12" style="font-size: 20px;">
              <b>Centro:</b> <span id="edit_centro"></span><br>
              <b>Mes:</b> <span id="edit_mes"></span>, <b>Año:</b> <span id="edit_ano"></span><br>
              <br>
            </div>
          </div>
            <div class="row">
            <div class="col-md-6">
              <label>Facturación (€)</label>
            <input type="number" step="0.01" name="facturacion" class="form-control" id="edit_facturacion">
            </div>
            <div class="col-md-6">
            <label>Rentabilidad (%)</label>
            <input type="number" step="0.01" name="rentabilidad" class="form-control"id="edit_rentabilidad">
            </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary"data-bs-dismiss="modal">Regresar</button>
          <button type="submit" class="btn btn-success">Guardar</button>
        </div>

      </div>
    </div>
  </div>

  </form>
  <script>
    $(document).ready(function() {
    cargarTablaObjetivos();
    function  cargarTablaObjetivos(){
      $(".loader_balance").fadeIn(200);
      $("#tabla_objetivos").load("./load_tabla_objetivos",function(){
        $(".loader_balance").fadeOut(200);
      });
    }

    //alta
    $(document).on("submit",'#form_alta_objetivo',function(e){
      e.preventDefault();
      $(".loader_balance").fadeIn(200);
      $.post($(this).attr('action'),$(this).serialize(),function(data){
        if(data=='0'){ alert("Error: Ya existe un objetivo en ese mes."); }
        cargarTablaObjetivos();
      })
    });

    //editar
    $(document).on("click",".editar_objetivo",function(){
      $("#modal_editar_objetivo").modal('show');
      $("#edit_centro").html($(this).attr('nombre_centro'));
      $("#edit_mes").html($(this).attr('mes'));
      $("#edit_ano").html($(this).attr('ano'));
      $("#edit_id_objetivo").val($(this).attr('id_objetivo'));
      $("#edit_facturacion").val($(this).attr('facturacion'));
      $("#edit_rentabilidad").val($(this).attr('rentabilidad'));
    })

    $(document).on("submit",'#form_editar_objetivo',function(e){
      e.preventDefault();
      $("#modal_editar_objetivo").modal('hide');
      $(".loader_balance").fadeIn(200);
      $.post($(this).attr('action'),$(this).serialize(),function(data){
        if(data=='0'){ alert("Error: Ya existe un objetivo en ese mes."); }
        cargarTablaObjetivos();
      })
    });
 


    //baja
    $(document).on("click",'.borrar_objetivo',function(e){
      if(!confirm("estas seguro de que deseas borrar este objetivo? ")){return;}
      $("#modal_editar_objetivo").modal('hide');
      $(".loader_balance").fadeIn(200);
      $.post($(this).attr('url'),function(data){
        if(data=='0'){ alert("Error"); }
        cargarTablaObjetivos();
      })
    });

    })
  </script>