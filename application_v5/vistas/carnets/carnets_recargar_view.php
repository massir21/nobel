<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">ELEGIR EL CARNET A RECARGAR</h1>
    <div class="card card-flush m-5">
        <div class="card-body p-5">
            <form id="form_carnets" action="<?php echo base_url(); ?>carnets/recargar/realizar" role="form" method="post" name="form_carnets">
                <div class="row mb-5 align-items-end">
                    <div class="col-6 mb-5">
                        <label for="" class="form-label">Código del carnet:</label>
                        <select name="id_carnet" id="id_carnet" class="form-select form-select-solid" data-placeholder="Elegir ..."></select>
                        <script type="text/javascript">
                            $("#id_carnet").select2({
                                language: "es",
                                minimumInputLength: 4,
                                ajax: {
                                    delay: 0,
                                    url: function(params) {
                                        return '<?php echo RUTA_WWW; ?>/carnets/json/' + params.term;
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

                    <div class="col-6 mb-5">
                        <label for="" class="form-label">Templos:</label>
                        <input type="number" name="templos_recarga" id="templos_recarga" min="0.5" max="19.5" step="0.5" value="" class="form-control form-control-solid" required />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-sm btn-secondary text-inverse-secondary m-2" type="button" onclick="window.close();">Cerrar sin Cambios</button>
                        <button class="btn btn-sm btn-primary text-inverse-primary" type="button" onclick="RealizarRecarga();">Realizar Recarga</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function RealizarRecarga(templos, id_carnet) {
            if (document.form_carnets.id_carnet.value == "") {
                alert("Debes de indicar un código de carnet");
                return false;
            }
            if (document.form_carnets.templos_recarga.value < 0.5 || document.form_carnets.templos_recarga.value > 19.5) {
                alert("El número de templos está fuera de rango (entre 0,5 y 19,5)");
                return false;
            }
            document.form_carnets.submit();
            return true;
        }

        function PagoEfectivo() {
            var posicion_x;
            var posicion_y;
            var ancho = 850;
            var alto = 600;
            posicion_x = (screen.width / 2) - (ancho / 2);
            posicion_y = (screen.height / 2) - (alto / 2);
            window.open("<?= base_url(); ?>dietario/pagoeuros/ver_recargas/<?= (isset($id_cliente)) ? $id_cliente : '' ?>/<?= $hoy_aaaammdd ?>", "_blank", "toolbar=no,scrollbars=no,resizable=no,top=" + posicion_y + ",left=" + posicion_x + ",width=" + ancho + ",height=" + alto);
        }
        <?php if ($accion == "realizar") { ?>
            document.location.href = '<?= base_url(); ?>dietario/recargar_carnet_manual/<?= (isset($id_cliente)) ? $id_cliente : '' ?>/<?= $hoy_aaaammdd; ?>/<?= (isset($id_carnet)) ? $id_carnet : '' ?>/<?= (isset($templos_recarga)) ? $templos_recarga : '' ?>';
            PagoEfectivo();
        <?php } ?>
        <?php if ($accion == "terminar") { ?>
            window.close();
        <?php } ?>
    </script>

</body>

</html>