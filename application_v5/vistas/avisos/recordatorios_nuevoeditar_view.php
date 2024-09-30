<style>
    .dataTables_filter {
        text-align: right;
    }
</style>
<?php if ($accion == "nuevo") { 
    $formaction= base_url().'avisos/guardar_recordatorio';
} else {
    $formaction= base_url().'avisos/actualizar_recordatorio/'.$registros[0]['id_recordatorio'];
} ?>
<div class="card card-flush">
	<div class="card-body pt-6">
        <form id="form" action="<?=$formaction?>" role="form" method="post" name="form">
            <div class="row mb-5 align-items-end">
                <div class="col-md-3">
                    <label for="" class="form-label">Fecha desde</label>
                    <input type="date" id="fecha" name="fecha" value="<?php if (isset($registros)) {echo $registros[0]['fecha_aaaammdd'];} ?>" class="form-control form-control-solid w-auto" placeholder="Fecha" required />
                </div>
                <div class="col-md-3">
                    <label for="" class="form-label">Hora</label>
                    <select class="form-select form-select-solid" name="hora" id="hora" required>
                        <option value="">Elegir...</option>
                        <?php if ($horarios != 0) {
                            foreach ($horarios as $hora) { ?>
                                <option value="<?php echo $hora; ?>" <?php if (isset($registros)) {if ($registros[0]['hora'] == $hora) {echo "selected";}} ?>><?php echo $hora; ?></option>
                        <?php }
                        } ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="" class="form-label">Estado</label>
                    <select class="form-select form-select-solid" name="estado" required>
                        <option value="Pendiente" <?php if (isset($registros)) {if ($registros[0]['estado'] == "Pendiente") {echo "selected";}} ?>>Pendiente</option>
                        <option value="Realizado" <?php if (isset($registros)) {if ($registros[0]['estado'] == "Realizado") {echo "selected";}} ?>>Realizado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="" class="form-label">Repetir</label>
                        <select class="form-select form-select-solid" name="repetir" required>
                        <option value="1" <?php if (isset($registros)) {if ($registros[0]['repetir'] == "1") {echo "selected";}} ?>>No repetir</option>
                        <option value="2" <?php if (isset($registros)) {if ($registros[0]['repetir'] == "2") {echo "selected";}} ?>>Diario</option>
                        <option value="3" <?php if (isset($registros)) {if ($registros[0]['repetir'] == "3") {echo "selected";}} ?>>Semanal</option>
                        <option value="4" <?php if (isset($registros)) {if ($registros[0]['repetir'] == "4") {echo "selected";}} ?>>Mensual</option>
                    </select>
                </div>
            </div>
            <div class="row mb-5 border-bottom">
                <div class="col-12 mb-5">
                    <label for="" class="form-label">Recordatorio</label>
                    <textarea name="recordatorio" id="recordatorio" class="form-control form-control-solid"><?php if (isset($registros)) {echo $registros[0]['recordatorio'];} ?></textarea>
                </div>
                <script type="text/javascript" src="<?=base_url()?>assets_v5/plugins/custom/tinymce/tinymce.bundle.js"></script>
                <script>
                        tinymce.init({
                            selector: 'textarea#recordatorio',
                            language_url: '<?=base_url()?>assets_v5/plugins/custom/tinymce/langs/es.js',
                            language: 'es',
                            menubar: false,
                        });
                </script>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary text-inverse-primary" type="submit">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>