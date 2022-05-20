<?php defined('BASEPATH') or exit('No direct script access allowed');

class Logger_model extends CI_Model
{
    private $_table = "v_m";

    public function getAll()
    {
        return $this->db->get($this->_table)->result();
    }

    public function getById($id)
    {
        return $this->db->get_where($this->_table, ["no" => $id])->row();
    }

    public function get_data_log($kode, $tanggal = "")
    {
        $qry = "select debit, tekanan, updatedindb from log_inbox li where kode like '%" . $kode . "' ";
        $qry .= " and updatedindb like '%" . $tanggal . "%'";
        return $this->db->query($qry)->result();
    }

    public function get_lokasi_log($kode)
    {
        $qry = "select kode, lokasi from m_mendoan where kode = 'M." . $kode . "'";
        return $this->db->query($qry)->row_array();
    }

    // public function save()
    // {
    //     $post = $this->input->post();
    //     $this->product_id = uniqid();
    //     $this->name = $post["name"];
    //     $this->price = $post["price"];
    //     $this->description = $post["description"];
    //     return $this->db->insert($this->_table, $this);
    // }

    // public function update()
    // {
    //     $post = $this->input->post();
    //     $this->product_id = $post["id"];
    //     $this->name = $post["name"];
    //     $this->price = $post["price"];
    //     $this->description = $post["description"];
    //     return $this->db->update($this->_table, $this, array('product_id' => $post['id']));
    // }

    // public function delete($id)
    // {
    //     return $this->db->delete($this->_table, array("product_id" => $id));
    //}
}
