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
	PULPAR EN DIENTE INMADURO
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
	<p>Que por el presente documento REQUIERO Y AUTORIZO a Dr. <?php echo strtoupper($datos_doctor[0]['nombre'])." ". strtoupper($datos_doctor[0]['apellidos']);?> licenciado en Medicina y Cirugía y/o Odontología con número de colegiado <?php echo  strtoupper($datos_doctor[0]['n_colegiado']) ?> y a su equipo a que realice en mi persona el tratamiento de <strong>pulpar inmaduro,</strong> habiéndome explicado que es conveniente proceder, en mi situación, a recibir dicho tratamiento.</p>
	
			</p>
			<p style="margin-top:10.3pt; margin-bottom:10.75pt; line-height:108%">
				Para satisfacción de los derechos del paciente como instrumento favorecedor del correcto uso de los Procedimientos Diagnósticos y Terapéuticos , y en cumplimiento dela Ley General de Sanidad y la ley 41/2002, se le presenta para su firma el siguiente documento: 
			</p>
			<p style="margin-top:0pt; margin-bottom:10.75pt; text-align:justify; line-height:108%">
			
			<p style="margin-top:0.55pt;margin-bottom:10.3pt; text-align:justify">
				El /la Dr./Dra <?php echo strtoupper($datos_doctor[0]['nombre'])." ". strtoupper($datos_doctor[0]['apellidos']); ?>
			</p>
			<p style="margin-top:10.3pt; margin-bottom:10.3pt; ">
				Me ha explicado y he sido debidamente informado/a y en consecuencia AUTORIZO al mismo para que me sea realizado el procedimiento denominado TRATAMIENTO PULPAR EN DIENTE PERMANENTE INMADURO. 
			</p>
			<p style="margin-top:10.3pt;margin-bottom:12.45pt;">
				El objetivo del tratamiento es eliminar de forma total el tejido afectado de la pieza a tratar y permitir el correcto desarrollo de la raíz que todavía no ha acabado de formarse. Para ello puede ser necesario realizar uno de los siguientes procedimientos:&#xa0; 
			</p>
			<ul style="margin-left: 71.65pt; list-style-type: disc;">
    <li style="margin-top: 12.45pt; margin-bottom: 1.4pt;">Recubrimiento pulpar indirecto.</li>
    <li style="margin-top: 1.4pt; margin-bottom: 1.5pt;">Recubrimiento pulpar directo</li>
    <li style="margin-top: 1.5pt; margin-bottom: 1.45pt;">Pulpotomía</li>
    <li style="margin-top: 1.45pt; margin-bottom: 1.5pt;">Revascularización</li>
    <li style="margin-top: 1.5pt; margin-bottom: 9.2pt;">Apicoformación</li>
</ul>

			<p style="margin-top:9.2pt; margin-left:0.5pt; margin-bottom:10.3pt; ">
				Si no se completara el desarrollo radicular con uno de estos tratamientos inicialmente, podría ser necesario realizar un tratamiento de conductos convencional (endodoncia) o un tratamiento apical quirúrgico.&#xa0; 
			</p>
			<p style="margin-top:10.3pt; margin-left:0.5pt; margin-bottom:10.3pt;">
				La finalidad es conservar funcionalmente un diente afectado por caries o traumatismo que puede llegar a afectar la pulpa dental. Así permitimos el correcto desarrollo radicular y mantener de esta forma el diente en la boca del niño/a. Se puede devolver la función al diente al poder ser restaurado de forma directa o indirecta después de realizar los tratamientos anteriores. 
			</p>
			<p style="margin-top:10.3pt; margin-left:0.5pt; margin-bottom:10.3pt;">
				Asimismo, me han sido expuestos los posibles riesgos o complicaciones del tratamiento, permitiéndome realizar todas las observaciones y preguntas para aclarar mis dudas. 
			</p>
			<ul style="margin-left: 0.5pt; list-style-type: disc; margin-top: 10.3pt; margin-bottom: 10.3pt;">
    <li style="text-indent: -0.5pt; margin-bottom: 10.3pt;">Riesgos propios de la inyección de anestésicos locales: posible hipersensibilidad al anestésico difícilmente previsible, alergia al anestésico, anestesias prolongadas, daños locales por la punción o mordisqueos post tratamiento (ulceración y/o hematoma).</li>
    <li style="text-indent: -0.5pt; margin-bottom: 10.3pt;">Riesgo de ingesta o incluso aspiración de pequeños restos de material de obturación sobrantes.</li>
    <li style="text-indent: -0.5pt; margin-bottom: 10.3pt;">Riesgo de pequeños daños en los tejidos blandos adyacentes a la zona de trabajo (encía, mucosa yugal, lengua) debido al uso del instrumental de trabajo, instrumentos separadores o clamps para sujetar el dique de goma. Este riesgo será mayor en niños poco colaboradores. Aún así suelen ser leves y se resuelven en varios días.</li>
    <li style="text-indent: -0.5pt;">Riesgo de que los tratamientos que afecten a la pulpa fracasen debido a la presencia de infección en los conductos radiculares o en el periápice, o debido a la poca colaboración del paciente.</li>
</ul>


			<?php if ($datos_firma[0]['edad']<18){ ?>
			<p style="margin-top:10.3pt; margin-left:0.5pt; margin-bottom:10.3pt; text-align:justify">
                <?php if ($descripcionTratamiento != null){ ?>
                    Así mismo, el niño/a <?php echo strtoupper($datos_firma[0]['cliente']); ?> sus especiales condiciones personales
                    (<?php if (isset($descripcionTratamiento)){ echo  $descripcionTratamiento; } else { echo $datos_firma[0]['descripcionTratamiento']; } ?>)
                <?php } ?>

                <?php if ($descripcionRiesgos != null){ ?>
                    , puede presentar riesgos añadidos consistentes en: </span><br/>
                    <?php if (isset($descripcionRiesgos)){ echo  $descripcionRiesgos; } else { echo $datos_firma[0]['descripcionRiesgos']; } ?>
                <?php } ?>
            </p>
			
			<p style="margin-top:10.75pt; margin-left:0.5pt; margin-bottom:10.3pt; text-align:justify">
				El paciente&#xa0; y sus padres o tutores también han sido informados de que los trabajos de odontopediatría pueden sufrir deterioros ( especialmente debido a la mala higiene). Para prevenir estas circunstancias el paciente debe acudir a las revisiones periódicas indicadas por el odontopediatra, y siempre que tenga cualquier duda sobre el tratamiento realizado 
			</p>
			<?php }?>
			<p style="margin-top:10.3pt; margin-left:0.5pt; margin-bottom:10.3pt;text-align:justify">
				Por todo ello,
			</p>
			<p style="margin-top:10.3pt; margin-left:0.5pt; margin-bottom:0.4pt; text-align:justify">
				Yo D/Dª <?php echo strtoupper($datos_firma[0]['cliente']); ?> con D.N.I. <?php echo strtoupper($datos_firma[0]['dni']); ?> como paciente <?php if ($datos_firma[0]['edad']<18){ ?> y D/Dña. <?php echo strtoupper($datos_firma[0]['nombre_tutor']);  ?> como padre, madre o tutor <?php } ?> &#xa0;&#xa0; he sido informado/a por el odontólogo/a, comprendo el alcance y el significado de dicha información y consiento en tratar al paciente mediante los procedimientos clínicos explicados. También he sido informado de la posibilidad de revocar este consentimiento por escrito en cualquier momento. 
			</p>
			
			<h3>
    DECLARACIONES Y FIRMAS
</h3>

<p>Se me ha dado tiempo suficiente para leer la información precedente que describe el tratamiento de pulpar en diente inmaduro. Comprendo los beneficios, riesgos e inconvenientes asociados al tratamiento. <br><br>He sido suficientemente informado y he tenido la oportunidad de hacer preguntas y discutir las preocupaciones sobre el tratamiento de pulpar en diente inmaduro con mi dentista que me ofrece el tratamiento. Por la presente doy mi consentimiento para el tratamiento de pulpar en diente inmaduro que ha sido prescrito por mi dentista.
</p>
<p>
    Autorizo a mi dentista a divulgar mis registros, incluyendo, pero no limitándose a radiografías, historial médico, fotografías, modelos de yeso o impresiones de dientes, prescripciones, diagnósticos y otros registros de tratamiento al proveedor y compañías asociadas. <br><br>Esto es con el propósito de investigar y revisar mi caso, en lo que se refiere al tratamiento de pulpar en diente inmaduro con sus producto(s) y/o con fines educativos/de investigación. 
</p>
<p>
    Por la presente consiento en la divulgación de lo anterior. No buscaré, ni nadie en mi nombre, daños o remedios legales, equitativos o monetarios por dicha divulgación. Una fotocopia de este consentimiento se considerará tan efectiva y válida como un original. <br><br>
</p>
<p>
    He leído, entendido y estoy de acuerdo con los términos establecidos en este Consentimiento, tal y como se indica con mi firma a continuación. 
</p>

<p>Declaro que el/la facultativo/a, Dr/Dra
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
    En <b>Madrid</b> a día   <b> <?php echo date('d/m/Y'); ?><br><br>
    </b>
</p>

<div>
    <p>Fdo.: El paciente o (representante legal)</p><br><br>

    <?php if ($datos_firma[0]['firma'] != "ONLINE" and $datos_firma[0]['firma'] != "") {
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
