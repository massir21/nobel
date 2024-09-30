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
              <span style="font-size: 20px;"><strong>Estadísticas por Centro</strong></span>              
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
      <div style="text-align: left;">
        <form id="form_estadisticas" action="<?php echo base_url();?>estadisticas/centros" role="form" method="post" name="form_estadisticas">
            Fecha:
            <input type="date" id="fecha" name="fecha_desde" value="<?php if (isset($fecha_desde)) { echo $fecha_desde; } ?>" />
            hasta
            <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?php if (isset($fecha_hasta)) { echo $fecha_hasta; } ?>" />
            <select name="periodo">
                <option value=''>Comprar con período ...</option>
                <option value='1' <?php if ($periodo == 1) { echo "selected"; } ?>>Período Mes Anterior</option>
                <option value='2' <?php if ($periodo == 2) { echo "selected"; } ?>>Período Año Anterior</option>                        
            </select>
            <?php if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 4) { ?>
            &nbsp; Centro: 
            <select name="id_centro">                
                <?php if (isset($centros)) { if ($centros != 0) { foreach ($centros as $key => $row) { if ($row['id_centro'] > 1) { ?>
                    <option value='<?php echo $row['id_centro']; ?>' <?php if (isset($id_centro)) { if ($row['id_centro']==$id_centro) { echo "selected"; } } ?>>
                        <?php echo $row['nombre_centro']; ?>
                    </option>
                <?php }}}} ?>                    
            </select>
            <?php } ?>
            <input type="submit" value="Filtrar" class="btn btn-primary text-inverse-primary" />            
        </form>
      </div>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">        
        <div class="panel-heading" style="width: 100%;">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab1default" data-toggle="tab">Facturación / Empleados</a></li>
                <li><a href="#tab2default" data-toggle="tab">Facturación Templos / Empleados</a></li>
                <li><a href="#tab3default" data-toggle="tab">Facturación / Recepcionistas</a></li>
                <!--<li><a href="#tab4default" data-toggle="tab">Facturación Templos / Recepcionista</a></li>-->
                <li><a href="#tab5default" data-toggle="tab">Venta Productos / Empleados</a></li>
                <li><a href="#tab6default" data-toggle="tab">Venta Carnets Templos / Recepcionista</a></li>
                <li><a href="#tab7default" data-toggle="tab">Citas Agregadas Agenda / Recepcionista</a></li>
                <li><a href="#tab8default" data-toggle="tab">Familias Servicios Realizados</a></li>
                <li><a href="#tab9default" data-toggle="tab">Clientes con Carnets Vendidos</a></li>
                <li><a href="#tab10default" data-toggle="tab">Clientes con Carnets Vendidos (todos los centros)</a></li>
                <li><a href="#tab11default" data-toggle="tab">Empleado Clientes "solo con este empleado"</a></li>
                <li><a href="#tab12default" data-toggle="tab">Clientes Activos / Inactivos</a></li>
                <li><a href="#tab13default" data-toggle="tab">Clientes Activos / Inactivos (todos los centros)</a></li>                
                <li><a href="#tab14default" data-toggle="tab">Pago Euros / Templos</a></li>
                <li><a href="#tab15default" data-toggle="tab">Pago Euros / Templos (todos los centros)</a></li>
                <li><a href="#tab16default" data-toggle="tab">Facturación por Centros</a></li>
                <li><a href="#tab17default" data-toggle="tab">Horas Totales Trabajdas por Centro</a></li>
            </ul>
        </div>
        <div class="panel-body" style="width: 100%;">
            <div class="tab-content" style="width: 100%;">            
                <div class="tab-pane fade in active" id="tab1default">
                    <h4>
                        <strong>
                        Facturación € por Empleado - Sservicios y Carnets (euros correspondientes)
                        </strong>                        
                    </h4>                    
                    <div id="chartFacturacionEurosEmpleados" style="height: 400px; width: 100%;"></div>
                </div>
                <div class="tab-pane fade active" id="tab2default">
                    <h4>
                        <strong>
                        Facturación Templos (servicios) por Empleado
                        </strong>                        
                    </h4>                                        
                    <div id="chartFacturacionTemplosEmpleados" style="height: 400px; width: 100%;"></div>
                </div>
                <div class="tab-pane fade active" id="tab3default">
                    <h4>
                        <strong>
                        Facturación € por Recepcionista
                        </strong>                        
                    </h4>                                                        
                    <div id="chartFacturacionEurosRecepcionista" style="height: 400px; width: 100%;"></div>
                </div>
                <div class="tab-pane fade active" id="tab5default">
                    <h4>
                        <strong>
                        Venta Productos por Empleado
                        </strong>                        
                    </h4>                                                        
                    <div id="chartVentaProductosEmpleados" style="height: 400px; width: 100%;"></div>
                </div>
                <div class="tab-pane fade active" id="tab6default">
                    <h4>
                        <strong>
                        Venta Carnets Templos por Recepcionista
                        </strong>                        
                    </h4>                                                                            
                    <div id="chartVentaCarnetsRecepcionista" style="height: 400px; width: 100%;"></div>
                </div>
                <div class="tab-pane fade active" id="tab7default">
                    <h4>
                        <strong>
                        Citas Agregadas por recepcionista
                        </strong>                        
                    </h4>                                                                            
                    <div id="chartCitasAgregadasRecepcionista" style="height: 400px; width: 100%;"></div>
                </div>
                <div class="tab-pane fade active" id="tab8default">
                    <h4>
                        <strong>
                        Número de servicios realizados por familia
                        </strong>                        
                    </h4>                    
                    <div id="chartFamiliasServiciosRealizados" style="height: 400px; width: 100%;"></div>
                </div>
                <div class="tab-pane fade active" id="tab9default">                    
                    <h4>
                        <strong>
                        Clientes con Carnets Vendidos
                        </strong>                        
                    </h4>                                        
                    <div id="chartClientesCarnetsVendidos" style="height: 400px; width: 100%;"></div>
                </div>
                <div class="tab-pane fade active" id="tab10default">                    
                    <h4>
                        <strong>
                        Clientes con Carnets Vendidos (todos los centros)
                        </strong>                        
                    </h4>                                        
                    <div id="chartClientesCarnetsVendidosTodos" style="height: 400px; width: 100%;"></div>
                </div>
                <div class="tab-pane fade active" id="tab11default">
                    <h4>
                        <strong>
                        Empleado Clientes "solo con este empleado"
                        </strong>                        
                    </h4>                                        
                    <div id="chartClienteEmpleadoConcreto" style="height: 400px; width: 100%;"></div>
                </div>
                <div class="tab-pane fade active" id="tab12default">
                    <h4>
                        <strong>
                        Clientes Activos / Inactivos
                        </strong>                        
                    </h4>                                        
                    <div id="chartClienteActivos" style="height: 400px; width: 100%;"></div>
                </div>
                <div class="tab-pane fade active" id="tab13default">
                    <h4>
                        <strong>
                        Clientes Activos / Inactivos (todos los centros)
                        </strong>                        
                    </h4>                                        
                    <div id="chartClienteActivosTodos" style="height: 400px; width: 100%;"></div>
                </div>
                <div class="tab-pane fade active" id="tab14default">
                    <h4>
                        <strong>
                        Pago Euros / Templos
                        </strong>                        
                    </h4>                                        
                    <div id="chartPagoEurosTemplos" style="height: 400px; width: 100%;"></div>
                </div>
                <div class="tab-pane fade active" id="tab15default">
                    <h4>
                        <strong>
                        Pago Euros / Templos (todos los centros)
                        </strong>                        
                    </h4>                                        
                    <div id="chartPagoEurosTemplosTodos" style="height: 400px; width: 100%;"></div>
                </div>
                <div class="tab-pane fade active" id="tab16default">
                    <h4>
                        <strong>
                        Facturación por Centros
                        </strong>                        
                    </h4>                                        
                    <div id="chartFacturacionPorCentros" style="height: 400px; width: 100%;"></div>
                </div>
                <div class="tab-pane fade active" id="tab17default">
                    <h4>
                        <strong>
                        Horas Totales Trabajadas por Centro
                        </strong>                        
                    </h4>                                        
                    <div id="chartHorasTrabajadasPorCentro" style="height: 400px; width: 100%;"></div>
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
    google.charts.load('current', {'packages':['corechart','bar']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {        
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        <?php if ($periodo == "") { ?>
            var options = {              
              fontSize: 14,
              fontName: 'Open Sans',
              is3D: true,
            };
            var data = google.visualization.arrayToDataTable([
              ['Task', 'euros'],
              <?php
                if ($facturacion_euros_empleados!=0) {
                    foreach ($facturacion_euros_empleados as $row) { if ($row['euros']>0) { ?>
                    ['<?php echo $row['empleado']." (".str_replace(",0", "", (string)number_format($row['euros'], 2, ",", "."))." €)"; ?>', <?php echo $row['euros'] ?>],
              <?php }}} ?>
            ]);    
            var chart = new google.visualization.PieChart(document.getElementById('chartFacturacionEurosEmpleados'));
            chart.draw(data, options);
        <?php } else { ?>
            var options = {
                title: 'Facturación € por Empleado (servicios y productos)',
                chartArea: {width: '50%',height: '100%'},
                hAxis: {
                    title: 'Euros facturados',
                    minValue: 0
                },
                vAxis: {
                    title: 'Empleados'
                }
            };
            var data = google.visualization.arrayToDataTable([
              ['', '<?php echo $fecha_desde_f ?> / <?php echo $fecha_hasta_f ?>','<?php echo $fecha_desde_c ?> / <?php echo $fecha_hasta_c ?>'],
              <?php
                if ($facturacion_euros_empleados != 0) {
                    foreach ($facturacion_euros_empleados as $row) { if ($row['cantidad_1']>0 || $row['cantidad_2']) { ?>
                    ['<?php echo $row['nombre']; ?>', <?php echo $row['cantidad_1'] ?>, <?php echo $row['cantidad_2']; ?>],
              <?php }}} ?>
            ]);
            var chart = new google.visualization.BarChart(document.getElementById('chartFacturacionEurosEmpleados'));
            chart.draw(data, options);
        <?php } ?>
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        <?php if ($periodo == "") { ?>
            var options = {
              title: '',
              fontSize: 14,
              fontName: 'Open Sans',          
              is3D: true,
            };
            var data = google.visualization.arrayToDataTable([
              ['Task', 'templos'],
              <?php
                if ($facturacion_templos_empleados!=0) {
                    foreach ($facturacion_templos_empleados as $row) { if ($row['templos']>0) { ?>
                    ['<?php echo $row['empleado']." (".str_replace(",0", "", (string)number_format($row['templos'], 1, ",", "."))." templos)"; ?>', <?php echo $row['templos'] ?>],
              <?php }}} ?>
            ]);    
            var chart = new google.visualization.PieChart(document.getElementById('chartFacturacionTemplosEmpleados'));
            chart.draw(data, options);
        <?php } else { ?>
            var options = {
                title: 'Facturación Templos (servicios) por Empleado',
                chartArea: {width: '50%',height: '100%'},
                hAxis: {
                    title: '',
                    minValue: 0
                },
                vAxis: {
                    title: 'Empleados'
                }
            };
            var data = google.visualization.arrayToDataTable([
              ['', '<?php echo $fecha_desde_f ?> / <?php echo $fecha_hasta_f ?>','<?php echo $fecha_desde_c ?> / <?php echo $fecha_hasta_c ?>'],
              <?php
                if ($facturacion_templos_empleados != 0) {
                    foreach ($facturacion_templos_empleados as $row) { if ($row['cantidad_1']>0 || $row['cantidad_2']) { ?>
                    ['<?php echo $row['nombre']; ?>', <?php echo $row['cantidad_1'] ?>, <?php echo $row['cantidad_2']; ?>],
              <?php }}} ?>
            ]);
            var chart = new google.visualization.BarChart(document.getElementById('chartFacturacionTemplosEmpleados'));
            chart.draw(data, options);
        <?php } ?>
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        <?php if ($periodo == "") { ?>
            var options = {
              title: '',
              fontSize: 14,
              fontName: 'Open Sans',          
              is3D: true,
            };
            var data = google.visualization.arrayToDataTable([
              ['Task', 'euros'],
              <?php
                if ($facturacion_euros_recepcionista!=0) {
                    foreach ($facturacion_euros_recepcionista as $row) { if ($row['euros']>0) { ?>
                    ['<?php echo $row['empleado']." (".str_replace(",0", "", (string)number_format($row['euros'], 1, ",", "."))." €)"; ?>', <?php echo $row['euros'] ?>],
              <?php }}} ?>
            ]);    
            var chart = new google.visualization.PieChart(document.getElementById('chartFacturacionEurosRecepcionista'));
            chart.draw(data, options);
        <?php } else { ?>
            var options = {
                title: 'Facturación € por Recepcionista',
                chartArea: {width: '50%',height: '100%'},
                hAxis: {
                    title: 'Euros facturados',
                    minValue: 0
                },
                vAxis: {
                    title: 'Empleados'
                }
            };
            var data = google.visualization.arrayToDataTable([
              ['', '<?php echo $fecha_desde_f ?> / <?php echo $fecha_hasta_f ?>','<?php echo $fecha_desde_c ?> / <?php echo $fecha_hasta_c ?>'],
              <?php
                if ($facturacion_euros_recepcionista != 0) {
                    foreach ($facturacion_euros_recepcionista as $row) { if ($row['cantidad_1']>0 || $row['cantidad_2']) { ?>
                    ['<?php echo $row['nombre']; ?>', <?php echo $row['cantidad_1'] ?>, <?php echo $row['cantidad_2']; ?>],
              <?php }}} ?>
            ]);
            var chart = new google.visualization.BarChart(document.getElementById('chartFacturacionEurosRecepcionista'));
            chart.draw(data, options);
        <?php } ?>
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        <?php if ($periodo == "") { ?>
            var options = {
              title: '',
              fontSize: 14,
              fontName: 'Open Sans',          
              is3D: true,
            };
            var data = google.visualization.arrayToDataTable([
              ['Task', 'euros'],
              <?php
                if ($ventas_productos_empleado!=0) {
                    foreach ($ventas_productos_empleado as $row) { if ($row['euros']>0) { ?>
                    ['<?php echo $row['empleado']." (".str_replace(",0", "", (string)number_format($row['euros'], 1, ",", "."))." €)"; ?>', <?php echo $row['euros'] ?>],
              <?php }}} ?>
            ]);    
            var chart = new google.visualization.PieChart(document.getElementById('chartVentaProductosEmpleados'));
            chart.draw(data, options);
        <?php } else { ?>
            var options = {
                title: 'Venta Productos por Empleado',
                chartArea: {width: '50%',height: '100%'},
                hAxis: {
                    title: '',
                    minValue: 0
                },
                vAxis: {
                    title: 'Empleados'
                }
            };
            var data = google.visualization.arrayToDataTable([
              ['', '<?php echo $fecha_desde_f ?> / <?php echo $fecha_hasta_f ?>','<?php echo $fecha_desde_c ?> / <?php echo $fecha_hasta_c ?>'],
              <?php
                if ($ventas_productos_empleado != 0) {
                    foreach ($ventas_productos_empleado as $row) { if ($row['cantidad_1']>0 || $row['cantidad_2']) { ?>
                    ['<?php echo $row['nombre']; ?>', <?php echo $row['cantidad_1'] ?>, <?php echo $row['cantidad_2']; ?>],
              <?php }}} ?>
            ]);
            var chart = new google.visualization.BarChart(document.getElementById('chartVentaProductosEmpleados'));
            chart.draw(data, options);
        <?php } ?>
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        <?php if ($periodo == "") { ?>
            var options = {
              title: '',
              fontSize: 14,
              fontName: 'Open Sans',          
              is3D: true,
            };
            var data = google.visualization.arrayToDataTable([
              ['Task', 'cantidad'],
              <?php
                if ($ventas_carnets_templos_recepcionista!=0) {
                    foreach ($ventas_carnets_templos_recepcionista as $row) { if ($row['cantidad']>0) { ?>
                    ['<?php echo $row['empleado']." (".str_replace(",0", "", (string)number_format($row['euros'], 1, ",", "."))." €)"; ?>', <?php echo $row['cantidad'] ?>],
              <?php }}} ?>
            ]);    
            var chart = new google.visualization.PieChart(document.getElementById('chartVentaCarnetsRecepcionista'));
            chart.draw(data, options);
        <?php } else { ?>
            var options = {
                title: 'Venta Carnets Templos por Recepcionista',
                chartArea: {width: '50%',height: '100%'},
                hAxis: {
                    title: '',
                    minValue: 0
                },
                vAxis: {
                    title: 'Empleados'
                }
            };
            var data = google.visualization.arrayToDataTable([
              ['', '<?php echo $fecha_desde_f ?> / <?php echo $fecha_hasta_f ?>','<?php echo $fecha_desde_c ?> / <?php echo $fecha_hasta_c ?>'],
              <?php
                if ($ventas_carnets_templos_recepcionista != 0) {
                    foreach ($ventas_carnets_templos_recepcionista as $row) { if ($row['cantidad_1']>0 || $row['cantidad_2']) { ?>
                    ['<?php echo $row['nombre']; ?>', <?php echo $row['cantidad_1'] ?>, <?php echo $row['cantidad_2']; ?>],
              <?php }}} ?>
            ]);
            var chart = new google.visualization.BarChart(document.getElementById('chartVentaCarnetsRecepcionista'));
            chart.draw(data, options);
        <?php } ?>
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        <?php if ($periodo == "") { ?>
            var options = {
              title: '',
              fontSize: 14,
              fontName: 'Open Sans',          
              is3D: true,
            };
            var data = google.visualization.arrayToDataTable([
              ['Task', 'citas'],
              <?php
                if ($citas_agregadas_agenda_recepcionista!=0) {
                    foreach ($citas_agregadas_agenda_recepcionista as $row) { if ($row['citas']>0) { ?>
                    ['<?php echo $row['empleado']." (".str_replace(",0", "", (string)number_format($row['citas'], 1, ",", "."))." citas)"; ?>', <?php echo $row['citas'] ?>],
              <?php }}} ?>
            ]);    
            var chart = new google.visualization.PieChart(document.getElementById('chartCitasAgregadasRecepcionista'));
            chart.draw(data, options);
        <?php } else { ?>
            var options = {
                title: 'Citas Agregadas por recepcionista',
                chartArea: {width: '50%',height: '100%'},
                hAxis: {
                    title: '',
                    minValue: 0
                },
                vAxis: {
                    title: 'Empleados'
                }
            };
            var data = google.visualization.arrayToDataTable([
              ['', '<?php echo $fecha_desde_f ?> / <?php echo $fecha_hasta_f ?>','<?php echo $fecha_desde_c ?> / <?php echo $fecha_hasta_c ?>'],
              <?php
                if ($citas_agregadas_agenda_recepcionista != 0) {
                    foreach ($citas_agregadas_agenda_recepcionista as $row) { if ($row['cantidad_1']>0 || $row['cantidad_2']) { ?>
                    ['<?php echo $row['nombre']; ?>', <?php echo $row['cantidad_1'] ?>, <?php echo $row['cantidad_2']; ?>],
              <?php }}} ?>
            ]);
            var chart = new google.visualization.BarChart(document.getElementById('chartCitasAgregadasRecepcionista'));
            chart.draw(data, options);
        <?php } ?>
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        <?php if ($periodo == "") { ?>
            var options = {
              title: '',
              fontSize: 14,
              fontName: 'Open Sans',          
              is3D: true,
            };
            var data = google.visualization.arrayToDataTable([
              ['Task', 'euros'],
              <?php
                if ($familias_servicios_realizados!=0) {
                    foreach ($familias_servicios_realizados as $row) { if ($row['cantidad']>0) { ?>
                    ['<?php echo $row['nombre_familia']." (".$row['cantidad']." realizados)"; ?>', <?php echo $row['cantidad'] ?>],
              <?php }}} ?>
            ]);    
            var chart = new google.visualization.PieChart(document.getElementById('chartFamiliasServiciosRealizados'));
            chart.draw(data, options);
        <?php } else { ?>        
            var options = {
                title: 'Familias Servicios Realizados',
                chartArea: {width: '50%',height: '100%'},
                hAxis: {
                    title: 'Cantidad',
                    minValue: 0
                },
                vAxis: {
                    title: 'Familias'
                }                
            };
            var data = google.visualization.arrayToDataTable([
              ['', '<?php echo $fecha_desde_f ?> / <?php echo $fecha_hasta_f ?>','<?php echo $fecha_desde_c ?> / <?php echo $fecha_hasta_c ?>'],
              <?php
                if ($familias_servicios_realizados != 0) {
                    foreach ($familias_servicios_realizados as $row) { ?>
                    ['<?php echo $row['nombre']; ?>', <?php echo $row['cantidad_1'] ?>, <?php echo $row['cantidad_2']; ?>],
              <?php }} ?>
            ]);
            var chart = new google.visualization.BarChart(document.getElementById('chartFamiliasServiciosRealizados'));
            chart.draw(data, options);
        <?php } ?>
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        <?php if ($periodo == "") { ?>
            var options = {
              title: '',
              fontSize: 14,
              fontName: 'Open Sans',          
              is3D: true,
            };
            var data = google.visualization.arrayToDataTable([
              ['Task', 'cantidad'],
              <?php
                if ($clientes_con_carnets_vendidos!=0) {
                    foreach ($clientes_con_carnets_vendidos as $row) { if ($row['cantidad']>0) { ?>
                    ['<?php echo $row['tipo_carnet']." (".str_replace(",0", "", (string)number_format($row['cantidad'], 1, ",", "."))." clientes)"; ?>', <?php echo $row['cantidad'] ?>],
              <?php }}} ?>
            ]);    
            var chart = new google.visualization.PieChart(document.getElementById('chartClientesCarnetsVendidos'));
            chart.draw(data, options);
        <?php } else { ?>        
            var options = {
                title: 'Clientes con Carnets Vendidos',
                chartArea: {width: '50%',height: '100%'},
                hAxis: {
                    title: 'Cantidad',
                    minValue: 0
                },
                vAxis: {
                    title: 'Tipos de Carnets'
                }                
            };
            var data = google.visualization.arrayToDataTable([
              ['', '<?php echo $fecha_desde_f ?> / <?php echo $fecha_hasta_f ?>','<?php echo $fecha_desde_c ?> / <?php echo $fecha_hasta_c ?>'],
              <?php
                if ($clientes_con_carnets_vendidos != 0) {
                    foreach ($clientes_con_carnets_vendidos as $row) { ?>
                    ['<?php echo $row['nombre']; ?>', <?php echo $row['cantidad_1'] ?>, <?php echo $row['cantidad_2']; ?>],
              <?php }} ?>
            ]);
            var chart = new google.visualization.BarChart(document.getElementById('chartClientesCarnetsVendidos'));
            chart.draw(data, options);
        <?php } ?>
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        <?php if ($periodo == "") { ?>
            var options = {
              title: '',
              fontSize: 14,
              fontName: 'Open Sans',          
              is3D: true,
            };
            var data = google.visualization.arrayToDataTable([
              ['Task', 'cantidad'],
              <?php
                if ($clientes_con_carnets_vendidos_todos!=0) {
                    foreach ($clientes_con_carnets_vendidos_todos as $row) { if ($row['cantidad']>0) { ?>
                    ['<?php echo $row['tipo_carnet']." (".str_replace(",0", "", (string)number_format($row['cantidad'], 1, ",", "."))." clientes)"; ?>', <?php echo $row['cantidad'] ?>],
              <?php }}} ?>
            ]);    
            var chart = new google.visualization.PieChart(document.getElementById('chartClientesCarnetsVendidosTodos'));
            chart.draw(data, options);
        <?php } else { ?>        
            var options = {
                title: 'Clientes con Carnets Vendidos (todos los centros)',
                chartArea: {width: '50%',height: '100%'},
                hAxis: {
                    title: 'Cantidad',
                    minValue: 0
                },
                vAxis: {
                    title: 'Tipos de Carnets'
                }                
            };
            var data = google.visualization.arrayToDataTable([
              ['', '<?php echo $fecha_desde_f ?> / <?php echo $fecha_hasta_f ?>','<?php echo $fecha_desde_c ?> / <?php echo $fecha_hasta_c ?>'],
              <?php
                if ($clientes_con_carnets_vendidos_todos != 0) {
                    foreach ($clientes_con_carnets_vendidos_todos as $row) { ?>
                    ['<?php echo $row['nombre']; ?>', <?php echo $row['cantidad_1'] ?>, <?php echo $row['cantidad_2']; ?>],
              <?php }} ?>
            ]);
            var chart = new google.visualization.BarChart(document.getElementById('chartClientesCarnetsVendidosTodos'));
            chart.draw(data, options);
        <?php } ?>
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        <?php if ($periodo == "") { ?>
            var options = {
              title: '',
              fontSize: 14,
              fontName: 'Open Sans',          
              is3D: true,
            };
            var data = google.visualization.arrayToDataTable([
              ['Task', 'cantidad'],
              <?php if ($empleado_cliente_solo_con_este!=0) { ?>
                    ['<?php echo "Sólo quieren un empleado concreto"; ?>', <?php echo $empleado_cliente_solo_con_este[0]['solo_con_empleado']; ?>],
                    ['<?php echo "Indiferente"; ?>', <?php echo $empleado_cliente_solo_con_este[0]['indiferente']; ?>],
              <?php } ?>
            ]);    
            var chart = new google.visualization.PieChart(document.getElementById('chartClienteEmpleadoConcreto'));
            chart.draw(data, options);
        <?php } else { ?>
            var options = {
                title: 'Empleado Clientes "solo con este empleado"',
                chartArea: {width: '50%',height: '100%'},
                hAxis: {
                    title: '',
                    minValue: 0
                },
                vAxis: {
                    title: ''
                }                
            };
            var data = google.visualization.arrayToDataTable([
              ['', '<?php echo $fecha_desde_f ?> / <?php echo $fecha_hasta_f ?>','<?php echo $fecha_desde_c ?> / <?php echo $fecha_hasta_c ?>'],
              <?php if ($empleado_cliente_solo_con_este!=0) { ?>
                    ['<?php echo "Sólo quieren un empleado concreto"; ?>', <?php echo $empleado_cliente_solo_con_este[0]['solo_con_empleado']; ?>,<?php echo $empleado_cliente_solo_con_este_2[0]['solo_con_empleado']; ?>],
                    ['<?php echo "Indiferente"; ?>', <?php echo $empleado_cliente_solo_con_este[0]['indiferente']; ?>,<?php echo $empleado_cliente_solo_con_este_2[0]['indiferente']; ?>],
              <?php } ?>
            ]);    
            var chart = new google.visualization.BarChart(document.getElementById('chartClienteEmpleadoConcreto'));
            chart.draw(data, options);
        <?php } ?>
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        <?php if ($periodo == "") { ?>
            var options = {
              title: '',
              fontSize: 14,
              fontName: 'Open Sans',          
              is3D: true,
            };
            var data = google.visualization.arrayToDataTable([
              ['Task', 'cantidad'],
              <?php if ($clientes_activos!=0) { ?>
                    ['<?php echo "Activos (6 últimos meses)"; ?>', <?php echo $clientes_activos[0]['activos']; ?>],
                    ['<?php echo "Inactivos (6 últimos meses)"; ?>', <?php echo $clientes_activos[0]['inactivos']; ?>],
              <?php } ?>
            ]);    
            var chart = new google.visualization.PieChart(document.getElementById('chartClienteActivos'));
            chart.draw(data, options);
        <?php } else { ?>
            var options = {
                title: 'Clientes Activos / Inactivos',
                chartArea: {width: '50%',height: '100%'},
                hAxis: {
                    title: '',
                    minValue: 0
                },
                vAxis: {
                    title: ''
                }                
            };
            var data = google.visualization.arrayToDataTable([
              ['', '<?php echo $fecha_desde_f ?> / <?php echo $fecha_hasta_f ?>','<?php echo $fecha_desde_c ?> / <?php echo $fecha_hasta_c ?>'],
              <?php if ($clientes_activos!=0) { ?>
                    ['<?php echo "Activos (6 últimos meses)"; ?>', <?php echo $clientes_activos[0]['activos']; ?>,<?php echo $clientes_activos_2[0]['activos']; ?>],
                    ['<?php echo "Inactivos (6 últimos meses)"; ?>', <?php echo $clientes_activos[0]['inactivos']; ?>,<?php echo $clientes_activos_2[0]['inactivos']; ?>],
              <?php } ?>
            ]);    
            var chart = new google.visualization.BarChart(document.getElementById('chartClienteActivos'));
            chart.draw(data, options);
        <?php } ?>
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        <?php if ($periodo == "") { ?>
            var options = {
              title: '',
              fontSize: 14,
              fontName: 'Open Sans',          
              is3D: true,
            };
            var data = google.visualization.arrayToDataTable([
              ['Task', 'cantidad'],
              <?php if ($clientes_activos_todos!=0) { ?>
                    ['<?php echo "Activos (6 últimos meses)"; ?>', <?php echo $clientes_activos_todos[0]['activos']; ?>],
                    ['<?php echo "Inactivos (6 últimos meses)"; ?>', <?php echo $clientes_activos_todos[0]['inactivos']; ?>],
              <?php } ?>
            ]);    
            var chart = new google.visualization.PieChart(document.getElementById('chartClienteActivosTodos'));
            chart.draw(data, options);
        <?php } else { ?>
            var options = {
                title: 'Clientes Activos / Inactivos (todos los centros)',
                chartArea: {width: '50%',height: '100%'},
                hAxis: {
                    title: '',
                    minValue: 0
                },
                vAxis: {
                    title: ''
                }                
            };
            var data = google.visualization.arrayToDataTable([
              ['', '<?php echo $fecha_desde_f ?> / <?php echo $fecha_hasta_f ?>','<?php echo $fecha_desde_c ?> / <?php echo $fecha_hasta_c ?>'],
              <?php if ($clientes_activos_todos!=0) { ?>
                    ['<?php echo "Activos (6 últimos meses)"; ?>', <?php echo $clientes_activos_todos[0]['activos']; ?>, <?php echo $clientes_activos_todos_2[0]['activos']; ?>],
                    ['<?php echo "Inactivos (6 últimos meses)"; ?>', <?php echo $clientes_activos_todos[0]['inactivos']; ?>, <?php echo $clientes_activos_todos_2[0]['inactivos']; ?>],
              <?php } ?>
            ]);    
            var chart = new google.visualization.BarChart(document.getElementById('chartClienteActivosTodos'));
            chart.draw(data, options);
        <?php } ?>
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        <?php if ($periodo == "") { ?>
            var options = {
              title: '',
              fontSize: 14,
              fontName: 'Open Sans',          
              is3D: true,
            };
            var data = google.visualization.arrayToDataTable([
              ['Task', 'cantidad'],
              <?php if ($pago_euros_templos!=0) { ?>
                    ['<?php echo "Pagado en Euros"; ?>', <?php echo $pago_euros_templos[0]['euros']; ?>],
                    ['<?php echo "Pagado en Templos"; ?>', <?php echo $pago_euros_templos[0]['templos']; ?>],
              <?php } ?>
            ]);    
            var chart = new google.visualization.PieChart(document.getElementById('chartPagoEurosTemplos'));
            chart.draw(data, options);
        <?php } else { ?>        
            var options = {
                title: 'Pago Euros / Templos',
                chartArea: {width: '50%',height: '100%'},
                hAxis: {
                    title: 'Cantidad',
                    minValue: 0
                },
                vAxis: {
                    title: 'Cantidades'
                }                
            };
            var data = google.visualization.arrayToDataTable([
              ['', '<?php echo $fecha_desde_f ?> / <?php echo $fecha_hasta_f ?>','<?php echo $fecha_desde_c ?> / <?php echo $fecha_hasta_c ?>'],
              <?php if ($pago_euros_templos != 0) { ?>
                    ['<?php echo "Pagado en Euros"; ?>', <?php echo $pago_euros_templos[0]['euros']; ?>, <?php echo $pago_euros_templos_2[0]['euros']; ?>],
                    ['<?php echo "Pagado en Templos"; ?>', <?php echo $pago_euros_templos[0]['templos']; ?>, <?php echo $pago_euros_templos[0]['templos']; ?>],
              <?php } ?>
            ]);
            var chart = new google.visualization.BarChart(document.getElementById('chartPagoEurosTemplos'));
            chart.draw(data, options);
        <?php } ?>
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        <?php if ($periodo == "") { ?>
            var options = {
              title: '',
              fontSize: 14,
              fontName: 'Open Sans',          
              is3D: true,
            };
            var data = google.visualization.arrayToDataTable([
              ['Task', 'cantidad'],
              <?php if ($pago_euros_templos_todos!=0) { ?>
                    ['<?php echo "Pagado en Euros"; ?>', <?php echo $pago_euros_templos_todos[0]['euros']; ?>],
                    ['<?php echo "Pagado en Templos"; ?>', <?php echo $pago_euros_templos_todos[0]['templos']; ?>],
              <?php } ?>
            ]);    
            var chart = new google.visualization.PieChart(document.getElementById('chartPagoEurosTemplosTodos'));
            chart.draw(data, options);
        <?php } else { ?>        
            var options = {
                title: 'Pago Euros / Templos (todos los centros)',
                chartArea: {width: '50%',height: '100%'},
                hAxis: {
                    title: '',
                    minValue: 0
                },
                vAxis: {
                    title: ''
                }                
            };
            var data = google.visualization.arrayToDataTable([
              ['', '<?php echo $fecha_desde_f ?> / <?php echo $fecha_hasta_f ?>','<?php echo $fecha_desde_c ?> / <?php echo $fecha_hasta_c ?>'],
              <?php if ($pago_euros_templos_todos!=0) { ?>
                    ['<?php echo "Pagado en Euros"; ?>', <?php echo $pago_euros_templos_todos[0]['euros']; ?>, <?php echo $pago_euros_templos_todos_2[0]['euros']; ?>],
                    ['<?php echo "Pagado en Templos"; ?>', <?php echo $pago_euros_templos_todos[0]['templos']; ?>, <?php echo $pago_euros_templos_todos_2[0]['templos']; ?>]
              <?php } ?>
            ]);
            var chart = new google.visualization.BarChart(document.getElementById('chartPagoEurosTemplosTodos'));
            chart.draw(data, options);
        <?php } ?>
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        <?php if ($periodo == "") { ?>
            var options = {
              title: '',
              fontSize: 14,
              fontName: 'Open Sans',          
              is3D: true,
            };
            var data = google.visualization.arrayToDataTable([
              ['Task', 'euros'],
              <?php
                if ($facturacion_por_centro!=0) {
                    foreach ($facturacion_por_centro as $row) { if ($row['euros']>0) { ?>
                    ['<?php echo $row['nombre_centro']." (".str_replace(",0", "", (string)number_format($row['euros'], 1, ",", "."))." €)"; ?>', <?php echo $row['euros'] ?>],
              <?php }}} ?>
              <?php
                if ($facturacion_por_centro_central!=0) {
                    foreach ($facturacion_por_centro_central as $row) { if ($row['euros']>0) { ?>
                    ['<?php echo $row['nombre_centro']." (".str_replace(",0", "", (string)number_format($row['euros'], 1, ",", "."))." €)"; ?>', <?php echo $row['euros'] ?>],
              <?php }}} ?>
            ]);    
            var chart = new google.visualization.PieChart(document.getElementById('chartFacturacionPorCentros'));
            chart.draw(data, options);
        <?php } else { ?>        
            var options = {
                title: 'Facturación por Centros',
                chartArea: {width: '50%',height: '100%'},
                hAxis: {
                    title: 'Euros',
                    minValue: 0
                },
                vAxis: {
                    title: 'Centros'
                }                
            };
            var data = google.visualization.arrayToDataTable([
              ['', '<?php echo $fecha_desde_f ?> / <?php echo $fecha_hasta_f ?>','<?php echo $fecha_desde_c ?> / <?php echo $fecha_hasta_c ?>'],
              <?php
                if ($facturacion_por_centro != 0) {
                    foreach ($facturacion_por_centro as $row) { ?>
                    ['<?php echo $row['nombre']; ?>', <?php echo $row['cantidad_1'] ?>, <?php echo $row['cantidad_2']; ?>],
              <?php }} ?>
              <?php
                if ($facturacion_por_centro_central != 0) {
                    foreach ($facturacion_por_centro_central as $row) { ?>                    
                    ['<?php echo $row['nombre']; ?>', <?php echo $row['cantidad_1'] ?>, <?php echo $row['cantidad_2']; ?>],
              <?php }} ?>
            ]);
            var chart = new google.visualization.BarChart(document.getElementById('chartFacturacionPorCentros'));
            chart.draw(data, options);
        <?php } ?>
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        <?php if ($periodo == "") { ?>
            var options = {
              title: '',
              fontSize: 14,
              fontName: 'Open Sans',          
              is3D: true,
            };
            var data = google.visualization.arrayToDataTable([
              ['Task', 'euros'],
              <?php
                if ($horas_totales_trabajadas_por_centro!=0) {
                    foreach ($horas_totales_trabajadas_por_centro as $row) { if ($row['horas_trabajadas']>0) { ?>
                    ['<?php echo $row['nombre_centro']." (".str_replace(",0", "", (string)number_format($row['horas_trabajadas'], 1, ",", "."))." horas trabajadas)"; ?>', <?php echo $row['horas_trabajadas'] ?>],
              <?php }}} ?>
            ]);    
            var chart = new google.visualization.PieChart(document.getElementById('chartHorasTrabajadasPorCentro'));
            chart.draw(data, options);
        <?php } else { ?>        
            var options = {
                title: 'Horas Totales Trabajadas por Centro',
                chartArea: {width: '50%',height: '100%'},
                hAxis: {
                    title: 'Centros',
                    minValue: 0
                },
                vAxis: {
                    title: 'Horas'
                }                
            };
            var data = google.visualization.arrayToDataTable([
              ['', '<?php echo $fecha_desde_f ?> / <?php echo $fecha_hasta_f ?>','<?php echo $fecha_desde_c ?> / <?php echo $fecha_hasta_c ?>'],
              <?php
                if ($horas_totales_trabajadas_por_centro != 0) {
                    foreach ($horas_totales_trabajadas_por_centro as $row) { ?>
                    ['<?php echo $row['nombre']; ?>', <?php echo $row['cantidad_1'] ?>, <?php echo $row['cantidad_2']; ?>],
              <?php }} ?>              
            ]);
            var chart = new google.visualization.BarChart(document.getElementById('chartHorasTrabajadasPorCentro'));
            chart.draw(data, options);
        <?php } ?>
        // -----------------------------------------------------------------        
    }
</script>