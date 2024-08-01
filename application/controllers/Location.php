<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Location extends MY_Controller
{
	function __construct() {
		parent::__construct();
		
    }
	public function index(){
		if(!$this->user_model->has_permission("list_location"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	$data['data'] = $this->location_model->getCountries();
			$this->load->view('location/country/index',$data);	
        }
		
	}
	public function country(){
		if(!$this->user_model->has_permission("list_location"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	$data['data'] = $this->location_model->getCountries();
			$this->load->view('location/country/index',$data);	
        }
	}
	public function state($id = NULL)
	{
		if(!$this->user_model->has_permission("list_location"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	if($id==NULL)
        	{
				$this->country();
			}
			else
			{
				$data['data'] = $this->location_model->getStates($id);
				$data['country_id'] = $id;
				$this->load->view('location/state/index',$data);
			}	
        }
	} 

	public function get_state_ajax($id)
	{
		echo json_encode($this->location_model->getStates($id));
	}

	public function city($id = NULl)
	{
		if(!$this->user_model->has_permission("list_location"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	if($id==NULL)
			{
				$this->country();
			}
			else
			{
				$data['data'] 		= $this->location_model->getCities($id);
				$data['state_id'] 	= $id;
				$data['state_code'] = $this->location_model->getStateCode($id);
				$data['country_id'] = $this->location_model->getStateRecord($id)->country_id;

				$this->load->view('location/city/index',$data);
			}
        }
	} 

	public function get_city_ajax($id)
	{
		$data 		= $this->location_model->getCities($id);
		$state_id 	= $id;
		$country_id = $this->location_model->getStateRecord($id)->country_id;
		$state_code = $this->location_model->getStateCode($id);

		echo json_encode(
							array(
								"data" 			=> $data,
								"state_id" 		=> $id, 
								"country_id" 	=> $country_id,
								"state_code" 	=> $state_code
							)
						);
	}
	
	/* 
		call Add view to add category  
	*/
	public function addCountry($id = NULL){

		if(!$this->user_model->has_permission("add_location"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	if($id == NULL){
				if($_POST) {

					$this->form_validation->set_rules('name', 'Country Name', 'trim|required|min_length[3]|callback_alpha_dash_space');
					$this->form_validation->set_rules('sortname', 'Short Name', 'trim|required');
					$this->form_validation->set_rules('phonecode', 'Short Name', 'trim|required|numeric');

					if ($this->form_validation->run() == FALSE){
						$this->addCountry();
					}else{

						$country_id = $this->input->post("country_id");
						$name 		= $this->input->post("name");
						$sortname 	= $this->input->post("sortname");
						$phonecode	= $this->input->post("phonecode");

						$data = array(
									"name" 		=> $name,
									"sortname" 	=> $sortname,
									"phonecode"	=> $phonecode
								);

						if($country_id == ""){					
							if($this->location_model->addCountryModel($data)){
								$this->session->set_flashdata('success', $name.' is added successfully');
								redirect('location/country');		
							}else{
								$this->session->set_flashdata('failure', $name.' is failed to add');
								redirect('location/country');		
							}
						}else{
							if($this->location_model->editCountryModel($data,$country_id)){
								$this->session->set_flashdata('success', $name.' is updated successfully');
								redirect('location/country');		
							}else{
								$this->session->set_flashdata('failure', $name.' is failed to update');
								redirect('location/country');		
							}
						}
					}
					
		        }else{
		            $this->load->view('location/country/add');
		        }
			}else{ 
				$country_id = $id;
				$data["data"] = $this->location_model->getCountryRecord($country_id);
				$this->load->view('location/country/add',$data);
			}
        }

		
	}

	public function addState($id = NULL){

		if(!$this->user_model->has_permission("add_location"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	if($id == NULL)
        	{
				if($_POST) 
				{
					$this->form_validation->set_rules('name', 'State Name', 'trim|required|min_length[3]');
					$this->form_validation->set_rules('country_id', 'Country', 'trim|required');

					if ($this->form_validation->run() == FALSE){
						$this->addCountry();
					}else{

						$state_id 	= $this->input->post("state_id");
						$country_id	= $this->input->post("country_id");
						$name 		= $this->input->post("name");

						$data = array(
									"name" 		=> $name,
									"country_id"=> $country_id
								);

						if($state_id == ""){					
							if($this->location_model->addStateModel($data)){
								$this->session->set_flashdata('success', $name.' is successfully saved');
								redirect('location/state/'.$country_id);		
							}else{
								$this->session->set_flashdata('failure', ' is failed to add.');
								redirect('location/state');		
							}
						}else{
							if($this->location_model->editStateModel($data,$state_id)){
								$this->session->set_flashdata('success', $name.' is successfully updated');
								redirect('location/state/'.$country_id);		
							}else{
								$this->session->set_flashdata('failure', $name.' is failed to update');
								redirect('location/state');		
							}
						}
					}
		        }else{
		        	$data["countries"] = $this->location_model->getCountries();
		        	// echo '<pre>';
		        	// print_r($data);
		        	// exit;
		            $this->load->view('location/state/add',$data);
		        }
			}else{ 
				$state_id = $id;
				$data["countries"] 	= $this->location_model->getCountries();
				$data["data"] 		= $this->location_model->getStateRecord($state_id);
				$this->load->view('location/state/add',$data);
			}	
        }

		
	}

	public function addCity($id = NULL){

		if(!$this->user_model->has_permission("add_location"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	if($id == NULL)
        	{

				if($_POST) 
				{

					$this->form_validation->set_rules('name', 'City Name', 'trim|required|min_length[3]');
					$this->form_validation->set_rules('state_id', 'State', 'trim|required');

					if ($this->form_validation->run() == FALSE){
						$this->addCountry();
					}else{

						$city_id 	= $this->input->post("city_id");
						$state_id 	= $this->input->post("state_id");
						$name 		= $this->input->post("name");

						$data = array(
									"name" 		=> $name,
									"state_id"	=> $state_id
								);

						if($city_id == ""){					
							if($this->location_model->addCityModel($data)){
								$this->session->set_flashdata('success', $name.' is successfully saved');
								redirect('location/state/'.$country_id);		
							}else{
								$this->session->set_flashdata('failure', $name.' is failed to add.');
								redirect('location/state');		
							}
						}else{
							if($this->location_model->editCityModel($data,$city_id)){
								$this->session->set_flashdata('success', $name.' is successfully updated');
								redirect('location/state/'.$country_id);		
							}else{
								$this->session->set_flashdata('failure', $name.' is failed to update');
								redirect('location/state');		
							}
						}
					}
		        }else{
		        	$data["countries"] = $this->location_model->getCountries();
		            $this->load->view('location/city/add',$data);
		        }
			}else{ 
				$city_id = $id;

				$data["data"] 		= $this->location_model->getCityRecord($city_id);
				$data["countries"] 	= $this->location_model->getCountries();
				$data["country_id"] = $this->location_model->getStateRecord($data["data"]->state_id)->country_id;
				$data["states"] 	= $this->location_model->getStates($data["country_id"]);			
				
				
				// echo '<pre>';
				// print_r($data);
				// exit;
				$this->load->view('location/city/add',$data);
			}	
        }

		
	}

	
	/*
		add category using ajax
	*/
	public function getStatesAjax($country_id){
		echo json_encode($this->location_model->getStates($country_id));
	} 
	
	public function deleteCountry($id){
		if(!$this->user_model->has_permission("delete_location"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	if($this->location_model->deleteCountryModel($id)){			
				$this->session->set_flashdata('success', 'Country successfully deleted');
				redirect('location/country');
			}
			else{
				$this->session->set_flashdata('fail', 'Country is not successfully Deleted.');
				redirect("location/country",'refresh');
			}	
        }
		
	}
	public function deleteState($id){
		if(!$this->user_model->has_permission("delete_location"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	if($this->location_model->deleteStateModel($id)){
				$this->session->set_flashdata('success', 'State successfully deleted');
				redirect('location/state');
			}
			else{
				$this->session->set_flashdata('fail', 'State is not successfully deleted');
				redirect("location/state",'refresh');
			}	
        }
		
	}
	public function deleteCity($id){
		if(!$this->user_model->has_permission("delete_location"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        	if($this->location_model->deleteCityModel($id)){
				$this->session->set_flashdata('success', 'City successfully deleted');
				redirect('location/city');
			}
			else{
				$this->session->set_flashdata('fail', 'City is not successfully Deleted.');
				redirect("location/city",'refresh');
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