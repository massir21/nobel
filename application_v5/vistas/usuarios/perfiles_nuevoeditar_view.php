<div class="card card-flush">
    <div class="card-body pt-6">
        <?php if ($accion == "nuevo") {
            $actionform = base_url() . 'usuarios/perfiles/guardar';
        } else {
            $actionform = base_url() . 'usuarios/perfiles/actualizar/' . $registros[0]['id_perfil'];
        } ?>
        <form id="form" action="<?php echo $actionform; ?>" role="form" method="post" name="form">
            <div class="row mb-5 border-bottom">
                <div class="col-md-6 col-lg-4 col-xxl-3 mb-5">
                    <label class="form-label">Nombre Perfil</label>
                    <input name="nombre_perfil" class="form-control form-control-solid" type="text" value="<?= (isset($registros))? $registros[0]['nombre_perfil'] : ''?>" placeholder="" required />
                </div>
                <div class="col-md-6 col-lg-6 mb-5">
                    <label class="form-label">Módulos</label>
                    <?php if ($modulos != 0) {
                        foreach ($modulos as $key => $row) {
                            $sw = 0;
                            if ($modulos_perfil != 0) {
                                foreach ($modulos_perfil as $key => $modulo) {
                                    if ($row['id_modulo'] == $modulo['id_modulo']) {
                                        $sw = 1;
                                    } 
                                }
                            }?>
                            <div class="form-check form-check-solid form-switch form-check-custom fv-row mb-3 w-auto">
                                <input class="form-check-input w-45px h-30px" type="checkbox" id="id_modulo_<?=$key?>" name="id_modulo[]" value="<?php echo $row['id_modulo']; ?>" <?= ($sw == 1) ? "checked" : '' ?>>
                                <label class="form-check-label" for="id_modulo_<?=$key?>"><?php echo $row['padre'] . " - " . $row['nombre_modulo']; ?></label>
                            </div>
                        <?php }
                    } ?>
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
    