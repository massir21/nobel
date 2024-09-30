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
		ODONTOLOGÍA
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
    <span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span>
</p>
<diV style="font-size: 16px; text-align: center;">
      <b>DECLARO</b><br><br>
    </diV>
<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
    <span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span>
</p>

<ul style="margin-top:0pt; line-height:normal; widows:0; orphans:0; font-family: Arial;">
    <li style="margin-bottom: 10px;">
        Anestesia local. Me ha explicado que el tratamiento que voy a recibir implica la administración de anestesia local, que consiste en proporcionar, mediante una inyección, sustancias que provocan un bloqueo reversible de los nervios de tal manera que se inhibe transitoriamente la sensibilidad con el fin de realizar el tratamiento sin dolor. Me ha explicado que tendré la sensación de acorchamiento del labio o de la cara, que normalmente van a desaparecer en dos o tres horas. También me ha explicado que la administración de la anestesia puede provocar, en el punto en el que se administre la inyección, ulceración de la mucosa y dolor, y menos frecuentemente, limitaciones en el movimiento de apertura de la boca, que pueden requerir tratamiento ulterior, y que la anestesia puede provocar bajada de tensión que, en casos menos frecuentes, pueden provocar un síncope o fibrilación ventricular, que deben tratarse posteriormente, e, incluso, excepcionalmente, la muerte. Comprendo que, aunque de mis antecedentes personales no se deducen posibles alergias o hipersensibilidad al agente anestésico, la anestesia puede provocar urticarias, dermatitis, asma, edema angioneurótico, que en casos extremos puede requerir tratamiento urgente.
    </li>
    <li style="margin-bottom: 10px;">
        Extracciones simples. La intervención consiste en la aplicación de un fórceps a la corona, practicando la luxación con movimientos de lateralidad, de manera que pueda desprenderse fácilmente del alvéolo donde está insertada. Aunque se me realizarán los medios diagnósticos que se estimen precisos, comprendo que es posible que el estado inflamatorio del diente que se me vaya a extraer pueda producir un proceso infeccioso, que puede requerir tratamiento con antibióticos y/o antiinflamatorios, del mismo modo que en el curso del procedimiento puede producirse una hemorragia, que exigiría, para cohibirla, la colocación en el alvéolo de una sustancia o de sutura. También sé que en el curso del procedimiento pueden producirse, aunque no es frecuente, la rotura de la corona, heridas en la mucosa de la mejilla o en la lengua, inserción de la raíz en el seno maxilar, fractura del maxilar o de la tuberosidad, que no dependen de la forma o modo de practicarse la intervención, ni de su correcta realización, sino que son imprevisibles, en cuyo caso el facultativo tomará las medidas precisas u continuará con la extracción.
    </li>
    <li style="margin-bottom: 10px;">
        Obturaciones o empastes. El propósito principal de esta intervención es restaurar los tejidos dentarios duros y proteger la pulpa, para conservar el diente o molar y su función, restableciendo al tiempo, siempre que sea posible, la estética adecuada. La intervención consiste en limpiar la cavidad de tejido enfermo y rellenarla posteriormente para conseguir un sellado hermético, conservando el diente o molar. El Dentista me ha advertido que es frecuente que se produzca una mayor sensibilidad, sobre todo al frío, que normalmente desaparecerá de modo espontáneo. También me ha recomendado que vuelva a visitarle si advierto signos de movilidad o alteraciones de la oclusión, pues en ese caso sería preciso ajustar la oclusión, para aliviar el dolor y para impedir la formación de una enfermedad periodontal y/o trauma. Comprendo que el sellado hermético puede reactivar procesos infecciosos que hagan necesaria una endodoncia y que, especialmente si la caries es profunda, el diente o molar quedar frágil y podrá ser necesario llevar a cabo otro tipo de reconstrucción o colocar una corona o funda protésica. También comprendo que es posible que no me encuentre satisfecho con la forma o el color del diente tras el tratamiento porque las cualidades de los empastes nunca serán idénticas a su aspecto sano.
    </li>
    <li style="margin-bottom: 10px;">
        Endodoncia. El propósito principal de esta intervención es la eliminación del tejido pulpar (conocido vulgarmente por el nervio del diente) inflamado o infectado, o de un proceso granulomatoso o quístico. La intervención consiste en la eliminación del tejido enfermo y rellenar la cámara pulpar y los tejidos radiculares con un material que selle la cavidad e impida el paso a las bacterias y toxinas infecciosas, conservando el diente o molar. El Dentista me ha advertido que, a pesar de realizarse correctamente la técnica, cabe la posibilidad de que la infección o el proceso quístico o granulomatoso no se eliminen totalmente, por lo que puede ser necesario acudir a la cirugía de partes de la raíz del diente al cabo de algunas semanas, meses o incluso años. A pesar de realizarse correctamente la técnica, es posible que no se obtenga el relleno total de los conductos, por lo que también puede ser necesario proceder a una reendodoncia, como en el caso de que el relleno quede corto o largo. El Dentista me ha advertido que es muy posible que después de la endodoncia el diente cambie de color o se oscurezca ligeramente. También sé que es frecuente que el diente/molar en que se realice la endodoncia se debilite y tienda a fracturarse, por lo que puede ser necesario realizar coronas protésicas e insertar refuerzos intrarradiculares.
    </li>
    <li style="margin-bottom: 10px;">
        Prótesis. Me ha explicado el Dentista la necesidad de tallar los pilares de la prótesis, lo que conlleva la posibilidad de aproximación excesiva a la cámara pulpar (nervio) que nos obligaría a realizar una endodoncia y en algunos casos si el muñón quedase frágil, a realizar una espiga colada. También se me ha explicado la necesidad de mantener una higiene escrupulosa para evitar el desarrollo de caries, gingivitis y secundariamente enfermedad periodontal. Asimismo, se me informa de la importancia de visitas periódicas (entre 6 meses y un año) para controlar la situación de la prótesis y su entorno. Por otro lado, se me aclara que existe la posibilidad de fractura de cualquiera de los componentes de la prótesis muy relacionada con el uso que yo haga de la misma. El Dentista me ha explicado que todo acto odontológico lleva implícitas una serie de complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios tanto médicos como quirúrgicos. He comprendido lo que se me ha explicado por el facultativo de forma clara, con un lenguaje sencillo, habiendo resuelto todas las dudas que se me han planteado, y la información complementaria que le he solicitado. Me ha quedado claro que en cualquier momento y sin necesidad de dar ninguna explicación, puedo revocar este consentimiento. Estoy satisfecho con la información recibida y comprendido el alcance y riesgos de este tratamiento, y por ello, DOY MI CONSENTIMIENTO.
    </li>
</ul>


<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
    <span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span>
</p>

<h3>
		DECLARACIONES Y FIRMAS
	</h3>
	
	<p>Se me ha dado tiempo suficiente para leer la información precedente que describe el tratamiento de Odontología. Comprendo los beneficios, riesgos e inconvenientes asociados al tratamiento. <br><br>He sido suficientemente informado y he tenido la oportunidad de hacer preguntas y discutir las preocupaciones sobre el tratamiento de odontología con los productos con mi dentista que me ofrece el tratamiento.  <br><br>Por la presente doy mi consentimiento para el tratamiento de odontología que ha sido prescrito por mi dentista.
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