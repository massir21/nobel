<div class="page-content">
    <!-- BEGIN PAGE HEADER-->                    
    <!-- BEGIN PAGE TITLE-->
    <h3 class="page-title">
        <b>Ley de protección de datos</b>
    </h3>
    <hr>
    <!-- END PAGE TITLE-->
    <?php if (isset($gracias)) { ?>
    <div class="alert alert-success" style="display: block; text-align: center;">       
        TODOS LOS DATOS DE LA FIRMA FUERON GUARDADOS CORRECTAMENTE
    </div>  
    <?php } else { ?>
        <?php if ($existe) { ?>
        <div class="alert alert-warning" style="display: block; text-align: center;">
            LOS DATOS SE LA FIRMA YA HAN SIDO PROCESADOS PARA <?php echo $cliente[0]['nombre']." ".$cliente[0]['apellidos']; ?>
        </div>  
        <?php } else { ?>
            <?php if ($cliente != 0) { ?>
            <div>        
                <form id="form" action="<?php echo base_url();?>clientes/guardar_proteccion_datos" role="form" method="post" name="form" onsubmit="return EsOk();">
                <p>Consentimiento Clientes</p>
                <p style="font-weight: bold;">
                    D./Dª <?php echo $cliente[0]['nombre']." ".$cliente[0]['apellidos']; ?>
                    <?php if ($cliente[0]['dni'] != "") { ?>
                        con N.I.F. <?php echo $cliente[0]['dni']; ?>
                        <input id="dni" name="dni" type="hidden" value="<?php echo $cliente[0]['dni']; ?>">
                    <?php } else { ?>
                        con N.I.F. <input id="dni" name="dni" type="text" maxlength="9" style="text-transform:uppercase">
                    <?php } ?>
                </p>
                <p>Por la presente, otorga su consentimiento expreso para que los datos
                personales sean tratados por la TEMPLO DEL MASAJE, S.L. y sean incorporados al
                fichero denominado CLIENTES del que es responsable la citada empresa.</p>
                <p>Presta su consentimiento para que los datos sean tratados para cumplir las finalidades de:</p>
                <p>
                    <input type="checkbox" checked disabled="disabled" /> Gestión de su historial<br>
                    <input type="checkbox" name="recibir_informacion" value="1" /> Deseo recibir información por medios electrónicos como ofertas y/o promociones
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
                <div class="firma" style="position: relative; width: 300px; height: 150px; -moz-user-select: none; -webkit-user-select: none; -ms-user-select: none; user-select: none;">
                    <canvas id="signature-pad" class="signature-pad" style="border: 1px solid #ddd; position: absolute; left: 0; top: 0; width: 300px; height: 150px; background-color: white;"></canvas>
                    <input type="hidden" name="firma_img" id="firma_img" value="" />
                </div>
                <span class="label label-sm label-danger">
                    <a href="#" id="clear" onclick="signaturePad.clear();return false;" style="color: #fff; font-weight: bold;">Borrar Firma</a>
                </span>
                <div style="text-align: center;">            
                    <input class="btn btn-primary text-inverse-primary margin-top-20" type="submit" value="GUARDAR" />
                </div>
                <input type="hidden" name="id_cliente" value="<?php if (isset($cliente)) { echo $cliente[0]['id_cliente']; } ?>" />
                </form>
            </div>
            <?php } else { ?>
            <div>
                No se ha encontrado al cliente.
            </div>
            <?php } ?>
        <!-- END existe -->
        <?php } ?>
    <!-- END GRACIAS -->
    <?php } ?>
</div>
<!-- END CONTENT BODY -->
<div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
   <div class="modal-dialog">      
        <div class="modal-body" style="padding: 10px; background: #fff; text-align: center;">
            <h4><b>Guardando los datos. Espere unos segundos...</b></h4>
        </div>
   </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<script>
var canvas = document.getElementById('signature-pad');
// Adjust canvas coordinate space taking into account pixel ratio,
// to make it look crisp on mobile devices.
// This also causes canvas to be cleared.
function resizeCanvas() {
    // When zoomed out to less than 100%, for some very strange reason,
    // some browsers report devicePixelRatio as less than 1
    // and only part of the canvas is cleared then.
    var ratio =  Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
}
//window.onresize = resizeCanvas;
//resizeCanvas();
var signaturePad = new SignaturePad(canvas, {
  backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
});
function EsOk() {
    if (!signaturePad.isEmpty()) {
        var data = signaturePad.toDataURL('image/png');
        document.getElementById('firma_img').value = data;
        $(document).ready(function()
        {
            $("#mostrarmodal").modal("show");
        });
    }
    return true;
}
</script>
</script>