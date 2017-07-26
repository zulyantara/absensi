<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller
{
    /*
     * @author zulyantara <zulyantara@gmail.com>
     * @copyright copyright 2014 zulyantara
     */
    
	function __construct()
	{
		parent::__construct();
		$this->output->enable_profiler(FALSE);
	}
	
    function index()
    {
        if($this->session->userdata('isLoggedIn') === FALSE)
        {
            $this->load->view('login/home');
        }
        else
        {
        	redirect('home');
        }
    }
    
    function validate_credential()
    {
		if($this->input->post('btn_login') === 'btn_login')
		{
			$this->load->model('auth_model');
			
			$query = $this->auth_model->validate($this->input->post('txt_user_name'), $this->input->post('txt_user_password'));
			if($query)
			{
				if(substr($this->input->post("txt_user_name"),0,6) == "cabang")
				{
					$data = array(
						'userName' => $query->cabang_ket,
						'userDivisi' => $query->cabang_kode,
						'isPresensi' => TRUE,
						'isLoggedIn' => TRUE
					);
				}
				else
				{
					$data = array(
						'userId' => $query->user_id,
						'userName' => $query->user_name,
						'userDivisiId' => $query->jabatan_divisi,
						'userDivisi' => $query->divisi_ket,
						'userSubDivisi' => $query->jabatan_sd,
						'userCabang' => $query->karyawan_cabang,
						'userLevel' => $query->user_level,
						'isLoggedIn' => TRUE
					);
				}
				
				$this->session->set_userdata($data);
				redirect("home");
			}
			else
			{
				$data['error'] = "<div class=\"uk-alert uk-alert-danger\">Username atau Password salah</div>";
				$this->load->view('login/home', $data);
			}
		}
		else
		{
			$data['error'] = "<div class=\"uk-alert uk-alert-danger\">Anda harus login terlebih dahulu</div>";
			$this->load->view('login/home', $data);
		}
    }
	
	function change_password()
	{
		$this->load->library('form_validation');
		
		$data["panel_title"]="Ubah Password";
        $data["content"] = "login/form";
        $this->load->view("template", $data);
	}
	
	function update_password()
	{
		$this->load->library('form_validation');
		$this->load->model("auth_model","am");
		
		$data["old_password"] = $this->input->post("txt_old_password");
		$data["new_password"] = $this->input->post("txt_new_password");
		$data["confirm_password"] = $this->input->post("txt_confirm_password");
		
		$this->form_validation->set_rules('txt_old_password', 'Password Lama', 'trim|required');
		$this->form_validation->set_rules('txt_new_password', 'Password Baru', 'trim|required|matches[txt_confirm_password]');
		$this->form_validation->set_rules('txt_confirm_password', 'Password Confirmation', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
			$data["panel_title"] = "Ubah Password";
			$data["content"] = "login/form";
			$this->load->view("template", $data);
		}
		else
		{
			//cek password lama
			$cek_old_password = $this->am->check_password($data["old_password"]);
			
			if($cek_old_password->jml === "1")
			{
				$update = $this->am->update_password($data);
				$data["panel_title"] = "Ubah Password";
				$data["content"] = "login/form";
				$data["message"] = "Password Berhasil diubah";
				$this->load->view("template", $data);
			}
			else
			{
				$data["panel_title"] = "Ubah Password";
				$data["content"] = "login/form";
				$data["message"] = "Password Lama tidak sama";
				$this->load->view("template", $data);
			}
		}
	}
	
    function logout()
	{
		$this->session->sess_destroy();
		$this->load->view('login/home');
	}
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */