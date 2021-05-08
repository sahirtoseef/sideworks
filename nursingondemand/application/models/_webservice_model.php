<?php 
if (!defined('BASEPATH')) exit('No direct script access allowd');
class Webservice_model extends CI_Model{

	public function get_request($where){
		$data =	$this->db->where($where)->order_by('created_date','DESC')
		->get('request_care');

			//echo $this->db->last_query();

		if($res = $data->result_array()){
			return $res;
		}else{
			return FALSE;
		}
	}

	public function get_current_request($user_id){
		//$arr_whr = ['request_care.user_id'=>$user_id, 'request_care.status'=>'Current'];
		$arr_whr = "request_care.user_id='$user_id' AND (request_care.status='Current' OR request_care.status='Pending')";

		$this->db->select("service_types.service_type, request_care.nurse_id, request_care.care_person, request_care.id, request_care.created_date",false);
		$this->db->from("request_care");
		$this->db->join("service_types", "service_types.id = request_care.service_type_id", "inner");
		$this->db->where($arr_whr,null,false);
		
		$this->db->order_by('request_care.created_date','DESC');	
		$this->db->limit(1);
		$data = $this->db->get();//var_dump($this->db->last_query());die();
		if($res = $data->result_array()){
			$arr_whr = ['id'=>$res[0]['nurse_id']];
			$this->db->select("users.firstname, users.lastname, users.phone_number",false);
			$this->db->from("users");
			$this->db->where($arr_whr);
			$this->db->limit(1);
			$data = $this->db->get();
			if($nurse = $data->result_array()){
				$res[0]['firstname'] = $nurse[0]['firstname'];
				$res[0]['lastname'] = $nurse[0]['lastname'];
				$res[0]['phone_number'] = $nurse[0]['phone_number'];			
			}
			else{
				$res[0]['firstname'] = null;
				$res[0]['lastname'] = null;
				$res[0]['phone_number'] = null;			
			}

			return $res[0];
		}else{
			return FALSE;
		}
	}

	public function get_where($table,$where, $order_by=null){
		if($order_by){
			$this->db->order_by($order_by);
		}
		$data =	$this->db->where($where)
		->get($table);

			//echo $this->db->last_query();


		if($res = $data->result_array()){
			return $res;
		}else{
			return FALSE;
		}

		
	}	

	public function insert_data($table,$data){
		$this->db->insert($table,$data);
		return $this->db->insert_id();
	}

	public function update_data($table,$data,$where){
		$query = $this->db->where($where)
		->update($table,$data);
		if ($query)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function get_all($table){
		$data = $this->db->get($table);
		$data = $data->result_array();
		if ($data) {
			return $data;
		}else{
			return FALSE;
		}
	}

	public function humanTiming($time)
	{
		$time = time() - $time; // to get the time since that moment
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
			return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
		}
	}

	function delete_data($table, $where)
	{
		$del = $this->db->where($where)
		->delete($table);
		if ($del)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	function generateRandomString($num) {
		$length=$num;
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	function distance($lat1, $lng1, $lat2, $lng2, $miles = false)
	{
		$lat1 = is_numeric($lat1) ? $lat1 : 0; 
		$lng1 = is_numeric($lng1) ? $lng1 : 0; 
		$lat2 = is_numeric($lat2) ? $lat2 : 0; 
		$lng2 = is_numeric($lng2) ? $lng2 : 0;
		//var_dump($lat1, $lng1, $lat2, $lng2, $miles);
		$pi80 = M_PI / 180;
		$lat1*= $pi80;
		$lng1*= $pi80;
		$lat2*= $pi80;
		$lng2*= $pi80;
		$r = 6372.797; // mean radius of Earth in km
		$dlat = $lat2 - $lat1;
		$dlng = $lng2 - $lng1;
		$a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
		$c = 2 * atan2(sqrt($a) , sqrt(1 - $a));
		$km = $r * $c;
		return ($miles ? ($km * 0.621371192) : $km);
	}


	function user_apk_notification($registration_ids, $message)
	{

		// Set POST variables
		//print_r($registration_ids); die;
		$url = 'https://android.googleapis.com/gcm/send';
		$fields = array(
			'registration_ids' => $registration_ids,
			'data' => $message,
		);
		
		$headers = array(
			'Authorization: key=' . "AAAAbq7f6XY:APA91bE6B8ILBRrSuH53QRKf7BeFsWPaXYjo1h_sUBvx9DjrD7p9-e9avHcPwC1sN-WAeJZCxX04B63BVPNciU1NoVsw_6IpdhV8y_YsYEeMw2wIGMkxyVJKd6Km09ue3hRn_Qew0LGT",
			'Content-Type: application/json'
		);

		// print_r($headers);
		// Open connection

		$ch = curl_init();

		// Set the url, number of POST vars, POST data

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Disabling SSL Certificate support temporarly

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

		// Execute post

		$result = curl_exec($ch);
		if ($result === FALSE)
		{
			die('Curl failed: ' . curl_error($ch));
		}

		// Close connection

		curl_close($ch);

		//echo $result;

	}

	function nurse_apk_notification($registration_ids, $message)
	{

		// Set POST variables

		// print_r($message);
		// print_r($registration_ids); die;
		$url = 'https://android.googleapis.com/gcm/send';
		$fields = array(
			'registration_ids' => $registration_ids,
			'data' => $message,
		);
		$headers = array(
			'Authorization: key=' . "AAAAoeDD7lY:APA91bGN5U6fT9fqvBu8c5bIZzWvC36npQuMChlnjb3n3zeS_VQ5N137hpCxJG_U7KOe5asqwNX7hufZfnUu8OWnmejmgO6JMu37Lwu17EhRjfckEzlrI-cXPQSoSlGTaABsLdK9VYRs",
			'Content-Type: application/json'
		);

		// print_r($headers);
		// Open connection

		$ch = curl_init();

		// Set the url, number of POST vars, POST data

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Disabling SSL Certificate support temporarly

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

		// Execute post

		$result = curl_exec($ch);
		if ($result === FALSE)
		{
			die('Curl failed: ' . curl_error($ch));
		}

		// Close connection

		curl_close($ch);

		//echo $result;

	}


	public function sendPushNotificationToFCMSever($token, $message) 
	{
		$path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';

		$fields = array(
			'to' => $token,
			'content_available' => true,
			'data' => $message,
		);
		
		$headers = array(
			'Authorization: key=' . "AAAAoeDD7lY:APA91bGN5U6fT9fqvBu8c5bIZzWvC36npQuMChlnjb3n3zeS_VQ5N137hpCxJG_U7KOe5asqwNX7hufZfnUu8OWnmejmgO6JMu37Lwu17EhRjfckEzlrI-cXPQSoSlGTaABsLdK9VYRs",
			'Content-Type: application/json'
		);

		// Open connection  
		$ch = curl_init(); 
		// Set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm); 
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		// Execute post   
		$result = curl_exec($ch); 
		// Close connection      
		curl_close($ch);
		//echo $result;
	}


	function ios_user_fcm_notification($registrationIds, $message) 
	{

		$fields = array(
			'registration_ids' => $registrationIds,
			'data' => $message,
		);

		$headers = array
		(
			'Authorization: key=' . "AAAAoeDD7lY:APA91bGN5U6fT9fqvBu8c5bIZzWvC36npQuMChlnjb3n3zeS_VQ5N137hpCxJG_U7KOe5asqwNX7hufZfnUu8OWnmejmgO6JMu37Lwu17EhRjfckEzlrI-cXPQSoSlGTaABsLdK9VYRs",
			'Content-Type: application/json',
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);


		curl_close($ch);

		//echo $result;

	}


	function getNurseDeviceIdWithinMiles($lat, $lng, $miles = 20){
		$this->db->select("register_id, ( 3959 * acos( cos( radians($lat) ) * cos( radians( lat ) ) * cos( radians( lon ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( lat ) ) ) ) AS distance");                         
		$this->db->having('distance <= ' . $miles);                     
		$this->db->order_by('distance');
		$this->db->where('user_type','NURSE');
		$query = $this->db->get('users'); 

		if($res = $query->result_array()){
			return array_column($res,'register_id');
		}else{
			return FALSE;
		}
	}

	function getNurseNotifications($lat, $lng, $miles = 20, $nurse_id){
		$site_url = SITE_URL;
		$this->db->select("users.firstname, users.lastname, CONCAT('$site_url/uploads/images/', CASE WHEN users.image = '' THEN 'user.jpg'
WHEN users.image IS NULL THEN 'user.jpg'
ELSE users.image END) AS image, 

service_types.service_type, request_care.care_person, request_care.id, ( 3959 * acos( cos( radians($lat) ) * cos( radians( lat ) ) * cos( radians( lon ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( lat ) ) ) ) AS distance, request_care.created_date",false);
		$this->db->from("request_care");
		$this->db->join("users","users.id = request_care.user_id","inner");
		$this->db->join("service_types", "service_types.id = request_care.service_type_id", "inner");
		$this->db->having('distance <= ' . $miles);
		$this->db->order_by('request_care.created_date','DESC');
		//$this->db->where('request_care.nurse_id',null);
		
		/*$where = "request_care.nurse_id IS NULL OR request_care.nurse_id = '0' OR 
					( request_care.nurse_id = '$nurse_id' AND ( request_care.status = 'Current' OR request_care.status = 'Scheduled' ) )";		
		$this->db->where($where);*/

		$query = $this->db->get();
		if($res = $query->result_array()){
			return $res;
		}else{
			return FALSE;
		}
	}

	function getCurrentRequestNotes($nurseId){
		$site_url = SITE_URL;
		$this->db->select("request_care.electronic_notes, request_care.id, request_care.nurse_id, CONCAT('$site_url/uploads/images/', CASE WHEN users.image = '' THEN 'user.jpg'
WHEN users.image IS NULL THEN 'user.jpg'
ELSE users.image END) AS image,

service_types.service_type, request_care.request_id, request_care.created_date, users.firstname, users.lastname",false);
		$this->db->from("request_care");
		$this->db->join("users","users.id = request_care.user_id","inner");
		$this->db->join("service_types", "service_types.id = request_care.service_type_id", "inner");
		$this->db->order_by('request_care.created_date','DESC');
		$this->db->where('request_care.nurse_id',$nurseId);
		//$this->db->where('request_care.status','Current');
		$this->db->where_in('request_care.status',array('Current','Completed'));

		$query = $this->db->get();
		if($res = $query->result_array()){
			return $res;
		}else{
			return FALSE;
		}
	}

	function getAllAvailableRequest($lat, $lng, $nurse_id, $miles = 20){
		$site_url  =SITE_URL;
		$this->db->select("users.firstname, users.lastname, CONCAT('$site_url/uploads/images/', 

CASE WHEN users.image = '' THEN 'user.jpg'
WHEN users.image IS NULL THEN 'user.jpg'
ELSE users.image END) AS image, 

request_care.created_date AS date_time, service_types.service_type, request_care.care_person, request_care.id, request_care.nurse_id, ( 3959 * acos( cos( radians($lat) ) * cos( radians( users.lat ) ) * cos( radians( users.lon ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( users.lat ) ) ) ) AS distance",false);
		$this->db->from("request_care");
		$this->db->join("users","users.id = request_care.user_id","inner");
		$this->db->join("service_types", "service_types.id = request_care.service_type_id", "inner");
		$this->db->having('distance <= ' . $miles);
		$this->db->order_by('request_care.created_date','DESC');
		
		$where = "request_care.nurse_id IS NULL OR request_care.nurse_id = '0' OR 
					( request_care.nurse_id = '$nurse_id' AND ( request_care.status = 'Current' OR request_care.status = 'Scheduled' ) )";
		$this->db->where($where);	
		//$this->db->where('request_care.nurse_id',null);
		//$this->db->or_where('request_care.nurse_id',0);
		//$this->db->or_where('request_care.nurse_id',$nurse_id);

		$query = $this->db->get();//var_dump($this->db->last_query());die();
		if($res = $query->result_array()){
			return $res;
		}else{
			return FALSE;
		}
	}


	function sendPushNotification($deviceIds,$message){
		$path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';

		$fields = array(
			'registration_ids' => $deviceIds,
			'content_available' => true,
			'data'=>$message,
			"notification"=>array(
				"title"=>$message['message']['key'],
				"body"=>$message['message']['body'],
				"sound"=>"default",
				"click_action"=>"FCM_PLUGIN_ACTIVITY",
				"icon"=>"fcm_push_icon"
			)
		);

	//var_dump(json_encode($fields));die();

		//array_push($fields,$message);

		//$headers = array(
		//	'Authorization:key=AIzaSyBnDWXv7JfrNVsgyEA5Gzn9MOJnhdPdqnM',
		//	'Content-type:application/json'
		//);
		
		$headers = array(
			'Authorization:key=AIzaSyDqC0Mcm1cFSW9BlschvNffevmVWxq1qM4',
			'Content-type:application/json'
		);

		// Open connection  
		$ch = curl_init(); 
		// Set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm); 
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		// Execute post   
		$result = curl_exec($ch); 
		// Close connection      
		curl_close($ch);
		return $result;

	}



//end class
}
?>
