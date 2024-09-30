<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">CARNET ESPECIAL <?= (isset($carnet[0]['codigo'])) ? $carnet[0]['codigo'] : '' ?> <small>(Asignado a <?= (isset($carnet[0]['cliente'])) ? $carnet[0]['cliente'] : '' ?>)</h1>
    <div class="card card-flush m-5">
        <div class="card-body p-5">
            <div class="row mb-5 align-items-end">
                <div class="col-8 mb-5">
                    <label for="" class="form-label">Elige un servicio para añadir al carnet</label>
                    <select name="id_servicio" id="id_servicio" data-placeholder="Elegir ..." class="form-select form-select-solid" data-control="select2" tabindex="-1" aria-hidden="true" style="width: 500px !important;">
                        <option value=""></option>
                        <?php if (isset($servicios) && $servicios != 0) {
                            foreach ($servicios as $key => $row) { ?>
                                <option value="<?php echo $row['id_servicio']; ?>" <?= (isset($cita[0]['id_servicio']) && $row['id_servicio'] == $cita[0]['id_servicio']) ? "selected" : "" ?>>
                                    <?php echo strtoupper($row['nombre_familia'] . " - " . $row['nombre_servicio'] . " (" . $row['duracion'] . " min) - PVP: " . $row['pvp']); ?>
                                </option>
                        <?php }
                        } ?>
                    </select>
                </div>
                <div class="w-auto">
                    <button type="button" class="btn btn-info text-inverse-info btn-icon" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Añadir otro servicio" onclick="AnadirServicio(document.getElementById('id_servicio').value);"><i class="fas fa-plus"></i></button>
                </div>
            </div>

            <div class="table-responsive mb-5">
                <table id="myTable1" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                    <thead class="">
                        <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                            <th>Servicios actuales del carnet</th>
                            <th>Estado del servicio</th>
                            <th>P.V.P.</th>
                            <th style="width: 5%;">Quitar</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php if (isset($carnets_servicios)) {
                            if ($carnets_servicios != 0) {
                                foreach ($carnets_servicios as $key => $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo strtoupper($row['nombre_familia'] . " - " . $row['nombre_servicio']); ?>
                                        </td>
                                        <td>
                                            <?php echo $row['estado_servicio']; ?>
                                        </td>
                                        <td class="text-end">
                                            <?php echo round($row['pvp'], 2) . " €"; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <a class="btn btn-sm btn-icon btn-danger" href="#" onclick="EliminarServicio('<?= strtoupper($row['nombre_familia'] . ' - ' . $row['nombre_servicio']); ?>',<?= $row['id'] ?>);""><i class=" fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                        <?php }
                            }
                        } ?>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <button class="btn btn-sm btn-secondary text-inverse-secondary m-2" type="button" onclick="window.close();">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function EliminarServicio(servicio, id_carnet_sevicio) {
            if (confirm("¿DESEA BORRAR EL SERVICIO " + servicio + "DEL CARNET?")) {
                document.location.href = "<?php echo base_url(); ?>carnets/modificar_especial/quitar/<?= (isset($carnet[0]['id_carnet'])) ? $carnet[0]['id_carnet'] : '' ?>/" + id_carnet_sevicio;
                return true;
            } else {
                return false;
            }
        }

        function AnadirServicio(id_servicio) {
            if (id_servicio > 0) {
                document.location.href = "<?php echo base_url(); ?>carnets/modificar_especial/anadir/<?= (isset($carnet[0]['id_carnet'])) ? $carnet[0]['id_carnet'] : '' ?>/" + id_servicio;
                return true;
            } else {
                return false;
            }
        }
    </script>
</body>

</html>