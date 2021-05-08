<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('User_model', 'user', TRUE);
		
		$this->load->model('Form_model', 'form', TRUE);
		$this->load->library('form_validation');
		$this->views = dirname(__FILE__,2).'/views';
		$this->data = array();
		$this->response = array();
	}
	public function index($slug = false){
		$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : false;
		if(isset($_SERVER['HTTP_ORIGIN'])&&($_SERVER['HTTP_ORIGIN'] == isset($_SERVER['HTTPS'] ) ? 'https://' : 'http://'.$_SERVER['SERVER_NAME'])){
			$this->response['token'] = csrf();
		}
		$file = str_replace('::','/',ltrim($action,':'));
		
		if(file_exists(dirname(__FILE__,1).'/Api/'.$file.'.php')){
			require_once dirname(__FILE__,1).'/Api/'.$file.'.php';
		}
		if(method_exists($this, $slug)){
			if(!$params){
				$this->$slug();
			}else{
				$this->$slug($params);
			}
		}
		echo json_encode($this->response);
	}
}