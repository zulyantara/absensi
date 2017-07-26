<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lembur extends CI_Controller
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
    
    function index($npp="", $tgl = "", $jam = "")
    {
        $this->load->library("form_validation");
        
        $this->form_validation->set_rules('txt_npp', 'NPP', 'required');
        $this->form_validation->set_rules('txt_tgl_lembur', 'Tanggal Lembur', 'required');
        $this->form_validation->set_rules('txt_keterangan', 'Keterangan', 'required');
        $this->form_validation->set_rules('rdo_hari_lembur', 'Hari Lembur', 'required');
        
        if ($this->form_validation->run() === FALSE)
        {
            $data["txt_npp"] = $npp;
            $data["txt_tgl"] = $tgl;
            $data["txt_jam"] = $jam;
            $data["li_id"] = "lembur";
            $data["panel_title"]="Lembur";
            $data["content"] = "lembur/home";
            $this->load->view("template", $data);
        }
        else
        {
            if($this->input->post("btn_simpan") === "simpan")
            {
                $this->simpan_lembur();
            }
        }
    }
    
    function simpan_lembur()
    {
        $this->load->model("lembur_model", "lm");
        
        $data["npp"] = $this->input->post("txt_npp");
        $data["tgl_lembur"] = $this->input->post("txt_tgl_lembur");
        $data["jam_kerja"] = $this->input->post("txt_jam_kerja");
        $data["hari_lembur"] = $this->input->post("rdo_hari_lembur");
        $data["ket"] = $this->input->post("txt_keterangan");
        
        $simpan_lembur = $this->lm->save_lembur($data);
        if($simpan_lembur === 1)
        {
            $data["class_alert"] = "success";
            $data["alert"] = "Lembur berhasil disimpan";
            $data["li_id"] = "lembur";
            $data["panel_title"]="Lembur";
            $data["content"] = "lembur/home";
            $this->load->view("template", $data);
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

/* End of file lembur.php */
/* Location: ./application/controllers/lembur.php */