<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sakit extends CI_Controller
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
    
    function index()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('txt_npp', 'NPP', 'required');
        $this->form_validation->set_rules('txt_tgl_sakit_1', 'Tanggal Sakit 1', 'required');
        $this->form_validation->set_rules('txt_tgl_sakit_2', 'Tanggal Sakit 2', 'required');
        $this->form_validation->set_rules('txt_keterangan', 'Keterangan', 'required');
        
        if ($this->form_validation->run() == FALSE)
        {
            $this->load->model("izin_model", "im");
            $this->load->model("master_model", "mm");
            
            $data["qry_sakit"] = $this->im->get_all_izin(4);
            $data["qry_kategori"] = $this->mm->get_all_kategori();
            $data["li_id"] = "sakit";
            $data["panel_title"] = "Sakit";
            $data["content"] = "izin/sakit";
            $this->load->view("template", $data);
        }
        else
        {
            $this->simpan_sakit();
        }
    }

    function simpan_sakit()
    {
        $this->load->model("izin_model", "im");
        
        $data_input["txt_npp"] = $this->input->post("txt_npp");
        $data_input["txt_tgl_sakit_1"] = $this->input->post("txt_tgl_sakit_1");
        $data_input["txt_tgl_sakit_2"] = $this->input->post("txt_tgl_sakit_2");
        $data_input["txt_keterangan"] = $this->input->post("txt_keterangan");
        
        //check NPP ada ato kagak
        $check_npp = $this->im->check_valid_npp($data_input);
        
        if($check_npp->jml == 1)
        {
            $data["simpan_sakit"] = $this->im->save_data_sakit($data_input);
            if($data["simpan_sakit"] == 1)
            {
                $data["class_alert"] = "success";
                $data["alert"] = "Data berhasil disimpan";
            }
            else
            {
                $data["class_alert"] = "danger";
                $data["alert"] = "Data tidak berhasil disimpan";
            }
        }
        else
        {
            $data["class_alert"] = "danger";
            $data["alert"] = "NPP tidak ada";
        }
        $this->load->model("izin_model", "im");
        
        $data["qry_sakit"] = $this->im->get_all_izin(4);
        $data["li_id"] = "sakit";
        $data["panel_title"] = "Sakit";
        $data["content"] = "izin/sakit";
        $this->load->view("template",$data);
    }
        
    private function cek_login()
    {
        if( ! $this->session->userdata('isLoggedIn') OR $this->session->userdata('isLoggedIn') === FALSE)
        {
            redirect("auth");
        }
    }
}

/* End of file sakit.php */
/* Location: ./application/controllers/sakit.php */