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
    </div>
    <div class="card-body pt-6">
        <span class="caption-subject bold uppercase">Venta de Productos realizada por <?php echo $usuario[0]['nombre'] . " " . $usuario[0]['apellidos']; ?></span>
        <div class="table-responsive">
            <table id="logs" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th style="display: none;">fecha</th>
                        <th>Fecha / Hora</th>
                        <th>Producto</th>
                        <th>Ventas de Productos</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php $total = 0;
                    if (isset($registros)) {
                        if ($registros != 0) {
                            foreach ($registros as $key => $row) { ?>
                                <tr>
                                    <td style="display: none;">
                                        <?php echo $row['fecha_hora_aaaammdd']; ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php echo $row['fecha_hora_ddmmaaaa']; ?>
                                    </td>
                                    <td style="text-align: center; vertical-align: middle">
                                        <?php echo $row['nombre_familia'] . " " . $row['nombre_producto']; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php echo number_format($row['venta_productos'], 2, ',', '.');
                                        $total += $row['venta_productos']; ?> €
                                    </td>
                                </tr>
                    <?php }
                        }
                    } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td class="text-end">TOTAL</td>
                        <td class="text-end">
                            <?php echo number_format($total, 2, ',', '.'); ?> €
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>