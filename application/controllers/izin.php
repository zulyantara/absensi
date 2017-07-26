<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Izin extends CI_Controller
{
    /*
     * @author zulyantara <zulyantara@gmail.com>
     * @copyright copyright 2014 zulyantara
     */
    
    function __construct()
    {
        parent::__construct();
        $this->cek_login();
    }
    
    function index()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('txt_npp', 'NPP', 'required');
        $this->form_validation->set_rules('txt_tgl_izin_1', 'Tanggal Izin', 'required');
        $this->form_validation->set_rules('txt_tgl_izin_2', 'Tanggal Izin', 'required');
        $this->form_validation->set_rules('txt_alasan', 'Alasan', 'required');
        
        if ($this->form_validation->run() == FALSE)
        {
            $this->load->model("izin_model", "im");
            $this->load->model("master_model", "mm");
            
            $join = "left join m_jabatan on karyawan_jabatan=jabatan_id";
            $where = "jabatan_divisi=".$this->session->userdata("userDivisiId")." and jabatan_sd=".$this->session->userdata("userSubDivisi")." and karyawan_cabang='".$this->session->userdata("userCabang")."' and";

            $data["qry_izin"] = $this->im->get_all_izin();
            $data["qry_kategori"] = $this->mm->get_all_data("*","m_kategori","","","kategori","kategori_id");
            $data["res_karyawan"] = $this->mm->get_all_data("karyawan_npp, karyawan_nama, jabatan_ket","karyawan",$join,$where,"karyawan","karyawan_npp");
            $data["li_id"] = "izin";
            $data["panel_title"] = "Izin";
            $data["content"] = "izin/izin";
            $this->load->view("template", $data);
        }
        else
        {
            $this->simpan_izin();
        }
    }
    
    function simpan_izin()
    {
        $this->load->model("izin_model", "im");
        
        $data_input["txt_npp"] = $this->input->post("txt_npp");
        $data_input["txt_tgl_izin_1"] = $this->input->post("txt_tgl_izin_1");
        $data_input["txt_tgl_izin_2"] = $this->input->post("txt_tgl_izin_2");
        $data_input["txt_alasan"] = $this->input->post("txt_alasan");
        
        //check NPP ada ato kagak
        $check_npp = $this->im->check_valid_npp($data_input);
        
        // check data apakah kembar?
        $check_izin = $this->im->check_izin($data_input);
        
        if($check_izin->jml == 0)
        {
            if($check_npp->jml == 1)
            {
                $data["simpan_izin"] = $this->im->save_data_izin($data_input);
                if($data["simpan_izin"] == 1)
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
        
        $data["qry_izin"] = $this->im->get_all_izin();
        $data["li_id"] = "izin";
        $data["panel_title"] = "Izin";
        $data["content"] = "izin/izin";
        $this->load->view("template",$data);
    }
    
    function check_nama()
    {
        $this->load->model("izin_model", "im");
        $npp = $this->input->post("txt_npp");
        if($npp != "")
        {
            $row = $this->im->check_nama($npp);
            if(isset($row) OR $row == TRUE)
            {
                echo $row->karyawan_nama;
            }
            else
            {
                echo "";
            }
        }
    }
    
    function form_proses($npp, $divisi, $tgl_1, $tgl_2)
    {
        $this->load->model("izin_model", "im");
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('txt_npp', 'NPP', 'required');
        $this->form_validation->set_rules('txt_tgl_izin_1', 'Tanggal Izin', 'required');
        $this->form_validation->set_rules('txt_tgl_izin_2', 'Tanggal Izin', 'required');
        $this->form_validation->set_rules('txt_alasan', 'Alasan', 'required');
        $this->form_validation->set_rules('rdo_kategori', 'Kategori', 'required');
        $this->form_validation->set_rules('rdo_approve', 'Approve', 'required');
        
        if ($this->form_validation->run() == FALSE)
        {
            $data["npp"] = $npp;
            $data["divisi"] = $divisi;
            $data["tgl_1"] = $tgl_1;
            $data["tgl_2"] = $tgl_2;
            
            $data["qry_izin"] = $this->im->get_izin_by_npp($data);
            $data["li_id"] = "izin";
            $data["panel_title"]="Izin";
            $data["content"] = "izin/form_izin";
            $this->load->view("template", $data);
        }
        else
        {
            $this->proses_izin();
        }
    }
    
    function proses_izin()
    {
        $this->load->model("izin_model", "im");
        $this->load->model("master_model", "mm");
        
        $data["npp"] = $this->input->post("txt_npp");
        $data["divisi"] = $this->input->post("txt_divisi");
        $data["tgl_izin_1"] = $this->input->post("txt_tgl_izin_1");
        $data["tgl_izin_2"] = $this->input->post("txt_tgl_izin_2");
        $data["tgl_approve_1"] = $this->input->post("txt_tgl_approve_1");
        $data["tgl_approve_2"] = $this->input->post("txt_tgl_approve_2");
        $data["kategori"] = $this->input->post("rdo_kategori");
        $data["approve"] = $this->input->post("rdo_approve");
        
        $proses = $this->im->proses_izin($data);
        
        if($proses === 1)
        {
            $data["class_alert"] = "success";
            $data["alert"] = "Izin ".$data["npp"]." telah diproses";
            
            $join = "left join m_jabatan on karyawan_jabatan=jabatan_id";
            $where = "jabatan_divisi=".$this->session->userdata("userDivisiId")." and jabatan_sd=".$this->session->userdata("userSubDivisi")." and karyawan_cabang='".$this->session->userdata("userCabang")."' and";

            $data["qry_izin"] = $this->im->get_all_izin();
            $data["qry_kategori"] = $this->mm->get_all_data("*","m_kategori","","","kategori","kategori_id");
            $data["res_karyawan"] = $this->mm->get_all_data("karyawan_npp, karyawan_nama, jabatan_ket","karyawan",$join,$where,"karyawan","karyawan_npp");
            $data["li_id"] = "izin";
            $data["panel_title"] = "Izin";
            $data["content"] = "izin/izin";
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

/* End of file izin.php */
/* Location: ./application/controllers/izin.php */