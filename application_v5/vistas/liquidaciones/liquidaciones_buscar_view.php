<?php if (isset($estado_msn)) {
	if ($estado_msn > 0) { ?>
		<div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
			<div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE GUARDÓ CORRECTAMENTE</div>
			<button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
				<i class="fa-times fas fs-3 text-primary"></i>
			</button>
		</div>
	<?php } else { ?>
		<div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
			<div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">NO SE HA PODIDO REALIZAR EL REGISTRO DE DATOS
			</div>
			<button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
				<i class="fa-times fas fs-3 text-primary"></i>
			</button>
		</div>
<?php }
} ?>
<?php if (isset($borrado)) { ?>
	<div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
		<div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE BORRÓ CORRECTAMENTE</div>
		<button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
			<i class="fa-times fas fs-3 text-primary"></i>
		</button>
	</div>
<?php } ?>

<?php if (isset($actionno)) { ?>
	<div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
		<div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">
			<?= $actionno ?>
		</div>
		<button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
			<i class="fa-times fas fs-3 text-primary"></i>
		</button>
	</div>
<?php } ?>
<div class="card card-flush">
	<div class="card-header align-items-end py-5 gap-2 gap-md-5">
		<form action="" method="post" id="buscar_liquidacion" class="w-100">
			<div class="card-title w-100 justify-content-end flex-wrap">

				<div class="m-1">
					<select name="id_empleado" id="id_empleado" class="form-select form-select-solid w-auto" data-control="select2">
						<option value="">Selecciona un empleado</option>
						<?php foreach ($doctores as $rs) { ?>
							<option value="<?php echo $rs['id_usuario']; ?>" <?= (isset($id_empleado) && $id_empleado == $rs['id_usuario']) ? 'selected' : '' ?>>
								<?php echo $rs['nombre'] . ' ' . $rs['apellidos'] . ' (' . $rs['nombre_centro'] . ')'; ?>
							</option>
						<?php } ?>
					</select>
				</div>

				<?php /*<div class="m-1">
						   <div class="input-group">
							   <span class="input-group-text">Desde</span>
							   <input type="date" id="fecha_desde" name="fecha_desde" value="<?= (isset($fecha_desde)) ? $fecha_desde : '' ?>" class="form-control form-control-solid w-auto" placeholder="Fecha desde" />
						   </div>
					   </div> */ ?>

				<div class="m-1">
					<div class="input-group">
						<span class="input-group-text">Hasta</span>
						<input type="date" id="fecha_hasta" name="fecha_hasta" value="<?= (isset($fecha_hasta)) ? $fecha_hasta : '' ?>" class="form-control form-control-solid w-auto" placeholder="Fecha hasta" />
					</div>
				</div>
				<div class="m-1">
					<button type="submit" name="submit" class="btn btn-info btn-icon text-inverse-info"><i class="fas fa-search"></i></button>
				</div>
			</div>
		</form>
	</div>
</div>
<?php if (isset($id_empleado)) { ?>
	<div class="card card-flush mt-3">
		<div class="card-header align-items-end py-5 gap-2 gap-md-5">
			<div class="card-title w-100 justify-content-between">
				<div class="d-flex align-items-center position-relative my-1">
					<i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
					<input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="tabla_citas">
				</div>
				<h3>Lista de citas</h3>
				<div id="buttons"></div>
			</div>
		</div>

		<div class="card-body pt-6">

			<div class="table-responsive">
				<table id="tabla_citas" class="table datatable align-middle table-striped table-row-dashed fs-7">
					<thead class="">
						<tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
							<th class="">#</th>
							<th class="">Cliente</th>
							<th class="">Fecha</th>
							<th class="">Servicio</th>
							<th class="">PVP</th>
							<th class="">Dto.</th>
							<th class="">Dto.Pre.</th>
							<th class="">C.Fin.</th>
							<th class="">G.Lab.</th>
							<th class="">Total Cal.</th>
							<th class="noexport">Total Reg</th>
							<th class="noexport"></th>
						</tr>
					</thead>
					<tbody class="text-gray-700 fw-semibold">
						<?php
						$total = 0;
						if (isset($citas_agenda_liquidacion)) {
							foreach ($citas_agenda_liquidacion as $key => $value) {
								if (isset($fecha_hasta)) {
									$mes_busqueda = date('Y-m', strtotime($fecha_hasta));
									$mes_cita = date('Y-m', strtotime($value['fecha_cita']));
								} ?>
								<tr class="" data-liquidacion-cita="<?= $value['id_liquidacion_cita'] ?>">
									<td export-data="<?= $value['id_liquidacion_cita'] ?>">
										<div class="form-check form-check-solid form-switch form-check-custom fv-row mb-2 p-2 ">
											<input class="form-check-input w-35px h-20px" type="checkbox" id="id_liq_<?= $value['id_liquidacion_cita'] ?>" name="ids_liquidacion[]" value="<?= $value['id_liquidacion_cita'] ?>">
											<label class="form-label ms-5" for="id_liq_<?= $value['id_liquidacion_cita'] ?>"></label>
										</div>
									</td>
									<td class="">
										<?= $value['cliente'] ?>
									</td>
									<td class="" <?= ($mes_cita < $mes_busqueda) ? 'style="border: 4px solid #fdd100;border-radius: 1rem;"' : '' ?>>
										<?= $value['fecha_cita'] ?>
									</td>
									<td class="" export-data="<?= $value['servicio'] ?> (<?= $value['familia'] ?>) <?= ($value['dientes'] != '') ? 'D:' . $value['dientes'] : '' ?> <?= ($value['id_presupuesto'] != '') ? ' Pto: #' . $value['id_presupuesto'] : '' ?> -- O:<?= $value['observaciones'] . ' D:' . $value['notas_pago_descuento'] . ' P:' . $value['estado_relacionado'] ?>">
										<?php
										if ($value['observaciones'] != ' ' || $value['notas_pago_descuento'] != ' ' || $value['estado_relacionado'] != ' ') { ?>
											<i class="fa-info-circle fas fas-info-circle fs-1 mr-3 text-info" data-bs-toggle="tooltip" title="O:<?= $value['observaciones'] . ' D:' . $value['notas_pago_descuento'] . ' P:' . $value['estado_relacionado'] ?>"></i>
										<?php } ?>

										<?= $value['servicio'] ?> (
										<?= $value['familia'] ?>)
										<?= ($value['dientes'] != '') ? 'D:' . $value['dientes'] : '' ?>
										<?= ($value['id_presupuesto'] != '') ? '<br>Pto: #' . $value['id_presupuesto'] : '' ?>

									</td>
									<td export-data="<?= $value['pvp'] ?>" data-sort="<?= $value['pvp'] ?>">
										<input type="number" class="form-control form-control-sm w-90px" step=".01" name="pvp" value="<?= $value['pvp'] ?>" data-ini="<?= $value['pvp'] ?>" data-id-presupuesto-item="<?= $value['id_presupuesto_item'] ?>">
									</td>
									<td export-data="<?= $value['dto'] ?>" data-sort="<?= $value['dto'] ?>">
										<input type="number" class="form-control form-control-sm w-80px" step=".01" name="dto" value="<?= $value['dto'] ?>" data-ini="<?= $value['dto'] ?>" data-id-presupuesto-item="<?= $value['id_presupuesto_item'] ?>">
									</td>
									<td export-data="<?= $value['dtop'] ?>" data-sort="<?= $value['dtop'] ?>">
										<input type="number" class="form-control form-control-sm w-80px" step=".01" name="dtop" value="<?= $value['dtop'] ?>" data-ini="<?= $value['dtop'] ?>" data-id-presupuesto-item="<?= $value['id_presupuesto_item'] ?>">
									</td>
									<td export-data="<?= $value['com_financiacion'] ?>" data-sort="<?= $value['com_financiacion'] ?>">
										<input type="number" class="form-control form-control-sm w-80px" step=".01" name="com_financiacion" value="<?= $value['com_financiacion'] ?>" data-ini="<?= $value['com_financiacion'] ?>" data-id-presupuesto-item="<?= $value['id_presupuesto_item'] ?>">
									</td>
									<?php
									$palabras_clave = array('implante', 'corona', 'sobredentadura', 'protesis', 'hueso', 'membrana', 'chincheta', 'ferula', 'entrada', 'laboratorio');
									$valor_servicio = $value['servicio'];
									$alertbg = '';
									foreach ($palabras_clave as $palabra) {
										if (str_contains($valor_servicio, $palabra)) {
											if ($value['gastos_lab'] == 0) {
												$alertbg = 'border: 6px solid red;';
											}
											break;
										}
									} ?>

									<td export-data="<?= $value['gastos_lab'] ?>" data-sort="<?= $value['gastos_lab'] ?>" style="width: 80px;">

										<input type="number" class="form-control form-control-sm w-80px" step=".01" name="gastos_lab" value="<?= $value['gastos_lab'] ?>" data-ini="<?= $value['gastos_lab'] ?>" data-id-presupuesto-item="<?= $value['id_presupuesto_item'] ?>" style="<?= $alertbg ?> ">
									</td>
									<td class="totalrow w-120px" export-data="<?= $value['total'] ?>">
										<?= euros($value['total']) ?>
									</td>
									<td class="w-120px noexport">
										<?= euros($value['total']) ?>
									</td>
									<td class="noexport"><button type="button" class="btn btn-sm btn-icon btn-warning" data-actualizar-row data-bs-toggle="tooltip" title="Guardar datos de la cita"><i class="fas fa-save"></i></button></td>

								</tr>
						<?php $total += $value['total'];
							}
						} ?>
					</tbody>
				</table>
				<p class="text-center fs-3 fw-bold">Total citas: <span class="totalcitas">
						<?= euros($total); ?>
					</span></p>
			</div>
			<hr>
			<div class="text-center">
				<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-linea">Añadir linea de liquidación</button>
				<button type="button" class="btn btn-warning" data-calcular-comisiones>Calcular nueva liquidación</button>
				<button type="button" class="btn btn-outline btn-outline-info" data-bs-toggle="modal" data-bs-target="#modal-liquidaciones">Calcular con liquidación existente</button>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-add-linea" aria-labelledby="modal-add-lineaLabel" data-bs-focus="false" aria-hidden="true" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="form_add_linea_liquidacion" action="<?php echo base_url(); ?>Liquidaciones/nueva_linea" role="form" method="post" name="form_add_linea_liquidacion">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Añadir linea de liquidación</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="p-4 mb-5">
							<label for="" class="form-label">Concepto</label>
							<input type="text" name="concepto" id="concepto" class="form-control form-solid">
						</div>
						<div class="p-4 mb-5">
							<label for="" class="form-label">Importe (+/-)</label>
							<input type="number" class="form-control form-control-sm" step=".01" name="importe" value="" id="importe">
						</div>

					</div>
					<div class="modal-footer p-2 justify-content-center">
						<button type="button" class="btn btn-sm btn-secondary text-inverse-secondary" data-bs-dismiss="modal">Cerrar</button>
						<button type="submit" class="btn btn-sm btn-primary text-inverse-primary">Registrar Linea</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-card_comisiones" aria-labelledby="modal-card_comisionesLabel" data-bs-focus="false" aria-hidden="true" tabindex="-1">
		<div class="modal-dialog modal-xl" style="min-width: 95%;">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Lista de comisiones</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">

					<div class="card-header align-items-end py-5 gap-2 gap-md-5">
						<div class="card-title d-flex w-100 justify-content-between">
							<div class="d-flex align-items-center position-relative my-1">
								<i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
								<input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="tabla_comisiones">
							</div>
							<div id="buttonscom"></div>
						</div>
					</div>
					<div class="card-body pt-6">
						<div class="table-responsive mb-5">
							<table id="tabla_comisiones" class="table align-middle table-sm table-striped table-row-dashed fs-6 gy-5">
								<thead class="">
									<tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
										<th>ID</th>
										<th>Aplica sobre</th>
										<th>Tipo comisión</th>
										<th>Comisión</th>
										<th>Nº citas</th>
										<th>Monto válido(€)</th>
										<th>Comision (€)</th>
									</tr>
								</thead>
								<tbody class="text-gray-700 fw-semibold">
									<?php /* $pvpacumulado = 0;
													   $totalapagar = 0;
													   if (isset($comisiones) && is_array($comisiones)) {
														   foreach ($comisiones as $key => $value) {
															   if ($value['pvpacumulado'] > 0) { ?>
																   <tr class="" data-id_comision="<?= $value['id_comision'] ?>" data-pvpacumulado="<?= $value['pvpacumulado'] ?>" data-valoreuros="<?= $value['valoreuros'] ?>">
																	   <td class="col_id">
																		   <?= $value['id_comision'] ?>
																	   </td>
																	   <td><?= ($value['id_item'] > 0) ? $value['nombre_item'] : (($value['id_familia_item'] > 0) ? $value['nombre_familia'] : $value['item']); ?></td>
																	   <td>
																		   <?= $value['tipo'] ?>
																		   <?php if ($value['tipo'] == 'tramo') {
																			   echo '<span class="fs-8 text-muted d-block">' . euros($value['importe_desde']) . ' -> ' . euros($value['importe_hasta']) . '</span>';
																		   } ?>
																	   </td>
																	   <td><?= $value['comision'] ?>%</td>
																	   <td class="acumularo"><?= euros($value['pvpacumulado']) ?></td>
																	   <td>
																		   <?= euros($value['valoreuros']) ?>
																	   </td>

																   </tr>
													   <?php $pvpacumulado += $value['pvpacumulado'];
																   $totalapagar += $value['valoreuros'];
															   }
														   }
													   }  */ ?>
								</tbody>
							</table>

							<table id="tabla_comisiones_total" class="table align-middle table-sm table-striped table-row-dashed fs-6 gy-5">
								<thead class="">
									<tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
										<th colspan="5" class="text-end">PVP acumulado</th>
										<th id="pvp_acumulado_comisiones" colspan="1"></th>
									</tr>

									<tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
										<th colspan="5" class="text-end">Total a pagar</th>
										<th id="total_pagar_comisiones" colspan="1"></th>
									</tr>
								</thead>
							</table>
						</div>
						<hr>
						<div class="text-center">
							<input type="hidden" name="id_liquidacion" id="id_liquidacion" value="0">
							<button type="button" class="btn btn-warning" data-liquidar>Registrar liquidación</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-liquidaciones" aria-labelledby="modal-liquidaciones" data-bs-focus="false" aria-hidden="true" tabindex="-1">
		<div class="modal-dialog modal-xl" style="min-width: 95%;">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Lista de liquidaciones</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">

					<div class="card-header align-items-end py-5 gap-2 gap-md-5">
						<div class="card-title d-flex w-100 justify-content-between">
							<div class="d-flex align-items-center position-relative my-1">
								<i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
								<input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="tabla_liquidaciones">
							</div>
							<div id="buttonscom"></div>
						</div>
					</div>
					<div class="card-body pt-6">
						<div class="table-responsive mb-5">
							<table id="tabla_liquidaciones" class="table align-middle table-sm table-striped table-row-dashed fs-6 gy-5">
								<thead class="">
									<tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
										<th class="col_id">#</th>
										<th>Mes</th>
										<th class="col_validez">Citas hasta</th>
										<th class="col_presu">Total</th>
										<th class="col_aceptado">Fecha liquidacion</th>
										<th class="col_fecha">Us. liquidación</th>
										<th></th>
									</tr>
								</thead>
								<tbody class="text-gray-700 fw-semibold">
									<?php if (is_array($liquidaciones)) {
										foreach ($liquidaciones as $key => $value) { ?>
											<tr>
												<td class="col_id">
													<?= $value['id_liquidacion'] ?>
												</td>
												<td>
													<?= $value['mes'] ?>
												</td>
												<td>
													<?= $value['fecha_hasta'] ?>
												</td>
												<td>
													<?= $value['total'] ?>
												</td>
												<td>
													<?= $value['fecha_creacion'] ?>
												</td>
												<td>
													<?= $value['empleado'] ?>
												</td>
												<td><button type="button" class="btn btn-sm btn-icon btn-warning" data-ver data-bs-toggle="tooltip" title="Añadir a esta liquidacion" data-calcular-liquidacion="<?= $value['id_liquidacion'] ?>"><i class="fa-solid fa-add"></i></button></td>
											</tr>
									<?php }
									} ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		var buttonCommon = {
			exportOptions: {
				format: {
					body: function(data, row, column, node) {
						if (typeof $(node).attr('export-data') !== 'undefined') {
							data = $(node).attr('export-data');
						}
						return data;

					}
				},
				//orthogonal: "export",
				columns: ":not(.noexport)",

			}
		};

		var tablacitas = $('#tabla_citas').DataTable({
			lengthMenu: [
				[10, 25, 50, 100, -1],
				[10, 25, 50, 100, "Todos"],
			],
			pageLength: -1,
			"order": [
				[1, "asc"],
				[2, "asc"]
			],

			/*"dom": 't<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end"p>>',*/
			buttons: {
				buttons: [
					$.extend(true, {}, buttonCommon, {
						extend: 'excel',
						className: 'btn btn-sm btn-round btn-info',
						text: 'Exportar',
						title: 'citas_<?= $empleado[0]['nombre'] . "_" . $empleado[0]['apellidos'] . "_" . $fecha_hasta ?>',

					})
				],
			},
			language: {
				"sProcessing": "Procesando...",
				"sZeroRecords": "No se encontraron resultados",
				"sEmptyTable": "Ningún dato disponible en esta tabla",
				"sInfoEmpty": "No hay resultados",
				"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
				"sInfoPostFix": "",
				"sUrl": "",
				"sInfoThousands": ",",
				"sLoadingRecords": "Cargando...",
				"sLengthMenu": "<div class=\"\">_MENU_</div>",
				"sSearch": "<div class=\"\">_INPUT_</div>",
				"sSearchPlaceholder": "Escribe para buscar...",
				"sInfo": "_START_ de _END_ (_TOTAL_ total)",
				"oPaginate": {
					"sPrevious": "",
					"sNext": ""
				},
				"oAria": {
					"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
					"sSortDescending": ": Activar para ordenar la columna de manera descendente"
				}
			},

		});
		tablacitas.buttons(0, null).containers().appendTo('#buttons');

		$('[data-item]').on('click', function() {
			Swal.fire({
				title: 'Gasto de laboratorio',
				html: `Guardar los gastos de laboratorio que se muestran?`,
				showCancelButton: true,
				confirmButtonText: 'Si, guardar',
				showLoaderOnConfirm: true
			}).then((result) => {
				if (result.value) {
					var cambios = [];
					$('input[name="gastos_lab"]').each(function() {
						var valorActual = $(this).val();
						var valorInicial = $(this).data('value-ini');
						var idPresupuestoItem = $(this).data('id-presupuesto-item');
						if (valorActual != valorInicial) {
							cambios.push({
								id_presupuesto_item: idPresupuestoItem,
								gastos_lab: valorActual
							});
						}
					});
					if (cambios.length > 0) {
						$.ajax({
							url: '<?= base_url() ?>Liquidaciones/cambios_gastos_lab', // Reemplaza 'tu_url_php' con la URL de tu script PHP
							method: 'POST',
							data: {
								cambios: cambios
							},
							dataType: 'json',
							success: function(response) {
								if (response.success) {
									$('#buscar_liquidacion [type="submit"]').click();
									Swal.fire({
										type: 'success',
										title: 'Gastos de laboratorio actualizados con éxito. Se va a cargar de nuevo la página con los cambios realizados',
										willClose: function() {

										},
									});


								} else {
									Swal.fire({
										title: 'Error al actualizar los cambios en el servidor. Inténtelo de nuevo o recargue la página.',
										type: 'error',
										willClose: function() {},
									});
								}
							},
							error: function() {
								Swal.fire({
									type: 'error',
									title: 'Oops...',
									text: 'Error en la solicitud AJAX'
								});
							}
						});
					} else {
						Swal.fire({
							type: 'info',
							text: 'No hay cambios en los gastos de laboratorio'
						});
					}
				}
			})
		});

		function calcular_total() {
			var totalcitas = 0;
			var idsLiquidacion = [];

			tablacitas.rows().every(function() {
				var tr = this.node();
				var checkbox = $(tr).find('[name="ids_liquidacion[]"]');
				if ($(checkbox).is(':checked')) {
					var pvp = $(tr).find('[name="pvp"]').val();
					var dto = $(tr).find('[name="dto"]').val();
					var dtop = $(tr).find('[name="dtop"]').val();
					var com_financiacion = $(tr).find('[name="com_financiacion"]').val();
					var gastos_lab = $(tr).find('[name="gastos_lab"]').val();
					var totalrow = pvp - dto - dtop - com_financiacion - gastos_lab;
					totalcitas += totalrow;
					$(tr).find('.totalrow').html(totalrow.toFixed(2) + '€');
				}
			});

			$('.totalcitas').html(totalcitas.toFixed(2) + '€');
		}

		function calcular_row(tr) {
			var pvp = $(tr).find('[name="pvp"]').val();
			var dto = $(tr).find('[name="dto"]').val();
			var dtop = $(tr).find('[name="dtop"]').val();
			var com_financiacion = $(tr).find('[name="com_financiacion"]').val();
			var gastos_lab = $(tr).find('[name="gastos_lab"]').val();
			var totalrow = pvp - dto - dtop - com_financiacion - gastos_lab;
			$(tr).find('.totalrow').html(totalrow.toFixed(2) + '€');
		}
		$('[type="number"], [name="ids_liquidacion[]"]').on('input', function() {
			var tr = $(this).closest('tr');
			calcular_row(tr);
			calcular_total();
		});
		$(document).on('change', '[name="ids_liquidacion[]"]', calcular_total);


		$(document).on('click', '[data-actualizar-row]', function(event) {
			event.preventDefault();
			var tr = $(this).closest('tr');
			Swal.fire({
				title: 'Actualizar cita',
				html: '¿Guardar los datos de la cita?',
				showCancelButton: true,
				confirmButtonText: 'Si, guardar'
			}).then((result) => {
				if (result.value) {
					var formData = new FormData();
					var pvp = $(tr).find('[name="pvp"]').val();
					var dto = $(tr).find('[name="dto"]').val();
					var dtop = $(tr).find('[name="dtop"]').val();
					var com_financiacion = $(tr).find('[name="com_financiacion"]').val();
					var gastos_lab = $(tr).find('[name="gastos_lab"]').val();
					var id_liquidacion_cita = $(tr).attr('data-liquidacion-cita');

					formData.append('id_liquidacion_cita', id_liquidacion_cita);
					formData.append('gastos_lab', gastos_lab);
					formData.append('com_financiacion', com_financiacion);
					formData.append('dtop', dtop);
					formData.append('dto', dto);
					formData.append('pvp', pvp);

					$.ajax({
						url: '<?= base_url() ?>Liquidaciones/cambios_datos_cita',
						method: 'POST',
						data: formData,
						dataType: 'json',
						processData: false, // Evita que jQuery procese los datos
						contentType: false, // No establece automáticamente el tipo de contenido
						success: function(response) {
							if (response.success) {
								Swal.fire({
									type: 'success',
									title: 'Guardado',
									willClose: function() {
										// Haz algo después de cerrar la alerta de éxito
									},
								});
							} else {
								Swal.fire({
									title: 'Error al actualizar los cambios en el servidor. Inténtelo de nuevo o recargue la página.',
									type: 'error',
									willClose: function() {
										// Haz algo después de cerrar la alerta de error
									},
								});
							}
						},
						error: function() {
							Swal.fire({
								type: 'error',
								title: 'Oops...',
								text: 'Error en la solicitud AJAX'
							});
						}
					});
				}
			});
		});


		$('[data-calcular-liquidacion]').on('click', function() {
			var id_liquidacion = $(this).attr('data-calcular-liquidacion');
			var idsLiquidacion = [];
			tablacitas.rows().nodes().to$().find('input[name="ids_liquidacion[]"]:checked').each(function() {
				idsLiquidacion.push($(this).val());
			});
			if (idsLiquidacion.length > 0) {
				var idsLiquidacionStr = idsLiquidacion.join(',');
				var idEmpleado = $('#id_empleado').val();
				$.ajax({
					url: '<?= base_url() ?>Liquidaciones/comisiones_citas_liquidacion',
					type: 'POST',
					data: {
						ids_liquidaciones_cita: idsLiquidacionStr,
						id_usuario: idEmpleado,
						id_liquidacion: id_liquidacion
					},
					dataType: 'json',
					success: function(response) {
						$('#modal-liquidaciones').modal('hide');
						if (response.comisiones && response.comisiones.length > 0) {
							var tbody = $('#tabla_comisiones tbody');
							tbody.empty();
							var pvpacumulado = parseFloat(0);
							var totalapagar = parseFloat(0);
							$.each(response.comisiones, function(index, comision) {
								var comision_pvpacumulado = parseFloat(comision.valorable);
								var comision_valoreuros = parseFloat(comision.valoreuros);
								var simbolo = (comision.tipo == 'tramo' || comision.tipo == 'porcentaje') ? '%' : ((comision.tipo == 'fijo') ? '€/srv' : '€')

								var row = '<tr';
								row += ' class="" data-id_comision="' + comision.id_comision + '"';
								row += ' data-pvpacumulado="' + comision_pvpacumulado + '"';
								row += ' data-valoreuros="' + comision_valoreuros + '">';
								row += '<td class="col_id">' + comision.id_comision + '</td>';
								row += '<td>' + (comision.id_item > 0 ? comision.nombre_item : (comision.id_familia_item > 0 ? comision.nombre_familia : comision.item)) + '</td>';
								row += '<td>' + comision.tipo;
								if (comision.tipo == 'tramo') {
									row += '<span class="fs-8 text-muted d-block">' + comision.importe_desde + ' -> ' + comision.importe_hasta + '</span>';
								}
								row += '</td>';
								row += '<td>' + comision.comision + simbolo + '</td>';
								row += '<td>' + comision.num_citas + '</td>';
								row += '<td class="acumulado">' + comision_pvpacumulado.toFixed(2) + '€</td>';
								row += '<td>' + comision_valoreuros.toFixed(2) + '€</td>';
								row += '</tr>';
								$('#tabla_comisiones tbody').append(row);
								pvpacumulado += comision_pvpacumulado;
								totalapagar += comision_valoreuros;
							});
							$('#pvp_acumulado_comisiones').html(parseFloat(pvpacumulado).toFixed(2) + '€')
							$('#total_pagar_comisiones').html(parseFloat(totalapagar).toFixed(2) + '€')
							$('#id_liquidacion').val(id_liquidacion)
							$('#modal-card_comisiones').modal('show')
							$('#tabla_comisiones').DataTable().columns.adjust().responsive.recalc();
						} else {
							Swal.fire('No se encontraron comisiones.');
						}
					},
					error: function() {
						Swal.fire('Error en la solicitud AJAX.');
					}
				});
			} else {
				Swal.fire('Indica alguna cita para realizar el cálculo de comisiones');
			}
		});

		$('[data-calcular-comisiones]').on('click', function() {
			var idsLiquidacion = [];
			tablacitas.rows().nodes().to$().find('input[name="ids_liquidacion[]"]:checked').each(function() {
				idsLiquidacion.push($(this).val());
			});
			if (idsLiquidacion.length > 0) {
				var idsLiquidacionStr = idsLiquidacion.join(',');
				var idEmpleado = $('#id_empleado').val();
				$.ajax({
					url: '<?= base_url() ?>Liquidaciones/comisiones_citas',
					type: 'POST',
					data: {
						ids_liquidaciones_cita: idsLiquidacionStr,
						id_usuario: idEmpleado
					},
					dataType: 'json',
					success: function(response) {
						//if (response.comisiones && response.comisiones.length > 0) {
						var tbody = $('#tabla_comisiones tbody');
						tbody.empty();
						var pvpacumulado = parseFloat(0);
						var totalapagar = parseFloat(0);
						$.each(response.comisiones, function(index, comision) {
							var comision_pvpacumulado = parseFloat(comision.valorable);
							var comision_valoreuros = parseFloat(comision.valoreuros);
							//if (comision_pvpacumulado > 0) {
							var simbolo = (comision.tipo == 'tramo' || comision.tipo == 'porcentaje') ? '%' : ((comision.tipo == 'fijo') ? '€/srv' : '€')

							var row = '<tr';
							row += ' class="" data-id_comision="' + comision.id_comision + '"';
							row += ' data-pvpacumulado="' + comision_pvpacumulado + '"';
							row += ' data-valoreuros="' + comision_valoreuros + '">';
							row += '<td class="col_id">' + comision.id_comision + '</td>';
							row += '<td>' + (comision.id_item > 0 ? comision.nombre_item : (comision.id_familia_item > 0 ? comision.nombre_familia : comision.item)) + '</td>';
							row += '<td>' + comision.tipo;
							if (comision.tipo == 'tramo') {
								row += '<span class="fs-8 text-muted d-block">' + comision.importe_desde + ' -> ' + comision.importe_hasta + '</span>';
							}
							row += '</td>';
							row += '<td>' + comision.comision + simbolo + '</td>';
							row += '<td>' + comision.num_citas + '</td>';
							row += '<td class="acumulado">' + comision_pvpacumulado.toFixed(2) + '€</td>';
							row += '<td>' + comision_valoreuros.toFixed(2) + '€</td>';
							row += '</tr>';
							$('#tabla_comisiones tbody').append(row);
							console.log(pvpacumulado + ' + ' + comision.pvpacumulado + ' = ')
							pvpacumulado += comision_pvpacumulado;
							totalapagar += comision_valoreuros;
							//}
						});
						$('#pvp_acumulado_comisiones').html(parseFloat(pvpacumulado).toFixed(2) + '€')
						$('#total_pagar_comisiones').html(parseFloat(totalapagar).toFixed(2) + '€')
						//var tablacomisiones = datatabescomisiones();
						$('#modal-card_comisiones').modal('show')
						$('#tabla_comisiones').DataTable().columns.adjust().responsive.recalc();

						/*} else {
							Swal.fire('No se encontraron comisiones.');
						}*/
					},
					error: function() {
						Swal.fire('Error en la solicitud AJAX.');
					}
				});
			} else {
				Swal.fire('Indica alguna cita para realizar el cálculo de comisiones');
			}
		});

		function datatabescomisiones() {
			/*if ($.fn.DataTable.isDataTable('#tabla_comisiones')) {
				$('#tabla_comisiones').DataTable().destroy();
			}
			var tablacomisiones = $('#tabla_comisiones').DataTable({
				"order": [4, "desc"],
				"dom": 't<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end"p>>',
				buttons: {
					buttons: [{
						extend: 'excel',
						className: 'btn btn-sm btn-round btn-info',
						text: 'Exportar',
						footer: true,
						title: 'comisiones_'
					}]
				},
				language: {
					"sProcessing": "Procesando...",
					"sZeroRecords": "No se encontraron resultados",
					"sEmptyTable": "Ningún dato disponible en esta tabla",
					"sInfoEmpty": "No hay resultados",
					"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
					"sInfoPostFix": "",
					"sUrl": "",
					"sInfoThousands": ",",
					"sLoadingRecords": "Cargando...",
					"sLengthMenu": "<div class=\"\">_MENU_</div>",
					"sSearch": "<div class=\"\">_INPUT_</div>",
					"sSearchPlaceholder": "Escribe para buscar...",
					"sInfo": "_START_ de _END_ (_TOTAL_ total)",
					"oPaginate": {
						"sPrevious": "",
						"sNext": ""
					},
					"oAria": {
						"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
						"sSortDescending": ": Activar para ordenar la columna de manera descendente"
					}
				},
			});
			tablacomisiones.buttons(0, null).containers().appendTo('#buttonscom');

			return tablacomisiones;
			*/
		}

		$('[data-liquidar]').on('click', function() {
			var titleswal = ($('#id_liquidacion').val() > 0) ? 'Añadir a liquidación' : 'Registrar liquidación';
			Swal.fire({
				title: 'Registrar liquidación',
				html: `Se van a marcar las citas seleccionadas como ya liquidadas y no se mostrarán en nuevas consultas: <div><label class="form-label">Mes de liquidación</label><input class="form-control" type="month" id="liquidacion_mes" name="mes" value="<?= date('Y-m') ?>" min="2023-01" max="2055-12"></div>`,
				showCancelButton: true,
				confirmButtonText: 'Si, registrar liquidación',
				//showLoaderOnConfirm: true
			}).then((result) => {
				if (result.value) {
					var citas_liquidacion = [];

					var idsLiquidacion = [];
					tablacitas.rows().nodes().to$().find('input[name="ids_liquidacion[]"]:checked').each(function() {
						citas_liquidacion.push($(this).val());
					});

					if (citas_liquidacion.length > 0) {
						var comisiones_liquidacion = [];
						$('[data-id_comision]').each(function() {
							comisiones_liquidacion.push({
								id_comision: $(this).data('id_comision'),
								pvpacumulado: $(this).data('pvpacumulado'),
								total_comision: $(this).data('valoreuros')
							});
						});
						//if (comisiones_liquidacion.length > 0) {

						$('#loadingModal').modal('show');
						var formData = $('#buscar_liquidacion').serializeArray();
						formData.push({
							name: 'citas_liquidacion',
							value: JSON.stringify(citas_liquidacion)
						});
						formData.push({
							name: 'comisiones_liquidacion',
							value: JSON.stringify(comisiones_liquidacion)
						});
						formData.push({
							name: 'mes',
							value: $('#liquidacion_mes').val()
						});
						if ($('#id_liquidacion').val() > 0) {
							formData.push({
								name: 'id_liquidacion',
								value: $('#id_liquidacion').val()
							});
						}

						$.ajax({
							url: '<?= base_url() ?>Liquidaciones/registrarLiquidacion',
							method: 'POST',
							data: formData,
							dataType: 'json',
							beforeSend: function() {
								Swal.fire({
									title: 'Cargando',
									text: 'Por favor, espera...',
									showConfirmButton: false,
									allowOutsideClick: false,
									onBeforeOpen: () => {
										Swal.showLoading();
									}
								});
							},
							success: function(response) {
								if (response.success) {
									window.location.reload();

									//window.location.href = '<?= base_url() ?>Liquidaciones/lista'
								} else {
									Swal.fire({
										title: 'Error al registrar la liquidación Inténtelo de nuevo o recargue la página.',
										type: 'error',
										willClose: function() {},
									});
								}
							},
							error: function() {
								Swal.fire({
									type: 'error',
									title: 'Oops...',
									text: 'Error en la solicitud AJAX'
								});
							}
						});
						/*} else {
							Swal.fire({
								type: 'info',
								text: 'No hay comisiones para registrar en la liquidación'
							});
						}*/
					} else {
						Swal.fire({
							type: 'info',
							text: 'No hay citas en la liquidación'
						});
					}
				}
			})
		})

		$('#modal-card_comisiones').on('shown.bs.modal', function(event) {
			$('#tabla_comisiones').DataTable().columns.adjust().responsive.recalc();
		});

		$('#form_add_linea_liquidacion').on('submit', function(event) {
			event.preventDefault();
			var idEmpleado = $('#id_empleado').val();
			var formData = $(this).serializeArray();
			formData.push({
				name: 'id_usuario',
				value: idEmpleado
			}); // Agrega el nuevo parámetro
			$.ajax({
				url: '<?= base_url() ?>Liquidaciones/nueva_linea',
				method: 'POST',
				data: formData,
				dataType: 'json',
				success: function(response) {
					if (response.success) {
						Swal.fire({
							title: 'Concepto de liquidación registrado',
							type: 'success',
							willClose: function() {
								window.location.reload();
							},
						});


					} else {
						Swal.fire({
							title: 'Error al registrar la liquidación, Inténtelo de nuevo o recargue la página.',
							type: 'error',
							willClose: function() {},
						});
					}
				},
				error: function() {
					Swal.fire('Error en la solicitud AJAX.');
				}
			});
		});
		calcular_total();
	</script>
<?php } ?>