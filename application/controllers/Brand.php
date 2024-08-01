<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Brand extends MY_Controller
{
	function __construct() {
		parent::__construct();
		
	}
	public function index(){
		//get All Category  to display list
		if(!$this->user_model->has_permission("list_brand"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	$data['data'] = $this->brand_model->getBrand();
			$this->load->view('brand/list',$data);	
        }
		
	} 
	/* 
		call Add view to add category  
	*/
	public function add(){
		if(!$this->user_model->has_permission("add_brand"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	$this->load->view('brand/add');	
        }
		
	} 
	/*
		add brand ajax
	*/
	public function add_brand_ajax(){
		$data = array(
					"brand_name"=>$this->input->post('brand_name')
				);
		$id = $this->brand_model->addModel($data);
		$log_data = array(
				'user_id'  => $this->session->userdata('user_id'),
				'table_id' => $id,
				'message'  => 'Brand Inserted(Product)'
			);
		$this->log_model->insert_log($log_data);
		$data['data'] = $this->brand_model->getBrand();
		$data['id'] = $id;
		echo json_encode($data);
	}
	/* 
		This function used to store category record in database  
	*/
	public function add_brand(){
        $this->form_validation->set_rules('brand_name', 'Brand Name', 'trim|required|min_length[3]|callback_alpha_dash_space');
        if ($this->form_validation->run() == FALSE)
        {
            $this->add();
        }
        else
        {
			//$category_code = $this->brand_model->getMaxId();
			$brand_name 	= $this->input->post('brand_name');

			$data 			= array(
						
								"brand_name"=> $brand_name
							);

			if($id = $this->brand_model->addModel($data)){ 
				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $id,
						'message'  => $brand_name.' is added successfully.'
					);
				$this->log_model->insert_log($log_data);

				$this->session->set_flashdata('success', $brand_name.' is added successfully.');
				redirect('brand','refresh');
			}
			else{
				$this->session->set_flashdata('failure', $brand_name.' is failed to added.');
				redirect("brand",'refresh');
			}
        }	
		
	}
	/* 
		call edit view to edit Category Record 
	*/
	public function edit($id){
		if(!$this->user_model->has_permission("edit_brand"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	$data['data'] = $this->brand_model->getRecord($id);
			$this->load->view('brand/edit',$data);		
        }
		
	}
	/* 
		This function is used to edit category record in database 
	*/
	public function edit_brand(){
		$id = $this->input->post('id');
		$this->form_validation->set_rules('brand_name', 'Brand Name', 'trim|required|min_length[3]|callback_alpha_dash_space');
        if ($this->form_validation->run() == FALSE)
        {
            $this->edit($id);
        }
        else
        {
        	$brand_name 	= $this->input->post('brand_name');

			$data 			= array(
								"brand_name"=>$brand_name
								);

			if($this->brand_model->editModel($data,$id)){
				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $id,
						'message'  => $brand_name.' is updated successfully.'
					);
				$this->log_model->insert_log($log_data);

				$this->session->set_flashdata('success', $brand_name.' is updated successfully.');
				redirect('brand','refresh');
			}
			else{
				$this->session->set_flashdata('failure', $brand_name.' is failed to update.');
				redirect("brand",'refresh');
			}
		}
	}
	/* 
		Delete selected  Category Record 
	*/
	public function delete($id){
		if(!$this->user_model->has_permission("delete_brand"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	$brand = $this->brand_model->get_single_record($id);

        	if($this->brand_model->deleteModel($id))
        	{
				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $id,
						'message'  => $brand->brand_name.' is successfully deleted.'
					);
				$this->log_model->insert_log($log_data);


				$this->session->set_flashdata('success', $brand->brand_name.' is successfully deleted.');
				redirect('brand');
			}
			else
			{
				$this->session->set_flashdata('failure', $brand->brand_name.' is failed to delete.');
				redirect("brand",'refresh');
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