<table id="myTable2"
       class="table align-middle table-striped table-row-dashed fs-6 gy-5 tblListadoGestionFacturas">
    <thead class="">
    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
        <th style="display: none;"></th>
        <th>Centro</th>
        <th>Tipo</th>
        <th>Fecha</th>
        <th>Doctor</th>
        <th>Nota</th>
        <th>Total</th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    </thead>
    <tbody class="text-gray-700 fw-semibold">

<?php foreach ($registros as $key => $row) : ?>
    <?php
    $fecha1 = new DateTime(date("Y-m-d H:i:s"));
    $fecha2 = new DateTime($row['fecha_creacion']);
    $timestamp1 = $fecha1->getTimestamp();
    $timestamp2 = $fecha2->getTimestamp();
    $diferenciaSegundos = abs($timestamp2 - $timestamp1);
    $diferenciaHoras = round($diferenciaSegundos / 3600, 1);
    ?>
    <tr class="th_factura_<?= $row['id_gestion_facturas'] ?> <?php if ($row['check_descarga'] == '1') {
                                                                    echo "f_seleccionada";
                                                                } ?>">
        <td style="display: none"><?php echo $row['id_gestion_facturas'] ?></td>

        <td><?php echo $row['nombre_centro'] ?></td>
        <td>
            <?php if (explode('/', $row['documento_ruta'])[3] != '') { ?>
                <a href="<?php echo base_url() . '/recursos/' . $row['documento_ruta'] ?>" download> <?php echo $row['nombreProveedor'] ?></a>
            <?php } else { ?>
                <?php echo $row['nombreProveedor'] ?>
            <?php } ?>
        </td>
        <td><?php echo date("d/m/Y", strtotime($row['fecha_factura'])) ?></td>
        <td>
            <?php if ($row['id_doctor'] != '0') foreach ($doctores as $doc) if ($row['id_doctor'] == $doc['id_usuario']) { ?>
                <?= $doc['nombre'] . " " . $doc['apellidos'] ?>
            <?php } ?>
        </td>
        <td><?= $row['nota'] ?></td>
        <td><?php echo $row['total_factura'] . "€" ?></td>
        <td><input type="checkbox" class="form-check-input check_gf" id_factura="<?= $row['id_gestion_facturas'] ?>" <?php if ($row['check_descarga'] == '1') {
                                                                                                                            echo "checked";
                                                                                                                        } ?>></td>
        <?php if ($this->session->userdata('id_perfil') == '' || $diferenciaHoras <= 24  || $this->session->userdata('id_perfil') == 0) { ?>
            <td>
                <a class="btn btn-sm btn-icon btn-warning" href="<?php echo base_url(); ?>facturas/gestion/editar/<?php echo $row['id_gestion_facturas'] ?>"><i class="fa-regular fa-pen-to-square"></i></a>
            </td>
            <td>
                <button class="btn btn-sm btn-icon btn-danger" onclick="BorrarFactura(<?php echo $row['id_gestion_facturas'] ?>);">
                    <i class="fa-solid fa-trash"></i></button>
            </td>
        <?php } else { ?>
            <td></td>
            <td></td>
        <?php } ?>
    </tr>
<?php endforeach; ?>
    </tbody>
</table>
