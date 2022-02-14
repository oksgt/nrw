<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DetailKomponen extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Komponen_model');
        if ($this->session->userdata('status') !== 'loggedin') {
            redirect(site_url("admin/login"));
        }
    }

    public function index($id){
        $data_komponen = $this->Komponen_model->get_by(['id' => $id])->row_array();
        if($data_komponen['step'] == 1){
            redirect('intakedetail/'.$id, 'refresh');
        } else if($data_komponen['step'] == 2){
            redirect('ipadetail/'.$id, 'refresh');
        } else if($data_komponen['step'] == 3){
            redirect('rsvdetail/'.$id, 'refresh');
        } else if($data_komponen['step'] == 4){
            redirect('wildetail/'.$id, 'refresh');
        }  
    }
}