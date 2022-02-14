<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Spam_pelanggan extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('spam_pelanggan_model');
        if ($this->session->userdata('status') !== 'loggedin') {
            redirect(site_url("admin/login"));
        }
    }

    public function index(){
        $this->load->helper('url');
        $this->load->view('admin/spam_pelanggan');
    }

    public function ajax_list(){
        $list = $this->spam_pelanggan_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $spam_pelanggan) {
            $no++;
            $row = array();
            $row[] = $no;
            //$row[] = $spam_pelanggan->id;
            $row[] = $spam_pelanggan->kd_sumber;
            $row[] = $spam_pelanggan->kd_cabang;
            $row[] = $spam_pelanggan->kd_spam;
            $row[] = $spam_pelanggan->kd_dma;
            $row[] = $spam_pelanggan->kd_wil;
            $row[] = $spam_pelanggan->nosamw;
            $row[] = $spam_pelanggan->keterangan;

            //add html for action
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person('."'".$spam_pelanggan->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
            <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_person('."'".$spam_pelanggan->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->spam_pelanggan_model->count_all(),
            "recordsFiltered" => $this->spam_pelanggan_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);

    }

    public function ajax_edit($id)
    {
        $data = $this->spam_pelanggan_model->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = array(
            'id' => $this->input->post('id'),
            'kd_sumber' => $this->input->post('kd_sumber'),
            'kd_cabang' => $this->input->post('kd_cabang'),
            'kd_kd_wil' => $this->input->post('kd_kd_wil'),
            'kd_dma' => $this->input->post('kd_dma'),
            'kd_wil' => $this->input->post('kd_wil'),
            'nosamw' => $this->input->post('nosamw'),
            'keterangan' => $this->input->post('keterangan'),
        );
        $insert = $this->spam_pelanggan_model->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_update()
    {
        $data = array(
           'id' => $this->input->post('id'),
           'kd_sumber' => $this->input->post('kd_sumber'),
           'kd_cabang' => $this->input->post('kd_cabang'),
           'kd_kd_wil' => $this->input->post('kd_kd_wil'),
           'kd_dma' => $this->input->post('kd_dma'),
           'kd_wil' => $this->input->post('kd_wil'),
           'nosamw' => $this->input->post('nosamw'),
           'keterangan' => $this->input->post('keterangan'),
       );
        $this->spam_pelanggan_model->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_delete($id)
    {
        $this->spam_pelanggan_model->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }

}