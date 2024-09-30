<style>
	.dataTables_filter {
		text-align: right;
	}
</style>

<?php if (isset($mensaje)) { ?>
	<div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
		<div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center"><?= $mensaje ?></div>
		<button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
			<i class="fa-times fas fs-3 text-primary"></i>
		</button>
	</div>
<?php } ?>
<div class="card card-flush">
	<div class="card-body pt-6">
		<form name="form_presupuesto" id="form_presupuesto" action="<?php echo base_url(); ?>Presupuestos/actualizarEstadoPresupuesto" method="post">
			<div class="row mb-5">
				<div class="col-md-6">
					<label for="" class="form-label">Cliente</label>
					<h4><?= $registro[0]['nombre'] . ' ' . $registro[0]['apellidos'] . ' (' . $registro[0]['telefono'] . ')'; ?></h4>
				</div>

				<div class="col-md-6">
					<label for="" class="form-label">Fecha de validez:</label>
					<h4><?= $registro[0]['fecha_validez'] ?></h4>
				</div>
			</div>
			<div class="row mb-5">
				<div class="col-md-12">
					<div class="alert alert-info d-flex flex-column flex-sm-row p-5 mb-10">
						<div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">Marca la casilla de la izquierda de los items del presupuesto que han sido aceptados por el cliente, el estado del presupuesto pasará a:
							<ul>
								<li>Si marcas todas las casillas, el presupuesto será aceptado</li>
								<li>Si marcas alguna de las casillas, el presupuesto será aceptado parcialmente y será necesario añadir un comentario u observación</li>
								<li>Si no marcas ninguna casilla, el presupuesto será rechazado y será necesario añadir un comentario u observación</li>
							</ul>
						</div>
					</div>
				</div>
			</div>

			<div class="mb-5 pb-5 border-bottom">
				<?php if (count($servicios_items) > 0) { ?>
					<table class="table table-striped">
						<thead>
							<tr>
								<th></th>
								<th>Servicio</th>
								<th>Cantidad</th>
								<th>Dientes</th>
								<th>PVP</th>
								<th>% Dto</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($servicios_items as $i => $value) { ?>
								<tr>
									<td>
										<label class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
											<input class="form-check-input" type="checkbox" name="ids_presupuesto_item[]" value="<?= $value['id_presupuesto_item'] ?>" <?= ($registro[0]['estado'] == 'Pendiente' || $value['aceptado'] == 1) ? 'checked' : '' ?>>
										</label>
									</td>
									<td><?php echo strtoupper($value['nombre_item']) . " (" . $value['nombre_familia'] . ")"; ?></td>
									<td><?= $value['cantidad'] ?></td>
									<td><?php
										echo $value['dientes'];
										if ( $value['dientes'] != '' ) { ?>
											<input type="hidden" name="servicioDientes[]" id="servicioDientes<?= $value['id_presupuesto_item'] ?>" class="form-control form-control-solid" value="<?php echo $value['dientes']; ?>" readonly/>
											<button type="button" class="btn btn-primary btn-icon ms-2" id="botonServicio<?= $value['id_presupuesto_item'] ?>" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Ver Odontograma" onclick="habilitaOdontograma('<?= $value['id_presupuesto_item'] ?>')">
												<i class="fa fa-eye" aria-hidden="true"></i>
											</button>
										<?php } ?>
									</td>
									<td><?= $value['pvp'] ?> €</td>
									<td><?= $value['dto'] ?> %</td>
									<td><?php $total = $value['cantidad'] * $value['pvp'] * (1 - $value['dto'] / 100); ?>
										<?= $total ?>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				<?php } ?>
			</div>

			<div class="row border-bottom mb-5 pb-5">
				<div class="col-md-12">
					<label for="" class="form-label">Observaciones:</label>
					<textarea rows="3" id="estado_relacionado" name="estado_relacionado" class="form-control form-control-solid" placeholder="Observaciones"><?= $registro[0]['estado_relacionado'] ?></textarea>
				</div>
			</div>

			<div class="row align-items-top border-bottom mb-5 pb-5">
				<div class="col-md-3">
					<label for="" class="form-label fw-bold">Descuentos:</label>
					<ul>
						<li>Porcentaje: <span id="dto_100"><?= $registro[0]['dto_100'] ?></span> (<span id="dto_100_euros">0€</span>)</li>
						<li>Euros: <span id="dto_euros"><?= $registro[0]['dto_euros'] ?></span></li>
					</ul>
				</div>

				<div class="col-md-3">
					<label for="" class="form-label fw-bold">Subtotal:</label>
					<ul>
						<li>Servicios: <span id="subtotal"></span></li>
						<li>Despues de descuentos: <span id="subtotal2"></span></li>
					</ul>

				</div>

				<div class="col-md-3">
					<label for="" class="form-label fw-bold">Financiación:</label>
					<ul>
						<li>Com. apertura: <span id="apertura"><?= $registro[0]['apertura'] ?></span></li>
						<li>Nº cuotas: <span id="n_cuotas"><?= $registro[0]['n_cuotas'] ?></span></li>
						<li>€/cuota: <span id="totalcuota"><?= $registro[0]['totalcuota'] ?></span></li>
					</ul>
				</div>

				<div class="col-md-3">
					<label for="" class="form-label fw-bold">Total:</label>
					<ul class="list-style-none">
						<li class="fs-1">Total: <span id="total_aceptado" class="fw-bold"></span></li>
						<li>Total con financiacion: <span id="total_aceptado_financiacion"></span></li>
					</ul>
				</div>

				<?php if($registro[0]['es_repeticion'] == 1){ ?>
					<div class="col-md-12 text-center">
						<p class="fs-1">Presupuesto de repetición: El coste para el cliente es 0.00€</p>
					</div>
				<?php } ?>

			</div>
			<div class="row">
				<div class="col-md-12">
					<input type="hidden" name="id_presupuesto" value="<?= $registro[0]['id_presupuesto']; ?>">
					<input type="hidden" name="total_aceptado" value="<?= $registro[0]['total_aceptado']; ?>">
					<input type="hidden" name="estado" value="">
					<button class="btn btn-primary text-inverse-primary" type="submit" id="guardar_presupuesto">Guardar</button>
					<a class="btn btn-secondary" href="/presupuestos/">Regresar</a>
				</div>
			</div>
		</form>
	</div>
</div>




<?php
// **************************************************************
// Insertamos el Modal de seleccion de cientes, que se usa en NUEVO y EDICON
// **************************************************************

$die_data = array();
$die_data['die_editable'] = 0;
$this->load->view('presupuestos/presupuestos_modal_dientes', $die_data);
?>






<script>
	function sumarCifras() {
		var suma = 0;
		var suma2 = 0;

		$('input[name="ids_presupuesto_item[]"]:checked').each(function() {
			var tr = $(this).closest('tr');
			var lastCell = tr.find('td:last-child');
			var cifra = parseFloat(lastCell.text().trim());
			if (!isNaN(cifra)) {
				suma += cifra;
			}
		});
		// Obtener los valores de los elementos con los IDs "dto_100"
		var dto100 = parseFloat($('#dto_100').text().trim());
		if (!isNaN(dto100) && dto100 > 0) {
			var descuentoPorcentaje = dto100 / 100;
			var descuentoeuros = suma * descuentoPorcentaje;
			$('#dto_100_euros').text(descuentoeuros.toFixed(2)+'€');	
			suma2 = suma - descuentoeuros;
		}else{
			suma2 = suma;
		}
		var dto_euros = parseFloat($('#dto_euros').text().trim());
		if (!isNaN(dto_euros) && dto_euros > 0) {
			var descuentoeuros = dto_euros;	
			suma2 = suma2 - descuentoeuros;
		}else{
			suma2 = suma2;
		}
		$('#subtotal').text(suma.toFixed(2)+'€');	
		$('#subtotal2').text(suma2.toFixed(2)+'€');	

		var comApertura = parseFloat($('#apertura').text().trim());
		var totalfinanciacion = parseFloat((suma2 * comApertura / 100) + suma2);
		var n_cuotas = parseFloat($('#n_cuotas').text().trim());
		var totalcuota = parseFloat(totalfinanciacion / n_cuotas);
		
		$('#totalcuota').text(totalcuota.toFixed(2)+'€');	
		$('#subtotal2').text(suma2.toFixed(2)+'€');	
		$('#subtotal2').text(suma2.toFixed(2)+'€');	
		$('#total_aceptado').text(suma2.toFixed(2));
		$('[name="total_aceptado"]').val(suma2.toFixed(2));
		$('#total_aceptado_financiacion').text(totalfinanciacion.toFixed(2));
	}

	$('input[name="ids_presupuesto_item[]"]').on('change', function() {
		sumarCifras();
	});

	$(document).ready(function() {
		sumarCifras();
		$('#form_presupuesto').submit(function(event) {
			var checkboxes = $('input[name="ids_presupuesto_item[]"]');
			var estadoInput = $('input[name="estado"]');
			var estadoRelacionadoInput = $('#estado_relacionado');

			if (checkboxes.length === checkboxes.filter(':checked').length) {
				estadoInput.val('Aceptado');
				estadoRelacionadoInput.prop('required', false);
			} else if (checkboxes.filter(':checked').length > 0) {
				estadoInput.val('Aceptado parcial');
				estadoRelacionadoInput.prop('required', true);
			} else {
				estadoInput.val('Rechazado');
				estadoRelacionadoInput.prop('required', true);
			}
			<?php if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 3) { ?>
				if (estadoRelacionadoInput.prop('required') && estadoRelacionadoInput.val().trim() === '') {
					event.preventDefault();
					Swal.fire('Por favor, complete el campo "Observaciones".');
				}
			<?php }else{ ?>
				if (estadoRelacionadoInput.prop('required')) {
					event.preventDefault();
					Swal.fire('Solo los encargados pueden rechazar los presupuestos.');
				}
			<?php } ?>

			
		});
	});
</script>