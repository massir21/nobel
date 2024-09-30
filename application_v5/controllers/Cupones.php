<?php class Cupones extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Cupones_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    public function index()
    {
        // ... Leemos todos los cupones de tienda existentes.
        // $data['cupones'] = $this->Cupones_model->get_cupones();
        // ... Viewer con el contenido
        $data['pagetitle'] = 'Gestión de cupones descuento';
        $data['actionstitle'] = ['<a href="' . base_url() . 'cupones/nuevo" class="btn btn-primary text-inverse-primary">Añadir cupón</a>'];
        $data['content_view'] = $this->load->view('cupones/index_view', $data, true);
        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos']            = $this->Usuarios_model->leer_modulos($param_modulos);

        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 23);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    public function get_cupones($table = null, $columna = null, $valor = null)
    {
        $this->load->library('Datatable');
        $campos = [
            'cupones.codigo_cupon',
            'cupones.descuento_euros',
            'cupones.descuento_porcentaje',
            'cupones.fecha_desde',

            '"" As validado',
            'cupones.id_cupon',
            'cupones.fecha_hasta',
        ];
        $tabla = 'cupones';
        $join = [];
        $add_rule = ['group_by' => 'cupones.id_cupon'];
        $where = ['cupones.borrado' => 0, 'fecha_desde !=' => '0000-00-00 00:00:00', 'fecha_hasta !=' => '0000-00-00 00:00:00'];

        if ($this->input->get('fecha_desde') != '') {
            $where['cupones.fecha_hasta >='] = $this->input->get('fecha_desde');
        }
        if ($this->input->get('fecha_hasta') != '') {
            $where['cupones.fecha_hasta <='] = $this->input->get('fecha_hasta');
        }

        if (($table != "") && ($columna != "") && ($valor != "")) {
            $where[$table . '.' . $columna] = $valor;
            $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $campos, $join, $where, $add_rule));
        } else {
            $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $campos, $join, $where, $add_rule));
        }
        foreach ($result->data as $key => $value) {
            $result->data[$key]->valido = $this->Cupones_model->comprobar_cupon($value->id_cupon);
        }
        $res = json_encode($result);
        echo $res;
    }

    public function fecha_hasta_check()
    {
        $fecha_desde = $this->input->post('fecha_desde');
        $fecha_hasta = $this->input->post('fecha_hasta');
        if (($fecha_hasta > $fecha_desde) || ($fecha_hasta == "")) {
            return true;
        } else {
            $this->form_validation->set_message('fecha_hasta_check', 'La fecha de vencimiento no puede ser anterior a la de inicio.');
            return false;
        }
    }

    public function nuevo()
    {
        //if($this->input->post() !== ''){

        $this->form_validation->set_rules('accion', 'Acción', 'required');
        $this->form_validation->set_rules('codigo_cupon', 'Código cupón', 'required|is_unique[cupones.codigo_cupon]');
        $this->form_validation->set_rules('fecha_desde', 'Fecha de inicio', 'required');
        $this->form_validation->set_rules('fecha_hasta', 'Fecha de vencimiento', 'callback_fecha_hasta_check');
        $this->form_validation->set_rules('descuento_euros', 'Descuento en euros', 'numeric');
        $this->form_validation->set_rules('descuento_porcentaje', 'Descuento en %', 'numeric');
        $this->form_validation->set_rules('comentario', 'Comentario', 'max_length[500]');
        $this->form_validation->set_rules('id_servicio', 'Familia');
        $this->form_validation->set_rules('id_familia_servicio', 'Servicio');

        if ($this->form_validation->run()) {

            $datos = [
                'codigo_cupon'            => $this->input->post('codigo_cupon'),
                'fecha_desde'             => $this->input->post('fecha_desde'),
                'fecha_hasta'             => $this->input->post('fecha_hasta'),
                'descuento_euros'         => $this->input->post('descuento_euros'),
                'descuento_porcentaje'    => $this->input->post('descuento_porcentaje'),
                'id_cliente'              => $this->input->post('id_cliente'),
                'cantidad_cliente'        => $this->input->post('cantidad_cliente'),
                'cantidad'                => $this->input->post('cantidad'),
                'id_familia_servicio'     => ($this->input->post('id_familia_servicio') != '') ? implode(',', $this->input->post('id_familia_servicio')) : '',
                'id_servicio'             => ($this->input->post('id_servicio') != '') ? implode(',', $this->input->post('id_servicio')) : '',
                'comentario'              => $this->input->post('comentario'),
                'fecha_modificacion'      => date("Y-m-d H:i:s"),
                'id_usuario_modificacion' => $this->session->userdata('id_usuario'),
                'borrado'                 => 0
            ];

            if ($this->Cupones_model->add_cupon($datos) != false) {
                $this->session->set_flashdata('mensaje', 'SE HA AÑADIDO UN NUEVO CUPÓN.');
                redirect('cupones');
            } else {
                $this->session->set_flashdata('mensaje', 'NO SE HA PODIDO AÑADIR UN NUEVO CUPÓN.');
                redirect('cupones');
            }
        } else {

            $data['accion']             = "nuevo";
            $data['familias_servicios'] = $this->Servicios_model->leer_familias_servicios('');
            $param = [];
            $data['clientes'] = $data['registros'] = $this->Clientes_model->leer_clientes($param);
            $data['pagetitle'] =  'Nuevo Cupón descuento';
            $data['content_view']       = $this->load->view('cupones/nuevo_view', $data, true);
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos']            = $this->Usuarios_model->leer_modulos($param_modulos);

            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 23);
            if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }
        // }
    }

    public function editar($id_cupon)
    {
        if ($this->input->post('accion') == 'editar') {
            $cupon = $this->Cupones_model->get_cupon($id_cupon);
            $this->form_validation->set_rules('accion', 'Acción', 'required');
            if ($cupon['codigo_cupon'] != $this->input->post('codigo_cupon')) {
                $this->form_validation->set_rules('codigo_cupon', 'Código cupón', 'required|is_unique[cupones.codigo_cupon]');
            }
            $this->form_validation->set_rules('fecha_desde', 'Fecha de inicio', 'required');
            $this->form_validation->set_rules('fecha_hasta', 'Fecha de vencimiento', 'callback_fecha_hasta_check');
            $this->form_validation->set_rules('comentario', 'Comentario', 'max_length[500]');
            $this->form_validation->set_rules('id_familia_servicio_tienda', 'Familia');
            $this->form_validation->set_rules('id_familia_servicio', 'Servicio');

            if ($this->form_validation->run()) {
                $datos = [
                    'codigo_cupon'            => $this->input->post('codigo_cupon'),
                    'fecha_desde'             => $this->input->post('fecha_desde'),
                    'fecha_hasta'             => $this->input->post('fecha_hasta'),
                    'descuento_euros'         => $this->input->post('descuento_euros'),
                    'descuento_porcentaje'    => $this->input->post('descuento_porcentaje'),
                    'id_cliente'              => $this->input->post('id_cliente'),
                    'cantidad_cliente'        => $this->input->post('cantidad_cliente'),
                    'cantidad'                => $this->input->post('cantidad'),
                    'id_familia_servicio'     => ($this->input->post('id_familia_servicio') != '') ? implode(',', $this->input->post('id_familia_servicio')) : '',
                    'id_servicio'             => ($this->input->post('id_servicio') != '') ? implode(',', $this->input->post('id_servicio')) : '',
                    'comentario'              => $this->input->post('comentario'),
                    'fecha_modificacion'      => date("Y-m-d H:i:s"),
                    'id_usuario_modificacion' => $this->session->userdata('id_usuario'),
                    'borrado'                 => 0
                ];

                if ($this->Cupones_model->update_cupon($datos, $cupon['id_cupon']) != false) {
                    $this->session->set_flashdata('mensaje', 'SE HA ACTUALIZADO EL CUPÓN.');
                    redirect('cupones');
                } else {
                    $this->session->set_flashdata('mensaje', 'NO SE HA PODIDO ACTUALIZAR EL CUPÓN.');
                    redirect('cupones');
                }
            } else {
                $data['cupon']                     = $this->Cupones_model->get_cupon($id_cupon);
                $data['accion']                    = "editar";
                $data['familias_servicios']        = $this->Servicios_model->leer_familias_servicios('');
                $parametros['id_familia_servicio'] = $data['cupon']['id_familia_servicio'];
                $data['servicios']                 = $this->Servicios_model->leer_servicios($parametros);
                $data['pagetitle'] =  'Editar Cupón descuento';
                $data['content_view']              = $this->load->view('cupones/editar_view', $data, true);
                // ... Modulos del usuario
                $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
                $data['modulos']            = $this->Usuarios_model->leer_modulos($param_modulos);
                $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 23);
                if ($permiso) {
                    $this->load->view($this->config->item('template_dir') . '/master', $data);
                } else {
                    header("Location: " . RUTA_WWW . "/errores/error_404.html");
                    exit;
                }
            }
        } else {
            $data['cupon']                     = $this->Cupones_model->get_cupon($id_cupon);
            $data['accion']                    = "editar";
            $data['familias_servicios']        = $this->Servicios_model->leer_familias_servicios('');
            $data['pagetitle'] =  'Editar Cupón descuento';
            $data['content_view']              = $this->load->view('cupones/editar_view', $data, true);
            // ... Modulos del usuario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos']            = $this->Usuarios_model->leer_modulos($param_modulos);
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 23);
            if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }
    }

    public function borrar_cupon()
    {
        $this->form_validation->set_rules('id_cupon', 'Cupón', 'required');
        if ($this->form_validation->run()) {
            $datos['fecha_borrado']      = date("Y-m-d H:i:s");
            $datos['id_usuario_borrado'] = $this->session->userdata('id_usuario');
            $datos['borrado']            = 1;

            if ($this->Cupones_model->update_cupon($datos, $this->input->post('id_cupon')) != false) {
                $this->session->set_flashdata('mensaje', 'SE HA ELIMINADO EL CUPÓN.');
                redirect('cupones');
            } else {
                $this->session->set_flashdata('mensaje', 'NO SE HA PODIDO ELIMINAR EL CUPÓN.');
                redirect('cupones');
            }
        } else {
            $this->session->set_flashdata('mensaje', 'HA OCURRIDO UN ERROR CON LA ACCIÓN INDICADA.');
            redirect('cupones');
        }
    }

    public function uso()
    {

        if (($this->input->post('fecha_desde') != '') && ($this->input->post('fecha_hasta') != '')) {
            $fecha_desde = date('Y-m-d', strtotime($this->input->post('fecha_desde'))) . " 00:00:00";
            $fecha_hasta = date('Y-m-d', strtotime($this->input->post('fecha_hasta'))) . " 23:59:59";
        } else {
            $fecha_desde = date('Y-m-d') . " 00:00:00";
            $fecha_hasta = date('Y-m-d ') . " 23:59:59";
        }
        if (($this->input->post('id_centro') == '') || ($this->input->post('id_centro') == 'todos')) {
            $id_centro = 'todos';
        } else {
            $id_centro = $this->input->post('id_centro');
        }

        $parametros['vacio'] = "";
        $centros = $this->Intercentros_model->leer_centros_nombre($parametros);
        $data['centros'] = $centros;
        //var_dump($id_centro);exit;
        unset($parametros);
        $parametros['fecha_desde'] = $fecha_desde;
        $parametros['fecha_hasta'] = $fecha_hasta;
        $parametros['id_centro'] = $id_centro;
        $data['cupones_usados'] = $this->Cupones_model->cupones_usados_entre_fechas($parametros);

        $data['fecha_desde'] = $fecha_desde;
        $data['fecha_hasta'] = $fecha_hasta;
        $data['id_centro'] = $id_centro;
        // ... Viewer con el contenido
        $data['pagetitle'] = 'Cupones descuento usados';
        $data['content_view'] = $this->load->view('cupones/uso_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos']            = $this->Usuarios_model->leer_modulos($param_modulos);

        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 23);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    public function search_clientes()
    {
        if (($this->input->get('nombre') != '') || ($this->input->get('apellidos') != '')) {
            $nombre_completo = '%';
            if ($this->input->get('nombre') != '') {
                $nombre_completo .= $this->input->get('nombre') . '%';
            }
            if ($this->input->get('apellidos') != '') {
                $nombre_completo .= $this->input->get('apellidos') . '%';
            }

            $clientes = $this->Cupones_model->search_cliente($nombre_completo);
            //echo '<pre>'; print_r($clientes);exit;
            if ($clientes->num_rows() > 0) {
                $option = "<option>selecciona el cliente</option>";
                foreach ($clientes->result() as $key => $value) {
                    $option .= "<option value='$value->id_cliente'>$value->nombre $value->apellidos</option>";
                }
                echo $option;
            } else {
                echo 'NO';
            }
        } else {

            echo '<option>Cliente no encontrado</option>';
        }
    }

    public function search_clientes_json()
    {
        $clientes = $this->Cupones_model->search_cliente('%' . $_GET['search'] . '%');
        if ($clientes->num_rows() > 0) {
            foreach ($clientes->result() as $key => $value) {
                $json[] = ['id' => $value->id_cliente, 'text' => $value->nombre_completo];
            }
        } else {
            $json[] = ['id' => 0, 'text' => $this->db->last_query()];
        }
        echo json_encode($json);
    }


    //31/03/20 Cupones

    public function comprobar_cupon()
    {

        if (isset($_POST['codigo_cupon']) and isset($_POST['id_cliente'])) {
            $codigo_cupon = $_POST['codigo_cupon'];
            $id_cliente = $_POST['id_cliente'];
        } else {
            echo "Sin Valor";
            return 1;
        }

        //$this->load->model('Cupones_model');
        $descuento_porcentaje = 0;
        $descuento_euros = 0;
        $cupon = $this->Cupones_model->get_cupon_codigo($codigo_cupon);
        if ($cupon) {
            //Pedido
            //$pedido = $this->Cupones_model->leer_pedido_codigo($codigo_pedido);

            // veces que ha sido usado
            //$usos= $this->Cupones_clientes_model->usos_de_cupon($cupon['id_cupon'], $pedido[0]['id_cliente']);
            $usos = $this->Cupones_model->usos_de_cupon($cupon['id_cupon'], $id_cliente);
            //$servicios_en_pedido = $this->Cupones_clientes_model->servicios_en_pedido($pedido[0]['id_pedido']);

            //Determinar si el cupón tiene famlia o servicios asignados
            $tipo_cupon = "general";
            //if ($cupon['id_familia_servicio']!="" OR (!is_null($cupon['id_familia_servicio'])) ){
            if ($cupon['id_familia_servicio'] != "") {
                $tipo_cupon = "familia";
                //Obtener todos los servicios de X familia del cupón
                unset($param);
                $param['id_familia_servicio'] = $cupon['id_familia_servicio'];
                $datos['familias'] = $this->Servicios_model->leer_servicios($param);
                $cuantos_servicios_en_familia = count($datos['familias']);

                if ($datos['familias'] != 0) {
                    for ($j = 0; $j < count($datos['familias']); $j++) {
                        $servicios_en_familia[$j] = $datos['familias'][$j]['id_servicio'];
                    }
                }
            }

            //if ($cupon['id_servicio']!="" OR (!is_null($cupon['id_servicio'])) ){
            if ($cupon['id_servicio'] != "") {
                $tipo_cupon = "servicio";
            }


            //01/04/20
            $c = 0;
            $xservicios = "";
            $asignar = "";
            $xmarcados = count($_POST['servicios']);
            $con = 0;
            for ($i = 0; $i < $xmarcados; $i++) {
                $xservicios .= $_POST['servicios'][$i];
                $xarray[$i] = $_POST['servicios'][$i];
                if ($tipo_cupon == "general") //Todos los servicos de la vista reciben cupón d edescuento
                    $xelementos[$i] = $_POST['servicios'][$i];

                if ($tipo_cupon == "familia") {
                    $buscar = $xarray[$i];
                    //Buscar qué servicio de la familia del cupón coincide con los servicios de la vista
                    for ($h = 0; $h < $cuantos_servicios_en_familia; $h++) {
                        if ($buscar == $servicios_en_familia[$h]) {
                            $xfamilias[$con] = $buscar;
                            $con++;
                        }
                    }
                }
            }





            //Solo si el cupón no es GENERAL (tiene servicios o familia)
            if ($tipo_cupon != "general") {
                unset($xelementos);
                //Determinar si el cupón tiene famlia o servicios asignados
                if ($tipo_cupon == "servicio") {
                    $cuantos_servicios_coinciden = count(array_intersect(explode(',', $cupon['id_servicio']), $xarray));

                    if ($cuantos_servicios_coinciden == 1) {
                        $buscar = "nada";
                        $buscar = $cupon['id_servicio'];
                        //$resultSearch = array_search($buscar, $xarray);
                        $resultSearch = 0;
                        for ($i = 0; $i < $xmarcados; $i++) {
                            if ($xarray[$i] == $buscar) {
                                $resultSearch = 1;
                                $xindice = $i;
                            }
                        }
                        if ($resultSearch == 1) {
                            $sw = 1;
                            $asignar = 1;
                            $xelementos[0] = $xarray[$xindice];
                        } else {
                            $sw = 0;
                        }
                    }
                    if ($cuantos_servicios_coinciden > 1) {
                        $asignar = $cuantos_servicios_coinciden;
                        $xelementos = array_intersect(explode(',', $cupon['id_servicio']), $xarray);
                        $sw = 1;
                    }
                } // Busca por familia
                if ($tipo_cupon == "familia") {
                    $xelementos = $xfamilias;
                    //$xelementos=array_intersect($servicios_en_familia, $xarray);
                    //$xelementos=$servicios_en_familia;
                    //$asignar=$con;
                    $asignar = count($xelementos);
                } //Fin de Familia 
            } //Cupón Gereral 
            else {
                $asignar = $xmarcados;
            }

            /*
             foreach ($_POST['marcados'] as $row) {
                        if ($row != "") {
                          $xmarcados++;  
                        }
                        $c++;
                    }
                    */
            //Fin



            if ($cupon['fecha_desde'] >= date('Y-m-d H:i:s')) {
                // la fecha de inicio es posterior a la actual
                $respuesta = "Este cupón aun no es válido.";
            } elseif (($cupon['fecha_hasta'] != '0000-00-00 00:00:00') && ($cupon['fecha_hasta'] < date('Y-m-d H:i:s'))) {
                // La fecha de vencimiento ha pasado
                $respuesta = "Este cupón ha vencido.";
            } elseif (($cupon['id_cliente'] != 0) && ($cupon['id_cliente'] != $id_cliente)) {
                // el cupon está limitado a un cliente y no es del pedido
                $respuesta = "Este cupón no te ha sido asignado.";
            }
            /*
          elseif ($sw==0) {
              // el cupon está limitado a un cliente y no es del pedido
              $respuesta = "Este cupón no corresponde a estos servicios. ".$cuantos_servicios_coinciden;
          } 
          
          */ elseif (($cupon['cantidad'] > 0) && ($usos['cantidad_total'] > $cupon['cantidad'])) {
                // si existe limite de usos u se supera
                $respuesta = "Se ha superado el limite total de uso de éste cupón";
            } elseif (($cupon['cantidad_cliente'] > 0) && ($usos['cantidad_cliente'] > $cupon['cantidad_cliente'])) {
                // si existe limite de usos por cliente y se supera
                $respuesta = "Se ha superado el limite de uso por cliente de éste cupón.";
            } elseif (isset($cuantos_servicios_en_familia) && $cuantos_servicios_en_familia > 0 and $con == 0) {
                $respuesta = "El cupón no puede aplicarse sobre la familia de los servicios reservados." . $cuantos_servicios_en_familia . ' Tipo Cupon ' . $tipo_cupon;
            }
            /*
          elseif (($cupon['id_familia_servicio'] != '') && ((count(array_intersect(explode(',',$cupon['id_familia_servicio']), $servicios_en_pedido['id_familia'])) == 0))) {
              // si existe familia de servicio pero no está entre las del pedido
              $respuesta = "El cupón no puede aplicarse sobre la familia de los servicios reservados.";
          } elseif (($cupon['id_servicio'] != '') && ((count(array_intersect(explode(',',$cupon['id_servicio']), $servicios_en_pedido['id_servicio'])) == 0))) {
              // si existe el id de servicio pero no está entre lss del pedido
              $respuesta = "El cupón no puede aplicarse sobre los servicios indicados.";
          } elseif(($cupon['id_cupon'] == 0) || ($cupon['id_cupon'] == 145)){
            
              $respuesta = $this->cuponcumpleannos($pedido[0]['id_cliente']);
              
          } 
          */ else {

                $respuesta = 1;
                $descuento_euros = $cupon['descuento_euros'];
                $descuento_porcentaje = $cupon['descuento_porcentaje'];
            }
        } else {
            $respuesta = "El cupón no existe.";
        }
        //echo $respuesta;
        //json_encode($arrayPHP);
        //.' Cuantos: '.$cuantos_servicios_coinciden,
        $return = [
            'descuento_euros' => $descuento_euros,
            'descuento_porcentaje' => $descuento_porcentaje,
            'asignar' => $asignar,
            'elementos' => $xelementos,
            'respuesta' => $respuesta
        ];

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    //Fin

    //04/04/20
    public function aplicar_cupon()
    {
        if (isset($_POST['codigo_cupon']) and isset($_POST['id_cliente'])) {
            $codigo_cupon = $_POST['codigo_cupon'];
            $id_cliente = $_POST['id_cliente'];
            $descuento = $_POST['descuento'];
            $descuento_euros = $_POST['descuento_euros'];
            $descuento_porcentaje = $_POST['descuento_porcentaje'];
            $id_pedido = $id_cliente;
            $cupon = $this->Cupones_model->get_cupon_codigo($codigo_cupon);
            $id_cupon = $cupon['id_cupon'];
        } else {
            echo "Sin Valor";
            return 1;
        }

        $data   = [
            'id_cupon'             => $id_cupon,
            'id_cliente'           => $id_cliente,
            'id_pedido'            => $id_pedido,
            'descuento'            => $descuento,
            'descuento_euros'      => $descuento_euros,
            'descuento_porcentaje' => $descuento_porcentaje,
            'fecha_creacion'       => date('Y-m-d H:i:s'),
            'borrado'              => 0,
        ];
        $anadido = $this->Cupones_model->add_cupon_usadoII($data);

        $return = [
            'id_cupon' => $codigo_cupon,
            'descuento' => $descuento,
        ];
        header('Content-Type: application/json');
        echo json_encode($return);
    }

    //Fin 



    /*
    public function instalar()
    {

      echo '<!DOCTYPE html>
      <html lang="es">
          <head>
              <meta charset="utf-8" />
          </head>
          <body>';
      if (!$this->db->table_exists('cupones') )
      {
        $create_cupones = "CREATE TABLE `cupones` (
            `id_cupon` int(11) NOT NULL AUTO_INCREMENT,
            `codigo_cupon` varchar(50) DEFAULT NULL,
            `fecha_desde` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `fecha_hasta` timestamp NULL DEFAULT NULL,
            `descuento_euros` decimal(10,2) NOT NULL DEFAULT 0.00,
            `descuento_porcentaje` decimal(10,2) NOT NULL DEFAULT 0.00,
            `comentario` varchar(500) DEFAULT NULL,
            `id_familia_servicio` TINYTEXT NULL DEFAULT NULL,
            `id_servicio` TINYTEXT NULL DEFAULT NULL,
            `cantidad` int(11) DEFAULT NULL,
            `cantidad_cliente` int(11) DEFAULT NULL,
            `id_cliente` int(11) DEFAULT NULL,
            `id_usuario_creador` int(11) DEFAULT NULL,
            `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `id_usuario_modificacion` int(11) DEFAULT NULL,
            `fecha_modificacion` timestamp NULL DEFAULT NULL,
            `borrado` int(11) DEFAULT NULL,
            `fecha_borrado` timestamp NULL DEFAULT NULL,
            `id_usuario_borrado` int(11) DEFAULT NULL,
            PRIMARY KEY (`id_cupon`),
            KEY `borrado` (`borrado`)
            ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";
        $this->db->query($create_cupones);
        echo '<h2>Tabla -Cupones- creada</h2>';
      }

      if (!$this->db->table_exists('cupones_usados') )
      {
        $create_cupones_usados = "CREATE TABLE `cupones_usados` (
            `id_cupon_usado` int(11) NOT NULL AUTO_INCREMENT,
            `id_cupon` int(11) NOT NULL,
            `id_cliente` int(11) NOT NULL,
            `id_pedido` int(11) NOT NULL,
            `descuento` decimal(10,2) NOT NULL DEFAULT 0.00,
            `descuento_euros` decimal(10,2) NOT NULL DEFAULT 0.00,
            `descuento_porcentaje` decimal(10,2) NOT NULL DEFAULT 0.00,
            `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `estado` BOOLEAN NOT NULL DEFAULT FALSE,
            `id_usuario_modificacion` int(11) DEFAULT NULL,
            `fecha_modificacion` timestamp NULL DEFAULT NULL,
            `borrado` int(11) DEFAULT NULL,
            `fecha_borrado` timestamp NULL DEFAULT NULL,
            `id_usuario_borrado` int(11) DEFAULT NULL,
            PRIMARY KEY (`id_cupon_usado`),
            KEY `borrado` (`borrado`)
            ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";
        $this->db->query($create_cupones_usados);
        echo '<h2>Tabla -cupones_usados- creada</h2>';
      }

      /// añade nuevos campos de descuento a citas temporales
      if (!$this->db->field_exists('descuento_euros', 'citas_temporales'))
      {
        $add_descuentos_temporales = "ALTER TABLE `citas_temporales` ADD `descuento_euros` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `pvp`, ADD `descuento_porcentaje` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `descuento_euros`;";
        $this->db->query($add_descuentos_temporales);
        echo '<h2>Campos -descuento_euros- y -descuentos_porcentaje- de la tabla -citas_temporales- creados</h2>';
      }

      // añade nueva pestaña Cupones al modulo
      $this->db->where('nombre_modulo', 'Cupones');
      $cupones = $this->db->get('modulos')->num_rows();
      if ($cupones == 0) {
        // se busca el id más alto de la tabla modulos para sumarle 1
        $this->db->select('id_modulo');
        $this->db->order_by('id_modulo', 'desc');
        $this->db->limit(1);
        $last_id = $this->db->get('modulos')->row()->id_modulo + 1;

        $data = [
            'id_modulo'               => $last_id,
            'nombre_modulo'           => 'Cupones',
            'url'                     => 'cupones',
            'padre'                   => 'Master',
            'orden'                   => 6,
            'orden_item'              => 0,
            'id_usuario_creacion'     => 0,
            'fecha_creacion'          => date('Y-m-d H:i:s'),
            'id_usuario_modificacion' => 0,
            'fecha_modificacion'      => date('Y-m-d H:i:s'),
            'borrado'                 => 0,
        ];
        $this->db->insert('modulos', $data);

        // Se añade al perfil master
        $data = [
            'id_modulo'               => $last_id,
            'id_perfil'               => 3,
            'id_usuario_creacion'     => 1,
            'fecha_creacion'          => date('Y-m-d H:i:s'),
            'id_usuario_modificacion' => 1,
            'fecha_modificacion'      => date('Y-m-d H:i:s'),
            'borrado'                 => 0,
        ];
        $this->db->insert('modulos_perfiles', $data);
        echo '<h2>Añadido pestaña -Cupones- al menu Master</h2>';
      }


      // añade nueva pestaña del modulo cupones a estadísticas
      $this->db->where('nombre_modulo', 'Cupones: Uso');
      $cupones_uso = $this->db->get('modulos')->num_rows();
      if ($cupones_uso == 0)
      {
        // se busca el id más alto de la tabla modulos para sumarle 1
        $this->db->select('id_modulo');
        $this->db->order_by('id_modulo', 'desc');
        $this->db->limit(1);
        $last_id = $this->db->get('modulos')->row()->id_modulo + 1;

        $data = [
            'id_modulo'               => $last_id,
            'nombre_modulo'           => 'Cupones: Uso',
            'url'                     => 'cupones/uso',
            'padre'                   => 'Estadísticas',
            'orden'                   => 12,
            'orden_item'              => 0,
            'id_usuario_creacion'     => 0,
            'fecha_creacion'          => date('Y-m-d H:i:s'),
            'id_usuario_modificacion' => 0,
            'fecha_modificacion'      => date('Y-m-d H:i:s'),
            'borrado'                 => 0,
        ];
        $this->db->insert('modulos', $data);
        $data = [
            'id_modulo'               => $last_id,
            'id_perfil'               => 3,
            'id_usuario_creacion'     => 1,
            'fecha_creacion'          => date('Y-m-d H:i:s'),
            'id_usuario_modificacion' => 1,
            'fecha_modificacion'      => date('Y-m-d H:i:s'),
            'borrado'                 => 0,
        ];
        $this->db->insert('modulos_perfiles', $data);
        echo '<h2>Añadido pestaña -Cupones: Uso- al menu Estadísticas</h2>';
      }
      echo '</body></html>';
    }
    */
}
