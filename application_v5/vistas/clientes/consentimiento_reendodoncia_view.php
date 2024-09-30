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
		REENDODONCIA
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
	<p>Que por el presente documento REQUIERO Y AUTORIZO a Dr. <?php echo strtoupper($datos_doctor[0]['nombre'])." ". strtoupper($datos_doctor[0]['apellidos']);?> licenciado en Medicina y Cirugía y/o Odontología con número de colegiado <?php echo  strtoupper($datos_doctor[0]['n_colegiado']) ?> y a su equipo a que realice en mi persona el tratamiento de <strong>reendodoncia,</strong> habiéndome explicado que es conveniente proceder, en mi situación, a recibir dicho tratamiento.</p>
	
			<h3 style="margin-top:30pt; margin-left:0.5pt; margin-bottom:10.6pt; text-align:center; line-height:108%; font-size:12pt">
    <strong>Características del procedimiento:</strong>
</h3>
<body>
			<p style="margin-top:10.6pt; margin-left:0.5pt; margin-bottom:0pt; text-align:left; line-height:108%">
				<strong>¿En qué consiste?</strong>
			</p>
			<p style="margin-top:10pt; margin-left:0.5pt; margin-bottom:12.65pt">
				Consiste en la eliminación de los materiales que se encuentran en el interior de los conductos radiculares de un diente y la posterior limpieza, conformación y relleno de dichos conductos.
			</p>
			<p style="margin-top:12.65pt; margin-left:0.5pt; margin-bottom:0pt; text-align:left; line-height:108%">
				<strong>¿Para qué sirve?</strong>
			</p>
			<p style="margin-top:10pt; margin-left:0.5pt; margin-bottom:12.65pt">
				Es una intervención que se realiza cuando una endodoncia no tenido éxito, para intentar conservar el diente en el lugar que ocupa en la arcada dentaria, manteniendo una función adecuada.
			</p>
			<p style="margin-top:0pt; margin-left:0.5pt; margin-bottom:0pt; text-align:left; line-height:108%">
				<strong>¿Cómo se hace?</strong>
			</p>
			<p style="margin-top:10pt; margin-bottom:8pt; text-align:left; line-height:108%">
				&#xa0;
			</p>
			<p style="margin-top:-20pt; margin-left:0.5pt; margin-bottom:27.05pt">
				Tras anestesiar localmente la zona, se realiza una cavidad para acceder al interior del diente. Luego se eliminan los materiales de relleno utilizados en la endodoncia previa, se limpian los conductos radiculares y se rellenan con un material adecuado. Finalmente se coloca un empaste provisional que deberá ser sustituido por uno definitivo. Será necesario el uso de radiografías dentales intraorales para realizar el procedimiento. Dependiendo del estado previo del diente y de la anatomía que presente, pue<span style="text-decoration:line-through">d</span>en ser necesarias una o más sesiones.
			</p>
            <p style="margin-top:-18pt; margin-left:0.5pt; margin-bottom:10.25pt">
                El  procedimiento requiere la aplicación de anestesia local, lo que provocará una sensación de acorchamiento del labio o de la cara que desaparecerá al cabo de unas horas. También, que la administración de la anestesia podría producir ulceración y/o hematoma del tejido, dolor y en raras ocasiones, pérdida de la sensibilidad en la boca o en la cara. Así mismo puede provocar bajada de tensión y mareo.
            </p>
			<h3 style="margin-top:27.05pt; margin-left:0.5pt; margin-bottom:0pt; text-align:center; line-height:108%">
    Riesgos y complicaciones:
</h3>

			</p>
			<p style="margin-top:10pt; margin-left:0.5pt; margin-bottom:12.65pt">
				Cualquier intervención en un paciente conlleva riesgos. La mayor parte de las veces no se presentan complicaciones, pero a veces sí, por lo que es importante que estas se conozcan. Las más frecuentes son:
			</p>
			<p style="margin-top:12.65pt; margin-left:0.5pt; margin-bottom:0pt; text-align:left; line-height:108%">
				<strong>A) Referidas al uso de anestésicos locales: </strong>
			</p>
			<p style="margin-top:10pt; margin-left:0.5pt; margin-bottom:12.65pt">
				Interrupción de la función sensitiva (sensación de hormigueo) generalmente de forma temporal, ulceración en la mucosa, aparición de hematomas y alteraciones generalmente transitorias como bloqueo de la articulación temporomandibular, crisis vaso-vagal consistente en sudoración fría, sensación de mareo, bajada de tensión arterial e incluso lipotimia. En el caso de existir alguna alergia conocida a los anestésicos debe avisar siempre al profesional. Si hubiera alguna alergia desconocida pueden producirse alteraciones del tipo urticaria, edema de glotis e incluso <em>shock</em> anafiláctico, que necesitarían otros medios de reanimación.
			</p>
			<p style="margin-top:12.65pt; margin-left:0.5pt; margin-bottom:0pt; text-align:left; line-height:108%">
				<strong>B) Referidas al tratamiento en sí: </strong>
			</p>
			<p style="margin-top:10pt; margin-left:0.5pt; margin-bottom:28.2pt">
				Durante la fase de apertura y limpieza de los conductos pueden aparecer alteraciones no detectables que imposibiliten la continuación del tratamiento de conductos tales como: existencia de una red compleja de curvaturas o calcificaciones imposibles de trabajar con los instrumentos disponibles actualmente, comunicaciones con los tejidos periodontales, 
			o bien contratiempos como la fractura de algún instrumento que el profesional intentará subsanar, pero que en el caso de no lograrse complicarían el pronóstico del tratamiento.
</p>
			<p style="margin-top:12.65pt; margin-left:0.5pt; margin-bottom:0pt; text-align:left; line-height:108%">
				<strong>C) Referidas al futuro del tratamiento:</strong>
			</p>
			<p style="margin-top:10pt; margin-left:0.5pt; margin-bottom:13.2pt;line-height:108%">
				Una vez finalizado el tratamiento es necesario realizar la restauración definitiva del diente. Si dicha reconstrucción no fuese la indicada o se retrasase en exceso o el paciente tuviera hábitos de apretamiento (bruxismo) o mordiese accidentalmente alimentos muy duros sobre los dientes desvitalizados, podrían producirse fisuras verticales o fracturas radiculares que empeorarían el pronóstico restaurador, pudiendo llegar a implicar la necesidad de extraer el diente.
			</p>
			<p style="margin-top:10pt; margin-left:0.5pt; margin-bottom:12.65pt; ">
				Una vez terminado el tratamiento (o entre las sesiones) el diente puede quedar sensibilizado a la presión durante un periodo más o menos largo pudiendo necesitar algún analgésico. En algunos casos, puede llegar a producirse la inflamación de los tejidos próximos al diente, pudiendo ser necesaria en tal caso la administración de antibióticos.
			</p>
			<p style="margin-top:12.65pt; margin-left:0.5pt; margin-bottom:12.65pt; ">
				Podrán ser necesarias revisiones y radiografías algún tiempo después de realizar el tratamiento para comprobar su evolución y pronóstico definitivo. Puede producirse algún cambio de coloración externa en la corona o bien necesitar algún otro procedimiento como el retratamiento no quirúrgico o la cirugía periapical, si con el primer tratamiento no se consiguiesen los objetivos, ya que en el organismo humano nunca podemos tener <em>a priori</em> la garantía absoluta del éxito.
			</p>
			<h3 style="margin-top:12.65pt; margin-left:0.5pt; margin-bottom:0pt; text-align:center; line-height:108%">
    Riesgos personalizados:
</h3>

			
			<p style="margin-top:0pt; margin-bottom:8pt; text-align:left; line-height:108%">
				&#xa0;
			</p>
			<p style="margin-top:8pt; margin-left:0.5pt; margin-bottom:11.9pt; line-height:108%">
				Se hará siempre una historia clínica de las enfermedades que afecten al paciente para comprobar la idoneidad del retratamiento de conductos (reendodoncia).
			</p>
			<p style="margin-top:11.9pt; margin-bottom:13.2pt; text-align:left; line-height:108%">
			</p>
			<h4 style="margin-top: 13.2pt ; text-align: left; line-height: 108%">
    Información de su interés:
</h4>
<p style="margin-top: 10pt; line-height: 1.6;">
    <span style="color: #181717;">Usted tiene derecho a conocer el procedimiento dental al que va a ser sometido y los riesgos y complicaciones más frecuentes que pueden ocurrir. En su actual estado clínico, los beneficios derivados de la realización de este tratamiento superan los posibles riesgos. Por este motivo se le indica la conveniencia de que le sea practicado. Si aparecieran complicaciones, el personal médico que le atiende está capacitado y dispone de medios para tratar de resolverlas. Por favor, lea atentamente este documento y consulte con su dentista las dudas que pueda tener.</span>
</p>
<p style="margin-top: 0pt; margin-left: 0.5pt; margin-bottom: 0pt;text-indent: -0.5pt; line-height: 108%">
    <span style="font-weight: normal;">&#xa0;</span>
</p>

			<p style="margin-top:0pt; margin-left:0.5pt; margin-bottom:0p; text-indent:-0.5pt; text-align:left; line-height:108%">
				<strong>Alternativas al procedimiento:</strong>
			</p>
			<p style="margin-top:0pt; margin-left:0.5pt; margin-bottom:12.65pt">
				La extracción del diente.
			</p>
			<h1 style="margin:12.65pt 0.15pt 11.8pt 0.5pt; text-align:center; line-height:108%">
				&#xa0;
			</h1>
			<h1 style="margin:11.8pt 0.15pt 11.8pt 0.5pt; text-align:center; page-break-inside:avoid; page-break-after:avoid; line-height:108%">
				CONSENTIMIENTO INFORMADO – REENDODONCIA
			</h1>
			<p style="margin-top:11.8pt; margin-left:0.5pt; margin-bottom:13.2pt; text-align:left; line-height:98%">
				Para satisfacción de los <strong>derechos del paciente</strong>, como instrumento favorecedor del correcto uso de los Procedimientos Terapéuticos y Diagnósticos, y en cumplimiento de la Ley General de Sanidad y la ley 41/2002:
			</p>
			<p style="margin-top:13.2pt; margin-left:0.5pt; margin-bottom:12.65pt">
				Yo, D/Dª <?php echo strtoupper($datos_firma[0]['cliente']); ?>&#xa0; como paciente, <?php if ($datos_firma[0]['edad']<18){ ?> y D/Dña. <?php echo strtoupper($datos_firma[0]['nombre_tutor']);  ?> como padre, madre o tutor y DNI <?php echo $datos_firma[0]['dni_tutor'];  } ?> en pleno uso de mis facultades, libre y voluntariamente, DECLARO:
			</p>
			<p style="margin-top:12.65pt; margin-left:0.5pt; margin-bottom:0.55pt">
				Que el/la Dr/Dra: <?php echo strtoupper($datos_doctor[0]['nombre'])." ". strtoupper($datos_doctor[0]['apellidos']); ?>
			</p>
			<p style="margin-top:0.55pt; margin-left:0.5pt; margin-bottom:0.55pt">
				me ha explicado, en términos asequibles, que necesito un <strong>retratamiento endodóntico</strong>
                <?php if ($descripcionTratamiento != null){ ?>
                    en el/los diente/s <strong><span style="color:#181717"><?php if (isset($descripcionTratamiento)){ echo  $descripcionTratamiento; } else { echo $datos_firma[0]['descripcionTratamiento']; } ?></span></strong>
                <?php } ?>
                y me han sido expuesto los posibles riesgos generales contenidos en las hojas informativas adjuntas, publicadas por la Asociación Española de Endodoncia (AEDE), permitiéndome realizar todas las observaciones y preguntas para aclarar mis dudas.             
			</p>
			<p style="margin-top:8pt; margin-bottom:4.9pt">
				Consiento que se me tomen fotografías&#xa0; o registros en otros tipos de soporte audiovisual, antes, durante o despues de la intervención quirúrgica, para facilitar el avance del conocimiento científico y la docencia. En todos los casos será resguardada la identidad del paciente.
			</p>
			<p style="margin-top:4.9pt; margin-bottom:4.9pt">
				&#xa0;
			</p>
			<p style="margin-top:4.9pt; margin-left:0pt; margin-bottom:5.15pt">
    Manifiesto que estoy satisfecho con la información recibida y que comprendo el alcance y los riesgos del tratamiento.
</p>
<p style="margin-top:5.15pt; margin-left:0pt; margin-bottom:5.3pt">
   También comprendo que, en cualquier momento y sin necesidad de dar ninguna explicación, puedo revocar el consentimiento que ahora presto.
</p>
<p style="margin-top:5.3pt; margin-bottom:25.65pt; text-align:left; margin-right:0pt;">
    <span style="color:#000000; display:inline-block"></span>Y en tales condiciones <strong>CONSIENTO</strong> en que se me realice el <strong>retratamiento endodóntico</strong><span style="font-family:'Times New Roman'">.</span>
</p>


<h3>
		DECLARACIONES Y FIRMAS
	</h3>
	
	<p>Se me ha dado tiempo suficiente para leer la información precedente que describe el tratamiento de reendodoncia. Comprendo los beneficios, riesgos e inconvenientes asociados al tratamiento. <br><br>He sido suficientemente informado y he tenido la oportunidad de hacer preguntas y discutir las preocupaciones sobre el tratamiento de reendodoncia con los productos con mi dentista que me ofrece el tratamiento.  <br><br>Por la presente doy mi consentimiento para el tratamiento de reendodoncia que ha sido prescrito por mi dentista.
			</p>
			
			<p>
				Autorizo a mi dentista a divulgar mis registros, incluyendo, pero no limitándose a radiografías, historial médico, fotografías, modelos de yeso o impresiones de dientes, prescripciones, diagnósticos y otros registros de tratamiento al proveedor y compañías asociadas. <br><br>Esto es con el propósito de investigar y revisar mi caso, en lo que se refiere al tratamiento de endodoncia con sus producto(s) y/o con fines educativos/de investigación. 
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