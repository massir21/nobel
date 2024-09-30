<?php setlocale(LC_MONETARY, 'es_ES'); ?>

<div class="card card-flush">

    <div class="card-body pt-6">
        <form id="form" action="<?php echo base_url() . 'cupones/editar/' . $cupon['id_cupon']; ?>" role="form" method="post" name="form" style="padding: 10px;">

            <?php echo form_hidden('accion', $accion); ?>
            <?php echo form_hidden('id_cupon', $cupon['id_cupon']); ?>

            <div class="row mb-5 border-bottom">
                <h4>Configuración base:</h4>
                <div class="col-12 mb-5">
                    <label class="form-label">Datos básicos del cupón. Si no se especifica la <strong>fecha de finalización</strong>, la validez será indefinida. En <strong>Descuentos</strong>, se aplicará primero el descuento en euros y si existe, el descuento en porcentaje.</label>
                </div>
                <div class="col-md-6 col-lg-4 mb-5">
                    <label class="form-label">Código del cupón</label>
                    <input name="codigo_cupon" class="form-control form-control-solid" type="text" value="<?php echo $cupon['codigo_cupon']; ?>" placeholder="" />
                    <span class="help-block"><?php echo form_error('codigo_cupon'); ?></span>
                </div>

                <div class="col-md-6 col-lg-4 mb-5">
                    <label class="form-label">Fecha de inicio</label>
                    <input name="fecha_desde" class="form-control form-control-solid" type="date" value="<?= ($cupon['fecha_desde'] != '0000-00-00 00:00:00') ? date('Y-m-d', strtotime($cupon['fecha_desde'])) : '' ?>" placeholder="" />
                    <span class="help-block"><?php echo form_error('fecha_desde'); ?></span>
                </div>

                <div class="col-md-6 col-lg-4 mb-5">
                    <label class="form-label">Fecha de vencimiento</label>
                    <input name="fecha_hasta" class="form-control form-control-solid" type="date" value="<?= ($cupon['fecha_hasta'] != '0000-00-00 00:00:00') ? date('Y-m-d', strtotime($cupon['fecha_hasta'])) : '' ?>" placeholder="" />
                    <span class="help-block"><?php echo form_error('fecha_hasta'); ?></span>
                </div>

                <div class="col-md-6 col-lg-4 mb-5">
                    <label class="form-label">Descuento</label>
                    <select class="form-select form-select-solid w-auto" data-control="select2" data-placeholder="Elegir ..." data-placeholder="Tipo de descuento" data-minimum-results-for-search="Infinity" name="tipo_descuento" id="tipo_descuento">
                        <option value="">selecciona un tipo de descuento</option>
                        <option value="euros" <?= ($cupon['descuento_euros'] != 0) ? 'selected' : '' ?>>Descuento en euros</option>
                        <option value="porciento" <?= ($cupon['descuento_porcentaje'] != 0) ? 'selected' : '' ?>>Descuento en porcentaje</option>
                    </select>
                </div>
                <div class="col-md-6 tipo_descuento collapse <?= ($cupon['descuento_euros'] != 0) ? 'show' : '' ?>" id="tipo_euros">
                    <label class="form-label">Descuento en Euros</label>
                    <input name="descuento_euros" class="form-control form-control-solid" type="number" value="<?php echo $cupon['descuento_euros']; ?>" placeholder="" />
                    <span class="help-block"><?php echo form_error('descuento_euros'); ?></span>
                </div>

                <div class="col-md-6 tipo_descuento collapse <?= ($cupon['descuento_porcentaje'] != 0) ? 'show' : '' ?>" id="tipo_porciento">
                    <label class="form-label">Descuento en porcentaje</label>
                    <input name="descuento_porcentaje" class="form-control form-control-solid" type="number" value="<?php echo $cupon['descuento_porcentaje']; ?>" placeholder="" />
                    <span class="help-block"><?php echo form_error('descuento_porcentaje'); ?></span>
                </div>
            </div>

            <div class="row mb-5 border-bottom">
                <h4>Úsos del cupón:</h4>
                <div class="col-12 mb-5">
                    <span>Cuando se selecciona un cliente, el cupón será válido únicamente para ese cliente. <strong>Usos por cliente</strong> indica el número de veces que un mismo cliente puede usar un cupón. <strong>Usos totales</strong> indica el limite de veces que se puede usar un cupón</span>
                </div>

                <div class="col-md-6 col-lg-4 mb-5">
                    <label class="form-label">Cliente <small style="display: block;">(en blanco para todos)</small></label>
                    <select name="id_cliente" id="id_cliente" class="form-select form-select-solid" data-placeholder="Elegir ...">
                        <?php if (isset($cupon['id_cliente']) && $cupon['id_cliente'] > 0) { ?>
                            <option value="<?php echo $cupon['id_cliente'] ?>" selected><?php echo $this->Cupones_model->nombre_cliente($cupon['id_cliente']); ?></option>
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

                    <span class="help-block"><?php echo form_error('id_cliente'); ?></span>
                </div>

                <div class="col-md-6 col-lg-4 mb-5">
                    <label class="form-label">Usos por cliente<small style="display: block;">(en blanco para ilimitado)</small></label>
                    <input name="cantidad_cliente" class="form-control form-control-solid" type="number" value="<?php echo $cupon['cantidad_cliente']; ?>" placeholder="" />
                    <span class="help-block"><?php echo form_error('cantidad_cliente'); ?></span>
                </div>


                <div class="col-md-6 col-lg-4 mb-5">
                    <label class="form-label">Usos totales<small style="display: block;">(en blanco para ilimitado)</small></label>
                    <input name="cantidad" class="form-control form-control-solid" type="number" value="<?php echo $cupon['cantidad']; ?>" placeholder="" />
                    <span class="help-block"><?php echo form_error('cantidad'); ?></span>
                </div>
            </div>

            <div class="row mb-5 border-bottom">
                <h4>Servicios asociados al cupón:</h4>
                <div class="col-12 mb-5">
                    <span>Cuando se selecciona una o más familias, el cupón será válido para todos los servicios de las familias seleccionadas y los servicios seleccionados, si hubiese.</span>
                </div>

                <div class="col-md-6 col-lg-4 mb-5">
                    <label class="form-label">Familia</label>
                    <div class="input-group mb-5">
                        <select name="id_familia_servicio_tienda" id="id_familia_servicio_tienda" class="form-select form-select-solid" data-placeholder="Seleciona una familia" onchange="Servicios('#id_servicio_pre','<?php echo base_url(); ?>tienda/servicios_familia');">
                            <option value="">Elegir...</option>
                            <?php foreach ($familias_servicios as $row) { ?>
                                <option value="<?php echo $row['id_familia_servicio']; ?>" <?= ($row['id_familia_servicio'] == $cupon['id_familia_servicio']) ? 'selected' : '' ?>>
                                    <?php echo $row['nombre_familia']; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <span class="input-group-btn btn-warning text-inverse-warning">
                            <button class="btn btn-warning" type="button" onclick="add_familias()"><i class="fa fa-plus"></i></button>
                        </span>
                    </div>
                    <label class="form-label">Familias seleccionadas(*)</label>
                    <select name="id_familia_servicio[]" id="id_familia_servicio" class="form-select form-select-solid " data-control="select2" multiple data-placeholder="Seleciona una familia">
                        <?php $array_familias = explode(',', $cupon['id_familia_servicio']);
                        foreach ($familias_servicios as $row) { ?>
                            <?php if (in_array($row['id_familia_servicio'], $array_familias)) { ?>
                                <option value="<?php echo $row['id_familia_servicio']; ?>" selected>
                                    <?php echo $row['nombre_familia']; ?>
                                </option>
                        <?php }
                        } ?>
                    </select>
                    <span class="help-block"><?php echo form_error('id_familia_servicio'); ?></span>
                </div>

                <div class="col-md-6 col-lg-8 mb-5">
                    <label class="form-label">Servicio</label>
                    <div class="input-group mb-5">
                        <select name="id_servicio_pre" id="id_servicio_pre" class="form-select form-select-solid" data-placeholder="Elegir ...">
                        </select>
                        <span class="input-group-btn btn-warning text-inverse-warning">
                            <button class="btn btn-warning" type="button" onclick="add_servicios()"><i class="fa fa-plus"></i></button>
                        </span>
                    </div>
                    <label class="form-label">Servicios seleccionados</label>
                    <select name="id_servicio[]" id="id_servicio" class="form-select form-select-solid" data-control="select2" data-placeholder="Elegir ..." multiple>
                        <?php
                        $servicios_cupon = explode(',', $cupon['id_servicio']);
                        if (count($servicios_cupon) > 0) {
                            foreach ($servicios_cupon as $value) { ?>
                                <?php $servicio = $this->Cupones_model->get_servicio($value);
                                if ($servicio) { ?>
                                    <option value="<?php echo $servicio->id_servicio; ?>" selected>
                                        <?php echo $servicio->nombre_servicio; ?>
                                    </option>
                        <?php }
                            }
                        } ?>
                    </select>
                    <span class="help-block"><?php echo form_error('id_servicio'); ?></span>
                </div>
            </div>
            <div class="row mb-5 border-bottom">
                <div class="col-md-12">
                    <textarea class="form-control form-control-solid" name="comentario" rows="4"><?php echo set_value('comentario'); ?><?= ($cupon['comentario'] != '') ? $cupon['comentario'] : ''; ?></textarea>
                    <span class="help-block"><?php echo form_error('comentario'); ?></span>
                </div>
            </div>
            <div class="row mb-5 border-bottom">
                <div class="col-md-12" style="text-align: center;">
                    <input class="btn btn-primary margin-top-10" type="submit" value="GUARDAR" />
                </div>
            </div>
        </form>
    </div>
</div>
<!-- END CONTENT BODY -->
<script>
    $(document).on('change', '#tipo_descuento', function() {
        elem = '#tipo_' + $(this).val();
        $('.tipo_descuento').find('input').val('');
        $('.tipo_descuento').collapse('hide');
        $(elem).collapse('show');
    });
    /*
     * Carga los Servicios correspondientes a la familia indicada.
     */
    function Servicios(control, url) {
        var dataString = $("#form").serialize();
        $.ajax({
            type: "POST",
            url: url,
            data: dataString,
            success: function(response) {
                $(control).html('').fadeIn();
                $(control).append(response);
                $('#id_servicio_pre').trigger('change.select2');
            }
        });
    }

    function add_servicios() {
        if ($('#id_servicio_pre').val() > 0) {
            var opcion = "<option value='" + $('#id_servicio_pre').val() + "' selected>" + $("#id_servicio_pre option:selected").text() + "</option>";
            $('#id_servicio').append(opcion);
            $('#id_servicio').trigger('change.select2');
        }
    }

    function add_familias() {
        if ($('#id_familia_servicio_tienda').val() > 0) {
            var opcion = "<option value='" + $('#id_familia_servicio_tienda').val() + "' selected>" + $("#id_familia_servicio_tienda option:selected").text() + "</option>";
            $('#id_familia_servicio').append(opcion);
            $('#id_familia_servicio').trigger('change.select2');
        }
    }
</script>