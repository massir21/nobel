<?php
/*

  CREATE TABLE `citas_observaciones` (
  `id_observacion` int(11) NOT NULL,
  `id_cita` int(11) NOT NULL,
  `fecha_observacion` timestamp,
  `observacion` text NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `id_usuario_creacion` int(11) NOT NULL,
  `fecha_modificacion` datetime NOT NULL,
  `id_usuario_modificacion` int(11) NOT NULL,
  `fecha_actualizacion` datetime NOT NULL,
  `id_usuario_actualizacion` int(11) NOT NULL,
  `borrado` tinyint(1) NOT NULL,
  `fecha_borrado` datetime NOT NULL,
  `id_usuario_borrado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `citas_observaciones`
  ADD PRIMARY KEY (`id_observacion`);
ALTER TABLE `citas_observaciones`
  MODIFY `id_observacion` int(11) NOT NULL AUTO_INCREMENT;


SET @max_id = (SELECT IFNULL(MAX(id_modulo), 0) FROM modulos);
SET @new_id = @max_id + 1;

INSERT INTO `modulos` (`id_modulo`, `nombre_modulo`, `url`, `padre`, `orden`, `orden_item`, `id_usuario_creacion`, `fecha_creacion`, `id_usuario_modificacion`, `fecha_modificacion`, `borrado`, `id_usuario_borrado`, `fecha_borrado`, `debug`)
        VALUES (@new_id, 'Parrilla', 'dietario/parrilla', 'Dietario', '10', '1', '1', '2024-02-18 23:00:00', '1', '2024-02-18 23:00:00', '0', NULL, NULL, '0');

 *
 */
class Observacionescita_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function getObservacionesCita($idcita)
    {
        $AqConexion_model = new AqConexion_model();
        $busqueda=' id_cita = @id_cita';
        $parametros=['id_cita'=>$idcita];

        $sql="SELECT citas_observaciones.*, ".
                " CONCAT(usuarios.nombre,CONCAT(' ',usuarios.apellidos)) AS creador_nombre ".
                " FROM citas_observaciones ".
                " INNER JOIN usuarios ON citas_observaciones.id_usuario_creacion = usuarios.id_usuario ".
                " WHERE citas_observaciones.borrado = 0 AND ".$busqueda;
        $sql.=" ORDER BY citas_observaciones.fecha_observacion DESC";
        $datos = $AqConexion_model->select($sql, $parametros);
        return $datos;

    }

    function guardar_observacion($parametros)
    {

        $AqConexion_model = new AqConexion_model();
        $registro=[];
        $idObservacion=null;
        if(isset($parametros['id_observacion'])) {
            $idObservacion=$parametros['id_observacion'];
        }
        if(isset($parametros['id_cita'])) $registro['id_cita'] = $parametros['id_cita'];
        if(isset($parametros['fecha_observacion'])) $registro['fecha_observacion'] = $parametros['fecha_observacion'];
        if(isset($parametros['observacion'])) $registro['observacion'] = $parametros['observacion'];


        if(!$idObservacion) {
            unset($registro['id_observacion']);
            $registro['fecha_creacion'] = date("Y-m-d H:i:s");
            $registro['id_usuario_creacion'] = $this->session->userdata('id_usuario');
            $registro['borrado'] = 0;
        }
        else{
            if(isset($parametros['borrado'])){
                $registro['borrado']=$parametros['borrado'] ? 1 : 0;
                if($registro['borrado']){
                    $registro['fecha_borrado']=date("Y-m-d H:i:s");
                    $registro['id_usuario_borrado']=$this->session->userdata('id_usuario');
                }
            }
        }
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');

        if($idObservacion) {
            $where['id_observacion'] = $idObservacion;

            $AqConexion_model->update('citas_observaciones', $registro, $where);
            return $idObservacion;
        }
        else{
            $AqConexion_model->insert('citas_observaciones', $registro);

            $sentenciaSQL = "select max(id_observacion) as id_observacion from citas_observaciones";
            $resultado = $AqConexion_model->select($sentenciaSQL, null);

            return $resultado[0]['id_observacion'];
        }
    }


}
