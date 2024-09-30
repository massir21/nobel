
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
		ENDODONCIA SEDO
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
	<p>Que por el presente documento REQUIERO Y AUTORIZO a Dr. <?php echo strtoupper($datos_doctor[0]['nombre'])." ". strtoupper($datos_doctor[0]['apellidos']);?> licenciado en Medicina y Cirugía y/o Odontología con número de colegiado <?php echo  strtoupper($datos_doctor[0]['n_colegiado']) ?> y a su equipo a que realice en mi persona el tratamiento de <strong>endodoncia sedo,</strong> habiéndome explicado que es conveniente proceder, en mi situación, a recibir dicho tratamiento.</p>
	
<p style="margin: 10pt 0.5pt 6.65pt 0.5pt;  text-align: left; line-height: 108%">
Consiste en la eliminación de los tejidos que se encuentran en el interior de los conductos radiculares de un diente y la posterior limpieza, conformación y relleno de dichos conductos.
</p>


<p style="margin:10pt 0.5pt 0pt 0.5pt; text-align:left; line-height:108%">
<strong>¿Para qué sirve?</strong> <br>
</p>
<p style="margin:10pt 0.5pt 12.65pt 0.5pt;  text-align:left; line-height:108%">
Es una intervención que se realiza para intentar conservar el diente en el lugar que ocupa en la arcada dentaria, manteniendo una función adecuada.
</p>


	<p style="margin:10pt 0.5pt 0pt 0.5pt;  text-align:left; line-height:108%"><strong>¿Cómo se hace?</strong></p>
<p style="margin:10pt 0.5pt 12.65pt 0.5pt;  text-align:left; line-height:108%">Tras anestesiar localmente la zona, se realiza una cavidad para acceder al interior del diente. Luego se limpian los conductos radiculares y se rellenan con un material adecuado. Finalmente se coloca un empaste provisional que deberá ser sustituido por uno definitivo. Será necesario el uso de radiografías dentales intraorales para realizar el procedimiento. Dependiendo del estado previo del diente y de la anatomía que presente, pueden ser necesarias una o más sesiones.</p>
<p>El procedimiento requiere la aplicación de anestesia local, lo que provocará una sensación de adormecimiento del labio o de la cara que desaparecerá al cabo de unas horas. También, la administración de la anestesia podría provocar ulceración y/o hematoma del tejido, dolor y en raras ocasiones, pérdida de la sensibilidad en la boca o en la cara. Asimismo, puede provocar bajada de tensión y mareo.</p>

			<!-- <p style="margin-top:0pt; margin-left:207.2pt; margin-bottom:19.4pt; text-align:left; line-height:108%">
				<span style="height:0pt; display:block; position:absolute; z-index:0"><img src="1698763830_c.i.-endodoncia-sedo/1698763830_c.i.-endodoncia-sedo-2.png" width="84" height="3" alt="" style="margin-left:-236pt; position:absolute" ></span>
			</p> -->
			<p style="margin-top:19.4pt; margin-left:0.5pt; margin-bottom:10pt; text-align:left; line-height:108%; font-size:12pt">
				<strong>Riesgos y complicaciones:</strong>
			</p>
			<p style="margin-top:0pt; margin-left:0.5pt; margin-bottom:12.65pt">
				Cualquier intervención en un paciente conlleva riesgos. La mayor parte de las veces no se presentan complicaciones, pero a veces sí, por lo que es importante que estas se conozcan. Las más frecuentes son:
			</p>
			<ul>
    <li>
        <strong>Referidas al uso de anestésicos locales:</strong>
        <br> 
        <ul>
            <li>Interrupción de la función sensitiva (sensación de hormigueo) generalmente de forma temporal.</li>
            <li>Ulceración en la mucosa.</li>
            <li>Aparición de hematomas.</li>
            <li>Alteraciones generalmente transitorias como bloqueo de la articulación temporomandibular.</li>
            <li>Crisis vaso-vagal consistente en sudoración fría, sensación de mareo, bajada de tensión arterial e incluso lipotimia.</li>
        </ul>
    </li>
    <li style="margin-top: 10px;">
        <strong>Referidas al tratamiento en sí:</strong>
        <ul>
            <li>Durante la fase de apertura y limpieza de los conductos pueden aparecer alteraciones no detectables que imposibiliten la continuación del tratamiento de conductos tales como:</li>
            <ul style="margin-top: 10px;">
                <li>Existencia de una red compleja de curvaturas o calcificaciones imposibles de trabajar con los instrumentos disponibles actualmente.</li>
                <li>Contratiempos como la fractura de algún instrumento, que el profesional intentará subsanar, pero que en el caso de no lograrse complicarían el pronóstico del tratamiento.</li>
            </ul>
        </ul>
    </li>
</ul>




<p style="margin-top:12.65pt; margin-left:16.55pt; margin-bottom:5pt; text-align:left; line-height:108%">
    <strong>Referidas al futuro del tratamiento:</strong>
    <p style="margin-top: 0pt; margin-left: 0.5pt; margin-bottom: 12.65pt;">
    Una vez finalizado el tratamiento es necesario realizar la restauración definitiva del diente. Si dicha reconstrucción no fuese la indicada o se retrasase en exceso o el paciente tuviera hábitos de apretamiento (bruxismo) o mordiese accidentalmente alimentos muy duros sobre los dientes desvitalizados, podrían producirse fisuras verticales o fracturas radiculares que empeorarían el pronóstico restaurador, pudiendo llegar a implicar la necesidad de extraer el diente.
</p>


</p>


<p style="margin-top:12.65pt; margin-left:0.5pt; margin-bottom:12.65pt;">
    Una vez terminado el tratamiento (o entre las sesiones) el diente puede quedar sensibilizado a la presión durante un período más o menos largo pudiendo necesitar algún analgésico. En algunos casos, puede llegar a producirse la inflamación de los tejidos próximos al diente, pudiendo ser necesaria en tal caso la administración de antibióticos.
</p>
<p style="margin-top: 12.65pt; margin-left: 0.5pt; margin-bottom: 12.65pt; ">
    Podrán ser necesarias revisiones y radiografías algún tiempo después de realizar el tratamiento para comprobar su evolución y pronóstico definitivo. Puede producirse algún cambio de coloración externa en la corona o bien necesitar algún otro procedimiento como el retratamiento no quirúrgico o la cirugía periapical, si con el primer tratamiento no se consiguiesen los objetivos, ya que en el organismo humano nunca podemos tener a priori la garantía absoluta del éxito.
</p>


<p style="margin-top:8pt; margin-left:0.5pt; margin-bottom:0pt; text-align:left; line-height:108%;">
    <strong>Riesgos personalizados:</strong>
</p>
<p style="margin-top:5pt; margin-left:0.5pt; margin-bottom:11.35pt">
    Se hará siempre una historia clínica de<u> </u>las enfermedades que afecten al paciente para comprobar la idoneidad del tratamiento de conductos (endodoncia).
</p>
<p style="margin-top:11.35pt; margin-bottom:13.2pt; text-align:left; line-height:108%">
</p>

<h2 style="margin:13.2pt  0pt 11.35pt; text-align:left; line-height:108%">
    <strong>¡Información de su interés!</strong>
</h2>
<p style="margin:10pt 27.6pt 11.35pt; line-height:108%;">
Usted tiene derecho a conocer el procedimiento dental al que va a ser sometido y los riesgos y complicaciones más frecuentes que pueden ocurrir. En su actual estado clínico, los beneficios derivados de la realización de este tratamiento superan los posibles riesgos. Por este motivo se le indica la conveniencia de que le sea practicado. Si aparecieran complicaciones, el personal médico que le atiende está capacitado y dispone de medios para tratar de resolverlas. Por favor, lea atentamente este documento y consulte con su dentista las dudas que pueda tener.
</p>



<p style="margin-top:0pt; margin-left:0.5pt; margin-bottom:0pt; text-indent:-0.5pt; text-align:left; line-height:108%">
    <strong>&#xa0;</strong>
</p>
<p style="margin-top:0pt; margin-left:0.5pt; margin-bottom:0pt; text-indent:-0.5pt; text-align:left; line-height:108%">
    <strong>Alternativas al procedimiento:</strong>
</p>
<p style="margin-top:10pt; margin-left:0.5pt; margin-bottom:12.65pt; text-indent:-0.5pt">
    La extracción del diente.
</p>

			</p>
			<h1 style="margin:12.65pt 0.15pt 11.8pt 0.5pt; text-indent:-0.5pt; text-align:center; line-height:108%">
				&#xa0;
			</h1>
	<h3>		
		DECLARACIONES Y FIRMAS
	</h3>
	
	<p>Se me ha dado tiempo suficiente para leer la información precedente que describe el endodoncia. Comprendo los beneficios, riesgos e inconvenientes asociados al tratamiento. <br><br>He sido suficientemente informado y he tenido la oportunidad de hacer preguntas y discutir las preocupaciones sobre el tratamiento de endodoncia con los productos con mi dentista que me ofrece el tratamiento.  <br><br>Por la presente doy mi consentimiento para el tratamiento de endodoncia que ha sido prescrito por mi dentista.
			</p>
			
			<p>
				Autorizo a mi dentista a divulgar mis registros, incluyendo, pero no limitándose a radiografías, historial médico, fotografías, modelos de yeso o impresiones de dientes, prescripciones, diagnósticos y otros registros de tratamiento al proveedor y compañías asociadas. <br><br>Esto es con el propósito de investigar y revisar mi caso, en lo que se refiere al tratamiento de endodoncia con sus producto(s) y/o con fines educativos/de investigación. 
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