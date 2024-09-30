<div class="row">
    <!-- Primera tarjeta para el recordatorio de 24 horas -->
    <div class="col-lg-8 ">
        <div class="card card-flush">
            <div class="card-body">
                <h4 class="card-title">Recordatorio cada 24 horas</h4>
                <p class="card-text">Recordatorio de citas enviado 24 horas antes de cada cita. Puedes modificar la plantilla de mensaje que se enviará:</p>
                <form id="form" action="<?= base_url() ?>Notificaciones/actualizacion_ajustes" role="form" method="post" name="form">
                    <input type="hidden" name="id_frecuenciaEnvio" value="<?= $tipo_recordatorio[0]['id_frecuenciaEnvio'] ?>">
                    <div class="mb-3 form-check d-flex justify-content-end">
                        <input type="checkbox" class="form-check-input" name='esta_activo' id="activarRecordatorios24" <?= $tipo_recordatorio[0]['esta_activo'] ? 'checked' : '' ?>>
                        <label class="form-check-label mx-3" for="activarRecordatorios24" style="font-weight: bold;"><?= $tipo_recordatorio[0]['esta_activo'] ? 'Desactivar recordatorios' : 'Activar Recordatorios' ?></label>
                    </div>
                    <div class="mb-3">
                        <label for="mensajeRecordatorio24" class="form-label">Mensaje del recordatorio</label>
                        <textarea required class="form-control" id="mensajeRecordatorio24" rows="3" name="mensaje_personalizado"><?= htmlspecialchars($tipo_recordatorio[0]['mensaje_personalizado']) ?></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn" style="background-color: #ff69b4; color: white;">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4"></div>

    <!-- Segunda tarjeta para el recordatorio después de 6 meses -->
    <div class=" col-lg-8 mt-8">
        <div class="card card-flush">
            <div class="card-body">
                <h4 class="card-title">Recordatorio después de 6 meses</h4>
                <p class="card-text">Recordatorio de citas después de 6 meses de cada cita. Puedes modificar la plantilla de mensaje que se enviará:</p>
                <form id="form" action="<?= base_url() ?>Notificaciones/actualizacion_ajustes" role="form" method="post" name="form">
                    <input type="hidden" name="id_frecuenciaEnvio" value="<?= $tipo_recordatorio[1]['id_frecuenciaEnvio'] ?>">
                    <div class="mb-3 form-check d-flex justify-content-end">
                        <input type="checkbox" class="form-check-input" name='esta_activo' id="activarRecordatorios24" <?= $tipo_recordatorio[1]['esta_activo'] ? 'checked' : '' ?>>
                        <label class="form-check-label mx-3" for="activarRecordatorios24" style="font-weight: bold;"><?= $tipo_recordatorio[1]['esta_activo'] ? 'Desactivar recordatorios' : 'Activar Recordatorios' ?></label>
                    </div>
                    <div class="mb-3">
                        <label for="mensajeRecordatorio24" class="form-label">Mensaje del recordatorio</label>
                        <textarea required class="form-control" id="mensajeRecordatorio24" rows="3" name="mensaje_personalizado"><?= htmlspecialchars($tipo_recordatorio[1]['mensaje_personalizado']) ?></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn" style="background-color: #ff69b4; color: white;">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4"></div>
</div>