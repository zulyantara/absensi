<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cuti extends CI_Controller
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
        $this->form_validation->set_rules('txt_tgl_cuti_1', 'Tanggal Izin', 'required');
        $this->form_validation->set_rules('txt_tgl_cuti_2', 'Tanggal Izin', 'required');
        $this->form_validation->set_rules('txt_alasan', 'Alasan', 'required');
        
        if ($this->form_validation->run() == FALSE)
        {
            $this->load->model("izin_model", "im");
            $data["qry_cuti"] = $this->im->get_all_izin(3);
            $data["li_id"] = "cuti";
            $data["panel_title"]="Cuti";
            $data["content"] = "izin/cuti";
            $this->load->view("template", $data);
        }
        else
        {
            $this->simpan_cuti();
        }
    }

    function simpan_cuti()
    {
        $this->load->model("izin_model", "im");
        
        $data_input["txt_npp"] = $this->input->post("txt_npp");
        $data_input["txt_tgl_izin_1"] = $this->input->post("txt_tgl_cuti_1");
        $data_input["txt_tgl_izin_2"] = $this->input->post("txt_tgl_cuti_2");
        $data_input["txt_alasan"] = $this->input->post("txt_alasan");
        
        //check NPP ada ato kagak
        $check_npp = $this->im->check_valid_npp($data_input);
        
        // check data apakah kembar?
        $check_cuti = $this->im->check_izin($data_input);
        
        if($check_cuti->jml == 0)
        {
            if($check_npp->jml == 1)
            {
                $data["simpan_cuti"] = $this->im->save_data_cuti($data_input);
                if($data["simpan_cuti"] == 1)
                {
                    $data["class_alert"] = "success";
                    $data["alert"] = "Data berhasil disimpan";
                }
            }
            else
            {
                $data["class_alert"] = "danger";
                $data["alert"] = "NPP tidak ada";
            }
        }
        else
        {
            $data["class_alert"] = "danger";
            $data["alert"] = "Data sudah ada";
        }
        
        /*
         *@todo ngirim email ke atasan masing2? (gimana cara tau atasannya?) dan ngirim cc ke kepala divisi sdm (emailnya masih berubah2)
         */
        
        $this->load->model("izin_model", "im");
        
        $data["qry_cuti"] = $this->im->get_all_izin(3);
        $data["li_id"] = "cuti";
        $data["panel_title"] = "Cuti";
        $data["content"] = "izin/cuti";
        $this->load->view("template",$data);
    }
    
    function form_proses($npp, $divisi, $tgl_1, $tgl_2)
    {
        $this->load->model("izin_model", "im");
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('txt_npp', 'NPP', 'required');
        $this->form_validation->set_rules('txt_tgl_izin_1', 'Tanggal Izin', 'required');
        $this->form_validation->set_rules('txt_tgl_izin_2', 'Tanggal Izin', 'required');
        $this->form_validation->set_rules('txt_alasan', 'Alasan', 'required');
        $this->form_validation->set_rules('rdo_approve', 'Approve', 'required');
        
        if ($this->form_validation->run() == FALSE)
        {
            $data["npp"] = $npp;
            $data["divisi"] = $divisi;
            $data["tgl_1"] = $tgl_1;
            $data["tgl_2"] = $tgl_2;
            
            $data["qry_cuti"] = $this->im->get_izin_by_npp($data);
            $data["li_id"] = "cuti";
            $data["panel_title"]="Cuti";
            $data["content"] = "izin/form_cuti";
            $this->load->view("template", $data);
        }
        else
        {
            $this->proses_cuti();
        }
    }
    
    function proses_cuti()
    {
        $this->load->model("izin_model", "im");
        
        $data["npp"] = $this->input->post("txt_npp");
        $data["divisi"] = $this->input->post("txt_divisi");
        $data["tgl_izin_1"] = $this->input->post("txt_tgl_izin_1");
        $data["tgl_izin_2"] = $this->input->post("txt_tgl_izin_2");
        $data["tgl_approve_1"] = $this->input->post("txt_tgl_approve_1");
        $data["tgl_approve_2"] = $this->input->post("txt_tgl_approve_2");
        $data["approve"] = $this->input->post("rdo_approve");
        
        $proses = $this->im->proses_cuti($data);
        
        if($proses === 1)
        {
            $data["class_alert"] = "success";
            $data["alert"] = "Izin ".$data["npp"]." telah diproses";
            
            $data["qry_cuti"] = $this->im->get_all_izin(3);
            $data["li_id"] = "cuti";
            $data["panel_title"] = "Cuti";
            $data["content"] = "izin/cuti";
            $this->load->view("template",$data);
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

/* End of file cuti.php */
/* Location: ./application/controllers/cuti.php */