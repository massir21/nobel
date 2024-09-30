<!DOCTYPE html>
<html lang="es">

<head>
	<base href="" />
	<title><?= SITETITLE ?></title>
	<meta charset="utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta property="og:locale" content="es_ES" />
	<meta property="og:type" content="article" />
	<meta property="og:title" content="" />
	<meta property="og:url" content="<?= base_url() ?>" />
	<meta property="og:site_name" content="<?= SITETITLE ?>" />
	<link rel="canonical" href="<?= base_url() ?>" />
	<link rel="shortcut icon" href="<?= base_url() ?>favicon.ico" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
	<link href="<?= base_url() ?>assets_v5/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
	<link href="<?= base_url() ?>assets_v5/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
	<link href="<?= base_url() ?>assets_v5/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
	<link href="<?= base_url() ?>assets_v5/css/style.bundle.css" rel="stylesheet" type="text/css" />
	<script>
		var hostUrl = "<?= base_url() ?>assets_v5/";
	</script>
	<script src="<?= base_url() ?>assets_v5/plugins/global/plugins.bundle.js"></script>
	<script src="<?= base_url() ?>assets_v5/js/scripts.bundle.js"></script>
	<script src="<?= base_url() ?>assets_v5/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
	<script src="<?= base_url() ?>assets_v5/js/widgets.bundle.js"></script>
	<script src="<?= base_url() ?>assets_v5/js/custom/widgets.js"></script>
	<script src="<?= base_url() ?>assets_v5/js/custom/apps/chat/chat.js"></script>
	<script src="<?= base_url() ?>assets_v5/js/custom/utilities/modals/upgrade-plan.js"></script>
	<script src="<?= base_url() ?>assets_v5/js/custom/utilities/modals/create-app.js"></script>
	<script src="<?= base_url() ?>assets_v5/js/custom/utilities/modals/new-target.js"></script>
	<script src="<?= base_url() ?>assets_v5/js/custom/utilities/modals/users-search.js"></script>
	<script src="<?= base_url() ?>assets_v5/plugins/custom/datatables/datatables.bundle.js"></script>
	<script src="<?= base_url() ?>assets_v5/plugins/custom/select2/es.js" type="text/javascript"></script>
	<script src='<?php echo base_url(); ?>recursos/moment/min/moment-with-locales.min.js'></script>
	<!-- fullcalendar old
	<link href='<?php echo base_url(); ?>recursos/fullcalendar/fullcalendar.min.css' rel='stylesheet' />
	<link href='<?php echo base_url(); ?>recursos/fullcalendar/fullcalendar.print.min.css' rel='stylesheet' media='print' />
	<link href='<?php echo base_url(); ?>recursos/fullcalendar/scheduler.min.css' rel='stylesheet' />
	<script src='<?php echo base_url(); ?>recursos/fullcalendar/jquery-2.2.4.min.js'></script>
	<script src='<?php echo base_url(); ?>recursos/fullcalendar/fullcalendar.min.js'></script>
	<script src='<?php echo base_url(); ?>recursos/fullcalendar/scheduler.min.js'></script>
	<script src="<?php echo base_url(); ?>recursos/fullcalendar/es.js"></script>
	<!-- fullcalendar old -->
	<script>
		var ventanasAbiertas = [];

		function openwindow(tipoVentana, url, ancho, alto) {
			ventanasAbiertas.forEach(function(ventana) {
				if (ventana.tipoVentana === tipoVentana) {
					ventana.close();
				}
			});

			var ventanaActual = window;
			var posicionVentanaActual = {
				x: ventanaActual.screenX || ventanaActual.screenLeft || 0,
				y: ventanaActual.screenY || ventanaActual.screenTop || 0
			};
			var posicion_x = posicionVentanaActual.x + (ventanaActual.innerWidth - ancho) / 2;
			var posicion_y = posicionVentanaActual.y + (ventanaActual.innerHeight - alto) / 2;
			var nuevaVentana = window.open(url, "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
			nuevaVentana.tipoVentana = tipoVentana
			ventanasAbiertas.push(nuevaVentana);

		}
	</script>
	<style>
		.form-switch.form-check-solid .form-check-input:not(:checked) {
			background-color: #807e9a;
		}

		/* Firefox */
		* {
			scrollbar-width: thin !important;
			scrollbar-color: #7E8299 #DFE9EB !important;
		}

		/* Chrome, Edge and Safari */
		*::-webkit-scrollbar {
			height: 10px !important;
			width: 10px !important;
		}

		*::-webkit-scrollbar-track {
			border-radius: 5px !important;
			background-color: #DFE9EB !important;
		}

		*::-webkit-scrollbar-track:hover {
			background-color: #B8C0C2 !important;
		}

		*::-webkit-scrollbar-track:active {
			background-color: #B8C0C2 !important;
		}

		*::-webkit-scrollbar-thumb {
			border-radius: 5px !important;
			background-color: #7E8299 !important;
		}

		*::-webkit-scrollbar-thumb:hover {
			background-color: #3F4254 !important;
		}

		*::-webkit-scrollbar-thumb:active {
			background-color: #5E6278 !important;
		}
		.form-control,
		/*.select2-container--bootstrap5.select2-container--focus:not(.select2-container--disabled) .form-select-solid, .select2-container--bootstrap5.select2-container--open:not(.select2-container--disabled) .form-select-solid */
		.form-select.form-select-solid{
			background-color: #D8d8d8 !important;
		}
	</style>
</head>