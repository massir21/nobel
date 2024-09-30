<style>
    .dataTables_filter {
        text-align: right;
    }
</style>
<?php if (isset($estado) && $estado == 1) { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE GUARDÓ CORRECTAMENTE</div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="fa-times fas fs-3 text-primary"></i>
        </button>
    </div>
<?php } ?>
<?php if (isset($borrado) && $borrado == 1) { ?>
    <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE BORRÓ CORRECTAMENTE</div>
    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
        <i class="fa-times fas fs-3 text-primary"></i>
    </button>
    </div>
<?php } ?>
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
        <form id="form" action="<?php echo base_url(); ?>avisos/citas_espera" role="form" method="post" name="form">
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
                <div class="w-auto">
                    <label for="" class="form-label">Fecha desde</label>
                    <input type="date" id="fecha" name="fecha_desde" value="<?= (isset($fecha_desde)) ? $fecha_desde : '' ?>" class="form-control form-control-solid w-auto" placeholder="Fecha desde" required />
                </div>
                <div class="w-auto ms-3">
                    <label for="" class="form-label">Fecha hasta</label>
                    <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?php if (isset($fecha_hasta)) {
                                                                                        echo $fecha_hasta;
                                                                                    } ?>" class="form-control form-control-solid w-auto" placeholder="Fecha hasta" required />
                </div>
                <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                    <div class="w-auto ms-3">
                        <label for="" class="form-label">Centro:</label>
                        <select name="id_centro" id="id_centro" class="form-select form-select-solid w-auto">
                            <option value="">Todos</option>
                            <?php if (isset($centros)) {
                                if ($centros != 0) {
                                    foreach ($centros as $key => $row) {
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
                    <button type="submit" class="btn btn-info btn-icon text-inverse-info"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body pt-6">
        <div class="table responsive">
            <table id="estadistica_usuarios" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th style="display: none;"></th>
                        <th style="width: 10%;">Fecha / Horas</th>
                        <th>Cliente</th>
                        <th>Servicio</th>
                        <th>Empleado</th>
                        <th>Estado</th>
                        <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                            <th>Modificación</th>
                        <?php }?>
                        <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                            <th>Centro</th>
                        <?php } ?>
                        <th></th>
                        <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                            <th></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php if (isset($registros)) {
                        if ($registros != 0) {
                            foreach ($registros as $key => $row) { ?>
                                <tr>
                                    <td style="display: none;"><?php echo $row['fecha_aaaammdd']; ?></td>
                                    <td class="text-center <?=($row['estado'] == "Realizado") ? "bg-success text-success-invert" : (($row['estado'] == "Perdida") ? "bg-warning text-warning-invert" : "")?>">
                                        <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6 text-nowrap"><?php echo $row['fecha_ddmmaaaa_abrv']; ?></span>
                                        <span class="text-muted fw-semibold text-muted d-block fs-7">(De <?php echo $row['hora_inicio'] . " a ", $row['hora_fin']; ?>)</span>
                                        <?php if ($row['id_usuario_creacion'] == 0) { ?>
                                            <i class="fa fa-globe"></i>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <a class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6" href="<?php echo base_url(); ?>clientes/historial/ver/<?php echo $row['id_cliente'] ?>" target="_blank"><?php echo strtoupper($row['cliente']); ?></a>
                                        <span class="text-muted fw-semibold text-mutedfs-7"><?php echo strtoupper($row['telefono']); ?></span>
                                        <?php if ($row['como_contactar'] != "") { ?>
                                             <span class="text-muted fw-semibold text-muted fs-7"> Contactar por: <?php echo $row['como_contactar']; ?></span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bold d-block mb-1 fs-6"><?php echo strtoupper($row['nombre_familia'] . " - " . $row['nombre_servicio']); ?></span>
                                        <span class="text-muted fw-semibold text-muted fs-7">(<?php echo strtoupper($row['duracion']); ?> minutos)</span>
                                    </td>
                                    <td><?= ($row['id_usuario_empleado'] > 0) ? $row['empleado'] : "ME DA IGUAL";?></td>
                                    <td><?php echo $row['estado']; ?></td>
                                    <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                                        <td>
                                            <?php if ($row['usuario_modif'] != '') { ?>
                                                <span class="text-dark fw-bold d-block mb-1 fs-6"><?= $row['usuario_modif']?></span>
                                                <span class="text-muted fw-semibold text-muted fs-7"><?= date('d-m-Y H:i:s', strtotime($row['fecha_modificacion'])); ?> </span>
                                            <?php } ?>
                                        </td>
                                    <?php } ?>
                                    <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                                        <td><?php echo $row['nombre_centro']; ?></td>
                                    <?php } ?>
                                    <td>
                                        <button class="btn btn-sm btn-icon btn-warning" onclick="javascript:CitasEsperaEditar(<?php echo $row['id_cita_espera'] ?>);"><i class="fa-regular fa-pen-to-square"></i></a>
                                    </td>
                                    <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                                        <td>
                                            <button class="btn btn-sm btn-icon btn-danger" onclick="Borrar(<?php echo $row['id_cita_espera'] ?>);" ><i class="fa-solid fa-trash"></i></button>
                                        </td>
                                    <?php } ?>
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
    function Borrar(id_cita_espera) {
        if (confirm("¿DESEA BORRAR LA CITA EN ESPERA?")) {
            document.location.href = "<?php echo base_url(); ?>avisos/borrar_cita_espera/" + id_cita_espera;
        }
        return false;
    }
    function CitasEspera() {
        var posicion_x;
        var posicion_y;
        var ancho = 565;
        var alto = 700;
        posicion_x = (screen.width / 2) - (ancho / 2);
        posicion_y = (screen.height / 2) - (alto / 2);
        window.open("<?php echo base_url(); ?>avisos/citas_espera_gestion/nuevo/0/0/<?php echo date('Y-m-d') ?>/null/", "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
    }
    function CitasEsperaEditar(id_cita) {
        var posicion_x;
        var posicion_y;
        var ancho = 565;
        var alto = 700;
        posicion_x = (screen.width / 2) - (ancho / 2);
        posicion_y = (screen.height / 2) - (alto / 2);
        window.open("<?php echo base_url(); ?>avisos/citas_espera_gestion/editar/" + id_cita, "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
    }
</script>