<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Flow extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model('Spam_model');
    }

    // function _remap($param)
    // {
    //     $this->index($param);
    // }

    public function index(){
        
    }

    public function load($id){
        if($id == ""){
            redirect('admin/spam');
        } else {
            $data = $this->Spam_model->get_spam_node($id)->result_array();
            if(empty($data)){
                redirect('admin/spam');
            } else {
                $data['spam_name'] = $data[0]['spam_name'];
                $data['direction'] = $data[0]['diagram_flow_direction'];
                $data['root']      = $id;
                $this->load->view('admin/flow_view', $data);
            }
        }
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
			$result[$value->id] = ['trad' => $value->step_name .' _ '.$value->name, 'styles' => ['box-shadow' => '0 0 5px 1px #f8f9fa']];
		}
		echo json_encode($result, JSON_PRETTY_PRINT);
	}

	function getNodeName($param){
		$data = $this->Spam_model->getData($param)->row_array();
		echo json_encode($data, JSON_PRETTY_PRINT);
	}

}
