<?php
class Facturacion_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	function get_pedidos($parametros)
	{

		$query = $this->db->select('
			id_pedido,
			fecha_pedido,
			fecha_entrega
		')
		->where('id_centro', $parametros['id_centro'])
		->where('fecha_entrega >=', $parametros['fecha_desde'])
		->where('fecha_entrega <=', $parametros['fecha_hasta'])
    ->where('estado', 'Entregado')
		->get('pedidos')
		->result();

		foreach ($query as $key => $value) {
			$value->total_factura = $this->total_factura_pedido($value->id_pedido);
		}

		return $query;
	}

	function total_factura_pedido($id_pedido)
	{
		$this->db->select_sum('(precio_franquiciado_sin_iva*cantidad)', 'total_facturacion');
		$this->db->where('pedidos_productos.id_pedido', $id_pedido);
		$this->db->where('pedidos_productos.borrado', 0);
		$this->db->join('productos', 'productos.id_producto = pedidos_productos.id_producto');
		return $this->db->get('pedidos_productos')->row()->total_facturacion;
	}

	function  productos_en_pedido($id_pedido)
	{
		$this->db->select('
			pedidos_productos.id_pedido AS ref,
			productos_familias.nombre_familia AS familia,
			productos.nombre_producto AS producto,
			pedidos_productos.cantidad_entregada AS cantidad,
			productos.precio_franquiciado_sin_iva AS precio_sin_iva,
		');
		$this->db->where('pedidos_productos.id_pedido', $id_pedido);
		$this->db->where('pedidos_productos.borrado', 0);
		$this->db->order_by('productos.nombre_producto', 'asc');
		$this->db->join('productos', 'productos.id_producto = pedidos_productos.id_producto');
		$this->db->join('productos_familias', 'productos.id_familia_producto = productos_familias.id_familia_producto');
		return $this->db->get('pedidos_productos')->result();
	}


	function get_citas_online($parametros)
	{
		$this->db->select('
			dietario.fecha_hora_concepto AS fecha,
			CONCAT(clientes.nombre," ", clientes.apellidos) AS cliente,
			servicios.nombre_servicio AS servicio,
			CONCAT(usuarios.nombre," ", usuarios.apellidos) AS empleado,
			dietario.importe_euros AS precio_con_iva,
		', FALSE);

		$this->db->where('dietario.id_centro', $parametros['id_centro']);
		$this->db->where('dietario.id_pedido >', 0);
		$this->db->where('dietario.estado', 'Pagado');
		$this->db->where('dietario.borrado', 0);
		$this->db->where('dietario.tipo_pago', '#tarjeta');
		$this->db->where('dietario.fecha_hora_concepto >=', $parametros['fecha_desde']);
		$this->db->where('dietario.fecha_hora_concepto <=', $parametros['fecha_hasta']);
		$this->db->order_by('dietario.fecha_hora_concepto', 'asc');
		$this->db->join('usuarios', 'usuarios.id_usuario = dietario.id_empleado');
		$this->db->join('servicios', 'servicios.id_servicio = dietario.id_servicio');
		$this->db->join('clientes', 'clientes.id_cliente = dietario.id_cliente');
		
		return $this->db->get('dietario')->result();
	}


	// -------------------------------------------------------------------
  // ... INTERCENTROS
  // -------------------------------------------------------------------

	/*if (isset($parametros['nombre_centro'])) {
      $busqueda.=" AND (centros.nombre_centro LIKE @nombre_centro OR C2.nombre_centro LIKE @nombre_centro)";
    }*/
  function intercentros($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";
    
   /* if (isset($parametros['id_centro'])) {
      $busqueda.=" AND D.id_centro = @id_centro ";
    }*/
    
    if (isset($parametros['id_cen_carnet'])) {
      $busqueda.=" AND CT.id_centro = @id_cen_carnet ";
    }
    
    if (isset($parametros['fecha_desde']) && isset($parametros['fecha_hasta'])) {
      $busqueda.=" AND D.fecha_hora_concepto >= @fecha_desde
      AND D.fecha_hora_concepto <= @fecha_hasta ";      
    }

    if (isset($parametros['nombre_centro'])) {
      $busqueda.=" AND (centros.nombre_centro LIKE @nombre_centro OR C2.nombre_centro LIKE @nombre_centro)";
    }
    
    // ... Leemos los registros
    $sentencia_sql="SELECT D.fecha_hora_concepto,S.abreviatura as servicio,
    CT.codigo,centros.nombre_centro as original_de,C2.nombre_centro as usado_en,
    round((CT.precio/CT.templos)*(select sum(templos) from carnets_templos_historial
    where id_dietario = D.id_dietario and id_carnet = C.id_carnet),2) as total,
    (select sum(templos) from carnets_templos_historial where id_dietario = D.id_dietario
    and id_carnet = C.id_carnet)    
    as templos,
    DATE_FORMAT(D.fecha_hora_concepto,'%H:%i') as hora,
    DATE_FORMAT(D.fecha_hora_concepto,'%d-%m-%Y') as fecha,centros.id_centro,
    (select IFNULL(round((CT.precio/CT.templos)*SUM(templos_disponibles_anteriores),2),0)
    from carnets_templos_ajustes where id_carnet = C.id_carnet and borrado = 0
    and id_centro = @id_centro) as total_sin_recargas
    from carnets_templos_historial as C
    left join dietario as D on D.id_dietario = C.id_dietario
    left join carnets_templos as CT  on CT.id_carnet = C.id_carnet
    left join centros on centros.id_centro = CT.id_centro
    left join centros as C2 on C2.id_centro = D.id_centro
    left join servicios as S on S.id_servicio = D.id_servicio
    where C.borrado = 0 and D.borrado = 0 and C.id_dietario > 0     
    and D.id_servicio > 0
    and CT.id_centro <> D.id_centro ".$busqueda."
    
    UNION ALL 
    
    SELECT D.fecha_hora_concepto,S.abreviatura as servicio,CT.codigo,
    centros.nombre_centro as original_de,C2.nombre_centro as usado_en,    
    round(
      (
      round(CT.precio /
      (SELECT sum(servicios.templos) FROM carnets_templos_servicios
      left join servicios on servicios.id_servicio = carnets_templos_servicios.id_servicio
      where id_carnet = CT.id_carnet and carnets_templos_servicios.borrado = 0),2)
      *
      D.templos
      )
    ,2) as total,D.templos,
    DATE_FORMAT(D.fecha_hora_concepto,'%H:%i') as hora,
    DATE_FORMAT(D.fecha_hora_concepto,'%d-%m-%Y') as fecha,centros.id_centro,
    0 as total_sin_recargas
    from carnets_templos_servicios as C
    left join dietario as D on D.id_dietario = C.id_dietario
    left join carnets_templos as CT on CT.id_carnet = C.id_carnet
    left join centros on centros.id_centro = CT.id_centro
    left join centros as C2 on C2.id_centro = D.id_centro
    left join servicios as S on S.id_servicio = D.id_servicio
    where C.borrado = 0 and D.borrado = 0 and C.id_dietario > 0     
    and D.id_servicio > 0
    and CT.id_centro <> D.id_centro ".$busqueda."
    
    ORDER BY fecha_hora_concepto asc";
    
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    return $datos;
  }

}
