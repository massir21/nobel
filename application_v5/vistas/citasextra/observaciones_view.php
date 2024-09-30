<?php
$calcIdCita=$id_cita;
?>
<div class="row">
   <div class="col-md-12">
       <h3 class="fw-bold fs-2x text-grey-700 border-bottom pb-3 d-flex justify-content-between">
           Observaciones de Cita
           <button type="button" class="btn btn-primary text-inverse-primary margin-top-20 nuevoevol">Nueva
               Observación</button>
       </h3>

   </div>

    <div class="col-md-12">
        <div class="row">

            <?php
            if($registros>0){
            foreach($registros as $registro){
                $calcIdCita=$registro['id_cita'];
            ?>

            <div class="d-flex flex-column border-1 border-dashed card-rounded p-5 p-lg-10 mb-14 item_evolutivo item-evolutivo-<?php echo $registro['id_observacion'];?>">
                <div class="d-flex justify-content-between border-2 border-bottom border-dark-subtle mb-3">
                    <h4 class="text-start text-dark fw-bold fs-4 text-uppercase gs-0"><?php echo $registro['creador_nombre']; ?></h4>
                    <h5 class="text-start text-danger fw-bold fs-5 text-uppercase gs-0">
                       <?php
                       echo date("d/m/Y H:i:s",strtotime($registro['fecha_creacion']));
                       ?>
                        <button type="button" class="btn btn-sm btn-warning btn-icon ms-3 editevol" idevol="<?php echo $registro['id_observacion'];?>">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-icon btn-danger delevol" idevol="<?php echo $registro['id_observacion'];?>"
                                    idcita="<?php echo $registro['id_cita'];?>">
                            <i class="fa-solid fa-trash"></i>
                        </button>

                    </h5>
                </div>
                <div class="mb-0 fs-6">
                    <div class="text-muted mb-2 content_evol content-evol-<?php echo $registro['id_observacion'];?>"><p><?php echo $registro['observacion'];  ?></p></div>
                </div>
            </div>

            <?php
            }
            }
            else{
                ?>
                    <div class="col-md-12">
                        No hay  observaciones
                    </div>

            <?php
            }
            ?>


        </div>
    </div>

    <div class="col-md-12" id="editordiv" style="display:none">
        <input type="hidden" id="form-idobservacion" />
        <input type="hidden" id="form-idcita" value="<?php echo $calcIdCita;?>"/>
        <textarea style="width: 100%;" name="nota_evolutivo" id="nota_evolutivo" class="form-control form-control-solid" style="height: 600px;" placeholder="Nueva nota"></textarea>
        <br/>

        <button type="button" class="btn btn-sm btn-primary text-inverse-primary btnsaveobs" style="float:right">Guardar</button>
    </div>

</div>


<script type="text/javascript">
    jQuery(document).ready(function(){

    });
</script>