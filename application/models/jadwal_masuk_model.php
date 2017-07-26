<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jadwal_masuk_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    /*
     * mengambil semua data
     */
    function get_all_data()
    {
        $where = "";
        if($this->session->userdata("userLevel") === "1")
        {
            $where = "";
        }
        else
        {
            $where = " and d.divisi_id=".$this->session->userdata("userDivisiId")." and jabatan_sd=".$this->session->userdata("userSubDivisi")." and karyawan_cabang='".$this->session->userdata("userCabang")."'";
        }
        $sql = "select wk.*, kry.karyawan_npp, kry.karyawan_nama from waktu_kerja wk left join karyawan kry on wk.wk_npp=kry.karyawan_npp left join m_jabatan j on kry.karyawan_jabatan=j.jabatan_id left join m_divisi d on j.jabatan_divisi=d.divisi_id where karyawan_is_delete=0 ".$where." order by jabatan_divisi";
        
        $query = $this->db->query($sql);
        
        return ($query->num_rows() > 0) ? $query->result() : array();
    }
    
    /*
     * menyimpan data ke database
     */
    function save_wk($data = array())
    {
        $wk_npp = $data["wk_npp"];
        $wk_senin = $data["wk_senin"];
        $wk_selasa = $data["wk_selasa"];
        $wk_rabu = $data["wk_rabu"];
        $wk_kamis = $data["wk_kamis"];
        $wk_jumat = $data["wk_jumat"];
        $wk_sabtu = $data["wk_sabtu"];
        $wk_cabang = $data["wk_cabang"];
        $wk_is_delete = 0;
        $wk_update_by = $this->session->userdata("userId");
        $wk_update_date = date("Y-m-d H:i:s");
        
        // cek npp klo sudah ada update klo belum simpan
        $cek_npp = $this->cek_npp($wk_npp);
        
        if($cek_npp == 0)
        {
            $sql = "insert into waktu_kerja(wk_npp, wk_senin, wk_selasa, wk_rabu, wk_kamis, wk_jumat, wk_sabtu, wk_cabang, wk_is_delete, wk_update_by, wk_update_date) values(".$this->db->escape($wk_npp).", ".$this->db->escape($wk_senin).", ".$this->db->escape($wk_selasa).", ".$this->db->escape($wk_rabu).", ".$this->db->escape($wk_kamis).", ".$this->db->escape($wk_jumat).", ".$this->db->escape($wk_sabtu).", ".$this->db->escape($wk_cabang).", ".$this->db->escape($wk_is_delete).", ".$this->db->escape($wk_update_by).", ".$this->db->escape($wk_update_date).")";
        }
        elseif($cek_npp == 1)
        {
            $sql = "update waktu_kerja set wk_senin=".$this->db->escape($wk_senin).", wk_selasa=".$this->db->escape($wk_selasa).", wk_rabu=".$this->db->escape($wk_rabu).", wk_kamis=".$this->db->escape($wk_kamis).", wk_jumat=".$this->db->escape($wk_jumat).", wk_sabtu=".$this->db->escape($wk_sabtu).", wk_cabang=".$this->db->escape($wk_cabang).", wk_is_delete=".$this->db->escape($wk_is_delete).", wk_update_by=".$this->db->escape($wk_update_by).", wk_update_date=".$this->db->escape($wk_update_date)." where wk_npp=".$this->db->escape($wk_npp);
        }
        
        $query = $this->db->query($sql);
        return $this->db->affected_rows();
    }
    
    /*
     * cek npp kembar
     */
    function cek_npp($npp)
    {
        $sql = "select * from waktu_kerja where wk_npp='".$npp."'";
        $query = $this->db->query($sql);
        return $query->num_rows();
    }
    
    /*
     * get jadwal by NPP
     */
    function get_selected_jadwal($npp)
    {
        $sql = "select * from waktu_kerja where wk_npp='".$npp."'";
        $qry = $this->db->query($sql);
        return $qry->num_rows() > 0 ? $qry->row() : FALSE;
    }
}

/* End of file jadwal_masuk_model.php */
/* Location: ./application/models/jadwal_masuk_model.php */