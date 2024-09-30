<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">PAGO DE PRESUPUESTO</h1>
    <div class="card card-flush m-5">
        <div class="card-body">
            <form id="form" action="<?php echo base_url(); ?>presupuestos/realizar_pago_presupuesto" role="form" method="post" name="form" onsubmit="return Guardar();">
                <div class="row mb-5 align-items-end">
                    <div class="col-8 mb-5">
                        <label for="" class="form-label">Cliente:</label>
                        <select name="id_cliente" id="id_cliente" class="form-select form-select-solid" data-placeholder="Elegir ..." onchange="Buscarpresupuestos();">
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

					<?php if(isset($presupuestos_cliente)){ ?>
						<div class="col-8 mb-5">
							<div class="row mb-5 align-items-end">
								<div class="col-12">
									<label for="" class="form-label">Presupuesto:</label>
									<select name="id_presupuesto" id="id_presupuesto" class="form-select form-select-solid" data-placeholder="Elegir ..." onchange="NotasClientes();" required data-error="Por favor, selecciona una opción.">
										<?php foreach ($presupuestos_cliente as $p => $presupuesto) { ?>
											<option value="<?=$presupuesto['id_presupuesto']?>" <?=(isset($id_presupuesto) && $id_presupuesto == $presupuesto['id_presupuesto'])?'selected':''?>>Presupuesto <?=$presupuesto['nro_presupuesto']?> (<?=euros($presupuesto['total_pendiente'] * -1)?>)</option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
					<?php } ?>
					


                    <div class="col-6 mb-5">
                        <label for="" class="form-label">Importe del pago</label>
                        <input type="number" class="form-control form-control-solid" name="importe" step="0.01" min="1" required />
                    </div>
                    <div class="col-6 mb-5">
                        <label for="" class="form-label">Tipo de pago</label>
                        <select class="form-select form-select-solid" name="tipo_pago">
                            <option value="#efectivo">Efectivo</option>
                            <option value="#tarjeta">Tarjeta</option>
							<option value="#transferencia">Transferencia</option>
							<option value="#paypal">Paypal</option>
							<option value="#tpv2">TPV</option>
                        </select>
                    </div>
                    <div class="col-12 mb-5">
                        <label for="" class="form-label">Concepto</label>
                        <textarea name="concepto" class="form-control form-control-solid"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-sm btn-secondary text-inverse-secondary m-2" type="button" onclick="window.close();">Cerrar sin Cambios</button>
                        <button class="btn btn-sm btn-primary text-inverse-primary" type="submit">Realizar Pago</button>
						<input type="hidden" name="accion" id="accion" value="submit">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function Buscarpresupuestos() {
            if (document.form.id_cliente.value === "") {
                alert("DEBES DE INDICAR UN CLIENTE");
                return false;
            } else {
				document.form.accion.value="buscarpresupuestos";
				document.form.submit();
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