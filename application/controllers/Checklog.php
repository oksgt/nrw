<?php
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') or exit('No direct script access allowed');

class Checklog extends CI_Controller
{
    private $db_212;

    public function __construct()
    {
        parent::__construct();
        $this->db_212 = $this->load->database('db_212', TRUE);
        $this->load->library(array('form_validation'));
        $this->load->model([
            'Spam_model', 'Spam_intake_detail_model', 'Spam_rsv_detail_model', 'Spam_ipa_detail_model', 'Spam_wil_detail_model',
            'Komponen_model', 'Checklog_model'
        ]);
    }

    public function loglama()
    {
        die;
        $data = $this->db->query("select updatedindb from log_inbox order by updatedindb asc limit 1")->row_array();
        echo $data['updatedindb'];

        $qry = "
            SELECT 
                LEFT(TextDecoded,11) as kode,
                SUBSTRING_INDEX( SUBSTRING_INDEX( TextDecoded, 'P', 1 ), 'Q', - 1 ) AS debit ,
                SUBSTRING_INDEX(SUBSTRING_INDEX(TextDecoded, 'T', 1), 'P', -1) as tekanan,
                left(SUBSTRING_INDEX(SUBSTRING_INDEX(TextDecoded, 'Z', 1), 'J', -1),2) as jam,
                SUBSTRING_INDEX(SUBSTRING_INDEX(TextDecoded, 'J', 1), 'T', -1) as tgl,
                UpdatedInDB
            FROM
                inbox 
            WHERE
                UpdatedInDB < '" . $data['updatedindb'] . "'
            ORDER BY
            UpdatedInDB desc
        ";

        $result = [];
        $result_get_compare = $this->db_212->query($qry)->result_array();
        foreach ($result_get_compare as $key => $value) {
            $result[] = $value;
        }
        // echo count($result);
        // die;

        $inserted = $this->Checklog_model->insert_local_datalama($result);
        if ($inserted > 0) {
            echo $inserted . " row(s) inserted at " . date('Y-m-d H:i:s') . "<br><pre>";
            print_r($result);
        } else {
            echo " nothing inserted";
        }
    }

    public function index()
    {
        $last_updatedindb = $this->Checklog_model->get_local_last_date_record();
        echo "last_updatedindb " . $last_updatedindb . "<br>";
        $result_compare = $this->Checklog_model->check_compare($last_updatedindb)->result_array();
        if (!empty($result_compare)) {
            $result = [];
            $result_get_compare = $this->Checklog_model->get_check_compare($last_updatedindb)->result_array();

            foreach ($result_get_compare as $key => $value) {
                $result[] = $value;
            }
            $inserted = $this->Checklog_model->insert_local($result);
            if ($inserted > 0) {
                echo $inserted . " row(s) inserted at " . date('Y-m-d H:i:s') . "<br><pre>";
                print_r($result);
            } else {
                echo " nothing inserted";
            }
        } else {
            echo "kosong";
        }
    }

    public function ajax_update()
    {
        $last_updatedindb = $this->Checklog_model->get_local_last_date_record();
        $result_compare = $this->Checklog_model->check_compare($last_updatedindb)->result_array();
        if (!empty($result_compare)) {
            $result = [];
            $result_get_compare = $this->Checklog_model->get_check_compare($last_updatedindb)->result_array();

            foreach ($result_get_compare as $key => $value) {
                $result[] = $value;
            }

            $inserted = $this->Checklog_model->insert_local($result);
            if ($inserted > 0) {
                $result = ['status' => true];
            } else {
                $result = ['status' => false];
            }
        } else {
            $result = ['status' => true];
        }
        echo json_encode($result);
    }


    public function test()
    {
        $table_rows = $this->Checklog_model->cekrowscheme()->row_array();
        $result = json_encode($table_rows);
        if (!write_file(FCPATH . '/data.json', $result)) {
            echo 'Unable to write the file';
        } else {
            echo 'File written!';
        }

        $file = file_get_contents(FCPATH . '/data.json');
        $file = json_decode($file);
        $table_rows = $file->TABLE_ROWS;
        if ($table_rows['TABLE_ROWS'] > $table_rows) {
        }
    }
}
