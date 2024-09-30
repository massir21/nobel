<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
    <?php if ($accion == "nuevo") {
        $pagetitle = 'AÑADIR NUEVO CARNET';
        $actionform = base_url() . 'carnets/gestion/guardar/';
    } else if ($accion == "nueva_venta") {
        $pagetitle = 'VENTA DE NUEVO CARNET';
        $actionform = base_url() . 'carnets/gestion/guardar_venta/';
    } elseif(isset($carnet) && $carnet != 0){
        $pagetitle = 'EDITAR CARNET ' . (isset($carnet) && $carnet != 0) ? $carnet[0]['codigo'] : '';
        $actionform = base_url() . 'carnets/gestion/modificar_carnet/' . (isset($carnet)) ? $carnet[0]['id_carnet'] : '';
    } else{
        $pagetitle = 'CARNET';
        $actionform = base_url() . 'carnets/gestion/modificar_carnet/';
    } ?>

    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase"><?= $pagetitle ?></h1>
    <div class="card card-flush m-5">
        <div class="card-body p-5">
            <?php if ($accion == "nuevo") { ?>
                <div class="alert alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
                    <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center text-uppercase">Atención: Vas a añadir un Carnet sin pasar por caja</div>
                </div>
            <?php } ?>

            <form id="form_carnets" action="<?= $actionform ?>" role="form" method="post" name="form_carnets">
                <div class="row mb-5 align-items-end">
                    <div class="col-8">
                        <label for="" class="form-label">Cliente:</label>
                        <select name="id_cliente" id="cliente" class="form-select form-select-solid" data-placeholder="Elegir ...">
                            <?php if (isset($cliente_elegido) && $cliente_elegido != 0 && $cliente_elegido[0]['id_cliente'] > 0) { ?>
                                <option value="<?= $cliente_elegido[0]['id_cliente'] ?>" selected><?= $cliente_elegido[0]['nombre'] . ' ' . $cliente_elegido[0]['apellidos'] . ' (' . $cliente_elegido[0]['telefono'] . ')'; ?></option>
                            <?php } ?>
                        </select>

                        <script type="text/javascript">
                            $("#cliente").select2({
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
                        <?php if ($accion == "anadir" || $accion == "modificar") { ?>
                            <input type="hidden" name="id_cliente" value="<?php echo $carnet[0]['id_cliente']; ?>" />
                        <?php } ?>
                    </div>

                    <div class="w-auto">
                        <?php if ($accion == "nuevo") { ?>
                            <button type="button" class="btn btn-info text-inverse-info btn-icon" data-bs-target="#stack-cliente" data-bs-toggle="modal" title="Añadir un nuevo Cliente"><i class="fas fa-user-plus"></i></button>
                        <?php } elseif ($accion == "nueva_venta") { ?>
                            <button type="button" class="btn btn-info text-inverse-info btn-icon" data-bs-target="#stack-cliente-venta" data-bs-toggle="modal" title="Añadir un nuevo Cliente"><i class="fas fa-user-plus"></i></button>
                        <?php } ?>
                    </div>
                </div>

                <div class="row mb-5 align-items-start">
                    <div class="col-8 mb-5">
                        <label for="" class="form-label">Tipo:</label>
                        <select name="id_tipo" id="id_tipo" onchange="Servicios();" data-placeholder="Elegir ..." class="form-select form-select-solid" data-control="select2" required <?= ($accion == "anadir" || $accion == "modificar") ? 'disabled' : '' ?>>
                            <option value=""></option>
                            <?php if (isset($tipos_carnets)) {
                                if ($tipos_carnets != 0) {
                                    foreach ($tipos_carnets as $key => $row) { ?>
                                        <option value="<?= $row['id_tipo'] ?>" <?= (isset($id_tipo) && $row['id_tipo'] == $id_tipo) ? "selected" : '' ?>><?= $row['descripcion']; ?></option>
                                    <?php }
                                }
                            } ?>
                        </select>
                        <?php if ($accion == "anadir" || $accion == "modificar") { ?>
                            <input type="hidden" name="id_tipo" value="99" />
                        <?php } ?>
                    </div>
                    <div class="col-4 mb-5">
                        <?php if ($accion == "nuevo" || $accion == "nueva_venta") { ?>
                            <div style="display: none;" id="templos_disponibles" name="templos_disponibles">
                                <?php if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 3) { ?>
                                    <label for="" class="form-label">Templos disponibles:</label>
                                    <input type="number" name="templos_disponibles" class="form-control form-control-solid" value="" min="0" />
                                    <span class="w-100 text-muted">(por defecto todos los templos del carnet)</span>
                                <?php } ?>
                            </div>
                        
                            <div style="overflow: hidden; display: none;" id="codigo_pack">
                                <label for="" class="form-label">Pack:</label>
                                <input type="text" name="codigo_pack_online" class="form-control form-control-solid" value="" placeholder="Introducir Código, si es un Pack-Online" />
                            </div>
                            
                        <?php } ?>
                    </div>
                    <div class="col-6 mb-5">
                        <label for="" class="form-label">Nº Carnet:</label>
                        <input name="numero_carnet" class="form-control form-control-solid" type="text" placeholder="Nº Carnet" required enabled />
                    </div>

                </div>

                <div class="row mb-5 align-items-end">

                    <div class="col-md-12" id="servicios_familias" style="display: none;">
                        <?php if ($accion != "modificar") { ?>
                            <div>
                                <strong>Elige los servicios para el carnet</strong>
                            </div>
                            <div style="overflow: hidden;">
                                <div style="float: left; width: 70%; margin-right: 1%">
                                    <select name="id_servicio" id="servicioListado"data-placeholder="Elegir ..." class="form-select form-select-solid" data-control="select2">
                                        <option value=""></option>
                                        <?php if (isset($servicios)) {
                                            if ($servicios != 0) {
                                                foreach ($servicios as $key => $row) { ?>
                                                    <option value='<?php echo $row['id_servicio']; ?>' <?php if (isset($cita[0]['id_servicio'])) {
                                                                                                            if ($row['id_servicio'] == $cita[0]['id_servicio']) {
                                                                                                                echo "selected";
                                                                                                            }
                                                                                                        } ?>>
                                                        <?php echo strtoupper($row['nombre_familia'] . " - " . $row['nombre_servicio'] . " (" . $row['duracion'] . " min) - PVP: " . $row['pvp']); ?>
                                                    </option>
                                        <?php }
                                            }
                                        } ?>
                                    </select>
                                </div>
                                <div style="float: left; width: 22%; margin-right: 1%">
                                    <input name="cantidad" id="cantidad" class="form-control form-control-solid" type="number" placeholder="Cantidad" />
                                </div>
                                <div style="float: left; width: 5%;">
                                    <input type="button" id="agregar" class="btn btn-transparent green btn-outline btn-circle btn-sm active" value=" + " />
                                </div>
                            </div>
                            <br>
                        <?php } ?>

                        <table id="myTable1" class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Servicios</th>
                                    <?php if ($accion != "modificar") { ?>
                                        <th style="width: 5%;">Quitar</th>
                                    <?php } ?>
                                    <th style="display: none;"></th>
                                </tr>
                            </thead>
                            <?php if ($accion != "modificar") { ?>
                                <tbody id="elementos_servicios">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td style="display: none;"></td>
                                    </tr>
                                </tbody>
                            <?php } else { ?>
                                <tbody class="text-gray-700 fw-semibold">
                                    <?php if (isset($carnets_servicios)) {
                                        if ($carnets_servicios != 0) {
                                            foreach ($carnets_servicios as $key => $row) { ?>
                                                <tr>
                                                    <td>
                                                        <?php echo strtoupper($row['nombre_familia'] . " - " . $row['nombre_servicio']); ?>
                                                    </td>
                                                    <td style="display: none;"></td>
                                                </tr>
                                    <?php }
                                        }
                                    } ?>
                                </tbody>
                            <?php } ?>
                        </table>
                        <div style="overflow: hidden;">
                            <div style="float: right;">
                                <input name="precio" class="form-control form-control-solid" type="number" step="0.01" value="<?php if (isset($carnet)) {
                                                                                                                                    echo $carnet[0]['precio'];
                                                                                                                                } else {
                                                                                                                                    echo 0;
                                                                                                                                } ?>" style="text-align: right; width: 100px;" required />
                            </div>
                            <div style="float: right; margin-right: 5px;">
                                <b>Precio Actual Carnet:</b>
                            </div>
                        </div>
                        <div style="margin-top: 5px;">
                            <textarea class="form-control form-control-solid" name="notas" row="3" placeholder="Nota adicional aclaratoria si se modifica el Precio Actual Carnet"><?php if (isset($carnet)) {
                                                                                                                                                                                        echo $carnet[0]['notas'];
                                                                                                                                                                                    } ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <hr>
                    </div>
                    <div class="col-md-12" style="text-align: center;">
                        <button type="button" class="btn btn-sm btn-secondary text-inverse-secondary" onclick="window.close();">Cancelar</button>
                        <button type="button" class="btn btn-sm btn-primary text-inverse-primary" onclick="GuardarEspecial();">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- END CONTENT BODY -->
    <div id="stack-cliente" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-focus-on="input:first">
        <div class="modal-dialog">
            <div class="modal-content">
                <form name="form_nuevo_cliente" id="form_nuevo_cliente" action="<?php echo base_url(); ?>carnets/gestion/nuevo/-99" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">AÑADIR NUEVO CLIENTE</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-5 border-bottom">
                            <div class="col-12">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control form-control-solid" name="nombre" placeholder="Nombre" required />
                            </div>
                            <div class="col-12">
                                <label class="form-label">Apellidos</label>
                                <input type="text" class="form-control form-control-solid" name="apellidos" placeholder="Apellidos" required />
                            </div>
                            <div class="col-12">
                                <label class="form-label">Teléfono</label>
                                <input type="text" class="form-control form-control-solid" name="telefono" placeholder="Teléfono" style="width: 150px;" required />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Añadir Cliente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="stack-cliente-venta" class="modal fade" tabindex="-1" data-focus-on="input:first">
        <div class="modal-dialog">
            <div class="modal-content">
                <form name="form_nuevo_cliente" id="form_nuevo_cliente" action="<?php echo base_url(); ?>carnets/gestion/nueva_venta/-99" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">AÑADIR NUEVO CLIENTE</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-5 border-bottom">
                            <div class="col-12">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control form-control-solid" name="nombre" placeholder="Nombre" required />
                            </div>
                            <div class="col-12">
                                <label class="form-label">Apellidos</label>
                                <input type="text" class="form-control form-control-solid" name="apellidos" placeholder="Apellidos" required />
                            </div>
                            <div class="col-12">
                                <label class="form-label">Teléfono</label>
                                <input type="text" class="form-control form-control-solid" name="telefono" placeholder="Teléfono" style="width: 150px;" required />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Añadir Cliente</button>
                    </div>
                </form>
            </form>
        </div>
    </div>

    <script>
        function Servicios() {
            if (document.getElementById("id_tipo").value == 99) {
                document.getElementById("servicios_familias").style.display = 'block';
                document.getElementById("templos_disponibles").style.display = 'none';
                document.getElementById("codigo_pack").style.display = 'block';
            } else {
                document.getElementById("servicios_familias").style.display = 'none';
                document.getElementById("templos_disponibles").style.display = 'block';
                document.getElementById("codigo_pack").style.display = 'none';
            }
        }

        function CarnetNumero() {
            if (document.form_carnets.marcar_carnet.checked) {
                document.form_carnets.numero_carnet.disabled = true;
            } else {
                document.form_carnets.numero_carnet.disabled = false;
            }
        }

        function Recarga() {
            <?php if ($accion == "nuevo") { ?>
                document.form_cita.action = '<?php echo base_url(); ?>agenda/citas/nuevo/';
                document.form_cita.submit();
            <?php } ?>
        }

        function Cerrar() {
            window.opener.location.reload();
            window.close();
        }

        function AnadirServicio() {
            <?php if ($accion == "nuevo" || $accion == "modificar") { ?>
                document.form_carnets.action = '<?php echo base_url(); ?>carnets/gestion/anadir/<?php if ($id_carnet != null) {
                                                                                                    echo $id_carnet;
                                                                                                } ?>';
            <?php } ?>
            <?php if ($accion == "nueva_venta") { ?>
                document.form_carnets.action = '<?php echo base_url(); ?>carnets/gestion/anadir_venta/<?php if ($id_carnet != null) {
                                                                                                            echo $id_carnet;
                                                                                                        } ?>';
            <?php } ?>
            document.form_carnets.submit();
        }

        function GuardarEspecial() {
            <?php if ($accion != "modificar") { ?>
                if (datos.length > 0) {
                    ElementosTabla(1);
                }
            <?php } ?>
            <?php if ($accion != "modificar") { ?>
                if (document.form_carnets.id_cliente.value == "") {
                    alert("DEBES DE INDICAR UN CLIENTE");
                    return false;
                }
                if (document.form_carnets.id_tipo.value == "") {
                    alert("DEBES DE INDICAR UN TIPO");
                    return false;
                }
            <?php } ?>
            if (document.form_carnets.numero_carnet.value == "") {
                alert("DEBES DE INDICAR UN NÚMERO DE CARNET");
                return false;
            }
            <?php if ($accion != "modificar") { ?>
                if (document.form_carnets.id_tipo.value == 99) {
                    if (document.form_carnets.precio.value == "" && document.form_carnets.id_tipo.value == 99) {
                        alert("DEBES DE INDICAR UN PRECIO ACTUAL");
                        return false;
                    }
                    if (datos.length == 0) {
                        alert("DEBES DE AÑADIR AL MENOS UN SERVICIO");
                        return false;
                    }
                }
            <?php } ?>
            document.form_carnets.submit();
            return true;
        }

        function Borrar(id) {
            document.form_carnets.action = '<?php echo base_url(); ?>carnets/gestion/borrar_servicio/<?php if ($id_carnet != null) {
                                                                                                            echo $id_carnet;
                                                                                                        } ?>/' + id;
            document.form_carnets.submit();
        }
        <?php if ($accion == "guardar" || $accion == "guardar_venta") { ?>
            Cerrar();
        <?php } ?>
        <?php if ($accion == "modificar_carnet") { ?>
            window.close();
        <?php } ?>
        <?php if ($accion == "modificar" || $accion == "anadir") { ?>
            Servicios();
        <?php } ?>
        /* ------------------------------------------------------------------------ */
        /* Control de servicios que se añaden 
        /* ------------------------------------------------------------------------ */
        var tablaElementos = document.getElementById('elementos_servicios');
        var txtServicio = document.getElementById('servicioListado');
        var txtCantidad = document.getElementById('cantidad');
        var btnAgregar = document.getElementById('agregar');
        var datos = [];

        function btnBorrar_Click(event) {
            document.form_carnets.precio.value = parseFloat(document.form_carnets.precio.value) - parseFloat(this.elemento.precio);
            tablaElementos.removeChild(tablaElementos.childNodes[this.elemento.item]);
            datos.splice(this.elemento.item, 1);
            // Cada vez que borro vuelvo a regener la tabla para que
            // los indices se recalculen.
            ElementosTabla();
        }

        function btnAgregar_Click(event) {
            if (document.form_carnets.id_servicio.value == "") {
                alert("DEBES DE INDICAR UN SERVICIO ANTES DE AÑADIR");
                return false;
            }
            if (txtCantidad.value <= 0) {
                alert("DEBES DE INDICAR UNA CANTIDAD MAYOR DE 0");
                return false;
            }
            var servicio = txtServicio[txtServicio.selectedIndex].innerHTML;
            var servicio_id = txtServicio.value;
            var res = servicio.split("PVP: ");
            var precioServicio = parseFloat(res[1]);
            var cantidad = txtCantidad.value;
            // ... Tantas veces como la cantidad
            if (txtCantidad.value > 0) {
                for (var v = 0; v < txtCantidad.value; v++) {
                    // JSON
                    var item = {
                        servicio: servicio.trim(),
                        servicio_id: servicio_id.trim(),
                        precio: precioServicio,
                        item: 0
                    };
                    datos.push(item);
                    document.form_carnets.precio.value = parseFloat(document.form_carnets.precio.value) + precioServicio;
                }
                ElementosTabla();
            }
            return true;
        };

        function ElementosTabla(enviar) {
            tablaElementos.innerHTML = '';
            for (var i = 0; i < datos.length; i++) {
                var elemento = datos[i];
                var tr = document.createElement('tr');
                var td1 = document.createElement('td');
                var td2 = document.createElement('td');
                var td3 = document.createElement('td');
                td3.style.display = 'none';
                td2.style = "text-align: center;";
                tr.appendChild(td1);
                tr.appendChild(td2);
                tr.appendChild(td3);
                td1.textContent = elemento.servicio;
                elemento.item = i;
                tablaElementos.appendChild(tr);
                var nuevoBoton = document.createElement('button');
                nuevoBoton.type = 'button';
                nuevoBoton.textContent = 'X';
                nuevoBoton.style = "background: red; color: #fff; border: 0px;";
                nuevoBoton.addEventListener('click', btnBorrar_Click);
                nuevoBoton.elemento = elemento;
                td2.appendChild(nuevoBoton);
                var input = document.createElement('input');
                input.setAttribute("type", "hidden");
                input.value = elemento.servicio_id;
                input.name = "servicios_carnet[]";
                td3.appendChild(input);
                if (enviar == 1) {
                    document.getElementById('form_carnets').appendChild(input);
                }
            }
        }
        btnAgregar.addEventListener('click', btnAgregar_Click);
    </script>

</body>

</html>