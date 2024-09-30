<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>
<script type="text/javascript" src="<?= base_url() ?>assets_v5/plugins/custom/tinymce/tinymce.bundle.js"></script>
<style>
    .dataTables_filter {
        text-align: right;
    }

    .swal2-container.swal2-center.swal2-backdrop-show {
        z-index: 99999;
    }
</style>
<body class="p-5">
	<h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">Ficha de cliente</h1>
	<?php if (isset($estado)) {
		if ($estado > 0) { ?>
			<div class="alert alert-dismissible alert-primary p-5 mb-10">
				<div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center"></div>
				<button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
					<i class="fa-times fas fs-3 text-primary"></i>
				</button>
				<span>
					EL REGISTRO SE GUARDÓ CORRECTAMENTE </span>
			</div>
		<?php } else { ?>
			<div class="alert alert-danger display-hide" style="display: block; text-align: center;">
				<div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center"></div>
				<button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
					<i class="fa-times fas fs-3 text-primary"></i>
				</button>
				<span>
					YA EXISTE UN CLIENTE CON EL MISMO EMAIL EN EL SISTEMA</span>
				<p><br><a href="javascript:history.back();">Volver</a></p>
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

	<div class="card card-flush m-5">
		<div class="card-body">
			<div class="d-flex flex-center flex-row mb-5">
				<h1 class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1">
					<?= ($registros[0]['activo'] == 1) ? '<i class="fas fa-user-check me-3 text-success fs-2"></i>' : '<i class="fas fa-user-alt-slash me-3 text-danger fs-2"></i>'; ?>
					<?= $registros[0]['nombre'] ?> <?= (isset($registros)) ? $registros[0]['apellidos'] : '' ?>
					<?php if ($this->session->userdata('id_perfil') != 6&&$this->session->userdata('id_perfil') != 1) { ?><a class="btn btn-sm btn-icon btn-warning" href="<?php echo base_url(); ?>clientes/gestion/editar/<?php echo $registros[0]['id_cliente'] ?>" data-bs-toggle="tooltip" title="Editar cliente"><i class="fa-regular fa-pen-to-square"></i></a><?php } ?>
				</h1>
			</div>
			<?php if ($this->session->userdata('id_perfil') <> 6 && $this->session->userdata('id_perfil') != 1) { ?>
				<div class="d-flex flex-row flex-wrap justify-content-center">
					<div class="p-3 border">
						<div class="fw-bold">Fecha Alta</div>
						<div class="text-gray-600">
							<?= (isset($registros)) ? $registros[0]['fecha_creacion_ddmmaaaa'] : '' ?>
						</div>
					</div>
					<div class="p-3 border">
						<div class="fw-bold">DNI</div>
						<div class="text-gray-600">
							<?= (isset($registros)) ? $registros[0]['dni'] : '' ?>
						</div>
					</div>
					<?php if ($this->session->userdata('id_perfil') != 6&&$this->session->userdata('id_perfil') != 1) { ?>
						<div class="p-3 border">
							<div class="fw-bold">Email</div>
							<div class="text-gray-600">
								<?= (isset($registros)) ? (($registros[0]['email'] == "") ? "N/D" : $registros[0]['email']) : '' ?>
							</div>
						</div>
					<?php } ?>

					<div class="p-3 border">
						<div class="fw-bold">Nacimiento</div>
						<div class="text-gray-600">
							<?= (isset($registros)) ? (($registros[0]['fecha_nacimiento_ddmmaaaa'] == "") ? "N/D" : $registros[0]['fecha_nacimiento_ddmmaaaa']) : '' ?>
						</div>
					</div>
					<?php if ($this->session->userdata('id_perfil') != 6&&$this->session->userdata('id_perfil') != 1) { ?>
						<div class="p-3 border">
							<div class="fw-bold">Teléfono</div>
							<div class="text-gray-600">
								<?= (isset($registros)) ? (($registros[0]['telefono'] == "") ? "N/D" : $registros[0]['telefono']) : '' ?>
							</div>
						</div>

						<div class="p-3 border">
							<div class="fw-bold">Dirección</div>
							<div class="text-gray-600">
								<?= (isset($registros)) ? (($registros[0]['direccion'] == "") ? "N/D" : $registros[0]['direccion']) : '' ?>
							</div>
						</div>

						<div class="p-3 border">
							<div class="fw-bold">Código Postal</div>
							<div class="text-gray-600">
								<?= (isset($registros)) ? (($registros[0]['codigo_postal'] == "") ? "N/D" : $registros[0]['codigo_postal']) : '' ?>
							</div>
						</div>
					<?php } ?>

					<div class="p-3 border">
						<div class="fw-bold">Notas</div>
						<div class="text-gray-600">
							<?= (isset($registros)) ? (($registros[0]['notas'] == "") ? "" : $registros[0]['notas']) : '' ?>
						</div>
					</div>
					<?php if ($this->session->userdata('id_perfil') != 6&&$this->session->userdata('id_perfil') != 1) { ?>
						<div class="p-3 border">
							<div class="fw-bold">Publicidad</div>
							<div class="text-gray-600">
								<?= (isset($registros)) ? (($registros[0]['no_quiere_publicidad'] == 1) ? "<strong style='color:#f00;'>NO quiero recibir publicidad</strong>" : "SI quiero recibir publicidad") : '' ?>
							</div>
						</div>
					<?php } ?>
					<?php if ($this->session->userdata('id_perfil') != 6&&$this->session->userdata('id_perfil') != 1) { ?>
						<div class="p-3 border">
							<div class="fw-bold">Como nos conoció</div>
							<div class="text-gray-600">
								<?= (isset($registros)) ? (($registros[0]['como_conocio'] != '') ? "<strong style='color:#f00;'>".$registros[0]['como_conocio']."</strong>" : "Sin indicar") : '' ?>
							</div>
						</div>
					<?php } ?>
					<div class="p-3 border">
						<div class="fw-bold">Protección de datos</div>
						<div class="text-gray-600">
							<?= ($existe_firma) ? '<a class="" href="' . base_url() . 'clientes/ver_firma_lopd/' . $registros[0]['id_cliente'] . '" target="_blank">Ver PDF de la firma</a>' : '<a class="" href="' . base_url() . 'clientes/proteccion_de_datos/' . $registros[0]['id_cliente'] . '" class="btn btn-info text-inverse-info">Firmar Protección de Datos</a>' ?>
						</div>
					</div>
				</div>
			<?php } ?>


		</div>

		<div class="card-footer px-5 py-0">
			<ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-6 fw-bold" role="tablist">
				<?php if ($this->session->userdata('id_perfil') <> 6 && $this->session->userdata('id_perfil') != 1) { ?>
					<li class="nav-item" role="presentation"><a class="nav-link text-active-primary py-5 me-6 active" aria-selected="true" role="tab" href="#tab1default" data-bs-toggle="tab">Historial</a></li>
				<?php } ?>
				<li class="nav-item" role="presentation"><a class="nav-link text-active-primary py-5 me-6 <?= ($this->session->userdata('id_perfil') == 6) ? 'active' : '' ?>" aria-selected="<?= ($this->session->userdata('id_perfil') == 6) ? 'true' : 'false' ?>" tabindex="-1" role="tab" href="#tab2default" data-bs-toggle="tab">H. Clínica</a></li>
				<?php if ($this->session->userdata('id_perfil') <> 6 && $this->session->userdata('id_perfil') != 1) { ?>
					<?php /*<li class="nav-item" role="presentation"><a class="nav-link text-active-primary py-5 me-6" aria-selected="false" tabindex="-1" role="tab" href="#tab3default" data-bs-toggle="tab">Carnets</a></li>*/ ?>
					<li class="nav-item" role="presentation"><a class="nav-link text-active-primary py-5 me-6" aria-selected="false" tabindex="-1" role="tab" href="#tab11default" data-bs-toggle="tab">Rellamadas</a></li>
					<li class="nav-item" role="presentation"><a class="nav-link text-active-primary py-5 me-6" aria-selected="false" tabindex="-1" role="tab" href="#tab9default" data-bs-toggle="tab">Fichas</a></li>
					<li class="nav-item" role="presentation"><a class="nav-link text-active-primary py-5 me-6" aria-selected="false" tabindex="-1" role="tab" href="#tab8default" data-bs-toggle="tab">Asociados</a></li>
					<li class="nav-item" role="presentation"><a class="nav-link text-active-primary py-5 me-6" aria-selected="false" tabindex="-1" role="tab" href="#tab4default" data-bs-toggle="tab">Otros Datos</a></li>
					<li class="nav-item" role="presentation"><a class="nav-link text-active-primary py-5 me-6" aria-selected="false" tabindex="-1" role="tab" href="#tab14default" data-bs-toggle="tab">Notas internas</a></li>
					<li class="nav-item" role="presentation"><a class="nav-link text-active-primary py-5 me-6" aria-selected="false" tabindex="-1" role="tab" href="#tab5default" data-bs-toggle="tab">N.Citas</a></li>
					<li class="nav-item" role="presentation"><a class="nav-link text-active-primary py-5 me-6" aria-selected="false" tabindex="-1" role="tab" href="#tab6default" data-bs-toggle="tab">N.
							Cobrar</a></li>
					<li class="nav-item" role="presentation"><a class="nav-link text-active-primary py-5 me-6" aria-selected="false" tabindex="-1" role="tab" href="#tab7default" data-bs-toggle="tab">Saldo</a></li>
					<li class="nav-item" role="presentation"><a class="nav-link text-active-primary py-5 me-6" aria-selected="false" tabindex="-1" role="tab" href="#tab10default" data-bs-toggle="tab">Consentimientos</a></li>
				<?php } ?>
				<li class="nav-item" role="presentation"><a class="nav-link text-active-primary py-5 me-6" aria-selected="false" tabindex="-1" role="tab" href="#tab12default" data-bs-toggle="tab">Documentos</a></li>
				<li class="nav-item" role="presentation"><a class="nav-link text-active-primary py-5 me-6" aria-selected="false" tabindex="-1" role="tab" href="#tab13default" data-bs-toggle="tab">Presupuestos</a></li>
			</ul>
		</div>
		<div class="card-body">
			<div class="tab-content">

				<div class="tab-pane fade <?= ($this->session->userdata('id_perfil') == 6) ? 'active show' : '' ?>" id="tab2default">
					<h3 class="fw-bold fs-2x text-grey-700 border-bottom pb-3 d-flex justify-content-between">Historia
						Clínica <a href="<?php echo base_url(); ?>clientes/historiaclinicapdf/<?= $id_cliente ?>" target="_blank"><i class="fa fa-print" style="font-size:20px"></i></a>
						<button type="button" class="btn btn-primary text-inverse-primary margin-top-20 nuevoevol">Nueva
							Nota Historia Clínica</button>
					</h3>

					<?php if ($notas_evolutivo != 0) {
						foreach ($notas_evolutivo as $key => $nota) { ?>
							<div class="d-flex flex-column border-1 border-dashed card-rounded p-5 p-lg-10 mb-14 item_evolutivo">
								<div class="d-flex justify-content-between border-2 border-bottom border-dark-subtle mb-3">
									<h4 class="text-start text-dark fw-bold fs-4 text-uppercase gs-0">
                                        <?php
                                        if($nota['doctor']!=null && $nota['doctor'] != $nota['empleado'] && !empty(trim($nota['doctor']))){
                                            echo $nota['doctor'];
                                            echo ' - <span class="" style="font-size: 0.575em">'.$nota['empleado'].'</span>';
                                        }
                                        else echo $nota['empleado'];
                                        ?>
									</h4>
									<h5 class="text-start text-danger fw-bold fs-5 text-uppercase gs-0">
										<?= $nota['fecha_nota_formateada'] ?>
										<?php if ($this->session->userdata('id_perfil') == 0 || ($nota['id_usuario_creador'] == $this->session->userdata('id_usuario') && $nota['fecha_creacion'] > date("Y-m-d H:i:s", strtotime("-1 day")))) { ?>
											<button type="button" class="btn btn-sm btn-warning btn-icon ms-3 editevol"
                                                    idevol="<?= $nota['id'] ?>" idd="<?= $nota['id_usuario_doctor'];?>"
                                                    fn="<?= $nota['fecha_nota'];?>" fnt="<?= $nota['fecha_creacion'] > date("Y-m-d H:i:s", strtotime("-1 day")); ?>"
                                            ><i class="fa-regular fa-pen-to-square"></i></button>
											<button type="button" class="btn btn-sm btn-icon btn-danger delevol" idevol="<?= $nota['id'] ?>"><i class="fa-solid fa-trash"></i></button>
										<?php } ?>
									</h5>

								</div>
								<div class="mb-0 fs-6">
									<div class="text-muted mb-2 content_evol"><?= $nota['nota'] ?></div>
								</div>
							</div>

						<?php }
					} else { ?>
						<div class="d-flex flex-column border-1 border-dashed card-rounded p-5 p-lg-10 mb-14 item_evolutivo">
							<div class="d-flex justify-content-between border-2 border-bottom border-dark-subtle mb-3">
								<h4 class="text-start text-dark fw-bold fs-4 text-uppercase gs-0">No hay notas</h4>
							</div>
						</div>
					<?php } ?>

				</div>

				<div class="tab-pane fade" id="tab12default">
					<h3 class="fw-bold fs-2x text-grey-700 border-bottom pb-3">Documentos</h3>
					<?php if ($this->session->userdata('id_perfil') <> 6 && $this->session->userdata('id_perfil') != 1 ) { ?>
						<div class="row mb-5 border-bottom align-items-end">
							<div class="col-md-4">
								<label for="formFile" class="form-label">Carga el documento</label>
								<input class="form-control" type="file" id="formFile" name="nuevodoc">

							</div>
							<div class="col-md-4">
								<label class="form-label">Hasta</label>
								<input type="date" id="fecha_hasta" name="fecha_hasta" value="" class="form-control form-control-solid" placeholder="Hasta" />
							</div>
							<div class="col-md-4">
								<button type="button" onclick="Exportar(<?php echo $registros[0]['id_cliente']; ?>)" class="btn btn-warning text-inverse-warning">Exportar CSV</button>
								<button type="button" onclick="Ver_HistorialAntiguo(<?php echo $registros[0]['id_cliente']; ?>)" class="btn btn-light text-inverse-light">H.Antiguo</button>
							</div>
						</div>
					<?php } ?>
					<div class="table-responsive">
						<table class="datatable table align-middle table-striped table-row-dashed fs-6 gy-5">
							<thead class="">
								<tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
									<th style="display: none;">ID</th>
									<th>Fecha Estudio</th>
									<th>Documento</th>
									<th>Tipo</th>
									<th>Creación</th>
									<?php if ($this->session->userdata('id_perfil') <> 6 && $this->session->userdata('id_perfil') != 1) { ?>
										<th></th>
										<th></th>
									<?php } ?>
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
											);
											$jsonDatos = json_encode($datos); ?>
											<tr data-json="<?php echo htmlentities($jsonDatos); ?>">
												<td style="display: none;"><?php echo $row['id_documento']; ?></td>
												<td><?php echo $row['fecha_estudio']; ?></td>
												<td><a href="<?php echo base_url(); ?>recursos/clientes_docs/<?= $id_cliente ?>/<?php echo $row['documento']; ?>" target="_blank" rel="noopener noreferrer"><?php echo $row['documento']; ?></a></td>
												<td><?php echo $row['tipo']; ?></td>
												<td><?php echo $row['fecha_creacion']; ?></td>
												<?php if ($this->session->userdata('id_perfil') <> 6 && $this->session->userdata('id_perfil') != 1) { ?>
													<td>
														<button type="button" class="btn btn-sm btn-icon btn-warning" editarRowDocumento data-bs-toggle="tooltip" title="Editar documento"><i class="fa-regular fa-pen-to-square"></i></button>
													</td>
													<td>
														<button class="btn btn-sm btn-icon btn-danger" borrarDocumento data-bs-toggle="tooltip" title="Borrar documento"><i class="fa-solid fa-trash"></i></button>
													</td>
												<?php } ?>
											</tr>
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

				<div class="tab-pane fade" id="tab13default">
					<h3 class="fw-bold fs-2x text-grey-700 border-bottom pb-3 d-flex justify-content-between w-100">
						Presupuestos
						<div id="buttons"></div>
						<?php if ($this->session->userdata('id_perfil') <> 6 && $this->session->userdata('id_perfil') != 1) { ?>
							<a href="<?php echo base_url(); ?>presupuestos/nuevo_presupuesto/<?php echo $id_cliente; ?>" class="btn btn-primary text-inverse-primary margin-top-20">Nuevo presupuesto</a>
						<?php } ?>
					</h3>
					<div class="row mb-5 border-bottom align-items-end">
						<div class="col">
							<label class="form-label">Buscar por texto</label>
							<div class="d-flex align-items-center position-relative my-1">
								<i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
								<input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="myTable1">
							</div>
						</div>
						<div class="col-md-3">
							<label class="form-label">Desde</label>
							<input type="date" id="fecha_desde_presupuestos" name="fecha_desde" value="" class="form-control form-control-solid" placeholder="Desde" />
						</div>
						<div class="col-md-3">
							<label class="form-label">Hasta</label>
							<input type="date" id="fecha_hasta_presupuestos" name="fecha_hasta" value="" class="form-control form-control-solid" placeholder="Hasta" />
						</div>
						<div class="col-md-3">
							<label class="form-label">Estado</label>
							<select name="filter_estado" id="filter_estado" class="form-select form-select-solid w-auto">
								<option value="">Cualquier estado</option>
								<option value="Borrador">Borrador</option>
								<option value="Pendiente">Pendiente</option>
								<option value="Aceptado parcial">Aceptado parcial</option>
								<option value="Aceptado">Aceptado</option>
								<option value="Rechazado">Rechazado</option>
							</select>
						</div>

					</div>
					<div class="table-responsive">
						<table id="tabla_presupuestos" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
							<thead class="">
								<tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
									<th>#</th>
									<th>Cliente</th>
									<th>Fecha</th>
									<th>Estado</th>
									<th>Validez</th>
									<th>Empleado</th>
									<th></th>
									<?php if ($this->session->userdata('id_perfil') <> 6 && $this->session->userdata('id_perfil') != 1) { ?>
										<th></th>
									<?php } ?>
								</tr>
							</thead>
							<tbody class="text-gray-700 fw-semibold"></tbody>
						</table>
					</div>
				</div>

				<?php if ($this->session->userdata('id_perfil') <> 6 && $this->session->userdata('id_perfil') != 1) {  ?>
					<div class="tab-pane fade active show" id="tab1default">
						<h3 class="fw-bold fs-2x text-grey-700 border-bottom pb-3">Historial</h3>
						<div class="row mb-5 border-bottom align-items-end pb-5">
							<div class="col-md-4">
								<label class="form-label">Desde</label>
								<input type="date" id="fecha_desde" name="fecha_desde" value="" class="form-control form-control-solid" placeholder="Desde" />
							</div>
							<div class="col-md-4">
								<label class="form-label">Hasta</label>
								<input type="date" id="fecha_hasta" name="fecha_hasta" value="" class="form-control form-control-solid" placeholder="Hasta" />
							</div>
							<div class="col-md-4">
								<button type="button" onclick="Exportar(<?php echo $registros[0]['id_cliente']; ?>)" class="btn btn-warning text-inverse-warning">Exportar CSV</button>
								<button type="button" onclick="Ver_HistorialAntiguo(<?php echo $registros[0]['id_cliente']; ?>)" class="btn btn-light text-inverse-light">H.Antiguo</button>
							</div>
						</div>
						<div class="table-responsive">
                            <table id="myTable3" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                                <thead class="">
                                <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                                    <th style="display: none;">ID</th>
                                    <th>Fecha - Hora</th>
                                    <th>Centro</th>
                                    <th>Concepto</th>
                                    <th>Emp.</th>
                                    <th>Euros</th>
                                    <?php /* <th>Templos</th> */ ?>
                                    <th>Estado</th>
                                </tr>
                                </thead>
                                <tbody class="text-gray-700 fw-semibold">
                                <?php $total_importe = 0;
                                $total_templos = 0;
                                if (isset($historial)) {
                                    if ($historial != 0) {
                                        foreach ($historial as $key => $row) {
                                            if($row['importe_total_final'] > 0 || $row['estado'] == 'Cierre Caja' || $row['estado'] == 'Devuelto'){
                                                $datatipo = 'tipo_pago';
                                            }elseif($row['nro_presupuesto'] != ''){
                                                $datatipo = 'tipo_cita_presupuesto';
                                            }else{
                                                $datatipo = 'tipo_cita_no_presupuesto';
                                            }

                                            ?>
                                            <tr style="background: <?php echo $row['color_estado']; ?>;">
                                                <td style="display: none;">
                                                    <?php echo $row['fecha_hora_concepto_aaaammdd'] . " " . $row['hora']; ?>
                                                </td>
                                                <td style="text-align: center; font-size: 11px; background: <?php echo $row['color_estado']; ?> !important;">
                                                    <?php if ($row['tipo_pago'] == "#templos") { ?>
                                                        <a class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6" href="<?php echo base_url(); ?>dietario/ticket_today/<?php echo $row['fecha_hora_concepto_aaaammdd'] . "/" . $row['id_cliente']; ?>" target="_blank"><?php echo $row['fecha_hora_concepto_ddmmaaaa_abrv']; ?> <?php echo $row['hora']; ?></a>
                                                    <?php } else { ?>
                                                        <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6"><?= $row['fecha_hora_concepto_ddmmaaaa_abrv'] ?> <?php echo $row['hora']; ?></span>
                                                    <?php } ?>
                                                    <?php if ($row['id_ticket'] > 0 && $row['estado'] == "Pagado") { ?>
                                                        <!--                <a class="btn btn-sm btn-icon btn-warning" href="<?php echo base_url(); ?>dietario/ver_ticket/<?php echo $row['id_ticket'] ?>" target="_blank" data-bs-toggle="tooltip" title="Ver ticket"><i class="fas fa-eye"></i></a>-->
                                                    <?php } ?>
                                                    <?php if ($row['id_ticket'] == 0 && $row['estado'] == "Pagado") { ?>
                                                        <!--                <button type="button" onclick="Generarticket(<?php echo $row['id_cliente']; ?>,<?php echo $row['id_centro']; ?>);" class="btn btn-icon btn-sm btn-primary" data-bs-toggle="tooltip" title="Generar ticket"><i class="fas fa-ticket"></i></button>-->
                                                    <?php } ?>
                                                    <?php if ($row['id_pedido'] > 0) { ?>
                                                        <i class="fa fa-globe"></i>
                                                    <?php } ?>

                                                    <?php if ($datatipo != 'tipo_pago' && $row['estado_cita'] == 'Programada' && $this->session->userdata('id_perfil') == 0) { ?>
                                                        <button type="button" class="btn btn-sm btn-icon btn-info ms-3" data-bs-toggle="tooltip" title="Editar cita" onclick="CitasEditar(<?php echo $row['id_cita']; ?>);"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                                    <?php } ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?php echo $row['nombre_centro']; ?><br/>
                                                    <?php if ($row['estado'] == "Pagado" || $row['estado'] == "Devuelto") { ?>
                                                        <button type="button" class="btn btn-sm btn-icon btn-dark" data-bs-toggle="tooltip" title="Generar Recibo" onclick="Facturacion(<?php echo $row['id_cliente']; ?>,<?php echo $row['id_centro']; ?>);"><i class="fas fa-file-invoice"></i></button>
                                                    <?php } ?>
                                                </td>

                                                <td style="text-align: center; <?= ($row['producto'] != "") ? 'background: #fad7e4;' : '' ?> <?= ($row['descuento_euros'] > 0 || $row['descuento_porcentaje'] > 0) ? 'background: #dda6fa;' : '' ?> <?= ($row['codigo_proveedor'] != "") ? 'background: #f9ca8e;' : '' ?>">
                                                    <?php if ($row['servicio'] != "") {
                                                        echo $row['servicio'];
                                                        if ($row['codigo_proveedor'] != "") {
                                                            echo "<br>" . $row['codigo_proveedor'];
                                                        }
                                                    } ?>
                                                    <?php if ($row['producto'] != "") {
                                                        echo $row['producto'];
                                                        if ($row['cantidad'] > 1) {
                                                            echo "<br>(cantidad: " . $row['cantidad'] . ")";
                                                        }
                                                    } ?>
                                                    <?php if ($row['carnet'] != "") {
                                                        if ($row['servicio'] != "") {
                                                            echo "<br>";
                                                        }
                                                        echo strtoupper($row['carnet']);
                                                    }
                                                    if ($row['recarga'] == 1) {
                                                        echo " (Recarga)";
                                                    }
                                                    if ($row['codigo_pack_online'] != "") {
                                                        echo "<br>(Pack-online: " . $row['codigo_pack_online'] . ")";
                                                    }
                                                    ?>
                                                    <?php if ($row['pago_a_cuenta'] == 1) {
                                                        echo "Pago a cuenta";
                                                    } ?>
                                                    <?php if ($row['id_presupuesto'] > 1) {
                                                        echo "Presupuesto " . $row['nro_presupuesto'];
                                                    } ?>
                                                    <?php
                                                    if(isset($row['dientes']) && !empty($row['dientes'])){
                                                        $dent=explode(",",$row['dientes']);
                                                        if(count($dent)) {
                                                            echo " <i>(".( count($dent)>1 ? "Piezas: ": "Pieza: ");
                                                            foreach($dent as $k=>$dd){
                                                                $dent[$k]="#".$dd;
                                                            }
                                                            echo implode(",",$dent);
                                                            echo ") </i>";
                                                        }

                                                    }
                                                    ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?php echo $row['empleado']; ?>
                                                </td>
                                                <td class="text-end">
                                                    <?php if ($row['notas_pago_descuento'] != "") { ?>
                                                    <span style="cursor: pointer; cursor: hand;" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="<?php echo $row['notas_pago_descuento']; ?>">
                                                    <?php } ?>
                                                    <?php if ($row['tipo_pago'] != "#templos") { ?>
                                                        <?php echo number_format($row['importe_total_final'], 2, ',', '.') . "€"; ?>
                                                        <?php if ($row['descuento_euros'] > 0) {
                                                            echo "<br><span class='badge badge-primary' style='font-size: 11px; color: #fff;'>Dto. " . round($row['descuento_euros'], 2) . " €</span>";
                                                        } ?>
                                                        <?php if ($row['descuento_porcentaje'] > 0) {
                                                            echo "<br><span class='lbadge badge-primary' style='font-size: 11px; color: #fff;'>Dto. " . round($row['descuento_porcentaje'], 2) . "%</span>";
                                                        } ?>
                                                        <?php $total_importe += $row['importe_total_final']; ?>
                                                    <?php } else { ?>
                                                        <?php echo "0,00€"; ?>
                                                    <?php } ?>
                                                    <?php if ($row['tipo_pago_saldo'] == "#liquidacion") {
                                                        echo "<br><span class='badge badge-primary' style='font-size: 11px; '>Saldo. " . round($row['importe_saldo'], 2) . " €</span>";
                                                    } ?>
                                                    <?php if ($row['notas_pago_descuento'] != "") { ?>
                                                    </span>
                                                <?php } ?>
                                                </td>
                                                <?php /* <td class="text-end">
                                                <?php if ($row['templos'] > 0 && $row['tipo_pago'] == "#templos") { ?>
                                                    <?php echo $row['templos'];
                                                    $total_templos += $row['templos'];
                                                    if ($row['foto_templo'] != null && $row['foto_templo'] != '') { ?>
                                                        <br>
                                                        <a href="<?php echo base_url() . 'recursos/foto/' . $row['foto_templo']; ?>" data-lightbox="smile"> <img height="42" width="42" src="<?php echo base_url() . 'recursos/foto/' . $row['foto_templo']; ?>"></a>
                                                    <?php } ?>
                                                    <?php $total_templos += $row['templos']; ?>
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                                */ ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?php if ($row['estado'] == "Pagado" || $row['estado'] == "Devuelto") { ?>

                                                        <?php echo $row['estado']; ?>

                                                        <?php $tipo_pago_label = $row['tipo_pago'];
                                                        $tipo_pago_array = explode('#', $row['tipo_pago']);
                                                        foreach ($tipo_pago_array as $tp => $tipop) {
                                                            if ($tipop != '') {
                                                                switch ($tipop) {
                                                                    case 'efectivo':
                                                                        $class = "badge badge-info text-uppercase border-0 d-block mx-auto my-2";
                                                                    case 'tarjeta':
                                                                        $class = "badge badge-success text-uppercase border-0 d-block mx-auto my-2";
                                                                        break;
                                                                    case 'habitacion':
                                                                        $class = "badge badge-primary text-uppercase border-0 d-block mx-auto my-2";
                                                                        break;
                                                                    case 'templos':
                                                                        $class = "badge badge-warning text-uppercase border-0 d-block mx-auto my-2";
                                                                        break;
                                                                    case 'transferencia':
                                                                        $class = "badge btn btn-outline btn-outline-info text-uppercase border-0 d-block mx-auto my-2";
                                                                        break;
                                                                    case 'paypal':
                                                                        $class = "badge badge-secondary text-uppercase border-0 d-block mx-auto my-2";
                                                                        break;
                                                                    case 'tpv2':
                                                                        $class = "badge btn btn-outline btn-outline-primary text-uppercase border-0 d-block mx-auto my-2";
                                                                        break;
                                                                    case 'financiado':
                                                                        $class = "badge badge-primary text-uppercase border-0 d-block mx-auto my-2";
                                                                        break;
                                                                    default:
                                                                        $class = "badge badge-success text-uppercase border-0 d-block mx-auto my-2";
                                                                        break;
                                                                } ?>

                                                                <?php if ($tipop == "efectivo" || $tipop == "tarjeta" || $tipop == "transferencia" || $tipop == "tpv2" || $tipop == "habitacion" || $tipop == "financiado" || $tipop == "paypal") { ?>

                                                                    <button type="button" class="<?= $class ?>" onclick="CambioEfectivoTarjeta(<?php echo $row['id_dietario']; ?>)"><?= $tipop ?></button>
                                                                <?php } else { ?>
                                                                    <?php echo /*$row['estado'] . */ '<br><span class="' . $class . '">' . $tipop  . '</span>';
                                                                } ?>

                                                                <?php if ($tipop == "templos" && isset($row['carnets_pagos'][0]['codigo'])) { ?>
                                                                    <?php foreach ($row['carnets_pagos'] as $dato) { ?> | <button type="button" class="btn btn-sm btn-link" onclick="VerCarnetsPagos(<?php echo $dato['id_carnet'] ?>);"><?php echo strtoupper($dato['codigo']); ?></button>
                                                                    <?php } ?>
                                                                <?php } ?>

                                                                <?php if ($row['estado'] == "Devuelto") { ?>
                                                                    <?php echo "<br>Motivo: " . $row['motivo_devolucion']; ?>
                                                                <?php }
                                                            }
                                                        } ?>
                                                        <?php if ($row['estado'] == "Pagado" && $row['id_pedido'] == 0) { ?>
                                                            <?php if ($row['id_carnet'] > 0 && $row['recarga'] == 0) { ?>
                                                                <a href="#" class="btn btn-sm btn-icon btn-warning" onclick="javascript:DevolucionCarnet('<?php echo $row['id_dietario'] ?>');"><i class="fas fa-trash"></i></a>
                                                            <?php } else { ?>
                                                                <a href="#" class="btn btn-sm btn-icon btn-warning" onclick="javascript:Devolucion('<?php echo $row['id_dietario'] ?>');"><i class="fas fa-trash"></i></a>
                                                            <?php } ?>

                                                            <?php if ($row['pago_a_cuenta'] == 1 && $row['justificante_pagado'] != '') { ?>
                                                                <button type="button" class="btn btn-sm btn-info p-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Ver justificante de pago" data-ver-justificante="<?php echo $row['justificante_pagado']; ?>">Ver justificante <i class="fa-solid fa-file fs-6"></i></button>
                                                            <?php } ?>

                                                        <?php }
                                                    } else { ?>
                                                        <?php if ($row['estado'] == 'Pendiente justificante') { ?>
                                                            <button type="button" class="btn btn-sm btn-warning" data-add-justificante="<?php echo $row['id_dietario']; ?>" data-presupuesto-justificante="<?php echo $row['id_presupuesto']; ?>"><?= $row['estado'] ?></button>
                                                        <?php } else { ?>
                                                            <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6"><?php echo $row['estado']; ?></span>
                                                        <?php } ?>

                                                        <?php if ($row['estado'] == "Anulada" || $row['estado'] == "No vino") { ?>
                                                            <?php //echo "<br>".$row['observaciones'];
                                                            ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php }
                                    }
                                } ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="4" class="fw-bolder text-end">TOTALES</td>
                                    <td style="text-align: right; padding: 8px;">
                                        <?php echo  number_format($total_importe, 2, ",", ".") . '€'; ?></td>
                                    <?php /*<td style="text-align: right; padding: 8px;"><?php echo round($total_templos, 0); ?>
                                </td> */ ?>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>
						</div>
					</div>

					<div class="tab-pane fade" id="tab4default">
						<h3 class="fw-bold fs-2x text-grey-700 border-bottom pb-3">Otros datos</h3>
						<div class="table-responsive">
							<table class="table table-striped table-hover table-bordered">
								<tbody class="text-gray-700 fw-semibold">
									<tr>
										<td>Número de Citas</td>
										<td><?php echo $citas_totales; ?></td>
									</tr>
									<tr>
										<td>Facturación Total</td>
										<td><?php echo number_format($facturacion_total, 2, ',', '.') . " €"; ?></td>
									</tr>
									<tr>
										<td>Facturación Total Antigua</td>
										<td><?php echo number_format($facturacion_total_antigua, 2, ',', '.') . " €"; ?>
										</td>
									</tr>
									<tr>
										<td>Total Descuentos €</td>
										<td><?php echo number_format($descuentos_total, 2, ',', '.') . " €"; ?></td>
									</tr>
									<tr>
										<td>Número Productos Comprados</td>
										<td><?php echo $productos_comprados; ?></td>
									</tr>
									<tr>
										<td>Rentabilidad</td>
										<td><?php echo number_format($rentabilidad, 2, ',', '.') . "%"; ?></td>
									</tr>
									<tr>
										<td>Citas No Vino</td>
										<td><?php echo $citas_no_vino; ?> /
											<?php echo number_format($importe_no_vino, 2, ',', '.') . " €"; ?></td>
									</tr>
									<tr>
										<td>Citas Anuladas</td>
										<td><?php echo $citas_anuladas; ?> /
											<?php echo number_format($importe_anuladas, 2, ',', '.') . " €"; ?></td>
									</tr>
									<tr>
										<td>Frecuencia Visitas Totales</td>
										<td>
											<?php echo number_format($frecuencia_annos, 2, ',', '.') . " veces año"; ?>
											&nbsp;&nbsp;/&nbsp;&nbsp;
											<?php echo number_format($frecuencia_mes, 2, ',', '.') . " veces mes"; ?>
											&nbsp;&nbsp;/&nbsp;&nbsp;
											<?php echo number_format($frecuencia_semana, 2, ',', '.') . " veces semana"; ?>
										</td>
									</tr>
									<tr>
										<td>Visitas por periodos</td>
										<td>
											<?php echo "Último año: " . number_format($frecuencia_ultimo_anno, 0, ',', '.') . " veces"; ?>
											<br>
											<?php echo "Últimos 3 meses: " . number_format($frecuencia_ultimo_3_mes, 0, ',', '.') . " veces"; ?>
											<br>
											<?php echo "Último mes: " . number_format($frecuencia_ultimo_mes, 0, ',', '.') . " veces"; ?>
											<br>
											<?php echo "Última semana: " . number_format($frecuencia_ultimo_semana, 0, ',', '.') . " veces"; ?>
										</td>
									</tr>
									<tr>
										<td>Frecuencia Visitas Históricas</td>
										<td>
											<?php echo number_format($frecuencia_annos_historica, 2, ',', '.') . " veces año"; ?>
											&nbsp;&nbsp;/&nbsp;&nbsp;
											<?php echo number_format($frecuencia_mes_historica, 2, ',', '.') . " veces mes"; ?>
											&nbsp;&nbsp;/&nbsp;&nbsp;
											<?php echo number_format($frecuencia_semana_historica, 2, ',', '.') . " veces semana"; ?>
										</td>
									</tr>
									<tr>
										<td>Empleados Favoritos</td>
										<td>
											<table>
												<?php if (isset($empleados_favoritos)) {
													if ($empleados_favoritos != 0) {
														foreach ($empleados_favoritos as $key => $row) { ?>
															<tr>
																<td style="padding-right: 5px;">
																	<?php echo $row['nombre'] . " " . $row['apellidos']; ?></td>
																<td style="padding-right: 5px;"> | <?php echo $row['veces']; ?> veces
																</td>
															</tr>
												<?php }
													}
												} ?>
											</table>
										</td>
									</tr>
									<tr>
										<td>Carnets Vendidos</td>
										<td>
											<table>
												<?php if (isset($carnets_vendidos)) {
													if ($carnets_vendidos != 0) {
														$suma_total_precio = 0;
														foreach ($carnets_vendidos as $key => $row) { ?>
															<tr>
																<td style="padding-right: 5px;"><?php echo $row['descripcion']; ?></td>
																<td style="padding-right: 5px;"> | <?php echo $row['numero']; ?> veces
																</td>
																<td style="padding-right: 5px;"> | <?php $suma_total_precio += $row['total_precio'];
																									echo number_format($row['total_precio'], 2, ',', '.'); ?> €</td>
															</tr>
												<?php }
													}
												} ?>
												<?php if (isset($carnets_vendidos)) {
													if ($carnets_vendidos != 0) { ?>
														<tr>
															<td style="padding-right: 5px;"></td>
															<td style="padding-right: 5px; text-align: right;">Total: </td>
															<td style="padding-right: 5px; text-align: left;"> |
																<?php echo number_format($suma_total_precio, 2, ',', '.'); ?> €</td>
														</tr>
												<?php }
												} ?>
											</table>
										</td>
									</tr>
									<tr>
										<td>Pago Con Templos</td>
										<td><?php echo number_format($pago_con_templos, 2, ',', '.'); ?> templos</td>
									</tr>
									<tr>
										<td>Valor Unitario del Templo</td>
										<td><?php echo number_format($valor_unitario_templo, 2, ',', '.'); ?> €</td>
									</tr>
									<tr>
										<td>Pago Dinero</td>
										<td><?php echo number_format($facturacion_servicios_productos_recargas_total, 2, ',', '.') . " €"; ?>
										</td>
									</tr>
									<tr>
										<td>% Servicios Pagados con Templos</td>
										<td><?php echo number_format($porcentaje_pagado_templos, 2, ',', '.') . "%"; ?></td>
									</tr>
									<tr>
										<td>Centros Visitados</td>
										<td>
											<table>
												<?php if (isset($centros_visitados)) {
													if ($centros_visitados != 0) {
														foreach ($centros_visitados as $key => $row) { ?>
															<tr>
																<td style="padding-right: 5px;"><?php echo $row['nombre_centro']; ?>
																</td>
																<td style="padding-right: 5px;"> | <?php echo $row['veces']; ?> veces
																</td>
															</tr>
												<?php }
													}
												} ?>
											</table>
										</td>
									</tr>
									<tr>
										<td>Servicios Realizados</td>
										<td>
											<table>
												<?php if (isset($servicios_realizados)) {
													if ($servicios_realizados != 0) {
														foreach ($servicios_realizados as $key => $row) { ?>
															<tr>
																<td style="padding-right: 5px;">
																	<?php echo $row['nombre_familia'] . " - " . $row['nombre_servicio']; ?>
																</td>
																<td style="padding-right: 5px;"> | <?php echo $row['veces']; ?> veces
																</td>
															</tr>
												<?php }
													}
												} ?>
											</table>
										</td>
									</tr>
									<tr>
										<td>Antelación Anulaciones</td>
										<td>
											<table>
												<tr>
													<td>Estado</td>
													<td>Fecha Cita</td>
													<td>Horas antes de la cita</td>
												</tr>
												<?php if (isset($antelacion_anulaciones)) {
													if ($antelacion_anulaciones != 0) {
														foreach ($antelacion_anulaciones as $key => $row) { ?>
															<tr>
																<td style="padding-right: 5px; <?= ($row['horas'] < 3) ? 'color: red;' : '' ?>">
																	<?php echo $row['estado'] . " | "; ?></td>
																<td style="padding-right: 5px; <?= ($row['horas'] < 3) ? 'color: red;' : '' ?>">
																	<?php echo $row['fecha_cita']; ?></td>
																<td style="padding-right: 5px; <?= ($row['horas'] < 3) ? 'color: red;' : '' ?>">
																	| <?php echo number_format($row['horas'], 2, ',', '.'); ?> h.</td>
															</tr>
												<?php }
													}
												} ?>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<div class="tab-pane fade" id="tab5default">
						<h3 class="fw-bold fs-2x text-grey-700 border-bottom pb-3 d-flex justify-content-between">Notas
							citas
							<a href="<?php echo base_url(); ?>clientes/nueva_nota_cita/<?php echo $id_cliente; ?>" class="btn btn-primary text-inverse-primary margin-top-20">Nueva Nota Cita</a>
						</h3>

						<form id="form_notas_citas" action="<?php echo base_url(); ?>clientes/finalizar_notas_citas/<?php echo $id_cliente; ?>" method="post" name="form_notas_citas">
							<div class="table-responsive">
								<table id="logs" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
									<thead class="">
										<tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
											<th style="display: none;"></th>
											<th>Marcar</th>
											<th>Creación - Nota</th>
											<th>Recepcionista</th>
											<th>Estado</th>
											<th>Recep. Finalizó</th>
											<th>Finalización</th>
											<th class="sorting_disabled" style="width: 1%"></th>
											<?php if ($this->session->userdata('id_perfil') == 0) { ?>
												<th class="sorting_disabled" style="width: 1%"></th>
											<?php } ?>
										</tr>
									</thead>
									<tbody class="text-gray-700 fw-semibold">
										<?php if (isset($notas_citas)) {
											if ($notas_citas != 0) {
												foreach ($notas_citas as $key => $row) { ?>
													<tr>
														<td style="display: none;">
															<?php echo $row['fecha_creacion_aaaammdd'] ?>
														</td>
														<td class="text-center">
															<label class="form-check form-check-custom form-check-inline form-check-solid me-5 is-valid">
																<input class="form-check-input" name="citas[]" type="checkbox" value="<?php echo $row['id_nota_cita'] ?>">
															</label>
														</td>
														<td>
															<?php echo $row['fecha_creacion_ddmmaaaa'] ?><br>
															<?php echo $row['nota'] ?>
														</td>
														<td>
															<?php echo $row['usuario_creacion'] ?>
														</td>
														<td>
															<?php echo $row['estado'] ?>
														</td>
														<td class="text-center">
															<?php echo $row['usuario_finalizacion'] ?>
														</td>
														<td class="text-center">
															<?php echo $row['fecha_finalizacion_ddmmaaaa'] ?>
														</td>
														<td class="text-center">
															<a href="<?php echo base_url(); ?>clientes/editar_nota_cita/<?php echo $row['id_nota_cita'] ?>" class="btn btn-sm btn-warning btn-icon"><i class="fa-regular fa-pen-to-square"></i></a>
														</td>
														<?php if ($this->session->userdata('id_perfil') == 0) { ?>
															<td class="text-center">
																<button class="btn btn-sm btn-icon btn-danger" onclick="BorrarNotaCita(<?php echo $row['id_nota_cita'] ?>);"><i class="fa-solid fa-trash"></i></button>
															</td>
														<?php } ?>
													</tr>
										<?php }
											}
										} ?>
									</tbody>
								</table>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-primary text-inverse-primary" type="submit">Finalizar las Notas
										Marcardas</button>
								</div>
							</div>
						</form>
					</div>

					<div class="tab-pane fade" id="tab6default">
						<h3 class="fw-bold fs-2x text-grey-700 border-bottom pb-3 d-flex justify-content-between">Notas
							cobrar
							<a href="<?php echo base_url(); ?>clientes/nueva_nota_cobrar/<?php echo $id_cliente; ?>" class="btn btn-primary text-inverse-primary margin-top-20" />Nueva Nota Cobrar</a>
						</h3>

						<form id="form_notas_cobrar" action="<?php echo base_url(); ?>clientes/finalizar_notas_cobrar/<?php echo $id_cliente; ?>" method="post" name="form_notas_cobrar">
							<div class="table-responsive">
								<table id="logs" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
									<thead class="">
										<tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
											<th style="display: none;"></th>
											<th>Marcar</th>
											<th>Creación - Nota</th>
											<th>Recepcionista</th>
											<th>Estado</th>
											<th>Recep. Finalizó</th>
											<th>Finalización</th>
											<th>Carnet</th>
											<th class="sorting_disabled" style="width: 1%"></th>
											<?php if ($this->session->userdata('id_perfil') == 0) { ?>
												<th class="sorting_disabled" style="width: 1%"></th>
											<?php } ?>
										</tr>
									</thead>
									<tbody class="text-gray-700 fw-semibold">
										<?php if (isset($notas_cobrar)) {
											if ($notas_cobrar != 0) {
												foreach ($notas_cobrar as $key => $row) { ?>
													<tr>
														<td style="display: none;">
															<?php echo $row['fecha_creacion_aaaammdd'] ?>
														</td>
														<td class="text-center">
															<label class="form-check form-check-custom form-check-inline form-check-solid me-5 is-valid">
																<input class="form-check-input" name="cobros[]" type="checkbox" value="<?php echo $row['id_nota_cobrar'] ?>">
															</label>
														</td>
														<td>
															<?php echo $row['fecha_creacion_ddmmaaaa'] ?><br>
															<?php echo $row['nota'] ?>
														</td>
														<td>
															<?php echo $row['usuario_creacion'] ?>
														</td>
														<td>
															<?php echo $row['estado'] ?>
														</td>
														<td class="text-center">
															<?php echo $row['usuario_finalizacion'] ?>
														</td>
														<td class="text-center">
															<?php echo $row['fecha_finalizacion_ddmmaaaa'] ?>
														</td>
														<td class="text-center">
															<?php echo $row['carnet'] ?>
														</td>
														<td class="text-center">
															<a href="<?php echo base_url(); ?>clientes/editar_nota_cobrar/<?php echo $row['id_nota_cobrar'] ?>" class="btn btn-sm btn-warning btn-icon"><i class="fa-regular fa-pen-to-square"></i></a>
														</td>
														<?php if ($this->session->userdata('id_perfil') == 0) { ?>
															<td class="text-center">
																<button class="btn btn-sm btn-icon btn-danger" onclick="BorrarNotaCobrar(<?php echo $row['id_nota_cobrar'] ?>);"><i class="fa-solid fa-trash"></i></button>
															</td>
														<?php } ?>
													</tr>
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
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-primary text-inverse-primary" type="submit">Finalizar las Notas
										Marcardas</button>
								</div>
							</div>
						</form>
					</div>

					<div class="tab-pane fade" id="tab7default">
						<h3 class="fw-bold fs-2x text-grey-700 border-bottom pb-3 d-flex justify-content-between">Saldo
							<span class="fs-3">SALDO ACTUAL: <?php echo number_format($saldo, 2, ',', '.') . "€"; ?></span>
						</h3>
						<div class="table-responsive">
							<table id="saldos" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
								<thead class="">
									<tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
										<th style="display: none;"></th>
										<th>Fecha - Hora</th>
										<th>Importe</th>
										<th>Tipo Pago</th>
										<th>Estado</th>
										<th>Motivo</th>
									</tr>
								</thead>
								<tbody class="text-gray-700 fw-semibold">
									<?php if (isset($lista_saldos)) {
										if ($lista_saldos != 0) {
											foreach ($lista_saldos as $key => $row) { ?>
												<?php if ($row['importe'] >= 0) {
													$color = "#e0ffd4";
												} else {
													$color = "#f8d7dd";
												} ?>
												<tr style="background: <?php echo $color ?>">
													<td style="display: none;">
														<?php echo $row['fecha_creacion_aaaammdd'] ?>
													</td>
													<td style="text-align: center; width: 150px; background: <?php echo $color ?> !important;">
														<?php echo $row['fecha_creacion_ddmmaaaa'] ?>
													</td>
													<td class="text-center">
														<?php echo number_format($row['importe'], 2, ',', '.') . "€"; ?>
													</td>
													<td>
														<?php echo $row['tipo_pago'] ?>
													</td>
													<td>
														<?php if ($row['estado'] == 'Pendiente justificante') { ?>
															<button type="button" class="badge badge-primary text-uppercase border-0 d-block" data-add-justificante="<?php echo $row['id_dietario']; ?>" data-presupuesto-justificante="<?php echo $row['id_presupuesto']; ?>"><?= $row['estado'] ?></button>
														<?php } else {
															echo $row['estado'];
														}
														?>
													</td>
													<td>
														<?php echo $row['motivo'] ?>
														<?= ($row['tipo_pago'] == "#liquidacion") ? $row['empleado'] . "<br> Fecha dietario: " . $row['fecha_hora_concepto'] : '' ?>
													</td>
												</tr>
									<?php }
										}
									} ?>
								</tbody>
							</table>
						</div>
					</div>

					<div class="tab-pane fade" id="tab8default">
						<h3 class="fw-bold fs-2x text-grey-700 border-bottom pb-3 d-flex justify-content-between align-items-start">
							Asociados
							<div class="d-flex flex-row-fluid justify-content-end gap-5 align-items-end mb-5">
								<div class="w-auto ms-3">
									<label for="" class="form-label">Cliente:</label>
									<select name="id_cliente" id="cliente" class="form-select form-select-solid" data-placeholder="Elegir ..."></select>
									<script type="text/javascript">
										$("#cliente").select2({
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
								<div class="w-auto ms-3">
									<button type="button" class="btn btn-primary text-inverse-primary" onclick="anadir_socio(<?php echo $registros[0]['id_cliente']; ?>)">Añadir
										Asociado</button>
								</div>
							</div>
						</h3>

						<form id="form_asociados" action="#" method="post" name="form_asociados">
							<div class="table-responsive">
								<table id="logs" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
									<thead class="">
										<tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
											<th>Cliente</th>
											<th>Acción</th>
										</tr>
									</thead>
									<tbody class="text-gray-700 fw-semibold">
										<?php if (isset($asociados)) {
											if ($asociados != 0) {
												foreach ($asociados as $key => $row) { ?>
													<tr>
														<td>
															<?php echo $row['cliente'] ?><br>
														</td>
														<td class="text-center">
															<?php if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 3) { ?>
																<button class="btn btn-sm btn-icon btn-danger" onclick="BorrarAsociado(<?php echo $row['id_cliente'] ?>,<?php echo $row['id_asociado'] ?>);"><i class="fa-solid fa-trash"></i></button>
															<?php } ?>
														</td>
													</tr>
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
						</form>
					</div>

					<div class="tab-pane fade" id="tab9default">
						<h3 class="fw-bold fs-2x text-grey-700 border-bottom pb-3">Fichas</h3>
						<?php
						$completo = "si";
						if (isset($registros)) { //Datos del Cliente
							if ($registros[0]['nombre'] == "") {
								$nombre = "";
								$completo = "no";
							} else {
								$nombre = $registros[0]['nombre'];
							}
							if ($registros[0]['apellidos'] == "") {
								$apellidos = "";
								$completo = "no";
							} else {
								$apellidos = $registros[0]['apellidos'];
							}
							if ($registros[0]['dni'] == "") {
								$dni = "";
								$completo = "no";
							} else {
								$dni = $registros[0]['dni'];
							}
							if ($registros[0]['fecha_nacimiento_aaaammdd'] == "") {
								$fecha_nacimiento_aaaammdd = "";
								$completo = "no";
							} else {
								$fecha_nacimiento_aaaammdd = $registros[0]['fecha_nacimiento_aaaammdd'];
							}
							if ($registros[0]['telefono'] == "") {
								$telefono = "";
								$completo = "no";
							} else {
								$telefono = $registros[0]['telefono'];
							}
							if ($registros[0]['sexo'] == "") {
								$sexo = "";
								$completo = "no";
							} else {
								$sexo = $registros[0]['sexo'];
							}
							if ($registros[0]['ocupacion'] == "") {
								$ocupacion = "";
								$completo = "no";
							} else {
								$ocupacion = $registros[0]['ocupacion'];
							}
							if ($completo == "no") {
								$xcolor = "red";
								$contenido = "Incompleto";
							} else {
								$xcolor = "#337ab7";
								$contenido = "Completo";
							}
						} ?>
						<div class="table-responsive">
							<table class="table align-middle table-striped table-row-dashed fs-6 gy-5">
								<thead class="">
									<tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
										<th>Sección</th>
										<th>Estado</th>
										<th>Fec. Actualización</th>
										<th>Usuario</th>
										<th>Acción</th>
									</tr>
								</thead>
								<tbody class="text-gray-700 fw-semibold">
									<tr>
										<td><strong>Datos personales</strong></td>
										<td><span style="color:<?php echo $xcolor; ?>; font-weight: bold;"><?php echo $contenido; ?></span>
										</td>

										<td><?php echo $registros[0]['fecha_modificacion_ddmmaaaa']; ?></td>
										<td><?php echo $registros[0]['modificador']; ?></td>
										<td>
											<button class="btn btn-sm btn-warning btn-icon" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_form_ficha_cliente" aria-expanded="false" aria-bs-controls="collapse_form_ficha_cliente"><i class="fa-regular fa-pen-to-square"></i></button>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="collapse" id="collapse_form_ficha_cliente">
							<form id="form" action="<?php echo base_url(); ?>clientes/gestion/actualizar/<?php echo $registros[0]['id_cliente'] ?>" role="form" method="post" name="form" class="border p-3">
								<div class="row mb-5 border-bottom">
									<div class="col-md-4 mb-5">
										<label class="form-label">Nombre *</label>
										<input name="nombre" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['nombre'] : '' ?>" placeholder="" required />
									</div>

									<div class="col-md-4 mb-5">
										<label class="form-label">Apellidos *</label>
										<input name="apellidos" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['apellidos'] : '' ?>" placeholder="" required />
									</div>

									<div class="col-md-4 mb-5">
										<label class="form-label">Email</label>
										<input name="email" class="form-control form-control-solid" type="email" value="<?= (isset($registros)) ? $registros[0]['email'] : '' ?>" placeholder="" />
									</div>

									<div class="col-md-3 mb-5">
										<label class="form-label">Teléfono *</label>
										<input name="telefono" class="form-control form-control-solid" type="number" value="<?= (isset($registros)) ? $registros[0]['telefono'] : '' ?>" placeholder="" required />
									</div>

									<div class="col-md-6 mb-5">
										<label class="form-label">Dirección</label>
										<input name="direccion" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['direccion'] : '' ?>" placeholder="" />
									</div>

									<div class="col-md-3 mb-5">
										<label class="form-label">Código Postal</label>
										<input name="codigo_postal" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['codigo_postal'] : '' ?>" placeholder="" />
									</div>

									<div class="col-md-3 mb-5">
										<label class="form-label">Fecha de Nacimiento *</label>
										<input class="form-control form-control-solid" id="fecha_nacimiento" name="fecha_nacimiento" type="date" value="<?= (isset($registros[0]['fecha_nacimiento_aaaammdd'])) ? $registros[0]['fecha_nacimiento_aaaammdd'] : '' ?>">
									</div>
									<div class="col-md-3 mb-5">
										<label class="form-label">Fecha de Nacimiento *</label>
										<input class="form-control form-control-solid" id="fecha_nacimiento" name="fecha_nacimiento" type="date" value="<?= (isset($registros[0]['fecha_nacimiento_aaaammdd'])) ? $registros[0]['fecha_nacimiento_aaaammdd'] : '' ?>" onblur="calcEdad()">
									</div>

									<!-- 13/11/23 Tutor -->
									<?php
									$displayEstado = "none";
									if (isset($registros)) {
										if ($registros[0]['edad'] < 18)
											$displayEstado = "block";
									}
									?>
									<div class="row" id="divTutor" style="display: <?php echo $displayEstado; ?>; display:flex">
										<div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
											<label class="form-label">Nombre y Apellido del Tutor</label>
											<input class="form-control form-control-solid" id="nombre_tutor" name="nombre_tutor" type="text" value="<?php if (isset($registros[0]['nombre_tutor'])) {
																																						echo $registros[0]['nombre_tutor'];
																																					} ?>">
										</div>
										<div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
											<label class="form-label">D.N.I Tutor</label>
											<input class="form-control form-control-solid" id="dni_tutor" name="dni_tutor" type="text" value="<?php if (isset($registros[0]['dni_tutor'])) {
																																					echo $registros[0]['dni_tutor'];
																																				} ?>">
										</div>
									</div>
									<!-- fin -->


									<div class="col-md-3 mb-5">
										<label class="form-label">D.N.I *</label>
										<input class="form-control form-control-solid" id="dni" name="dni" type="text" value="<?= (isset($registros[0]['dni'])) ? $registros[0]['dni'] : '' ?>" maxlength="9" style="text-transform:uppercase" required="">
									</div>

									<div class="col-md-3 mb-5">
										<label class="form-label">Sexo *</label>
										<select name="sexo" id="sexo" class="form-select form-select-solid">
											<option value="Femenino" <?= ($registros[0]['sexo'] == "Femenino") ? 'selected' : '' ?>>Femenino
											</option>
											<option value="Masculino" <?= ($registros[0]['sexo'] == "Masculino") ? 'selected' : '' ?>>Masculino
											</option>
										</select>
									</div>

									<div class="col-md-3 mb-5">
										<label class="form-label">Ocupación *</label>
										<input class="form-control form-control-solid" id="ocupacion" name="ocupacion" type="text" value="<?= (isset($registros[0]['ocupacion'])) ? $registros[0]['ocupacion'] : '' ?>" required="">
									</div>

									<div class="col-md-12 mb-5">
										<label class="form-label">Notas</label>
										<textarea class="form-control form-control-solid" name="notas"><?= (isset($registros[0]['notas'])) ? $registros[0]['notas'] : '' ?></textarea>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 d-flex justify-content-between">
										<button class="btn btn-secondary text-inverse-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_form_ficha_cliente" aria-expanded="false" aria-bs-controls="collapse_form_ficha_cliente">Cerrar</button>
										<button class="btn btn-primary text-inverse-primary btn-sm" type="submit">Guardar</button>
									</div>
								</div>
							</form>
						</div>
						<!-- Ficha Salud, añadir -->
						<?php if ($completo != "si") { ?>
							<div class="border p-3 mt-5">
								<h4 class="fs-4 fw-bold">Datos de Salud</h4>
								<div class="table-responsive">
									<table class="table align-middle table-striped table-row-dashed fs-6 gy-5">
										<thead class="">
											<tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
												<th>Fec/Creación</th>
												<th>Creador</th>
												<th>Fec/Modificación</th>
												<th>Modificado</th>
												<th></th>
											</tr>
										</thead>
										<tbody class="text-gray-700 fw-semibold">
											<?php if (isset($salud) && $salud != 0) {
												if (count($salud) > 0) {
													foreach ($salud as $ficha) {
														$hoy = Date('Y-m-d');
														$fecha_actual = new DateTime($hoy);
														$fecha_ultima = new DateTime($ficha['fecha_creacion']);
														$diferencia = $fecha_ultima->diff($fecha_actual);
														$endias = $diferencia->days;
											?>
														<tr>
															<td><?php echo date('d/m/Y', strtotime($ficha['fecha_creacion'])); ?></td>
															<td><?php echo $ficha['creador']; ?></td>
															<td><?php echo date('d/m/Y', strtotime($ficha['fecha_modificacion'])); ?></td>
															<td><?php echo $ficha['modificador']; ?></td>
															<td>
																<button class="btn btn-sm btn-icon btn-primary" onclick="Ver_FichaSalud('<?php echo $ficha['id']; ?>','ver')"><i class="fas fa-eye"></i></button>
																<?php if ($endias < 180) { ?>
																	<button class="btn btn-sm btn-warning btn-icon ms-2" onclick="Ver_FichaSalud('<?php echo $ficha['id']; ?>','editar')"><i class="fa-regular fa-pen-to-square"></i></button>
																<?php } else { ?>
																	<button class="btn btn-sm btn-warning btn-icon ms-2" onclick="Ver_FichaSalud('<?php echo $ficha['id']; ?>','clonar')"><i class="fas fa-clone"></i></button>
																<?php } ?>
															</td>
														</tr>
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
								<button type="button" class="btn btn-primary btn-sm text-inverse-primary" data-bs-toggle="collapse" data-bs-target="#collapse_form_datos_salud" aria-expanded="false" aria-controls="collapse_form_datos_salud"><i class="fas fa-plus"></i></button>
								<div class="collapse mt-4" id="collapse_form_datos_salud">
									<form id="form" action="<?php echo base_url(); ?>clientes/salud/nuevo/<?php echo $registros[0]['id_cliente'] ?>" role="form" method="post" name="form2" class="border p-3" onsubmit="return EsOk();">
										<div class="row mb-5 border-bottom">
											<div class="col-md-4">
												<label class="form-label">Enfermedades Pasadas</label>
											</div>
											<div class="col-md-4">
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="pasadas" id="pasadas1" value="no" onclick="elegir('pasadas','no')" checked>
													<label class="form-check-label text-uppercase" for="pasadas1">no</label>
												</div>
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="pasadas" id="pasadas2" value="si" onclick="elegir('pasadas','si')">
													<label class="form-check-label text-uppercase" for="pasadas2">si: Cáncer,
														hepatitis, ...</label>
												</div>
											</div>
											<div class="col-md-4" id="t_pasadas" style="display: none;">
												<label class="form-label">Cuáles, cuándo se curó</label>
												<textarea class="form-control form-control-solid" name="notas_pasadas" id="notas_pasadas"></textarea>
											</div>
										</div>

										<div class="row mb-5 border-bottom">
											<div class="col-md-4">
												<label class="form-label">Enfermedades Actuales</label>
											</div>
											<div class="col-md-4">
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="actuales" id="actuales1" value="no" onclick="elegir('actuales','no')" checked>
													<label class="form-check-label text-uppercase" for="actuales1">no</label>
												</div>
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="actuales" id="actuales2" value="si" onclick="elegir('actuales','si')">
													<label class="form-check-label text-uppercase" for="actuales2">si: Cáncer,
														VIH, hepatitis, autoinmunes, diabetes, tensión, ...</label>
												</div>
											</div>
											<div class="col-md-4" id="t_actuales" style="display: none;">
												<label class="form-label">Desde cuándo</label>
												<textarea class="form-control form-control-solid" name="notas_actuales" id="notas_actuales">  </textarea>
											</div>
										</div>

										<div class="row mb-5 border-bottom">
											<div class="col-md-4">
												<label class="form-label">Toma Medicamentos Actualmente</label>
											</div>
											<div class="col-md-4">
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="medicamentos" id="medicamentos1" value="no" onclick="elegir('medicamentos','no')" checked>
													<label class="form-check-label text-uppercase" for="medicamentos1">no</label>
												</div>
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="medicamentos" id="medicamentos2" value="si" onclick="elegir('medicamentos','si')">
													<label class="form-check-label text-uppercase" for="medicamentos2">si:
														Aspirina, diuréticos, corticoides, antiinflamatorios, antibióticos,
														antidepresivos, ansiolíticos, somníferos,...</label>
												</div>
											</div>
											<div class="col-md-4" id="t_medicamentos" style="display: none;">
												<label class="form-label">Desde cuándo, cantidad</label>
												<textarea class="form-control form-control-solid" name="notas_medicamentos" id="notas_medicamentos">  </textarea>
											</div>
										</div>

										<div class="row mb-5 border-bottom">
											<div class="col-md-4">
												<label class="form-label">Toma Suplementos Actualmente</label>
											</div>
											<div class="col-md-4">
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="suplementos" id="suplementos1" value="no" onclick="elegir('suplementos','no')" checked>
													<label class="form-check-label text-uppercase" for="suplementos1">no</label>
												</div>
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="suplementos" id="suplementos2" value="si" onclick="elegir('suplementos','si')">
													<label class="form-check-label text-uppercase" for="suplementos2">si:
														Vitaminas, diuréticos, homeopatía,...</label>
												</div>
											</div>
											<div class="col-md-4" id="t_suplementos" style="display: none;">
												<label class="form-label">Cuales, frecuencia, cantidad</label>
												<textarea class="form-control form-control-solid" name="notas_suplementos" id="notas_suplementos">  </textarea>
											</div>
										</div>

										<div class="row mb-5 border-bottom">
											<div class="col-md-4">
												<label class="form-label">Intervenciones Quir&uacute;rgicas</label>
											</div>
											<div class="col-md-4">
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="intervenciones" id="intervenciones1" value="no" onclick="elegir('intervenciones','no')" checked>
													<label class="form-check-label text-uppercase" for="intervenciones1">no</label>
												</div>
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="intervenciones" id="intervenciones2" value="si" onclick="elegir('intervenciones','si')">
													<label class="form-check-label text-uppercase" for="intervenciones2">si:
														cirugía general, cirugía estética o plástica de reconstrucción,
														fracturas, esguinces,...</label>
												</div>
											</div>
											<div class="col-md-4" id="t_intervenciones" style="display: none;">
												<label class="form-label">Qué, cuándo dónde</label>
												<textarea class="form-control form-control-solid" name="notas_intervenciones" id="notas_intervenciones">  </textarea>
											</div>
										</div>

										<div class="row mb-5 border-bottom">
											<div class="col-md-4">
												<label class="form-label">Implantes o Dispositivos</label>
											</div>
											<div class="col-md-4">
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="implantes" id="implantes1" value="no" onclick="elegir('implantes','no')" checked>
													<label class="form-check-label text-uppercase" for="implantes1">no</label>
												</div>
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="implantes" id="implantes2" value="si" onclick="elegir('implantes','si')">
													<label class="form-check-label text-uppercase" for="implantes2">si</label>
												</div>
											</div>
											<div class="col-md-4" id="t_implantes" style="display: none;">
												<label class="form-label">Cuál y dónde</label>
												<textarea class="form-control form-control-solid" name="notas_implantes" id="notas_implantes">  </textarea>
											</div>
										</div>

										<div class="row mb-5 border-bottom">
											<div class="col-md-4">
												<label class="form-label">Alergias</label>
											</div>
											<div class="col-md-4">
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="alergias" id="alergias1" value="no" onclick="elegir('alergias','no')" checked>
													<label class="form-check-label text-uppercase" for="alergias1">no</label>
												</div>
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="alergias" id="alergias2" value="si" onclick="elegir('alergias','si')">
													<label class="form-check-label text-uppercase" for="alergias2">si</label>
												</div>
											</div>
											<div class="col-md-4" id="t_alergias" style="display: none;">
												<label class="form-label">Cuales</label>
												<textarea class="form-control form-control-solid" name="notas_alergias" id="notas_alergias">  </textarea>
											</div>
										</div>

										<div class="row mb-5 border-bottom">
											<div class="col-md-4">
												<label class="form-label">Fumador</label>
											</div>
											<div class="col-md-8">
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="fumador" id="fumador1" value="nunca" checked>
													<label class="form-check-label text-uppercase" for="fumador1">Nunca</label>
												</div>
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="fumador" id="fumador2" value="rara vez">
													<label class="form-check-label text-uppercase" for="fumador2">Rara
														vez</label>
												</div>
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="fumador" id="fumador3" value="habitual">
													<label class="form-check-label text-uppercase" for="fumador3">Habitual</label>
												</div>
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="fumador" id="fumador4" value="mucho">
													<label class="form-check-label text-uppercase" for="fumador4">Mucho</label>
												</div>
											</div>
										</div>

										<div class="row mb-5 border-bottom">
											<div class="col-md-4">
												<label class="form-label">Alcohol</label>
											</div>
											<div class="col-md-8">
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="alcohol" id="alcohol1" value="nunca" checked>
													<label class="form-check-label text-uppercase" for="alcohol1">Nunca</label>
												</div>
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="alcohol" id="alcohol2" value="rara vez">
													<label class="form-check-label text-uppercase" for="alcohol2">Rara
														vez</label>
												</div>
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="alcohol" id="alcohol3" value="habitual">
													<label class="form-check-label text-uppercase" for="alcohol3">Habitual</label>
												</div>
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="alcohol" id="alcohol4" value="mucho">
													<label class="form-check-label text-uppercase" for="alcohol4">Mucho</label>
												</div>
											</div>
										</div>

										<div class="row mb-5 border-bottom">
											<div class="col-md-4">
												<label class="form-label">Drogas</label>
											</div>
											<div class="col-md-4">
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="drogas" id="drogas1" value="no" onclick="elegir('drogas','no')" checked>
													<label class="form-check-label text-uppercase" for="drogas1">no</label>
												</div>
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="drogas" id="drogas2" value="si" onclick="elegir('drogas','si')">
													<label class="form-check-label text-uppercase" for="drogas2">si</label>
												</div>
											</div>
											<div class="col-md-4" id="t_drogas" style="display: none;">
												<label class="form-label">Cuáles y con qué frecuencia</label>
												<textarea class="form-control form-control-solid" name="notas_drogas" id="notas_drogas">  </textarea>
											</div>
										</div>

										<div class="row mb-5 border-bottom">
											<div class="col-md-4">
												<label class="form-label">Anticonceptivos</label>
											</div>
											<div class="col-md-4">
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="anticonceptivos" id="anticonceptivos1" value="no" onclick="elegir('anticonceptivos','no')" checked>
													<label class="form-check-label text-uppercase" for="anticonceptivos1">no</label>
												</div>
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="anticonceptivos" id="anticonceptivos2" value="si" onclick="elegir('anticonceptivos','si')">
													<label class="form-check-label text-uppercase" for="anticonceptivos2">si</label>
												</div>
											</div>
											<div class="col-md-4" id="t_anticonceptivos" style="display: none;">
												<label class="form-label">Desde cuándo</label>
												<textarea class="form-control form-control-solid" name="notas_anticonceptivos" id="notas_anticonceptivos"> </textarea>
											</div>
										</div>

										<div class="row mb-5 border-bottom">
											<div class="col-md-4">
												<label class="form-label">Embarazada o cree estarlo</label>
											</div>
											<div class="col-md-4">
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="embarazada" id="embarazada1" value="no" onclick="elegir('embarazada','no')" checked>
													<label class="form-check-label text-uppercase" for="embarazada1">no</label>
												</div>
												<div class="form-check form-check-custom form-check-solid mb-3">
													<input class="form-check-input" type="radio" name="embarazada" id="embarazada2" value="si" onclick="elegir('embarazada','si')">
													<label class="form-check-label text-uppercase" for="embarazada2">si</label>
												</div>
											</div>
											<div class="col-md-4" id="t_embarazada" style="display: none;">
												<label class="form-label">Notas</label>
												<textarea class="form-control form-control-solid" name="notas_embarazada" id="notas_embarazada">  </textarea>
											</div>
										</div>

										<div class="row mb-5 border-bottom">
											<div class="col-md-4">
												<label class="form-label">Firma del cliente</label>
												<div class="firma" style="position: relative; width: 300px; height: 150px; -moz-user-select: none; -webkit-user-select: none; -ms-user-select: none; user-select: none;">
													<canvas id="signature-pad" class="signature-pad" style="position: absolute; left: 0; top: 0; width: 300px; height: 150px; background-color: white;"></canvas>
													<input type="hidden" name="firma_img" id="firma_img" value="" />
												</div>
												<a href="#" id="clear" onclick="signaturePad.clear();return false;" class="btn btn-sm btn-danger">Borrar Firma</a>
											</div>
										</div>

										<div class="row">
											<div class="col-md-12">
												<button class="btn btn-primary text-inverse-primary btn-sm" type="submit">GUARDAR</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						<?php } ?>
						<!-- fin de rellenar datos de salud -->

						<!-- 01/07/21 SubFichas -->
						<div class="border p-3 mt-5">
							<h4 class="fs-4 fw-bold">Sub fichas</h4>
							<div class="d-none">
								<label for="dropdown1">Tratamientos</label>
								<select class="form-select form-select-solid w-auto" id="dropdown1" name="dropdown1">
									<option value="Todos">Todos</option>
									<option value="Tratamiento Facial">Tratamiento Facial</option>
									<option value="Tratamiento Corporal">Tratamiento Corporal </option>
									<option value="Acupuntura">Acupuntura</option>
								</select>
							</div>
							<div class="table-responsive">
								<table id="myTable2" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
									<thead class="">
										<tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
											<th>Fecha</th>
											<th>Empleado</th>
											<th>Tratamientos</th>
											<th>Contenido</th>
											<?php if ($this->session->userdata('id_perfil') == 0) { ?>
												<th></th>
											<?php } ?>
										</tr>
									</thead>
									<tbody class="text-gray-700 fw-semibold">
										<?php if (isset($lista_subficha)) {
											if ($lista_subficha != 0) {
												foreach ($lista_subficha as $key => $row) { ?>
													<tr>
														<td><?php echo $row['fecha_ddmmaaaa']; ?></td>
														<td><?php echo $row['empleado']; ?></td>
														<td><?php echo $row['tratamiento']; ?></td>
														<td><?php echo $row['contenido']; ?></td>
														<?php if ($this->session->userdata('id_perfil') == 0) { ?>
															<td class="text-center">
																<span class="label label-sm label-warning">
																	<a href="#" onclick="EditarSubficha('<?php echo $row['id'] ?>','<?php echo $registros[0]['id_cliente']; ?>');" class="btn btn-sm btn-icon btn-warning"><i class="fa-regular fa-pen-to-square"></i></a>
																</span>
															</td>
														<?php } ?>
													</tr>
										<?php }
											}
										} ?>
									</tbody>
								</table>
							</div>

							<button type="button" class="btn btn-primary btn-sm text-inverse-primary" data-bs-toggle="collapse" data-bs-target="#collapse_form_subficha" aria-expanded="false" aria-controls="collapse_form_subficha"><i class="fas fa-plus"></i></button>
							<!-- Creación de SubFichas -->
							<div class="collapse mt-4" id="collapse_form_subficha">
								<form id="form_sub_ficha" action="<?php echo RUTA_WWW ?>/clientes/subficha/<?php echo $registros[0]['id_cliente'] ?>" role="form" method="post" name="form_sub_ficha">
									<div class="row mb-5 border-bottom">
										<div class="col-md-4">
											<label for="selectFichas">Tratamientos</label>
											<select class="form-select form-select-solid" id="selectFichas" name="selectFichas">
												<option value="Tratamiento Facial">Tratamiento Facial</option>
												<option value="Tratamiento Corporal">Tratamiento Corporal </option>
												<option value="Acupuntura">Acupuntura</option>
											</select>
										</div>
										<div class="col-12">
											<textarea style="width: 100%;" name="nota_ficha" id="editor" class="form-control form-control-solid" rows="6" style="height: 300px;" placeholder="Fichas"></textarea>
											<script type="text/javascript" src="<?= base_url() ?>assets_v5/plugins/custom/tinymce/tinymce.bundle.js">
											</script>
											<script>
												tinymce.init({
													selector: 'textarea#editor',
													language_url: '<?= base_url() ?>assets_v5/plugins/custom/tinymce/langs/es.js',
													language: 'es',
													menubar: false,
												});
											</script>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class="btn btn-primary text-inverse-primary btn-sm" type="submit">GUARDAR</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>

					<div class="tab-pane fade" id="tab10default">
						<h3 class="fw-bold fs-2x text-grey-700 border-bottom pb-3">Consentimientos</h3>
						<?php $datos_faltantes = "Complete estos datos: ";
						$con_faltantes = 0;

                        if ($registros[0]['direccion'] == "") {
							$datos_faltantes .= " Dirección, ";
							$con_faltantes++;
						}
						if ($registros[0]['dni'] == "") {
							$datos_faltantes .= " DNI, ";
							$con_faltantes++;
						}
						if ($registros[0]['codigo_postal'] == "") {
							$datos_faltantes .= " CP, ";
							$con_faltantes++;
						}
						if ($registros[0]['fecha_nacimiento_aaaammdd'] == null) {
							$datos_faltantes .= " Fecha Nacimiento ";
							$con_faltantes++;
						}
						if ($con_faltantes > 0) {
							$hablitado = "disabled";
						} else {
							$hablitado = "";
						} ?>

						<?php if (!isset($aleatorio)&&$this->session->userdata('id_perfil') != 1 ) { ?>
							<button type="button" class="btn btn-primary btn-sm text-inverse-primary" data-bs-toggle="collapse" data-bs-target="#collapse_form_consentimientos" aria-expanded="false" aria-controls="collapse_form_consentimientos"><i class="fas fa-plus"></i></button>
						<?php } ?>

						<div <?= (!isset($aleatorio)) ? ' class="collapse mt-4" id="collapse_form_consentimientos"' : '' ?>>
							<form id="form" action="<?php echo base_url(); ?>clientes/consentimiento/<?php echo $registros[0]['id_cliente'] ?>" role="form" method="post" name="form2" class="border p-3" onsubmit="return EsOk2();">
								<div class="row mb-5 border-bottom">
									<?php if (!isset($aleatorio)) { ?>
										<div class="col-md-4">
											<label class="form-label" for="selectConsentimniento">Tratamientos</label>
											<select class="form-select form-select-solid" data-control="select2" id="selectConsentimniento" name="selectConsentimniento" data-placeholder="Selecciona un tratamiento">
                                                <option value=""></option>
												<option value="1">&Aacute;cido Hialurónico</option>
												<option value="4">&Aacute;cido Pólilactico</option>
												<option value="7">Alineadores EZCLEAR</option>
												<option value="8">Alineadores Genérico</option>
												<option value="2">Bioestimulación Facial </option>
												<option value="13">Blanqueamiento</option>
												<option value="10">Cirugía Elevación SINUSAL</option>
                                                <option value="34">Cirugía Microtornillos</option>
												<option value="9">Cirugía Peirapical SEDO</option>
												<option value="23">Cláusula Informativa</option>
                                                <option value="38">Documento RGPD</option>
												<option value="11">Endodoncia SEDO</option>
                                                <option value="24">Extracción Dental</option>
                                                <option value="25">Implante Dental</option>
												<option value="12">Implantes Cigomáticos</option>
												<option value="14">Implantología</option>
                                                <option value="15">Injertos Conectivos</option>
                                                <option value="16">Injertos Óseos</option>
												<option value="17">Invisalign</option>
												<option value="32">Limpieza Dental</option>
												<option value="5">Mesoterapia</option>
                                                <option value="26">Obturación o Empaste dental</option>
                                                <option value="35">Odontologia General</option>
                                                <option value="27">Odontopedriatia</option>
                                                <option value="18">Ortodoncia</option>
                                                <option value="3">PDO PCL PLL</option>
												<option value="37">Periodoncia</option>
                                                <option value="30">Prótesis Fija</option>
                                                <option value="31">Prótesis Removible</option>
                                                <option value="19">Prótesis Sobre Implante</option>
                                                <option value="22">Pulpar en Diente Inmaduro</option>
                                                <option value="20">Pulpectomía</option>
                                                <option value="33">Pulpotomía</option>
                                                <option value="36">Quad Helix</option>
                                                <option value="21">Reendodoncia</option>
                                                <option value="28">Tratamiento Ortodoncia</option>
                                                <option value="29">Tratamiento Periodontal</option>
                                                <option value="6">Tóxina Botul&iacute;nica</option>
											</select>
										</div>
										<!-- 25/10/23 -->
										<div class="col-md-4">
											<label class="form-label" for="selectDoctores">Doctores</label>
											<select class="form-select form-select-solid" id="selectDoctores" name="selectDoctores">
												<?php
												foreach ($doctores as $row) {
												?>
													<option value="<?php echo $row['id_usuario'] ?>">
														<?php echo $row['nombre'] . " " . $row['apellidos'] ?></option>
												<?php
												}
												?>
											</select>
										</div>
										<!-- Fin -->
									<?php }
									if ($con_faltantes > 0) { ?>
										<label style="color: red;"><?php echo $datos_faltantes; ?></label>
									<?php }
									$boton = "CREAR";
									if (isset($aleatorio)) {
										$boton = "GRABAR";  ?>
										<input type="hidden" name="aleatorio" value="<?php echo $aleatorio; ?>" />
										<input type="hidden" name="tipo_consentimiento" value="<?php echo $tipo_consentimiento; ?>" />
										<input type="hidden" name="id_doctor" value="<?php echo $id_doctor; ?>" />
										<div class="col-12">
											<embed src="<?php echo RUTA_WWW . '/recursos/consentimientos/preConsentimiento_' . $registros[0]['id_cliente'] . '_' . $aleatorio . '.pdf'; ?>" type="application/pdf" width="100%" height="600px" />
										</div>
										<div class="col-md-4">
											<label class="form-label">Firma del cliente</label>
											<div class="firma" style="position: relative; width: 300px; height: 150px; -moz-user-select: none; -webkit-user-select: none; -ms-user-select: none; user-select: none;">
                                                <canvas id="signature-pad2" class="signature-pad" style="position: absolute; left: 0; top: 0; width: 300px; height: 150px; background-color: #cccccc; border-color: black; border-width: 4px; border-style: solid"></canvas>
												<input type="hidden" name="firma_img_consen" id="firma_img_consen" value="" />
											</div>
											<span class="label label-sm label-danger">
												<a href="#" id="clear" onclick="signaturePad.clear();return false;" style="color: #fff; font-weight: bold;">Borrar Firma</a>
											</span>
										</div>
									<?php } ?>
								</div>
								<div class="row">
									<div class="col-md-12">
										<button class="btn btn-primary text-inverse-primary btn-sm" type="submit" <?php echo $hablitado; ?>><?php echo $boton; ?></button>
									</div>
								</div>
							</form>
						</div>
						<!-- Lista de Documentos Frimados -->
						<?php if (isset($lista_consentimientos)) {
							if ($lista_consentimientos != 0) { ?>
								<div>
									<table class="table table-hover">
										<thead>
											<th>Tratamientos</th>
											<th>Fecha</th>
											<th>Doctor</th>
											<th>PDF</th>
											<?php if ($this->session->userdata('id_perfil') == 0) { ?>
												<th>Acción</th>
											<?php } ?>
										</thead>
										<tbody class="text-gray-700 fw-semibold">
											<?php foreach ($lista_consentimientos as $key => $row) {
												$fichero = "Consentimiento_" . $row['id_cliente'] . "_" . $row['aleatorio'] . ".pdf";
												$ruta = RUTA_WWW . "/recursos/consentimientos/" . $fichero;
											?>
												<tr>
													<td><?php echo $row['tratamiento']; ?></td>
													<td><?php echo $row['fecha_ddmmaaaa']; ?></td>
													<td><?php echo $row['nombre'] . " " . $row['apellidos']; ?></td>
													<td><a href="<?php echo $ruta; ?>" target="_blank">PDF</a></td>
													<?php if ($this->session->userdata('id_perfil') == 0) { ?>
														<td class="text-center">
															<button class="btn btn-sm btn-icon btn-danger" onclick="BorrarConsentimiento('<?php echo $row['id'] ?>','<?php echo $registros[0]['id_cliente']; ?>');"><i class="fa-solid fa-trash"></i></button>
														</td>
													<?php } ?>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
						<?php  }
						} ?>
						<!-- Fin Lista -->
					</div>

					<div class="tab-pane fade" id="tab11default">
						<?php unset($param);
						$param['obsoleto'] = 0;
						$servicios = $this->Servicios_model->leer_servicios($param);
						?>
						<h3 class="fw-bold fs-2x text-grey-700 border-bottom pb-3 d-flex justify-content-between">Rellamadas <button type="button" class="btn btn-primary text-inverse-primary" id="nueva_rellamada">Nueva rellamada</button></h3>
						<div class="d-flex w-100 justify-content-end flex-wrap">
							<div class="m-1">
								<label for="" class="form-label">Desde</label>
								<input type="date" id="rellamada_fecha_desde" name="rellamada_fecha_desde" class="form-control form-control-solid w-auto" placeholder="Fecha desde" />
							</div>
							<div class="m-1">
								<label for="" class="form-label">Hasta</label>
								<input type="date" id="rellamada_fecha_hasta" name="rellamada_fecha_hasta" class="form-control form-control-solid w-auto" placeholder="Fecha hasta" />
							</div>
							<div class="m-1">
								<label for="" class="form-label">Fecha</label>
								<input type="date" id="fecha_rellamada" name="fecha_rellamada" class="form-control form-control-solid w-auto" placeholder="Fecha rellamada" />
							</div>
							<div class="m-1">
								<label for="" class="form-label">Estado</label>
								<select name="rellamada_filter_estado" id="rellamada_filter_estado" data-control="select2"  data-placeholder="Estado..." class="form-select form-select-solid w-auto">
									<option value="">Cualquier estado</option>
									<option value="pendiente" selected >Pendiente</option>
									<option value="realizada">Realizada</option>
									<option value="anulada">Anulada</option>
								</select>
							</div>
							<div class="m-1">
								<button type="button" class="btn btn-icon btn-warning mt-9" id="filtersearch" data-bs-toggle="tooltip" title="Aplicar filtros"><i class="fas fa-search"></i></button>
							</div>
						</div>        
						<div class="table-responsive">
							<table id="tabla_rellamadas" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
								<thead class="">
									<tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
										<th>Fecha <i class="fas fa-phone"></i></th>
										<th>Fecha Cita</th>
										<th>Servicio</th>
										<th>Familia</th>
										<th>Empleado</th>
										<th>Centro</th>
										<th>Estado</th>
										<th></th>
									</tr>
								</thead>
								<tbody class="text-gray-700 fw-semibold"></tbody>
							</table>
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

						<div class="modal fade" id="modal-rellamada-nueva" aria-labelledby="modal-rellamada-nuevaLabel" data-bs-focus="false" aria-hidden="true" tabindex="-1">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title w-100 text-center text-uppercase" id="exampleModalLabel">Nueva Rellamada</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<div class="mb-3">
											<label for="">Servicio:</label>
											<select name="nueva_id_servicio" id="nueva_id_servicio" class="form-select form-select-solid" data-control="select2" data-placeholder="Servicio...">
												<option value="">Vacío si no se especifica</option>
												<?php foreach ($servicios as $key => $value) { ?>
													<option value="<?=$value['id_servicio']?>"><?=$value['nombre_servicio']?></option>
												<?php } ?>
											</select>
										</div>

										<div class="mb-3">
											<label for="">Comentarios:</label>
											<textarea name="nueva_comentarios" id="nueva_comentarios_modal" class="form-control" rows="5"></textarea>
										</div>
										<div class="mb-3">
											<label for="">Fecha:</label>
											<input type="date" name="nueva_fecha_rellamada" id="nueva_fecha_rellamada" class="form-control">
										</div>
									</div>
									<div class="modal-footer p-2 justify-content-center">
										<button type="button" class="btn btn-sm btn-secondary text-inverse-secondary" data-bs-dismiss="modal">Cerrar</button>
										<button type="button" class="btn btn-sm btn-primary text-inverse-primary" id="btn_nueva_rellamada">Crear</button>
									</div>
								</div>
							</div>
						</div>
						<script>
							var tabla_rellamadas = $("#tabla_rellamadas").DataTable({
								info: true,
								paging: true,
								ordering: true,
								searching: true,
								stateSave: false,
								processing: true,
								serverSide: true,
								scrollX: true,
								autoWidth: false,
								order: [
									[0, "desc"],
									[1, "desc"]
								],
								pageLength: 50,
								lengthMenu: [
									[10, 25, 50, 100, -1],
									[10, 25, 50, 100, "Todos"],
								],
								columns: [{
										// 0
										name: "fecha_rellamada",
										data: "fecha_rellamada",
										render: function(data, type, row) {
											var html = row.fecha_rellamada;
											return html
										}
									},
									{
										//3
										titlee: "",
										name: "fecha_cita",
										data: "fecha_cita",
										className: "text-nowrap",
										render: function(data, type, row) {
											return row.fecha_cita;
										}
									},
									{
										// 4
										titlee: "",
										name: "nombre_servicio",
										data: "nombre_servicio",
										className: "text-nowrap",
										render: function(data, type, row) {
											var html = row.nombre_servicio
											return html;
										}
									},
									{
										// 5
										titlee: "",
										name: "nombre_familia",
										data: "nombre_familia",
										className: "text-nowrap",
										render: function(data, type, row) {
											var html = row.nombre_familia
											return html;
										}
									},
									{
										//6
										titlee: "",
										name: "empleado",
										data: "empleado",
										render: function(data, type, row) {
											var html = row.empleado;
											return html
										}
									},
									{
										//7
										titlee: "",
										name: "nombre_centro",
										data: "nombre_centro",
										render: function(data, type, row) {
											var html = row.nombre_centro;
											return html
										}
									},
									{
										//8
										titlee: "estado",
										name: "estado",
										data: "estado",
										render: function(date, type, row, meta) {
											var html = row.estado;
											if(row.comentarios != ''){
												html += `<i class="fa-comment fa-comments fa-solid ms-3 rounded-circle text-info" data-bs-toggle="tooltip" title="${row.comentarios}"></i>`;
											}
											return html
										}
									}
									,{
										// 8
										titlee: "",
										name: "",
										data: "",
										render: function(data, type, row) {
											var html = '<div class="btn-group">';
											html += `<button type="button" class="btn btn-sm btn-icon btn-warning" data-edit data-bs-toggle="tooltip" title="Editar Rellamada"><i class="fa-regular fa-pen-to-square"></i></button>`;
											html += `<button type="button" class="btn btn-sm btn-icon btn-info" data-copiar data-bs-toggle="tooltip" title="Agendar rellamada vinculada"><i class="fa-regular fa-calendar"></i></button>`;
											html += `</div>`;
											return html
										}
									},
								],
								columnDefs: [{
										targets: [0, 1, 2, 3, 4, 5, 6, 7],
										visible: true,
									},
									{
										targets: ["_all"],
										visible: false,
									},
									/*{
										targets: [-2],
										orderable: false,
									},
									{
										targets: ['col_id', 'col_validez'],
										className: 'text-center'
									},*/
									{
										targets: ['col_aceptado', 'col_desc', 'col_presu_sin_desc', 'col_presu'],
										className: 'text-end'
									}
								],
								ajax: {
									url: "<?php echo base_url(); ?>Rellamadas/get_rellamadas_cliente",
									type: "GET",
									datatype: "json",
									data: function(data) {
										var id_cliente = <?= $id_cliente ?>;
										var fecha_desde = $('[name="rellamada_fecha_desde"]').val();
										var fecha_hasta = $('[name="rellamada_fecha_hasta"]').val();
										var fecha_rellamada = $('[name="fecha_rellamada"]').val();
										var estado = $('[name="rellamada_filter_estado"]').val();

										data.id_cliente = id_cliente;
										if (fecha_desde != "") {
											data.fecha_desde = fecha_desde;
										}
										if (fecha_hasta != "") {
											data.fecha_hasta = fecha_hasta;
										}
										if (fecha_rellamada != "") {
											data.fecha_rellamada = fecha_rellamada;
										}
										if (estado != "") {
											data.estado = estado;
										}
									},
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
								dom: "<'table-responsive'tr>" +
									"<'row'" +
									"<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'li>" +
									"<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
									">",
								buttons: {
									buttons: [{
										text: "Exportar Excel",
										extend: "excelHtml5",
										title: 'Rellamadas',
										className: "btn btn-warning text-inverse-warning",
										attr: {
											"data-tooltip": "Exportar tabla en excel",
											"data-placement": "auto",
											title: "Exportar tabla en excel",
										},
										exportOptions: {
											columns: ":not(.noexp)",
											orthogonal: "export",
										},
									}, ],
									dom: {
										button: {
											className: "btn",
										},
									},
								},
								headerCallback: function(thead, data, start, end, display) {},
								createdRow: function(row, data, dataIndex) {},
								drawCallback: function(settings) {
									var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
									var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
										return new bootstrap.Tooltip(tooltipTriggerEl, {
											'trigger': 'hover'
										})
									})
								},
								initComplete: function() {
									var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
									var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
										return new bootstrap.Tooltip(tooltipTriggerEl, {
											'trigger': 'hover'
										})
									})
								},
							});
							var buttons = new $.fn.dataTable.Buttons(tabla_rellamadas, {
								buttons: [{
									text: "Excel",
									extend: "excelHtml5",
									title: 'Rellamadas',
									className: "btn btn-warning text-inverse-warning",
									attr: {
										"data-tooltip": "Exportar tabla en excel",
										"data-placement": "auto",
										title: "Exportar tabla en excel",
									},
									exportOptions: {
										columns: ":not(.noexp)",
										orthogonal: "export",
									},
								}, {
									text: "CSV",
									extend: "csvHtml5",
									title: 'Rellamadas',
									className: "btn btn-warning text-inverse-warning",
									attr: {
										"data-tooltip": "Exportar tabla en CSV",
										"data-placement": "auto",
										title: "Exportar tabla en CSV",
									},
									exportOptions: {
										columns: ":not(.noexp)",
										orthogonal: "export",
									},
								}]
							}).container().appendTo($('#buttons'));
							$('[data-table-search]').on('input', function() {
								tabla_rellamadas.search($(this).val()).draw();
							});
							$('#filtersearch').on('click', function() {   
								tabla_rellamadas.draw();
							});;
							function copiarAlPortapapeles(id_elemento) {
								id = "id" + id_elemento;
								var aux = document.createElement("input");
								aux.setAttribute("value", document.getElementById(id).innerHTML);
								document.body.appendChild(aux);
								aux.select();
								document.execCommand("copy");
								document.body.removeChild(aux);
								var copyButton = document.getElementById(id);
								var tooltip = bootstrap.Tooltip.getInstance(copyButton);
								tooltip.setContent({ '.tooltip-inner': '¡Copiado!' });
								tooltip.show();
								setTimeout(function() {
									tooltip.setContent({ '.tooltip-inner': 'Copiar teléfono' });
								}, 2000);
							}
							$(document).on('click', '[data-edit]', function(event) {
								var button = $(this);
								var data = tabla_rellamadas.row(button.parents("tr")).data();
								$('#comentarios_modal').val(data.comentarios)
								$('#estado_modal').val(data.estado)
								$('#id_rellamada_modal').val(data.id_rellamada)
								$('#modal-rellamada').modal('show');
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
													tabla_rellamadas.draw();
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
								var data = tabla_rellamadas.row(button.parents("tr")).data();
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
													tabla_rellamadas.draw();
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
							$(document).on('click', '#nueva_rellamada', function(event) {
								var button = $(this);
								$('#nueva_comentarios_modal').val('')
								$('#nueva_fecha_rellamada').val('')
								$('#modal-rellamada-nueva').modal('show');
							});
							$(document).on('click', '#btn_nueva_rellamada', function(event) {
								var comentarios = $('#nueva_comentarios_modal').val()
								var id_cliente =  <?= $id_cliente ?>;
								var id_servicio = $('#nueva_id_servicio').val()
								var fecha_rellamada = $('#nueva_fecha_rellamada').val()
								if(id_cliente == '' || fecha_rellamada == ''){
									Swal.fire({
									title: 'Indica un cliente y una fecha',
										willClose: function() {
												},
									});
									return;
								}
								var formData = new FormData();
								formData.append("id_cliente", id_cliente);
								formData.append("id_servicio", id_servicio);
								formData.append("comentarios", comentarios);
								formData.append("fecha_rellamada", fecha_rellamada);
								$.ajax({
									headers: {
										'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
									},
									method: 'post',
									url: '<?php echo base_url() ?>Rellamadas/crearRellamada',
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
													tabla_rellamadas.draw();
													$('#modal-rellamada-nueva').modal('hide');
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
							});
							$.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons flex-wrap';
							var oldExportAction = function(self, e, dt, button, config) {
								if (button[0].className.indexOf('buttons-excel') >= 0) {
									if ($.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)) {
										$.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);
									} else {
										$.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
									}
								} else if (button[0].className.indexOf('buttons-print') >= 0) {
									$.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
								}
							};
							var newExportAction = function(e, dt, button, config) {
								var self = this;
								var oldStart = dt.settings()[0]._iDisplayStart;
								dt.one('preXhr', function(e, s, data) {
									data.start = 0;
									data.length = 2147483647;
									dt.one('preDraw', function(e, settings) {
										oldExportAction(self, e, dt, button, config);
										dt.one('preXhr', function(e, s, data) {
											settings._iDisplayStart = oldStart;
											data.start = oldStart;
										});
										setTimeout(dt.ajax.reload, 0);
										return false;
									});
								});
								dt.ajax.reload();
							};
						</script>
					</div>

					<div class="tab-pane fade" id="tab14default">
						<h3 class="fw-bold fs-2x text-grey-700 border-bottom pb-3 d-flex justify-content-between">Notas internas <button type="button" class="btn btn-primary text-inverse-primary margin-top-20" id="nueva-nota-interna">Nueva Nota</button>
						</h3>
						<ul class="list-group">
						<?php if ($notas_internas != 0) {
							foreach ($notas_internas as $key => $nota) { ?>
								<li class="list-group-item d-flex justify-content-between align-items-start p-4 pb-0" data-content="<?= $nota['nota'] ?>" data-fecha="<?=$nota['fecha_nota']?>">
									<div class="ms-2 me-auto">
										<div class="fw-bold"><?= date("d-m-Y H:i",strtotime($nota['fecha_nota']));?> <?= $nota['empleado'];?></div>
										<div class="content"><?= $nota['nota'] ?></div>
									</div>
									<?php if($this->session->userdata('id_usuario') == $nota['id_usuario_creador'] || $this->session->userdata('id_usuario_perfil') == 1){?>
									<div class="btn-group">
										<button type="button" class="btn btn-sm btn-warning btn-icon ms-3" editar_nota_interna="<?=$nota['id_nota_interna']?>"><i class="fa-regular fa-pen-to-square"></i></button>
										<button type="button" class="btn btn-sm btn-icon btn-danger" borrar_nota_interna="<?=$nota['id_nota_interna']?>"><i class="fa-solid fa-trash"></i></button>
									</div>
									<?php } ?>
								</li>
							<?php }
						} else { ?>
							<li class="list-group-item d-flex justify-content-between align-items-start">
								<div class="ms-2 me-auto">
									<div class="fw-bold">No hay notas</div>
								</div>
							</li>
						<?php } ?>
						</ul>
					</div>
				<?php } ?>

			</div>
		</div>
	</div>

	<div id="edit_documento_modal" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-focus-on="input:first">
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="form_edit_comision_modal" id="edit_documento_modal" action="<?php echo base_url(); ?>clientes/edit_documento" method="post">
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
									<?php $tipos =
                                        ['Plan de tratamiento', 'Anamnesis', 'Consentimiento', 'Ficha antigua', 'Panoramica',
                                            'Informe médico', 'Informe medicacion', 'Consentimientos de reconstruccion', 'Consentimiento limpieza',
                                            'DNI', 'Datos bancarios', 'Nomina', 'Pasaporte de implantes', 'Solicitud devolución',
                                            'Fotografías'];
                                	asort($tipos);
									foreach ($tipos as $key => $value) { ?>
										<option value="<?= $value ?>"><?= $value ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
						<button type="submit" class="btn btn-primary">Actualizar Documento</button>
					</div>
					<input type="hidden" name="id_documento" value="" />
					<input type="hidden" name="id_cliente" value="<?= $id_cliente ?>" />
				</form>
			</div>
			<form name="form_delete_documento_modal" id="delete_documento_modal" action="<?php echo base_url(); ?>clientes/edit_documento" method="post">
				<input type="hidden" id="delete_fecha_estudio" name="fecha_estudio" value="" />
				<input type="hidden" id="delete_tipo_doc" name="tipo" value="" />
				<input type="hidden" id="delete_id_documento" name="id_documento" value="" />
				<input type="hidden" id="delete_borrado" name="borrado" value="1" />
				<input type="hidden" id="delete_id_cliente" name="id_cliente" value="<?= $id_cliente ?>" />
			</form>
		</div>
	</div>


	<div class="modal fade" id="modal-presupuesto" tabindex="-1" aria-labelledby="modal-presupuestoLabel" aria-hidden="true" data-focus-on="input:first">
		<div class="modal-dialog modal-lg modal-fullscreen-lg-down">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title text-center text-uppercase w-100" id="exampleModalLabelP">Detalle</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer p-1">
					<button type="button" class="btn btn-sm btn-secondary text-inverse-secondary" data-bs-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-pago" tabindex="-1" aria-labelledby="modal-pagoLabel" aria-hidden="true" data-focus-on="input:first">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form id="form_pagoeuros" action="<?php echo base_url(); ?>Presupuestos/pagoeuros" role="form" method="post" name="form_pagoeuros">
					<div class="modal-header">
						<h5 class="modal-title" id="presupuestoPagoModalLabel"></h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>

					<div class="modal-body">
						<div class="border p-4 mb-5">
							<div class="fs-2 text-center fw-bolder">
								PENDIENTE DE PAGAR:
								<input name="total_importes_marcados" typ="number" value="0" style="text-align: right; width: 80px; border: 0px; background: #fff; font-weight: bold;" disabled /> €
								<span class="mx-5">/</span>
								<span id="faltan" style="color: red;">FALTAN:</span>
								<input id="faltan_importe" name="falta_importe" typ="number" value="0" style="text-align: right; width: 80px; border: 0px; background: #fff; font-weight: bold; color: red;" disabled />
								<span id="faltan_simbolo" style="color: red;">€</span>
							</div>
						</div>

						<div class="row mb-5 align-items-end border-bottom">
							<div class="col mb-5">
								<label for="" class="form-label text-center">Importe<br>Efectivo</label>
								<input name="pagado_efectivo" class="form-control form-control-solid" type="number" step="0.01" min="0" value="0" style="text-align: right;" onchange="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" onkeyup="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" required />
							</div>
							<div class="col mb-5">
								<label for="" class="form-label text-center">Importe<br>Tarjeta</label>
								<input name="pagado_tarjeta" id="pagado_tarjeta" class="form-control form-control-solid" type="number" step="0.01" min="0" value="0" style="text-align: right;" onchange="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" onkeyup="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" required />
								<p id="nota-efectivo" style="display:none; font-weight:bolder"></p>
							</div>
							<div class="col mb-5">
								<label for="" class="form-label text-center">Importe<br>Transferencia</label>
								<input name="pagado_transferencia" id="pagado_transferencia" class="form-control form-control-solid" type="number" step="0.01" min="0" value="0" style="text-align: right;" onchange="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" onkeyup="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" required />
								<p id="nota-transferencia" style="display:none; font-weight:bolder"></p>
							</div>
							<div class="col mb-5">
								<label for="" class="form-label text-center">Importe<br>TPV2</label>
								<input name="pagado_tpv2" id="pagado_tpv2" class="form-control form-control-solid" type="number" step="0.01" min="0" value="0" style="text-align: right;" onchange="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" onkeyup="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" required />
								<p id="nota-tpv2" style="display:none; font-weight:bolder"></p>
							</div>
							<div class="col mb-5">
								<label for="" class="form-label text-center">Importe<br>PayPal</label>
								<input name="pagado_paypal" id="pagado_paypal" class="form-control form-control-solid" type="number" step="0.01" min="0" value="0" style="text-align: right;" onchange="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" onkeyup="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" required />
							</div>
							<div class="col mb-5">
								<label for="" class="form-label text-center">Importe<br>Financiado</label>
								<input name="pagado_financiado" id="pagado_financiado" class="form-control form-control-solid" type="number" step="0.01" min="0" value="0" style="text-align: right;" onchange="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" onkeyup="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" required />
							</div>

							<input name="pagado_habitacion" type="hidden" value="0" />
							<input type="hidden" name="id_presupuesto" id="id_presupuesto" value="">
						</div>

					</div>
					<div class="modal-footer p-2 justify-content-center">
						<button type="button" class="btn btn-sm btn-secondary text-inverse-secondary" data-bs-dismiss="modal">Cerrar</button>
						<button type="submit" class="btn btn-sm btn-primary text-inverse-primary">Registrar
							pago</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-evolutivo" tabindex="-1" aria-labelledby="modal-presupuestoLabel" aria-hidden="true" data-focus-on="input:first">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<form id="form_evolutivo" action="<?php echo base_url(); ?>Clientes/nuevo_evolutivo_popup" method="post" name="form_evolutivo">

					<div class="modal-header">
						<h5 class="modal-title" id="evolutivo-title">Notas</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
                        <?php
                        if ($this->session->userdata('id_perfil')== 6){
                            // CHAINS - Los doctores no pueden seleccionar el escritor
                        }
                        else{
                            ?>

                            <div class="row mb-5 border-bottom  py-4">
                                <div class="col-md-3">
                                    <label for="" class="form-label">Autor:</label>
                                    <select name="nota_escritor" id="nota_escritor" class="form-select form-select-solid">
                                        <?php
                                        foreach($usuarios as $usuario){
                                            ?>
                                            <option value="<?php echo $usuario['id_usuario'];?>"
                                                <?php
                                                if($this->session->userdata('id_usuario')==$usuario['id_usuario']) echo ' selected="selected" ';
                                                ?>
                                            ><?php echo $usuario['nombre'].' '.$usuario['apellidos'];?></option>
                                            <?php
                                        }
                                        ?>
                                        ?>
                                    </select>
                                </div>
                                <?php
                                }
                                ?>
                                    <div class="col-md-3">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="form-label">Fecha nota:</label>
                                        <input type="date" id="fecha_nota" name="fecha_nota" value="" class="form-control form-control-solid w-auto" placeholder="Fecha nota"  />
                                    </div>

						<textarea style="width: 100%;" name="nota_evolutivo" id="nota_evolutivo" class="form-control form-control-solid" style="height: 600px;" placeholder="Nueva nota"></textarea>
						<script>
							tinymce.init({
								selector: 'textarea#nota_evolutivo',
								language_url: '<?= base_url() ?>assets_v5/plugins/custom/tinymce/langs/es.js',
								language: 'es',
								menubar: false,
								height: 500,
							});
						</script>
					</div>
					<div class="modal-footer p-1">
						<input type="hidden" name="id_cliente" value="<?= $id_cliente ?>">
						<input type="hidden" name="id" value="">
						<button type="button" class="btn btn-sm btn-secondary text-inverse-secondary" data-bs-dismiss="modal">Cerrar</button>
						<button type="submit" class="btn btn-sm btn-primary text-inverse-primary">Guardar</button>
					</div>
			</div>
		</div>
	</div>
    </div>
	<div class="modal fade" id="modal-nota-interna" tabindex="-1" aria-labelledby="modal-nota-internaLabel" aria-hidden="true" data-focus-on="input:first">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form id="form_nota_interna" action="<?php echo base_url(); ?>Clientes/nueva_nota_interna" method="post" name="form_nota_interna">
					<div class="modal-header">
						<h5 class="modal-title" id="nota-interna-title">Notas internas</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="row border-bottom  py-4">
							<div class="col-12 mb-5">
								<label for="" class="form-label">Fecha:</label>
								<input type="date" id="fecha_nota_interna" name="fecha_nota_interna" value="" class="form-control form-control-solid w-auto" placeholder="Fecha nota interna"  />
							</div>
							<div class="col-12 mb-5 ">
								<textarea style="width: 100%;" name="nota_interna_content" id="nota_interna_content" class="form-control form-control-solid" style="height: 400px;" placeholder="Nueva nota"></textarea>
								<script>
									tinymce.init({
										selector: 'textarea#nota_interna_content',
										language_url: '<?= base_url() ?>assets_v5/plugins/custom/tinymce/langs/es.js',
										language: 'es',
										menubar: false,
										height: 300,
									});
								</script>
							</div>
						</div>
					</div>
					<div class="modal-footer p-1">
						<input type="hidden" name="id_cliente" value="<?= $id_cliente ?>">
						<input type="hidden" name="id_nota_interna" value="">
						<button type="button" class="btn btn-sm btn-secondary text-inverse-secondary" data-bs-dismiss="modal">Cerrar</button>
						<button type="submit" class="btn btn-sm btn-primary text-inverse-primary">Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>


	<?php if ($this->session->userdata('id_perfil') <> 6 && $this->session->userdata('id_perfil') != 1) {  ?>


		<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>

		<script>
			document.addEventListener("DOMContentLoaded", function() {
				// Obtener la parte final de la URL
				var url = window.location.href;
				var urlParts = url.split("?");
				if (urlParts.length > 1) {
					var lastSegment = urlParts[urlParts.length - 1];
					console.log(lastSegment)
					var container = document.getElementById("ul_tab_nav");
					var primerElemento = container.querySelector('a[href="#' + lastSegment + '"]');
					primerElemento.click()
				}
			});

			$('button[editarRowDocumento]').on('click', function() {
				var tr = $(this).closest('tr');
				var json = tr.data('json');
				console.log(json)
				var modal = $('#edit_documento_modal')
				$('#edit_fecha_estudio').val(json.fecha_estudio);
				$('#edit_tipo_doc').val(json.tipo);
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

			var tabla_presupuestos = $("#tabla_presupuestos").DataTable({
				info: true,
				paging: true,
				ordering: true,
				searching: true,
				stateSave: false,
				processing: true,
				serverSide: true,
				scrollX: true,
				autoWidth: false,
				order: [0, "desc"],
				pageLength: 50,
				lengthMenu: [
					[10, 25, 50, 100, -1],
					[10, 25, 50, 100, "Todos"],
				],
				columns: [{
						//0
						titlee: "",
						name: "id_presupuesto",
						data: "id_presupuesto",
						render: function(data, type, row) {
							var numeroString = row.id_presupuesto.toString();
							var cerosFaltantes = 5 - numeroString.length;
							if (cerosFaltantes > 0) {
								var ceros = '0'.repeat(cerosFaltantes);
								return ceros + numeroString;
							}
							return numeroString;
						}
					},
					{
						//1
						titlee: "",
						name: "cliente",
						data: "cliente",
						render: function(data, type, row) {
							var html = row.cliente;
							return html
						}
					},
					{
						// 2
						titlee: "",
						name: "fecha_creacion",
						data: "fecha_creacion",
						render: function(data, type, row) {
							var html = row.f_creacion;
							return html
						}
					},
					{
						// 3
						titlee: "",
						name: "estado",
						data: "estado",
						render: function(data, type, row) {
							var span = "badge badge-secondary";
							if (row.estado == 'Pendiente') {
								span = "badge badge-warning"
							};
							if (row.estado == 'Aceptado' || row.estado == 'Aceptado parcial') {
								span = "badge badge-success"
							};
							if (row.estado == 'Rechazado') {
								span = "badge badge-danger"
							};
							html = `<span class="${span} text-uppercase">${row.estado}</span>`;
							return html
						}
					},
					{
						// 4
						titlee: "",
						name: "fecha_validez",
						data: "fecha_validez",
						render: function(data, type, row) {
							var html = row.f_validez;
							return html
						}
					},
					{
						//5
						titlee: "Empleado",
						name: "empleado",
						data: "empleado",
						render: function(data, type, row) {
							var html = row.empleado;
							return html
						}
					},
					{
						//6
						titlee: "",
						name: "",
						data: "",
						render: function(data, type, row) {
                   
							var html = '<div class="btn-group">';
							html += `<button type="button" class="btn btn-sm btn-icon btn-info" data-ver data-bs-toggle="tooltip" title="Detalle del presupuesto"><i class="fa fa-eye"></i></button>`;
							/*
							if( row.estado == 'Pendiente' && '0'=='<?php echo $this->session->userdata('id_perfil'); ?>' ){
								html += `<button type="button" class="btn btn-sm btn-icon btn-warning" data-edit data-bs-toggle="tooltip" title="Editar presupuesto"><i class="fa-regular fa-pen-to-square"></i></button>`;
							}
							*/
							if (row.estado == 'Borrador') {
								html += `<button type="button" class="btn btn-sm btn-icon btn-warning" data-edit data-bs-toggle="tooltip" title="Editar presupuesto"><i class="fa-regular fa-pen-to-square"></i></button>`;
							} else {

								html += `<button type="button" class="btn btn-sm btn-icon btn-warning" data-duplicar data-bs-toggle="tooltip" title="Duplicar presupuesto"><i class="fas fa-clone"></i></button>`;

								/*html += `<button type="button" class="btn btn-sm btn-icon btn-warning" data-clonar data-bs-toggle="tooltip" title="Duplicar presupuesto"><i class="fas fa-clone"></i></button>`;*/

								<?php /*if (row.estado == 'Pendiente') {
									html += `<button type="button" class="btn btn-sm btn-icon btn-primary" data-estado data-bs-toggle="tooltip" title="Gestionar estado"><i class="fas fa-exclamation-triangle"></i></button>`;
								} else */ ?>
								
								<?php if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 3) { ?>
									if (row.estado == 'Pendiente') {
										html += `<button type="button" class="btn btn-sm btn-icon btn-primary" data-estado data-bs-toggle="tooltip" title="Gestionar estado"><i class="fas fa-exclamation-triangle"></i></button>`;
									}
								<?php } ?>
								<?php if ($this->session->userdata('id_perfil') == 2) { ?>
									if (row.estado == 'Pendiente' && row.totalpresupuesto <= 600) {
										html += `<button type="button" class="btn btn-sm btn-icon btn-primary" data-estado data-bs-toggle="tooltip" title="Gestionar estado"><i class="fas fa-exclamation-triangle"></i></button>`;
									}
								<?php } ?>
								html += `<button type="button" class="btn btn-sm btn-icon btn-info" data-pdf data-bs-toggle="tooltip" title="Ver presupuesto"><i class="fas fa-file-pdf"></i></button>`;
							}
							html += `</div>`;
							return html
						
						}
					},
					{
						//7
						titlee: "",
						name: "",
						data: "",
						render: function(data, type, row) {
							var html =
								`<button type="button" class="btn btn-sm btn-icon btn-danger" data-del data-bs-toggle="tooltip" title="Eliminar presupuesto"><i class="fa-solid fa-trash"></i></button>`;
							return html
						}
					}
				],
				columnDefs: [{
						targets: [0, 1, 2, 3, 4, 5, 6, 7],
						visible: true,
					},
					{
						targets: ["_all"],
						visible: false,
					},
					{
						targets: [-2],
						orderable: false,
					},
				],
				ajax: {
					url: "<?php echo base_url(); ?>Presupuestos/get_presupuestos",
					type: "GET",
					datatype: "json",
					data: function(data) {
						var id_cliente = <?php echo $id_cliente; ?>;
						var fecha_desde = $('#fecha_desde_presupuestos').val();
						var fecha_hasta = $('#fecha_hasta_presupuestos').val();
						var estado = $('[name="filter_estado"]').val();
						if (id_cliente != "") {
							data.id_cliente = id_cliente;
						}
						if (fecha_desde != "") {
							data.fecha_desde = fecha_desde;
						}
						if (fecha_hasta != "") {
							data.fecha_hasta = fecha_hasta;
						}
						if (estado != "") {
							data.estado = estado;
						}
					},
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
				dom: "<'table-responsive'tr>" +
					"<'row'" +
					"<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'li>" +
					"<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
					">",
				buttons: {
					buttons: [{
						text: "Exportar Excel",
						extend: "excelHtml5",
						title: 'Comisiones',
						className: "btn btn-warning text-inverse-warning",
						attr: {
							"data-tooltip": "Exportar tabla en excel",
							"data-placement": "auto",
							title: "Exportar tabla en excel",
						},
						exportOptions: {
							columns: ":not(.noexp)",
							orthogonal: "export",
						},
					}, ],
					dom: {
						button: {
							className: "btn",
						},
					},
				},
				headerCallback: function(thead, data, start, end, display) {},
				createdRow: function(row, data, dataIndex) {},
				drawCallback: function(settings) {},
				initComplete: function() {
					var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
					var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
						return new bootstrap.Tooltip(tooltipTriggerEl, {
							'trigger': 'hover'
						})
					})
				},
			});

			var buttons = new $.fn.dataTable.Buttons(tabla_presupuestos, {
				buttons: [{
					text: "Exportar Excel",
					extend: "excelHtml5",
					title: 'Comisiones',
					className: "btn btn-warning text-inverse-warning",
					attr: {
						"data-tooltip": "Exportar tabla en excel",
						"data-placement": "auto",
						title: "Exportar tabla en excel",
					},
					exportOptions: {
						columns: ":not(.noexp)",
						orthogonal: "export",
					},
				}, {
					text: "Exportar CSV",
					extend: "csvHtml5",
					title: 'Comisiones',
					className: "btn btn-warning text-inverse-warning",
					attr: {
						"data-tooltip": "Exportar tabla en CSV",
						"data-placement": "auto",
						title: "Exportar tabla en CSV",
					},
					exportOptions: {
						columns: ":not(.noexp)",
						orthogonal: "export",
					},
				}]
			}).container().appendTo($('#buttons'));

			$('[data-table-search]').on('input', function() {
				tabla_presupuestos.search($(this).val()).draw();
			});

			$('#filter_estado').on('change', function() {
				tabla_presupuestos.search($(this).val()).draw();
			});

			$(document).on('click', '[data-pdf]', function(event) {
				var button = $(this);
				var data = tabla_presupuestos.row(button.parents("tr")).data();
				var url = '<?= base_url() ?>presupuestos/ver_pdf/' + data.id_presupuesto;
				//window.location.href = url;  
				var posicion_x;
				var posicion_y;
				var ancho = 600;
				var alto = 450;
				posicion_x = (screen.width / 2) - (ancho / 2);
				posicion_y = (screen.height / 2) - (alto / 2);
				window.open(url, "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" +
					posicion_x + ",width=" + ancho + ",height=" + alto);
			})

			$(document).on('click', '[data-edit]', function(event) {
				var button = $(this);
				var data = tabla_presupuestos.row(button.parents("tr")).data();
				var url = '<?= base_url() ?>presupuestos/editar_presupuesto/' + data.id_presupuesto;
				window.location.href = url;
			});

			$(document).on('click', '[data-clonar]', function(event) {
				var button = $(this);
				var data = tabla_presupuestos.row(button.parents("tr")).data();
				var url = '<?= base_url() ?>presupuestos/duplicar_presupuesto/' + data.id_presupuesto;
				window.location.href = url;
			});

			$(document).on('click', '[data-duplicar]', function(event) {
				if (confirm(
						'Seguro desea duplicar este presupuesto?\n\nSe generará un nuevo presupuesto con los mismos datos y podrá editarlos ..'
					)) {
					var button = $(this);
					var data = tabla_presupuestos.row(button.parents("tr")).data();
					var url = '<?= base_url() ?>presupuestos/duplicar_presupuesto_nuevo/' + data.id_presupuesto;
					window.location.href = url;
				}
			});

			$(document).on('click', '[data-ver]', function(event) {
				var button = $(this);
				var data = tabla_presupuestos.row(button.parents("tr")).data();
				var url = '<?= base_url() ?>presupuestos/ver_detalle/' + data.id_presupuesto;

				$('#modal-presupuesto .modal-title').html('Detalle del presupuesto');
				$('#modal-presupuesto .modal-body').html('<i class="fas fa-sync fa-spin"></i>');
				$('#modal-presupuesto').modal('show');

				$.get(url, function(data) {
					$('#modal-presupuesto .modal-body').html(data);
				});
			});

			$(document).on('click', '[data-estado]', function(event) {
				var button = $(this);
				var data = tabla_presupuestos.row(button.parents("tr")).data();
				var url = '<?= base_url() ?>Presupuestos/gestionar_estado/' + data.id_presupuesto;
				window.location.href = url;
			})

			$(document).on('click', '[data-del]', function() {
				if (confirm("¿DESEA MARCAR COMO BORRADO EL REGISTRO?")) {
					var button = $(this);
					var data = tabla_presupuestos.row(button.parents("tr")).data();
					var url = '<?= base_url() ?>presupuestos/borrar_presupuesto/' + data.id_presupuesto;
					window.location.href = url;
				}
				return false;
			});

			$(document).on('click', '[data-pago]', function(event) {
				var button = $(this);
				var data = tabla_presupuestos.row(button.parents("tr")).data();
				console.log(data);
				$('#presupuestoPagoModalLabel').html('PRESUPUESTO Nº: ' + data.nro_presupuesto);
				$('[name="total_importes_marcados"]').val(data.pendiente)
				$('#faltan_importe').val(data.pendiente);
				$('#id_presupuesto').val(data.id_presupuesto);
				$('#modal-pago').modal('show');
			})

			function ImporteMarcado(total) {

				efectivo = parseFloat(document.form_pagoeuros.pagado_efectivo.value);
				tarjeta = parseFloat(document.form_pagoeuros.pagado_tarjeta.value);
				habitacion = parseFloat(document.form_pagoeuros.pagado_habitacion.value);
				transferencia = parseFloat(document.form_pagoeuros.pagado_transferencia.value);
				tpv2 = parseFloat(document.form_pagoeuros.pagado_tpv2.value);
				financiado = parseFloat(document.form_pagoeuros.pagado_financiado.value);
				r = (total) - parseFloat(efectivo + tarjeta + habitacion + transferencia + tpv2 + financiado).toFixed(2);
				document.form_pagoeuros.falta_importe.value = parseFloat(r).toFixed(2);
				if (r == 0) {
					document.getElementById("faltan").style.color = "green";
					document.getElementById("faltan_simbolo").style.color = "green";
					document.getElementById("faltan_simbolo").style.visibility = "hidden";
					document.getElementById("faltan_importe").style.visibility = "hidden";
					document.getElementById("faltan").innerHTML = "COMPLETO";
				}
				if (r > 0) {
					document.getElementById("faltan").style.color = "red";
					document.getElementById("faltan_simbolo").style.color = "red";
					document.getElementById("faltan_simbolo").style.visibility = "visible";
					document.getElementById("faltan_importe").style.visibility = "visible";
					document.getElementById("faltan").innerHTML = "FALTAN";
				}
				if (r < 0) {
					document.getElementById("faltan").style.color = "red";
					document.getElementById("faltan_simbolo").style.color = "red";
					document.getElementById("faltan_simbolo").style.visibility = "visible";
					document.getElementById("faltan_importe").style.visibility = "visible";
					document.getElementById("faltan").innerHTML = "SOBRAN";
				}
			}

			$.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons flex-wrap';
			var oldExportAction = function(self, e, dt, button, config) {
				if (button[0].className.indexOf('buttons-excel') >= 0) {
					if ($.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)) {
						$.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);
					} else {
						$.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
					}
				} else if (button[0].className.indexOf('buttons-print') >= 0) {
					$.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
				}
			};
			var newExportAction = function(e, dt, button, config) {
				var self = this;
				var oldStart = dt.settings()[0]._iDisplayStart;
				dt.one('preXhr', function(e, s, data) {
					data.start = 0;
					data.length = 2147483647;
					dt.one('preDraw', function(e, settings) {
						oldExportAction(self, e, dt, button, config);
						dt.one('preXhr', function(e, s, data) {
							settings._iDisplayStart = oldStart;
							data.start = oldStart;
						});
						setTimeout(dt.ajax.reload, 0);
						return false;
					});
				});
				dt.ajax.reload();
			};

			function EsOk() {
				if (!signaturePad.isEmpty()) {
					var data = signaturePad.toDataURL('image/png');
					document.getElementById('firma_img').value = data;
					$(document).ready(function() {
						$("#mostrarmodal").modal("show");
					});
				}
				return true;
			}

			function EsOk2() {
                console.log('4');

                if (!signaturePad2.isEmpty()) {
					var data = signaturePad2.toDataURL('image/png');
					document.getElementById('firma_img_consen').value = data;
				}
				return true;
			}

			function BorrarAsociado(id_cliente, id_asociado) {
				document.location.href = "<?php echo base_url(); ?>clientes/borrar_asociado/" + id_cliente + "/" + id_asociado;
				return false;
			}

			function anadir_socio(id_cliente) {
				id_asociado = document.getElementById('cliente').value;
				if (id_asociado == "" || id_asociado == 0 || id_asociado == "0")
					return false;
				else
					document.location.href = "<?php echo base_url(); ?>clientes/nuevo_asociado/" + id_cliente + "/" + id_asociado;
				return false;
			}

			function Exportar(id_cliente) {

				var fecha_desde = $('#fecha_desde').val();
				var fecha_hasta = $('#fecha_hasta').val();
				console.log('Fechas ' + fecha_desde + ' ' + fecha_hasta);
				if (fecha_desde == "" || fecha_hasta == "")
					document.location.href = "<?php echo base_url(); ?>clientes/historial_csv/" + id_cliente;
				else {
					console.log('Fechas ' + fecha_desde + ' ' + fecha_hasta);
					document.location.href = "<?php echo base_url(); ?>clientes/historial_csv/" + id_cliente + "/" + fecha_desde +
						"/" + fecha_hasta;
				}
				return false;
			}

			function Ver_FichaSalud(id_ficha_salud, accion) {
				var posicion_x;
				var posicion_y;
				var ancho = 1000;
				var alto = 800;
				posicion_x = (screen.width / 2) - (ancho / 2);
				posicion_y = (screen.height / 2) - (alto / 2);
				window.open("<?php echo base_url(); ?>clientes/ver_ficha/" + id_ficha_salud + "/" + accion, "_blank",
					"toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho +
					",height=" + alto);
			}

			function Ver_HistorialAntiguo(id_cliente) {
				var posicion_x;
				var posicion_y;
				var ancho = 1000;
				var alto = 800;
				posicion_x = (screen.width / 2) - (ancho / 2);
				posicion_y = (screen.height / 2) - (alto / 2);
				window.open("<?php echo base_url(); ?>clientes/ver_historial_antiguo/" + id_cliente, "_blank",
					"toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho +
					",height=" + alto);
			}

			function DevolucionCarnet(id_dietario) {
				var posicion_x;
				var posicion_y;
				var ancho = 565;
				var alto = 450;
				posicion_x = (screen.width / 2) - (ancho / 2);
				posicion_y = (screen.height / 2) - (alto / 2);
				window.open("<?php echo base_url(); ?>dietario/devolucion_carnet/" + id_dietario, "_blank",
					"toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho +
					",height=" + alto);
			}

			function Devolucion(id_dietario) {
				var posicion_x;
				var posicion_y;
				var ancho = 565;
				var alto = 450;
				posicion_x = (screen.width / 2) - (ancho / 2);
				posicion_y = (screen.height / 2) - (alto / 2);
				window.open("<?php echo base_url(); ?>dietario/devoluciones/index/0/" + id_dietario, "_blank",
					"toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho +
					",height=" + alto);
			}

			function Generarticket(id_cliente, id_centro_facturar) {
				var posicion_x;
				var posicion_y;
				var ancho = 800;
				var alto = 600;
				posicion_x = (screen.width / 2) - (ancho / 2);
				posicion_y = (screen.height / 2) - (alto / 2);
				window.open("<?php echo base_url(); ?>dietario/generarticket/" + id_cliente + "/" + id_centro_facturar, "_blank",
					"toolbar=no,scrollbars=yes,resizable=yes,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho +
					",height=" + alto);
			}

			function Facturacion(id_cliente, id_centro_facturar) {
				var posicion_x;
				var posicion_y;
				var ancho = 800;
				var alto = 600;
				posicion_x = (screen.width / 2) - (ancho / 2);
				posicion_y = (screen.height / 2) - (alto / 2);
				window.open("<?php echo base_url(); ?>dietario/facturacion/" + id_cliente + "/" + id_centro_facturar, "_blank",
					"toolbar=no,scrollbars=yes,resizable=yes,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho +
					",height=" + alto);
			}

			function VerCarnetsPagos(id_carnet) {
				var posicion_x;
				var posicion_y;
				var ancho = 640;
				var alto = 480;
				posicion_x = (screen.width / 2) - (ancho / 2);
				posicion_y = (screen.height / 2) - (alto / 2);
				window.open("<?php echo base_url(); ?>dietario/carnets_pago/ver/" + id_carnet, "_blank",
					"toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho +
					",height=" + alto);
			}

			function BorrarNotaCita(id_nota_cita) {
				if (confirm("¿Desea borrar la nota de cita indicada?")) {
					document.location.href = "<?php echo base_url(); ?>clientes/borrar_nota_cita/" + id_nota_cita;
				}
				return false;
			}

			function BorrarConsentimiento(id_consentimiento, id_cliente) {
				if (confirm("¿Desea borrar este Consentimiento?")) {
					document.location.href = "<?php echo base_url(); ?>clientes/borrar_consentimiento/" + id_consentimiento + "/" +
						id_cliente;
				}
				return false;
			}

			function EditarSubficha(id, id_cliente) {
				var posicion_x;
				var posicion_y;
				var ancho = 800;
				var alto = 480;
				posicion_x = (screen.width / 2) - (ancho / 2);
				posicion_y = (screen.height / 2) - (alto / 2);
				window.open("<?php echo base_url(); ?>clientes/editar_subficha/" + id + "/" + id_cliente, "_blank",
					"toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho +
					",height=" + alto);
			}

			function BorrarNotaCobrar(id_nota_cobrar) {
				if (confirm("¿Desea borrar la nota para cobrar indicada?")) {
					document.location.href = "<?php echo base_url(); ?>clientes/borrar_nota_cobrar/" + id_nota_cobrar;
				}
				return false;
			}

			function elegir(id, respuesta) {
				xtexto = 't_' + id;
				xnotas = "notas_" + id;
				if (respuesta == 'si')
					document.getElementById(xtexto).style.display = "block";
				else {
					document.getElementById(xnotas).value = "";
					document.getElementById(xtexto).style.display = "none";
				}
			}

			$(document).on('click', '.editevol', function() {
				var parent = $(this).closest('.item_evolutivo');
				var content = parent.find('.content_evol').html();
				console.log($(this).attr('idevol'));
				$('#form_evolutivo').find('[name="id"]').val($(this).attr('idevol'));
                $('#form_evolutivo').find('[name="nota_escritor"]').val($(this).attr('idd'));
                <?php
                if($this->session->userdata('id_perfil') == 0){
                ?>
                    $('#form_evolutivo').find('[name="fecha_nota"]').prop('disabled',false);
                <?php
                }
                else
                if ($this->session->userdata('id_perfil') != 6&&$this->session->userdata('id_perfil') != 1){
                ?>
                    if($(this).attr('fnt')=='1'){
                        $('#form_evolutivo').find('[name="fecha_nota"]').prop('disabled',false);
                    }
                    else{
                        $('#form_evolutivo').find('[name="fecha_nota"]').prop('disabled',true);
                    }
                <?php
                }
                else{
                ?>
                    if($(this).attr('fnt')=='1' && $(this).attr('idd')=='<?php echo $this->session->userdata('id_usuario');?>'){
                        $('#form_evolutivo').find('[name="fecha_nota"]').prop('disabled',false);
                    }
                    else{
                        $('#form_evolutivo').find('[name="fecha_nota"]').prop('disabled',true);
                    }
                <?php
                }
                ?>
                console.log($(this).attr('fn'));
                $('#form_evolutivo').find('[name="fecha_nota"]').val( moment($(this).attr('fn')).format('YYYY-MM-DD'));

                var ContentSet = tinymce.get('nota_evolutivo').setContent(content);
				$('#form_evolutivo').attr('action', '<?php echo base_url(); ?>Clientes/editar_evolutivo');
				$('#modal-evolutivo').modal('show')
			});

			$(document).on('click', '.nuevoevol', function() {
				var ContentSet = tinymce.get('nota_evolutivo').setContent('');
				$('#form_evolutivo').find('[name="id"]').val('')
				$('#form_evolutivo').attr('action', '<?php echo base_url(); ?>Clientes/nuevo_evolutivo');
                //$('#form_evolutivo').find('[name="fecha_nota"]').prop('disabled',true);
                $('#form_evolutivo').find('[name="fecha_nota"]').val( moment().format('YYYY-MM-DD'));

                $('#modal-evolutivo').modal('show')
			})
			$(document).on('click', '.delevol', function() {
				if (confirm("¿DESEA MARCAR COMO BORRADO EL REGISTRO?")) {
					var url = '<?= base_url() ?>Clientes/borrar_evolutivo/' + $(this).attr('idevol') + '/' + $(
						'#form_evolutivo').find('[name="id_cliente"]').val();
					window.location.href = url;
				}
				return false;
			})

			$(document).ready(function() {
				$('#saldos').DataTable({
					"order": [
						[0, "desc"]
					],
					"language": {
						"lengthMenu": " _MENU_ registros",
						"zeroRecords": "No se encontraron datos",
						"info": "Mostrar página _PAGE_ de _PAGES_",
						"infoEmpty": "No hay registros disponibles",
						"infoFiltered": "(filtrado sobre un total de _MAX_ registros)",
						"sSearch": "Buscar: "
					},
				});

				$('[data-bs-toggle="tab"]').on('shown.bs.tab', function(event) {
					$('.dataTable').DataTable().columns.adjust().responsive.recalc();
				});

				var table = $('#myTable2').DataTable();
				$('#dropdown1').on('change', function() {
					xbusca = this.value;
					if (xbusca == "Todos")
						xbusca = "";
					table.columns(2).search(xbusca).draw();
				});
			});

			function calcEdad() {
				var fecha = new Date(document.getElementById("fecha_nacimiento").value);
				//calculate month difference from current date in time
				var month_diff = Date.now() - fecha.getTime();

				//convert the calculated difference in date format
				var age_dt = new Date(month_diff);

				//extract year from date    
				var year = age_dt.getUTCFullYear();

				//now calculate the age of the user
				var age = Math.abs(year - 1970);

				//display the calculated age
				//document.write("Age of the date entered: " + age + " years");
				if (age < 18) {
					alert('Edad: ' + age + ' años, rellene los datos del tutor');
					document.getElementById('divTutor').style.display = "block";
					document.getElementById('divTutor').style.display = "flex";
				} else {
					document.getElementById('divTutor').style.display = "none";
				}
			}

			var canvas = document.getElementById('signature-pad');
			var canvas2 = document.getElementById('signature-pad2');
			// Adjust canvas coordinate space taking into account pixel ratio,
			// to make it look crisp on mobile devices.
			// This also causes canvas to be cleared.
			function resizeCanvas() {
				// When zoomed out to less than 100%, for some very strange reason,
				// some browsers report devicePixelRatio as less than 1
				// and only part of the canvas is cleared then.
				var ratio = Math.max(window.devicePixelRatio || 1, 1);
				canvas.width = canvas.offsetWidth * ratio;
				canvas.height = canvas.offsetHeight * ratio;
				canvas.getContext("2d").scale(ratio, ratio);

				var ratio = Math.max(window.devicePixelRatio || 1, 1);
				canvas2.width = canvas2.offsetWidth * ratio;
				canvas2.height = canvas2.offsetHeight * ratio;
				canvas2.getContext("2d").scale(ratio, ratio);
			}
			//window.onresize = resizeCanvas;
			//resizeCanvas();
			var signaturePad = new SignaturePad(canvas, {
				backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
			});
			var signaturePad2 = new SignaturePad(canvas2, {
				backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
			});
		</script>




	<?php } else { ?>

		<script>
			$(document).on('click', '.editevol', function() {
				var parent = $(this).closest('.item_evolutivo');
				var content = parent.find('.content_evol').html();
				console.log($(this).attr('idevol'));
				$('#form_evolutivo').find('[name="id"]').val($(this).attr('idevol'));
                $('#form_evolutivo').find('[name="id"]').val($(this).attr('idevol'));
                $('#form_evolutivo').find('[name="nota_escritor"]').val($(this).attr('idd'));
                <?php
                if($this->session->userdata('id_perfil') == 0){
                ?>
                $('#form_evolutivo').find('[name="fecha_nota"]').prop('disabled',false);
                <?php
                }
                else
                if ($this->session->userdata('id_perfil') != 6&&$this->session->userdata('id_perfil') != 1){
                ?>
                if($(this).attr('fnt')=='1'){
                    $('#form_evolutivo').find('[name="fecha_nota"]').prop('disabled',false);
                }
                else{
                    $('#form_evolutivo').find('[name="fecha_nota"]').prop('disabled',true);
                }
                <?php
                }
                else{
                ?>
                if($(this).attr('fnt')=='1' && $(this).attr('idd')=='<?php echo $this->session->userdata('id_usuario');?>'){
                    $('#form_evolutivo').find('[name="fecha_nota"]').prop('disabled',false);
                }
                else{
                    $('#form_evolutivo').find('[name="fecha_nota"]').prop('disabled',true);
                }
                <?php
                }
                ?>
                console.log($(this).attr('fn'));
                $('#form_evolutivo').find('[name="fecha_nota"]').val( moment($(this).attr('fn')).format('YYYY-MM-DD'));

                var ContentSet = tinymce.get('nota_evolutivo').setContent(content);
				$('#form_evolutivo').attr('action', '<?php echo base_url(); ?>Clientes/editar_evolutivo_popup');
				$('#modal-evolutivo').modal('show')
			});

			$(document).on('click', '.nuevoevol', function() {
				var ContentSet = tinymce.get('nota_evolutivo').setContent('');
				$('#form_evolutivo').find('[name="id"]').val('')
                //$('#form_evolutivo').find('[name="fecha_nota"]').prop('disabled',true);
                $('#form_evolutivo').find('[name="fecha_nota"]').val( moment().format('YYYY-MM-DD'));
				$('#form_evolutivo').attr('action', '<?php echo base_url(); ?>Clientes/nuevo_evolutivo_popup');
				$('#modal-evolutivo').modal('show')
			})

			$(document).on('click', '.delevol', function() {
				if (confirm("¿DESEA MARCAR COMO BORRADO EL REGISTRO?")) {
					var url = '<?= base_url() ?>Clientes/borrar_evolutivo_popup/' + $(this).attr('idevol') + '/' + $(
						'#form_evolutivo').find('[name="id_cliente"]').val();
					window.location.href = url;
				}
				return false;
			})

			var tabla_presupuestos = $("#tabla_presupuestos").DataTable({
				info: true,
				paging: true,
				ordering: true,
				searching: true,
				stateSave: false,
				processing: true,
				serverSide: true,
				scrollX: true,
				autoWidth: false,
				order: [0, "desc"],
				pageLength: 50,
				lengthMenu: [
					[10, 25, 50, 100, -1],
					[10, 25, 50, 100, "Todos"],
				],
				columns: [{
						//0
						titlee: "",
						name: "id_presupuesto",
						data: "id_presupuesto",
						render: function(data, type, row) {
							var numeroString = row.id_presupuesto.toString();
							var cerosFaltantes = 5 - numeroString.length;
							if (cerosFaltantes > 0) {
								var ceros = '0'.repeat(cerosFaltantes);
								return ceros + numeroString;
							}
							return numeroString;
						}
					},
					{
						//1
						titlee: "",
						name: "cliente",
						data: "cliente",
						render: function(data, type, row) {
							var html = row.cliente;
							return html
						}
					},
					{
						// 2
						titlee: "",
						name: "fecha_creacion",
						data: "fecha_creacion",
						render: function(data, type, row) {
							var html = row.f_creacion;
							return html
						}
					},
					{
						// 3
						titlee: "",
						name: "estado",
						data: "estado",
						render: function(data, type, row) {
							var span = "badge badge-secondary";
							if (row.estado == 'Pendiente') {
								span = "badge badge-warning"
							};
							if (row.estado == 'Aceptado' || row.estado == 'Aceptado parcial') {
								span = "badge badge-success"
							};
							if (row.estado == 'Rechazado') {
								span = "badge badge-danger"
							};
							html = `<span class="${span} text-uppercase">${row.estado}</span>`;
							return html
						}
					},
					{
						// 4
						titlee: "",
						name: "fecha_validez",
						data: "fecha_validez",
						render: function(data, type, row) {
							var html = row.f_validez;
							return html
						}
					},
					{
						//5
						titlee: "Empleado",
						name: "empleado",
						data: "empleado",
						render: function(data, type, row) {
							var html = row.empleado;
							return html
						}
					},
					{
						//6
						titlee: "",
						name: "",
						data: "",
						render: function(data, type, row) {
							var html = '';
							html +=
								`<button type="button" class="btn btn-sm btn-icon btn-info" data-ver data-bs-toggle="tooltip" title="Detalle del presupuesto"><i class="fa fa-eye"></i></button>`;
							return html
						}
					}
				],
				columnDefs: [{
						targets: [0, 1, 2, 3, 4, 5, 6],
						visible: true,
					},
					{
						targets: ["_all"],
						visible: false,
					},
					{
						targets: [-1],
						orderable: false,
					},
				],
				ajax: {
					url: "<?php echo base_url(); ?>Presupuestos/get_presupuestos",
					type: "GET",
					datatype: "json",
					data: function(data) {
						var id_cliente = <?php echo $id_cliente; ?>;
						var fecha_desde = $('#fecha_desde_presupuestos').val();
						var fecha_hasta = $('#fecha_hasta_presupuestos').val();
						var estado = $('[name="filter_estado"]').val();
						if (id_cliente != "") {
							data.id_cliente = id_cliente;
						}
						if (fecha_desde != "") {
							data.fecha_desde = fecha_desde;
						}
						if (fecha_hasta != "") {
							data.fecha_hasta = fecha_hasta;
						}
						if (estado != "") {
							data.estado = estado;
						}
					},
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
				dom: "<'table-responsive'tr>" +
					"<'row'" +
					"<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'li>" +
					"<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
					">",

				headerCallback: function(thead, data, start, end, display) {},
				createdRow: function(row, data, dataIndex) {},
				drawCallback: function(settings) {},
				initComplete: function() {
					var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
					var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
						return new bootstrap.Tooltip(tooltipTriggerEl, {
							'trigger': 'hover'
						})
					})
				},
			});



			$('[data-table-search]').on('input', function() {
				tabla_presupuestos.search($(this).val()).draw();
			});

			$('#filter_estado').on('change', function() {
				tabla_presupuestos.search($(this).val()).draw();
			});
			$(document).on('click', '[data-ver]', function(event) {
				var button = $(this);
				var data = tabla_presupuestos.row(button.parents("tr")).data();
				var url = '<?= base_url() ?>presupuestos/ver_detalle/' + data.id_presupuesto;

				$('#modal-presupuesto .modal-title').html('Detalle del presupuesto #' + data.nro_presupuesto);
				$('#modal-presupuesto .modal-body').html('<i class="fas fa-sync fa-spin"></i>');
				$('#modal-presupuesto').modal('show');

				$.get(url, function(data) {
					$('#modal-presupuesto .modal-body').html(data);
				});
			});
		</script>

	<?php } ?>
	<script>

$(document).on('click', '#nueva-nota-interna', function() {
        var ContentSet = tinymce.get('nota_interna_content').setContent('');
        $('#form_nota_interna').find('[name="fecha_nota_interna"]').val( moment().format('YYYY-MM-DD'));
        $('#form_nota_interna').find('[name="id_nota_interna"]').val('');
        $('#form_nota_interna').attr('action', '<?php echo base_url(); ?>Clientes/nueva_nota_interna/1');
        $('#nota-interna-title').html('Nueva nota interna');
        $('#modal-nota-interna').modal('show')
    })

    $(document).on('click', '[editar_nota_interna]', function(){
        var parent = $(this).closest('li');
        var id_nota_interna = $(this).attr('editar_nota_interna')
        var content = parent.attr('data-content');
        var fecha = parent.attr('data-fecha');
        $('#form_nota_interna').find('[name="fecha_nota_interna"]').val(moment(fecha).format('YYYY-MM-DD'));
        $('#form_nota_interna').find('[name="id_nota_interna"]').val(id_nota_interna);
        var ContentSet = tinymce.get('nota_interna_content').setContent(content);
        $('#form_nota_interna').attr('action', '<?php echo base_url(); ?>Clientes/editar_nota_interna/1');
        $('#nota-interna-title').html('Editar nota interna')
        $('#modal-nota-interna').modal('show')
    })
    
    $(document).on('click', '[borrar_nota_interna]', function() {
        if (confirm("¿DESEA MARCAR COMO BORRADO EL REGISTRO?")) {
            var url = '<?= base_url() ?>Clientes/borrar_nota_interna/' + $(this).attr('borrar_nota_interna') + '/<?=$id_cliente?>/1';
            window.location.href = url;
        }
        return false;
    })
<?php
/*
**** RCG 20240627 - Todo esto se carga ahora con la vista /templates/justificane_pago_view

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
});

*/
?>

	$(document).on('click', '[data-ver-justificante]', function() {
		var nuevaPestana = window.open('<?php echo base_url(); ?>' + $(this).attr('data-ver-justificante'), '_blank');
		nuevaPestana.focus();
	})
</script>



        <?php

        $this->load->view($this->config->item('template_dir') . '/justificante_pago_popup');

        ?>

</body>

</html>

<?php /*
				<div class="tab-pane fade" id="tab3default">
					<h3 class="fw-bold fs-2x text-grey-700 border-bottom pb-3">Historial</h3>
					<div class="table-responsive">
						<table id="myTable1" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
							<thead class="">
								<tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
									<th>Carnet</th>
									<th>Tipo</th>
									<th>T. Disponibles</th>
									<th>Centro</th>
									<th>Precio</th>
								</tr>
							</thead>
							<tbody class="text-gray-700 fw-semibold">
								<?php if (isset($carnets)) {
									if ($carnets != 0) {
										foreach ($carnets as $key => $row) { ?>
<tr>
	<td>
		<a href="<?php echo base_url(); ?>carnets/detalle/gestion/<?php echo $row['id_carnet']; ?>"
			data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Ver detalle del Carnet">
			<a onclick="VerCarnetsPagos(<?php echo $row['id_carnet'] ?>);"><b><?php echo $row['codigo'] ?></b></a>
		</a>
	</td>
	<td class="text-center">
		<?php echo $row['tipo']; ?>
		<?php if ($row['codigo_pack_online']) {
														echo "<br>(pack-online: " . $row['codigo_pack_online'] . ")";
													} ?>
	</td>

	<td class="text-center">
		<?php if ($row['id_tipo'] != 99) {
														echo $row['templos_disponibles'];
													} else {
														echo "-";
													} ?>
	</td>
	<td style="text-align: left;">
		<?php echo $row['nombre_centro']; ?>
	</td>
	<td class="text-end">
		<?php if ($row['id_tipo'] != 99) {
														echo number_format($row['precio'], 2, ',', '.') . "€";
													} else {
														echo number_format($row['precio'], 2, ',', '.') . "€";
													} ?>
	</td>
</tr>
<?php }
									}
								} ?>
</tbody>
</table>
</div>
</div>
*/ ?>