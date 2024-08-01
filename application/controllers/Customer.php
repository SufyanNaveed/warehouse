<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Customer extends MY_Controller
{
	function __construct() {
		parent::__construct();
		
		$this->lang->load('auth');
	}

	public function index(){
		if(!$this->user_model->has_permission("list_customer"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
			//get all customer records to display list
			$data['data'] = $this->user_model->get_user_records_by_role('customer');
			$this->load->view('customer/list',$data);
		}
	} 
	/*
		get all state of country
	*/
	public function getState($id){
		$data = $this->customer_model->getState($id);
		echo json_encode($data);
	}
	/*
		get all city of state
	*/
	public function getCity($id){
		$data = $this->customer_model->getCity($id);
		echo json_encode($data);
	}
	
	public function add()
	{
		if(!$this->user_model->has_permission("add_customer"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {

			if($this->input->server('REQUEST_METHOD') === 'POST'){

				$this->form_validation->set_rules('first_name','First Name','required');		
				$this->form_validation->set_rules('last_name','Last Name','required');		
				$this->form_validation->set_rules('country_id','Country','required');		
				$this->form_validation->set_rules('state_id','State','required');		
				$this->form_validation->set_rules('city_id','City','required');		
				$this->form_validation->set_rules('postal_code','Postal Code','required');		
				$this->form_validation->set_rules('gstregtype','GST Registration Type','required');		

				if($this->form_validation->run()==FALSE){
					$this->load->view('customer/add');
				}
				else{
					
					$groupData 				= array();

		            $email    				= strtolower($this->input->post('email'));
		            $identity 				= strtolower($this->input->post('email'));
		            $password 				= "";
		            $groupData[]			= $this->input->post('role_id');

		            $first_name 			= $this->input->post('first_name');
		            $last_name 				= $this->input->post('last_name');
		            $phone 					= $this->input->post('phone');
		            $country_id 			= $this->input->post('country_id');
		            $state_id 				= $this->input->post('state_id');
		            $city_id 				= $this->input->post('city_id');
		            $address 				= $this->input->post('address');
		            $postal_code 			= $this->input->post('postal_code');
		            $company 				= $this->input->post('company');
		            $gstid 					= $this->input->post('gstid');
		            $gst_registration_type 	= $this->input->post('gstregtype');

		            $additional_data 		= array(
								                'first_name'			=> $first_name,
								                'last_name' 			=> $last_name,
								                'phone' 				=> $phone,
								                'country_id'			=> $country_id,
								                'state_id'  			=> $state_id,
								                'city_id'  				=> $city_id,	
								                'address'  				=> $address,	
								                'postal_code'			=> $postal_code,	
								                'company'				=> $company,		                
								                'gstid'					=> $gstid,	
								                'gst_registration_type'	=> $gst_registration_type,
								                'api_key'   			=> md5(uniqid(rand(), true))
								            );

		            

		            if($id = $this->ion_auth->register($identity, $password, $email, $additional_data,$groupData))
		            {
		            	$user 				=  	$this->user_model->get_single_user_record($id);
		            	$account_group_id 	= 	$user->account_group_id;
		            	// echo $id;
		            	// exit;
		            	$ledger_data = array(
									'title' 			=> strtoupper($user->first_name." ".$user->last_name),
									'opening_balance'   => 0.00,
									'closing_balance'   => 0.00,
									'accountgroup_id' 	=> $account_group_id
								);
						$ledger_id = $this->ledger_model->add_record($ledger_data);

						$this->user_model->edit_user_record(array("ledger_id"=>$ledger_id),$id);

						$this->session->set_flashdata('success', $first_name.' is added successfully.');
						redirect("customer",'refresh');
		            }
		            else
		            {
		            	$this->session->set_flashdata('failure', $first_name.' is failed to add.');
						redirect("customer",'refresh');
		            }
				}

			}else{
				// $data['taxes'] 		  = $this->tax_model->getTax();
				$data['country']	= 	$this->supplier_model->getCountry();
				$this->load->view('customer/add',$data);
			}
		}
	}

	public function edit($id = NULL)
	{

		if(!$this->user_model->has_permission("edit_customer"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
			if($this->input->server('REQUEST_METHOD') === 'POST'){

				$id 			= $this->input->post('user_id');

				$this->form_validation->set_rules('first_name','First Name','required');		
				$this->form_validation->set_rules('last_name','Last Name','required');		
				$this->form_validation->set_rules('country_id','Country','required');		
				$this->form_validation->set_rules('state_id','State','required');		
				$this->form_validation->set_rules('city_id','City','required');		
				$this->form_validation->set_rules('postal_code','Postal Code','required');		
				$this->form_validation->set_rules('gstregtype','GST Registration Type','required');	

				if($this->form_validation->run()==FALSE){
					// redirect('customer/edit/'.$id);
					$this->load->view('customer/edit');
				}
				else{

					$groupData  	= array();

		        	

					$email    		= strtolower($this->input->post('email'));
		            $identity 		= strtolower($this->input->post('email'));
		            $password 		= $this->input->post('password');
		            $group_id 		= $this->input->post('role_id');
		           

		            // $this->user_model->assign_warehouse($id,$warehouse_id);
		            
		           

		            $first_name 			= $this->input->post('first_name');
		            $last_name 				= $this->input->post('last_name');
		            $phone 					= $this->input->post('phone');
		            $country_id 			= $this->input->post('country_id');
		            $state_id 				= $this->input->post('state_id');
		            $city_id 				= $this->input->post('city_id');
		            $address 				= $this->input->post('address');
		            $postal_code 			= $this->input->post('postal_code');
		            $company 				= $this->input->post('company');
		            $gstid 					= $this->input->post('gstid');
		            $gst_registration_type 	= $this->input->post('gstregtype');

		            $additional_data 		= array(
								                'first_name'			=> $first_name,
								                'last_name' 			=> $last_name,
								                'phone' 				=> $phone,
								                'country_id'			=> $country_id,
								                'state_id'  			=> $state_id,
								                'city_id'  				=> $city_id,	
								                'address'  				=> $address,	
								                'postal_code'			=> $postal_code,	
								                'company'				=> $company,		                
								                'gstid'					=> $gstid,	
								                'gst_registration_type'	=> $gst_registration_type
								                
								            );

		            // echo '<pre>';
		            // print_r($data);
		            // exit;

		            if($this->ion_auth->update($id, $data))
		            {
		            	// $this->change_group($group_id,$id);
		            	$this->session->set_flashdata('success', $first_name.' is updated successfully.');
						redirect("customer",'refresh');
		            }
		            else
		            {
		            	$this->session->set_flashdata('failure', $first_name.' is failed to update.');
						redirect("customer",'refresh');
		            }
				}
			}
			else
			{
				
				$data['data']		=	$this->user_model->get_single_user_record($id);
				$data['country']  			= $this->customer_model->getCountry();

				if($data['data']->country_id != "")
					$data['state']  = $this->customer_model->getState($data['data']->country_id);
				else
					$data['state']  = array();

				if($data['data']->state_id != "")
					$data['city']  = $this->customer_model->getCity($data['data']->state_id);					
				else
					$data['city']  = array();
				$this->load->view('customer/edit',$data);
			}
		}
	}

	public function delete($id){

		if(!$this->user_model->has_permission("delete_customer"))
        {
            $this->load->view('layout/restricted'); 
        }
        $customer 	= $this->customer_model->getCustomerRecord($id);

        else
        {
			if($this->ion_auth_model->deactivate($id)){
				$this->session->set_flashdata('success', $customer->first_name.' is deleted successfully.');
				redirect("customer",'refresh');
			}else{
				$this->session->set_flashdata('failure', $customer->first_name.' is failed to delete.');
				redirect("customer",'refresh');
			}
		}
	}
	// /* 
	// 	call add view to add customer record 
	// */
	// public function add(){
	// 	$data['country']  = $this->customer_model->getCountry();
	// 	$this->load->view('customer/add',$data);
	// }
	// /* 
	// 	call edit view to edit customer record 
	// */
	// public function edit($id){
	// 	$data['data'] = $this->customer_model->getRecord($id);
	// 	$data['country']  = $this->customer_model->getCountry();
	// 	$data['state'] = $this->customer_model->getState($data['data'][0]->country_id);
	// 	$data['city'] = $this->customer_model->getCity($data['data'][0]->state_id);
	// 	$this->load->view('customer/edit',$data);
	// }
	// /*

	// */
	// public function add_customer_sort(){
		

	// 	$sms_setting = $this->sms_setting_model->getSmsSetting();
	// 	$company_name = $this->db->get('company_settings')->row()->name;

	// 	$ledger_data = array(
	// 			'title' 			=> strtoupper($this->input->post('customer_name')),
	// 			'accountgroup_id' 	=> 28
	// 	);


	// 	$ledger_id = $this->ledger_model->addLedger($ledger_data);
	// 	$user_id = $this->ion_auth_model->user()->row()->id;
	// 	$mobile = $this->input->post('mobile');
	// 	$result = array(
	// 			'customer_name' => 	$this->input->post('customer_name'),
	// 			'gstid' 		=> 	$this->input->post('gstin'),
	// 			'address'		=>	$this->input->post('address'),	
	// 			'birth_date'	=>	$this->input->post('birth_date'),
	// 			'postal_code'	=>	$this->input->post('postal_code'),
	// 			'city_id'	 	=>	$this->input->post('city'),
	// 			'country_id'	=>	$this->input->post('country'),
	// 			'state_id'	 	=>	$this->input->post('state'),
	// 			"ledger_id"		=>  $ledger_id,
	// 			'mobile'	 	=>	$mobile,
	// 			'user_id'	 	=>	$user_id
	// 		);
	// 	$id = $this->customer_model->addModel($result);
	// 	// send sms
	// 	$message = "Dear ".$this->input->post('customer_name').", Welcome to ".$company_name.". Thank you for visiting us.";				
	// 	$response = send_sms($sms_setting, $mobile, $message);
	// 	$sms_history_data = array(
	// 					'mobile' => $mobile,
	// 					'message' => $message,
	// 					'response' => $response	
	// 				);
	// 	$this->sms_setting_model->addSmsHistroy($sms_history_data);


	// 	$log_data = array(
	// 			'user_id'  => $this->session->userdata('user_id'),
	// 			'table_id' => $id,
	// 			'message'  => 'Customer Inserted(Sortcut)'
	// 		);
	// 	$this->log_model->insert_log($log_data);
		
	// 	$data['customer']  = $this->customer_model->getRecord($id);
	// 	$data['data'] = $this->customer_model->getCustomer();
	// 	$data['country']  = $this->customer_model->getCountry();
	// 	$data['state'] = $this->customer_model->getState($data['customer'][0]->country_id);
	// 	$data['city'] = $this->customer_model->getCity($data['customer'][0]->state_id);
	// 	$data['customer_details'] = $this->customer_model->getCustomerData($id);
		
	// 	/*echo '<pre>';
	// 	print_r($data);
	// 	exit;*/

	// 	$data['id'] = $id;
	// 	echo json_encode($data);
	// }
	// /* 
	// 	This function used to add record in database 
	// */
	// public function addCustomer(){
	// 	$this->form_validation->set_rules('customer_name','Customer Name','trim|required|min_length[3]|callback_alpha_dash_space1');
	// 	/*$this->form_validation->set_rules('gstid', 'GSTID', 'trim|required');*/
	// 	$this->form_validation->set_rules('company_name', 'Company Name', 'trim|required|min_length[3]|callback_alpha_dash_space');
	// 	$this->form_validation->set_rules('address', 'Address', 'required');
	// 	$this->form_validation->set_rules('city', 'City', 'trim|required');
	// 	$this->form_validation->set_rules('country', 'Country', 'trim|required');
	// 	$this->form_validation->set_rules('state', 'State', 'trim|required');
	// 	$this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric');
	// 	$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
	// 	$this->form_validation->set_rules('postal_code', 'Postal Code', 'trim|required|numeric');

	// 	if($this->form_validation->run()==false){
	// 		$this->add();
	// 	}
	// 	else
	// 	{

	// 		$sms_setting = $this->sms_setting_model->getSmsSetting();
	// 		$company_name = $this->db->get('company_settings')->row()->name;

	// 		// echo '<pre>';
	// 		// print_r($sms_setting);
	// 		// echo $company_name;
	// 		// exit;

	// 		$ledger_data = array(
	// 				'title' 			=> strtoupper($this->input->post('customer_name')),
	// 				'accountgroup_id' 	=> 28
	// 		);
	// 		$ledger_id = $this->ledger_model->addLedger($ledger_data);
	// 		$user_id = $this->ion_auth_model->user()->row()->id;
	// 		$mobile = $this->input->post('mobile');
	// 		$data = array(
	// 					"customer_name"			=>	$this->input->post('customer_name'),
	// 					"company_name"			=>	$this->input->post('company_name'),
	// 					"address"				=>	$this->input->post('address'),
	// 					"city_id"				=>	$this->input->post('city'),
	// 					"birth_date"			=>	$this->input->post('birth_date'),
	// 					"country_id"			=>	$this->input->post('country'),
	// 					"state_id"				=>	$this->input->post('state'),
	// 					"state_code"			=>	$this->input->post('state_code'),
	// 					"mobile"				=>	$mobile,
	// 					"email"					=>	$this->input->post('email'),
	// 					"postal_code"			=>	$this->input->post('postal_code'),
	// 					"gstid"					=>	$this->input->post('gstid'),
	// 					"vat_no"    			=>	$this->input->post('vatno'),
	// 					"pan_no"    			=>	$this->input->post('panno'),
	// 					"tan_no"    			=>	$this->input->post('tan'),
	// 					"cst_reg_no"    		=>	$this->input->post('cstregno'),
	// 					"excise_reg_no"    		=>	$this->input->post('exciseregno'),
	// 					"lbt_reg_no"    		=>	$this->input->post('lbtregno'),
	// 					"servicetax_reg_no"    	=>	$this->input->post('servicetaxno'),
	// 					"ledger_id"				=>  $ledger_id,
	// 					"gst_registration_type" =>	$this->input->post('gstregtype'),
	// 					"user_id"				=>  $user_id
	// 				);
	// 		if($id = $this->customer_model->addModel($data)){ 

	// 			$message = "Dear ".$this->input->post('customer_name').", Welcome to ".$company_name.". Thank you for visiting us.";				
	// 			$response = send_sms($sms_setting, $mobile, $message);
	// 			$sms_history_data = array(
	// 							'mobile' => $mobile,
	// 							'message' => $message,
	// 							'response' => $response	
	// 						);
	// 			$this->sms_setting_model->addSmsHistroy($sms_history_data);


	// 			$log_data = array(
	// 					'user_id'  => $this->session->userdata('user_id'),
	// 					'table_id' => $id,
	// 					'message'  => 'Customer Inserted'
	// 				);
	// 			$this->log_model->insert_log($log_data);
	// 			$this->session->set_flashdata('success', 'Customer added successfully.');
	// 			redirect('biller','refresh');
	// 		}
	// 		else{
	// 			$this->session->set_flashdata('fail', 'Customer can not be Inserted.');
	// 			redirect("biller",'refresh');
	// 		}
	// 	}
		
	// }
	// /* 
	// 	This function used to edit customer record in database 
	// */
	// public function editCustomer(){
	// 	$id = $this->input->post('id');
	// 	$this->form_validation->set_rules('customer_name','Customer Name','trim|required|min_length[3]|callback_alpha_dash_space1');
	// 	/*$this->form_validation->set_rules('gstid', 'GSTID', 'trim|required');*/
	// 	$this->form_validation->set_rules('company_name', 'Company Name', 'trim|required|min_length[3]|callback_alpha_dash_space');
	// 	$this->form_validation->set_rules('address', 'Address', 'required');
	// 	$this->form_validation->set_rules('city', 'City', 'trim|required');
	// 	$this->form_validation->set_rules('country', 'Country', 'trim|required');
	// 	$this->form_validation->set_rules('state', 'State', 'trim|required');
	// 	$this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric');
	// 	$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
	// 	$this->form_validation->set_rules('postal_code', 'Postal Code', 'trim|required|numeric');

	// 	if($this->form_validation->run()==false){
	// 		$this->edit($id);
	// 	}
	// 	else
	// 	{
	// 		$data = array(
	// 					"customer_name"	=>	$this->input->post('customer_name'),
	// 					"company_name"	=>	$this->input->post('company_name'),
	// 					"address"		=>	$this->input->post('address'),
	// 					"birth_date"	=>	$this->input->post('birth_date'),
	// 					"city_id"		=>	$this->input->post('city'),
	// 					"country_id"	=>	$this->input->post('country'),
	// 					"state_id"		=>	$this->input->post('state'),
	// 					"state_code"	=>	$this->input->post('state_code'),
	// 					"mobile"		=>	$this->input->post('mobile'),
	// 					"email"			=>	$this->input->post('email'),
	// 					"postal_code"	=>	$this->input->post('postal_code'),
	// 					"gstid"			=>	$this->input->post('gstid'),
	// 					"vat_no"    	=>	$this->input->post('vatno'),
	// 					"pan_no"    	=>	$this->input->post('panno'),
	// 					"tan_no"    	=>	$this->input->post('tan'),
	// 					"cst_reg_no"    =>	$this->input->post('cstregno'),
	// 					"excise_reg_no" =>	$this->input->post('exciseregno'),
	// 					"lbt_reg_no"    =>	$this->input->post('lbtregno'),
	// 					"servicetax_reg_no"    	=>	$this->input->post('servicetaxno'),
	// 					"gst_registration_type" =>	$this->input->post('gstregtype'),
	// 					"customer_id"			=>	$this->input->post('id')
	// 					);
	// 		if($this->customer_model->editModel($data,$id)){
	// 			$log_data = array(
	// 					'user_id'  => $this->session->userdata('user_id'),
	// 					'table_id' => $id,
	// 					'message'  => 'Customer Updated'
	// 				);
	// 			$this->log_model->insert_log($log_data);
	// 			$this->session->set_flashdata('success', 'Customer updated successfully.');
	// 			redirect('biller','refresh');
	// 		}
	// 		else{
	// 			$this->session->set_flashdata('fail', 'Customer can not be Updated.');
	// 			redirect("biller",'refresh');
	// 		}
	// 	}
	// }

	public function sendBirthdayWishes()
	{
		$sms_setting = $this->sms_setting_model->getSmsSetting();
		$customers 	=  $this->db->select('*,CONCAT(c.first_name," ",c.last_name) as customer_name')
	                            ->from('users c')
	                            ->join('users_groups ug','c.id = ug.user_id')
	                            ->join('groups g','g.id = ug.group_id')
	                            ->where('MONTH(c.birth_date)',date('m'))
	                            ->where('DAY(c.birth_date)',date('d'))
	                            ->where('g.name','customer')
	                            ->get()
	                            ->result();
        $company_setting = $this->company_setting_model->getData();
        $no_of_customer = sizeof($customers);
        foreach ($customers as $row) {

        	$message = "Dear ".$row->customer_name.", Wish you many many happy returns of the day. ".$company_setting[0]->name;				
			$response = send_sms($sms_setting, $row->phone, $message);
			$sms_history_data = array(
							'mobile' => $row->phone,
							'message' => $message,
							'response' => $response	
						);
			$this->sms_setting_model->addSmsHistroy($sms_history_data);
        }
        $this->session->set_flashdata('success', 'Birthday wishes are sent to '.$no_of_customer." Customers");
		redirect("auth/dashboard",'refresh');
                                        
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
	function alpha_dash_space1($str) {
		if (! preg_match("/^([a-zA-Z ])+$/i", $str))
	    {
	        $this->form_validation->set_message('alpha_dash_space1', 'The %s field may only contain alpha and spaces.');
	        return FALSE;
	    }
	    else
	    {
	        return TRUE;
	    }
	}
	function mobile($str) {
		if (! preg_match("/^[6-9][0-9]{9}$/", $str))
	    {
	        $this->form_validation->set_message('mobile', 'The %s field may only contain Numeric and 10 digit(Ex.9898767654)');
	        return FALSE;
	    }
	    else
	    {
	        return TRUE;
	    }
	}
	function postal_code($str) {
		if (! preg_match("/^[1-9][0-9]{5}$/", $str))
	    {
	        $this->form_validation->set_message('postal_code', 'The %s field may only contain Numeric and 6 digit (Ex.382463)');
	        return FALSE;
	    }
	    else
	    {
	        return TRUE;
	    }
	}
	function generateRandomString($length) {
	    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}
	/*

	*/
	public function customer_sort_data(){
		$data['city'] = $this->customer_model->getCityName($this->input->post('city'));
		$data['state'] = $this->customer_model->getStateName($this->input->post('state'));
		$data['country'] = $this->customer_model->getCountryName($this->input->post('country'));
		echo json_encode($data);
	}
}
?>