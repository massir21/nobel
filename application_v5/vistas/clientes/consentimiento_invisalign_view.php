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
		INVISALIGN
	</h2>
	<p>
              <span style="font-family:Arial">Yo, Don/Doña, </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['nombre_tutor']);  ?></span></strong>
              <span style="font-family:Arial">con DNI nº </span><strong><span style="font-family:Arial; "><?php echo $datos_firma[0]['dni_tutor']; ?></span></strong><span style="font-family:Arial">, en calidad de padre, madre, tutor/a o representante legal del paciente: </span>
          </p>
		  <p>Don/Doña, <?php echo strtoupper($datos_firma[0]['cliente']); ?> de <?php echo $datos_firma[0]['edad']; ?> años de edad, con domicilio en <?php echo $datos_firma[0]['direccion']; ?> y DNI <?php echo $datos_firma[0]['dni']; ?></p>
	
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				&#xa0;
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				Contrato y consentimiento informado del paciente con respecto al tratamiento de ortodoncia Invisalign
	</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				&#xa0;
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:normal; widows:0; orphans:0">
				Su médico le ha recomendado el sistema Invisalign para su tratamiento de ortodoncia. Aunque el tratamiento de ortodoncia puede ayudarle a disfrutar de una sonrisa más saludable y atractiva, también debe tener en cuenta que cualquier tratamiento de ortodoncia (incluido el tratamiento de ortodoncia con aligners Invisalign) tiene limitaciones y riesgos potenciales que debe considerar antes de someterse a él. </span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				Descripción del dispositivo. 
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:normal; widows:0; orphans:0">
				Los aligners Invisalign, desarrollados por Align Technology, Inc. («Align») consisten en una serie de dispositivos extraíbles de plástico transparente que mueven los dientes en pequeños incrementos. Los productos de Invisalign combinan el diagnóstico y la prescripción de su doctor con la sofisticada tecnología de los gráficos por ordenador para desarrollar un plan de tratamiento que especifique los movimientos que deseamos que realicen los dientes en el transcurso de su tratamiento. Tras la aprobación de un plan de tratamiento desarrollado por su doctor, se crean una serie de aligners Invisalign personalizados específicamente para su tratamiento. </span>
			</p>
			<h3 style="margin: top 10px; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				Procedimiento:
</h3>
			<p style="margin-top:5pt; margin-bottom:0pt; text-align:justify; line-height:normal; widows:0; orphans:0">
				<span>Es posible que tenga que someterse a un examen ortodóntico rutinario previo al tratamiento que incluya radiografías (rayos X) y fotografías. Su doctor realizará impresiones y escaneos intraorales de sus dientes y los enviará al laboratorio de Align junto con una prescripción. Los técnicos de Align seguirán las prescripciones de su doctor para crear un modelo de su tratamiento en el software ClinCheck</span><span></span><span>. Tras la aprobación por parte de su doctor del plan de tratamiento ClinCheck, Align creará y enviará una serie de aligners personalizados a su doctor. El número total de aligners dependerá de la complejidad de la maloclusión y del plan de tratamiento del doctor. Los aligners se numerarán individualmente y su doctor se los entregará junto con las instrucciones de uso. A menos que su doctor le indique lo contrario, deberá llevar puestos los aligners aproximadamente entre 20 y 22 horas al día y quitárselos solamente para comer, cepillarse los dientes y utilizar hilo dental. Según lo indique su doctor, deberá cambiar al siguiente juego de aligners de la serie cada dos semanas o según le indique el doctor. La duración del tratamiento dependerá de la complejidad de la prescripción de su doctor. A menos que se le indique lo contrario, deberá tener citas de seguimiento con su doctor cada 6 u 8 semanas como mínimo. </span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:normal; widows:0; orphans:0">
				<span >Algunos pacientes pueden necesitar attachments estéticos cementados o el uso de elásticos durante el tratamiento para facilitar determinados movimientos ortodónticos. Es posible que algunos pacientes requieran impresiones o escaneos intraorales adicionales y/o aligners de refinement después de la serie inicial de aligners. </span>
			</p>
			<h3 style="margin-top:10pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				Beneficios:
</h3>
<ul style="margin-top: 5pt; margin-bottom: 0pt; line-height: normal; widows: 0; orphans: 0;">
    <li style="margin-bottom: 9.95pt;">Los aligners de Invisalign ofrecen una alternativa más estética a los brackets convencionales.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Los aligners son casi invisibles y poca gente se dará cuenta de que usted se encuentra realizando el tratamiento.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Se puede acceder a los planes de tratamiento desde el software ClinCheck.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Los aligners permiten seguir realizando las tareas habituales de cepillado y uso de hilo dental que generalmente se ven afectadas por el uso de brackets convencionales.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Los aligners no tienen alambres ni soportes metálicos a diferencia de los brackets convencionales.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Utilizar aligners puede mejorar los hábitos de higiene oral durante el tratamiento.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 0pt;">Es posible que los pacientes de Invisalign noten una mejoría en su salud periodontal (encías) durante el tratamiento.</li>
</ul>

			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span >&#xa0;</span>
			</p>
			<h3 style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span>Riesgos e inconvenientes: </h3>
			
			<p style="margin-top:5pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span>Al igual que otros tratamientos ortodónticos, el uso de productos Invisalign puede implicar algunos de los riesgos descritos a continuación: </span>
			</p>
			
			<ol>
    <li style="margin-top: 0pt; margin-bottom: 10.05pt;">No utilizar los aparatos durante el número requerido de horas al día, no utilizar el producto según lo indicado por su doctor, no asistir a las citas programadas o la existencia de dientes en erupción o con forma atípica pueden alargar el tiempo de tratamiento e influir sobre la posibilidad de conseguir los resultados deseados.</li>
    <li style="margin-top: 10.05pt; margin-bottom: 9.95pt;">Es posible que se experimente sensibilidad dental al cambiar al siguiente aligner de la serie.</li>
    <li style="margin-top: 0pt; margin-bottom: 9.95pt;">Es posible que las encías, las mejillas y los labios se rasguen o irriten.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Los dientes podrían cambiar de posición después del tratamiento. El uso constante de retenedores al final del tratamiento debería reducir esta tendencia.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Podría producirse caries dental, enfermedad periodontal, inflamación de las encías o marcas permanentes (como descalcificación) si el paciente consume alimentos o bebidas que contienen azúcar, no se cepilla los dientes adecuadamente antes de usar los productos Invisalign o no tiene una higiene oral adecuada ni realiza un mantenimiento preventivo.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Los aligners pueden afectar temporalmente al habla y pueden originar un ligero ceceo, aunque cualquier impedimento del habla causado por los productos Invisalign® debería desaparecer al cabo de una o dos semanas.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Los aligners pueden provocar un aumento temporal de la salivación o la la sequedad bucal y ciertos medicamentos pueden agravar este efecto.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Los attachments son «botones» del color del diente que pueden cementarse a uno o más dientes durante el tratamiento para facilitar el movimiento dental o la retención del aligner. El uso de attachments puede hacer que el tratamiento se vea más. Se retirarán al término del tratamiento.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Los attachments pueden caerse y requerir sustitución.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Los dientes pueden requerir reducción interproximal o estrechamiento para crear el espacio necesario para que se produzca la alineación dental.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Es posible que cambie la mordida a lo largo del tratamiento y eso puede ocasionar molestias temporales al paciente.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">En raras ocasiones, puede ocurrir un ligero desgaste superficial del aligner en el punto donde los pacientes puedan estar rechinar los dientes o donde los dientes tengan un ligero roce y normalmente no supondrá ningún problema, ya que la integridad y la fuerza del aligner permanecerán intactos a pesar de ello.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Al final del tratamiento ortodóntico, la mordida puede requerir un ajuste («ajuste oclusal»).</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Los dientes con forma atípica o que estén en erupción o los huecos donde falten dientes pueden afectar a la adaptación del aligner y a la capacidad de conseguir los resultados deseados.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">El tratamiento de la mordida abierta grave, el resalte grave, la dentición mixta o una mandíbula esqueléticamente estrecha puede requerir tratamiento complementario además del tratamiento del aligner.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Es posible que sea necesario realizar un tratamiento ortodóntico complementario, por ejemplo con botones cementados, elásticos ortodónticos, dispositivos dentales/aparatos auxiliares (como dispositivos de anclaje temporales, aparatología fija seccional) o procedimientos dentales de restauración en el caso de planes de tratamiento más complicados donde los aligners por sí solos pueden no ser suficiente para conseguir el resultado deseado.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Los dientes que llevan mucho tiempo superpuestos pueden no presentar tejido gingival por debajo del contacto interproximal una vez que los dientes están alineados, lo que da lugar a la aparición de un «triángulo negro».</li>
	<li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Los aligners no son efectivos en el movimiento de implantes dentales.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Las patologías médicas generales y el uso de medicamentos pueden afectar al tratamiento ortodóntico.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">La salud de los huesos y de las encías que sostienen los dientes puede verse afectada o agravada.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Puede ser necesaria la realización de cirugía oral para corregir el apiñamiento o los desequilibrios mandibulares graves que haya presentes antes de usar el producto Invisalign. Si se requiere cirugía oral, se deben tener en cuenta los riesgos asociados con la anestesia y lograr una adecuada cicatrización antes del tratamiento.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Si un diente ha sufrido algún traumatismo o una restauración significativa anteriormente, podría sufrir un agravamiento. En casos raros, la vida útil del diente podría reducirse, el diente podría necesitar un tratamiento adicional, como endodoncia o restauraciones adicionales, o bien el diente podría llegar a perderse.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Es posible que las restauraciones dentales existentes (como las coronas) se desplacen y sea necesario volver a cementarlas o sustituirlas.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Es posible que las coronas clínicas cortas provoquen problemas de retención del aparato y dificulten el movimiento de los dientes.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">La longitud de las raíces de los dientes podría acortarse durante el tratamiento ortodóntico y podría suponer un peligro para la vida útil de estos.</li>
    <li style="margin-top: 0pt; margin-bottom: 9.95pt;">Es más probable que se rompa el producto en pacientes con apiñamiento grave o a los que les falten varios dientes.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Los aparatos ortodónticos o sus partes podrían tragarse o aspirarse accidentalmente.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">En raras ocasiones, también podrían presentarse problemas en la articulación de la mandíbula, provocando dolor en las articulaciones, dolores de cabeza y problemas de oído.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Podrían producirse reacciones alérgicas.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 9.95pt;">Los dientes que no estén cubiertos al menos parcialmente por el aligner podrían desarrollar sobreerupción.</li>
    <li style="margin-top: 9.95pt; margin-bottom: 0pt;">En raras ocasiones, los pacientes con angioedema hereditario (AEH), un trastorno genético, podrían experimentar una inflamación local rápida de los tejidos subcutáneos, incluida la laringe. El AEH puede desencadenarse por estímulos leves, como por ejemplo procedimientos dentales.</li>
</ol>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span>&#xa0;</span>
			</p>
			
			<h3 style="margin-top:-100pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; text-align:center;">
    Consentimiento informado:
</h3>

			
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:normal; widows:0; orphans:0; ">
				<span>&#xa0;</span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:normal; widows:0; orphans:0;">
				<span>Se me ha dado tiempo suficiente para leer y he leído la información anterior que describe el tratamiento de ortodoncia con aligners Invisalign. Comprendo los beneficios, los riesgos, las alternativas y los inconvenientes asociados al tratamiento, así como la opción de no recibir tratamiento. He sido suficientemente informado y he tenido la oportunidad de plantear preguntas y debatir inquietudes sobre el tratamiento ortodóntico con productos Invisalign</span><span> </span><span>con el doctor de quien me dispongo a recibir el tratamiento. Entiendo que solo debo usar los productos Invisalign después de la consulta y la prescripción de un doctor formado en Invisalign y doy mi consentimiento para el tratamiento ortodóntico con los productos Invisalign prescritos por mi doctor. </span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:normal; widows:0; orphans:0;">
				<span>Dado que la ortodoncia no es una ciencia exacta, reconozco que mi doctor y Align Technology, Inc., con sede en San José, California («Align») no garantizan ni pueden asegurar el resultado de mi tratamiento. Entiendo que Align no es un proveedor de servicios médicos ni dentales ni de atención médica y no ofrece ni puede practicar la medicina ni la odontología ni dar asesoramiento médico. Ni mi doctor, ni Align, ni sus representantes, sucesores, cesionarios o agentes me han ofrecido garantías de ningún tipo con respecto a ningún resultado específico de mi tratamiento. </span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:normal; widows:0; orphans:0;">
				<span>&#xa0;</span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:normal; widows:0; orphans:0;">
				<span >Entiendo que mi médico recopile y use mis registros médicos, que incluyen, como radiografías (rayos X), informes, diagramas, historial médico, fotografías, hallazgos, modelos de yeso, impresiones de los dientes o escaneos intraorales, prescripciones, diagnósticos, pruebas médicas, resultados de pruebas, raza, datos de facturación y otros registros de tratamiento que obren en su posesión («Registros médicos»), en la medida necesaria para el tratamiento, el servicio al cliente y la facturación. Si no doy mi consentimiento para estos usos de mis registros médicos, es posible que no pueda recibir un tratamiento Invisalign. </span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:normal; widows:0; orphans:0;">
				<span>&#xa0;</span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:normal; widows:0; orphans:0;">
				<span>No solicitaré, ni nadie en mi nombre solicitará, indemnizaciones ni reparaciones por daños o perjuicios legales, monetarios o en equidad por tal divulgación. Reconozco que el uso de mis Registros médicos se realiza sin compensación y que ni yo ni nadie en mi nombre contaremos con ningún derecho de aprobación ni reclamación de indemnización ni solicitaremos ni obtendremos indemnizaciones legales, monetarias ni en equidad derivadas de cualquier uso que cumpla con las condiciones de este Consentimiento. </span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:normal; widows:0; orphans:0; ">
				<span >&#xa0;</span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:normal; widows:0; orphans:0;">
				<span >Una copia de este Consentimiento se considerará tan efectiva y válida como el original. He leído, entiendo y acepto los términos establecidos en este Consentimiento tal como lo indica mi firma a continuación. </span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span >&#xa0;</span>
			</p>
			<h3 style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span >Aviso de privacidad </span>
	</h3>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span >&#xa0;</span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:normal; widows:0; orphans:0;">
				<span >Su doctor es el responsable de los datos personales contenidos en sus registros médicos. Align y otros miembros de su grupo corporativo («el Grupo Align») recibirán sus registros médicos, que pueden almacenarse en servidores del Grupo Align situados fuera de su país de residencia. Sin embargo, el Grupo Align seguirá protegiendo su información de conformidad con las normas corporativas vinculantes del Grupo Align, que se pueden consultar en </span><span style="color:#0461c1">www.aligntech.com</span><span style="color:#686b72">. </span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:normal; widows:0; orphans:0;">
				<span >&#xa0;</span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:normal; widows:0; orphans:0;">
				<span >Sus Registros médicos solo se compartirán con terceros si la ley vigente lo permite, como por ejemplo si es necesario para proteger sus intereses vitales o los de otra persona o si es necesario para el establecimiento, el ejercicio o la defensa de reclamaciones legales. Su doctor y el Grupo Align procesarán sus Registros médicos para poder llevar a cabo su tratamiento. El Grupo Align también podría anonimizar sus Registros médicos y utilizarlos para fines internos, de análisis o investigación. Su doctor y el Grupo Align conservarán sus Registros médicos solo mientras exista una necesidad legítima continua de hacerlo, como por ejemplo para proporcionarle el tratamiento y cumplir con las obligaciones reglamentarias. Los Registros médicos se eliminarán o anonimizarán o, si esto no es posible (por ejemplo porque sus registros médicos se hayan almacenado en archivos de copia de seguridad), se guardarán de forma segura hasta que sea posible su eliminación. </span>
			</p>
			
			<p style="margin-top:10pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span>Si tiene alguna pregunta sobre cómo se utilizan sus Registros médicos, puede preguntar a su doctor sobre cualquiera de sus derechos a: </span>
			</p>
			<ul>
    <li style="margin-top:0pt; margin-bottom:0pt; line-height:normal;">Acceder, corregir, actualizar o solicitar la eliminación de sus Registros médicos</li>
    <li style="margin-top:0pt; margin-bottom:0pt; line-height:normal;">Restringir el procesamiento de sus Registro médicos</li>
    <li style="margin-top:0pt; margin-bottom:0pt; line-height:normal;">Solicitar a su doctor la transferencia de sus Registros médicos a otro doctor</li>
    <li style="margin-top:0pt; margin-bottom:0pt; line-height:normal;">Retirar en cualquier momento su consentimiento para el procesamiento de sus Registros médicos</li>
</ul>

			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span>&#xa0;</span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span >También tiene derecho a quejarse ante la autoridad de protección de datos local sobre la recopilación y el uso de sus registros médicos. </span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span>&#xa0;</span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span >&#xa0;</span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; ">
				<span >Paciente: </span><strong><span><?php echo strtoupper($datos_firma[0]['cliente']); ?> con DNI Nº. <?php echo strtoupper($datos_firma[0]['dni']); ?></span></strong>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; ">
				<span >&#xa0;</span>
			</p>
			
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; ">
				<span >Clínica Dental: <?php echo $nombre_centro; ?></span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span>Colaborador:</span><span>&#xa0; </span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; ">
				<span>&#xa0;</span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; ">
				<span>Firmado en <strong>Madrid</strong> a <?php echo Date('d/m/Y'); ?></span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; ">
				<span">&#xa0;</span>
			</p>
			
			
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; ">
				<span >Align Technology BV </span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span>Arlandaweg 161 1043 HS Ámsterdam Países Bajos </span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; ">
				<span >España: 900 98 49 70 </span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0;">
				<span >Otros países (en inglés): +31 (0)20 586 3615 </span>
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; ">
				<span >www.invisalign.es </span>
			</p>
			<p style="margin-top: 0pt; margin-bottom: 0pt; line-height: normal; widows: 0; orphans: 0;">
     2018 Align Technology (BV). Todos los derechos reservados.
</p>
<p style="margin-top: 0pt; margin-bottom: 0pt; line-height: normal; widows: 0; orphans: 0;">
    Invisalign, ClinCheck® y SmartTrack® son, entre otras, marcas comerciales y/o marcas de servicio de Align Technology, Inc. o de una de sus filiales o empresas asociadas, y pueden estar registradas en los Estados Unidos y/o en otros países.
</p>
<p style="margin-top: 0pt; margin-bottom: 0pt; line-height: normal; widows: 0; orphans: 0;">
    F16118 rev C
</p>
<p style="margin-top: 0pt; margin-bottom: 0pt; line-height: normal; widows: 0; orphans: 0;">
    Si el paciente no tiene capacidad jurídica para firmar, deberá firmar este Contrato también el padre, la madre o su tutor legal.
</p>
<p style="margin-top: 0pt; margin-bottom: 0pt; line-height: normal; widows: 0; orphans: 0;">
    &#xa0;
</p>
<p style="margin-top: 0pt; margin-bottom: 0pt; line-height: normal; widows: 0; orphans: 0;">
    &#xa0;
</p>
<p style="margin-top: 0pt; margin-bottom: 0pt; line-height: normal; widows: 0; orphans: 0;">
    &#xa0;
</p>
<p style="margin-top: 0pt; margin-bottom: 0pt; line-height: normal; widows: 0; orphans: 0;">
    &#xa0;
</p>

			<!-- <p style="margin-top:0pt; margin-bottom:0pt; text-align:right; line-height:normal; widows:0; orphans:0; font-size:6.5pt">
				<img src="1698782210_c.i.-invisalign/1698782210_c.i.-invisalign-1.jpeg" width="277" height="111" alt="" >
			</p> -->

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
	