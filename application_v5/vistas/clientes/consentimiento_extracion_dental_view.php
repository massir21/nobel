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
		EXTRACCIÓN DENTAL
	</h2>
				<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span>
			<<?php if ($datos_firma[0]['edad']<18){ ?>
          <p>
              <span style="font-family:Arial">Yo, Don/Doña, </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['nombre_tutor']);  ?></span></strong>
              <span style="font-family:Arial">con DNI nº </span><strong><span style="font-family:Arial; "><?php echo $datos_firma[0]['dni_tutor']; ?></span></strong><span style="font-family:Arial">, en calidad de padre, madre, tutor/a o representante legal del paciente: </span>
          </p>
      <?php } ?>
	
	
	<p>Don/Doña, <?php echo strtoupper($datos_firma[0]['cliente']); ?> de <?php echo $datos_firma[0]['edad']; ?> años de edad, con domicilio en <?php echo $datos_firma[0]['direccion']; ?> y DNI <?php echo $datos_firma[0]['dni']; ?></p>
	
	  
			<!-- <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<img src="1698829148_extraccin-dental/1698829148_extraccin-dental-5.png" width="161" height="55" alt="" ><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="font-family:Arial">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span>
			</p> -->
			<!-- <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<span style="font-family:Arial">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span><img src="1698829148_extraccin-dental/1698829148_extraccin-dental-6.png" width="454" height="52" alt="" ><span style="font-family:Arial">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span><span style="font-family:Arial; font-size:8pt">&#xa0;&#xa0; </span>
			</p> -->
			
			<br></br><h4 style="margin-top:5pt; margin-bottom:5pt; line-height:normal; widows:0; orphans:0">
				DECLARO:
			</h4>
			<!-- <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; font-size:8pt">
				<img src="1698829148_extraccin-dental/1698829148_extraccin-dental-7.png" width="56" height="15" alt="" >
			</p> -->
			<p style="font-family:Arial; line-height:normal;">
    Que se me ha explicado que es necesario que se me realice una extracción
        <?php if ($descripcionTratamiento != null) { ?>
            en el/los diente/s <strong><?php echo isset($descripcionTratamiento) ? $descripcionTratamiento : $datos_firma[0]['descripcionTratamiento']; ?></strong> y me han sido expuesto los posibles riesgos generales contenidos en las hojas informativas adjuntas, publicadas por la Asociación Española de Endodoncia (AEDE), permitiéndome realizar todas las observaciones y preguntas para aclarar mis dudas.
        <?php } ?>
		</p>
    <ol style="font-family:Arial; line-height:normal;">
    <li>Que se me ha explicado que es necesario que se me realice una extracción
        <?php if ($descripcionTratamiento != null) { ?>
            en el/los diente/s <strong><?php echo isset($descripcionTratamiento) ? $descripcionTratamiento : $datos_firma[0]['descripcionTratamiento']; ?></strong> y me han sido expuesto los posibles riesgos generales contenidos en las hojas informativas adjuntas, publicadas por la Asociación Española de Endodoncia (AEDE), permitiéndome realizar todas las observaciones y preguntas para aclarar mis dudas.
        <?php } ?>
    </li>
    <li style="margin-top: 10px;">Antes de iniciar dicho tratamiento he sido informado/a de que:
        <ul style="margin-top: 20px;">
            <li style="margin-bottom: 10px;">El objetivo del tratamiento es extirpar un diente que es irrecuperable desde el punto de vista odontológico o es perjudicial para la salud del aparato masticador (estomatognático). Entiendo que únicamente podrá ser sustituido por una prótesis.</li>
            <li style="margin-bottom: 10px;">El procedimiento requiere la aplicación de anestesia local, lo que provocará una sensación de acorchamiento del labio o de la cara que desaparecerá al cabo de unas horas. También, que la administración de la anestesia podría producir ulceración y/o hematoma del tejido, dolor y, en raras ocasiones, pérdida de la sensibilidad en la boca o en la cara. Asimismo, puede provocar bajada de tensión y mareo.</li>
            <li style="margin-bottom: 10px;">Aunque me han realizado los medios diagnósticos que se han estimado precisos (considerado oportunos), comprendo que es posible que el estado inflamatorio del diente/molar que se me va a extraer pueda producir un proceso infeccioso y requiera un tratamiento con antibióticos y/o antiinflamatorios, del mismo modo que en el curso del procedimiento puede producirse una hemorragia, la rotura de la corona, heridas en la mucosa de la mejilla o en la lengua, inserción de la raíz en el seno maxilar, fractura del maxilar; que no dependen de la forma o modo de practicarse la intervención, ni de su correcta realización, sino que son imprevisibles, en cuyo caso el facultativo tomará las medidas precisas y continuará con la extracción.</li>
            <li style="margin-bottom: 10px;">Las complicaciones más frecuentes, generalmente con muy poca trascendencia, son: dolor e inflamación de la zona, aparición de hematomas, pequeños daños en los tejidos blandos de la zona afectada, infección de la herida, etc. Si fuera necesario, se le pautará la medicación precisa. Siga siempre las instrucciones de su odontólogo.</li>
            <li style="margin-bottom: 10px;">Todo acto quirúrgico lleva implícitas una serie de complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios tanto médicos como quirúrgicos, y que por mi situación actual
                <?php if ($descripcionRiesgos != null) { ?>
                    (<?php echo isset($descripcionRiesgos) ? $descripcionRiesgos : $datos_firma[0]['descripcionRiesgos']; ?>)
                <?php } ?>
                se pueden aumentar los riesgos.
            </li>
        </ul>
    </li>
</ol>

        </ul>
    </li>
</ol>

			<!-- <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; font-size:8pt">
				<img src="1698829148_extraccin-dental/1698829148_extraccin-dental-8.png" width="702" height="27" alt="" >
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; font-size:8pt">
				<img src="1698829148_extraccin-dental/1698829148_extraccin-dental-9.png" width="144" height="13" alt="" >
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; font-size:8pt">
				<img src="1698829148_extraccin-dental/1698829148_extraccin-dental-10.png" width="55" height="15" alt="" >
			</p>  -->
			<h4 style="color: #000000; text-align: center;">
    DECLARACIONES Y FIRMAS
</h4>

			<h4 style="color: #000000;">
                PACIENTE
            </h4>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span style="font-family:Arial">D./ Dª </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['cliente']); ?></span></strong><strong><span style="font-family:Arial; ">&#xa0; </span></strong><span style="font-family:Arial">y con DNI nº </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['dni']); ?> </span></strong><span style="font-family:Arial">&#xa0;</span><span style="font-family:Arial">declaro que el/la facultativo/a, Dr/Dra</span><strong><span style="font-family:Arial; "> <?php echo strtoupper($datos_doctor[0]['nombre'])." ". strtoupper($datos_doctor[0]['apellidos']); ?> </span></strong><span style="font-family:Arial">me ha explicado de forma satisfactoria qué es, cómo se realiza y para qué sirve esta exploración/intervención. También me ha explicado los riesgos existentes, las posibles molestias o complicaciones, que éste es el procedimiento más adecuado para mi situación clínica actual, y las consecuencias previsibles de su no realización. He comprendido perfectamente todo lo anterior, he podido aclarar las dudas planteadas, y </span><strong><span style="font-family:Arial; ">doy mi consentimiento</span></strong><span style="font-family:Arial"> para que me realicen dicha exploración/intervención. He recibido copia del presente documento. Sé que puedo retirar este consentimiento cuando lo desee.</span>
			</p>
			<!-- <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; font-size:8pt">
				<img src="1698829148_extraccin-dental/1698829148_extraccin-dental-8.png" width="702" height="27" alt="" >
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; font-size:8pt">
				<img src="1698829148_extraccin-dental/1698829148_extraccin-dental-11.png" width="129" height="13" alt="" >
			</p>  -->
			<?php if ($datos_firma[0]['edad']<18){ ?>
			<h4 style="color: #000000; text-align: center;">
                REPRESENTANTE LEGAL
            </h4>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span style="font-family:Arial">D. / Dª </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['nombre_tutor']);  ?></span></strong><span style="font-family:Arial">&#xa0; </span><span style="font-family:Arial">con DNI </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['dni_tutor']);  ?></span></strong><span style="font-family:Arial"> y domicilio en calle </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['direccion']); ?></span></strong><span style="font-family:Arial"> declaro que el/la facultativo/a, Dr/Dra, me ha explicado de forma satisfactoria qué es, cómo se realiza y para qué sirve esta exploración/intervención. También me ha explicado los riesgos existentes, las posibles molestias o complicaciones, que éste es el procedimiento más adecuado para la situación clínica actual del paciente y las consecuencias previsibles de su no realización. He comprendido perfectamente todo lo anterior, he podido aclarar las dudas planteadas, y </span><strong><span style="font-family:Arial; ">doy mi consentimiento </span></strong><span style="font-family:Arial">para que realicen al paciente D./ Dª </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['cliente']); ?></span></strong><span style="font-family:Arial"> con DNI </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['dni']); ?></span></strong><span style="font-family:Arial"> dicha exploración/intervención. He recibido copia del presente documento.</span>
			</p>
			<?php } ?>
			<!-- <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; font-size:8pt">
				<img src="1698829148_extraccin-dental/1698829148_extraccin-dental-8.png" width="702" height="27" alt="" >
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; font-size:8pt">
				<img src="1698829148_extraccin-dental/1698829148_extraccin-dental-12.png" width="73" height="16" alt="" >
			</p> -->
			<h4 style="color: #000000; text-align: center;">
    FACULTATIVO
</h4>

<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
    <span style="font-family:Arial">Dr/Dra </span><strong><span style="font-family:Arial;"><?php echo strtoupper($datos_doctor[0]['nombre'])." ". strtoupper($datos_doctor[0]['apellidos']); ?></span></strong><span style="font-family:Arial;"> He informado a este/a paciente, y/o a su representante legal, del propósito y naturaleza del procedimiento descrito, de sus riesgos y alternativas, y de las consecuencias previsibles de su no realización, dejando constancia en la historia clínica. Asimismo, se le preguntó sobre posibles alergias, la existencia de otras enfermedades o cualquier otra circunstancia patológica personal que pudiera condicionar la realización de la exploración/intervención. Se incorpora este documento a la historia clínica del paciente.</span>
</p>

			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<span style="font-family:Arial">&#xa0;</span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<span style="font-family:Arial"><strong>En</span><span style="font-family:Arial; "> Madrid</strong></span><span style="font-family:Arial"> a dia</span><strong><span style="font-family:Arial; "> </span></strong><span style="font-family:Arial"><?php echo Date('d/m/Y'); ?></span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<span style="font-family:Arial">&#xa0;</span>
			</p>
	
			

			<div style="font-size: 12px; text-align: center;">
    <p><strong>Fdo.: El paciente o (representante legal)</strong></p>
    <p></p>
    <p></p>
    <?php if ($datos_firma[0]['firma'] != "ONLINE" and $datos_firma[0]['firma'] != "") { 
      //$fuente=RUTA_SERVIDOR . "/recursos/consentimientos/" . $datos_firma[0]['firma'];
      
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
    </body>
  </div>