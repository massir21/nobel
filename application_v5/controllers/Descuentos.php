<?php
class Descuentos extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    // ----------------------------------------------------------------------------- //
    // ... DESCUENTOS
    // ----------------------------------------------------------------------------- //
    function index()
    {
        // ... Comprobamos la sesion del descuento
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['vacio'] = "";

        unset($parametros);
        $parametros['vacio'] = "";
        $data['registros'] = $this->Descuentos_model->leer_descuentos($parametros);

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Gestión de Descuentos';
        $data['actionstitle'] = ['<a href="'.base_url().'descuentos/gestion/nuevo" class="btn btn-primary text-inverse-primary">Añadir descuento</a>'];
        $data['content_view'] = $this->load->view('descuentos/descuentos_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 39);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    function gestion($accion = null, $id_descuento = null)
    {
        // ... Comprobamos la sesion del descuento
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
                $param['id_descuento'] = $id_descuento;
                $data['registros'] = $this->Descuentos_model->leer_descuentos($param);
            }

            unset($param);
            $param['vacio'] = "";
            $data['familias_servicios'] = $this->Servicios_model->leer_familias_servicios($param);

            unset($param);
            $param['vacio'] = "";
            $data['familias_productos'] = $this->Productos_model->leer_familias_productos($param);

            // ... Viewer con el contenido
            $data['pagetitle'] = ($accion== 'editar')? 'Editar registro' : 'Nuevo registro';
            $data['content_view'] = $this->load->view('descuentos/descuentos_nuevoeditar_view', $data, true);

            // ... Modulos del usuario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 39);
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
                $data['estado'] = $this->Descuentos_model->nuevo_descuento($parametros);
            } else {
                $parametros['id_descuento'] = $id_descuento;
                $data['estado'] = $this->Descuentos_model->actualizar_descuento($parametros);
            }
        }

        // ----------------------------------------------------------------------------- //
        // ... Borrar ...
        // ----------------------------------------------------------------------------- //
        if ($accion == "borrar") {
            $parametros['id_descuento'] = $id_descuento;
            $data['borrado'] = $this->Descuentos_model->borrar_descuento($parametros);
        }

        // ----------------------------------------------------------------------------- //
        // ... Principal ...
        // ----------------------------------------------------------------------------- //
        if ($accion == "" || $accion == "guardar" || $accion == "actualizar" || $accion == "borrar") {
            unset($parametros);
            $parametros['vacio'] = "";
            $data['registros'] = $this->Descuentos_model->leer_descuentos($parametros);
            $data['familias_servicios'] = $this->Servicios_model->leer_familias_servicios($parametros);
            $data['familias_productos'] = $this->Productos_model->leer_familias_productos($parametros);

            // ... Viewer con el contenido
            $data['pagetitle'] = 'Gestión de descuentos';
            $data['actionstitle'] = ['<a href="'.base_url().'descuentos/gestion/nuevo" class="btn btn-primary text-inverse-primary">Añadir descuento</a>'];
            $data['content_view'] = $this->load->view('descuentos/descuentos_view', $data, true);

            // ... Modulos del usuario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 39);
            if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }
    }

    //
    //
    //
    function comprobar()
    {
        // ... Comprobamos la sesion del descuento
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        if (isset($_POST['items'])) {
            $r = $this->Descuentos_model->comprobar($_POST['items'], $_POST['importes']);
            echo $r;
        } else {
            echo "0";
        }

        exit;
    }
}
