<div class="row">
	<div class="col-md-5">
		<b>Empleado</b>: <?php echo $liquidacion[0]['empleado'] ?>
		<?php /* <br><b>Desde</b>: <?php echo fechaES($liquidacion[0]['fecha_desde']) ?> */ ?>
		<br><b>Fecha</b>: <?php echo fechaES($liquidacion[0]['fecha_hasta']) ?>
		<br><b>Mes de liquidación</b>: <?=($liquidacion[0]['mes'] != '' && $liquidacion[0]['mes'] != '0000-00-00') ? mesletra(date('m', strtotime($liquidacion[0]['mes']))).' de '.date('Y', strtotime($liquidacion[0]['mes'])) : ' - '; ?>
	</div>
	<div class="col-md-5">
		<b>Total a percibir:</b>
		<h1 class="text-gray-600 fw-bold fs-2hx"><?= euros($liquidacion[0]['total']) ?></h1>
	</div>
	<div class="col-md-2">
		<div id="buttons_tags" class="text-end"></div>
	</div>
</div>

<ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
	<li class="nav-item">
		<a class="nav-link active" data-bs-toggle="tab" href="#tab_comisiones">Comisiones</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-bs-toggle="tab" href="#tab_citas">Citas</a>
	</li>
</ul>

<div class="tab-content" id="TabContent">
	<div class="tab-pane fade in active show" id="tab_comisiones" role="tabpanel">
		<h3>Comisiones</h3>
		<div class="table-responsive mb-5">
			<table id="tabla_comisiones" class="table table-striped tableexcel" data-rowonetitle="Comisiones" data-sheetname="Comisiones">
				<thead class="">
					<tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
						<th>ID</th>
						<th>Aplica sobre</th>
						<th>Tipo comisión</th>
						<th>Comisión</th>
						<th>PVP acumulado</th>
						<th>Comision (€)</th>			
						</tr>
				</thead>
				<tbody class="text-gray-700 fw-semibold">
					<?php if(is_array($comisiones_liquidacion)) {
						foreach ($comisiones_liquidacion as $key => $value) {
							if ($value['pvpacumulado'] > 0 || $value['tipo'] == 'fijo') { 
								$pvpacumulado = ($value['tipo'] == 'fijo') ? ($value['total_comision'] / $value['comision']).' citas' : $value['pvpacumulado'];?>
								<tr class="">
									<td class="col_id">
										<?= $value['id_comision'] ?>
									</td>
									<td><?= ($value['id_item'] > 0) ? $value['nombre_item'] : (($value['id_familia_item'] > 0) ? $value['nombre_familia'] : $value['item']); ?></td>
									<td>
										<?= $value['tipo'] ?>
										<?php if ($value['tipo'] == 'tramo') {
											echo "<br>".$value['importe_desde'] . ' -> ' . $value['importe_hasta'];
										} ?>
									</td>
									<td><?= $value['comision'] ?></td>
									<td class="acumularo"><?= $pvpacumulado ?></td>
									<td>
										<?= $value['total_comision'] ?>
									</td>
								</tr>
							<?php }
						}
					} ?>
				</tbody>

			</table>
		</div>
	</div>

	<div class="tab-pane fade" id="tab_citas" role="tabpanel">
		<h3>Citas</h3>
		<div class="table-responsive mb-5">
			<table id="tabla_citas" class="table table-striped tableexcel" data-rowonetitle="Citas" data-sheetname="Citas">
				<thead>
					<tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
						<th>Fecha</th>
						<th>Servicio</th>
						<th >PVP(€)</th>
						<th >Dto</th>
						<th >Dto P.</th>
						<th >Gastos L.</th>
						<th >Com. F.</th>
						<th>Total</th>
						<?php if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 3) { ?>
							<th></th>
						<?php } ?>		
					</tr>
				</thead>
				<tbody class="text-gray-700 fw-semibold">
					<?php foreach ($citas_liquidacion as $i => $value) { ?>
						<tr>
							<td><?= $value['fecha_cita'] ?></td>
							<td><?= $value['servicio'] ?> <?= $value['familia'] ?> <?= $value['cliente'] ?><br><span class="text-primary">Diente <?= ($value['dientes'] != '') ? $value['dientes'] : '-' ?></span></td>
							<td><?= $value['pvp'] ?></td>
							<td><?= $value['dto'] ?></td>
							<td><?= $value['dtop'] ?></td>
							<?php
									$palabras_clave = array('implante', 'corona', 'sobredentadura', 'protesis', 'hueso', 'membrana', 'chincheta', 'ferula', 'entrada', 'laboratorio');
									$valor_servicio = $value['servicio'];
									$alertbg = '';
									$show = '';
									foreach ($palabras_clave as $palabra) {
										if (str_contains($valor_servicio, $palabra)) {
											if ($value['gastos_lab'] > 0) {
												$alertbg = '';
												$show = '';
											}
											else
											{
                                                 $alertbg='border: 6px solid red;';
                                                 $show='id="gastosL_'.$value['id_liquidacion_cita'].'" class="gastosLab_rojo" data-id="'.$value['id_liquidacion_cita'].'"';
											}
											break;
										}
										if (str_contains($valor_servicio, 'osteointegrado')) {
											$alertbg = '';
											$show = '';
										}
									} 
     								?>
							<td style="<?= $alertbg ?> "><span <?= $show ?>><?= $value['gastos_lab'] ?></span></td>
							<td><?= $value['com_financiacion'] ?></td>
							<td><?= $value['total'] ?></td>
							<?php if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 3) { ?>
									<td>
										<button type="button" class="btn btn-sm btn-icon btn-danger" data-borrar-cita-liquidacion="<?= $value['id_liquidacion_cita'] ?>" data-bs-toggle="tooltip" title="Eliminar cita de la liquidación"><i class="fa-solid fa-trash"></i></button>
									</td>
							<?php } ?>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>


<style>
	.swal2-container.swal2-center.swal2-backdrop-show {
		z-index: 99999;
	}
</style>


<script>
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
		return new bootstrap.Tooltip(tooltipTriggerEl)
	})
	$('[data-bs-toggle="tab"]').on('shown.bs.tab', function(event) {
		$('.dataTable').DataTable().columns.adjust().responsive.recalc();
	});

	function getHeaderNames(table) {
		var header = $(table).DataTable().columns().header().toArray();
		var names = [];
		header.forEach(function(th) {
			names.push($(th).html());
		});
			
		return names;
	}
  
	function buildCols(data) {
		var cols = '<cols>';
		for (i=0; i<data.length; i++) {
		colNum = i + 1;
		cols += '<col min="' + colNum + '" max="' + colNum + '" width="20" customWidth="1"/>';
		}
		cols += '</cols>';
		return cols;
	}
  
	function buildRow(data, rowNum, styleNum) {
		var style = styleNum ? ' s="' + styleNum + '"' : '';
		var row = '<row r="' + rowNum + '">';
		for (i=0; i<data.length; i++) {
		colNum = (i + 10).toString(36).toUpperCase();  // Convert to alpha
		var cr = colNum + rowNum;
		row += '<c t="inlineStr" r="' + cr + '"' + style + '>' +
				'<is>' +
					'<t>' + data[i] + '</t>' +
				'</is>' +
				'</c>';
		}
		row += '</row>'; 
		return row;
	}
  
	function getTableData(table, title) {
		var header = getHeaderNames(table);
		var table = $(table).DataTable();
		var rowNum = 1;
		var mergeCells = '';
		var ws = '';
		ws += buildCols(header);
		ws += '<sheetData>';
		if (title.length > 0) {
		ws += buildRow([title], rowNum, 51);
		rowNum++;
		mergeCol = ((header.length - 1) + 10).toString(36).toUpperCase();
		mergeCells = '<mergeCells count="1">'+
			'<mergeCell ref="A1:' + mergeCol + '1"/>' +
					'</mergeCells>';
		}                
		ws += buildRow(header, rowNum, 2);
		rowNum++;    
		table.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
		var data = this.data();
		ws += buildRow(data, rowNum, '');
		rowNum++;
		} );
		ws += '</sheetData>' + mergeCells;
		return ws;
	}
  
	function setSheetName(xlsx, name) {
		if (name.length > 0) {
		var source = xlsx.xl['workbook.xml'].getElementsByTagName('sheet')[0];
		source.setAttribute('name', name);
		}
	}
	<?php $xmlversion = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'; ?>

	function addSheet(xlsx, table, title, name, sheetId) {
		var source = xlsx['[Content_Types].xml'].getElementsByTagName('Override')[1];
		var clone = source.cloneNode(true);
		clone.setAttribute('PartName','/xl/worksheets/sheet' + sheetId + '.xml');
		xlsx['[Content_Types].xml'].getElementsByTagName('Types')[0].appendChild(clone);
		var source = xlsx.xl._rels['workbook.xml.rels'].getElementsByTagName('Relationship')[0];
		var clone = source.cloneNode(true);
		clone.setAttribute('Id','rId3');
		clone.setAttribute('Target','worksheets/sheet' + sheetId + '.xml');
		xlsx.xl._rels['workbook.xml.rels'].getElementsByTagName('Relationships')[0].appendChild(clone);
		var source = xlsx.xl['workbook.xml'].getElementsByTagName('sheet')[0];
		var clone = source.cloneNode(true);
		clone.setAttribute('name', name);
		clone.setAttribute('sheetId', sheetId);
		clone.setAttribute('r:id','rId3');
		xlsx.xl['workbook.xml'].getElementsByTagName('sheets')[0].appendChild(clone);
		var newSheet = '<?=$xmlversion?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" xmlns:x14ac="http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac" mc:Ignorable="x14ac">'+
		getTableData(table, title) +
		'</worksheet>';
		xlsx.xl.worksheets['sheet' + sheetId + '.xml'] = $.parseXML(newSheet);
	}


	var table_comisiones = $('#tabla_comisiones').DataTable({
		info: true,
        paging: true,
        ordering: true,
        searching: true,
        stateSave: false,
        processing: true,
        scrollX: true,
        autoWidth: false,
        order: [0, "desc"],
        pageLength: 50,
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Todos"],
        ],
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
		buttons: [
			{
				extend: 'excelHtml5',
				text: 'Excel',
				title: 'Comisiones',
				customize: function( xlsx ) {
					setSheetName(xlsx, 'Comisiones');
					addSheet(xlsx, '#tabla_citas', 'Citas', 'Citas', '2');
				}
			}
		],
		drawCallback: function(settings) {
			$('#tabla_comisiones').DataTable().columns.adjust().responsive.recalc();
		},
		initComplete: function(){
			
		}

	});

	<?php 
	$titulo = $liquidacion[0]['empleado'].'_'.$liquidacion[0]['fecha_hasta'];
	$titulofin = limpiar_string($titulo);
	$titulofin = str_replace(' ','_', $titulofin);
	?>

	var buttons = new $.fn.dataTable.Buttons(table_comisiones, {
        buttons: [{
            extend: 'excelHtml5',
			text: 'Excel',
			title: '<?php echo $titulofin ?>',
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
			customize: function( xlsx ) {
					setSheetName(xlsx, 'Comisiones');
					addSheet(xlsx, '#tabla_citas', 'Citas', 'Citas', '2');
				}
        }]
    }).container().appendTo($('#buttons_tags'));
    $('.gastosLab_rojo').each(function(){
    	var span=$(this);
        var id_liquidacion_cita = span.attr("data-id");
        $.ajax({
            url:  '<?php echo base_url(); ?>Liquidaciones/cargar_comentario',
            type: 'POST',
            datatype: "json",
            data: {
                id_liquidacion_cita : id_liquidacion_cita,
            }, 
            success: function(response) {
                span.attr('data-bs-toggle','tooltip');
                span.attr('title','Motivo: '+ response.comentarios); 
            }  
        }); 
    });
	$('#tabla_citas').DataTable({
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
	});

	$(document).on('click', '[data-borrar-cita-liquidacion]', function() {
		var tr = $(this).closest('tr');
		var id_liquidacion_cita = $(this).attr('data-borrar-cita-liquidacion');
		var id_liquidacion = <?=$liquidacion[0]['id_liquidacion']?>;
		Swal.fire({
			title: 'Eliminar la cita de la liquidación',
			html: `¿Estas seguro?`,
			showCancelButton: true,
			confirmButtonText: 'Si, eliminar',
			showLoaderOnConfirm: true
		}).then((result) => {
			if (result.value) {
				var formData = new FormData();
				formData.append("id_liquidacion_cita", id_liquidacion_cita);
				formData.append("id_liquidacion", id_liquidacion);
				$.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					method: 'post',
					url: '<?php echo base_url() ?>Liquidaciones/borrarCitaDeLiquidacion',
					data: formData,
					processData: false,
					contentType: false,
					success: function(resp) {
						if (resp == false) {
							Swal.fire({
								title: 'Error',
								type: 'error',
								willClose: function() {},
							});
						} else {
							$('#modal-liquidacion .modal-body').slideUp();
							Swal.fire({
								title: 'Borrado',
								html: 'Se volverá a cargar el detalle de la liquidación',
								type: 'success',
								willClose: function() {
									var url = '<?= base_url() ?>Liquidaciones/ver_detalle/' + id_liquidacion;
									$('#modal-liquidacion .modal-title').html('Detalle de la liquidacion');
									$('#modal-liquidacion .modal-body').html('<i class="fas fa-sync fa-spin"></i>');
									$('#modal-liquidacion').modal('show');
									$.get(url, function(data) {
										$('#modal-liquidacion .modal-body').html(data);
									});
									$('#modal-liquidacion .modal-body').slideDown();
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
			}
		})
	})

</script>



