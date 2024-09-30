<style>            
    .dataTables_filter {
        text-align: right;        
    }
</style>
<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="myTable2">
            </div>
        </div>
        <form id="form" action="" role="form" method="post" name="form">
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
                <div class="w-auto">
                    <label for="" class="form-label">Fecha desde</label>
                    <input type="date" id="fecha" name="fecha_desde" value="<?php if (isset($fecha_desde)) {echo $fecha_desde;} ?>" class="form-control form-control-solid w-auto" placeholder="Fecha desde" />
                </div>
                <div class="w-auto ms-3">
                    <label for="" class="form-label">Fecha hasta</label>
                    <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?php if (isset($fecha_hasta)) {echo $fecha_hasta; } ?>" class="form-control form-control-solid w-auto" placeholder="Fecha hasta" />
                </div>
                <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                    <div class="w-auto ms-3">
                        <label for="" class="form-label">Centro:</label>
                        <select name="id_centro" id="id_centro" class="form-select form-select-solid w-auto" onchange="NuevoDiaFiltroCentro();">
                        <option value="">Todos</option>
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
                        <th>Nº Ticket</th>
                        <th>Fecha</th>                        
                        <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                            <th>Centro</th>
                        <?php } ?>
                        <th>Cliente</th>            
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php if (isset($tickets)) {
                        if ($tickets != 0) {
                            foreach ($tickets as $key => $row) { ?>
                                <tr>
                                    <td>
                                        <a class="btn btn-secondary btn-sm btn-text d-inline-flex align-items-center" href="<?php echo base_url();?>dietario/ver_ticket/<?php echo $row['id_ticket'] ?>" target="_blank"><?php echo $row['id_ticket'] ?></a>
                                    </td>
                                    <td><?php echo $row['fecha_creacion_abrev'] ?></td>
                                    <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                                        <td><?php echo $row['nombre_centro'] ?></td>
                                    <?php } ?>
                                    <td><?php echo $row['cliente'] ?></td>            
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
    function Buscar() {
        document.location.href="<?php echo base_url();?>dietario/tickets/"+document.getElementById("fecha").value+"/"+document.getElementById("fecha_hasta").value+"/"+document.getElementById("id_centro").value;
    }
</script>