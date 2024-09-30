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
		PRÓTESIS FIJA
	</h2>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span>
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
	<p>Que por el presente documento REQUIERO Y AUTORIZO a Dr. <?php echo strtoupper($datos_doctor[0]['nombre'])." ". strtoupper($datos_doctor[0]['apellidos']);?> licenciado en Medicina y Cirugía y/o Odontología con número de colegiado <?php echo  strtoupper($datos_doctor[0]['n_colegiado']) ?> y a su equipo a que realice en mi persona el tratamiento de <strong>prótesis fija,</strong> habiéndome explicado que es conveniente proceder, en mi situación, a recibir dicho tratamiento.</p>
	
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="font-family:Arial">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span><span style="width:7.06pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="font-family:Arial">&#xa0;&#xa0; </span><span style="width:3.53pt; font-family:Arial; display:inline-block">&#xa0;</span>
			</p>
			

		
			</p>
			<p style="margin-top:10pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; ">
				<span style="font-family:Arial">• Que se me ha explicado que es necesario que se me realice una prótesis fija en mi boca con el fin de restituir mis dientes ausentes y recuperar la función y la estética. Además, he comprendido que dicha prótesis se sujeta a mis dientes naturales y que no podrá ser retirada por mis propios medios.</span>
			</p>
			<p style="margin-top:10pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; ">
				<span style="font-family:Arial">• Que antes de iniciar dicho tratamiento he sido informado/a de que: </span>
			</p>
			<p style="margin-top:10pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; ">
				<span style="font-family:Arial">- El procedimiento puede requerir la aplicación de anestesia local, lo que provocará una sensación de acorchamiento del labio o de la cara que desaparecerá al cabo de unas horas. También, que la administración de la anestesia podría producir ulceración y/o hematoma del tejido, dolor y, en raras ocasiones, pérdida de la sensibilidad en la boca o en la cara. Más raramente, puede provocar bajada de tensión y mareo.</span>
			</p>
			<p style="margin-top:10pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; ">
				<span style="font-family:Arial">- Para la realización de una prótesis fija es imprescindible tallar o desgastar uno o más dientes para darles una forma y tamaño específicos. En ocasiones desfavorables, el tallado puede provocar un daño en el nervio del diente que obligue a realizar una endodoncia.</span>
			</p>
			<p style="margin-top:10pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; ">
				<span style="font-family:Arial">- La confección de una prótesis fija requiere diversas visitas al odontólogo. Para que durante estas fases los dientes estén protegidos y podamos hacer una vida normal, estos llevarán coronas provisionales, que se pegan con un cemento también provisional. A pesar de esto, es frecuente que con estos provisionales exista un aumento de la sensibilidad del diente ante distintos estímulos (frío, calor, masticación, etc).</span>
			</p>
			<p style="margin-top:10pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; ">
				<span style="font-family:Arial">- Respecto a la prótesis definitiva, aunque los materiales cerámicos modernos tienen un aspecto muy natural, en ocasiones es imposible reproducir el brillo y tono exacto de los dientes originales.</span>
			</p>
			<p style="margin-top:10pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; ">
				<span style="font-family:Arial">- Una vez cementada la prótesis comienza un periodo de acostumbramiento de unas pocas semanas, durante el cual nuestra boca se va adaptando a la nueva situación durante la masticación y el habla. Además, podría haber un aumento de la salivación que también cederá progresivamente.</span>
			</p>
			<p style="margin-top:10pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span style="font-family:Arial">- Debido a las potentes fuerzas masticatorias, el cemento bajo las prótesis puede ir experimentando un deterioro que culmine con el hipotético despegamiento de la prótesis. La mayoría de estos casos se solucionan con una nueva cementación.</span>
			</p>
			<p style="margin-top:10pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span style="font-family:Arial">- Debajo de los pónticos (coronas que sustituyen a los dientes ausentes) se pueden acumular con mayor facilidad restos alimenticios, por lo que será recomendable extremar la higiene mediante las maniobras y los instrumentos que su odontólogo le aconseje, a fin de evitar la inflamación de las encías y el mal aliento, así como mantener el buen aspecto de la prótesis.</span>
			</p>
			<h3>
		DECLARACIONES Y FIRMAS
	</h3>
	
	<p>Se me ha dado tiempo suficiente para leer la información precedente que describe el tratamiento de prótesis fija. Comprendo los beneficios, riesgos e inconvenientes asociados al tratamiento. <br><br>He sido suficientemente informado y he tenido la oportunidad de hacer preguntas y discutir las preocupaciones sobre el tratamiento de prótesis fija con los productos con mi dentista que me ofrece el tratamiento.  <br><br>Por la presente doy mi consentimiento para el prótesis fija que ha sido prescrito por mi dentista.
			</p>
			<p>
				Entiendo que el prótesis fija no es una ciencia exacta. Reconozco que mi dentista y no tiene ni puede dar ninguna garantía o seguridad sobre el resultado de mi tratamiento. <br><br>Entiendo la empresa proveedora no es un proveedor de servicios médicos, odontológicos o de atención de la salud y no practica ni puede practicar la odontología ni dar consejos médicos. <br><br>Ni mi dentista ni el proveedor me han dado ningún tipo de garantías sobre el resultado específico de mi tratamiento. <br><br>
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