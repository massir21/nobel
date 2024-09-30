<?php
foreach ($data_grafica_consolidado as $key => $row) {
	$meses[] = $row['mes'];
	$gastos[] = $row['total_gastos'];
	$ingresos[] = $row['total_ingresos'];
	$ganancias[] = $row['diferencia'];
	$objetivos[] = $row['objetivo'];
}
?>

<style>
	.custom-card {
		border: none;
		border-radius: 15px;
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
		transition: transform 0.5s ease-in-out;
	}

	.card-subtitle-small {
		font-size: 1.0rem;
		/* Ajusta el tamaño según tus preferencias */
	}

	.card-amount {
		font-size: 2.5rem;
		/* Ajusta el tamaño según tus preferencias */
		font-weight: bold;
		color: #4CAF50;
		/* Color verde de Material Design */
	}

	.card-amount-bills {
		font-size: 2.5rem;
		/* Ajusta el tamaño según tus preferencias */
		font-weight: bold;
		color: #424242;
		/* Color rojo de Material Design */
	}


	.card-amount-earnings {
		font-size: 2.5rem;
		/* Ajusta el tamaño según tus preferencias */
		font-weight: bold;
		color: #2196F3;
		/* Color rojo de Material Design */
	}
	.loader_balance{
		position: absolute;
		width: 100%;
		height: 100%;
		top: -20px;
		left: -20px;
		text-align: center;
		background-color: rgba(255, 255, 255, 0.5);
		z-index: 1;
		display: none;
	}

	/* ocultamos los elementos que no queremos ver al imprimir */
	@media print {
		.filtro {
			display: none;
		}

		.app-header {
			display: none;
		}
	}
</style>
<div class="loader_balance"></div>
<form method="post">
	<div class="card card-flush filtro">
		<div class="card-header align-items-end py-5 gap-2 gap-md-5">
			<div class="card-title">
				<div class="card-title">

				</div>
			</div>
			<div class="card-toolbar flex-row-fluid justify-content-start gap-5">

				<div class="col-md-2">
					<label class="form-label">Centro:</label>
					<select name="id_centro" id="centro" class="form-select form-select-solid">
						<option value="0">Todos</option>
						<?php if (isset($centros_todos)) {
							if ($centros_todos != 0) {
								foreach ($centros_todos as $key => $row) {
									if ($row['id_centro'] > 1) { ?>
										<option value='<?php echo $row['id_centro']; ?>' <?php if (isset($id_centro)) {
																																				if ($row['id_centro'] == $id_centro) {
																																					echo "selected";
																																				}
																																			} ?>>
											<?php echo $row['nombre_centro']; ?>
										</option>
						<?php }
								}
							}
						} ?>
					</select>
				</div>

				<div class="col-md-2">
					<label class="form-label">Mes:</label>
					<select name="mes" id="mes" class="form-select form-select-solid">
						<?php if (isset($todos_meses)) {
							foreach ($todos_meses as $mes_r) { ?>
								<option value='<?php echo $mes_r; ?>' <?php if ($mes_r == $mes) echo "selected"; ?>>
									<?php echo mesletra($mes_r); ?>
								</option>
						<?php }
						} ?>

					</select>
				</div>

				<div class="col-md-2">
					<label class="form-label">Año:</label>
					<select name="ano" id="anio" class="form-select form-select-solid">
						<?php if (isset($todos_anios)) {
							foreach ($todos_anios as $ano_r) { ?>
								<option value='<?php echo $ano_r; ?>' <?php if ($ano_r == $ano) echo "selected"; ?>>
									<?php echo $ano_r; ?>
								</option>
						<?php }
						} ?>
					</select>
				</div>
				<div class="col-md-2">
					<br>
					<button type="submit" class="btn btn-success pull-right" id="consultar"><i class="bi bi-search"></i></button>
				</div>
				<div class="col-md-3" style="text-align: right;">
					<br>
					<a class="btn btn-info pull-right" onclick="window.print()"><i class="bi bi-printer"></i></a>
				</div>
			</div>

			<!--<div class="w-auto ms-3">
                <label class="form-label">Proveedor:</label>
                <select name="proveedor_id" id="proveedor_id" class="form-select form-select-solid w-auto">
                    <option value="">Todos</option>
                    <?php if (isset($proveedor) && !empty($proveedor)) : ?>
                        <?php if (count($proveedor) > 0) : ?>
                            <?php foreach ($proveedor as $key => $row) : ?>
                                <option value="<?php echo $row['id_proveedor'] ?>"><?php echo $row['nombreProveedor'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </select>
            </div>-->
		</div>
	</div>

	<div class="card-body pl-6 pt-6" id="content">

		<div class="tab-content pt-3">

			<div class="tab-pane fade active show" id="tab1default">
				<h3 class="fw-bold fs-2x text-grey-700 border-bottom pb-3">Balance de <b class="lblMes"></b> <?= $ano ?></h3>

				<div class="row">


					<div class="col-md-4">
						<div class="card custom-card">
							<div class="card-body">
								<h4 class="card-title">Gastos</h4>
								<p class="card-text card-subtitle-small"><small class="lblMes"></small> <small class="lblAnio"></small></p>

								<div class="row">

									<?php if (isset($balance_gasto_consolidado) && !empty($balance_gasto_consolidado)) : ?>
										<div class="col montogastos">
											<?php if (count($balance_gasto_consolidado) > 0) : ?>
												<?php $this->load->view('gestion/balance/componentes/card_monto_gastos', ['registros' => $balance_gasto_consolidado]); ?>
											<?php endif; ?>
										</div>
									<?php endif; ?>

								</div><!-- row -->

								<div class="row">
									<div class="table-responsive">
										<table class="align-middle border-danger-subtle fs-7 gx-2 table table-bordered table-row-dashed table-striped">

											<thead class="">
												<tr class="text-start text-gray-600 fw-bold text-uppercase">
													<th class="text-start text-gray-600 fw-bold text-uppercase">Centro</th>
													<th class="text-end text-gray-600 fw-bold text-uppercase">Importe</th>
												</tr>
											</thead>


											<!-- detalles por centro -->
											<?php if (isset($balance_gasto_centro) && !empty($balance_gasto_centro)) : ?>
												<tbody class="grillagastos">

													<?php if (count($balance_gasto_centro) > 0) : ?>
														<?php $this->load->view('gestion/balance/componentes/listado_detalle_gastos', ['registros' => $balance_gasto_centro]); ?>
													<?php endif; ?>

												</tbody>
											<?php endif; ?>


										</table>
									</div><!-- table-responsive -->
								</div><!-- row -->

							</div>
						</div><!-- card  -->

					</div><!-- col -->

					<div class="col-md-4">

						<div class="card custom-card">
							<div class="card-body">
								<h4 class="card-title">Ingresos</h4>
								<p class="card-text card-subtitle-small"><small class="lblMes"></small> <small class="lblAnio"></small></p>

								<div class="row">

									<?php if (isset($balance_ingreso_consolidado) && !empty($balance_ingreso_consolidado)) : ?>
										<div class="col montoingresos">
											<?php if (count($balance_ingreso_consolidado) > 0) : ?>
												<?php $this->load->view('gestion/balance/componentes/card_monto_ingresos', ['registros' => $balance_ingreso_consolidado]); ?>
											<?php endif; ?>
										</div>
									<?php endif; ?>


								</div><!-- row -->

								<div class="row">
									<div class="table-responsive">
										<table class="align-middle border-danger-subtle fs-7 gx-2 table table-bordered table-row-dashed table-striped">

											<thead class="">
												<tr class="text-start text-gray-600 fw-bold  text-uppercase">
													<th class="text-start text-gray-600 fw-bold  text-uppercase">Centro</th>
													<th class="text-end text-gray-600 fw-bold  text-uppercase">Importe</th>
													<th class="text-end text-gray-600 fw-bold  text-uppercase">Objetivo</th>
													<th class="text-end text-gray-600 fw-bold  text-uppercase">%</th>
												</tr>
											</thead>


											<!-- detalles por centro -->
											<?php if (isset($balance_ingreso_centro) && !empty($balance_ingreso_centro)) : ?>

												<tbody class="grillaingresos">

													<?php if (count($balance_ingreso_centro) > 0) : ?>
														<?php 
															$passwiew = ['registros' => $balance_ingreso_centro];
															if (isset($balance_ingreso_consolidado) && !empty($balance_ingreso_consolidado)) {
																if (count($balance_ingreso_consolidado) > 0)  { 
																	if(!isset($balance_ingreso_consolidado[0]['facturas_emitidas'])){
																		$balance_ingreso_consolidado[0]['facturas_emitidas']=0;
																	}
																	$passwiew['balance_ingreso_consolidado_facturas_emitidas'] = $balance_ingreso_consolidado[0]['facturas_emitidas'];
																}
															}
															$this->load->view('gestion/balance/componentes/listado_detalle_ingresos', $passwiew); ?>
													<?php endif; ?>

												</tbody>

											<?php endif; ?>
										</table>

										<?php if (isset($balance_ingreso_consolidado) && !empty($balance_ingreso_consolidado)) : ?>
											<?php if (count($balance_ingreso_consolidado) > 0) : ?>
												<p class="text-end" style="position: absolute; right: 50px;"> *Facturas de ingreso: 
													<?php if(!isset($balance_ingreso_consolidado[0]['facturas_emitidas'])){$balance_ingreso_consolidado[0]['facturas_emitidas']=0;}
																		echo euros($balance_ingreso_consolidado[0]['facturas_emitidas']) ?></p>
											<?php endif; ?>
									<?php endif; ?>

										
									</div><!-- table-responsive -->
								</div><!-- row -->

							</div>



						</div>

					</div><!-- col -->







					<div class="col-md-4">

						<div class="card custom-card">
							<div class="card-body">
								<h4 class="card-title">Rentabilidad</h4>
								<p class="card-text card-subtitle-small"><small class="lblMes"></small> <small class="lblAnio"></small></p>

								<div class="row">

									<?php if (isset($balance_ganancias_consolidado) && !empty($balance_ganancias_consolidado)) : ?>
										<div class="col montoganado">
											<?php 
											if (count($balance_ganancias_consolidado) > 0) : ?>
												<?php $this->load->view('gestion/balance/componentes/card_monto_ganancia', ['registros' => $balance_ganancias_consolidado]); ?>
											<?php endif; ?>
										</div>
									<?php endif; ?>

								</div><!-- row -->

								<div class="row">
									<div class="table-responsive">
										<table class="align-middle border-danger-subtle fs-7 gx-2 table table-bordered table-row-dashed table-striped">

											<thead class="">
												<tr class="text-start text-gray-600 fw-bold text-uppercase">
													<th class="text-start text-gray-600 fw-bold text-uppercase">Centro</th>
													<th class="text-end text-gray-600 fw-bold text-uppercase">Importe</th>
													<th class="text-end text-gray-600 fw-bold  text-uppercase">Objetivo</th>
													<th class="text-end text-gray-600 fw-bold  text-uppercase">%</th>
												</tr>
											</thead>

											<!-- detalles por centro -->
											<?php if (isset($balance_ganancias_centro) && !empty($balance_ganancias_centro)) : ?>
												<tbody class="grillaganancias">
													<?php if (count($balance_ganancias_centro) > 0) : ?>
														<?php $this->load->view('gestion/balance/componentes/listado_detalle_ganancias', ['registros' => $balance_ganancias_centro]); ?>
													<?php endif; ?>
												</tbody>
											<?php endif; ?>

										</table>
									</div><!-- table-responsive -->
								</div><!-- row -->

							</div>
						</div>

					</div><!-- col -->

				</div><!-- row -->
				<div class="row pt-5">
					<!-- desgloce de gastos por familia -->
					<div class="col-md-4">
						<div class="card custom-card">
							<div class="card-body">
								<h4 class="card-title">Detalle de gastos por tipo</h4>
								<p class="card-text card-subtitle-small"><small class="lblMes"></small> <small class="lblAnio"></small></p>
								<div class="row">
									<?php $this->load->view('gestion/balance/componentes/listado_detalle_gastos_familias', ['registros' => $gastos_familias]); ?>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-8">
						<div class="card custom-card">
							<div class="card-body">
								<h5 class="card-title">Consolidado de ingresos, gastos y ganancias <?= $ano ?></h5>
								<p class="card-text">
								<p class="lblAnio"></p>
								</p>

								<div class="row grafica">
									<canvas id="lineChart" style="height: 300px;"></canvas>
								</div><!-- row -->
							</div>
						</div>

					</div><!-- row -->
				</div>

			</div><!-- tabcontent -->
		</div>

	</div>

	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

	<script>
		document.addEventListener("DOMContentLoaded", function() {
			var selectElementMes = document.getElementById("mes");
			var defaultOptionMes = selectElementMes.options[selectElementMes.selectedIndex];
			var defaultTextMes = defaultOptionMes.textContent;

			$(".lblMes").html(defaultTextMes);


			var selectElementAnio = document.getElementById("anio");
			var defaultOptionAnio = selectElementAnio.options[selectElementAnio.selectedIndex];
			var defaultTextAnio = defaultOptionAnio.textContent;

			$(".lblAnio").html(defaultTextAnio);

		});
		$("#consultar").click(function(){
			$(".loader_balance").fadeIn(200);
		})


		// Datos del gráfico

		var data = {
			labels: <?php echo json_encode($meses); ?>,
			datasets: [
				{
					label: 'Gastos',
					data: <?php echo json_encode($gastos); ?>,
					borderColor: 'rgb(66, 66, 66)',
					borderWidth: 2,
					fill: false
				},
				{
				
					label: 'Ingresos',
					data: <?php echo json_encode($ingresos); ?>,
					borderColor: 'rgb(76, 175, 80)',
					borderWidth: 2,
					fill: false
				},
				
				{
					label: 'Rentabilidad',
					data: <?php echo json_encode($ganancias); ?>,
					borderColor: 'rgb(33, 150, 243)',
					borderWidth: 2,
					fill: false
				},
				{
					label: 'Objetivo',
					data: <?php echo json_encode($objetivos); ?>,
					borderColor: 'rgb(1, 27, 214)',
					borderWidth: 2,
					fill: false
				}
			]
		};

		// Configuración del gráfico
		var options = {
			responsive: true,
			maintainAspectRatio: false,
			scales: {
				x: {
					type: 'category',
					labels: data.labels
				},
				y: {
					beginAtZero: true
				}
			}
		};

		// Obtener el contexto del lienzo
		var ctx = document.getElementById('lineChart').getContext('2d');

		// Crear el gráfico de línea
		var myLineChart = new Chart(ctx, {
			type: 'line',
			data: data,
			options: options
		});
	</script>