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
                <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="estadistica_usuarios">
            </div>
        </div>
        <form id="form_estadisticas" action="<?php echo base_url();?>estadisticas" role="form" method="post" name="form_estadisticas">
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
                <div class="w-auto">
                    <label for="" class="form-label">Fecha desde</label>
                    <input type="date" id="fecha" name="fecha_desde" value="<?php if (isset($fecha_desde)) {echo $fecha_desde;} ?>" class="form-control form-control-solid w-auto" placeholder="Fecha desde" required/>
                </div>
                <div class="w-auto ms-3">
                    <label for="" class="form-label">Fecha hasta</label>
                    <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?php if (isset($fecha_hasta)) {echo $fecha_hasta; } ?>" class="form-control form-control-solid w-auto" placeholder="Fecha hasta" required/>
                </div>
                <?php if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 4) { ?>
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
                <?php } ?>
                <div class="w-auto  ms-3">
                    <button type="submit" class="btn btn-info btn-icon text-inverse-info"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body d-flex flex-column-reverse pt-6">
        <div class="table-responsive">
            <table id="estadistica_usuarios" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th style="display: none;">ID</th>
                        <th>Nombre</th>            
                        <th>Centro</th>
                        <th>Perfil</th>
                        <th>Venta<br>Productos</th>
                        <th>Total<br>Efectivo</th>
                        <th>Total<br>Tarjeta</th>
                        <th>Total<br>Transferencia</th>
                        <?php if ($id_centro == "" || $id_centro == 9) { ?>
                        <th>Total<br>Habitación</th>
                        <?php } ?>
                        <th>Total<br>Ventas</th>         
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php 
                    $total_horas_trabajadas=0;
                    $total_templos=0;
                    $total_ventas_productos=0;
                    $total_ventas=0;
                    $total_ventas_efectivo=0;
                    $total_ventas_tarjeta=0;
                    $total_ventas_transferencia=0;
                    $total_ventas_habitacion=0;
                    $total_ventas_proveedores=0;
                    $total_tiempo_jornada=0;?>
                    <?php if (isset($registros)) {
                        if ($registros != 0) {
                            foreach ($registros as $key => $row) { ?>
                                <?php if ($row['horas_trabajadas']>0 || $row['templos']>0 || $row['ventas_productos']!=0 || $row['ventas']!=0 || $row['ventas_tarjeta']!=0 || $row['ventas_habitacion']!=0 || $row['ventas_proveedores']!=0) { ?>
                                    <tr>
                                        <td style="display: none;"><?php echo $row['id_usuario'] ?></td>
                                        <td><?php echo $row['nombre']." ".$row['apellidos'] ?></td>            
                                        <td><?php echo $row['nombre_centro'] ?></td>
                                        <td><?php echo $row['nombre_perfil'] ?></td>
                                        <td class="text-end">
                                            <?php if ($row['ventas_productos']!=0) { ?>
                                                <a href="<?php echo base_url();?>estadisticas/gestion/ventasproductos/<?php echo $row['id_usuario'] ?>/<?php echo $row['id_centro'] ?>/<?php echo $fecha_desde; ?>/<?php echo $fecha_hasta; ?>" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Ver detalle de los productos vendidos" class="btn btn-sm btn-icon btn-info">
                                                    <i class="fas fa-list-alt"></i>
                                                </a>
                                                <?php echo number_format($row['ventas_productos'], 2, ',', '.'); ?> €
                                                <?php $total_ventas_productos+=$row['ventas_productos']; ?>
                                            <?php } else { echo "-"; } ?>                
                                        </td>
                                        <td class="text-end">
                                            <?php if ($row['ventas_efectivo']!=0) { ?>
                                                <a href="<?php echo base_url();?>estadisticas/gestion/ventas/<?php echo $row['id_usuario'] ?>/<?php echo $row['id_centro'] ?>" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Ver detalle de las ventas realizadas" class="btn btn-sm btn-icon btn-secondary">
                                                    <i class="fas fa-list"></i>
                                                </a>
                                                <?php echo number_format($row['ventas_efectivo'], 2, ',', '.'); ?> €
                                                <?php $total_ventas_efectivo+=$row['ventas_efectivo']; ?>
                                            <?php } else { echo "-"; } ?>
                                        </td>
                                        <td class="text-end">
                                            <?php if ($row['ventas_tarjeta']!=0) { ?>
                                                <?php echo number_format($row['ventas_tarjeta'], 2, ',', '.'); ?> €
                                                <?php $total_ventas_tarjeta+=$row['ventas_tarjeta']; ?>
                                            <?php } else { echo "-"; } ?>
                                        </td>
                                        <td class="text-end">
                                            <?php if ($row['ventas_transferencia']!=0) { ?>
                                                <?php echo number_format($row['ventas_transferencia'], 2, ',', '.'); ?> €
                                                <?php $total_ventas_transferencia+=$row['ventas_transferencia']; ?>
                                            <?php } else { echo "-"; } ?>
                                        </td>
                                        <?php if ($id_centro == "" || $id_centro == 9) { ?>
                                            <td class="text-end">
                                                <?php if ($row['ventas_habitacion']!=0) { ?>
                                                    <?php echo number_format($row['ventas_habitacion'], 2, ',', '.'); ?> €
                                                    <?php $total_ventas_habitacion+=$row['ventas_habitacion']; ?>
                                                <?php } else { echo "-"; } ?>
                                            </td>
                                        <?php } ?>
                                        <td class="text-end">
                                            <?php if ($row['ventas']!=0) { ?>
                                                <?php echo number_format($row['ventas'], 2, ',', '.'); ?> €
                                                <?php $total_ventas+=$row['ventas']; ?>
                                            <?php } else { echo "-"; } ?>
                                        </td>     
                                    </tr>
                                <?php }
                            }
                        }
                    } ?>
                </tbody>        
                <tfoot>
                    <tr>
                        <td class="text-end"></td>
                        <td class="text-end"></td>
                        <td class="text-end"></td>
                        <td class="text-end"><?php echo number_format($total_ventas_productos, 2, ',', '.'); ?> €</td>            
                        <td class="text-end"><?php echo number_format($total_ventas_efectivo, 2, ',', '.'); ?> €</td>
                        <td class="text-end"><?php echo number_format($total_ventas_tarjeta, 2, ',', '.'); ?> €</td>
                        <td class="text-end"><?php echo number_format($total_ventas_transferencia, 2, ',', '.'); ?> €</td>
                        <?php if ($id_centro == "" || $id_centro == 9) { ?>
                            <td class="text-end"><?php echo number_format($total_ventas_habitacion, 2, ',', '.'); ?> €</td>
                        <?php } ?>            
                        <td class="text-end"><?php echo number_format($total_ventas, 2, ',', '.'); ?> €</td>        
                    </tr>          
                </tfoot>
            </table>
        </div>
        <?php if ($total_horas_trabajadas == 0) { $total_horas_trabajadas=1; } ?>
        <?php if ($total_tiempo_jornada == 0) { $total_tiempo_jornada=1; } ?>
        <div class="alert alert-primary d-flex flex-column flex-sm-row p-5">
            <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">
                <p><?php echo str_replace(",0", "", (string)number_format((($total_templos)/$total_horas_trabajadas), 1, ",", ".")); ?> Templos por hora trabajada</p>
            <p><?php echo str_replace(",0", "", (string)number_format((($total_ventas)/$total_horas_trabajadas), 1, ",", ".")); ?> € Media ventas por hora trabajada</p>
            <p><?php echo str_replace(",0", "", (string)number_format((($total_templos)/$total_tiempo_jornada), 1, ",", ".")); ?> Templos por hora toda la jornada</p>
            <p class="mb-0"><?php echo str_replace(",0", "", (string)number_format((($total_ventas)/$total_tiempo_jornada), 1, ",", ".")); ?> € Media ventas por hora toda la jornada</p>
        </div>
    </div>
</div>