<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Customer extends MY_Controller
{
	function __construct() {
		parent::__construct();
		$this->load->model('customer_model');
		$this->load->model('log_model');
		$this->load->model('ledger_model');
		$this->load->model('ion_auth_model');
		$this->load->model('sms_setting_model');
		$this->load->model('company_setting_model');
		
		$this->load->helper('sms_helper');
		
	}
	public function index(){
		//get all customer records to display list
		$data['data'] = $this->customer_model->getCustomer();
		$this->load->view('customer/list',$data);
	} 
	public function add()
	{
        if(!$this->user_model->has_permission("add_customer"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
	        if($_POST)
			{
				// $tables = $this->config->item('tables','ion_auth');

				$this->form_validation->set_rules('first_name', "Last Name", 'required');
		        $this->form_validation->set_rules('last_name', "First Name", 'required');
		        $this->form_validation->set_rules('role_id', "User Role", 'required');        
		        $this->form_validation->set_rules('email', "Email", 'required|valid_email|is_unique[users.email]');
		        $this->form_validation->set_rules('password', "Password", 'required|min_length[5]|max_length[16]|matches[password_confirm]');
		        $this->form_validation->set_rules('password_confirm', "Confirm Password", 'required');


		        if ($this->form_validation->run() == true)
		        {
		        	$groupData  = array();

		            $email    	= strtolower($this->input->post('email'));
		            $identity 	= strtolower($this->input->post('email'));
		            $password 	= $this->input->post('password');
		            $groupData[]= $this->input->post('role_id');

		            $additional_data = array(
		                'first_name'	=> $this->input->post('first_name'),
		                'last_name' 	=> $this->input->post('last_name'),
		                'email'  		=> $this->input->post('email'),
		                'password'  	=> $this->input->post('password'),
		                'birth_date'	=> $this->input->post('birth_date'),
		                'phone' 		=> $this->input->post('phone'),
		                'country_id'	=> $this->input->post('country_id'),
		                'state_id'  	=> $this->input->post('state_id'),
		                'city_id'  		=> $this->input->post('city_id'),	
		                'address'  		=> $this->input->post('address'),	
		                'postal_code'	=> $this->input->post('postal_code'),	
		                'company'		=> $this->input->post('company'),		                
		                'gstid'			=> $this->input->post('gstid'),	
		                'password'		=> $this->input->post('password'),	
		                'gst_registration_type'	=> $this->input->post('gst_registration_type'),
		                'api_key'   	=> md5(uniqid(rand(), true))
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


						$this->session->set_flashdata('success', 'User has been added successfully.');
						redirect("user/users",'refresh');
		            }
		            else
		            {
		            	$this->session->set_flashdata('failure', 'We got problem while creating User.');
						redirect("user/users",'refresh');
		            }
		        }
		        else
		        {
		        	$data['roles'] = $this->user_model->get_role_records();
					$this->load->view('user/add_user',$data);
		        }
			}
			else
			{
				$data['country']  = $this->customer_model->getCountry();
				$data['state'] 	  = array();
				$data['city']  	  = array();
				$data['roles'] 	  = $this->user_model->get_role_records();

				$this->load->view('user/add_user',$data);
			}
		}	
	}

	public function add_user_ajax()
	{

		// if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
  //       {
  //           redirect('auth', 'refresh');
  //       }

  //       if(!$this->user_model->has_permission("add_user"))
		// {
		// 	//$this->load->view('layout/restricted');	
		// 	echo 'failure';
		// }
		// else
		// {
	        
        	$groupData  = array();

            $email    	= strtolower($this->input->post('email'));
            $identity 	= strtolower($this->input->post('email'));
            $password 	= $this->input->post('password');
            $groupData[]= $this->input->post('role_id');

            // echo $this->input->post('role_id');

            // exit;
            $additional_data = array(
                'first_name'=> $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email'  	=> $this->input->post('email'),
                'api_key'   => md5(uniqid(rand(), true))
            );

            if($this->input->post('password') !== null){
            	$additional_data['password'] = $this->input->post('password');
            }

            if($this->input->post('country_id') !== null){
            	$additional_data['country_id'] = $this->input->post('country_id');
            }

            if($this->input->post('state_id') !== null){
            	$additional_data['state_id'] 	= $this->input->post('state_id');
            	$additional_data['state_code']	= $this->location_model->getStateCode($this->input->post('state_id'));
            }

            if($this->input->post('city_id') !== null){
            	$additional_data['city_id'] = $this->input->post('city_id');
            }

            if($this->input->post('gst_registration_type') !== null){
            	$additional_data['gst_registration_type'] = $this->input->post('gst_registration_type');
            }

            if($this->input->post('gstid') !== null){
            	$additional_data['gstid'] = $this->input->post('gstid');
            }

            if($this->input->post('phone') !== null){
            	$additional_data['phone'] = $this->input->post('phone');
            }

            if($this->input->post('address') !== null){
            	$additional_data['address'] = $this->input->post('address');
            }

            if($this->input->post('postal_code') !== null){
            	$additional_data['postal_code'] = $this->input->post('postal_code');
            }

            if($this->input->post('birth_date') !== null){
            	$additional_data['birth_date'] = $this->input->post('birth_date');
            }

            if($this->input->post('company') !== null){
            	$additional_data['company'] = $this->input->post('company');
            }




            // echo '<pre>';
            // print_r($additional_data);
            // exit;

            if($id = $this->ion_auth->register($identity, $password, $email, $additional_data,$groupData))
            {
            	// echo $id;
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

				$role_name = $this->user_model->get_single_role_record($this->input->post('role_id'))->name;

				$this->send_welcome_message($additional_data['first_name']." ".$additional_data['last_name'],$additional_data['phone']);


				if($role_name == "customer")
				{
					$data['customer']  			= $user;
					$data['data'] 				= $this->user_model->get_user_records_by_role("customer");
					$data['country']  			= $this->location_model->getCountries();
					$data['state']	 			= $this->location_model->getStates($user->country_id);
					$data['city'] 				= $this->location_model->getCities($user->state_id);
					$data['customer_details'] 	= $this->user_model->get_single_user_record($id);
				
					$data['id'] = $id;	
					echo json_encode($data);
				}
				else
				{
					$data['users'] 	= $this->user_model->get_user_records_by_role($this->user_model->get_single_role_record($this->input->post('role_id'))->name);
					$data['id']		= $id;
					$data['state_id']	=	$user->id;

					echo json_encode($data);	
				}
            }
            else
            {
            	echo "failure";
            }
		        
		// }	
	} 

	public function get_single_record_ajax($id)
	{
		echo json_encode($this->user_model->get_single_user_record($id));
	}

	public function edit($id = NULL)
	{
		if(!$this->user_model->has_permission("edit_user"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
	        if($_POST)
			{
	        	$groupData  	= array();

	        	$id 			= $this->input->post('user_id');

	            $email    		= strtolower($this->input->post('email'));
	            $identity 		= strtolower($this->input->post('email'));
	            $password 		= $this->input->post('password');
	            $group_id 		= $this->input->post('role_id');
	            $warehouse_id	= $this->input->post('warehouse_id');


	            $this->user_model->assign_warehouse($id,$warehouse_id);
	            
	           

	            $data = array(
	                'first_name'	=> $this->input->post('first_name'),
	                'last_name' 	=> $this->input->post('last_name'),
	                'email'  		=> $this->input->post('email'),
	                'password'  	=> $this->input->post('password'),
	                'birth_date'	=> $this->input->post('birth_date'),
	                'phone' 		=> $this->input->post('phone'),
	                'country_id'	=> $this->input->post('country_id'),
	                'state_id'  	=> $this->input->post('state_id'),
	                'city_id'  		=> $this->input->post('city_id'),	
	                'address'  		=> $this->input->post('address'),	
	                'postal_code'	=> $this->input->post('postal_code'),	
	                'company'		=> $this->input->post('company'),		                
	                'gstid'			=> $this->input->post('gstid'),	
	                'password'		=> $this->input->post('password'),	
	                'gst_registration_type'	=> $this->input->post('gst_registration_type')
	                
	            );

	    
	            if($this->ion_auth->update($id, $data))
	            {
	            	$this->change_group($group_id,$id);
	            	$this->session->set_flashdata('success', 'User has been updated successfully.');
					redirect("user/edit_user/".$id,'refresh');
	            }
	            else
	            {
	            	$this->session->set_flashdata('failure', 'We got problem while updating User.');
					redirect("user/edit_user/".$id,'refresh');
	            }
			}
			else
			{

				$data['roles'] 				= $this->user_model->get_role_records();
				$data['user'] 				= $this->user_model->get_single_user_record($id);
				$data['warehouse'] 			= $this->warehouse_model->get_records();
				$data['assigned_warehouse'] = $this->user_model->assigned_warehouse($id);
				$data['country']  			= $this->customer_model->getCountry();

				if($data['user']->country_id != "")
					$data['state']  = $this->customer_model->getState($data['user']->country_id);
				else
					$data['state']  = array();

				if($data['user']->state_id != "")
					$data['city']  = $this->customer_model->getCity($data['user']->state_id);					
				else
					$data['city']  = array();

		
				$this->load->view('user/edit_user',$data);
			}
		}	
	} 

	public function delete($user_id)
	{
		if(!$this->user_model->is_user_exist_in_transaction($user_id))
		{
			if($this->user_model->delete_user_record($user_id))
			{
				$this->session->set_flashdata('success', 'User has been delete successfully.');
				redirect("user/users",'refresh');
			}
			else
			{
				$this->session->set_flashdata('failure', 'User has been failed to delete.');
				redirect("user/users",'refresh');
			}
		}
		else
		{
			$this->session->set_flashdata('warning', 'User is associated with any transaction. so you will not be able to delete the user.');
			redirect("user/users",'refresh');
		}
	}
}
?>