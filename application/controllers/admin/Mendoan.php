<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mendoan extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('m_mendoan_model');
    }

    public function index(){
        $this->load->helper('url');
        $this->load->view('admin/master_mendoan');
    }

    public function ajax_list(){
        $list = $this->m_mendoan_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $mendoan) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $mendoan->KODE;
            $row[] = $mendoan->LOKASI;
            $row[] = $mendoan->DEBIT_NORMAL;
            $row[] = $mendoan->TEKANAN_NORMAL;
            $row[] = $mendoan->DIAMETER_PIPA;
            $row[] = $mendoan->SPAM;
            $row[] = $mendoan->CABANG_LAYANAN;
            $row[] = $mendoan->JENIS_LAYANAN;

            //add html for action
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person('."'".$mendoan->NO."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
            <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_person('."'".$mendoan->NO."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->m_mendoan_model->count_all(),
            "recordsFiltered" => $this->m_mendoan_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);

    }

    public function ajax_edit($id)
    {
        $data = $this->m_mendoan_model->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = array(
            'KODE' => $this->input->post('KODE'),
            'LOKASI' => $this->input->post('LOKASI'),
            'DEBIT_NORMAL' => $this->input->post('DEBIT_NORMAL'),
            'TEKANAN_NORMAL' => $this->input->post('TEKANAN_NORMAL'),
            'DIAMETER_PIPA' => $this->input->post('DIAMETER_PIPA'),
            'SPAM' => $this->input->post('SPAM'),
            'CABANG_LAYANAN' => $this->input->post('CABANG_LAYANAN'),
            'JENIS_LAYANAN' => $this->input->post('JENIS_LAYANAN'),
        );
        $insert = $this->m_mendoan_model->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_update()
    {
        $data = array(
           'KODE' => $this->input->post('KODE'),
           'LOKASI' => $this->input->post('LOKASI'),
           'DEBIT_NORMAL' => $this->input->post('DEBIT_NORMAL'),
           'TEKANAN_NORMAL' => $this->input->post('TEKANAN_NORMAL'),
           'DIAMETER_PIPA' => $this->input->post('DIAMETER_PIPA'),
           'SPAM' => $this->input->post('SPAM'),
           'CABANG_LAYANAN' => $this->input->post('CABANG_LAYANAN'),
           'JENIS_LAYANAN' => $this->input->post('JENIS_LAYANAN'),
       );
        $this->m_mendoan_model->update(array('NO' => $this->input->post('NO')), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_delete($id)
    {
        $this->m_mendoan_model->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }

}