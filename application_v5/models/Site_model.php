<?php
class Site_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function leer_clientes_especial($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['id_cliente'])) {
            $busqueda .= " AND id_cliente = @id_cliente ";
        }

        if (isset($parametros['id_centro'])) {
            $busqueda .= " AND id_centro = @id_centro ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT id_cliente,nombre,apellidos
    FROM zz_clientes_especiales
    WHERE 1=1 " . $busqueda;
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function leer_tareas_diarias($param)
    {
        $this->db->select('tareas.*');
        if (isset($param['id_usuario'])) {
            $this->db->where('tareas_asignados.id_usuario', $param['id_usuario']);
        }
        if (isset($param['id_creador'])) {
            $this->db->where('tareas_asignados.id_creador', $param['id_creador']);
        }
        $this->db->where('tareas.fecha_creacion <=', $param['fecha'] . ' 23:59:59');
        $this->db->where('tareas.estado', 'Pendiente');
        $this->db->where('borrado', 0);
        $this->db->join('tareas_asignados', 'tareas_asignados.id_tarea = tareas.id');
        $return =  $this->db->get('tareas')->result();
        return $return;
    }

    function leer_rellamadas_diarias($param)
    {
        $this->db->select('rellamadas.*, (CONCAT(clientes.nombre, " ", clientes.apellidos)) AS cliente, clientes.telefono,servicios.nombre_servicio,(CONCAT(usuarios.nombre, " ", usuarios.apellidos)) AS empleado,centros.nombre_centro');
        if (isset($param['id_centro'])) {
            $this->db->where('rellamadas.id_centro', $param['id_centro']);
        }
        if (isset($param['id_usuario'])) {
            $this->db->where('rellamadas.id_usuario_creacion', $param['id_usuario']);
        }
        $this->db->where('rellamadas.fecha_rellamada <=', $param['fecha'] . ' 23:59:59');
        $this->db->where('rellamadas.estado', 'pendiente');
        $this->db->where('rellamadas.borrado', 0);
        $this->db->join('clientes', 'clientes.id_cliente = rellamadas.id_cliente');
        $this->db->join('servicios', 'rellamadas.id_servicio = servicios.id_servicio');
        $this->db->join('centros', 'rellamadas.id_centro = centros.id_centro');
        if (isset($param['id_usuario'])) {
            $this->db->join('usuarios', 'rellamadas.id_usuario_creacion = usuarios.id_usuario');
        }
        if (isset($param['id_centro'])) {
            $this->db->join('usuarios', 'rellamadas.id_usuario_cita = usuarios.id_usuario');
        }
        $return =  $this->db->get('rellamadas')->result();
        return $return;
    }


    function leer_primeras_visitas($param)
    {
        $this->db->select("citas.*, 
                           CONCAT(clientes.nombre, ' ', clientes.apellidos) AS cliente, 
                           CONCAT(usuarios.nombre, ' ', usuarios.apellidos) AS empleado, 
                           CONCAT(confirmadores.nombre, ' ', confirmadores.apellidos) AS usuario_confirma");
    
        if (isset($param['id_centro'])) {
            $this->db->where('usuarios.id_centro', $param['id_centro']);
        }
        if (isset($param['fecha'])) {
            $this->db->where("DATE_FORMAT(citas.fecha_hora_inicio,'%Y-%m-%d')", $param['fecha']);
        }
        if (isset($param['mes'])) {
            $this->db->where("DATE_FORMAT(citas.fecha_hora_inicio,'%Y-%m')", $param['mes']);
        }
    
        $this->db->where('citas.borrado', 0);
        $this->db->where('citas.estado !=', 'Anulada');
        $this->db->where('citas.id_servicio', 15404);
    
        $this->db->join('clientes', 'clientes.id_cliente = citas.id_cliente');
        $this->db->join('servicios', 'citas.id_servicio = servicios.id_servicio');
        $this->db->join('usuarios', 'citas.id_usuario_empleado = usuarios.id_usuario');
        $this->db->join('centros', 'usuarios.id_centro = centros.id_centro');
        $this->db->join('usuarios AS confirmadores', 'citas.id_user_conf = confirmadores.id_usuario', 'left');
    
        $this->db->group_by('citas.id_cliente');
        $this->db->order_by('citas.fecha_hora_inicio', 'asc');
        
        $return = $this->db->get('citas')->result();
        return $return;
    }

    

    function leer_gasto_mes($param)
    {
        // proveedores
        $this->db->where('borrado', 0);
        $this->db->where('id_tipo_proveedor', 13);
        $proveedores = $this->db->get('proveedores')->result();
        $ids_proveedores = [];
        foreach ($proveedores as $key => $prov) {
            $ids_proveedores[] = $prov->id_proveedor;
        }

        $this->db->where_not_in('id_proveedor', $ids_proveedores);

        if (isset($param['id_centro'])) {
            $this->db->where('centro_id', $param['id_centro']);
        }

        if (isset($param['year'])) {
            $this->db->where('YEAR(DATE(fecha_factura))', $param['year']);
        }

        if (isset($param['mes'])) {
            $this->db->where('MONTH(DATE(fecha_factura))', $param['mes']);
        }
        $this->db->select('COALESCE(SUM(total_factura),0) AS total_gasto, COUNT(id_gestion_facturas) as n_facturas');
        $this->db->where('borrado', 0);
        $result = $this->db->get('gestion_facturas')->row();
        return $result;
    }

    function leer_ingresos_mes($param)
    {
        if (isset($param['id_centro'])) {
            $this->db->where('id_centro', $param['id_centro']);
        }
        if (isset($param['year'])) {
            $this->db->where('YEAR(DATE(fecha_creacion))', $param['year']);
        }
        if (isset($param['mes'])) {
            $this->db->where('MONTH(DATE(fecha_creacion))', $param['mes']);
        }
        $this->db->select('COALESCE(SUM(importe_euros),0) AS total_ingreso, COUNT(id_dietario) as n_facturas');
        $this->db->where('borrado', 0);
        $this->db->where('pago_a_cuenta', 1);
        $result = $this->db->get('dietario')->row();
        return $result;
    }

    function leer_objetivos_mes($param)
    {
        if (isset($param['id_centro'])) {
            $this->db->where('id_centro', $param['id_centro']);
        }
        if (isset($param['year'])) {
            $this->db->where('ano', $param['year']);
        }
        if (isset($param['mes'])) {
            $this->db->where('mes', $param['mes']);
        }
        $this->db->where('borrado', 0);
        $objetivos = $this->db->get('objetivos')->result();
        $objetivo = (object) [
            'facturacion' => 0,
            'rentabilidad_euros' => 0,
            'rentabilidad' => 0
        ];
        foreach ($objetivos as $key => $ingreso) {
            $rentabilidad_euros = round($ingreso->facturacion * ($ingreso->rentabilidad / 100), 2);
            $objetivo->facturacion += $ingreso->facturacion;
            $objetivo->rentabilidad += $ingreso->rentabilidad;
            $objetivo->rentabilidad_euros += $rentabilidad_euros;
        }
        return $objetivo;
    }

    function confirmar_cita($param)
    {
        if (isset($param['post']['parent'])) {
            $data = array(
                'confirma' => 1
            );
    
            $this->db->where('id_cita', $param['post']['parent']);
    
            if ($this->db->update('citas', $data)) {
                $affected_rows = $this->db->affected_rows();
    
                if ($affected_rows > 0) {
                    $update_data = array(
                        'id_user_conf' => $param['id_usuario'],
                        'fecha_conf' => date('Y-m-d H:i:s')
                    );
    
                    $this->db->where('id_cita', $param['post']['parent']);
                    $this->db->update('citas', $update_data);
                }
    
                return $affected_rows;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
    
}
