<?php

/* CHAINS 20240917 - ALTER TABLE `frecuencia_envio_evaluacion_google` ADD `no_repetir_dias` INT NOT NULL DEFAULT '90' AFTER `mensaje_personalizado`;
 *
 */
class EvaluacionesGoogle_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('AqConexion_model');
		date_default_timezone_set('Europe/Madrid');
    }


    public function leer_notificaciones(array $param = []): array
    {
        $estatuses = ['Enviado', 'Programado para envio'];
        $this->db->select('
            evaluacion_google.id_evaluacion,
            evaluacion_google.estatus,
            evaluacion_google.fecha_hora_envio,
            dietario.fecha_pagado,
            clientes.nombre,
            clientes.apellidos,
            servicios.nombre_servicio,
            centros.nombre_centro
        ');
        $this->db->from('evaluacion_google');
        $this->db->join('citas', 'evaluacion_google.id_cita = citas.id_cita');
        $this->db->join('clientes', 'citas.id_cliente = clientes.id_cliente');
        $this->db->join('servicios', 'citas.id_servicio = servicios.id_servicio');
        $this->db->join('dietario', 'citas.id_cita = dietario.id_cita');
        $this->db->join('usuarios', 'citas.id_usuario_empleado = usuarios.id_usuario');
        $this->db->join('centros', 'usuarios.id_centro = centros.id_centro');
        $this->db->where_in('evaluacion_google.estatus', $estatuses);


        // Aplicar filtros si están presentes
        if (!empty($param['fecha_desde'])) {
            $fecha_desde = date('Y-m-d', strtotime($param['fecha_desde'])) . ' 00:00:00';
            $this->db->where('citas.fecha_hora_inicio >=', $fecha_desde);
        }
        if (!empty($param['fecha_hasta'])) {
            $fecha_hasta = date('Y-m-d', strtotime($param['fecha_hasta'])) . ' 23:59:59';
            $this->db->where('citas.fecha_hora_inicio <=', $fecha_hasta);
        }
        if (!empty($param['id_cliente'])) {
            $this->db->where('citas.id_cliente', $param['id_cliente']);
        }

        if (!empty($param['filtro_estados'])) {
            if ($param['filtro_estados'] == 'Enviados') {
                $estatus = 'Enviado';
            } else {
                $estatus = 'Programado para envio';
            }

            $this->db->where('evaluacion_google.estatus', $estatus);
        }

        $this->db->order_by('evaluacion_google.id_evaluacion', 'DESC');
        $this->db->limit(100);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function leer_notificaciones_no_enviadas(array $param = []): array
    {
        $this->db->select('
            evaluacion_google.id_evaluacion,
            evaluacion_google.estatus,
            evaluacion_google.motivo_fallido,
            evaluacion_google.fecha_hora_envio,
            dietario.fecha_pagado,
            clientes.nombre,
            clientes.apellidos,
            servicios.nombre_servicio,
            centros.nombre_centro
        ');
        $this->db->from('evaluacion_google');
        $this->db->join('citas', 'evaluacion_google.id_cita = citas.id_cita');
        $this->db->join('clientes', 'citas.id_cliente = clientes.id_cliente');
        $this->db->join('servicios', 'citas.id_servicio = servicios.id_servicio');
        $this->db->join('dietario', 'citas.id_cita = dietario.id_cita');
        $this->db->join('usuarios', 'citas.id_usuario_empleado = usuarios.id_usuario');
        $this->db->join('centros', 'usuarios.id_centro = centros.id_centro');
        $this->db->where('evaluacion_google.estatus', 'No enviado');



        if (!empty($param['fecha_desde'])) {
            $fecha_desde = date('Y-m-d', strtotime($param['fecha_desde'])) . ' 00:00:00';
            $this->db->where('citas.fecha_hora_inicio >=', $fecha_desde);
        }
        if (!empty($param['fecha_hasta'])) {
            $fecha_hasta = date('Y-m-d', strtotime($param['fecha_hasta'])) . ' 23:59:59';
            $this->db->where('citas.fecha_hora_inicio <=', $fecha_hasta);
        }
        if (!empty($param['id_cliente'])) {
            $this->db->where('citas.id_cliente', $param['id_cliente']);
        }

        $this->db->order_by('evaluacion_google.id_evaluacion', 'DESC');
        $this->db->limit(100);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function tipo_recordatorio(): array
    {
        $this->db->select('id_frecuenciaEnvio, esta_activo, mensaje_personalizado,no_repetir_dias');
        $this->db->from('frecuencia_envio_evaluacion_google');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function update_ajustes(int $id_frecuenciaEnvio, bool $esta_activo, string $mensaje_personalizado,
                                int $norepetirdias=90): void
    {
        $data = [
            'esta_activo' => $esta_activo,
            'mensaje_personalizado' => $mensaje_personalizado,
            'no_repetir_dias'=>$norepetirdias
        ];

        $this->db->where('id_frecuenciaEnvio', $id_frecuenciaEnvio);
        $this->db->update('frecuencia_envio_evaluacion_google', $data);
    }


    private function puede_insertar_notificacion(int $id_dietario): bool
    {
        $this->db->select('dietario.id_cliente, dietario.fecha_pagado, citas.id_cita');
        $this->db->from('dietario');
        $this->db->join('citas', 'citas.id_cita = dietario.id_cita');
        $this->db->where('dietario.id_dietario', $id_dietario);
        $query_dietario = $this->db->get();
        $dietario = $query_dietario->row();

        if ($dietario) {
            $fecha_pago = date('Y-m-d', strtotime($dietario->fecha_pagado));
            $this->db->from('dietario');
            $this->db->join('citas', 'citas.id_cita = dietario.id_cita');
            $this->db->where('citas.id_cliente', $dietario->id_cliente);
            $this->db->where('dietario.estado', 'Pagado');
            $this->db->where('DATE(dietario.fecha_pagado)', $fecha_pago);
            $count_notificaciones = $this->db->count_all_results();

            return $count_notificaciones <= 1;
        }
        return false;
    }



    private function noExisteEvaluacionGoogle(int $id_cita): bool
    {
        $this->db->from('evaluacion_google');
        $this->db->where('id_cita', $id_cita);
        $count = $this->db->count_all_results();
        return $count == 0;
    }

    // CHAINS 20240918
    private function cumpleLimiteTemporalMaximo($id_cliente): bool
    {
        // return true;

        $max=0;
        $recordatorio=$this->tipo_recordatorio();
        if($recordatorio){
            $max=$recordatorio[0]['no_repetir_dias'];
        }

        $now = new DateTime();

        $toleranceStart = clone $now;
        $toleranceStart->modify('-'.$max.' days');


        $this->db->select('estado, tipo_pago, id_cita, id_centro');
        $this->db->from('evaluacion_google');
        $this->db->join('citas','citas.id_cita = evaluacion_google.id_cita');
        $this->db->where('citas.id_cliente', $id_cliente);
        $this->db->where_in('evaluacion_google.estatus',['Enviado','Programado para envio']);
        $this->db->where('evaluacion_google.fecha_hora_envio >=', $toleranceStart->format('Y-m-d H:i:s'));
        $count = $this->db->count_all_results();

        return $count == 0;
    }





    public function dietarioPagado(int $id_dietario): void
    {
        $this->db->select('estado, tipo_pago, id_cita, id_centro,id_cliente');
        $this->db->from('dietario');
        $this->db->where('id_dietario', $id_dietario);
        $query = $this->db->get();

        // Verificar si el dietario existe y si el estado es 'Pagado'
        if ($query->num_rows() > 0) {
            $dietario = $query->row();

            if (!empty($dietario->id_cita) && !empty($dietario->id_cliente)) {
                // Verificar si se puede insertar la notificación con el estado en pagado y que no sea saldo_cuenta
                if ($dietario->estado == 'Pagado'  && $this->puede_insertar_notificacion($id_dietario)  && $this->noExisteEvaluacionGoogle($dietario->id_cita)
                        // CHAINS 20240918
                        && $this->cumpleLimiteTemporalMaximo($dietario->id_cliente)
                    ) {
                    $data_notificacion = array(
                        'id_cita' => $dietario->id_cita,
                        'estatus' => 'Programado para envio',
                        'motivo_fallido' => null,
                    );
                    $this->db->insert('evaluacion_google', $data_notificacion);
                }
            }
        }
    }

    public function get_cita_info(int $id_cita)
    {
        $this->db->select('citas.id_cita, clientes.nombre as cliente_nombre, clientes.telefono as cliente_telefono, centros.link_formulario_evaluacion');
        $this->db->from('dietario');
        $this->db->join('citas', 'dietario.id_cita = citas.id_cita');
        $this->db->join('usuarios', 'usuarios.id_usuario = citas.id_usuario_empleado');
        $this->db->join('clientes', 'clientes.id_cliente = citas.id_cliente');
        $this->db->join('centros', 'centros.id_centro = usuarios.id_centro');
        $this->db->where('citas.id_cita', $id_cita);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_frecuencia_envio(int $id_frecuenciaEnvio)
    {
        $this->db->select('mensaje_personalizado');
        $this->db->from('frecuencia_envio_evaluacion_google');
        $this->db->where('id_frecuenciaEnvio', $id_frecuenciaEnvio);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_notificacion_by_cita(int $id_cita)
    {
        $this->db->select('id_evaluacion');
        $this->db->from('evaluacion_google');
        $this->db->where('id_cita', $id_cita);
        $query = $this->db->get();
        return $query->row();
    }


    public function update_notificacion_status(int $id_notificacion, string $status,  string $motivo_fallido = null): void
    {

        $data = array(
            'estatus' => $status,
            'motivo_fallido' => $motivo_fallido,
            'fecha_hora_envio' => date('Y-m-d H:i:s')
        );
        $this->db->where('id_evaluacion', $id_notificacion);
        $this->db->update('evaluacion_google', $data);
    }



    public function obtener_citas_proximas()
    {
        $now = new DateTime();

        $toleranceStart = clone $now;
        $toleranceStart->modify('-2 hours');
        $toleranceEnd = clone $toleranceStart;
        $toleranceStart->modify('-5 minutes');
        $toleranceStart->setTime((int) $toleranceStart->format('H'), (int) $toleranceStart->format('i'), 0);

        if (!$this->esta_frecuencia_activa(1)) {
            return [];
        }

        $this->db->select('dietario.id_cita');
        $this->db->from('dietario');
        $this->db->join('citas', 'dietario.id_cita = citas.id_cita');
        $this->db->join('evaluacion_google', 'evaluacion_google.id_cita = citas.id_cita');
        $this->db->where('evaluacion_google.estatus', 'Programado para envio');
        $this->db->where('dietario.fecha_pagado <=', $toleranceEnd->format('Y-m-d H:i:s'));
        $this->db->where('dietario.fecha_pagado >=', $toleranceStart->format('Y-m-d H:i:s'));

        $query = $this->db->get();
        $consultas = $query->result();
        return $consultas;
    }




    private function esta_frecuencia_activa(int $id_frecuenciaEnvio)
    {
        $this->db->select('esta_activo');
        $this->db->from('frecuencia_envio_evaluacion_google');
        $this->db->where('id_frecuenciaEnvio', $id_frecuenciaEnvio);
        $query = $this->db->get();
        $result = $query->row();
        return $result && $result->esta_activo;
    }
}
