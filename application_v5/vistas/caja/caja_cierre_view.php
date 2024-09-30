<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
	<h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">Cierre de Caja para el a día (<?php echo $fecha; ?>)</h1>
	<div class="card card-flush m-5">
		<div class="card-body p-5">
			<form id="form_cierre_caja" action="<?php echo base_url(); ?>caja/cierre/comprobar/<?php echo $fecha; ?>" role="form" method="post" name="form_cierre_caja">
				<div class="row mb-5 align-items-end">
					<div class="col-4 mb-5">
						<label for="" class="form-label">Tarjetas:</label>
						<input type="number" name="tarjeta" step="0.01" value="<?= (isset($tarjeta)) ? $tarjeta : '0'; ?>" class="form-control form-control-solid" placeholder="Total cobros tarjeta" required>
					</div>

					<div class="col-4 mb-5">
						<label for="" class="form-label">TPV2:</label>
						<input type="number" name="tpv2" step="0.01" value="<?= (isset($tpv2)) ? $tpv2 : '0'; ?>" class="form-control form-control-solid" placeholder="Total cobros tpv2" required>
					</div>

					<div class="col-4 mb-5">
						<label for="" class="form-label">PayPal:</label>
						<input type="number" name="paypal" step="0.01" value="<?= (isset($paypal)) ? $paypal : '0'; ?>" class="form-control form-control-solid" placeholder="Total cobros paypal" required>
					</div>

					<div class="col-4 mb-5">
						<label for="" class="form-label">Transferencia:</label>
						<input type="number" name="transferencia" step="0.01" value="<?= (isset($transferencia)) ? $transferencia : '0'; ?>" class="form-control form-control-solid" placeholder="Total cobros transferencia" required>
					</div>

					<div class="col-4 mb-5">
						<label for="" class="form-label">Financiación:</label>
						<input type="number" name="financiado" step="0.01" value="<?= (isset($financiado)) ? $financiado : '0'; ?>" class="form-control form-control-solid" placeholder="Total cobros financiación" required>
					</div>

					<?php if ($this->session->userdata('id_centro_usuario') == 9) { ?>
						<div class="col-4 mb-5">
							<label for="" class="form-label">Habitación:</label>
							<input type="number" name="habitacion" step="0.01" value="<?= (isset($habitacion)) ? $habitacion : '0'; ?>" class="form-control form-control-solid" placeholder="Total cobros habitacion" required>
						</div>
					<?php } else { ?>
						<input type="hidden" name="habitacion" value="0">
					<?php } ?>
				</div>

				<div class="row mb-5 align-items-end border-bottom">
					<h3 style="font-weight:bold;">Efectivo </h3>
					<h4>Billetes</h4>
					<div class="col-3 mb-5">
						<label for="" class="form-label">50€ <span class="text-muted" id="50_euros_lbl">(<?= (isset($v50_euros)) ? $v50_euros : '0 €' ?>)</span></label>
						<input type="number" name="50_euros" min="0" max="9999" step="" value="<?= (isset($m50_euros)) ? $m50_euros : '0' ?>" class="form-control form-control-solid" required onchange="Calcular(50,this.value,'50_euros_lbl');">
					</div>
					<div class="col-3 mb-5">
						<label for="" class="form-label">20€ <span class="text-muted" id="20_euros_lbl">(<?= (isset($v20_euros)) ? $v20_euros : '0 €' ?>)</span></label>
						<input type="number" name="20_euros" min="0" max="9999" step="" value="<?= (isset($m20_euros)) ? $m20_euros : '0' ?>" class="form-control form-control-solid" required onchange="Calcular(20,this.value,'20_euros_lbl');">
					</div>
					<div class="col-3 mb-5">
						<label for="" class="form-label">10€ <span class="text-muted" id="10_euros_lbl">(<?= (isset($v10_euros)) ? $v10_euros : '0 €' ?>)</span></label>
						<input type="number" name="10_euros" min="0" max="9999" step="" value="<?= (isset($m10_euros)) ? $m10_euros : '0' ?>" class="form-control form-control-solid" required onchange="Calcular(10,this.value,'10_euros_lbl');">
					</div>

					<div class="col-3 mb-5">
						<label for="" class="form-label">5€ <span class="text-muted" id="5_euros_lbl">(<?= (isset($v5_euros)) ? $v5_euros : '0 €' ?>)</span></label>
						<input type="number" name="5_euros" min="0" max="9999" step="" value="<?= (isset($m5_euros)) ? $m5_euros : '0' ?>" class="form-control form-control-solid" required onchange="Calcular(5,this.value,'5_euros_lbl');">
					</div>

					<h4>Monedas</h4>
					<div class="col-3 mb-5">
						<label for="" class="form-label">2€ <span class="text-muted" id="2_euros_lbl">(<?= (isset($v2_euros)) ? $v2_euros : '0 €' ?>)</span></label>
						<input type="number" name="2_euros" min="0" max="9999" step="" value="<?= (isset($m2_euros)) ? $m2_euros : '0' ?>" class="form-control form-control-solid" required onchange="Calcular(2,this.value,'2_euros_lbl');">
					</div>

					<div class="col-3 mb-5">
						<label for="" class="form-label">1€ <span class="text-muted" id="1_euros_lbl">(<?= (isset($v1_euros)) ? $v1_euros : '0 €' ?>)</span></label>
						<input type="number" name="1_euros" min="0" max="9999" step="" value="<?= (isset($m1_euros)) ? $m1_euros : '0' ?>" class="form-control form-control-solid" required onchange="Calcular(1,this.value,'1_euros_lbl');">
					</div>

					<div class="col-3 mb-5">
						<label for="" class="form-label">50 Cents <span class="text-muted" id="50_cents_lbl">(<?= (isset($v50_cents)) ? $v50_cents : '0 €' ?>)</span></label>
						<input type="number" name="50_cents" min="0" max="9999" step="" value="<?= (isset($m50_cents)) ? $m50_cents : '0' ?>" class="form-control form-control-solid" required onchange="Calcular(0.50,this.value,'50_cents_lbl');">
					</div>

					<div class="col-3 mb-5">
						<label for="" class="form-label">20 Cents <span class="text-muted" id="20_cents_lbl">(<?= (isset($v20_cents)) ? $v20_cents : '0 €' ?>)</span></label>
						<input type="number" name="20_cents" min="0" max="9999" step="" value="<?= (isset($m20_cents)) ? $m20_cents : '0' ?>" class="form-control form-control-solid" required onchange="Calcular(0.20,this.value,'20_cents_lbl');">
					</div>

					<div class="col-3 mb-5">
						<label for="" class="form-label">10 Cents <span class="text-muted" id="10_cents_lbl">(<?= (isset($v10_cents)) ? $v10_cents : '0 €' ?>)</span></label>
						<input type="number" name="10_cents" min="0" max="9999" step="" value="<?= (isset($m10_cents)) ? $m10_cents : '0' ?>" class="form-control form-control-solid" required onchange="Calcular(0.10,this.value,'10_cents_lbl');">
					</div>

					<div class="col-3 mb-5">
						<label for="" class="form-label">5 Cents <span class="text-muted" id="5_cents_lbl">(<?= (isset($v5_cents)) ? $v5_cents : '0 €' ?>)</span></label>
						<input type="number" name="5_cents" min="0" max="9999" step="" value="<?= (isset($m5_cents)) ? $m5_cents : '0' ?>" class="form-control form-control-solid" required onchange="Calcular(0.5,this.value,'5_cents_lbl');">
					</div>

					<div class="col-3 mb-5">
						<label for="" class="form-label">2 Cents <span class="text-muted" id="2_cents_lbl">(<?= (isset($v2_cents)) ? $v2_cents : '0 €' ?>)</span></label>
						<input type="number" name="2_cents" min="0" max="9999" step="" value="<?= (isset($m2_cents)) ? $m2_cents : '0' ?>" class="form-control form-control-solid" required onchange="Calcular(0.2,this.value,'2_cents_lbl');">
					</div>

					<div class="col-3 mb-5">
						<label for="" class="form-label">1 Cents <span class="text-muted" id="1_cents_lbl">(<?= (isset($v1_cents)) ? $v1_cents : '0 €' ?>)</span></label>
						<input type="number" name="1_cents" min="0" max="9999" step="" value="<?= (isset($m1_cents)) ? $m1_cents : '0' ?>" class="form-control form-control-solid" required onchange="Calcular(0.1,this.value,'1_cents_lbl');">
					</div>
				</div>

				<?php if (isset($estado_2) || isset($estado_3) || isset($estado_4) || isset($estado_5) || isset($estado_6) || isset($estado_7) || isset($estado_8) || isset($estado_9) || isset($estado_10) || isset($estado_11)) { ?>
					<div class="alert alert-warning d-flex p-5">
						<i class="fa-exclamation-triangle fas fs-3qx me-5 text-warning"></i>
						<div class="d-flex flex-column">
							<?php if (isset($estado_2)) { ?>
								<span>Sobran <?php echo round($cuadre_efectivo, 2) . " €"; ?> de Efectivo.</span>
							<?php } ?>
							<?php if (isset($estado_3)) { ?>
								<span>Faltan <?php echo round($cuadre_efectivo, 2) . " €"; ?> de Efectivo.</span>
							<?php } ?>
							<?php if (isset($estado_4)) { ?>
								<span>Sobran <?php echo round($cuadre_tarjeta, 2) . " €"; ?> de Tarjeta.</span>
							<?php } ?>
							<?php if (isset($estado_5)) { ?>
								<span>Faltan <?php echo round($cuadre_tarjeta, 2) . " €"; ?> de Tarjeta.</span>
							<?php } ?>
							<?php if (isset($estado_6)) { ?>
								<span>Sobran <?php echo round($cuadre_habitacion, 2) . " €"; ?> de Habitación.</span>
							<?php } ?>
							<?php if (isset($estado_7)) { ?>
								<span>Faltan <?php echo round($cuadre_habitacion, 2) . " €"; ?> de Habitación.</span>
							<?php } ?>
							<?php if (isset($estado_8)) { ?>
								<span>Sobran <?php echo round($cuadre_transferencia, 2) . " €"; ?> de Transferencia.</span>
							<?php } ?>
							<?php if (isset($estado_9)) { ?>
								<span>Faltan <?php echo round($cuadre_transferencia, 2) . " €"; ?> de Transferencia.</span>
							<?php } ?>
							<?php if (isset($estado_10)) { ?>
								<span>Sobran <?php echo round($cuadre_tpv2, 2) . " €"; ?> de TPV2.</span>
							<?php } ?>
							<?php if (isset($estado_11)) { ?>
								<span>Faltan <?php echo round($cuadre_tpv2, 2) . " €"; ?> de TPV2.</span>
							<?php } ?>
							<?php if (isset($estado_12)) { ?>
								<span>Sobran <?php echo round($cuadre_paypal, 2) . " €"; ?> de PayPal.</span>
							<?php } ?>
							<?php if (isset($estado_13)) { ?>
								<span>Faltan <?php echo round($cuadre_paypal, 2) . " €"; ?> de PayPal.</span>
							<?php } ?>
							<?php if (isset($estado_14)) { ?>
								<span>Sobran <?php echo round($cuadre_financiado, 2) . " €"; ?> de Financiación.</span>
							<?php } ?>
							<?php if (isset($estado_15)) { ?>
								<span>Faltan <?php echo round($cuadre_financiado, 2) . " €"; ?> de Financiación.</span>
							<?php } ?>
							
						</div>
					</div>
				<?php } ?>


				<div class="row">
					<div class="col-md-12 text-center">
						<?php if (isset($oportunidad_cuadre) && !isset($estado_1)) {
							if ($oportunidad_cuadre < 2) { ?>
								<button type="submit" class="btn btn-sm btn-primary">Comprobación previa</button>
						<?php }
						} ?>

						<?php if (isset($oportunidad_cuadre)) {
							if ($oportunidad_cuadre == 1) { ?>
								<div class="alert alert-primary d-flex flex-column flex-sm-row p-5 mt-5">
									<div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center text-uppercase">Tienes una oportunidad más para cuadrar la caja</div>
								</div>
						<?php }
						} ?>

						<?php if (isset($oportunidad_cuadre)) {
							if ($oportunidad_cuadre == 2) { ?>
								<div class="alert alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
									<div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center text-uppercase">La caja se ha cerrado con descuadre</div>
								</div>
								<button type="button" class="btn btn-sm btn-danger" onclick="Cerrar();">Terminar</button>
						<?php }
						} ?>

						<?php if (isset($estado_1)) { ?>
							<div class="alert alert-success d-flex flex-column flex-sm-row p-5 mb-10">
								<div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center text-uppercase">Muy bien. La caja ha cuadrado</div>
							</div>
							<button type="button" class="btn btn-sm btn-success" onclick="Cerrar();">Terminar</button>
						<?php } ?>
					</div>
				</div>


			</form>
		</div>
	</div>

	<script>
		function Cerrar() {
			window.opener.location.reload();
			window.close();
		}

		function Calcular(moneda, cantidad, id) {
			valor = (moneda * cantidad);
			valor = valor.toFixed(2);
			document.getElementById(id).innerHTML = "(" + valor + " €)";
		}
	</script>
</body>

</html>