<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Login_model' => 'model']);
    }

    public function index()
    {
        if ($this->session->userdata('status') == 'loggedin') {
            redirect(base_url());
        }
        $this->load->view('admin/view_login');
    }

    function action()
    {
        $username           = $this->input->post('username');
        $plain_password  = $this->input->post('password', true);
        $where = array(
            'username' => $username
        );
        $cek = $this->model->cek_login("user", $where);
        if (!$cek->result()) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">
                                Username salah.
                            </div>');
            redirect("admin/login", "refresh");
        } else {
            if ($cek->num_rows() >= 1) {
                foreach ($cek->result() as $row) {
                    $verify = $this->hash_verify($plain_password, $row->password);
                    if ($verify == TRUE) {
                        $login_data = $cek->row_array();
                        if(!empty($login_data)){
                            $login_data['status'] = 'loggedin';
                            $this->session->set_userdata($login_data);
                            
                            // echo "<pre>";
                            // print_r ($this->session->userdata());
                            // echo "</pre>";
                            
                            redirect('admin');
                        }
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger">
                                Password salah.
                            </div>');
                    redirect("admin/login", "refresh");
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">
                            Username / Password Tidak Ditemukan
                        </div>');
                redirect("admin/login", "refresh");
            }
        }
    }

    public function hash_string($string)
    {
        $hashed_string = password_hash($string, PASSWORD_BCRYPT);
        return $hashed_string;
    }

    public function hash_verify($plain_text, $hashed_string)
    {
        $hashed_string = password_verify($plain_text, $hashed_string);
        return $hashed_string;
    }

    function logout()
    {
        $this->session->sess_destroy();
        redirect('admin/login');
    }

    public function sxd()
    {
        // $data = array(
        //     'username'        => 'admin',
        //     'password'        => $this->hash_string('admin'),
        // );
        // $insertedId = $this->model->save($data);
        // echo $insertedId;
    }

    function ubah_password(){
        if ($this->session->userdata('status') !== 'loggedin') {
            redirect(site_url("admin/login"));
        }
        $this->load->view('admin/view_login_update');
    }

    function update(){
        //validasi password lama
        $old_password  = $this->input->post('old_password', true);
        $where = array(
            'username' => $this->session->userdata('username')
        );
        $cek = $this->model->cek_login("user", $where);
        foreach ($cek->result() as $row) {
            $verify = $this->hash_verify($old_password, $row->password);
            if ($verify == TRUE) { //jika password lama valid
                $object = [
                    'password' =>  $this->hash_string($this->input->post('new_password', true))
                ];

                $updated = $this->model->update($object, ['id' => $this->session->userdata('id')]);
                if ($updated) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success">
                        Password berhasil diubah.
                    </div>');
                    redirect("admin/login/ubah_password", "refresh");
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger">
                        Password gagal diubah.
                    </div>');
                    redirect("admin/login/ubah_password", "refresh");
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">
                        Password lama salah.
                    </div>');
                redirect("admin/login/ubah_password", "refresh");
            }
        }
    }
}
