<?php
class Liquidaciones_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function leer_liquidaciones($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['id_usuario'])) {
            $busqueda .= " AND l.id_usuario = @id_usuario ";
        }

        if (isset($parametros['id_liquidacion'])) {
            $busqueda .= " AND l.id_liquidacion = @id_liquidacion ";
        }

        if (isset($parametros['fecha_desde'])) {
            $busqueda .= " AND DATE_FORMAT(l.fecha_desde,'%Y-%m-%d') >= @fecha_desde ";
        }

        if (isset($parametros['fecha_hasta'])) {
            $busqueda .= " AND DATE_FORMAT(l.fecha_hasta,'%Y-%m-%d') <= @fecha_hasta ";
        }

        if (isset($parametros['estado'])) {
            $busqueda .= " AND l.estado = @estado ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT 
            l.*,
            CONCAT(usuarios.nombre, ' ', usuarios.apellidos) As empleado
        FROM liquidaciones l
        LEFT JOIN usuarios on usuarios.id_usuario = l.id_usuario
        WHERE l.borrado = 0 " . $busqueda . " ORDER BY l.id_liquidacion DESC";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);
        return $datos;
    }

    function leer_citas_empleado($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";
        $firma_lopd = "";
        $orden = "";

        if (isset($parametros['id_cita'])) {
            if ($parametros['id_cita'] > 0) {
                $busqueda .= " AND C.id_cita = @id_cita ";
            }
        }

        if (isset($parametros['id_servicio'])) {
            if ($parametros['id_servicio'] > 0) {
                $busqueda .= " AND C.id_servicio = @id_servicio ";
            }
        }

        if (isset($parametros['no_id_servicio']) && is_array($parametros['no_id_servicio'])) {
            $parametros['no_id_servicio'] = implode(',', $parametros['no_id_servicio']);
            $busqueda .= " AND C.id_servicio NOT IN (@no_id_servicio) ";
        }

        if (isset($parametros['id_familia_servicio'])) {
            if ($parametros['id_familia_servicio'] > 0) {
                $busqueda .= " AND servicios.id_familia_servicio = @id_familia_servicio ";
            }
        }

        if (isset($parametros['no_id_familia_servicio']) && is_array($parametros['no_id_familia_servicio'])) {
            $parametros['no_id_familia_servicio'] = implode(',', $parametros['no_id_familia_servicio']);
            $busqueda .= " AND servicios.id_familia_servicio NOT IN (@no_id_familia_servicio) ";
        }

        if (isset($parametros['no_id_servicio']) && is_array($parametros['no_id_servicio'])) {
            $no_ids_servicios = implode(',', $parametros['no_id_servicio']);
            $busqueda .= " AND C.id_servicio NOT IN (" . $no_ids_servicios . ") ";
        }

        if (isset($parametros['id_empleado'])) {
            if ($parametros['id_empleado'] > 0) {
                $busqueda .= " AND C.id_usuario_empleado = @id_empleado ";
            }
        }

        if (isset($parametros['fecha'])) {
            if ($parametros['fecha'] != "") {
                $parametros['fecha'] = str_replace("-", "/", $parametros['fecha']);
                $busqueda .= " AND DATE_FORMAT(C.fecha_hora_inicio,'%d/%m/%Y') = @fecha ";
            }
        }

        if (isset($parametros['fecha_desde'])) {
            $busqueda .= " AND DATE_FORMAT(C.fecha_hora_inicio,'%Y-%m-%d') >= @fecha_desde ";
        }

        if (isset($parametros['fecha_hasta'])) {
            $busqueda .= " AND DATE_FORMAT(C.fecha_hora_inicio,'%Y-%m-%d') <= @fecha_hasta ";
        }

        if (isset($parametros['estado'])) {
            $busqueda .= " AND C.estado = @estado ";
        }

        if (isset($parametros['lopd_cumplimentada'])) {
            $firma_lopd = " (select count(id) from clientes_firmas_lopd where id_cliente = C.id_cliente) as existe_firma, ";
        }

        if (isset($parametros['programadas_finalizadas'])) {
            if ($parametros['programadas_finalizadas'] == 1) {
                $busqueda .= " AND (C.estado = 'Programada' OR C.estado = 'Finalizado') AND C.duracion > 0 ";
            }
        }

        if (isset($parametros['orden_cliente'])) {
            $orden .= " id_cliente,fecha_hora_inicio ";
        } else {
            $orden .= " fecha_hora_inicio ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT
            C.id_cita,
            C.id_servicio,
            servicios.id_familia_servicio,
            C.id_usuario_empleado,
            C.id_cliente,
            C.duracion,
            C.estado,
            {$firma_lopd}
            C.observaciones,
            DATE_FORMAT(C.fecha_hora_inicio,'%d-%m-%Y') as fecha_inicio,
            DATE_FORMAT(C.fecha_hora_inicio,'%H:%i') as hora_inicio,
            DATE_FORMAT(C.fecha_hora_inicio + INTERVAL C.duracion MINUTE,'%H:%i') as hora_fin,
            DATE_FORMAT(C.fecha_hora_inicio,'%Y-%m-%d') as fecha_inicio_aaaammdd,
            C.fecha_hora_inicio,
            CONCAT(clientes.nombre, ' ', clientes.apellidos) As cliente,
            servicios.abreviatura as servicio,
            servicios_familias.nombre_familia AS familia,
            CONCAT(usuarios.nombre, ' ', usuarios.apellidos) As empleado,
            usuarios.color,
            servicios.pvp,
            servicios.templos,
            C.solo_este_empleado,
            presupuestos_items.id_presupuesto_item,
            presupuestos_items.dientes,
            presupuestos_items.id_presupuesto,
            presupuestos_items.coste,
            presupuestos.nro_presupuesto
        FROM citas AS C
        LEFT JOIN clientes on clientes.id_cliente = C.id_cliente
        LEFT JOIN servicios on servicios.id_servicio = C.id_servicio
        LEFT JOIN servicios_familias on servicios.id_familia_servicio = servicios_familias.id_familia_servicio
        LEFT JOIN usuarios on usuarios.id_usuario = C.id_usuario_empleado
        LEFT JOIN presupuestos_items on presupuestos_items.id_cita = C.id_cita
        LEFT JOIN presupuestos on presupuestos_items.id_presupuesto = presupuestos_items.id_presupuesto
        WHERE C.borrado = 0 " . $busqueda . " GROUP BY C.id_cita ORDER BY " . $orden;
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return (is_array($datos)) ? $datos : [];
    }

    function leer_comisiones($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['id_usuario'])) {
            $busqueda .= " AND uc.id_usuario = @id_usuario ";
        }

        if (isset($parametros['id_comision'])) {
            $busqueda .= " AND uc.id_comision = @id_comision ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT 
            uc.*,
            CASE 
            WHEN uc.item = 'producto' THEN pf.nombre_familia
            WHEN uc.item = 'servicio' THEN sf.nombre_familia
            ELSE NULL
            END AS nombre_familia,
            CASE 
            WHEN uc.item = 'producto' THEN p.nombre_producto
            WHEN uc.item = 'servicio' THEN s.nombre_servicio
            ELSE NULL
            END AS nombre_item,
            (1 - 1) AS pvpacumulado,
            (1 - 1) AS gastoacumulado,
            (1 - 1) AS num_citas
        FROM usuarios_comisiones uc
        LEFT JOIN productos_familias pf ON uc.item = 'producto' AND uc.id_familia_item = pf.id_familia_producto
        LEFT JOIN productos p ON uc.item = 'producto' AND uc.id_item = p.id_producto
        LEFT JOIN servicios_familias sf ON uc.item = 'servicio' AND uc.id_familia_item = sf.id_familia_servicio
        LEFT JOIN servicios s ON uc.item = 'servicio' AND uc.id_item = s.id_servicio
        WHERE uc.borrado = 0 " . $busqueda . " ORDER BY uc.id_item DESC, uc.id_familia_item DESC, uc.item DESC";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);
        return $datos;
    }

    function presupuestos_item_detail($parametros)
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

        if (isset($parametros['id_usuario'])) {
            $busqueda .= " AND presupuestos_items.id_usuario <= @id_usuario ";
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
            presupuestos_items.pvp AS coste,
	        presupuestos_items.dto,
            presupuestos_items.dto_euros,
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
            presupuestos_items.comi_fin,
	        usuarios.nombre AS e_nombre,
	        usuarios.apellidos AS e_apellidos,
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
	        END AS duracion

	        FROM presupuestos_items
                LEFT JOIN usuarios on usuarios.id_usuario = presupuestos_items.id_usuario
                LEFT JOIN productos ON presupuestos_items.tipo_item = 'Producto' AND presupuestos_items.id_item = productos.id_producto
                LEFT JOIN servicios ON presupuestos_items.tipo_item = 'Servicio' AND presupuestos_items.id_item = servicios.id_servicio
                LEFT JOIN productos_familias ON presupuestos_items.tipo_item = 'Producto' AND productos_familias.id_familia_producto = productos.id_familia_producto
                LEFT JOIN servicios_familias ON presupuestos_items.tipo_item = 'Servicio' AND servicios_familias.id_familia_servicio = servicios.id_familia_servicio
	        WHERE presupuestos_items.borrado = 0 
        " . $busqueda . " ORDER  BY presupuestos_items.id_presupuesto_item DESC ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);
        return (is_array($datos)) ? $datos : [];
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


        // ... Leemos los registros
        $sentencia_sql = "SELECT 
        presupuestos.*,
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
        (SELECT SUM(importe_euros) FROM dietario WHERE id_presupuesto = presupuestos.id_presupuesto AND borrado = 0 AND estado = 'Pagado') AS total_pagado,
        (SELECT SUM(pi.pvp) AS total_pvp FROM presupuestos_items pi JOIN citas c ON pi.id_cita = c.id_cita WHERE pi.id_presupuesto = presupuestos.id_presupuesto AND c.estado = 'Finalizado' AND pi.borrado = 0 AND c.borrado = 0) AS total_gastado

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

    function leer_liquidaciones_citas($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $busqueda = "";
        if (isset($parametros['id_cita'])) {
            if ($parametros['id_cita'] > 0) {
                $busqueda .= " AND LC.id_cita = @id_cita ";
            }
        }

        if (isset($parametros['ids_cita'])) {
            if ($parametros['ids_cita'] != '') {
                $busqueda .= " AND LC.id_liquidacion_cita IN (@ids_cita) ";
            }
        }

        if (isset($parametros['id_liquidacion'])) {
            if ($parametros['id_liquidacion'] > 0) {
                $busqueda .= " AND LC.id_liquidacion = @id_liquidacion ";
            }
        }

        if (isset($parametros['id_usuario'])) {
            if ($parametros['id_usuario'] > 0) {
                $busqueda .= " AND LC.id_usuario = @id_usuario ";
            }
        }

        if (isset($parametros['fecha_desde'])) {
            $busqueda .= " AND DATE_FORMAT(LC.fecha_cita,'%Y-%m-%d') >= @fecha_desde ";
        }

        if (isset($parametros['fecha_hasta'])) {
            $busqueda .= " AND DATE_FORMAT(LC.fecha_cita,'%Y-%m-%d') <= @fecha_hasta ";
        }

        if (isset($parametros['estado'])) {
            $busqueda .= " AND LC.estado = @estado ";
        }


        // ... Leemos los registros
        $sentencia_sql = "SELECT
            LC.*,
            CONCAT(clientes.nombre, ' ', clientes.apellidos) As cliente,
            servicios.abreviatura as servicio,
            servicios_familias.nombre_familia AS familia
        FROM liquidaciones_citas AS LC
        LEFT JOIN clientes on clientes.id_cliente = LC.id_cliente
        LEFT JOIN servicios on servicios.id_servicio = LC.id_item
        LEFT JOIN servicios_familias on servicios.id_familia_servicio = servicios_familias.id_familia_servicio
        WHERE LC.borrado = 0 
        AND LC.id_liquidacion_cita = (
            SELECT MAX(LC2.id_liquidacion_cita)
            FROM liquidaciones_citas AS LC2
            WHERE LC2.id_cita = LC.id_cita 
            AND LC2.borrado = 0
        ) " . $busqueda . " GROUP BY LC.id_cita ORDER BY fecha_cita ASC";

        $sentencia2 = "SELECT
            LC.*,
            presupuestos_items.dientes,
            presupuestos_items.id_presupuesto,
            citas.observaciones,
            dietario.notas_pago_descuento,
            presupuestos.estado_relacionado,
            CASE
                WHEN LC.id_cliente = 0 THEN LC.concepto
                ELSE CONCAT(clientes.nombre, ' ', clientes.apellidos)
            END AS cliente,
            CASE
                WHEN LC.id_item = 0 THEN 'LINEA'
                ELSE servicios.abreviatura
            END AS servicio,
            CASE
                WHEN LC.id_familia_item = 0 THEN 'GENERADA'
                ELSE servicios_familias.nombre_familia
            END AS familia
        FROM liquidaciones_citas AS LC
        LEFT JOIN clientes ON LC.id_cliente = clientes.id_cliente
        LEFT JOIN servicios ON LC.id_item = servicios.id_servicio
        LEFT JOIN servicios_familias ON LC.id_familia_item = servicios_familias.id_familia_servicio
        LEFT JOIN presupuestos_items ON LC.id_cita = presupuestos_items.id_cita
        LEFT JOIN presupuestos ON presupuestos_items.id_presupuesto = presupuestos.id_presupuesto
        LEFT JOIN citas ON LC.id_cita = citas.id_cita AND LC.id_cita > 0
        LEFT JOIN dietario ON LC.id_cita = dietario.id_cita AND LC.id_cita > 0
        WHERE LC.borrado = 0 
        AND LC.id_item NOT IN (5188,5189) /*NO TAC */
        AND (LC.id_cita = 0 OR (LC.id_cita > 0 AND LC.id_liquidacion_cita = (
            SELECT MAX(LC2.id_liquidacion_cita)
            FROM liquidaciones_citas AS LC2
            WHERE LC2.id_cita = LC.id_cita 
            AND LC2.borrado = 0
            GROUP BY LC.id_cita
            ORDER BY fecha_cita ASC
        ) ) )". $busqueda . " GROUP BY LC.id_liquidacion_cita ORDER BY fecha_cita ASC";  


        $busqueda2=str_replace("LC.","LC2.",$busqueda);

        $sentencia3 = "SELECT
                LC.*,
                presupuestos_items.dientes,
                presupuestos_items.id_presupuesto,
                citas.observaciones,
                dietario.notas_pago_descuento,
                presupuestos.estado_relacionado,
                CASE
                    WHEN LC.id_cliente = 0 THEN LC.concepto
                    ELSE CONCAT(clientes.nombre, ' ', clientes.apellidos)
                END AS cliente,
                CASE
                    WHEN LC.id_item = 0 THEN 'LINEA'
                    ELSE servicios.abreviatura
                END AS servicio,
                CASE
                    WHEN LC.id_familia_item = 0 THEN 'GENERADA'
                    ELSE servicios_familias.nombre_familia
                END AS familia
            FROM LiquidacionesCitasVista LC
            LEFT JOIN clientes ON LC.id_cliente = clientes.id_cliente AND LC.id_cita > 0
            LEFT JOIN servicios ON LC.id_item = servicios.id_servicio AND LC.id_cita > 0
            LEFT JOIN servicios_familias ON LC.id_familia_item = servicios_familias.id_familia_servicio AND LC.id_cita > 0
            LEFT JOIN presupuestos_items ON LC.id_cita = presupuestos_items.id_cita AND LC.id_cita > 0
            LEFT JOIN presupuestos ON presupuestos_items.id_presupuesto = presupuestos.id_presupuesto AND LC.id_cita > 0
            LEFT JOIN citas ON LC.id_cita = citas.id_cita AND LC.rn = 1 AND LC.id_cita > 0
            LEFT JOIN dietario ON LC.id_cita = dietario.id_cita AND LC.rn = 1 AND LC.id_cita > 0 ".
//            WHERE LC.rn = 1 $busqueda  GROUP BY LC.id_liquidacion_cita ORDER BY fecha_cita ASC;
            // CHAINS 20240319 - Quitamos el LC.rn=1 porque limita las limneas metidas manualmente
            //                   Añadimos la condicion LC.id_cita > 0 y lo unimos con otra consulta para las id_cita = 0
            "   WHERE LC.id_cita>0 $busqueda  GROUP BY LC.id_liquidacion_cita  ".
            " UNION 
            (SELECT LC2.*,
                '' AS dientes,
                '' AS id_presupuesto,
                '' AS observaciones,
                '' AS notas_pago_descuento,
                '' AS estado_relacionado,
                '' AS cliente,
                LC2.concepto AS servicio,
                'LINEA GENERADA' AS familia
            FROM LiquidacionesCitasVista LC2
            WHERE LC2.id_cita =0 ".$busqueda2." GROUP BY LC2.id_liquidacion_cita)
            
            ORDER BY fecha_cita ASC 
            ";


        $datos = $AqConexion_model->select($sentencia3, $parametros);
        return (is_array($datos)) ? $datos : [];
    }

    function leer_liquidaciones_citas_conceptos($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $busqueda = "";
      
        if (isset($parametros['ids_cita'])) {
            if ($parametros['ids_cita'] != '') {
                $busqueda .= " AND LC.id_liquidacion_cita IN (@ids_cita) ";
            }
        }

        if (isset($parametros['id_liquidacion'])) {
            if ($parametros['id_liquidacion'] > 0) {
                $busqueda .= " AND LC.id_liquidacion = @id_liquidacion ";
            }
        }

        if (isset($parametros['id_usuario'])) {
            if ($parametros['id_usuario'] > 0) {
                $busqueda .= " AND LC.id_usuario = @id_usuario ";
            }
        }

        if (isset($parametros['fecha_desde'])) {
            $busqueda .= " AND DATE_FORMAT(LC.fecha_cita,'%Y-%m-%d') >= @fecha_desde ";
        }

        if (isset($parametros['fecha_hasta'])) {
            $busqueda .= " AND DATE_FORMAT(LC.fecha_cita,'%Y-%m-%d') <= @fecha_hasta ";
        }

        if (isset($parametros['estado'])) {
            $busqueda .= " AND LC.estado = @estado ";
        }


        // ... Leemos los registros
        $sentencia_sql = "SELECT
            LC.*,
            LC.concepto As cliente,
            'linea' as servicio,
            'generada' AS familia
        FROM liquidaciones_citas AS LC
        WHERE LC.borrado = 0 
        " . $busqueda . " GROUP BY LC.id_liquidacion_cita ORDER BY fecha_cita ASC";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return (is_array($datos)) ? $datos : [];
    }

    function leer_liquidaciones_comisiones($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $busqueda = "";
        if (isset($parametros['id_liquidacion'])) {
            $busqueda .= " AND lco.id_liquidacion = @id_liquidacion ";
        }

        if (isset($parametros['id_usuario'])) {
            $busqueda .= " AND lco.id_usuario = @id_usuario ";
        }

        if (isset($parametros['id_comision'])) {
            $busqueda .= " AND lco.id_comision = @id_comision ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT 
            lco.*,
            CASE 
            WHEN lco.item = 'producto' THEN pf.nombre_familia
            WHEN lco.item = 'servicio' THEN sf.nombre_familia
            ELSE NULL
            END AS nombre_familia,
            CASE 
            WHEN lco.item = 'producto' THEN p.nombre_producto
            WHEN lco.item = 'servicio' THEN s.nombre_servicio
            ELSE NULL
            END AS nombre_item
        FROM liquidaciones_comisiones lco
        LEFT JOIN productos_familias pf ON lco.item = 'producto' AND lco.id_familia_item = pf.id_familia_producto
        LEFT JOIN productos p ON lco.item = 'producto' AND lco.id_item = p.id_producto
        LEFT JOIN servicios_familias sf ON lco.item = 'servicio' AND lco.id_familia_item = sf.id_familia_servicio
        LEFT JOIN servicios s ON lco.item = 'servicio' AND lco.id_item = s.id_servicio
        WHERE lco.borrado = 0 " . $busqueda . " ORDER BY lco.id_item DESC, lco.id_familia_item DESC, lco.item DESC";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);
        return $datos;
    }

    function pagos_presupuesto($parametros)
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

        if (isset($parametros['tipo_pago'])) {
            $busqueda .= " AND D.tipo_pago LIKE '%" . $parametros['tipo_pago'] . "%' ";
        }

        if (isset($parametros['estado'])) {
            $busqueda .= " AND D.estado = @estado ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT 
        D.*,
        DATE_FORMAT(D.fecha_hora_concepto,'%d-%m-%Y %H:%i:%s') as fecha_hora_pago
        FROM dietario D
        WHERE D.borrado = 0 " . $busqueda . " ORDER BY D.fecha_hora_concepto DESC ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);
        return $datos;
    }

    function totalcomisionfinanciacion($id_presupuesto)
    {
        $AqConexion_model = new AqConexion_model();
        $sentencia_sql = "SELECT COALESCE(SUM(comisionfinanciacion) , 0)  AS comisiontotal
        FROM dietario 
        WHERE borrado = 0 AND id_presupuesto = " . $id_presupuesto . " AND estado = 'Pagado' AND pagado_financiado > 0 ";
        $datos = $AqConexion_model->select($sentencia_sql, null);
        return (isset($datos)) ? $datos[0]['comisiontotal'] : 0;
    }

    function actualizar_gastos_laboratorio($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $registro['gastos_lab'] = $parametros['gastos_lab'];
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $where['id_presupuesto_item'] = $parametros['id_presupuesto_item'];
        $AqConexion_model->update('presupuestos_items', $registro, $where);

        $AqConexion_model = new AqConexion_model();
        $registro['gastos_lab'] = $parametros['gastos_lab'];
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $where['id_presupuesto_item'] = $parametros['id_presupuesto_item'];
        $AqConexion_model->update('liquidaciones_citas', $registro, $where);
        $param = [
            'id_presupuesto_item' => $parametros['id_presupuesto_item'],
        ];
        $item = $this->Liquidaciones_model->presupuestos_item_detail($param);
        $this->liquidacion_cita($item[0]['id_cita']);

        return $this->db->affected_rows();
    }

    function actualizar_datos_cita_liquidacion($parametros)
    {
        $registro=[];
        $AqConexion_model = new AqConexion_model();
        if(isset($parametros['gastos_lab'])) $registro['gastos_lab'] = $parametros['gastos_lab'];
        if(isset($parametros['com_financiacion'])) $registro['com_financiacion'] = $parametros['com_financiacion'];
        if(isset($parametros['dtop'])) $registro['dtop'] = $parametros['dtop'];
        if(isset($parametros['dto'])) $registro['dto'] = $parametros['dto'];
        if(isset($parametros['pvp'])) $registro['pvp'] = $parametros['pvp'];
        $registro['total'] = $parametros['pvp'] - $parametros['dtop'] - $parametros['dto'] - $parametros['gastos_lab'] - $parametros['com_financiacion'];

        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $where['id_liquidacion_cita'] = $parametros['id_liquidacion_cita'];
        $AqConexion_model->update('liquidaciones_citas', $registro, $where);
        return $this->db->affected_rows();
    }


    function liquidacion_cita($id_cita)
    {
        $param = [
            'id_cita' => $id_cita,
        ];
        $cita = $this->Liquidaciones_model->leer_citas_empleado($param);
        if (is_array($cita) && isset($cita[0])) {
            $cita = $cita[0];
            // buscar la liquidacion de esa cita, si no existe, se continua igual. Si existe, se actualiza esa liquidacion
            $cita_liquidada = $this->Liquidaciones_model->leer_liquidaciones_citas($param);
            if(count($cita_liquidada) < 1){
                $item = $this->Liquidaciones_model->presupuestos_item_detail($param);
                if (is_array($item) && isset($item[0])) {
                    $item = $item[0];
                    $datos = $this->Liquidaciones_model->calcularCosteCita($cita);
                    $AqConexion_model = new AqConexion_model();
                    // ... Datos generales.
                    $registro['id_usuario'] = $cita['id_usuario_empleado'];
                    $registro['id_presupuesto_item'] = $item['id_presupuesto_item'];
                    $registro['id_cita'] = $id_cita;
                    $registro['fecha_cita'] =  $cita['fecha_hora_inicio'];
                    $registro['item'] =  $item['tipo_item'];
                    $registro['id_item'] = $item['id_item'];
                    $registro['id_familia_item'] =  $cita['id_familia_servicio'];
                    $registro['id_cliente'] = $cita['id_cliente'];
                    $registro['pvp'] =  $datos['pvpinicial'];
                    $registro['dto'] = $datos['dto_propio_euros'];
                    $registro['dtop'] = $datos['dto_presupuesto_euros'];
                    $registro['com_financiacion'] = $datos['comisionItem'];
                    $registro['gastos_lab'] = $datos['gastos_lab'];
                    $registro['total'] = $datos['pvpfinal']; //number_format(($datos['pvpfinal'] - $datos['dto_propio_euros'] - $datos['dto_presupuesto_euros']), 2);
                    $registro['estado'] = 0;
                    $registro['id_usuario_creacion'] = $this->session->userdata('id_usuario');
                    $registro['fecha_creacion'] = date("Y-m-d H:i:s");
                    $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
                    $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
                    $registro['borrado'] = 0;
                    $AqConexion_model->insert('liquidaciones_citas', $registro);
                    return $this->db->insert_id();
                } else {
                    $AqConexion_model = new AqConexion_model();
                    // NO ES UNA CITA DE PRESUPUESTO
                    $registro['id_usuario'] = $cita['id_usuario_empleado'];
                    $registro['id_presupuesto_item'] = 0;
                    $registro['id_cita'] = $id_cita;
                    $registro['fecha_cita'] =  $cita['fecha_hora_inicio'];
                    $registro['item'] =  'Servicio';
                    $registro['id_item'] = $cita['id_servicio'];
                    $registro['id_familia_item'] =  $cita['id_familia_servicio'];
                    $registro['id_cliente'] = $cita['id_cliente'];
                    $registro['dtop'] = 0;
                    $registro['com_financiacion'] = 0;
                    $registro['gastos_lab'] = 0;
                    $registro['estado'] = 0;
                    $registro['id_usuario_creacion'] = $this->session->userdata('id_usuario');
                    $registro['fecha_creacion'] = date("Y-m-d H:i:s");
                    $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
                    $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
                    $registro['borrado'] = 0;

                    // se busca el dietario para pillar los importes con o sin descuento
                    $this->db->where('id_cita', $id_cita);
                    $this->db->where('borrado', 0);
                    $dietario =  $this->db->get('dietario');
                    if ($dietario->num_rows() > 0) {
                        $dietario = $dietario->row_array();
                        $registro['pvp'] =  $dietario['importe_euros'];
                        $dto = 0;
                        if ($dietario['descuento_euros'] > 0) {
                            $dto += $dietario['descuento_euros'];
                        }
                        if ($dietario['descuento_porcentaje'] > 0) {
                            $dto_e = $dietario['importe_euros'] * $dietario['descuento_porcentaje'] / 100;
                            $dto += $dto_e;
                        }
                        $registro['dto'] = $dto;
                        $registro['total'] = $dietario['importe_euros'] - $dto;
                    } else {
                        // si no esta ene l dietario, se pillan los costes de la cita
                        $registro['pvp'] =  $cita['pvp'];
                        $registro['dto'] = 0;
                        $registro['total'] = $cita['pvp'];
                    }
                    $AqConexion_model->insert('liquidaciones_citas', $registro);
                    return $this->db->insert_id();
                }
            }else{
                $this->Liquidaciones_model->recalcularCifrasCita($id_cita);
                return $cita_liquidada[0]['id_liquidacion_cita'];
            }
        }
    }
	
	public function calcularCosteCita($cita)
    {
        $param = [
            'id_cita' => $cita['id_cita'],
        ];
        $item = $this->Liquidaciones_model->presupuestos_item_detail($param);
        $pvpinicial = $cita['pvp'];
        $pvpfinal = $cita['pvp'];
        $pvpfinal_fly = $cita['pvp'];
        $dto_propio_euros = 0;
        $dto_presupuesto_euros = 0;
        $comisionItem = 0;
        $gastos_lab = 0;
        //printr($item[0]);
        if ($item) {
            $pvpinicial = $item[0]['pvp'];
            $pvpfinal = $item[0]['pvp'];
            $pvpfinal_fly = $item[0]['pvp'];
            $id_presupuesto = $item[0]['id_presupuesto'];
            $param = [
                'id_presupuesto' => $id_presupuesto
            ];
            $presupuesto = $this->Liquidaciones_model->leer_presupuestos($param)[0];
            $totalPresupuesto = $presupuesto['total_aceptado']; // presupuesto aceptado, con descuento aplicado

            // DESCUENTO PROPIO DEL ITEM
            if ($item[0]['dto'] > 0) {
                $dto_propio_euros = $pvpfinal * ($item[0]['dto'] / 100);
                $pvpfinal_fly = $pvpfinal_fly - $dto_propio_euros;
            }
            if ($item[0]['dto_euros'] > 0) {
                $dto_propio_euros += $item[0]['dto_euros'];
                $pvpfinal_fly = $pvpfinal_fly - $dto_propio_euros;
            }
            // DESCUENTO GENERAL EUROS PRIMERO Y PORCENTAJE DESPUES
            if ($presupuesto['dto_euros'] > 0) {
                $totalPresupuestoSinDescuento = $presupuesto['totalpresupuesto'] + $presupuesto['dto_euros'];
                $proporcion = $item[0]['coste'] / $totalPresupuestoSinDescuento;
                $dto_presupuesto_euros = number_format($proporcion * $presupuesto['dto_euros'], 2);
                $nuevoPVP = $item[0]['coste'] - $dto_presupuesto_euros;
                $item[0]['dtop'] = $dto_presupuesto_euros;
                $item[0]['coste'] = number_format($nuevoPVP, 2);
                $pvpfinal_fly = $pvpfinal_fly - $dto_presupuesto_euros;
            } elseif ($presupuesto['dto_100'] > 0) {
                $dtog = $presupuesto['dto_100'];  // descuento, en %
                $descuentoNum = number_format($dtog / 100, 2); // descuento, en multiplicador
                $totalPresupuestoSinDescuento = $presupuesto['totalpresupuesto'] / (1 - $descuentoNum); // precio total sin descuento
                $descuentoEur = $totalPresupuestoSinDescuento - $presupuesto['totalpresupuesto']; // total descontado del presupuesto
                $proporcion = $item[0]['coste'] / $totalPresupuestoSinDescuento; //Proporcion del item en el total del presupuesto
                $dto_presupuesto_euros = number_format($proporcion * $descuentoEur, 2); // descuento especifico sobre el item  
                $nuevoPVP = $item[0]['coste'] - $dto_presupuesto_euros; // nuevo precio, con el descuento especifico aplicado
                $item[0]['dtop'] = $dto_presupuesto_euros;
                $item[0]['coste'] = number_format($nuevoPVP, 2);
                $pvpfinal_fly = $pvpfinal_fly - $dto_presupuesto_euros;
            }

            // COMISION POR PAGOS FINANCIADOS
            /* CHAINS 20240613 - Eliminamos esta parte
            $comisionTotal = $this->Liquidaciones_model->totalcomisionfinanciacion($id_presupuesto);
            if ($comisionTotal > 0) {
                $proporcion = $pvpfinal_fly / $totalPresupuesto;
                $parteComision = $proporcion * $comisionTotal;
                if (is_numeric($parteComision)) {
                    $comisionItem = number_format($parteComision, 2);
                    if (is_numeric($comisionItem)) {
                        $pvpfinal_fly = $pvpfinal_fly - $comisionItem;
                    }
                }
            }
            */
            $comisionItem=$item[0]['comi_fin'];
            if(is_numeric($comisionItem)){
                $pvpfinal_fly = $pvpfinal_fly - $comisionItem;
            }

            // GASTOS DE LABORATORIO
            if ($item[0]['gastos_lab'] > 0) {
                $gastos_lab = $item[0]['gastos_lab'];
                $pvpfinal_fly = $pvpfinal_fly - $gastos_lab;
            }
        }

        $return = [
            'pvpinicial' => $pvpinicial,
            'pvpfinal' => $pvpfinal_fly,
            'dto_propio_euros' => $dto_propio_euros,
            'dto_presupuesto_euros' => $dto_presupuesto_euros,
            'comisionItem' => $comisionItem,
            'gastos_lab' => $gastos_lab
        ];
        return $return;
    }

    public function recalcularCifrasCita($id_cita)
    {
        $param = [
            'id_cita' => $id_cita,
        ];
        $cita = $this->Liquidaciones_model->leer_citas_empleado($param);
        if (is_array($cita) && isset($cita[0])) {
            $cita = $cita[0];
            $item = $this->Liquidaciones_model->presupuestos_item_detail($param);
            if (is_array($item) && isset($item[0])) {
                $item = $item[0];
                $datos = $this->Liquidaciones_model->calcularCosteCita($cita);
                // ... Datos generales.
                $registro['pvp'] =  $datos['pvpinicial'];
                $registro['dto'] = $datos['dto_propio_euros'];
                $registro['dtop'] = $datos['dto_presupuesto_euros'];
                $registro['com_financiacion'] = $datos['comisionItem'];
                $registro['gastos_lab'] = $datos['gastos_lab'];
                $registro['total'] = $datos['pvpfinal']; //number_format(($datos['pvpfinal'] - $datos['dto_propio_euros'] - $datos['dto_presupuesto_euros']), 2);
            } else {
                // se busca el dietario para pillar los importes con o sin descuento
                $this->db->where('id_cita', $id_cita);
                $this->db->where('borrado', 0);
                $dietario =  $this->db->get('dietario');
                if ($dietario->num_rows() > 0) {
                    $dietario = $dietario->row_array();
                    $registro['pvp'] =  $dietario['importe_euros'];
                    $dto = 0;
                    if ($dietario['descuento_euros'] > 0) {
                        $dto += $dietario['descuento_euros'];
                    }
                    if ($dietario['descuento_porcentaje'] > 0) {
                        $dto_e = $dietario['importe_euros'] * $dietario['descuento_porcentaje'] / 100;
                        $dto += $dto_e;
                    }
                    $registro['dto'] = $dto;
                    $registro['total'] = $dietario['importe_euros'] - $dto;
                } else {
                    // si no esta ene l dietario, se pillan los costes de la cita
                    $registro['pvp'] =  $cita['pvp'];
                    $registro['dto'] = 0;
                    $registro['total'] = $cita['pvp'];
                }
            }

            $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
            $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
            $where['id_cita'] = $id_cita;
            $AqConexion_model = new AqConexion_model();
            $AqConexion_model->update('liquidaciones_citas', $registro, $where);


           /* $datos = $this->Liquidaciones_model->calcularCosteCita($cita);
            $registro['dto'] = $datos['dto_propio_euros'];
            $registro['dtop'] = $datos['dto_presupuesto_euros'];
            $registro['com_financiacion'] = $datos['comisionItem'];
            $registro['gastos_lab'] = $datos['gastos_lab'];
            $registro['total'] = $datos['pvpfinal'];
            $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
            $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
            $where['id_cita'] = $id_cita;

            $AqConexion_model = new AqConexion_model();
            $AqConexion_model->update('liquidaciones_citas', $registro, $where);*/
        }
    }

    public function registrar_liquidacion($paramentros)
    {
        $AqConexion_model = new AqConexion_model();
        // ... Datos generales.
        $registro['id_usuario'] = $paramentros['id_usuario'];
        $registro['fecha_desde'] = $paramentros['fecha_desde'];
        $registro['fecha_hasta'] = $paramentros['fecha_hasta'];
        if(isset($paramentros['mes'])){
            $registro['mes'] = $paramentros['mes'];
        }
        $registro['id_usuario_creacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_creacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['borrado'] = 0;
        $AqConexion_model->insert('liquidaciones', $registro);
        return $this->db->insert_id();
    }

    public function actualizar_total_liquidacion_($id_liquidacion, $total)
    {
        $AqConexion_model = new AqConexion_model();
        $registro['total'] = $total;
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $where['id_liquidacion'] = $id_liquidacion;
        $AqConexion_model->update('liquidaciones', $registro, $where);
    }

    public function actualizar_total_liquidacion($id_liquidacion, $total)
    {
        $this->db->select_sum('total_comision');
        $this->db->where('id_liquidacion', $id_liquidacion);
        $this->db->where('borrado', 0);
        $total = $this->db->get('liquidaciones_comisiones')->row()->total_comision;
        $datos = [
            'total' => $total,
            'id_usuario_modificacion' => $this->session->userdata('id_usuario'),
            'fecha_modificacion' => date("Y-m-d H:i:s"),
        ];
        $this->db->where('id_liquidacion', $id_liquidacion);
        $this->db->update('liquidaciones', $datos);
        return $this->db->affected_rows();
    }

    public function registrar_comision_liquidacion($paramentros)
    {
        $AqConexion_model = new AqConexion_model();
        // ... Datos generales.
        $registro['id_liquidacion'] = $paramentros['id_liquidacion'];
        $registro['id_usuario'] = $paramentros['id_usuario'];
        $registro['id_comision'] = $paramentros['id_comision'];
        $registro['item'] = $paramentros['item'];
        $registro['id_item'] = $paramentros['id_item'];
        $registro['id_familia_item'] = $paramentros['id_familia_item'];
        $registro['tipo'] = $paramentros['tipo'];
        $registro['importe_desde'] = $paramentros['importe_desde'];
        $registro['importe_hasta'] = $paramentros['importe_hasta'];
        $registro['comision'] = $paramentros['comision'];
        $registro['pvpacumulado'] = $paramentros['pvpacumulado'];
        $registro['total_comision'] = $paramentros['total_comision'];

        $registro['id_usuario_creacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_creacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['borrado'] = 0;
        $AqConexion_model->insert('liquidaciones_comisiones', $registro);
        return $this->db->insert_id();
    }

    public function actualizar_comision_liquidacion($paramentros)
    {
        $AqConexion_model = new AqConexion_model();
        // ... Datos generales.
        $registro['item'] = $paramentros['item'];
        $registro['id_item'] = $paramentros['id_item'];
        $registro['id_familia_item'] = $paramentros['id_familia_item'];
        $registro['tipo'] = $paramentros['tipo'];
        $registro['importe_desde'] = $paramentros['importe_desde'];
        $registro['importe_hasta'] = $paramentros['importe_hasta'];
        $registro['comision'] = $paramentros['comision'];
        $registro['pvpacumulado'] = $paramentros['pvpacumulado'];
        $registro['total_comision'] = $paramentros['total_comision'];
        
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['borrado'] = 0;
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        if(isset($paramentros['id_liquidacion_comision'])){
            $where['id_liquidacion_comision'] =  $paramentros['id_liquidacion_comision'];
        }else{
            $where['id_liquidacion'] =  $paramentros['id_liquidacion'];
            $where['id_usuario'] =  $paramentros['id_usuario'];
            $where['id_comision'] =  $paramentros['id_comision'];
            $where['borrado'] =  0;
        }
        $AqConexion_model->update('liquidaciones_comisiones', $registro, $where);
        $actualizado = $this->db->affected_rows();
        return ($actualizado == 1) ? 1 : 0;
    }

    public function borrar_comision_liquidacion($paramentros)
    {
        $AqConexion_model = new AqConexion_model();
        // ... Datos generales.
        $registro['borrado'] = 1;
        $registro['id_usuario_borrado'] = $this->session->userdata('id_usuario');
        $registro['fecha_borrado'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        if(isset($paramentros['id_liquidacion_comision'])){
            $where['id_liquidacion_comision'] =  $paramentros['id_liquidacion_comision'];
        }else{
            $where['id_liquidacion'] =  $paramentros['id_liquidacion'];
            $where['id_usuario'] =  $paramentros['id_usuario'];
            $where['id_comision'] =  $paramentros['id_comision'];
        }
        $AqConexion_model->update('liquidaciones_comisiones', $registro, $where);
        $borrado = $this->db->affected_rows();
        return ($borrado == 1) ? 1 : 0;
    }

    public function citasLiquidacion($id_liquidaciones_citas, $id_liquidacion)
    {

        $AqConexion_model = new AqConexion_model();
        $param['id_liquidacion'] = $id_liquidacion;
        $param['userdata'] = $this->session->userdata('id_usuario');
        $param['fecha'] = date('Y-m-d H:i:s');
        $query = "UPDATE liquidaciones_citas SET id_liquidacion = @id_liquidacion, estado = 1, id_usuario_modificacion = @userdata, fecha_modificacion = @fecha WHERE id_liquidacion_cita IN (" . $id_liquidaciones_citas . ")";
        $AqConexion_model->no_select($query, $param);
    }

    public function borrarCitaLiquidacion($id_liquidacion_cita)
    {
        $AqConexion_model = new AqConexion_model();
        $registro = [];
        $registro['estado'] = 0;
        $registro['id_liquidacion'] = 0;
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $where['id_liquidacion_cita'] = $id_liquidacion_cita;
        $AqConexion_model->update('liquidaciones_citas', $registro, $where);
        $borrado = $this->db->affected_rows();
        return ($borrado == 1) ? 1 : 0;
    }

    public function archivarLiquidacion($id_liquidacion, $estado)
    {
        $AqConexion_model = new AqConexion_model();
        $registro['borrado'] = 0;
        $registro['estado'] = $estado;
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $where['id_liquidacion'] = $id_liquidacion;
        $AqConexion_model->update('liquidaciones', $registro, $where);
        $borrado = $this->db->affected_rows();
        return ($borrado == 1) ? 1 : 0;
    }

    public function borrarLiquidacion($id_liquidacion)
    {
        $AqConexion_model = new AqConexion_model();
        $registro['borrado'] = 1;
        $registro['id_usuario_borrado'] = $this->session->userdata('id_usuario');
        $registro['fecha_borrado'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $where['id_liquidacion'] = $id_liquidacion;
        $AqConexion_model->update('liquidaciones', $registro, $where);
        $borrado = $this->db->affected_rows();

        $registro = [];
        $registro['estado'] = 0;
        $registro['id_liquidacion'] = 0;
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $where['id_liquidacion'] = $id_liquidacion;
        $AqConexion_model->update('liquidaciones_citas', $registro, $where);
        $borrado2 = $this->db->affected_rows();

        $registro = [];
        $registro['borrado'] = 1;
        $registro['id_usuario_borrado'] = $this->session->userdata('id_usuario');
        $registro['fecha_borrado'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $where['id_liquidacion'] = $id_liquidacion;
        $AqConexion_model->update('liquidaciones_comisiones', $registro, $where);

        return ($borrado == 1) ? 1 : 0;
    }

    function leer_liquidaciones_para_comisiones($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $busqueda = "";

        if (isset($parametros['ids_cita'])) {
            if ($parametros['ids_cita'] != '') {
                $busqueda .= " AND LC.id_liquidacion_cita IN (" . $parametros['ids_cita'] . ") ";
            }
        }

        if (isset($parametros['estado'])) {
            $busqueda .= " AND LC.estado = @estado ";
        }

        if (isset($parametros['id_liquidacion'])) {
            $busqueda .= " AND LC.id_liquidacion = @id_liquidacion ";
        }
        // ... Leemos los registros
        $sentencia_sql = "SELECT
            LC.*
        FROM liquidaciones_citas AS LC
        WHERE LC.borrado = 0  " . $busqueda . " GROUP BY LC.id_liquidacion_cita ORDER BY fecha_cita ASC";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return (is_array($datos)) ? $datos : [];
    }

    public function nueva_linea($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $registro['id_usuario'] = $parametros['id_usuario'];
        $registro['id_presupuesto_item'] = $parametros['id_presupuesto_item'];
        $registro['id_cita'] = $parametros['id_cita'];;
        $registro['fecha_cita'] =  $parametros['fecha_cita'];
        $registro['item'] =  $parametros['item'];
        $registro['id_item'] = $parametros['id_item'];
        $registro['id_familia_item'] =  $parametros['id_familia_item'];
        $registro['id_cliente'] = $parametros['id_cliente'];
        $registro['pvp'] =  $parametros['pvp'];
        $registro['dto'] = $parametros['dto'];
        $registro['dtop'] = $parametros['dtop'];
        $registro['com_financiacion'] = $parametros['com_financiacion'];
        $registro['gastos_lab'] = $parametros['gastos_lab'];
        $registro['total'] = $parametros['total'];
        $registro['concepto'] = $parametros['concepto'];
        $registro['estado'] = 0;
        $registro['id_usuario_creacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_creacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['borrado'] = 0;
        $AqConexion_model->insert('liquidaciones_citas', $registro);
        return $this->db->insert_id();
    }


    public function leer_liquidacion_itempresupuesto($id_presupuesto_item){
        $AqConexion_model = new AqConexion_model();
        $sentencia_sql = "SELECT
            LC.*
        FROM liquidaciones_citas AS LC
        WHERE LC.borrado = 0 AND id_presupuesto_item = @id_presupuesto_item";
        $parametros=[
            'id_presupuesto_item'=>$id_presupuesto_item
        ];
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);
        return $datos;
    }


    public function leer_liquidaciones_presupuesto($id_presupuesto){
        $AqConexion_model = new AqConexion_model();

        $sentencia_sql = "SELECT
            LC.*
        FROM liquidaciones_citas AS LC
        INNER JOIN presupuestos_items ON LC.id_presupuesto_item=presupuestos_items.id_presupuesto_item
        WHERE LC.borrado = 0 AND id_presupuesto = @id_presupuesto";
        $parametros=[
            'id_presupuesto'=>$id_presupuesto
        ];

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);
        return $datos;
    }
    public  function  getDientes($id_liquidacion){
        $diente=0;
        $AqConexion_model = new AqConexion_model();
        $parametros=['id_liquidacion'=>$id_liquidacion];
        $query="SELECT liquidaciones.id_liquidacion, presupuestos_items.dientes FROM liquidaciones, liquidaciones_citas, presupuestos_items WHERE liquidaciones.id_liquidacion=liquidaciones_citas.id_liquidacion AND liquidaciones_citas.id_presupuesto_item=presupuestos_items.id_presupuesto_item AND liquidaciones.id_liquidacion = @id_liquidacion";
        $result = $AqConexion_model->select($query, $parametros);
        foreach ($result as $key => $value) {
            $diente=$value['dientes'];
        }
        if(empty($diente)){
            $diente=0;
        }
        return $diente;
    }
}
