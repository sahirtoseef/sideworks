<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User_model extends CI_Model {

	public function __construct(){
		parent::__construct();
		$this->uhash = $this->isBack();
		$this->user = false;
		$this->id = false;
		$this->info = false;
		if($this->uhash){
			$this->user = $this->db->select('*')->from('users_log')->where('log_key',$this->uhash)->get()->row();
			$this->id = !is_null($this->user) ? $this->user->user_id : false;
			$this->info = $this->db->select('`uemail`,`regdate`,`ukey`,`ustatus`,`urole`')->from('users')->where('id',$this->id)->get()->row();
		}
		//var_dump($this->isBack());
	}
	
	function isBack(){
	//	$bkt = hex2bin($this->input->cookie('bkt'));
	  //$this->encodeUserData($bkt);
		return $this->decodeUserData();
	}
	
	function getEmployerList($status = false){
		if($status){
			$this->db->where('status',$status);
		}
		return $this->db->select('*, (SELECT COUNT(*) FROM users where empID = employers.id) as total')->from('employers')->get()->row();
	}
	
	public function isLogin(){
		return $this->id;
	}
	function isAdmin(){
		return $this->info && ($this->info->urole == 1 || $this->info->urole == 2);
	}

	function isSuperAdmin(){
		return $this->info && ($this->info->urole == 1);
	}

	function isSubscriber(){
		return ($this->info->urole == 3);
	}

	function password($val){
		return hash("sha1",hash("sha256",$val));
	}
	
	function xkey($val){
		return hash("sha1",hash("sha256",$val.time()));
	}

	function encodeUserData($log_key){
		$_SESSION["udata"] = bin2hex(encrypt($log_key));
	}

	function decodeUserData(){
		return isset($_SESSION['udata']) ? decrypt(hex2bin($_SESSION['udata'])) : false;
	}
	
	function save_meta($metakey, $metaval, $id = null, $lock = false){
		$id = is_null($id) ? $this->id : $id;
		$id = filter_num($id);
		$metakey = filter_query($metakey);
		$metaval = htmlentities($metaval);
		$meta = $this->db->select('*')->from('users_meta')->where(array('uid'=>$id, "ukey"=>$metakey))->get()->row();
		if(!is_null($meta) && !$lock){
			return $this->db->update('users_meta')->set(array("uval"=>$metaval))->where(array('uid'=>$id, "ukey"=>$metakey));
		}else{
			return $this->db->insert('users_meta',array('uid'=>$id, "ukey"=>$metakey, "uval"=>$metaval));
		}
	}
	
	function get_meta($mkey,$uid = false){
		$uid = !$uid ? $this->id : $uid;
		$data = $this->db->select('mval')->from('users_meta')->where(array('uid'=>$uid, 'ukey'=>$mkey))->get()->row();
		return in_null($data) ? '' : $data['mval'];
	}
	
	function login($args){
		$user = isset($args['user'])?$args['user']:"";
		$pwd = isset($args['pwd'])? $args['pwd'] :"";
		$remember = isset($args['remember']) && ($args['remember']=='true' || $args['remember'] == '1' || $args['remember'] == 'on') ? true : false;
		
		$pwd = $this->password($pwd);
			
		$user = $this->db->select("id,ustatus,urole,pwd,ukey")->from("users")->where("uemail",$user)->get()->row();
		if($user){
			if($pwd==$user->pwd){
				if($user->ustatus=="1" || $user->ustatus=="0"){
					
					
						$log_key = $this->xkey($user->id.$user->ukey);
						////$_SESSION["token"] = $log_key;
						$device = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "Unknown";
						if(defined('MULTI_LOGIN')&&!MULTI_LOGIN){
							$this->db->delete("users_log",array("user_id",filter_num($user->id)));
						}
						$log = $this->db->insert("users_log",array("user_id"=>$user->id,"log_device"=>$device,"log_ip"=>client_ip(),"log_key"=>$log_key));
						if($remember){
							$this->encodeUserData($log_key);
							setcookie("bkt", bin2hex($log_key), 2147483647);
						}else{
							$this->encodeUserData($log_key);
						}
						$response = array("RES"=>"LOGGEDIN");
				}else{
					$response = array("ERR"=>"INVALID_USER_ACCOUNT_OR_BANNED");
				}
			}else{
				$response = array("ERR"=>"INVALID_LOGIN_CREDENTIALS");
			}
		}else{
			$response = array("ERR"=>"USER_ACCOUNT_UNAVAILABLE");
		}
		
		return $response;
	}

	

	public function register($args, $meta = array()){
		$email = isset($args['email'])?$args['email']:"";
		$pwd = isset($args['pwd'])? $this->password($args['pwd']) :"";
		$role = isset($args['role'])?(int)$args['role']:3;
		$status = isset($args['status'])?(int)$args['status']:0;
		$key = $this->password($email.time());
		
		$user = $this->db->select('*')->from("users")->where(array("uemail"=>$email))->get()->row_array();
		if($user){
			$response = array("ERR"=>"ACCOUNT_ALREADY_EXISTS");
		}else{
			$arg = array("uemail"=>$email, "pwd"=>$pwd, "ukey"=>$key,"ustatus"=>$status,"urole"=>$role);
			if($this->config->item('user_can_register')){
				$reg = $this->db->insert("users",$arg);
				$id = $this->db->insert_id();
				foreach($meta as $mkey => $mval){
					$this->save_meta($mkey, $mval, $id);
				}
				$response = array("RES"=>"ACCOUNT_CREATED");
			}else{
				$response = array("ERR"=>"REGISTRATION_DISABLED");
			}
		}
		
		return $response;
	}

	function logout($url = '', $users = false){
		if(!$users){
			$users = array('log_key'=>$this->uhash);
		}
		$this->db->delete('users_log', $users);
		unset($_SESSION["udata"]);
		redirect($url);
		
	}
	
	public function getList(){
		return $this->db->select('*')->from('users')->get()->result();
	}
}