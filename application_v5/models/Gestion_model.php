<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Gestion_model extends CI_Model
{

    function get_resumen_presupuestos($centro, $mes, $ano)
    {

        if ($centro != 0) {
            $centros = $this->db->select("*,'' as data,'' as detalle,'' as presupuestos")
                ->where("id_centro!=", 1)
                ->where("id_centro", $centro)->get("centros")->result();
            $this->db->where("id_centro", $centro);
        } else {
            $centros = $this->db->select("*,'' as data,'' as detalle,'' as presupuestos")
                ->where("id_centro!=", 1)
                ->get("centros")->result();
        }

        $presupuestos = $this->db->select("id_centro,estado,count(*) as cantidad, sum(totalpresupuesto) as total")
            ->where("month(fecha_creacion)", $mes)
            ->where("year(fecha_creacion)", $ano)
            ->where("borrado", 0)
            ->where("totalpresupuesto >", 0)
            ->group_by("id_centro,estado")
            ->get("presupuestos")->result();
        //echo $this->db->last_query();
        //show_array($presupuestos);exit;
        foreach ($centros as &$c) {
            $c->data = array();
            $c->detalle = array();
            foreach ($presupuestos as $p) if ($p->id_centro == $c->id_centro) {
                $c->data[] = $p;
                $c->detalle = $this->get_detalle_presupuestos($c->id_centro, $mes, $ano);
                $c->presupuestos = $this->get_presupuestos($c->id_centro, $mes, $ano);
            }
        }

        return $centros;
    }
    function get_detalle_presupuestos($centro, $mes, $ano)
    {

        $presupuestos = $this->db->select('id_presupuesto')
            ->where("month(fecha_creacion)", $mes)
            ->where("year(fecha_creacion)", $ano)
            ->where("borrado", 0)
            ->where("id_centro", $centro)
            ->get("presupuestos")->result();
        $a_presupuestos = array();
        foreach ($presupuestos as $p) {
            $a_presupuestos[] = $p->id_presupuesto;
        }
        $a_presupuestos[] = -1;

        return $this->db->select("id_presupuesto_item,dto,(presupuestos_items.pvp-presupuestos_items.dto_euros) as total,citas.estado,servicios.id_familia_servicio,nombre_familia")
            ->join("citas", "presupuestos_items.id_cita=citas.id_cita", "LEFT")
            ->join("servicios", "citas.id_servicio = servicios.id_servicio", "LEFT")
            ->join("servicios_familias", "servicios_familias.id_familia_servicio = servicios.id_familia_servicio", "LEFT")
            ->where("month(presupuestos_items.fecha_creacion)", $mes)
            ->where("year(presupuestos_items.fecha_creacion)", $ano)
            ->where_in("id_presupuesto", $a_presupuestos)
            ->where("presupuestos_items.borrado", 0)
            ->get("presupuestos_items")->result();
        //echo $this->db->last_query();
        //show_array($presupuestos);exit;
    }
    function get_familias_servicios()
    {
        return $this->db->select("id_familia_servicio,nombre_familia,'0' as total")->order_by('nombre_familia')->get('servicios_familias')->result();
    }
    function produccion_citas($parametros)
    {
        $parametros = array(
            "mes" => $parametros['mes'],
            "ano" => $parametros['ano'],
            "id_centro" => $parametros['id_centro'],
        );
        $AqConexion_model = new AqConexion_model();
        $busqueda = " AND C.estado = 'Finalizado' AND (dietario.estado = 'Pagado' OR dietario.estado = 'Presupuesto')";
        if (isset($parametros['id_centro'])) {
            if ($parametros['id_centro'] > 0) {
                $busqueda .= " AND dietario.id_centro = @id_centro ";
            }
        }

        if (isset($parametros['mes'])) {
            if ($parametros['mes'] != "") {
                $busqueda .= " AND MONTH(C.fecha_hora_inicio) = @mes  AND YEAR(C.fecha_hora_inicio) = @ano ";
            }
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT 
        C.id_cita,
        C.id_servicio,
        C.id_usuario_empleado,
        C.id_cliente,
        C.estado, 
        C.fecha_hora_inicio,
        CONCAT(usuarios.nombre,' ' ,usuarios.apellidos) AS empleado,
        servicios.pvp as pvp,
        servicios.templos,
        dietario.importe_euros as importe_euros,
        (servicios.pvp - dietario.importe_euros) AS descontado
        FROM citas AS C
        LEFT JOIN servicios on servicios.id_servicio = C.id_servicio
        LEFT JOIN usuarios on usuarios.id_usuario = C.id_usuario_empleado
        LEFT JOIN dietario on dietario.id_cita = C.id_cita
        WHERE C.borrado = 0 " . $busqueda . " GROUP BY C.id_cita ORDER BY C.fecha_hora_inicio";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);
        return $datos;
    }
    function doctores_con_cita($id_centro, $mes, $ano)
    {
        if ($id_centro > 0) {
            $this->db->where("usuarios.id_centro", $id_centro);
        }
        $doctores = $this->db->select("id_usuario_empleado,CONCAT(usuarios.nombre,' ' ,usuarios.apellidos) AS doctor, '0' as facturado")
            ->join('usuarios', "usuarios.id_usuario = citas.id_usuario_empleado")
            ->join('usuarios_perfiles', "usuarios_perfiles.id_usuario = citas.id_usuario_empleado")
            ->where("estado", 'Finalizado')
            ->where("MONTH(citas.fecha_hora_inicio)", $mes)
            ->where("YEAR(citas.fecha_hora_inicio)", $ano)
            ->where('usuarios_perfiles.id_perfil', '6')
            ->group_by('id_usuario_empleado')
            ->order_by('usuarios.nombre')
            ->get('citas')->result();
        $id_doctores = array();
        foreach ($doctores as $doc) {
            $id_doctores[] = $doc->id_usuario_empleado;
        }
        if (count($id_doctores) > 0) {
            $facturas = $this->db->select("id_doctor,sum(total_factura) as total")
                ->where_in('id_doctor', $id_doctores)
                ->where("MONTH(fecha_factura)", $mes)
                ->where("YEAR(fecha_factura)", $ano)
                ->where("borrado", 0)
                ->group_by('id_doctor')
                ->get('gestion_facturas')->result();
            foreach ($doctores as &$doc) foreach ($facturas as $f) if ($f->id_doctor == $doc->id_usuario_empleado) {
                $doc->facturado += $f->total;
            }
        }

        return $doctores;
    }
    function get_presupuestos($centro, $mes, $ano)
    {
        $presupuestos =  $this->db->select("presupuestos.*, clientes.fecha_creacion as creacion_cliente,'0' as frecuente")
            ->join('clientes', 'presupuestos.id_cliente=clientes.id_cliente', 'left')
            ->where("month(presupuestos.fecha_creacion)", $mes)
            ->where("year(presupuestos.fecha_creacion)", $ano)
            ->where("presupuestos.borrado", 0)
            ->where("id_centro!=", 1)
            ->where("id_centro", $centro)
            ->get("presupuestos")->result();
        foreach ($presupuestos as &$p) {
            if (explode(" ", $p->fecha_creacion)[0] == explode(" ", $p->creacion_cliente)[0]) {
                $p->frecuente = 0;
            } else {
                $p->frecuente = 1;
            }
        }
        return $presupuestos;
    }

    function get_presupuestos_doctores($centro, $mes, $ano)
    {
        if ($centro != 0) {
            $this->db->where("presupuestos.id_centro", $centro);
        }

        $presupuestos =  $this->db->select("presupuestos.*, nombre, apellidos")
            ->join('usuarios', 'presupuestos.id_doctor=usuarios.id_usuario', 'left')
            ->where("month(presupuestos.fecha_creacion)", $mes)
            ->where("year(presupuestos.fecha_creacion)", $ano)
            ->where("presupuestos.borrado", 0)
            ->where("presupuestos.id_centro!=", 1)
            ->get("presupuestos")->result();
        $id_doctores = array();
        foreach ($presupuestos as $p) {
            $id_doctores[] = $p->id_doctor;
        }
        $doctores = array();
        if (count($id_doctores) > 0) {
            $doctores = $this->db->where_in("id_usuario", $id_doctores)->get("usuarios")->result();
        }

        return array("doctores" => $doctores, "presupuestos" => $presupuestos);
    }

    function presupuestos_anuales($centro, $ano)
    {
        if ($centro != 0) {
            $centros = $this->db->select("*,'' as data,'' as detalle,'' as presupuestos")
                ->where("id_centro!=", 1)
                ->where("id_centro", $centro)->get("centros")->result();
            $this->db->where("id_centro", $centro);
        } else {
            $centros = $this->db->select("*,'' as data,'' as detalle,'' as presupuestos")
                ->where("id_centro!=", 1)
                ->get("centros")->result();
        }

        $presupuestos = $this->db->select("id_centro,month(fecha_creacion) as mes,estado,count(*) as cantidad, sum(totalpresupuesto) as total")
            ->where("year(fecha_creacion)", $ano)
            ->where("borrado", 0)
            ->where("totalpresupuesto >", 0)
            ->group_by("id_centro,month(fecha_creacion), estado")
            ->get("presupuestos")->result();
        //echo $this->db->last_query();
        //show_array($presupuestos);exit;
        foreach ($centros as &$c) {
            $c->data = array();
            $c->detalle = array();
            foreach ($presupuestos as $p) if ($p->id_centro == $c->id_centro) {
                $c->data[] = $p;
            }
        }

        return $centros;
    }
    // OBJETIVOS ***************************************************************************************
    //alfonso - get objetivos ***************************************************************************************
    function get_objetivos()
    {
        return $this->db->join('centros', 'objetivos.id_centro=centros.id_centro')->where('objetivos.borrado', 0)->order_by('ano,mes')->get('objetivos')->result();
    }
    //alfonso : insertar objetivo
    function insertar_objetivo($data)
    {
        //verificar que no exista
        $cantidad = $this->db->where('borrado', 0)
            ->where('id_centro', $data['id_centro'])
            ->where('ano', $data['ano'])
            ->where('mes', $data['mes'])
            ->get('objetivos')->num_rows();
        if ($cantidad > 0) {
            return 0;
        } else { //insertamos en caso de que no exista
            $data['fecha_creacion'] = date('Y-m-d H:i:s');
            $data['borrado'] = 0;
            $data['id_usuario'] = $this->session->userdata('id_usuario');
            $this->db->insert('objetivos', $data);
            return 1;
        }
    }
    function editar_objetivo($data)
    {
        $id_objetivo = $data['id_objetivo'];
        unset($data['id_objetivo']);
        $this->db->where('id_objetivo', $id_objetivo)->update('objetivos', $data);
    }
    function borrar_objetivo($id_objetivo)
    {
        $this->db->where('id_objetivo', $id_objetivo)
            ->update(
                'objetivos',
                array(
                    'borrado' => 1,
                    'fecha_modificacion' => date('Y-m-d H:i:s')
                )
            );
    }
    // OBJETIVOS ***************************************************************************************


    public function ctrlGLab($param)
    {
        $this->db->select('liquidaciones_citas.*, servicios.nombre_servicio,(CONCAT(usuarios.nombre, " ", usuarios.apellidos)) AS usuario, presupuestos_items.dientes as dientes');
        
        if(isset($param['fecha_desde']) && $param['fecha_desde'] != ''){
            $this->db->where('liquidaciones_citas.fecha_cita >=', $param['fecha_desde'].' 00:00:00');
        }
        if(isset($param['fecha_hasta']) && $param['fecha_hasta'] != ''){
            $this->db->where('liquidaciones_citas.fecha_cita <=', $param['fecha_hasta'].' 23:59:59');
        }
        if(isset($param['estado']) && $param['estado'] != ''){
            $this->db->where('liquidaciones_citas.estado', $param['estado']);
        }
        if(isset($param['id_cliente']) && $param['id_cliente'] != ''){
            $this->db->where('liquidaciones_citas.id_cliente', $param['id_cliente']);
        }
        if(isset($param['id_usuario']) && $param['id_usuario'] != ''){
            $this->db->where('liquidaciones_citas.id_usuario', $param['id_usuario']);
        }

        $palabras_clave = array('implante', 'corona', 'sobredentadura', 'protesis', 'hueso', 'membrana', 'chincheta', 'ferula', 'entrada', 'laboratorio');
		$palabras_clave = implode(',', $palabras_clave);
        
        $this->db->where('liquidaciones_citas.borrado',0);

        $this->db->group_start();
            $this->db->like('servicios.nombre_servicio', $palabras_clave);
		    $this->db->or_where('liquidaciones_citas.gastos_lab >', 0);
        $this->db->group_end();

		$this->db->join('servicios','servicios.id_servicio = liquidaciones_citas.id_item','left');
        $this->db->join('usuarios','usuarios.id_usuario = liquidaciones_citas.id_usuario','left');
        $this->db->join('presupuestos_items','presupuestos_items.id_presupuesto_item = liquidaciones_citas.id_presupuesto_item','left');
        $this->db->group_by('liquidaciones_citas.id_liquidacion_cita');
        $this->db->order_by('liquidaciones_citas.id_cliente','asc');
		$this->db->order_by('liquidaciones_citas.fecha_cita','asc');
        $citas = $this->db->get('liquidaciones_citas')->result_array();
        $clientes = [];
        $total['pvp'] = 0;
		$total['dto'] = 0;
		$total['dtop'] = 0;
		$total['gastos_lab'] = 0;
		$total['total'] = 0;
        foreach ($citas as $key => $value) {
            if(!array_key_exists($value['id_cliente'], $clientes)){
                $client = $this->db->where('id_cliente', $value['id_cliente'])->get('clientes')->row_array();
                $clientes[$value['id_cliente']] = $client;
                $clientes[$value['id_cliente']]['dientes'] = $value['dientes'];
                $clientes[$value['id_cliente']]['pvp'] = 0;
				$clientes[$value['id_cliente']]['dto'] = 0;
				$clientes[$value['id_cliente']]['dtop'] = 0;
				$clientes[$value['id_cliente']]['gastos_lab'] = 0;
				$clientes[$value['id_cliente']]['total'] = 0;
            }
            $clientes[$value['id_cliente']]['citas'][] = $value;
            $clientes[$value['id_cliente']]['pvp'] += $value['pvp'];
			$clientes[$value['id_cliente']]['dto'] += $value['dto'];
			$clientes[$value['id_cliente']]['dtop'] += $value['dtop'];
			$clientes[$value['id_cliente']]['gastos_lab'] += $value['gastos_lab'];
			$clientes[$value['id_cliente']]['total'] += $value['total'];
            // para el total
            $total['pvp'] += $value['pvp'];
			$total['dto'] += $value['dto'];
			$total['dtop'] += $value['dtop'];
			$total['gastos_lab'] += $value['gastos_lab'];
			$total['total'] += $value['total'];
        }
        return ['total' => $total, 'clientes' => $clientes];
    }
}


/* End of file ModelName.php */
