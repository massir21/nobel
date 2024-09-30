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
		BLANQUEAMIENTO
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
	<p>Que por el presente documento REQUIERO Y AUTORIZO a Dr. <?php echo strtoupper($datos_doctor[0]['nombre'])." ". strtoupper($datos_doctor[0]['apellidos']);?> licenciado en Medicina y Cirugía y/o Odontología con número de colegiado <?php echo  strtoupper($datos_doctor[0]['n_colegiado']) ?> y a su equipo a que realice en mi persona el tratamiento de <strong>blanqueamiento dental externo,</strong> habiéndome explicado que es conveniente proceder, en mi situación, a recibir dicho tratamiento.</p>
	<h2>Descripción del tratamiento de blanqueamiento dental externo:</h2>
	<ul>
		<li>El blanqueamiento o aclaramiento dental externo consiste, fundamentalmente, en la aplicación en diversas
			sesiones en la clínica o siguiendo una pauta de tiempo y frecuencia limitada en casa, de un gel blanqueador
			que, al liberar oxígeno, actúa sobre los tejidos duros dentarios, aclarándolos progresivamente. El resultado
			final consiste en una sonrisa más clara, más luminosa. </li>
		<li>Los dientes pueden presentar un color oscuro por distintos motivos, como la edad, colorantes de la dieta
			(té, café, vino tinto, bebidas de cola, granada, etc.) y tabaco, o consumo de determinados fármacos. El
			odontólogo determinará el origen de la tinción y la forma de tratarla. </li>
		<li>La aplicación del producto blanqueador en forma de gel se realiza, generalmente, mediante su colocación en
			cubetas preformadas adaptadas a los dientes del paciente. Es necesario que éstas se mantengan en la boca
			durante unas horas determinadas al día para lograr los mejores resultados. </li>
		<li>Las cubetas personalizadas se confeccionan a medida, logrando un ajuste máximo a la forma de los dientes.
			Esto conlleva que la cantidad de gel que se coloque debe ser reducida, con el objetivo de prolongar la vida
			del producto y facilitar la evacuación del sobrante .</li>
		<li>El principal efecto secundario asociado consiste en el aumento de la sensibilidad de los dientes(a
			estímulos, principalmente el frío), sobre todo en pacientes que ya presentan hipersensibilidad. Estas
			molestias, en caso de aparecer, están asociadas a la aplicación del producto de blanqueamiento, por lo que
			desaparecen completamente cuando se finaliza el mismo. El odontólogo dispone de diversas formas para reducir
			esta hipersensibilidad. </li>
		<li>Complicaciones mayores, como reacciones alérgicas, son infrecuentes. Para prevenir posibles efectos
			indeseados es fundamental que nos advierta de cualquier alergia (sobre todo a peróxidos) o enfermedad que
			padezca, así como de los medicamentos que esté tomando.</li>
		<li>El producto blanqueador sólo tiene efecto en los dientes naturales, es decir, tanto las coronas (fundas)
			como las obturaciones (empastes) previos no modifican su aspecto, pudiendo existir una disparidad de color
			una vez concluido el blanqueamiento. </li>
		<li>La capacidad de aclaramiento de los dientes es limitada. Del mismo modo no se puede predecir el grado de
			aclaramiento que se va a producir. El profesional es el que debe valorar la situación y será quien decida
			sobre la posible prolongación o modificación del tratamiento.</li>
		<li>La zona cervical de los dientes (junto a la encía), debido a su anatomía, es difícil de blanquear,
			permaneciendo siempre algo más oscura que el resto del diente. Por la misma razón, los caninos se aclaran
			menos que los incisivos.</li>
		<li>En casos de discoloraciones dentarias muy severas pueden no lograrse resultados completamente
			satisfactorios. Los dientes más oscuros o con discoloraciones severas requerirán mayor tiempo de
			tratamiento. Las manchas blancas opacas o bandas que algunos dientes presentan normalmente no desaparecen,
			si bien se aclaran.</li>
		<li>El mantenimiento del color que se obtenga depende de diversos factores. Cuanto mayor sea la presencia de
			hábitos perjudiciales (tabaco) o el consumo de alimentos o bebidas coloreadas en la dieta, menor será la
			duración y mayor probabilidad existirá de recidiva. He entendido que, con la edad, los dientes tienden a ser
			menos claros.</li>
	</ul>

	<h3>
		DECLARACIONES Y FIRMAS
	</h3>
	
	<p>Se me ha dado tiempo suficiente para leer la información precedente que describe el tratamiento de blanqueamiento dental externo. Comprendo los beneficios, riesgos e inconvenientes asociados al tratamiento. <br><br>He sido suficientemente informado y he tenido la oportunidad de hacer preguntas y discutir las preocupaciones sobre el tratamiento de blanqueamiento dental externo con los productos con mi dentista que me ofrece el tratamiento.  <br><br>Por la presente doy mi consentimiento para el tratamiento de blanqueamiento dental externo que ha sido prescrito por mi dentista.
			</p>
			<p>
				Entiendo que el blanqueamiento dental no es una ciencia exacta. Reconozco que mi dentista y no tiene ni puede dar ninguna garantía o seguridad sobre el resultado de mi tratamiento. <br><br>Entiendo la empresa proveedora no es un proveedor de servicios médicos, odontológicos o de atención de la salud y no practica ni puede practicar la odontología ni dar consejos médicos. <br><br>Ni mi dentista ni el proveedor me han dado ningún tipo de garantías sobre el resultado específico de mi tratamiento. <br><br>
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