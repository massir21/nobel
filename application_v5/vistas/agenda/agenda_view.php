<?php
if(!isset($citaspendientesnota) || $citaspendientesnota==0){
    $citaspendientesnota=[];
}
$clientescitasSinNota=[];
foreach($citaspendientesnota as $reg){
    if(!isset($clientescitasSinNota[$reg['id_cliente']])) $clientescitasSinNota[$reg['id_cliente']]=[];
    $clientescitasSinNota[$reg['id_cliente']][]=$reg;
}
if(count($clientescitasSinNota)){
?>
<div id="tabla-citaspendientes" class="modal fade" tabindex="-1" aria-labelledby="" aria-hidden="true" data-focus-on="input:first">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div style="padding:50px">
            <h4>Clientes sin Notas Clínicas en sus citas</h4>
                <br/>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr class="fw-bold fs-6 text-gray-800">
                        <th>Paciente</th>
                        <th>Fechas de las citas</th>
                        <th>Doctor/es</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($clientescitasSinNota as $xidcliente => $citas){
                        if(count($citas)){
                            $xxcitas=[];
                            $xxdoctores=[];
                            foreach($citas as $cita){
                                $xxcitas[$cita['fecha_cita']]=date("d/m/Y",strtotime($cita['fecha_cita']));
                                $xxdoctores[$cita['id_doctor']]=$cita['nombre_doctor'];
                            }
                        ?>
                        <tr>
                            <td><a target="_blank"
                                   href="<?php echo base_url(); ?>clientes/historial/ver/<?php echo $citas[0]['id_cliente']; ?>">
                                <?php echo $citas[0]['nombre_cliente'];?>
                                </a></td>
                            <td><?php echo implode(", ",array_values($xxcitas)); ?></td>
                            <td><?php
                                foreach($xxdoctores as $id_doctor => $xxdoctor){
                                    echo $xxdoctor."<br/>";
                                }
                            ?></td>
                        </tr>
                        <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>
</div>
<div class="py-5">
    <div class="rounded border p-10 pb-0 d-flex flex-column">
        <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
            <i class="ki-duotone ki-shield-tick fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span></i>                    <div class="d-flex flex-column">
                <h4 class="mb-1 text-danger">Pacientes pendientes de incluir historia clínica</h4>
                <span>Existen <?php echo count($clientescitasSinNota);?> pacientes con cita a los que no se les ha añadido nota en su historia clínica
                    <a href="javascript:void(0);" data-bs-target="#tabla-citaspendientes" data-bs-toggle="modal">Ver Pacientes</a>
            </div>
        </div>
    </div>
</div>
<?php
}
?>
<?php if (isset($citas_agenda)) { ?>
    <style>
        /*.fc-event-draggable.fc-event-resizable{
            border: 2px solid red;
            box-shadow: 0px 0px 0px 2px red !important;
        }*/
        .sinsaldo {
            border-left: 10px solid red !important;
            border-radius: 1rem;
            padding: 2px;
        }

        .fc .fc-timegrid-slot {
            height: 45px;
        }

        .fc-event-main {
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Firefox */
        * {
            scrollbar-width: thin;
            scrollbar-color: #709CB9 #DFE9EB;
        }

        /* Chrome, Edge and Safari */
        .fc-scroller::-webkit-scrollbar {
            width: 11px;
            width: 11px;
        }

        .fc-scroller::-webkit-scrollbar-track {
            border-radius: 3px;
            background-color: #DFE9EB;
        }

        .fc-scroller::-webkit-scrollbar-track:hover {
            background-color: #B3C2C1;
        }

        .fc-scroller::-webkit-scrollbar-track:active {
            background-color: #B8C0C2;
        }

        .fc-scroller::-webkit-scrollbar-thumb {
            border-radius: 5px;

            background-color: #4D93B9;
        }

        .fc-scroller::-webkit-scrollbar-thumb:hover {
            background-color: #709CB9;
        }

        .fc-scroller::-webkit-scrollbar-thumb:active {
            background-color: #6876B9;
        }


        @media (max-width: 582px) {

            .fc .fc-col-header-cell .fc-col-header-cell-cushion,
            .fc .fc-timegrid-axis-cushion,
            .fc .fc-timegrid-slot-label-cushion,
            .fc .fc-timegrid-event .fc-event-main {
                font-size: 4px;
            }

            .fc .fc-col-header-cell {
                padding: 0px;
            }
        }

        .fc-non-business {
            background-color: #8a8a8a;
            opacity: .5;
            background: repeating-linear-gradient(-45deg, #8a8a8a, #8a8a8a 6.5px, #8a8a8a 6.5px, #8a8a8a 32.5px) !important;
        }

        .fc-theme-standard td,
        .fc-theme-standard th {
            border: 1px solid #8a8a8a;
        }

        .en_sala:before {
            position: absolute;
            content: "*";
            font-size: 2rem;
            text-align: center;
            font-weight: 900;
            line-height: 1.1;
            left: -14px;
            top: 5px;
            width: 20px;
            height: 20px;
            background-color: yellow;
            border-radius: 50%;
            box-shadow: 1px 2px 7px 2px rgba(0, 0, 0, .6);
        }
    </style>
    <script>
        $(document).ready(function() {
            const element = document.getElementById("agenda");
            var todayDate = moment().startOf("day");
            var YM = todayDate.format("YYYY-MM");
            var YESTERDAY = todayDate.clone().subtract(1, "day").format("YYYY-MM-DD");
            var TODAY = '<?php echo $fecha; ?>'; //todayDate.format("YYYY-MM-DD");
            var TOMORROW = todayDate.clone().add(1, "day").format("YYYY-MM-DD");


            // para las bussineshours
            var calendarEl = document.getElementById("agenda");
            var calendar = new FullCalendar.Calendar(calendarEl, {
                schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
                headerToolbar: false,
                height: 565,
                contentHeight: 550,
                aspectRatio: 3,
                nowIndicator: true,
                // now: TODAY + "T09:25:00", // just for demo
                views: {
                    dayGridMonth: {
                        buttonText: "Mes"
                    },
                    timeGridWeek: {
                        buttonText: "Semana"
                    },
                    timeGridDay: {
                        buttonText: "Día"
                    },
                    resourceTimeGridDay: {
                        buttonText: "Horario",
                    },
                },
                initialView: 'resourceTimeGridDay',
                initialDate: TODAY,
                locale: 'es',
                editable: true,
                dayMaxEvents: true, // allow "more" link when too many events
                navLinks: true,
                businessHours: true,
                selectable: true,
                slotDuration: "00:15",
                slotMinTime: "08:00",
                slotMaxTime: "21:15",
                slotLabelFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    omitZeroMinute: false,
                },
                slotLabelInterval: "00:15",
                allDaySlot: false,
                displayEventTime: true,
                resources: [
                    <?php if ($empleados != 0) {
                        foreach ($empleados as $key => $row) { ?> {
                                id: '<?php echo $row['id_usuario']; ?>',
                                title: '<?php echo $row['nombre'] . " " . $row['apellidos']; ?>',
                                eventColor: '#fad7e4',
                                businessHours: [
                                    <?php if ($horarios[$row['id_usuario']] != 0) {
                                        foreach ($horarios[$row['id_usuario']] as $key => $hora_trabajo) { ?> {
                                                // days of week. an array of zero-based day of week integers (0=Sunday)
                                                daysOfWeek: [0, 1, 2, 3, 4, 5, 6], // Todos los dias.
                                                startTime: '<?php echo $hora_trabajo['hora_inicio']; ?>', // a start time
                                                endTime: '<?php echo $hora_trabajo['hora_fin']; ?>', // an end time
                                            },
                                    <?php }
                                    } ?>
                                ],
                                horarios: [
                                    <?php if ($horarios[$row['id_usuario']] != 0) {
                                        foreach ($horarios[$row['id_usuario']] as $key => $hora_trabajo) { ?> {
                                                // days of week. an array of zero-based day of week integers (0=Sunday)
                                                dow: [0, 1, 2, 3, 4, 5, 6], // Todos los dias.
                                                start: '<?php echo $hora_trabajo['hora_inicio']; ?>', // a start time
                                                end: '<?php echo $hora_trabajo['hora_fin']; ?>', // an end time
                                            },
                                    <?php }
                                    } ?>
                                ],
                            },
                    <?php }
                    } ?>
                ],
                events: [
                    <?php if ($citas_agenda != 0) {
                        foreach ($citas_agenda as $key => $row) {
                            $row['observaciones'] = trim($row['observaciones']);
                            $row['observaciones'] = preg_replace("/[\r\n|\n|\r]+/", " ", $row['observaciones']);
                            $time = "<span class=\"d-block\">" . $row['hora_inicio'] . " - " . $row['hora_fin'] . "</span>";
                            $online = "";
                            if ($row['id_usuario_creador'] == 0) {
                                $online = '<img src="' . base_url() . 'recursos/images/online.png"> ';
                            }
                            $firma = "";
                            if (isset($row['existe_firma']) && $row['existe_firma'] == 0 && $row['no_quiere_publicidad'] == 0) {
                                $firma = '<i class="fa fa-pencil" aria-hidden="true" style="color: red;"></i> ';
                            }
                            $recordatorio_sms = ($row['recordatorio_sms'] == 1) ? "(SMS)" : "";
                            $recordatorio_email = ($row['recordatorio_email'] == 1) ? "(Email)" : "";
                            $solo_este_empleado = ($row['solo_este_empleado'] == 1) ? '<strong>' . strtoupper($row['cliente']) . '</strong>' : strtoupper($row['cliente']);

                            $observaciones = ($row['observaciones'] != '') ? addslashes(strtolower($row['observaciones'])) : '';
                            $dientes = ($row['dientes'] != '') ? ' (PIEZA Nº:' . $row['dientes'] . ')' : '';

                            // CHAINS 20240127: Se muestran los otros dientes de evento agrupado
                            if(isset($row['otrosdientes'])){
                                foreach($row['otrosdientes'] as $otrodiente){
                                    $dientes.=' (PIEZA Nº:' . $otrodiente . ')';
                                }
                            }
                            // CHAINS 20240127: Se añade el parametro para obtener los eventos agrupados

                            $servicio = (isset($row['servicio']) && $row['servicio'] !== null) ? "<strong class=\"d-block\">" . strtoupper($row['servicio']) . $dientes . "</strong>" : "";
                            $servicioTip = (isset($row['servicio']) && $row['servicio'] !== null) ?  strtoupper($row['servicio']) . $dientes : "";

                            $titleevent = $time . $online . $firma . $solo_este_empleado . $servicio . $observaciones . $recordatorio_sms . " " . $recordatorio_email;

                            $colorservicio = ($row['id_presupuesto'] == '') ? '#96BE25' : (($row['color_servicio'] == "") ? "#fad7e4" : $row['color_servicio']);

                            $textColor = ($row['estado'] == "Programada") ? "#000" : '#b90400';
                            $tip = strtoupper($row['cliente']) . ' ' . $servicioTip . $observaciones . $recordatorio_sms . " " . $recordatorio_email;
                            $resourceEditable = ($row['solo_este_empleado'] == 1 || $row['estado'] == "Finalizado") ? 'false' : 'true';

                            $editable = ($row['estado'] == "Programada" && $this->session->userdata('id_perfil') != 1) ? 'true' : 'false';
                            $bordercolor = 'red'; // ($row['saldo'] == 0) ? 'red' : $colorservicio;
                            if ($row['id_servicio'] == 15460 || $row['id_servicio'] == 15404 || $row['id_servicio'] == 15588 )  {
                                $colorservicio = $row['color_servicio'];
                                $bordercolor = $row['color_servicio'] . ' !important';
                            }

                    ?> {
                                id: <?php echo $row['id_cita']; ?>,
                                estado: '<?php echo $row['estado']; ?>',
                                resourceId: '<?php echo $row['id_usuario_empleado']; ?>',
                                perfil: <?php echo $this->session->userdata('id_perfil'); ?>,
                                title: '<?= $titleevent ?>',
                                start: '<?php echo $row['fecha_inicio_aaaammdd'] . " " . $row['hora_inicio']; ?>',
                                end: '<?php echo $row['fecha_inicio_aaaammdd'] . " " . $row['hora_fin']; ?>',
                                color: '<?= $colorservicio ?>',
                                textColor: '<?= $textColor ?>',
                                // borderColor: "<?= $bordercolor ?>",
                                description: '<?= $tip ?>',
                                resourceEditable: <?= $resourceEditable ?>,
                                editable: <?= $editable ?>,
                                overlap: true,
                                allDay: false,
                                sinsaldo: "<?= $row['saldo'] ?>",
                                finalizada: "<?= ($row['estado'] == "Programada") ? 0 : 1 ?>",
                                colorservicio: "<?= ($row['id_servicio'] == 15460 || $row['id_servicio'] == 15404) ? 1 : 0 ?>",
                                id_cliente: <?php echo $row['id_cliente']; ?>,
                                fecha: '<?php echo $row['fecha_inicio_aaaammdd'] ?>',
                                en_sala: "<?= $row['en_sala'] ?>",
                            },
                    <?php }
                    } ?>


                ],
                eventDidMount: function(info) {
                    var elementtooltip = $(info.el).find('.fc-event-main')
                    var tooltip = new bootstrap.Tooltip(elementtooltip, {
                        title: info.event.extendedProps.description,
                        placement: 'top',
                        trigger: 'hover',
                        container: 'body'
                    });

                    var eventObj = info.event;


                    var counter_p;
                    var popover = new bootstrap.Popover(info.el, {
                        sanitize: false,
                        trigger: 'manual',
                        placement: 'top',
                        html: true,
                        content: function() {
                            var contentPopover = '<div class="card-toolbar">';
                            if (info.event.extendedProps.en_sala == '') {
                                contentPopover += `<button class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Marcar cita  esperando" onclick="CitasEnSala(${eventObj.id});"><i class="fa-solid fa-user-check"></i></button>`;
                            }
                            if ( info.event.extendedProps.perfil != 1) {
                                contentPopover += `<button class="btn btn-sm btn-icon btn-info ms-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar cita" onclick="CitasEditar(${eventObj.id});"><i class="fa fa-pencil" aria-hidden="true"></i></button>`;
                            }
                            
                            contentPopover += `<button class="btn btn-sm btn-icon btn-warning ms-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Ficha cliente" onclick="FichaCliente(${eventObj.extendedProps.id_cliente});"><i class="fa fa-address-book" aria-hidden="true"></i></button>`;

                            var fechaDada = new Date(info.event.extendedProps.fecha);
                            var fechaActual = new Date();

                            if (fechaDada.getTime() < fechaActual.getTime() && info.event.extendedProps.estado != "Finalizado") {
                                contentPopover += `<button class="btn btn-sm btn-icon btn-success text-inverse-success m-2" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Cobrar cita" onclick="Cobrar(${info.event.extendedProps.id_cliente},'${info.event.extendedProps.fecha}')"><i class="fas fa-euro"></i></button>`;
                            }
                            contentPopover += `</div>`;

                            return contentPopover;

                        },
                        title: info.event.extendedProps.description,
                    });

                    // Evento para mostrar el popover en 'mouseenter'
                    $(info.el).on("click", function(e) {
                        e.preventDefault();
                        clearTimeout(counter_p);
                        $('[rel="popover"]').not(info.el).popover('hide');
                        counter_p = setTimeout(function() {
                            if ($(info.el).is(':hover')) {
                                popover.show();
                                $('[data-bs-toggle="tooltip"]').tooltip();
                            }
                            $(".popover").on("mouseleave", function() {
                                popover.hide();
                            });
                        }, 100);
                    });

                    // Evento para ocultar el popover en 'mouseleave'
                    $(info.el).on("mouseleave", function() {
                        setTimeout(function() {
                            if (!$(".popover:hover").length) {
                                if (!$(info.el).is(':hover')) {
                                    popover.hide();
                                }
                            }
                        }, 300);
                    });
                },
                eventContent: function(info) {
                    return {
                        html: info.event.title
                    };
                },
                eventClick: function(info) {
                    closetooltips()
                    /*var eventObj = info.event;
                    console.log(eventObj.id)
                    console.log(eventObj.title)
                    if (eventObj.editable) {
                        CitasEditar(eventObj.id);
                    } else {
                        CitasEditar(eventObj.id);
                        return false;
                    }*/
                },
                eventResize: function(info) {
                    closetooltips()
                    var resourceId = info.event._def.resourceIds[0];
                    var resource = calendar.getResourceById(resourceId);
                    hora_inicio = info.event.startStr.substr(11, 5);
                    hora_fin = info.event.endStr.substr(11, 5);
                    var r = HoraDentroHorario(hora_inicio, hora_fin, resource.extendedProps.horarios);
                    if (r) {
                        closetooltips()
                        if (!confirm("¿DESEAS CONFIRMAR EL CAMBIO DE DURACIÓN DE LA CITA?")) {
                            info.revert();
                        } else {
                            var parametros = {
                                "id_cita": info.event.id,
                                "fecha_fin_nueva": info.event.endStr,
                            };
                            $.ajax({
                                data: parametros,
                                url: '<?php echo base_url(); ?>agenda/modificar_duracion',
                                type: 'post',
                                beforeSend: function() {
                                    // $("#resultado").html("Procesando, espere por favor..."); // no hay div resultado
                                },
                                success: function(response) {
                                    // $("#resultado").html(response); // no se recibe respuesta
                                }
                            });
                        }
                    } else {
                        info.revert();
                    }

                },
                eventDrop: function(info) {
                    closetooltips()
                    var resourceId = info.event._def.resourceIds[0];
                    var resource = calendar.getResourceById(resourceId);
                    if (info.event.extendedProps.estado == "Finalizado" || info.event.extendedProps.perfil == 1) {
                        info.revert();
                        return false;
                    }
                    hora_inicio = info.event.startStr.substr(11, 5);
                    hora_fin = info.event.endStr.substr(11, 5);

                    var r = HoraDentroHorario(hora_inicio, hora_fin, resource.extendedProps.horarios);
                    var parametros = {
                        "id_cita": info.event.id,
                        "id_empleado_nuevo": resourceId,
                        "fecha_inicio_nueva": info.event.startStr,
                    };
                    puede_capacidad = 0;
                    if (r) {
                        // ... Comprobamos si el empleado de destino puede hacer el servicio.
                        $.ajax({
                            data: parametros,
                            url: '<?php echo base_url(); ?>agenda/comprobar_capacidad_empleado',
                            type: 'post',
                            beforeSend: function() {
                                $("#resultado").html("Procesando, espere por favor...");
                            },
                            success: function(response) {
                                puede_capacidad = response;
                                if (puede_capacidad == 0) {
                                    Swal.fire("NO SE PUEDE MOVER LA CITA AL EMPLEADO INDICADO, PORQUE NO TIENE LA CAPACIDAD DE DAR EL SERVICIO.");
                                    info.revert();
                                } else {
                                    console.log("Hola que tal 2");
                                    if (!confirm("¿DESEAS CONFIRMAR EL CAMBIOS DE LA CITA?")) {
                                        info.revert();
                                    } else {
                                        $.ajax({
                                            data: parametros,
                                            url: '<?php echo base_url(); ?>agenda/modificar_empleado_fecha',
                                            type: 'post',
                                            beforeSend: function() {
                                                $("#resultado").html("Procesando, espere por favor...");
                                            },
                                            success: function(response) {
                                                $("#resultado").html(response);
                                            }
                                        });
                                    }
                                }
                                $("#resultado").html("");
                            }
                        });
                    } else {
                        info.revert();
                    }
                },
                dateClick: function(info) {
                    var resource = info.resource._resource;
                    var hora_click = info.dateStr.substr(11, 5);
                    var r = HoraDentroHorario(hora_click, hora_click, resource.extendedProps.horarios);
                    if (r) {
                        var fecha = info.dateStr.substr(0, 10);
                        var hora = hora_click.replace(":", "-");
                        Citas(resource.id, fecha, hora);
                    } else {
                        return false;
                    }
                },
                select: function(info) {
                    /*var resource = info.resource._resource;
                    var hora_click = info.startStr.substr(11, 5);
                    var r = HoraDentroHorario(hora_click, hora_click, resource.extendedProps.horarios);
                    if (r) {
                        var fecha = info.startStr.substr(0, 10);
                        var hora = hora_click.replace(":", "-");
                        Citas(resource.id, fecha, hora);
                    } else {
                        return false;
                    }*/
                },
                eventClassNames: function(info) {
                    var clases = [];
                    if (info.event.extendedProps.sinsaldo == 0 && info.event.extendedProps.finalizada == 0 && info.event.extendedProps.colorservicio == 0) {
                        clases.push('sinsaldo')
                    }

                    if (info.event.extendedProps.en_sala != '' && info.event.extendedProps.finalizada == 0) {
                        clases.push('en_sala')
                    }
                    return clases;
                }
            });

            calendar.render();
        });
    </script>
<?php } ?>
<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title d-flex flex-column">
            <h3 class="w-100" id="fecha_completa"></h3>
            <input type="date" id="fecha" name="fecha" value="<?php if (isset($fecha)) {
                                                                    echo $fecha;
                                                                } ?>" id="datepicker" class="form-control form-control-solid w-auto" placeholder="Fecha" onchange="FechaCompleta(this.value); <?php if ($this->session->userdata('id_perfil') == 0) { ?>NuevoDiaFiltroCentro();<?php } else { ?>NuevoDia();<?php } ?>" ; required />
        </div>

        <div class="card-toolbar">
            <button class="btn btn-primary text-inverse-primary" onclick="NuevoServicio();">Servicio</button>

            <button class="btn btn-info text-inverse-info ms-3" onclick="NuevoProducto();">Producto</button>
            <?php /*
            <button class="btn btn-warning text-inverse-warning ms-3" onclick="CarnetUnico();"> Carnet Único</button>
            <button class="btn btn-success text-inverse-success ms-3" onclick="NuevoCarnet();"> Carnet</button>
            */ ?>
            <button class="btn btn-secondary text-inverse-secondary ms-3" onclick="CitasEspera();">Cita Espera</button>
            <button class="m-1 btn btn-outline btn-outline-info " onclick="PagoCuenta();" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Realizar un pago a cuenta por el cliente">Pago a cuenta</button>
        </div>
        <?php if ($this->session->userdata('id_perfil') == 0) { ?>
            <div class="card-title">
                <select name="id_centro" id="id_centro" class="form-select form-select-solid" onchange="NuevoDiaFiltroCentro();">
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
        <?php } ?>
    </div>
    <div class="card-body pt-6">
        <div id="agenda"></div>
    </div>
</div>
<script>
    var ventanasAbiertas = [];

    function openwindow(tipoVentana, url, ancho, alto) {
        ventanasAbiertas.forEach(function(ventana) {
            if (ventana.tipoVentana === tipoVentana) {
                ventana.close();
            }
        });

        var ventanaActual = window;
        var posicionVentanaActual = {
            x: ventanaActual.screenX || ventanaActual.screenLeft || 0,
            y: ventanaActual.screenY || ventanaActual.screenTop || 0
        };
        var posicion_x = posicionVentanaActual.x + (ventanaActual.innerWidth - ancho) / 2;
        var posicion_y = posicionVentanaActual.y + (ventanaActual.innerHeight - alto) / 2;
        var nuevaVentana = window.open(url, "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
        nuevaVentana.tipoVentana = tipoVentana
        ventanasAbiertas.push(nuevaVentana);

    }

    function closetooltips() {
        document.querySelectorAll('.tooltip').forEach(function(element) {
            element.remove();
        });
    }

    function Citas() {
        var url = "<?php echo base_url(); ?>agenda/citas/nuevo/";
        openwindow('citas', url, 800, 620);
    }


    function CitasEditar(id_cita) {
        var url = "<?php echo base_url(); ?>agenda/citas/editar/" + id_cita;
        openwindow('citas_editar', url, 800, 620);
    }

    function Citas(id_empleado, fecha, hora) {
        var url = "<?php echo base_url(); ?>agenda/citas/nuevo/0/" + id_empleado + "/" + fecha + "/" + hora;
        openwindow('citas_0', url, 800, 620);
    }

    function HoraDentroHorario(hora_inicio, hora_fin, horarios) {
        console.log(hora_inicio, hora_fin, horarios, horarios.length)
        var sw_inicio = 0;
        var sw_fin = 0;
        // ... Comprobamos si la hora de inicio encaja.
        for (i = 0; i < horarios.length; i++) {
            var d1 = new Date("<?php echo date("Y/m/d"); ?> " + hora_inicio);
            var d2 = new Date("<?php echo date("Y/m/d"); ?> " + horarios[i].start);
            var d3 = new Date("<?php echo date("Y/m/d"); ?> " + horarios[i].end);
            var t1 = d1.getTime();
            var t2 = d2.getTime();
            var t3 = d3.getTime();

            if (t1 >= t2 && t1 <= t3) {
                sw_inicio = 1;
            }
        }
        // ... Comprobamos si la hora de fin encaja.
        for (i = 0; i < horarios.length; i++) {
            var d1 = new Date("<?php echo date("Y/m/d"); ?> " + hora_fin);
            var d2 = new Date("<?php echo date("Y/m/d"); ?> " + horarios[i].start);
            var d3 = new Date("<?php echo date("Y/m/d"); ?> " + horarios[i].end);
            var t1 = d1.getTime();
            var t2 = d2.getTime();
            var t3 = d3.getTime();
            if (t1 >= t2 && t1 <= t3) {
                sw_fin = 1;
            }
        }
        console.log("sw", sw_inicio, sw_fin);
        if (sw_inicio == 1 && sw_fin == 1) {
            return true;
        } else {
            return false;
        }
    }

    function NuevoDia() {
        document.location.href = "<?php echo base_url(); ?>agenda/index/" + document.getElementById("fecha").value;
    }

    function NuevoDiaFiltroCentro() {
        document.location.href = "<?php echo base_url(); ?>agenda/index/" + document.getElementById("fecha").value + "/" + document.getElementById("id_centro").value;
    }

    function FechaCompleta(fecha) {
        var meses = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        var diasSemana = new Array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
        var f = new Date(fecha);
        document.getElementById("fecha_completa").innerHTML = diasSemana[f.getDay()] + ", " + f.getDate() + " de " + meses[f.getMonth()] + " de " + f.getFullYear();
    }
    <?php if (isset($fecha)) { ?>
        FechaCompleta("<?php echo $fecha; ?>");
    <?php } ?>

    function NuevoServicio() {
        var url = "<?php echo base_url(); ?>agenda/citas/nuevo/0/0/<?= (isset($fecha)) ? $fecha : '' ?>/null/";
        openwindow('nuevo_servicio', url, 800, 620);
    }

    function NuevoProducto() {
        var url = "<?php echo base_url(); ?>productos/dietario/vender/";
        openwindow('nuevo_producto', url, 600, 650);
    }
    <?php /*
    function NuevoCarnet() {
        var posicion_x;
        var posicion_y;
        var ancho = 600;
        var alto = 500;
       var ventanaActual = window;
    var posicionVentanaActual = {
        x: ventanaActual.screenX || ventanaActual.screenLeft || 0,
        y: ventanaActual.screenY || ventanaActual.screenTop || 0
    };

    // Calcular la posición centrada en la pantalla activa
    posicion_x = posicionVentanaActual.x + (ventanaActual.innerWidth - ancho) / 2;
    posicion_y = posicionVentanaActual.y + (ventanaActual.innerHeight - alto) / 2;
        window.open("<?php echo base_url(); ?>carnets/gestion/nueva_venta/0/0/0", "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
    }

    function CarnetUnico() {
        var posicion_x;
        var posicion_y;
        var ancho = 600;
        var alto = 400;
       var ventanaActual = window;
    var posicionVentanaActual = {
        x: ventanaActual.screenX || ventanaActual.screenLeft || 0,
        y: ventanaActual.screenY || ventanaActual.screenTop || 0
    };

    // Calcular la posición centrada en la pantalla activa
    posicion_x = posicionVentanaActual.x + (ventanaActual.innerWidth - ancho) / 2;
    posicion_y = posicionVentanaActual.y + (ventanaActual.innerHeight - alto) / 2;
        window.open("<?php echo base_url(); ?>carnets/recargar_unico/", "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
    }
    */ ?>

    function CitasEspera() {
        var url = "<?php echo base_url(); ?>avisos/citas_espera_gestion/nuevo/0/0/<?= (isset($fecha)) ? $fecha : '' ?>/null/";
        openwindow('citas_espera', url, 600, 600);
    }

    function CitasEnSala(id_cita) {
        Swal.fire({
            text: "¿Deseas marcar la cita para que aparezca como que el paciente esta esperando en sala?",
            icon: "info",
            showCancelButton: true,
            confirmButtonText: 'Si, marcar',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return new Promise((resolve) => {
                    resolve();
                });
            },
        }).then((result) => {
            if (result.value) {
                var parametros = {
                    "id_cita": id_cita,
                };
                $.ajax({
                    data: parametros,
                    url: '<?php echo base_url(); ?>agenda/marcar_en_sala',
                    type: 'post',
                    beforeSend: function() {
                        $("#resultado").html("Procesando, espere por favor...");
                    },
                    success: function(response) {
                        Swal.fire({
                            text: "Cita en sala de espera",
                            willClose: function() {
                                location.reload(true);
                            }
                        });
                    }
                });
            } else {
                return;
            }
        });
    }

    function PagoCuenta() {
        var url = "<?php echo base_url(); ?>dietario/pago_a_cuenta";
        openwindow('pago_cuenta', url, 800, 450);
    }

    function Cobrar(id_cliente, fecha) {
        var url = "<?php echo base_url(); ?>dietario/ficha/ver/" + id_cliente + "/" + fecha;
        openwindow('pago_cuenta', url, 850, 600);
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

    <?php if ($this->session->userdata('id_perfil') == 1) { ?>

        function recargarPagina() {
            location.reload(true);
        }
        var intervaloRecarga = setInterval(recargarPagina, 600000);
    <?php } ?>
</script>