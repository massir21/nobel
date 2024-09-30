<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">Recibo generado</h1>
    <div class="card card-flush m-5">
        <div class="card-body p-5">
            <h3 class="text-center mt-5">Recibo generado para: <?php echo $cliente[0]['nombre'] . " " . $cliente[0]['apellidos']; ?></h3>
            <h1 class="text-center my-5">El RECIBO HA SIDO GENERADA CORRECTAMENTE</h1>
            <div class="row">
                <div class="col-md-12 text-center">
                        <a onclick="cerrar()" class="btn btn-sm btn-secondary text-inverse-secondary m-2" >Regresar</a>
                        <a href="<?php echo base_url(); ?>dietario/ver_recibo/<?php echo $id_recibo ?>" class="btn btn-sm btn-primary text-inverse-primary" target="_blank">Ver Recibo</a>
                </div>
            </div>
        </div>
    </div>
</body>



<script language="javascript" type="text/javascript"> 
function cerrar() { 
    window.close();
   window.open('','_parent',''); 
   window.close(); 
} 
</script>


</html>