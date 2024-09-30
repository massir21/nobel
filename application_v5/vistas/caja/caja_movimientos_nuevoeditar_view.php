<?php
$proveedores_pago_doctor = array();
if(isset($proveedores) && is_array($proveedores)){
    foreach ($proveedores as $row) {
        if ($row['pago_doctor'] == 1) {
            $proveedores_pago_doctor[] = $row['id_proveedor'];
        }
    }
}
?>
<div class="card card-flush">
    <div class="card-body pt-6">
        <?php if ($accion == "nuevo") {
            $actionform = base_url().'caja/movimientos/guardar';
        } else { 
            $actionform = base_url().'caja/movimientos/actualizar/'.$registros[0]['id_centro'];
        } ?>
        <form id="form" action="#" role="form" method="post" name="form" enctype="multipart/form-data">

            <div class="row mb-5 border-bottom onlyforgasto" style="display:none">
                <div class="col-md-6 mb-5">
                    <label class="form-label">Centro</label>
                    <select name="centro_id" class="form-select form-select-solid requiredforgasto" required>
                        <option value="">Eligir ...</option>
                        <?php if ($centros_todos != 0): ?>
                            <?php foreach ($centros_todos as $key => $row): ?>
                                <option value="<?php echo $row['id_centro'] ?>"
                                    <?php
                                    if(isset($registros) && isset($registros[0]) && $registros[0]['centro_id'] == $row['id_centro']){
                                        echo ' selected="selected" ';
                                    }
                                    else
                                    if($this->session->userdata('id_centro_usuario')==$row['id_centro']){
                                        echo ' selected="selected" ';
                                    }
                                    else{

                                    }
                                    /*
                                    ?>
                                    <?= (isset($registros) && isset($registros[0]) && $registros[0]['centro_id'] == $row['id_centro']) ? "selected" : '' ?>
                                    */ ?>
                                ><?php echo $row['nombre_centro'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>


                <div class="col-md-6 mb-5">
                    <label class="form-label">Documento</label>
                    <input type="file" class="form-control" name="documento_ruta"/>
                </div>
            </div>

            <div class="row mb-5 border-bottom onlyforgasto" style="display:none">
                <div class="col-md-6 mb-5">
                    <label class="form-label">Proveedor</label>
                    <select name="id_proveedor" class="form-select form-select-solid requiredforgasto" required id="id_proveedor">
                        <option value="">Elegir ....</option>
                        <?php if ($proveedores != 0) {
                            foreach ($proveedores as $key => $row) { ?>
                                <option value="<?php echo $row['id_proveedor'] ?>" <?= (isset($registros) && isset($registros[0]) && $registros[0]['id_proveedor'] == $row['id_proveedor']) ? "selected" : '' ?>><?php echo $row['nombreProveedor'] ?></option>
                            <?php }
                        } ?>
                    </select>
                </div>

                <div class="col-md-6 mb-5">
                    <label class="form-label">Fecha factura</label>
                    <div class="input-group mb-5">
                        <input type="date" class="form-control" name="fecha_factura"
                               value="<?php echo (isset($registros) && isset($registros[0])) ? date("Y-m-d", strtotime($registros[0]['fecha_factura'])) : '' ?>"/>
                    </div>
                </div>
            </div>


            <div class="row mb-5 border-bottom">
                <div class="col-md-8 col-lg-7 mb-5">
                    <label class="form-label">Concepto</label>
                    <input name="concepto" class="form-control form-control-solid" type="text" value="<?=(isset($registros))?$registros[0]['concepto']:''?>" placeholder="" required />
                </div>
                <div class="col-md-2">
                <label class="form-label">Tipo</label>
                    <select class="form-select" id="tipo">
                        <option value="1" <?php if(isset($registros)&&$registros[0]['cantidad']>0){ echo "selected"; }?>>Ingreso</option>
                        <option value="-1" <?php if(isset($registros)&&$registros[0]['cantidad']<0){ echo "selected"; }?>>Retirada</option>
                        <?php if(isset($registros) && $registros){}
                                else{?>
                            <option value="0">Gasto</option>
                        <?php
                                }
                        ?>
                    </select>
                </div>
                <div class="col-md-3 col-lg-3 mb-5">
                    <label class="form-label">Importe (€)</label>
                    <input name="cantidad" id="importe" class="form-control form-control-solid" type="number" step="0.01" value="<?=(isset($registros))?$registros[0]['cantidad']:''?>" placeholder="" required/>
                </div>
            </div>

            <div class="row mb-5 border-bottom onlyforgasto" style="display:none">
                <div class="col-md-12 mb-5" id="div_lista_doc" style="display:none">
                    <label class="form-label">Doctor</label>
                    <select name="id_doctor" class="form-select form-select-solid select2-selection requiredfordoctor" required>
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
                <div class="col-md-12 mb-5">
                    <label class="form-label">Nota</label>
                    <div class="input-group mb-5">
                        <input type="text" class="form-control" name="nota"
                               value=""/>
                    </div>
                </div>
            </div>

            <input type="hidden" name="crear_movimiento" value="0" id="crear_movimiento" />
        
            <div class="row mb-5">
                <div class="col-md-12">
                    <button class="btn btn-primary text-inverse-primary" type="submit">GUARDAR</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#tipo').change(function(){
            if(jQuery(this).val()==0){
                jQuery(".onlyforgasto").show();
                jQuery("#form").attr("action","<?php echo base_url().'facturas/gestion/guardar';?>");
                jQuery(".requiredforgasto").attr("required","required");
                jQuery("#importe").attr('name','total_factura');
                jQuery("#crear_movimiento").val(1);
            }
            else{
                jQuery(".onlyforgasto").hide();
                jQuery("#form").attr("action","<?php echo $actionform; ?>");
                jQuery(".requiredforgasto").removeAttr("required");
                jQuery("#importe").attr('name','cantidad');
                jQuery("#crear_movimiento").val(0);
            }
            ajustarimporte();
        });
        $("#importe").change(function (e) { 
            ajustarimporte();
        });
        function ajustarimporte(){
            if($("#tipo").val()>=0){
                $("#importe").val( Math.abs($("#importe").val()));
            }else {
                $("#importe").val( Math.abs($("#importe").val())*-1);
            }
        }




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
                jQuery(".requiredfordoctor").attr("required","required");
            }else{
                $(".select2-selection").val(0);
                $(".select2-selection").select2();
                $("#div_lista_doc").fadeOut(700);
                jQuery(".requiredfordoctor").removeAttr("required");
            }

        })
        $(".select2-selection").select2();

        $('#tipo').trigger('change');

    })
</script>