<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">Factura generada</h1>
    <div class="card card-flush m-5">
        <div class="card-body p-5">
            <h3 class="text-center mt-5">Factura generada para: <?php echo $cliente[0]['nombre'] . " " . $cliente[0]['apellidos']; ?></h3>
            <h1 class="text-center my-5">LA FACTURA HA SIDO GENERADA CORRECTAMENTE</h1>
            <div class="row">
                <div class="col-md-12 text-center">
                    <button class="btn btn-sm btn-secondary text-inverse-secondary m-2" type="button" onclick="window.close();">Cerrar</button>
                    <a href="<?php echo base_url(); ?>dietario/ver_factura/<?php echo $id_factura ?>" class="btn btn-sm btn-primary text-inverse-primary" onclick="window.close();" target="_blank">Ver Factura</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html