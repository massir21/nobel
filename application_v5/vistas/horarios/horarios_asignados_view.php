<link href="<?= base_url() ?>recursos/tempusdominus/css/tempusdominus-bootstrap-4.css" rel="stylesheet" type="text/css" />
<script src='<?php echo base_url(); ?>recursos/tempusdominus/js/tempusdominus-bootstrap-4.js'></script>

<?php if (isset($estado)) {
    if ($estado > 0) { ?>
        <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
            <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE GUARDÓ CORRECTAMENTE</div>
            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="fa-times fas fs-3 text-primary"></i>
            </button>
        </div>
    <?php } ?>
<?php } ?>
<?php if (isset($borrado)) { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE BORRÓ CORRECTAMENTE</div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="fa-times fas fs-3 text-primary"></i>
        </button>
    </div>
<?php } ?>
<?php if (isset($rango_fechas_error)) { ?>
    <div class="alert alert-dismissible alert-warning d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">YA EXISTE UNA FECHA O RANGO DE FECHAS, QUE CUBRE LA FECHA INDICADA. ESPECIFIQUE OTRO RANGO POR FAVOR.<button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="fa-times fas fs-3 text-primary"></i>
            </button>
        </div>
    </div>
<?php } ?>
<?php $tipojornada  = ['Mañana', 'Tarde', 'Continua', 'Extra', 'Baja', 'Vacaciones'] ?>

<?php /* 
    <div class="card card-flush">
        <div class="card-header align-items-end py-5 gap-2 gap-md-5">
            <div class="card-title w-100 align-items-end">
                Horarios Asignados a <?php echo $usuario[0]['nombre'] . " " . $usuario[0]['apellidos']; ?>
            </div>
        </div>
        <div class="card-body pt-6">
            <?php if ($accion != "editar") { ?>
                <h3 class="card-title">SELECCIÓN HORARIOS OPCIÓN A</h3>
                <form id="form_A" action="<?php echo base_url(); ?>horarios/gestion/guardar/<?php echo $usuario[0]['id_usuario'] ?>" role="form" method="post" name="form_A" onsubmit="ActualizaFechaFin();" class="mb-8 pb-4 border-bottom">
                    <div class="row mb-5 align-items-end">
                        <div class="col-md-2">
                            <label for="" class="form-label">Jornada</label>
                            <select name="jornada" id="jornada" class="form-select form-select-solid" onchange="Jornada();" required>
                                <option value="">Elegir...</option>
                                <?php foreach ($tipojornada as $key => $value) { ?>
                                    <option value="<?= $value ?>" <?= (isset($registro_horario) && $registro_horario[0]['jornada'] == $value) ? "selected" : '' ?>><?= $value ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="" class="form-label">Fecha (aaaa-mm-dd)</label>
                            <input name="fecha_inicio" class="form-control form-control-solid" type="date" value="<?php if (isset($registro_horario)) {
                                                                                                                        echo $registro_horario[0]['fecha_inicio_ddmmaaaa'];
                                                                                                                    } ?>" required />
                            <input name="fecha_fin" class="form-control form-control-solid" type="hidden" value="<?php if (isset($registro_horario)) {
                                                                                                                        echo $registro_horario[0]['fecha_inicio_ddmmaaaa'];
                                                                                                                    } ?>" />
                        </div>
                        <div class="col-md-2">
                            <label for="" class="form-label">Hora Inicio</label>
                            <input name="hora_inicio" class="form-control form-control-solid" type="time" value="<?php if (isset($registro_horario)) {
                                                                                                                        echo $registro_horario[0]['hora_inicio'];
                                                                                                                    } ?>" required />
                        </div>
                        <div class="col-md-2">
                            <label for="" class="form-label">Hora Fin</label>
                            <input name="hora_fin" class="form-control form-control-solid" type="time" value="<?php if (isset($registro_horario)) {
                                                                                                                    echo $registro_horario[0]['hora_fin'];
                                                                                                                } ?>" required />
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Obrevaciones</label>
                            <textarea class="form-control form-control-solid" name="notas"><?php if (isset($registro_horario)) {
                                                                                                echo $registro_horario[0]['notas_horario'];
                                                                                            } ?></textarea>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-primary text-inverse-primary" type="submit">Agregar</button>
                        </div>
                    </div>
                </form>
            <?php } ?>
            <h3 class="card-title">SELECCIÓN HORARIOS OPCIÓN A</h3>
            <?php if ($accion != "editar") {
                $actionform = base_url() . 'horarios/gestion/guardar/' . $usuario[0]['id_usuario'];
            } else {
                $id_horario_form = (isset($registro_horario)) ? $registro_horario[0]['id_horario'] : '';
                $actionform = base_url() . 'horarios/gestion/actualizar/' . $usuario[0]['id_usuario'] . '/' . $id_horario_form;
            } ?>
            <form id="form" action="<?= $actionform ?>" role="form" method="post" name="form" class="mb-8 pb-4 border-bottom" onsubmit="return EsOk_B();">
                <div class="row mb-5 align-items-end">
                    <div class="col-md-2">
                        <label for="" class="form-label">Jornada</label>
                        <select name="jornada" class="form-select form-select-solid" onchange="Jornada();" required>
                            <option value="">Elegir...</option>
                            <?php foreach ($tipojornada as $key => $value) { ?>
                                <option value="<?= $value ?>" <?= (isset($registro_horario) && $registro_horario[0]['jornada'] == $value) ? "selected" : '' ?>><?= $value ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="" class="form-label">Fecha Inicio (aaaa-mm-dd)</label>
                        <input name="fecha_inicio" class="form-control form-control-solid" type="date" value="<?php if (isset($registro_horario)) {
                                                                                                                    echo $registro_horario[0]['fecha_inicio_ddmmaaaa'];
                                                                                                                } ?>" required />
                    </div>
                    <div class="col-md-2">
                        <label for="" class="form-label">Fecha Fin (aaaa-mm-dd)</label>
                        <input name="fecha_fin" class="form-control form-control-solid" type="date" value="<?php if (isset($registro_horario)) {
                                                                                                                echo $registro_horario[0]['fecha_fin_ddmmaaaa'];
                                                                                                            } ?>" required />
                    </div>
                    <div class="col-md-2">
                        <label for="" class="form-label">Hora Inicio</label>
                        <input name="hora_inicio" class="form-control form-control-solid" type="time" value="<?php if (isset($registro_horario)) {
                                                                                                                    echo $registro_horario[0]['hora_inicio'];
                                                                                                                } ?>" required />
                    </div>
                    <div class="col-md-2">
                        <label for="" class="form-label">Hora Fin</label>
                        <input name="hora_fin" class="form-control form-control-solid" type="time" value="<?php if (isset($registro_horario)) {
                                                                                                                echo $registro_horario[0]['hora_fin'];
                                                                                                            } ?>" required />
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Obrevaciones</label>
                        <textarea class="form-control form-control-solid" name="notas"><?php if (isset($registro_horario)) {
                                                                                            echo $registro_horario[0]['notas_horario'];
                                                                                        } ?></textarea>
                    </div>
                    <div class="col-md-4 text-end">
                        <button class="btn btn-primary text-inverse-primary" type="submit"><?= ($accion != "editar") ? 'Agregar' : 'Actualizar' ?></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body pt-6">
            <div class="table-responsive">
                <table id="logs" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                    <thead class="">
                        <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                            <th style="display: none;">-</th>
                            <th>Jornada</th>
                            <th style="display: none;">ID</th>
                            <th>Fecha</th>
                            <th>Hora Inicio</th>
                            <th>Hora Fin</th>
                            <th>Observaciones</th>
                            <th>Editar</th>
                            <th>Borrar</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($registros)) {
                            if ($registros != 0) {
                                foreach ($registros as $key => $row) { ?>
                                    <tr>
                                        <td style="display: none;"><?php echo $row['fecha_inicio_aaaammdd']; ?></td>
                                        <td>
                                            <?php if ($row['jornada'] == "Mañana") {
                                                $color = 'info';
                                            } elseif ($row['jornada'] == "Tarde") {
                                                $color = 'warning';
                                            } elseif ($row['jornada'] == "Continua") {
                                                $color = 'success';
                                            } elseif ($row['jornada'] == "Extra") {
                                                $color = 'primary';
                                            } elseif ($row['jornada'] == "Baja") {
                                                $color = 'danger';
                                            } elseif ($row['jornada'] == "Vacaciones") {
                                                $color = 'default';
                                            } ?>
                                            <span class="badge d-block fs-4 badge-<?= $color ?>"><?php echo $row['jornada'] ?></span>
                                        </td>
                                        <td style="display: none;"><?php echo $row['fecha_inicio_aaaammdd']; ?></td>
                                        <td style="text-align: center;">
                                            <?php if ($row['fecha_inicio_f'] != $row['fecha_fin_f']) {
                                                echo "<span style='font-size: 0px;'>" . $row['fecha_inicio_aaaammdd'] . "</span>" . $row['fecha_inicio_f'] . "<br>hasta<br>" . $row['fecha_fin_f'];
                                            } else {
                                                echo "<span style='font-size: 0px;'>" . $row['fecha_inicio_aaaammdd'] . "</span>" . $row['fecha_inicio_f'];
                                            } ?>
                                        </td>
                                        <td><?php echo $row['hora_inicio']; ?></td>
                                        <td><?php echo $row['hora_fin']; ?> </td>
                                        <td><?php echo $row['notas_horario']; ?></td>
                                        <td>
                                            <a href="<?php echo base_url(); ?>horarios/gestion/editar/<?php echo $row['id_usuario'] ?>/<?php echo $row['id_horario'] ?>" class="btn btn-sm btn-icon btn-warning"><i class="fa-regular fa-pen-to-square"></i></a>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-icon btn-danger" onclick="Borrar(<?php echo $row['id_usuario'] ?>,<?php echo $row['id_horario'] ?>);"><i class="fa-solid fa-trash"></i></button>
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

    */ ?>



<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="tabla_horarios">
            </div>
        </div>
        <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
            <div class="w-auto">
                <label for="" class="form-label">Fecha desde</label>
                <input type="date" id="fecha_desde" name="fecha_desde" class="form-control form-control-solid w-auto" placeholder="Fecha desde" />
            </div>
            <div class="w-auto ms-3">
                <label for="" class="form-label">Fecha hasta</label>
                <input type="date" id="fecha_hasta" name="fecha_hasta" class="form-control form-control-solid w-auto" placeholder="Fecha hasta" />
            </div>

        </div>
    </div>

    <div class="card-body pt-6">
        <div class="table-responsive">
            <table id="tabla_horarios" class="table align-middle table-striped table-row-dashed fs-6 gy-2" data-export="horarios" data-fetch="">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th class="col_id">#</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th style="width: 50%;">Notas</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold"></tbody>
            </table>
        </div>
    </div>
</div>

<div id="horario_modal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="addNewDepLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php $attr = ['id' => 'form_horario_new'];
            echo form_open_multipart('Horarios/manageHorario', $attr); ?>
            <div class="modal-header">
                <h5 class="modal-title" id="horario_modalLabel">Asignar horarios a <?php echo $usuario[0]['nombre'] . " " . $usuario[0]['apellidos']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row mb-5 border-bottom">
                    <div class="col-12">
                        <label class="control-label">Día o días a generar</label>
                        <div id="datetimepicker14"></div>
                    </div>
                </div>

                <div class="row mb-3 pb-3 border-bottom form-row">
                    <div class="col-sm-4">
                        <label for="" class="form-label">Jornada</label>
                        <select name="jornada[]" class="form-select form-select-solid" onchange="Jornada2($(this));" required>
                            <option value="">Elegir...</option>
                            <?php foreach ($tipojornada as $key => $value) { ?>
                                <option value="<?= $value ?>"><?= $value ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-sm-4">
                        <label class="control-label">Hora de inicio</label>
                        <input type="time" class="form-control" name="hora_inicio_nuevo[]">
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Hora de fin</label>
                        <input type="time" class="form-control" name="hora_fin_nuevo[]">
                    </div>
                    <div class="col-10">
                        <label class="control-label" for="hora_fin_nuevo">Notas</label>
                        <textarea class="form-control" name="notas_horario[]" rows="2"></textarea>
                    </div>
                    <div class="col-sm-1 pt-30">
                        <button type="button" class="btn btn-sm btn-icon btn-info mt-7" add-horario-row><i class="fas fa-plus"></i></button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="reset" class="btn btn-danger">Limpiar</button>
                <button type="button" class="btn btn-dark" id="btn-submit-form">Guardar</button>
            </div>
            <?= form_hidden('id_usuario', $usuario[0]['id_usuario']); ?>
            <?= form_hidden('action', ''); ?>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
    function Borrar(id_usuario, id_horario) {
        if (confirm("¿DESEA BORRAR EL HORARIO ELEGIDO?")) {
            document.location.href = "<?php echo base_url(); ?>horarios/gestion/borrar/" + id_usuario + "/" + id_horario;
        }
        return false;
    }

    function ActualizaFechaFin() {
        document.form_A.fecha_fin.value = document.form_A.fecha_inicio.value;
        return true;
    }

    function EsOk_B() {
        var fecha_inicio = new Date(document.form.fecha_inicio.value);
        var fecha_fin = new Date(document.form.fecha_fin.value);
        if ((fecha_inicio.getTime() > fecha_fin.getTime())) {
            alert("LA FECHA DE FIN NO PUEDE SER MENOR QUE LA FECHA INICIAL");
            return false;
        } else {
            return true;
        }
    }

    function Jornada() {
        if (document.form_A.jornada.value == "Mañana") {
            document.form_A.hora_inicio.value = "10:00";
            document.form_A.hora_fin.value = "16:00";
        }
        if (document.form_A.jornada.value == "Tarde") {
            document.form_A.hora_inicio.value = "16:00";
            document.form_A.hora_fin.value = "22:00";
        }
        if (document.form_A.jornada.value == "Continua") {
            document.form_A.hora_inicio.value = "10:00";
            document.form_A.hora_fin.value = "22:00";
        }
        if (document.form.jornada.value == "Mañana") {
            document.form.hora_inicio.value = "10:00";
            document.form.hora_fin.value = "16:00";
        }
        if (document.form.jornada.value == "Tarde") {
            document.form.hora_inicio.value = "16:00";
            document.form.hora_fin.value = "22:00";
        }
        if (document.form.jornada.value == "Continua") {
            document.form.hora_inicio.value = "10:00";
            document.form.hora_fin.value = "22:00";
        }
    }

    function Jornada2(elem) {
        var row = elem.closest('.form-row');
        if (elem.val() == "Mañana") {
            $(row).find('[name="hora_inicio_nuevo[]"]').val("10:00");
            $(row).find('[name="hora_fin_nuevo[]"]').val("16:00");
        }
        if (elem.val() == "Tarde") {
            $(row).find('[name="hora_inicio_nuevo[]"]').val("16:00");
            $(row).find('[name="hora_fin_nuevo[]"]').val("22:00");
        }
        if (elem.val() == "Continua") {
            $(row).find('[name="hora_inicio_nuevo[]"]').val("09:00");
            $(row).find('[name="hora_fin_nuevo[]"]').val("19:30");
        }
    }

    $(function() {
        "use strict";
        // HORARIOS
        var tabla_horarios;
        var titledoc = "Horarios";
        tabla_horarios = $("#tabla_horarios").DataTable({
            info: true,
            paging: true,
            ordering: true,
            searching: true,
            stateSave: false,
            processing: true,
            serverSide: true,
            scrollX: true,
            autoWidth: false,
            order: [1, "asc"],
            pageLength: 50,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "Todos"],
            ],
            columns: [{ //0
                    title: 'Jornada',
                    name: "id_horario",
                    data: "id_horario",
                    className: "",
                    render: function(data, type, row) {
                        var html = '';
                        var color = '';
                        if (row.jornada == "Mañana") {
                            color = 'info';
                        } else if (row.jornada == "Tarde") {
                            color = 'warning';
                        } else if (row.jornada == "Continua") {
                            color = 'success';
                        } else if (row.jornada == "Extra") {
                            color = 'primary';
                        } else if (row.jornada == "Baja") {
                            color = 'danger';
                        } else if (row.jornada == "Vacaciones") {
                            color = 'default';
                        }
                        html += `<span class="badge d-block fs-4 badge-${color}" data-bs-toggle="tooltip" title="ID ${row.id_horario}">${row.jornada}</span>`;

                        //var html = row.id_horario;
                        return html;
                    },
                },
                { //1
                    title: "Inicio",
                    name: "fecha_inicio",
                    data: "fecha_inicio",
                    render: function(data, type, row) {
                        var html = `<input class="form-control" name="inicio" id="inicio_${row.id_horario}" type="datetime-local" placeholder="" value="${row.fecha_inicio}" />`;
                        return html;
                    },
                },
                {
                    //2
                    title: "Fin",
                    name: "fecha_fin",
                    data: "fecha_fin",
                    render: function(data, type, row) {
                        var html = `<input class="form-control" name="fin" id="fin_${row.id_horario}" type="datetime-local" placeholder="" value="${row.fecha_fin}" />`;
                        return html;
                    },
                },
                {
                    //2
                    title: "Notas",
                    name: "notas_horario",
                    data: "notas_horario",
                    render: function(data, type, row) {
                        var html = `<textarea class="form-control" name="notas" rows="1" id="notas_${row.id_horario}">${row.notas_horario}</textarea>`;
                        return html;
                    },
                },
                {
                    //4
                    title: "",
                    name: "editar",
                    data: "id_horario",
                    render: function(data, type, row) {
                        var html = `<button type="button" class="btn btn-sm btn-icon btn-warning" data-editar-horario="${row.id_horario}"><i class="fa-regular fa-pen-to-square"></i></button>`;
                        return html;
                    },
                },
                {
                    //5
                    title: "",
                    name: "eliminar",
                    data: "id_horario",
                    render: function(data, type, row) {
                        var html = `<button class="btn btn-sm btn-icon btn-danger" data-borrar-horario="${row.id_horario}"><i class="fa-solid fa-trash"></i></button></div>`;
                        return html;
                    },
                },
            ],
            columnDefs: [{
                    targets: [0, 1, 2, 3, 4, 5],
                    visible: true,
                },
                {
                    targets: ["_all"],
                    visible: false,
                },
                {
                    targets: [4, 5],
                    orderable: false,
                },
                {
                    "targets": 0,
                    "width": "100px"
                },
                {
                    "targets": 1,
                    "width": "150px"
                },
                {
                    "targets": 2,
                    "width": "150px"
                },
                {
                    "targets": 4,
                    "width": "80px"
                },
                {
                    "targets": 5,
                    "width": "80px"
                }

            ],
            ajax: {
                url: "<?= base_url() ?>Horarios/get_horarios_usuario",
                type: "GET",
                datatype: "json",
                data: function(data) {
                    var id_usuario = <?= $usuario[0]['id_usuario'] ?>;
                    var fecha_desde = $('[name="fecha_desde"]').val();
                    var fecha_hasta = $('[name="fecha_hasta"]').val();
                    var id_centro = $('[name="id_centro"]').val();
                    if (fecha_desde != "") {
                        data.fecha_desde = fecha_desde;
                    }
                    if (fecha_hasta != "") {
                        data.fecha_hasta = fecha_hasta;
                    }

                    if (id_usuario != "") {
                        data.id_usuario = id_usuario;
                    }
                },
            },
            language: {
                "sProcessing": "Procesando...",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfoEmpty": "No hay resultados",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "sLengthMenu": "<div class=\"\">_MENU_</div>",
                "sSearch": "<div class=\"\">_INPUT_</div>",
                "sSearchPlaceholder": "Escribe para buscar...",
                "sInfo": "_START_ de _END_ (_TOTAL_ total)",
                "oPaginate": {
                    "sPrevious": "",
                    "sNext": ""
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },
            dom: "<'table-responsive'tr>" +
                "<'row'" +
                "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'li>" +
                "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                ">",
            buttons: {
                buttons: [{
                    text: "Exportar Excel",
                    extend: "excelHtml5",
                    title: 'Comisiones',
                    className: "btn btn-warning text-inverse-warning",
                    attr: {
                        "data-tooltip": "Exportar tabla en excel",
                        "data-placement": "auto",
                        title: "Exportar tabla en excel",
                    },
                    exportOptions: {
                        columns: ":not(.noexp)",
                        orthogonal: "export",
                    },
                }, ],
                dom: {
                    button: {
                        className: "btn",
                    },
                },
            },
            headerCallback: function(thead, data, start, end, display) {},
            createdRow: function(row, data, dataIndex) {},
            drawCallback: function(settings) {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl, {
                        'trigger': 'hover'
                    })
                })
            },
            initComplete: function() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl, {
                        'trigger': 'hover'
                    })
                })
            },
        });

        var buttons = new $.fn.dataTable.Buttons(tabla_horarios, {
            buttons: [{
                text: "Excel",
                extend: "excelHtml5",
                title: 'Liquidaciones',
                className: "btn btn-warning text-inverse-warning",
                attr: {
                    "data-tooltip": "Exportar tabla en excel",
                    "data-placement": "auto",
                    title: "Exportar tabla en excel",
                },
                exportOptions: {
                    columns: ":not(.noexp)",
                    orthogonal: "export",
                },
            }, {
                text: "CSV",
                extend: "csvHtml5",
                title: 'Liquidaciones',
                className: "btn btn-warning text-inverse-warning",
                attr: {
                    "data-tooltip": "Exportar tabla en CSV",
                    "data-placement": "auto",
                    title: "Exportar tabla en CSV",
                },
                exportOptions: {
                    columns: ":not(.noexp)",
                    orthogonal: "export",
                },
            }]
        }).container().appendTo($('#buttons'));

        $('[data-table-search]').on('input', function() {
            tabla_horarios.search($(this).val()).draw();
        });

        $(document).on('change', '#fecha_desde, #fecha_hasta', function() {
            tabla_horarios.draw()
        })
        $("#datetimepicker14").datetimepicker({
            debug: true,
            inline: true,
            allowMultidate: true,
            multidateSeparator: ",",
            format: "L",
            locale: "es",
            useCurrent: false,
        });

        $(document).on("click", "#add_horario_modal", function() {
            $("#datetimepicker3, #datetimepicker2").datetimepicker(
                "date",
                new Date("2022-01-01 00:00:00")
            );
            $("#datetimepicker14").datetimepicker("clear");
            $("#horario_modal").modal("show");
        });

        $(document).on("click", "#btn-submit-form", function() {
            var btn = $(this);
            var fechas_init = $("#datetimepicker14")
                .data("datetimepicker")
                .date()
                .split(",");
            var fechas = "";
            for (let index = 0; index < fechas_init.length; index++) {
                const element = fechas_init[index];
                var date = new Date(element);
                fechas += date.toISOString().split("T")[0];
                if (index < fechas_init.length - 1) {
                    fechas += ",";
                }
            }
            var fd = new FormData(document.getElementById("form_horario_new"));
            fd.append("fechas", fechas);
            $.ajax({
                    url: "<?= base_url() ?>Horarios/horarios_add_batch",
                    method: "POST",
                    contentType: false,
                    processData: false,
                    data: fd,
                    beforeSend: function() {
                        btn.attr("disabled", "disabled");
                    },
                })
                .done(function(response) {
                    var response = JSON.parse(response);
                    $('[name="secure_request_token"]').val(response.csrf);
                    if (response.error > 0) {
                        var errorhtml = "";
                        if (response.hasOwnProperty("error_validation")) {
                            $.each(response.error_validation, function(i, value) {
                                errorhtml += value + "<br>";
                            });
                        }
                        if (response.hasOwnProperty("error_msn")) {
                            errorhtml += response.error_msn;
                        }
                        swal.fire({
                            title: "ERROR",
                            html: errorhtml,
                            willClose: function() {
                                btn.removeAttr("disabled");
                            },
                        });
                        return;
                    } else {
                        swal.fire({
                            icon: "success",
                            html: response.msn,
                            timer: 2000,
                            willClose: function() {
                                btn.removeAttr("disabled");
                                $("#horario_modal").modal("hide");
                                tabla_horarios.draw();
                            },
                        });
                    }
                })
                .always(function(jqXHR, textStatus) {
                    if (textStatus != "success") {
                        swal.fire({
                            icon: "error",
                            title: "Ha ocurrido un error AJAX",
                            html: jqXHR.statusText,
                            timer: 5000,
                            willClose: function() {
                                if (reload != false) {
                                    window.location.reload();
                                }
                            },
                        });
                    }
                });
        });

        $(document).on("click", "[data-editar-horario]", function() {
            var btn = $(this);
            var row = btn.closest("tr");
            var id_horario = btn.data("editar-horario");
            var inicio = $("#inicio_" + id_horario).val();
            var fin = $("#fin_" + id_horario).val();
            var notas = $("#notas_" + id_horario).val();
            var fd = new FormData();
            fd.append("id_horario", id_horario);
            fd.append("inicio", inicio);
            fd.append("fin", fin);
            fd.append("notas", notas);
            if ($("#eliminar_" + id_horario).is(":checked")) {
                fd.append("eliminar", 1);
            }
            fd.append("secure_request_token", $('[name="secure_request_token"]').val());
            $.ajax({
                    url: "<?= base_url() ?>Horarios/horarios_editar",
                    method: "POST",
                    contentType: false,
                    processData: false,
                    data: fd,
                    beforeSend: function() {
                        btn.attr("disabled", "disabled");
                        row.css("opacity", ".5");
                    },
                })
                .done(function(response) {
                    var response = JSON.parse(response);
                    $('[name="secure_request_token"]').val(response.csrf);
                    if (response.error > 0) {
                        var errorhtml = "";
                        if (response.hasOwnProperty("error_validation")) {
                            $.each(response.error_validation, function(i, value) {
                                errorhtml += value + "<br>";
                            });
                        }
                        if (response.hasOwnProperty("error_msn")) {
                            errorhtml += response.error_msn;
                        }
                        swal.fire({
                            title: "ERROR",
                            html: errorhtml,
                            willClose: function() {
                                btn.removeAttr("disabled");
                                row.css("opacity", "1");
                            },
                        });
                        return;
                    } else {
                        swal.fire({
                            icon: "success",
                            html: response.msn,
                            timer: 2000,
                            willClose: function() {
                                if (response.data.borrado == 1) {
                                    $(row).remove();
                                }
                                btn.removeAttr("disabled");
                                row.css("opacity", "1");
                            },
                        });
                    }
                })
                .always(function(jqXHR, textStatus) {
                    if (textStatus != "success") {
                        swal.fire({
                            icon: "error",
                            title: "Ha ocurrido un error AJAX",
                            html: jqXHR.statusText,
                            timer: 5000,
                            willClose: function() {
                                if (reload != false) {
                                    window.location.reload();
                                }
                            },
                        });
                    }
                });
        });

        $(document).on("click", "[data-borrar-horario]", function() {
            var btn = $(this);
            var row = btn.closest("tr");
            var id_horario = btn.data("borrar-horario");

            Swal.fire({
                title: 'Eliminar horario',
                html: `¿Seguro que quieres eliminar el horario?`,
                showCancelButton: true,
                confirmButtonText: 'Si, eliminar',
                showLoaderOnConfirm: true
            }).then((result) => {
                if (result.value) {
                    
                    var fd = new FormData();
                    fd.append("id_horario", id_horario);
                    fd.append("eliminar", 1);
                    fd.append("secure_request_token", $('[name="secure_request_token"]').val());
                    $.ajax({
                            url: "<?= base_url() ?>Horarios/horarios_borrar",
                            method: "POST",
                            contentType: false,
                            processData: false,
                            data: fd,
                            beforeSend: function() {
                                btn.attr("disabled", "disabled");
                                row.css("opacity", ".5");
                            },
                        })
                        .done(function(response) {
                            var response = JSON.parse(response);
                            $('[name="secure_request_token"]').val(response.csrf);
                            if (response.error > 0) {
                                var errorhtml = "";
                                if (response.hasOwnProperty("error_validation")) {
                                    $.each(response.error_validation, function(i, value) {
                                        errorhtml += value + "<br>";
                                    });
                                }
                                if (response.hasOwnProperty("error_msn")) {
                                    errorhtml += response.error_msn;
                                }
                                swal.fire({
                                    title: "ERROR",
                                    html: errorhtml,
                                    willClose: function() {
                                        btn.removeAttr("disabled");
                                        row.css("opacity", "1");
                                    },
                                });
                                return;
                            } else {
                                swal.fire({
                                    icon: "success",
                                    html: response.msn,
                                    timer: 2000,
                                    willClose: function() {
                                        if (response.data.borrado == 1) {
                                            $(row).remove();
                                        }
                                        btn.removeAttr("disabled");
                                        row.css("opacity", "1");
                                    },
                                });
                            }
                        })
                        .always(function(jqXHR, textStatus) {
                            if (textStatus != "success") {
                                swal.fire({
                                    icon: "error",
                                    title: "Ha ocurrido un error AJAX",
                                    html: jqXHR.statusText,
                                    timer: 5000,
                                    willClose: function() {
                                        if (reload != false) {
                                            window.location.reload();
                                        }
                                    },
                                });
                            }
                        });

                }
            })
        })

        $(document).on('click', '[add-horario-row]', function() {
            var button = $(this)
            var row = $(this).closest('.form-row');
            var clone = $(row).clone();
            clone.find("input").val("")
            clone.find("textarea").val("")
            clone.insertAfter(row);
            button.removeAttr('add-horario-row');
            button.attr('remove-horario-row', 1);
            button.html('<i class="fas fa-minus"></i>')
        });

        $(document).on('click', '[remove-horario-row]', function() {
            var button = $(this)
            var row = $(this).closest('.form-row');
            $(row).remove();
        });
    });
</script>