<style>
    .dataTables_filter {
        text-align: right;
    }

    .swal2-container.swal2-center.swal2-backdrop-show {
        z-index: 99999;
    }
</style>
<div class="card card-flush">
    <div class="card-header align-items-center py-5">
        <div class="card-toolbar justify-content-center w-100">

        </div>

        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="text" class="form-control form-control-solid w-250px ps-12"
                       placeholder="Escribe para buscar" data-table-search="dietario">
            </div>
        </div>
        <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
            <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                <div class="w-auto ms-3">
                    <label for="" class="form-label">Centro:</label>
                    <select name="id_centro" id="id_centro" class="form-select form-select-solid w-auto"
                            onchange="document.form.submit();">
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
            <?php } else { ?>
                <input type="hidden" name="id_centro" id="id_centro"
                       value="<?php echo $this->session->userdata('id_perfil'); ?>"/>
            <?php } ?>
            <div class="w-auto">
                <label for="" class="form-label">Fecha</label>
                <input type="date" id="fecha" name="fecha" value="<?php if (isset($hoy_aaaammdd)) {
                    echo $hoy_aaaammdd;
                } ?>" class="form-control form-control-solid w-auto" placeholder="Fecha desde" required/>
            </div>
            <div class="w-auto  ms-3">
                <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                    <button type="button" class="btn btn-info btn-icon text-inverse-info"
                            onclick="NuevoDiaFiltroCentro();"><i class="fas fa-search"></i></button>
                <?php } else { ?>
                    <button type="button" class="btn btn-info text-inverse-info" onclick="NuevoDia();"><i
                                class="fas fa-search"></i></button>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">
            <table id="dietario" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                    <th style="display: none;"></th>
                    <th>Fecha / Hora</th>
                    <th>Cliente</th>
                    <th>Concepto</th>
                    <th>Empleado</th>
                    <th>Euros</th>
                    <th>Templos</th>
                    <th>Estado</th>
                    <th style="display: none;"></th>
                </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">

                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

