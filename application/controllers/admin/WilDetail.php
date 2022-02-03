<?php
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') or exit('No direct script access allowed');

class WilDetail extends CI_Controller
{

    private $node_id;

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model('Spam_wil_detail_model');
        $this->load->model('Komponen_model');
        $this->load->helper('app_helper');
    }

    public function index($id)
    {
        $this->node_id = $id;
        $data['komponen'] = $this->Komponen_model->get_by(['id' => $id])->row_array();
        $data['node_id']  = $id;
        // echo "<pre>";
        // print_r($data); die;
        $this->load->view('admin/wildetail_view', $data);
    }

    public function ajax_list($node_id)
    {
        $list = $this->Spam_wil_detail_model->get_datatables($node_id);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $r) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = format_month_year($r->periode);
            $row[] = $r->input_sistem . " l/dt ( " . conver_ldt_m3($r->input_sistem) . " ) ";
            $row[] = rupiah($r->air_terjual) . " &#13221; ";
            $row[] = rupiah($r->kehilangan_air). " &#13221; ";
            $row[] = rupiah($r->jml_pelanggan) . " SR ";
            $row[] = formatTglIndo_datetime($r->input_date);

            $row[] = '
            <a class="btn btn-sm btn-warning" href="javascript:void(0)" title="Edit" onclick="detail(\'' . $r->id . '\')"><i class="fas fa-edit"></i> Edit</a>
            <a class="btn btn-sm btn-outline-danger border-0" href="javascript:void(0)" title="Hapus" onclick="hapus_data(\'' . $r->id . '\')"><i class="fas fa-trash"></i></a>
            ';
            $data[] = $row;
            // $no++;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Spam_wil_detail_model->count_all($node_id),
            "recordsFiltered" => $this->Spam_wil_detail_model->count_filtered($node_id),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function validation()
    {
        $this->form_validation->set_rules('input_sistem', 'Input Sistem', 'required', array('required' => 'Wajib Diisi'));
        $this->form_validation->set_rules('input_periode', 'Periode', 'required', array('required' => 'Wajib Diisi'));
        $this->form_validation->set_rules('input_air_terjual', 'Air Terjual', 'required', array('required' => 'Wajib Diisi'));
        $this->form_validation->set_rules('input_kehilangan_air', 'Kehilangan Air', 'required', array('required' => 'Wajib Diisi'));
        $this->form_validation->set_rules('input_jml_pelanggan', 'Jumlah Pelanggan', 'required', array('required' => 'Wajib Diisi'));
        
        if ($this->form_validation->run()) {
            $array = array('success' => true);
        } else {
            $array = array(
                'error' => true,
                'input_sistem_error_detail'             => form_error('input_sistem', '<b class="fa fa-exclamation-triangle"></b> ', ' '),
                'input_air_terjual_error_detail'        => form_error('input_air_terjual', '<b class="fa fa-exclamation-triangle"></b> ', ' '),
                'input_kehilangan_air_error_detail'     => form_error('input_kehilangan_air', '<b class="fa fa-exclamation-triangle"></b> ', ' '),
                'input_jml_pelanggan_error_detail'      => form_error('input_jml_pelanggan', '<b class="fa fa-exclamation-triangle"></b> ', ' '),
                'input_periode_error_detail'             => form_error('input_periode', '<b class="fa fa-exclamation-triangle"></b> ', ' ')
            );
        }
        echo json_encode($array);
    }

    public function validasi_pilih($str)
    {
        if ($str == 'x') {
            $this->form_validation->set_message('validasi_pilih', '<b class="fa fa-exclamation-triangle"></b> Silahkan Pilih');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function insert()
    {
        $data = array(
            'id_spam_node'      => $this->input->post('input_spam_node'),
            'input_sistem'      => $this->input->post('input_sistem'),
            'periode'           => $this->input->post('input_periode'),
            'air_terjual'       => $this->input->post('input_air_terjual'),
            'kehilangan_air'    => $this->input->post('input_kehilangan_air'),
            'jml_pelanggan'     => $this->input->post('input_jml_pelanggan'),
            'input_date'        => Date('Y-m-d H:i:s'),
            'is_del'            => 0,
        );
        $inserted = $this->Spam_wil_detail_model->save($data);
        if ($inserted) {
            $result = array('status' => true);
        } else {
            $result = array('status' => false);
        }
        echo json_encode($result);
    }

    function detail($id = "")
    {
        $data = $this->Spam_wil_detail_model->get_by(array('id' => $id))->row_array();
        echo json_encode($data);
    }

    public function update()
    {
        $object = array(
            'id_spam_node'      => $this->input->post('input_spam_node'),
            'input_sistem'      => $this->input->post('input_sistem'),
            'air_terjual'       => $this->input->post('input_air_terjual'),
            'periode'           => $this->input->post('input_periode'),
            'kehilangan_air'    => $this->input->post('input_kehilangan_air'),
            'jml_pelanggan'     => $this->input->post('input_jml_pelanggan'),
            'input_date'        => Date('Y-m-d H:i:s'),
            'is_del'            => 0,
        );

        $existing_data = $this->Spam_wil_detail_model->get_by(array('ID' => $this->input->post('id', true)))->row_array();
        unset($existing_data["id"]);
        unset($existing_data["id_spam_node"]);
        unset($existing_data["input_date"]);
        unset($existing_data["is_del"]);

        $edited_data = array_diff_assoc($object, $existing_data);

        if (!empty($edited_data)) { //if there is any edited data
            $updated = $this->Spam_wil_detail_model->update($object, array('ID' => $this->input->post('id', true)));
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
        $affected_row = $this->Spam_wil_detail_model->update($object, $where);
        $result = array('status' => true);
        echo json_encode($result);
    }

    function upload(){
        $file_name = $this->Komponen_model->_uploadImage();
        if ($file_name['status']) {
            $object = [
                'path'     => $file_name['original_image'],
                'node_id'  => $this->input->post('input_spam_node'),
            ];

            $file = './assets/gambar/'.$this->input->post('old_img');
        
            if($this->input->post('old_img') !== 'no-image.png'){
                unlink($file);
            }

            $this->Komponen_model->remove_existing_foto($this->input->post('input_spam_node'));
            $inserted = $this->Komponen_model->save_upload($object);
            if ($inserted) {
                $result = array('status' => true);
            } else {
                $result = array('status' => false);
            }
            echo json_encode($result);
        }
    }
}
