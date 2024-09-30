  <style>
    .tipo_gasto{
      cursor: pointer;
    }
    .tipo_gasto:hover{
      background-color:#f5f5f5;
    }
  </style>
  <table class="table">
    <thead>
      <tr>
        <th  colspan="2">Tipo</th>
        <th style="text-align: right;">Total</th>
      </tr>
    </thead>
    <tbody>
    <?php if(is_array($registros)) { ?>
        <?php foreach ($registros as $key => $row) { ?>
            <tr class="tipo_gasto" style="font-weight: bold;">
                <td class="text-start fw-bold fs-5 text-uppercase" colspan="2"><?= $row->tipo ?></td>
                <td class="text-end text-body fw-bold"><?= euros($row->total) ?></td>
            </tr>
            <?php foreach($row->detalles as $p){?>
              <tr class="hiddenRows">
                <td><?= $p->proveedor ?>
                <?php if($p->id_doctor!='0')foreach($doctores as $doc)if($p->id_doctor==$doc['id_usuario']){?>
                  <br><span style="padding: 3px;background-color: #CAD5FF; color gray; font-size: 12px;"><?= $doc['nombre']." ".$doc['apellidos'] ?></span>
                <?php } ?>
                </td>
                <td><?= $p->nombre_centro ?>
                <?php if($p->nota!=''){?>
                  <br><span style="padding: 3px;background-color: #F9FF9B; color gray; font-size: 12px;"><?= $p->nota ?></span></td>
                <?php } ?>
                <td style="text-align: right;"><?= euros($p->total) ?></td>
              </tr>
            <?php } ?>
        <?php } ?>
    <?php } ?>
    </tbody>
  </table>

  <script>
    $(document).ready(function () {
      $(".hiddenRows").hide();
      $('.tipo_gasto').click(function(){
        //toggle a los tr con la clase hiddenRows
        $(this).nextUntil('.tipo_gasto').slideToggle();
      })
    });

  </script>