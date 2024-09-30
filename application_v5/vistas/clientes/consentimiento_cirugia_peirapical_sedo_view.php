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
	RETRATAMIENTO QUIRÚRGICO O CIRUGÍA PERIAPICAL
	</h2>
	<br> <?php if ($datos_firma[0]['edad'] < 18): ?>
    <p>
        <span style="font-family:Arial">Yo, Don/Doña, </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['nombre_tutor']);  ?></span></strong>
        <span style="font-family:Arial">con DNI nº </span><strong><span style="font-family:Arial; "><?php echo $datos_firma[0]['dni_tutor']; ?></span></strong><span style="font-family:Arial">, en calidad de padre, madre, tutor/a o representante legal del paciente: </span>
    </p>
	<p>Don/Doña, <?php echo strtoupper($datos_firma[0]['cliente']); ?> de <?php echo $datos_firma[0]['edad']; ?> años de edad, con domicilio en <?php echo $datos_firma[0]['direccion']; ?> y DNI <?php echo $datos_firma[0]['dni']; ?></p>
	
 
<?php endif; ?>

			<h3 style="margin-top:25.8pt; margin-left:0.5pt; margin-bottom:11.15pt;; line-height:103%;">
				Características del procedimiento:
</h3>
			<h3 style="margin-top:11.15pt; margin-left:0.5pt; margin-bottom:10pt;">
				¿En qué consiste?</h3>
			</p>
			<p style="margin-top:0pt; margin-left:0.5pt; margin-bottom:10pt; text-align:justify; line-height:99%">
				<span style="color:#181717">Como consecuencia de diferentes patologías (caries, traumatismos dentarios...) se puede producir una necrosis de la pulpa, seguida de una infección crónica en la región apical o periapical de la raíz del diente, que con el tiempo puede desarrollar un granuloma y en ocasiones quistes dentarios que pueden persistir pese a realizar un tratamiento de endodoncia convencional. La cirugía periapical trata de eliminar estas lesiones y permitir la curación de los tejidos que rodean el diente.</span>
				<h3 style="margin-top:0pt; margin-left:0.5pt; margin-bottom: -10pt">
    ¿Para qué sirve?</h3>

<p style="margin-top:0pt; margin-bottom:0pt">
    &#xa0;
</p>
<p style="margin-top:8pt; margin-left:0.5pt; margin-bottom:10pt; text-align:justify; line-height:99%">
    Es una intervención que se realiza en un diente endodonciado cuando persiste la sintomatología inflamatoria o en los controles posteriores permanecen lesiones radiolúcidas a nivel periapical, para tratar de conservar el diente en el lugar que ocupa en la arcada dentaria, manteniendo una función adecuada.
</p>



<h3 style="margin-top:13.2pt; margin-left:0.5pt; margin-bottom:10px">
    ¿Cómo se hace?</h3>

<p style="margin-top:10px; margin-left:0.5pt; margin-bottom:27.55pt; text-align:justify; line-height:99%">
    Tras anestesiar localmente la zona, se realiza el despegamiento de la encía para tener acceso a la lesión. Luego se extirpa el extr</span><span style="text-decoration:line-through; color:#181717">e</span><span style="color:#181717">mo final d</span><span style="text-decoration:line-through; color:#181717">e</span><span style="color:#181717"> la raíz, se limpia el tejido inflamatorio localizado alrededor de la misma y, en ocasiones, se realiza la obturación de los conductos radiculares con un material adecuado. En algunos casos es necesaria la reconstrucción del lecho quirúrgico mediante injertos de hueso u otros materiales sintéticos con el fin de mejorar el pronóstico del tratamiento. Finalmente se dan puntos de sutura.
</p>

            <p style="margin-top:-18pt; margin-left:0.5pt; margin-bottom:27.55pt; text-align:justify; line-height:99%">
                El  procedimiento requiere la aplicación de anestesia local, lo que provocará una sensación de acorchamiento del labio o de la cara que desaparecerá al cabo de unas horas. También, que la administración de la anestesia podría producir ulceración y/o hematoma del tejido, dolor y en raras ocasiones, pérdida de la sensibilidad en la boca o en la cara. Así mismo puede provocar bajada de tensión y mareo.
            </p>
			<h3 style="margin-top:-10pt; margin-left:0.5pt; margin-bottom:0.6pt; line-height:103%">
			Riesgos y complicaciones:</h3>
			
			<p style="margin-top:10pt; margin-left:0.5pt; margin-bottom:13.2pt; text-align:justify; line-height:99%">
				Cualquier intervención en un paciente conlleva riesgos. La mayor parte de las veces no se presentan complicaciones, pero a veces sí, por lo que es importante que estas se conozcan. Las más frecuentes son:
			</p>
			<h3 style="margin-top:13.2pt; margin-left:0.5pt; margin-bottom:0pt">
				Referidas al uso de anestésicos locales: </h3>
			</p>
			<p style="margin-top:10pt; margin-left:0.5pt; margin-bottom:13.2pt; text-align:justify; line-height:99%">
				Interrupción de la función sensitiva (sensación de hormigueo) generalmente de forma temporal, ulceración en la mucosa, aparición de hematomas y alteraciones generalmente transitorias como bloqueo de la articulación temporomandibular, crisis vaso-vagal consistente en sudoración fría, sensación de mareo, bajada de tensión arterial e incluso lipotimia. En el caso de existir alguna alergia conocida a los anestésicos debe avisar siempre al profesional. Si hubiera alguna alergia desconocida pueden producirse alteraciones del tipo urticaria, edema de glotis e incluso </span><em><span style="color:#181717">shock</span></em><span style="color:#181717"> anafiláctico, que necesitarían otros medios de reanimación.
			</p>
			<p style="margin-top:13.2pt; margin-left:0.5pt; margin-bottom:13.2pt; text-align:justify; line-height:99%">
    &#xa0;
</p>
<h3 style="margin-top:10px; margin-left:0.5pt; margin-bottom:0pt">
Referidas al tratamiento en sí:
</h3>
<ul style="margin-top:10px; margin-left:-4.85pt; margin-bottom:12.8pt; text-align:justify; line-height:103%">
    <li style="color:#181717">
        RIESGOS FRECUENTES: Inflamación local, dolor variable, dificultades para la alimentación, apertura de la boca, habla, según los casos, durante aproximadamente 3-7 días. Hemorragia postoperatoria, apertura de los puntos de sutura, hematoma o infección de los puntos de sutura.
    </li>
</ul>

</ul>


	<ul style="margin-top:10pt; margin-left:-0.5pt; margin-bottom:12.8pt; text-align:justify; line-height:103%">
    <li>
        RIESGOS POCO FRECUENTES (Cuando sean de especial gravedad y esten asociados al procedimiento por criterios científicos): Daño a los dientes vecinos. Falta de sensibilidad parcial o total, temporal o permanente del nervio dentario inferior (sensibilidad del labio inferior). Falta de sensibilidad parcial o total del nervio lingual, temporal o definitiva (de la lengua y del gusto en el lado operado). Falta de sensibilidad parcial o total del nervio infraorbitario (de la mejilla), temporal o definitiva. Infección de los tejidos o del hueso. Sinusitis. Comunicación entre la boca y la nariz o los senos maxilares. Fracturas óseas. Desplazamiento de dientes a estructuras vecinas. Tragado o aspiración de dientes o de alguna de sus partes. Rotura de instrumentos. Rotura de la aguja de anestesia.
    </li>
</ul>



			<h3 style="margin-top:0pt; margin-left:0.5pt; margin-bottom:0pt">
				Referidas al futuro del tratamiento:</h3>
			
				<p style="margin-top:10pt; margin-left:0.5pt; margin-bottom:12.8pt; text-align:justify; line-height:103%">
    <span style="color:#181717">Es muy importante la colaboración del paciente mediante una higiene oral muy escrupulosa y visitas periódicas para controles clínicos y radiográficos. Puede producirse algún cambio en la posición de la encía con respecto al diente.</span>
</p>
<p style="margin-left:0.5pt; margin-bottom:13.2pt; text-align:justify; line-height:99%">
  Si no se consiguiera el resultado esperado, podría ser necesaria la realización de una segunda cirugía periapical o bien la extracción del diente en cuestión para solucionar el problema.
</p>

			<p style="margin-top:13.2pt; margin-bottom:8pt">
				&#xa0;
			</p>
			<h3 style="margin-top:8pt; margin-left:0.5pt; margin-bottom:10pt; line-height:103%">
			Riesgos personalizados:</h3>
			
			<p>Se hará siempre una historia clínica de las enfermedades que afecten al paciente para comprobar la idoneidad de la cirugía periapical.</p>

			<p style="margin-top:5pt; margin-bottom:-10pt">
			</p>
			<p>Usted tiene derecho a conocer el procedimiento dental al que va a ser sometido y los riesgos y complicaciones más frecuentes que pueden ocurrir. En su actual estado clínico, los beneficios derivados de la realización de este tratamiento superan los posibles riesgos. Por este motivo se le indica la conveniencia de que le sea practicado. Si aparecieran complicaciones, el personal médico que le atiende está capacitado y dispone de medios para tratar de resolverlas. Por favor, lea atentamente este documento y consulte con su dentista las dudas que pueda tener.</p>

			<h3 style="margin-top:10pt; margin-left:0.5pt; margin-bottom:0pt; line-height:108%">
				Alternativas al procedimiento:</h3>
			
			<p style="margin-top:5pt; margin-left:0.5pt; margin-bottom:12.65pt; text-align:justify; line-height:103%">
				<span style="color:#181717">La extracción del diente.</span>
			</p>
			<h1 style="margin-top:12.65pt; margin-right:0.15pt; margin-bottom:0pt; text-align:center; line-height:108%; font-size:15pt">
				<span style="color:#181717">&#xa0;</span>
			</h1>
			<h1 style="margin-top:0pt; margin-right:0.15pt; margin-bottom:0pt; text-align:center; line-height:108%; font-size:15pt">
				<span style="color:#181717">&#xa0;</span>
			</h1>
			<h3 style="margin:0pt 0.05pt 0pt 0.5pt; text-align:center; line-height:108%">
				CONSENTIMIENTO INFORMADO – RETRATAMIENTO QUIRÚRGICO </h3>
			
			<p style="margin:0pt 0.05pt 11.8pt 0.5pt; text-align:center; line-height:108%; font-size:12pt">
				<strong><span style="color:#181717">&#xa0;</span></strong><strong><span style="color:#181717">O CIRUGÍA PERIAPICAL</span></strong>
			</p>
			<p style="margin:11.8pt 0.05pt 11.8pt 0.5pt; text-align:center">
				<span style="font-family:Arial">&#xa0;</span>
			</p>
			<p style="margin-top:11.8pt; margin-left:0.5pt; margin-bottom:13.2pt; line-height:98%">
				<span style="color:#181717">Para satisfacción de los </span><strong><span style="color:#181717">derechos del paciente</span></strong><span style="color:#181717">, como instrumento favorecedor del correcto uso de los Procedimientos Terapéuticos y Diagnósticos, y en cumplimiento de la Ley General de Sanidad y la ley 41/2002:</span>
	</p>
    <p style="margin-top:11.8pt; margin-left:0.5pt; margin-bottom:13.2pt; text-align:left; line-height:98%">
        Don/Doña, <?php echo strtoupper($datos_firma[0]['cliente']); ?> (paciente) de: <?php echo $datos_firma[0]['edad']; ?> años de edad, Con domicilio en
    </p>
    <p style="margin-top:11.8pt; margin-left:0.5pt; margin-bottom:13.2pt; text-align:left; line-height:98%">
        <?php echo $datos_firma[0]['direccion']; ?> y DNI <?php echo $datos_firma[0]['dni']; ?>
    </p>
    <?php if ($datos_firma[0]['edad']<18){ ?>
        <p style="margin-top:11.8pt; margin-left:0.5pt; margin-bottom:13.2pt; text-align:left; line-height:98%">
            <span style="font-family:Arial">En caso de paciente menor de edad, impedido o incapacitado Yo, </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['nombre_tutor']);  ?></span></strong>
        </p>
        <p style="margin-top:11.8pt; margin-left:0.5pt; margin-bottom:13.2pt; text-align:left; line-height:98%">
            <span style="font-family:Arial">con DNI nº </span><strong><span style="font-family:Arial; "><?php echo $datos_firma[0]['dni_tutor']; ?></span></strong><span style="font-family:Arial"> , en calidad de padre, madre, tutor/a o representante legal del paciente </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['cliente']); ?></span></strong>
        </p>
    <?php } ?>
			<h3>
		DECLARACIONES Y FIRMAS
	</h3>
	
	<p>Se me ha dado tiempo suficiente para leer la información precedente que describe el tratamiento de cirugía peirapica sedo. Comprendo los beneficios, riesgos e inconvenientes asociados al tratamiento. <br><br>He sido suficientemente informado y he tenido la oportunidad de hacer preguntas y discutir las preocupaciones sobre el tratamiento de cirugía peirapica sedo con los productos con mi dentista que me ofrece el tratamiento.  <br><br>Por la presente doy mi consentimiento para el tratamiento de cirugía peirapica sedo que ha sido prescrito por mi dentista.
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