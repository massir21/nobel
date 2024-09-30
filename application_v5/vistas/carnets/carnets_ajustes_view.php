<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">MODIFICAR CARNET DE TEMPLOS <?= (isset($carnet[0]['codigo'])) ? $carnet[0]['codigo'] : '' ?></h1>
    <div class="card card-flush m-5">
        <div class="card-body p-5">
            <form id="form_carnets" action="<?php echo base_url(); ?>carnets/ajustes/guardar/<?= (isset($carnet[0]['id_carnet'])) ? $carnet[0]['id_carnet'] : '' ?>" role="form" method="post" name="form_carnets">
                <div class="row mb-5 justify-content-center align-items-end">
                    <div class="col-6 mb-5">
                        <label for="" class="form-label">Templos Disponibles:</label>
                        <input type="number" name="templos_disponibles" step="0.50" min="0" class="form-control form-control-solid" value="<?= $carnet[0]['templos_disponibles']; ?>" style="width: 100px;" min="0" />
                    </div>
                    <input type="hidden" name="sin_pasar_por_caja" value="1">
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