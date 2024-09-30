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
    $pagetitle = ($accion == "nuevo" || $finalizado == true) ? 'AÑADIR CITA EN ESPERA' : 'EDITAR CITA EN ESPERA ' . $editar;
    $formaction = ($accion == "nuevo" || $finalizado == true) ? base_url() . 'avisos/citas_espera_gestion/guardar/' : base_url() . 'avisos/citas_espera_gestion/modificar/' . $cita[0]['id_cita_espera'];

    ?>
    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase"><?= $pagetitle ?></h1>
    <div class="card card-flush m-5">
        <div class="card-body p-5">
            <form id="form_cita" action="<?php echo $formaction; ?>" role="form" method="post" name="form_cita" onsubmit="return ComprobarCliente();">
                <div class="row mb-5 align-items-end">
                    <div class="col-8">
                        <label for="" class="form-label">Cliente:</label>
                        <select name="id_cliente" id="id_cliente" class="form-select form-select-solid" data-placeholder="Elegir ..." onchange="NotasClientes();">
                            <?php if (isset($cliente_elegido) && $cliente_elegido[0]['id_cliente'] > 0) { ?>
                                <option value="<?= $cliente_elegido[0]['id_cliente'] ?>" selected><?= $cliente_elegido[0]['nombre'] . ' ' . $cliente_elegido[0]['apellidos'] . ' (' . $cliente_elegido[0]['telefono'] . ')'; ?></option>
                            <?php } ?>
                        </select>
                        <script type="text/javascript">
                            $("#id_cliente").select2({
                                language: "es",
                                minimumInputLength: 4,
                                ajax: {
                                    delay: 0,
                                    url: function(params) {
                                        return '<?php echo RUTA_WWW; ?>/clientes/json/' + params.term;
                                    },
                                    dataType: 'json',
                                    processResults: function(data) {
                                        return {
                                            results: data
                                        };
                                    }
                                }
                            });
                        </script>
                    </div>
                    <div class="w-auto">
                        <button type="button" class="btn btn-info text-inverse-info btn-icon" data-bs-target="#stack-cliente" data-bs-toggle="modal" title="Añadir un nuevo Cliente"><i class="fas fa-user-plus"></i></button>
                        <?php if (isset($cliente_elegido)) {
                            if ($cliente_elegido[0]['id_cliente'] > 0) { ?>
                                <button type="button" class="btn btn-success text-inverse-success btn-icon" onclick="FichaCliente('<?= $cliente_elegido[0]['id_cliente'] ?>');" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Ficha cliente"><i class="fas fa-user"></i></button>

                                <button type="button" class="btn btn-secondary text-inverse-secondary btn-icon" onclick="NuevaNota('<?= $cliente_elegido[0]['id_cliente']; ?>');" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Añadir una Nueva Nota"><i class="fas fa-file-text"></i></button>
                        <?php }
                        } ?>
                    </div>
                </div>

                <?php if (isset($cliente_elegido) && $cliente_elegido[0]['id_cliente'] > 0 && isset($notas_citas) && $notas_citas != 0) { ?>
                    <div class="table-responsive mb-5">
                        <table id="tabla_notas" class="align-middle border border-secondary fs-6 gy-5 p-2 porder table table-rounded table-row-dashed">
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
                                                <input class="form-check-input" id="id_nota_cita_<?php echo $row['id_nota_cita'] ?>" type="checkbox" value="<?php echo $row['id_nota_cita'] ?>" onclick="FinalizarNota(this,'<?php echo $row['id_nota_cita'] ?>');"><i class="fas fa-trash ms-3"></i>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-dark fw-bold d-block mb-1 fs-6" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="<?php echo $row['nota'] ?>">
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
                        <select name="id_empleado" id="id_idusuario" onchange="Recarga();" data-control="select2" class="form-select form-select-solid w-auto">
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
                            <input class="form-check-input w-45px h-30px" type="checkbox" id="solo_este_empleado" name="solo_este_empleado" value="1" <?= (isset($cita[0]['solo_este_empleado']) && $cita[0]['solo_este_empleado'] == 1) ? "checked" : '' ?>>
                            <label class="form-check-label" for="solo_este_empleado"></label>
                        </div>
                    </div>
                    <?php if (isset($cita[0]['solo_este_empleado']) && $cita[0]['solo_este_empleado'] == 1) { ?>
                        <div class="col-12 text-warning text-center">
                            ATENCIÓN: El cliente sólo quiere cita con este empleado.
                        </div>
                    <?php } ?>
                </div>

                <div class="row mb-5 align-items-end">
                    <div class="col-8">
                        <label for="" class="form-label">Servicios <? (isset($id_familia_servicio) && $id_familia_servicio == 12) ? '/ Código Proveedor' : '' ?></label>
                        <select name="id_servicio" id="id_servicio" class="form-select form-select-solid w-auto" data-control="select2" data-placeholder="Elegir ..." onchange="Recarga();">
                            <option value=""></option>
                            <?php if (isset($servicios)) {
                                if ($servicios != 0) {
                                    foreach ($servicios as $key => $row) { ?>
                                        <option value="<?php echo $row['id_servicio']; ?>" <?= (isset($cita[0]['id_servicio']) && $row['id_servicio'] == $cita[0]['id_servicio']) ? "selected" : '' ?>>
                                            <?php echo strtoupper($row['nombre_familia'] . " - " . $row['nombre_servicio'] . " (" . $row['duracion'] . " min)"); ?>
                                        </option>
                            <?php }
                                }
                            } ?>
                        </select>
                        <input type="hidden" name="id_servicio_ultimo_marcado" value="<?= (isset($id_servicio_ultimo_marcado)) ? $id_servicio_ultimo_marcado : '' ?>" />
                    </div>
                    <div class="w-auto">
                        <button type="button" class="btn btn-info text-inverse-info btn-icon" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Añadir otro servicio" onclick="OtroServicio();"><i class="fas fa-plus"></i></button>
                    </div>
                </div>

                <?php for ($i = 2; $i <= 6; $i++) { ?>
                    <div id="id_servicio<?= $i ?>">
                        <div class="row mb-5 align-items-end">
                            <div class="col-8">
                                <label for="" class="form-label">Servicio <?= (isset($id_familia_servicio) && $id_familia_servicio == 12) ? '/ Código Proveedor ' : '' ?><?= $i ?></label>
                                <select name="id_servicio<?= $i ?>" id="id_servicio<?= $i ?>" class="form-select form-select-solid w-auto" data-control="select2" data-placeholder="Elegir ..." onchange="Recarga();">
                                    <option value=""></option>
                                    <?php if (isset($servicios)) {
                                        if ($servicios != 0) {
                                            foreach ($servicios as $key => $row) { ?>
                                                <option value="<?php echo $row['id_servicio']; ?>" <?= (isset($cita[0]['id_servicio' . $i]) && $row['id_servicio'] == $cita[0]['id_servicio' . $i]) ? "selected" : '' ?>>
                                                    <?php echo strtoupper($row['nombre_familia'] . " - " . $row['nombre_servicio'] . " (" . $row['duracion'] . " min)"); ?>
                                                </option>
                                    <?php }
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php if (isset($id_familia_servicio) && $id_familia_servicio == 12) { ?>
                    <input type="text" name="codigo_proveedor" class="form-control form-control-solid" value="<?= (isset($codigo_proveedor)) ? $codigo_proveedor : '' ?>" placeholder="Código Proveedor" required />
                <?php } ?>

                <div class="row mb-5 align-items-end">
                    <div class="col">
                        <label for="" class="form-label">Día de la cita (<?php echo $fecha_completa; ?>)</label>
                        <input type="date" id="fecha" name="fecha" onchange="Recarga();" value="<?= (isset($cita[0]['fecha_inicio_aaaammdd'])) ? $cita[0]['fecha_inicio_aaaammdd'] : '' ?>" class="form-control form-control-solid w-auto" placeholder="Fecha desde" required readonly />
                    </div>

                    <div class="col">
                        <label for="" class="form-label">Hora desde</label>
                        <select name="hora" data-placeholder="Elegir hora..." data-control="select2" class="form-select form-select-solid" required>
                            <option value=""></option>
                            <?php if (isset($horas_libres)) {
                                if ($horas_libres != 0) {
                                    foreach ($horas_libres as $key => $row) { ?>
                                        <option value="<?php echo $row; ?>" <?= (isset($cita[0]['hora_inicio']) && $row == $cita[0]['hora_inicio']) ? "selected" : '' ?>><?php echo $row; ?></option>
                            <?php }
                                }
                            } ?>
                        </select>
                    </div>

                    <div class="col">
                        <label for="" class="form-label">Hora hasta</label>
                        <select name="hora_fin" data-placeholder="Elegir hora..." data-control="select2" class="form-select form-select-solid" required>
                            <option value=""></option>
                            <?php if (isset($horas_libres)) {
                                if ($horas_libres != 0) {
                                    foreach ($horas_libres as $key => $row) { ?>
                                        <option value="<?php echo $row; ?>" <?= (isset($cita[0]['hora_fin']) && $row == $cita[0]['hora_fin']) ? "selected" : '' ?>><?php echo $row; ?></option>
                            <?php }
                                }
                            } ?>
                        </select>
                    </div>
                </div>

                <?php if (($accion != "nuevo" && $accion != "horarios") && (($this->session->userdata('id_perfil') == 0) || ($this->session->userdata('id_perfil') == 2) || ($this->session->userdata('id_perfil') == 3))) { ?>
                    <div class="row mb-5 align-items-end">
                        <div class="col-12">
                            <label for="" class="form-label">Estado de la cita</label>
                            <select name="estado" data-placeholder="Estado" data-control="select2" class="form-select form-select-solid" required>
                                <option value="Pendiente" <?= ((isset($cita[0]['estado'])) && ($cita[0]['estado'] == 'Pendiente')) ? 'selected' : '' ?>>Pendiente</option>
                                <option value="Perdida" <?= ((isset($cita[0]['estado'])) && ($cita[0]['estado'] == 'Perdida')) ? 'selected' : '' ?>>Perdida</option>
                                <option value="Agendada" <?= ((isset($cita[0]['estado'])) && ($cita[0]['estado'] == 'Agendada')) ? 'selected' : '' ?>>Agendada</option>
                            </select>
                        </div>
                    </div>
                <?php } ?>

                <div class="row mb-5 align-items-end border-bottom">
                    <div class="col-md-12 mb-5">
                        <label for="" class="form-label">Notas para la cita</label>
                        <textarea name="notas" placeholder="Nota para la cita" class="form-control form-control-solid" style="height: 50px;"><?= (isset($cita[0]['notas'])) ? $cita[0]['notas'] : '' ?></textarea>
                    </div>
                </div>
                <input type="hidden" name="observaciones" value="">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-sm btn-secondary text-inverse-secondary m-2" type="button" onclick="window.close();">Cerrar sin Cambios</button>
                        <?php if ($accion == "nuevo" || $accion == "horarios") { ?>
                            <button class="btn btn-sm btn-primary text-inverse-primary" type="submit">Añadir</button>
                        <?php } else { ?>
                            <button class="btn btn-sm btn-primary text-inverse-primary m-2" type="submit">Modificar</button>
                            
                        <?php } ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="stack-cliente" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-focus-on="input:first">
        <div class="modal-dialog">
            <div class="modal-content">
                <?php
                $actionformmodal = ($accion == "nuevo" || $accion == "horarios") ? base_url() . 'agenda/citas_espera_gestion/nuevo/-99' : base_url() . 'agenda/citas_espera_gestion/editar/' . ((isset($cita[0]['id_cita_espera'])) ? $cita[0]['id_cita_espera'] . '/-99' : '/-99');
                ?>
                <form name="form_nuevo_cliente" id="form_nuevo_cliente" action="<?= $actionformmodal ?>" method="post" onsubmit="return EsOk();">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">AÑADIR NUEVO CLIENTE</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-5 border-bottom">
                            <div class="col-12">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control form-control-solid" name="nombre" placeholder="Nombre" required />
                            </div>
                            <div class="col-12">
                                <label class="form-label">Apellidos</label>
                                <input type="text" class="form-control form-control-solid" name="apellidos" placeholder="Apellidos" required />
                            </div>
                            <div class="col-12">
                                <label class="form-label">Teléfono</label>
                                <input type="text" class="form-control form-control-solid" name="telefono" placeholder="Teléfono" style="width: 150px;" required />
                            </div>
                            <div class="col-12">
                                <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                                    <input class="form-check-input w-45px h-30px" type="checkbox" id="no_quiere_publicidad_modal" name="no_quiere_publicidad" value="1">
                                    <label class="form-check-label" for="no_quiere_publicidad_modal">NO quiero recibir publicidad</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Añadir Cliente</button>
                    </div>
                    <input type="hidden" name="solo_este_empleado" value="" />
                    <input type="hidden" name="id_empleado" value="" />
                    <input type="hidden" name="id_servicio" value="" />
                    <input type="hidden" name="fecha" value="<?= (isset($cita[0]['fecha_inicio_aaaammdd'])) ? $cita[0]['fecha_inicio_aaaammdd'] : '' ?>" />
                    <input type="hidden" name="hora" value="" />
                    <input type="hidden" name="observaciones" value="" />
                </form>
            </div>
        </div>
    </div>
    <script>
        function Recarga() {
            <?php if ($accion == "nuevo") { ?>
                document.form_cita.action = '<?php echo base_url(); ?>avisos/citas_espera_gestion/nuevo/';
                document.form_cita.submit();
            <?php } ?>
            <?php if ($accion == "editar") { ?>
                document.form_cita.action = '<?php echo base_url(); ?>avisos/citas_espera_gestion/editar/<?php if (isset($cita[0]['id_cita_espera'])) {
                                                                                                            echo $cita[0]['id_cita_espera'];
                                                                                                        } ?>';
                document.form_cita.submit();
            <?php } ?>
        }

        function ComprobarCliente() {
            if (document.form_cita.id_cliente.value > 0) {
                return true;
            } else {
                alert("DEBES DE INDICAR UN CLIENTE PARA LA CITA");
                return false;
            }
        }

        function Cerrar() {
            window.opener.location.reload();
            window.close();
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
        <?php if ($accion == "nuevo") { ?>
            OtroServicioInicializar();
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
                    success: function(result) {
                        $("#borrar_nota").html(result);
                    }
                });
                var f = document.getElementById("tabla_notas").rows.length;
                if (f == 1) {
                    document.getElementById("tabla_notas").deleteRow(0);
                }
            }
        }

        function NuevaNota(id_cliente) {
            var posicion_x;
            var posicion_y;
            var ancho = 640;
            var alto = 400;
            posicion_x = (screen.width / 2) - (ancho / 2);
            posicion_y = (screen.height / 2) - (alto / 2);
            window.open("<?php echo base_url(); ?>clientes/nueva_nota_cita_agenda/" + id_cliente, "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
        }

        function Cobrar(id_cliente, fecha) {
            var posicion_x;
            var posicion_y;
            var ancho = 800;
            var alto = 600;
            posicion_x = (screen.width / 2) - (ancho / 2);
            posicion_y = (screen.height / 2) - (alto / 2);
            window.open("<?php echo base_url(); ?>dietario/ficha/ver/" + id_cliente + "/" + fecha, "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
        }
        function FichaCliente(id_cliente) {
            var posicion_x;
            var posicion_y;
            var ancho = 800;
            var alto = 680;
            posicion_x = (screen.width / 2) - (ancho / 2);
            posicion_y = (screen.height / 2) - (alto / 2);
            window.open("<?php echo base_url(); ?>clientes/historialpopup/ver/" + id_cliente, "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
        }
    </script>
</body>

</html>