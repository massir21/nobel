<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
	<h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">Historial Antiguo</h1>
	<?php if (isset($estado)) {
		if ($estado > 0) { ?>
			<div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
				<div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center"></div>
				<button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
					<i class="fa-times fas fs-3 text-primary"></i>
				</button>
				<span>
					EL REGISTRO SE GUARDÓ CORRECTAMENTE </span>
			</div>
		<?php } else { ?>
			<div class="alert alert-danger display-hide" style="display: block; text-align: center;">
				<div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center"></div>
				<button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
					<i class="fa-times fas fs-3 text-primary"></i>
				</button>
				<span>
					YA EXISTE UN CLIENTE CON EL MISMO EMAIL EN EL SISTEMA</span>
				<p><br><a href="javascript:history.back();">Volver</a></p>
			</div>
	<?php }
	} ?>
	<?php if (isset($borrado)) { ?>
		<div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
			<div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE BORRÓ CORRECTAMENTE</div>
			<button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
				<i class="fa-times fas fs-3 text-primary"></i>
			</button>
		</div>
	<?php } ?>

	<div class="card card-flush m-5">
		<div class="card-body">
			<div class="d-flex flex-center flex-row mb-5">
				<h1 class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1"><?= $registros[0]['nombre'] ?> <?= (isset($registros)) ? $registros[0]['apellidos'] : '' ?></h1>
			</div>
			<div class="table-responsive">
				<table id="myTable4" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
					<thead class="">
						<tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
							<th>Nº Fact</th>
							<th>Fecha</th>
							<th>Centro</th>
							<th>Importe</th>
							<th>Cod Art.</th>
							<th>Artículo</th>
							<th>Cantidad</th>
						</tr>
					</thead>
					<tbody class="text-gray-700 fw-semibold">
						<?php $total_importe = 0;
						if (isset($historial_antiguo)) {
							if (is_array($historial_antiguo)) {
								foreach ($historial_antiguo as $key => $row) { ?>
									<tr>
										<td class="text-center">
											<?php echo $row['numfac']; ?>
										</td>
										<td class="text-center">
											<?php echo $row['fecfac']; ?>
										</td>
										<td class="text-center">
											<?php echo $row['nombre_centro']; ?>
										</td>
										<td class="text-end">
											<?php echo $row['totfac'] . "€";
											if (is_numeric($row['totfac'])) {
												$total_importe += $row['totfac'];
											}?>
										</td>
										<td class="text-center">
											<?php echo $row['codart']; ?>
										</td>
										<td style="text-align: left;">
											<?php echo $row['desart']; ?>
										</td>
										<td class="text-center">
											<?php echo $row['cant']; ?>
										</td>
									</tr>
						<?php }
							}
						} ?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="2" style="text-align: right; padding: 8px;"><b>TOTALES</b></td>
							<td style="text-align: right; padding: 8px;"><?php echo number_format($total_importe, 2, ",", ".") . '€'; ?></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>

	<script>
		$('#myTable4').DataTable({
			"order": [1, "desc"],
		});
	</script>

</body>

</html>