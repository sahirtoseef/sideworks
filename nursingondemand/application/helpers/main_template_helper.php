<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('load_view')){
	function load_view($view = '',$data = array()){
		$data['msg']='';
		$CI =& get_instance();
		$query = $CI->db->query('select * from news order by id desc limit 5');
		$news['news'] = $query->result_array(); 
		$query = $CI->db->query('select * from products where category_id=4 order by RAND() limit 4');
		$news['veg'] = $query->result_array();
		$query = $CI->db->query('select * from products where category_id=1 order by RAND() limit 4');
		$news['fruit'] = $query->result_array();
		$CI->load->view('templates/header',$news);
		$CI->load->view($view, $data);
		$CI->load->view('templates/footer');
	}  
}

if ( ! function_exists('load_prd_box')){
	function load_prd_box($reslt = array()){
		$CI =& get_instance();
		$data['featured']=$reslt;
		return $prdcts=$CI->load->view('product/prdBox', $data, true);
	}
}