<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Uqc extends MY_Controller
{
	function __construct() {
		parent::__construct();
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
        	$uom 			= 	$this->input->post('uom');
        	$description 	= 	$this->input->post('description');
			$data 			= 	array(
									"uom"		 =>$uom,
									"description"=>$description
								);

			if($id = $this->uqc_model->addModel($data)){ 
				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $id,
						'message'  => $uom.' is added successfully.'
					);
				$this->log_model->insert_log($log_data);

				$this->session->set_flashdata('success', $uom.' is added successfully.');
				redirect('uqc','refresh');
			}
			else{
				$this->session->set_flashdata('failure', $uom.' is failed to add.');
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
			$uom 			= 	$this->input->post('uom');
        	$description 	= 	$this->input->post('description');

			$data 			= 	array(
									"uom"		 =>$uom,
									"description"=>$description
								);

			if($this->uqc_model->editModel($id,$data)){ 
				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $id,
						'message'  => $uom.' is Updated successfully.'
					);
				$this->log_model->insert_log($log_data);

				$this->session->set_flashdata('success', $uom.' is Updated successfully.');
				redirect('uqc','refresh');
			}
			else{
				$this->session->set_flashdata('failure', $uom.' is failed to update.');
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
        	$uqc 	= 	$this->uqc_model->getRecord($id);
        	
			if($this->uqc_model->deleteModel($id)){
				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $id,
						'message'  => 'UQC Deleted'
					);
				$this->log_model->insert_log($log_data);

				$this->session->set_flashdata('success', $uqc->uom.' is Deleted successfully.');
				redirect('uqc');
			}
			else{
				$this->session->set_flashdata('failure', $uqc->uom.' is failed to delete.');
				redirect("uqc",'refresh');
			}
		}
	}
}
?>