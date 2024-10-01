!DOCTYPE html>
<html lang="en-US">
	<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="utf-8" />
		<title>
		</title>
		<style>
		 @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap');
			body { font-family: 'Montserrat', sans-serif; font-size:12pt }
			li {  }
			p { text-align:justify; }
			h1 {text-align:center; }
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
			<h1>Consentimiento Informado para Ácido Hialurónico </h1>
		
  <br>
 <?php if ($datos_firma[0]['edad']<18){ ?>
          <p>
              <span style="font-family:Arial">Don/Doña, </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['nombre_tutor']);  ?></span></strong>
              <span style="font-family:Arial">con DNI nº </span><strong><span style="font-family:Arial; "><?php echo $datos_firma[0]['dni_tutor']; ?></span></strong><span style="font-family:Arial">, en calidad de padre, madre, tutor/a o representante legal del paciente: </span>
          </p>
      <?php } ?>
	
	
	<p>Don/Doña, <?php echo strtoupper($datos_firma[0]['cliente']); ?> de <?php echo $datos_firma[0]['edad']; ?> años de edad, con domicilio en <?php echo $datos_firma[0]['direccion']; ?> y DNI <?php echo $datos_firma[0]['dni']; ?></p>
 
  <div style="font-size: 15px; font-family:monospace">
    <diV style="font-size: 16px; text-align: center;">
      <b>DECLARO</b>
    </diV>
    <br>
    <p>Que por el presente documento REQUIERO Y AUTORIZO a <?php echo strtoupper($datos_doctor[0]['nombre'])." ". strtoupper($datos_doctor[0]['apellidos']);?> licenciado en Medicina y Cirugía y/o Odontología con número de colegiado <?php echo  strtoupper($datos_doctor[0]['n_colegiado']) ?> y a su equipo a que realice en mi persona el tratamiento de  <strong>ácido hialurónico</strong>, habiéndome explicado que es conveniente proceder, en mi situación, a recibir dicho tratamiento.</p>
    <p></p>
    <p>1.- El objetivo de la técnica es conseguir un relleno dérmico en el tratamiento de arrugas, cicatrices, deformidades del contorno o aumento de labios.</p>
    <p>2.- AUTORIZO a que se me practiquen fotografías de la zona intervenida que puedan ser utilizadas con fines científicos, docentes o médicos, quedando entendido que su uso no constituya ninguna violación a la intimidad o confidencialidad, a las que tengo derecho. </p>
    <p>3.- Se trata de una práctica belicosa de la epidermis, en la que se pueden producir heridas y por lo tanto, se corren riesgos menores.</p>
    <p>4.- Si tiene algún tipo de enfermedad de la piel, alergias, problemas de coagulación, alergias, etc... es necesario, que antes de hacerse el implante lo indique al doctor.</p>
    <p>5.- El médico me ha explicado que a lo largo del tratamiento puede ser necesaria la administración de anestesia local o tópica de cuyos riesgos me ha informado el facultativo y que consiento, aunque no requiera ningún tipo de prueba previa.</p>
    <p>6.- El tratamiento consiste en inyectar en la piel una sustancia sintética para conseguir el relleno dérmico. La duración del efecto conseguido es variable, de varios meses. Se puede repetir el tratamiento o inyectar pequeñas dosis para retoque, siempre que sea necesario con intervalo a criterio médico.</p>
    <p>7.- Soy consciente de que existe una variabilidad individual en la respuesta a cualquier tratamiento y comprendo que a pesar de la adecuada elección del tratamiento y de su correcta realización pueden presentarse efectos no deseados, como hinchazón, enrojecimiento, dolor, escozor, algún tipo de reacción alérgica que será algo más duradera cuando la implantación sea en los labios, también pueden aparecer hematomas que desaparecen espontáneamente en varios días.
      Excepcionalmente y en raras ocasiones puede aparecer reacción tardía o granulomas, abscesos y necrosis. El médico me ha advertido que el tratamiento de los labios puede reactivar un herpes simple recidivante.
    </p>
    <p>8.- Se me advierte que después del implante necesita que la zona este siempre limpia, hidratada y desinfectada. El especialista le recomendará el producto que se deberá aplicar. Me advierte de la no exposición directa a la luz solar o rayes U.V.A. en caso de hematoma. En caso de exposición Solar deberá cubrir toda la zona con productos de pantalla total. ME COMPROMETO a seguir fielmente, en lo mejor de mis posibilidades, las instrucciones del médico para antes, durante y después de la intervención antes mencionada. Quedando bajo mi responsabilidad el cumplimiento de las medidas postoperatorias recomendadas por el Centro.</p>
    <p>9.- En mi caso particular, se ha considerado que, este es el tratamiento más adecuado, aunque pueden existir otras alternativas que estarían indicadas en otro caso y que he tenido la oportunidad de comentar con el médico. También he sido informado de las posibles consecuencias de no realizar el tratamiento que se me propone.</p>
    <p>10.- He comprendido las explicaciones que se me han facilitado en un lenguaje claro y sencillo, y el facultativo que me ha atendido me ha permitido realizar todas las observaciones y me ha aclarado todas las dudas que le he planteado.</p>
    <p>11. COMPRENDO que el fin del tratamiento es mejorar mi apariencia existiendo la posibilidad de que alguna imperfección persista y que el resultado pueda no ser el esperado por mí. En este sentido, se me informa que el resultado estético del tratamiento depende de factores como la facilidad de cicatrización, formación o no de queloides, aparición de reacciones al producto (como por ejemplo fibrosis). Sé que la medicina no es una ciencia exacta y que nadie puede garantizar la perfección absoluta. Comprendo que el resultado pueda no ser el esperado por mí y reconozco que no se me ha dado, en absoluto, tal garantía.
      Por ello, manifiesto que estoy satisfecho con la información recibida y comprendo el alcance y los riesgos del tratamiento.
    </p>
    <p>12. SE ME HA INFORMADO que el número de sesiones y/o cantidad de producto que es necesario para conseguir el efecto deseado se me ha comunicado de forma orientativa, siendo imposible de antemano conocer la cantidad exacta de producto ó número de sesiones que son necesarias, por la diferente forma de absorción/reacción de cada paciente. </p>
    <p>13. DOY FE de no haber omitido o alterado datos al exponer mi historial y antecedentes clínico quirúrgicos, especialmente los referidos a alergias y enfermedades o riesgos personales. </p>
    <p>14. ME CONSTA que mis datos van a ser tratados de forma automatizada, lo cual autorizo habiéndome sido explicados mis derechos de conformidad con la vigente LOPD.</p>
    <p></p>
    <p></p>
    <p></p>
    <p>Se me ha informado, igualmente, de mi derecho a rechazar la intervención o revocar este consentimiento. </p>
    <p></p>
    <p></p>
    <p></p>
    <p>He podido aclarar todas mis dudas acerca de todo lo anteriormente expuesto y he entendido totalmente este DOCUMENTO DE CONSENTIMIENTO reafirmándome en todas y cada uno de sus puntos y con la firma del documento EN TODAS LAS PÁGINAS Y POR DUPLICADO ratifico y consiento que el tratamiento se realice.</p>
  </div>
  <div style="font-size: 12px; text-align: center;">
    <p>Fdo.: El paciente o (representante legal)</p>
    <?php if ($datos_firma[0]['firma'] != "ONLINE" and $datos_firma[0]['firma'] != "") { ?>
      <div style="margin-top: 6px;">
        <img src="<?php echo RUTA_SERVIDOR; ?>/recursos/consentimientos/<?php echo $datos_firma[0]['firma']; ?>" style="height: 35mm;" />
      </div>
    <?php } else { ?>
      Por firmar.
    <?php } ?>
    <br>
    <p><?php echo Date('d/m/Y'); ?></p>
  </div>
</body>
</html>