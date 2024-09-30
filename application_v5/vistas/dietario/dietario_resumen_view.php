<style>
    .dataTables_filter {
        text-align: right;
    }
</style>
<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title">
        </div>
        <form id="form" action="" role="form" method="post" name="form">
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
                <div class="w-auto">
                    <label for="" class="form-label">Fecha</label>
                    <input type="date" id="fecha" name="fecha" value="<?php if (isset($fecha)) {
                                                                            echo $fecha;
                                                                        } ?>" class="form-control form-control-solid w-auto" placeholder="Fecha" />
                </div>
                <?php if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 3) { ?>
                    <div class="w-auto ms-3">
                        <label for="" class="form-label">Centro:</label>
                        <select name="id_centro" id="id_centro" class="form-select form-select-solid w-auto">
                            <option value="">Todos</option>
                            <?php if (isset($centros_todos)) {
                                if ($centros_todos != 0) {
                                    foreach ($centros_todos as $key => $row) {
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
                    <?php if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 3) { ?>
                        <button type="button" class="btn btn-info btn-icon text-inverse-info" onclick="NuevoDiaFiltroCentro();"><i class="fas fa-search"></i></button>
                    <?php } else { ?>
                        <button type="button" class="btn btn-info text-inverse-info" onclick="NuevoDia();"><i class="fas fa-search"></i></button>
                    <?php } ?>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body pt-6">
        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-6 fw-bold" role="tablist" id="ul_tab_nav">
            <li class="nav-item" role="presentation"><a class="nav-link text-active-primary py-5 me-6 active" aria-selected="true" role="tab" href="#tab1default" data-bs-toggle="tab">Facturación</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link text-active-primary py-5 me-6" aria-selected="false" tabindex="-1" role="tab" href="#tab2default" data-bs-toggle="tab">Movimientos de caja</a></li>
        </ul>
        <div class="tab-content pt-3">
            
            <div class="tab-pane fade active show" id="tab1default">
                <h3 class="fw-bold fs-2x text-grey-700 border-bottom pb-3">Facturación</h3>
                <div class="row">
                    <div class="col-md-4 col-lg-3 col-xl-2">
                        <div class="table-responsive">
                            <table class="align-middle border-danger-subtle fs-6 gx-2 table table-bordered table-row-dashed table-striped">
                                <thead class="">
                                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase">
                                        <tr>
                                            <th class="text-start text-gray-600 fw-bold fs-5 text-uppercase">Total <?=(isset($fecha)) ? date('m-Y', strtotime($fecha)) : ''?></th>
                                            <th class="text-end text-gray-600 fw-bold fs-5 text-uppercase">Importe</th>
                                        </tr>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($totales_mes as $key => $value) { ?>
                                        <tr>
                                            <th class="text-start text-gray-600 fw-bold fs-5 text-uppercase"><?=ucfirst($key)?></th>
                                            <td class="text-end text-body fw-bold"><?=euros( $value)?></td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                    <div class="col-md-8 col-lg-9 col-xl-10">
                        <?php if (isset($facturacion_manana)) {
                            if ($facturacion_manana != 0) { ?>
                                <div class="table-responsive">
                                    <table class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                                        <thead class="">
                                            <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                                                <th>Jornada</th>
                                                <th>Efectivo</th>
                                                <th>Tarjeta</th>
                                                <th>Transferencia</th>
                                                <th>TPV2</th>
                                                <th>Financiado</th>
                                                <?php if ($id_centro == 9) { ?>
                                                    <th>Habitación</th>
                                                <?php } ?>
                                                <th>1ª visitas</th>
                                                <th>H. Cierre</th>
                                                <?php if($id_centro == 3) { ?>
                                                    <th>Psicotécnco</th>
                                                    <th>Tasas</th>
                                                    <th>Análisis</th>
                                                <?php } ?>
                                                <th>Total</th>
                                                <?php /* 
                                                <th>Total mes</th>
                                                */?>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-700 fw-semibold">
                                            <tr>
                                                <td class="fw-bold">Mañana</td>
                                                <td class="text-end text-nowrap">
                                                    <?php echo number_format($total_efectivo_manana, 2, ",", ".") . " €"; ?>
                                                </td>
                                                <td class="text-end text-nowrap">
                                                    <?php echo number_format($total_tarjeta_manana, 2, ",", ".") . " €"; ?>
                                                </td>
                                                <td class="text-end text-nowrap">
                                                    <?php echo number_format($total_transferencia_manana, 2, ",", ".") . " €"; ?>
                                                </td>
                                                <td class="text-end text-nowrap">
                                                    <?php echo number_format($total_tpv2_manana, 2, ",", ".") . " €"; ?>
                                                </td>
                                                <td class="text-end text-nowrap">
                                                    <?php echo number_format($total_financiado_manana, 2, ",", ".") . " €"; ?>
                                                </td>
                                                <?php if ($id_centro == 9) { ?>
                                                    <td class="text-end text-nowrap">
                                                        <?php echo number_format($total_habitacion_manana, 2, ",", ".") . " €"; ?>
                                                    </td>
                                                <?php } ?>
                                                <td class="text-end text-nowrap">
                                                    <?php echo $primerascitas_manana; ?>
                                                </td>
                                                <td class="text-end text-nowrap">
                                                    <?php if(isset($cierres_manana) && $cierres_manana != 0 && is_array($cierres_manana)){
                                                        foreach ($cierres_manana as $key => $value) {
                                                        echo '<span class="badge badge-primary">'.$value['hora'].'</span>';
                                                        }                                            
                                                    }else{
                                                        echo '-';
                                                    }?>
                                                </td>
                                                <?php if($id_centro == 3) { ?>
                                                    <td class="text-end text-nowrap"><?=$psico_servicios_manana?> / <?=euros($psico_valor_manana)?></td>
                                                    <td class="text-end text-nowrap"><?=$tasa_servicios_manana?> / <?=euros($tasa_valor_manana)?></td>
                                                    <td class="text-end text-nowrap"><?=$analisis_servicios_manana?> / <?=euros($analisis_valor_manana)?></td>
                                                <?php } ?>
                                                <td class="text-end text-nowrap">
                                                    <?php echo number_format($total_manana, 2, ",", ".") . " €"; ?>
                                                </td>
                                                <?php /* 
                                                <td class="text-end text-nowrap">
                                                    <?php echo number_format($facturacion_mes_manana, 2, ",", ".") . " €"; ?>
                                                </td>
                                                */ ?>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Tarde</td>
                                                <td class="text-end text-nowrap">
                                                    <?php echo number_format($total_efectivo_tarde, 2, ",", ".") . " €"; ?>
                                                </td>
                                                <td class="text-end text-nowrap">
                                                    <?php echo number_format($total_tarjeta_tarde, 2, ",", ".") . " €"; ?>
                                                </td>
                                                <td class="text-end text-nowrap">
                                                    <?php echo number_format($total_transferencia_tarde, 2, ",", ".") . " €"; ?>
                                                </td>
                                                <td class="text-end text-nowrap">
                                                    <?php echo number_format($total_tpv2_tarde, 2, ",", ".") . " €"; ?>
                                                </td>
                                                <td class="text-end text-nowrap">
                                                    <?php echo number_format($total_financiado_tarde, 2, ",", ".") . " €"; ?>
                                                </td>
                                                <?php if ($id_centro == 9) { ?>
                                                    <td class="text-end text-nowrap">
                                                        <?php echo number_format($total_habitacion_tarde, 2, ",", ".") . " €"; ?>
                                                    </td>
                                                <?php } ?>
                                                <td class="text-end text-nowrap">
                                                    <?php echo $primerascitas_tarde; ?>
                                                </td>
                                                <td class="text-end text-nowrap">
                                                    <?php if(isset($cierres_tarde) && $cierres_tarde != 0 && is_array($cierres_tarde)){
                                                        foreach ($cierres_tarde as $key => $value) {
                                                        echo '<span class="badge badge-primary">'.$value['hora'].'</span>';
                                                        }                                            
                                                    }else{
                                                        echo '-';
                                                    }?>
                                                </td>
                                                <?php if($id_centro == 3) { ?>
                                                    <td class="text-end text-nowrap"><?=$psico_servicios_tarde?> / <?=euros($psico_valor_tarde)?></td>
                                                    <td class="text-end text-nowrap"><?=$tasa_servicios_tarde?> / <?=euros($tasa_valor_tarde)?></td>
                                                    <td class="text-end text-nowrap"><?=$analisis_servicios_tarde?> / <?=euros($analisis_valor_tarde)?></td>
                                                <?php } ?>
                                                <td class="text-end text-nowrap">
                                                    <?php echo number_format($total_tarde, 2, ",", ".") . " €"; ?>
                                                </td>
                                                <?php /* 
                                                <td class="text-end text-nowrap">
                                                    <?php echo number_format($facturacion_mes_tarde, 2, ",", ".") . " €"; ?>
                                                </td>
                                                */?>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Total</td>
                                                <td class="text-end text-nowrap">
                                                    <?php echo number_format($total_facturacion_efectivo, 2, ",", ".") . " €"; ?>
                                                </td>
                                                <td class="text-end text-nowrap">
                                                    <?php echo number_format($total_facturacion_tarjeta, 2, ",", ".") . " €"; ?>
                                                </td>
                                                <td class="text-end text-nowrap">
                                                    <?php echo number_format($total_facturacion_transferencia, 2, ",", ".") . " €"; ?>
                                                </td>
                                                <td class="text-end text-nowrap">
                                                    <?php echo number_format($total_facturacion_tpv2, 2, ",", ".") . " €"; ?>
                                                </td>
                                                <td class="text-end text-nowrap">
                                                    <?php echo number_format($total_facturacion_financiado, 2, ",", ".") . " €"; ?>
                                                </td>
                                                <?php if ($id_centro == 9) { ?>
                                                    <td class="text-end text-nowrap">
                                                        <?php echo number_format($total_facturacion_habitacion, 2, ",", ".") . " €"; ?>
                                                    </td>
                                                <?php } ?>
                                                <td class="text-end text-nowrap">
                                                    <?php echo $primerascitas_manana + $primerascitas_tarde; ?>
                                                </td>
                                                <td class="text-end text-nowrap"></td>
                                                <?php if($id_centro == 3) { ?>
                                                    <td class="text-end text-nowrap"><?=$psico_servicios_tarde + $psico_servicios_manana?> / <?=euros($psico_valor_tarde + $psico_valor_manana)?></td>
                                                    <td class="text-end text-nowrap"><?=$tasa_servicios_tarde + $tasa_servicios_manana?> / <?=euros($tasa_valor_tarde + $tasa_valor_manana)?></td>
                                                    <td class="text-end text-nowrap"><?=$analisis_servicios_tarde + $analisis_servicios_manana?> / <?=euros($analisis_valor_tarde + $analisis_valor_manana)?></td>
                                                <?php } ?>
                                                <td class="text-end text-nowrap">
                                                    <?php echo number_format($total_facturacion, 2, ",", ".") . " €"; ?>
                                                </td>
                                                <?php /* 
                                                <td class="text-end text-nowrap">
                                                    <?php echo number_format(($facturacion_mes_manana + $facturacion_mes_tarde), 2, ",", ".") . " €"; ?>
                                                </td>
                                                */ ?>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } else { ?>
                                <div class="alert alert-primary d-flex flex-column flex-sm-row p-5 mb-10">Sin datos</div>
                        <?php }
                        } ?>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab2default">
                <h3 class="fw-bold fs-2x text-grey-700 border-bottom pb-3">Movimientos de caja</h3>
                <?php if (isset($movimientos_caja)) {
                    if ($movimientos_caja != 0) { ?>
                        <div class="table-responsive">
                            <table id="movimientos_caja_table" class="table table-row-bordered table-row-gray-100 align-middle table-bordered">
                                <thead class="">
                                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                                        <th style="width: 65%;">Concepto</th>
                                        <th style="width: 45%;">Importe</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700 fw-semibold">
                                    <?php if (isset($movimientos_caja)) {
                                        if ($movimientos_caja != 0) {
                                            foreach ($movimientos_caja as $key => $row) { ?>
                                                <tr>
                                                    <td>
                                                        <?php if ($row['cantidad'] > 0) { ?>
                                                            <span class="glyphicon glyphicon-triangle-left btn btn-primary text-inverse-primary" aria-hidden="true"></span>
                                                        <?php } else { ?>
                                                            <span class="glyphicon glyphicon-flash glyphicon btn red" aria-hidden="true"></span>
                                                        <?php } ?>
                                                        <?php echo $row['concepto'] ?>
                                                    </td>
                                                    <td style="width: 100px; text-align: right;">
                                                        <?php echo number_format($row['cantidad'], 2, ',', '.') . " €"; ?>
                                                        <?= ($row['cantidad'] > 0) ? '(añadidos)' : '(sacados)' ?>
                                                    </td>
                                                </tr>
                                    <?php }
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } else { ?>
                        <div class="alert alert-primary d-flex flex-column flex-sm-row p-5 mb-10">Sin movimientos</div>
                <?php }
                } ?>
            </div>
        </div>
    </div>
</div>
<script>
    function NuevoDia() {
        document.location.href = "<?php echo base_url(); ?>dietario/resumen/" + document.getElementById("fecha").value;
    }

    function NuevoDiaFiltroCentro() {
        document.location.href = "<?php echo base_url(); ?>dietario/resumen/" + document.getElementById("fecha").value + "/" + document.getElementById("id_centro").value;
    }
</script>