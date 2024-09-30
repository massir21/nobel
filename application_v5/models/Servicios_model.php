<?php
/*
 * ALTER TABLE servicios ADD maxdescuento DECIMAL(6,2) DEFAULT 100 AFTER precio_proveedor ;
 */
class Servicios_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    // -------------------------------------------------------------------
    // ... SERVICIOS
    // -------------------------------------------------------------------
    function leer_servicios($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['id_servicio'])) {
            if ($parametros['id_servicio'] > 0) {
                $busqueda .= " AND servicios.id_servicio = @id_servicio ";
            }
        }

        if (isset($parametros['id_familia_servicio'])) {
            if ($parametros['id_familia_servicio'] > 0) {
                $busqueda .= " AND servicios.id_familia_servicio = @id_familia_servicio ";
            }
        }

        if (isset($parametros['id_empleado'])) {
            if ($parametros['id_empleado'] > 0) {
                $busqueda .= " AND servicios.id_servicio in
        (select id_servicio from usuarios_capacidades where id_usuario = @id_empleado) ";
            }
        }

        if (isset($parametros['velazquez'])) {
            if ($parametros['velazquez'] > 0) {
                $busqueda .= " AND servicios.id_servicio in
        (select id_servicio from usuarios_capacidades where id_usuario in
        (select id_usuario from usuarios where id_centro = 10 and borrado = 0)) ";
            }
        }

        if (isset($parametros['obsoleto'])) {
            $busqueda .= " AND servicios.obsoleto = 0 ";
        }

        if (isset($parametros['padre'])) {
            $busqueda .= " AND servicios.padre = @padre";
        }
        if (isset($parametros['solo_padre'])) {
            $busqueda .= " AND servicios.padre = 0";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT servicios.id_servicio,servicios.nombre_servicio,
    servicios.id_familia_servicio,servicios.abreviatura,servicios.pvp, servicios.maxdescuento, 
    servicios.precio_proveedor,servicios.notas,servicios.iva,servicios.link_encuesta,
    servicios.templos,servicios.duracion,servicios.obsoleto,
    servicios.id_usuario_creacion,servicios.fecha_creacion,
    servicios.id_usuario_modificacion,servicios.fecha_modificacion,
    servicios.borrado,servicios.id_usuario_borrado,servicios.fecha_borrado,
    servicios_familias.nombre_familia,servicios.color,
    servicios.padre, servicios.parte_padre, servicios.rellamada /* AÑADIDO PARA RELLAMADAS */
    FROM servicios
    LEFT JOIN servicios_familias ON servicios_familias.id_familia_servicio =
    servicios.id_familia_servicio
    WHERE servicios.borrado = 0 " . $busqueda . " ORDER BY nombre_familia,nombre_servicio ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function nuevo_servicio($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Datos generales como usuario.
        $registro['nombre_servicio'] = $parametros['nombre_servicio'];
        $registro['id_familia_servicio'] = $parametros['id_familia_servicio'];
        $registro['abreviatura'] = $parametros['abreviatura'];
        $registro['link_encuesta'] = $parametros['link_encuesta'];
        $registro['pvp'] = $parametros['pvp'];
        $registro['iva'] = $parametros['iva'];
        $registro['precio_proveedor'] = $parametros['precio_proveedor'];
        $registro['templos'] = $parametros['templos'];
        $registro['duracion'] = $parametros['duracion'];
        $registro['color'] = $parametros['color'];
        if (!isset($parametros['obsoleto'])) {
            $parametros['obsoleto'] = 0;
        }
        $registro['obsoleto'] = $parametros['obsoleto'];
        $registro['notas'] = $parametros['notas'];
        /* AÑADIDO PARA RELLAMADAS */
        if (isset($parametros['rellamada'])) {
            $registro['rellamada'] = $parametros['rellamada'];
        }
        /* AÑADIDO PARA RELLAMADAS */
        $registro['fecha_creacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_creacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['borrado'] = 0;

        if (isset($parametros['maxdescuento'])) {
            $registro['maxdescuento'] = $parametros['maxdescuento'];
        }

        $AqConexion_model->insert('servicios', $registro);

        $sentenciaSQL = "select max(id_servicio) as id_servicio from servicios";
        $resultado = $AqConexion_model->select($sentenciaSQL, null);

        return $resultado[0]['id_servicio'];
    }

    function actualizar_servicio($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $param['id_servicio'] = $parametros['id_servicio'];

        // ... Datos generales como usuario.
        $registro['nombre_servicio'] = $parametros['nombre_servicio'];
        $registro['id_familia_servicio'] = $parametros['id_familia_servicio'];
        $registro['abreviatura'] = $parametros['abreviatura'];
        $registro['link_encuesta'] = $parametros['link_encuesta'];
        $registro['pvp'] = $parametros['pvp'];
        $registro['iva'] = $parametros['iva'];
        $registro['precio_proveedor'] = $parametros['precio_proveedor'];
        $registro['templos'] = $parametros['templos'];
        $registro['duracion'] = $parametros['duracion'];
        $registro['color'] = $parametros['color'];
        if (!isset($parametros['obsoleto'])) {
            $parametros['obsoleto'] = 0;
        }
        $registro['obsoleto'] = $parametros['obsoleto'];
        $registro['notas'] = $parametros['notas'];
        /* AÑADIDO PARA RELLAMADAS */
        if (isset($parametros['rellamada'])) {
            $registro['rellamada'] = $parametros['rellamada'];
        }
        /* AÑADIDO PARA RELLAMADAS */
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');

        if (isset($parametros['maxdescuento'])) {
            $registro['maxdescuento'] = $parametros['maxdescuento'];
        }

        $where['id_servicio'] = $parametros['id_servicio'];
        $AqConexion_model->update('servicios', $registro, $where);

        return 1;
    }

    function actualizar_rellamada($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $registro['rellamada'] = $parametros['rellamada'];
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        if ($parametros['tipo'] == 'servicio') {
            $where['id_servicio'] = $parametros['id'];
            $AqConexion_model->update('servicios', $registro, $where);
            return 1;
        } elseif ($parametros['tipo'] == 'servicio_familia') {
            $where['id_familia_servicio'] = $parametros['id'];
            $AqConexion_model->update('servicios_familias', $registro, $where);
            return 1;
        } else {
            return 0;
        }
    }

    function borrar_servicio($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $parametros['id_usuario_borrado'] = $this->session->userdata('id_usuario');
        $parametros['fecha_borrado'] = date("Y-m-d H:i:s");

        $sentenciaSQL = "update servicios set borrado = 1,
    id_usuario_borrado = @id_usuario_borrado,
    fecha_borrado = @fecha_borrado
    where id_servicio = @id_servicio";
        $AqConexion_model->no_select($sentenciaSQL, $parametros);

        return 1;
    }

    function iva_servicio($id_servicio)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT iva    
    FROM servicios        
    WHERE id_servicio = @id_servicio ";

        $parametros['id_servicio'] = $id_servicio;
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        if (isset($datos[0]['iva'])) {
            return $datos[0]['iva'];
        } else {
            return 0;
        }
    }

    // -------------------------------------------------------------------
    // ... FAMILIAS DE SERVICIOS
    // -------------------------------------------------------------------
    function leer_familias_servicios($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['id_familia_servicio'])) {
            $busqueda .= " AND SF.id_familia_servicio = @id_familia_servicio ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT SF.id_familia_servicio,SF.nombre_familia,    
    SF.id_usuario_creacion,SF.fecha_creacion,
    SF.id_usuario_modificacion,SF.fecha_modificacion,
    SF.borrado,SF.id_usuario_borrado,SF.fecha_borrado,
    SF.nombre_familia,SF.citas_online, SF.rellamada /* AÑADIDO PARA RELLAMADAS */   
    /* AÑADIDO PARA RELLAMADAS */
    FROM servicios_familias AS SF        
    WHERE SF.borrado = 0 " . $busqueda . " ORDER BY nombre_familia ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function nuevo_familia_servicio($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Datos generales como usuario.
        $registro['nombre_familia'] = $parametros['nombre_familia'];
        if (isset($parametros['citas_online'])) {
            $registro['citas_online'] = $parametros['citas_online'];
        } else {
            $registro['citas_online'] = "";
        }
        /* AÑADIDO PARA RELLAMADAS */
        if (isset($parametros['rellamada'])) {
            $registro['rellamada'] = $parametros['rellamada'];
        }
        /* AÑADIDO PARA RELLAMADAS */
        //
        $registro['fecha_creacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_creacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['borrado'] = 0;

        $AqConexion_model->insert('servicios_familias', $registro);

        $sentenciaSQL = "select max(id_familia_servicio) as id_familia_servicio
    from servicios_familias";
        $resultado = $AqConexion_model->select($sentenciaSQL, null);

        return $resultado[0]['id_familia_servicio'];
    }

    function actualizar_familia_servicio($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $param['id_familia_servicio'] = $parametros['id_familia_servicio'];

        // ... Datos generales como usuario.
        $registro['nombre_familia'] = $parametros['nombre_familia'];
        if (isset($parametros['citas_online'])) {
            $registro['citas_online'] = $parametros['citas_online'];
        } else {
            $registro['citas_online'] = "";
        }
        /* AÑADIDO PARA RELLAMADAS */
        if (isset($parametros['rellamada'])) {
            $registro['rellamada'] = $parametros['rellamada'];
        }
        /* AÑADIDO PARA RELLAMADAS */
        //
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');

        $where['id_familia_servicio'] = $parametros['id_familia_servicio'];
        $AqConexion_model->update('servicios_familias', $registro, $where);

        return 1;
    }

    function borrar_familia_servicio($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $parametros['id_usuario_borrado'] = $this->session->userdata('id_usuario');
        $parametros['fecha_borrado'] = date("Y-m-d H:i:s");

        $sentenciaSQL = "update servicios_familias set borrado = 1,
    id_usuario_borrado = @id_usuario_borrado,
    fecha_borrado = @fecha_borrado
    where id_familia_servicio = @id_familia_servicio";
        $AqConexion_model->no_select($sentenciaSQL, $parametros);

        return 1;
    }

    function asociar_productos_tienda($id_servicio, $productos_tienda)
    {
        $AqConexion_model = new AqConexion_model();

        // Primero borramos todas las relaciones del servicio con ids_tienda.
        $sentenciaSQL = "DELETE FROM servicios_tienda WHERE id_servicio = @id_servicio";
        $parametros['id_servicio'] = $id_servicio;
        $AqConexion_model->no_select($sentenciaSQL, $parametros);

        // Guardamos la relacion de cada id_tienda con el servicio.
        if ($productos_tienda != "") {
            // Creamos un array con cada id tienda indicado.
            $ids_tienda = explode(",", $productos_tienda);

            foreach ($ids_tienda as $id_tienda) {
                $registro['id_servicio'] = $id_servicio;
                $registro['id_tienda'] = $id_tienda;
                $AqConexion_model->insert('servicios_tienda', $registro);
            }
        }

        return 1;
    }

    function leer_productos_tienda($id_servicio)
    {
        $AqConexion_model = new AqConexion_model();

        $sentencia_sql = "SELECT id_tienda FROM servicios_tienda
    WHERE id_servicio = @id_servicio";
        $parametros['id_servicio'] = $id_servicio;
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        if ($datos != 0) {
            $ids = array();
            $i = 0;
            foreach ($datos as $row) {
                $ids[$i] = $row['id_tienda'];
                $i++;
            }

            $separado_por_comas = implode(",", $ids);

            return $separado_por_comas;
        } else {
            return "";
        }
    }

    # ----------------------------------------------------------------------------------------------------------
    # ... Devuelve el Javascript que permite que al elegir una familia muestre solo los servicios asociados.
    # ---------------------------------------------------------------------------------------------------------
    function javacript_familias_servicios($parametros)
    {

        $id_servicio = $parametros['id_servicio'];
        $form = $parametros['form'];

        $script = "";

        # ... Leemos todas las familias existentes
        $param['vacio'] = "";
        $familias = $this->leer_familias_servicios($param);

        $script .= " if (document." . $form . ".id_familia_servicio.value=='0') {\n";
        $script .= "document." . $form . ".id_servicio.length = 1;\n";
        $script .= "document." . $form . ".id_servicio.options[0].value='0';\n";
        $script .= "document." . $form . ".id_servicio.options[0].text='Cualquier servicio...';\n";
        $script .= "document." . $form . ".id_servicio.options[0].selected = false;\n";
        $script .= " } \n";

        for ($i = 0; $i < count($familias); $i++) {
            $script .= " if (document." . $form . ".id_familia_servicio.value==" . $familias[$i]['id_familia_servicio'] . ") {\n";

            # ... Leemos las servicios en la familia concreta
            unset($parm);
            $param['id_familia_servicio'] = $familias[$i]['id_familia_servicio'];
            $servicios = $this->leer_servicios($param);
            $items = (is_countable($servicios)) ? count($servicios) : 0;

            if ($items != 0) {
                $items++;
                $script .= "document." . $form . ".id_servicio.length = " . $items . ";\n";

                for ($j = 0; $j < count($servicios); $j++) {
                    $idx = $j + 1;

                    $nom_servicio = $servicios[$j]['nombre_servicio'];
                    $nom_servicio = str_replace("'", "_", $nom_servicio);
                    $nom_servicio = str_replace('"', "_", $nom_servicio);

                    $script .= "document." . $form . ".id_servicio.options[" . $idx . "].value='" . $servicios[$j]['id_servicio'] . "';\n";
                    $script .= "document." . $form . ".id_servicio.options[" . $idx . "].text='" . $nom_servicio . ")';\n";

                    if ($id_servicio == $servicios[$j]['id_servicio']) {
                        $script .= "document." . $form . ".id_servicio.options[" . $idx . "].selected = true;\n";
                    }
                }
            } else {
                $script .= "document." . $form . ".id_servicio.length = 1;\n";
            }

            $script .= "document." . $form . ".id_servicio.options[0].value='0';\n";
            $script .= "document." . $form . ".id_servicio.options[0].text='Cualquier servicio...';\n";
            $script .= "document." . $form . ".id_servicio.options[0].selected = false;\n";

            $script .= " } \n";
        }

        return $script;
    }
}
