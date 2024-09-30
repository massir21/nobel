<div class="card card-flush">
	<div class="card-body pt-6">
		<?php if ($accion == "nuevo") {
			$cliente_id = (isset($cliente)) ? $cliente[0]['id_cliente'] : '';
			$actionform = base_url() . 'clientes/crear_presupuesto/' . $cliente_id;
		} else {
			$actionform = base_url() . 'clientes/actualizar_presupuesto/' . $registros[0]['id_presupuesto'];
		} ?>
		<form id="form" action="<?php echo $actionform; ?>" role="form" method="post" name="form">
			<div class="row mb-5 border-bottom">
				<?php if ($accion == "editar") { ?>
					<div class="col-md-6 col-lg-3 mb-5">
						<label class="form-label">Fecha Creación Nota</label>
						<div class="input-icon">
							<?php if (isset($registros)) {
								echo $registros[0]['fecha_creacion_ddmmaaaa'];
							} ?>
						</div>
					</div>
					<div class="col-md-6 col-lg-3 mb-5">
						<label class="form-label">Recepcionista Creador</label>
						<div class="input-icon">
							<?php if (isset($registros)) {
								echo $registros[0]['usuario_creacion'];
							} ?>
						</div>
					</div>
				<?php } ?>
				<div class="col-md-6 col-lg-3 mb-5">
					<label class="form-label">Estado</label>
					<select name="estado" class="form-control form-control-solid" required>
						<option value="Pendiente" <?= (isset($registros) && $registros[0]['estado'] == "Pendiente") ? "selected" : '' ?>>
							Pendiente
						</option>
						<?php if ($accion == "editar") { ?>
							<option value="Finalizada" <?= (isset($registros) && $registros[0]['estado'] == "Finalizada") ? "selected" : '' ?>>
								Finalizada
							</option>
						<?php } ?>
					</select>
				</div>

				<div class="col-md-12">
					<textarea name="nota" class="form-control form-control-solid" style="height: 200px;" required><?= (isset($registros)) ? $registros[0]['nota'] : '' ?></textarea>
				</div>
			</div>
			<div class="row mb-5 border-bottom">
				<div class="col-md-12" style="text-align: center;">
					<input class="btn btn-primary text-inverse-primary margin-top-20" type="submit" value="GUARDAR" />
				</div>
			</div>
		</form>
	</div>
</div>