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
		OBTURACIÓN
	</h2>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<span style="font-family:Arial">&#xa0;</span>
			</p>
			<?php if ($datos_firma[0]['edad']<18){ ?>
          <p>
              <span style="font-family:Arial">Yo, Don/Doña, </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['nombre_tutor']);  ?></span></strong>
              <span style="font-family:Arial">con DNI nº </span><strong><span style="font-family:Arial; "><?php echo $datos_firma[0]['dni_tutor']; ?></span></strong><span style="font-family:Arial">, en calidad de padre, madre, tutor/a o representante legal del paciente: </span>
          </p>
      <?php } ?>
	
	
	<p>Don/Doña, <?php echo strtoupper($datos_firma[0]['cliente']); ?> de <?php echo $datos_firma[0]['edad']; ?> años de edad, con domicilio en <?php echo $datos_firma[0]['direccion']; ?> y DNI <?php echo $datos_firma[0]['dni']; ?></p>
	<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<span style="font-family:Arial">&#xa0;</span>
			</p>

			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<span style="font-family:Arial">&#xa0;</span>
			</p>
			
			
			<diV style="font-size: 16px; text-align: center;">
      <b>DECLARO</b><br><br>
    </diV>
			
<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
   
<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
    <span style="font-family:Arial">&#xa0;</span>
</p>

			<p style="margin-top:0pt; margin-bottom:10pt; line-height:normal; widows:0; orphans:0;">
				<span style="font-family:Arial">Que se me ha explicado que es necesario que se me realice una OBTURACIÓN o EMPASTE en el/los diente/s: </span>
                <strong><span ><?php if (isset($descripcionTratamiento)){ echo  $descripcionTratamiento; } else { echo $datos_firma[0]['descripcionTratamiento']; } ?></span></strong>
			</p>
			<ul style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
    <li style="font-family:Arial; margin-bottom: 10px;">Que se me ha explicado que es necesario que se me realice una OBTURACIÓN o EMPASTE en el/los diente/s: <strong><?php if (isset($descripcionTratamiento)){ echo  $descripcionTratamiento; } else { echo $datos_firma[0]['descripcionTratamiento']; } ?></strong></li>
    <li style="font-family:Arial; margin-bottom: 10px;">Que el propósito principal de la intervención es restaurar los tejidos dentarios duros y proteger la pulpa, para conservar el diente/molar y su función, restableciendo al tiempo, siempre que sea posible, la estética adecuada.</li>
    <li style="font-family:Arial; margin-bottom: 10px;">Que el tratamiento que voy a recibir implica la administración de anestesia local, que consiste en proporcionar, mediante una inyección, sustancias que provocan un bloqueo reversible de los nervios de tal manera que se inhibe transitoriamente la sensibilidad con el fin de realizar el tratamiento sin dolor.</li>
    <li style="font-family:Arial; margin-bottom: 10px;">Que la administración de la anestesia puede provocar, en el punto en el que se administre, ulceración de la mucosa y dolor, heridas por mordedura de las zonas insensibilizadas y menos frecuentemente, anestesia o parestesias en la zona inervada por lesión del nervio herido durante algunas semanas, limitaciones en el movimiento de la apertura de la boca, que pueden requerir tratamiento ulterior. La anestesia también puede provocar bajada de tensión que en casos menos frecuentes, pueden provocar un síncope o fibrilación ventricular, que deben tratarse posteriormente, e incluso excepcionalmente la muerte.</li>
    <li style="font-family:Arial; margin-bottom: 10px;">Que aunque de mis antecedentes personales no se deducen posibles alergias al agente anestésico, puede provocar urticaria, dermatitis, asma, edema angioneurótico (asfixia), que incluso puede requerir tratamiento urgente.</li>
    <li style="font-family:Arial; margin-bottom: 10px;">Aunque es absolutamente excepcional, un movimiento brusco de la cabeza durante la administración de la anestesia, podría provocar una rotura de la aguja, que no siempre puede retirarse sin causar daños colaterales por las heridas que hay que infligir en la zona hasta localizar el fragmento (aunque dejarlo sin extraer suele ser perfectamente tolerado).</li>
    <li style="font-family:Arial; margin-bottom: 10px;">Que la intervención consiste en limpiar la cavidad de tejido enfermo y rellenarla posteriormente con material de obturación para conseguir un sellado hermético, conservando el diente/molar.</li>
    <li style="font-family:Arial; margin-bottom: 10px;">Que es frecuente que se produzca una mayor sensibilidad, sobre todo al frío, en los días posteriores, que normalmente desaparecerá de modo espontáneo en un período variable de tiempo.</li>
    <li style="font-family:Arial; margin-bottom: 10px;">Que durante las primeras 24 horas tras realizar el tratamiento, el material restaurador puede absorber pigmentos, por lo que se recomienda evitar consumir bebidas oscuras durante este periodo de tiempo con el fin de evitar tinciones.</li>
    <li style="font-family:Arial; margin-bottom: 10px;">Que es recomendable que vuelva a visitarle lo más pronto posible, si advierto signos de movilidad o alteraciones de la oclusión, pues sería preciso ajustarla para aliviar el dolor y para impedir la formación de periodontitis y/o trauma.</li>
    <li style="font-family:Arial; margin-bottom: 10px;">Que la eliminación de la caries puede reactivar procesos infecciosos que hagan necesaria la endodoncia y que, especialmente si la caries es profunda, el diente/molar quedará frágil y podrá ser necesario llevar a cabo otro tipo de reconstrucción o colocar una corona protésica.</li>
    <li style="font-family:Arial; margin-bottom: 10px;">Que es posible que no me encuentre satisfecho con la forma y el color del diente tras el tratamiento, porque las cualidades de las restauraciones directas nunca serán idénticas al aspecto de diente sano.</li>
    <li style="font-family:Arial; margin-bottom: 10px;">Que, en caso de sustitución de una amalgama por una obturación estética, puede aumentar la sensibilidad del diente e incluso puede que tenga que endodonciar (matar el nervio) de la pieza.</li>
    <li style="font-family:Arial; margin-bottom: 10px;">Que todo acto terapeútico lleva implícitas una serie de complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios tanto médicos como quirúrgicos.</li>
    <li style="font-family:Arial; margin-bottom: 10px;">Doy autorización para que se me efectúen radiografías, fotografías y/o sea filmada la intervención que se me efectúe.</li>
    <li style="font-family:Arial; margin-bottom: 10px;">Que sé que en algún momento en que se tenga que trasmitir información entre doctor y el protésico (laboratorio) de mis datos, impresiones, moldes de yeso, fotografías o vídeos, pueden ser enviados entre ambos de forma directa o a través de correo electrónico y lo autorizo.</li>
</ul>


<ul style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
    <?php if ($descripcionRiesgos != null){ ?>
        <li style="font-family:Arial; margin-bottom: 10px;">
            Que en mi caso particular: <strong><?php if (isset($descripcionRiesgos)){ echo  $descripcionRiesgos; } else { echo $datos_firma[0]['descripcionRiesgos']; } ?></strong>
        </li>
    <?php } ?>
    <li style="font-family:Arial; margin-bottom: 10px;">Que debido a las características individuales de cada persona es imposible predecir todas las complicaciones derivadas del acto terapéutico que se me va a realizar y que es necesario para establecer el estado de salud oral.</li>
    <li style="font-family:Arial; margin-bottom: 10px;">He comprendido las explicaciones que se me han facilitado en un lenguaje claro y sencillo, y el facultativo que me ha atendido me ha permitido realizar todas las observaciones y me ha aclarado todas las dudas que le he planteado.</li>
    <li style="font-family:Arial; margin-bottom: 10px;">Que en cualquier momento y sin necesidad de dar ninguna explicación, puedo revocar el consentimiento que ahora presto.</li>
    <li style="font-family:Arial; margin-bottom: 10px;">
        Por ello manifiesto que estoy satisfecho con la información recibida y que comprendo el alcance y riesgos del acto, es por ello que DOY MI CONSENTIMIENTO para que se me efectúe el tratamiento arriba indicado.<br/><br/>
        Y, para que así conste, firmo el presente original después de leído.
    </li>
</ul>


			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; ">
				<span style="font-family:Arial">&#xa0;</span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span style="font-family:Arial">D./ Dª </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['cliente']); ?> </span></strong><span style="font-family:Arial">y con DNI nº </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['dni']); ?> </span></strong><span style="font-family:Arial">&#xa0;</span><span style="font-family:Arial">declaro que el/la facultativo/a, Dr/Dra</span><strong><span style="font-family:Arial; "> <?php echo strtoupper($datos_doctor[0]['nombre'])." ". strtoupper($datos_doctor[0]['apellidos']); ?> </span></strong><span style="font-family:Arial">me ha explicado de forma satisfactoria qué es, cómo se realiza y para qué sirve esta exploración/intervención. También me ha explicado los riesgos existentes, las posibles molestias o complicaciones, que éste es el procedimiento más adecuado para mi situación clínica actual, y las consecuencias previsibles de su no realización. He comprendido perfectamente todo lo anterior, he podido aclarar las dudas planteadas, y doy mi consentimiento para que me realicen dicha exploración/intervención. He recibido copia del presente documento. Sé que puedo retirar este consentimiento cuando lo desee.</span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span style="font-family:Arial">&#xa0;</span>
			</p>

			<h3>
		DECLARACIONES Y FIRMAS
	</h3>
	
			<p>
				Entiendo que el obturación dental no es una ciencia exacta. Reconozco que mi dentista y no tiene ni puede dar ninguna garantía o seguridad sobre el resultado de mi tratamiento. <br><br>Entiendo la empresa proveedora no es un proveedor de servicios médicos, odontológicos o de atención de la salud y no practica ni puede practicar la odontología ni dar consejos médicos. <br><br>Ni mi dentista ni el proveedor me han dado ningún tipo de garantías sobre el resultado específico de mi tratamiento. <br><br>
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