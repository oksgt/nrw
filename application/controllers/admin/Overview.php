<?php

class Overview extends CI_Controller {
    public function __construct()
    {
		parent::__construct();
		$this->load->model('Spam_model');
        if ($this->session->userdata('status') !== 'loggedin') {
            redirect(site_url("admin/login"));
        }
	}


    // public function index(){
	// 	$id = 1;
    //     if($id == ""){
    //         redirect('admin/spam');
    //     } else {
    //         $data = $this->Spam_model->get_spam_node($id)->result_array();
    //         if(empty($data)){
    //             redirect('admin/spam');
    //         } else {
    //             $data['spam_name'] = $data[0]['spam_name'];
    //             $data['direction'] = $data[0]['diagram_flow_direction'];
    //             $data['root']      = $id;
    //             $this->load->view('admin/flow_view', $data);
    //         }
    //     }
    // }

	public function index()
	{
        // load view admin/overview.php
        // $this->load->view("admin/overview");
		// redirect('admin/flow/1');
		$id = 1;
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
                $this->load->view('admin/overview', $data);
            }
        }
	}
}