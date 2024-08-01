<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Raw_material extends MY_Controller
{
	function __construct() {
		parent::__construct();
		$this->load->model('raw_material_model');
		$this->load->model('category_model');
		$this->load->model('log_model');
		$this->load->library('zend');
	    $this->zend->load('Zend/Barcode');
	    $this->load->model('sales_model');
	    $this->load->model('ion_auth_model');
	    $this->load->model('warehouse_model');
	    $this->load->model('biller_model');
	    $this->load->model('user_model');
	    $this->load->model('email_setup_model');
        $this->load->model('sms_setting_model');
        

	    
	}
	public function index()
	{
		if(!$this->user_model->has_permission("list_product"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			$data['user'] = $this->ion_auth_model->user()->row();
			$data['user_group'] = $this->ion_auth_model->get_users_groups($data['user']->id)->result();

			if($data['user_group'][0]->name =="sales_person" || $data['user_group'][0]->name =="purchaser")
			{
				$data['data'] = $this->raw_material_model->getProductsWarehouseWise($data['user']->id);
			}
			else
			{
				$data['data'] = $this->raw_material_model->getProducts();
			}	

			$data['no_of_warehouse'] = sizeof($this->warehouse_model->getWarehouseCount());
			$data['no_of_category'] = sizeof($this->category_model->getCategoryCount());

			$this->load->view('raw_material/list',$data);		
		}
		
		
	}
	/* 
		call add view to add product record 
	*/
	public function add(){
		if(!$this->user_model->has_permission("add_product"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			$data['category'] = $this->raw_material_model->getCategory();		
		    $data['sac']      = $this->raw_material_model->getSac();
			$data['chapter']  = $this->raw_material_model->getHsnChapter();
			$data['hsn']      = $this->raw_material_model->getHsn();
			$data['brand']	  = $this->raw_material_model->getBrand();	
			$data['uqc']	  = $this->raw_material_model->getUqc();	
			$this->load->view('raw_material/add',$data);
		}
			
		
	}
	/* 
		This function used when category is change subcategory list change  
	*/
	public function getSubcategory($id){
		$data = $this->raw_material_model->selectSubcategory($id);
		echo json_encode($data);
	}
	/*

	*/
	public function getHsnData($id){
		$data = $this->raw_material_model->getHsnData($id);
		echo json_encode($data);
	}
	/* 
		This function is used to add product record in database 
	*/
	public function addProduct(){
		$this->load->helper('security');
		$this->form_validation->set_rules('code', 'Code', 'trim|required|xss_clean');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('category', 'Category', 'trim|required|numeric|xss_clean');
		//$this->form_validation->set_rules('subcategory', 'Subcategory', 'trim|required|numeric|xss_clean');
		// $this->form_validation->set_rules('unit', 'Unit', 'trim|required|xss_clean');
		//$this->form_validation->set_rules('size', 'Size', 'trim|required|alpha_numeric|xss_clean');
		$this->form_validation->set_rules('cost', 'Cost', 'trim|required|numeric|xss_clean');
		$this->form_validation->set_rules('price', 'Price', 'trim|required|numeric|xss_clean');
		//$this->form_validation->set_rules('alert_quantity', 'Alert Quantity', 'trim|required|numeric|xss_clean');
		//$this->form_validation->set_rules('tax', 'Tax', 'trim|required|numeric|xss_clean');
		//$this->form_validation->set_rules('image', 'Image', 'trim|required');
		//$this->form_validation->set_rules('details', 'Details', 'trim|required|xss_clean');

		if ($this->form_validation->run() == FALSE)
        {
            $this->add();
        }
        else
        {
        	if($_FILES["image"]["name"]){
        		$type = explode('.',$_FILES["image"]["name"]);
				$type = $type[count($type)-1];
				$url = "assets/images/product/".uniqid(rand()).'.'.$type;
						
				if(in_array($type,array("jpg","jpeg","gif","png"))){
							
					if(is_uploaded_file($_FILES["image"]["tmp_name"])){
								
						if(move_uploaded_file($_FILES["image"]["tmp_name"],$url)){
									
						}
					}	
				}
        	}
        	else{
        		$url = "assets/images/product/no_image.jpg";
        	}
					
		
			$data = array(
					"code"           => $this->input->post('code'),
					"name"           => $this->input->post('name'),
					"hsn_sac_code"   => $this->input->post('hsn_sac_code'),
					"barcode"		 => $this->barcode($this->input->post('code')),
					"category_id"    => $this->input->post('category'),
					"subcategory_id" => $this->input->post('subcategory'),
					"brand_id"       => $this->input->post('brand'),
					"unit"           => $this->input->post('unit'),
					"size"           => $this->input->post('size'),
					"cost"           => $this->input->post('cost'),
					"price"          => $this->input->post('price'),
					"alert_quantity" => $this->input->post('alert_quantity'),					
					"igst" 			 => $this->input->post('igst'),
					"cgst" 			 => $this->input->post('cgst'),
					"sgst" 			 => $this->input->post('sgst'),
					/*"tax_id"         => $this->input->post('tax'),*/
					"image"          => base_url().''.$url,
					"date"           => date('Y-m-d'),
					"details"        => $this->input->post('note')					
				);

			if($id = $this->raw_material_model->addModel($data)){ 
				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $id,
						'message'  => 'Product Inserted'
					);
				$this->log_model->insert_log($log_data);
				redirect('product','refresh');
			}
			else{
				$this->session->set_flashdata('fail', 'Product can not be Inserted.');
				redirect("product",'refresh');
			}
		}
	}
	/*
		call edit view to edit product record 
	*/
	public function edit($id){
		if(!$this->user_model->has_permission("edit_product"))
		{
			$this->load->view('layout/restricted');	
		}
		$data['data']        = $this->raw_material_model->getRecord($id);
		if($data['data'] == null){
			$this->session->set_flashdata('fail', 'Product is not available. It might be deleted or removed.');
			redirect("product/index",'refresh');
		}
		$data['category']    = $this->raw_material_model->getCategory();
		$data['subcategory'] = $this->raw_material_model->getSubcategory($id);
		
		$data['sac']         = $this->raw_material_model->getSac();
		$data['chapter']     = $this->raw_material_model->getHsnChapter();
		$data['hsn']         = $this->raw_material_model->getHsn();
		$data['brand']	     = $this->raw_material_model->getBrand();	
		$data['uqc']	     = $this->raw_material_model->getUqc();	
		$this->load->view('raw_material/edit',$data);
	
	}
	/* 
		This function is used to edit product in database 
	*/
	public function editProduct(){
		$id = $this->input->post('id');
		$this->form_validation->set_rules('code', 'Code', 'trim|required');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('category', 'Category', 'trim|required|numeric');
		//$this->form_validation->set_rules('subcategory', 'Subcategory', 'trim|required|numeric');
		// $this->form_validation->set_rules('unit', 'Unit', 'trim|required');
		//$this->form_validation->set_rules('size', 'Size', 'trim|required|alpha_numeric');
		$this->form_validation->set_rules('cost', 'Cost', 'trim|required|numeric');
		$this->form_validation->set_rules('price', 'Price', 'trim|required|numeric');
		//$this->form_validation->set_rules('alert_quantity', 'Alert Quantity', 'trim|required|numeric');
		//$this->form_validation->set_rules('tax', 'Tax', 'trim|required|numeric');
		//$this->form_validation->set_rules('image', 'Image', 'trim|required');
		//$this->form_validation->set_rules('details', 'Details', 'trim|required');


		if ($this->form_validation->run() == FALSE)
        {
            $this->edit($id);
        }
        else
        {
				
			if($_FILES["image"]["name"] == null){
				$url = $this->input->post('hidden_image');
			}
			else{
				$type = explode('.',$_FILES["image"]["name"]);
				$type = $type[count($type)-1];
				$url = "./assets/images/product/".uniqid(rand()).'.'.$type;
						
				if(in_array($type,array("jpg","jpeg","gif","png"))){
							
					if(is_uploaded_file($_FILES["image"]["tmp_name"])){
								
						if(move_uploaded_file($_FILES["image"]["tmp_name"],$url)){
							$url = base_url().''.$url;	
						}
					}	
				}
			}	


			$data = array(
					"code"           => $this->input->post('code'),
					"name"           => $this->input->post('name'),
					"hsn_sac_code"   => $this->input->post('hsn_sac_code'),
					"category_id"    => $this->input->post('category'),
					"subcategory_id" => $this->input->post('subcategory'),
					"brand_id"       => $this->input->post('brand'),
					"unit"           => $this->input->post('unit'),
					"size"           => $this->input->post('size'),
					"cost"           => $this->input->post('cost'),
					"price"          => $this->input->post('price'),
					"alert_quantity" => $this->input->post('alert_quantity'),					
					"igst" 			 => $this->input->post('igst'),
					"cgst" 			 => $this->input->post('cgst'),
					"sgst" 			 => $this->input->post('sgst'),
					/*"tax_id"         => $this->input->post('tax'),*/
					"image"          => $url,
					"date"           => date('Y-m-d'),
					"details"        => $this->input->post('note')
				);
			// echo '<pre>';
			// print_r($data);
			// exit;
			if($this->raw_material_model->editModel($data,$id)){ 
				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $id,
						'message'  => 'Product Updated'
					);
				$this->log_model->insert_log($log_data);
				// redirect('product','refresh');
				$this->session->set_flashdata('success', 'Product updated successfully.');
				redirect("product",'refresh');
			}
			else{
				$this->session->set_flashdata('fail', 'Product can not be Updated.');
				redirect("product",'refresh');
			}
		}
	}
	/* 
		This function is used to delete product record in databse 
	*/
	public function delete($id){

		if($this->user_model->has_permission("delete_product"))
		{
			if($this->raw_material_model->deleteModel($id)){
				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $id,
						'message'  => 'Product Deleted'
					);
					$this->log_model->insert_log($log_data);
				redirect('product','refresh');
			}
			else{
				$this->session->set_flashdata('fail', 'Product can not be Deleted.');
				redirect("product",'refresh');
			}
		}
		else
		{
			$this->load->view('layout/restricted');	
		}
		
	}
	/*
		this function call CSV file view
	*/
	public function import(){

		if(!$this->user_model->has_permission("import_product"))
		{
			$this->load->view('layout/restricted');	
		}
		$data['category'] = $this->raw_material_model->getCategory();
		$this->load->view('raw_material/import',$data);
	}
	/*
		this  function get csv file data
	*/
	public function import_csv(){
        
        $category_id 	= $this->input->post('category');
        $update_records = $this->input->post('update_records');
        $filename		= $_FILES["csv"]["tmp_name"];      
    
        if($_FILES["csv"]["size"] > 0)
        {
            $file = fopen($filename, "r");
            
            for ($lines = 0; $data = fgetcsv($file,1000,",",'"'); $lines++) 
            {
            	
            
                if ($lines == 0){
                	if(sizeof($data)!=13){
						if(sizeof($data)<13){
							fclose($file);
	        				$this->session->set_flashdata('fail', 'Few columns are missing in uploaded file.');
							redirect("product/import",'refresh');		
						}else{
							fclose($file);
	        				$this->session->set_flashdata('fail', 'There are few extra columns are available in uploaded file.');
							redirect("product/import",'refresh');		
						}
						
					}else if(
								($data[0] != "Code") || 
								($data[1] != "Name") || 
								($data[2] != "Hsn Sac Code") || 
								($data[3] != "Unit") || 
								($data[4] != "Size") || 
								($data[5] != "Cost") || 
								($data[6] != "Price") || 
								($data[7] != "Opening Qty") || 
								($data[8] != "Alert Quantity") || 
								($data[9] != "Details") ||
								($data[10] != "IGST") ||
								($data[11] != "CGST") ||
								($data[12] != "SGST") 
							){
        				
        				fclose($file);
        				$this->session->set_flashdata('fail', 'File is not having proper format as per sample product file.');
						redirect("product/import",'refresh');	
					}else{
						continue;
					}
                } 

                if($data[10] == null || $data[10] == ""){
                	$igst = 0;
                }else{
                	$igst = $data[10];
                }


                if($data[11] == null || $data[11] == ""){
                	$cgst = 0;
                }else{
                	$cgst = $data[11];
                }


                if($data[12] == null || $data[12] == ""){
                	$sgst = 0;
                }else{
                	$sgst = $data[12];
                }

                
                $sql = "INSERT INTO `products`(
                						`category_id`,
                						`code`, 
                						`name`, 
                						`hsn_sac_code`, 
                						`unit`, 
                						`size`, 
                						`cost`, 
                						`price`,
                						`quantity`, 
                						`opening_stock`, 
                						`alert_quantity`, 
                						`details`,
                						`igst`,
                						`cgst`,
                						`sgst`
                						) 
                					VALUES (
                						$category_id,'".
                						trim($data[0])."','".
                						trim($data[1])."','".
                						trim($data[2])."','".
                						trim($data[3])."','".
                						trim($data[4])."','".
                						trim($data[5])."','".
                						trim($data[6])."','".
                						trim($data[7])."','".
                						trim($data[7])."','".
                						trim($data[8])."','".
                						trim($data[9])."','".
                						trim($igst)."','".
                						trim($cgst)."','".
                						trim($sgst)."')";
                $this->db->query($sql);
                if($id = $this->db->insert_id()){
                	$this->raw_material_model->insertOpeningStock($id,$data[7]);
                }else{
                	$error = $this->db->error();

					if ($error['code'] == 1062) {
						if($update_records=="yes"){
							$product = $this->raw_material_model->getRecordByCode(trim($data[0]));
							$this->raw_material_model->updateOpeningStock($product->product_id,trim($data[7]),$product->quantity);
						}
					}
                }
            }
            fclose($file); 
            $this->session->set_flashdata('success', 'Products are imported successfully.');
			redirect("product",'refresh'); 


        }
        else{
            redirect("product/import",'refresh'); 
        }
        redirect('product','refresh');
	}
	/*
		generate basr code
	*/
	function barcode($code=100)
	{
	    $file = Zend_Barcode::draw('code128', 'image', array('text' => $code), array());
	   	$code = time().$code;
	   	$store_image = imagepng($file,"./assets/images/barcode/{$code}.png");
	   	return   base_url('assets/images/barcode/').$code.'.png';
	 
	}
	/*
		call barcode list view
	*/
	public function products_barcode(){
		$data['data'] = $this->raw_material_model->getBarcode();
		$this->load->view('raw_material/barcode',$data);
	}
	function code_exists($code) {
		if($this->raw_material_model->codeExist($code)){
			$this->form_validation->set_message('code_exists', 'Code Already Exist');
			return false;
		}
		else{
			return true;
		}
	}
	function alpha_dash_space($str) {
		if (! preg_match("/^([-a-zA-Z0-9_ ])+$/i", $str))
	    {
	        $this->form_validation->set_message('alpha_dash_space', 'The %s field may only contain alpha-numeric characters, spaces, underscores, and dashes.');
	        return FALSE;
	    }
	    else
	    {
	        return TRUE;
	    }
	}
} 
?>