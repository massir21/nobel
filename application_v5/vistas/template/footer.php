<div id="kt_app_footer" class="app-footer"  <?=($_SERVER['SERVER_NAME'] != 'extranet.clinicadentalnobel.es') ? ' style="background-color: #1e1e2d;"' : ''?>>
	<div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
		<div class="text-dark order-2 order-md-1">
			<span class="text-muted fw-semibold me-1"><?=date('Y')?>&copy;</span>
			<a href="<?=base_url()?>" target="_blank" class="text-gray-800 text-hover-primary"><?=SITETITLE?></a>
		</div>
		<?php /*
		<ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
			<li class="menu-item">
				<a href="https://keenthemes.com" target="_blank" class="menu-link px-2">About</a>
			</li>
			<li class="menu-item">
				<a href="https://devs.keenthemes.com" target="_blank" class="menu-link px-2">Support</a>
			</li>
			<li class="menu-item">
				<a href="https://1.envato.market/EA4JP" target="_blank" class="menu-link px-2">Purchase</a>
			</li>
		</ul>
		*/ ?>
	</div>
</div>