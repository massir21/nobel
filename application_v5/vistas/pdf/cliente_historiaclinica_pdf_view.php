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
            margin: 0 .75cm 0 0;
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
            padding: 2mm 1cm 2mm 0;
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
            padding-left: 1px;
            padding-right: 1px;
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
$logo = FCPATH . '/assets_v5/media/logos/logo-dorado-sm.png'; //'/recursos/logo-templos.png';
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
                    <br/><br/>
                    <h3>Historia Clínica</h3>
                    <br/>
                    <?php echo date("d/m/Y"); ?>
                </td>
                <td class="invoiceDetails">
                    <h3>Paciente</h3>
                    <p>
                        <b><?php echo $cliente[0]['nombre'] . ' ' . $cliente[0]['apellidos'] ?></b>
                        <?php if ($cliente[0]['dni'] != '') { ?>
                            <br>
                            DNI: <?php echo $cliente[0]['dni'] ?>
                        <?php } ?>
                        <?php if ($cliente[0]['telefono'] != '') { ?>
                            <br>
                            Teléfono: <?php echo $cliente[0]['telefono'] ?>
                        <?php } ?>
                    </p>
                </td>
            </tr>
        </table>

    </header>

    <main>
        <table class="table table-striped" style="width: 100%; margin-bottom: 20px;">
            <thead>
            <tr>
                <th style="text-align: left;width:25%">Fecha</th>
                <th style="text-align: left;width:25%">Doctor</th>
                <th style="text-align: left;width:50%">Nota</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($notas_evolutivo as $nota){
                ?>
                <tr>
                    <td><?= date("d/m/Y",strtotime($nota['fecha_nota']));?></td>
                    <td><?= $nota['doctor'];?></td>
                    <td style="text-align: left;font-weight: normal"><?= $nota['nota'];?></td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </main>
</div>















</body>

</html>
