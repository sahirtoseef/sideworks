<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form_model extends CI_Model {

	public function __construct(){
		parent::__construct();
		$this->load->model('User_model', 'user', TRUE);
	}
	
	function getList(){
		return $this->db->select('`QsID` as id,`QsLabel`,`createdOn`,`createdBy`,`QsCondition`,`QsSlug`, (SELECT COUNT(*) from '.$this->db->dbprefix('questions').' where QsID = id) as questions, (SELECT COUNT(*) from '.$this->db->dbprefix('answerset').' where QsID = id) as submissions')->from('queset')->get()->result();
	}
	
	function getSubmissions($emp = false, $order = false){
		if($emp){
			$this->db->where('empID',$emp);
		}
		return $this->db->select('*')->from('answerset')->join('users','users.id = answerset.Uid', 'left')->join('employers','users.empID = employers.id','left')->order_by('answerset.created','desc')->get()->result();
		
	}
	
	function getSubmissionsReport($emp = false, $order = false){
		if($emp){
			$this->db->where('empID',$emp);
		}
		return $this->db->select('fname`, `name`, users.id as uid, empname, storeid, fname, lname, uemail, umobile, regdate, created, user_meta("addr",uid) as addr, user_meta("state",uid) as ustate, user_meta("city",uid) as ucity, user_meta("zip",uid) as uzip')->from('answerset')->join('users','users.id = answerset.Uid', 'left')->join('employers','users.empID = employers.id','left')->order_by('answerset.created','desc')->get()->result();
		
	}

	function getSubmissionsReportForExport($emp = false, $order = false){
		if($emp){
			$this->db->where('empID',$emp);
		}
		return $this->db->select('users.`fname`, users.`lname`, users.`urole`,employers.`empname`, users.`uemail`, users.`umobile`, answerset.`created`, answerset.`Result`')->from('answerset')->join('users','users.id = answerset.Uid', 'left')->join('employers','users.empID = employers.id','left')->order_by('answerset.created','desc')->get()->result();
		
	}
	
	function passed($emp = false){
		if($emp){
			$this->db->where('empID',$emp);
		}
	}
	
	function failed($emp = false){
		if($emp){
			$this->db->where('empID',$emp);
		}
	}
	
	function getPendingSubmissions($emp = false){
		if($emp){
			$this->db->where('empID',$emp);
		}
		return $this->db->select('*')->from('users')->where($this->db->dbprefix('users.id').' NOT IN (SELECT Uid from '.$this->db->dbprefix('answerset').')',NULL,false)->get()->result();
	}
	
	function create($label, $id = false){
		$data = array(
			'QsLabel'=>$label,
			'createdBy'=>$this->user->id,
			'QsSlug'=>strtolower(filter_slug($label))
		);
		if($id){
			$this->db->where('QsID',$id)->set($data)->update('queset');
		}else{
			$this->db->insert('queset', $data);
		}
		if($this->db->affected_rows()>0){
			return array('REL'=>'NEW_FORM_CREATED');
		}else{
			return array('ERR'=>'FAILED_TO_CREATE_FORM');
		}
	}
	
	function testResult($qsid, $id = false){
		$id = $id ? $id : $this->user->id;
		return $this->db->select('*')->from('answerset')->where('Uid',$id)->where('QsID',$qsid)->get()->row(); 		
	}
	
	
	
	function submit($qsparams, $qsans){
		$tested = $this->testResult($qsparams['QsID']);
		$id = $this->user->id;
		$name  = $this->user->info->fname." ".$this->user->info->lname;
		$email = $this->user->info->uemail;
		$points = (int)$qsparams['Result'];
		
    if($points >= 40 && $points < 50){
    	$testResult = "$name [$email] submitted test and has Medium Risk.";
    	$verdict = "May be at Risk";
    	$this->user->create_notification($id, 3, $testResult, $verdict);
     // echo '<span class="badge badge-warning">Medium Risk<span>';
    }elseif($points >= 50){
    	$testResult = "$name [$email] submitted test and is at high Risk.";
    	$verdict = "Failed / Not Cleared";
      $this->user->create_notification($id, 2, $testResult, $verdict);
    }else{
    	$testResult = "$name [$email] submitted test and is Safe.";
    	$verdict = "Passed / Cleared";
    	 $this->user->create_notification($id, 1, $testResult, $verdict);
    } 
                        
			
		if(is_null($tested)){
			$this->db->insert('answerset',$qsparams);
			$aid = $this->db->insert_id();
		}else{
			$aid = $tested->AsID;
			$this->db->where('AsID',$aid)->set($qsparams)->update('answerset');
			$this->db->delete('answers',array('AsID'=>$aid));
		}
		$answers = array();
		foreach($qsans as $k => $v){
			foreach($v as $vl){
				array_push($answers, array('Qid'=>$k, 'Answer'=> $vl, 'AsID'=>$aid));
			}
		}
		$this->db->insert_batch('answers',$answers);
		if($this->db->affected_rows()>0){
			// Notify employee and employer
			$employeeEmail = $email;
			$employerEmail = "";
            if (!empty($this->user->info->eid)) {
                $employer = $this->user->getEmpbyID($this->user->info->eid);
            }
            if (!empty($employer)) {
			    $employerUser = $this->user->getUserById($employer->user_id);
                if (!empty($employerUser)) {
			        $employerEmail = $employerUser->uemail;
                }

            }

			if ($this->notifyAboutTest($testResult, $verdict, $employeeEmail, $employerEmail) )
			    return array('RED'=>base_url());
		}else{
			return array('ERR'=>'FAILED');
		}
		
	}
	
	function delete($data){
		$n = 0;
		foreach($data as $k=>$v){
			$this->db->delete('queset',array('QsID'=>$v));
			$n++;
		}
		return $n++;
	}
	
	function getFormList($id = false){
		if($id){
			return $this->db->select('Qid as id, QsID, QLabel, Qtype, (SELECT count(*) from '.$this->db->dbprefix('qoptions').' where Qid = id) as options')->from('questions')->where('QsID',$id)->get()->result();
		}else{
			return false;
		}
	}
	
	function getForm($id = false){
		if($id){
			return $this->db->select('Qid as id, QsID, QLabel, Qtype, (SELECT count(*) from '.$this->db->dbprefix('qoptions').' where Qid = id) as options')->from('questions')->where('QsID',$id)->get()->row();
		}else{
			return false;
		}
	}
	
	function getQueAns($id){
		$ques = $this->db->select('*')->from('questions')->where('questions.QsID',$id)->get()->result();
		$options = array();
		foreach($ques as $que){
			$options[$que->Qid] = $this->db->select('*')->from('qoptions')->where('Qid',$que->Qid)->get()->result();
			
		}
	
		//var_dump($this->db->last_query());
		return array('questions'=>$ques, 'options'=>$options);
	}
	
	function getQue($id){
		return $this->db->select('Qid as id, QsID, QLabel, Qtype, (SELECT count(*) from '.$this->db->dbprefix('qoptions').' where Qid = id) as options')->from('questions')->where('Qid',$id)->get()->row();
		
	}
	
	function getOptions($id){
		return $this->db->select('*')->from('qoptions')->where('Qid',$id)->get()->result();
	}
	
	function saveQue($params){
		if($params['Qid']>0){
				$this->db->where('Qid',$params['Qid'])->set($params)->update('questions');
				if($this->db->affected_rows() > 0){
					return array('REL'=>'QUESTION_SAVED');
				}else{
					return array('ERR'=>'QUESTION_NOT_SAVED');
				}
		}else{
			$this->db->insert('questions', $params);
			if($this->db->affected_rows() > 0){
					return array('REL'=>'QUESTION_CREATED');
			}else{
				return array('ERR'=>'QUESTION_NOT_CREATED');
			}
		}
	}
	
	function deleteQue($data){
		$n = 0;
		foreach($data as $k=>$v){
			$this->db->delete('questions',array('Qid'=>$v));
			$n++;
		}
		return $n++;
	}
	
	function saveopt($params){
		if($params['QOID']>0){
				$this->db->where('QOID',$params['QOID'])->set($params)->update('qoptions');
				if($this->db->affected_rows() > 0){
					return array('REL'=>'OPTION_SAVED');
				}else{
					return array('ERR'=>'OPTION_NOT_SAVED');
				}
		}else{
			$this->db->insert('qoptions', $params);
			if($this->db->affected_rows() > 0){
					return array('REL'=>'OPTION_CREATED');
			}else{
				return array('ERR'=>'OPTION_NOT_CREATED');
			}
		}
	}
	
	function deleteopt($data){
		$n = 0;
		foreach($data as $k=>$v){
			$this->db->delete('qoptions',array('QOID'=>$v));
			$n++;
		}
		return $n++;
	}

	function notifyAboutTest($testResult, $verdict, $employeeEmail, $employerEmail)
	{
		$userId = $this->user->id;
		$htmlContent = '<table width="100%" cellpadding="0" cellspacing="0" border="0">
				  <tbody>
				    <tr>
				      <td>
				        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;max-width:600px;font-family: Arial, sans-serif" align="center">
				          <tbody>
				            <tr>
				              <td style="padding:0px 0px 0px 0px;color:#000000;text-align:left" bgcolor="#ffffff" width="100%" align="center">                                
				                <table border="0" cellpadding="0" cellspacing="0" width="100%">
				                  <tbody>
				                    <tr>
				                      <td bgcolor="#a50101" align="center">
				                        <p style="color: #fff; padding: 50px; font-size: 30px; text-transform: uppercase;letter-spacing: 2px;">well pass</p>
				                      </td>
				                    </tr>
				                  </tbody>
				                </table>
				                <table border="0" cellpadding="0" cellspacing="0" width="100%">
				                  <tbody>
				                    <tr>
				                      <td align="left" style="padding: 20px">
				                        <p style="color: #000;font-size: 18px; text-align:cemter;">Hi, '.$this->user->getInfo($userId, 'fname').'!</p>
				                        <p style="color: #000;font-size: 14px;line-height: 22px">Status: '. $verdict .'.</p>
				                        </p>
				                        <p style="color: #000;font-size: 14px;line-height: 22px">Test Result: '. $testResult .'.</p>
				                      </td>
				                    </tr>
				                  </tbody>
				                </table>
				                <table border="0" cellpadding="0" cellspacing="0" width="100%">
				                  <tbody>
				                    <tr>
				                      <td bgcolor="#f1f1f1" align="left" style="padding: 30px 30px 20px;font-size: 14px;line-height: 20px">
				                        <b>Note : </b>
				                        <ul style="padding-left: 20px">
				                          <li>Stay safe, Stay home</li>
				                          <li>Keep social distance</li>
				                          <li>Wash hands regularly</li>
				                          <li>Wear mask</li>
				                        </ul>
				                      </td>
				                    </tr>
				                  </tbody>
				                </table>
				                <table border="0" cellpadding="0" cellspacing="0" width="100%">
				                  <tbody>
				                    <tr>
				                      <td bgcolor="#a50101" align="center">
				                        <p style="color: #fff; padding: 30px; font-size: 20px;">Stay Safe, Stay Home</p>
				                      </td>
				                    </tr>
				                  </tbody>
				                </table>
				              </td>
				            </tr>
				          </tbody>
				        </table>
				      </td>
				    </tr>
				  </tbody>
				</table>';
    
	    $config['mailtype'] = 'html';
	    $this->email->initialize($config);
	    $this->email->to($employerEmail);
	    $this->email->cc($employeeEmail);
	    $this->email->from('web@wellpass.today','WellPass');
	    $this->email->subject('Test result');
	    $this->email->message($htmlContent);
	    if($this->email->send()){
	    	return TRUE;
	    }

        return FALSE;
	}

}