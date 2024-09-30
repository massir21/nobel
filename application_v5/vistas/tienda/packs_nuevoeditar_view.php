<div class="card card-flush">
    <div class="card-body pt-6">
        <?php if ($accion == "nuevo") {
            $actionform = base_url() . 'tienda/guardar_pack';
        } else {
            $actionform = base_url() . 'tienda/actualizar_pack/'.$registros[0]['id_pack'];
        } ?>
        <form id="form" action="<?php echo $actionform; ?>" role="form" method="post" name="form">
            <div class="row mb-5 border-bottom">
                <div class="col-md-6 col-lg-4 mb-5">
                    <label class="form-label"><b>Nombre Pack</b></label>
                    <input name="nombre_pack" class="form-control form-control-solid" type="text" value="<?=(isset($registros))?$registros[0]['nombre_pack']:''?>" placeholder="" required />
                </div>
                <div class="col-md-6 col-lg-4 mb-5">
                    <label class="form-label">Link a la encuesta del servicio (ficha en tienda online)</label>
                    <input name="link_encuesta" class="form-control form-control-solid" type="text" value="<?=(isset($registros))?$registros[0]['link_encuesta']:''?>" placeholder="" />
                </div>
                <div class="col-md-6 col-lg-2 mb-5">
                    <label class="form-label">ID Tienda
                        <i class="fa fa-question-circle ms-3" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Importante: Los productos de la tienda pueden ser simples o con variaciones, si tienen variación el id que hay que asociar es variation_id, sino product_id."></i>
                    </label>
                    <input name="id_tienda" class="form-control form-control-solid" type="text" value="<?=(isset($registros))?$registros[0]['id_tienda']:''?>">
                </div>
            </div>
            <div class="row mb-5 border-bottom align-items-end">
                <div class="col-md-6 col-lg-4 mb-5">
                    <label class="form-label">Familia</label>
                    <select name="id_familia_servicio_tienda" id="id_familia_servicio_tienda" class="form-select form-select-solid" onchange="Servicios('#id_servicio','<?php echo base_url(); ?>tienda/servicios_familia');">
                        <option value="">Elegir...</option>
                        <?php foreach ($familias_servicios as $row) { ?>
                            <option value="<?php echo $row['id_familia_servicio']; ?>">
                                <?php echo $row['nombre_familia']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-6 col-lg-4 mb-5">
                    <label class="form-label">Servicio</label>
                    <select name="id_servicio" id="id_servicio" class="form-select form-select-solid" data-placeholder="Elegir ...">
                        <option value="" selected>Elegir...</option>
                    </select>
                </div>
                <div class="col-md-2 mb-5">
                    <label class="form-label">Cantidad</label>
                    <input name="cantidad" id="cantidad" class="form-control form-control-solid" type="number" min="1">
                </div>
                <div class="col-md-2 mb-5">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-info text-inverse-info btn-icon" onclick="Anadir();"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="table-responsive mb-5 border-bottom">
                <table id="servicios" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                    <thead class="">
                        <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                            <th>Familia</th>
                            <th>Servicio</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($servicios_asociados)) {
                            if ($servicios_asociados != 0) {
                                foreach ($servicios_asociados as $row) { ?>
                                    <tr>
                                        <td><?php echo $row['nombre_familia']; ?></td>
                                        <td><?php echo $row['nombre_servicio'] . " (" . $row['duracion'] . " min)"; ?> <input type='hidden' name='servicios[]' value="<?php echo $row['id_servicio'] ?>"></td>
                                        <td>
                                            <button class="btn btn-sm btn-icon btn-danger" quitar-tr><i class="fa-solid fa-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php }
                            } ?>
                        <?php } ?>
                    </tbody>
                </table>
                <?php if (!isset($servicios_asociados) or $servicios_asociados == 0) { ?>
                    <div class="alert alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
                        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center" id="msg">NO HAY SERVICIOS ASOCIADOS A ESTE PACK</div>
                    </div>
                <?php } ?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary text-inverse-primary" type="submit">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    /*
     * Carga los Servicios correspondientes a la familia indicada.
     */
    function Servicios(control, url) {
        var dataString = $("#form").serialize();
        $.ajax({
            type: "POST",
            url: url,
            data: dataString,
            success: function(response) {
                $('option:not(:selected)', control).remove();
                $(control).append(response);
                $(control).trigger("change");
            }
        });
    }
    function Anadir() {
        id_familia = document.getElementById("id_familia_servicio_tienda");
        id_servicio = document.getElementById("id_servicio");
        cantidad = document.getElementById("cantidad");
        if (id_servicio.value != "" && cantidad.value != "") {
            for (i = 0; i < cantidad.value; i++) {
                nuevaFila = "<tr>";
                nuevaFila += "<td>" + $("#id_familia_servicio_tienda option:selected").text() + "</td>";
                nuevaFila += "<td>" + $("#id_servicio option:selected").text() + "<input type='hidden' name='servicios[]' value='" + id_servicio.value + "'></td>";
                nuevaFila += `<td><button class="btn btn-sm btn-icon btn-danger" quitar-tr><i class="fa-solid fa-trash"></i></button></td>`;
                nuevaFila += "</tr>";
                $("#servicios").append(nuevaFila);
            }
            $("#msg").hide();
            $("#id_servicio").select2("val", "");
            $("#id_familia_servicio_tienda").val('');
            $("#cantidad").val('');
        } else {
            alert("Debes de indicar un servicio y cantidad");
        }
    }
    $(document).on('click', '[quitar-tr]', function(event) {
        event.preventDefault();
        $(this).closest('tr').remove();
        var nFilas = $("#mi-tabla tr").length;
    });
</script>