<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login_model extends CI_Model
{

    var $table = 'user';
    var $view = 'user';
    var $column_order = array('id', 'username', 'password');
    var $column_search = array('id', 'username', 'password');
    var $order = array('id' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }

    public function cek_login($table, $where)
    {
        return $this->db->get_where($table, $where);
    }

    public function query($sql)
    {
        return $this->db->query($sql);
    }
    public function get_data()
    {
        return $this->db->get($this->view);
    }

    public function save($object)
    {
        $this->db->insert($this->table, $object);
        return $this->db->insert_id();
    }

    public function detail($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->table);
    }

    public function update($object, $where)
    {
        // return $this->db->affected_rows();
        $this->db->trans_start();
        $this->db->where($where);
        $this->db->update($this->table, $object);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }

    public function get_by($parameter)
    {
        $this->db->where($parameter);
        return $this->db->get($this->view);
    }
}
