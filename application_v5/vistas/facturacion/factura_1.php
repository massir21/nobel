<style>
	.dataTables_filter {
		text-align: right;
	}
	.sorting_disabled::after { display: none!important; }
</style>
<div class="page-content">
	<!-- BEGIN PAGE HEADER-->
	<!-- BEGIN PAGE BAR -->
	<div class="page-bar">
		<ul class="page-breadcrumb">
			<li>
				<a href="<?php echo base_url();?>site" style="font-size: 20px;">Panel de Control</a>
				<i class="fa fa-angle-right"></i>
			</li>
			<li>
				<span style="font-size: 20px;"><strong>Datos para la facturación</strong></span>
			</li>
		</ul>
	</div>
	<!-- END PAGE BAR -->
	<!-- END PAGE HEADER-->
	<div class="row ">
		<div class="col-md-12">
			<?php if ($this->session->flashdata('mensaje') != '') {?>
			<div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
				<div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center"></div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
        <i class="fa-times fas fs-3 text-primary"></i>
    </button>
				<span><?php echo $this->session->flashdata('mensaje'); ?></span>
			</div>
			<?php }?>
			<?php setlocale(LC_MONETARY, 'es_ES');?>
			<!-- BEGIN SAMPLE FORM PORTLET-->
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div style="overflow: hidden;">
						<form id="form_facturacion" action="<?php echo base_url();?>Facturacion" role="form" method="post" name="form_estadisticas">
							<div class="col-sm-3">
								<div class="form-group">
									<label class="form-label" for="fecha_desde">Fecha desde</label>
									<input type="date" id="fecha_desde" class="form-control form-control-solid" name="fecha_desde" value="<?php if (isset($fecha_desde)) { echo date('Y-m-d', strtotime($fecha_desde)); } ?>" />
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="form-label" for="fecha_hasta">Fecha hasta</label>
									<input type="date" id="fecha_hasta" class="form-control form-control-solid" name="fecha_hasta" value="<?php if (isset($fecha_hasta)) { echo date('Y-m-d', strtotime($fecha_hasta)); } ?>" />
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="form-label" for="id_centro">Centro</label>
									<select name="id_centro" id="id_centro" class="form-control form-control-solid">
										<option value="">Todos</option>
										<?php if (isset($centros)) { if ($centros != 0) { foreach ($centros as $key => $row) { if ($row['id_centro'] > 1) { ?>
										<option value='<?php echo $row['id_centro']; ?>' <?php if (isset($id_centro)) { if ($row['id_centro']==$id_centro) { echo "selected"; } } ?>>
											<?php echo $row['nombre_centro']; ?>
										</option>
										<?php }}}} ?>
									</select>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label btn-block">&nbsp;</label>
									<input type="submit" value="Buscar" class="btn btn-primary text-inverse-primary" />
								</div>
							</div>
						</form>
					</div>
					<div class="caption font-dark">
						<i class="icon-settings font-dark"></i>
						<span class="caption-subject bold uppercase"> Pedidos del centro</span>
					</div>
				</div>
				<div class="card-body pt-6">
        <div class="table-responsive">
					<?php if ((isset($productos['rows'])) && ($productos['rows'] != 0)){?>
					<table id="factura" class="table table-striped table-hover table-bordered">
						<thead>
							<tr>
								<th>REF</th>
								<th colspan="2">Descripción</th>
								<th>Cantidad</th>
								<th>%DTO</th>
								<th>Precio</th>
								<th>Subtotal</th>
							</tr>
						</thead>
						<tbody class="text-gray-700 fw-semibold">
							<?php foreach ($productos['rows'] as $key => $value) { ?>
							<tr>
								<td><?php echo $value->ref;?></td>
								<td><?php echo $value->familia;?></td>
								<td><?php echo $value->producto;?></td>
								<td><?php echo $value->cantidad;?></td>
								<td><?php echo $value->descuento;?></td>
								<td><?php echo $value->precio_sin_iva;?></td>
								<td><?php echo $value->subtotal;?></td>
							</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<th colspan="6" class="text-right"><strong>TOTAL PEDIDOS</strong></th>
								<th><?php echo $productos['total'];?></th>
							</tr>
						</tfoot>
					</table>
					<?php } ?>
					<?php if ((isset($citas_online['rows'])) && ($citas_online['rows'] != 0)){?>
					<table id="citas_online" class="table table-striped table-hover table-bordered">
						<thead>
							<tr>
								<th>Fecha</th>
								<th>Cliente</th>
								<th>Servicio</th>
								<th>Empleado</th>
								<th>Precio (sin IVA)</th>
								<th>Precio (con IVA)</th>
							</tr>
						</thead>
						<tbody class="text-gray-700 fw-semibold">
							<?php foreach ($citas_online['rows'] as $key => $value) { ?>
							<tr>
								<td><?php echo $value->fecha;?></td>
								<td><?php echo $value->cliente;?></td>
								<td><?php echo $value->servicio;?></td>
								<td><?php echo $value->empleado;?></td>
								<td><?php echo $value->precio_con_iva;?></td>
								<td><?php echo $value->precio_sin_iva;?></td>
							</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<th colspan="5" class="text-right"><strong>total sin IVA</strong></th>
								<th><?php echo $citas_online['subtotal'];?></th>
							</tr>
							<tr>
								<th colspan="5" class="text-right"><strong>25%</strong></th>
								<th><?php echo $citas_online['comision'];?></th>
							</tr>
							<tr>
								<th colspan="5" class="text-right"><strong>TOTAL CITAS ONLINE</strong></th>
								<th><?php echo $citas_online['total'];?></th>
							</tr>
						</tfoot>
					</table>
					<?php } ?>
				</div>
			</div>
			<!-- END SAMPLE FORM PORTLET-->
		</div>
	</div>
</div>
<!-- END CONTENT BODY -->
</div>
<!-- END CONTENT BODY -->
<script type="text/javascript">
</script>