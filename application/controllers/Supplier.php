<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Supplier extends MY_Controller
{
	function __construct() {
		parent::__construct();

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
	}

	public function index(){

		if(!$this->user_model->has_permission("list_supplier"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
			// get all supplier to display list
			$data['data'] = $this->user_model->get_user_records_by_role('supplier');

			// echo '<pre>';
			// print_r($data);
			// exit;
			$this->load->view('supplier/list',$data);
		}
	} 

	public function add()
	{
		if(!$this->user_model->has_permission("add_supplier"))
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
					$this->load->view('supplier/add');
				}
				else{
					
					$groupData  			= array();

		            $email    				= strtolower($this->input->post('email'));
		            $identity 				= strtolower($this->input->post('email'));
		            $password 				= "";
		            $groupData[]			= $this->input->post('role_id');

		            $first_name 			= 	$this->input->post('first_name');
		            $last_name 				= 	$this->input->post('last_name');
		            $phone 					= 	$this->input->post('phone');
		            $country_id 			= 	$this->input->post('country_id');
		            $state_id 				= 	$this->input->post('state_id');
		            $city_id 				= 	$this->input->post('city_id');
		            $address 				= 	$this->input->post('address');
		            $postal_code 			= 	$this->input->post('postal_code');
		            $company 				= 	$this->input->post('company');
		            $gstid 					= 	$this->input->post('gstid');
		            $gst_registration_type 	= 	$this->input->post('gstregtype');

		            $additional_data		= 	array(
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
						redirect("supplier",'refresh');
		            }
		            else
		            {
		            	$this->session->set_flashdata('failure', 'We got problem while creating User.');
						redirect("supplier",'refresh');
		            }
				}

			}else{
				// $data['taxes'] 		  = $this->tax_model->getTax();
				$data['country']	= 	$this->supplier_model->getCountry();
				$this->load->view('supplier/add',$data);
			}
		}
	}

	public function edit($id = NULL)
	{

		if(!$this->user_model->has_permission("edit_supplier"))
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
					$this->load->view('supplier/edit');
				}
				else{

					$groupData  	= array();

		        	$id 			= $this->input->post('user_id');

					$email    		= strtolower($this->input->post('email'));
		            $identity 		= strtolower($this->input->post('email'));
		            $password 		= $this->input->post('password');
		            $group_id 		= $this->input->post('role_id');
		           

		            // $this->user_model->assign_warehouse($id,$warehouse_id);
		            
		           

		            $first_name 			= 	$this->input->post('first_name');
		            $last_name 				= 	$this->input->post('last_name');
		            $phone 					= 	$this->input->post('phone');
		            $country_id 			= 	$this->input->post('country_id');
		            $state_id 				= 	$this->input->post('state_id');
		            $city_id 				= 	$this->input->post('city_id');
		            $address 				= 	$this->input->post('address');
		            $postal_code 			= 	$this->input->post('postal_code');
		            $company 				= 	$this->input->post('company');
		            $gstid 					= 	$this->input->post('gstid');
		            $gst_registration_type 	= 	$this->input->post('gstregtype');

		            $additional_data		= 	array(
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

		            // echo '<pre>';
		            // print_r($data);
		            // exit;

		            if($this->ion_auth->update($id, $data))
		            {
		            	// $this->change_group($group_id,$id);
		            	$this->session->set_flashdata('success', $first_name.' is updated successfully.');
						redirect("supplier",'refresh');
		            }
		            else
		            {
		            	$this->session->set_flashdata('failure', 'We got problem while updating User.');
						redirect("supplier",'refresh');
		            }
				}
			}else{
				
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
				$this->load->view('supplier/edit',$data);
			}
		}
	}

	public function delete($id)
	{
		if(!$this->user_model->has_permission("delete_supplier"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
			if($this->ion_auth_model->deactivate($id)){
				$this->session->set_flashdata('success', 'Supplier deleted successfully.');
				redirect("supplier",'refresh');
			}else{
				$this->session->set_flashdata('failure', 'We got problem while creating User.');
				redirect("supplier",'refresh');
			}
		}
	}
	/* 
		This function id used to add supplier in darabase 
	*/
	// public function add_supplier_ajax(){
	// 		$ledger_data = array(
	// 				'title' 			=> strtoupper($this->input->post('supplier_name')),
	// 				'accountgroup_id' 	=> 25
	// 		);
	// 		$ledger_id = $this->ledger_model->addLedger($ledger_data);
	// 		$data = array(
	// 					"supplier_name"		=>	$this->input->post('supplier_name'),
	// 					"company_name"		=>	$this->input->post('company_name'),
	// 					"city_id"			=>	$this->input->post('city'),
	// 					"country_id"		=>	$this->input->post('country'),
	// 					"state_id"			=>	$this->input->post('state'),
	// 					"mobile"			=>	$this->input->post('mobile'),
	// 					"email"				=>	$this->input->post('email'),
	// 					"gstid"				=>  $this->input->post('gstid'),
	// 					"ledger_id"				=>  $ledger_id,
	// 					"gst_registration_type" =>	$this->input->post('gstregtype')
	// 				);
			
	// 		$id = $this->supplier_model->addModel($data); 
	// 		$log_data = array(
	// 				'user_id'  => $this->session->userdata('user_id'),
	// 				'table_id' => $id,
	// 				'message'  => 'Supplier Inserted'
	// 			);
	// 		$this->log_model->insert_log($log_data);
	// 		$data['data'] = $this->supplier_model->getSuppliers();
	// 		$data['id'] = $id;
	// 		$data['supplier_state_id'] = $this->supplier_model->getSupplierStateId($id);
			
	// 		//print_r($data);
	// 		echo json_encode($data);
	// }
	// /* 
	// 	This function id used to add supplier in darabase 
	// */
	// public function addSupplier(){
	// 	$this->form_validation->set_rules('supplier_name', 'Supplier Name', 'trim|required|min_length[3]');
		
	// 	$this->form_validation->set_rules('company_name', 'Company Name', 'trim|required|min_length[3]');
	// 	$this->form_validation->set_rules('address', 'Address', 'required');
	// 	$this->form_validation->set_rules('city', 'City', 'trim|required');
	// 	$this->form_validation->set_rules('country', 'Country', 'trim|required');
	// 	$this->form_validation->set_rules('state', 'State', 'trim|required');
	// 	$this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric');
	// 	$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
	// 	$this->form_validation->set_rules('postal_code', 'Postal Code', 'trim|required|numeric');

	// 	if ($this->form_validation->run() == FALSE)
 //        {    
 //                    $this->add();
 //         			//redirect('supplier');
 //        }
 //        else
 //        {
 //        	$ledger_data = array(
	// 				'title' 			=> strtoupper($this->input->post('supplier_name')),
	// 				'accountgroup_id' 	=> 25
	// 		);
	// 		$ledger_id = $this->ledger_model->addLedger($ledger_data);
	// 		$data = array(
	// 					"supplier_name"			=>	$this->input->post('supplier_name'),
	// 					"company_name"			=>	$this->input->post('company_name'),
	// 					"address"				=>	$this->input->post('address'),
	// 					"city_id"				=>	$this->input->post('city'),
	// 					"country_id"			=>	$this->input->post('country'),
	// 					"state_id"				=>	$this->input->post('state'),
	// 					"state_code"			=>	$this->input->post('state_code'),
	// 					"mobile"				=>	$this->input->post('mobile'),
	// 					"email"					=>	$this->input->post('email'),
	// 					"postal_code"			=>	$this->input->post('postal_code'),
	// 					"gstid"					=>  $this->input->post('gstid'),
	// 					"vat_no"    			=>	$this->input->post('vatno'),
	// 					"pan_no"    			=>	$this->input->post('panno'),
	// 					"tan_no"    			=>	$this->input->post('tan'),
	// 					"cst_reg_no"    		=>	$this->input->post('cstregno'),
	// 					"excise_reg_no"    		=>	$this->input->post('exciseregno'),
	// 					"lbt_reg_no"    		=>	$this->input->post('lbtregno'),
	// 					"servicetax_reg_no"    	=>	$this->input->post('servicetaxno'),
	// 					"ledger_id"				=>  $ledger_id,
	// 					"gst_registration_type" =>	$this->input->post('gstregtype')
	// 				);

	// 		if($id = $this->supplier_model->addModel($data)){ 
	// 			$log_data = array(
	// 					'user_id'  => $this->session->userdata('user_id'),
	// 					'table_id' => $id,
	// 					'message'  => 'Supplier Inserted'
	// 				);
	// 			$this->log_model->insert_log($log_data);
	// 			redirect('biller','refresh');
	// 		}
	// 		else{
	// 			$this->session->set_flashdata('fail', 'Supplier can not be Inserted.');
	// 			redirect("biller",'refresh');
	// 		}
	// 	}
	// }
	
	// /* 
	// 	call edit view to edit supplier 
	// */ 
	// public function edit($id){
	// 	$data['data'] = $this->supplier_model->getRecord($id);
	// 	$data['country']  = $this->supplier_model->getCountry();
	// 	$data['state'] = $this->supplier_model->getState($data['data'][0]->country_id);
	// 	$data['city'] = $this->supplier_model->getCity($data['data'][0]->state_id);
	// 	// echo '<pre>';
	// 	// print_r($data);
	// 	// exit;

	// 	$this->load->view('supplier/edit',$data);
	// }

	// /* 
	// 	This function is used to save edited sullpier record  
	// */
	// public function editSupplier(){
	// 	$id = $this->input->post('id');
	// 	$this->form_validation->set_rules('supplier_name', 'Supplier Name', 'trim|required|min_length[3]|callback_alpha_dash_space1');
		
	// 	$this->form_validation->set_rules('company_name', 'Company Name', 'trim|required|min_length[3]|callback_alpha_dash_space');
	// 	$this->form_validation->set_rules('address', 'Address', 'required');
	// 	$this->form_validation->set_rules('city', 'City', 'trim|required');
	// 	$this->form_validation->set_rules('country', 'Country', 'trim|required');
	// 	$this->form_validation->set_rules('state', 'State', 'trim|required');
	// 	$this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric');
	// 	$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
	// 	$this->form_validation->set_rules('postal_code', 'Postal Code', 'trim|required|numeric');

	// 	if ($this->form_validation->run() == FALSE)
 //        {    
 //                    $this->edit($id);
 //         			//redirect('supplier');
 //        }
 //        else
 //        {
	// 		$data = array(
	// 					"supplier_name"	=>	$this->input->post('supplier_name'),
	// 					"company_name"	=>	$this->input->post('company_name'),
	// 					"address"		=>	$this->input->post('address'),
	// 					"city_id"			=>	$this->input->post('city'),
	// 					"country_id"		=>	$this->input->post('country'),
	// 					"state_id"			=>	$this->input->post('state'),
	// 					"state_code"			=>	$this->input->post('state_code'),
	// 					"mobile"		=>	$this->input->post('mobile'),
	// 					"email"			=>	$this->input->post('email'),
	// 					"postal_code"	=>	$this->input->post('postal_code'),
	// 					"gstid"			=>	$this->input->post('gstid'),
	// 					"vat_no"    =>	$this->input->post('vatno'),
	// 					"pan_no"    =>	$this->input->post('panno'),
	// 					"tan_no"    =>	$this->input->post('tan'),
	// 					"cst_reg_no"    =>	$this->input->post('cstregno'),
	// 					"excise_reg_no"    =>	$this->input->post('exciseregno'),
	// 					"lbt_reg_no"    =>	$this->input->post('lbtregno'),
	// 					"servicetax_reg_no"    =>	$this->input->post('servicetaxno'),
	// 					"gst_registration_type" =>	$this->input->post('gstregtype'),
	// 					"supplier_id" 	=>	$this->input->post('id')
	// 					);
			
	// 		if($this->supplier_model->editModel($data,$id)){
	// 			$log_data = array(
	// 					'user_id'  => $this->session->userdata('user_id'),
	// 					'table_id' => $id,
	// 					'message'  => 'Supplier Updated'
	// 				);
	// 			$this->log_model->insert_log($log_data);
	// 			redirect('biller');
	// 		}
	// 		else{
	// 			$this->session->set_flashdata('fail', 'Supplier can not be Updated.');
	// 			redirect("biller",'refresh');
	// 		}
	// 	}
	// }
	/* 
		this function is used to delete supplier from database 
	*/
	// public function delete($id){
	// 	if($this->supplier_model->deleteModel($id)){
	// 		$log_data = array(
	// 				'user_id'  => $this->session->userdata('user_id'),
	// 				'table_id' => $id,
	// 				'message'  => 'Supplier Deleted'
	// 			);
	// 		$this->log_model->insert_log($log_data);
	// 		redirect('biller');
	// 	}
	// 	else{
	// 		$this->session->set_flashdata('fail', 'Supplier can not be Deleted.');
	// 		redirect("biller",'refresh');
	// 	}
	// }

	/*
		get all state of country
	*/
	public function getState($id){
		$data = $this->supplier_model->getState($id);
		echo json_encode($data);
	}
	public function getState1(){

		$data = $this->supplier_model->getState(1);
		echo json_encode($data);
	}

	/*
		get all city of state
	*/
	public function getCity($id){
		$data = $this->supplier_model->getCity($id);
		echo json_encode($data);
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
}
?>