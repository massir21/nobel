<?php
class Usuarios extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function add_datos_fiscales()
    {
        //$query = "ALTER TABLE `usuarios` ADD `nif` VARCHAR(20) NOT NULL AFTER `telefono`, ADD `domicilio` VARCHAR(150) NOT NULL AFTER `nif`, ADD `provincia` VARCHAR(150) NOT NULL AFTER `domicilio`, ADD `n_colegiado` VARCHAR(150) NOT NULL AFTER `provincia`;";

        $query = "ALTER TABLE `usuarios` ADD `empresa` VARCHAR(150) NOT NULL AFTER `n_colegiado`, ADD `cif` VARCHAR(20) NOT NULL AFTER `empresa`";

        $this->db->query($query);
        /*$query = "CREATE TABLE `CCAA` (
            `id_CCAA` tinyint(4) unsigned NOT NULL,
            `Nombre` varchar(100) NOT NULL,
            PRIMARY KEY (`id_CCAA`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Lista de Comunicades Autónomas';";
        $this->db->query($query);
        $query = "INSERT INTO `CCAA` (`id_CCAA`, `Nombre`)
        VALUES
            (1,'Andalucía'),
            (2,'Aragón'),
            (3,'Asturias, Principado de'),
            (4,'Balears, Illes'),
            (5,'Canarias'),
            (6,'Cantabria'),
            (7,'Castilla y León'),
            (8,'Castilla - La Mancha'),
            (9,'Catalunya'),
            (10,'Comunitat Valenciana'),
            (11,'Extremadura'),
            (12,'Galicia'),
            (13,'Madrid, Comunidad de'),
            (14,'Murcia, Región de'),
            (15,'Navarra, Comunidad Foral de'),
            (16,'País Vasco'),
            (17,'Rioja, La'),
            (18,'Ceuta'),
            (19,'Melilla');";
        $this->db->query($query);

        $query = "CREATE TABLE `provincias` (
            `id_provincia` smallint(6) unsigned NOT NULL,
            `id_CCAA`      tinyint(4) unsigned NOT NULL,
            `provincia`   varchar(30) DEFAULT NULL,
            PRIMARY KEY (`id_provincia`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Lista de Provincias';";
        $this->db->query($query);

        $query = "INSERT INTO `provincias` (`id_provincia`, `id_CCAA`, `provincia`)
        VALUES
            (2, 8, 'Albacete'),
            (3, 10, 'Alicante/Alacant'),
            (4, 1, 'Almería'),
            (1, 16, 'Araba/Álava'),
            (33, 3, 'Asturias'),
            (5, 7, 'Ávila'),
            (6, 11, 'Badajoz'),
            (7, 4, 'Balears, Illes'),
            (8, 9, 'Barcelona'),
            (48, 16, 'Bizkaia'),
            (9, 7, 'Burgos'),
            (10, 11, 'Cáceres'),
            (11, 1, 'Cádiz'),
            (39, 6, 'Cantabria'),
            (12, 10, 'Castellón/Castelló'),
            (51, 18, 'Ceuta'),
            (13, 8, 'Ciudad Real'),
            (14, 1, 'Córdoba'),
            (15, 12, 'Coruña, A'),
            (16, 8, 'Cuenca'),
            (20, 16, 'Gipuzkoa'),
            (17, 9, 'Girona'),
            (18, 1, 'Granada'),
            (19, 8, 'Guadalajara'),
            (21, 1, 'Huelva'),
            (22, 2, 'Huesca'),
            (23, 1, 'Jaén'),
            (24, 7, 'León'),
            (27, 12, 'Lugo'),
            (25, 9, 'Lleida'),
            (28, 13, 'Madrid'),
            (29, 1, 'Málaga'),
            (52, 19, 'Melilla'),
            (30, 14, 'Murcia'),
            (31, 15, 'Navarra'),
            (32, 12, 'Ourense'),
            (34, 7, 'Palencia'),
            (35, 5, 'Palmas, Las'),
            (36, 12, 'Pontevedra'),
            (26, 17, 'Rioja, La'),
            (37, 7,  'Salamanca'),
            (38, 5, 'Santa Cruz de Tenerife'),
            (40, 7, 'Segovia'),
            (41, 1, 'Sevilla'),
            (42, 7, 'Soria'),
            (43, 9, 'Tarragona'),
            (44, 2, 'Teruel'),
            (45, 8, 'Toledo'),
            (46, 10, 'Valencia/València'),
            (47, 7, 'Valladolid'),
            (49, 7, 'Zamora'),
            (50, 2, 'Zaragoza');";
        $this->db->query($query);
        */
    }

    /*public function add_comisiones_usuarios()
    {
        $query = "CREATE TABLE `usuarios_comisiones` (
            `id_comision` INT(11) NOT NULL AUTO_INCREMENT,
            `id_usuario` INT(11) NOT NULL,
            `item` ENUM('producto','servicio') NOT NULL,
            `id_familia_item` INT NOT NULL DEFAULT '0' ,
            `id_item` INT NOT NULL DEFAULT '0' ,
            `tipo` ENUM('fijo','porcentaje') NOT NULL ,
            `comision` DECIMAL(6,2) NOT NULL DEFAULT '0' ,
            `id_usuario_creacion` INT(11) NULL ,
            `fecha_creacion` TIMESTAMP NULL ,
            `id_usuario_modificacion` INT(11) NULL ,
            `fecha_modificacion` TIMESTAMP NULL ,
            `borrado` TINYINT(1) NULL ,
            `id_usuario_borrado` INT(11) NULL ,
            `fecha_borrado` TIMESTAMP NULL ,
            `debug` INT(1) NOT NULL DEFAULT '0' ,
            PRIMARY KEY (`id_comision`),
            INDEX (`id_usuario`)
        ) ENGINE = InnoDB;";
        $this->db->query($query);
    }*/

    /*public function alter_comisiones()
    {
        $q = "ALTER TABLE `usuarios_comisiones` ADD `importe_desde` DECIMAL(6,2) NOT NULL AFTER `comision`, ADD `importe_hasta` DECIMAL(6,2) NOT NULL AFTER `importe_desde`;";
        $this->db->query($q);
        $q = "ALTER TABLE `usuarios_comisiones` CHANGE `tipo` `tipo` ENUM('fijo','porcentaje','tramo') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;";
        $this->db->query($q);
    }*/

    // ----------------------------------------------------------------------------- //
    // ... USUARIOS
    // ----------------------------------------------------------------------------- //
    function index()
    {
        // ... Comprobamos la sesion del usuario
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['vacio'] = "";

        unset($parametros);
        $parametros['vacio'] = "";
        if(isset($_POST['borrado'])){
            if($_POST['borrado'] < 2){
                $parametros['borrado'] = $_POST['borrado'];
            }
        }else{
            $parametros['borrado'] = 0;
        }
        $data['registros'] = $this->Usuarios_model->leer_usuarios($parametros);

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Gestión de Usuarios';
        $data['actionstitle'] = ['<a href="' . base_url() . 'usuarios/gestion/nuevo" class="btn btn-primary text-inverse-primary">Añadir usuario</a>'];
        $data['content_view'] = $this->load->view('usuarios/usuarios_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 1);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    function gestion($accion = null, $id_usuario = null)
    {
        // ... Comprobamos la sesion del usuario
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Recogemos la accion a realizar
        $data['accion'] = $accion;

        // ----------------------------------------------------------------------------- //
        // ... Nuevo Registro o Edición ...
        // ----------------------------------------------------------------------------- //
        if ($accion == "nuevo" || $accion == "editar") {
            if ($accion == "editar") {
                $param['id_usuario'] = $id_usuario;
                $data['registros'] = $this->Usuarios_model->leer_usuarios($param);
            }

            unset($param);
            $param['vacio'] = "";
            $data['centros'] = $this->Usuarios_model->leer_centros($param);
            $data['perfiles'] = $this->Usuarios_model->leer_perfiles($param);
            $data['provincias'] = $this->Usuarios_model->leer_provincias($param);

            // ... Viewer con el contenido
            $data['pagetitle'] = ($accion == 'editar') ? 'Editar registro' : 'Nuevo registro';
            $data['content_view'] = $this->load->view('usuarios/usuarios_nuevoeditar_view', $data, true);

            // ... Modulos del usuario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 1);
            if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }

        // ----------------------------------------------------------------------------- //    
        // ... Guardar o Actualizar ...
        // ----------------------------------------------------------------------------- //
        if ($accion == "guardar" || $accion == "actualizar") {
            //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;

            if ($accion == "guardar") {
                $data['estado'] = $this->Usuarios_model->nuevo_usuario($parametros);
            } else {
                if ($id_usuario > 0) {
                    $parametros['id_usuario'] = $id_usuario;
                    $data['estado'] = $this->Usuarios_model->actualizar_usuario($parametros);
                }
            }
        }

        // ----------------------------------------------------------------------------- //
        // ... Borrar ... 
        // ----------------------------------------------------------------------------- //
        if ($accion == "borrar") {
            $parametros['id_usuario'] = $id_usuario;
            $data['borrado'] = $this->Usuarios_model->borrar_usuario($parametros);
        }

        // ----------------------------------------------------------------------------- //
        // ... Principal ... 
        // ----------------------------------------------------------------------------- //
        if ($accion == "" || $accion == "guardar" || $accion == "actualizar" || $accion == "borrar") {
            unset($parametros);
            $parametros['vacio'] = "";
            $data['registros'] = $this->Usuarios_model->leer_usuarios($parametros);

            // ... Viewer con el contenido
            $data['pagetitle'] = 'Gestión de Usuarios';
            $data['actionstitle'] = ['<a href="' . base_url() . 'usuarios/gestion/nuevo" class="btn btn-primary text-inverse-primary">Añadir usuario</a>'];
            $data['content_view'] = $this->load->view('usuarios/usuarios_view', $data, true);

            // ... Modulos del usuario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 1);
            if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }
    }

    function comisiones($id_usuario)
    {
        // ... Comprobamos la sesion del usuario
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $param['id_usuario'] = $id_usuario;
        $data['usuario'] = $this->Usuarios_model->leer_usuarios($param);
        $data['registros'] = $this->Usuarios_model->leer_comisiones($param);

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Comisiones de ' . $data['usuario'][0]['nombre'] . ' ' . $data['usuario'][0]['apellidos'];
        $data['actionstitle'] = ['<button type="button" class="btn btn-primary text-inverse-primary" data-bs-toggle="modal" data-bs-target="#add_comision_modal" title="Añadir comisión">Añadir comisión</a>'];
        $data['content_view'] = $this->load->view('usuarios/usuarios_comisiones_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 1);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    public function get_comisiones_user($table = null, $columna = null, $valor = null)
	{
		$this->load->library('Datatable');
		$campos = [
            'usuarios_comisiones.id_comision',
            'usuarios_comisiones.item',
            '(CASE 
                WHEN usuarios_comisiones.item = "producto" THEN productos_familias.nombre_familia
                WHEN usuarios_comisiones.item = "servicio" THEN servicios_familias.nombre_familia
                ELSE NULL
            END )AS nombre_familia',
            '(CASE 
                WHEN usuarios_comisiones.item = "producto" THEN productos.nombre_producto
                WHEN usuarios_comisiones.item = "servicio" THEN servicios.nombre_servicio
                ELSE NULL
            END )AS nombre_item',
            'usuarios_comisiones.tipo',
            'usuarios_comisiones.comision',
            'usuarios_comisiones.importe_desde',
            'usuarios_comisiones.importe_hasta',
            'usuarios_comisiones.id_item',
            'usuarios_comisiones.id_familia_item',
		];
		$tabla = 'usuarios_comisiones';
		$join = [
            'productos_familias' => 'usuarios_comisiones.item = "producto" AND usuarios_comisiones.id_familia_item = productos_familias.id_familia_producto',
            'productos' => 'usuarios_comisiones.item = "producto" AND usuarios_comisiones.id_item = productos.id_producto',
            'servicios_familias' => 'usuarios_comisiones.item = "servicio" AND usuarios_comisiones.id_familia_item = servicios_familias.id_familia_servicio',
            'servicios' => 'usuarios_comisiones.item = "servicio" AND usuarios_comisiones.id_item = servicios.id_servicio'
        ];
		$add_rule = [];
		$where = ['usuarios_comisiones.borrado' => 0];
		
        if ($this->input->get('id_usuario') != ''){
            $where['usuarios_comisiones.id_usuario'] = $this->input->get('id_usuario');
        }
        if ($this->input->get('tipo') != ''){
            $where['usuarios_comisiones.tipo'] = $this->input->get('tipo');
        }
        if ($this->input->get('item') != ''){
            $where['usuarios_comisiones.item'] = $this->input->get('item');
        }

		if (($table != "") && ($columna != "") && ($valor != "")) {
			$where[$table . '.' . $columna] = $valor;
			$result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $campos, $join, $where, $add_rule));
		} else {
			$result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $campos, $join, $where, $add_rule));
		}
		$res = json_encode($result);
		echo $res;
	}

    function load_familias()
    {
        $tipoitem = $this->input->post('tipoitem');
        if($tipoitem == 'producto'){
            $familias = $this->Productos_model->leer_familias_productos([]);
        }else{
            $familias = $this->Servicios_model->leer_familias_servicios([]);
        }
        echo json_encode($familias);
    }

    function load_items_familias()
    {
        $tipoitem = $this->input->post('tipoitem');
        $id_familia = $this->input->post('familia');
        if($tipoitem == 'producto'){
            $param['id_familia_producto'] = $id_familia;
            $items = $this->Productos_model->leer_productos($param);
        }else{
            $param['id_familia_servicio'] = $id_familia;
            $items = $this->Servicios_model->leer_servicios($param);
        }
        echo json_encode($items);
    }
    
    function manage_comision()
    {
        // ... Comprobamos la sesion del usuario
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $this->load->library('form_validation');
        if($this->input->post('accion') != 'delete'){
            $this->form_validation->set_rules('item', 'Comisión sobre', 'required');
            $this->form_validation->set_rules('id_familia_item', 'Familia');
            $this->form_validation->set_rules('id_item', 'Artículo', 'trim');
            $this->form_validation->set_rules('tipo', 'Tipo de comisión', 'required');
            $this->form_validation->set_rules('comision', 'Comision', 'required|is_numeric');
            $this->form_validation->set_rules('id_usuario', 'Usuario', 'required');
            $this->form_validation->set_rules('accion', 'Tipo de acción', 'required');
            if($this->input->post('accion') == 'edit'){
                $this->form_validation->set_rules('id_comision', 'ID comisión', 'required');
            }
        }else{
            $this->form_validation->set_rules('id_comision', 'ID comisión', 'required');
        }
        if ($this->form_validation->run() == false) {
            $response = [
                'error' => 1,
                'msn' => validation_errors()
            ];
            echo json_encode($response);
            exit();
        }
        $parametros = $_POST;
        // todo ok, valorar la acción
        if($this->input->post('accion') == 'edit' || $this->input->post('accion') == 'delete'){
            $estado = $this->Usuarios_model->actualizar_comision($parametros);
        }else{
            $estado = $this->Usuarios_model->nueva_comision($parametros);
        }
        if(isset($estado) && $estado == 1){
            $response = [
                'error' => 0,
                'msn' => 'Acción realizada'
            ];
            echo json_encode($response);
            exit();
        }else{
            $response = [
                'error' => 1,
                'msn' => 'Acción no realizada'
            ];
            echo json_encode($response);
            exit();
        }
        
       
    }


    // ----------------------------------------------------------------------------- //
    // ... PERFILES
    // ----------------------------------------------------------------------------- //
    function perfiles($accion = null, $id_perfil = null)
    {
        // ... Comprobamos la sesion del perfil
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Recogemos la accion a realizar
        $data['accion'] = $accion;

        // ----------------------------------------------------------------------------- //
        // ... Nuevo Registro o Edición ...
        // ----------------------------------------------------------------------------- //
        if ($accion == "nuevo" || $accion == "editar") {
            if ($accion == "editar") {
                $param['id_perfil'] = $id_perfil;
                $data['registros'] = $this->Usuarios_model->leer_perfiles($param);
            }

            unset($param);
            $param['vacio'] = "";
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param);

            unset($param);
            $param['id_perfil'] = $id_perfil;
            $data['modulos_perfil'] = $this->Usuarios_model->leer_modulos($param);

            // ... Viewer con el contenido
            $data['pagetitle'] = ($accion == 'editar') ? 'Editar registro' : 'Nuevo registro';
            $data['content_view'] = $this->load->view('usuarios/perfiles_nuevoeditar_view', $data, true);

            // ... Modulos del usuario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master      
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 2);
            if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }

        // ----------------------------------------------------------------------------- //    
        // ... Guardar o Actualizar ...
        // ----------------------------------------------------------------------------- //
        if ($accion == "guardar" || $accion == "actualizar") {
            //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;

            $parametros['id_perfil'] = $id_perfil;
            $data['estado'] = $this->Usuarios_model->actualizar_perfil($parametros);
        }

        // ----------------------------------------------------------------------------- //
        // ... Borrar ... 
        // ----------------------------------------------------------------------------- //
        if ($accion == "borrar") {
            $parametros['id_perfil'] = $id_perfil;
            $data['borrado'] = $this->Usuarios_model->borrar_perfil($parametros);
        }

        // ----------------------------------------------------------------------------- //
        // ... Principal ... 
        // ----------------------------------------------------------------------------- //
        if ($accion == "" || $accion == "guardar" || $accion == "actualizar" || $accion == "borrar") {
            unset($parametros);
            $parametros['vacio'] = "";
            $data['registros'] = $this->Usuarios_model->leer_perfiles($parametros);

            unset($param);
            $param['id_perfil'] = $id_perfil;
            $data['modulos_perfil'] = $this->Usuarios_model->leer_modulos($param);

            // ... Viewer con el contenido
            $data['pagetitle'] = 'Gestión de Perfiles';
            $data['actionstitle'] = ['<a href="' . base_url() . 'usuarios/perfiles/nuevo" class="btn btn-primary text-inverse-primary">Añadir perfil</a>'];
            $data['content_view'] = $this->load->view('usuarios/perfiles_view', $data, true);

            // ... Modulos del usuario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 2);
            if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }
    }

    // ----------------------------------------------------------------------------- //
    // ... CENTROS
    // ----------------------------------------------------------------------------- //
    function centros($accion = null, $id_centro = null)
    {
        // ... Comprobamos la sesion del centro
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Recogemos la accion a realizar
        $data['accion'] = $accion;

        // ----------------------------------------------------------------------------- //
        // ... Nuevo Registro o Edición ...
        // ----------------------------------------------------------------------------- //
        if ($accion == "nuevo" || $accion == "editar") {
            if ($accion == "editar") {
                $param['id_centro'] = $id_centro;
                $data['registros'] = $this->Usuarios_model->leer_centros($param);
            }

            // ... Leemos el saldo actual de la caja
            unset($param);
            $param['fecha'] = date("Y-m-d");
            $param['id_centro'] = $id_centro;
            $data['saldo_actual_efectivo'] = $this->Caja_model->caja_saldo_actual_efectivo($param);
            $data['saldo_actual_tarjeta'] = $this->Caja_model->caja_saldo_actual_tarjeta($param);

            // ... Viewer con el contenido
            $data['pagetitle'] = ($accion == 'editar') ? 'Editar registro' : 'Nuevo registro';
            $data['content_view'] = $this->load->view('usuarios/centros_nuevoeditar_view', $data, true);

            // ... Modulos del usuario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 3);
            if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }

        // ----------------------------------------------------------------------------- //    
        // ... Guardar o Actualizar ...
        // ----------------------------------------------------------------------------- //
        if ($accion == "guardar" || $accion == "actualizar") {
            //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;

            if ($accion == "guardar") {
                $data['estado'] = $this->Usuarios_model->nuevo_centro($parametros);
            } else {
                $parametros['id_centro'] = $id_centro;
                $data['estado'] = $this->Usuarios_model->actualizar_centro($parametros);
            }
        }

        // ----------------------------------------------------------------------------- //
        // ... Borrar ... 
        // ----------------------------------------------------------------------------- //
        if ($accion == "borrar") {
            $parametros['id_centro'] = $id_centro;
            $data['borrado'] = $this->Usuarios_model->borrar_centro($parametros);
        }

        // ----------------------------------------------------------------------------- //
        // ... Principal ... 
        // ----------------------------------------------------------------------------- //
        if ($accion == "" || $accion == "guardar" || $accion == "actualizar" || $accion == "borrar") {
            unset($parametros);
            $parametros['vacio'] = "";
            $data['registros'] = $this->Usuarios_model->leer_centros($parametros);

            // ... Viewer con el contenido
            $data['pagetitle'] = 'Gestion de centros';
            $data['actionstitle'] = ['<a href="' . base_url() . 'usuarios/centros/nuevo" class="btn btn-primary text-inverse-primary">Añadir centro</a>'];
            $data['content_view'] = $this->load->view('usuarios/centros_view', $data, true);

            // ... Modulos del usuario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 3);
            if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }
    }

    // ----------------------------------------------------------------------------- //
    // ... 
    // ----------------------------------------------------------------------------- //
    function recuperar($id_usuario = null)
    {
        // ... Comprobamos la sesion del centro
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $parametros['id_usuario'] = $id_usuario;
        $data['borrado'] = $this->Usuarios_model->recuperar_usuario($parametros);

        header("Location: " . RUTA_WWW . "/usuarios");
        exit;
    }
}
