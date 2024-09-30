<?php
//https://extranet.templodelmasaje.com/clientes/consentimiento/5381
//var_dump($datos_firma);
?>
<!DOCTYPE html>
<html lang="es">
<!-- BEGIN HEAD -->
<head>
  <title><?= SITETITLE ?></title><style>
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
    <H1>CONSENTIMIENTO INFORMADO PARA BIOESTIMULACIÓN CON FACTORES DE CRECIMIENTO AUTÓLOGOS</H1>
    
	<?php if ($datos_firma[0]['edad']<18){ ?>
          <p>
              <span style="font-family:Arial">Don/Doña, </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['nombre_tutor']);  ?></span></strong>
              <span style="font-family:Arial">con DNI nº </span><strong><span style="font-family:Arial; "><?php echo $datos_firma[0]['dni_tutor']; ?></span></strong><span style="font-family:Arial">, en calidad de padre, madre, tutor/a o representante legal del paciente: </span>
          </p>
      <?php } ?>
	
	
	<p>Don/Doña, <?php echo strtoupper($datos_firma[0]['cliente']); ?> de <?php echo $datos_firma[0]['edad']; ?> años de edad, con domicilio en <?php echo $datos_firma[0]['direccion']; ?> y DNI <?php echo $datos_firma[0]['dni']; ?></p>
  
  <br>

      
      
 
    <diV style="font-size: 12px; text-align: center;">
      <b>DECLARO</b>
    </diV>
    <br>
    <p>Que por el presente documento REQUIERO Y AUTORIZO a Dr. <?php echo strtoupper($datos_doctor[0]['nombre'])." ". strtoupper($datos_doctor[0]['apellidos']); ?> licenciad0 en Medicina y Cirugía con número de colegiado <?php echo  strtoupper($datos_doctor[0]['n_colegiado']) ?> y a su equipo a que realice en mi persona el tratamiento de <strong>bioestimulación facial con PRP y factores de crecimiento autólogos,</strong> habiéndome explicado que es conveniente proceder, en mi situación, a recibir dicho tratamiento.</p>
    <br>
    <h2>BREVE EXPLICACION DEL TRATAMIENTO:</h2>
    <p>Es una técnica ambulatoria basada en la aplicación intradérmica de Plasma Rico en Plaquetas que se obtiene a partir de una pequeña muestra de sangre del paciente, la cual se extrae vía endovenosa y se centrifuga, para activar de forma natural las funciones del fibroblasto, la célula encargada de determinar la estructura y la calidad de la piel. <br>Sé que el tratamiento consiste en realizar microinyecciones intradérmicas con mi plasma, a modo de autoinjerto, acondicionado para que sea rico en plaquetas y factores de crecimiento plaquetarios.</p>
    <p>He sido correctamente informado, incluso por escrito (documentos de información) y/o mediante imágenes, de las características de este tratamiento: de sus fundamentos, de la forma y detalles de su realización, de sus mecanismos de acción, de sus efectos inmediatos, del proceso y evolución que seguiré en los siguientes días, semanas y meses, de las curas que serán necesarias practicar, de los tratamientos complementarios necesarios, de las atenciones y precauciones que debo adoptar en las próximas semanas y meses y de la variabilidad en el tiempo necesario para el completo restablecimiento; aceptando, por lo tanto, que no se me puede asegurar la fecha en que podré reincorporarme a mis actividades habituales (afectivas, sociales, laborales y deportivas).</p>
    <p>Las sustancias y aparatos empleados han sido autorizados para su uso en medicina estética y ostenta la marca CE y número de registro sanitario correspondiente.</p>
    <p>He sido correctamente informado, y los acepto, los riesgos comúnmente conocidos de la pre-medicación, la anestesia y/o el tratamiento que me han de realizar.</p>
    <p><strong>CONFIRMO</strong> que el tratamiento mencionado, me ha sido explicado a fondo, por un médico en palabras comprensibles para mí, los riesgos típicos que tiene, los efectos no deseados, los riesgos característicos a mi persona, así como las molestias o, en ocasiones, dolores que puedo sentir teniendo un post-procedimiento normal. Se me han explicado, igualmente otras opciones existentes que están disponibles en el mercado, con pros y contras de cada una de ellas. Teniendo esto en cuenta he escogido la intervención anteriormente descrita.</p>
    <p><strong>ACEPTO</strong> la capacitación profesional del facultativo para la realización del tratamiento propuesto.</p>
    <p><strong>CONSIENTO</strong> en la administración de sedación y/o anestesia dada por o bajo la dirección del cirujano o anestesista por él seleccionados y a usar dicha anestesia, tanto local como general, como convenga</p>
    <p>También he sido informado, en términos de probabilidades, de los resultados del procedimiento según referencias de la literatura científica contrastada y de la experiencia previa del profesional en la realización de estos procedimientos.</p>
    <p><strong>COMPRENDO</strong> que el fin de la operación es mejorar mi apariencia existiendo la posibilidad de que alguna imperfección persista y que el resultado pueda no ser el esperado por mí. Sé que la medicina no es una ciencia exacta y que nadie puede garantizar la perfección absoluta. Comprendo que el resultado pueda no ser el esperado por mí y reconozco que no se me ha dado, en absoluto, tal garantía.</p>
    <h2>RIESGOS GENERALES:</h2>
    <p>
      <stong>ACEPTO</stong> que puedan ocurrir los <strong>RIESGOS Y COMPLICACIONES</strong> descritos por la ciencia médica como inherentes a este tratamiento.
    </p>
    <ul>
      <li> Sé que pueden aparecer complicaciones como: hemorragias, equimosis y hematomas, e infecciones.</li>
      <li> Asumo que como consecuencia del tratamiento pudiera sufrir un perjuicio estético en lugar de una mejoría.</li>
      <li> Riesgo y complicaciones comunes a cualquier tratamiento estético, entre otros reacciones alérgicas a la sustancia empleada o a la anestesia (por lo general leves, que remiten bajo el tratamiento adecuado ó incluso sin tratamiento), hematomas, edemas o inflamación que remitirán generalmente en poco tiempo sin necesidad de ser tratados.</li>
      <li> Riesgos y complicaciones achacables al procedimiento anestésico.</li>
      <li> Riesgos y complicaciones específicos de esta intervención que me han sido explicados y que asumo y acepto.</li>
    </ul>
    <br>
   
    <h2>RIESGOS INHERENTES AL PACIENTE Y A SUS CIRCUNSTANCIAS PERSONALES:</h2>

  <p>Además de los anteriores, las circunstancias personales que no se han ocultado y que constan en la Historia Clínica (enfermedades, hábitos adquiridos o tratamientos previos del paciente) pueden aumentar la incidencia de aparición de los riesgos indicados.</p>
  <p><strong>DOY FE</strong> de no haber omitido o alterado datos al exponer mi historial y antecedentes clínico quirúrgicos, especialmente los referidos a alergias y enfermedades o riesgos personales. AUTORIZO a que se me practiquen fotografías de la zona intervenida que puedan ser utilizadas con fines científicos, docentes o médicos, quedando entendido que su uso no constituya ninguna violación a la intimidad o confidencialidad, a las que tengo derecho.
  </p>
  <p>Se me advierte que después del implante necesita que la zona este siempre limpia, hidratada y desinfectada. El especialista le recomendará el producto que se deberá aplicar. Me advierte de la no exposición directa a la luz solar o rayes U.V.A. en caso de hematoma. En caso de exposición Solar deberá cubrir toda la zona con productos de pantalla total. ME COMPROMETO a seguir fielmente, en lo mejor de mis posibilidades, las instrucciones del médico para antes, durante y después de la intervención antes mencionada. Quedando bajo mi responsabilidad el cumplimiento de las medidas postoperatorias recomendadas por el Centro.</p>
  <p>He comprendido las explicaciones que se me han facilitado en un lenguaje claro y sencillo, y el facultativo que me ha atendido me ha permitido realizar todas las observaciones y me ha aclarado todas las dudas que le he planteado.</p>
  <p>SE ME HA INFORMADO que el número de sesiones y/o cantidad de producto que es necesario para conseguir el efecto deseado se me ha comunicado de forma orientativa, siendo imposible de antemano conocer la cantidad exacta de producto ó número de sesiones que son necesarias, por la diferente forma de absorción/reacción de cada paciente. </p>
  <p>ME CONSTA que mis datos van a ser tratados de forma automatizada, lo cual autorizo habiéndome sido explicados mis derechos de conformidad con la vigente LOPD.</p>
  <p>Se me ha informado, igualmente, de mi derecho a rechazar la intervención o revocar este consentimiento. </p>
  <p>He podido aclarar todas mis dudas acerca de todo lo anteriormente expuesto y he entendido totalmente este DOCUMENTO DE CONSENTIMIENTO reafirmándome en todas y cada uno de sus puntos y con la firma del documento EN TODAS LAS PÁGINAS Y POR DUPLICADO ratifico y consiento que el tratamiento se realice.</p>
  
  <div style="font-size: 12px; text-align: center;">
    <p>Fdo.: El paciente o (representante legal)</p>
    <?php if ($datos_firma[0]['firma'] != "ONLINE" and $datos_firma[0]['firma'] != "") { 
      if(file_exists(RUTA_SERVIDOR . "/recursos/consentimientos/" . $datos_firma[0]['firma'])) 
        $fuente=RUTA_SERVIDOR . "/recursos/consentimientos/" . $datos_firma[0]['firma'];
      else
        $fuente=RUTA_SERVIDOR . "/recursos/firmas_lopd/" . $datos_firma[0]['firma'];

      $imagenBase64 = "data:image/png;base64," . base64_encode(file_get_contents($fuente));
      ?>
      <div style="margin-top: 6px;">
         <img src="<?php echo $imagenBase64 ?>" style="height: 35mm;" />
      </div>
    <?php } else { ?>
      Por firmar.
    <?php } ?>
    <br>
    <p><?php echo Date('d/m/Y'); ?></p>
  </div>
</body>
</html>