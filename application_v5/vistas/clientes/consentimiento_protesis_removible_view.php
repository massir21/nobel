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
		PRÓTESIS REMOVIBLE
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
	<p>Que por el presente documento REQUIERO Y AUTORIZO a Dr. <?php echo strtoupper($datos_doctor[0]['nombre'])." ". strtoupper($datos_doctor[0]['apellidos']);?> licenciado en Medicina y Cirugía y/o Odontología con número de colegiado <?php echo  strtoupper($datos_doctor[0]['n_colegiado']) ?> y a su equipo a que realice en mi persona el tratamiento de <strong>prótesis removible,</strong> habiéndome explicado que es conveniente proceder, en mi situación, a recibir dicho tratamiento.</p>
	
			
			
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; ">
				<span style="font-family:Arial">&#xa0;</span>
			</p>
			
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span style="font-family:Arial">• Que se me ha explicado que es necesario que se me realice una prótesis removible para mi boca con el fin de restituir mis dientes ausentes, además de mejorar la función y la estética. Dicha prótesis podrá ser retirada por mí en cualquier momento.</span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span style="font-family:Arial">&#xa0;</span><span style="font-family:Arial">• Que antes de iniciar dicho tratamiento he sido informado/a de que: </span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span style="font-family:Arial">- Esta prótesis consta de dientes artificiales que se sujetan sobre una base acrílica y/o metálica. Dicha estructura se apoya, a su vez, sobre la encía y se sujeta a dientes remanentes (si es que existen) mediante retenedores directos (ganchos) o indirectos (ataches) o incluso a implantes.</span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span style="font-family:Arial">- La capacidad de trituración ycorte de estos dientes artificialesserá menor que la de los dientes naturales. Además, en determinados casos y ante algunos alimentos especialmente duros puede notarse cierto balanceo de la prótesis.</span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; ">
				<span style="font-family:Arial">- Respecto a la estética, aunque el material de los dientes artificiales imitará elcolor y forma de los dientes naturales, la reproducción exacta en brillo y tono puede no conseguirse. Además, los retenedores pueden ser visibles y conllevar una merma en la estética de la boca.</span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; ">
				<span style="font-family:Arial">- Para la realización de una prótesis removible es imprescindible acudir a varias citas al odontólogo, ya que necesita diversas fases durante su elaboración, colocación y adaptación en las primeras semanas. De hecho, en este tiempo, es normal que el paciente note un cuerpo extraño, un aumento de la cantidad de saliva y dificultades para hablar y/o masticar que, en ocasiones, causan dolor y pequeñas úlceras en las zonas de apoyo. El odontólogo revisará esta fase de acostumbramiento y realizará las intervenciones o tratamientos indicados para solventar estos inconvenientes iniciales.</span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; ">
				<span style="font-family:Arial">- Asimismo, debido al paso del tiempo y uso de la prótesis, pueden surgir pequeños desajustes en su sujeción, desajustándose, por lo que puede ser necesario añadir algún material (rebase) para recuperar la mejor fijación posible. Existen diversos materiales cementantes (pegamentos) para optimizar la fijación de la prótesis y el confort de los tejidos en los que ésta apoya. Pregunte a su odontólogo. </span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; ">
				<span style="font-family:Arial">- Es imprescindible que los pacientes con prótesis removible acudan periódicamente al odontólogo para revisarse la prótesis y la boca. Es importante saber que los dientes pilares (los que quedan en la boca) aumentan la probabilidad de tener caries o desgastes.</span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; ">
				<span style="font-family:Arial">- Debido a que debajo de la prótesis se pueden acumular con mayor facilidad restos alimenticios, es necesario cumplir con unas medidas de higiene correctas, con el fin de reducir el riesgo de caries en los dientes naturales que hubiera, la salud de las encías y el buen aspecto de la prótesis. Deberé seguir las instrucciones de cuidado, mantenimiento e higiene que el odontólogo me indique.</span>
			</p>
		
			<h3>
		DECLARACIONES Y FIRMAS
	</h3>
	
	<p>Se me ha dado tiempo suficiente para leer la información precedente que describe el tratamiento de prótesis removible. Comprendo los beneficios, riesgos e inconvenientes asociados al tratamiento. <br><br>He sido suficientemente informado y he tenido la oportunidad de hacer preguntas y discutir las preocupaciones sobre el tratamiento de prótesis removible con los productos con mi dentista que me ofrece el tratamiento.  <br><br>Por la presente doy mi consentimiento para el tratamiento de prótesis removible que ha sido prescrito por mi dentista.
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