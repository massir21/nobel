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
?><?php if (isset($citas_agenda)) { ?>
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
			background-color: #dadada;
			opacity: .5;
			background: repeating-linear-gradient(-45deg, #fdfdfd, #fdfdfd 6.5px, #dadada 6.5px, #dadada 32.5px) !important;
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
				slotMaxTime: "20:45",
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
                            $servicio = (isset($row['servicio']) && $row['servicio'] !== null) ? "<strong class=\"d-block\">" . strtoupper($row['servicio']) . $dientes . "</strong>" : "";
                            $servicioTip = (isset($row['servicio']) && $row['servicio'] !== null) ?  strtoupper($row['servicio']) . $dientes : "";

                            $titleevent = $time . $online . $firma . $solo_este_empleado . $servicio . $observaciones . $recordatorio_sms . " " . $recordatorio_email;

                            $colorservicio = ($row['id_presupuesto'] == '') ? '#96BE25' : (($row['color_servicio'] == "") ? "#fad7e4" : $row['color_servicio']);

                            $textColor = ($row['estado'] == "Programada") ? "#000" : '#b90400';
                            $tip = strtoupper($row['cliente']) . ' ' . $servicioTip . $observaciones . $recordatorio_sms . " " . $recordatorio_email;
                            $resourceEditable = ($row['solo_este_empleado'] == 1 || $row['estado'] == "Finalizado") ? 'false' : 'true';

                            $editable = ($row['estado'] == "Programada" && $this->session->userdata('id_perfil') != 1) ? 'true' : 'false';
                            $bordercolor = 'red'; // ($row['saldo'] == 0) ? 'red' : $colorservicio;
                            if ($row['id_servicio'] == 15460 || $row['id_servicio'] == 15404) {
                                $colorservicio = $row['color_servicio'];
                                $bordercolor = $row['color_servicio'] . ' !important';
                            }

                    ?> {
                                id: <?php echo $row['id_cita']; ?>,
                                estado: '<?php echo $row['estado']; ?>',
                                resourceId: '<?php echo $row['id_usuario_empleado']; ?>',
                                //perfil: <?php echo $this->session->userdata('id_perfil'); ?>,
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
                },
                eventContent: function (info) {
                    return {
                        html: info.event.title
                    };
                },
				eventClick: function (info) {
                    closetooltips()
                    var eventObj = info.event;
                    if (eventObj.editable) {
                        FichaCliente(info.event.extendedProps.id_cliente);
                    } else {
                        FichaCliente(info.event.extendedProps.id_cliente);
                        return false;
                    }
                },
				eventClassNames: function(info) {
                    var clases = [];
                    if (info.event.extendedProps.sinsaldo == 0) {
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
			<?php /*
            <button class="btn btn-primary text-inverse-primary" onclick="NuevoServicio();">Servicio</button>
            
            <button class="btn btn-info text-inverse-info ms-3" onclick="NuevoProducto();">Producto</button>
            
            <button class="btn btn-warning text-inverse-warning ms-3" onclick="CarnetUnico();"> Carnet Único</button>
            <button class="btn btn-success text-inverse-success ms-3" onclick="NuevoCarnet();"> Carnet</button>
            */ ?>
			<button class="btn btn-secondary text-inverse-secondary ms-3" onclick="CitasEspera();">Cita Espera</button>
		</div>
	</div>
	<div class="card-body pt-6">
		<div id="agenda"></div>
	</div>
</div>
<script>
	function closetooltips() {
		document.querySelectorAll('.tooltip').forEach(function(element) {
			element.remove();
		});
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

	function CitasEspera() {
		var posicion_x;
		var posicion_y;
		var ancho = 600;
		var alto = 600;
		posicion_x = (screen.width / 2) - (ancho / 2);
		posicion_y = (screen.height / 2) - (alto / 2);
		window.open("<?php echo base_url(); ?>avisos/citas_espera_gestion/nuevo/0/0/<?= (isset($fecha)) ? $fecha : '' ?>/null/", "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
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

	function recargarPagina() {
		location.reload(true);
	}
	var intervaloRecarga = setInterval(recargarPagina, 600000);
</script>