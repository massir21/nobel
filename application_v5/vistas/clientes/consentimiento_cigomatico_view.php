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
		IMPLANTES CIGOMÁTICOS
	</h2>
	<p>
              <span style="font-family:Arial">Yo, Don/Doña, </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['nombre_tutor']);  ?></span></strong>
              <span style="font-family:Arial">con DNI nº </span><strong><span style="font-family:Arial; "><?php echo $datos_firma[0]['dni_tutor']; ?></span></strong><span style="font-family:Arial">, en calidad de padre, madre, tutor/a o representante legal del paciente: </span>
          </p>
		  <p>Don/Doña, <?php echo strtoupper($datos_firma[0]['cliente']); ?> de <?php echo $datos_firma[0]['edad']; ?> años de edad, con domicilio en <?php echo $datos_firma[0]['direccion']; ?> y DNI <?php echo $datos_firma[0]['dni']; ?></p>
			<br></br><p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				En cumplimiento de la Ley 41/2002, básica reguladora de la autonomía del paciente y de derechos y obligaciones en materia de información y documentación clínica, se le presenta para su firma el siguiente documento: 
				</p>
			
			<br><p style="margin-top:10pt; margin-bottom:0pt; line-height:normal">
				sobre los procedimientos clínicos de Implantología oral, que constan en el plan de tratamiento que previamente he aceptado y que en micas incorporan el uso de implantes alojados en mi hueso cigomático, que constan en el plan de tratamiento propuesto
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				&#xa0;
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				<span>Los implantes cigomáticos son implantes dentales modificados de mayor tamaño a los habituales que se fijan al hueso malar en situaciones de disponibilidad insuficiente para la utilización de implantes convencionales. SI bien pueden simularse en planificación guiada, su colocación supone la necesidad de incisión, despegamiento de tejidos blandos, preparación del trayecto óseo y sutura. En determinadas situaciones es precisa la manipulación quirúrgica del seno maxilar. Suelen efectuarse bajo anestesia general o sedación intravenosa. Estos implantes están indicados en situaciones de déficit de hueso alveolar residual por motivos atróficos, tumorales, traumáticos, mal formativos, fracaso de otros tratamientos u otras circunstancias menos habituales. Sus alternativas son los implantes convencionales con reconstrucción y/o regeneración y, en determinados casos, los implantes cortos y basales, procedimientos de cuyas ventajas e inconvenientes he sido informado/a antes de empezar el tratamiento. El éxito de estos implantes es muy alto, superior al 90-98% en la mayoría de los casos y mejora en rehabilitaciones maxilares totales con el uso de prótesis de función inmediata. Su uso en rehabilitaciones parciales es clínicamente exitoso, pero no forma parte del protocolo consensuado de actuación, precisando convenir con el paciente su uso y otras alternativas. </span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				&#xa0;
			</p>
        <?php if ($descripcionProblema != null){ ?>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				Este procedimiento está indicado para el problema que el/la paciente tiene, consistente en:
                <?php if (isset($descripcionProblema)){ echo  $descripcionProblema; } else { echo $datos_firma[0]['descripcionProblema']; } ?>
            </p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				&#xa0;
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				&#xa0;
			</p>
        <?php } ?>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				Se han sopesado y descartado por distintos motivos, de los que ha sido informado/a, otros procedimientos terapéuticos alternativos como: 
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				&#xa0;
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				El/la paciente ha sido informado/a y conoce los riesgos estadísticamente frecuentes que puede comportar este tratamiento: 
			</p>
			<p style="margin-top:0pt; margin-bottom:1.35pt; line-height:normal">
				• Riesgos propios de la inyección de anestesia local: posibles hipersensibilidades al anestésico difícilmente previsibles, anestesias prolongadas, daños locales por la punción, etc. 
			</p>
			<p style="margin-top:1.35pt; margin-bottom:1.35pt; line-height:normal">
				• Riesgo de inflamación y dolor en la zona tratada debido al procedimiento quirúrgico. 
			</p>
			<p style="margin-top:1.35pt; margin-bottom:1.35pt; line-height:normal">
				• Riesgo de hemorragia y aparición de hematomas en la zona o áreas adyacentes. 
			</p>
			<p style="margin-top:1.35pt; margin-bottom:1.35pt; line-height:normal">
				• Riesgo de dehiscencia (separación) de las suturas empleadas. 
			</p>
			<p style="margin-top:1.35pt; margin-bottom:1.35pt; line-height:normal">
				• Riesgo de que se produzcan pequeños daños en las zonas próximas a las tratadas debido a la manipulación y separación de tejidos propios de la cirugía, en especial desgarros de la mucosa sinusal y rotura de la cortical ósea (que obligaría a utilizar otros procedimientos de cierre del seno maxilar). 
			</p>
			<p style="margin-top:1.35pt; margin-bottom:1.35pt; line-height:normal">
				• Riesgo de sobreinfección de las heridas quirúrgicas por los gérmenes bucales. 
			</p>
			<p style="margin-top:1.35pt; margin-bottom:1.35pt; line-height:normal">
				• Riesgo de pérdida de sensibilidad, temporal o no, en la zona tratada por los daños producidos a las pequeñas terminaciones nerviosas. 
			</p>
			<p style="margin-top:1.35pt; margin-bottom:0pt; line-height:normal">
				• Riesgo de dañar las raíces dentarias adyacentes. 
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				&#xa0;
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				<br style="page-break-before:always; clear:both" >
			</p>
			<p style="margin-top:0pt; margin-bottom:1.35pt; line-height:normal">
				• Riesgo de fracaso en la integración ósea de los implantes. En ocasiones, por causas desconocidas, el hueso no integra al implante y éste se acaba perdiendo. Esto implica la necesidad de repetir la fase quirúrgica y, en ocasiones, de replantear el tratamiento. 
			</p>
			<p style="margin-top:1.35pt; margin-bottom:1.35pt; line-height:normal">
				• Riesgo del fracaso del implante a más largo plazo. El hecho de que el implante se hubiera integrado en el hueso en un primer momento, no implica que no pudiera fracasar posteriormente. Las causas del mismo son múltiples y muchas, desconocidas: factores relacionados con la oclusión, con la higiene defectuosa, con la falta de revisiones periódicas, factores intrínsecos a la propia biología del paciente, etc. Dependiendo del caso concreto, se podría recolocar el implante en una zona próxima o habría de replantearse toda la rehabilitación, incluso descartando el uso de implantes. EL fracaso de algún implante supone la modificación, o en la mayoría de las ocasiones, tener que cambiar completamente, la prótesis apoyada sobre ellos. 
			</p>
			<p style="margin-top:1.35pt; margin-bottom:1.35pt; line-height:normal">
				• Riesgo de ingestión o aspiración del pequeño material quirúrgico o prostodóncico empleado. 
			</p>
			<p style="margin-top:1.35pt; margin-bottom:1.35pt; line-height:normal">
				• Riesgo de fractura del material implantado o de los aditamentos protésicos empleados debido a la magnitud de las fuerzas oclusales soportadas. 
			</p>
			<p style="margin-top:1.35pt; margin-bottom:0pt; line-height:normal">
				• Riesgo de problemas relacionados con la prótesis y que se detallan en un documento aparte. 
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				&#xa0;
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				Los implantes cigomáticos incorporan riesgos propios de infección local y posibles fístulas cutáneas crónicas (que pueden requerir accesos transcutáneos para su tratamiento), disestesias anestésicas infraorbitarias y de la zona malar y daño de estructuras vecinas (muy raramente se han descrito complicaciones por invasión orbitaria). En caso de sinusitis de repetición en pacientes portadores de implantes cigomáticos puede ser necesaria la intervención ORL. Pueden aparecer comunicaciones entre la boca y la nariz o los senos maxilares. Si es preciso retirar un implante cigomático de forma total o parcial por fracaso del mismo no siempre es posible su reemplazo y pueden estar indicados otros procedimientos alternativos o modificar la estrategia de rehabilitación por completo. 
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				&#xa0;
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				Eventualmente puede abortarse el procedimiento por problemas relacionados con la anestesia general o sedación o por problemas en la inserción del implante, siendo dicha circunstancia excepcional. El fracaso del tratamiento a corto, medio o largo plazo o la aparición de complicaciones con necesidad de intervención del profesional que le atiende o de otros especialistas médicos o quirúrgicos puede devengar en gastos económicos adicionales no contemplados en el presupuesto inicial que previamente he aceptado. En cualquier caso, no es posible garantizar médicamente el éxito inicial, final o a medio o largo plazo del tratamiento implantológico. A pesar del éxito del tratamiento inicial, no se realiza el procedimiento con garantías de por vida ni como rehabilitación definitiva de por vida al igual que en ningún tratamiento implantológico. 
			</p>
			
        <?php if ($descriptionProcTerapeuticos != null){ ?>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				Debido a la zona donde se efectuará el tratamiento quirúrgico, se podrán producir además los siguientes riesgos y complicaciones: <br/>
                <?php if (isset($descriptionProcTerapeuticos)){ echo  $descriptionProcTerapeuticos; } else { echo $datos_firma[0]['descriptionProcTerapeuticos']; } ?>
            </p>
			
        <?php } ?>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:left; line-height:normal">
				&#xa0;
			</p>
			
        <?php if ($descripcionTratamiento != null){ ?>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				Asimismo el Sr/Sra.<?php echo strtoupper($datos_firma[0]['cliente']); ?> por sus especiales condiciones personales (<?php if (isset($descripcionTratamiento)){ echo  $descripcionTratamiento; } else { echo $datos_firma[0]['descripcionTratamiento']; } ?>)
        <?php } ?>

        <?php if ($descripcionRiesgos != null){ ?>
                , puede presentar riesgos añadidos en:
                <?php if (isset($descripcionRiesgos)){ echo  $descripcionRiesgos; } else { echo $datos_firma[0]['descripcionRiesgos']; } ?>
            </p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				&#xa0;
			</p>
        <?php } ?>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				El paciente también ha sido informado de que debe seguir los consejos y pautas de tratamiento dados por el profesional y que deberá consultar cualquier eventualidad que ocurra y que le parezca anormal. Además debe seguir meticulosamente las instrucciones sobre higiene del implante y de la prótesis y acudir a las revisiones periódicas acordadas con el profesional, al menos cada seis meses, y siempre que tenga cualquier molestia o duda sobre el tratamiento. 
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				&#xa0;
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				&#xa0;
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				Yo, D/Dña. <?php echo strtoupper($datos_firma[0]['cliente']); ?>&#xa0; como paciente <?php if ($datos_firma[0]['edad']<18){ ?> y D/Dña.
                    <?php if ($datos_firma[0]['nombre_tutor'] != null) echo strtoupper($datos_firma[0]['nombre_tutor']); else echo '<span><br/></span>'; ?> como padre, madre o tutor <?php } ?>, he sido informado/a por el doctor.
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				&#xa0;
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal">
				Comprendo el alcance y el significado de dicha información, y consiento en someterme a los procedimientos quirúrgicos implantológicos incluidos en el plan de tratamiento. También he sido informado/a de la posibilidad de rechazar este consentimiento por escrito en cualquier momento, haciendo frente a los gastos ocasionados hasta ese momento. 
			</p>
			<h3>
		DECLARACIONES Y FIRMAS
	</h3>
	
	<p>Se me ha dado tiempo suficiente para leer la información precedente que describe el tratamiento de implantes cigomáticos. Comprendo los beneficios, riesgos e inconvenientes asociados al tratamiento. <br><br>He sido suficientemente informado y he tenido la oportunidad de hacer preguntas y discutir las preocupaciones sobre el tratamiento de implantes cigomáticos con los productos con mi dentista que me ofrece el tratamiento.  <br><br>Por la presente doy mi consentimiento para el tratamiento de implantes cigomáticos que ha sido prescrito por mi dentista.
			</p>
			<p>
				Autorizo a mi dentista a divulgar mis registros, incluyendo, pero no limitándose a radiografías, historial médico, fotografías, modelos de yeso o impresiones de dientes, prescripciones, diagnósticos y otros registros de tratamiento al proveedor y compañías asociadas. <br><br>Esto es con el propósito de investigar y revisar mi caso, en lo que se refiere al tratamiento de odontología con sus producto(s) y/o con fines educativos/de investigación. 
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