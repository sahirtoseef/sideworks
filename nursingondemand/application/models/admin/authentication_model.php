<?php
class Authentication_model  extends CI_Model {
	public function __construct()
	{
	$this->load->database();
	}
	public function admin_login()
	{
		// grab user input
		$email = $this->security->xss_clean($this->input->post('username'));
		$password = $this->security->xss_clean($this->input->post('password'));
		
		$query = $this->db->query("SELECT * FROM (`admin`) WHERE ( `email` = '".$email."' ) AND `password` = '".$password."' ");
               // echo $this->db->last_query(); die;
		// Let's check if there are any results

		if($query->num_rows == 1)
		{
			// If there is a user, then create session data
			$row = $query->row();
			$this->session->set_userdata('admin',$row);

			return true;
		}
		// If the previous process did not validate
		// then return false.
		return false;
	}
	
	public function update_data($table,$data,$where)
	{
		$this->db->where($where);
		$rs=$this->db->update($table,$data);
		if($rs) { return true; } else { return false; } 
	}
	
	public function fetch_recordbyid($tbname,$data)
	{
		$this->db->where($data);
		$query = $this->db->get($tbname);
		if($query->num_rows == 1)
		{
			$row = $query->row();
			return $row;
		}
		else{ 
		return false; 
		}
	}
}  