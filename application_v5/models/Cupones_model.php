<?php
class Cupones_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /*
     * Listado de cupones.
     *
     */
    public function get_cupon($id_cupon)
    {
        $AqConexion_model = new AqConexion_model();
        $sentencia_sql = "SELECT *
            FROM cupones 
            WHERE
            id_cupon = @id_cupon
            AND borrado = 0 
            ";

        $parametros['id_cupon'] = $id_cupon;
        $datos = $AqConexion_model->select($sentencia_sql, $parametros); 
        return $datos[0];
    }

    public function get_cupones()
    {
        $AqConexion_model = new AqConexion_model();
        $sentencia_sql = "SELECT
            id_cupon,codigo_cupon,fecha_desde, fecha_hasta,descuento_euros,descuento_porcentaje
            FROM cupones 
            WHERE
            borrado = 0 
            ORDER BY id_cupon DESC
            ";

        $parametros['vacio'] = '';
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);
        foreach ($datos as $key => $value) {
          $datos[$key]['valido'] = $this->comprobar_cupon($value['id_cupon']);
        }
        return $datos;
    }

    public function add_cupon($datos)
    {    
        $AqConexion_model = new AqConexion_model();
        $AqConexion_model->insert('cupones',$datos);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    public function update_cupon($datos, $id_cupon)
    {
        //$this->db->update('cupones', $datos, "id_cupon =" . $id_cupon);

        $AqConexion_model = new AqConexion_model();

        $where['id_cupon']=$id_cupon;
        $AqConexion_model->update('cupones',$datos,$where);

        return ($this->db->affected_rows() != 1) ? false : true;
    }

    /// comprobar cupon con pedido
    public function get_cupon_codigo($codigo_cupon)
    {
        // datos del cupon
        return $this->db->get_where('cupones', ['codigo_cupon' => $codigo_cupon])->row_array();
    }

    public function usos_de_cupon($id_cupon, $id_cliente)
    {
        $cantidad_total = $this->db->get_where('cupones_usados', ['id_cupon' => $id_cupon], 'estado != 0')->num_rows();

        $cantidad_cliente = $this->db->get_where('cupones_usados', ['id_cupon' => $id_cupon, 'id_cliente' => $id_cliente], 'estado != 0')->num_rows();

        return ['cantidad_total' => $cantidad_total, 'cantidad_cliente' => $cantidad_cliente];
    }

    public function leer_pedido_codigo($codigo)
    {
        $AqConexion_model = new AqConexion_model();

        $sentencia_sql = "
    SELECT
      CP.id_pedido,CP.codigo,CP.id_cliente,CP.total,CP.templos,CP.estado,CP.fecha_creacion,CP.borrado,
      UPPER(CONCAT(clientes.nombre,' ',clientes.apellidos)) as cliente,CP.codigo_autorizacion
    FROM citas_pedidos as CP
      LEFT JOIN clientes on clientes.id_cliente = CP.id_cliente
    WHERE
      CP.borrado = 0 and
      CP.codigo = @codigo
    ";

        $parametros['codigo'] = $codigo;

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    public function servicios_en_pedido($id_pedido)
    {
        $this->db->select('id_servicio ');
        $this->db->distinct('id_servicio');
        $this->db->where('id_pedido', $id_pedido);
        $ids_servicios = $this->db->get('citas_temporales')->result_array();

        $array_id = [];
        foreach ($ids_servicios as $key => $value) {
            $array_id[] = $value['id_servicio'];
        }

        $this->db->select('id_familia_servicio');
        $this->db->distinct('id_familia_servicio');
        $this->db->where_in('id_servicio', $array_id);

        $ids_familias = $this->db->get('servicios')->result_array();

        $array_id_familias = [];
        foreach ($ids_familias as $key => $value) {
            $array_id_familias[] = $value['id_familia_servicio'];
        }

        return ['id_servicio' => $array_id, 'id_familia' => $array_id_familias];

    }

    public function citas_en_pedido($data)
    {
        if (isset($data['id_servicio'])) {
            $this->db->where('citas_temporales.id_servicio', $data['id_servicio']);
        }

        if (isset($data['id_familia_servicio'])) {
            $this->db->where('servicios.id_familia_servicio', $data['id_familia_servicio']);
            $this->db->join('servicios', 'servicios.id_servicio = citas_temporales.id_servicio');
        }

        $this->db->where('citas_temporales.id_pedido', $data['id_pedido']);
        $this->db->from('citas_temporales');
        return $this->db->get()->result_array();
    }

    public function actualizar_cita_temporal($id_cita, $datos)
    {
        //$this->db->update('citas_temporales', $datos, "id_cita =" . $id_cita);
        $AqConexion_model = new AqConexion_model();

        $where['id_cita']=$id_cita;
        $AqConexion_model->update('citas_temporales',$datos,$where);

    }

    public function importe_total_pedido($id_pedido)
    {
        $this->db->where('id_pedido', $id_pedido);
        $citas = $this->db->get('citas_temporales')->result();

        $total = 0;
        foreach ($citas as $key => $value) {
            $pre_total = $value->pvp - $value->descuento_euros;
            if ($value->descuento_porcentaje != 0) {
                $pre_total = $pre_total - (($value->descuento_porcentaje / 100) * $pre_total);
            }
            $total = $total + $pre_total;
        }
        return $total;
    }

    public function actualizar_pedido($id_pedido, $datos)
    {
        //$this->db->update('citas_pedidos', $datos, "id_pedido =" . $id_pedido);
        $AqConexion_model = new AqConexion_model();

        $where['id_pedido']=$id_pedido;
        $AqConexion_model->update('citas_pedidos',$datos,$where);
    }

    public function get_pedido($id_pedido)
    {
        return $this->db->get_where('citas_pedidos', ['id_pedido' => $id_pedido])->row_array();
    }

    public function add_cupon_usado($data)
    {
        
        $AqConexion_model = new AqConexion_model();
        $AqConexion_model->insert('cupones_usados',$data);
        $insertId = $this->db->insert_id();
        $this->session->set_userdata('id_cupon_usado', $insertId);
    }

    public function cupon_usado_pedido($id_cupon, $id_pedido)
    {
        $this->db->where('id_pedido', $id_pedido);
        $this->db->where('id_cupon', $id_cupon);
        $this->db->where('estado', $id_cupon);
        if ($this->db->get('cupones_usados')->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function cupones_usados_entre_fechas($parametros)
    {
        $fecha_desde = $parametros['fecha_desde'];
        $fecha_hasta = $parametros['fecha_hasta'];
        $id_centro = $parametros['id_centro'];

        $this->db->select("
            cupones.codigo_cupon,
            cupones_usados.id_cupon,
            cupones_usados.id_pedido,
            cupones_usados.id_cliente,
            cupones_usados.fecha_creacion,
            count(dietario.id_cita) AS id_cita,
            dietario.id_centro,
            dietario.id_servicio,
              sum(dietario.importe_euros) AS importe_euros,
              sum(dietario.pagado_tarjeta) AS pagado_tarjeta,
              sum(dietario.pagado_paypal) AS pagado_paypal,
              sum(dietario.descuento_euros) AS descuento_euros,
            dietario.descuento_porcentaje,
            centros.nombre_centro,
            CONCAT (clientes.nombre, ' ', clientes.apellidos) AS cliente,
            servicios.nombre_servicio,
            ",false);
        $this->db->group_by('cupones_usados.id_pedido');
        $this->db->where('cupones_usados.fecha_creacion >=', $fecha_desde);
        $this->db->where('cupones_usados.fecha_creacion <=', $fecha_hasta);
        $this->db->where('cupones_usados.borrado', 0);
        $this->db->where('cupones_usados.estado', 1);
        if((isset($id_centro)) && ($id_centro != 'todos')){
          $this->db->where('dietario.id_centro', $id_centro);
        }
        $this->db->where('(dietario.descuento_porcentaje != 0 OR dietario.descuento_euros != 0)');

        $this->db->join('cupones','cupones.id_cupon = cupones_usados.id_cupon');
        $this->db->join('dietario','dietario.id_pedido = cupones_usados.id_pedido');
        $this->db->join('centros','dietario.id_centro = centros.id_centro');
        $this->db->join('clientes','cupones_usados.id_cliente = clientes.id_cliente');
        $this->db->join('servicios','servicios.id_servicio = dietario.id_servicio');
        $this->db->from('cupones_usados');
        $cupones = $this->db->get()->result_array();

        return $cupones;

    }

    function comprobar_cupon($id_cupon)
    {
      $cupon = $this->get_cupon($id_cupon);

      //$cantidad_total = $this->db->get_where('cupones_usados', ['id_cupon' => $id_cupon], 'estado != 0')->num_rows();
      $cupon = $this->get_cupon($id_cupon);
      $this->db->where('id_cupon',$id_cupon);
      $this->db->where('estado !=',0);
      $cantidad_total = $this->db->get('cupones_usados')->num_rows();
      
      if ($cupon['fecha_desde'] >= date('Y-m-d H:i:s')) {
          // la fecha de inicio es posterior a la actual
          $respuesta = false;
      } elseif (($cupon['fecha_hasta'] != '0000-00-00 00:00:00') && ($cupon['fecha_hasta'] < date('Y-m-d H:i:s'))) {
          // La fecha de vencimiento ha pasado
          $respuesta = false;
      } elseif (($cupon['cantidad'] > 0) && ($cantidad_total >= $cupon['cantidad'])) {
          // si existe limite de usos u se supera
          $respuesta = false;
      } elseif (($cupon['cantidad_cliente'] > 0) && ($cantidad_total >= $cupon['cantidad_cliente'])) {
          // si existe limite de usos por cliente y se supera
          $respuesta = false;
      } else {
          $respuesta = true;
      }

      if($respuesta == true){
        return "SI";
      }else{
        return "NO";
      }
    }

    function search_cliente($nombre){
      $query = "SELECT id_cliente,nombre,apellidos, concat(nombre,' ',apellidos) AS nombre_completo
FROM clientes HAVING nombre_completo LIKE '$nombre'";

      return $this->db->query($query);
      //return $this->db->last_query();
    }

    function nombre_cliente($id_cliente){
      $this->db->where('id_cliente', $id_cliente);
      $cliente = $this->db->get('clientes')->row();
      return $cliente->nombre.' '.$cliente->apellidos. ' ('.$cliente->telefono.')';
    }

    function get_servicio($id_servicio){
      $this->db->where('id_servicio', $id_servicio);
      $cliente = $this->db->get('servicios')->row();
      return $cliente;
    }

    function get_familia($id_familia_servicio){
      $this->db->where('id_familia_servicio', $id_familia_servicio);
      $cliente = $this->db->get('servicios_familias')->row();
      return $cliente;
    }
    
    //31/03/20
      public function get_cupon_codigo_dos($codigo_cupon)
    {
        // datos del cupon
        return $this->db->get_where('cupones', ['codigo_cupon' => $codigo_cupon])->row_array();
    }

  //Fin
  
  //04/04/20
    public function add_cupon_usadoII($data)
    {
        
        //$cupon = $this->db->get_where('cupones_usados', ['id_pedido' => $data['id_pedido']])->row();

        //if(!isset($cupon)){
            $AqConexion_model = new AqConexion_model();
            $AqConexion_model->insert('cupones_usados',$data);
            $insertId = $this->db->insert_id();
            //$this->session->set_userdata('id_cupon_usado', $insertId);
        /*}else{
            $id_cupon_udsado = $cupon->id_cupon_udsado;
            $this->db->where('id_cupon_udsado', $id_cupon_udsado);
            $this->db->update('cupones_usados', $data);
        }
        */
    }
  //Fin
  
    

}
