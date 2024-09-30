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
<p style="text-align:center"><img src="<?= $logo ?>" style=" height:2.5cm;" /></p>
	
			<p style="margin-top:10.3pt; margin-left:0.5pt; margin-bottom:10.3pt; text-indent:-0.5pt; line-height:112%; font-size:13pt">
				<strong>&#xa0;</strong>
			</p>
			</head>
			<body>
			<h1>
		CONSENTIMIENTO INFORMADO
	</h1>
	<h2>PULPECTOMÍA</h2>

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
	<p>Que por el presente documento REQUIERO Y AUTORIZO a Dr. <?php echo strtoupper($datos_doctor[0]['nombre'])." ". strtoupper($datos_doctor[0]['apellidos']);?> licenciado en Medicina y Cirugía y/o Odontología con número de colegiado <?php echo  strtoupper($datos_doctor[0]['n_colegiado']) ?> y a su equipo a que realice en mi persona el tratamiento de <strong>pulpectomía,</strong> habiéndome explicado que es conveniente proceder, en mi situación, a recibir dicho tratamiento.</p>
	
<p style="margin-top:10.3pt; margin-bottom:10.75pt; line-height:108%">
    <strong>Me ha explicado y he sido debidamente informado/a y en consecuencia AUTORIZO al mismo para que me sea realizado el procedimiento denominado PULPECTOMÍA.</strong>
</p>
<p style="margin-top:10.75pt; margin-left:0.5pt; margin-bottom:10.25pt">
    El objetivo del tratamiento de pulpectomía es eliminar totalmente el tejido pulpar (extirpación de la pulpa cameral y radicular) del diente, seguido de la obturación o relleno del mismo con sustancias medicamentosas REABSORBIBLES.
</p>
<p style="margin-top:10.25pt; margin-left:0.5pt; margin-bottom:10.25pt">
    La finalidad es conservar funcionalmente un diente cuya pulpa está totalmente dañada y mantener el diente en la boca del niño/a; con la finalidad de curar la infección presente, evitar su reinfección y en ningún caso ser perjudicial para el germen definitivo, que suele estar muy cercano; evitar evolucionar a una infección más extensa de la pulpa, hueso y tejidos perirradiculares. Se puede devolver la función al diente al poder ser restaurado de forma directa o indirecta después de realizar la pulpectomía.
</p>
<p style="margin-top:10.25pt; margin-left:0.5pt; margin-bottom:10.25pt">
    Puede suceder que por diferentes motivos un diagnóstico inicial de pulpectomía complique su realización y termine en una extracción, ya sea por perforación del suelo cameral, reinfección del conducto, o fracaso del tratamiento, que biológicamente no es 100% exitoso,o incluso por dificultad en el manejo de la conducta. Si el tratamiento de pulpectomía no fuera posible por algún motivo, el tratamiento indicado sería la extracción del diente temporal.
</p>
<p style="margin-top:10.25pt; margin-left:0.5pt; margin-bottom:10.25pt">
    El procedimiento requiere la aplicación de anestesia local, lo que provocará una sensación de acorchamiento del labio o de la cara que desaparecerá al cabo de unas horas. También, que la administración de la anestesia podría producir ulceración y/o hematoma del tejido, dolor y en raras ocasiones, pérdida de la sensibilidad en la boca o en la cara. Así mismo puede provocar bajada de tensión y mareo.
</p>
<p style="margin-top:10.25pt; margin-left:0.5pt; margin-bottom:10.25pt">
    Asimismo me han sido expuestos los posibles riesgos o complicaciones del tratamiento, permitiéndome realizar todas las observaciones y preguntas para aclarar mis dudas.
</p>
<p style="margin-top:10.25pt; margin-left:0.5pt; margin-bottom:10.25pt">
    -Riesgos propios de la inyección de anestésicos locales: posible hipersensibilidad al anestésico difícilmente previsible, alergia al anestésico, anestesias prolongadas, daños locales por la punción o mordisqueo post tratamiento (ulceración y/o hematoma).
</p>
<p style="margin-top:10.25pt; margin-left:0.5pt; margin-bottom:10.25pt">
    -Riesgo de ingesta o incluso aspiración de pequeños restos de material de obturación sobrantes.
</p>
<p style="margin-top:10.25pt; margin-left:0.5pt; margin-bottom:10.25pt">
    -Riesgo de pequeños daños en los tejidos blandos adyacentes a la zona de trabajo (encía, mucosa yugal, lengua) debido al uso del instrumental de trabajo, instrumentos separadores o clamps para sujetar el dique de goma. Este riesgo será mayor en niños poco colaboradores. Aún así suelen ser leves y se resuelven en varios días.
</p>
<p style="margin-top:10.25pt; margin-left:0.5pt; margin-bottom:10.25pt">
    Como ya se ha comentado, riesgo de que los tratamientos que afecten a la pulpa fracasen debido a la presencia de infección en los conductos radiculares o en el periápice, o debido a la poca colaboración del paciente.
</p>
<h3>
    DECLARACIONES Y FIRMAS
</h3>

<p>Se me ha dado tiempo suficiente para leer la información precedente que describe el tratamiento de pulpectomía. Comprendo los beneficios, riesgos e inconvenientes asociados al tratamiento. <br><br>He sido suficientemente informado y he tenido la oportunidad de hacer preguntas y discutir las preocupaciones sobre el tratamiento de pulpectomía con mi dentista que me ofrece el tratamiento. Por la presente doy mi consentimiento para el tratamiento de pulpectomía que ha sido prescrito por mi dentista.
</p>
<p>
    Autorizo a mi dentista a divulgar mis registros, incluyendo, pero no limitándose a radiografías, historial médico, fotografías, modelos de yeso o impresiones de dientes, prescripciones, diagnósticos y otros registros de tratamiento al proveedor y compañías asociadas. <br><br>Esto es con el propósito de investigar y revisar mi caso, en lo que se refiere al tratamiento de pulpectomía con sus producto(s) y/o con fines educativos/de investigación. 
</p>
<p>
    Por la presente consiento en la divulgación de lo anterior. No buscaré, ni nadie en mi nombre, daños o remedios legales, equitativos o monetarios por dicha divulgación. Una fotocopia de este consentimiento se considerará tan efectiva y válida como un original. <br><br>
</p>
<p>
    He leído, entendido y estoy de acuerdo con los términos establecidos en este Consentimiento, tal y como se indica con mi firma a continuación. 
</p>

<p>Declaro que el/la facultativo/a, Dr/Dra
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
    En <b>Madrid</b> a día   <b> <?php echo date('d/m/Y'); ?><br><br>
    </b>
</p>

<div>
    <p>Fdo.: El paciente o (representante legal)</p><br><br>

    <?php if ($datos_firma[0]['firma'] != "ONLINE" and $datos_firma[0]['firma'] != "") {
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
