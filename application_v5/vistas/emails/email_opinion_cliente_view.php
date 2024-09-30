<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Templo del Masaje">
  <meta name="author" content="Templo del Masaje">
  <title><?= SITETITLE ?></title>  
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
              <div id="wrapper" dir="ltr" style="background-color: #ffffff; margin: 0; padding: 70px 0 70px 0; -webkit-text-size-adjust: none !important; width: 100%;">
                      <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%"><tr>
<td align="center" valign="top">
                                              <div id="template_header_image">
                                                      <p style="margin-top: 0;"><img src="https://tienda.templodelmasaje.com/wp-content/uploads/2016/06/tdm-logo.jpg" alt="Templo del Masaje" style="border: none; display: inline-block; font-size: 14px; font-weight: bold; height: auto; outline: none; text-decoration: none; text-transform: capitalize; vertical-align: middle; margin-right: 10px;"></p>						</div>
                                <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="box-shadow: 0 1px 4px rgba(0,0,0,0.1) !important; background-color: #f5f5f5; border: 1px solid #dcdcdc; border-radius: 3px !important;">
<tr>
<td align="center" valign="top">
                                                        <!-- Header -->
                                                        <table border="0" cellpadding="0" cellspacing="0" width="750" id="template_header" style='background-color: #4f2020; border-radius: 3px 3px 0 0 !important; color: #ffffff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;'><tr>
<td id="header_wrapper" style="padding: 36px 48px; display: block;">
                                                                                <h1 style='color: #ffffff; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; font-size: 30px; font-weight: 300; line-height: 150%; margin: 0; text-align: center; text-shadow: 0 1px 0 #724d4d;'>DÉJANOS TU OPINIÓN</h1>
                                                                        </td>
                                                                </tr></table>
<!-- End Header -->
</td>
                                                      </tr>
<tr>
<td align="center" valign="top">
                                                                      <!-- Body -->
                                                                      <table border="0" cellpadding="0" cellspacing="0" width="750" id="template_body"><tr>
<td valign="top" id="body_content" style="background-color: #fdfdfd;">
                                                                                              <!-- Content -->
                                                                                              <table border="0" cellpadding="20" cellspacing="0" width="100%"><tr>
<td valign="top" style="padding: 48px 48px 0;">
                                                                                                                      <div id="body_content_inner" style='color: #737373; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; font-size: 14px; line-height: 150%; text-align: left;'>
<p style="margin: 0 0 16px;">
<p>
        Hola <?php echo $cliente[0]['nombre']; ?>,
      </p>
      <p>
        Nos gustaría saber qué tal ha ido tu cita con nosotros.
      </p>
      <p>
        Si hay algo que mejorar o que pudimos haber hecho de otra forma, por favor envíanos un email a <a href="mailto:info@templodelmasaje.com">info@templodelmasaje.com</a>. <br><strong>Este email llegará a las personas responsables del centro y se tomarán las medidas oportunas con la máxima discreción.</strong>
      </p>
      <p>Por otro lado si todo ha ido bien, nos gustaría que nos dejaras una reseña en Google con tu puntuación. No te llevará más de dos minutos.</p>
      <!-- GOYA -->
      <?php if ($this->session->userdata('id_centro_usuario') == 3) { ?>
      <a href="https://www.google.es/search?q=templo+del+masaje+goyao&oq=templo+del+masaje+goya">Poner reseña en Google</a>
      <?php } ?>
      <!-- POZUELO -->
      <?php if ($this->session->userdata('id_centro_usuario') == 4) { ?>
      <a href="https://www.google.es/search?q=templo+del+masaje+pozuelo&oq=templo+del+masaje+pozuelo">Poner reseña en Google</a>
      <?php } ?>
      <!-- BETANZOS -->
      <?php if ($this->session->userdata('id_centro_usuario') == 6) { ?>
      <a href="https://www.google.es/search?q=templo+del+masaje+betanzos">Poner reseña en Google</a>
      <?php } ?>
      <!-- PRINCESA -->
      <?php if ($this->session->userdata('id_centro_usuario') == 7) { ?>
      <a href="https://www.google.es/search?q=templo+del+masaje+princesa&oq=templo+del+masaje+princesa">Poner reseña en Google</a>
      <?php } ?>
      <!-- ARTURO SORIA -->
      <?php if ($this->session->userdata('id_centro_usuario') == 9) { ?>
      <a href="https://www.google.es/search?q=templo+del+masaje+arturo+soria&oq=templo+del+masaje+arturo+soria">Poner reseña en Google</a>
      <?php } ?>
      <!-- VELAZQUEZ -->
      <?php if ($this->session->userdata('id_centro_usuario') == 10) { ?>
      <?php } ?>
      <br><br>
       <img src="https://www.templodelmasaje.com/wp-content/uploads/2018/08/google-opinion.jpg" width="600">
</P>
</div>
              </div>
              </td>
              </tr></table>
<!-- End Content -->
</td>
              </tr></table>
<!-- End Body -->
</td>
      </tr>
<tr>
<td align="center" valign="top">
                                                                      <!-- Footer -->
                                                                      <table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer"><tr>
<td valign="top" style="padding: 0; -webkit-border-radius: 6px;">
                                                                                              <table border="0" cellpadding="10" cellspacing="0" width="100%"><tr>
<td colspan="2" valign="middle" id="credit" style="padding: 0 48px 48px 48px; -webkit-border-radius: 6px; border: 0; color: #957979; font-family: Arial; font-size: 12px; line-height: 125%; text-align: center;">
                                                                                                                      <p><a href="https://clientes.templodelmasaje.com">Gestiona tus citas en Templo del Masaje</a></p>
                                                                                                              </td>
                                                                                                      </tr></table>
</td>
                                                                              </tr></table>
<!-- End Footer -->
</td>
                                                      </tr>
</table>
</td>
                              </tr></table>
</div>
      </body>
</html>