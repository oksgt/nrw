<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Spam_ipa_detail_model extends CI_Model {
 
    var $table = 'tb_spam_ipa_detail';
    var $view  = 'v_spam_ipa_detail';
    var $column_order = array('id','step', 'step_name', 'periode', 'id_spam_node','debit_produksi','debit_distribusi','air_terjual','kehilangan_air','jml_pelanggan','kapasitas_pompa','is_del','input_date','name'); //set column field database for datatable orderable
    var $column_search = array('id','step', 'step_name', 'periode',  'id_spam_node','debit_produksi','debit_distribusi','air_terjual','kehilangan_air','jml_pelanggan','kapasitas_pompa','is_del','input_date','name'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('input_date' => 'desc'); // default order 
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
    private function _get_datatables_query($node_id) {
        $this->db->where('id_spam_node', $node_id);
        $this->db->from($this->view);
        $i = 0;
     
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    function get_datatables($node_id)
    {
        $this->_get_datatables_query($node_id);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered($node_id)
    {
        $this->_get_datatables_query($node_id);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($node_id)
    {
        $this->db->where('id_spam_node', $node_id);
        $this->db->from($this->view);
        return $this->db->count_all_results();
    }
 
    public function get_by_id($id)
    {
        $this->db->from($this->view);
        $this->db->where('id',$id);
        $query = $this->db->get();
 
        return $query->row();
    }

    public function get_by($parameter)
    {
        $this->db->where($parameter);
        return $this->db->get($this->view);
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

    public function get_last_value($node_id){
        $sql = 'select periode as "Periode", 
        concat(debit_produksi, " l/dt", " | " , format(debit_produksi/1000*(86400*24*30), 0), " m3" ) as "Debit Produksi", 
        concat(debit_distribusi, " l/dt", " | " , format(debit_distribusi/1000*(86400*24*30), 0), " m3" ) as "Debit Distribusi", 
        concat(air_terjual, " l/dt", " | " , format(air_terjual/1000*(86400*24*30), 0), " m3" ) as "Air Terjual",
        concat(kehilangan_air, " l/dt", " | " , format(kehilangan_air/1000*(86400*24*30), 0), " m3" ) as "Kehilangan Air",
        concat(kapasitas_pompa, " l/dt", " | " , format(kapasitas_pompa/1000*(86400*24*30), 0), " m3" ) as "Kapasitas Pompa",
        format(jml_pelanggan, 0) as "Jumlah Pelanggan"
        from v_spam_ipa_detail vsid where id_spam_node = '.$node_id.' order by input_date desc limit 1';
        return $this->db->query($sql);
    }

}