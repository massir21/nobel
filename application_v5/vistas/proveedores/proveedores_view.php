<?php if (isset($estado)) {
    if ($estado > 0) { ?>
        <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
            <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE GUARDÓ CORRECTAMENTE
            </div>
            <button type="button"
                    class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                    data-bs-dismiss="alert">
                <i class="fa-times fas fs-3 text-primary"></i>
            </button>
        </div>
    <?php }
} ?>
<?php if (isset($borrado)) { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE BORRÓ CORRECTAMENTE</div>
        <button type="button"
                class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                data-bs-dismiss="alert">
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
                    <input type="text" class="form-control form-control-solid w-250px ps-12"
                           placeholder="Escribe para buscar" data-table-search="myTable1">
                </div>
            </div>
        </div>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">
            <table id="myTable1" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                    <th style="display: none;">ID</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th style="text-align: center;">Obsoleto</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                <?php if (isset($registros) && !empty($registros)): ?>
                    <?php if (count($registros) > 0): ?>
                        <?php foreach ($registros as $key => $row): ?>
                            <tr>
                                <td style="display: none;">
                                    <?php echo $row['id_proveedor'] ?>
                                </td>
                                <td>
                                    <?php echo $row['nombreProveedor'] ?>
                                </td>
                                <td>
                                    <?php echo $row['tipoProveedor'] ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php if ($row['obsoleto'] == 1) {
                                        echo "<span class='label label-sm label-danger'>Sí</span>";
                                    } else {
                                        echo "No";
                                    } ?>
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-icon btn-warning"
                                       href="<?php echo base_url(); ?>proveedores/gestion/editar/<?php echo $row['id_proveedor'] ?>"><i
                                                class="fa-regular fa-pen-to-square"></i></a>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-icon btn-danger"
                                            onclick="Borrar(<?php echo $row['id_proveedor'] ?>);"><i
                                                class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- END SAMPLE FORM PORTLET-->
</div>

<div class="card shadow-sm mt-5">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5 border-bottom">
        <div class="card-title align-items-end">Gestión de Tipos de Proveedores</div>
        <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
            <a href="<?php echo base_url(); ?>proveedores/tipos/nuevo" class="btn btn-primary text-inverse-primary">Añadir
                tipo</a>
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
                    <input type="text" class="form-control form-control-solid w-250px ps-12"
                           placeholder="Escribe para buscar" data-table-search="myTable2">
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
                    <th>Nombre tipo</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                <?php if (isset($registros_tipos) && !empty($registros_tipos)): ?>
                    <?php if (count($registros_tipos) > 0): ?>
                        <?php foreach ($registros_tipos as $key => $row): ?>
                            <tr>
                                <td style="display: none;">
                                    <?php echo $row['id_tipo'] ?>
                                </td>
                                <td>
                                    <?php echo $row['nombre'] ?>
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-icon btn-warning"
                                       href="<?php echo base_url(); ?>proveedores/tipos/editar/<?php echo $row['id_tipo'] ?>"><i
                                                class="fa-regular fa-pen-to-square"></i></a>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-icon btn-danger"
                                            onclick="BorrarTipo(<?php echo $row['id_tipo'] ?>);"><i
                                                class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function Borrar(id_proveedor) {
        if (confirm("¿Desea borrar el proveedor?")) {
            document.location.href = "<?php echo base_url(); ?>proveedores/gestion/borrar/" + id_proveedor;
        }
        return false;
    }

    function BorrarTipo(id_tipo) {
        if (confirm("¿Desea borrar el tipo?")) {
            document.location.href = "<?php echo base_url(); ?>proveedores/tipos/borrar/" + id_tipo;
        }
        return false;
    }
</script>