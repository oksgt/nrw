<?php
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') or exit('No direct script access allowed');

class Tte extends CI_Controller
{
    function index(){
        $this->load->view('view_tte_digital');
    }
}