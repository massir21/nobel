<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">Cambio de forma de pago en Euros</h1>
    <div class="card card-flush m-5">
        <div class="card-body p-5">
            <h3 class="text-center"><?php echo $registros[0]['cliente']; ?><br><span class="fs-6"><?php echo $registros[0]['fecha_hora_concepto_ddmmaaaa_abrev2']; ?></span></h3>

            <?php if ($registros[0]['tipo_pago'] == "#efectivo" || $registros[0]['tipo_pago'] == "#tarjeta" || $registros[0]['tipo_pago'] == "#transferencia" || $registros[0]['tipo_pago'] == "#tpv2" || $registros[0]['tipo_pago'] == "#habitacion") { ?>
                <form id="form_cambio" action="<?php echo base_url(); ?>dietario/cambio_efectivo_tarjeta/<?php echo $registros[0]['id_dietario']; ?>/actualizar" role="form" method="post" name="form_cambio">
                    <div class="row mb-5 align-items-end border-bottom">
                        <div class="col-12 mb-5">
                            <label for="" class="form-label">Forma de pago actual:</label>
                            <select class="form-select form-select-solid" name="tipo_pago">
                                <option value="#efectivo" <?= ($registros[0]['tipo_pago'] == "#efectivo") ? "selected" : '' ?>>Efectivo</option>
                                <option value="#tarjeta" <?= ($registros[0]['tipo_pago'] == "#tarjeta") ? "selected" : '' ?>>Tarjeta</option>
                                <option value="#transferencia" <?= ($registros[0]['tipo_pago'] == "#transferencia") ? "selected" : '' ?>>Transferencia</option>
                                <option value="#tpv2" <?= ($registros[0]['tipo_pago'] == "#tpv2") ? "selected" : '' ?>>TPV2</option>
                                <?php if ($registros[0]['id_centro'] == 9) { ?>
                                    <option value="#habitacion" <?= ($registros[0]['tipo_pago'] == "#habitacion") ? "selected" : '' ?>>Habitación</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button class="btn btn-sm btn-secondary text-inverse-secondary m-2" type="button" onclick="Cerrar();">Cerrar sin Cambios</button>
                            <button class="btn btn-sm btn-primary text-inverse-primary" type="submit">Guardar</button>
                        </div>
                    </div>
                </form>
            <?php } else { ?>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="alert alerty-primary">Este registro no permite cambios</div>
                    </div>
                    <div class="col-md-12 text-center">
                        <button class="btn btn-sm btn-secondary text-inverse-secondary m-2" type="button" onclick="Cerrar();">Cerrar sin Cambios</button>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <script>
        function Cerrar() {
            window.close();
        }
        <?php if (isset($guardado)) { ?>
            window.opener.location.reload();
            window.close();
        <?php } ?>
    </script>
</body>

</html>