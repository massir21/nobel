<style>
    .dataTables_filter {
        text-align: right;
    }
</style>
<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="logs">
            </div>
        </div>
        <form id="form_estadisticas" action="<?php echo base_url(); ?>estadisticas/historicocitas" role="form" method="post" name="form_estadisticas">
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
                <div class="w-auto">
                    <label for="" class="form-label">Fecha desde</label>
                    <input type="date" id="fecha" name="fecha_desde" value="<?php if (isset($fecha_desde)) {echo $fecha_desde;} ?>" class="form-control form-control-solid w-auto" placeholder="Fecha desde" required/>
                </div>
                <div class="w-auto ms-3">
                    <label for="" class="form-label">Fecha hasta</label>
                    <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?php if (isset($fecha_hasta)) {echo $fecha_hasta; } ?>" class="form-control form-control-solid w-auto" placeholder="Fecha hasta" required/>
                </div>
                <div class="w-auto ms-3">
                    <label for="" class="form-label">Cliente:</label>
                    <select name="id_cliente" id="id_cliente" class="form-select form-select-solid" data-placeholder="Elegir ..."></select> 
                    <script type="text/javascript">
                        $("#id_cliente").select2({
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
                <div class="w-auto ms-3">
                    <label for="" class="form-label">Estado:</label>
                    <select name="estado" id="estado" class="form-select form-select-solid w-auto">
                        <option value="" selected>Todos</option>
                        <option value="Anulada" <?php if ($estado == "Anulada") { echo "selected";} ?>>Anulada</option>
                        <option value="Devuelto" <?php if ($estado == "Devuelto") {echo "selected";} ?>>Devuelto</option>
                        <option value="No Pagado" <?php if ($estado == "No Pagado") {echo "selected";} ?>>No Pagado</option>
                        <option value="No vino" <?php if ($estado == "No vino") {echo "selected";} ?>>No vino</option>
                        <option value="Pagado" <?php if ($estado == "Pagado") {echo "selected";} ?>>Pagado</option>
                    </select>
                </div>
                <div class="w-auto  ms-3">
                    <button type="submit" class="btn btn-info btn-icon text-inverse-info"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body d-flex flex-column-reverse pt-6">
        <div class="table-responsive">
            <table id="logs" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th style="display: none">ID</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Servicio</th>
                        <th>Empleado</th>
                        <th>Recepcionista<br>Creación</th>
                        <th>Recepcionista<br>Modificó</th>
                        <th>Recepcionista<br>Marcó Pagado</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php if (isset($registros)) {
                        if ($registros != 0) {
                            foreach ($registros as $key => $row) { ?>
                                <tr style="background: <?php echo $row['color_estado'] ?>;">
                                    <th style="display: none"><?php echo $row['fecha_hora_concepto_aaaammdd']; ?></th>
                                    <td><?php echo $row['fecha_hora_concepto_ddmmaaaa_abrv'] . " - " . $row['hora']; ?></td>
                                    <td><?php echo $row['cliente']; ?></td>
                                    <td><?php echo $row['servicio']; ?></td>
                                    <td><?php echo $row['empleado']; ?></td>
                                    <td>
                                        <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6"><?php echo $row['recepcionista_inicio']; ?></span>
                                        <span class="text-muted fw-semibold text-muted d-block fs-7"><?php echo $row['fecha_creacion_abrev']; ?></span>
                                    </td>
                                    <td>
                                        <?php if ($row['id_centro_recepcionista'] == $row['id_centro']) { ?>
                                            <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6"><?php echo $row['recepcionista']; ?></span>
                                            <span class="text-muted fw-semibold text-muted d-block fs-7"><?php echo $row['fecha_modificacion_abrev']; ?></span>
                                        <?php } else { ?>
                                            <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6"><?php echo $row['recepcionista_inicio']; ?></span>
                                            <span class="text-muted fw-semibold text-muted d-block fs-7"><?php echo $row['fecha_modificacion_abrev']; ?></span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6"><?php echo $row['recepcionista_pagado']; ?></span>
                                        <span class="text-muted fw-semibold text-muted d-block fs-7"><?php echo $row['fecha_pagado_abrev']; ?></span>
                                    </td>
                                    <td>
                                        <?php if ($row['estado'] == "Pagado" || $row['estado'] == "Devuelto") {
                                            $tipo_pago_label = $row['tipo_pago'];
                                            $tipo_pago_label = str_replace('#efectivo', '<span class="badge d-block fs-4 badge-info">Efectivo</span> ', $tipo_pago_label);
                                            $tipo_pago_label = str_replace('#tarjeta', '<span class="badge d-block fs-4 badge-success">Tarjeta</span> ', $tipo_pago_label);
                                            $tipo_pago_label = str_replace('#habitacion', '<span class="badge d-block fs-4 badge-primary">Habitación</span>', $tipo_pago_label);
                                            $tipo_pago_label = str_replace('#templos', '<span class="badge d-block fs-4 badge-warning">templos</span> ', $tipo_pago_label);
                                            echo $row['estado'] . "<br>" . $tipo_pago_label; 
                                        } else {
                                            echo $row['estado'];
                                        } ?>
                                    </td>
                                    </tr>
                                <?php }
                                }
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>