<!DOCTYPE html>
<html lang="es">
<!--begin::Head-->
<head>
	<title><?= SITETITLE ?></title>
	<meta charset="utf-8" />
	<meta charset="utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta property="og:locale" content="es_ES" />
	<meta property="og:type" content="article" />
	<meta property="og:title" content="" />
	<meta property="og:url" content="<?= RUTA_WWW ?>" />
	<meta property="og:site_name" content="<?= SITETITLE ?>" />
	<!--begin::Fonts(mandatory for all pages)-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
	<!--end::Fonts-->
	<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
	<link href="<?= base_url() ?>assets_v5/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
	<link href="<?= base_url() ?>assets_v5/css/style.bundle.css" rel="stylesheet" type="text/css" />
	<!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body" class="app-blank bgi-size-cover bgi-position-center bgi-no-repeat">
	<!--begin::Theme mode setup on page load-->
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
	<!--end::Theme mode setup on page load-->
	<!--begin::Root-->
	<div class="d-flex flex-column flex-root" id="kt_app_root">
		<!--begin::Page bg image-->
		<style>
			body {
				background-image: url('<?= base_url() ?>assets_v5/media/auth/bg6.jpg');
			}
			[data-bs-theme="dark"] body {
				background-image: url('<?= base_url() ?>assets_v5/media/auth/bg6-dark.jpg');
			}
		</style>
		<!--end::Page bg image-->
		<!--begin::Authentication - Signup Welcome Message -->
		<div class="d-flex flex-column flex-center flex-column-fluid">
			<!--begin::Content-->
			<div class="d-flex flex-column flex-center text-center p-10">
				<!--begin::Wrapper-->
				<div class="card card-flush w-lg-650px py-5">
					<div class="card-body py-15 py-lg-20">
						<div class="mb-14">
							<a href="<?= base_url(); ?>" class="">
								<img alt="Logo" src="<?= base_url() ?>assets_v5/media/logos/logo-dorado-sm.png"/>
							</a>
						</div>
						<form class="form w-100" novalidate="novalidate" method="post" action="<?php echo base_url(); ?>acceso/validar" id="kt_sign_in_form">
							<div class="text-center mb-11">
								<h1 class="text-dark fw-bolder mb-3">Extranet</h1>
							</div>
							<div class="fv-row mb-8">
								<input type="text" placeholder="Usuario" name="usuario" autocomplete="off" class="form-control bg-transparent" />
							</div>
							<div class="fv-row mb-3">
								<input type="password" placeholder="Password" name="password" autocomplete="off" class="form-control bg-transparent" />
							</div>
							<div class="d-grid mb-10">
								<button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
									<span class="indicator-label">Entrar</span>
									<span class="indicator-progress">Please wait...
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
								</button>
							</div>
						</form>
						<?php if ($error == 1) { ?>
						<div class="alert alert-danger display-hide" style="display: block; text-align: center;">
							Acceso Incorrecto
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		var hostUrl = "<?= base_url() ?>assets_v5/";
	</script>
	<script src="<?= base_url() ?>assets_v5/plugins/global/plugins.bundle.js"></script>
	<script src="<?= base_url() ?>assets_v5/js/scripts.bundle.js"></script>
	<?php /*<script src="<?= base_url() ?>assets_v5/js/custom/authentication/sign-in/general.js"></script>*/ ?>
</body>
</html>