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
              <span style="font-size: 20px;"><strong>Estadísticas por Productos / Familias</strong></span>              
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
      <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Estadísticas</span>
      </div>
      <div style="text-align: right;">
        <form id="form_estadisticas" action="<?php echo base_url();?>estadisticas/productos" role="form" method="post" name="form_estadisticas">
            Fecha desde:
            <input type="date" id="fecha" name="fecha_desde" value="<?php if (isset($fecha_desde)) { echo $fecha_desde; } ?>" />
            hasta
            <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?php if (isset($fecha_hasta)) { echo $fecha_hasta; } ?>" />
            &nbsp; Familia: 
            <select name="id_familia_producto" required>
                <option value="">Elegir ...</option>
                <?php if (isset($familias)) { if ($familias != 0) { foreach ($familias as $key => $row) { ?>
                    <option value='<?php echo $row['id_familia_producto']; ?>' <?php if (isset($id_familia_producto)) { if ($row['id_familia_producto']==$id_familia_producto) { echo "selected"; } } ?>>
                        <?php echo $row['nombre_familia']; ?>
                    </option>
                <?php }}} ?>                    
            </select>
            <input type="submit" value="Filtrar" class="btn btn-primary text-inverse-primary" />            
        </form>
      </div>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">        
    <?php if (isset($productos)) { if ($productos != 0) { ?>        
        <table id="estadistica_usuarios" class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th style="display: none;"></th>
                    <th>Producto</th>
                    <?php if (isset($centros)) { if ($centros != 0) { foreach ($centros as $key => $row) { if ($row['id_centro'] > 1) { ?>
                    <th><?php echo $row['nombre_centro']; ?></th>
                    <?php }}}} ?>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 fw-semibold">
                <?php if (isset($productos)) { if ($productos != 0) {
                    if (isset($centros)) { if ($centros != 0) { foreach ($centros as $key => $c) { if ($c['id_centro'] > 1) {                       
                        $item="c".$c['id_centro'];                        
                        $total_centro[$item]=0;
                    }}}}
                    foreach ($productos as $key => $row) { ?>
                <tr>
                    <td style="display: none;"></td>
                    <td><b><?php echo $row['nombre_producto'] ?></b></td>
                    <?php if (isset($centros)) { if ($centros != 0) { $total_producto=0; foreach ($centros as $key => $c) { if ($c['id_centro'] > 1) { ?>
                    <td style="text-align: center; width: 10%;">
                        <?php
                            $item="c".$c['id_centro'];
                            echo $row[$item];
                            $total_producto+=$row[$item];
                            $total_centro[$item]+=$row[$item];
                        ?>
                    </td>
                    <?php }}}} ?>                    
                    <td style="text-align: center; width: 8%;">
                        <b><?php echo $total_producto; ?></b>
                    </td>                    
                </tr>
                <?php }}} ?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-end">TOTALES</td>
                    <?php if (isset($centros)) { if ($centros != 0) { $total_producto=0; foreach ($centros as $key => $c) { if ($c['id_centro'] > 1) { ?>
                    <td style="text-align: center; width: 10%;">
                        <?php
                            $item="c".$c['id_centro'];
                            echo $total_centro[$item];
                            $total_producto+=$total_centro[$item];
                        ?>
                    </td>
                    <?php }}}} ?>
                    <td style="text-align: center; width: 8%;">
                        <b><?php echo $total_producto; ?></b>
                    </td>                    
                </tr>
            </tfoot>
        </table>
    <?php }} ?>
    </div>
  </div>
  <!-- END SAMPLE FORM PORTLET-->
</div>
</div>
</div>
<!-- END CONTENT BODY -->