<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Include librari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Chart extends CI_Controller
{
    private $db_212;
    private $db_gis;

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model(['M_mendoan_model', 'Logger_model']);
        $this->load->helper('app_helper');
        $this->db_212 = $this->load->database('db_212', TRUE);
        $this->db_gis = $this->load->database('gis', TRUE);
    }

    public function index()
    {
        $data['list_logger'] = $this->M_mendoan_model->get_all()->result();
        $data['graph'] = null;
        $this->load->view('admin/chart', $data);
    }

    function max_with_key($array, $key)
    {
        if (!is_array($array) || count($array) == 0) return false;
        $max = $array[0][$key];
        foreach ($array as $a) {
            if ($a[$key] > $max) {
                $max = $a[$key];
            }
        }
        return $max;
    }

    public function get_chart($kode_logger = "", $tanggal_ = "")
    {
        $type = "";
        $first_date_of_current_month = null;
        $logger = explode(".", $kode_logger);
        $tanggal = null;
        if ($tanggal_ == "") {
            $tanggal = Date('Y-m');
            $first_date_of_current_month = date('Y-m-01 00:00:00');
            $type = "CURRENT_MONTH";
        } else {
            $exp_tanggal = explode("-", $tanggal_);
            if (count($exp_tanggal) > 2) { // jika format tanggal
                $first_date_of_current_month = $tanggal_;
                $type = "SELECTED_DATE";
            } else if (count($exp_tanggal) == 2) { // jika format bulan
                $first_date_of_current_month = $tanggal_;
                $type = "MONTH";
            }
        }

        //ambil data GPRS dari table t_bakwan
        $sql_gprs = "select null as 'kode', id_mendoan as kode_m, debit, tekanan, tanggal as updatedindb, tgl_log, jam_log, concat(tgl_log, ' ', jam_log) as periode
	                from t_bakwan where id_mendoan = 'm." . $logger[1] . "' and tanggal like '%" . $tanggal . "%'";
        $data_gprs = $this->db_gis->query($sql_gprs)->result();
        // echo $this->db_gis->last_query();
        // die;
        if (!empty($data_gprs)) { //jika ada data gprs 

            if ($type == "MONTH") {
                $list_tgl_gprs = [];
                foreach ($data_gprs as $key => $value) {
                    $data_gprs[$key]->periode = date('Y-m-d H:i:s', strtotime($value->periode));
                    // kumpulan data tanggal kedalam array
                    $list_tgl_gprs[] = $value->updatedindb;
                }

                if (!empty($list_tgl_gprs)) {
                    $min_tgl = min($list_tgl_gprs);
                    $first_date_of_current_month = date('Y-m-01 00:00:00', strtotime($min_tgl));
                    if ($min_tgl > $first_date_of_current_month) {
                        $sql_sms = "select li.kode,
                        concat(substring(li.kode, 2, 1), '.',
                            if(LENGTH(trim(substring(li.kode, 9, 3))) >= 3, substring(li.kode, 9, 3), concat(trim(substring(li.kode, 9, 3)), '0'))
                        ) as kode_m, debit, tekanan, updatedindb, DATE_FORMAT(DATE(updatedindb),'%d-%m-%Y') as tgl_log, TIME_FORMAT(time(updatedindb),'%H:%i') as jam_log, updatedindb as periode
                        from log_inbox li where kode like '%" . $logger[1] . "' and 
                        updatedindb >= '" . $first_date_of_current_month . "' and updatedindb <= '" . $min_tgl . "'";
                        $data_sms = $this->db_gis->query($sql_sms)->result();
                        $data = array_merge($data_gprs, $data_sms);
                        $sort = array();
                        foreach ($data as $k => $v) {
                            $sort['periode'][$k] = $v->periode;
                        }

                        # sort by event_type desc and then title asc
                        array_multisort($sort['periode'], SORT_ASC, $data);

                        $lokasi = $this->Logger_model->get_lokasi_log($logger[1]);

                        echo json_encode(['result' => $data, 'lokasi' => $lokasi['kode'] . " - " . $lokasi['lokasi']]);
                    }
                }
            } else if ($type == "SELECTED_DATE") {
                foreach ($data_gprs as $key => $value) {
                    $data_gprs[$key]->periode = date('Y-m-d H:i:s', strtotime($value->periode));
                }

                $sort = array();
                foreach ($data_gprs as $k => $v) {
                    $sort['periode'][$k] = $v->periode;
                }

                # sort by event_type desc and then title asc
                array_multisort($sort['periode'], SORT_ASC, $data_gprs);

                $lokasi = $this->Logger_model->get_lokasi_log($logger[1]);

                echo json_encode(['result' => $data_gprs, 'lokasi' => $lokasi['kode'] . " - " . $lokasi['lokasi']]);
            }
        } else {
            $sql_sms = "select li.kode,
                        concat(substring(li.kode, 2, 1), '.',
                            if(LENGTH(trim(substring(li.kode, 9, 3))) >= 3, substring(li.kode, 9, 3), concat(trim(substring(li.kode, 9, 3)), '0'))
                        ) as kode_m, debit, tekanan, updatedindb, DATE_FORMAT(DATE(updatedindb),'%d-%m-%Y') as tgl_log, TIME_FORMAT(time(updatedindb),'%H:%i') as jam_log, updatedindb as periode
                        from log_inbox li where kode like '%" . $logger[1] . "' and updatedindb like '%" . $tanggal . "%'";
            $data_sms = $this->db_gis->query($sql_sms)->result();

            if (!empty($data_sms)) {
                $sort = array();
                foreach ($data_sms as $k => $v) {
                    $sort['periode'][$k] = $v->periode;
                }

                array_multisort($sort['periode'], SORT_ASC, $data_sms);

                $lokasi = $this->Logger_model->get_lokasi_log($logger[1]);
                echo json_encode(['result' => $data_sms, 'lokasi' => $lokasi['kode'] . " - " . $lokasi['lokasi']]);
            }
        }
    }

    public function get_chart_data($kode_logger = "", $tanggal = "")
    {
        $type = "";
        $first_date_of_current_month = null;
        $logger = explode(".", $kode_logger);
        if ($tanggal == "") {
            $tanggal = Date('Y-m');
            $first_date_of_current_month = date('Y-m-01 00:00:00');
            $type = "CURRENT_MONTH";
        } else {
            $exp_tanggal = explode("-", $tanggal);
            if (count($exp_tanggal) > 2) { // jika format tanggal
                $first_date_of_current_month = $tanggal;
                $type = "SELECTED_DATE";
            } else if (count($exp_tanggal) == 2) { // jika format bulan
                $first_date_of_current_month = $tanggal;
                $type = "MONTH";
            }
        }

        //ambil data GPRS dari table t_bakwan
        $sql_gprs = "select null as 'kode', id_mendoan as kode_m, debit, tekanan, tanggal as updatedindb, tgl_log, jam_log, concat(tgl_log, ' ', jam_log) as periode
	                from t_bakwan where id_mendoan = 'm." . $logger[1] . "' and tanggal like '%" . $tanggal . "%'";
        $data_gprs = $this->db_gis->query($sql_gprs)->result();

        if (!empty($data_gprs)) { //jika ada data gprs 

            if ($type == "MONTH") {
                $list_tgl_gprs = [];
                foreach ($data_gprs as $key => $value) {
                    $data_gprs[$key]->periode = date('Y-m-d H:i:s', strtotime($value->periode));
                    // kumpulan data tanggal kedalam array
                    $list_tgl_gprs[] = $value->updatedindb;
                }

                if (!empty($list_tgl_gprs)) {
                    $min_tgl = min($list_tgl_gprs);
                    $first_date_of_current_month = date('Y-m-01 00:00:00', strtotime($min_tgl));
                    if ($min_tgl > $first_date_of_current_month) {
                        $sql_sms = "select li.kode,
                        concat(substring(li.kode, 2, 1), '.',
                            if(LENGTH(trim(substring(li.kode, 9, 3))) >= 3, substring(li.kode, 9, 3), concat(trim(substring(li.kode, 9, 3)), '0'))
                        ) as kode_m, debit, tekanan, updatedindb, DATE_FORMAT(DATE(updatedindb),'%d-%m-%Y') as tgl_log, TIME_FORMAT(time(updatedindb),'%H:%i') as jam_log, updatedindb as periode
                        from log_inbox li where kode like '%" . $logger[1] . "' and 
                        updatedindb >= '" . $first_date_of_current_month . "' and updatedindb <= '" . $min_tgl . "'";
                        $data_sms = $this->db_gis->query($sql_sms)->result();
                        $data = array_merge($data_gprs, $data_sms);
                        $sort = array();
                        foreach ($data as $k => $v) {
                            $sort['periode'][$k] = $v->periode;
                        }

                        # sort by event_type desc and then title asc
                        array_multisort($sort['periode'], SORT_ASC, $data);

                        $lokasi = $this->Logger_model->get_lokasi_log($logger[1]);
                        return $data;
                        // echo json_encode(['result' => $data, 'lokasi' => $lokasi['kode'] . " - " . $lokasi['lokasi']]);
                    }
                }
            } else if ($type == "SELECTED_DATE") {
                foreach ($data_gprs as $key => $value) {
                    $data_gprs[$key]->periode = date('Y-m-d H:i:s', strtotime($value->periode));
                }

                $sort = array();
                foreach ($data_gprs as $k => $v) {
                    $sort['periode'][$k] = $v->periode;
                }

                # sort by event_type desc and then title asc
                array_multisort($sort['periode'], SORT_ASC, $data_gprs);

                $lokasi = $this->Logger_model->get_lokasi_log($logger[1]);
                return $data_gprs;
                // echo json_encode(['result' => $data_gprs, 'lokasi' => $lokasi['kode'] . " - " . $lokasi['lokasi']]);
            }
        } else {
            $sql_sms = "select li.kode,
                        concat(substring(li.kode, 2, 1), '.',
                            if(LENGTH(trim(substring(li.kode, 9, 3))) >= 3, substring(li.kode, 9, 3), concat(trim(substring(li.kode, 9, 3)), '0'))
                        ) as kode_m, debit, tekanan, updatedindb, DATE_FORMAT(DATE(updatedindb),'%d-%m-%Y') as tgl_log, TIME_FORMAT(time(updatedindb),'%H:%i') as jam_log, updatedindb as periode
                        from log_inbox li where kode like '%" . $logger[1] . "' and updatedindb like '%" . $tanggal . "%'";
            $data_sms = $this->db_gis->query($sql_sms)->result();

            if (!empty($data_sms)) {
                $sort = array();
                foreach ($data_sms as $k => $v) {
                    $sort['periode'][$k] = $v->periode;
                }

                array_multisort($sort['periode'], SORT_ASC, $data_sms);

                $lokasi = $this->Logger_model->get_lokasi_log($logger[1]);
                return $data_sms;
                // echo json_encode(['result' => $data_sms, 'lokasi' => $lokasi['kode'] . " - " . $lokasi['lokasi']]);
            }
        }
    }

    public function get_chart_old($kode_logger, $tanggal = "")
    {
        $logger = explode(".", $kode_logger);
        if ($tanggal == "") {
            $tanggal = Date('Y-m');
        }
        $data = $this->Logger_model->get_data_log($logger[1], $tanggal);
        $lokasi = $this->Logger_model->get_lokasi_log($logger[1]);

        echo json_encode(['result' => $data, 'lokasi' => $lokasi['kode'] . " - " . $lokasi['lokasi']]);
    }

    public function export($kode_logger, $tanggal = "")
    {

        $logger = explode(".", $kode_logger);
        if ($tanggal == "") {
            $tanggal = Date('Y-m');
        }
        // $data = $this->Logger_model->report_logger($logger[1], $tanggal);
        $data = $this->get_chart_data($kode_logger, $tanggal);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = [
            'font' => ['bold' => true], // Set font nya jadi bold
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
            ]
        ];

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
            ]
        ];

        $sheet->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai E1
        $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1

        // Buat header tabel nya pada baris ke 3
        $sheet->setCellValue('A3', "NO");
        $sheet->setCellValue('B3', "KODE");
        $sheet->setCellValue('C3', "DEBIT");
        $sheet->setCellValue('D3', "TEKANAN");
        $sheet->setCellValue('E3', "TIME LOGGER");
        // $sheet->setCellValue('F3', "JENIS PIPA");
        // $sheet->setCellValue('G3', "DIAMETER PIPA");
        // $sheet->setCellValue('H3', "SPAM");
        // $sheet->setCellValue('I3', "LOKASI");
        // $sheet->setCellValue('J3', "JENIS LAYANAN");
        // $sheet->setCellValue('K3', "CABANG LAYANAN");
        // $sheet->setCellValue('L3', "DEBIT NORMAL");
        // $sheet->setCellValue('M3', "TEKANAN NORMAL");

        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $sheet->getStyle('A3')->applyFromArray($style_col);
        $sheet->getStyle('B3')->applyFromArray($style_col);
        $sheet->getStyle('C3')->applyFromArray($style_col);
        $sheet->getStyle('D3')->applyFromArray($style_col);
        $sheet->getStyle('E3')->applyFromArray($style_col);
        // $sheet->getStyle('F3')->applyFromArray($style_col);
        // $sheet->getStyle('G3')->applyFromArray($style_col);
        // $sheet->getStyle('H3')->applyFromArray($style_col);
        // $sheet->getStyle('I3')->applyFromArray($style_col);
        // $sheet->getStyle('J3')->applyFromArray($style_col);
        // $sheet->getStyle('K3')->applyFromArray($style_col);
        // $sheet->getStyle('L3')->applyFromArray($style_col);
        // $sheet->getStyle('M3')->applyFromArray($style_col);

        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4

        foreach ($data as $data) {
            // Lakukan looping pada variabel DATA
            $sheet->setCellValue('A' . $numrow, $no);
            $sheet->setCellValue('B' . $numrow, $data->kode_m);
            $sheet->setCellValue('C' . $numrow, $data->debit);
            $sheet->setCellValue('D' . $numrow, $data->tekanan);
            $sheet->setCellValue('E' . $numrow, strval($data->periode));
            // $sheet->setCellValue('F' . $numrow, $data->JENIS_PIPA);
            // $sheet->setCellValue('G' . $numrow, $data->DIAMETER_PIPA);
            // $sheet->setCellValue('H' . $numrow, $data->SPAM);
            // $sheet->setCellValue('I' . $numrow, $data->LOKASI);
            // $sheet->setCellValue('J' . $numrow, $data->JENIS_LAYANAN);
            // $sheet->setCellValue('K' . $numrow, $data->CABANG_LAYANAN);
            // $sheet->setCellValue('L' . $numrow, $data->DEBIT_NORMAL);
            // $sheet->setCellValue('M' . $numrow, $data->TEKANAN_NORMAL);

            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $sheet->getStyle('A' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('B' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('C' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('D' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('E' . $numrow)->applyFromArray($style_row);
            // $sheet->getStyle('F' . $numrow)->applyFromArray($style_row);
            // $sheet->getStyle('G' . $numrow)->applyFromArray($style_row);
            // $sheet->getStyle('H' . $numrow)->applyFromArray($style_row);
            // $sheet->getStyle('I' . $numrow)->applyFromArray($style_row);
            // $sheet->getStyle('J' . $numrow)->applyFromArray($style_row);
            // $sheet->getStyle('K' . $numrow)->applyFromArray($style_row);
            // $sheet->getStyle('L' . $numrow)->applyFromArray($style_row);
            // $sheet->getStyle('M' . $numrow)->applyFromArray($style_row);

            $no++; // Tambah 1 setiap kali looping
            $numrow++; // Tambah 1 setiap kali looping
        }

        // Set width kolom
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(30);
        // $sheet->getColumnDimension('F')->setWidth(30);
        // $sheet->getColumnDimension('G')->setWidth(30);
        // $sheet->getColumnDimension('H')->setWidth(30);
        // $sheet->getColumnDimension('I')->setWidth(30);
        // $sheet->getColumnDimension('J')->setWidth(30);
        // $sheet->getColumnDimension('K')->setWidth(30);
        // $sheet->getColumnDimension('L')->setWidth(30);
        // $sheet->getColumnDimension('M')->setWidth(30);

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);

        // Set orientasi kertas jadi LANDSCAPE
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

        // Set judul file excel nya
        // $sheet->setTitle("Laporan Data Logger M." . $logger[1] . " Periode " . $tanggal);
        $sheet->setTitle('Data');
        $title_file = "Laporan Data Logger M." . $logger[1] . " Periode " . $tanggal;
        $sheet->setCellValue('A1', $title_file); // Set kolom A1 dengan tulisan "REPORT DATA LOGGER"
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $title_file . '.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
