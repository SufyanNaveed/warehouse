<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Currency extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('log_model');
		$this->load->model('customer_model');
		$this->load->model('currency_model');
		$this->load->model('branch_model');
		$this->load->model('warehouse_model');
		$this->load->model('user_model');
		$this->load->model('category_model');
		$this->load->model('subcategory_model');
		$this->load->model('brand_model');
		$this->load->model('product_model');
		$this->load->model('payment_method_model');
		$this->load->model('expense_category_model');
		$this->load->model('discount_model');
		$this->load->model('email_setup_model');
        $this->load->model('sms_setting_model');

		$this->load->library(array('ion_auth','form_validation'));
		$this->load->helper(array('url','language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');

		
	}

	public function index()
	{
		if(!$this->user_model->has_permission("list_currency"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	$data['currency'] 	= $this->currency_model->get_records();
			$this->load->view('currency/index',$data);	
        }
		
	} 


	public function add_currency()
	{
		if(!$this->user_model->has_permission("add_currency"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	if($_POST)
			{
				$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]');
				$this->form_validation->set_rules('symbol', 'Symbol', 'trim');

				if($this->form_validation->run() == FALSE)
				{
		            $this->add_role();
		        }
		        else
		        {
		    		$data = array(
		    					'name' 		=> $this->input->post('name'),
		    					'symbol' 	=> $this->input->post('symbol')
		    				);

		    		if($id = $this->currency_model->add_record($data))
		    		{ 
		    			$this->session->set_flashdata('success', 'Currency has been added successfully.');
						redirect("currency",'refresh');
		    		}
		        }

			}
			else{
				$this->load->view('currency/add');	
			}	
        }
		
	} 

	public function edit_currency($id = NULL)
	{
		if(!$this->user_model->has_permission("edit_currency"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	if($_POST)
			{
				$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]');
				$this->form_validation->set_rules('symbol', 'Symbol', 'trim');

				if($this->form_validation->run() == FALSE)
				{
		            $this->add_role();
		        }
		        else
		        {
		        	$id = $this->input->post('id');
		    		$data = array(
		    					'name' 			=> $this->input->post('name'),
		    					'symbol' 	=> $this->input->post('symbol')
		    				);
		    				
		    		if($this->currency_model->edit_record($data,$id))
		    		{
		    			$this->session->set_flashdata('success', 'Currency has been updated successfully.');
						redirect("currency",'refresh');
		    		}
		        }

			}
			else
			{
				$data['currency'] = $this->currency_model->get_single_record($id);
				$this->load->view('currency/add',$data);	
			}	
        }
		
	} 

	public function delete_record($id)
	{
		if(!$this->user_model->has_permission("delete_currency"))
        {
            $this->load->view('layout/restricted'); 
        }	
        else
        {
        	if($this->currency_model->delete_record($id))
			{
				$this->session->set_flashdata('success', 'Currency is deleted successfully');
				redirect('currency');
			}
			else
			{
				$this->session->set_flashdata('failure', 'Currency is not deleted.');
				redirect("currency",'refresh');
			}
        }
			
	}
}
?>