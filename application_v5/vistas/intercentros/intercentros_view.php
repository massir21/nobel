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
        <form id="form_intercentros" action="<?php echo base_url(); ?>intercentros" role="form" method="post" name="form_intercentros">
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
                <div class="w-auto">
                    <label for="" class="form-label">Fecha desde</label>
                    <input type="date" id="fecha" name="fecha_desde" value="<?php if (isset($fecha_desde)) {
                                                                                echo $fecha_desde;
                                                                            } ?>" class="form-control form-control-solid w-auto" placeholder="Fecha desde" required />
                </div>
                <div class="w-auto ms-3">
                    <label for="" class="form-label">Fecha hasta</label>
                    <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?php if (isset($fecha_hasta)) {
                                                                                        echo $fecha_hasta;
                                                                                    } ?>" class="form-control form-control-solid w-auto" placeholder="Fecha hasta" required />
                </div>
                <div class="w-auto  ms-3">
                    <button type="submit" class="btn btn-info btn-icon text-inverse-info"><i class="fas fa-search"></i></button>
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
                                            <option value='<?php echo $row['id_centro']; ?>' <?php if (isset($id_centro)) {
                                                                                                    if ($row['id_centro'] == $id_centro) {
                                                                                                        echo "selected";
                                                                                                    }
                                                                                                } ?>>
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
    <div class="card-body pt-6">
        <?php if (isset($centros)) {
            if ($centros != 0) {
                foreach ($centros as $key => $c) {
                    if ($c['id_centro'] != $id_centro) { ?>
                        <h4><b><?php echo $c['nombre_centro']; ?></b></h4>
                        <table class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                            <thead class="">
                                <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                                    <th style="display: none;">ID</th>
                                    <th style="width: 15%;">Fecha Hora</th>
                                    <th style="width: 20%;">Servicio</th>
                                    <th style="width: 10%;">Templos</th>
                                    <th style="width: 15%;">Carnet</th>
                                    <th style="width: 15%;">Original de</td>
                                    <th style="width: 15%;">Usado en</th>
                                    <th style="width: 10%; text-align: right;">Total</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 fw-semibold">
                                <?php $total = 0; ?>
                                <?php if (isset($registros)) {
                                    if ($registros != 0) {
                                        foreach ($registros as $key => $row) { ?>
                                            <?php if ($row['id_centro'] == $c['id_centro']) { ?>
                                                <tr>
                                                    <td style="display: none;">
                                                        <?php echo $row['fecha_hora_concepto']; ?>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <?php echo $row['fecha'] . " " . $row['hora']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['servicio']; ?>
                                                    </td>
                                                    <td class="text-end">
                                                        <?php echo $row['templos']; ?>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <?php echo $row['codigo']; ?>
                                                    </td>
                                                    <td style="text-align: left;">
                                                        <?php echo $row['original_de']; ?>
                                                    </td>
                                                    <td style="text-align: left;">
                                                        <?php echo $row['usado_en']; ?>
                                                    </td>
                                                    <td class="text-end">
                                                        <?php if ($row['total_sin_recargas'] > 0 && $row['total_sin_recargas'] < $row['total']) { ?>
                                                            <?php $total += $row['total_sin_recargas'];
                                                            echo number_format($row['total_sin_recargas'], 2, ',', '.'); ?> €
                                                        <?php } else { ?>
                                                            <?php $total += $row['total'];
                                                            echo number_format($row['total'], 2, ',', '.'); ?> €
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                <?php }
                                    }
                                } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6"></td>
                                    <td style="text-align: right; padding: 8px; background: #888; color: #fff;">
                                        <?php echo number_format($total, 2, ',', '.'); ?> €
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    <?php }
                }
            }
        } ?>
    </div>
</div>