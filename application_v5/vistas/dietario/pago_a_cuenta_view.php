<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">PAGO A CUENTA</h1>
    <div class="card card-flush m-5">
        <div class="card-body">
            <form id="form" action="<?php echo base_url(); ?>dietario/realizar_pago_a_cuenta" role="form" method="post" name="form" onsubmit="return Guardar();">
                <div class="row mb-5 align-items-end">
                    <div class="col-12 mb-5">
                        <label for="" class="form-label">Cliente:</label>
                        <select name="id_cliente" id="id_cliente" class="form-select form-select-solid" data-placeholder="Elegir ..." >
                            <?php if (isset($cliente_elegido) && $cliente_elegido[0]['id_cliente'] > 0) { ?>
                                <option value="<?= $cliente_elegido[0]['id_cliente'] ?>" selected><?= $cliente_elegido[0]['nombre'] . ' ' . $cliente_elegido[0]['apellidos'] . ' (' . $cliente_elegido[0]['telefono'] . ')'; ?></option>
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
                    <?php /*<div class="col-12 mb-5">
                        <label for="" class="form-label">Importe del pago a cuenta</label>
                        <input type="number" class="form-control form-control-solid" name="importe" step="0.01" min="1" required />
                    </div>
                    <div class="col-12 mb-5">
                        <label for="" class="form-label">Tipo de pago</label>
                        <select class="form-select form-select-solid" data-control="select2" name="tipo_pago">
                            <option value="#efectivo">Efectivo</option>
                            <option value="#tarjeta">Tarjeta</option>
                            <option value="#tpv2">TPV2</option>
                            <option value="#paypal">PayPal</option>
                            <option value="#transferencia">Transferencia</option>
                            <option value="#financiado">Financiación</option>
                        </select>
                    </div>
                    */?>
                </div>
                <?php
                if($this->session->userdata('id_perfil') == 0){
                ?>
                    <input type="checkbox" id="saldo_ficticio" name="saldo_ficticio" />
                    <label for="saldo_ficticio">Saldo Ficticio</label>
                <?php
                }
                ?>
                <div class="row mb-5 align-items-end border-bottom">
                    <div class="col mb-5">
                        <label for="" class="form-label text-center">Imp. Efectivo</label>
                        <input name="pagado_efectivo" id="pagado_efectivo" class="form-control form-control-solid" type="number" step="0.01" min="0" value="0" style="text-align: right;"required />
                    </div>
                    <div class="col mb-5">
                        <label for="" class="form-label text-center">Imp. Tarjeta</label>
                        <input name="pagado_tarjeta" id="pagado_tarjeta" class="form-control form-control-solid" type="number" step="0.01" min="0" value="0" style="text-align: right;"required />
                        <p id="nota-efectivo" style="display:none; font-weight:bolder"></p>
                    </div>
                    <div class="col mb-5">
                        <label for="" class="form-label text-center">Imp. TPV2</label>
                        <input name="pagado_tpv2" id="pagado_tpv2" class="form-control form-control-solid" type="number" step="0.01" min="0" value="0" style="text-align: right;"required />
                        <p id="nota-tpv2" style="display:none; font-weight:bolder"></p>
                    </div>
                    <div class="col mb-5">
                        <label for="" class="form-label text-center">Imp. PayPal</label>
                        <input name="pagado_paypal" id="pagado_paypal" class="form-control form-control-solid" type="number" step="0.01" min="0" value="0" style="text-align: right;"required />
                        <p id="nota-tpv2" style="display:none; font-weight:bolder"></p>
                    </div>
                    <div class="col mb-5">
                        <label for="" class="form-label text-center">Imp. Transferencia</label>
                        <input name="pagado_transferencia" id="pagado_transferencia" class="form-control form-control-solid" type="number" step="0.01" min="0" value="0" style="text-align: right;"required />
                        <p id="nota-transferencia" style="display:none; font-weight:bolder"></p>
                    </div>  
                    
                    <div class="col mb-5">
                        <label for="" class="form-label text-center">Imp. Financiado</label>
                        <input name="pagado_financiado" id="pagado_financiado" class="form-control form-control-solid" type="number" step="0.01" min="0" value="0" style="text-align: right;"required />
                        <p id="nota-financiado" style="display:none; font-weight:bolder"></p>
                    </div>  
                
                    <input name="pagado_habitacion" type="hidden" value="0" />
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-sm btn-secondary text-inverse-secondary m-2" type="button" onclick="window.close();">Cerrar sin Cambios</button>
                        <button class="btn btn-sm btn-primary text-inverse-primary" type="submit" id="submit_pago">Realizar Pago</button>
                        <button class="btn btn-sm btn-primary text-inverse-primary" type="button" id="submit_ficticio">Realizar Pago</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $("#submit_ficticio").hide();
        $(function() {
            $("#saldo_ficticio").on( "change", function() {
                var $input = $( this );
                if($input.is( ':checked' )){
                    $("#submit_pago").hide();
                    $("#submit_ficticio").show();
                }
                else{
                    $("#submit_pago").show();
                    $("#submit_ficticio").hide();
                }
            });
            $("#submit_ficticio").on("click", function(){
                let pagado_efectivo = $("#pagado_efectivo").val();
                let pagado_tarjeta = $("#pagado_tarjeta").val();
                let pagado_tpv2 = $("#pagado_tpv2").val();
                let pagado_paypal = $("#pagado_paypal").val();
                let pagado_transferencia = $("#pagado_transferencia").val();
                let id_cliente = $("#id_cliente").val();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:  '<?php echo base_url() ?>Dietario/pago_ficticio',
                    type: 'POST',
                    async: true,
                    dataType: "json",
                    data:{
                        id_cliente : id_cliente,
                        pagado_tarjeta : pagado_tarjeta,
                        pagado_efectivo : pagado_efectivo,
                        pagado_tpv2 : pagado_tpv2,
                        pagado_paypal : pagado_paypal,
                        pagado_transferencia : pagado_transferencia
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Guardado correctamente',
                            text: 'Guardado con dietario #' + response.data.id_dietario + ' y saldo cliente #' + response.data.saldo_ficticio,
                            willClose: function() {
                                window.close();
                            },
                        });
                    },
                    error: function() {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Ha ocurrido un error'
                        })
                    }
                });
            });
        });
        function Guardar() {
            if (document.form.id_cliente.value === "") {
                Swal.fire("DEBES DE INDICAR UN CLIENTE");
                return false;
            } else {
                Swal.fire({
						title: 'Añadir saldo a cuenta',
						html: `¿Desesas añadir el saldo indicado a la cuenta del cliente?`,
						showCancelButton: true,
						confirmButtonText: 'Si, añadir saldo',
						showLoaderOnConfirm: true
					}).then((result) => {
			        if (result.value) {
                        document.form.submit();
					}
				})
            }
        }

        function Cerrar() {
            window.opener.location.reload();
            window.close();
        }
        <?php if ((isset($operacion_correcta)) && ($operacion_correcta == 1)) { ?>
            Cerrar();
        <?php } ?>
    </script>
</body>

</html>