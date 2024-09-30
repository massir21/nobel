<?php if(is_array($registros)) { ?>
    <?php foreach ($registros as $key => $row) { ?>
        <p class="card-amount-earnings"><?= euros($row['ganancia']) ?></p>
    <?php } ?>
<?php } ?>