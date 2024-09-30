<style>            
    .dataTables_filter {
        text-align: right;        
    }
</style>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.10.0/js/lightbox.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.10.0/css/lightbox.css" rel="stylesheet"/>
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->                        
    <!-- BEGIN PAGE BAR -->
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>              
              <a href="<?php echo base_url();?>site" style="font-size: 20px;">Panel de Control</a>
              <i class="fa fa-angle-right"></i>                
            </li>
            <li>              
              <span style="font-size: 20px;"><strong>Citas Avisos</strong></span>              
            </li>
        </ul>
        <?php if ($this->session->userdata('id_perfil') == 3) { ?>
        <div style="float: right; margin-right: 10px;">
            <b>Centro Filtrado: </b>
            <select name="id_centro" id="id_centro" class="form-control form-control-solid" onchange="NuevoDiaFiltroCentro();" style="width: 145px;">
                <option value="99">Todos</option>
                <?php if (isset($centros_todos)) { if ($centros_todos != 0) { foreach ($centros_todos as $key => $row) { if ($row['id_centro'] > 1) { ?>
                    <option value='<?php echo $row['id_centro']; ?>' <?php if (isset($id_centro)) { if ($row['id_centro']==$id_centro) { echo "selected"; } } ?>>
                        <?php echo $row['nombre_centro']; ?>
                    </option>
                <?php }}}} ?>
            </select>            
        </div>
        <?php } 
         else { //23/03/20
            ?>
            <input type="hidden" name="id_centro" id="id_centro" value="<?php echo $this->session->userdata('id_centro_usuario'); ?>" />
            <?php
         }
        ?>
    </div>
    <!-- END PAGE BAR -->
    <!-- END PAGE HEADER-->
<div id="content">
  <div style="float: right; margin-right: 10px;">
         <b>Estado: </b>
         <select name="otroestado" id="idotroestado" class="form-control form-control-solid" onchange="otroestado();" style="width: 145px;">
         <option value="0" <?php if($accion==0){ echo "selected"; } ?> >Pendientes</option>
         <option value="1" <?php if($accion==1){ echo "selected"; } ?> >Enviados</option>
         <option value="3" <?php if($accion==3){ echo "selected"; } ?> >Ambos</option> 
         </select>
   </div> 
</div>
<div class="row ">
<div class="col-md-12">
  <!-- BEGIN SAMPLE FORM PORTLET-->
  <div class="portlet light bordered">
    <div class="card-body pt-6">
        <div class="table-responsive">              
        <table id="tablaCitasAvisos"class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                <thead class="">
                    <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
            <th style="display: none;"></th>
            <th>Fecha</th>
            <th>Centro</th>
            <th>Cliente</th>
            <th>Mensaje</th>            
            <th>Estado</th>
          </tr>
        </thead>
        <tbody class="text-gray-700 fw-semibold">
          <?php if (isset($registros)) { if ($registros != 0) { $i=0; foreach ($registros as $key => $row) {
            if ($row['enviado']==0){
                $color="background: #ffffff; font-weight: bold;";   
               $estado="Pendiente";
           }else
            { 
               $color="background: #f2f2f2; text-decoration:line-through;";
               $estado="Enviado";
            }
            ?>
           <tr id="fila<?php echo $i; ?>" style=" <?php echo $color; ?>">
           <td style="display: none;">                
                <?php echo $row['fecha_creacion']; ?>                
            </td>
            <td>                
                <?php echo $row['fecha_creacion']; ?>                
            </td>
            <td>                
                <?php echo $row['centro']; ?>                
            </td>
            <td>                
                <span onclick="laapi('<?php echo $i; ?>')" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Clic Whatsapp"><?php echo $row['cliente']; ?></span> 
                <span><?php echo $row['telefono']; ?></span>               
            </td>
            <td>                
               <span id="id<?php echo $i; ?>" onclick="copiarAlPortapapeles('<?php echo $i; ?>')" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Clic Copiar"><?php echo $row['mensaje']; ?> </span>                
            </td>
            <td>                
               <span id="estado<?php echo $i; ?>" onclick="cambiar('<?php echo $i; ?>','<?php echo $row["id_aviso"]; ?>')" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"  title="Clic Cambiar"><?php echo $estado; ?></span>            
            </td>
            <input type="hidden" id="valorestado<?php echo $i; ?>" value="<?php echo $row['enviado']; ?>" />
            <input type="hidden" id="idtelefono<?php echo $i; ?>" value="<?php echo $row['telefono']; ?>" />
            <input type="hidden" id="idmensaje<?php echo $i; ?>" value="<?php echo $row['mensaje']; ?>" />
          </tr>
          <?php $i++; } } } ?>          
        </tbody>
      </table>
    </div>
  </div>
  <!-- END SAMPLE FORM PORTLET-->
</div>
</div>
</div>
<!-- END CONTENT BODY -->
<script>
function copiarAlPortapapeles(id_elemento) {
  id="id"+id_elemento;
  var aux = document.createElement("input");
  aux.setAttribute("value", document.getElementById(id).innerHTML);
  document.body.appendChild(aux);
  aux.select();
  document.execCommand("copy");
  document.body.removeChild(aux);
}
function cambiar(i,id_aviso) {
        idfila=document.getElementById('fila'+i);
        idestado=document.getElementById('estado'+i);
        idvalorestado=document.getElementById('valorestado'+i);
        estado=idvalorestado.value;
        //document.location.href="<?php echo base_url();?>agenda/cambiar_aviso/"+id_aviso+"/"+estado;
        $.get('<?php echo base_url();?>agenda/cambiar_aviso/'+id_aviso+"/"+estado, function(data, status){
                                  //alert("Data: " + data + "\nStatus: " + status);
                                  console.log('Cambio estado');
                                });
        if (estado==0){
             color="background: #f2f2f2; text-decoration:line-through;";
             idfila.style=color;
             idestado.innerHTML="Enviado";
             idvalorestado.value="1"
        }else{
            color="background: #ffffff; font-weight: bold;";
            idfila.style=color;
             idestado.innerHTML="Pendiente";
             idvalorestado.value="0"
        }
}
function laapi(i){
  telefono='34'+document.getElementById('idtelefono'+i).value;
  mensaje=document.getElementById('idmensaje'+i).value;
  document.location.href="https://api.whatsapp.com/send?phone="+telefono+"&text="+mensaje, "_blank";    
}
function NuevoDiaFiltroCentro() {
        valor=document.getElementById('idotroestado').value;
        document.location.href="<?php echo base_url();?>agenda/leer_avisos_citas/"+document.getElementById("id_centro").value+"/"+valor;
    }
function otroestado() {
    valor=document.getElementById('idotroestado').value;
        document.location.href="<?php echo base_url();?>agenda/leer_avisos_citas/"+document.getElementById("id_centro").value+"/"+valor;
    }
</script>