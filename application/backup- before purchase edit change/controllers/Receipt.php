<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Receipt extends MY_Controller
{
	function __construct() {
		parent::__construct();
		$this->load->model('receipt_model');
		$this->load->model('purchase_model');
		$this->load->model('biller_model');
		$this->load->model('log_model');
		$this->load->model('customer_model');
		$this->load->model('supplier_model');
		$this->load->model('ion_auth_model');
		$this->load->model('sales_model');
		$this->load->model('sms_setting_model');
		$this->load->model('ledger_model');
		
		$this->load->model('branch_model');
		$this->load->model('warehouse_model');
		$this->load->model('user_model');
		$this->load->model('category_model');
		$this->load->model('subcategory_model');
		$this->load->model('brand_model');
		$this->load->model('product_model');
		$this->load->model('payment_method_model');
		$this->load->model('email_setup_model');
        $this->load->model('sms_setting_model');
        
		$this->load->model('expense_category_model');
		$this->load->model('discount_model');


		$this->load->helper('sms_helper');
		$this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        
	}
	public function index()
	{
		if(!$this->user_model->has_permission("list_receipt"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			$data['data'] = $this->receipt_model->getReceipt();
			$this->load->view('receipt/list',$data);
		}
	} 
	/* 
		call Add view to add category  
	*/
	public function add()
	{
		if(!$this->user_model->has_permission("add_receipt"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			$data['invoice'] 		= $this->receipt_model->getInvoice();
			$data['company'] 		= $this->purchase_model->getCompany();
			$data['branch'] 		= $this->branch_model->get_records();
			$data['payment_method'] = $this->payment_method_model->payUserData();
			$data['p_reference_no'] = $this->receipt_model->generateReferenceNo();
			$data['to_ac'] 			= $this->receipt_model->getToAccount();
			$data['from_ac'] 		= $this->receipt_model->getFromAccount();

			$this->load->view('receipt/add',$data);
		}
	}
	
	public function getAccount($branch_id)
	{
		$data['to_ac'] 	= $this->receipt_model->getFromAccount($branch_id);
		$data['from_ac'] 		= $this->receipt_model->getToAccount($branch_id);
	
		echo json_encode($data);
	}
	
	public function getAmount($id){
		$data = $this->receipt_model->getAmount($id);
		echo json_encode($data);
	}
	/*

	*/
	public function getAccountGroupID($id){
		echo $this->receipt_model->getAccountGroupID($id);
	}
	/* 
		This function used to store receipt record in database  
	*/
	public function addReceipt(){
        
        $paying_by = $this->input->post('paying_by');
		$this->form_validation->set_rules('date','Date','trim|required');
		$this->form_validation->set_rules('reference_no','Reference No','trim|required');
		// $this->form_validation->set_rules('branch','Branch','trim|required');
		$this->form_validation->set_rules('from_ac','From A/C','trim|required');
		$this->form_validation->set_rules('to_ac','To A/C','trim|required');
		$this->form_validation->set_rules('amount','Amount','trim|required');
		$this->form_validation->set_rules('paying_by','Paying By','trim|required');
		if($paying_by == "Cheque"){
			$this->form_validation->set_rules('bank_name','Bank Name','trim|required|callback_alpha_dash_space');
			$this->form_validation->set_rules('cheque_no','Cheque No','trim|required|numeric');
			$this->form_validation->set_rules('cheque_date','Cheque Date','trim|required');
		}
		if($this->form_validation->run()==false){
			$this->add();
		}
		else
		{
			// $id = $this->input->post('id');
			
			$invoice = $this->input->post('invoice');
			$amount = $this->input->post('amount');
			$sms_setting = $this->sms_setting_model->getSmsSetting();
			$company_name = $this->db->get('company_settings')->row()->name;
			$company_phone = $this->db->get('company_settings')->row()->phone;

			if($this->input->post('cheque_date') == '')
			{
				$cheque_date = null;
			}
			else
			{
				$cheque_date = $this->input->post('cheque_date');
			}
			$data = array(
					"transaction_date" => date('Y-m-d'),
					"type"             => $this->input->post('type'),
					"amount"           => $amount,
					"voucher_no"       => $this->input->post('reference_no'),
					"voucher_date"     => $this->input->post('date'),
					"mode"             => $this->input->post('paying_by'),
					"cheque_no"        => $this->input->post('cheque_no'),
					"cheque_date"      => $cheque_date,
					"bank_name"        => $this->input->post('bank_name'),
					"from_account"     => $this->input->post('from_ac'),
					"to_account"       => $this->input->post('to_ac'),
					"narration"        => $this->input->post('description'),
					"voucher_status"   => 1
				);
			
			if($this->input->post('type')=='P')
			{
				$data['receipt_id'] = $invoice;
				$data['invoice_id'] = null;
			}
			else if($this->input->post('type')=='R')
			{
				$data['invoice_id'] = $invoice;
				$data['receipt_id'] = null;
			}
			

			if($this->input->post('type')=="R")
			{
				// echo '<pre>';
				// echo 'in r';
				// print_r($data);
				// exit;
				if($id = $this->receipt_model->addReceipt($data)){
					$log_data = array(
							'user_id'  => $this->session->userdata('user_id'),
							'table_id' => $id,
							'message'  => 'Receipt Generated'
						);
					$this->log_model->insert_log($log_data);

					if($this->input->post('to_mobile') !== null)
					{
						$message = "We have received your payment of ".$amount. " .Thank you visting our store. ".$company_name;
						$mobile = $this->input->post('to_mobile');

						$response = send_sms($sms_setting, $mobile, $message);
						$sms_history_data = array(
									'mobile' => $mobile,
									'message' => $message,
									'response' => $response	
								);
						$this->sms_setting_model->addSmsHistroy($sms_history_data);
					}

					redirect('receipt','refresh');
				}
				else{
					redirect("receipt",'refresh');
				}
			}
			else
			{
				// echo '<pre>';
				// echo 'p';
				// print_r($data);
				// exit;
				if($id = $this->receipt_model->addReceipt($data)){
					$log_data = array(
							'user_id'  => $this->session->userdata('user_id'),
							'table_id' => $id,
							'message'  => 'Payment Completed'
						);
					$this->log_model->insert_log($log_data);

					
					if($this->input->post('to_mobile') !== null)
					{
						$message = "We have made your payment of ".$amount. " .Thank you for doing business with us. ".$company_name;
						$mobile = $this->input->post('to_mobile');
					
						$response = send_sms($sms_setting, $mobile, $message);
						$sms_history_data = array(
									'mobile' => $mobile,
									'message' => $message,
									'response' => $response	
								);
						$this->sms_setting_model->addSmsHistroy($sms_history_data);
					}


					// $message = "We have received your payment of ".$amount." in regards with ".$reference_no.". Thank you visting our store. ".$company_name;
					// send_sms($sms_setting->api_url, $sms_setting->sender, $sms_setting->route, $sms_setting->auth_key, $sms_setting->country, $sms_setting->unicode, $mobile, $message, 1);
					

					redirect('payment','refresh');
				}
				else{
					redirect("payment",'refresh');
				}
			}
			
		}
		
	}
	/* 
		call edit view to edit Category Record 
	*/
	public function edit($id){
		$data['data'] = $this->category_model->getRecord($id);
		$this->load->view('category/edit',$data);
	}
	/* 
		This function is used to edit category record in database 
	*/
	public function editCategory(){
		$id = $this->input->post('id');
		$this->form_validation->set_rules('category_name', 'Category Name', 'trim|required|min_length[3]');
        if ($this->form_validation->run() == FALSE)
        {
            $this->edit($id);
        }
        else
        {
			$data = array(
						"category_name" => $this->input->post('category_name')
						);
			if($this->category_model->editModel($data,$id)){
				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $id,
						'message'  => 'Category Updated'
					);
				$this->log_model->insert_log($log_data);
				redirect('category','refresh');
			}
			else{
				$this->session->set_flashdata('fail', 'Category can not be Updated.');
				redirect("category",'refresh');
			}
		}
	}
	/* 
		Delete selected  Category Record 
	*/
	public function delete($id){
		if($this->category_model->deleteModel($id)){
			$log_data = array(
					'user_id'  => $this->session->userdata('user_id'),
					'table_id' => $id,
					'message'  => 'Category Deleted'
				);
			$this->log_model->insert_log($log_data);
			redirect('category');
		}
		else{
			$this->session->set_flashdata('fail', 'Category can not be Deleted.');
			redirect("category",'refresh');
		}
	}

	
	function alpha_dash_space($str) {
		if (! preg_match("/^([-a-zA-Z ])+$/i", $str))
	    {
	        $this->form_validation->set_message('alpha_dash_space', 'The %s field may only contain alpha, spaces and dashes.');
	        return FALSE;
	    }
	    else
	    {
	        return TRUE;
	    }
	}
}
?>