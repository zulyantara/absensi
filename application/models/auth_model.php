<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends CI_Model
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
    
    function validate($username, $userpassword)
    {
        //klo6 character pertama dari username cabang maka ambil dari table m_cabang
        if(substr($username,0,6)=="cabang")
        {
            $this->db->where('cabang_user', $username);
            $this->db->where('cabang_password', sha1($userpassword));
            $this->db->where('cabang_is_delete', 0);
            $query = $this->db->get('m_cabang');
        }
        else
        {
            $this->db->select("user_id, user_name, user_password, user_level, karyawan_cabang, jabatan_id, jabatan_ket, jabatan_divisi, jabatan_sd, divisi_ket");
            $this->db->where('user_name', $username);
            $this->db->where('user_password', sha1($userpassword));
            $this->db->where('user_is_delete', 0);
            $this->db->join("karyawan", "user_npp=karyawan_npp", "left");
            $this->db->join("m_jabatan", "karyawan_jabatan=jabatan_id", "left");
            $this->db->join("m_divisi", "jabatan_divisi=divisi_id", "left");
            $query = $this->db->get('user');
        }
        
        if($query->num_rows() == 1)
        {
            return $query->row();
        }
        else
        {
            return array();
        }
    }
    
    function update_password($data = array())
    {
        $old_password = $data["old_password"];
        $new_password = sha1($data["new_password"]);
        $user = $this->session->userdata("userId");
        $date = date('Y-m-d H:i:s');
        
        $sql = "update user set user_password='".$new_password."', user_update_date='".$date."' where user_id=".$user;
        $qry = $this->db->query($sql);
        return $this->db->affected_rows();
    }
    
	function check_password($password)
	{
		$sql = "select count(*) as jml from user where user_id=".$this->session->userdata("userId")." and user_password='".sha1($password)."'";
        $qry = $this->db->query($sql);
        return $qry->row();
	}
}

/* End of file auth_model.php */
/* Location: ./application/controllers/auth_model.php */