<div class="card card-flush">
    <div class="card-body pt-6">
        <?php if ($accion == "nuevo") {
            $actionform = base_url() . 'descuentos/gestion/guardar';
        } else {
            $actionform = base_url() . 'descuentos/gestion/actualizar/' . $registros[0]['id_descuento'];
        } ?>
        <form id="form" action="<?php echo $actionform; ?>" role="form" method="post" name="form">
            <div class="row mb-5">
                <div class="col-md-6 col-lg-3 mb-5">
                    <label class="form-label">Pago total</label>
                    <select name="tipo_pago" class="form-select form-select-solid" required>
                        <option value="">Elegir ....</option>
                        <option value="mayor" <?=(isset($registros) && $registros[0]['tipo_pago'] == "mayor") ? "selected":''?>>Mayor ></option>
                        <option value="menor" <?=(isset($registros) && $registros[0]['tipo_pago'] == "menor") ? "selected":''?>>Menor <</option>
                    </select>
                </div>
                <div class="col-md-6 col-lg-3 mb-5">
                    <label class="form-label">Cantidad</label>
                    <input name="pago_total" class="form-control form-control-solid" type="number" step="0.01" value="<?=(isset($registros))?$registros[0]['pago_total']:''?>" placeholder="" required />
                </div>
                <div class="col-md-6 col-lg-3 mb-5">
                    <label class="form-label">Descuento €</label>
                    <input name="descuento_euros" class="form-control form-control-solid" type="number" step="0.01" value="<?=(isset($registros))?$registros[0]['descuento_euros']:''?>" onkeypress="DescuentoEuro();" placeholder="" required />
                </div>
                <div class="col-md-6 col-lg-3 mb-5">
                    <label class="form-label">Descuento %</label>
                    <input name="descuento_porcentaje" class="form-control form-control-solid" type="number" step="0.01" value="<?=(isset($registros))?$registros[0]['descuento_porcentaje']:''?>" onkeypress="DescuentoPorcentaje();" placeholder="" required />
                </div>
            </div>
            <div class="row mb-5 border-bottom">
                <div class="col-md-6 mb-5">
                    <label class="form-label">Familia Servicio</label>
                    <select name="id_familia_servicio" id="id_familia_servicio" class="form-select form-select-solid" onchange="Servicios();">
                        <option value="0">Elegir ....</option>
                        <?php if ($familias_servicios != 0) {
                            foreach ($familias_servicios as $key => $row) { ?>
                                <option value="<?php echo $row['id_familia_servicio'] ?>" <?=(isset($registros) && $registros[0]['id_familia_servicio'] == $row['id_familia_servicio']) ?"selected":''?>><?php echo $row['nombre_familia'] ?></option>
                            <?php }
                        } ?>
                    </select>
                </div>
                <div class="col-md-6 mb-5">
                    <label class="form-label">Familia Producto</label>
                    <select name="id_familia_producto" id="id_familia_producto" class="form-select form-select-solid" onchange="Productos();">
                        <option value="0">Elegir ....</option>
                        <?php if ($familias_productos != 0) {
                            foreach ($familias_productos as $key => $row) { ?>
                                <option value="<?php echo $row['id_familia_producto'] ?>" <?=(isset($registros) && $registros[0]['id_familia_producto'] == $row['id_familia_producto']) ? "selected":'' ?>><?php echo $row['nombre_familia'] ?></option>
                            <?php }
                        } ?>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control form-control-solid"><?=(isset($registros))?$registros[0]['descripcion']:''?></textarea>
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
        function DescuentoEuro() {
            document.form.descuento_porcentaje.value = 0;
        }
        function DescuentoPorcentaje() {
            document.form.descuento_euros.value = 0;
        }
        function Servicios() {
            document.getElementById("id_familia_producto").value = "0";
        }
        function Productos() {
            document.getElementById("id_familia_servicio").value = "0";
        }
    </script>