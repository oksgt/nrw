<?php
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') or exit('No direct script access allowed');

class Checklog extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model([
            'Spam_model', 'Spam_intake_detail_model', 'Spam_rsv_detail_model', 'Spam_ipa_detail_model', 'Spam_wil_detail_model',
            'Komponen_model', 'Checklog_model'
        ]);
        
    }

    public function index(){
        $last_updatedindb = $this->Checklog_model->get_local_last_date_record();
        echo "last_updatedindb " . $last_updatedindb . "<br>";
        $result_compare = $this->Checklog_model->check_compare($last_updatedindb)->result_array();
        if(!empty($result_compare)){
            $result = [];
            $result_get_compare = $this->Checklog_model->get_check_compare($last_updatedindb)->result_array();
            
            foreach ($result_get_compare as $key => $value) {
                $result[] = $value;
            }
            
            $inserted = $this->Checklog_model->insert_local($result);
            if($inserted > 0){
                echo $inserted." row(s) inserted at ".date('Y-m-d H:i:s')."<br><pre>";
                print_r($result); 
            } else {
                echo " nothing inserted";
            }
        } else {
            echo "kosong";
        }
    }

    public function test(){
        $table_rows = $this->Checklog_model->cekrowscheme()->row_array();
        $result = json_encode($table_rows);
        if ( ! write_file(FCPATH.'/data.json', $result))
        {
                echo 'Unable to write the file';
        }
        else
        {
                echo 'File written!';
        }

        $file = file_get_contents(FCPATH.'/data.json');
        $file = json_decode($file);
        $table_rows = $file->TABLE_ROWS;
        if($table_rows['TABLE_ROWS'] > $table_rows){
            
        }
    }
}