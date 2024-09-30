<?php
class Productos_model extends CI_Model {
  function __construct() {
    parent::__construct();
  }
  
  // -------------------------------------------------------------------
  // ... PRODUCTOS
  // -------------------------------------------------------------------
  function leer_productos($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";
    
    if (isset($parametros['id_producto'])) {
      $busqueda.=" AND productos.id_producto = @id_producto ";
    }
    
    if (isset($parametros['id_familia_producto'])) {
      $busqueda.=" AND productos.id_familia_producto = @id_familia_producto ";
    }
    
    if ($this->session->userdata('id_perfil') > 0) {
      $busqueda.=" AND productos.obsoleto = 0 ";
    }
    
    // ... Asignamos el centro del usuario validado pora mostrar ese stock
    $parametros['id_centro_usuario']=$this->session->userdata('id_centro_usuario');
    
    // ... Leemos los registros
    $sentencia_sql="SELECT productos.id_producto,productos.nombre_producto,
    productos.id_familia_producto,productos.pvp,productos.iva,
    productos.precio_franquiciado_sin_iva,productos.precio_compra_sin_iva,
    productos.obsoleto,productos.instrucciones,
    productos.id_usuario_creacion,productos.fecha_creacion,
    productos.id_usuario_modificacion,productos.fecha_modificacion,
    productos.borrado,productos.id_usuario_borrado,productos.fecha_borrado,
    productos_familias.nombre_familia,productos_stock.cantidad_stock,
    productos_stock.stock_minimo
    FROM productos
    LEFT JOIN productos_familias ON productos_familias.id_familia_producto =
    productos.id_familia_producto
    LEFT JOIN productos_stock ON productos_stock.id_producto =
    productos.id_producto
    WHERE productos.borrado = 0 and productos_stock.id_centro = @id_centro_usuario
    ".$busqueda." ORDER BY nombre_producto ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    return $datos;
  }
  
  function nuevo_producto($parametros) {    
    $AqConexion_model = new AqConexion_model();
    
    // ... Datos generales
    $registro['nombre_producto']=$parametros['nombre_producto'];      
    $registro['id_familia_producto']=$parametros['id_familia_producto'];    
    $registro['pvp']=$parametros['pvp'];
    $registro['iva']=$parametros['iva'];
    $registro['precio_franquiciado_sin_iva']=$parametros['precio_franquiciado_sin_iva'];
    $registro['precio_compra_sin_iva']=$parametros['precio_compra_sin_iva'];
    if (!isset($parametros['obsoleto'])) { $parametros['obsoleto']=0; }
    $registro['obsoleto']=$parametros['obsoleto'];
    if (isset($parametros['instrucciones'])) {
      $registro['instrucciones']=$parametros['instrucciones'];
    }
    //
    $registro['fecha_creacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_creacion']=$this->session->userdata('id_usuario');
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    $registro['borrado']=0;
    
    $AqConexion_model->insert('productos',$registro);
      
    $sentenciaSQL="select max(id_producto) as id_producto from productos";
    $resultado = $AqConexion_model->select($sentenciaSQL,null);
    
    // --------------------------------------------------------------------
    // ... Guardamos el stock del producto para el centro correspondiente.
    // y para el resto de centros.
    // --------------------------------------------------------------------
    unset($param);
    $param['vacio']="";
    $centros = $this->Usuarios_model->leer_centros($param);
    
    foreach ($centros as $row) {
      $stock['fecha_creacion']=date("Y-m-d H:i:s");
      $stock['id_usuario_creacion']=$this->session->userdata('id_usuario');
      $stock['fecha_modificacion']=date("Y-m-d H:i:s");
      $stock['id_usuario_modificacion']=$this->session->userdata('id_usuario');
      $stock['borrado']=0;
      if ($this->session->userdata('id_centro_usuario') == $row['id_centro']) {
        if (!isset($parametros['cantidad_stock'])) { $parametros['cantidad_stock']=0; }
        $stock['cantidad_stock']=$parametros['cantidad_stock'];
      }
      else {
        $stock['cantidad_stock']=0;
      }
      $stock['id_producto']=$resultado[0]['id_producto'];
      $stock['id_centro']=$row['id_centro'];
      
      $AqConexion_model->insert('productos_stock',$stock);
    }
    // --------------------------------------------------------------------
    
    return $resultado[0]['id_producto'];      
  }

  function actualizar_producto($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    $param['id_producto']=$parametros['id_producto'];    
    
    // ... Datos generales
    $registro['nombre_producto']=$parametros['nombre_producto'];      
    $registro['id_familia_producto']=$parametros['id_familia_producto'];    
    $registro['pvp']=$parametros['pvp'];
    $registro['iva']=$parametros['iva'];
    $registro['precio_franquiciado_sin_iva']=$parametros['precio_franquiciado_sin_iva'];
    $registro['precio_compra_sin_iva']=$parametros['precio_compra_sin_iva'];
    if (!isset($parametros['obsoleto'])) { $parametros['obsoleto']=0; }
    $registro['obsoleto']=$parametros['obsoleto'];
    if (isset($parametros['instrucciones'])) {
      $registro['instrucciones']=$parametros['instrucciones'];
    }
    //
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    
    $where['id_producto']=$parametros['id_producto'];    
    $AqConexion_model->update('productos',$registro,$where);
    
    // --------------------------------------------------------------------
    // ... Guardamos el stock del producto para el centro correspondiente.
    // --------------------------------------------------------------------
    unset($where);
    $stock['fecha_modificacion']=date("Y-m-d H:i:s");
    $stock['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    $stock['borrado']=0;
    //
    if (!isset($parametros['cantidad_stock'])) { $parametros['cantidad_stock']=0; }
    $stock['cantidad_stock']=$parametros['cantidad_stock'];    
    
    $where['id_producto']=$parametros['id_producto'];
    $where['id_centro']=$this->session->userdata('id_centro_usuario');
    
    $AqConexion_model->update('productos_stock',$stock,$where);
    // --------------------------------------------------------------------
    
    return 1;    
  }
  
  function actualizar_producto_stock($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    if(!isset($parametros['id_productos']) || !is_array($parametros['id_productos'])){
      return 0;
    }else{
      $ids=$parametros['id_productos'];
      $cantidad_stock=$parametros['cantidad_sock'];
      $stock_minimo=$parametros['stock_minimo'];
      
      for ($i=0; $i<count($ids); $i++) {
        unset($stock);
        $stock['fecha_modificacion']=date("Y-m-d H:i:s");
        $stock['id_usuario_modificacion']=$this->session->userdata('id_usuario');
        $stock['borrado']=0;
        //      
        $stock['cantidad_stock']=$cantidad_stock[$i];
        $stock['stock_minimo']=$stock_minimo[$i];
        
        unset($where);
        $where['id_producto']=$ids[$i];
        $where['id_centro']=$this->session->userdata('id_centro_usuario');
        
        $AqConexion_model->update('productos_stock',$stock,$where);    
      }
      
      return 1;    
    }
  }
  
  function actualizar_stock_venta($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    unset($param);
    $param['id_producto']=$parametros['id_producto'];
    $param['cantidad']=$parametros['cantidad'];
    $param['id_centro']=$this->session->userdata('id_centro_usuario');
    $param['fecha_modificacion']=date("Y-m-d H:i:s");
    $param['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    
    $sentenciaSQL="update productos_stock set cantidad_stock = cantidad_stock - @cantidad,
    fecha_modificacion = @fecha_modificacion, id_usuario_modificacion = @id_usuario_modificacion
    where id_producto = @id_producto and id_centro = @id_centro ";
    $AqConexion_model->no_select($sentenciaSQL,$param);
    
    return 1;
  }
  
  function actualizar_stock_devolucion($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    unset($param);
    $param['id_producto']=$parametros['id_producto'];
    $param['cantidad']=$parametros['cantidad'];
    $param['id_centro']=$this->session->userdata('id_centro_usuario');
    $param['fecha_modificacion']=date("Y-m-d H:i:s");
    $param['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    
    $sentenciaSQL="update productos_stock set cantidad_stock = cantidad_stock + @cantidad,
    fecha_modificacion = @fecha_modificacion, id_usuario_modificacion = @id_usuario_modificacion
    where id_producto = @id_producto and id_centro = @id_centro ";
    $AqConexion_model->no_select($sentenciaSQL,$param);
    
    return 1;
  }
  
  function borrar_producto($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    $parametros['id_usuario_borrado']=$this->session->userdata('id_usuario');
    $parametros['fecha_borrado']=date("Y-m-d H:i:s");
    
    $sentenciaSQL="update productos set borrado = 1,
    id_usuario_borrado = @id_usuario_borrado,
    fecha_borrado = @fecha_borrado
    where id_producto = @id_producto";
    $AqConexion_model->no_select($sentenciaSQL,$parametros);
    
    return 1;
  }
  
  function iva_producto($id_producto) {
    $AqConexion_model = new AqConexion_model();

    // ... Leemos los registros
    $sentencia_sql="SELECT iva    
    FROM productos
    WHERE id_producto = @id_producto ";
    
    $parametros['id_producto'] = $id_producto;
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    if (isset($datos[0]['iva']))
    {
      return $datos[0]['iva'];
    }
    else {
      return 0;
    }
  }
  
  // -------------------------------------------------------------------
  // ... FAMILIAS DE SERVICIOS
  // -------------------------------------------------------------------
  function leer_familias_productos($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";
    
    if (isset($parametros['id_familia_producto'])) {
      $busqueda.=" AND SF.id_familia_producto = @id_familia_producto ";
    }
    
    // ... Leemos los registros
    $sentencia_sql="SELECT SF.id_familia_producto,SF.nombre_familia,    
    SF.id_usuario_creacion,SF.fecha_creacion,
    SF.id_usuario_modificacion,SF.fecha_modificacion,
    SF.borrado,SF.id_usuario_borrado,SF.fecha_borrado,
    SF.nombre_familia
    FROM productos_familias AS SF        
    WHERE SF.borrado = 0 ".$busqueda." ORDER BY nombre_familia ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    return $datos;
  }
  
  function nuevo_familia_producto($parametros) {    
    $AqConexion_model = new AqConexion_model();
    
    // ... Datos generales como usuario.
    $registro['nombre_familia']=$parametros['nombre_familia'];        
    //
    $registro['fecha_creacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_creacion']=$this->session->userdata('id_usuario');
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    $registro['borrado']=0;
    
    $AqConexion_model->insert('productos_familias',$registro);
      
    $sentenciaSQL="select max(id_familia_producto) as id_familia_producto
    from productos_familias";
    $resultado = $AqConexion_model->select($sentenciaSQL,null);
    
    return $resultado[0]['id_familia_producto'];      
  }

  function actualizar_familia_producto($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    $param['id_familia_producto']=$parametros['id_familia_producto'];    
    
    // ... Datos generales como usuario.
    $registro['nombre_familia']=$parametros['nombre_familia'];          
    //
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    
    $where['id_familia_producto']=$parametros['id_familia_producto'];
    $AqConexion_model->update('productos_familias',$registro,$where);
    
    return 1;    
  }

  function borrar_familia_producto($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    $sentenciaSQL="update productos_familias set borrado = 1 where id_familia_producto = @id_familia_producto";
    $AqConexion_model->no_select($sentenciaSQL,$parametros);
    
    return 1;
  }
  
  # ----------------------------------------------------------------------------------------------------------
  # ... Devuelve el Javascript que permite que al elegir una familia muestre solo las productos pertenecientes
  # ----------------------------------------------------------------------------------------------------------
  function javacript_familias_productos($parametros) {
    
    $id_producto=$parametros['id_producto'];
    $form=$parametros['form'];
    
    $script="";
    
    # ... Leemos todas las familias existentes
    $param['vacio']="";
    $familias=$this->leer_familias_productos($param);
    
    $script.=" if (document.".$form.".id_familia_producto.value=='') {\n";
    $script.="document.".$form.".id_producto.length = 1;\n";
    $script.="document.".$form.".id_producto.options[0].value='';\n";
    $script.="document.".$form.".id_producto.options[0].text='... Elegir ...';\n";
    $script.="document.".$form.".id_producto.options[0].selected = false;\n";      
    $script.=" } \n";
    
    for($i=0; $i<count($familias); $i++) {
        $script.=" if (document.".$form.".id_familia_producto.value==".$familias[$i]['id_familia_producto'].") {\n";
            
        # ... Leemos las productos en la familia concreta
        unset($parm);
        $param['id_familia_producto']=$familias[$i]['id_familia_producto'];
        $productos=$this->leer_productos($param);        
        $items=count($productos);
        
        if ($items!=0) {
            $items++;
            $script.="document.".$form.".id_producto.length = ".$items.";\n";
            
            for($j=0; $j<count($productos); $j++) {
                $idx=$j+1;
                
                $nom_producto=$productos[$j]['nombre_producto'];
                $nom_producto=str_replace("'", "_", $nom_producto);
                $nom_producto=str_replace('"', "_", $nom_producto);
                
                $script.="document.".$form.".id_producto.options[".$idx."].value='".$productos[$j]['id_producto']."';\n";
                $script.="document.".$form.".id_producto.options[".$idx."].text='".$nom_producto." --> (Stock actual: ".$productos[$j]['cantidad_stock'].")';\n";
                
                if ($id_producto==$productos[$j]['id_producto']) {
                    $script.="document.".$form.".id_producto.options[".$idx."].selected = true;\n";
                }                
            }
        }
        else {
            $script.="document.".$form.".id_producto.length = 1;\n";
        }
        
        $script.="document.".$form.".id_producto.options[0].value='';\n";
        $script.="document.".$form.".id_producto.options[0].text='... Elegir ...';\n";
        $script.="document.".$form.".id_producto.options[0].selected = false;\n";
        
        $script.=" } \n";
    }
    
    return $script;
  }
  
  // -------------------------------------------------------------------
  // ... PRODUCTOS CONSUMO
  // -------------------------------------------------------------------
  function leer_productos_consumo($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";
    
    if (isset($parametros['id_consumo'])) {
      $busqueda.=" AND PC.id_consumo = @id_consumo ";
    }
    
    if (isset($parametros['id_centro'])) {
      $busqueda.=" AND PC.id_centro = @id_centro ";
    }
    
    // ... Leemos los registros
    $sentencia_sql="SELECT PC.id_consumo,PC.id_producto,PC.id_centro,PC.nota,PC.cabina,
    PC.cantidad_consumida,productos.nombre_producto,
    DATE_FORMAT(PC.fecha_creacion,'%Y-%m-%d') as fecha_consumo_aaaammdd,
    DATE_FORMAT(PC.fecha_creacion,'%d-%m-%Y') as fecha_consumo_ddmmaaaa,
    productos_familias.nombre_familia,centros.nombre_centro
    FROM productos_consumo AS PC
    LEFT JOIN centros ON centros.id_centro = PC.id_centro
    LEFT JOIN (productos LEFT JOIN productos_familias
    ON productos_familias.id_familia_producto = productos.id_familia_producto)
    ON productos.id_producto = PC.id_producto
    WHERE PC.borrado = 0 ".$busqueda." ORDER BY PC.fecha_creacion DESC ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    return $datos;
  }

  function guardar_consumo_producto($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    // ... Datos generales
    $registro['id_producto']=$parametros['id_producto'];      
    $registro['id_centro']=$parametros['id_centro'];
    $registro['cantidad_consumida']=$parametros['cantidad_consumida'];    
    //
    $registro['fecha_creacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_creacion']=$this->session->userdata('id_usuario');
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    $registro['borrado']=0;
    //08/07/20
    $registro['nota']=$parametros['nota'];
    $registro['cabina']=$parametros['cabina'];
    //Fin
    
    $AqConexion_model->insert('productos_consumo',$registro);

    // ... Actualizamos el stock del prodcuto para el centro que sea.
    // en este caso restamos del stock el producto consumido
    unset($param);
    $param['id_producto']=$parametros['id_producto'];
    $param['cantidad']=$parametros['cantidad_consumida'];    
    $this->actualizar_stock_venta($param);
    
    return 1;
  }
  
  function borrar_consumo_producto($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    // ... Leemos los datos del producto consumido antes de borrarlo.
    unset($param);
    $param['id_consumo']=$parametros['id_consumo'];
    $producto_consumo = $this->leer_productos_consumo($param);

    // ... Borramos el producto consumido.
    $sentenciaSQL="update productos_consumo set borrado = 1
    where id_consumo = @id_consumo";
    $AqConexion_model->no_select($sentenciaSQL,$parametros);
    
    // ... Actualizamos el stock del prodcuto para el centro que sea.
    // en este caso sumamos de nuevo al stock.
    unset($param);
    $param['id_producto']=$producto_consumo[0]['id_producto'];
    $param['cantidad']=$producto_consumo[0]['cantidad_consumida'];
    $this->actualizar_stock_devolucion($param);
    
    return 1;
  }
  
  function productos_json($parametros) {
    $AqConexion_model = new AqConexion_model();
   
    $busqueda="";
    
    if (isset($parametros['q'])) {
      $busqueda.=" AND CONCAT(P.nombre_producto, ' ', productos_familias.nombre_familia) like '%".$parametros['q']."%' ";
    }
    
    $parametros['id_centro_usuario']=$this->session->userdata('id_centro_usuario');
    
    // ... Leemos los registros
    $sentencia_sql="SELECT P.id_producto as id,
    CONCAT(P.nombre_producto, ' - ', productos_familias.nombre_familia, ' - Stock actual ', productos_stock.cantidad_stock) as name,
    CONCAT(P.nombre_producto, ' - ', productos_familias.nombre_familia, ' - Stock actual ', productos_stock.cantidad_stock) as text
    FROM productos as P
    LEFT JOIN productos_familias ON productos_familias.id_familia_producto =
    P.id_familia_producto
    LEFT JOIN productos_stock ON productos_stock.id_producto =
    P.id_producto
    WHERE P.borrado = 0 and P.obsoleto = '0' and productos_stock.id_centro = @id_centro_usuario
    ".$busqueda." ORDER BY P.nombre_producto ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    return $datos;
  }
  
  function productos_instrucciones_json($parametros) {
    $AqConexion_model = new AqConexion_model();
   
    $busqueda="";
    
    if (isset($parametros['q'])) {
      $busqueda.=" AND CONCAT(P.nombre_producto, ' ', productos_familias.nombre_familia) like '%".$parametros['q']."%' ";
    }
    
    $parametros['id_centro_usuario']=$this->session->userdata('id_centro_usuario');
    
    // ... Leemos los registros
    $sentencia_sql="SELECT P.id_producto as id,
    CONCAT(P.nombre_producto, ' - ', productos_familias.nombre_familia) as name,
    CONCAT(P.nombre_producto, ' - ', productos_familias.nombre_familia) as text
    FROM productos as P
    LEFT JOIN productos_familias ON productos_familias.id_familia_producto =
    P.id_familia_producto
    LEFT JOIN productos_stock ON productos_stock.id_producto =
    P.id_producto
    WHERE P.borrado = 0 and P.obsoleto = '0' and productos_stock.id_centro = @id_centro_usuario
    ".$busqueda." ORDER BY P.nombre_producto ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    return $datos;
  }
  
  // -------------------------------------------------------------------
  // ... PRODUCTOS VENTA_ONLINE
  // -------------------------------------------------------------------
  function leer_productos_venta_online($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";
    
    if (isset($parametros['id_venta_online'])) {
      $busqueda.=" AND PC.id_venta_online = @id_venta_online ";
    }
    
    if (isset($parametros['id_centro'])) {
      $busqueda.=" AND PC.id_centro = @id_centro ";
    }
    
    // ... Leemos los registros
    $sentencia_sql="SELECT PC.id_venta_online,PC.id_producto,PC.id_centro,
    PC.cantidad_consumida,productos.nombre_producto,
    DATE_FORMAT(PC.fecha_creacion,'%Y-%m-%d') as fecha_consumo_aaaammdd,
    DATE_FORMAT(PC.fecha_creacion,'%d-%m-%Y') as fecha_consumo_ddmmaaaa,
    productos_familias.nombre_familia,centros.nombre_centro
    FROM productos_venta_online AS PC
    LEFT JOIN centros ON centros.id_centro = PC.id_centro
    LEFT JOIN (productos LEFT JOIN productos_familias
    ON productos_familias.id_familia_producto = productos.id_familia_producto)
    ON productos.id_producto = PC.id_producto
    WHERE PC.borrado = 0 ".$busqueda." ORDER BY PC.fecha_creacion DESC ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    return $datos;
  }

  function guardar_venta_online_producto($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    // ... Datos generales
    $registro['id_producto']=$parametros['id_producto'];      
    $registro['id_centro']=$parametros['id_centro'];
    $registro['cantidad_consumida']=$parametros['cantidad_consumida'];    
    //
    $registro['fecha_creacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_creacion']=$this->session->userdata('id_usuario');
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    $registro['borrado']=0;
    
    $AqConexion_model->insert('productos_venta_online',$registro);

    // ... Actualizamos el stock del prodcuto para el centro que sea.
    // en este caso restamos del stock el producto consumido
    unset($param);
    $param['id_producto']=$parametros['id_producto'];
    $param['cantidad']=$parametros['cantidad_consumida'];    
    $this->actualizar_stock_venta($param);
    
    return 1;
  }
  
  function borrar_venta_online_producto($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    // ... Leemos los datos del producto consumido antes de borrarlo.
    unset($param);
    $param['id_venta_online']=$parametros['id_venta_online'];
    $producto_consumo = $this->leer_productos_venta_online($param);

    // ... Borramos el producto consumido.
    $sentenciaSQL="update productos_venta_online set borrado = 1
    where id_venta_online = @id_venta_online";
    $AqConexion_model->no_select($sentenciaSQL,$parametros);
    
    // ... Actualizamos el stock del prodcuto para el centro que sea.
    // en este caso sumamos de nuevo al stock.
    unset($param);
    $param['id_producto']=$producto_consumo[0]['id_producto'];
    $param['cantidad']=$producto_consumo[0]['cantidad_consumida'];
    $this->actualizar_stock_devolucion($param);
    
    return 1;
  }
  
  // -------------------------------------------------------------------
  // ... PRODUCTOS STOCK INTRODUCION CENTRAL
  // -------------------------------------------------------------------
  function leer_productos_stock_introducido($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";
    
    if (isset($parametros['id_stock_introducido'])) {
      $busqueda.=" AND PC.id_stock_introducido = @id_stock_introducido ";
    }
    
    // ... Leemos los registros
    $sentencia_sql="SELECT PC.id_stock_introducido,PC.id_producto,PC.id_centro,
    PC.cantidad,productos.nombre_producto,
    DATE_FORMAT(PC.fecha_creacion,'%Y-%m-%d %H:%i') as fecha_introducido_aaaammdd,
    DATE_FORMAT(PC.fecha_creacion,'%d-%m-%Y %H:%i') as fecha_introducido_ddmmaaaa,
    productos_familias.nombre_familia,centros.nombre_centro
    FROM productos_introducido_stock AS PC
    LEFT JOIN centros ON centros.id_centro = PC.id_centro
    LEFT JOIN (productos LEFT JOIN productos_familias
    ON productos_familias.id_familia_producto = productos.id_familia_producto)
    ON productos.id_producto = PC.id_producto
    WHERE PC.borrado = 0 ".$busqueda." ORDER BY PC.fecha_creacion DESC ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    return $datos;
  }
  
  function guardar_stock_introducido($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    // ... Datos generales
    $registro['id_producto']=$parametros['id_producto'];      
    $registro['id_centro']=$parametros['id_centro'];
    $registro['cantidad']=$parametros['cantidad'];    
    //
    $registro['fecha_creacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_creacion']=$this->session->userdata('id_usuario');
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    $registro['borrado']=0;
    
    $AqConexion_model->insert('productos_introducido_stock',$registro);

    // ... Actualizamos el stock del prodcuto para el centro que sea.
    // en este caso sumamos el stock del producto introducido.
    unset($param);
    $param['id_producto']=$parametros['id_producto'];
    $param['cantidad']=$parametros['cantidad'];    
    $this->actualizar_stock_introducido($param);
    
    return 1;
  }
  
  function actualizar_stock_introducido($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    unset($param);
    $param['id_producto']=$parametros['id_producto'];
    $param['cantidad']=$parametros['cantidad'];
    $param['id_centro']=1;
    $param['fecha_modificacion']=date("Y-m-d H:i:s");
    $param['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    
    $sentenciaSQL="update productos_stock set cantidad_stock = cantidad_stock + @cantidad,
    fecha_modificacion = @fecha_modificacion, id_usuario_modificacion = @id_usuario_modificacion
    where id_producto = @id_producto and id_centro = @id_centro ";
    $AqConexion_model->no_select($sentenciaSQL,$param);
    
    return 1;
  }
  
  function borrar_stock_introducido($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    // ... Leemos los datos del producto consumido antes de borrarlo.
    unset($param);
    $param['id_stock_introducido']=$parametros['id_stock_introducido'];
    $producto = $this->leer_productos_stock_introducido($param);

    // ... Borramos el producto consumido.
    $sentenciaSQL="update productos_introducido_stock set borrado = 1
    where id_stock_introducido = @id_stock_introducido";
    $AqConexion_model->no_select($sentenciaSQL,$parametros);
    
    // ... Actualizamos el stock del prodcuto para el centro que sea.
    // en este caso multiplicamos por -1 para restar.
    unset($param);
    $param['id_producto']=$producto[0]['id_producto'];
    $param['cantidad']=$producto[0]['cantidad']*-1;
    $this->actualizar_stock_introducido($param);
    
    return 1;
  }
  
}
?>