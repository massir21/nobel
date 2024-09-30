<!DOCTYPE html>
<html lang="en-US">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="utf-8" />

	<style>
		@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap');

		body {
			font-family: 'Montserrat', sans-serif;
			font-size: 12pt
		}

		p {
			text-align: justify;
			margin-top: 0pt;
			margin-bottom: 0pt;
		}

		h1, h2 {
			text-align: center;
		}
		.Ttulo1Car { margin:0pt; text-indent:0pt; text-align:left; widows:0; orphans:0; font-family:Calibri; font-size:12pt; font-weight:bold; color:#000000 }
	</style>
</head>
<?php
$logo = FCPATH . '/assets_v5/media/logos/logo-dorado-sm.png';
$type = pathinfo($logo, PATHINFO_EXTENSION);
$data = file_get_contents($logo);
$logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>
<body>
<p style="text-align:center"><img src="<?= $logo ?>" style=" height:2.5cm;" /></p>
	<h1>
		CONSENTIMIENTO INFORMADO
	</h1>
	<h2>
		CLÁUSULA
	</h2>
</p>

			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				&#xa0;
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				&#xa0;
			</p>
			<?php if ($datos_firma[0]['edad']<18){ ?>
          <p>
              <span style="font-family:Arial">Yo, Don/Doña, </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['nombre_tutor']);  ?></span></strong>
              <span style="font-family:Arial">con DNI nº </span><strong><span style="font-family:Arial; "><?php echo $datos_firma[0]['dni_tutor']; ?></span></strong><span style="font-family:Arial">, en calidad de padre, madre, tutor/a o representante legal del paciente: </span>
          </p>
      <?php } ?>
	
	
	
	<P> es el <strong>Responsable del tratamiento </strong>de los datos personales del <strong>Interesado </strong>y le informa que estos datos serán tratados de conformidad con lo dispuesto en el Reglamento (UE) 2016/679, de 27 de abril de 2016, relativo a la protección de las personas físicas en lo que respecta al
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				tratamiento de datos personales y a la libre circulación de estos datos (Reglamento General de Protección de Datos o RGPD), por lo que se le facilita la siguiente información del tratamiento:
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<strong>&#xa0;</strong>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<P style="margin-top:0pt; margin-bottom:10pt; line-height:normal; widows:0; orphans:0"><strong>Fin del tratamiento:</strong></p>
				<p> Prestarle el servicio de asistencia sanitaria, seguimiento y evolución (mantenimiento de una historia clínica) así como finalidades derivadas de dicha prestación, incluyendo, entre otras, la gestión administrativa y de facturación y/o recordatorio de citas.
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<strong>&#xa0;</strong>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<P style="margin-top:0pt; margin-bottom:10pt; line-height:normal; widows:0; orphans:0"> <strong>Criterios de conservación de los datos</strong>:</p>
				<p> De conformidad con la normativa sanitaria vigente, la documentación se conservará al menos durante <strong>cinco años </strong>contados desde la fecha de alta de cada proceso asistencial. Cuando ya no sea necesario para tal fin, se suprimirá la información con medidas de seguridad adecuadas para garantizar la seudonimización de los datos o la destrucción total de los mismos.
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<strong>&#xa0;</strong>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<P style="margin-top:0pt; margin-bottom:10pt; line-height:normal; widows:0; orphans:0"><strong>Comunicación de los datos:</strong></P>
				<p> Informar sobre cesiones de datos (Sociedades Médicas, laboratorios, etc..) en cualquier otro caso, no se comunicarán los datos a terceros, salvo obligación legal o requerimiento judicial.</p>
			
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<strong>&#xa0;</strong>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<P style="margin-top:0pt; margin-bottom:10pt; line-height:normal; widows:0; orphans:0"><strong>Derechos que asisten al Interesado: </strong></p>
				<p>Derecho a retirar el consentimiento en cualquier momento, derecho de acceso, rectificación, portabilidad y supresión de sus datos y a la limitación u oposición a su tratamiento, así como el derecho a presentar una reclamación ante la Autoridad de Control
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				(<a href="www.agpd.es" style="text-decoration:none"><span style="color:#0000ff">www.agpd.es</span></a>) si considera que el tratamiento no se ajusta a la normativa vigente.
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<strong>&#xa0;</strong>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<P style="margin-top:0pt; margin-bottom:10pt; line-height:normal; widows:0; orphans:0"> <strong>Datos de contacto para ejercer sus derechos: </strong></p>
				<p>Podrá ejercitar sus derechos enviando su solicitud junto con un documento acreditativo de su identidad a:
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<strong><em>&#xa0;</em></strong>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<strong><em>&#xa0;</em></strong>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<strong>DATOS DE CONTACTO DEL RESPONSABLE</strong>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
                <?php if ($direccion_centro != null){ ?>
                    <strong>Dirección postal (datos de la empresa): <span style="color:#181717"><?php if (isset($direccion_centro)){ echo $direccion_centro; } else { echo $datos_firma[0]['direccion_centro']; } ?></span></strong><br/>
                <?php } ?>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<strong><em>Correo electrónico: administracion@clinicadentalnobel.es </em></strong>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				&#xa0;
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				&#xa0;
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				Mediante el presente documento se cumple con el deber de información legal exigido por la normativa de protección de datos y con su firma otorga su consentimiento para el tratamiento de sus datos con los fines arriba expuestos.
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				&#xa0;
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<strong><em>&#xa0;</em></strong>
			</p>

            <div style="font-size: 12px; text-align: center;">
    <p><strong>Fdo.: El paciente o (representante legal)</strong></p>
    <p></p>
    <p></p>
    <?php if ($datos_firma[0]['firma'] != "ONLINE" and $datos_firma[0]['firma'] != "") { 
      //$fuente=RUTA_SERVIDOR . "/recursos/consentimientos/" . $datos_firma[0]['firma'];
      
      if(file_exists(RUTA_SERVIDOR . "/recursos/consentimientos/" . $datos_firma[0]['firma'])) 
        $fuente=RUTA_SERVIDOR . "/recursos/consentimientos/" . $datos_firma[0]['firma'];
      else
        $fuente=RUTA_SERVIDOR . "/recursos/firmas_lopd/" . $datos_firma[0]['firma'];

      $imagenBase64 = "data:image/png;base64," . base64_encode(file_get_contents($fuente));
      ?>
      <div style="margin-top: 6px;">
         <img src="<?php echo $imagenBase64 ?>" style="height: 35mm;" />
      </div>
    <?php } else { ?>
      Por firmar.
    <?php } ?>
    <br>
    </body>
  </div>
</html>