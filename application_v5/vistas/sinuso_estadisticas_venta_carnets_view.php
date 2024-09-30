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
                <strong>Estadísticas Venta de Carnets Recepcionistas</strong></span>                
            </li>
        </ul>
        <!--<div style="float: right; margin-top: 5px; margin-right: 5px;">
            <button class="btn btn-info text-inverse-info">            
            <a href="<?php echo base_url();?>recursos/estadisticas_recepcionistas_<?php echo $this->session->userdata('id_usuario') ?>.csv" style="color: #fff; text-decoration: none;">
              Exportar CSV
            </a>            
          </button>            
        </div>-->
    </div>
    <!-- END PAGE BAR -->
    <!-- END PAGE HEADER-->
<div class="row ">
<div class="col-md-12">
    <div class="portlet light bordered">
    <div class="portlet-title">      
      <div style="overflow: hidden;">
        <form id="form_estadisticas" action="<?php echo base_url();?>estadisticas/venta_carnets_recepcionistas" role="form" method="post" name="form_estadisticas">
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
            Recepcionista:
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
    <?php if ($hay_registros==1) { ?>
        <table class="table table-striped table-hover table-bordered">
        <thead>
            <tr>
                <th style="display: none;"></th>
                <th>Tipo de Carnet</th>
                <th>Cantidad</th>
                <th>Nuevas Ventas</th>
                <th>Carnet Superior</th>                
            </tr>
        </thead>
        <tbody class="text-gray-700 fw-semibold">
            <?php if (isset($venta_carnets)) { if ($venta_carnets != 0) { foreach ($venta_carnets as $key => $row) { ?>
            <tr>
                <td style="display: none;"></td>
                <td style="text-align: left;"><?php echo $row['tipo'] ?></td>
                <td style="text-align: center;"><?php echo $row['cantidad'] ?></td>
                <td style="text-align: center;"><?php echo $row['nuevas_ventas'] ?></td>
                <td style="text-align: center;"><?php echo $row['carnet_superior'] ?></td>
            </tr>
            <?php }}} ?>
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