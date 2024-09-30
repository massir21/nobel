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
		LIMPIEZA DENTAL
	</h2>
    <p>
              <span style="font-family:Arial">Yo, Don/Doña, </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['nombre_tutor']);  ?></span></strong>
              <span style="font-family:Arial">con DNI nº </span><strong><span style="font-family:Arial; "><?php echo $datos_firma[0]['dni_tutor']; ?></span></strong><span style="font-family:Arial">, en calidad de padre, madre, tutor/a o representante legal del paciente: </span>
          </p>
		  <p>Don/Doña, <?php echo strtoupper($datos_firma[0]['cliente']); ?> de <?php echo $datos_firma[0]['edad']; ?> años de edad, con domicilio en <?php echo $datos_firma[0]['direccion']; ?> y DNI <?php echo $datos_firma[0]['dni']; ?></p>
		  <div style="font-size: 16px; margin-top:10pt; line-height:normal; text-align: center;">
    <b>DECLARO</b><br><br>
</div>

<ul>
    <li style="margin-top:10pt; margin-bottom:5pt; line-height:normal;">
        <span style="font-family:Arial">El propósito principal del tratamiento tiene como finalidad la eliminación de los factores irritativos e infecciosos locales (cálculo dental o masa mineralizada adherida a la superficie de los dientes y su margen gingival libre y placa bacteriana) para conseguir el mantenimiento de los dientes en tiempo, función y estética, evitando movilidad, pérdida de hueso y caída de los dientes.</span>
    </li>
    <li style="margin-top:5pt; margin-bottom:5pt; line-height:normal;">
        <span style="font-family:Arial">El sarro o cálculo es un factor irritante que en el caso de no eliminarlo dará lugar a una inflamación de las encías (gingivitis y periodontitis) que se manifestará con dolor, hinchazón y sangrado de las mismas y que llega a afectar a los tejidos de soporte de los dientes.</span>
    </li>
    <li style="margin-top:5pt; margin-bottom:5pt; line-height:normal;">
        <span style="font-family:Arial">La técnica se lleva a cabo mediante un aparato de ultrasonidos que produce una vibración que hace desprender el cálculo del diente o bien de forma manual mediante instrumental específico. Aunque no es una técnica dolorosa, si puede resultar en algunos casos molesta dependiendo de la sensibilidad dentaria de cada persona.</span>
    </li>
    <li style="margin-top:5pt; margin-bottom:5pt; line-height:normal;">
        <span style="font-family:Arial">También se realizará la remoción de manchas dentales extrínsecas y pulido especializado con pasta abrasiva, o bien mediante un aeropulidor con el sistema a base de bicarbonato de sodio a presión.</span>
    </li>
    <li style="margin-top:5pt; margin-bottom:5pt; line-height:normal;">
        <span style="font-family:Arial">El tratamiento, en principio, no necesita la administración de anestesia local. En caso de necesitarla, se podrá administrar vía tópica, administrando benzocaína en gel difundiéndose a través de la mucosa, para aliviar levemente el dolor provocado por la sensibilidad dental.</span>
    </li>
</ul>

<h3>
		DECLARACIONES Y FIRMAS
	</h3>
	
	<p>Se me ha dado tiempo suficiente para leer la información precedente que describe el Limpieza dental . Comprendo los beneficios, riesgos e inconvenientes asociados al tratamiento. <br><br>He sido suficientemente informado y he tenido la oportunidad de hacer preguntas y discutir las preocupaciones sobre el tratamiento de limpiea dental con los productos con mi dentista que me ofrece el tratamiento.  <br><br>Por la presente doy mi consentimiento para el tratamiento de Limpieza dental que ha sido prescrito por mi dentista.
			</p>
			
			<p>
				Autorizo a mi dentista a divulgar mis registros, incluyendo, pero no limitándose a radiografías, historial médico, fotografías, modelos de yeso o impresiones de dientes, prescripciones, diagnósticos y otros registros de tratamiento al proveedor y compañías asociadas. <br><br>Esto es con el propósito de investigar y revisar mi caso, en lo que se refiere al tratamiento de ortodoncia con sus producto(s) y/o con fines educativos/de investigación. 
			</p>
			<p>
				Por la presente consiento en la divulgación de lo anterior. No buscaré, ni nadie en mi nombre, daños o remedios legales, equitativos o monetarios por dicha divulgación. Una fotocopia de este consentimiento se considerará tan efectiva y válida como un original. <br><br>
			</p>
			<p>
				He leído, entendido y estoy de acuerdo con los términos establecidos en este Consentimiento, tal y como se indica con mi firma a continuación. 
			</p>
	
		Declaro que el/la facultativo/a, Dr/Dra
		<b>
			<?php echo strtoupper($datos_doctor[0]['nombre']) . " " . strtoupper($datos_doctor[0]['apellidos']); ?>
		</b>
		me ha explicado de forma satisfactoria qué es, cómo se realiza y para qué sirve esta exploración/intervención.
		<br><br>También me ha explicado los riesgos existentes, las posibles molestias o complicaciones, que éste es el
		procedimiento más adecuado para mi situación clínica actual, y las consecuencias previsibles de su no
		realización.<br><br>
		He comprendido perfectamente todo lo anterior, he podido aclarar las dudas planteadas, y
		<b>doy mi consentimiento</b>
		para que me realicen dicha exploración/intervención. <br><br>
		He recibido copia del presente documento. Sé que puedo retirar este consentimiento cuando lo desee.<br><br>
	</p>

		<p>
		En <b>Madrid</b> a día 	<b>	<?php echo date('d/m/Y'); ?><br><br>
		</b>
	</p>

	<div>
		<p>Fdo.: El paciente o (representante legal)</p><br><br>

		<?php if ($datos_firma[0]['firma'] != "ONLINE" and $datos_firma[0]['firma'] != "") {
			// $fuente=RUTA_SERVIDOR . "/recursos/consentimientos/" . $datos_firma[0]['firma'];
		
			if (file_exists(RUTA_SERVIDOR . "/recursos/consentimientos/" . $datos_firma[0]['firma']))
				$fuente = RUTA_SERVIDOR . "/recursos/consentimientos/" . $datos_firma[0]['firma'];
			else
				$fuente = RUTA_SERVIDOR . "/recursos/firmas_lopd/" . $datos_firma[0]['firma'];

			$imagenBase64 = "data:image/png;base64," . base64_encode(file_get_contents($fuente));
			?>
			<div style="margin-top: 6px;">
				<img src="<?php echo $imagenBase64 ?>" style="height: 35mm;" />
			</div>
		<?php } else { ?>
			
		<?php } ?>
	</div>

</body>

</html>