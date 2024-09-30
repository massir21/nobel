<!DOCTYPE html>
<html lang="es">
<!-- BEGIN HEAD -->

<head>
  <title><?= SITETITLE ?></title>
  <meta charset="utf-8" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap');

    * {
      margin: 0;
      padding: 0
    }

    @page {
      size: A4;
      margin: 1cm 0;

      @top-left {
        content: element(header);
      }

      @bottom-left {
        content: element(footer);
      }
    }

    body {
      margin: 0;
      padding: 0;
      color: #000000;
      font-family: 'Montserrat', sans-serif;
      font-size: 10pt;
    }

    a {
      color: inherit;
      text-decoration: none;
    }

    hr {
      margin: 1cm 0;
      height: 0;
      border: 0;
      border-top: 1mm solid #eeeeee;
    }

    header {
      margin-top: -1cm;
      padding: .5cm 1cm;
      background-color: #f5f8fa;
    }

    header .headerSection {
      width: 100%;
    }

    header .headerSection h3 {
      margin: 0;
      color: #626262;
    }

    header .headerSection div:last-of-type h3:last-of-type {
      margin-top: .5cm;
    }


    header .headerSection div p {
      margin-top: 2px;
    }

    header h1,
    header h2,
    header h3,
    header p {
      margin: 0;
    }

    header .invoiceDetails,
    header .invoiceDetails h2 {
      text-align: right;
      font-size: 1rem;
      text-transform: none;
    }

    header h2,
    header h3 {
      text-transform: uppercase;
    }

    header hr {
      margin: .5cm 0 .5cm 0;
    }

    main {
      padding: 1cm;
    }

    main table {
      width: 100%;
      border-collapse: collapse;
    }

    main table thead th {
      height: 1cm;
      color: #626262;
    }

    main table thead th:nth-of-type(2),
    main table thead th:nth-of-type(3),
    main table thead th:last-of-type {
      width: 2.5cm;
    }

    main table tbody td {
      padding: 2mm 0;
    }

    main table thead th:last-of-type,
    main table tbody td:last-of-type {
      text-align: right;
    }

    main table th {
      text-align: left;
    }

    main table.summary {
      width: calc(40% + 2cm);
      margin-left: 60%;
      margin-top: .5cm;
    }

    main table.summary tr.total {
      font-weight: bold;
      background-color: #60D0E4;
    }

    main table.summary th {
      padding: 4mm 0 4mm 1cm;
    }

    main table.summary td {
      padding: 4mm 2cm 4mm 0;
      border-bottom: 0;
    }

    aside {

      padding: 0 2cm .5cm 2cm;
    }

    aside>div {

      justify-content: space-between;
    }

    aside>div>div {
      width: 45%;
    }

    aside>div>div ul {
      list-style-type: none;
      margin: 0;
    }

    footer {
      position: fixed;
      bottom: -2cm;
      left: 0px;
      right: 0px;
      height: 2cm;
      background-color: #f5f8fa;
      font-size: 8pt;
      padding-top: .3cm;
      text-align: center;

    }

    footer a:first-child {
      font-weight: bold;
    }

    .table-striped tbody tr:nth-child(odd) {
      background-color: #f9f9f9;
    }

    .table-striped tbody tr:nth-child(even) {
      background-color: #ffffff;
    }

    .table-striped th,
    .table-striped td {
      padding-left: 3px;
      padding-right: 3px;
    }

    .table-striped tbody td:first-child(),
    .table-striped tbody tr:first-child() {
      padding-left: .3cm;
    }

    .table-striped tbody td:last-child(),
    .table-striped tbody tr:last-child() {
      padding-right: .3cm;
    }

    .tabla-dientes td {
      padding: 1px;
    }

    .page_number:after {
      content: counter(page);
    }
    .tabla_presupuesto tr td{
      font-size: 11px;
      padding: 0px;
      color: #454545;
    }
  </style>
</head>
<?php
$logo = FCPATH . '/assets_v5/media/logos/logo-dorado-sm.png';
$type = pathinfo($logo, PATHINFO_EXTENSION);
$data = file_get_contents($logo);
$logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>

<body>

  <footer>
    <span>
      <?= (isset($centro[0]['razon_social_centro']) && $centro[0]['razon_social_centro'] != '') ? $centro[0]['razon_social_centro'] . ' - ' : '' ?>
      <?= (isset($centro[0]['cif_centro'])) ? $centro[0]['cif_centro'] . ' - ' : '' ?>
      <?= (isset($centro[0]['direccion_centro'])) ? $centro[0]['direccion_centro'] . ' - ' : '' ?> pag. <span class="page_number"></span>
    </span>
  </footer>

  <div style="width: 100%; text-align: center;">

    <header>
      <table class="headerSection">
        <tr>
          <td class="logoAndName">
            <img src="<?= $logo ?>" style=" height:2.5cm;" />
          </td>
          <td class="invoiceDetails">
            <h2>Recibo <?= $recibo[0]['numero_recibo'] ?></h2>
            <br>
            <p><?= (isset($recibo[0]['fecha_emision_ddmmaaaa'])) ? $recibo[0]['fecha_emision_ddmmaaaa'] : ''; ?></p>
          </td>
        </tr>
      </table>
      <hr />
      <table class="headerSection">
        <tr>
          <td style="vertical-align: top;">
            <h3>Paciente</h3>
            <p>
              <b><?= (isset($cliente[0]['empresa'])) ? $cliente[0]['empresa'] : ''; ?></b>
              <?= (isset($cliente[0]['cif_nif'])) ? '<br>NIF: ' . $cliente[0]['cif_nif'] : ''; ?>
              <?= (isset($cliente[0]['telefono'])) ? '<br>Teléfono: ' . $cliente[0]['telefono'] : ''; ?>
              <?= (isset($cliente[0]['direccion_recibocion'])) ? '<br>Dirección: ' . $cliente[0]['direccion_recibocion'] : ''; ?>
              <?= (isset($cliente[0]['localidad_recibocion'])) ? '<br>Localidad: ' . $cliente[0]['localidad_recibocion'] : ''; ?>
              <?= (isset($cliente[0]['codigo_postal_recibocion'])) ? '<br>Código postal: ' . $cliente[0]['codigo_postal_recibocion'] : ''; ?>
              <?= (isset($cliente[0]['provincia_recibocion'])) ? '<br>Provincia: ' . $cliente[0]['provincia_recibocion'] : ''; ?>
            </p>
          </td>


          <td style="vertical-align: top;">
            <h3>Centro</h3>
            <p>
              <b><?= (isset($centro[0]['razon_social_centro'])) ? $centro[0]['razon_social_centro'] : ''; ?></b>
              <?= (isset($centro[0]['cif_centro'])) ? '<br>CIF: ' . $centro[0]['cif_centro'] : ''; ?>
              <?= (isset($centro[0]['direccion_centro'])) ? '<br>Dirección: ' . $centro[0]['direccion_centro'] : ''; ?>
            </p>
          </td>
        </tr>
      </table>
    </header>


    <main>

    <?php 
    $total_presupuesto=0;
    if (count($desglose_presupuestos) > 0) {
        foreach ($desglose_presupuestos as $key => $presupuesto) { 
          
          //show_array($presupuesto);
          ?>
          <h4 style="text-align: center; text-transform: uppercase; padding: 10px; background-color: #f5f8fa; margin-bottom: 0px;">
            Presupuesto #<?= $key ?>
          </h4>
          <table class="table table-striped" style="width: 100%; margin-bottom: 0px;">
            <thead>
              <tr>
                <th style="text-align: left">Servicio</th>
                <th style="text-align: center">Cantidad</th>
                <th style="text-align: center">Dientes</th>
                <th style="text-align: right">PVP(€)</th>
                <th style="text-align: right">Dto(%)</th>
                <th style="text-align: right">Total</th>
              </tr>
            </thead>
            <tbody class="tabla_presupuesto">
              <?php
              $item_entrada = ['entrada' => '', 'mensualidad' => '', 'coste' => 0];
              $ver_item_entrada = false;
              $padres = [];
              foreach ($presupuesto as $i => $value) {
                $total_presupuesto+=$value['pvp'];
                if (stripos($value['nombre_item'], "entrada") !== false || stripos($value['nombre_item'], "mensualidad") !== false) {
                  $coste_item = $value['cantidad'] * $value['pvp'] * (1 - $value['dto'] / 100);
                  if (stripos($value['nombre_item'], "entrada") !== false) {
                    $item_entrada['entrada'] = str_ireplace('entrada', '', $value['nombre_item']);
                  }
                  if (stripos($value['nombre_item'], "mensualidad") !== false) {
                    $item_entrada['mensualidad'] = str_ireplace('mensualidad', '', $value['nombre_item']);
                  }
                  $item_entrada['coste'] +=  $coste_item;
                  $ver_item_entrada = true;
                } else if ($value['padre'] > 0) {
                  $coste_item = $value['cantidad'] * $value['pvp'] * (1 - $value['dto'] / 100);
                  $pvp_item = $value['cantidad'] * $value['pvp'];
                  if (!array_key_exists($value['padre'], $padres)) {
                    $padres[$value['padre']][$value['dientes']]['coste'] =  $coste_item;
                    $padres[$value['padre']][$value['dientes']]['pvp'] =  $pvp_item;
                  } else {
                    if (!isset($padres[$value['padre']][$value['dientes']]['coste'])) {
                      $padres[$value['padre']][$value['dientes']]['coste'] = 0;
                      $padres[$value['padre']][$value['dientes']]['pvp'] = 0;
                    }
                    $padres[$value['padre']][$value['dientes']]['coste'] +=  $coste_item;
                    $padres[$value['padre']][$value['dientes']]['pvp'] +=  $pvp_item;
                  }
                } else {

                  if ($value['aceptado'] == 1) { ?>
                    <tr>
                      <td style="width: 40%; text-align: left;"><?php echo strtoupper($value['nombre_item']) . " (" . $value['nombre_familia'] . ")"; ?></td>
                      <td style="text-align: center;"><?= $value['cantidad'] ?></td>
                      <td style="text-align: center;"><?= $value['dientes'] ?></td>
                      <td style="text-align: right;"><?= $value['pvp'] ?></td>
                      <td style="text-align: right;"><?= $value['dto'] ?></td>
                      <td style="text-align: right;"><?php
                          $total = $value['cantidad'] * $value['pvp'] * (1 - $value['dto'] / 100);
                          echo euros($total);
                      ?>
                      </td>
                    </tr>
                <?php }
                 $dientes_seleccionados="";
                  if ($value['dientes'] != '') {
                    $dientes_seleccionados .= ',' . $value['dientes'];
                  }
                } ?>
              <?php } ?>
              <?php if ($ver_item_entrada == true) {
                $nombre_entrada = ($item_entrada['entrada'] != '') ? $item_entrada['entrada'] : $item_entrada['mensualidad']; ?>
                <tr>
                  <td style="width: 40%; text-align: left;"><?php echo strtoupper($nombre_entrada); ?></td>
                  <td style="text-align: center;">1</td>
                  <td style="text-align: center;"></td>
                  <td style="text-align: right;"><?= number_format($item_entrada['coste'], 2, ',', '.'); ?></td>
                  <td style="text-align: right;">0.00</td>
                  <td style="text-align: right;"><?= euros($item_entrada['coste']); ?>
                  </td>
                </tr>
              <?php } ?>

              <?php if (count($padres) > 0) {

                foreach ($padres as $k => $dientes) {

                  $param['id_servicio'] = $k;
                  $padre = $this->Servicios_model->leer_servicios($param)[0];
                  foreach ($dientes as $d => $diente) { ?>
                    <tr>
                      <td style="width: 40%; text-align: left;"><?php echo strtoupper($padre['nombre_servicio']); ?></td>
                      <td style="text-align: center;">1</td>
                      <td style="text-align: center;"><?= $d ?></td>
                      <td style="text-align: right;"><?= number_format($diente['pvp'], 2, ',', '.'); ?></td>
                      <td style="text-align: right;">0.00</td>
                      <td style="text-align: right;"><?= euros($diente['coste']); ?>
                      </td>
                    </tr>
              <?php }
                }
              } ?>
            </tbody>
            <tfoot>
              <tr>
                <td style="text-align: right; font-weight: bold;" colspan="5">Total del presupuesto</td>
                <td style="text-align: right;"><?= euros($total_presupuesto); ?></td>
              </tr>
            </tfoot>
          </table>

        <?php } ?>

      <?php } ?>

      <table class="table table-striped" style="width: 100%; margin-bottom: 0px;">
        <thead>
          <tr>
            <th style="text-align: left;width: 80%;" colspan="2">CONCEPTOS</th>
            <th style="width: 20%; text-align: right;">Pago</th>
          </tr>
        </thead>
        <tbody>
          <?php if (isset($conceptos)) {
            if ($conceptos != 0) {
              foreach ($conceptos as $key => $row) {
                $importe = ($row['total'] + $row['descuento_euros']) / (($row['iva'] / 100) + 1);
                $descuento = ($row['descuento_euros'] / (($row['iva'] / 100) + 1)) * -1;
                $mostrar_frase = ($row['iva'] == 0) ? "si" : "no";
          ?>
                <tr>
                  <td colspan="2">
                    <?php if ($row['id_presupuesto'] > 0) { ?>
                      Pago a cuenta de presupuesto #<?php echo $row['descripcion']; ?>
                    <?php } else {
                      echo $row['descripcion'];
                    } ?>
                    <br>
                    Forma pago: <?php echo str_replace("#", " ", $row['tipo_pago']); ?>
                    <br>
                    Fecha: <?php echo $row['fecha_hora_concepto']; ?>
                  </td>
                  <td style="text-align: right; padding: 5px; vertical-align: top; font-weight: bold;">
                    <?php echo number_format($importe, 2, ',', '.'); ?> €
                  </td>
                </tr>
                <?php if ($row['descuento_euros'] > 0) { ?>
                  <tr>
                    <td colspan="2">
                      <span style="font-size: 13px;">
                        <?php if ($row['descuento_euros'] > 0) { ?>
                          Descuento <?php echo number_format($row['descuento_porcentaje'], 2, ',', '.'); ?>%
                          (<?php echo number_format($row['descuento_euros'], 2, ',', '.'); ?>€)
                          <br>
                        <?php } ?>
                      </span>
                    </td>
                    <td style="text-align: right; padding: 5px; vertical-align: top;">
                      <?php echo number_format($descuento, 2, ',', '.'); ?> €
                    </td>
                  </tr>
                <?php } ?>
          <?php }
            }
          } ?>
        </tbody>
      </table>

      <table class="table table-striped" style="width: 100%; margin-top: 0px;">
        <tbody>
          <tr>
            <td style="text-align: right;width: 80%; padding:0px;" colspan="2">Subtotal</td>
            <td style="text-align: right; width: 20%;  padding:0px;"><?php echo number_format($recibo[0]['importe'], 2, ',', '.'); ?> €</td>
          </tr>
          <?php if (isset($ivas_desglose)) {
            if ($ivas_desglose != 0) {
              foreach ($ivas_desglose as $key => $row) { ?>
                <tr>

                  <td style="text-align: right;width: 80%; padding:0px;" colspan="2">I.V.A. <?php echo round($row['iva'], 2) . "%"; ?></td>
                  <td style="text-align: right;  padding:0px;">
                    <?php echo number_format($row['iva_suma'], 2, ',', '.'); ?> €
                  </td>
                </tr>
          <?php }
            }
          } ?>
          <tr>
            <td style="text-align: right;width: 80%; padding:0px;" colspan="2"><strong>TOTAL PAGO</strong></td>
            <td style="text-align: right;  padding:0px;">
              <b><?php echo number_format($recibo[0]['total'], 2, ',', '.'); ?> €</b>
            </td>
          </tr>
        </tbody>
      </table>

      <?php if ($mostrar_frase == "si") { ?>
        <table class="table table-striped" style="width: 100%;margin-top: 1rem;">
          <tbody>
            <tr>
              <td style="text-align: center;">Recibo exenta de IVA.</td>
            </tr>
          </tbody>
        </table>
      <?php } ?>
    </main>
    <aside>
      <hr />
      <div>
        <p id="watermark" style="font-size: 9px; text-align: justify; ">
          <?php if (isset($centro[0]['razon_social_centro'])) {
            echo $centro[0]['razon_social_centro'];
          } ?>
          es el Responsable del tratamiento de los datos personales
          del Interesado y le informa que estos datos serán
          tratados de conformidad con lo dispuesto en las normativas vigentes en protección de
          datos personales, el Reglamento (UE) 2016/679 y la Ley Orgánica 15/1999, con la finalidad
          de prestación de servicios profesionales y comunicación sobre productos y servicios.
          Los datos se conservarán mientras exista un interés mutuo para la finalidad descrita.
          Así mismo, se cederán los datos para cumplir con la finalidad del tratamiento y con
          las obligaciones legales que pudieran derivarse de la relación contractual.
          El Interesado puede ejercer sus derechos de acceso, rectificación, portabilidad y
          supresión de sus datos y a la limitación u oposición a su tratamiento dirigiendo un
          escrito a: administracion@clinicadentalnobel.es
        </p>
      </div>
    </aside>
  </div>
</body>

</html>