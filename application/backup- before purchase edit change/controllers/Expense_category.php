<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Expense_category extends MY_Controller
{
	function __construct() {
		parent::__construct();
		
		$this->load->model('log_model');
		$this->load->model('ledger_model');
		$this->load->model('account_group_model');
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
		$this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('email_setup_model');
        $this->load->model('sms_setting_model');
        
	}
	public function index(){
		if(!$this->user_model->has_permission("list_expense_category"))
        {
            $this->load->view('layout/restricted'); 
        }
		$data['data'] = $this->expense_category_model->getExpenseCategory();
		$this->load->view('expense_category/list',$data);
	} 
	/* 
		call Add view to add category  
	*/
	public function add(){
		if(!$this->user_model->has_permission("add_expense_category"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	$data['account_groups'] = $this->account_group_model->get_records();
        	$this->load->view('expense_category/add',$data);	
        }
		
	} 
	/* 
		This function used to store category record in database  
	*/
	public function addExpenseCategory()
	{
        $this->form_validation->set_rules('category_name', 'Name', 'trim|required|min_length[3]|callback_alpha_dash_space');

        if ($this->form_validation->run() == FALSE)
        {
            $this->add();
        }
        else
        {
        	$account_group_id 	= $this->input->post('account_group_id');

        	$ledger_data = array(
								'title' 			=> strtoupper($this->input->post('category_name')),
								'opening_balance'   => 0.00,
								'closing_balance'   => 0.00,
								'accountgroup_id' 	=> $account_group_id
							);
			$ledger_id = $this->ledger_model->add_record($ledger_data);
			$data = array(
						"name"=>$this->input->post('category_name'),
						"ledger_id"=> $ledger_id
					);

			if($id = $this->expense_category_model->addModel($data))
			{
				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $id,
						'message'  => 'Expense Category Inserted'
					);
				$this->log_model->insert_log($log_data);
				$this->session->set_flashdata('success', 'Expense Category successfully Inserted.');
				redirect('expense_category','refresh');
			}
			else
			{
				$this->session->set_flashdata('fail', 'Expense Category can not be Inserted.');
				redirect("expense_category",'refresh');
			}
        }	
	}
	/* 
		call edit view to edit Category Record 
	*/
	public function edit($id){
		if(!$this->user_model->has_permission("edit_expense_category"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	$data['account_groups'] = $this->account_group_model->get_records();
        	$data['data'] 			= $this->expense_category_model->getRecord($id);
        	$data['ledger'] 		= $this->ledger_model->get_single_record($data['data']->ledger_id);
			
			$this->load->view('expense_category/edit',$data);	
        }
	}
	/* 
		This function is used to edit category record in database 
	*/
	public function editExpenseCategory(){
		
		$this->form_validation->set_rules('category_name', 'Category Name', 'trim|required|min_length[3]');

        if ($this->form_validation->run() == FALSE)
        {
            $this->edit($id);
        }
        else
        {
        	$account_group_id 	= $this->input->post('account_group_id');
        	$id 				= $this->input->post('expense_category_id');
        	$ledger_id			= $this->input->post('ledger_id');

        	$ledger_data = array(
								'title' 			=> strtoupper($this->input->post('category_name')),
								'accountgroup_id' 	=> $account_group_id
							);

			$this->ledger_model->edit_record($ledger_data,$ledger_id);

			$data = array(
							"name" => $this->input->post('category_name')
						);
			if($this->expense_category_model->editModel($data,$id)){
				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $id,
						'message'  => 'Expense Category Updated'
					);
				$this->log_model->insert_log($log_data);
				$this->session->set_flashdata('success', 'Expense Category successfully Updated.');
				redirect('expense_category','refresh');
			}
			else{
				$this->session->set_flashdata('fail', 'Expense Category can not be Updated.');
				redirect("expense_category",'refresh');
			}
		}
	}
	/* 
		Delete selected  Category Record 
	*/
	public function delete($id){
		if(!$this->user_model->has_permission("delete_expense_category"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	if($this->expense_category_model->delete_record($id)){
				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $id,
						'message'  => 'Expense Category Deleted'
					);
				$this->log_model->insert_log($log_data);
				$this->session->set_flashdata('success', 'Expense Category successfully Deleted.');
				redirect('expense_category');
			}
			else{
				$this->session->set_flashdata('fail', 'Expense Category can not be Deleted.');
				redirect("expense_category",'refresh');
			}	
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