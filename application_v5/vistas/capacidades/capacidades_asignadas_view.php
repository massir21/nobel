<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title w-100 align-items-end">
            Capacidades (servicios) Asignadas a <?php echo $usuario[0]['nombre'] . " " . $usuario[0]['apellidos']; ?>
        </div>
    </div>
    <div class="card-body pt-6">
        <form id="form" action="<?php echo base_url(); ?>capacidades/gestion/guardar/<?php echo $usuario[0]['id_usuario'] ?>" role="form" method="post" name="form">
            <div class="row mb-5 border-bottom">
                <?php if (count($familias) != 0) {
                    foreach ($familias as $key => $familia) { ?>
                        <div class="col-md-6 mb-3">
                            <div class="card p-3 border border-2">
                                <div class="d-flex justify-content-between align-items-center border-bottom border-2">
                                    <div class="form-check form-check-solid form-switch form-check-custom fv-row mb-2 p-2 ">
                                        <input class="form-check-input w-45px h-30px" type="checkbox" id="familia<?= $familia['id_familia']; ?>" name="familia" value="<?= $familia['id_familia']; ?>">
                                        <label class="form-label ms-5 fs-3" for="familia<?= $familia['id_familia']; ?>"><?= $familia['nombre_familia']; ?></label>
                                    </div>
                                    <button type="button" class="accordion-icon btn btn-icon btn-secondary btn-sm collapsed" data-bs-toggle="collapse" data-bs-target="#collapse<?= $familia['id_familia']; ?>" aria-expanded="true">
                                        <i class="fas fa-expand"></i></button>
                                </div>

                                <div id="collapse<?= $familia['id_familia']; ?>" class="collapse show">
                                    <?php foreach ($familias_servicios[$familia['id_familia']] as $k => $row) { ?>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="servicios<?php echo $row['id_servicio']; ?>" name="servicios[]" data-familia="<?= $row['id_familia_servicio']; ?>" value="<?= $row['id_servicio'] ?>" <?= (in_array($row['id_servicio'], $servicios_usuario)) ? 'checked=' : ""; ?>>
                                            <label class="form-label ms-5 fs-5 text-muted" for="servicios<?php echo $row['id_servicio']; ?>"><?php echo $row['nombre_familia'] . " - " . $row['nombre_servicio']; ?> <?= $row['id_servicio'] ?></label>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                        </div>
                <?php }



                    /*foreach ($servicios as $key => $row) {
                        $sw = 0;
                        
                        if ($servicios_usuario != 0) {
                            foreach ($servicios_usuario as $key => $servicio) {
                                if ($row['id_servicio'] == $servicio['id_servicio']) {
                                    $sw = 1;
                                }
                            }
                        } ?>
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                                <input class="form-check-input w-45px h-30px" type="checkbox" id="servicios<?php echo $row['id_servicio']; ?>" name="servicios[]" value="<?php echo $row['id_servicio']; ?>"  <?php if ($sw == 1) {echo "checked";} ?>>
                                <label class="form-label ms-5 fs-3" for="servicios<?php echo $row['id_servicio']; ?>"><?php echo $row['nombre_familia'] . " - " . $row['nombre_servicio']; ?></label> 
                            </div>
                        </div>
                    <?php }*/
                } ?>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary text-inverse-primary" type="submit">Guardar</button>
                    <?php //<input name="btn_agregar" type="submit" value="Guardar" class="btn btn-primary text-inverse-primary" /> 
                    ?>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    function Borrar(id_usuario, id_capacidad) {
        if (confirm("¿Desea borrar el capacidad indicado?")) {
            document.location.href = "<?php echo base_url(); ?>capacidades/gestion/borrar/" + id_usuario + "/" + id_capacidad;
        }
        return false;
    }

    $(document).ready(function() {
  // Función para manejar cambios en los checkboxes
  function handleCheckboxChange(valorFamilia) {
    const checkboxesRelacionados = $(`input[data-familia="${valorFamilia}"]`);
    const familiaCheckbox = $(`input[name="familia"][value="${valorFamilia}"]`);
    const todosMarcados = checkboxesRelacionados.length === checkboxesRelacionados.filter(':checked').length;

    if (this.type === 'checkbox' && this.name === 'familia') {
      checkboxesRelacionados.prop('checked', this.checked);
    } else {
      if (todosMarcados) {
        familiaCheckbox.prop('checked', true);
      } else {
        familiaCheckbox.prop('checked', false);
      }
    }
  }

  // Verifica el estado al cargar la página
  $('input[data-familia]').each(function() {
    handleCheckboxChange.call(this, $(this).attr('data-familia'));
  });

  // Agrega un evento change a todos los checkboxes
  $('input[data-familia], input[name="familia"]').change(function() {
    handleCheckboxChange.call(this, $(this).attr('data-familia') || $(this).val());
  });
});

</script>