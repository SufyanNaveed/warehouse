<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Currency extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		
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
		        	$name 	= $this->input->post('name');
		        	$symbol = $this->input->post('symbol');

		    		$data 	= array(
		    					'name' 		=> $name,
		    					'symbol' 	=> $symbol
		    				);

		    		if($id = $this->currency_model->add_record($data))
		    		{ 
		    			$this->session->set_flashdata('success', $name.' is added successfully.');
						redirect("currency",'refresh');
		    		}
		    		else
		    		{
		    			$this->session->set_flashdata('failure', $name.' is failed to add.');
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
		    		$name 	= $this->input->post('name');
		        	$symbol = $this->input->post('symbol');

		    		$data 	= array(
		    					'name' 		=> $name,
		    					'symbol' 	=> $symbol
		    				);
		    				
		    		if($this->currency_model->edit_record($data,$id))
		    		{
		    			$this->session->set_flashdata('success', $name.' is updated successfully.');
						redirect("currency",'refresh');
		    		}
		    		else
		    		{
		    			$this->session->set_flashdata('failure', ' is failed to update.');
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
        	$currency 	= $this->currency_model->get_single_record($id);
        	
        	if($this->currency_model->delete_record($id))
			{
				$this->session->set_flashdata('success', $currency->name.' is deleted successfully');
				redirect('currency');
			}
			else
			{
				$this->session->set_flashdata('failure', $currency->name.' is not deleted.');
				redirect("currency",'refresh');
			}
        }
			
	}
}
?>