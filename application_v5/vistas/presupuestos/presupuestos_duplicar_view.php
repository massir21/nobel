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
		<form name="form_presupuesto" id="form_presupuesto" action="<?php echo base_url(); ?>Presupuestos/crearPresupuesto" method="post" onsubmit="return EsOk2();">
			<div class="row mb-5 pb-5 align-items-end border-bottom">
				<div class="col-md-3">
					<label for="" class="form-label">Elige el cliente:</label>
					<select name="id_cliente" id="id_cliente" class="form-select form-select-solid" data-placeholder="Elegir ...">
						<?php if (isset($registro) && $registro[0]['id_cliente'] > 0) { ?>
							<option value="<?= $registro[0]['id_cliente'] ?>" selected><?= $registro[0]['nombre'] . ' ' . $registro[0]['apellidos'] . ' (' . $registro[0]['telefono'] . ')'; ?></option>
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
				
				<div class="col-md-3">
				</div>
				
				<div class="col-md-3">
					<label for="" class="form-label">Fecha de validez:</label>
					<input type="date" id="fecha_validez" name="fecha_validez" value="<?=date('Y-m-d', strtotime('+ 15 days'));?>" class="form-control form-control-solid" placeholder="Fecha de validez" />
				</div>
				<div class="col-md-3">
					<label for="" class="form-label">Estado:</label>
					<select class="form-select form-select-solid" id="estado" name="estado" required>
						<option value="Borrador">Borrador</option>
						<option value="Pendiente">Pendiente</option>
					</select>
				</div>
			</div>

			<?php /*
			<div class="mb-5 pb-5 border-bottom">
				<h4>Productos</h4>
				<?php $i = 0;
				foreach ($productos_items as $i => $value) { ?>
					<div id="producto<?= $i ?>" class="itemcontent" data-item-id="<?= $productos_items[$i]['id_presupuesto_item'] ?>">
						<div class="row mb-1 align-items-end sumrow">
							<div class="col-lg-4 col-xl-5">
								<?= ($i == 0) ? '<label for="" class="form-label">Añadidos:</label>' : '<p></p>' ?>
								<select name="id_producto[]" id="id_producto<?= $i ?>" class="form-select form-select-solid" data-control="select2" data-placeholder="Elegir ..." onchange="buscarEmpleado(<?= $i ?>);">
									<option value=""></option>
									<?php if (isset($productos)) {
										if ($productos != 0) {
											foreach ($productos as $key => $row) { ?>
												<option value="<?php echo $row['id_producto']; ?>" <?= (isset($productos_items[$i]) && $productos_items[$i]['id_item'] == $row['id_producto']) ? 'selected' : '' ?>><?php echo strtoupper($row['nombre_producto']) . " (" . $row['nombre_familia'] . ")"; ?></option>
									<?php }
										}
									} ?>
								</select>
							</div>
							<div class="col-lg-2 col-xl-2">
								<?= ($i == 0) ? '<label for="" class="form-label">Cantidad</label>' : '' ?>
								<input type="number" name="productoCantidad[]" id="productoCantidad<?= $i ?>" class="form-control form-control-solid" value="<?= (isset($productos_items[$i])) ? $productos_items[$i]['cantidad'] : '1' ?>" step="1" min="0" max="100" required />
							</div>
							<div class="col-lg-2 col-xl-2">
								<?= ($i == 0) ? '<label for="" class="form-label">PVP €</label>' : '' ?>
								<input type="text" name="productoPrecio[]" id="productoPrecio<?= $i ?>" class="form-control form-control-solid" value="<?= (isset($productos_items[$i])) ? $productos_items[$i]['pvp'] : '1' ?>" readonly />
							</div>
							<div class="col-lg-2 col-xl-1">
								<?= ($i == 0) ? '<label for="" class="form-label">DTO %</label>' : '' ?>
								<input type="number" name="productoDescuento[]" id="productoDescuento<?= $i ?>" class="form-control form-control-solid" value="<?= (isset($productos_items[$i])) ? $productos_items[$i]['dto'] : '0' ?>" step="1" min="0" max="100" required />
							</div>
							<input type="hidden" name="ids_productos_items[]" value="<?= $productos_items[$i]['id_presupuesto_item'] ?>" />
							<div class="col-lg-2 col-xl-2">
								<button type="button" class="btn btn-danger text-inverse-danger btn-icon" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Eliminar Producto" onclick="EliminarItem(<?= $productos_items[$i]['id_presupuesto_item'] ?>)">
									<i class="fa fa-trash" aria-hidden="true"></i>
								</button>
							</div>
						</div>
					</div>
				<?php } ?>

				<?php $j = $i + 1;
				for ($i = $j; $i <= 15; $i++) { ?>
					<div id="producto<?= $i ?>" <?= ($i > $j) ? 'style="display:none;"' : '' ?>>
						<div class="row mb-1 align-items-end sumrow">
							<div class="col-lg-4 col-xl-5">
								<?= ($i == $j) ? '<label for="" class="form-label">Elegir Productos:</label>' : '<p></p>' ?>
								<select name="id_producto[]" id="id_producto<?= $i ?>" class="form-select form-select-solid" data-control="select2" data-placeholder="Elegir ..." onchange="buscarEmpleado(<?= $i ?>);">
									<option value=""></option>
									<?php if (isset($productos)) {
										if ($productos != 0) {
											foreach ($productos as $key => $row) { ?>
												<option value='<?php echo $row['id_producto']; ?>'><?php echo strtoupper($row['nombre_producto']) . " (" . $row['nombre_familia'] . ")"; ?></option>
									<?php }
										}
									} ?>
								</select>
							</div>
							<div class="col-lg-2 col-xl-2">
								<?= ($i == $j) ? '<label for="" class="form-label">Cantidad</label>' : '' ?>
								<input type="number" name="productoCantidad[]" id="productoCantidad<?= $i ?>" class="form-control form-control-solid" value="1" step="1" min="0" max="100" required />
							</div>
							<div class="col-lg-2 col-xl-2">
								<?= ($i == $j) ? '<label for="" class="form-label">PVP €</label>' : '' ?>
								<input type="text" name="productoPrecio[]" id="productoPrecio<?= $i ?>" class="form-control form-control-solid" readonly />
							</div>
							<div class="col-lg-2 col-xl-1">
								<?= ($i == 1) ? '<label for="" class="form-label">DTO %</label>' : '' ?>
								<input type="number" name="productoDescuento[]" id="productoDescuento<?= $i ?>" class="form-control form-control-solid" value="0" step="1" min="0" max="100" required />
							</div>
							<input type="hidden" name="ids_productos_items[]" value="0" />
							<div class="col-lg-2 col-xl-2">
								<button type="button" class="btn btn-info text-inverse-info btn-icon" id="botonProducto<?= $i ?>" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Añadir un nuevo Producto" onclick="habilitaProducto(<?= $i + 1 ?>)">
									<i class="fa fa-plus" aria-hidden="true"></i>
								</button>
								<button type="button" class="btn btn-danger text-inverse-danger btn-icon" data-reiniciar-row data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Anular producto">
									<i class="fa fa-times" aria-hidden="true"></i>
								</button>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
			*/ ?>

			<div class="mb-5 pb-5 border-bottom">
				<h4>Servicios</h4>
				<?php $i = 0;
				foreach ($servicios_items as $i => $value) { ?>
					<div id="servicio<?= $i ?>" class="itemcontent" data-item-id="<?= $servicios_items[$i]['id_presupuesto_item'] ?>">
						<div class="row mb-1 align-items-end sumrow">
							<div class="col-lg-4 col-xl-5">
								<?= ($i == 0) ? '<label for="" class="form-label">Añadidos:</label>' : '<p></p>' ?>
								<select name="id_servicio[]" id="id_servicio<?= $i ?>" class="form-select form-select-solid" data-control="select2" data-placeholder="Elegir ..." onchange="buscarServicio(<?= $i ?>);">
									<option value=""></option>
									<?php if (isset($servicios)) {
										if ($servicios != 0) {
											foreach ($servicios as $key => $row) { ?>
												<option value="<?php echo $row['id_servicio']; ?>" <?= (isset($servicios_items[$i]) && $servicios_items[$i]['id_item'] == $row['id_servicio']) ? 'selected' : '' ?>><?php echo strtoupper($row['nombre_servicio']) . " (" . $row['nombre_familia'] . ")"; ?></option>
									<?php }
										}
									} ?>
								</select>
							</div>
							<div class="col-lg-2 col-xl-2">
								<?= ($i == 0) ? '<label for="" class="form-label">Cantidad</label>' : '' ?>
								<input type="number" name="servicioCantidad[]" id="servicioCantidad<?= $i ?>" class="form-control form-control-solid" value="<?= (isset($servicios_items[$i])) ? $servicios_items[$i]['cantidad'] : '1' ?>" step="1" min="0" max="100" required />
							</div>
							<div class="col-lg-2 col-xl-2">
								<?= ($i == 0) ? '<label for="" class="form-label">PVP €</label>' : '' ?>
								<input type="text" name="servicioPrecio[]" id="servicioPrecio<?= $i ?>" class="form-control form-control-solid" value="<?= (isset($servicios_items[$i])) ? $servicios_items[$i]['pvp'] : '1' ?>" readonly />
							</div>
							<div class="col-lg-2 col-xl-1">
								<?= ($i == 0) ? '<label for="" class="form-label">DTO %</label>' : '' ?>
								<input type="number" name="servicioDescuento[]" id="servicioDescuento<?= $i ?>" class="form-control form-control-solid" value="<?= (isset($servicios_items[$i])) ? $servicios_items[$i]['dto'] : '0' ?>" step="1" min="0" max="100" required />
							</div>
							<input type="hidden" name="ids_servicios_items[]" value="<?= $servicios_items[$i]['id_presupuesto_item'] ?>" />
							<div class="col-lg-2 col-xl-2">
								<button type="button" class="btn btn-danger text-inverse-danger btn-icon" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Eliminar servicio" onclick="EliminarItem(<?= $servicios_items[$i]['id_presupuesto_item'] ?>)">
									<i class="fa fa-trash" aria-hidden="true"></i>
								</button>
							</div>
						</div>
					</div>
				<?php } ?>

				<?php $j = $i + 1;
				for ($i = $j; $i <= 15; $i++) { ?>
					<div id="servicio<?= $i ?>" <?= ($i > $j) ? 'style="display:none;"' : '' ?>>
						<div class="row mb-1 align-items-end sumrow">
							<div class="col-lg-4 col-xl-5">
								<?= ($i == $j) ? '<label for="" class="form-label">Elegir servicios:</label>' : '<p></p>' ?>
								<select name="id_servicio[]" id="id_servicio<?= $i ?>" class="form-select form-select-solid" data-control="select2" data-placeholder="Elegir ..." onchange="buscarServicio(<?= $i ?>);">
									<option value=""></option>
									<?php if (isset($servicios)) {
										if ($servicios != 0) {
											foreach ($servicios as $key => $row) { ?>
												<option value='<?php echo $row['id_servicio']; ?>'><?php echo strtoupper($row['nombre_servicio']) . " (" . $row['nombre_familia'] . ")"; ?></option>
									<?php }
										}
									} ?>
								</select>
							</div>
							<div class="col-lg-2 col-xl-2">
								<?= ($i == $j) ? '<label for="" class="form-label">Cantidad</label>' : '' ?>
								<input type="number" name="servicioCantidad[]" id="servicioCantidad<?= $i ?>" class="form-control form-control-solid" value="1" step="1" min="0" max="100" required />
							</div>
							<div class="col-lg-2 col-xl-2">
								<?= ($i == $j) ? '<label for="" class="form-label">PVP €</label>' : '' ?>
								<input type="text" name="servicioPrecio[]" id="servicioPrecio<?= $i ?>" class="form-control form-control-solid" readonly />
							</div>
							<div class="col-lg-2 col-xl-1">
								<?= ($i == $j) ? '<label for="" class="form-label">DTO %</label>' : '' ?>
								<input type="number" name="servicioDescuento[]" id="servicioDescuento<?= $i ?>" class="form-control form-control-solid" value="0" step="1" min="0" max="100" required />
							</div>
							<input type="hidden" name="ids_servicios_items[]" value="0" />
							<div class="col-lg-2 col-xl-2">
								<button type="button" class="btn btn-info text-inverse-info btn-icon" id="botonServicio<?= $i ?>" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Añadir un nuevo Servicio" onclick="habilitaServicio(<?= $i + 1 ?>)">
									<i class="fa fa-plus" aria-hidden="true"></i>
								</button>
								<button type="button" class="btn btn-danger text-inverse-danger btn-icon" data-reiniciar-row data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Anular servicio">
									<i class="fa fa-times" aria-hidden="true"></i>
								</button>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>




			<div class="row align-items-end border-bottom mb-5 pb-5">
				<div class="col-md-2">
					<label for="" class="form-label">Pago por cuota:</label>
					<input type="number" id="com_cuota" name="com_cuota" class="form-control form-control-solid" step=".01" value="<?= $registro[0]['com_cuota'] ?>" />
				</div>

				<div class="col-md-2">
					<label for="" class="form-label">Dto Euros:</label>
					<input type="number" id="dto_euros" name="dto_euros" class="form-control form-control-solid" step=".01" value="<?= $registro[0]['dto_euros'] ?>" />
				</div>
				<div class="col-md-2">
					<label for="" class="form-label">Dto Porcentaje:</label>
					<input type="number" id="dto_100" name="dto_100" class="form-control form-control-solid" step=".01" value="<?= $registro[0]['dto_100'] ?>" />
				</div>
				<div class="col-md-2">
					<button type="button" class="btn btn-warning text-inverse-warning" id="calcular" onClick="calcular()">Calcular total</button>
				</div>
				<div class="col-md-2">
					<label for="" class="form-label">Total:</label>
					<input type="number" id="totalpresupuesto" name="totalpresupuesto" readonly class="form-control form-control-solid  readonly" step=".01" value="<?= $registro[0]['totalpresupuesto'] ?>" />
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<input type="hidden" name="id_presupuesto" value="<?= $registro[0]['id_presupuesto']; ?>">
					<input type="hidden" id="estado_relacionado" name="estado_relacionado" value=""/>
					<input type="hidden" name="accion" value="<?= $accion; ?>">
					<button class="btn btn-primary text-inverse-primary" type="submit" id="guardar_presupuesto">Guardar</button>
				</div>
			</div>
		</form>
	</div>
</div>


<script>
	function EsOk2() {
		document.getElementById('nombreCliente').value = $('p').text();
		var combo = document.getElementById("id_empleado_venta");
		var selected = combo.options[combo.selectedIndex].text;
		document.getElementById("nombreEmpleado").value = selected;
		calcular();
		return true;
	}


	function buscarEmpleado(j) {
		var combo = document.getElementById("id_producto" + j);
		var selected = combo.options[combo.selectedIndex].text;
		var id_producto = ($("#id_producto" + j).val());
		var ar = <?php echo json_encode($productos) ?>;
		for (i = 0; i < ar.length; i++) {
			if (ar[i]['id_producto'] == id_producto) {
				$("#productoPrecio" + j).val(ar[i]['pvp']);
				$("#productoNombre" + j).val(selected);
			}
		}
	}

	function habilitaProducto(i) {
		$("#producto" + i).show();
	}

	function buscarServicio(j) {
		var combo = document.getElementById("id_servicio" + j);
		var selected = combo.options[combo.selectedIndex].text;
		var id_servicio = ($("#id_servicio" + j).val());
		var ar = <?php echo json_encode($servicios) ?>;
		for (i = 0; i < ar.length; i++) {
			if (ar[i]['id_servicio'] == id_servicio) {
				$("#servicioPrecio" + j).val(ar[i]['pvp']);
				$("#servicioNombre" + j).val(selected);
			}
		}
	}

	function habilitaServicio(i) {
		$("#servicio" + i).show();
	}

	function calcular() {
		var idCliente = document.getElementById("id_cliente").value;
		var fechaValidez = document.getElementById("fecha_validez").value;
		var estado = document.getElementById("estado").value;

		if (idCliente === "" || fechaValidez === "" || estado === "") {
			alert("Por favor, completa todos los campos obligatorios.");
			document.getElementById("guardar_presupuesto").disabled = true;
			return;
		}

		var elementosSumrow = document.getElementsByClassName("sumrow");
		var totalSinDescuento = 0;
		var totalConDescuento = 0;

		for (var i = 0; i < elementosSumrow.length; i++) {
			var inputsCantidad = elementosSumrow[i].querySelectorAll("input[id*='Cantidad']");
			var inputsPrecio = elementosSumrow[i].querySelectorAll("input[id*='Precio']");
			var inputsDescuento = elementosSumrow[i].querySelectorAll("input[id*='Descuento']");

			if (inputsCantidad.length > 0 && inputsPrecio.length > 0 && inputsDescuento.length > 0) {
				var cantidad = parseFloat(inputsCantidad[0].value);
				var precio = parseFloat(inputsPrecio[0].value);
				var descuento = parseFloat(inputsDescuento[0].value);

				if (!isNaN(cantidad) && !isNaN(precio) && !isNaN(descuento)) {
					var cifra = (cantidad * precio).toFixed(2);
					var cifraConDescuento = (cifra - (cifra * (descuento / 100))).toFixed(2);

					totalSinDescuento += parseFloat(cifra);
					totalConDescuento += parseFloat(cifraConDescuento);
				}
			}
		}

		var dtoEuros = parseFloat(document.getElementById("dto_euros").value);
		var dto100 = parseFloat(document.getElementById("dto_100").value);
		var importeTotalFinal = totalConDescuento.toFixed(2)
		if (!isNaN(dtoEuros) && dtoEuros > 0) {
			importeTotalFinal = (totalSinDescuento - dtoEuros).toFixed(2);
		} else if (!isNaN(dto100) && dto100 > 0) {
			importeTotalFinal = (totalSinDescuento - (totalSinDescuento * (dto100 / 100))).toFixed(2);
		}
		document.getElementById("totalpresupuesto").value = importeTotalFinal;
		document.getElementById("guardar_presupuesto").disabled = false;
		if (dtoEuros > 0 || dto100 > 0) {
			var inputsDescuento = document.querySelectorAll("input[id*='Descuento']");
			for (var j = 0; j < inputsDescuento.length; j++) {
				inputsDescuento[j].value = "0";
			}
		}
	}

	function EliminarItem(id_presupuesto_item) {
		var confirmacion = confirm('¿Estás seguro de que deseas eliminar el elemento?');
		if (confirmacion) {
			$.ajax({
				url: '<?= base_url() ?>Presupuestos/eliminar_item',
				method: 'POST',
				data: {
					id_presupuesto_item: id_presupuesto_item
				},
				success: function(response) {
					console.log(response)
					if (response.status == 'success') {
						$('[data-item-id="' + id_presupuesto_item + '"]').remove();
						calcular();
					} else {
						console.log('Error en la respuesta del servidor');
					}
				},
				error: function(xhr, status, error) {
					alert('Error en la petición AJAX:', error);
				}
			});
		} else {
			console.log('Acción cancelada por el usuario');
		}
	}

	$(document).ready(function() {

		/*if($('#estado').val() != 'Borrador'){
			$('.sumrow input').attr('readonly', 'readonly');
			$('.sumrow select').prop("disabled", true);
			$('.sumrow .btn, #calcular').attr('disabled', 'disabled');
			$('#estado option[value="Aceptado"], #estado option[value="Rechazado"], #estado option[value="Aceptado parcial"]').attr('disabled', 'disabled');
			$('#con_cuotas, #dtoo_euros, #dto_100').attr('disabled', 'disabled');
		}
		if($('#estado').val() == 'Pendiente'){
			$('#estado option').prop('disabled', false);
			$('#estado option[value="Borrador"]').attr('disabled', 'disabled');
		}*/

		$('.sumrow input, #dto_euros, #dto_100, #com_cuota').on('input', function() {
			calcular();
		});

		$('.sumrow select').on('select2:select', function() {
			calcular();
		});

		$('[data-reiniciar-row]').on('click', function() {
			var $sumrow = $(this).closest('.sumrow');
			$sumrow.find('select').each(function() {
				$(this).val(null).trigger('change');
			});
			$sumrow.find('input').val(0);
			//$sumrow.parent().hide();
			calcular();
		});

		// crear funcion para los distintos estados del presupuesto
		// y llamar a la funciona con la carga de la página y al cambiar el estado


		$('#estado').on('change', function(){
			//poner aqui que si el estado es
		})
	});
</script>