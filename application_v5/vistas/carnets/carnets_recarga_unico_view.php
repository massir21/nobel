<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">RECARGA DE CARNET ÚNICO<?= ($cliente != 0) ? " A " . strtoupper($cliente[0]['nombre'] . " " . $cliente[0]['apellidos']) : '' ?></h1>
    <div class="card card-flush m-5">
        <div class="card-body p-5">
            <?php if ($accion != "terminar") { ?>
                <form id="form_carnets" action="<?php echo base_url(); ?>carnets/recargar_unico/realizar" role="form" method="post" name="form_carnets">
                    <div class="row mb-5 align-items-end">
                        <?php if ($id_cliente == null) { 
                            $xcodigo = ""; ?>
                            <div class="col-md-6 mb-5">
                                <label for="" class="form-label">Cliente:</label>
                                <select name="id_cliente" id="id_cliente" class="form-select form-select-solid" data-placeholder="Elegir ..." onchange="BuscarUnico()">
                                    <?php if (isset($cliente_elegido) && $cliente_elegido !== null && isset($cliente_elegido[0]['id_cliente']) && $cliente_elegido[0]['id_cliente'] > 0) {?>
                                        <option value="<?=$cliente_elegido[0]['id_cliente']?>" selected><?= $cliente_elegido[0]['nombre'].' '.$cliente_elegido[0]['apellidos'].' ('.$cliente_elegido[0]['telefono'].')';?></option>
                                    <?php } ?>
                                </select>
                                <script type="text/javascript">
                                    $("#id_cliente").select2({
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
                        <?php } else { 
                            $xcodigo = "U" . $id_cliente; ?>
                            <input type="hidden" name="id_cliente" value="<?php echo $id_cliente; ?>" />
                        <?php } ?>
                         
                        <div class="col-md-6 mb-5">
                            <label for="" class="form-label">Carnet Único:</label>
                            <input name="numero_carnet" id="numero_carnet" class="form-control form-control-solid" type="text" placeholder="Nº Carnet" value="<?php echo $xcodigo; ?>" readonly="" required />
                        </div>

                        <div class="col-md-12">
                            <label for="" class="form-label">Tipo</label>
                            <select name="id_tipo" id="id_tipo" onchange="Servicios();" data-placeholder="Elegir ..." class="form-select form-select-solid" data-control="select2" required <?=($accion == "anadir" || $accion == "modificar")? 'disabled':''?>>
                                <option value=""></option>
                                <?php if (isset($tipos_carnets)) {
                                    if ($tipos_carnets != 0) {
                                        foreach ($tipos_carnets as $key => $row) {
                                            if ($row['precio'] > 0) {?>
                                                <option value="<?= $row['id_tipo']; ?>" <?=(isset($id_tipo) && $row['id_tipo'] == $id_tipo) ?"selected":''?>><?= $row['descripcion'] . " PVP: " . $row['precio'] . " " . $row['templos'] . "T"; ?></option>
                                            <?php }
                                        }
                                    }
                                } ?>
                            </select>
                            <input type="hidden" name="solo_pago" id="solo_pago" value="" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">  
                            <button class="btn btn-sm btn-secondary text-inverse-secondary m-2" type="button" onclick="window.close();">Cerrar sin Cambios</button>
                            <button class="btn btn-sm btn-primary text-inverse-primary" type="button" onclick="RealizarRecarga();">Realizar Recarga</button>
                        </div>
                    </div>
                </form>
            <?php } else {?>
                <div class="alert alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
                    <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">RECARGA REALIZADA</div>
                </div>
            <?php } ?>
        </div>
    </div>

    <script>
        $("#id_tipo").change(function() {
            var valor = $(this).val(); // Capturamos el valor del select
            var texto = $(this).find('option:selected').text(); // Capturamos el texto del option seleccionado
            termino = "EFECTIVO";
            posicion = texto.toLowerCase().indexOf(termino.toLowerCase());
            if (posicion !== -1){
                document.getElementById('solo_pago').value = "efectivo";
            }else{
                document.getElementById('solo_pago').value = "";
            }
        });

        function BuscarUnico() {
            codigo = "U" + document.getElementById('id_cliente').value;
            id_carnet = "90" + document.getElementById('id_cliente').value;
            if (codigo == "U" || codigo == "") {
                document.getElementById('numero_carnet').value = "";
            } else {
                document.getElementById('numero_carnet').value = codigo;
            }
        }

        function Servicios() {

        }

        function RealizarRecarga(templos, codigo) { // Quitar id_carnet y colocar codigo       
            document.form_carnets.submit();
            return true;
        }

        function PagoEfectivo() {
            var posicion_x;
            var posicion_y;
            var ancho = 750;
            var alto = 570;
            posicion_x = (screen.width / 2) - (ancho / 2);
            posicion_y = (screen.height / 2) - (alto / 2);
            window.open("<?php echo base_url(); ?>dietario/pagoeuros/ver_recargas/<?=(isset($id_cliente))? $id_cliente: ''?>/<?php echo $hoy_aaaammdd ?>", "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
        }
        <?php if ($accion == "realizar") { ?>
            document.location.href = "<?php echo base_url(); ?>dietario/recargar_carnet_manual_unico/<?=(isset($id_cliente))? $id_cliente: ''?>/<?php echo $hoy_aaaammdd; ?>/<?=(isset($id_carnet))?$id_carnet:''?>/<?=(isset($templos_recarga))?$templos_recarga:''?>/<?=(isset($precio))?$precio:''?>/<?=(isset($solo_pago))?$solo_pago:''?>";
        <?php } ?>

        <?php if ($accion == "terminar") { ?>
            window.opener.location.reload();
            window.close();
        <?php } ?>
    </script>
</body>
</html>