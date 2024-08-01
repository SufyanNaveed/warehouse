<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Warehouse_product_model extends CI_Model
{
	function __construct() 
	{
		parent::__construct();
	}
	
	public function add_record($data)
	{

		if($this->db->insert('warehouses_products',$data))
		{
			return  $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}

	// public function edit_record($data,$warehouse_id,$supplier_id,$product_id)
	public function edit_record($data,$warehouse_id,$product_id)	
	{
		$this->db->where('warehouse_id',$warehouse_id);
		// $this->db->where('supplier_id',$supplier_id);
		$this->db->where('product_id',$product_id);
		
		if($this->db->update('warehouses_products',$data))
		{
			
			return true;
		}
		else
		{	
			
			return false;
		}
	}
	
	// public function get_product_quantity_by_warehouse($warehouse_id, $product_id, $supplier_id)
	public function get_product_quantity_by_warehouse($warehouse_id, $product_id)
	{
		$data =  $this->db->select('*')
					 	  ->from('warehouses_products wp')
					 	  ->where('wp.warehouse_id',$warehouse_id)
					 	  ->where('wp.product_id',$product_id)
					 	  // ->where('wp.supplier_id',$supplier_id)
					 	  ->get()
					 	  ->row();
		
		if($data == NULL)
		{
			return NULL;
		}
		else
		{
			return $data->quantity;
		}
	}
}
?>