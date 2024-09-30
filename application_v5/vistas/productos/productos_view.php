<?php if (isset($estado)) {
    if ($estado > 0) { ?>
        <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
            <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE GUARDÓ CORRECTAMENTE</div>
            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="fa-times fas fs-3 text-primary"></i>
            </button>
        </div>
<?php }
} ?>
<?php if (isset($borrado)) { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE BORRÓ CORRECTAMENTE</div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="fa-times fas fs-3 text-primary"></i>
        </button>
    </div>
<?php } ?>
<?php if ($this->session->userdata('id_perfil') == 3 || $this->session->userdata('id_perfil') == 0) { ?>
    <div class="alert alert-dismissible alert-warning d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">* Los productos marcados en rojo están por debajo del stock mínimo.</div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="fa-times fas fs-3 text-primary"></i>
        </button>
    </div>
<?php } ?>
<?php setlocale(LC_MONETARY, 'es_ES'); ?>
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
        <form id="form_productos" action="<?php echo base_url(); ?>productos/gestion/actualizar_stock" role="form" method="post" name="form_productos">
            <div class="table-responsive">
                <table id="myTable1" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                    <thead class="">
                        <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                            <th style="display: none;">ID</th>
                            <th>Nombre Producto</th>
                            <th>Familia</th>
                            <th>P.V.P.</th>
                            <th>P. Franquiciado<br>Sin IVA</th>
                            <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                                <th>P. Compra<br> Sin IVA</th>
                            <?php } ?>
                            <?php if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 3 || $this->session->userdata('id_perfil') == 2) { ?>
                                <th>Stock</th>
                                <th>Stock Mínimo</th>
                            <?php } ?>
                            <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                                <th>Obsoleto</th>
                                <th></th>
                                <th></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($registros)) {
                            if ($registros != 0) {
                                foreach ($registros as $key => $row) { ?>
                                    <tr <?= ($row['cantidad_stock'] < $row['stock_minimo']) ? 'style="background: #fad7e4 !important;"' : '' ?>>
                                        <td style="display: none;">
                                            <?php echo $row['id_producto'] ?>
                                            <input name="id_productos[]" class="form-control form-control-solid" type="hidden" value="<?php echo $row['id_producto'] ?>" />
                                        </td>
                                        <td <?= ($row['cantidad_stock'] < $row['stock_minimo']) ? 'style="background: #fad7e4 !important;"' : '' ?>>
                                            <?php echo $row['nombre_producto'] ?>
                                        </td>
                                        <td><?php echo $row['nombre_familia'] ?></td>
                                        <td class="text-end"><?php echo number_format($row['pvp'], 2, ",", "."); ?></td>
                                        <td class="text-end"><?php echo number_format($row['precio_franquiciado_sin_iva'], 2, ",", "."); ?></td>
                                        <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                                            <td class="text-end"><?php echo number_format($row['precio_compra_sin_iva'], 2, ",", "."); ?></td>
                                        <?php } ?>
                                        <?php if ($this->session->userdata('id_perfil') == 2) { ?>
                                            <td><?php echo $row['cantidad_stock'] ?></td>
                                            <td><?php echo $row['stock_minimo'] ?></td>
                                        <?php } else { ?>
                                            <td>
                                                <input type="number" name="cantidad_sock[]" value="<?php echo $row['cantidad_stock'] ?>" class="form-control form-control-solid form-control-sm" required />
                                            </td>
                                            <td>
                                                <input type="number" name="stock_minimo[]" value="<?php echo $row['stock_minimo'] ?>" class="form-control form-control-solid form-control-sm" required />
                                            </td>
                                        <?php } ?>
                                        <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                                            <td><?= ($row['obsoleto'] == 1) ? '<span class="badge d-block fs-4 badge-primary">Obsoleto</span>' : '' ?></td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-warning" href="<?= base_url() ?>productos/gestion/editar/<?php echo $row['id_producto']; ?>"><i class="fa-regular fa-pen-to-square"></i></a>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-icon btn-danger" onclick="Borrar(<?php echo $row['id_producto']; ?>);"><i class="fa-solid fa-trash"></i></button>
                                            </td>
                                        <?php } ?>
                                    </tr>
                        <?php }
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
            <?php if (($this->session->userdata('id_perfil') == 3 || $this->session->userdata('id_perfil') == 0) && isset($registros) && $this->session->userdata('id_usuario') != 125) {
                if ($registros != 0) { ?>
                    <div class="w-100 border-top mt-5 pt-5">
                        <button type="submit" class="btn btn-info text-inverse-info">ACTUALIZAR STOCK</button>
                    </div>
            <?php }
            } ?>
        </form>
    </div>
</div>
<?php if ($this->session->userdata('id_perfil') == 4 || $this->session->userdata('id_perfil') == 0) { ?>
    <div class="card shadow-sm mt-5">
        <div class="card-header align-items-end py-5 gap-2 gap-md-5 border-bottom">
            <div class="card-title align-items-end">Gestión de Familias de Productos</div>
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
                <a href="<?php echo base_url(); ?>productos/familias/nuevo" class="btn btn-primary text-inverse-primary">Añadir familia</a>
            </div>
        </div>
        <div class="card-header align-items-end py-5 gap-2 gap-md-5">
            <div class="card-title">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="myTable2">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body pt-6">
            <div class="table-responsive">
                <table id="myTable2" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                    <thead class="">
                        <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                            <th style="display: none;">ID</th>
                            <th>Nombre Familia</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($registros_familias)) {
                            if ($registros_familias != 0) {
                                foreach ($registros_familias as $key => $row) { ?>
                                    <tr>
                                        <td style="display: none;"><?php echo $row['id_familia_producto'] ?></td>
                                        <td><?php echo $row['nombre_familia'] ?></td>
                                        <td>
                                            <a class="btn btn-sm btn-icon btn-warning" href="<?php echo base_url(); ?>productos/familias/editar/<?php echo $row['id_familia_producto'] ?>"><i class="fa-regular fa-pen-to-square"></i></a>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-icon btn-danger" onclick="BorrarFamilia(<?php echo $row['id_familia_producto'] ?>);"><i class="fa-solid fa-trash"></i></button>
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
<?php } ?>
<script>
    function Borrar(id_producto) {
        if (confirm("¿Desea borrar el producto?")) {
            document.location.href = "<?php echo base_url(); ?>productos/gestion/borrar/" + id_producto;
        }
        return false;
    }

    function BorrarFamilia(id_familia_producto) {
        if (confirm("¿Desea borrar la familia?")) {
            document.location.href = "<?php echo base_url(); ?>productos/familias/borrar/" + id_familia_producto;
        }
        return false;
    }
</script>