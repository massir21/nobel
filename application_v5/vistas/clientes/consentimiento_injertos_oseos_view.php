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
		INJERTOS ÓSEOS
	</h2>
	<p> <?php if ($datos_firma[0]['edad']<18){ ?>
          <p>
              <span style="font-family:Arial">Yo, Don/Doña, </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['nombre_tutor']);  ?></span></strong>
              <span style="font-family:Arial">con DNI nº </span><strong><span style="font-family:Arial; "><?php echo $datos_firma[0]['dni_tutor']; ?></span></strong><span style="font-family:Arial">, en calidad de padre, madre, tutor/a o representante legal del paciente: </span>
          </p>
      <?php } ?>
	
	
	<p>Don/Doña, <?php echo strtoupper($datos_firma[0]['cliente']); ?> de <?php echo $datos_firma[0]['edad']; ?> años de edad, con domicilio en <?php echo $datos_firma[0]['direccion']; ?> y DNI <?php echo $datos_firma[0]['dni']; ?></p>
	
		  
			<P style="margin-top:10pt; margin-bottom:0pt; line-height:normal">
				En cumplimiento de la Ley 41/2002, básica reguladora de la autonomía del paciente y de derechos y obligaciones en materia de información y documentación clínica, se le presenta para su firma el siguiente documento: 
				</P>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				&#xa0;
			</p>
			<p style="margin-top:5pt; margin-bottom:0pt; line-height:normal">
				D/Dª <?php echo strtoupper($datos_firma[0]['cliente']); ?> con DNI <?php echo strtoupper($datos_firma[0]['dni']); ?>&#xa0; como paciente (en caso de menores o incapacitados consignar el nombre y DNI del padre, madre o tutor , ha sido informado/a por el Dr/Dra: <?php echo strtoupper($datos_doctor[0]['nombre'])." ". strtoupper($datos_doctor[0]['apellidos']); ?>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				sobre procedimientos clínicos de injertos óseos, que constan en el plan de tratamiento que previamentehe aceptadoy entiendo elpropósito y la naturaleza del tratamiento con injertos óseos, así como también entiendo la necesidad de emplear un procedimiento quirúrgico para colocar el injerto. 
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				&#xa0;
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:left; line-height:normal">
				He recibido información de los posibles riesgos y complicaciones involucradas en el procedimiento quirúrgico, uso de medicamentos y anestesia. Algunas de estas complicaciones incluyen dolor, inflamaciones, infecciones, decoloraciones y mordeduras. La duración de estos fenómenos no está determinada, pudiendo ser irreversible, también puede ocurrir daño a dientes preexistentes, fracturas óseas, penetración en cavidades, reacciones alérgicas a medicamentos, dolores de cabeza, dolores referidos, etc. 
			</p>
<?php if ($descripcionProblema != null){ ?>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:left; line-height:normal">
				También me han informado de los riesgos específicos que puedo correr en base a mi estado general y a las particularidades de mi tratamiento y que son:
                <?php if (isset($descripcionProblema)){ echo  $descripcionProblema; } else { echo $datos_firma[0]['descripcionProblema']; } ?>
            </p>
<?php } ?>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:left; line-height:normal">
				&#xa0;
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:left; line-height:normal">
				He sido informado de que no hay ningún método para predecir la completa integración de los injertos en el hueso adyacente, y me han explicado que en caso de no integrarse adecuadamente deben extraerse, por lo que no existen garantías en los resultados que se pueden obtener con estos tratamientos. 
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:left; line-height:normal">
				&#xa0;
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:left; line-height:normal">
				He sido informado de los riesgos atribuidos al uso del tabaco y sus derivados, que reducen considerablemente la tasa de éxitos en los tratamientos con injertos óseos. 
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:left; line-height:normal">
				Acepto la realización de injertos óseos procedentes de bancos de tejidos humanos, utilización de biomateriales y, en general, cualquier técnica quirúrgica que sea necesaria para el éxito de mi tratamiento. 
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:left; line-height:normal">
				&#xa0;
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:left; line-height:normal">
				Me han informado de que el plan de tratamiento que se ha planificado, podría sufrir variación en función de las incidencias quirúrgicas de mi caso, pero de todas formas, se adaptaría lo más posible a lo previamente diseñado.
			</p>
			
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
                <?php if ($descripcionTratamiento != null){ ?>
                    Asimismo el Sr/Sra.<?php echo strtoupper($datos_firma[0]['cliente']); ?> por sus especiales condiciones personales
                    (<?php if (isset($descripcionTratamiento)){ echo  $descripcionTratamiento; } else { echo $datos_firma[0]['descripcionTratamiento']; } ?>)
                <?php } ?>
                <?php if ($descripcionRiesgos != null){ ?>
                    , puede presentar riesgos añadidos en:
                    <?php if (isset($descripcionRiesgos)){ echo  $descripcionRiesgos; } else { echo $datos_firma[0]['descripcionRiesgos']; } ?>
                <?php } ?>
            </p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				&#xa0;
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				El paciente también ha sido informado de que debe seguir los consejos y pautas de tratamiento dados por el profesional y que deberá consultar cualquier eventualidad que ocurra y que le parezca anormal. Además debe seguir meticulosamente las instrucciones sobre higiene del implante y de la prótesis y acudir a las revisiones periódicas acordadas con el profesional, al menos cada seis meses, y siempre que tenga cualquier molestia o duda sobre el tratamiento. 
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				&#xa0;
			</p>
			
			<h3>
		DECLARACIONES Y FIRMAS
	</h3>
	
	<p>Se me ha dado tiempo suficiente para leer la información precedente que describe el tratamiento de injertos óseos. Comprendo los beneficios, riesgos e inconvenientes asociados al tratamiento. <br><br>He sido suficientemente informado y he tenido la oportunidad de hacer preguntas y discutir las preocupaciones sobre el tratamiento de injertos óseos con los productos con mi dentista que me ofrece el tratamiento.  <br><br>Por la presente doy mi consentimiento para el tratamiento de injertos óseos que ha sido prescrito por mi dentista.
			</p>
			<p>
				Autorizo a mi dentista a divulgar mis registros, incluyendo, pero no limitándose a radiografías, historial médico, fotografías, modelos de yeso o impresiones de dientes, prescripciones, diagnósticos y otros registros de tratamiento al proveedor y compañías asociadas. <br><br>Esto es con el propósito de investigar y revisar mi caso, en lo que se refiere al tratamiento de odontología  con sus producto(s) y/o con fines educativos/de investigación. 
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