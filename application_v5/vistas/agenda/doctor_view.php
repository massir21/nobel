<style>
    .dataTables_filter {
        text-align: right;
    }
</style>
<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title flex-column">
        <h3 class="w-100" id="fecha_completa"></h3>
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="dietario">
            </div>
        </div>
        <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
            <div class="w-auto ms-3">
                <input type="date" id="fecha" name="fecha" value="<?php if (isset($fecha)) { echo $fecha;} ?>" id="datepicker" class="form-control form-control-solid w-auto" placeholder="Fecha" onchange="FechaCompleta(this.value); NuevoDia();" ; required />
            </div>
        </div>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">
            <table id="dietario"  class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th>Hora</th>
                        <th>Cliente</th>
                        <th>Servicio</th>
                        <th>Nota</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php if (isset($citas_agenda)) {
                        if ($citas_agenda != 0) {
                            foreach ($citas_agenda as $key => $row) { ?>
                                <tr>
                                    <td>
                                        <?php echo $row['hora_inicio']; ?>
                                    </td>
                                    <td>
                                        <a class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6" href="<?php echo base_url(); ?>clientes/historial/ver/<?php echo $row['id_cliente']; ?>" target="_blanck"><?php echo $row['cliente']; ?></a>
                                    </td>
                                    <td>
                                        <?php echo $row['servicio']; ?>
                                        <?=($row['dientes'] != '') ? ' <br><small>(PIEZA Nº:'.$row['dientes'].')</small>':'';?>
                                    </td>
                                    <td>
                                        <?php echo $row['observaciones']; ?>
                                    </td>
                                </tr>
                            <?php }
                        }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    function FechaCompleta(fecha) {
        var meses = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        var diasSemana = new Array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
        var f = new Date(fecha);
        document.getElementById("fecha_completa").innerHTML = diasSemana[f.getDay()] + ", " + f.getDate() + " de " + meses[f.getMonth()] + " de " + f.getFullYear();
    }
    <?php if (isset($fecha)) { ?>
        FechaCompleta("<?php echo $fecha; ?>");
    <?php } ?>
    function NuevoDia() {
        document.location.href = "<?php echo base_url(); ?>agenda/prueba_doctor/" + document.getElementById("fecha").value;
    }
</script>