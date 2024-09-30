<STYLE>
    .dataTables_filter { text-align: right; }
</STYLE>
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->                        
    <!-- BEGIN PAGE BAR -->
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>              
              <a href="<?php echo base_url();?>site" style="font-size: 20px;">Panel de Control</a>              
              <i class="fa fa-angle-right"></i>                
            </li>
            <li>              
              <span style="font-size: 20px;">                
                <strong>Estadísticas Empleados</strong></span>                
            </li>
        </ul>
        <div style="float: right; margin-top: 5px; margin-right: 5px;">
            <button class="btn btn-info text-inverse-info">            
            <a href="<?php echo base_url();?>recursos/estadisticas_empleados_<?php echo $this->session->userdata('id_usuario') ?>.csv" style="color: #fff; text-decoration: none;">
              Exportar CSV
            </a>            
          </button>            
        </div>
    </div>
    <!-- END PAGE BAR -->
    <!-- END PAGE HEADER-->
<div class="row ">
<div class="col-md-12">
    <div class="portlet light bordered">
    <div class="portlet-title">      
      <div style="overflow: hidden;">
        <form id="form_estadisticas" action="<?php echo base_url();?>estadisticas/empleados" role="form" method="post" name="form_estadisticas">
            <div style="float: left; margin-right: 7px;">
            Fecha desde:
            </div>
            <div style="float: left; margin-right: 7px;">
            <input type="date" id="fecha" class="form-control form-control-solid" name="fecha_desde" value="<?php if (isset($fecha_desde)) { echo $fecha_desde; } ?>" />
            </div>
            <div style="float: left; margin-right: 7px;">
            hasta
            </div>
            <div style="float: left; margin-right: 7px;">
            <input type="date" id="fecha_hasta" class="form-control form-control-solid" name="fecha_hasta" value="<?php if (isset($fecha_hasta)) { echo $fecha_hasta; } ?>" />
            </div>
            <div style="float: left; margin-right: 7px;">
            Empleado o Encargado:
            </div>
            <div style="float: left; margin-right: 7px;">
            <select name="id_empleado" data-placeholder="Elegir empleado ..." class="form-control select2 select2-hidden-accessible" required>
                <option value=""></option>
                <?php if (isset($empleados)) { if ($empleados != 0) { foreach ($empleados as $key => $row) { ?>
                    <option value='<?php echo $row['id_usuario']; ?>' <?php if (isset($id_empleado)) { if ($row['id_usuario']==$id_empleado) { echo "selected"; } } ?>>
                        <?php echo $row['nombre']." ".$row['apellidos']." (".$row['nombre_centro'].")"; ?>
                    </option>
                <?php }}} ?>
            </select>
            </div>
            <div style="float: left";>
                <input type="submit" value="Filtrar" class="btn btn-primary text-inverse-primary" />
            </div>
        </form>
      </div>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">          
    <?php if ($hay_registros==1) { ?>
        <table class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <td></td>
                    <td>Empleado / Encargado</td>
                    <td>Media del Centro</td>
                </tr>
            </thead>
            <tbody class="text-gray-700 fw-semibold">
            <tr>
                <td style="width: 35%;">Facturación servicios €</td>
                <td><?php echo number_format($facturacion_servicios_euros, 2, ',', '.')." €"; ?></td>
                <td><?php echo number_format($facturacion_euros_media, 2, ',', '.')." €"; ?></td>
            </tr>
            <tr>
                <td>Templos realizados</td>
                <td><?php echo round($templos_realizados, 2); ?></td>
                <td><?php echo round($templos_realizados_media, 2); ?></td>                
            </tr>
            <tr>
                <td>Número de productos vendidos</td>
                <td><?php echo round($productos_vendidos, 2); ?></td>
                <td><?php echo round($productos_vendidos_media, 2); ?></td>                
            </tr>
            <tr>
                <td>Total € productos vendidos</td>
                <td><?php echo number_format($total_productos_vendidos, 2, ',', '.')." €"; ?></td>
                <td><?php echo number_format($total_productos_vendidos_media, 2, ',', '.')." €"; ?></td>
            </tr>
            <tr>
                <td>Total de Facturación Real</td>
                <td><?php echo number_format($total_real_facturado, 2, ',', '.')." €"; ?></td>
                <td><?php echo number_format($total_real_facturado_media, 2, ',', '.')." €"; ?></td>                                
            </tr>
            <tr>                            
                <td>Nº de clientes fieles (clientes únicos que piden con este empleado)</td>
                <td><?php echo round($clientes_fieles, 2); ?></td>
                <td><?php echo round($clientes_fieles_media, 2); ?></td>
            </tr>
            <tr>
                <td>Rentabilidad (Facturación servicios + Facturacion productos / horas trabajadas)</td>
                <td><?php echo number_format($rentabilidad, 2, ',', '.')." € / hora"; ?></td>
                <td><?php echo number_format($rentabilidad_media, 2, ',', '.')." € / hora"; ?></td>
            </tr>
            <tr>
                <td>Nº de clientes nuevos atendidos:</td>
                <td><?php echo round($clientes_nuevos_atendidos, 2); ?></td>
                <td><?php echo round($clientes_nuevos_atendidos_media, 2); ?></td>
            </tr>
            <tr>
                <td>Nº de clientes última visita atendidos:</td>
                <td><?php echo round($clientes_ultima_visita_atendidos, 2); ?></td>
                <td><?php echo round($clientes_ultima_visita_atendidos_media, 2); ?></td>
            </tr>
            <tr>
                <td>Nº de clientes atendidos total:</td>
                <td><?php echo round($clientes_atendidos_total, 2); ?></td>
                <td><?php echo round($clientes_atendidos_total_media, 2); ?></td>
            </tr>
            <tr>
                <td>Nº de servicios realizados:</td>                
                <td><?php echo round($numero_servicios, 2); ?></td>
                <td><?php echo round($numero_servicios_media, 2); ?></td>
            </tr>
            <tr>
                <td>Nº de servicios pagados con Carnets:</td>
                <td><?php echo round($numero_servicios_pago_carnet, 2); ?></td>
                <td><?php echo round($numero_servicios_pago_carnet_media, 2); ?></td>
            </tr>
            <tr>
                <td>Nº de servicios pagados con Euros:</td>
                <td><?php echo round($numero_servicios_pago_euros, 2); ?></td>
                <td><?php echo round($numero_servicios_pago_euros_media, 2); ?></td>
            </tr>
            <tr>
                <td>Nº de cobros con descuento:</td>
                <td><?php echo round($cobros_descuentos, 2); ?></td>                                
                <td><?php echo round($cobros_descuentos_media, 2); ?></td>                                
            </tr>
            <tr>
                <td>Total dinero descontado:</td>
                <td><?php echo number_format($total_dinero_descontado, 2, ',', '.')." € "; ?></td>
                <td><?php echo number_format($total_dinero_descontado_media, 2, ',', '.')." € "; ?></td>
            </tr>            
            <tr>
                <td>Nº de servicios donde hubo venta de Carnet de Templos (Turno mañana % - Turno de tarde %)</td>
                <td>-</td>
                <td>-</td>
            </tr>
            <tr>
                <td style="width: 35%;">Horas asignadas</td>
                <td><?php echo number_format($horas_normal, 1, '.', ''); ?></td>
                <td><?php echo round($horas_normal_media, 2) ?></td>
            </tr>
            <tr>
                <td>
                Horas semanales / Horas asignadas
                </td>
                <td>
                <?php 
                foreach ($semanas_horas as $key => $semana) { ?>
                    Semana del <?php echo $semana['week_start'];?> al <?php echo $semana['week_end'];?>:
                    <?php if($semana['horas_balance'] == 0){
                        echo '<span class="label label-sm label-success" style = padding:3px 6px;"><strong>'.$semana['horas_balance'].'</strong></span>';
                    }elseif($semana['horas_balance'] > 0){
                        echo '<span class="label label-sm label-info" style = padding:3px 6px;"><strong>+ '.$semana['horas_balance'].'</strong></span>';
                    }else{
                        echo '<span class="label label-sm label-danger" style = padding:3px 6px;"><strong> '.$semana['horas_balance'].'</strong></span>';
                    }?> <?php echo $semana['texto_balance'];?><br>
                <?php }
                ?>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="width: 35%;">Horas extra asignadas</td>
                <td><?php echo $horas_extra; ?></td>
                <td><?php echo round($horas_extra_media, 2) ?></td>
            </tr>
            <tr>
                <td style="width: 35%;">Dias de baja</td>
                <td><?php echo $dias_baja; ?></td>
                <td><?php echo round($dias_baja_media, 2) ?></td>
            </tr>
            <tr>
                <td style="width: 35%;">Días de vacaciones</td>
                <td><?php echo $dias_vacaciones; ?></td>
                <td><?php echo round($dias_vacaciones_media, 2) ?></td>
            </tr>            
            </tbody>
        </table>
    <?php } else { ?>
        <p style="text-align: center;">No hay datos con los filtros indicados</p>
    <?php } ?>
    </div>
  </div>
  <!-- END SAMPLE FORM PORTLET-->
</div>
</div>
</div>
<!-- END CONTENT BODY -->