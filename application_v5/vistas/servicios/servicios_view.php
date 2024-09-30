<?php if (isset($estado)) {
    if ($estado > 0) { ?>
        <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
            <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE GUARDÓ CORRECTAMENTE</div>
            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="fa-times fas fs-3 text-primary"></i>
            </button>
        </div>
<?php }
} ?>
<?php if (isset($borrado)) { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE BORRÓ CORRECTAMENTE</div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="fa-times fas fs-3 text-primary"></i>
        </button>
    </div>
<?php } ?>
<?php setlocale(LC_MONETARY, 'es_ES'); ?>
<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="myTable1">
                </div>
            </div>
        </div>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">
            <table id="myTable1" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th style="display: none;">ID</th>
                        <th>Nombre Servicio</th>
                        <th>Familia</th>
                        <th>P.V.P.</th>
                     <?php /*   <th>Templos</th> */ ?>
                        <th>Max. Desc.</th>
                        <th>Duración</th>
                        
                        <th>Obsoleto</th>
                        <th>Rellamada</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php if (isset($registros)) {
                        if ($registros != 0) {
                            foreach ($registros as $key => $row) { ?>
                                <tr>
                                    <td style="display: none;">
                                        <?php echo $row['id_servicio'] ?>
                                    </td>
                                    <td>
                                        <?php echo $row['nombre_servicio'] ?>
                                    </td>
                                    <td>
                                        <?php echo $row['nombre_familia'] ?>
                                    </td>
                                    <td class="text-end">
                                        <?php echo number_format($row['pvp'], 2, ",", "."); ?>
                                    </td>
                                    <?php
                                    /*
                                    ?>
                                    <td style="text-align: center;">
                                        <?php echo $row['templos'] ?>
                                    </td>
                                    <?php
                                    */
                                    ?>
                                    <td style="text-align: center;">
                                        <?php echo number_format($row['maxdescuento'], 2, ",", "."); ?>%
                                    </td>
                                    <td style="text-align: center;">
                                        <?php echo $row['duracion'] . " min"; ?>
                                    </td>
                                    
                                    <td style="text-align: center;">
                                        <?php if ($row['obsoleto'] == 1) {
                                            echo "<span class='label label-sm label-danger'>Sí</span>";
                                        } else {
                                            echo "No";
                                        } ?>
                                    </td>
                                    <td>
                                        <div class="input-group w-100px">
                                            <input type="number" min="0" data-id_servicio="<?=$row['id_servicio']?>" class="form-control form-control-sm" value="<?=$row['rellamada']?>">
					                        <div class="input-group-append">
                                                <button type="button" class="btn btn-sm btn-icon btn-warning" data-actualizar-rellamada="servicio" data-id="<?=$row['id_servicio']?>" data-bs-toggle="tooltip" aria-label="Guardar valor rellamada" title="Guardar valor rellamada" ><i class="fas fa-save"></i></button>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="text-align: center;">
                                        <a class="btn btn-sm btn-icon btn-warning" href="<?= base_url() ?>servicios/gestion/editar/<?php echo $row['id_servicio'] ?>"><i class="fa-regular fa-pen-to-square"></i></a>
                                    </td>
                                    <td style="text-align: center;">
                                        <button class="btn btn-sm btn-icon btn-danger" onclick="Borrar(<?php echo $row['id_servicio'] ?>);"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                    <?php }
                        }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- END SAMPLE FORM PORTLET-->
</div>

<div class="card shadow-sm mt-5">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5 border-bottom">
        <div class="card-title align-items-end">Gestión de Familias de Servicios</div>
        <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
            <a href="<?php echo base_url(); ?>servicios/familias/nuevo" class="btn btn-primary text-inverse-primary">Añadir familia</a>
        </div>
    </div>
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="myTable2">
                </div>
            </div>
        </div>
    </div>

    <div class="card-body pt-6">
        <div class="table-responsive">
            <table id="myTable2" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th style="display: none;">ID</th>
                        <th>Nombre Familia</th>
                        <th>Rellamada</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php if (isset($registros_familias)) {
                        if ($registros_familias != 0) {
                            foreach ($registros_familias as $key => $row) { ?>
                                <tr>
                                    <td style="display: none;">
                                        <?php echo $row['id_familia_servicio'] ?>
                                    </td>
                                    <td>
                                        <?php echo $row['nombre_familia'] ?>
                                    </td>
                                    <td>
                                        <div class="input-group w-100px">
                                            <input type="number" min="0" data-id_servicio_familia="<?=$row['id_familia_servicio']?>" class="form-control form-control-sm" value="<?=$row['rellamada']?>">
					                        <div class="input-group-append">
                                                <button type="button" class="btn btn-sm btn-icon btn-warning" data-actualizar-rellamada="servicio_familia" data-id="<?=$row['id_familia_servicio']?>" data-bs-toggle="tooltip" aria-label="Guardar valor rellamada" title="Guardar valor rellamada" ><i class="fas fa-save"></i></button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-icon btn-warning" href="<?php echo base_url(); ?>servicios/familias/editar/<?php echo $row['id_familia_servicio'] ?>"><i class="fa-regular fa-pen-to-square"></i></a>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-icon btn-danger" onclick="BorrarFamilia(<?php echo $row['id_familia_servicio'] ?>);"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                    <?php }
                        }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function Borrar(id_servicio) {
        if (confirm("¿Desea borrar el servicio?")) {
            document.location.href = "<?php echo base_url(); ?>servicios/gestion/borrar/" + id_servicio;
        }
        return false;
    }

    function BorrarFamilia(id_familia_servicio) {
        if (confirm("¿Desea borrar la familia?")) {
            document.location.href = "<?php echo base_url(); ?>servicios/familias/borrar/" + id_familia_servicio;
        }
        return false;
    }

    $(document).on('click', '[data-actualizar-rellamada]', function(){
		var tipo = $(this).attr('data-actualizar-rellamada');
        var id = $(this).attr('data-id');
		var rellamada = $('[data-id_'+tipo+'="'+id+'"]').val();
        var formData = new FormData();
		formData.append("id", id);
		formData.append("rellamada", rellamada);
        formData.append("tipo", tipo);
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			method: 'post',
			url: '<?php echo base_url() ?>Servicios/actualizarRellamada',
			data: formData,
			processData: false,
			contentType: false,
			success: function(resp) {
				if (resp == false) {
					Swal.fire({
						title: 'Error',
						willClose: function() {},
					});
				} else {
					Swal.fire({
						title: 'Actualizado',
						willClose: function() {
							
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
    })
</script>