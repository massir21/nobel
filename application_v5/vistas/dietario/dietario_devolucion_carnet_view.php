<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">DEVOLUCIÓN DE CARNET <spanclass="text-muted"><?php echo strtoupper($dietario[0]['carnet']); ?></spanclass>
    </h1>
    <div class="card card-flush m-5">
        <div class="card-body p-5">
            <?php if (isset($usado)) { ?>
                <div class="alert alert-primary">
                    <p>EL CARNET YA HA SIDO USADO O DEVUELTO ANTERIORMENTE</p>
                    <p>NO ES POSIBLE REALIZAR LA DEVOLUCIÓN</p>
                </div>
            <?php } else { ?>
                <form id="form_devolver" action="<?php echo base_url(); ?>dietario/devolucion_carnet_realizar/<?php echo $dietario[0]['id_dietario']; ?>" role="form" method="post" name="form_devolver">
                    <div class="row mb-5 align-items-end border-bottom">
                        <div class="col-12 mb-5">
                            <label for="" class="form-label">Cliente al que se realiza la devolución:</label>
                            <h4><?php echo strtoupper($dietario[0]['cliente']); ?></h4>
                            <input type="hidden" name="id_dietario" value="<?php echo $dietario[0]['id_dietario']; ?>">
                        </div>

                        <div class="col-12 mb-5">
                            <label for="" class="form-label">Forma de devolución</label>
                            <select id="forma_pago" name="forma_pago" data-placeholder="Elegir ..." class="form-select form-select-solid" tabindex="-1" aria-hidden="true" onchange="Mostrar();" required>
                                <option value="">Elegir...</option>
                                <option value="#efectivo">Efectivo (devolución de <?php echo number_format($dietario[0]['importe_euros'], 2, ',', '.') . "€"; ?>)</option>
                                <option value="#tarjeta">Tarjeta (devolución de <?php echo number_format($dietario[0]['importe_euros'], 2, ',', '.') . "€"; ?>)</option>
                                <?php if ($this->session->userdata('id_centro_usuario') == 9) { ?>
                                    <option value="#habitacion">Habitación (devolución de <?php echo number_format($dietario[0]['importe_euros'], 2, ',', '.') . "€"; ?>)</option>
                                <?php } ?>
                                <?php if ($dietario[0]['tipo_pago'] == "#templos") { ?>
                                    <option value="#templos">Templos (devolución de <?php echo round($dietario[0]['templos'], 1) . " templos"; ?>)</option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-12 mb-5">
                            <div class="mb-5" style="display: none;" id="devolucion_templos">
                                <?php if (isset($carnet_pago_templos)) { ?>
                                    <div class="alert alert-info">Se devolverá en el carnet de templos con el que se pagó: <?php echo $carnet_pago_templos[0]['codigo']; ?></div>
                                    <input type="hidden" name="id_carnet_pago_templos" value="<?php echo $carnet_pago_templos[0]['id_carnet']; ?>">
                                <?php } ?>
                            </div>

                            <label for="" class="form-label">Motivo de la Devolución</label>
                            <textarea name="motivo_devolucion" class="form-control form-control-solid" required></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button class="btn btn-sm btn-secondary text-inverse-secondary m-2" type="button" onclick="window.close();">Cerrar sin Cambios</button>
                            <button class="btn btn-sm btn-primary text-inverse-primary" type="submit">Realizar Devolución</button>
                        </div>
                    </div>
                </form>
            <?php } ?>
        </div>
    </div>

    <script>
        function Mostrar() {
            if (document.getElementById("forma_pago").value == "#templos") {
                document.getElementById("devolucion_templos").style.display = "block";
            } else {
                document.getElementById("devolucion_templos").style.display = "none";
            }
        }

        function Cerrar() {
            window.opener.location.reload();
            window.close();
        }
        <?php if (isset($cerrar)) { ?>
            Cerrar();
        <?php } ?>
    </script>
</body>

</html>