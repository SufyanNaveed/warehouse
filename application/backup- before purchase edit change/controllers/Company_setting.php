<?php
defined('BASEPATH') OR exit('NO direct script access allowed');

class Company_setting extends MY_Controller
{
	function __construct(){
		parent::__construct();
		$this->load->model('company_setting_model');
		$this->load->model('log_model');
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
		$this->load->model('email_setup_model');
        $this->load->model('sms_setting_model');

		
	}
	public function index()
	{
		if(!$this->user_model->has_permission("company_setting"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	$data['country']  = $this->company_setting_model->getCountry();
			$data['state'] = $this->company_setting_model->getState();
			$data['city'] = $this->company_setting_model->getCity();
			$data['currency'] = $this->company_setting_model->getCurrency();

			$data['data'] = $this->company_setting_model->getData();
			
			// echo '<pre>';
			// print_r($data);
			// exit;
			$this->load->view('company_setting/add',$data);	
        }
		
	}


	/*
		get all state of country
	*/
	public function getState($id){
		$data = $this->company_setting_model->getState($id);
		echo json_encode($data);
	}
	/*
		get all city of state
	*/
	public function getCity($id){
		$data = $this->company_setting_model->getCity($id);
		echo json_encode($data);
	}
	/*
		update company details 
	*/
	public function add()
	{
		if(!$this->user_model->has_permission("company_setting"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
			if($_FILES["logo"]["name"]){
				$path_parts = pathinfo($_FILES["logo"]["name"]);
				date_default_timezone_set('Asia/Kolkata');
				$date = date_create();
				$image_path = $path_parts['filename'].'_'.date_timestamp_get($date).'.'.$path_parts['extension'];
		        $url =  "assets/images/".$image_path; 
		
				if(in_array($path_parts['extension'],array("jpg","jpeg","png"))){
							
					if(is_uploaded_file($_FILES["logo"]["tmp_name"])){
								
						if(move_uploaded_file($_FILES["logo"]["tmp_name"],$url)){
							$image_name = $url;	
						}
					}	
				}
			}
			else{
				$image_name = $this->input->post('hidden_logo_name');
			}
			if($_FILES["favico"]["name"]){
				$path_parts = pathinfo($_FILES["favico"]["name"]);
				date_default_timezone_set('Asia/Kolkata');
				$date = date_create();
				$favico_path = $path_parts['filename'].'_'.date_timestamp_get($date).'.'.$path_parts['extension'];
		        $url =  "assets/images/".$favico_path; 
		
				if(in_array($path_parts['extension'],array("jpg","jpeg","png"))){
							
					if(is_uploaded_file($_FILES["favico"]["tmp_name"])){
								
						if(move_uploaded_file($_FILES["favico"]["tmp_name"],$url)){
							$favico_name = $url;	
						}
					}	
				}
			}
			else{
				$favico_name = $this->input->post('hidden_favico');
			}

			
			
			$data = array(
					"name"             		=> $this->input->post('name'),
					"site_short_name"  		=> $this->input->post('short_name'),
					"gstin"		   		    => $this->input->post('gstin'),
					"drug_licence_number"	=> $this->input->post('drug_licence_number'),
					"drug_licence_number1"	=> $this->input->post('drug_licence_number1'),
					// "tan_no"		   		=> $this->input->post('tan'),
					// "cst_reg_no"    		=> $this->input->post('cstregno'),
					// "excise_reg_no"    		=> $this->input->post('exciseregno'),
					// "lbt_reg_no"    		=> $this->input->post('lbtregno'),
					// "servicetax_reg_no"    	=> $this->input->post('servicetaxno'),
					// "cin"    				=> $this->input->post('cin'),
					"gst_registration_type" => $this->input->post('gstregtype'),
					"email"            		=> $this->input->post('email'),
					"phone"			   		=> $this->input->post('mobile'),
					"street"		   		=> $this->input->post('street'),
					"city_id"          		=> $this->input->post('city'),
					"state_id"         		=> $this->input->post('state'),
					"state_code"         	=> $this->input->post('state_code'),
					"zip_code"         		=> $this->input->post('zip_code'),
					"country_id"       		=> $this->input->post('country'),
					"default_language" 		=> $this->input->post('language'),
					"default_currency" 		=> $this->input->post('currency'),
					"terms_condition" 		=> $this->input->post('details'),
					"bank_name" 		    => $this->input->post('bank'),
					"account_no" 		    => $this->input->post('account'),
					"branch_ifsccode" 		=> $this->input->post('branch'),
					"logo" 				    => $image_name,
					"favico" 				=> $favico_name,
					"theme" 				=> $this->input->post('theme')
				);

			// echo '<pre>';

			// // if($this->input->post('tax_1_status') == "")
			// // 	echo 'not';
			// print_r($data);
			// exit;
			if($this->company_setting_model->add($data)){
				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => 0,
						'message'  => 'Comapany Setting Updated'
					);
				$result =  $this->db->query('SELECT 
									c.name,c.symbol 
								FROM company_settings cs 
								INNER JOIN currency c ON c.id = cs.default_currency
								')->row();
				if($result->symbol=="INR"){
					$sym = "<span class='fa fa-rupee'></span>";
				}
				else{
					$sym = $result->symbol;
				}
				$data = array(
						'currency_name' => $result->name,
						'symbol' => $sym
					);
				$this->session->set_userdata($data);
				$this->log_model->insert_log($log_data);
				$this->session->set_flashdata('success', 'Company Settings Successfully Updated.');
				redirect('company_setting','refresh');
			}
			else{
				$this->session->set_flashdata('fail', 'Error in Update Comapany Settings.');
				redirect('company_setting','refresh');
			}
		}

	}
}

?>