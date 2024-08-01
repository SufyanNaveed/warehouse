<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
	}

	public function index()
	{
		if(!$this->user_model->has_permission("list_user_group"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			$default_roles 			= ['admin','accountant','purchaser','sales_person','customer','members','supplier'];
			$data['roles'] 			= $this->user_model->get_role_records();
			$data['default_roles']	= $default_roles;
			
			$this->load->view('user/index',$data);
		}
	} 

	public function users()
	{
		if(!$this->user_model->has_permission("list_user"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			$data['users'] = $this->user_model->get_user_records();
			$this->load->view('user/users',$data);
		}
	} 

	public function add_user()
	{
		// if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
  //       {
  //           redirect('auth', 'refresh');
  //       }

        if(!$this->user_model->has_permission("add_user"))
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
		        	$groupData  			= 	array();

		            $email    				= 	strtolower($this->input->post('email'));
		            $identity 				= 	strtolower($this->input->post('email'));
		            $password 				= 	$this->input->post('password');
		            $groupData[]			= 	$this->input->post('role_id');

		            $first_name 			= 	$this->input->post('first_name');
		            $last_name 				= 	$this->input->post('last_name');
		            $email 					= 	$this->input->post('email');
		            $password 				= 	$this->input->post('password');
		            $birth_date 			= 	$this->input->post('birth_date');
		            $phone 					= 	$this->input->post('phone');
		            $country_id 			= 	$this->input->post('country_id');
		            $state_id 				= 	$this->input->post('state_id');
		            $city_id 				= 	$this->input->post('city_id');
		            $address 				= 	$this->input->post('address');
		            $postal_code 			= 	$this->input->post('postal_code');
		            $company 				= 	$this->input->post('company');
		            $gstid 					= 	$this->input->post('gstid');
		            $password 				= 	$this->input->post('password');
		            $gst_registration_type 	= 	$this->input->post('gst_registration_type');

		            $additional_data 		= 	array(
									                'first_name'			=> $first_name,
									                'last_name' 			=> $last_name,
									                'email'  				=> $email,
									                'password'  			=> $password,
									                'birth_date'			=> $birth_date,
									                'phone' 				=> $phone,
									                'country_id'			=> $country_id,
									                'state_id'  			=> $state_id,
									                'city_id'  				=> $city_id,	
									                'address'  				=> $address,	
									                'postal_code'			=> $postal_code,	
									                'company'				=> $company,		
									                'gstid'					=> $gstid,	
									                'password'				=> $password,	
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
						redirect("user/users",'refresh');
		            }
		            else
		            {
		            	$this->session->set_flashdata('failure', $first_name.' is failed to add.');
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
					$data['state_id']	=	$user->state_id;

					echo json_encode($data);	
				}
            }
            else
            {
            	echo "failure";
            }
		        
		// }	
	} 

	public function get_single_user_record_ajax($id)
	{
		echo json_encode($this->user_model->get_single_user_record($id));
	}

	public function edit_user($id = NULL)
	{
		// if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
  //       {
  //           redirect('auth', 'refresh');
  //       }

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


	            
	            
	            $first_name 			= 	$this->input->post('first_name');
	            $last_name 				= 	$this->input->post('last_name');
	            $email 					= 	$this->input->post('email');
	            $password 				= 	$this->input->post('password');
	            $birth_date 			= 	$this->input->post('birth_date');
	            $phone 					= 	$this->input->post('phone');
	            $country_id 			= 	$this->input->post('country_id');
	            $state_id 				= 	$this->input->post('state_id');
	            $city_id 				= 	$this->input->post('city_id');
	            $address 				= 	$this->input->post('address');
	            $postal_code 			= 	$this->input->post('postal_code');
	            $company 				= 	$this->input->post('company');
	            $gstid 					= 	$this->input->post('gstid');
	            $password 				= 	$this->input->post('password');
	            $gst_registration_type 	= 	$this->input->post('gst_registration_type');

	            $additional_data 		= 	array(
								                'first_name'			=> $first_name,
								                'last_name' 			=> $last_name,
								                'email'  				=> $email,
								                'password'  			=> $password,
								                'birth_date'			=> $birth_date,
								                'phone' 				=> $phone,
								                'country_id'			=> $country_id,
								                'state_id'  			=> $state_id,
								                'city_id'  				=> $city_id,	
								                'address'  				=> $address,	
								                'postal_code'			=> $postal_code,	
								                'company'				=> $company,		
								                'gstid'					=> $gstid,	
								                'password'				=> $password,	
								                'gst_registration_type'	=> $gst_registration_type
								                
								            );

	            // echo '<pre>';
	            // // print_r($data);
	            // echo $_SERVER['SERVER_NAME'];
	            // exit;

	            if($_SERVER['SERVER_NAME'] != 'vaksys.com')
	            {
            		if($this->ion_auth->update($id, $additional_data))
		            {
		            	$this->user_model->assign_warehouse($id,$warehouse_id);
		            	$this->change_group($group_id,$id);

		            	$this->session->set_flashdata('success', $first_name.' is updated successfully.');
						redirect("user/edit_user/".$id,'refresh');
		            }
		            else
		            {
		            	$this->session->set_flashdata('failure', $first_name.' is failed to update.');
						redirect("user/edit_user/".$id,'refresh');
		            }
		        }
		        else
		        {
					if($id == 1)
	            	{
	            		$this->session->set_flashdata('failure', 'You are not allowed to change the admin user information in demo');
						redirect("user/edit_user/".$id,'refresh');	
	            	}
	            	{
	            		if($this->ion_auth->update($id, $additional_data))
			            {
			            	$this->user_model->assign_warehouse($id,$warehouse_id);
			            	$this->change_group($group_id,$id);

			            	$this->session->set_flashdata('success', $first_name.' is updated successfully.');
							redirect("user/edit_user/".$id,'refresh');
			            }
			            else
			            {
			            	$this->session->set_flashdata('failure', $first_name.' is failed to update.');
							redirect("user/edit_user/".$id,'refresh');
			            }
	            	}		        	
		        }
			}
			else
			{

				// echo 'out post';
				// exit;
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

				// echo '<pre>';
	   //          print_r($data);
	   //          exit;

				$this->load->view('user/edit_user',$data);
			}
		}	
	} 

	public function delete_user($user_id)
	{
		$user 	= 	$this->user_model->get_single_user_record($id);
		
		if(!$this->user_model->is_user_exist_in_transaction($user_id))
		{
			if($this->user_model->delete_user_record($user_id))
			{
				$this->session->set_flashdata('success', $user->first_name.' is delete successfully.');
				redirect("user/users",'refresh');
			}
			else
			{
				$this->session->set_flashdata('failure', $user->first_name.' is failed to delete.');
				redirect("user/users",'refresh');
			}
		}
		else
		{
			$this->session->set_flashdata('warning', $user->first_name.' is associated with any transaction. so you will not be able to delete the user.');
			redirect("user/users",'refresh');
		}
	}
	public function change_group($new_group_id, $user_id=false)
	{
		$old_user	= $this->user_model->get_single_user_record($user_id); 
		$old_groupData 	= array();
		$new_groupData 	= array();

		if($old_user->group_id != $new_group_id)
        {
        	// remove from old group
         	$groupData[] = $old_user->group_id;
         	$this->ion_auth_model->remove_from_group($old_groupData,$user_id);

        	// add to new group
        	$new_groupData[] = $new_group_id;
        	$this->ion_auth_model->add_to_group($new_groupData,$user_id);
        }
	}

	/*********************** Permission function ********************/

	public function permission()
	{

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            $this->load->view('layout/restricted');	
        }
        else
        {
        	$data['roles'] 							= $this->user_model->get_role_records();
			$data['permissions'] 					= $this->user_model->get_permission_records();
			$data['product_permissions'] 			= $this->user_model->get_permission_records_by_module("product");
			$data['sales_permissions'] 				= $this->user_model->get_permission_records_by_module("sales");
			$data['s_return_permissions'] 			= $this->user_model->get_permission_records_by_module("sales_return");
			$data['purchase_permissions'] 			= $this->user_model->get_permission_records_by_module("purchase");
			$data['p_return_permissions'] 			= $this->user_model->get_permission_records_by_module("purchase_return");
			$data['transfer_permissions'] 			= $this->user_model->get_permission_records_by_module("transfer");
			$data['location_permissions'] 			= $this->user_model->get_permission_records_by_module("location");
			$data['user_permissions'] 				= $this->user_model->get_permission_records_by_module("user");
			$data['category_permissions'] 			= $this->user_model->get_permission_records_by_module("category");
			$data['subcategory_permissions']		= $this->user_model->get_permission_records_by_module("subcategory");
			$data['brand_permissions'] 				= $this->user_model->get_permission_records_by_module("brand");
			$data['branch_permissions']				= $this->user_model->get_permission_records_by_module("branch");
			$data['warehouse_permissions']			= $this->user_model->get_permission_records_by_module("warehouse");
			$data['discount_permissions']			= $this->user_model->get_permission_records_by_module("discount");
			$data['ledger_permissions']				= $this->user_model->get_permission_records_by_module("ledger");
			$data['report_permissions']				= $this->user_model->get_permission_records_by_module("report");
			$data['bank_account_permissions']		= $this->user_model->get_permission_records_by_module("bank_account");
			$data['expense_category_permissions']	= $this->user_model->get_permission_records_by_module("expense_category");
			$data['expense_permissions']			= $this->user_model->get_permission_records_by_module("expense");
			$data['setting_permissions']			= $this->user_model->get_permission_records_by_module("setting");
			$data['accountgroup_permissions']		= $this->user_model->get_permission_records_by_module("accountgroup");
			$data['transaction_permissions']		= $this->user_model->get_permission_records_by_module("transaction");
			$data['quotation_permissions']			= $this->user_model->get_permission_records_by_module("quotation");
			$data['currency_permissions']			= $this->user_model->get_permission_records_by_module("currency");
			$data['stock_permissions']				= $this->user_model->get_permission_records_by_module("stock");
			$data['user_group_permissions']			= $this->user_model->get_permission_records_by_module("user_group");
			$data['permission_permissions']			= $this->user_model->get_permission_records_by_module("permission");
			$data['setting_permissions']			= $this->user_model->get_permission_records_by_module("setting");
			$data['role_permissions']				= $this->user_model->get_permission_records_by_module("role");
			$data['uqc_permissions']				= $this->user_model->get_permission_records_by_module("uqc");
			$data['customer_permissions']			= $this->user_model->get_permission_records_by_module("customer");
			$data['supplier_permissions']			= $this->user_model->get_permission_records_by_module("supplier");
			

			$this->load->view('user/permission',$data);	
        }

		
	} 

	public function ajax_get_permission_records_by_role($role_id)
	{
		echo json_encode($this->user_model->get_permission_records_by_role($role_id));
	}

	public function save_permission()
	{
		if($_POST)
		{
			$this->form_validation->set_rules('role_id', 'User Role', 'required');
			if($this->form_validation->run() == FALSE)
			{
	            $this->permission();
	        }
	        else
	        {
	        	$user_id				= $this->session->get_userdata()['user_id'];
	        	$group_id 				= $this->user_model->get_single_user_record($user_id)->group_id;

	        	$group 	= $this->user_model->get_single_role_record($this->input->post('role_id'));

	        	// echo '<pre>';
	        	// print_r($group);
	        	// exit;
	        	
	        	// echo 'user id :'.$user_id;
	        	// echo 'group id :'.$group_id;
	        	// // print_r($user_id);
	        	// // print_r($group_id);
	        	// exit;
	        	
	        	if($group->name != 'admin')
	        	{

		    		$role_id 				= $this->input->post('role_id');
		    		$permission 			= $this->input->post('permission');
		    		$old_permission 		= $this->user_model->get_permission_records_by_role($role_id);
		    		$old_permission_array 	= array();

		    		// echo '<pre>';
		    		// print_r($old_permission);
		    		// print_r($permission);
		    		// exit;

		    		foreach ($old_permission as $row) {
		    			array_push($old_permission_array, $row->id);
		    		}

		    		for($i=0; $i < sizeof($permission); $i++) 
		    		{ 
		    			if(!in_array($permission[$i], $old_permission_array))
		    			{
		    				$this->user_model->add_permission_role_record($permission[$i],$role_id);	    				
		    				$key = array_search($permission[$i], $old_permission_array);
		    				unset($old_permission_array[$key]);
		    			}
		    			else if (in_array($permission[$i], $old_permission_array))
		    			{
		    				$key = array_search($permission[$i], $old_permission_array);
		    				unset($old_permission_array[$key]);	
		    			}
		    		}
		
		     		foreach ($old_permission_array as $key => $value) {
		     			$this->user_model->delete_permission_record($old_permission_array[$key],$role_id);	    				
		     		}


		     		if($role_id == $group_id)
		     		{
		     			redirect('auth/logout');
		     		}
		     		else
		     		{
		     			$this->session->set_flashdata('success', 'Permission has been saved for user role.');
						redirect("user/permission",'refresh');	
		     		}
	    		}
	    		else
	    		{
	    			$this->session->set_flashdata('failure', 'You can not change the permission for Admin');
					redirect("user/permission",'refresh');	
	    		}
	        }
		}
		else
		{
			$data['roles'] 			= $this->user_model->get_role_records();
			$data['permissions'] 	= $this->user_model->get_permission_records();			
			$this->load->view('user/permission',$data);	
		}
	}

	/*********************** User role function ********************/

	public function add_role()
	{

		if(!$this->user_model->has_permission("add_user_group"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			if($_POST)
			{
				$this->form_validation->set_rules('account_group_id', 'Account Group', 'trim|required');
				$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[3]');
				$this->form_validation->set_rules('description', 'Description', 'trim');

				if($this->form_validation->run() == FALSE)
				{
		            $this->add_role();
		        }
		        else
		        {
		        	$account_group_id 	= 	$this->input->post('account_group_id');
		        	$name 				= 	$this->input->post('name');
		        	$description 		= 	$this->input->post('description');

		    		$data 				= 	array(
						    					'account_group_id' 	=> $account_group_id,
						    					'name' 				=> $name,
						    					'description' 		=> $description
						    				);

		    		if($id = $this->user_model->add_role_record($data))
		    		{ 
		    			$this->session->set_flashdata('success', $name.' is added successfully.');
						redirect("user",'refresh');
		    		}
		    		else
		    		{
		    			$this->session->set_flashdata('failure', $name.' is failed to added.');
						redirect("user",'refresh');
		    		}
		        }

			}
			else
			{
				$data['account_groups'] 	=	$this->account_group_model->get_records();	
				$this->load->view('user/add_role',$data);	
			}
		}
	} 

	public function edit_role($id = NULL)
	{
		if(!$this->user_model->has_permission("edit_user_group"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			if($_POST)
			{
				$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[3]');
				$this->form_validation->set_rules('description', 'Description', 'trim');

				if($this->form_validation->run() == FALSE)
				{
		            $this->add_role();
		        }
		        else
		        {
		        	$id = $this->input->post('id');

		    		$account_group_id 	= 	$this->input->post('account_group_id');
		        	$name 				= 	$this->input->post('name');
		        	$description 		= 	$this->input->post('description');

		    		$data 				= 	array(
						    					'account_group_id' 	=> $account_group_id,
						    					'name' 				=> $name,
						    					'description' 		=> $description
						    				);
		    				
		    		if($this->user_model->edit_role_record($data,$id))
		    		{ 
		    			$this->session->set_flashdata('success', $name.' is updated successfully.');
						redirect("user",'refresh');
		    		}
		    		else
		    		{
		    			$this->session->set_flashdata('success', $name.' is failed to update.');
						redirect("user",'refresh');
		    		}
		        }

			}
			else{

				$data['account_groups'] 	=	$this->account_group_model->get_records();	
				$data['role'] 				= 	$this->user_model->get_single_role_record($id);
				$this->load->view('user/add_role',$data);	
			}
		}
	} 

	public function delete_role($id)
	{

		if(!$this->user_model->has_permission("delete_user_group"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			$is_deleted = $this->user_model->delete_role_record($id);
			if($is_deleted === true || $is_deleted > 0 )
			{
				if(is_int($is_deleted) === true)
				{
					$this->session->set_flashdata('failure', 'There are already '.$is_deleted.' user exist in this role. Kindly delete those user first.');
					redirect('user');
				}
				else
				{
					$this->session->set_flashdata('success', 'Role is deleted successfully');
					redirect('user');
				}
			}
			else
			{
				$this->session->set_flashdata('failure', 'Role is not deleted.');
				redirect("user",'refresh');
			}
		}
	}

	/********************************************* Send welcome message ***********************************/

	public function send_welcome_message($customer_name,$mobile)
	{
		$sms_setting 		= $this->sms_setting_model->getSmsSetting();
		$company_name 		= $this->db->get('company_settings')->row()->name;
		$message 			= "Dear ".$customer_name.", Welcome to ".$company_name;				
		$response 			= send_sms($sms_setting, $mobile, $message);
		$sms_history_data 	= array(
									'mobile' => $mobile,
									'message' => $message,
									'response' => $response	
								);
		$this->sms_setting_model->addSmsHistroy($sms_history_data);
	}
}
?>