<?php if (isset($estado)){
    if($estado > 0) { ?>
        <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
            <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE GUARDÓ CORRECTAMENTE</div>
            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="fa-times fas fs-3 text-primary"></i>
            </button>
        </div>
    <?php } else { ?>
        <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
            <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">YA EXISTE UN USUARIO CON EL MISMO EMAIL/USUARIO</div>
            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="fa-times fas fs-3 text-primary"></i>
            </button>
        </div>
    <?php } 
} ?>
<?php if (isset($borrado)) { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL REGISTRO SE BORRÓ CORRECTAMENTE</div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="fa-times fas fs-3 text-primary"></i>
        </button>
    </div>
<?php } ?>
<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <div class="card-title d-flex flex-column">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Escribe para buscar" data-table-search="myTable1">
            </div>
        </div>
        <div class="card-toolbar">
            <form method="post" >
                <select name="borrado" id="borrado" class="form-select" title="Ver usuarios en estado" onchange="this.form.submit()">        
                    <option value="0" <?=((isset($_POST['borrado']) && $_POST['borrado'] < 1) || !isset($_POST['borrado'])) ?'selected':''?>>No borrados</option>
                    <option value="1" <?=(isset($_POST['borrado']) && $_POST['borrado'] == 1)?'selected':''?>>Borrados</option>
                    <option value="2" <?=(isset($_POST['borrado']) && $_POST['borrado'] > 1)?'selected':''?>>Todos</option>
                </select>
            </form>
        </div>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">                    
            <table id="myTable1" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                        <th style="display: none;">ID</th>
                        <th>Nombre y apellidos</th>
                        <th>Usuario / Email</th>                        
                        <th>Centro</th>
                        <th>Perfil</th>                        
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php if (isset($registros)) { if ($registros != 0) { foreach ($registros as $key => $row) { ?>
                    <tr <?php if ($row['borrado']==1) { ?> style="background: #ddd; "<?php } ?>>
                        <td style="display: none;">
                            <?php echo $row['id_usuario'] ?>
                        </td>
                        <td <?php if ($row['borrado']==1) { ?> style="background: #ddd !important; "<?php } ?>>
                            <?php echo $row['nombre']." ".$row['apellidos'] ?>
                            <?php if ($row['borrado']==1) { ?>(borrado)<?php } ?>
                        </td>
                        <td>
                            <?php echo $row['email'] ?>
                        </td>                        
                        <td >                            
                            <?php echo $row['nombre_centro'] ?>
                        </td>
                        <td >                            
                            <?php echo $row['nombre_perfil'] ?>
                        </td>
                        <td >
                            <a class="btn btn-sm btn-icon btn-warning" href="<?php echo base_url(); ?>usuarios/gestion/editar/<?php echo $row['id_usuario'] ?>" data-bs-toggle="tooltip" title="Datos de usuario"><i class="fa-regular fa-pen-to-square"></i></a>
                            <a class="btn btn-sm btn-icon btn-primary" href="<?php echo base_url(); ?>usuarios/comisiones/<?php echo $row['id_usuario'] ?>"><i class="fas fa-percentage" data-bs-toggle="tooltip" title="Comisiones"></i></a>
                        </td>
                        <td >
                            <?php if ($row['id_usuario'] != 1 && $row['id_usuario'] != $this->session->userdata('nombre_usuario')) { ?>                            
                                <?php if ($row['borrado']==0) { ?>
                                    <button class="btn btn-sm btn-icon btn-danger" onclick="Borrar(<?php echo $row['id_usuario'] ?>);" data-bs-toggle="tooltip" title="Borrar usuario"><i class="fa-solid fa-trash"></i></button>
                                <?php } else { ?>
                                    <button class="btn btn-sm btn-icon btn-info" onclick="Recuperar(<?php echo $row['id_usuario'] ?>);" data-bs-toggle="tooltip" title="Recuperar usuario"><i class="fas fa-sync-alt"></i></button>
                                <?php } ?>                            
                            <?php } else { ?>
                            NO
                            <?php } ?>
                        </td>                        
                    </tr>
                    <?php } } } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    function Borrar(id_usuario) {
        if(confirm("¿DESEA MARCAR COMO BORRADO AL USUARIO?")) {
            document.location.href="<?php echo base_url();?>usuarios/gestion/borrar/"+id_usuario;
        }
        return false;             
    }    
    function Recuperar(id_usuario) {
        if(confirm("¿DESEA RECUPERAR AL USUARIO?")) {
            document.location.href="<?php echo base_url();?>usuarios/recuperar/"+id_usuario;
        }
        return false;             
    }
</script>