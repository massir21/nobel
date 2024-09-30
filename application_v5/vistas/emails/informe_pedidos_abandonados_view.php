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
        INFORME CITAS ONLINE ABANDONADAS - <?php echo $fecha ?>
      </p>        
      <hr>
      <!-- ------------------------------------------------------------------------------ -->
      <!-- ------------------------------------------------------------------------------ -->
      <!-- ------------------------------------------------------------------------------ -->      
      <table style="width: 100%;">
        <thead class="cabecera-tabla">
          <tr>
            <th>Fecha Cita</th>
            <th>Cliente (ID)</th>
            <th>Email</th>
            <th>Servicio</th>
            <th>Importe</th>            
            <th>Centro</th>
            <th>Empleado</th>            
            <th>Notas Cliente</th>
          </tr>
        </thead>
        <tbody class="text-gray-700 fw-semibold">
          <?php if (isset($pedidos)) { if ($pedidos != 0) { $i=0; foreach ($pedidos as $key => $row) { ?>
          <?php if ($i % 2 == 0) { ?>
          <tr>
          <?php } else { ?>
          <tr class="fondo-fila">
          <?php } ?>
            <td style="text-align: center;">
              <?php echo $row['fecha_hora_inicio']; ?>
            </td>
            <td style="text-align: left; text-transform: uppercase;">
              <?php if ($row['personas']==2) { ?>
                <?php echo $row['observaciones']; ?>
              <?php } else { ?>
                <?php echo $row['cliente']; ?>
              <?php } ?>
              <?php echo "<br>(ID: ".$row['id_cliente'].")"; ?>
            </td>
            <td style="text-align: left;">
              <?php echo $row['email']; ?>
            </td>
            <td style="text-align: left;">
              <?php echo $row['servicio']; ?>
            </td>
            <td class="text-end">
              <?php echo number_format($row['importe'], 2, ',', '.')."€"; ?>
            </td>
            <td style="text-align: center;">              
              <?php echo $row['centro']; ?>
            </td>                
            <td style="text-align: center;">              
              <?php echo $row['empleado']; ?>
              <?php if ($row['medaigual']==1) { ?>
                <br>(Me da igual)
              <?php } ?>                
            </td>
            <td style="text-align: left;">              
              <?php echo $row['notas_cliente']; ?>
            </td>                
          </tr>
          <?php $i++; ?>          
          <?php } } } ?>          
        </tbody>
      </table>      
    </div>
  </body>
</html>