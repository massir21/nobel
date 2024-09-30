<div class="card card-flush">
    <div class="card-body pt-6">
        <?php if ($accion == "nuevo") {
            $actionform = base_url() . 'usuarios/centros/guardar';
        } else {
            $actionform = base_url() . 'usuarios/centros/actualizar/' . $registros[0]['id_centro'];
        } ?>
        <form id="form" action="<?php echo $actionform; ?>" role="form" method="post" name="form">
            <div class="row mb-5">
                <div class="col-md-6 col-lg-4 col-xl-3 mb-5">
                    <label class="form-label">Nombre Centro</label>
                    <input name="nombre_centro" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['nombre_centro'] : '' ?>" placeholder="" required />
                </div>
                <div class=" col-md-6 col-lg-4 col-xl-3 mb-5">
                    <label class="form-label">Email</label>
                    <input name="email" class="form-control form-control-solid" type="email" value="<?= (isset($registros)) ? $registros[0]['email'] : '' ?>" placeholder="" required />
                </div>
                <div class="col-md-6 col-lg-4 col-xl-3 mb-5">
                    <label class="form-label">Email Gestor&iacute;a</label>
                    <input name="email_gestoria" class="form-control form-control-solid" type="email" value="<?= (isset($registros)) ? $registros[0]['email_gestoria'] : '' ?>" placeholder="" />
                </div>
                <div class="col-md-6 col-lg-4 col-xl-3 mb-5">
                    <label class="form-label">Habilitado</label>
                    <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                        <input class="form-check-input w-45px h-30px" type="checkbox" id="habilitado_gestoria" name="habilitado_gestoria" value="1" <?= (isset($registros) && $registros[0]['habilitado_gestoria'] == 1 ) ? "checked" : '' ?>>
                        <label class="form-check-label" for="habilitado_gestoria"></label>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-3 mb-5">
                    <label class="form-label">Teléfono</label>
                    <input name="telefono" class="form-control form-control-solid" type="number" value="<?= (isset($registros)) ? $registros[0]['telefono'] : '' ?>" placeholder="" required />
                </div>
                <div class="col-md-6 col-lg-4 col-xl-3 mb-5">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select form-select-solid" required>
                        <option value="Activo" <?= (isset($registros) && $registros[0]['estado'] == "Activo") ? "selected" : '' ?>>Activo</option>
                        <option value="Inactivo" <?= (isset($registros) && $registros[0]['estado'] == "Inactivo") ? "selected" : '' ?>>Inactivo</option>
                    </select>
                </div>
                <div class="col-md-6 col-lg-3 col-xl-2 mb-5">
                    <label class="form-label">Saldo Inicial</label>
                    <input name="saldo_inicial" class="form-control form-control-solid" type="number" step="0.01" value="<?= (isset($registros)) ? $registros[0]['saldo_inicial'] : '' ?>" placeholder="" required />
                </div>
                <div class="col-md-6 col-lg-3 col-xl-2 mb-5">
                    <label class="form-label">Saldo Efectivo</label>
                    <div class="fs-4">
                        <?php if (isset($registros)) {
                            echo $saldo_actual_efectivo . " €";
                        } ?>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 col-xl-2 mb-5">
                    <label class="form-label">Saldo Tarjeta</label>
                    <div class="fs-4">
                        <?php if (isset($registros)) {
                            echo $saldo_actual_tarjeta . " €";
                        } ?>
                    </div>
                </div>
            </div>
			<div class="row mb-5 border-bottom">
                <div class="col-md-12 mb-5">
                    <label class="form-label">Enlace para formulario de google</label>
                    <input name="link_formulario_evaluacion" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['link_formulario_evaluacion'] : '' ?>">
                </div>
            </div>
            <div class="row mb-5 border-bottom">
                <div class="col-md-12 mb-5">
                    <label class="form-label">Emails para Informe Diario (separados por comas)</label>
                    <input name="emails_informe_diario" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['emails_informe_diario'] : '' ?>">
                </div>
            </div>

            <div class="row mb-5 border-bottom">
                <div class="col-12"><h4>Datos de facturacion y presupuestos</h4></div>
                <div class="col-md-6 col-lg-4 col-xl-3 mb-5">
                    <label class="form-label">Razón social</label>
                    <input name="razon_social_centro" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['razon_social_centro'] : '' ?>" placeholder="" required />
                </div>

                <div class="col-md-6 col-lg-4 col-xl-3 mb-5">
                    <label class="form-label">CIF</label>
                    <input name="cif_centro" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['cif_centro'] : '' ?>" placeholder="" required />
                </div>

                <div class="col-md-6 col-lg-4 col-xl-3 mb-5">
                    <label class="form-label">Dirección</label>
                    <input name="direccion_centro" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['direccion_centro'] : '' ?>" placeholder="" required />
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