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
                <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="myTable5">
            </div>
        </div>
    </div>
    <div class="card-body pt-6">
        <span class="caption-subject bold uppercase">Total de Ventas realizadas por <?php echo $usuario[0]['nombre'] . " " . $usuario[0]['apellidos']; ?></span>
        <div class="table-responsive">
            <table id="myTable5" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th style="display: none;">fecha</th>
                        <th>Año</th>
                        <th>Mes</th>
                        <th>Ventas Efectivo</th>
                        <th>Ventas Tarjeta</th>
                        <th>Ventas Transferencia</th>
                        <?php if ($id_centro_uusuario_estadistica == 9) { ?>
                            <th>Ventas Habitación</th>
                        <?php } ?>
                        <th>Total Ventas</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php if (isset($registros)) {
                        if ($registros != 0) {
                            foreach ($registros as $key => $row) { ?>
                                <tr>
                                    <td style="display: none;">
                                        <?php echo $row['fecha']; ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php echo $row['anno']; ?>
                                    </td>
                                    <td style="text-align: center; vertical-align: middle">
                                        <?php echo $row['mes']; ?>
                                    </td>
                                    <td style="text-align: center; vertical-align: middle">
                                        <?php echo number_format($row['ventas_efectivo'], 2, ',', '.'); ?> €
                                    </td>
                                    <td style="text-align: center; vertical-align: middle">
                                        <?php echo number_format($row['ventas_tarjeta'], 2, ',', '.'); ?> €
                                    </td>
                                    <td style="text-align: center; vertical-align: middle">
                                        <?php echo number_format($row['ventas_transferencia'], 2, ',', '.'); ?> €
                                    </td>
                                    <?php if ($id_centro_uusuario_estadistica == 9) { ?>
                                        <td style="text-align: center; vertical-align: middle">
                                            <?php echo number_format($row['ventas_habitacion'], 2, ',', '.'); ?> €
                                        </td>
                                    <?php } ?>
                                    <td style="text-align: center; vertical-align: middle">
                                        <?php echo number_format($row['total_ventas'], 2, ',', '.'); ?> €
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