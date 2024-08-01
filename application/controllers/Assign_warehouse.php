<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Assign_warehouse extends MY_Controller
{
	function __construct() {
		parent::__construct();
		
		
	}
	public function index(){
		$data['data'] = $this->assign_warehouse_model->getAssignWarehouse(); 
		$this->load->view('assign_warehouse/list',$data);
	}
	/*
		Display all Data
	*/
	public function add(){
		//get All User Name and Id		
		$data['user'] = $this->assign_warehouse_model->getUser();
		/*echo "<pre>";
		print_r($data);
		exit(); */
		//get All Warehouse Name And Id
		//$data['warehouse'] = $this->assign_warehouse_model->getWarehouse(); 
		$this->load->view('assign_warehouse/add',$data);
	}
	/*
		return not assign warehouse
	*/
	public function getWarehouse($id){
		$data = $this->assign_warehouse_model->getWarehouse($id);
		//log_message('debug',print_r($data,true));
		echo json_encode($data);
	}
	/* 
		Add warehouse in database. 
	*/
	public function assignWarehouse()
	{ 
		$user_id 		= $this->input->post('user_id');
		$warehouse_id 	= $this->input->post('warehouse_id');
		

		$data 			= array(
							"user_id" 		=> $user_id,
							"warehouse_id" 	=> $warehouse_id
						);
		$user 			  = $this->user_model->get_single_user_record($user_id);
		$assign_warehouse = $this->assign_warehouse_model->get_single_record($warehouse_id);
		/*echo "<pre>";
		print_r($user);
		exit;*/

		/*$user = $this->session->userdata('user_id');*/
		if($id = $this->assign_warehouse_model->assignWarehouse($data)){
			$log_data = array(
					'user_id'  => $this->session->userdata('user_id'),
					'table_id' => $id,
					'message'  => $user->first_name.$user->last_name.' is assigned to '.$assign_warehouse->warehouse_name
				);
			$this->log_model->insert_log($log_data);
			$this->session->set_flashdata('success', $user->first_name.$user->last_name.' is assigned to '.$assign_warehouse->warehouse_name);
			redirect('assign_warehouse','refresh');
		}
		else{
			$this->session->set_flashdata('failure', $user->first_name.$user->last_name.' is failed to assigned '.$assign_warehouse->warehouse_name);
			redirect("assign_warehouse",'refresh');
		}
	}
	/*
		delete assign warehouse from database
	*/
	public function delete($id){
		if($this->assign_warehouse_model->deleteModel($id)){
			$log_data = array(
					'user_id'  => $this->session->userdata('user_id'),
					'table_id' => $id,
					'message'  => 'Assign Warehouse deleted'
				);
			$this->log_model->insert_log($log_data);
			redirect('assign_warehouse','refresh');
		}
		else{
			$this->session->set_flashdata('fail', 'Assign Warehouse can not be Deleted.');
			redirect("assign_warehouse",'refresh');
		}
	}
	/*

	*/
	public function assign_warehouse_sort()
	{
		$user_id 		= $this->input->post('user_id');
		$warehouse_id 	= $this->input->post('warehouse_id');

		$data 			= array(
							"user_id" 		=> $user_id,
							"warehouse_id" 	=> $warehouse_id
						);
		
		$id = $this->assign_warehouse_model->assignWarehouse($data);
		$log_data = array(
				'user_id'  => $this->session->userdata('user_id'),
				'table_id' => $id,
				'message'  => 'Assign Warehouse Inserted Sort Cut'
			);
		$this->log_model->insert_log($log_data);
	}
}
?>