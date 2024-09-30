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
		TOXINA BOTULÍNICA
	</h2>
  <br>
  <table style="width: 100%;">
    <tr>
      <td style="width: 100%;">Don/Doña, <?php echo strtoupper($datos_firma[0]['cliente']); ?> (paciente) de: <?php echo $datos_firma[0]['edad']; ?> años de edad, Con domicilio en</td>
    </tr>
    <tr>
      <td style="width: 100%;"><?php echo $datos_firma[0]['direccion']; ?> y DNI <?php echo $datos_firma[0]['dni']; ?> </td>
    </tr>
      <?php if ($datos_firma[0]['edad']<18){ ?>
          <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; font-size:8pt">
              <span style="font-family:Arial">En caso de paciente menor de edad, impedido o incapacitado Yo, </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['nombre_tutor']);  ?></span></strong>
          </p>
          <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; font-size:8pt">
              <span style="font-family:Arial">con DNI nº </span><strong><span style="font-family:Arial; "><?php echo $datos_firma[0]['dni_tutor']; ?></span></strong><span style="font-family:Arial"> , en calidad de padre, madre, tutor/a o representante legal del paciente </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['cliente']); ?></span></strong>
          </p>
      <?php } ?>
  </table>
  <div style="font-size: 11px; font-family:monospace">
    <diV style="font-size: 12px; text-align: center;">
      <b>DECLARO</b>
    </diV>
    <br>
    <p>Que por el presente documento REQUIERO Y AUTORIZO a Dr. <?php echo strtoupper($datos_doctor[0]['nombre'])." ". strtoupper($datos_doctor[0]['apellidos']); ?>, licenciada en Medicina y Cirugía con número de colegiado <?php echo  strtoupper($datos_doctor[0]['n_colegiado']) ?> y a su equipo a que realice en mi persona el tratamiento de <strong>tratamiento con toxina botulínica</strong> , habiéndome explicado que es conveniente proceder, en mi situación, a recibir dicho tratamiento.</p>
    <br>
    <p><b>BREVE EXPLICACION DEL TRATAMIENTO:</b></p>
    <p>Consiste en la infiltración, con una jeringa y una aguja muy fina, que la hace prácticamente indolora, de pequeñas cantidades de un fármaco específico, la toxina botulínica tipo A, comercializada con el nombre de Vistabel®, Botox®, Azzalure®, Bocouture®, entre otros, en músculos faciales, para el tratamiento de las <strong>arrugas de expresión</strong>, así denominadas por su relación directa con la mímica facial; fundamentalmente de las arrugas del entrecejo (<strong>Indicación oficial</strong>), pero también para las arrugas de la frente, las perioculares (patas de gallo) y las peribucales (verticales de los labios), considerándose el tratamiento de estas últimas zonas como <strong>“Off label”</strong> (indicación no oficial) </p>
    <p>Mediante la aplicación de la mencionada toxina, se consigue la paralización parcial y/o total, selectiva de los pequeños músculos responsables de ciertos gestos faciales (expresiones), cuya repetitividad determina la aparición de surcos y arrugas estables y profundas. Es una denervación selectiva, mediante el empleo de fármacos; es decir un bloqueo de la liberación de ciertas sustancias fundamentales para el establecimiento de la conexión necesaria entre las terminaciones nerviosas y la placa motora del músculo</p>
    <p>El tratamiento se realiza en una única sesión.</p>
    <p>El paciente se reincorpora inmediatamente a sus actividades habituales.</p>
    <p>El efecto aparece alrededor del 3º-4º día, y es completo a los 7-10 días.</p>
    <p>La inyección de toxina botulínica tipo A causa la reducción selectiva y temporal de la contracción en la musculatura hiperactiva, <strong>durante un periodo aproximado de 4 meses.</strong></p>
    <p>Las sustancias y aparatos empleados han sido autorizados para su uso en medicina estética y ostenta la marca CE y número de registro sanitario correspondiente.</p>
    <p>Es asimismo un tratamiento de elección de la <strong> hiperhidrosis</strong> (sudoración excesiva), a nivel de las axilas, las palmas de las manos y las plantas de los pies; por denervación de los receptores en las glándulas sudoríparas.</p>
    <diV style="font-size: 12px; text-align: center;">
      <b>RIESGOS INHERENTES AL PACIENTE Y A SUS CIRCUNSTANCIAS PERSONALES:</b>
    </diV>
    <p><strong>CONFIRMO</strong> que el tratamiento mencionado, me ha sido explicado a fondo, por un médico en palabras comprensibles para mí, los riesgos típicos que tiene, los efectos no deseados, los riesgos característicos a mi persona, así como las molestias o, en ocasiones, dolores que puedo sentir teniendo un post-tratamiento normal. Se me han explicado, igualmente otras opciones existentes que están disponibles en el mercado, con pros y contras de cada una de ellas. Teniendo esto en cuenta he escogido el tratamiento anteriormente descrito. Las formas alternativas pueden consistir en no tratar las zonas susceptibles de tratamiento. Existen riesgos y complicaciones potenciales asociadas a las formas alternativas de tratamiento.</p>
    <p><strong>ACEPTO</strong> que puedan ocurrir los <strong>RIESGOS Y COMPLICACIONES</strong> descritos por la ciencia médica como inherentes a este tratamiento. Entre otros los principales riesgos que me han sido explicados son los siguientes: </p>
    <ul>
      <li> Riesgo y complicaciones comunes a cualquier tratamiento estético, entre otros reacciones alérgicas a la sustancia empleada (por lo general leves, que remiten bajo el tratamiento adecuado o incluso sin tratamiento), hematomas, edemas o inflamación que remitirán generalmente en poco tiempo sin necesidad de ser tratados.</li>
      <li>Riesgos y complicaciones específicos de este tratamiento que me han sido explicados y que asumo y acepto. Especialmente: </li>
      <ul>
        <li>Infección. La infección después de este tipo de tratamiento es muy rara. Si ocurre una infección puede ser necesario tratamiento adicional incluyendo antibióticos.</li>
        <li>Cambios en la sensibilidad cutánea. Serían temporales resolviéndose espontáneamente a los pocos días.</li>
        <li>Asimetría. Puede no conseguirse un aspecto simétrico de la zona tratada tras un único tratamiento con toxina botulínica, por lo que pueden ser necesarios tratamientos adicionales.</li>
        <li>Reacciones alérgicas. Se han descrito reacciones de eritema generalizado o local, picores, de tipo transitorio, que pueden durar unos días. Las reacciones alérgicas pueden requerir tratamiento adicional.</li>
        <li>Ptosis palpebral. Caída de cejas.</li>
        <li>Otros. Pueden ser necesarios varios tratamientos con toxina botulínica, seriados y separados en el tiempo para obtener el resultado estético deseado. Puede ser necesaria la utilización de cremas o lociones hidratantes después de un tratamiento con toxina botulínica.</li>
      </ul>
    </ul>
    <p><strong>RECONOZCO</strong> que en el curso del tratamiento pueden surgir condiciones no previstas que hagan necesario un cambio de lo anteriormente planeado y doy aquí mi expresa autorización para el tratamiento de las mismas, incluyendo además procedimientos como biopsias, radiografías, etc. En caso de complicaciones durante el tratamiento autorizo al Centro a solicitar la necesaria ayuda de otros especialistas, según su mejor juicio profesional.</p>
    <p><strong>COMPRENDO</strong> que el fin del tratamiento es mejorar mi apariencia existiendo la posibilidad de que alguna imperfección persista y que el resultado pueda no ser el esperado por mí. En este sentido, se me informa que el resultado estético del tratamiento depende de factores como la facilidad de cicatrización, formación o no de queloides, aparición de reacciones al producto (como por ejemplo fibrosis). Sé que la medicina no es una ciencia exacta y que nadie puede garantizar la perfección absoluta. Comprendo que el resultado pueda no ser el esperado por mí y reconozco que no se me ha dado, en absoluto, tal garantía.</p>
    <p><strong>SE ME HA INFORMADO</strong> que el número de sesiones y/o cantidad de producto que es necesario para conseguir el efecto deseado se me ha comunicado de forma orientativa, siendo imposible de antemano conocer la cantidad exacta de producto o número de sesiones que son necesarias, por la diferente forma de absorción/reacción de cada paciente.</p>
    <p><strong>ME COMPROMETO</strong> a seguir fielmente, en lo mejor de mis posibilidades, las instrucciones del médico para antes, durante y después del tratamiento antes mencionado. Quedando bajo mi responsabilidad el cumplimiento de las medidas post-tratamiento recomendadas por el Centro.</p>
    <p><strong>DOY FE</strong> de no haber omitido o alterado datos al exponer mi historial y antecedentes clínico quirúrgicos, especialmente los referidos a alergias y enfermedades o riesgos personales.</p>
    <p><strong>AUTORIZO</strong> a que se me practiquen fotografías de la zona tratada que puedan ser utilizadas con fines científicos, docentes o médicos, quedando entendido que su uso no constituya ninguna violación a la intimidad o confidencialidad, a las que tengo derecho.</p>
    <p><strong>ME CONSTA</strong> que mis datos van a ser tratados de forma automatizada, lo cual autorizo habiéndome sido explicados mis derechos de conformidad con la vigente en la LOPD (Ley de protección de datos).</p>
    <p>Se me ha informado, igualmente, de mi derecho a rechazar el tratamiento o revocar este consentimiento. He podido aclarar todas mis dudas acerca de todo lo anteriormente expuesto y he entendido totalmente este DOCUMENTO DE CONSENTIMIENTO reafirmándome en todas y cada uno de sus puntos y con la firma del documento EN TODAS LAS PÁGINAS Y POR DUPLICADO ratifico y consiento que el tratamiento se realice.</p>
  </div>
  <h3>
		DECLARACIONES Y FIRMAS
	</h3>
	
	<p>Se me ha dado tiempo suficiente para leer la información precedente que describe el tratamiento de toxina botulínica. Comprendo los beneficios, riesgos e inconvenientes asociados al tratamiento. <br><br>He sido suficientemente informado y he tenido la oportunidad de hacer preguntas y discutir las preocupaciones sobre el tratamiento de blanqueamiento dental externo con los productos con mi dentista que me ofrece el tratamiento.  <br><br>Por la presente doy mi consentimiento para el tratamiento de botulínica que ha sido prescrito por mi dentista.
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