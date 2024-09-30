<div class="card card-flush">
    <div class="card-body border-bottom">
        <form id="form_intercentros" action="<?php echo base_url(); ?>comparativa" role="form" method="post" name="form_intercentros" class="form-horizontal">
            <div class="row align-items-center">
                <div class="col-md-5">
                    <div class="border p-4">
                        <legend>Intervalo 1</legend>

                        <label class="form-label">Fecha desde 1</label>
                        <input type="date" class="form-control form-control-solid" id="fecha_desde_1" name="fecha_desde_1" placeholder="desde" value="<?php echo $fecha_desde; ?>">

                        <label class="form-label mt-5">Fecha hasta 1</label>
                        <input type="date" class="form-control form-control-solid" id="fecha_hasta_1" name="fecha_hasta_1" placeholder="hasta" value="<?php echo $fecha_hasta; ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary text-inverse-primary">Comparar</button>
                </div>
                <div class="col-md-5">
                    <div class="border p-4">
                        <legend>Intervalo 2</legend>

                        <label class="form-label">Fecha desde 2</label>
                        <input type="date" class="form-control form-control-solid" id="fecha_desde_2" name="fecha_desde_2" placeholder="desde" value="<?= (isset($fecha_desde_2)) ? $fecha_desde_2 : '' ?>">

                        <label class="form-label mt-5">Fecha hasta 2</label>
                        <input type="date" class="form-control form-control-solid" id="fecha_hasta_2" name="fecha_hasta_2" placeholder="hasta" value="<?= (isset($fecha_hasta_2)) ? $fecha_hasta_2 : '' ?>">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="comparativa">
            </div>
        </div>
    </div>

    <div class="card-body pt-6">
        <div class="table-responsive">
            <table id="comparativa" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th>#</th>
                        <?php foreach ($centros as $key => $value) { ?>
                            <th style="text-align: center;"><?php echo $value['nombre_centro']; ?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php if (isset($tr_rows)) {
                        $i = 0;
                        foreach ($tr_rows as $key => $value) { ?>
                            <?php if (isset($fecha_desde_2)) { ?>
                                <tr>
                                    <td style="text-align: left;" rowspan="3"><strong><?php echo $key; ?></strong></td>
                                    <?php foreach ($value as $k => $dato) { ?>
                                        <td><?php echo $dato[0]; ?></td>
                                    <?php } ?>
                                </tr>
                                <tr>
                                    <?php foreach ($value as $k => $dato) { ?>
                                        <td style="color: red"><?php echo $dato[1]; ?></td>
                                    <?php } ?>
                                </tr>
                                <tr>
                                    <?php foreach ($value as $k => $dato) { ?>
                                        <td>
                                            <?php if (($dato[2][0] == '-') && (isset($dato[2][1])) && ($dato[2] != '- | -')) {
                                                echo '<span class="badge" style="background-color:red; color:#FFF;font-weight:bold;">' . $dato[2] . '</span>';
                                            } elseif ((isset($dato[2][1])) && ($dato[2] != '- | -')) {
                                                echo '<span class="badge" style="background-color:#0cef0c; color:#FFF;font-weight:bold;">' . $dato[2] . '</span>';
                                            } else {
                                                echo '';
                                            }
                                            ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                                <tr>
                                    <td colspan="<?php echo count('centros') + 1; ?>"></td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <td style="text-align: left;"><strong><?php echo $key; ?></strong></td>
                                    <?php foreach ($value as $k => $dato) { ?>
                                        <td><?php echo $dato[0]; ?></td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>