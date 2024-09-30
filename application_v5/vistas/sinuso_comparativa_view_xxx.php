<style>            
    .dataTables_filter {
        text-align: right;        
    }
</style>
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
              <span style="font-size: 20px;"><strong>Comparativa de datos...</strong></span>              
            </li>
        </ul>                        
    </div>
    <!-- END PAGE BAR -->
    <!-- END PAGE HEADER-->
<div class="row ">
<div class="col-md-12">
  <!-- BEGIN SAMPLE FORM PORTLET-->
  <div class="portlet light bordered">
    <div class="portlet-title">      
      <div style="text-align: center;">
        <form id="form_intercentros" action="<?php echo base_url();?>comparativa" role="form" method="post" name="form_intercentros" class="form-horizontal">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <legend>Intervalo 1</legend>
                        <label class="sr-only" for="exampleInputAmount">Fecha desde 1</label>
                        <div class="input-group">
                            <input type="date" class="form-control form-control-solid" id="fecha_desde_1" name="fecha_desde_1" placeholder="desde" value="<?php echo $fecha_desde;?>">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        </div>
                        <label class="sr-only" for="exampleInputAmount">Fecha hasta 1</label>
                        <div class="input-group">
                            <input type="date" class="form-control form-control-solid" id="fecha_hasta_1" name="fecha_hasta_1" placeholder="hasta" value="<?php echo $fecha_hasta;?>">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <legend class="hidden-xs">&nbsp;</legend>
                        <input type="submit" value="Comparar" class="btn btn-primary text-inverse-primary" />
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <legend>Intervalo 2</legend>
                        <label class="sr-only" for="exampleInputAmount">Fecha desde 2</label>
                        <div class="input-group">
                            <input type="date" class="form-control form-control-solid" id="fecha_desde_2" name="fecha_desde_2" placeholder="desde" value="<?php if(isset($fecha_desde_2)){echo $fecha_desde_2;}?>">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        </div>
                        <label class="sr-only" for="exampleInputAmount">Fecha hasta 2</label>
                        <div class="input-group">
                            <input type="date" class="form-control form-control-solid" id="fecha_hasta_2" name="fecha_hasta_2" placeholder="hasta" value="<?php if(isset($fecha_hasta_2)){echo $fecha_hasta_2;}?>">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--<b>Fecha desde:</b>
            <input type="date" id="fecha" name="fecha_desde" value="<?php if (isset($fecha_desde)) { echo $fecha_desde; } ?>" />
            <b>hasta</b>
            <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?php if (isset($fecha_hasta)) { echo $fecha_hasta; } ?>" />
            <?php if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 4) { ?>
            &nbsp; <b>Centro:</b>
            <select name="id_centro">                
                <?php if (isset($centros)) { if ($centros != 0) { foreach ($centros as $key => $row) { ?>
                    <option value='<?php echo $row['id_centro']; ?>' <?php if (isset($id_centro)) { if ($row['id_centro']==$id_centro) { echo "selected"; } } ?>>
                        <?php echo $row['nombre_centro']; ?>
                    </option>
                <?php }}} ?>                    
            </select>
            <?php } ?>
            <input type="submit" value="Filtrar" class="btn btn-primary text-inverse-primary" />  
            -->          
        </form>
      </div>
    </div>
    <div class="card-body pt-6">
        <div class="table-responsive">        
         <table id="comparativa" class="table table-striped table-hover table-bordered text-center">
            <thead>
                <tr>
                    <th>#..</th>
                    <?php foreach ($centros as $key => $value) {?>
                        <th style="text-align: center;"><?php echo $value['nombre_centro'];?></th>
                    <?php }?>
                </tr>
            </thead>
            <tbody class="text-gray-700 fw-semibold">
                <?php if(isset($tr_rows)){
                    $i = 0;
                    foreach ($tr_rows as $key => $value) {?>
                        <?php if(isset($fecha_desde_2)){?>
                            <tr>
                                <td style="text-align: left;" rowspan="3"><strong><?php echo $key;?></strong></td>
                                <?php foreach ($value as $k => $dato) {?>
                                    <td><?php echo $dato[0];?></td>
                                <?php }?>
                            </tr>
                            <tr>
                                <?php foreach ($value as $k => $dato) {?>
                                    <td style="color: red"><?php echo $dato[1];?></td>
                                <?php }?>
                            </tr>
                            <tr>
                                <?php foreach ($value as $k => $dato) {?>
                                    <td>
                                        <?php if(($dato[2][0] == '-') && (isset($dato[2][1])) && ($dato[2] != '- | -')){
                                            echo '<span class="badge" style="background-color:red; color:#FFF;font-weight:bold;">'.$dato[2].'</span>';
                                        }elseif((isset($dato[2][1])) && ($dato[2] != '- | -')){
                                            echo '<span class="badge" style="background-color:#0cef0c; color:#FFF;font-weight:bold;">'.$dato[2].'</span>';
                                        }else{echo '';}
                                        ?>
                                    </td>
                                <?php }?>
                            </tr>
                            <tr><td colspan="<?php echo count($centros)+1;?>"></td></tr>
                        <?php }else{?>
                            <tr>
                                <td style="text-align: left;"><strong><?php echo $key;?></strong></td>
                                <?php foreach ($value as $k => $dato) {?>
                                    <td><?php echo $dato[0];?></td>
                                <?php }?>
                            </tr>
                        <?php }?>
                <?php } }?>
            </tbody>
         </table>
    </div>
  </div>
  <!-- END SAMPLE FORM PORTLET-->
</div>
</div>
</div>
<!-- END CONTENT BODY -->