<div class="card card-flush">
    <div class="card-body pt-6">
        <?php if ($accion == "nuevo") {
            $actionform = base_url() . 'servicios/familias/guardar';
        } else {
            $actionform = base_url() . 'servicios/familias/actualizar/' . $registros[0]['id_familia_servicio'];
        } ?>
        <form id="form" action="<?php echo $actionform; ?>" role="form" method="post" name="form">

            <div class="row mb-5 border-bottom">
                <div class="col-lg-5 mb-5">
                    <label class="form-label">Nombre Familia</label>
                    <input name="nombre_familia" class="form-control form-control-solid" type="text" value="<?=(isset($registros))?$registros[0]['nombre_familia']:''?>" placeholder="Nombre Familia" required />
                </div>
                <div class="col-md-2 mb-5">
                    <label class="form-label">Rellamada (dias)</label>
                    <input name="rellamada" class="form-control form-control-solid" type="number" value="<?= (isset($registros)) ? $registros[0]['rellamada'] : '' ?>" placeholder="" required />
                </div>
                 
                <div class="col-lg-2 mb-5">
                    <label class="form-label">Disponible citas online</label>
                    <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                        <input class="form-check-input w-45px h-30px" type="checkbox" id="citas_online" name="citas_online" value="checked" <?= (isset($registros) && $registros[0]['citas_online'] == 'checked') ? "checked" : '' ?>>
                    </div>
                </div>
                
                <div class="col-lg-2 mb-5">
                    <label class="form-label">Disponible sin presupuesto</label>
                    <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                        <input class="form-check-input w-45px h-30px" type="checkbox" id="disponible_sin_presupuesto" name="disponible_sin_presupuesto" value="checked" <?= ( isset($registros) && $registros[0]['disponible_sin_presupuesto'] ) ? "checked" : '' ?>>
                    </div>
                </div>
                
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary text-inverse-primary" type="submit">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>