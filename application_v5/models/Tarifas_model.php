<?php
/*
 *
CREATE TABLE `tarifas` (
  `id_tarifa` int(11) NOT NULL,
  `nombre_tarifa` varchar(150) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `abreviatura` varchar(45) DEFAULT NULL,
  `id_usuario_creacion` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT NULL,
  `id_usuario_modificacion` int(11) DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL,
  `borrado` int(11) DEFAULT NULL,
  `fecha_borrado` timestamp NULL DEFAULT NULL,
  `id_usuario_borrado` int(11) DEFAULT NULL,
  `debug` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `tarifas`
  ADD PRIMARY KEY (`id_tarifa`),
  ADD KEY `abreviatura_tarifa_idx` (`abreviatura`),
  ADD KEY `activo_tarifa` (`activo`);

ALTER TABLE `tarifas`
  MODIFY `id_tarifa` int(11) NOT NULL AUTO_INCREMENT;


 *
 */


/*
 *
 CREATE TABLE `tarifas_precioservicio` (
  `id_preciotarifa` int(11) NOT NULL,
  `id_tarifa` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `pvp` decimal(10,2) NOT NULL,
  `id_usuario_creacion` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT NULL,
  `id_usuario_modificacion` int(11) DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL,
  `borrado` int(11) DEFAULT NULL,
  `fecha_borrado` timestamp NULL DEFAULT NULL,
  `id_usuario_borrado` int(11) DEFAULT NULL,
  `debug` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `tarifas_precioservicio`
  ADD PRIMARY KEY (`id_preciotarifa`),
  ADD KEY `id_tarifa_idx` (`id_tarifa`);

ALTER TABLE  `tarifas_precioservicio` ADD UNIQUE `id_tarifa_servicio_index` (`id_tarifa`, `id_servicio`);

ALTER TABLE `tarifas_precioservicio`
  MODIFY `id_preciotarifa` int(11) NOT NULL AUTO_INCREMENT;

 */


/*
 * INSERT INTO `modulos` (`id_modulo`, `nombre_modulo`, `url`, `padre`, `orden`, `orden_item`, `id_usuario_creacion`, `fecha_creacion`, `id_usuario_modificacion`, `fecha_modificacion`, `borrado`, `id_usuario_borrado`, `fecha_borrado`, `debug`) VALUES ('80', 'Tarifas', 'tarifas', 'Master', '8', '0', '1', '2024-01-01 23:00:00', '1', '2024-01-01 23:00:00', '0', NULL, NULL, '0');
 */


class Tarifas_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    // -------------------------------------------------------------------
    // ... SERVICIOS
    // -------------------------------------------------------------------
    function leer_tarifas($parametros) {
        $AqConexion_model = new AqConexion_model();

        $busqueda="";

        if (isset($parametros['id_tarifa'])) {
            if ($parametros['id_tarifa']>0) {
                $busqueda.=" AND tarifas.id_tarifa = @id_tarifa ";
            }
        }

        if (isset($parametros['nombre_tarifa'])) {
            if ($parametros['nombre_tarifa']>0) {
                $busqueda.=" AND tarifas.nombre_tarifa = @nombre_tarifa ";
            }
        }

        if (isset($parametros['abreviatura'])) {
            if ($parametros['abreviatura']>0) {
                $busqueda.=" AND servicios.abreviatura = @abreviatura ";
            }
        }

        if(isset($parametros['activo'])){
            $busqueda.=" AND tarifas.activo = @activo ";
        }





        // ... Leemos los registros
        $sentencia_sql="SELECT tarifas.*  ";
        $sentencia_sql.=" FROM tarifas ";
        $sentencia_sql.=" WHERE tarifas.borrado = 0 ".$busqueda." ORDER BY nombre_tarifa ASC ";



        $datos = $AqConexion_model->select($sentencia_sql,$parametros);

        return $datos;
    }

    function borrar_tarifa($parametros) {
        $AqConexion_model = new AqConexion_model();

        if(isset($parametros['id_tarifa'])) {
            $parametros['id_usuario_borrado'] = $this->session->userdata('id_usuario');
            $parametros['fecha_borrado'] = date("Y-m-d H:i:s");

            $sentenciaSQL = "update tarifas set borrado = 1,
    id_usuario_borrado = @id_usuario_borrado,
    fecha_borrado = @fecha_borrado
    where id_tarifa = @id_tarifa";
            $AqConexion_model->no_select($sentenciaSQL, $parametros);

            return 1;
        }
        return 0;
    }




    function nueva_tarifa($parametros) {
        $AqConexion_model = new AqConexion_model();

        // ... Datos generales como usuario.
        $registro['nombre_tarifa']=isset($parametros['nombre_tarifa']) ?  $parametros['nombre_tarifa'] : 'Sin nombre' ;
        $registro['activo']=isset($parametros['activo']) ? $parametros['activo']: 1;
        $registro['abreviatura']=isset($parametros['abreviatura']) ? $parametros['abreviatura'] : substr($registro['nombre_tarifa'],0,5);
        //
        $registro['fecha_creacion']=date("Y-m-d H:i:s");
        $registro['id_usuario_creacion']=$this->session->userdata('id_usuario');
        $registro['fecha_modificacion']=date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
        $registro['borrado']=0;

        $AqConexion_model->insert('tarifas',$registro);

        $sentenciaSQL="select max(id_tarifa) as id_tarifa from tarifas";
        $resultado = $AqConexion_model->select($sentenciaSQL,null);

        return $resultado[0]['id_tarifa'];
    }



    function actualizar_tarifa($parametros) {
        $AqConexion_model = new AqConexion_model();

        if(isset($parametros['id_tarifa'])) {
            $param['id_tarifa'] = $parametros['id_tarifa'];

            // ... Datos generales como usuario.
            $registro['nombre_tarifa'] = $parametros['nombre_tarifa'];
            $registro['abreviatura'] = isset($parametros['abreviatura']) ? $parametros['abreviatura'] : substr($registro['nombre_tarifa'],0,5);
            if (!isset($parametros['activo'])) {
                $parametros['activo'] = 0;
            }
            $registro['activo'] = $parametros['activo'];
            //
            $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
            $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');

            $where['id_tarifa'] = $parametros['id_tarifa'];
            $AqConexion_model->update('tarifas', $registro, $where);

            return 1;
        }
        return 0;
    }


    function leer_servicios($idTarifa) {
        $AqConexion_model = new AqConexion_model();

        $busqueda="";
        $parametros=[];
        $parametros['id_tarifa']=$idTarifa;
        //$busqueda.=" AND servicios.id_servicio = @id_servicio ";

        // ... Leemos los registros
        $sentencia_sql="SELECT servicios.id_servicio,servicios.nombre_servicio,
    servicios.id_familia_servicio,servicios.abreviatura,servicios.pvp,
    servicios.precio_proveedor,servicios.notas,servicios.iva,servicios.link_encuesta,
    servicios.templos,servicios.duracion,servicios.obsoleto,
    servicios.id_usuario_creacion,servicios.fecha_creacion,
    servicios.id_usuario_modificacion,servicios.fecha_modificacion,
    servicios.borrado,servicios.id_usuario_borrado,servicios.fecha_borrado,
    servicios_familias.nombre_familia,servicios.color,
    servicios.padre, servicios.parte_padre,
    tarifas_precioservicio.pvp as pvp_tarifa
    FROM servicios
    LEFT JOIN servicios_familias ON servicios_familias.id_familia_servicio = servicios.id_familia_servicio
    LEFT JOIN tarifas_precioservicio ON servicios.id_servicio=tarifas_precioservicio.id_servicio AND tarifas_precioservicio.id_tarifa=@id_tarifa
    WHERE servicios.borrado = 0 ".$busqueda." ORDER BY nombre_familia,nombre_servicio ";
        $datos = $AqConexion_model->select($sentencia_sql,$parametros);

        return $datos;
    }


    public function leer_precios($idServicio){
        $AqConexion_model = new AqConexion_model();

        $parametros=['id_servicio'=>$idServicio];
        $sentencia_sql=
            " SELECT tarifas_precioservicio.id_preciotarifa,
                    tarifas_precioservicio.id_tarifa,
                    tarifas_precioservicio.id_servicio,
                    tarifas_precioservicio.pvp
               FROM tarifas
               INNER JOIN tarifas_precioservicio ON tarifas_precioservicio.id_tarifa = tarifas.id_tarifa
               WHERE tarifas_precioservicio.id_servicio = @id_servicio ";
        $datos = $AqConexion_model->select($sentencia_sql,$parametros);

        return $datos;


    }

    public function actualizar_precios($id_tarifa,$prices){
        $registros=[];
        $toDelete=[];
        foreach($prices as $id_servicio => $price){
            if($price==""){
                $toDelete[]=$id_servicio;
            }
            else{
                if(is_numeric($price)){
                    $registros[] = array('id_servicio'=>$id_servicio,'pvp'=>floatval($price));
                }
            }
        }
        $AqConexion_model = new AqConexion_model();
        if(count($toDelete)){
            $parametros=['id_tarifa'=>$id_tarifa];

            $sql="DELETE FROM tarifas_precioservicio WHERE id_tarifa=@id_tarifa AND id_servicio IN (".implode(",",$toDelete).")";

            $AqConexion_model->no_select($sql,$parametros);
        }
        if(count($registros)){
            $arraysDivididos = array_chunk($registros, 100);
            foreach($arraysDivididos as $regs) {
                $sql = "INSERT INTO tarifas_precioservicio(id_tarifa,id_servicio,pvp) VALUES ";
                $steps=[];
                foreach($regs as $index=>$reg){
                    $steps[]=" (".$id_tarifa.",".$reg['id_servicio'].",".$reg['pvp'].")";
                }
                $sql.=implode(",",$steps);
                $sql.=" ON DUPLICATE KEY UPDATE pvp=VALUE(pvp) ";
                $AqConexion_model->no_select($sql,[]);
            }

        }
        return 1;
    }

    public function actualizar_precios_tarifas($idServicio,$prices){
        $registros=[];
        $toDelete=[];
        foreach($prices as $id_tarifa => $price){
            if($price==""){
                $toDelete[]=$id_tarifa;
            }
            else{
                if(is_numeric($price)){
                    $registros[] = array('id_tarifa'=>$id_tarifa,'pvp'=>floatval($price));
                }
            }
        }
        $AqConexion_model = new AqConexion_model();
        if(count($toDelete)){
            $parametros=['id_servicio'=>$idServicio];

            $sql="DELETE FROM tarifas_precioservicio WHERE id_servicio=@id_servicio AND id_tarifa IN (".implode(",",$toDelete).")";
            $AqConexion_model->no_select($sql,$parametros);
        }

        if(count($registros)){
            $arraysDivididos = array_chunk($registros, 100);
            foreach($arraysDivididos as $regs) {
                $sql = "INSERT INTO tarifas_precioservicio(id_tarifa,id_servicio,pvp) VALUES ";
                $steps=[];
                foreach($regs as $index=>$reg){
                    $steps[]=" (".$reg['id_tarifa'].",".$idServicio.",".$reg['pvp'].")";
                }
                $sql.=implode(",",$steps);
                $sql.=" ON DUPLICATE KEY UPDATE pvp=VALUE(pvp) ";
                $AqConexion_model->no_select($sql,[]);
            }

        }
        return 1;
    }



}
?>