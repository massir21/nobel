<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">

    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">DEVOLUCIÓN</h1>
    <?php
    if($accion=='error'){
        ?>
        <div class="alert alert-danger d-flex align-items-center p-5" style="margin-top:10px">
        <div class="d-flex flex-column">
            <h4 class="mb-1 text-danger">No se puede realizar la devolucón</h4>
            <span><?php /*echo "<pre>";var_dump($original_param);echo "</pre>";*/
                echo $errorDevolucion;?></span>
        </div>

        </div>
    <?php
    }
    ?>



    <div class="card card-flush m-5">
        <div class="card-body p-5">
            <form id="form_devolver" action="<?php echo base_url(); ?>dietario/devoluciones/realizar" role="form" method="post" name="form_devolver">
                <div class="row mb-5 align-items-end">
                    <div class="col-6 mb-5">
                        <label for="" class="form-label">Cliente:</label>
                        <select name="id_cliente" id="cliente" class="form-select form-select-solid" data-placeholder="Elegir ..." onchange="Mostrar()">
                            <?php if (isset($cliente_elegido) && $cliente_elegido != 0 && $cliente_elegido[0]['id_cliente'] > 0) { ?>
                                <option value="<?= $cliente_elegido[0]['id_cliente'] ?>" selected><?= $cliente_elegido[0]['nombre'] . ' ' . $cliente_elegido[0]['apellidos'] . ' (' . $cliente_elegido[0]['telefono'] . ')'; ?></option>
                            <?php } ?>
                        </select>
                        <script type="text/javascript">
                            $("#cliente").select2({
                                language: "es",
                                minimumInputLength: 4,
                                ajax: {
                                    delay: 0,
                                    url: function(params) {
                                        return '<?php echo RUTA_WWW; ?>/clientes/json/' + params.term.replace(/ /g, "_");
                                    },
                                    dataType: 'json',
                                    processResults: function(data) {
                                        return {
                                            results: data
                                        };
                                    }
                                }
                            });
                        </script>
                    </div>
                    <div class="col-6 mb-5" id="que_devolver">
                        <label for="" class="form-label">¿Producto o Servicio?</label>
                        <?php
                        $mostrarProducto=true;
                        $mostrarServicio=true;
                        $mostrarSaldo=true;
                        if(isset($dietario)){
                            $mostrarProducto=true && ((intval($dietario[0]['id_producto']) > 0));
                            $mostrarServicio=true && (intval($dietario[0]['id_servicio'] )> 0);
                            $mostrarSaldo=!$mostrarServicio && !$mostrarProducto && true && $dietario[0]['pago_a_cuenta']=1;


                        }
                        ?>
                        <select id="que_devolverId" name="que_devolver" data-placeholder="Elegir ..." class="form-select form-select-solid" onchange="Mostrar();" required>
                            <option value="">Elegir...</option>
                            <?php
                            if($mostrarProducto){
                            ?>
                            <option value="1" <?= (isset($dietario) && $dietario[0]['id_producto'] > 0) ? "selected" :
                                (isset($original_param['que_devolver']) && $original_param['que_devolver']==1 ? "selected" : "") ?>>Producto</option>
                            <?php
                            }
                            if($mostrarServicio){
                            ?>
                            <option value="2" <?= (isset($dietario) && $dietario[0]['id_servicio'] > 0) ? "selected" :
                                (isset($original_param['que_devolver']) && $original_param['que_devolver']==2 ? "selected" : "") ?>>Servicio</option>
                            <?php
                            }
                            if($mostrarSaldo){
                            ?>
                            <option value="3" <?= (isset($dietario) && $dietario[0]['pago_a_cuenta'] > 0) ? "selected" :
                                (isset($original_param['que_devolver']) && $original_param['que_devolver']==3 ? "selected" : "") ?>>Pago a cuenta</option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div style="display: none;" id="producto">
                    <div class="w-100">
                        <label for="" class="form-label">Producto</label>
                        <select id="id_producto" name="id_producto" data-placeholder="Elegir producto..." class="form-select form-select-solid" data-control="select2" tabindex="-1" aria-hidden="true" onchange="PrecioProducto(this.value);" required>
                            <option value=""></option>
                            <?php if (isset($productos)) {
                                if ($productos != 0) {
                                    foreach ($productos as $key => $row) { ?>
                                        <option value='<?php echo $row['id_producto']; ?>|<?php echo $row['pvp']; ?>' <?= (isset($dietario) && $dietario[0]['id_producto'] == $row['id_producto']) ? "selected" : '' ?>>
                                            <?php echo strtoupper($row['nombre_producto']) . " (" . $row['nombre_familia'] . ")"; ?>
                                        </option>
                            <?php }
                                }
                            } ?>
                        </select>
                    </div>
                </div>

                <div style="display: none;" id="servicio">
                    <div class="w-100">
                        <label for="" class="form-label">Servicio</label>
                        <select id="id_servicio" name="id_servicio" data-placeholder="Elegir producto..." class="form-select form-select-solid" data-control="select2" tabindex="-1" aria-hidden="true" onchange="PrecioServicio(this.value);" required>
                            <option value=""></option>
                            <?php if (isset($servicios)) {
                                if ($servicios != 0) {
                                    foreach ($servicios as $key => $row) { ?>
                                        <?php
                                        if(isset($dietario) && $dietario[0]['id_servicio']!=$row['id_servicio']) continue;
                                        ?>
                                        <option value='<?php
                                            /*
                                            echo $row['id_servicio']; ?>|<?php echo $row['pvp']; ?>|<?php echo $row['templos']; ?>' <?= (isset($dietario) && $dietario[0]['id_servicio'] == $row['id_servicio']) ? "selected" : ''
                                            */
                                            echo $row['id_servicio'];
                                            ?>' <?php echo (isset($dietario) && $dietario[0]['id_servicio']==$row['id_servicio'])  ? " selected ": ""; ?>>
                                            <?php echo strtoupper($row['nombre_familia'] . " - " . $row['nombre_servicio'] . " (" . $row['duracion'] . " min)"); ?>
                                        </option>
                            <?php }
                                }
                            } ?>
                        </select>
                    </div>
                </div>


                <div style="margin-top: 10px; display: none;" id="importe">
                    <div class="row mb-5 align-items-end">
                        <div class="col-6 mb-5">
                            <label for="" class="form-label">Forma de devolución</label>
                            <select id="forma_pago" name="forma_pago" data-placeholder="Elegir ..." class="form-select form-select-solid" tabindex="-1" aria-hidden="true" onchange="FormaPago(this.value);" required>
                                <option value="">Elegir...</option>
                                <option value="#efectivo" <?php
                                        echo isset($original_param['forma_pago']) && $original_param['forma_pago']=='#efectivo' ? "selected" : ""
                                        ?>>Efectivo</option>
                                <option value="#tarjeta" <?php
                                        echo isset($original_param['forma_pago']) && $original_param['forma_pago']=='#tarjeta' ? "selected" : ""
                                        ?>>Tarjeta</option>
                                <option value="#transferencia" <?php
                                        echo isset($original_param['forma_pago']) && $original_param['forma_pago']=='#transferencia' ? "selected" : ""
                                        ?>>Transferencia</option>
                                <option value="#tpv2" <?php
                                        echo isset($original_param['forma_pago']) && $original_param['forma_pago']=='#tpv2' ? "selected" : ""
                                        ?>>TPV2</option>
                                <option value="#saldo_cuenta" <?php
                                        echo isset($original_param['forma_pago']) && $original_param['forma_pago']=='#saldo_cuenta' ? "selected" : ""
                                        ?>>Saldo Cliente</option>
                                <?php if ($this->session->userdata('id_centro_usuario') == 9) { ?>
                                    <option value="#habitacion"  <?php
                                        echo isset($original_param['forma_pago']) && $original_param['forma_pago']=='#habitacion' ? "selected" : ""
                                        ?>>Habitación</option>
                                <?php } ?>
                                <option value="#templos"   <?php
                                        echo isset($original_param['forma_pago']) && $original_param['forma_pago']=='#templos' ? "selected" : ""
                                        ?>>Templos</option>
                                <?php if ($codigo_carnet_especial != "") { ?>
                                    <option value="#especial"  <?php
                                        echo isset($original_param['forma_pago']) && $original_param['forma_pago']=='#especial' ? "selected" : ""
                                        ?>>Carnet Especial</option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-6 mb-5" id="importe_en_euros">
                            <label for="" class="form-label">Importe en euros</label>
                            <input id="importe_devolver" type="number" step="0.01" min="0" name="importe_devolver" class="form-control form-control-solid"
                                <?= (isset($dietario) && $dietario[0]['importe_total_final'] > 0) ? 'value="' . $dietario[0]['importe_total_final'] . '"' :
                                    (isset($original_param['importe_devolver'])  ? 'value="'.$original_param['importe_devolver'].'"' : "") ?> required />
                        </div>
                        <div class="col-6 mb-5" id="templos">
                            <label for="" class="form-label">Templos</label>
                            <input id="templos" type="number" step="0.01" min="0" name="templos" class="form-control form-control-solid" <?= (isset($dietario) && $dietario[0]['templos'] > 0) ? 'value="' . $dietario[0]['templos'] . '"' : '' ?> required />
                        </div>
                        <div class="col-6 mb-5" id="especial">
                            <label for="" class="form-label">Se devolverá en el carnet: <?php echo $codigo_carnet_especial; ?></label>
                            <input type="hidden" id="id_carnet_especial" name="id_carnet_especial">
                        </div>
                        <div class="col-6 mb-5" id="carnets">
                            <label for="" class="form-label">Carnet:</label>
                            <select name="id_carnet" id="id_carnet" class="form-select form-select-solid" data-placeholder="Elegir ..."></select>
                            <script type="text/javascript">
                                $("#id_carnet").select2({
                                    language: "es",
                                    minimumInputLength: 4,
                                    ajax: {
                                        delay: 0,
                                        url: function(params) {
                                            return '<?php echo RUTA_WWW; ?>/carnets/json/' + params.term;
                                        },
                                        dataType: 'json',
                                        processResults: function(data) {
                                            return {
                                                results: data
                                            };
                                        }
                                    }
                                });
                            </script>
                        </div>

                        <div class="col-md-12 mb-5">
                            <label><b>Motivo de la Devolución</b></label>
                            <textarea id="motivo_devolucion" name="motivo_devolucion" class="form-control form-control-solid" required><?php
                                echo isset($original_param['motivo_devolucion']) ? $original_param['motivo_devolucion'] :'' ;
                            ?></textarea>
                        </div>
                        <input type="hidden" name="id_dietario" id="id_dietario" <?= (isset($dietario) && $dietario[0]['id_dietario'] > 0) ? 'value="' . $dietario[0]['id_dietario'] . '"' : '' ?> >
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button class="btn btn-sm btn-secondary text-inverse-secondary m-2" type="button" onclick="window.close();">Cerrar sin Cambios</button>
                            <button class="btn btn-sm btn-primary text-inverse-primary" type="submit">Realizar Devolución</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>

        jQuery(document).ready(function(){
            jQuery("select[name=id_cliente]").on('change',function(){
                getServicios(jQuery(this).val());
            });

            jQuery("select[name=id_servicio]").on('change',function(){
                var idcliente=jQuery("select[name=id_cliente]").val();
                var idservicio=jQuery(this).val();
                getInfoServicio(idcliente,idservicio);
                jQuery("select[name=forma_pago]").trigger('change');
            });

            jQuery("select[name=que_devolver]").on("change",function(){
                jQuery("select[name=forma_pago] option").show();
                var val=jQuery(this).val();
                if(val==3){
                    jQuery("select[name=forma_pago] option").each(function(){
                        if(jQuery(this).val()=="#saldo_cuenta"){
                            jQuery(this).hide();
                        }
                    });
                    jQuery("input[name=importe_devolver]").val("").prop("max",null).attr("max",null);
                }
                else
                if(val==2){
                    getServicios(jQuery("select[name=id_cliente]").val());
                }
                else
                if(val==1){
                    getProductos(jQuery("select[name=id_cliente]").val());
                }
            });


            <?php if ($accion == "realizar") { ?>
            Cerrar();
            <?php } ?>
            Mostrar();
            <?php
            if(isset($original_param['forma_pago'])){
            ?>
            jQuery("select[name=forma_pago]").trigger('change');
            <?php
            }
            ?>


            <?php
            if(isset($dietario) && $dietario[0]['id_servicio']>0){
                ?>
                jQuery("#id_servicio").trigger('change');

                <?php
            }
            ?>
            if(jQuery("select[name=que_devolver]").val()==3) {
                jQuery("select[name=que_devolver]").trigger('change');
            }
        });

        function getInfoServicio(idcliente,idservicio){
            var url="<?php echo base_url(); ?>Clientes/ultimo_servicio/"+idcliente+"/"+idservicio;
            jQuery.post(url,function(data){
                if(data){
                    jQuery("input[name=importe_devolver]").val(data.total_a_devolver);
                    jQuery("select[name=forma_pago] option").show();
                    if(data.tipo_pago=='#saldo_cuenta'){
                        jQuery("select[name=forma_pago] option").hide();
                        jQuery("select[name=forma_pago] option").each(function(){
                            if(jQuery(this).val()=="#saldo_cuenta"){
                                jQuery(this).show();
                            }
                        });
                    }
                    jQuery("input[name=importe_devolver]").attr('max',data.total_a_devolver);
                }
            },'json')
        }


        function getServicios(idcliente){
            var url="<?php echo base_url(); ?>Clientes/servicios_realizados/"+idcliente+"/0";
            jQuery.post(url,function(data){
                jQuery("select[name=id_servicio] option").remove();
                var dt=[];
                for(var i=0;i<data.length;i++){
                    if(data[i].total_a_devolver>0) {
                        dt.push(data[i]);
                    }
                }
                jQuery("select[name=id_servicio]").select2({
                    data: dt,
                     allowClear: true
                });
                jQuery("select[name=id_servicio]").val("");
                jQuery("select[name=id_servicio]").trigger('change');
                if(dt.length==0){
                    if(jQuery("#que_devolverId").val()=="2") alert('No hay servicios para devolver');
                }
            },'json')
        }

        function getProductos(idcliente){
            var url="<?php echo base_url(); ?>Clientes/productos_comprados/"+idcliente;
            jQuery.post(url,function(data){
                jQuery("select[name=id_producto] option").remove();
                var dt=[];
                for(var i=0;i<data.length;i++){
                    dt.push(data[i]);
                }
                jQuery("select[name=id_producto]").select2({
                    data: dt,
                    allowClear: true
                });
                jQuery("select[name=id_producto]").val("");
                jQuery("select[name=id_producto]").trigger('change');
            },'json')
        }

        function Mostrar() {
            jQuery("#que_devolver").hide();
            jQuery("#importe").show();
            jQuery("#importe_en_euros").hide();
            jQuery("#templos").hide();
            jQuery("#carnets").hide();
            jQuery("#especial").hide();
            if(jQuery("#cliente").val()!=""){
                jQuery("#que_devolver").show();
            }
            if (jQuery("#que_devolverId").val() == 1) {
                jQuery("#producto").show();
                jQuery("#servicio").hide();
                jQuery("#importe_en_euros").show();
                jQuery("#id_servicio").prop("disabled",true);
                jQuery("#id_producto").prop("disabled",false);
                jQuery("#importe_devolver").prop("disabled",false);
                jQuery("#templos").prop("disabled",true);
                jQuery("#id_carnet").prop("disabled",true);
                jQuery("#id_carnet_especial").prop("disabled",true);
                <?php if ($this->session->userdata('id_centro_usuario') == 9) { ?>
                    document.form_devolver.forma_pago[4] = null;
                <?php } else { ?>
                    document.form_devolver.forma_pago[3] = null;
                <?php } ?>
            }
            if (jQuery("#que_devolverId").val() == 2) {
                jQuery("#producto").hide();
                jQuery("#servicio").show();
                jQuery("#id_servicio").prop("disabled",false);
                jQuery("#id_producto").prop("disabled",true);
                jQuery("#importe_devolver").prop("disabled",false);
                jQuery("#templos").prop("disabled",false);
                jQuery("#id_carnet").prop("disabled",false);
                jQuery("#id_carnet_especial").prop("disabled",true);
                <?php if ($this->session->userdata('id_centro_usuario') == 9) { ?>
                    if (document.form_devolver.forma_pago.length < 5) {
                        s = document.form_devolver.forma_pago;
                        var option = document.createElement("option");
                        option.value = "#templos"
                        option.text = "Templos";
                        s.appendChild(option);
                    }
                <?php } else { ?>
                    if (document.form_devolver.forma_pago.length < 4) {
                        s = document.form_devolver.forma_pago;
                        var option = document.createElement("option");
                        option.value = "#temque_devolverIdplos"
                        option.text = "Templos";
                        s.appendChild(option);
                    }
                <?php } ?>
            }
            if (jQuery("#que_devolverId").val() == 3) {
                    jQuery("#producto").hide();
                    jQuery("#servicio").hide();
                    jQuery("#importe").show();
                    jQuery("#id_servicio").prop("required",false);
                    jQuery("#id_producto").prop("required",false);
                    jQuery("#templos").prop("disabled",false);
                    jQuery("#importe_devolver").prop("disabled",true);
                    jQuery("#id_carnet").prop("disabled",true);
                    jQuery("#id_carnet_especial").prop("disabled",true);
                    jQuery("#importe_en_euros").hide();
                    jQuery("#templos").hide();
                    jQuery("#carnets").hide();
            }

            if (jQuery("#que_devolverId").val() == "") {
                jQuery("#producto").hide();
                jQuery("#servicio").hide();
                jQuery("#importe").hide();
                jQuery("#id_servicio").prop("disabled",true);
                jQuery("#id_producto").prop("disabled",true);
                jQuery("#templos").prop("disabled",true);
                jQuery("#importe_devolver").prop("disabled",true);
                jQuery("#id_carnet").prop("disabled",true);
                jQuery("#id_carnet_especial").prop("disabled",true);
                jQuery("#importe_en_euros").hide();
                jQuery("#templos").hide();
                jQuery("#carnets").hide();
            }
        }

        function PrecioProducto(datos) {
            var res = datos.split("|");
            document.form_devolver.importe_devolver.value = res[1];
        }

        function PrecioServicio(datos) {
           /* var res = datos.split("|");
            document.form_devolver.importe_devolver.value = res[1];
            document.form_devolver.templos.value = res[2]; */
        }

        function FormaPago(valor) {
            if (valor == "#templos") {
                document.getElementById("importe_en_euros").style.display = "none";
                document.getElementById("templos").style.display = "block";
                document.getElementById("carnets").style.display = "block";
                document.form_devolver.importe_devolver.disabled = true;
                document.form_devolver.templos.disabled = false;
                document.form_devolver.id_carnet.disabled = false;
                document.getElementById("especial").style.display = "none";
                document.form_devolver.id_carnet_especial.disabled = true;
            } else if (valor == "#especial") {
                document.getElementById("importe_en_euros").style.display = "none";
                document.getElementById("templos").style.display = "none";
                document.getElementById("carnets").style.display = "none";
                document.form_devolver.importe_devolver.disabled = true;
                document.form_devolver.templos.disabled = true;
                document.form_devolver.id_carnet.disabled = true;
                document.getElementById("especial").style.display = "block";
                document.form_devolver.id_carnet_especial.disabled = false;
                document.form_devolver.id_carnet_especial.value = <?php echo $id_carnet_especial; ?>;
            } else {
                document.getElementById("importe_en_euros").style.display = "block";
                document.getElementById("templos").style.display = "none";
                document.getElementById("carnets").style.display = "none";
                document.form_devolver.importe_devolver.disabled = false;
                document.form_devolver.templos.disabled = true;
                document.form_devolver.id_carnet.disabled = true;
                document.getElementById("especial").style.display = "none";
                document.form_devolver.id_carnet_especial.disabled = true;
            }
        }

        function Cerrar() {
            window.opener.location.reload();
            window.close();
        }

    </script>
</body>

</html>