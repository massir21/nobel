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
		CIRUGÍA MICROTORNILLOS
	</h2>
    
          <p>
              <span style="font-family:Arial">Yo, Don/Doña, </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['nombre_tutor']);  ?></span></strong>
              <span style="font-family:Arial">con DNI nº </span><strong><span style="font-family:Arial; "><?php echo $datos_firma[0]['dni_tutor']; ?></span></strong><span style="font-family:Arial">, en calidad de padre, madre, tutor/a o representante legal del paciente: </span>
          </p>
  
	
	
	<p>Don/Doña, <?php echo strtoupper($datos_firma[0]['cliente']); ?> de <?php echo $datos_firma[0]['edad']; ?> años de edad, con domicilio en <?php echo $datos_firma[0]['direccion']; ?> y DNI <?php echo $datos_firma[0]['dni']; ?></p>
	
	  <diV style="font-size: 16px; text-align: center;">
      <b>DECLARO</b><br><br>
    </diV>
	<p>Que por el presente documento REQUIERO Y AUTORIZO a Dr. <?php echo strtoupper($datos_doctor[0]['nombre'])." ". strtoupper($datos_doctor[0]['apellidos']);?> licenciado en Medicina y Cirugía y/o Odontología con número de colegiado <?php echo  strtoupper($datos_doctor[0]['n_colegiado']) ?> y a su equipo a que realice en mi persona el tratamiento de <strong>cirugía microtonillos,</strong> habiéndome explicado que es conveniente proceder, en mi situación, a recibir dicho tratamiento.</p>
	<p>
	
	

      </diV>



<h3 style="text-align: center;">Información consentimiento</h3>


<h4 style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; font-size:16px;">
    <strong>Referente a la microcirugía de colocación de microtonillos ortodónticos:</strong>
</h4>
<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; text-align:justify;">
    <span style="font-family:Arial;">
        <ul>
            <li>Se me ha explicado que van a someterme a un tratamiento de microcirugía con microtornillos de ortodoncia, que consiste en la colocación de microtonillos en la zona ósea que rodea al diente para ser utilizado como un anclaje que permite desplazar o alinear los dientes.</li>
            <li>Entiendo que alternativamente podría recurrir a tratamiento de ortodoncia convencionales, pero las descarto por los beneficios que espero obtener con la técnica con microtonillos de ortodoncia (Ej. menor trauma sobre dientes adyacentes, tratamiento de ortodoncia más efectivo y rápido, etc....)</li>
            <li>Comprendo que es posible que puedan producirse procesos inflamatorios, infecciosos, hinchazón, ligero sangrado y dolor. De forma excepcional y muy infrecuentemente podrían aparecer sinusitis, o infección de las fosas nasales, lesiones sobre dientes adyacentes, fibrosis, laceraciones en las zonas intervenidas y lesiones sobre alguna terminación nerviosa, lo que generaría pérdida o ausencia de sensibilidad en alguna zona de la región bucal. Generalmente la pérdida de sensibilidad es transitoria, aunque puede llegar a ser permanente. Estas complicaciones no dependen de la técnica empleada ni de su correcta realización y de producirse, podrían afectar al resultado de la terapia.</li>
            <li>Entiendo que, aunque la técnica se realice correctamente, existe un porcentaje de fracasos entre el 1-5 %, que pueden requerir la repetición de la intervención.</li>
            <li>Entiendo que durante la microcirugía se pueden tomar decisiones intraoperatorias de las que no he sido informado previamente. También entiendo que, durante la microcirugía, la colocación del microtornillos puede verse impedida debido a factores como un hueso de mala calidad o la presencia de tejido infeccioso en la proximidad de la zona de colocación del implante.</li>
            <li>El hábito de fumar siempre empeora el pronóstico.</li>
            <li>Todo acto quirúrgico lleva implícitas una serie de complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios tanto médicos como quirúrgicos. Existen ciertas condiciones médicas (diabetes, cardiopatía, hipertensión, anemia, edad avanzada, obesidad, etc....) que pueden aumentar estos riesgos y complicaciones.</li>
            <li>Entiendo que en el caso que en el pasado haya sido tratado de un cáncer mediante radioterapia, exista la posibilidad de padecer una condición denominada osteoradonecrosis que podría infectar el hueso maxilar o mandibular y que podría requerir tratamiento quirúrgico respectivo de una parte o la totalidad de mi hueso maxilar y/o mandibular.</li>
            <li>Los tratamientos farmacológicos con bifosfonatos (medicación empleada para la osteoporosis) alteran el metabolismo de reparación del hueso, pudiendo aparecer secuestros óseos y necrosis de los huesos maxilares, incluso años después de haber cesado la medicación.</li>
            <li>Entiendo que el tratamiento no concluye con la colocación del microtornillo. Entiendo que el éxito a largo plazo del tratamiento requiere un control mecánico de la placa bacteriana a través de una rigurosa higiene bucal y de controles de mantenimiento periódicos. Debido a diferencias individuales entre cada paciente, existe riesgo de fracaso recidiva o retratamiento selectivo. Sin embargo, comprendo que el tratamiento será beneficioso. NO acudir a las revisiones puede comportar complicaciones futuras, no imputables a los profesionales que han llevado a cabo el tratamiento.</li>
        </ul>
    </span>
</p>





<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
    <span style="font-family:Arial">El procedimiento puede requerir la aplicación de anestesia local, lo que provocará una sensación de acorchamiento del labio o de la cara que desaparecerá al cabo de unas horas. También, que la administración de la anestesia podría producir ulceración y/o hematoma del tejido, dolor y, en raras ocasiones, pérdida de la sensibilidad en la boca o en la cara. Más raramente, puede provocar bajada de tensión y mareo.</span>
</p>



<h3>
		DECLARACIONES Y FIRMAS
	</h3>
	
	<p>Se me ha dado tiempo suficiente para leer la información precedente que describe el tratamiento de blanqueamiento dental externo. Comprendo los beneficios, riesgos e inconvenientes asociados al tratamiento. <br><br>He sido suficientemente informado y he tenido la oportunidad de hacer preguntas y discutir las preocupaciones sobre el tratamiento de blanqueamiento dental externo con los productos con mi dentista que me ofrece el tratamiento.  <br><br>Por la presente doy mi consentimiento para el tratamiento de blanqueamiento dental externo que ha sido prescrito por mi dentista.
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