<?php
if ( ! isset($die_editable) ) { $die_editable = 1; }
?>
<div class="modal fade" id="stack-dientes" tabindex="-1" aria-labelledby="stack-dientesLabel" aria-hidden="true" data-focus-on="input:first">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">SELECCIONAR DIENTES</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<?php
					function mostrar_un_diente_2($die, $orientac, $editable) {
						echo "<div style='width: 36px' class='d-inline-block text-center p-2 rounded-2' id='div-die-{$die}'>\n";
						if ( $editable ) {
							echo "<a href='javascript:void(0);' onclick=\"seleccionar_diente2('{$die}');\">";
						}
						if ( $orientac == 'supe' ) {
							echo "<img src='/assets_v5/media/dientes/raiz-diente-{$die}.png' class='img-fluid' vspace='2' style='max-height: 40px;'>\n";
							echo "<img src='/assets_v5/media/dientes/diente-{$die}.png' class='img-fluid' style='max-height: 33px;'>\n";
							echo '<span class="text-muted">'.$die.'</span>';
						}
						
						if ( $orientac == 'bajo' ) {
							echo '<span class="text-muted">'.$die.'</span>';
							echo "<img src='/assets_v5/media/dientes/diente-{$die}.png' class='img-fluid' style='max-height: 33px;'>\n";
							echo "<img src='/assets_v5/media/dientes/raiz-diente-{$die}.png' class='img-fluid' vspace='2' style='max-height: 40px;'>\n";
						}
						if ( $editable ) {
							echo "</a>";
						}
						echo "<input type='hidden' name='die-val-{$die}' id='die-val-{$die}' data-diente='{$die}' value='0' class='diente'>\n";
						echo "</div>\n";
					}
					function mostrar_dientes_2($desde, $hasta, $sentido, $orientac, $editable) {
						if ( $sentido == -1 ) {
							for ( $i = $desde; $i >= $hasta; --$i ) {
								mostrar_un_diente_2($i, $orientac, $editable);
							}
						} else {
							for ( $i = $desde; $i <= $hasta; ++$i ) {
								mostrar_un_diente_2($i, $orientac, $editable);
							}
						}
					}
					?>
					<div class="col-md-6 px-4 mt-2 text-end"><?php echo mostrar_dientes_2(18, 11, -1, 'supe', $die_editable); ?></div>
					<div class="col-md-6 px-4 mt-2 text-left"><?php echo mostrar_dientes_2(21, 28, 1, 'supe', $die_editable); ?></div>

					<div class="col-md-6 px-4 mt-2 text-end"><?php echo mostrar_dientes_2(55, 51, -1, 'supe', $die_editable); ?></div>
					<div class="col-md-6 px-4 mt-2 text-left"><?php echo mostrar_dientes_2(61, 65, 1, 'supe', $die_editable); ?></div>

					<div class="col-md-6 px-4 mt-2 text-end"><?php echo mostrar_dientes_2(85, 81, -1, 'bajo', $die_editable); ?></div>
					<div class="col-md-6 px-4 mt-2 text-left"><?php echo mostrar_dientes_2(71, 75, 1, 'bajo', $die_editable); ?></div>

					<div class="col-md-6 px-4 mt-2 text-end"><?php echo mostrar_dientes_2(48, 41, -1, 'bajo', $die_editable); ?></div>
					<div class="col-md-6 px-4 mt-2 text-left"><?php echo mostrar_dientes_2(31, 38, 1, 'bajo', $die_editable); ?></div>

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary text-inverse-secondary" data-bs-dismiss="modal">Cerrar</button>
				<?php if ( $die_editable ) { ?>
					<button type="button" class="btn btn-primary text-inverse-primary" onclick="javascript:guardar_dientes2();">Guardar</button>
				<?php } ?>
			</div>
			<input type="hidden" name="servicio_campo_id" id="id_presupuesto_item_diente" value="" />
			<input type="hidden" name="servicio_campo_id" id="dientes_seleccion" value="" />
		</div>
	</div>
</div>

<script>
	function habilitaOdontograma2(id) {
		var button = $('[data-dientes="'+id+'"]'); 
		deseleccionar_diente2_todos2();
		$('#id_presupuesto_item_diente').val(id);
		txSeleccion = button.text();
		if ( txSeleccion != '-' ) {
			var tmDiente = txSeleccion.split(',');
			$.each(tmDiente, function (ind, elem) {
				$('#die-val-'+elem).val(1);
				$("div#div-die-"+elem).css('background-color','#777777');
			});
			$('#dientes_seleccion').val(txSeleccion);
		}
		$('#stack-dientes').modal('show');
	}
	function seleccionar_diente2(id) {
		deseleccionar_diente2_todos2()
		if ( $('#die-val-'+id).val() == 1 ) {
			$('#die-val-'+id).val(0);
			$("div#div-die-"+id).css('background-color','white');
		} else {
			$('#die-val-'+id).val(1);
			$("div#div-die-"+id).css('background-color','#777777');
		}
		tomar_id_dientes2();
	}
	function deseleccionar_diente2_todos2() {
		$.each($(".diente"), function (index, value) {
			tmId = $(value).data('diente');
			$('#die-val-'+tmId).val(0);
			$("div#div-die-"+tmId).css('background-color','white');
		});
		$('#dientes_seleccion').val('');
	}
	function tomar_id_dientes2() {
		var txtDientes = '';
		var txtSepara = '';
		var iconta = 0;
		$.each($(".diente"), function (index, value) {
			if ( $(value).val() == 1 ){
				txtDientes += txtSepara + $(value).data('diente');
				txtSepara = ','
				++iconta;
			}
		});
		$('#dientes_seleccion').val(txtDientes);
	}
	function guardar_dientes2() {
		tomar_id_dientes2()
		filaId = $('#id_presupuesto_item_diente').val();
		var textbutton = $('#dientes_seleccion').val();
		// aqui el ajax
		var formData = new FormData();
		formData.append("id_presupuesto_item", filaId);
		formData.append("dientes", textbutton);
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			method: 'post',
			url: '<?php echo base_url() ?>Presupuestos/updateDientesItem',
			data: formData,
			processData: false,
			contentType: false,
			success: function(resp) {
				if (resp == false) {
					Swal.fire({
						title: 'Error',
						type: 'error',
						willClose: function() {
						},
					});
				}else{
					Swal.fire({
						title: 'Guardado',
						type: 'success',
						willClose: function() {
							$('[data-dientes="'+filaId+'"]').text(textbutton); 
							$('#stack-dientes').modal('hide');
						},
					});
				}
			},
			error: function() {
				Swal.fire({
					type: 'error',
					title: 'Oops...',
					text: 'Ha ocurrido un error'
				})
			}
		})
		//respuest positiva
		
		
	}
</script>
