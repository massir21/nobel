<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">Pago Efectivo o Tarjeta</h1>

    <div class="card card-flush m-5">
        <div class="card-body p-5">
            <h3 class="text-center">CONCEPTOS - <?php echo $registros[0]['cliente']; ?></h3>
            <?php if ($saldo_cliente > 0) { ?>
                <div class="alert alert-primary d-flex p-5">
                    <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center text-uppercase">SALDO DISPONIBLE: <?php echo number_format($saldo_cliente, 2, ',', '.') . "€"; ?></div>
                </div>
            <?php } ?>
            <div class="alert alert-warning p-5 text-center">Los conceptos no marcados, quedarán como No Pagados</div>
            <form id="form_pagoeuros" action="<?php echo base_url(); ?>dietario/pagoeuros/guardar/<?php echo $registros[0]['id_cliente']; ?>" role="form" method="post" name="form_pagoeuros">
                <div class="table-responsive p-4 mb-5 border">
                    <table class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                        <thead class="">
                            <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                                <th style="display: none;">ID</th>
                                <th></th>
                                <th>Serv/Prod/Carnet</th>
                                <th>Descuento €</th>
                                <th>Descuento %</th>
                                <th>Euros</th>
                                <th>Estado</th>
                                <?php if ($saldo_cliente > 0) { ?>
                                    <th>Descontar Saldo</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 fw-semibold">
                            <?php $total_importe = 0;
                            $total_templos = 0;
                            $xefectivo = "no";
                            if (isset($registros)) {
                                if ($registros != 0) {
                                    $i = 0;
                                    foreach ($registros as $key => $row) {
                                        if ($row['id_tipo'] != "") {
                                            if ($row['id_tipo'] == 99) {
                                                $row['importe_euros'] = $row['pvp_carnet'];
                                            }
                                            if ($row['id_tipo'] < 99) {
                                                if ($row['recarga'] == 0) {
                                                    $row['importe_euros'] = $row['pvp_carnet'];
                                                }
                                            }
                                        }
                                        if ($row['carnet'] != "") {
                                            $row['color_estado'] = "#dee8fd";
                                        } ?>
                                        <tr>
                                            <td style="display: none;">
                                                <?php echo $row['id_dietario']; ?>
                                            </td>
                                            <td>
                                                <div class="form-check form-check-solid form-switch">
                                                    <input class="form-check-input" type="checkbox" name="marcados[]" value="<?php echo $row['id_dietario']; ?>" onclick="Marcado(this,<?php echo $i; ?>);" <?= ((in_array($row['id_tipo'], $en_efectivo)) && $row['recarga'] != 1) ? 'en_efectivo' : '' ?> />
                                                </div>
                                                <input type="hidden" name="servicios[]" value="<?php echo $row['id_servicio']; ?>" />
                                                <input type="hidden" name="solo_pago[]" value="<?php echo $row['solo_pago']; ?>" />
                                            </td>

                                            <td>
                                                <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6"><?= ($row['servicio'] != "") ? $row['servicio'] : '' ?>
                                                    <?= ($row['producto'] != "") ? $row['producto'] : '' ?>
                                                    <?= ($row['carnet'] != "") ? $row['carnet'] : '' ?>
                                                    <?= ($row['recarga'] == 1) ? " (Recarga)" : '' ?></span>
                                                <span class="text-muted fw-semibold text-muted d-block fs-7"></span><?= $row['fecha_hora_concepto_ddmmaaaa_abrev2'] ?></span>
                                                <?= ((in_array($row['id_tipo'], $en_efectivo)) && ($row['recarga'] != 1)) ? '<small style="color: red">Solo pago en efectivo</small>' : '' ?>
                                                <?php if ($row['solo_pago'] == "efectivo") {
                                                    $xefectivo = "si";
                                                    echo '<small style="color: red">Solo pago en efectivo</small>';
                                                } ?>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" min="0" max="<?php echo $row['importe_euros']; ?>" value="0" name="descuento_euros[]" class="form-control form-control-solid" onchange="DescuentoEuros(this,'<?php echo $row['importe_euros']; ?>',<?php echo $i; ?>);" onkeyup="DescuentoEuros(this,'<?php echo $row['importe_euros']; ?>',<?php echo $i; ?>);" required disabled />
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" min="0" max="100" value="0" name="descuento_porcentaje[]" class="form-control form-control-solid" onchange="DescuentoPorcentaje(this,'<?php echo $row['importe_euros']; ?>',<?php echo $i; ?>);" onkeyup="DescuentoPorcentaje(this,'<?php echo $row['importe_euros']; ?>',<?php echo $i; ?>);" required disabled />
                                            </td>
                                            <td class="text-end">
                                                <input type="number" step="0.01" min="0" value="<?php echo round($row['importe_euros'], 2); ?>" name="importe_euros[]" class="form-control form-control-solid" disabled />
                                            </td>
                                            <td style="background-color: <?php echo $row['color_estado']; ?> !important;">
                                                <?= $row['estado'] ?>
                                            </td>
                                            <?php if ($saldo_cliente > 0) { ?>
                                                <td>
                                                    <input type="number" step="0.01" min="0" max="<?php echo $row['importe_euros']; ?>" value="0" name="usa_saldo[]" class="form-control form-control-solid" onchange="UsaSaldo(this,'<?php echo $row['importe_euros']; ?>',<?php echo $i; ?>);" onkeyup="UsaSaldo(this,'<?php echo $row['importe_euros']; ?>',<?php echo $i; ?>);" required disabled />
                                                </td>
                                            <?php } else { ?>
                                                <input type="hidden" value="0" name="usa_saldo[]">
                                            <?php } ?>
                                        </tr>
                            <?php $i++;
                                    }
                                }
                            } ?>
                        </tbody>
                    </table>
                </div>
                <div class="row mb-5 align-items-end" id="div_cupones">
                    <div class="col-12 mb-5">
                        <legend>Si el pago es con tarjeta o efectivo</legend>
                    </div>
                    <div class="col-8 mb-5">
                        <label for="" class="form-label">¿Tienes un cupón? Introcucelo aquí para usarlo</label>
                        <input type="text" class="form-control form-control-solid" name="cupon" id="cupon" placeholder="Introducir Cupón...">
                        <span id="mensaje_cupon" class="fs-7 fw-bold"></span>
                    </div>
                    <div class="col mb-5">
                        <button class="btn btn-primary text-inverse-primary" type="button" id="comprobar_cupon" type="button" onclick="ComprobarCupon('<?php echo $cliente[0]['id_cliente']; ?>');">Comprobar cupón</button>
                    </div>
                </div>

                <div class="border p-4 mb-5">
                    <div class="fs-2 text-center fw-bolder">
                        TOTAL A PAGAR:
                        <input name="total_importes_marcados" typ="number" value="0" style="text-align: right; width: 80px; border: 0px; background: #fff; font-weight: bold;" disabled /> €
                        <span class="mx-5">/</span>
                        <span id="faltan" style="color: red;">FALTAN:</span>
                        <input id="faltan_importe" name="falta_importe" typ="number" value="0" style="text-align: right; width: 80px; border: 0px; background: #fff; font-weight: bold; color: red;" disabled />
                        <span id="faltan_simbolo" style="color: red;">€</span>
                    </div>
                </div>

                <div class="row mb-5 align-items-end border-bottom">
                    <div class="col mb-5">
                        <label for="" class="form-label text-center">Importe en Efectivo</label>
                        <input name="pagado_efectivo" class="form-control form-control-solid" type="number" step="0.01" min="0" value="0" style="text-align: right;" onchange="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" onkeyup="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" required />
                    </div>
                    <div class="col mb-5">
                        <label for="" class="form-label text-center">Importe con Tarjeta</label>
                        <input name="pagado_tarjeta" id="pagado_tarjeta" class="form-control form-control-solid" type="number" step="0.01" min="0" value="0" style="text-align: right;" onchange="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" onkeyup="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" required />
                        <p id="nota-efectivo" style="display:none; font-weight:bolder"></p>
                    </div>
                    <div class="col mb-5">
                        <label for="" class="form-label text-center">Importe con TPV2</label>
                        <input name="pagado_tpv2" id="pagado_tpv2" class="form-control form-control-solid" type="number" step="0.01" min="0" value="0" style="text-align: right;" onchange="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" onkeyup="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" required />
                        <p id="nota-tpv2" style="display:none; font-weight:bolder"></p>
                    </div>
                    <div class="col mb-5">
                        <label for="" class="form-label text-center">Importe PayPal</label>
                        <input name="pagado_paypal" id="pagado_paypal" class="form-control form-control-solid" type="number" step="0.01" min="0" value="0" style="text-align: right;" onchange="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" onkeyup="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" required />
                        <p id="nota-tpv2" style="display:none; font-weight:bolder"></p>
                    </div>
                    <div class="col mb-5">
                        <label for="" class="form-label text-center">Imp. con Transferencia</label>
                        <input name="pagado_transferencia" id="pagado_transferencia" class="form-control form-control-solid" type="number" step="0.01" min="0" value="0" style="text-align: right;" onchange="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" onkeyup="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" required />
                        <p id="nota-transferencia" style="display:none; font-weight:bolder"></p>
                    </div>                
                    
                    <?php if ($this->session->userdata('id_centro_usuario') == 9) { ?>
                        <div class="col mb-5">
                            <label for="" class="form-label text-center">Cargo Habitación</label>
                            <input name="pagado_habitacion" class="form-control form-control-solid" type="number" step="0.01" min="0" value="0" style="text-align: right;" onchange="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" onkeyup="ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);" required />
                        </div>
                    <?php } else { ?>
                        <input name="pagado_habitacion" type="hidden" value="0" />
                    <?php } ?>
                    <div class="col-12 mb-5">
                        <div id="posibles_descuentos" class="mb-5" style="display: none;"></div>
                        <label for="" class="form-label">Notas sobre pagos con descuento</label>
                        <textarea name="notas_pago_descuento" id="notas_pago_descuento" class="form-control form-control-solid"></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-sm btn-secondary text-inverse-secondary m-2" type="button" onclick="Cerrar();">Cancelar</button>
                        <button class="btn btn-sm btn-warning text-inverse-warning" type="button" onclick="ComprobarDescuentos();">Comprobar Descuentos</button>
                        <button class="btn btn-sm btn-primary text-inverse-primary" id="boton_cobrar" type="button" onclick="Cobrar();" style="display: none;">Cobrar</button>
                    </div>
                </div>
                <input type="hidden" name="tipo_pago" value="" />
            </form>
        </div>
    </div>

    <script>
        var cupon_arrayJS = [];
        var cupon_aplicar = 1;
        var cupon_descuento_porcentaje = 0;
        var cupon_descuento_euros = 0;
        var aplicar_cupon = "";

        function Cobrar() {
            marcados = document.getElementsByName("marcados[]");
            sw = 0;
            for (i = 0; i < marcados.length; i++) {
                if (marcados[i].checked) {
                    sw = 1;
                }
            }
            if (sw == 0) {
                alert("DEBE DE MARCA AL MENOS UN CONCEPTO PARA COBRAR");
                return false;
            }
            if (document.form_pagoeuros.falta_importe.value == 0) {
                importe_euros = document.getElementsByName("importe_euros[]");
                descuento_porcentaje = document.getElementsByName("descuento_porcentaje[]");
                descuento_euros = document.getElementsByName("descuento_euros[]");
                notas_pago_descuento = document.getElementsByName("notas_pago_descuento");
                hay_descuento = 0;
                for (i = 0; i < marcados.length; i++) {
                    if (marcados[i].checked) {
                        importe_euros[i].disabled = false;
                        if (descuento_porcentaje[i].value > 0 || descuento_euros[i].value > 0) {
                            hay_descuento = 1;
                        }
                    }
                }
                if (hay_descuento == 1 && document.form_pagoeuros.notas_pago_descuento.value == "") {
                    alert("DEBES DE INDICAR UNA NOTA PARA EL PAGO CON DESCUENTO");
                    return false;
                }
                document.form_pagoeuros.submit();
                return true;
            } else {
                alert("LOS IMPORTES INDICADOS NO CUADRAN CON EL TOTAL A PAGAR");
                return false;
            }
        }

        function SumaTotal(idx) {
            marcados = document.getElementsByName("marcados[]");
            importe_euros = document.getElementsByName("importe_euros[]");
            document.form_pagoeuros.total_importes_marcados.value = 0;
            for (i = 0; i < marcados.length; i++) {
                if (marcados[i].checked) {
                    total = parseFloat(document.form_pagoeuros.total_importes_marcados.value);
                    document.form_pagoeuros.total_importes_marcados.value = total + parseFloat(importe_euros[i].value);
                    document.form_pagoeuros.total_importes_marcados.value = parseFloat(document.form_pagoeuros.total_importes_marcados.value).toFixed(2);
                }
            }
        }

        function Marcado(elemento, idx) {
            marcados = document.getElementsByName("marcados[]");
            solo_pago = document.getElementsByName("solo_pago[]");
            xsolo_efectivo = "no";
            for (i = 0; i < marcados.length; i++) {
                if (marcados[i].checked) {
                    if (solo_pago[i].value == "efectivo") {
                        xsolo_efectivo = "si";
                    }
                }
            }
            if (xsolo_efectivo == "si") {
                $("#pagado_tarjeta").hide('slow');
                $("#pagado_tarjeta").val(0);
                $("#pagado_transferencia").hide('slow');
                $("#pagado_transferencia").val(0);
                $("#nota-efectivo").html('Pago por tarjeta no disponible');
                $("#nota-efectivo").show('slow');
                $("#nota-transferencia").html('Pago por trasnferencia no disponible');
                $("#nota-transferencia").show('slow');
            } else {
                $("#pagado_tarjeta").show('slow');
                $("#pagado_tarjeta").val(0);
                $("#pagado_transferencia").show('slow');
                $("#pagado_transferencia").val(0);
                $("#nota-efectivo").html('');
                $("#nota-efectivo").hide('slow');
                $("#nota-transferencia").html('');
                $("#nota-transferencia").hide('slow');
            }
            descuento_porcentaje = document.getElementsByName("descuento_porcentaje[]");
            descuento_euros = document.getElementsByName("descuento_euros[]");
            usa_saldo = document.getElementsByName("usa_saldo[]");
            if (elemento.checked) {
                descuento_porcentaje[idx].disabled = false;
                descuento_euros[idx].disabled = false;
                usa_saldo[idx].disabled = false;
                if (elemento.hasAttribute("en_efectivo")) {
                    var opcion = confirm('Este carnet solo permite el pago en efectivo. Se cancelará el pago por tarjeta.');
                    if (opcion == true) {
                        elemento.checked = true;
                        $("#pagado_tarjeta").hide('slow');
                        $("#pagado_tarjeta").val(0);
                        $("#nota-efectivo").html('Pago por tarjeta no disponible');
                        $("#nota-efectivo").show('slow');
                    } else {
                        elemento.checked = false;
                        $("#pagado_tarjeta").show('slow');
                        $("#pagado_tarjeta").val('');
                        $("#nota-efectivo").html('');
                        $("#nota-efectivo").hide('slow');
                    }
                }
            } else {
                descuento_porcentaje[idx].disabled = true;
                descuento_euros[idx].disabled = true;
                usa_saldo[idx].disabled = true;
                if (elemento.hasAttribute("en_efectivo")) {
                    $("#pagado_tarjeta").show('slow');
                    $("#pagado_tarjeta").val(0);
                    $("#nota-efectivo").html('');
                    $("#nota-efectivo").hide('slow');
                }
            }
            SumaTotal(idx);
            ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);
        }

        function DescuentoEuros(elemento, importe, idx) {
            if (event.keyCode != 9) {
                descuento_porcentaje = document.getElementsByName("descuento_porcentaje[]");
                descuento_porcentaje[idx].value = "0";
                usa_saldo = document.getElementsByName("usa_saldo[]");
                usa_saldo[idx].value = "0";
                if ((parseFloat(elemento.value) >= 0 && parseFloat(elemento.value) <= importe) || elemento.value == "") {
                    importe_euros = document.getElementsByName("importe_euros[]");
                    descuento = parseFloat(elemento.value);
                    if (isNaN(descuento)) {
                        descuento = 0;
                    }
                    importe = parseFloat(importe);
                    importe_euros[idx].value = parseFloat(importe - descuento).toFixed(2);
                } else {
                    alert("EL DESCUENTO NO PUEDE SUPERAR EL IMPORTE O SER NEGATIVO");
                    elemento.value = 0;
                    importe_euros[idx].value = parseFloat(importe).toFixed(2);
                }
                SumaTotal();
                ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);
            }
            return true;
        }

        function DescuentoPorcentaje(elemento, importe, idx) {
            if (event.keyCode != 9) {
                descuento_euros = document.getElementsByName("descuento_euros[]");
                descuento_euros[idx].value = "0";
                usa_saldo = document.getElementsByName("usa_saldo[]");
                usa_saldo[idx].value = "0";
                if ((parseFloat(elemento.value) >= 0 && parseFloat(elemento.value) <= 100) || elemento.value == "") {
                    importe_euros = document.getElementsByName("importe_euros[]");
                    if (isNaN(elemento.value)) {
                        descuento = 0;
                    } else {
                        descuento = parseFloat(elemento.value / 100);
                    }
                    importe = parseFloat(importe);
                    valor = importe * descuento;
                    importe_euros[idx].value = parseFloat(importe - valor).toFixed(2);
                } else {
                    alert("EL DESCUENTO NO PUEDE DEBE SER ENTRE 0% Y 100%");
                    elemento.value = 0;
                    importe_euros[idx].value = parseFloat(importe).toFixed(2);
                }
                SumaTotal();
                ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);
            }
            return true;
        }

        function CuponDescuentoPorcentaje(elemento, importe, idx) {
            if (event.keyCode != 9) {
                descuento_euros = document.getElementsByName("descuento_euros[]");
                descuento_euros[idx].value = "0";
                usa_saldo = document.getElementsByName("usa_saldo[]");
                usa_saldo[idx].value = "0";
                console.log('Elemento ' + elemento + ' importe eeee ' + importe + ' idx ' + idx);
                if ((parseFloat(elemento) >= 0 && parseFloat(elemento) <= 100) || elemento == "") {
                    importe_euros = document.getElementsByName("importe_euros[]");
                    if (isNaN(elemento)) {
                        descuento = 0;
                    } else {
                        descuento = parseFloat(elemento / 100);
                    }
                    importe = parseFloat(importe);
                    valor = importe * descuento;
                    importe_euros[idx].value = parseFloat(importe - valor).toFixed(2);
                } else {
                    alert("EL DESCUENTO NO PUEDE DEBE SER ENTRE 0% Y 100%");
                    elemento = 0;
                    importe_euros[idx].value = parseFloat(importe).toFixed(2);
                }
            }
        }

        function CuponDescuentoEuros(elemento, importe, idx) {
            if (event.keyCode != 9) {
                descuento_porcentaje = document.getElementsByName("descuento_porcentaje[]");
                descuento_porcentaje[idx].value = "0";
                usa_saldo = document.getElementsByName("usa_saldo[]");
                usa_saldo[idx].value = "0";
                if ((parseFloat(elemento) >= 0 && parseFloat(elemento) <= importe) || elemento == "") {
                    importe_euros = document.getElementsByName("importe_euros[]");
                    descuento = parseFloat(elemento);
                    if (isNaN(descuento)) {
                        descuento = 0;
                    }
                    importe = parseFloat(importe);
                    importe_euros[idx].value = parseFloat(importe - descuento).toFixed(2);
                } else {
                    alert("EL DESCUENTO NO PUEDE SUPERAR EL IMPORTE O SER NEGATIVO");
                    elemento = 0;
                    importe_euros[idx].value = parseFloat(importe).toFixed(2);
                }
            }
        }

        function UsaSaldo(elemento, importe, idx) {
            if (event.keyCode != 9) {
                importe_euros = document.getElementsByName("importe_euros[]");
                descuento_euros = document.getElementsByName("descuento_euros[]");
                descuento_porcentaje = document.getElementsByName("descuento_porcentaje[]");
                porcentaje_euros = 0;
                if (parseFloat(descuento_porcentaje[idx].value) > 0) {
                    porcentaje_euros = (parseFloat(descuento_porcentaje[idx].value) / 100) * importe;
                }
                total_importe = parseFloat(importe) - (parseFloat(descuento_euros[idx].value) + parseFloat(porcentaje_euros));
                if ((parseFloat(elemento.value) >= 0 && parseFloat(elemento.value) <= parseFloat(total_importe)) || elemento.value == "") {
                    descuento = parseFloat(elemento.value);
                    if (isNaN(descuento)) {
                        descuento = 0;
                    }
                    importe_euros[idx].value = parseFloat(total_importe - descuento).toFixed(2);
                } else {
                    alert("EL USO DE SALDO NO PUEDE SUPERAR EL IMPORTE O SER NEGATIVO");
                    elemento.value = 0;
                    importe_euros[idx].value = parseFloat(importe).toFixed(2);
                }
                SumaTotal();
                ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);
            }
            return true;
        }

        function ImporteMarcado(total) {
            document.getElementById("posibles_descuentos").style.display = 'none';
            efectivo = parseFloat(document.form_pagoeuros.pagado_efectivo.value);
            tarjeta = parseFloat(document.form_pagoeuros.pagado_tarjeta.value);
            habitacion = parseFloat(document.form_pagoeuros.pagado_habitacion.value);
            transferencia = parseFloat(document.form_pagoeuros.pagado_transferencia.value);
            tpv2 = parseFloat(document.form_pagoeuros.pagado_tpv2.value);
            paypal = parseFloat(document.form_pagoeuros.pagado_paypal.value);
            //financiado = parseFloat(document.form_pagoeuros.pagado_financiado.value);
            r = (total) - parseFloat(efectivo + tarjeta + habitacion + transferencia + tpv2 + paypal).toFixed(2);
            console.log(r);
            document.form_pagoeuros.falta_importe.value = parseFloat(r).toFixed(2);
            if (r == 0) {
                document.getElementById("faltan").style.color = "green";
                document.getElementById("faltan_simbolo").style.color = "green";
                document.getElementById("faltan_simbolo").style.visibility = "hidden";
                document.getElementById("faltan_importe").style.visibility = "hidden";
                document.getElementById("faltan").innerHTML = "CORRECTO";
            }
            if (r > 0) {
                document.getElementById("faltan").style.color = "red";
                document.getElementById("faltan_simbolo").style.color = "red";
                document.getElementById("faltan_simbolo").style.visibility = "visible";
                document.getElementById("faltan_importe").style.visibility = "visible";
                document.getElementById("faltan").innerHTML = "FALTAN";
            }
            if (r < 0) {
                document.getElementById("faltan").style.color = "red";
                document.getElementById("faltan_simbolo").style.color = "red";
                document.getElementById("faltan_simbolo").style.visibility = "visible";
                document.getElementById("faltan_importe").style.visibility = "visible";
                document.getElementById("faltan").innerHTML = "SOBRAN";
            }
        }

        function Cerrar() {
            if (window.opener === undefined || window.opener === null) {
                window.close();
            } else {
                window.opener.location.reload();
                window.close();
            }
        }

        function ComprobarDescuentos() {
            items = [];
            importes = [];
            var posibles_descuentos = document.getElementById("posibles_descuentos");
            marcados = document.getElementsByName("marcados[]");
            importe_euros = document.getElementsByName("importe_euros[]");
            sw = 0;
            x = 0;
            for (i = 0; i < marcados.length; i++) {
                if (marcados[i].checked) {
                    items[x] = parseFloat(marcados[i].value);
                    importes[x] = parseFloat(importe_euros[i].value);
                    sw = 1;
                    x++;
                }
            }
            if (sw == 0) {
                alert("DEBE DE MARCA AL MENOS UN CONCEPTO PARA COBRAR");
                return false;
            }
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>descuentos/comprobar",
                data: {
                    items: items,
                    importes: importes
                },
                success: function(result) {
                    var notas = result.replace(' <br> ', '\n');
                    if (result != "0") {
                        document.form_pagoeuros.notas_pago_descuento.value = notas.replace(' <br> ', '\n');
                        posibles_descuentos.style.background = '#e0ffd4';
                        result = '<div class="alert alert-primary p-5 text-center">DEBES APLICAR LAS SIGUIENTES OFERTAS:<br>' + result + '</div>';
                    } else {
                        result = '<div class="alert alert-warning p-5 text-center">NO HAY DESCUENTOS PARA APLICAR</div>';
                        document.form_pagoeuros.notas_pago_descuento.value = "";
                    }
                    document.getElementById("boton_cobrar").style.display = 'inline-block';
                    posibles_descuentos.style.display = 'block';
                    posibles_descuentos.innerHTML = result;
                }
            });
        }

        function ComprobarCupon(id_cliente) {
            items = [];
            if (document.form_pagoeuros.total_importes_marcados.value > 0) {
                marcados = document.getElementsByName("marcados[]");
                xservicios = document.getElementsByName("servicios[]");
                id_cupon = $('#cupon').val();
                x = 0;
                for (i = 0; i < marcados.length; i++) {
                    if (marcados[i].checked) {
                        items[x] = xservicios[i].value;
                        x++;
                    }
                }
                //console.log('Servicios : '.items) 
                if (typeof(id_cupon) != "undefined" && id_cupon !== null && id_cupon !== '') {
                    url = '<?php echo base_url(); ?>cupones/comprobar_cupon'
                    $.ajax({
                        type: "post",
                        url: url,
                        data: {
                            codigo_cupon: id_cupon,
                            id_cliente: id_cliente,
                            servicios: items
                        },
                        dataType: "json",
                    }).done(function(data, textStatus, jqXHR) {
                        if (typeof data.error !== 'undefined') {
                            document.getElementById('mensaje_cupon').style.color = "red";
                            document.getElementById('mensaje_cupon').innerHTML = "Ups... Hay un error..";
                        } else {
                            console.log('Llego ');
                            if (data.respuesta == 1) {
                                console.log('Respuesta ' + data.respuesta + ' Asignar ' + data.asignar);
                                cupon_arrayJS = data.elementos;
                                for (var i = 0; i < cupon_arrayJS.length; i++) {
                                    console.log('Elementos ' + cupon_arrayJS[i]);
                                }
                                cupon_descuento_euros = data.descuento_euros;
                                cupon_descuento_porcentaje = data.descuento_porcentaje;
                                cupon_aplicar = data.asignar;
                                AplicarCupon(id_cupon, id_cliente);
                            } else {
                                document.getElementById('mensaje_cupon').style.color = "red";
                                $('#mensaje_cupon').html('Cupón no válido');
                            }
                        }
                    });
                } else {
                    document.getElementById('mensaje_cupon').style.color = "red";
                    $('#mensaje_cupon').html('No hay ningun cupón para verificar... Indica un cupón, por favor');
                }
            } else {
                alert('Error, debe marcar items a pagar');
            }
        }

        function AplicarCupon(id_cupon, id_cliente) {
            var opcion = 0;
            var descuento = 0;
            xservicios = document.getElementsByName("servicios[]");
            descuento_euros = document.getElementsByName("descuento_euros[]");
            descuento_porcentaje = document.getElementsByName("descuento_porcentaje[]");
            importe_monto = document.getElementsByName("importe_euros[]");
            console.log('cupon_descuento ' + cupon_descuento_porcentaje + ' cupo_euros ' + cupon_descuento_euros + ' copun aplicar' + cupon_aplicar);
            cupon_descuento_euros = parseFloat(cupon_descuento_euros);
            cupon_descuento_porcentaje = parseFloat(cupon_descuento_porcentaje);
            cupon_aplicar = parseInt(cupon_aplicar);
            if (cupon_descuento_porcentaje != 0) {
                factor = parseFloat(cupon_descuento_porcentaje / cupon_aplicar);
                opcion = 1;
            }
            if (cupon_descuento_euros != 0) {
                factor = parseFloat(cupon_descuento_euros / cupon_aplicar);
                opcion = 2;
            }
            console.log('Factorc' + factor);
            for (i = 0; i < marcados.length; i++) {
                if (marcados[i].checked) {
                    var element = xservicios[i].value;
                    var idx = cupon_arrayJS.indexOf(element);
                    console.log('Valor ' + element + ' idx ' + idx);
                    if (idx != -1 && opcion == 1) {
                        descuento_porcentaje[i].value = factor.toFixed(2);
                        console.log('importe_monto ' + importe_monto[i].value);
                        xx = importe_monto[i].value;
                        monto = parseFloat(importe_monto[i].value);
                        porcentaje = parseFloat(factor / 100);
                        valor = monto * porcentaje;
                        descuento = descuento + valor;
                        console.log('Monto ' + monto);
                        CuponDescuentoPorcentaje(factor, xx, i);
                    }
                    if (idx != -1 && opcion == 2) {
                        descuento_euros[i].value = factor.toFixed(2);
                        console.log('importe_monto ' + importe_monto[i].value);
                        xx = importe_monto[i].value;
                        monto = parseFloat(importe_monto[i].value);
                        if (factor > monto) {
                            console.log('Antes Factor ' + factor + ' Monto ' + monto);
                            descuento = descuento + monto;
                            factor = monto;
                            console.log('Despues Factor ' + factor + ' Descuento ' + descuento);
                        } else {
                            valor = monto - factor;
                            descuento = descuento + valor;
                        }
                        console.log('Monto ' + monto);
                        CuponDescuentoEuros(factor, xx, i);
                    }
                }
            }
            console.log('Descuento Total ' + descuento);
            SumaTotal();
            ImporteMarcado(document.form_pagoeuros.total_importes_marcados.value);
            texto = "Cupón " + document.getElementById('cupon').value;
            document.getElementById("notas_pago_descuento").innerHTML = texto;
            console.log('Nota; ' + texto);
            $('#comprobar_cupon').attr('disabled', 'disabled');
            url = '<?php echo base_url(); ?>cupones/aplicar_cupon' /*+id_cupon+'/'+codigo_pedido*/ ;
            $.ajax({
                type: "post",
                url: url,
                data: {
                    codigo_cupon: id_cupon,
                    id_cliente: id_cliente,
                    descuento: descuento,
                    descuento_euros: cupon_descuento_euros,
                    descuento_porcentaje: cupon_descuento_porcentaje
                },
                dataType: "json",
            }).done(function(data, textStatus, jqXHR) {
                if (typeof data.error !== 'undefined') {
                    document.getElementById('mensaje_cupon').style.color = "red";
                    $('#mensaje_cupon').html('Ups... Hay un error...');
                } else {
                    document.getElementById('mensaje_cupon').style.color = "blue";
                    $('#mensaje_cupon').html('Se aplicó el cupón con éxito');
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                document.getElementById('mensaje_cupon').style.color = "red";
                $('#mensaje_cupon').html('La solicitud a fallado ' + textStatus);
                if (console && console.log) {
                    console.log("La solicitud a fallado: " + textStatus);
                }
            });
        }
        <?php if ($accion == "guardar") { ?>
            Cerrar();
        <?php } ?>
    </script>
</body>

</html>