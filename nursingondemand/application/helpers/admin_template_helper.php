<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


	 if ( ! function_exists('checkAuth'))
     { 
		function checkAuth()
		{
			$CI =& get_instance();	
			$CI->load->library('session');
			if($CI->session->userdata('emp_code')!='')
			{
				return true;   
			}
			else 
			{
				redirect("admin/authentication");
			}
		}
	 }

if ( ! function_exists('admin_view'))
{       
	    function admin_view($view = '',$data = array()){
		$CI =& get_instance();	
		$CI->load->library('session');
		if($CI->session->userdata('emp_code')!='')
		{      
			if($CI->session->userdata('designation')==1)
			{
			$chk = array(
				'designation_id' => $CI->session->userdata('designation'),
				'parent_menu' =>0
			 );
            $query = $CI->db->get_where('admin_menu',$chk);
			$data['perent_menulist'] = $query->result_array();
			}
			else {
			$chk = array('emp_id' => $CI->session->userdata('id'));
           // $query = $CI->db->get_where('emp_menu_rel',$chk);
				$CI->db->select('*');
				$CI->db->from('admin_menu');
				$CI->db->join('emp_menu_rel', 'emp_menu_rel.menu_id = admin_menu.id'); 
				$CI->db->where('emp_menu_rel.emp_id', $CI->session->userdata('id'));
				$query = $CI->db->get();
				$data['perent_menulist'] = $query->result_array();
			}
		}
		else
		{
			$CI->session->sess_destroy();
			redirect('admin/authentication');
		}
		
		$CI->load->view('admin/template/header',$data);
		if($CI->session->userdata('designation')!=1)
		{
		$url=$CI->uri->segment(2).'/'.$CI->uri->segment(3); 
		$CI->db->select('*');
		$CI->db->from('admin_menu');
		$CI->db->join('emp_menu_rel', 'emp_menu_rel.menu_id = admin_menu.id'); 
		$CI->db->where('emp_menu_rel.emp_id', $CI->session->userdata('id'));
		$CI->db->where('admin_menu.url', $url);
		$query11 = $CI->db->get();
		$permit=$query11->result_array();
		if(!empty($permit[0]))
		{  //print_r($permit);
			$CI->load->view($view, $data);
		} else { 
		$CI->load->view('admin/forbidden', $data); 
		}
		}
		else {
		$CI->load->view($view, $data);
		}
	}  
}
if ( ! function_exists('load_submenu'))
{
function load_submenu($menuid)
	{   
	    $CI =& get_instance();
		$chk = array(
				'designation_id' => $CI->session->userdata('designation'),
				'parent_menu' =>$menuid
			 );
            $query = $CI->db->get_where('admin_menu',$chk);
			$sub_menulist = $query->result_array();
			if(!empty($sub_menulist)) {
			return $sub_menulist; }else { return false; }
	}
}

if ( ! function_exists('check_submenu_per'))
{
function check_submenu_per($menuid)
	{   
	    $CI =& get_instance();
		$chk = array(
				'emp_id' => $CI->session->userdata('id'),
				'menu_id' =>$menuid
			 );
            $query = $CI->db->get_where('emp_menu_rel',$chk);
			$check_per = $query->result_array();
			if(!empty($check_per)) {
			return 1; }else { return 0; }
	}
}