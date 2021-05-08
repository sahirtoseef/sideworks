<?php
defined('BASEPATH') OR exit('No direct script access allowed');
    
    function valid_email($val){
		if(filter_var($val,  FILTER_VALIDATE_EMAIL)){
			return true;
		}else{
			return false;
		}
    }
    
    function valid_password($val,$type="string",$min = 6,$max = 18){
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
		
		if((strlen($val)>=$min)&&(strlen($val)<=$max)&&($check)){
			return true;
		}else{
			return false;
		}
		//return $check;
		
    }
    
    function valid_date($date, $format = "Y-n-d"){
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) === $date;
	}

    function valid_request($val,$method = "POST"){
		$check_post = true;
		$method = strtoupper($method);
		$var = ($method == "GET")?$_GET:$_POST;
		foreach($val as $check){
			if(isset($var[$check])&&!empty($var[$check])){
				$check_post = true;
			}else{
				$check_post = false;
				break;
			}
			
		}
		return $check_post;
    }
    
    function filter_query($val){
		$regex = '/[^A-Za-z0-9\-,: _.@%!{}()+=]/';
		return preg_replace($regex,"",$val);	
	}
    
    function hashkey($val){
		return hash("sha1",hash("sha256",$val.time()));
    }
    
    function encode($x) { 
		$x = base64_encode($x);
		$x = str_replace(array('+','/','='),array('$P','$S','$E'),$x);
		$return = '$F$B'.$x;
		return $return;
    }
    
    function decode($x) { 
		$x = str_replace('$F$B','',$x);
		$x = str_replace(array('$P','$S','$E'),array('+','/','='),$x);
		return base64_decode($x); 
		//return substr($y,4-strlen($y)); 
    }
    
    function encrypt($data, $type = false){
        $ci=& get_instance();
        $key = $ci->config->item('encryption_key');

		$l = strlen($key);
        if ($l < 16)
            $key = str_repeat($key, ceil(16/$l));

        if ($m = strlen($data)%8){
            $data .= str_repeat("\x00",  8 - $m);
		}
       
		$val = encode(openssl_encrypt($data, 'BF-ECB', $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING));
		
		return $type == 'X' ? bin2hex($val) : $val;
	}
	
	function decrypt($data, $type = false){
		$ci=& get_instance();
        $key = $ci->config->item('encryption_key');
		$l = strlen($key);
        if ($l < 16)
            $key = str_repeat($key, ceil(16/$l));

        
			$data = $type == 'X' ? hex2bin($data) : $data;
			$data = decode($data);
			$val = openssl_decrypt($data, 'BF-ECB', $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING);
		
        return trim($val);
		//return base64_decode($data);
	}
	
	function request_token($val = null){
		$val = is_null($val) ? session_id() : "";
		return (encrypt($val));
	}
	function access_token($val){
		return (decrypt($val));
	}

	function csrf($req = 'hash'){
		$ci=& get_instance();
		return ($req == 'hash') ? $ci->security->get_csrf_hash() : $ci->security->get_csrf_token_name();
		
	}