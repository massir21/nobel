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
</body>
<p style="text-align:center"><img src="<?= $logo ?>" style=" height:2.5cm;" /></p>
	<h1>
		CONSENTIMIENTO INFORMADO
	</h1>
	<h2>
		PRÓTESIS SOBRE IMPLANTES
	</h2>
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
	<p>Que por el presente documento REQUIERO Y AUTORIZO a Dr. <?php echo strtoupper($datos_doctor[0]['nombre'])." ". strtoupper($datos_doctor[0]['apellidos']);?> licenciado en Medicina y Cirugía y/o Odontología con número de colegiado <?php echo  strtoupper($datos_doctor[0]['n_colegiado']) ?> y a su equipo a que realice en mi persona el tratamiento de <strong>prótesis sobre implante,</strong> habiéndome explicado que es conveniente proceder, en mi situación, a recibir dicho tratamiento.</p>
			
	<p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:normal; widows:2; orphans:2; ">
				<span style="font-family:'Arial Rounded MT Bold'">&#xa0;</span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:2; orphans:2">
				Cumplimiento de la Ley 41/2002, básica reguladora de la autonomía del paciente y de derechos y obligaciones en materia de información y documentación clínica, se le presenta para su firma el siguiente documento:
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:2; orphans:2">
				&#xa0;
			</p>
			
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:2; orphans:2">
				Sobre los procedimientos clínicos de prótesis fija sujeta a implantes, que constan en el plan de tratamiento que previamente he aceptado. 
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:2; orphans:2;">
				&#xa0;
			</p>
			<p style="margin:0pt 0.05pt 0.2pt 0.5pt; widows:2; orphans:2">
				Los procedimientos propios de la prótesis fija implantosoportada van dirigidos a la sustitución de los dientes perdidos mediante aparatología que el paciente no puede retirar por sus propios medios. Este tipo de prótesis vá sujeta sobre implantes y en el momento de su colocación han de estar ya osteointegrados. Es evidente que la función esperable de una prótesis nunca será la misma que la que proporcionaron los dientes naturales. 
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:left; line-height:108%; widows:2; orphans:2">
				&#xa0;
			</p>
			<p style="margin:0pt 0.05pt 11.05pt ;  widows:2; orphans:2">
				Este procedimiento está indicado para el problema que tiene él/la paciente, consistente en, 
			</p>
            <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
                <span style="font-family:Arial"> El procedimiento puede requerir la aplicación de anestesia local, lo que provocará una sensación de acorchamiento del labio o de la cara que desaparecerá al cabo de unas horas. También, que la administración de la anestesia podría producir ulceración y/o hematoma del tejido, dolor y, en raras ocasiones, pérdida de la sensibilidad en la boca o en la cara. Más raramente, puede provocar bajada de tensión y mareo.</span>
            </p>
			<p style="margin:11.05pt 0.05pt 0.2pt; line-height:200%; widows:2; orphans:2">
				Se han sopesado y descartado por distintos motivos de los que ha sido informado/a otros procedimientos terapéuticos alternativos como 
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:left; line-height:108%; widows:2; orphans:2">
				&#xa0;
			</p>
			<p style="margin:0pt 0.05pt 0.2pt ; widows:2; orphans:2">
				El/la paciente ha sido informado/a y conoce los riesgos e inconvenientes estadísticamente frecuentes que puede comportar este tratamiento: 
			</p>
			<p style="margin:0.2pt 0.05pt 0.2pt 36pt; widows:2; orphans:2">
				&#xa0;
			</p>
			<ul style="margin-left: 36pt;">
    <li>Problemas de adaptación a la prótesis. Una prótesis fija no deja de ser un cuerpo extraño que se instala en la boca intentando sustituir las funciones dentarias y por lo tanto requerirá un tiempo de adaptación que variará según la situación del paciente y el tipo de prótesis. Requerirá adaptación para la correcta masticación, posibles mordeduras, e hipersalivación en algunos individuos. Estos inconvenientes suelen pasar, pero en algunos pacientes persisten en un grado variable.</li>
    <li>Riesgo de no responder a las expectativas estéticas de los pacientes. Es preciso tener claro que estas prótesis, por sus materiales y sistemas de retención nunca podrán igualar la estética de los dientes naturales. Este tipo de prótesis precisan, además, espacios para asegurar la posibilidad de una correcta higiene alrededor de los implantes.</li>
    <li>Riesgo de pequeñas zonas de inflamación en la encía alrededor de las coronas. La prótesis es un cuerpo extraño y hay que extremar la higiene en toda la zona, en especial si por motivos estéticos la corona se sitúa parcialmente subgingival.</li>
    <li>Riesgo de que se introduzcan restos de alimentos bajo la prótesis o en los espacios vacíos que debe respetar.</li>
    <li>Riesgo de fractura de los materiales, despegamiento o aflojamiento de los sistemas de retención de la prótesis con el paso del tiempo. No hay que olvidar que las prótesis dentarias soportan grandes fuerzas masticatorias, y que esto, irremediablemente, afectará a los materiales y requerirá su sustitución.</li>
    <li>Riesgo de que el paciente manifieste o desarrolle algún tipo de intolerancia u alergia a los materiales de los que está construída la prótesis. Esta circunstancia es muy rara, difícilmente previsible, y de difícil solución.</li>
</ul>

			<p style="margin-top:0pt; margin-bottom:0pt; text-align:left; line-height:108%; widows:2; orphans:2">
				&#xa0;
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:left; line-height:108%; widows:2; orphans:2">
				&#xa0;
			</p>
			<p style="margin:0pt 0.05pt 0.2pt 0.5pt; line-height:108%; widows:2; orphans:2">
				Asimismo el Sr/Sra. <?php echo strtoupper($datos_firma[0]['cliente']); ?>&#xa0; por sus especiales condiciones personales (<?php if (isset($descripcionTratamiento)){ echo  $descripcionTratamiento; } else { echo $datos_firma[0]['descripcionTratamiento']; } ?>) puede presentar riesgos añadidos consistentes en :
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:left; line-height:108%; widows:2; orphans:2">
				&#xa0;
			</p>
		
			<p style="margin:0pt 0.05pt 0.2pt 0.5pt; text-indent:-0.5pt; widows:2; orphans:2">
				El paciente también ha sido informado de que las prótesis fijas implantosoportadas, debido a las fuerzas que soportan y al paso del tiempo, sufrirán deterioros (fisuras, roturas, despegamientos, etc) que harán necesario su renovación periódica. Igualmente ha sido informado de que la propia prótesis facilita la acumulación de placa bacteriana, hecho que puede dañar las encías, y que deberá ser eliminada mediante una detenida <strong>higiene</strong> de la prótesis y de los dientes remanentes después de cada comida. Para prevenir estas circunstancias se compromete a seguir las instrucciones dadas por su dentista y a someterse a <strong>revisiones periódicas</strong>, en ningún caso espaciadas más seis meses, y siempre que tenga cualquier tipo de molestia o duda sobre el tratamiento. Estas revisiones, pueden implicar la necesidad de desmontar la prótesis para su completa higiene y reajuste. 
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:left; line-height:108%; widows:2; orphans:2">
				&#xa0;
			</p>
			

	<h3>
		DECLARACIONES Y FIRMAS
	</h3>
	
	<p>Se me ha dado tiempo suficiente para leer la información precedente que describe el tratamiento de prótesis sobre implante. Comprendo los beneficios, riesgos e inconvenientes asociados al tratamiento. <br><br>He sido suficientemente informado y he tenido la oportunidad de hacer preguntas y discutir las preocupaciones sobre el tratamiento de prótesis dobre implante con los productos con mi dentista que me ofrece el tratamiento.  <br><br>Por la presente doy mi consentimiento para el tratamiento deprótesis sobre implante que ha sido prescrito por mi dentista.
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
	