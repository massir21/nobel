  <!-- detalles por centro -->
  <?php if(is_array($registros)) { ?>
        <?php foreach ($registros as $key => $row) { ?>
            <tr>
                <th class="text-start text-gray-600 fw-bold text-uppercase"><?= ucfirst($row['centro']) ?></th>
                <td class="text-end text-body fw-bold"><?= euros($row['total_facturado']) ?></td>
            </tr>

        <?php } ?>
    <?php } ?>