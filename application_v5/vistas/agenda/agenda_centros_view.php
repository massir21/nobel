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
        editable: false,
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
            {
                id: '<?php echo $row['id_usuario']; ?>',
                title: '<?php echo $row['nombre']." ".$row['apellidos']; ?>',
                eventColor: '#fad7e4',
                // ... Horarios disponibles para el empleado.
                businessHours: [
                <?php if ($horarios[$row['id_usuario']] != 0) { foreach ($horarios[$row['id_usuario']] as $key => $hora_trabajo) { ?>
                    {
                    // days of week. an array of zero-based day of week integers (0=Sunday)
                    dow: [ 0, 1, 2, 3, 4, 5, 6 ], // Todos los dias.
                    start: '<?php echo $hora_trabajo['hora_inicio']; ?>', // a start time
                    end: '<?php echo $hora_trabajo['hora_fin']; ?>', // an end time
                    },
                <?php }} ?>
                ],
            },
            <?php }} ?>
	],
        events: [
            <?php if ($citas_agenda != 0) { foreach ($citas_agenda as $key => $row) { ?>
            <?php $row['observaciones']=trim($row['observaciones']); ?>
            <?php $row['observaciones']=str_replace("\n","<br>",$row['observaciones']); ?>
            {
                id: <?php echo $row['id_cita']; ?>,
                resourceId: '<?php echo $row['id_usuario_empleado']; ?>',
				<?php /*// cita con el nombre del empleado
                // title: '<?php if ($row['solo_este_empleado']==1) { echo "<strong>"; } ?><?php echo strtoupper($row['cliente']); ?><?php if ($row['solo_este_empleado']==1) { echo " - </strong><br>"; } ?> \n <?php echo "<strong>".strtoupper($row['servicio'])."</strong> <span style=\'color: #000;\'>(".$row['empleado'].")</span><br>"; ?> \n <?php if ($row['observaciones']!='') { $row['observaciones']=preg_replace("/[\r\n|\n|\r]+/", " ", $row['observaciones']); echo addslashes(strtolower($row['observaciones'])); } ?>', */?>
			   // cita sin el nombre del empleado
			    title: '<?php if ($row['solo_este_empleado']==1) { echo "<strong>"; } ?><?=($row['cliente'] != '') ? strtoupper($row['cliente']) : ''; ?><?php if ($row['solo_este_empleado']==1) { echo " - </strong><br>"; } ?> \n <?= ($row['servicio'] != '') ?  "<strong>".strtoupper($row['servicio'])."</strong> <br>": "" ; ?> \n <?php if ($row['observaciones']!='') { $row['observaciones']=preg_replace("/[\r\n|\n|\r]+/", " ", $row['observaciones']); echo addslashes(strtolower($row['observaciones'])); } ?>',
                start: '<?php echo $row['fecha_inicio_aaaammdd']."T".$row['hora_inicio']; ?>',
                end: '<?php echo $row['fecha_inicio_aaaammdd']."T".$row['hora_fin']; ?>',
                <?php if ($row['color_servicio']=="") { ?>
                color: '#fad7e4',
                <?php } else { ?>
                color: '<?php echo $row['color_servicio'] ?>',
                <?php } ?>
                <?php if ($row['estado']=="Programada") { ?>
                textColor: '#000',
                <?php } else { ?>
                textColor: '#b90400',
                <?php } ?>
               <?php /*//tip: '<?php echo "Empleado: ".$row['empleado']."\\n Cliente: ".strtoupper($row['cliente']); ?> \n <?php echo strtoupper($row['servicio']); ?> \n <?php if ($row['observaciones']!='') { echo addslashes(strtolower($row['observaciones'])); } ?>', */ ?>
                tip: '<?php echo strtoupper($row['cliente']); ?>  <?php if ($row['observaciones']!='') { echo addslashes(strtolower(": ".$row['observaciones'])); } ?>',
                <?php if ($row['solo_este_empleado']==1 || $row['estado']=="Finalizado") { ?>
                resourceEditable: false,
                <?php } ?>
                editable: false,
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
    });
});
</script>
<?php } ?>
<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title">
            <h3 class="w-100">Otros Centros (Solo Consulta):<br><span id="fecha_completa"></span></h3>
        </div>
        <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
            <div class="w-auto">
                    <label for="" class="form-label">Fecha</label>
                    <input type="date" id="fecha" name="fecha" value="<?php if (isset($fecha)) { echo $fecha; } ?>" id="datepicker" class="form-control form-control-solid w-auto" placeholder="Fecha" onchange="FechaCompleta(this.value); <?php if ($this->session->userdata('id_perfil') == 0) { ?>NuevoDiaFiltroCentro();<?php } else { ?>NuevoDia();<?php } ?>"; required />
            </div>
            <div class="w-auto ms-3">
                <label for="" class="form-label">Centro:</label>
                <select name="id_centro" id="id_centro" class="form-select form-select-solid w-auto" onchange="NuevoDiaFiltroCentro();">
                    <?php if (isset($centros_todos)) { if ($centros_todos != 0) { foreach ($centros_todos as $key => $row) { if ($row['id_centro'] > 1) { ?>
                    <option value='<?php echo $row['id_centro']; ?>' <?php if (isset($id_centro)) { if ($row['id_centro']==$id_centro) { echo "selected"; } } ?>>
                        <?php echo $row['nombre_centro']; ?>
                    </option>
                <?php }}}} ?>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body pt-6">
        <div id="agenda"></div>
    </div>
</div>
<script>
    function NuevoDia() {
        document.location.href="<?php echo base_url();?>agenda/centros/"+document.getElementById("fecha").value+"/"+document.getElementById("id_centro").value;
    }
    function NuevoDiaFiltroCentro() {
        document.location.href="<?php echo base_url();?>agenda/centros/"+document.getElementById("fecha").value+"/"+document.getElementById("id_centro").value;
    }
    function FechaCompleta(fecha) {
        var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        var diasSemana = new Array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
        var f = new Date(fecha);
        document.getElementById("fecha_completa").innerHTML=diasSemana[f.getDay()] + ", " + f.getDate() + " de " + meses[f.getMonth()] + " de " + f.getFullYear();
    }
    <?php if (isset($fecha)) { ?>
        FechaCompleta("<?php echo $fecha; ?>");
    <?php } ?>
</script>
