<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model('Spam_model');
        $this->load->helper('app_helper');
    }

    public function index(){
        $data['data_spam'] = $this->Spam_model->get_active_spam()->result();
        $this->load->view('admin/dashboard', $data);
    }
}