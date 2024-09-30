<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
	<h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">Pago de servicios</h1>

	<div class="card card-flush m-5">
		<div class="card-body p-5">
			<h3 class="text-center">CONCEPTOS PENDIENTES DE COBRO <br><?php echo $cliente[0]['nombre'] . ' ' . $cliente[0]['apellidos']; ?></h3>
			<div class="alert alert-warning p-5 text-center">Los conceptos no marcados, quedarán como No Pagados</div>
			<form id="form_pagoeuros" action="<?php echo base_url(); ?>dietario/pagosaldo/guardar/<?php echo $cliente[0]['id_cliente']; ?>" role="form" method="post" name="form_pagoeuros">
				<div class="table-responsive p-4 mb-5 border">
					<table class="table align-middle table-striped table-row-dashed fs-6 gy-5">
						<thead class="">
							<tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
								<th style="display: none;">ID</th>
								<th></th>
								<th>Serv/Prod</th>
								<th>Descuento €</th>
								<th>Descuento %</th>
								<th>Euros</th>
								<th>Estado</th>
							</tr>
						</thead>
						<tbody class="text-gray-700 fw-semibold">
							<?php $total_importe = 0;
							if (isset($registros)) {
								if ($registros != 0) {
									$i = 0;
									foreach ($registros as $key => $row) {
										
										if ($row['id_tipo'] != "") {
											if ($row['id_tipo'] == 99) {
												$row['importe_euros'] = $row['pvp_carnet'];
											}
											if ($row['id_tipo'] < 99) {
												if ($row['recarga'] == 0) {
													$row['importe_euros'] = $row['pvp_carnet'];
												}
											}
										}							?>
										<tr>
											<td style="display: none;">
												<?php echo $row['id_dietario']; ?>

                                            </td>
											<td>
												<div class="form-check form-check-solid form-switch">
													<input class="form-check-input" type="checkbox" name="marcados[]" value="<?php echo $row['id_dietario']; ?>" onclick="Marcado(this,<?php echo $i; ?>);" />
												</div>
												<input type="hidden" name="servicios[]" value="<?php echo $row['id_servicio']; ?>" />
											</td>

											<td>
												<span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">
													<?= ($row['servicio'] != "") ? $row['servicio'] : '' ?>
													<?= ($row['producto'] != "") ? $row['producto'] : '' ?>
													<?= ($row['carnet'] != "") ? $row['carnet'] : '' ?>
													<?= ($row['recarga'] == 1) ? " (Recarga)" : '' ?></span>
												<span class="text-muted fw-semibold text-muted d-block fs-7"></span>
												<?= $row['fecha_hora_concepto_ddmmaaaa_abrev2'] ?></span>
												<?php if ($row['servicio'] != "") {
													$concepto = $row['servicio'];
												} ?>
												<?php if ($row['producto'] != "") {
													$concepto = $row['producto'];
												} ?>
												<?php if ($row['carnet'] != "") {
													$concepto = $row['carnet'];
												} ?>
											</td>
											</td>
											<td>
                                                <?php
                                                $max=$row['importe_euros'];
                                                if($row['maxdescuento']<100){
                                                    $max=$row['importe_euros']*($row['maxdescuento']/100);
                                                }
                                                ?>
												<input type="number" step="0.01" min="0" max="<?php echo $max; ?>" value="0" name="descuento_euros[]" class="form-control form-control-solid" onchange="DescuentoEuros(this,'<?php echo $row['importe_euros']; ?>',<?php echo $i; ?>);" onkeyup="DescuentoEuros(this,'<?php echo $row['importe_euros']; ?>',<?php echo $i; ?>);" required disabled <?php if($row['id_presupuesto']!=0){ echo "readonly";}?>/>
											</td>
											<td>
												<input type="number" step="0.01" min="0" max="<?php echo $row['maxdescuento'];?>" value="0" name="descuento_porcentaje[]" class="form-control form-control-solid" onchange="DescuentoPorcentaje(this,'<?php echo $row['importe_euros']; ?>',<?php echo $i; ?>);" onkeyup="DescuentoPorcentaje(this,'<?php echo $row['importe_euros']; ?>',<?php echo $i; ?>);" required disabled <?php if($row['id_presupuesto']!=0){ echo "readonly";}?>/>
											</td>
											<td class="text-end">
												<input type="number" step="0.01" min="0" value="<?php echo round($row['importe_euros'], 2); ?>" name="importe_euros[]" class="form-control form-control-solid" disabled <?php if($row['id_presupuesto']!=0){ echo "readonly";}?> />
											</td>
											<td class="d-flex align-items-center">
												<?= $row['estado'] ?>
												<?php if ($this->session->userdata('id_perfil') == 0) { ?>
                                                    <button type="button" class="btn btn-sm btn-icon btn-danger ms-3" onclick="Eliminar('<?php echo $row['id_dietario']; ?>','<?php echo addslashes($concepto); ?>');" data-bs-toggle="tooltip" title="Borrar"><i class="fa-solid fa-trash"></i></button>
                                                <?php } ?>
											</td>
											<input type="hidden" step="0.01" min="0" max="<?php echo $row['importe_euros']; ?>" value="<?php echo $row['importe_euros']; ?>" name="usa_saldo[]" class="form-control form-control-solid" onchange="UsaSaldo(this,'<?php echo $row['importe_euros']; ?>',<?php echo $i; ?>);" onkeyup="UsaSaldo(this,'<?php echo $row['importe_euros']; ?>',<?php echo $i; ?>);" required disabled style="display:none;" />
										</tr>
                                        <tr style="height: 0px"></tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td style="padding-top:0px!important" colspan="3">
                                                Comentarios de cobro:
                                                <textarea name="notas_pago_descuento[]" disabled
                                                          class="form-control form-control-solid"></textarea>

                                            </td>
                                        </tr>
							<?php $i++;
									}
								}
							} ?>
						</tbody>
					</table>
				</div>

				<div class="row border text-center p-4 mb-5">
					<div class="col-4 fs-2 fw-bolder">
						SALDO DISPONIBLE<br>
						<input name="saldo_disponible" typ="number" value="<?php echo $saldo_cliente; ?>" class="text-end border-0 bg-white fw-bold w-80px" disabled step="0.1"/>€


					</div>

					<div class="col-4 fs-2 fw-bolder text-primary">
						TOTAL MARCADO<br>
						<input name="total_importes_marcados" typ="number" value="0" class="text-primary text-end border-0 bg-white fw-bold w-80px" disabled />€
					</div>

					<div class="col-4 fs-2 fw-bolder text-danger">
						<span id="faltan">FALTAN<br>
							<input id="falta_importe" name="falta_importe" typ="number" value="0" class="text-danger text-end border-0 bg-white fw-bold w-80px" disabled />€
					</div>
				</div>

				<div class="row">
					<div class="col-md-12 text-center">
						<button class="btn btn-sm btn-secondary text-inverse-secondary m-2" id="boton_cancelar" type="button" onclick="Cerrar();">Cancelar</button>

						<button class="btn btn-sm btn-outline btn-outline-info m-2" id="boton_pago_cuenta" type="button" onclick="PagoCuenta();" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Realizar un pago a cuenta por el cliente">Pago a cuenta</button>
						<button class="btn btn-sm btn-primary text-inverse-primary m-2" id="boton_cobrar" style="display:none" type="button" onclick="Cobrar();">Cobrar</button>
					</div>
				</div>
				<input type="hidden" name="tipo_pago" value="#saldo_cuenta" />
			</form>
		</div>
	</div>

	<script>
		<?php //if($this->session->userdata('id_usaurio') == 497 || $this->session->userdata('id_perfil') == 0){ ?>
			function Cobrar() {
				var marcados = $('input[name="marcados[]"]');
				var m = 0;
				marcados.each(function() {
					if ($(this).prop('checked')) {
						m = 1;
					}
				});
				if (m == 0) {
					Swal.fire("DEBE DE MARCAR AL MENOS UN CONCEPTO PARA COBRAR");
					return false;
				}

                notas_pago_descuento = document.getElementsByName("notas_pago_descuento[]");
                for(var i=0;i<notas_pago_descuento.length;i++){
                    if(notas_pago_descuento[i].required && notas_pago_descuento[i].value.length<5){
                        Swal.fire("DEBE INTRODUCIR LA RAZON DEL DESCUENTO EN EL COMENTARIO (min. 5 caracteres)");
                        return false;
                    }
                }

                if ($('input[name="falta_importe"]').val() == 0) {

					Swal.fire({
						title: 'Cobrar la cita',
						html: `¿Desesas cobrar la cita? Los conceptos indicados serán marcados como cobrados.`,
						showCancelButton: true,
						confirmButtonText: 'Si, cobrar',
						showLoaderOnConfirm: true
					}).then((result) => {
						if (result.isConfirmed) {
							$('form[name="form_pagoeuros"]').submit();
							return true;
						}
					})
					
				} else {
					Swal.fire("LOS IMPORTES INDICADOS NO CUADRAN CON EL TOTAL A PAGAR");
					return false;
				}
			}
		


		function SumaTotal(idx) {
			var marcados = $('input[name="marcados[]"]');
			var importeEuros = $('input[name="importe_euros[]"]');
			$('input[name="total_importes_marcados"]').val(0);
			marcados.each(function(i) {
				if ($(this).prop('checked')) {
					var total = parseFloat($('input[name="total_importes_marcados"]').val());
					$('input[name="total_importes_marcados"]').val(total + parseFloat(importeEuros.eq(i).val()));
					$('input[name="total_importes_marcados"]').val(parseFloat($('input[name="total_importes_marcados"]').val()).toFixed(2));
				}
			});
		}


		function Marcado(elemento, idx) {
			marcados = document.getElementsByName("marcados[]");
			descuento_porcentaje = document.getElementsByName("descuento_porcentaje[]");
			descuento_euros = document.getElementsByName("descuento_euros[]");
			importe_euros = document.getElementsByName("importe_euros[]");
			usa_saldo = document.getElementsByName("usa_saldo[]");
            notas_pago_descuento = document.getElementsByName("notas_pago_descuento[]");
			if (elemento.checked) {
				descuento_porcentaje[idx].disabled = false;
				descuento_euros[idx].disabled = false;
				importe_euros[idx].disabled = false;
				usa_saldo[idx].disabled = false;
                notas_pago_descuento[idx].disabled = false;
			} else {
				descuento_porcentaje[idx].disabled = true;
				descuento_euros[idx].disabled = true;
				importe_euros[idx].disabled = true;
				usa_saldo[idx].disabled = true;
                notas_pago_descuento[idx].disabled=true;
			}
			SumaTotal(idx);
			ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);
		}

		function DescuentoEuros(elemento, importe, idx) {
			if (event.keyCode != 9) {
				descuento_porcentaje = document.getElementsByName("descuento_porcentaje[]");
                notas_pago_descuento = document.getElementsByName("notas_pago_descuento[]");
				descuento_porcentaje[idx].value = "0";
				usa_saldo = document.getElementsByName("usa_saldo[]");
				//usa_saldo[idx].value = "0";

                if(elemento.value != "") notas_pago_descuento[idx].required=true;
                else notas_pago_descuento[idx].required=false;


				if ((parseFloat(elemento.value) >= 0 && parseFloat(elemento.value) <= importe) || elemento.value == "") {
                    if((parseFloat(elemento.value) >= 0 && parseFloat(elemento.value) <= importe) && parseFloat(elemento.value) <= parseFloat(jQuery(elemento).attr('max'))){
                        importe_euros = document.getElementsByName("importe_euros[]");
                        descuento = parseFloat(elemento.value);
                        if (isNaN(descuento)) {
                            descuento = 0;
                        }
                        importe = parseFloat(importe);
                        importe_euros[idx].value = parseFloat(importe - descuento).toFixed(2);
                        usa_saldo[idx].value = parseFloat(importe - descuento).toFixed(2);
                    }
                    else{
                        Swal.fire("EL DESCUENTO SUPERA EL MAXIMO DESCUENTO PERMITIDO PARA ESE SERVICIO ("+parseFloat(jQuery(elemento).attr('max'))+"€)");
                        elemento.value = 0;
                        importe_euros[idx].value = parseFloat(importe - valor).toFixed(2);
                        usa_saldo[idx].value = parseFloat(importe).toFixed(2);
                    }
					 
				} else {
					Swal.fire("EL DESCUENTO NO PUEDE SUPERAR EL IMPORTE O SER NEGATIVO");
					elemento.value = 0;
					importe_euros[idx].value = parseFloat(importe - valor).toFixed(2);
					usa_saldo[idx].value = parseFloat(importe).toFixed(2);
				}
				SumaTotal();
				ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);
			}
			return true;
		}

		function DescuentoPorcentaje(elemento, importe, idx) {
			if (event.keyCode != 9) {
				descuento_euros = document.getElementsByName("descuento_euros[]");
				descuento_euros[idx].value = "0";
				usa_saldo = document.getElementsByName("usa_saldo[]");
				//usa_saldo[idx].value = "0";

                notas_pago_descuento = document.getElementsByName("notas_pago_descuento[]");

                if(elemento.value != "") notas_pago_descuento[idx].required=true;
                else notas_pago_descuento[idx].required=false;

				if ((parseFloat(elemento.value) >= 0 && parseFloat(elemento.value) <= 100) || elemento.value == "") {
                    if((parseFloat(elemento.value) >= 0 && parseFloat(elemento.value) <= 100) && parseFloat(elemento.value) <= parseFloat(jQuery(elemento).attr('max'))) {
                        importe_euros = document.getElementsByName("importe_euros[]");
                        if (isNaN(elemento.value)) {
                            descuento = 0;
                        } else {
                            descuento = parseFloat(elemento.value / 100);
                        }
                        importe = parseFloat(importe);
                        valor = importe * descuento;
                        importe_euros[idx].value = parseFloat(importe - valor).toFixed(2);
                        ;
                        usa_saldo[idx].value = parseFloat(importe - valor).toFixed(2);
                    }
                    else{
                        Swal.fire("EL DESCUENTO SUPERA EL MAXIMO DESCUENTO PERMITIDO PARA ESE SERVICIO ("+parseFloat(jQuery(elemento).attr('max'))+"%)");
                        elemento.value = 0;
                        importe_euros[idx].value = parseFloat(importe - valor).toFixed(2);
                        usa_saldo[idx].value = parseFloat(importe).toFixed(2);
                    }
				} else {
					Swal.fire("EL DESCUENTO NO PUEDE DEBE SER ENTRE 0% Y 100%");
					elemento.value = 0;
					importe_euros[idx].value = parseFloat(importe - valor).toFixed(2);
					usa_saldo[idx].value = parseFloat(importe).toFixed(2);
				}
				SumaTotal();
				ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);
			}
			return true;
		}

		function UsaSaldo(elemento, importe, idx) {
			if (event.keyCode != 9) {
				importe_euros = document.getElementsByName("importe_euros[]");
				descuento_euros = document.getElementsByName("descuento_euros[]");
				descuento_porcentaje = document.getElementsByName("descuento_porcentaje[]");
				porcentaje_euros = 0;
				if (parseFloat(descuento_porcentaje[idx].value) > 0) {
					porcentaje_euros = (parseFloat(descuento_porcentaje[idx].value) / 100) * importe;
				}
				total_importe = parseFloat(importe) - (parseFloat(descuento_euros[idx].value) + parseFloat(porcentaje_euros));
				if ((parseFloat(elemento.value) >= 0 && parseFloat(elemento.value) <= parseFloat(total_importe)) || elemento.value == "") {
					descuento = parseFloat(elemento.value);
					if (isNaN(descuento)) {
						descuento = 0;
					}
					importe_euros[idx].value = parseFloat(total_importe - descuento).toFixed(2);
				} else {
					Swal.fire("EL USO DE SALDO NO PUEDE SUPERAR EL IMPORTE O SER NEGATIVO");
					elemento.value = 0;
					importe_euros[idx].value = parseFloat(importe).toFixed(2);
				}
				SumaTotal();
				ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);
			}
			return true;
		}

		function ImporteMarcado(total) {
			var saldo_disponible = parseFloat($('input[name="saldo_disponible"]').val());
			console.log(saldo_disponible);
			var saldo_marcado = parseFloat($('input[name="total_importes_marcados"]').val());
			console.log(saldo_marcado);
			var r = saldo_disponible - total;
			console.log(r);
			$('input[name="falta_importe"]').val(parseFloat(r).toFixed(2));
			var checkboxesMarcados = document.querySelectorAll('input[name="marcados[]"]:checked');
			var cantidadMarcados = checkboxesMarcados.length;
			if (r < 0 || cantidadMarcados < 1) {
				$('input[name="falta_importe"]').val(parseFloat(r).toFixed(2));
				$('#boton_cobrar').hide();
			} else {
				$('input[name="falta_importe"]').val(0);
				$('#boton_cobrar').show();
			}
		}

		function Cerrar() {
			if (window.opener === undefined || window.opener === null) {
				window.close();
			} else {
				window.opener.location.reload();
				window.close();
			}
		}

		function PagoCuenta() {
			var url = "<?php echo base_url(); ?>dietario/pago_a_cuenta/<?php echo $cliente[0]['id_cliente']; ?>";
			openwindow('pago_cuenta', url, 800, 450);
		}

		<?php if ($accion == "guardar") { ?>
			Cerrar();
		<?php } ?>

		function Eliminar(id_dietario, concepto) {
            Swal.fire({
                html: '¿DESEA BORRAR EL CONCEPTO: ' + concepto.toUpperCase() + '?',
                showCancelButton: true,
                confirmButtonText: 'Si, borrar',
                showLoaderOnConfirm: true,
                onBeforeOpen: () => {},

            }).then((result) => {
                if (result.value) {
                    document.location.href = '<?php echo base_url(); ?>dietario/borrar_conceptos/<?php echo $id_cliente; ?>/<?php echo $hoy_aaaammdd ?>/' + id_dietario;
                }
            })
        }
	</script>
</body>

</html>