<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jadwal_masuk extends CI_Controller
{
    /*
     * @author zulyantara <zulyantara@gmail.com>
     * @copyright copyright 2014 zulyantara
     */
    
    function __construct()
    {
        parent::__construct();
        $this->cek_login();
        $this->output->enable_profiler(FALSE);
    }
    
    function index($msg = "")
    {
        $this->load->model("Jadwal_masuk_model");
        
        $data["li_id"] = "jadwal_masuk";
        $data["res_jadwal_masuk"] = $this->Jadwal_masuk_model->get_all_data();
        $data["msg"] = $msg;
        $data["panel_title"]="Jadwal Masuk";
        $data["content"] = "jadwal_masuk/home";
        $this->load->view("template", $data);
    }
    
    function frm_tambah($npp = "")
    {
        $this->load->library('form_validation');
        $this->load->model("Karyawan_model");
        $this->load->model("Master_model", "mm");
        
        $this->form_validation->set_rules('opt_npp', 'NPP', 'required');
        $this->form_validation->set_rules('txt_senin', 'Senin', 'required');
        $this->form_validation->set_rules('txt_selasa', 'Selasa', 'required');
        $this->form_validation->set_rules('txt_rabu', 'Rabu', 'required');
        $this->form_validation->set_rules('txt_kamis', 'Kamis', 'required');
        $this->form_validation->set_rules('txt_jumat', 'Jumat', 'required');
        $this->form_validation->set_rules('txt_sabtu', 'Sabtu', 'required');
        $this->form_validation->set_rules('opt_cabang', 'Cabang', 'required');
        
        if($this->form_validation->run() == FALSE)
        {
            if($npp != "")
            {
                $this->load->model("jadwal_masuk_model", "jmm");
                $data["qry_edit"] = $this->jmm->get_selected_jadwal($npp);
            }
            
            $join = "left join m_jabatan on karyawan_jabatan=jabatan_id";
            $where = "jabatan_divisi=".$this->session->userdata("userDivisiId")." and jabatan_sd=".$this->session->userdata("userSubDivisi")." and karyawan_cabang='".$this->session->userdata("userCabang")."' and";
            
            $data["li_id"] = "jadwal_masuk";
            $data["res_cabang"] = $this->mm->get_all_data("*","m_cabang","","","cabang","cabang_kode");
            $data["res_karyawan"] = $this->mm->get_all_data("karyawan_npp, karyawan_nama, jabatan_ket","karyawan",$join,$where,"karyawan","karyawan_npp");
            $data["panel_title"]="Jadwal Masuk &rarr; Tambah Data";
            $data["content"] = "jadwal_masuk/form";
            $this->load->view("template", $data);
        }
        else
        {
            if($this->input->post("btn_simpan") === "Simpan")
            {
                $this->simpan_wk();
            }
        }
    }
    
    /*
     * menyimpan waktu kerja karyawan
     */
    function simpan_wk()
    {
        $this->load->model("Jadwal_masuk_model", "jm");
        
        $data["wk_npp"] = $this->input->post("opt_npp");
        $data["wk_senin"] = $this->input->post("txt_senin");
        $data["wk_selasa"] = $this->input->post("txt_selasa");
        $data["wk_rabu"] = $this->input->post("txt_rabu");
        $data["wk_kamis"] = $this->input->post("txt_kamis");
        $data["wk_jumat"] = $this->input->post("txt_jumat");
        $data["wk_sabtu"] = $this->input->post("txt_sabtu");
        $data["wk_cabang"] = $this->input->post("opt_cabang");
        
        $simpan = $this->jm->save_wk($data);
        
        if($simpan == 1)
        {
            $msg = "data berhasil disimpan";
            $this->index($msg);
        }
        else
        {
            $msg = "data gagal disimpan";
            $this->index($msg);
        }
    }
    
    private function cek_login()
    {
        if( ! $this->session->userdata('isLoggedIn') OR $this->session->userdata('isLoggedIn') === FALSE)
        {
            redirect("auth");
        }
    }
}

/* End of file jadwal_masuk.php */
/* Location: ./application/controllers/jadwal_masuk.php */