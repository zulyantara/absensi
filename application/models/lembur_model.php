<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lembur_model extends CI_Model
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
     * simpan lembur
     */
    function save_lembur($data = array())
    {
        $npp = $data["npp"];
        $tgl_lembur = $data["tgl_lembur"];
        $jam_kerja = $data["jam_kerja"];
        $hari_lembur = $data["hari_lembur"];
        $ket = $data["ket"];
        $date = date("Y-m-d H:i:s");
        $user = $this->session->userdata("userId");
        
        // setting jumlah jam kerja
        if($npp === "02.102013.0123")
        {
            $jml_jam_kerja = 9;
        }
        elseif(date("D", strtotime($tgl_lembur)) === "Sat")
        {
            $jml_jam_kerja = 6;
        }
        else
        {
            $jml_jam_kerja = 8;
        }
        
        /*
         * menghitung jumlah jam lembur
         * jika $hari_lembur = 0 maka hari kerja, jika $hari_lembur = 1 maka hari libur
         */
        if($hari_lembur === "0")
        {
            $explJam = explode(":", $jam_kerja);
            $jam_lembur = $explJam[0] - $jml_jam_kerja;
            $menit_lembur = $explJam[1];
            
            //convert to time
            $jml_jam_lembur = $jam_lembur.":".$menit_lembur;
        }
        elseif($hari_lembur === "1")
        {
            $jml_jam_lembur = $jam_kerja;
        }
        else
        {
            $jml_jam_lembur = "00:00";
        }
        
        $sql = "insert into karyawan_lembur(kl_npp,kl_tgl_lembur,kl_jml_lembur,kl_keterangan,kl_hari,kl_update_by, kl_update_date) values('".$npp."','".$tgl_lembur."','".$jml_jam_lembur."','".$ket."',$hari_lembur,$user,'".$date."')";
        $qry = $this->db->query($sql);
        return $this->db->affected_rows();
    }
}

/* End of file lembur_model.php */
/* Location: ./application/models/lembur_model.php */