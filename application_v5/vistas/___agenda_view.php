<link href='<?php echo base_url();?>recursos/fullcalendar/fullcalendar.min.css' rel='stylesheet' />
<link href='<?php echo base_url();?>recursos/fullcalendar/fullcalendar.print.min.css' rel='stylesheet' media='print' />
<link href='<?php echo base_url();?>recursos/fullcalendar/scheduler.min.css' rel='stylesheet' />
<script src='<?php echo base_url();?>recursos/fullcalendar/moment.min.js'></script>
<script src='<?php echo base_url();?>recursos/fullcalendar/jquery-2.2.4.min.js'></script>
<script src='<?php echo base_url();?>recursos/fullcalendar/fullcalendar.min.js'></script>
<script src='<?php echo base_url();?>recursos/fullcalendar/scheduler.min.js'></script>
<script src="<?php echo base_url();?>recursos/fullcalendar/es.js"></script>
<style>
.fc-bgevent {
        opacity: 0.7;
}
.fc-slats {
        background: #e0ffd4;
}
.fc-toolbar.fc-header-toolbar {
        display: none;
}
.fc-title {
        font-size: 11px;
}
.page-content-wrapper .page-content {
        margin-top: 0px !important;
}
.fc-slats tr {
        height: 45px;
}
</style>
<?php if (isset($citas_agenda)) { ?>
<script>
$(document).ready(function() {
        $('#agenda').fullCalendar({
                //
                // ... Propiedades
                //
                schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
                locale: 'es',
                timeFormat: 'H:mm', // uppercase H for 24-hour clock
                slotLabelFormat : 'H:mm',
                allDaySlot: false,
                defaultDate: '<?php echo $fecha; ?>',
                defaultView: 'agendaDay',
                editable: true,
                minTime: "10:00:00",
                maxTime: "22:45:00",
                slotDuration: "00:15:00",
                //
                // ... Formato cabecera
                //
                header: {
                        //left: 'prev,next week',
                        left: '',
                        //center: 'title',
                        center: '',
                        right: ''
                        //right: 'agendaWeek,agendaDay'
                        //right: 'month,agendaWeek,agendaDay'
                },
                views: {
                        agendaTwoDay: {
                                type: 'agenda',
                                duration: { days: 1 },
                                // views that are more than a day will NOT do this behavior by default
                                // so, we need to explicitly enable it
                                groupByResource: true
                                //// uncomment this line to group by day FIRST with resources underneath
                                //groupByDateAndResource: true
                        },
                },
                //
                // ... Informacion de las citas
                //
                resources: [
                        <?php if ($empleados != 0) { foreach ($empleados as $key => $row) { ?>
                        <?="{"?>
                                id: '<?php echo $row['id_usuario']; ?>',
                                title: '<?php echo $row['nombre']." ".$row['apellidos']; ?>',
                                eventColor: '#fad7e4',
                                // ... Horarios disponibles para el empleado.
                                <?="businessHours: ["?>
                                <?php if ($horarios[$row['id_usuario']] != 0) { foreach ($horarios[$row['id_usuario']] as $key => $hora_trabajo) { ?>
                                        {
                                        // days of week. an array of zero-based day of week integers (0=Sunday)
                                        dow: [ 0, 1, 2, 3, 4, 5, 6 ], // Todos los dias.
                                        start: '<?php echo $hora_trabajo['hora_inicio']; ?>', // a start time
                                        end: '<?php echo $hora_trabajo['hora_fin']; ?>', // an end time
                                        },
                                <?php }} ?>
                                <?="],"?>
                        <?="},"?>
                        <?php }} ?>
	],
                events: [
                        <?php if ($citas_agenda != 0) { foreach ($citas_agenda as $key => $row) { ?>
                        <?php $row['observaciones']=trim($row['observaciones']); ?>
                        <?php $row['observaciones']=preg_replace("/[\r\n|\n|\r]+/", " ", $row['observaciones']); ?>
                        <?php $online=""; if ($row['id_usuario_creador']==0) { $online='<img src="'.base_url().'recursos/images/online.png"> '; } ?>
                        <?php $firma=""; if ($row['existe_firma']==0 && $row['no_quiere_publicidad']==0) { $firma='<i class="fa fa-pencil" aria-hidden="true" style="color: red;"></i> '; } ?>
                        <?php $recordatorio_sms=""; $recordatorio_email=""; ?>
                        <?php if ($row['recordatorio_sms']==1) { $recordatorio_sms="(SMS)"; } ?>
                        <?php if ($row['recordatorio_email']==1) { $recordatorio_email="(Email)"; } ?>
                        {
                                id: <?php echo $row['id_cita']; ?>,
                                estado:'<?php echo $row['estado']; ?>',
                                resourceId: '<?php echo $row['id_usuario_empleado']; ?>',
                                perfil: <?php echo $this->session->userdata('id_perfil');?>,
				// cita con el nombre del empleado
                                // title: '<?php if ($row['solo_este_empleado']==1) { echo "<strong>"; } ?><?php echo strtoupper($row['cliente']); ?><?php if ($row['solo_este_empleado']==1) { echo " - </strong><br>"; } ?> \n <?php echo "<strong>".strtoupper($row['servicio'])."</strong> <span style=\'color: #000;\'>(".$row['empleado'].")</span><br>"; ?> \n <?php if ($row['observaciones']!='') { echo addslashes(strtolower($row['observaciones'])); } ?>',
			     // cita sin el nombre del empleado
		title: '<?php echo $online; echo $firma; if ($row['solo_este_empleado']==1) { echo "<strong>"; } ?><?php echo strtoupper($row['cliente']); ?><?php if ($row['solo_este_empleado']==1) { echo " - </strong><br>"; } ?> \n <?php echo "<strong>".strtoupper($row['servicio'])."</strong> <br>"; ?> \n <?php if ($row['observaciones']!='') { echo addslashes(strtolower($row['observaciones'])); } ?> <?php echo $recordatorio_sms; ?> <?php echo $recordatorio_email; ?>',
                                start: '<?php echo $row['fecha_inicio_aaaammdd']."T".$row['hora_inicio']; ?>',
                                end: '<?php echo $row['fecha_inicio_aaaammdd']."T".$row['hora_fin']; ?>',
                                <?php echo ($row['color_servicio']=="") ? "color: '#fad7e4'," : "color: '".$row['color_servicio']."',";?>
                                <?php echo ($row['estado']=="Programada") ? "textColor: '#000'," : "textColor: '#b90400',";?>
                                tip: '<?php echo strtoupper($row['cliente']); ?>    <?php if ($row['observaciones']!='') { echo addslashes(strtolower(": ".$row['observaciones'])); } ?>',
                                <?php echo ($row['solo_este_empleado']==1 || $row['estado']=="Finalizado") ? "resourceEditable: false,":''; ?>
                                <?php echo ($row['estado']=="Programada" && $this->session->userdata('id_perfil') != 1) ? 'editable: true,': 'editable: true,'; ?>
                                overlap: true,
                        },
                        <?php }} ?>
	],
                //
                // ... Control de eventos
                //
                eventRender: function(event, element) {
                        element.attr('title', event.tip);
                        var $title = element.find( '.fc-title' );
                        $title.html( $title.text() );
                },
                // ... Si el usuario no es empleado si se activa el click, sino NO.
                <?php if ($this->session->userdata('id_perfil') != 1) { ?>
                eventClick: function(calEvent, jsEvent, view) {
                        if (calEvent.editable) {
                                CitasEditar(calEvent.id);
                        }
                        else {
                                return false;
                        }
                        // change the border color just for fun
                        //$(this).css('border-color', 'red');
                },
                dayClick: function(date, jsEvent, view, resourceObj) {
                        var hora_click = date.format().substr(11, 5);
                        var r = HoraDentroHorario(hora_click,hora_click,resourceObj.businessHours);
                        if (r) {
                                var fecha = date.format().substr(0, 10);
                                var hora = hora_click.replace(":", "-");
                                Citas(resourceObj.id,fecha,hora);
                        }
                        else {
                                return false;
                        }
                },
                <?php } ?>
                eventResize: function(event, delta, revertFunc) {
                        var myResource = $('#agenda').fullCalendar('getResourceById', event.resourceId);
                        hora_inicio = event.start.format().substr(11, 5);
                        hora_fin = event.end.format().substr(11, 5);
                        var r = HoraDentroHorario(hora_inicio,hora_fin,myResource.businessHours);
                        console.log("Hola");
                        if (r) {
                                console.log("Hola que tal");
                                if (!confirm("¿DESEAS CONFIRMAR EL CAMBIO DE DURACIÓN DE LA CITA?")) {
                                        revertFunc();
                                }
                                else {
                                        var parametros = {
                                                "id_cita" : event.id,
                                                "fecha_fin_nueva" : event.end.format(),
                                        };
                                        $.ajax({
                                                data: parametros,
                                                url: '<?php echo base_url();?>agenda/modificar_duracion',
                                                type: 'post',
                                                beforeSend: function () {
                                                        $("#resultado").html("Procesando, espere por favor...");
                                                },
                                                success:    function (response) {
                                                        $("#resultado").html(response);
                                                }
                                        });
                                }
                        }
                        else {
                                revertFunc();
                        }
                },
                eventDrop: function(event, delta, revertFunc) {
                        if(event.estado=="Finalizado" || event.perfil==1)        {
                                revertFunc();
                                return false;
                        }
                        var myResource = $('#agenda').fullCalendar('getResourceById', event.resourceId);
                        hora_inicio = event.start.format().substr(11, 5);
                        hora_fin = event.end.format().substr(11, 5);
                        var r = HoraDentroHorario(hora_inicio,hora_fin,myResource.businessHours);
                        var parametros = {
                                "id_cita" : event.id,
                                "id_empleado_nuevo" : event.resourceId,
                                "fecha_inicio_nueva" : event.start.format(),
                        };
                        puede_capacidad = 0;
                        if (r) {
                                // ... Comprobamos si el empleado de destino puede hacer el servicio.
                                $.ajax({
                                        data: parametros,
                                        url: '<?php echo base_url();?>agenda/comprobar_capacidad_empleado',
                                        type: 'post',
                                        beforeSend: function () {
                                                $("#resultado").html("Procesando, espere por favor...");
                                        },
                                        success:    function (response) {
                                                puede_capacidad = response;
                                                if (puede_capacidad==0) {
                                                        alert("NO SE PUEDE MOVER LA CITA AL EMPLEADO INDICADO, PORQUE NO TIENE LA CAPACIDAD DE DAR EL SERVICIO.");
                                                        revertFunc();
                                                }
                                                else {
                                                        console.log("Hola que tal 2");
                                                        if (!confirm("¿DESEAS CONFIRMAR EL CAMBIOS DE LA CITA?")) {
                                                                revertFunc();
                                                        }
                                                        else {
                                                                $.ajax({
                                                                        data: parametros,
                                                                        url: '<?php echo base_url();?>agenda/modificar_empleado_fecha',
                                                                        type: 'post',
                                                                        beforeSend: function () {
                                                                                $("#resultado").html("Procesando, espere por favor...");
                                                                        },
                                                                        success:    function (response) {
                                                                                $("#resultado").html(response);
                                                                        }
                                                                });
                                                        }
                                                }
                                                $("#resultado").html("");
                                        }
                                });
                        }
                        else {
                                revertFunc();
                        }
                }
        });
});
</script>
<?php } ?>
<div class="page-content" style="padding: 5px !important;">
        <!-- BEGIN PAGE HEADER-->
        <!-- END PAGE BAR -->
        <!-- END PAGE HEADER-->
<div class="row" style="padding: 5px !important;">
<div class="col-md-12" style="padding: 5px !important;">
    <!-- BEGIN SAMPLE FORM PORTLET-->
    <div class="portlet light bordered" style="padding: 5px; border: 0px !important;">
        <div class="portlet-title" style="border: none; min-height: 30px;">
                <div style="float: left;">
                        <input type="date" id="fecha" name="fecha" value="<?php if (isset($fecha)) { echo $fecha; } ?>" id="datepicker" class="form-control form-control-solid" placeholder="Fecha" style="width: 190px; font-weight: bold; font-size: 18px;" onchange="FechaCompleta(this.value); <?php if ($this->session->userdata('id_perfil') == 0) { ?>NuevoDiaFiltroCentro();<?php } else { ?>NuevoDia();<?php } ?>"; required />
                </div>
                <div style="float: left;">
                        <input type="text" id="fecha_completa" value="" class="form-control form-control-solid" style="width: 300px; font-weight: bold; font-size: 14px; border: 0px; background-color: #fff;" />
                </div>
                <div style="float: left;">
                    <button class="btn btn-primary text-inverse-primary" onclick="NuevoServicio();">
                        Servicio
                    </button>
                    <button class="btn btn-info text-inverse-info" onclick="NuevoProducto();">
                        Producto
                    </button>
                    <!-- 17/04/20 -->
                    <button class="btn btn-warning text-inverse-warning" style="color: white; background-color: #556B2F;" onclick="CarnetUnico();">
                        Carnet Único
                    </button>
                    <!-- Fin -->
                    <button class="btn btn-success text-inverse-success" onclick="NuevoCarnet();">
                        Carnet
                    </button>
                    <button class="btn btn-secondary text-inverse-secondary" onclick="CitasEspera();">
                        Cita Espera
                    </button>
             </div>
                <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                <div style="float: right;">
                        <select name="id_centro" id="id_centro" class="form-control form-control-solid" onchange="NuevoDiaFiltroCentro();" style="width: 140px;">
                                <?php if (isset($centros_todos)) { if ($centros_todos != 0) { foreach ($centros_todos as $key => $row) { if ($row['id_centro'] > 1) { ?>
                                        <option value='<?php echo $row['id_centro']; ?>' <?php if (isset($id_centro)) { if ($row['id_centro']==$id_centro) { echo "selected"; } } ?>>
                                                <?php echo $row['nombre_centro']; ?>
                                        </option>
                                <?php }}}} ?>
                        </select>
                </div>
                <?php } ?>
        </div>
        <div class="portlet-body" style="padding-top: 0px;">
                <div id="agenda"></div>
        </div>
    </div>
    <!-- END SAMPLE FORM PORTLET-->
</div>
</div>
</div>
<!-- END CONTENT BODY -->
<script>
        function Citas() {
                var posicion_x;
                var posicion_y;
                var ancho=600;
                var alto=700;
                posicion_x=(screen.width/2)-(ancho/2);
                posicion_y=(screen.height/2)-(alto/2);
                window.open("<?php echo base_url();?>agenda/citas/nuevo/", "_blank", "toolbar=no,scrollbars=no,resizable=no,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);
        }
         //17/04/20
        function CarnetUnico() {
                var posicion_x; 
                var posicion_y;
                var ancho=600;
                var alto=800;
                posicion_x=(screen.width/2)-(ancho/2);
                posicion_y=(screen.height/2)-(alto/2);
                //window.open("<?php echo base_url();?>carnets/gestion/recarga_unico/0/0/0", "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);
                window.open("<?php echo base_url();?>carnets/recargar_unico/", "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);
        }
        //Fin
        function CitasEditar(id_cita) {
                var posicion_x;
                var posicion_y;
                var ancho=600;
                var alto=700;
                posicion_x=(screen.width/2)-(ancho/2);
                posicion_y=(screen.height/2)-(alto/2);
                window.open("<?php echo base_url();?>agenda/citas/editar/"+id_cita, "_blank", "toolbar=no,scrollbars=no,resizable=no,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);
        }
        function Citas(id_empleado,fecha,hora) {
                var posicion_x;
                var posicion_y;
                var ancho=600;
                var alto=700;
                posicion_x=(screen.width/2)-(ancho/2);
                posicion_y=(screen.height/2)-(alto/2);
                hora = hora.replace(":", "-");
                window.open("<?php echo base_url();?>agenda/citas/nuevo/0/"+id_empleado+"/"+fecha+"/"+hora, "_blank", "toolbar=no,scrollbars=no,resizable=no,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);
        }
        function HoraDentroHorario(hora_inicio,hora_fin,horarios) {
                var sw_inicio=0;
                var sw_fin=0;
                // ... Comprobamos si la hora de inicio encaja.
                for (i=0; i<horarios.length; i++) {
                        var d1 = new Date("<?php echo date("Y/m/d"); ?> " + hora_inicio);
                        var d2 = new Date("<?php echo date("Y/m/d"); ?> " + horarios[i].start);
                        var d3 = new Date("<?php echo date("Y/m/d"); ?> " + horarios[i].end);
                        var t1 = d1.getTime();
                        var t2 = d2.getTime();
                        var t3 = d3.getTime();
                        if (t1 >= t2 && t1 <= t3) {
                                sw_inicio=1;
                        }
                }
                // ... Comprobamos si la hora de fin encaja.
                for (i=0; i<horarios.length; i++) {
                        var d1 = new Date("<?php echo date("Y/m/d"); ?> " + hora_fin);
                        var d2 = new Date("<?php echo date("Y/m/d"); ?> " + horarios[i].start);
                        var d3 = new Date("<?php echo date("Y/m/d"); ?> " + horarios[i].end);
                        var t1 = d1.getTime();
                        var t2 = d2.getTime();
                        var t3 = d3.getTime();
                        if (t1 >= t2 && t1 <= t3) {
                                sw_fin=1;
                        }
                }
                console.log("sw", sw_inicio, sw_fin);
                if (sw_inicio==1 && sw_fin==1) {
                        return true;
                }
                else {
                        return false;
                }
        }
        function NuevoDia() {
                document.location.href="<?php echo base_url();?>agenda/index/"+document.getElementById("fecha").value;
        }
        function NuevoDiaFiltroCentro() {
                document.location.href="<?php echo base_url();?>agenda/index/"+document.getElementById("fecha").value+"/"+document.getElementById("id_centro").value;
        }
        function FechaCompleta(fecha) {
                var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                var diasSemana = new Array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
                var f = new Date(fecha);
                document.getElementById("fecha_completa").value=diasSemana[f.getDay()] + ", " + f.getDate() + " de " + meses[f.getMonth()] + " de " + f.getFullYear();
        }
        <?php if (isset($fecha)) { ?>
                FechaCompleta("<?php echo $fecha; ?>");
        <?php } ?>
        function NuevoServicio() {
                var posicion_x;
                var posicion_y;
                var ancho=600;
                var alto=580;
                posicion_x=(screen.width/2)-(ancho/2);
                posicion_y=(screen.height/2)-(alto/2);
                window.open("<?php echo base_url();?>agenda/citas/nuevo/0/0/<?php if (isset($fecha)) { echo $fecha; } ?>/null/", "_blank", "toolbar=no,scrollbars=no,resizable=no,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);
        }
        function NuevoProducto() {
                var posicion_x;
                var posicion_y;
                var ancho=600;
                var alto=500;
                posicion_x=(screen.width/2)-(ancho/2);
                posicion_y=(screen.height/2)-(alto/2);
                window.open("<?php echo base_url();?>productos/dietario/vender/", "_blank", "toolbar=no,scrollbars=no,resizable=no,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);
        }
        function NuevoCarnet() {
                var posicion_x;
                var posicion_y;
                var ancho=600;
                var alto=800;
                posicion_x=(screen.width/2)-(ancho/2);
                posicion_y=(screen.height/2)-(alto/2);
                window.open("<?php echo base_url();?>carnets/gestion/nueva_venta/0/0/0", "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);
        }
        function CitasEspera() {
                var posicion_x;
                var posicion_y;
                var ancho=600;
                var alto=700;
                posicion_x=(screen.width/2)-(ancho/2);
                posicion_y=(screen.height/2)-(alto/2);
                window.open("<?php echo base_url();?>avisos/citas_espera_gestion/nuevo/0/0/<?php if (isset($fecha)) { echo $fecha; } ?>/null/", "_blank", "toolbar=no,scrollbars=no,resizable=no,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);
        }
</script>
