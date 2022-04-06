<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Step extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model('Step_model');
        $this->load->helper('app_helper');
    }

    public function index(){
        if ($this->session->userdata('status') !== 'loggedin') {
            redirect(site_url("admin/login"));
        }
        $this->load->view('admin/step');
    }

    public function ajax_list()
    {
        $list = $this->Step_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $spam) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $spam->name;

            //add html for action
            $row[] = '
            <a class="btn btn-sm btn-warning" href="javascript:void(0)" title="Edit" onclick="detail(\'' . $spam->id . '\')"><i class="fas fa-edit"></i> Edit</a>
            <a class="btn btn-sm btn-outline-danger border-0" href="javascript:void(0)" title="Hapus" onclick="hapus_data(\'' . $spam->id . '\')"><i class="fas fa-trash"></i></a>';
            $data[] = $row;
            // $no++;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Step_model->count_all(),
            "recordsFiltered" => $this->Step_model->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function validation()
    {
        $this->form_validation->set_rules('input_step', 'Step Name', 'required', array('required' => 'Wajib Diisi'));

        if ($this->form_validation->run()) {
            $array = array('success' => true);
        } else {
            $array = array(
                'error' => true,
                'input_step_error_detail'   => form_error('input_step', '<b class="fa fa-exclamation-triangle"></b> ', ' '),
            );
        }
        echo json_encode($array);
    }

    public function insert()
    {
        $data = array(
            'name'                   => $this->input->post('input_step'),
            'is_del'                 => 0
        );
        $inserted = $this->Step_model->save($data);
        if ($inserted) {
            $result = array('status' => true);
        } else {
            $result = array('status' => false);
        }
        echo json_encode($result);
    }

    function detail($id = "")
    {
        $data = $this->Step_model->get_by(array('id' => $id))->row_array();
        echo json_encode($data);
    }

    public function update()
    {
        $object = array(
            'name' => $this->input->post('input_step', true)
        );

        $existing_data = $this->Step_model->get_by(array('ID' => $this->input->post('id', true)))->row_array();
        unset($existing_data["id"]);
        unset($existing_data["is_del"]);

        $edited_data = array_diff_assoc($object, $existing_data);

        if (!empty($edited_data)) { //if there is any edited data
            $updated = $this->Step_model->update($object, array('ID' => $this->input->post('id', true)));
            if ($updated) {
                $result = array('status' => true);
            } else {
                $result = array('status' => false);
            }
        } else {
            $result = array('status' => true);
        }
        echo json_encode($result);
    }

    public function delete($id)
    {
        $object = array(
            'is_del'         => 1
        );

        $where = array('id' => $id);
        $affected_row = $this->Step_model->update($object, $where);
        $result = array('status' => true);
        echo json_encode($result);
    }
}