<!doctype html>
<html lang="es">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <style>
      body {
        font-family: Verdana;
        font-size: 12px;
      }        
      table {
        font-family: Verdana;
        font-size: 12px;
      }      
      .fondo-fila {
        background-color: #fff1d5;
      }      
      th {
        height: 30px;            
      }
      td {
        padding: 5px;
      }
      .label {
        background: #aaa;
        padding: 2px;
      }
      .titulo-informe {
        text-transform: uppercase;
        text-align: center;
        font-size: 16px;
        font-weight: bold;
      }
      .titulo-seccion {
        text-transform: uppercase;
        font-size: 14px;
        margin-top: 30px;
        font-weight: bold;
      }      
      .cabecera-tabla {
        background: #412210;
        color: #fff;
      }
      .sin-datos {
        text-align: left;
      }
    </style>
    <title><?= SITETITLE ?></title>
  </head>
  <body>
    <div style="padding: 30px;">      
      <p class="titulo-informe">        
        INFORME PARA <?php echo $centro[0]['nombre_centro'] ?> - <?php echo $fecha_dia ?>        
      </p>        
      <hr>
      <!-- ------------------------------------------------------------------------------ -->
      <!-- ------------------------------------------------------------------------------ -->
      <!-- ------------------------------------------------------------------------------ -->
      <p class="titulo-seccion">
         Pagos con Tarjetas en el centro.
      </p>
      <?php if (isset($tarjetas)) { if ($tarjetas != 0) { ?>
      <table style="width: 100%;">
        <thead class="cabecera-tabla">
          <tr>            
            <th>Fecha / Hora</th>
            <th>Cliente</th>
            <!-- <th>Nº Carnet</th> -->
            <th>Empleado</th>            
            <th>Pagado</th>
            <!-- <th>Precio Servicios</th> -->            
          </tr>
        </thead>
        <tbody class="text-gray-700 fw-semibold">
          <?php if (isset($tarjetas)) { if ($tarjetas != 0) { $i=0; foreach ($tarjetas as $key => $row) { ?>
          <?php if ($i % 2 == 0) { ?>
          <tr>
          <?php } else { ?>
          <tr class="fondo-fila">
          <?php } ?>
            <td style="text-align: center; font-size: 11px;">                
                <?php echo $row['fecha_hora_concepto_ddmmaaaa_abrv']; ?><br>
                <?php echo $row['hora']; ?>                
            </td>
            <td style="text-align: left;">
                <?php echo $row['cliente']; ?>
            </td>
            <?php /*
            <td style="text-align: left;">
                <?php echo $row['carnet']; ?>
                <div style="text-transform: lowercase;">
                <?php echo $row['notas_carnet']; ?>
                </div>
            </td>
            */ ?>
            <td style="text-align: left;">              
                <?php echo $row['empleado']; ?>
            </td>                
            <td class="text-end">                    
                <?php if ($row['notas_pago_descuento']!="") { ?>
                    <span style="cursor: pointer; cursor: hand;" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="<?php echo $row['notas_pago_descuento']; ?>">
                <?php } ?>
                <?php if ($row['tipo_pago']!="#templos") { ?>
                    <?php echo number_format($row['importe_total_final'], 2, ',', '.')."€"; ?>
                    <?php if ($row['descuento_euros']>0) { echo "<br><span class='label label-default' style='font-size: 11px; color: #fff;'>Dto. ".round($row['descuento_euros'],2)." €</span>"; } ?>
                    <?php if ($row['descuento_porcentaje']>0) { echo "<br><span class='label label-default' style='font-size: 11px; color: #fff;'>Dto. ".round($row['descuento_porcentaje'],2)."%</span>"; }?>
                <?php } else { ?>
                    <?php echo "0,00€"; ?>
                <?php } ?>
                <?php if ($row['notas_pago_descuento']!="") { ?>
                    </span>
                <?php } ?>
            </td>
            <?php /*
            <td style="text-align: center;">
                <?php echo number_format($row['precio_servicios'], 2, ',', '.')."€"; ?>                    
            </td>  
            */ ?>        
          </tr>
          <?php $i++; ?>          
          <?php } } } ?>          
        </tbody>
      </table>
      <?php } else { ?>
      <p>No hay datos para esta sección</p>
      <?php }} ?>
      <!-- Pagados por la Web Clientes -->
      <p class="titulo-seccion">
         Pagos con Tarjetas Web Clientes.
      </p>
      <?php if (isset($tarjetas_web)) { if ($tarjetas_web != 0) { ?>
      <table style="width: 100%;">
        <thead class="cabecera-tabla">
          <tr>            
            <th>Fecha / Hora</th>
            <th>Cliente</th>
            <!-- <th>Nº Carnet</th> -->
            <th>Empleado</th>            
            <th>Pagado</th>
            <!-- <th>Precio Servicios</th> -->            
          </tr>
        </thead>
        <tbody class="text-gray-700 fw-semibold">
          <?php if (isset($tarjetas_web)) { if ($tarjetas_web != 0) { $i=0; foreach ($tarjetas_web as $key => $row) { ?>
          <?php if ($i % 2 == 0) { ?>
          <tr>
          <?php } else { ?>
          <tr class="fondo-fila">
          <?php } ?>
            <td style="text-align: center; font-size: 11px;">                
                <?php echo $row['fecha_hora_concepto_ddmmaaaa_abrv']; ?><br>
                <?php echo $row['hora']; ?>                
            </td>
            <td style="text-align: left;">
                <?php echo $row['cliente']; ?>
            </td>
            <?php /*
            <td style="text-align: left;">
                <?php echo $row['carnet']; ?>
                <div style="text-transform: lowercase;">
                <?php echo $row['notas_carnet']; ?>
                </div>
            </td>
            */ ?>
            <td style="text-align: left;">              
                <?php echo $row['empleado']; ?>
            </td>                
            <td class="text-end">                    
                <?php if ($row['notas_pago_descuento']!="") { ?>
                    <span style="cursor: pointer; cursor: hand;" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="<?php echo $row['notas_pago_descuento']; ?>">
                <?php } ?>
                <?php if ($row['tipo_pago']!="#templos") { ?>
                    <?php echo number_format($row['importe_total_final'], 2, ',', '.')."€"; ?>
                    <?php if ($row['descuento_euros']>0) { echo "<br><span class='label label-default' style='font-size: 11px; color: #fff;'>Dto. ".round($row['descuento_euros'],2)." €</span>"; } ?>
                    <?php if ($row['descuento_porcentaje']>0) { echo "<br><span class='label label-default' style='font-size: 11px; color: #fff;'>Dto. ".round($row['descuento_porcentaje'],2)."%</span>"; }?>
                <?php } else { ?>
                    <?php echo "0,00€"; ?>
                <?php } ?>
                <?php if ($row['notas_pago_descuento']!="") { ?>
                    </span>
                <?php } ?>
            </td>
            <?php /*
            <td style="text-align: center;">
                <?php echo number_format($row['precio_servicios'], 2, ',', '.')."€"; ?>                    
            </td>  
            */ ?>      
          </tr>
          <?php $i++; ?>          
          <?php } } } ?>          
        </tbody>
      </table>
      <?php } else { ?>
      <p>No hay datos para esta sección</p>
      <?php }} ?>
      <!-- Fin pagado por la Web Clientes -->
    </div>
  </body>
</html>