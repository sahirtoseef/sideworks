<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin extends CI_Controller

{
  /**
   * Index Page for this controller.
   *
   * Maps to the following URL
   *    http://example.com/index.php/welcome
   *  - or -
   *    http://example.com/index.php/welcome/index
   *  - or -
   * Since this controller is set as the default controller in
   * config/routes.php, it's displayed at http://example.com/
   *
   * So any other public methods not prefixed with an underscore will
   * map to /index.php/welcome/<method_name>
   * @see https://codeigniter.com/user_guide/general/urls.html
   */
  function __construct()
  {
                parent::__construct();
                $this->load->helper('url');
                $this->load->model('admin/authentication_model');                
                $this->load->model('admin/admin_common_model');
                $this->load->library(array('form_validation','session')); 
                error_reporting(0);
                
  }

  public

  function index()
  {
    $this->load->view('admin/index');
  }

  public

  function dashboard()
  {
    $this->load->view('admin/dashboard');
  }

 

  public

  function view_page($page)
  {
     $this->load->view('admin/'.$page);
  }
  

  public

  function go()
  {
    
      $result = $this->authentication_model->admin_login();
      if(!$result) {
        $msg = array(
           'msg' =>'<strong>Error!</strong> Invalid Username and Password. Log in failed.','res' => 0
              );
                             $this->session->set_userdata($msg);
                             redirect('admin');
      }
      else {
        redirect('admin/dashboard', $message);
      }
    
  }  

  

  public function forgot_password()
    {
      $email = $this->input->post('email', TRUE);
      $arr_login = ['email' => $email];

      $login = $this->admin_common_model->fetch_recordbyid('admin', $arr_login);

      if ($login)
      {
        $pass = random_string('alnum', 6);

        $to = $email;
        $subject = "Forgot Password";
        $body = "<div style='max-width: 600px; width: 100%; margin-left: auto; margin-right: auto;'>
        <header style='color: #fff; width: 100%;'>
           <img alt='' src='".base_url('uploads/images/logo.png')."' width ='180' height='120'/>
        </header>
        
        <div style='margin-top: 10px; padding-right: 10px; 
      padding-left: 125px;
      padding-bottom: 20px;'>
          <hr>
          <h3 style='color: #232F3F;'>Hello ".$login->name.",</h3>
          <p>You have requested a new password for your Plannender Admin account.</p>
          <p>Your new password is <span style='background:#2196F3;color:white;padding:0px 5px'>".$pass."</span></p>
          <hr>
          
            <p>Warm Regards<br>Plannender<br>Support Team</p>
            
          </div>
        </div>

    </div>";

       

        $headers = "From: info@mobileappdevelop.co" . "\r\n";
        $headers.= "MIME-Version: 1.0" . "\r\n";
        $headers.= "Content-type:text/html;charset=UTF-8" . "\r\n";



          mail($to, $subject, $body, $headers);


          $this->admin_common_model->update_data('admin',['password'=>$pass],$arr_login);
        
      }
      else
      {
          $msg = array(
           'msg' =>'<strong>Error!</strong> This email is not registered to Plannender.','res' => 0
              );
                             $this->session->set_userdata($msg);
         redirect('admin/view_page/forgotpassword');
      }

        $msg = array(
              'success' =>'<strong>Success!</strong> Your new password has been send your registered email address.'
              );
                             $this->session->set_flashdata($msg);
        redirect('admin');
      
    }



  public function admin_logout(){
        $this->session->unset_userdata('admin');
        return redirect('admin');   
  }

  public function delete_data(){
        $table = $this->input->post('table');
        $id = $this->input->post('id');
        $this->admin_common_model->delete_data($table,['id'=>$id]);
        //echo $this->db->last_query();
  }

   public function create_owner(){
       
       $user_id = $this->input->post('user_id');
       $shop_id = $this->input->post('shop_id');
       if($shop_id!=''){
         $this->admin_common_model->update_data('shop',['user_id'=>$user_id],['id'=>$shop_id]);
       }
       return redirect('admin/view_page/userList');   
  }

  function updateStatus()
  {
      $status = $_POST['status'];
      $id = $_POST['id'];
      $this->admin_common_model->update_data("place_order",['status'=>$status],['id'=>$id]);
      return redirect('admin/view_page/orderList');
  }

  public function poll($event_id){
       
       $event_id = base64_decode($event_id);
       if($event_id!=''){
         $data['event'] = $this->admin_common_model->get_where('events',['id'=>$event_id])[0];
         $data['event_date'] = $this->admin_common_model->get_where('event_date',['event_id'=>$event_id]);
         $data['event_review'] = $this->admin_common_model->get_where('event_review',['event_id'=>$event_id]);
         $data['event_participat'] = $this->admin_common_model->get_where('event_participat',['event_id'=>$event_id]);
         $data['comm_count'] = $this->db->get_where('event_review',['event_id'=>$event_id])->num_rows();
         $data['part_count'] = $this->db->get_where('event_participat',['event_id'=>$event_id])->num_rows();
         if($data['event']){
           $this->load->view('admin/invite',$data); 
           return true;
         }
       }

       $this->load->view('admin/not_found');   
  }

  function add_event_user()
  {
      $user = $_POST['user_name'];
      $event_id = $_POST['event_id'];
      $this->db->insert("event_participat",['name'=>$user,'event_id'=>$event_id]);
      
      $this->load->view('admin/success');
      //echo "<script>alert('User invite successfully'); window.close();</script>";
  }



// end class echo $this->db->last_query(); die;
}