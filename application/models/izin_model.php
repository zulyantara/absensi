<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Izin_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    function get_all_izin($kategori=1)
    {
        $where_divisi =  " and jabatan_divisi=".$this->session->userdata('userDivisiId');
        
        if($kategori === 1)
        {
            $where_kategori = "and ki_kategori <> 3 and ki_kategori <> 4";
        }
        else
        {
            $where_kategori = "and ki_kategori = ".$kategori;
        }
        
        if($this->session->userdata("userLevel") === "2" OR $this->session->userdata("userLevel") === "1")
        {
            $where_divisi="";
        }
        elseif($this->session->userdata("userLevel") === "3" && $this->session->userdata("userDivisiId") === "1" && $this->session->userdata("userSubDivisi") === "2")
        {
            $where_divisi =  " and jabatan_divisi=".$this->session->userdata('userDivisiId')." and karyawan_cabang='".$this->session->userdata("userCabang")."'";
        }
        
        $sql = "select * from v_karyawan_izin left join m_kategori on ki_kategori=kategori_id where ki_is_delete=0 and karyawan_is_delete=0 ".$where_kategori.$where_divisi;
        $query = $this->db->query($sql);
        
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }
    
    function get_all_data_alasan()
    {
        $sql = "select * from m_alasan where alasan_isactive=1";
        $query = $this->db->query($sql);
        
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }
    
    function save_data_izin($data_input = array())
    {
        $npp = $data_input["txt_npp"];
        $tgl_izin_1 = $data_input["txt_tgl_izin_1"];
        $tgl_izin_2 = $data_input["txt_tgl_izin_2"];
        $alasan = strtolower($data_input["txt_alasan"]);
        $date_create = date("Y-m-d H:i:s");
        $flag_approve = 2;
        
        $sql = "insert into karyawan_izin(ki_npp,ki_date_izin_1,ki_date_izin_2,ki_alasan,ki_kategori,ki_date_create,ki_flag_approve,ki_is_delete) values('".$npp."','".$tgl_izin_1."','".$tgl_izin_2."','".$alasan."',1,'".$date_create."',$flag_approve,0)";
        $query = $this->db->query($sql);
        
        return $this->db->affected_rows();
    }
    
    function save_data_cuti($data_input = array())
    {
        $npp = $data_input["txt_npp"];
        $tgl_izin_1 = $data_input["txt_tgl_izin_1"];
        $tgl_izin_2 = $data_input["txt_tgl_izin_2"];
        $alasan = strtolower($data_input["txt_alasan"]);
        $date_create = date("Y-m-d H:i:s");
        $flag_approve = 2;
        
        $sql = "insert into karyawan_izin(ki_npp,ki_date_izin_1,ki_date_izin_2,ki_alasan,ki_kategori,ki_date_create,ki_flag_approve,ki_is_delete) values('".$npp."','".$tgl_izin_1."','".$tgl_izin_2."','".$alasan."',3,'".$date_create."',$flag_approve,0)";
        $query = $this->db->query($sql);
        
        return $this->db->affected_rows();
    }
    
    function save_data_sakit($data_input = array())
    {
        $npp = $data_input["txt_npp"];
        $tgl_sakit_1 = $data_input["txt_tgl_sakit_1"];
        $tgl_sakit_2 = $data_input["txt_tgl_sakit_2"];
        $keterangan = strtolower($data_input["txt_keterangan"]);
        $date_create = date("Y-m-d H:i:s");
        $flag_approve = 1;
        
        //get divisi karyawan
        $sql_karyawan = "select jabatan_divisi from karyawan left join m_jabatan on karyawan_jabatan=jabatan_id where karyawan_npp='".$npp."'";
        $qry_karyawan = $this->db->query($sql_karyawan);
        $res_karyawan = $qry_karyawan->row();
        $divisi = $res_karyawan->jabatan_divisi;
        
        //mengambil data atasan sesuai divisi masing2
        $qry_atasan = $this->get_max_grade($divisi);
        $atasan = $qry_atasan->karyawan_npp;
        
        $sql = "insert into karyawan_izin(ki_npp,ki_date_izin_1,ki_date_izin_2,ki_date_approve_1,ki_date_approve_2,ki_alasan,ki_kategori,ki_atasan,ki_date_create,ki_flag_approve,ki_is_delete) values('".$npp."','".$tgl_sakit_1."','".$tgl_sakit_2."','".$tgl_sakit_1."','".$tgl_sakit_2."','".$keterangan."',4,'".$atasan."','".$date_create."',$flag_approve,0)";
        $query = $this->db->query($sql);
        
        return $this->db->affected_rows();
    }
    
    function check_izin($arr_data = array())
    {
        $npp = $arr_data["txt_npp"];
        $tgl_1 = $arr_data["txt_tgl_izin_1"];
        $tgl_2 = $arr_data["txt_tgl_izin_2"];
        
        $sql = "select count(*) as jml from karyawan_izin where ki_npp='".$npp."' and ki_date_izin_1='".$tgl_1."' and ki_date_izin_2='".$tgl_2."'";
        $query = $this->db->query($sql);
        return $query->row();
    }
    
    function check_valid_npp($data_input = array())
    {
        $npp = $data_input["txt_npp"];
        $sql = "select count(*) as jml from karyawan where karyawan_npp='".$npp."'";
        $query = $this->db->query($sql);
        return $query->row();
    }
    
    function check_nama($npp)
    {
        $sql = "select karyawan_nama from karyawan where karyawan_npp='".$npp."'";
        $query = $this->db->query($sql);
        return ($query->num_rows() > 0) ? $query->row() : FALSE;
    }
    
    function get_izin_by_npp($data = array())
    {
        $npp = $data["npp"];
        $tgl_1 = date("Y-m-d", strtotime($data["tgl_1"]));
        $tgl_2 = date("Y-m-d", strtotime($data["tgl_2"]));
        
        $sql = "select ki_npp, karyawan_nama, ki_date_izin_1, ki_date_izin_2, ki_alasan from v_karyawan_izin where ki_npp='".$npp."' and ki_date_izin_1='".$tgl_1."' and ki_date_izin_2='".$tgl_2."'";
        $qry = $this->db->query($sql);
        return ($qry->num_rows() > 0) ? $qry->row() : FALSE;
    }
    
    function proses_izin($data = array())
    {
        $npp = $data["npp"];
        $divisi = $data["divisi"];
        $tgl_izin_1 = date("Y-m-d", strtotime($data["tgl_izin_1"]));
        $tgl_izin_2 = date("Y-m-d", strtotime($data["tgl_izin_2"]));
        $tgl_approve_1 = date("Y-m-d", strtotime($data["tgl_approve_1"]));
        $tgl_approve_2 = date("Y-m-d", strtotime($data["tgl_approve_2"]));
        $kategori = $data["kategori"];
        $approve = $data["approve"];
        $date_now = date("Y-m-d");
        $user = $this->session->userdata("userId");
        
        //mengambil data atasan sesuai divisi masing2
        $qry_atasan = $this->get_max_grade($divisi);
        $atasan = $qry_atasan->karyawan_npp;
        
        $sql = "update karyawan_izin set ki_date_approve_1='".$tgl_approve_1."', ki_date_approve_2='".$tgl_approve_2."', ki_flag_approve=".$approve.", ki_date_approve='".$date_now."', ki_kategori=$kategori,ki_atasan='".$atasan."', ki_update_by=".$user.", ki_update_date='".$date_now."' where ki_npp='".$npp."' and ki_date_izin_1='".$tgl_izin_1."' and ki_date_izin_2='".$tgl_izin_2."'";
        $qry = $this->db->query($sql);
        return $this->db->affected_rows();
    }
    
    function proses_cuti($data = array())
    {
        $npp = $data["npp"];
        $divisi = $data["divisi"];
        $tgl_izin_1 = date("Y-m-d", strtotime($data["tgl_izin_1"]));
        $tgl_izin_2 = date("Y-m-d", strtotime($data["tgl_izin_2"]));
        $tgl_approve_1 = date("Y-m-d", strtotime($data["tgl_approve_1"]));
        $tgl_approve_2 = date("Y-m-d", strtotime($data["tgl_approve_2"]));
        $approve = $data["approve"];
        $date_now = date("Y-m-d");
        $user = $this->session->userdata("userId");
        
        //mengambil data atasan sesuai divisi masing2
        $qry_atasan = $this->get_max_grade($divisi);
        $atasan = $qry_atasan->karyawan_npp;
        
        $sql = "update karyawan_izin set ki_date_approve_1='".$tgl_approve_1."', ki_date_approve_2='".$tgl_approve_2."', ki_flag_approve=".$approve.", ki_date_approve='".$date_now."',ki_atasan='".$atasan."', ki_update_by=".$user.", ki_update_date='".$date_now."' where ki_npp='".$npp."' and ki_date_izin_1='".$tgl_izin_1."' and ki_date_izin_2='".$tgl_izin_2."'";
        $qry = $this->db->query($sql);
        return $this->db->affected_rows();
    }
    
    // get NPP from maximum grade on m_jabatan
    function get_max_grade($divisi)
    {
        $sql = "select karyawan_npp from m_jabatan left join karyawan on jabatan_id=karyawan_jabatan where jabatan_is_delete=0 and jabatan_divisi=".$divisi." order by jabatan_grade desc";
        $qry = $this->db->query($sql);
        return ($qry->num_rows() > 0) ? $qry->row() : FALSE;
    }
}

/* End of file izin_model.php */
/* Location: ./application/controllers/izin_model.php */