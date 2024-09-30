<style>            
    .dataTables_filter {
        text-align: right;        
    }
</style>
<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title"></div>
        <form id="form_estadisticas" action="<?php echo base_url();?>estadisticas/tpv" role="form" method="post" name="form_estadisticas">
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
                <div class="w-auto">
                    <label for="" class="form-label">Fecha desde</label>
                    <input type="date" id="fecha" name="fecha_desde" value="<?php if (isset($fecha_desde)) {echo $fecha_desde;} ?>" class="form-control form-control-solid w-auto" placeholder="Fecha desde" required />
                </div>
                <div class="w-auto ms-3">
                    <label for="" class="form-label">Fecha hasta</label>
                    <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?php if (isset($fecha_hasta)) {echo $fecha_hasta;} ?>" class="form-control form-control-solid w-auto" placeholder="Fecha hasta" required />
                </div>
                <div class="w-auto  ms-3">
                    <button type="submit" class="btn btn-info btn-icon text-inverse-info"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
	</div>
	<div class="card-body pt-6">
        <div class="border-bottom mb-5 w-md-75 w-lg-50">    
            <div class="card-title mb-4"><h3>Pagos por TPV en Centros</h3></div>
            <?php if ($pagos_tpv != 0) { $total=0; ?>
            <table class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th>Centro</th>
                        <th class="text-right">Facturación</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php foreach ($pagos_tpv as $row) { ?>
                        <tr>
                            <td>
                                <?php echo $row['nombre_centro']; ?>
                            </td>
                            <td class="text-right">
                                <?php echo number_format($row['facturacion'], 2, ',', '.'); ?> €
                                <?php $total += $row['facturacion']; ?>
                            </td>                
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot class="border border-top-3 fs-4">
                    <tr>
                        <td class="text-right"></td>
                        <td class="text-right fw-bold">
                            <?php echo number_format($total, 2, ',', '.'); ?> €                    
                        </td>                
                    </tr>
                </tfoot>
            </table>
            <?php } else { ?>
                <p>No hay datos actualmente</p>
            <?php } ?>
        </div>
        <div class="border-bottom mb-5 w-md-75 w-lg-50">    
            <div class="card-title mb-4"><h3>Pagos por TPV (Reservas OnLine)</h3></div>
            <?php if ($pagos_tpv_online != 0) { $total=0; ?>
                <table class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                    <thead>
                        <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                            <th>Centro</th>
                            <th class="text-right">Facturación</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php foreach ($pagos_tpv_online as $row) { ?>
                            <tr>
                                <td>
                                    <?php echo $row['nombre_centro']; ?>
                                </td>
                                <td class="text-right">
                                    <?php echo number_format($row['facturacion'], 2, ',', '.'); ?> €
                                    <?php $total += $row['facturacion']; ?>
                                </td>                
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot class="border border-top-3 fs-4">
                        <tr>
                            <td class="text-right"></td>
                            <td class="text-right fw-bold">
                                <?php echo number_format($total, 2, ',', '.'); ?> €                    
                            </td>                
                        </tr>
                    </tfoot>
                </table>
            <?php } else { ?>
                <p>No hay datos actualmente</p>
            <?php } ?>
        </div>
        <div class="border-bottom mb-5 w-md-75 w-lg-50">  
            <div class="card-title mb-4"><h3>Pagos por TPV (Carnets comprados por el cliente)</h3></div>
            <?php if ($pagos_tpv_bonos[0]['facturacion'] > 0) { $total=0; ?>        
                <table class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                    <thead>
                        <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                            <th>Centro</th>
                            <th class="text-right">Facturación</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php foreach ($pagos_tpv_bonos as $row) { ?>
                            <tr>
                                <td>
                                    Central
                                </td>
                                <td class="text-right">
                                    <?php echo number_format($row['facturacion'], 2, ',', '.'); ?> €
                                    <?php $total += $row['facturacion']; ?>
                                </td>                
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot class="border border-top-3 fs-4">
                        <tr>
                            <td class="text-right"></td>
                            <td class="text-right fw-bold">
                                <?php echo number_format($total, 2, ',', '.'); ?> €                    
                            </td>                
                        </tr>
                    </tfoot>
                </table>        
            <?php } else { ?>
                <p>No hay datos actualmente</p>
            <?php } ?>
        </div>
    </div>
</div>