<?php
class Estadisticas_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function citas($param)
    {
        if(isset($param['id_centro'])){
            $this->db->select('id_usuario');
            $this->db->from('usuarios');
            $this->db->where('id_centro',$param['id_centro']);
            $usuarios = $this->db->get()->result();
            $usuarios_centro = '';
            $usuarios_array = [];
            foreach ($usuarios as $key => $u) {
                $usuarios_centro.= $u->id_usuario.',';
                array_push($usuarios_array, $u->id_usuario);
            }
            $usuarios_centro = substr($usuarios_centro, 0, -1);
        }
        $fecha_param = date('Y-m', strtotime($param['fecha']));
        $this->db->select('C.id_cita, C.id_servicio, C.id_usuario_empleado, C.id_cliente, C.estado, C.fecha_hora_inicio, servicios.nombre_servicio, servicios_familias.nombre_familia, servicios.pvp, presupuestos_items.coste');
        $this->db->from('citas as C');
        $this->db->join('clientes', 'clientes.id_cliente = C.id_cliente', 'left');
        $this->db->join('servicios', 'servicios.id_servicio = C.id_servicio', 'left');
        $this->db->join('servicios_familias', 'servicios.id_familia_servicio = servicios_familias.id_familia_servicio', 'left');
        $this->db->join('presupuestos_items', 'presupuestos_items.id_cita = C.id_cita', 'left');
        $this->db->where('C.borrado', 0);
        if(isset($param['id_centro'])){
            $this->db->where_in("C.id_usuario_empleado",$usuarios_array, false);
        }
        $this->db->where("DATE_FORMAT(C.fecha_hora_inicio, '%Y-%m') = '$fecha_param'", null, false);
        $this->db->group_start();
        $this->db->where('C.estado', 'Programada');
        $this->db->or_where('C.estado', 'Finalizado');
        $this->db->group_end();
        $this->db->where('C.duracion >', 0);
        $this->db->group_by('C.id_cita');
        $this->db->order_by('C.id_cita', 'ASC');
        $result = $this->db->get()->result();
        return $result;
    }

    // -------------------------------------------------------------------
    // ... ESTADISTICAS
    // -------------------------------------------------------------------
    function usuarios($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda_cita = "";
        $busqueda_dietario = "";

        $centro = "";
        $centro_citas = "";
        $centro_usuario = " AND U.id_centro > 1 ";

        if (isset($parametros['id_centro'])) {
            $centro = " AND dietario.id_centro = @id_centro ";
            $centro_citas = " AND citas.id_usuario_empleado
      in (select id_usuario from usuarios where id_centro = @id_centro) ";
            $centro_usuario = " AND U.id_centro = @id_centro ";
        }

        if (isset($parametros['fecha_desde']) && isset($parametros['fecha_hasta'])) {
            $busqueda_cita = " AND citas.fecha_hora_inicio >= @fecha_desde AND citas.fecha_hora_inicio <= @fecha_hasta ";
            $busqueda_dietario = " AND dietario.fecha_hora_concepto >= @fecha_desde AND dietario.fecha_hora_concepto <= @fecha_hasta ";
        }

        if (isset($parametros['mes'])) {
            $busqueda_cita = " AND MONTH(citas.fecha_hora_inicio) = MONTH('".$parametros['mes']."') AND YEAR(citas.fecha_hora_inicio) = YEAR('".$parametros['mes']."')";
            $busqueda_dietario = " AND MONTH(dietario.fecha_hora_concepto) = MONTH('".$parametros['mes']."') AND YEAR(dietario.fecha_hora_concepto) = YEAR('".$parametros['mes']."')";

            if(isset($parametros['jornada'])) {
                if($parametros['jornada'] == 1){
                    $busqueda_cita .=" AND TIME(citas.fecha_hora_inicio) < '16:00:00' ";
                    $busqueda_dietario .= " AND TIME(dietario.fecha_hora_concepto) < '16:00:00' ";
                }
                if($parametros['jornada'] == 2){
                    $busqueda_cita .= " AND TIME(citas.fecha_hora_inicio) >= '16:00:00' ";
                    $busqueda_dietario .= " AND TIME(dietario.fecha_hora_concepto) >= '16:00:00' ";
                }
            }
        }

        // ... Leemos los registros
        $sentencia_sql = "select U.id_usuario,U.nombre,U.apellidos,centros.id_centro,
    centros.nombre_centro,perfiles.nombre_perfil,

    (select round(sum(duracion)/60,1) from citas where id_usuario_empleado =
    U.id_usuario and citas.borrado = 0 and citas.estado = 'Finalizado'
    $centro_citas $busqueda_cita)
    as horas_trabajadas,

    (SELECT IFNULL(sum(TIMESTAMPDIFF(minute, fecha_inicio, fecha_fin) / 60),0)
    FROM usuarios_horarios_desglose where id_usuario = U.id_usuario
    and fecha_inicio >= @fecha_desde
    and fecha_fin <= @fecha_hasta)
    AS tiempo_jornada,

    ((select sum(templos) from dietario where dietario.borrado = 0
    and dietario.id_empleado = U.id_usuario and dietario.id_servicio > 0
    and (dietario.estado = 'Pagado')
    $centro $busqueda_dietario) -
    (select IFNULL(sum(templos),0) from dietario where dietario.borrado = 0
    and dietario.id_empleado = U.id_usuario and dietario.id_servicio > 0
    and (dietario.estado = 'Devuelto')
    $centro $busqueda_dietario))
    as templos,

    (select round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia+pagado_tpv2+pagado_financiado),2)
    from dietario where dietario.borrado = 0 and dietario.id_empleado = U.id_usuario
    and dietario.id_producto > 0
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    $centro $busqueda_dietario)
    as ventas_productos,

    (select round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia+pagado_tpv2+pagado_financiado),2)
    from dietario where dietario.borrado = 0 and dietario.id_empleado = U.id_usuario
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    $centro $busqueda_dietario)
    as ventas,

    (select round(sum(pagado_efectivo),2)
    from dietario where dietario.borrado = 0 and dietario.id_empleado = U.id_usuario
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    $centro $busqueda_dietario)
    as ventas_efectivo,

    (select round(sum(pagado_tarjeta),2)
    from dietario where dietario.borrado = 0 and dietario.id_empleado = U.id_usuario
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    $centro $busqueda_dietario)
    as ventas_tarjeta,
    
    (select round(sum(pagado_transferencia),2)
    from dietario where dietario.borrado = 0 and dietario.id_empleado = U.id_usuario
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    $centro $busqueda_dietario)
    as ventas_transferencia,
    
    (select round(sum(pagado_tpv2),2)
    from dietario where dietario.borrado = 0 and dietario.id_empleado = U.id_usuario
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    $centro $busqueda_dietario)
    as ventas_tpv2,

    (select round(sum(pagado_financiado),2)
    from dietario where dietario.borrado = 0 and dietario.id_empleado = U.id_usuario
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    $centro $busqueda_dietario)
    as ventas_financiado,


    (select round(sum(pagado_habitacion),2)
    from dietario where dietario.borrado = 0 and dietario.id_empleado = U.id_usuario
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    $centro $busqueda_dietario)
    as ventas_habitacion,

    (select round(sum(servicios.precio_proveedor),2)
    from dietario
    LEFT JOIN servicios on servicios.id_servicio = dietario.id_servicio
    where dietario.borrado = 0 and dietario.id_empleado = U.id_usuario
    AND dietario.id_servicio IN
    (select id_servicio from servicios where id_familia_servicio = 12)
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    $centro $busqueda_dietario)
    as ventas_proveedores

    from usuarios as U
    left join (usuarios_perfiles left join perfiles on perfiles.id_perfil = usuarios_perfiles.id_perfil) on usuarios_perfiles.id_usuario = U.id_usuario
    left join centros on centros.id_centro = U.id_centro
    where 1=1 $centro_usuario and (usuarios_perfiles.id_perfil > 0)
    ORDER BY U.nombre,U.apellidos ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function horas_trabajadas($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los datos.
        $sentencia_sql = "select round(sum(duracion)/60,1) as horas,
    DATE_FORMAT(citas.fecha_hora_inicio,'%Y-%m') as fecha,
    DATE_FORMAT(citas.fecha_hora_inicio,'%M') as mes,
    DATE_FORMAT(citas.fecha_hora_inicio,'%Y') as anno
    FROM citas
    WHERE id_usuario_empleado = @id_usuario_empleado
    and citas.borrado = 0 and citas.estado = 'Finalizado'
    AND citas.id_usuario_empleado in
    (select id_usuario from usuarios where id_centro = @id_centro and borrado = 0
    and id_usuario_empleado = @id_usuario_empleado)
    group by DATE_FORMAT(citas.fecha_hora_inicio,'%Y-%m') ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function templos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los datos.
        $sentencia_sql = "select sum(templos) as templos,
    DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m') as fecha,
    DATE_FORMAT(dietario.fecha_hora_concepto,'%M') as mes,
    DATE_FORMAT(dietario.fecha_hora_concepto,'%Y') as anno
    from dietario where dietario.borrado = 0
    and dietario.id_empleado = @id_usuario_empleado
    and dietario.id_servicio > 0
    and (dietario.estado = 'Pagado')
    and dietario.id_centro = @id_centro
    group by DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m')";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function templos_devueltos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los datos.
        $sentencia_sql = "select IFNULL(sum(templos),0) as templos,
    DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m') as fecha,
    DATE_FORMAT(dietario.fecha_hora_concepto,'%M') as mes,
    DATE_FORMAT(dietario.fecha_hora_concepto,'%Y') as anno
    from dietario where dietario.borrado = 0
    and dietario.id_empleado = @id_usuario_empleado
    and dietario.id_servicio > 0
    and (dietario.estado = 'Devuelto')
    and dietario.id_centro = @id_centro
    group by DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m')";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function productos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda_dietario = "";

        // ... Leemos los datos.
        //$sentencia_sql="select round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion),2)
        //as venta_productos,
        //DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m') as fecha,
        //DATE_FORMAT(dietario.fecha_hora_concepto,'%M') as mes,
        //DATE_FORMAT(dietario.fecha_hora_concepto,'%Y') as anno
        //from dietario where dietario.borrado = 0
        //and dietario.id_empleado = @id_usuario_empleado
        //and dietario.id_producto > 0
        //and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
        //and dietario.id_centro = @id_centro
        //group by DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m')";

        if (isset($parametros['fecha_desde']) && isset($parametros['fecha_hasta'])) {
            $busqueda_dietario = " AND dietario.fecha_hora_concepto >= @fecha_desde
      AND dietario.fecha_hora_concepto <= @fecha_hasta ";
        }

        $sentencia_sql = "Select round((pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2)
    as venta_productos,
    DATE_FORMAT(dietario.fecha_hora_concepto,'%W, %d-%b-%Y - %H:%i') as fecha_hora_ddmmaaaa,
    DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m-%d %H:%i') as fecha_hora_aaaammdd,
    productos.nombre_producto,productos_familias.nombre_familia
    from dietario
    left join (productos left join productos_familias on
    productos_familias.id_familia_producto = productos.id_familia_producto)
    on productos.id_producto = dietario.id_producto
    where dietario.borrado = 0 " . $busqueda_dietario . "
    and dietario.id_empleado = @id_usuario_empleado
    and dietario.id_producto > 0
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    and dietario.id_centro = @id_centro
    order by fecha_hora_concepto ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function ventas($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los datos.
        $sentencia_sql = "select round(sum(pagado_efectivo),2) as ventas_efectivo,
    round(sum(pagado_tarjeta),2) as ventas_tarjeta,
    round(sum(pagado_transferencia),2) as ventas_transferencia,
    round(sum(pagado_habitacion),2) as ventas_habitacion,
    round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2) as total_ventas,
    DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m') as fecha,
    DATE_FORMAT(dietario.fecha_hora_concepto,'%M') as mes,
    DATE_FORMAT(dietario.fecha_hora_concepto,'%Y') as anno
    from dietario where dietario.borrado = 0
    and dietario.id_empleado = @id_usuario_empleado
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    and dietario.id_centro = @id_centro
    group by DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m')";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function ventas_proveedores($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los datos.
        $sentencia_sql = "select round(sum(servicios.precio_proveedor),2) as ventas_proveedores,
    DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m') as fecha,
    DATE_FORMAT(dietario.fecha_hora_concepto,'%M') as mes,
    DATE_FORMAT(dietario.fecha_hora_concepto,'%Y') as anno
    from dietario
    LEFT JOIN servicios on servicios.id_servicio = dietario.id_servicio
    where dietario.borrado = 0
    and dietario.id_empleado = @id_usuario_empleado
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    and dietario.id_centro = @id_centro
    AND dietario.id_servicio IN
    (select id_servicio from servicios where id_familia_servicio = 12 and borrado = 0)
    group by DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m')";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function productos_resumen_dietario($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los datos.
        $sentencia_sql = "select IFNULL(round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2),0) as total,
    (COUNT(id_dietario) - (select COUNT(id_dietario)*2
      from dietario where dietario.borrado = 0
      and dietario.id_producto > 0
      and (dietario.estado = 'Devuelto')
      and dietario.id_centro = @id_centro
      and DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m-%d') = @fecha)
    )
    as numero_productos
    from dietario where dietario.borrado = 0
    and dietario.id_producto > 0
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    and dietario.id_centro = @id_centro
    and DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m-%d') = @fecha ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function servicios_resumen_dietario($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los datos.
        $sentencia_sql = "select IFNULL(round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2),0) as total,
    COUNT(id_dietario) as numero_servicios
    from dietario where dietario.borrado = 0
    and dietario.id_servicio > 0
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    and dietario.id_centro = @id_centro
    and DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m-%d') = @fecha ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function descuentos_resumen_dietario($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los datos.
        $sentencia_sql = "SELECT
    IFNULL(round(sum(dietario.importe_euros)-sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2),0) as total,
    COUNT(id_dietario) as numero_descuentos
    from dietario where dietario.borrado = 0
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    and (dietario.descuento_euros > 0 or dietario.descuento_porcentaje > 0)
    and dietario.id_centro = @id_centro
    and DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m-%d') = @fecha ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function devoluciones_resumen_dietario($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los datos.
        $sentencia_sql = "select IFNULL(round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2),0) as total,
    COUNT(id_dietario) as numero_devoluciones
    from dietario where dietario.borrado = 0
    and dietario.id_centro = @id_centro
    and (dietario.estado = 'Devuelto')
    and dietario.id_centro = @id_centro
    and DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m-%d') = @fecha ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function carnets_resumen_dietario($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los datos.
        $sentencia_sql = "select IFNULL(round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2),0) as total,
    COUNT(id_dietario) as numero_carnets
    from dietario where dietario.borrado = 0
    and dietario.id_carnet > 0
    and dietario.recarga = 0
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    and dietario.id_centro = @id_centro
    and DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m-%d') = @fecha ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function cajasregalo_resumen_dietario($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los datos.
        $sentencia_sql = "select IFNULL(round(sum(servicios.precio_proveedor),2),0) as total,
    COUNT(id_dietario) as numero_cajas_regalo
    from dietario
    left join servicios on servicios.id_servicio = dietario.id_servicio
    where dietario.borrado = 0
    AND dietario.id_servicio IN (select id_servicio from servicios where id_familia_servicio = 12 and borrado = 0)
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    and dietario.id_centro = @id_centro
    and DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m-%d') = @fecha ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function loglogins($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";

        if (isset($parametros['id_usuario'])) {
            if ($parametros['id_usuario'] != 0) {
                $buscar = " AND usuarios_accesos.id_usuario = @id_usuario ";
            }
        }

        // ... Leemos los datos.
        $sentencia_sql = "SELECT nombre,apellidos,nombre_centro,fecha_inicio,fecha_fin,
    DATE_FORMAT(fecha_inicio,'%d-%m-%Y') as fecha_inicio_ddmmaaaa,
    DATE_FORMAT(fecha_inicio,'%H:%i') as fecha_inicio_hora,
    DATE_FORMAT(fecha_fin,'%d-%m-%Y') as fecha_fin_ddmmaaaa,
    DATE_FORMAT(fecha_fin,'%H:%i') as fecha_fin_hora,
    DATE_FORMAT(fecha_inicio,'%W, %d-%b-%Y - %H:%i') as fecha_inicio_ddmmaaaa_abrev,
    DATE_FORMAT(fecha_fin,'%W, %d-%b-%Y - %H:%i') as fecha_fin_ddmmaaaa_abrev
    FROM usuarios_accesos
    left join (usuarios left join centros on centros.id_centro = usuarios.id_centro)
    on usuarios.id_usuario = usuarios_accesos.id_usuario
    WHERE usuarios_accesos.id_usuario in (select id_usuario from usuarios_perfiles where
    usuarios_perfiles.id_perfil = 1 or usuarios_perfiles.id_perfil = 2
    or usuarios_perfiles.id_perfil = 3)
    and fecha_inicio >= @fecha_desde
    and fecha_inicio <= @fecha_hasta
    $buscar ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function carnets_tipos_centros($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los datos.
        $sentencia_sql = "select carnets_templos_tipos.id_tipo,carnets_templos_tipos.descripcion,dietario.id_centro,
    COUNT(id_dietario) as numero_carnets,
    IFNULL(round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2),0) as total
    from dietario left join (carnets_templos left join carnets_templos_tipos
    on carnets_templos_tipos.id_tipo = carnets_templos.id_tipo)
    on carnets_templos.id_carnet = dietario.id_carnet
    where dietario.borrado = 0
    and dietario.id_carnet > 0
    and dietario.recarga = 0
    and dietario.estado = 'Pagado'
    and carnets_templos_tipos.borrado = 0
    and dietario.fecha_hora_concepto >= @fecha_desde
    and dietario.fecha_hora_concepto <= @fecha_hasta
    group by carnets_templos.id_tipo,dietario.id_centro
    order by dietario.id_centro,COUNT(id_dietario) desc ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function carnets_tipos_total_centros($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los datos.
        $sentencia_sql = "select dietario.id_centro,COUNT(id_dietario) as numero_carnets,
    IFNULL(round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2),0) as total
    from dietario left join (carnets_templos left join carnets_templos_tipos
    on carnets_templos_tipos.id_tipo = carnets_templos.id_tipo)
    on carnets_templos.id_carnet = dietario.id_carnet
    where dietario.borrado = 0
    and dietario.id_carnet > 0
    and dietario.recarga = 0
    and dietario.estado = 'Pagado'
    and carnets_templos_tipos.borrado = 0
    and dietario.fecha_hora_concepto >= @fecha_desde
    and dietario.fecha_hora_concepto <= @fecha_hasta
    group by dietario.id_centro
    order by dietario.id_centro,COUNT(id_dietario) desc ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function carnets_tipos_todos_centros($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los datos.
        $sentencia_sql = "select carnets_templos_tipos.id_tipo,
    carnets_templos_tipos.descripcion, COUNT(id_dietario) as numero_carnets,
    IFNULL(round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2),0) as total
    from dietario left join (carnets_templos left join
    carnets_templos_tipos on carnets_templos_tipos.id_tipo = carnets_templos.id_tipo)
    on carnets_templos.id_carnet = dietario.id_carnet
    where dietario.borrado = 0
    and dietario.id_carnet > 0
    and dietario.recarga = 0
    and dietario.estado = 'Pagado'
    and carnets_templos_tipos.borrado = 0
    and dietario.fecha_hora_concepto >= @fecha_desde
    and dietario.fecha_hora_concepto <= @fecha_hasta
    group by carnets_templos.id_tipo
    order by dietario.id_centro,COUNT(id_dietario) desc ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function carnets_tipos_total_todos_centros($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los datos.
        $sentencia_sql = "select COUNT(id_dietario) as numero_carnets,
    IFNULL(round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion)+pagado_transferencia,2),0) as total
    from dietario left join (carnets_templos left join carnets_templos_tipos
    on carnets_templos_tipos.id_tipo = carnets_templos.id_tipo)
    on carnets_templos.id_carnet = dietario.id_carnet
    where dietario.borrado = 0
    and dietario.id_carnet > 0
    and dietario.recarga = 0
    and dietario.estado = 'Pagado'
    and carnets_templos_tipos.borrado = 0
    and dietario.fecha_hora_concepto >= @fecha_desde
    and dietario.fecha_hora_concepto <= @fecha_hasta
    order by dietario.id_centro,COUNT(id_dietario) desc ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function carnets_tipos_listado($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT C.id_tipo,C.descripcion,C.templos
    FROM carnets_templos_tipos AS C
    WHERE C.borrado = 0 ORDER BY C.id_tipo ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function venta_carnets_centros($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "select centros.nombre_centro,
    COUNT(id_dietario) as numero_carnets,
    IFNULL(round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2),0) as total
    from dietario
    left join centros on centros.id_centro = dietario.id_centro
    where dietario.borrado = 0
    and dietario.id_carnet > 0
    and dietario.recarga = 0
    and dietario.estado = 'Pagado'
    and dietario.fecha_hora_concepto >= @fecha_desde
    and dietario.fecha_hora_concepto <= @fecha_hasta
    group by dietario.id_centro
    order by centros.nombre_centro ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }


    function tipo_carnets_vendidos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "select carnets_templos_tipos.descripcion,
    COUNT(id_dietario) as numero_carnets,
    IFNULL(round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2),0) as total
    from dietario left join (carnets_templos left join carnets_templos_tipos
    on carnets_templos_tipos.id_tipo = carnets_templos.id_tipo)
    on carnets_templos.id_carnet = dietario.id_carnet
    where dietario.borrado = 0
    and dietario.id_carnet > 0
    and dietario.recarga = 0
    and dietario.estado = 'Pagado'
    and carnets_templos_tipos.borrado = 0
    and dietario.fecha_hora_concepto >= @fecha_desde
    and dietario.fecha_hora_concepto <= @fecha_hasta
    group by carnets_templos.id_tipo
    order by carnets_templos.id_tipo ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function carnets_tipos_centros_sin_usar($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los datos.
        $sentencia_sql = "select carnets_templos_tipos.id_tipo,
    carnets_templos_tipos.descripcion,carnets_templos.id_centro,
    SUM(carnets_templos.templos_disponibles) as numero_templos,
    SUM(CE.templos_disponibles) as numero_templos_especiales,
    IFNULL(sum(round((carnets_templos.precio/carnets_templos.templos)*carnets_templos.templos_disponibles,2)),0) as total,
    IFNULL(sum(round((carnets_templos.precio/CE.templos)*CE.templos_disponibles,2)),0) as total_especial
    FROM carnets_templos
    LEFT JOIN carnets_templos_tipos on carnets_templos_tipos.id_tipo = carnets_templos.id_tipo
    LEFT JOIN carnets_especiales_estado_templos as CE on CE.id_carnet = carnets_templos.id_carnet
    WHERE
    carnets_templos.borrado = 0 and
    carnets_templos.id_centro > 1 and
    (carnets_templos.templos_disponibles > 0 or CE.templos_disponibles > 0) and
    carnets_templos_tipos.borrado = 0 and
    carnets_templos.id_carnet NOT IN
    (
      select distinct id_carnet
      from dietario
      where dietario.borrado = 0
      and dietario.id_carnet > 0
      and dietario.estado = 'Pagado'
      and dietario.fecha_hora_concepto >= DATE_SUB(CURDATE(),INTERVAL 12 month)
      and dietario.fecha_hora_concepto <= CURDATE()
    )
    GROUP BY carnets_templos.id_tipo,carnets_templos.id_centro
    ORDER BY carnets_templos.id_centro,COUNT(carnets_templos.id_carnet) desc";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function carnets_tipos_total_centros_sin_usar($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los datos.
        $sentencia_sql = "select carnets_templos_tipos.id_tipo,
    carnets_templos_tipos.descripcion,carnets_templos.id_centro,
    SUM(carnets_templos.templos_disponibles) as numero_templos,
    SUM(CE.templos_disponibles) as numero_templos_especiales,
    IFNULL(sum(round((carnets_templos.precio/carnets_templos.templos)*carnets_templos.templos_disponibles,2)),0) as total,
    IFNULL(sum(round((carnets_templos.precio/CE.templos)*CE.templos_disponibles,2)),0) as total_especial
    FROM carnets_templos
    LEFT JOIN carnets_templos_tipos on carnets_templos_tipos.id_tipo = carnets_templos.id_tipo
    LEFT JOIN carnets_especiales_estado_templos as CE on CE.id_carnet = carnets_templos.id_carnet
    WHERE
    carnets_templos.borrado = 0 and
    carnets_templos.id_centro > 1 and
    (carnets_templos.templos_disponibles > 0 or CE.templos_disponibles > 0) and
    carnets_templos_tipos.borrado = 0 and
    carnets_templos.id_carnet NOT IN
    (
      select distinct id_carnet
      from dietario
      where dietario.borrado = 0
      and dietario.id_carnet > 0
      and dietario.estado = 'Pagado'
      and dietario.fecha_hora_concepto >= DATE_SUB(CURDATE(),INTERVAL 12 month)
      and dietario.fecha_hora_concepto <= CURDATE()
    )
    GROUP BY carnets_templos.id_centro
    ORDER BY carnets_templos.id_centro,COUNT(carnets_templos.id_carnet) desc";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function carnets_tipos_todos_centros_sin_usar($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los datos.
        $sentencia_sql = "select carnets_templos_tipos.id_tipo,
    carnets_templos_tipos.descripcion,carnets_templos.id_centro,
    SUM(carnets_templos.templos_disponibles) as numero_templos,
    SUM(CE.templos_disponibles) as numero_templos_especiales,
    IFNULL(sum(round((carnets_templos.precio/carnets_templos.templos)*carnets_templos.templos_disponibles,2)),0) as total,
    IFNULL(sum(round((carnets_templos.precio/CE.templos)*CE.templos_disponibles,2)),0) as total_especial
    FROM carnets_templos
    LEFT JOIN carnets_templos_tipos on carnets_templos_tipos.id_tipo = carnets_templos.id_tipo
    LEFT JOIN carnets_especiales_estado_templos as CE on CE.id_carnet = carnets_templos.id_carnet
    WHERE
    carnets_templos.borrado = 0 and
    carnets_templos.id_centro > 1 and
    (carnets_templos.templos_disponibles > 0 or CE.templos_disponibles > 0) and
    carnets_templos_tipos.borrado = 0 and
    carnets_templos.id_carnet NOT IN
    (
      select distinct id_carnet
      from dietario
      where dietario.borrado = 0
      and dietario.id_carnet > 0
      and dietario.estado = 'Pagado'
      and dietario.fecha_hora_concepto >= DATE_SUB(CURDATE(),INTERVAL 12 month)
      and dietario.fecha_hora_concepto <= CURDATE()
    )
    GROUP BY carnets_templos.id_tipo
    ORDER BY carnets_templos.id_centro,COUNT(carnets_templos.id_carnet) desc";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function carnets_tipos_total_todos_centros_sin_usar($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los datos.
        $sentencia_sql = "select carnets_templos_tipos.id_tipo,
    carnets_templos_tipos.descripcion,carnets_templos.id_centro,
    SUM(carnets_templos.templos_disponibles) as numero_templos,
    SUM(CE.templos_disponibles) as numero_templos_especiales,
    IFNULL(sum(round((carnets_templos.precio/carnets_templos.templos)*carnets_templos.templos_disponibles,2)),0) as total,
    IFNULL(sum(round((carnets_templos.precio/CE.templos)*CE.templos_disponibles,2)),0) as total_especial
    FROM carnets_templos
    LEFT JOIN carnets_templos_tipos on carnets_templos_tipos.id_tipo = carnets_templos.id_tipo
    LEFT JOIN carnets_especiales_estado_templos as CE on CE.id_carnet = carnets_templos.id_carnet
    WHERE
    carnets_templos.borrado = 0 and
    carnets_templos.id_centro > 1 and
    (carnets_templos.templos_disponibles > 0 or CE.templos_disponibles > 0) and
    carnets_templos_tipos.borrado = 0 and
    carnets_templos.id_carnet NOT IN
    (
      select distinct id_carnet
      from dietario
      where dietario.borrado = 0
      and dietario.id_carnet > 0
      and dietario.estado = 'Pagado'
      and dietario.fecha_hora_concepto >= DATE_SUB(CURDATE(),INTERVAL 12 month)
      and dietario.fecha_hora_concepto <= CURDATE()
    )
    ORDER BY carnets_templos.id_centro,COUNT(carnets_templos.id_carnet) desc";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function carnets_sin_usar_tipo_centro($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $sentencia_sql = "SELECT carnets_templos.codigo,carnets_templos.id_carnet,carnets_templos.id_tipo,
    carnets_templos_tipos.descripcion,carnets_templos.id_centro,
    carnets_templos.templos_disponibles as numero_templos,
    CE.templos_disponibles as numero_templos_especiales
    FROM carnets_templos
    LEFT JOIN carnets_templos_tipos on carnets_templos_tipos.id_tipo = carnets_templos.id_tipo
    LEFT JOIN carnets_especiales_estado_templos as CE on CE.id_carnet = carnets_templos.id_carnet
    WHERE
    carnets_templos.borrado = 0 and
    carnets_templos.id_tipo = @id_tipo and
    carnets_templos.id_centro = @id_centro and
    (carnets_templos.templos_disponibles > 0 or CE.templos_disponibles > 0) and
    carnets_templos_tipos.borrado = 0 and
    carnets_templos.id_carnet NOT IN
    (
      select distinct id_carnet
      from dietario
      where dietario.borrado = 0
      and dietario.id_carnet > 0
      and dietario.estado = 'Pagado'
      and dietario.fecha_hora_concepto >= DATE_SUB(CURDATE(),INTERVAL 12 month)
      and dietario.fecha_hora_concepto <= CURDATE()
    )
    ORDER BY carnets_templos.codigo";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    // ----------------------------------------------------------------
    // ... Estadistica general por dias
    // ----------------------------------------------------------------
    function general_dias($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda_dietario = "";
        $centro = "";
        $centro_general = "";
        $centro_descuadre = "";

        if (isset($parametros['id_centro'])) {
            $centro_general = " D.id_centro = @id_centro and ";
            $centro = " dietario.id_centro = @id_centro and ";
            $centro_descuadre = " C.id_centro = @id_centro and ";
        }

        if (isset($parametros['fecha_desde']) && isset($parametros['fecha_hasta'])) {
            $busqueda_dietario = " D.fecha_hora_concepto >= @fecha_desde AND
      D.fecha_hora_concepto <= @fecha_hasta AND ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT DATE_FORMAT(fecha_hora_concepto,'%Y-%m-%d') as fecha,
    DATE_FORMAT(fecha_hora_concepto,'%d-%m-%Y') as fecha_ddmmaaaa,

    (SELECT descuadre_efectivo+descuadre_tarjeta+descuadre_habitacion+descuadre_transferencia
    FROM cajas_cierres as C
    WHERE
    C.borrado = 0 and
    $centro_descuadre
    DATE_FORMAT(C.fecha_creacion,'%Y-%m-%d') = fecha
    order by C.fecha_creacion DESC LIMIT 1
    ) as descuadres,

    (SELECT COUNT(C.id_cita)
    from citas AS C
    left join dietario on dietario.id_cita = C.id_cita
    where
    (C.estado = 'Programada' OR C.estado = 'Finalizado') AND
    DATE_FORMAT(C.fecha_hora_inicio,'%Y-%m-%d') = fecha and
    C.duracion > 0 AND
    dietario.estado = 'Pagado' AND
    dietario.borrado = 0 and
    $centro
    C.borrado = 0
    group by DATE_FORMAT(C.fecha_hora_inicio,'%Y-%m-%d')) as citas_completadas,

    (SELECT COUNT(C.id_cita)
    from citas AS C
    left join dietario on dietario.id_cita = C.id_cita
    where
    C.estado = 'Anulada' AND
    C.duracion > 0 AND
    C.borrado = 0 AND
    $centro
    dietario.borrado = 0 AND
    DATE_FORMAT(C.fecha_hora_inicio,'%Y-%m-%d') = fecha
    ) as citas_anuladas,

    (SELECT COUNT(C.id_cita)
    from citas AS C
    left join dietario on dietario.id_cita = C.id_cita
    where
    C.estado = 'No vino' AND
    C.duracion > 0 AND
    C.borrado = 0 AND
    $centro
    dietario.borrado = 0 AND
    DATE_FORMAT(C.fecha_hora_inicio,'%Y-%m-%d') = fecha
    ) as citas_no_vino,

    (SELECT
    IFNULL(round(sum(dietario.importe_euros)-sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2),0)
    from dietario
    where dietario.borrado = 0
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    and (dietario.descuento_euros > 0 or dietario.descuento_porcentaje > 0) and
    $centro
    DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m-%d') = fecha)
    as descuentos,

    (SELECT COUNT(id_dietario)
    from dietario
    where dietario.borrado = 0
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    and (dietario.descuento_euros > 0 or dietario.descuento_porcentaje > 0) and
    $centro
    DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m-%d') = fecha)
    as descuentos_cantidad,

    (select IFNULL(round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2),0)
    from dietario
    where
    dietario.borrado = 0
    and dietario.id_carnet > 0
    and dietario.recarga = 0
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') and
    $centro
    DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m-%d') = fecha)
    as carnets_vendidos,

    (select COUNT(id_dietario)
    from dietario
    where
    dietario.borrado = 0
    and dietario.id_carnet > 0
    and dietario.recarga = 0
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') and
    $centro
    DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m-%d') = fecha)
    as carnets_vendidos_cantidad,

    (select round(sum(citas.duracion)/60,1)
    from citas
    left join dietario on dietario.id_cita = citas.id_cita
    where
    citas.borrado = 0 and
    DATE_FORMAT(citas.fecha_hora_inicio,'%Y-%m-%d') = fecha and
    citas.estado = 'Finalizado' and
    $centro
    dietario.borrado = 0
    group by DATE_FORMAT(citas.fecha_hora_inicio,'%Y-%m-%d')) as horas_trabajadas,

    (SELECT IFNULL(sum(TIMESTAMPDIFF(minute, fecha_inicio, fecha_fin) / 60),0)
    FROM usuarios_horarios_desglose where id_usuario in
    (select distinct id_empleado from dietario where borrado = 0 and
    $centro
    DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m-%d') = fecha)
    and DATE_FORMAT(fecha_inicio,'%Y-%m-%d') >= fecha
    and DATE_FORMAT(fecha_fin,'%Y-%m-%d') <= fecha)
    AS tiempo_jornada,

    (SELECT sum(dietario.templos) FROM dietario
    WHERE DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m-%d') = fecha and
    dietario.borrado = 0 and
    $centro
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    and dietario.id_servicio > 0) as templos,

    (select round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2)
    from dietario
    where dietario.borrado = 0 and
    DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m-%d') = fecha and
    dietario.id_producto > 0 and
    $centro
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')) as ventas_productos,

    (select round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2)
    from dietario where
    dietario.borrado = 0 and
    DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m-%d') = fecha and
    $centro
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')) as ventas,

    (select round(sum(pagado_efectivo),2)
    from dietario where dietario.borrado = 0 and
    DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m-%d') = fecha AND
    $centro
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto'))
    as ventas_efectivo,

    (select round(sum(pagado_tarjeta),2)
    from dietario where dietario.borrado = 0 and
    DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m-%d') = fecha and
    $centro
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto'))
    as ventas_tarjeta,
    
    (select round(sum(pagado_transferencia),2)
    from dietario where dietario.borrado = 0 and
    DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m-%d') = fecha and
    $centro
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto'))
    as ventas_transferencia,

    (select round(sum(pagado_habitacion),2)
    from dietario where dietario.borrado = 0 and
    DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m-%d') = fecha and
    $centro
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto'))
    as ventas_habitacion,

    (select round(sum(servicios.precio_proveedor),2)
    from dietario
    LEFT JOIN servicios on servicios.id_servicio = dietario.id_servicio
    where dietario.borrado = 0 and
    DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m-%d') = fecha and
    $centro
    dietario.id_servicio IN
    (select id_servicio from servicios where id_familia_servicio = 12)
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto'))
    as ventas_proveedores

    FROM dietario AS D
    WHERE 1=1 AND
    $busqueda_dietario
    (D.estado = 'Pagado' or D.estado = 'Devuelto') AND
    $centro_general
    D.borrado = 0
    group by DATE_FORMAT(D.fecha_hora_concepto,'%Y-%m-%d') ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function g_facturacion_euros_empleados($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT CONCAT(usuarios.nombre,' ' ,usuarios.apellidos) AS empleado,
    round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2) as euros,
    dietario.id_empleado
    FROM dietario
    left join usuarios on usuarios.id_usuario = dietario.id_empleado
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.id_centro = @id_centro AND
    usuarios.id_centro = @id_centro AND
    (dietario.id_servicio > 0 or dietario.id_producto > 0) and
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta AND
    usuarios.id_usuario in (select id_usuario from usuarios_perfiles
    where (id_perfil = 1 or id_perfil = 3))
    GROUP BY id_empleado
    order by round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2) desc ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        // ... Calculamos los euros de los pagos con carnets de templos o especiales.
        unset($param);
        $param['fecha_desde'] = $parametros['fecha_desde'];
        $param['fecha_hasta'] = $parametros['fecha_hasta'];
        $param['id_centro'] = $parametros['id_centro'];

        for ($i = 0; $i < count($datos); $i++) {
            $param['id_empleado'] = $datos[$i]['id_empleado'];

            $euros_templos = $this->empleado_facturacion_real_euros($param);
            $euros_templos_especiales = $this->empleado_facturacion_real_euros_especiales($param);
            $datos[$i]['euros'] = 0; // para inicializar la variable
            $datos[$i]['euros'] += $euros_templos + $euros_templos_especiales;
        }

        $r = $this->array_sort($datos, "euros");

        return $datos;
    }

    function g_facturacion_templos_empleados($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT CONCAT(usuarios.nombre,' ' ,usuarios.apellidos) AS empleado,
    sum(templos) as templos
    FROM dietario
    left join usuarios on usuarios.id_usuario = dietario.id_empleado
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.id_centro = @id_centro AND
    usuarios.id_centro = @id_centro AND
    dietario.id_servicio > 0 AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta AND
    usuarios.id_usuario in (select id_usuario from usuarios_perfiles
    where (id_perfil = 1 or id_perfil = 3))
    GROUP BY id_empleado
    order by sum(templos) desc ";

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function g_facturacion_euros_recepcionista($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT CONCAT(usuarios.nombre,' ' ,usuarios.apellidos) AS empleado,
    round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2) as euros
    FROM dietario
    left join usuarios on usuarios.id_usuario = dietario.id_empleado
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.id_centro = @id_centro AND
    usuarios.id_centro = @id_centro AND
    ((dietario.id_servicio = 0 and dietario.id_carnet > 0) or dietario.id_producto > 0) AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta AND
    dietario.recarga = 0 AND
    usuarios.id_usuario in (select id_usuario from usuarios_perfiles
    where (id_perfil = 2 or id_perfil = 3))
    GROUP BY id_empleado
    order by round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2) desc ";

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function g_facturacion_templos_recepcionistas($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT CONCAT(usuarios.nombre,' ' ,usuarios.apellidos) AS empleado,
    sum(templos) as templos
    FROM dietario
    left join usuarios on usuarios.id_usuario = dietario.id_empleado
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.id_centro = @id_centro AND
    usuarios.id_centro = @id_centro AND
    usuarios.borrado = 0 AND
    dietario.id_carnet > 0 AND dietario.id_servicio = 0 AND
    dietario.recarga = 0 AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta AND
    usuarios.id_usuario in (select id_usuario from usuarios_perfiles
    where borrado = 0 and (id_perfil = 2 or id_perfil = 3))
    GROUP BY id_empleado";

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function g_ventas_productos_empleados($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT CONCAT(usuarios.nombre,' ' ,usuarios.apellidos) AS empleado,
    round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2) as euros
    FROM dietario
    left join usuarios on usuarios.id_usuario = dietario.id_empleado
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.id_centro = @id_centro AND
    usuarios.id_centro = @id_centro AND
    dietario.id_producto > 0 AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta AND
    usuarios.id_usuario in (select id_usuario from usuarios_perfiles
    where (id_perfil = 1 or id_perfil = 3))
    GROUP BY id_empleado
    order by round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2) desc ";

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function g_ventas_carnets_templos_recepcionista($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT CONCAT(usuarios.nombre,' ' ,usuarios.apellidos) AS empleado,
    round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2) as euros,
    count(id_carnet) as cantidad
    FROM dietario
    left join usuarios on usuarios.id_usuario = dietario.id_empleado
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.id_centro = @id_centro AND
    usuarios.id_centro = @id_centro AND
    dietario.id_carnet > 0 AND dietario.id_servicio = 0 AND
    dietario.recarga = 0 and
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta AND
    usuarios.id_usuario in (select id_usuario from usuarios_perfiles
    where (id_perfil = 2 or id_perfil = 3))
    GROUP BY id_empleado
    order by count(id_carnet) desc";

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function g_citas_agregadas_agenda_recepcionista($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT CONCAT(usuarios.nombre,' ' ,usuarios.apellidos) AS empleado,
    count(id_cita) as citas
    FROM dietario
    left join usuarios on usuarios.id_usuario = dietario.id_usuario_creador
    WHERE dietario.borrado = 0 AND
    dietario.id_centro = @id_centro AND
    usuarios.id_centro = @id_centro AND
    dietario.id_cita > 0 and
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta AND
    usuarios.id_usuario in (select id_usuario from usuarios_perfiles
    where (id_perfil = 2 or id_perfil = 3))
    GROUP BY id_usuario_creador
    order by count(id_cita) desc ";

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function g_familias_servicios_realizados($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $sentencia_sql = "
    SELECT
      servicios_familias.nombre_familia,
      count(servicios_familias.id_familia_servicio) as cantidad
    FROM
      dietario
      left join
        (servicios left join servicios_familias on servicios_familias.id_familia_servicio
        = servicios.id_familia_servicio)
      on servicios.id_servicio = dietario.id_servicio
    WHERE
      dietario.borrado = 0 AND
      (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
      dietario.id_centro = @id_centro AND
      dietario.id_servicio > 0 AND
      dietario.fecha_hora_concepto >= @fecha_desde AND
      dietario.fecha_hora_concepto <= @fecha_hasta
    GROUP BY
      servicios_familias.id_familia_servicio
    ORDER BY
      count(servicios_familias.id_familia_servicio) DESC
    ";

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function g_clientes_con_carnets_vendidos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT carnets_templos_tipos.descripcion AS tipo_carnet,
    round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2) as euros,
    count(dietario.id_carnet) as cantidad
    FROM dietario
    left join (carnets_templos left join carnets_templos_tipos ON carnets_templos_tipos.id_tipo = carnets_templos.id_tipo)
    ON carnets_templos.id_carnet = dietario.id_carnet
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.id_centro = @id_centro AND
    dietario.id_carnet > 0 AND dietario.id_servicio = 0 AND dietario.recarga = 0 and
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta
    GROUP BY carnets_templos.id_tipo
    order by count(dietario.id_carnet) desc";

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function g_clientes_con_carnets_vendidos_todos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT carnets_templos_tipos.descripcion AS tipo_carnet,
    round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2) as euros,
    count(dietario.id_carnet) as cantidad
    FROM dietario
    left join (carnets_templos left join carnets_templos_tipos ON carnets_templos_tipos.id_tipo = carnets_templos.id_tipo)
    ON carnets_templos.id_carnet = dietario.id_carnet
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.id_carnet > 0 AND dietario.id_servicio = 0 AND dietario.recarga = 0 and
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta
    GROUP BY carnets_templos.id_tipo
    order by count(dietario.id_carnet) desc";

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function g_empleado_cliente_solo_con_este($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "select
    (SELECT count(id_cita) FROM `citas`
    WHERE estado = 'Finalizado' and solo_este_empleado = 1
    and fecha_hora_inicio >= @fecha_desde AND
    fecha_hora_inicio <= @fecha_hasta
    AND id_cita in (select id_cita from dietario where borrado = 0
    and id_centro = @id_centro)
    ) as solo_con_empleado,
    (SELECT count(id_cita) FROM `citas`
    WHERE estado = 'Finalizado' and solo_este_empleado = 0
    and fecha_hora_inicio >= @fecha_desde AND
    fecha_hora_inicio <= @fecha_hasta
    AND id_cita in (select id_cita from dietario where borrado = 0
    and id_centro = @id_centro)
    ) as indiferente";

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function g_clientes_activos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "select

    (select count(id_cliente) from clientes
    where id_cliente in (select id_cliente from dietario
    where borrado = 0 and fecha_hora_concepto >= (now() - interval 6 month)
    and estado = 'Pagado' and id_centro = @id_centro)) as activos,

    (select count(id_cliente) from clientes
    where id_cliente not in (select id_cliente from dietario
    where borrado = 0 and fecha_hora_concepto >= (now() - interval 6 month)
    and estado = 'Pagado' and id_centro = @id_centro)) as inactivos
    ";

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function g_clientes_activos_todos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "select

    (select count(id_cliente) from clientes
    where id_cliente in (select id_cliente from dietario
    where borrado = 0 and fecha_hora_concepto >= (now() - interval 6 month)
    and estado = 'Pagado')) as activos,

    (select count(id_cliente) from clientes
    where id_cliente not in (select id_cliente from dietario
    where borrado = 0 and fecha_hora_concepto >= (now() - interval 6 month)
    and estado = 'Pagado')) as inactivos
    ";

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function g_pago_euros_templos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT

    (SELECT count(id_dietario)
    FROM dietario
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    (dietario.tipo_pago like '%#efectivo%' or dietario.tipo_pago like '%#tarjeta%' or
     dietario.tipo_pago like '%#habitacion%') and
    dietario.id_centro = @id_centro AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta) as euros,

    (SELECT count(id_dietario)
    FROM dietario
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.tipo_pago like '%#templos%' and
    dietario.id_centro = @id_centro AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta) as templos
    ";

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function g_pago_euros_templos_todos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT

    IFNULL((SELECT count(id_dietario)
    FROM dietario
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    (dietario.tipo_pago like '%#efectivo%' or dietario.tipo_pago like '%#tarjeta%' or
     dietario.tipo_pago like '%#habitacion%') and
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta),0) as euros,

    IFNULL((SELECT count(id_dietario)
    FROM dietario
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.tipo_pago like '%#templos%' and
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta),0) as templos
    ";

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function g_facturacion_por_centro($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "
    SELECT
      centros.nombre_centro,
      round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2) as euros
    FROM
      dietario
        left join centros on centros.id_centro = dietario.id_centro
    WHERE
      dietario.borrado = 0 AND
      dietario.id_centro > 1 AND
      (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
      dietario.fecha_hora_concepto >= @fecha_desde AND
      dietario.fecha_hora_concepto <= @fecha_hasta
    GROUP BY
      dietario.id_centro
    ORDER BY
      round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2) DESC
    ";

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function g_facturacion_por_centro_central($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "
    SELECT
      'Central' as nombre_centro,
      IFNULL(round(sum(precio),2),0) as euros
    FROM
      carnets_templos
    WHERE
      borrado = 0 and
      id_centro = 1 and
      (codigo_tienda = '' or codigo_tienda is null) and
      (codigo_pack_online = '' or codigo_pack_online is null) and
      fecha_creacion >= @fecha_desde and
      fecha_creacion <= @fecha_hasta
    ";

        //(codigo like '%_WEB' or id_cliente = 0) and

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function g_horas_totales_trabajadas_por_centro($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = " Select nombre_centro,(select round(sum(duracion)/60,1)
    from citas
    where citas.borrado = 0 AND
    citas.id_cita in (select id_cita from dietario where id_centro = centros.id_centro) and
    citas.estado = 'Finalizado' AND
    citas.fecha_hora_inicio >= @fecha_desde AND
    citas.fecha_hora_inicio <= @fecha_hasta) as horas_trabajadas
    from centros
    where borrado = 0 and id_centro > 1
    order by horas_trabajadas desc ";

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    // --------------------------------------------------------------------
    // ... Estadisticas por Empleado
    // --------------------------------------------------------------------
    function empleado_facturacion_servicios_euros($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND dietario.id_empleado = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND dietario.id_centro = @id_centro AND dietario.id_empleado IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 1 or id_perfil = 3))";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2) as euros
    FROM dietario
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.id_servicio > 0 AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta
    $buscar ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['euros'];
    }

    function empleado_facturacion_real_euros($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND CT.id_empleado = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND CT.id_centro = @id_centro AND CT.id_empleado IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 1 or id_perfil = 3))";
        }

        // ... Leemos los registros
        $sentencia_sql = "
    SELECT
      SUM(round((carnets_templos.precio / carnets_templos_tipos.templos) * CT.templos,2)) as total_euros_templos
    FROM
      carnets_templos_historial AS CT
      LEFT JOIN carnets_templos on carnets_templos.id_carnet = CT.id_carnet
      LEFT JOIN carnets_templos_tipos on carnets_templos_tipos.id_tipo = carnets_templos.id_tipo
    WHERE
      CT.borrado = 0 AND
      CT.fecha_creacion >= @fecha_desde AND
      CT.fecha_creacion <= @fecha_hasta
      $buscar
    ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['total_euros_templos'];
    }

    function empleado_facturacion_real_euros_especiales($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND dietario.id_empleado = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND dietario.id_centro = @id_centro AND dietario.id_empleado IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 1 or id_perfil = 3))";
        }

        // ... Leemos los registros
        $sentencia_sql = "
    SELECT
            SUM(round(servicios.templos * RCTS.precio_templo,2)) as euros
    FROM
            carnets_templos_servicios
            INNER join dietario on dietario.id_dietario = carnets_templos_servicios.id_dietario
            INNER join servicios on servicios.id_servicio = carnets_templos_servicios.id_servicio
            INNER join
                    (SELECT
                            carnets_templos.id_carnet,(carnets_templos.precio / SUM(servicios.templos)) as precio_templo
                            from carnets_templos_servicios as CTS
                                    left join carnets_templos on carnets_templos.id_carnet = CTS.id_carnet
                                    left join servicios on servicios.id_servicio = CTS.id_servicio
                            where
                                    CTS.borrado = 0 and
                                    carnets_templos.precio > 0 and
                                    carnets_templos.id_tipo = 99
                    group by carnets_templos.id_carnet) RCTS
            ON RCTS.id_carnet = carnets_templos_servicios.id_carnet
    WHERE
            carnets_templos_servicios.borrado = 0 and
            carnets_templos_servicios.gastado = 1 and
            dietario.fecha_hora_concepto >= @fecha_desde and
            dietario.fecha_hora_concepto <= @fecha_hasta
            $buscar
    ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['euros'];
    }

    function empleado_numero_servicios($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND dietario.id_empleado = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND dietario.id_centro = @id_centro AND dietario.id_empleado IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 1 or id_perfil = 3))";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT count(id_dietario) as cantidad
    FROM dietario
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.id_servicio > 0 AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta
    $buscar ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['cantidad'];
    }

    function empleado_templos_realizados($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND dietario.id_empleado = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND dietario.id_centro = @id_centro AND dietario.id_empleado IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 1 or id_perfil = 3))";
        }

        // ... Leemos los registros
        $sentencia_sql = "select
    ((select IFNULL(sum(templos),0) from dietario where dietario.borrado = 0
    and dietario.id_servicio > 0
    and (dietario.estado = 'Pagado') AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta
    $buscar)
    -
    (select IFNULL(sum(templos),0) from dietario where dietario.borrado = 0
    and dietario.id_servicio > 0
    and (dietario.estado = 'Devuelto') AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta
    $buscar))
    as templos";

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['templos'];
    }

    function empleado_productos_vendidos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND dietario.id_empleado = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND dietario.id_centro = @id_centro AND dietario.id_empleado IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 1 or id_perfil = 3))";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT COUNT(id_dietario) as cantidad
    FROM dietario
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.id_producto > 0 AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta
    $buscar ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['cantidad'];
    }

    function empleado_total_productos_vendidos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND dietario.id_empleado = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND dietario.id_centro = @id_centro AND dietario.id_empleado IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 1 or id_perfil = 3))";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2) as euros
    FROM dietario
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.id_producto > 0 AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta
    $buscar ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['euros'];
    }

    function empleado_clientes_fieles($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND dietario.id_empleado = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND dietario.id_centro = @id_centro AND dietario.id_empleado IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 1 or id_perfil = 3))";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT distinct dietario.id_cliente as id_cliente
    FROM dietario
    left join citas on citas.id_cita = dietario.id_cita
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.id_servicio > 0 AND citas.solo_este_empleado = 1 AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta
    $buscar ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return (is_array($datos)) ? count($datos) : 0;
    }

    function empleado_horas_trabajadas($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT round(sum(duracion)/60,1) as horas_trabajadas
    FROM citas
    WHERE
    citas.id_usuario_empleado = @id_empleado AND
    citas.borrado = 0 AND
    citas.estado = 'Finalizado' AND
    citas.fecha_hora_inicio >= @fecha_desde AND
    citas.fecha_hora_inicio <= @fecha_hasta ";

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['horas_trabajadas'];
    }

    function empleado_horas_trabajadas_todos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT round(sum(duracion)/60,1) as horas_trabajadas
    FROM citas
    WHERE
    citas.id_cita IN
      (select id_cita from dietario
        where
        dietario.fecha_hora_concepto >= @fecha_desde AND
        dietario.fecha_hora_concepto <= @fecha_hasta AND
        dietario.estado = 'Pagado' AND
        dietario.borrado = 0 AND
        dietario.id_centro = @id_centro
        AND dietario.id_empleado IN
        (select id_usuario from usuarios_perfiles where (id_perfil = 1 or id_perfil = 3))
        ) AND
    citas.borrado = 0 AND
    citas.estado = 'Finalizado' AND
    citas.fecha_hora_inicio >= @fecha_desde AND
    citas.fecha_hora_inicio <= @fecha_hasta ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['horas_trabajadas'];
    }

    function empleado_clientes_nuevos_atendidos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND id_empleado = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND id_centro = @id_centro AND id_empleado IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 1 or id_perfil = 3))";
        }

        // ... Leemos los registros
        $sentencia_sql = "
    SELECT count(id_cliente) as cantidad
    FROM clientes_primer_centros
    WHERE
    fecha >= @fecha_desde AND
    fecha <= @fecha_hasta
    $buscar ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['cantidad'];
    }

    function empleado_clientes_ultima_visita_atendidos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND id_empleado = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND id_centro = @id_centro AND id_empleado IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 1 or id_perfil = 3))";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT count(id_cliente) as cantidad
    FROM clientes_ultimos_centros
    WHERE
    fecha >= @fecha_desde AND
    fecha <= @fecha_hasta
    $buscar ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['cantidad'];
    }

    function empleado_clientes_atendidos_total($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND dietario.id_empleado = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND dietario.id_centro = @id_centro AND dietario.id_empleado IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 1 or id_perfil = 3))";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT distinct dietario.id_cliente as id_cliente
    FROM dietario
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.id_servicio > 0 AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta
    $buscar";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return (is_array($datos)) ? count($datos) : 0;
    }

    function empleado_numero_servicios_pago_carnet($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND dietario.id_empleado = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND dietario.id_centro = @id_centro AND dietario.id_empleado IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 1 or id_perfil = 3))";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT count(id_dietario) as cantidad
    FROM dietario
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.id_servicio > 0 AND
    dietario.tipo_pago like '%#templos%' AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta
    $buscar";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['cantidad'];
    }

    function empleado_numero_servicios_pago_euros($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND dietario.id_empleado = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND dietario.id_centro = @id_centro AND dietario.id_empleado IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 1 or id_perfil = 3))";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT count(id_dietario) as cantidad
    FROM dietario
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.id_servicio > 0 AND
    (dietario.tipo_pago like '%#efectivo%' or
    dietario.tipo_pago like '%#tarjeta%' or
    dietario.tipo_pago like '%#habitacion%') AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta
    $buscar";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['cantidad'];
    }

    function empleado_cobros_descuentos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND dietario.id_empleado = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND dietario.id_centro = @id_centro AND dietario.id_empleado IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 1 or id_perfil = 3))";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT count(id_dietario) as cantidad
    FROM dietario
    WHERE dietario.borrado = 0 AND
    (dietario.descuento_euros > 0 or
    dietario.descuento_porcentaje > 0) AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta
    $buscar";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['cantidad'];
    }

    function empleado_total_dinero_descontado($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND dietario.id_empleado = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND dietario.id_centro = @id_centro AND dietario.id_empleado IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 1 or id_perfil = 3))";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2) as euros
    FROM dietario
    WHERE dietario.borrado = 0 AND
    (dietario.descuento_euros > 0 or
    dietario.descuento_porcentaje > 0) AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta
    $buscar";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['euros'];
    }

    function empleados_dietario_periodo($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT count(distinct id_empleado) as cantidad
    FROM dietario
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.id_servicio > 0 AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta AND
    dietario.id_centro = (select id_centro from usuarios where id_usuario = @id_empleado) ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['cantidad'];
    }

    // --------------------------------------------------------------------
    // ... Estadisticas por Recepcionistas
    // --------------------------------------------------------------------
    function recep_facturacion_produtos_carnets_euros($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND dietario.id_empleado = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND dietario.id_centro = @id_centro AND dietario.id_empleado IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 2 or id_perfil = 3))";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2) as euros
    FROM dietario
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta
    $buscar";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['euros'];
    }

    function recep_numero_carnets_vendidos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND dietario.id_empleado = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND dietario.id_centro = @id_centro AND dietario.id_empleado IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 2 or id_perfil = 3))";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT COUNT(id_dietario) as cantidad
    FROM dietario
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado') AND
    dietario.id_carnet > 0 AND
    dietario.recarga = 0 AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta
    $buscar";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['cantidad'];
    }

    function recep_numero_citas_anuladas($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND C.id_usuario_creador = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND C.id_usuario_creador IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 2 or id_perfil = 3))
      AND dietario.id_centro = @id_centro ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT COUNT(C.id_cita) as cantidad
    from citas AS C
    left join dietario on dietario.id_cita = C.id_cita
    where
    C.estado = 'Anulada' AND
    C.duracion > 0 AND
    C.borrado = 0 AND
    dietario.borrado = 0 AND
    C.fecha_hora_inicio >= @fecha_desde AND
    C.fecha_hora_inicio <= @fecha_hasta $buscar";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['cantidad'];
    }

    function recep_numero_citas_novino($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND C.id_usuario_creador = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND C.id_usuario_creador IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 2 or id_perfil = 3))
      AND dietario.id_centro = @id_centro ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT COUNT(C.id_cita) as cantidad
    from citas AS C
    left join dietario on dietario.id_cita = C.id_cita
    where
    C.estado = 'No vino' AND
    C.duracion > 0 AND
    C.borrado = 0 AND
    dietario.borrado = 0 AND
    C.fecha_hora_inicio >= @fecha_desde AND
    C.fecha_hora_inicio <= @fecha_hasta $buscar";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['cantidad'];
    }

    function recep_numero_citas_creadas($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND C.id_usuario_creador = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND C.id_usuario_creador IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 2 or id_perfil = 3))
      AND dietario.id_centro = @id_centro ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT COUNT(C.id_cita) as cantidad
    from citas AS C
    left join dietario on dietario.id_cita = C.id_cita
    where
    C.duracion > 0 AND
    C.borrado = 0 AND
    dietario.borrado = 0 AND
    C.fecha_hora_inicio >= @fecha_desde AND
    C.fecha_hora_inicio <= @fecha_hasta $buscar";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['cantidad'];
    }

    function recep_numero_citas_modificadas($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND C.id_usuario_modificacion = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND C.id_usuario_creador IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 2 or id_perfil = 3))
      AND dietario.id_centro = @id_centro ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT COUNT(C.id_cita) as cantidad
    from citas AS C
    left join dietario on dietario.id_cita = C.id_cita
    where
    C.duracion > 0 AND
    C.borrado = 0 AND
    dietario.borrado = 0 AND
    C.fecha_creacion <> C.fecha_modificacion AND
    C.fecha_hora_inicio >= @fecha_desde AND
    C.fecha_hora_inicio <= @fecha_hasta $buscar";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['cantidad'];
    }

    function recep_numero_cajas_descuadre($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND id_usuario_creacion = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND id_usuario_creacion IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 2 or id_perfil = 3))
      AND id_centro = @id_centro ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT count(id) as cantidad FROM cajas_cierres
    WHERE
    (descuadre_efectivo <> 0 or descuadre_tarjeta <> 0 or descuadre_habitacion <> 0) AND
    fecha_creacion >= @fecha_desde and
    fecha_creacion <= @fecha_hasta
    $buscar ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['cantidad'];
    }

    function recep_login_fecha($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT count(id) as filas FROM usuarios_accesos
    WHERE
    id_usuario = @id_empleado and
    @fecha BETWEEN fecha_inicio AND fecha_fin";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        if ($datos[0]['filas'] > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    function recep_clientes_nuevos_atendidos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT DATE_FORMAT(fecha,'%Y-%m-%d %H:%i:%s') as fecha
    FROM clientes_primer_centros
    WHERE
    id_centro in (select id_centro from usuarios where id_usuario = @id_empleado) and
    fecha >= @fecha_desde
    AND fecha <= @fecha_hasta
    order by fecha ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function recep_clientes_ultima_visita($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT DATE_FORMAT(fecha,'%Y-%m-%d %H:%i:%s') as fecha
    FROM clientes_ultimos_centros
    WHERE
    id_centro in (select id_centro from usuarios where id_usuario = @id_empleado) and
    fecha >= @fecha_desde
    AND fecha <= @fecha_hasta
    order by fecha ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function recep_clientes_atendidos_total($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m-%d %H:%i:%s') as fecha
    FROM dietario
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.id_servicio > 0 AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta AND
    dietario.id_centro in (select id_centro from usuarios where id_usuario = @id_empleado)
    order by fecha_hora_concepto ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function recep_productos_vendidos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND dietario.id_empleado = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND dietario.id_centro = @id_centro AND dietario.id_empleado IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 2 or id_perfil = 3))";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT COUNT(id_dietario) as cantidad
    FROM dietario
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.id_producto > 0 AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta
    $buscar ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['cantidad'];
    }

    function recep_total_productos_vendidos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND dietario.id_empleado = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND dietario.id_centro = @id_centro AND dietario.id_empleado IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 2 or id_perfil = 3))";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2) as euros
    FROM dietario
    WHERE dietario.borrado = 0 AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.id_producto > 0 AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta
    $buscar ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['euros'];
    }

    function recep_cobros_descuentos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND dietario.id_empleado = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND dietario.id_centro = @id_centro AND dietario.id_empleado IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 2 or id_perfil = 3))";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT count(id_dietario) as cantidad
    FROM dietario
    WHERE dietario.borrado = 0 AND
    (dietario.descuento_euros > 0 or
    dietario.descuento_porcentaje > 0) AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta
    $buscar";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['cantidad'];
    }

    function recep_total_dinero_descontado($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND dietario.id_empleado = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND dietario.id_centro = @id_centro AND dietario.id_empleado IN
      (select id_usuario from usuarios_perfiles where (id_perfil = 2 or id_perfil = 3))";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2) as euros
    FROM dietario
    WHERE dietario.borrado = 0 AND
    (dietario.descuento_euros > 0 or
    dietario.descuento_porcentaje > 0) AND
    (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto') AND
    dietario.fecha_hora_concepto >= @fecha_desde AND
    dietario.fecha_hora_concepto <= @fecha_hasta
    $buscar";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['euros'];
    }

    function recep_dietario_periodo($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "select count(distinct usuarios_accesos.id_usuario) as cantidad
    from usuarios_accesos
    left join usuarios on usuarios.id_usuario = usuarios_accesos.id_usuario
    where
    usuarios_accesos.id_usuario in
      (select id_usuario from usuarios_perfiles where (id_perfil = 2 or id_perfil = 3)) and
    fecha_inicio >= @fecha_desde and
    fecha_inicio <= @fecha_hasta and
    usuarios.id_centro = @id_centro ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['cantidad'];
    }

    function servicios($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar_fecha = "";
        $filtro_centros = "";

        // ... Leemos los datos
        if (isset($parametros['fecha_desde']) && isset($parametros['fecha_hasta'])) {
            $parametros['fecha_desde'] = $parametros['fecha_desde'] . " 00:00:00";
            $parametros['fecha_hasta'] = $parametros['fecha_hasta'] . " 23:59:59";

            $buscar_fecha = " AND fecha_hora_concepto >= @fecha_desde
      AND fecha_hora_concepto <= @fecha_hasta ";
        }

        unset($param);
        $param['vacio'] = "";
        $centros = $this->Usuarios_model->leer_centros($param);

        foreach ($centros as $row) {
            if ($row['id_centro'] > 1) {
                $filtro_centros .= " (select count(id_dietario) from dietario
        where id_servicio = S.id_servicio and borrado = 0 and estado = 'Pagado'
        $buscar_fecha and id_centro = " . $row['id_centro'] . ") as c" . $row['id_centro'] . ", ";
            }
        }

        $sentencia_sql = "SELECT nombre_servicio, $filtro_centros id_servicio
    from servicios as S
    where id_familia_servicio = @id_familia_servicio
    group by id_servicio,nombre_servicio ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function venta_carnets($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "
    SELECT
      id_tipo, tipo, count(id_tipo) as cantidad,
      0 as nuevas_ventas, 0 as carnet_superior
    FROM ventas_carnets
    WHERE
      id_empleado = @id_empleado and
      fecha_hora_concepto >= @fecha_desde and
      fecha_hora_concepto <= @fecha_hasta
    GROUP BY id_tipo
    ORDER BY tipo ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function venta_carnets_nuevos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $tipos_carnets = "";
        if ($parametros['carnets'] == "templos") {
            $tipos_carnets .= " D2.id_tipo <> 99 and ";
        } else {
            $tipos_carnets .= " D2.id_tipo = 99 and ";
        }

        // ... Leemos los registros
        $sentencia_sql = "
    SELECT count(D.id_dietario) as nuevo
      FROM ventas_carnets as D
      WHERE
        D.id_empleado = @id_empleado and
        D.fecha_hora_concepto >= @fecha_desde and
        D.fecha_hora_concepto <= @fecha_hasta and
        D.id_tipo = @id_tipo and
        D.id_cliente not in
        (
          select D2.id_cliente
          from carnets_templos As D2
          where
            D2.borrado = 0 AND
            $tipos_carnets
            D2.id_cliente = D.id_cliente and
            D2.id_carnet <> D.id_carnet and
            D2.fecha_creacion <= @fecha_hasta
        )
    ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['nuevo'];
    }

    function venta_carnets_superior($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Primero comprobamos si es la primera venta de un carnet
        // en ese caso se devuelve siempre 0, porque no hay nada superior
        // contra lo que comparar.

        // ... Leemos los registros
        $sentencia_sql = "
    SELECT count(D.id_dietario) as superior
      FROM ventas_carnets AS D
      WHERE
        D.id_empleado = @id_empleado and
        D.fecha_hora_concepto >= @fecha_desde and
        D.fecha_hora_concepto <= @fecha_hasta and
        D.id_tipo = @id_tipo and
        (select count(id_carnet) from carnets_templos where borrado = 0 and id_cliente = D.id_cliente) > 1 and
        D.id_cliente not in
        (
          select D2.id_cliente
          from carnets_templos As D2
          where
            D2.borrado = 0 and
            D2.precio > 0 and
            D2.precio >= D.importe_euros and
            D2.id_cliente = D.id_cliente and
            D2.id_carnet <> D.id_carnet and
            D2.fecha_creacion <= @fecha_hasta
        )
    ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['superior'];
    }

    function productos_resumen($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar_fecha = "";
        $filtro_centros = "";

        // ... Leemos los datos
        if (isset($parametros['fecha_desde']) && isset($parametros['fecha_hasta'])) {
            $parametros['fecha_desde'] = $parametros['fecha_desde'] . " 00:00:00";
            $parametros['fecha_hasta'] = $parametros['fecha_hasta'] . " 23:59:59";

            $buscar_fecha = " AND fecha_hora_concepto >= @fecha_desde
      AND fecha_hora_concepto <= @fecha_hasta ";
        }

        unset($param);
        $param['vacio'] = "";
        $centros = $this->Usuarios_model->leer_centros($param);

        foreach ($centros as $row) {
            if ($row['id_centro'] > 1) {
                $filtro_centros .= " (select count(id_dietario) from dietario
        where id_producto = P.id_producto and borrado = 0 and estado = 'Pagado'
        $buscar_fecha and id_centro = " . $row['id_centro'] . ") as c" . $row['id_centro'] . ", ";
            }
        }

        $sentencia_sql = "SELECT nombre_producto, $filtro_centros id_producto
    from productos as P
    where id_familia_producto = @id_familia_producto and borrado = 0
    group by id_producto,nombre_producto ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    //
    function codigos_tienda_online($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['id_centro'])) {
            if ($parametros['id_centro'] > 0) {
                $busqueda .= "AND usuarios.id_centro = @id_centro ";
            }
        }

        if (isset($parametros['fecha_desde']) && isset($parametros['fecha_hasta'])) {
            $busqueda .= " AND C.fecha_creacion >= @fecha_desde
      AND C.fecha_creacion <= @fecha_hasta ";
        }

        // ... Leemos los registros
        $sentencia_sql = "
    SELECT
      C.id_carnet,C.id_tipo,C.codigo,C.codigo_pack_online,C.templos,C.codigo_tienda,
      C.templos_disponibles,C.id_cliente,C.id_centro,C.precio,C.activo_online,
      C.id_usuario_creador,C.fecha_creacion,C.id_usuario_modificacion,
      C.fecha_modificacion,C.borrado,C.id_usuario_borrado,C.fecha_borrado,
      CONCAT(clientes.nombre, ' ', clientes.apellidos) As cliente,
      carnets_templos_tipos.descripcion as tipo,centros.nombre_centro,
      CONCAT(usuarios.nombre, ' ', usuarios.apellidos) As empleado,
      DATE_FORMAT(C.fecha_creacion,'%d-%m-%Y') as fecha_vendido,
      DATE_FORMAT(C.fecha_creacion,'%Y-%m-%d') as fecha_aaaammdd_vendido,
      DATE_FORMAT(C.fecha_creacion,'%W, %d-%b-%Y - %H:%i') as fecha_vendido_abrev,
      C.notas,
      (
        select sum(pvp) from carnets_templos_servicios where id_carnet = C.id_carnet
        and borrado = 0
      ) as precio_servicios,
      carnets_templos_tipos.id_tipo_padre,CU.nombre_centro as nombre_centro_generado
    FROM
      carnets_templos AS C
      LEFT JOIN clientes on clientes.id_cliente = C.id_cliente
      LEFT JOIN usuarios on usuarios.id_usuario = C.id_usuario_creador
      LEFT JOIN centros on centros.id_centro = C.id_centro
      LEFT JOIN centros AS CU on CU.id_centro = usuarios.id_centro
      LEFT JOIN carnets_templos_tipos on carnets_templos_tipos.id_tipo = C.id_tipo
    WHERE
      (C.codigo_tienda is not null or C.codigo_tienda <> '') and
      C.borrado = 0 " . $busqueda . " ORDER BY codigo ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    //
    function numero_clientes_registrados()
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "
    SELECT
      COUNT(id_cliente) as cantidad
    FROM
      clientes
    WHERE
      borrado = 0 and
      (password is not null or password <> '') and
      (email is not null or email <> '')
    ";
        $datos = $AqConexion_model->select($sentencia_sql, '');

        return $datos[0]['cantidad'];
    }

    //
    function numero_clientes_verificados()
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "
    SELECT
      COUNT(id_cliente) as cantidad
    FROM
      clientes
    WHERE
      borrado = 0 and
      (password is not null or password <> '') and
      (email is not null or email <> '') and
      activo = 1
    ";
        $datos = $AqConexion_model->select($sentencia_sql, '');

        return $datos[0]['cantidad'];
    }

    //
    function numero_clientes_con_citas_online()
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "
    SELECT
      COUNT(id_cliente) as cantidad
    FROM
      clientes
    WHERE
      borrado = 0 and
      (password is not null or password <> '') and
      (email is not null or email <> '') and
      activo = 1 and
      id_cliente in (select id_cliente from dietario where borrado = 0 and id_pedido > 0)
    ";
        $datos = $AqConexion_model->select($sentencia_sql, '');

        return $datos[0]['cantidad'];
    }

    //
    function sms_enviados($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['fecha_desde']) && isset($parametros['fecha_hasta'])) {
            $busqueda .= " AND S.fecha_envio >= @fecha_desde
      AND S.fecha_envio <= @fecha_hasta ";
        }

        // ... Leemos los registros
        $sentencia_sql = "
    SELECT distinct S.id_centro,centros.nombre_centro, count(*) as cantidad
    FROM sms_envios as S
    LEFT JOIN centros on centros.id_centro = S.id_centro
    WHERE 1 = 1 $busqueda
    GROUP BY S.id_centro
    HAVING COUNT(*)>0 ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    //
    function pagos_tpv($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['fecha_desde']) && isset($parametros['fecha_hasta'])) {
            $busqueda .= " AND dietario.fecha_pagado >= @fecha_desde
      AND dietario.fecha_pagado <= @fecha_hasta ";
        }

        // ... Leemos los registros
        $sentencia_sql = "
    SELECT
      centros.nombre_centro,sum(pagado_tarjeta) as facturacion
    FROM dietario
      LEFT JOIN centros on centros.id_centro = dietario.id_centro
    WHERE
      dietario.borrado = 0 and
      dietario.estado = 'Pagado' and
      dietario.tipo_pago like '%#tarjeta%' AND
      dietario.id_pedido = 0 and
      dietario.id_centro > 1
      $busqueda
    GROUP BY dietario.id_centro
    ORDER BY centros.nombre_centro";

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    //
    function pagos_tpv_online($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['fecha_desde']) && isset($parametros['fecha_hasta'])) {
            $busqueda .= " AND dietario.fecha_pagado >= @fecha_desde
      AND dietario.fecha_pagado <= @fecha_hasta ";
        }

        // ... Leemos los registros
        $sentencia_sql = "
    SELECT
      centros.nombre_centro,sum(pagado_tarjeta) as facturacion
    FROM dietario
      LEFT JOIN centros on centros.id_centro = dietario.id_centro
    WHERE
      dietario.borrado = 0 and
      dietario.estado = 'Pagado' and
      dietario.tipo_pago like '%#tarjeta%' AND
      dietario.id_pedido > 1 and
      dietario.id_centro > 1
      $busqueda
    GROUP BY dietario.id_centro
    ORDER BY centros.nombre_centro";

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    //
    function pagos_tpv_bonos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['fecha_desde']) && isset($parametros['fecha_hasta'])) {
            $busqueda .= " AND fecha_creacion >= @fecha_desde
      AND fecha_creacion <= @fecha_hasta ";
        }

        // ... Leemos los registros
        $sentencia_sql = "
    SELECT
      sum(total) as facturacion
    FROM carnets_templos_pedidos
    WHERE
    estado = 'Finalizado' and
    borrado = 0
    $busqueda
    ";

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function array_sort($array, $on, $order = SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();

        if ((count($array) > 0) && (is_array($array))) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }

    /////////////////////////////////////////////////////////////////////


    function horas_de_empleado_en_dia($parametros)
    {
        // se recibe el id del empleado, el dia a buscar y el tipo de jornada.
        // se retorna el numero de horas que trabaja en ese dia.
        $AqConexion_model = new AqConexion_model();
        $buscar = "";
        if (isset($parametros['jornada'])) {
            if (is_array($parametros['jornada'])) {
                $buscar .= " AND (";
                $total = count($parametros['jornada']);
                $i = 1;
                foreach ($parametros['jornada'] as $key => $value) {
                    if ($i > 1) {
                        // si no es la primera vuelta, se añade el OR
                        $buscar .= " OR ";
                    }

                    $buscar .= " usuarios_horarios.jornada = '" . $value . "'";

                    if ($i == $total) {
                        // Si es la última vuelta, se cierra el paréntesis del grupo de condiciones
                        $buscar .= " ) ";
                    }

                    $i = $i + 1;
                }
            } else {
                $buscar .= " ";
            }
            unset($parametros['jornada']);
        } else {
            $buscar .= " AND usuarios_horarios.jornada != 'Vacaciones' AND usuarios_horarios.jornada != 'Baja'";
        }

        $sentencia_sql = "
    SELECT DATE_FORMAT(usuarios_horarios.fecha_fin,'%H:%i:%s')- 
        DATE_FORMAT(usuarios_horarios.fecha_inicio,'%H:%i:%s') AS horas
    FROM usuarios_horarios 
    LEFT JOIN usuarios on usuarios.id_usuario = usuarios_horarios.id_usuario 
    WHERE 
      (DATE_FORMAT(usuarios_horarios.fecha_inicio,'%Y-%m-%d') <= @fecha AND DATE_FORMAT(usuarios_horarios.fecha_fin,'%Y-%m-%d') >= @fecha) 
      AND usuarios_horarios.id_usuario = @id_usuario 
      AND usuarios_horarios.borrado = 0 
      $buscar
    ";

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return ($datos[0]['horas'] > 0) ? $datos[0]['horas'] : 0;;
    }

    function empleado_horas_normales_total($parametros)
    {

        $fecha_desde  = date('Y-m-d', strtotime($parametros['fecha_desde']));
        $fecha_hasta  = date('Y-m-d', strtotime($parametros['fecha_hasta']));
        $horas = 0;

        for ($i = $fecha_desde; $i <= $fecha_hasta; $i = date("Y-m-d", strtotime($i . "+ 1 days"))) {

            if (isset($parametros['id_centro'])) {
                $id_centro = $parametros['id_centro'];
                // ... Leemos todos los empleados del centro.
                unset($parametros);
                $parametros['solo_empleados_con_horarios'] = 1;
                $parametros['fecha_agenda'] = $i;
                $parametros['id_centro']    = $id_centro;
                $empleados = $this->Usuarios_model->leer_usuarios($parametros);

                // ... Leemos horarios de trabajo de cada empleado.
                if ($empleados != 0) {
                    foreach ($empleados as $key => $row) {
                        unset($parametros);
                        $parametros['id_usuario'] = $row['id_usuario'];
                        $parametros['fecha']      = $i;
                        $horas += $this->horas_de_empleado_en_dia($parametros);
                    }
                }
            }

            if (isset($parametros['id_empleado'])) {
                $parametros['fecha'] = $i;
                $parametros['id_usuario'] = $parametros['id_empleado'];
                $horas += $this->horas_de_empleado_en_dia($parametros);
            }
        }
        return $horas;
    }

    function empleado_horas_extra_total($parametros)
    {
        $fecha_desde  = date('Y-m-d', strtotime($parametros['fecha_desde']));
        $fecha_hasta  = date('Y-m-d', strtotime($parametros['fecha_hasta']));
        $horas = 0;

        for ($i = $fecha_desde; $i <= $fecha_hasta; $i = date("Y-m-d", strtotime($i . "+ 1 days"))) {

            if (isset($parametros['id_centro'])) {
                $id_centro = $parametros['id_centro'];
                // ... Leemos todos los empleados del centro.
                unset($parametros);
                $parametros['solo_empleados_con_horarios'] = 1;
                $parametros['fecha_agenda'] = $i;
                $parametros['id_centro']    = $id_centro;
                $empleados = $this->Usuarios_model->leer_usuarios($parametros);

                // ... Leemos horarios de trabajo de cada empleado.
                if ($empleados != 0) {
                    foreach ($empleados as $key => $row) {
                        unset($parametros);
                        $parametros['id_usuario'] = $row['id_usuario'];
                        $parametros['fecha']      = $i;
                        $parametros['jornada']    = ["Extra"];
                        $horas += $this->horas_de_empleado_en_dia($parametros);
                    }
                }
            }

            if (isset($parametros['id_empleado'])) {
                $parametros['fecha'] = $i;
                $parametros['id_usuario'] = $parametros['id_empleado'];
                $parametros['jornada']    = ["Extra"];
                $horas += $this->horas_de_empleado_en_dia($parametros);
            }
        }
        return $horas;
    }

    function empleado_dias_vacaciones($parametros)
    {

        $AqConexion_model = new AqConexion_model();
        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND usuarios_horarios.id_usuario = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND usuarios_horarios.id_usuario IN
      (select id_usuario from usuarios where (usuarios.id_centro = @id_centro))";
        }
        $sentencia_sql = "
    SELECT 
      IFNULL(SUM(DATEDIFF( usuarios_horarios.fecha_fin, usuarios_horarios.fecha_inicio) + 1),0) as dias
    FROM usuarios_horarios
    LEFT JOIN usuarios on usuarios.id_usuario = usuarios_horarios.id_usuario
    WHERE  DATE_FORMAT(usuarios_horarios.fecha_inicio,'%Y-%m-%d') > @fecha_desde 
      AND DATE_FORMAT(usuarios_horarios.fecha_fin,'%Y-%m-%d') < @fecha_hasta 
      AND usuarios_horarios.jornada = 'Vacaciones' 
      AND usuarios_horarios.borrado = 0
      $buscar
    ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['dias'];
    }

    function empleado_dias_baja($parametros)
    {

        $AqConexion_model = new AqConexion_model();
        $buscar = "";
        if (isset($parametros['id_empleado'])) {
            $buscar .= " AND usuarios_horarios.id_usuario = @id_empleado ";
        }
        if (isset($parametros['id_centro'])) {
            $buscar .= " AND usuarios_horarios.id_usuario IN
      (select id_usuario from usuarios where (usuarios.id_centro = @id_centro))";
        }
        $sentencia_sql = "
    SELECT 
      IFNULL(SUM(DATEDIFF(usuarios_horarios.fecha_fin, usuarios_horarios.fecha_inicio) + 1),0) as dias
    FROM usuarios_horarios
    LEFT JOIN usuarios on usuarios.id_usuario = usuarios_horarios.id_usuario
    WHERE DATE_FORMAT(usuarios_horarios.fecha_inicio,'%Y-%m-%d') > @fecha_desde 
      AND DATE_FORMAT(usuarios_horarios.fecha_fin,'%Y-%m-%d') < @fecha_hasta 
      AND usuarios_horarios.jornada = 'Baja' 
      AND usuarios_horarios.borrado = 0
      $buscar
    ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['dias'];
    }

    function semanas_horas($parametros)
    {
        $fecha_inicio = strtotime($parametros['fecha_desde']);
        $fecha_final = strtotime($parametros['fecha_hasta']);
        $param['id_usuario'] = $parametros['id_empleado'];
        $empleado = $this->Usuarios_model->leer_usuarios($param);

        for ($i = $fecha_inicio; $i <= $fecha_final; $i += 86400 * 7) {
            $week_start = date("Y-m-d", strtotime('monday this week', $i));
            $week_end = date("Y-m-d", strtotime('sunday this week', $i));
            $semanas[] = [
                'week_start' => $week_start,
                'week_end'  => $week_end
            ];
        }

        foreach ($semanas as $key => $semana) {
            $horas_asignadas = 0;
            $fecha_desde  = $semana['week_start'];
            $fecha_hasta  = $semana['week_end'];
            for ($i = $fecha_desde; $i <= $fecha_hasta; $i = date("Y-m-d", strtotime($i . "+ 1 days"))) {
                $param['fecha'] = $i;
                $param['id_usuario'] = $parametros['id_empleado'];
                //$param['jornada'] = 'Extra';
                $horas_asignadas += $this->horas_de_empleado_en_dia($param);
            }

            $semanas[$key]['horas_asignadas'] = $horas_asignadas;
            $horas_balance = $horas_asignadas - $empleado[0]['horas_semana'];
            $semanas[$key]['horas_balance'] = $horas_balance;
            $semanas[$key]['texto_balance'] = "(" . $empleado[0]['horas_semana'] . " / " . number_format($horas_asignadas, 1, '.', '') . ")";
        }
        return $semanas;
    }

    /**
     * 07/03/23
     * Incluye degub
     */

    function usuarios_debug($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda_cita = "";
        $busqueda_dietario = "";

        $centro = "";
        $centro_citas = "";
        $centro_usuario = " AND U.id_centro > 1 ";

        if (isset($parametros['id_centro'])) {
            $centro = " AND dietario.id_centro = @id_centro ";
            $centro_citas = " AND citas.id_usuario_empleado
      in (select id_usuario from usuarios where id_centro = @id_centro) ";
            $centro_usuario = " AND U.id_centro = @id_centro ";
        }

        if (isset($parametros['fecha_desde']) && isset($parametros['fecha_hasta'])) {
            $busqueda_cita = " AND citas.fecha_hora_inicio >= @fecha_desde AND citas.fecha_hora_inicio <= @fecha_hasta ";
            $busqueda_dietario = " AND dietario.fecha_hora_concepto >= @fecha_desde AND dietario.fecha_hora_concepto <= @fecha_hasta ";
        }

         if (isset($parametros['mes'])) {
            $busqueda_cita = " AND MONTH(citas.fecha_hora_inicio) = MONTH('".$parametros['mes']."') AND YEAR(citas.fecha_hora_inicio) = YEAR('".$parametros['mes']."')";
            $busqueda_dietario = " AND MONTH(dietario.fecha_hora_concepto) = MONTH('".$parametros['mes']."') AND YEAR(dietario.fecha_hora_concepto) = YEAR('".$parametros['mes']."')";

            if(isset($parametros['jornada'])) {
                if($parametros['jornada'] == 1){
                    $busqueda_cita .=" AND TIME(citas.fecha_hora_inicio) < '16:00:00' ";
                    $busqueda_dietario .= " AND TIME(dietario.fecha_hora_concepto) < '16:00:00' ";
                }
                if($parametros['jornada'] == 2){
                    $busqueda_cita .= " AND TIME(citas.fecha_hora_inicio) >= '16:00:00' ";
                    $busqueda_dietario .= " AND TIME(dietario.fecha_hora_concepto) >= '16:00:00' ";
                }
            }
        }

        // ... Leemos los registros
        $sentencia_sql = "select U.id_usuario,U.nombre,U.apellidos,centros.id_centro,
    centros.nombre_centro,perfiles.nombre_perfil,

    (select round(sum(duracion)/60,1) from citas where id_usuario_empleado =
    U.id_usuario and (citas.borrado = 0 OR (citas.borrado = 1 AND citas.debug = 1)) and citas.estado = 'Finalizado'
    $centro_citas $busqueda_cita)
    as horas_trabajadas,

    (SELECT IFNULL(sum(TIMESTAMPDIFF(minute, fecha_inicio, fecha_fin) / 60),0)
    FROM usuarios_horarios_desglose where id_usuario = U.id_usuario
    and fecha_inicio >= @fecha_desde
    and fecha_fin <= @fecha_hasta)
    AS tiempo_jornada,

    ((select sum(templos) from dietario where ((dietario.borrado = 0 OR (dietario.borrado = 1 AND dietario.debug = 1)) OR (dietario.borrado = 1 AND dietario.debug = 1))
    and dietario.id_empleado = U.id_usuario and dietario.id_servicio > 0
    and (dietario.estado = 'Pagado')
    $centro $busqueda_dietario) -
    (select IFNULL(sum(templos),0) from dietario where ((dietario.borrado = 0 OR (dietario.borrado = 1 AND dietario.debug = 1)) OR (dietario.borrado = 1 AND dietario.debug = 1))
    and dietario.id_empleado = U.id_usuario and dietario.id_servicio > 0
    and (dietario.estado = 'Devuelto')
    $centro $busqueda_dietario))
    as templos,

    (select round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia+pagado_paypal+pagado_tpv2+pagado_financiado),2)
    from dietario where ((dietario.borrado = 0 OR (dietario.borrado = 1 AND dietario.debug = 1)) OR (dietario.borrado = 1 AND dietario.debug = 1)) and dietario.id_empleado = U.id_usuario
    and dietario.id_producto > 0
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    $centro $busqueda_dietario)
    as ventas_productos,

    (select round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia+pagado_paypal+pagado_tpv2+pagado_financiado),2)
    from dietario where ((dietario.borrado = 0 OR (dietario.borrado = 1 AND dietario.debug = 1)) OR (dietario.borrado = 1 AND dietario.debug = 1)) and dietario.id_empleado = U.id_usuario
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    $centro $busqueda_dietario)
    as ventas,

    (select round(sum(pagado_efectivo),2)
    from dietario where ((dietario.borrado = 0 OR (dietario.borrado = 1 AND dietario.debug = 1)) OR (dietario.borrado = 1 AND dietario.debug = 1)) and dietario.id_empleado = U.id_usuario
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    $centro $busqueda_dietario)
    as ventas_efectivo,

    (select round(sum(pagado_tarjeta),2)
    from dietario where ((dietario.borrado = 0 OR (dietario.borrado = 1 AND dietario.debug = 1)) OR (dietario.borrado = 1 AND dietario.debug = 1)) and dietario.id_empleado = U.id_usuario
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    $centro $busqueda_dietario)
    as ventas_tarjeta,
    
    (select round(sum(pagado_transferencia),2)
    from dietario where ((dietario.borrado = 0 OR (dietario.borrado = 1 AND dietario.debug = 1)) OR (dietario.borrado = 1 AND dietario.debug = 1)) and dietario.id_empleado = U.id_usuario
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    $centro $busqueda_dietario)
    as ventas_transferencia,

    (select round(sum(pagado_paypal),2)
    from dietario where ((dietario.borrado = 0 OR (dietario.borrado = 1 AND dietario.debug = 1)) OR (dietario.borrado = 1 AND dietario.debug = 1)) and dietario.id_empleado = U.id_usuario
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    $centro $busqueda_dietario)
    as ventas_paypal,
    
    (select round(sum(pagado_tpv2),2)
    from dietario where ((dietario.borrado = 0 OR (dietario.borrado = 1 AND dietario.debug = 1)) OR (dietario.borrado = 1 AND dietario.debug = 1)) and dietario.id_empleado = U.id_usuario
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    $centro $busqueda_dietario)
    as ventas_tpv2,

    (select round(sum(pagado_financiado),2)
    from dietario where ((dietario.borrado = 0 OR (dietario.borrado = 1 AND dietario.debug = 1)) OR (dietario.borrado = 1 AND dietario.debug = 1)) 
    and dietario.id_empleado = U.id_usuario
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    $centro $busqueda_dietario)
    as ventas_financiado,
    
    (select round(sum(pagado_habitacion),2)
    from dietario where ((dietario.borrado = 0 OR (dietario.borrado = 1 AND dietario.debug = 1)) OR (dietario.borrado = 1 AND dietario.debug = 1)) and dietario.id_empleado = U.id_usuario
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    $centro $busqueda_dietario)
    as ventas_habitacion,

    (select round(sum(servicios.precio_proveedor),2)
    from dietario
    LEFT JOIN servicios on servicios.id_servicio = dietario.id_servicio
    where ((dietario.borrado = 0 OR (dietario.borrado = 1 AND dietario.debug = 1)) OR (dietario.borrado = 1 AND dietario.debug = 1)) and dietario.id_empleado = U.id_usuario
    AND dietario.id_servicio IN
    (select id_servicio from servicios where id_familia_servicio = 12)
    and (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
    $centro $busqueda_dietario)
    as ventas_proveedores

    from usuarios as U
    left join (usuarios_perfiles left join perfiles on perfiles.id_perfil = usuarios_perfiles.id_perfil) on usuarios_perfiles.id_usuario = U.id_usuario
    left join centros on centros.id_centro = U.id_centro
    where 1=1 $centro_usuario and (usuarios_perfiles.id_perfil > 0)
    ORDER BY U.nombre,U.apellidos ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }


    function produccion_citas($parametros)
    {

        $AqConexion_model = new AqConexion_model();
        $busqueda = " AND C.estado = 'Finalizado' AND (dietario.estado = 'Pagado' OR dietario.estado = 'Presupuesto')";
        if (isset($parametros['id_centro'])) {
            if ($parametros['id_centro'] > 0) {
                $busqueda .= " AND dietario.id_centro = @id_centro ";
            }
        }

        if (isset($parametros['fecha'])) {
            if ($parametros['fecha'] != "") {
                $busqueda .= " AND DATE_FORMAT(C.fecha_hora_inicio,'%Y-%m-%d') = @fecha ";
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
        servicios.pvp,
        servicios.templos,
        dietario.importe_euros,
        (servicios.pvp - dietario.importe_euros) AS descontado
        FROM citas AS C
        LEFT JOIN servicios on servicios.id_servicio = C.id_servicio
        LEFT JOIN usuarios on usuarios.id_usuario = C.id_usuario_empleado
        LEFT JOIN dietario on dietario.id_cita = C.id_cita
        WHERE C.borrado = 0 " . $busqueda . " GROUP BY C.id_cita ORDER BY C.fecha_hora_inicio";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);
        return $datos;

    }

    //Fin con debug


    //function venta_carnets_nuevos($parametros) {
    //  $AqConexion_model = new AqConexion_model();
    //
    //  $tipos_carnets="";
    //  if ($parametros['carnets'] == "templos") {
    //    $tipos_carnets .= " D2.id_tipo <> 99 and ";
    //  }
    //  else {
    //    $tipos_carnets .= " D2.id_tipo = 99 and ";
    //  }
    //
    //  // ... Leemos los registros
    //  $sentencia_sql="
    //  SELECT count(D.id_dietario) as nuevo
    //    FROM ventas_carnets as D
    //    WHERE
    //      D.id_empleado = @id_empleado and
    //      D.fecha_hora_concepto >= @fecha_desde and
    //      D.fecha_hora_concepto <= @fecha_hasta and
    //      D.id_tipo = @id_tipo and
    //      D.id_cliente not in
    //      (
    //        select D2.id_cliente
    //        from ventas_carnets As D2
    //        where
    //          D2.fecha_hora_concepto >= '2016-11-01' and
    //          D2.fecha_hora_concepto <= D.fecha_hora_concepto and
    //          $tipos_carnets
    //          D2.id_cliente = D.id_cliente and
    //          D2.id_dietario <> D.id_dietario
    //      )
    //  ";
    //  $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    //
    //  return $datos[0]['nuevo'];
    //}

    //function venta_carnets_superior($parametros) {
    //  $AqConexion_model = new AqConexion_model();
    //
    //   ... Leemos los registros
    //  $sentencia_sql="
    //  SELECT count(D.id_dietario) as superior
    //    FROM ventas_carnets AS D
    //    WHERE
    //      D.id_empleado = @id_empleado and
    //      D.fecha_hora_concepto >= @fecha_desde and
    //      D.fecha_hora_concepto <= @fecha_hasta and
    //      D.id_tipo = @id_tipo and
    //      D.id_cliente in
    //      (
    //        select D2.id_cliente
    //        from ventas_carnets As D2
    //        where
    //          D2.fecha_hora_concepto >= '2016-11-01' and
    //          D2.fecha_hora_concepto <= D.fecha_hora_concepto and
    //          D2.importe_euros < D.importe_euros and
    //          D2.id_cliente = D.id_cliente and
    //          D2.id_dietario <> D.id_dietario
    //      )
    //  ";
    //  $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    //
    //  return $datos[0]['superior'];
    //}

}
