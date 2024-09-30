<style>
    #contenedor {
        overflow: hidden;
    }
    #horarios {
        float: left;
        margin-right: 5px;
    }
    #horarios .horario {
        overflow: hidden;
        height: 40px;
        padding-top: 10px;
        border-bottom: 1px solid #ddd;
    }
    .empleado {
        float: left;
        width: 142px;
        margin-left: 7.6px;
    }
    .cita {
        overflow: hidden;
        height: 40px;
        font-size: 11px;
        border-bottom: 1px solid #ddd;
    }
</style>
<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title">
            <h3 class="w-100">Citas por Empleados a fecha:<br><span id="fecha_completa"></span></h3>
        </div>
        <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
            <div class="w-auto">
                    <label for="" class="form-label">Fecha</label>
                    <input type="date" id="fecha" name="fecha" value="<?php if (isset($fecha)) { echo $fecha; } ?>" id="datepicker" class="form-control form-control-solid w-auto" placeholder="Fecha" onchange="FechaCompleta(this.value); <?php if ($this->session->userdata('id_perfil') == 0) { ?>NuevoDiaFiltroCentro();<?php } else { ?>NuevoDia();<?php } ?>"; required />
            </div>
            <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                <div class="w-auto ms-3">
                    <label for="" class="form-label">Centro filtrado</label>
                    <select name="id_centro" id="id_centro" class="form-select form-select-solid w-auto" onchange="NuevoDiaFiltroCentro();">
                        <option value="">Todos</option>
                        <?php if (isset($centros_todos)) { if ($centros_todos != 0) { foreach ($centros_todos as $key => $row) { if ($row['id_centro'] > 1) { ?>
                            <option value='<?php echo $row['id_centro']; ?>' <?php if (isset($id_centro)) { if ($row['id_centro']==$id_centro) { echo "selected"; } } ?>>
                                <?php echo $row['nombre_centro']; ?>
                            </option>
                        <?php }}}} ?>
                    </select>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php if ($empleados != 0) {
        $d=0;  $d=count($empleados)*175; if ($d==175) { $d=200; } ?>
        <div class="card-body pt-6">
            <div id="contenedor" <?php if ($d>0) { ?>style="width: <?php echo $d; ?>px !important;"<?php } ?>>
                <!-- -------------------------------------------------- -->
                <!-- Cabecera con los nombre de los empleados
                <!-- -------------------------------------------------- -->
                <div style="float: left; width: 40px;">&nbsp;</div>
                <?php if ($empleados != 0) { foreach ($empleados as $key => $row) { ?>
                    <div class="badge bg-dark justify-content-center text-white" style="float: left; height: 50px; width: 142px; margin-left: 7.6px; padding: 5px;"><?php echo $row['nombre']." ".$row['apellidos']; ?></div>
                <?php }} ?>
                <div style="clear: both;"></div>
                <!-- -------------------------------------------------- -->
                <!-- Bucle para mostrar cada uno de los tramos horarios -->
                <!-- -------------------------------------------------- -->
                <div id="horarios">
                    <?php if ($horas != 0) { foreach ($horas as $key => $row) { ?>
                        <div class="bg-body border-bottom border-bottom-4 h-40px w-100 d-flex align-items-center"><?php echo $row['horario']; ?></div>
                    <?php }} ?>
                </div>
                <!-- -------------------------------------------------- -->
                <!-- Bucle Mostrar cada uno de los empleados -->
                <!-- -------------------------------------------------- -->
                <?php if ($empleados != 0) { foreach ($empleados as $key => $empleado) { $sw=0; ?>
                <div class="empleado">
                    <?php if ($horas != 0) { for ($i=0; $i<count($horas); $i++) { ?>
                        <?php if ($citas[$empleado['id_usuario']] != 0) { foreach ($citas[$empleado['id_usuario']] as $key => $row) { ?>
                            <?php $sw=0; ?>
                            <?php if ($horas[$i]['horario'] == $row['hora_inicio']) { ?>
                                <?php $sw=1; ?>
                            <?php } ?>
                            <?php if ($sw==1) { $duracion=($row['duracion']/15); if ($duracion<1) { $duracion=1; } ?>
                            <div class="cita" id="servicioid_<?php echo $row['id_servicio']; ?>" style="background: #fad7e4; color: #000; padding: 5px; height: <?php echo (intval($duracion)*40); ?>px !important;">
                                <?php if ($row['estado']=="Programada") { ?>
                                <a href="#" onclick="CitasEditar(<?php echo $row['id_cita']; ?>);" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="<?php echo $empleado['nombre']." ".$empleado['apellidos']; ?> - <?php if ($row['observaciones']!='') { echo $row['observaciones']; } else { echo 'Editar cita...'; } ?>" style="color: #000; <?php if ($row['solo_este_empleado']==1) { echo "font-weight: bold;"; } ?>">
                                <?php echo strtoupper($row['cliente']); ?>
                                - <?php echo strtoupper($row['servicio']); ?>
                                </a>
                                <?php if ($row['observaciones']!='') { echo "<br>".$row['observaciones']; } ?>
                                <?php } else { ?>
                                    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="<?php echo $empleado['nombre']." ".$empleado['apellidos']; ?> - <?php if ($row['observaciones']!='') { echo $row['observaciones']." - ESTA CITA YA NO SE PUEDE MODIFICAR"; } else { echo 'ESTA CITA YA NO SE PUEDE MODIFICAR'; } ?>" style="color: #b90400; cursor: pointer; <?php if ($row['solo_este_empleado']==1) { echo "font-weight: bold;"; } ?>">
                                    <?php echo strtoupper($row['cliente']); ?>
                                    - <?php echo strtoupper($row['servicio']); ?>
                                    <?php if ($row['observaciones']!='') { echo "<br>".$row['observaciones']; } ?>
                                    </span>
                                <?php } ?>
                                - <?php echo "<span style='font-size: 10px;'>(".$empleado['nombre'].")</span>"; ?>
                            </div>
                            <?php $i=$i+($duracion-1); break; } ?>
                        <?php }} ?>
                        <?php if ($sw==0) { ?>
                            <?php if ($horarios[$empleado['id_usuario']] != 0) { foreach ($horarios[$empleado['id_usuario']] as $key => $hora_trabajo) { ?>
                                <?php $sw_horas=0; ?>
                                <?php if ($horas[$i]['horario'] == $hora_trabajo) { ?>
                                    <?php $sw_horas=1; ?>
                                <?php } ?>
                                <?php if ($sw_horas==1) { break; } ?>
                            <?php }} ?>
                            <?php if ($sw_horas==0) { ?>
                            <div class="bg-body border-bottom border-bottom-4 h-40px w-100">
                            </div>
                            <?php } else { ?>
                                <?php if (1==1) {  ?>
                                <?php //if (strtotime($horas[$i]['horario']) >= strtotime(date("H:i")) || date("Y-m-d") != $fecha) { ?>
                                <a href="#" onclick="Citas(<?php echo $empleado['id_usuario']; ?>,'<?php echo $fecha; ?>','<?php echo $horas[$i]['horario']; ?>');" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Añadir nueva cita" class="btn btn-outline btn-outline-default btn-light d-block h-40px">
                                <i class="fa-sharp fa-solid fa-circle-plus fw-bold fs-3"></i> <?=$empleado['nombre']?>
                                </a>
                                <?php } else { ?>
                                <div class="bg-body border-bottom-2 h-40px w-100">
                                </div>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <?php }} ?>
                </div>
                <?php }} ?>
                <!-- -------------------------------------------------- -->
                <!-- Cabecera con los nombre de los empleados
                <!-- -------------------------------------------------- -->
                <div style="clear: both;"></div>
                <div style="float: left; width: 40px;">&nbsp;</div>
                <?php if ($empleados != 0) { foreach ($empleados as $key => $row) { ?>
                    <div class="badge bg-dark justify-content-center text-white" style="float: left; height: 50px; width: 142px; margin-left: 7.6px; padding: 5px;"><?php echo $row['nombre']." ".$row['apellidos']; ?></div>
                <?php }} ?>
                <div style="clear: both;"></div>
            </div>
        </div>
    <?php } ?>
</div>
<!-- END CONTENT BODY -->
<script>
    function Citas(id_empleado,fecha,hora) {
        var posicion_x;
        var posicion_y;
        var ancho=600;
        var alto=600;
        posicion_x=(screen.width/2)-(ancho/2);
        posicion_y=(screen.height/2)-(alto/2);
        hora = hora.replace(":", "-");
        window.open("<?php echo base_url()?>agenda/citas/nuevo/0/"+id_empleado+"/"+fecha+"/"+hora, "_blank", "toolbar=no,scrollbars=no,resizable=no,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);
    }
    function CitasEditar(id_cita) {
        var posicion_x;
        var posicion_y;
        var ancho=600;
        var alto=600;
        posicion_x=(screen.width/2)-(ancho/2);
        posicion_y=(screen.height/2)-(alto/2);
        window.open("<?php echo base_url()?>agenda/citas/editar/"+id_cita, "_blank", "toolbar=no,scrollbars=no,resizable=no,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);
    }
    function NuevoDia() {
        document.location.href="<?php echo base_url()?>agenda/empleados/"+document.getElementById("fecha").value;
    }
    function NuevoDiaFiltroCentro() {
        document.location.href="<?php echo base_url()?>agenda/empleados/"+document.getElementById("fecha").value+"/"+document.getElementById("id_centro").value;
    }
    function FichaCliente(id_cliente) {
        var posicion_x;
        var posicion_y;
        var ancho=800;
        var alto=600;
        posicion_x=(screen.width/2)-(ancho/2);
        posicion_y=(screen.height/2)-(alto/2);
        window.open("<?php echo base_url()?>dietario/ficha/ver/"+id_cliente+"/<?php if (isset($fecha)) { echo $fecha; } ?>", "_blank", "toolbar=no,scrollbars=no,resizable=no,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);
    }
    function FechaCompleta(fecha) {
        var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        var diasSemana = new Array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
        var f = new Date(fecha);
        document.getElementById("fecha_completa").innerHTML =diasSemana[f.getDay()] + ", " + f.getDate() + " de " + meses[f.getMonth()] + " de " + f.getFullYear();
    }
    <?php if (isset($fecha)) { ?>
        FechaCompleta("<?php echo $fecha; ?>");
    <?php } ?>

    function recargarPagina() {
		location.reload(true);
	}
	var intervaloRecarga = setInterval(recargarPagina, 600000);
</script>
