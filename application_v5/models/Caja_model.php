<?php
class Caja_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    // -------------------------------------------------------------------
    // ... CAJA MOVIMIENTOS
    // ALTER TABLE `cajas_cierres` ADD `saldo_cierre_paypal` DECIMAL(10,2) NOT NULL AFTER `saldo_cierre_habitacion`, ADD `saldo_cierre_financiado` DECIMAL(10,2) NOT NULL AFTER `saldo_cierre_paypal`, ADD `descuadre_paypal` DECIMAL(10,2) NOT NULL AFTER `descuadre_habitacion`, ADD `descuadre_financiado` DECIMAL(10,2) NOT NULL AFTER `descuadre_paypal`;
    // -------------------------------------------------------------------
    function leer_caja_movimientos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['id'])) {
            $busqueda .= " AND C.id = @id ";
        }

        if (isset($parametros['id_centro']) && $parametros['id_centro'] > 0) {
            $busqueda .= " AND C.id_centro = @id_centro ";
        }

        if (isset($parametros['fecha_desde'])) {
            if ($parametros['fecha_desde'] != "") {
                $busqueda .= " AND (DATE_FORMAT(C.fecha_creacion,'%Y-%m-%d') >= @fecha_desde)
         AND (DATE_FORMAT(C.fecha_creacion,'%Y-%m-%d') <= @fecha_hasta) ";
            }
        }

        if (isset($parametros['tipo_movimiento']) && $parametros['tipo_movimiento'] == 1) {
                $busqueda .= " AND C.cantidad < 0 ";
        }
        if (isset($parametros['tipo_movimiento']) && $parametros['tipo_movimiento'] == 2) {
                $busqueda .= " AND C.cantidad > 0 ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT C.id,C.id_centro,C.cantidad,C.concepto,
            C.id_usuario_creacion,C.fecha_creacion,C.id_usuario_modificacion,
            C.fecha_modificacion,C.borrado,C.id_usuario_borrado,C.fecha_borrado,
            DATE_FORMAT(C.fecha_creacion,'%H:%i') as hora,
            DATE_FORMAT(C.fecha_creacion,'%d-%m-%Y') as fecha_creacion_ddmmaaaa,
            DATE_FORMAT(C.fecha_creacion,'%Y-%m-%d %H:%i') as fecha_creacion_aaaammdd_hhss,
            CONCAT(usuarios.nombre, ' ', usuarios.apellidos) As empleado,
            centros.nombre_centro,usuarios.email as email
            FROM cajas_movimientos as C
            LEFT JOIN usuarios on usuarios.id_usuario = C.id_usuario_creacion
            LEFT JOIN centros on centros.id_centro = C.id_centro
            WHERE C.borrado = 0 " . $busqueda . " ORDER BY C.fecha_creacion desc ";
        $datos = $AqConexion_model->SELECT($sentencia_sql, $parametros);

        return $datos;
    }

    function nuevo_movimiento_caja($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Datos generales
        $registro['id_centro'] = $this->session->userdata('id_centro_usuario');
        $registro['cantidad'] = $parametros['cantidad'];
        $registro['concepto'] = $parametros['concepto'];
        //
        $registro['fecha_creacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_creacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['borrado'] = 0;

        $AqConexion_model->insert('cajas_movimientos', $registro);

        $sentenciaSQL = "SELECT max(id) as id FROM cajas_movimientos";
        $resultado = $AqConexion_model->SELECT($sentenciaSQL, null);

        return $resultado[0]['id'];
    }

    function actualizar_movimiento_caja($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Datos generales como cliente.
        $registro['id_centro'] = $this->session->userdata('id_centro_usuario');
        $registro['cantidad'] = $parametros['cantidad'];
        $registro['concepto'] = $parametros['concepto'];
        //  
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id');

        $where['id'] = $parametros['id'];

        $AqConexion_model->update('cajas_movimientos', $registro, $where);

        return 1;
    }

    function borrar_movimiento_caja($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        unset($paramborrar);
        $paramborrar['id_primario'] = $parametros['id'];
        $paramborrar['id_usuario_borrado'] = $this->session->userdata('id_usuario');
        $paramborrar['fecha_borrado'] = date("Y-m-d H:i:s");

        $sentenciaSQL = "UPDATE cajas_movimientos SET borrado = 1, id_usuario_borrado = @id_usuario_borrado, fecha_borrado = @fecha_borrado WHERE id = @id_primario";
        $AqConexion_model->no_SELECT($sentenciaSQL, $paramborrar);

        return 1;
    }

    function caja_saldo_actual_efectivo($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['id_centro'])) {
            if ($parametros['id_centro'] > 0) {
                $busqueda .= " AND DC.id_empleado IN (SELECT id_usuario FROM usuarios WHERE id_centro = @id_centro AND borrado = 0)  ";
            }
        }

        // ... Leemos los ingresos por servicios, carnets o productos.
        $sentencia_sql = "SELECT sum(pagado_efectivo) as saldo_actual
            FROM dietario AS DC 
            WHERE DC.borrado = 0 AND (DC.estado = 'Pagado' OR estado = 'Devuelto')
            AND DC.tipo_pago like '%efectivo%' AND DC.fecha_modificacion >=
            (SELECT max(fecha_creacion) FROM cajas_cierres WHERE borrado = 0
            AND id_centro = @id_centro) " . $busqueda;
        $datos = $AqConexion_model->SELECT($sentencia_sql, $parametros);

        if ($datos[0]['saldo_actual'] == "") {
            $datos[0]['saldo_actual'] = 0;
        }

        unset($param);
        if (isset($parametros['id_centro'])) {
            $param['id_centro'] = $parametros['id_centro'];
        } else {
            $param['id_centro'] = $this->session->userdata('id_centro_usuario');
        }
        $saldo_inicial = $this->Caja_model->saldo_inicial($param);

        $saldo_actual = $datos[0]['saldo_actual'] + $saldo_inicial;

        // ... Leemos los movimientos de caja.
        unset($param);
        $busqueda = "";
        if (isset($parametros['id_centro'])) {
            if ($parametros['id_centro'] > 0) {
                $busqueda = " AND C.id_centro = @id_centro ";
                $param['id_centro'] = $parametros['id_centro'];
            }
        } else {
            $param['id_centro'] = $this->session->userdata('id_centro_usuario');
        }
        /*if (isset($parametros['fecha'])) {
        if ($parametros['fecha'] != "") {
            $busqueda.=" AND DATE_FORMAT(C.fecha_creacion,'%Y-%m-%d') = @fecha ";
            $param['fecha']=$parametros['fecha'];
        }      
        }*/
        $sentencia_sql = "SELECT sum(cantidad) as cantidad_total
        FROM cajas_movimientos AS C 
        WHERE C.borrado = 0 AND C.fecha_creacion >=
        (SELECT max(fecha_creacion) FROM cajas_cierres WHERE borrado = 0
        AND id_centro = @id_centro) " . $busqueda;
        $caja = $AqConexion_model->SELECT($sentencia_sql, $param);

        if ($caja[0]['cantidad_total'] == "") {
            $caja[0]['cantidad_total'] = 0;
        }

        $saldo_actual += $caja[0]['cantidad_total'];

        return round($saldo_actual, 2);
    }

    function caja_saldo_actual_tarjeta($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['id_centro'])) {
            if ($parametros['id_centro'] > 0) {
                $busqueda .= " AND DC.id_empleado IN (SELECT id_usuario FROM usuarios WHERE id_centro = @id_centro AND borrado = 0)  ";
            }
        }

        // ... Leemos los ingresos por servicios, carnets o productos.
        $sentencia_sql = "SELECT sum(pagado_tarjeta) as saldo_actual
            FROM dietario AS DC 
            WHERE
            DC.borrado = 0 AND
            DC.id_pedido = 0 AND 
            (DC.estado = 'Pagado' OR estado = 'Devuelto') AND
            DC.tipo_pago like '%tarjeta%' AND DC.fecha_modificacion >=
            (SELECT max(fecha_creacion) FROM cajas_cierres WHERE borrado = 0
            AND id_centro = @id_centro) " . $busqueda;
        $datos = $AqConexion_model->SELECT($sentencia_sql, $parametros);

        $saldo_actual = $datos[0]['saldo_actual'];

        if ($saldo_actual == "") {
            $saldo_actual = 0;
        }

        return round($saldo_actual, 2);
    }

    function caja_saldo_actual_transferencia($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['id_centro'])) {
            if ($parametros['id_centro'] > 0) {
                $busqueda .= " AND DC.id_empleado IN  (SELECT id_usuario FROM usuarios WHERE id_centro = @id_centro AND borrado = 0)  ";
            }
        }

        // ... Leemos los ingresos por servicios, carnets o productos.
        $sentencia_sql = "SELECT sum(pagado_transferencia) as saldo_actual
            FROM dietario AS DC 
            WHERE
            DC.borrado = 0 AND
            DC.id_pedido = 0 AND 
            (DC.estado = 'Pagado' OR estado = 'Devuelto') AND
            DC.tipo_pago like '%transferencia%' AND DC.fecha_modificacion >=
            (SELECT max(fecha_creacion) FROM cajas_cierres WHERE borrado = 0
            AND id_centro = @id_centro) " . $busqueda;
        $datos = $AqConexion_model->SELECT($sentencia_sql, $parametros);

        $saldo_actual = $datos[0]['saldo_actual'];

        if ($saldo_actual == "") {
            $saldo_actual = 0;
        }

        return round($saldo_actual, 2);
    }

    function caja_saldo_actual_tpv2($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['id_centro'])) {
            if ($parametros['id_centro'] > 0) {
                $busqueda .= " AND DC.id_empleado IN 
            (SELECT id_usuario FROM usuarios WHERE id_centro = @id_centro AND borrado = 0)  ";
            }
        }

        // ... Leemos los ingresos por servicios, carnets o productos.
        $sentencia_sql = "SELECT sum(pagado_tpv2) as saldo_actual
            FROM dietario AS DC 
            WHERE
            DC.borrado = 0 AND
            DC.id_pedido = 0 AND 
            (DC.estado = 'Pagado' OR estado = 'Devuelto') AND
            DC.tipo_pago like '%tpv2%' AND DC.fecha_modificacion >=
            (SELECT max(fecha_creacion) FROM cajas_cierres WHERE borrado = 0
            AND id_centro = @id_centro) " . $busqueda;
        $datos = $AqConexion_model->SELECT($sentencia_sql, $parametros);

        $saldo_actual = $datos[0]['saldo_actual'];

        if ($saldo_actual == "") {
            $saldo_actual = 0;
        }

        return round($saldo_actual, 2);
    }

    function caja_saldo_actual_habitacion($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['id_centro'])) {
            if ($parametros['id_centro'] > 0) {
                $busqueda .= " AND DC.id_empleado IN   (SELECT id_usuario FROM usuarios WHERE id_centro = @id_centro AND borrado = 0)  ";
            }
        }

        // ... Leemos los ingresos por servicios, carnets o productos.
        $sentencia_sql = "SELECT sum(pagado_habitacion) as saldo_actual
            FROM dietario AS DC 
            WHERE DC.borrado = 0 AND (DC.estado = 'Pagado' OR estado = 'Devuelto') AND
            DC.tipo_pago like '%habitacion%' AND DC.fecha_modificacion >=
            (SELECT max(fecha_creacion) FROM cajas_cierres WHERE borrado = 0
            AND id_centro = @id_centro) " . $busqueda;
        $datos = $AqConexion_model->SELECT($sentencia_sql, $parametros);

        $saldo_actual = $datos[0]['saldo_actual'];

        if ($saldo_actual == "") {
            $saldo_actual = 0;
        }

        return round($saldo_actual, 2);
    }

    function caja_saldo_actual_paypal($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $busqueda = "";
        if (isset($parametros['id_centro'])) {
            if ($parametros['id_centro'] > 0) {
                $busqueda .= " AND DC.id_empleado IN (SELECT id_usuario FROM usuarios WHERE id_centro = @id_centro AND borrado = 0)  ";
            }
        }
        // ... Leemos los ingresos por servicios, carnets o productos.
        $sentencia_sql = "SELECT sum(pagado_paypal) as saldo_actual
            FROM dietario AS DC 
            WHERE
            DC.borrado = 0 AND
            DC.id_pedido = 0 AND 
            (DC.estado = 'Pagado' OR estado = 'Devuelto') AND
            DC.tipo_pago like '%paypal%' AND DC.fecha_modificacion >=
            (SELECT max(fecha_creacion) FROM cajas_cierres WHERE borrado = 0
            AND id_centro = @id_centro) " . $busqueda;
        $datos = $AqConexion_model->SELECT($sentencia_sql, $parametros);
        $saldo_actual = $datos[0]['saldo_actual'];
        if ($saldo_actual == "") {
            $saldo_actual = 0;
        }
        return round($saldo_actual, 2);
    }

    function caja_saldo_actual_financiado($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $busqueda = "";
        if (isset($parametros['id_centro'])) {
            if ($parametros['id_centro'] > 0) {
                $busqueda .= " AND DC.id_empleado IN (SELECT id_usuario FROM usuarios WHERE id_centro = @id_centro AND borrado = 0)  ";
            }
        }
        // ... Leemos los ingresos por servicios, carnets o productos.
        $sentencia_sql = "SELECT sum(pagado_financiado) as saldo_actual
            FROM dietario AS DC 
            WHERE
            DC.borrado = 0 AND
            DC.id_pedido = 0 AND 
            (DC.estado = 'Pagado' OR estado = 'Devuelto') AND
            DC.tipo_pago like '%financiado%' AND DC.fecha_modificacion >=
            (SELECT max(fecha_creacion) FROM cajas_cierres WHERE borrado = 0
            AND id_centro = @id_centro) " . $busqueda;
        $datos = $AqConexion_model->SELECT($sentencia_sql, $parametros);
        $saldo_actual = $datos[0]['saldo_actual'];
        if ($saldo_actual == "") {
            $saldo_actual = 0;
        }
        return round($saldo_actual, 2);
    }

    function saldo_inicial($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['id_centro'])) {
            if ($parametros['id_centro'] > 0) {
                $busqueda .= " AND centros.id_centro = @id_centro ";
            }
        }

        $sentencia_sql = "SELECT saldo_inicial FROM centros WHERE centros.borrado = 0 " . $busqueda;
        $datos = $AqConexion_model->SELECT($sentencia_sql, $parametros);

        $saldo_inicial = (isset($datos[0]['saldo_inicial'])) ? $datos[0]['saldo_inicial'] : "";

        if ($saldo_inicial == "") {
            $saldo_inicial = 0;
        }

        return $saldo_inicial;
    }

    function guardar_saldo_inicial($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        //
        // ... Guardamos el movimiento realizado en el saldo IN icial
        //
        unset($registro);
        $registro['id_centro'] = $this->session->userdata('id_centro_usuario');
        $registro['saldo_inicial_anterior'] = $this->saldo_inicial($registro);
        $registro['saldo_inicial_nuevo'] = $parametros['saldo_inicial'];
        $registro['motivo'] = $parametros['motivo'];
        //
        $registro['fecha_creacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_creacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['borrado'] = 0;
        $AqConexion_model->insert('cajas_saldos_iniciales', $registro);

        //
        // ... Cambiamos el nuevo saldo IN icial
        //
        unset($registro);
        $registro['saldo_inicial'] = $parametros['saldo_inicial'];
        //    
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');

        $where['id_centro'] = $this->session->userdata('id_centro_usuario');
        $AqConexion_model->update('centros', $registro, $where);

        return 1;
    }

    function cierre($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $registro['id_centro'] = $this->session->userdata('id_centro_usuario');
        $registro['saldo_inicial'] = $parametros['saldo_inicial'];
        $registro['saldo_cierre_efectivo'] = $parametros['saldo_cierre_efectivo'];
        $registro['saldo_cierre_tarjeta'] = $parametros['saldo_cierre_tarjeta'];
        $registro['saldo_cierre_transferencia'] = $parametros['saldo_cierre_transferencia']; 
        $registro['saldo_cierre_tpv2'] = $parametros['saldo_cierre_tpv2']; 
        $registro['saldo_cierre_habitacion'] = $parametros['saldo_cierre_habitacion'];
        $registro['saldo_cierre_paypal'] = $parametros['saldo_cierre_paypal']; 
        $registro['saldo_cierre_financiado'] = $parametros['saldo_cierre_financiado'];

        $registro['descuadre_efectivo'] = $parametros['descuadre_efectivo'];
        $registro['descuadre_tarjeta'] = $parametros['descuadre_tarjeta'];
        $registro['descuadre_transferencia'] = $parametros['descuadre_transferencia'];
        $registro['descuadre_tpv2'] = $parametros['descuadre_tpv2']; 
        $registro['descuadre_habitacion'] = $parametros['descuadre_habitacion'];
        $registro['descuadre_paypal'] = $parametros['descuadre_paypal']; 
        $registro['descuadre_financiado'] = $parametros['descuadre_financiado'];
        //
        $registro['fecha_creacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_creacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['borrado'] = 0;

        $AqConexion_model->insert('cajas_cierres', $registro);

        $sentenciaSQL = "SELECT max(id) as id FROM cajas_cierres";
        $resultado = $AqConexion_model->SELECT($sentenciaSQL, null);

        // ... Actualizamos el saldo de la caja.
        unset($param);
        $param['saldo_inicial'] = $parametros['saldo_cierre_efectivo'];
        $param['motivo'] = "Cierre de caja";
        $ok = $this->Caja_model->guardar_saldo_inicial($param);

        // ... Apuntamos el cierre en el dietario.
        unset($param);
        $param['id_cliente'] = 0;
        $param['id_cita'] = 0;
        $param['fecha_hora_concepto'] = date("Y-m-d H:i:s");
        $param['id_empleado'] = 0;
        $param['id_servicio'] = 0;
        $param['id_producto'] = 0;
        $param['id_carnet'] = 0;
        $param['recarga'] = 0;
        $param['importe_euros'] = 0;
        $param['templos'] = 0;
        $param['estado'] = "Cierre Caja";
        $id_dietario = $this->Dietario_model->nuevo_dietario_concepto($param);

        return $resultado[0]['id'];
    }

    function leer_cierres($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['id'])) {
            $busqueda .= " AND C.id = @id ";
        }

        if (isset($parametros['id_centro'])) {
            $busqueda .= " AND C.id_centro = @id_centro ";
        }

        if (isset($parametros['fecha'])) {
            if ($parametros['fecha'] != "") {
                $busqueda .= " AND (DATE_FORMAT(C.fecha_creacion,'%Y-%m-%d') = @fecha) ";
            }
        }

        if (isset($parametros['fecha_hasta'])) {
            if ($parametros['fecha_hasta'] != "") {
                $busqueda .= " AND C.fecha_creacion >= @fecha_desde AND
        C.fecha_creacion <= @fecha_hasta ";
            }
        }

        if (isset($parametros['descuadres_caja'])) {
            $busqueda .= " AND ( C.descuadre_efectivo <> 0 OR C.descuadre_tarjeta <> 0 OR C.descuadre_tpv2 <> 0 OR C.descuadre_paypal <> 0 OR C.descuadre_transferencia <> 0 OR C.descuadre_financiado <> 0 OR C.descuadre_habitacion <> 0 ) ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT C.id,C.id_centro,C.saldo_inicial,C.saldo_cierre_efectivo,
            C.saldo_cierre_tarjeta,C.saldo_cierre_tpv2,C.saldo_cierre_paypal,C.saldo_cierre_transferencia,C.saldo_cierre_financiado,C.saldo_cierre_habitacion,C.descuadre_efectivo,descuadre_tarjeta,descuadre_tpv2,descuadre_paypal,C.descuadre_transferencia,C.descuadre_financiado,C.descuadre_habitacion,
            C.id_usuario_creacion,C.fecha_creacion,C.id_usuario_modificacion,
            C.fecha_modificacion,C.borrado,C.id_usuario_borrado,C.fecha_borrado,
            DATE_FORMAT(C.fecha_creacion,'%H:%i') as hora,
            DATE_FORMAT(C.fecha_creacion,'%d-%m-%Y') as fecha_creacion_ddmmaaaa,
            DATE_FORMAT(C.fecha_creacion,'%Y-%m-%d') as fecha_creacion_aaaammdd,
            CONCAT(usuarios.nombre, ' ', usuarios.apellidos) As empleado,
            centros.nombre_centro,usuarios.email as email
            FROM cajas_cierres as C
            LEFT JOIN usuarios on usuarios.id_usuario = C.id_usuario_creacion
            LEFT JOIN centros on centros.id_centro = C.id_centro
            WHERE C.borrado = 0 " . $busqueda . " ORDER BY C.fecha_creacion DESC ";
        $datos = $AqConexion_model->SELECT($sentencia_sql, $parametros);

        return $datos;
    }

    function leer_movimientos_saldos_iniciales($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['id_centro'])) {
            $busqueda .= " AND C.id_centro = @id_centro ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT C.id,C.id_centro,C.saldo_inicial_anterior,
            C.saldo_inicial_nuevo,C.motivo,C.id_usuario_creacion,C.fecha_creacion,
            C.id_usuario_modificacion,C.fecha_modificacion,C.borrado,
            C.id_usuario_borrado,C.fecha_borrado,
            DATE_FORMAT(C.fecha_creacion,'%H:%i') as hora,
            DATE_FORMAT(C.fecha_creacion,'%d-%m-%Y') as fecha_creacion_ddmmaaaa,
            DATE_FORMAT(C.fecha_creacion,'%Y-%m-%d') as fecha_creacion_aaaammdd,
            CONCAT(usuarios.nombre, ' ', usuarios.apellidos) As empleado,
            centros.nombre_centro,usuarios.email as email
            FROM cajas_saldos_iniciales as C
            LEFT JOIN usuarios on usuarios.id_usuario = C.id_usuario_creacion
            LEFT JOIN centros on centros.id_centro = C.id_centro
            WHERE C.borrado = 0 " . $busqueda . " ORDER BY C.fecha_creacion DESC ";
        $datos = $AqConexion_model->SELECT($sentencia_sql, $parametros);

        return $datos;
    }

    function leer_cierre_efectivo_guardados($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $sentencia_sql = "SELECT * FROM cajas_cierres_efectivo  WHERE fecha_creacion BETWEEN '" . $parametros['fecha_desde'] . "' AND '" . $parametros['fecha_hasta'] . "' AND id_usuario_creacion=" . $parametros['id_usuario'];
        $datos = $AqConexion_model->SELECT($sentencia_sql, $parametros);

        return $datos;
    }

    function leer_cierre_tarjeta_guardados($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $sentencia_sql = "SELECT * FROM cajas_cierres WHERE fecha_creacion BETWEEN '" . $parametros['fecha_desde'] . "' AND '" . $parametros['fecha_hasta'] . "' AND id_usuario_creacion=" . $parametros['id_usuario'];
        $datos = $AqConexion_model->SELECT($sentencia_sql, $parametros);

        return $datos;
    }

    function caja_guardar_efectivo($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Datos generales
        $registro['b50_euros'] = $parametros['50_euros'];
        $registro['b20_euros'] = $parametros['20_euros'];
        $registro['b10_euros'] = $parametros['10_euros'];
        $registro['b5_euros'] = $parametros['5_euros'];
        $registro['m2_euros'] = $parametros['2_euros'];
        $registro['m1_euros'] = $parametros['1_euros'];
        $registro['m50_cents'] = $parametros['50_cents'];
        $registro['m20_cents'] = $parametros['20_cents'];
        $registro['m10_cents'] = $parametros['10_cents'];
        $registro['m5_cents'] = $parametros['5_cents'];
        $registro['m2_cents'] = $parametros['2_cents'];
        $registro['m1_cents'] = $parametros['1_cents'];
        $registro['total_efectivo'] = $parametros['total_efectivo'];
        $registro['oportunidad_cuadre'] = $parametros['oportunidad_cuadre'];
        $registro['id_centro'] = $parametros['id_centro'];
        //
        $registro['fecha_creacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_creacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['borrado'] = 0;

        $AqConexion_model->insert('cajas_cierres_efectivo', $registro);

        return 1;
    }
}
