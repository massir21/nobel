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
              <span style="font-size: 20px;"><strong>Gestión de Cajas Regalo</strong></span>              
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
          <span class="caption-subject bold uppercase">Cajas Regalo</span>
      </div>
      <div style="text-align: right; margin-bottom: 20px">
        <form id="form_cajas" action="<?php echo base_url();?>servicios/cajas_regalo" role="form" method="post" name="form_cajas">
            Desde:
            <input type="date" id="fecha" name="fecha_inicio" value="<?php if (isset($fecha_inicio)) { echo $fecha_inicio; } ?>" style="width: 140px;" />
            hasta
            <input type="date" id="fecha_hasta" name="fecha_fin" value="<?php if (isset($fecha_fin)) { echo $fecha_fin; } ?>" style="width: 140px;" />
            <?php if ($this->session->userdata('id_perfil')==0) { ?>
            &nbsp; Centro: 
            <select name="id_centro">
                <option value="">Todos</option>
                <?php if (isset($centros)) { if ($centros != 0) { foreach ($centros as $key => $row) { ?>
                    <option value='<?php echo $row['id_centro']; ?>' <?php if (isset($id_centro)) { if ($row['id_centro']==$id_centro) { echo "selected"; } } ?>>
                        <?php echo $row['nombre_centro']; ?>
                    </option>
                <?php }}} ?>                    
            </select>
            <?php } ?>
            <input type="submit" value="Filtrar" class="btn btn-primary text-inverse-primary" />
            <a href="<?php echo base_url();?>recursos/cajas_regalo_<?php echo $this->session->userdata('id_usuario'); ?>.csv" style="color: #fff; text-decoration: none;" class="btn btn-success text-inverse-success">
              Exportar CSV
            </a>                      
        </form>        
    </div>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">          
      <table id="myTable1"class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
            <th style="display: none;"></th>
            <th>Fecha</th>
            <th>Centro</th>
            <th>Servicio</th>
            <th>Cod. Proveedor</th>
            <th>Rembolso Proveedor</th>                        
            <th>Cliente</th>
            <th>Empleado</th>                                    
          </tr>
        </thead>
        <tbody class="text-gray-700 fw-semibold">
          <?php $total_importe=0; ?>
          <?php if (isset($registros)) { if ($registros != 0) { foreach ($registros as $key => $row) { ?>
          <tr>
            <td style="display: none;">
                <?php echo $row['fecha_hora_concepto_aaaammdd'] ?>
            </td>
            <td style="text-align: center;">              
              <?php echo $row['fecha_hora_concepto_ddmmaaaa_abrv'] ?>
            </td>
            <td style="text-align: center;">              
              <?php echo $row['nombre_centro']; ?>
            </td>            
            <td style="text-align: center;">              
              <?php echo $row['servicio']; ?>
            </td>
            <td style="text-align: center;">              
              <?php echo $row['codigo_proveedor']; ?>
            </td>
            <td class="text-end">              
              <?php echo round($row['precio_proveedor'],2)." €"; $total_importe+=$row['precio_proveedor']; ?>              
            </td>
            <td style="text-align: center;">              
              <?php echo $row['cliente'] ?>
            </td>
            <td style="text-align: center;">              
              <?php echo $row['empleado'] ?>
            </td>            
          </tr>
          <?php } } } ?>
        </tbody>
        <?php if (isset($registros)) { ?>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right; padding: 8px;"><b>TOTAL</b></td>
                <td style="text-align: right; padding: 8px;"><?php echo round($total_importe,2); ?> €</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
        <?php } ?>
      </table>
    </div>
  </div>
  <!-- END SAMPLE FORM PORTLET-->
</div>
</div>
</div>
<!-- END CONTENT BODY -->