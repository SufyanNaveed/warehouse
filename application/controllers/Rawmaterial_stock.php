<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Rawmaterial_stock extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
	}

	public function index()
	{
		if(!$this->user_model->has_permission("list_stock"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
	        $data['stocks'] 	= $this->rawmaterial_stock_model->get_records();
			$this->load->view('manufacturing/stock/index',$data);
        }
	} 

	public function add()
	{
		if(!$this->user_model->has_permission("add_stock"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
	        {
	            redirect('auth', 'refresh');
	        }

	        if(!$this->user_model->has_permission("list_stock"))
	        {
	            $this->load->view('layout/restricted'); 
	        }
	        else
	        {
		        if($_POST)
				{
					$this->form_validation->set_rules('product_id', "Product", 'required');
			        $this->form_validation->set_rules('quantity', "Quantity", 'required');
			        $this->form_validation->set_rules('warehouse_id', "Warehouse", 'required');
			        
			        // echo 'before validation';
			        if ($this->form_validation->run() == true)
			        {
			        	$product_id 	= 	$this->input->post('product_id');
			        	$warehouse_id 	= 	$this->input->post('warehouse_id');
			        	$supplier_id	= 	$this->input->post('supplier_id');
			        	$quantity 		= 	$this->input->post('quantity');
			        	$created_by 	= 	$this->session->userdata('user_id');

			            $data 			= 	array(
								                'product_id'	=> $product_id,
								                'warehouse_id' 	=> $warehouse_id,
								                'supplier_id'  	=> $supplier_id,
								                'quantity'  	=> $quantity,
								                'created_by'  	=> $created_by
								            );

			            // echo '<pre>';
			            // print_r($data);
			            // exit;

			            if($this->rawmaterial_stock_model->add_record($data))
			            {
			            	$this->session->set_flashdata('success', 'Stock has been added successfully.');
							redirect("stock",'refresh');
			            }
			            else
			            {
			            	$this->session->set_flashdata('failure', 'We got problem while adding stock.');
							redirect("stock",'refresh');
			            }
			        }
			        else
			        {
			        	echo 'else validation';
			        }
				}
				else
				{
					$data['products'] 	= $this->product_model->getProducts();
					$data['suppliers'] 	= $this->user_model->get_user_records_by_role("supplier");
					$data['warehouse'] 	= $this->warehouse_model->get_records();
					// echo '<pre>';
					// print_r($data);
					// exit;
					$this->load->view('manufacturing/stock/add',$data);
				}	
			}
		}
	} 

	public function edit($id = NULL)
	{
		if(!$this->user_model->has_permission("edit_stock"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
	        {
	            redirect('auth', 'refresh');
	        }
	        else
	        {
	        	if($_POST)
				{
					$this->form_validation->set_rules('product_id', "Product", 'required');
			        $this->form_validation->set_rules('quantity', "Quantity", 'required');
			        $this->form_validation->set_rules('warehouse_id', "Warehouse", 'required');

			        if ($this->form_validation->run() == true)
			        {
						$id 			= $this->input->post('id');

			            $product_id 	= 	$this->input->post('product_id');
			        	$warehouse_id 	= 	$this->input->post('warehouse_id');
			        	$supplier_id	= 	$this->input->post('supplier_id');
			        	$quantity 		= 	$this->input->post('quantity');
			        	

			            $data 			= 	array(
								                'product_id'	=> $product_id,
								                'warehouse_id' 	=> $warehouse_id,
								                'supplier_id'  	=> $supplier_id,
								                'quantity'  	=> $quantity
								                
								            );
			   			// echo '<pre>';
						// print_r($data);
						// exit;

			            if($this->rawmaterial_stock_model->edit_record($id, $data))
			            {
			            	$this->session->set_flashdata('success', 'Stock has been updated successfully.');
							redirect("stock",'refresh');
			            }
			            else
			            {
			            	$this->session->set_flashdata('failure', 'We got problem while updating stock.');
							redirect("stock",'refresh');
			            }
			        }
			        else
			        {
			        	echo 'someting wong';
			        	exit;
			        }
				}
				else
				{
					$data['products'] 	= $this->product_model->getProducts();
					$data['suppliers'] 	= $this->user_model->get_user_records_by_role("supplier");
					$data['warehouse'] 	= $this->warehouse_model->get_records();
					$data['stock']		= $this->rawmaterial_stock_model->get_single_record_by_join($id);
					// echo '<pre>';
					// print_r($data);
					// exit;
					$this->load->view('manufacturing/stock/add',$data);
				}		
	        }
	    }
	} 

	public function delete_record($id)
	{
		if(!$this->user_model->has_permission("delete_stock"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			$current_stock = $this->rawmaterial_stock_model->get_single_record($id);
			$this->rawmaterial_stock_model->remove_stock($current_stock->product_id, $current_stock->warehouse_id, $current_stock->supplier_id, $current_stock->quantity);
			// remove stock entry
			$this->db->where("id",$id);
			if($this->db->delete('stock'))
			{
				
				$this->session->set_flashdata('success', 'Stock record has been deleted successfully.');
				redirect('stock');			
			}
			else
			{
				$this->session->set_flashdata('failure', 'There is issue while deleting the stock record. Kindly contact the developer');
				redirect('stock');
			}
			if(is_int($is_deleted) === true)
			{
				
			}
		}
		
	}
}
?>