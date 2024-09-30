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
        <form id="form_estadisticas" action="<?php echo base_url();?>estadisticas/carnetsinpasarcaja" role="form" method="post" name="form_estadisticas">
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
                    <label for="" class="form-label">Empleado:</label>
                    <select name="id_usuario" id="id_usuario" data-control="select2" class="form-select form-select-solid w-auto">
                        <option value="0">Todos los empleados</option>
                        <?php if (isset($empleados)) {
                            if ($empleados != 0) {
                                foreach ($empleados as $key => $row) { ?>
                                    <option value='<?php echo $row['id_usuario']; ?>' <?php if (isset($id_usuario)) {if ($row['id_usuario'] == $id_usuario) {echo "selected";}} ?>>
                                        <?php echo strtoupper($row['apellidos'] . ", " . $row['nombre'] . " (" . $row['nombre_centro'] . ")"); ?>
                                        </option>
                                <?php }
                            }
                        } ?>
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
                        <th style="display: none;">ID</th>
                        <th>Carnet</th>
                        <th>Fecha</th>
                        <th>Empleado</th>
                        <th>Cliente</th>
                        <th>Templos disponibles</th>
                        <th>Código</th>                
                        <th>Notas</th>            
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php if (isset($registros)) {
                        if ($registros != 0) {
                            foreach ($registros as $key => $row) { ?>
                                <tr>
                                    <td style="display: none;"><?php echo $row['fecha_aaaammdd_vendido']; ?></td>
                                    <td>
                                        <a class="btn btn-secondary btn-sm btn-text d-inline-flex align-items-center" href="<?php echo base_url();?>carnets/detalle/gestion/<?php echo $row['id_carnet']; ?>" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Ver detalle del Carnet"><i class="fas fa-id-card"></i> <?php echo $row['codigo']; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $row['fecha_vendido_abrev']; ?></td>
                                    <td><?php echo $row['empleado']; ?></td>
                                    <td><?php echo $row['cliente']; ?></td>                                          
                                    <td><?=($row['id_tipo']==99) ? 'Servicios' :  round($row['templos_disponibles'],2)?>
                                    </td>
                                    <td><?php echo $row['codigo_pack_online']; ?></td>
                                    <td><?php echo $row['notas']; ?></td>                        
                                </tr>
                            <?php }
                        }
                    } ?>                
                </tbody>
            </table>
        </div>
    </div>
</div>