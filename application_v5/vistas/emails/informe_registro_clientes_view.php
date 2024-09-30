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
      .fondo-fila-2 {
        background-color: #ddd;
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
      <?php if (isset($clientes_registrados)) { if ($clientes_registrados != 0) { ?>
      <p class="titulo-informe">        
        CLIENTES REGISTRADOS EN LAS ÚLTIMAS 12 HORAS
      </p>        
      <hr>
      <table style="width: 100%;">
        <thead class="cabecera-tabla">
          <tr>
            <th>Nombre</th>
            <th>Apellidos</th>            
            <th>Email</th>
            <th>Teléfono</th>
            <th>Fecha Creación</th>
          </tr>
        </thead>
        <tbody class="text-gray-700 fw-semibold">
          <?php $i=0; foreach ($clientes_registrados as $row) { ?>
            <?php if ($i % 2 == 0) { ?>
            <tr>
            <?php } else { ?>
            <tr class="fondo-fila">
            <?php } ?>
              <td style="text-align: left;">
                <?php echo strtoupper($row['nombre']); ?>
              </td>
              <td style="text-align: left;">
                <?php echo strtoupper($row['apellidos']); ?>
              </td>
              <td style="text-align: left;">
                <?php echo $row['email']; ?>
              </td>
              <td style="text-align: center;">
                <?php echo $row['telefono']; ?>
              </td>
              <td style="text-align: center;">
                <?php echo $row['fecha_creacion']; ?>
              </td>
            </tr>
            <?php $i++; ?>          
          <?php } ?>          
        </tbody>
      </table>
      <?php } } ?>
    <?php if (isset($clientes_registrados_verificados)) { if ($clientes_registrados_verificados != 0) { ?>      
      <p class="titulo-informe">        
        CLIENTES REGISTRADOS EN LAS ÚLTIMAS 12 HORAS (TELÉFONOS IGUALES)
      </p>        
      <hr>
      <table style="width: 100%;">
        <thead class="cabecera-tabla">
          <tr>
            <th>Nombre</th>
            <th>Apellidos</th>            
            <th>Email</th>
            <th>Teléfono</th>
            <th>Fecha Creación</th>
          </tr>
        </thead>
        <tbody class="text-gray-700 fw-semibold">
        <?php foreach ($clientes_registrados_verificados as $row) { $sw=0; ?>
          <?php if (isset($clientes_telefonos_iguales)) { if ($clientes_telefonos_iguales != 0) { foreach ($clientes_telefonos_iguales as $cliente) { ?>
            <?php if ($cliente[0]['id_cliente_principal'] == $row['id_cliente']) { $sw=1; } ?>
          <?php } } } ?>
          <?php if ($sw==1) { ?>
          <tr style="font-weight: bold;">
            <td style="text-align: left;">
              <?php echo strtoupper($row['nombre']); ?>
            </td>
            <td style="text-align: left;">
              <?php echo strtoupper($row['apellidos']); ?>
            </td>
            <td style="text-align: left;">
              <?php echo $row['email']; ?>
            </td>
            <td style="text-align: center;">
              <?php echo $row['telefono']; ?>
            </td>
            <td style="text-align: center;">
              <?php echo $row['fecha_creacion']; ?>
            </td>
          </tr>
          <?php } ?>
            <?php if (isset($clientes_telefonos_iguales)) { if ($clientes_telefonos_iguales != 0 && $sw==1) { foreach ($clientes_telefonos_iguales as $cliente) { ?>
              <?php if ($cliente[0]['id_cliente_principal'] == $row['id_cliente']) { ?>
                <tr class="fondo-fila-2">          
                  <td class="text-end">                    
                    <?php echo strtoupper($cliente[0]['nombre']); ?>
                  </td>
                  <td class="text-end">
                    <?php echo strtoupper($cliente[0]['apellidos']); ?>
                  </td>
                  <td style="text-align: left;">
                    <?php echo $cliente[0]['email']; ?>
                  </td>
                  <td style="text-align: center;">
                    <?php echo $cliente[0]['telefono']; ?>
                  </td>
                  <td style="text-align: center;">
                    <?php echo $cliente[0]['fecha_creacion']; ?>
                  </td>
                </tr>
              <?php } ?>
            <?php } } } ?>
        <?php } ?>          
        </tbody>
      </table>
      <?php } } ?>          
      <?php if (isset($clientes_fechas_distintas)) { if ($clientes_fechas_distintas != 0) { ?>
      <p class="titulo-informe">        
        CLIENTES FECHA DE ACTIVACIÓN Y FECHA DE CREACIÓN DIFERENTES
      </p>        
      <hr>
      <table style="width: 100%;">
        <thead class="cabecera-tabla">
          <tr>
            <th>Nombre</th>
            <th>Apellidos</th>            
            <th>Email</th>
            <th>Teléfono</th>
            <th>Fecha Creación</th>
            <th>Fecha Activación</th>
          </tr>
        </thead>
        <tbody class="text-gray-700 fw-semibold">
        <?php $i=0; foreach ($clientes_fechas_distintas as $row) { ?>
          <?php if ($i % 2 == 0) { ?>
          <tr>
          <?php } else { ?>
          <tr class="fondo-fila">
          <?php } ?>
            <td style="text-align: left;">
              <?php echo strtoupper($row['nombre']); ?>
            </td>
            <td style="text-align: left;">
              <?php echo strtoupper($row['apellidos']); ?>
            </td>
            <td style="text-align: left;">
              <?php echo $row['email']; ?>
            </td>
            <td style="text-align: center;">
              <?php echo $row['telefono']; ?>
            </td>
            <td style="text-align: center;">
              <?php echo $row['fecha_creacion']; ?>
            </td>
            <td style="text-align: center;">
              <?php echo $row['fecha_activacion']; ?>
            </td>
          </tr>
          <?php $i++; ?>          
        <?php }?>          
        </tbody>
      </table>
      <?php } } ?>
    </div>
  </body>
</html>