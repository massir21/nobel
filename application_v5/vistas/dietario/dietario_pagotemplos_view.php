<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
    <style>
        #carnets_del_cliente {
            display: none;
        }

        /*::before#video {
            height: 200px;
            width: 200px;
        }*/
    </style>

    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">Pago en Templos</h1>
    <div class="card card-flush m-5">
        <div class="card-body">
            <div class="d-flex flex-center flex-row mb-5">
                <h1 class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1"><?= (isset($registros[0]['cliente'])) ? $registros[0]['cliente'] : ''; ?></h1>
            </div>
            <?php if (isset($registros) && $registros != 0) { ?>
                <form id="form_carnets" action="<?php echo base_url(); ?>dietario/pagotemplos/comprobar_carnet/<?php echo $id_cliente; ?>/<?php echo $hoy_aaaammdd; ?>" role="form" method="post" name="form_carnets">

                    <div class="border p-5 mb-5">
                        <h4>Paso 1. Elige los servicios que deseas cobrar.</h4>
                        <div class="table-responsive mb-5">
                            <table id="myTable1" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                                <thead class="">
                                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                                        <th style="display: none;">ID</th>
                                        <th></th>
                                        <th>Servicios</th>
                                        <th>Templos</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700 fw-semibold">
                                    <?php $total_templos = 0; ?>
                                    <?php if (isset($registros)) {
                                        if ($registros != 0) {
                                            foreach ($registros as $key => $row) { ?>
                                                <?php if ($row['carnet'] != "") {
                                                    $row['color_estado'] = "#dee8fd";
                                                } ?>
                                                <tr>
                                                    <td style="display: none;">
                                                        <?php echo $row['id_dietario']; ?>
                                                    </td>
                                                    <td>
                                                        <?php $seleciona_servicio = "";
                                                        if (isset($servicios_marcados)) {
                                                            foreach ($servicios_marcados as $cada) {
                                                                if ($cada == $row['id_dietario']) {
                                                                    $seleciona_servicio = "checked";
                                                                }
                                                            }
                                                        } ?>
                                                        <div class="form-check form-check-sm form-check-custom">
                                                            <input class="form-check-input" type="checkbox" name="servicios_marcados[]" value="<?php echo $row['id_dietario']; ?>" <?= ($seleciona_servicio == "checked") ? "checked" : '' ?> />
                                                        </div>


                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($row['servicio'] != "") {
                                                            echo $row['servicio'];
                                                        } ?>
                                                        <?php if ($row['producto'] != "") {
                                                            echo $row['producto'];
                                                        } ?>
                                                        <?php if ($row['carnet'] != "") {
                                                            echo $row['carnet'];
                                                        } ?>
                                                        <?php echo "<div class='fs-6'>" . $row['fecha_hora_concepto_ddmmaaaa_abrev2'] . "</div>"; ?>
                                                    </td>
                                                    <td class="text-end pe-2">
                                                        <?php if ($row['templos'] > 0) { ?>
                                                            <?php echo round($row['templos'], 2);
                                                            if ($row['templos'] > 0) {
                                                                $total_templos += $row['templos'];
                                                            } ?>
                                                        <?php } else { ?>
                                                            -
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                    <?php }
                                        }
                                    } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td style="text-align: right; padding: 8px;" colspan="2"><b>TEMPLOS TOTALES</b></td>
                                        <td style="text-align: right; padding: 8px;"><?php echo round($total_templos, 2); ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <?php if (isset($mensaje_descuento) && $mensaje_descuento != "") { ?>
                            <div class="alert alert-warning mb-5"><?php echo $mensaje_descuento; ?></div>
                        <?php } ?>

                        <?php if (isset($notas_cobrar)) {
                            if ($notas_cobrar != 0) { ?>
                                <div class="table-responsive mb-5">
                                    <table id="tabla_notas" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                                        <thead class="">
                                            <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                                                <th style="display: none;"></th>
                                                <th style="width: 1%; border: 0px;">Finalizar</th>
                                                <th style="border: 0px;">NOTAS COBROS CARNET: <?php echo $carnet_cobrar; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-700 fw-semibold">
                                            <?php if (isset($notas_cobrar)) {
                                                if ($notas_cobrar != 0) {
                                                    foreach ($notas_cobrar as $key => $row) { ?>
                                                        <tr style="background: #feeea3; border: 0px;">
                                                            <td style="display: none;">
                                                                <?php echo $row['fecha_creacion_aaaammdd'] ?>
                                                            </td>
                                                            <td>
                                                                <div class="form-check form-check-sm form-check-custom" onclick="FinalizarNota(this,'<?php echo $row['id_nota_cobrar'] ?>');">
                                                                    <input class="form-check-input" type="checkbox" name="id_nota_cobrar_<?php echo $row['id_nota_cobrar'] ?>" value="<?php echo $row['id_nota_cobrar'] ?>" />
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <?php echo $row['fecha_creacion_ddmmaaaa'] ?><br>
                                                                <a href="#" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="<?php echo $row['nota'] ?>" style="color: blue;">
                                                                    <?php echo substr($row['nota'], 0, 85); ?> ...
                                                                </a>
                                                            </td>
                                                        </tr>
                                            <?php }
                                                }
                                            } ?>
                                        </tbody>
                                    </table>
                                    <div id="borrar_nota" style="display: none;"></div>
                                </div>
                        <?php }
                        } ?>
                    </div>

                    <div class="border p-5 mb-5">
                        <h4>Paso 2. Selecciona el/los carnet/s para el pago.</h4>

                        <?php if (isset($estado_carnet) && $puedo_pagar == 0) { ?>
                            <?php if ($estado_carnet == 0) {  ?>
                                <div class="alert alert-danger mb-5">
                                    No existe el carnet indicado
                                </div>
                            <?php } ?>
                            <?php if ($estado_carnet == 1) {  ?>
                                <div class="alert alert-success mb-5">
                                    Carnet Válido, añadido.
                                </div>
                            <?php } ?>
                            <?php if ($estado_carnet == 2) {  ?>
                                <div class="alert alert-danger mb-5">
                                    El carnet no dispone de ningún servicio de los pendientes de pago.
                                </div>
                            <?php } ?>
                            <?php if ($estado_carnet == 3) {  ?>
                                <div class="alert alert-danger mb-5">
                                    El carnet no dispone de templos
                                </div>
                            <?php } ?>
                            <?php if ($estado_carnet == 4) {  ?>
                                <div class="alert alert-danger mb-5">
                                    El carnet sólo tiene algunos de los servicios necesarios para el pago.
                                </div>
                            <?php } ?>
                        <?php } ?>

                        <div class="row align-items-end mb-5">
                            <?php if ($puedo_pagar == 0) { ?>
                                <div class="col mb-5">
                                    <label for="" class="form-label">Nº Carnet</label>
                                    <input type="text" class="form-control form-control-solid" name="codigo" style="width: 140px;" placeholder="Nº Carnet" />
                                </div>
                                <div class="col mb-5">
                                    <button class="btn btn-info text-inverse-info" type="button" onclick="Comprobar();">Comprobar</button>
                                </div>
                                <div class="col mb-5">
                                    <button class="btn px-1 btn-warning text-inverse-warning" type="button" onclick="VerCarnetsCliente();">Ver Carnets Cliente</button>
                                </div>
                                <div class="col-12 mb-5">
                                    <div id="carnets_del_cliente">
                                        <div class="table-responsive">
                                            <table id="myTable1" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                                                <thead class="">
                                                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                                                        <th></th>
                                                        <th>Carnet</th>
                                                        <th>Cliente</th>
                                                        <th>Templos</th>
                                                        <th>T. Disponibles</th>
                                                        <th>Notas</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-gray-700 fw-semibold">
                                                    <?php if (isset($carnets_cliente)) {
                                                        if ($carnets_cliente != 0) {
                                                            foreach ($carnets_cliente as $key => $row) { ?>
                                                                <?php if ($row['id_tipo'] == 99 || $row['templos_disponibles'] > 0) { ?>
                                                                    <tr>
                                                                        <td><a href="javascript:document.form_carnets.codigo.value='<?php echo $row['codigo']; ?>'; VerCarnetsCliente();" class="btn btn-icon btn-sm btn-success" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Pagar con este carnet"><i class="fas fa-play"></i></a></td>
                                                                        <td>
                                                                            <a class="btn btn-secondary btn-sm btn-text d-inline-flex align-items-center" href="#" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Ver detalle del Carnet" onclick="VerCarnetsPagos(<?php echo $row['id_carnet'] ?>);"><i class="fas fa-id-card"></i> <?php echo $row['codigo']; ?></a>
                                                                        </td>
                                                                        <td>
                                                                            <?php echo $row['cliente']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php if ($row['id_tipo'] != 99) {
                                                                                echo round($row['templos'], 2, 1) . " templos";
                                                                            } else {
                                                                                echo "Especial";
                                                                            } ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php if ($row['id_tipo'] != 99) {
                                                                                echo round($row['templos_disponibles'], 2, 1);
                                                                            } else {
                                                                                echo "-";
                                                                            } ?>
                                                                        </td>
                                                                        <td style="text-align: left;">
                                                                            <?php echo $row['notas']; ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                    <?php }
                                                        }
                                                    } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="col-12">
                                    <div class="alert alert-success" style="width: 98%;">
                                        Ya puedes proceder con el pago.
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <?php $total_templos_carnets = 0;
                        if (isset($carnets_elegidos) && $carnets_elegidos != 0) { ?>
                            <table id="datos" class="table table-striped table-hover table-bordered">
                                <tr>
                                    <th>Carnet</th>
                                    <th>T. Disponibles</th>
                                    <th>Recargar</th>
                                    <th>Borrar</th>
                                </tr>
                                <?php if (isset($carnets_elegidos)) {
                                    if ($carnets_elegidos != 0) {
                                        foreach ($carnets_elegidos as $key => $row) { ?>
                                            <tr>
                                                <td>
                                                    <b><?php echo $row['codigo']; ?></b>
                                                    <br>
                                                    <?php echo $row['cliente']; ?>
                                                    <?php if ($row['notas'] != "") {
                                                        echo "<br>Notas: " . $row['notas'];
                                                    } ?>
                                                </td>
                                                <td>
                                                    <?php if ($row['id_tipo'] == 99) { ?>
                                                        Especial
                                                    <?php } else { ?>
                                                        <?php echo $row['templos_disponibles'];
                                                        $total_templos_carnets += $row['templos_disponibles']; ?>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?php if ($row['id_tipo'] == 99) { ?>
                                                        -
                                                    <?php } else { ?>
                                                        <input type="number" id="templos_recarga" min="0.5" max="19.5" step="0.5" value="" class="from-control form-control-solid" />
                                                        <a href="#" onclick="RecargaCarnet(document.getElementById('templos_recarga').value,<?php echo $row['id_carnet']; ?>);" class="btn btn-sm btn-icon btn-primary text-inverse-primary" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Recargar carnet"><i class="fas fa-plus"></i></a>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>dietario/pagotemplos/ver/<?php echo $id_cliente; ?>/<?php echo $hoy_aaaammdd; ?>/<?php echo $row['id']; ?>" class="btn btn-sm btn-icon btn-danger"><i class="fa-solid fa-trash"></i></a>
                                                </td>
                                            </tr>
                                <?php }
                                    }
                                } ?>
                            </table>
                        <?php } ?>
                    </div>

                    <?php if ($puedo_pagar == 1) { ?>
                        <div class="text-center mb-5">
                            <input type="button" class="btn btn-primary text-inverse-primary" onclick="MarcarPagoTemplos();" value="Pagar los servicios Marcados">
                        </div>
                        <?php } else {
                        if ($accion != "ver") { ?>
                            <div class="alert alert-danger text-center"><?php if ($templos_por_pagar > 0 && $templos_por_pagar <= 11) { ?>
                                    <div>Te faltan <?php echo $templos_por_pagar ?> templos
                                        <br>
                                        ó
                                        <br>
                                        Puedes indicar un carnet que cubra el servicio a pagar.
                                    </div>
                                <?php } ?>
                                <?php if ($templos_por_pagar > 11) { ?>
                                    <div>
                                        Debes de comprar un carnet porque te faltan <?php echo $templos_por_pagar; ?> templos
                                        <br>
                                        ó
                                        <br>
                                        Puedes indicar un carnet que cubra el servicio a pagar.
                                    </div>
                                <?php } ?>
                            </div>
                    <?php }
                    } ?>

                    <?php if ($puedo_pagar == 1) { ?>
                        <div id="div_foto" class="row justify-content-center align-items-start mb-5">

                            <div class="col-6 mb-5 text-center">
                                <input type="hidden" name="foto" id="foto" value="" />
                                <video muted="muted" id="video" class="border border-2 img-fluid img-rounded img-thumbnail"></video>
                                <canvas id="canvas" style="display: none;"></canvas>
                                <div class="input-group mb-5">
                                    <select name="listaDeDispositivos" id="listaDeDispositivos" class="form-select form-select-solid">
                                        <option value="">Seleccionar...</option>
                                    </select>
                                    <button id="boton" type="button" class="btn btn-success btn-icon"><i class="fas fa-camera"></i></button>
                                </div>
                            </div>
                            <div class="col-6 mb-5 text-center">
                                <img id="fotocliente" src="" class="border border-2 img-fluid img-rounded img-thumbnail mb-1" alt="Cliente" style="display: none;">
                                <div id="estado"></div>
                            </div>
                        </div>
                    <?php } ?>
                </form>
            <?php } else { ?>
                <div class="alert alert-primary">No hay servicios para pagar con templos</div>
                <div class="row my-5">
                    <div class="col-12 text-center">
                        <button type="button" class="btn btn-warning text-inverse-warning" onclick="Cerrar();">Cerrar</button>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <script>
        <?php if (isset($registros)) {
            if ($registros != 0) { ?>
                <?php if ($puedo_pagar == 0) { ?>
                    document.getElementById("carnets_del_cliente").style.display = "none";
                <?php } ?>
        <?php }
        } ?>

        function MarcarPagoTemplos() {
            //22/05/20 Para asegurar ue ha tomodao la Foto
            xfoto = document.getElementById('foto').value;
            if (xfoto == "") {
                alert("La foto no se ha cargado...");
                return false;
            }
            //Fin 
            formulario = document.getElementsByName("servicios_marcados[]");
            sw = 0;
            for (var i = 0; i < formulario.length; i++) {
                if (formulario[i].checked) {
                    sw = 1;
                }
            }
            if (sw == 1) {
                document.form_carnets.action = '<?php echo base_url(); ?>dietario/pagotemplos/marcarpago/<?php echo $id_cliente; ?>/<?php echo $hoy_aaaammdd; ?>';
                document.form_carnets.submit();
                return true;
            } else {
                alert("DEBES DE INDICAR AL MENOS UN SERVICIO PARA EL PAGO");
                return false;
            }
        }

        function Comprobar() {
            formulario = document.getElementsByName("servicios_marcados[]");
            sw = 0;
            for (var i = 0; i < formulario.length; i++) {
                if (formulario[i].checked) {
                    sw = 1;
                }
            }
            if (sw == 1) {
                document.form_carnets.submit();
                return true;
            } else {
                alert("DEBES DE INDICAR AL MENOS UN SERVICIO PARA EL PAGO");
                return false;
            }
        }

        function Cerrar() {
            window.opener.location.reload();
            window.close();
        }

        function RecargaCarnet(templos, id_carnet) {
            if (templos < 0.5 || templos > 19.5) {
                alert("El número de templos está fuera de rango");
                return false;
            } else {
                sessionStorage.setItem("recarga_templos", "true");
                document.location.href = '<?php echo base_url(); ?>dietario/recargar_carnet/<?php echo $id_cliente; ?>/<?php echo $hoy_aaaammdd; ?>/' + id_carnet + '/' + templos;
            }
            // PagoEfectivo();
        }
        window.onload = function() {
            var reloading = sessionStorage.getItem("recarga_templos");
            if (reloading) {
                sessionStorage.removeItem("recarga_templos");
                PagoEfectivo();
            }
        }

        function VerCarnetsCliente() {
            if (document.getElementById("carnets_del_cliente").style.display == "none") {
                document.getElementById("carnets_del_cliente").style.display = "block";
            } else {
                document.getElementById("carnets_del_cliente").style.display = "none";
            }
        }

        function VerCarnetsPagos(id_carnet) {
            var posicion_x;
            var posicion_y;
            var ancho = 640;
            var alto = 480;
            posicion_x = (screen.width / 2) - (ancho / 2);
            posicion_y = (screen.height / 2) - (alto / 2);
            window.open("<?php echo base_url(); ?>dietario/carnets_pago/ver/" + id_carnet, "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
        }

        function PagoEfectivo() {
            var posicion_x;
            var posicion_y;
            var ancho = 750;
            var alto = 570;
            posicion_x = (screen.width / 2) - (ancho / 2);
            posicion_y = (screen.height / 2) - (alto / 2);
            window.open("<?php echo base_url(); ?>dietario/pagoeuros/ver_recargas/<?php echo $id_cliente; ?>/<?php echo $hoy_aaaammdd ?>", "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
        }

        function FinalizarNota(fila, id_nota_cobrar) {
            if (confirm("¿DESEA FINALIZAR LA NOTA DE COBRO MARCADA?")) {
                document.getElementById("tabla_notas").deleteRow(fila.parentNode.rowIndex);
                $.ajax({
                    url: "<?php echo base_url(); ?>clientes/finalizar_una_nota_cobrar/" + id_nota_cobrar,
                    success: function(result) {
                        $("#borrar_nota").html(result);
                    }
                });
                var f = document.getElementById("tabla_notas").rows.length;
                if (f == 1) {
                    document.getElementById("tabla_notas").deleteRow(0);
                }
            }
        }

        <?php if ($accion == "marcarpago") { ?>
            Cerrar();
        <?php } ?>

        function previo() {
            console.log('No enviar submit');
            alert('No enviar ...');
            return false;
        }


        const tieneSoporteUserMedia = () =>
            !!(navigator.getUserMedia || (navigator.mozGetUserMedia || navigator.mediaDevices.getUserMedia) || navigator.webkitGetUserMedia || navigator.msGetUserMedia)

        const _getUserMedia = (...arguments) =>
            (navigator.getUserMedia || (navigator.mozGetUserMedia || navigator.mediaDevices.getUserMedia) || navigator.webkitGetUserMedia || navigator.msGetUserMedia).apply(navigator, arguments);

        // Declaramos elementos del DOM
        const $video = document.querySelector("#video"),
            $canvas = document.querySelector("#canvas"),
            $estado = document.querySelector("#estado"),
            $boton = document.querySelector("#boton"),
            $listaDeDispositivos = document.getElementById("listaDeDispositivos");
        const limpiarSelect = () => {
            for (let x = $listaDeDispositivos.options.length - 1; x >= 0; x--)
                $listaDeDispositivos.remove(x);
        };
        const obtenerDispositivos = () => navigator
            .mediaDevices
            .enumerateDevices();
        // La función que es llamada después de que ya se dieron los permisos
        // Lo que hace es llenar el select con los dispositivos obtenidos
        const llenarSelectConDispositivosDisponibles = () => {
                limpiarSelect();
                const option = document.createElement('option');
                option.value = '';
                option.text = 'Seleccionar...';
                $listaDeDispositivos.appendChild(option);
                obtenerDispositivos()
                    .then(dispositivos => {
                        const dispositivosDeVideo = [];
                        dispositivos.forEach(dispositivo => {
                            const tipo = dispositivo.kind;
                            if (tipo === "videoinput") {
                                dispositivosDeVideo.push(dispositivo);
                            }
                        });
                        // Vemos si encontramos algún dispositivo, y en caso de que si, entonces llamamos a la función
                        if (dispositivosDeVideo.length > 0) {
                            // Llenar el select
                            dispositivosDeVideo.forEach(dispositivo => {
                                const option = document.createElement('option');
                                option.value = dispositivo.deviceId;
                                option.text = dispositivo.label;
                                $listaDeDispositivos.appendChild(option);
                            });
                        }
                    });
            }
            (function() {
                // Comenzamos viendo si tiene soporte, si no, nos detenemos
                if (!tieneSoporteUserMedia()) {
                    alert("Lo siento. Tu navegador no soporta esta característica");
                    $estado.innerHTML = "Parece que tu navegador no soporta esta característica. Intenta actualizarlo.";
                    return;
                }
                //Aquí guardaremos el stream globalmente
                let stream;
                // Comenzamos pidiendo los dispositivos
                obtenerDispositivos()
                    .then(dispositivos => {
                        // Vamos a filtrarlos y guardar aquí los de vídeo
                        const dispositivosDeVideo = [];
                        // Recorrer y filtrar
                        dispositivos.forEach(function(dispositivo) {
                            const tipo = dispositivo.kind;
                            if (tipo === "videoinput") {
                                dispositivosDeVideo.push(dispositivo);
                            }
                        });
                        // Vemos si encontramos algún dispositivo, y en caso de que si, entonces llamamos a la función
                        // y le pasamos el id de dispositivo
                        if (dispositivosDeVideo.length > 0) {
                            // Mostrar stream con el ID del primer dispositivo, luego el usuario puede cambiar
                            mostrarStream(dispositivosDeVideo[0].deviceId);
                        }
                    });
                const mostrarStream = idDeDispositivo => {
                    _getUserMedia({
                            video: {
                                // Justo aquí indicamos cuál dispositivo usar
                                deviceId: idDeDispositivo,
                            }
                        },
                        (streamObtenido) => {
                            // Aquí ya tenemos permisos, ahora sí llenamos el select,
                            // pues si no, no nos daría el nombre de los dispositivos
                            llenarSelectConDispositivosDisponibles();
                            // Escuchar cuando seleccionen otra opción y entonces llamar a esta función
                            $listaDeDispositivos.onchange = () => {
                                // Detener el stream
                                if (stream) {
                                    stream.getTracks().forEach(function(track) {
                                        track.stop();
                                    });
                                }
                                // Mostrar el nuevo stream con el dispositivo seleccionado
                                mostrarStream($listaDeDispositivos.value);
                            }
                            // Simple asignación
                            stream = streamObtenido;
                            // Mandamos el stream de la cámara al elemento de vídeo
                            $video.srcObject = stream;
                            $video.play();
                            //Escuchar el click del botón para tomar la foto
                            //Escuchar el click del botón para tomar la foto
                            $boton.addEventListener("click", function(event) {

                                event.preventDefault();
                                //Pausar reproducción
                                $video.pause();
                                //Deshabilitar el botón pagar
                                $('#pagar').attr("disabled"); //desabilita boton
                                //Obtener contexto del canvas y dibujar sobre él
                                let contexto = $canvas.getContext("2d");
                                $canvas.width = $video.videoWidth;
                                $canvas.height = $video.videoHeight;
                                contexto.drawImage($video, 0, 0, $canvas.width, $canvas.height);
                                let foto = $canvas.toDataURL(); //Esta es la foto, en base 64
                                console.log('Foto base64 ' + foto);
                                $estado.innerHTML = "Enviando foto. Por favor, espera...";

                                xfoto = encodeURIComponent(foto);
                                var parametros = {
                                    "xfoto": xfoto
                                };
                                $.ajax({
                                    data: parametros,
                                    url: '<?php echo base_url(); ?>dietario/guardar_foto',
                                    type: 'post',
                                    beforeSend: function() {
                                        console.log('voy ');
                                        //alert ('Ya va');
                                        document.getElementById('estado').classList.add('alert');
                                        document.getElementById('estado').classList.add('alert-primary');
                                        document.getElementById('estado').classList.add('fw-bold');
                                        $("#estado").html("Procesando, espere por favor...");
                                    },
                                    success: function(response) {
                                        //$("#resultado").html(response);
                                        //alert ('Respuesta '+response);
                                        nombreDeLaFoto = response;
                                        document.getElementById('foto').value = nombreDeLaFoto;
                                        console.log('Nombre 2 : ' + nombreDeLaFoto);
                                        //document.getElementById('estado').style.backgroundColor = "#ff4000";
                                        $estado.innerHTML = "Guardada correctamente.";
                                        camino = '<?php echo base_url(); ?>/recursos/foto/' + nombreDeLaFoto;
                                        document.getElementById("fotocliente").src = camino;
                                        document.getElementById("fotocliente").style.display = "block";

                                        //$.get('<?php echo base_url(); ?>dietario/marcar/'+nombreDeLaFoto, function(data, status){
                                        //      alert("Data: " + data + "\nStatus: " + status);
                                        //    });
                                        //console.log('Paso ...');
                                        //document.getElementById("fotocliente").src=camino;
                                        //document.getElementById("fotocliente").style.display="block";

                                    }
                                });
                                //Reanudar reproducción
                                $video.play();
                                //Habilitar Pagar
                                $('#pagar').removeAttr("disabled"); //habilita boton
                            });
                        }, (error) => {
                            console.log("Permiso denegado o error: ", error);
                            $estado.innerHTML = "No se puede acceder a la cámara, o no diste permiso.";
                        });
                }
            })();
        //Con y Sin Foto
        var idsin = document.getElementById("sinfoto");
        var idcon = document.getElementById("confoto");
        idsin.addEventListener("click", function(event) {
            event.preventDefault();
            sinfoto();
        });
        idcon.addEventListener("click", function(event) {
            event.preventDefault();
            confoto();
        });

        function muestraFoto(nombreDeLaFoto) {
            alert('Si ' + nombreDeLaFoto);
            document.getElementById("fotocliente").src = nombreDeLaFoto + "?" + Math.random();
            document.getElementById("fotocliente").style.display = "block";
        }

        function sinfoto() {
            document.getElementById('div_foto').style.display = "none";
            document.getElementById('sinfoto').style.display = "none";
            document.getElementById('confoto').style.display = "block";
            document.getElementById('pagar').disabled = false;
        }

        function confoto() {
            document.getElementById('div_foto').style.display = "block";
            document.getElementById('sinfoto').style.display = "block";
            document.getElementById('confoto').style.display = "none";
            document.getElementById('pagar').disabled = true;
        }
    </script>

</body>

</html>