<?php

class Notificaciones_model extends CI_Model
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
            notificaciones.id_notificacion,
            notificaciones.estatus,
            notificaciones.estatus6meses,
            notificaciones.fecha_hora_envio,
            notificaciones.fecha_hora_envio_6meses,
            citas.fecha_hora_inicio,
            clientes.nombre,
            clientes.apellidos,
            servicios.nombre_servicio
        ');
		$this->db->distinct();
        $this->db->from('notificaciones');
        $this->db->join('citas', 'notificaciones.id_cita = citas.id_cita');
        $this->db->join('clientes', 'citas.id_cliente = clientes.id_cliente');
        $this->db->join('servicios', 'citas.id_servicio = servicios.id_servicio');
        $this->db->group_start();
        $this->db->where_in('notificaciones.estatus', $estatuses);
        $this->db->or_where_in('notificaciones.estatus6meses', $estatuses);
        $this->db->group_end();

        $this->db->group_start();
        $this->db->where('citas.estado', 'Programada');
        $this->db->or_where('citas.estado', 'Finalizado');
        $this->db->or_where('citas.estado', 'Anulada');
        $this->db->group_end();



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
            if ($param['filtro_estados'] == 'Enviados')
            {
                $estatus = 'Enviado';
            }else{
                $estatus = 'Programado para envio';
            }
            
            $this->db->where('notificaciones.estatus', $estatus);
        }

        $this->db->order_by('notificaciones.id_notificacion', 'DESC');
        $this->db->limit(100);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function leer_notificaciones_no_enviadas(array $param = []): array
    {
        $this->db->select('
            notificaciones.id_notificacion,
            notificaciones.estatus,
            notificaciones.estatus6meses,
            notificaciones.fecha_hora_envio,
            notificaciones.fecha_hora_envio_6meses,
            notificaciones.motivo_fallido,
            notificaciones.motivo_fallido_6meses,
            citas.fecha_hora_inicio,
            clientes.nombre,
            clientes.apellidos,
            servicios.nombre_servicio
        ');
        $this->db->from('notificaciones');
        $this->db->join('citas', 'notificaciones.id_cita = citas.id_cita');
        $this->db->join('clientes', 'citas.id_cliente = clientes.id_cliente');
        $this->db->join('servicios', 'citas.id_servicio = servicios.id_servicio');
        $this->db->where('notificaciones.estatus', 'No enviado');
        $this->db->or_where('notificaciones.estatus6meses', 'No enviado');
        $this->db->group_start();
        $this->db->where('citas.estado', 'Programada');
        $this->db->or_where('citas.estado', 'Finalizado');
        $this->db->or_where('citas.estado', 'Anulada');
        $this->db->group_end();


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

        $this->db->order_by('notificaciones.id_notificacion', 'DESC');
        $this->db->limit(100);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function tipo_recordatorio(): array
    {
        $this->db->select('id_frecuenciaEnvio, tipo_envio, esta_activo, mensaje_personalizado');
        $this->db->from('frecuencia_envio');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function update_ajustes(int $id_frecuenciaEnvio, bool $esta_activo, string $mensaje_personalizado): void
    {
        $data = [
            'esta_activo' => $esta_activo,
            'mensaje_personalizado' => $mensaje_personalizado
        ];

        $this->db->where('id_frecuenciaEnvio', $id_frecuenciaEnvio);
        $this->db->update('frecuencia_envio', $data);
    }


   
    private function puede_insertar_notificacion(int $id_cita): bool
    {
        $this->db->select('id_cliente, DATE(fecha_hora_inicio) as fecha_cita');
        $this->db->where('id_cita', $id_cita);
        $query_cita = $this->db->get('citas');
        $cita = $query_cita->row();

        if ($cita) {
            $this->db->from('citas');
            $this->db->where('id_cliente', $cita->id_cliente);
            $this->db->where('DATE(fecha_hora_inicio)', $cita->fecha_cita);
            $count_citas = $this->db->count_all_results();
            return $count_citas <= 1;
        }
        return false;
    }

    public function insertar_notificaciones(int $id_cita, bool $creacionNoExiste = false): void
    {
        if ($this->puede_insertar_notificacion($id_cita) && !$creacionNoExiste ) {
            $data_notificacion = array(
                'id_cita' => $id_cita,
                'estatus' => 'Programado para envio',
                'motivo_fallido' => null,
            );
            $this->db->insert('notificaciones', $data_notificacion);
			return;
        }

        if ($creacionNoExiste) {
            $data_notificacion = array(
                'id_cita' => $id_cita,
                'estatus' => 'Programado para envio',
                'motivo_fallido' => null,
            );
            $this->db->insert('notificaciones', $data_notificacion);
        }
    }



    public function get_cita_info(int $id_cita)
    {
        $this->db->select('citas.id_cita, clientes.nombre as cliente_nombre, clientes.apellidos as cliente_apellido, clientes.telefono as cliente_telefono, citas.fecha_hora_inicio, servicios.nombre_servicio as servicio_nombre');
        $this->db->from('citas');
        $this->db->join('clientes', 'clientes.id_cliente = citas.id_cliente');
        $this->db->join('servicios', 'servicios.id_servicio = citas.id_servicio');
        $this->db->where('citas.id_cita', $id_cita);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_frecuencia_envio(int $id_frecuenciaEnvio)
    {
        $this->db->select('mensaje_personalizado');
        $this->db->from('frecuencia_envio');
        $this->db->where('id_frecuenciaEnvio', $id_frecuenciaEnvio);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_notificacion_by_cita(int $id_cita)
    {
        $this->db->select('id_notificacion');
        $this->db->from('notificaciones');
        $this->db->where('id_cita', $id_cita);
        $query = $this->db->get();
        return $query->row();
    }

    public function update_notificacion_status(int $id_notificacion, string $status, int $tipo_envio,  string $motivo_fallido = null): void
    {
        $data = array();


        if ($tipo_envio == 1) {
            $data = array(
                'estatus' => $status,
                'motivo_fallido' => $motivo_fallido,
                'fecha_hora_envio' => date('Y-m-d H:i:s')
            );
        } else if ($tipo_envio == 2) {
            $data = array(
                'estatus6meses' => $status,
                'motivo_fallido_6meses' => $motivo_fallido,
                'fecha_hora_envio_6meses' => date('Y-m-d H:i:s')
            );
        }

        $this->db->where('id_notificacion', $id_notificacion);
        $this->db->update('notificaciones', $data);
    }

    public function verificar_y_actualizar_notificaciones(array $citas_proximas): void
    {
        foreach ($citas_proximas as $cita) {
            $this->db->from('notificaciones');
            $this->db->where('id_cita', $cita->id_cita);
            $exist = $this->db->count_all_results();

            if ($exist == 0) {
                $this->insertar_notificaciones($cita->id_cita, true); 
            }
        }
    }


    public function obtener_citas_proximas()
    {
        $now = new DateTime();
        $now->modify('+1 day');

        $toleranceEnd = clone $now;
        $toleranceStart = clone $now;
        $toleranceStart->modify('-2 minutes');
        $toleranceStart->setTime((int) $toleranceStart->format('H'), (int) $toleranceStart->format('i'), 0);
        if (!$this->esta_frecuencia_activa(1)) {
            return [];
        }

        $this->db->select('MIN(id_cita) AS id_cita');
        $this->db->from('citas');
        $this->db->where('estado', 'Programada');
        $this->db->where('fecha_hora_inicio >=', $now->format('Y-m-d 00:00:00'));
        $this->db->where('fecha_hora_inicio <=', $now->format('Y-m-d 23:59:59'));
        $this->db->group_by('id_cliente');
        $this->db->order_by('fecha_hora_inicio', 'ASC');
        $subQuery = $this->db->get_compiled_select(); 


        $this->db->select('*');
        $this->db->from("($subQuery) AS sub"); 
        $this->db->join('citas', 'citas.id_cita = sub.id_cita');
		$this->db->join('notificaciones', 'citas.id_cita = notificaciones.id_cita', 'left');
        $this->db->where('notificaciones.estatus NOT IN ("Enviado", "No enviado")');
        $this->db->where('citas.fecha_hora_inicio <=', $toleranceEnd->format('Y-m-d H:i:s'));
        $this->db->where('citas.fecha_hora_inicio >=', $toleranceStart->format('Y-m-d H:i:s'));

        $query = $this->db->get();
        $consultas = $query->result();
        $this->verificar_y_actualizar_notificaciones($consultas);
        return $consultas;
    }

    public function obtener_citas_a_seis_meses()
    {
        $now = new DateTime();
        $now->modify('-6 months');
        $toleranceStart = clone $now;
        $toleranceEnd = clone $now;
        $toleranceStart->modify('-2 minutes');

        if (!$this->esta_frecuencia_activa(2)) {
            return [];
        }

        $this->db->select('citas.id_cita');
        $this->db->from('citas');
        $this->db->join('notificaciones', 'notificaciones.id_cita = citas.id_cita');
        $this->db->group_start();
        $this->db->where('citas.estado', 'Programada');
        $this->db->or_where('citas.estado', 'Finalizado');
        $this->db->group_end();
        $this->db->where('citas.fecha_hora_inicio <=', $toleranceEnd->format('Y-m-d H:i:s'));
        $this->db->where('citas.fecha_hora_inicio >=', $toleranceStart->format('Y-m-d H:i:s'));

        $query = $this->db->get();
        return $query->result();
    }


    private function esta_frecuencia_activa(int $id_frecuenciaEnvio)
    {
        $this->db->select('esta_activo');
        $this->db->from('frecuencia_envio');
        $this->db->where('id_frecuenciaEnvio', $id_frecuenciaEnvio);
        $query = $this->db->get();
        $result = $query->row();
        return $result && $result->esta_activo;
    }
}
