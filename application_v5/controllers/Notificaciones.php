<?php

require '/var/www/vhosts/clinicadentalnobel.es/extranet.clinicadentalnobel.es/recursos/motorPDF/vendor/autoload.php';

use Twilio\Rest\Client;


class Notificaciones extends CI_Controller
{

    private $sid;
    private $token;
    private $twilio_number;


    function __construct()
    {
        parent::__construct();
        $this->load->model('Notificaciones_model');
        $this->sid = 'ACeb88dbf27b250ed7522a44b9d194de1d';
        $this->token = '206a9e788a630d7717882f9f844992f3';
        $this->twilio_number = '+34631669727';
    }

    // ----------------------------------------------------------------------------- //
    // ... Notificaciones
    // ----------------------------------------------------------------------------- //
    function index(): void
    {

        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($param);
        $param['vacio'] = "";
        $data['centros_todos'] = $this->Usuarios_model->leer_centros($param);
        //printr($this->input->post());
        unset($param);
        $param['vacio'] = "";
        if ($this->input->post('fecha_desde') != '') {
            $param['fecha_desde'] = $this->input->post('fecha_desde');
            $data['fecha_desde'] = $this->input->post('fecha_desde');
        }
        if ($this->input->post('fecha_hasta') != '') {
            $param['fecha_hasta'] = $this->input->post('fecha_hasta');
            $data['fecha_hasta'] = $this->input->post('fecha_hasta');
        }
        if ($this->input->post('id_cliente') != '') {
            $param['id_cliente'] = $this->input->post('id_cliente');
            $data['id_cliente'] = $this->input->post('id_cliente');
            $data['cliente'] = $this->Clientes_model->leer_clientes($param);
        }
         if ($this->input->post('filtro_estados') != '') {
            $param['filtro_estados'] = $this->input->post('filtro_estados');
            $data['filtro_estados'] = $this->input->post('filtro_estados');
        }


        if ($this->input->post('buscar') != '') {
            $data['citas'] = $this->Notificaciones_model->leer_notificaciones($param);
        } else {
            $data['citas'] = $this->Notificaciones_model->leer_notificaciones();
        }

        $data['tipo_recordatorio'] = $this->Notificaciones_model->tipo_recordatorio();

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Notificaciones';
        $data['content_view'] = $this->load->view('notificaciones/notificaciones_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        $this->load->view($this->config->item('template_dir') . '/master', $data);
    }

    function no_enviados()
    {

        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($param);
        $param['vacio'] = "";
        $data['centros_todos'] = $this->Usuarios_model->leer_centros($param);
        //printr($this->input->post());
        unset($param);
        $param['vacio'] = "";
        if ($this->input->post('fecha_desde') != '') {
            $param['fecha_desde'] = $this->input->post('fecha_desde');
            $data['fecha_desde'] = $this->input->post('fecha_desde');
        }
        if ($this->input->post('fecha_hasta') != '') {
            $param['fecha_hasta'] = $this->input->post('fecha_hasta');
            $data['fecha_hasta'] = $this->input->post('fecha_hasta');
        }
        if ($this->input->post('id_cliente') != '') {
            $param['id_cliente'] = $this->input->post('id_cliente');
            $data['id_cliente'] = $this->input->post('id_cliente');
            $data['cliente'] = $this->Clientes_model->leer_clientes($param);
        }
        // echo (($this->input->post('buscar') == '') ? 'entra' : 'No esta vacio');

        if ($this->input->post('buscar') != '') {
            $data['citas'] = $this->Notificaciones_model->leer_notificaciones_no_enviadas($param);
        } else {
            $data['citas'] = $this->Notificaciones_model->leer_notificaciones_no_enviadas();
        }

        $data['tipo_recordatorio'] = $this->Notificaciones_model->tipo_recordatorio();

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Recordatorios no enviados ';
        $data['content_view'] = $this->load->view('notificaciones/notificaciones_no_enviados_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        $this->load->view($this->config->item('template_dir') . '/master', $data);
    }


    function ajustes(): void
    {
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }


        $data['tipo_recordatorio'] = $this->Notificaciones_model->tipo_recordatorio();

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Ajustes de las notificaciones';
        $data['content_view'] = $this->load->view('notificaciones/ajustes_notificaciones_view.php', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        $this->load->view($this->config->item('template_dir') . '/master', $data);
    }


    function actualizacion_ajustes(): void
    {
        $id_frecuenciaEnvio = $this->input->post('id_frecuenciaEnvio');
        $esta_activo = $this->input->post('esta_activo') ? 1 : 0;
        $mensaje_personalizado = $this->input->post('mensaje_personalizado');

        $this->Notificaciones_model->update_ajustes($id_frecuenciaEnvio, $esta_activo, $mensaje_personalizado);

        redirect('notificaciones/ajustes');
    }



    public function enviar_notificaciones_programadas(): void
    {
        // if (!$this->input->is_cli_request()) {
        //     echo "Este método solo puede ser ejecutado desde la línea de comandos.";
        //     return;
        // }

		

        $citas_proximas = $this->Notificaciones_model->obtener_citas_proximas();

        foreach ($citas_proximas as $cita) {
			
            $this->enviar_notificacion($cita->id_cita, 1, 1);

        }

        $citas_a_seis_meses = $this->Notificaciones_model->obtener_citas_a_seis_meses();
        foreach ($citas_a_seis_meses as $cita) {
            $this->enviar_notificacion($cita->id_cita, 2, 2);
        }
    }


    private function enviar_notificacion(int $id_cita, int $id_frecuenciaEnvio, int $tipo_envio): void
    {

        $cita = $this->Notificaciones_model->get_cita_info($id_cita);
        $frecuencia = $this->Notificaciones_model->get_frecuencia_envio($id_frecuenciaEnvio);
        $notificacion = $this->Notificaciones_model->get_notificacion_by_cita($id_cita);

         if ($cita && $frecuencia && $notificacion) {
            $mensaje = $this->personalizar_mensaje($frecuencia->mensaje_personalizado, $cita);

            try {
            	$client = new Client($this->sid, $this->token);
				
                $message = $client->messages->create(
                    'whatsapp:+34' . $cita->cliente_telefono,
                    [
                        'from' => 'whatsapp:' . $this->twilio_number,
                        'body' => $mensaje
                    ]
                );

                if ($message->sid) {
                    $this->Notificaciones_model->update_notificacion_status($notificacion->id_notificacion, 'Enviado', 		$tipo_envio);
                } else {
                    $this->Notificaciones_model->update_notificacion_status($notificacion->id_notificacion, 'No enviado', 'No se recibió SID de Twilio', $tipo_envio);
                }
            } catch (Exception $e) {
                $this->Notificaciones_model->update_notificacion_status($notificacion->id_notificacion, 'No enviado', $tipo_envio,  $e->getMessage());
            }
        }
    }

    private function personalizar_mensaje($mensaje, $cita): string
    {
        $fecha = isset($cita->fecha_hora_inicio) ? date('Y-m-d', strtotime($cita->fecha_hora_inicio)) : 'Sin Datos';
        $hora = isset($cita->fecha_hora_inicio) ? date('H:i', strtotime($cita->fecha_hora_inicio)) : 'Sin Datos';

        $nombreFormateado = isset($cita->cliente_nombre) ? ucwords(strtolower($cita->cliente_nombre)) : 'Estimado Cliente';

        $variables = [
            '{{nombre_usuario}}' => $nombreFormateado,
            '{{fecha}}' => $fecha,
            '{{hora}}' => $hora
        ];


        foreach ($variables as $key => $value) {
            $mensaje = str_replace($key, $value, $mensaje);
        }

        return $mensaje;
    }
}
