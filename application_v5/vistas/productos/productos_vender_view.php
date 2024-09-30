<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">VENTA DE PRODUCTOS<?=($cliente != 0)?" A " . strtoupper($cliente[0]['nombre'] . " " . $cliente[0]['apellidos']):''?></h1>
    <div class="card card-flush m-5">
        <div class="card-body p-5">
            <form id="form_vender" action="<?php echo base_url(); ?>productos/dietario/guardar" role="form" method="post" name="form_vender">
                <div class="row mb-5 align-items-end">
                    <?php if ($id_cliente == null) { ?>
                        <div class="col-md-6 mb-5">
                            <label for="" class="form-label">Cliente:</label>
                            <select name="id_cliente" id="id_cliente" class="form-select form-select-solid" data-placeholder="Elegir ...">
                                <?php if (isset($cliente_elegido) && $cliente_elegido[0]['id_cliente'] > 0) {?>
                                    <option value="<?=$cliente_elegido[0]['id_cliente']?>" selected><?= $cliente_elegido[0]['nombre'].' '.$cliente_elegido[0]['apellidos'].' ('.$cliente_elegido[0]['telefono'].')';?></option>
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
                    <?php } else { ?>
                        <input type="hidden" name="id_cliente" value="<?php echo $id_cliente; ?>" />
                    <?php } ?>

                    <div class="col-md-6 mb-5">
                        <label for="" class="form-label">Empleado a quien se le asigna la venta:</label>
                        <select name="id_empleado_venta" id="id_empleado_venta" data-control="select2" class="form-select form-select-solid w-auto">
                            <option value="0">Todos los empleados</option>
                            <?php if (isset($empleados)) {
                                if ($empleados != 0) {
                                    foreach ($empleados as $key => $row) { ?>
                                        <option value='<?php echo $row['id_usuario']; ?>' <?php if (isset($id_usuario)) {if ($row['id_usuario'] == $id_usuario) {echo "selected";}} ?>>
                                            <?php echo strtoupper($row['apellidos'] . ", " . $row['nombre']); ?>
                                        </option>
                                    <?php }
                                }
                            } ?>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <?php for ($i=1; $i <= 7; $i++) { ?>
                            <div id="producto<?=$i?>">
                                <div class="row mb-1 align-items-end" >
                                    <div class="col-9">
                                        <?=($i == 1)?'<label for="" class="form-label">Elegir Productos:</label>':''?>
                                        <select name="id_producto[]" id="id_producto<?=$i?>" class="form-select form-select-solid" data-control="select2" data-placeholder="Elegir ...">
                                            <option value=""></option>
                                            <?php if (isset($productos)) {
                                                if ($productos != 0) {
                                                    foreach ($productos as $key => $row) { ?>
                                                        <option value="<?php echo $row['id_producto']; ?>"><?php echo strtoupper($row['nombre_producto']) . " (" . $row['nombre_familia'] . ")"; ?></option>
                                                <?php }
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <?=($i == 1)?'<label for="" class="form-label">Cantidad:</label>':''?>
                                        <input type="number" name="cantidad[]" id="productoCantidad<?=$i?>" class="form-control form-control-solid" value="1" step="1" min="1" max="100" required />
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-sm btn-secondary text-inverse-secondary m-2" type="button" onclick="window.close();">Cerrar sin Cambios</button>
                        <button class="btn btn-sm btn-primary text-inverse-primary" type="submit">Vender</button>
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