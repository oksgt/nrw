<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Flow extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model(['Spam_model', 'Komponen_model']);
    }

    // function _remap($param)
    // {
    //     $this->index($param);
    // }

    public function index()
    {
    }

    public function testing()
    {
        $this->load->view('admin/flow_3');
    }

    public function fetch_existing_node($node_id)
    {
        $existing_node = $this->Komponen_model->get_existing_node($node_id);
        $option = '<option value="x">-- Silahkan Pilih --</option>
        <option value=""></option>';
        if (!empty($existing_node->result())) {
            foreach ($existing_node->result() as $row) {
                if ($row->step == 5) {
                    $option_name = $row->step_name . ' - ' . $row->kode;
                } else {
                    $option_name = $row->step_name . ' - ' . $row->name;
                }
                $option .= '<option value="' . $row->id . '">' . $option_name . '</option>';
            }
        }

        echo $option;
    }

    public function load($id)
    {
        if ($id == "") {
            redirect('admin/spam');
        } else {
            $data['node'] = $this->Spam_model->get_spam_node($id)->result_array();
            if (empty($data)) {
                redirect('admin/spam');
            } else {
                $data['spam_name'] = $data['node'][0]['spam_name'];
                $data['template_db'] = $data['node'][0]['template'];
                $data['direction'] = $data['node'][0]['diagram_flow_direction'];
                $data['root']      = $id;
                $is_exist = $this->cek_existing_root($id);
                if ($is_exist) {
                    $data['step'] = $this->Komponen_model->get_step();
                } else {
                    $data['step'] = $this->Komponen_model->get_first_step();
                }
                // echo "<pre>";
                // print_r($data);
                // exit;
                $this->load->view('admin/flow_4', $data);
            }
        }
    }

    public function cek_existing_root($root)
    {
        $res = null;
        $data_existing = $this->Komponen_model->get_by(['root' => $root, 'is_del' => 0])->result();
        if (empty($data_existing)) {
            $res = false;
        } else {
            $res = true;
        }
        return $res;
    }



    function getnode($id)
    {
        $data = $this->Spam_model->get_spam_node($id)->result_array();
        echo json_encode($data);
    }

    function getNodeStructure($root)
    {
        $data = $this->Spam_model->getDataNodeByRoot($root)->result();
        echo json_encode($data);
    }

    function getNodeDetail($root)
    {
        $data = $this->Spam_model->getDataNodeDetailByRoot($root)->result();
        $result = [];
        foreach ($data as $key => $value) {
            $result[$value->id] = ['trad' => $value->step_name . ' _ ' . $value->name, 'styles' => ['box-shadow' => '0 0 5px 1px #f8f9fa']];
        }
        echo json_encode($result, JSON_PRETTY_PRINT);
    }

    function getNodeName($param)
    {
        $data = $this->Spam_model->getData($param)->row_array();
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}
