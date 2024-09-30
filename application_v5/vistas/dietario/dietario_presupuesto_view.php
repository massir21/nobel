<style>
    .dataTables_filter {
        text-align: right;
    }
</style>
<div class="card card-flush">
    <div class="card-body pt-6">
        <form name="form_presupuesto" id="form_presupuesto" action="<?php echo base_url(); ?>dietario/crearPresupuesto" method="post" target="_blank" onsubmit="return EsOk2();">
            <div class="row mb-5 align-items-end">
                <div class="col-md-10 col-xl-11">
                    <label for="" class="form-label">Elige el cliente:</label>
                    <?php //<input type="text" id="cliente" name="cliente" class="form-control form-control-solid" required /> ?>
                    <select name="cliente" id="cliente" class="form-select form-select-solid" data-placeholder="Elegir ...">
                    <input type="hidden" name="nombreCliente" id="nombreCliente" />
                    <script type="text/javascript">
                        $("#cliente").select2({
                            language: "es",
                            minimumInputLength: 4,
                            ajax: {
                                delay: 0,
                                url: function (params) {
                                    return '<?php echo RUTA_WWW; ?>/clientes/json/'+ params.term;
                                },
                                dataType: 'json',
                                processResults: function (data) {
                                    return {
                                        results: data
                                    };
                                }
                            }
                        });    
                    </script>
                </div>
                <div class="col-md-2 col-xl-1">
                    <button type="button" class="btn btn-info text-inverse-info btn-icon" data-bs-target="#stack-cliente" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Añadir un nuevo Cliente"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="row mb-5 align-items-end">
                <div class="col-md-10 col-xl-11">
                    <label for="" class="form-label">Elige el empleado:</label>
                    <input type="hidden" id="nombreEmpleado" name="nombreEmpleado" />
                    <select name="id_empleado_venta" id="id_empleado_venta" class="form-select form-select-solid w-auto" data-control="select2" data-placeholder="Elegir ..." onclick="buscarEmpleado();">
                        <option value=""></option>
                        <?php if (isset($empleados)) {
                            if ($empleados != 0) {
                                foreach ($empleados as $key => $row) { ?>
                                    <option value='<?php echo $row['id_usuario']; ?>'><?php echo strtoupper($row['apellidos'] . ", " . $row['nombre']); ?></option>
                                <?php }
                            }
                        } ?>
                    </select>
                </div>
            </div>
            <div class="mb-5">
            <?php for ($i=1; $i <= 15; $i++) { ?>
                <div id="producto<?=$i?>" <?=($i>1)?'style="display:none;"':''?>>
                    <div class="row mb-1 align-items-end" >
                        <div class="col-lg-4 col-xl-8">
                        <?=($i==1)?'<label for="" class="form-label">Elegir Productos:</label>':''?>
                            <select name="id_producto<?=$i?>" id="id_producto<?=$i?>" class="form-select form-select-solid" data-control="select2" data-placeholder="Elegir ..." onchange="buscarEmpleado(<?=$i?>);">
                                <option value=""></option>
                                <?php if (isset($productos)) {
                                    if ($productos != 0) {
                                        foreach ($productos as $key => $row) { ?>
                                            <option value='<?php echo $row['id_producto']; ?>'><?php echo strtoupper($row['nombre_producto']) . " (" . $row['nombre_familia'] . ")"; ?></option>
                                <?php }
                                    }
                                } ?>
                            </select>
                        </div>
                        <div class="col-lg-2 col-xl-1">
                        <?=($i==1)?'<label for="" class="form-label">Cantidad</label>':''?>
                            <input type="hidden" name="productoNombre<?=$i?>" id="productoNombre<?=$i?>" />
                            <input type="number" name="productoCantidad<?=$i?>" id="productoCantidad<?=$i?>" class="form-control form-control-solid" value="1" step="1" min="1" max="100" required />
                        </div>
                        <div class="col-lg-2 col-xl-1">
                        <?=($i==1)?'<label for="" class="form-label">PVP €</label>':''?>
                            <input type="text" name="productoPrecio<?=$i?>" id="productoPrecio<?=$i?>" class="form-control form-control-solid" readonly />
                        </div>
                        <div class="col-lg-2 col-xl-1">
                        <?=($i==1)?'<label for="" class="form-label">DTO %</label>':''?>
                            <input type="number" name="productoDescuento<?=$i?>" id="productoDescuento<?=$i?>" class="form-control form-control-solid" value="0" step="1" min="0" max="100" required />
                        </div>
                        <div class="col-lg-2 col-xl-1">
                            <a class="btn btn-info text-inverse-info btn-icon" id="botonProducto<?=$i?>" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Añadir un nuevo Producto" onclick="habilitaProducto(<?=$i + 1?>)">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
            </div>
            <div class="border-bottom mb-5 pb-5">
            <?php for ($i=1; $i <= 15; $i++) { ?>
                <div id="servicio<?=$i?>" <?=($i>1)?'style="display:none;"':''?>>
                    <div class="row mb-1 align-items-end" >
                        <div class="col-lg-4 col-xl-8">
                        <?=($i==1)?'<label for="" class="form-label">Elegir servicios:</label>':''?>
                            <select name="id_servicio[]" id="id_servicio<?=$i?>" class="form-select form-select-solid" data-control="select2" data-placeholder="Elegir ..." onchange="buscarServicio(<?=$i?>);">
                                <option value=""></option>
                                <?php if (isset($servicios)) {
                                    if ($servicios != 0) {
                                        foreach ($servicios as $key => $row) { ?>
                                            <option value='<?php echo $row['id_servicio']; ?>'>
                                                <?php echo strtoupper($row['nombre_servicio']) . " (" . $row['nombre_familia'] . ")"; ?>
                                            </option>
                                        <?php }
                                    }
                                } ?>
                            </select>
                        </div>
                        <div class="col-lg-2 col-xl-1">
                        <?=($i==1)?'<label for="" class="form-label">Cantidad</label>':''?>
                            <input type="hidden" name="servicioNombre<?=$i?>" id="servicioNombre<?=$i?>" />
                            <input type="number" name="servicioCantidad<?=$i?>" id="servicioCantidad<?=$i?>" class="form-control form-control-solid" value="1" step="1" min="1" max="100" required />
                        </div>
                        <div class="col-lg-2 col-xl-1">
                        <?=($i==1)?'<label for="" class="form-label">PVP €</label>':''?>
                            <input type="text" name="servicioPrecio<?=$i?>" id="servicioPrecio<?=$i?>" class="form-control form-control-solid" readonly />
                        </div>
                        <div class="col-lg-2 col-xl-1">
                        <?=($i==1)?'<label for="" class="form-label">DTO %</label>':''?>
                            <input type="number" name="servicioDescuento<?=$i?>" id="servicioDescuento<?=$i?>" class="form-control form-control-solid" value="0" step="1" min="0" max="100" required />
                        </div>
                        <div class="col-lg-2 col-xl-1">
                            <a class="btn btn-info text-inverse-info btn-icon" id="botonProducto<?=$i?>" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Añadir un nuevo Servicio" onclick="habilitaServicio(<?=$i + 1?>)">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php }?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary text-inverse-primary" type="submit">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="stack-cliente" tabindex="-1" aria-labelledby="stack-clienteLabel" aria-hidden="true" data-focus-on="input:first">
    <div class="modal-dialog">
        <div class="modal-content">
            <form name="form_nuevo_cliente" id="form_nuevo_cliente" action="<?php echo base_url(); ?>dietario/altaCliente" method="post" onsubmit="return EsOk();">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">AÑADIR NUEVO CLIENTE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">        
                <div class="row">
                    <div class="col-12 mb-4">
                        <label class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" value="" class="form-control form-control-solid " placeholder="Nombre"/>
                    </div>
                    <div class="col-12 mb-4">
                        <label class="form-label">Apellidos</label>
                        <input type="text" id="apellidos" name="apellidos" value="" class="form-control form-control-solid" placeholder="Apellidos"/>
                    </div>
                    <div class="col-12 mb-4">
                        <label class="form-label">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" value="" class="form-control form-control-solid" placeholder="Teléfono"/>
                    </div>
                    <div class="col-12 mb-4">
                        <label class="form-label">NO quiero recibir publicidad</label>
                        <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                            <input class="form-check-input w-45px h-30px" type="checkbox" id="no_quiere_publicidad" name="no_quiere_publicidad" value="1">
                            <label class="form-check-label" for="no_quiere_publicidad"></label> 
                        </div>
                    </div>
                </div>
                <input type="hidden" name="codigo" value="<?php if (isset($codigo)) { echo $codigo; } ?>">        
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary text-inverse-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary text-inverse-primary">Crear Cliente</button>
            </div>
            <input type="hidden" name="solo_este_empleado" value="" />
            <input type="hidden" name="id_empleado" value="" />
            <input type="hidden" name="id_servicio" value="" />
            <input type="hidden" name="fecha" value="" />
            <input type="hidden" name="hora" value="" />
            <input type="hidden" name="observaciones" value="" />
            </form>
        </div>
    </div>
</div>
<script>
    var myModalEl = document.getElementById('stack-cliente')
    myModalEl.addEventListener('hidden.bs.modal', function (event) {
        document.form_nuevo_cliente.nombre.value = "";
        document.form_nuevo_cliente.apellidos.value = "";
        document.form_nuevo_cliente.telefono.value = "";
        document.form_nuevo_cliente.no_quiere_publicidad.value = 0;
        document.form_nuevo_cliente.no_quiere_publicidad.value = 0;
    })
</script>
<script>
    function EsOk() {
        if (document.form_cita.solo_este_empleado.checked) {
            document.form_nuevo_cliente.solo_este_empleado.value = 1;
        }
        document.form_nuevo_cliente.id_empleado.value = document.form_cita.id_empleado.value;
        document.form_nuevo_cliente.id_servicio.value = document.form_cita.id_servicio.value;
        document.form_nuevo_cliente.fecha.value = document.form_cita.fecha.value;
        document.form_nuevo_cliente.hora.value = document.form_cita.hora.value;
        document.form_nuevo_cliente.observaciones.value = document.form_cita.observaciones.value;
        return true;
    }
    function EsOk2() {
        document.getElementById('nombreCliente').value = $('p').text();
        var combo = document.getElementById("id_empleado_venta");
        var selected = combo.options[combo.selectedIndex].text;
        document.getElementById("nombreEmpleado").value = selected;
        return true;
    }
    function FichaCliente(id_cliente) {
        var posicion_x;
        var posicion_y;
        var ancho = 800;
        var alto = 680;
        posicion_x = (screen.width / 2) - (ancho / 2);
        posicion_y = (screen.height / 2) - (alto / 2);
        window.open("<?php echo base_url(); ?>clientes/historial/ver/" + id_cliente, "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
    }
    function NuevaNota(id_cliente) {
        var posicion_x;
        var posicion_y;
        var ancho = 600;
        var alto = 400;
        posicion_x = (screen.width / 2) - (ancho / 2);
        posicion_y = (screen.height / 2) - (alto / 2);
        window.open("<?php echo base_url(); ?>clientes/nueva_nota_cita_agenda/" + id_cliente, "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
    }
    function Cobrar(id_cliente, fecha) {
        var posicion_x;
        var posicion_y;
        var ancho = 800;
        var alto = 600;
        posicion_x = (screen.width / 2) - (ancho / 2);
        posicion_y = (screen.height / 2) - (alto / 2);
        window.open("<?php echo base_url(); ?>dietario/ficha/ver/" + id_cliente + "/" + fecha, "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
    }
/*
    $(document).tooltip({
        position: {
            my: "center bottom-20",
            at: "center top",
            using: function(position, feedback) {
                $(this).css(position);
                $("<div>")
                    .addClass("arrow")
                    .addClass(feedback.vertical)
                    .addClass(feedback.horizontal)
                    .appendTo(this);
            }
        }
    });*/
    function buscarEmpleado(j) {
        var combo = document.getElementById("id_producto" + j);
        var selected = combo.options[combo.selectedIndex].text;
        var id_producto = ($("#id_producto" + j).val());
        var ar = <?php echo json_encode($productos) ?>;
        for (i = 0; i < ar.length; i++) {
            if (ar[i]['id_producto'] == id_producto) {
                $("#productoPrecio" + j).val(ar[i]['pvp']);
                $("#productoNombre" + j).val(selected);
            }
        }
    }
    function habilitaProducto(i) {
        $("#producto" + i).show();
    }
    function buscarServicio(j) {
        var combo = document.getElementById("id_servicio" + j);
        var selected = combo.options[combo.selectedIndex].text;
        var id_servicio = ($("#id_servicio" + j).val());
        var ar = <?php echo json_encode($servicios) ?>;
        for (i = 0; i < ar.length; i++) {
            if (ar[i]['id_servicio'] == id_servicio) {
                $("#servicioPrecio" + j).val(ar[i]['pvp']);
                $("#servicioNombre" + j).val(selected);
            }
        }
    }
    function habilitaServicio(i) {
        $("#servicio" + i).show();
    }
</script>