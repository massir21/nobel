<style>
    .dataTables_filter {
        text-align: right;
    }
</style>

<?php if (isset($mensaje)) { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center"><?= $mensaje ?></div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="fa-times fas fs-3 text-primary"></i>
        </button>
    </div>
<?php } ?>
<div class="card card-flush">
    <div class="card-body pt-6">
        <form name="form_presupuesto" id="form_presupuesto" action="<?php echo base_url(); ?>Presupuestos/actualizarPresupuesto" method="post" onsubmit="return EsOk2();">
            <div class="row mb-5 pb-5 align-items-end border-bottom">
                <div class="col-md-3">
                    <label for="" class="form-label">Elige el cliente:</label>
                    <select name="id_cliente" id="id_cliente" class="form-select form-select-solid" data-placeholder="Elegir ...">
                        <?php if (isset($registro) && $registro[0]['id_cliente'] > 0) { ?>
                            <option value="<?= $registro[0]['id_cliente'] ?>" selected><?= $registro[0]['nombre'] . ' ' . $registro[0]['apellidos'] . ' (' . $registro[0]['telefono'] . ')'; ?></option>
                        <?php } ?>
                    </select>
                    <script type="text/javascript">
                        $("#id_cliente").select2({
                            language: "es",
                            minimumInputLength: 4,
                            ajax: {
                                delay: 0,
                                url: function(params) {
                                    return '<?php echo RUTA_WWW; ?>/clientes/json/' + params.term.replace(/ /g, "_");
                                },
                                dataType: 'json',
                                processResults: function(data) {
                                    return {
                                        results: data
                                    };
                                }
                            }
                        });
                    </script>
                </div>

                <div class="col-md-3">
                    <label for="" class="form-label">Elige el doctor:</label>
                    <select name="id_doctor" id="id_doctor" class="form-select form-select-solid" data-control="select2" data-placeholder="Elegir ...">
                        <option value="">Selecciona un doctor</option>
                        <?php if (isset($doctores)) {
                            foreach ($doctores as $d => $doctor) { ?>
                                <option value="<?= $doctor['id_usuario'] ?>" <?= ($doctor['id_usuario'] == $registro[0]['id_doctor']) ? 'selected' : '' ?>><?= $doctor['nombre'] . ' ' . $doctor['apellidos']; ?></option>
                            <?php } ?>

                        <?php } ?>
                    </select>

                </div>

                <div class="col-md-3">
                    <label for="" class="form-label">Fecha de validez:</label>
                    <input type="date" id="fecha_validez" name="fecha_validez" value="<?= $registro[0]['fecha_validez'] ?>" class="form-control form-control-solid" placeholder="Fecha de validez" />
                </div>
                <div class="col-md-3">
                    <label for="" class="form-label">Estado:</label>
                    <select class="form-select form-select-solid" id="estado" name="estado" required>
                        <option value="Borrador" <?= ($registro[0]['estado'] == 'Borrador') ? 'selected' : '' ?>>Borrador</option>
                        <option value="Pendiente" <?= ($registro[0]['estado'] == 'Pendiente') ? 'selected' : '' ?>>Pendiente</option>
                    </select>
                </div>
            </div>
            <?php
            // 20240201 - Chains Seleccionar tarifa
            ?>
            <div class="border-bottom mb-5 pb-5">
                <div class="col-md-3">
                    <label for="" class="form-label">Tarifa:</label>
                    <select class="form-select form-select-solid" id="id_tarifa" name="id_tarifa" required>
                        <option value="0">Base</option>
                        <?php
                        if(isset($tarifas) && is_array($tarifas)){
                            foreach($tarifas as $tarifa){
                                ?>
                                <option value="<?php echo $tarifa['id_tarifa'];?>"
                                    <?php echo $tarifa['id_tarifa']==$registro[0]['id_tarifa'] ? ' selected="selected" ':'';?>
                                ><?php echo $tarifa['nombre_tarifa'];?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <?php
            // 20240201 - FIN Chains Seleccionar tarifa
            ?>
            <div class="mb-5 pb-5 border-bottom">
                <h4>Servicios</h4>
                <?php $i = 0;
                foreach ($servicios_items as $i => $value) { ?>
                    <div id="servicio<?= $i ?>" class="itemcontent" data-item-id="<?= $servicios_items[$i]['id_presupuesto_item'] ?>">
                        <div class="row mb-1 align-items-end sumrow">
                            <div class="col-lg-4 col-xl-4">
                                <?= ($i == 0) ? '<label for="" class="form-label">Añadidos:</label>' : '<p></p>' ?>
                                <select name="id_servicio[]" id="id_servicio<?= $i ?>" class="form-select form-select-solid" data-control="select2" data-placeholder="Elegir ..." onchange="buscarServicio(<?= $i ?>);">
                                    <option value=""></option>
                                    <?php if (isset($servicios)) {
                                        if ($servicios != 0 && is_array($servicios)) {
                                            foreach ($servicios as $key => $row) { ?>
                                                <option value="<?php echo $row['id_servicio']; ?>" <?= (isset($servicios_items[$i]) && $value['id_item'] == $row['id_servicio']) ? 'selected' : '' ?>><?php echo strtoupper($row['nombre_servicio']) . " (" . $row['nombre_familia'] . ")"; ?></option>
                                            <?php }
                                        }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-lg-2 col-xl-2">
                                <?= ($i == 0) ? '<label for="" class="form-label">Dientes</label>' : '' ?>
                                <div class="d-flex justify-content-between">
                                    <?php $palabrasClave = ["corona", "endodoncia", "extracción", "implante", "reendodoncia", "obturacion"]; ?>

                                    <input type="text" name="servicioDientes[]" id="servicioDientes<?= $i ?>" class="form-control form-control-solid" value="<?= (isset($servicios_items[$i])) ? $value['dientes'] : '' ?>" readonly <?= (preg_match('/\b(' . implode('|', $palabrasClave) . ')\b/i', $value['nombre_item'])) ? 'required' : ''; ?>/>

                                    <button type="button" class="btn btn-primary btn-icon" id="botonServicio<?= $i ?>" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Seleccionar Odontograma" onclick="habilitaOdontograma(<?= $i ?>)">
                                        <i class="fa fa-tooth" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-1 col-xl-1">
                                <?= ($i == 0) ? '<label for="" class="form-label">Cantidad</label>' : '' ?>
                                <input type="number" name="servicioCantidad[]" id="servicioCantidad<?= $i ?>" class="form-control form-control-solid" value="<?= (isset($servicios_items[$i])) ? $value['cantidad'] : '1' ?>" step="1" min="0" max="100" required />
                            </div>
                            <div class="col-lg-1 col-xl-1">
                                <?= ($i == 0) ? '<label for="" class="form-label">PVP €</label>' : '' ?>
                                <input type="number" name="servicioPrecio[]" id="servicioPrecio<?= $i ?>" class="form-control form-control-solid" value="<?= (isset($servicios_items[$i])) ? $value['pvp'] : '1' ?>" readonly />
                            </div>
                            <div class="col-lg-1 col-xl-1">
                                <?= ($i == 0) ? '<label for="" class="form-label">DTO %</label>' : '' ?>
                                <input type="number" name="servicioDescuento[]" id="servicioDescuento<?= $i ?>" class="form-control form-control-solid servicioDescuento" value="<?= (isset($servicios_items[$i])) ? $value['dto'] : '0' ?>" step=".01" min="0" max="100" required />
                            </div>
                            <div class="col-lg-1 col-xl-1">
                                <?= ($i == 0) ? '<label for="" class="form-label">DTO €</label>' : '' ?>
                                <input type="number" name="servicioDescuentoE[]" id="servicioDescuentoE<?= $i ?>" class="form-control form-control-solid servicioDescuento" step=".01" min="0" required value="<?= (isset($servicios_items[$i])) ? $value['dto_euros'] : '0' ?>" />
                            </div>
                            <div class="col-lg-1 col-xl-1">
                                <?= ($i == 0) ? '<label for="" class="form-label">Tot.</label>' : '' ?>
                                <span class="form-control form-control-solid totalrow">00.00</span>
                            </div>
                            <input type="hidden" name="ids_servicios_items[]" value="<?= $value['id_presupuesto_item'] ?>" />
                            <div class="col-lg-1 col-xl-1">
                                <button type="button" class="btn btn-danger text-inverse-danger btn-icon" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Eliminar servicio" onclick="EliminarItem(<?= $servicios_items[$i]['id_presupuesto_item'] ?>)">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php $i = $i + 1; ?>
                <div id="servicio<?= $i ?>" class="servicio">
                    <div class="row mb-1 align-items-end sumrow">
                        <div class="col-lg-4 col-xl-4">
                            <label for="" class="form-label">Elegir servicios:</label>
                            <select name="id_servicio[]" id="id_servicio<?= $i ?>" class="form-select form-select-solid" data-control="select2" data-placeholder="Elegir ..." onchange="buscarServicio(<?= $i ?>);">
                                <option value=""></option>
                                <?php if (isset($servicios)) {
                                    if ($servicios != 0) {
                                        foreach ($servicios as $key => $row) { ?>
                                            <option value='<?php echo $row['id_servicio']; ?>'>
                                                <?php echo strtoupper($row['nombre_servicio']) . " (" . $row['nombre_familia'] . ")"; ?>
                                            </option>
                                        <?php }
                                    }
                                } ?>
                            </select>
                        </div>
                        <div class="col-lg-2 col-xl-2">
                            <label for="" class="form-label">Dientes</label>
                            <div class="d-flex justify-content-between">
                                <input type="text" name="servicioDientes[]" id="servicioDientes<?= $i ?>" class="form-control form-control-solid" value="" readonly />

                                <button type="button" class="btn btn-primary btn-icon" id="botonServicio<?= $i ?>" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Seleccionar Odontograma" onclick="habilitaOdontograma(<?= $i ?>)">
                                    <i class="fa fa-tooth" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-1 col-xl-1">
                            <label for="" class="form-label">Cantidad</label>
                            <input type="number" name="servicioCantidad[]" id="servicioCantidad<?= $i ?>" class="form-control form-control-solid" value="0" step="1" min="0" max="100" required />
                        </div>
                        <div class="col-lg-1 col-xl-1">
                            <label for="" class="form-label">PVP €</label>
                            <input type="number" name="servicioPrecio[]" id="servicioPrecio<?= $i ?>" class="form-control form-control-solid" value="0" readonly />
                        </div>
                        <div class="col-lg-1 col-xl-1">
                            <label for="" class="form-label">DTO %</label>
                            <input type="number" name="servicioDescuento[]" id="servicioDescuento<?= $i ?>" class="form-control form-control-solid servicioDescuento" value="0" step=".01" min="0" max="100" required />
                        </div>
                        <div class="col-lg-1 col-xl-1">
                            <label for="" class="form-label">DTO €</label>
                            <input type="number" name="servicioDescuentoE[]" id="servicioDescuentoE<?= $i ?>" class="form-control form-control-solid servicioDescuento" value="0" step=".01" min="0" required />
                        </div>
                        <div class="col-lg-1 col-xl-1">
                            <label for="" class="form-label">Tot.</label>
                            <span class="form-control form-control-solid totalrow">00.00</span>
                        </div>
                        <input type="hidden" name="ids_servicios_items[]" value="0" />
                        <div class="col-lg-1 col-xl-1">
                            <button type="button" class="btn btn-info text-inverse-info btn-icon" id="botonServicio<?= $i ?>" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Añadir un nuevo Servicio" add-servicio>
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </button>
                            <button type="button" class="btn btn-danger text-inverse-danger btn-icon" data-reiniciar-row data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Anular servicio">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row border-bottom mb-5 pb-5">
                <div class="col-md-10">
                    <label for="" class="form-label">Observaciones:</label>
                    <textarea rows="3" id="estado_relacionado" name="estado_relacionado" class="form-control form-control-solid" placeholder="Observaciones"><?= $registro[0]['estado_relacionado'] ?></textarea>
                </div>
                <div class="col-md-2">
                    <div class="col-12 mb-4">
                        <label class="form-label">Mostrar en PDF</label>
                        <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                            <input class="form-check-input w-45px h-30px" type="checkbox" id="mostrar_obs" name="mostrar_obs" value="1" <?= ($registro[0]['mostrar_obs'] == 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="mostrar_obs"></label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row align-items-end border-bottom mb-5 pb-5">
                <div class="col-12">
                    <h4 class="mt-5">Financiación:</h4>
                </div>
                <div class="col-md-2">
                    <label for="" class="form-label">Anticipo:</label>
                    <input type="number" id="anticipo_financiacion" name="anticipo_financiacion" class="form-control form-control-solid" step=".01" value="<?= $registro[0]['anticipo_financiacion'] ?>" />
                </div>
                <div class="col-md-2">
                    <label for="" class="form-label">Cuotas:</label>
                    <input type="number" id="cuotas" name="cuotas" class="form-control form-control-solid" step=".01" value="<?= $registro[0]['n_cuotas'] ?>" />
                </div>
                <div class="col-md-2">
                    <label for="" class="form-label">Apertura:</label>
                    <input type="number" id="apertura" name="apertura" class="form-control form-control-solid" step=".01" value="<?= $registro[0]['apertura'] ?>" />
                </div>

                <div class="col-md-2">
                    <label for="" class="form-label">Cuotas de:</label>
                    <input type="number" id="totalcuota" name="totalcuota" class="form-control form-control-solid text-end " step=".01" readonly value="<?= $registro[0]['totalcuota'] ?>" />
                </div>
                <div class="col-md-2">
                    <div class="col-12 mb-4">
                        <label class="form-label">Mostrar financiación</label>
                        <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                            <input class="form-check-input w-45px h-30px" type="checkbox" id="mostrar_financiacion" name="mostrar_financiacion" value="1" <?= ($registro[0]['mostrar_financiacion'] == 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="mostrar_financiacion"></label>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                </div>

            </div>

            <div class="row align-items-end border-bottom mb-5 pb-5">
                <div class="col-12">
                    <h4>Coste de los servicios:</h4>
                </div>
                <div class="col-md-2">
                    <label for="" class="form-label">Descuento (€):</label>
                    <input type="number" id="dto_euros" name="dto_euros" class="form-control form-control-solid" step=".01" value="<?= $registro[0]['dto_euros'] ?>" />
                </div>
                <div class="col-md-2">
                    <label for="" class="form-label">Descuento (%):</label>
                    <input type="number" id="dto_100" name="dto_100" class="form-control form-control-solid" step=".01" value="<?= $registro[0]['dto_100'] ?>" />
                </div>
                <div class="col-md-2">
                    <label for="" class="form-label">Total:</label>
                    <input type="number" id="totalpresupuesto" name="totalpresupuesto" class="form-control form-control-solid text-end " readonly step=".01" value="<?= $registro[0]['totalpresupuesto'] ?>" />
                </div>
                <div class="col-md-2">
                </div>

                <div class="col-md-2">
                    <button type="button" class="btn btn-warning" onclick="calcular()">Actualizar cálculos</button>
                </div>

                <div class="col-md-2">
                    <div class="col-12 mb-4">
                        <label class="form-label">Presupuesto de repetición</label>
                        <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                            <input class="form-check-input w-45px h-30px" type="checkbox" id="es_repeticion" name="es_repeticion" value="1" <?= ($registro[0]['es_repeticion'] == 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="es_repetcicion">El coste de todos los items en el presupuesto (pdf) y en las citas será 0.00</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" name="id_presupuesto" value="<?= $registro[0]['id_presupuesto']; ?>">
                    <input type="hidden" name="accion" value="<?= $accion; ?>">
                    <button class="btn btn-primary text-inverse-primary" type="submit" id="guardar_presupuesto">Guardar</button>
                    <a class="btn btn-secondary" href="/presupuestos/">Regresar</a>
                </div>
            </div>
        </form>
    </div>
</div>



<?php $this->load->view('presupuestos/presupuestos_modal_dientes'); ?>



<script>
    var GLOBAL_arprecios=<?php echo isset($precios) ? json_encode($precios) : json_encode([]); ?>;
    var GLOBAL_ar=<?php echo json_encode($servicios) ?>;



    function EsOk2() {

        /*document.getElementById('nombreCliente').value = $('p').text();
        var combo = document.getElementById("id_empleado_venta");
        var selected = combo.options[combo.selectedIndex].text;
        document.getElementById("nombreEmpleado").value = selected;*/
        var elementosRequeridos = document.querySelectorAll('input[required]');
        var elementosSinValor = 0;
        elementosRequeridos.forEach(function(elemento) {
            if (elemento.value === null || elemento.value === '') {
                elementosSinValor++;
                $(elemento).addClass('border-primary');
            } else {
                $(elemento).removeClass('border-primary');
            }
        });
        if (elementosSinValor > 0) {
            Swal.fire({
                text: 'Por favor, completa todos los datos obligatorios'
            });
            $("#guardar_presupuesto").prop("disabled", true);
            return false;
        }

        calcular();
        return true;
    }

    function buscarServicio(j) {
        var combo = document.getElementById("id_servicio" + j);
        var selected = combo.options[combo.selectedIndex].text;
        var id_servicio = ($("#id_servicio" + j).val());
        var ar = GLOBAL_ar;
        for (i = 0; i < ar.length; i++) {
            if (ar[i]['id_servicio'] == id_servicio) {
                var texto = ar[i]['nombre_servicio'];
                var palabras = ["corona", "endodoncia", "extracción", "implante", "reendodoncia", "obturacion"];
                var expresionRegular = new RegExp("\\b(" + palabras.join("|") + ")\\b", "i"); // "i" para hacer la búsqueda insensible a mayúsculas/minúsculas \\b()\\b para palabra exacta
                if (expresionRegular.test(texto)) {
                    $("#servicioDientes" + j).attr('required', 'required');
                    Swal.fire({
                        text: 'Este servicio requiere que se indique al menos un diente',
                        willClose: function() {
                            $('#servicioDientes' + j).addClass('border-primary');
                            habilitaOdontograma(j)
                        }
                    });
                } else {
                    $('#servicioDientes' + j).removeClass('border-primary');
                    $("#servicioDientes" + j).removeAttr('required');
                }
                <?php
                // CHAINS 20240201 - Precios
                ?>
                var id_tarifa = jQuery("#id_tarifa").val();
                var xprecio = null;
                if(typeof GLOBAL_arprecios['tarifa_'+id_tarifa] != "undefined"){
                    var precios=GLOBAL_arprecios['tarifa_'+id_tarifa];
                    if(typeof precios['id_servicio_'+id_servicio] !="undefined"){
                        xprecio= precios['id_servicio_'+id_servicio];
                    }
                }
                if(xprecio!=null){
                    $("#servicioPrecio" + j).val(xprecio);
                }
                <?php
                    // CHAINS 20240201 - Precios
                    ?>
                else {
                    $("#servicioPrecio" + j).val(ar[i]['pvp']);
                }

                $("#servicioNombre" + j).val(selected);
                $("#servicioCantidad" + j).val(1)
            }
        }
        calcular();
    }

    function habilitaServicio(i) {
        $("#servicio" + i).show();
    }

    function calcularFinanciacion() {
        var anticipo = parseFloat(document.getElementById("anticipo_financiacion").value)
        var total = parseFloat(document.getElementById("totalpresupuesto").value)
        var cuotas = parseFloat(document.getElementById("cuotas").value)
        var apertuta = parseFloat(document.getElementById("apertura").value)
        var total_financiado = total - anticipo;
        var totalfinanciacion = parseFloat(total_financiado + (total_financiado * (apertuta / 100)))
        var totalcuota = totalfinanciacion / cuotas;
        if (!isNaN(totalcuota) && isFinite(totalcuota)) {
            document.getElementById('totalcuota').value = totalcuota.toFixed(2);
        } else {
            document.getElementById('totalcuota').value = 0
        }
    }

    function calcular() {
        var idCliente = $("#id_cliente").val();
        var idDoctor = $("#id_doctor").val();
        var fechaValidez = $("#fecha_validez").val();
        var estado = $("#estado").val();
        if (idCliente == "" || idCliente == null || idDoctor == "" || idDoctor == null || fechaValidez == "" || estado == "") {
            Swal.fire({
                text: 'Por favor, indica el paciente y doctor'
            });
            $("#guardar_presupuesto").prop("disabled", true);
            return;
        }
        var totalSinDescuento = 0;
        var totalConDescuento = 0;
        var descuentoGeneralEurosItem = 0;
        var descuentoGeneral100Item = 0;
        // revisar los valores de los descuentos genertales, y aplicar a cada item, si existe
        var dtoEuros = parseFloat($("#dto_euros").val());
        var dto100 = parseFloat($("#dto_100").val());
        var cantidadElements = $("[id*='Cantidad']");
        var sumaCantidad = 0;
        cantidadElements.each(function() {
            var valor = parseFloat($(this).val());
            if (!isNaN(valor)) {
                sumaCantidad += valor;
            }
        });
        if (!isNaN(dtoEuros) && dtoEuros > 0) {
            var descuentoEElements = $("[id*='DescuentoE']");
            var descuentoGeneralItem = dtoEuros / sumaCantidad;
            var descuentoGeneralEurosItem = descuentoGeneralItem.toFixed(2);
            $("#dto_100").val(0);
            $("input[id*='Descuento']:not([id*='DescuentoE']").val(0);
        } else if (!isNaN(dto100) && dto100 > 0) {
            var descuentoGeneral100Item = dto100;
            $("[id*='DescuentoE']").val(0);
        }

        var ultimoSumRow = null;
        $(".sumrow").each(function() {
            var inputsCantidad = $(this).find("input[id*='Cantidad']");
            var inputsPrecio = $(this).find("input[id*='Precio']");
            var inputsDescuento = $(this).find("input[id*='Descuento']:not([id*='DescuentoE']");
            if (descuentoGeneral100Item > 0) {
                inputsDescuento.val(descuentoGeneral100Item)
            }
            var inputsDescuentoE = $(this).find("input[id*='DescuentoE']");
            if (descuentoGeneralEurosItem > 0) {
                var row_dto = descuentoGeneralEurosItem * parseFloat(inputsCantidad.val());
                inputsDescuentoE.val(row_dto.toFixed(2))
            }
            var totalrow = $(this).find(".totalrow");
            if (inputsCantidad.length > 0 && inputsPrecio.length > 0 && inputsDescuento.length > 0) {
                var cantidad = parseFloat(inputsCantidad.val());
                var precio = parseFloat(inputsPrecio.val());
                var descuento = parseFloat(inputsDescuento.val());
                var descuentoE = parseFloat(inputsDescuentoE.val());
                if (!isNaN(cantidad) && !isNaN(precio) && !isNaN(descuento)) {
                    var cifra = (cantidad * precio).toFixed(2);
                    var cifraConDescuento = (cifra - (cifra * (descuento / 100)) - descuentoE).toFixed(2);
                    totalrow.html(cifraConDescuento)
                    totalSinDescuento += parseFloat(cifra);
                    totalConDescuento += parseFloat(cifraConDescuento);
                }
                if (!isNaN(cantidad) && cantidad > 0) {
                    ultimoSumRow = $(this);
                }
            }
        });
        if (!isNaN(dtoEuros) && dtoEuros > 0) {
            var calculado = parseFloat(totalSinDescuento - dtoEuros);
            var descuadre = parseFloat(totalConDescuento - calculado);
            if (ultimoSumRow !== null) {
                var inputsCantidad = $(this).find("input[id*='Cantidad']");
                var inputsPrecio = $(this).find("input[id*='Precio']");
                var inputsDescuento = $(this).find("input[id*='Descuento']:not([id*='DescuentoE']");

                var rowcantidad = parseFloat(ultimoSumRow.find("input[id*='Cantidad']").val());
                var rowprecio = parseFloat(ultimoSumRow.find("input[id*='Precio']").val());
                var rowDescuento = parseFloat(ultimoSumRow.find("input[id*='Descuento']:not([id*='DescuentoE']").val());
                var rowdescuentoE = parseFloat(ultimoSumRow.find("input[id*='DescuentoE']").val());
                var compensado = parseFloat(rowdescuentoE + descuadre)
                ultimoSumRow.find("input[id*='DescuentoE']").val(compensado.toFixed(2));

                if (!isNaN(rowcantidad) && !isNaN(rowprecio) && !isNaN(rowDescuento)) {
                    var cifra = (rowcantidad * rowprecio).toFixed(2);
                    var cifraConDescuento = (cifra - (cifra * (rowDescuento / 100)) - compensado).toFixed(2);
                    ultimoSumRow.find(".totalrow").html(cifraConDescuento)
                }
            }
            var importeTotalFinal = (totalSinDescuento - dtoEuros).toFixed(2);
        }else{
            var importeTotalFinal = totalConDescuento.toFixed(2);
        }
        $("#totalpresupuesto").val(importeTotalFinal);
        $("#guardar_presupuesto").prop("disabled", false);
        calcularFinanciacion();
    }


    <?php
    /* CHAINS 20240202 - Recalcular cuando se cambia la tarifa */
    ?>
    function recalcularTarifas(){
        var id_tarifa = jQuery("#id_tarifa").val();

        var inputsPrecio = jQuery("input[id*='Precio']");
        inputsPrecio.each(function(){
            var input=jQuery(this);
            var index=input.attr('id').replace("servicioPrecio","");
            var id_servicio=jQuery("#id_servicio"+index).val();
            var ar = GLOBAL_ar;
            for (i = 0; i < ar.length; i++) {
                if (ar[i]['id_servicio'] == id_servicio) {
                    var xprecio = null;
                    if (typeof GLOBAL_arprecios['tarifa_' + id_tarifa] != "undefined") {
                        var precios = GLOBAL_arprecios['tarifa_' + id_tarifa];
                        if (typeof precios['id_servicio_' + id_servicio] != "undefined") {
                            xprecio = precios['id_servicio_' + id_servicio];
                        }
                    }
                    if (xprecio != null) {
                        $("#servicioPrecio" + index).val(xprecio);
                    }
                    else {
                        $("#servicioPrecio" + index).val(ar[i]['pvp']);
                    }
                }
            }
        });
        calcular();
    }
    <?php
    /* FIN CHAINS 20240202 - Recalcular cuando se cambia la tarifa */
    ?>

    $(document).on('input', '.sumrow input, #dto_euros, #dto_100, #com_cuota, #cuotas, #apertura, #anticipo_financiacion, .servicioDescuento', function() {
        calcular();
    });

    function EliminarItem(id_presupuesto_item) {
        Swal.fire({
            text: '¿Estás seguro de que deseas eliminar el elemento?',
            showCancelButton: true,
            confirmButtonText: 'Si, eliminar',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '<?= base_url() ?>Presupuestos/eliminar_item',
                    method: 'POST',
                    data: {
                        id_presupuesto_item: id_presupuesto_item
                    },
                    success: function(response) {
                        console.log(response)
                        if (response.status == 'success') {
                            $('[data-item-id="' + id_presupuesto_item + '"]').remove();
                            calcular();
                        } else {
                            Swal.fire('Error en la respuesta del servidor');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error en la petición AJAX:', error);
                    }
                });
            }
        })
        /*var confirmacion = confirm('¿Estás seguro de que deseas eliminar el elemento?');
        if (confirmacion) {
            $.ajax({
                url: '<?= base_url() ?>Presupuestos/eliminar_item',
				method: 'POST',
				data: {
					id_presupuesto_item: id_presupuesto_item
				},
				success: function(response) {
					console.log(response)
					if (response.status == 'success') {
						$('[data-item-id="' + id_presupuesto_item + '"]').remove();
						calcular();
					} else {
						console.log('Error en la respuesta del servidor');
					}
				},
				error: function(xhr, status, error) {
					alert('Error en la petición AJAX:', error);
				}
			});
		} else {
			console.log('Acción cancelada por el usuario');
		}*/
    }

    function estadopresupuesto() {
        var estado = $('#estado').val();
        if (estado != 'Borrador') {
            $('.sumrow input').attr('readonly', 'readonly');
            $('.sumrow select').prop("disabled", true);
            $('.sumrow .btn, #calcular').attr('disabled', 'disabled');
            $('#estado option[value="Aceptado"], #estado option[value="Rechazado"], #estado option[value="Aceptado parcial"]').attr('disabled', 'disabled');
            $('#con_cuotas, #dtoo_euros, #dto_100').attr('disabled', 'disabled');
        }

        if (estado == 'Pendiente') {
            $('#estado option').prop('disabled', false);
            $('#estado option[value="Borrador"]').attr('disabled', 'disabled');
        }

        if (estado == 'Aceptado parcial') {}

        if (estado == 'Aceptado ') {}

        if (estado == 'Rechazado') {

        }
    }

    $(document).ready(function() {

        if ($('#estado').val() != 'Borrador') {
            $('.sumrow input').attr('readonly', 'readonly');
            $('.sumrow select').prop("disabled", true);
            $('.sumrow .btn, #calcular').attr('disabled', 'disabled');
            $('#estado option[value="Aceptado"], #estado option[value="Rechazado"], #estado option[value="Aceptado parcial"]').attr('disabled', 'disabled');
            $('#con_cuotas, #dtoo_euros, #dto_100').attr('disabled', 'disabled');
        }
        if ($('#estado').val() == 'Pendiente') {
            $('#estado option').prop('disabled', false);
            $('#estado option[value="Borrador"]').attr('disabled', 'disabled');
        }

        $('.sumrow select').on('select2:select', function() {
            calcular();
        });

        $(document).on("click", '[data-reiniciar-row]', function() {
            var $sumrow = $(this).closest('.sumrow');
            $sumrow.find('select').each(function() {
                $(this).val(null).trigger('change');
            });
            $sumrow.find('input').val(0);
            calcular();
        });

        $(document).on("click", "[add-servicio]", function() {
            var ultimoServicio = $(".servicio:last");
            var ultimoID = ultimoServicio.attr("id");
            var numero = parseInt(ultimoID.replace("servicio", "")) + 1;
            var nuevoServicio = ultimoServicio.clone();
            nuevoServicio.attr("id", "servicio" + numero);
            nuevoServicio.find("*[id]").each(function() {
                var antiguoID = $(this).attr("id");
                var nuevoID = antiguoID.replace(/\d+$/, numero);
                $(this).attr("id", nuevoID);
            });
            nuevoServicio.find("*[name]").each(function() {
                var antiguoNombre = $(this).attr("name");
                var nuevoNombre = antiguoNombre.replace(/\d+$/, numero);
                $(this).attr("name", nuevoNombre);
                $(this).val("");
            });
            nuevoServicio.find("[type='number']").each(function() {
                $(this).val(0);
            });
            nuevoServicio.find("*[onchange]").each(function() {
                var antiguoOnchange = $(this).attr("onchange");
                var nuevoOnchange = antiguoOnchange.replace(/\d+/, numero);
                $(this).attr("onchange", nuevoOnchange);
            });
            nuevoServicio.find("*[onclick]").each(function() {
                var antiguoOnclick = $(this).attr("onclick");
                var nuevoOnclick = antiguoOnclick.replace(/\d+/, numero);
                $(this).attr("onclick", nuevoOnclick);
            });
            nuevoServicio.find(".form-label").remove();
            nuevoServicio.find(".select2.select2-container").remove();
            nuevoServicio.find('[name="ids_servicios_items[]"]').val(0);
            ultimoServicio.after(nuevoServicio);
            ultimoServicio.find("select").select2();
            nuevoServicio.find("select").select2();
        });

        $(document).on('input', '.servicioDescuento', function() {
            //var currentValue = $(this).val();
            $(this).closest('.sumrow').find('.servicioDescuento').not(this).val('0.00');
            //$(this).val(currentValue);
            calcular();
        });


        $('#estado').on('change', function() {
            //poner aqui que si el estado es
        })
        calcular();

        jQuery("#id_tarifa").on('change',function(){
            recalcularTarifas();
        });
    });
</script>