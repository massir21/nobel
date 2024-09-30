<?php	/*	
		$control = fopen(APPPATH."errors/errores.log","a+");
		if ($control == false) {
				die("No se ha podido crear el archivo de errores 1");
				exit;
		}
		else {
				$fecha = date("d-m-y H:i:s");
				fputs($control,$fecha." - Error en General: ");
				fputs($control,$heading);
				fputs($control," - Mensaje: ".$message."\n");        
				fclose($control);
		}		
?>
<script>_document.location.href="/errores/error.html";</script>
*/ ?>

<!DOCTYPE html>
<html lang="es">
<!--begin::Head-->

<head>
	<title>ERROR</title>
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
	<link href="/assets_v5/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
	<link href="/assets_v5/css/style.bundle.css" rel="stylesheet" type="text/css" />
	<!--end::Global Stylesheets Bundle-->
</head>
<body id="kt_body" class="app-blank bgi-size-cover bgi-position-center bgi-no-repeat" style="background-image: url('/assets_v5/media/auth/bg9-dark.jpg')">
	<div class="d-flex flex-column flex-root" id="kt_app_root">
		<div class="d-flex flex-column flex-center flex-column-fluid">
			<div class="d-flex flex-column flex-center text-center p-10">
                <div class="card card-flush w-lg-650px py-5">
                    <div class="card-body py-15 py-lg-20">
                        <div class="mb-14">
							<a href="/" class="">
								<img alt="Logo" src="/assets_v5/media/logos/custom-2.svg" class="h-40px" />
							</a>
						</div>
                        <h1 class="text-dark fw-bolder mb-3"><?php echo $heading; ?></h1>
                        <h1 class="text-dark fw-bolder mb-3"><?php echo $message; ?></h1>
		
                        <div class="mb-3">
                            <img src="/assets_v5/media/auth/404-error.png" class="mw-100 mh-300px theme-light-show" alt="">
                        </div>
                        <div class="mb-0">
                            <a href="/" class="btn btn-sm btn-primary">Volver</a>
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
</body>
</html>