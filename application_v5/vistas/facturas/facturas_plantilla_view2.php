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
            <h2>FACTURA <?= $factura[0]['numero_factura'] ?></h2>
            <br>
            <p><?= (isset($factura[0]['fecha_emision_ddmmaaaa'])) ? $factura[0]['fecha_emision_ddmmaaaa'] : ''; ?></p>
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
              <?= (isset($cliente[0]['direccion_facturacion'])) ? '<br>Dirección: ' . $cliente[0]['direccion_facturacion'] : ''; ?>
              <?= (isset($cliente[0]['localidad_facturacion'])) ? '<br>Localidad: ' . $cliente[0]['localidad_facturacion'] : ''; ?>
              <?= (isset($cliente[0]['codigo_postal_facturacion'])) ? '<br>Código postal: ' . $cliente[0]['codigo_postal_facturacion'] : ''; ?>
              <?= (isset($cliente[0]['provincia_facturacion'])) ? '<br>Provincia: ' . $cliente[0]['provincia_facturacion'] : ''; ?>
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

      <?php if (count($conceptos) > 0) { ?>
        <table class="table table-striped" style="width: 100%; margin-bottom: 20px;">
          <thead>
            <tr>
              <th style="text-align: left">Servicio</th>
              <th style="text-align: center">Cantidad</th>
              <th style="text-align: center">PVP</th>
              <th style="text-align: right">Dto</th>
              <th style="text-align: right">IVA</th>
              <th style="text-align: right">Total</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($conceptos as $value) {
              if ($value['iva'] == 0) {
                $mostrar_frase = 'si';
              }
            ?>
              <tr>
                <td style="width: 40%; text-align: left;"><?= $value['descripcion'] ?></td>
                <td style="text-align: center;">1</td>
                <td style="text-align: center;"><?= $value['importe'] ?></td>
                <td style="text-align: right;"><?= $value['descuento_euros'] ?></td>
                <td style="text-align: right;"><?= $value['iva_euros']; ?></td>
                <td style="text-align: right;"><?= euros($value['total']); ?>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>

      <?php } ?>


      <table class="table table-striped" style="width: 100%;">
        <tbody>
          <tr>
            <td style="text-align: right;width: 80%;" colspan="2">Subtotal</td>
            <td style="text-align: right; width: 20%;"><?php echo number_format($factura[0]['importe'], 2, ',', '.'); ?> €</td>
          </tr>
          <?php if (isset($ivas_desglose)) {
            if ($ivas_desglose != 0) {
              foreach ($ivas_desglose as $key => $row) { ?>
                <tr>

                  <td style="text-align: right;width: 80%;" colspan="2">I.V.A. <?php echo round($row['iva'], 2) . "%"; ?></td>
                  <td style="text-align: right;">
                    <?php echo number_format($row['iva_suma'], 2, ',', '.'); ?> €
                  </td>
                </tr>
          <?php }
            }
          } ?>
          <tr>

            <td style="text-align: right;width: 80%;" colspan="2"><strong>TOTAL</strong></td>
            <td style="text-align: right;">
              <b><?php echo number_format($factura[0]['total'], 2, ',', '.'); ?> €</b>
            </td>
          </tr>
        </tbody>
      </table>

      <?php if ($mostrar_frase == "si") { ?>
        <table class="table table-striped" style="width: 100%;margin-top: 1rem;">
          <tbody>
            <tr>
              <td style="text-align: center;">Factura exenta de IVA en virtud del Artic. 20.3 de la Ley 37/1992.</td>
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