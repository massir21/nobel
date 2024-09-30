<div class="row g-5 g-xl-8">

	<?php if (count($primeras_visitas_hoy) > 0) { ?>

	<div class="col-sm-6 col-lg-4">
		<div class="border border-secondary card mb-5 shadow">
			<div class="align-items-start border-0 card-header flex-nowrap p-5">
				<h3 class="card-title fs-1 align-items-start flex-column">
					<span class="card-label fw-bold text-gray-900">Primeras visitas de hoy</span>
					<span class="text-muted fw-semibold fs-7">Hoy hay <?= count($primeras_visitas_hoy) ?> citas de primera visita
				</h3>
				<div class="card-toolbar">
					<span class="border border-1 border-secondary fs-2 fw-bold px-3 py-2 rounded"><?= count($primeras_visitas_hoy) ?></span>
				</div>
			</div>
			<div class="card-body p-5" style="height: 300px;overflow-y: auto;">
				<div class="timeline-label">
					<?php

						foreach ($primeras_visitas_hoy as $ci => $cita) { ?>

							<div class="timeline-item" data-conf="<?php echo htmlspecialchars(json_encode($cita), ENT_QUOTES, 'UTF-8'); ?>">
								<div class="timeline-label fw-bold text-gray-800 fs-6"><?= date('H:i', strtotime($cita->fecha_hora_inicio)) ?></div>
								<div class="timeline-badge">
									<i class="fa fa-genderless text-warning fs-1"></i>
								</div>
								<div class="fw-mormal timeline-content d-flex flex-column ps-3">
									<span class="text-gray-800 text-hover-primary fs-6 fw-bold"><?= $cita->cliente ?></span>
									<span class="text-muted fw-bold"><?= $cita->empleado ?></span>
									<?php if ($cita->estado == 'Programada') { ?>
										<?php if ($cita->confirma == '1') { ?>
											<span class="fs-8 fw-bold">Confirmada por: <?= $cita->usuario_confirma ?> a las <?= date('H:i', strtotime($cita->fecha_conf)) ?></span>
										<?php } ?>
									<?php } ?>
								</div>
								<?php if ($cita->estado == 'Programada') { ?>
									<div class="btn-group">
										<button type="button" class="btn btn-icon btn-outline btn-outline-primary btn-sm" data-confirmar data-bs-toggle="tooltip" title="Confirmar cita"><i class="fa fa-thumbs-up"></i></button>

									</div>
								<?php } ?>

								<div class="w-25 text-end">

									<?php if ($cita->estado == 'Finalizada') { ?>
										<span class="badge badge-light-success fs-8 fw-bold">Finalizada</span>
									<?php } ?>
									<?php if ($cita->estado == 'Programada') { ?>
										<span class="badge badge-light-info fs-8 fw-bold">Programada</span>
										<?php if ($cita->confirma == '1') { ?>
											<span class="badge badge-light-success fs-8 fw-bold">Confirmada</span>

										<?php } ?>
									<?php } ?>
									<?php if ($cita->estado == 'No vino') { ?>
										<span class="badge badge-light-danger fs-8 fw-bold">No vino</span>
									<?php } ?>

								</div>
							</div>
						<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>

		<?php if (count($primeras_visitas_manana) > 0) { ?>
		<div class="col-sm-6 col-lg-4"> 
		<div class="border border-secondary card mb-5 shadow">
			<div class="align-items-start border-0 card-header flex-nowrap p-5">
				<h3 class="card-title fs-1 align-items-start flex-column">
					<span class="card-label fw-bold text-gray-900">Primeras visitas de mañana</span>
					<span class="text-muted fw-semibold fs-7">Mañana hay <?= count($primeras_visitas_manana) ?> citas de primera visita</span>
				</h3>
				<div class="card-toolbar">
					<span class="border border-1 border-secondary fs-2 fw-bold px-3 py-2 rounded"><?= count($primeras_visitas_manana) ?></span>
				</div>
			</div>
			<div class="card-body p-5" style="height: 300px;overflow-y: auto;">
				<div class="timeline-label">
					<?php

						foreach ($primeras_visitas_manana as $ci => $cita) { ?>

							<div class="timeline-item" data-conf="<?php echo htmlspecialchars(json_encode($cita), ENT_QUOTES, 'UTF-8'); ?>">
								<div class="timeline-label fw-bold text-gray-800 fs-6"><?= date('H:i', strtotime($cita->fecha_hora_inicio)) ?></div>
								<div class="timeline-badge">
									<i class="fa fa-genderless text-warning fs-1"></i>
								</div>
								<div class="fw-mormal timeline-content d-flex flex-column ps-3">
									<span class="text-gray-800 text-hover-primary fs-6 fw-bold"><?= $cita->cliente ?></span>
									<span class="text-muted fw-bold"><?= $cita->empleado ?></span>
									<?php if ($cita->estado == 'Programada') { ?>
										<?php if ($cita->confirma == '1') { ?>
											<span class="fs-8 fw-bold">Confirmada por: <?= $cita->usuario_confirma ?> a las <?= date('H:i', strtotime($cita->fecha_conf)) ?></span>
										<?php } ?>
									<?php } ?>
								</div>
								<?php if ($cita->estado == 'Programada') { ?>
									<div class="btn-group">
										<button type="button" class="btn btn-icon btn-outline btn-outline-primary btn-sm" data-confirmar data-bs-toggle="tooltip" title="Confirmar cita"><i class="fa fa-thumbs-up"></i></button>

									</div>
								<?php } ?>

								<div class="w-25 text-end">

									<?php if ($cita->estado == 'Finalizada') { ?>
										<span class="badge badge-light-success fs-8 fw-bold">Finalizada</span>
									<?php } ?>
									<?php if ($cita->estado == 'Programada') { ?>
										<span class="badge badge-light-info fs-8 fw-bold">Programada</span>
										<?php if ($cita->confirma == '1') { ?>
											<span class="badge badge-light-success fs-8 fw-bold">Confirmada</span>

										<?php } ?>
									<?php } ?>
									<?php if ($cita->estado == 'No vino') { ?>
										<span class="badge badge-light-danger fs-8 fw-bold">No vino</span>
									<?php } ?>

								</div>
							</div>
						<?php } ?>
				</div>
			</div>
		</div>
		</div>
		<?php } ?>
		
		<?php if (count($presupuestosServiciosPendientes)) { ?>
		<div class="col-sm-6 col-lg-4">
			<div class="border border-secondary card mb-5 shadow">
				
				<div class="align-items-start border-0 card-header flex-nowrap p-5">
					<h3 class="card-title fs-1 align-items-start flex-column">
						<span class="card-label fw-bold text-gray-900">Pacientes con servicios pendientes</span>
						<span class="text-muted fw-semibold fs-7">Servicios pendientes en su presupuesto con más de dos meses desde la última cita</span>
					</h3>
					<div class="card-toolbar">
						<span class="border border-1 border-secondary fs-2 fw-bold px-3 py-2 rounded"><?= count($presupuestosServiciosPendientes) ?></span>
					</div>
				</div>
				<div class="card-body p-5" style="height: 300px; overflow-y:auto;">
					
						<?php 
						foreach ($presupuestosServiciosPendientes as $i => $cita_pendiente) {
								
								$class_notas = 'btn-outline-warning';
								if ( $cita_pendiente['notas'] == 'pendiente' ) $class_notas = 'btn-outline-success';
								if ( $cita_pendiente['notas'] == 'archivado' ) $class_notas = 'btn-outline-danger';
								
								if ( $this->session->userdata('id_perfil') == 0 || $cita_pendiente['notas'] != 'archivado' ){
									
									$fecha_dada = new DateTime($cita_pendiente['fecha_ultima_cita']);
									$fecha_actual = new DateTime();
									$diferencia = $fecha_dada->diff($fecha_actual);
								?>
								<div class="d-flex pb-6">
									<div class="flex-grow-1">
										<i class="fa fa-user text-dark-emphasis"></i> <strong style="cursor:pointer" onclick="FichaCliente('<?= $cita_pendiente['id_cliente'] ?>');"><?php echo $cita_pendiente['cliente']; ?></strong><br/>
										<i class="fa fa-clock" title="Fecha última cita"></i> Última cita:  <?php echo $cita_pendiente['fecha_ultima_cita']; ?><br/>
										<span class="alert-danger">Hace <?php echo $diferencia->days ?> días.</span>
									</div>
									<div class="w-10 text-end">
										<button type="button" class="btn btn-sm btn-icon btn-outline <?php echo $class_notas; ?> notaspresu" data-bs-toggle="tooltip" aria-label="Añadir notas" data-bs-original-title="Añadir notas" data-idpresupuesto="<?php echo $cita_pendiente['id_presu'] ?>" data-numpresupuesto="<?php echo $cita_pendiente['nro_presupuesto'] ?>"><i class="fa-regular fa-pen-to-square"></i></button>
										<button type="button" class="btn btn-icon btn-outline btn-outline-primary btn-sm viewpresu" data-bs-toggle="tooltip" aria-label="Detalle del presupuesto" data-bs-original-title="Detalle del presupuesto" data-idpresupuesto="<?php echo $cita_pendiente['id_presu'] ?>" data-numpresupuesto="<?php echo $cita_pendiente['nro_presupuesto'] ?>"><i class="fa fa-eye"></i></button>
									</div>
									
								</div>
								<?php
								}
						} ?>
					
				</div>
			</div>
		</div>
		<?php } ?>
		
		<?php if ($this->session->userdata('id_perfil') == 0 && count($primeras_visitas_mes) > 0) { ?>
		<div class="col-sm-6 col-lg-4">	
			<div class="border border-secondary card mb-5 shadow">
				<div class="align-items-start border-0 card-header flex-nowrap p-5">
					<h3 class="card-title fs-1 align-items-start flex-column">
						<span class="card-label fw-bold text-gray-900">Primeras visitas del mes</span>
						<span class="text-muted fw-semibold fs-7">Este mes hay <?= count($primeras_visitas_mes) ?> citas de primera visita</span>
					</h3>
					<div class="card-toolbar">
						<span class="border border-1 border-secondary fs-2 fw-bold px-3 py-2 rounded"><?= count($primeras_visitas_mes) ?></span>
					</div>
				</div>
				<div class="card-body p-5" style="height: 300px;overflow-y: auto;">
					<div class="timeline-label">
						<?php 
							foreach ($primeras_visitas_mes as $ci => $cita) { ?>

								<div class="timeline-item">
									<div class="timeline-label fw-bold text-gray-800 fs-6">Día <?= date('d H:i', strtotime($cita->fecha_hora_inicio)) ?></div>
									<div class="timeline-badge">
										<i class="fa fa-genderless text-warning fs-1"></i>
									</div>
									<div class="fw-mormal timeline-content d-flex flex-column ps-3">
										<span class="text-gray-800 text-hover-primary fs-6 fw-bold"><?= $cita->cliente ?></span>
										<span class="text-muted fw-bold"><?= $cita->empleado ?></span>
									</div>
									<div class="w-25 text-end">
										<?php if ($cita->estado == 'Finalizada') { ?>
											<span class="badge badge-light-success fs-8 fw-bold">Finalizada</span>
										<?php } ?>
										<?php if ($cita->estado == 'Programada') { ?>
											<span class="badge badge-light-info fs-8 fw-bold">Programada</span>
										<?php } ?>
										<?php if ($cita->estado == 'No vino') { ?>
											<span class="badge badge-light-danger fs-8 fw-bold">No vino</span>
										<?php } ?>
									</div>
								</div>
							<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
	
	<?php if (count($presupuestosPendientes)) { ?>
	<div class="col-sm-6 col-lg-4">
		<div class="border border-secondary card mb-5 shadow">
			
			<div class="align-items-start border-0 card-header flex-nowrap p-5">
				<h3 class="card-title fs-1 align-items-start flex-column">
					<span class="card-label fw-bold text-gray-900">Presupuestos pendientes</span>
				</h3>
				<div class="card-toolbar">
					<span class="border border-1 border-secondary fs-2 fw-bold px-3 py-2 rounded"><?= count($presupuestosPendientes) ?></span>
				</div>
			</div>
			<div class="card-body p-5" style="height: 300px; overflow-y:auto;">
				
					<?php 
					foreach ($presupuestosPendientes as $i => $presupuestoPendientes) {
						?>
						<div class="d-flex pb-6">
							<div class="flex-grow-1">
								<span title="Paciente"><i class="fa fa-user text-dark-emphasis"></i> <?php echo $presupuestoPendientes['nombre'].' '.$presupuestoPendientes['apellidos']; ?></span><br/>
								<span title="Total"><i class="fa fa-euro"></i> <?php echo euros($presupuestoPendientes['totalpresupuesto']); ?></span> - 
								<span title="Total"><i class="fa fa-phone"></i> <?php echo $presupuestoPendientes['telefono']; ?></span> <br/>
								<span title="Fecha validez"><i class="fa fa-calendar"></i> <?php echo fechaES($presupuestoPendientes['fecha_validez']); ?></span>
							</div>
							<div class="w-10 text-end">
								<?php if ( $_SESSION['id_perfil'] == 0 || $_SESSION['id_usuario'] == $presupuestoPendientes['id_usuario'] ) { ?>
								<button type="button" class="btn btn-sm btn-icon btn-primary estado-presu-pendiente" data-idpresupuesto="<?php echo $presupuestoPendientes['id_presupuesto'] ?>" data-bs-toggle="tooltip" title="Gestionar estado"><i class="fas fa-exclamation-triangle"></i></button>
								<?php } ?>
								<button type="button" class="btn btn-sm btn-icon btn-info pdf-presu-pendiente" data-idpresupuesto="<?php echo $presupuestoPendientes['id_presupuesto'] ?>" data-bs-toggle="tooltip" title="Ver presupuesto"><i class="fas fa-file-pdf"></i></button>
							</div>
						</div>
						<?php
					} 
					?>
				
			</div>
		</div>
	</div>
	<script>
		$(document).ready( function(){
			$('.pdf-presu-pendiente').on('click', function() {
				var url = '<?= base_url() ?>presupuestos/ver_pdf/' + $(this).data('idpresupuesto');
				var posicion_x;
				var posicion_y;
				var ancho = 800;
				var alto = 550;
				posicion_x = (screen.width / 2) - (ancho / 2);
				posicion_y = (screen.height / 2) - (alto / 2);
				window.open(url, "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
			});
			
			$('.estado-presu-pendiente').on('click', function() {
				window.location.href ='<?= base_url() ?>presupuestos/gestionar_estado/' + $(this).data('idpresupuesto');
			});
		});
	</script>
	<?php } ?>
	
	<?php if (count($tareas_diarias_asignadas) > 0) { ?>
	<div class="col-sm-6 col-lg-4">
		<div class="border border-secondary card mb-5 shadow">
			<div class="align-items-start border-0 card-header flex-nowrap p-5">
				<h3 class="card-title fs-1 align-items-start flex-column">
					<span class="card-label fw-bold text-gray-900">Tareas Diarias Asignadas</span>
					<span class="text-muted fw-semibold fs-7">Tareas pendientes asignadas hasta hoy</span>
				</h3>
				<div class="card-toolbar">
					<span class="border border-1 border-secondary fs-2 fw-bold px-3 py-2 rounded"><?= count($tareas_diarias_asignadas) ?></span>
				</div>
			</div>
			<div class="card-body p-5" style="height: 300px;overflow-y: auto;">
				<div class="timeline-label">
					<?php 
						foreach ($tareas_diarias_asignadas as $ta => $tarea) { ?>
							<div class="timeline-item" data-tarea="<?php echo htmlspecialchars(json_encode($tarea), ENT_QUOTES, 'UTF-8'); ?>">
								<div class="timeline-label fw-bold text-gray-800 fs-6">
									<?= date('Y-m-d', strtotime($tarea->fecha_ejecucion)) ?>
								</div>
								<div class="timeline-badge">
									<i class="fa fa-genderless text-warning fs-1"></i>
								</div>
								<div class="fw-mormal timeline-content d-flex flex-column ps-3" style="min-width: 65%; width: 65%;">
									<span class="text-gray-800 text-hover-primary fs-6 fw-bold"><?= $tarea->titulo ?></span>
									<span class="text-muted fw-bold"><?= $tarea->contenido ?></span>
								</div>
								<div class="w-25 text-end">
									<div class="btn-group">
										<button type="button" class="btn btn-icon btn-outline btn-outline-primary btn-sm" data-ver data-bs-toggle="tooltip" title="Ver registro de actividad" onclick="ver_registro(<?= $tarea->id ?>)"><i class="fa fa-eye"></i></button>
									</div>
								</div>
							</div>
						<?php } ?>
						
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
		
	<?php if (count($tareas_diarias_propias) > 0) { ?>	
	<div class="col-sm-6 col-lg-4">
		<div class="border border-secondary card mb-5 shadow">
			<div class="align-items-start border-0 card-header flex-nowrap p-5">
				<h3 class="card-title fs-1 align-items-start flex-column">
					<span class="card-label fw-bold text-gray-900">Tareas Diarias Creadas</span>
					<span class="text-muted fw-semibold fs-7">Tareas pendientes que has creado hasta hoy</span>
				</h3>
				<div class="card-toolbar">
					<span class="border border-1 border-secondary fs-2 fw-bold px-3 py-2 rounded"><?= count($tareas_diarias_propias) ?></span>
				</div>
			</div>
			<div class="card-body p-5" style="height: 300px;overflow-y: auto;">
				<div class="timeline-label">
					<?php 
						foreach ($tareas_diarias_propias as $ta => $tarea) { ?>
							<div class="timeline-item" data-tarea="<?php echo htmlspecialchars(json_encode($tarea), ENT_QUOTES, 'UTF-8'); ?>">
								<div class="timeline-label fw-bold text-gray-800 fs-6">
									<?= date('Y-m-d', strtotime($tarea->fecha_ejecucion)) ?>
								</div>
								<div class="timeline-badge">
									<i class="fa fa-genderless text-warning fs-1"></i>
								</div>
								<div class="fw-mormal timeline-content d-flex flex-column ps-3" style="min-width: 65%; width: 65%;">
									<span class="text-gray-800 text-hover-primary fs-6 fw-bold"><?= $tarea->titulo ?></span>
									<span class="text-muted fw-bold"><?= $tarea->contenido ?></span>
								</div>
								<div class="w-25 text-end">
									<div class="btn-group">
										<button type="button" class="btn btn-icon btn-outline btn-outline-primary btn-sm" data-ver data-bs-toggle="tooltip" title="Ver registro de actividad" onclick="ver_registro(<?= $tarea->id ?>)"><i class="fa fa-eye"></i></button>
									</div>
								</div>
							</div>
						<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	
	<?php if ($this->session->userdata('id_perfil') != 6 && count($rellamadas_diarias_pendientes) > 0 ) { ?>
		<div class="col-sm-6 col-lg-4">
			<div class="border border-secondary card mb-5 shadow">
				<div class="align-items-start border-0 card-header flex-nowrap p-5">
					<h3 class="card-title fs-1 align-items-start flex-column">
						<span class="fw-bold mb-2 text-gray-900">Rellamadas</span>
						<span class="text-muted fw-semibold fs-7">Rellamadas pendientes hasta hoy</span>
					</h3>
					<div class="card-toolbar">
						<span class="border border-1 border-secondary fs-2 fw-bold px-3 py-2 rounded"><?= count($rellamadas_diarias_pendientes) ?></span>
					</div>
				</div>
				<div class="card-body p-5" style="height: 300px; overflow-y:auto;">
					<?php
					usort($rellamadas_diarias_pendientes, 'compararFechas');
					$fecha_actual = date('Y-m-d');
					?>
					<div class="timeline-label">
						<?php
							foreach ($rellamadas_diarias_pendientes as $rellamada) {
								$es_pendiente_pasada = (strtotime($rellamada->fecha_rellamada) < strtotime($fecha_actual));
								$clase_destacada = $es_pendiente_pasada ? 'rellamada-destacada' : 'rellamada-normal';
						?>
								<div class="timeline-item <?= $clase_destacada ?>" data-rellamada="<?php echo htmlspecialchars(json_encode($rellamada), ENT_QUOTES, 'UTF-8'); ?>">
									<div class="timeline-label fw-bold text-gray-800 fs-6"><?= $rellamada->fecha_cita ?></div>
									<div class="timeline-badge">
										<i class="fa fa-genderless <?= $es_pendiente_pasada ? 'icono-destacado' : 'icono-normal' ?> fs-1"></i>
									</div>
									<div class="fw-normal timeline-content d-flex flex-column ps-3">
										<span class="text-gray-800 text-hover-primary fs-6 fw-bold"><?= $rellamada->cliente ?></span>
										<a href="tel:<?= $rellamada->telefono ?>"><?= $rellamada->telefono ?></a>
										<span class="text-muted fw-bold"><?= $rellamada->nombre_servicio ?></span>
										<?php if ($es_pendiente_pasada) {
											$fecha_cita = new DateTime($rellamada->fecha_cita);
											$fecha_actual_dt = new DateTime($fecha_actual);
											$intervalo = $fecha_cita->diff($fecha_actual_dt);
											$diferencia_dias = $intervalo->format('%a');
										?>
											<p>Llevas <?= $diferencia_dias ?> días de retraso.</p>
										<?php } ?>
									</div>
									<div class="w-25 text-end">
										<div class="btn-group">
											<button type="button" class="btn btn-sm btn-icon btn-outline btn-outline-warning" data-edit data-bs-toggle="tooltip" title="Editar Rellamada"><i class="fa-regular fa-pen-to-square"></i></button>
											<button type="button" class="btn btn-sm btn-icon btn-outline btn-outline-info" data-copiar data-bs-toggle="tooltip" title="Agendar rellamada vinculada">
												<i class="fa-regular fa-calendar"></i>
											</button>
										</div>
									</div>
								</div>
							<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
		
		<?php if (count($rellamadas_diarias_pendientes_propias) > 0) { ?>
		<div class="col-sm-6 col-lg-4"> 
			<div class="border border-secondary card mb-5 shadow">
				<div class="align-items-start border-0 card-header flex-nowrap p-5">
					<h3 class="card-title fs-1 align-items-start flex-column">
						<span class="fw-bold mb-2 text-gray-900">Rellamadas propias</span>
						<span class="text-muted fw-semibold fs-7">Rellamadas pendientes de tu usuario hasta hoy</span>
					</h3>
					<div class="card-toolbar">
						<span class="border border-1 border-secondary fs-2 fw-bold px-3 py-2 rounded"><?= count($rellamadas_diarias_pendientes_propias) ?></span>
					</div>
				</div>
				<div class="card-body p-5" style="height: 300px; overflow-y:auto;">
					<?php
					usort($rellamadas_diarias_pendientes_propias, 'compararFechas');
					$fecha_actual = date('Y-m-d');
					?>
					<div class="timeline-label">
						<?php 
							foreach ($rellamadas_diarias_pendientes_propias as $rellamada) {
								$es_pendiente_pasada = (strtotime($rellamada->fecha_rellamada) < strtotime($fecha_actual));
								$clase_destacada = $es_pendiente_pasada ? 'rellamada-destacada' : 'rellamada-normal';
						?>
								<div class="timeline-item <?= $clase_destacada ?>" data-rellamada="<?php echo htmlspecialchars(json_encode($rellamada), ENT_QUOTES, 'UTF-8'); ?>">
									<div class="timeline-label fw-bold text-gray-800 fs-6"><?= $rellamada->fecha_cita ?></div>
									<div class="timeline-badge">
										<i class="fa fa-genderless <?= $es_pendiente_pasada ? 'icono-destacado' : 'icono-normal' ?> fs-1"></i>
									</div>
									<div class="fw-normal timeline-content d-flex flex-column ps-3">
										<span class="text-gray-800 text-hover-primary fs-6 fw-bold"><?= $rellamada->cliente ?></span>
										<a href="tel:<?= $rellamada->telefono ?>"><?= $rellamada->telefono ?></a>
										<span class="text-muted fw-bold"><?= $rellamada->nombre_servicio ?></span>
										<?php if ($es_pendiente_pasada) {
											$fecha_cita = new DateTime($rellamada->fecha_cita);
											$fecha_actual_dt = new DateTime($fecha_actual);
											$intervalo = $fecha_cita->diff($fecha_actual_dt);
											$diferencia_dias = $intervalo->format('%a');
										?>
											<p>Llevas <?= $diferencia_dias ?> días de retraso.</p>
										<?php } ?>
									</div>
									<div class="w-25 text-end">
										<div class="btn-group">
											<button type="button" class="btn btn-sm btn-icon btn-outline btn-outline-warning" data-edit data-bs-toggle="tooltip" title="Editar Rellamada"><i class="fa-regular fa-pen-to-square"></i></button>
											<button type="button" class="btn btn-sm btn-icon btn-outline btn-outline-info" data-copiar data-bs-toggle="tooltip" title="Agendar rellamada vinculada">
												<i class="fa-regular fa-calendar"></i>
											</button>
										</div>
									</div>
								</div>
							<?php }  ?>
					</div>

					<style>
						/*
						.timeline-label .timeline-badge {
							border: 6px solid #FFE6E6 !important;
					}
							*/
						.rellamada-destacada .timeline-badge {
							background-color: transparent !important;
						}

						.rellamada-normal .timeline-badge {
							background-color: transparent !important;
						}

						.rellamada-destacada .timeline-badge i {
							color: #dc3545 !important;
						}

						.rellamada-normal .timeline-badge i {
							color: #ffc107 !important;
						}

						.rellamada-destacada a {
							color: #0d6efd !important;
						}

						.rellamada-destacada p {
							color: #dc3545 !important;
						}

						.rellamada-normal a {
							color: #0d6efd !important;
						}

						.btn-outline-warning {
							border-color: #ffc107 !important;
							color: #ffc107 !important;
						}

						.btn-outline-info {
							border-color: #17a2b8 !important;
							color: #17a2b8 !important;
						}
					</style>

				</div>
			</div>
		</div>
	<?php } ?>

	<?php if (!isset($citaspendientesnota) || $citaspendientesnota == 0) {
		$citaspendientesnota = [];
	}
	$clientescitasSinNota = [];
	foreach ($citaspendientesnota as $reg) {
		if (!isset($clientescitasSinNota[$reg['id_cliente']])) $clientescitasSinNota[$reg['id_cliente']] = [];
		$clientescitasSinNota[$reg['id_cliente']][] = $reg;
	} 
	?>
	
	<?php if (count($clientescitasSinNota)) { ?>
	<div class="col-sm-6 col-lg-4">
		<div class="border border-secondary card mb-5 shadow">
			<div class="align-items-start border-0 card-header flex-nowrap p-5">
				<h3 class="card-title fs-1 align-items-start flex-column">
					<span class="fw-bold mb-2 text-gray-900">Pacientes sin historia clínica</span>
					<span class="text-muted fw-semibold fs-7">Pendiente de añadir notas en sus citas</span>
				</h3>
				<div class="card-toolbar">
					<span class="border border-1 border-secondary fs-2 fw-bold px-3 py-2 rounded"><?= count($clientescitasSinNota) ?></span>
				</div>
			</div>
			<div class="card-body p-5" style="height: 300px; overflow-y:auto;">
					<?php $i = 0;
					foreach ($clientescitasSinNota as $xidcliente => $citas) {
						if (count($citas)) {
							$i++;
							$xxcitas = [];
							$xxdoctores = [];
							foreach ($citas as $cita) {
								$xxcitas[$cita['fecha_cita']] = date("d/m/Y", strtotime($cita['fecha_cita']));
								$xxdoctores[$cita['id_doctor']] = $cita['nombre_doctor'];
							} ?>
							<div class="d-flex align-items-center mb-8">
								<div class="flex-grow-1">
									<a href="<?php echo base_url(); ?>clientes/historial/ver/<?php echo $citas[0]['id_cliente']; ?>" class="text-gray-800 text-hover-primary fw-bold fs-6"><?php echo $citas[0]['nombre_cliente']; ?></a>
									<div class="ps-3">

										<?php foreach ($citas as $cita) { ?>
											<div class="d-flex justify-content-between align-items-center">
												<span class="text-muted fw-semibold d-block"><?= $cita['nombre_doctor'] ?></span>
												<span class="badge badge-light-success fs-8 fw-bold"><?= $cita['fecha_cita'] ?></span>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
					<?php }
					} ?>
			</div>
		</div>
	</div>
	<?php } ?>

	
	<?php if (count($citasAnuladasSinReagendar)) { ?>
	<div class="col-sm-6 col-lg-4">
		<div class="border border-secondary card mb-5 shadow">
			
			<div class="align-items-start border-0 card-header flex-nowrap p-5">
				<h3 class="card-title fs-1 align-items-start flex-column">
					<span class="card-label fw-bold text-gray-900">Citas anuladas sin reagendar</span>
					<span class="text-muted fw-semibold fs-7">Pacientes con última cita anulada o sin asistir y sin nuevas citas</span>
				</h3>
				<div class="card-toolbar">
					<span class="border border-1 border-secondary fs-2 fw-bold px-3 py-2 rounded"><?= count($citasAnuladasSinReagendar) ?></span>
				</div>
			</div>
			<div class="card-body p-5" style="height: 300px; overflow-y:auto;">
				
					<?php 
					foreach ($citasAnuladasSinReagendar as $i => $cita_pendiente) {
							
							$class_notas = 'btn-outline-warning';
							if ( $cita_pendiente['notas'] == 'pendiente' ) $class_notas = 'btn-outline-success';
							if ( $cita_pendiente['notas'] == 'archivado' ) $class_notas = 'btn-outline-danger';
							
							if ( $this->session->userdata('id_perfil') == 0 || $cita_pendiente['notas'] != 'archivado' ){
								
								$fecha_dada = new DateTime($cita_pendiente['fecha_ultima_cita']);
								$fecha_actual = new DateTime();
								$diferencia = $fecha_dada->diff($fecha_actual);
							?>
							<div class="d-flex pb-6">
								<div class="flex-grow-1">
									<i class="fa fa-user text-dark-emphasis"></i> <strong style="cursor:pointer" onclick="FichaCliente('<?= $cita_pendiente['id_cliente'] ?>');"><?php echo $cita_pendiente['cliente']; ?></strong><br/>
									<i class="fa fa-phone"></i> <?php echo $cita_pendiente['telefono']; ?><br/>
									<i class="fa fa-clock" title="Fecha última cita"></i> Última cita:  <?php echo $cita_pendiente['fecha_ultima_cita']; ?><br/>
									<span class="alert-danger">Hace <?php echo $diferencia->days ?> días.</span>
								</div>
								<div class="w-10 text-end">
									<button type="button" class="btn btn-sm btn-icon btn-outline <?php echo $class_notas; ?> notaspresu" data-bs-toggle="tooltip" aria-label="Añadir notas" data-bs-original-title="Añadir notas" data-idpresupuesto="<?php echo $cita_pendiente['id_presu'] ?>" data-numpresupuesto="<?php echo $cita_pendiente['nro_presupuesto'] ?>"><i class="fa-regular fa-pen-to-square"></i></button>
									<button type="button" class="btn btn-icon btn-outline btn-outline-primary btn-sm viewpresu" data-bs-toggle="tooltip" aria-label="Detalle del presupuesto" data-bs-original-title="Detalle del presupuesto" data-idpresupuesto="<?php echo $cita_pendiente['id_presu'] ?>" data-numpresupuesto="<?php echo $cita_pendiente['nro_presupuesto'] ?>"><i class="fa fa-eye"></i></button>
								</div>
								
							</div>
							<?php
							}
					} ?>
			</div>
		</div>
	</div>
	<?php } ?>
	
	<?php if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 3) { ?>
		<div class="row g-5 g-xl-8">
			<?php if (isset($centros_todos)) {
				foreach ($centros_todos as $c => $centro) {
					if ($centro['id_centro'] > 1) {
						$objetivo = $centro['rentabilidad_euros_mes'];
						$obtenido = $centro['ingresos_mes']->total_ingreso - $centro['gastos_mes']->total_gasto;
						//$porcentaje = round(($centro['ingresos_mes']->total_ingreso - $centro['gastos_mes']->total_gasto) * 100 / $centro['rentabilidad_euros_mes'], 2);
                        $porcentaje = $centro['rentabilidad_euros_mes'] ?
                            round(($centro['ingresos_mes']->total_ingreso - $centro['gastos_mes']->total_gasto) * 100 / $centro['rentabilidad_euros_mes'], 2)
                            : 100;
						$porcentaje = ($porcentaje > 100) ? 100 : $porcentaje;
			?>
						<div class="col-sm-6 col-lg-4">
							<div class="border border-secondary card mb-5 shadow">
								<div class="align-items-start border-0 card-header flex-nowrap p-5">
									<h3 class="card-title fs-1 align-items-start flex-column">
										<span class="fw-bold mb-2 text-gray-900"><?= $centro['nombre_centro'] ?></span>
										<span class="text-muted fw-semibold fs-7">Información finanzas</span>
									</h3>

								</div>
								<div class="card-body p-5">
									<div class="mb-4 px-9 py-3 border border-secondary d-flex justify-content-between">
										<?php /*<span class="fs-6 fw-semibold text-gray-500">Gastos del mes</span>
											<span class="fs-2qx fw-bold text-gray-800 me-2 d-block text-end"><?= euros($centro['gastos_mes']->total_gasto) ?></span> */ ?>
										<div class="">
											<span class="fs-6 fw-semibold text-gray-500 mt-3">Facturación</span>
											<span class="h1 fw-bold text-gray-800 me-2 d-block"><?= euros($centro['ingresos_mes']->total_ingreso) ?></span>
											<span class="fs-6 fw-semibold text-gray-500 mt-3">Objetivo</span>
											<span class="h1 fw-bold text-warning me-2 d-block ms-2" style="color: #4CAF50;">
												<?= euros($centro['facturacion_mes']) ?>
											</span>
										</div>
										<div class="">
											<div class="donut" data-content-dato="<?= $centro['ingresos_mes']->total_ingreso ?>|<?= $centro['facturacion_mes'] ?>" data-color="#4CAF50" data-size="120">
											</div>
										</div>
									</div>

									<div class="mb-4 px-9 py-3 border border-secondary d-flex justify-content-between">
										<div class="">
											<span class="fs-6 fw-semibold text-gray-500 mt-3">Rentabilidad</span>
											<span class="h1 fw-bold text-gray-800 me-2 d-block"><?= euros($centro['ingresos_mes']->total_ingreso - $centro['gastos_mes']->total_gasto) ?></span>
											<span class="fs-6 fw-semibold text-gray-500 mt-3">Objetivo</span>
											<span class="h1 fw-bold text-warning me-2 d-block ms-2" style="color: #2196F3;">
												<?= euros($centro['rentabilidad_euros_mes']) ?>
											</span>
										</div>
										<div class="">
											<div class="donut" data-content-dato="<?= $centro['ingresos_mes']->total_ingreso - $centro['gastos_mes']->total_gasto ?>|<?= $centro['rentabilidad_euros_mes'] ?>" data-color="#2196F3" data-size="120"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
			<?php }
				}
			} ?>
		</div>

		<?php
		$objetivo = $rentabilidad_euros_mes;
		$obtenido = $ingresos_mes->total_ingreso - $gasto_mes->total_gasto;
		$porcentaje = $rentabilidad_euros_mes !=0 ? round(($ingresos_mes->total_ingreso - $gasto_mes->total_gasto) * 100 / $rentabilidad_euros_mes, 2) : 100;
		$porcentaje = ($porcentaje > 100) ? 100 : $porcentaje;
		?>
		<div class="col-sm-6 col-lg-4">
			<div class="border border-secondary card mb-5 shadow">
				<div class="align-items-start border-0 card-header flex-nowrap p-5">
					<h3 class="card-title fs-1 align-items-start flex-column">
						<span class="fw-bold mb-2 text-gray-900 text-uppercase">Información financiera</span>
					</h3>

				</div>
				<div class="card-body p-5">
					<div class="mb-4 px-9 py-3 border border-secondary d-flex justify-content-between">
						<div class="">
							<span class="fs-6 fw-semibold text-gray-500 mt-3">Facturación</span>
							<span class="h1 fw-bold text-gray-800 me-2 d-block"><?= euros($ingresos_mes->total_ingreso) ?></span>
							<span class="fs-6 fw-semibold text-gray-500 mt-3">Objetivo</span>
							<span class="h1 fw-bold text-warning me-2 d-block ms-2" style="color: #4CAF50;">
								<?= euros($facturacion_mes) ?>
							</span>
						</div>
						<div class="">
							<div class="donut" data-content-dato="<?= $ingresos_mes->total_ingreso ?>|<?= $facturacion_mes ?>" data-color="#4CAF50" data-size="120">
							</div>
						</div>
					</div>

					<div class="mb-4 px-9 py-3 border border-secondary d-flex justify-content-between">
						<div class="">
							<span class="fs-6 fw-semibold text-gray-500 mt-3">Rentabilidad</span>
							<span class="h1 fw-bold text-gray-800 me-2 d-block"><?= euros($ingresos_mes->total_ingreso - $gasto_mes->total_gasto) ?></span>
							<span class="fs-6 fw-semibold text-gray-500 mt-3">Objetivo</span>
							<span class="h1 fw-bold text-warning me-2 d-block ms-2" style="color: #2196F3;">
								<?= euros($rentabilidad_euros_mes) ?>
							</span>
						</div>
						<div class="">
							<div class="donut" data-content-dato="<?= $ingresos_mes->total_ingreso - $gasto_mes->total_gasto ?>|<?= $rentabilidad_euros_mes ?>" data-color="#2196F3" data-size="120"></div>
						</div>
					</div>
				</div>


			</div>
		</div>
	<?php } ?>

</div>




<div class="modal fade" id="modal-rellamada" aria-labelledby="modal-rellamadaLabel" data-bs-focus="false" aria-hidden="true" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title w-100 text-center text-uppercase" id="exampleModalLabel">Editar Rellamada</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="mb-3">
					<label for="">Comentarios:</label>
					<textarea name="comentarios" id="comentarios_modal" class="form-control" rows="5"></textarea>
				</div>
				<div class="mb-3">
					<label for="">Estado:</label>
					<select name="estado" id="estado_modal" class="form-select">
						<option value="pendiente">Pendiente</option>
						<option value="realizada">Realizada</option>
						<option value="anulada">Anulada</option>
					</select>
				</div>
			</div>
			<input type="hidden" id="id_rellamada_modal" value="">
			<div class="modal-footer p-2 justify-content-center">
				<button type="button" class="btn btn-sm btn-secondary text-inverse-secondary" data-bs-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-sm btn-primary text-inverse-primary" id="actualizar_rellamada">Actualizar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-rellamada-copiar" aria-labelledby="modal-rellamada-copiarLabel" data-bs-focus="false" aria-hidden="true" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title w-100 text-center text-uppercase" id="exampleModalLabel">Agendar Rellamada vinculada</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="mb-3">
					<label for="">Comentarios para la nueva rellamada:</label>
					<textarea name="copiar_comentarios" id="copiar_comentarios_modal" class="form-control" rows="5"></textarea>
				</div>
				<div class="mb-3">
					<label for="">Nueva fecha:</label>
					<input type="date" name="copiar_fecha_rellamada" id="copiar_fecha_rellamada" class="form-control">
				</div>
			</div>
			<input type="hidden" id="id_copiar_rellamada_modal" value="">
			<div class="modal-footer p-2 justify-content-center">
				<button type="button" class="btn btn-sm btn-secondary text-inverse-secondary" data-bs-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-sm btn-primary text-inverse-primary" id="btn_copiar_rellamada">Crear</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-confirmacion-cita" aria-labelledby="modal-confirmacion-citaLabel" data-bs-focus="false" aria-hidden="true" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title w-100 text-center text-uppercase" id="modal-confirmacion-citaLabel">Confirmar Cita</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body text-center">
				<p>¿Estás seguro de que deseas confirmar esta cita?</p>
			</div>
			<input type="hidden" id="id_cita" value="">
			<div class="modal-footer p-2 justify-content-center">
				<button type="button" class="btn btn-sm btn-secondary text-inverse-secondary" data-bs-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-sm btn-primary text-inverse-primary" id="btn_confirmar_cita">Confirmar</button>
			</div>
		</div>
	</div>
</div>


<script>
	function ver_registro(id_tarea) {
		var posicion_x;
		var posicion_y;
		var ancho = 650;
		var alto = 700;
		posicion_x = (screen.width / 2) - (ancho / 2);
		posicion_y = (screen.height / 2) - (alto / 2);
		window.open("<?php echo base_url(); ?>avisos/iteraciones_tareas/editar/" + id_tarea, "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
	}

	function confirmar_cita(id_tarea) {
		var posicion_x;
		var posicion_y;
		var ancho = 650;
		var alto = 700;
		posicion_x = (screen.width / 2) - (ancho / 2);
		posicion_y = (screen.height / 2) - (alto / 2);
		window.open("<?php echo base_url(); ?>avisos/confirmar_cita/confirmar/" + id_tarea, "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
	}


	$(document).on('click', '[data-edit]', function(event) {
		var button = $(this);
		var dataObject = button.parents("[data-rellamada]").attr('data-rellamada');
		var data = JSON.parse(dataObject);
		$('#comentarios_modal').val(data.comentarios)
		$('#estado_modal').val(data.estado)
		$('#id_rellamada_modal').val(data.id_rellamada)
		$('#modal-rellamada').modal('show');
	});



	$(document).on('click', '[data-confirmar]', function(event) {
		var button = $(this);
		var dataObject = button.parents("[data-conf]").attr('data-conf');
		var data = JSON.parse(dataObject);

		console.log(data)

		$('#comentarios_modal').val(data.comentarios)
		$('#estado_modal').val(data.estado)
		$('#id_cita').val(data.id_cita)
		$('#modal-confirmacion-cita').modal('show');
	});

	$(document).on('click', '#actualizar_rellamada', function(event) {
		var comentarios = $('#comentarios_modal').val()
		var id_rellamada = $('#id_rellamada_modal').val()
		var estado = $('#estado_modal').val()
		var formData = new FormData();
		formData.append("id_rellamada", id_rellamada);
		formData.append("estado", estado);
		formData.append("comentarios", comentarios);
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			method: 'post',
			url: '<?php echo base_url() ?>Rellamadas/actualizarRellamada',
			data: formData,
			processData: false,
			contentType: false,
			success: function(resp) {
				if (resp == false) {
					Swal.fire({
						title: 'Error',
						willClose: function() {},
					});
				} else {
					Swal.fire({
						title: 'Actualizado',
						willClose: function() {
							//	tabla_rellamadas.draw();
							$('#modal-rellamada').modal('hide');
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
	})

	$(document).on('click', '[data-copiar]', function(event) {
		var button = $(this);
		var dataObject = button.parents("[data-rellamada]").attr('data-rellamada');
		var data = JSON.parse(dataObject);
		$('#copiar_comentarios_modal').val('')
		$('#copiar_fecha_rellamada').val(data.fecha_rellamada)
		$('#id_copiar_rellamada_modal').val(data.id_rellamada)
		$('#modal-rellamada-copiar').modal('show');
	});

	$(document).on('click', '#btn_copiar_rellamada', function(event) {
		var comentarios = $('#copiar_comentarios_modal').val()
		var id_rellamada = $('#id_copiar_rellamada_modal').val()
		var fecha_rellamada = $('#copiar_fecha_rellamada').val()
		var formData = new FormData();
		formData.append("parent", id_rellamada);
		formData.append("fecha_rellamada", fecha_rellamada);
		formData.append("comentarios", comentarios);
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			method: 'post',
			url: '<?php echo base_url() ?>Rellamadas/crearRellamadaVinculada',
			data: formData,
			processData: false,
			contentType: false,
			success: function(resp) {
				if (resp == false) {
					Swal.fire({
						title: 'Error',
						willClose: function() {},
					});
				} else {
					Swal.fire({
						title: 'Rellamada agendada',
						willClose: function() {
							//tabla_rellamadas.draw();
							$('#modal-rellamada-copiar').modal('hide');
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
	})

	$(document).on('click', '#btn_confirmar_cita', function(event) {
		var id_cita = $('#id_cita').val()
		var formData = new FormData();
		formData.append("parent", id_cita);
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			method: 'post',
			url: '<?php echo base_url() ?>Site/confirmar_cita',
			data: formData,
			processData: false,
			contentType: false,
			success: function(resp) {
				if (resp == false) {
					Swal.fire({
						title: 'Error',
						willClose: function() {},
					});
				} else {
					Swal.fire({
						title: 'Cita confirmada',
						willClose: function() {
							$('#modal-confirmacion-cita').modal('hide');
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
	})
</script>


<script>
	$(document).ready(function() {
		$('.donut').each(function() {
			const element = $(this);
			const data = element.attr('data-content-dato').split('|').map(Number);
			const color = (element.attr('data-color') !== 'undefined') ? element.attr('data-color') : '#080808';
			const size = (element.attr('data-size') !== 'undefined') ? element.attr('data-size') : 200;
			const total = data[data.length - 1];
			const value = data[0];
			const proportion = (value / total) * 100;
			const proportiondraw = (proportion > 100) ? 100 : proportion;
			element.css({
				position: 'relative',
				width: size + 'px',
				height: size + 'px',
				"border-radius": "50%",
				border: "1px solid " + color
			});

			// Calcular el tamaño del canvas
			const canvasSize = parseFloat(size) + 20; // Ajustar el tamaño del canvas según el tamaño de .donut

			// Crear un canvas dentro del elemento .donut
			const canvas = document.createElement('canvas');
			canvas.width = canvasSize;
			canvas.height = canvasSize;
			canvas.style.position = 'Absolute';
			canvas.style.top = '-5px';
			element.append(canvas);

			const ctx = canvas.getContext('2d');
			const centerX = canvas.width / 2;
			const centerY = canvas.height / 2;

			ctx.beginPath();
			ctx.arc(centerX, centerY, canvasSize / 2, 0, 2 * Math.PI);
			ctx.strokeStyle = color; // Color del borde
			ctx.lineWidth = 3; // Ancho del borde
			ctx.stroke();

			const progressChart = new Chart(ctx, {
				type: 'doughnut',
				data: {
					datasets: [{
						data: [proportiondraw, 100 - proportiondraw],
						backgroundColor: [
							color,
							'transparent'
						],
						borderDashOffset: 5,
						borderWidth: 5
					}]
				},
				options: {
					rotation: -Math.PI / 2, // Rotar para que comience desde la parte superior
					cutout: '75%', // Ajustar el tamaño del corte interno
					/*plugins: {
						tooltip: {
							callbacks: {
								label: function(context) {
									return context.raw.toFixed(2) + '%';
								}
							}
						}
					}*/
				}
			});

			// Crear y configurar el elemento label
			const label = document.createElement('label');
			label.classList.add('donut-label');
			label.textContent = proportion.toFixed(2) + '%';
			label.style.position = 'absolute';
			label.style.top = '50%';
			label.style.left = '50%';
			label.style.transform = 'translate(-50%, -50%)';
			label.style.textAlign = 'center';
			label.style.fontSize = canvasSize / 7 + 'px'; // Tamaño de la fuente
			label.style.fontWeight = 'bold'; // Peso de la fuente
			label.style.color = color; // Color del texto
			element.append(label);
		});

	});
</script>

<div class="modal fade" id="modal-presupuesto" aria-labelledby="modal-presupuestoLabel" data-bs-focus="false" aria-hidden="true" tabindex="-1">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title w-100 text-center text-uppercase" id="exampleModalLabel">Detalle</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer p-1">
				<button type="button" class="btn btn-sm btn-secondary text-inverse-secondary" data-bs-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-notas-presupuestos" aria-labelledby="modal-notaPresupuestosLabel" data-bs-focus="false" aria-hidden="true" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title w-100 text-center text-uppercase" id="exampleModalLabel">Notas</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="notaPresupuestosForm" action="<?= base_url() ?>presupuestos/json_savenotas/" method="post">
			<div class="modal-body">
				<div class="mb-3">
					<label for="">Comentarios:</label>
					<textarea id="presupuestos_notas_comentarios" name="comentarios" class="form-control" rows="5"></textarea>
				</div>
				<div class="mb-3">
					<label for="">Estado:</label>
					<select id="presupuestos_notas_estado" name="estado" class="form-select">
						<option value="pendiente">Pendiente</option>
						<option value="archivado">Archivado</option>
					</select>
				</div>
			</div>
			<input type="hidden" id="presupuestos_notas_id_nota_presupuesto" name="id_nota_presupuesto" value="">
			<input type="hidden" id="presupuestos_notas_id_presupuesto" name="id_presupuesto" value="">

			</form>
			<div class="modal-footer p-2 justify-content-center">
				<button type="button" class="btn btn-sm btn-secondary text-inverse-secondary" data-bs-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-sm btn-primary text-inverse-primary" id="actualizar_notapresupuesto">Actualizar</button>
			</div>
		</div>
	</div>
</div>

<script>
	jQuery(".viewpresu").on("click",function(){
		
		var idpresu=jQuery(this).data("idpresupuesto");
		var numpresu=jQuery(this).data("numpresupuesto");
		var url = '<?= base_url() ?>presupuestos/ver_detalle/' + idpresu;
		
		$('#modal-presupuesto .modal-title').html('Detalle del presupuesto #'+numpresu);
		$('#modal-presupuesto .modal-body').html('<i class="fas fa-sync fa-spin"></i>');
		$('#modal-presupuesto').modal('show');
		$('.tooltip').remove();
		
		$.get(url, function(data) {
			$('#modal-presupuesto .modal-body').html(data);
		});
	});
	
	jQuery(".notaspresu").on("click",function(){
		
		var idpresu=jQuery(this).data("idpresupuesto");
		var numpresu=jQuery(this).data("numpresupuesto");
		var url = '<?= base_url() ?>presupuestos/json_getnotas/' + idpresu;
		
		$('#modal-notas-presupuestos .modal-title').html('Notas del presupuesto #'+numpresu);
		$('#modal-notas-presupuestos').modal('show');
		$('.tooltip').remove();
		
		$.get(url, function(data) {
			var data = JSON.parse(data);
			$('#presupuestos_notas_id_nota_presupuesto').val(data.id_nota_presupuesto);
			$('#presupuestos_notas_id_presupuesto').val(data.id_presupuesto);
			$('#presupuestos_notas_comentarios').val(data.comentarios);
			$('#presupuestos_notas_estado').val(data.estado);
		});
	});
	
	jQuery("#actualizar_notapresupuesto").on("click",function(){
		
		$.post( $('#notaPresupuestosForm').attr('action'), $('#notaPresupuestosForm').serialize(), function(data){
			
			Swal.fire({
				title: 'Actualizado',
				willClose: function() {
					$('#modal-notas-presupuestos').modal('hide');
				},
			});
		});
	});
	
	function FichaCliente(id_cliente) {
		var url = "<?php echo base_url(); ?>clientes/historialpopup/ver/" + id_cliente;
		openwindow('pago_cuenta', url, 800, 680);
	}
	
</script>