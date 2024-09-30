                        <!-- BEGIN SIDEBAR -->
                        <div class="page-sidebar-wrapper">
                                <!-- BEGIN SIDEBAR -->
                                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                                <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                                <div class="page-sidebar navbar-collapse collapse">
                                        <!-- BEGIN SIDEBAR MENU -->
                                        <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                                        <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                                        <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                                        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                                        <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                                        <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                                        <ul class="page-sidebar-menu    page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                                                <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                                                <li class="sidebar-toggler-wrapper hide">
                                                        <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                                                        <div class="sidebar-toggler"> </div>
                                                        <!-- END SIDEBAR TOGGLER BUTTON -->
                                                </li>
                                                <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
                                                <li class="sidebar-search-wrapper">
                                                        <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
                                                        <!-- DOC: Apply "sidebar-search-bordered" class the below search form to have bordered search box -->
                                                        <!-- DOC: Apply "sidebar-search-bordered sidebar-search-solid" class the below search form to have bordered & solid search box -->
                                                        <!--<form class="sidebar-search    " action="page_general_search_3.html" method="POST">
                                                                <a href="javascript:;" class="remove">
                                                                        <i class="icon-close"></i>
                                                                </a>
                                                                <div class="input-group">
                                                                        <input type="text" class="form-control form-control-solid" placeholder="Search...">
                                                                        <span class="input-group-btn btn-warning text-inverse-warning">
                                                                                <a href="javascript:;" class="btn submit">
                                                                                        <i class="icon-magnifier"></i>
                                                                                </a>
                                                                        </span>
                                                                </div>
                                                        </form>-->
							<br><br>
                                                        <!-- END RESPONSIVE QUICK SEARCH FORM -->
                                                </li>
                                                <?php
                                                if ($this->session->userdata('id_perfil')==6){
                                                        ?>
                                                        <li class="nav-item">
                                                         <a href="<?php echo base_url();?><?php echo 'agenda/prueba_doctor'; ?>" class="nav-link ">     
                                                         <span class="title"><?php echo 'Agenda' ?></span> </a> </li>
                                                         <li class="nav-item">
                                                         <a href="<?php echo base_url();?><?php echo 'clientes'; ?>" class="nav-link ">     
                                                         <span class="title"><?php echo 'Clientes' ?></span> </a> </li>
                                                 <?php     
                                                }
                                                else{
                                                    if (isset($modulos)) {
                                                        // ... Verificamos que modulos padre deben estar activos,
                                                        // porque al meno hay algun hijo.
                                                        if ($modulos != 0) {
                                                                $padres = array("Agenda", "Carnets Templos", "Clientes", "Dietario","Empleados","Estadísticas","Productos","Master","Tareas");
                                                                foreach ($padres as $p) { $menu[$p]=""; }
                                                                foreach ($modulos as $key => $row) {
                                                                        foreach ($padres as $p) {
                                                                                if ($row['padre']==$p) {
                                                                                        $menu[$p]=$p;
                                                                                }
                                                                        }
                                                                }
                                                        }
                                                }
                                                ?>
                                                <?php foreach ($padres as $p) { ?>
                                                        <?php if ($menu[$p] == $p) { ?>
                                                                <li class="nav-item">
                                                                        <a href="#" class="nav-link nav-toggle">
                                                                                <i class="icon-wallet"></i>
                                                                                <span class="title"><?php echo $p; ?></span>
                                                                                <span class="arrow"></span>
                                                                        </a>
                                                                        <ul class="sub-menu">
                                                                        <?php if (isset($modulos)) { 
                                                                                if ($modulos != 0) {
                                                                                        //$no_modulos = ['Comparativa', 'Cajas Regalo', 'Carnets', 'Empleados', 'Recepcionistas', 'Pago Intercentros','Productos','Servicios','Venta Carnets','Histórico citas','Modificación de carnets','Exportar CSV','Duplicados CSV','Facturacion'];
                                                                                        $no_modulos = ['Comparativa', 'Cajas Regalo', 'Carnets', 'Empleados', 'Recepcionistas', 'Pago Intercentros','Productos','Servicios','Venta Carnets','Modificación de carnets','Exportar CSV','Duplicados CSV','Facturacion'];
                                                                                        foreach ($modulos as $key => $row) {
                                                                                                if(!in_array($row['nombre_modulo'], $no_modulos)){
                                                                                                if ($row['padre']==$menu[$p]) {?>
                                                                                                        <?php if($row['id_modulo']==43) { ?>
                                                                                                                <?php if($this->session->userdata('id_centro_usuario')==6 || $this->session->userdata('id_centro_usuario')==1) { ?>
                                                                                                                <li class="nav-item">
                                                                                                                        <a href="<?php echo base_url();?><?php echo $row['url']; ?>" class="nav-link ">
                                                                                                                                > <span class="title"><?php echo $row['nombre_modulo'] ?></span>
                                                                                                                        </a>
                                                                                                                </li>
                                                                                                                <?php } ?>
                                                                                                        <?php }
                                                                                                        elseif (
                                                                                                                (
                                                                                                                        ($row['nombre_modulo']!='Centros') AND ($row['nombre_modulo']!='Servicios')
                                                                                                                ) OR (
                                                                                                                        ($row['nombre_modulo']=='Centros') AND ($row['padre'] != 'Estadísticas')
                                                                                                                ) OR (
                                                                                                                        ($row['nombre_modulo']=='Servicios') AND ($row['padre'] != 'Estadísticas')
                                                                                                                ) OR (
                                                                                                                        ($row['nombre_modulo']=='Extra') AND (
                                                                                                                                ($this->session->userdata('id_perfil') == 3) OR 
                                                                                                                                ($this->session->userdata('id_perfil') == 0)
                                                                                                                        )
                                                                                                                )
                                                                                                        ){ ?>
                                                                                                                        <li class="nav-item">
                                                                                                                                <a href="<?php echo base_url();?><?php echo $row['url']; ?>" class="nav-link ">
                                                                                                                                        > <span class="title"><?php echo $row['nombre_modulo']; ?></span>
                                                                                                                                </a>
                                                                                                                        </li>
                                                                                                        <?php } 
                                                                                                        ?>
                                                                                                <?php } 
                                                                                                }
                                                                                        } 
                                                                                } 
                                                                        } ?>
                                                                        </ul>
                                                                </li>
                                                        <?php } ?>
                                                <?php } 
                                                 } //else principal
                                                ?>
                                        </ul>
                                        <!-- END SIDEBAR MENU -->
                                        <!-- END SIDEBAR MENU -->
                                </div>
                                <!-- END SIDEBAR -->
                        </div>
                        <!-- END SIDEBAR -->