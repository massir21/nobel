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
		CIRUGÍA DE ELEVACIÓN SINUSAL
	</h2>
<!--	<h4>
		Clínica dental: <?php echo $nombre_centro; ?>
	</h4> -->
	


	<p>
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
	<p>Que por el presente documento REQUIERO Y AUTORIZO a Dr. <?php echo strtoupper($datos_doctor[0]['nombre'])." ". strtoupper($datos_doctor[0]['apellidos']);?> licenciado en Medicina y Cirugía y/o Odontología con número de colegiado <?php echo  strtoupper($datos_doctor[0]['n_colegiado']) ?> y a su equipo a que realice en mi persona el tratamiento de <strong>cirugía de elevación sinusal,</strong> habiéndome explicado que es conveniente proceder, en mi situación, a recibir dicho tratamiento.</p>
	<h2>Descripción del tratamiento de cirugía de elevación sinusal:</h2>


			
			<p>
				&#xa0;
			</p>
			<p>
				Los procedimientos de cirugía de elevación sinusal van dirigidos básicamente a enterponer entre el seno maxilar y la cavidad oral una cantidad de hueso suficiente que nos permita insertar, con mayores posibilidades de éxito, implantes osteointegrables. La técnica consiste en acceder al seno maxilar a través de una incisión realizada en la cavidad oral y una ventana practicada en el hueso maxilar. <br><br>Una vez en el seno se procede a despegar y levantar su mucosa y a introducir, si procede, algún material de relleno óseo. En el mismo acto quirúrgico se pueden colocar implantes osteointegrables o no. <br><br>Muy raramente se precisan procedimientos de fijación u osteosíntesis externos como placas, mallas o tornillos. Posteriormente se sutura la zona. 
			</p>
			<p>
				&#xa0;
			</p>
			<p>
				Este procedimiento está indicado para el problema que tiene <STRONG>el/la paciente, </STRONG> consistente en una insuficiente cantidad de hueso en la zona del seno maxilar para permitir la inserción de implantes osteointegrables. 
			</p>
            <p>
                &#xa0;
            </p>
            <p>El  procedimiento requiere la aplicación de anestesia local, lo que provocará una sensación de acorchamiento del labio o de la cara que desaparecerá al cabo de unas horas. También, que la administración de la anestesia podría producir ulceración y/o hematoma del tejido, dolor y en raras ocasiones, pérdida de la sensibilidad en la boca o en la cara. Así mismo puede provocar bajada de tensión y mareo.
            </p>
			<p>
				&#xa0;
			</p>
			<!--<p>
				<STRONG>Se han sopesado y descartado por distintos motivos, de los que ha sido informado/a.</STRONG>
			</p>
			<h4>
    Otros procedimientos terapéuticos alternativos como:
</h4> -->

			<h3>
				El/la paciente ha sido informado/a y conoce los riesgos estadísticamente frecuentes que puede comportar este tratamiento: </h3>

					<ul>
    <li>Riesgos propios de la inyección de anestesia local: posibles hipersensibilidades al anestésico difícilmente previsibles, anestesias prolongadas, daños locales por la punción, etc.</li>
    <li>Riesgo de inflamación y dolor en la zona tratada debido al procedimiento quirúrgico.</li>
    <li>Riesgo de hemorragia y aparición de hematomas en la zona o áreas adyacentes.</li>
    <li>Riesgo de dehiscencia (separación) de las suturas empleadas.</li>
    <li>Riesgo de que se produzcan pequeños daños en las zonas próximas a las tratadas debido a la manipulación y separación de tejidos propios de la cirugía, en especial desgarros de la mucosa sinusal y rotura de la cortical ósea (que obligaría a utilizar otros procedimientos de cierre del seno maxilar).</li>
    <li>Riesgo de sobreinfección de las heridas quirúrgicas por los gérmenes bucales.</li>
    <li>Riesgo de pérdida de sensibilidad, temporal o no, en la zona tratada por los daños producidos a las pequeñas terminaciones nerviosas.</li>
    <li>Complicaciones infrecuentes propias del procedimiento quirúrgico de elevación sinusal: aparición de sinusitis aguda o crónica (que en ocasiones puede obligar a la reintervención), comunicación orosinusal (que sería indicación de nueva cirugía), alergia a los materiales de relleno utilizados no provenientes del paciente (hueso liofilizado, hidroxiapatita de origen animal, etc.) y colección hemática persistente en seno maxilar (que obligaría a su drenaje).</li>
    <li>Riesgos y complicaciones debidas a los procedimientos de injertos de hueso autólogos (del propio paciente) en caso de utilizarse. La zona donante de hueso puede sufrir complicaciones propias de la extracción de dicho hueso: dolor, inflamación, etc.</li>
</ul>

            <?php if ($descripcionTratamiento != null){ ?>
            <p>
                Asimismo el Sr/Sra.<strong><?php echo strtoupper($datos_firma[0]['cliente']); ?></strong> por sus especiales condiciones personales (<strong><?php if (isset($descripcionTratamiento)){ echo  $descripcionTratamiento; } else { echo $datos_firma[0]['descripcionTratamiento']; } ?></strong>)
                <?php } ?>

                <?php if ($descripcionRiesgos != null){ ?>
                 puede presentar riesgos añadidos en:
                <?php if (isset($descripcionRiesgos)){ echo  $descripcionRiesgos; } else { echo $datos_firma[0]['descripcionRiesgos']; } ?>
            </p>
                <p>
                    &#xa0;
                </p>
            <?php } ?>
			<p>
				&#xa0;
			</p>
			<p>
				El paciente también ha sido informado de que debe seguir los consejos y pautas de tratamiento dados por el profesional y que deberá consultar cualquier eventualidad que ocurra y que le parezca anormal. Además debe seguir meticulosamente las instrucciones sobre higiene del implante y de la prótesis y acudir a las revisiones periódicas acordadas con el profesional, al menos cada seis meses, y siempre que tenga cualquier molestia o duda sobre el tratamiento. 
			</p>
			<p>
				&#xa0;
			</p>
			
			<p>
				Yo, D/Dña. <strong><?php echo strtoupper($datos_firma[0]['cliente']); ?>&#xa0;</strong> como paciente <strong><?php if ($datos_firma[0]['edad']<18){ ?></strong> y D/Dña.
                   <strong> <?php
                    if ($datos_firma[0]['nombre_tutor'] != null)
                        echo strtoupper($datos_firma[0]['nombre_tutor']);
                    else
                        echo '<span><br/></span>';
                    ?></strong>
                    como padre, madre o tutor y DNI <strong><?php if ($datos_firma[0]['nombre_tutor'] != null) echo $datos_firma[0]['dni_tutor']; } ?></strong> he sido informado/a por el doctor.
			</p>
			<p>
				&#xa0;
			</p>
			<p>
				Comprendo el alcance y el significado de dicha información, y consiento en someterme a los procedimientos quirúrgicos implantológicos incluidos en el plan de tratamiento. También he sido informado/a de la posibilidad de rechazar este consentimiento por escrito en cualquier momento, haciendo frente a los gastos ocasionados hasta ese momento. 
			</p>
			<p>
				&#xa0;
			</p>
			<p >
				Madrid, a <?php echo Date('d/m/Y'); ?>
			</p>
			<div>
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