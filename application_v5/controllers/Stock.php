<?php
class Stock extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    // ----------------------------------------------------------------------------- //
    // ... INTRODUCIR STOCK EN LA CENTRAL
    // ----------------------------------------------------------------------------- //
    function index()
    {
        // ... Comprobamos la sesion
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($param);
        $param['id_centro'] = 1;
        //$data['registros'] = $this->Productos_model->leer_productos_stock_introducido($param);

        //$data['productos_json'] = 1;

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Introducir stock';
        $data['content_view'] = $this->load->view('stock/stock_view', $data, true);

        // ... Modulos
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 32);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    public function get_stock_introducido($table = null, $columna = null, $valor = null)
	{
		$this->load->library('Datatable');
		$campos = [
            'productos_introducido_stock.fecha_creacion',
            'productos_familias.nombre_familia',
            'productos.nombre_producto',
            'productos_introducido_stock.cantidad',
            'productos_introducido_stock.id_stock_introducido',
		];
		$tabla = 'productos_introducido_stock';
		$join = [
            'productos' => 'productos.id_producto = productos_introducido_stock.id_producto',
            'productos_familias' => 'productos_familias.id_familia_producto = productos.id_familia_producto',
            'centros' => 'centros.id_centro = productos_introducido_stock.id_centro'
        ];
		$add_rule = ['group_by' => 'productos_introducido_stock.id_stock_introducido'];
		$where = ['productos_introducido_stock.borrado' => 0];
	
		if ($this->input->get('id_centro') != '') {
			$where['recordatorios.id_centro'] = $this->input->get('id_centro');
		}
        if ($this->input->get('fecha_desde') != ''){
            $where['productos_introducido_stock.fecha_creacion >='] = $this->input->get('fecha_desde');
        }
        if ($this->input->get('fecha_hasta') != ''){
            $where['productos_introducido_stock.fecha_creacion <='] = $this->input->get('fecha_hasta');
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

    //
    // ...
    //
    function anadir($id_centro = null)
    {
        // ... Comprobamos la sesion
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Recogemos los parametros del producto indicado y lo guardamos.
        //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
        $parametros = $_POST;

        unset($param);
        $param['id_producto'] = $parametros['id_producto'];
        $param['id_centro'] = 1;
        $param['cantidad'] = $parametros['cantidad'];
        $data['estado'] = $this->Productos_model->guardar_stock_introducido($param);

        unset($param);
        $param['id_centro'] = 1;
        $data['registros'] = $this->Productos_model->leer_productos_stock_introducido($param);

        $data['productos_json'] = 1;

        // ... Viewer con el contenido
        $data['pagetitle'] = '';
        $data['content_view'] = $this->load->view('stock/stock_view', $data, true);

        // ... Modulos
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 32);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    //
    // ...
    //
    function borrar($id_stock_introducido = null)
    {
        // ... Comprobamos la sesion
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($param);
        $param['id_stock_introducido'] = $id_stock_introducido;
        $data['borrado'] = $this->Productos_model->borrar_stock_introducido($param);

        unset($param);
        $param['id_centro'] = 1;
        $data['registros'] = $this->Productos_model->leer_productos_stock_introducido($param);

        $data['productos_json'] = 1;

        // ... Viewer con el contenido
        $data['pagetitle'] = '';
        $data['content_view'] = $this->load->view('stockstock_view', $data, true);

        // ... Modulos
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 32);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }
}
