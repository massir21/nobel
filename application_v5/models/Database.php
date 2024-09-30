<?php defined('BASEPATH') or exit('No direct script access allowed');
class Database extends CI_Model
{

    public function __construct()
    {

    }

    private function paginate($param)
    {
        if (!isset($param['page'])) {
            $param['page'] = 1;
        }
        if (!isset($param['limit'])) {
            $param['limit'] = 5000;
        }

        if ($param['limit'] > 0) {
            $desde = $param['limit'] * ($param['page'] - 1);
            $this->db->limit($param['limit'], $desde);
        }

    }

    public function get($tabla)
    {
        return $this->db->get($tabla)->result();
    }

    public function getWhere($param, $nopage = '', $object = true)
    {
        // PAGINACIÓN
        if ($nopage == '') {
            $this->paginate($param);
        }

        if (isset($param['where']) && count($param['where']) > 0) {
            foreach ($param['where'] as $key => $value) {
                $this->db->where($key, $value);
            }
        }

        if (isset($param['order_by']) && count($param['order_by']) > 0) {
            foreach ($param['order_by'] as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }
        if($object == true){
            return $this->db->get($param['tabla'])->result();
        }else{
            return $this->db->get($param['tabla'])->result_array();
        }
       
    }

    public function insert($tabla, $datos)
    {
        $id = $this->db->insert($tabla, $datos);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function actualizar($tabla, $datos, $id_field, $id)
    {
        $this->db->where($id_field, $id);
        $this->db->update($tabla, $datos);
        if ($this->db->affected_rows() > 0) {
            return $id;
        } else {
            return $this->db->error();
        }
    }

    public function buscarDato($tabla, $column, $value)
    {
        $this->db->where($column, $value);
        $row = $this->db->get($tabla);

        if ($row->num_rows() > 1) {
            return $row->result();
        } elseif ($row->num_rows() == 1) {
            return $row->row();
        } else {
            return false;
        }

    }
}