<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
<style>
    #id_servicio2 {
        display: <?= (isset($cita[0]['id_servicio2'])) ? 'block' : 'none' ?>;
    }

    #id_servicio3 {
        display: <?= (isset($cita[0]['id_servicio3'])) ? 'block' : 'none' ?>;
    }

    #id_servicio4 {
        display: <?= (isset($cita[0]['id_servicio4'])) ? 'block' : 'none' ?>;
    }

    #id_servicio5 {
        display: <?= (isset($cita[0]['id_servicio5'])) ? 'block' : 'none' ?>;
    }

    #id_servicio6 {
        display: <?= (isset($cita[0]['id_servicio6'])) ? 'block' : 'none' ?>;
    }
</style>
<?php
$finalizado = (isset($cita[0]['estado']) && $cita[0]['estado'] == "Finalizado") ? true : false;
$editar = (isset($cita[0]['cliente'])) ? strtoupper($cita[0]['cliente']) : '';
$pagetitle = ($accion == "nuevo" || $finalizado == true) ? 'AÑADIR CITA' : 'EDITAR CITA ' . $editar;
$id_cita = (isset($cita[0]['id_cita'])) ? $cita[0]['id_cita'] : '';
$formaction = ($accion == "nuevo" || $finalizado == true) ? base_url() . 'agenda/citas/guardar/' : base_url() . 'agenda/citas/modificar/' . $id_cita;

?>
<h1 class="fs-2x fw-bolder my-0 text-center text-uppercase"><?= $pagetitle ?></h1>
<div class="card card-flush m-5">
    <div class="card-body p-5">
        <form id="form_cita" action="<?php echo $formaction; ?>" role="form" method="post" name="form_cita"
              onsubmit="return ComprobarCliente();">
            <div class="row mb-5 align-items-end">
                <div class="col-8">
                    <label for="" class="form-label">Cliente:</label>
                    <select name="id_cliente" id="id_cliente" class="form-select form-select-solid"
                            data-placeholder="Elegir ..." onchange="NotasClientes();" required
                            data-error="Por favor, selecciona una cliente.">
                        <?php if (isset($cliente_elegido) && $cliente_elegido[0]['id_cliente'] > 0) { ?>
                            <option value="<?= $cliente_elegido[0]['id_cliente'] ?>"
                                    selected><?= $cliente_elegido[0]['nombre'] . ' ' . $cliente_elegido[0]['apellidos'] . ' (' . $cliente_elegido[0]['telefono'] . ')'; ?></option>
                        <?php } ?>
                    </select>

                    <script type="text/javascript">
                        $("#id_cliente").select2({
                            language: "es",
                            minimumInputLength: 4,
                            ajax: {
                                delay: 0,
                                url: function (params) {
                                    return '<?php echo RUTA_WWW; ?>/clientes/json/' + params.term.replace(/ /g, "_").replace(/ /g, "_");
                                },
                                dataType: 'json',
                                processResults: function (data) {
                                    return {
                                        results: data
                                    };
                                }
                            }
                        });
                    </script>
                </div>

                <div class="w-auto">
                    <button type="button" class="btn btn-info text-inverse-info btn-icon"
                            data-bs-target="#stack-cliente" data-bs-toggle="modal" title="Añadir un nuevo Cliente"><i
                                class="fas fa-user-plus"></i></button>
                    <?php if (isset($cliente_elegido)) {
                        if ($cliente_elegido[0]['id_cliente'] > 0) { ?>
                            <button type="button" class="btn btn-success text-inverse-success btn-icon"
                                    onclick="FichaCliente('<?= $cliente_elegido[0]['id_cliente'] ?>');"
                                    data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                                    title="Ficha cliente"><i class="fas fa-user"></i></button>

                            <button type="button" class="btn btn-secondary text-inverse-secondary btn-icon"
                                    onclick="NuevaNota('<?= $cliente_elegido[0]['id_cliente']; ?>');"
                                    data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                                    title="Añadir una Nueva Nota"><i class="fas fa-file-text"></i></button>

                            <button type="button" class="btn btn-warning text-inverse-warning btn-icon"
                                    data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                                    title="Copiar teléfono"
                                    onclick="copiartelefono('<?= $cliente_elegido[0]['telefono'] ?>')"><i
                                        class="fas fa-phone"></i></button>
                            <script>
                                function copiartelefono(copy) {
                                    copy = copy.replace(/\s/g, "");
                                    var aux = document.createElement("input");
                                    aux.setAttribute("value", '+34' + copy);
                                    document.body.appendChild(aux);
                                    aux.select();
                                    document.execCommand("copy");
                                    document.body.removeChild(aux);
                                    $('.tooltip-inner').html('COPIADO!');
                                }
                            </script>
                        <?php }
                    } ?>
                </div>
            </div>

            <?php if (isset($presupuestos_cliente)) { ?>
                <div class="row mb-5 align-items-end">
                    <div class="col-12">
                        <label for="" class="form-label">Presupuesto:</label>
                        <select name="id_presupuesto" id="id_presupuesto" class="form-select form-select-solid"
                                data-placeholder="Elegir ..." onchange="NotasClientes();"
                                data-error="Por favor, selecciona una opción.">
                            <option value="">No es una cita relacionada con un presupuesto</option>
                            <?php foreach ($presupuestos_cliente as $p => $presupuesto) {
                                if (isset($id_presupuesto) && $id_presupuesto == $presupuesto['id_presupuesto']) {
                                    $presupuesto_seleccionado = $presupuesto;
                                } ?>
                                <option value="<?= $presupuesto['id_presupuesto'] ?>" <?= (isset($id_presupuesto) && $id_presupuesto == $presupuesto['id_presupuesto']) ? 'selected' : '' ?>>
                                    Presupuesto <?= $presupuesto['nro_presupuesto'] ?>
                                    , <?= euros($presupuesto['total_aceptado']) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            <?php } ?>

            <?php if (isset($presupuesto_items)) { ?>
                <div class="item_presupuesto">
                    <div class="row mb-5 align-items-end">
                        <div class="col-12">
                            <label for="" class="form-label">Servicios del presupuesto</label>
                            <select name="id_presupuesto_item[]" id="id_presupuesto_item"
                                    class="form-select form-select-solid w-auto" data-control="select2"
                                    data-placeholder="Elegir ..." onchange="Recarga();" required
                                    data-error="Por favor, selecciona un servicio." <?= ($accion == "nuevo" || $finalizado == true) ? 'multiple' : '' ?>>
                                <option value=""></option>
                                <?php if (isset($presupuesto_items)) {
                                    if ($presupuesto_items != 0) {
                                        foreach ($presupuesto_items as $key => $row) {
                                            if ($finalizado == true && $row['id_cita'] == $cita[0]['id_cita']) {
                                            } else {
                                                if (isset($cita[0]['id_presupuesto_item'][0]) && in_array($row['id_item'] . '|' . $row['id_presupuesto_item'], $cita[0]['id_presupuesto_item'])) {
                                                    $servicio_presupuesto = $row;
                                                    if ($presupuesto['es_repeticion'] == 1) {
                                                        $servicio_presupuesto['dto'] = 100;
                                                        $servicio_presupuesto['coste'] = 0.00;
                                                    }
                                                } ?>
                                                <option value="<?php echo $row['id_item']; ?>|<?php echo $row['id_presupuesto_item']; ?>" <?= (isset($cita[0]['id_presupuesto_item'][0]) && in_array($row['id_item'] . '|' . $row['id_presupuesto_item'], $cita[0]['id_presupuesto_item']) && $finalizado != true) ? "selected" : '' ?> <?= (isset($row['disabled'])) ? 'disabled' : '' ?>>
                                                    <?php echo strtoupper($row['nombre_item']) . " (" . $row['duracion'] . " min)"; ?><?= ($row['dientes'] != '') ? ' PIEZA Nº ' . $row['dientes'] : '' ?>
                                                </option>
                                            <?php }
                                        }
                                    }
                                } ?>
                            </select>
                        </div>

                    </div>
                </div>
            <?php } ?>


            <?php if (isset($cliente_elegido) && $cliente_elegido[0]['id_cliente'] > 0 && isset($notas_citas) && $notas_citas != 0) { ?>
                <div class="table-responsive mb-5">
                    <table id="tabla_notas"
                           class="align-middle border border-secondary fs-6 gy-5 p-2 porder table table-rounded table-row-dashed">
                        <thead class="">
                        <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                            <th style="display: none;"></th>
                            <th style="width: 1%; border: 0px;"></th>
                            <th style="border: 0px;">NOTAS CLIENTE PARA SUS CITAS</th>
                        </tr>
                        </thead>
                        <tbody class="text-gray-700 fw-semibold">
                        <?php foreach ($notas_citas as $key => $row) { ?>
                            <tr>
                                <td style="display: none;">
                                    <?php echo $row['fecha_creacion_aaaammdd'] ?>
                                </td>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input"
                                               id="id_nota_cita_<?php echo $row['id_nota_cita'] ?>" type="checkbox"
                                               value="<?php echo $row['id_nota_cita'] ?>"
                                               onclick="FinalizarNota(this,'<?php echo $row['id_nota_cita'] ?>');"><i
                                                class="fas fa-trash ms-3"></i>
                                    </div>
                                </td>
                                <td>
                                            <span class="text-dark fw-bold d-block mb-1 fs-6" data-bs-toggle="tooltip"
                                                  data-bs-custom-class="tooltip-inverse"
                                                  title="<?php echo $row['nota'] ?>">
                                                <?php echo $row['fecha_creacion_ddmmaaaa'] ?> -> <?php echo substr($row['nota'], 0, 55); ?> ...</span>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div id="borrar_nota" class="mb-5" style="display: none;"></div>
            <?php } ?>


            <div class="row mb-5 align-items-end">
                <div class="col-8">
                    <label for="" class="form-label">¿Con quién quiere la cita?:</label>
                    <select name="id_empleado" id="id_empleado" onchange="Recarga();" data-control="select2"
                            class="form-select form-select-solid w-auto" required
                            data-error="Por favor, selecciona un empleado.">
                        <option value="0">Todos los empleados</option>
                        <?php if (isset($empleados)) {
                            if ($empleados != 0) {
                                foreach ($empleados as $key => $row) { ?>
                                    <option value="<?php echo $row['id_usuario']; ?>" <?= ((isset($cita[0]['id_usuario_empleado']) && $row['id_usuario'] == $cita[0]['id_usuario_empleado']) || (isset($quelleva) && $row['id_usuario'] == $cita[0]['id_usuario_empleado'])) ? "selected" : '' ?>>
                                        <?php echo strtoupper($row['apellidos'] . ", " . $row['nombre'] . " (" . $row['nombre_centro'] . ")"); ?>
                                    </option>
                                <?php }
                            }
                        } ?>
                    </select>
                </div>
                <div class="col-4">
                    <label class="form-label">Sólo con este empleado</label>
                    <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                        <input class="form-check-input w-45px h-30px" type="checkbox" id="solo_este_empleado"
                               name="solo_este_empleado"
                               value="1" <?= (isset($cita[0]['solo_este_empleado']) && $cita[0]['solo_este_empleado'] == 1) ? "checked" : '' ?>>
                        <label class="form-check-label" for="solo_este_empleado"></label>
                    </div>
                </div>
                <?php if (isset($cita[0]['solo_este_empleado']) && $cita[0]['solo_este_empleado'] == 1) { ?>
                    <div class="col-12 text-warning text-center">
                        ATENCIÓN: El cliente sólo quiere cita con este empleado.
                    </div>
                <?php } ?>
            </div>
            <?php if (!isset($presupuesto_items)) { ?>
                <div class="row mb-5 align-items-end">
                    <div class="col-8">
                        <label for=""
                               class="form-label">Servicios <? (isset($id_familia_servicio) && $id_familia_servicio == 12) ? '/ Código Proveedor' : '' ?></label>
                        <select name="id_servicio" id="id_servicio" class="form-select form-select-solid w-auto"
                                data-control="select2" data-placeholder="Elegir ..." onchange="RecargaServicio(event);"
                                data-error="Por favor, selecciona un servicio." <?= (isset($cita[0]['id_presupuesto_item'][0])) ? "" : "required" ?>>
                            <option value=""></option>
                            <?php if (isset($servicios)) {
                                if ($servicios != 0) {
                                    foreach ($servicios as $key => $row) { ?>
                                        <option value="<?php echo $row['id_servicio']; ?>" <?= (isset($cita[0]['id_servicio']) && ($row['id_servicio'] == $cita[0]['id_servicio'])) ? "selected" : '' ?>>
                                            <?php echo strtoupper($row['nombre_servicio']) . " (" . $row['duracion'] . " min)"; ?>
                                        </option>
                                    <?php }
                                }
                            } ?>
                        </select>
                        <input type="hidden" name="id_servicio_ultimo_marcado"
                               value="<?= (isset($id_servicio_ultimo_marcado)) ? $id_servicio_ultimo_marcado : '' ?>"/>

                    </div>
                    <div class="w-auto">
                        <button type="button" class="btn btn-info text-inverse-info btn-icon" data-bs-toggle="tooltip"
                                data-bs-custom-class="tooltip-inverse" title="Añadir otro servicio"
                                onclick="OtroServicio();"><i class="fas fa-plus"></i></button>
                    </div>
                </div>
                <?php for ($i = 2; $i <= 6; $i++) { ?>
                    <div id="id_servicio<?= $i ?>">
                        <div class="row mb-5 align-items-end">
                            <div class="col-8">
                                <label for=""
                                       class="form-label">Servicio <?= (isset($id_familia_servicio) && $id_familia_servicio == 12) ? '/ Código Proveedor ' : '' ?><?= $i ?></label>
                                <select name="id_servicio<?= $i ?>" id="id_servicio<?= $i ?>"
                                        class="form-select form-select-solid w-auto" data-control="select2"
                                        data-placeholder="Elegir ..." onchange="RecargaServicio(event);">
                                    <option value=""></option>
                                    <?php if (isset($servicios)) {
                                        if (is_array($servicios)) {
                                            foreach ($servicios as $key => $row) { ?>
                                                <option value="<?php echo $row['id_servicio']; ?>" <?= (isset($cita[0]['id_servicio' . $i]) && $row['id_servicio'] == $cita[0]['id_servicio' . $i]) ? "selected" : '' ?>>
                                                    <?php echo strtoupper($row['nombre_servicio']) . " (" . $row['duracion'] . " min)"; ?>
                                                </option>
                                            <?php }
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
            <?php if (isset($id_familia_servicio) && $id_familia_servicio == 12) { ?>
                <input type="text" name="codigo_proveedor" class="form-control form-control-solid"
                       value="<?= (isset($codigo_proveedor)) ? $codigo_proveedor : '' ?>" placeholder="Código Proveedor"
                       required/>
            <?php } ?>

            <?php if ($accion == "editar") { ?>
                <div class="row mb-5 align-items-end">
                    <div class="col-8">
                        <label for="" class="form-label">Establecer una duración diferente para el servicio</label>
                        <select name="duracion_nueva" onchange="Recarga();" class="form-select form-select-solid w-auto"
                                data-control="select2" data-placeholder="Elegir ...">
                            <option value="">No establecer</option>
                            <?php for ($t = 15; $t < 241; $t = $t + 15) { ?>
                                <option value="<?php echo $t; ?>" <?= ($duracion_nueva == $t) ? "selected" : '' ?>><?php echo $t; ?>
                                    minutos
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            <?php } ?>

            <div class="row mb-5 align-items-end">
                <div class="col">
                    <label for="" class="form-label">Día de la cita (<?php echo $fecha_completa; ?>)</label>
                    <input type="date" id="fecha" name="fecha" onchange="Recarga();"
                           value="<?= (isset($cita[0]['fecha_inicio_aaaammdd'])) ? $cita[0]['fecha_inicio_aaaammdd'] : '' ?>"
                           class="form-control form-control-solid w-auto" placeholder="Fecha desde" required/>
                </div>

                <div class="col">
                    <label for="" class="form-label">Hora</label>
                    <select name="hora" data-placeholder="Elegir hora..." data-control="select2"
                            class="form-select form-select-solid w-auto" required>
                        <option value=""></option>
                        <?php if (isset($quelleva)) { ?>
                            <option value="<?php echo $cita[0]['hora_inicio']; ?>"
                                    selected><?php echo $cita[0]['hora_inicio']; ?></option>
                        <?php } ?>
                        <?php if (!isset($quelleva) && isset($horas_libres) && $horas_libres != 0) {
                            foreach ($horas_libres as $key => $row) { ?>
                                <option value="<?php echo $row; ?>" <?= (isset($cita[0]['hora_inicio']) && $row == $cita[0]['hora_inicio']) ? "selected" : '' ?>><?php echo $row; ?></option>
                            <?php }
                        } ?>
                    </select>
                </div>
                <?php if (isset($horas_libres) && is_array($horas_libres) && count($horas_libres) == 0) { ?>
                    <div class="col-12 text-center text-warning">
                        Empleado no disponible
                    </div>
                <?php } ?>
            </div>

            <div class="row mb-5 align-items-end border-bottom">
                <div class="col-md-12 mb-5">
                    <label for="" class="form-label">Observaciones</label>
                    <textarea name="observaciones" placeholder="Notas sobre el cliente"
                              class="form-control form-control-solid"
                              style="height: 50px;"><?= (isset($notas_cliente)) ? $notas_cliente : '' ?></textarea>
                </div>
                <div class="col-6 mb-5">
                    <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                        <input class="form-check-input w-45px h-30px" type="checkbox" id="recordatorio_sms"
                               name="recordatorio_sms"
                               value="1" <?= (isset($cita[0]['recordatorio_sms']) && $cita[0]['recordatorio_sms'] == 1) ? "checked" : '' ?>>
                        <label class="form-check-label" for="recordatorio_sms">Recordatorio SMS</label>
                    </div>
                </div>

                <div class="col-6 mb-5">
                    <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                        <input class="form-check-input w-45px h-30px" type="checkbox" id="recordatorio_email"
                               name="recordatorio_email"
                               value="1" <?= (isset($cita[0]['recordatorio_email']) && $cita[0]['recordatorio_email'] == 1) ? "checked" : '' ?>>
                        <label class="form-check-label" for="recordatorio_email">Recordatorio Email</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <?php if ($accion == "nuevo" || $accion == "horarios" || $finalizado == true) { ?>

                        <button class="btn btn-sm btn-secondary text-inverse-secondary m-2" type="button"
                                onclick="Cerrar();">Cerrar sin Cambios
                        </button>
                        <button class="btn btn-sm btn-primary text-inverse-primary" type="submit">Añadir</button>

                    <?php } else { ?>

                        <?php /* if (isset($presupuesto_seleccionado)) { ?>
                                <div class="row alert alert-info my-3">
                                    <div class="col-4">
                                        Coste Presupuesto: <h5><?= ($presupuesto_seleccionado['es_repeticion'] == 1) ? '0.00€' : euros($presupuesto_seleccionado['total_aceptado']) ?></h5>
                                        Saldo usado: <h5><?= euros($presupuesto_seleccionado['total_gastado']) ?></h5>
                                    </div>
                                    <div class="col-4">
                                        Total pagado: <h5><?= ($presupuesto_seleccionado['es_repeticion'] == 1) ? '0.00€' : euros($presupuesto_seleccionado['total_pagado']) ?></h5>
                                        Saldo disponible: <h5><?= ($presupuesto_seleccionado['es_repeticion'] == 1) ? '0.00€' : euros($presupuesto_seleccionado['total_pagado'] - $presupuesto_seleccionado['total_gastado']) ?></h5>
                                    </div>
                                    <div class="col-4">
                                        Saldo pendiente: <h5><?= ($presupuesto_seleccionado['es_repeticion'] == 1) ? '0.00€' : euros($presupuesto_seleccionado['total_aceptado'] - $presupuesto_seleccionado['total_pagado']) ?></h5>
                                        Coste del servicio: <h5><?=($presupuesto_seleccionado['es_repeticion'] == 1) ? '0.00€' : ((isset($servicio_presupuesto)) ? euros($servicio_presupuesto['coste']):'???') ?></h5>
                                    </div>
                                </div>
                            <?php } */ ?>

                        <button class="btn btn-sm btn-warning text-inverse-warning m-2" type="button"
                                onclick="window.close();">Cerrar sin Cambios
                        </button>
                        <button class="btn btn-sm btn-outline btn-outline-danger m-2" type="button" onclick="NoVino();">
                            No vino
                        </button>
                        <button class="btn btn-sm btn-primary text-inverse-primary m-2" type="submit">Modificar</button>
                        <button class="btn btn-sm btn-danger text-inverse-denger m-2" type="button" onclick="Anular();">
                            Anular
                        </button>
                        <?php //if (isset($cita[0]['id_presupuesto_item'])) {
                        if (isset($presupuesto_seleccionado) && isset($servicio_presupuesto) && (($servicio_presupuesto['coste'] <= $presupuesto_seleccionado['total_pagado'] - $presupuesto_seleccionado['total_gastado']) || $presupuesto_seleccionado['es_repeticion'] == 1)) { ?>
                            <button class="btn btn-sm btn-success text-inverse-success m-2" type="button"
                                    onclick="Finalizar()">Finalizar
                            </button>
                            <?php /*} else { ?>
                                    <span data-bs-toggle="modal" data-bs-target="#modal-pago">
                                        <button type="button" class="btn btn-sm  btn-outline btn-outline-primary" data-pago data-bs-toggle="tooltip" title="Registrar pago en el presupuesto">Realizar pago</button>
                                    </span>
                                <?php } ?>

                            <?php */
                        } else { ?>
                            <button class="btn btn-sm btn-success text-inverse-success m-2" type="button"
                                    onclick="Cobrar(<?= (isset($cita[0]['id_cliente'])) ? $cita[0]['id_cliente'] : '' ?>,'<?= (isset($cita[0]['fecha_inicio_aaaammdd'])) ? $cita[0]['fecha_inicio_aaaammdd'] : '' ?>')">
                                Cobrar
                            </button>
                        <?php } ?>


                    <?php } ?>
                </div>
            </div>

            <?php if (isset($existe_firma)) {
                if (!$existe_firma) { ?>
                    <?php if (isset($cita[0]['no_quiere_publicidad'])) {
                        if ($cita[0]['no_quiere_publicidad'] == 0) { ?>
                            <div class="row mt-5 border-top">
                                <div class="col-md-12 text-center">
                                    <a class="btn btn-outline btn-outine-primary"
                                       href="<?php echo base_url(); ?>clientes/proteccion_de_datos/<?= (isset($cita[0]['id_cliente'])) ? $cita[0]['id_cliente'] : '' ?>"
                                       class="btn red" target="_blank">Firmar Protección de Datos</a>
                                </div>
                            </div>
                        <?php }
                    } ?>
                <?php }
            } ?>

            <?php if (isset($otrascitas) && is_countable($otrascitas)) {
            $xcitas = count($otrascitas);
            if ($xcitas > 1) { ?>
            <div class="row mt-5 pt-5 border-top">
                <h4>Otras Citas Programadas:</h4>
                <ul class="list-grop">
                    <?php foreach ($otrascitas as $key => $row) {
                        if ($row['id_cita'] != $citaactual) { ?>
                            <li class="list-group-item"><?php echo $row['fecha_inicio']; ?> a
                                las <?php echo $row['hora_inicio']; ?> -> <?php echo $row['servicio']; ?> </li>
                        <?php }
                    } ?>
                </ul>
                <?php }
                } ?>

                <input type="hidden" name="id_cliente_anterior"
                       value="<?= (isset($id_cliente_anterior)) ? $id_cliente_anterior : "0" ?>">
        </form>
    </div>
</div>

<div id="stack-cliente" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
     data-focus-on="input:first">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php
            $actionformmodal = ($accion == "nuevo" || $accion == "horarios") ? base_url() . 'agenda/citas/nuevo/-99' : base_url() . 'agenda/citas/editar/' . ((isset($cita[0]['id_cita'])) ? $cita[0]['id_cita'] . '/-99' : '/-99');
            ?>
            <form name="form_nuevo_cliente" id="form_nuevo_cliente" action="<?= $actionformmodal ?>" method="post"
                  onsubmit="return EsOk();">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">AÑADIR NUEVO CLIENTE</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-5 border-bottom">
                        <div class="col-12 mb-2">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control form-control-solid" name="nombre"
                                   placeholder="Nombre" required/>
                        </div>
                        <div class="col-12 mb-2">
                            <label class="form-label">Apellidos</label>
                            <input type="text" class="form-control form-control-solid" name="apellidos"
                                   placeholder="Apellidos" required/>
                        </div>
                        <div class="col-12 mb-2">
                            <label class="form-label">Teléfono</label>
                            <input type="text" class="form-control form-control-solid" name="telefono"
                                   placeholder="Teléfono" style="width: 150px;" required/>
                        </div>
                        <div class="col-12 mb-2">
                            <label class="form-label">¿Cómo nos conocio?</label>
                            <select class="form-select" name="como_conocio" required>
                                <option value=""></option>
                                <option value="Redes sociales">Redes sociales</option>
                                <option value="Conocidos">Conocidos</option>
                                <option value="Busqueda en Google">Google</option>
                                <option value="Folletos">Folletos</option>
                                <option value="Otros medios">Otros</option>
                            </select>
                        </div>
                        <div class="col-12 mb-2">
                            <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                                <input class="form-check-input w-45px h-30px" type="checkbox"
                                       id="no_quiere_publicidad_modal" name="no_quiere_publicidad" value="1">
                                <label class="form-check-label" for="no_quiere_publicidad_modal">NO quiero recibir
                                    publicidad</label>
                            </div>
                        </div>
                    </div>
                    <div id="esperar" style="display: none;">
                        <h3 class="text-warning text-center fs-4"> No cierre la ventana, espere un momento...</h3>
                        <img src="<?php echo base_url() . 'recursos/foto/loader.gif'; ?>"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="enviar()">Añadir Cliente</button>
                </div>
                <input type="hidden" name="modal_id_usuario_empleado"
                       value="<?php echo (isset($cita[0]['id_usuario_empleado'])) ? $cita[0]['id_usuario_empleado'] : ''; ?>"/>
                <input type="hidden" name="modal_hora_inicio"
                       value="<?php echo (isset($cita[0]['hora_inicio'])) ? $cita[0]['hora_inicio'] : ''; ?>"/>
                <input type="hidden" name="modal_fecha_inicio"
                       value="<?php echo (isset($cita[0]['fecha_inicio_aaaammdd'])) ? $cita[0]['fecha_inicio_aaaammdd'] : ''; ?>"/>
                <input type="hidden" name="solo_este_empleado" value=""/>
                <input type="hidden" name="id_empleado" value=""/>
                <input type="hidden" name="id_servicio" value=""/>
                <input type="hidden" name="fecha" value=""/>
                <input type="hidden" name="hora" value=""/>
                <input type="hidden" name="observaciones" value=""/>
            </form>
        </div>
    </div>
</div>
<?php if (isset($presupuesto_seleccionado) && isset($servicio_presupuesto) && ($presupuesto_seleccionado['total_pagado'] - $presupuesto_seleccionado['total_gastado']) < $servicio_presupuesto['coste']) { ?>
    <div class="modal fade" id="modal-pago" tabindex="-1" aria-labelledby="modal-pagoLabel" aria-hidden="true"
         data-focus-on="input:first">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="form_pagoeuros" action="<?php echo base_url(); ?>Presupuestos/pagoeuros" role="form"
                      method="post" name="form_pagoeuros">
                    <div class="modal-header">
                        <h5 id="presupuestoPagoModalLabel" class="mb-0">PRESUPUESTO
                            Nº: <?= $presupuesto_seleccionado['nro_presupuesto'] ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row alert alert-info my-3">
                            <div class="col-4">
                                Coste Presupuesto: <h5><?= euros($presupuesto_seleccionado['total_aceptado']) ?></h5>
                                Saldo usado: <h5><?= euros($presupuesto_seleccionado['total_gastado']) ?></h5>
                            </div>
                            <div class="col-4">
                                Total pagado: <h5><?= euros($presupuesto_seleccionado['total_pagado']) ?></h5>
                                Saldo disponible:
                                <h5><?= euros($presupuesto_seleccionado['total_pagado'] - $presupuesto_seleccionado['total_gastado']) ?></h5>
                            </div>
                            <div class="col-4">
                                Saldo pendiente:
                                <h5><?= euros($presupuesto_seleccionado['total_aceptado'] - $presupuesto_seleccionado['total_pagado']) ?></h5>
                                Coste del servicio: <h5><?= euros($servicio_presupuesto['coste']) ?></h5>
                            </div>
                        </div>

                        <div class="row mb-5 align-items-end border-bottom">
                            <div class="col-6 mb-5">
                                <label for="" class="form-label text-center">Importe Efectivo</label>
                                <input name="pagado_efectivo" class="form-control form-control-solid" type="number"
                                       step="0.01" min="0" value="0" style="text-align: right;"
                                       onchange="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);"
                                       onkeyup="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);"
                                       required/>
                            </div>
                            <div class="col-6 mb-5">
                                <label for="" class="form-label text-center">Importe Tarjeta</label>
                                <input name="pagado_tarjeta" id="pagado_tarjeta" class="form-control form-control-solid"
                                       type="number" step="0.01" min="0" value="0" style="text-align: right;"
                                       onchange="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);"
                                       onkeyup="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);"
                                       required/>
                                <p id="nota-efectivo" style="display:none; font-weight:bolder"></p>
                            </div>
                            <div class="col-6 mb-5">
                                <label for="" class="form-label text-center">Importe TPV2</label>
                                <input name="pagado_tpv2" id="pagado_tpv2" class="form-control form-control-solid"
                                       type="number" step="0.01" min="0" value="0" style="text-align: right;"
                                       onchange="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);"
                                       onkeyup="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);"
                                       required/>
                                <p id="nota-tpv2" style="display:none; font-weight:bolder"></p>
                            </div>
                            <div class="col-6 mb-5">
                                <label for="" class="form-label text-center">Importe PayPal</label>
                                <input name="pagado_paypal" id="pagado_paypal" class="form-control form-control-solid"
                                       type="number" step="0.01" min="0" value="0" style="text-align: right;"
                                       onchange="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);"
                                       onkeyup="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);"
                                       required/>
                            </div>
                            <div class="col-6 mb-5">
                                <label for="" class="form-label text-center">Importe Transferencia</label>
                                <input name="pagado_transferencia" id="pagado_transferencia"
                                       class="form-control form-control-solid" type="number" step="0.01" min="0"
                                       value="0" style="text-align: right;"
                                       onchange="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);"
                                       onkeyup="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);"
                                       required/>
                                <p id="nota-transferencia" style="display:none; font-weight:bolder"></p>
                            </div>
                            <div class="col-6 mb-5">
                                <label for="" class="form-label text-center">Importe Financiado</label>
                                <input name="pagado_financiado" id="pagado_financiado"
                                       class="form-control form-control-solid" type="number" step="0.01" min="0"
                                       value="0" style="text-align: right;"
                                       onchange="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);"
                                       onkeyup="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);"
                                       required/>
                            </div>

                            <input name="pagado_habitacion" type="hidden" value="0"/>
                            <input type="hidden" name="id_presupuesto" id="id_presupuesto"
                                   value="<?= $presupuesto_seleccionado['id_presupuesto'] ?>">
                        </div>

                    </div>
                    <div class="modal-footer p-2 justify-content-center">
                        <button type="button" class="btn btn-sm btn-secondary text-inverse-secondary"
                                data-bs-dismiss="modal">Cerrar
                        </button>
                        <button type="button" class="btn btn-sm btn-primary text-inverse-primary"
                                id="btn-registrar-pago" onclick="PagoPresupuesto();">Registrar pago
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>

<script>
    function enviar() {
        document.getElementById('esperar').style.display = "block";
        document.form_nuevo_cliente.submit();
    }

    function RecargaServicio(event) {
        var nombreElemento = $(event.target).attr('name');
        if (nombreElemento && nombreElemento.startsWith('id_servicio')) {
            var idItemValue = $(event.target).val();
            var idItemValue = $(event.target).val();
            var idClienteValue = <?= (isset($cliente_elegido[0]['id_cliente'])) ? $cliente_elegido[0]['id_cliente'] : 0 ?>;
            var data = {
                id_item: idItemValue,
                id_cliente: idClienteValue
            };
            $.ajax({
                url: '<?= base_url() ?>Presupuestos/buscarServicio',
                method: 'POST',
                data: data,
                success: function (response) {
                    if (response.hasOwnProperty('presupuestos') && response.presupuestos.length > 0) {
                        Swal.fire({
                            text: "Este paciente tiene al menos un presupuesto con el mismo servicio. ¿Seguro que no es una cita de presupuesto?",
                            icon: "info",
                            showCancelButton: true,
                            confirmButtonText: 'Si, no es una cita de presupuesto',
                            cancelButtonText: 'Cancelar, es una cita de presupuesto',
                            showLoaderOnConfirm: true,
                            preConfirm: () => {
                                // Continuar con lógica después de cerrar la alerta
                                // Puedes acceder a idItemValue aquí
                                return new Promise((resolve) => {
                                    // Realizar alguna lógica adicional si es necesario
                                    resolve();
                                });
                            },
                        }).then((result) => {
                            if (result.value) {
                                // Continuar con la lógica después de cerrar la alerta
                                Recarga();
                            } else {
                                return;
                            }
                        });
                    }
                },
                error: function (xhr, status, error) {
                    Swal.fire('Error en la petición AJAX:', error);
                    return;
                }
            });
        } else {
            Recarga();
        }
    }

    function Recarga() {
        <?php if ($accion == "nuevo" || $finalizado == true) { ?>
        document.form_cita.action = '<?php echo base_url(); ?>agenda/citas/nuevo/';
        document.form_cita.submit();
        <?php } ?>

        <?php if ($accion == "editar" && $finalizado != true) { ?>
        document.form_cita.action = '<?php echo base_url(); ?>agenda/citas/editar/<?= (isset($cita[0]['id_cita'])) ? $cita[0]['id_cita'] : '' ?>';
        document.form_cita.submit();
        <?php  } ?>
    }


    function ComprobarCliente() {
        if (document.form_cita.id_cliente.value > 0) {
            if (document.form_cita.id_empleado.value > 0) {
                return true;
            } else {
                Swal.fire("DEBES DE INDICAR UN EMPLEADO PARA LA CITA");
                return false;
            }
        } else {
            Swal.fire("DEBES DE INDICAR UN CLIENTE PARA LA CITA");
            return false;
        }
    }

    function Cerrar() {
        window.opener.location.reload();
        window.close();
    }
    <?php if (isset($id_cita_reload)) { ?>

    function CerrarYAnadir() {

        window.opener.CitasEditar(<?php echo $id_cita_reload; ?>);
        window.opener.location.reload();
        window.close();

    }
    <?php } ?>

    function Anular() {
        if (confirm("¿Desea realmente ANULAR la cita?")) {
            document.form_cita.action = "/agenda/citas/anular/<?php if (isset($cita[0]['id_cita'])) {
                echo $cita[0]['id_cita'];
            } ?>";
            document.form_cita.submit();
        }
    }

    function Finalizar() {
        Swal.fire({
            html: '¿DESEA REALMENTE FINALIZAR LA CITA?',
            showCancelButton: true,
            confirmButtonText: 'Si, finalizar',
            showLoaderOnConfirm: true,
            onBeforeOpen: () => {
            },

        }).then((result) => {
            if (result.value) {
                document.form_cita.action = "/agenda/citas/finalizar/<?= (isset($cita[0]['id_cita'])) ? $cita[0]['id_cita'] : '' ?>";
                document.form_cita.submit();
            }
        })
    }

    function NoVino() {
        Swal.fire({
            text: '¿Desea marcar la cita como NO VINO?',
            showCancelButton: true,
            confirmButtonText: 'Si, marcar como NO VINO',

        }).then((result) => {
            if (result.value) {
                document.form_cita.action = "/agenda/citas/no_vino/<?= (isset($cita[0]['id_cita'])) ? $cita[0]['id_cita'] : '' ?>";
                document.form_cita.submit();
            }
        })
    }

    function NotasClientes() {
        document.form_cita.observaciones.value = "";
        Recarga();
    }

    function OtroServicio() {
        if (document.getElementById("id_servicio2").style.display == 'block') {
            if (document.getElementById("id_servicio3").style.display == 'block') {
                if (document.getElementById("id_servicio4").style.display == 'block') {
                    if (document.getElementById("id_servicio5").style.display == 'block') {
                        document.getElementById("id_servicio6").style.display = 'block';
                    } else {
                        document.getElementById("id_servicio5").style.display = 'block';
                    }
                } else {
                    document.getElementById("id_servicio4").style.display = 'block';
                }
            } else {
                document.getElementById("id_servicio3").style.display = 'block';
            }
        } else {
            document.getElementById("id_servicio2").style.display = 'block';
        }
    }

    function OtroServicioPresupuesto() {
        var elementositem = $(".item_presupuesto").length;
        var elementosVisibles = $(".item_presupuesto:visible").length;
        var siguiente = elementosVisibles + 1;
        if (siguiente <= elementositem) {
            $('#presupuesto_item' + siguiente).show();
        } else {
            Swal.fire('EL PRESUPUESTO NO TIENE MÁS SERVICIOS')
        }
    }

    function QuitarServicioPresupuesto(button) {

        var div = $(button).closest('.item_presupuesto');
        var select = div.find('select[name="id_presupuesto_item[]"]');
        select.val(null).trigger('change');
        div.hide();
    }

    function QuitarServicio(elemento) {
        document.getElementById(elemento).style.display = 'none';
    }

    function OtroServicioInicializar() {
        <?php if (isset($cita[0]['id_servicio2'])) { ?>
        document.getElementById("id_servicio2").style.display = 'block';
        <?php } else { ?>
        document.getElementById("id_servicio2").style.display = 'none';
        <?php } ?>
        <?php if (isset($cita[0]['id_servicio3'])) { ?>
        document.getElementById("id_servicio3").style.display = 'block';
        <?php } else { ?>
        document.getElementById("id_servicio3").style.display = 'none';
        <?php } ?>
        <?php if (isset($cita[0]['id_servicio4'])) { ?>
        document.getElementById("id_servicio4").style.display = 'block';
        <?php } else { ?>
        document.getElementById("id_servicio4").style.display = 'none';
        <?php } ?>
        <?php if (isset($cita[0]['id_servicio5'])) { ?>
        document.getElementById("id_servicio5").style.display = 'block';
        <?php } else { ?>
        document.getElementById("id_servicio5").style.display = 'none';
        <?php } ?>
        <?php if (isset($cita[0]['id_servicio6'])) { ?>
        document.getElementById("id_servicio6").style.display = 'block';
        <?php } else { ?>
        document.getElementById("id_servicio6").style.display = 'none';
        <?php } ?>
    }


    <?php if ($accion == "nuevo" || $finalizado == true) { ?>
    OtroServicioInicializar();
    <?php } ?>
    <?php if ($accion == "finalizar") { ?>
    CerrarYAnadir();
    <?php } ?>
    <?php if ($accion == "guardar" || $accion == "modificar" || $accion == "anular" || $accion == "no_vino") { ?>
    Cerrar();
    <?php } ?>

    function EsOk() {
        if (document.form_cita.solo_este_empleado.checked) {
            document.form_nuevo_cliente.solo_este_empleado.value = 1;
        }
        document.form_nuevo_cliente.id_empleado.value = document.form_cita.id_empleado.value;
        document.form_nuevo_cliente.id_servicio.value = document.form_cita.id_servicio.value;
        document.form_nuevo_cliente.fecha.value = document.form_cita.fecha.value;
        document.form_nuevo_cliente.hora.value = document.form_cita.hora.value;
        document.form_nuevo_cliente.observaciones.value = document.form_cita.observaciones.value;
        return true;
    }

    function FinalizarNota(fila, id_nota_cita) {
        if (confirm("¿DESEA FINALIZAR LA NOTA DE CITA MARCADA?")) {
            document.getElementById("tabla_notas").deleteRow(fila.parentNode.rowIndex);
            $.ajax({
                url: "<?php echo base_url(); ?>clientes/finalizar_una_nota_citas/" + id_nota_cita,
                success: function (result) {
                    $("#borrar_nota").html(result);
                }
            });
            var f = document.getElementById("tabla_notas").rows.length;
            if (f == 1) {
                document.getElementById("tabla_notas").deleteRow(0);
            }
        } else {
            $(this).prop('checked', false)
        }
    }

    function FichaCliente(id_cliente) {
        var url = "<?php echo base_url(); ?>clientes/historialpopup/ver/" + id_cliente;
        openwindow('pago_cuenta', url, 800, 680);
    }

    function NuevaNota(id_cliente) {
        var url = "<?php echo base_url(); ?>clientes/nueva_nota_cita_agenda/" + id_cliente;
        openwindow('pago_cuenta', url, 640, 400);
    }

    function Cobrar(id_cliente, fecha) {
        var url = "<?php echo base_url(); ?>dietario/ficha/ver/" + id_cliente + "/" + fecha;
        openwindow('pago_cuenta', url, 850, 600);
    }

    function PagoPresupuesto() {
        $('#btn-registrar-pago').attr('disabled', 'disabled')
        $.ajax({
            url: '<?= base_url() ?>Presupuestos/pagoeurosajax',
            method: 'POST',
            data: $('#form_pagoeuros').serialize(),
            success: function (response) {
                console.log(response)
                if (response.status == 'success') {
                    window.location.reload();
                } else {
                    Swal.fire('Ha ocurrido un error');
                    $('#btn-registrar-pago').removeAttr('disabled')
                }
            },
            error: function (xhr, status, error) {
                Swal.fire('Error en la petición AJAX:', error);
                $('#btn-registrar-pago').removeAttr('disabled')
            }
        });


    }

    function ImporteMarcado(total) {
    }
</script>
</body>

</html>