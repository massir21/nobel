<?php $this->load->view($this->config->item('template_dir').'/head');?>
<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
	<script>
		var defaultThemeMode = "light";
		var themeMode;
		if (document.documentElement) {
			if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
				themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
			} else {
				if (localStorage.getItem("data-bs-theme") !== null) {
					themeMode = localStorage.getItem("data-bs-theme");
				} else {
					themeMode = defaultThemeMode;
				}
			}
			if (themeMode === "system") {
				themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
			}
			document.documentElement.setAttribute("data-bs-theme", themeMode);
		}
	</script>
	<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
		<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
			<?php $this->load->view($this->config->item('template_dir') . '/header'); ?>
			<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
				<?php $this->load->view($this->config->item('template_dir') . '/sidebar'); ?>
				<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
					<div class="d-flex flex-column flex-column-fluid">
						<?php // $this->load->view($this->config->item('template_dir') . '/pagetitle'); ?>
						<div id="kt_app_content" class="app-content flex-column-fluid">
							<div id="kt_app_content_container" class="app-container container-fluid pt-5">
								<?php echo $content_view ?>
							</div>
						</div>
					</div>
					<?php $this->load->view($this->config->item('template_dir') . '/footer'); ?>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		/*
		// No está el plugin datepicker
		$.datepicker.regional['es'] = {
			closeText: 'Cerrar',
			prevText: '<Ant',
			nextText: 'Sig>',
			currentText: 'Hoy',
			monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
			dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
			dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
			dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
			weekHeader: 'Sm',
			dateFormat: 'yy-mm-dd',
			firstDay: 1,
			isRTL: false,
			showMonthAfterYear: false,
			yearSuffix: ''
		};
		var datefield = document.createElement("input")
		datefield.setAttribute("type", "date")
		//if browser doesn't support input type="date", initialize date picker widget:
		if (datefield.type != "date") {
			jQuery(function($) { //on document.ready
				$('#fecha').datepicker();
				document.getElementById("fecha").readOnly = true;
				$('#fecha_hasta').datepicker();
				document.getElementById("fecha_hasta").readOnly = true;
			})
		}*/
		$.extend($.fn.dataTable.ext.classes, {
			//sWrapper: "dataTables_wrapper dt-bootstrap4",
			sFilterInput: "form-control    form-control-solid w-auto",
			sLengthSelect: "form-select form-select-solid w-auto",
			//sProcessing: "dataTables_processing card",
			//sPageButton: "paginate_button page-item",
		});
		$.extend($.fn.dataTable.defaults, {
			info: true,
			paging: true,
			ordering: true,
			searching: true,
			stateSave: false,
			processing: false,
			serverSide: false,
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
			dom:
				/*"<'row'" +
								"<'col-sm-6 d-flex align-items-center justify-conten-start'f>" +
								"<'col-sm-6 d-flex align-items-center justify-content-end'>" +
								">" +*/
				"<'table-responsive'tr>" +
				"<'row'" +
				"<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'li>" +
				"<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
				">",
			buttons: {
				buttons: [{
					text: "Exportar",
					extend: "excelHtml5",
					title: $(this).closest('table').attr('data-export'),
					className: "btn btn-sm btn-round btn-info",
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
		});
		$('[data-table-search]').on("keyup", function() {
			//alert()
			var tabladatatable = $(this).attr('data-table-search')
			var table = $('#' + tabladatatable).DataTable();
			table.search(this.value).draw();
		})
		$.fn.dataTable.Buttons.defaults.dom.container.className =
			"dt-buttons flex-wrap";
		var oldExportAction = function(self, e, dt, button, config) {
			if (button[0].className.indexOf("buttons-excel") >= 0) {
				if ($.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)) {
					$.fn.dataTable.ext.buttons.excelHtml5.action.call(
						self,
						e,
						dt,
						button,
						config
					);
				} else {
					$.fn.dataTable.ext.buttons.excelFlash.action.call(
						self,
						e,
						dt,
						button,
						config
					);
				}
			} else if (button[0].className.indexOf("buttons-print") >= 0) {
				$.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
			}
		};
		var newExportAction = function(e, dt, button, config) {
			var self = this;
			var oldStart = dt.settings()[0]._iDisplayStart;
			dt.one("preXhr", function(e, s, data) {
				data.start = 0;
				data.length = 2147483647;
				dt.one("preDraw", function(e, settings) {
					oldExportAction(self, e, dt, button, config);
					dt.one("preXhr", function(e, s, data) {
						settings._iDisplayStart = oldStart;
						data.start = oldStart;
					});
					setTimeout(dt.ajax.reload, 0);
					return false;
				});
			});
			dt.ajax.reload();
		};
		<?php if($this->uri->segment(2) != ''){ ?>
			<?php if($this->uri->segment(2) == 'index'){ ?>
				var currenturl = '<?= base_url() . $this->uri->segment(1)?>';
			<?php }else{ ?>
				var currenturl = '<?= base_url() . $this->uri->segment(1) . '/' . $this->uri->segment(2) ?>';
			<?php } ?>
		<?php }else{?>
			var currenturl = '<?= base_url() . $this->uri->segment(1)?>';
		<?php } ?>
		var menuitem = $('.menu-link[href="' + currenturl + '"]');
		if (menuitem.length > 0) {
			console.log('1-'+currenturl)
			menuitem.addClass('active');
			menuitem.closest('.menu-sub-accordion').addClass('show');
			menuitem.closest('.menu-accordion').addClass('hover show');
		} else {
			var currenturl = '<?= base_url() . $this->uri->segment(1) ?>/gestion';
			var encontrado = 0;
			$(".menu-link[href]").each(function() {
				if ($(this).attr("href").indexOf(currenturl) >= 0 && $(this).hasClass("menu-link") && encontrado == 0) {
					var menuitem = $(this);
					menuitem.addClass('active');
					menuitem.closest('.menu-sub-accordion').addClass('show');
					menuitem.closest('.menu-accordion').addClass('hover show');
					encontrado = 1;
				}
			});
			if (encontrado == 0) {
				var currenturl = '<?= base_url() . $this->uri->segment(1) ?>/<?= $this->uri->segment(2) ?>';
				$(".menu-link[href]").each(function() {
					if ($(this).attr("href").indexOf(currenturl) >= 0 && $(this).hasClass("menu-link") && encontrado == 0) {
						var menuitem = $(this);
						menuitem.addClass('active');
						menuitem.closest('.menu-sub-accordion').addClass('show');
						menuitem.closest('.menu-accordion').addClass('hover show');
						encontrado = 1;
					}
				});
				if (encontrado == 0) {
					var currenturl = '<?= base_url() . $this->uri->segment(1) ?>';
					$(".menu-link[href]").each(function() {
						if ($(this).attr("href").indexOf(currenturl) >= 0 && $(this).hasClass("menu-link") && encontrado == 0) {
							var menuitem = $(this);
							menuitem.addClass('active');
							menuitem.closest('.menu-sub-accordion').addClass('show');
							menuitem.closest('.menu-accordion').addClass('hover show');
							encontrado = 1;
						}
					});
				}
			}
		}
	</script>
	<?php if (isset($citas_agenda)) { ?>
		<script>
			$(document).ready(function() {
				$('#agenda').fullCalendar({
					header: {
						left: 'prev,next week',
						center: 'title',
						//right: 'agendaWeek,agendaDay'
						right: 'month,agendaWeek,agendaDay'
					},
					defaultDate: '<?php echo date("Y-m-d H:i:s"); ?>',
					defaultView: 'agendaDay',
					editable: false,
					events: [
						<?php if ($citas_agenda != 0) {
							foreach ($citas_agenda as $key => $row) { ?> {
									id: <?php echo $row['id_cita']; ?>,
									title: '<?php echo strtoupper($row['cliente']); ?> \n <?php echo strtoupper($row['servicio']); ?> \n <?php echo strtoupper($row['empleado']); ?>',
									start: '<?php echo $row['fecha_inicio_aaaammdd'] . "T" . $row['hora_inicio']; ?>',
									color: '<?php echo $row['color']; ?>',
								},
						<?php }
						} ?>
					],
					eventClick: function(calEvent, jsEvent, view) {
						CitasEditar(calEvent.id);
						// change the border color just for fun
						$(this).css('border-color', 'red');
					}
				});
			});
		</script>
	<?php } ?>
	<script>
		$('#myTable1').DataTable({
			order: [1, "asc"]
		});
		// Remove accented character from search input as well
		$('#myTable1').keyup(function() {
			table.search(jQuery.fn.DataTable.ext.type.search.string(this.value)).draw()
		});
		$('#dietario').DataTable({});
		//27/05/20
		$('#tablaCitasAvisos').DataTable({
			order: [3, "asc"],
		});
		//Fin 27/05/20
		$('#myTable2').DataTable({});
		var myTable3 = $('#myTable3').DataTable({});
		$('#myTable4').DataTable({
			"order": [1, "desc"],
		});
		$('#estadistica_usuarios').DataTable({
			pageLength: 50
		});
		$('#movimientos_caja_table').DataTable({
			order: [1, "desc"],
		});
		$('#templos_table').DataTable({
			"order": [1, "desc"],
		});
		$('#logs').DataTable({});
		$('#logs_carnets').DataTable({
			order: [0, "asc"],
		});
		$('#logs_carnets2').DataTable({
			order: [0, "asc"],
		});
	</script>
	<!-- Modal de Recordatorios -->
	<script src="<?php echo base_url(); ?>recursos/js/recordatorios.js"></script>
	<?php $this->load->view($this->config->item('template_dir') . '/recordatorios_modal_view'); ?>
	<!-- Modal de Citas Espera -->
	<script src="<?php echo base_url(); ?>recursos/js/citas_espera.js"></script>
	<?php $this->load->view($this->config->item('template_dir') . '/citas_espera_modal_view'); ?>
</body>
</html>