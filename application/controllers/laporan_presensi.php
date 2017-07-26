<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_presensi extends CI_Controller
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
		$data["li_id"] = "laporan_presensi";
        $data["panel_title"]="Laporan Presensi";
        $data["content"] = "laporan_presensi/home";
        $this->load->view("template", $data);
    }
    
    /*
     * mengambil data absensi karyawan
     */
    function personal_presensi()
    {
        $this->load->model("laporan_presensi_model", "lpm");
        
        $data = array();
        $data["opt_npp"] = $this->input->post("opt_npp");
        $data["thn_pilih"] = ($this->input->post("opt_tahun")) ? $this->input->post("opt_tahun") : date("Y");
        $data["bln_pilih"] = ($this->input->post("opt_bulan")) ? $this->input->post("opt_bulan") : date("m");
        
        $data["res_biodata"] = $this->lpm->get_biodata_karyawan($data["opt_npp"]);
        $data["res_karyawan"] = $this->lpm->get_all_karyawan();
		$data["li_id"] = "laporan_presensi";
        $data["panel_title"] = "Laporan Personal Presensi";
        $data["content"] = "laporan_presensi/personal";
        $this->load->view("template", $data);
    }
    
	/*
	 * cetak laporan presensi personal ke excel
	 */
    function cetak_personal_excel()
	{
		$this->load->model('laporan_presensi_model', "lpm");
		
        $data['nama_user'] = $this->session->userdata('userName');
        
		$data['txt_npp'] = $this->input->post('Npp')?$this->input->post('Npp'):0;
		//$data['txt_tgl_1'] = ($this->input->post('prd1')!=""?$this->input->post('prd1'):"");
		//$data['txt_tgl_2'] = ($this->input->post('prd2')!=""?$this->input->post('prd2'):"");
        $data["thn_pilih"] = ($this->input->post("prd2")) ? $this->input->post("prd2") : date("Y");
        $data["bln_pilih"] = ($this->input->post("prd1")) ? $this->input->post("prd1") : date("m");
		
		$data['res_biodata'] = $this->lpm->get_biodata_karyawan($data['txt_npp']);
		//$data['res_personal'] = $this->lpm->get_personal_presensi($data);
        
		$this->load->view('laporan_presensi/spreadsheet_personal',$data);
	}
    
    /*
     * mengambil data rekap absensi karyawan etc: keterlambatan, izin dll
     */
    function rekap_presensi()
    {
        $this->load->model("laporan_presensi_model", "lpm");
        $this->load->model("master_model", "mm");
		
		$data['opt_divisi'] = $this->input->post('opt_divisi');
		$data['txt_tgl_1'] = ($this->input->post('txt_tgl_1')!=""?$this->input->post('txt_tgl_1'):"");
		$data['txt_tgl_2'] = ($this->input->post('txt_tgl_2')!=""?$this->input->post('txt_tgl_2'):"");
        
        $data["res_karyawan"] = $this->lpm->get_karyawan($data['opt_divisi']);
        $data["res_divisi"] = $this->mm->get_all_data_divisi();
        
		$data["li_id"] = "laporan_presensi";
        $data["panel_title"] = "Laporan Rekap Presensi";
        $data["content"] = "laporan_presensi/rekap";
        $this->load->view("template", $data);
    }
    
    function cetak_rekap_excel()
	{
		$this->load->model('laporan_presensi_model', "lpm");
        $this->load->model("master_model", "mm");
		
        $data['nama_user'] = $this->session->userdata('userName');
        
		$data['opt_divisi'] = $this->input->post('opt_divisi')?$this->input->post('opt_divisi'):-1;
		$data['txt_tgl_1'] = ($this->input->post('prd1')!=""?$this->input->post('prd1'):"");
		$data['txt_tgl_2'] = ($this->input->post('prd2')!=""?$this->input->post('prd2'):"");
		
        $data["res_karyawan"] = $this->lpm->get_karyawan($data['opt_divisi']);
        $data["res_divisi"] = $this->mm->get_all_data_divisi();
        
		$this->load->view('laporan_presensi/spreadsheet_rekap',$data);
	}
    
    private function cek_login()
    {
        if( ! $this->session->userdata('isLoggedIn') OR $this->session->userdata('isLoggedIn') === FALSE)
        {
            redirect("auth");
        }
    }
}

/* End of file laporan_presensi.php */
/* Location: ./application/controllers/laporan_presensi.php */