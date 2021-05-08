<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model', 'user', TRUE);
        $this->load->model('Form_model','form',TRUE);
        
		$this->data = array('scriptInject'=>'');
        $this->data = array();
        
		$this->data['user'] = $this->user;
        $this->views = dirname(__FILE__, 2) . '/views/';
        $this->allowed = array('login', 'forgotpassword', 'register', 'not_allowed');
        $this->slug = false;
    }

    private function validateAccess() {
        // if($this->user->isSubscriber()) {
        //     redirect('');
        // }
        if (!file_exists($this->views . "public/$this->slug.php")) {
            //var_dump($this->slug);
            show_404();
            die();
        } elseif (!$this->user->isLogin() && !in_array($this->slug, $this->allowed)) {
            redirect('login');
            die();
        } elseif (in_array($this->slug, $this->allowed) && $this->user->isLogin()) {
            redirect('');
        }
        /* if(in_array($this->slug, $this->allowed) && $this->user->isLogin() ){
          redirect('');
          die();
          } */
    }

    public function index($slug = 'home', $params = false) {
        //var_dump($this->user->isLogin());
        if($this->user->isAdmin() && $slug=='test'){
            redirect('admin');
        } else {
            $this->data['result'] = $this->form->testResult(8);
            // echo '<pre>';
            // print_r($this->data['result']);
            // die;
            $this->slug = $slug;
            if (file_exists(dirname(__FILE__, 1) . '/Public/' . $slug . '.php')) {
                require_once dirname(__FILE__, 1) . '/Public/' . $slug . '.php';
            }
            
            
            if (method_exists($this, $slug)) {
                $this->$slug();
            }
            // if ($slug == 'employer' && $this->user->isAdmin()) {
            //     redirect('');
            // }
            //~ echo '<pre>';
            //~ print_r($this->views . "public/$slug.php");
            //~ die;
            if (file_exists($this->views . "public/$slug.php")) {
                $this->load->view('public/' . $slug, $this->data);
            } else {

                show_404();
                //$this->load->view('public/404', $this->data);
            }
        }
    }
    
    public function home(){
        $this->validateAccess();
        if(!$this->user->info->ustatus){
            redirect('check');
            die();
        }
        if(!$this->user->hasEmployer() && $this->user->isSubscriber()){
            redirect('employer');
            die();
        }
       /*if(!$this->user->hasMeta()){
           redirect('profile');
       }*/
        if(is_null($this->data['result'])){
            redirect('test');
        }
        //var_dump($result);
       // $this->data['qa'] = $this->form->getQueAns(8);
    }
    
     public function test(){
        $this->validateAccess();
        if(!$this->user->info->ustatus){
            redirect('check');
            die();
        }
        if(!$this->user->hasEmployer()){
            redirect('employer');
            die();
        }
        /*
        if(!$this->user->hasMeta()){
           redirect('profile');
       }
       */
        $this->data['qa'] = $this->form->getQueAns(8);
    }
    
    function profile(){
        $this->validateAccess();
    }
		
    function setpassword(){
        $v = isset($_GET['v']) ? $_GET['v'] : false;
        $this->data['udata'] = $this->user->verified($v);
        if(!$this->data['udata']){
            show_404();
        }
    }
    
    public function signout() {
        $this->user->logout(base_url('login'));
    }
    
    public function verify(){
        $v = isset($_GET['v']) ? $_GET['v'] : false;
        
        if(!empty($v)){
            $this->data['response'] = $this->user->verify($v);
           // var_dump($this->data['response']);
            $resp = array_keys($this->data['response'])[0];
            if($resp=='ERR'){
                redirect('check?fail='.$this->data['response'][$resp]); 
            }else{
                redirect($this->user->isLogin() ? 'home' : 'login'.'?check=verified');
            }
        }else{
           redirect('check?fail=fail'.$this->data['response'][$resp]);
        }
    }
    
   
    /*
    public function verify($id='') {
        if(isset($id) && !empty($id)) {
            $checkverify = $this->user->checkveriftion($id);
            $message = '';
            if($checkverify) {
                if($checkverify['ustatus'] == 0){
                    $this->user->verify_sucess($id);
                    $message = 'Account verifed Successfully';                    
                } else {
                    $message = 'Account already verifed';
                }                
            } else {
                $message = 'Something want wrong please try after sometime';
            }
            $this->session->set_flashdata("email_sent",$message);            
            redirect(base_url('login'));
        } else {
            redirect(base_url('login'));
        }        
    }*/
public function privacyPolicy(){
    $this->load->view('public/privacy-policy');
}

public function termsandcondition(){
    $this->load->view('public/terms-condition');
}

public function refundpolicy(){
    $this->load->view('public/refund-policy');
}

public function requestademo(){
    $this->load->view('public/request-a-demo');
}

public function sendrequestdemo(){
     $this->load->library('email');   
    $this->email->from('info@waxensoftech.com', 'Waxen Softech');
$this->email->to('abc@gmail.com');
 
$this->email->subject('Email Test');
$this->email->message('Testing the email class.');
$this->email->send();
}
}


