<?php
class Presupuestos_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function leer_presupuestos($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $busqueda = "";

        if (isset($parametros['id_presupuesto'])) {
            $busqueda .= " AND presupuestos.id_presupuesto = @id_presupuesto ";
        }

        if (isset($parametros['id_centro'])) {
            $busqueda .= " AND presupuestos.id_centro = @id_centro ";
        }

        if (isset($parametros['estado'])) {
            $busqueda .= " AND presupuestos.estado = @estado ";
        }

        if (isset($parametros['aceptados']) && $parametros['aceptados'] == TRUE) {
            $busqueda .= " AND presupuestos.estado IN ('Aceptado','Aceptado parcial') ";
        }

        if (isset($parametros['id_cliente'])) {
            $busqueda .= " AND presupuestos.id_cliente = @id_cliente ";
        }

        if (isset($parametros['fecha_validez'])) {
            $busqueda .= " AND presupuestos.fecha_validez <= @fecha_validez ";
        }

        if (isset($parametros['pendiente'])) {
            $busqueda .= " AND presupuestos.total_pendiente < 0 ";
        }

        if (isset($parametros['fecha_creacion_inicio'])) {
            if ($parametros['fecha_creacion_inicio'] != "") {
                $busqueda .= " AND (DATE_FORMAT(presupuestos.fecha_creacion,'%Y-%m-%d')) >= @fecha_creacion_inicio ";
            }
        }

        if (isset($parametros['fecha_creacion_fin'])) {
            if ($parametros['fecha_creacion_fin'] != "") {
                $busqueda .= " AND (DATE_FORMAT(presupuestos.fecha_creacion,'%Y-%m-%d')) <= @fecha_fin ";
            }
        }


        // ... Leemos los registros
        $sentencia_sql = "SELECT 
        presupuestos.id_presupuesto,
        presupuestos.id_cliente,
        presupuestos.id_usuario,
        presupuestos.id_doctor,
        presupuestos.id_centro,
        presupuestos.fecha_validez,
        presupuestos.estado,
        presupuestos.estado_relacionado,
        presupuestos.mostrar_obs,
        presupuestos.com_cuota,
        presupuestos.dto_euros,
        presupuestos.dto_100,
        presupuestos.totalpresupuesto,
        presupuestos.n_cuotas,
        presupuestos.apertura,
        presupuestos.totalcuota,
        presupuestos.fecha_creacion,
        presupuestos.id_usuario_modificacion,
        presupuestos.fecha_modificacion,
        presupuestos.borrado,
        presupuestos.id_usuario_borrado,
        presupuestos.fecha_borrado,
        usu.nombre AS e_nombre,
        usu.apellidos AS e_apellidos,
        usu_modi.nombre AS usu_modif_nombre,
        usu_modi.apellidos AS usu_modif_pellidos,
        centros.nombre_centro,
        clientes.nombre,
        clientes.apellidos,
        clientes.telefono,
        clientes.dni,
        presupuestos.nro_presupuesto,
        presupuestos.total_pendiente,
        presupuestos.total_aceptado,
        presupuestos.revisado,
        presupuestos.anticipo_financiacion,
        presupuestos.mostrar_financiacion,
        presupuestos.es_repeticion,
        (SELECT COALESCE(SUM(importe_euros), 0) FROM dietario WHERE id_presupuesto = presupuestos.id_presupuesto AND borrado = 0 AND estado = 'Pagado') AS total_pagado,
        (SELECT COALESCE(SUM(pi.coste), 0) AS total_pvp FROM presupuestos_items pi JOIN citas c ON pi.id_cita = c.id_cita WHERE pi.id_presupuesto = presupuestos.id_presupuesto AND c.estado = 'Finalizado' AND pi.borrado = 0 AND c.borrado = 0) AS total_gastado

        FROM presupuestos
	        LEFT JOIN usuarios usu on usu.id_usuario = presupuestos.id_usuario
	        LEFT JOIN usuarios usu_modi on usu_modi.id_usuario = presupuestos.id_usuario_modificacion
	        LEFT JOIN clientes on clientes.id_cliente = presupuestos.id_cliente
	        LEFT JOIN centros on centros.id_centro = presupuestos.id_centro
        WHERE presupuestos.borrado = 0 
        " . $busqueda . " ORDER BY presupuestos.id_presupuesto DESC ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);
        return $datos;
    }

    function leer_presupuestos_pendiente($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $busqueda = "";

        if (isset($parametros['id_presupuesto'])) {
            $busqueda .= " AND presupuestos.id_presupuesto = @id_presupuesto ";
        }

        if (isset($parametros['id_centro'])) {
            $busqueda .= " AND presupuestos.id_centro = @id_centro ";
        }

        if (isset($parametros['id_cliente'])) {
            $busqueda .= " AND presupuestos.id_cliente = @id_cliente ";
        }

        if (isset($parametros['pendiente'])) {
            $busqueda .= " AND presupuestos.total_pendiente < 0 ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT 
        presupuestos.*
        FROM presupuestos
        WHERE presupuestos.borrado = 0 AND presupuestos.estado IN ('Aceptado', 'Aceptado parcial') 
        " . $busqueda . " ORDER BY presupuestos.id_presupuesto DESC ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);
        return $datos;
    }

    function leer_presupuestos_items($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $busqueda = "";

        if (isset($parametros['id_presupuesto_item'])) {
            $busqueda .= " AND presupuestos_items.id_presupuesto_item = @id_presupuesto_item ";
        }

        if (isset($parametros['id_presupuesto'])) {
            $busqueda .= " AND presupuestos_items.id_presupuesto = @id_presupuesto ";
        }

        if (isset($parametros['tipo_item'])) {
            $busqueda .= " AND presupuestos_items.tipo_item = @tipo_item ";
        }
        if (isset($parametros['id_item'])) {
            $busqueda .= " AND presupuestos_items.id_item = @id_item ";
        }
        if (isset($parametros['id_cliente'])) {
            $busqueda .= " AND presupuestos_items.id_cliente = @id_cliente ";
        }

        if (isset($parametros['id_usuario'])) {
            $busqueda .= " AND presupuestos_items.id_usuario <= @id_usuario ";
        }

        if (isset($parametros['citas'])) {
            $busqueda .= " AND (presupuestos_items.id_cita = 0 OR citas.estado = 'Anulada' OR citas.estado = 'No vino') ";
        }

        if (isset($parametros['id_cita'])) {
            $busqueda .= " AND presupuestos_items.id_cita = @id_cita ";
        }

        if (isset($parametros['aceptado'])) {
            $busqueda .= " AND presupuestos_items.aceptado = @aceptado ";
        }

        if (isset($parametros['id_dietario'])) {
            $busqueda .= " AND presupuestos_items.id_dietario = @id_dietario ";
        }
        if (isset($parametros['or_id_presupuesto_item'])) {
            $busqueda .= " OR presupuestos_items.id_presupuesto_item = @or_id_presupuesto_item ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT 
	        presupuestos_items.id_presupuesto_item,
	        presupuestos_items.id_presupuesto,
	        presupuestos_items.tipo_item,
	        presupuestos_items.id_item,
	        presupuestos_items.cantidad,
	        presupuestos_items.dientes,
	        presupuestos_items.pvp,
	        presupuestos_items.dto,
            presupuestos_items.dto_euros,
            presupuestos_items.dtop,
            presupuestos_items.coste,
	        presupuestos_items.aceptado,
	        presupuestos_items.id_cliente,
	        presupuestos_items.id_usuario,
            presupuestos_items.id_cita,
            presupuestos_items.id_dietario,
	        presupuestos_items.fecha_creacion,
	        presupuestos_items.id_usuario_modificacion,
	        presupuestos_items.fecha_modificacion,
	        presupuestos_items.borrado,
	        presupuestos_items.id_usuario_borrado,
	        presupuestos_items.fecha_borrado,
            presupuestos_items.gastos_lab,
	        usuarios.nombre AS e_nombre,
	        usuarios.apellidos AS e_apellidos,
            citas.fecha_hora_inicio,
            citas.estado AS estado_cita,
            citas.id_usuario_empleado,
            servicios.padre,
            servicios.parte_padre,
            CONCAT(empleados.nombre, ' ', empleados.apellidos) As empleado,
	        CASE
	            WHEN presupuestos_items.tipo_item = 'Producto' THEN productos.nombre_producto
	            WHEN presupuestos_items.tipo_item = 'Servicio' THEN servicios.nombre_servicio
	            ELSE 'No especificado'
	        END AS nombre_item,
	        CASE
	            WHEN presupuestos_items.tipo_item = 'Producto' THEN productos_familias.nombre_familia
	            WHEN presupuestos_items.tipo_item = 'Servicio' THEN servicios_familias.nombre_familia
	            ELSE 'No especificado'
	        END AS nombre_familia,
            CASE
	            WHEN presupuestos_items.tipo_item = 'Producto' THEN 0
	            WHEN presupuestos_items.tipo_item = 'Servicio' THEN servicios.duracion
	            ELSE 'No especificado'
	        END AS duracion,
            CASE
	            WHEN presupuestos_items.tipo_item = 'Producto' THEN 'no'
	            WHEN presupuestos_items.tipo_item = 'Servicio' THEN citas.estado
	            ELSE 'No especificado'
	        END AS estado_cita,
            CASE
	            WHEN presupuestos_items.tipo_item = 'Producto' THEN 'no'
	            WHEN presupuestos_items.tipo_item = 'Servicio' THEN dietario.estado
	            ELSE 'No especificado'
	        END AS estado_dietario

	        FROM presupuestos_items
                LEFT JOIN usuarios on usuarios.id_usuario = presupuestos_items.id_usuario
                LEFT JOIN clientes on clientes.id_cliente = presupuestos_items.id_cliente
                LEFT JOIN productos ON presupuestos_items.tipo_item = 'Producto' AND presupuestos_items.id_item = productos.id_producto
                LEFT JOIN servicios ON presupuestos_items.tipo_item = 'Servicio' AND presupuestos_items.id_item = servicios.id_servicio
                LEFT JOIN productos_familias ON presupuestos_items.tipo_item = 'Producto' AND productos_familias.id_familia_producto = productos.id_familia_producto
                LEFT JOIN servicios_familias ON presupuestos_items.tipo_item = 'Servicio' AND servicios_familias.id_familia_servicio = servicios.id_familia_servicio
                LEFT JOIN citas ON presupuestos_items.tipo_item = 'Servicio' AND presupuestos_items.id_cita = citas.id_cita
                LEFT JOIN dietario ON presupuestos_items.id_dietario = dietario.id_dietario
                LEFT JOIN usuarios AS empleados on empleados.id_usuario = citas.id_usuario_empleado
	
	        WHERE presupuestos_items.borrado = 0 
        " . $busqueda . " ORDER  BY presupuestos_items.id_presupuesto_item DESC ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);
        return (is_array($datos)) ? $datos : [];
    }

    function nuevo_presupuesto($parametros)
    {
        $AqConexion_model = new AqConexion_model();

	    $sentencia_sql = "SELECT MAX(id_presupuesto) as max FROM presupuestos";
	    $max_presup = $AqConexion_model->select($sentencia_sql, null);
		$proximo_id = intval($max_presup[0]['max'])+1;

	    // ... Datos generales como usuario.
	    $registro['id_presupuesto'] = $proximo_id;
	    $registro['id_cliente'] = $parametros['id_cliente'];
        $registro['id_doctor'] = $parametros['id_doctor'];
        $registro['id_usuario'] = $this->session->userdata('id_usuario');
        $registro['id_centro'] =  $this->session->userdata('id_centro_usuario');
	    $registro['nro_presupuesto'] = substr('000000'.$proximo_id, -6);
        $registro['fecha_validez'] = $parametros['fecha_validez'];
        $registro['estado'] = $parametros['estado'];
        $registro['estado_relacionado'] = $parametros['estado_relacionado'];
        $registro['mostrar_obs'] = (isset($parametros['mostrar_obs']))?1:0;
        //$registro['com_cuota'] = $parametros['com_cuota'];
        $registro['dto_euros'] = $parametros['dto_euros'];
        $registro['dto_100'] = $parametros['dto_100'];
        $registro['totalpresupuesto'] = $parametros['totalpresupuesto'];

        $registro['anticipo_financiacion'] = $parametros['anticipo_financiacion'];
        $registro['n_cuotas'] = $parametros['cuotas'];
        $registro['apertura'] = $parametros['apertura'];
        $registro['totalcuota'] = $parametros['totalcuota'];
        $registro['mostrar_financiacion'] = (isset($parametros['mostrar_financiacion']))?1:0;
        $registro['es_repeticion'] = (isset($parametros['es_repeticion']))?1:0;
        $registro['fecha_creacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['borrado'] = 0;
        $AqConexion_model->insert('presupuestos', $registro);

		$last_id = $this->db->insert_id();

        return $last_id;
    }

    function actualizar_presupuesto($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $registro['id_cliente'] = $parametros['id_cliente'];
        $registro['id_doctor'] = $parametros['id_doctor'];
        $registro['id_usuario'] = (isset($parametros['id_usuario'])) ? $parametros['id_usuario'] : $this->session->userdata('id_usuario');
        $registro['id_centro'] =  $this->session->userdata('id_centro_usuario');
        $registro['fecha_validez'] = $parametros['fecha_validez'];
        $registro['estado'] = $parametros['estado'];
        $registro['estado_relacionado'] = $parametros['estado_relacionado'];
        $registro['mostrar_obs'] = (isset($parametros['mostrar_obs']))?1:0;
        //$registro['com_cuota'] = $parametros['com_cuota'];
        $registro['dto_euros'] = $parametros['dto_euros'];
        $registro['dto_100'] = $parametros['dto_100'];
        $registro['totalpresupuesto'] = $parametros['totalpresupuesto'];

        $registro['anticipo_financiacion'] = $parametros['anticipo_financiacion'];
        $registro['n_cuotas'] = $parametros['cuotas'];
        $registro['apertura'] = $parametros['apertura'];
        $registro['totalcuota'] = $parametros['totalcuota'];
        $registro['mostrar_financiacion'] = (isset($parametros['mostrar_financiacion']))?1:0;
        $registro['es_repeticion'] = (isset($parametros['es_repeticion']))?1:0;

        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        if(isset($parametros['revisado'])){$registro['revisado'] = $parametros['revisado'];}
        if(isset($parametros['nro_presupuesto'])){$registro['nro_presupuesto'] = $parametros['nro_presupuesto'];}
        if(isset($parametros['fecha_creacion'])){$registro['fecha_creacion'] = $parametros['fecha_creacion'];}
        $where['id_presupuesto'] = $parametros['id_presupuesto'];
        $AqConexion_model->update('presupuestos', $registro, $where);
        return $this->db->affected_rows();
    }

    function actualizar_estado_presupuesto($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $registro['estado'] = $parametros['estado'];
        $registro['estado_relacionado'] = $parametros['estado_relacionado'];
        $registro['total_aceptado'] = $parametros['total_aceptado'];
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $where['id_presupuesto'] = $parametros['id_presupuesto'];
        $AqConexion_model->update('presupuestos', $registro, $where);
        return $this->db->affected_rows();
    }

    function nuevo_item_presupuesto($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        // ... Datos generales.
        $registro['id_presupuesto'] = $parametros['id_presupuesto'];
        $registro['tipo_item'] =  $parametros['tipo_item'];
        $registro['id_item'] =  $parametros['id_item'];
	    $registro['cantidad'] = $parametros['cantidad'];
	    $registro['dientes'] = $parametros['dientes'];
        $registro['pvp'] = $parametros['pvp'];
        $registro['dto'] = $parametros['dto'];
        $registro['dto_euros'] = $parametros['dto_euros'];
        $registro['coste'] = $parametros['coste'];
        $registro['id_cliente'] = $parametros['id_cliente'];
        $registro['id_usuario'] = $this->session->userdata('id_usuario');
        $registro['fecha_creacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['borrado'] = 0;
        $AqConexion_model->insert('presupuestos_items', $registro);
        return $this->db->insert_id();
    }

	function get_proximo_numero_presupuesto($nro_presupuesto) {

		$AqConexion_model = new AqConexion_model();

		$arrNro = explode('-', $nro_presupuesto);
		for ( $i = $arrNro[1]+1; $i <= 300; $i++ ) {
			$parametro['nro_presupuesto'] = $arrNro[0].'-'.$i;
			$sentencia_sql = "SELECT id_presupuesto FROM presupuestos WHERE nro_presupuesto = @nro_presupuesto";
			$rsPresu = $AqConexion_model->select($sentencia_sql, $parametro);
			if ( empty($rsPresu) ) {
				return $parametro['nro_presupuesto'];
			}
		}
		die('Uh');

	}

	function duplicar_presupuesto($id_presupuesto) 	{

		$AqConexion_model = new AqConexion_model();

		$sentencia_sql = "SELECT nro_presupuesto FROM presupuestos WHERE id_presupuesto = @id_presupuesto";
		$rsPresu = $AqConexion_model->select($sentencia_sql, array('id_presupuesto'=>$id_presupuesto));
		if ( strpos($rsPresu[0]['nro_presupuesto'], '-') !== false ) {
			$siguiente_nro = $this->get_proximo_numero_presupuesto($rsPresu[0]['nro_presupuesto']);
		} else {
			$siguiente_nro = $rsPresu[0]['nro_presupuesto'] . '-1';
		}

		$rsPresu = $this->leer_presupuestos( array('id_presupuesto'=>$id_presupuesto) );
		$rsPresuItems = $this->leer_presupuestos_items( array('id_presupuesto'=>$id_presupuesto) );

		$nuevoPresu['id_cliente'] = $rsPresu[0]['id_cliente'];
        $nuevoPresu['id_doctor'] = $rsPresu[0]['id_doctor'];
		$nuevoPresu['id_usuario'] = $this->session->userdata('id_usuario');
		$nuevoPresu['id_centro'] =  $this->session->userdata('id_centro_usuario');
		$nuevoPresu['nro_presupuesto'] = $siguiente_nro;
		$nuevoPresu['fecha_validez'] = date('Y-m-d', strtotime("+15 day"));
		$nuevoPresu['estado'] = 'Borrador';
		$nuevoPresu['estado_relacionado'] = '';
		//$nuevoPresu['com_cuota'] = $rsPresu[0]['com_cuota'];
		$nuevoPresu['dto_euros'] = $rsPresu[0]['dto_euros'];
		$nuevoPresu['dto_100'] = $rsPresu[0]['dto_100'];
		$nuevoPresu['totalpresupuesto'] = $rsPresu[0]['totalpresupuesto'];
		$nuevoPresu['fecha_creacion'] = date("Y-m-d H:i:s");
		$nuevoPresu['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
		$nuevoPresu['fecha_modificacion'] = date("Y-m-d H:i:s");
		$nuevoPresu['borrado'] = 0;
		$AqConexion_model->insert('presupuestos', $nuevoPresu);

		$nuevo_id = $this->db->insert_id();

		foreach( $rsPresuItems as $rsItem ) {
			$registro['id_presupuesto'] = $nuevo_id;
			$registro['tipo_item'] = $rsItem['tipo_item'];
			$registro['id_item'] = $rsItem['id_item'];
			$registro['cantidad'] = $rsItem['cantidad'];
			$registro['dientes'] = $rsItem['dientes'];
			$registro['pvp'] = $rsItem['pvp'];
			$registro['dto'] = $rsItem['dto'];
            $registro['dto_euros'] = $rsItem['dto_euros'];
			$registro['id_cliente'] = $rsItem['id_cliente'];
			$registro['id_usuario'] = $this->session->userdata('id_usuario');
			$registro['fecha_creacion'] = date("Y-m-d H:i:s");
			$registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
			$registro['fecha_modificacion'] = date("Y-m-d H:i:s");
			$registro['borrado'] = 0;
			$AqConexion_model->insert('presupuestos_items', $registro);
		}

		$this->recalcular_totales($nuevo_id);

		return $nuevo_id;

	}

    function actualizar_item_presupuesto($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        if(isset($parametros['borrado']) && $parametros['borrado'] == 1){
            $registro['borrado'] = 1;
            $registro['id_usuario_borrado'] = $this->session->userdata('id_usuario');
            $registro['fecha_borrado'] = date("Y-m-d H:i:s");
        }else{
            if(isset($parametros['id_presupuesto'])){
                $registro['id_presupuesto'] = $parametros['id_presupuesto'];
            }
            if(isset($parametros['tipo_item'])){
                $registro['tipo_item'] =  $parametros['tipo_item'];
            }
            if(isset($parametros['id_item'])){
                $registro['id_item'] =  $parametros['id_item'];
            }
            if(isset($parametros['cantidad'])){
                $registro['cantidad'] = $parametros['cantidad'];
            }
            if(isset($parametros['dientes'])){
                $registro['dientes'] = $parametros['dientes'];
            }
            if(isset($parametros['pvp'])){
                $registro['pvp'] = $parametros['pvp'];
            }
            if(isset($parametros['dto'])){
                $registro['dto'] = $parametros['dto'];
            }
            if(isset($parametros['dto_euros'])){
                $registro['dto_euros'] = $parametros['dto_euros'];
            }
            if(isset($parametros['dtop'])){
                $registro['dtop'] = $parametros['dtop'];
            }
            if(isset($parametros['coste'])){
                $registro['coste'] = $parametros['coste'];
            }
            if(isset($parametros['id_cliente'])){
                $registro['id_cliente'] = $parametros['id_cliente'];
            }
            if(isset($parametros['gastos_lab'])){
                $registro['gastos_lab'] = $parametros['gastos_lab'];
            }
        }
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $where['id_presupuesto_item'] = $parametros['id_presupuesto_item'];
        $AqConexion_model->update('presupuestos_items', $registro, $where);
        return $this->db->affected_rows();
    }

    function actualizar_presupuesto_item_cita_dietario($id_presupuesto_item, $id_cita, $id_dietario) {
        $AqConexion_model = new AqConexion_model();
        $registro['id_cita'] = $id_cita;
        $registro['id_dietario'] =  $id_dietario;
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $where['id_presupuesto_item'] = $id_presupuesto_item;
        $AqConexion_model->update('presupuestos_items', $registro, $where);
        return $this->db->affected_rows();
    }

    function actualizar_estado_item_presupuesto($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $registro['aceptado'] = $parametros['aceptado'];
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $where['id_presupuesto_item'] = $parametros['id_presupuesto_item'];
        $AqConexion_model->update('presupuestos_items', $registro, $where);
        return $this->db->affected_rows();
    }

    function borrar_presupuesto($id_presupuesto)
    {
        $AqConexion_model = new AqConexion_model();
        $registro['borrado'] = 1;
        $registro['id_usuario_borrado'] = $this->session->userdata('id_usuario');
        $registro['fecha_borrado'] = date("Y-m-d H:i:s");
        $where['id_presupuesto'] = $id_presupuesto;
        $AqConexion_model->update('presupuestos_items', $registro, $where);

        $AqConexion_model = new AqConexion_model();
        $registro['borrado'] = 1;
        $registro['id_usuario_borrado'] = $this->session->userdata('id_usuario');
        $registro['fecha_borrado'] = date("Y-m-d H:i:s");
        $where['id_presupuesto'] = $id_presupuesto;
        $AqConexion_model->update('presupuestos', $registro, $where);
        return 1;
    }


	function recalcular_totales($id_presupuesto) {

		unset($param);
		$param['id_presupuesto'] = $id_presupuesto;
		$param['tipo_item'] = 'Producto';
		$rsItems_Productos = $this->Presupuestos_model->leer_presupuestos_items($param);

		unset($param);
		$param['id_presupuesto'] = $id_presupuesto;
		$param['tipo_item'] = 'Servicio';
		$rsItems_Servicios = $this->Presupuestos_model->leer_presupuestos_items($param);

		unset($param);
		$param['id_presupuesto'] = $id_presupuesto;
		$rsPresupuesto  = $this->Presupuestos_model->leer_presupuestos($param);

		$totalItemsSinDescuento = 0;
		$totalItemsConDescuento = 0;
		$total_aceptado_ItemsSinDescuento = 0;
		$total_aceptado_ItemsConDescuento = 0;

		foreach ( $rsItems_Productos as $rs ) {
			$tmTotal = $rs['pvp'] * $rs['cantidad'];
			$totalItemsSinDescuento += $tmTotal;
			$totalItemsConDescuento += $tmTotal - ($tmTotal * $rs['dto'] / 100) - $rs['dto_euros'];
			if ( $rs['aceptado'] ) {
				$total_aceptado_ItemsSinDescuento += $tmTotal;
				$total_aceptado_ItemsConDescuento += $tmTotal - ($tmTotal * $rs['dto'] / 100) - $rs['dto_euros'];
			}
		}

		foreach ( $rsItems_Servicios as $rs ) {
			$tmTotal = $rs['pvp'] * $rs['cantidad'];
			$totalItemsSinDescuento += $tmTotal;
			$totalItemsConDescuento += $tmTotal - ($tmTotal * $rs['dto'] / 100);
			if ( $rs['aceptado'] ) {
				$total_aceptado_ItemsSinDescuento += $tmTotal;
				$total_aceptado_ItemsConDescuento += $tmTotal - ($tmTotal * $rs['dto'] / 100) - $rs['dto_euros'];
			}
		}

		$totalFinal = $totalItemsConDescuento;
		$totalAceptadoFinal = $total_aceptado_ItemsConDescuento;
		if ( $rsPresupuesto[0]['dto_euros'] > 0 ) {
			$totalFinal = $totalItemsSinDescuento - $rsPresupuesto[0]['dto_euros'];
			$totalAceptadoFinal = $total_aceptado_ItemsSinDescuento - $rsPresupuesto[0]['dto_euros'];
		} else if ( $rsPresupuesto[0]['dto_100'] > 0 ) {
			$totalFinal = $totalItemsSinDescuento - ($totalItemsSinDescuento * $rsPresupuesto[0]['dto_100'] / 100);
			$totalAceptadoFinal = $total_aceptado_ItemsSinDescuento - ($total_aceptado_ItemsSinDescuento * $rsPresupuesto[0]['dto_100'] / 100);
		}

		//echo "<p>Presupuesto $id_presupuesto > Total $totalFinal > Total desc $totalItemsConDescuento > Total sin desc $totalItemsSinDescuento";

		$AqConexion_model = new AqConexion_model();
		$sentencia_sql = "UPDATE presupuestos 
			SET total_sin_descuento = '$totalItemsSinDescuento',
            total_aceptado = '$totalAceptadoFinal',
            total_pendiente = $totalAceptadoFinal - COALESCE((
                SELECT SUM(importe_euros) FROM dietario WHERE id_presupuesto = $id_presupuesto AND borrado = 0 AND estado = 'Pagado' AND id_cita = 0 ), 0)
			WHERE id_presupuesto = $id_presupuesto";
		$AqConexion_model->no_select($sentencia_sql, null);

	}

	function get_usuarios_presupuestos() {

		$AqConexion_model = new AqConexion_model();
		$sentencia_sql="SELECT id_usuario, nombre, apellidos FROM usuarios WHERE id_usuario IN (SELECT id_usuario FROM presupuestos)";
		$datos = $AqConexion_model->select($sentencia_sql, null);

		return $datos;
	}


    function crear_presupuestos_saldos($id_presupuesto) {
        $AqConexion_model = new AqConexion_model();
        $parametros['id_presupuesto'] = $id_presupuesto;
        $sentencia_sql = "SELECT * FROM presupuestos WHERE borrado = 0  AND id_presupuesto = @id_presupuesto";
        $presupuesto = $AqConexion_model->select($sentencia_sql, $parametros);
        $presupuesto = $presupuesto[0];
        $parametros['id_usuario_logueado'] = $this->session->userdata('id_usuario');

        // se borra el pago del presupuesto que no tiene dietario (esos se creas al aceptar presupuesto)
        $sentencia_sql = "UPDATE presupuestos_pagos SET borrado = 1, fecha_borrado = NOW(), id_usuario_borrado = @id_usuario_logueado WHERE id_presupuesto = $id_presupuesto AND id_dietario = 0 AND borrado = 0";
		$AqConexion_model->no_select($sentencia_sql, $parametros);

        /*$sentencia_sql = "UPDATE presupuestos SET total_pendiente =( SELECT SUM(cantidad) FROM presupuestos_pagos WHERE id_presupuesto = @id_presupuesto AND borrado = 0) WHERE id_presupuesto = @id_presupuesto;";
        $AqConexion_model->no_select($sentencia_sql, $parametros);*/

        // ... Datos generales como usuario.
	    $registro['id_presupuesto'] = $id_presupuesto;
        $registro['id_item_presupuesto'] = 0;
	    $registro['id_cliente'] = $presupuesto['id_cliente'];
        $registro['id_dietario'] = 0;
        $registro['cantidad'] = $presupuesto['total_aceptado'] * -1;
        $registro['id_usuario_creacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_creacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['borrado'] = 0;
        $AqConexion_model->insert('presupuestos_pagos', $registro);

    }


    function realizar_pago_presupuesto($id_presupuesto, $id_cliente, $importe, $tipo_pago, $concepto)
    {
        $AqConexion_model = new AqConexion_model();
        $registro['id_cliente'] = $id_cliente;
        $registro['fecha_hora_concepto'] = date("Y-m-d H:i:s");
        $registro['fecha_pagado'] = date("Y-m-d H:i:s");
        $registro['id_empleado'] = $this->session->userdata('id_usuario');
        $registro['importe_euros'] = $importe;
        $registro['tipo_pago'] = $tipo_pago;
        $registro['estado'] = "Pagado";
        $registro['id_centro'] = $this->session->userdata('id_centro_usuario');
        if ($tipo_pago == "#efectivo") {
            $registro['pagado_efectivo'] = $importe;
        } elseif ($tipo_pago == "#tarjeta") {
            $registro['pagado_tarjeta'] = $importe;
        } elseif ($tipo_pago == "#transferencia") {
            $registro['pagado_transferencia'] = $importe;
        } elseif ($tipo_pago == "#paypal") {
            $registro['pagado_paypal'] = $importe;
        } elseif ($tipo_pago == "#tpv2") {
            $registro['pagado_tpv2'] = $importe;
        }
        $registro['id_servicio'] = 0;
        $registro['id_producto'] = 0;
        $registro['id_presupuesto'] = $id_presupuesto;
        $registro['id_carnet'] = 0;
        $registro['templos'] = 0;
        $registro['pago_a_cuenta'] = 0;
        $registro['fecha_creacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_creador'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['borrado'] = 0;

        $AqConexion_model->insert('dietario', $registro);

        $sentenciaSQL = "select max(id_dietario) as id from dietario";
        $id_dietario = $AqConexion_model->select($sentenciaSQL, null)[0]['id'];
        $registro = [];
        $registro['id_presupuesto'] = $id_presupuesto;
        $registro['id_item_presupuesto'] = 0;
	    $registro['id_cliente'] = $id_cliente;
        $registro['id_dietario'] = $id_dietario;
        $registro['cantidad'] = $importe;
        $registro['concepto'] = $concepto;
        $registro['id_usuario_creacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_creacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['borrado'] = 0;
        $AqConexion_model->insert('presupuestos_pagos', $registro);
        return 1;
        
    }

    function pagoeuros($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $tipos_pagos = [];
        $importe_euros = 0;
        if (isset($parametros['pagado_efectivo']) && $parametros['pagado_efectivo'] > 0) {
            $tipos_pagos[]= 'efectivo';
        }
        if (isset($parametros['pagado_tarjeta']) && $parametros['pagado_tarjeta'] > 0) {
            $tipos_pagos[]= 'tarjeta';
        }
        if (isset($parametros['pagado_transferencia']) && $parametros['pagado_transferencia'] > 0) {
            $tipos_pagos[]= 'transferencia';
        }
        if (isset($parametros['pagado_tpv2']) && $parametros['pagado_tpv2'] > 0) {
            $tipos_pagos[]= 'tpv2';
        }
        if (isset($parametros['pagado_financiado']) && $parametros['pagado_financiado'] > 0) {
            $tipos_pagos[]= 'financiado';
        }
        if (isset($parametros['pagado_habitacion']) && $parametros['pagado_habitacion'] > 0) {
            $tipos_pagos[]= 'habitacion';
        }
        if (isset($parametros['pagado_paypal']) && $parametros['pagado_paypal'] > 0) {
            $tipos_pagos[]= 'paypal';
        }
        foreach ($tipos_pagos as $key => $tipo_pago) {
            $registro = [];
            $registro['id_cliente'] = $parametros['id_cliente'];
            $registro['fecha_hora_concepto'] = date("Y-m-d H:i:s");
            $registro['id_empleado'] = $this->session->userdata('id_usuario');
            $registro['tipo_pago'] = '#'.$tipo_pago;
            $registro['id_centro'] = $this->session->userdata('id_centro_usuario');
            $registro['id_servicio'] = 0;
            $registro['id_producto'] = 0;
            $registro['id_presupuesto'] = $parametros['id_presupuesto'];
            $registro['id_carnet'] = 0;
            $registro['templos'] = 0;
            $registro['pago_a_cuenta'] = 0;
            $registro['fecha_creacion'] = date("Y-m-d H:i:s");
            $registro['id_usuario_creador'] = $this->session->userdata('id_usuario');
            $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
            $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
            $registro['borrado'] = 0;
            $registro['importe_euros'] = $parametros['pagado_'.$tipo_pago];
            $registro['pagado_'.$tipo_pago] = $parametros['pagado_'.$tipo_pago];
            if($tipo_pago == 'transferencia' || $tipo_pago == 'financiado'){
                $registro['estado'] = 'Pendiente justificante' ;
            }else{
                $registro['fecha_pagado'] = date("Y-m-d H:i:s");
                $registro['estado'] = 'Pagado';
            }
            $AqConexion_model->insert('dietario', $registro);
            $sentenciaSQL = "select max(id_dietario) as id from dietario";
            $id_dietario = $AqConexion_model->select($sentenciaSQL, null)[0]['id'];

            $registro = [];
            $registro['id_presupuesto'] = $parametros['id_presupuesto'];;
            $registro['id_item_presupuesto'] = 0;
            $registro['id_cliente'] = $parametros['id_cliente'];
            $registro['id_dietario'] = $id_dietario;
            $registro['cantidad'] = $parametros['pagado_'.$tipo_pago];
            $registro['concepto'] = '';
            $registro['id_usuario_creacion'] = $this->session->userdata('id_usuario');
            $registro['fecha_creacion'] = date("Y-m-d H:i:s");
            $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
            $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
            $registro['borrado'] = 0;
            $AqConexion_model->insert('presupuestos_pagos', $registro);
        }
        return 1;
    }

    function leer_presupuestos_pagos($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $busqueda = "";

        if (isset($parametros['id_presupuesto'])) {
            $busqueda .= " AND D.id_presupuesto = @id_presupuesto ";
        }

        if (isset($parametros['id_dietario'])) {
            $busqueda .= " AND D.id_dietario = @id_dietario ";
        }

        if (isset($parametros['id_cliente'])) {
            $busqueda .= " AND D.id_cliente = @id_cliente ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT 
        D.*,
        DATE_FORMAT(D.fecha_hora_concepto,'%d-%m-%Y %H:%i:%s') as fecha_hora_pago
        FROM dietario D
        WHERE D.borrado = 0 AND id_cita = 0 " . $busqueda . " ORDER BY D.fecha_hora_concepto DESC ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);
        return $datos;
    }

    function leer_pago_presupuesto($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $busqueda = "";

        if (isset($parametros['id_presupuesto_pago'])) {
            $busqueda .= " AND P.id_presupuesto_pago = @id_presupuesto_pago ";
        }
        if (isset($parametros['id_presupuesto'])) {
            $busqueda .= " AND P.id_presupuesto = @id_presupuesto ";
        }

        if (isset($parametros['id_dietario'])) {
            $busqueda .= " AND P.id_dietario = @id_dietario ";
        }

        if (isset($parametros['id_cliente'])) {
            $busqueda .= " AND P.id_cliente = @id_cliente ";
        }

        if (isset($parametros['fecha_creacion_inicio'])) {
            if ($parametros['fecha_inicio'] != "") {
                $busqueda .= " AND (DATE_FORMAT(P.fecha_creacion,'%Y-%m-%d')) >= @fecha_creacion_inicio ";
            }
        }

        if (isset($parametros['fecha_creacion_fin'])) {
            if ($parametros['fecha_fin'] != "") {
                $busqueda .= " AND (DATE_FORMAT(P.fecha_creacion,'%Y-%m-%d')) <= @fecha_fin ";
            }
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT 
        P.*
        FROM presupuestos_pagos P
        WHERE P.borrado = 0 " . $busqueda . " ORDER BY P.fecha_creacion DESC ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);
        return $datos;
    }

    function leer_presupuesto_aceptados($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $busqueda = "";
        if (isset($parametros['fecha_aceptado_inicio'])) {
            if ($parametros['fecha_aceptado_inicio'] != "") {
                $busqueda .= " AND (DATE_FORMAT(pp.fecha_creacion,'%Y-%m-%d')) >= @fecha_aceptado_inicio ";
            }
        }

        if (isset($parametros['fecha_aceptado_fin'])) {
            if ($parametros['fecha_aceptado_fin'] != "") {
                $busqueda .= " AND (DATE_FORMAT(pp.fecha_creacion,'%Y-%m-%d')) <= @fecha_aceptado_fin ";
            }
        }
        if (isset($parametros['id_centro'])) {
            $busqueda .= " AND P.id_centro = @id_centro ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT 
        P.*
        FROM presupuestos P
            LEFT JOIN presupuestos_pagos pp on P.id_presupuesto = pp.id_presupuesto
	        LEFT JOIN centros on centros.id_centro = P.id_centro
        WHERE P.borrado = 0 AND (P.estado = 'Aceptado' OR P.estado = 'Aceptado parcial') AND pp.id_dietario = 0 " . $busqueda . " ORDER BY P.fecha_creacion DESC ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);
        return $datos;
    }

    function marcarPagado($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $registro['estado'] = 'Pagado';
        $registro['justificante_pagado'] = $parametros['fileurl'];
        $registro['comisionfinanciacion'] = $parametros['comisionfinanciacion'];
        $registro['id_usuario_pagado'] = $this->session->userdata('id_usuario');
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['fecha_pagado'] = date("Y-m-d H:i:s");
        $where['id_dietario'] =  $parametros['id_dietario'];
        $AqConexion_model->update('dietario', $registro, $where);
        $return = ($this->db->affected_rows() == 1) ? true : false;
        if($parametros['id_presupuesto'] > 0){
            $sentencia_sql = "SELECT COALESCE(SUM(cantidad), 0) AS total_pagado FROM presupuestos_pagos WHERE id_presupuesto = ".$parametros['id_presupuesto']. " AND id_dietario = 0 AND borrado = 0;";
            $datos = $AqConexion_model->select($sentencia_sql, null);
            $total_pagado =  $datos[0]['total_pagado'];
        
            $sentencia_sql = "UPDATE presupuestos
            SET total_pendiente = COALESCE((
                SELECT SUM(importe_euros)
                FROM dietario
                WHERE id_presupuesto = ".$parametros['id_presupuesto']. " AND borrado = 0 AND estado = 'Pagado' ), 0) + ". $total_pagado ." WHERE id_presupuesto = ".$parametros['id_presupuesto']. ";";
            $AqConexion_model->no_select($sentencia_sql, null);

            $param['id_presupuesto'] = $parametros['id_presupuesto'];
            $param['tipo_item'] = 'Servicio';
            $param['aceptado'] = 1;
            $citas = $this->leer_presupuestos_items($param);
            $this->load->model('Liquidaciones_model');
            foreach ($citas as $key => $value) {
                if($value['id_cita'] > 0){
                    $cifrascita = $this->Liquidaciones_model->recalcularCifrasCita($value['id_cita']);
                }
            }
        }
        
        return $return;
    }

	function tasks_caducados_a_rechazados() {
		$AqConexion_model = new AqConexion_model();
		$fecha_hoy = date('Ymd');
		$sentencia_sql = "UPDATE presupuestos SET estado = 'Rechazado' WHERE fecha_validez < '$fecha_hoy' AND estado IN ('Borrador')";
		$AqConexion_model->no_select($sentencia_sql, null);
		return true;
	}

    function borrarpagoeuros($parametros)
    {
        
        $pago = $this->leer_pago_presupuesto($parametros);

        $AqConexion_model = new AqConexion_model();
        
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['borrado'] = 1;
        $registro['fecha_borrado'] = date("Y-m-d H:i:s");
        $registro['id_usuario_borrado'] = $this->session->userdata('id_usuario');
        $where['id_dietario'] =  $parametros['id_dietario'];
        $AqConexion_model->update('dietario', $registro, $where);

        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['borrado'] = 1;
        $registro['fecha_borrado'] = date("Y-m-d H:i:s");
        $registro['id_usuario_borrado'] = $this->session->userdata('id_usuario');
        $where['id_presupuesto_pago'] =  $pago[0]['id_presupuesto_pago'];
        $AqConexion_model->update('presupuestos_pagos', $registro, $where);

        $this->recalcular_totales($pago[0]['id_presupuesto']);
        return 1;
    }

}
