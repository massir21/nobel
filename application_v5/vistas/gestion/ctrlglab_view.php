<?php if (isset($estado)) {
	if ($estado > 0) { ?>
		<div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
			<div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE GUARDÓ CORRECTAMENTE</div>
			<button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
				<i class="fa-times fas fs-3 text-primary"></i>
			</button>
		</div>
	<?php } else { ?>
		<div class="alert alert-danger display-hide" style="display: block; text-align: center;">
			<div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">YA EXISTE UN CLIENTE CON EL MISMO EMAIL EN EL SISTEMA<br><a href="javascript:history.back();">Volver</a></div>
			<button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
				<i class="fa-times fas fs-3 text-primary"></i>
			</button>
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
<?php if (isset($estado_fusion)) {
	if ($estado_fusion == 1) { ?>
		<div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
			<div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center"> LA FUSIÓN DE LOS CLIENTES MARCADOS SE HA REALIZADO CORRECTAMENTE: <a href="<?php echo base_url(); ?>recursos/logs/fusiones_clientes.log" target="_blank">VER LOG CON MÁS INFORMACIÓN DEL PROCESO</a></div>
			<button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
				<i class="fa-times fas fs-3 text-primary"></i>
			</button>
		</div>
<?php }
} ?>
<style>
	.select2-container--bootstrap5 .select2-selection__clear {
		height: 1rem;
		width: 1rem;
		top: 5px;
		transform: translateY(-50%);
		background-color: var(--bs-danger) !important;
	}
</style>
<div class="card card-flush">
	<div class="card-header align-items-end py-5 gap-2 gap-md-5">
		<div class="card-title">
			<div class="d-flex align-items-center position-relative my-1">
				<i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>
				<input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="tabla_clientes">
			</div>

		</div>
		<div id="buttons"></div>

		<div class="card-title w-100 justify-content-end flex-wrap">
			<form action="" method="post" class="d-flex justify-content-end flex-wrap">
				<div class="m-1">
					<div class="input-group mb-3">
						<span class="input-group-text">Desde</span>
						<input type="date" id="fecha_desde" name="fecha_desde" value="<?= ($this->input->post('fecha_desde') != '') ? $this->input->post('fecha_desde') : '' ?>" class="form-control form-control-solid w-auto" placeholder="Fecha desde"  />
					</div>
				</div>

				<div class="m-1">
					<div class="input-group mb-3">
						<span class="input-group-text">Hasta</span>
						<input type="date" id="fecha_hasta" name="fecha_hasta" value="<?= ($this->input->post('fecha_hasta') != '') ? $this->input->post('fecha_hasta') : '' ?>" class="form-control form-control-solid w-auto" placeholder="Fecha hasta"  />
					</div>
				</div>
				<div class="m-1" style="min-width: 200px;">
					<select name="id_usuario" id="id_usuario" class="form-select form-select-solid" data-placeholder="Elegir empleado">
						<option value="">Todos los usuarios</option>
						<?php foreach ($usuarios as $rs) { ?>
							<option value="<?php echo $rs['id_usuario']; ?>" <?=($this->input->post('id_usuario') == $rs['id_usuario'])?'selected':''?>><?php echo $rs['nombre'] . ' ' . $rs['apellidos'] . ' (' . $rs['nombre_centro'] . ')'; ?></option>
						<?php } ?>
					</select>
					<script type="text/javascript">
						$("#id_usuario").select2({
							language: "es",
							//minimumInputLength: 3,
							allowClear: true,
						});
					</script>
				</div>

				<div class="m-1">
					<select name="id_cliente" id="id_cliente" class="form-select form-select-solid" data-placeholder="Elegir cliente">
						<option value="">Todos los clientes</option>
					</select>
					<script type="text/javascript">
						$("#id_cliente").select2({
							language: "es",
							minimumInputLength: 3,
							allowClear: true,
							ajax: {
								delay: 0,
								url: function(params) {
									return '<?php echo RUTA_WWW; ?>/clientes/json/' + params.term.replace(/ /g, "_");
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
				<div class="m-1">
					<div class="input-group mb-3">
						<span class="input-group-text">Estado</span>
						<select class="form-select" name="estado" id="estado">
							<option value="" <?= ($this->input->post('estado') == '') ? 'selected' : '' ?>>Cualquiera</option>
							<option value="0" <?= ($this->input->post('estado') == 0) ? 'selected' : '' ?>>Pendiente</option>
							<option value="1" <?= ($this->input->post('estado') == 1) ? 'selected' : '' ?>>Liquidada</option>
						</select>
					</div>
				</div>
				<div class="m-1">
					<button type="submit" class="btn btn-info btn-icon text-inverse-info" id="filterbutton"><i class="fas fa-search"></i></button>
				</div>
			</form>
		</div>
	</div>
	<div class="card-body pt-6">
		<ul class="gap-3 mb-6 nav nav-justified nav-pills">
			<li class="border border-1 border-secondary mb-3 nav-item p-3 rounded text-center">
				<span class="fw-bold d-block text-gray-800 cursor-pointer text-hover-primary fs-3 fs-xl-1">PVP</span>
				<span class="text-success text-end fw-bold fs-1"><?= euros($total['pvp']) ?></span>
			</li>
			<li class="border border-1 border-secondary mb-3 nav-item p-3 rounded text-center">
				<span class="fw-bold d-block text-gray-800 cursor-pointer text-hover-primary fs-3 fs-xl-1">Dto.</span>
				<span class="text-success text-end fw-bold fs-1"><?= euros($total['dto']) ?></span>
			</li>
			<li class="border border-1 border-secondary mb-3 nav-item p-3 rounded text-center">
				<span class="fw-bold d-block text-gray-800 cursor-pointer text-hover-primary fs-3 fs-xl-1">Dto. P.</span>
				<span class="text-success text-end fw-bold fs-1"><?= euros($total['dtop']) ?></span>
			</li>
			<li class="border border-1 border-secondary mb-3 nav-item p-3 rounded text-center">
				<span class="fw-bold d-block text-gray-800 cursor-pointer text-hover-primary fs-3 fs-xl-1">Gastos L.</span>
				<span class="text-success text-end fw-bold fs-1"><?= euros($total['gastos_lab']) ?></span>
			</li>
			<?php /*<li class="border border-1 border-secondary mb-3 nav-item p-3 rounded text-center">
				<span class="fw-bold d-block text-gray-800 cursor-pointer text-hover-primary fs-3 fs-xl-1">Total</span>
				<span class="text-success text-end fw-bold fs-1"><?= euros($total['total']) ?></span>
			</li>
			*/ ?>
		</ul>
		<div class="table-responsive">
			<table id="tabla_clientes" class="align-middle" style="display: none;">
				<thead>
					<tr>
						<th>Clientes</th>
					</tr>
				</thead>
				<tbody>

					<?php $i = 0;
					foreach ($clientes as $key => $value) {
						$i++; ?>
						<tr>
							<td>
								<div class="py-0" data-kt-customer-payment-method="row">
									<div class="py-3 d-flex flex-stack flex-wrap border-bottom">
										<div class="d-flex align-items-center collapsible rotate collapsed" data-bs-toggle="collapse" href="#cliente_liquidacion_<?= $value['id_cliente'] ?>" role="button" aria-expanded="false" aria-controls="cliente_liquidacion_<?= $value['id_cliente'] ?>">
											<div class="me-3 rotate-90"><i class="ki-duotone ki-right fs-3"></i></div>
											<div class="me-3">
												<div class="d-flex align-items-center">
													<div class="text-gray-800 fw-bold fs-3"><?= $value['nombre'] . ' ' . $value['apellidos'] ?></div>
												</div>
											</div>
										</div>

										<ul class="nav nav-pills nav-justified nav-pills-custom gap-3 w-50 text-end">
											<li class="nav-item">
												<span class="fw-bold text-gray-800 cursor-pointer text-hover-primary fs-9">PVP</span>
												<span class="text-success text-end fw-bold fs-5"><?= euros($value['pvp']) ?></span>
											</li>
											<li class="nav-item">
												<span class="fw-bold text-gray-800 cursor-pointer text-hover-primary fs-9">Dto.</span>
												<span class="text-success text-end fw-bold fs-5"><?= euros($value['dto']) ?></span>
											</li>
											<li class="nav-item">
												<span class="fw-bold text-gray-800 cursor-pointer text-hover-primary fs-9">Dto. P.</span>
												<span class="text-success text-end fw-bold fs-5"><?= euros($value['dtop']) ?></span>
											</li>
											<li class="nav-item">
												<span class="fw-bold text-gray-800 cursor-pointer text-hover-primary fs-9">Gastos L.</span>
												<span class="text-success text-end fw-bold fs-5"><?= euros($value['gastos_lab']) ?></span>
											</li>
											<li class="nav-item">
												<span class="fw-bold text-gray-800 cursor-pointer text-hover-primary fs-9">Total</span>
												<span class="text-success text-end fw-bold fs-5"><?= euros($value['total']) ?></span>
											</li>
										</ul>
									</div>
									<div id="cliente_liquidacion_<?= $value['id_cliente'] ?>" class="fs-6 p-10 collapse" data-bs-parent="#cliente_liquidacion" style="">
										<div class="table-responsive">
											<table class="align-middle border fs-6 mb-5 table table-bordered table-row-dashed table-sm table-striped">
												<thead class="">
													<tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
														<th>Fecha</th>
														<th>Servicio</th>
														<th>Empleado</th>
														<th>PVP</th>
														<th>Dto.</th>
														<th>Dto. P.</th>
														<th>Gastos L.</th>
														<th>Total</th>
														<th>Estado</th>
													</tr>
												</thead>
												<tbody class="text-gray-700 fw-semibold">
													<?php //print_r($value['citas']); ?>
													<?php foreach ($value['citas'] as $c => $cita) { ?>
														<tr class="citas_lab">
															<td><?= $cita['fecha_cita'] ?></td>
															<td><?= $cita['nombre_servicio'] ?> <span  class="text-primary"><br>Diente: <span class="diente" id="id_presupuesto_item_<?= $cita['id_presupuesto_item'] ?>" data-id="<?= $cita['id_presupuesto_item'] ?>"></span></span></td>
															<td><?= $cita['usuario'] ?></td>
															<td><?= $cita['pvp'] ?></td>
															<td><?= $cita['dto'] ?></td>
															<td><?= $cita['dtop'] ?></td>
															<td><?= $cita['gastos_lab'] ?></td>
															<td><?= $cita['total'] ?></td>
															<td><?= ($cita['estado'] == 1) ? 'Liquidada' : 'Pendiente' ?></td>
														</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
									<!--end::Body-->
								</div>

								<?php if (count($clientes) > $i) { ?>
									<div class="separator separator-dashed"></div>
								<?php } ?>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
    /*$('.citas_lab').each(function (){
        let span=$(this).find(".diente");
        let id_presupuesto_item= span.attr("data-id");
        $.ajax({
            url:  "<?php echo base_url(); ?>Presupuestos/get_diente",
            type: 'POST',
            datatype: "json",
            data: {
                id_presupuesto_item : id_presupuesto_item,
            }, 
            success: function(response) {
                let diente=$("#id_presupuesto_item_"+id_presupuesto_item);
                diente.html(response.diente);
            }  
        
        });
    });*/
	$("#tabla_clientes").DataTable({
		language: {
			"sProcessing": "Procesando...",
			"sZeroRecords": "No se encontraron resultados",
			"sEmptyTable": "Ningún dato disponible en esta tabla",
			"sInfoEmpty": "No hay resultados",
			"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix": "",
			"sUrl": "",
			"sInfoThousands": ",",
			"sLoadingRecords": "Cargando...",
			"sLengthMenu": "<div class=\"\">_MENU_</div>",
			"sSearch": "<div class=\"\">_INPUT_</div>",
			"sSearchPlaceholder": "Escribe para buscar...",
			"sInfo": "_START_ de _END_ (_TOTAL_ total)",
			"oPaginate": {
				"sPrevious": "",
				"sNext": ""
			},
			"oAria": {
				"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}
		},
		pageLength: 10,
		initComplete: function() {
			$("#tabla_clientes").fadeIn()
		},
		drawCallback: function(settings) {
            $('.citas_lab').each(function (){
                let span=$(this).find(".diente");
                let id_presupuesto_item= span.attr("data-id");
                $.ajax({
                    url:  "<?php echo base_url(); ?>Presupuestos/get_diente",
                    type: 'POST',
                    datatype: "json",
                    data: {
                        id_presupuesto_item : id_presupuesto_item,
                    }, 
                    success: function(response) {
                        let diente=$("#id_presupuesto_item_"+id_presupuesto_item);
                        diente.html(response.diente);
                    }
                });
            });
		},
	});
    


	/*
		var oldExportAction = function(self, e, dt, button, config) {
			if (button[0].className.indexOf('buttons-excel') >= 0) {
				if ($.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)) {
					$.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);
				} else {
					$.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
				}
			} else if (button[0].className.indexOf('buttons-print') >= 0) {
				$.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
			}
		};

		var newExportAction = function(e, dt, button, config) {
			var self = this;
			var oldStart = dt.settings()[0]._iDisplayStart;
			dt.one('preXhr', function(e, s, data) {
				data.start = 0;
				data.length = 2147483647;
				dt.one('preDraw', function(e, settings) {
					oldExportAction(self, e, dt, button, config);
					dt.one('preXhr', function(e, s, data) {
						settings._iDisplayStart = oldStart;
						data.start = oldStart;
					});
					setTimeout(dt.ajax.reload, 0);
					return false;
				});
			});
			dt.ajax.reload();
		};

		var tabla_liquidaciones = $("#tabla_liquidaciones").DataTable({
			info: true,
			paging: true,
			ordering: true,
			searching: true,
			stateSave: false,
			processing: true,
			serverSide: true,
			scrollX: true,
			autoWidth: false,
			order: [[0, "desc"],[1, "asc"]],
			pageLength: 50,
			lengthMenu: [
				[10, 25, 50, 100, -1],
				[10, 25, 50, 100, "Todos"],
			],
			columns: [{
					//0
					title: "Fecha",
					name: "fecha_cita",
					data: "fecha_cita",
					render: function(data, type, row) {
						return row.fecha_cita;
					}
				},
				{
					//1
					title: "Cliente",
					name: "cliente",
					data: "cliente",
					render: function(data, type, row) {
						var html = `<a class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6" href="<?php echo base_url(); ?>clientes/historial/ver/${row.id_cliente}">${row.cliente}</a>`;
						return html
					}
				},
				{
					// 2
					title: "Servicio",
					name: "nombre_servicio",
					data: "nombre_servicio",
					render: function(data, type, row) {
						var html = row.nombre_servicio;
						return html
					}
				},
				{
					// 3
					title: "Empleado",
					name: "usuario",
					data: "usuario",
					render: function(data, type, row) {
						var html = row.usuario;
						return html
					}
				},
				{
					// 4
					title: "PVP",
					name: "pvp",
					data: "pvp",
					render: function(data, type, row) {
						var html = row.pvp;
						return html
					}
				},
				{
					// 5
					title: "Dto",
					name: "dto",
					data: "dto",
					render: function(data, type, row) {
						var html = row.dto;
						return html
					}
				},
				{
					// 6
					title: "Dto P.",
					name: "dtop",
					data: "dtop",
					render: function(data, type, row) {
						var html = row.dtop;
						return html
					}
				},
				{
					// 7
					title: "G.Lab",
					name: "gastos_lab",
					data: "gastos_lab",
					render: function(data, type, row) {
						var html = row.gastos_lab;
						return html
					}
				},
				{
					// 8
					title: "Total",
					name: "total",
					data: "total",
					render: function(data, type, row) {
						var html = row.total;
						return html
					}
				},
				{
					// 9
					title: "Estado",
					name: "estado",
					data: "estado",
					render: function(data, type, row) {
						if(row.estado == 1){
							var html = 'Liquidada';
						}else{
							var html = 'Pendiente';
						}
						return html
					}
				}
			],

			columnDefs: [{
					targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
					visible: true,
				},
				{
					targets: ["_all"],
					visible: false,
				},
				
			],

			ajax: {
				url: "<?php echo base_url(); ?>Gestion/get_ctrlGLab",
				type: "GET",
				datatype: "json",
				data: function(data) {
					var fecha_desde = $('#fecha_desde').val();
					var fecha_hasta = $('#fecha_hasta').val();
					var id_cliente = $('#id_cliente').val();
					var id_usuario = $('#id_usuario').val();
					var estado = $('#estado').val();
					if (fecha_desde != "") {
						data.fecha_desde = fecha_desde;
					}
					if (fecha_hasta != "") {
						data.fecha_hasta = fecha_hasta;
					}
					if (id_cliente != "") {
						data.id_cliente = id_cliente;
					}
					if (id_usuario != "") {
						data.id_usuario = id_usuario;
					}
					if (estado != "") {
						data.estado = estado;
					}
				},
			},
			language: {
				"sProcessing": "Procesando...",
				"sZeroRecords": "No se encontraron resultados",
				"sEmptyTable": "Ningún dato disponible en esta tabla",
				"sInfoEmpty": "No hay resultados",
				"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
				"sInfoPostFix": "",
				"sUrl": "",
				"sInfoThousands": ",",
				"sLoadingRecords": "Cargando...",
				"sLengthMenu": "<div class=\"\">_MENU_</div>",
				"sSearch": "<div class=\"\">_INPUT_</div>",
				"sSearchPlaceholder": "Escribe para buscar...",
				"sInfo": "_START_ de _END_ (_TOTAL_ total)",
				"oPaginate": {
					"sPrevious": "",
					"sNext": ""
				},
				"oAria": {
					"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
					"sSortDescending": ": Activar para ordenar la columna de manera descendente"
				}
			},
			dom: "<'table-responsive'tr>" +
				"<'row'" +
				"<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'li>" +
				"<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
				">",
			buttons: {
				buttons: [{
					text: "Exportar Excel",
					extend: "excelHtml5",
					title: 'Comisiones',
					className: "btn btn-warning text-inverse-warning",
					attr: {
						"data-tooltip": "Exportar tabla en excel",
						"data-placement": "auto",
						title: "Exportar tabla en excel",
					},
					exportOptions: {
						columns: ":not(.noexp)",
						orthogonal: "export",
					},
				}, ],
				dom: {
					button: {
						className: "btn",
					},
				},
			},
			headerCallback: function(thead, data, start, end, display) {},
			createdRow: function(row, data, dataIndex) {
				$(row).attr('id', 'fila' + data.id_cliente);
			},
			drawCallback: function(settings) {
				$('thead tr').addClass('text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0')
			},
			initComplete: function() {
				var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
				var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
					return new bootstrap.Tooltip(tooltipTriggerEl, {
						'trigger': 'hover'
					})
				})
			},
		});

		var buttons = new $.fn.dataTable.Buttons(tabla_liquidaciones, {
			buttons: [{
				text: "Exportar Excel",
				extend: "excelHtml5",
				title: 'Control_gastos_laboratorio',
				className: "btn btn-warning text-inverse-warning",
				attr: {
					"data-tooltip": "Exportar tabla en excel",
					"data-placement": "auto",
					title: "Exportar tabla en excel",
				},
				exportOptions: {
					columns: ":not(.noexp)",
					orthogonal: "export",
				},
				action: newExportAction
			}, {
				text: "Exportar CSV",
				extend: "csvHtml5",
				title: 'Pacientes347',
				className: "btn btn-warning text-inverse-warning",
				attr: {
					"data-tooltip": "Exportar tabla en CSV",
					"data-placement": "auto",
					title: "Exportar tabla en CSV",
				},
				exportOptions: {
					columns: ":not(.noexp)",
					orthogonal: "export",
				},
				action: newExportAction
			}]
		}).container().appendTo($('#buttons'));

		$('[data-table-search]').on('input', function() {
			tabla_liquidaciones.search($(this).val()).draw();
		});


		$('#estado').on('change', function() {
			tabla_liquidaciones.draw();
		});
		$('#fecha_desde').on('change', function() {
			tabla_liquidaciones.draw();
		});
		$('#fecha_hasta').on('change', function() {
			tabla_liquidaciones.draw();
		});
		$('#id_usuario').on('change', function() {
			tabla_liquidaciones.draw();
		});
		$('#id_cliente').on('change', function() {
			tabla_liquidaciones.draw();
		});
	*/
</script>