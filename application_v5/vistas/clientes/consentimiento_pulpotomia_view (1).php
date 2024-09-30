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
		PULPOTOMÍA
	</h2>

<body>
<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
    <span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span>
</p>
<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
    <span style="font-family:Arial">
        Para satisfacción de los derechos del paciente como instrumento favorecedor del correcto uso de los Procedimientos Diagnósticos y Terapéuticos, y en cumplimiento dela Ley General de Sanidad y la ley 41/2002, se le presenta para su firma el siguiente documento:
    </span>
</p>

<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
    <span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span>
</p>
<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
    Yo, D/Dª <?php echo strtoupper($datos_firma[0]['cliente']); ?>&#xa0; como paciente,
</p>
<?php if ($datos_firma[0]['edad']<18){ ?>
    <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
        <span style="font-family:Arial">En caso de paciente menor de edad, impedido o incapacitado Yo, </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['nombre_tutor']);  ?></span></strong>
    </p>
    <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
        <span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span>
    </p>
    <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
        <span style="font-family:Arial">con DNI nº </span><strong><span style="font-family:Arial; "><?php echo $datos_firma[0]['dni_tutor']; ?></span></strong><span style="font-family:Arial"> , en calidad de padre, madre, tutor/a o representante legal del paciente </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['cliente']); ?></span></strong>
    </p>
    libre y voluntariamente, DECLARO:
<?php } ?>
<p style="margin-top:12.65pt; margin-left:0.5pt; margin-bottom:0.55pt; text-indent:-0.5pt">
    Que el/la Dr/Dra: <?php echo strtoupper($datos_doctor[0]['nombre'])." ". strtoupper($datos_doctor[0]['apellidos']); ?>
</p>
<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
    <span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span>
</p>
<p style="margin-top:0.55pt; margin-left:0.5pt; margin-bottom:0.55pt; text-indent:-0.5pt">
    Me ha explicado y he sido debidamente informado/a y en consecuencia AUTORIZO al mismo para que me sea realizado el procedimiento denominado PULPOTOMÍA.
</p>
<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
    <span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span>
</p>
<ul style="margin-top:0pt; margin-bottom:10pt; line-height:normal; widows:0; orphans:0">
    <li style="margin-top:0pt; margin-bottom:5pt; line-height:normal; widows:0; orphans:0">
        <span style="font-family:Arial">El objetivo del tratamiento de pulpotomía es eliminar parcialmente el tejido pulpar del diente seguido de una cobertura de la entrada de la raíz con sustancias medicamentosas.</span>
    </li>
    <li style="margin-top:0pt; margin-bottom:5pt; line-height:normal; widows:0; orphans:0">
        <span style="font-family:Arial">La finalidad es conservar funcionalmente un diente cuya pulpa está parcialmente dañada y mantener el diente en la boca del niño/a; evitar evolucionar a una infección más extensa de la pulpa, hueso y tejidos perirradiculares. Se puede devolver la función al diente al poder ser restaurado de forma directa o indirecta después de realizar la pulpotomía. Además, evitamos posibles afecciones del diente permanente que está en íntimo contacto con el temporal.</span>
    </li>
    <li style="margin-top:0pt; margin-bottom:5pt; line-height:normal; widows:0; orphans:0">
        <span style="font-family:Arial">Puede suceder que por diferentes motivos un diagnóstico inicial de pulpotomía complique su realización y termine en una pulpectomía (extirpación de la pulpa cameral y radicular del diente). Si este último no fuera posible, el tratamiento indicado sería la extracción del diente temporal.</span>
    </li>
</ul>

<ul style="margin-top:0pt; margin-bottom:10pt; line-height:normal; widows:0; orphans:0">
    <li style="margin-top:0pt; margin-bottom:5pt; line-height:normal; widows:0; orphans:0">
        <span style="font-family:Arial">Asimismo me han sido expuestos los posibles riesgos o complicaciones del tratamiento, permitiéndome realizar todas las observaciones y preguntas para aclarar mis dudas.</span>
    </li>
    <li style="margin-top:0pt; margin-bottom:5pt; line-height:normal; widows:0; orphans:0">
        <span style="font-family:Arial">
             Riesgos propios de la inyección de anestésicos locales: posible hipersensibilidad al anestésico difícilmente previsible, alergia al anestésico, anestesias prolongadas, daños locales por la punción o mordisqueos post tratamiento (ulceración y/o hematoma).<br>
             Riesgo de ingesta o incluso aspiración de pequeños restos de material de obturación sobrantes.<br>
             Riesgo de pequeños daños en los tejidos blandos adyacentes a la zona de trabajo (encía, mucosa yugal, lengua) debido al uso del instrumental de trabajo, instrumentos separadores o clamps para sujetar el dique de goma. Este riesgo será mayor en niños poco colaboradores. Aún así suelen ser leves y se resuelven en varios días.<br>
             Riesgo de que los tratamientos que afecten a la pulpa fracasen debido a la presencia de infección en los conductos radiculares o en el periápice, o debido a la poca colaboración del paciente.
        </span>
    </li>
</ul>

<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
    <span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span>
</p>
<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
    <?php if ($descripcionTratamiento != null){ ?>
        Así mismo, el niño/a <?php echo strtoupper($datos_firma[0]['cliente']); ?> sus especiales condiciones personales
        (<?php if (isset($descripcionTratamiento)){ echo  $descripcionTratamiento; } else { echo $datos_firma[0]['descripcionTratamiento']; } ?>)
    <?php } ?>
    <?php if ($descripcionRiesgos != null){ ?>
        , puede presentar riesgos añadidos consistentes en :<br/>
        <?php if (isset($descripcionRiesgos)){ echo  $descripcionRiesgos; } else { echo $datos_firma[0]['descripcionRiesgos']; } ?>
    <?php } ?>
</p>
<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
    <span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span>
</p>
<h3>
		DECLARACIONES Y FIRMAS
	</h3>
	
	<p>Se me ha dado tiempo suficiente para leer la información precedente que describe el tratamiento de blanqueamiento dental externo. Comprendo los beneficios, riesgos e inconvenientes asociados al tratamiento. <br><br>He sido suficientemente informado y he tenido la oportunidad de hacer preguntas y discutir las preocupaciones sobre el tratamiento de blanqueamiento dental externo con los productos con mi dentista que me ofrece el tratamiento.  <br><br>Por la presente doy mi consentimiento para el tratamiento de blanqueamiento dental externo que ha sido prescrito por mi dentista.
			</p>
			<p>
				Entiendo que el blanqueamiento dental no es una ciencia exacta. Reconozco que mi dentista y no tiene ni puede dar ninguna garantía o seguridad sobre el resultado de mi tratamiento. <br><br>Entiendo la empresa proveedora no es un proveedor de servicios médicos, odontológicos o de atención de la salud y no practica ni puede practicar la odontología ni dar consejos médicos. <br><br>Ni mi dentista ni el proveedor me han dado ningún tipo de garantías sobre el resultado específico de mi tratamiento. <br><br>
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