<style>
    .dataTables_filter {
        text-align: right;
    }
</style>
<?php if ($this->session->flashdata('mensaje') != '') { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center"><?php echo $this->session->flashdata('mensaje'); ?></div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="fa-times fas fs-3 text-primary"></i>
        </button>
    </div>
<?php } ?>
<?php setlocale(LC_MONETARY, 'es_ES'); ?>
<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="myTable1">
            </div>
        </div>
        <form id="form_estadisticas" action="<?php echo base_url();?>cupones/uso" role="form" method="post" name="form_estadisticas">
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
                <div class="w-auto">
                    <label for="" class="form-label">Fecha desde</label>
                    <input type="date" id="fecha_desde" name="fecha_desde" value="<?php if (isset($fecha_desde)) {echo date('Y-m-d', strtotime($fecha_desde));} ?>" class="form-control form-control-solid w-auto" placeholder="Fecha desde" required />
                </div>
                <div class="w-auto ms-3">
                    <label for="" class="form-label">Fecha hasta</label>
                    <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?php if (isset($fecha_hasta)) {echo date('Y-m-d', strtotime($fecha_hasta));} ?>" class="form-control form-control-solid w-auto" placeholder="Fecha hasta" required />
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
            <table id="myTable1" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th>Cupón</th>
                        <th>Pedido citas</th>
                        <th>Citas</th>
                        <th>Centro</th>
                        <th>Servicio</th>
                        <th>Cliente</th>
                        <th>Precio</th>
                        <th>Dto (€)</th>
                        <th>Dto (%)</th>
                        <th>Pagado</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php if (isset($cupones_usados)) {
                        if ($cupones_usados != 0) {
                            foreach ($cupones_usados as $key => $row) { ?>
                                <tr>
                                    <td><?php echo $row['codigo_cupon'] ?></td>
                                    <td><?php echo $row['id_pedido'] ?></td>
                                    <td><?php echo $row['id_cita'] ?></td>
                                    <td><?php echo $row['nombre_centro'] ?></td>
                                    <td><?php echo $row['nombre_servicio'] ?></td>
                                    <td><?php echo $row['cliente'] ?></td>
                                    <td><?php echo $row['importe_euros'] ?></td>
                                    <td><?php echo $row['descuento_euros'] ?></td>
                                    <td><?php echo $row['descuento_porcentaje'] ?></td>
                                    <td><?php echo $row['pagado_tarjeta'] + $row['pagado_paypal']; ?></td>
                                    <td><?php echo $row['fecha_creacion']; ?></td>
                                </tr>
                            <?php }
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>