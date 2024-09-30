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
                <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="dietario">
            </div>
        </div>
        <form id="form" action="<?php echo RUTA_WWW ?>/dietario/recibos/buscar" role="form" method="post" name="form">
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
                <div class="w-auto">
                    <label for="" class="form-label">Fecha desde</label>
                    <input type="date" id="fecha" name="fecha_desde" value="<?php if (isset($fecha_desde)) {echo $fecha_desde;} ?>" class="form-control form-control-solid w-auto" placeholder="Fecha desde" />
                </div>
                <div class="w-auto ms-3">
                    <label for="" class="form-label">Fecha hasta</label>
                    <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?php if (isset($fecha_hasta)) {echo $fecha_hasta; } ?>" class="form-control form-control-solid w-auto" placeholder="Fecha hasta" />
                </div>
                <div class="w-auto ms-3">
                    <label for="" class="form-label">Pacientes</label>
                    <input type="text" id="buscar" name="buscar" value="<?php if (isset($buscar)) {echo $buscar; } ?>" class="form-control form-control-solid w-auto" placeholder="Buscar paciente" />
                </div>
                <div class="w-auto  ms-3">
                    <button type="submit" class="btn btn-info btn-icon text-inverse-info" onclick="Buscar();"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">
            <table id="dietario" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th style="display: none;"></th>
                        <th>Nº Recibo</th>
                        <th>Fecha Recibo</th>
                        <th>Centro</th>
                        <th>Paciente</th>
                        <th>Base</th>
                        <th>Descuento</th>
                        <th>IVA</th>
                        <th>Importe</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php if (isset($recibos)) {
                        if ($recibos != 0) {
                            $total_importe = 0;
                            $total_descuento = 0;
                            $total_iva = 0;
                            $total_general = 0;
                            foreach ($recibos as $key => $row) { ?>
                                <tr>
                                    <td style="display: none;"  data-order="<?php echo $row['id_recibo'] ?>">
                                        <?php echo $row['numero_recibo'] ?>
                                    </td>
                                    <td>
                                        <a class="btn btn-secondary btn-sm btn-text d-inline-flex align-items-center" href="<?php echo RUTA_WWW ?>/dietario/ver_recibo/<?php echo $row['id_recibo'] ?>" target="_blank"><?php echo $row['numero_recibo'] ?></a>
                                    </td>
                                    <td><?php echo $row['fecha_emision_ddmmaaaa'] ?></td>
                                    <td><?php echo $row['nombre_centro'] ?> </td>
                                    <td><?php echo $row['cliente'] ?></td>
                                    <td><?php echo number_format($row['importe'], 2, ',', '.'); ?>€<?php $total_importe += $row['importe']; ?></td>
                                    <td>
                                        <?php echo number_format($row['descuento'], 2, ',', '.'); ?>€
                                        <?php $total_descuento += $row['descuento']; ?>
                                        <span class="text-muted fw-semibold text-muted d-block fs-7">(<?php echo number_format($row['descuento_porcentaje'], 2, ',', '.'); ?>%)</span>
                                    </td>
                                    <td>
                                        <?php echo number_format($row['iva'], 2, ',', '.'); ?>€
                                        <?php $total_iva += $row['iva']; ?>
                                        <span class="text-muted fw-semibold text-muted d-block fs-7">(<?php echo number_format($row['iva_porcentaje'], 2, ',', '.'); ?>%)</span>
                                    </td>
                                    <td>
                                        <?php echo number_format($row['total'], 2, ',', '.'); ?>€
                                        <?php $total_general += $row['total']; ?>
                                    </td>
                                </tr>
                            <?php }
                        }
                    } ?>
                </tbody>
                <?php if (isset($facturas)) {
                    if ($facturas != 0) { ?>
                        <?php if (isset($fecha_desde)) { ?>
                            <tfoot>
                                <tr>
                                    <td class="text-right" colspan="4">TOTALES</td>
                                    <td class="text-right"><?php echo number_format($total_importe, 2, ',', '.'); ?>€</td>
                                    <td class="text-right"><?php echo number_format($total_descuento, 2, ',', '.'); ?>€</td>
                                    <td class="text-right"><?php echo number_format($total_iva, 2, ',', '.'); ?>€</td>
                                    <td class="text-right"><?php echo number_format($total_general, 2, ',', '.'); ?>€</td>
                                </tr>
                            </tfoot>
                        <?php } ?>
                    <?php }
                } ?>
            </table>
        </div>
    </div>
</div>
<script>
    function Informe() {
        document.getElementById("form").action = "<?php echo RUTA_WWW ?>/dietario/informe_facturas";
        document.getElementById("form").submit();
    }
    function Buscar() {
        document.getElementById("form").action = "<?php echo RUTA_WWW ?>/dietario/recibos/buscar";
        document.getElementById("form").submit();
    }
</script>