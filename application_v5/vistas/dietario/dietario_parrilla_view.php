<?php
if(true || isset($_GET['v2'])){
    // Nueva vista del dietario - parrilla (primeras visitas)
    $this->load->view($this->config->item('template_dir') . '/dashboards/primerasvisitaswidget');

    return;
}
?>


<script type="text/javascript" src="<?= base_url() ?>assets_v5/plugins/custom/tinymce/tinymce.bundle.js"></script>
<style type="text/css">
    .rowred{
        background-color: #c30b0b!important;
        color: #ffffff!important;
    }
    .table-striped>tbody>tr.rowred:nth-of-type(odd)>*{
        color: #ffffff!important;
    }

    .rowverde{
        background-color: #AAF0A8!important;
    }

    .rowamarillo{
        background-color: #F1F72D!important;

    }
    .rownaranja{
        background-color: #FFCC00!important;

    }

    .rowred a{
        color: #FFFFFF;
    }

    .rownaranja a, .rowamarillo a, .rowverde a{
        color: #000000;
    }

    a.linkpresu{
        color: #000000!important;
    }


</style>


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

        </div>
        <div class="card-title w-100 justify-content-end flex-wrap">
            <div class="m-1">
                <label class="form-label">Control</label>
                <select name="filter_archivado" id="filter_archivado" class="form-select form-select-solid w-auto">
                    <option value="">Todos</option>
                    <option value="0" selected="selected">No archivado</option>
                    <option value="1">Archivado</option>
                </select>
            </div>
            <div class="m-1">
                    <label class="form-label">Mes</label>
                    <select name="filter_month" id="filter_month" class="form-select form-select-solid w-auto">
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
            </div>
            <div class="m-1">
                    <label class="form-label">Año</label>
                    <select name="filter_year" id="filter_year" class="form-select form-select-solid w-auto">
                        <?php
                        for($yy=date("Y");$yy>=2017;$yy--){
                            ?>
                            <option value="<?php echo $yy;?>"><?php echo $yy;?></option>
                            <?php
                        }
                        ?>
                    </select>
            </div>


            <div class="m-1">


            </div>
            <div class="m-1">


            </div>
            <div class="m-1">
                <label class="form-label">Centro</label>
                <select name="id_centro" id="id_centro" class="form-select form-select-solid">
                    <option value=""></option>
                    <?php if (isset($centros_todos)) {
                        if ($centros_todos != 0) {
                            foreach ($centros_todos as $key => $row) {
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

        </div>
    </div>
    <form name="form_delete_comision_modal" id="delete_comision_modal" action="" method="post">
        <input type="hidden" id="delete_borrado" name="borrado" value="1" />
        <input type="hidden" id="delete_accion" name="accion" value="delete" />
        <div class="card-body pt-6">
            <div class="table-responsive">
                <table id="tabla_presupuestos" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                    <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                    <?php /*    <th class="col_id">#</th> */ ?>
                        <th class="col_fecha">F. Cita.</th>
                        <th class="">Paciente.</th>
                        <th>Cita</th>
                        <th class="">Doctor</th>
                        <th class="">PRES.</th>
                        <th>DTO.</th>
                        <th>IMP. VENTA</th>
                        <th>SALDO</th>
                        <th>PROX. CITA</th>
                        <th>OBSERV.</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold"></tbody>
                </table>
            </div>
        </div>
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




<style type="text/css">
    .hideimportant{
        display: none!important;
    }
</style>


<script>

    var tabla_presupuestos = $("#tabla_presupuestos").DataTable({
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
            [10, "asc"]
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
                    //1
                    titlee: "",
                    name: "fecha_hora_inicio",
                    data: "fecha_hora_inicio",
                    className: "text-nowrap",
                    render: function(data, type, row) {
                                return ' <span>'+row.fecha_inicio+'</span>'+
                                        '<br/><span class=" ms-2 small">'+row.hora_inicio+'</span>';
                            }
                    },
                    {
                    //2
                    titlee: "",
                    name: "paciente",
                    data: "nombre_cliente",
                    className: "text-nowrap",
                    render: function(data, type, row) {
                                    var html = '<span class="text-nowrap d-block"><i class="fa fa-user text-dark-emphasis"></i> '
                                                    + '<a href="<?php echo base_url();?>/clientes/historial/ver/'+row.id_cliente+'">'
                                                    + row.nombre_cliente+' '+ row.apellidos_cliente + '</a></span>';

                                    html += '<span class=" ms-2 small"><i class="fa fa-phone"></i> ' + row.telefono_cliente + '</span>';
                                    return html
                            }
                    },
                    {
                    // 3
                    titlee: "",
                    name: "nombre_servicio",
                    data: "nombre_servicio",
                    className: "text-nowrap",
                    render: function(data, type, row) {
                                    return row.nombre_servicio;
                            }
                    },
                    {
                    // 4
                    name: "doctor",
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
                        //5
                        titlee: "",
                        name: "id_presupuesto",
                        data: "id_cita",
                        className: "text-nowrap",
                        render: function(data, type, row) {
                                    var icon=''
                                    var title='';
                                    var estadoclass='';
                                    if(row.es_presupuesto_cita){
                                        icon='fa-calendar';
                                        title='El presupuesto viene de la cita';
                                    }
                                    else{
                                        icon='fa-calendar-xmark';
                                        title='El presupuesto no viene de la cita';
                                    }
                                    switch(row.presupuesto_estado){
                                        case 'Pendiente': estadoclass='badge py-3 px-4 fs-7 badge-light-warning'; break;
                                        case 'Rechazado': estadoclass='badge py-3 px-4 fs-7 badge-light-danger'; break;
                                        case 'Borrador': estadoclass='badge py-3 px-4 fs-7 badge-light-primary'; break;
                                        case 'Aceptado':
                                        case 'Aceptado parcial':
                                            estadoclass='badge py-3 px-4 fs-7 badge-light-success';
                                            break;
                                    }


                                    var badge='';
                                    if(row.count_presupuestos>1){
                                        badge+='&nbsp;&nbsp<span class="badge badge-circle badge-outline badge-dark">'+row.count_presupuestos+'</span>'
                                    }

                                    var rtval=row.id_presupuesto>0 ?
                                            ('<i class="fa '+icon+' text-dark-emphasis" title="'+title+'"></i> <span class="'+estadoclass+'">'+
                                                     '<a class="linkpresu" href="/presupuestos/ver_detalle/'+row.id_presupuesto+'">'+row.id_presupuesto)+ ' - '+ row.presupuesto_estado + '</a></span>' + badge +
                                                '<br/><span class=" ms-2 small">'+'<i class="fa fa-calendar-check text-dark-emphasis text-success" title="Fecha de creacion"></i> '+row.presupuesto_fecha_creacion+'</span>'+
                                                '<br/><span class=" ms-2 small">'+'<i class="fa fa-calendar-times text-dark-emphasis text-danger" title="Fecha de validez"></i> '+row.presupuesto_fecha_validez+'</span>'
                                            : '' ;
                                    return rtval;
                                }
                        },
                        {
                            //6
                            titlee: "",
                            name: "descuento",
                            data: "descuento",
                            className: "text-nowrap text-center",
                            render: function(data, type, row) {
                                return row.descuento ? row.descuento+'%' : '';
                            }
                        },
                        {
                            //7
                            titlee: "",
                            name: "totalpresupuesto",
                            data: "totalpresupuesto",
                            className: "text-nowrap text-center",
                            render: function(data, type, row) {
                                return row.totalpresupuesto ? row.totalpresupuesto+'€' : '';
                            }
                        },
                        {
                            //8
                            titlee: "",
                            name: "pagado",
                            data: "pagado",
                            className: "text-nowrap text-center",
                            render: function(data, type, row) {
                                return row.pagado ? (Math.round(row.pagado*100)/100)+'€' : '';
                            }
                        },
                        {
                            //9
                            titlee: "",
                            name: "fecha_proxima_cita",
                            data: "fecha_proxima_cita",
                            className: "text-nowrap text-center",
                            render: function(data, type, row) {
                                if(row.fecha_proxima_cita) {
                                    return ' <span>'+row.fecha_proxima_cita+'</span><br/>'+
                                                '<span class=" ms-2 small">'+row.hora_proxima_cita+'</span>';
                                }
                                return '';
                            }
                        },
                        {
                            //10
                            titlee: "",
                            name: "observaciones",
                            data: "observaciones",
                            render: function(data, type, row) {
                                var classbtn='btn-dark';
                                if(row.count_observaciones>0){
                                    classbtn=' btn-warning ';
                                }
                                return '<a href="<?php echo base_url();?>citasextra/observaciones/'+row.id_cita+'" '+
                                        'title="'+row.count_observaciones+' observaciones"  '+' class="btncomments btncomments-'+row.id_cita+' btn btn-icon '+classbtn+' me-2 mb-2">'+
                                '<i class="fas fa-comment fs-4"></i>'+
                            '</a>';           
                            }
                        },
            {
                //11
                titlee: "",
                name: "",
                data: "",
                render: function(data, type, row) {
                    <?php
                    if($this->session->userdata('id_perfil')==0){
                        ?>
                    return row.archivado_parrilla=="1" ?
                        '<a href="<?php echo base_url();?>citasextra/archivarparrilla/'+row.id_cita+'/0" title="DesArchivar" '+
                        ' class="btnarchives btndesarchives btnarchive-'+row.id_cita+' btn btn-icon btn-danger me-2 mb-2">'+
                        '<i class="fas fa-archive fs-4"></i>'  :
                        '<a href="<?php echo base_url();?>citasextra/archivarparrilla/'+row.id_cita+'/1" title="Archivar" '+
                        ' class="btnarchives btnarchive-'+row.id_cita+' btn btn-icon btn-primary me-2 mb-2">'+
                        '<i class="fas fa-archive fs-4"></i>';
                        <?php
                     }
                     else{
                         ?>
                         return '';
                    <?php
                     }
                     ?>
                }
            },
            {
                //12
                titlee: "",
                name: "",
                data: "",
                render: function(data, type, row) {
                    return '';
                }
            },
        ],
        columnDefs: [{
            targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10,11],
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
        createdRow: function(row, data, dataIndex) {
            if((data.pagado==null || data.pagado==0) && (data.fecha_proxima_cita == null || data.fecha_proxima_cita=='')) {
                jQuery(row).addClass('rowred');
            }
            if((data.pagado>0) && (data.fecha_proxima_cita != null && data.fecha_proxima_cita!='')) {
                jQuery(row).addClass('rowverde');
                jQuery(row).removeClass('odd').removeClass('even');
            }
            if((data.pagado>0) && (data.fecha_proxima_cita == null || data.fecha_proxima_cita=='')) {
                jQuery(row).addClass('rowamarillo');
                jQuery(row).removeClass('odd').removeClass('even');
            }
            if((data.pagado==null || data.pagado==0) && (data.fecha_proxima_cita != null && data.fecha_proxima_cita!='')) {
                jQuery(row).addClass('rownaranja');
                jQuery(row).removeClass('odd').removeClass('even');
            }


        },
        "stripeClasses": [],
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
                        tabla_presupuestos.draw();
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

    $('[data-table-search]').on('input', function() {
        tabla_presupuestos.search($(this).val()).draw();
    });

    jQuery('#filter_month').on('change',function(){
        tabla_presupuestos.draw();
    });
    jQuery('#filter_year').on('change',function(){
        tabla_presupuestos.draw();
    });
    jQuery('#id_centro').on('change',function(){
        tabla_presupuestos.draw();
    });
    jQuery('#filter_archivado').on('change',function(){
        tabla_presupuestos.draw();
    });






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
</script>