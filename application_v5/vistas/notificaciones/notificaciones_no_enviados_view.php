<style>
    .dataTables_filter {
        text-align: right;
    }
</style>
<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">

        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="tablaCitasAvisos_dt">
            </div>
        </div>

        <form id="form" action="<?= base_url() ?>Notificaciones/no_enviados" role="form" method="post" name="form">
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">

                <?php /* if ($this->session->userdata('id_perfil') == 0) { ?>
					<div class="w-auto ms-3">
						<label for="" class="form-label">Centro:</label>
						<select name="id_centro" id="id_centro" class="form-select form-select-solid w-auto" onchange="NuevoDiaFiltroCentro();">
							<option value="">Todos</option>
							<?php if (isset($centros_todos)) {
								if ($centros_todos != 0) {
									foreach ($centros_todos as $key => $row) {
										if ($row['id_centro'] > 1) { ?>
											<option value='<?php echo $row['id_centro']; ?>' <?= (isset($id_centro) && $row['id_centro'] == $id_centro) ? "selected" : '' ?>><?php echo $row['nombre_centro']; ?></option>
							<?php }
									}
								}
							} ?>
						</select>
					</div>
				<?php } else { ?>
					<input type="hidden" name="id_centro" id="id_centro" value="<?php echo $this->session->userdata('id_centro_usuario'); ?>" />
				<?php } */ ?>


                <div class="w-auto ms-3">
                    <label for="" class="form-label">Cliente:</label>
                    <select name="id_cliente" id="id_cliente" class="form-select form-select-solid" data-placeholder="Elegir ..." required data-error="Por favor, selecciona una cliente.">
                        <?php if (isset($cliente) && $cliente[0]['id_cliente'] > 0) { ?>
                            <option value="<?= $cliente[0]['id_cliente'] ?>" selected><?= $cliente[0]['nombre'] . ' ' . $cliente[0]['apellidos'] . ' (' . $cliente[0]['telefono'] . ')'; ?></option>
                        <?php } ?>
                    </select>
                    <script type="text/javascript">
                        $("#id_cliente").select2({
                            language: "es",
                            minimumInputLength: 3,
                            ajax: {
                                delay: 0,
                                url: function(params) {
                                    return '<?php echo RUTA_WWW; ?>/clientes/json/' + params.term;
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

                <div class="w-auto">
                    <label for="" class="form-label">Fecha desde</label>
                    <input type="date" id="fecha_desde" name="fecha_desde" value="<?php if (isset($fecha_desde)) {
                                                                                        echo $fecha_desde;
                                                                                    } ?>" class="form-control form-control-solid w-auto" placeholder="Fecha desde" required />
                </div>

                <div class="w-auto ms-3">
                    <label for="" class="form-label">Fecha hasta</label>
                    <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?php if (isset($fecha_hasta)) {
                                                                                        echo $fecha_hasta;
                                                                                    } ?>" class="form-control form-control-solid w-auto" placeholder="Fecha hasta" required />
                </div>

                <?php /* <div class="w-auto ms-3">
					<label for="" class="form-label">Estado:</label>
					<select name="otroestado" id="otroestado" class="form-select form-select-solid w-auto" onchange="otroestado();">
						<option value="0" <?= ($accion == 0) ? "selected":''?>>Pendientes</option>
						<option value="1" <?=($accion == 1)?"selected":''?>>Enviados</option>
						<option value="" <?=($accion == 3) ? "selected":''?>>Ambos</option>
						<option value="2" <?=($accion == 3) ? "selected":''?>>Obsoletos</option>
					</select>
				</div> */ ?>
                <button type="submit" class="btn btn-info btn-icon text-inverse-info" name="buscar" value="1" id="filterbutton"><i class="fas fa-search"></i></button>
            </div>
        </form>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">
            <table id="tablaCitasAvisos_dt" class="table align-middle table-striped table-row-dashed fs-6 gy-5">

                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th>Fecha y Hora Cita</th>

                        <th>Paciente</th>
                        <th>Nombre Servicio</th>
                        <th>Recordatorio 24 horas</th>
                        <th>Recordatorio 6 meses después</th>
                        <th>Fecha y hora de envio 24 horas</th>
                        <th>Fecha y hora de envio 6 meses</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php if (isset($citas) && $citas != 0) {
                        foreach ($citas as $row) {
                            $fecha_hora_inicio = strtotime($row['fecha_hora_inicio']);

                            $recordatorio24Horas = 'Sin Programación';
                            $recordatorio6Meses = 'Sin Programación';
                            $fechaEnvio24Horas = !empty($row['fecha_hora_envio']) ? date('Y-m-d H:i', strtotime($row['fecha_hora_envio'])) : 'Sin Fecha';
                            $fechaEnvio6Meses = !empty($row['fecha_hora_envio_6meses']) ? date('Y-m-d H:i', strtotime($row['fecha_hora_envio_6meses'])) : 'Sin Fecha';

                            foreach ($tipo_recordatorio as $recordatorio) {
                                if ($recordatorio['esta_activo']) {
                                    // Recordatorio de 24 horas antes
                                    if ($recordatorio['id_frecuenciaEnvio'] == 1) {
                                        $recordatorio24Horas = $row['estatus'] . "\n" . $row['motivo_fallido'];
                                    }

                                    // Recordatorio de 6 meses después
                                    if ($recordatorio['id_frecuenciaEnvio'] == 2) {
                                        $recordatorio6Meses = $row['estatus6meses'] . "\n" . $row['motivo_fallido_6meses'];
                                    }
                                }
                            }
                    ?>
                            <tr>
                                <td><?= date('Y-m-d H:i', $fecha_hora_inicio) ?></td>
                                <td><?= htmlspecialchars($row['nombre']) . ' ' . htmlspecialchars($row['apellidos']) ?></td>
                                <td><?= htmlspecialchars($row['nombre_servicio']) ?></td>
                                <td><?= nl2br(htmlspecialchars($recordatorio24Horas)) ?></td>
                                <td><?= nl2br(htmlspecialchars($recordatorio6Meses)) ?></td>
                                <td><?= htmlspecialchars($fechaEnvio24Horas) ?></td>
                                <td><?= htmlspecialchars($fechaEnvio6Meses) ?></td>
                            </tr>
                    <?php
                        }
                    } ?>
                </tbody>

            </table>
        </div>
    </div>
</div>