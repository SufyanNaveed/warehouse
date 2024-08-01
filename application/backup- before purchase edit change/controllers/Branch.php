<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Branch extends MY_Controller
{
	function __construct() {
		parent::__construct();
		$this->load->model('company_setting_model');
		$this->load->model('account_group_model');
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
	public function index(){
		//get Branch Name and Id 
		if(!$this->user_model->has_permission("list_branch"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
			$data['data'] = $this->branch_model->getBranch();
			$this->load->view('branch/list',$data);
		}
	} 
	/* 
		call add view to add Branch 
	*/
	public function add(){
		if(!$this->user_model->has_permission("add_branch"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
			$data['country']  = $this->company_setting_model->getCountry();
			$this->load->view('branch/add',$data);
		}
	} 

	/* 
		this function is used to count the number of branches 
	*/
	public function getBranchCount(){
		return sizeof($this->warehouse_model->getBranchCount());
	}
	/*  
		Add Benach Record in Database 
	*/
	public function addBranch()
	{
		$this->form_validation->set_rules('branch_name', 'Branch Name', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('city', 'City', 'trim|required');
		$this->form_validation->set_rules('state', 'State', 'trim|required');
		$this->form_validation->set_rules('country', 'Country', 'trim|required');
		$this->form_validation->set_rules('address', 'Branch Address', 'trim|required');
		// $this->form_validation->set_rules('gstin', 'GSTIN', 'trim|required');

		if ($this->form_validation->run() == FALSE)
        {
            $this->add();
        }
        else
        {	
        	
    		$data = array(
				"branch_name" 	=> $this->input->post('branch_name'),
				"gstin" 		=> $this->input->post('gstin'),
				"city_id"       => $this->input->post('city'),
				"state_id"      => $this->input->post('state'),
				"country_id"    => $this->input->post('country'),
				"address"  	  	=> $this->input->post('address')
			);

    	
    		if($id = $this->branch_model->addModel($data))
    		{
				$branch  		= $this->branch_model->get_single_record($id);
				$account_groups = $this->account_group_model->get_records_by_unique_group_title();
				
				$account_group_id = $this->account_group_model->get_record_by_group_title("Sales Accounts")->id;
				// echo '<pre>';
				// print_r($branch);
				// print_r($account_group_id);
				// echo $this->db->last_query();
				// exit;
				$ledger_data = array(
							'title' 			=> strtoupper($branch->branch_name),
							'opening_balance'   => 0.00,
							'closing_balance'   => 0.00,
							'accountgroup_id' 	=> $account_group_id
						);
				$ledger_id = $this->ledger_model->add_record($ledger_data);

				$branch_data = array(
									"ledger_id" => $ledger_id
								);	
				$this->branch_model->edit_record(array("ledger_id"=>$ledger_id),$id);

				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $id,
						'message'  => 'Branch Inserted'
					);
				$this->log_model->insert_log($log_data);
				redirect('branch');
			}
			else
			{
				$this->session->set_flashdata('fail', 'Branch can not be Inserted.');
				redirect("branch",'refresh');
			}
		}
	}
	/*  
		call Edit view to edit record 
	*/
	public function edit($id)
	{
		if(!$this->user_model->has_permission("edit_branch"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	$data['data'] = $this->branch_model->getRecord($id);
			$data['country']  = $this->company_setting_model->getCountry();
			$data['state'] = $this->company_setting_model->getState($data['data'][0]->country_id);
			$data['city'] = $this->company_setting_model->getCity($data['data'][0]->state_id);
			$this->load->view('branch/edit',$data);		
        }
		
	}
	
	public function editBranch(){
		$id = $this->input->post('id');
		$this->form_validation->set_rules('branch_name', 'Branch Name', 'trim|required|min_length[3]|callback_alpha_dash_space');
		$this->form_validation->set_rules('city', 'City', 'trim|required');
		$this->form_validation->set_rules('state', 'State', 'trim|required');
		$this->form_validation->set_rules('country', 'Country', 'trim|required');
		$this->form_validation->set_rules('address', 'Branch Address', 'trim|required');
		$this->form_validation->set_rules('gstin', 'GSTIN', 'trim|required');
		
		if ($this->form_validation->run() == FALSE)
        {
            $this->edit($id);
        }
        else
        {
			$data = array(
					"branch_name" 	=> $this->input->post('branch_name'),
					"gstin" 		=> $this->input->post('gstin'),
					"city_id"       => $this->input->post('city'),
					"state_id"      => $this->input->post('state'),
					"country_id"    => $this->input->post('country'),
					"address"  	  	=> $this->input->post('address')
				);
			if($this->branch_model->editModel($data,$id)){
				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $id,
						'message'  => 'Branch Updated'
					);
				$this->log_model->insert_log($log_data);
				redirect('branch');
			}
			else{
				$this->session->set_flashdata('fail', 'Branch can not be Updated.');
				redirect("branch",'refresh');
			}
		}
	}
	/* 
		Display selected  Branch Record 
	*/
	public function single($id){
		$data['data'] = $this->branch_model->getRecord($id);
		$this->load->view('header');
		$this->load->view('branch/single',$data);
		$this->load->view('footer');
	}
	/* 
		Delete selected  Branch Record 
	*/
	public function delete($id)
	{
		if(!$this->user_model->has_permission("delete_branch"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	if($this->branch_model->deleteModel($id))
        	{
				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $id,
						'message'  => 'Branch Deleted'
					);
				$this->log_model->insert_log($log_data);
				redirect('branch');
			}
			else
			{
				$this->session->set_flashdata('fail', 'Branch can not be Deleted.');
				redirect("branch",'refresh');
			}	
        }
		
	}

	public function get_default_account_group()
	{
		$json_accountgroup = '[{"group_title":"Bank Accounts","category":"Income","opening_balance":"0.000"},{"group_title":"Bank OCC A\/c","category":"Income","opening_balance":"0.000"},{"group_title":"Bank OD A\/c","category":"Income","opening_balance":"0.000"},{"group_title":"Branch\/Divisons","category":"Expense","opening_balance":"0.000"},{"group_title":"Capital Account","category":"Liabilities","opening_balance":"0.000"},{"group_title":"Cash-in-hand","category":"Assets","opening_balance":"0.000"},{"group_title":"Current Assets","category":"Assets","opening_balance":"0.000"},{"group_title":"Current Liabilities","category":"Liabilities","opening_balance":"0.000"},{"group_title":"Deposits","category":"Assets","opening_balance":"0.000"},{"group_title":"Direct Expenses","category":"Income","opening_balance":"0.000"},{"group_title":"Direct Incomes","category":"Income","opening_balance":"0.000"},{"group_title":"Duties & Taxes","category":"Expense","opening_balance":"0.000"},{"group_title":"Expenses(Direct)","category":"Expense","opening_balance":"0.000"},{"group_title":"Expenses(Indirect)","category":"Expense","opening_balance":"0.000"},{"group_title":"Fixed Asstes","category":"Assets","opening_balance":"0.000"},{"group_title":"Income(Direct)","category":"Income","opening_balance":"0.000"},{"group_title":"Income(Indirect)","category":"Income","opening_balance":"0.000"},{"group_title":"Indirect Expenses","category":"Expense","opening_balance":"0.000"},{"group_title":"Indirect Incomes","category":"Income","opening_balance":"0.000"},{"group_title":"Investments","category":"Assets","opening_balance":"0.000"},{"group_title":"Loans & Advances","category":"Assets","opening_balance":"0.000"},{"group_title":"Loans","category":"Liabilities","opening_balance":"0.000"},{"group_title":"Misc. Expenses","category":"Assets","opening_balance":"0.000"},{"group_title":"Provisions","category":"Assets","opening_balance":"0.000"},{"group_title":"Purchase Accounts","category":"Expense","opening_balance":"0.000"},{"group_title":"Reserves & Surplus","category":"Income","opening_balance":"0.000"},{"group_title":"Retained Ernings","category":"Assets","opening_balance":"0.000"},{"group_title":"Sales Accounts","category":"Income","opening_balance":"0.000"},{"group_title":"Secured Loans","category":"Assets","opening_balance":"0.000"},{"group_title":"Stock-in-hand","category":"Assets","opening_balance":"0.000"},{"group_title":"Sundry Creditors","category":"Expense","opening_balance":"0.000"},{"group_title":"Sundry Debitors","category":"Income","opening_balance":"0.000"},{"group_title":"Suspense A\/c","category":"Assets","opening_balance":"0.000"},{"group_title":"Unsecured Loans","category":"Assets","opening_balance":"0.000"},{"group_title":"Salary","category":"Liabilities","opening_balance":"0.000"}]';

        		
		return json_decode($json_accountgroup);
	}

	public function ajax_get_company_setting_record()
	{
		echo json_encode($this->company_setting_model->getData());
	}

	function alpha_dash_space($str) 
	{
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

	function alpha_space_city($str) 
	{
		if (! preg_match("/^([a-zA-Z ])+$/i", $str))
	    {
	        $this->form_validation->set_message('alpha_dash_space', 'The %s field may only contain alpha and spaces.');
	        return FALSE;
	    }
	    else
	    {
	        return TRUE;
	    }
	}
}
?>