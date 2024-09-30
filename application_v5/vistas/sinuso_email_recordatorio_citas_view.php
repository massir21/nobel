<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">    
  <title><?= SITETITLE ?></title>
  <style>
  </style>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
		<div id="wrapper" dir="ltr" style="background-color: #ffffff; margin: 0; padding: 70px 0 70px 0; -webkit-text-size-adjust: none !important; width: 100%;">
			<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%"><tr>
<td align="center" valign="top">
						<div id="template_header_image">
							<p style="margin-top: 0;"><img src="<?php echo base_url();?>wp-content/uploads/2016/06/tdm-logo.jpg" alt="Templo del Masaje" style="border: none; display: inline-block; font-size: 14px; font-weight: bold; height: auto; outline: none; text-decoration: none; text-transform: capitalize; vertical-align: middle; margin-right: 10px;"></p>						</div>
						<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="box-shadow: 0 1px 4px rgba(0,0,0,0.1) !important; background-color: #f5f5f5; border: 1px solid #dcdcdc; border-radius: 3px !important;">
<tr>
<td align="center" valign="top">
									<!-- Header -->
									<table border="0" cellpadding="0" cellspacing="0" width="750" id="template_header" style='background-color: #4f2020; border-radius: 3px 3px 0 0 !important; color: #ffffff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;'><tr>
<td id="header_wrapper" style="padding: 36px 48px; display: block;">
												<h1 style='color: #ffffff; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; font-size: 30px; font-weight: 300; line-height: 150%; margin: 0; text-align: center; text-shadow: 0 1px 0 #724d4d;'>RECORDATORIO DE CITAS</h1>
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
<p style="margin: 0 0 16px;"><strong>
        A continuación le recordamos sus próximas citas.
      </strong></p>
<p style="margin: 0 0 16px;">
<table style="width: 100%; font-size:0.8em; text-transform:capitalize;">
      <tr>
        <th>Fecha / Hora</th>
        <th>Centro</th>
        <th>Cliente</th>
        <th>Servicio</th>
        <th>Empleado</th>        
      </tr>
      <?php $total_euros=0; $total_templos=0; foreach ($citas as $row) { ?>
      <tr>
        <td>
          <?php echo $row['fecha_inicio']." ".$row['hora_inicio']; ?>
        </td>
        <td>
          <?php echo $row['nombre_centro']; ?>
        </td>
        <td style="text-transform: uppercase;">          
          <?php echo $row['cliente']; ?>          
        </td>
        <td style="text-transform:lowercase;">
          <?php echo $row['nombre_servicio']; ?>
        </td>
        <td>
          <?php echo $row['empleado']; ?>
          <?php if ($row['medaigual']==1) { echo " (Me da igual)"; } ?>
        </td>        
      </tr>
      <?php } ?>      
    </table>
</p>
    <div>
    	<h4>Lugar de la reserva</h4>
        <?php if ($citas[0]['id_centro']==6) { ?>
        <h3>Templo del Masaje Barrio del Pilar</h3>
        <p>Avenida de Betanzos 64, 28034 Madrid<br>
        Teléfono: <a href="tel:913733193">91 373 31 93</a></p>
        Email: <a href="mailto:betanzos@templodelmasaje.com">betanzos@templodelmasaje.com</a><br><br>        
        </p>
        <?php } ?>
        <?php if ($citas[0]['id_centro']==7) { ?>
        <h3>Templo del Masaje Princesa</h3>
        <p>Calle Evaristo San Miguel 19, 28008 Madrid<br>
        Teléfono: <a href="tel:915414193">91 541 41 93</a></p>
        Email: <a href="princesa@templodelmasaje.com">princesa@templodelmasaje.com</a><br><br>        
        </p>
        <?php } ?>
        <?php if ($citas[0]['id_centro']==3) { ?>
        <h3>Templo del Masaje Goya</h3>
        <p>Calle Povedilla 6, 28009 Madrid<br>
        Teléfono: <a href="tel:914010166">91 401 01 66</a></p>
        Email: <a href="goya@templodelmasaje.com">goya@templodelmasaje.com</a><br><br>        
        </p>
        <?php } ?>
        <?php if ($citas[0]['id_centro']==4) { ?>
        <h3>Templo del Masaje Pozuelo</h3>
        <p>Avenida de Europa 2, 28224 Pozuelo de Alarcón - Madrid<br>
        Teléfono: <a href="tel:918256789">91 825 67 89</a></p>
        Email: <a href="pozuelo@templodelmasaje.com">pozuelo@templodelmasaje.com</a><br><br>       
        </p>
        <?php } ?>
        <?php if ($citas[0]['id_centro']==9) { ?>
        <h3>Templo del Masaje Arturo Soria</h3>
        <p>Calle Bausá 27, Planta -1 dentro del Hotel Nuevo Madrid, 28033 Madrid<br>
        Teléfono: <a href="tel:919192568">91 919 25 68</a></p>
        Email: <a href="arturosoria@templodelmasaje.com">arturosoria@templodelmasaje.com</a><br><br>        
        </p>
        <?php } ?>
    </div>  
    <h3 style='color: #4f2020; display: block; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px; text-align: left;'>¿Y ahora qué?</h3>
    <p style="margin: 0 0 16px;">Si nada cambia, solo tienes que presentarte en el centro elegido en el día y la hora establecidos.
Todos los detalles de tu reserva han sido confirmados por el centro y están en su poder, así que no tienes que imprimir el comprobante de confirmación.
    </p>
     <h3 style='color: #4f2020; display: block; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px; text-align: left;'>¿Tengo que llevar algo?</h3>
    <p style="margin: 0 0 16px;">No. Ni códigos, ni papeles, ni ropa especial, ni toallas, ni nada. 
Solamente trae las ganas de disfrutar de tus servicios.
Para cualquier duda o consulta, por favor, contacta con tu centro.
    </p>
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