<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms_setting extends MY_Controller
{
	function __construct(){
		parent::__construct();
		$this->load->model('sms_setting_model');
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
		$this->load->model('log_model');
		$this->load->model('email_setup_model');
        $this->load->model('sms_setting_model');
        
	}
	public function index()
	{
		if(!$this->user_model->has_permission("sms_setting"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			$data['data'] = $this->sms_setting_model->getSmsSetting();
			// echo '<pre>';
			// print_r($data);
			

			// exit;
			$this->load->view('sms_setting/index',$data);
		}
	}
	public function updateSmsSetting()
	{
		if(!$this->user_model->has_permission("sms_setting"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			$data = array(
					'api_url' => $this->input->post('api_url'),
					'sender' => $this->input->post('sender'),
					'route' => $this->input->post('route'),
					'auth_key' => $this->input->post('auth_key'),
					'unicode' => $this->input->post('unicode'),
					'country' => $this->input->post('country')
				);

			if($this->sms_setting_model->update($data)){
				
				$this->session->set_flashdata('fail', 'SMS Setting successfully Updated.');
				redirect('sms_setting','refresh');
			}
			else{
				$this->session->set_flashdata('fail', 'Error in Update SMS Setting.');
				redirect('sms_setting','refresh');
			}
		}
	}

	public function history()
	{
		$data['sms_history'] = $this->sms_setting_model->getHistory();		
		$this->load->view('sms_setting/sms_history',$data);
	}


}
?>