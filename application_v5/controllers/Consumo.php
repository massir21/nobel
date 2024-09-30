<?php
class Consumo extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    // ----------------------------------------------------------------------------- //
    // ... COSUMO DE PRODUCTOS
    // ----------------------------------------------------------------------------- //
    function index($id_centro = null)
    {
        // ... Comprobamos la sesion
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        if ($id_centro == null) {
            if ($this->session->userdata('id_centro_usuario') == 1) {
                $id_centro = 6;
            } else {
                $id_centro = $this->session->userdata('id_centro_usuario');
            }
        }
        $data['id_centro'] = $id_centro;

        // unset($param);
        // $param['id_centro'] = $id_centro;
        // $data['registros'] = $this->Productos_model->leer_productos_consumo($param);

        // ... Leemos todos los centros disponibles para filtrar.
        unset($param);
        $param['vacio'] = "";
        $data['centros_todos'] = $this->Usuarios_model->leer_centros($param);

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Consumo de productos';
        if ($this->session->userdata('id_perfil') != 0 && $this->session->userdata('id_perfil') != 4) {
            $data['actionstitle'] = ['<a href="'.base_url().'consumo/nuevo" class="btn btn-primary text-inverse-primary">Añadir consumo</a>'];
        }
        $data['content_view'] = $this->load->view('consumo/consumo_view', $data, true);

        // ... Modulos del caja
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 22);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    public function get_consumos($table = null, $columna = null, $valor = null)
	{
		$this->load->library('Datatable');
		$campos = [
            'centros.nombre_centro',
            'productos_consumo.fecha_creacion',
            'productos_familias.nombre_familia',
            'productos.nombre_producto',
            'productos_consumo.cantidad_consumida',
            'productos_consumo.nota',
            'productos_consumo.cabina',
            'productos_consumo.id_centro',
            'productos_consumo.id_consumo',
		];
		$tabla = 'productos_consumo';
		$join = [
            'centros' => 'centros.id_centro = productos_consumo.id_centro',
            'productos' => 'productos.id_producto = productos_consumo.id_producto',
            'productos_familias' => 'productos_familias.id_familia_producto = productos.id_familia_producto'
        ];
		$add_rule = [];
		$where = ['productos_consumo.borrado' => 0];

		
		if ($this->input->get('id_centro') != '') {
			$where['productos_consumo.id_centro'] = $this->input->get('id_centro');
		}
		
        if ($this->input->get('id_consumo') != ''){
            $where['productos_consumo.id_consumo'] = $this->input->get('id_consumo');
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
    function nuevo()
    {
        // ... Comprobamos la sesion
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['vacio'] = "";

        // ... Siempre leemos todas las familias de productos y productos, con el javascript
        // correspondiente en los desplegables.
        /*unset($param);
        $param['vacio'] = "";
        $data['familias_productos'] = $this->Productos_model->leer_familias_productos($param);

        unset($param);
        $param['vacio'] = "";
        $data['productos'] = $this->Productos_model->leer_productos($param);

        unset($param);
        $param['id_producto'] = "";
        $param['form'] = "form";
        //$data['script_productos'] = $this->Productos_model->javacript_familias_productos($param);
        $data['productos_json'] = 1; //08/07/20*/

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Consumo de Productos';
        $data['content_view'] = $this->load->view('consumo/consumo_realizar_view', $data, true);

        // ... Modulos del caja
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 22);
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
    function guardar($id_centro = null)
    {
        // ... Comprobamos la sesion
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        if ($id_centro == null) {
            if ($this->session->userdata('id_centro_usuario') == 1) {
                $id_centro = 6;
            } else {
                $id_centro = $this->session->userdata('id_centro_usuario');
            }
        }
        $data['id_centro'] = $id_centro;

        // ... Recogemos los parametros del producto indicado y lo guardamos.
        //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
        foreach ($_POST as $key => $value) {
            $parametros[$key] = $value;
        }

        unset($param);
        $param['id_producto'] = $parametros['id_producto'];
        $param['id_centro'] = $id_centro;
        $param['cantidad_consumida'] = $parametros['cantidad_consumida'];
        //08/07/20
        $param['nota'] = $parametros['nota'];
        $param['cabina'] = $parametros['cabina'];
        //Fin

        $data['estado'] = $this->Productos_model->guardar_consumo_producto($param);

        // ... Leemos los productos consumidos para el centro correspondiente.
        //unset($param);
        //$param['id_centro'] = $id_centro;
        //$data['registros'] = $this->Productos_model->leer_productos_consumo($param);

        // ... Leemos todos los centros disponibles para filtrar.
        unset($param);
        $param['vacio'] = "";
        $data['centros_todos'] = $this->Usuarios_model->leer_centros($param);

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Consumo de productos';
        if ($this->session->userdata('id_perfil') != 0 && $this->session->userdata('id_perfil') != 4) {
            $data['actionstitle'] = ['<a href="'.base_url().'consumo/nuevo" class="btn btn-primary text-inverse-primary">Añadir consumo</a>'];
        }
        $data['content_view'] = $this->load->view('consumo/consumo_view', $data, true);

        // ... Modulos del caja
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 22);
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
    function borrar($id_consumo = null, $id_centro = null)
    {
        // ... Comprobamos la sesion
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($param);
        $param['id_consumo'] = $id_consumo;
        $data['borrado'] = $this->Productos_model->borrar_consumo_producto($param);

        if ($id_centro == null) {
            if ($this->session->userdata('id_centro_usuario') == 1) {
                $id_centro = 6;
            } else {
                $id_centro = $this->session->userdata('id_centro_usuario');
            }
        }
        $data['id_centro'] = $id_centro;

        // ... Leemos los productos consumidos para el centro correspondiente.
        //unset($param);
        //$param['id_centro'] = $id_centro;
        //$data['registros'] = $this->Productos_model->leer_productos_consumo($param);

        // ... Leemos todos los centros disponibles para filtrar.
        unset($param);
        $param['vacio'] = "";
        $data['centros_todos'] = $this->Usuarios_model->leer_centros($param);

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Consumo de productos';
        if ($this->session->userdata('id_perfil') != 0 && $this->session->userdata('id_perfil') != 4) {
            $data['actionstitle'] = ['<a href="'.base_url().'consumo/nuevo" class="btn btn-primary text-inverse-primary">Añadir consumo</a>'];
        }
        $data['content_view'] = $this->load->view('consumo/consumo_view', $data, true);

        // ... Modulos del caja
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 22);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }
}
