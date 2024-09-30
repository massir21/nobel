<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">Historal del Carnet</h1>
    <div class="card card-flush m-5">
        <div class="card-body p-5">
            <h3 class="text-center"><?php echo (isset($carnet[0]['codigo'])) ? $carnet[0]['codigo']:''; ?>(<?php echo (isset($carnet[0]['tipo'])) ? $carnet[0]['tipo']:''; ?>)</h3>
            <div class="table-responsive">
                <?php if (isset($carnet[0]['id_tipo']) && $carnet[0]['id_tipo'] == 99) { ?>
                    <table id="myTable1" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                        <thead class="">
                            <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Servicio</th>
                                <th>Empleado</th>
                                <th>Centro</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 fw-semibold">
                            <?php if (isset($carnets_servicios)) {
                                if ($carnets_servicios != 0) {
                                    foreach ($carnets_servicios as $key => $row) { 
                                        $tdclass =($row['estado_servicio'] == "Gastado") ? 'style="--bs-table-accent-bg: var(--bs-highlight-bg);"' : '';
                                        ?>
                                        <tr <?=$trclass?>>
                                            <td <?=$tdclass?>>
                                                <?php if ($row['estado_servicio'] == "Gastado") { ?>
                                                    <?php echo $row['fecha_modificacion_ddmmaaa']; ?><br>
                                                    <?php echo $row['estado_servicio']; ?>
                                                <?php } else { ?>
                                                    <?php echo $row['estado_servicio']; ?>
                                                <?php } ?>
                                            </td>
                                            <td <?=$tdclass?>> 
                                                <?php echo $row['cliente']; ?>
                                            </td>
                                            <td <?=$tdclass?>>
                                                <?php echo $row['nombre_familia'] . " - " . $row['nombre_servicio'] . " (" . $row['duracion'] . " min)"; ?>
                                            </td>
                                            <td <?=$tdclass?>>
                                                <?php if ($row['estado_servicio'] == "Gastado") { ?>
                                                    <?php echo $row['empleado']; ?>
                                                <?php } else { ?>
                                                <?php } ?>
                                            </td>
                                            <td <?=$tdclass?>>
                                                <?php if ($row['estado_servicio'] == "Gastado") { ?>
                                                    <?php echo $row['centro_servicio']; ?>
                                                <?php } else { ?>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                <?php }
                                }
                            } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <table id="myTable1" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                        <thead class="">
                            <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Servicio</th>
                                <th>Templos Gastados</th>
                                <th>Empleado</th>
                                <th>Centro</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 fw-semibold">
                            <?php $total_templos = 0;
                            if (isset($carnets_historial)) {
                                if ($carnets_historial != 0) {
                                    foreach ($carnets_historial as $key => $row) { ?>
                                        <tr>
                                            <td>
                                                <?php echo $row['fecha_creacion_ddmmaaaa']; ?><br>
                                                <?php echo $row['hora']; ?>
                                            </td>
                                            <td>
                                                <?php echo $row['cliente']; ?>
                                            </td>
                                            <td>
                                                <?php if ($row['carnet_especial'] == "") { ?>
                                                    <?php echo $row['nombre_familia'] . " - " . $row['nombre_servicio'] . " (" . $row['duracion'] . " min)"; ?>
                                                <?php } else { ?>
                                                    <?php echo $row['carnet_especial']; ?>
                                                <?php } ?>
                                            </td>
                                            <td class="text-end">
                                                <?php echo round($row['templos'], 2);
                                                $total_templos += $row['templos']; ?>
                                            </td>
                                            <td>
                                                <?php echo $row['empleado']; ?>
                                            </td>
                                            <td>
                                                <?php echo $row['nombre_centro']; ?>
                                            </td>
                                        </tr>
                            <?php }
                                }
                            } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" style="text-align: right; padding: 8px;"><b>TOTAL TEMPLOS GASTADOS</b></td>
                                <td style="text-align: right; padding: 8px;"><?php echo round($total_templos, 2, 1); ?></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                <?php } ?>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <button class="btn btn-sm btn-secondary text-inverse-secondary" type="button" onclick="Cerrar();">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function Cerrar() {
            //window.opener.location.reload();        
            window.close();
        }
    </script>

</body>

</html>