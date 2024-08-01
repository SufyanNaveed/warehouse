<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Raw_material_model extends CI_Model
{
	function __construct() {
		parent::__construct();
		
	}
	public function index(){
		
	}
	/* 
		return category id and name to use drop down manu 
	*/
	public function getCategory(){
		$this->db->select('category_id,category_name');
		$data =	$this->db->get_where('category',array('delete_status'=>0));
		return $data->result();
	}
	/* 
		return brand name to use drop down manu 
	*/
	public function getBrand(){
		$this->db->select('*');
		$data =	$this->db->get_where('brand',array('delete_status'=>0));
		return $data->result();
	}
	/* 
		return UQC name to use drop down manu 
	*/
	public function getUqc(){
		return $this->db->get_where('uqc',array('delete_status'=>0))->result();
	}
	/* 
		return subcategory id and name to use drop down manu 
	*/
	public function getSubcategory($id){
		$sql = "SELECT s.* FROM sub_category s INNER JOIN products p ON s.category_id = p.category_id where p.product_id = ?";
		$data = $this->db->query($sql,array($id));
		/*$this->db->select('sub_category_id,sub_category_name');
		$data =	$this->db->get('sub_category');*/
		return $data->result();
	}
	/* 
		return tax id and name to use drop down manu 
	*/
	// public function getTax(){
	// 	$this->db->select('tax_id,tax_name');
	// 	$data =	$this->db->get_where('tax',array('delete_status'=>0));
	// 	return $data->result();
	// }
	/*
		return sac data
	*/
	public function getSac(){
		
		return $this->db->get('sac')->result();
	}
	/*
		return hsn chapter
	*/
	public function getHsnChapter(){
		return $this->db->get('hsn_chapter')->result();
	}
	/*
		return hsn data
	*/
	public function getHsn(){
		return $this->db->get_where('hsn',array('chapter'=>1))->result();
	}
	/*

	*/
	public function getHsnData($id){
		return $this->db->get_where('hsn',array('chapter'=>$id))->result();
	}
	/* 
		return subcategory details when category change 
	*/
	public function selectSubcategory($id){
		/*$sql = "select * from sub_category where category_id = ?";
		$data = $this->db->query($sql,array($id));*/
		//$this->db->where('category_id',$id);
		$data = $this->db->get_where('sub_category',array('category_id'=>$id,'delete_status'=>0));
		return $data->result();
	}
	/* 
		return all product details to display list 
	*/
	public function getProducts(){

		$u_type=$this->session->userdata('type');
		
		
		if ($u_type=="manager" || $u_type == "sales_person") 
		{
			$user_id=$this->session->userdata('user_id');		

			$this->db->select('p.product_id')
					 ->from('warehouses_products p')
					 ->join('warehouse_management w','w.warehouse_id = p.warehouse_id')
					 ->where('w.warehouse_id',$warehouse_id);
			$data = $this->db->get()->result();
			/*echo $this->db->last_query();
			exit;*/
			foreach ($data as  $value) 
	        { 
	            $p_id[] = $value->product_id;             
	        }
	        if ($data) 
	        {
	        		$this->db->select('p.*,c.category_name')
					 ->from('raw_material p')
					 ->join('category c','c.category_id = p.category_id')
					 ->where_in('p.product_id',$p_id)
					 ->where('p.delete_status',0);
			return $this->db->get()->result();
			} 
			else{

				$this->db->select('p.*,c.category_name')
					 ->from('raw_material p')
					 ->join('category c','c.category_id = p.category_id')
					 ->where('p.product_id',0)
					 ->where('p.delete_status',0);
			return $this->db->get()->result();
			}       
						
		}
		else{
			$this->db->select('p.*,c.category_name')
					 ->from('raw_material p')
					 ->join('category c','c.category_id = p.category_id')
					 ->where('p.delete_status',0);
			return $this->db->get()->result();
		}
		
	}

	/* 
		return warehouse wise product details to display list 
	*/
	public function getProductsWarehouseWise($user_id){

		$u_type=$this->session->userdata('type');
		
		
		if ($u_type=="manager" || $u_type == "sales_person") 
		{
			$user_id=$this->session->userdata('user_id');		
			
			$this->db->select('p.*,ws.quantity as w_quantity,c.category_name')
					 ->from('raw_material p')
					 ->join('warehouses_products ws','ws.product_id = p.product_id')
					 ->join('warehouse_management wm','wm.warehouse_id = ws.warehouse_id')
					 ->join('category c','c.category_id = p.category_id')
					 ->where('wm.user_id',$user_id);
			return $this->db->get()->result();
			/*echo $this->db->last_query();
			exit;*/
			/*foreach ($data as  $value) 
	        { 
	            $p_id[] = $value->product_id;             
	        }*/
	        /*if ($data) 
	        {
        		$this->db->select('p.*,c.category_name, ws.quantity as warehouse_quantity')
					 ->from('products p')
					 ->join('category c','c.category_id = p.category_id')
					 ->join('warehouses_products ws','ws.product_id = p.product_id')
					 ->where_in('p.product_id',$p_id)
					 ->where('p.delete_status',0);
				echo '<pre>';
				print_r($this->db->get()->result());
				exit;
			} */
			/*else{

				$this->db->select('p.*,c.category_name')
					 ->from('products p')
					 ->join('category c','c.category_id = p.category_id')
					 ->where('p.product_id',0)
					 ->where('p.delete_status',0);
				return $this->db->get()->result();
			}       
			*/			
		}
		else{
			$this->db->select('p.*,c.category_name')
					 ->from('raw_material p')
					 ->join('category c','c.category_id = p.category_id')
					 ->where('p.delete_status',0);
			return $this->db->get()->result();
		}
		
	}
	/* 
		ckech product code already exist or not 
	*/
	function codeExist($key)
	{
		$sql = "select * from raw_material where code = ?";
		$query = $this->db->query($sql,array("code" => $key));
	    /*$this->db->where('code',$key);
	    $query = $this->db->get('products');*/
	    if ($query->num_rows() > 0){
	        return true;
	    }
	    else{
	        return false;
	    }
	}
	/* 
		add new product record in database 
	*/
	public function addModel($data){
		log_message('debug', print_r($data, true));
		if($this->db->insert('raw_material',$data)){
			return  $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	/* 
		return all product details when product edit 
	*/
	public function getRecord($data){
		//$this->db->where('product_id',$data);
		$this->db->select('raw_material.*, category.category_id,category.category_name, sub_category.sub_category_id, sub_category.sub_category_name')
				 ->from('raw_material')
				 ->join('category','raw_material.category_id = category.category_id')
				 ->join('sub_category','raw_material.subcategory_id = sub_category.sub_category_id','left')
				 ->where('raw_material.product_id',$data);
		$query = $this->db->get();
		if($query){
			return $query->result();
		}
		else{
			return FALSE;
		}
	}

	public function getRecordByProductId($product_id){
		//$this->db->where('product_id',$data);
		$this->db->select('raw_material.*, category.category_id,category.category_name, sub_category.sub_category_id, sub_category.sub_category_name')
				 ->from('raw_material')
				 ->join('category','raw_material.category_id = category.category_id')
				 ->join('sub_category','raw_material.subcategory_id = sub_category.sub_category_id','left')
				 ->where('raw_material.product_id',$product_id);
		$query = $this->db->get();
		if($query){
			return $query->row();
		}
		else{
			return FALSE;
		}
	}

	public function getRecordByCode($code){
		//$this->db->where('product_id',$data);
		$this->db->select('raw_material.*, category.category_id,category.category_name, sub_category.sub_category_id, sub_category.sub_category_name')
				 ->from('raw_material')
				 ->join('category','raw_material.category_id = category.category_id')
				 ->join('sub_category','raw_material.subcategory_id = sub_category.sub_category_id','left')
				 ->where('raw_material.code',$code);
		$query = $this->db->get();
		if($query){
			return $query->row();
		}
		else{
			return FALSE;
		}
	}
	/* 
		save edited product record in database  
	*/
	public function editModel($data,$id){
		$this->db->where('product_id',$id);
		if($this->db->update('raw_material',$data)){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/* 
		delete product record from database 
	*/
	public function deleteModel($id){
		/*$sql = "delete from products where product_id = ?";
		if($this->db->query($sql,array($id))){*/
		$this->db->where('product_id',$id);
		if($this->db->update('raw_material',array('delete_status'=>1))){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*
		return barcode images
	*/
	public function getBarcode(){
		return $this->db->get_where('raw_material',array('delete_status'=>0))->result();
	}
	/*
		insert data in warehouses products
	*/
	public function insertOpeningStock($id,$quantity){
		$warehouse = $this->db->get_where('warehouse',array('primary_warehouse'=>1))->row();
		if($warehouse!=null){
			$warehouse_id = $warehouse->warehouse_id;
			$this->db->insert('warehouses_products',array('product_id'=>$id,'warehouse_id'=>$warehouse_id,'quantity'=>$quantity));
		}

	}
	/*
		update data in warehouses products
	*/
	public function updateOpeningStock($id,$quantity,$old_qty){
		$warehouse = $this->db->select('w.warehouse_id,wp.quantity')
							  ->from('warehouse w')
							  ->join('warehouses_products wp','wp.warehouse_id = w.warehouse_id')
							  ->where('w.primary_warehouse',1)
							  ->get()
							  ->row();
		// echo '<pre>';
		// echo $warehouse;
		// exit;
		$product_quantity = $this->getRecordByProductId($id)->quantity;

		if($warehouse!=null){
			$warehouse_id = $warehouse->warehouse_id;
			$old_quantity = $warehouse->quantity;
			$this->db->where('product_id',$id);
			$this->db->where('warehouse_id',$warehouse_id);
			$new_qty = $old_quantity - $old_qty + $quantity;			
			$this->db->update('warehouses_products',array('quantity'=>$new_qty));

			$product_quantity = $product_quantity - $old_quantity + $new_qty;
			// echo $old_quantity;
			// echo $new_qty;
			// echo $product_quantity;
			// exit;
			$data = array(
							"quantity" => $product_quantity,
							"opening_stock" => $quantity
						);
			if($this->editModel($data,$id)){
				return true;
			}
		}

	}
}
?>