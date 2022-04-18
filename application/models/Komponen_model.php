<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Komponen_model extends CI_Model
{

    var $table = 'tb_spam_node';
    var $view  = 'view_spam_node';
    var $column_order = array('id', 'root', 'input_port', 'output_port', 'kode', 'spam_name', 'pid', 'parent', 'parent_step', 'parent_step_name', 'step', 'step_name', 'name', 'img', 'url', 'is_del'); //set column field database for datatable orderable
    var $column_search = array('id', 'root', 'input_port', 'output_port', 'kode', 'spam_name', 'pid', 'parent', 'parent_step', 'parent_step_name', 'step', 'step_name', 'name', 'img', 'url', 'is_del'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('id' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query($root)
    {
        $this->db->where('root', $root);
        $this->db->where('is_del', 0);
        $this->db->where('step != 999');
        $this->db->from($this->view);

        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables($root)
    {
        $this->_get_datatables_query($root);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($root)
    {
        $this->_get_datatables_query($root);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($root)
    {
        $this->db->where('root', $root);
        $this->db->where('is_del', 0);
        $this->db->from($this->view);
        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        $this->db->from($this->view);
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    public function get_by($parameter)
    {
        $this->db->where($parameter);
        return $this->db->get($this->view);
    }

    public function get_table_by($parameter)
    {
        $this->db->where($parameter);
        return $this->db->get($this->table);
    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($object, $where)
    {
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

    public function soft_delete_by_id($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function delete_by_id($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }

    public function get_existing_node($root)
    {
        $this->db->where('root', $root);
        $this->db->where('is_del', 0);
        // $this->db->where('step != 999'); 
        return $this->db->get($this->view);
    }

    public function get_step()
    {
        $this->db->where('is_del', 0);
        return $this->db->get('tb_spam_step');
    }

    public function get_first_step()
    {
        $this->db->where('is_del', 0);
        $this->db->where('is_first', 1);
        return $this->db->get('tb_spam_step');
    }

    public function _uploadImage()
    {
        if (empty($_FILES["imgInp"]["name"])) {
            $result = array(
                'status'           => true,
                'message'            => "Tidak ada gambar yg di upload",
                'original_image' => null,
            );
            return $result;
        } else {
            $config['upload_path']   = './assets/gambar/';
            $config['allowed_types'] = 'jpg|png|jpeg';
            // $config['max_size']      = '1024';

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('imgInp')) {
                $result = array(
                    'status'          => true,
                    'message'          => "Upload Berhasil",
                    'original_image'  => $this->upload->data("file_name"),
                );
                return $result;
            } else {
                $error = $this->upload->display_errors('', '');
                $result = array(
                    'status'          => false,
                    'message'          => $error,
                    'original_image'  => null
                );
                return $result;
            }
        }
    }

    public function remove_existing_foto($node_id)
    {
        $this->db->where('node_id', $node_id);
        return $this->db->delete('image_upload');
    }

    public function save_upload($data)
    {
        $this->db->insert('image_upload', $data);
        return $this->db->insert_id();
    }

    public function get_image($node_id)
    {
        $this->db->where('node_id', $node_id);
        return $this->db->get('image_upload');
    }

    public function getByKode($kode)
    {
        $this->db->where('kode', $kode);
        // $this->db->delete($this->table);
    }

    public function getNextStep($id = "")
    {
        // $this->db->where('id >= ', $id);
        // $this->db->where('is_last != 1');
        $this->db->order_by('name', 'asc');
        return  $this->db->get('tb_spam_step');
    }
}
