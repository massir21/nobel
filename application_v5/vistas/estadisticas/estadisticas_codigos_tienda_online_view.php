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
        <form id="form_estadisticas" action="<?php echo base_url();?>estadisticas/codigo_tienda_online" role="form" method="post" name="form_estadisticas">
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
                <div class="w-auto  ms-3">
                    <button type="submit" class="btn btn-info btn-icon text-inverse-info"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">
            <?php $total_euros = 0;?>
            <table id="logs" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th style="display: none"></th>              
                        <th>Nº Carnet</th>
                        <th>Fecha Venta</th>
                        <th>Cliente</th>
                        <th>Código Online</th>
                        <th>Precio Total</th>
                        <th>Centro Validación</th>
                        <th>Empleado Validación</th>            
                    </tr>
                </thead>
            <tbody class="text-gray-700 fw-semibold">
                <?php if ($ventas_online!=0) {
                    $total_euros=0;
                    foreach ($ventas_online as $row) {?>
                        <tr>
                            <td style="display: none"><?php echo $row['codigo']; ?></td>
                            <td>
                                <a class="btn btn-secondary btn-sm btn-text d-inline-flex align-items-center" href="<?php echo base_url();?>carnets/detalle/gestion/<?php echo $row['id_carnet']; ?>" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Ver detalle del Carnet"><i class="fas fa-id-card"></i> <?php echo $row['codigo']; ?></a>
                            </td>
                            <td><?php echo $row['fecha_vendido']; ?></td>
                            <td><?php echo $row['cliente']; ?></td>
                            <td><?php echo $row['codigo_tienda']; ?></td>            
                            <td class="text-end">
                                <?php echo number_format($row['precio'], 2, ',', '.');?>€
                                <?php $total_euros+=$row['precio'];?>
                            </td>
                            <td><?php echo $row['nombre_centro_generado']; ?></td>
                            <td><?php echo $row['empleado']; ?></td>
                        </tr>
                    <?php } 
                }?>
            </tbody>  
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end">Total:</td>
                    <td class="text-end"><?php echo number_format($total_euros, 2, ',', '.'); ?>€</td>            
                </tr>
            </tfoot>
        </table>
    </div>
</div>