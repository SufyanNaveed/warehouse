<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms_setting extends MY_Controller
{
	function __construct(){
		parent::__construct();
	
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
			$api_url 	= 	$this->input->post('api_url');
			$sender		= 	$this->input->post('sender');
			$route 		= 	$this->input->post('route');
			$auth_key 	= 	$this->input->post('auth_key');
			$unicode 	= 	$this->input->post('unicode');
			$country 	= 	$this->input->post('country');

			$data 		= 	array(
								'api_url' 	=> $api_url,
								'sender' 	=> $sender,
								'route' 	=> $route,
								'auth_key' 	=> $auth_key,
								'unicode' 	=> $unicode,
								'country' 	=> $country
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