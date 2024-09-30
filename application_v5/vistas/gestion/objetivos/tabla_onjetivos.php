<!-- RESUMEN -->
<div class="row pt-5">
    <div class="col-12-md">
      <div class="card text-start gap-5">
        <div class="card-body">
        <div class="col-md-12 text-center"><h2>Objetivos guardados</h2></div>
          <table class="table table-striped datatable">
            <thead>
              <tr>
                <th>CENTRO</th>
                <th >AÑO</th>
                <th >MES</th>
                <th class="text-end">FACTURACIÓN</th>
                <th class="text-end">RENTABILIDAD</th>
                <th class="text-end"></th>
              </tr>
            </thead>
            <tbody>
            <?php 
            if(count($objetivos)>0)foreach ($objetivos as &$o) { 
              ?>
                <tr>
                  <td><?= $o->nombre_centro ?></td>
                  <td><?= $o->ano ?></td>
                  <td><?= $o->mes  ?></td>
                  <td class="text-end"><?=  euros($o->facturacion) ?></td>
                  <td class="text-end"><?= round($o->rentabilidad)  ?></td>

                  <td class="text-end">
                  <a class="btn btn-warning"><i class="bi bi-pencil"></i></a>
                  <a class="btn btn-danger"><i class="bi bi-trash"></i></a>
                  </td>
                </tr>
              <?php } ?>
  <?php 
  //show_array($centros);exit;
          $total_p=0;
          $cant_p=0;
          $total_a=0;
          $cant_a=0;
          $total_f=0;
          $cant_f=0;
          if(count($centros[0]->data)>0)foreach($centros as $c2){
            if(isset($c2->total_p)&&$c2->total_p>0){$total_p+=$c2->total_p;}
            if(isset($c2->cant_p)&&$c2->cant_p>0){$cant_p+=$c2->cant_p;}
            if(isset($c2->total_a)&&$c2->total_a>0){$total_a+=$c2->total_a;}
            if(isset($c2->cant_a)&&$c2->cant_a>0){$cant_a+=$c2->cant_a;}
            if(isset($c2->total_f)&&$c2->total_f>0){$total_f+=$c2->total_f;}
            if(isset($c2->cant_f)&&$c2->cant_f>0){$cant_f+=$c2->cant_f;}
          }
  ?>
              <tr style="border-top: solid 1px #424242;">
                <th></th>
                <th class="text-end"><?= $cant_p ?></th>
                <th class="text-end"><?= euros($total_p) ?></th>
                <th class="text-end"><?= $cant_a ?></th>
                <th class="text-end"><?= euros($total_a) ?></th>
                <th></th>
                <th class="text-end"><?= $cant_f ?></th>
                <th class="text-end"><?= euros($total_f) ?></th>
                <th></th>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<script>
  $('.datatable').DataTable({
    order: [1, "desc"],
    searching: true,
    dom: 'ftp'
  });
</script>