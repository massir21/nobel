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
		Implantes
	</h2>
	<br></br><p>
              <span style="font-family:Arial">Yo, Don/Doña, </span><strong><span style="font-family:Arial; "><?php echo strtoupper($datos_firma[0]['nombre_tutor']);  ?></span></strong>
              <span style="font-family:Arial">con DNI nº </span><strong><span style="font-family:Arial; "><?php echo $datos_firma[0]['dni_tutor']; ?></span></strong><span style="font-family:Arial">, en calidad de padre, madre, tutor/a o representante legal del paciente: </span>
          </p>
		  <p>Don/Doña, <?php echo strtoupper($datos_firma[0]['cliente']); ?> de <?php echo $datos_firma[0]['edad']; ?> años de edad, con domicilio en <?php echo $datos_firma[0]['direccion']; ?> y DNI <?php echo $datos_firma[0]['dni']; ?></p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; font-family:Arial;">
   
			<!-- <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<span style="font-family:Arial">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span><img src="1698864515_implante/1698864515_implante-4.png" width="457" height="66" alt="" >
			</p> -->
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="font-family:Arial">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span><span style="width:7.06pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="font-family:Arial">&#xa0;&#xa0; </span><span style="width:3.53pt; font-family:Arial; display:inline-block">&#xa0;</span>
			</p>
			<!-- <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<img src="1698864515_implante/1698864515_implante-5.png" width="161" height="55" alt="" ><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="font-family:Arial">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span>
			</p> -->
			<!-- <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
				<span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><span style="width:12.7pt; font-family:Arial; display:inline-block">&#xa0;</span><img src="1698864515_implante/1698864515_implante-6.png" width="516" height="16" alt="" ><span style="font-family:Arial">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span><span style="font-family:Arial; font-size:8pt">&#xa0;&#xa0; </span>
			</p> -->
	<h3>DECLARO:
	</h3>
			

<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0t">
    <span style="color:#181717">Que el/la Dr/Dra: <?php echo strtoupper($datos_doctor[0]['nombre'])." ". strtoupper($datos_doctor[0]['apellidos']); ?></span>
</p>
<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
    <span style="color:#181717">me ha explicado, en términos asequibles, que necesito un </span><strong><span style="color:#181717">implante dental </span></strong><span style="color:#181717">&#xa0;</span>
    <?php if ($descripcionTratamiento != null){ ?>
        <span style="color:#181717">en </span>
        <span style="color:#181717">el/los diente/s <strong><span style="color:#181717"><?php if (isset($descripcionTratamiento)){ echo  $descripcionTratamiento; } else { echo $datos_firma[0]['descripcionTratamiento']; } ?></span></strong> y me han sido expuesto los posibles riesgos generales contenidos en las hojas informativas adjuntas, publicadas por la Asociación Española de Endodoncia (AEDE), permitiéndome realizar todas las observaciones y preguntas para aclarar mis dudas.</span><span style="color:#181717">              </span>
    <?php } ?>
<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0">
    <strong><span style="font-family:Arial; ">&#xa0;</span></strong>
</p>
</p>
<ul style="margin-top: 0pt; margin-bottom: 0pt; line-height: normal; widows: 0; orphans: 0">
    <li style="margin-top: 0pt; margin-bottom: 0pt; line-height: normal; widows: 0; orphans: 0; font-family: Arial;">Que se me ha explicado que es necesario que se me coloque uno o varios implantes.</li>
    <li style="margin-top: 0pt; margin-bottom: 0pt; line-height: normal; widows: 0; orphans: 0; font-family: Arial;">Que antes de iniciar dicho tratamiento he sido informado/a de que:</li>
    <ul style="margin-top: 0pt; margin-bottom: 0pt; line-height: normal; widows: 0; orphans: 0">
        <li style="margin-top: 0pt; margin-bottom: 0pt; line-height: normal; widows: 0; orphans: 0; font-family: Arial;">El objetivo de la colocación de un implante es la reposición del diente perdido, suponiendo una mejora en la función (masticación), estética y fonación del paciente. Sé que alternativamente podría recurrir a prótesisconvencionales de menorcoste, pero lo descarto por los beneficios que se pueden obtener con la técnica implantológica.</li>
        <li style="margin-top: 0pt; margin-bottom: 0pt; line-height: normal; widows: 0; orphans: 0; font-family: Arial;">El procedimiento requiere la aplicación de anestesia local, lo que provocará una sensación de acorchamiento del labio o de la cara que desaparecerá al cabo de unas horas. También, que la administración de la anestesia podría producir ulceración y/o hematoma del tejido, dolor y, en raras ocasiones, pérdida de la sensibilidad en la boca o en la cara. Asimismo, puede provocar bajada de tensión y mareo.</li>
        <li style="margin-top: 0pt; margin-bottom: 0pt; line-height: normal; widows: 0; orphans: 0; font-family: Arial;">Aunque se me han practicado los medios diagnósticos que se han estimado precisos, comprendo que pueden producirse procesos edematosos, inflamación, hematomas, dolor o laceraciones en la mucosa del labio o mejilla o en la lengua, que no dependen de la técnica empleada ni de su correcta realización.</li>
        <li style="margin-top: 0pt; margin-bottom: 0pt; line-height: normal; widows: 0; orphans: 0; font-family: Arial;">Raramente pueden producirse comunicaciones con los senos nasales o con las fosas nasales y lesionar las raíces de dientes adyacentes, que pueden requerir tratamiento posterior. Existe incluso la posibilidad de lesionar el seno maxilar y provocar una sinusitis que deba ser tratada posteriormente por el especialista competente.</li>
        <li style="margin-top: 0pt; margin-bottom: 0pt; line-height: normal; widows: 0; orphans: 0; font-family: Arial;">Con menos frecuencia e independientemente de la técnica empleada y de su correcta realización, es posible que se produzcan lesiones de tipo nervioso, por afectar a terminaciones nerviosas o nervios próximos, lo que puede generar pérdida de sensibilidad en loslabios, mentón, la lengua o la encía,según cualsea el nervio afectado. Generalmente la pérdida de sensibilidad estransitoria, aunque puede llegar a ser permanente.</li>
        <li style="margin-top: 0pt; margin-bottom: 0pt; line-height: normal; widows: 0; orphans: 0; font-family: Arial;">Todo acto quirúrgico lleva implícitas una serie de complicacionescomunesy potencialmente serias que podrían requerir tratamientos complementarios tanto médicos como quirúrgicos, y que por mi situación actual () se pueden aumentar los riesgos.</li>
        <li style="margin-top: 0pt; margin-bottom: 0pt; line-height: normal; widows: 0; orphans: 0; font-family: Arial;">Aunque la técnica se realice correctamente, existe la posibilidad de que se produzca un fracaso del tratamiento, que pueda requerir la repetición de la intervención en las mismascondiciones o con alguna cirugía previa como el injerto de hueso o la elevación delseno maxilar.</li>
        <li style="margin-top: 0pt; margin-bottom: 0pt; line-height: normal; widows: 0; orphans: 0; font-family: Arial;">Entiendo que el tratamiento no concluye con la colocación del implante, sino que es preciso realizar revisiones periódicas y seguir escrupulosamente las normas de higiene que se me han explicado.</li>
        <li style="margin-top: 0pt; margin-bottom: 0pt; line-height: normal; widows: 0; orphans: 0; font-family: Arial;">También entiendo que, una vez colocada la prótesis, pueda fracturarse con la consiguiente necesidad de sustituir algún tornillo o componente.</li>
        <li style="margin-top: 0pt; margin-bottom: 0pt; line-height: normal; widows: 0; orphans: 0; font-family: Arial;">Se me ha informado que es imprescindible un seguimiento periódico, a fin de detectar precozmente y tratar con mejor pronóstico la aparición de cualquier complicación, como la perimplantitis, una enfermedad e inflamación de los tejidos que rodean el implante.</li>
    </ul>
</ul>

			<!-- <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; font-size:8pt">
				<img src="1698864515_implante/1698864515_implante-8.png" width="702" height="27" alt="" >
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; font-size:8pt">
				<img src="1698864515_implante/1698864515_implante-9.png" width="144" height="13" alt="" >
			</p>
			<p style="margin-top:0pt; margin-bottom:0pt; line-height:normal; widows:0; orphans:0; font-size:8pt">
				<img src="1698864515_implante/1698864515_implante-10.png" width="55" height="15" alt="" >
			</p> -->
			<h3>
		DECLARACIONES Y FIRMAS
	</h3>
	
	<p>Se me ha dado tiempo suficiente para leer la información precedente que describe el tratamiento de implante. Comprendo los beneficios, riesgos e inconvenientes asociados al tratamiento. <br><br>He sido suficientemente informado y he tenido la oportunidad de hacer preguntas y discutir las preocupaciones sobre el tratamiento de implante con los productos con mi dentista que me ofrece el tratamiento.  <br><br>Por la presente doy mi consentimiento para el tratamiento de implante que ha sido prescrito por mi dentista.
			</p>
			<p>
				Autorizo a mi dentista a divulgar mis registros, incluyendo, pero no limitándose a radiografías, historial médico, fotografías, modelos de yeso o impresiones de dientes, prescripciones, diagnósticos y otros registros de tratamiento al proveedor y compañías asociadas. <br><br>Esto es con el propósito de investigar y revisar mi caso, en lo que se refiere al tratamiento de ondodoncia con sus producto(s) y/o con fines educativos/de investigación. 
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