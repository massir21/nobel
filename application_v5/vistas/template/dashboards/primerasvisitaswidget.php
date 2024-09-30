<?php
if(!isset( $primeras_visitas_mes))  $primeras_visitas_mes=[];

/*
 ALTER TABLE `presupuestos` ADD `pago_estado_manual` ENUM('Sin Definir','Pendiente','Recibido','') NOT NULL AFTER `titulo_presupuesto`;
 */
?>
<script type="text/javascript" src="<?= base_url() ?>assets_v5/plugins/custom/tinymce/tinymce.bundle.js"></script>

<div class="card card-flush h-xl-100">
    <!--begin::Card header-->
    <div class="card-header pt-7">
        <!--begin::Title-->
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-800">Primeras visitas</span>
            <span class="text-gray-500 mt-1 fw-semibold fs-6">Media de <?= 0 ?> primeras visitas por dia</span>
        </h3>
        <!--end::Title-->

        <!--begin::Actions-->
        <div class="card-toolbar">
            <!--begin::Filters-->
            <div class="d-flex flex-stack flex-wrap gap-4">
                <!--begin::Year-->
                <div class="d-flex align-items-center fw-bold">
                    <!--begin::Label-->
                    <div class="text-gray-500 fs-7 me-2">Centro</div>
                    <!--end::Label-->

                    <!--begin::Select-->
                    <select name="id_centro" id="id_centro"
                            class="form-select form-select-transparent text-gray-900 fs-7 lh-1 fw-bold py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Select an option" data-kt-table-widget-5="id_centro">
                        <?php
                        if($this->session->userdata('id_perfil') == 0){
                            ?>
                            <option value="0" selected="selected">Todos</option>
                        <?php
                        }
                        ?>
                        <?php if (isset($centros_todos)) { if ($centros_todos != 0) { foreach ($centros_todos as $key => $row) { if ($row['id_centro'] > 1) { ?>
                            <option value='<?php echo $row['id_centro']; ?>'
                                <?php
                                    if($this->session->userdata('id_perfil') != 0 && $this->session->userdata('id_centro')==$row['id_centro']) { echo "selected"; } } ?>>
                                <?php echo $row['nombre_centro']; ?>
                            </option>
                        <?php }}} ?>
                    </select>
                    <!--end::Select-->
                </div>
                <!--end::Year-->


                <?php
                if(true){
                ?>
                <!--begin::Destination-->
                <div class="d-flex align-items-center fw-bold">
                    <!--begin::Label-->
                    <div class="text-gray-500 fs-7 me-2">Control</div>
                    <!--end::Label-->

                    <!--begin::Select-->
                    <select class="form-select form-select-transparent text-gray-900 fs-7 lh-1 fw-bold py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px"
                            name="filter_archivado" id="filter_archivado">
                        <?php
                        if($this->session->userdata('id_perfil') == 0){
                        ?><option value="" >Todos</option>
                        <?php
                        }
                        ?>
                        <option value="0" <?php echo $this->session->userdata('id_perfil') == 0 ? ' selected="selected>" ' : '';?>>No Archivado</option>
                        <?php
                        if($this->session->userdata('id_perfil') == 0){
                        ?><option value="1">Archivado</option>
                            <?php
                        }
                        ?>
                    </select>
                    <!--end::Select-->
                </div>
                <!--end::Destination-->
                <?php
                }
                ?>
                <!--begin::Status-->
                <div class="d-flex align-items-center fw-bold">
                    <!--begin::Label-->
                    <div class="text-gray-500 fs-7 me-2">Mes</div>
                    <!--end::Label-->

                    <!--begin::Select-->
                    <select class="form-select form-select-transparent text-gray-900 fs-7 lh-1 fw-bold py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px"
                            name="filter_month" id="filter_month"
                        >
                        <option value="01" <?php
                        echo date("m")=="01" ? " selected='selected' " : "";
                        ?>>Enero</option>
                        <option value="02" <?php
                        echo date("m")=="02" ? " selected='selected' " : "";
                        ?>>Febrero</option>
                        <option value="03" <?php
                        echo date("m")=="03" ? " selected='selected' " : "";
                        ?>>Marzo</option>
                        <option value="04" <?php
                        echo date("m")=="04" ? " selected='selected' " : "";
                        ?>>Abril</option>
                        <option value="05" <?php
                        echo date("m")=="05" ? " selected='selected' " : "";
                        ?>>Mayo</option>
                        <option value="06" <?php
                        echo date("m")=="06" ? " selected='selected' " : "";
                        ?>>Junio</option>
                        <option value="07" <?php
                        echo date("m")=="07" ? " selected='selected' " : "";
                        ?>>Julio</option>
                        <option value="08" <?php
                        echo date("m")=="08" ? " selected='selected' " : "";
                        ?>>Agosto</option>
                        <option value="09"<?php
                        echo date("m")=="09" ? " selected='selected' " : "";
                        ?>>Septiembre</option>
                        <option value="10"<?php
                        echo date("m")=="10" ? " selected='selected' " : "";
                        ?>>Octubre</option>
                        <option value="11"<?php
                        echo date("m")=="11" ? " selected='selected' " : "";
                        ?>>Noviembre</option>
                        <option value="12"<?php
                        echo date("m")=="12" ? " selected='selected' " : "";
                        ?>>Diciembre</option>
                    </select>
                    <!--end::Select-->
                </div>
                <!--end::Status-->

                <!--begin::Year-->
                <div class="d-flex align-items-center fw-bold">
                    <!--begin::Label-->
                    <div class="text-gray-500 fs-7 me-2">Año</div>
                    <!--end::Label-->

                    <!--begin::Select-->
                    <select name="filter_year" id="filter_year"
                            class="form-select form-select-transparent text-gray-900 fs-7 lh-1 fw-bold py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Select an option" data-kt-table-widget-5="filter_year">
                        <?php
                        for($yy=date("Y");$yy>=2017;$yy--){
                            ?>
                            <option value="<?php echo $yy;?>"><?php echo $yy;?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <!--end::Select-->
                </div>
                <!--end::Year-->

                <!--begin::Search-->
                <div class="position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-2 position-absolute top-50 translate-middle-y ms-4">
                        <span class="path1"></span><span class="path2"></span></i>
                    <input type="text" kt_table_primerasvisitas_tablee="search" class="form-control w-150px fs-7 ps-12" placeholder="Search">
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Filters-->
        </div>
        <!--end::Actions-->
    </div>
    <!--end::Card header-->

    <!--begin::Card body-->
    <div class="card-body pt-2">
        <!--begin::Table-->
        <div id="kt_table_widget_4_table_wrapper" class="dt-container dt-bootstrap5 dt-empty-footer">
            <div id="" class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-3 dataTable" id="kt_table_primerasvisitas_tablee" style="width: 712.925px;">
                    <colgroup>
                        <col data-dt-column="0" style="width: 283.312px;">
                        <col data-dt-column="1" style="width: 101.65px;">
                        <col data-dt-column="2" style="width: 131.65px;">
                        <col data-dt-column="3" style="width: 70.65px;">
                        <col data-dt-column="4" style="width: 101.65px;">
                        <col data-dt-column="5" style="width: 105.888px;">
                        <col data-dt-column="6" style="width: 37.125px;">
                    </colgroup>
                    <!--begin::Table head-->
                    <thead>
                    <!--begin::Table row-->
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0" role="row">
                        <th class="min-w-100px dt-orderable-none" data-dt-column="0" rowspan="1" colspan="1">
                            <span class="dt-column-title">PACIENTE</span><span class="dt-column-order"></span>
                        </th>
                        <th class="text-center min-w-100px dt-orderable-none" data-dt-column="1" rowspan="1" colspan="1">
                            <span class="dt-column-title">FECHA</span><span class="dt-column-order"></span>
                        </th>
                        <th class="text-end min-w-100px dt-orderable-none" data-dt-column="2" rowspan="1" colspan="1">
                            <span class="dt-column-title">DOCTOR</span>
                            <span class="dt-column-order"></span>
                        </th>
                        <th class="text-center min-w-100px dt-orderable-none dt-type-numeric" data-dt-column="3" rowspan="1" colspan="1">
                            <span class="dt-column-title">CITA</span>
                            <span class="dt-column-order"></span>
                        </th>
                        <th class="text-end min-w-100px dt-orderable-none dt-type-numeric" data-dt-column="4" rowspan="1" colspan="1">
                            <span class="dt-column-title">SALDO</span>
                            <span class="dt-column-order"></span>
                        </th>
                        <th class="text-end min-w-50px dt-orderable-none" data-dt-column="5" rowspan="1" colspan="1">
                            <span class="dt-column-title">Status</span>
                            <span class="dt-column-order"></span>
                        </th>
                        <th class="text-end min-w-50px dt-orderable-none" data-dt-column="5" rowspan="1" colspan="1">
                        </th>
                    </tr>
                    <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->

                    <!--begin::Table body-->
                    <tbody class="fw-bold text-gray-600">
                    </tbody>
                    <!--end::Table body-->
                    <tfoot></tfoot>
                </table>
            </div>
            <div id="" class="row"><div id="" class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start dt-toolbar"></div><div id="" class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end"></div></div></div>
        <!--end::Table-->
    </div>
    <!--end::Card body-->
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




<style type="text/css">
    .hideimportant{
        display: none!important;
    }
</style>


<div style="display:none">
    <table>
    <tr data-kt-table-widget-4="subtable_template" id="subtable_template" class="d-none">
        <td colspan="2">
            <div class="align-items-center gap-3 ">
                <a class="linkpresu text-gray-600" href="" ><strong>xxxx</strong></a></span>
                <span class=" ms-2 small presufecha"><br/>0000</span>
            </div>
        </td>
        <td class="text-end">
            <div class="text-gray-800 fs-7">Total</div>
            <div class="text-muted fs-7 fw-bold template_total"></div>
        </td>
        <td class="text-end">
            <div class="text-gray-800 fs-7">Dto</div>
            <div class="text-muted fs-7 fw-bold template_dto"></div>
        </td>
        <td class="text-end">
            <div class="text-gray-800 fs-7">Próxima cita</div>
            <div class="text-muted fs-7 fw-bold template_proximacita" ></div>
        </td>

        <td class="text-end">
            <div class="text-gray-800 fs-7 me-3">Pago</div>
            <div class="text-muted fs-7 fw-bold template_pago" ></div>
        </td>
        <td></td>
    </tr>


        <tr data-kt-table-widget-4="subtable_template" id="subtable_template_anteriores" class="d-none">
            <td colspan="2">
                <div class="align-items-center gap-3 ">
                    <a class="linkpresu text-gray-600" href="" ><strong>xxxx</strong></a></span>
                    <span class=" ms-2 small presufecha"><br/>0000</span>
                </div>
            </td>
            <td class="text-end">
                <div class="text-gray-800 fs-7">Total</div>
                <div class="text-muted fs-7 fw-bold template_total"></div>
            </td>
            <td class="text-end">
                <div class="text-gray-800 fs-7">Dto</div>
                <div class="text-muted fs-7 fw-bold template_dto"></div>
            </td>
            <td></td>

            <td class="text-end">
                <div class="text-gray-800 fs-7 me-3">Estado</div>
                <div class="text-muted fs-7 fw-bold template_estado" ></div>
            </td>

        </tr>
    </table>
</div>


<script type="text/javascript">

    var template;
    var datasubtemplate=[];


    jQuery(document).ready(function(){

        var subtable = jQuery('#subtable_template');

        template = subtable.clone();
        template.removeClass('d-none');

        var tablePrimerasVisitas = $('#kt_table_primerasvisitas_tablee').on('preXhr.dt', function(){
            datasubtemplate=[];
        }).DataTable({
            info: true,
            paging: true,
            ordering: false,
            searching: true,
            stateSave: false,
            processing: true,
            serverSide: true,
            scrollX: true,
            autoWidth: false,
            order: [
                [2, "asc"]
            ],
            pageLength: 20,
            lengthMenu: [
                [10, 20,30],
                [10, 20,30],
            ],
            columns: [  <?php /* {
                  //0
                    titlee: "",
                    name: "id_cita",
                    data: "id_cita",
                    className: "text-nowrap",
                    render: function(data, type, row) {
                                return row.id_cita;
                            }
                    },
                    */ ?>
                {
                    //0
                    titlee: "",
                    name: "paciente",
                    data: "nombre_cliente",
                    className: "text-nowrap",
                    render: function(data, type, row) {
                        var html = ''
                            + '<a href="<?php echo base_url();?>/clientes/historial/ver/'+row.id_cliente+'">'
                            + row.nombre_cliente+' '+ row.apellidos_cliente + '</a>';

                        html += '<br/>' + row.telefono_cliente + '';
                        return html
                    }
                },
                {
                    //1
                    titlee: "",
                    name: "fecha_hora_inicio",
                    data: "fecha_hora_inicio",
                    className: "text-nowrap",
                    render: function(data, type, row) {
                        return ' <span>' + row.fecha_inicio + '</span>' +
                            '<br/><span class=" ms-2 small">' + row.hora_inicio + '</span>';
                    }
                },

                {
                    // 2
                    name: "doctor",
                    className: "text-end",
                    data: "nombre_doctor",
                    render: function(data, type, row) {
                        var nombre='';
                        var apellidos='';
                        var icon=''
                        var title='';
                        if(row.nombre_doctor_dietario!='' && row.nombre_doctor_dietario!=null){
                            nombre=row.nombre_doctor_dietario;
                            apellidos=row.apellidos_doctor_dietario;
                            icon = 'fa-coins';
                            title = 'El doctor viene del dietario';
                        }
                        else{
                            nombre=row.nombre_doctor_cita;
                            apellidos=row.apellidos_doctor_cita;
                            icon = 'fa-calendar'
                            title="El doctor viene de la cita";
                        }
                        var html = '<span class="text-nowrap d-block">'+
                            '<i class="fa '+icon+' text-dark-emphasis" title="'+title+'"></i> '
                            + nombre+' '+ apellidos + '</span>';

                        return html
                    }
                },
                {
                    //3
                    titlee: "",
                    name: "estado",
                    data: "estado",
                    className: "text-center text-nowrap",
                    render: function(data, type, row) {
                        var html="";
                        if(row.estado=='Finalizada' || row.estado=='Finalizado'){
                            html='<span class="badge badge-light-success fs-8 fw-bold">Realizada</span>';
                        }
                        else
                        if(row.estado=='Programada'){
                            html='<span class="badge badge-light-info fs-8 fw-bold">Programada</span>';
                        }
                        else
                        if(row.estado=='No vino'){
                            html='<span class="badge badge-light-danger fs-8 fw-bold">No vino</span>';
                        }
                        else{
                            html='<span class="badge badge-light fs-8 fw-bold">'+row.estado+'</span>';
                        }
                        return html;
                    }
                },
                {
                    //4
                    titlee: "",
                    name: "saldo",
                    data: "saldo",
                    className: "text-nowrap text-center",
                    render: function(data, type, row) {
                        return row.saldo+ '€';
                    }
                },
                {
                    //5
                    titlee: "",
                    name: "estado",
                    data: "estado",
                    className: "text-nowrap text-center",
                    render: function(data, type, row) {
                        var html='<span class="badge badge-light-primary fs-8 fw-bold">Pendiente</span>';
                        if(row.estado=='Anulada' || row.estado=='No vino'){
                            if(row.fecha_cita_posterior){
                                html = '<span class="badge badge-light-primary fs-8 fw-bold">Vuelto a Citar</span>';
                            }
                            else {
                                html = '<span class="badge badge-light-warning fs-8 fw-bold">Citar</span>';
                            }
                        }
                        else
                        if(row.estado=='Finalizada' || row.estado=='Finalizado'){
                             if(row.count_presupuestos>0){
                                    if(row.fecha_cita_posterior){
                                        if(row.presupuestos_pagos>0){
                                            html='<span class="badge badge-light-success fs-8 fw-bold">Conseguido</span>';
                                        }
                                        else{
                                            html='<span class="badge badge-light-warning fs-8 fw-bold">Pendiente pago</span>';
                                        }
                                    }
                                    else{
                                        html='<span class="badge badge-light-warning fs-8 fw-bold">Citar</span>';
                                    }
                             }
                             else{
                                 html='<span class="badge badge-light-warning fs-8 fw-bold">Presupuestar</span>';
                             }
                        }
                        return html;
                    }
                },
                {
                    //6
                    titlee: "",
                    name: "",
                    data: "",
                    className: "text-nowrap text-center",
                    render: function(data, type, row) {
                        var html= '<button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary toggle h-25px w-25px" data-kt-table-widget-4="expand_row">'+
                                    '<i class="ki-duotone ki-plus fs-4 m-0 toggle-off" ></i>'+
                                    '<i class="ki-duotone ki-minus fs-4 m-0 toggle-on" ></i> '+
                                '</button>';
						var styleobs='';
						if(row.count_observaciones>0){
							styleobs='color:red';
						}
                        var titleobserv='Sin observaciones';
                        if(row.count_observaciones){
                            titleobserv=row.ultima_observacion;
                            if(row.count_observaciones>1){
                                titleobserv=titleobserv+" \r\n(+"+(row.count_observaciones-1)+" obs.)";
                            }
                        }
                        html += '<a href="<?php echo base_url();?>citasextra/observaciones/'+row.id_cita+'" type="button" class="btncomments btncomments-'+row.id_cita+' btn btn-sm btn-icon btn-light btn-active-light-primary toggle h-25px w-25px" ' +
                            ' title="'+titleobserv+'">'+
                            '<i class="ki-outline ki-messages fs-4 m-0 toggle-off" style="'+styleobs+'"></i>'+
                            '<i class="ki-outline ki-messages fs-4 m-0 toggle-on" style="'+styleobs+'"></i> '+
                            '</a>';
                        var arctive="";
                        var valarctive="1";
                        var titlearctive="Archivar";
                        if(row.archivado_parrilla=="1") {
                            arctive=" active btndesarchives";
                            valarctive="0";
                            titlearctive="Desarchivar";
                        }
                        <?php
                        if($this->session->userdata('id_perfil')==0){
                        ?>


                        html += '<a type="button" class="btnarchives btnarchive-'+row.id_cita+' '+arctive+
                                        ' btn btn-sm btn-icon btn-light btn-active-light-primary toggle h-25px w-25px" '+
                                        ' href="<?php echo base_url();?>citasextra/archivarparrilla/'+row.id_cita+'/'+valarctive+
                                        '" title="'+titlearctive+'">'+
                            '<i class="ki-solid ki-archive fs-4 m-0 toggle-off"></i>'+
                            '<i class="ki-solid ki-archive fs-4 m-0 toggle-on"></i> '+
                            '</a>';

                        <?php
                        }
                        ?>
                        return html;
                    }
                },

            ],
            columnDefs: [{
                targets: [0, 1, 2, 3, 4, 5, 6],
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

            ],

            ajax: {
                url: "<?php echo base_url(); ?>Dietario/get_presupuestos",
                type: "GET",
                datatype: "json",
                data: function(data) {
                    var month=jQuery('[name="filter_month"').val();
                    data.filter_month=month;
                    var year=jQuery('[name="filter_year"').val();
                    data.filter_year=year;
                    var idcentro=jQuery('[name="id_centro"').val();
                    data.id_centro=idcentro;
                    var archivado=jQuery('[name="filter_archivado"').val();





                    var id_cliente = $('[name="id_cliente"]').val();
                    if (id_cliente != "") {
                        data.id_cliente = id_cliente;
                    }

                    var fecha_validez = $('[name="fecha_validez"]').val();
                    var id_usuario = $('[name="id_usuario"]').val();
                    var estado = $('[name="filter_estado"]').val();
                    var revisado = $('[name="filter_revisado"]').val();
                    if (id_cliente != "") {
                        data.id_cliente = id_cliente;
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
                    if (revisado != "") {
                        data.revisado = revisado;
                    }
                    data.archivado=archivado;
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
            createdRow: function(row, data, dataIndex) {
                jQuery(row).attr('id','trcita'+data.id_cita);
                jQuery(row).attr('idcita',data.id_cita);
                datasubtemplate.push(data);
            },
            "stripeClasses": [],
            drawCallback: function(settings) {
                handleActionButton();
            },
            initComplete: function() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl, {
                        'trigger': 'hover'
                    })
                });
                handleActionButton();
            },
        });




        const filterSearch = document.querySelector('[kt_table_primerasvisitas_tablee="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            tablePrimerasVisitas.search(e.target.value).draw();
        });

        jQuery('#filter_archivado').on('change',function(){
            tablePrimerasVisitas.draw();
        });

        jQuery('#filter_month').on('change',function(){
            tablePrimerasVisitas.draw();
        });

        jQuery('#filter_year').on('change',function(){
            tablePrimerasVisitas.draw();
        });

        jQuery('#id_centro').on('change',function(){
            tablePrimerasVisitas.draw();
        });

        // Subtable data sample
        const data = [
            {
                image: '76',
                name: 'Go Pro 8',
                description: 'Latest  version of Go Pro.',
                cost: '500.00',
                qty: '1',
                total: '500.00',
                stock: '12'
            },
            {
                image: '60',
                name: 'Bose Earbuds',
                description: 'Top quality earbuds from Bose.',
                cost: '300.00',
                qty: '1',
                total: '300.00',
                stock: '8'
            },
            {
                image: '211',
                name: 'Dry-fit Sports T-shirt',
                description: 'Comfortable sportswear.',
                cost: '89.00',
                qty: '1',
                total: '89.00',
                stock: '18'
            },
            {
                image: '21',
                name: 'Apple Airpod 3',
                description: 'Apple\'s latest earbuds.',
                cost: '200.00',
                qty: '2',
                total: '400.00',
                stock: '32'
            },
            {
                image: '83',
                name: 'Nike Pumps',
                description: 'Apple\'s latest headphones.',
                cost: '200.00',
                qty: '1',
                total: '200.00',
                stock: '8'
            }
        ];


        var handleActionButton = () => {
            const buttons = document.querySelectorAll('[data-kt-table-widget-4="expand_row"]');
            // Sample row items counter --- for demo purpose only, remove this variable in your project
            const rowItems = [3, 1, 3, 1, 2, 1];

            buttons.forEach((button, index) => {
                button.addEventListener('click', e => {
                    e.stopImmediatePropagation();
                    e.preventDefault();
                    const row = jQuery(button).closest('tr');
                    const rowClasses = ['isOpen', 'border-bottom-0'];

                    // Get total number of items to generate --- for demo purpose only, remove this code snippet in your project
                    const demoData = [];
                    for (var j = 0; j < datasubtemplate.length; j++) {
                        if(datasubtemplate[j].id_cita==row.attr('idcita')) {
                            demoData.push(datasubtemplate[j]);
                        }
                    }
                    // End of generating demo data

                    // Handle subtable expanded state
                    if (row.hasClass('isOpen')) {
                        // Remove all subtables from current order row
                        while (row.next() && row.next().attr('data-kt-table-widget-4') === 'subtable_template') {
                            row.next().remove();
                        }
                        for(var i=0;i<rowClasses.length;i++){
                            row.removeClass(rowClasses[i]);
                        }
                        button.classList.remove('active');
                    } else {
                        populateTemplate(demoData, row);
                        for(var i=0;i<rowClasses.length;i++){
                            row.addClass(rowClasses[i]);
                        }
                        button.classList.add('active');
                    }
                });
            });


        }

        var populateTemplate = (data, target) => {
            var toInsertElements=[];
            data.forEach((d, index) => {
                // Clone template node
                const newTemplate = template.clone();

                // Populate elements with data

                if(d.id_presupuesto) {
                    var cssclass='light';
                    switch(d.presupuesto_estado){
                        case 'Borrador': cssclass='secondary'; break;
                        case 'Pendiente': cssclass='warning'; break;
                        case 'Aceptado parcial': cssclass='info'; break;
                        case 'Aceptado': cssclass='success'; break;
                        case 'Rechazado': cssclass='danger'; break;
                    }

                    newTemplate.find('a.linkpresu').attr('href', '/presupuestos/ver_detalle/' + d.id_presupuesto)
                        .html('<strong>' + d.nro_presupuesto + ' - <span class="badge badge-light-'+cssclass+'">'+d.presupuesto_estado+'</span>' + '</strong>');
                    newTemplate.find('.template_total').html(d.totalpresupuesto+'&euro;');
                    if(d.descuento){
                        newTemplate.find('.template_dto').html(d.descuento+"%");
                    }
                    else{
                        newTemplate.find('.template_dto').html('-');
                    }
                    newTemplate.find('.presufecha').html('<br/>'+d.presupuesto_fecha_creacion);
                    var selected="";
                    var htmlpago=
                        '<select class="form-select-sm form-select-solid selpresupago" data-control="select2" data-placeholder="Select an option" data-hide-search="true">';
                    if("Sin Definir"==d.pago_estado_manual){
                        selected=" selected ";
                    }else selected="";
                    htmlpago+='<option value="Sin Definir" '+selected+'>Sin Definir</option>';
                    if("Pendiente"==d.pago_estado_manual){
                        selected=" selected ";
                    }else selected="";
                    htmlpago+='<option value="Pendiente"  '+selected+'>Pendiente</option>';
                    if("Recibido"==d.pago_estado_manual){
                        selected=" selected ";
                    }else selected="";
                    htmlpago+='<option value="Recibido" '+selected+'>Recibido</option>';
                    htmlpago+='</select>';
                    newTemplate.find('.template_pago').html(htmlpago);
                }
                else{
                    newTemplate.find('a.linkpresu').parent()
                        .append('<span class="badge badge-light-danger fs-8 fw-bold">Sin Presupuesto</span>');
                    newTemplate.find('a.linkpresu').remove();
                    newTemplate.find('.presufecha').remove();

                    newTemplate.find('.template_total').html('-');
                    newTemplate.find('.template_dto').html('-');
                    newTemplate.find('.template_pago').html('-');
                }

                if(d.fecha_proxima_cita){
                    newTemplate.find('.template_proximacita').html(d.fecha_proxima_cita);
                }
                else{
                    if(d.fecha_cita_posterior){
                        newTemplate.find('.template_proximacita').html(d.fecha_cita_posterior);
                    }
                    else
                    newTemplate.find('.template_proximacita').html('<span class="badge badge-light-danger fs-8 fw-bold">Sin cita</span>');
                }



                // New template border controller
                // When only 1 row is available
                if (data.length === 1) {
                    //let borderClasses = ['rounded', 'rounded-end-0'];
                    //newTemplate.querySelectorAll('td')[0].classList.add(...borderClasses);
                    //borderClasses = ['rounded', 'rounded-start-0'];
                    //newTemplate.querySelectorAll('td')[4].classList.add(...borderClasses);

                    // Remove bottom border
                    //newTemplate.classList.add('border-bottom-0');
                } else {
                    // When multiple rows detected
                    if (index === (data.length - 1)) { // first row
                        //let borderClasses = ['rounded-start', 'rounded-bottom-0'];
                        // newTemplate.querySelectorAll('td')[0].classList.add(...borderClasses);
                        //borderClasses = ['rounded-end', 'rounded-bottom-0'];
                        //newTemplate.querySelectorAll('td')[4].classList.add(...borderClasses);
                    }
                    if (index === 0) { // last row
                        //let borderClasses = ['rounded-start', 'rounded-top-0'];
                        //newTemplate.querySelectorAll('td')[0].classList.add(...borderClasses);
                        //borderClasses = ['rounded-end', 'rounded-top-0'];
                        //newTemplate.querySelectorAll('td')[4].classList.add(...borderClasses);

                        // Remove bottom border on last row
                        //newTemplate.classList.add('border-bottom-0');
                    }
                }

                // Insert new template into table
                /*var idcita=jQuery(target).attr('id');
                console.log(newTemplate);
                const tbody = jQuery('#kt_table_primerasvisitas_tablee').find('tbody');*/

                //newTemplate.insertAfter(target);
                newTemplate.find(".selpresupago").on('change',function(){
                    var $this=jQuery(this);
                    $this.hide();
                    $this.parent().append(jQuery('<span class="spinner-border text-primary" role="status" style="width:14px;height: 14px"><span class="visually-hidden">Loading...</span></span>'));
                    $.ajax({
                        type: "POST",
                        data: {
                            id_presupuesto: d.id_presupuesto,
                            estado: $this.val()
                        },
                        url: "<?php echo base_url(); ?>Dietario/set_presupuestopagoestado/",
                        dataType: 'json',
                        success: function(response)
                        {
                            $this.parent().find(".spinner-border").remove();
                            $this.val(response.estado);
                            $this.show();
                        }
                    });
                });
                toInsertElements.push(newTemplate);

                if(d.presupuestos_anteriores.length>0){
                    for(var p=0;p<d.presupuestos_anteriores.length;p++){
                        var presu=d.presupuestos_anteriores[p];
                        if(presu.id_presupuesto!=d.id_presupuesto) {
                            var cssclass='light';
                            switch(presu.estado){
                                case 'Borrador': cssclass='secondary'; break;
                                case 'Pendiente': cssclass='warning'; break;
                                case 'Aceptado parcial': cssclass='info'; break;
                                case 'Aceptado': cssclass='success'; break;
                                case 'Rechazado': cssclass='danger'; break;
                            }
                            var subt = jQuery("#subtable_template_anteriores").clone();
                            subt.removeClass('d-none');
                            subt.find('a.linkpresu').attr('href', '/presupuestos/ver_detalle/' + presu.id_presupuesto)
                                .html('<strong>' + presu.nro_presupuesto + ' - <span class="badge badge-light-'+cssclass+'">'+presu.estado+'</span>' + '</strong>');
                            subt.find('.template_total').html(presu.totalpresupuesto + '&euro;');
                            if (presu.dto_100 > 0) {
                                subt.find('.template_dto').html(presu.dto_100 + "%");
                            } else if (presu.dto_euros > 0) {
                                subt.find('.template_dto').html(presu.dto_euros + "€");
                            } else {
                                subt.find('.template_dto').html('-');
                            }
                            subt.find('.presufecha').html('<br/>' + d.presupuesto_fecha_creacion);
                            //console.log(subt.html());
                            //subt.insertAfter(newTemplate);

                            subt.find('.template_estado').html('<span class="badge badge-light-'+cssclass+'">'+presu.estado+'</span>');



                            toInsertElements.push(subt);
                        }
                    }
                }


/*                jQuery('<tr><td><span class="spinner-border text-primary" role="status" style="width:14px;height: 14px"><span class="visually-hidden">Loading...</span></span> <small>Cargando presupuestos...</small></td></tr>')
                    .insertAfter(newTemplate);
                $.ajax({
                    type: "GET",
                    data: function(data) {
                        data.id_cliente = d.id_cliente
                    },
                    url: "<?php echo base_url(); ?>Presupuestos/get_presupuestosporcliente/",
                    dataType: 'json',
                    success: function(response)
                    {

                    }
                });
*/


            });
            for(k=toInsertElements.length-1;k>=0;k--){
                console.log(k);
                var elem=toInsertElements[k];
                elem.insertAfter(target);
            }
        }

        jQuery(document).on('click','.btnarchives',function(event){
            event.preventDefault();
            event.stopPropagation();
            var url = jQuery(this).attr("href");
            var html='¿DESEA ARCHIVAR LA PRIMERA CITA?';
            if(jQuery(this).hasClass('btndesarchives')) html='¿DESEA DES-ARCHIVAR LA PRIMERA CITA?';
            var btnok='Si, archivar';
            if(jQuery(this).hasClass('btndesarchives')) btnok='Si, desarchivar';
            Swal.fire({
                html: html,
                showCancelButton: true,
                confirmButtonText: btnok,
                cancelButtonText: 'No, cancelar',
                showLoaderOnConfirm: true,
                allowOutsideClick: false,
                onBeforeOpen: () => {},

            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        method: 'get',
                        url: url,
                        data: {},
                        success: function(data){
                            tablePrimerasVisitas.draw();
                        },
                        dataType: 'json'
                    });
                }
            });

            return false;
        });


        jQuery(document).on('click','.btncomments',function(event){
            event.preventDefault();
            event.stopPropagation();
            var url = jQuery(this).attr("href");
            $('#modal-presupuesto .modal-title').html('Observaciones de la cita');
            $('#modal-presupuesto .modal-body').html('<i class="fas fa-sync fa-spin"></i>');
            $('#modal-presupuesto').modal('show');

            $.ajax({
                method: 'get',
                url: url,
                data: {},
                success: function(data){
                    $('#modal-presupuesto .modal-body').html(data);
                },
                dataType: 'html'
            });

            return false;
        });



        jQuery(document).on('click','.nuevoevol',function(event){
            event.preventDefault();
            event.stopPropagation();
            jQuery(".item_evolutivo").addClass('hideimportant');
            jQuery("button.nuevoevol").addClass('hideimportant');
            jQuery("textarea#nota_evolutivo").text('');
            tinymce.remove("textarea#nota_evolutivo");
            jQuery("#form-idobservacion").val(0);

            tinymce.init({
                forced_root_block : "",
                selector: 'textarea#nota_evolutivo',
                language_url: '<?= base_url() ?>assets_v5/plugins/custom/tinymce/langs/es.js',
                language: 'es',
                menubar: false,
                height: 500,
            });

            jQuery("#editordiv").show();
            return false;
        });

        jQuery(document).on('click', '.editevol', function(event) {
            $(".editable").each(function() { $(this).tinymce().remove();});
            event.preventDefault();
            event.stopPropagation();
            var id=jQuery(this).attr('idevol');
            jQuery("#form-idobservacion").val(id);
            jQuery(".item_evolutivo").addClass('hideimportant');
            jQuery("button.nuevoevol").addClass('hideimportant');
            jQuery("textarea#nota_evolutivo").text(jQuery(".content-evol-"+id).html());
            tinymce.remove("textarea#nota_evolutivo");

            tinymce.init({
                selector: 'textarea#nota_evolutivo',
                language_url: '<?= base_url() ?>assets_v5/plugins/custom/tinymce/langs/es.js',
                language: 'es',
                menubar: false,
                height: 500,
            });

            jQuery("#editordiv").show();
            return false;
        });

        jQuery(document).on('click', '.delevol', function(event) {
            event.preventDefault();
            event.stopPropagation();
            var id=jQuery(this).attr('idevol');
            var idcita=jQuery(this).attr('idcita');

            if (confirm("¿DESEA MARCAR COMO BORRADO EL REGISTRO?")) {
                var url = '<?= base_url() ?>citasextra/borrar_observacion/' + id;
                $.ajax({
                    method: 'get',
                    url: url,
                    data: {},
                    success: function(data){
                        jQuery(".btncomments-"+idcita).trigger('click');
                    },
                    dataType: 'html'
                });
            }

            return false;
        });

        jQuery(document).on('click','.btnsaveobs',function(event){
            event.preventDefault();
            event.stopPropagation();
            var idobs=jQuery("#form-idobservacion").val();
            var idcita=jQuery("#form-idcita").val();
            var content=tinymce.get('nota_evolutivo').getContent();

            var url = '<?= base_url() ?>citasextra/guardar_observacion/' + idcita;
            $.ajax({
                method: 'post',
                url: url,
                data: {
                    idobservacion: idobs,
                    observacion: content
                },
                success: function(data){
                    jQuery(".btncomments-"+idcita).trigger('click');
                },
                dataType: 'json'
            });
        });


        $(document).on('click', '.linkpresu', function(event) {
            event.preventDefault();
            event.stopPropagation();
            var url = '<?= base_url() ?>'+jQuery(this).attr("href");
            $('#modal-presupuesto .modal-title').html('Detalle del presupuesto');
            $('#modal-presupuesto .modal-body').html('<i class="fas fa-sync fa-spin"></i>');
            $('#modal-presupuesto').modal('show');

            $.ajax({
                method: 'get',
                url: url,
                data: {},
                success: function(data){
                    $('#modal-presupuesto .modal-body').html(data);
                },
                dataType: 'html'
            });

            return false;
        });

    });

</script>