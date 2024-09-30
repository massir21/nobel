

<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <form id="form" action="<?php echo base_url(); ?>tienda/procesar_codigo" role="form" method="post" name="form">
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
                <div class="w-auto">
                    <label for="" class="form-label">Código</label>
                    <input type="text" id="codigo" name="codigo" value="<?= (isset($codigo)) ? $codigo : '' ?>" class="form-control form-control-solid w-auto" placeholder="Introducir Código" />
                </div>
                <div class="w-auto    ms-3">
                    <button type="submit" class="btn btn-info text-inverse-info">Comprobar</button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body pt-6">
        <?php if (isset($error)) { ?>
            <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
                <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">No se ha generado ningún carnet. No se ha especificado el cliente o un código correcto.</div>
                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                    <i class="fa-times fas fs-3 text-primary"></i>
                </button>
            </div>
        <?php }  ?>

        <?php if (isset($usado)) { ?>
            <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
                <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center"> El código: <?=(isset($codigo))?$codigo:''?> ya ha sido procesado.</div>
                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                    <i class="fa-times fas fs-3 text-primary"></i>
                </button>
            </div>
        <?php } ?>

        <?php if (isset($ok)) { ?>
            <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
                <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center"> TODOS LOS CARNETS HAN SIDO GENERADOS CORRECTAMENTE</div>
                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                    <i class="fa-times fas fs-3 text-primary"></i>
                </button>
            </div>
            <?php if (isset($carnet_especial)) {
                if (count($carnet_especial) > 0) { ?>
                    <h3>Carnets Especiales Generados:</h3>
                    <div class="table-responsive">
                        <table class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                            <thead class="">
                                <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                                    <th>Código</th>
                                    <th>Tipo</th>
                                    <th>Cliente</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 fw-semibold">
                                <?php if (isset($carnet_especial)) {
                                    foreach ($carnet_especial as $row) { ?>
                                        <tr>
                                            <td>
                                                <a class="btn btn-secondary btn-sm btn-text d-inline-flex align-items-center" href="<?php echo base_url();?>carnets/detalle/gestion/<?php echo $row['id_carnet']; ?>" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Ver detalle del Carnet" target="_blank"><i class="fas fa-id-card"></i> <?php echo $row['codigo']; ?></a>
                                            </td>
                                            <td><?php echo $row['tipo']; ?></td>
                                            <td><?php echo $row['cliente']; ?></td>
                                        </tr>
                                <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                <?php }
            } ?>

            <?php if (isset($carnets_templos)) {
                if (count($carnets_templos) > 0) { ?>
                    <h3>Carnets Templos Generados:</h3>
                    <div class="table-responsive">
                        <table class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                            <thead class="">
                                <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                                    <th>Código</th>
                                    <th>Tipo</th>
                                    <th>Cliente</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 fw-semibold">
                                <?php if (isset($carnets_templos)) {
                                    foreach ($carnets_templos as $row) { ?>
                                        <tr>
                                            <td>
                                                <a class="btn btn-secondary btn-sm btn-text d-inline-flex align-items-center" href="<?php echo base_url();?>carnets/detalle/gestion/<?php echo $row['id_carnet']; ?>" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Ver detalle del Carnet" target="_blank"><i class="fas fa-id-card"></i> <?php echo $row['codigo']; ?></a>
                                            </td>
                                            <td><?php echo $row['tipo']; ?></td>
                                            <td><?php echo $row['cliente']; ?></td>
                                        </tr>
                                    <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                <?php }
            }
        } ?>
    </div>
</div>