<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
	<div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
		<a href="<?= SITETITLE ?>" class="">
			<img alt="Logo" src="<?= base_url() ?>assets_v5/media/logos/logo-dorado-sm.png" class="h-65px" />
		</a>
		<div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
			<i class="ki-duotone ki-double-left fs-2 rotate-180">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</div>
	</div>
	<!--begin::sidebar menu-->
	<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
		<!--begin::Menu wrapper-->
		<div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
			<!--begin::Menu-->
			<div class="menu menu-column menu-rounded menu-sub-indention px-3" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
				<?php if ($this->session->userdata('id_perfil') == 6) { ?>
					<div data-kt-menu-trigger="click" class="menu-item here show menu-accordion">
						<!--begin:Menu link-->
						<span class="menu-link">
							<i class="ki-duotone ki-calendar-8 fs-2">
								<span class="path1"></span>
								<span class="path2"></span>
								<span class="path3"></span>
								<span class="path4"></span>
								<span class="path5"></span>
								<span class="path6"></span>
							</i>
							<span class="menu-title">Agenda</span>
							<span class="menu-arrow"></span>
						</span>
						<!--end:Menu link-->
						<div class="menu-sub menu-sub-accordion">
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link active" href="../../demo1/dist/index.html">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Default</span>
								</a>
								<!--end:Menu link-->
							</div>
						</div>
					</div>
					<a href="<?php echo base_url(); ?><?php echo 'agenda/prueba_doctor'; ?>" class="nav-link ">
						<span class="title"><?php echo 'Agenda' ?></span> </a> </li>
					<li class="nav-item">
						<a href="<?php echo base_url(); ?><?php echo 'clientes'; ?>" class="nav-link ">
							<span class="title"><?php echo 'Clientes' ?></span> </a>
					</li>
				<?php } else { ?>
					<?php if (isset($modulos)) {
						// ... Verificamos que modulos padre deben estar activos,
						// porque al meno hay algun hijo.
						if ($modulos != 0) {
							$padres = array("Agenda", "Carnets Templos", "Clientes", "Dietario", "Empleados", "Estadísticas", "Productos", "Master", "Tareas");
							$iconos = array("Agenda" => "calendar", "Carnets Templos" => "two-credit-cart", "Clientes" => "people", "Dietario" => "calendar-8", "Empleados" => "profile-user", "Estadísticas" => "chart-simple-3", "Productos" => "cube-2", "Master" => "element-11", "Tareas" => "row-horizontal");
							foreach ($padres as $p) {
								$menu[$p] = "";
							}
							foreach ($modulos as $key => $row) {
								foreach ($padres as $p) {
									if ($row['padre'] == $p) {
										$menu[$p] = $p;
									}
								}
							}
						}
						foreach ($padres as $p) {
							if ($menu[$p] == $p AND $p!="Carnets Templos" ) {
								$nombre_opcion=$p;
								if ($p=="Clientes"){
									$nombre_opcion="Pacientes";
								}
								?>
								<div data-kt-menu-trigger="click" class="menu-item here menu-accordion">
									<span class="menu-link">
										<span class="menu-icon">
											<i class="ki-outline ki-<?= $iconos[$p]; ?> fs-2"></i>
										</span>
										<span class="menu-title"><?php echo $nombre_opcion; ?></span>
										<span class="menu-arrow"></span>
									</span>
									<div class="menu-sub menu-sub-accordion">
										<?php if (isset($modulos)) {
											if ($modulos != 0) {
												//$no_modulos = ['Comparativa', 'Cajas Regalo', 'Carnets', 'Empleados', 'Recepcionistas', 'Pago Intercentros','Productos','Servicios','Venta Carnets','Histórico citas','Modificación de carnets','Exportar CSV','Duplicados CSV','Facturacion'];
												$no_modulos = ['Comparativa', 'Cajas Regalo', 'Carnets', 'Empleados', 'Recepcionistas', 'Pago Intercentros', 'Productos', 'Servicios', 'Venta Carnets', 'Modificación de carnets', 'Exportar CSV', 'Duplicados CSV', 'Facturacion', 'Extra', 'Agenda Doctor'];
												foreach ($modulos as $key => $row) {
													if (!in_array($row['nombre_modulo'], $no_modulos)) {
														if ($row['padre'] == $menu[$p]) { ?>
															<?php if ($row['id_modulo'] == 43) { ?>
																<?php if ($this->session->userdata('id_centro_usuario') == 6 || $this->session->userdata('id_centro_usuario') == 1) { ?>
																	<div class="menu-item">
																		<a class="menu-link" href="<?php echo base_url(); ?><?php echo $row['url']; ?>">
																			<span class="menu-bullet">
																				<span class="bullet bullet-dot"></span>
																			</span>
																			<span class="menu-title"><?php echo $row['nombre_modulo'] ?></span>
																		</a>
																	</div>
																<?php } ?>
															<?php } elseif ((($row['nombre_modulo'] != 'Centros') && ($row['nombre_modulo'] != 'Servicios')) || (($row['nombre_modulo'] == 'Centros') && ($row['padre'] != 'Estadísticas')) || (($row['nombre_modulo'] == 'Servicios') && ($row['padre'] != 'Estadísticas')) || (($row['nombre_modulo'] == 'Extra') && (($this->session->userdata('id_perfil') == 3) || ($this->session->userdata('id_perfil') == 0)))) { ?>
																<div class="menu-item">
																	<a class="menu-link" href="<?php echo base_url(); ?><?php echo $row['url']; ?>">
																		<span class="menu-bullet">
																			<span class="bullet bullet-dot"></span>
																		</span>
																		<span class="menu-title"><?php echo $row['nombre_modulo'] ?></span>
																	</a>
																</div>
															<?php }
														}
													}
												}
											}
										} ?>
									</div>
								</div>
							<?php }
						}
					}
				} ?>
			</div>
		</div>
	</div>
	<?php /*
	<div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">
		<a href="https://preview.keenthemes.com/html/metronic/docs" class="btn btn-flex flex-center btn-custom btn-primary overflow-hidden text-nowrap px-0 h-40px w-100" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="200+ in-house components and 3rd-party plugins">
			<span class="btn-label">Docs & Components</span>
			<i class="ki-duotone ki-document btn-icon fs-2 m-0">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</a>
	</div>
	*/ ?>
</div>