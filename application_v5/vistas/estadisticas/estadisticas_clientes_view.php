<style>
    .dataTables_filter {
        text-align: right;
    }
    .align-items-baseline.border-bottom.d-flex.py-1>*:not(:first-child) {
        margin-left: .5rem;
    }
    .form-select ~ .select2.select2-container.select2-container--bootstrap5{
        width: auto!important;
    }
</style>
<div class="card card-flush">
    <?php if (!isset($registros)) { ?>
        <div class="card-body">
            <form id="form_estadisticas_clientes" action="<?php echo base_url(); ?>estadisticas/clientes/buscar" role="form" method="post" name="form_estadisticas_clientes" target="_blank">
                <div class="align-items-baseline border-bottom d-flex py-1 flex-wrap">
                    <label for="" class="form-label">Creado entre</label>
                    <input type="date" id="fecha" name="fecha_desde_creacion" value="" class="form-control form-control-solid w-auto" placeholder="Creado desde" />
                    <label for="" class="form-label">hasta</label>
                    <input type="date" id="fecha_hasta" name="fecha_hasta_creacion" value="" class="form-control form-control-solid w-auto" placeholder="Creado hasta" />
                </div>
                <div class="align-items-baseline border-bottom d-flex py-1 flex-wrap">
                    <label for="" class="form-label">Que venga</label>
                    <select name="que_venga_condicion" id="que_venga_condicion" class="form-select form-select-solid w-auto">
                        <option value="0">Cualquier condición ...</option>
                        <option value="Mas">Más</option>
                        <option value="Igual">Igual</option>
                        <option value="Menos">Menos</option>
                    </select>
                    <input type="number" id="que_venga_veces" name="que_venga_veces" value="" step="1" class="form-control form-control-solid w-auto" placeholder="Nº de veces" style="max-width: 125px;" />
                    <label for="" class="form-label">por</label>
                    <select name="que_venga_periodo" id="que_venga_periodo" class="form-select form-select-solid w-auto">
                        <option value="0">Cualquier período ...</option>
                        <option value="Semana">Semana</option>
                        <option value="Mes">Mes</option>
                        <option value="Anno">Año</option>
                    </select>
                </div>
                <div class="align-items-baseline border-bottom d-flex py-1 flex-wrap">
                    <label for="" class="form-label">Última visita entre</label>
                    <input type="date" id="fecha_desde_ultima_visita" name="fecha_desde_ultima_visita" value="" class="form-control form-control-solid w-auto" placeholder="Creado desde" />
                    <label for="" class="form-label">hasta</label>
                    <input type="date" id="fecha_hasta_ultima_visita" name="fecha_hasta_ultima_visita" value="" class="form-control form-control-solid w-auto" placeholder="Última visita hasta" />
                </div>
                <div class="align-items-baseline border-bottom d-flex py-1 flex-wrap">
                    <label for="" class="form-label">Consumo por</label>
                    <select name="consumo_periodo" id="consumo_periodo" class="form-select form-select-solid w-auto">
                        <option value="0">Cualquier período ...</option>
                        <option value="Semana">Semana</option>
                        <option value="Mes">Mes</option>
                        <option value="Anno">Año</option>
                    </select>
                    <label for="" class="form-label">sea</label>
                    <select name="consumo_condicion" id="consumo_condicion" class="form-select form-select-solid w-auto">
                        <option value="0">Cualquier condición...</option>
                        <option value="Mayor_igual">Mayor o igual</option>
                        <option value="Menos_igual">Menor o igual</option>
                    </select>
                    <label for="" class="form-label">a</label>
                    <input type="number" id="consumo_importe" name="consumo_importe" value="" step="0.01" class="form-control form-control-solid w-auto" placeholder="Importe consumo" />
                </div>
                <div class="align-items-baseline border-bottom d-flex py-1 flex-wrap">
                    <label for="" class="form-label">Que haya acudido al centro:</label>
                    <select name="acudido_centro" id="acudido_centro" class="form-select form-select-solid w-auto">
                        <option value="0">Cualquier centro...</option>
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
                    <select name="acudido_centro_periodo" data-placeholder="Cualquier período ..." class="form-select form-select-solid w-auto" aria-hidden="true">
                        <option value="0">Cualquier período...</option>
                        <option value="Primera_visita">Primera visita</option>
                        <option value="Alguna_vez">Alguna vez</option>
                        <option value="Ultima_visita">Ultima visita</option>
                    </select>
                </div>
                <div class="align-items-baseline border-bottom d-flex py-1 flex-wrap">
                    <label for="" class="form-label">Que haya sido atendido por</label>
                    <select name="atendido_empleado" data-control="select2" data-placeholder="Cualquier empleado ..." class="form-select form-select-solid w-auto" aria-hidden="true">
                        <option value="0">Cualquier empleado...</option>
                        <?php if (isset($empleados)) {
                            if ($empleados != 0) {
                                foreach ($empleados as $key => $row) { ?>
                                    <option value='<?php echo $row['id_usuario']; ?>' <?php if (isset($id_usuario)) {if ($row['id_usuario'] == $id_usuario) {echo "selected";}} ?>>
                                        <?php echo strtoupper($row['apellidos'] . ", " . $row['nombre'] . " (" . $row['nombre_centro'] . ")"); ?>
                                    </option>
                            <?php }
                            }
                        } ?>
                    </select>
                    <select name="atendido_periodo" data-placeholder="Cualquier período ..." class="form-select form-select-solid w-auto" aria-hidden="true">
                        <option value="0">Cualquier período...</option>
                        <option value="Primera_visita">Primera visita</option>
                        <option value="Alguna_vez">Alguna vez</option>
                        <option value="Ultima_visita">Ultima visita</option>
                    </select>
                </div>
                <div class="align-items-baseline border-bottom d-flex py-1 flex-wrap">
                    <label for="" class="form-label">Pide solo con</label>
                    <select name="atendido_solo_empleado" data-control="select2" data-placeholder="Cualquier empleado ..." class="form-select form-select-solid w-auto" aria-hidden="true">
                        <option value="0">Cualquier empleado...</option>
                        <?php if (isset($empleados)) {
                            if ($empleados != 0) {
                                foreach ($empleados as $key => $row) { ?>
                                    <option value='<?php echo $row['id_usuario']; ?>' <?php if (isset($id_usuario)) {if ($row['id_usuario'] == $id_usuario) {echo "selected";}} ?>>
                                        <?php echo strtoupper($row['apellidos'] . ", " . $row['nombre'] . " (" . $row['nombre_centro'] . ")"); ?>
                                    </option>
                            <?php }
                            }
                        } ?>
                    </select>
                    <select name="atendido_solo_periodo" data-placeholder="Cualquier período ..." class="form-select form-select-solid w-auto" aria-hidden="true">
                        <option value="0">Cualquier período...</option>
                        <option value="Primera_visita">Primera visita</option>
                        <option value="Alguna_vez">Alguna vez</option>
                        <option value="Ultima_visita">Ultima visita</option>
                    </select>
                </div>
                <div class="align-items-baseline border-bottom d-flex py-1 flex-wrap">
                    <label for="" class="form-label">Que haya hecho</label>
                    <select name="id_familia_servicio" data-placeholder="Cualquier familia..." class="form-select form-select-solid w-auto" aria-hidden="true" onchange="Servicios();">
                        <option value="0">Cualquier familia....</option>
                        <?php if ($servicios_familias != 0) {
                            foreach ($servicios_familias as $key => $row) { ?>
                                <option value="<?php echo $row['id_familia_servicio'] ?>"><?php echo $row['nombre_familia'] ?></option>
                        <?php }
                        } ?>
                    </select>
                    <select name="id_servicio" data-placeholder="Cualquier servicios ..." class="form-select form-select-solid w-auto" aria-hidden="true">
                        <option value="0">Cualquier servicios...</option>
                    </select>
                    <select name="hecho_servicio_periodo" data-placeholder="Cualquier período ..." class="form-select form-select-solid w-auto" aria-hidden="true">
                        <option value="0">Cualquier período...</option>
                        <option value="Primera_visita">Primera visita</option>
                        <option value="Alguna_vez">Alguna vez</option>
                        <option value="Ultima_visita">Ultima visita</option>
                    </select>
                </div>
                <div class="align-items-baseline border-bottom d-flex py-1 flex-wrap">
                    <label for="" class="form-label">Que haya comprado</label>
                    <select name="comprado_producto" data-control="select2" data-placeholder="Cualquier producto ..." class="form-select form-select-solid w-auto" aria-hidden="true">
                        <option value="0">Cualquier producto...</option>
                        <?php if (isset($productos)) {
                            if ($productos != 0) {
                                foreach ($productos as $key => $row) { ?>
                                    <option value='<?php echo $row['id_producto']; ?>'>
                                        <?php echo strtoupper($row['nombre_producto']) . " (" . $row['nombre_familia'] . ")"; ?>
                                    </option>
                            <?php }
                            }
                        } ?>
                    </select></label>
                    <select name="comprado_producto_periodo" data-placeholder="Cualquier período ..." class="form-select form-select-solid w-auto" aria-hidden="true">
                        <option value="0">Cualquier período...</option>
                        <option value="Primera_visita">Primera visita</option>
                        <option value="Alguna_vez">Alguna vez</option>
                        <option value="Ultima_visita">Ultima visita</option>
                    </select>
                </div>
                <div class="align-items-baseline border-bottom d-flex py-1 flex-wrap">
                    <label for="" class="form-label">Que haya comprado el carnet</label>
                    <select name="comprado_carnet" data-control="select2" data-placeholder="Cualquier tipo carnet ..." class="form-select form-select-solid w-auto" aria-hidden="true">
                        <option value="0">Cualquier carnet...</option>
                        <?php if (isset($tipos_carnets)) {
                            if ($tipos_carnets != 0) {
                                foreach ($tipos_carnets as $key => $row) { ?>
                                    <option value='<?php echo $row['id_tipo']; ?>'>
                                        <?php echo strtoupper($row['descripcion']); ?>
                                    </option>
                            <?php }
                            }
                        } ?>
                    </select>
                    <select name="comprado_carnet_periodo" data-placeholder="Cualquier período ..." class="form-select form-select-solid w-auto" aria-hidden="true">
                        <option value="0">Cualquier período...</option>
                        <option value="Primera_visita">Primera visita</option>
                        <option value="Alguna_vez">Alguna vez</option>
                        <option value="Ultima_visita">Ultima visita</option>
                    </select>
                </div>
                <div class="align-items-baseline border-bottom d-flex py-1 flex-wrap">
                    <label for="" class="form-label">Que repita</label>
                    <select name="que_repita_condicion" data-placeholder="Cualquier condición ..." class="form-select form-select-solid w-auto" aria-hidden="true">
                        <option value="0">Cualquier condición ...</option>
                        <option value="Mas">Más</option>
                        <option value="Igual">Igual</option>
                        <option value="Menos">Menos</option>
                    </select>
                    <label for="" class="form-label">de</label>
                    <input type="number" step="1" name="que_repita_veces" class="form-control form-control-solid w-auto" style="max-width: 125px;"/>
                    <label for="" class="form-label">veces con</label>
                    <select name="que_repita_empleado" data-control="select2" data-placeholder="Cualquier empleado ..." class="form-select form-select-solid w-auto" aria-hidden="true">
                        <option value="0">Cualquier empleado...</option>
                        <?php if (isset($empleados)) {
                            if ($empleados != 0) {
                                foreach ($empleados as $key => $row) { ?>
                                    <option value='<?php echo $row['id_usuario']; ?>' <?php if (isset($id_usuario)) {if ($row['id_usuario'] == $id_usuario) {echo "selected";}} ?>>
                                        <?php echo strtoupper($row['apellidos'] . ", " . $row['nombre'] . " (" . $row['nombre_centro'] . ")"); ?>
                                    </option>
                            <?php }
                            }
                        } ?>
                    </select>
                </div>
                <div class="align-items-baseline border-bottom d-flex py-1 flex-wrap">
                    <label for="" class="form-label">Que haya Anulado Cita</label>
                    <select name="que_haya_anulado_condicion" data-placeholder="Cualquier condición ..." class="form-select form-select-solid w-auto" aria-hidden="true">
                        <option value="0">Cualquier condición ...</option>
                        <option value="Mas">Más</option>
                        <option value="Igual">Igual</option>
                        <option value="Menos">Menos</option>
                    </select>
                    <input type="number" step="1" name="que_haya_anulado_veces" class="form-control form-control-solid w-auto" style="max-width: 125px;"/>
                    <label for="" class="form-label">veces</label>
                    <select name="que_haya_anulado_periodo" data-placeholder="Cualquier período ..." class="form-select form-select-solid w-auto" aria-hidden="true">
                        <option value="0">Cualquier período ...</option>
                        <option value="Semana">Semana</option>
                        <option value="Mes">Mes</option>
                        <option value="Anno">Año</option>
                    </select>
                </div>
                <div class="align-items-baseline border-bottom d-flex py-1 flex-wrap">
                    <label for="" class="form-label">Que haya No Vino</label>
                    <select name="que_no_vino_condicion" data-placeholder="Cualquier condición ..." class="form-select form-select-solid w-auto" aria-hidden="true">
                        <option value="0">Cualquier condición ...</option>
                        <option value="Mas">Más</option>
                        <option value="Igual">Igual</option>
                        <option value="Menos">Menos</option>
                    </select>
                    <input type="number" step="1" name="que_no_vino_veces" class="form-control form-control-solid w-auto" style="max-width: 125px;"/>
                    <label for="" class="form-label">veces</label>
                    <select name="que_no_vino_periodo" data-placeholder="Cualquier período ..." class="form-select form-select-solid w-auto" aria-hidden="true">
                        <option value="0">Cualquier período ...</option>
                        <option value="Semana">Semana</option>
                        <option value="Mes">Mes</option>
                        <option value="Anno">Año</option>
                    </select>
                </div>
                <div class="align-items-baseline border-bottom d-flex py-1 flex-wrap">
                    <label for="" class="form-label">Que acuda a más de</label>
                    <input type="number" step="1" name="que_acuda_centros"  class="form-control form-control-solid w-auto" style="max-width: 125px;"/>
                    <label for="" class="form-label">a centros distintos</label>
                </div>
                <div class="align-items-baseline border-bottom d-flex py-1 flex-wrap">
                    <label for="" class="form-label">Cuya rentabilidad sea</label>
                    <select name="rentabilidad_condicion" data-placeholder="Cualquier condición ..." class="form-select form-select-solid w-auto" aria-hidden="true">
                        <option value="0">Cualquier condición ...</option>
                        <option value="Mas">Más</option>
                        <option value="Igual">Igual</option>
                        <option value="Menos">Menos</option>
                    </select>
                    <input type="number" step="1" name="rentabilidad" class="form-control form-control-solid w-auto" style="max-width: 125px;"/>
                    <label for="" class="form-label">% *(Facturacion Servicios + Productos) - Citas que no vino / Visitas totales</label>
                </div>
                <div class="row ">
                    <div class="col-md-12">
                        <button class="btn btn-primary text-inverse-primary" type="submit">Filtrar Clientes</button>
                    </div>
                </div>
            </form>
        </div>
    <?php } ?>
    <?php if (isset($filtros)) { ?>
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
        </div>
        <div class="card-body">
            <div class="alert alert-primary d-flex flex-column flex-sm-row p-5">
                <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">
                    <p class="fs-4">Filtros elegidos:</p>
                    <?php echo $filtros; ?>
                </div>
            </div>
            <div class="table-responsive">
                <table id="myTable1" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                    <thead class="">
                        <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                            <th style="display: none;">ID</th>
                            <th style="width: 130px;">Fecha Alta</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Teléfono</th>
                            <th>Publicidad</th>
                            <th>Último Centro</th>
                            <th>Último Empleado</th>
                            <th>Última Recepcionista</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($registros)) {
                            if ($registros != 0) {
                                foreach ($registros as $key => $row) { ?>
                                    <tr id="fila<?php echo $row['id_cliente'] ?>">
                                        <td style="display: none;"> <?php echo $row['id_cliente'] ?></td>
                                        <td><?php echo $row['fecha_creacion_ddmmaaaa'] ?></td>
                                        <td>
                                            <a class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6" href="<?php echo base_url(); ?>clientes/historial/ver/<?php echo $row['id_cliente']; ?>"><?php echo $row['nombre']; ?></a>
                                        </td>
                                        <td><?php echo $row['apellidos']; ?></td>
                                        <td ><?php echo $row['telefono'] ?></td>
                                        <td><?= ($row['no_quiere_publicidad'] == 0) ? "No Quiere":"Si Quiere"?></td>
                                        <td><?php echo $row['ultimo_centro'] ?> </td>
                                        <td><?php echo $row['ultimo_empleado'] ?></td>
                                        <td><?php echo $row['ultima_recepcionista'] ?></td>
                                    </tr>
                                <?php }
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php }?>
</div>
<script type="text/javascript">
    function Servicios() {
        <?php echo $script_servicios; ?>
    }
    Servicios();
</script>