<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Flowkomponen extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model('Spam_model');
        $this->load->model('Komponen_model');
    }

    public function load($id)
    {
        if ($id == "") {
            redirect('admin');
        } else {
            $data_spam = $this->Spam_model->get_by_id($id);
            $data['spam'] = $data_spam;
            $data['root'] = $id;
            $data['existing_node'] = $this->Komponen_model->get_existing_node($id);

            $is_exist = $this->cek_existing_root($id);
            if($is_exist){
                $data['step'] = $this->Komponen_model->get_step();
            } else {
                $data['step'] = $this->Komponen_model->get_first_step();
            }

            $this->load->view('admin/komponen_view', $data);
        }
    }

    public function fetch_existing_node($id){
        $existing_node = $this->Komponen_model->get_existing_node($id);
        $option = '<option value="x">-- Silahkan Pilih --</option>
        <option value=""></option>';
        if(!empty($existing_node->result())){
            foreach ($existing_node->result() as $row) { 
                if($row->step == 5){
                    $option_name = $row->step_name . ' - ' . $row->kode;
                } else {
                    $option_name = $row->step_name . ' - ' . $row->name ;
                }
                $option .= '<option value="'.$row->id.'">'.$option_name.'</option>';
            }
        }

        echo $option;
    }

    public function ajax_list($root)
    {
        $list = $this->Komponen_model->get_datatables($root);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $spam) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $spam->kode;
            if($spam->step == 5){
                $row[] = $spam->step_name . ' - ' . $spam->kode;
            } else {
                $row[] = $spam->step_name . ' - ' . $spam->name;
            }

            if($spam->parent_step == 5){
                $row[] = $spam->parent_step_name . ' - ' . $spam->parent_step_kode;
            } else {
                $row[] = $spam->parent_step_name . ' - ' . $spam->parent;
            }
            // $row[] = $spam->parent_step_name . ' - ' . $spam->parent;

            $string = strip_tags($spam->url);
            if (strlen($string) > 20) {

                // truncate string
                $stringCut = substr($string, 0, 20);
                $endPoint = strrpos($stringCut, ' ');

                //if the string doesn't contain any space then it will cut without word basis.
                $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                $string .= '...';
            }
            
            // if($spam->url !== "" || $spam->url !== "-")
            $button_url = "<i class='text-info'>$string</i>" ." <a class='btn btn-sm btn-info' href='". prep_url($spam->url)."' alt='Broken Link' target='_blank'><i class='fa fa-link'></i></a>";

            $row[] = $button_url;
            // $row[] = $spam->step_name;

            //add html for action
            if($spam->step == 5){
                $row[] = '
                <a class="btn btn-sm btn-warning" href="javascript:void(0)" title="Edit" onclick="detail(\'' . $spam->id . '\')"><i class="fas fa-edit"></i> Edit</a>
                <a class="btn btn-sm btn-outline-danger border-0" href="javascript:void(0)" title="Hapus" onclick="hapus_data(\'' . $spam->id . '\')"><i class="fas fa-trash"></i></a>';
            } else {
                // $row[] = $spam->parent_step_name . ' - ' . $spam->parent;
                $row[] = '
                <a class="btn btn-sm btn-info" href="'.base_url('index.php/').'detailkomponen/'.$spam->id.'" title="Nilai Parameter"><i class="fas fa-list"></i></a>
                <a class="btn btn-sm btn-warning" href="javascript:void(0)" title="Edit" onclick="detail(\'' . $spam->id . '\')"><i class="fas fa-edit"></i> Edit</a>
                <a class="btn btn-sm btn-outline-danger border-0" href="javascript:void(0)" title="Hapus" onclick="hapus_data(\'' . $spam->id . '\')"><i class="fas fa-trash"></i></a>';
            }
            
            $data[] = $row;
            // $no++;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Komponen_model->count_all($root),
            "recordsFiltered" => $this->Komponen_model->count_filtered($root),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function validation()
    {
        // $this->form_validation->set_rules('input_nama_komponen', 'SPAM Name', 'required', array('required' => 'Wajib Diisi'));
        $this->form_validation->set_rules('input_parent', 'Parent', 'callback_validasi_pilih');
        $this->form_validation->set_rules('input_step', 'Step', 'callback_validasi_pilih');

        if ($this->form_validation->run()) {
            $array = array('success' => true);
        } else {
            $array = array(
                'error' => true,
                // 'input_nama_komponen_error_detail'   => form_error('input_nama_komponen', '<b class="fa fa-exclamation-triangle"></b> ', ' '),
                'input_parent_error_icon'  => form_error('input_parent', '', ''),
                'input_step_error_icon'  => form_error('input_step', '', ''),
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

    public function validasi_kode($str)
    {
        $data = $this->Komponen_model->getByKode($str)->row_array();
        if (!empty($data)) {
            $this->form_validation->set_message('validasi_kode', '<b class="fa fa-exclamation-triangle"></b> Kode Logger '.$str.' Sudah Ada');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function insert()
    {
        $data = array(
            'root'      => $this->input->post('root'),
            'pid'       => ($this->input->post('input_parent') == "x") ? 0 : $this->input->post('input_parent'),
            'step'      => $this->input->post('input_step'),
            'desc'      => '-',
            'name'      => $this->input->post('input_nama_komponen'),
            'kode'      => $this->input->post('input_kode'),
            'img'       => '-',
            'url'       => $this->input->post('input_url'),
            'is_del'    => 0,
        );
        $inserted = $this->Komponen_model->save($data);
        if ($inserted) {
            $result = array('status' => true, 'id' => $inserted);
        } else {
            $result = array('status' => false);
        }
        echo json_encode($result);
    }

    function detail($id = "")
    {
        $data = $this->Komponen_model->get_by(array('id' => $id))->row_array();
        echo json_encode($data);
    }

    public function update()
    {
        $object = array(
            'pid'       => $this->input->post('input_parent'),
            'step'      => $this->input->post('input_step'),
            'name'      => $this->input->post('input_nama_komponen'),
            'kode'      => $this->input->post('input_kode'),
            'url'       => $this->input->post('input_url')
        );

        $existing_data = $this->Komponen_model->get_by(array('ID' => $this->input->post('id', true)))->row_array();
        unset($existing_data["id"]);
        unset($existing_data["root"]);
        unset($existing_data["desc"]);
        unset($existing_data["img"]);
        unset($existing_data["is_del"]);

        $edited_data = array_diff_assoc($object, $existing_data);

        if (!empty($edited_data)) { //if there is any edited data
            $updated = $this->Komponen_model->update($object, array('ID' => $this->input->post('id', true)));
            if ($updated) {
                $result = array('status' => true, 'id' => $this->input->post('id', true));
            } else {
                $result = array('status' => false);
            }
        } else {
            $result = array('status' => true, 'id' => $this->input->post('id', true));
        }
        echo json_encode($result);
    }

    public function delete($id)
    {
        $object = array(
            'is_del'         => 1
        );

        $where = array('id' => $id);
        $affected_row = $this->Komponen_model->update($object, $where);
        $result = array('status' => true);
        echo json_encode($result);
    }

    public function cek_existing_root($root){
        $res = null;
        $data_existing = $this->Komponen_model->get_by(['root' => $root, 'is_del' => 0])->result();
        if(empty($data_existing)){
            $res = false;
        } else {
            $res = true;
        }
        return $res;
    }
}
