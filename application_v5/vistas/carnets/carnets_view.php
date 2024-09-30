<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="estadistica_usuarios">
            </div>
        </div>
        <form id="form_buscar" action="<?php echo base_url(); ?>carnets" role="form" method="post" name="form_buscar">
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
                <div class="w-auto ms-3">
                    <label for="" class="form-label">Buscar carnet</label>
                    <input type="text" name="buscar" value="<?php if (isset($buscar)) {
                                                                echo $buscar;
                                                            } ?>" class="form-control form-control-solid w-auto" />
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
                        <th>Carnet</th>
                        <th>Tipo</th>
                        <th>T. Disponibles</th>
                        <th>Cliente</th>
                        <th>Centro</th>
                        <th>Precio</th>
                        <th>Reasignar</th>
                        <?php if ($this->session->userdata('id_perfil') == 3 || $this->session->userdata('id_perfil') == 0) { ?>
                            <th>Editar</th>
                        <?php } ?>
                        <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                            <th>Borrar</th>
                        <?php } ?>
                    </tr>
                </thead>
                <?php if (isset($buscar)) { ?>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($carnets)) {
                            if ($carnets != 0) {
                                foreach ($carnets as $key => $row) { ?>
                                    <tr>
                                        <td>
                                            <a class="btn btn-secondary btn-sm btn-text d-inline-flex align-items-center" href="<?php echo base_url(); ?>carnets/detalle/gestion/<?php echo $row['id_carnet']; ?>" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Ver detalle del Carnet"><i class="fas fa-id-card"></i> <?php echo $row['codigo']; ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?php echo $row['tipo']; ?>
                                            <?php if ($row['codigo_pack_online']) {
                                                echo "<br>(pack-online: " . $row['codigo_pack_online'] . ")";
                                            } ?>
                                        </td>
                                        <td>
                                            <?php if ($row['id_tipo'] != 99) {
                                                echo $row['templos_disponibles'];
                                            } else {
                                                if ($row['no_gastado'] == 0) {
                                                    echo "<span style='color: red'>Gastado</span>";
                                                } else {
                                                    echo "<span style='color: green'>Disponibles</span>";
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo $row['cliente']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['nombre_centro']; ?>
                                        </td>
                                        <td>
                                            <?php if ($row['id_tipo'] != 99) {
                                                echo number_format($row['precio'], 2, ',', '.') . "€";
                                            } else {
                                                echo number_format($row['precio'], 2, ',', '.') . "€";
                                            } ?>
                                        </td>
                                        <td>
                                            <?php if ((substr($row['codigo'], 0, 1) == 'U') and $this->session->userdata('id_perfil') != 0) { ?>
                                                <button class="btn btn-sm btn-icon btn-info"  disabled="">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            <?php } else { ?>
                                                <button class="btn btn-sm btn-icon btn-info" onclick="Reasignar(<?php echo $row['id_carnet']; ?>);">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            <?php } ?>
                                        </td>
                                        <?php if ($this->session->userdata('id_perfil') == 3 || $this->session->userdata('id_perfil') == 0) { ?>
                                            <td>
                                                <?php if ($row['id_tipo'] == 99) { ?>
                                                    <?php if ($this->session->userdata('id_perfil') == 3 || $this->session->userdata('id_perfil') == 0) { ?>
                                                        <button class="btn btn-sm btn-icon btn-warning" onclick="EditarCarnet(<?php echo $row['id_carnet']; ?>);"><i class="fa-regular fa-pen-to-square"></i></button>
                                                    <?php } else { ?>
                                                        -
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <?php if ($this->session->userdata('id_perfil') == 3 || $this->session->userdata('id_perfil') == 0) {
                                                        if ((substr($row['codigo'], 0, 1) == 'U') and $this->session->userdata('id_perfil') != 0) { ?>
                                                            <button class="btn btn-sm btn-icon btn-warning" disabled=""><i class="fa-regular fa-pen-to-square"></i></button>
                                                        <?php } else { ?>
                                                            <button class="btn btn-sm btn-icon btn-warning" onclick="EditarCarnetTemplos(<?php echo $row['id_carnet']; ?>);"><i class="fa-regular fa-pen-to-square"></i></button>
                                                        <?php }
                                                    } else { ?>
                                                        -
                                                    <?php } ?>
                                                <?php } ?>
                                            </td>
                                        <?php } ?>
                                        <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                                            <td>
                                                <button class="btn btn-sm btn-icon btn-danger" onclick="Borrar(<?php echo $row['id_carnet']; ?>,'<?php echo $row['codigo']; ?>');"><i class="fa-solid fa-trash"></i></button>
                                            </td>
                                        <?php } ?>
                                    </tr>
                        <?php }
                            }
                        } ?>
                    </tbody>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
<script>
    function NuevoCarnet() {
        var posicion_x;
        var posicion_y;
        var ancho = 600;
        var alto = 500;
        posicion_x = (screen.width / 2) - (ancho / 2);
        posicion_y = (screen.height / 2) - (alto / 2);
        window.open(" <?php echo base_url(); ?>carnets/gestion/nuevo/", "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top=" + posicion_y + ",left=" + posicion_x + " ,width=" + ancho + " ,height=" + alto);
    }
    function EditarCarnet(id_carnet) {
        var posicion_x;
        var posicion_y;
        var ancho = 565;
        var alto = 700;
        posicion_x = (screen.width / 2) - (ancho / 2);
        posicion_y = (screen.height / 2) - (alto / 2);
        window.open(" <?php echo base_url(); ?>carnets/modificar_especial/editar/" + id_carnet, "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + " ,width=" + ancho + " ,height=" + alto);
    }
    function EditarCarnetTemplos(id_carnet) {
        var posicion_x;
        var posicion_y;
        var ancho = 565;
        var alto = 300;
        posicion_x = (screen.width / 2) - (ancho / 2);
        posicion_y = (screen.height / 2) - (alto / 2);
        window.open(" <?php echo base_url(); ?>carnets/ajustes/gestion/" + id_carnet, "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + " ,width=" + ancho + " ,height=" + alto);
    }
    function Reasignar(id_carnet) {
        var posicion_x;
        var posicion_y;
        var ancho = 565;
        var alto = 350;
        posicion_x = (screen.width / 2) - (ancho / 2);
        posicion_y = (screen.height / 2) - (alto / 2);
        window.open(" <?php echo base_url(); ?>carnets/reasignar/gestion/" + id_carnet, "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + " ,width=" + ancho + " ,height=" + alto);
    }
    function Borrar(id, codigo) {
        if (confirm('SE DISPONE A BORRAR UN CARNET DE FORMA MANUAL, ESTO IMPLICA QUE EL Nº DE CARNET: ' + codigo + ' NO PODRÁ VOLVER A SER USADO\n\n¿DESEA BORRAR EL CARNET Nº ' + codigo + '?')) {
            document.location.href = '<?php echo base_url(); ?>carnets/index/borrar/<?php if (isset($id_carnet)) {
                                                                                        echo $id_carnet;
                                                                                    } ?>/' + id;
        }
        return true;
    }
</script>