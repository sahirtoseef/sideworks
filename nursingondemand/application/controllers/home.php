<?php 
	if (!defined('BASEPATH')) exit('No direct script access allowed');
	//define("SITE_URL",'http://yashtechsolutions.com/QuikMoves/');
    define("SITE_URL",'http://www.air.sideworkapps.com/nursingondemand/');

	class Home extends CI_Controller{

		public function __construct(){
			parent:: __construct();
			$this->load->model('webservice_model');
			$this->load->library(['form_validation','email']);   
                        $this->load->helper(['url']);                     
		}

		public function index(){
			$this->load->view('home');
		}

		public function view_load($page){
			$this->load->view($page);
		}

                public function signup(){
			$arr_data = $this->input->post();

                        if (isset($_FILES['vehicle_photo']))
			{
                           $n = rand(0, 100000);
		           $vehicle_photo = "VEH_IMG_" . $n . '.png';
			   move_uploaded_file($_FILES['vehicle_photo']['tmp_name'], "img/" . $vehicle_photo);
                           $arr_data['vehicle_photo'] = $vehicle_photo;			   
			}

                        if (isset($_FILES['vehicle_photo']))
			{
                           $n = rand(0, 100000);
		           $license_photo = "LIC_IMG_" . $n . '.png';
			   move_uploaded_file($_FILES['license_photo']['tmp_name'], "img/" . $license_photo);
                           $arr_data['license_photo'] = $license_photo;			   
			}

                        $arr_get = ['email'=>$arr_data['email']];

			$login = $this->webservice_model->get_where('users',$arr_get);
			if ($login) {
				
				$this->session->set_flashdata('welcome','This email already exist');
				redirect('home');
				die;
			}	

                        $id = $this->webservice_model->insert_data('users',$arr_data);

                        $fetch = $this->webservice_model->get_where('users',['id'=>$id]);
                        $this->session->set_userdata('users',$fetch[0]);

                        if(isset($arr_data['type'])){
                          $this->session->set_flashdata('welcome','Welcome to Quickmoves, you are successfully registered on Quickmoves your request has been send to administrator to verify your detail');
                        }else{
                          $this->session->set_flashdata('welcome','Welcome to Quickmoves, you have permission to access our services');
                        }
                        redirect('home');
		}
               
                public function user_login(){
			$arr_data = $this->input->post();
                        $fetch = $this->webservice_model->get_where('users',$arr_data);
                        if($fetch){
                           $this->session->set_userdata('users',$fetch[0]);
                           $this->session->set_flashdata('welcome','Welcome to Quickmoves, you have permission to access our services');                
                        }else{
                           $this->session->set_flashdata('welcome','You have enter wrong username or password');
                        }
                        redirect('home');
		}

                public function logout(){
                       $this->session->unset_userdata('admin');
		       redirect('home');
		} 
		
		public function privacy(){
                       $this->load->view('static/privacy');
		}
		
    public function terms(){
                       $this->load->view('static/terms');
		}
    

		


    // end class
    }

?>