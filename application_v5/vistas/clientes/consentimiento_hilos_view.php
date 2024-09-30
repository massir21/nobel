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
  Hilos PDO, PCL, PLL
	</h2>
	<?php if ($datos_firma[0]['edad']<18){ ?>
          <p>
              <span style="font-family:Arial">Yo, Don/Doña, </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['nombre_tutor']);  ?></span></strong>
              <span style="font-family:Arial">con DNI nº </span><strong><span style="font-family:Arial; "><?php echo $datos_firma[0]['dni_tutor']; ?></span></strong><span style="font-family:Arial">, en calidad de padre, madre, tutor/a o representante legal del paciente: </span>
          </p>
      <?php } ?>
	
	
	<p>Don/Doña, <?php echo strtoupper($datos_firma[0]['cliente']); ?> de <?php echo $datos_firma[0]['edad']; ?> años de edad, con domicilio en <?php echo $datos_firma[0]['direccion']; ?> y DNI <?php echo $datos_firma[0]['dni']; ?></p>
	
  </table>
  <diV style="font-size: 16px; text-align: center;">
      <h3>DECLARO</h3>
    </diV>
    <br>
    <p>Que por el presente documento REQUIERO Y AUTORIZO a Dr. <?php echo strtoupper($datos_doctor[0]['nombre'])." ". strtoupper($datos_doctor[0]['apellidos']); ?>, licenciada en Medicina y Cirugía con número de colegiado <?php echo  strtoupper($datos_doctor[0]['n_colegiado']) ?> y a su equipo a que realice en mi persona el tratamiento de <strong>hilos PDO, PCL, PLL</strong> , habiéndome explicado que es conveniente proceder, en mi situación, a recibir dicho tratamiento.</p>
    <br>
    <h3>BREVE EXPLICACION DEL TRATAMIENTO:</h3>
    <p>El uso, indicaciones, contraindicaciones y potenciales efectos secundarios de la implantación de hilos Polidioxanona (PDO), Policaprolactona(PCL) y Poliláctico (PLL) me han sido explicados. He contestado con toda sinceridad a las preguntas que me han sido hechas a propósito de mis antecedentes médicos y estéticos. He podido hacer todas las preguntas que he estimado oportuno y las respuestas dadas fueron totalmente satisfactorias. </p>
    <p style="margin-top: 5pt"><strong>Indicaciones:</strong> </p>
    <p>Método de reposicionamiento de tejidos faciales, no quirúrgico, basado en la implantación de múltiples mini hilos o de hilos de mayor tamaño, que actúan como soporte del tejido provocando un efecto de elevación a la vez que favorece la producción natural de colágeno. Mejora el aspecto de arrugas, pliegues y la piel flácida de la cara. </p>
    <p style="margin-top: 5pt"><strong>Método que se emplea para el tratamiento que se va a recibir:</strong></p>
    <p> Consiste en un sistema de implantación en la dermis profunda con la utilización de agujas o cánulas de distintos grosores y longitudes, adecuadas a cada zona siendo en este caso el médico quien que decidirá cuál es el más aconsejable para cada paciente. </p>
    <p style="margin-top: 5pt"><strong>Tratamiento:</strong> </p>
    <p>El material de que están fabricados es un material altamente seguro aplicado en técnicas de cirugía cardíaca que actúa favoreciendo la cohesión de las células. Se realizara una sesión anual en la zona donde se apliquen, pues son totalmente reabsorbidos por el organismo en un plazo entre 8 y 10 meses. Se aplica mediante técnica ambulatoria con anestésica en los puntos de inserción, sin hospitalización. El paciente no necesita tiempo de recuperación pudiendo reincorporarse a su vida cotidiana de forma inmediata e incluso maquillarse. </p>
    <p><strong>Inconvenientes:</strong> </p>
    <p>Los hilos ocasionan una reacción inflamatoria en los tejidos que generalmente es mínima dependiendo de cada tipo de piel. Su aplicación puede provocar eritema y un pequeño dolor causado por el trauma quirúrgico del hilo en el tejido que remite en unas horas así como equimosis que desaparecerán en aproximadamente 10 días. Pueden producirse asimetrías, bultos, depresiones cutáneas, migración o rotura del hilo(s).</p>
    <p style="margin-top: 5pt"><strong>Complicaciones:</strong> </p>
    <p>La utilización de agujas o cánulas para insertar los hilos en la piel pueden ocasionar lesiones de vasos sanguíneos con hematomas o lesiones de nervios faciales con alteraciones de la sensibilidad y/o parálisis temporal o permanente de la movilidad de una zona o la totalidad de la hemicara afectada, lo que podría ameritar tratamientos quirúrgicos o de rehabilitación, entre otros.</p>
    <p style="margin-top: 5pt"><strong>Autorización:</strong> </p>
    <p>He sido informado y he entendido que existen riesgos. Si surge alguna complicación o algún tipo de alergia, doy mi consentimiento para que el médico actúe como estime oportuno.</p>
    <p><strong>COMPRENDO</strong> que el fin del tratamiento es mejorar mi apariencia existiendo la posibilidad de que alguna imperfección persista y que el resultado pueda no ser el esperado por mí. En este sentido, se me informa que el resultado estético del tratamiento depende de factores como la facilidad de cicatrización, formación o no de queloides, aparición de reacciones al producto (como por ejemplo fibrosis). Sé que la medicina no es una ciencia exacta y que nadie puede garantizar la perfección absoluta. Comprendo que el resultado pueda no ser el esperado por mí y reconozco que no se me ha dado, en absoluto, tal garantía.</p>
    <p><strong>SE ME HA INFORMADO</strong> que el número de sesiones y/o cantidad de producto que es necesario para conseguir el efecto deseado se me ha comunicado de forma orientativa, siendo imposible de antemano conocer la cantidad exacta de producto ó número de sesiones que son necesarias, por la diferente forma de absorción/reacción de cada paciente.</p>
    <p><strong>ME COMPROMETO</strong> a seguir fielmente, en lo mejor de mis posibilidades, las instrucciones del médico para antes, durante y después del tratamiento antes mencionado. Quedando bajo mi responsabilidad el cumplimiento de las medidas post tratamiento por el Centro.</p>
    <p><strong>DOY FE</strong> de no haber omitido o alterado datos al exponer mi historial y antecedentes clínico quirúrgicos, especialmente los referidos a alergias y enfermedades o riesgos personales.</p>
    <p><strong>AUTORIZO</strong> a que se me practiquen fotografías de la zona tratada que puedan ser utilizadas con fines científicos, docentes o médicos, quedando entendido que su uso no constituya ninguna violación a la intimidad o confidencialidad, a las que tengo derecho.</p>
    <p><strong>ME CONSTA</strong> que mis datos van a ser tratados de forma automatizada, lo cual autorizo habiéndome sido explicados mis derechos de conformidad con la vigente LOPD.</p>
    <p>Se me ha informado, igualmente, de mi derecho a rechazar el tratamiento o revocar este consentimiento. He podido aclarar todas mis dudas acerca de todo lo anteriormente expuesto y he entendido totalmente este DOCUMENTO DE CONSENTIMIENTO reafirmándome en todas y cada uno de sus puntos y con la firma del documento EN TODAS LAS PÁGINAS Y POR DUPLICADO ratifico y consiento que el tratamiento se realice.</p>
  </div>
  <h3>
		DECLARACIONES Y FIRMAS
	</h3>
	
	<p>Se me ha dado tiempo suficiente para leer la información precedente que describe el tratamiento de Hilos. Comprendo los beneficios, riesgos e inconvenientes asociados al tratamiento. <br><br>He sido suficientemente informado y he tenido la oportunidad de hacer preguntas y discutir las preocupaciones sobre el tratamiento de hilos con los productos con mi dentista que me ofrece el tratamiento.  <br><br>Por la presente doy mi consentimiento para el tratamiento de hilos que ha sido prescrito por mi dentista.
			</p>
			
			<p>
				Autorizo a mi dentista a divulgar mis registros, incluyendo, pero no limitándose a radiografías, historial médico, fotografías, modelos de yeso o impresiones de dientes, prescripciones, diagnósticos y otros registros de tratamiento al proveedor y compañías asociadas. <br><br>Esto es con el propósito de investigar y revisar mi caso, en lo que se refiere al tratamiento de odontologia con sus producto(s) y/o con fines educativos/de investigación. 
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