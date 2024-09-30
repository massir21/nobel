<?php
class Pedidos extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    // ----------------------------------------------------------------------------- //
    // ... PEDIDOS
    // ----------------------------------------------------------------------------- //
    function index()
    {
        // ... Comprobamos la sesion del pedido
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['vacio'] = "";

        unset($parametros);
        $parametros['vacio'] = "";
        // ... controlamos que el perfil sea el master, sino solo mostramos lo del centro que
        // corresponda.
        if ($this->session->userdata('id_perfil') > 0) {
            $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
        } else {
            $parametros['master'] = 1;
        }

        if ($this->input->post('id_centro') != "") {
            $parametros['id_centro'] = $this->input->post('id_centro');
            $data['id_centro'] = $this->input->post('id_centro');
        }
        if ($this->input->post('estado') != "") {
            $parametros['estado'] = $this->input->post('estado');
            $data['estado'] = $this->input->post('estado');
        }

        //$data['registros'] = $this->Pedidos_model->leer_pedidos($parametros);

        unset($parametros);
        $parametros['vacio'] = "";
        $data['centros'] = $this->Usuarios_model->leer_centros($parametros);

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Gestión de Pedidos';
        if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 4) {
                $url = base_url().'pedidos/exportar/';
                $url .= (isset($id_centro)) ? $id_centro.'/': '/';
                $url .= (isset($estado)) ? $estado: '';
                $data['actionstitle'][] = '<a href="'.$url.'" class="btn btn-warning text-inverse-warning">Exportar CSV</a>';
                $data['actionstitle'][] = '<a href="'.base_url().'pedidos/gestion/nuevo" class="btn btn-primary text-inverse-primary">Realizar Pedido</a>';
        }else{
            $data['actionstitle'][] = '<a href="'.base_url().'pedidos/gestion/nuevo" class="btn btn-primary text-inverse-primary">Realizar Pedido</a>';
        }
        $data['content_view'] = $this->load->view('pedidos/pedidos_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 10);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    public function get_pedidos($table = null, $columna = null, $valor = null)
	{
		$this->load->library('Datatable');
		$campos = [
            'pedidos.id_pedido',
            'centros.nombre_centro',
            'pedidos.fecha_pedido',
            'pedidos.fecha_entrega',
            'pedidos.total_factura',
            'pedidos.estado',
            "DATE_FORMAT(pedidos.fecha_pedido,'%d-%m-%Y') as fecha_pedido_ddmmaaaa",
            "DATE_FORMAT(pedidos.fecha_pedido,'%H:%i') as hora_pedido",
            "DATE_FORMAT(pedidos.fecha_entrega,'%d-%m-%Y') as fecha_entrega_ddmmaaaa",
            "DATE_FORMAT(pedidos.fecha_entrega,'%H:%i') as hora_entrega"
		];
		$tabla = 'pedidos';
		$join = [
            'usuarios' => 'usuarios.id_usuario = pedidos.id_usuario',
            'centros' => 'centros.id_centro = pedidos.id_centro'
        ];
		$add_rule = ['group_by' => 'pedidos.id_pedido'];
		$where = ['pedidos.borrado' => 0, 'pedidos.estado !=' => 'PENDALTA'];
	
		if ($this->input->get('id_centro') != '') {
			$where['pedidos.id_centro'] = $this->input->get('id_centro');
		}
        if ($this->input->get('estado') != '') {
			$where['pedidos.estado'] = $this->input->get('estado');
		}
        if ($this->input->get('fecha_desde') != ''){
            $where['pedidos.fecha_pedido >='] = $this->input->get('fecha_desde');
        }
        if ($this->input->get('fecha_hasta') != ''){
            $where['pedidos.fecha_pedido <='] = $this->input->get('fecha_hasta');
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


    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //  
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    function gestion($accion = null, $id_pedido = null, $id_producto_pedido = null)
    {

        // ... Comprobamos la sesion del pedido
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Recogemos la accion a realizar
        $data['accion'] = $accion;

        // ----------------------------------------------------------------------------- //
        // ... Nuevo Registro o Edici�n ...
        // ----------------------------------------------------------------------------- //
        if (
            $accion == "nuevo" || $accion == "editar" || $accion == "editar_pedido"
            || $accion == "anadir_producto" || $accion == "borrar_producto_pedido"
        ) {
            $data['id_pedido'] = $id_pedido;

            $data['productos_json'] = 1;

            // ----------------------------------------------------------------------------- //
            // ... Borrar ... 
            // ----------------------------------------------------------------------------- //
            if ($accion == "borrar_producto_pedido") {
                $parametros['id_producto_pedido'] = $id_producto_pedido;
                $data['borrado'] = $this->Pedidos_model->borrar_producto_pedido($parametros);
                $accion = "editar_pedido";
            }

            // ... En caso de haber elegido un nuevo pedido creamos uno nuevo.
            if ($accion == "nuevo") {
                unset($param);
                $param['vacio'] = "";
                $data['id_pedido'] = $this->Pedidos_model->nuevo_pedido($param);
            }

            // ... En caso de editar el pedido ya sea por el master o el encargado para modificarlo
            // antes del envio.
            if ($accion == "editar" || $accion == "editar_pedido") {
                $param['id_pedido'] = $id_pedido;
                $data['registros'] = $this->Pedidos_model->leer_pedidos($param);
            }

            // ... Cuando se a�ade un producto desde la pantalla del encargado.
            if ($accion == "anadir_producto") {
                //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
                $parametros = $_POST;

                $parametros['id_pedido'] = $id_pedido;
                $ok = $this->Pedidos_model->anadir_producto($parametros);
            }

            // ... Siempre leemos todas las familias de productos y productos, con el javascript
            // correspondiente en los desplegables.
            unset($param);
            $param['vacio'] = "";
            $data['familias_productos'] = $this->Productos_model->leer_familias_productos($param);

            unset($param);
            $param['vacio'] = "";
            $data['productos'] = $this->Productos_model->leer_productos($param);

            //unset($param);
            //$param['id_producto']="";
            //$param['form']="form";
            //$data['script_productos'] = $this->Productos_model->javacript_familias_productos($param);

            // ... Leemos los productos asociados al pedido
            unset($param);
            if ($id_pedido == null) {
                $id_pedido = 0;
            }
            $param['id_pedido'] = $id_pedido;
            $data['productos_pedido'] = $this->Pedidos_model->leer_productos_pedido($param);

            // ----------------------------------------------------------------------------- //    
            // ... Viewer con el contenido
            // ----------------------------------------------------------------------------- //    
            if ($accion == "editar") {
                $data['pagetitle'] = 'Editar pedido';
                $data['content_view'] = $this->load->view('pedidos/pedidos_editar_view', $data, true);
            } else {
                $data['pagetitle'] = 'Editar pedido';
                $data['content_view'] = $this->load->view('pedidos/pedidos_realizar_view', $data, true);
            }

            // ----------------------------------------------------------------------------- //    
            // ... Modulos del pedido
            // ----------------------------------------------------------------------------- //    
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 10);
            if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
            // ----------------------------------------------------------------------------- //      
        }

        // ----------------------------------------------------------------------------- //
        // ... Guardar pedido antes de enviar  ... 
        // ----------------------------------------------------------------------------- //
        if ($accion == "guardar") {
            unset($parametros);
            //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;

            unset($param);
            $param['id'] = $parametros['id'];
            $param['cantidad'] = $parametros['cantidad'];
            $ok = $this->Pedidos_model->actualizar_pedido_productos_sinenviar($param);

            unset($parametros);
            $parametros['id_pedido'] = $id_pedido;
            $parametros['estado'] = "Sin Enviar";
            $ok = $this->Pedidos_model->activar_pedido($parametros);

            $data['estado'] = 3;
        }

        // ----------------------------------------------------------------------------- //    
        // ... Actualizar pedido ...
        // ----------------------------------------------------------------------------- //
        if ($accion == "actualizar_pedido") {
            //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;

            unset($param);
            $param['id_pedido'] = $id_pedido;
            $pedido = $this->Pedidos_model->leer_pedidos($param);

            // ... Actualizamos el estado del pedido.
            unset($param);
            $param['id_pedido'] = $id_pedido;
            $param['estado'] = $parametros['estado'];
            $ok = $this->Pedidos_model->actualizar_pedido($param);

            // ... Actualizamos los stocks del pedido.
            if ($parametros['estado'] == "Sin Terminar" || $parametros['estado'] == "Entregado") {
                unset($param);
                $param['id_pedido'] = $id_pedido;
                $param['id_productos'] = $parametros['id_productos'];
                $param['id_centro'] = $pedido[0]['id_centro'];
                $param['cantidad'] = $parametros['cantidad_entregada'];

                $ok = $this->Pedidos_model->actualizar_stock_productos($param);

                unset($param);
                $param['id'] = $parametros['id'];
                $param['cantidad_entregada'] = $parametros['cantidad_entregada'];
                $ok = $this->Pedidos_model->actualizar_pedido_productos($param);
            }

            $data['estado'] = 2;
        }

        // ----------------------------------------------------------------------------- //
        // ... Borrar ... 
        // ----------------------------------------------------------------------------- //
        if ($accion == "borrar") {
            $parametros['id_pedido'] = $id_pedido;
            $data['borrado'] = $this->Pedidos_model->borrar_pedido($parametros);
        }

        // ----------------------------------------------------------------------------- //
        // ... Enviar ... 
        // ----------------------------------------------------------------------------- //
        if ($accion == "enviar") {
            unset($parametros);
            //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;

            unset($param);
            if(isset($parametros['id']) && isset($parametros['cantidad'])){
                $param['id'] = $parametros['id'];
                $param['cantidad'] = $parametros['cantidad'];
                $ok = $this->Pedidos_model->actualizar_pedido_productos_sinenviar($param);
            }

            unset($parametros);
            $parametros['id_pedido'] = $id_pedido;
            $parametros['estado'] = "Sin Entregar";
            $ok = $this->Pedidos_model->activar_pedido($parametros);

            $pedido = $this->Pedidos_model->leer_pedidos($parametros);
            $pedido_productos = $this->Pedidos_model->leer_productos_pedido($parametros);

            $mensaje = "";

            $mensaje .= "
      <div>
      
      <p>
      Se ha realizado el siguiente pedido desde el centro: " . $pedido[0]['nombre_centro'] . "
      <br><br>Para gestionarlo accede al panel de control.
      </p>
      
      <table>
        <thead>
          <tr>            
            <th>Nombre Familia</th>                        
            <th>Nombre Producto</th>
            <th>PVP</th>
            <th>Precio franquiciado sin IVA</th>
            <th>Stock</th>
            <th>Pedidos</th>
          </tr>
        </thead>
        <tbody>";

            if (isset($pedido_productos)) {
                if ($pedido_productos != 0) {
                    foreach ($pedido_productos as $key => $row) {
                        $mensaje .= "        
          <tr>            
            <td style='text-align: center;'>" .
                            $row['nombre_familia'] . "
            </td>                        
            <td style='text-align: center;'>" .
                            $row['nombre_producto'] . "
            </td>
            <td style='text-align: center;'>" .
                            $row['pvp'] . "
            </td>
            <td style='text-align: center;'>" .
                            $row['precio_franquiciado_sin_iva'] . "
            </td>
            <td style='text-align: center;'>" .
                            $row['cantidad_stock'] . "
            </td>
            <td style='text-align: center;'>" .
                            $row['cantidad'] . "
            </td>                        
          </tr>          
        ";
                    }
                }
            }

            $mensaje .= "        
        </tbody>
      </table>
      </div>  
      ";

            if ($id_pedido > 0) {
                $this->Utiles_model->enviar_email("pedidos@templodelmasaje.com", "pedidos@templodelmasaje.com", "Nuevo Pedido " . $pedido[0]['nombre_centro'], $mensaje);

                $data['estado'] = 1;
            }
        }

        // ----------------------------------------------------------------------------- //
        // ... Principal ... 
        // ----------------------------------------------------------------------------- //
        if ($accion == "" || $accion == "guardar" || $accion == "actualizar_pedido" || $accion == "borrar" || $accion == "enviar") {
            unset($parametros);
            $parametros['vacio'] = "";
            // ... controlamos que el perfil sea el master, sino solo mostramos lo del centro que
            // corresponda.
            if ($this->session->userdata('id_perfil') > 0) {
                $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
            } else {
                $parametros['master'] = 1;
            }
            $data['registros'] = $this->Pedidos_model->leer_pedidos($parametros);

            // ... Viewer con el contenido
            $data['pagetitle'] = 'Gestión de Productos';
            if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 4) {
                    $url = base_url().'pedidos/exportar/';
                    $url .= (isset($id_centro)) ? $id_centro.'/': '/';
                    $url .= (isset($estado)) ? $estado: '';
                    $data['actionstitle'][] = '<a href="'.$url.'" class="btn btn-warning text-inverse-warning">Exportar CSV</a>';
            }else{
                $data['actionstitle'][] = '<a href="'.base_url().'pedidos/gestion/nuevo" class="btn btn-warning text-inverse-warning">Exportar CSV</a>';
            }
            $data['content_view'] = $this->load->view('pedidos/pedidos_view', $data, true);

            // ... Modulos del pedido
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 10);
            if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }
    }

    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //  
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    function exportar($id_centro = null, $estado = null)
    {
        // ... Comprobamos la sesion del usuario
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($parametros);
        $parametros['vacio'] = "";

        // ... Leemos todos los registros
        unset($parametros);
        $parametros['pendalta_no'] = 1;

        if ($id_centro != "") {
            $parametros['id_centro'] = $id_centro;
        }
        if ($estado != "") {
            $parametros['estado'] = str_replace("%20", " ", $estado);
        }

        $registros = $this->Pedidos_model->leer_productos_pedido($parametros);

        $fichero = RUTA_SERVIDOR . "/recursos/pedidos.csv";

        $file = fopen($fichero, "w");

        $linea = "numero_pedido;nombre_centro;fecha_pedido;fecha_entrega;estado;nombre_familia;nombre_producto;pvp;precio_franquiciado_sin_iva;cantidad_stock;cantidad;cantidad_entregada;cantidad_pendiente;total_factura\n";
        fwrite($file, $linea);

        if ($registros > 0) {
            foreach ($registros as $row) {
                unset($linea);

                $row['pvp'] = str_replace(".", ",", $row['pvp']);
                $row['precio_franquiciado_sin_iva'] = str_replace(".", ",", $row['precio_franquiciado_sin_iva']);
                $row['total_factura'] = str_replace(".", ",", $row['total_factura']);

                $linea = $row['id_pedido'] . ";" . $row['nombre_centro'] . ";" . $row['fecha_pedido'] . ";" . $row['fecha_entrega'] . ";" . $row['estado'] . ";" . $row['nombre_familia'] . ";" . $row['nombre_producto'] . ";" . $row['pvp'] . ";" . $row['precio_franquiciado_sin_iva'] . ";" . $row['cantidad_stock'] . ";" . $row['cantidad'] . ";" . $row['cantidad_entregada'] . ";" . $row['cantidad_pendiente'] . ";" . $row['total_factura'] . "\n";

                $linea = iconv("UTF-8", "Windows-1252", $linea);

                fwrite($file, $linea);
            }
        }

        fclose($file);

        if (file_exists($fichero)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($fichero) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fichero));
            readfile($fichero);
        }

        exit;
    }
}
