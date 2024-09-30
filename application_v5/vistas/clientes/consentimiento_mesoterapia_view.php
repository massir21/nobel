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
		MESOTERAPIA
	</h2>
  <br>
  <table style="width: 100%;">
  <?php if ($datos_firma[0]['edad']<18){ ?>
          <p>
              <span style="font-family:Arial">Yo, Don/Doña, </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['nombre_tutor']);  ?></span></strong>
              <span style="font-family:Arial">con DNI nº </span><strong><span style="font-family:Arial; "><?php echo $datos_firma[0]['dni_tutor']; ?></span></strong><span style="font-family:Arial">, en calidad de padre, madre, tutor/a o representante legal del paciente: </span>
          </p>
      <?php } ?>
	
	
	<p>Don/Doña, <?php echo strtoupper($datos_firma[0]['cliente']); ?> de <?php echo $datos_firma[0]['edad']; ?> años de edad, con domicilio en <?php echo $datos_firma[0]['direccion']; ?> y DNI <?php echo $datos_firma[0]['dni']; ?></p>
	
</table>

  <div style=" font-family:Arial">
    <diV style="text-align: center;">
      
      <h3>RIESGOS INHERENTES AL PACIENTE Y A SUS CIRCUNSTANCIAS PERSONALES:</h3>
    </diV>
    <p><strong>CONFIRMO</strong> que el tratamiento mencionado, me ha sido explicado a fondo por un médico en palabras comprensibles para mí, los riesgos típicos que tiene, los efectos no deseados, los riesgos característicos a mi persona, así como las molestias o, en ocasiones, dolores que puedo sentir teniendo un post tratamiento normal. Se me han explicado, igualmente otras opciones existentes que están disponibles en el mercado, con pros y contras de cada una de ellas. Teniendo esto en cuenta he escogido libremente el tratamiento anteriormente descrito.</p>
    <p><strong>ACEPTO</strong> que puedan ocurrir los <strong>RIESGOS Y COMPLICACIONES</strong> descritos por la ciencia médica como inherentes a este tratamiento. Entre otros principales riesgos, me han sido explicados son los siguientes:</p>
    <ul>
      <li>Riesgo y complicaciones comunes a cualquier tratamiento estético, entre otros, reacciones alérgicas a la sustancia empleada (por lo general leves, que remiten bajo el tratamiento adecuado o incluso sin tratamiento), hematomas, infección en las zonas a tratar, edemas o inflamación que remitirán generalmente en poco tiempo sin necesidad de ser tratados.</li>
      <li>Riesgos y complicaciones específicos de este tratamiento que me han sido explicados y que asumo y acepto. Especialmente pueden aparecer pequeños hematomas o costras tras la inyección que desaparecen en unos días sin presentar mayores complicaciones.</li>
    </ul>
    <p><strong>CONTRAINDICACIONES:</strong> Pacientes que presenten coagulopatías o estén en tratamiento con anticoagulantes.</p>
    <p><strong>RECONOZCO</strong> que en el curso del tratamiento pueden surgir condiciones no previstas que hagan necesario un cambio de lo anteriormente planeado y doy aquí mi expresa autorización para el tratamiento de las mismas, incluyendo además procedimientos como biopsias, radiografías, etc. En caso de complicaciones durante el tratamiento autorizo al Centro a solicitar la necesaria ayuda de otros especialistas, según su mejor juicio profesional.</p>
    <p><strong>COMPRENDO</strong> que el fin del tratamiento es mejorar mi apariencia existiendo la posibilidad de que alguna imperfección persista y que el resultado pueda no ser el esperado por mí. En este sentido, se me informa que el resultado estético del tratamiento depende de factores como la facilidad de cicatrización, formación o no de queloides, aparición de reacciones al producto (como por ejemplo fibrosis). Sé que la medicina no es una ciencia exacta y que nadie puede garantizar la perfección absoluta. Comprendo que el resultado pueda no ser el esperado por mí y reconozco que no se me ha dado, en absoluto, tal garantía.</p>
    <p><strong>SE ME HA INFORMADO</strong> que el número de sesiones y/o cantidad de producto que es necesario para conseguir el efecto deseado se me ha comunicado de forma orientativa, siendo imposible de antemano conocer la cantidad exacta de producto ó número de sesiones que son necesarias, por la diferente forma de absorción/reacción de cada paciente.</p>
    <p><strong>ME COMPROMETO</strong> a seguir fielmente, en lo mejor de mis posibilidades, las instrucciones del médico para antes, durante y después del tratamiento antes mencionado. Quedando bajo mi responsabilidad el cumplimiento de las medidas post tratamiento por el Centro.</p>
    <p><strong>DOY FE</strong> de no haber omitido o alterado datos al exponer mi historial y antecedentes clínico quirúrgicos, especialmente los referidos a alergias y enfermedades o riesgos personales.</p>
    <p><strong>AUTORIZO</strong> a que se me practiquen fotografías de la zona tratada que puedan ser utilizadas con fines científicos, docentes o médicos, quedando entendido que su uso no constituya ninguna violación a la intimidad o confidencialidad, a las que tengo derecho.</p>
  </div>
  
  <div style=" font-family:arial">
  <h3>
		DECLARACIONES Y FIRMAS
	</h3>
	
	<p>Se me ha dado tiempo suficiente para leer la información precedente que describe el tratamiento de mesoterapia. Comprendo los beneficios, riesgos e inconvenientes asociados al tratamiento. <br><br>He sido suficientemente informado y he tenido la oportunidad de hacer preguntas y discutir las preocupaciones sobre el tratamiento de mesoterapia con los productos con mi dentista que me ofrece el tratamiento.  <br><br>Por la presente doy mi consentimiento para el tratamiento de blanqueamiento dental externo que ha sido prescrito por mi dentista.
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