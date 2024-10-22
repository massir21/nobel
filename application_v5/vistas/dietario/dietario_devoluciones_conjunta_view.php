<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">

    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">DEVOLUCIÓN CONJUNTA</h1>
    <?php
    if($accion=='error'){
        ?>
        <div class="alert alert-danger d-flex align-items-center p-5" style="margin-top:10px">
            <div class="d-flex flex-column">
                <h4 class="mb-1 text-danger">No se puede realizar la devolucón</h4>
                <span><?php echo $errorDevolucion;?></span>
            </div>
        </div>
    <?php
    }
    ?>

    <div class="card card-flush m-5">
        
        <div class="card-body p-5">
            <form id="form_devolver" action="<?php echo base_url(); ?>dietario/devoluciones/realizar" role="form" method="post" name="form_devolver">
                <div class="row">
                    <div class="col-3">
                        <strong>Cliente</strong>
                    </div>
                    <div class="col-3">
                        <strong>Servicio o Producto</strong>
                    </div>
                    <div class="col-3">
                        <strong>Forma devolución</strong>
                    </div>
                    <div class="col-3">
                        <strong>Importe en euros</strong>
                    </div>
                </div>
                <?php foreach ( $lineas_devolucion as $dietario ) { ?>
                <div class="row">    
                    <div class="col-3">
                        <?php echo $dietario[0]['cliente']; ?>
                    </div>
                    <div class="col-3">
                        <?php
                        $mostrarProducto=true;
                        $mostrarServicio=true;
                        $mostrarSaldo=true;
                        if(isset($dietario)){
                            $mostrarProducto=true && ((intval($dietario[0]['id_producto']) > 0));
                            $mostrarServicio=true && (intval($dietario[0]['id_servicio'] )> 0);
                            $mostrarSaldo=!$mostrarServicio && !$mostrarProducto && true && $dietario[0]['pago_a_cuenta']=1;
                        }
                        
                        if($mostrarProducto){
                            echo ucfirst($dietario[0]['producto']);
                        }
                        if($mostrarServicio){
                            echo ucfirst($dietario[0]['servicio']);
                        }
                        if($mostrarSaldo){
                            echo 'Pago a cuenta';
                        }
                        ?>
                    </div>
                    <div class="col-3">
                        <select id="forma_pago_<?php echo $dietario[0]['id_dietario']; ?>" name="forma_pago_<?php echo $dietario[0]['id_dietario']; ?>" data-placeholder="Elegir ..." class="form-select form-select-solid" tabindex="-1" aria-hidden="true" required>
                            <option value="#efectivo">Efectivo</option>
                            <option value="#tarjeta">Tarjeta</option>
                            <option value="#transferencia">Transferencia</option>
                            <option value="#tpv2">TPV2</option>
                            <option value="#saldo_cuenta" selected>Saldo Cliente</option>
                            <?php if ($this->session->userdata('id_centro_usuario') == 9) { ?>
                                <option value="#habitacion">Habitación</option>
                            <?php } ?>
                            <option value="#templos">Templos</option>
                            <?php if ($codigo_carnet_especial != "") { ?>
                                <option value="#especial">Carnet Especial</option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-3">
                        <input id="importe_devolver_<?php echo $dietario[0]['id_dietario']; ?>" type="number" step="0.01" min="0" name="importe_devolver_<?php echo $dietario[0]['id_dietario']; ?>" class="form-control form-control-solid"
                        <?= ($dietario[0]['importe_total_final'] > 0) ? 'value="' . $dietario[0]['importe_total_final'] . '"' :
                            ( $dietario[0]['importe_euros'] > 0  ? 'value="'.$dietario[0]['importe_euros'].'"' : "" ) ?> required />
                    </div>
                </div>
                <?php } ?>
                
                <div style="margin-top: 10px;" id="importe">
                    <div class="row mb-5 align-items-end">
                        <div class="col-md-12">
                            <label><b>Motivo de la Devolución</b></label>
                            <textarea id="motivo_devolucion" name="motivo_devolucion" class="form-control form-control-solid" required><?php
                                echo isset($original_param['motivo_devolucion']) ? $original_param['motivo_devolucion'] :'' ;
                            ?></textarea>
                        </div>
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