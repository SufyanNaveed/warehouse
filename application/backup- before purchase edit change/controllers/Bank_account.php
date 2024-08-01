<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_account extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('form_validation','ion_auth'));
		$this->load->model('bank_account_model');
		$this->load->model('log_model');
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
		$this->load->model('email_setup_model');
        $this->load->model('sms_setting_model');
        

		$this->load->model('ledger_model');

		
	}
	/*
		View list of Bank Account data
	*/
	public function index()
	{
		if(!$this->user_model->has_permission("list_bank_account"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	$data['bank_accounts'] = $this->bank_account_model->get_records();
			$this->load->view('bank_account/list',$data);	
        }
	}

	/*
		View Add new account bank form
	*/
	public function add()
	{
		if(!$this->user_model->has_permission("add_bank_account"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {	
        	if($_POST)
        	{
        		$this->form_validation->set_rules('account_name','Account Name','trim|required');
				$this->form_validation->set_rules('type','Account Type','trim|required');
				$this->form_validation->set_rules('account_number','Account Number','trim|required');
				$this->form_validation->set_rules('bank_name','Bank Name','trim|required');
				$this->form_validation->set_rules('balance','Opening Balance','trim|required');
				$this->form_validation->set_rules('address','Bank Address','trim|required');
				$this->form_validation->set_rules('account_group_id','Account Group','trim|required');
				
				if($this->form_validation->run()==FALSE)
				{
					$this->add();
				}
				else
				{
					$ledger_data = array(
										'title' 			=> strtoupper($this->input->post('bank_name')),
										'opening_balance'   => $this->input->post('balance'),
										'closing_balance'   => $this->input->post('balance'),
										'accountgroup_id' 	=> $this->input->post('account_group_id')
									);
					$ledger_id = $this->ledger_model->add_record($ledger_data);
					$data = array(
									'account_name'=>$this->input->post('account_name'),					
									'account_type'=>$this->input->post('type'),
									'account_no'=>$this->input->post('account_number'),
									'bank_name'=>$this->input->post('bank_name'),
									'bank_address'=>$this->input->post('address'),
									'opening_balance'=>$this->input->post('balance'),
									'default_account'=>$this->input->post('default'),
									'ledger_id'=> $ledger_id
								);
					if($id = $this->bank_account_model->add_record($data))
					{
						$log_data = array(
								'user_id'  => $this->session->userdata('user_id'),
								'table_id' => $id,
								'message'  => 'Bank Account Inserted'
							);
						$this->log_model->insert_log($log_data);
						
						$this->session->set_flashdata('success', 'Bank Account Add successfully.');
			           	redirect("bank_account",'refresh');
					}
					else
					{
						$this->session->set_flashdata('failure', 'Bank Account Add Failed.');
			           	redirect("bank_account",'refresh');
					}
				}
        	}
        	else
        	{
        		$data['account_groups'] = $this->account_group_model->get_records();
        		$this->load->view('bank_account/add',$data);			
        	}
        }
	}

	public function edit($id = NULL)
	{
		if(!$this->user_model->has_permission("edit_bank_account"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {	
        	if($_POST)
        	{
        		$this->form_validation->set_rules('account_name','Account Name','trim|required');
				$this->form_validation->set_rules('type','Account Type','trim|required');
				$this->form_validation->set_rules('account_number','Account Number','trim|required');
				$this->form_validation->set_rules('bank_name','Bank Name','trim|required');
				$this->form_validation->set_rules('balance','Opening Balance','trim|required');
				$this->form_validation->set_rules('address','Bank Address','trim|required');
				$this->form_validation->set_rules('account_group_id','Account Group','trim|required');
				
				if($this->form_validation->run()==FALSE)
				{
					$this->edit($id);
				}
				else
				{	
					$id 				= $this->input->post("bank_account_id");
					$account_group_id 	= $this->input->post("account_group_id");

					$ledger_id 	= $this->input->post("ledger_id");
					$ledger 	= $this->ledger_model->get_single_record($ledger_id);

					$current_op = floatval($this->input->post('balance'));
					$old_op_bl  = $ledger->opening_balance;
					$old_cl_bl  = $ledger->closing_balance;
					
					if($old_op_bl > $current_op)
					{
						$old_cl_bl = $old_cl_bl - $old_op_bl - $current_op;
					}
					else if($old_op_bl < $current_op)
					{
						
						$old_cl_bl = $old_cl_bl + $current_op - $old_op_bl;
						$old_op_bl = $current_op;
					}

					$ledger_data = array(
											'title' 			=> strtoupper($this->input->post('bank_name')),
											'opening_balance'   => $old_op_bl,
											'closing_balance'   => $old_cl_bl,
											'accountgroup_id' 	=> $account_group_id
									);

					$this->ledger_model->edit_record($ledger_data,$ledger_id);

					$data = array(
									'account_name'=>$this->input->post('account_name'),					
									'account_type'=>$this->input->post('type'),
									'account_no'=>$this->input->post('account_number'),
									'bank_name'=>$this->input->post('bank_name'),
									'bank_address'=>$this->input->post('address'),
									'opening_balance'=>$this->input->post('balance'),
									'default_account'=>$this->input->post('default')
								);

					if($this->bank_account_model->edit_record($data,$id))
					{
						$log_data = array(
										'user_id'  => $this->session->userdata('user_id'),
										'table_id' => $id,
										'message'  => 'Bank Account Inserted'
									);
						$this->log_model->insert_log($log_data);
						
						$this->session->set_flashdata('success', 'Bank Account updated successfully.');
			           	redirect("bank_account",'refresh');
					}
					else
					{
						$this->session->set_flashdata('failure', 'Bank Account update to fail.');
			           	redirect("bank_account",'refresh');
					}
				}
        	}
        	else
        	{
        		$data['bank_account'] 	= $this->bank_account_model->get_single_record($id);
        		$data['ledger'] 		= $this->ledger_model->get_single_record($data['bank_account']->ledger_id);
        		$data['account_groups'] = $this->account_group_model->get_records();
        		$this->load->view('bank_account/add',$data);			
        	}
        }
	}

	
	/*
		Delete bank account data delete_status=1
	*/
	public function delete($id)
	{
		if(!$this->user_model->has_permission("delete_bank_account"))
        {
            $this->load->view('layout/restricted'); 
        }
        $bank_account = $this->bank_account_model->get_single_record($id);
       	if($this->bank_account_model->delete_record($id))
       	{

       		$this->ledger_model->delete_record($bank_account->ledger_id);
       		$log_data = array(
					'user_id'  => $this->session->userdata('user_id'),
					'table_id' => $id,
					'message'  => 'Bank Account Deleted'
				);
			$this->log_model->insert_log($log_data);
           	$this->session->set_flashdata('success', 'Bank Account Deleted successfully.');
           	redirect("bank_account",'refresh');
	   	}
	   	else
		{
			$this->session->set_flashdata('success', 'Failed to delete Record.');
            redirect("bank_account",'refresh');	
		}	
	}
}