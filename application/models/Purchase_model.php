<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchase_model extends CI_Model
{
	public function index(){
		
	} 
	/* 
		return all purchase details to display list 
	*/
	public function getPurchase(){

		if($this->session->userdata('type') == "admin"){

			$this->db->select('p.*,s.*,ar.*')
				 ->from('purchases p')
				 ->join('users s','p.supplier_id = s.id')
				 ->join('account_receipts ar','ar.purchase_id = p.purchase_id')
				 ->order_by('p.purchase_id','DESC')
				 ->where('p.delete_status',0);
			$data = $this->db->get();
			// log_message('debug', print_r($data, true));
			return $data->result();

		}else if($this->session->userdata('type') == "purchaser"){
			$this->db->select('p.*,s.*,ar.*')
				 ->from('purchases p')
				 ->join('users s','p.supplier_id = s.id')
				 ->join('account_receipts ar','ar.purchase_id = p.purchase_id')
				 ->order_by('p.purchase_id','DESC')
				 ->where('p.user',$this->session->userdata('user_id'))
				 ->where('p.delete_status',0);
			$data = $this->db->get();
			// log_message('debug', print_r($data, true));
			return $data->result();

		}
		
	}
	/* 
		return warehouse detail use drop down 
	*/
	public function getWarehouse(){
		if($this->session->userdata('type') == "admin"){
			$this->db->select('w.*, b.*, cs.name as city_name, s.name as state_name, c.name as country_name, b.branch_name as branch_name ')
				 ->from('warehouse w')
				 ->join('warehouse_management wm','wm.warehouse_id = w.warehouse_id')
				 ->join('branch b','b.branch_id = w.branch_id')
				 ->join('cities cs','cs.id = b.city_id')
				 ->join('states s','s.id = cs.state_id')
				 ->join('countries c','c.id = s.country_id')
				 ->join('users u','wm.user_id = u.id')
				 ->where('w.delete_status',0);	
			return $this->db->get()->result();
		}
		else{
			$this->db->select('w.*')
				 ->from('warehouse w')
				 ->join('warehouse_management wm','wm.warehouse_id = w.warehouse_id')
				 ->where('wm.user_id',$this->session->userdata('user_id'))
				 ->where('w.delete_status',0);
			return $this->db->get()->result();
		}
	}

	public function checkPurchaserForPrimaryWarehouse(){

		$result = $this->db->select('*')
						 ->from('warehouse w')
						 ->join('warehouse_management wm','wm.warehouse_id = w.warehouse_id')
						 ->join('branch b','b.branch_id = w.branch_id')
						 ->join('users u','wm.user_id = u.id')
						 ->join('users_groups ug','ug.user_id = u.id')
						 ->join('groups g','g.id = ug.group_id')
						 ->where('w.primary_warehouse',1)
						 ->where('g.id',2)
						 ->where('w.delete_status',0)
						 ->get()
						 ->result();
		// echo '<pre>';
		// print_r($result);
		// exit;
		if($result != null){
			return true;
		}else{
			return false;
		}

	}



	public function getPurchaserWarehouse(){
	
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
				 ->where('g.id',2)
				 ->where('w.delete_status',0)
				 ->get()
				 ->result();
		// echo '<pre>';
		// print_r($purchase_user_id);
		// exit;
		return $purchase_user_id;
	
			// $this->db->select('w.*, b.*, cs.name as city_name, s.name as state_name, c.name as country_name, b.branch_name as branch_name,u.id as user_id')
			// 	 ->from('warehouse w')
			// 	 ->join('warehouse_management wm','wm.warehouse_id = w.warehouse_id')
			// 	 ->join('branch b','b.branch_id = w.branch_id')
			// 	 ->join('cities cs','cs.id = b.city_id')
			// 	 ->join('states s','s.id = cs.state_id')
			// 	 ->join('countries c','c.id = s.country_id')
			// 	 ->join('users u','wm.user_id = u.id')
			// 	 ->join('users_groups ug','ug.user_id = u.id')
			// 	 ->join('groups g','g.id = ug.group_id')
			// 	 ->where('g.id',2)
			// 	 ->where('w.delete_status',0);	
			// return $this->db->get()->result();
		
	}

	/* 
		return purchaser count detail use drop down 
	*/
	public function getPurchaserCount(){
		
			$this->db->select('count(*) as no_of_purchaser')
				 ->from('users u')
				 ->join('users_groups ug','ug.user_id = u.id')
				 ->join('groups g','g.id = ug.group_id')
				 ->where('g.id',2);
				 
			return $this->db->get()->row()->no_of_purchaser;
		
	}

	/* 
		return purchaser assign count detail use drop down 
	*/
	public function getPurchaserAssignmentCount(){
		
			$data = $this->db->select('*')
				 ->from('users u')
				 ->join('users_groups ug','ug.user_id = u.id')
				 ->join('warehouse_management wm','wm.user_id = ug.user_id')
				 ->join('groups g','g.id = ug.group_id')
				 ->where('g.id',2)
				 ->get()
				 ->result();

			if(sizeof($data)){
				return sizeof($data);
			}else{
				return 0;
			}
				 
			//return $this->db->get()->row()->no_of_purchaser_assignment;
		
	}

	/* 
		return warehouse detail use drop down 
	*/
	public function getWarehouesDetails($warehouse_id){
		
			$this->db->select('w.*,b.state_id as warehouse_state_id')
				 ->from('warehouse w')
				 ->join('warehouse_management wm','wm.warehouse_id = w.warehouse_id')
				 ->join('branch b','b.branch_id = w.branch_id')
				 ->where('w.warehouse_id',$warehouse_id)
				 ->where('w.delete_status',0);
			return $this->db->get()->result();
		
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
		return supplier detail use drop down 
	*/
	public function getSupplier(){
		return $this->db->get_where('suppliers',array('delete_status'=>0))->result();
	}
	/*
		generate invoive no
	*/
	public function generateInvoiceNo(){
		$query = $this->db->query("SELECT * FROM account_receipts ORDER BY receipt_voucher_no DESC LIMIT 1");
		$result = $query->result();
		if($result==null){
            $no = sprintf('%06d',intval(1));
        }
        else{
          foreach ($result as $value) {
            $no = sprintf('%06d',intval($value->receipt_voucher_no)+1); 
          }
        }
		return "PO-".$no;
	}

	public function edit_invoice_record($data,$id)
	{	
		$this->db->where('purchase_id',$id);
		$this->db->update('account_receipts',$data);
	}
	public function get_single_invoice_record($id)
	{	
		return $this->db->get_where("account_receipts",array('purchase_id'=>$id))->row();
		
	}
	/*	
		generate payment reference no
	*/
	public function generateReferenceNo(){
		$query = $this->db->query("SELECT * FROM account_payments ORDER BY payment_voucher_no DESC LIMIT 1");
		$result = $query->result();
		return $result;
	}
	/* 
		return last purchase id 
	*/
	public function createReferenceNo(){
		$query = $this->db->query("SELECT * FROM purchases ORDER BY purchase_id DESC LIMIT 1");
		$result = $query->result();
		return $result;
	}
	/* 
		return supplier name whose id get 
	*/
	public function getSupplierName($id){
		$sql = "select supplier_name from suppliers where supplier_id = ?";
		return $this->db->query($sql,array($id))->result();
	}
	/* 
		add new purchase record in database 
	*/
	public function addModel($data,$invoice){
		if($this->db->insert('purchases',$data)){
			$insert_id = $this->db->insert_id(); 
			$invoice['purchase_id'] = $insert_id;
			
 			/*echo '<pre>';
			print_r($data);
			print_r($invoice);
			exit;*/

			$this->db->insert('account_receipts',$invoice);
			return $insert_id;
		}
		else{
			return FALSE;
		}
	}
	/* 
		update quantity in product table 
	*/
	public function updateProductQuantity($product_id,$quantity){
		$sql = "select * from products where product_id = ?";
		$product_data = $this->db->query($sql,array($product_id));
		
		if($product_data->num_rows()>0){
			$p_data = $product_data->result();
			foreach ($p_data as $pvalue) {
			  $pquantity = $quantity + $pvalue->quantity;
			  $sql = "update products set quantity = ? where product_id = ?";
			  if($this->db->query($sql,array($pquantity,$product_id))){
			  	return true;
			  }	
			 	
			} 
		}
	}
	/* 
		add new record or update quantity in warehouse_products table 
	*/
	public function addProductInWarehouse($product_id,$quantity,$warehouse_id,$warehouse_data,$expiry = null,$s_price){
		$sql = "select * from warehouses_products where product_id = ? AND warehouse_id = ? AND batch = ?";
		$query = $this->db->query($sql,array($product_id,$warehouse_id,$warehouse_data['batch']));
		
		if($query->num_rows()>0)
		{
			$result 		= $query->result();
			foreach ($result as  $value) 
			{
				$wquantity 	= $quantity + $value->quantity;
				$sql 		= "update warehouses_products set quantity = ? where product_id = ? AND warehouse_id = ? AND batch = ?";
				if($this->db->query($sql,array($wquantity,$product_id,$warehouse_id,$warehouse_data['batch'])) && $this->updateProductQuantity($product_id,$quantity))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		}
		else
		{
			$warehouse_data['expiry'] 	= $expiry;
			$warehouse_data['price'] 	= $s_price;

			$sql = "insert into warehouses_products (product_id,warehouse_id,quantity,batch,expiry,price) values (?,?,?,?,?,?)";
			if($this->db->query($sql,$warehouse_data) && $this->updateProductQuantity($product_id,$quantity)){
				return true;
			}else{
				return false;
			}
		}

	}
	/*  
		add newly purchse items record in database 
	*/
	public function addPurchaseItem($data){
		if($this->db->insert('purchase_items',$data)){
			return true;
		}
		else{
			return false;
		}
	}

	public function editPurchaseItem($data,$purchase_item_id){
		$this->db->where("purchase_item_id",$purchase_item_id);
		if($this->db->update('purchase_items',$data)){
			return true;
		}
		else{
			return false;
		}
	}

	public function editPurchaseRecord($id,$data)
	{
		$this->db->where('purchase_id',$id);
		if($this->db->update('purchases',$data)){
			return true;
		}else{
			return false;
		}
		
	}
	/* 
		add or update purchase items in database 
	*/
	public function addUpdatePurchaseItem($purchase_id,$product_id,$warehouse_id,$quantity,$warehouse_data,$data){
		
		// echo '<pre>';
		// echo 'Warehouse Data';
		// print_r($warehouse_data);
		// echo 'Purchase item data';
		// print_r($data);
		// exit;

		$sql = "select * from purchase_items where purchase_id = ? AND product_id = ? AND batch = ?";

		$result = $this->db->query($sql,array($purchase_id,$product_id,$data['batch']));
		$order_status = $this->db->query($sql,array($purchase_id,$product_id,$data['batch']))->row()->order_status;
		$batch = $warehouse_data['batch'];
		
		if($result->num_rows()>0){

			$purchase_quantity = $result->row()->quantity;
			$where = "purchase_id = $purchase_id AND product_id = $product_id AND batch = '$batch'";
			$this->db->where($where);
			$this->db->update('purchase_items',$data);

			if($order_status == 1)
			{
				$sql = "select * from warehouses_products where warehouse_id = ? AND product_id = ? AND batch = ?";
				$warehouse_quantity = $this->db->query($sql,array($warehouse_id,$product_id,$data['batch']))->row()->quantity;
				
				$new_quantity = $warehouse_quantity + $quantity - $purchase_quantity;
				$sql = "update warehouses_products set quantity = ? where warehouse_id = ? AND product_id = ? AND batch = ?";
				$this->db->query($sql,array($new_quantity,$warehouse_id,$product_id,$data['batch']));
			
				$sql = "select * from products where product_id = ?";
				$product_quantity = $this->db->query($sql,array($product_id))->row()->quantity;
				
				$new_quantity = $product_quantity + $quantity - $purchase_quantity;
				$sql = "update products set quantity = ? where product_id = ?";
				$this->db->query($sql,array($new_quantity,$product_id));
			}
		}
		else{
			// if($this->addProductInWarehouse($product_id,$quantity,$warehouse_id,$warehouse_data) && $this->addPurchaseItem($data)){
			// 	return true;
			// }else{
			// 	return false;
			// }
			if($this->addPurchaseItem($data))
				return true;
			else
				return false;
			
		}

	}
	/*
		return products details
	*/
	public function getProduct(){
		return $this->db->get_where('products',array('delete_status'=>0))->result();
	}
	/* 
		return  product code or name it use to purchase table in web page 
	*/
	public function getProductAjax($id){
		$sql = "select * from products where product_id = ?";
		$data = $this->db->query($sql,array($id));
		return $data->result();
	}
	/* 
		return purchase record to edit 
	*/
	public function getRecord($id){
		
		$sql = "select * from purchases where purchase_id = ?";
		if($query = $this->db->query($sql,array($id))){
		
			return $query->result();
		}
		else{
			return FALSE;
		}
	}
	/* 
		return purchase items to purchase 
	*/
	public function getPurchaseItems($purchase_id){
		$this->db->select('pi.*,p.product_id,p.code,p.name,p.unit,pi.price,p.hsn_sac_code,p.details')
				 ->from('purchase_items pi')
				 ->join('products p','pi.product_id = p.product_id')
				 // ->join('warehouses_products wp','wp.product_id = p.product_id')
				 // ->where('wp.warehouse_id',$warehouse_id)
				 ->where('pi.purchase_id',$purchase_id)
				 ->where('pi.delete_status',0);
		if($query = $this->db->get())
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}

	public function getPurchaseItemsForDelete($id){
		return $this->db->get_where('purchase_items',array('purchase_id'=>$id))->result();
	}

	public function restoreQuantity($id,$product_id,$warehouse_id,$quantity,$batch){
		$item_quantity 		= $this->db->get_where('purchase_items',array('product_id'=>$product_id,"purchase_id"=>$id,"batch"=>$batch))->row()->quantity;
		$warehouse_quantity = $this->db->get_where('warehouses_products',array('product_id'=>$product_id,"warehouse_id"=>$warehouse_id,"batch"=>$batch))->row()->quantity;
		$product_quantity 	= $this->db->get_where('products',array('product_id'=>$product_id))->row()->quantity;

		$this->db->where('warehouse_id',$warehouse_id);
		$this->db->where('product_id',$product_id);
		$this->db->update('warehouses_products',array('quantity'=>$warehouse_quantity-$item_quantity));
		$this->db->where('product_id',$product_id);
		$this->db->update('products',array('quantity'=>$product_quantity-$item_quantity));
		
	}
	/* 
		save edited record in database Record	public function editPurchaseRecord($id,$data,$invoice)
	{
		$this->db->where('purchase_id',$id);
		if($this->db->update('purchases',$data))
		{
			$this->db->where('purchase_id',$id);
			if($this->db->update('account_receipts',$invoice))
			{
				return true;
			}
			else
			{
				return false;	
			}
		}
		else
		{
			return false;
		}
	}
	/* 
		delete purchase record in database 
	*/
	public function deleteModel($id){
		/*$sql = "delete from purchases where purchase_id = ?";
		if($this->db->query($sql,array($id))){*/
		$this->db->where('purchase_id',$id);
		if($this->db->update('purchases',array('delete_status'=>1))){
			/*$sql = "delete from purchase_items where purchase_id = ?";
			if($this->db->query($sql,array($id))){
				return TRUE;
			}*/
			$this->db->where('purchase_id',$id);
			$this->db->update('purchase_items',array('delete_status'=>1));
		}
		else{
			return FALSE;
		}
	}
	/* 
		delete old purchase item when edit purchse  
	*/
	public function deletePurchaseItems($purchase_id,$wp_product_id,$product_id,$warehouse_id){

		$sql = "select * from purchase_items where purchase_id = ? AND product_id = ?";
		// echo $purchase_id.'-'.$product_id;
		// exit;
		$delete_quantity = $this->db->query($sql,array($purchase_id,$product_id))->row()->quantity;
		$order_status = $this->db->query($sql,array($purchase_id,$product_id))->row()->order_status;
		
		if($order_status == 1)
		{
			$sql = "select * from warehouses_products where warehouse_id = ? AND product_id = ?";
			$warehouse_quantity = $this->db->query($sql,array($warehouse_id,$product_id))->row()->quantity;
		
			$wquantity = $warehouse_quantity - $delete_quantity;
			$sql = "update warehouses_products set quantity = ? where warehouse_id = ? AND product_id = ?";
			$this->db->query($sql,array($wquantity,$warehouse_id,$product_id));
			
			$sql = "select * from products where product_id = ?";
			$product_quantity = $this->db->query($sql,array($product_id))->row()->quantity;
		
			$pquantity = $product_quantity - $delete_quantity;
			$sql = "update products set quantity = ? where product_id = ?";
			$this->db->query($sql,array($pquantity,$product_id));
			
			/*$sql = "delete from purchase_items where purchase_id = ? AND product_id = ?";
			if($this->db->query($sql,array($purchase_id,$product_id))){*/
			$this->db->where('purchase_id',$purchase_id);
			$this->db->where('product_id',$product_id);
			if($this->db->update('purchase_items',array('delete_status'=>1))){
				return true;
			}
			else{
				return false;
			}
		}
	}

	public function deletePurchaseItemsByPurchaseId($purchase_id)
	{
		$this->db->where('purchase_id', $purchase_id);
    	if($this->db->delete('purchase_items'))
    	{
    		return TRUE;
    	}
    	else
    	{
    		return FALSE;
    	}
	}

	public function getProductIdFromWpid($wp_id)
	{
		return $this->db->get_where('warehouses_products',array('id'=>$id))->row()->product_id;
	}
	/*
		return purchase details
	*/
	public function getDetails($id){
			return	$this->db->select('p.*,
								  ar.receipt_voucher_no as invoice_id,
								  ar.invoice_no,
								  ar.paid_amount,
								  b.branch_name,
								  b.branch_id,
								  cb.name as branch_city,
								  b.address as branch_address,
								  u.address as supplier_address,
								  ct.name as supplier_city,
								  u.phone as supplier_mobile,
								  u.email as supplier_email,
								  u.state_id as supplier_state_id,
								  u.postal_code as supplier_postal_code,
								  u.company as supplier_company_name,
								  st.name as supplier_state_name,
								  cu.name as supplier_country_name,
								  u.gstid as supplier_gstin,
								  CONCAT(u.first_name," ", u.last_name) AS supplier_name,								  
								  ar.invoice_no as invoice_no,
								  p.supplier_id as supplier_id,
								  w.warehouse_name as warehouse_name
								')
						 ->from('purchases p')
						 ->join('account_receipts ar','ar.purchase_id = p.purchase_id')
						 ->join('warehouse w','w.warehouse_id = p.warehouse_id')
						 ->join('branch b','b.branch_id = w.branch_id')
						 ->join('users u','p.supplier_id = u.id')
						 // ->join('suppliers s','p.supplier_id = s.supplier_id')
						 ->join('cities ct','u.city_id = ct.id')
						 ->join('cities cb','b.city_id = cb.id')
						 ->join('states st','u.state_id = st.id')
						 ->join('countries cu','u.country_id = cu.id')
						 ->where('p.purchase_id',$id)
						 ->get()
						 ->result();
	}
	/*

	*/
	public function getDetailsPayment($id){

	}
	/*
		return company setting details
	*/
	public function getCompany(){
		return $this->db->select('cs.*,c.name as city_name,s.name as state_name,co.name as country_name')
		                ->from('company_settings cs')
		                ->join('cities c','cs.city_id = c.id')
		                ->join('states s','cs.state_id = s.id')
		                ->join('countries co','cs.country_id = co.id')
					    ->get()
					    ->result();
	}
	/*		
		return purchase items details
	*/
	public function getItems($id){
		return $this->db->select('pi.*,pr.name,pr.hsn_sac_code,pr.details,br.brand_name')
						 ->from('purchase_items pi')
						 ->join('products pr','pi.product_id = pr.product_id')
						 ->join('brand br','br.id = pi.brand','left')
						 ->where('pi.purchase_id',$id)
						 ->where('pi.delete_status',0)
						 ->get()
						 ->result();
	}
	/*
		return supplier details
	*/
	public function getSupplierEmail($id){

		return $this->db->select('*')
						 ->from('purchases p')
						 ->join('users s','p.supplier_id = s.id')
						 ->where('p.purchase_id',$id)
						 ->get()
						 ->result();
	}
	/*
		return discount value
	*/
	public function getDiscountValue($id){
		return $this->db->get_where('discount',array('discount_id'=>$id))->result();
	}
	/*
		return discount value
	*/
	public function getTaxValue($id){
		return $this->db->get_where('tax',array('tax_id'=>$id))->result();
	}
	/*
		return SMTP server Data
	*/
	public function getSmtpSetup(){
		return $this->db->get('email_setup')->row();
	} 
	/*
		add payment details
	*/
	public function addPayment($data){
		/*$sql = "INSERT INTO payment (sales_id,date,reference_no,amount,paying_by,bank_name,cheque_no,description) VALUES (?,?,?,?,?,?,?,?)";
		if($this->db->query($sql,$data)){*/
		if($this->db->insert('account_payments',$data)){
			$this->db->where('purchase_id',$data['purchase_id']);
			$this->db->update('account_receipts',array("paid_amount"=>$data['payment_amount']));
			return true;
		}else{
			return false;
		}
	}
	/*
		return ledger
	*/
	public function getLedger(){
		return $this->db->get('ledger')->result();
	}
	/*
		return ajax barcode product 
	*/
	public function getBarcodeProducts($term){
		return  $this->db->select('p.product_id,p.code,p.name')
				        ->from('products p')
				        // ->join('warehouses_products wp','wp.product_id = p.product_id')
				        ->like('p.code',$term)
				        ->or_like('p.name',$term)
				        ->where('delete_status',0)
				        // ->where('wp.warehouse_id',$warehouse_id)
				        ->get()
				        ->result_array();
	}
	/*
		return ajax name product 
	*/
	public function getNameProducts($term){
		return  $this->db->select('product_id,code,name')
				        ->from('products')
				        ->like('name',$term)
				        ->where('delete_status',0)
				        ->get()
				        ->result_array();
	}
	/*
		return product details
	*/
	public function getProductUseCode($product_id){
		 return $this->db->select('p.*')
						 ->from('products p')
						 // ->join('warehouses_products wp','wp.product_id = p.product_id')
						 ->where('p.product_id',$product_id)
						 // ->where('wp.warehouse_id',$warehouse_id)
					     ->get()
					     ->result();
	}
	


	/*
		return branch id of warehouse
	*/
	public function getBranchIdOfWarehouse($id){
		return $this->db->select('b.branch_id as branch_id')
						->from('warehouse w')
						->join('branch b','b.branch_id = w.branch_id')
						->where('w.warehouse_id',$id)
						->get()
						->row()->branch_id;
	}
	/*
		return biller details
	*/
	public function getSupplierState($id){
		return $this->db->get_where('users',array('id'=>$id))->row()->state_id;
		//return $this->db->get_where('SUP',array('biller_id' =>$id))->row()->state_id;
	}

	public function getPurchaseItem($purchase_item_id)
	{
		return $this->db->get_where('purchase_items', array('purchase_item_id'=>$purchase_item_id))->row();
	}

	public function getPurchaseItemsOrderStatus($purchase_id){

		$no_of_purchase_items = $this->db->select('COUNT(*) as no_of_data')
										 ->from('purchase_items pi')
										 ->where('pi.purchase_id',$purchase_id)
										 ->where('pi.delete_status',0)
										 ->get()
										 ->row()->no_of_data;

		$received_product = $this->db->select('COUNT(*) as no_of_data')
										 ->from('purchase_items pi')
										 ->where('pi.purchase_id',$purchase_id)
										 ->where('pi.order_status',1)
										 ->where('pi.delete_status',0)
										 ->get()
										 ->row()->no_of_data;

		// echo 'no of items '.$no_of_purchase_items;
		// echo '<br/>';
		// echo 'receved produt '.$received_product;
		

		// if($received_product == $no_of_purchase_items)
		// 	echo "1";
		// else if($received_product > 0 && $received_product < $no_of_purchase_items)
		// 	echo "2";
		// else
		// 	echo "0";

		// exit;
		if($received_product == $no_of_purchase_items)
			return 1;
		else if($received_product > 0 && $received_product < $no_of_purchase_items)
			return 2;
		else
			return 0;


		
	}	
}
?>