<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">NUEVA NOTA PARA CITA (<?php echo $cliente[0]['nombre']." ".$cliente[0]['apellidos']; ?>)</h1>

    <div class="card card-flush m-5">
        <div class="card-body p-5">
            <form id="form" action="<?php echo base_url();?>clientes/crear_nota_cita_agenda/<?php echo $cliente[0]['id_cliente']; ?>" role="form" method="post" name="form">
                <div class="row mb-5 align-items-end border-bottom">
                    <div class="col-12">
                        <label for="" class="form-label">Nota de la cita</label>
                        <textarea name="nota" id="nota" class="form-control form-control-solid" style="height: 190px;" required></textarea>
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
        <?php if (isset($accion)) { if ($accion == "guardar") { ?>
            Cerrar();
        <?php }} ?>    
    </script>
</body>
</html>