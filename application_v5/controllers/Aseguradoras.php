<?php
class Aseguradoras extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }
    
    function index()
    {
        // ... Comprobamos la sesion del servicio
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        
        $data['registros'] = $this->Aseguradoras_model->getListadoAseguradoras();
        $data['pagetitle'] = 'Gestión de Aseguradoras';
        $data['actionstitle'][] = '<a href="' . base_url() . 'Aseguradoras/gestion/nuevo" class="btn btn-primary text-inverse-primary">Añadir aseguradora</a>';

        $data['content_view'] = $this->load->view('aseguradoras/aseguradoras_view.php', $data, true);


        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 4);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    function gestion($accion = null, $id_aseguradora = null)
    {
        // ... Comprobamos la sesion del servicio
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Recogemos la accion a realizar
        $data['accion'] = $accion;
        $paramFiltroaseguradora = [];
        if ($accion === 'nuevo' || $accion === 'editar') {
            if ($accion === 'editar') {
                $paramFiltroaseguradora['id_aseguradora'] = $id_aseguradora;
                $data['registros'] = $this->Aseguradoras_model->getListadoAseguradoras($paramFiltroaseguradora);

            }
            unset($param);

            $param['vacio'] = "";

            $data['pagetitle'] = ($accion == 'nuevo') ? 'Nuevo aseguradora' : 'Editar aseguradora';
            $data['content_view'] = $this->load->view('aseguradoras/aseguradoras_nuevoeditar_view', $data, true);

            // ... Modulos del usuario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 4);
            if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }

        if ($accion === 'guardar' || $accion === 'actualizar') {
            if (!empty($_POST)) {
                $parametros = $_POST;
            }

            if ($accion == "guardar") {
                $data['estado'] = $this->Aseguradoras_model->nuevo_aseguradora($parametros);
            } else {
                $parametros['id_aseguradora'] = $id_aseguradora;
                $data['estado'] = $this->Aseguradoras_model->actualizar_aseguradora($parametros);
            }
        }

        if ($accion === 'borrar') {
            $parametros['id_aseguradora'] = $id_aseguradora;
            $data['borrado'] = $this->Aseguradoras_model->borrar_aseguradora($parametros);
        }

        if ($accion == "" || $accion == "guardar" || $accion == "actualizar" || $accion == "borrar") {
            unset($parametros);
            $parametros['vacio'] = "";
            $data['registros'] = $this->Aseguradoras_model->getListadoAseguradoras($parametros);

            // ... Viewer con el contenido
            $data['pagetitle'] = 'Gestión de Aseguradoras';
            $data['actionstitle'][] = '<a href="' . base_url() . 'Aseguradoras/gestion/nuevo" class="btn btn-primary text-inverse-primary">Añadir aseguradora</a>';

            $data['content_view'] = $this->load->view('aseguradoras/aseguradoras_view.php', $data, true);

            // ... Modulos del usuario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 4);
            if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }

    }

    function tipos($accion = null, $id_tipo = null)
    {
        // ... Comprobamos la sesion del servicio
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Recogemos la accion a realizar
        $data['accion'] = $accion;

        if ($accion == "nuevo" || $accion == "editar") {
            if ($accion == "editar") {
                $param['id_tipo'] = $id_tipo;
            }

            // ... Viewer con el contenido
            $data['pagetitle'] = ($accion == 'nuevo') ? 'Nuevo tipo' : 'Editar tipo';
            $data['content_view'] = $this->load->view('aseguradoras/aseguradoras_tipo_nuevoeditar_view', $data, true);

            // ... Modulos del usuario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 4);
            if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }

        if ($accion === 'guardar' || $accion === 'actualizar') {
            if (!empty($_POST)) {
                $parametros = $_POST;
            }

            if ($accion == "guardar") {
                $data['estado'] = $this->TiposAseguradoras_model->nuevo_tipo($parametros);
            } else {
                $parametros['id_tipo'] = $id_tipo;
                $data['estado'] = $this->TiposAseguradoras_model->actualizar_tipo($parametros);
            }
        }

        if ($accion === 'borrar') {
            $parametros['id_tipo'] = $id_tipo;
            $data['borrado'] = $this->TiposAseguradoras_model->borrar_tipo($parametros);
        }


        if ($accion == "" || $accion == "guardar" || $accion == "actualizar" || $accion == "borrar") {
            unset($parametros);
            $parametros['vacio'] = "";
            $data['registros'] = $this->Aseguradoras_model->getListadoAseguradoras($parametros);

            // ... Viewer con el contenido
            $data['pagetitle'] = 'Gestión de Aseguradoras';
            $data['actionstitle'][] = '<a href="' . base_url() . 'Aseguradoras/gestion/nuevo" class="btn btn-primary text-inverse-primary">Añadir aseguradora</a>';

            $data['content_view'] = $this->load->view('aseguradoras/aseguradoras_view.php', $data, true);

            // ... Modulos del usuario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 4);
            if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }

    }
}