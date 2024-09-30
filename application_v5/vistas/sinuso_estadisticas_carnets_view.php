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
              <span style="font-size: 20px;"><strong>Estadísticas de Carnets</strong></span>              
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
          <span class="caption-subject bold uppercase"> Estadísticas de Carnets</span>
      </div> 
      <div style="text-align: right;">
        <form id="form_estadisticas" action="<?php echo base_url();?>estadisticas/carnets" role="form" method="post" name="form_estadisticas">
            Fecha desde:
            <input type="date" id="fecha" name="fecha_desde" value="<?php if (isset($fecha_desde)) { echo $fecha_desde; } ?>" />
            hasta
            <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?php if (isset($fecha_hasta)) { echo $fecha_hasta; } ?>" />
            <input type="submit" value="Filtrar" class="btn btn-primary text-inverse-primary" />            
        </form>
      </div>
    </div>
    <div class="panel with-nav-tabs panel-default">
        <div class="panel-heading">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab1default" data-toggle="tab">Carnets Vendidos</a></li>
                <li><a href="#tab2default" data-toggle="tab">Templos Sin Usar</a></li>
                <li><a href="#tab3default" data-toggle="tab">Venta Carnets Centros</a></li>
                <li><a href="#tab4default" data-toggle="tab">Tipos Carnets Vendidos</a></li>                    
            </ul>
        </div>
        <div class="panel-body">
            <div class="tab-content">
            <div class="tab-pane fade in active" id="tab1default">                
            <?php if ($registros != 0) { ?>
            <table id="logs_carnets" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                    <thead class="">
                        <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th style="display: none;">ID</th>
                        <th style="width: 10%;">Tipo de Carnet</th>
                        <?php foreach ($centros as $row) { if($row['id_centro'] > 1) { ?>
                        <th style="width: 15%;"><?php echo $row['nombre_centro'] ?></th>
                        <?php }} ?>
                        <th style="width: 15%;">Total</th>            
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php foreach ($tipos_carnets as $row) { ?>
                    <tr>
                        <td style="display: none;">
                            <?php echo $row['id_tipo'] ?>
                        </td>
                        <td><b><?php echo $row['descripcion'] ?></b></td>
                        <?php foreach ($centros as $centro) { if($centro['id_centro'] > 1) { ?>
                        <td>                        
                            <?php foreach ($registros as $dato) { if ($dato['id_tipo']==$row['id_tipo'] && $dato['id_centro']==$centro['id_centro']) { ?>
                                <?php                            
                                    if ($centro['id_centro']==3) { $total_centro = $total_carnets[0]['numero_carnets']; }
                                    if ($centro['id_centro']==4) { $total_centro = $total_carnets[1]['numero_carnets']; }
                                    if ($centro['id_centro']==6) { $total_centro = $total_carnets[2]['numero_carnets']; }
                                    if ($centro['id_centro']==7) { $total_centro = $total_carnets[3]['numero_carnets']; }
                                    if ($centro['id_centro']==9) { $total_centro = $total_carnets[4]['numero_carnets']; }
                                    if ($total_centro>0) { $porcentaje=round(($dato['numero_carnets']*100)/$total_centro,2); }
                                    else { $porcentaje=0; }
                                    echo $dato['numero_carnets']." carnets<br>".str_replace(",0", "", (string)number_format($dato['total'], 1, ",", "."))." €<br>".$porcentaje."%";
                                ?>
                            <?php } } ?>                        
                        </td>
                        <?php }} ?>
                        <td>
                            <?php foreach ($todos as $dato) { if ($dato['id_tipo']==$row['id_tipo']) { ?>
                                <?php                                                            
                                    if ($total_centro>0) { $porcentaje=round(($dato['numero_carnets']*100)/$total_todos_carnets[0]['numero_carnets'],2); }
                                    else { $porcentaje=0; }                                
                                    echo $dato['numero_carnets']." carnets<br>".str_replace(",0", "", (string)number_format($dato['total'], 1, ",", "."))." €<br>".$porcentaje."%";
                                ?>
                            <?php } } ?>                        
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } ?>
            </div>
            <div class="tab-pane fade" id="tab2default">
            <b>
            <h3 style="font-weight: bold;">Datos correspondientes a Carnets sin Usar desde hace 1 año</h3>
            <br>
            </b>
            <?php if ($sin_usar != 0) { ?>
            <table id="logs_carnets2" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                    <thead class="">
                        <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th style="display: none;">ID</th>
                        <th style="width: 10%;">Tipo de Carnet</th>
                        <?php foreach ($centros as $row) { if($row['id_centro'] > 1) { ?>
                        <th style="width: 15%;"><?php echo $row['nombre_centro'] ?></th>
                        <?php }} ?>
                        <th style="width: 15%;">Total</th>            
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php $total_centro=0; foreach ($tipos_carnets as $row) { ?>
                    <tr>
                        <td style="display: none;">
                            <?php echo $row['id_tipo'] ?>
                        </td>
                        <td><b><?php echo $row['descripcion'] ?></b></td>
                        <?php foreach ($centros as $centro) { if($centro['id_centro'] > 1) { ?>
                        <td>                        
                            <?php foreach ($sin_usar as $dato) { if ($dato['id_tipo']==$row['id_tipo'] && $dato['id_centro']==$centro['id_centro']) { ?>
                                <?php                                    
                                    if ($centro['id_centro']==3) { $total_centro = $total_carnets_sin_usar[0]['numero_templos']+$total_carnets_sin_usar[0]['numero_templos_especiales']; }
                                    if ($centro['id_centro']==4) { $total_centro = $total_carnets_sin_usar[1]['numero_templos']+$total_carnets_sin_usar[1]['numero_templos_especiales']; }
                                    if ($centro['id_centro']==6) { $total_centro = $total_carnets_sin_usar[2]['numero_templos']+$total_carnets_sin_usar[2]['numero_templos_especiales']; }
                                    if ($centro['id_centro']==7) { $total_centro = $total_carnets_sin_usar[3]['numero_templos']+$total_carnets_sin_usar[3]['numero_templos_especiales']; }
                                    if ($centro['id_centro']==9) { $total_centro = $total_carnets_sin_usar[4]['numero_templos']+$total_carnets_sin_usar[4]['numero_templos_especiales']; }    
                                    if ($row['id_tipo']==99) {                                    
                                        $dato['numero_templos']=$dato['numero_templos_especiales'];
                                        $dato['total']=$dato['total_especial'];
                                    }
                                    if ($total_centro>0) {                                        
                                        $porcentaje=round(($dato['numero_templos']*100)/$total_centro,2);
                                    }
                                    else {
                                        $porcentaje=0;
                                    }
                                    echo "<a href='<?php echo base_url();?>estadisticas/carnets_desglose/".$dato['id_centro']."/".$dato['id_tipo']."' target='_blank'><b>".$dato['numero_templos']."t</b></a><br>".str_replace(",0", "", (string)number_format($dato['total'], 1, ",", "."))." €<br>".$porcentaje."%";
                                ?>
                            <?php } } ?>                        
                        </td>
                        <?php }} ?>
                        <td>
                            <?php foreach ($todos_sin_usar as $dato) { if ($dato['id_tipo']==$row['id_tipo']) { ?>
                                <?php                                                            
                                    if ($row['id_tipo']==99) {                                        
                                        $dato['total']=$dato['total_especial'];
                                        $dato['numero_templos']=$dato['numero_templos_especiales'];
                                    }
                                    if ($total_centro>0) { $porcentaje=round(($dato['numero_templos']*100)/($total_todos_carnets_sin_usar[0]['numero_templos']+$total_todos_carnets_sin_usar[0]['numero_templos_especiales']),2); }
                                    else { $porcentaje=0; }                                
                                    echo $dato['numero_templos']."t<br>".str_replace(",0", "", (string)number_format($dato['total'], 1, ",", "."))." €<br>".$porcentaje."%";
                                ?>
                            <?php } } ?>                        
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } ?>
            </div>
            <div class="tab-pane fade in active" id="tab3default">                                
                <div id="piechart1" style="width: 100%; height: 500px;"></div>
            </div>
            <div class="tab-pane fade in active" id="tab4default">                
                <div id="piechart2" style="width: 100%; height: 500px;"></div>
            </div>
            </div>
        </div>
    </div>
  </div>
  <!-- END SAMPLE FORM PORTLET-->
</div>
</div>
</div>
<!-- END CONTENT BODY -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        //
        // ... Venta Carnets Centros
        //
        var data = google.visualization.arrayToDataTable([
          ['Task', 'carnets'],
          <?php if ($venta_carnets_centros > 0) { foreach ($venta_carnets_centros as $row) { ?>
            ['<?php echo $row['nombre_centro']." (".str_replace(",0", "", (string)number_format($row['total'], 1, ",", "."))." €)"; ?>', <?php echo $row['numero_carnets'] ?>],
          <?php }} ?>                                          
        ]);
        var options = {
          title: 'Venta Carnets Centros',
          fontSize: 14,
          fontName: 'Open Sans',          
          is3D: true,
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart1'));
        chart.draw(data, options);
        //
        // ... Tipos Carnets Vendidos
        //
        var data = google.visualization.arrayToDataTable([
          ['Task', 'carnets'],
          <?php if ($tipo_carnets_vendidos > 0) { foreach ($tipo_carnets_vendidos as $row) { ?>
            ['<?php echo $row['descripcion']." (".str_replace(",0", "", (string)number_format($row['total'], 1, ",", "."))." €)"; ?>', <?php echo $row['numero_carnets'] ?>],
          <?php }} ?>                                
        ]);
        var options = {
          title: 'Tipos Carnets Vendidos',
          is3D: true,
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart2'));
        chart.draw(data, options);        
    }
</script>