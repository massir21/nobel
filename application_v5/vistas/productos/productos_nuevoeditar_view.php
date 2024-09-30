<div class="card card-flush">
	<div class="card-body pt-6">
        <?php if ($accion == "nuevo") {
            $actionform = base_url().'productos/gestion/guardar';
        } else { 
            $actionform = base_url().'productos/gestion/actualizar/'.$registros[0]['id_producto'];
        } ?>
        <form id="form" action="<?php echo $actionform; ?>" role="form" method="post" name="form">
            <div class="row mb-5">
                <div class="col-lg-6">
                    <label class="form-label">Nombre Producto</label>
                    <input name="nombre_producto" class="form-control form-control-solid" type="text" value="<?=(isset($registros))?$registros[0]['nombre_producto']:''?>" placeholder="Nombre Producto" required />
                </div>
                <div class="col-lg-6">
                    <label class="form-label">Familia</label>
                    <select name="id_familia_producto" class="form-select form-select-solid" required>
                        <option value="">Elegir ....</option>
                        <?php if ($familias_productos != 0) {
                            foreach ($familias_productos as $key => $row) { ?>
                                <option value="<?php echo $row['id_familia_producto'] ?>" <?php if (isset($registros)) {if ($registros[0]['id_familia_producto'] == $row['id_familia_producto']) {echo "selected";}} ?>>
                                    <?php echo $row['nombre_familia'] ?>
                                </option>
                            <?php }
                        } ?>
                    </select>
                </div>
            </div>
            <div class="row mb-5">    
                <div class="col-md-6 col-lg-4 col-xxl-2">
                    <label class="form-label">Obsoleto</label>
                    <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                        <input class="form-check-input w-45px h-30px" type="checkbox" id="obsoleto" name="obsoleto" value="1" <?php if (isset($registros)) {if ($registros[0]['obsoleto'] == 1) { echo "checked";}} ?>>
                        <label class="form-check-label" for="recordatorio_sms"></label> 
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xxl-2">
                    <label class="form-label">P.V.P</label>
                    <input name="pvp" class="form-control form-control-solid" type="number" value="<?php if (isset($registros)) {echo $registros[0]['pvp'];} ?>" placeholder="P.V.P" step="0.01" required />
                </div>
                <div class="col-md-6 col-lg-4 col-xxl-2">
                    <label class="form-label">I.V.A</label>
                    <input name="iva" class="form-control form-control-solid" type="number" value="<?php if (isset($registros)) {echo $registros[0]['iva'];} ?>" placeholder="I.V.A" step="0.01" required />
                </div>
                <div class="col-md-6 col-lg-4 col-xxl-2">
                    <label class="form-label">Precio Franquicia sin IVA</label>
                    <input name="precio_franquiciado_sin_iva" class="form-control form-control-solid" type="number" value="<?php if (isset($registros)) {echo $registros[0]['precio_franquiciado_sin_iva'];} ?>" placeholder="Precio Franquicia sin IVA" step="0.01" required />
                </div>
                <div class="col-md-6 col-lg-4 col-xxl-2">
                    <label class="form-label">Precio de Compra sin IVA</label>
                    <input name="precio_compra_sin_iva" class="form-control form-control-solid" type="number" value="<?php if (isset($registros)) {echo $registros[0]['precio_compra_sin_iva'];} ?>" placeholder="Precio de Compra sin IVA" step="0.01" required />
                </div>
                <div class="col-md-6 col-lg-4 col-xxl-2">
                    <label class="form-label">Stock (<?php echo $centros[0]['nombre_centro']; ?>)</label>
                    <input name="cantidad_stock" class="form-control form-control-solid" type="number" value="<?php if (isset($registros)) {echo $registros[0]['precio_compra_sin_iva'];} ?>" placeholder="Stock (<?php echo $centros[0]['nombre_centro']; ?>)" step="1" required />
                </div>
            </div>
            <div class="row mb-5 pb-5 border-bottom">
                <div class="col-12">
                    <label class="form-label">Instrucciones</label>
                    <textarea class="form-control form-control-solid" id="instrucciones" name="instrucciones"><?php if (isset($registros[0]['instrucciones'])) { echo $registros[0]['instrucciones'];} ?></textarea>
                    <script type="text/javascript" src="<?=base_url()?>assets_v5/plugins/custom/tinymce/tinymce.bundle.js"></script>
                    <script>
                        tinymce.init({
                            selector: 'textarea#instrucciones',
                            language_url: '<?=base_url()?>assets_v5/plugins/custom/tinymce/langs/es.js',
                            language: 'es',
                            menubar: false,
                        });
                    </script>
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