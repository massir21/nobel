<?php if(is_array($registros)) { ?>
            <?php foreach ($registros as $key => $row) { ?>
                <p class="card-amount"><?= euros($row['total_consolidado']) ?></p>
                <?php $ingresos = $row['total_consolidado']; ?>
            <?php } ?>
<?php } ?>

