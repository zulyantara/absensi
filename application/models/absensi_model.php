<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Absensi_model extends CI_Model
{
    /*
     * @author Zulyantara <zulyantara@gmail.com>
     * @copyright Copyright (c) 2014, Zulyantara
     */
    
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    function get_all_absensi($cabang)
    {
        $sql = "select absensi_npp, min(DATE_FORMAT(absensi_tgl, '%T')) as JamMasuk, max(DATE_FORMAT(absensi_tgl, '%T')) as JamKeluar, absensi_tgl, karyawan_nama, jabatan_ket from absensi left join karyawan on absensi_npp=karyawan_npp left join m_jabatan on karyawan_jabatan=jabatan_id where absensi_cabang='".$cabang."' and date(absensi_tgl)=CURDATE() group by absensi_npp, karyawan_nama, jabatan_ket order by max(DATE_FORMAT(absensi_tgl, '%T')) desc";
        
        $query = $this->db->query($sql);
        
        return ($query->num_rows > 0) ? $query->result() : array();
    }
    
    function insert_absen($npp, $cabang, $ipaddress)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = date('Y-m-d H:i:s', time());
        $sql_insert = "insert into absensi(absensi_npp, absensi_tgl, absensi_cabang, absensi_ip) values('".$npp."', '".$date."', '".$cabang."', '".$ipaddress."')";
        
        return $this->db->query($sql_insert);
    }
    
    function cek_pegawai($npp)
    {
        $sql = "select count(*) as jumlah from karyawan where karyawan_npp='".$npp."' and karyawan_is_delete=0";
        $query = $this->db->query($sql);
        return ($query->num_rows() > 0) ? $query->row() : 0;
    }
    
    function cek_jam_masuk()
    {
        $sql = "select wk_npp, wk_senin, wk_selasa, wk_rabu, wk_kamis, wk_jumat, wk_sabtu from waktu_kerja where wk_is_delete=0";
        $query = $this->db->query($sql);
        return ($query->num_rows > 0 ? $query->result() : array());
    }
}

/* End of file absensi_model.php */
/* Location: ./application/models/absensi_model.php */