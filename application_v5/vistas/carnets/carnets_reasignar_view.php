<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">REASIGNAR CARNET <?= (isset($carnet[0]['codigo'])) ? $carnet[0]['codigo'] : '' ?></h1>
    <div class="card card-flush m-5">
        <div class="card-body p-5">
            <form id="form_carnets" action="<?php echo base_url(); ?>carnets/reasignar/guardar/<?=(isset($carnet[0]['id_carnet']))?$carnet[0]['id_carnet']:''?>" role="form" method="post" name="form_carnets">
                <div class="row mb-5 justify-content-center align-items-end">
                    <div class="col-6 mb-5">
                        <label for="" class="form-label">Cliente:</label>
                        <select name="id_cliente" id="id_cliente" class="form-select form-select-solid" data-placeholder="Elegir ...">
                            <?php if (isset($cliente_elegido) && $cliente_elegido[0]['id_cliente'] > 0) { ?>
                                <option value="<?= $cliente_elegido[0]['id_cliente'] ?>" selected><?= $cliente_elegido[0]['nombre'] . ' ' . $cliente_elegido[0]['apellidos'] . ' (' . $cliente_elegido[0]['telefono'] . ')'; ?></option>
                            <?php } ?>
                        </select>
                        <script type="text/javascript">
                            $("#id_cliente").select2({
                                language: "es",
                                minimumInputLength: 4,
                                ajax: {
                                    delay: 0,
                                    url: function(params) {
                                        return '<?php echo RUTA_WWW; ?>/clientes/json/' + params.term.replace(/ /g, "_");
                                    },
                                    dataType: 'json',
                                    processResults: function(data) {
                                        return {
                                            results: data
                                        };
                                    }
                                }
                            });
                        </script>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-sm btn-secondary text-inverse-secondary m-2" type="button" onclick="window.close();">Cerrar sin Cambios</button>
                        <button class="btn btn-sm btn-primary text-inverse-primary" type="submit">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function Cerrar() {
            window.opener.location.reload();
            window.close();
        }
        <?php if ($accion == "guardar") { ?>
            Cerrar();
        <?php } ?>
    </script>
</body>

</html>