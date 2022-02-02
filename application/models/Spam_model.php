<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Spam_model extends CI_Model {
 
    var $table = 'tb_spam';
    var $column_order = array('id','name','desc', 'diagram_flow_direction', 'is_del'); //set column field database for datatable orderable
    var $column_search = array('id','name','desc', 'diagram_flow_direction', 'is_del'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('name' => 'desc'); // default order 
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
    private function _get_datatables_query() {
        $this->db->where('is_del', 0);
        $this->db->from($this->table);
 
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
 
    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->where('is_del', 0);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
 
    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('id',$id);
        $query = $this->db->get();
 
        return $query->row();
    }

    public function get_by($parameter)
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

    public function get_spam_node($id){
        $qry = '
        select node.id, node.root, spam.name as spam_name, spam.diagram_flow_direction, 
        node.pid, node.step, tss.name as step_name, node.name, node.img, node.url from tb_spam_node as node 
        inner join tb_spam as spam on node.root = spam.id 
        left join tb_spam_step tss on node.step = tss.id
        where node.is_del = 0 and node.root = ' . $id;
        // $this->db->where('root', $id);
        return $this->db->query($qry);
    }
 
    public function getDataNodeByRoot($id){
        $this->db->where('root', $id);
        $this->db->where('is_del', 0);
        $this->db->select('id, pid');
        $this->db->order_by('id', 'asc');
        $data = $this->db->get("tb_spam_node");
        return $data;
    }

    public function getDataNodeDetailByRoot($id){
        $qry = '
        select node.id, node.root, node.parent_step_kode, spam.name as spam_name, spam.diagram_flow_direction, node.kode, 
        node.pid, node.step, tss.name as step_name, node.name, tss.img , node.url from view_spam_node as node 
        inner join tb_spam as spam on node.root = spam.id 
        left join tb_spam_step tss on node.step = tss.id
        where node.is_del = 0 and node.root = ' . $id;
        // $this->db->where('root', $id);
        return $this->db->query($qry);
    }

    public function getDataNode($id){
        $this->db->where('id', $id);
        $this->db->where('is_del', 0);
        $data = $this->db->get("view_spam_node");
        return $data;
    }

    public function get_data_logger($id){
        $sql = "select li.id, li.debit, li.tekanan, li.updatedindb,
        concat_ws('.', Substring(li.kode, 2, 1), Substring(li.kode, 9, 3)) as new_kode, mm.DEBIT_NORMAL, mm.TEKANAN_NORMAL
        from log_inbox li 
        inner join m_mendoan mm on mm.KODE = concat_ws('.', Substring(li.kode, 2, 1), Substring(li.kode, 9, 3)) 
        where mm.KODE = '".$id."' order by updatedindb desc limit 1";
        return $this->db->query($sql);
    }

}