<style type="text/css">
    .class-familia.hide{
        display:none;
    }
</style><div class="card card-flush">
    <div class="card-body pt-6">
        <?php if ($accion == "nuevo") {
            $actionform = base_url() . 'tarifas/gestion/guardar';
        } else {
            $actionform = base_url() . 'tarifas/gestion/actualizar/' . $registros[0]['id_tarifa'];
        } ?>
        <form id="form" action="<?php echo $actionform; ?>" role="form" method="post" name="form">
            <div class="row mb-5 border-bottom">
                <div class="col-md-6 mb-5">
                    <label class="form-label">Nombre Tarifa</label>
                    <input name="nombre_tarifa" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['nombre_tarifa'] : '' ?>" placeholder="" required />
                </div>

                <div class="col-md-3 mb-5">
                    <label class="form-label">Abreviatura</label>
                    <input name="abreviatura" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['abreviatura'] : '' ?>" placeholder="" required />
                </div>

                <div class="col-md-3 mb-5">
                    <label class="form-label">Activa</label>
                    <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                        <input class="form-check-input w-45px h-30px" type="checkbox" id="activo" name="activo" value="1" <?= (isset($registros) && $registros[0]['activo'] == 1) ? "checked" : '' ?>>
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

<?php
if(isset($servicios)) {
    ?>
    <div class="card shadow-sm mt-5">
        <div class="card-body pt-6">
            <h2>Precios de Servicios en Tarifa</h2>
            <div class="col-md-12">
                <div class="alert alert-info">
                    <p>
                        Si dejas el precio vacio, se utilizará el precio base para la tarifa<br/>
                        Si pones el precio a 0, el servicio será gratuito para la tarifa<br/>
                    </p>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-6 mb-5">
                    <label class="form-label">Familia</label>
                    <select id="id_familia_servicio" name="id_familia_servicio" class="form-select form-select-solid" required>
                        <option value="0">Elegir ....</option>
                        <option value="-1">-- TODOS LOS SERVICIOS --</option>
                        <?php if ($familias != 0) {
                            foreach ($familias as $key => $row) { ?>
                                <option value="<?php echo $row['id_familia_servicio'] ?>" ><?php echo $row['nombre_familia'] ?></option>
                            <?php }
                        } ?>
                    </select>
                </div>

            </div>
            <div class="col-md-12 tarifabuttons" style="display:none">
                <div class="row">
                    <div class="col-md-3">

                            <button class="btn btn-primary" onclick="putPrecios0();">Poner todos los precios a 0</button>

                    </div>
                    <div class="col-md-3">

                        <button class="btn btn-primary" onclick="borrarPrecios();">Borrar todos los precios</button>

                    </div>
                    <div class="col-md-6 mb-5">
                        <div class="row">
                            <div class="col-md-6">
                                <input style="width:100px;float:right" class="form-control form-control-solid" id="percentprice" type="number" />
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-primary" onclick="putPreciosPercent();">Aplicar % a precio base</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <form id="form" action="<?php echo $actionform; ?>" role="form" method="post" name="form2">
                <input type="hidden" name="editprecios" value="1" />
                <?php
                $i = 0;
                foreach ($servicios as $servicio) {
                    ?>
                    <div class="row mb-5 class-familia <?php echo 'class-familia-' . $servicio['id_familia_servicio']; ?> hide">
                        <div class="col-md-5 mb-5">
                            <?php if ($i == 0) { ?> <label class="form-label">Servicio</label><?php } ?>
                            <input name="nombre_tarifa" class="form-control form-control-solid" type="text"
                                   value="<?= $servicio['nombre_servicio']; ?>" disabled="disabled"
                                   readonly="readonly"/>
                        </div>

                        <div class="col-md-3 mb-5">
                            <?php if ($i == 0) { ?> <label class="form-label">Familia</label><?php } ?>
                            <input name="nombre_tarifa" class="form-control form-control-solid" type="text"
                                   value="<?= $servicio['nombre_familia']; ?>" disabled="disabled" readonly="readonly"/>
                        </div>

                        <div class="col-md-2 mb-5">
                            <?php if ($i == 0) { ?> <label class="form-label">Precio Original Servicio</label><?php } ?>
                            <input name="abreviatura" class="pvpinputp form-control form-control-solid" type="text"
                                   value="<?= $servicio['pvp']; ?>" disabled="disabled" readonly="readonly"/>
                        </div>

                        <div class="col-md-2 mb-5">
                            <?php if ($i == 0) { ?> <label class="form-label">Precio Servicio en
                                tarifa</label><?php } ?>
                            <input name="prices[<?php echo $servicio['id_servicio'];?>]" class="tarifainputp form-control form-control-solid" type="text"
                                   value="<?= $servicio['pvp_tarifa']; ?>" placeholder=""
                                   />
                        </div>


                    </div>
                    <?php
                    $i++;
                }
                ?>

                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-primary text-inverse-primary btn-familia-save" type="submit">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        function putPreciosPercent(){
            var val=jQuery("#percentprice").val();
            jQuery('.tarifainputp').each(function(){
                var p=jQuery(this).parent().parent().find(".pvpinputp").val();
                if(jQuery(this).is(":visible")) jQuery(this).val(parseFloat(p)+Math.round(100*parseFloat(p)*val/100)/100);
            });
            return false;
        }

        function putPrecios0(){

           jQuery('.tarifainputp').each(function(){
               if(jQuery(this).is(":visible")) jQuery(this).val("0.00");
            });

            return false;
        }

        function borrarPrecios(){
            jQuery('.tarifainputp').each(function(){
                if(jQuery(this).is(":visible")) jQuery(this).val("");
            });

            return false;
        }
        jQuery(document).ready(function () {
            jQuery(".class_familia").hide();
            if(jQuery('.class_famlia:not(.hide)').length>0){
                jQuery(".btn-familia-save").show();
                jQuery(".tarifabuttons").show();
            }
            else {
                jQuery(".btn-familia-save").hide();
                jQuery(".tarifabuttons").hide();
            }

            jQuery("#id_familia_servicio").on('change',function(){
               var val=jQuery(this).val();
               if(val==-1){
                   jQuery(".class-familia").removeClass("hide");
                   jQuery(".btn-familia-save").show();
                   jQuery(".tarifabuttons").show();
               }
               else
               if(val==0){
                   jQuery(".class-familia").addClass("hide");
                   jQuery(".btn-familia-save").hide();
                   jQuery(".tarifabuttons").hide();
               }
               else{
                   jQuery(".class-familia").addClass("hide");
                   jQuery(".class-familia-"+val).removeClass("hide");
                   jQuery(".btn-familia-save").show();
                   jQuery(".tarifabuttons").show();
               }
            });
        })
    </script>


    <?php
}
?>
