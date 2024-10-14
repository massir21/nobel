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
        <form name="form_presupuesto" id="form_presupuesto" action="<?php echo base_url(); ?>Presupuestos/crearPresupuesto" method="post" enctype="multipart/form-data" onsubmit="return EsOk2();">
            <div class="row mb-5 pb-5 align-items-end border-bottom">
                <div class="col-md-3">
                    <label for="" class="form-label">Elige el cliente:</label>
                    <select name="id_cliente" id="id_cliente" class="form-select form-select-solid" data-placeholder="Elegir ...">

                        <?php if (isset($cliente) && $cliente[0]['id_cliente'] > 0) { ?>
                            <option value="<?= $cliente[0]['id_cliente'] ?>" selected><?= $cliente[0]['nombre'] . ' ' . $cliente[0]['apellidos'] . ' (' . $cliente[0]['telefono'] . ')'; ?></option>
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
                <div class="col-md-1">
                    <button type="button" class="btn btn-info text-inverse-info btn-icon" data-bs-target="#stack-cliente" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Añadir un nuevo Cliente"><i class="fas fa-plus"></i></button>
                </div>
                <div class="col-md-3">
                    <label for="" class="form-label">Elige el doctor:</label>
                    <select name="id_doctor" id="id_doctor" class="form-select form-select-solid" data-control="select2" data-placeholder="Elegir ...">
                        <option value="">Selecciona un doctor</option>
                        <?php if (isset($doctores)) {
                            foreach ($doctores as $d => $doctor) { ?>
                                <option value="<?= $doctor['id_usuario'] ?>"><?= $doctor['nombre'] . ' ' . $doctor['apellidos']; ?></option>
                            <?php } ?>

                        <?php } ?>
                    </select>

                </div>
                <div class="col-md-2">
                    <label for="" class="form-label">Fecha de validez:</label>
                    <input type="date" id="fecha_validez" name="fecha_validez" value="<?= date('Y-m-d', strtotime('+ 15 days')); ?>" class="form-control form-control-solid" placeholder="Fecha de validez" />
                </div>
                <div class="col-md-3">
                    <label for="" class="form-label">Estado:</label>
                    <select class="form-select form-select-solid" id="estado" name="estado" required>
                        <option value="Borrador">Borrador</option>
                        <option value="Pendiente">Pendiente</option>
                    </select>
                </div>
            </div>
            <?php
            // 20240201 - Chains Seleccionar tarifa
            ?>
    <div class="row mb-5 pb-5 border-bottom mb-5 pb-5">
        <div style="display: none">
            <label for="id_tarifa" class="form-label">Tarifa:</label>
            <select class="form-select form-select-solid" id="id_tarifa" name="id_tarifa" required>
                <option value="0">Base</option>
                <?php
                if(isset($tarifas) && is_array($tarifas)){
                    foreach($tarifas as $tarifa){
                        ?>
                        <option value="<?php echo $tarifa['id_tarifa'];?>"><?php echo $tarifa['nombre_tarifa'];?></option>
                        <?php
                    }
                }
                ?>
            </select>
            <?php //Ocultado pero se sigue enviando el dato por compatibilidad */ ?>
        </div>
        <div class="col-md-12">
            <label for="titulo" class="form-label">Título:</label>
            <input type="text" class="form-control form-control-solid" id="titulo" name="titulo" required>
        </div>
    </div>
    <?php if (isset($aseguradoras)) { ?> 
    <div class="row border-bottom  mb-5 pb-5">
       <div class="col-md-2">
          <label class="form-label">Aplica a seguros</label>
          <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                <input class="form-check-input w-45px h-30px" type="checkbox" id="mostrar_aseguradoras" name="mostrar_aseguradoras" value="1">
                <label class="form-check-label" for="mostrar_aseguradoras"></label>
          </div>
       </div>
        <div class="col-md-3 aplica_seguro" style="display: none">
             <label for="" class="form-label">Elige la aseguradora:</label>
             <select name="id_aseguradora" id="id_aseguradora" class="form-select form-select-solid" data-control="select2" data-placeholder="Elegir ...">
                 <option value="">Selecciona una aseguradora</option>
                     <?php
                     foreach ($aseguradoras as $d => $aseguradora) { ?>
                         <option value="<?= $aseguradora['id_aseguradora'] ?>"><?= $aseguradora['nombre_aseguradora']; ?></option>
                     <?php } ?>
             </select>
         </div>
         <div class="col-md-3 aplica_seguro" style="display: none">
             <label for="formFile1" class="form-label">Tarjeta paciente</label>
             <input class="form-control" type="file" id="aseguradora_tarjeta_paciente_file" name="aseguradora_tarjeta_paciente">
         </div>
         <div class="col-md-3 aplica_seguro" style="display: none">
             <label for="aseguradora_presupuesto_file" class="form-label">Presupuesto aseguradora</label>
             <input class="form-control" type="file" id="aseguradora_presupuesto_file" name="aseguradora_presupuesto">
         </div>
    </div> 
    <?php } ?>
            <?php
            // 20240201 - FIN Chains Seleccionar tarifa
            ?>
            <div class="border-bottom mb-5 pb-5">
                <div id="servicio1" class="servicio">
                    <div class="row mb-1 align-items-end sumrow">
                        <div class="col-lg-4 col-xl-4">
                            <label for="" class="form-label">Elegir servicios:</label>
                            <select name="id_servicio[]" id="id_servicio1" class="form-select form-select-solid" data-control="select2" data-placeholder="Elegir ..." onchange="buscarServicio(1);">
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
                                <input type="text" name="servicioDientes[]" id="servicioDientes1" class="form-control form-control-solid" value="" readonly />
                                <button type="button" class="btn btn-primary btn-icon" id="botonServicio1" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Seleccionar Odontograma" onclick="habilitaOdontograma(1)">
                                    <i class="fa fa-tooth" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-1 col-xl-1">
                            <label for="" class="form-label">Cantidad</label>
                            <input type="number" name="servicioCantidad[]" id="servicioCantidad1" class="form-control form-control-solid serviciocantidadInput" value="0" step="1" min="0" max="100" required />
                        </div>
                        <div class="col-lg-1 col-xl-1">
                            <label for="" class="form-label">PVP €</label>
                            <input type="number" name="servicioPrecio[]" id="servicioPrecio1" class="form-control form-control-solid" readonly />
                        </div>
                        <div class="col-lg-1 col-xl-1">
                            <label for="" class="form-label">DTO %</label>
                            <input type="number" name="servicioDescuento[]" id="servicioDescuento1" class="form-control form-control-solid servicioDescuento servicioDescuentoPorcentaje" value="0" step=".01" min="0" max="100" required />
                        </div>
                        <div class="col-lg-1 col-xl-1">
                            <label for="" class="form-label">DTO €</label>
                            <input type="number" name="servicioDescuentoE[]" id="servicioDescuentoE1" class="form-control form-control-solid servicioDescuento servicioDescuentoEuros" value="0" step=".01" min="0" required />
                        </div>
                        <div class="col-lg-1 col-xl-1">
                            <label for="" class="form-label">Tot.</label>
                            <span class="form-control form-control-solid totalrow">00.00</span>
                        </div>
                        <div class="col-lg-1 col-xl-1">
                            <button type="button" class="btn btn-info text-inverse-info btn-icon" id="botonServicio1" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Añadir un nuevo Servicio" add-servicio>
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
                    <textarea rows="3" id="estado_relacionado" name="estado_relacionado" class="form-control form-control-solid" placeholder="Observaciones"></textarea>
                </div>
                <div class="col-md-2">
                    <div class="col-12 mb-4">
                        <label class="form-label">Mostrar en PDF</label>
                        <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                            <input class="form-check-input w-45px h-30px" type="checkbox" id="mostrar_obs" name="mostrar_obs" value="1">
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
                    <input type="number" id="anticipo_financiacion" name="anticipo_financiacion" class="form-control form-control-solid" step=".01" value="" />
                </div>
                <div class="col-md-2">
                    <label for="" class="form-label">Cuotas:</label>
                    <input type="number" id="cuotas" name="cuotas" class="form-control form-control-solid" step=".01" value="" />
                </div>
                <div class="col-md-2">
                    <label for="" class="form-label">Apertura:</label>
                    <input type="number" id="apertura" name="apertura" class="form-control form-control-solid" step=".01" value="" />
                </div>

                <div class="col-md-2">
                    <label for="" class="form-label">Cuotas de:</label>
                    <input type="number" id="totalcuota" name="totalcuota" class="form-control form-control-solid text-end " step=".01" readonly value="" />
                </div>
                <div class="col-md-2">
                    <div class="col-12 mb-4">
                        <label class="form-label">Mostrar financiación</label>
                        <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                            <input class="form-check-input w-45px h-30px" type="checkbox" id="mostrar_financiacion" name="mostrar_financiacion" value="1">
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
                    <input type="number" id="dto_euros" name="dto_euros" class="form-control form-control-solid" step=".01" value="" />
                </div>
                <div class="col-md-2">
                    <label for="" class="form-label">Descuento (%):</label>
                    <input type="number" id="dto_100" name="dto_100" class="form-control form-control-solid" step=".01" value="" />
                </div>
                <div class="col-md-2">
                    <label for="" class="form-label">Total:</label>
                    <input type="number" id="totalpresupuesto" name="totalpresupuesto" class="form-control form-control-solid text-end " readonly step=".01" value="" />
                </div>

                <div class="col-md-2">
                    <button type="button" class="btn btn-warning" onclick="calcular()">Actualizar cálculos</button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary text-inverse-primary" type="submit" id="guardar_presupuesto" disabled>Guardar</button>
                    <a class="btn btn-secondary" href="/presupuestos/">Regresar</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php $this->load->view('presupuestos/presupuestos_modal_dientes'); ?>

<div class="modal fade" id="stack-cliente" tabindex="-1" aria-labelledby="stack-clienteLabel" aria-hidden="true" data-focus-on="input:first">
    <div class="modal-dialog">
        <div class="modal-content">
            <form name="form_nuevo_cliente" id="form_nuevo_cliente" action="<?php echo base_url(); ?>dietario/altaCliente" method="post" onsubmit="return EsOk();">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">AÑADIR NUEVO CLIENTE</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <label class="form-label">Nombre</label>
                            <input type="text" id="nombre" name="nombre" value="" class="form-control form-control-solid " placeholder="Nombre" />
                        </div>
                        <div class="col-12 mb-4">
                            <label class="form-label">Apellidos</label>
                            <input type="text" id="apellidos" name="apellidos" value="" class="form-control form-control-solid" placeholder="Apellidos" />
                        </div>
                        <div class="col-12 mb-4">
                            <label class="form-label">Teléfono</label>
                            <input type="text" id="telefono" name="telefono" value="" class="form-control form-control-solid" placeholder="Teléfono" />
                        </div>
                        <div class="col-12 mb-4">
                            <label class="form-label">NO quiero recibir publicidad</label>
                            <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                                <input class="form-check-input w-45px h-30px" type="checkbox" id="no_quiere_publicidad" name="no_quiere_publicidad" value="1">
                                <label class="form-check-label" for="no_quiere_publicidad"></label>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="codigo" value="<?php if (isset($codigo)) {
                        echo $codigo;
                    } ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary text-inverse-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary text-inverse-primary">Crear Cliente</button>
                </div>
                <input type="hidden" name="solo_este_empleado" value="" />
                <input type="hidden" name="id_empleado" value="" />
                <input type="hidden" name="id_servicio" value="" />
                <input type="hidden" name="fecha" value="" />
                <input type="hidden" name="hora" value="" />
                <input type="hidden" name="observaciones" value="" />
            </form>
        </div>
    </div>

</div>
<script>
    var myModalEl = document.getElementById('stack-cliente')
    myModalEl.addEventListener('hidden.bs.modal', function(event) {
        document.form_nuevo_cliente.nombre.value = "";
        document.form_nuevo_cliente.apellidos.value = "";
        document.form_nuevo_cliente.telefono.value = "";
        document.form_nuevo_cliente.no_quiere_publicidad.value = 0;
        document.form_nuevo_cliente.no_quiere_publicidad.value = 0;
    })
</script>
<script>

    var GLOBAL_arprecios=<?php echo json_encode($precios); ?>;
    var GLOBAL_ar=<?php echo json_encode($servicios) ?>;


    function EsOk() {
        if (document.form_cita.solo_este_empleado.checked) {
            document.form_nuevo_cliente.solo_este_empleado.value = 1;
        }
        document.form_nuevo_cliente.id_empleado.value = document.form_cita.id_empleado.value;
        document.form_nuevo_cliente.id_servicio.value = document.form_cita.id_servicio.value;
        document.form_nuevo_cliente.fecha.value = document.form_cita.fecha.value;
        document.form_nuevo_cliente.hora.value = document.form_cita.hora.value;
        document.form_nuevo_cliente.observaciones.value = document.form_cita.observaciones.value;
        return true;
    }

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

    function FichaCliente(id_cliente) {
        var posicion_x;
        var posicion_y;
        var ancho = 800;
        var alto = 680;
        posicion_x = (screen.width / 2) - (ancho / 2);
        posicion_y = (screen.height / 2) - (alto / 2);
        window.open("<?php echo base_url(); ?>clientes/historial/ver/" + id_cliente, "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
    }

    function NuevaNota(id_cliente) {
        var posicion_x;
        var posicion_y;
        var ancho = 600;
        var alto = 400;
        posicion_x = (screen.width / 2) - (ancho / 2);
        posicion_y = (screen.height / 2) - (alto / 2);
        window.open("<?php echo base_url(); ?>clientes/nueva_nota_cita_agenda/" + id_cliente, "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
    }

    function Cobrar(id_cliente, fecha) {
        var posicion_x;
        var posicion_y;
        var ancho = 800;
        var alto = 600;
        posicion_x = (screen.width / 2) - (ancho / 2);
        posicion_y = (screen.height / 2) - (alto / 2);
        window.open("<?php echo base_url(); ?>dietario/ficha/ver/" + id_cliente + "/" + fecha, "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
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
                        willClose: function () {
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
                $("#servicioDescuento"+j).attr('max',ar[i]['maxdescuento']);
                $("#servicioDescuento"+j).attr('max_original',ar[i]['maxdescuento']);
                $("#servicioDescuentoE"+j).attr('max_dto_unidad',(ar[i]['pvp']*(ar[i]['maxdescuento']/100)));
                $("#servicioDescuentoE"+j).attr('max_dto_unidad_original',(ar[i]['pvp']*(ar[i]['maxdescuento']/100)));
                $("#servicioDescuentoE"+j).attr('max',(ar[i]['pvp']*(ar[i]['maxdescuento']/100)));
                $("#servicioDescuentoE"+j).attr('max_original',(ar[i]['pvp']*(ar[i]['maxdescuento']/100)));
                
                if ( tiene_seguro() ){
                  $("#servicioDescuento"+j).attr('max', '100'); 
                  $("#servicioDescuentoE"+j).attr('max_dto_unidad', ar[i]['pvp']);
                  $("#servicioDescuentoE"+j).attr('max', ar[i]['pvp']);
                  
                  quitar_limite_descuentos();
                }
                else {
                    restaurar_limite_descuentos();
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
    
    function tiene_seguro(){
        return jQuery('#mostrar_aseguradoras').is(":checked");
    }
    
    function quitar_limite_descuentos(){
       jQuery('.servicioDescuentoPorcentaje').each( function(){
          jQuery(this).attr('max', '100');
       });
    }
    
    function restaurar_limite_descuentos(){
         
         jQuery('.servicioDescuentoPorcentaje').each( function(){
            jQuery(this).attr('max', jQuery(this).attr('max_original'));
            
            if ( jQuery(this).val() > jQuery(this).attr('max') ){
              jQuery(this).val(jQuery(this).attr('max'));
            }
         });
         
        jQuery('.servicioDescuentoEuros').each( function(){
            jQuery(this).attr('max', jQuery(this).attr('max_original'));
            jQuery(this).attr('max_dto_unidad', jQuery(this).attr('max_dto_unidad_original'));
             
            if ( jQuery(this).val() > jQuery(this).attr('max') ){
              jQuery(this).val(jQuery(this).attr('max'));
            }
        });
       
    }
    
    $(document).on('input', '.sumrow input, #dto_euros, #dto_100, #com_cuota, #cuotas, #apertura, #anticipo_financiacion, .servicioDescuento', function() {
        calcular();
    });

    $(document).ready(function() {
        
        $('#mostrar_aseguradoras, #id_aseguradora, #aseguradora_tarjeta_paciente_file, #aseguradora_presupuesto_file').on('change', function(){
            if ( tiene_seguro() ){
                quitar_limite_descuentos();
                
                jQuery('#id_aseguradora').attr('required', 'required');
                jQuery('#aseguradora_tarjeta_paciente_file').attr('required', 'required');
                jQuery('#aseguradora_presupuesto_file').attr('required', 'required');
            }
            else {
                restaurar_limite_descuentos();
                
                jQuery('#id_aseguradora').removeAttr('required');
                jQuery('#aseguradora_tarjeta_paciente_file').removeAttr('required');
                jQuery('#aseguradora_presupuesto_file').removeAttr('required');
            }
        });
        
        $(".serviciocantidadInput").on('change',function(){
            var maxDto1=$(this).closest('.sumrow').find('.servicioDescuentoEuros').attr('max_dto_unidad');
            var unidades=$(this).val();
            $(this).closest('.sumrow').find('.servicioDescuentoEuros').attr('max',unidades*maxDto1);
        });

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

        $(document).on('input', '.servicioDescuento', function() {
            //var currentValue = $(this).val();
            $(this).closest('.sumrow').find('.servicioDescuento').not(this).val('0.00');
            //$(this).val(currentValue);
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
            nuevoServicio.find("select").select2();
        });

        jQuery("#id_tarifa").on('change',function(){
            recalcularTarifas();
        });

        jQuery("#id_cliente").on('change',function(){
            var id_cliente=jQuery(this).val();
            var $this=jQuery(this);
            if(id_cliente!=""){
                var url="<?php echo base_url(); ?>Clientes/checkinfocompleta/"+id_cliente;
                jQuery.post(url,function(data){
                    if(data.ok){

                    }
                    else{
                        $this.parent().find(".select2.select2-container").remove();
                        $this.parent().find('[name="id_cliente"]').val(0);
                        $this.parent().find("select").select2();
                        alert('El cliente tiene datos obligatorios sin completar. Rellene la ficha antes de realizar el presupuesto');
                        var url = "<?php echo base_url(); ?>clientes/gestion/editar/" + id_cliente+'?frompresupuesto='+id_cliente;
                        openwindow('cliente_editar', url, 800, 620);
                    }
                },'json')
            }
        });
        
        jQuery('#mostrar_aseguradoras').on('change', function(){
           if ( !jQuery(this).is(":checked") ){
              jQuery('.aplica_seguro').hide('slow');
              restaurar_limite_descuentos();
           }
           else {
              jQuery('.aplica_seguro').show('slow');
              quitar_limite_descuentos();
           }
        });
        
        <?php
        if(isset($_GET['idcliente'])){
            ?>
            var $newOption = $("<option selected='selected'></option>").val("<?php echo $_GET['idcliente'];?>").text("<?php echo $_GET['cliente'];?>")
            $("#id_cliente").append($newOption).trigger('change');
        <?php
        }
        ?>
    });
</script>