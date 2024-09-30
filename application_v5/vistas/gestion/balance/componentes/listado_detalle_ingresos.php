<?php if(is_array($registros)) { ?>
    <?php foreach ($registros as $key => $row) { 
        
        if($row['id_centro'] == 3 && isset($balance_ingreso_consolidado_facturas_emitidas)){
            $row['total_facturado'] = $row['total_facturado'] - $balance_ingreso_consolidado_facturas_emitidas;
        }?>
        <tr>
            <th class="text-start text-gray-600 fw-bold text-uppercase"><?= ucfirst($row['centro']) ?></th>
            <td class="text-end text-body fw-bold"><?= euros($row['total_facturado']) ?></td>
            <td class="text-end text-body fw-bold"><?= euros($row['objetivo']) ?></td>
            <?php
            $logro = 0;
            $color = "red";
            if($row['objetivo']>0){ $logro = round(($row['total_facturado']/$row['objetivo'])*100);}
            if($logro>=100){$color = 'green';}
            ?>
            <td class="text-end fw-bold" style="color:<?= $color ?>;"><?= $logro ?>%</td>
        </tr>
    <?php } ?>
<?php } ?>