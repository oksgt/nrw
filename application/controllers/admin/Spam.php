<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Spam extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->helper('app_helper');
        $this->load->model([
            'Spam_model', 'Spam_intake_detail_model', 'Spam_rsv_detail_model', 'Spam_ipa_detail_model', 'Spam_wil_detail_model',
            'Komponen_model'
        ]);
    }

    public function index()
    {
        $this->load->view('admin/spam_view');
    }

    public function ajax_list()
    {
        $list = $this->Spam_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $spam) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $spam->name;

            //add html for action
            $row[] = '<a class="btn btn-sm btn-success" href="'.base_url('index.php/').'flow/'.$spam->id.'" title="Lihat Diagram" ><i class="fas fa-project-diagram"></i> Lihat Diagram</a>
            <a class="btn btn-sm btn-primary" href="'.base_url('index.php/').'flowkomponen/'.$spam->id.'" title="Komponen Diagram"><i class="fas fa-list"></i> Komponen Diagram</a>
            <a class="btn btn-sm btn-warning" href="javascript:void(0)" title="Edit" onclick="detail(\'' . $spam->id . '\')"><i class="fas fa-edit"></i> Edit</a>
            <a class="btn btn-sm btn-outline-danger border-0" href="javascript:void(0)" title="Hapus" onclick="hapus_data(\'' . $spam->id . '\')"><i class="fas fa-trash"></i></a>';
            $data[] = $row;
            // $no++;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Spam_model->count_all(),
            "recordsFiltered" => $this->Spam_model->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function validation()
    {
        $this->form_validation->set_rules('input_spam', 'SPAM Name', 'required', array('required' => 'Wajib Diisi'));

        if ($this->form_validation->run()) {
            $array = array('success' => true);
        } else {
            $array = array(
                'error' => true,
                'input_spam_error_detail'   => form_error('input_spam', '<b class="fa fa-exclamation-triangle"></b> ', ' '),
            );
        }
        echo json_encode($array);
    }

    public function insert()
    {
        $data = array(
            'name'                   => $this->input->post('input_spam'),
            'diagram_flow_direction' => $this->input->post('input_flow'),
            'is_del'                 => 0
        );
        $inserted = $this->Spam_model->save($data);
        if ($inserted) {
            $result = array('status' => true);
        } else {
            $result = array('status' => false);
        }
        echo json_encode($result);
    }

    function detail($id = "")
    {
        $data = $this->Spam_model->get_by(array('id' => $id))->row_array();
        echo json_encode($data);
    }

    public function update()
    {
        $object = array(
            'name'                      => $this->input->post('input_spam', true),
            'diagram_flow_direction'    => $this->input->post('input_flow'),
        );

        $existing_data = $this->Spam_model->get_by(array('ID' => $this->input->post('id', true)))->row_array();
        unset($existing_data["id"]);
        unset($existing_data["desc"]);
        unset($existing_data["diagram_flow_direction"]);
        unset($existing_data["is_del"]);

        $edited_data = array_diff_assoc($object, $existing_data);

        if (!empty($edited_data)) { //if there is any edited data
            $updated = $this->Spam_model->update($object, array('ID' => $this->input->post('id', true)));
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
        $affected_row = $this->Spam_model->update($object, $where);
        $result = array('status' => true);
        echo json_encode($result);
    }

    function test(){
        $this->load->view('admin/test');
    }

    public function show_diagram($id=""){
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
        array_walk_recursive($data, function(&$item) {
            if (is_numeric($item)) {
                $item = intval($item);
            }
        });
        // echo "<pre>";
		echo json_encode($data, JSON_NUMERIC_CHECK);
	}

	function getNodeDetail($root)
	{
		$data = $this->Spam_model->getDataNodeDetailByRoot($root)->result();
		$result = [];
		foreach ($data as $key => $value) {
            $color_box = ($value->step == 1) ? "#f8f9fa" : "#f8f9fa" ;

            if($value->img == "-"){
                $img = 'node_icon/pump.png';
            } else {
                $img = 'gambar/'.$value->img;
            }

            if($value->step == 5){ //jika step komponen == logger
                $result[$value->id] = [
                    'trad' => $value->step_name .'<br>'.$value->name,
                    'kode' => $value->kode, 
                    'is_logger' => true,
                    'parent_kode' => '',
                    'img'   => $img,
                    'styles' => ['box-shadow' => '0 0 5px 5px orange']
                ];
            } else {
                $result[$value->id] = [
                    'trad' => $value->step_name .'<br>'.$value->name, 
                    'kode' => "",
                    'is_logger' => false,
                    'parent_kode' => $value->parent_step_kode,
                    'img'   => $img,
                    'styles' => ['box-shadow' => '0 0 5px 5px '.$color_box]
                ];
            }
			
		}
		echo json_encode($result, JSON_PRETTY_PRINT);
	}

	function getNodeName($param){
		$data = $this->Spam_model->getDataNode($param)->row_array();
		echo json_encode($data, JSON_PRETTY_PRINT);
	}

    function get_image($node_id){
        $data = $this->Komponen_model->get_image($node_id)->row_array();
        $res = "";
        if(!empty($data)){
            if($data['path'] !== ""){
                $res = $data['path'];
            } else {
                $res = 'no-image.png';
            }
        } else {
            $res = 'no-image.png';
        }
        echo $res;
    }

    function get_last_value($step, $node_id){
        $data = [];
        $res = "";
        if($step == 1) {
            $data = $this->Spam_intake_detail_model->get_last_value($node_id)->row_array();
            if(!empty($data)){
                $res = '
                <table class="table table-bordered">
                    <tbody>';
                    foreach ($data as $key => $value) {
                        if($key == "Periode") {
                            $res .= '<tr><td>'.$key.'</td><td>'.format_month_year($value).'</td></tr>';
                        } else {
                            $res .= '<tr><td>'.$key.'</td><td>'.$value.'</td></tr>';
                        }
                    }
                $res .= '</tbody>
                </table>';   
            } else {
                $res .= '<div class="alert alert-warning"><i>Data belum ada</i></div>';
            }
        } else if($step == 2) {
            $data = $this->Spam_ipa_detail_model->get_last_value($node_id)->row_array();
            if(!empty($data)){
                $res = '
                <table class="table table-bordered">
                    <tbody>';
                    foreach ($data as $key => $value) {
                        if($key == "Periode") {
                            $res .= '<tr><td>'.$key.'</td><td>'.format_month_year($value).'</td></tr>';
                        } else {
                            $res .= '<tr><td>'.$key.'</td><td>'.$value.'</td></tr>';
                        }
                    }
                $res .= '</tbody>
                </table>';
            } else {
                $res .= '<div class="alert alert-warning"><i>Data belum ada</i></div>';
            }
        } else if($step == 3) {
            $data = $this->Spam_rsv_detail_model->get_last_value($node_id)->row_array();
            if(!empty($data)){
                $res = '
                <table class="table table-bordered">
                    <tbody>';
                    foreach ($data as $key => $value) {
                        if($key == "Periode") {
                            $res .= '<tr><td>'.$key.'</td><td>'.format_month_year($value).'</td></tr>';
                        } else {
                            $res .= '<tr><td>'.$key.'</td><td>'.$value.'</td></tr>';
                        }
                    }
                $res .= '</tbody>
                </table>';
            } else {
                $res .= '<div class="alert alert-warning"><i>Data belum ada</i></div>';
            }
        } else if($step == 4) {
            $data = $this->Spam_wil_detail_model->get_last_value($node_id)->row_array();
            if(!empty($data)){
                $res = '
                <table class="table table-bordered">
                    <tbody>';
                    foreach ($data as $key => $value) {
                        if($key == "Periode") {
                            $res .= '<tr><td>'.$key.'</td><td>'.format_month_year($value).'</td></tr>';
                        } else {
                            $res .= '<tr><td>'.$key.'</td><td>'.$value.'</td></tr>';
                        }
                    }
                $res .= '</tbody>
                </table>'; 
            } else {
                $res .= '<div class="alert alert-warning"><i>Data belum ada</i></div>';
            }
        }
        echo $res;
    }

    public function getDataLogger($id){
        $res = $this->Spam_model->get_data_logger($id)->row_array();
        echo json_encode($res);
    }
}
