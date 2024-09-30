<?php
class Tienda_model extends CI_Model {  
  function __construct() {
    parent::__construct();    
  }
  
  /**
  * Devuelve el post_id en base a un codigo de la tienda
  * 
  * @param string $codigo
  * 
  * @return int $post_id
  */
  function post_id($codigo) {
    // Conectando con la BBDD de la tienda.
    $link = mysqli_connect('localhost', 'cirilofl_beauty', 'JTM7ALzMe6wu', 'cirilofl_beautyonline') or die('No se pudo conectar: ' . mysqli_error());
    mysqli_select_db($link,'cirilofl_beautyonline') or die('No se pudo seleccionar la base de datos');
    
    // Preparamos los parametros.
    $codigo = $this->db->escape($codigo);
    
    $query = "
    SELECT
      id
    FROM wp_posts
      WHERE
        post_status = 'wc-completed' and
        id in (SELECT post_id FROM wp_postmeta WHERE meta_value = ".$codigo.")
    ";
    
    $result = mysqli_query($link,$query) or die('Consulta fallida: ' . mysqli_error($link));

    if (mysqli_num_rows($result) > 0) {
      mysqli_close($link);
      
      return $this->mysqli_result($result, 0, "id");
    }
    else {
      mysqli_close($link);
      
      return 0;
    }    
  }
  
  /**
  * Devuelve el precio total de un pedido en base al codigo.
  * 
  * @param string $codigo
  * 
  * @return decimal $total
  */
  function total_pedido($codigo) {
    // Conectando con la BBDD de la tienda.
    $link = mysqli_connect('localhost', 'cirilofl_beauty', 'JTM7ALzMe6wu', 'cirilofl_beautyonline') or die('No se pudo conectar: ' . mysqli_error());
    mysqli_select_db($link,'cirilofl_beautyonline') or die('No se pudo seleccionar la base de datos');
    
    $post_id=$this->post_id($codigo);
    
    $query = "
    SELECT
      meta_value
    FROM
      wp_postmeta
    WHERE
      post_id = ".$post_id." and
      meta_key = '_order_total'
    ";
    
    $result = mysqli_query($link,$query) or die('Consulta fallida: ' . mysqli_error($link));

    if (mysqli_num_rows($result) > 0) {
      mysqli_close($link);
      
      return $this->mysqli_result($result, 0, "meta_value");
    }
    else {
      mysqli_close($link);
      
      return 0;
    }    
  }
  
  /**
  * Devuelve cada uno de los productos asociados la post_id
  * con su product_id, variation_id y cantidad.
  * 
  * @param int $productos
  * 
  * @return hast_array $post_id
  */
  function order_item_ids($post_id) {
    // Conectando con la BBDD de la tienda.
    $link = mysqli_connect('localhost', 'cirilofl_beauty', 'JTM7ALzMe6wu', 'cirilofl_beautyonline') or die('No se pudo conectar: ' . mysqli_error());
    mysqli_select_db($link,'cirilofl_beautyonline') or die('No se pudo seleccionar la base de datos');
    
    // Preparamos los parametros.
    $post_id = $this->db->escape($post_id);
    
    $query = "
      SELECT
        order_item_id 
      FROM
        wp_woocommerce_order_items
      WHERE
	order_id = ".$post_id." and
	order_item_type = 'line_item'
    ";    
    $result = mysqli_query($link,$query) or die('Consulta fallida: ' . mysqli_error($link));

    if (mysqli_num_rows($result) > 0) {
      mysqli_close($link);
      
      $productos = array();
      
      $i=0;
      
      while ($row = $result->fetch_assoc()) {
        $item=$this->producto_id($row['order_item_id']);
        
        if ($item!=0)
        {          
          if (($item['_product_id']>0 || $item['_variation_id']>0) && $item['_qty']>0)
          {
            $productos[$i]['_product_id']=$item['_product_id'];
            $productos[$i]['_variation_id']=$item['_variation_id'];
            $productos[$i]['_qty']=$item['_qty'];
            
            $i++;          
          }
        }
      }      
      
      return $productos;
    }
    else {
      mysqli_close($link);
      
      return 0;
    }    
  }
  
  /**
  * Devuelve el producto asociado al order_item_id indicado  
  * 
  * @param int $order_item_id
  * 
  * @return hast_array $producto
  */
  function producto_id($order_item_id) {
    // Conectando con la BBDD de la tienda.
    $link = mysqli_connect('localhost', 'cirilofl_beauty', 'JTM7ALzMe6wu', 'cirilofl_beautyonline') or die('No se pudo conectar: ' . mysqli_error());
    mysqli_select_db($link,'cirilofl_beautyonline') or die('No se pudo seleccionar la base de datos');
    
    // Preparamos los parametros.
    $order_item_id = $this->db->escape($order_item_id);
    
    $query = "
      SELECT
        *
      FROM
        wp_woocommerce_order_itemmeta
      WHERE
        order_item_id = ".$order_item_id." and 
        (
          meta_key = '_product_id' or
          meta_key = '_variation_id' or
          meta_key='_qty'
        )
    ";    
    $result = mysqli_query($link,$query) or die('Consulta fallida: ' . mysqli_error($link));

    if (mysqli_num_rows($result) > 0) {      
      mysqli_close($link);
      
      $product_id=0;
      $variation_id=0;
      $qty=0;
      
      while ($row = $result->fetch_assoc()) {        
        if ($row["meta_key"]=="_product_id")
        {
          $product_id=$row["meta_value"];
        }
        if ($row["meta_key"]=="_variation_id")
        {
          $variation_id=$row["meta_value"];
        }
        if ($row["meta_key"]=="_qty")
        {
          $qty=$row["meta_value"];
        }        
      }
      
      if (($product_id>0 || $variation_id>0) && $qty>0)
      {
        $productos['_product_id']=$product_id;
        $productos['_variation_id']=$variation_id;
        $productos['_qty']=$qty;        
      }
      
      return $productos;   
    }
    else {
      mysqli_close($link);
      
      return 0;
    }    
  }
  
  private function mysqli_result($res, $row, $field=0) {
    $res->data_seek($row); 
    $datarow = $res->fetch_array();
    
    return $datarow[$field]; 
  }
  
  /**
  * Determina si un codigo de la tienda ya ha sido procesado
  * y por tanto ha generado un carnet.
  * 
  * @param string $codigo
  * 
  * @return boolean
  */
  function existe_codigo($codigo) {
    $AqConexion_model = new AqConexion_model();

    $sentencia_sql="
    SELECT
      id_carnet 
    FROM
      carnets_templos    
    WHERE
      borrado = 0 and
      codigo_tienda = @codigo
    ";
    
    $parametros['codigo']=$codigo;
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    $r1 = FALSE;    
    if ($datos!=0) {
      $r1 = TRUE;
    }

    // Comprobar codigo tienda online plugin antiguo
    $r2 = $this->existe_codigo_plugin_tienda($codigo);
    
    if ($r1 || $r2)
    {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }
  
  /**
  * Determina el id_item que finalmente se comparara con
  * los datos de tipos de carnets de la extranet y servicios.  
  * 
  * @param hast $item
  * 
  * @return int $id_item
  */
  function item_id($item) {
    if ($item['_variation_id'] > 0)
    {
      return $item['_variation_id'];
    }
    else
    {
      return $item['_product_id'];
    }  
  }
  
  /**
  * Determina si el id_item pasado corresponde a un tipo de carnet o no  
  * 
  * @param int $id_item
  * 
  * @return int $id_tipo_carnet o 0 sino lo es
  */
  function es_carnet_templos($id_item) {
    $AqConexion_model = new AqConexion_model();
    
    $sentencia_sql="
    SELECT
      id_tipo 
    FROM
      carnets_templos_tipos    
    WHERE      
      id_tienda = @id_item
    ";
    
    $parametros['id_item']=$id_item;    
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    if ($datos!=0) {
      return $datos[0]['id_tipo'];
    }
    else {
      return 0;
    }    
  }
  
  /**
  * Determina si el id_item pasado corresponde a un servicio o no  
  * 
  * @param int $id_item
  * 
  * @return int $id_servicio o 0 sino lo es
  */
  function es_servicio($id_item) {
    $AqConexion_model = new AqConexion_model();
    
    $sentencia_sql="
    SELECT
      id_servicio
    FROM
      servicios_tienda
    WHERE      
      id_tienda = @id_item
    ";
    
    $parametros['id_item']=$id_item;
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    if ($datos!=0) {
      return $datos[0]['id_servicio'];
    }
    else {
      return 0;
    }    
  }
  
  /**
  * Determina si el id_item pasado corresponde a un pack de tienda
  * 
  * @param int $id_item
  * 
  * @return int $id_pack o 0 sino lo es
  */
  function es_pack($id_item) {
    $AqConexion_model = new AqConexion_model();
    
    $sentencia_sql="
    SELECT
      id_pack
    FROM
      packs_tienda
    WHERE      
      id_tienda = @id_item
    ";
    
    $parametros['id_item']=$id_item;
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    if ($datos!=0) {
      return $datos[0]['id_pack'];
    }
    else {
      return 0;
    }    
  }
  
  /**
  * Devuelve un hash_array con todos los items correspondientes a la extranet
  * 
  * @param string $items
  * 
  * @return hast_array $items_extranet
  */
  function correspondencias_tienda($items) {    
    $items_extranet = array();
    $i=0;
    
    foreach ($items as $row)
    {      
      $id_item=$this->item_id($row);      
      $id_tipo_carnet=$this->es_carnet_templos($id_item);
      $id_servicio=$this->es_servicio($id_item);
      $id_pack=$this->es_pack($id_item);
      $cantidad=$row['_qty'];
      
      if (($id_servicio > 0 || $id_tipo_carnet > 0 || $id_pack > 0) && $cantidad > 0)
      {
        $descripcion="";
        
        if ($id_tipo_carnet > 0)
        {
          unset($param);
          $param['id_tipo']=$id_tipo_carnet;
          $tipo=$this->tipos_carnets($param);
          $descripcion="Carnet ".$tipo[0]['descripcion'];
          $link_encuesta=$tipo[0]['link_encuesta'];
        }
        
        else if ($id_servicio > 0)
        {
          unset($param);
          $param['id_servicio']=$id_servicio;
          $servicio=$this->Servicios_model->leer_servicios($param);
          $descripcion=(isset($servicio) && is_array($servicio)) ? $servicio[0]['nombre_servicio']: '';
          $link_encuesta=(isset($servicio) && is_array($servicio)) ? $servicio[0]['link_encuesta']: '';
        }
        
        else
        {          
          $pack=$this->Tienda_model->leer_packs($id_pack);
          $descripcion=$pack[0]['nombre_pack'];
          $link_encuesta=$pack[0]['link_encuesta'];
        }
        
        if ($link_encuesta == "")
        {
          $link_encuesta="#";
        }
        
        $items_extranet[$i]['id_tipo_carnet']=$id_tipo_carnet;
        $items_extranet[$i]['id_servicio']=$id_servicio;
        $items_extranet[$i]['id_pack']=$id_pack;
        $items_extranet[$i]['cantidad']=$cantidad;
        $items_extranet[$i]['descripcion']=$descripcion;
        $items_extranet[$i]['link_encuesta']=$link_encuesta;
        
        $i++;
      }
    }
    
    if (count($items_extranet) > 0)
    {
      return $items_extranet;
    }
    else {
      return 0;
    }
  }
  
  /*
  * Crear un carnet templos desde codigo tienda
  *
  * @items array
  * @id_cliente int
  * $codigo string
  * 
  * Return array ids_carnets
  *
  */
  function crear_carnet_templos($items,$id_cliente,$codigo)
  {
    $AqConexion_model = new AqConexion_model();
    
    $carnets = array();
    $x=0;
    
    foreach ($items as $row) {
      for ($i=0; $i<$row['cantidad']; $i++)
      {
        unset($param);
        $param['id_tipo']=$row['id_tipo'];
        $tipo=$this->tipos_carnets($param);
        
        $carnet['id_tipo']=$row['id_tipo'];
        $carnet['codigo']=$this->siguiente_codigo_carnet($tipo[0]['codigo']);
        $carnet['codigo_tienda']=$codigo;
        $carnet['templos']=$tipo[0]['templos'];
        $carnet['templos_disponibles']=$tipo[0]['templos'];
        $carnet['id_cliente']=$id_cliente;
        $carnet['id_centro']=1;
        $carnet['precio']=$tipo[0]['precio'];
        $carnet['sin_pasar_caja']=0;
        $carnet['activo_online']=1;
        $carnet['fecha_creacion']=date("Y-m-d H:i:s");
        $carnet['id_usuario_creador']=$this->session->userdata('id_usuario');
        $carnet['fecha_modificacion']=date("Y-m-d H:i:s");
        $carnet['id_usuario_modificacion']=$this->session->userdata('id_usuario');
        $carnet['borrado']=0;
        
        $AqConexion_model->insert("carnets_templos",$carnet);
        
        $id_carnet = $this->db->insert_id();
        
        $carnets[$x]['id_carnet']=$id_carnet;
        $x++;
      }
    }
    
    return $carnets;
  }
  
  
  /*
  * Crear un carnet especial desde codigo tienda
  *
  * @items array
  * @id_cliente int
  * $codigo string
  * 
  * Return integer id_carnet
  *
  */
  function crear_carnet_especial($items,$id_cliente,$codigo)
  {
    $AqConexion_model = new AqConexion_model();
    
    if ($items!= 0 && $id_cliente > 0)
    {
      $total=$this->total_pedido($codigo);
      
      $carnet['id_tipo']=99;
      $carnet['codigo']=$this->siguiente_codigo_carnet("E");
      $carnet['codigo_tienda']=$codigo;
      $carnet['templos']=0;
      $carnet['templos_disponibles']=0;
      $carnet['id_cliente']=$id_cliente;
      $carnet['id_centro']=1;
      $carnet['precio']=$total;
      $carnet['sin_pasar_caja']=0;
      $carnet['activo_online']=1;
      $carnet['fecha_creacion']=date("Y-m-d H:i:s");
      $carnet['id_usuario_creador']=$this->session->userdata('id_usuario');
      $carnet['fecha_modificacion']=date("Y-m-d H:i:s");
      $carnet['id_usuario_modificacion']=$this->session->userdata('id_usuario');
      $carnet['borrado']=0;
      
      $AqConexion_model->insert("carnets_templos",$carnet);
      
      $id_carnet = $this->db->insert_id();
      
      //
      // Metemos cada uno de los servicios o packs asociados al carnet.
      //
      unset($carnet);
      foreach ($items as $row) {
        // ... Servicios  
        if ($row['id_servicio'] > 0)
        {
          for ($i=0; $i<$row['cantidad']; $i++)
          {
            unset($param);
            $param['id_servicio']=$row['id_servicio'];
            $servicio=$this->Servicios_model->leer_servicios($param);
            
            $carnet['id_carnet']=$id_carnet;
            $carnet['id_dietario']=0;
            $carnet['id_servicio']=$row['id_servicio'];          
            $carnet['id_cliente']=$id_cliente;
            $carnet['id_centro']=1;
            $carnet['gastado']=0;
            $carnet['pvp']=(isset($servicio) && is_array($servicio)) ? $servicio[0]['pvp'] : '';
            $carnet['fecha_creacion']=date("Y-m-d H:i:s");
            $carnet['id_usuario_creador']=$this->session->userdata('id_usuario');
            $carnet['fecha_modificacion']=date("Y-m-d H:i:s");
            $carnet['id_usuario_modificacion']=$this->session->userdata('id_usuario');
            $carnet['borrado']=0;
            
            $AqConexion_model->insert("carnets_templos_servicios",$carnet);        
          }        
        }
        
        // ... Packs
        if ($row['id_pack'] > 0)
        {
          for ($i=0; $i<$row['cantidad']; $i++)
          {
            $servicios_pack=$this->leer_servicios_pack($row['id_pack']);
            
            if ($servicios_pack !=0 )
            {
              foreach ($servicios_pack as $servicio_row)
              {
                unset($param);
                $param['id_servicio']=$servicio_row['id_servicio'];
                $servicio=$this->Servicios_model->leer_servicios($param);
                
                $carnet['id_carnet']=$id_carnet;
                $carnet['id_dietario']=0;
                $carnet['id_servicio']=$servicio_row['id_servicio'];          
                $carnet['id_cliente']=$id_cliente;
                $carnet['id_centro']=1;
                $carnet['gastado']=0;
                $carnet['pvp']=$servicio[0]['pvp'];
                $carnet['fecha_creacion']=date("Y-m-d H:i:s");
                $carnet['id_usuario_creador']=$this->session->userdata('id_usuario');
                $carnet['fecha_modificacion']=date("Y-m-d H:i:s");
                $carnet['id_usuario_modificacion']=$this->session->userdata('id_usuario');
                $carnet['borrado']=0;
                
                $AqConexion_model->insert("carnets_templos_servicios",$carnet);        
              }
            }
          }
          
        }
      }
      
      return $id_carnet;
    }    
  }
  
  //
  function siguiente_codigo_carnet($codigo) {
    $AqConexion_model = new AqConexion_model();
  
    $parametros['id_centro']=1;
    
    $sentencia_sql="SELECT tienda_carnet FROM centros
    WHERE borrado = 0 and id_centro = @id_centro";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    $numero_carnet=$datos[0]['tienda_carnet']+1;
    
    $sentenciaSQL="update centros set tienda_carnet = tienda_carnet + 1
    where id_centro = @id_centro";
    $AqConexion_model->no_select($sentenciaSQL,$parametros);
    
    if ($numero_carnet<10) { $numero_carnet="000".$numero_carnet; }
    if ($numero_carnet>9 && $numero_carnet<100) { $numero_carnet="00".$numero_carnet; }
    if ($numero_carnet>99 && $numero_carnet<1000) { $numero_carnet="0".$numero_carnet; }
    
    $r=$numero_carnet.$codigo."_WEB";
    
    return $r;
  }
  
  function tipos_carnets($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";
    
    if (isset($parametros['id_tipo'])) {
      if ($parametros['id_tipo'] > 0) {
        $busqueda.=" AND C.id_tipo = @id_tipo ";
      }
    }
    
    if (isset($parametros['id_tipo_padre'])) {
      if ($parametros['id_tipo_padre'] > 0) {
        $busqueda.=" AND C.id_tipo_padre = @id_tipo_padre ";
      }
    }
    
    // ... Leemos los registros
    $sentencia_sql="SELECT C.id_tipo,C.descripcion,C.templos,C.precio,C.codigo,
    C.id_tienda,C.link_encuesta,
    C.id_usuario_creador,C.fecha_creacion,C.id_usuario_modificacion,
    C.fecha_modificacion,C.borrado,C.id_usuario_borrado,C.fecha_borrado,
    (select numero + 1 from carnets_codigos_centros where
    id_centro = @id_centro and id_tipo = C.id_tipo)
    as numero_siguiente,C.id_tipo_padre
    FROM carnets_templos_tipos AS C
    WHERE 1=1 ".$busqueda." ORDER BY C.templos,C.id_tipo ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    return $datos;
  }
  
  function leer_packs($id_pack) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";
    
    if (isset($id_pack)) {
      if ($id_pack > 0) {
        $busqueda.=" AND id_pack = @id_pack ";
      }
    }
    
    // ... Leemos los registros
    $sentencia_sql="SELECT id_pack,nombre_pack,id_tienda,link_encuesta,
    id_usuario_creador,fecha_creacion,id_usuario_modificacion,
    fecha_modificacion,borrado,id_usuario_borrado,fecha_borrado
    FROM packs_tienda
    WHERE borrado = 0 ".$busqueda." ORDER BY nombre_pack ";
    
    $parametros['id_pack']=$id_pack;
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    return $datos;
  }
  
  /*
  * Lee todos los servicios vinculados a un pack.
  *
  * @id_pack int  
  * 
  * Return array servicios
  *
  */
  function leer_servicios_pack($id_pack) {
    $AqConexion_model = new AqConexion_model();

    // ... Leemos los registros
    $sentencia_sql="SELECT PS.id_pack,PS.id_servicio,
    servicios.nombre_servicio,servicios.duracion,
    servicios_familias.nombre_familia
    FROM packs_servicios as PS
    LEFT JOIN (servicios LEFT JOIN servicios_familias
      ON servicios_familias.id_familia_servicio = servicios.id_familia_servicio)
    ON servicios.id_servicio = PS.id_servicio
    WHERE id_pack = @id_pack ORDER BY servicios.nombre_servicio ";
    
    $parametros['id_pack']=$id_pack;
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    return $datos;
  }
  
  /*
  * Crear un pack online con los servicios de la extranet asociados.
  *
  * @nombre_pack string
  * @id_tienda int
  * @link_encuesta string
  * @servicios array
  * 
  * Return int id_pack
  *
  */
  function guardar_pack($nombre_pack,$id_tienda,$link_encuesta,$servicios)
  {
    $AqConexion_model = new AqConexion_model();
    
    $pack['nombre_pack']=$nombre_pack;
    $pack['id_tienda']=$id_tienda;
    $pack['link_encuesta']=$link_encuesta;    
    $pack['fecha_creacion']=date("Y-m-d H:i:s");
    $pack['id_usuario_creador']=$this->session->userdata('id_usuario');
    $pack['fecha_modificacion']=date("Y-m-d H:i:s");
    $pack['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    $pack['borrado']=0;
    
    $AqConexion_model->insert("packs_tienda",$pack);
    
    $id_pack = $this->db->insert_id();
    
    // .. Guardamos los servicios asociados.
    if ($servicios!=0)
    {
      foreach ($servicios as $id_servicio) {
        $servicio['id_pack']=$id_pack;
        $servicio['id_servicio']=$id_servicio;
        
        $AqConexion_model->insert("packs_servicios",$servicio);      
      }
    }
    
    return $id_pack;
  }
  
  /*
  * Actualizar n pack online con los servicios de la extraner asociados.
  *
  * @id_pack int
  * @nombre_pack string
  * @id_tienda int
  * @link_encuesta string
  * @servicios array
  * 
  * Return int id_pack
  *
  */
  function actualizar_pack($id_pack,$nombre_pack,$id_tienda,$link_encuesta,$servicios)
  {
    $AqConexion_model = new AqConexion_model();
    
    $pack['nombre_pack']=$nombre_pack;
    $pack['id_tienda']=$id_tienda;
    $pack['link_encuesta']=$link_encuesta;
    $pack['fecha_modificacion']=date("Y-m-d H:i:s");
    $pack['id_usuario_modificacion']=$this->session->userdata('id_usuario');        
    $where['id_pack']=$id_pack;        
    $AqConexion_model->update('packs_tienda',$pack,$where);
    
    // .. Guardamos los servicios asociados.
    if ($servicios!=0)
    {
      // Primero borramos todas las relaciones del pack con los servicios.
      $sentenciaSQL="DELETE FROM packs_servicios WHERE id_pack = @id_pack";
      $parametros['id_pack']=$id_pack;
      $AqConexion_model->no_select($sentenciaSQL,$parametros);
    
      foreach ($servicios as $id_servicio) {
        $servicio['id_pack']=$id_pack;
        $servicio['id_servicio']=$id_servicio;
        
        $AqConexion_model->insert("packs_servicios",$servicio);      
      }
    }
    
    return $id_pack;
  }
  
  /*
  * Borrar el pack indicado
  *
  * @id_pack int  
  * 
  * Return 1
  *
  */
  function borrar_pack($id_pack)
  {
    $AqConexion_model = new AqConexion_model();
    
    $parametros['id_pack']=$id_pack;
    
    $sentenciaSQL="UPDATE packs_tienda SET borrado = 1 WHERE id_pack = @id_pack";
    $AqConexion_model->no_select($sentenciaSQL,$parametros);
    
    $sentenciaSQL="DELETE FROM packs_servicios WHERE id_pack = @id_pack";  
    $AqConexion_model->no_select($sentenciaSQL,$parametros);
    
    return 1;
  }
  
  
  /**
  * Existe codigo en el plugin antiguo de la tienda online
  * 
  * @param string $codigo
  * 
  * @return bool
  */
  function existe_codigo_plugin_tienda($codigo) {
    // Conectando con la BBDD de la tienda.
    $link = mysqli_connect('localhost', 'cirilofl_beauty', 'JTM7ALzMe6wu', 'cirilofl_beautyonline') or die('No se pudo conectar: ' . mysqli_error());
    mysqli_select_db($link,'cirilofl_beautyonline') or die('No se pudo seleccionar la base de datos');
    
    // Preparamos los parametros.
    $codigo = $this->db->escape($codigo);
    
    $query = "
    SELECT
      codigo
    FROM
      codigos
    WHERE
      codigo = $codigo
    "; 
    
    $result = mysqli_query($link,$query) or die('Consulta fallida: ' . mysqli_error($link));

    if (mysqli_num_rows($result) > 0) {      
      return TRUE;
    }
    else {      
      return FALSE;
    }    
  }
  
  /**
  * Devuelve los datos del cliente que ha realizado la compra
  * de un codigo concreto
  * 
  * @param string $codigo
  * 
  * @return array
  */
  function datos_compra_cliente($codigo) {
    // Conectando con la BBDD de la tienda.
    $link = mysqli_connect('localhost', 'cirilofl_beauty', 'JTM7ALzMe6wu', 'cirilofl_beautyonline') or die('No se pudo conectar: ' . mysqli_error());
    mysqli_select_db($link,'cirilofl_beautyonline') or die('No se pudo seleccionar la base de datos');
    
    $codigo = $this->db->escape($codigo);
    
    $query = "
    SELECT
      meta_value,meta_key
    FROM
      wp_postmeta
    WHERE
      post_id = (SELECT post_id FROM wp_postmeta WHERE meta_value = ".$codigo.") and
      (meta_key = '_billing_email' or meta_key = '_billing_first_name' or
      meta_key = '_billing_last_name')
    ";
    
    $result = mysqli_query($link,$query) or die('Consulta fallida: ' . mysqli_error($link));

    if (mysqli_num_rows($result) > 0) {
      mysqli_close($link);
      
      $email="";
      $nombre="";
      $apellidos="";
      
      for ($i=0; $i<3; $i++)
      {
        if ($this->mysqli_result($result, $i, "meta_key") == '_billing_email')
        {
          $email = $this->mysqli_result($result, $i, "meta_value");
        }
        if ($this->mysqli_result($result, $i, "meta_key") == '_billing_first_name')
        {
          $nombre = $this->mysqli_result($result, $i, "meta_value");
        }
        if ($this->mysqli_result($result, $i, "meta_key") == '_billing_last_name')
        {
          $apellidos = $this->mysqli_result($result, $i, "meta_value");
        }
      }
      
      $datos['nombre']=$nombre;
      $datos['apellidos']=$apellidos;
      $datos['email']=$email;
      
      return $datos;
    }
    else {
      mysqli_close($link);
      
      return 0;
    }    
  }
  
  /**
  * Devuelve el barcode en base a un post_id
  * 
  * @param string $codigo
  * 
  * @return int $post_id
  */
  function post_id_barcode($post_id) {
    // Conectando con la BBDD de la tienda.
    $link = mysqli_connect('localhost', 'cirilofl_beauty', 'JTM7ALzMe6wu', 'cirilofl_beautyonline') or die('No se pudo conectar: ' . mysqli_error());
    mysqli_select_db($link,'cirilofl_beautyonline') or die('No se pudo seleccionar la base de datos');
    
    // Preparamos los parametros.
    $post_id = $this->db->escape($post_id);
    
    $query = "
      SELECT meta_value
      FROM wp_postmeta 
      left join wp_posts on wp_posts.id = wp_postmeta.post_id
      WHERE
        meta_key = '_barcode_text' and
        post_id = ".$post_id." and
        post_status = 'wc-completed'
    ";
    
    $result = mysqli_query($link,$query) or die('Consulta fallida: ' . mysqli_error($link));

    if (mysqli_num_rows($result) > 0) {
      mysqli_close($link);
      
      return $this->mysqli_result($result, 0, "meta_value");
    }
    else {
      mysqli_close($link);
      
      return 0;
    }    
  }
  
}
?>