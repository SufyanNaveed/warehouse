<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Uqc extends MY_Controller
{
	function __construct() {
		parent::__construct();
		$this->load->model('uqc_model');
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
		$data['data'] = $this->uqc_model->getUQc();
		$this->load->view('uqc/list',$data);
	}
	/*
		call add view
	*/
	public function add()
	{
		if(!$this->user_model->has_permission("add_uqc"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	$this->load->view('uqc/add');
        }
		
	}
	/*
		get new data
	*/
	public function addUqc(){
		$this->form_validation->set_rules('uom', 'UOM', 'trim|required');
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() == FALSE)
        {
            $this->add();
        }
        else
        {
			$data = array(
						"uom"=>$this->input->post('uom'),
						"description"=>$this->input->post('description')
					);

			if($id = $this->uqc_model->addModel($data)){ 
				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $id,
						'message'  => 'UQC Inserted'
					);
				$this->log_model->insert_log($log_data);
				redirect('uqc','refresh');
			}
			else{
				$this->session->set_flashdata('fail', 'UQC can not be Inserted.');
				redirect("uqc",'refresh');
			}
        }	
	}
	/*
		call edit
	*/
	public function edit($id)
	{
		if(!$this->user_model->has_permission("edit_uqc"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
			$data['data'] = $this->uqc_model->getRecord($id);
			$this->load->view('uqc/edit',$data);
		}
	}
	/*
		get new data
	*/
	public function editUqc()
	{
		$id = $this->input->post('id');
		$this->form_validation->set_rules('uom', 'UOM', 'trim|required');
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() == FALSE)
        {
            $this->edit($id);
        }
        else
        {
			$data = array(
						"uom"=>$this->input->post('uom'),
						"description"=>$this->input->post('description')
					);
			if($this->uqc_model->editModel($id,$data)){ 
				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $id,
						'message'  => 'UQC Updated'
					);
				$this->log_model->insert_log($log_data);
				redirect('uqc','refresh');
			}
			else{
				$this->session->set_flashdata('fail', 'UQC can not be Update.');
				redirect("uqc",'refresh');
			}
        }	
	}
	/* 
		Delete selected  UQC Record 
	*/
	public function delete($id)
	{
		if(!$this->user_model->has_permission("delete_uqc"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
			if($this->uqc_model->deleteModel($id)){
				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $id,
						'message'  => 'UQC Deleted'
					);
				$this->log_model->insert_log($log_data);
				redirect('uqc');
			}
			else{
				$this->session->set_flashdata('fail', 'UQC can not be Deleted.');
				redirect("uqc",'refresh');
			}
		}
	}
}
?>