<?php
class Admin_common_model  extends CI_Model {
	public function __construct()
	{
	$this->load->database();
	}
	// function for all record start
	public function fetch_record($tbname)
	{
		$query = $this->db->get($tbname);
		return $query->result_array();	
	}
	// function for all record end

	// function for single record start
	public function fetch_recordbyid($tbname,$data)
	{
		$this->db->where($data);
		$query = $this->db->get($tbname);

		if($query->num_rows == 1)
		{
			$row = $query->row();
			return $row;
		}
		else{ 
		return false; 
		}
	}
	// function for single record end

	// function for insert record start
	public function insert_data($table,$data)
	{
		$que = $this->db->insert_string($table,$data);
		$this->db->query($que);
		$id=$this->db->insert_id();
		if($id) { return $id; } else { return false; }
	}
	// function for insert record end

	// function for single record start
	public function fetch_condrecord($tbname,$data)
	{
		$this->db->where($data);
		$query = $this->db->get($tbname);
		if($query->num_rows > 0)
		{
			$row = $query->result_array();
			return $row;
		}
		else { return false; }
	}
	// function for single record end

	// function for update record start
	public function update_data($table,$data,$where)
	{
		$this->db->where($where);
		$rs=$this->db->update($table,$data);
		if($rs) { return true; } else { return false; } 
	}
	// function for update record end

	// function for delete record start
	public function delete_data($table,$where)
	{
		$rs=$this->db->delete($table,$where);
		if($rs) { return true; } else { return false; } 
	}
	
	public function fetch_join_condi_records($firsttb,$secondtp,$fname,$sname,$wherefield,$val)
	{
	    $this->db->select($firsttb.'.*',$secondtp.'.*');
		$this->db->from($firsttb);
		$this->db->join($secondtp, "$secondtp.$sname = $firsttb.$fname"); 
		$this->db->where("$secondtp.$wherefield", $val);
		$query = $this->db->get();
		return $query->result();
	}
	public function fetch_join_allrecords($firsttb,$secondtp,$fname,$sname)
	{
	       $this->db->select('*');
		   $this->db->from($firsttb);
		   $this->db->join($secondtp, "$firsttb.$fname= $secondtp.$sname");
		   $query = $this->db->get();
		   return $query->result_array();
	}
	public function fetch_greaterdata($sql)
	{
		$query = $this->db->query($sql);
		if($query->num_rows > 0)
		{
			$row = $query->num_rows();
			return $row;
		}
		else { return false; }
	}
	
	
	
	public function fetch_total_record_wher($tbname,$data)
	{
		$this->db->select("*");
		$this->db->where($data);
		$this->db->from($tbname);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function fetch_total_record_like($tbname,$data,$data2)
	{
		$this->db->select("order_id,order_time,customer_id");
		$this->db->like($data,$data2);
		$this->db->from($tbname);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function fetch_total_record_like_count($tbname,$data,$data2)
	{
		$this->db->select("count($tbname.id) as counts");
		$this->db->like($data,$data2);
		$this->db->from($tbname);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function fetch_record_by_query($sql)
	{
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			$row = $query->result_array();
			return $row;
		}
		else { return false; }
	}
	/***************************************vinod******************************************/
	public function get_data_column_where($table,$column='',$where='',$orderby='',$rand='')
	{
		if($column !='')
		{
			$this->db->select($column);	
		}
		else
		{
			$this->db->select('*');
		}
		$this->db->from($table);
		if($where !='')
		{
			$this->db->where($where);	
		}
		if($orderby !='')
		{
			$this->db->order_by($orderby,'DESC');	
		}
                if($rand !='')
		{
			$this->db->order_by($orderby,'RANDOM');	
		}
		$que = $this->db->get();
		return $que->result();		
	}
	public function fetch_join_disrecords($firsttb,$secondtp,$fname,$sname)
    {
           $this->db->select('*');
           $this->db->from($firsttb);
           $this->db->join($secondtp, "$firsttb.$fname= $secondtp.$sname");
           $this->db->group_by("$secondtp.name");
           $query = $this->db->get();
           return $query->result_array();

    }
 	
	public function addQuant($cart_id,$qty){
		$this->db->set('quantity', '`quantity`+'.$qty.'', false);
		$this->db->where('id', $cart_id);
		$this->db->update('cart_items');
	}
	public function substractQuant($cart_id){
		$this->db->set('quantity', '`quantity`-1', false);
		$this->db->where('id', $cart_id);
		$this->db->where('quantity >', 1);
		$this->db->update('cart_items');
	}
	public function cartItems($customer_id ){
		$this->db->select('*');
		$this->db->from('products');
		$this->db->join('cart_items', 'products.id= cart_items.product_id');
		$this->db->where('cart_items.customer_id', $customer_id);
		$query = $this->db->get();
		// echo $sel="SELECT *, case products.discount_type when 'percent' then (products.price-(products.price*products.discount_price)/100) when 'flat' then (products.price-products.discount_price) else products.price End as amount FROM (`products`) JOIN `cart_items` ON `products`.`id`= `cart_items`.`product_id` WHERE `cart_items`.`customer_id` = $customer_id";
		// $query = $this->db->query($sel);
		return $query->result_array();
	}
	
	function check_salary($smonth,$year){
		$this->db->select('*');
		$this->db->from('employee_salary');
		$this->db->join('employees', 'employee_salary.emp_id= employees.id');
		$where= array(
			'employee_salary.salary_month' => $smonth,
			'employee_salary.salary_year' => $year
		);
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function get_item_report($date)
	{
		 $sql="select * from orders,order_items,product_qua,products,product_pack,unit where 
		 orders.order_id=order_items.order_id and order_items.qua_id=product_qua.qua_id 
		 and products.product_id=product_qua.product_id and product_pack.pack_id=product_qua.pack_id
		 and product_pack.unit_id=unit.id
		 and orders.order_time like('%$date%') and orders.status='pending'";	
		 $query =$this->db->query($sql);
		 return $query->result_array();
	}
	
	public function get_quantity_items()
	{
		 $sql="select * from products,product_qua,product_pack,unit
		   where product_pack.pack_id=product_qua.pack_id and
		   product_qua.product_id=products.product_id and product_pack.unit_id=unit.id order by products.product_id ASC";	
		 $query =$this->db->query($sql);
		 return $query->result_array();
	}
	
	public function get_order_items($order_id)
	{
		 $sql="select * from order_items,product_qua,product_pack,unit,products 
		   where order_items.qua_id=product_qua.qua_id and product_pack.pack_id=product_qua.pack_id and
		   product_qua.product_id=products.product_id and product_pack.unit_id=unit.id and  order_items.order_id='$order_id'";	
		 $query =$this->db->query($sql);
		 return $query->result_array();
	}
	
	public function fetch_wallet_total($where)
	{
		$this->db->select('SUM(amount) AS total');
		$this->db->from('customer_wallet');
		$this->db->where($where);                                  
		$getData = $this->db->get('');
		if($getData->num_rows() > 0)
		return $getData->result_array();
		else
		return 0;
	}
	
	public function get_products_quantity($pid,$limit='')
	{
		 $sql="SELECT * FROM `product_qua` INNER JOIN `product_pack` ON `product_qua`.`pack_id`= `product_pack`.`pack_id` 
		 		INNER JOIN `unit` on  `product_pack`.`unit_id`=`unit`.`id` WHERE  `product_qua`.`product_id`='$pid'";
				if($limit!='') {
				$sql=$sql." limit 1"; }
				
		 $query =$this->db->query($sql);
		 return $query->result_array();
	}
	
	public function get_cart_items($user_id)
	{
		 $sql="select * from cart_items,product_qua,product_pack,unit,products 
		   where cart_items.qua_id=product_qua.qua_id and product_pack.pack_id=product_qua.pack_id and
		   product_qua.product_id=products.product_id and product_pack.unit_id=unit.id and cart_items.user_id='$user_id'";	
		 $query =$this->db->query($sql);
		 return $query->result_array();
	}
	
	public function get_sql_record($sql)
	{
		 $query =$this->db->query($sql);
		 return $query->result_array();
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

        function generateRandomString($length) {
           $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
           $charactersLength = strlen($characters);
           $randomString = '';
           for ($i = 0; $i < $length; $i++) {
              $randomString .= $characters[rand(0, $charactersLength - 1)];
           }
           return $randomString;
        }
 
        
       public function get_where($table,$where){
			$data =	$this->db->where($where)->get($table);

			//echo $this->db->last_query();

	 		if($res = $data->result_array()){
	 			return $res;
			}else{
				return FALSE;
			}

			
		}
      
      
       public function get_like($where,$table)
       {
		$this->db->like($where); 
                $getData = $this->db->get($table);

		if($getData->num_rows() > 0)
		 return $getData->result_array();
		else
		return 0;
       }



}  