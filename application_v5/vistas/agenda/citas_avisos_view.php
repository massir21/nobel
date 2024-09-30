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
                <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="tablaCitasAvisos_dt">
            </div>
        </div>
        <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
            <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                <div class="w-auto ms-3">
                    <label for="" class="form-label">Centro:</label>
                    <select name="id_centro" id="id_centro" class="form-select form-select-solid w-auto" onchange="NuevoDiaFiltroCentro();">
                        <option value="">Todos</option>
                        <?php if (isset($centros_todos)) {
                            if ($centros_todos != 0) {
                                foreach ($centros_todos as $key => $row) {
                                    if ($row['id_centro'] > 1) { ?>
                                        <option value='<?php echo $row['id_centro']; ?>' <?=(isset($id_centro) && $row['id_centro'] == $id_centro) ? "selected":''?>><?php echo $row['nombre_centro']; ?></option>
                                    <?php }
                                }
                            }
                        } ?>
                    </select>
                </div>
            <?php } else { ?>
                <input type="hidden" name="id_centro" id="id_centro" value="<?php echo $this->session->userdata('id_centro_usuario'); ?>" />
            <?php } ?>
            <div class="w-auto ms-3">
                <label for="" class="form-label">Estado:</label>
                <select name="otroestado" id="otroestado" class="form-select form-select-solid w-auto" onchange="otroestado();">
                    <option value="0" <?= ($accion == 0) ? "selected":''?>>Pendientes</option>
                    <option value="1" <?=($accion == 1)?"selected":''?>>Enviados</option>
                    <option value="" <?=($accion == 3) ? "selected":''?>>Ambos</option>
                    <option value="2" <?=($accion == 3) ? "selected":''?>>Obsoletos</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">
            <table id="tablaCitasAvisos_dt" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th>Fecha</th>
                        <th>Centro</th>
                        <th>Cliente</th>
                        <th>Mensaje</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php /*if (isset($registros)) {
                        if ($registros != 0) {
                            $i = 0;
                            foreach ($registros as $key => $row) {
                                if ($row['enviado'] == 0) {
                                    $color = "background: #ffffff; font-weight: bold;";
                                    $estado = "Pendiente";
                                } else {
                                    $color = "background: #f2f2f2; text-decoration:line-through;";
                                    $estado = "Enviado";
                                } ?>
                                <tr id="fila<?php echo $i; ?>" style=" <?php //echo $color;?>">
                                    <td style="display: none;">
                                        <?php echo $row['fecha_creacion']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['fecha_creacion']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['centro']; ?>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6" onclick="laapi('<?php echo $i; ?>')" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Clic Whatsapp"><?php echo $row['cliente']; ?></span>
                                        <span class="text-muted fw-semibold text-muted d-block fs-7"><?php echo $row['telefono']; ?></span>
                                    </td>
                                    <td>
                                        <span class="text-muted fw-semibold fs-6" id="id<?php echo $i; ?>" onclick="copiarAlPortapapeles('<?php echo $i; ?>')" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Clic Copiar" ><?php echo $row['mensaje']; ?> </span>
                                    </td>
                                    <td>
                                    <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6" id="estado<?php echo $i; ?>" onclick="cambiar('<?php echo $i; ?>','<?php echo $row["id_aviso"]; ?>')" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Clic Cambiar"><?php echo $estado; ?></span>
                                    </td>
                                    <input type="hidden" id="valorestado<?php echo $i; ?>" value="<?php echo $row['enviado']; ?>" />
                                    <input type="hidden" id="idtelefono<?php echo $i; ?>" value="<?php echo $row['telefono']; ?>" />
                                    <input type="hidden" id="idmensaje<?php echo $i; ?>" value="<?php echo $row['mensaje']; ?>" />
                                </tr>
                                <?php $i++;
                            }
                        }
                    } */ ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    function copiarAlPortapapeles(id_elemento) {
        id = "id" + id_elemento;
        var aux = document.createElement("input");
        aux.setAttribute("value", document.getElementById(id).innerHTML);
        document.body.appendChild(aux);
        aux.select();
        document.execCommand("copy");
        document.body.removeChild(aux);
    }
    function cambiar(i, id_aviso) {
        idfila = document.getElementById('fila' + i);
        idestado = document.getElementById('estado' + i);
        idvalorestado = document.getElementById('valorestado' + i);
        estado = idvalorestado.value;
        $.get('<?php echo base_url(); ?>agenda/cambiar_aviso/' + id_aviso + "/" + estado, function(data, status) {
        });
        if (estado == 0) {
            color = "background: #f2f2f2; text-decoration:line-through;";
            idfila.style = color;
            idestado.innerHTML = "Enviado";
            idvalorestado.value = "1"
        } else {
            color = "background: #ffffff; font-weight: bold;";
            idfila.style = color;
            idestado.innerHTML = "Pendiente";
            idvalorestado.value = "0"
        }
    }
    function laapi(i) {
        telefono = '34' + document.getElementById('idtelefono' + i).value;
        mensaje = document.getElementById('idmensaje' + i).value;
        document.location.href = "https://api.whatsapp.com/send?phone=" + telefono + "&text=" + mensaje, "_blank";
    }
    function NuevoDiaFiltroCentro() {
        tablaCitasAvisos_dt.draw();
        /*
        valor = document.getElementById('idotroestado').value;
        document.location.href = "<?php echo base_url(); ?>agenda/leer_avisos_citas/" + document.getElementById("id_centro").value + "/" + valor;
        */
    }
    function otroestado() {
        tablaCitasAvisos_dt.draw();
        /*valor = document.getElementById('idotroestado').value;
        document.location.href = "<?php echo base_url(); ?>agenda/leer_avisos_citas/" + document.getElementById("id_centro").value + "/" + valor;*/
    }
    var tablaCitasAvisos_dt = $("#tablaCitasAvisos_dt").DataTable({
        info: true,
        paging: true,
        ordering: true,
        searching: true,
        stateSave: false,
        processing: true,
        serverSide: true,
        scrollX: true,
        autoWidth: false,
        order: [0, "desc"],
        pageLength: 50,
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Todos"],
        ],
        columns: [{
                //0
                titlee: "Fecha / Hora",
                name: "fecha_creacion",
                data: "fecha_creacion",
            },
            {
                // 1
                titlee: "centro",
                name: "centro",
                data: "centro",
            },
            {
                //2
                titlee: "cliente",
                name: "cliente",
                data: "cliente",
                render: function(data, type, row, meta) {
                    var html = `<span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6" onclick="laapi(${meta.row})" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Clic Whatsapp">${row.cliente}</span>
                        <span class="text-muted fw-semibold text-muted d-block fs-7">${row.telefono}</span>`;
                    return html;
                }
            },
            {
                //3
                titlee: "mensaje",
                name: "mensaje",
                data: "mensaje",
                render: function(data, type, row, meta) {
                    var html = `<span class="text-muted fw-semibold fs-6" id="id${meta.row}" onclick="copiarAlPortapapeles(${meta.row})" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Clic Copiar">${row.mensaje}</span>`;
                    return html;
                }
            },
            {
                //4
                titlee: "enviado",
                name: "enviado",
                data: "enviado",
                render: function(date, type, row, meta) {
                    var estado = (row.enviado == 0) ? 'Pendiente' : ((row.enviado == 1) ? 'Enviado' : 'Obsoleto');
                    return `<span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6" id="estado${meta.row}" onclick="cambiar('${meta.row}','${row.id_aviso}')" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Clic Cambiar">${estado}</span>
                        <input type="hidden" id="valorestado${meta.row}" value="${row.enviado}" />
                        <input type="hidden" id="idtelefono${meta.row}" value="${row.telefono}" />
                        <input type="hidden" id="idmensaje${meta.row}" value="${row.mensaje}" /> `;
                }
            }
        ],
        columnDefs: [{
                targets: [0, 1, 2, 3, 4],
                visible: true,
            },
            {
                targets: ["_all"],
                visible: false,
            },
            {
                targets: [],
                orderable: false,
            },
        ],
        ajax: {
            url: "<?php echo base_url(); ?>Agenda/get_leer_avisos_citas",
            type: "GET",
            datatype: "json",
            data: function(data) {
                var fecha_desde = $('[name="fecha_desde"]').val();
                var fecha_hasta = $('[name="fecha_hasta"]').val();
                var id_centro = $('[name="id_centro"]').val();
                var enviado = $('[name="otroestado"]').val();
                if (fecha_desde != "") {
                    data.fecha_desde = fecha_desde;
                }
                if (fecha_hasta != "") {
                    data.fecha_hasta = fecha_hasta;
                }
                if (id_centro != "") {
                    data.id_centro = id_centro;
                }
                if (enviado != "") {
                    data.enviado = enviado;
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
        dom:
            /*"<'row'" +
            				"<'col-sm-6 d-flex align-items-center justify-conten-start'f>" +
            				"<'col-sm-6 d-flex align-items-center justify-content-end'>" +
            				">" +*/
            "<'table-responsive'tr>" +
            "<'row'" +
            "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'li>" +
            "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
            ">",
        headerCallback: function(thead, data, start, end, display) {},
        createdRow: function(row, data, dataIndex) {
            $(row).attr("id", "fila"+dataIndex);
        },
        drawCallback: function(settings) {},
        initComplete: function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl, {
                    'trigger': 'hover'
                })
})
        },
    });
    $(document).on("click", "#filterbutton", function() {
        tablaCitasAvisos_dt.draw();
    });
</script>