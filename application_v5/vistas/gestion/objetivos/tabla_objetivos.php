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
                <th class="text-end">FACTURACIÓN (€)</th>
                <th class="text-end">RENTABILIDAD (%)</th>
                <th class="text-end">RENTABILIDAD (€)</th>
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
                  <td><?= mesletra($o->mes)  ?></td>
                  <td class="text-end"><?=  euros($o->facturacion) ?></td>
                  <td class="text-end"><?= round($o->rentabilidad)  ?>%</td>
                  <td class="text-end"><?= euros($o->facturacion*($o->rentabilidad/100))  ?></td>

                  <td class="text-end">
                  <a class="btn btn-warning btn-sm editar_objetivo" 
                  id_objetivo="<?= $o->id_objetivo ?>"
                  nombre_centro="<?= $o->nombre_centro?>";
                  mes="<?= mesletra($o->mes) ?>"
                  ano="<?= $o->ano?>"
                  facturacion = "<?= $o->facturacion ?>"
                  rentabilidad = "<?= $o->rentabilidad ?>"
                  ><i class="bi bi-pencil"></i></a>
                  <a class="btn btn-danger btn-sm borrar_objetivo" url="./borrar_objetivo/<?= $o->id_objetivo ?>" id_objetivo="<?= $o->id_objetivo?>"><i class="bi bi-trash"></i></a>
                  </td>
                </tr>
                <?php } ?>
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