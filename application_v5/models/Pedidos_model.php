<?php
class Pedidos_model extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  // -------------------------------------------------------------------
  // ... PEDIDOS
  // -------------------------------------------------------------------
  function leer_pedidos($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";
    
    if (isset($parametros['id_pedido'])) {
      $busqueda.=" AND pedidos.id_pedido = @id_pedido ";
    }
    
    if (isset($parametros['id_centro'])) {
      $busqueda.=" AND pedidos.id_centro = @id_centro ";
    }
    
    if (isset($parametros['estado'])) {
      $busqueda.=" AND pedidos.estado = @estado ";
    }
    
    if (isset($parametros['master'])) {
      $busqueda.=" AND pedidos.estado <> 'Sin Enviar' ";
    }
    
    // ... Leemos los registros
    $sentencia_sql="SELECT pedidos.id_pedido,pedidos.id_usuario,pedidos.id_centro,
    pedidos.estado,pedidos.fecha_pedido,pedidos.fecha_entrega,pedidos.total_factura,
    pedidos.id_usuario_creacion,pedidos.fecha_creacion,pedidos.id_usuario_modificacion,
    pedidos.fecha_modificacion,pedidos.borrado,pedidos.id_usuario_borrado,
    pedidos.fecha_borrado,usuarios.nombre,centros.nombre_centro,
    (select sum(precio_franquiciado_sin_iva*cantidad) from pedidos_productos
    left join productos on productos.id_producto = pedidos_productos.id_producto
    where id_pedido = pedidos.id_pedido and pedidos_productos.borrado = 0) as total_factura,
    DATE_FORMAT(pedidos.fecha_pedido,'%d-%m-%Y') as fecha_pedido_ddmmaaaa,
    DATE_FORMAT(pedidos.fecha_pedido,'%H:%i') as hora_pedido,
    DATE_FORMAT(pedidos.fecha_entrega,'%d-%m-%Y') as fecha_entrega_ddmmaaaa,
    DATE_FORMAT(pedidos.fecha_entrega,'%H:%i') as hora_entrega
    FROM pedidos
    LEFT JOIN usuarios on usuarios.id_usuario = pedidos.id_usuario
    LEFT JOIN centros on centros.id_centro = pedidos.id_centro
    WHERE pedidos.borrado = 0 and pedidos.estado <> 'PENDALTA'
    ".$busqueda." ORDER BY pedidos.id_pedido DESC ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    return $datos;
  }

  function nuevo_pedido($parametros) {    
    $AqConexion_model = new AqConexion_model();
        
    // ... Datos generales como pedido.
    $registro['id_usuario']=$this->session->userdata('id_usuario');
    $registro['id_centro']=$this->session->userdata('id_centro_usuario');
    $registro['estado']="PENDALTA";
    $registro['fecha_pedido']=date("Y-m-d H:i:s");
    $registro['total_factura']=0;
    //
    $registro['fecha_creacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_creacion']=$this->session->userdata('id_usuario');
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    $registro['borrado']=0;        
    
    $AqConexion_model->insert('pedidos',$registro);
    
    $sentenciaSQL="select max(id_pedido) as id_pedido from pedidos";
    $resultado = $AqConexion_model->select($sentenciaSQL,null);
    
    return $resultado[0]['id_pedido'];    
  }

  function actualizar_pedido($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    $param['id_pedido']=$parametros['id_pedido'];
    $datos = $this->leer_pedidos($param);
        
    // ... Datos generales como pedido.
    $registro['estado']=$parametros['estado'];
        
    if (($parametros['estado'] == "Entregado" || $parametros['estado'] == "Sin Terminar") && $datos[0]['estado']=="Sin Entregar") {
      $registro['fecha_entrega']=date("Y-m-d H:i:s");
    }
    
    if ($parametros['estado'] == "Sin Entregar") {
      $registro['fecha_entrega']=null;
    }
    
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_pedido');
    
    $where['id_pedido']=$parametros['id_pedido'];
    
    $AqConexion_model->update('pedidos',$registro,$where);
    
    return 1;    
  }

  function borrar_pedido($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    $parametros['id_usuario_borrado']=$this->session->userdata('id_usuario');
    $parametros['fecha_borrado']=date("Y-m-d H:i:s");
    
    $sentenciaSQL="update pedidos set borrado = 1,
    id_usuario_borrado = @id_usuario_borrado,
    fecha_borrado = @fecha_borrado
    where id_pedido = @id_pedido";
    $AqConexion_model->no_select($sentenciaSQL,$parametros);
    
    $sentenciaSQL="update pedidos_productos set borrado = 1,
    id_usuario_borrado = @id_usuario_borrado,
    fecha_borrado = @fecha_borrado
    where id_pedido = @id_pedido";
    $AqConexion_model->no_select($sentenciaSQL,$parametros);
    
    return 1;
  }
  
  function borrar_producto_pedido($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    $parametros['id_usuario_borrado']=$this->session->userdata('id_usuario');
    $parametros['fecha_borrado']=date("Y-m-d H:i:s");
    
    $sentenciaSQL="update pedidos_productos set borrado = 1,
    id_usuario_borrado = @id_usuario_borrado,
    fecha_borrado = @fecha_borrado
    where id = @id_producto_pedido";
    $AqConexion_model->no_select($sentenciaSQL,$parametros);
    
    return 1;
  }
  
  
  function leer_productos_pedido($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";
    
    if (isset($parametros['id_clave'])) {
      $busqueda.=" AND pedidos_productos.id = @id_clave ";
    }
    
    if (isset($parametros['id_pedido'])) {
      $busqueda.=" AND pedidos_productos.id_pedido = @id_pedido ";
    }
    
    if (isset($parametros['id_producto'])) {
      $busqueda.=" AND pedidos_productos.id_producto = @id_producto ";
    }
    
    if (isset($parametros['pendalta_no'])) {
      if ($parametros['pendalta_no']==1) {
        $busqueda.=" AND pedidos.estado <> 'PENDALTA' ";
      }
    }
    
    if (isset($parametros['id_centro'])) {
      $busqueda.=" AND pedidos.id_centro = @id_centro ";
    }
    else {
      $parametros['id_centro']=$this->session->userdata('id_centro_usuario');
    }
    
    if (isset($parametros['estado'])) {
      $busqueda.=" AND pedidos.estado = @estado ";
    }
    
    // ... Leemos los registros
    $sentencia_sql="SELECT pedidos_productos.id,pedidos_productos.id_pedido,
    pedidos_productos.id_producto,pedidos_productos.cantidad,
    pedidos_productos.cantidad_entregada,pedidos_productos.cantidad_pendiente,    
    pedidos_productos.id_usuario_creacion,pedidos_productos.fecha_creacion,
    pedidos_productos.id_usuario_modificacion,pedidos_productos.fecha_modificacion,
    pedidos_productos.borrado,pedidos_productos.id_usuario_borrado,
    pedidos_productos.fecha_borrado,productos.nombre_producto,productos_familias.nombre_familia,
    productos.pvp,productos.precio_franquiciado_sin_iva,productos_stock.cantidad_stock,
    pedidos.id_centro,centros.nombre_centro,pedidos.fecha_pedido,pedidos.fecha_entrega,
    pedidos.estado,
    (select sum(pvp*cantidad) from pedidos_productos AS PP
    left join productos AS P on P.id_producto = PP.id_producto
    where id_pedido = pedidos.id_pedido) as total_factura
    FROM pedidos_productos
    LEFT JOIN (pedidos left join centros on centros.id_centro = pedidos.id_centro)
    on pedidos.id_pedido = pedidos_productos.id_pedido
    LEFT JOIN (productos
    LEFT JOIN productos_familias on productos_familias.id_familia_producto = productos.id_familia_producto
    LEFT JOIN productos_stock on productos_stock.id_producto = productos.id_producto
    )
    on productos.id_producto = pedidos_productos.id_producto    
    WHERE pedidos.borrado = 0 and
    pedidos_productos.borrado = 0 and productos_stock.id_centro = @id_centro
    ".$busqueda." ORDER BY pedidos.id_pedido,productos.nombre_producto ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    return $datos;
  }
  
  function actualizar_pedido_productos($parametros) {
    $AqConexion_model = new AqConexion_model();
      
    $ids=$parametros['id'];
    $cantidad_entregada=$parametros['cantidad_entregada'];
    
    for ($i=0; $i<count($ids); $i++) {
      // ... leemos los datos del producto del pedido, antes de actualizar.
      unset($param);
      unset($pedido_producto);      
      $param['id_clave']=$ids[$i];
      $pedido_producto=$this->leer_productos_pedido($param);
      //
      if ($cantidad_entregada[$i]>$pedido_producto[0]['cantidad_pendiente']) {
        $cantidad_entregada[$i]=$pedido_producto[0]['cantidad_pendiente'];
      }
      //
      $registro['cantidad_entregada']=$cantidad_entregada[$i]+$pedido_producto[0]['cantidad_entregada'];
      $registro['cantidad_pendiente']=$pedido_producto[0]['cantidad_pendiente']-$cantidad_entregada[$i];
      //
      $registro['fecha_modificacion']=date("Y-m-d H:i:s");
      $registro['id_usuario_modificacion']=$this->session->userdata('id_pedido');
      
      $where['id']=$ids[$i];
      
      $AqConexion_model->update('pedidos_productos',$registro,$where);      
    }
    
    return 1;    
  }
  
  function actualizar_pedido_productos_sinenviar($parametros) {
    $AqConexion_model = new AqConexion_model();

    $ids=$parametros['id'];
    $cantidad=$parametros['cantidad'];
    
    unset($registro);
    
    for ($i=0; $i<count($ids); $i++) {      
      $registro['cantidad']=$cantidad[$i];
      $registro['cantidad_pendiente']=$cantidad[$i];      
      //
      $registro['fecha_modificacion']=date("Y-m-d H:i:s");
      $registro['id_usuario_modificacion']=$this->session->userdata('id_pedido');
      
      $where['id']=$ids[$i];
    
      $AqConexion_model->update('pedidos_productos',$registro,$where);      
    }
    
    return 1;    
  }
  
  function actualizar_stock_productos($parametros) {
    $AqConexion_model = new AqConexion_model();
      
    $id_pedido=$parametros['id_pedido'];
    $id_productos=$parametros['id_productos'];
    $cantidad=$parametros['cantidad'];
    
    for ($i=0; $i<count($id_productos); $i++) {
      unset($param);      
      $param['id_pedido']=$id_pedido;
      $param['id_producto']=$id_productos[$i];
      $pedido_producto=$this->leer_productos_pedido($param);
      
      // ... Solo actualizamos el stock, si la cantidad pedida actual es mayor
      // de la entregada actual, sino consideramos que eso ya se entrego por completo y
      // no se toca.
      if ($pedido_producto[0]['cantidad']>$pedido_producto[0]['cantidad_entregada']) {        
        // ... Si la cantidad que se indica para entregar es mayor de la cantidad pendiente
        // de pedido solicitada, automaticamente cambio la cantidad a entregar
        // a la misma pendiente del pedido, ya que no se puede servir más de lo pedido.
        // Si se pone eso, es porque se ha equivocado.        
        if ($cantidad[$i] > $pedido_producto[0]['cantidad_pendiente']) {
          $cantidad[$i]=$pedido_producto[0]['cantidad_pendiente'];
        }
        
        // ... Aqui actualizamos la cantidad a la indicada menos la ya entragada.
        unset($param);
        $param['id_producto']=$id_productos[$i];
        $param['cantidad']=$cantidad[$i];
        $param['id_centro']=$parametros['id_centro'];
        
        // ... resta al stock de la central siempre
        $sentenciaSQL=" update productos_stock set cantidad_stock = cantidad_stock - @cantidad
        where id_producto = @id_producto and id_centro = 1 ";
        $AqConexion_model->no_select($sentenciaSQL,$param);
        
        // ... suma al stock del centro que realizo el pedido
        $sentenciaSQL=" update productos_stock set cantidad_stock = cantidad_stock + @cantidad
        where id_producto = @id_producto and id_centro = @id_centro ";
        $AqConexion_model->no_select($sentenciaSQL,$param);
      }
    }
    
    return 1;    
  }
  
  function anadir_producto($parametros) {
    if ($parametros['id_pedido'] > 0 && $parametros['id_pedido'] != null) {      
      $AqConexion_model = new AqConexion_model();
      
      $param['id_pedido']=$parametros['id_pedido'];
      $param['id_producto']=$parametros['id_producto'];
      $existe = $this->existe_producto_pedido($param);
      
      if ($existe==0) {
        // ... Datos generales como pedido.
        $registro['id_pedido']=$parametros['id_pedido'];
        $registro['id_producto']=$parametros['id_producto'];
        $registro['cantidad']=$parametros['cantidad'];
        $registro['cantidad_entregada']=0;
        $registro['cantidad_pendiente']=$parametros['cantidad'];
        //
        $registro['fecha_creacion']=date("Y-m-d H:i:s");
        $registro['id_usuario_creacion']=$this->session->userdata('id_usuario');
        $registro['fecha_modificacion']=date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
        $registro['borrado']=0;        
          
        $AqConexion_model->insert('pedidos_productos',$registro);
      }
      else {
        // ... existe trae la cantidad de productos pedidos en caso de no venir vacio.              
        //if (($parametros['cantidad']+$existe)>0) {
          unset($param);
          $param['id_pedido']=$parametros['id_pedido'];
          $param['id_producto']=$parametros['id_producto'];
          $param['cantidad']=$parametros['cantidad'];
          
          $sentenciaSQL="update pedidos_productos set  cantidad = cantidad + @cantidad
          where id_pedido = @id_pedido and id_producto = @id_producto";
          $AqConexion_model->no_select($sentenciaSQL,$param);
        //}
        /*else {
          unset($param);
          $param['id_pedido']=$parametros['id_pedido'];
          $param['id_producto']=$parametros['id_producto'];
          
          $sentenciaSQL="DELETE FROM pedidos_productos where id_pedido = @id_pedido
          and id_producto = @id_producto";
          $AqConexion_model->no_select($sentenciaSQL,$param);
        }*/
      }
      
      return 1;
    }
    else {
      return 0;
    }
  }
  
  function activar_pedido($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    $sentenciaSQL="update pedidos set estado = @estado where id_pedido = @id_pedido";
    $AqConexion_model->no_select($sentenciaSQL,$parametros);
    
    return 1;
  }
  
  function existe_producto_pedido($parametros) {    
      $datos = $this->leer_productos_pedido($parametros);
      
      if ($datos==0) {
          return 0;
      }
      else {
          return $datos[0]['cantidad'];
      }      
  }
  
}
?>