<style>
  .custom-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.5s ease-in-out;
  }

  .card-subtitle-small {
    font-size: 1.0rem;
    /* Ajusta el tamaño según tus preferencias */
  }

  .card-amount {
    font-size: 2.5rem;
    /* Ajusta el tamaño según tus preferencias */
    font-weight: bold;
    color: #4CAF50;
    /* Color verde de Material Design */
  }

  .card-amount-bills {
    font-size: 2.5rem;
    /* Ajusta el tamaño según tus preferencias */
    font-weight: bold;
    color: #424242;
    /* Color rojo de Material Design */
  }


  .card-amount-earnings {
    font-size: 2.5rem;
    /* Ajusta el tamaño según tus preferencias */
    font-weight: bold;
    color: #2196F3;
    /* Color rojo de Material Design */
  }

  .loader_balance {
    position: absolute;
    width: 100%;
    height: 100%;
    top: -20px;
    left: -20px;
    text-align: center;
    background-color: rgba(255, 255, 255, 0.5);
    z-index: 1;
    display: none;
  }

  .tipo_gasto {
    /*cursor: pointer;*/
  }

  .tipo_gasto:hover {
    /*background-color:#f5f5f5;*/
  }

  /* ocultamos los elementos que no queremos ver al imprimir */
  @media print {
    .filtro {
      display: none;
    }

    .app-header {
      display: none;
    }
  }
</style>
<div class="loader_balance"></div>
<form method="post">
  <div class="card card-flush filtro">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
      <div class="card-title">
        <div class="card-title">

        </div>
      </div>
      <div class="card-toolbar flex-row-fluid justify-content-start gap-5">

        <div class="col-md-2">
          <label class="form-label">Centro:</label>
          <select name="id_centro" id="centro" class="form-select form-select-solid">
            <option value="0">Todos</option>
            <?php if (isset($centros_todos)) {
              if ($centros_todos != 0) {
                foreach ($centros_todos as $key => $row) {
                  if ($row['id_centro'] > 1) { ?>
                    <option value='<?php echo $row['id_centro']; ?>' <?php if (isset($id_centro)) {
                                                                        if ($row['id_centro'] == $id_centro) {
                                                                          echo "selected";
                                                                        }
                                                                      } ?>>
                      <?php echo $row['nombre_centro']; ?>
                    </option>
            <?php }
                }
              }
            } ?>
          </select>
        </div>

        <div class="col-md-2">
          <label class="form-label">Mes:</label>
          <select name="mes" id="mes" class="form-select form-select-solid">
            <?php if (isset($todos_meses)) {
              foreach ($todos_meses as $mes_r) { ?>
                <option value='<?php echo $mes_r; ?>' <?php if ($mes_r == $mes) echo "selected"; ?>>
                  <?php echo mesletra($mes_r); ?>
                </option>
            <?php }
            } ?>

          </select>
        </div>

        <div class="col-md-2">
          <label class="form-label">Año:</label>
          <select name="ano" id="anio" class="form-select form-select-solid">
            <?php if (isset($todos_anios)) {
              foreach ($todos_anios as $ano_r) { ?>
                <option value='<?php echo $ano_r; ?>' <?php if ($ano_r == $ano) echo "selected"; ?>>
                  <?php echo $ano_r; ?>
                </option>
            <?php }
            } ?>
          </select>
        </div>
        <div class="col-md-2">
          <br>
          <button type="submit" class="btn btn-success pull-right" id="consultar"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-md-3" style="text-align: right;">
          <br>
          <a class="btn btn-info pull-right" onclick="window.print()"><i class="bi bi-printer"></i></a>
        </div>
      </div>

      <!--<div class="w-auto ms-3">
                <label class="form-label">Proveedor:</label>
                <select name="proveedor_id" id="proveedor_id" class="form-select form-select-solid w-auto">
                    <option value="">Todos</option>
                    <?php if (isset($proveedor) && !empty($proveedor)) : ?>
                        <?php if (count($proveedor) > 0) : ?>
                            <?php foreach ($proveedor as $key => $row) : ?>
                                <option value="<?php echo $row['id_proveedor'] ?>"><?php echo $row['nombreProveedor'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </select>
            </div>-->
    </div>
  </div>

  <!-- RESUMEN -->
  <div class="row pt-5">
    <div class="col-12-md">
      <div class="card text-start gap-5">
        <div class="card-body">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>CENTRO</th>
                <th class="text-end">UD. PRESENTADOS</th>
                <th class="text-end">PRE. PRSENTADOS</th>
                <th class="text-end">UD. ACEPTADOS</th>
                <th class="text-end">PRE. ACEPTADOS</th>
                <th>%</th>
                <th class="text-end">UD. REALIZADOS</th>
                <th class="text-end">SERV. REALIZADOS</th>
                <th>%</th>
              </tr>
            </thead>
            <tbody>
              <?php
              //show_array($centros);
              if (count($centros[0]->data) > 0) foreach ($centros as &$c) {
                //cantidad de presupuestos
                $cant_p = 0;
                foreach ($c->data as $p) {
                  $cant_p += $p->cantidad;
                }
                //total presupuestado
                $total_p = 0;
                foreach ($c->data as $p) {
                  $total_p += $p->total;
                }
                //cantidad aceptados
                $cant_a = 0;
                foreach ($c->data as $p) if ($p->estado == "Aceptado" || $p->estado == "Aceptado parcial") {
                  $cant_a += $p->cantidad;
                }
                //total Aceptado
                $total_a = 0;
                foreach ($c->data as $p) if ($p->estado == "Aceptado" || $p->estado == "Aceptado parcial") {
                  $total_a += $p->total;
                }
                //cantidad Finalizado
                $cant_f = 0;
                foreach ($c->detalle as $d) if ($d->estado == "Finalizado") {
                  $cant_f++;
                }
                //total Finalizado
                $total_f = 0;
                foreach ($c->detalle as $d) if ($d->estado == "Finalizado") {
                  if ($d->dto > 0) {
                    $d->total -= $d->total * ($d->dto / 100);
                  }
                  $total_f += $d->total;
                }
                //creamos las variantes para los siguientes reportes
                if (!isset($c->cant_p)) {
                  $c->cant_p = 0;
                }
                $c->cant_p += $cant_p;
                if (!isset($c->total_p)) {
                  $c->total_p = 0;
                }
                $c->total_p += $total_p;
                if (!isset($c->cant_a)) {
                  $c->cant_a = 0;
                }
                $c->cant_a += $cant_a;
                if (!isset($c->total_a)) {
                  $c->total_a = 0;
                }
                $c->total_a += $total_a;
                if (!isset($c->cant_f)) {
                  $c->cant_f = 0;
                }
                $c->cant_f += $cant_f;
                if (!isset($c->total_f)) {
                  $c->total_f = 0;
                }
                $c->total_f += $total_f;


              ?>
                <tr>
                  <td><?= $c->nombre_centro ?></td>
                  <td class="text-end"><?= $cant_p ?></td>
                  <td class="text-end"><?= euros($total_p)  ?></td>
                  <td class="text-end"><?= $cant_a ?></td>
                  <td class="text-end"><?= euros($total_a)  ?></td>
                  <td><?php if ($total_p > 0) {
                        echo round(($total_a / $total_p) * 100);
                      } ?>%</td>
                  <td class="text-end"><?= $cant_f ?></td>
                  <td class="text-end"><?= euros($total_f)  ?></td>
                  <td><?php if ($total_p > 0) {
                        echo round(($total_f / $total_p) * 100);
                      } ?>%</td>
                </tr>
              <?php } ?>
              <?php
              //show_array($centros);exit;
              $total_p = 0;
              $cant_p = 0;
              $total_a = 0;
              $cant_a = 0;
              $total_f = 0;
              $cant_f = 0;
              if (count($centros[0]->data) > 0) foreach ($centros as $c2) {
                if (isset($c2->total_p) && $c2->total_p > 0) {
                  $total_p += $c2->total_p;
                }
                if (isset($c2->cant_p) && $c2->cant_p > 0) {
                  $cant_p += $c2->cant_p;
                }
                if (isset($c2->total_a) && $c2->total_a > 0) {
                  $total_a += $c2->total_a;
                }
                if (isset($c2->cant_a) && $c2->cant_a > 0) {
                  $cant_a += $c2->cant_a;
                }
                if (isset($c2->total_f) && $c2->total_f > 0) {
                  $total_f += $c2->total_f;
                }
                if (isset($c2->cant_f) && $c2->cant_f > 0) {
                  $cant_f += $c2->cant_f;
                }
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


  <!--Tipo Paciente -->
  <div class="row pt-5">
    <div class="col-md-4">
      <div class="row" style="display: none;">
        <div class="col-md-6">
          <div class="alert alert-primary text-center bg-primary" style="color:white" role="alert">
            <b><?php if ($cant_p > 0) {
                  echo round(($cant_a / $cant_p) * 100);
                } ?>%</b> Aceptado
          </div>
        </div>
        <div class="col-md-6">
          <div class="alert alert-success text-center bg-success" style="color:white" role="alert">
            <b><?= round($cant_a) ?></b> P. Aceptados
          </div>
        </div>
      </div>
      <div class="card text-start">
        <div class="card-body">
          <table class="table table-striped">
            <tbody>
              <tr>
                <th>Total Presupuestado</th>
                <td class="text-end"><?= euros($total_p) ?></td>
              </tr>
              <tr>
                <th>UD Presupuestado</th>
                <td class="text-end"><?= $cant_p ?></td>
              </tr>
              <tr>
                <th>Ticket promedio</th>
                <td class="text-end"><?php if ($cant_p > 0) {
                                        echo euros($total_p / $cant_p);
                                      } ?></td>
              </tr>
              <tr>
                <td colspan="2">
                  <hr>
                </td>
              </tr>
              <tr>
                <th>Total Aceptado</th>
                <td class="text-end"><?= euros($total_a) ?></td>
              </tr>
              <tr>
                <th>UD Aceptado</th>
                <td class="text-end"><?= $cant_a ?></td>
              </tr>
              <tr>
                <th>Ticket promedio</th>
                <td class="text-end"><?php if ($cant_a > 0) {
                                        echo euros($total_a / $cant_a);
                                      } ?></td>
              </tr>
              <tr>
                <td colspan="2">
                  <hr>
                </td>
              </tr>
              <tr>
                <th>Servicios realizados</th>
                <td class="text-end"><?= euros($total_f) ?></td>
              </tr>
              <tr>
                <th>Pacientes presentados</th>
                <td class="text-end"><?= $cant_f ?></td>
              </tr>
              <tr>
                <th>Ticket promedio</th>
                <td class="text-end"><?php if ($cant_f > 0) {
                                        echo euros($total_f / $cant_f);
                                      } ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>


      <?php
      $cf_presupuesto[0]['total'] = 0;
      $cf_presupuesto[0]['cantidad'] = 0;
      $cf_presupuesto[1]['total'] = 0;
      $cf_presupuesto[1]['cantidad'] = 0;

      $cf_aceptado[0]['total'] = 0;
      $cf_aceptado[0]['cantidad'] = 0;
      $cf_aceptado[1]['total'] = 0;
      $cf_aceptado[1]['cantidad'] = 0;

      if (count($centros[0]->data) > 0) foreach ($centros as $c4) if ($c4->presupuestos != '') foreach ($c4->presupuestos as $p2) if ($p2->totalpresupuesto > 0) {
        if ($p2->estado == 'Aceptado') {
          $cf_aceptado[$p2->frecuente]['total'] += $p2->totalpresupuesto;
          $cf_aceptado[$p2->frecuente]['cantidad']++;
        }
        $cf_presupuesto[$p2->frecuente]['total'] += $p2->totalpresupuesto;
        $cf_presupuesto[$p2->frecuente]['cantidad']++;
      }
      if (count($centros[0]->data) > 0) {
      ?>
        <div class="row pt-5">
          <div class="col-md-6">
            <div class="alert alert-primary text-center bg-primary" style="color:white" role="alert">PA <b><?= round(($cf_aceptado[0]['cantidad'] / ($cf_presupuesto[0]['cantidad'] + $cf_presupuesto[1]['cantidad'])) * 100, 2) ?>%</b> 1ra Visita</div>
          </div>
          <div class="col-md-6">
            <div class="alert alert-success text-center bg-success" style="color:white" role="alert">PA <b><?= round(($cf_aceptado[1]['cantidad'] / ($cf_presupuesto[0]['cantidad'] + $cf_presupuesto[1]['cantidad'])) * 100, 2) ?>%</b> Frecuente</div>
          </div>
        </div>
        <div class="card text-start">
          <div class="card-body">
            <table class="table table-striped">
              <tbody>
                <thead>
                  <th></th>
                  <th class="text-end">1ra Visita</th>
                  <th class="text-end">Frecuente</th>
                </thead>
                <tr>
                  <th>Total Presupuestado</th>
                  <td class="text-end"><?= euros($cf_presupuesto[0]['total']) ?></td>
                  <td class="text-end"><?= euros($cf_presupuesto[1]['total']) ?></td>
                </tr>
                <tr>
                  <th>UD Presupuestado</th>
                  <td class="text-end"><?= $cf_presupuesto[0]['cantidad'] ?></td>
                  <td class="text-end"><?= $cf_presupuesto[1]['cantidad'] ?></td>
                </tr>
                <tr>
                  <th>Ticket promedio</th>
                  <td class="text-end">
                    <?php if ($cf_presupuesto[0]['cantidad'] > 0) {
                      echo euros($cf_presupuesto[0]['total'] / $cf_presupuesto[0]['cantidad']);
                    } else {
                      echo '0';
                    } ?>
                  </td>
                  <td class="text-end">
                    <?php if ($cf_presupuesto[1]['cantidad'] > 0) {
                      echo euros($cf_presupuesto[1]['total'] / $cf_presupuesto[1]['cantidad']);
                    } else {
                      echo '0';
                    } ?>
                  </td>
                </tr>
                <tr>
                  <td colspan="3">
                    <hr>
                  </td>
                </tr>
                <tr>
                  <th>Total Aceptado</th>
                  <td class="text-end"><?= euros($cf_aceptado[0]['total']) ?></td>
                  <td class="text-end"><?= euros($cf_aceptado[1]['total']) ?></td>
                </tr>
                <tr>
                  <th>UD Aceptado</th>
                  <td class="text-end"><?= $cf_aceptado[0]['cantidad'] ?></td>
                  <td class="text-end"><?= $cf_aceptado[1]['cantidad'] ?></td>
                </tr>
                <tr>
                  <th>Ticket promedio</th>
                  <td class="text-end">
                    <?php if ($cf_aceptado[0]['cantidad'] > 0) {
                      echo euros($cf_aceptado[0]['total'] / $cf_aceptado[0]['cantidad']);
                    } else {
                      echo '0';
                    } ?>
                  </td>
                  <td class="text-end">
                    <?php if ($cf_aceptado[1]['cantidad'] > 0) {
                      echo euros($cf_aceptado[1]['total'] / $cf_aceptado[1]['cantidad']);
                    } else {
                      echo '0';
                    } ?>
                  </td>
                </tr>

              </tbody>
            </table>
          </div>






        </div>
      <?php
      } //condicion de que exista informacion

      $rangos[100]['euros'] = 0;
      $rangos[100]['cantidad'] = 0;
      $rangos[500]['euros'] = 0;
      $rangos[500]['cantidad'] = 0;
      $rangos[1000]['euros'] = 0;
      $rangos[1000]['cantidad'] = 0;
      $rangos[3000]['euros'] = 0;
      $rangos[3000]['cantidad'] = 0;
      $rangos[5000]['euros'] = 0;
      $rangos[5000]['cantidad'] = 0;
      $rangos[10000]['euros'] = 0;
      $rangos[10000]['cantidad'] = 0;
      $rangos[11000]['euros'] = 0;
      $rangos[11000]['cantidad'] = 0;
      $rangos['total']['euros'] = 0;
      $rangos['total']['cantidad'] = 0;
      if (count($centros[0]->data) > 0) foreach ($centros as $c3) if ($c3->presupuestos != '') foreach ($c3->presupuestos as $p) if ($p->totalpresupuesto > 0) {
        if ($p->totalpresupuesto >= 0 && $p->totalpresupuesto <= 100) {
          $rangos[100]['euros'] += $p->totalpresupuesto;
          $rangos[100]['cantidad']++;
        } elseif ($p->totalpresupuesto > 100 && $p->totalpresupuesto <= 500) {
          $rangos[500]['euros'] += $p->totalpresupuesto;
          $rangos[500]['cantidad']++;
        } elseif ($p->totalpresupuesto > 500 && $p->totalpresupuesto <= 1000) {
          $rangos[1000]['euros'] += $p->totalpresupuesto;
          $rangos[1000]['cantidad']++;
        } elseif ($p->totalpresupuesto > 1000 && $p->totalpresupuesto <= 3000) {
          $rangos[3000]['euros'] += $p->totalpresupuesto;
          $rangos[3000]['cantidad']++;
        } elseif ($p->totalpresupuesto > 3000 && $p->totalpresupuesto <= 5000) {
          $rangos[5000]['euros'] += $p->totalpresupuesto;
          $rangos[5000]['cantidad']++;
        } elseif ($p->totalpresupuesto > 5000 && $p->totalpresupuesto <= 10000) {
          $rangos[10000]['euros'] += $p->totalpresupuesto;
          $rangos[10000]['cantidad']++;
        } elseif ($p->totalpresupuesto > 10000) {
          $rangos[11000]['euros'] += $p->totalpresupuesto;
          $rangos[11000]['cantidad']++;
        } else {
          echo $p->totalpresupuesto;
        }
        $rangos['total']['euros'] += $p->totalpresupuesto;
        $rangos['total']['cantidad']++;
      }


      ?>

      <div class="row pt-5"></div>
      <div class="card text-start">
        <div class="card-body">
          <table class="table table-striped">
            <tbody>
              <thead>
                <th></th>
                <th class="text-end">Cantidad</th>
                <th class="text-end">Euros</th>
                <th class="text-end">Promedio</th>
              </thead>
              <tr>
                <th>0-100€</th>
                <td class="text-end"><?= $rangos[100]['cantidad'] ?></td>
                <td class="text-end"><?= euros($rangos[100]['euros']) ?></td>
                <td class="text-end"><?php if ($rangos[100]['cantidad']) {
                                        echo euros($rangos[100]['euros'] / $rangos[100]['cantidad']);
                                      } else {
                                        echo "0";
                                      } ?></td>
              </tr>
              <tr>
                <th>100-500€</th>
                <td class="text-end"><?= $rangos[500]['cantidad'] ?></td>
                <td class="text-end"><?= euros($rangos[500]['euros']) ?></td>
                <td class="text-end"><?php if ($rangos[500]['cantidad']) {
                                        echo euros($rangos[500]['euros'] / $rangos[500]['cantidad']);
                                      } else {
                                        echo "0";
                                      } ?></td>
              </tr>
              <tr>
                <th>500-1.000€</th>
                <td class="text-end"><?= $rangos[1000]['cantidad'] ?></td>
                <td class="text-end"><?= euros($rangos[1000]['euros']) ?></td>
                <td class="text-end"><?php if ($rangos[1000]['cantidad']) {
                                        echo euros($rangos[1000]['euros'] / $rangos[1000]['cantidad']);
                                      } else {
                                        echo "0";
                                      } ?></td>
              </tr>
              <tr>
                <th>1.000-3.000€</th>
                <td class="text-end"><?= $rangos[3000]['cantidad'] ?></td>
                <td class="text-end"><?= euros($rangos[3000]['euros']) ?></td>
                <td class="text-end"><?php if ($rangos[3000]['cantidad']) {
                                        echo euros($rangos[3000]['euros'] / $rangos[3000]['cantidad']);
                                      } else {
                                        echo "0";
                                      } ?></td>
              </tr>
              <tr>
                <th>3.000-5.000€</th>
                <td class="text-end"><?= $rangos[5000]['cantidad'] ?></td>
                <td class="text-end"><?= euros($rangos[5000]['euros']) ?></td>
                <td class="text-end"><?php if ($rangos[5000]['cantidad']) {
                                        echo euros($rangos[5000]['euros'] / $rangos[5000]['cantidad']);
                                      } else {
                                        echo "0";
                                      } ?></td>
              </tr>
              <tr>
                <th>5.000-10.000€ </th>
                <td class="text-end"><?= $rangos[10000]['cantidad'] ?></td>
                <td class="text-end"><?= euros($rangos[10000]['euros']) ?></td>
                <td class="text-end"><?php if ($rangos[10000]['cantidad']) {
                                        echo euros($rangos[10000]['euros'] / $rangos[10000]['cantidad']);
                                      } else {
                                        echo "0";
                                      } ?></td>
              </tr>
              <tr>
                <th>mas de 10.000€ </th>
                <td class="text-end"><?= $rangos[11000]['cantidad'] ?></td>
                <td class="text-end"><?= euros($rangos[11000]['euros']) ?></td>
                <td class="text-end"><?php if ($rangos[11000]['cantidad']) {
                                        echo euros($rangos[11000]['euros'] / $rangos[11000]['cantidad']);
                                      } else {
                                        echo "0";
                                      } ?></td>
              </tr>
              <tr>
                <td colspan="4">
                  <hr>
                </td>
              </tr>
              <tr>
                <th>Total</th>
                <td class="text-end"><?= $rangos['total']['cantidad'] ?></td>
                <td class="text-end"><?= euros($rangos['total']['euros']) ?></td>
                <td class="text-end"><?php if ($rangos['total']['cantidad']) {
                                        echo euros($rangos['total']['euros'] / $rangos['total']['cantidad']);
                                      } else {
                                        echo "0";
                                      } ?></td>
              </tr>
            </tbody>
          </table>
        </div>












      </div>


      <div class="card mt-5">
        <div class="card-body">




          <style>
            .tipo_gasto {
              cursor: pointer;
            }

            .tipo_gasto:hover {
              background-color: #f5f5f5;
            }
          </style>
          <h4 style="text-align: center;">Servicios realizados</h4>
          <table class="table">
            <thead>
              <tr>
                <th colspan="2">Familia</th>
                <th style="text-align: right;">Total</th>
                <th style="text-align: right;"></th>
              </tr>
            </thead>
            <tbody>
              <?php $total_servicios = 0;

               if (is_array($familias_servicios))
                foreach ($centros as $c3)
                  foreach ($c3->detalle as $d)
                    if ($d->estado == "Finalizado")
                      foreach ($familias_servicios as &$fs)
                        if($fs->id_familia_servicio==$d->id_familia_servicio){
                          $fs->total += $d->total;
                          $total_servicios += $d->total;
                    
                  }

              if (is_array($familias_servicios)) foreach ($familias_servicios as $fs)if($fs->total>0){
                $porcentaje = round(($fs->total / $total_servicios) * 100);
                // Calcular los valores de rojo, verde y azul
                $azul = (100 - $porcentaje) * 255 / 100;
                $verde = $porcentaje * 255 / 100;
                $rojo = 0; // En este caso, no necesitamos azul
                
                // Formatear el color en formato hexadecimal
                $color = sprintf("#%02x%02x%02x", $rojo, $verde, $azul);
              ?>
                <tr class="tipo_gasto" style="font-weight: bold;">
                  <td class="text-start fw-bold fs-5 text-uppercase" colspan="2"><?= $fs->nombre_familia ?></td>
                  <td class="text-end text-body fw-bold"><?= euros($fs->total) ?></td>
                  <td style="text-align: center;"><label style="color:white; background-color:<?= $color ?>; padding:0px 5px 0px 5px; border-radius:5px"><?= $porcentaje ?>% </label></td>
                </tr>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr style="border-top: 1px solid #424242;">
                <th colspan="2">Total servicios</th>
                <th style="text-align: right;"><?= euros($total_servicios) ?></th>
                <th></th>
              </tr>
            </tfoot>
          </table>

        </div>
      </div>



    </div>



    <!--Resumen medicos -->
    <div class="col-md-8">

      <div class="card text-start">
        <div class="card-body">

          <h2 class="text-center mt-15">Resumen anual</h2>
          <div class="row grafica">
            <canvas id="lineChart" style="height: 300px;"></canvas>
          </div><!-- row -->
        </div>
      </div>
      <div class="card text-start mt-5">
        <div class="card-body">

          <h2 class="text-center">Presupuesto por Doctores</h2>
          <table class="table table-striped">
            <thead>
              <tr>
                <th rowspan="2">Doctor</th>
                <th class="text-center" colspan="3">Presupuestado</th>
                <th class="text-center" colspan="3">Aceptado</th>

              </tr>
              <tr>
                <th class="text-end">€</th>
                <th class="text-end">Ud</th>
                <th class="text-end">T.Prom.</th>
                <th class="text-end">€</th>
                <th class="text-end">Ud</th>
                <th class="text-end">T.Prom.</th>
                <th class="text-end">%Ud</th>
                <th class="text-center">Aprobación</th>
              </tr>
            </thead>
            <tbody>

              <?php $bar = ['primary', 'warning', 'info', 'danger'];
              $ibar = 0;
              $max = 0;
              foreach ($presupuestos_doctores['doctores'] as $doc) {
                $max_temporal = 0;
                foreach ($presupuestos_doctores['presupuestos'] as $pre) if ($pre->id_doctor == $doc->id_usuario) {
                  $max_temporal++;
                }
                if ($max_temporal > $max) {
                  $max = $max_temporal;
                }
              }

              foreach ($presupuestos_doctores['doctores'] as $doc) {
                $cant_p = 0;
                $total_p = 0;
                $cant_a = 0;
                $total_a = 0;

                foreach ($presupuestos_doctores['presupuestos'] as $pre) if ($pre->id_doctor == $doc->id_usuario) {
                  if ($pre->estado == "Aceptado" || $pre->estado == "Aceptado parcial") {
                    $cant_a++;
                    $total_a += $pre->totalpresupuesto;
                  }
                  $cant_p++;
                  $total_p += $pre->totalpresupuesto;
                }
                //$rand =  round(($cant_p/$max)*100);
                $rand = round(($cant_a / $cant_p) * 100);
                // Calcular los valores de rojo, verde y azul
                $azul = (100 - $rand) * 255 / 100;
                $rojo = 0;
                $verde =  $rand * 255 / 100;
                $color = sprintf("#%02x%02x%02x", $rojo, $verde, $azul);


              ?>

                <tr>
                  <th><?= $doc->nombre . " " . $doc->apellidos ?></th>
                  <td class="text-end"><?= euros($total_p) ?></td>
                  <td class="text-end"><?= $cant_p ?></td>
                  <td class="text-end">
                    <?php if ($cant_p > 0) {
                      echo euros($total_p / $cant_p);
                    } else {
                      echo "0";
                    } ?>
                  </td>

                  <td class="text-end"><?= euros($total_a) ?></td>
                  <td class="text-end"><?= $cant_a ?></td>
                  <td class="text-end">
                    <?php if ($cant_a > 0) {
                      echo euros($total_a / $cant_a);
                    } else {
                      echo "0";
                    } ?>
                  </td>

                  <td class="text-end">
                    <?php if ($cant_p > 0) {
                      echo round(($cant_a / $cant_p) * 100);
                    } else {
                      echo "0";
                    } ?>%
                  </td>
                  <td style="width: 20%;">
                    <div class="progress">
                      <div class="progress-bar" role="progressbar" style="background-color:<?= $color ?>; width: <?= $rand ?>%" aria-valuenow="<?= $rand ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                  </td>
                </tr>
              <?php $ibar++;
                if ($ibar > count($bar) - 1) {
                  $ibar = 0;
                }
              } ?>

            </tbody>
          </table>


        </div>
      </div>

      <!-- Produccion medicos-->
      <div class="card text-start mt-5">
        <div class="card-body">
          <?php
          //show_array($citas_produccion);
          ?>
          <h2 class="text-center">Producción doctores</h2>
          <table class="table table-striped">
            <thead>
              <tr>
                <th rowspan="2">Doctor</th>
                <th class="text-end">Servicios realizados €</th>
                <th class="text-end">Pago a doctor €</th>
                <th class="text-end">Ganancia €</th>
                <th class="text-end">%</th>
              </tr>
            </thead>
            <tbody>

              <?php
              foreach ($doctores_con_cita as $doc) {
                $doc->serv = 0;
                foreach ($citas_produccion as $cita) if ($doc->id_usuario_empleado == $cita['id_usuario_empleado']) {
                  $doc->serv += $cita['importe_euros'];
                }
                $ganancia = $doc->serv - $doc->facturado;
                if ($doc->serv > 0) {
                  $porcentaje = round(($ganancia / $doc->serv) * 100);
                  $style_td = "style='color:green;font-weight: bold;'";
                  if ($porcentaje < 0) {
                    $style_td = "style='color:red;font-weight: bold;'";
                  }
              ?>

                  <tr>
                    <th><?= $doc->doctor ?></th>
                    <td class="text-end"><?= euros($doc->serv) ?></td>
                    <td class="text-end"><?= euros($doc->facturado) ?></td>
                    <td class="text-end" <?= $style_td ?>><?= euros($ganancia) ?></td>
                    <td class="text-end" <?= $style_td ?>><?= $porcentaje ?></td>
                  </tr>
              <?php }
              } ?>

            </tbody>
          </table>


        </div>
      </div><!-- div Produccion medicos -->
    </div><!-- div presupuestos medicos -->





  </div>



  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var selectElementMes = document.getElementById("mes");
      var defaultOptionMes = selectElementMes.options[selectElementMes.selectedIndex];
      var defaultTextMes = defaultOptionMes.textContent;

      $(".lblMes").html(defaultTextMes);


      var selectElementAnio = document.getElementById("anio");
      var defaultOptionAnio = selectElementAnio.options[selectElementAnio.selectedIndex];
      var defaultTextAnio = defaultOptionAnio.textContent;

      $(".lblAnio").html(defaultTextAnio);

    });
    $("#consultar").click(function() {
      $(".loader_balance").fadeIn(200);
    })


    // Datos del gráfico
    <?php
    $data_grafica_p = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    $data_grafica_a = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    $data_grafica_r = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    foreach ($presupuestos_anuales as &$c) {
      //total presupuestado
      foreach ($c->data as $p) {
        $data_grafica_p[$p->mes - 1] += $p->total;
      }
      //total Aceptado
      foreach ($c->data as $p) if ($p->estado == "Aceptado" || $p->estado == "Aceptado parcial") {
        $data_grafica_a[$p->mes - 1] += $p->total;
      }
    }
    for ($i = 0; $i < count($data_grafica_r); $i++) {
      $data_grafica_r[$i] = $data_grafica_p[$i] - $data_grafica_a[$i];
    }

    ?>
    var data = {
      labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
      datasets: [{
          label: 'Presupuestado',
          data: <?= json_encode($data_grafica_p) ?>,
          borderColor: '#013BBF',
          borderWidth: 2,
          fill: false
        },
        {
          label: 'Aceptado',
          data: <?= json_encode($data_grafica_a) ?>,
          borderColor: '#12BF01',
          borderWidth: 2,
          fill: false
        },
        {
          label: 'Rechazados',
          data: <?= json_encode($data_grafica_r) ?>,
          borderColor: '#DE5100',
          borderWidth: 2,
          fill: false
        }
      ]
    };

    // Configuración del gráfico
    var options = {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        x: {
          type: 'category',
          labels: data.labels
        },
        y: {
          beginAtZero: true
        }
      }
    };

    // Obtener el contexto del lienzo
    var ctx = document.getElementById('lineChart').getContext('2d');

    // Crear el gráfico de línea
    var myLineChart = new Chart(ctx, {
      type: 'line',
      data: data,
      options: options
    });
  </script>

  <script>
    $(document).ready(function() {
      $(".hiddenRows").hide();
      $('.tipo_gasto').click(function() {
        //toggle a los tr con la clase hiddenRows
        //$(this).nextUntil('.tipo_gasto').slideToggle();
      })
    });
  </script>