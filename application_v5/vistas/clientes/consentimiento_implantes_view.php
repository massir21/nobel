<?php
//https://extranet.templodelmasaje.com/clientes/consentimiento/5381
//var_dump($datos_firma);
?>
<!DOCTYPE html>
<html lang="es">
<!-- BEGIN HEAD -->

<head>
  <title><?= SITETITLE ?></title>
  <meta charset="utf-8" />
</head>




<body>
  <div style="font-size: 12px; text-align: center;">
    <h1>DOCUMENTO DE CONSENTIMIENTO INFORMADO</h1>
    <h1>Para implantes con ácido poliláctico</h1>
  </div>
  <br>
  <table style="width: 100%;">
    <tr>
      <td style="width: 100%;">Don/Doña, <?php echo strtoupper($datos_firma[0]['cliente']); ?> (paciente) de: <?php echo $datos_firma[0]['edad']; ?> años de edad, Con domicilio en</td>
    </tr>
    <tr>
      <td style="width: 100%;"><?php echo $datos_firma[0]['direccion']; ?> y DNI <?php echo $datos_firma[0]['dni']; ?> </td>
    </tr>
      <?php if ($datos_firma[0]['edad']<18){ ?>
          <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
              <span style="font-family:Arial">En caso de paciente menor de edad, impedido o incapacitado Yo, </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['nombre_tutor']);  ?></span></strong>
          </p>
          <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
              <span style="font-family:Arial">con DNI nº </span><strong><span style="font-family:Arial; "><?php echo $datos_firma[0]['dni_tutor']; ?></span></strong><span style="font-family:Arial"> , en calidad de padre, madre, tutor/a o representante legal del paciente </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['cliente']); ?></span></strong>
          </p>
      <?php } ?>
  </table>
  <div style="font-family:Arial">
    <diV style="text-align: center;">
    <h3 style="margin-top: 30pt;">DECLARO:</h3>

    </diV>
    <br>
    <p>Que por el presente documento REQUIERO Y AUTORIZO a Dr. <?php echo strtoupper($datos_doctor[0]['nombre'])." ". strtoupper($datos_doctor[0]['apellidos']); ?>, licenciada en Medicina y Cirugía con número de colegiado <?php echo  strtoupper($datos_doctor[0]['n_colegiado']) ?> y a su equipo a que realice en mi persona el tratamiento de <strong>implantes con ácido poliláctico</strong> , habiéndome explicado que es conveniente proceder, en mi situación, a recibir dicho tratamiento.</p>
    <br>
    <h3 style=" margin-top: 10pt; text-align: center;">BREVE EXPLICACION DEL TRATAMIENTO:</h3>

    <p>Este producto es un implante constituido por ácido poli-L-láctico, un polímero sintético altamente biocompatible y biodegradable, desprovisto de proteínas animales.</p>
    <p>El ácido poliláctico está indicado para la corrección de los signos de envejecimiento facial y de otras áreas corporales, sin recurrir a la cirugía plástica. Se utiliza para conseguir, mediante un aumento de volumen de la zona tratada, un remodelado del contorno de la cara y la corrección de surcos y arrugas superficiales, así como corregir cicatrices y ojeras, o mejorar la apariencia de la zona del mentón, mejillas o pómulos.</p>
    <p>El ácido poliláctico se implanta por inyecciones subcutáneas o intradérmicas profundas con aguja fina, que producen ligeras molestias transitorias. Tras la inyección, el implante induce la producción de nuevo colágeno por parte de la propia dermis del paciente, de forma que se produce una mejoría progresiva de los signos de envejecimiento, sin que en la piel permanezcan restos de ninguna sustancia extraña.</p>
    <p>Son necesarias varias sesiones de tratamiento, cada 4 a 6 semanas, para obtener la mejoría estética deseada.</p>
    <p>La cantidad de producto a utilizar en cada sesión varía en cada caso individual. El número de sesiones requeridas para obtener el resultado estético deseado es también variable, y será estimado en mi caso concreto por el médico responsable. Una vez finalizado el tratamiento, pueden ser necesarias sesiones adicionales para mantener la mejoría obtenida, generalmente cada 1 y 1/2 años.</p>
    <p>Los efectos secundarios que pueden asociarse con mayor frecuencia a las inyecciones son: sangrado y dolor pasajero, enrojecimiento de los puntos de inyección o edema leve de la zona, que generalmente desaparecen en el plazo de 2 a 6 días. <strong> También es posible la aparición de pequeños nódulos palpables, levemente visibles o áreas de induración en la zona tratada, que puede aparecer inflamada o con cambios de coloración.
        Otros efectos secundarios comunicados en raras ocasiones consisten en: formación de nódulos tardíos, abscesos, reacciones alérgicas, hipertrofia o atrofia cutánea. </strong>
    </p>
    <p>La aparición de cualquier efecto secundario será comunicada al médico. Una vez finalizado el tratamiento, se recomienda evitar la exposición al sol, rayos UVA o frío y calor intensos, durante las 2-3 semanas siguientes.</p>
    <p>Me han preguntado si soy alérgico a algún componente del producto, si estoy siendo tratado con anticoagulantes u otros productos y si en alguna ocasión he desarrollado cicatrices muy hipertrofiadas (queloides).</p>
    <p>Entiendo el procedimiento de administración de implantes de ácido poliláctico y reconozco la posibilidad de los efectos secundarios descritos. Asimismo, he sido informado a cerca de la existencia de otras alternativas para tratar mi problema concreto.</p>
    <p><strong>Autorización:</strong> He sido informado y he entendido que existen riesgos. Si surge alguna complicación o algún tipo de alergia, doy mi consentimiento para que el médico actúe como estime oportuno.</p>
    <p><strong>COMPRENDO</strong> que el fin del tratamiento es mejorar mi apariencia existiendo la posibilidad de que alguna imperfección persista y que el resultado pueda no ser el esperado por mí. En este sentido, se me informa que el resultado estético del tratamiento depende de factores como la facilidad de cicatrización, formación o no de queloides, aparición de reacciones al producto (como por ejemplo fibrosis). Sé que la medicina no es una ciencia exacta y que nadie puede garantizar la perfección absoluta. Comprendo que el resultado pueda no ser el esperado por mí y reconozco que no se me ha dado, en absoluto, tal garantía.</p>
    <p><strong>SE ME HA INFORMADO</strong> que el número de sesiones y/o cantidad de producto que es necesario para conseguir el efecto deseado se me ha comunicado de forma orientativa, siendo imposible de antemano conocer la cantidad exacta de producto ó número de sesiones que son necesarias, por la diferente forma de absorción/reacción de cada paciente.</p>
    <p><strong>ME COMPROMETO</strong> a seguir fielmente, en lo mejor de mis posibilidades, las instrucciones del médico para antes, durante y después del tratamiento antes mencionado. Quedando bajo mi responsabilidad el cumplimiento de las medidas post tratamiento por el Centro.</p>
    <p><strong>DOY FE</strong> de no haber omitido o alterado datos al exponer mi historial y antecedentes clínico quirúrgicos, especialmente los referidos a alergias y enfermedades o riesgos personales.</p>
    <p><strong>AUTORIZO</strong> a que se me practiquen fotografías de la zona tratada que puedan ser utilizadas con fines científicos, docentes o médicos, quedando entendido que su uso no constituya ninguna violación a la intimidad o confidencialidad, a las que tengo derecho.</p>
    <p><strong>ME CONSTA</strong> que mis datos van a ser tratados de forma automatizada, lo cual autorizo habiéndome sido explicados mis derechos de conformidad con la vigente LOPD.</p>
    <p>Se me ha informado, igualmente, de mi derecho a rechazar el tratamiento o revocar este consentimiento. He podido aclarar todas mis dudas acerca de todo lo anteriormente expuesto y he entendido totalmente este DOCUMENTO DE CONSENTIMIENTO reafirmándome en todas y cada uno de sus puntos y con la firma del documento EN TODAS LAS PÁGINAS Y POR DUPLICADO ratifico y consiento que el tratamiento se realice.</p>
  </div>
  <div style="font-size: 12px; text-align: center;">
    <p><strong>Fdo.: El paciente o (representante legal)</strong></p>
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