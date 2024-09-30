<div id="kt_app_toolbar" class="app-toolbar py-3">
	<?php if (isset($pagetitle)) { ?>
		<div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack justify-content-between">
			<?php /*<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
				<h1 class="page-heading d-flex text-dark fw-bold flex-column justify-content-center my-0"><?= $pagetitle ?></h1>
				<?php $url_actual = current_url();
				$segmentos_url = explode('/', uri_string());
				$breadcrumb = '<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-5 my-0 pt-1">';
				$breadcrumb .= '<li class="breadcrumb-item text-muted">
					<a href="' . base_url() . 'site" class="text-muted text-hover-primary">Panel de control</a></li>';
				$enlace_actual = '';
				foreach ($segmentos_url as $segmento) {
					if ($enlace_actual == '') {
						$enlace_actual .= $segmento;
					} else {
						$enlace_actual .= '/' . $segmento;
					}
					$breadcrumb .= '<li class="breadcrumb-item">
					<span class="bullet bg-gray-400 w-6px"></span>
				</li><li class="breadcrumb-item text-muted">
					<a href="' . base_url($enlace_actual) . '" class="breadcrumb-item text-muted">' . ucfirst($segmento) . '</a></li>';
				}
				$breadcrumb .= '</ul>';
				echo $breadcrumb;
				?>
			</div>
			<?php */if (isset($actionstitle)) { ?>
				<div class="d-flex align-items-center gap-2 gap-lg-3">
					<?php foreach ($actionstitle as $key => $action) {
						echo $action;
					} ?>
				</div>
			<?php } ?>
			<?php /*<div class="d-flex align-items-center gap-2 gap-lg-3">
			<!--begin::Secondary button-->
			<a href="#" class="btn btn-sm fw-bold bg-body btn-color-gray-700 btn-active-color-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">Rollover</a>
			<!--end::Secondary button-->
			<!--begin::Primary button-->
			<a href="#" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_new_target">Add Target</a>
			<!--end::Primary button-->
		</div>  */ ?>
		</div>
	<?php } ?>
</div>