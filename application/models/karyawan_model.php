<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Karyawan_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    function get_npp()
    {
        $sql = "select karyawan_npp, karyawan_nama from karyawan order by karyawan_nama asc";
        $query = $this->db->query($sql);
        
        return ($query->num_rows() > 0) ? $query->result() : array();
    }
}


/* End of file karyawan_model.php */
/* Location: ./application/models/karyawan_model.php */