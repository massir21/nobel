<div class="card card-flush">
    <div class="card-body pt-6">
        <?php if ($accion == "nuevo") {
            $actionform = base_url() . 'proveedores/gestion/guardar';
        } else {
            $actionform = base_url() . 'proveedores/gestion/actualizar/' . $registros[0]['id_proveedor'];
        } ?>
        <form id="form" action="<?php echo $actionform; ?>" role="form" method="post" name="form">
            <div class="row mb-5 border-bottom">
                <div class="col-md-6 mb-5">
                    <label class="form-label">Nombre proveedor</label>
                    <input name="nombre" class="form-control form-control-solid" type="text"
                           value="<?= (isset($registros) && isset($registros[0])) ? $registros[0]['nombreProveedor'] : '' ?>" placeholder=""
                           required/>
                </div>
                <div class="col-md-6 mb-5">
                    <label class="form-label">Tipo</label>
                    <select name="id_tipo_proveedor" class="form-select form-select-solid" required>
                        <option value="">Elegir ....</option>
                        <?php if ($tipos_proveedores != 0) {
                            foreach ($tipos_proveedores as $key => $row) { ?>
                                <option value="<?php echo $row['id_tipo'] ?>" <?= (isset($registros) && isset($registros[0]) && $registros[0]['id_tipo_proveedor'] == $row['id_tipo']) ? "selected" : '' ?>><?php echo $row['nombre'] ?></option>
                            <?php }
                        } ?>
                    </select>
                </div>


                <div class="col-md-3 mb-5">
                    <label class="form-label">Obsoleto</label>
                    <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                        <input class="form-check-input w-45px h-30px" type="checkbox" id="obsoleto" name="obsoleto"
                               value="1" <?= (isset($registros) && isset($registros[0]) && $registros[0]['obsoleto'] == 1) ? "checked" : '' ?>>
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
<script>

</script>