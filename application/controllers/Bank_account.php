<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_account extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		
		
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
					$title				=	strtoupper($this->input->post('bank_name'));
					$opening_balance	=	$this->input->post('balance');
					$closing_balance	=	$this->input->post('balance');
					$accountgroup_id 	=	$this->input->post('account_group_id');

					$ledger_data 		=   array(
												'title' 			=> $title,
												'opening_balance'   => $opening_balance,
												'closing_balance'   => $closing_balance,
												'accountgroup_id' 	=> $accountgroup_id
											);
					$ledger_id			= 	$this->ledger_model->add_record($ledger_data);

					$account_name 		= 	$this->input->post('account_name');
					$account_type 		= 	$this->input->post('type');
					$account_no 		= 	$this->input->post('account_number');
					$bank_name 			= 	$this->input->post('bank_name');
					$bank_address 		= 	$this->input->post('address');
					$opening_balance 	= 	$this->input->post('balance');
					$default_account 	= 	$this->input->post('default');

					$data 				= 	array(
												'account_name'		=> $account_name,					
												'account_type'		=> $account_type,
												'account_no'		=> $account_no,
												'bank_name'			=> $bank_name,
												'bank_address'		=> $bank_address,
												'opening_balance'	=> $opening_balance,
												'default_account'	=> $default_account,
												'ledger_id'			=> $ledger_id
											);
					if($id = $this->bank_account_model->add_record($data))
					{
						$log_data = array(
								'user_id'  => $this->session->userdata('user_id'),
								'table_id' => $id,
								'message'  =>  $account_name.' is Add successfully.'
							);
						$this->log_model->insert_log($log_data);
						
						$this->session->set_flashdata('success', $account_name.' is Add successfully.');
			           	redirect("bank_account",'refresh');
					}
					else
					{
						$this->session->set_flashdata('failure', $account_name.' is Add Failed.');
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

					$account_name 		= 	$this->input->post('account_name');
					$account_type 		= 	$this->input->post('type');
					$account_no 		= 	$this->input->post('account_number');
					$bank_name 			= 	$this->input->post('bank_name');
					$bank_address 		= 	$this->input->post('address');
					$opening_balance 	= 	$this->input->post('balance');
					$default_account 	= 	$this->input->post('default');

					$data 				= 	array(
												'account_name'		=> $account_name,					
												'account_type'		=> $account_type,
												'account_no'		=> $account_no,
												'bank_name'			=> $bank_name,
												'bank_address'		=> $bank_address,
												'opening_balance'	=> $opening_balance,
												'default_account'	=> $default_account
											);

					if($this->bank_account_model->edit_record($data,$id))
					{
						$log_data = array(
										'user_id'  => $this->session->userdata('user_id'),
										'table_id' => $id,
										'message'  => $account_name.' is updated successfully.'
									);
						$this->log_model->insert_log($log_data);
						
						$this->session->set_flashdata('success', $account_name.' is updated successfully.');
			           	redirect("bank_account",'refresh');
					}
					else
					{
						$this->session->set_flashdata('failure', $account_name.' is  update to fail.');
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
					'message'  => $bank_account->account_name.' is Deleted successfully.'
				);
			$this->log_model->insert_log($log_data);
           	$this->session->set_flashdata('success', $bank_account->account_name.' is Deleted successfully.');
           	redirect("bank_account",'refresh');
	   	}
	   	else
		{
			$this->session->set_flashdata('failure', $bank_account->account_name.' is 
				failed to delete.');
            redirect("bank_account",'refresh');	
		}	
	}
}