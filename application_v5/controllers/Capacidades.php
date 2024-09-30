<?php
class Capacidades extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    // ----------------------------------------------------------------------------- //
    // ... CAPACIDADES
    // ----------------------------------------------------------------------------- //
    function index()
    {
        // ... Comprobamos la sesion del capacidad
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['vacio'] = "";

        unset($parametros);
        $parametros['solo_empleados'] = "";
        // ... controlamos que el perfil sea el master, sino solo mostramos lo del centro que
        // corresponda.
        if ($this->session->userdata('id_perfil') > 0) {
            $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
        }
        $data['registros'] = $this->Usuarios_model->leer_usuarios($parametros);

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Gestión de Capacidades de Empleados';
        $data['content_view'] = $this->load->view('capacidades/capacidades_usuarios_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 8);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    function gestion($accion = null, $id_usuario = null, $id_capacidad = null)
    {
        // ... Comprobamos la sesion del capacidad
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Recogemos la accion a realizar
        $data['accion'] = $accion;

        // ----------------------------------------------------------------------------- //    
        // ... Guardar ...
        // ----------------------------------------------------------------------------- //
        if ($accion == "guardar") {
            //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;

            $parametros['id_usuario'] = $id_usuario;
            
            $data['estado'] = $this->Capacidades_model->nuevo_capacidad($parametros);

            unset($parametros);
            $parametros['solo_empleados'] = "";
            // ... controlamos que el perfil sea el master, sino solo mostramos lo del centro que
            // corresponda.
            if ($this->session->userdata('id_perfil') > 0) {
                $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
            }
            $data['registros'] = $this->Usuarios_model->leer_usuarios($parametros);

            // ... Viewer con el contenido
            $data['pagetitle'] = 'Gestión de Capacidades de Empleados';
            $data['content_view'] = $this->load->view('capacidades/capacidades_usuarios_view', $data, true);

            // ... Modulos del usuario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 8);
            if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }

        // ----------------------------------------------------------------------------- //
        // ... Principal ... 
        // ----------------------------------------------------------------------------- //
        if ($accion == "principal") {
            unset($parametros);
            $parametros['id_usuario'] = $id_usuario;
            $servicios_usuario = $this->Capacidades_model->leer_capacidades($parametros);
            $data['servicios_usuario'] = [];
            if($servicios_usuario>0) {
                foreach ($servicios_usuario as $k => $s) {
                    $data['servicios_usuario'][] = $s['id_servicio'];
                }
            }
            //printr($data['servicios_usuario']);
            unset($param);
            $param['id_usuario'] = $id_usuario;
            $data['usuario'] = $this->Usuarios_model->leer_usuarios($param);

            unset($param);
            $param['vacio'] = "";
            $param['obsoleto']="0";
            $servicios = $this->Servicios_model->leer_servicios($param);
            //printr($servicios);
            $familias = [];
            $familias_servicios = [];
            foreach ($servicios as $key => $servicio) {
                if(!array_key_exists($servicio['id_familia_servicio'], $familias_servicios)){
                    $familias[] = ['id_familia' => $servicio['id_familia_servicio'], 'nombre_familia' => $servicio['nombre_familia']];
                }
                $familias_servicios[$servicio['id_familia_servicio']][] = $servicio;
            }
            $data['familias'] = $familias;
            $data['familias_servicios'] = $familias_servicios;
            //printr($familias);
            // ... Viewer con el contenido
            $data['pagetitle'] = 'Gestión de Capacidades de Empleados';
            $data['content_view'] = $this->load->view('capacidades/capacidades_asignadas_view', $data, true);

            // ... Modulos del usuario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 8);
            if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }
    }
}
