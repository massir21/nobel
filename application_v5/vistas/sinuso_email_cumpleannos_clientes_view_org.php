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
      <p>
        Hola <?php echo $nombre_cliente ?>,
      </p>
      <p>
        ¡Nos gustaría ser los primeros en Felicitarte por tu Cumpleaños! Mañana será un día lleno de mensajes y llamadas con buenos deseos.
      </p>
      <p>
        Nos encantaría colaborar para que el descanso y el bienestar llenen tu día. Es por ello que tienes <strong><?php echo $descuento;?></strong> en el servicio que tú quieras para ti y un acompañante en tu próxima visita con el cupón <strong><?php echo $cupon_codigo;?></strong>* al hacer tu reserva online. 
      </p>
      <p>
          <small>* válido hasta el <?php echo $fecha_limite;?></small>
      </p>
      <p>
        Esperamos que disfrutes de tu día.
      </p>
    </div>
  </body>
</html>
