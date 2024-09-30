<?php if(is_array($registros)) { ?>
    <?php foreach ($registros as $key => $row) { ?>
        <p class="card-amount-bills"><?= euros($row['total_consolidado']) ?></p>

        <?php $gastos = $row['total_consolidado']; ?>


    <?php } ?>
<?php } ?>