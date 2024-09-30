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
        <form id="form_estadisticas" action="<?php echo base_url();?>estadisticas/ventas_online" role="form" method="post" name="form_estadisticas">
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
                    <label for="" class="form-label">Centro:</label>
                    <select name="id_centro" id="id_centro" class="form-select form-select-solid w-auto">
                    <option value="">Todos</option>
                        <?php if (isset($centros)) {
                            if ($centros != 0) {
                                foreach ($centros as $key => $row) {
                                    if ($row['id_centro'] > 1) { ?>
                                        <option value='<?php echo $row['id_centro']; ?>' <?php if (isset($id_centro)) {if ($row['id_centro'] == $id_centro) {echo "selected";}} ?>>
                                            <?php echo $row['nombre_centro']; ?>
                                        </option>
                                    <?php }
                                }
                            }
                        } ?>
                    </select>
                </div>
                <div class="w-auto ms-3">
                    <label for="" class="form-label">Tipo Pago:</label>
                    <select name="tipo_pago" id="tipo_pago" class="form-select form-select-solid w-auto">
                        <option value="">Todos</option>
                        <option value="tarjeta">Tarjeta</option>
                        <option value="templos">Templos</option>
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
                        <th style="display: none"></th>
                        <th>Fecha / Hora</th>
                        <th>Centro</th>
                        <th>Cliente</th>
                        <th>Servicio</th>
                        <th>Empleado</th>
                        <th>Euros</th>
                        <th>Tipo Pago</th>            
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php $total_euros=0; 
                    if ($ventas_online!=0) {    
                        foreach ($ventas_online as $row) { ?>
                            <tr>
                                <td style="display: none"><?php echo $row['fecha_hora_concepto_aaaammdd']; ?></td>
                                <td><?php echo $row['fecha_hora_concepto_ddmmaaaa']; ?></td>
                                <td><?php echo $row['nombre_centro']; ?></td>
                                <td><?php echo $row['cliente']; ?></td>
                                <td><?php echo $row['servicio_completo']; ?></td>
                                <td><?php echo $row['empleado']; ?></td>
                                <td class="text-end">
                                    <?php
                                        $total_euros+=$row['importe_euros'];
                                        echo number_format($row['importe_euros'], 2, ',', '.');
                                    ?>€
                                </td>
                                <td><?php // echo $row['tipo_pago']; ?>
                                <?php 
                                    switch ($row['tipo_pago']) {
                                        case '#tarjeta':
                                            $span = '<span class="badge d-block fs-4 badge-success">TARJETA</span>';
                                            break;
                                        case '#templos':
                                            $span = '<span class="badge d-block fs-4 badge-warning">TEMPLOS</span>';
                                            break;
                                        case '#paypal':
                                            $span = '<span class="badge d-block fs-4 badge-info">PAYPAL</span>';
                                            break;
                                        case '#stripe':
                                            $span = '<span class="badge d-block fs-4 badge-primary">STRIPE</span>';
                                            break;
                                        default:
                                            $span = '<span class="badge d-block fs-4 badge-secondary">-</span>';
                                            break;
                                    } 
                                    echo $span; ?>
                                </td>
                            </tr>
                        <?php }
                    } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" style="text-align: right;"><b>Total:</b></td>
                        <td class="text-end"><b><?php echo number_format($total_euros, 2, ',', '.'); ?>€</b></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="alert alert-primary d-flex flex-column flex-sm-row p-5">
            <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">
                <p><?php echo $numero_clientes_registrados; ?> clientes Registrados</p>
                <p><?php echo $numero_clientes_verificados; ?> clientes Verificados</p>
                <p><?php echo $numero_clientes_con_citas_online; ?> clientes que han reservado Cita Online</p>
            </div>
        </div>
    </div>
</div>