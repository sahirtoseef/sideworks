<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->uhash = $this->isBack();
        $this->user = false;
        $this->id = false;
        $this->info = false;
        $this->vcode = false;

        if ($this->uhash) {
            $this->user = $this->db->select('*')->from('users_log')->where('log_key', $this->uhash)->get()->row();
            $this->id = !is_null($this->user) ? $this->user->user_id : false;
            $this->info = $this->getInfo();
            if (is_null($this->info)) {
                $this->logout();
            }
            $this->checkSubscribtion();
        }
        //var_dump($this->isBack());
    }

    function getID($email) {
        $data = $this->db->select('id')->from('users')->where('uemail', $email)->get()->row();
        return !is_null($data) ? $data->id : false;
    }

    function getInfo($id = false, $key = false) {
        $id = $id ? $id : $this->id;
        $role_for_user = $this->db->select('urole')->from('users')->where('id', $id)->get()->row();
        $data = '';
        if ($role_for_user->urole == '2') {
            $this->db->select('u.fname,u.lname,u.uemail,u.umobile,u.regdate,u.ukey,u.ustatus,u.urole,u.empID, e.id as eid,e.empname as employer, e.account_subscription_status as subscription_status');
            $this->db->from('employers e');
            $this->db->join('users u', 'u.id = e.user_id', 'left');
            $this->db->where('e.user_id', $id);
            $data = $this->db->get()->row();
        } else {
            $data = $this->db->select('`fname`,`lname`,`uemail`,`umobile`,`regdate`,`ukey`,`ustatus`,`urole`,`empID` as eid, (SELECT `empname` from ' . $this->db->dbprefix("employers") . ' where id = eid) AS employer ')->from('users')->where('id', $id)->get()->row();
        }
        //$data = $this->db->select('`fname`,`lname`,`uemail`,`umobile`,`regdate`,`ukey`,`ustatus`,`urole`,`empID` as eid, (SELECT `empname` from '.$this->db->dbprefix("employers").' where id = eid) AS employer ')->from('users')->where('id',$id)->get()->row();

        if ($key) {
            return $data->$key;
        } else {
            return $data;
        }
    }

    function getUserbyToken($token) {
        return $this->db->select('`id` as uid,`fname`,`lname`,`uemail`,`umobile`,`regdate`,`ustatus`,`urole`,`empID` as eid, (SELECT `empname` from ' . $this->db->dbprefix("employers") . ' where id = eid) AS employer ')->from('users')->where('ukey', $token)->get()->row();
    }

    function getUserById($id) {
        return $this->db->select('* ')->from('users')->where('id', $id)->get()->row();
    }

    function isBack() {
        //	$bkt = hex2bin($this->input->cookie('bkt'));
        //$this->encodeUserData($bkt);
        return $this->decodeUserData();
    }

    function toEmployer($empname, $id = false, $utype = false) {
        $id = $id ? $id : $this->id;
        $user = $this->getInfo($id);
        $urole = $utype ? "urole = " . $utype . "," : '';
        $qry = "UPDATE " . $this->db->dbprefix('users') . " SET $urole  `empID` = (SELECT id from " . $this->db->dbprefix("employers") . " where empname='" . $empname . "') where id = $id";
        if ($this->db->simple_query($qry)) {
            $this->create_notification($id, 0, $user->fname . ' assigned to ' . $empname, "User Assigned to Employer / Store");
            return array("RES" => "EMPLOYER_SET");
        } else {
            return array("ERR" => "ERROR");
        }
    }

    function searchEmployer($form) {
        $empname = $form['empname'];
        $city_or_state = $form['city_or_state'];

        $rs = $this->db->select('*')->from('employers');

        if (!empty($empname)) {
            $rs = $rs->or_where("empname LIKE '%$empname%'");
        }
        if (!empty($city_or_state)) {
            $rs = $rs->or_where("estate LIKE '%$city_or_state%' OR ecity LIKE '%$city_or_state%'");
        }

        $rs = $rs->get()->result();

        return array("RESULT" => $rs);
    }

    function verificationLink($email = false) {
        //$ukey = $this->info->ukey;
        if ($email && !$this->vcode) {
            $ukey = $this->db->select('ukey')->from('users')->where('uemail', $email)->get()->row();
            if (is_null($ukey)) {
                return false;
            }
            $this->vcode = encrypt($ukey->ukey);
        }

        return base_url('verify?v=' . $this->vcode);
    }

    function resetlink($email) {
        $ukey = $this->db->select('ukey')->from('users')->where('uemail', $email)->get()->row();
        if (is_null($ukey)) {
            return false;
        }
        $this->vcode = encrypt($ukey->ukey);
        return base_url('setpassword?v=' . $this->vcode);
    }

    function resetpassword($pwd, $token) {
        $this->db->where('ukey', $token)->set('pwd', $this->password($pwd))->set('ukey', $this->xkey($token))->update('users');
        if ($this->db->affected_rows() > 0) {
            return array('RES' => 'PASSWORD_CHANGED: You can now login with your new password. <a href="' . base_url() . '" class="badge badge-success">click here to login</a>');
        } else {
            return array('ERR' => 'PASSWORD_NOT_CHANGED');
        }
    }

    function verified($token) {
        $token = decrypt($token);

        if (ctype_alnum($token)) {
            //return (array('RES'=>$token));
            $data = $this->db->where('ukey', $token)->select('*')->from('users')->get()->row();
            if (!is_null($data)) {
                return $data;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function verify($token) {
        $token = decrypt($token);

        if (ctype_alnum($token)) {
            $user = $this->getUserbyToken($token);
            //return (array('RES'=>$token));
            $this->db->where('ukey', $token)->set('ustatus', 1)->set('ukey', $this->xkey($token))->update('users');
            if ($this->db->affected_rows() > 0) {
                $this->create_notification($user->uid, 1, $user->fname . ' verified email address ' . $user->uemail, "User Verified Email Address");
                return array('RES' => "EMAIL_VERIFIED");
            } else {
                return array('ERR' => "INVALID");
            }
        } else {
            return array('ERR' => "INVALID");
        }
    }

    function getEmployeeList($id = false, $status = false) {
        $urole = '';
        if ($this->info) {
            $data = $this->info;
            $urole = $data->urole;
        }

        if ($id) {
            if ($urole == '2') {
                $this->db->where('employers.id', $id);
            } else {
                $this->db->where('users.id', $id);
            }
        }
        if ($status) {
            $this->db->where('employers.status', $status);
        }
        return $this->db->select('users.id as userid,`fname`,`lname`,`uemail`,`umobile`,`regdate`,`ukey`,`ustatus`,`urole`,`empID` as eid, empname, account_subscription_status')->from('users')->join('employers', 'employers.id = users.empID', 'left')->order_by('users.id', 'desc')->get()->result();
    }

    function getbillingList($id = false, $status = false) {
        $urole = '';
        if ($this->info) {
            $data = $this->info;
            $urole = $data->urole;
        }

        if ($id) {
            if ($urole == '2') {
                $this->db->where('employers.id', $id);
            } else {
                $this->db->where('users.id', $id);
            }
        }
        if ($status) {
            $this->db->where('employers.status', $status);
        }

        return $this->db->select('subscription_id', 'account_subscription_status')->from('employers')->get()->result();
    }

    function getEmployeeListForExport($id = false, $status = false) {
        if ($id) {
            $this->db->where('users.empID', $id);
        }
        if ($status) {
            $this->db->where('employers.status', $status);
        }
        return $this->db->select('`fname`,`lname`, `urole`, `empname`,  `uemail`,`umobile`, `addr`, `ecity`, `estate`, `zip`, `regdate`, `ustatus`')->from('users')->join('employers', 'employers.id = users.empID', 'left')->order_by('users.id', 'desc')->get()->result();
    }

    function hasEmployer() {
        return empty($this->info->eid) || is_null($this->info->eid) ? false : true;
    }

    function getEmployer() {
        return $this->db->select('*')->from('employers')->where('id', $this->info->eid)->get()->row();
    }

    function getStripeEmployerDetails() {
        $return = true;
        $employer = $this->db->select('*')->from('employers')->where('id', $this->info->eid)->get()->row();
        if ($employer->account_subscription_status == "" && $employer->account_subscription_status == null) {
            $return = false;
        }
        return $return;
    }

    function getEmployerList($status = false, $order = false) {
        if ($status) {
            $this->db->where('status', $status);
        }
        if (!$order) {
            $this->db->order_by('id', 'desc');
        }

        return $this->db->select('id as eid, empname, status, phone, addr, estate, ecity, zip, storeid, email, (SELECT COUNT(*) FROM ' . $this->db->dbprefix('users') . ' where empID = eid) as total')->from('employers')->get()->result();
    }

    function getEmployerListForExport($status = false, $order = false) {
        if ($status) {
            $this->db->where('status', $status);
        }
        if (!$order) {
            $this->db->order_by('id', 'desc');
        }

        return $this->db->select("`id AS eid`, `empname`, `storeid`, `phone`, `addr`, `estate`, `ecity`, `zip`, `email`, (SELECT COUNT(*) FROM " . $this->db->dbprefix('users') . " where empID = eid) as total, `status`")->from('employers')->get()->result();
    }

    function getEmp($empname) {
        return $this->db->select('*')->from('employers')->where('empname', $empname)->get()->row();
    }

    function getEmpbyID($id) {
        return $this->db->select('*')->from('employers')->where('id', $id)->get()->row();
    }

    function getEmpbyUserID($id) {
        return $this->db->select('*')->from('employers')->where('user_id', $id)->get()->row();
    }

    function checkSubscribtion() {
        if ($this->isLogin()){
            $employer = $this->getEmpbyUserID($this->isLogin());
            if ( isset( $employer->renewal_date) && date("Y-m-d") > $employer->renewal_date ) {
                $user = $this->db->select('*')->from('users')->where('id', $this->isLogin())->get()->row();
                if ( $user->urole == "2") {
                    if (uri_string() != "admin/buy_subscription" && !$_POST) {
                        if (!$employer->account_subscription_status) {
                            redirect('admin/buy_subscription/');
                        }
                    }
                }
            }
        }
    }

    function saveEmp($args, $id = false) {
        try {
            $status = isset($args['estatus']) ? $args['estatus'] : 1;
//            $store = isset($args['storeid']) ? $args['storeid'] : '';
            // $phone = isset($args['phone']) ? $args['phone'] : '';
            // New Start
            if (isset($args['mobile-hidden'])) {
                $phone = $args['phone'];
                $empname = $args['empname'];
                $state = isset($args['estate']) ? $args['estate'] : '';
                $city = isset($args['ecity']) ? $args['ecity'] : '';
                $zip = isset($args['zip']) ? $args['zip'] : '';
                $emp = $this->getEmpbyID($args['empid']);
                $email = $emp->email;
            } else {
                $phone = $args['phone1'] . $args['phone2'] . $args['phone3'];
                $empname = $args['emp'];
                $state = isset($args['state']) ? $args['state'] : '';
                $city = isset($args['city']) ? $args['city'] : '';
                $zip = isset($args['zip']) ? $args['zip'] : '';
                $email = isset($args['email']) ? $args['email'] : '';
                $emp = $this->db->select('*')->from('employers')->where('email', $email)->get()->row();
            }
            // New End
            $addr = isset($args['addr']) ? $args['addr'] : '';
//            $stripe_token = isset($args['stripetoken']) ? $args['stripetoken'] : '';

            $params = array(
                'empname' => $empname,
                'phone' => $phone,
                'addr' => $addr,
                'estate' => $state,
                'ecity' => $city,
                'zip' => $zip,
                'email' => $email,
                'status' => $status
            );



            $id = !is_null($emp) && !$id ? $emp->id : $id;

            if ($id) {
                $this->db->where('id', $id)->set($params)->update('employers');
                $this->create_notification($id, 0, "Changes applied to '" . $empname, "Changed Employer Details");
                if (isset($args['mobile-hidden'])) {
                    return array('REL' => $id ? 'CHANGES_SAVED' : 'NEW_EMPLOYER_ADDED');
                }
            } else {
                //~ print_r($params);die;
//                $plan_id = $this->db->select('*')->from('config')->where('id', 2)->get()->row();
                /* Create stripe charge payment */

//                $stripe_response = $this->create_customer($stripe_token, $params, $plan_id);
//                if ($stripe_response != '') {
//                    $stripe_arrray = json_decode($stripe_response, true);
//                    if ($stripe_arrray['status'] == 200) {
//                        $myarray = array(
//                            'customer_id' => $stripe_arrray['customer_id'],
//                            'subscription_id' => $stripe_arrray['subscription_id'],
//                            'account_subscription_status' => 'Active'
//                        );
//                        $finalarray = array_merge($params, $myarray);
                $date = strtotime("+7 day");
                $params['renewal_date'] = date('Y-m-d', $date);
                $store = mt_rand(10000000,99999999);
                $params['storeid'] = $store;
                $this->db->insert('employers', $params);
                $last_insert_employer_id = $this->db->insert_id();
                $rs = $this->registerEmployer([
                    'fname' => $args['emp'],
                    'email' => $args['email'],
                    'role' => 2,
                    'pwd' => $args['password'],
                        // 'empID'  => $last_insert_employer_id
                ]);

                // Update user_id in employer table
                $this->db->where('id', $last_insert_employer_id)->set(['user_id' => $rs['USER_ID']])->update('employers');
                $this->create_notification($id, 0, "New store / Employer was created with name '" . $args['emp'] . "'.", "New Employer Created");
                if ($this->db->affected_rows() > 0) {
                    return array('REL' => $id ? 'CHANGES_SAVED' : 'NEW_EMPLOYER_ADDED');
                } else {
                    return array('INF' => 'NO_CHANGES');
                }
//                    } else {
//                        return array('INF' => 'NO_CHANGES');
//                    }
//                } else {
//                    return array('INF' => 'NO_CHANGES');
//                }
            }
        } catch (Exception $ex) {
            print_r($ex->getMessage());
        }
    }

    /* Update plan */

    function update_plan_data($stripe_token, $id = false) {
        if ($stripe_token != '') {
            $plan_id = $this->db->select('*')->from('config')->where('id', 2)->get()->row();
            //~ print_r($this->info);
            //~ die;
            $params = array(
                'email' => $this->info->uemail,
                'empname' => $this->info->employer,
            );
            /* Create stripe charge payment */

            try {
                // Use Stripe's library to make requests...

                $stripe_res = $this->create_customer($stripe_token, $params, $plan_id);
                $stripe_arrray = json_decode($stripe_res, true);
                $date = strtotime("+1 months");
                $renewal_date = date('Y-m-d', $date);
                if ($stripe_arrray['status'] == 200) {
                    $myarray = array(
                        'customer_id' => $stripe_arrray['customer_id'],
                        'subscription_id' => $stripe_arrray['subscription_id'],
                        'account_subscription_status' => 'Active',
                        'renewal_date' => $renewal_date
                    );

                    $this->db->where('id', $this->info->eid)->update("employers", $myarray);
                    $last_insert_employer_id = $this->db->insert_id();

                    // Update user_id in employer table
                    $this->create_notification($this->info->eid, 0, "Upgraded Plan'" . $params['empname'] . "'.", "Upgraded Plan");
                    if ($this->db->affected_rows() > 0) {
                        return array('REL' => $this->info->eid ? 'CHANGES_SAVED' : 'NEW_EMPLOYER_ADDED');
                    } else {
                        return array('INF' => 'NO_CHANGES');
                    }
                } else {
                    return array('INF' => 'NO_CHANGES');
                }
            } catch (\Stripe\Exception\CardException $e) {
                return array('INF' => $e->getError()->message);
            } catch (\Stripe\Exception\RateLimitException $e) {
                return array('INF' => 'Too many requests made to the API too quickly');
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                return array('INF' => "Invalid parameters were supplied to Stripe's API");
            } catch (\Stripe\Exception\AuthenticationException $e) {
                return array('INF' => "Authentication with Stripe's API failed");
            } catch (\Stripe\Exception\ApiConnectionException $e) {
                return array('INF' => 'Network communication with Stripe failed');
            } catch (\Stripe\Exception\ApiErrorException $e) {
                return array('INF' => 'Display a very generic error to the user, and maybe send');
            } catch (Exception $e) {
                return array('INF' => 'Something went wrong...!');
            }
        }
    }

    /* Stripe functions */

    function create_customer($stripe_token, $params, $plan_id) {
        require_once(APPPATH . 'libraries/stripe-php-master/init.php');
        $response_id = '';
        $stripe = \Stripe\Stripe::setApiKey(STRIPE_SECRET);
        try {
            $res = \Stripe\Customer::create(array(
                        'source' => $stripe_token,
                        'email' => $params['email'],
                        'name' => $params['empname'],
                        'description' => "Created for regular payment"
            ));

            if ($res) {
                $subscription = \Stripe\Subscription::create(array(
                            "customer" => $res->id,
                            "items" => array(
                                array(
                                    "plan" => $plan_id->value,
                                ),
                            ),
                ));

                if ($subscription) {
                    $array = array(
                        'status' => 200,
                        'subscription_id' => $subscription->id,
                        'customer_id' => $res->id
                    );
                    return json_encode($array);
                } else {
                    $array = array(
                        'status' => 404,
                        'message' => 'Error in creating subscription'
                    );
                    return json_encode($array);
                }
            } else {
                $array = array(
                    'status' => 404,
                    'message' => 'Error in creating customer'
                );
                return json_encode($array);
            }
        } catch (Exception $e) {
            $array = array(
                'status' => 404,
                'message' => $e->getMessage()
            );
            return json_encode($array);
        }
    }

    /* Cancel Subscription */

    function cancelSubscription($id = false) {
        require_once(APPPATH . 'libraries/stripe-php-master/init.php');
        try {
            $stripe = new \Stripe\StripeClient(STRIPE_SECRET);
            $emp = $this->db->select('*')->from('employers')->where('id', $this->info->eid)->get()->row();

            $res = $stripe->subscriptions->cancel(
                    $emp->subscription_id,
                    []
            );
            $this->db->where('id', $this->info->eid)->set(['account_subscription_status' => ''])->update('employers');
            $array = array(
                'status' => 200,
                'message' => 'Updated Successfully'
            );
            return json_encode($array);
        } catch (Exception $e) {
            $array = array(
                'status' => 404,
                'message' => $e->getMessage()
            );
            return json_encode($array);
        }
    }

    function createEmp($empname, $id = false) {
        $id = $id ? $id : $this->id;
        $save = $this->saveEmp(array('empname' => $empname));
        if (array_key_first($save) !== 'ERR') {
            return $this->toEmployer($empname, $id, 2);
        } else {
            return (array('ERR' => 'EMPLOYER_NOT_CREATED'));
        }
    }

    public function isLogin() {
        return $this->id;
    }

    function isAdmin() {
        return $this->info && ($this->info->urole == 1 || $this->info->urole == 2);
    }

    function isSuperAdmin() {
        return $this->info && ($this->info->urole == 1);
    }

    function isEmployer() {
        return $this->info && ($this->info->urole == 2);
    }

    function isSubscriber() {
        return ($this->info->urole == 3);
    }

    function password($val) {
        return hash("sha1", hash("sha256", $val));
    }

    function xkey($val) {
        return hash("sha1", hash("sha256", $val . time()));
    }

    function encodeUserData($log_key) {
        $_SESSION["udata"] = bin2hex(encrypt($log_key));
    }

    function decodeUserData() {
        return isset($_SESSION['udata']) ? decrypt(hex2bin($_SESSION['udata'])) : false;
    }

    function delete($data) {
        $n = 0;
        foreach ($data as $k => $v) {
            $u = $this->getInfo($v);

            $n = $u->fname;
            $e = $u->uemail;
            $r = userRole($u->urole);
            $this->create_notification($v, 2, "$n [$e] had role: $r was deleted", "User Deleted");
            $this->db->delete('users', array('id' => $v));
            $n++;
        }
        return $n++;
    }

    function deleteEmp($data) {
        $n = 0;

        foreach ($data as $k => $v) {
            $emp = $this->getEmpbyID($v);
            $this->create_notification($v, 2, $emp->empname . " was deleted", "Employer Deleted");
            $this->db->delete('employers', array('id' => $v));
            $n++;
        }
        return $n++;
    }

    function save_meta($metakey, $metaval, $id = null, $lock = false) {
        $id = is_null($id) ? $this->id : $id;
        $id = filter_num($id);
        $metakey = filter_query($metakey);
        $metaval = htmlentities($metaval);
        $meta = $this->db->select('*')->from('users_meta')->where(array('uid' => $id, "ukey" => $metakey))->get()->row();
        if (!is_null($meta) && !$lock) {
            $this->db->set(array("uval" => $metaval))->where(array('uid' => $id, "ukey" => $metakey))->update('users_meta');
        } else {
            $this->db->insert('users_meta', array('uid' => $id, "ukey" => $metakey, "uval" => $metaval));
        }
        return $this->db->affected_rows();
    }

    function get_meta($mkey, $uid = false) {
        $uid = !$uid ? $this->id : $uid;
        $data = $this->db->select('uval')->from('users_meta')->where(array('uid' => $uid, 'ukey' => $mkey))->get()->row();
        return is_null($data) ? '' : $data->uval;
    }

    function login($args) {
        $user = isset($args['user']) ? $args['user'] : "";
        $pwd = isset($args['pwd']) ? $args['pwd'] : "";
        $remember = isset($args['remember']) && ($args['remember'] == 'true' || $args['remember'] == '1' || $args['remember'] == 'on') ? true : false;

        $pwd = $this->password($pwd);
        $role = $args['role'];

        $user = $this->db->select("id,ustatus,urole,pwd,ukey")->from("users")->where("uemail", $user)->get()->row();
        if ($user) {
            if ($pwd == $user->pwd) {
                if ($user->ustatus == "1") {
                    $log_key = $this->xkey($user->id . $user->ukey);
                    ////$_SESSION["token"] = $log_key;
                    $device = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "Unknown";
                    if (defined('MULTI_LOGIN') && !MULTI_LOGIN) {
                        $this->db->delete("users_log", array("user_id", filter_num($user->id)));
                    }
                    $log = $this->db->insert("users_log", array("user_id" => $user->id, "log_device" => $device, "log_ip" => client_ip(), "log_key" => $log_key));
                    if ($remember) {
                        $this->encodeUserData($log_key);
                        setcookie("bkt", bin2hex($log_key), 2147483647);
                    } else {
                        $this->encodeUserData($log_key);
                    }
                    $response = array("RES" => "LOGGEDIN");
                } elseif ($user->ustatus == "0") {
                    $response = array("ERR" => "ACCOUNT_IS_NOT_VERIFIED");
                } else {
                    $response = array("ERR" => "INVALID_USER_ACCOUNT_OR_BANNED");
                }
            } else {
                $response = array("ERR" => "INVALID_LOGIN_CREDENTIALS");
            }
        } else {
            $response = array("ERR" => "USER_ACCOUNT_UNAVAILABLE");
        }

        return $response;
    }

    function hasMeta($id = false) {
        $id = !$id ? $this->id : $id;
        $id = filter_num($id);
        $meta = $this->db->select('*')->from('users_meta')->where(array('uid' => $id))->get()->row();
        return !is_null($meta) ? true : false;
    }

    public function saveProfile($args, $meta = array(), $id = false) {
        $id = !$id ? $this->id : $id;
        $email = isset($args['email']) ? $args['email'] : $this->info->uemail;
        $mobile = isset($args['mobile']) ? $args['mobile'] : $this->info->umobile;
        $role = isset($args['role']) ? (int) $args['role'] : $this->info->urole;

        $fname = isset($args['fname']) ? $args['fname'] : $this->info->fname;
        $lname = isset($args['lname']) ? $args['lname'] : $this->info->lname;
        $empid = isset($args['empid']) ? (int) $args['empid'] : $this->info->eid;
        $key = $this->password($email . time());

        $arg = array("fname" => $fname, "lname" => $lname, "uemail" => $email, 'umobile' => $mobile, "ukey" => $key, "urole" => $role, "empID" => $empid);
        //var_dump($arg);
        //var_dump($id);
        $this->db->where('id', $id)->update("users", $arg);
        $affected = 0;
        if ($meta && !empty($meta) && is_array($meta)) {
            foreach ($meta as $mkey => $mval) {
                $affected += $this->save_meta($mkey, $mval, $id);
            }
        }

        $this->create_notification($id, 0, "$fname [$email]'s profile was updated", "Profile Updated");
        $response = array("RES" => "CHANGES_SAVED");

        return $response;
    }

    public function register($args, $meta = array()) {
        $email = isset($args['email']) ? $args['email'] : "";
        // $mobile = isset($args['mobile'])?$args['mobile']:"";
        // New start
        $mobile = $args['mobile1'] . $args['mobile2'] . $args['mobile3'];
        // New end
        $pwd = isset($args['pwd']) ? $this->password($args['pwd']) : "";
        $role = isset($args['role']) ? (int) $args['role'] : 3;
        $status = isset($args['status']) ? (int) $args['status'] : 0;
        $empid = isset($args['empid']) ? (int) $args['empid'] : 0;
        $fname = $args['fname'];
        $lname = $args['lname'];
        $key = $this->password($email . time());

        $user = $this->db->select('*')->from("users")->where(array("uemail" => $email))->get()->row_array();
        if ($user) {
            $response = array("ERR" => "ACCOUNT_ALREADY_EXISTS");
        } else {
            $arg = array("fname" => $fname, "lname" => $lname, "uemail" => $email, 'umobile' => $mobile, "pwd" => $pwd, "ukey" => $key, "ustatus" => $status, "urole" => $role, "empID" => $empid);
            if ($this->config->item('user_can_register')) {
                $reg = $this->db->insert("users", $arg);
                $id = $this->db->insert_id();
                if (!empty($meta)) {
                    foreach ($meta as $mkey => $mval) {
                        $this->save_meta($mkey, $mval, $id);
                    }
                }
                $this->create_notification($id, 0, "$fname's account was created with email [$email] has role " . userRole($role), "New Account Created");
                $this->vcode = encrypt($key);
                $response = array("RES" => "ACCOUNT_CREATED");
            } else {
                $response = array("ERR" => "REGISTRATION_DISABLED");
            }
        }

        return $response;
    }

    public function registerEmployer($args, $meta = array()) {
        $email = isset($args['email']) ? $args['email'] : "";
        $mobile = isset($args['mobile']) ? $args['mobile'] : "";
        $pwd = isset($args['pwd']) ? $this->password($args['pwd']) : "";
        $role = isset($args['role']) ? (int) $args['role'] : 3;
        $status = isset($args['status']) ? (int) $args['status'] : 0;
        $empid = isset($args['empID']) ? (int) $args['empID'] : 0;
        $fname = isset($args['fname']) ? $args['fname'] : "";
        $lname = isset($args['lname']) ? $args['lname'] : "";
        $key = $this->password($email . time());

        $user = $this->db->select('*')->from("users")->where(array("uemail" => $email))->get()->row_array();
        if ($user) {
            $response = array("ERR" => "ACCOUNT_ALREADY_EXISTS");
        } else {
            $arg = array("fname" => $fname, "lname" => $lname, "uemail" => $email, 'umobile' => $mobile, "pwd" => $pwd, "ukey" => $key, "ustatus" => $status, "urole" => $role, "empID" => $empid);
            if ($this->config->item('user_can_register')) {
                $reg = $this->db->insert("users", $arg);
                $id = $this->db->insert_id();
                if (!empty($meta)) {
                    foreach ($meta as $mkey => $mval) {
                        $this->save_meta($mkey, $mval, $id);
                    }
                }
                $this->create_notification($id, 0, "$fname's account was created with email [$email] has role " . userRole($role), "New Account Created");
                $this->vcode = encrypt($key);
                $response = array("RES" => "ACCOUNT_CREATED", "USER_ID" => $id);
            } else {
                $response = array("ERR" => "REGISTRATION_DISABLED");
            }
        }

        return $response;
    }

    function logout($url = '', $users = false) {
        if (!$users) {
            $users = array('log_key' => $this->uhash);
        }
        $this->db->delete('users_log', $users);
        unset($_SESSION["udata"]);
        redirect($url);
    }

    function create_notification($uid, $ntype, $ntext, $nlabel) {

        $this->db->insert('notifications', array('uid' => $uid, 'ntype' => $ntype, 'nlabel' => $nlabel, 'ntext' => $ntext, 'byuid' => $this->id));
    }

    function getNotifications($emp = false, $limit = 10) {
        if ($emp) {
            $this->db->where('empID', $emp);
        }
        return $this->db->select('*')->from('notifications')->join('users', 'users.id = notifications.uid', 'left')->order_by('notifications.id', 'desc')->limit($limit)->get()->result();
    }

    public function getList() {
        return $this->db->select('*')->from('users')->get()->result();
    }

    function getUserDetails() {
        $response = array();
        $this->db->select('*');
        $q = $this->db->get('employers');
        $response = $q->result_array();
        return $response;
    }

    public function get_all_est_data() {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('employers', 'users.id=employers.id', 'left');
        //$this->db->join('employers', 'users.empID=employers.id', 'left'); 
        $query = $this->db->get();
        return $query->result();
    }

}
