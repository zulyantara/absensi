<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    function get_all_data_cabang()
    {
        $sql = "select * from m_cabang where cabang_is_delete=0 order by cabang_kode asc";
        $query = $this->db->query($sql);
        
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }
    
    function get_all_data_divisi()
    {
        $sql = "select * from m_divisi where divisi_is_delete=0 order by divisi_ket asc";
        $query = $this->db->query($sql);
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }
    
    function get_all_kategori()
    {
        $sql = "select * from m_kategori where kategori_is_delete=0";
        $query = $this->db->query($sql);
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }
    
    function get_all_data($select="*", $table, $join, $where, $is_delete, $order)
    {
        $sql = "select ".$select." from ".$table." ".$join." where ".$where." ".$is_delete."_is_delete=0 order by ".$order." asc";
        $query = $this->db->query($sql);
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }
}


/* End of file master_model.php */
/* Location: ./application/models/master_model.php */