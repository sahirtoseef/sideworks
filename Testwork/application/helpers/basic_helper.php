<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function assets($path = '', $return = false){
	if($return){
	return base_url().'assets/'.$path;
	}else{
		echo base_url().'assets/'.$path;
	}
	
}

function randomstring($length = 10){
	$characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
	$string = '';
	for ($p = 0; $p < $length; $p++) {
		$string .= $characters[mt_rand(0, strlen($characters))];
	}
 
	return bin2hex($string);
}

function result($points){
	if($points >= 40 && $points < 50){
  	return 'Medium Risk';
  }elseif($points >= 50){
    return 'High Risk';
  }else{
  	return 'Safe';
  }
}
function errtype($n){
	$arr = is_numeric($n) ? array("info","success","danger","warning") : array("inf"=>"info","res"=>"success","err"=>"danger","war"=>"warning");
	$n = !is_numeric($n) ? strtolower($n) : $n;
	return isset($arr[$n]) ? $arr[$n] : false;
}

function icon($n){
	$arr = array("info"=>'<i class="fa fa-info-circle"></i>',"success"=>'<i class="fa fa-check-circle"></i>',"danger"=>'<i class="fa fa-exclamation-triangle"></i>',"warning"=>'<i class="fa fa-exclamation-circle"></i>');
	return isset($arr[$n]) ? $arr[$n] : false;
}

function str_valid($val, $type="alnum",$min = false,$max = false){
		if($type=="alpha"){
			$check = ctype_alpha($val);
		}
		elseif($type=="alnum"){
			$check = preg_match("#.*^(?=.*[a-zA-Z])(?=.*[0-9]).*$#",$val);	
		}
		elseif($type=="alcaps"){
			$check = preg_match("#.*^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$#",$val);	
		}
		
		elseif($type=="strong")
		{
			$check = preg_match("#.*^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#",$val);	
		}
		else{
			$check = true;	
		}
		
		if($min && $max){
			if((strlen($val)>=$min)&&(strlen($val)<=$max)&&($check)){
				return true;
			}else{
				return false;
			}
		}
		elseif($min){
			if((strlen($val)>=$min)&&($check)){
				return true;
			}else{
				return false;
			}
		}
		elseif($max){
			if((strlen($val)<=$max)&&($check)){
				return true;
			}else{
				return false;
			}
		}else{
			if($check){
				return true;
			}else{
				return false;
			}
		}
		//return $check;
		
	}
	
	function quetype($n){
		return array('Single','Multiple','Text')[$n];
	}
	
	function selectType($n, $id, $name, $label, $val = '', $points = 0, $class = 'selector'){
		switch($n){
			case 0:
				return "<input id='$id' name='$name' type='radio' points='$points' class='iselect' value='$val' />
            <label for='$id'>$label</label>";
			break;
			case 1:
				return "<input id='$id' name='$name' type='checkbox' points='$points' class='iselect' value='$val' />
            <label for='$id'>$label</label>";
			break;
			case 2:
				return "<label for='$id'>$label</label>
        	  <textarea id='$id' name='$name' class='form-control' points='$points' class='iselect'>$val</textarea>";
			break;
			default:
				return "<label for='$id'>$label</label>
        	  <input id='$id' name='$name' type='text' points='$points' class='iselect' value='$val' />";
			break;
		}
		
	}

function load_model($path){
	if(file_exists($path)){
			require_once $path;
	}
}



function load_view(){
	
}

function userRole($n){
	$arr = array('','Administrator','Employer / Store','Employee / Staff');
	return isset($arr[$n]) ? $arr[$n] : false;
}

function userStatus($n){
	$arr = array('Not Verified','Verified','Ban');
	return isset($arr[$n]) ? $arr[$n] : false;
}

function Age($date){
	$today = date_create(date('Y-m-d'));
	$dob = date_create($date);
	$age = date_diff($dob,$today);
	return $age->format("%y");
}

function pre_replace($val,$prefix = "[^A-Za-z0-9]",$replace = ""){
		return preg_replace('/'.$prefix.'/',"", $val);
}
function client_ip() {
	$ipaddress = '';
	if (isset($_SERVER['HTTP_CLIENT_IP']))
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else if(isset($_SERVER['HTTP_X_FORWARDED']))
		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	else if(isset($_SERVER['HTTP_FORWARDED']))
		$ipaddress = $_SERVER['HTTP_FORWARDED'];
	else if(isset($_SERVER['REMOTE_ADDR']))
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	else
		$ipaddress = 'UNKNOWN';
	return $ipaddress;
}

function filter_slug($val){
	$regex = '/[^A-Za-z0-9]/';
	return preg_replace($regex,"-",$val);
}
function filter_alphanum($val){
	$regex = '/[^A-Za-z0-9]/';
	return preg_replace($regex,"",$val);
}
function filter_alpha($val){
	$regex = '/[^A-Za-z]/';
	return preg_replace($regex,"",$val);
}
function filter_num($val){
	$regex = '/[^0-9]/';
	return preg_replace($regex,"",$val);
}

function array_assoc_unique($array, $key) { 
	$temp_array = array(); 
	$i = 0; 
	$key_array = array(); 
	
	foreach($array as $val) { 
		if (!in_array($val[$key], $key_array)) { 
			$key_array[$i] = $val[$key]; 
			$temp_array[$i] = $val; 
		} 
		$i++; 
	} 
	return $temp_array; 
} 

function printQuestionResult($num) {
	$num = (int) $num;
	if($num >= 40 && $num < 50){
    	return "Medium Risk";
    }elseif($num >= 50){
    	return "High Risk";
    }

   	return "Safe";
    
}

?>