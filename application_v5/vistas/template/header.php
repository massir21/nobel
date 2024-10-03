<div id="kt_app_header" class="app-header" <?=($_SERVER['SERVER_NAME'] != 'extranet.clinicadentalnobel.es') ? ' style="background-color: #1e1e2d;"' : ''?>>
	<div class="app-container container-fluid d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
		<div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Show sidebar menu">
			<div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
				<i class="ki-duotone ki-abstract-14 fs-2 fs-md-1">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>
			</div>
		</div>
		<div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
			<a href="<?= base_url() ?>" class="d-lg-none">
				<img alt="Logo" src="<?= base_url() ?>assets_v5/media/logos/logo-icono-sm.png" class="h-30px" />
			</a>
		</div>
		<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
			<div class="app-header-mobile-drawer">
				<div class="align-items-stretch fw-semibold justify-content-center menu menu-column menu-lg-row menu-rounded my-5 my-lg-0 px-2 w-100" id="kt_app_header_menu" data-kt-menu="true">				
					<div class="display-6 fw-bold menu-item text-gray-600"><?= (isset($pagetitle))?$pagetitle:'' ?></div>
				</div>
			</div>


			<?php /*if (isset($actionstitle)) { ?>
				<div class="d-flex align-items-center gap-2 gap-lg-3" style="z-index:1;">
					<?php foreach ($actionstitle as $key => $action) {
						echo $action;
					} ?>
				</div>
			<?php } */?>

			<div class="app-navbar flex-shrink-0">
				<?php if (isset($actionstitle)) { ?>
					<div class="app-navbar-item align-items-center gap-2 gap-lg-3 d-none d-lg-flex">
						<?php foreach ($actionstitle as $key => $action) {
							echo $action;
						} ?>
					</div>

					<div class="app-navbar-item ms-1 ms-md-3 d-lg-none">
						<div class="btn btn-icon btn-sm btn-warning cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
							<i class="fas fa-cog"></i>
						</div>
						<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold w-100" data-kt-menu="true">
							<?php foreach ($actionstitle as $key => $action) { ?>
								
								<?= $action ?>
							<?php } ?>
						</div>
					</div>
					
				<?php } ?>


				<div class="app-navbar-item ms-1 ms-md-3" id="kt_header_user_menu_toggle">
    <!-- Icono de Inicio -->
	<?php if ( $this->session->userdata('id_perfil') != PERFIL_DOCTOR ){ ?>
    <div class="d-flex align-items-center cursor-pointer symbol symbol-30px symbol-md-40px <?=($_SERVER['SERVER_NAME'] != 'extranet.clinicadentalnobel.es') ? 'text-white':''?>">
        <a href="<?= base_url() ?>site" class="d-flex align-items-center text-decoration-none">
            <span class="ms-2" style ="margin-right: 5px; color: black;">Ir al inicio</span>
	        <i class="fas fa-home fa-lg" style="margin-right: 5px; color: black;"></i> 
        </a>
    </div>
	<?php } ?>

    <!-- Menú de Usuario -->
    <div class="cursor-pointer symbol symbol-30px symbol-md-40px <?=($_SERVER['SERVER_NAME'] != 'extranet.clinicadentalnobel.es') ? 'text-white':''?>" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
        <?php echo $this->session->userdata('nombre_usuario') ?> 
        <?php $genero = getgender($this->session->userdata('nombre_usuario'));
        if($genero == 'female' ){
            $img = '300-12.jpg';
        }elseif($genero == 'male'){
            $img = '300-1.jpg';
        }else{
            $img = 'blank.png';
        } ?>
        <img src="<?= base_url() ?>assets_v5/media/avatars/<?=$img?>" class="ms-2" alt="user" />
    </div>
    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
        <div class="menu-item px-5">
            <a href="<?= base_url() ?>acceso/desconectar" class="menu-link px-5">Cerrar sesión</a>
        </div>
    </div>
</div>
			</div>
		</div>
	</div>
</div>