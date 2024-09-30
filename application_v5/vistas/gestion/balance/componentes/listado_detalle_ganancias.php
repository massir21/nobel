<?php if(is_array($registros)) { ?>
    <?php foreach ($registros as $key => $row) { ?>
        <tr>
            <th class="text-start text-gray-600 fw-bold text-uppercase"><?= ucfirst($row['centro']) ?></th>
            <td class="text-end text-body fw-bold"><?= euros($row['diferencia']) ?></td>
            <td class="text-end text-body fw-bold"><?= euros($row['rentabilidad']) ?></td>
            <?php
            $logro = 0;
            $color = "red";
            if($row['rentabilidad']>0){ $logro = round(($row['diferencia']/$row['rentabilidad'])*100);}
            if($logro>=100){$color = 'green';}
            ?>
            <td class="text-end fw-bold" style="color:<?= $color ?>;"><?= $logro ?>%</td>
        </tr>
    <?php } ?>
<?php } ?>