<div class="card card-flush">
	<div class="card-body pt-6">
		<?php if ($accion == "nuevo") {
			$cliente_id = (isset($cliente)) ? $cliente[0]['id_cliente'] : '';
			$actionform = base_url() . 'clientes/crear_nota_cobrar/' . $cliente_id;
		} else {
			$actionform = base_url() . 'clientes/gestion/actualizar_nota_cobrar/' . $registros[0]['id_nota_cobrar'];
		} ?>

        <form id="form" action="<?php echo $actionform; ?>" role="form" method="post" name="form">
            <div class="row mb-5 border-bottom">
				<?php if ($accion == "editar") { ?>
					<div class="col-md-6 col-lg-3 mb-5">
						<label class="form-label">Fecha Creación Nota</label>
						<div class="input-icon">
							<?php if (isset($registros)) {
								echo $registros[0]['fecha_creacion_ddmmaaaa'];
							} ?>
						</div>
					</div>
					<div class="col-md-6 col-lg-3 mb-5">
						<label class="form-label">Recepcionista Creador</label>
						<div class="input-icon">
							<?php if (isset($registros)) {
								echo $registros[0]['usuario_creacion'];
							} ?>
						</div>
					</div>
				<?php } ?>
                <div class="col-md-6 col-lg-3 mb-5">
					<label class="form-label">Estado</label>
					<select name="estado" class="form-control form-control-solid" required>
						<option value="Pendiente" <?= (isset($registros) && $registros[0]['estado'] == "Pendiente") ? "selected" : '' ?>>
							Pendiente
						</option>
						<?php if ($accion == "editar") { ?>
							<option value="Finalizada" <?= (isset($registros) && $registros[0]['estado'] == "Finalizada") ? "selected" : '' ?>>
								Finalizada
							</option>
						<?php } ?>
					</select>
				</div>

                <div class="col-md-6 col-lg-3 mb-5">
                    <label class="form-label">Vincular a Carnet</label> 
                    <select name="id_carnet" id="carnets_codigos_cobro" class="form-select form-select-solid" data-placeholder="Elegir ...">
                        <?php if (isset($registros) && $registros[0]['id_carnet'] > 0) { ?>
                            <option value="<?php echo $registros[0]['id_carnet'] ?>" selected><?php echo $registros[0]['carnet']; ?></option>
                        <?php } ?>
                    </select>
                    <script type="text/javascript">
                            $("#carnets_codigos_cobro").select2({
                                language: "es",
                                minimumInputLength: 4,
                                ajax: {
                                    delay: 0,
                                    url: function(params) {
                                        return '<?php echo RUTA_WWW; ?>/carnets/json_todos/' + params.term;
                                    },
                                    dataType: 'json',
                                    processResults: function(data) {
                                        return {
                                            results: data
                                        };
                                    }
                                }
                            });
                    </script>
                </div>
                <div class="col-md-12 mb-5">
                    <div class="row">
                        <?php if ($accion=="nuevo") { ?>
                            <div class="col-md-8">
                                <textarea name="nota" class="form-control form-control-solid" style="height: 200px;" required><?= (isset($registros)) ? $registros[0]['nota'] : '' ?></textarea>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Firma del cliente</label>          
                                <div class="firma" style="position: relative; width: 300px; height: 150px; -moz-user-select: none; -webkit-user-select: none; -ms-user-select: none; user-select: none;">
                                    <canvas id="signature-pad" class="signature-pad" style="position: absolute; left: 0; top: 0; width: 300px; height: 150px; background-color: white;"></canvas>
                                    <input type="hidden" name="firma_img" id="firma_img" value="" />
                                </div>
                                <span class="label label-sm label-danger">
                                    <a href="#" id="clear" onclick="signaturePad.clear();return false;" style="color: #fff; font-weight: bold;">Borrar Firma</a>
                                </span>
                            </div>
                        <?php } else { ?>
                            <?php if (isset($registros)) {
                                if ($registros[0]['firma_img']!="") { ?>
                                    <div class="col-md-8">
                                        <textarea name="nota" class="form-control form-control-solid" style="height: 200px;" required><?php if (isset($registros)) { echo $registros[0]['nota']; } ?></textarea>
                                    </div>
                                    <div class="col-md-4" style="text-align: center;">                      
                                        <label class="form-label">Firma del cliente guardada</label>
                                        <div style="margin: 10px;">
                                            <img src="<?php echo base_url();?>recursos/firmas/<?php echo $registros[0]['firma_img']; ?>" style="width: 300px; height: 150px;" />
                                        </div>
                                    </div>
                                <?php } else {   ?>
                                    <div class="col-md-12">
                                        <textarea name="nota" class="form-control form-control-solid" style="height: 200px;" required><?php if (isset($registros)) { echo $registros[0]['nota']; } ?></textarea>
                                    </div>
                                <?php }
                            } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
			<div class="row mb-5 border-bottom">
				<div class="col-md-12" style="text-align: center;">
					<input class="btn btn-primary text-inverse-primary margin-top-20" type="submit" value="GUARDAR" />
				</div>
			</div>
        </form>
    </div>
</div>
<!-- END CONTENT BODY -->
<div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
   <div class="modal-dialog">      
        <div class="modal-body" style="padding: 10px; background: #fff; text-align: center;">
            <h4><b>Guardando Datos de la Nota de Cobro y Firma. Espere unos segundos...</b></h4>
        </div>
   </div>
</div>

<script>
function guardar(){
    valor=document.getElementById('carnets_codigos_cobro').value;
    alert ('Valor del carnet es: '+valor);
    console.log('Valor '+valor);
    return false;
}
</script>

<?php if ($accion=="nuevo") { ?>
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
<?php } ?>