<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Payment extends MY_Controller
{
	function __construct() {
		parent::__construct();
		
		
	}
	public function index()
	{
		if(!$this->user_model->has_permission("list_payment"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			$data['data'] = $this->payment_model->getPayment();	
			$this->load->view('payment/list',$data);
		}
	} 
	public function add()
	{
		if(!$this->user_model->has_permission("add_payment"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			$data['invoice'] 		= $this->payment_model->getReceipt();
			$data['branch'] 		= $this->branch_model->get_records();
			$data['p_reference_no'] = $this->receipt_model->generateReferenceNo();
			$data['payment_method'] = $this->payment_method_model->payUserData();
			$data['to_ac'] 			= $this->receipt_model->getFromAccount();
			$data['from_ac'] 		= $this->receipt_model->getToAccount();
			$this->load->view('payment/add',$data);
		}
	}
	/*

	*/
	public function getAmount($id){
		$data = $this->payment_model->getAmount($id);
		echo json_encode($data);
	}

	public function getAccount($branch_id){
		$data['from_ac'] 	= $this->payment_model->getFromAccount($branch_id);
		$data['to_ac'] 		= $this->payment_model->getToAccount($branch_id);
		// $data = $this->payment_model->getAccount($branch_id);
		// echo '<pre>';
		// print_r($data);
		// exit;
		echo json_encode($data);
	}

	public function getAccountGroupID($id){
		echo $this->payment_model->getAccountGroupID($id);
	}
}