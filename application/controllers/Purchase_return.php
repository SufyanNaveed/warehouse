<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchase_return extends MY_Controller
{
	function __construct() {
		parent::__construct();
		
	}
		
	public function index(){
		if(!$this->user_model->has_permission("list_purchase_return"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			$data['data'] = $this->purchase_return_model->getPurchaseReturn();
			$this->load->view('purchase_return/list',$data);	
		}
	} 
	/* call add view to add purchase return   */
	public function add($purchase_id = null){
		if(!$this->user_model->has_permission("add_purchase_return"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			//$data['product'] 	= $this->purchase_model->getProduct();
			$data['warehouse'] 	= $this->purchase_return_model->getWarehouse(); 
			$data['biller'] 	= $this->sales_model->getBiller();
			$data['supplier'] 	= $this->purchase_return_model->getSupplier();
			$data['user'] 		= $this->assign_warehouse_model->getUser();
			$data['reference_no'] 	= $this->purchase_return_model->createReferenceNo();
			$data['receipts'] 		= $this->purchase_return_model->get_receipt_record();
			

			if($purchase_id != null)
			{
				$data['data'] 				= $this->purchase_model->getRecord($purchase_id);
				$data['items'] 				= $this->purchase_model->getPurchaseItems($purchase_id,$data['data'][0]->warehouse_id);	
				$data['purchase_id']		= $purchase_id;
				$data['biller_id']			= $this->purchase_return_model->get_single_receipt_record($purchase_id)->biller_id;
				$data['supplier_id']		= $this->purchase_return_model->get_single_receipt_record($purchase_id)->supplier_id;
				$data['warehouse_id']		= $this->purchase_return_model->get_single_receipt_record($purchase_id)->warehouse;
				$data['warehouse_state_id']	= $this->purchase_return_model->get_single_receipt_record($purchase_id)->warehouse_state_id;
				$data['supplier_state_id']	= $this->purchase_return_model->get_single_receipt_record($purchase_id)->supplier_state_id;
				$data['purchase_invoice_number'] = $data['data'][0]->reference_no;
				$data['purchase_returned_items'] = $this->purchase_return_model->getReturnedItems($purchase_id);

				// echo '<pre>';
				// print_r($data);
				// exit;

			}

			
			$this->load->view('purchase_return/add',$data);	
		}
		

	}
	/* get single product */
	public function getProduct($product_id,$warehouse_id){
		$data = $this->purchase_return_model->getProduct($product_id,$warehouse_id);
		$data['discount'] = $this->purchase_model->getDiscount();

	    echo json_encode($data);
		//print_r($data);
	}
	/* get all product warehouse wise */
	public function getProducts($warehouse_id){
		$data = $this->purchase_return_model->getProducts($warehouse_id);
	    echo json_encode($data);
		//print_r($data);
	}
	/* This function is used to search product code / name in database */
	public function getAutoCodeName($code,$search_option,$warehouse){
          //$code = strtolower($code);
		  $p_code = $this->input->post('p_code');
		  $p_search_option = $this->input->post('p_search_option');
          $data = $this->purchase_return_model->getProductCodeName($p_code,$p_search_option,$warehouse);
          if($search_option=="Code"){
          	$list = "<ul class='auto-product'>";
          	foreach ($data as $val){
          		$list .= "<li value=".$val->code.">".$val->code."</li>";
          	}
          	$list .= "</ul>";
          }
          else{
          	$list = "<ul class='auto-product'>";
          	foreach ($data as $val){
          		$list .= "<li value=".$val->product_id.">".$val->name."</li>";
          	}
          	$list .= "</ul>";
          }
          
          echo $list;
          //echo json_encode($data);
          //print_r($list);
	}
	/* This function is used to add purchase return in database */
	public function addPurchaseReturn(){
		$this->form_validation->set_rules('date','Date','trim|required');
		$this->form_validation->set_rules('reference_no','Reference No','trim|required');
		//$this->form_validation->set_rules('supplier_id','Supplier ID','trim|required');
		//$this->form_validation->set_rules('warehouse_id','Warehouse ID','trim|required');
		if($this->form_validation->run()==false){

			$this->add();
		}
		else
		{
			$warehouse_id 				= $this->input->post('warehouse');
			$date 						= $this->input->post('date');
			$reference_no   			= $this->input->post('reference_no');
			$purchase_id 				= $this->input->post('purchase_id');
			$supplier_id 				= $this->input->post('supplier');
			$biller_id 					= $this->input->post('biller');
			$total 						= $this->input->post('grand_total');
			$flat_discount 				= $this->input->post('discount');
			$discount_value 			= $this->input->post('total_discount');
			$tax_value 					= $this->input->post('total_tax');
			$note 						= $this->input->post('note');
			$user 						= $this->session->userdata('user_id');
			$purchase_invoice_number 	= $this->input->post('purchase_invoice_number');

			$data 						= array(
											"date" 			=> 	$date,
											"reference_no" 	=> 	$reference_no,
											"purchase_id" 	=> 	$purchase_id,
											"supplier_id" 	=>	$supplier_id,
											"biller_id"		=> 	$biller_id,
											"warehouse_id" 	=>	$warehouse_id,
											"total" 		=> 	$total,
											"flat_discount" =>  $flat_discount,
											"discount_value"=>  $discount_value,
											"tax_value" 	=>  $tax_value,
											"note" 			=> 	$note,
											"user"			=>	$user,
											"purchase_invoice_number" 	=> $purchase_invoice_number 	
											
										);	
			// echo '<pre>';
			// print_r($data);
			// exit;

			$invoice = array(
				"invoice_no" 	=> $this->sales_model->generateInvoiceNo(),
				"sales_amount" 	=> $this->input->post('grand_total'),
				"invoice_date" 	=> date('Y-m-d')
			);

			if($id = $this->purchase_return_model->addModel($data,$invoice))
			{
				$purchase_item_data = $this->input->post('table_data');

				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $id,
						'message'  => 'Purchase Return Inserted'
					);
				$this->log_model->insert_log($log_data);
				$js_data = json_decode($purchase_item_data);
				// echo $js_data;
				// exit;
				foreach ($js_data as $key => $value) {
					if($value==null){
					}
					else{
						$product_id = $value->product_id;
						$quantity = $value->quantity;
						$data = array(
									"product_id" => $value->product_id,
									"quantity" => $value->quantity,
									"gross_total" => $value->total,
									"discount_id" => $value->discount_id,
									"discount_value" => $value->discount_value,
									"tax_type" => $value->tax_type,
									"discount" => $value->discount,
									"igst" => $value->igst,
									"igst_tax" => $value->igst_tax,
									"cgst" => $value->cgst,
									"cgst_tax" => $value->cgst_tax,
									"sgst" => $value->sgst,
									"sgst_tax" => $value->sgst_tax,
									"cost" => $value->price,
									"purchase_return_id" => $id
								);
						$warehouse_data = array(
							"product_id" => $value->product_id,
							"warehouse_id" => $warehouse_id,
							"quantity" => $value->quantity
							);

						$this->purchase_return_model->addProductInWarehouse($product_id,$quantity,$warehouse_id,$warehouse_data);
						
						if($this->purchase_return_model->addPurchaseItem($data))
						{
							redirect('purchase_return','refresh');
						}
						else{

						}
					}
				}
				redirect('purchase_return','refresh');
			}
			else{
			}
		}
	}
	/* This function is used to call view  edit purchase return */
	public function view($id)
	{
		if(!$this->user_model->has_permission("edit_purchase_return"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			$data['warehouse'] = $this->purchase_return_model->getWarehouse(); 
			//$data['biller'] = $this->sales_model->getBiller();
			$data['user'] = $this->assign_warehouse_model->getUser();
			$data['supplier'] = $this->purchase_return_model->getSupplier();
			$data['data'] = $this->purchase_return_model->getRecord($id);
			$data['product'] = $this->purchase_return_model->getProducts($data['data'][0]->warehouse_id);
			$data['discount'] = $this->purchase_model->getDiscount();
			$data['company'] 			= $this->purchase_model->getCompany();
			
			$data['items'] = $this->purchase_return_model->getPurchaseReturnItems($data['data'][0]->id,$data['data'][0]->warehouse_id);
			$data['supplier_state_id'] = $this->purchase_model->getSupplierState($data['data'][0]->supplier_id);
			$data['warehouse_details']  = $this->purchase_model->getWarehouesDetails($data['data'][0]->warehouse_id);
			$data['warehouse_state_id'] = $data['warehouse_details'][0]->warehouse_state_id;
				

			// echo '<pre>';
			// print_r($data);
			// exit;

			$this->load->view('purchase_return/view',$data);	
		}
		
	}

	public function pdf($id){
		if(!$this->user_model->has_permission("download_purchase"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			ob_start();
			$html = ob_get_clean();
			$html = utf8_encode($html);

			$data['data'] = $this->purchase_return_model->getRecord($id);
			$data['items'] = $this->purchase_return_model->getPurchaseReturnItems($data['data'][0]->id,$data['data'][0]->warehouse_id);
			$data['company'] = $this->purchase_model->getCompany();

			// echo '<pre>';
			// print_r($data);
			// exit;
			$html = $this->load->view('purchase_return/pdf',$data,true);

			include(APPPATH.'third_party/mpdf60/mpdf.php');
	        $mpdf = new mPDF();
	        $mpdf->allow_charset_conversion = true;
	        $mpdf->charset_in = 'UTF-8';
	        $mpdf->WriteHTML($html);
	        $mpdf->Output($data['data'][0]->reference_no.'pdf','I');	
		}
		
	}

	/* This function is used to delete discount record in databse */
	public function delete($id){
		if(!$this->user_model->has_permission("delete_purchase_return"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			if($this->purchase_return_model->deleteModel($id))
			{
				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $id,
						'message'  => 'Purchase Return Deleted'
					);
				$this->log_model->insert_log($log_data);
				redirect('purchase_return','return');
			}
			else{
				redirect('purchase_return','return');
			}	
		}
		
	}
	/* This function is to edit purchase return record in database */
	public function editPurchaseReturn(){
		$id = $this->input->post('purchase_return_id');
		$this->form_validation->set_rules('date','Date','trim|required');
		$this->form_validation->set_rules('reference_no','Reference No','trim|required');
		//$this->form_validation->set_rules('supplier_id','Supplier ID','trim|required');
		//$this->form_validation->set_rules('warehouse_id','Warehouse ID','trim|required');
		if($this->form_validation->run()==false){

			$this->edit($id);
		}
		else
		{
			
			$warehouse_id 				= $this->input->post('warehouse');
			$date 						= $this->input->post('date');
			$reference_no   			= $this->input->post('reference_no');
			$purchase_id 				= $this->input->post('purchase_id');
			$supplier_id 				= $this->input->post('supplier');
			$biller_id 					= $this->input->post('biller');
			$total 						= $this->input->post('grand_total');
			$flat_discount 				= $this->input->post('discount');
			$discount_value 			= $this->input->post('total_discount');
			$tax_value 					= $this->input->post('total_tax');
			$note 						= $this->input->post('note');
			$user 						= $this->session->userdata('user_id');
			

			$data 						= array(
											"date" 			=> 	$date,
											"reference_no" 	=> 	$reference_no,
											"purchase_id" 	=> 	$purchase_id,
											"supplier_id" 	=>	$supplier_id,
											"biller_id"		=> 	$biller_id,
											"warehouse_id" 	=>	$warehouse_id,
											"total" 		=> 	$total,
											"flat_discount" =>  $flat_discount,
											"discount_value"=>  $discount_value,
											"tax_value" 	=>  $tax_value,
											"note" 			=> 	$note,
											"user"			=>	$user
												
											
										);

			$invoice = array(
				"sales_amount" 	=> $this->input->post('grand_total'),
			);
			
			//$id = $this->input->post('purchase_return_id');
			$js_data = json_decode($this->input->post('table_data1'));
			$php_data = json_decode($this->input->post('table_data'));
			if($this->purchase_return_model->editModel($id,$data,$invoice)){
				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $id,
						'message'  => 'Purchase Return Updated'
					);
				$this->log_model->insert_log($log_data);
				if($js_data !=null){
					foreach ($js_data as $key => $value) {
						if($value=='delete'){
							//echo " delete".$key;
							$product_id =  $php_data[$key];
							if($this->purchase_return_model->deletePurchaseReturnItems($id,$product_id,$warehouse_id)){
								//echo " 1.Dsuccess";
							}
						}
						else if($value==null){
							//echo " Null".$key;
						}
						else{
							//echo " array";
							$product_id = $value->product_id;
							$quantity = $value->quantity;
							$data = array(
									"product_id" => $value->product_id,
									"quantity" => $value->quantity,
									"gross_total" => $value->total,
									"discount_id" => $value->discount_id,
									"discount_value" => $value->discount_value,
									"discount" => $value->discount,
									"tax_type" => $value->tax_type,
									"igst" => $value->igst,
									"igst_tax" => $value->igst_tax,
									"cgst" => $value->cgst,
									"cgst_tax" => $value->cgst_tax,
									"sgst" => $value->sgst,
									"sgst_tax" => $value->sgst_tax,
									"cost" => $value->price,
									"purchase_return_id" => $id
								);
							$warehouse_data = array(
								"product_id" => $value->product_id,
								"warehouse_id" => $warehouse_id,
								"quantity" => $value->quantity
								);
							if($this->purchase_return_model->addUpdatePurchaseItem($id,$product_id,$warehouse_id,$quantity,$data,$warehouse_data)){
								//echo " 1 Asuccess add";
							}
							else{

							}
						}
					}
				}
				redirect('purchase_return');
			}
		}
	}
	/*
		return barcode product
	*/
	public function getBarcodeProducts($term,$warehouse_id){
		/*echo $term." ".$warehouse_id;
		exit;	*/
		$data = $this->purchase_return_model->getBarcodeProducts($term,$warehouse_id);
		echo json_encode($data);
	}
	

	public function getNameProducts($term,$warehouse_id){
		$data = $this->purchase_return_model->getNameProducts($term,$warehouse_id);
		echo json_encode($data);
	}
	/*
		return product details
	*/
	public function getProductUseCode($product_id,$warehouse_id){
		$data = $this->purchase_return_model->getProductUseCode($product_id,$warehouse_id);
		$data['discount'] = $this->purchase_return_model->getDiscount();
		/*echo '<pre>';
		print_r($data);
		exit;*/
		echo json_encode($data);
	}
	/*
		return biller details
	*/
	public function getBillerState($id){
		echo $this->purchase_return_model->getBillerState($id);
	}
	/*
		return customer details
	*/
	public function getCustomerState($id){
		echo $this->purchase_return_model->getCustomerState($id);
	}

	public function getWarehouseStateId($user_id)
	{
		// $data = array(
		// 				"warehouse_state_id" =>$this->branch_model->get_single_record($this->warehouse_model->get_single_record($warehouse_id)->branch_id)->state_id,
		// 				"warehouse" => $this->warehouse_model->get_single_record_with_join($warehouse_id)->biller
		// 			);

		$data = array(
					"warehouse_state_id" => $this->warehouse_model->get_single_record_by_user_id($user_id)->warehouse_state_id,
					"warehouse"			 => $this->warehouse_model->get_single_record_by_user_id($user_id)->warehouse_id
				);
		echo json_encode($data);
	}

	public function receipt($id)
	{
		$data['data'] = $this->purchase_return_model->get_single_record($id);

		$data['to_ledger'] = $this->ledger_model->get_single_record($this->branch_model->get_single_record($this->warehouse_model->get_single_record($data['data']->warehouse_id)->branch_id)->ledger_id);
		$data['from_ledger'] = $this->sales_model->getFromLedger($data['data']->supplier_id);
		$data['company'] = $this->purchase_model->getCompany();
		$data['payment_method'] = $this->payment_method_model->payUserData();
		$data['p_reference_no'] = $this->receipt_model->generateReferenceNo();
		// echo '<pre>';
		// print_r($data);
		// exit;
	

		$this->load->view('purchase_return/receipt',$data);
	}
}
?>