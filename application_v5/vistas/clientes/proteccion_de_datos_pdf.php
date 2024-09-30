<p>Ley de protección de datos.</p>
<p style="font-weight: bold;">
    D./Dª <?php echo $datos_firma[0]['cliente']; ?>
    <?php if ($datos_firma[0]['dni'] != "") { ?>
    con N.I.F. <?php echo $datos_firma[0]['dni']; ?>
    <?php } ?>
</p>
<p>Por la presente, otorga su consentimiento expreso para que los datos
personales sean tratados por la TEMPLO DEL MASAJE, S.L. y sean incorporados al
fichero denominado CLIENTES del que es responsable la citada empresa.</p>
<p>Presta su consentimiento para que los datos sean tratados para cumplir las finalidades de:</p>
<p>
    [SI] - Gestión de su historial<br>
    <?php if ($datos_firma[0]['recibir_informacion'] == 1) { ?>
    [SI]
    <?php } else { ?>
    [NO]
    <?php } ?>    
    - Deseo recibir información por medios electrónicos como ofertas y/o promociones    
</p>
<p>Los datos personales se conservarán mientras exista una relación contractual entre el Responsable
y el interesado. La base legal para el tratamiento de sus datos es el consentimiento expreso, otorgado
mediante la firma de este documento.</p>
<p>Se le informa, asimismo, de que no se cederán sus datos a terceros, salvo a organismos públicos
para el cumplimiento de obligaciones legales para la consecución de los fines anteriormente establecidos.
Cuando ya no sea necesario para tal fin, se suprimirán con medidas de seguridad adecuadas para garantizar
la seudonimización de los datos o la destrucción total de los mismos. La empresa se compromete en todo
caso al tratamiento de los datos personales de acuerdo con la Ley y normativa vigente al respecto, así
como establecer los pertinentes compromisos de confidencialidad con terceros a los que ceda o permita el
acceso a estos datos personales.</p>
<p>Queda enterado de que de acuerdo con el Capítulo III del Reglamento (UE) 2016/679, tiene el derecho de acceso,
rectificación o supresión, limitación del tratamiento, oposición y portabilidad de los datos, sin perjuicio y con
independencia de las consecuencias que el ejercicio de estos derechos pudieran ocasionar a la relación contractual,
así como de las obligaciones legales derivadas de dicha relación.</p>
<p>En caso de que no desee que sus datos personales sean tratados con los fines señalados, puede ejercitar el
derecho de oposición, junto con el de acceso, rectificación o supresión y portabilidad mediante comunicación dirigida a
TEMPLO DEL MASAJE, S.L.: Avda. Betanzos 64 28034, Madrid o a través del mail: info@templodelmasaje.com</p>
<p>El Interesado consiente el tratamiento de sus datos en los términos expuestos:</p>
<p>Firma:</p>
<?php if ($datos_firma[0]['firma'] != "ONLINE") { 
    $fuente=RUTA_SERVIDOR . "/recursos/firmas_lopd/" . $datos_firma[0]['firma'];
    $imagenBase64 = "data:image/png;base64," . base64_encode(file_get_contents($fuente));
    ?>
    <img src="<?php echo $imagenBase64 ?>" style="height: 35mm;" />
<?php } else { ?>
    ONLINE
<?php } ?>