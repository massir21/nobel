<?php

class GestionFacturas_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function getListadoGestionFacturas($parametros = [])
    {
        $AqConexion_model = new AqConexion_model();

        $sql = "SELECT f.nota,f.id_doctor,f.check_descarga,f.id_gestion_facturas, f.documento_ruta, f.total_factura, f.fecha_factura, c.nombre_centro, ";
        $sql .= "tp.nombre as nombreProveedor, f.centro_id, f.id_proveedor, f.fecha_creacion ";
        $sql .= "FROM gestion_facturas f INNER JOIN centros c ON(f.centro_id = c.id_centro) ";
        $sql .= "INNER JOIN proveedores tp ON(f.id_proveedor = tp.id_proveedor) ";
        $sql .= "WHERE f.borrado = 0 ";

        if (isset($parametros['id_gestion_facturas']) && !empty($parametros['id_gestion_facturas'])) {
            $sql .= " AND f.id_gestion_facturas = @id_gestion_facturas ";
        }

        if (isset($parametros['id_centro']) && !empty($parametros['id_centro'])) {
            $sql .= " AND f.centro_id = @id_centro ";
        }

        if (isset($parametros['fecha_factura']) && !empty($parametros['fecha_factura'])) {
            $sql .= " AND f.fecha_factura = '@fecha_factura' ";
        }

        if (isset($parametros['id_proveedor']) && !empty($parametros['id_proveedor'])) {
            $sql .= " AND f.id_proveedor = @id_proveedor ";
        }

        if ((isset($parametros['fecha_factura_desde']) && !empty($parametros['fecha_factura_desde']))
            ) {
            $sql .= " AND (DATE_FORMAT(f.fecha_factura,'%Y-%m-%d') >= @fecha_factura_desde)  ";
        }

        if (isset($parametros['fecha_factura_hasta']) && !empty($parametros['fecha_factura_hasta'])) {
            $sql .= "  AND (DATE_FORMAT(f.fecha_factura,'%Y-%m-%d') <= @fecha_factura_hasta) ";
        }


      /*  if($_SERVER['REMOTE_ADDR']=='178.237.232.170'){
            var_dump($sql);
            var_dump($parametros);
            die();
        }*/

        $datos = $AqConexion_model->select($sql, $parametros);

        return $datos;
    }

    //Alfonso: mismo listado de facturas pero agrupado por familias
    function getListadoGestionFacturasFamilias($id_centro=NULL,$mes=NULL,$ano=NULL)
    {
        $this->db->select("gestion_facturas.id_proveedor,tipo_proveedores.id_tipo,tipo_proveedores.nombre as tipo, sum(total_factura) as total, '' as detalle")
                            ->join('proveedores', 'gestion_facturas.id_proveedor=proveedores.id_proveedor')
                            ->join('tipo_proveedores', 'proveedores.id_tipo_proveedor=tipo_proveedores.id_tipo')
                            ->where('gestion_facturas.borrado',0)
                            ->where('tipo_proveedores.id_tipo !=','13')
                            ->order_by('tipo_proveedores.orden')
                            ->group_by('tipo_proveedores.id_tipo');
        if ($id_centro!=NULL&&$id_centro!=0) { $this->db->where('centro_id',$id_centro); }
        if ($mes!=NULL) { $this->db->where('month(fecha_factura)',$mes); }
        if ($ano!=NULL) { $this->db->where('year(fecha_factura)',$ano); }
        $tipos = $this->db->get('gestion_facturas')->result();
        foreach($tipos as &$t){
            $proveedores = $this->db->where('proveedores.id_tipo_proveedor',$t->id_tipo)->get('proveedores')->result();
            $provs=Array(); foreach($proveedores as $p){ $provs[]=$p->id_proveedor;}
            $this->db->select("nombre_centro,nota,id_doctor,proveedores.nombre as proveedor, total_factura as total")
                    ->join('proveedores', 'gestion_facturas.id_proveedor=proveedores.id_proveedor')
                    ->join('centros', 'gestion_facturas.centro_id=centros.id_centro');
            if ($id_centro!=NULL&&$id_centro!=0) { $this->db->where('centro_id',$id_centro); }
            if ($mes!=NULL) { $this->db->where('month(fecha_factura)',$mes); }
            if ($ano!=NULL) { $this->db->where('year(fecha_factura)',$ano); }
            $this->db->where_in('gestion_facturas.borrado',0);
            $this->db->where_in('gestion_facturas.id_proveedor',$provs);
            $t->detalles = $this->db->get('gestion_facturas')->result();
        }
        return $tipos;
    }

    function nuevo_gestion_facturas($parametros, $file)
    {
        $AqConexion_model = new AqConexion_model();

        $registros = [];
        $registros['centro_id'] = $parametros['centro_id'];
        $registros['documento_ruta'] = $this->uploadFile($file, $parametros['centro_id']);
        $registros['total_factura'] = str_replace(",", ".", $parametros['total_factura']);
        $registros['fecha_factura'] = date("Y-m-d H:i:s", strtotime($parametros['fecha_factura']));
        $registros['borrado'] = 0;
        $registros['fecha_creacion'] = date("Y-m-d H:i:s");
        $registros['id_usuario_creacion'] = $this->session->userdata('id_usuario');
        $registros['fecha_actualizacion'] = date("Y-m-d H:i:s");
        $registros['id_usuario_actualizacion'] = $this->session->userdata('id_usuario');
        $registros['id_proveedor'] = $parametros['id_proveedor'];
        $registros['nota'] = $parametros['nota'];
        $registros['id_doctor'] = $parametros['id_doctor'];

        $AqConexion_model->insert('gestion_facturas', $registros);

        $sentenciaSQL = "select max(id_gestion_facturas) as id_gestion_facturas from gestion_facturas";
        $resultado = $AqConexion_model->select($sentenciaSQL, null);

        return $resultado[0]['id_gestion_facturas'];
    }

    function actualizar_gestion_facturas($parametros, $file)
    {
        $AqConexion_model = new AqConexion_model();

        $registros = [];
        $registros['centro_id'] = $parametros['centro_id'];
        if (isset($file) && !empty($file)) {
            if ($file['documento_ruta']['name'] !== '') {
                $registros['documento_ruta'] = $this->uploadFile($file, $parametros['centro_id']);
            }
        }
        $registros['total_factura'] = str_replace(",", ".", $parametros['total_factura']);
        $registros['fecha_factura'] = date("Y-m-d H:i:s", strtotime($parametros['fecha_factura']));
        $where['id_gestion_facturas'] = $parametros['id_gestion_facturas'];
        $registros['fecha_actualizacion'] = date("Y-m-d H:i:s");
        $registros['id_usuario_actualizacion'] = $this->session->userdata('id_usuario');
        $registros['id_proveedor'] = $parametros['id_proveedor'];
        $registros['nota'] = $parametros['nota'];
        $registros['id_doctor'] = $parametros['id_doctor'];

        $AqConexion_model->update('gestion_facturas', $registros, $where);

        return 1;
    }

    function borrar_facturas($parametros)
    {
        $data['borrado'] = 1;
        $data['id_usuario_borrado'] = $this->session->userdata('id_usuario');
        $data['fecha_borrado'] = date("Y-m-d H:i:s");
        $this->db->where('id_gestion_facturas',$parametros['id_gestion_facturas'])->update('gestion_facturas',$data);
        return 1;
    }

    function uploadFile($file, $centro_id)
    {

        $extension = pathinfo($file['documento_ruta']['tmp_name'], PATHINFO_EXTENSION);
        $nombre_limpio = $file['documento_ruta']['name'];
        $this->load->helper('global_helper');
        $nombre_limpio = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $nombre_limpio);
        $nombre_limpio = limpiar_string($nombre_limpio);
        $nombre_limpio = str_replace(' ', '_', $nombre_limpio);
        $final_name = $nombre_limpio;
        $directorioDestino = FCPATH . 'recursos/gestion/facturas/' . $centro_id . '/';
        if (!is_dir($directorioDestino)) {
            mkdir($directorioDestino, 0755, true);
        }
        move_uploaded_file($file['documento_ruta']['tmp_name'], $directorioDestino . $final_name);

        return 'gestion/facturas/' . $centro_id . '/' . $final_name;
    }

    function cambio_check_factura($id_factura,$check){
        $this->db->where('id_gestion_facturas',$id_factura)->update('gestion_facturas',Array('check_descarga'=>$check));
    }
}