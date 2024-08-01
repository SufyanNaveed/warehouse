<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sales_return_model extends CI_Model
{
	function __construct() {
		parent::__construct();
		
	}
	/* 
		return all sales return details to display list 
	*/
	public function getSales()
	{

		if($this->session->userdata('type') == "admin")
		{
			$this->db->select('sr.*,b.first_name as biller_first_name, b.last_name as biller_last_name,c.first_name as customer_first_name, c.last_name as customer_last_name,ac.*')
			         ->from('sales_return sr')
			         ->join('users b','sr.biller_id=b.id')
		         	 ->join('users c','sr.customer_id=c.id')			         
		         	 ->join('account_receipts ac','ac.sales_return_id = sr.id')
			         ->where('sr.delete_status',0);
			         
			return $this->db->get()->result();
			
		}
		else if($this->session->userdata('type') == "sales_person" || $this->session->userdata('type') == "purchaser")
		{
			$data = $this->db->select('sr.*,bc.first_name as biller_first_name,bc.last_name as biller_last_name,cc.id as customer_id,cc.first_name as customer_first_name,cc.last_name as customer_last_name,ac.*')
			         ->from('sales_return sr')
			         ->join('users bc','bc.id = sr.biller_id')
			         ->join('users cc','cc.id = sr.customer_id')
			         ->join('account_receipts ac','ac.sales_return_id = sr.id')
			         ->where('bc.id',$this->session->userdata('user_id'))
			         ->where('sr.delete_status',0);
			return $data->get()->result();

		}
		// $this->db->select('sales_return.*,b.first_name as biller_first_name, b.last_name as biller_last_name,c.first_name as customer_first_name, c.last_name as customer_last_name')
		//          ->from('sales_return sr')
		//          ->join('users b','sr.biller_id=b.id')
		//          ->join('users c','sr.customer_id=c.id')
		//          ->where('sales_return.user',$this->session->userdata('user_id'))
		//          ->where('sales_return.delete_status',0);
		// return $this->db->get()->result();
	}

	public function getItems($id){
		return  $this->db->select('si.*,pr.name,pr.code,pr.hsn_sac_code,pr.unit,pr.size')
						 ->from('sale_return_items si')
						 ->join('sales_return s','si.sale_return_id = s.id')
						 ->join('products pr','si.product_id = pr.product_id')
						 ->where('si.sale_return_id',$id)
						 ->get()
						 ->result();
	}

	public function getReturnedItems($sales_id){

		return $this->db->select('sri.product_id as product_id,SUM(sri.quantity) as quantity')
						 ->from('sales_return sr')
						 ->join('sale_return_items sri','sri.sale_return_id = sr.id')
						 ->where('sr.sales_id',$sales_id)
						 ->group_by('sri.product_id')
						 ->get()
						 ->result();
		// echo '<pre>';
		// print_r($data);
		// exit;

	}

	/* 
		return warehouse detail use drop down 
	*/	
	public function getWarehouse(){
		if($this->session->userdata('type') == "admin"){
			$this->db->select('w.*')
		         ->from('warehouse w')
		         ->join('warehouse_management wm','wm.warehouse_id = w.warehouse_id')
		         ->join('users u','u.id = wm.user_id')
		         ->join('users_groups ug','ug.user_id = u.id')
		         ->where('w.delete_status',0);
			return $this->db->get()->result();
			//return $this->db->get_where('warehouse',array('delete_status'=>0))->result();
		}
		else if($this->session->userdata('type') == "sales_person"){
			$this->db->select('w.*')
					 ->from('warehouse w')
					 ->join('warehouse_management wm','wm.warehouse_id = w.warehouse_id')
					 ->join('biller b','b.user_id = wm.user_id')
					 ->where('b.user_id',$this->session->userdata('user_id'))
					 ->where('w.delete_status',0);
			return $this->db->get()->result();
		}
	}

	/* 
		return warehouse detail use drop down 
	*/	
	public function getSalesWarehouse(){
		// $users_id = $this->db->select('u.id')
		// 					 ->from('users u')
		// 					 ->join('warehouse_management wm','wm.user_id = u.id')
		// 					 ->join('users_groups ug','ug.user_id = u.id')
		// 					 ->join('groups g','g.id = ug.group_id')
		// 					 ->where('g.id !=',3)
		// 					 ->get()
		// 					 ->result();	
		
		// $user_id_array = array();
		
		// foreach($users_id as $user_id){
		// 	$user_id_array[] = $user_id->id;
		// }
		
		// echo '<pre>';
		// print_r($user_id_array);
		// exit;
		$purchase_user_id = $this->db->select('w.*,wm.user_id as user_id,cs.name as city_name, s.name as state_name, c.name as country_name,b.branch_name as branch_name, s.id as state_id, b.address as address')
				 ->from('warehouse w')
				 ->join('warehouse_management wm','wm.warehouse_id = w.warehouse_id')
				 ->join('branch b','b.branch_id = w.branch_id')
				 ->join('cities cs','cs.id = b.city_id')
				 ->join('states s','s.id = cs.state_id')
				 ->join('countries c','c.id = s.country_id')
				 ->join('users u','wm.user_id = u.id')
				 ->join('users_groups ug','ug.user_id = u.id')
				 ->join('groups g','g.id = ug.group_id')
				 ->where('g.id',3)
				 ->where('w.delete_status',0)
				 ->get()
				 ->result();
		return $purchase_user_id;
		
	}
	/* 
		return warehouse details available in warehouse products 
	*/
	public function getWarehouseProducts(){
		$this->db->select('warehouse.warehouse_id,warehouses_products.product_id,quantity')
		         ->from('warehouse')
		         ->join('warehouses_products','warehouse.warehouse_id = warehouses_products.warehouse_id');
		return $this->db->get()->result();
	}
	/* 
		return biller detail use drop down 
	*/
	public function getBiller(){
		return $this->db->get_where('biller',array('delete_status'=>0))->result();
	}
	/* 
		return customer detail use drop down 
	*/
	public function getCustomer(){
		return $this->db->get_where('customer',array('delete_status'=>0))->result();
	}
	/* 
		return discount detail use drop down 
	*/
	public function getDiscount(){
		return $this->db->get_where('discount',array('delete_status'=>0))->result();
	}
	/* 
		return tax detail use dynamic table
	*/
	public function getTax(){
		return $this->db->get_where('tax',array('delete_status'=>0))->result();
	}
	/* 
		return last purchase id 
	*/
	public function createReferenceNo(){
		$query = $this->db->query("SELECT * FROM sales_return ORDER BY id DESC LIMIT 1");
		$result = $query->result();
		return $result;
	}
	/* 
		return sales return record 
	*/
	public function getRecord($data){

		return $this->db->select('
									sr.*,
									ac.*,
									b.branch_name as branch_name,
									bu.address as biller_address,
									bu.address as biller_address,
									bu.phone as biller_mobile,
									bu.email as biller_email,
									ctu.name as biller_city,
									stu.name as biller_state,
									stu.name as biller_country,
									CONCAT(cu.first_name," ",cu.last_name) as customer_name,
									cu.address as customer_address,
									cu.phone as customer_mobile,
									ccu.name as customer_city,
									csu.name as customer_state,
									cscu.name as customer_country,
									cu.postal_code as customer_postal_code,
									cu.gstid as customer_gstid,
									cu.address as shipping_address,
									ccu.name as shipping_city,
									csu.name as shipping_state,
									cscu.name as shipping_country
								')
						->from('sales_return sr')
						->join('account_receipts ac','sr.id = ac.sales_return_id','left')	
						->join('users bu','bu.id = sr.biller_id','left')	
						->join('cities ctu','ctu.id = bu.city_id','left')	
						->join('states stu','stu.id = bu.state_id','left')	
						->join('countries cou','cou.id = bu.country_id','left')	
						->join('warehouse w','w.warehouse_id = sr.warehouse_id','left')
						->join('users cu','cu.id = sr.customer_id','left')
						->join('cities ccu','ccu.id = cu.city_id','left')
						->join('states csu','csu.id = cu.state_id','left')
						->join('countries cscu','cscu.id = cu.country_id','left')
						->join('branch b','b.branch_id = w.branch_id')
						->where('sr.id',$data)
						->get()
						->result();

		// $this->db->where('id',$data);
		// if($query = $this->db->get('sales_return')){
		// 	return $query->result();
		// }
		// else{
		// 	return FALSE;
		// }
	}

	public function get_record_with_invoice($id)
	{
		return $this->db->select('ac.*,sr.*,b.branch_name as branch_name')
						->from('account_receipts ac')	
						->join('sales_return sr','sr.id = ac.sales_return_id')
						->join('warehouse w','w.warehouse_id = sr.warehouse_id')
						->join('branch b','b.branch_id = w.branch_id')
						->where('ac.sales_return_id',$id)
						->get()
						->row();

		// $this->db->where('id',$data);
		// if($query = $this->db->get('sales_return')){
		// 	return $query->result();
		// }
		// else{
		// 	return FALSE;
		// }
	}

	public function get_invoice_record()
	{
		return $this->db->select('
									i.*,
									s.warehouse_id as warehouse, 
									s.shipping_state_id as customer_state_id,
									CONCAT(cu.first_name," ",cu.last_name) as customer_name
								')
						->from('invoice i')	
						->join('sales s', 's.sales_id = i.sales_id')
						->join('warehouse w','w.warehouse_id = s.warehouse_id')
						->join('users cu','s.customer_id = cu.id')
						
						// ->join('branch b','b.branch_id = w.branch_id')
						->get()
						->result();

		// $this->db->where('id',$data);
		// if($query = $this->db->get('sales_return')){
		// 	return $query->result();
		// }
		// else{
		// 	return FALSE;
		// }
	}

	public function get_single_invoice_record($sales_id)
	{
		return $this->db->select('
									i.*,
									s.warehouse_id as warehouse_id, 
									s.shipping_state_id as customer_state_id,
									CONCAT(cu.first_name," ",cu.last_name) as customer_name,
									s.biller_id as biller,
									bu.state_id as biller_state_id,
									s.customer_id as customer_id
								')
						->from('invoice i')	
						->join('sales s', 's.sales_id = i.sales_id')
						->join('warehouse w','w.warehouse_id = s.warehouse_id')
						->join('users cu','s.customer_id = cu.id')
						->join('users bu','s.biller_id = bu.id')
						->where('s.sales_id',$sales_id)
						// ->join('branch b','b.branch_id = w.branch_id')
						->get()
						->row();

		// $this->db->where('id',$data);
		// if($query = $this->db->get('sales_return')){
		// 	return $query->result();
		// }
		// else{
		// 	return FALSE;
		// }
	}
	/* 
		add new sales return record in database 
	*/
	public function add($data,$invoice){

		// $this->db->insert('sales_return',$data);
		// echo $this->db->last_query();
		// exit;
		if($this->db->insert('sales_return',$data)){
			$insert_id = $this->db->insert_id(); 
			$invoice['sales_return_id'] = $insert_id;
			
			if($this->db->insert('account_receipts',$invoice))
			{
				return $insert_id;
			}
			else
			{
				return FALSE;
			}
		}
		else{
			return FALSE;
		}
	}
	/* 
		return discount detail use drop down when discount change
	*/
	public function getDiscountAjax($id){
		$sql = "select * from discount where discount_id = ?";
		return $this->db->query($sql,array($id))->result();
	}
	/* 
		check product available in sales or not
	*/
	public function checkProductInSalesReturn($sale_return_id,$product_id){
		$sql = "select * from sale_return_items where sale_return_id = ? AND product_id = ? AND delete_status = 0";
		if($quantity = $this->db->query($sql,array($sale_return_id,$product_id))->num_rows() > 0){

			$sql = "select * from sale_return_items where sale_return_id = ? AND product_id = ? AND delete_status = 0";
			$quantity = $this->db->query($sql,array($sale_return_id,$product_id));

			return $quantity->row()->quantity;
		}
		else{
			return false;
		}
		
	}
	/* 
		update quantity in product table 
	*/
	public function updateQuantity($sale_return_id,$product_id,$warehouse_id,$quantity,$old_quantity,$data){
		/*$sql = "update sale_return_items set quantity = ? where sale_return_id = ? AND product_id = ?";
		$this->db->query($sql,array($quantity,$sale_return_id,$product_id));*/

		$where = "sale_return_id = $sale_return_id AND product_id = $product_id";
		$this->db->where($where);
		$this->db->update('sale_return_items',$data);
		
		$sql = "select * from warehouses_products where warehouse_id = ? AND product_id = ?";
		$warehouse_quantity = $this->db->query($sql,array($warehouse_id,$product_id))->row()->quantity;
		
		$wquantity = $warehouse_quantity + $quantity - $old_quantity;
		$sql = "update warehouses_products set quantity = ? where warehouse_id = ? AND product_id = ?";
		$this->db->query($sql,array($wquantity,$warehouse_id,$product_id));

		$sql = "select * from products where product_id = ?";
		$product_quantity = $this->db->query($sql,array($product_id))->row()->quantity;
		
		$pquantity = $product_quantity + $quantity - $old_quantity;
		$sql = "update products set quantity = ? where product_id = ?";
		$this->db->query($sql,array($pquantity,$product_id));
		
	}
	/* 
		check product available in warehouse or not 
	*/
	public function checkProductInWarehouse($product_id,$quantity,$warehouse_id){
		$sql = "select * from warehouses_products where product_id = ? AND warehouse_id = ?";
		$query = $this->db->query($sql,array($product_id,$warehouse_id));
		
		if($query->num_rows()>0){
			echo $warehouse_quantity = $query->row()->quantity;
			if($warehouse_quantity >= $quantity){
				echo $wquantity = $warehouse_quantity + $quantity;
				$sql = "update warehouses_products set quantity = ? where product_id = ? AND warehouse_id = ?";
				$this->db->query($sql,array($wquantity,$product_id,$warehouse_id));
				
				$sql = "select * from products where product_id = ?";
				$product_quantity = $this->db->query($sql,array($product_id))->row()->quantity;
				$pquantity = $product_quantity + $quantity ;	
				$sql = "update products set quantity = ? where product_id = ?";
				$this->db->query($sql,array($pquantity,$product_id));

			  	if($this->db->insert('sale_return_items',$data)){
					return true;
				}
				else{
					return false;
				}

			  	return true;
			}
		}
	}
	/*  
		add newly sales return items record in database 
	*/
	public function addSalesReturnItem($data,$product_id,$warehouse_id,$quantity){
		$sql = "select * from warehouses_products where warehouse_id = ? AND product_id = ?";
		$warehouse_quantity = $this->db->query($sql,array($warehouse_id,$product_id))->row()->quantity;
		
		$wquantity = $warehouse_quantity + $quantity;
		$sql = "update warehouses_products set quantity = ? where warehouse_id = ? AND product_id = ?";
		$this->db->query($sql,array($wquantity,$warehouse_id,$product_id));
		
		$sql = "select * from products where product_id = ?";
		$product_quantity = $this->db->query($sql,array($product_id))->row()->quantity;
		
		$pquantity = $product_quantity + $quantity ;	
		$sql = "update products set quantity = ? where product_id = ?";
		$this->db->query($sql,array($pquantity,$product_id));

		if($this->db->insert('sale_return_items',$data)){
			return true;
		}
		else{
			return false;
		}
	}
	/* 
		return sales return item data when edited 
	*/
	public function getSalesReturnItems($sales_return_id,$warehouse_id){
		$this->db->select('si.*,wp.quantity as warehouses_quantity,pr.product_id,pr.code,pr.name,pr.unit,pr.price,pr.cost,pr.hsn_sac_code')
				 ->from('sale_return_items si')
				 ->join('products pr','si.product_id = pr.product_id')
				 ->join('warehouses_products wp','wp.product_id = pr.product_id')
				 ->where('si.sale_return_id',$sales_return_id)
				 ->where('wp.warehouse_id',$warehouse_id)
				 ->where('si.delete_status',0);
		if($query = $this->db->get()){
			return $query->result();
		}
		else{
			return FALSE;
		}
	}
	/* 
		return  single product to add dynamic table 
	*/
	public function getProduct($product_id,$warehouse_id){
		return $this->db->select('p.product_id,code,unit,name,size,cost,price,alert_quantity,image,category_id,subcategory_id,wp.quantity,warehouse_id')
			 ->from('products p')
			 ->join('warehouses_products wp','p.product_id = wp.product_id')
			 ->where('wp.warehouse_id',$warehouse_id)
			 ->where('wp.product_id',$product_id)
		     ->get()
		     ->result();
	}
	/* 
		return  product list to add product 
	*/
	public function getProducts($warehouse_id){
		return  $this->db->select('p.*')
					 ->from('products p')
					 ->join('warehouses_products wp','p.product_id = wp.product_id')
					 ->where('wp.warehouse_id',$warehouse_id)
					 ->where('wp.quantity > 0')
				     ->get()
				     ->result();
	}
	/* 
		save edited record in database 
	*/
	public function editModel($id,$data,$invoice)
	{
		$this->db->where('id',$id);
		if($this->db->update('sales_return',$data))
		{
			$this->db->where('sales_return_id',$id);
			if($this->db->update('account_receipts',$invoice))
			{
				return true;
			}
			else
			{
				return false;	
			}	
		}
		else{
			return false;
		}
	}
	/* 
		delete old purchase item when edit purchse  
	*/
	public function deleteSalesReturnItems($sale_return_id,$product_id,$warehouse_id,$old_warehouse_id){

		$sql = "select * from sale_return_items where sale_return_id = ? AND product_id = ?";
		$delete_quantity = $this->db->query($sql,array($sale_return_id,$product_id))->row()->quantity;

		$sql = "select * from warehouses_products where warehouse_id = ? AND product_id = ?";
		$warehouse_quantity = $this->db->query($sql,array($warehouse_id,$product_id))->row()->quantity;

		$wquantity = $warehouse_quantity + $delete_quantity;
		$sql = "update warehouses_products set quantity = ? where warehouse_id = ? AND product_id = ?";
		$this->db->query($sql,array($wquantity,$warehouse_id,$product_id));

		$sql = "select * from products where product_id = ?";
		$product_quantity = $this->db->query($sql,array($product_id))->row()->quantity;

		$pquantity = $product_quantity + $delete_quantity;
		$sql = "update products set quantity = ? where product_id = ?";
		$this->db->query($sql,array($pquantity,$product_id));
		
		/*$sql = "delete from sale_return_items where sale_return_id = ? AND product_id = ?";
		if($this->db->query($sql,array($sale_return_id,$product_id))){*/
		$this->db->where('sale_return_id',$sale_return_id);
		$this->db->where('product_id',$product_id);
		if($this->db->update('sale_return_items',array('delete_status'=>1))){
			return true;
		}
		else{
			return false;
		}
	}
	/* 
		when warehouse change selected items is delete this function  
	*/
	public function changeWarehouseDeleteSalesReturnItems($sale_return_id,$product_id,$warehouse_id,$old_warehouse_id){

		$sql = "select * from sale_return_items where sale_return_id = ? AND product_id = ?";
		$delete_quantity = $this->db->query($sql,array($sale_return_id,$product_id))->row()->quantity;

		$sql = "select * from warehouses_products where warehouse_id = ? AND product_id = ?";
		$warehouse_quantity = $this->db->query($sql,array($old_warehouse_id,$product_id))->row()->quantity;

		$wquantity = $warehouse_quantity + $delete_quantity;
		$sql = "update warehouses_products set quantity = ? where warehouse_id = ? AND product_id = ?";
		$this->db->query($sql,array($wquantity,$old_warehouse_id,$product_id));

		$sql = "select * from products where product_id = ?";
		$product_quantity = $this->db->query($sql,array($product_id))->row()->quantity;

		$pquantity = $product_quantity + $delete_quantity;
		$sql = "update products set quantity = ? where product_id = ?";
		$this->db->query($sql,array($pquantity,$product_id));
		
		/*$sql = "delete from sale_return_items where sale_return_id = ? AND product_id = ?";
		if($this->db->query($sql,array($sale_return_id,$product_id))){*/
		$this->db->where('sale_return_id',$sale_return_id);
		$this->db->where('product_id',$product_id);
		if($this->db->update('sale_return_items',array('delete_status'=>1))){
			return true;
		}
		else{
			return false;
		}
	}
	/* 
		delete sales return  record in database 
	*/
	public function restoreQuantity($id,$product_id,$warehouse_id,$quantity)
	{
		$item_quantity = $this->db->get_where('sale_return_items',array('product_id'=>$product_id,"sale_return_id"=>$id))->row()->quantity;
		$warehouse_quantity = $this->db->get_where('warehouses_products',array('product_id'=>$product_id,"warehouse_id"=>$warehouse_id))->row()->quantity;
		$product_quantity = $this->db->get_where('products',array('product_id'=>$product_id))->row()->quantity;

		$this->db->where('warehouse_id',$warehouse_id);
		$this->db->where('product_id',$product_id);
		$this->db->update('warehouses_products',array('quantity'=>$warehouse_quantity-$item_quantity));
		$this->db->where('product_id',$product_id);
		$this->db->update('products',array('quantity'=>$product_quantity-$item_quantity));
		
	}

	public function getSalesReturnItemsForDelete($id)
	{
		return $this->db->get_where('sale_return_items',array('sale_return_id'=>$id))->result();
	}
	
	public function delete($id)
	{
		/*$sql = "delete from sales_return where id = ?";
		if($this->db->query($sql,array($id))){*/

		$this->db->where('id',$id);
		if($this->db->update('sales_return',array('delete_status'=>1)))
		{
			/*$sql = "delete from sale_return_items where sale_return_id = ?";
			if($this->db->query($sql,array($id))){
				return TRUE;
			}*/
			$this->db->where('sale_return_id',$id);
			return $this->db->update('sale_return_items',array('delete_status'=>1));
		}
		else{
			return FALSE;
		}
	}

	/* 
		get biller id from warehouse
	*/
	public function getBillerIdFromWarehouse($warehouse_id){
		// $data = $this->db->get_where('biller',array('warehouse_id'=>$warehouse_id))->result();
		$data = $this->db->select('wm.*')
						 ->from('warehouse w')
						 ->join('warehouse_management wm','wm.warehouse_id = w.warehouse_id')
						 ->where('w.warehouse_id',$warehouse_id)
						 ->get()
						 ->result();
		/*echo '<pre>';
		print_r($data);
		exit;*/
		if($data != null){
			return $data[0]->user_id;
		}else{
			return 0;
		}
	}

	/* 
		get biller state id from warehouse
	*/
	public function getBillerStateIdFromWarehouse($warehouse_id){
//		$data = $this->db->get_where('biller',array('warehouse_id'=>$warehouse_id))->result();

		$data = $this->db->select('b.state_id as state_id')
							->from('warehouse w')
							->join('branch b', 'b.branch_id = w.branch_id')
							->where('w.warehouse_id',$warehouse_id)
							->get()
							->result();
		if($data != null){
			return $data[0]->state_id;
		}else{
			$company = $this->db->select('cs.state_id')
							->from('company_settings cs')
							->get()
							->result();
			return $company[0]->state_id;
		}

		/*echo '<pre>';
		print_r($data);
		exit;*/
		if($data != null){
			return $data[0]->biller_id;
		}else{
			return 0;
		}
	}

	

	/* 
		get customer state id from customer
	*/
	public function getCustomerStateId($customer_id){
		return $this->db->get_where('users',array('id'=>$customer_id))->row()->state_id;
	}

}
?>