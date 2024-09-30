<style>            
    .dataTables_filter {
        text-align: right;        
    }
</style>
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
              <span style="font-size: 20px;"><strong>Modificación de Carnets</strong></span>              
            </li>
        </ul>                        
    </div>
    <!-- END PAGE BAR -->
    <!-- END PAGE HEADER-->
<div class="row ">
<div class="col-md-12">
  <!-- BEGIN SAMPLE FORM PORTLET-->
  <div class="portlet light bordered">
    <div class="portlet-title">      
      <div style="text-align: left; overflow: hidden;">
        <form id="form_estadisticas" action="<?php echo base_url();?>estadisticas/modificacioncarnets" role="form" method="post" name="form_estadisticas">            
            <div style="float: left; width: 40%;">
                <select name="id_usuario" data-placeholder="Todos los empleados ..." class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                    <option value="0">Todos los empleados</option>
                    <?php if (isset($empleados)) { if ($empleados != 0) { foreach ($empleados as $key => $row) { ?>
                        <option value='<?php echo $row['id_usuario']; ?>' <?php if (isset($id_usuario)) { if ($row['id_usuario']==$id_usuario) { echo "selected"; } } ?>>
                            <?php echo strtoupper($row['apellidos'].", ".$row['nombre']." (".$row['nombre_centro'].")"); ?>
                        </option>
                    <?php }}} ?>                    
                </select>
            </div>
            <div style="float: left; margin-left: 10px;">
                Fecha desde:
                <input type="date" id="fecha" name="fecha_desde" value="<?php if (isset($fecha_desde)) { echo $fecha_desde; } ?>" required />
                hasta
                <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?php if (isset($fecha_hasta)) { echo $fecha_hasta; } ?>" required />
            </div>
            <div style="float: left; margin-left: 10px;">
                <input type="submit" value="Filtrar" class="btn btn-primary text-inverse-primary" />
            </div>
        </form>
      </div>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">        
      <table id="logs" class="table table-striped table-hover table-bordered">
            <thead>
              <tr>
                <th style="display: none;">ID</th>
                <th>Fecha</th>
                <th>Carnet</th>
                <th>Empleado</th>
                <th>Centro</th>
                <th>Templos / Servicio </th>                
              </tr>
            </thead>
            <tbody class="text-gray-700 fw-semibold">
                <?php if (isset($carnets_ajustes)) { if ($carnets_ajustes != 0) { foreach ($carnets_ajustes as $key => $row) { ?>
                <tr>
                  <td style="display: none;">
                    <?php echo $row['fecha_aaaammdd']; ?>                    
                  </td>
                  <td style="text-align: center;">                      
                    <?php echo $row['fecha_abrev']; ?>                    
                  </td>
                  <td style="text-align: center;">
                    <a href="<?php echo base_url();?>carnets/detalle/gestion/<?php echo $row['id_carnet']; ?>" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Ver detalle del Carnet" target="_blank">
                      <b><?php echo $row['codigo']; ?></b>
                    </a>
                  </td>
                  <td style="text-align: left;">                      
                    <?php echo $row['empleado']; ?>                    
                  </td>
                  <td style="text-align: center;">                      
                    <?php echo $row['nombre_centro']; ?>                      
                  </td>                  
                  <td style="text-align: center;">              
                      <?php
                        $templos=$row['templos_disponibles']-$row['templos_disponibles_anteriores'];
                        if ($templos>0) { echo "<span style='color: green; font-weight: bold;'>+".$templos."</span>"; }
                        if ($templos==0) { echo "<br>".$templos."</b>"; }
                        if ($templos<0) { echo "<span style='color: red; font-weight: bold;'>".$templos."</span>"; }
                      ?>
                  </td>
                </tr>
                <?php } } } ?>
                <?php if (isset($carnets_servicios)) { if ($carnets_servicios != 0) { foreach ($carnets_servicios as $key => $row) { ?>
                <tr>             
                  <td style="display: none;">
                      <?php echo $row['fecha_modificacion_aaaammdd'] ?>
                  </td>
                  <td style="text-align: center;">              
                      <?php echo $row['fecha_modificacion_abrev'] ?>
                  </td>            
                  <td style="text-align: center;">
                    <a href="<?php echo base_url();?>carnets/detalle/gestion/<?php echo $row['id_carnet']; ?>" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Ver detalle del Carnet" target="_blank">
                      <b><?php echo $row['codigo']; ?></b>
                    </a>
                  </td>
                  <td style="text-align: left;">                      
                      <?php echo $row['empleado'] ?>
                  </td>            
                  <td style="text-align: center;">              
                      <?php echo $row['nombre_centro'] ?>
                  </td>                  
                  <td style="text-align: center;">              
                        <?php
                            if ($row['borrado']==1) {
                                echo "<span style='color: red; font-weight: bold;'>(quitado)<br>".$row['nombre_familia']." - ".$row['nombre_servicio']." (".$row['duracion']." min)</span>";
                            }
                            else {
                                echo "<span style='color: green; font-weight: bold;'>(agregado)</br>".$row['nombre_familia']." - ".$row['nombre_servicio']." (".$row['duracion']." min)</span>";
                            }
                        ?>
                  </td>
                </tr>
                <?php } } } ?>          
            </tbody>
        </table>
    </div>
  </div>
  <!-- END SAMPLE FORM PORTLET-->
</div>
</div>
</div>
<!-- END CONTENT BODY -->