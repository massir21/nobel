<?php
class Informes_model extends CI_Model {

  function __construct() {
    parent::__construct();
  }

  function precio_servicios($id_carnet) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";

    if (isset($parametros['id_carnet'])) {
      if ($parametros['id_carnet'] > 0) {
        $busqueda.=" AND C.id_carnet = @id_carnet ";
      }
    }

    if (isset($parametros['gastado'])) {
      $busqueda.=" AND C.gastado = @gastado ";
    }

    $sentencia_sql="
    SELECT
      SUM(pvp) as precio
    FROM
      carnets_templos_servicios AS C
    WHERE
      C.borrado = 0 and
      C.id_carnet = @id_carnet
    ";

    $parametros['id_carnet']=$id_carnet;
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos[0]['precio'];
  }

  function propietario_carnet($id_dietario) {
    $AqConexion_model = new AqConexion_model();

    $sentencia_sql="
    SELECT
      CONCAT(clientes.nombre, ' ', clientes.apellidos) As cliente
    FROM
      pagos_cliente_distinto_carnet
      left join clientes on clientes.id_cliente = pagos_cliente_distinto_carnet.id_cliente
    WHERE
      id_dietario = @id_dietario
    ";

    $parametros['id_dietario']=$id_dietario;
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos[0]['cliente'];
  }

  function pedidos_abandonados($fecha) {
    $AqConexion_model = new AqConexion_model();

    $sentencia_sql="
    SELECT
      C.id_pedido,C.personas,C.id_servicio,C.pvp as importe,C.templos,C.id_centro,C.id_usuario_empleado,
      C.medaigual,C.id_cliente,DATE_FORMAT(C.fecha_hora_inicio,'%Y-%m-%d %H:%i') as fecha_hora_inicio,
      C.observaciones,C.notas_cliente,
      C.fecha_creacion,CONCAT(clientes.nombre, ' ', clientes.apellidos) As cliente,
      CONCAT(usuarios.nombre, ' ', usuarios.apellidos) As empleado,nombre_centro as centro,
      servicios.nombre_servicio as servicio,clientes.email,clientes.id_cliente
    FROM
      citas_temporales as C
        left join clientes on clientes.id_cliente = C.id_cliente
        left join servicios on servicios.id_servicio = C.id_servicio
        left join usuarios on usuarios.id_usuario = C.id_usuario_empleado
        left join centros on centros.id_centro = C.id_centro
        left join citas_pedidos on citas_pedidos.id_pedido = C.id_pedido
    WHERE
      C.borrado = 0 and
      citas_pedidos.estado = 'Abandonado' and
      DATE_FORMAT(C.fecha_creacion,'%Y-%m-%d') = @fecha
    ";

    $parametros['fecha']=$fecha;
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  function leer_pedidos_abandonados($id_cliente,$id_servicio,$fecha) {
    $AqConexion_model = new AqConexion_model();

    $servicio="";

    if ($id_servicio > 0) {
      $servicio = " C.id_servicio = @id_servicio AND ";
    }

    $sentencia_sql="
    SELECT
      C.id_cita,C.id_pedido,C.personas,C.id_servicio,C.pvp as importe,C.templos,C.id_centro,C.id_usuario_empleado,
      C.medaigual,C.id_cliente,DATE_FORMAT(C.fecha_hora_inicio,'%Y-%m-%d %H:%i') as fecha_hora_inicio,
      C.observaciones,C.notas_cliente,
      C.fecha_creacion,CONCAT(clientes.nombre, ' ', clientes.apellidos) As cliente,
      CONCAT(usuarios.nombre, ' ', usuarios.apellidos) As empleado,nombre_centro as centro,
      servicios.nombre_servicio as servicio,clientes.email,clientes.id_cliente
    FROM
      citas_temporales as C
        left join clientes on clientes.id_cliente = C.id_cliente
        left join servicios on servicios.id_servicio = C.id_servicio
        left join usuarios on usuarios.id_usuario = C.id_usuario_empleado
        left join centros on centros.id_centro = C.id_centro
        left join citas_pedidos on citas_pedidos.id_pedido = C.id_pedido
    WHERE
      C.borrado = 0 AND
      citas_pedidos.estado = 'Abandonado' AND
      citas_pedidos.id_cliente = @id_cliente AND
      {$servicio}
      C.fecha_creacion >= (@fecha - INTERVAL 8 hour)
        AND
      C.fecha_creacion <= @fecha
    ";

    $parametros['id_cliente']=$id_cliente;
    $parametros['id_servicio']=$id_servicio;
    $parametros['fecha']=$fecha;

    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  /**
   *
   * Devuelve las citas programadas online
   *
   * @param $id_cliente
   * @param $id_servicio
   * @param $fecha
   * @param $pedido_online
   *
   * @return array
   */
  function citas_programadas_online($id_cliente,$id_servicio,$fecha,$pedido_online) {
    $AqConexion_model = new AqConexion_model();

    $online = "";
    $servicio = "";

    if ($pedido_online == 0)
    {
      $online = " dietario.id_pedido = 0 ";
    }
    else {
      $online = " dietario.id_pedido > 0 ";
    }

    if ($id_servicio > 0) {
      $servicio = " citas.id_servicio = @id_servicio AND ";
    }

    $sentencia_sql="
    SELECT
      citas.id_cliente,citas.id_servicio,citas.fecha_hora_inicio
    FROM citas
      LEFT JOIN dietario ON dietario.id_cita = citas.id_cita
    WHERE
      citas.borrado = 0 AND
      citas.fecha_hora_inicio >= (@fecha - INTERVAL 8 hour) AND
      citas.id_cliente = @id_cliente AND
      {$servicio}
      (citas.estado = 'Programada' or citas.estado = 'Finalizado') AND
      {$online}
    ";

    $parametros['id_cliente']=$id_cliente;
    $parametros['id_servicio']=$id_servicio;
    $parametros['fecha']=$fecha;

    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  function clientes_citas_abandonadas($fecha) {
    $AqConexion_model = new AqConexion_model();

    $sentencia_sql="
      SELECT
        DISTINCT citas_temporales.id_cliente
      FROM
        citas_temporales
        left join citas_pedidos on citas_pedidos.id_pedido = citas_temporales.id_pedido
      WHERE
        citas_temporales.borrado = 0 AND
        citas_pedidos.estado = 'Abandonado' AND
        citas_temporales.fecha_creacion >= (@fecha - INTERVAL 8 hour)
        AND
        citas_temporales.fecha_creacion <= @fecha
      ORDER BY
        citas_temporales.id_cliente
    ";

    $parametros['fecha']=$fecha;
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  function borrar_cita_temporal($id_cita) {
    $AqConexion_model = new AqConexion_model();

    $registro['borrado']=1;
    $where['id_cita']=$id_cita;

    $AqConexion_model->update('citas_temporales',$registro,$where);
  }

  function cumpleannos_clientes($fecha) {
    $AqConexion_model = new AqConexion_model();

    $sentencia_sql="
      SELECT
        id_cliente,nombre,apellidos,email,DATE_FORMAT(fecha_nacimiento,'%Y-%m-%d') as fecha_nacimiento,
        DATE_FORMAT(fecha_nacimiento,'%Y') as anno_nacimiento,
        TIMESTAMPDIFF(YEAR,fecha_nacimiento,(CURDATE() + INTERVAL 1 DAY)) AS edad,no_quiere_publicidad
      FROM
        clientes
      WHERE
        borrado = 0 AND
        DATE_FORMAT(fecha_nacimiento,'%m-%d') = @fecha
      ORDER BY
        apellidos,nombre
    ";

    $parametros['fecha']=$fecha;
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }



  /*
   *
   *
   * Próximas citas de cleintes
   *
   */

  function filtro_citas_pendientes($id_cliente){
      $fecha = date('Y-m-d', strtotime('+1 day',strtotime(date('Y-m-d'))));
      $this->db->where('id_cliente', $id_cliente);
      $this->db->where('fecha_hora_inicio >=', $fecha);
      $this->db->where('estado', 'Programada');
      $this->db->where('borrado', 0);
      $citas = $this->db->get('citas')->num_rows();
      if($citas > 0){
          $return = false;
      }else{
          $result = true;
      }
      return $result;
  }

  function filtro_carnet_templos($id_cliente){
      $this->db->where('id_cliente', $id_cliente);
      $this->db->where('templos >=', 40);
      $this->db->where('templos_disponibles >', 0);
      $carnets = $this->db->get('carnets_templos')->num_rows();
      if($carnets > 0){
          $return = false;
      }else{
          $return = true;
      }
      return $return;
  }



  /*
   *
   * Clientes registrados y no verificados desde una fecha y hora concreta.
   *
   */
  function clientes_registrados_no_verificados($fecha) {
    $AqConexion_model = new AqConexion_model();

    $sentencia_sql="
      SELECT
        id_cliente,nombre,apellidos,email,telefono,fecha_creacion,activo
      FROM clientes
      WHERE
        borrado = 0 and
	(activo is null or activo = '') and
        fecha_creacion >= @fecha AND
        email <> '' AND
        password <> ''
      ORDER BY apellidos,nombre,fecha_creacion
    ";

    $parametros['fecha']=$fecha;
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  /*
   *
   * Clientes registrados y verificados desde una fecha y hora concreta.
   *
   */
  function clientes_registrados_verificados($fecha) {
    $AqConexion_model = new AqConexion_model();

    $sentencia_sql="
      SELECT
        id_cliente,nombre,apellidos,email,telefono,fecha_creacion,activo
      FROM clientes
      WHERE
        borrado = 0 and
	activo = 1 and
        fecha_activacion >= @fecha
      ORDER BY apellidos,nombre,fecha_creacion
    ";

    $parametros['fecha']=$fecha;
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  /*
   *
   * Clientes registrados cuya fecha de alta no coincida con la fecha de registro.
   *
   */
  function clientes_registrados_fecha_alta($fecha) {
    $AqConexion_model = new AqConexion_model();

    $sentencia_sql="
      SELECT
        id_cliente,nombre,apellidos,email,telefono,fecha_creacion,fecha_activacion,activo
      FROM clientes
      WHERE
	activo = 1 and
        fecha_activacion >= @fecha AND
        DATE_FORMAT(fecha_activacion,'%Y-%m-%d') <> DATE_FORMAT(fecha_creacion,'%Y-%m-%d')
      ORDER BY apellidos,nombre,fecha_creacion
    ";

    $parametros['fecha']=$fecha;
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  /*
   *
   * Clientes con telefono igual al indicando-
   * Se excluye de la busqueda al cliente del cual se quiere saber
   * que clientes tienen telefono igual.
   *
   */
  function clientes_telefono_igual($id_cliente_excluir,$telefono) {
    $AqConexion_model = new AqConexion_model();

    $sentencia_sql="
      SELECT
        id_cliente,nombre,apellidos,email,telefono,fecha_creacion,activo,
        {$id_cliente_excluir} as id_cliente_principal
      FROM
        clientes
      WHERE
	telefono = @telefono and
        id_cliente <> @id_cliente_excluir
      ORDER BY
        apellidos,nombre,fecha_creacion
    ";

    $parametros['id_cliente_excluir']=$id_cliente_excluir;
    $parametros['telefono']=$telefono;
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }


  /*
   *
   * Leer los cambios de numero de carnet por el master
   * en un rango de fechas y para un centro concreto
   *
   */
  function cambios_carnets($parametros)
  {
    $AqConexion_model = new AqConexion_model();

    $sentencia_sql="
      SELECT
	codigo_nuevo,codigo_anterior,carnets_templos.id_centro
      FROM
	carnets_templos_cambios as C
	LEFT JOIN carnets_templos ON carnets_templos.id_carnet = C.id_carnet
      WHERE
	carnets_templos.borrado = 0 and
        DATE_FORMAT(C.fecha_creacion,'%Y-%m-%d') >= @fecha_desde and
        DATE_FORMAT(C.fecha_creacion,'%Y-%m-%d') <= @fecha_hasta and
        carnets_templos.id_centro = @id_centro
      ORDER BY C.fecha_creacion DESC
    ";

    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  /*
   *
   * Leer los ajustes sin pasar por caja en carnets
   *
   */
  function ajustes_carnets_templos($parametros)
  {
    $AqConexion_model = new AqConexion_model();

    $sentencia_sql="
      SELECT
        carnets_templos.codigo,
	(C.templos_disponibles - C.templos_disponibles_anteriores) as recarga,
        C.templos_disponibles,C.templos_disponibles_anteriores,
        upper(CONCAT(clientes.nombre, ' ', clientes.apellidos)) As cliente,
        upper(CONCAT(usuarios.nombre, ' ', usuarios.apellidos)) As empleado
      FROM
	carnets_templos_ajustes as C
          LEFT JOIN (carnets_templos left join clientes on clientes.id_cliente = carnets_templos.id_cliente)
            ON carnets_templos.id_carnet = C.id_carnet
          LEFT JOIN usuarios on usuarios.id_usuario = C.id_usuario_creador
      WHERE
	C.borrado = 0 and
        C.sin_pasar_por_caja = 1 and
        DATE_FORMAT(C.fecha_creacion,'%Y-%m-%d') >= @fecha_desde and
        DATE_FORMAT(C.fecha_creacion,'%Y-%m-%d') <= @fecha_hasta and
        carnets_templos.id_centro = @id_centro
      ORDER BY C.fecha_creacion DESC
    ";

    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  /*
   *
   * Leer los ajustes en servicios sin pasar por caja
   *
   */
  function ajustes_carnets_servicios($parametros)
  {
    $AqConexion_model = new AqConexion_model();

    $sentencia_sql="
      SELECT
        carnets_templos.codigo,
        servicios.nombre_servicio,
        upper(CONCAT(clientes.nombre, ' ', clientes.apellidos)) As cliente,
        upper(CONCAT(usuarios.nombre, ' ', usuarios.apellidos)) As empleado
      FROM
	carnets_templos_servicios as C
	LEFT JOIN (carnets_templos left join clientes on clientes.id_cliente = carnets_templos.id_cliente)
        ON carnets_templos.id_carnet = C.id_carnet
        left join servicios on servicios.id_servicio = C.id_servicio
        LEFT JOIN usuarios on usuarios.id_usuario = C.id_usuario_creador
      WHERE
        C.borrado = 0 and
        C.sin_pasar_por_caja = 1 and
        DATE_FORMAT(C.fecha_creacion,'%Y-%m-%d') >= @fecha_desde and
        DATE_FORMAT(C.fecha_creacion,'%Y-%m-%d') <= @fecha_hasta and
        carnets_templos.id_centro = @id_centro
      ORDER BY C.fecha_creacion DESC
    ";

    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  /*
   *
   * Cambio de saldo manual.
   *
   */
  function cambios_saldo_manual($parametros)
  {
    $AqConexion_model = new AqConexion_model();

    $sentencia_sql="
      SELECT
        saldo_inicial_anterior,saldo_inicial_nuevo,
        upper(CONCAT(usuarios.nombre, ' ', usuarios.apellidos)) As empleado
      FROM
	cajas_saldos_iniciales as C
        LEFT JOIN usuarios on usuarios.id_usuario = C.id_usuario_creacion
      WHERE
        C.borrado = 0 and
        C.motivo <> 'Cierre de caja' and
        DATE_FORMAT(C.fecha_creacion,'%Y-%m-%d') >= @fecha_desde and
        DATE_FORMAT(C.fecha_creacion,'%Y-%m-%d') <= @fecha_hasta and
        C.id_centro = @id_centro
      ORDER BY C.fecha_creacion DESC
    ";

    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  /*
   *
   * Lee los codigos de tienda woocommerce que han sido usados con un carnet generado
   * a partir de este codigo y canjeado por un empleado, los canjeados por el propio
   * cliente desde su panel de control no los tiene en cuenta.
   *
   */
  function codigos_tienda_usados_por_carnets($fecha)
  {
    $AqConexion_model = new AqConexion_model();

    $sentencia_sql="
    SELECT
      carnets_templos.codigo_tienda,carnets_templos.id_cliente,
      clientes.nombre,clientes.apellidos,clientes.email
    FROM
      carnets_templos left join clientes on clientes.id_cliente = carnets_templos.id_cliente
    WHERE carnets_templos.codigo_tienda in
    (
      SELECT
        C.codigo_tienda
      FROM
        carnets_templos_servicios as CS
        LEFT JOIN (carnets_templos as C left join clientes on clientes.id_cliente = C.id_cliente)
        ON C.id_carnet = CS.id_carnet
        LEFT JOIN dietario as D ON D.id_dietario = CS.id_dietario
      WHERE
        C.codigo_tienda <> '' and
        C.id_usuario_creador > 0 and
        DATE_FORMAT(D.fecha_pagado,'%Y-%m-%d') = @fecha

    UNION

      SELECT
        C.codigo_tienda
      FROM
        carnets_templos_historial as CS
        LEFT JOIN (carnets_templos as C left join clientes on clientes.id_cliente = C.id_cliente)
        ON C.id_carnet = CS.id_carnet
        LEFT JOIN dietario as D ON D.id_dietario = CS.id_dietario
      WHERE
        C.codigo_tienda <> '' and
        C.id_usuario_creador > 0 and
        DATE_FORMAT(D.fecha_pagado,'%Y-%m-%d') = @fecha
    )
    ";

    $parametros['fecha']=$fecha;

    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  /*
   * Determina si un codigo de tienda (woocommerce) canjeado ha sido
   * ya usado a traves de carnets en la extranet en base a una fecha dada.
   */
  function codigo_tienda_usado($fecha,$codigo_tienda)
  {
    $AqConexion_model = new AqConexion_model();

    $sentencia_sql="
      SELECT
        C.id_cliente,C.codigo_tienda,clientes.nombre,clientes.apellidos,clientes.email
      FROM
        carnets_templos_servicios as CS
        LEFT JOIN (carnets_templos as C left join clientes on clientes.id_cliente = C.id_cliente)
        ON C.id_carnet = CS.id_carnet
        LEFT JOIN dietario as D ON D.id_dietario = CS.id_dietario
      WHERE
        C.codigo_tienda = @codigo_tienda and
        C.id_usuario_creador > 0 and
        DATE_FORMAT(D.fecha_pagado,'%Y-%m-%d') < @fecha

    UNION

      SELECT
        C.id_cliente,C.codigo_tienda,clientes.nombre,clientes.apellidos,clientes.email
      FROM
        carnets_templos_historial as CS
        LEFT JOIN (carnets_templos as C left join clientes on clientes.id_cliente = C.id_cliente)
        ON C.id_carnet = CS.id_carnet
        LEFT JOIN dietario as D ON D.id_dietario = CS.id_dietario
      WHERE
        C.codigo_tienda = @codigo_tienda and
        C.id_usuario_creador > 0 and
        DATE_FORMAT(D.fecha_pagado,'%Y-%m-%d') < @fecha
    ";

    $parametros['fecha']=$fecha;
    $parametros['codigo_tienda']=$codigo_tienda;

    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    if ($datos != 0)
    {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }


  /*
  *
  *
  * Busca los registros en el dietario de clientes con notas de cobro sin finalizar
  * Devuelve el id del dietario, el id del empleado que lo gestiona la nota de cobro, el ti
  *
  */

  function caja_notas_pago($param)
  {

    // que se busca
    $this->db->select("
      dietario.fecha_pagado,
      CONCAT(clientes.nombre, ' ', clientes.apellidos) AS cliente,
      CONCAT(usuarios.nombre, ' ', usuarios.apellidos) AS empleado,
      dietario.tipo_pago,
      dietario.id_carnet,
      clientes_notas_cobrar.nota
    ");
    // centro y fecha
    $this->db->where('dietario.id_centro', $param['id_centro']);
    $this->db->where('dietario.fecha_pagado >=', $param['fecha_desde']);
    $this->db->where('dietario.fecha_pagado <=', $param['fecha_hasta']);

    //notas_cobro
    $this->db->where('clientes_notas_cobrar.estado', 'Pendiente');

    // join a otras tablas
    $this->db->join('clientes_notas_cobrar', 'clientes_notas_cobrar.id_cliente = dietario.id_cliente');
    $this->db->join('usuarios', 'usuarios.id_usuario = dietario.id_empleado');
    $this->db->join('clientes', 'clientes.id_cliente = dietario.id_cliente');
    $this->db->from('dietario');
    return $this->db->get()->result_array();
  }

}
?>
