<?php 
$proveedores_pago_doctor = array();
foreach ($proveedores as $row) {
    if ($row['pago_doctor'] == 1) {
        $proveedores_pago_doctor[] = $row['id_proveedor'];
    }
}
?>
<div class="card card-flush">
    <div class="card-body pt-6">
        <?php if ($accion == "nuevo") {
            $actionform = base_url() . 'facturas/gestion/guardar';
        } else {
            $actionform = base_url() . 'facturas/gestion/actualizar/' . $registros[0]['id_gestion_facturas'];
        } ?>
        <form id="form" action="<?php echo $actionform; ?>" role="form" method="post" name="form"
              enctype="multipart/form-data">
            <div class="row mb-5 border-bottom">
                <div class="col-md-6 mb-5">
                    <label class="form-label">Centro</label>
                    <select name="centro_id" class="form-select form-select-solid" required>
                        <option value="">Eligir ...</option>
                        <?php if ($centros_todos != 0): ?>
                            <?php foreach ($centros_todos as $key => $row): ?>
                                <option value="<?php echo $row['id_centro'] ?>" <?= (isset($registros) && isset($registros[0]) && $registros[0]['centro_id'] == $row['id_centro']) ? "selected" : '' ?>><?php echo $row['nombre_centro'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <?php if(isset($registros[0])&&explode('/',$registros[0]['documento_ruta'])[3]!=''){?>
                <div class="col-md-6 mb-5">
                    <a href="<?php echo base_url() . 'recursos/' . $registros[0]['documento_ruta'] ?>" style="position: absolute; right:40px;" download>Documento actual</a>
                    <label class="form-label">Acualizar documento</label>
                    <input type="file" class="form-control" name="documento_ruta"/>
                </div>
                <?php }else{?>
                    <div class="col-md-6 mb-5">
                    <label class="form-label">Documento</label>
                    <input type="file" class="form-control" name="documento_ruta"/>
                </div>
                <?php } ?>
                <div class="col-md-6 mb-5">
                    <label class="form-label">Proveedor</label>
                    <select name="id_proveedor" class="form-select form-select-solid" required id="id_proveedor">
                        <option value="">Elegir ....</option>
                        <?php if ($proveedores != 0) {
                            foreach ($proveedores as $key => $row) { ?>
                                <option value="<?php echo $row['id_proveedor'] ?>" <?= (isset($registros) && isset($registros[0]) && $registros[0]['id_proveedor'] == $row['id_proveedor']) ? "selected" : '' ?>><?php echo $row['nombreProveedor'] ?></option>
                            <?php }
                        } ?>
                    </select>
                </div>
                
                <div class="col-md-6 mb-5">
                    <label class="form-label">Total factura</label>
                    <div class="input-group mb-5">
                        <input type="text" class="form-control" name="total_factura"
                               value="<?php echo (isset($registros) && isset($registros[0])) ? $registros[0]['total_factura'] : '' ?>"/>
                        <span class="input-group-text"><i class="fa-solid fa-euro-sign"></i></span>
                    </div>
                </div>
                <div class="row" id="div_lista_doc"
                <?php if(!isset($registros) || !in_array($registros[0]['id_proveedor'], $proveedores_pago_doctor)){ ?>
                        style="display: none;" 
                 <?php } ?>
                >


                <div class="col-md-6 mb-5">
                    <label class="form-label">Doctor</label>
                    <select name="id_doctor" class="form-select form-select-solid select2-selection" required>
                    <option value="0">Elegir ....</option>
                        <?php if ($doctores != 0) {
                            foreach ($doctores as $key => $row) { ?>
                                <option value="<?php echo $row['id_usuario'] ?>" <?= (isset($registros) && isset($registros[0]) && $registros[0]['id_doctor'] == $row['id_usuario']) ? "selected" : '' ?>>
                                    <?= $row['nombre']." ".$row['apellidos']." - (".$row['centro'].")" ?>
                                    <?php if($row['borrado']=='1'){ echo " - Borrado";} ?>
                                </option>
                            <?php }
                        } ?>
                    </select>
                </div>
                </div>
                <div class="col-md-6 mb-5">
                    <label class="form-label">Fecha factura</label>
                    <div class="input-group mb-5">
                        <input type="date" class="form-control" name="fecha_factura"
                               value="<?php echo (isset($registros) && isset($registros[0])) ? date("Y-m-d", strtotime($registros[0]['fecha_factura'])) : '' ?>"/>
                    </div>
                </div>
                <div class="col-md-6 mb-5">
                    <label class="form-label">Nota</label>
                    <div class="input-group mb-5">
                        <input type="text" class="form-control" name="nota"
                               value="<?php echo (isset($registros) && isset($registros[0])) ? $registros[0]['nota'] : '' ?>"/>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary text-inverse-primary" type="submit">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>

//objeto javascript a partir de una cadena json
var proveedores_pago_doctor = JSON.parse('<?php echo json_encode($proveedores_pago_doctor); ?>');

//jquery al cambiar el valor del select id_proveedor recorrer proveedores_pago_doctor y verificar el valor del select exista en el arrelo recorrido
$("#id_proveedor").change(function(){
    var id_proveedor = $(this).val();
    var es_doctor=false;
    $.each(proveedores_pago_doctor, function (idex,doc){
        if(id_proveedor==doc){
            es_doctor=true;
        }
    });
    if(es_doctor){
        $("#div_lista_doc").fadeIn(700);
    }else{
        $(".select2-selection").val(0);
        $(".select2-selection").select2();
        $("#div_lista_doc").fadeOut(700);
    }

})
$(".select2-selection").select2();


</script>




