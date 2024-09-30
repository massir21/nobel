<?php if (isset($estado)) {
    if ($estado > 0) { ?>
        <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
            <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE GUARDÓ CORRECTAMENTE</div>
            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="fa-times fas fs-3 text-primary"></i>
            </button>
        </div>
    <?php } else { ?>
        <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
            <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">NO SE HA PODIDO REALIZAR EL REGISTRO DE DATOS</div>
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

<?php if (isset($actionno)) { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center"><?= $actionno ?></div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="fa-times fas fs-3 text-primary"></i>
        </button>
    </div>
<?php } ?>

<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title w-100 justify-content-between">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="myTable1">
            </div>
            <?php
            /* CHAINS 20240221 - Eliminamos este filtro
            if ($this->session->userdata('id_perfil') == 0) { ?>
                <div class="m-1">
                    <select name="filter_revisado" id="filter_revisado" class="form-select form-select-solid w-auto">
                        <option value="">Cualquier estado</option>
                        <option value="0">Sin revisar</option>
                        <option value="1">Revisado</option>
                    </select>
                </div>
            <?php }
            */
            ?>
            <div id="buttons"></div>
        </div>
        <div class="card-title w-100 justify-content-end flex-wrap">

            <div class="m-1">
                <div class="input-group mb-3">
                    <span class="input-group-text">Desde</span>
                    <input type="date" id="fecha_desde" name="fecha_desde" value="<?= (isset($fecha_desde)) ? $fecha_desde : '' ?>" class="form-control form-control-solid w-auto" placeholder="Fecha desde" required />
                </div>
            </div>

            <div class="m-1">
                <div class="input-group mb-3">
                    <span class="input-group-text">Hasta</span>
                    <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?= (isset($fecha_hasta)) ? $fecha_hasta : '' ?>" class="form-control form-control-solid w-auto" placeholder="Fecha hasta" required />
                </div>
            </div>
            <div class="m-1">
                <select name="filter_estado" id="filter_estado" class="form-select form-select-solid w-auto">
                    <option value="">Cualquier estado</option>
                    <option value="Borrador">Borradorx</option>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Aceptado parcial">Aceptado parcial</option>
                    <option value="Aceptado">Aceptado</option>
                    <option value="Rechazado">Rechazado</option>
                    <option disabled>-----------------------------------</option>
                    <option value="Aceptado pendiente">Aceptado con servicio pendiente</option>
                    <option value="Finalizado">Finalizado</option>
                </select>
            </div>
            <div class="m-1">
                <select name="id_cliente" id="id_cliente" class="form-select form-select-solid w-250px" data-placeholder="Cliente...">
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
                                return '<?php echo RUTA_WWW; ?>/clientes/json/' + params.term.replace(/ /g, "_");
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
        </div>
        <div class="card-title w-100 justify-content-end flex-wrap">
            <div class="m-1">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="incluir_rechazados" name="incluir_rechazados">
                    <label class="form-check-label" for="flexSwitchCheckChecked">Incluir rechazados</label>
                </div>
            </div>
            <div class="m-1">
                <select name="id_usuario" id="id_usuario" class="form-select form-select-solid w-auto">
                    <option value="">Todos los usuarios</option>
                    <?php foreach ($usuarios as $rs) { ?>
                        <option value="<?php echo $rs['id_usuario']; ?>"><?php echo $rs['nombre'] . ' ' . $rs['apellidos']; ?></option>
                    <?php } ?>
                </select>
            </div>

        </div>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">
            <table id="tabla_presupuestos" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th class="col_mod">F. Mod.</th>
                        <th class="col_titulo">Titulo.</th>

                        <th class="col_id">#Num. Pres.</th>
                        <th>Cliente</th>
                        <th class="col_fecha">Creación - Validez</th>
                        <th class="col_presu">Total</th>
                        <th class="col_aceptado">Aceptado</th>
                        <th class="col_aceptado">Pendiente</th>
                        <th class="col_desc">%</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold"></tbody>
            </table>
        </div>
    </div>
    <form name="form_delete_comision_modal" id="delete_comision_modal" action="" method="post">
        <input type="hidden" id="delete_borrado" name="borrado" value="1" />
        <input type="hidden" id="delete_accion" name="accion" value="delete" />
    </form>
</div>



<div class="modal fade" id="modal-presupuesto" aria-labelledby="modal-presupuestoLabel" data-bs-focus="false" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center text-uppercase" id="exampleModalLabel">Detalle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer p-1">
                <button type="button" class="btn btn-sm btn-secondary text-inverse-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-pago" tabindex="-1" aria-labelledby="modal-pagoLabel" aria-hidden="true" data-focus-on="input:first">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form_pagoeuros" action="<?php echo base_url(); ?>Presupuestos/pagoeuros" role="form" method="post" name="form_pagoeuros">
                <div class="modal-header">
                    <h5 class="modal-title" id="presupuestoPagoModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="border p-4 mb-5">
                        <div class="fs-2 text-center fw-bolder">
                            PENDIENTE DE PAGAR:
                            <input name="total_importes_marcados" typ="number" value="0" style="text-align: right; width: 80px; border: 0px; background: #fff; font-weight: bold;" disabled /> €
                            <span class="mx-5">/</span>
                            <span id="faltan" style="color: red;">FALTAN:</span>
                            <input id="faltan_importe" name="falta_importe" typ="number" value="0" style="text-align: right; width: 80px; border: 0px; background: #fff; font-weight: bold; color: red;" disabled />
                            <span id="faltan_simbolo" style="color: red;">€</span>
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="col mb-5">
                            <label for="" class="form-label text-center">Importe<br>Efectivo</label>
                            <input name="pagado_efectivo" class="form-control form-control-solid" type="number" step="0.01" min="0" value="0" style="text-align: right;" onchange="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" onkeyup="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" onkeyDown="capturar_valor(this);" required />
                        </div>
                        <div class="col mb-5">
                            <label for="" class="form-label text-center">Importe<br>Tarjeta</label>
                            <input name="pagado_tarjeta" id="pagado_tarjeta" class="form-control form-control-solid" type="number" step="0.01" min="0" value="0" style="text-align: right;" onchange="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" onkeyup="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" onkeyDown="capturar_valor(this);" required />
                            <p id="nota-efectivo" style="display:none; font-weight:bolder"></p>
                        </div>
                        <div class="col mb-5">
                            <label for="" class="form-label text-center">Importe<br>Transferencia</label>
                            <input name="pagado_transferencia" id="pagado_transferencia" class="form-control form-control-solid" type="number" step="0.01" min="0" value="0" style="text-align: right;" onchange="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" onkeyup="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" onkeyDown="capturar_valor(this);" required />
                            <p id="nota-transferencia" style="display:none; font-weight:bolder"></p>
                        </div>
                        <div class="col mb-5">
                            <label for="" class="form-label text-center">Importe<br>TPV2</label>
                            <input name="pagado_tpv2" id="pagado_tpv2" class="form-control form-control-solid" type="number" step="0.01" min="0" value="0" style="text-align: right;" onchange="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" onkeyup="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" onkeyDown="capturar_valor(this);" required />
                            <p id="nota-tpv2" style="display:none; font-weight:bolder"></p>
                        </div>
                        <div class="col mb-5">
                            <label for="" class="form-label text-center">Importe<br>PayPal</label>
                            <input name="pagado_paypal" id="pagado_paypal" class="form-control form-control-solid" type="number" step="0.01" min="0" value="0" style="text-align: right;" onchange="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" onkeyup="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" onkeyDown="capturar_valor(this);" required />
                        </div>
                        <div class="col mb-5">
                            <label for="" class="form-label text-center">Importe<br>Financiado</label>
                            <input name="pagado_financiado" id="pagado_financiado" class="form-control form-control-solid" type="number" step="0.01" min="0" value="0" style="text-align: right;" onchange="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" onkeyup="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" onkeyDown="capturar_valor(this);" required />
                        </div>
                        <div class="col-12">
                            <p class="text-muted text-center mb-0">Utiliza la coma (,) como separador decimal</p>
                        </div>

                        <input name="pagado_habitacion" type="hidden" value="0" />
                        <input type="hidden" name="id_presupuesto" id="id_presupuesto" value="">
                    </div>

                </div>
                <div class="modal-footer p-2 justify-content-center">
                    <button type="button" class="btn btn-sm btn-secondary text-inverse-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-sm btn-primary text-inverse-primary">Registrar pago</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    var valorpagar = 0;
    var inputpagar = '';
    $('#new_tipo').on('change', function() {
        var tipocomision = document.getElementById('new_tipo').value
        if (tipocomision == 'tramo') {
            $('#comisionestramo_nuevo').slideDown('slow');
        } else {
            $('#comisionestramo_nuevo').slideUp('slow');
        }
    })

    $('#edit_tipo').on('change', function() {
        var tipocomision = document.getElementById('edit_tipo').value
        if (tipocomision == 'tramo') {
            $('#comisionestramo_editar').slideDown('slow');
        } else {
            $('#comisionestramo_editar').slideUp('slow');
        }
    })

    var tabla_presupuestos = $("#tabla_presupuestos").DataTable({
        info: true,
        paging: true,
        ordering: true,
        searching: true,
        stateSave: false,
        processing: true,
        serverSide: true,
        scrollX: true,
        autoWidth: false,
        order: [
            [0, "desc"],
            [1, "desc"]
        ],
        pageLength: 50,
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Todos"],
        ],
        columns: [{
                //0
                titlee: "",
                name: "fecha_modificacion",
                data: "fecha_modificacion",
                className: "text-nowrap",
                render: function(data, type, row) {
                    var fechaCompleta = row.f_modificacion;
                    var fecha = fechaCompleta.split(' ')[0]; // Obtiene solo la parte de la fecha
                    return fecha;
             }
            },
            {
                //0
                titlee: "",
                name: "titulo",
                data: "titulo_presupuesto",
                className: "text-nowrap",
                render: function(data, type, row) {
                    return row.titulo_presupuesto;
                }
            },
            {
                //1
                titlee: "",
                name: "nro_presupuesto",
                data: "nro_presupuesto",
                className: "text-nowrap",
                render: function(data, type, row) {
                    var html = row.nro_presupuesto;
                    if (row.estado == 'Pendiente') {
                        html += ' <span class="badge badge-warning ms-2" data-bs-toggle="tooltip" title="' + row.estado + '"><i class="fa fa-clock text-white"></i></span>';
                    } else if ((row.estado == 'Aceptado' || row.estado == 'Aceptado parcial')&&row.items_sinfinalizar > 0) {
                        html += ' <span class="badge badge-success ms-2" data-bs-toggle="tooltip" title="' + row.estado + '"><i class="fa fa-check text-white"></i></span>';
                    }  else if ((row.estado == 'Aceptado' || row.estado == 'Aceptado parcial')&&row.items_sinfinalizar == 0) {
                         /*html += ' <span class="badge badge-primary ms-2" data-bs-toggle="tooltip" title="Finalizado"><i class="fa fa-check text-white"></i></span>'; */
                    } else if (row.estado == 'Rechazado') {
                        html += ' <span class="badge badge-danger ms-2" data-bs-toggle="tooltip" title="' + row.estado + '"><i class="fa fa-times text-white"></i></span>';
                    } else {
                        html += ' <span class="badge badge-secondary ms-2" data-bs-toggle="tooltip" title="' + row.estado + '"><i class="fa fa-clock text-dark"></i></span>';
                    }

                    return html;
                }
            },
            {
                //2
                titlee: "",
                name: "cliente",
                data: "cliente",
                render: function(data, type, row) {
                    var html = '<span class="text-nowrap d-block"><i class="fa fa-user text-dark-emphasis"></i> ' + row.cliente + '</span>';

                    html += '<span class="text-muted ms-2 small"><i class="fa fa-user-md"></i> ' + row.empleado + '</span>';
                    return html
                }
            },
            {
                // 3
                titlee: "",
                name: "fecha_creacion",
                data: "fecha_creacion",
                className: "text-nowrap",
                render: function(data, type, row) {
                    var html = `<span class="text-nowrap d-block" data-bs-toggle="tooltip" title="Creación"><span class="badge badge-success ms-2"><i class="fa fa-calendar-plus text-white"></i></span> ${row.f_creacion}</span><span class="text-muted ms-2 small" data-bs-toggle="tooltip" title="Validez"><i class="fa fa-calendar-times text-danger" ></i> ${row.f_validez}</span>`;
                    return html;
                }
            },
            {
                // 4
                name: "totalpresupuesto",
                data: "totalpresupuesto",
                render: function(data, type, row) {
                    var html = row.totalpresupuesto;
                    return html
                }
            },
            {
                // 5
                name: "total_aceptado",
                data: "total_aceptado",
                render: function(data, type, row) {
                    var html = '';
                    if (row.total_aceptado > 0) {
                        html = row.total_aceptado;
                    }
                    return html
                }
            },
            {
                //6
                name: "pendeinte",
                data: "pendeinte",
                render: function(data, type, row) {
                    var html=''; /*row.pendiente;*/
                    if(row.total_pendiente_calculado != null && row.total_pendiente_calculado > 0 ){
                        html+=' ('+row.total_pendiente_calculado+') ';
                    }
                    return html;
                }
            },
            {
                //7
                name: "descuento",
                data: "descuento",
                render: function(data, type, row) {
                    var html = '';
                    if ((row.total_sin_descuento - row.totalpresupuesto) > 0) {
                        desc_monto = (row.total_sin_descuento - row.totalpresupuesto)
                        descuento = desc_monto / row.total_sin_descuento * 100;
                        html = descuento.toFixed(2) + ' %';
                    }
                    return html
                }
            },
            /*
            {
                //4
                titlee: "Empleado",
                name: "empleado_nombre",
                data: "empleado_nombre",
				render: function(data, type, row) {
                    var html = row.e_nombre +' '+row.e_apellidos;
                    return html
                }
            },
	         */
            {
                // 8
                titlee: "",
                name: "",
                data: "",
                render: function(data, type, row) {
                    var html = '<div class="btn-group">';
                    html += `<button type="button" class="btn btn-sm btn-icon btn-info" data-ver data-bs-toggle="tooltip" title="Detalle del presupuesto"><i class="fa fa-eye"></i></button>`;
                    /*
	                if( row.estado == 'Pendiente' && '0'=='<?php echo $this->session->userdata('id_perfil'); ?>' ){
		                html += `<button type="button" class="btn btn-sm btn-icon btn-warning" data-edit data-bs-toggle="tooltip" title="Editar presupuesto"><i class="fa-regular fa-pen-to-square"></i></button>`;
	                }
	                */
                    if (row.estado == 'Borrador') {
                        html += `<button type="button" class="btn btn-sm btn-icon btn-warning" data-edit data-bs-toggle="tooltip" title="Editar presupuesto"><i class="fa-regular fa-pen-to-square"></i></button>`;
                    } else {

                        html += `<button type="button" class="btn btn-sm btn-icon btn-warning" data-duplicar data-bs-toggle="tooltip" title="Duplicar presupuesto"><i class="fas fa-clone"></i></button>`;

                        /*html += `<button type="button" class="btn btn-sm btn-icon btn-warning" data-clonar data-bs-toggle="tooltip" title="Duplicar presupuesto"><i class="fas fa-clone"></i></button>`;*/

                        if (row.estado == 'Pendiente' && row.id_centro == <?=$this->session->userdata('id_centro_usuario')?>) {
                            html += `<button type="button" class="btn btn-sm btn-icon btn-primary" data-estado data-bs-toggle="tooltip" title="Gestionar estado"><i class="fas fa-exclamation-triangle"></i></button>`;
                        }
                        
                        <?php /*if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 3) { ?>
                            if (row.estado == 'Pendiente') {
                                html += `<button type="button" class="btn btn-sm btn-icon btn-primary" data-estado data-bs-toggle="tooltip" title="Gestionar estado"><i class="fas fa-exclamation-triangle"></i></button>`;
                            }
                        <?php } ?>
                        <?php if ($this->session->userdata('id_perfil') == 2) { ?>
                            if (row.estado == 'Pendiente' && row.totalpresupuesto <= 600) {
                                html += `<button type="button" class="btn btn-sm btn-icon btn-primary" data-estado data-bs-toggle="tooltip" title="Gestionar estado"><i class="fas fa-exclamation-triangle"></i></button>`;
                            }
                        <?php } */?>
                        html += `<button type="button" class="btn btn-sm btn-icon btn-info" data-pdf data-bs-toggle="tooltip" title="Ver presupuesto"><i class="fas fa-file-pdf"></i></button>`;
                    }
                    html += `</div>`;
                    return html
                }
            },
            {
                //9
                titlee: "",
                name: "",
                data: "",
                render: function(data, type, row) {
                    var html = '<div class="btn-group">';
                    <?php  if ($this->session->userdata('id_perfil') == 0) { ?>
                        if ((row.estado == 'Aceptado' || row.estado == 'Aceptado parcial') && row.es_repeticion != 1) {
                            html += `<button type="button" class="btn btn-sm btn-icon btn-outline btn-outline-primary" data-pago data-bs-toggle="tooltip" title="Registrar pago en el presupuesto"><i class="fas fa-euro"></i></button>`;
                        }
                    
                        html += `<button type="button" class="btn btn-sm btn-icon btn-secondary" data-edit-m data-bs-toggle="tooltip" title="Edición MASTER"><i class="fa-asterisk fa-regular"></i></button>`;
                        html += `<button type="button" class="btn btn-sm btn-icon btn-danger" data-del data-bs-toggle="tooltip" title="Eliminar presupuesto"><i class="fa-solid fa-trash"></i></button>`;
                    <?php } ?>

                    html += `</div>`;

                    return html
                }
            },
        ],
        columnDefs: [{
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10],
                visible: true,
            },
            {
                targets: ["_all"],
                visible: false,
            },
            {
                targets: [-2],
                orderable: false,
            },
            {
                targets: ['col_id', 'col_validez'],
                className: 'text-center'
            },
            {
                targets: ['col_aceptado', 'col_desc', 'col_presu_sin_desc', 'col_presu'],
                className: 'text-end'
            }
        ],
        ajax: {
            url: "<?php echo base_url(); ?>Presupuestos/get_presupuestos",
            type: "GET",
            datatype: "json",
            data: function(data) {
                var id_cliente = $('[name="id_cliente"]').val();
                var fecha_desde = $('[name="fecha_desde"]').val();
                var fecha_hasta = $('[name="fecha_hasta"]').val();
                var fecha_validez = $('[name="fecha_validez"]').val();
                var id_usuario = $('[name="id_usuario"]').val();
                var estado = $('[name="filter_estado"]').val();
                // var revisado = $('[name="filter_revisado"]').val();

                var rechazados=jQuery('[name="incluir_rechazados"]').is(':checked');
                if(rechazados) data.rechazados = 1;
                else data.rechazados = 0;


                if (id_cliente != "") {
                    data.id_cliente = id_cliente;
                }
                if (fecha_desde != "") {
                    data.fecha_desde = fecha_desde;
                }
                if (fecha_hasta != "") {
                    data.fecha_hasta = fecha_hasta;
                }
                if (fecha_validez != "") {
                    data.fecha_validez = fecha_validez;
                }
                if (id_usuario != "") {
                    data.id_usuario = id_usuario;
                }
                if (estado != "") {
                    data.estado = estado;
                }
                /*if (revisado != "") {
                    data.revisado = revisado;
                }*/
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
        drawCallback: function(settings) {},
        initComplete: function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    'trigger': 'hover'
                })
            })
        },
    });

    var buttons = new $.fn.dataTable.Buttons(tabla_presupuestos, {
        buttons: [{
            text: "Excel",
            extend: "excelHtml5",
            title: 'Presupuestos',
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
            title: 'Presupuestos',
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
        tabla_presupuestos.search($(this).val()).draw();
    });
    $('#filter_estado').on('change', function() {
        tabla_presupuestos.draw();
    });

    $('#incluir_rechazados').on('change',function(){
        tabla_presupuestos.draw();
    });

    /*$('#filter_revisado').on('change', function() {
        tabla_presupuestos.draw();
    });*/
    $('#id_usuario').on('change', function() {
        tabla_presupuestos.draw();
    });
    $('#id_cliente').on('change', function() {
        tabla_presupuestos.draw();
    });
    $('#fecha_desde').on('change', function() {
        tabla_presupuestos.draw();
    });
    $('#fecha_hasta').on('change', function() {
        tabla_presupuestos.draw();
    });

    $(document).on('click', '[data-pdf]', function(event) {
        var button = $(this);
        var data = tabla_presupuestos.row(button.parents("tr")).data();
        var url = '<?= base_url() ?>presupuestos/ver_pdf/' + data.id_presupuesto;
        //window.location.href = url;  
        var posicion_x;
        var posicion_y;
        var ancho = 800;
        var alto = 550;
        posicion_x = (screen.width / 2) - (ancho / 2);
        posicion_y = (screen.height / 2) - (alto / 2);
        window.open(url, "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
    })

    $(document).on('click', '[data-edit]', function(event) {
        var button = $(this);
        var data = tabla_presupuestos.row(button.parents("tr")).data();
        var url = '<?= base_url() ?>presupuestos/editar_presupuesto/' + data.id_presupuesto;
        window.location.href = url;
    });

    $(document).on('click', '[data-edit-m]', function(event) {
        var button = $(this);
        var data = tabla_presupuestos.row(button.parents("tr")).data();
        var url = '<?= base_url() ?>presupuestos/master_presupuesto/' + data.id_presupuesto;
        //window.location.href = url;
        window.open(url, '_blank');
    });

    $(document).on('click', '[data-clonar]', function(event) {
        var button = $(this);
        var data = tabla_presupuestos.row(button.parents("tr")).data();
        var url = '<?= base_url() ?>presupuestos/duplicar_presupuesto/' + data.id_presupuesto;
        window.location.href = url;
    });

    $(document).on('click', '[data-duplicar]', function(event) {
        Swal.fire({
            html: '¿DESEA DUPLICAR EL PRESUPUESTO? <br> Se generará un nuevo presupuesto con los mismos datos y podrá editarlo',
            showCancelButton: true,
            confirmButtonText: 'Si, duplicar',
            cancelButtonText: 'No, cancelar',
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            onBeforeOpen: () => {},

        }).then((result) => {
            if (result.value) {
                var button = $(this);
                var data = tabla_presupuestos.row(button.parents("tr")).data();
                if (data.estado == 'Aceptado' || data.estado == 'Aceptado parcial') {
                    Swal.fire({
                        html: '¿QUIERE MARCAR COMO RECHAZADO EL PRESUPUESTO QUE ESTÁ DUPLICANDO?',
                        showCancelButton: true,
                        confirmButtonText: 'Si, rechazar el presupuesto',
                        cancelButtonText: 'No, mantener en el estado actual',
                        showLoaderOnConfirm: true,
                        allowOutsideClick: false,
                        onBeforeOpen: () => {},

                    }).then((result2) => {
                        if (result2.value) {
                            var url = '<?= base_url() ?>presupuestos/duplicar_rechazar_presupuesto_nuevo/' + data.id_presupuesto;
                            window.location.href = url;
                        }else{
                            var url = '<?= base_url() ?>presupuestos/duplicar_presupuesto_nuevo/' + data.id_presupuesto;
                            window.location.href = url;
                        }
                    })
                }else{
                    var url = '<?= base_url() ?>presupuestos/duplicar_presupuesto_nuevo/' + data.id_presupuesto;
                    window.location.href = url;
                }
            }
        })
    });

    $(document).on('click', '[data-ver]', function(event) {
        var button = $(this);
        $(this).blur();
        var data = tabla_presupuestos.row(button.parents("tr")).data();
        var url = '<?= base_url() ?>presupuestos/ver_detalle/' + data.id_presupuesto;

        $('#modal-presupuesto .modal-title').html('Detalle del presupuesto #'+data.nro_presupuesto);
        $('#modal-presupuesto .modal-body').html('<i class="fas fa-sync fa-spin"></i>');
        $('#modal-presupuesto').modal('show');

        $.get(url, function(data) {
            $('#modal-presupuesto .modal-body').html(data);
        });
    });

    $(document).on('click', '[data-estado]', function(event) {
        var button = $(this);
        var data = tabla_presupuestos.row(button.parents("tr")).data();
        var url = '<?= base_url() ?>Presupuestos/gestionar_estado/' + data.id_presupuesto;
        window.location.href = url;
    })

    $(document).on('click', '[data-del]', function() {
        Swal.fire({
            html: '¿DESEA MARCAR EL PRESUPUESTO COMO BORRADO?',
            showCancelButton: true,
            confirmButtonText: 'Si, borrar',
            showLoaderOnConfirm: true,
            onBeforeOpen: () => {},

        }).then((result) => {
            if (result.value) {
                var button = $(this);
                var data = tabla_presupuestos.row(button.parents("tr")).data();
                var url = '<?= base_url() ?>presupuestos/borrar_presupuesto/' + data.id_presupuesto;
                window.location.href = url;
            }
        })
    });

    $(document).on('click', '[data-pago]', function(event) {
        var button = $(this);
        var data = tabla_presupuestos.row(button.parents("tr")).data();
        console.log(data);
        $('#presupuestoPagoModalLabel').html('PRESUPUESTO Nº: ' + data.nro_presupuesto);
        $('[name="total_importes_marcados"]').val(data.pendiente)
        $('#faltan_importe').val(data.pendiente);
        $('#id_presupuesto').val(data.id_presupuesto);
        $('#modal-pago').modal('show');
    })

    $.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons flex-wrap';
    var oldExportAction = function(self, e, dt, button, config) {
        if (button[0].className.indexOf('buttons-excel') >= 0) {
            if ($.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)) {
                $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);
            } else {
                $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
            }
        } else if (button[0].className.indexOf('buttons-print') >= 0) {
            $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
        }
    };
    var newExportAction = function(e, dt, button, config) {
        var self = this;
        var oldStart = dt.settings()[0]._iDisplayStart;
        dt.one('preXhr', function(e, s, data) {
            data.start = 0;
            data.length = 2147483647;
            dt.one('preDraw', function(e, settings) {
                oldExportAction(self, e, dt, button, config);
                dt.one('preXhr', function(e, s, data) {
                    settings._iDisplayStart = oldStart;
                    data.start = oldStart;
                });
                setTimeout(dt.ajax.reload, 0);
                return false;
            });
        });
        dt.ajax.reload();
    };

    function ImporteMarcado(total) {
        efectivo = parseFloat(document.form_pagoeuros.pagado_efectivo.value);
        tarjeta = parseFloat(document.form_pagoeuros.pagado_tarjeta.value);
        habitacion = parseFloat(document.form_pagoeuros.pagado_habitacion.value);
        transferencia = parseFloat(document.form_pagoeuros.pagado_transferencia.value);
        tpv2 = parseFloat(document.form_pagoeuros.pagado_tpv2.value);
        paypal = parseFloat(document.form_pagoeuros.pagado_paypal.value);
        financiado = parseFloat(document.form_pagoeuros.pagado_financiado.value);
        r = (total) - parseFloat(efectivo + tarjeta + habitacion + transferencia + tpv2 + paypal + financiado).toFixed(2);
        if (r >= 0) {
            document.form_pagoeuros.falta_importe.value = parseFloat(r).toFixed(2);
            if (r == 0) {
                document.getElementById("faltan").style.color = "green";
                document.getElementById("faltan_simbolo").style.color = "green";
                document.getElementById("faltan_simbolo").style.visibility = "hidden";
                document.getElementById("faltan_importe").style.visibility = "hidden";
                document.getElementById("faltan").innerHTML = "COMPLETO";
            }
            if (r > 0) {
                document.getElementById("faltan").style.color = "red";
                document.getElementById("faltan_simbolo").style.color = "red";
                document.getElementById("faltan_simbolo").style.visibility = "visible";
                document.getElementById("faltan_importe").style.visibility = "visible";
                document.getElementById("faltan").innerHTML = "FALTAN";
            }
            if (r < 0) {
                document.getElementById("faltan").style.color = "red";
                document.getElementById("faltan_simbolo").style.color = "red";
                document.getElementById("faltan_simbolo").style.visibility = "visible";
                document.getElementById("faltan_importe").style.visibility = "visible";
                document.getElementById("faltan").innerHTML = "SOBRAN";
            }
        } else {
            $('[name="' + inputpagar + '"]').val(valorpagar)
            Swal.fire('No puedes realizar un pago que supere el saldo pendeinte de pagar.')
        }
    }

    function capturar_valor(input) {
        valorpagar = $(input).val();
        inputpagar = $(input).attr('name')
    }
</script>