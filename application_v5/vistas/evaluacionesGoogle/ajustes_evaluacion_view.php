<div class="row">
    <!-- Primera tarjeta para el recordatorio de 24 horas -->
    <div class="col-lg-8 ">
        <div class="card card-flush">
            <div class="card-body">
                <h4 class="card-title">Recordatorio 2 horas posterior al pago de la cita</h4>
                <p class="card-text">Recordatorio para la evaluación en google de acuerdo con el centro de atención.  <br>
                Puedes modificar la plantilla de mensaje que se enviará:</p>
                <form id="form" action="<?= base_url() ?>EvaluacionesGoogle/actualizacion_ajustes" role="form" method="post" name="form">
                    <input type="hidden" name="id_frecuenciaEnvio" value="<?= $tipo_recordatorio[0]['id_frecuenciaEnvio'] ?>">

                    <div class="mb-3 form-check d-flex justify-content-end">
                        <input type="checkbox" class="form-check-input" name='esta_activo' id="activarRecordatorios24" <?= $tipo_recordatorio[0]['esta_activo'] ? 'checked' : '' ?>>
                        <label class="form-check-label mx-3" for="activarRecordatorios24" style="font-weight: bold;"><?= $tipo_recordatorio[0]['esta_activo'] ? 'Desactivar recordatorios' : 'Activar Recordatorios' ?></label>
                    </div>
                    <div class="mb-3">
                        <label for="mensajeRecordatoriodias" class="form-label">Días sin repetir el mensaje</label>
                        <input type="text" required class="form-control" id="mensajeRecordatoriodias" name="no_repetir_dias"
                               value="<?= $tipo_recordatorio[0]['no_repetir_dias'];?>" />
                    </div>
                    <div class="mb-3">
                        <label for="mensajeRecordatorio24" class="form-label">Mensaje del recordatorio</label>
                        <textarea required class="form-control" id="mensajeRecordatorio24" rows="10" name="mensaje_personalizado"><?= htmlspecialchars($tipo_recordatorio[0]['mensaje_personalizado']) ?></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn" style="background-color: #ff69b4; color: white;">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>