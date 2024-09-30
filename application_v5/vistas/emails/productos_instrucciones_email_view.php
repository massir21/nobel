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
        Gracias por comprar en nuestra tienda. A continuación te pasamos
        las instrucciones de uso de los productos que nos acabas de comprar:        
      </p>
      <p>
        <?php echo $instrucciones; ?>
      </p>
      <p>
        Si necesitas cualquier otra información, por favor, contáctanos.
      </p>
    </div>
  </body>
</html>