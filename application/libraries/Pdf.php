<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed'); 
require_once dirname(__FILE__,1).'/pdf/autoload.inc.php';
use Dompdf\Dompdf; 
use Dompdf\Options;
class Pdf extends Dompdf { 
    public function __construct() { 
        parent::__construct();
    }
	/*
	public function options(){
		return new Options();
	}
	
	public function dompdf($options){
		return new Dompdf($options);
	}
	*/
} 
?>