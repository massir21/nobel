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
		IMPLANTOLOGIA
	</h2>
	<p>
              <span style="font-family:Arial">Yo, Don/Doña, </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['nombre_tutor']);  ?></span></strong>
              <span style="font-family:Arial">con DNI nº </span><strong><span style="font-family:Arial; "><?php echo $datos_firma[0]['dni_tutor']; ?></span></strong><span style="font-family:Arial">, en calidad de padre, madre, tutor/a o representante legal del paciente: </span>
          </p>
		  <p>Don/Doña, <?php echo strtoupper($datos_firma[0]['cliente']); ?> de <?php echo $datos_firma[0]['edad']; ?> años de edad, con domicilio en <?php echo $datos_firma[0]['direccion']; ?> y DNI <?php echo $datos_firma[0]['dni']; ?></p>

			</p>
			<p style="margin-top:10pt; margin-bottom:0pt; line-height:normal">
				En cumplimiento de la Ley 41/2002, básica reguladora de la autonomía del paciente y de derechos y obligaciones en materia de información y documentación clínica, se le presenta para su firma el siguiente documento: 
				</p>
				<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				&#xa0;
			</p>
			<p>Los procedimientos propios de la implantología oral van dirigidos básicamente a la sustitución de alguna/as raíces dentarias perdidas para que sirvan de soporte a algún tipo de rehabilitación protésica. Ello implica la invasión y manipulación mecánica del medio interno del organismo: incisión y despegamiento gingival, preparación en el hueso, colocación del implante y sutura. Según el tipo de implante y la situación del paciente existen variaciones técnicas que pueden significar tener que dejar en reposo el implante durante un tiempo. Posteriormente se vuelve a acceder al implante a través de la encía y se acoplan los accesorios protésicos necesarios para la confección y colocación de la prótesis.
			</p>
<?php if ($descripcionProblema != null){ ?>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				Este procedimiento está indicado para el problema que tiene el/la paciente, consistente en:
                <?php if (isset($descripcionProblema)){ echo  $descripcionProblema; } else { echo $datos_firma[0]['descripcionProblema']; } ?>
            </p>
<?php } ?>
			
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				&#xa0;
                <?php if ($descriptionProcTerapeuticos != null){ ?>
                    </p>
                    Se han sopesado y descartado por distintos motivos de los que ha sido informado/a otros procedimientos terapéuticos alternativos como:
                        <?php if (isset($descriptionProcTerapeuticos)){ echo  $descriptionProcTerapeuticos; } else { echo $datos_firma[0]['descriptionProcTerapeuticos']; } ?>
                    </p>
                <?php } ?>
			
		
			</p>
<?php if ($descripcionProcedimiento != null){ ?>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				El/la paciente ha sido informado/a y conoce los riesgos estadísticamente frecuentes que puede comportar este tratamiento:
                <?php if (isset($descripcionProcedimiento)){ echo  $descripcionProcedimiento; } else { echo $datos_firma[0]['descripcionProcedimiento']; } ?>
			</p>
<?php } ?>
<ul>
    <li style="margin-bottom: 1.35pt;">Riesgos propios de la inyección de anestesia local: posibles hipersensibilidades al anestésico difícilmente previsibles, anestesias prolongadas, daños locales por la punción, etc.</li>
    <li style="margin-bottom: 1.35pt;">Riesgos intrínsecos a los procedimientos quirúrgicos: dolor, inflamación, hemorragia y aparición de hematomas en la zona o áreas adyacentes, dehiscencia (separación) de las suturas empleadas, pequeños daños en las zonas próximas a las tratadas debido a la manipulación y separación de tejidos propios de la cirugía, sobreinfección de las heridas quirúrgicas por los gérmenes bucales, pérdida de sensibilidad, temporal o permanente, en la zona tratada por los daños producidos a las pequeñas terminaciones nerviosas, incluso dolores neurálgicos crónicos (dolores sin causa evidente y de difícil tratamiento) de forma excepcional, etc.</li>
    <li style="margin-bottom: 1.35pt;">Riesgo de dañar las raíces dentarias adyacentes.</li>
    <li style="margin-bottom: 1.35pt;">Riesgo de fracaso en la integración ósea de los implantes. En ocasiones, por causas desconocidas, el hueso no integra al implante y éste se acaba perdiendo. Esto implica la necesidad de repetir la fase quirúrgica, y en ocasiones de replantear el tratamiento.</li>
    <li>Riesgo de fracaso del implante a más largo plazo. El hecho de que el implante se hubiera integrado en el hueso en un primer momento, no implica que no pudiera fracasar posteriormente. Las causas del mismo son múltiples, y muchas desconocidas: factores relacionados con la oclusión, con la higiene defectuosa, con la falta de revisiones periódicas, factores intrínsecos a la propia biología del paciente, etc.</li>
</ul>

			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				Es importante resaltar que el hábito de fumar es un factor de riesgo conocido para la osteointegración de los implantes: fumar aumenta el riesgo de pérdida de los mismos.Ante la pérdida de uno o más implantes, y dependiendo del caso concreto, se podría recolocar el implante en una zona próxima o habría que replantearse toda la rehabilitación,incluso descartando el uso de implantes. El fracaso de algún implante siempre supone la modificación, o en la mayoría de las ocasiones tener que cambiar completamente, la prótesis apoyada sobre ellos. 
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				&#xa0;
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				&#xa0;
			</p>
			<ul style="margin-top: 0pt; margin-bottom: 0pt;">
    <li style="margin-bottom: 1.2pt; line-height: normal;">Riesgo de ingestión o aspiración del pequeño material quirúrgico o prostodóncico empleado.</li>
    <li style="margin-top: 1.2pt; margin-bottom: 1.2pt; line-height: normal;">Riesgo de daños temporales o permanentes en el seno maxilar al colocar implantes y/o injertos en la arcada superior.</li>
    <li style="margin-top: 1.2pt; margin-bottom: 1.2pt; line-height: normal;">Riesgo de fractura del material implantado o de los aditamentos protésicos empleados debido a la magnitud de las fuerzas oclusales soportadas.</li>
    <li style="margin-top: 1.2pt; margin-bottom: 1.2pt; line-height: normal;">En el caso de que se utilicen injertos óseos propios del sujeto, además de las posibles complicaciones quirúrgicas en la zona donante, existe siempre riesgo de que el injerto no prenda en la nueva localización, o incluso se infecte y tenga que ser retirado, con las modificaciones que esto supondría en el plan de tratamiento. El riesgo de infección (y necesidad de retirada del material injertado) también existe al utilizar hueso exógeno (o algún material sintético sustitutivo).</li>
    <li style="margin-top: 1.2pt; margin-bottom: 0pt; line-height: normal;">Riesgo de problemas relacionados con la prótesis y que se detallan en un documento aparte.</li>
</ul>

			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				&#xa0;
			</p>
<?php if ($descriptionProc != null){ ?>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				Debido a la zona donde se efectuará el tratamiento quirúrgico, se podrán producir además los siguientes riesgos y complicaciones:
                <?php if (isset($descriptionProc)){ echo  $descriptionProc; } else { echo $datos_firma[0]['descriptionProc']; } ?>
            </p>
<?php } ?>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				&#xa0;
			</p>
			
<?php if ($descripcionRiesgos != null){ ?>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				Asimismo el Sr/Sra. <?php echo strtoupper($datos_firma[0]['cliente']); ?> por sus especiales condiciones personales (<?php if (isset($descripcionTratamiento)){ echo  $descripcionTratamiento; } else { echo $datos_firma[0]['descripcionTratamiento']; } ?>), puede presentar riesgos añadidos en:
                <?php if (isset($descripcionRiesgos)){ echo  $descripcionRiesgos; } else { echo $datos_firma[0]['descripcionRiesgos']; } ?>
            </p>
			
<?php } ?>
<h3>
		DECLARACIONES Y FIRMAS
	</h3>
	
	<p>Se me ha dado tiempo suficiente para leer la información precedente que describe el tratamiento de implantologia. Comprendo los beneficios, riesgos e inconvenientes asociados al tratamiento. <br><br>He sido suficientemente informado y he tenido la oportunidad de hacer preguntas y discutir las preocupaciones sobre el tratamiento de implantologia con los productos con mi dentista que me ofrece el tratamiento.  <br><br>Por la presente doy mi consentimiento para el tratamiento de implantologia que ha sido prescrito por mi dentista.
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