<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sales_return extends MY_Controller
{
	function __construct() 
	{
		parent::__construct();
		$this->load->model('sales_return_model');
		$this->load->model('sales_model');
		$this->load->model('customer_model');
		$this->load->model('purchase_model');
		$this->load->model('log_model');
		$this->load->model('purchase_model');
		$this->load->model('receipt_model');
		$this->load->model('purchase_return_model');
		
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
		// get all sales to display list
		if(!$this->user_model->has_permission("list_sales_return"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			$data['data'] = $this->sales_return_model->getSales();
			
			$this->load->view('sales_return/list',$data);
		}
	} 
	/* 
		call add view to add sales return record 
	*/
	public function add($sales_id = null)
	{
		if(!$this->user_model->has_permission("add_sales_return"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			if($this->sales_model->checkBillerForPrimaryWarehouse())
			{
				$data['warehouse'] = $this->sales_model->getSalesWarehouse();	
			}
			else
			{
				$this->session->set_flashdata('no_sales_person', 'Kindly create Saler Person / Biller  for primary warhouse');
				redirect('sales_return','refresh');
			}
			
			$data['user'] = $this->ion_auth_model->user()->row();
			$data['user_group'] = $this->ion_auth_model->get_users_groups($data['user']->id)->result();

			if($data['user_group'][0]->name =="sales_person")
			{
				//$data['biller'] =  $this->biller_model->getBillerDetails($data['user']->id);
				$data['warehouse_products'] = $this->sales_model->getWarehouseProductsFromWarehouse($data['warehouse'][0]->warehouse_id); 			
			}
			else
			{
				$data['warehouse_products'] = $this->sales_model->getWarehouseProducts(); 			
			}
			// $data['warehouse_products'] = $this->sales_return_model->getWarehouseProducts();
			$data['biller'] =  $this->sales_model->getBiller();
			$data['customer'] = $this->user_model->get_user_records_by_role("customer"); 
			$data['discount'] = $this->sales_return_model->getDiscount();
			$data['invoices'] = $this->sales_return_model->get_invoice_record();
			$data['reference_no'] = $this->sales_return_model->createReferenceNo();



			if($sales_id != null)
			{
				$data['data'] 	= $this->sales_model->getRecord($sales_id);
				$data['items'] 	= $this->sales_model->getSalesItems($data['data'][0]->sales_id,$data['data'][0]->warehouse_id);
				$data['sales_id']	= $sales_id;
				$data['biller_id'] 	= $this->sales_return_model->get_single_invoice_record($sales_id)->biller;
				$data['warehouse_id'] 	= $this->sales_return_model->get_single_invoice_record($sales_id)->warehouse_id;
				$data['biller_state_id'] 	= $this->sales_return_model->get_single_invoice_record($sales_id)->biller_state_id;
				$data['customer_state_id'] 	= $this->sales_return_model->get_single_invoice_record($sales_id)->customer_state_id;
				$data['customer_id'] 	= $this->sales_return_model->get_single_invoice_record($sales_id)->customer_id;
				$data['sales_invoice_number'] = $data['data'][0]->reference_no;
				$data['sales_returned_items'] = $this->sales_return_model->getReturnedItems($sales_id);

				// echo '<pre>';
				// // echo $this->db->last_query();
				// print_r($data);
				// exit;	
			}
			// $data['biller_state_id'] = $this->sales_return_model->getBillerStateIdFromWarehouse($data['warehouse'][0]->warehouse_id);
			
			$this->load->view('sales_return/add',$data);
		}
	}

	
	/* 
		this function is used to get discount data when discount is change 
	*/
	public function getDiscountAjax($id){
		$data = $this->sales_return_model->getDiscount($id);
		echo json_encode($data);
		//print_r($data);
	}
	/* get all product warehouse wise */
	public function getProducts($warehouse_id){
		$data = $this->sales_return_model->getProducts($warehouse_id);
	    echo json_encode($data);
	}
	/* get single product */
	public function getProduct($product_id,$warehouse_id){
		$data = $this->sales_return_model->getProduct($product_id,$warehouse_id);
	    echo json_encode($data);
		//print_r($data);
	}
	/* get customer state id */
	public function getCustomerStateIdAjax($customer_id){
		echo $this->sales_return_model->getCustomerStateId($customer_id);
	    //echo *json_encode($data);
		//print_r($data);
	}
	/* 
		this function is used to search product name / code in auto complite 
	*/
	public function getAutoCodeName($code,$search_option,$warehouse){
          //$code = strtolower($code);
		  $p_code = $this->input->post('p_code');
		  $p_search_option = $this->input->post('p_search_option');
          $data = $this->sales_return_model->getProductCodeName($p_code,$p_search_option,$warehouse);
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
          //print_r($data);
	}
	/* 
		this fucntion is used to add sales return record in database 
	*/
	public function addSalesReturn(){
		$this->form_validation->set_rules('date','Date','trim|required');
		$this->form_validation->set_rules('reference_no','Reference No','trim|required');
		//$this->form_validation->set_rules('supplier_id','Supplier ID','trim|required');
		//$this->form_validation->set_rules('warehouse_id','Warehouse ID','trim|required');
		//$this->form_validation->set_rules('discount_id','Discount ID','trim|required');
		//$this->form_validation->set_rules('biller_id','Biller ID','trim|required');
		if($this->form_validation->run()==false){

			$this->add();
		}
		else
		{
			$warehouse_id = $this->input->post('warehouse');
			if($this->input->post('total_discount')==""){
				$discount_value = $this->input->post('total_discount');
			}else{
				$discount_value = 0;
			}


			if($this->input->post('discount') == ""){
				$flat_discount = 0.00;
			}else{
				$flat_discount = $this->input->post('discount');
			}

			$data = array(
						"date" 			=> $this->input->post('date'),
						"reference_no" 	=> $this->input->post('reference_no'),
						"sales_id" 		=> $this->input->post('sales_id'),
						"warehouse_id" 	=> $this->input->post('warehouse'),
						"customer_id" 	=> $this->input->post('customer'),
						"flat_discount" => $flat_discount,
						"biller_id" 	=> $this->input->post('biller'),
						"total" 		=> $this->input->post('grand_total'),
						"discount_value"=> $discount_value,
						"tax_value" 	=> $this->input->post('total_tax'),
						"note" 			=> $this->input->post('note'),
						"internal_note" => $this->input->post('internal_note'),
						"user"			=> $this->session->userdata('user_id'),
						"sales_invoice_number" 	=> $this->input->post('sales_invoice_number'),
					);

			$invoice = array(
				"invoice_no" 			=> $this->purchase_model->generateInvoiceNo(),
				"receipt_amount" 		=> $this->input->post('grand_total'),
				"receipt_voucher_date" 	=> date('Y-m-d'),
				"branch_id" 			=> $this->purchase_model->getBranchIdOfWarehouse($warehouse_id)
			);

			if($sale_return_id = $this->sales_return_model->add($data,$invoice)){
				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $sale_return_id,
						'message'  => 'Sales Return Inserted'
					);
				$this->log_model->insert_log($log_data);
				$sales_item_data = $this->input->post('table_data');
				$js_data = json_decode($sales_item_data);

				// echo '<pre>';
				// print_r($js_data);
				// exit;

				foreach ($js_data as $key => $value) {
					if($value==null){
					}
					else{
						$product_id = $value->product_id;
						$quantity = $value->quantity;	
						if(isset($value->tax_type)){
							$tax_type = $value->tax_type;
						}else{
							$tax_type = "";
						}
						$data = array(
							"product_id" => $value->product_id,
							"quantity" => $value->quantity,
							"price" => $value->price,
							"gross_total" => $value->total,
							"discount_id" => $value->discount_id,
							"discount_value" => $value->discount_value,
							"discount" => $value->discount,
							"tax_type" => $tax_type,
							"igst" => $value->igst,
							"igst_tax" => $value->igst_tax,
							"cgst" => $value->cgst,
							"cgst_tax" => $value->cgst_tax,
							"sgst" => $value->sgst,
							"sgst_tax" => $value->sgst_tax,
							"sale_return_id" => $sale_return_id
							);
						/*if($this->sales_return_model->checkProductInWarehouse($product_id,$quantity,$warehouse_id)){

						}
						else
						{*/
							$this->sales_return_model->addSalesReturnItem($data,$product_id,$warehouse_id,$quantity);
							
						/*}*/
					}
				}
				redirect('sales_return','refresh');
			}
			else{
			}
		}
	}
	/* 
		call edit view to edit sales return record 
	*/
	public function edit($id)
	{
		if(!$this->user_model->has_permission("edit_sales_return"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			$data['data'] = $this->sales_return_model->getRecord($id);
			if($data['data'] == null){
				redirect('sales_return','refresh');			
			}

			// echo '<pre>';
			// print_r($data['data']);
			// exit;

			if($this->sales_model->checkBillerForPrimaryWarehouse())
			{
				$data['warehouse'] = $this->sales_model->getSalesWarehouse();	
			}
			else
			{
				$this->session->set_flashdata('no_sales_person', 'Kindly create Saler Person / Biller  for primary warhouse');
				redirect('sales_return','refresh');
			}

			$data['user'] = $this->ion_auth_model->user()->row();
			$data['user_group'] = $this->ion_auth_model->get_users_groups($data['user']->id)->result();

			if($data['user_group'][0]->name =="sales_person")
			{

				//$data['biller'] =  $this->biller_model->getBillerDetails($data['user']->id);
				$data['warehouse_products'] = $this->sales_model->getWarehouseProductsFromWarehouse($data['warehouse'][0]->warehouse_id); 			
			}
			else
			{
				$data['warehouse_products'] = $this->sales_model->getWarehouseProducts(); 			
			}

			// $data['warehouse'] = $this->sales_return_model->getSalesWarehouse(); 
			//$data['biller'] = $this->sales_return_model->getBillerIdFromWarehouse($data['data'][0]->biller_id);

			$data['customer'] = $this->user_model->get_user_records_by_role("customer"); 
			$data['biller_id'] = $data['data'][0]->biller_id;
			$data['biller_state_id'] = $this->sales_model->getBillerStateIdFromBiller($data['data'][0]->biller_id);
			$data['customer_data'] = $this->customer_model->getCustomerData($data['data'][0]->customer_id);
			$data['shipping_state_id'] = $data['customer_data']->shipping_state_id;
			// echo '<pre>';
			// print_r($data);
			// exit;
			$data['discount'] = $this->sales_return_model->getDiscount();
			
			// echo '<pre>';
			// print_r($data);
			// exit;
			
			$data['product'] = $this->sales_return_model->getProducts($data['data'][0]->warehouse_id);
			$data['items'] = $this->sales_return_model->getSalesReturnItems($data['data'][0]->id,$data['data'][0]->warehouse_id);	
			$this->load->view('sales_return/edit',$data);
		}
	}
	/*  
		this fucntion is to edit sales return record and save in database 
	*/
	public function editSalesReturn(){
		$id = $this->input->post('sale_return_id');
		$this->form_validation->set_rules('date','Date','trim|required');
		$this->form_validation->set_rules('reference_no','Reference No','trim|required');
		//$this->form_validation->set_rules('supplier_id','Supplier ID','trim|required');
		//$this->form_validation->set_rules('warehouse_id','Warehouse ID','trim|required');
		//$this->form_validation->set_rules('discount_id','Discount ID','trim|required');
		//$this->form_validation->set_rules('biller_id','Biller ID','trim|required');
		if($this->form_validation->run()==false){

			$this->edit($id);
		}
		else
		{
		$warehouse_id = $this->input->post('warehouse');
		$old_warehouse_id = $this->input->post('old_warehouse_id');
		$warehouse_change = $this->input->post('warehouse_change');

		if($this->input->post('total_discount')==""){
			$discount_value = $this->input->post('total_discount');
		}else{
			$discount_value = 0;
		}
			$data = array(
					"date" 			=> $this->input->post('date'),
					"reference_no" 	=> $this->input->post('reference_no'),
					"warehouse_id" 	=> $this->input->post('warehouse'),
					"customer_id" 	=> $this->input->post('customer'),
					"flat_discount" => $this->input->post('discount'),
					"biller_id" 	=> $this->input->post('biller'),
					"total" 		=> $this->input->post('grand_total'),
					"discount_value"=> $discount_value,
					"tax_value" 	=> $this->input->post('total_tax'),
					"note" 			=> $this->input->post('note'),
					"internal_note" => $this->input->post('internal_note'),
					"user"			=> $this->session->userdata('user_id'),
					);
			$invoice = array(
				"receipt_amount" 	=> $this->input->post('grand_total')
			);

			$this->input->post('table_data1');

			$js_data = json_decode($this->input->post('table_data1'));
			$php_data = json_decode($this->input->post('table_data'));
			
			if($this->sales_return_model->editModel($id,$data,$invoice))
			{	
				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $id,
						'message'  => 'Sales Return Updated'
					);
				$this->log_model->insert_log($log_data);
				if($js_data!=null){
					foreach ($js_data as $key => $value) {
						if($value=='delete'){
							//echo " delete".$key;
							$product_id =  $php_data[$key];
							if($this->sales_return_model->deleteSalesReturnItems($id,$product_id,$warehouse_id,$old_warehouse_id)){
								//echo " 1.Dsuccess";
							}
						}
						else if($value==null){
							if($warehouse_id != $old_warehouse_id AND $php_data[$key] !=null){
								$product_id =  $php_data[$key];
								if($this->sales_return_model->changeWarehouseDeleteSalesReturnItems($id,$product_id,$warehouse_id,$old_warehouse_id)){
									//echo " 1.Dsuccess";
								}
							}
							else if($warehouse_change == "yes"){
								$product_id =  $php_data[$key];
								if($this->sales_return_model->changeWarehouseDeleteSalesReturnItems($id,$product_id,$warehouse_id,$old_warehouse_id)){
									//echo " 1.Dsuccess";
								}
							}
						}
						else{
							if(isset($value->tax_type)){
								$tax_type = $value->tax_type;
							}else{
								$tax_type = "";
							}
							$product_id = $value->product_id;
							$quantity = $value->quantity;
							$data = array(
									"product_id" => $value->product_id,
									"quantity" => $value->quantity,
									"price" => $value->price,
									"gross_total" => $value->total,
									"discount_id" => $value->discount_id,
									"discount_value" => $value->discount_value,
									"discount" => $value->discount,
									"tax_type" => $tax_type,
									"igst" => $value->igst,
									"igst_tax" => $value->igst_tax,
									"cgst" => $value->cgst,
									"cgst_tax" => $value->cgst_tax,
									"sgst" => $value->sgst,
									"sgst_tax" => $value->sgst_tax,
									"sale_return_id" => $id
								);
							if($old_quantity = $this->sales_return_model->checkProductInSalesReturn($id,$product_id)){
								$this->sales_return_model->updateQuantity($id,$product_id,$warehouse_id,$quantity,$old_quantity,$data);
							}
							else{
								if($this->sales_return_model->addSalesReturnItem($data,$product_id,$warehouse_id,$quantity)){
									//echo " 1 Asuccess add";
								}
								else{

								}
							}
						}
					}
				}
				redirect('sales_return','refresh');
			}
		}
		/*echo "<pre>";
		print_r($js_data);
		print_r($php_data);*/
	}

	public function payment($id)
	{
		$data['data'] 			= $this->sales_return_model->get_record_with_invoice($id);
		// $data['to_ledger'] = $this->purchase_model->getToLedger($data['data'][0]->supplier_id);
		// echo '<pre>';
		// print_r($this->ledger_model->get_single_record($this->user_model->get_single_user_record($data['data']->customer_id)->ledger_id));
		// echo $data['data']->customer_id;
		// exit;
		$data['to_ledger'] 		= $this->ledger_model->get_single_record($this->user_model->get_single_user_record($data['data']->customer_id)->ledger_id);
		$data['to_mobile']		= $this->user_model->get_single_user_record($data['data']->customer_id)->phone;
		$data['from_ledger'] 	= $this->ledger_model->get_records_by_accountgroup_category(array("Income","Assets"));
		$data['company'] 		= $this->purchase_model->getCompany();
		$data['p_reference_no'] = $this->receipt_model->generateReferenceNo();
		$data['payment_method'] = $this->payment_method_model->payUserData();
		
		// echo '<pre>';
		// print_r($data);
		// exit;
		
		$this->load->view('sales_return/payment',$data);
	}

	public function view($id){
		//echo $id;
		if(!$this->user_model->has_permission("view_sales"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			$data['data'] = $this->sales_return_model->getRecord($id);

			if($data['data'] == null)
			{
				redirect('sales_return','refresh');
			}

			// $data['items'] 			= $this->sales_model->getItems($id);
			$data['company'] 			= $this->purchase_model->getCompany();

			$data['customer'] 			= $this->user_model->get_user_records_by_role("customer"); 
			$data['biller_id'] 			= $data['data'][0]->biller_id;
			$data['biller_state_id']	= $this->sales_model->getBillerStateIdFromBiller($data['data'][0]->biller_id);
			$data['customer_data'] 		= $this->customer_model->getCustomerData($data['data'][0]->customer_id);
			$data['shipping_state_id'] 	= $data['customer_data']->shipping_state_id;
			
			$data['discount'] 			= $this->sales_return_model->getDiscount();
			
			$data['items'] 				= $this->sales_return_model->getSalesReturnItems($data['data'][0]->id,$data['data'][0]->warehouse_id);		

			// echo '<pre>';
			// print_r($data);
			// exit;

			$this->load->view('sales_return/view',$data);
		}
	}

	public function pdf($id){

		// $data['data'] = $this->sales_model->getDetails($id);
		// $data['items'] = $this->sales_model->getItems($id);
		// $data['company'] = $this->purchase_model->getCompany();
		// echo '<pre>';
		// print_r($data);
		// exit;
		if(!$this->user_model->has_permission("download_sales"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			$log_data = array(
					'user_id'  => $this->session->userdata('user_id'),
					'table_id' => $id,
					'message'  => 'Invoice Generated'
				);
			$this->log_model->insert_log($log_data);
			ob_start();
			$html = ob_get_clean();
			$html = utf8_encode($html);

			$data['data'] = $this->sales_return_model->getRecord($id);
			$data['items'] = $this->sales_return_model->getItems($id);
			$data['company'] = $this->purchase_model->getCompany();
			// echo '<pre>';
			// print_r($data);
			// exit;
			$pdf_page = $this->sales_model->getPDFPage();
			if($pdf_page=="invoice-1"){
				$html = $this->load->view('sales_return/pdf',$data,true);
			}
			else{
				$html = $this->load->view('sales_return/pdf2',$data,true);
			}

			include(APPPATH.'third_party/mpdf60/mpdf.php');
	        $mpdf = new mPDF();
	        $mpdf->allow_charset_conversion = true;
	        $mpdf->charset_in = 'UTF-8';
	        $mpdf->WriteHTML($html);
	        $mpdf->Output($data['data'][0]->reference_no.'.pdf','I');
	    }
	}

	public function delete($id)
	{
		if(!$this->user_model->has_permission("delete_sales_return"))
		{
			$this->load->view('layout/restricted');	
		}
		else
		{
			if($this->sales_return_model->delete($id))
			{
				$data['sales_return'] = $this->sales_return_model->getRecord($id);
				$data['sale_return_item'] = $this->sales_return_model->getSalesReturnItemsForDelete($id);

				// echo '<pre>';
				// print_r($data);
				// exit;
				foreach ($data['sale_return_item'] as $row) {
					$this->sales_return_model->restoreQuantity($id,$row->product_id,$data['sales_return'][0]->warehouse_id,$row->quantity);
				}
				$log_data = array(
						'user_id'  => $this->session->userdata('user_id'),
						'table_id' => $id,
						'message'  => 'Sales Return Deleted'
					);
				$this->log_model->insert_log($log_data);
				redirect('sales_return','refresh');
			}
			else{
				redirect('sales_return','refresh');
			}
		}
	}

	public function get_single_invoice_record($sales_id)
	{
		$data['invoice'] 	= $this->sales_return_model->get_single_invoice_record($sales_id);
		$data['items']		= $this->sales_model->getSalesItems($sales_id,$data['invoice']->warehouse);
		$data['discount'] 	= $this->purchase_return_model->getDiscount();

		echo json_encode($data);
	}
}
?>