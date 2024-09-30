<?php
class Instrucciones extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        // ... Comprobamos la sesion del producto
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
    }

    function index()
    {
        $data['vacio'] = "";

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Envio de instrucciones de productos';
        $data['content_view'] = $this->load->view('instrucciones/productos_envio_instrucciones_view', $data, true);

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

    function enviar()
    {   
        //print_r($this->input->post('productos'));
        //exit;
        if ($this->input->post('productos') != "") {
            $instrucciones = "";
            if(!is_array($this->input->post('productos'))){
                $productos = explode(",", $this->input->post('productos'));
            }else{
                $productos = $this->input->post('productos');
            }
            foreach ($productos as $id_producto) {
                $producto = $this->Productos_model->leer_productos(['id_producto' => $id_producto]);

                $instrucciones .= "<p>" . $producto[0]['instrucciones'] . "</p>";
            }

            //
            // Generar contenido del email
            //
            $data['enviado'] = 1;
            $data['nombre_cliente'] = $this->input->post('nombre_cliente');
            $data['instrucciones'] = $instrucciones;

            $mensaje = $this->load->view('emails/productos_instrucciones_email_view', $data, true);
            $to = $this->input->post('email_cliente');
            $from = "info@templodelmasaje.com";
            $asunto = "Instrucciones de Productos";

            if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
                $error = $this->Utiles_model->enviar_email($to, $from, $asunto, $mensaje);
            }

            // ... Viewer con el contenido
            $data['pagetitle'] = 'Envio de instrucciones de productos';
            $data['content_view'] = $this->load->view('instrucciones/productos_envio_instrucciones_view', $data, true);

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
        } else {
            echo "error";
            exit;
        }
    }

    function json($q = null)
    {
        unset($parametros);
        $parametros['q'] = $_GET["q"];
        $productos = $this->Productos_model->productos_instrucciones_json($parametros);

        $json_response = json_encode($productos);

        $json_response = str_replace("(", "-", $json_response);
        $json_response = str_replace(")", "-", $json_response);
        $json_response = str_replace("&", "y", $json_response);

        echo $json_response;

        exit;
    }
}
