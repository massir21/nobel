<!doctype html>
<html lang="es">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <style>
      body {
        font-family: Calibri, Verdana;
        font-size: 12px;
      }        
    </style>
    <title><?= SITETITLE ?></title>
  </head>
  <body>
    <div style="padding: 30px;">      
      <p>Hola <?php echo $nombre_cliente ?>,</p>
      <p>Gracias por comprar en nuestra tienda.
      Por favor valora qué tal te ha ido con los servicios:</p>
      <?php if ($compra != 0) { foreach ($compra as $row) { ?>
        <a href="<?php echo $row['link_encuesta']; ?>"><?php echo $row['descripcion']; ?></a>
      <?php }} ?>
      <p>Un saludo.</p>
      <small>AVISO LEGAL: Este mensaje y sus archivos adjuntos van dirigidos exclusivamente a su destinatario, pudiendo contener información confidencial sometida a secreto profesional. No está permitida su comunicación, reproducción o distribución sin la autorización expresa de Si usted no es el destinatario final, por favor elimínelo e infórmenos por esta vía.
      PROTECCIÓN DE DATOS: De conformidad con lo dispuesto en las normativas vigentes en protección de datos personales, el Reglamento (UE) 2016/679 de 27 de abril de 2016 (GDPR) y la Ley Orgánica (ES) 15/1999 de 13 de diciembre (LOPD), le informamos que los datos personales y dirección de correo electrónico, recabados del propio interesado o de fuentes públicas, serán tratados bajo la responsabilidad de Ponme 2 Masajes S.L.
      para el envío de comunicaciones sobre nuestros productos y servicios y se conservarán mientras exista un interés mutuo para ello. Los datos no serán comunicados a terceros, salvo obligación legal. Le informamos que puede ejercer los derechos de acceso, rectificación, portabilidad y supresión de sus datos y los de limitación y oposición a su tratamiento dirigiéndose a Ponme 2 Masajes, Avenida de Betanzos 64 28034 Madrid Email: info@templodelmasaje.com si considera que el tratamiento no se ajusta a la normativa vigente, podrá presentar una reclamación ante la autoridad de control en www.agpd.es.
      </small>
    </div>
  </body>
</html>