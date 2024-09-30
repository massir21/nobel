<style>
    .dataTables_filter {
        text-align: right;
    }
</style>
<?php if (isset($estado) && $estado > 0) { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE GUARDÓ CORRECTAMENTE</div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="fa-times fas fs-3 text-primary"></i>
        </button>
    </div>
<?php } ?>
<?php if (isset($borrado)) { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE BORRÓ CORRECTAMENTE</div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="fa-times fas fs-3 text-primary"></i>
        </button>
    </div>
<?php } ?>
<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title"></div>
        <form id="form" action="" role="form" method="post" name="form">
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
                <div class="w-auto">
                    <label for="" class="form-label">Fecha desde</label>
                    <input type="date" id="fecha" name="fecha" value="<?php if (isset($fecha)) {echo $fecha;} ?>" class="form-control form-control-solid w-auto" placeholder="Fecha desde" required/>
                </div>
                <div class="w-auto ms-3">
                    <label for="" class="form-label">Fecha hasta</label>
                    <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?php if (isset($fecha_hasta)) {echo $fecha_hasta; } ?>" class="form-control form-control-solid w-auto" placeholder="Fecha hasta" required/>
                </div>
                <div class="w-auto ms-3">
                    <label for="" class="form-label">Tipo de movimiento</label>
                    <select name="tipo_movimiento" id="tipo_movimiento" class="form-select form-select-solid w-auto">
                        <option value="0" <?=(isset($tipo_movimiento) && $tipo_movimiento == 0) ? 'selected' : ''?>>Cualquier movimiento</option>
                        <option value="1" <?=(isset($tipo_movimiento) && $tipo_movimiento == 1) ? 'selected' : ''?>>Retiradas</option>
                        <option value="2" <?=(isset($tipo_movimiento) && $tipo_movimiento == 2) ? 'selected' : ''?>>Ingresos</option>
                    </select>
                </div>
                <?php if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 4) { ?>
                    <div class="w-auto ms-3">
                        <label for="" class="form-label">Centro:</label>
                        <select name="id_centro" id="id_centro" class="form-select form-select-solid w-auto">
                        <option value="0">Todos</option>
                            <?php if (isset($centros_todos)) {
                                if ($centros_todos != 0) {
                                    foreach ($centros_todos as $key => $row) {
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
                <?php } else { ?>
                    <input id="id_centro" value="" type="hidden" />
                <?php } ?>
                <div class="w-auto  ms-3">
                    <button type="button" class="btn btn-info btn-icon text-inverse-info" onclick="Buscar();"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">
            <table id="myTable2" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th>Fecha</th>
                        <th>Concepto</th>
                        <th class="text-end">Importe</th>
                        <th>Empleado</th>
                        <?php if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 4) { ?>
                            <th>Centro</th>
                        <?php } ?>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php if (isset($registros)) {
                        if ($registros != 0) {
                            foreach ($registros as $key => $row) { ?>
                                <tr>
                                    <td>
                                        <span class="d-none"><?=$row['fecha_creacion_aaaammdd_hhss']?></span>
                                        <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6"><?=$row['fecha_creacion_ddmmaaaa']?></span>
                                        <span class="text-muted fw-semibold text-muted d-block fs-7"><?=$row['hora']?></span>
                                    </td>
                                    <td>
                                        <?php echo $row['concepto'] ?>
                                    </td>
                                    <td class="text-end">
                                        <?php echo round($row['cantidad'], 2) . " €"; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['empleado'] ?>
                                    </td>
                                    <?php if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 4) { ?>
                                        <td>
                                            <?php echo $row['nombre_centro'] ?>
                                        </td>
                                    <?php } ?>
                                    <td>
                                        <a class="btn btn-sm btn-icon btn-danger"  href="#" onclick="Borrar(<?php echo $row['id'] ?>);"><i class="fa-solid fa-trash"></i></a>
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
<script>
    function Borrar(id) {
        if (confirm("¿Desea borrar el movimiento indicando?")) {
            document.location.href = "<?php echo base_url(); ?>caja/movimientos/borrar/" + id;
        }
        return false;
    }
    function Buscar() {
        document.location.href = "<?php echo base_url(); ?>caja/movimientos/listado/0/" + document.getElementById("fecha").value + "/" + document.getElementById("fecha_hasta").value + "/" + document.getElementById("id_centro").value + "/" + document.getElementById('tipo_movimiento').value ;
    }
</script>