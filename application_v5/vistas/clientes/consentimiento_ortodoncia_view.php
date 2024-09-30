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
		ORTODONCÍA
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
			<p style="margin:13.3pt 0.3pt 27pt 0.25pt; text-align:justify; line-height:104%;">
				En cumplimiento de la Ley 41/2002, básica reguladora de la autonomía del paciente y de derechos y obligaciones en materia de información y documentación clínica, se le presenta para su firma el siguiente documento: 
</strong></p>
			
			<p style="margin:13.3pt 0.3pt 13.3pt 0.25pt; text-align:justify; line-height:104%;">
				<span>sobre los procedimientos clínicos de ortodoncia, que constan en el plan de tratamiento que previamente he aceptado. </span>
			</p>
			<p style="margin:13.3pt 0.3pt 13.25pt 0.25pt;  text-align:justify; line-height:104%; ">
				Los procedimientos propios de la ortodoncia van dirigidos al tratamiento de anomalías en la colocación dentaria o en el desarrollo óseo que pueden producir alteraciones en la función masticatoria y en la estética, principalmente.</span><span style="color:#0b0907">&#xa0; </span><span style="color:#0b0907">El propósito principal de la ortodoncia es mejorar la salud bucal</span><span style="color:#0b0907">&#xa0; </span><span style="color:#0b0907">al</span><span style="color:#0b0907">&#xa0; </span><span style="color:#0b0907">conseguir un alineamiento correcto de los dientes así como una relación intermaxilar adecuada con una oclusión normal. Los tratamientos de ortodoncia también pueden formar parte de tratamientos más complejos y asociarse a tratamientos periodontales o de prótesis dental. En ocasiones la ortodoncia se realiza como preparación de las arcadas para tratamientos de cirugía ortognática (que realizará bajo su responsabilidad el cirujano máxilofacial).La ortodoncia incluye muchos posibles procedimientos. Éstos se podrían clasificar, de forma muy básica, en procedimientos de aparatología fija (vestibular o lingual), aparatología removible, aparatos ortopédicos bucofaciales, y todas sus posibles combinaciones. Todos los aparatos pero en especial los removibles requieren una adecuada colaboración en su uso y cuidados para obtener el resultado previsto. Si el paciente no lo usa el tiempo prescrito, el resultado y la duración no serán los previstos. 
			</p>
			
			
    <?php if ($descripcionProblema != null){ ?>
			<p style="margin:13.25pt 0.3pt 13.8pt 0.05pt; text-indent:-0.05pt;">
				<span>Este procedimiento está indicado para el problema que tiene el/ la paciente, consistente en:</span><br/>
                <?php if (isset($descripcionProblema)){ echo  $descripcionProblema; } else { echo $datos_firma[0]['descripcionProblema']; } ?>
			</p>
    <?php } ?>
			
    <?php if ($descriptionProcTerapeuticos != null){ ?>

    <p style="margin:13.8pt 0.3pt 3.4pt 0.25pt; text-indent:-0.25pt; text-align:justify; line-height:104%;">
				<span style="color:#0b0907">Se han sopesado y descartado por distintos motivos de los que ha sido informado/a otros procedimientos terapéuticos alternativos como:</span>
                <?php if (isset($descriptionProcTerapeuticos)){ echo  $descriptionProcTerapeuticos; } else { echo $datos_firma[0]['descriptionProcTerapeuticos']; } ?>
			</p>
			
    <?php } ?>

</div>
		
		<div>
			
			
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; text-align:justify; line-height:normal;">
				<span style="color:#00000">El/la paciente ha sido informado/a y conoce los riesgos que puede comportar este tratamiento: </span>
			</p>
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; text-align:justify; line-height:normal; ">
				<span style="color:#000000">&#xa0;</span>
			</p>
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; text-align:justify; line-height:normal; ">
				<span style="color:#000000">Riesgo de sensibilidad dentaria debido a las fuerzas ejercidas sobre los dientes o maxilares. </span>
			</p>
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; text-align:justify; line-height:normal;">
				<span style="color:#000000">&#xa0;</span>
			</p>
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; text-align:justify; line-height:normal;">
				<span style="color:#000000">Riesgo de que se suelten partes de la aparatología fija (brackets, bandas, arcos, etc.) debido a las fuerzas masticatorias. Ante esta situación hay que consultar con el profesional. </span>
			</p>
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; text-align:justify; line-height:normal;">
				<span style="color:#000000">&#xa0;</span>
			</p>
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; text-align:justify; line-height:normal;">
				<span style="color:#000000">Riesgo de ingestión, o incluso aspiración, de los materiales empleados en ortodoncia fija (brackets, etc.) que se hubiesen podido despegar completamente. </span>
			</p>
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; text-align:justify; line-height:normal;">
				<span style="color:#000000">&#xa0;</span>
			</p>
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; text-align:justify; line-height:normal;">
				<span style="color:#000000">Riesgo de pequeñas molestias dolorosas de los dientes y la irritación de encías, labios, mejillas y lengua (llagas). Estas molestias suelen ser iniciales y remiten espontáneamente</span><span style="color:#0b0907">&#xa0; </span><span style="color:#0b0907">en unos días.</span>
			</p>
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; text-align:justify; line-height:normal;">
				<span style="color:#000000">&#xa0;</span>
			</p>
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; text-align:justify; line-height:normal;">
				<span style="color:#000000">Riesgo de alergia a los materiales empleados que podría provocar su retirada</span><span style="color:#0b0907">&#xa0; </span><span style="color:#0b0907">un eventual cambio en el plan de tratamiento. </span>
			</p>
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; text-align:justify; line-height:normal;">
				<span style="color:#000000">&#xa0;</span>
			</p>
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; text-align:justify; line-height:normal; ">
				<span style="color:#000000">Riesgo de que una deficiente higiene facilite la aparición de manchas blancas permanentes (descalcificaciones), caries dental o gingivitis (encías inflamadas). Se me ha explicado con toda claridad que durante el tratamiento debo de extremar las medidas higiénicas y evitar la ingesta frecuente de productos muy azucarados.</span>
			</p>
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; text-align:justify; line-height:normal; ">
				<span style="color:#000000">&#xa0;</span>
			</p>
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; text-align:justify; line-height:normal;">
				<span style="color:#000000">En caso de tratamientos ortodóncicos con finalidad principalmente estética existirá siempre el riesgo de no cumplir con las expectativas del paciente por motivos difícilmente evitables: imprevisibilidad del crecimiento bucofacial, etc. • Riesgo de que el desarrollo imprevisible de la erupción dentaria, el crecimiento de los maxilares o de respuesta de dientes o hueso a las fuerzas ortodóncicas obliguen a cambiar el plan de tratamiento, requiriendo en ocasiones extracciones de dientes definitivos para conseguir espacio y el alargamiento del tiempo de tratamiento. </span>
			</p>
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; text-align:justify; line-height:normal; ">
				<span style="color:#000000">&#xa0;</span>
			</p>
			
		
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; text-align:justify; line-height:normal;">
				<span style="color:#000000">Algunos pacientes son mas susceptibles a que se produzca la reabsorción (acortamiento) de la raíz de uno o varios dientes sometidos a fuerzas ortodóncicas. Este fenómeno es infrecuente, de etiología desconocida pero imprevisible. Habitualmente esto no tiene consecuencias apreciables, pero en ocasiones puede afectar a la longevidad del diente e implicaría alterar el plan de tratamiento. </span>
			</p>
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; text-align:justify; line-height:normal;">
				<span style="color:#000000">&#xa0;</span>
			</p>
			
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; text-align:justify; line-height:normal;">
				<span style="color:#000000">Riesgo de molestias o dolor en la articulación témporo-mandibular debido a la modificación del patrón oclusal necesario para el correcto alineamiento dental. Estos problemas pueden ocurrir con o sin tratamiento de ortodoncia y en general son debidos a factores previos predisponentes (hiperlaxitud ligamentosa, traumatismos previos, artrosis, artritis, bruxismo, stress etc.) y también a un fenómeno de maduración esquelética.</span>
			</p>
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; text-align:justify; line-height:normal;">
				<span style="color:#000000">&#xa0;</span>
			</p>
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; text-align:justify; line-height:normal;">
				<span style="color:#000000">Riesgo de retracciones de la encía, no previsibles, debidas al efecto de los movimientos dentarios. También pueden aparecer hipertrofiadas como consecuencia del acumulo de placa bacteriana.</span><span style="color:#0b0907">&#xa0;&#xa0; </span>
			</p>
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; text-align:justify; line-height:normal;">
				<span style="color:#000000">&#xa0;</span>
			</p>
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; text-align:justify; line-height:normal;">
				<span style="color:#000000">Los dientes incluidos tienen un tratamiento mas complejo y sus resultados no se pueden asegurar. Existe la posibilidad que el diente incluido dañe la raíz de los dientes vecinos hasta en ocasiones, provocar su pérdida. En ocasiones el tratamiento falla por anquilosis dental</span><span style="color:#0b0907">&#xa0; </span><span style="color:#0b0907">que es imposible diagnosticar previo al tratamiento y que conllevaría la necesidad de extraerlo y reponerlo y estas actuaciones corresponderían a su dentista. </span>
			</p>
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; line-height:normal;">
				<span style="color:#000000">&#xa0;</span>
			</p>
			<p style="margin-top:0pt; margin-right:0.3pt; margin-bottom:0pt; line-height:normal;">
    Riesgo de que se produzcan modificaciones en los resultados conseguidos a la conclusión del tratamiento por factores de desarrollo o erupción dentaria, o por modificaciones en el hueso de soporte dental. Estos factores son difícilmente predecibles pero pueden ser paliados siguiendo las indicaciones dadas por el profesional respecto a la utilización de retenedores y a las revisiones una vez terminado el tratamiento.
</p>

<p style="margin:0pt 0.3pt 0pt 0.25pt; text-indent:-0.25pt; text-align:justify; line-height:104%;">
    <?php if ($descripcionTratamiento != null){ ?>
        <span style="color:#000000">Asimismo el Sr./Sra </span><span style="line-height:104%;"><?php echo strtoupper($datos_firma[0]['cliente']); ?> </span><span style="color:#000000">&#xa0;</span><span style="color:#000000">sus especiales condiciones personales
            (<?php if (isset($descripcionTratamiento)){ echo  $descripcionTratamiento; } else { echo $datos_firma[0]['descripcionTratamiento']; } ?>)
    <?php } ?>

    <?php if ($descripcionRiesgos != null){ ?>
        , puede presentar riesgos añadidos consistentes en: </span><br/>
        <?php if (isset($descripcionRiesgos)){ echo  $descripcionRiesgos; } else { echo $datos_firma[0]['descripcionRiesgos']; } ?>
    <?php } ?>
</p>

<p style="margin:0pt 0.3pt 0pt 0.25pt; text-align:justify; line-height:104%;">
    <span style="color:#000000">El paciente ha sido informado de la importancia crítica de la higiene bucodental durante el tratamiento de ortodoncia. Un deficiente control de la placa bacteriana puede obligar incluso a la suspensión del tratamiento. </span>
</p>


			<p style="margin:13.3pt 0.3pt 13.3pt 0.25pt;  text-align:justify; line-height:104%; ">
				<span style="color:#000000">El paciente también ha sido informado de que los tratamientos de ortodoncia, debido a los factores propios del desarrollo óseo y la erupción dentaria, pueden alargarse más tiempo del esperado. Asimismo los resultados conseguidos al final del tratamiento se pueden ver alterados por estos mismos factores.</span><span style="color:#0b0907">&#xa0; </span>
			</p>
			<p style="margin:13.3pt 0.3pt 13.3pt 0.25pt;  text-align:justify; line-height:104%;">
				<span style="color:#000000">La ortodoncia no es una ciencia exacta y, por</span><span style="color:#000000">&#xa0; </span><span style="color:#0b0907">ello ningún ortodoncista puede certificar el éxito ni garantizar un resultado específico. </span>
			</p>
			<p style="margin:13.3pt 0.3pt 13.3pt 0.25pt;  text-align:justify; line-height:104%;">
				<span style="color:#000000">Es relativamente frecuente que durante el curso del tratamiento se produzcan despegamientos o roturas de la aparatología utilizada que requerirán consultar con el ortodoncista.</span>
			</p>
			<p style="margin:13.3pt 0.3pt 13.15pt 0.25pt;  text-align:justify; line-height:104%;">
				<span style="color:#000000">Para prevenir todas estas circunstancias se compromete a someterse a las </span><strong><span style="color:#0b0907">revisiones periódicas </span></strong><span style="color:#0b0907">que el profesional considere oportunas durante e incluso después de concluir el tratamiento.</span><span style="color:#0b0907">&#xa0; </span>
			</p>
			<p style="margin:13.15pt 0.3pt 27.15pt 0.25pt; text-align:justify; line-height:104%;">
				<span style="color:#000000">El paciente conoce que, después de terminar el tratamiento y dependiendo del caso concreto, es necesario utilizar algún sistema de contención para evitar modificaciones posteriores de la alineación dentaria. Esta fase de retención también precisa revisiones, aunque más espaciadas. Un tratamiento de ortodoncia no garantiza que los dientes estén perfectamente alineados toda la vida, pues a lo largo de los años los dientes están sometidos a fuerzas de masticación, habla, hábitos y al desgaste propio que sufre todo el organismo. </span>
			</p>
			
			<h3>
    DECLARACIONES Y FIRMAS
</h3>

<p>Se me ha dado tiempo suficiente para leer la información precedente que describe el tratamiento de ortodoncia. Comprendo los beneficios, riesgos e inconvenientes asociados al tratamiento. <br><br>He sido suficientemente informado y he tenido la oportunidad de hacer preguntas y discutir las preocupaciones sobre el tratamiento de ortodoncia con los productos con mi dentista que me ofrece el tratamiento.  <br><br>Por la presente doy mi consentimiento para el tratamiento de ortodoncia que ha sido prescrito por mi dentista.
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
En <b>Madrid</b> a día  <b><?php echo date('d/m/Y'); ?><br><br>
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
