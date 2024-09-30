// ESTRUCTURA DE LA PÁGINA
<div class="card card-flush">
	<div class="card-header">
		<div class="card-title w-100 align-items-end">
		</div>
	</div>
	<div class="card-body pt-6">
	</div>
</div>
// FORMULARIOS TITLE
<form id="form_estadisticas" action="<?php echo base_url(); ?>estadisticas/codigo_tienda_online" role="form" method="post" name="form_estadisticas">
	<div class="card-title w-100 align-items-end">
		<div class="w-auto">
			<label for="" class="form-label">Fecha desde</label>
			<input type="date" id="fecha" name="fecha_desde" value="<?= (isset($fecha_desde)) ? $fecha_desde : '' ?>" class="form-control form-control-solid w-auto" placeholder="Fecha desde" required />
		</div>
		<div class="w-auto ms-3">
			<label for="" class="form-label">Fecha hasta</label>
			<input type="date" id="fecha_hasta" name="fecha_hasta" value="<?= (isset($fecha_hasta))?$fecha_hasta: ''?>" class="form-control form-control-solid w-auto" placeholder="Fecha hasta" required />
		</div>
		<div class="w-auto ms-3">
			<label for="" class="form-label">Tipo Pago:</label>
			<select name="tipo_pago" id="tipo_pago" class="form-select form-select-solid w-auto">
				<option value="">Todos</option>
				<option value="tarjeta">Tarjeta</option>
				<option value="templos">Templos</option>
			</select>
		</div>
		<div class="w-auto ms-3">
			<label for="" class="form-label">Empleado:</label>
			<select name="id_usuario" id="id_usuario" data-control="select2" class="form-select form-select-solid w-auto">
				<option value="0">Todos los empleados</option>
				<?php if (isset($empleados)) {
					if ($empleados != 0) {
						foreach ($empleados as $key => $row) { ?>
							<option value='<?php echo $row['id_usuario']; ?>' <?php if (isset($id_usuario)) {if ($row['id_usuario'] == $id_usuario) {echo "selected";}} ?>>
								<?php echo strtoupper($row['apellidos'] . ", " . $row['nombre'] . " (" . $row['nombre_centro'] . ")"); ?>
							</option>
						<?php }
					}
				} ?>
			</select>
		</div>
		<div class="w-auto ms-3">
        	<label for="" class="form-label">Centro:</label>
            <select name="id_centro" id="id_centro" class="form-select form-select-solid w-auto">
            <option value="">Todos</option>
                <?php if (isset($centros)) {
                    if ($centros != 0) {
                        foreach ($centros as $key => $row) {
                            if ($row['id_centro'] > 1) { ?>
                                <option value='<?php echo $row['id_centro']; ?>' <?php if (isset($id_centro)) {if ($row['id_centro'] == $id_centro) {echo "selected";}} ?>>
                                    <?php echo $row['nombre_centro']; ?>
                                </option>
                            <?php }
                        }
                    }
                } ?>
            </select>
        </div>
		<div class="w-auto ms-3">
            <label for="" class="form-label">Cliente:</label>
            <select name="id_cliente" id="id_cliente" class="form-select form-select-solid" data-placeholder="Elegir ..."></select> 
            <script type="text/javascript">
                $("#id_cliente").select2({
                    language: "es",
                    minimumInputLength: 4,
                    ajax: {
                        delay: 0,
                        url: function (params) {
                            return '<?php echo RUTA_WWW; ?>/clientes/json/'+ params.term;
                        },
                        dataType: 'json',
                        processResults: function (data) {
                            return {
                                results: data
                            };
                        }
                    }
                });    
            </script>
        </div>
		<div class="w-auto ms-3">
            <label for="" class="form-label">Tipo Pago:</label>
            <select name="tipo_pago" id="tipo_pago" class="form-select form-select-solid w-auto">
                <option value="">Todos</option>
                <option value="tarjeta">Tarjeta</option>
                <option value="templos">Templos</option>
			</select>
        </div>
		<div class="w-auto  ms-3">
			<button type="submit" class="btn btn-info btn-icon text-inverse-info"><i class="fas fa-search"></i></button>
		</div>
	</div>
</form>
// ESTRUCTURA DE LA TABLA
<div class="table-responsive">
	<table id="logs" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
		<thead class="">
			<tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
			</tr>
		</thead>
		<tbody class="text-gray-700 fw-semibold"></tbody>
		<tfoot></tfoot>
	</table>
</div>
// BOTÓN de CÓDIGO
<a class="btn btn-secondary btn-sm btn-text d-inline-flex align-items-center" href="<?php echo base_url(); ?>carnets/detalle/gestion/<?php echo $row['id_carnet']; ?>" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Ver detalle del Carnet"><i class="fas fa-id-card"></i> <?php echo $row['codigo']; ?></a>
// BOTÓN de CLIENTES
<a class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6" href="<?php echo base_url(); ?>clientes/historial/ver/<?php echo $row['id_cliente']; ?>"><?php echo $row['nombre'] . " " . $row['apellidos'] ?></a>
// BOTON CREAR
<a href="#" class="btn btn-primary text-inverse-primary">Nuevo</a>
// BOTON DE EDITAR
<a class="btn btn-sm btn-icon btn-warning" href="<?php echo base_url(); ?>clientes/gestion/editar/<?php echo $row['id_cliente'] ?>"><i class="fa-regular fa-pen-to-square"></i></a>
// BOTON DE INFO
<a class="btn btn-sm btn-icon btn-warning" href=""><i class="fas fa-eye"></i></a>
// BOTÓN BORRAR
<button class="btn btn-sm btn-icon btn-danger"><i class="fa-solid fa-trash"></i></button>
// LINEA IMPORTANTE
<span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6"></span>
// SUBLINEA 
<span class="text-muted fw-semibold text-muted d-block fs-7"></span>