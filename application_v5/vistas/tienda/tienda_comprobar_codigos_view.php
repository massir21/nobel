<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <form id="form" action="<?php echo base_url(); ?>tienda/procesar_codigo" role="form" method="post" name="form">
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
                <div class="w-auto">
                    <label for="" class="form-label">Código</label>
                    <input type="text" id="codigo" name="codigo" value="<?= (isset($codigo)) ? $codigo : '' ?>" class="form-control form-control-solid w-auto" placeholder="Introducir Código" />
                </div>
                <div class="w-auto    ms-3">
                    <button type="submit" class="btn btn-info text-inverse-info">Comprobar</button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body pt-6">
        <?php if (isset($no_existe)) { ?>
            <div class="alert alert-danger d-flex flex-column flex-sm-row p-5 mb-0">
                <div class="align-items-baseline d-flex justify-content-center pe-0 pe-sm-10">No existe el código indicado: <?= (isset($codigo)) ? $codigo : '' ?></div>
            </div>
        <?php } ?>
        <?php if (isset($usado)) { ?>
            <div class="alert alert-danger d-flex flex-column flex-sm-row p-5 mb-0">
                <div class="align-items-baseline d-flex justify-content-center pe-0 pe-sm-10">El código <?= (isset($codigo)) ? $codigo : '' ?> ya ha sido procesado<?= (isset($post_id)) ? ':<br>Orden No. ' . $post_id : '.' ?></div>
            </div>
        <?php } ?>
        <?php if (isset($items_extranet)) {
            if ($items_extranet > 0) { ?>
                <h5>A continuación se indican los items asociados al código de compra.</h5>
                <div class="table-responsive">
                    <table id="table" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                        <thead class="">
                            <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                                <th>Descripción</th>
                                <th class="text-center" style="width: 20%;">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 fw-semibold">
                            <?php if (isset($items_extranet)) {
                                foreach ($items_extranet as $producto) { ?>
                                    <tr>
                                        <td><?php echo $producto['descripcion']; ?></td>
                                        <td class="text-center"><?php echo $producto['cantidad']; ?></td>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>
                    </table>
                </div>
                <form id="form2" action="<?php echo base_url(); ?>tienda/generar_carnets" role="form" method="post" name="form2" onsubmit="return ComprobarCliente();">
                    <div class="row mb-5 border-bottom">
                        <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
                            <label class="form-label">Cliente al que se le asignarán el/los carnet/s</label>
                            <select name="id_cliente" id="id_cliente" class="form-select form-select-solid" data-placeholder="Cliente al que se le asignarán el/los carnet/s"></select>
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
                        <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
                            <button type="button" class="btn btn-secondary text-inverse-secondary" data-bs-toggle="modal" data-bs-target="#nuevoCliente" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Añadir nuevo cliente..."><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </div>
                        <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
                            <input type="submit" class="btn btn-primary text-inverse-primary" value="Generar Carnets Correspondientes">
                        </div>
                        <input type="hidden" name="codigo" value="<?= (isset($codigo)) ? $codigo : '' ?>">
                    </div>
                </form>
            <?php } else { ?>
                <div class="alert alert-danger d-flex flex-column flex-sm-row p-5 mb-0">
                    <div class="align-items-baseline d-flex justify-content-center pe-0 pe-sm-10">El código: <?= (isset($codigo)) ? $codigo : '' ?> no contiene Servicios o Carnets de templos. Nada que procesar.</div>
                </div>
        <?php }
        } ?>
    </div>
</div>
<!-- Nuevo cliente -->
<div class="modal fade" id="nuevoCliente" tabindex="-1" role="dialog" aria-labelledby="nuevoClienteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formCliente" action="<?php echo base_url(); ?>tienda/nuevo_cliente" role="form" method="post" name="formCliente">
                <div class="modal-header">
                    <h4 class="modal-title" id="nuevoClienteLabel">Nuevo Cliente</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <label class="form-label">Nombre</label>
                            <input type="text" id="nombre" name="nombre" value="" class="form-control form-control-solid" placeholder="Nombre" />
                        </div>
                        <div class="col-12 mb-4">
                            <label class="form-label">Apellidos</label>
                            <input type="text" id="apellidos" name="apellidos" value="" class="form-control form-control-solid" placeholder="Apellidos" />
                        </div>
                        <div class="col-12 mb-4">
                            <label class="form-label">Teléfono</label>
                            <input type="text" id="telefono" name="telefono" value="" class="form-control form-control-solid" placeholder="Teléfono" />
                        </div>
                        <div class="col-12 mb-4">
                            <label class="form-label">NO quiero recibir publicidad</label>
                            <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                                <input class="form-check-input w-45px h-30px" type="checkbox" id="no_quiere_publicidad" name="no_quiere_publicidad" value="1">
                                <label class="form-check-label" for="no_quiere_publicidad"></label>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="codigo" value="<?php if (isset($codigo)) {
                                                                    echo $codigo;
                                                                } ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary text-inverse-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary text-inverse-primary">Crear Cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    var myModalEl = document.getElementById('nuevoCliente')
    myModalEl.addEventListener('hidden.bs.modal', function(event) {
        document.formCliente.nombre.value = "";
        document.formCliente.apellidos.value = "";
        document.formCliente.telefono.value = "";
        document.formCliente.no_quiere_publicidad.value = 0;
        document.formCliente.no_quiere_publicidad.value = 0;
    })
</script>
<script>
    function ComprobarCliente() {
        if (document.form2.id_cliente.value > 0) {
            if (confirm("SE VA HA PROCEDER A CREAR EL/LOS CARNET/S CORRESPONDIENTES A LOS ITEMS DEL CÓDIGO INDICADO.\n\n¿DESEAS CONTINUAR CON EL PROCESO?")) {
                return true;
            } else {
                return false;
            }
        } else {
            alert("DEBES DE INDICAR UN CLIENTE PARA PROCESAR EL CÓDIGO");
            return false;
        }
    }
</script>