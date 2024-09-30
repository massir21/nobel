<?php
/*
 * ALTER TABLE servicios ADD maxdescuento DECIMAL(6,2) DEFAULT 100 AFTER precio_proveedor ;
 */
class Rellamadas_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	// -------------------------------------------------------------------
	// ... SERVICIOS
	// -------------------------------------------------------------------
	function registrar_rellamadas_dia($parametros)
	{
		/* $parametros siempre la fecha y es posible tambien el centro*/
		$AqConexion_model = new AqConexion_model();
		$busqueda = "";
		if (isset($parametros['id_centro'])) {
			if ($parametros['id_centro'] > 0) {
				$busqueda .= " AND C.id_usuario_empleado in (select id_usuario from usuarios where id_centro = @id_centro) ";
			}
		}
		$busqueda .= " AND DATE_FORMAT(C.fecha_hora_inicio,'%Y-%m-%d') = @fecha  AND C.estado = 'Finalizado' ";
		// ... Leemos los registros
		$sentencia_sql = "SELECT 
			C.id_cliente,
			C.id_cita,
			DATE_FORMAT(C.fecha_hora_inicio,'%Y-%m-%d %H-%i-%s') as fecha_cita,
			C.id_usuario_empleado AS id_usuario_cita,
			usuarios.id_centro,
			C.id_servicio,
			servicios.rellamada AS rellamada_servicio,
			servicios.id_familia_servicio,
			servicios_familias.rellamada AS rellamada_familia,
			CASE 
				WHEN servicios.rellamada = 0 THEN servicios_familias.rellamada ELSE servicios.rellamada 
			END AS rellamada_final,
			CASE 
				WHEN (CASE WHEN servicios.rellamada = 0 THEN servicios_familias.rellamada ELSE servicios.rellamada END) != 0 
				THEN DATE_FORMAT(DATE_ADD(C.fecha_hora_inicio, INTERVAL 
					(CASE WHEN servicios.rellamada = 0 THEN servicios_familias.rellamada ELSE servicios.rellamada END) DAY), '%Y-%m-%d')
				ELSE NULL 
			END AS fecha_rellamada

		FROM citas AS C
		LEFT JOIN servicios on servicios.id_servicio = C.id_servicio
		LEFT JOIN servicios_familias on servicios.id_familia_servicio = servicios_familias.id_familia_servicio
		LEFT JOIN usuarios on usuarios.id_usuario = C.id_usuario_empleado
		WHERE C.borrado = 0 " . $busqueda . " GROUP BY C.id_cliente ORDER BY C.fecha_hora_inicio ASC";
		$datos = $AqConexion_model->select($sentencia_sql, $parametros);
		
		return $datos;
	}

	function nueva_rellamada($parametros)
	{
		
		$AqConexion_model = new AqConexion_model();
		// ... Datos generales como usuario.
		$registro['id_cliente'] = $parametros['id_cliente'];
		$registro['id_cita'] = $parametros['id_cita'];
		$registro['fecha_cita'] = $parametros['fecha_cita'];
		$registro['id_usuario_cita'] = $parametros['id_usuario_cita'];
		$registro['id_centro'] = $parametros['id_centro'];
		$registro['id_servicio'] = $parametros['id_servicio'];
		$registro['rellamada_servicio'] = $parametros['rellamada_servicio'];
		$registro['id_familia_servicio'] = $parametros['id_familia_servicio'];
		$registro['rellamada_familia'] = $parametros['rellamada_familia'];
		$registro['rellamada_final'] = $parametros['rellamada_final'];
		$registro['fecha_rellamada'] = $parametros['fecha_rellamada'];
		$registro['estado'] = isset($parametros['estado']) ? $parametros['estado'] : 'pendiente';
		$registro['comentarios'] = isset($parametros['comentarios']) ? $parametros['comentarios'] : '';
		$registro['fecha_creacion'] = date("Y-m-d H:i:s");
		$registro['id_usuario_creacion'] = ($this->session->userdata('id_usuario') !='')?$this->session->userdata('id_usuario'):1;
		$registro['fecha_modificacion'] = date("Y-m-d H:i:s");
		$registro['id_usuario_modificacion'] = ($this->session->userdata('id_usuario') != '')?$this->session->userdata('id_usuario'):1;
		$registro['borrado'] = 0;
		if(isset($parametros['parent']) && $parametros['parent'] != ''){
			$registro['parent'] = $parametros['parent'];
		}
		$AqConexion_model->insert('rellamadas', $registro);
		$sentenciaSQL = "select max(id_rellamada) as id_rellamada from rellamadas";
		$resultado = $AqConexion_model->select($sentenciaSQL, null);
		return $resultado[0]['id_rellamada'];
	}

	function crear_rellamada($parametros)
	{
		$AqConexion_model = new AqConexion_model();
		// ... Datos generales como usuario.
		$rellamada['id_cliente'] = $parametros['id_cliente'];
		$rellamada['id_cita'] = 0;
		$rellamada['fecha_cita'] = (isset($parametros['fecha_cita'])) ? $parametros['fecha_cita'] : $parametros['fecha_rellamada'];
		$rellamada['id_usuario_cita'] = 0;
		$rellamada['id_centro'] = ($this->session->userdata('id_centro_usuario') != '') ? $this->session->userdata('id_centro_usuario') : 0;
		$rellamada['id_servicio'] = $parametros['id_servicio'];
		$rellamada['rellamada_servicio'] = 0;
		$rellamada['id_familia_servicio'] = 0;
		$rellamada['rellamada_familia'] = 0;
		$rellamada['rellamada_final'] = 0;
		$rellamada['fecha_rellamada'] = $parametros['fecha_rellamada'];
		$rellamada['estado'] = isset($parametros['estado']) ? $parametros['estado'] : 'pendiente';
		$rellamada['comentarios'] = isset($parametros['comentarios']) ? $parametros['comentarios'] : '';
		$id_nueva_rellamada = $this->nueva_rellamada($rellamada);
		return $id_nueva_rellamada;
	}

	function actualizar_rellamada($parametros)
	{
		$AqConexion_model = new AqConexion_model();
		$registro = [];
		if(isset($parametros['id_cliente'])){
			$registro['id_cliente'] = $parametros['id_cliente'];
		}
		if(isset($parametros['id_cita'])){
			$registro['id_cita'] = $parametros['id_cita'];
		}
		if(isset($parametros['fecha_cita'])){
			$registro['fecha_cita'] = $parametros['fecha_cita'];
		}
		if(isset($parametros['id_usuario_cita'])){
			$registro['id_usuario_cita'] = $parametros['id_usuario_cita'];
		}
		if(isset($parametros['id_centro'])){
			$registro['id_centro'] = $parametros['id_centro'];
		}
		if(isset($parametros['rellamada_servicio'])){
			$registro['rellamada_servicio'] = $parametros['rellamada_servicio'];
		}
		if(isset($parametros['id_familia_servicio'])){
			$registro['id_familia_servicio'] = $parametros['id_familia_servicio'];
		}
		if(isset($parametros['rellamada_familia'])){
			$registro['rellamada_familia'] = $parametros['rellamada_familia'];
		}
		if(isset($parametros['rellamada_final'])){
			$registro['rellamada_final'] = $parametros['rellamada_final'];
		}
		if(isset($parametros['fecha_rellamada'])){
			$registro['fecha_rellamada'] = $parametros['fecha_rellamada'];
		}
		if(isset($parametros['estado'])){
			$registro['estado'] = $parametros['estado'];
		}
		if(isset($parametros['comentarios'])){
			$registro['comentarios'] = $parametros['comentarios'];
		}
		if(isset($parametros['parent'])){
			$registro['parent'] = $parametros['parent'];
		}
		if(isset($parametros['children'])){
			$registro['children'] = $parametros['children'];
		}
		if(isset($parametros['borrado'])){
			$registro['borrado'] = $parametros['borrado'];
			$registro['fecha_borrado'] = date("Y-m-d H:i:s");
			$registro['id_usuario_borrado'] = $this->session->userdata('id_usuario');
		}
		if(count($registro) > 0) {
			$registro['fecha_modificacion'] = date("Y-m-d H:i:s");
			$registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
			$where['id_rellamada'] = $parametros['id_rellamada'];
			$AqConexion_model->update('rellamadas', $registro, $where);
			return 1;
		} else{
			return 0;
		}
	}

	function crear_rellamada_vinculada($parametros)
	{
		$AqConexion_model = new AqConexion_model();
		if(!isset($parametros['parent']) || $parametros['parent'] == ''){
			return 0;
		}
		// buscar la rellamada
		$this->db->where('id_rellamada', $parametros['parent']);
		$rellamada = $this->db->get('rellamadas')->row_array();
		
		$rellamada['fecha_rellamada'] = $parametros['fecha_rellamada'];
		$rellamada['comentarios'] = $parametros['comentarios'];
		$rellamada['parent'] = $parametros['parent'];
		unset($rellamada['id_rellamada']);
		
		$id_nueva_rellamada = $this->nueva_rellamada($rellamada);

		if($id_nueva_rellamada > 0){
			// añadir el hijo a la rellamada actual
			$parametros_actualizar = [];
			$parametros_actualizar['children'] = $id_nueva_rellamada;
			$parametros_actualizar['id_rellamada'] = $parametros['parent'];
			$actualizado = $this->actualizar_rellamada($parametros_actualizar);
			return $actualizado;
		} else{
			return 0;
		}
	}


}
