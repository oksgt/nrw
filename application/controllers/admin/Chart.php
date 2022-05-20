<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Chart extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model(['M_mendoan_model', 'Logger_model']);
        $this->load->helper('app_helper');
    }

    public function index()
    {
        $data['list_logger'] = $this->M_mendoan_model->get_all()->result();
        $data['graph'] = null;
        $this->load->view('admin/chart', $data);
    }

    public function get_chart($kode_logger, $tanggal = "")
    {
        $logger = explode(".", $kode_logger);
        if ($tanggal == "") {
            $tanggal = Date('Y-m-d');
        }
        $data = $this->Logger_model->get_data_log($logger[1], $tanggal);
        $lokasi = $this->Logger_model->get_lokasi_log($logger[1]);
        // echo $tanggal;
        // echo $this->db->last_query();
        // echo $lokasi['kode'];
        // $periode = "";
        // foreach ($data as $key => $value) {
        //     $periode .= "'" . $value->updatedindb . "', ";
        // }
        // echo $periode;

        echo json_encode(['result' => $data, 'lokasi' => $lokasi['kode'] . " - " . $lokasi['lokasi']]);
    }
}
