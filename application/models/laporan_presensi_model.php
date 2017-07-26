<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_presensi_model extends CI_Model
{
    /*
     * @author zulyantara <zulyantara@gmail.com>
     * @copyright copyright 2014 zulyantara
     */
    
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    /*
     * mengambil semua karyawan
     */
    function get_all_karyawan()
    {
        $level = $this->session->userdata("userLevel");
        $divisi = $this->session->userdata("userDivisiId");
        $subDivisi = $this->session->userdata("userSubDivisi");
        $cabang = $this->session->userdata("userCabang");
        
        if($level === 1 OR $level === 2)
        {
            $sql = "select karyawan_npp, karyawan_nama from karyawan left join m_jabatan on karyawan_jabatan=jabatan_id where karyawan_is_delete=0 order by karyawan_nama asc";
        }
        else
        {
            $sql = "select karyawan_npp, karyawan_nama from karyawan left join m_jabatan on karyawan_jabatan=jabatan_id where karyawan_is_delete=0 and jabatan_divisi=".$divisi." and jabatan_sd=".$subDivisi." and karyawan_cabang='".$cabang."' order by karyawan_nama asc";
        }
        
        $qry = $this->db->query($sql);
        return $qry->num_rows() > 0 ? $qry->result() : FALSE;
    }
    
    /*
     * mengambil data absen sesuai npp dan range tanggal
     */
    function get_personal_presensi($data = array())
    {
        $npp = $data["txt_npp"];
        $bln1 = $data["txt_tgl_1"] ? date("Y-m-d",strtotime($data["txt_tgl_1"])) : 0;
        $bln2 = $data["txt_tgl_2"] ? date("Y-m-d",strtotime($data["txt_tgl_2"])) : 0;
        
        //klo usernya admin dan sdm bisa menampilkan semua selain itu sesuai divisi
        if($this->session->userdata("userLevel")==1 OR $this->session->userdata("userLevel")==2)
        {
            $sql = "SELECT absensi_npp, date_format(absensi_tgl, '%W') as absensi_hari, date_format(absensi_tgl, '%Y-%m-%d') as absensi_tgl, min(DATE_FORMAT(absensi_tgl, '%T')) as absensi_jamMasuk, max(DATE_FORMAT(absensi_tgl, '%T')) as absensi_jamKeluar FROM absensi left join karyawan on absensi_npp=karyawan_npp left join m_jabatan on karyawan_jabatan=jabatan_id left join m_divisi on jabatan_divisi=divisi_id where absensi_npp='".$npp."' AND date_format(absensi_tgl, '%Y-%m-%d') between '".$bln1."' and '".$bln2."' group by date_format(absensi_tgl, '%Y-%m-%d') order by date_format(absensi_tgl, '%Y-%m-%d %H:%i:%s') desc";
        }
        elseif($this->session->userdata("userLevel") == 3)
        {
            $sql = "SELECT absensi_npp, date_format(absensi_tgl, '%W') as absensi_hari, date_format(absensi_tgl, '%Y-%m-%d') as absensi_tgl, min(DATE_FORMAT(absensi_tgl, '%T')) as absensi_jamMasuk, max(DATE_FORMAT(absensi_tgl, '%T')) as absensi_jamKeluar FROM absensi left join karyawan on absensi_npp=karyawan_npp left join m_jabatan on karyawan_jabatan=jabatan_id left join m_divisi on jabatan_divisi=divisi_id where absensi_npp='".$npp."' and divisi_id='".$this->session->userdata("userDivisiId")."' AND date_format(absensi_tgl, '%Y-%m-%d') between '".$bln1."' and '".$bln2."' group by date_format(absensi_tgl, '%Y-%m-%d') order by date_format(absensi_tgl, '%Y-%m-%d %H:%i:%s') desc";
        }
        $query = $this->db->query($sql);
        
        return ($query->num_rows > 0) ? $query->result() : "";
    }
    
    /*
     * mengambil biodata karyawan berdasarkan NPP
     */
    function get_biodata_karyawan($npp)
    {
        $where="";
        if($this->session->userdata("userLevel") != "2")
        {
            $where = " and divisi_id=".$this->session->userdata("userDivisiId");
        }
        $sql = "select karyawan_npp, karyawan_nama, divisi_ket, jabatan_ket, cabang_ket from karyawan left join m_cabang on karyawan_cabang = cabang_kode left join m_jabatan on karyawan_jabatan = jabatan_id left join m_divisi on jabatan_divisi = divisi_id where karyawan_npp='".$npp."' and karyawan_is_delete=0".$where;
        $qry = $this->db->query($sql);
        
        return ($qry->num_rows() > 0) ? $qry->row() : "";
    }
    
    /*
     * mengambil semua data karyawan
     */
    function get_karyawan($divisi_id)
    {
        if($divisi_id === "0")
        {
            $sql = "select karyawan_npp, karyawan_nama, jabatan_ket, divisi_ket from karyawan left join m_jabatan on karyawan_jabatan=jabatan_id left join m_divisi on jabatan_divisi=divisi_id where karyawan_is_delete=0 order by divisi_ket asc";
        }
        elseif($divisi_id != "")
        {
            $sql = "select karyawan_npp, karyawan_nama, jabatan_ket, divisi_ket from karyawan left join m_jabatan on karyawan_jabatan=jabatan_id left join m_divisi on jabatan_divisi=divisi_id where karyawan_is_delete=0 and divisi_id=".$divisi_id." order by divisi_ket asc";
        }
        else
        {
            $sql = "select karyawan_npp, karyawan_nama, jabatan_ket, divisi_ket from karyawan left join m_jabatan on karyawan_jabatan=jabatan_id left join m_divisi on jabatan_divisi=divisi_id where karyawan_npp=-1";
        }
        
        $query = $this->db->query($sql);
        
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }
    
    /*
     * ngitung jumlah telat
     */
    function get_rekap_karyawan($data = array())
    {
        $npp = $data["txt_npp"];
        $bln1 = $data["txt_tgl_1"] ? date("Y-m-d",strtotime($data["txt_tgl_1"])) : 0;
        $bln2 = $data["txt_tgl_2"] ? date("Y-m-d",strtotime($data["txt_tgl_2"])) : 0;
        
        $sql = "SELECT absensi_npp, min(DATE_FORMAT(absensi_tgl, '%T')) as jam_masuk FROM absensi where absensi_npp='".$npp."' and date_format(absensi_tgl, '%Y-%m-%d') between '".$bln1."' and '".$bln2."' group by date_format(absensi_tgl, '%Y-%m-%d')";
        
        $query = $this->db->query($sql);
        
        return ($query->num_rows > 0) ? $query->result() : "";
    }
    
    /*
     * mengambil data jadwal masuk karyawan
     */
    function get_wk_karyawan($npp)
    {
        $sql = "select * from waktu_kerja where wk_npp='".$npp."' and wk_is_delete=0";
        $query = $this->db->query($sql);
        
        return ($query->num_rows() > 0) ? $query->row() : FALSE;
    }
    
    function get_selected_absen($npp, $tgl)
    {
        $sql = "select absensi_npp, cabang_ket, absensi_ip, min(DATE_FORMAT(absensi_tgl, '%T')) as JamMasuk, max(DATE_FORMAT(absensi_tgl, '%T')) as JamKeluar from absensi left join m_cabang on absensi_cabang=cabang_kode where absensi_npp='".$npp."' and absensi_tgl like '".$tgl."%' group by absensi_npp";
        $qry = $this->db->query($sql);
        return ($qry->num_rows > 0) ? $qry->row() : FALSE;
    }
    
    function get_jam_msk($npp, $hari)
    {
        $sql = "select wk_".$hari." from waktu_kerja where wk_npp='".$npp."'";
        $qry = $this->db->query($sql);
        return ($qry->num_rows > 0) ? $qry->row() : FALSE;
    }
    
    /*
     * menghitung jumlah izin yang sudah diapprove
     */
    function count_izin($data = array(), $kategori=1)
    {
        $npp = $data["txt_npp"];
        $date_1 = $data["txt_tgl_1"];
        $date_2 = $data["txt_tgl_2"];
        
        $sql = "select count(*) as jml_izin from karyawan_izin where ki_npp='".$npp."' and ki_date_approve_1 between '".$date_1."' and '".$date_2."' and ki_flag_approve=1 and ki_kategori=".$kategori;
        $qry = $this->db->query($sql);
        
        return ($qry->num_rows() > 0) ? $qry->row() : FALSE;
    }
    
    /*
     * menghitung lembur yang sudah diapprove
     */
    function count_lembur($data = array())
    {
        $npp = $data["txt_npp"];
        $date_1 = $data["txt_tgl_1"];
        $date_2 = $data["txt_tgl_2"];
        
        $sql = "select kl_jml_lembur from karyawan_lembur where kl_npp='".$npp."' and kl_tgl_lembur between '".$date_1."' and '".$date_2."'";
        $qry = $this->db->query($sql);
        
        return ($qry->num_rows() > 0) ? $qry->result() : FALSE;
    }
    
    /*
     * hitung jumlah karyawan
     */
    function count_all_karyawan()
    {
        $this->db->where("karyawan_is_delete", 0);
        return $this->db->count_all('karyawan');
    }
}

/* End of file laporan_presensi_model.php */
/* Location: ./application/models/laporan_presensi_model.php */