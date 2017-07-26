<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
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
        $this->load->model("absensi_model", "am");
        
        $cabang = $this->session->userdata('userDivisi');
        
        $npp = $this->input->post("txt_npp");
        $cabang = $this->session->userdata("userDivisi");
        $ip = $this->session->userdata("ip_address");
        
        if($npp)
        {
            //pertama-tama cek NPP valid tak
            $valid_npp = $this->am->cek_pegawai($npp);
            if($valid_npp->jumlah === "1")
            {
                $this->am->insert_absen($npp, $cabang, $ip);
            }
        }
        
        $data["sql_absensi"] = $this->am->get_all_absensi($cabang);
        $data['sql_jam_masuk'] = $this->am->cek_jam_masuk();
        $data["panel_title"] = "Home";
        $data["content"] = "home";
        $this->load->view("template", $data);
    }
    
    function jam()
    {
        date_default_timezone_set("Asia/Jakarta");
        $jam = date("H:i:s");
        echo "$jam WIB";
    }
    
    private function cek_login()
    {
        if( ! $this->session->userdata('isLoggedIn') OR $this->session->userdata('isLoggedIn') === FALSE)
        {
            redirect("auth");
        }
    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */