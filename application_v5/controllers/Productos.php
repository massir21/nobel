<?php
class Productos extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    // ----------------------------------------------------------------------------- //
    // ... PRODUCTOS
    // ----------------------------------------------------------------------------- //
    function index()
    {
        // ... Comprobamos la sesion del producto
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['vacio'] = "";

        unset($parametros);
        $parametros['vacio'] = "";
        $data['registros'] = $this->Productos_model->leer_productos($parametros);
        $data['registros_familias'] = $this->Productos_model->leer_familias_productos($parametros);

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Gestión de Productos';
        if ($this->session->userdata('id_perfil') == 0) {
            $data['actionstitle'][] = '<a href="'.base_url().'productos/gestion/nuevo" class="btn btn-primary text-inverse-primary">Añadir producto</a>';
        }
        $data['actionstitle'][] = '<a href="'.base_url().'productos/exportar" class="btn btn-warning text-inverse-warning">Exportar CSV</a>';
        $data['content_view'] = $this->load->view('productos/productos_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 5);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    public function get_productos($table = null, $columna = null, $valor = null)
	{
		$this->load->library('Datatable');
		$campos = [
            'productos.nombre_producto',
            'productos_familias.nombre_familia',
            'productos.pvp',
            'productos.precio_franquiciado_sin_iva',
            'productos.precio_compra_sin_iva',
            'productos_stock.cantidad_stock',
            'productos_stock.stock_minimo',
            'productos.obsoleto',
            'productos.id_producto'

		];
		$tabla = 'productos';
		$join = [
            'productos_familias' => 'productos_familias.id_familia_producto = productos.id_familia_producto',
            'productos_stock' => 'productos_stock.id_producto = productos.id_producto'
        ];
		$add_rule = ['group_by' => 'productos.id_producto'];
		$where = [
            'productos.borrado' => 0,
            'productos_stock.id_centro = '.$this->session->userdata('id_centro_usuario')
        ];

		
		if ($this->input->get('id_centro') != '') {
			$where['recordatorios.id_centro'] = $this->input->get('id_centro');
		}
		
        if ($this->input->get('fecha_desde') != ''){
            $where['recordatorios.posponer >='] = $this->input->get('fecha_desde');
        }
        if ($this->input->get('fecha_hasta') != ''){
            $where['recordatorios.posponer <='] = $this->input->get('fecha_hasta');
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
    // ... 
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    function gestion($accion = null, $id_producto = null)
    {
        // ... Comprobamos la sesion del producto
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
        if ($accion == "nuevo" || $accion == "editar") {
            if ($accion == "editar") {
                $param['id_producto'] = $id_producto;
                $data['registros'] = $this->Productos_model->leer_productos($param);
            }

            unset($param);
            $param['vacio'] = "";
            $data['familias_productos'] = $this->Productos_model->leer_familias_productos($param);

            // ... Nombre del centro del usuario
            $param_centro['id_centro'] = $this->session->userdata('id_centro_usuario');
            $data['centros'] = $this->Usuarios_model->leer_centros($param_centro);

            // ... Viewer con el contenido
            $data['pagetitle'] = ($accion == 'nuevo') ? 'Nuevo producto' : 'Editar producto';
            $data['content_view'] = $this->load->view('productos/productos_nuevoeditar_view', $data, true);

            // ... Modulos del usuario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 5);
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
        if ($accion == "guardar" || $accion == "actualizar" || $accion == "actualizar_stock") {
            //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;

            if ($accion == "guardar") {
                $data['estado'] = $this->Productos_model->nuevo_producto($parametros);
            }

            if ($accion == "actualizar") {
                $parametros['id_producto'] = $id_producto;
                $data['estado'] = $this->Productos_model->actualizar_producto($parametros);
            }

            if ($accion == "actualizar_stock") {
                $data['estado'] = $this->Productos_model->actualizar_producto_stock($parametros);
            }
        }

        // ----------------------------------------------------------------------------- //
        // ... Borrar ... 
        // ----------------------------------------------------------------------------- //
        if ($accion == "borrar") {
            $parametros['id_producto'] = $id_producto;
            $data['borrado'] = $this->Productos_model->borrar_producto($parametros);
        }

        // ----------------------------------------------------------------------------- //
        // ... Principal ... 
        // ----------------------------------------------------------------------------- //
        if ($accion == "" || $accion == "guardar" || $accion == "actualizar" || $accion == "actualizar_stock" || $accion == "borrar") {
            unset($parametros);
            $parametros['vacio'] = "";
            $data['registros'] = $this->Productos_model->leer_productos($parametros);
            $data['registros_familias'] = $this->Productos_model->leer_familias_productos($parametros);

            // ... Viewer con el contenido
            $data['pagetitle'] = 'Gestión de Productos';
            if ($this->session->userdata('id_perfil') == 0) {
                $data['actionstitle'][] = '<a href="'.base_url().'productos/gestion/nuevo" class="btn btn-primary text-inverse-primary">Añadir producto</a>';
            }
            $data['actionstitle'][] = '<a href="'.base_url().'productos/exportar" class="btn btn-warning text-inverse-warning">Exportar CSV</a>';
            $data['content_view'] = $this->load->view('productos/productos_view', $data, true);

            // ... Modulos del usuario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 5);
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
    // ... 
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    function familias($accion = null, $id_familia_producto = null)
    {
        // ... Comprobamos la sesion del producto
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
        if ($accion == "nuevo" || $accion == "editar") {
            if ($accion == "editar") {
                $param['id_familia_producto'] = $id_familia_producto;
                $data['registros'] = $this->Productos_model->leer_familias_productos($param);
            }

            // ... Viewer con el contenido
            $data['pagetitle'] = ($accion == 'nuevo') ? 'Nueva familia' : 'Editar familia';
            $data['content_view'] = $this->load->view('productos/productos_familias_nuevoeditar_view', $data, true);

            // ... Modulos del usuario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 5);
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
                $data['estado'] = $this->Productos_model->nuevo_familia_producto($parametros);
            } else {
                $parametros['id_familia_producto'] = $id_familia_producto;
                $data['estado'] = $this->Productos_model->actualizar_familia_producto($parametros);
            }
        }

        // ----------------------------------------------------------------------------- //
        // ... Borrar ... 
        // ----------------------------------------------------------------------------- //
        if ($accion == "borrar") {
            $parametros['id_familia_producto'] = $id_familia_producto;
            $data['borrado'] = $this->Productos_model->borrar_familia_producto($parametros);
        }

        // ----------------------------------------------------------------------------- //
        // ... Principal ... 
        // ----------------------------------------------------------------------------- //
        if ($accion == "" || $accion == "guardar" || $accion == "actualizar" || $accion == "borrar") {
            unset($parametros);
            $parametros['vacio'] = "";
            $data['registros_familias'] = $this->Productos_model->leer_familias_productos($parametros);
            $data['registros'] = $this->Productos_model->leer_productos($parametros);

            // ... Viewer con el contenido
            $data['pagetitle'] = 'Gestión de Productos';
            if ($this->session->userdata('id_perfil') == 0) {
                $data['actionstitle'][] = '<a href="'.base_url().'productos/gestion/nuevo" class="btn btn-primary text-inverse-primary">Añadir producto</a>';
            }
            $data['actionstitle'][] = '<a href="'.base_url().'productos/exportar" class="btn btn-warning text-inverse-warning">Exportar CSV</a>';
            $data['content_view'] = $this->load->view('productos/productos_view', $data, true);

            // ... Modulos del usuario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 5);
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
    // ... 
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    function dietario($accion = null, $id_cliente = null)
    {
        // ... Comprobamos la sesion del producto
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['accion'] = $accion;
        $data['id_cliente'] = $id_cliente;

        // ... Guardar
        if ($accion == "guardar") {
            //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;

            // Hacemos el bucle por si se eligieron varios productos.
            $i = 0;
            foreach ($parametros['id_producto'] as $producto) {
                if ($producto > 0) {
                    unset($param);
                    $param['id_cliente'] = $parametros['id_cliente'];
                    $param['id_empleado_venta'] = $parametros['id_empleado_venta'];
                    $param['id_producto'] = $producto;
                    $param['cantidad'] = $parametros['cantidad'][$i];

                    $data['estado'] = $this->Dietario_model->nuevo_producto($param);
                }
                $i++;
            }

            $id_cliente = $parametros['id_cliente'];
        }

        if ($id_cliente != null) {
            unset($parametros);
            $parametros['id_cliente'] = $id_cliente;
            $data['cliente'] = $this->Clientes_model->leer_clientes($parametros);
        } else {
            $data['cliente'] = 0;
        }

        // ... Leemos los empleados
        unset($parametros);
        $parametros['solo_empleados_recepcionistas'] = 1;
        if ($this->session->userdata('id_perfil') > 0) {
            $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
        }
        $data['empleados'] = $this->Usuarios_model->leer_usuarios($parametros);

        // ... Leemos los productos
        unset($parametros);
        $parametros['vacio'] = "";
        $data['productos'] = $this->Productos_model->leer_productos($parametros);

        //unset($param);
        //$param['vacio']="";    
        //$data['clientes']=$this->Clientes_model->leer_clientes($param);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 5);
        if ($permiso) {
            $this->load->view('productos/productos_vender_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    // ... 
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    function exportar()
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
        $parametros['vacio'] = "";
        $registros = $this->Productos_model->leer_productos($parametros);

        $fichero = RUTA_SERVIDOR . "/recursos/productos.csv";

        $file = fopen($fichero, "w");

        if ($this->session->userdata('id_perfil') > 0) {
            $linea = "nombre_producto;nombre_familia;pvp;precio_franquiciado_sin_iva;cantidad_stock;stock_minimo\n";
        } else {
            $linea = "nombre_producto;nombre_familia;pvp;precio_franquiciado_sin_iva;precio_compra_sin_iva;obsoleto;cantidad_stock;stock_minimo\n";
        }
        fwrite($file, $linea);

        if ($registros > 0) {
            foreach ($registros as $row) {
                unset($linea);
                if ($this->session->userdata('id_perfil') > 0) {
                    $linea = $row['nombre_producto'] . ";" . $row['nombre_familia'] . ";" . $row['pvp'] . ";" . $row['precio_franquiciado_sin_iva'] . ";" . $row['cantidad_stock'] . ";" . $row['stock_minimo'] . "\n";
                } else {
                    $linea = $row['nombre_producto'] . ";" . $row['nombre_familia'] . ";" . $row['pvp'] . ";" . $row['precio_franquiciado_sin_iva'] . ";" . $row['precio_compra_sin_iva'] . ";" . $row['obsoleto'] . ";" . $row['cantidad_stock'] . ";" . $row['stock_minimo'] . "\n";
                }

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

    function json($q = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($parametros);
        $parametros['q'] = $_GET["q"];
        $productos = $this->Productos_model->productos_json($parametros);

        $json_response = json_encode($productos);

        $json_response = str_replace("(", "-", $json_response);
        $json_response = str_replace(")", "-", $json_response);
        $json_response = str_replace("&", "y", $json_response);

        echo $json_response;

        exit;
    }

    function jsonselect2($q = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        unset($parametros);
        $parametros['q'] = $_GET["q"];
        $clientes = $this->Productos_model->productos_json($parametros);
        $json_response = json_encode($clientes);
        echo $json_response;
        exit;
    }


}
