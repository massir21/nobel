<div class="row">
	<?php if ($this->session->userdata('id_perfil') == 0 && $registro[0]['revisado'] == 1) { ?>
		<div class="col-md-12">
			<h4 class="text-center p-3 bg-info-subtle mb-3">PRESUPUESTO REVISADO</h4>
		</div>
	<?php } ?>

	<div class="col-md-6">
		<b>Cliente</b>: <?php echo $registro[0]['nombre'] . ' ' . $registro[0]['apellidos'] ?>
		<?php if ($registro[0]['dni'] != '') { ?>
			<br><b>DNI</b>: <?php echo $registro[0]['dni'] ?>
		<?php } ?>
	</div>
	<div class="col-md-6">
		<b>Válido hasta</b>: <?php echo  fechaES($registro[0]['fecha_validez']) ?>
		<br><b>Estado</b>: <?php echo  $registro[0]['estado'] ?>
	</div>
	<?php if ($registro[0]['estado_relacionado'] != '') { ?>
		<div class="col-md-12">
			<p class="border card card-body my-3 py-3"><?= $registro[0]['estado_relacionado'] ?></p>
		</div>
	<?php } ?>

</div>



<ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
	<li class="nav-item">
		<a class="nav-link active" data-bs-toggle="tab" href="#tab_presupuesto">Presupuesto</a>
	</li>
	<?php if ($this->session->userdata('id_perfil') <> 6) {  ?>
		<?php if ($registro[0]['es_repeticion'] != 1) { ?>
			<li class="nav-item">
				<a class="nav-link" data-bs-toggle="tab" href="#tab_pagos">Pagos</a>
			</li>
		<?php } ?>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="tab" href="#tab_docs">Documentos</a>
		</li>
	<?php } ?>

</ul>
<div class="tab-content" id="TabContent">
	<div class="tab-pane fade show active" id="tab_presupuesto" role="tabpanel">
		<?php
		$dientes_seleccionados = '';
		if (count($servicios_items) > 0) {
			foreach ($servicios_items as $i => $value) {
				if ($value['dientes'] != '') {
					$dientes_seleccionados .= ',' . $value['dientes'];
				}
			}
		}

		if ($dientes_seleccionados != '') { ?>
			<br>
			<?php
			function get_diente_imagen($die)
			{
				$logo = FCPATH . '/assets_v5/media/dientes/' . $die . '.png';
				$type = pathinfo($logo, PATHINFO_EXTENSION);
				$data = file_get_contents($logo);
				//return 'data:image/' . $type . ';base64,' . base64_encode($data);
				return '/assets_v5/media/dientes/' . $die . '.png';
			}
			function mostrar_un_diente($die, $orientac, $dientes_seleccionados)
			{
				$bgSelect = '';
				$pos = strpos($dientes_seleccionados, ',' . $die);
				if ($pos !== false) {
					$bgSelect = ' background: #CCCCCC; ';
				}
				echo "<td style='width: 18px; text-align: center; {$bgSelect}'>\n";
				if ($orientac == 'supe') {
					echo "<img src='" . get_diente_imagen('raiz-diente-' . $die) . "' vspace='0' style='height: 16px;display: block; margin: 0 auto 2px;'>\n";
					echo "<img src='" . get_diente_imagen('diente-' . $die) . "' vspace='0' style='height: 10px;display: block; margin: 0 auto;'>\n";
					echo '<span class="text-muted">' . $die . '</span>';
				}


				if ($orientac == 'bajo') {
					echo '<span class="text-muted">' . $die . '</span>';
					echo "<img src='" . get_diente_imagen('diente-' . $die) . "' vspace='0' style='height: 10px;display: block; margin: 0 auto;'>\n";
					echo "<img src='" . get_diente_imagen('raiz-diente-' . $die) . "' vspace='0' style='height: 16px;display: block; margin: 2px auto 0;'>\n";
				}
				echo "</td>\n";
			}
			function mostrar_dientes($desde, $hasta, $sentido, $alignTabla, $orientac, $dientes_seleccionados)
			{
				echo "<table class='tabla-dientes' align='{$alignTabla}'><tr>\n";
				if ($sentido == -1) {
					for ($i = $desde; $i >= $hasta; --$i) {
						mostrar_un_diente($i, $orientac, $dientes_seleccionados);
					}
				} else {
					for ($i = $desde; $i <= $hasta; ++$i) {
						mostrar_un_diente($i, $orientac, $dientes_seleccionados);
					}
				}
				echo "</tr></table>\n";
			}
			?>

			<table class="table" align="center" style="width: 80%; margin-bottom: 20px;">
				<tr>
					<td><?php echo mostrar_dientes(18, 11, -1, 'right', 'supe', $dientes_seleccionados); ?></td>
					<td width="20"></td>
					<td><?php echo mostrar_dientes(21, 28, 1, 'left', 'supe', $dientes_seleccionados); ?></td>
				</tr>
				<tr>
					<td><?php echo mostrar_dientes(55, 51, -1, 'right', 'supe', $dientes_seleccionados); ?></td>
					<td width="20"></td>
					<td><?php echo mostrar_dientes(61, 65, 1, 'left', 'supe', $dientes_seleccionados); ?></td>
				</tr>
				<tr>
					<td><?php echo mostrar_dientes(85, 81, -1, 'right', 'bajo', $dientes_seleccionados); ?></td>
					<td width="20"></td>
					<td><?php echo mostrar_dientes(71, 75, 1, 'left', 'bajo', $dientes_seleccionados); ?></td>
				</tr>
				<tr>
					<td><?php echo mostrar_dientes(48, 41, -1, 'right', 'bajo', $dientes_seleccionados); ?></td>
					<td width="20"></td>
					<td><?php echo mostrar_dientes(31, 38, 1, 'left', 'bajo', $dientes_seleccionados); ?></td>
				</tr>
			</table>

		<?php } ?>
		<br>
		<?php
		$totalitems = 0;
		$total = 0;
		?>

		<?php if (count($servicios_items) > 0) { ?>
			<table class="table table-striped" style="width: 100%; margin-bottom: 20px;">
				<thead>
					<tr>
						<th style="text-align: left">Servicio</th>
						<th style="text-align: right">G. Lab</th>
                        <th style="text-align: right">Cost Financ.</th>
						<th style="text-align: right">Cantidad</th>
						<th style="text-align: right">Dientes</th>
						<th style="text-align: right">PVP(€)</th>
						<th style="text-align: right">Dto(%)</th>
						<th style="text-align: right">Dto(€)</th>
						<th style="text-align: right">Total</th>
                        <th style="text-align: center">Liq.</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($servicios_items as $i => $value) {
						if ($this->session->userdata('id_perfil') <> 6) {  ?>
							<tr>
								<td style="width: 38%;">
									<?php
									if (($value['id_cita'] == 0 || $value['estado_cita'] == 'Anulada' || $value['estado_cita'] == 'No vino') && ($this->session->userdata('id_perfil') == 0 /*|| $this->session->userdata('id_perfil') == 3*/)) { ?>
										<?php
                                         if($value['aceptado']==2){ ?>
                                            <i class="fas fa-calendar-times text-danger rounded-circle fs-3 border border-danger p-1" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Item Rechazado Motivo: <?php echo $comentarios; ?>" ></i>
                                         <?php }
                                         else{
                                         ?>
                                            <i class="fas fa-calendar-plus text-primary rounded-circle fs-3 border border-primary p-1" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Añadir cita" data-add-cita="<?= $value['id_presupuesto_item'] ?>"></i> 
                                            <i class="fas fa-calendar-times text-warning rounded-circle fs-3 border border-warning p-1" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Rechazar Presupuesto" data-rechazar-cita="<?= $value['id_presupuesto_item'] ?>"></i>
                                         <?php
                                         }
                                         ?>
                                         <?php
                                         if($value['aceptado']==2){ ?>
                                         	<span class="text-danger">Este item ha sido rechazado</span>
										 
                                         <?php }
                                         else{
                                         ?>
                                            <span>Añadir una cita para este servicio</span>
                                         <?php
                                         }
                                         ?>
										<?php }

									if ($value['id_cita'] > 0 && ($value['estado_cita'] != 'Anulada' && $value['estado_cita'] != 'No vino')) {
										if ($this->session->userdata('id_perfil') == 0) { ?>
											<i class="fas fa-calendar-day text-warning rounded-circle fs-3 border border-wharning p-1" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Editar cita" data-edit-cita="<?= $value['id_presupuesto_item'] ?>" data-fecha-cita="<?= $value['fecha_hora_inicio'] ?>" data-id-doctor="<?= $value['id_usuario_empleado'] ?>" data-estado-cita="<?= $value['estado_cita'] ?>" data-estado-dietario="<?= $value['estado_dietario'] ?>"></i>
										<?php }
										if ($value['estado_cita'] == 'Finalizado') { ?>
											<i class="fas fa-check-circle text-success fs-3 border border-success p-1"></i>
										<?php } ?>
										<span class="fecha_hora"><?= $value['fecha_hora_inicio'] . ' - ' . $value['empleado']; ?></span>
									<?php } ?>
									<br>
									<?php echo strtoupper($value['nombre_item']) . " (" . $value['nombre_familia'] . ")"; ?>
								</td>

								<td style="text-align: right;">
									<div class="input-group mb-3">
										<input type="number" class="form-control" step=".01" name="gastos_lab" value="<?= $value['gastos_lab'] ?>">
										<button class="btn btn-icon btn-warning" type="button" data-item="<?= $value['id_presupuesto_item'] ?>"><i class="fas fa-save"></i></button>
									</div>
								</td>
                                <td style="text-align: right;"><?= $value['comi_fin']?$value['comi_fin'].'€':''; ?></td>
								<td style="text-align: right;"><?= $value['cantidad'] ?></td>
								<td style="text-align: right;">
									<button type="button" class="btn btn-sm btn-info" data-dientes="<?= $value['id_presupuesto_item'] ?>" onclick="habilitaOdontograma2(<?= $value['id_presupuesto_item'] ?>)"><?= ($value['dientes'] != '') ? $value['dientes'] : '-' ?></button>
								</td>
								<td style="text-align: right;"><?= $value['pvp'] ?></td>
								<td style="text-align: right;"><?= $value['dto'] ?></td>
								<td style="text-align: right;"><?= $value['dto_euros'] ?></td>
								<td style="text-align: right;">
									<?php $total = $value['cantidad'] * $value['pvp'] * (1 - $value['dto'] / 100) - $value['dto_euros'];
									echo $total;
									?>
								</td>
                                <td style="text-align: center">
                                    <?php
                                    if ($value['id_cita'] > 0 && ($value['estado_cita'] == 'Finalizado') ){
                                        $found=false;
                                        foreach($liquidaciones as $liq){
                                            if($liq['id_presupuesto_item']==$value['id_presupuesto_item']){
                                                ?>
                                                <i class="bi bi-check2-square"></i>
                                                <?php
                                                $found=true;
                                            }
                                        }
                                        if(!$found){
                                            if ($this->session->userdata('id_perfil') == 0){
                                            ?>
                                            <a href="javascript:void(0);" class="btn-generarliquidacion" title="Generar Liquidacion" data-itemc="<?php echo $value['id_cita'];?>"><i class="fas fa-coins text-primary"></i></a>
                                             <?php
                                            }
                                        }
                                    }
                                    ?>
                                </td>
							</tr>
						<?php } else { ?>
							<tr>
								<td style="width: 52%;">
									<?php if ($value['id_cita'] > 0 && ($value['estado_cita'] != 'Anulada' && $value['estado_cita'] != 'No vino')) {
										if ($value['estado_cita'] == 'Finalizado') { ?>
											<i class="fas fa-check-circle text-success fs-3 border border-success p-1"></i>
										<?php } ?>
										<span class="fecha_hora"><?= $value['fecha_hora_inicio'] . ' - ' . $value['empleado']; ?></span>
										<br>
									<?php } ?>
									<?php echo strtoupper($value['nombre_item']) . " (" . $value['nombre_familia'] . ")"; ?>
								</td>
								<td style="text-align: right;"><?= $value['gastos_lab'] ?></td>
                                <td style="text-align: right;"><?= $value['comi_fin'] ?></td>
								<td style="text-align: right;"><?= $value['cantidad'] ?></td>
								<td style="text-align: right;"><?= ($value['dientes'] != '') ? $value['dientes'] : '-' ?></td>
								<td style="text-align: right;"><?= $value['pvp'] ?></td>
								<td style="text-align: right;"><?= $value['dto'] ?></td>
								<td style="text-align: right;"><?= $value['dto_euros'] ?></td>
								<td style="text-align: right;">
									<?php $total = $value['cantidad'] * $value['pvp'] * (1 - $value['dto'] / 100) - $value['dto_euros'];
									echo $total;
									?>
								</td>
							</tr>

						<?php } ?>
						<?php
						$totalitems += $total;
						if ($value['dientes'] != '') {
							$dientes_seleccionados .= ',' . $value['dientes'];
						}
						?>
					<?php } ?>
				</tbody>
			</table>
		<?php }

		$this->load->view('presupuestos/presupuestos_modal_dientes_detalle_presupuesto');

		?>


		<?php
		$dto_euros = 0;
		$dto_100 = 0;
		$dto_100_euros = 0;
		$com_cuota = 0;
		$importecomision = 0;
		$precioCuota = 0;
		?>
		<table class="table table-striped" style="width: 100%;">
			<tbody>
				<tr>
					<td style="text-align: right; width: 75%;">Subtotal</td>
					<td style="text-align: right; width: 25%;"><?= euros($totalitems) ?></td>
				</tr>
				<?php
				if ($totalitems > 0) {
					if ($registro[0]['dto_euros'] > 0) {
						$dto_euros = $registro[0]['dto_euros'];
						$totalitems = $totalitems - $dto_euros; ?>
						<tr>
							<td style="text-align: right; width: 75%;">Dto</td>
							<td style="text-align: right; width: 25%;"><?= euros($registro[0]['dto_euros']) ?></td>
						</tr>
					<?php } else if ($registro[0]['dto_100'] > 0) {
						$dto_100 = $registro[0]['dto_100'];
						$dto_100_euros = $dto_100 / 100 * $totalitems;
						$totalitems = $totalitems - $dto_100_euros; ?>
						<tr>
							<td style="text-align: right; width: 75%;">Dto <?= $registro[0]['dto_100'] ?>%</td>
							<td style="text-align: right; width: 25%;"><?= euros($dto_100_euros) ?></td>
						</tr>
				<?php }
				} ?>

				<?php if ($registro[0]['com_cuota'] > 0) {
					$com_cuota = $registro[0]['com_cuota'];
					$importecomision = $com_cuota / 100 * $totalitems;
					$totalitems = $totalitems + $importecomision;
					$precioCuota = $totalitems / 38;
					$preciototalfinal = '38 cuotas de ' . euros($precioCuota); ?>
					<tr>
						<td style="text-align: right; width: 75%;">Comisión financiación <?= $registro[0]['com_cuota'] ?>%</td>
						<td style="text-align: right; width: 25%;"><?= euros($importecomision) ?></td>
					</tr>
				<?php } else {
					$preciototalfinal = euros($totalitems);
				} ?>
				<tr>
					<td style="text-align: right; width: 75%;">Total</td>
					<td style="text-align: right; width: 25%;"><?= ($registro[0]['es_repeticion'] == 1) ? 'Es repetición: 0.00€' : $preciototalfinal ?></td>
				</tr>

			</tbody>
		</table>
	</div>

	<?php if ($this->session->userdata('id_perfil') <> 6) {  ?>
		<?php if ($registro[0]['es_repeticion'] != 1) { ?>
			<div class="tab-pane fade" id="tab_pagos" role="tabpanel">
				<?php $totapagado = 0; ?>
				<table class="table table-striped" style="width: 100%; margin-bottom: 20px;">
					<thead>
						<tr>
							<th style="text-align: left">Fecha /hora</th>
							<th style="text-align: right">Modo de pago</th>
							<th style="text-align: right">Importe</th>
							<th style="text-align: right">Estado</th>
							<?php if ($this->session->userdata('id_perfil') == 0) { ?>
								<th style="text-align: right"></th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php if (is_array($pagos)) { ?>
							<?php foreach ($pagos as $i => $value) { ?>
								<tr class="align-middle <?= ($value['estado'] == 'Pagado') ? 'bg-warning-active' : '' ?>">
									<td style="width: 25%; text-align: left;" class="ps-2"><?php echo $value['fecha_hora_pago']; ?></td>
									<td style="width: 25%; text-align: right;"><?= ($value['tipo_pago'] != '') ? ltrim(str_replace('#', ' ', $value['tipo_pago']), ' ') : '';  ?></td>
									<td style="width: 25%; text-align: right;">
										<?= $value['importe_euros'] ?>
										<?= ($value['comisionfinanciacion'] > 0) ? '<br><small>(' . $value['comisionfinanciacion'] . '€ com.)</small>' : '' ?>
									</td>
									<td style="width: 25%; text-align: right;" class="pe-2">
										<?php if ($value['tipo_pago'] != '' && (strpos($value['tipo_pago'], 'transferencia') !== false || strpos($value['tipo_pago'], 'financiado') !== false)) { ?>
											<?php if ($value['estado'] == 'Pendiente justificante') { ?>
												<button type="button" class="btn btn-sm btn-warning p-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Añadir justificante de pago" data-add-justificante="<?php echo $value['id_dietario']; ?>" data-presupuesto-justificante="<?php echo $value['id_presupuesto']; ?>">Cargar justificante <i class="fa-solid fa-file-import fs-6"></i></button>
											<?php } ?>
											<?php if ($value['estado'] == 'Pagado') { ?>
												<button type="button" class="btn btn-sm btn-info p-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Ver justificante de pago" data-ver-justificante="<?php echo $value['justificante_pagado']; ?>">Ver justificante <i class="fa-solid fa-file fs-6"></i></button>
											<?php } ?>
										<?php } else { ?>
											<?= $value['estado'] ?>
										<?php } ?>
									</td>
									<?php if ($this->session->userdata('id_perfil') == 0) { ?>
										<td>
											<button type="button" class="btn btn-sm btn-icon btn-danger" data-borrar-pago="<?= $value['id_dietario'] ?>" data-bs-toggle="tooltip" title="Eliminar pago"><i class="fa-solid fa-trash"></i></button>
										</td>
									<?php } ?>
								</tr>
								<?php
								$totapagado += $value['importe_euros']; ?>
							<?php } ?>
						<?php } ?>
					</tbody>
				</table>

				<table class="table table-striped" style="width: 100%;">
					<tbody>
						<tr>
							<td style="text-align: right; width: 75%;">Total</td>
							<td style="text-align: right; width: 25%;"><?= euros($totapagado) ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		<?php } ?>

		<div class="tab-pane fade" id="tab_docs" role="tabpanel">
			<button type="button" class="btn btn-warning mb-3" data-bs-toggle="collapse" data-bs-target="#form_documentos">Nuevo documento</button>
			<form id="form_documentos" enctype="multipart/form-data" action="<?php echo base_url(); ?>clientes/nuevodocumento" method="post" name="form_documentos" class="collapse">
				<div class="border border-dark card">
					<div class="card-body">
						<div class="row mb-5 border-bottom align-items-end py-4">
							<input type="hidden" name="id_cliente" value="<?php echo $id_cliente; ?>">
							<div class="col-md-5">
								<label for="formFile" class="form-label">Carga el documento</label>
								<input class="form-control" type="file" id="formFile" name="nuevodoc">
							</div>
							<div class="col-md-3">
								<label class="form-label">Fecha</label>
								<input type="date" id="fecha_estudio" name="fecha_estudio" value="" class="form-control form-control-solid" placeholder="Hasta" />
							</div>
							<div class="col-md-3">
								<label class="form-label">Tipo</label>
								<select class="form-select form-select-solid" data-control="select2" name="tipo" id="tipo_doc">
									<?php $tipos = ['Panoramica', 'Informe médico', 'Informe medicacion', 'Consentimientos de reconstruccion', 'Consentimiento limpieza', 'DNI', 'Datos bancarios', 'Nomina', 'Pasaporte de implantes', 'Presupuesto antiguo'];
									foreach ($tipos as $key => $value) { ?>
										<option value="<?= $value ?>"><?= $value ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-md-12">
								<label for="" class="form-label">Observaciones</label>
								<p>Escribir y confirmar que no faltan o sobran servicios, que los precios y descuentos están ok, apuntar qué servicios están realizados y cuales no, cuánto se ha pagado del presupuesto y cuánto falta.</p>
								<textarea name="observaciones" placeholder="Notas sobre el presupuesto" class="form-control form-control-solid mb-3" rows="5"></textarea>
							</div>
							<div class="col-md-12">
								<button type="submit" class="btn btn-icon btn-warning text-inverse-warning" data-bs-toggle="tooltip" title="Cargar nuevo documento"><i class="fas fa-file-upload"></i></button>
							</div>
							<?php if ($this->session->userdata('errorform') != '') { ?>
								<div class="col-12">
									<div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mt-10">
										<div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center text-uppercase"><?= $this->session->userdata('errorform') ?></div>
										<button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
											<i class="fa-times fas fs-3 text-primary"></i>
										</button>
									</div>
								</div>
							<?php $this->session->unset_userdata('errorform');
							} ?>
						</div>
						<input type="hidden" name="id_presupuesto" value="<?= $registro[0]['id_presupuesto'] ?>">
						<input type="hidden" name="redirectto" value="<?= base_url() ?>Presupuestos">
					</div>
				</div>
			</form>



			<div class="table-responsive">
				<table class="datatable table align-middle table-striped table-row-dashed fs-6 gy-5">
					<thead class="">
						<tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
							<th style="display: none;">ID</th>
							<th>Fecha</th>
							<th>Documento</th>
							<th>Tipo</th>
							<th>Creación</th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody class="text-gray-700 fw-semibold">
						<?php
						if (isset($documentos)) {
							if ($documentos != 0) {
								foreach ($documentos as $key => $row) {
									$datos = array(
										'id_documento' => $row['id_documento'],
										'fecha_estudio' => $row['fecha_estudio'],
										'tipo' => $row['tipo'],
										'observaciones' => $row['observaciones']
									);
									$jsonDatos = json_encode($datos); ?>
									<tr data-json="<?php echo htmlentities($jsonDatos); ?>">
										<td style="display: none;"><?php echo $row['id_documento']; ?></td>
										<td><?php echo $row['fecha_estudio']; ?></td>
										<td><a href="<?php echo base_url(); ?>recursos/clientes_docs/<?= $id_cliente ?>/<?php echo $row['documento']; ?>" target="_blank" rel="noopener noreferrer"><?php echo $row['documento']; ?></a></td>
										<td><?php echo $row['tipo']; ?></td>
										<td><?php echo $row['fecha_creacion']; ?></td>
										<td>
											<?php if ($row['observaciones'] != '') { ?>
												<button type="button" class="btn btn-sm btn-icon btn-secondary" data-bs-toggle="collapse" title="Ver observaciones documento" href="#observaciones_<?= $row['id_documento'] ?>" aria-expanded="false"><i class="fas fa-text-height"></i></button>
											<?php } ?>
											<button type="button" class="btn btn-sm btn-icon btn-warning" editarRowDocumento data-bs-toggle="tooltip" title="Editar documento"><i class="fa-regular fa-pen-to-square"></i></button>
										</td>
										<td>
											<button class="btn btn-sm btn-icon btn-danger" borrarDocumento data-bs-toggle="tooltip" title="Borrar documento"><i class="fa-solid fa-trash"></i></button>
										</td>
									</tr>
									<?php if ($row['observaciones'] != '') { ?>
										<tr class="collapse" id="observaciones_<?= $row['id_documento'] ?>">
											<td colspan="7" class="bg-light-active border border-1 p-3"><?= $row['observaciones'] ?></td>
										</tr>
									<?php } ?>

						<?php }
							} else {
								echo '<tr><td colspan="7" class="text-center">Ningún dato disponible en esta tabla</td></tr>';
							}
						} else {
							echo '<tr><td colspan="7" class="text-center">Ningún dato disponible en esta tabla</td></tr>';
						} ?>
					</tbody>
				</table>
			</div>
		</div>
	<?php } ?>
</div>


<div id="edit_documento_modal" class="modal fade" tabindex="-1" aria-labelledby="edit_documento_modalModalLabel" aria-hidden="true" data-focus-on="input:first">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<form name="form_edit_comision_modal" id="form_edit_comision_modal" action="<?php echo base_url(); ?>clientes/edit_documento" method="post">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">EDITAR Documento</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row mb-5 border-bottom">
						<div class="col-12">
							<label class="form-label">Fecha estudio</label>
							<input type="date" id="edit_fecha_estudio" name="fecha_estudio" value="" class="form-control form-control-solid" placeholder="Hasta" />
						</div>

						<div class="col-12">
							<label class="form-label">Tipo</label>
							<select class="form-select form-select-solid" data-control="select2" name="tipo" id="edit_tipo_doc">
								<?php $tipos = ['Panoramica', 'Informe médico', 'Informe medicacion', 'Consentimientos de reconstruccion', 'Consentimiento limpieza', 'DNI', 'Datos bancarios', 'Nomina', 'Pasaporte de implantes', 'Presupuesto antiguo'];
								foreach ($tipos as $key => $value) { ?>
									<option value="<?= $value ?>"><?= $value ?></option>
								<?php } ?>
							</select>
						</div>

						<div class="col-md-12">
							<label for="" class="form-label">Observaciones</label>
							<textarea id="edit_observaciones_doc" name="observaciones" placeholder="Notas sobre el presupuesto" class="form-control form-control-solid mb-3" rows="6"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary">Actualizar Documento</button>
				</div>
				<input type="hidden" name="id_documento" value="" />
				<input type="hidden" name="id_cliente" value="<?= $id_cliente ?>" />
				<input type="hidden" name="id_presupuesto" value="<?= $registro[0]['id_presupuesto'] ?>">
				<input type="hidden" name="redirectto" value="<?= base_url() ?>Presupuestos">
			</form>
		</div>
		<form name="form_delete_documento_modal" id="delete_documento_modal" action="<?php echo base_url(); ?>clientes/edit_documento" method="post">
			<input type="hidden" id="delete_fecha_estudio" name="fecha_estudio" value="" />
			<input type="hidden" id="delete_tipo_doc" name="tipo" value="" />
			<input type="hidden" id="delete_id_documento" name="id_documento" value="" />
			<input type="hidden" id="delete_borrado" name="borrado" value="1" />
			<input type="hidden" id="delete_id_cliente" name="id_cliente" value="<?= $id_cliente ?>" />
			<input type="hidden" name="id_presupuesto" value="<?= $registro[0]['id_presupuesto'] ?>">
			<input type="hidden" name="redirectto" value="<?= base_url() ?>Presupuestos">
		</form>
	</div>
</div>

<div id="add_cita_modal" class="modal fade" tabindex="-1" aria-labelledby="add_cita_modalModalLabel" aria-hidden="true" data-focus-on="input:first">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<form name="form_add_cita_presupuesto_item_modal" id="form_add_cita_presupuesto_item_modal" action="<?php echo base_url(); ?>Presupuestos/add_cita_presupuesto_item" method="post">
				<div class="modal-header">
					<h5 class="modal-title" id="add_cita_modalModalLabel">Añadir cita</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row mb-5">
						<div class="col-12 mb-3">
							<label class="form-label">Fecha y hora de la cita</label>
							<input type="datetime-local" id="fecha_cita" name="fecha_cita" value="" class="form-control form-control-solid" placeholder="Fecha y hora" />
						</div>

						<div class="col-12 mb-3">
							<label class="form-label">Doctor que la ha realizado</label>
							<select name="id_doctor" id="id_doctor" class="form-select form-select-solid" data-placeholder="Elegir ...">
								<option value="">Selecciona un doctor</option>
								<?php if (isset($doctores)) {
									foreach ($doctores as $d => $doctor) { ?>
										<option value="<?= $doctor['id_usuario'] ?>" <?= ($doctor['id_usuario'] == $registro[0]['id_doctor']) ? 'selected' : '' ?>><?= $doctor['nombre'] . ' ' . $doctor['apellidos']; ?> <?= ($doctor['borrado'] == 1) ? '(B)' : '' ?></option>
									<?php } ?>

								<?php } ?>
							</select>
						</div>
						<div class="col-12">

							<div class="form-check mb-3">
								<input class="form-check-input" type="checkbox" id="finalizar" name="finalizar" value="1">
								<label class="form-check-label" for="finalizar">Marcar la nueva cita como finalizada</label>
							</div>

							<div class="form-check mb-3">
								<input class="form-check-input" type="checkbox" id="pagada" name="pagada" value="1">
								<label class="form-check-label" for="pagada">Marcar la nueva cita como pagada</label>
							</div>

						</div>

					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary">Guardar cita</button>
				</div>
				<input type="hidden" name="id_presupuesto_item" value="">
			</form>
		</div>

	</div>
</div>

<div id="rechazar_cita_modal" class="modal fade" tabindex="-1" aria-labelledby="rechazar_cita_modalModalLabel" aria-hidden="true" data-focus-on="input:first">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<form name="form_add_cita_presupuesto_item_modal" id="form_add_cita_presupuesto_item_modal" action="<?php echo base_url(); ?>Presupuestos/rechazar_cita_presupuesto_item" method="post">
				<div class="modal-header">
					<h5 class="modal-title" id="rechazar_cita_modalModalLabel">Rechazar cita</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row mb-5">
						<div class="col-12 mb-3">
							<label class="form-label">Por favor indique el motivo del rechazo</label>
							<textarea name="motivo" class="form-control"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary">Rechazar cita</button>
				</div>
				<input type="hidden" name="id_presupuesto_item" value="">
			</form>
		</div>

	</div>
</div>
<div id="edit_cita_modal" class="modal fade" tabindex="-1" aria-labelledby="edit_cita_modalModalLabel" aria-hidden="true" data-focus-on="input:first">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<form name="form_edit_cita_presupuesto_item_modal" id="form_edit_cita_presupuesto_item_modal" action="<?php echo base_url(); ?>Presupuestos/edit_cita_presupuesto_item" method="post">
				<div class="modal-header">
					<h5 class="modal-title" id="edit_cita_modalModalLabel">Editar cita</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row mb-5">
						<div class="col-12 mb-3">
							<label class="form-label">Fecha y hora de la cita</label>
							<input type="datetime-local" id="fecha_cita_editar" name="fecha_cita" value="" class="form-control form-control-solid" placeholder="Fecha y hora" />
						</div>

						<div class="col-12 mb-3">
							<label class="form-label">Doctor que la ha realizado</label>
							<select name="id_doctor" id="id_doctor_editar" class="form-select form-select-solid" data-placeholder="Elegir ...">
								<option value="">Selecciona un doctor</option>
								<?php if (isset($doctores)) {
									foreach ($doctores as $d => $doctor) { ?>
										<option value="<?= $doctor['id_usuario'] ?>" <?= ($doctor['id_usuario'] == $registro[0]['id_doctor']) ? 'selected' : '' ?>><?= $doctor['nombre'] . ' ' . $doctor['apellidos']; ?> <?= ($doctor['borrado'] == 1) ? '(B)' : '' ?></option>
									<?php } ?>

								<?php } ?>
							</select>
						</div>
						<?php if ($this->session->userdata('id_perfil') == 0) { ?>
							<div class="form-check mb-3">
								<input class="form-check-input" type="checkbox" id="finalizar" name="finalizar" value="1">
								<label class="form-check-label" for="finalizar">Marcar la cita como FINALIZADA</label>
							</div>

							<div class="form-check mb-3">
								<input class="form-check-input" type="checkbox" id="pagada" name="pagada" value="1">
								<label class="form-check-label" for="pagada">Marcar la cita como PAGADA</label>
							</div>
							<div class="form-check mb-3">
								<input class="form-check-input" type="checkbox" id="novino" name="novino" value="1">
								<label class="form-check-label" for="novino">Marcar la cita como NO VINO</label>
							</div>
						<?php } ?>
						<?php /*
							<div class="col-12">
								<label class="form-label">Estado de la cita</label>
								<select name="estado_cita" id="estado_cita" class="form-select form-select-solid" data-placeholder="Elegir ...">
									<option value=""></option>
									<option value="Anulada">Anulada</option>
									<option value="Programada">Programada</option>
									<option value="Finalizada">Finalizada</option>
									<option value="No vino">No vino</option>
								</select>
							</div>
							<div class="col-12">
								<label class="form-label">Estado del pago</label>
								<select name="estado_dietario" id="estado_dietario" class="form-select form-select-solid" data-placeholder="Elegir ...">
									<option value=""></option>
									<option value="Anulada">Anulada</option>
									<option value="No Pagado">No Pagado</option>
									<option value="No vino">No vino</option>
									<option value="Pagado">Pagado</option>
									<option value="Pendiente">Pendiente</option>
									<option value="Pendiente justificante">Pendiente justificante</option>
									<option value="Presupuesto">Presupuesto</option>
								</select>
							</div>
						<?php */ ?>

					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary">Actualizar cita</button>
				</div>
				<input type="hidden" name="id_presupuesto_item" value="">
			</form>
		</div>

	</div>
</div>

<div class="row mt-5 bg-light-info p-3">
	<div class="col-md-6 p-2">
		<b>Creado por:</b> <?php echo $registro[0]['e_nombre'] . ' ' . $registro[0]['e_apellidos']; ?>
		<br><b>Fecha:</b> <?php echo $registro[0]['fecha_creacion']; ?>
	</div>
	<div class="col-md-6 p-2">
		<b>Modificado por:</b> <?php echo $registro[0]['usu_modif_nombre'] . ' ' . $registro[0]['usu_modif_pellidos']; ?>
		<br><b>Fecha:</b> <?php echo $registro[0]['fecha_modificacion']; ?>
	</div>
</div>

<style>
	.swal2-container.swal2-center.swal2-backdrop-show {
		z-index: 99999;
	}

	.modal .modal {
		background: rgb(0 0 0 / 40%);
	}
</style>

<script>
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
		return new bootstrap.Tooltip(tooltipTriggerEl)
	})


	$(document).on('click', '[data-add-justificante]', function() {
		var id_dietario = $(this).attr('data-add-justificante');
		var id_presupuesto = $(this).attr('data-presupuesto-justificante');
		const modalContent = `
                <div class="modal fade" id="modalFormularioPago" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index:99999;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Justificante de operación de pago</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="justificante-pago">
									<div class="mb-3">
										<label for="comisionfinanciacion" class="form-label">Gastos de transacción</label>
										<input type="number" class="form-control" id="comisionfinanciacion" name="comisionfinanciacion" required>
										<span class="text-muted" id="comisionfinanciacion"></span>
									</div>
									<div class="mb-3">
										<label for="formFile" class="form-label">Justificante de pago</label>
										<input class="form-control" type="file" id="fileToUpload" name="fileToUpload">
										<span class="text-muted" id="comisionfinanciacion"></span>
									</div>
                                    <button type="button" class="btn btn-primary" id="enviar_justificante">Enviar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;
		$("body").append(modalContent);
		$("#modalFormularioPago").modal('show');
		$('.modal-backdrop.fade.show').attr('style', 'z-index:9999;')
		$("#enviar_justificante").click(function(event) {
			console.log(id_dietario, id_presupuesto);
			if ($('#comisionfinanciacion').val() <= 0) {
				Swal.fire({
					title: 'Gastos de operación de pago',
					html: `¿Seguro que la operación no tiene gastos asociados? El campo gastos esta vacío y se guardará con valor 0`,
					showCancelButton: true,
					confirmButtonText: 'Si, enviar sin gastos',
					showLoaderOnConfirm: true,
					onBeforeOpen: () => {
						$(".swal2-file").change(function() {
							var reader = new FileReader();
							reader.readAsDataURL(this.files[0]);
						});
					},

				}).then((result) => {
					if (result.value) {
						enviarjustificante(id_dietario, id_presupuesto)
					}
				})
			} else {
				enviarjustificante(id_dietario, id_presupuesto)
			}
		});

		function enviarjustificante(id_dietario, id_presupuesto) {
			console.log(id_dietario, id_presupuesto);
			var formData = new FormData(document.getElementById('justificante-pago'));
			formData.append("id_dietario", id_dietario);
			formData.append("id_presupuesto", id_presupuesto);
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				method: 'post',
				url: '<?php echo base_url() ?>Presupuestos/cargarJustificante',
				data: formData,
				processData: false,
				contentType: false,
				success: function(resp) {
					console.log(resp);
					if (resp == true) {
						Swal.fire({
							title: 'Cargado',
							html: 'El archivo ha sido cargado',
							type: 'success',
							willClose: function() {
								$("#modalFormularioPago").modal('hide');
								window.location.reload();
							},
						});
					} else {
						Swal.fire({
							title: 'Error',
							type: 'error',
							willClose: function() {
								//window.location.reload();
							},
						});
					}

				},
				error: function() {
					Swal.fire({
						type: 'error',
						title: 'Oops...',
						text: 'Ha ocurrido un error'
					})
				}
			})
		}

		$("#modalFormularioPago").on("hidden.bs.modal", function() {
			$('.modal-backdrop.fade.show').removeAttr('style')
			$(this).remove();
		});
	})

	$(document).on('click', '[data-ver-justificante]', function() {
		var nuevaPestana = window.open('<?php echo base_url(); ?>' + $(this).attr('data-ver-justificante'), '_blank');
		nuevaPestana.focus();
	})

	$('button[editarRowDocumento]').on('click', function() {
		var tr = $(this).closest('tr');
		var json = tr.data('json');
		console.log(json)
		var modal = $('#edit_documento_modal')
		console.log(modal);
		$('#edit_fecha_estudio').val(json.fecha_estudio);
		$('#edit_tipo_doc').val(json.tipo);
		$('#edit_observaciones_doc').val(json.observaciones);
		$('#edit_tipo_doc').select2();
		modal.find('[name="id_documento"]').val(json.id_documento);
		modal.modal('show')
	});

	$('button[borrarDocumento]').on('click', function() {
		if (confirm("¿Desea borrar este DOCUMENTO?")) {
			var tr = $(this).closest('tr');
			var json = tr.data('json');
			$('#delete_fecha_estudio').val(json.fecha_estudio);
			$('#delete_tipo_doc').val(json.tipo);
			$('#delete_id_documento').val(json.id_documento);
			var form = document.getElementById('delete_documento_modal');
			form.submit();
		}
		return false;
	});

	$('[data-item]').on('click', function() {
		var id_presupuesto_item = $(this).attr('data-item');
		var gastos_lab = $(this).closest(".input-group").find("input").val();
		Swal.fire({
			title: 'Gasto de laboratorio',
			html: `Guardar el gasto de laboratorio del servicio?`,
			showCancelButton: true,
			confirmButtonText: 'Si, guardar',
			showLoaderOnConfirm: true
		}).then((result) => {
			if (result.value) {
				var formData = new FormData();
				formData.append("id_presupuesto_item", id_presupuesto_item);
				formData.append("gastos_lab", gastos_lab);
				$.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					method: 'post',
					url: '<?php echo base_url() ?>Presupuestos/updateGastoLaboratorio',
					data: formData,
					processData: false,
					contentType: false,
					success: function(resp) {
						if (resp == false) {
							Swal.fire({
								title: 'Error',
								type: 'error',
								willClose: function() {},
							});
						} else {
							Swal.fire({
								title: 'Guardado',
								type: 'success',
								willClose: function() {},
							});
						}
					},
					error: function() {
						Swal.fire({
							type: 'error',
							title: 'Oops...',
							text: 'Ha ocurrido un error'
						})
					}
				})
			}
		})
	})

	$(document).on('click', '[data-add-cita]', function() {
		var id_presupuesto_item = $(this).attr('data-add-cita');
		var modal = $('#add_cita_modal')
		$('#fecha_cita').val('');
		$('#id_doctor').val('');
		$('#id_doctor').select2({
			dropdownParent: $('#add_cita_modal')
		});
		modal.find('[name="id_presupuesto_item"]').val(id_presupuesto_item);
		modal.modal('show')
	});
	$(document).on('click', '[data-rechazar-cita]', function() {
		var id_presupuesto_item = $(this).attr('data-rechazar-cita');
		var modal = $('#rechazar_cita_modal')
		$('#fecha_cita').val('');
		$('#id_doctor').val('');
		$('#id_doctor').select2({
			dropdownParent: $('#rechazar_cita_modal')
		});
		modal.find('[name="id_presupuesto_item"]').val(id_presupuesto_item);
		modal.modal('show')
	});
	document.getElementById('form_add_cita_presupuesto_item_modal').addEventListener('submit', function(e) {
		e.preventDefault();
		const formData = new FormData(this);
		fetch('<?= base_url() ?>Presupuestos/add_cita_presupuesto_item', {
				method: 'POST',
				headers: {
					'X-Requested-With': 'XMLHttpRequest'
				},
				body: formData
			})
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					if (data.error) {
						Swal.fire({
							icon: false,
							title: data.msn,
							allowOutsideClick: false,
							showCancelButton: false,
							confirmButtonText: 'Cerrar',
							willClose: function() {}
						});
						return;
					}
					Swal.fire({
						icon: 'success',
						title: data.msn,
						allowOutsideClick: false,
						showCancelButton: false,
						confirmButtonText: 'Continuar',
						willClose: function() {
							var id_presupuesto_item = data.id_presupuesto_item;
							if (data.hasOwnProperty('finalizada') && data.finalizada === true) {
								var newicon = '<i class="fa-check-circle text-success fs-3 border border-success"></i><span class="fecha_hora">' + data.fecha_hora_inicio + ' - ' + data.empleado + '</span>';
							} else {
								var newicon = '<i class="fas fa-calendar-day text-warning rounded-circle fs-3 border border-wharning p-1" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Editar cita" data-edit-cita="' + id_presupuesto_item + '" data-fecha-cita="' + data.fecha_hora_inicio + '" data-id-doctor="' + data.id_usuario_empleado + '" data-estado-cita="' + data.estado_cita + '" data-estado-dietario="' + data.estado_dietario + '"></i><span class="fecha_hora">' + data.fecha_hora_inicio + ' - ' + data.empleado + '</span>';
							}
							$('[data-add-cita="' + id_presupuesto_item + '"]').before(newicon);
							$('[data-add-cita="' + id_presupuesto_item + '"]').nextUntil('br').addBack().remove();
							$("#add_cita_modal").modal('hide');
						}
					});
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: data.msn
					});
				}
			})
			.catch(error => {
				console.error('Error:', error);
				Swal.fire({
					icon: 'error',
					title: 'error',
					text: error
				});
			});
	});

	$(document).on('click', '[data-edit-cita]', function() {
		var id_presupuesto_item = $(this).attr('data-edit-cita');
		var fecha = $(this).attr('data-fecha-cita');
		var id_doctor = $(this).attr('data-id-doctor');
		var estado_cita = $(this).attr('data-estado-cita');
		var estado_dietario = $(this).attr('data-estado-dietario');

		var modal = $('#edit_cita_modal')
		modal.find('[name="fecha_cita"]').val(fecha);
		modal.find('[name="id_doctor"]').val(id_doctor);
		modal.find('[name="id_doctor"]').select2({
			dropdownParent: $('#edit_cita_modal')
		});
		/*modal.find('[name="estado_cita"]').val(estado_cita);
		modal.find('[name="estado_dietario"]').val(estado_dietario);*/
		modal.find('[name="id_presupuesto_item"]').val(id_presupuesto_item);
		modal.modal('show')
	});

	document.getElementById('form_edit_cita_presupuesto_item_modal').addEventListener('submit', function(e) {
		e.preventDefault();
		const formData = new FormData(this);
		fetch('<?= base_url() ?>Presupuestos/edit_cita_presupuesto_item', {
				method: 'POST',
				headers: {
					'X-Requested-With': 'XMLHttpRequest'
				},
				body: formData
			})
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					if (data.error) {
						Swal.fire({
							icon: false,
							title: data.msn,
							allowOutsideClick: false,
							showCancelButton: false,
							confirmButtonText: 'Cerrar',
							willClose: function() {}
						});
						return;
					}
					Swal.fire({
						icon: 'success',
						title: data.msn,
						allowOutsideClick: false,
						showCancelButton: false,
						confirmButtonText: 'Continuar',
						willClose: function() {
							var id_presupuesto_item = data.id_presupuesto_item;
							$('[data-edit-cita="' + id_presupuesto_item + '"]').nextAll('.fecha_hora').first().text(data.fecha_hora_inicio + ' - ' + data.empleado)
							if (data.estado_cita == 'No vino') {
								var newicon = '<i class="fas fa-calendar-plus text-primary rounded-circle fs-3 border border-primary p-1" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Añadir cita" data-add-cita="' + id_presupuesto_item + '"></i> <span>Añadir una cita para este servicio</span>';
								$('[data-edit-cita="' + id_presupuesto_item + '"]').before(newicon);
								$('[data-edit-cita="' + id_presupuesto_item + '"]').nextUntil('br').addBack().remove();
							}
							$("#edit_cita_modal").modal('hide');
						}
					});
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: data.msn
					});
				}
			})
			.catch(error => {
				console.error('Error:', error);
				Swal.fire({
					icon: 'error',
					title: 'error',
					text: error
				});
			});
	});

	$('[data-borrar-pago]').on('click', function() {
		var tr = $(this).closest('tr');
		var id_dietario = $(this).attr('data-borrar-pago');
		Swal.fire({
			title: 'Eliminar pago',
			html: `¿Eliminar el pago de presupuesto?`,
			showCancelButton: true,
			confirmButtonText: 'Si, eliminar',
			showLoaderOnConfirm: true
		}).then((result) => {
			if (result.value) {
				var formData = new FormData();
				formData.append("id_dietario", id_dietario);
				$.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					method: 'post',
					url: '<?php echo base_url() ?>Presupuestos/borrarPagoPresupuesto',
					data: formData,
					processData: false,
					contentType: false,
					success: function(resp) {
						if (resp == false) {
							Swal.fire({
								title: 'Error',
								type: 'error',
								willClose: function() {},
							});
						} else {
							Swal.fire({
								title: 'Borrado',
								type: 'success',
								willClose: function() {
									tr.remove();
								},
							});
						}
					},
					error: function() {
						Swal.fire({
							type: 'error',
							title: 'Oops...',
							text: 'Ha ocurrido un error'
						})
					}
				})
			}
		})
	})


    jQuery(".btn-generarliquidacion").on('click',function(){
       var id=jQuery(this).data("itemc");
       var $this=jQuery(this);
       // crear_liquidacion_cita_master
        Swal.fire({
            title: 'Generar liquidacion',
            html: `Genear la liquidación del item seleccionado?`,
            showCancelButton: true,
            confirmButtonText: 'Si, generar',
            showLoaderOnConfirm: true
        }).then((result) => {
            if (result.value) {

                var formData = new FormData();
                formData.append("id_cita", id);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'post',
                    url: '<?php echo base_url() ?>Liquidaciones/crear_liquidacion_cita_master/'+id,
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(resp) {
                        if(resp.success){
                            $this.html('<i class="bi bi-check2-square"></i>');
                            Swal.fire({
                                title: 'Guardado',
                                type: 'success',
                                willClose: function() {},
                            });
                        }
                        else {
                            Swal.fire({
                                title: 'Error',
                                type: 'error',
                                willClose: function() {},
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Ha ocurrido un error'
                        })
                    }
                })

            }
        })
    });
</script>