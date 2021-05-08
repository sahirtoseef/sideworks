<?php 

if (!defined('BASEPATH')) exit('No direct script access allowed');
define("SITE_URL",'http://www.air.sideworkapps.com/nursingondemand/');

class Adminwebservice extends CI_Controller{

  public function __construct(){
    parent:: __construct();
    $this->load->model('webservice_model');
    $this->load->library(['form_validation','email']);   
    header('Access-Control-Allow-Origin: *');                     
  }

  public function index(){
      //$this->load->view('home'); 
  }

  /************* login function *************/

  /* http://mobileappdevelop.co/PLANNENDER/webservice/login?email=admin@gmail.com&password=123456 */


  public function login(){

    $email = $this->input->get_post('email');
    $password = $this->input->get_post('password');
    $user_type = $this->input->get_post('user_type');
    $lat = $this->input->get_post('lat');
    $lon = $this->input->get_post('lon');

    // $arr_whr = "password ='$pwd' AND (email = '$email' OR phone_number = '$email')";
    
    $arr_whr = ['email'=>$email,'password'=>base64_encode($password),'user_type'=>$user_type];

    $check_login = $this->webservice_model->get_where('users',$arr_whr);
    if ($check_login) {

      $login_where = ['id'=>$check_login[0]['id']];

      $this->webservice_model->update_data('users',['lat' => $lat,'lon' => $lon],$login_where);
      $check_login[0]['image']=SITE_URL.'uploads/images/'.$check_login[0]['image'];
      $ressult['result']=$check_login[0];
      // $ressult['user_status']='Admin';
      $ressult['message']='successfull';
      $ressult['status']='1';
      $json = $ressult;

    }else{
      $ressult['result']='Your have entered wrong email & password';
      $ressult['message']='unsuccessfull';
      $ressult['status']='0';
      $json = $ressult;       
    }

    header('Content-type:application/json');
    echo json_encode($json);
  }

  /************* signup_nurse function *************/

  public function signup_nurse(){

  // $nurse_id = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);

    $nurse_id = 'NURSE'.rand(1,1000000);

    $arr_data = [
      'firstname'=>$this->input->get_post('firstname'),
      'lastname'=>$this->input->get_post('lastname'),
      'email'=>$this->input->get_post('email'),
      'password'=>base64_encode($this->input->get_post('password')),
      'phone_number'=>$this->input->get_post('phone_number'),
      'nursing_experience'=>$this->input->get_post('nursing_experience'),
      'workplace'=>$this->input->get_post('workplace'),
      'nurse_type'=>$this->input->get_post('nurse_type'),
      'service_type_id'=>$this->input->get_post('service_type_id'),
      'gender'=>$this->input->get_post('gender'),
      'lat'=>$this->input->get_post('lat'),
      'lon'=>$this->input->get_post('lon'),         
      'nurse_id'=>$nurse_id,
      'user_type'=>'NURSE'        
    ];


   //echo "<pre>";print_r($arr_data);die;

    $phone_number = $arr_data['phone_number'];
    $email = $arr_data['email'];

    $arr_whr = "(phone_number='$phone_number' or email = '$email')";

    if (isset($_FILES['image']))
    {
                         //  unlink('uploads/images/'.$login[0]['image']);
     $n = rand(0, 100000);
     $img = "NURSE_IMG_" . $n . '.png';
     move_uploaded_file($_FILES['image']['tmp_name'], "uploads/images/" . $img);
     $arr_data['image'] = $img;        
   }

 // echo "<pre>";print_r($_FILES['image']);

   $chk_usr_exist = $this->webservice_model->get_where('users',$arr_whr);

  // echo $this->db->last_query();die;

   if (!empty($chk_usr_exist)) {

    $ressult['result']='Phone Number Or Email Already Exist';
    $ressult['message']='unsuccessfull';
    $ressult['status']='0';
    $json = $ressult;

    header('Content-type:application/json');
    echo json_encode($json);
    die;
  }


  $id = $this->webservice_model->insert_data('users',$arr_data);

  if ($id=="") {
    $json = ['result'=>'unsuccessfull','status'=>0,'message'=>'data not found'];
  }else{


    $arr_whr_user = ['id'=>$id];
    $get_user = $this->webservice_model->get_where('users',$arr_whr_user);  

    $get_user[0]['image']=SITE_URL.'uploads/images/'.$get_user[0]['image'];
    $get_user[0]['password'] = base64_decode($get_user[0]['password']);     
    $ressult['result']=$get_user[0];
    $ressult['message']='successfull';
    $ressult['status']='1';
    $json = $ressult;
  }

  header('Content-type:application/json');
  echo json_encode($json);

}


/*************  get_all_user_list *************/
public function get_all_user_list()
{                     
  $arr_whr = ['user_type'=>'USER'];
  $user_lists = $this->webservice_model->get_where('users',$arr_whr);


  if ($user_lists) {

    foreach($user_lists as $user_list)
    {

      if($user_list['image']!=''){
        $user_list['image']=SITE_URL.'uploads/images/'.$user_list['image'];
      }
      else{
        $user_list['image'] = '';
      }
      $data[] = $user_list;

    }

    $ressult['result']=$data;
    $ressult['message']='successful';
    $ressult['status']='1';
    $json = $ressult;                      


  }
  else {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);

}

/*************  get_all_nurse_list *************/
public function get_all_nurse_list()
{                     
  $arr_whr = ['user_type'=>'NURSE'];
  $nurse_lists = $this->webservice_model->get_where('users',$arr_whr);


  if ($nurse_lists) {

    foreach($nurse_lists as $nurse_list)
    {

      if($nurse_list['image']!=''){
        $nurse_list['image']=SITE_URL.'uploads/images/'.$nurse_list['image'];
      }
      else{
        $nurse_list['image'] = '';
      }
      $nurse_list['rating']=1;
      $data[] = $nurse_list;

    }

    $ressult['result']=$data;
    $ressult['message']='successful';
    $ressult['status']='1';
    $json = $ressult;                      


  }
  else {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);

}


/************* search_user_lists *************/
public

function search_user_lists()
{
  $keyword = $this->input->get_post("keyword");
  
  //$filter_product_lists = $this->home_model->get_where('products',['product_id'=>$product_id]);
  
  $search_user_lists = $this->db->query("SELECT * from users WHERE firstname LIKE '%$keyword%' AND user_type='USER' ")->result_array();  
  
  if(!empty($search_user_lists)){

    foreach($search_user_lists as $search_user_list)
    {
      if($search_user_list['image']!=''){

        $search_user_list['image']=SITE_URL.'uploads/images/'.$search_user_list['image'];
      }
      else{
        $search_user_list['image'] = '';
      }
      $data[] = $search_user_list;
    }

    $ressult['result'] = $data;
    $ressult['message'] ='successful';
    $ressult['status'] = '1';
    $json = $ressult;
  }

  else
  {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;                              

  }



  header('Content-type: application/json');
  echo json_encode($json);
  die;
}


/************* search_nurse_lists *************/
public

function search_nurse_lists()
{
  $keyword = $this->input->get_post("keyword");
  
  //$filter_product_lists = $this->home_model->get_where('products',['product_id'=>$product_id]);
  
  $search_nurse_lists = $this->db->query("SELECT * from users WHERE firstname LIKE '%$keyword%' AND user_type='NURSE' ")->result_array();  
  
  if(!empty($search_nurse_lists)){

    foreach($search_nurse_lists as $search_nurse_list)
    {

      if($search_nurse_list['image']!=''){

        $search_nurse_list['image']=SITE_URL.'uploads/images/'.$search_nurse_list['image'];
      }
      else{
        $search_nurse_list['image'] = '';
      }
      $data[] = $search_nurse_list;
    }

    $ressult['result'] = $data;
    $ressult['message'] ='successful';
    $ressult['status'] = '1';
    $json = $ressult;
  }

  else
  {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;                              

  }



  header('Content-type: application/json');
  echo json_encode($json);
  die;
}



/************** delete_user ****************/
public function delete_user(){

  $arr_whr = ['id'=>$this->input->get_post('user_id', TRUE)];

  $check_user_exists = $this->webservice_model->get_where('users', $arr_whr);
  

  if(!empty($check_user_exists)){

    $delete_id = $this->webservice_model->delete_data('users', $arr_whr);

    $ressult['result']='successfull';
    $ressult['message']='User Deleted successfull';
    $ressult['status']='1';
    $json = $ressult;
  }else{

    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessfull';
    $ressult['status']='0';
    $json = $ressult;
  }


  header('Content-type: application/json');
  echo json_encode($json);

}



/************** delete_nurse ****************/
public function delete_nurse(){

  $arr_whr = ['id'=>$this->input->get_post('nurse_id', TRUE)];

  $check_nurse_exists = $this->webservice_model->get_where('users', $arr_whr);
  

  if(!empty($check_nurse_exists)){

    $delete_id = $this->webservice_model->delete_data('users', $arr_whr);

    $ressult['result']='successfull';
    $ressult['message']='Nurse Deleted successfull';
    $ressult['status']='1';
    $json = $ressult;
  }else{

    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessfull';
    $ressult['status']='0';
    $json = $ressult;
  }


  header('Content-type: application/json');
  echo json_encode($json);

}



/*************  nurse_details *************/
public function nurse_details()
{                     
  $arr_whr = ['id'=>$this->input->get_post('nurse_id'),'USER_TYPE'=>'NURSE'];
  $nurse_details = $this->webservice_model->get_where('users',$arr_whr);


  if (!empty($nurse_details)) {
    $nurse_details[0]['rating'] = '1';
    $nurse_details[0]['electronic_notes'] = [];


    $nurse_details[0]['Current'] = $this->get_all_current_nurse_request_list($arr_whr['id'],'Current');
    $nurse_details[0]['Scheduled'] = $this->get_all_current_nurse_request_list($arr_whr['id'],'Scheduled');
    $nurse_details[0]['Completed'] = $this->get_all_current_nurse_request_list($arr_whr['id'],'Completed');
    $nurse_details[0]['Cancel'] = $this->get_all_current_nurse_request_list($arr_whr['id'],'Cancel');
    
    if($nurse_details[0]['image']==''){
     $nurse_details[0]['image'] = '';
   }
   else{
     $nurse_details[0]['image'] = SITE_URL.'uploads/images/'.$nurse_details[0]['image'];
   }
   $ressult['result']=$nurse_details[0];
   $ressult['message']='successful';
   $ressult['status']='1';
   $json = $ressult;                      


 }
 else {
  $ressult['result']='Data Not Found';
  $ressult['message']='unsuccessful';
  $ressult['status']='0';
  $json = $ressult;
}

header('Content-type: application/json');
echo json_encode($json);

}


/*************  user_details *************/
public function user_details()
{                     
  $arr_whr = ['id'=>$this->input->get_post('user_id'),'user_type'=>'USER'];
  $user_details = $this->webservice_model->get_where('users',$arr_whr);


  if (!empty($user_details)) {

    $user_details[0]['Current'] = $this->get_all_current_request_list($arr_whr['id'],'Current');

    //echo $this->db->last_query();
    $user_details[0]['Scheduled'] = $this->get_all_current_request_list($arr_whr['id'],'Scheduled');
    $user_details[0]['Completed'] = $this->get_all_current_request_list($arr_whr['id'],'Completed');
    $user_details[0]['Cancel'] = $this->get_all_current_request_list($arr_whr['id'],'Cancel');

    $family_members = $this->webservice_model->get_where('add_family_members',['user_id'=>$user_details[0]['id']]);

    $authorize_person_detail = $this->webservice_model->get_where('authorize_person',['user_id'=>$user_details[0]['id']]);

    $get_request_details = $this->webservice_model->get_where('request_care',['user_id'=>$arr_whr['id'],'status'=>'Current']);
	//var_dump($get_request_details);die();
    //$get_plan_details = $this->webservice_model->get_where('pricing',['id'=>$get_request_details[0]['user_id']]);
	$get_plan_subscription = $this->webservice_model->get_where('user_subscription', array(
		'user_id'=>	$user_details[0]['id'],
		'created_on <' => date("Y-m-d h:i:s"),
		'expire_on > '=> date("Y-m-d h:i:s")
	));
	$get_plan_details = $this->webservice_model->get_where('pricing',['id'=>$get_plan_subscription[0]['plan_id']]);
	
	$get_visit_request_details = $this->webservice_model->get_where('request_care',	array(
		'user_id'=>	$user_details[0]['id'],
		'plan_id' => $get_request_details[0]['plan_id'],
		'created_date >=' => $get_plan_subscription[0]['created_on'] ? $get_plan_subscription[0]['created_on'] : date("Y-m-d h:i:s"),
		'created_date <= '=> $get_plan_subscription[0]['expire_on'] ? $get_plan_subscription[0]['expire_on'] : date("Y-m-d h:i:s")
	));

	if(!empty($get_plan_details)){
      $plan_details = $get_plan_details[0];
    }
    else{
     $plan_details = null;
   }

   if(!empty($authorize_person_detail)){
    $authorize_person_detail = $authorize_person_detail[0];
  }
  else{

    $authorize_person_detail = [];
  }

  $family_data = [];
  $doctor_information = [];
  if(!empty($family_members)){
    foreach($family_members as $family_member){

      if($family_member['relation']=='Own'){
       $doctor_information = $family_member;
     }

     $family_data[] = $family_member;    
   }
 }

 $user_details[0]['electronic_notes'] = [];
 $user_details[0]['authorize_person_detail'] = $authorize_person_detail;
 $user_details[0]['doctor_information'] = $doctor_information;
 $user_details[0]['family_members'] = $family_data;
 $user_details[0]['plan_detail'] = $plan_details;
 $user_details[0]['plan_subscription'] = $get_plan_subscription;
 $user_details[0]['visit_request_details'] = $get_visit_request_details;
 if($user_details[0]['image']==''){
  $user_details[0]['image'] = '';
}
else{
  $user_details[0]['image'] = SITE_URL.'uploads/images/'.$user_details[0]['image'];
}
$ressult['result']=$user_details[0];
$ressult['message']='successful';
$ressult['status']='1';
$json = $ressult;  

    //echo "<pre>";print_r($user_details);                    


}
else {
  $ressult['result']='Data Not Found';
  $ressult['message']='unsuccessful';
  $ressult['status']='0';
  $json = $ressult;
}

header('Content-type: application/json');
echo json_encode($json);

}

/*************  get_all_current_request_list *************/
public function get_all_current_request_list($user_id,$status)
{ 

  $arr_whr = ['user_id'=>$user_id,'status'=>$status];





  $user_details = $this->webservice_model->get_where('users',['id'=>$user_id]);
  $lat = $user_details[0]['lat'];
  $lon = $user_details[0]['lon'];
  if($status=='Current'){
   $request_care_lists = $this->db->query("SELECT * from request_care WHERE user_id='$user_id' AND status='Current' order by created_date DESC LIMIT 1")->result_array();

   //echo $this->db->last_query();
 }
 else
 {

   $request_care_lists = $this->webservice_model->get_where('request_care',$arr_whr);

 }
  // echo $this->db->last_query();
  // echo "<pre>";print_r($request_care_lists);


 if (!empty($request_care_lists)) {

  foreach($request_care_lists as $request_care_list)
  {
    $nurse_details = $this->webservice_model->get_where('users',['id'=>$request_care_list['nurse_id']]);

    $distance = $this->webservice_model->distance($lat, $lon, $nurse_details[0]['lat'], $nurse_details[0]['lon'], $miles = false);

    $service_type_details = $this->webservice_model->get_where('service_types',['id'=>$request_care_list['service_type_id']]);

    $care_details = $this->webservice_model->get_where('cares',['id'=>$request_care_list['care_id']]);

    $service_id_arr = explode(',', $request_care_list['service_id']);
    $services = [];
    for ($i=0; $i < count($service_id_arr); $i++) { 
     $service_details = $this->webservice_model->get_where('services',['id'=>$service_id_arr[$i]]);
     $services[] = $service_details[0]['service_name'];
   }



   $request_care_list['nurse_unique_id'] = $nurse_details[0]['nurse_id'];
   $request_care_list['nurse_name'] = $nurse_details[0]['firstname'].' '.$nurse_details[0]['lastname'];
   $request_care_list['nurse_type'] = $nurse_details[0]['nurse_type'];
   $request_care_list['gender'] = $nurse_details[0]['gender'];
   $request_care_list['phone_number'] = $nurse_details[0]['phone_number'];
   $request_care_list['lat'] = $nurse_details[0]['lat'];
   $request_care_list['lon'] = $nurse_details[0]['lon'];
   $request_care_list['distance'] = number_format($distance,2);
   if($nurse_details[0]['image']!=''){
     $request_care_list['nurse_image'] = SITE_URL.'uploads/images/'.$nurse_details[0]['image'];
   }
   else{
    $nurse_details[0]['image'] = '';
  }


  $time =  ($distance * 2);
  $arrivel_time = $this->convertToHoursMins($time);

  $visit_details = $this->webservice_model->get_where('visits',['id'=>$request_care_list['visit_id']]);


   // $visit_time_duration = '';


  $time1 = strtotime($request_care_list['start_time']);
  $time2 = strtotime($request_care_list['end_time']);
  $visit_seconds = $time2 - $time1;
  $visit_time_duration = gmdate("H:i:s", $visit_seconds);
   // $visit_time_duration = round(abs($time2 - $time1) / 3600,2);
   //echo $difference;


  $request_care_list['service_type_name'] = $service_type_details[0]['service_type'];
  $request_care_list['care_type'] = $care_details[0]['type'];
  $request_care_list['visit_type'] = $visit_details[0]['type'];
  $request_care_list['visit_charge'] = '100.00';
  $request_care_list['visit_time_duration'] = $visit_time_duration;
  $request_care_list['arrivel_time'] =$arrivel_time;
  $request_care_list['services'] = $services;
  $data[] = $request_care_list;
}

$current_data = $data;                    


}
else {
  $current_data = array();  
}

//echo "<pre>";print_r($current_data);
return $current_data;

}



/*************  get_all_current_request_list *************/
public function get_all_current_nurse_request_list($nurse_id,$status)
{ 

  $arr_whr = ['nurse_id'=>$nurse_id,'status'=>$status];

  $user_details = $this->webservice_model->get_where('users',['id'=>$nurse_id]);
  $lat = $user_details[0]['lat'];
  $lon = $user_details[0]['lon'];
  if($status=='Current'){
   $request_care_lists = $this->db->query("SELECT * from request_care WHERE nurse_id='$nurse_id' AND status='Current' order by created_date DESC LIMIT 1")->result_array();

   //echo $this->db->last_query();
 }
 else
 {

   $request_care_lists = $this->webservice_model->get_where('request_care',$arr_whr);

 }
  // echo $this->db->last_query();
  // echo "<pre>";print_r($request_care_lists);


 if (!empty($request_care_lists)) {

  foreach($request_care_lists as $request_care_list)
  {
    $user_details = $this->webservice_model->get_where('users',['id'=>$request_care_list['user_id']]);

    $distance = $this->webservice_model->distance($lat, $lon, $user_details[0]['lat'], $user_details[0]['lon'], $miles = false);

    $service_type_details = $this->webservice_model->get_where('service_types',['id'=>$request_care_list['service_type_id']]);

    $care_details = $this->webservice_model->get_where('cares',['id'=>$request_care_list['care_id']]);

    $service_id_arr = explode(',', $request_care_list['service_id']);
    $services = [];
    for ($i=0; $i < count($service_id_arr); $i++) { 
     $service_details = $this->webservice_model->get_where('services',['id'=>$service_id_arr[$i]]);
     $services[] = $service_details[0]['service_name'];
   }


   $request_care_list['user_name'] = $user_details[0]['firstname'].' '.$user_details[0]['lastname'];
   $request_care_list['gender'] = $user_details[0]['gender'];
   $request_care_list['phone_number'] = $user_details[0]['phone_number'];
   $request_care_list['lat'] = $user_details[0]['lat'];
   $request_care_list['lon'] = $user_details[0]['lon'];
   $request_care_list['distance'] = number_format($distance,2);
   if($user_details[0]['image']!=''){
     $request_care_list['user_image'] = SITE_URL.'uploads/images/'.$user_details[0]['image'];
   }
   else{
    $user_details[0]['image'] = '';
  }


  $time =  ($distance * 2);
  $arrivel_time = $this->convertToHoursMins($time);

  $visit_details = $this->webservice_model->get_where('visits',['id'=>$request_care_list['visit_id']]);


   // $visit_time_duration = '';


  $time1 = strtotime($request_care_list['start_time']);
  $time2 = strtotime($request_care_list['end_time']);
  $visit_seconds = $time2 - $time1;
  $visit_time_duration = gmdate("H:i:s", $visit_seconds);
   // $visit_time_duration = round(abs($time2 - $time1) / 3600,2);
   //echo $difference;


  $request_care_list['service_type_name'] = $service_type_details[0]['service_type'];
  $request_care_list['care_type'] = $care_details[0]['type'];
  $request_care_list['visit_type'] = $visit_details[0]['type'];
  $request_care_list['visit_charge'] = '100.00';
  $request_care_list['visit_time_duration'] = $visit_time_duration;
  $request_care_list['arrivel_time'] =$arrivel_time;
  $request_care_list['services'] = $services;
  $data[] = $request_care_list;
}

$current_data = $data;                    


}
else {
  $current_data = array();  
}

//echo "<pre>";print_r($current_data);
return $current_data;

}





/************* signup_nurse1 function *************/

public function signup_nurse1(){

  // $nurse_id = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);

  $nurse_id = 'NURSE'.rand(1,1000000);

  $arr_data = [
    'firstname'=>$this->input->get_post('firstname'),
    'lastname'=>$this->input->get_post('lastname'),
    'email'=>$this->input->get_post('email'),
    'gender'=>$this->input->get_post('gender'),
    'nurse_id'=>$nurse_id,
    'phone_number'=>$this->input->get_post('phone_number'),
    'date_of_birth'=>$this->input->get_post('date_of_birth'),
    'lat'=>$this->input->get_post('lat'),
    'lon'=>$this->input->get_post('lon'),         
    'user_type'=>'NURSE'        
  ];


   //echo "<pre>";print_r($arr_data);die;

  $phone_number = $arr_data['phone_number'];
  $email = $arr_data['email'];

  $arr_whr = "(phone_number='$phone_number' or email = '$email')";

  $chk_usr_exist = $this->webservice_model->get_where('users',$arr_whr);
  
  //echo $this->db->last_query();die;

  if ($chk_usr_exist) {

    $ressult['result']='Phone Number Or Email Already Exist';
    $ressult['message']='unsuccessfull';
    $ressult['status']='0';
    $json = $ressult;

    header('Content-type:application/json');
    echo json_encode($json);
    die;
  }


  $id = $this->webservice_model->insert_data('users',$arr_data);

  if ($id=="") {
    $json = ['result'=>'unsuccessfull','status'=>0,'message'=>'data not found'];
  }else{


    $arr_whr_user = ['id'=>$id];
    $get_user = $this->webservice_model->get_where('users',$arr_whr_user);  


    $get_user[0]['password'] = base64_decode($get_user[0]['password']);     
    $ressult['result']=$get_user[0];
    $ressult['message']='successfull';
    $ressult['status']='1';
    $json = $ressult;
  }

  header('Content-type:application/json');
  echo json_encode($json);

}

/************* add_new_visit function *************/

public function add_new_visit(){

 $arr_data = [
   'type'=>$this->input->get_post('type')    
 ];


 $id = $this->webservice_model->insert_data('visits',$arr_data);

 if ($id=="") {
  $json = ['result'=>'unsuccessfull','message'=>'data not found','status'=>0];
}else{


  $arr_whr_visit_id = ['id'=>$id];
  $get_visit = $this->webservice_model->get_where('visits',$arr_whr_visit_id);  

  $ressult['result']=$get_visit[0];
  $ressult['message']='successfull';
  $ressult['status']='1';
  $json = $ressult;
}

header('Content-type:application/json');
echo json_encode($json);

}



/*************  visit_list *************/
public function visit_list()
{                     

  $visit_lists = $this->webservice_model->get_all('visits');


  if ($visit_lists) {

    foreach($visit_lists as $visit_list)
    {

      $data[] = $visit_list;

    }

    $ressult['result']=$data;
    $ressult['message']='successful';
    $ressult['status']='1';
    $json = $ressult;                      


  }
  else {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);

}

/************* delete_visit function *************/

public function delete_visit(){

 $arr_whr = [
   'id'=>$this->input->get_post('visit_id')  
 ];

 $check_visit_exists = $this->webservice_model->get_where('visits',$arr_whr);

 if (empty($check_visit_exists)) {
  $json = ['result'=>'unsuccessfull','message'=>'data not found','status'=>0];
}else{

  $delete_visit_id = $this->webservice_model->delete_data('visits',$arr_whr);  
  
  $ressult['result']= 'successfull';
  $ressult['message']='Delete Visit Successfully';
  $ressult['status']='1';
  $json = $ressult;
}

header('Content-type:application/json');
echo json_encode($json);

}

/*************  care_list *************/
public function care_list()
{                     

  $care_lists = $this->webservice_model->get_all('cares');


  if ($care_lists) {

    foreach($care_lists as $care_list)
    {

      $data[] = $care_list;

    }

    $ressult['result']=$data;
    $ressult['message']='successful';
    $ressult['status']='1';
    $json = $ressult;                      


  }
  else {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);

}

/*************  service_type_list *************/
public function service_type_list()
{                     

  $service_type_lists = $this->webservice_model->get_all('service_types');


  if ($service_type_lists) {

    foreach($service_type_lists as $service_type_list)
    {

      $data[] = $service_type_list;

    }

    $ressult['result']=$data;
    $ressult['message']='successful';
    $ressult['status']='1';
    $json = $ressult;                      


  }
  else {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);

}

public function update_service_type(){
  $updated_data = [
    'id'=>1,
    'description'=>'Nursing care offers the highest level of care for adults (age 18+) outside of a hospital setting. This type of care is typically recommended for those who need skilled nursing care. A nurse can provide care to individuals who were recently discharged from a medical facility, need wound care, assistance with glucose monitoring, chronic medical conditions, severe pain, permanent disabilities, cognitive disorders (e.g. Alzheimer’s, dementia) and those acquiring help with activities of daily living and much more!'
  ];
  $where = ['id'=>1];
  $this->webservice_model->update_data('service_types',$updated_data, $where);//dpk
  header('Content-type: application/json');
  echo json_encode(array("message"=>"success"));
}

/************* add_pricing_plan function *************/

public function add_pricing_plan(){

 $arr_data = [
   'service_type_id'=>$this->input->get_post('service_type_id'),
   'plan_name'=>$this->input->get_post('plan_name'),
   'visits'=>$this->input->get_post('visits'),
   'free_visits'=>$this->input->get_post('free_visits'),
   'price'=>$this->input->get_post('price'), 
   'plan_type'=>'Monthly', 

 ];


 $pricing_id = $this->webservice_model->insert_data('pricing',$arr_data);
 
 if($pricing_id!=''){

 $pricing_details = $this->webservice_model->get_where('pricing',['id'=>$pricing_id]);
 $ressult['result']=$pricing_details;
 $ressult['message']='successfull';
 $ressult['status']='1';
 $json = $ressult; 
 } 
else{
  $ressult['result']="unsuccessfull";
 $ressult['message']='Some Problem Occured';
 $ressult['status']='1';
 $json = $ressult; 
  }
 

header('Content-type:application/json');
echo json_encode($json);

}

/************* add_pricing_plan_daily function *************/

public function add_pricing_plan_daily(){

 $arr_data = [
   'pricing_id'=>$this->input->get_post('pricing_id'),
   'type'=>$this->input->get_post('plan_name'),
   'price'=>$this->input->get_post('price') 
  ];


 $pricing_detail_id = $this->webservice_model->insert_data('pricing_details',$arr_data);
 
 if($pricing_detail_id!=''){

 $pricing_details = $this->webservice_model->get_where('pricing_details',['id'=>$pricing_detail_id]);
 $ressult['result']=$pricing_details;
 $ressult['message']='successfull';
 $ressult['status']='1';
 $json = $ressult; 
 } 
else{
  $ressult['result']="unsuccessfull";
 $ressult['message']='Some Problem Occured';
 $ressult['status']='1';
 $json = $ressult; 
  }
 

header('Content-type:application/json');
echo json_encode($json);

}


/************* update_pricing_plan function *************/

public function update_pricing_plan(){

 $plan_id = $this->input->get_post('plan_id');
 $arr_data = [
   'service_type_id'=>$this->input->get_post('service_type_id'),
   'plan_name'=>$this->input->get_post('plan_name'),
   'daily_price'=>$this->input->get_post('daily_price')     
 ];
 
 $arr_whr = ['id'=>$plan_id];
 $chk_pricing_exists = $this->webservice_model->get_where('pricing',$arr_whr);
 
 if(!empty($chk_pricing_exists)){


   $this->webservice_model->update_data('pricing',$arr_data);

   $arr_pricing_data = [
     'pricing_id'=>$plan_id,
     'visits'=>$this->input->get_post('visits'),
     'free_visits'=>$this->input->get_post('free_visits'),
     'price'=>$this->input->get_post('price')     
   ];


   $pricing_details_id = $this->webservice_model->update_data('pricing_details',$arr_pricing_data);

  //$arr_whr_fam_mem = ['id'=>$id];
  //$get_family_member = $this->webservice_model->get_where('add_family_members',$arr_whr_fam_mem);  

   $ressult['result']="Add Plan Successfully";
   $ressult['message']='successfull';
   $ressult['status']='1';
   $json = $ressult;

 }

 else
 {
  $ressult['result']="No Data Found";
  $ressult['message']='unsuccessfull';
  $ressult['status']='0';
  $json = $ressult; 
}

header('Content-type:application/json');
echo json_encode($json);

}

/************** remove_plan_monthly ****************/
public function remove_plan_monthly(){

  $arr_whr = ['plan_name'=>$this->input->get_post('plan_name', TRUE)];

  $check_plan_exists = $this->webservice_model->get_where('pricing', $arr_whr);
  

  if(!empty($check_plan_exists)){

    $res = $this->webservice_model->delete_data('pricing', $arr_whr);

    $ressult['result']='successfull';
    $ressult['message']='plan delete successfully';
    $ressult['status']='1';
    $json = $ressult;
  }else{

    $ressult['result']='unsuccessfull';
    $ressult['message']='Data Not Found';
    $ressult['status']='0';
    $json = $ressult;
  }


  header('Content-type: application/json');
  echo json_encode($json);

}

/************** remove_plan_monthly ****************/
public function remove_plan_hourly(){

  $arr_whr = ['id'=>$this->input->get_post('id', TRUE)];

  $check_plan_exists = $this->webservice_model->get_where('pricing_details', $arr_whr);
  

  if(!empty($check_plan_exists)){

    $res = $this->webservice_model->delete_data('pricing_details', $arr_whr);

    $ressult['result']='successfull';
    $ressult['message']='plan delete successfully';
    $ressult['status']='1';
    $json = $ressult;
  }else{

    $ressult['result']='unsuccessfull';
    $ressult['message']='Data Not Found';
    $ressult['status']='0';
    $json = $ressult;
  }


  header('Content-type: application/json');
  echo json_encode($json);

}



/*************  service_list *************/
public function service_list()
{                     
  $service_type_id = $this->input->get_post('service_type_id');
  $arr_whr = ['service_type_id'=>$service_type_id];
  $service_lists = $this->webservice_model->get_where('services',$arr_whr);


  if ($service_lists) {

    foreach($service_lists as $service_list)
    {

      $data[] = $service_list;

    }

    $ressult['result']=$data;
    $ressult['message']='successful';
    $ressult['status']='1';
    $json = $ressult;                      


  }
  else {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);

}


/************* add_new_service function *************/

public function add_new_service(){

 $arr_data = [
   'service_type_id'=>$this->input->get_post('service_type_id'),
   'service_name'=>$this->input->get_post('service_name')     
 ];


 $id = $this->webservice_model->insert_data('services',$arr_data);

 if ($id=="") {
  $json = ['result'=>'unsuccessfull','message'=>'data not found','status'=>0];
}else{


  $arr_whr_service_id = ['id'=>$id];
  $get_service = $this->webservice_model->get_where('services',$arr_whr_service_id);  

  $ressult['result']=$get_service[0];
  $ressult['message']='successfull';
  $ressult['status']='1';
  $json = $ressult;
}

header('Content-type:application/json');
echo json_encode($json);

}


/************* delete_service function *************/

public function delete_service(){

 $arr_whr = [
   'id'=>$this->input->get_post('service_id')  
 ];

 $check_service_exists = $this->webservice_model->get_where('services',$arr_whr);

 if (empty($check_service_exists)) {
  $json = ['result'=>'unsuccessfull','message'=>'data not found','status'=>0];
}else{

  $delete_service_id = $this->webservice_model->delete_data('services',$arr_whr);  
  
  $ressult['result']= 'successfull';
  $ressult['message']='Delete Service Successfully';
  $ressult['status']='1';
  $json = $ressult;
}

header('Content-type:application/json');
echo json_encode($json);

}



/*************  get_all_price_list *************/
public function get_all_price_list()
{    
  //$pricing_lists = $this->db->query("SELECT * from pricing WHERE plan_type='Monthly' GROUP BY plan_name ORDER BY plan_name DESC ")->result_array();

$pricing_lists = $this->db->query("SELECT * from pricing WHERE plan_type='Monthly' ORDER BY plan_name DESC ")->result_array();

  //echo $this->db->last_query();
  


  if (!empty($pricing_lists)) {

    foreach($pricing_lists as $pricing_list)
    {

      $service_type_id = $pricing_list['service_type_id']; 

      $pricing = $this->webservice_model->get_where('pricing',['service_type_id'=>$service_type_id,'plan_name'=>$pricing_list['plan_name']]);
      $price_details = [];
      if(!empty($pricing)){ 
        foreach($pricing as $price)

        {
          $price_details[] = $price;
        }
      }

      if($pricing_list['plan_type']=='Monthly'){
      $pricing_list['plan_detail'] = $price_details;
      }

      else{

       $get_pricing = $this->webservice_model->get_where('pricing_details',['pricing_id'=>$pricing_list['id']]);
       
       if(!empty($get_pricing)){
        foreach ($get_pricing as $get_value) {
        $get_price[] = $get_value;
        }
       }

       $pricing_list['plan_detail'] = $get_price;

      }


      $data[] = $pricing_list;
    }

    $ressult['result']=$data;
    $ressult['message']='successful';
    $ressult['status']='1';
    $json = $ressult;                      


  }
  else {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);

}

/*************  get_all_price_list_daily *************/
public function get_all_price_list_daily()
{                 
  $pricing_lists = $this->webservice_model->get_where('pricing',['plan_type'=>'Hourly']);


  if (!empty($pricing_lists)) {

    foreach($pricing_lists as $pricing_list)
    {

      $pricing_details = $this->webservice_model->get_where('pricing_details',['pricing_id'=>$pricing_list['id']]);
      $pricing_list['pricing_details'] = $pricing_details;
      $data[] = $pricing_list;
    }

    $ressult['result']=$data;
    $ressult['message']='successful';
    $ressult['status']='1';
    $json = $ressult;                      


  }
  else {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);

}


/*************  price_details *************/
public function price_details()
{  
  $price_id = $this->input->get_post('price_id');                  
  $pricing = $this->webservice_model->get_where('pricing_details',['id'=>$price_id]);


  if (!empty($pricing)) {

    foreach($pricing as $val)
    {


      $pricing_details = $this->webservice_model->get_where('pricing',['id'=>$val['pricing_id']]);
      
      $service_type_details = $this->webservice_model->get_where('service_types',['id'=>$pricing_details[0]['service_type_id']]);
      
      $val['service_type'] = $service_type_details[0]['service_type'];
      $val['plan_name'] = $pricing_details[0]['plan_name'];

    //   if(!empty($pricing_details)) {
    //     $details = array();
    //     foreach ($pricing_details as $pricing_detail) {
    //       $details[] = $pricing_detail;
    //     }
      //echo "<pre>";print_r($details);
    //   }

      //die;


    //   $val['pricing_details'] = $details;
      $data[] = $val;

    }

    $ressult['result']=$data;
    $ressult['message']='successful';
    $ressult['status']='1';
    $json = $ressult;                      


  }
  else {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);

}

/*************  plan_pricing_detail *************/
public function plan_pricing_detail()
{  
  $price_id = $this->input->get_post('price_id');                  
  $pricing = $this->webservice_model->get_where('pricing',['id'=>$price_id]);


  if (!empty($pricing)) {

    foreach($pricing as $val)
    {


      // $pricing_details = $this->webservice_model->get_where('pricing',['id'=>$val['pricing_id']]);
      
      // $service_type_details = $this->webservice_model->get_where('service_types',['id'=>$pricing_details[0]['service_type_id']]);
      
      // $val['service_type'] = $service_type_details[0]['service_type'];
      // $val['plan_name'] = $pricing_details[0]['plan_name'];
      $data[] = $val;

    }

    $ressult['result']=$data;
    $ressult['message']='successful';
    $ressult['status']='1';
    $json = $ressult;                      


  }
  else {
    $ressult['result']='unsuccessful';
    $ressult['message']='Data Not Found';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);

}


/************* update_plan_pricing_detail function *************/
public function update_plan_pricing_detail(){

  $arr_whr = ['id'=>$this->input->get_post('price_id')];

  $check_pricing_plan_detail_exists = $this->webservice_model->get_where('pricing',$arr_whr);
  if (empty($check_pricing_plan_detail_exists))
  {
    $ressult['result']='unsuccessfull';
    $ressult['message']='Data Not Found';
    $ressult['status']='0';
    $json = $ressult;
  }

  $arr_data = [
    'visits'=>$this->input->get_post('visits'),      
    'free_visits'=>$this->input->get_post('free_visits'),      
    'price'=>$this->input->get_post('price')     
  ];


  $update = $this->webservice_model->update_data('pricing',$arr_data,$arr_whr);
  if ($update)
  {
    $data = $this->webservice_model->get_where('pricing',$arr_whr);

    $ressult['result']=$data[0];
    $ressult['message']='successfull';
    $ressult['status']='1';
    $json = $ressult;
  }
  else
  {
    $ressult['result']='unsuccessfull';
    $ressult['message']='Some Problem Occured';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);

}

/************* update_hour_plan_pricing_detail function *************/
public function update_hour_plan_pricing_detail(){

  $arr_whr = ['id'=>$this->input->get_post('price_id')];

  $check_pricing_plan_detail_exists = $this->webservice_model->get_where('pricing_details',$arr_whr);
  if (empty($check_pricing_plan_detail_exists))
  {
    $ressult['result']='unsuccessfull';
    $ressult['message']='Data Not Found';
    $ressult['status']='0';
    $json = $ressult;
  }

  $arr_data = [
    'type'=>$this->input->get_post('plan_name'),      
    'price'=>$this->input->get_post('price')     
  ];


  $update = $this->webservice_model->update_data('pricing_details',$arr_data,$arr_whr);
  if ($update)
  {
    $data = $this->webservice_model->get_where('pricing_details',$arr_whr);

    $ressult['result']=$data[0];
    $ressult['message']='successfull';
    $ressult['status']='1';
    $json = $ressult;
  }
  else
  {
    $ressult['result']='unsuccessfull';
    $ressult['message']='Some Problem Occured';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);

}



/************* Request Care function *************/

public function request_care(){

 $arr_data = [
   'user_id'=>$this->input->get_post('user_id'),
   'request_id'=>'REQUEST'.rand(1,1000000),
   'visit_id'=>$this->input->get_post('visit_id'),
   'nurse_id'=>$this->input->get_post('nurse_id'),
   'care_id'=>$this->input->get_post('care_id'),
   'care_person'=>$this->input->get_post('care_person'),
   'medical_facility'=>$this->input->get_post('medical_facility'),
   'doctor_order'=>$this->input->get_post('doctor_order'),
   'needs'=>$this->input->get_post('needs'),
   'contact_number'=>$this->input->get_post('contact_number'),
   'contact_address'=>$this->input->get_post('contact_address'),         
   'authorize_person'=>$this->input->get_post('authorize_person'),                
   'service_type_id'=>$this->input->get_post('service_type_id'),         
   'service_id'=>$this->input->get_post('service_id')
 ];


 $request_id = $this->webservice_model->insert_data('request_care',$arr_data);

 if ($request_id=="") {
  $json = ['result'=>'unsuccessfull','status'=>0,'message'=>'data not found'];
}else{


  $arr_whr_request = ['id'=>$request_id];
  $get_request_care = $this->webservice_model->get_where('request_care',$arr_whr_request); 

  $get_service_type = $this->webservice_model->get_where('service_types',['id'=>$get_request_care[0]['service_type_id']]); 

  $get_request_care[0]['service_type'] = $get_service_type[0]['service_type'];

  $get_nurse = $this->webservice_model->get_where('users',['nurse_id'=>$get_request_care[0]['nurse_id']]); 

  $get_request_care[0]['nurse_type'] = $get_nurse[0]['nurse_type'];


  $service_ids = explode(',', $get_request_care[0]['service_id']);

  if(!empty($service_ids)){

    foreach ($service_ids as $service_id) {

      $get_services = $this->webservice_model->get_where('services',['id'=>$service_id]); 
      $service_list[] = $get_services[0];
    } 

  }
  //echo "<pre>";print_r($service_list);die;

  $get_request_care[0]['services'] = $service_list;

  $ressult['result']=$get_request_care[0];
  $ressult['message']='successfull';
  $ressult['status']='1';
  $json = $ressult;
}

header('Content-type:application/json');
echo json_encode($json);

}


/*************  get_all_request_list *************/
public

function get_all_request_list()
{ 

  $nurse_id = $this->input->get_post('nurse_id');                                       
  $arr_whr = ['nurse_id'=>$nurse_id,'status'=>'Pending'];
  $request_care_lists = $this->webservice_model->get_where('request_care',$arr_whr);

  //echo "<pre>";print_r($request_care_lists);die;


  if (!empty($request_care_lists)) {
    foreach($request_care_lists as $request_care_list){
     $user_details = $this->webservice_model->get_where('users',['id'=>$request_care_list['user_id']]);  
     $request_care_list['user_details'] = $user_details[0];
     //$user_details = $this->webservice_model->get_where('users',['id'=>$request_care_list['user_id']]);  
     //echo $this->db->last_query();
     //$request_care_list['user_details'] = $user_details[0];
     $data[] = $request_care_list;   
   }
   $ressult['result']=$data;
   $ressult['message']='successful';
   $ressult['status']='1';
   $json = $ressult;
 }
 else {
  $ressult['result']='Data Not Found';
  $ressult['message']='unsuccessful';
  $ressult['status']='0';
  $json = $ressult;
}

header('Content-type: application/json');
echo json_encode($json);

}

/************* request_details function *************/
public function request_details(){

  $arr_whr = ['id'=>$this->input->get_post('request_id')];

  $get_request_care = $this->webservice_model->get_where('request_care',$arr_whr);

  if (!empty($get_request_care))
  {
    //$update_request_care = $this->webservice_model->get_where('request_care',$arr_whr);
    $service_type_details = $this->webservice_model->get_where('service_types',['id'=>$get_request_care[0]['service_type_id']]);

    // echo $this->db->last_query();

    $get_care = $this->webservice_model->get_where('cares',['id'=>$get_request_care[0]['care_id']]);
    
    $service_id_arr = explode(',', $get_request_care[0]['service_id']);
      // echo "<pre>";print_r($service_id_arr);
    $services = [];
    for ($i=0; $i < count($service_id_arr); $i++) { 
     $service_details = $this->webservice_model->get_where('services',['id'=>$service_id_arr[$i]]);
       //echo $this->db->last_query();
     $services[] = $service_details[0]['service_name'];
   }

   $get_request_care[0]['service_type_name'] = $service_type_details[0]['service_type'];
   $get_request_care[0]['services'] = $services;
   $get_request_care[0]['care_name'] = $get_care[0]['type'];
   $ressult['result']=$get_request_care[0];
   $ressult['message']='successfull';
   $ressult['status']='1';
   $json = $ressult;
 }
 else
 {
  $ressult['result']='Data Not Found';
  $ressult['message']='unsuccessfull';
  $ressult['status']='0';
  $json = $ressult;
}

header('Content-type: application/json');
echo json_encode($json);

}



/*************  get_all_complete_request_list *************/
public

function get_all_complete_request_list()
{ 

  $user_id = $this->input->get_post('user_id');                    
  $status = $this->input->get_post('status');                    
  $arr_whr = ['user_id'=>$user_id,'status'=>$status];
  $request_care_lists = $this->webservice_model->get_where('request_care',$arr_whr);


  if (!empty($request_care_lists)) {
    foreach($request_care_lists as $request_care_list){
     $nurse_details = $this->webservice_model->get_where('users',['nurse_id'=>$request_care_list['nurse_id']]);  
     $request_care_list['nurse_details'] = $nurse_details[0];
     //$user_details = $this->webservice_model->get_where('users',['id'=>$request_care_list['user_id']]);  
     //echo $this->db->last_query();
     //$request_care_list['user_details'] = $user_details[0];
     $data[] = $request_care_list;   
   }
   $ressult['result']=$data;
   $ressult['message']='successful';
   $ressult['status']='1';
   $json = $ressult;
 }
 else {
  $ressult['result']='Data Not Found';
  $ressult['message']='unsuccessful';
  $ressult['status']='0';
  $json = $ressult;
}

header('Content-type: application/json');
echo json_encode($json);

}


/*************  get_all_complete_request_list *************/
public

function get_all_scheduled_request_list()
{ 

  $user_id = $this->input->get_post('user_id');                    
  $status = $this->input->get_post('status');                    
  $arr_whr = ['user_id'=>$user_id,'status'=>$status];
  $request_care_lists = $this->webservice_model->get_where('request_care',$arr_whr);


  if (!empty($request_care_lists)) {
    foreach($request_care_lists as $request_care_list){
     $nurse_details = $this->webservice_model->get_where('users',['nurse_id'=>$request_care_list['nurse_id']]);  
     $care_details = $this->webservice_model->get_where('cares',['id'=>$request_care_list['care_id']]); 
     //echo $this->db->last_query();
     $request_care_list['type'] = $care_details[0]['type'];
     $request_care_list['nurse_details'] = $nurse_details[0];
     $data[] = $request_care_list;   
   }
   $ressult['result']=$data;
   $ressult['message']='successful';
   $ressult['status']='1';
   $json = $ressult;
 }
 else {
  $ressult['result']='Data Not Found';
  $ressult['message']='unsuccessful';
  $ressult['status']='0';
  $json = $ressult;
}

header('Content-type: application/json');
echo json_encode($json);

}

/*************  get_all_complete_request_list *************/
public

function get_all_scheduled_request_list_nurse()
{ 

  $nurse_id = $this->input->get_post('nurse_id');                    
  $status = $this->input->get_post('status');                    
  $arr_whr = ['nurse_id'=>$nurse_id,'status'=>$status];
  $request_care_lists = $this->webservice_model->get_where('request_care',$arr_whr);


  if (!empty($request_care_lists)) {
    foreach($request_care_lists as $request_care_list){
     $user_details = $this->webservice_model->get_where('users',['id'=>$request_care_list['user_id']]);  
     $care_details = $this->webservice_model->get_where('cares',['id'=>$request_care_list['care_id']]); 
     //echo $this->db->last_query();
     $request_care_list['type'] = $care_details[0]['type'];
     $request_care_list['user_details'] = $user_details[0];
     $data[] = $request_care_list;   
   }
   $ressult['result']=$data;
   $ressult['message']='successful';
   $ressult['status']='1';
   $json = $ressult;
 }
 else {
  $ressult['result']='Data Not Found';
  $ressult['message']='unsuccessful';
  $ressult['status']='0';
  $json = $ressult;
}

header('Content-type: application/json');
echo json_encode($json);

}


/************* request_cancel function *************/
public function request_cancel(){

  $arr_whr = ['id'=>$this->input->get_post('request_id')];

  $get_request_care = $this->webservice_model->get_where('request_care',$arr_whr);
  if ($get_request_care[0]['id'] == "")
  {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessfull';
    $ressult['status']='0';
    $json = $ressult;

    header('Content-type:application/json');
    echo json_encode($json);
    die;
  }


  $update_request_care = $this->webservice_model->update_data('request_care',['status'=>'Cancel'],$arr_whr);
  if ($res)
  {
    $update_request_care = $this->webservice_model->get_where('request_care',$arr_whr);

    $ressult['result']=$data[0];
    $ressult['message']='successfull';
    $ressult['status']='1';
    $json = $ressult;
  }
  else
  {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessfull';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);

}

/************* get_profile function *************/
public function get_profile(){

  $arr_whr = ['id'=>$this->input->get_post('admin_id'),'user_type'=>'ADMIN'];

  $get_admin = $this->webservice_model->get_where('users',$arr_whr);

  if (!empty($get_admin)) {  
    if($get_admin[0]['image']!=''){
     $get_admin[0]['image']=SITE_URL.'uploads/images/'.$get_admin[0]['image'];
   }
   else{
     $get_admin[0]['image']='';

   }
   $ressult['result']=$get_admin[0];
   $ressult['message']='successfull';
   $ressult['status']='1';
   $json = $ressult;

 }else{

  $json = ['result'=>'unsuccessfull','status'=>0,'message'=>'Data Not Found'];

}

header('Content-type: application/json');
echo json_encode($json);
}         

/************* user_update function *************/
public function user_update(){

  $arr_whr = ['id'=>$this->input->get_post('admin_id')];

  $get_admin_user = $this->webservice_model->get_where('users',$arr_whr);
  if ($get_admin_user[0]['id'] == "")
  {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessfull';
    $ressult['status']='0';
    $json = $ressult;

    header('Content-type:application/json');
    echo json_encode($json);
    die;
  }

  $arr_data = [
    'firstname'=>$this->input->get_post('firstname'),
    'lastname'=>$this->input->get_post('lastname'),
    'phone_number'=>$this->input->get_post('phone_number')      
  ];

  if (isset($_FILES['image']))
  {
                         //  unlink('uploads/images/'.$login[0]['image']);
   $n = rand(0, 100000);
   $img = "USER_IMG_" . $n . '.png';
   move_uploaded_file($_FILES['image']['tmp_name'], "uploads/images/" . $img);
   $arr_data['image'] = $img;        
 }


 $update = $this->webservice_model->update_data('users',$arr_data,$arr_whr);
 if ($update)
 {
  $data = $this->webservice_model->get_where('users',$arr_whr);
  if($data[0]['image']==''){

  $data[0]['image']='';
  }
  else{
    
  $data[0]['image']=SITE_URL.'uploads/images/'.$data[0]['image'];
  }

  $ressult['result']=$data[0];
  $ressult['message']='successfull';
  $ressult['status']='1';
  $json = $ressult;
}
else
{
  $ressult['result']='Data Not Found';
  $ressult['message']='unsuccessfull';
  $ressult['status']='0';
  $json = $ressult;
}

header('Content-type: application/json');
echo json_encode($json);

}


/************* user_update function *************/
public function update_phone_number(){

  $arr_whr = ['id'=>$this->input->get_post('user_id')];

  $get_user = $this->webservice_model->get_where('users',$arr_whr);
  if ($get_user[0]['id'] == "")
  {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessfull';
    $ressult['status']='0';
    $json = $ressult;

    header('Content-type:application/json');
    echo json_encode($json);
    die;
  }

  $arr_data = [
    'phone_number'=>$this->input->get_post('phone_number')      
  ];


  $update = $this->webservice_model->update_data('users',$arr_data,$arr_whr);
  if ($update)
  {
    $data = $this->webservice_model->get_where('users',$arr_whr);
    $data[0]['image']=SITE_URL.'uploads/images/'.$data[0]['image'];

    $ressult['result']=$data[0];
    $ressult['message']='successfull';
    $ressult['status']='1';
    $json = $ressult;
  }
  else
  {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessfull';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);

}

/************* update_address function *************/
public function update_address(){

  $arr_whr = ['id'=>$this->input->get_post('user_id')];

  $get_user = $this->webservice_model->get_where('users',$arr_whr);
  if ($get_user[0]['id'] == "")
  {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessfull';
    $ressult['status']='0';
    $json = $ressult;

    header('Content-type:application/json');
    echo json_encode($json);
    die;
  }

  $arr_data = [
    'address'=>$this->input->get_post('address')      
  ];


  $update = $this->webservice_model->update_data('users',$arr_data,$arr_whr);
  if ($update)
  {
    $data = $this->webservice_model->get_where('users',$arr_whr);
    $data[0]['image']=SITE_URL.'uploads/images/'.$data[0]['image'];

    $ressult['result']=$data[0];
    $ressult['message']='successfull';
    $ressult['status']='1';
    $json = $ressult;
  }
  else
  {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessfull';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);

}



/************* add_family_members function *************/

public function add_family_members(){

 $arr_data = [
   'user_id'=>$this->input->get_post('user_id'),
   'firstname'=>$this->input->get_post('firstname'),
   'lastname'=>$this->input->get_post('lastname'),
   'gender'=>$this->input->get_post('gender'),
   'date_of_birth'=>$this->input->get_post('date_of_birth'),
   'email'=>$this->input->get_post('email'),
   'phone_number'=>$this->input->get_post('phone_number'),     
   'doctor_name'=>$this->input->get_post('doctor_name'),     
   'doctor_email'=>$this->input->get_post('doctor_email'),     
   'doctor_phone_number'=>$this->input->get_post('doctor_phone_number')     
 ];


 $id = $this->webservice_model->insert_data('add_family_members',$arr_data);

 if ($id=="") {
  $json = ['result'=>'unsuccessfull','status'=>0,'message'=>'data not found'];
}else{


  $arr_whr_fam_mem = ['id'=>$id];
  $get_family_member = $this->webservice_model->get_where('add_family_members',$arr_whr_fam_mem);  

  $ressult['result']=$get_family_member[0];
  $ressult['message']='successfull';
  $ressult['status']='1';
  $json = $ressult;
}

header('Content-type:application/json');
echo json_encode($json);

}

/*************  get_all_nurse_list *************/
public

function get_all_family_members_list()
{ 

  $user_id = $this->input->get_post('user_id');                    
  $arr_whr = ['user_id'=>$user_id];
  $family_members_lists = $this->webservice_model->get_where('add_family_members',$arr_whr);


  if ($family_members_lists) {

    foreach($family_members_lists as $family_members_list)
    {

      $data[] = $family_members_list;

    }

    $ressult['result']=$data;
    $ressult['message']='successful';
    $ressult['status']='1';
    $json = $ressult;                      


  }
  else {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);

}

/************* add_new_blog function *************/

  public function add_new_blog(){


    $arr_data = [
      'blog_name'=>$this->input->get_post('blog_name'),
      'author_name'=>$this->input->get_post('author_name'),
      'blog_description'=>$this->input->get_post('blog_description'),
      'created_date'=>$this->input->get_post('blog_date')
    ];

    if (isset($_FILES['blog_image']))
    {
                         //  unlink('uploads/images/'.$login[0]['image']);
     $n = rand(0, 100000);
     $img = "BLOG_IMG_" . $n . '.png';
     move_uploaded_file($_FILES['blog_image']['tmp_name'], "uploads/images/" . $img);
     $arr_data['blog_image'] = $img;        
   }


  $id = $this->webservice_model->insert_data('blogs',$arr_data);

  if ($id=="") {
    $json = ['result'=>'unsuccessfull','status'=>0,'message'=>'data not found'];
  }else{


    $arr_whr_blog = ['id'=>$id];
    $get_blog = $this->webservice_model->get_where('blogs',$arr_whr_blog);  

    $get_blog[0]['blog_image']=SITE_URL.'uploads/images/'.$get_blog[0]['blog_image'];    
    $ressult['result']=$get_blog[0];
    $ressult['message']='successfull';
    $ressult['status']='1';
    $json = $ressult;
  }

  header('Content-type:application/json');
  echo json_encode($json);

}

/*************  get_cancellation_policy_list *************/
public function get_cancellation_policy_list()
{    


  $cancellation_policy_lists = $this->webservice_model->get_all('cancellation_policy'); 

  if (!empty($cancellation_policy_lists)) {

    foreach($cancellation_policy_lists as $cancellation_policy_list)
    {
      $data[] = $cancellation_policy_list;
    }

    $ressult['result']=$data;
    $ressult['message']='successful';
    $ressult['status']='1';
    $json = $ressult;                      


  }
  else {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);

}

/*************  get_cancellation_policy_list_details *************/
public function get_cancellation_policy_list_details()
{    

  $policy_id = $this->input->get_post('policy_id');

  $cancellation_policy_details = $this->webservice_model->get_where('cancellation_policy',['id'=>$policy_id]); 

  if (!empty($cancellation_policy_details)) {

    foreach($cancellation_policy_details as $cancellation_policy_detail)
    {
      $data = $cancellation_policy_detail;
    }

    $ressult['result']=$data;
    $ressult['message']='successful';
    $ressult['status']='1';
    $json = $ressult;                      


  }
  else {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);

}

/************* update_cancellation_policy function *************/
public function update_cancellation_policy(){

  $arr_whr = ['id'=>$this->input->get_post('policy_id')];

  $get_user = $this->webservice_model->get_where('cancellation_policy',$arr_whr);
  if ($get_user[0]['id'] == "")
  {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessfull';
    $ressult['status']='0';
    $json = $ressult;

    header('Content-type:application/json');
    echo json_encode($json);
    die;
  }

  $arr_data = [
    'description'=>$this->input->get_post('description')      
  ];


  $update = $this->webservice_model->update_data('cancellation_policy',$arr_data,$arr_whr);
  if ($update)
  {
    $data = $this->webservice_model->get_where('cancellation_policy',$arr_whr);

    $ressult['result']=$data[0];
    $ressult['message']='successfull';
    $ressult['status']='1';
    $json = $ressult;
  }
  else
  {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessfull';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);

}




/*************  get_all_blog_list *************/
public function get_all_blog_list()
{    


  $blog_lists = $this->webservice_model->get_all('blogs'); 

  if (!empty($blog_lists)) {

    foreach($blog_lists as $blog_list)
    {
      $blog_list['created_date'] = date("F,d Y", strtotime($blog_list['created_date']));
      if($blog_list['blog_image']!=''){

      $blog_list['blog_image'] = SITE_URL.'uploads/images/'.$blog_list['blog_image'];
      }
      else{
      $blog_list['blog_image'] = '';
      }
      $data[] = $blog_list;
    }

    $ressult['result']=$data;
    $ressult['message']='successful';
    $ressult['status']='1';
    $json = $ressult;                      


  }
  else {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);

}

/*************  get_blog_details *************/
public function get_blog_details()
{    

  $blog_id = $this->input->get_post('blog_id');

  $blog_details = $this->webservice_model->get_where('blogs',['id'=>$blog_id]); 

  if (!empty($blog_details)) {

    foreach($blog_details as $blog_detail)
    {

      $blog_descriptions = $this->webservice_model->get_where('blog_description',['blog_id'=>$blog_detail['id']]);
      $description = [];
      if(!empty($blog_descriptions)){
        foreach($blog_descriptions as $blog_description)
        {
          $blog_description['description'] = html_entity_decode($blog_description['description']);

          $description[] = $blog_description;
        }
      }

      $blog_detail['created_date'] = date("F,d Y", strtotime($blog_detail['created_date']));
      $blog_detail['blog_image'] = SITE_URL.'uploads/images/'.$blog_detail['blog_image'];
      $blog_detail['description'] = $description;
      $data = $blog_detail;
    }

    $ressult['result']=$data;
    $ressult['message']='successful';
    $ressult['status']='1';
    $json = $ressult;                      


  }
  else {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);

}



/************* forgot_password function *************/

public function forgot_password()
{
  $email = $this->input->get_post('email', TRUE);
  $arr_login = ['email' => $email];

  $login = $this->webservice_model->get_where('users', $arr_login);
  if ($login)
  {
    $pass = random_string('alnum', 6);

    $to = $email;
    $subject = "Forgot Password";
    $body = "<div style='max-width: 600px; width: 100%; margin-left: auto; margin-right: auto;'>
    <header style='color: #fff; width: 100%;'>
    <img alt='' src='".SITE_URL."uploads/images/logo.png' width ='120' height='120'/>
    </header>

    <div style='margin-top: 10px; padding-right: 10px; 
    padding-left: 125px;
    padding-bottom: 20px;'>
    <hr>
    <h3 style='color: #232F3F;'>Hello ".$login[0]['firstname'].",</h3>
    <p>You have requested a new password for your Nursing On Demand account.</p>
    <p>Your new password is <span style='background:#2196F3;color:white;padding:0px 5px'>".$pass."</span></p>
    <hr>

    <p>Warm Regards<br>Nursing On Demand<br>Support Team</p>

    </div>
    </div>

    </div>";

    $headers = "From: info@mobileappdevelop.co" . "\r\n";
    $headers.= "MIME-Version: 1.0" . "\r\n";
    $headers.= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // file_get_contents("http://technorizen.co.in/mail.php?to=".urlencode($to)."&subject=".urlencode($subject)."&body=".urlencode($body)."&headers=".urlencode($headers));

    mail($to, $subject, $body, $headers);

    $ressult['result']="Forgot password successfuly";
    $ressult['message']='successfull';
    $ressult['status']='1';
    $json = $ressult;

    $this->webservice_model->update_data('users',['password'=>base64_encode($pass)],$arr_login);

  }
  else
  {
    $ressult['result']='Email not exist';
    $ressult['message']='unsuccessfull';
    $ressult['status']='0';
    $json = $ressult;

  }

  header('Content-type: application/json');
  echo json_encode($json);
}


/************* change_password function *************/
public function change_password()
{
  $password = $this->input->get_post('old_password', TRUE);
  $id = $this->input->get_post('admin_id', TRUE);

  $arr_whr = ['id' => $id ,'password' => base64_encode($password)];
  $login = $this->webservice_model->get_where('users', $arr_whr);

  $arr_data = ['password'=>base64_encode($this->input->get_post('password'))];


  if ($login)
  {     
    $this->webservice_model->update_data('users',$arr_data,$arr_whr);

    $ressult['result']="successfull";
    $ressult['message']='Change password successfuly';
    $ressult['status']='1';
    $json = $ressult;


  }
  else
  {
    $ressult['result']="Data not found";
    $ressult['message']='unsuccessfull';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);
}


/************* app_rating function *************/
public function app_rating(){

  $arr_whr = ['user_id'=>$this->input->get_post('user_id')];

  $rate = $this->input->get_post('rate');
  $comment = $this->input->get_post('comment');

  $arr_data = [
    'user_id'=>$arr_whr['user_id'],
    'rate'=>$rate,
    'comment'=>$comment    
  ];

  $get_rating = $this->webservice_model->get_where('rating',$arr_whr);
  if ($get_rating[0]['id'] == "")
  {
    $rating_id = $this->webservice_model->insert_data('rating',$arr_data);

    if($rating_id!=''){
      $rating = $this->webservice_model->get_where('rating',['id'=>$rating_id]);
      $ressult['result']=$rating[0];
      $ressult['message']='successfull';
      $ressult['status']='1';
      $json = $ressult;
    }
  }


  $update = $this->webservice_model->update_data('rating',$arr_data,$arr_whr);
  if ($update)
  {
    $data = $this->webservice_model->get_where('rating',$arr_whr);

    $ressult['result']=$data[0];
    $ressult['message']='successfull';
    $ressult['status']='1';
    $json = $ressult;
  }
  else
  {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessfull';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);

}


/************* contact_us function *************/
public function contact_us(){

  $arr_whr = ['email'=>$this->input->get_post('email')];

  $name = $this->input->get_post('name');
  $message = $this->input->get_post('message');

  $arr_data = [
    'name'=>$name,
    'email'=>$arr_whr['email'],
    'message'=>$message    
  ];

  $get_contact_us = $this->webservice_model->get_where('contact_us',$arr_whr);
  if ($get_contact_us[0]['id'] == "")
  {
    $contact_id = $this->webservice_model->insert_data('contact_us',$arr_data);

    if($contact_id!=''){
      $contact = $this->webservice_model->get_where('contact_us',['id'=>$contact_id]);

      $to = $arr_whr['email'];
      $subject = "Contact Us";
      $body = "<div style='max-width: 600px; width: 100%; margin-left: auto; margin-right: auto;'>
      <header style='color: #fff; width: 100%;'>
      <img alt='' src='".SITE_URL."uploads/images/logo.png' width ='120' height='120'/>
      </header>

      <div style='margin-top: 10px; padding-right: 10px; 
      padding-left: 125px;
      padding-bottom: 20px;'>
      <hr>
      <h3 style='color: #232F3F;'>Hello ".$contact[0]['name'].",</h3>
      <p>You have contacted successfully Nursing On Demand App.</p>
      <hr>

      <p>Warm Regards<br>Nursing On Demand<br>Support Team</p>

      </div>
      </div>

      </div>";

      $headers = "From: info@mobileappdevelop.co" . "\r\n";
      $headers.= "MIME-Version: 1.0" . "\r\n";
      $headers.= "Content-type:text/html;charset=UTF-8" . "\r\n";

      file_get_contents("http://technorizen.co.in/mail.php?to=".urlencode($to)."&subject=".urlencode($subject)."&body=".urlencode($body)."&headers=".urlencode($headers));

      mail($to, $subject, $body, $headers);



      $ressult['result']=$contact[0];
      $ressult['message']='successfull';
      $ressult['status']='1';
      $json = $ressult;
    }
  }

  else
  {
   $ressult['result']='Email Already Exist';
   $ressult['message']='unsuccessfull';
   $ressult['status']='0';
   $json = $ressult;
 }

 header('Content-type: application/json');
 echo json_encode($json);

}





/************* social_login function *************/
public function social_login(){

  $arr_data = [
   'firstname'=>$this->input->get_post('firstname'),
   'email'=>$this->input->get_post('email'),
            //'password'=>$this->input->get_post('password'),
   'lat'=>$this->input->get_post('lat'),
   'lon'=>$this->input->get_post('lon'),
   'social_id'=>$this->input->get_post('social_id')
           // 'register_id'=>$this->input->get_post('register_id'),
            //'ios_register_id'=>$this->input->get_post('ios_register_id')          
 ];

 $image = $this->input->get_post('image');

 if($image!=""){
   $img = "USER_IMG_" . rand(1, 10000) . ".png";
   @file_put_contents('uploads/images/'.$img, file_get_contents($image));
   $arr_data['image'] = $img;

 }

 $arr_get = ['social_id'=>$arr_data['social_id']];

 $login = $this->webservice_model->get_where('users',$arr_get);

 if (!empty($login)) {  

  $this->webservice_model->update_data('users',$arr_data,$arr_get);
  $data = $this->webservice_model->get_where('users',$arr_get);
  $data[0]['image']=SITE_URL.'uploads/images/'.$data[0]['image'];

  $ressult['result']=$data[0];
  $ressult['message']='successfull';
  $ressult['status']='1';
  $json = $ressult;

}else{

        //$arr_data['user_code'] = $this->webservice_model->generateRandomString(4);

  $id = $this->webservice_model->insert_data('users',$arr_data);
  $data = $this->webservice_model->get_where('users',['id'=>$id]);        
  $data[0]['image']=SITE_URL.'uploads/images/'.$data[0]['image'];

  $ressult['result']=$data[0];
  $ressult['message']='successfull';
  $ressult['status']='1';
  $json = $ressult;

}

header('Content-type: application/json');
echo json_encode($json);
}


/************* add_event function *************/



public function add_event(){

  $user_id = $this->input->get_post('user_id');
  $event_name = $this->input->get_post('event_name');
  $description = $this->input->get_post('description');
  $event_date = $this->input->get_post('event_date');  
  $event_time = $this->input->get_post('event_time'); 
  $event_time_arrival = $this->input->get_post('event_time_arrival');  
  $lat = $this->input->get_post('lat'); 
  $lon = $this->input->get_post('lon');  
  $location = $this->input->get_post('location');   




  $arr_data = ['user_id' => $user_id,'event_name' => $event_name,'description' => $description,'event_time' => $event_time,'event_time_arrival' => $event_time_arrival,'lat' => $lat,'lon' => $lon,'location' => $location];



  if (isset($_FILES['image']))
  {
                         //  unlink('uploads/images/'.$login[0]['image']);
   $n = rand(0, 100000);
   $img = "USER_IMG_" . $n . '.png';
   move_uploaded_file($_FILES['image']['tmp_name'], "uploads/images/" . $img);
   $arr_data['image'] = $img;        
 }


 $inst = $this->webservice_model->insert_data('events',$arr_data);


 $add_date = explode(",",$event_date);
 $time = explode(",",$event_time);

 $i=0;
 foreach($add_date as $val)
 {
   $arr_da = ['event_id' => $inst,'event_date' => $val,'event_time' => $time[$i]];
   $this->webservice_model->insert_data('event_date',$arr_da);
   $i++;
 }

 $ressult['result']='add Event successfull';
 $ressult['message']='successfull';
 $ressult['status']='1';
 $json = $ressult; 

 header('Content-type:application/json');
 echo json_encode($json);

}



/*************** get_event *****************/


public function get_event()
{  

  $arr_login = array(
    'user_id' => $this->input->get_post('user_id', TRUE)      
  );

  /*Check Login*/
  $login = $this->webservice_model->get_where('events', $arr_login);

              // print_r($login);
  if ($login)      
  {
    foreach($login as  $val)
    {
      $where = ['event_id'=>$val['id']];
      $fetch = $this->webservice_model->get_where('event_date',$where);
      if($fetch){
        $arr = [];
        foreach($fetch as $val1)
        {

          $where1 = ['event_date_id'=>$val1['id']];
          $fetch1 = $this->webservice_model->get_where('event_participat',$where1);
          if($fetch1){
            $val1['guest_list'] = $fetch1;  
          }else{
            $val1['guest_list'] = array();  
          }
          $arr[] = $val1;
        }

        $val['event_date'] = $arr;  
      }else{
        $val['event_date'] =array();  
      }
      $ressult[]=$val;

    }
    $data['result']= $ressult;
    $data['message']='successfull';
    $data['status']='1';
    $json = $data;


  }                                
  else
  {
    $data['result']['errorMsg']='No Event Found';
    $data['message']='unsuccessfull';
    $data['status']='0';
    $json = $data; 
  }

  header('Content-type: application/json');
  echo json_encode($json);
}


/*************** get_new_event *****************/


public function get_new_event()
{  

  $user_id = $this->input->get_post('user_id', TRUE);

  $arr_login = array(
    'user_id' => $this->input->get_post('user_id', TRUE)      
  );

  $login = $this->webservice_model->get_where('events', $arr_login);

  if ($login)      
  {
    foreach($login as  $val)
    {
      $where = ['event_id'=>$val['id']];
      $fetch = $this->webservice_model->get_where('event_date',$where);
      if($fetch){
        $arr = [];
        foreach($fetch as $val1)
        {

          $where1 = ['event_date_id'=>$val1['id']];
          $fetch1 = $this->webservice_model->get_where('event_participat',$where1);
          if($fetch1){
            $val1['guest_list'] = $fetch1;  
          }else{
            $val1['guest_list'] = array();  
          }
          $arr[] = $val1;
        }

        $val['event_date'] = $arr;  
      }else{
        $val['event_date'] =array();  
      }
      $ressult[]=$val;

    }
    $data['result']= $ressult;
    $data['message']='successfull';
    $data['status']='1';



  }                                
  else
  {
    $data['result']['errorMsg']='No Event Found';
    $data['message']='unsuccessfull';
    $data['status']='0';

  }

  $login = $this->db->query("SELECT c.username,b.* FROM invite_to_event as a inner join events as b inner join users as c where a.user_id='$user_id' and b.id = a.event_id and b.user_id = c.id")->result_array();
  $ressult = [];
  if ($login)      
  {
    foreach($login as  $val)
    {


      $where = ['event_id'=>$val['id']];
      $fetch = $this->webservice_model->get_where('event_date',$where);
      if($fetch){
        $arr = [];
        foreach($fetch as $val1)
        {

          $where1 = ['event_date_id'=>$val1['id']];
          $fetch1 = $this->webservice_model->get_where('event_participat',$where1);
          if($fetch1){
            $val1['guest_list'] = $fetch1;  
          }else{
            $val1['guest_list'] = array();  
          }
          $arr[] = $val1;
        }

        $val['event_date'] = $arr;  
      }else{
        $val['event_date'] =array();  
      }
      $ressult[]=$val;

    }
    $data1['result']= $ressult;
    $data1['message']='successfull';
    $data1['status']='1';



  }                                
  else
  {
    $data1['result']['errorMsg']='No Event Found';
    $data1['message']='unsuccessfull';
    $data1['status']='0';

  }

  $json['created_events'] = $data;
  $json['invited_events'] = $data1;


  header('Content-type: application/json');
  echo json_encode($json);
}




/***************get_event_detail *****************/


public


function get_event_detail()
{  

  $arr_login = array(
    'id' => $this->input->get_post('event_id', TRUE)       
  );
  $user_id = $this->input->get_post('user_id', TRUE);
  /*Check Login*/
  $login = $this->webservice_model->get_where('events', $arr_login);

              // print_r($login);
  if ($login)

  {
    foreach($login as  $val)
    {



      $where = ['event_id'=>$val['id']];
      $fetch = $this->webservice_model->get_where('event_date',$where);
      if($fetch){
        $arr = [];
        foreach($fetch as $val1)
        {

          $where1 = ['event_date_id'=>$val1['id']];
          $fetch1 = $this->webservice_model->get_where('event_participat',$where1);
          if($fetch1){
            $val1['guest_list'] = $fetch1;  
          }else{
            $val1['guest_list'] = array();  
          }

          $fetch2 = $this->db->query("SELECT b.* FROM event_vote as a inner join users as b where a.event_date_id = ".$val1['id']." and a.user_id = b.id ")->result_array();
          if($fetch2){
            $val1['who_vote'] = $fetch2;  
          }else{
            $val1['who_vote'] = array();  
          }

          $val1['my_vote'] = 'NO';  
          $where1['user_id'] = $user_id;
          $fetch3 = $this->webservice_model->get_where('event_vote',$where1);
          if($fetch3){
            $val1['my_vote'] = 'YES';  
          }

          $arr[] = $val1;
        }

        $val['event_date'] = $arr;  
      }else{
        $val['event_date'] =array();  
      }

      $val['my_status'] = 'no_invite';  
      $where2 = ['event_id'=>$val['id'],'user_id'=>$user_id];
      $fetch3 = $this->webservice_model->get_where('invite_to_event',$where2);
      if($fetch3){
        $val['my_status'] = $fetch3[0]['status'];  
      }

      $val['event_guest'] = 'no_invite';                                 
      $fetch4 = $this->db->query("SELECT a.status,b.* FROM invite_to_event as a inner join users as b where a.event_id = ".$val['id']." and a.user_id = b.id ")->result_array();
//echo $this->db->last_query(); die;
      if($fetch4){
        $val['event_guest'] = $fetch4;  
      }else{
        $val['event_guest'] = array();  
      }

      $ressult[]=$val;

    }
    $data['result']= $ressult;
    $data['message']='successfull';
    $data['status']='1';
    $json = $data;


  }


  else
  {
    $data['result']['errorMsg']='No Event Found';
    $data['message']='unsuccessfull';
    $data['status']='0';
    $json = $data; 
  }

  header('Content-type: application/json');
  echo json_encode($json);
}



/*************** who_vote *****************/


public function who_vote()
{  


 $event_date_id = $this->input->get_post('event_date_id', TRUE);    


 $fetch2 = $this->db->query("SELECT b.* FROM event_vote as a inner join users as b where a.event_date_id = ".$event_date_id." and a.user_id = b.id ")->result_array();

 if($fetch2){

  $data['result']= $fetch2;
  $data['message']='successfull';
  $data['status']='1';
  $json = $data;  

}else{

  $data['result']='No Event Found';
  $data['message']='unsuccessfull';
  $data['status']='0';
  $json = $data;

}


header('Content-type: application/json');
echo json_encode($json);

}

/*************** event_ans *****************/


public function event_ans()
{  


  $event_id = $this->input->get_post('event_id', TRUE);
  $user_id = $this->input->get_post('user_id', TRUE);

  $arr_login = ['event_id' => $event_id ,'user_id' => $user_id];
  $login = $this->webservice_model->get_where('invite_to_event', $arr_login);

  $arr_data = ['status'=>$this->input->get_post('status')];


  if ($login)
  {     
    $this->webservice_model->update_data('invite_to_event',$arr_data,$arr_login);

    $ressult['result']="Event answere successfuly";
    $ressult['message']='successfull';
    $ressult['status']='1';
    $json = $ressult;


  }
  else
  {
    $ressult['result']="Data not found";
    $ressult['message']='unsuccessfull';
    $ressult['status']='0';
    $json = $ressult;
  }


  header('Content-type: application/json');
  echo json_encode($json);

}


/************* get_event_acc_date function *************/


public function get_event_acc_date(){

  $user_id = $this->input->get_post('user_id');
  $event_date = $this->input->get_post('event_date');


  $fetch = $this->db->query("select * from `events` where user_id = '$user_id'")->result_array();
                        //echo $this->db->last_query();
  if ($fetch) {                                
               // print_r($fetch);die;            
   foreach($fetch as $val)
   {

     $where = ['event_id'=>$val['id']];       
     $fet = $this->webservice_model->get_where('event_date',$where);
     $i=0;
     foreach($fet as $value)
     {
      if($value['event_date'] == $event_date)
      {
        $i++;
        $val['event_date']= $value['event_date'];

      }

    }
    if($i > 0){

      $data[] = $val;
    }
  }


  $ressult['result']=$data;
  $ressult['message']='successfull';
  $ressult['status']='1';
  $json = $ressult;


}

else{

  $ressult['result']='no data found';
  $ressult['message']='unsuccessfull';
  $ressult['status']='0';
  $json = $ressult;       
}

header('Content-type:application/json');
echo json_encode($json);
}


/************* get_event_acc_week function *************/


public function get_event_acc_week(){

  $user_id = $this->input->get_post('user_id');
  $start_date = $this->input->get_post('start_date');
  $end_date = $this->input->get_post('end_date');

  $fetch = $this->db->query("select * from `events` where user_id = '$user_id'")->result_array();
                        //echo $this->db->last_query();
  if ($fetch) {                                
   $i=0;
   foreach($fetch as $val)
   {


     $where = "event_id = '".$val['id']."' and event_date between '".$start_date."' and '".$end_date."'";   
     $event_date = [];
     $fet = $this->webservice_model->get_where('event_date',$where);
//echo $this->db->last_query();

     if($fet){
       foreach($fet as $value)
       {
        $i++;
        $event_date[]= $value;

      }
      $val['event_date'] = $event_date;
      $data[] = $val;
    }


  }


  if($i > 0){

    $ressult['result']=$data;
    $ressult['message']='successfull';
    $ressult['status']='1';
    $json = $ressult;

  }else{

   $ressult['result']='no data found';
   $ressult['message']='unsuccessfull';
   $ressult['status']='0';
   $json = $ressult;   
   header('Content-type:application/json');
   echo json_encode($json);  die; 

 }                              


}else{

  $ressult['result']='no data found';
  $ressult['message']='unsuccessfull';
  $ressult['status']='0';
  $json = $ressult;       
}

header('Content-type:application/json');
echo json_encode($json);
}


/*************  event_vote *************/
public

function event_vote()
{                     

  $arr_data = array(
    'event_id' => $this->input->get_post('event_id'),
    'event_date_id' => $this->input->get_post('event_date_id'), 
    'user_id' => $this->input->get_post('user_id')                          
  );

  $arr_where = array(
    'event_id' => $this->input->get_post('event_id'),
    'user_id' => $this->input->get_post('user_id')                          
  );

  $this->webservice_model->delete_data('event_vote',$arr_where); 

  $event_date_id = explode(",",$this->input->get_post('event_date_id'));


  foreach($event_date_id as $val)
  {
    $arr_data['event_date_id'] = $val;
    $vote = $this->webservice_model->insert_data('event_vote', $arr_data);

  }



  if ($vote != "") {

    $single_data = ['id' => $vote];

    $fetch_order = $this->webservice_model->get_where('event_vote',$single_data); 

    $ressult['result']=$fetch_order[0];
    $ressult['message']='successful';
    $ressult['status']='1';
    $json = $ressult;
  }
  else {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);
}


/************* update_event function *************/
public function update_event(){

  $arr_get = ['id'=>$this->input->get_post('event_id')];

  $login = $this->webservice_model->get_where('events',$arr_get);
  if ($login[0]['id'] == "")
  {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessfull';
    $ressult['status']='0';
    $json = $ressult;

    header('Content-type:application/json');
    echo json_encode($json);
    die;
  }



  $arr_data = [
    'event_name'=>$this->input->get_post('event_name'),
    'description'=>$this->input->get_post('description'),
            //'event_date'=>$this->input->get_post('event_date'),
    'event_time_arrival'=>$this->input->get_post('event_time_arrival') ,
    'lat'=>$this->input->get_post('lat'),
    'lon'=>$this->input->get_post('lon'),
    'location'=>$this->input->get_post('location')         
  ];

  if (isset($_FILES['image']))
  {
                         //  unlink('uploads/images/'.$login[0]['image']);
   $n = rand(0, 100000);
   $img = "USER_IMG_" . $n . '.png';
   move_uploaded_file($_FILES['image']['tmp_name'], "uploads/images/" . $img);
   $arr_data['image'] = $img;        
 }


 $res = $this->webservice_model->update_data('events',$arr_data,$arr_get);
 if ($res)
 {
  $data = $this->webservice_model->get_where('events',$arr_get);
  $data[0]['image']=SITE_URL.'uploads/images/'.$data[0]['image'];

  $ressult['result']=$data[0];
  $ressult['message']='successfull';
  $ressult['status']='1';
  $json = $ressult;
}
else
{
  $ressult['result']='Data Not Found';
  $ressult['message']='unsuccessfull';
  $ressult['status']='0';
  $json = $ressult;
}

header('Content-type: application/json');
echo json_encode($json);                          

}

/************** delete_event product ****************/
public function delete_event(){

  $arr_id = ['id'=>$this->input->get_post('event_id', TRUE)];
  $arr_get = ['event_id'=>$this->input->get_post('event_id', TRUE)];

  $res = $this->webservice_model->delete_data('events', $arr_id);
  

  if($res){

    $res = $this->webservice_model->delete_data('event_date', $arr_get);

    $ressult['result']='event delete successfully';
    $ressult['message']='successfull';
    $ressult['status']='1';
    $json = $ressult;
  }else{

    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessfull';
    $ressult['status']='0';
    $json = $ressult;
  }


  header('Content-type: application/json');
  echo json_encode($json);

}

/***************get_event *****************/


public function get_event_poll()
{  

  $arr_login = array(
    'id' => $this->input->get_post('event_id', TRUE) 
  );

  /*Check Login*/
  $fetch = $this->webservice_model->get_where('events', $arr_login);


  if ($fetch)
  {

    $where = ['event_id'=>$this->input->get_post('event_id', TRUE)];
    $get = $this->webservice_model->get_where('event_date',$where);
    if($get){
      $fetch[0]['event_dates'] = $get;  
    }else{
      $fetch[0]['event_dates'] =array();  
    }

    $fetch[0]['image']=SITE_URL.'uploads/images/'.$fetch[0]['image'];

    $data['result']= $fetch[0];
    $data['message']='successfull';
    $data['status']='1';
    $json = $data;


  }else{
    $data['result']='No Event Found';
    $data['message']='unsuccessfull';
    $data['status']='0';
    $json = $data; 
  }

  header('Content-type: application/json');
  echo json_encode($json);
}

/*************  invite_to_event *************/
public

function invite_to_event()
{                     

  $arr_data = array(
    'event_id' => $this->input->get_post('event_id'),
    'user_id' => $this->input->get_post('user_id')                          
  );

  $get = $this->webservice_model->get_where('users',['id'=>$arr_data['user_id']]);
  $arr_data['name'] = $get[0]['username']; 

  $get1 = $this->webservice_model->get_where('events',['id'=>$arr_data['event_id']]);
  $arr_data1['user_id'] = $get1[0]['user_id'];       

  $get2 = $this->webservice_model->get_where('users',['id'=>$arr_data1['user_id']]);
      //$arr_data2['username'] = $get2[0]['username'];       


  $invite = $this->webservice_model->insert_data('invite_to_event', $arr_data);


  if ($invite != "") {


   $user_message_apk = array(
     "message" => array(
       "result" => "successful",
       "key" => "new invitation message",
       "username" => $get2[0]['username'],
       "register_id" => $get[0]['register_id'],
       "userid" => $arr_data1['user_id'],
       "event_id" => $arr_data['event_id'],
       "event_name" => $get1[0]['event_name'],
       "event_image" => SITE_URL.'uploads/images/'.$get1[0]['image'],
       "user_image" => SITE_URL.'uploads/images/'.$get2[0]['image'],
       "date"=> date('Y-m-d h:i:s')
     )
   );


   $register_userid = array($get[0]['register_id']);


   $this->webservice_model->user_apk_notification($register_userid, $user_message_apk);


   $single_data = ['id' => $invite];

   $fetch = $this->webservice_model->get_where('invite_to_event',$single_data); 

   $ressult['result']=$fetch[0];
   $ressult['message']='successful';
   $ressult['status']='1';
   $json = $ressult;
 }
 else {
  $ressult['result']='Data Not Found';
  $ressult['message']='unsuccessful';
  $ressult['status']='0';
  $json = $ressult;
}

header('Content-type: application/json');
echo json_encode($json);
}




public function check_contact()
{

  $event_id = $this->input->get_post('event_id', TRUE);  

  $cont = $this->input->get_post('phone', TRUE);   

  $contacts = explode(",",$cont);




  $i = 0;
  foreach($contacts as $val){

   $where = "phone = '$val' ";
   $data = $this->webservice_model->get_where('users',$where);
   if($data){            

     $res['check_status'] = "no_invited";
     $res['id'] = $data[0]['id'];
     $res['username'] = $data[0]['username'];
     $res['user_image'] = SITE_URL.'uploads/images/'.$data[0]['image'];

     $fetch = $this->webservice_model->get_where('invite_to_event',['event_id'=>$event_id,'user_id'=>$res['id']]);

     if($fetch){
       $res['check_status'] = "invited";
     }


     $res['phone'] = $val;
     $res['status'] = "1";
     $json[] = $res;


   }
   else
   {
    $res['id'] = "0";
    $res['phone'] = $val;
    $res['username'] = "";
    $res['check_status'] = "no_invited";
    $res['user_image'] = "";
    $res['status'] = "0";
    $json[] = $res;
  }

  $i++;

}

header('Content-type: application/json');
echo json_encode($json);

}

/************* event_review function *************/
public function event_review(){


  $arr_data = [
    'event_id'=>$this->input->get_post('event_id'),
    'review'=>$this->input->get_post('review'),
    'user_id'=>$this->input->get_post('user_id')         
  ];

  if (isset($_FILES['image']))
  {
   $n = rand(0, 100000);
   $img = "EVENT_IMG_" . $n . '.png';
   move_uploaded_file($_FILES['image']['tmp_name'], "uploads/images/" . $img);
   $arr_data['image'] = $img;        
 }


 $id = $this->webservice_model->insert_data('event_review',$arr_data);

 if ($id=="") {
  $json = ['result'=>'unsuccessfull','status'=>0,'message'=>'data not found'];
}else{


 $event_id = $this->input->get_post('event_id');

 $get_users = $this->db->query("SELECT b.register_id FROM invite_to_event as a inner join users as b where a.event_id = ".$event_id." and a.user_id = b.id ")->result_array();


 $evt = $this->webservice_model->get_where('events',['id'=>$arr_data['event_id']]);
 $get2 = $this->webservice_model->get_where('users',['id'=>$arr_data['user_id']]);

 $user_message_apk = array(
   "message" => array(
     "result" => "successful",
     "key" => "new review message",
     "username" => $get2[0]['username'],
     "register_id" => $get2[0]['register_id'],
     "userid" => $arr_data['user_id'],
     "event_id" => $arr_data['event_id'],
     "event_name" => $evt[0]['event_name'],
     "event_image" => SITE_URL.'uploads/images/'.$evt[0]['image'],
     "user_image" => SITE_URL.'uploads/images/'.$get2[0]['image'],
     "date"=> date('Y-m-d h:i:s')
   )
 );

 foreach($get_users as $val)
 {

  $register_userid = array($val['register_id']);
  $this->webservice_model->user_apk_notification($register_userid, $user_message_apk);

}


$arr_gets = ['id'=>$id];
$login = $this->webservice_model->get_where('event_review',$arr_gets);       
$login[0]['image']=SITE_URL.'uploads/images/'.$login[0]['image'];
$ressult['result']=$login[0];
$ressult['message']='successfull';
$ressult['status']='1';
$json = $ressult;
}

header('Content-type:application/json');
echo json_encode($json);


}


/*************** get_event_review *****************/

public function get_event_review()
{  


 $event_id = $this->input->get_post('event_id', TRUE);    


 $fetch = $this->db->query("SELECT a.date_time as evt_time, a.review,a.image as event_img, b.image as user_img, b.* FROM event_review as a inner join users as b where a.event_id = ".$event_id." and a.user_id = b.id order by a.id DESC ")->result_array();

 if($fetch){


  foreach($fetch as $val)
  {
    $val['event_img']=SITE_URL.'uploads/images/'.$val['event_img'];  
    $val['user_img']=SITE_URL.'uploads/images/'.$val['user_img'];  
    $ptime = @strtotime($val['evt_time']);
    $val['ago_time']=$this->humanTiming($ptime );          
    $result[] = $val;        
  }


  $data['result'] = $result;
  $data['message']='successfull';
  $data['status']='1';
  $json = $data;  

}else{

  $data['result']='No Event Found';
  $data['message']='unsuccessfull';
  $data['status']='0';
  $json = $data;

}


header('Content-type: application/json');
echo json_encode($json);

}


public

function add_expense()
{                     

  $arr_data = array(
    'who_paid' => $this->input->get_post('who_paid'),
    'user_id' => $this->input->get_post('user_id'), 
    'expense' => $this->input->get_post('expense'), 
    'expense_ids' => $this->input->get_post('expense_ids'), 
    'expense_cnt' => $this->input->get_post('expense_cnt'),
    'amount' => $this->input->get_post('amount'), 
    'currency' => $this->input->get_post('currency'), 
    'event_id' => $this->input->get_post('event_id')                           
  );



  $fetch = $this->webservice_model->insert_data('expense', $arr_data);




  if ($fetch != "") {

    $single_data = ['id' => $fetch];

    $fetch = $this->webservice_model->get_where('expense',$single_data); 

    $ressult['result']=$fetch[0];
    $ressult['message']='successful';
    $ressult['status']='1';
    $json = $ressult;
  }
  else {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);
}

public

function get_expense()
{                     

  $where = array(
    'event_id' => $this->input->get_post('event_id')                           
  );

  $ev_id = $this->input->get_post('event_id');
  $fetch = $this->webservice_model->get_where('expense', $where);                        


  if ($fetch) {

    foreach($fetch as $val)
    {
      $ids = explode(",",$val['expense_ids']);
      $cnt = explode(",",$val['expense_cnt']);
      $i=0; $arr = [];
      foreach($ids as $exp)
      {
       $usr = $this->webservice_model->get_where('users', ['id'=>$exp]);
       $usr[0]['image'] = SITE_URL.'uploads/images/'.$usr[0]['image'];
       $usr[0]['expense_cnt'] = $cnt[$i];
       $arr[] = $usr[0];
       $i++;
     }

     $invite_to_event_usr = $this->webservice_model->get_where('invite_to_event', ['event_id'=>$this->input->get_post('event_id')]);

     $sum = $this->db->query("SELECT sum(amount) as amount FROM `expense` where event_id = '.$ev_id.'")->result_array();



     $due_users = array();
     foreach($invite_to_event_usr as $evt_user)
     {
      $total_amount = number_format($sum[0]['amount']/count($invite_to_event_usr),2);

      $user_list = $this->webservice_model->get_where('users', ['id'=>$evt_user['user_id']]);


      $expense_list = $this->webservice_model->get_where('expense', ['event_id'=>$evt_user['event_id'],'user_id'=>$evt_user['user_id']]);


      if($expense_list)
      {

        $user_list[0]['remaining_amount'] = $total_amount - $expense_list[0]['amount'];
      }
      else
      {
        $user_list[0]['remaining_amount'] = $total_amount;
      }




                            /*if($exp_user['currency']=='pound')
                              {
                              
                              $exp_user['amount'] = 1.35 * $exp_user['amount'];
                            }*/

                            $due_users[] = array_unique($user_list[0]);
                               //print_r($evt_user);
                               //die;

                          }

                          $val['split_user'] = $arr;
                          $val['due_users'] = $due_users;
                          $user = $this->webservice_model->get_where('users', ['id'=>$val['user_id']]);
                          $user[0]['image'] = SITE_URL.'uploads/images/'.$user[0]['image']; 
                          $val['user_detail'] = $user[0];
                          $ptime = @strtotime($val['date_time']);
                          $val['ago_time']=$this->humanTiming($ptime ); 
                          $date[] = $val;        
                        }


                        $ressult['result']=$date;
                        $ressult['message']='successful';
                        $ressult['status']='1';
                        $json = $ressult;
                      }
                      else {
                        $ressult['result']='Data Not Found';
                        $ressult['message']='unsuccessful';
                        $ressult['status']='0';
                        $json = $ressult;
                      }

                      header('Content-type: application/json');
                      echo json_encode($json);
                    }


                    public

                    function get_expense_o()
                    {                     

                      $where = array(
                        'event_id' => $this->input->get_post('event_id')                           
                      );


                      $fetch = $this->webservice_model->get_where('expense', $where);                        


                      if ($fetch) {

                        foreach($fetch as $val)
                        {
                          $ids = explode(",",$val['expense_ids']);
                          $cnt = explode(",",$val['expense_cnt']);
                          $i=0; $arr = [];
                          foreach($ids as $exp)
                          {
                           $usr = $this->webservice_model->get_where('users', ['id'=>$exp]);
                           $usr[0]['image'] = SITE_URL.'uploads/images/'.$usr[0]['image'];
                           $usr[0]['expense_cnt'] = $cnt[$i];
                           $arr[] = $usr[0];
                           $i++;
                         }

                         $invite_to_event_usr = $this->webservice_model->get_where('invite_to_event', ['event_id'=>$this->input->get_post('event_id')]);
                         $expense_usr = $this->webservice_model->get_where('expense', ['event_id'=>$this->input->get_post('event_id')]);
                         $sum = $this->db->query("SELECT sum(amount) as amount FROM `expense` where event_id = 18")->result_array();
                         $total_amount[] = number_format($sum[0]['amount']/count($invite_to_event_usr),2);

                         $exp_ids = '';
                         $exp_cnt = '';

                         foreach($expense_usr as $exp_user)
                         {


                          if($exp_ids==''){
                            $exp_ids = $exp_user['expense_ids'];
                            $exp_cnt = $exp_user['expense_cnt'];

                          }else{
                           $exp_ids = $exp_ids.','.$exp_user['expense_ids'];
                           $exp_cnt = $exp_cnt.','.$exp_user['expense_cnt'];
                         }
                       }

                       $total_amt = array_sum($total_amount)/count($invite_to_event_usr);
                       $arr_exp_ids = explode(',', $exp_ids);
                       $arr_exp_cnt = explode(',', $exp_cnt);

                       foreach($invite_to_event_usr as $evt_user)
                       {


                        $user_list = $this->webservice_model->get_where('users', ['id'=>$evt_user['user_id']]);





                              /*if($exp_user['currency']=='pound')
                              {
                              
                              $exp_user['amount'] = 1.35 * $exp_user['amount'];
                            }*/




                            $j=0;
                            foreach($arr_exp_ids as $arr_exp_id)
                            {
                                //echo $arr_exp_id;
                              if($arr_exp_id==$evt_user['user_id']){
                                $user_list[0]['remaining_amount'] = $total_amt-$arr_exp_cnt[$j]; 
                              }
                              else
                              {
                                $user_list[0]['remaining_amount'] = $total_amt;
                              }

                              $j++; 
                            }

                            $due_users[] = $user_list[0];
                               //print_r($evt_user);
                               //die;

                          }
                          $val['split_user'] = $arr;
                          $val['due_amount'] = $due_users;
                          $user = $this->webservice_model->get_where('users', ['id'=>$val['user_id']]);
                          $user[0]['image'] = SITE_URL.'uploads/images/'.$user[0]['image']; 
                          $val['user_detail'] = $user[0];
                          $ptime = @strtotime($val['date_time']);
                          $val['ago_time']=$this->humanTiming($ptime ); 
                          $date[] = $val;        
                        }


                        $ressult['result']=$date;
                        $ressult['message']='successful';
                        $ressult['status']='1';
                        $json = $ressult;
                      }
                      else {
                        $ressult['result']='Data Not Found';
                        $ressult['message']='unsuccessful';
                        $ressult['status']='0';
                        $json = $ressult;
                      }

                      header('Content-type: application/json');
                      echo json_encode($json);
                    }

                    public

                    function get_expense_detail()
                    {                     

                      $where = array(
                        'id' => $this->input->get_post('id')                           
                      );


                      $fetch = $this->webservice_model->get_where('expense', $where);                        


                      if ($fetch) {

                        foreach($fetch as $val)
                        {
                          $ids = explode(",",$val['expense_ids']);
                          $cnt = explode(",",$val['expense_cnt']);
                          $i=0; $arr = [];
                          foreach($ids as $exp)
                          {
                           $usr = $this->webservice_model->get_where('users', ['id'=>$exp]);
                           $usr[0]['image'] = SITE_URL.'uploads/images/'.$usr[0]['image'];
                           $usr[0]['expense_cnt'] = $cnt[$i];
                           $arr[] = $usr[0];
                           $i++;
                         }
                         $val['split_user'] = $arr;
                         $user = $this->webservice_model->get_where('users', ['id'=>$val['user_id']]);
                         $user[0]['image'] = SITE_URL.'uploads/images/'.$user[0]['image']; 
                         $val['user_detail'] = $user[0];
                         $date[] = $val;        
                       }


                       $ressult['result']=$date;
                       $ressult['message']='successful';
                       $ressult['status']='1';
                       $json = $ressult;
                     }
                     else {
                      $ressult['result']='Data Not Found';
                      $ressult['message']='unsuccessful';
                      $ressult['status']='0';
                      $json = $ressult;
                    }

                    header('Content-type: application/json');
                    echo json_encode($json);
                  }


                  /**************************************** old function here ***********************************************/

                  /************* get_nearest_list *************/
                  public

                  function get_nearest_list()
                  {
                    $type = $this->input->get_post('type');
                    $lat = $this->input->get_post('lat');
                    $lon = $this->input->get_post('lon');

                    if($type=='restaurant'){
                     $list = $this->webservice_model->get_all('restaurant');
                   }else{
                     $list = $this->webservice_model->get_all('shop');
                   }

                   if ($list)
                   {
                    foreach($list as $val)
                    {

                      $videos = $reviews = array();

                      $distance = $this->webservice_model->distance($lat, $lon, $val['lat'], $val['lon'], $miles = false);
                      if($type=='restaurant'){
                        $get = $this->db->select_avg("rating", "rating")->where(['restaurant_id'=>$val['id']])->get('restaurant_review')->result_array();
                        $get_review = $this->db->where(['restaurant_id'=>$val['id']])->get('restaurant_review')->result_array();
                      }else{
                       $get = $this->db->select_avg("rating", "rating")->where(['shop_id'=>$val['id']])->get('shop_review')->result_array();
                       $get_review = $this->db->where(['shop_id'=>$val['id']])->get('shop_review')->result_array();
                       $get_video = $this->db->where(['item_id'=>$val['id']])->get('shop_video')->result_array();                                
                                   //echo $this->db->last_query(); die;
                       foreach($get_video as $vid)
                       {
                         $vid['video']=SITE_URL.'uploads/images/'.$vid['video'];
                         $videos[] = $vid;                     
                       }

                       $val['image1']=SITE_URL.'uploads/images/'.$val['image1'];
                       $val['image2']=SITE_URL.'uploads/images/'.$val['image2'];
                       $val['image3']=SITE_URL.'uploads/images/'.$val['image3'];
                       $val['image4']=SITE_URL.'uploads/images/'.$val['image4'];



                     }

                     $val['discount_img']=SITE_URL.'uploads/images/'.$val['discount_img'];



                     foreach($get_review as $rev)
                     {

                      if($rev['review']!=''){
                        $user_id = $rev['user_id'];
                        $users = $this->webservice_model->get_where('users',['id'=>$user_id]);
                        $reviews[] = ['username'=>$users[0]['username'],'review'=>$rev['review']];
                      }
                    }

                    $rating = ($get[0]['rating']=='') ?  0 : $get[0]['rating'];   

                    $val['videos'] = $videos;
                    $val['rating'] = $rating;
                    $val['review'] = $reviews;
                    $val['distance'] = number_format($distance,2);
                    $val['image']=SITE_URL.'uploads/images/'.$val['image'];            
                    $data[] = $val;

                  }

                  $dis = array();
                  foreach ($data as $key => $row)
                  {
                   $dis[$key] = $row['distance'];
                 }
                 array_multisort($dis, SORT_ASC, $data);

                 $ressult['result']=$data;
                 $ressult['message']='successful';
                 $ressult['status']='1';
                 $json = $ressult;
               }
               else
               {
                $ressult['result']='Data Not Found';
                $ressult['message']='unsuccessful';
                $ressult['status']='0';
                $json = $ressult;                              

              }



              header('Content-type: application/json');
              echo json_encode($json);
            }

            /************* restaurant_category *************/
            public

            function restaurant_category()
            {
              $res_id = $this->input->get_post('res_id');
              $list = $this->webservice_model->get_where('restaurant_cat',['restaurant_id'=>$res_id]);

      //print_r($lis);

              if ($list)
              {
                foreach($list as $val)
                {


                  $val['image']=SITE_URL.'uploads/images/'.$val['image'];            
                  $data[] = $val;

                }

                $ressult['result']=$data;
                $ressult['message']='successful';
                $ressult['status']='1';
                $json = $ressult;
              }
              else
              {
                $ressult['result']='Data Not Found';
                $ressult['message']='unsuccessful';
                $ressult['status']='0';
                $json = $ressult;                              

              }



              header('Content-type: application/json');
              echo json_encode($json);
            }

            /************* restaurant_sub_category *************/
            public

            function restaurant_sub_category()
            {
              $res_id = $this->input->get_post('res_id');
              $res_cat_id = $this->input->get_post('res_cat_id');
              $list = $this->webservice_model->get_where('restaurant_sub_cat',['restaurant_id'=>$res_id,'restaurant_cat_id'=>$res_cat_id]);

      //print_r($lis);

              if ($list)
              {
                foreach($list as $val)
                {


                  $val['image']=SITE_URL.'uploads/images/'.$val['image'];            
                  $data[] = $val;

                }

                $ressult['result']=$data;
                $ressult['message']='successful';
                $ressult['status']='1';
                $json = $ressult;
              }
              else
              {
                $ressult['result']='Data Not Found';
                $ressult['message']='unsuccessful';
                $ressult['status']='0';
                $json = $ressult;                              

              }



              header('Content-type: application/json');
              echo json_encode($json);
            }


            /************* shop_category *************/
            public

            function shop_category()
            {
              $shop_id = $this->input->get_post('shop_id');
              $list = $this->webservice_model->get_where('shop_cat',['shop_id'=>$shop_id]);

      //print_r($lis);

              if ($list)
              {
                foreach($list as $val)
                {


                  $val['image']=SITE_URL.'uploads/images/'.$val['image'];            
                  $data[] = $val;

                }

                $ressult['result']=$data;
                $ressult['message']='successful';
                $ressult['status']='1';
                $json = $ressult;
              }
              else
              {
                $ressult['result']='Data Not Found';
                $ressult['message']='unsuccessful';
                $ressult['status']='0';
                $json = $ressult;                              

              }



              header('Content-type: application/json');
              echo json_encode($json);
            }


            /************* shop_sub_category *************/
            public

            function shop_sub_category()
            {
              $shop_id = $this->input->get_post('shop_id');
              $shop_cat_id = $this->input->get_post('shop_cat_id');
              $list = $this->webservice_model->get_where('shop_sub_cat',['shop_id'=>$shop_id,'shop_cat_id'=>$shop_cat_id]);

      //print_r($lis);

              if ($list)
              {
                foreach($list as $val)
                {

                  $get_data = $this->db->where('item_id',$val['id'])->get('shop_color')->result_array();
                  $val['cours'] = $get_data;
                  $get_data = $this->db->where('item_id',$val['id'])->get('shop_size')->result_array();
                  $val['size'] = $get_data;
                  $val['image']=SITE_URL.'uploads/images/'.$val['image'];
                  $val['image1']=SITE_URL.'uploads/images/'.$val['image1'];
                  $val['image2']=SITE_URL.'uploads/images/'.$val['image2'];
                  $val['image3']=SITE_URL.'uploads/images/'.$val['image3'];
                  $val['image4']=SITE_URL.'uploads/images/'.$val['image4'];

                  $data[] = $val;

                }

                $ressult['result']=$data;
                $ressult['message']='successful';
                $ressult['status']='1';
                $json = $ressult;
              }
              else
              {
                $ressult['result']='Data Not Found';
                $ressult['message']='unsuccessful';
                $ressult['status']='0';
                $json = $ressult;                              

              }



              header('Content-type: application/json');
              echo json_encode($json);
            }


            /************** delete_image ****************/
            public function delete_image(){


              $table = $this->input->get_post('table', TRUE);

              $position = $this->input->get_post('position', TRUE);

              $arr_id = ['id'=>$this->input->get_post('id', TRUE)];

              $list = $this->webservice_model->get_where($table,$arr_id);

              $arr_data = [$position=>''];

              unlink("uploads/images/" . $list[0][$position]);

              $res = $this->webservice_model->update_data($table, $arr_data, $arr_id);

                  //echo $this->db->last_query();
              if($res){

                $message = array(
                  "result" => "successful"
                );
              }else{

                $message = array(
                  "result" => "unsuccessful"
                );
              }


              echo json_encode($message);

            }

            /************* add_to_cart function *************/

            public function add_to_cart(){

              $user_id = $this->input->get_post('user_id');
              $item_id = $this->input->get_post('item_id');
              $quantity = $this->input->get_post('quantity');
              $color = $this->input->get_post('color');
              $size = $this->input->get_post('size');
              $type = $this->input->get_post('type');
              $date = date('Y-m-d');
              $time = date('h:i:s');
              $arr_get = ['user_id' => $user_id,'item_id' => $item_id, 'status' => 'Pending'];
              $arr_data = ['user_id' => $user_id,'item_id' => $item_id, 'quantity' => $quantity, 'date' => $date, 'time' => $time, 'color' => $color, 'size' => $size, 'type' => $type];

              $login = $this->webservice_model->get_where('add_to_cart',$arr_get);
                        //echo $this->db->last_query();
              if ($login) {                                

                $this->webservice_model->update_data('add_to_cart',$arr_data,$arr_get);
                $ressult['result']='cart update successfull';
                $ressult['message']='successfull';
                $ressult['status']='1';
                $json = $ressult;

              }else{
                $this->webservice_model->insert_data('add_to_cart',$arr_data);
                $ressult['result']='add to cart successfull';
                $ressult['message']='successfull';
                $ressult['status']='1';
                $json = $ressult;       
              }

              header('Content-type:application/json');
              echo json_encode($json);
            }


            /************* get_cart function *************/

            public function get_cart(){

              $user_id = $this->input->get_post('user_id');
              $arr_get = ['user_id' => $user_id, 'status' => 'Pending'];

              $fetch = $this->webservice_model->get_where('add_to_cart',$arr_get);
                        //echo $this->db->last_query();
              if ($fetch) {                                

                foreach($fetch as $val)
                {

                  if($val['type']=='shop'){
                    $get = $this->webservice_model->get_where('shop_sub_cat',['id'=>$val['item_id']]);
                  }else{
                    $get = $this->webservice_model->get_where('restaurant_sub_cat',['id'=>$val['item_id']]);
                  }


                  $total[] = ($get[0]['price']*$val['quantity']);

                  $val['price'] = $get[0]['price'];
                  $val['item_name'] = $get[0]['name'];
                  $val['image'] = SITE_URL.'uploads/images/'.$get[0]['image'];            
                  $data[] = $val;

                }

                $user_address = [];

                $address = $this->webservice_model->get_where('user_address',['user_id'=>$user_id]);
                if($address){ $user_address =  $address[0]; }

                $ressult['user_address'] = $user_address;
                $ressult['total'] = array_sum($total);
                $ressult['result']=$data;
                $ressult['message']='successfull';
                $ressult['status']='1';
                $json = $ressult;

              }else{

                $ressult['result']='no data found';
                $ressult['message']='unsuccessfull';
                $ressult['status']='0';
                $json = $ressult;       
              }

              header('Content-type:application/json');
              echo json_encode($json);
            }

            /************* get_filter_list *************/
            public

            function get_filter_list()
            {
              $type = $this->input->get_post('type');
              $lat = $this->input->get_post('lat');
              $lon = $this->input->get_post('lon');
              $nearby = $this->input->get_post('nearby');
              $review = $this->input->get_post('review');
              $price = $this->input->get_post('price');



              if($type=='restaurant'){
               $list = $this->webservice_model->get_all('restaurant');
               if($price!=''){

                $this->get_restaurant_item($price);    
                die;
              }                             
            }else{
             $list = $this->webservice_model->get_all('shop');
             if($price!=''){

               $this->get_shop_item($price);                                 
               die;
             }                             
           }

           if ($list)
           {
            foreach($list as $val)
            {

              $distance = $this->webservice_model->distance($lat, $lon, $val['lat'], $val['lon'], $miles = false);
              if($type=='restaurant'){
                $get = $this->db->select_avg("rating", "rating")->where(['restaurant_id'=>$val['id']])->get('restaurant_review')->result_array();
                $get_review = $this->db->where(['restaurant_id'=>$val['id']])->get('restaurant_review')->result_array();
                $review_count = $this->db->where(['restaurant_id'=>$val['id']])->get('restaurant_review')->num_rows();
              }else{
               $get = $this->db->select_avg("rating", "rating")->where(['shop_id'=>$val['id'],'review'=>''])->get('shop_review')->result_array();
               $get_review = $this->db->where(['shop_id'=>$val['id']])->get('shop_review')->result_array();
               $review_count = $this->db->where(['shop_id'=>$val['id']])->get('shop_review')->num_rows();
               $val['image1']=SITE_URL.'uploads/images/'.$val['image1'];
               $val['image2']=SITE_URL.'uploads/images/'.$val['image2'];
               $val['image3']=SITE_URL.'uploads/images/'.$val['image3'];
               $val['image4']=SITE_URL.'uploads/images/'.$val['image4'];

             }

             $reviews = array();
             foreach($get_review as $rev)
             {

              if($rev['review']!=''){
                $user_id = $rev['user_id'];
                $users = $this->webservice_model->get_where('users',['id'=>$user_id]);
                $reviews[] = ['username'=>$users[0]['username'],'review'=>$rev['review']];
              }
            }

            $rating = ($get[0]['rating']=='') ?  0 : $get[0]['rating'];   
            $val['review_count'] = $review_count;
            $val['rating'] = $rating;
            $val['review'] = $reviews;
            $val['distance'] = number_format($distance,2);
            $val['image']=SITE_URL.'uploads/images/'.$val['image'];  

            if($nearby=='filter' && $distance<=5){
              $data[] = $val;
            }else if($review=='filter'){
              $data[] = $val;
            }


          }

          if(!isset($data)){
           $ressult['result']='Data Not Found';
           $ressult['message']='unsuccessful';
           $ressult['status']='0';
           $json = $ressult;
           header('Content-type: application/json');
           echo json_encode($json); die;

         }


         if($review=='filter'){

          $dis = array();
          foreach ($data as $key => $row)
          {
           $dis[$key] = $row['review_count'];
         }
         array_multisort($dis, SORT_DESC, $data);                                  

       }else{

        $dis = array();
        foreach ($data as $key => $row)
        {
         $dis[$key] = $row['distance'];
       }
       array_multisort($dis, SORT_ASC, $data);

     }



     $ressult['result']=$data;
     $ressult['message']='successful';
     $ressult['status']='1';
     $json = $ressult;
   }
   else
   {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;                              

  }



  header('Content-type: application/json');
  echo json_encode($json);
}


/************* get_shop_item *************/
public

function get_shop_item($price)
{
  $list = $this->webservice_model->get_where('shop_sub_cat',"price <= $price");
                        //echo $this->db->last_query(); die;

      //print_r($lis);

  if ($list)
  {
    foreach($list as $val)
    {

      $get_data = $this->db->where('item_id',$val['id'])->get('shop_color')->result_array();
      $val['cours'] = $get_data;
      $get_data = $this->db->where('item_id',$val['id'])->get('shop_size')->result_array();
      $val['size'] = $get_data;
      $val['image']=SITE_URL.'uploads/images/'.$val['image'];
      $val['image1']=SITE_URL.'uploads/images/'.$val['image1'];
      $val['image2']=SITE_URL.'uploads/images/'.$val['image2'];
      $val['image3']=SITE_URL.'uploads/images/'.$val['image3'];
      $val['image4']=SITE_URL.'uploads/images/'.$val['image4'];

      $data[] = $val;

    }

    $ressult['result']=$data;
    $ressult['message']='successful';
    $ressult['status']='1';
    $json = $ressult;
  }
  else
  {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;                              

  }



  header('Content-type: application/json');
  echo json_encode($json);
}



/************* get_restaurant_item *************/
public

function get_restaurant_item()
{
 $list = $this->webservice_model->get_where('restaurant_sub_cat',"price <= $price");

      //print_r($lis);

 if ($list)
 {
  foreach($list as $val)
  {


    $val['image']=SITE_URL.'uploads/images/'.$val['image'];            
    $data[] = $val;

  }

  $ressult['result']=$data;
  $ressult['message']='successful';
  $ressult['status']='1';
  $json = $ressult;
}
else
{
  $ressult['result']='Data Not Found';
  $ressult['message']='unsuccessful';
  $ressult['status']='0';
  $json = $ressult;                              

}



header('Content-type: application/json');
echo json_encode($json);
}

/************* delete_cart_item *************/
public

function delete_cart_item()
{
  $id = $this->input->get_post('cart_id');
  $list = $this->webservice_model->get_where('add_to_cart',['id'=>$id]);

  if ($list)
  {
    $this->webservice_model->delete_data('add_to_cart',['id'=>$id]);

    $ressult['result']="Item delete successfull";
    $ressult['message']='successful';
    $ressult['status']='1';
    $json = $ressult;
  }
  else
  {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;                              

  }



  header('Content-type: application/json');
  echo json_encode($json);
}

/*************  place_order *************/
public

function place_order()
{                     

  $arr_data = array(
    'user_id' => $this->input->get_post('user_id'),
    'full_name' => $this->input->get_post('full_name'), 
    'address' => $this->input->get_post('address'), 
    'mobile' => $this->input->get_post('mobile'),
    'country' => $this->input->get_post('country'), 
    'state' => $this->input->get_post('state'), 
    'city' => $this->input->get_post('city'),
    'zip_code' => $this->input->get_post('zip_code')                                     
  );

  $where = ['user_id'=>$arr_data['user_id']];

  $fetch = $this->webservice_model->get_where('user_address',$where);

  if($fetch){

    $this->webservice_model->update_data('user_address',$arr_data,$where);
    $address_id = $fetch[0]['id'];

  }else{

    $address_id = $this->webservice_model->insert_data('user_address', $arr_data);

  }

  $order_id = $this->webservice_model->generateRandomString(8);
  $delivery_date = date('Y-m-d', strtotime("+3 days"));

  $arr_ord = array(
    'user_id' => $this->input->get_post('user_id'),
    'cart_id' => $this->input->get_post('cart_id'), 
    'address_id' => $address_id,  
    'order_id' => $order_id,  
    'delivery_date' => $delivery_date                                
  );

  $type = "COD";
  $cart_ids = explode(",",$arr_ord['cart_id']);
  foreach($cart_ids as $ids){

    $arr_ord['cart_id'] = $ids;

    $order = $this->webservice_model->insert_data('place_order', $arr_ord);

    $cart = $this->webservice_model->get_where('add_to_cart',['id'=>$ids]);
    if($cart[0]['type']=='shop'){
      $get = $this->webservice_model->get_where('shop_sub_cat',['id'=>$cart[0]['item_id']]);
    }else{
      $get = $this->webservice_model->get_where('restaurant_sub_cat',['id'=>$cart[0]['item_id']]);
    }

    $admin = $this->webservice_model->get_where('admin',['id'=>$get[0]['user_id']]);
    $cntry = $this->webservice_model->get_where('country',['currency'=>$get[0]['currancy']]);

    if($cntry[0]['country']!=$arr_data['country']){
      $type = "PAYBLE";
    }


    /* start code for send email */

    $to = $admin[0]['email'];
    $subject = "Your product is sell out";
    $body = "<div style='max-width: 600px; width: 100%; margin-left: auto; margin-right: auto;'>
    <header style='color: #fff; width: 100%;'>
    <img alt='' src='".SITE_URL."uploads/images/logo.png' width ='120' height='120'/>
    </header>

    <div style='margin-top: 10px; padding-right: 10px; 
    padding-left: 125px;
    padding-bottom: 20px;'>
    <hr>
    <h3 style='color: #232F3F;'>Hello ".$admin[0]['name'].",</h3>
    <p>You product of ".$get[0]['name']." is purchase by user ".$arr_data['full_name'].".</p>
    <p>Its mobile number is <span style='background:#2196F3;color:white;padding:0px 5px'>".$arr_data['mobile']."</span></p>
    <hr>

    <p>Warm Regards<br>SNIFF<br>Support Team</p>

    </div>
    </div>

    </div>";

    $headers = "From: info@technorizen.com" . "\r\n";
    $headers.= "MIME-Version: 1.0" . "\r\n";
    $headers.= "Content-type:text/html;charset=UTF-8" . "\r\n";

    mail($to, $subject, $body, $headers);
    /* end code for send email */




  }


  if ($order != "") {

    $single_data = ['order_id' => $order_id];

    $fetch_order = $this->webservice_model->get_where('place_order',$single_data); 

    $ressult['result']=$fetch_order;
    $ressult['pay_method']=$type;
    $ressult['message']='successful';
    $ressult['status']='1';
    $json = $ressult;                      


  }
  else {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);
}

/*************  payment *************/
public

function payment()
{                     

  $arr_data = array(
    'user_id' => $this->input->get_post('user_id'),
    'order_id' => $this->input->get_post('order_id'), 
    'payment_method' => $this->input->get_post('payment_method'), 
    'total_amount' => $this->input->get_post('total_amount')                           
  );



  $pay = $this->webservice_model->insert_data('payment', $arr_data);

  $this->webservice_model->update_data('place_order',['status'=>'Complete'],['order_id'=>$arr_data['order_id']]);

  $get_order = $this->webservice_model->get_where('place_order',['order_id'=>$arr_data['order_id']]);

  $cart_ids = explode(",",$get_order[0]['cart_id']);
  foreach($cart_ids as $ids){

   $this->webservice_model->update_data('add_to_cart',['status'=>'Complete'],['id'=>$ids]);
 }

 if ($pay != "") {

  $single_data = ['id' => $pay];

  $fetch_order = $this->webservice_model->get_where('payment',$single_data); 

  $ressult['result']=$fetch_order[0];
  $ressult['message']='successful';
  $ressult['status']='1';
  $json = $ressult;
}
else {
  $ressult['result']='Data Not Found';
  $ressult['message']='unsuccessful';
  $ressult['status']='0';
  $json = $ressult;
}

header('Content-type: application/json');
echo json_encode($json);
}

/************* get_order *************/
public

function get_order()
{

  $user_id = $this->input->get_post('user_id');
  $list = $this->webservice_model->get_where('place_order',['user_id' => $user_id]);

      //print_r($lis);

  if ($list)
  {
    foreach($list as $val)
    {

      $item_data = $item = [];
      $cart_ids = explode(",",$val['cart_id']);
      foreach($cart_ids as $ids){

        $fetch = $this->webservice_model->get_where('add_to_cart',['id'=>$ids]);

        if($fetch[0]['type']=='shop'){
         $get = $this->webservice_model->get_where('shop_sub_cat',['id'=>$fetch[0]['item_id']]);
       }else{
         $get = $this->webservice_model->get_where('restaurant_sub_cat',['id'=>$fetch[0]['item_id']]);
       }

       $item['id'] = $ids;
       $item['item_name'] = $get[0]['name'];
       $item['image'] = SITE_URL.'uploads/images/'.$get[0]['image'];            
       $item_data[] = $item;


     }

     $val['item_data'] = $item_data;   
     $data[] = $val;

   }

   $ressult['result']=$data;
   $ressult['message']='successful';
   $ressult['status']='1';
   $json = $ressult;
 }
 else
 {
  $ressult['result']='Data Not Found';
  $ressult['message']='unsuccessful';
  $ressult['status']='0';
  $json = $ressult;                              

}



header('Content-type: application/json');
echo json_encode($json);
}


/************* get_search_list *************/
public

function get_search_list()
{
  $type = $this->input->get_post('type'); 
  $lat = $this->input->get_post('lat');
  $lon = $this->input->get_post('lon');
  $search = $this->input->get_post('search');

  $data = array();
  if($type=='restaurant'){
   $list = $this->webservice_model->get_where('restaurant',"name 
    '%$search%'");
   $return = $this->get_restaurant_search($search);
   if($return){
     $data = $return;
   }
 }else{
   $list = $this->webservice_model->get_where('shop',"name LIKE '%$search%'");
   $return = $this->get_shop_search($search);
   if($return){
     $data = $return;
   }
 }
  //print_r($data); die;
 if ($list)
 {
  foreach($list as $val)
  {

    $distance = $this->webservice_model->distance($lat, $lon, $val['lat'], $val['lon'], $miles = false);
    $videos = $reviews = array();
    if($type=='restaurant'){
      $get = $this->db->select_avg("rating", "rating")->where(['restaurant_id'=>$val['id']])->get('restaurant_review')->result_array();
      $get_review = $this->db->where(['restaurant_id'=>$val['id']])->get('restaurant_review')->result_array();
    }else{
     $get = $this->db->select_avg("rating", "rating")->where(['shop_id'=>$val['id']])->get('shop_review')->result_array();
     $get_review = $this->db->where(['shop_id'=>$val['id']])->get('shop_review')->result_array();

     $get_video = $this->db->where(['item_id'=>$val['id']])->get('shop_video')->result_array();                                

     foreach($get_video as $vid)
     {
       $vid['video']=SITE_URL.'uploads/images/'.$vid['video'];
       $videos[] = $vid;                     
     }

     $val['image1']=SITE_URL.'uploads/images/'.$val['image1'];
     $val['image2']=SITE_URL.'uploads/images/'.$val['image2'];
     $val['image3']=SITE_URL.'uploads/images/'.$val['image3'];
     $val['image4']=SITE_URL.'uploads/images/'.$val['image4'];


   }

   $val['discount_img']=SITE_URL.'uploads/images/'.$val['discount_img'];

   foreach($get_review as $rev)
   {

    if($rev['review']!=''){
      $user_id = $rev['user_id'];
      $users = $this->webservice_model->get_where('users',['id'=>$user_id]);
      $reviews[] = ['username'=>$users[0]['username'],'review'=>$rev['review']];
    }
  }

  $rating = ($get[0]['rating']=='') ?  0 : $get[0]['rating'];  

  $val['videos'] = $videos;
  $val['rating'] = $rating;
  $val['review'] = $reviews;
  $val['distance'] = number_format($distance,2);
  $val['image']=SITE_URL.'uploads/images/'.$val['image'];            
  $data[] = $val;

}
}

if($data){


  $arr = [];
  foreach($data as $items){
    if(!in_array($items['id'],$arr)){
     $array[] = $items;
     $arr[] = $items['id'];
   }                            
 }


 $ressult['result']=$array;
 $ressult['message']='successful';
 $ressult['status']='1';
 $json = $ressult;
}
else
{
  $ressult['result']='Data Not Found';
  $ressult['message']='unsuccessful';
  $ressult['status']='0';
  $json = $ressult;                              

}



header('Content-type: application/json');
echo json_encode($json);
}


/************* get_shop_search *************/
public

function get_shop_search($search)
{
  $list = $this->webservice_model->get_where('shop_sub_cat',"name LIKE '%$search%' GROUP BY shop_id");

                  //print_r($lis);

  if ($list)
  {
    $ids = "";
    foreach($list as $vals){
      if($ids==""){
       $ids = $vals['shop_id'];
     }else{
       $ids = $ids.",".$vals['shop_id'];
     }
   }
   $shopList = $this->webservice_model->get_where('shop',"id IN($ids)");

   foreach($shopList as $val){

     $videos = $reviews = array();

     $get = $this->db->select_avg("rating", "rating")->where(['shop_id'=>$val['id']])->get('shop_review')->result_array();
     $get_review = $this->db->where(['shop_id'=>$val['id']])->get('shop_review')->result_array();

     $get_video = $this->db->where(['item_id'=>$val['id']])->get('shop_video')->result_array();                                

     foreach($get_video as $vid)
     {
       $vid['video']=SITE_URL.'uploads/images/'.$vid['video'];
       $videos[] = $vid;                     
     }

     $val['image1']=SITE_URL.'uploads/images/'.$val['image1'];
     $val['image2']=SITE_URL.'uploads/images/'.$val['image2'];
     $val['image3']=SITE_URL.'uploads/images/'.$val['image3'];
     $val['image4']=SITE_URL.'uploads/images/'.$val['image4'];
     $val['discount_img']=SITE_URL.'uploads/images/'.$val['discount_img'];                                   



     foreach($get_review as $rev){

      if($rev['review']!=''){
        $user_id = $rev['user_id'];
        $users = $this->webservice_model->get_where('users',['id'=>$user_id]);
        $reviews[] = ['username'=>$users[0]['username'],'review'=>$rev['review']];
      }
    }

    $rating = ($get[0]['rating']=='') ?  0 : $get[0]['rating'];  

    $val['videos'] = $videos;
    $val['rating'] = $rating;
    $val['review'] = $reviews;
    $val['distance'] = '';
    $val['image']=SITE_URL.'uploads/images/'.$val['image'];            
    $data[] = $val;

  }                               




  return $data;


}else{ return false; }



}



/************* get_restaurant_search *************/
public

function get_restaurant_search($search)
{
  $list = $this->webservice_model->get_where('restaurant_sub_cat',"name LIKE '%$search%' GROUP BY restaurant_id");

                  //print_r($lis);

  if ($list)
  {
    $ids = "";
    foreach($list as $vals){
      if($ids==""){
       $ids = $vals['restaurant_id'];
     }else{
       $ids = $ids.",".$vals['restaurant_id'];
     }
   }
   $restaurantList = $this->webservice_model->get_where('restaurant',"id IN($ids)");

   foreach($restaurantList as $val){

     $reviews = array();

     $get = $this->db->select_avg("rating", "rating")->where(['restaurant_id'=>$val['id']])->get('restaurant_review')->result_array();
     $get_review = $this->db->where(['restaurant_id'=>$val['id']])->get('restaurant_review')->result_array();                     

     $val['discount_img']=SITE_URL.'uploads/images/'.$val['discount_img'];

     foreach($get_review as $rev){

      if($rev['review']!=''){
        $user_id = $rev['user_id'];
        $users = $this->webservice_model->get_where('users',['id'=>$user_id]);
        $reviews[] = ['username'=>$users[0]['username'],'review'=>$rev['review']];
      }
    }

    $rating = ($get[0]['rating']=='') ?  0 : $get[0]['rating'];       

    $val['rating'] = $rating;
    $val['review'] = $reviews;
    $val['distance'] = '';
    $val['image']=SITE_URL.'uploads/images/'.$val['image'];            
    $data[] = $val;

  }                               




  return $data;


}else{ return false; }



}


/*************  get_my_order *************/
public

function get_my_order()
{                     

  $user_id = $this->input->get_post('user_id');
  $where = "(user_id = '$user_id') AND (status = 'Complete' OR status = 'Waiting' OR status = 'Way') ";

  $fetch = $this->webservice_model->get_where('place_order',$where);



  if ($fetch) {

    foreach($fetch as $val)
    {

      $cart = $this->webservice_model->get_where('add_to_cart',['id'=>$val['cart_id']]);

      if($cart[0]['type']=='shop'){
        $get = $this->webservice_model->get_where('shop_sub_cat',['id'=>$cart[0]['item_id']]);
        $get[0]['image1']=SITE_URL.'uploads/images/'.$get[0]['image1'];
        $get[0]['image2']=SITE_URL.'uploads/images/'.$get[0]['image2'];
        $get[0]['image3']=SITE_URL.'uploads/images/'.$get[0]['image3'];
        $get[0]['image4']=SITE_URL.'uploads/images/'.$get[0]['image4'];

        $get_data = $this->db->where('item_id',$cart[0]['item_id'])->get('shop_color')->result_array();
        $val['cours'] = $get_data;
        $get_data = $this->db->where('item_id',$cart[0]['item_id'])->get('shop_size')->result_array();
        $val['size'] = $get_data;

      }else{
        $get = $this->webservice_model->get_where('restaurant_sub_cat',['id'=>$cart[0]['item_id']]);
      }

      $get[0]['image']=SITE_URL.'uploads/images/'.$get[0]['image'];

      $total[] = ($get[0]['price']*$cart[0]['quantity']);                            
      $val['product']=$get[0];                           
      $val['quantity']=$cart[0]['quantity'];                                        
      $data[] = $val;

    }

    $ressult['total'] = array_sum($total);
    $ressult['result']=$data;
    $ressult['message']='successful';
    $ressult['status']='1';
    $json = $ressult;                      


  }
  else {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);

}


/*************  contact_info *************/
public

function contact_info()
{                     

  $arr_data = array(
    'email' => $this->input->get_post('email'),
    'message' => $this->input->get_post('message'), 
    'phone' => $this->input->get_post('phone')                           
  );



  $fetch = $this->webservice_model->insert_data('contact_info', $arr_data);




  if ($fetch != "") {

    $single_data = ['id' => $fetch];

    $fetch_order = $this->webservice_model->get_where('contact_info',$single_data); 

    $ressult['result']=$fetch_order[0];
    $ressult['message']='successful';
    $ressult['status']='1';
    $json = $ressult;
  }
  else {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);
}


/*************  country_list *************/
public

function country_list()
{                     

  $fetch = $this->webservice_model->get_all('country');


  if ($fetch) {

    foreach($fetch as $val)
    {

      $data[] = $val;

    }

    $ressult['result']=$data;
    $ressult['message']='successful';
    $ressult['status']='1';
    $json = $ressult;                      


  }
  else {
    $ressult['result']='Data Not Found';
    $ressult['message']='unsuccessful';
    $ressult['status']='0';
    $json = $ressult;
  }

  header('Content-type: application/json');
  echo json_encode($json);

}

/************* get_offer_list *************/
public

function get_offer_list()
{
  $list = $this->webservice_model->get_where('shop_sub_cat',['offer'=>'YES']);


  if ($list)
  {                              

    foreach($list as $val){

     $videos = array();                                  

     $get_video = $this->db->where(['item_id'=>$val['id']])->get('shop_video')->result_array();                                

     foreach($get_video as $vid)
     {
       $vid['video']=SITE_URL.'uploads/images/'.$vid['video'];
       $videos[] = $vid;                     
     }

     $val['image1']=SITE_URL.'uploads/images/'.$val['image1'];
     $val['image2']=SITE_URL.'uploads/images/'.$val['image2'];
     $val['image3']=SITE_URL.'uploads/images/'.$val['image3'];
     $val['image4']=SITE_URL.'uploads/images/'.$val['image4'];

     
     $val['videos'] = $videos;
     $val['distance'] = '';
     $val['image']=SITE_URL.'uploads/images/'.$val['image'];            
     $data[] = $val;

   }                               




   $ressult['result']=$data;
   $ressult['message']='successful';
   $ressult['status']='1';
   $json = $ressult;  


 }else{ 

  $ressult['result']='Data Not Found';
  $ressult['message']='unsuccessful';
  $ressult['status']='0';
  $json = $ressult;

}

header('Content-type: application/json');
echo json_encode($json);



}


function humanTiming($time)
{

    // echo date('Y-m-d H:i:s');  die;
    ///echo    date('Y-m-d h:i:s');
    $time = strtotime(date('Y-m-d H:i:s')) - $time; // to get the time since that moment 

    $time = ($time < 1) ? 1 : $time;
    $tokens = array(
      31536000 => 'year',
      2592000 => 'month',
      604800 => 'week',
      86400 => 'day',
      3600 => 'hour',
      60 => 'minute',
      1 => 'second'
    );
    
    foreach($tokens as $unit => $text)
    {
      if ($time < $unit) continue;
      $numberOfUnits = floor($time / $unit);
      return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? '' : '');
    }
  }



  function get_token_key() {

   $uri = "https://api.stripe.com\Stripe\Charge::create(array(
   'amount' => 2000,
   'currency' => 'usd',
   'source' => 'tok_189gKA2eZvKYlo2CfUDZuPeT', 
   'metadata' => array('order_id' => '6735')))";




   $curl = curl_init();

   curl_setopt_array($curl, array(
    CURLOPT_URL => $uri
  ));

   $response = curl_exec($curl);
   $err = curl_error($curl);

   curl_close($curl);

   if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    echo $response;
  }


}


function convertToHoursMins($time) {
      // $seconds = ($time*60);
      // $hours = floor($seconds / 3600);
      // $minutes = floor(($seconds / 60) % 60);
      // $seconds = $seconds % 60;
      // return "$hours:$minutes:$seconds";


  $days = floor($time / (60 * 60 * 24));
  $time -= $days * (60 * 60 * 24);

  $hours = floor($time / (60 * 60));
  $time -= $hours * (60 * 60);

  $minutes = floor($time / 60);
  $time -= $minutes * 60;

  $seconds = floor($time);
  $time -= $seconds;

  return $hours.' hours '.$minutes.' minutes'; // 1d 6h 50m




} 










    // end class
}

?>
