<div class="card card-flush">
    <div class="card-body pt-6">
        <?php if ($accion == "nuevo") {
            $actionform = base_url() . 'servicios/gestion/guardar';
        } else {
            $actionform = base_url() . 'servicios/gestion/actualizar/' . $registros[0]['id_servicio'];
        } ?>
        <form id="form" action="<?php echo $actionform; ?>" role="form" method="post" name="form">
            <div class="row mb-5 border-bottom">
                <div class="col-md-6 mb-5">
                    <label class="form-label">Nombre Servicio</label>
                    <input name="nombre_servicio" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['nombre_servicio'] : '' ?>" placeholder="" required />
                </div>
                <div class="col-md-6 mb-5">
                    <label class="form-label">Familia</label>
                    <select name="id_familia_servicio" class="form-select form-select-solid" required>
                        <option value="">Elegir ....</option>
                        <?php if ($familias_servicios != 0) {
                            foreach ($familias_servicios as $key => $row) { ?>
                                <option value="<?php echo $row['id_familia_servicio'] ?>" <?= (isset($registros) && $registros[0]['id_familia_servicio'] == $row['id_familia_servicio']) ? "selected" : '' ?>><?php echo $row['nombre_familia'] ?></option>
                        <?php }
                        } ?>
                    </select>
                </div>

                <div class="col-md-3 mb-5">
                    <label class="form-label">Abreviatura</label>
                    <input name="abreviatura" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['abreviatura'] : '' ?>" placeholder="" required />
                </div>

                <div class="col-md-3 mb-5">
                    <label class="form-label">Obsoleto</label>
                    <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                        <input class="form-check-input w-45px h-30px" type="checkbox" id="obsoleto" name="obsoleto" value="1" <?= (isset($registros) && $registros[0]['obsoleto'] == 1) ? "checked" : '' ?>>
                    </div>
                </div>

                <div class="col-md-3 mb-5">
                    <label class="form-label">Duración (minutos)</label>
                    <input name="duracion" class="form-control form-control-solid" type="number" value="<?= (isset($registros)) ? $registros[0]['duracion'] : '' ?>" placeholder="" required />
                </div>

                <div class="col-md-3 mb-5">
                    <label class="form-label">Color (#haxdecimal)</label>
                    <input name="color" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['color'] : '' ?>" placeholder="" />
                </div>

                <div class="col-md-3 mb-5">
                    <label class="form-label">P.V.P</label>
                    <input name="pvp" class="form-control form-control-solid" type="number" step="0.01" value="<?= (isset($registros)) ? $registros[0]['pvp'] : '' ?>" placeholder="" required />
                </div>

                <div class="col-md-3 mb-5">
                    <label class="form-label">I.V.A</label>
                    <input name="iva" class="form-control form-control-solid" type="number" step="0.01" value="<?= (isset($registros)) ? $registros[0]['iva'] : '' ?>" placeholder="" required />
                </div>

                <div class="col-md-3 mb-5">
                    <label class="form-label">Precio Proveedor</label>
                    <input name="precio_proveedor" class="form-control form-control-solid" type="number" step="0.01" value="<?= (isset($registros)) ? $registros[0]['precio_proveedor'] : '' ?>" placeholder="" required />
                </div>

                <div class="col-md-3 mb-5">
                    <label class="form-label">Templos</label>
                    <input name="templos" class="form-control form-control-solid" type="number" step="0.01" value="<?= (isset($registros)) ? $registros[0]['templos'] : '' ?>" placeholder="" required />
                </div>

                <div class="col-md-12 mb-5">
                    <label class="form-label">Link a la encuesta del servicio (ficha en tienda online)</label>
                    <input name="link_encuesta" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['link_encuesta'] : '' ?>" placeholder="" />
                </div>

                <div class="col-md-12 mb-5">
                    <label class="form-label">Notas</label>
                    <textarea name="notas" class="form-control form-control-solid"><?= (isset($registros)) ? $registros[0]['notas'] : '' ?></textarea>
                </div>

                <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                    <div class="col-md-12 mb-5">
                        <label class="form-label">IDs Productos de la tienda, separador por comas, ejemplo: 243,567,345
                            <i class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Importante: Los productos de la tienda pueden ser simples o con variaciones, si tienen variación el id que hay que asociar es variation_id, sino product_id."></i></label>

                        <input name="productos_tienda" class="form-control form-control-solid" type="text" value="<?= (isset($productos_tienda)) ? $productos_tienda : '' ?>" placeholder="Ej. 345,234,566">
                    </div>
                <?php } ?>
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
    function Servicios(control, url) {
        var dataString = $("#form").serialize();
        $.ajax({
            type: "POST",
            url: url,
            data: dataString,
            success: function(response) {
                $('option:not(:selected)', control).remove();
                $(control).append(response);
                $(control).trigger("change");
            }
        });
    }
</script>