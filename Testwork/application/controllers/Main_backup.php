<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	
	public function __construct(){
		parent::__construct();
		$this->load->model('User_model', 'user', TRUE);
		$this->data = array();
		$this->views = dirname(__FILE__,2).'/views/';
		$this->allowed = array('login','forgotpassword','verify','register','not_allowed');
		$this->slug = false;
	} 
	
	private function validateAccess(){
		if(!file_exists($this->views."public/$this->slug.php")){
			//var_dump($this->slug);
				show_404();
				die();
		}elseif(!$this->user->isLogin() && !in_array($this->slug, $this->allowed)){
				redirect('login');
				die();
		}elseif(in_array($this->slug, $this->allowed) && $this->user->isLogin()){
			redirect('');
		}
		/*if(in_array($this->slug, $this->allowed) && $this->user->isLogin() ){
			redirect('');
					die();
		}*/
	}

	
	public function index($slug = 'home', $params = false)
	{
		//var_dump($this->user->isLogin());
		
		$this->slug = $slug;
		$this->validateAccess();
		if(file_exists(dirname(__FILE__,1).'/Public/'.$slug.'.php')){
			require_once dirname(__FILE__,1).'/Public/'.$slug.'.php';
		}
		if(method_exists($this, $slug)){
			$this->$slug();
			
		}
		if(file_exists($this->views."public/$slug.php")){
			$this->load->view('public/'.$slug, $this->data);
		}else{
			
			show_404();
			//$this->load->view('public/404', $this->data);
		}
	}
	
	public function signout(){
	  $this->user->logout(base_url('login'));
	}
	
}
