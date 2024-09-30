<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">Datos del cliente</h1>
    <div class="card card-flush m-5">
        <div class="card-body p-5">
            <?php if (isset($registros)) { ?>
                <h3 class="text-center"><?php echo $registros[0]['nombre'] . " " . $registros[0]['apellidos']; ?></h3>
                <p class="text-center">Todos los datos son obligatorios para generar factura</p>
                <form name="form" id="form" action="<?php echo base_url(); ?>dietario/registrar_datos_facturacion/<?php echo $registros[0]['id_cliente'] ?><?=(isset($id_centro_facturar))?$id_centro_facturar:'' ?><?php if(isset($recibo)){echo "?recibo=1";} ?>" method="POST">
                    <div class="row mb-5 align-items-end border-bottom">
                        <div class="col-6 mb-5">
                            <label class="form-label">Nombre o Empresa</label>
                            <input name="empresa" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['empresa'] : '' ?>" required="" />
                        </div>
                        <div class="col-6 mb-5">
                            <label class="form-label">CIF o NIF</label>
                            <input name="cif_nif" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['cif_nif'] : '' ?>" required="" />
                        </div>
                        <div class="col-6 mb-5">
                            <label class="form-label">Dirección</label>
                            <input name="direccion_facturacion" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['direccion_facturacion'] : '' ?>" required="" />
                        </div>
                        <div class="col-6 mb-5">
                            <label class="form-label">Código Postal</label>
                            <input name="codigo_postal_facturacion" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['codigo_postal_facturacion'] : '' ?>" required="" />
                        </div>
                        <div class="col-6 mb-5">
                            <label class="form-label">Localidad</label>
                            <input name="localidad_facturacion" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['localidad_facturacion'] : '' ?>" required="" />
                        </div>
                        <div class="col-6 mb-5">
                            <label class="form-label">Provincia</label>
                            <input name="provincia_facturacion" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['provincia_facturacion'] : '' ?>" required="" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button class="btn btn-sm btn-secondary text-inverse-secondary m-2" type="button" onclick="window.close();">Cerrar sin Cambios</button>
                            <button class="btn btn-sm btn-primary text-inverse-primary" type="submit">Guardar</button>
                        </div>
                    </div>
                </form>
            <?php }else{
                echo 'No Hay datos';
            } ?>
        </div>
    </div>

    <script>
        <?php if (isset($accion) && $accion == "actualizar") { ?>
            
            <?php if (isset($recibo)){ ?>
                //para el caso de un recibo hay que recargar la ventana opener
                window.opener.location.reload();
            <?php }else{?>
                //para el caso de una factura hay que ejecutar la funcion consultar_conceptos_cliente
                window.opener.consultar_conceptos_cliente();
            <?php } ?>
            window.close();
            
        <?php } ?>
    </script>

</body>

</html>