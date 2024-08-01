<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_setup extends MY_Controller
{
	function __construct(){
		parent::__construct();
		
		
	}
	public function index(){
		if(!$this->user_model->has_permission("invoice_setup"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	$data['data'] = $this->invoice_setup_model->getInvoiceSetup();
			$this->load->view('invoice_setup/add',$data);	
        }
		
	}
	public function add(){

		if($this->invoice_setup_model->invoice_setup($this->input->post('id'))){
			$log_data = array(
					'user_id'  => $this->session->userdata('user_id'),
					'table_id' => $this->input->post('id'),
					'message'  => 'Invoice Setup Updated'
				);
			$this->log_model->insert_log($log_data);
			$this->session->set_flashdata('fail', 'Invoice Setup Successfully Updated.');
			redirect('invoice_setup','refresh');
		}
		else{
			$this->session->set_flashdata('fail', 'Error in Update Invoice Setup.');
			redirect('invoice_setup','refresh');
		}
	}
}
?>