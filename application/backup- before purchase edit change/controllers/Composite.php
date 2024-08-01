<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Composite extends MY_Controller
{
	function __construct() {
		parent::__construct();
		$this->load->model('log_model');
		$this->load->model('composite_model');
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
		$data['data'] = $this->composite_model->getData();
		$this->load->view('composite/index',$data);
	} 

	public function add(){ 

		if($_POST) {

			$js_data 				= json_decode($this->input->post("entry_data"));
			$composite_product_name = $this->input->post("composite_product_name");
			$description	 		= $this->input->post("description");

			$composite = array(
								"composite_product_name"=> $composite_product_name,
								"description" 			=> $description
							);

			if($id = $this->composite_model->addModel($composite)){

				foreach ($js_data as $key => $value) {
					$composite_product = array(
											"composite_id" 		=> $id,
											"product_id" 		=> $value->product_id,
											"quantity" 			=> $value->quantity
										);
					if($this->composite_model->addModelItem($composite_product)){

					}else{

						$this->delete($id);
						goto fail;
					}
				}
				$this->session->set_flashdata('success', 'Data successfully saved');
				redirect('composite');
			}else{
				fail:
				$this->session->set_flashdata('fail', 'Data doesn\'t successfully saved');
				redirect('composite');
			}
        }else{

        	$data['products'] = $this->product_model->getProducts();

        	// echo '<pre>';
        	// print_r($data);
        	// exit;
            $this->load->view('composite/add',$data);
        }
		
	}
	
	public function edit($id = NULL){ 
		
		if($id == NULL){

			if($_POST) {

				$composite_id = $this->input->post("composite_id");

				$existing_composite_products = $this->composite_model->getDataEntry($composite_id);
				$existing_composite_product_array = array();

				foreach ($existing_composite_products as $existing_product) {
					$existing_composite_product_array[] = $existing_product->composite_product_id;
				}

				$js_data 				= json_decode($this->input->post("entry_data"));
				$composite_product_name = $this->input->post("composite_product_name");
				$description	 		= $this->input->post("description");

				$composite = array(
								"composite_product_name"=> $composite_product_name,
								"description" 			=> $description
							);


				if($this->composite_model->editModel($composite,$composite_id)){
					foreach ($js_data as $key => $value) {
						$composite_product = array(
											"composite_id" 		=> $composite_id,
											"product_id" 		=> $value->product_id,
											"quantity" 			=> $value->quantity
										);

						if($value->composite_product_id != ""){

							if (($key = array_search($value->composite_product_id, $existing_composite_product_array)) !== false) {
							    unset($existing_composite_product_array[$key]);
							}
							$this->composite_model->editModelItem($composite_product,$value->composite_product_id);
						}else{
							$this->composite_model->addModelItem($composite_product);	
						}
					}
					// delete the entries which are not available while updating 
					$existing_composite_product_array = array_values($existing_composite_product_array);

					
					if(sizeof($existing_composite_product_array)>0){
						for($i = 0 ; $i < sizeof($existing_composite_product_array) ; $i++){
							$this->composite_model->deleteModelItem($existing_composite_product_array[$i]);
						}
					}

					$this->session->set_flashdata('success', 'Data successfully saved');
					redirect('composite');
				}else{

					$this->session->set_flashdata('fail', 'Data does not successfully saved');
					redirect('composite');
				}
				
			}else{
				redirect("sales",'refresh');
			}

		}else{

			$composite_id = $id;

			$data['data'] = $this->composite_model->getRecord($composite_id);
			if($data['data'] == null){
				$this->session->set_flashdata('fail', 'Records does not exist anymore');
				redirect("sales",'refresh');
			}
			$data['products'] = $this->product_model->getProducts();
			$data['composite_entries'] = $this->composite_model->getDataEntry($composite_id);

			// echo '<pre>';
			// print_r($data);
			// exit;
			
		    $this->load->view('composite/edit',$data);
		}
		
	}
	
	public function delete($composite_id){

		if($this->composite_model->deleteModel($composite_id)){
			$this->session->set_flashdata('success', 'Record deleted successfully');
			redirect("composite",'refresh');
		}else{
			$this->session->set_flashdata('fail', 'System is not able to remove records.');
			redirect("composite",'refresh');
		}
	}
}
?>