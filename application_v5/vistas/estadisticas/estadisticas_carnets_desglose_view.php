<style>            
    .dataTables_filter {
        text-align: right;        
    }
</style>
<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="logs_carnets2">
            </div>
        </div>
    </div>
    <div class="card-body pt-6">
        <span class="caption-subject bold uppercase"> Estadísticas de Carnets - Desglose de Carnets Sin Usar desde hace 1 Año</span>
        <div class="table-responsive">
            <table id="logs_carnets2" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th style="display: none;">ID</th>
                        <th>Código</th>
                        <th>Tipo de Carnet</th>                    
                        <th>Templos Disponibles</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php if(isset($datos) && $datos != 0){
                        foreach ($datos as $row) { ?>
                            <tr>
                                <td style="display: none;"><?php echo $row['codigo'] ?></td>
                                <td>
                                    <button class="btn btn-secondary btn-sm btn-text d-inline-flex align-items-center" type="button" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Ver detalle del Carnet" onclick="VerCarnetsDetalle(<?php echo $row['id_carnet'] ?>);"><i class="fas fa-id-card"></i> <?php echo $row['codigo']; ?></button>
                                </td>
                                <td><?php echo $row['descripcion'] ?></td>                    
                                <td>
                                    <?php if ($row['id_tipo']==99) { echo $row['numero_templos_especiales']; }
                                        else { echo $row['numero_templos']; }
                                    ?>
                                </td>        
                            </tr>
                        <?php } 
                        }?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function VerCarnetsDetalle(id_carnet) {
        var posicion_x; 
        var posicion_y;
        var ancho=640;
        var alto=480;
        posicion_x=(screen.width/2)-(ancho/2);
        posicion_y=(screen.height/2)-(alto/2);
        window.open("<?php echo base_url();?>dietario/carnets_pago/ver/"+id_carnet, "_blank", "toolbar=no,scrollbars=no,resizable=no,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);
    }
</script>