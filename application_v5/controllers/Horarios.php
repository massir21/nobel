<?php
class Horarios extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    // ----------------------------------------------------------------------------- //
    // ... HORARIOS
    // ----------------------------------------------------------------------------- //
    function index()
    {
        // ... Comprobamos la sesion del horario
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['vacio'] = "";

        unset($parametros);
        $parametros['solo_empleados_recepcionistas'] = "";
        // ... controlamos que el perfil sea el master, sino solo mostramos lo del centro que
        // corresponda.
        if ($this->session->userdata('id_perfil') > 0) {
            $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
        }
        $data['registros'] = $this->Usuarios_model->leer_usuarios($parametros);

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Gestión de Horarios de Empleados';
        $data['content_view'] = $this->load->view('horarios/horarios_usuarios_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 6);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    function gestion($accion = null, $id_usuario = null, $id_horario = null)
    {
        // ... Comprobamos la sesion del horario
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
                $param['id_horario'] = $id_horario;
                $param['jornada'] = true;
                $data['registro_horario'] = $this->Horarios_model->leer_horarios($param);

                $parametros['id_usuario'] = $id_usuario;
                $data['registros'] = $this->Horarios_model->leer_horarios($parametros);
                $data['usuario'] = $this->Usuarios_model->leer_usuarios($parametros);
            }

            // ... Viewer con el contenido
            $data['content_view'] = $this->load->view('horarios/horarios_asignados_view', $data, true);

            // ... Modulos del horario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 6);
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

            // ... Verificamos si el rango de fechas no existe ya
            // o las fechas indicadas, ya est�n dentro de alg�n rango.
            unset($param2);
            $param2['id_usuario'] = $id_usuario;
            if ($accion == "actualizar") {
                $param2['id_horario'] = $id_horario;
            }
            $param2['fecha_inicio'] = $parametros['fecha_inicio'] . " " . $parametros['hora_inicio'];
            $param2['fecha_fin'] = $parametros['fecha_fin'] . " " . $parametros['hora_fin'];
            $control_rango_fechas = $this->Horarios_model->control_rango_fechas($param2);

            // .. Si el rango no existe, guardamos el dato, sino mensaje de aviso.
            if ($control_rango_fechas == 0) {
                if ($accion == "guardar") {
                    $parametros['id_usuario'] = $id_usuario;
                    $data['estado'] = $this->Horarios_model->nuevo_horario($parametros);
                } else {
                    $parametros['id_usuario'] = $id_usuario;
                    $parametros['id_horario'] = $id_horario;
                    $data['estado'] = $this->Horarios_model->actualizar_horario($parametros);
                }
            } else {
                $data['rango_fechas_error'] = 1;
            }

            // ... Leemos el ultimo horario introducido para el usuario
            unset($param);
            $param['id_usuario'] = $id_usuario;
            $id_horario_ultimo = $this->Horarios_model->ultimo_horario_usuario($param);

            unset($param);
            $param['id_horario'] = $id_horario_ultimo;
            $param['jornada'] = true;
            $data['registro_horario'] = $this->Horarios_model->leer_horarios($param);
        }

        // ----------------------------------------------------------------------------- //
        // ... Borrar ... 
        // ----------------------------------------------------------------------------- //
        if ($accion == "borrar") {
            $parametros['id_horario'] = $id_horario;
            $data['borrado'] = $this->Horarios_model->borrar_horario($parametros);
        }

        // ----------------------------------------------------------------------------- //
        // ... Principal ... 
        // ----------------------------------------------------------------------------- //
        if ($accion == "principal" || $accion == "guardar" || $accion == "actualizar" || $accion == "borrar") {
            unset($parametros);
            $parametros['id_usuario'] = $id_usuario;
            $parametros['jornada'] = true;
            $data['registros'] = $this->Horarios_model->leer_horarios($parametros);

            $data['usuario'] = $this->Usuarios_model->leer_usuarios($parametros);

            // ... Viewer con el contenido
            $data['pagetitle'] = 'Gestión de Horarios de Empleados';
            $data['content_view'] = $this->load->view('horarios/horarios_asignados_view', $data, true);

            // ... Modulos del usuario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 6);
            if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }
    }
}
