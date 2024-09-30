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
    CLÁUSULA INFORMATIVA DIRIGIDA A PACIENTES
	</h2>
            <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
                <span style="font-family:Arial">&#xa0;</span>
            </p>
            <?php if ($datos_firma[0]['edad']<18){ ?>
          <p>
              <span style="font-family:Arial">Yo, Don/Doña, </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['nombre_tutor']);  ?></span></strong>
              <span style="font-family:Arial">con DNI nº </span><strong><span style="font-family:Arial; "><?php echo $datos_firma[0]['dni_tutor']; ?></span></strong><span style="font-family:Arial">, en calidad de padre, madre, tutor/a o representante legal del paciente: </span>
          </p>
      <?php } ?>
      <p>Don/Doña, <?php echo strtoupper($datos_firma[0]['cliente']); ?> de <?php echo $datos_firma[0]['edad']; ?> años de edad, con domicilio en <?php echo $datos_firma[0]['direccion']; ?> y DNI <?php echo $datos_firma[0]['dni']; ?></p>
	
	  <diV style="font-size: 16px; text-align: center;">
      <b>DECLARO</b><br><br>
    </diV>
	
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<span style="font-family:Arial">&#xa0;</span>
			</p>
            <p style="margin-top:8pt; margin-bottom:4.9pt">
               <P>Estos datos serán tratados de conformidad con lo dispuesto en el Reglamento (UE) 2016/679, de 27 de abril de 2016, relativo a la protección de las personas físicas en lo que respecta al
                tratamiento de datos personales y a la libre circulación de estos datos (Reglamento General de Protección de Datos o RGPD), por lo que se le facilita la siguiente información del tratamiento:
            </p>
            <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
                <span style="font-family:Arial">&#xa0;</span>
            </p>
            <h4 style="margin-top:8pt; margin-bottom:4.9pt">
                Fin del tratamiento:</h4>
                <p style="font-weight: normal; color: inherit;">
    Prestarle el servicio de asistencia sanitaria, seguimiento y evolución (mantenimiento de una historia clínica) así como finalidades derivadas de dicha prestación, incluyendo, entre otras, la gestión administrativa y de facturación y/o recordatorio de citas.
</p>

            <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
                <span style="font-family:Arial">&#xa0;</span>
            </p>
            <h4 style="margin-top:8pt; margin-bottom:4.9pt">
                Criterios de conservación de los datos:</h4>
                <p style="font-weight: normal; color: inherit;">
    De conformidad con la normativa sanitaria vigente, la documentación se conservará al menos durante cinco años contados desde la fecha de alta de cada proceso asistencial. Cuando ya no sea necesario para tal fin, se suprimirá la información con medidas de seguridad adecuadas para garantizar la seudonimización de los datos o la destrucción total de los mismos.
</p>

            <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
                <span style="font-family:Arial">&#xa0;</span>
            </p>
            <h4 style="margin-top:8pt; margin-bottom:4.9pt">
                Comunicación de los datos: </h4>
                <p style="font-weight: normal; color: inherit;">
    Informar sobre cesiones de datos (Sociedades Médicas, laboratorios, etc..) En cualquier otro caso, no se comunicarán los datos a terceros, salvo obligación legal o requerimiento judicial.
</p>

            <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
                <span style="font-family:Arial">&#xa0;</span>
            </p>
            <h4 style="margin-top:8pt; margin-bottom:4.9pt">
                Derechos que asisten al Interesado:</h4>
                <p style="font-weight: normal; color: inherit;">
    Derecho a retirar el consentimiento en cualquier momento, derecho de acceso, rectificación, portabilidad y supresión de sus datos y a la limitación u oposición a su tratamiento, así como el derecho a presentar una reclamación ante la Autoridad de Control
    (www.agpd.es) si considera que el tratamiento no se ajusta a la normativa vigente.
</p>

            <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
                <span style="font-family:Arial">&#xa0;</span>
            </p>
            <h4 style="margin-top:8pt; margin-bottom:4.9pt">
                Datos de contacto para ejercer sus derechos: </h4>
                <p style="font-weight: normal; color: inherit;">
    Podrá ejercitar sus derechos enviando su solicitud junto con un documento acreditativo de su identidad a:
</p>

            <h3>DATOS DE CONTACTO DEL RESPONSABLE:</h3>
            <p style="margin-top:8pt; margin-bottom:4.9pt">
                <?php if ($direccion_centro != null){ ?>
                    <strong>Dirección postal (datos de la empresa): <span style="color:#181717"><?php if (isset($direccion_centro)){ echo $direccion_centro; } else { echo $datos_firma[0]['direccion_centro']; } ?></span></strong><br/>
                <?php } ?>
                <strong>Correo electrónico: administracion@clinicadentalnobel.es</strong>
            </p>

            <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
                <span style="font-family:Arial">&#xa0;</span>
            </p>
            <p style="margin-top:8pt; margin-bottom:4.9pt">
                Mediante el presente documento se cumple con el deber de información legal exigido por la normativa de protección de datos y con su firma otorga su consentimiento para el tratamiento de sus datos con los fines arriba expuestos.
            </p>
            
            <p>
		En <b>Madrid</b> a día 	<b>	<?php echo date('d/m/Y'); ?><br><br>
		</b>
	</p>
            <div style="font-size: 12px; text-align: center;">
    <p><strong>Fdo.: El paciente o (representante legal)</strong></p>
    
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