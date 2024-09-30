<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
	<style>
		.radio-inline{
			text-transform: uppercase;
			padding-right: 1rem;
			display: inline-grid;
			grid-template-columns: 1em auto;
			gap: 0.5em;
			align-items: center;
		}

		.radio-inline + .radio-inline {
			margin-top: 1em;
		}

		input[type="radio"] {
			appearance: none;
			margin: 0;
			font: inherit;
			color: #009ef7;
			width: 1.15em;
			height: 1.15em;
			border: 1px solid currentColor;
			border-radius: 50%;
			transform: translateY(-0.075em);
			display: grid;
			place-content: center;
		}

		input[type="radio"]::before {
		    content: "";
			width: 0.65em;
			height: 0.65em;
			border-radius: 50%;
			transform: scale(0);
			transition: 120ms transform ease-in-out;
			box-shadow: inset 1em 1em var(--form-control-color);
			background-color: #009ef7;
		}

		input[type="radio"]:checked::before {
			transform: scale(1);
		}

		input[type="radio"]:focus {
		outline: max(2px, 0.15em) solid currentColor;
		outline-offset: max(2px, 0.15em);
		}
	</style>
	<div class="card card-flush m-5">
		<div class="card-body">
			<div class="d-flex flex-center flex-row mb-5">
				<h1 class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1">Ficha de Salud</h1>
			</div>
			<?php if (isset($registros)) {
				$modo = "";
				$modo_radio = "";
				if ($accion == "ver") {
					$modo = "readonly";
					$modo_radio = "disabled";
				}
				if ($accion == "clonar") {
					$action = base_url() . 'clientes/clon/nuevo/' . $registros[0]['id_cliente'] . '/si';
					$name = "form2";
				} else {
					$name = "form";
					$action = base_url() . 'clientes/ver_ficha/' . $registros[0]['id'] . '/actualizar';
				} ?>

				<form name="<?= $name ?>" id="form" action="<?= $action ?>" method="POST">

					<!--- ******************* campo ************************* -->
					<?php
					if ($registros[0]['pasadas'] == "no") {
						$op1 = "checked";
						$op2 = "";
						$ver = "none";
					} else {
						$op2 = "checked";
						$op1 = "";
						$ver = "block";
					}
					?>
					<?php if ($accion == "clonar") { ?>
						<input type="hidden" name="firma_img" id="firma_img" value="<?php echo $registros[0]['firma_img']; ?>" />
					<?php } ?>
					<div class="row mb-5 border-bottom">
						<div class="col-md-4">
							<label class="form-label">Enfermedades Pasadas</label>
						</div>
						<div class="col-md-4">
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="pasadas" id="pasadas1" value="no" onclick="elegir('pasadas','no')" <?php echo $op1; ?>>no</label>
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="pasadas" id="pasadas2" value="si" onclick="elegir('pasadas','si')" <?php echo $op2; ?>>si</label>
						</div>
						<div class="col-md-4" id="t_pasadas" style="display: <?php echo $ver; ?>;">
							<label class="form-label">Notas</label>
							<textarea class="form-control form-control-solid" name="notas_pasadas" id="notas_pasadas" <?php echo $modo ?>><?php echo $registros[0]['notas_pasadas']; ?>  </textarea>
						</div>
					</div>
					<?php
					if ($registros[0]['actuales'] == "no") {
						$op1 = "checked";
						$op2 = "";
						$ver = "none";
					} else {
						$op2 = "checked";
						$op1 = "";
						$ver = "block";
					}
					?>
					<!--- ******************* campo ************************* -->
					<div class="row mb-5 border-bottom">
						<div class="col-md-4">
							<label class="form-label">Enfermedades Actuales</label>
						</div>
						<div class="col-md-4">
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="actuales" id="actuales1" value="no" onclick="elegir('actuales','no')" <?php echo $op1; ?>>no</label>
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="actuales" id="actuales2" value="si" onclick="elegir('actuales','si')" <?php echo $op2; ?>>si</label>
						</div>
						<div class="col-md-4" id="t_actuales" style="display: <?php echo $ver; ?>;">
							<label class="form-label">Notas</label>
							<textarea class="form-control form-control-solid" name="notas_actuales" id="notas_actuales" <?php echo $modo ?>><?php echo $registros[0]['notas_actuales']; ?>   </textarea>
						</div>
					</div>
					<?php
					if ($registros[0]['medicamentos'] == "no") {
						$op1 = "checked";
						$op2 = "";
						$ver = "none";
					} else {
						$op2 = "checked";
						$op1 = "";
						$ver = "block";
					}
					?>
					<!--- ******************* campo ************************* -->
					<div class="row mb-5 border-bottom">
						<div class="col-md-4">
							<label class="form-label">Toma Medicamentos Actualmente</label>
						</div>
						<div class="col-md-4">
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="medicamentos" id="medicamentos1" value="no" onclick="elegir('medicamentos','no')" <?php echo $op1; ?>>no</label>
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="medicamentos" id="medicamentos2" value="si" onclick="elegir('medicamentos','si')" <?php echo $op2; ?>>si</label>
						</div>
						<div class="col-md-4" id="t_medicamentos" style="display: <?php echo $ver; ?>;">
							<label class="form-label">Notas</label>
							<textarea class="form-control form-control-solid" name="notas_medicamentos" id="notas_medicamentos" <?php echo $modo ?>><?php echo $registros[0]['notas_medicamentos']; ?>   </textarea>
						</div>
					</div>
					<?php
					if ($registros[0]['suplementos'] == "no") {
						$op1 = "checked";
						$op2 = "";
						$ver = "none";
					} else {
						$op2 = "checked";
						$op1 = "";
						$ver = "block";
					}
					?>
					<!--- ******************* campo ************************* -->
					<div class="row mb-5 border-bottom">
						<div class="col-md-4">
							<label class="form-label">Toma Suplementos Actualmente</label>
						</div>
						<div class="col-md-4">
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="suplementos" id="suplementos1" value="no" onclick="elegir('suplementos','no')" <?php echo $op1; ?>>no</label>
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="suplementos" id="suplementos2" value="si" onclick="elegir('suplementos','si')" <?php echo $op2; ?>>si</label>
						</div>
						<div class="col-md-4" id="t_suplementos" style="display: <?php echo $ver; ?>;">
							<label class="form-label">Notas</label>
							<textarea class="form-control form-control-solid" name="notas_suplementos" id="notas_suplementos" <?php echo $modo ?>><?php echo $registros[0]['notas_suplementos']; ?>   </textarea>
						</div>
					</div>
					<?php
					if ($registros[0]['intervenciones'] == "no") {
						$op1 = "checked";
						$op2 = "";
						$ver = "none";
					} else {
						$op2 = "checked";
						$op1 = "";
						$ver = "block";
					}
					?>
					<!--- ******************* campo ************************* -->
					<div class="row mb-5 border-bottom">
						<div class="col-md-4">
							<label class="form-label">Intervenciones Quir&uacute;rgicas</label>
						</div>
						<div class="col-md-4">
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="intervenciones" id="intervenciones1" value="no" onclick="elegir('intervenciones','no')" <?php echo $op1; ?>>no</label>
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="intervenciones" id="intervenciones2" value="si" onclick="elegir('intervenciones','si')" <?php echo $op2; ?>>si</label>
						</div>
						<div class="col-md-4" id="t_intervenciones" style="display: <?php echo $ver; ?>;">
							<label class="form-label">Notas</label>
							<textarea class="form-control form-control-solid" name="notas_intervenciones" id="notas_intervenciones" <?php echo $modo ?>><?php echo $registros[0]['notas_intervenciones']; ?>  </textarea>
						</div>
					</div>
					<?php
					if ($registros[0]['implantes'] == "no") {
						$op1 = "checked";
						$op2 = "";
						$ver = "none";
					} else {
						$op2 = "checked";
						$op1 = "";
						$ver = "block";
					}
					?>
					<!--- ******************* campo ************************* -->
					<div class="row mb-5 border-bottom">
						<div class="col-md-4">
							<label class="form-label">Implantes o Dispositivos</label>
						</div>
						<div class="col-md-4">
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="implantes" id="implantes1" value="no" onclick="elegir('implantes','no')" <?php echo $op1; ?>>no</label>
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="implantes" id="implantes2" value="si" onclick="elegir('implantes','si')" <?php echo $op2; ?>>si</label>
						</div>
						<div class="col-md-4" id="t_implantes" style="display: <?php echo $ver; ?>;">
							<label class="form-label">Notas</label>
							<textarea class="form-control form-control-solid" name="notas_implantes" id="notas_implantes" <?php echo $modo ?>><?php echo $registros[0]['notas_implantes']; ?>  </textarea>
						</div>
					</div>
					<?php
					if ($registros[0]['alergias'] == "no") {
						$op1 = "checked";
						$op2 = "";
						$ver = "none";
					} else {
						$op2 = "checked";
						$op1 = "";
						$ver = "block";
					}
					?>
					<!--- ******************* campo ************************* -->
					<div class="row mb-5 border-bottom">
						<div class="col-md-4">
							<label class="form-label">Alergias</label>
						</div>
						<div class="col-md-4">
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="alergias" id="alergias1" value="no" onclick="elegir('alergias','no')" <?php echo $op1; ?>>no</label>
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="alergias" id="alergias2" value="si" onclick="elegir('alergias','si')" <?php echo $op2; ?>>si</label>
						</div>
						<div class="col-md-4" id="t_alergias" style="display: <?php echo $ver; ?>;">
							<label class="form-label">Notas</label>
							<textarea class="form-control form-control-solid" name="notas_alergias" id="notas_alergias" <?php echo $modo ?>><?php echo $registros[0]['notas_alergias']; ?>  </textarea>
						</div>
					</div>
					<?php
					if ($registros[0]['fumador'] == "nunca") {
						$op1 = "checked";
						$op2 = "";
						$op3 = "";
						$op4 = "";
					}
					if ($registros[0]['fumador'] == "rara vez") {
						$op2 = "checked";
						$op1 = "";
						$op3 = "";
						$op4 = "";
					}
					if ($registros[0]['fumador'] == "habitual") {
						$op3 = "checked";
						$op2 = "";
						$op1 = "";
						$op4 = "";
					}
					if ($registros[0]['fumador'] == "mucho") {
						$op4 = "checked";
						$op2 = "";
						$op3 = "";
						$op1 = "";
					}
					?>
					<!--- ******************* campo ************************* -->
					<div class="row mb-5 border-bottom">
						<div class="col-md-4">
							<label class="form-label">Fumador</label>
						</div>
						<div class="col-md-8">
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="fumador" id="fumador1" value="nunca" <?php echo $op1; ?>>Nunca</label>
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="fumador" id="fumador2" value="rara vez" <?php echo $op2; ?>>Rara vez</label>
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="fumador" id="fumador3" value="habitual" <?php echo $op3; ?>>Habitual</label>
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="fumador" id="fumador4" value="mucho" <?php echo $op4; ?>>Mucho</label>
						</div>
					</div>
					<?php
					if ($registros[0]['fumador'] == "nunca") {
						$op1 = "checked";
						$op2 = "";
						$op3 = "";
						$op4 = "";
					}
					if ($registros[0]['fumador'] == "rara vez") {
						$op2 = "checked";
						$op1 = "";
						$op3 = "";
						$op4 = "";
					}
					if ($registros[0]['fumador'] == "habitual") {
						$op3 = "checked";
						$op2 = "";
						$op1 = "";
						$op4 = "";
					}
					if ($registros[0]['fumador'] == "mucho") {
						$op4 = "checked";
						$op2 = "";
						$op3 = "";
						$op1 = "";
					}
					?>
					<!--- ******************* campo ************************* -->
					<div class="row mb-5 border-bottom">
						<div class="col-md-4">
							<label class="form-label">Alcohol</label>
						</div>
						<div class="col-md-8">
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="alcohol" id="alcohol1" value="nunca" <?php echo $op1; ?>>Nunca</label>
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="alcohol" id="alcohol2" value="rara vez" <?php echo $op2; ?>>Rara vez</label>
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="alcohol" id="alcohol3" value="habitual" <?php echo $op3; ?>>Habitual</label>
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="alcohol" id="alcohol4" value="mucho" <?php echo $op4; ?>>Mucho</label>
						</div>
					</div>
					<!--- ******************* campo ************************* -->
					<?php
					if ($registros[0]['drogas'] == "no") {
						$op1 = "checked";
						$op2 = "";
						$ver = "none";
					} else {
						$op2 = "checked";
						$op1 = "";
						$ver = "block";
					}
					?>
					<div class="row mb-5 border-bottom">
						<div class="col-md-4">
							<label class="form-label">Drogas</label>
						</div>
						<div class="col-md-4">
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="drogas" id="drogas1" value="no" onclick="elegir('drogas','no')" <?php echo $op1; ?>>no</label>
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="drogas" id="drogas2" value="si" onclick="elegir('drogas','si')" <?php echo $op2; ?>>si</label>
						</div>
						<div class="col-md-4" id="t_drogas" style="display: <?php echo $ver; ?>;">
							<label class="form-label">Notas</label>
							<textarea class="form-control form-control-solid" name="notas_drogas" id="notas_drogas" <?php echo $modo ?>><?php echo $registros[0]['notas_drogas']; ?>  </textarea>
						</div>
					</div>
					<!--- ******************* campo ************************* -->
					<?php
					if ($registros[0]['anticonceptivos'] == "no") {
						$op1 = "checked";
						$op2 = "";
						$ver = "none";
					} else {
						$op2 = "checked";
						$op1 = "";
						$ver = "block";
					}
					?>
					<div class="row mb-5 border-bottom">
						<div class="col-md-4">
							<label class="form-label">Anticoneptivos</label>
						</div>
						<div class="col-md-4">
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="anticonceptivos" id="anticonceptivos1" value="no" onclick="elegir('anticonceptivos','no')" <?php echo $op1; ?>>no</label>
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="anticonceptivos" id="anticonceptivos2" value="si" onclick="elegir('anticonceptivos','si')" <?php echo $op2; ?>>si</label>
						</div>
						<div class="col-md-4" id="t_anticonceptivos" style="display: <?php echo $ver; ?>;">
							<label class="form-label">Notas</label>
							<textarea class="form-control form-control-solid" name="notas_anticonceptivos" id="notas_anticonceptivos" <?php echo $modo ?>><?php echo $registros[0]['notas_anticonceptivos']; ?>  </textarea>
						</div>
					</div>
					<!--- ******************* campo ************************* -->
					<?php
					if ($registros[0]['embarazada'] == "no") {
						$op1 = "checked";
						$op2 = "";
						$ver = "none";
					} else {
						$op2 = "checked";
						$op1 = "";
						$ver = "block";
					}
					?>
					<div class="row mb-5 border-bottom">
						<div class="col-md-4">
							<label class="form-label">Embarazada o cree estarlo</label>
						</div>
						<div class="col-md-4">
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="embarazada" id="embarazada1" value="no" onclick="elegir('embarazada','no')" <?php echo $op1; ?>>no</label>
							<label class="radio-inline"><input <?php echo $modo_radio ?> type="radio" name="embarazada" id="embarazada2" value="si" onclick="elegir('embarazada','si')" <?php echo $op2; ?>>si</label>
						</div>
						<div class="col-md-4" id="t_embarazada" style="display: <?php echo $ver; ?>;">
							<label class="form-label">Notas</label>
							<textarea class="form-control form-control-solid" name="notas_embarazada" id="notas_embarazada" <?php echo $modo ?>><?php echo $registros[0]['notas_embarazada']; ?>  </textarea>
						</div>
					</div>
					<!--- ******************* Firma ************************* -->
					<?php if ($registros[0]['firma_img'] != "") { ?>
						<div class="row mb-5 border-bottom">
							<div class="col-md-4" style="text-align: center;">
								<label class="form-label">Firma del cliente.</label>
								<div style="margin: 10px;">
									<img src="<?php echo base_url(); ?>recursos/firmas/<?php echo $registros[0]['firma_img']; ?>" style="width: 300px; height: 150px;" />
								</div>
							</div>
						</div>
					<?php } ?>
					<?php if ($accion == "editar") { ?>
						<div class="row mb-5 border-bottom">
							<div class="col-md-12" style="text-align: center;">
								<input type="submit" class="btn btn-info text-inverse-info" value="Actualizar" />
							</div>
						</div>
					<?php } ?>
					<?php if ($accion == "clonar") { ?>
						<div class="row mb-5 border-bottom">
							<div class="col-md-12" style="text-align: center;">
								<input type="submit" class="btn btn-info text-inverse-info" value="Clonar" />
							</div>
						</div>
					<?php } ?>
				</form>
			<?php } ?>
		</div>
	</div>

	<script>
		//20/10/20
		function elegir(id, respuesta) {
			xtexto = 't_' + id;
			xnotas = "notas_" + id;
			//alert ('llego '+xnotas);
			if (respuesta == 'si')
				document.getElementById(xtexto).style.display = "block";
			else {
				document.getElementById(xnotas).value = "";
				document.getElementById(xtexto).style.display = "none";
			}
		}

		<?php if ($accion == "actualizar" or $accion == "nuevo") { ?>
			window.opener.location.reload();
			window.close();
		<?php } ?>
	</script>
</body>
</html>