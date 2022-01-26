<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kartu extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('kartu_model');
    }

    public function index(){
        $this->load->helper('url');
        $this->load->view('admin/kartu_view');
    }

    public function ajax_list(){
        $list = $this->kartu_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $kartu) {
            $no++;
            $row = array();
            $row[] = $kartu->no_hp;
            $row[] = $kartu->lokasi_pasang;
            $row[] = $kartu->keterangan;

            //add html for action
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person('."'".$kartu->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
            <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_person('."'".$kartu->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->kartu->count_all(),
            "recordsFiltered" => $this->kartu->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id)
    {
        $data = $this->kartu->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = array(
            'firstName' => $this->input->post('firstName'),
            'lastName' => $this->input->post('lastName'),
            'gender' => $this->input->post('gender'),
            'address' => $this->input->post('address'),
            'dob' => $this->input->post('dob'),
        );
        $insert = $this->kartu->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_update()
    {
        $data = array(
            'firstName' => $this->input->post('firstName'),
            'lastName' => $this->input->post('lastName'),
            'gender' => $this->input->post('gender'),
            'address' => $this->input->post('address'),
            'dob' => $this->input->post('dob'),
        );
        $this->kartu->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_delete($id)
    {
        $this->kartu->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }

}