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
        PRÓXIMOS CUMPLEAÑOS DE CLIENTES - <?php echo $fecha ?>
      </p>
      <hr>
      <!-- ------------------------------------------------------------------------------ -->
      <!-- ----    CLIENTES SI SE EVIÓ EL EMAIL DEL CUMPLEAÑOS             -------------- -->
      <!-- ------------------------------------------------------------------------------ -->
      <p><b>Lista de clientes a los que se les ha enviado el email del cumpleaños</b></p>
      <table style="width: 100%;">
        <thead class="cabecera-tabla">
          <tr>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Email</th>
            <th>Año Nacimiento</th>
            <th>Edad</th>
          </tr>
        </thead>
        <tbody class="text-gray-700 fw-semibold">
          <?php if (isset($clientes_si_envio)) { if ($clientes_si_envio != 0) { $i=0; foreach ($clientes_si_envio as $key => $row) { ?>
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
              <?php echo $row['anno_nacimiento']; ?>
            </td>
            <td style="text-align: center;">
              <?php echo $row['edad']; ?>
            </td
          </tr>
          <?php $i++; ?>
          <?php } } } ?>
        </tbody>
      </table>
      <!-- ------------------------------------------------------------------------------ -->
      <!-- ----    CLIENTES SIN RECIBIR EL EMAIL                           -------------- -->
      <!-- ------------------------------------------------------------------------------ -->
      <p><b>Clientes a los que NO se les ha enviado el email del cumpleaños y motivo</b></p>
      <table style="width: 100%;">
        <thead class="cabecera-tabla">
          <tr>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Email</th>
            <th>Año Nacimiento</th>
            <th>Edad</th>
            <th>Motivo</th>
          </tr>
        </thead>
        <tbody class="text-gray-700 fw-semibold">
          <?php if (isset($clientes_no_envio)) { if ($clientes_no_envio != 0) { $i=0; foreach ($clientes_no_envio as $key => $row) { ?>
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
              <?php echo $row['anno_nacimiento']; ?>
            </td>
            <td style="text-align: center;">
              <?php echo $row['edad']; ?>
            </td>
            <td style="text-align: center;">
              <?php echo $row['motivo']; ?>
            </td>
          </tr>
          <?php $i++; ?>
          <?php } } } ?>
        </tbody>
      </table>
    </div>
  </body>
</html>
