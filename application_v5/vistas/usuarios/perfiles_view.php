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
        <div class="card-title">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="myTable1">
                </div>
            </div>
        </div>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">
            <table id="myTable1" class="table align-top table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th style="display: none;">ID</th>
                        <th>Perfil</th>
                        <th>Módulos Asociados</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php if (isset($registros)) {
                        if ($registros != 0) {
                            foreach ($registros as $key => $row) { ?>
                                <tr>
                                    <td style="display: none;">
                                        <?php echo $row['id_perfil'] ?>
                                    </td>
                                    <td>
                                        <?php echo $row['nombre_perfil'] ?>
                                    </td>
                                    <td>
                                        <?php echo $row['modulos'] ?><br />
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-icon btn-warning" href="<?php echo base_url(); ?>usuarios/perfiles/editar/<?php echo $row['id_perfil'] ?>"><i class="fa-regular fa-pen-to-square"></i></a>
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