<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Stock_model extends CI_Model
{
	function __construct() 
	{
		parent::__construct();
		$CI =& get_instance();
        $CI->load->model('warehouse_model');
        $CI->load->model('product_model');
        $CI->load->model('warehouse_product_model');
	}

	public function get_records()
	{
		// return $this->db->get("stock")->result();
		return $this->db->select('s.*,p.name as product_name,w.warehouse_name as warehouse_name')
		                 ->from('stock s')
		                 ->join('products p','p.product_id = s.product_id')
		                 // ->join('users u','u.id = s.supplier_id')
		                 ->join('warehouse w','w.warehouse_id = s.warehouse_id')
		                 ->get()
		                 ->result();
	}

	public function get_single_record($id)
	{
		return $this->db->select('s.*')
		                 ->from('stock s')
		                 ->where('s.id',$id)
		                 ->get()
		                 ->row();
		
	}

	public function get_single_record_by_join($id)
	{
		// return $this->db->select('s.*,p.name as product_name,w.warehouse_name as warehouse_name,u.first_name,u.last_name')
		return $this->db->select('s.*,p.name as product_name,w.warehouse_name as warehouse_name')
		                 ->from('stock s')
		                 ->join('products p','p.product_id = s.product_id')
		                 // ->join('users u','u.id = s.supplier_id')
		                 ->join('warehouse w','w.warehouse_id = s.warehouse_id')
		                 ->where('s.id',$id)
		                 ->get()
		                 ->row();
		
	}

	public function add_record($data)
	{
		if($this->db->insert('stock',$data))
		{
			if($this->add_stock($data['product_id'],$data['warehouse_id'],$data['quantity']))
			// if($this->add_stock($data['product_id'],$data['warehouse_id'],$data['supplier_id'],$data['quantity']))
				return  TRUE;
			else
				return false;
		}
		else
		{
			return FALSE;
		}
	}

	public function edit_record($id,$data)
	{
		$old_stock = $this->get_single_record($id);
		$this->db->where('id',$id);		
		if($this->db->update('stock',$data))
		{
			$this->edit_stock($old_stock->quantity,$data['product_id'],$data['warehouse_id'],$data['quantity']);
			// $this->edit_stock($old_stock->quantity,$data['product_id'],$data['warehouse_id'],$data['supplier_id'],$data['quantity']);
			return true;
		}
		else
		{
			return false;
		}
	}

	public function delete_record($id)
	{
		$this->db->where('id',$id);
		if($this->db->update('stock',array('delete_status'=>1)))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	// public function add_stock($product_id, $warehouse_id, $supplier_id, $quantity)
	public function add_stock($product_id, $warehouse_id, $quantity)
	{
		$product_quantity 	= $this->product_model->getRecordByProductId($product_id)->quantity;
		$warehouse_quantity = $this->warehouse_product_model->get_product_quantity_by_warehouse($warehouse_id,$product_id);
		// $warehouse_quantity = $this->warehouse_product_model->get_product_quantity_by_warehouse($warehouse_id,$product_id,$supplier_id);
		$warehouse_quantity_bk = $warehouse_quantity;

		// if warehouse quantity is null then set to zero
		if($warehouse_quantity == NULL)
		{
			$warehouse_quantity = 0 ;
		}
		// updated warehouse and product quantity
		$updated_warehouse_quantity = $warehouse_quantity + $quantity;
		$updated_product_quantity   = $product_quantity + $quantity;

		$warehouse_data = array("quantity" => $updated_warehouse_quantity);
		$product_data 	= array("quantity" => $updated_product_quantity);

		if($warehouse_quantity_bk == NULL)
		{
			$data = array(
						"warehouse_id" => $warehouse_id,
						// "supplier_id"  => $supplier_id,
						"product_id"   => $product_id,
						"quantity"	   => $updated_warehouse_quantity
					);
			if($this->warehouse_product_model->add_record($data) && $this->product_model->editModel($product_data,$product_id))
				return true;
			else
				return false;	
		}
		else
		{
			if($this->warehouse_product_model->edit_record($warehouse_data,$warehouse_id,$product_id) && $this->product_model->editModel($product_data,$product_id))
			// if($this->warehouse_product_model->edit_record($warehouse_data,$warehouse_id,$supplier_id,$product_id) && $this->product_model->editModel($product_data,$product_id))
				return true;
			else 
				return false;
		}
	}

	// public function edit_stock($old_stock_quantity,$product_id, $warehouse_id, $supplier_id, $quantity)
	public function edit_stock($old_stock_quantity,$product_id, $warehouse_id, $quantity)
	{
		$old_stock_quantity = $old_stock_quantity;

		$product_quantity 	= $this->product_model->getRecordByProductId($product_id)->quantity;
		$warehouse_quantity = $this->warehouse_product_model->get_product_quantity_by_warehouse($warehouse_id,$product_id);
		// $warehouse_quantity = $this->warehouse_product_model->get_product_quantity_by_warehouse($warehouse_id,$product_id,$supplier_id);
		
		// updated warehouse and product quantity
		$updated_warehouse_quantity = $warehouse_quantity + $quantity - $old_stock_quantity;
		$updated_product_quantity   = $product_quantity + $quantity - $old_stock_quantity;

		$warehouse_data = array("quantity" => $updated_warehouse_quantity);
		$product_data 	= array("quantity" => $updated_product_quantity);
	
		// if($this->warehouse_product_model->edit_record($warehouse_data,$warehouse_id,$supplier_id,$product_id)
		if($this->warehouse_product_model->edit_record($warehouse_data,$warehouse_id,$product_id)
						 && $this->product_model->editModel($product_data,$product_id))
			return true;
		else 
			return false;
	}

	// public function remove_stock($product_id, $warehouse_id, $supplier_id, $quantity)
	public function remove_stock($product_id, $warehouse_id, $quantity)
	{
		
		$product_quantity 	= $this->product_model->getRecordByProductId($product_id)->quantity;
		$warehouse_quantity = $this->warehouse_product_model->get_product_quantity_by_warehouse($warehouse_id,$product_id);
		// $warehouse_quantity = $this->warehouse_product_model->get_product_quantity_by_warehouse($warehouse_id,$product_id,$supplier_id);
		
		// updated warehouse and product quantity
		$updated_warehouse_quantity = $warehouse_quantity - $quantity;
		$updated_product_quantity   = $product_quantity - $quantity;

		$warehouse_data = array("quantity" => $updated_warehouse_quantity);
		$product_data 	= array("quantity" => $updated_product_quantity);
	
		// if($this->warehouse_product_model->edit_record($warehouse_data,$warehouse_id,$supplier_id,$product_id) && $this->product_model->editModel($product_data,$product_id))
		if($this->warehouse_product_model->edit_record($warehouse_data,$warehouse_id,$product_id) && $this->product_model->editModel($product_data,$product_id))
			return true;
		else 
			return false;

	}
}
?>