<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Reports extends MY_Controller
{
	function __construct() {
		parent::__construct();
		
	}
	public function index(){
		$this->daily();
	} 
	public function purchase(){
		$data['data'] = $this->reports_model->getPurchase();
		$data['purchase_items'] = $this->reports_model->getPurchaseItems();
		$data['products'] = $this->reports_model->getProduct();
		$data['user'] = $this->reports_model->getUsers();
		$data['supplier'] = $this->reports_model->getSuppliers();
		$data['branch'] = $this->warehouse_model->getWarehouse();
		$this->load->view('reports/purchase',$data);
	}

	public function purchase_report(){
		$reference_no = $this->input->post('reference_no');
		$user_id = $this->input->post('user');
		$supplier_id = $this->input->post('supplier');
		$warehouse_id = $this->input->post('warehouse');
	 	$start_date =  $this->input->post('start_date');
		$end_date =  $this->input->post('end_date');
		if($this->input->post('submit') == "Submit"){
			$data['data'] = $this->reports_model->getPurchaseDetails($reference_no,$user_id,$supplier_id,$warehouse_id,$start_date,$end_date);
			$data['purchase_items'] = $this->reports_model->getPurchaseItems();
			$data['products'] = $this->reports_model->getProduct();
			$data['user'] = $this->reports_model->getUsers();
			$data['supplier'] = $this->reports_model->getSuppliers();
			$data['warehouse'] = $this->warehouse_model->getWarehouse();
			$this->load->view('reports/purchase',$data);
		}
		else if($this->input->post('submit') == "PDF"){
			$log_data = array(
					'user_id'  => $this->session->userdata('user_id'),
					'table_id' => 0,
					'message'  => 'Purchase PDF Generated'
				);
			$this->log_model->insert_log($log_data);
			ob_start();
			$html = ob_get_clean();
			$html = utf8_encode($html);

			$data['purchase'] = $this->reports_model->getPurchaseDetails($reference_no,$user_id,$supplier_id,$warehouse_id,$start_date,$end_date);

			$data['purchase_items'] = $this->reports_model->getPurchaseItems();
			$data['products'] = $this->reports_model->getProduct();
			$html = $this->load->view('reports/purchase_pdf',$data,true);

			include(APPPATH.'third_party/mpdf/mpdf.php');
	        $mpdf = new mPDF();
	        $mpdf->allow_charset_conversion = true;
	        $mpdf->charset_in = 'UTF-8';
	        $mpdf->WriteHTML($html);
	        $mpdf->Output('Purchase.pdf','I');
		}
		else if($this->input->post('submit') == "CSV"){
			$log_data = array(
					'user_id'  => $this->session->userdata('user_id'),
					'table_id' => 0,
					'message'  => 'Purchase CSV Generated'
				);
			$this->log_model->insert_log($log_data);
			ob_start();
			$this->load->dbutil();
			$delimiter = ",";
	        $newline = "\r\n";
	        $filename = "products_report.csv";
			$data  = $this->reports_model->getPurchaseDetailsForCSV($reference_no,$user_id,$supplier_id,$warehouse_id,$start_date,$end_date);
			/*$products = $this->reports_model->getPurchaseProduct();
			foreach ($data as  $key => $row) {
				foreach ($products as $value) {
					if($row->purchase_id == $value->purchase_id){
						if(!isset($data[$key]->product)){
							$data[$key]->product = $value->name.'('.$value->quantity.")";
						}
						else{
							$data[$key]->product .= ','.$value->name.'('.$value->quantity.")";
						}
					}
				}
			}*/
			$data = $this->dbutil->csv_from_result($data, $delimiter, $newline);
        	force_download($filename, $data);
		}
		else if($this->input->post('submit') == "Print"){
			$log_data = array(
					'user_id'  => $this->session->userdata('user_id'),
					'table_id' => 0,
					'message'  => 'Purchase Print Generated'
				);
			$this->log_model->insert_log($log_data);
			$data['purchase'] = $this->reports_model->getPurchaseDetails($reference_no,$user_id,$supplier_id,$warehouse_id,$start_date,$end_date);
			$data['purchase_items'] = $this->reports_model->getPurchaseItems();
			$data['products'] = $this->reports_model->getProduct();
			$html = $this->load->view('reports/purchase_pdf',$data);

			
		}
		else{
			redirect('reports/purchase','refresh');
		}
	}
	public function purchase_pdf(){
		ob_start();
		$html = ob_get_clean();
		$html = utf8_encode($html);

		$data['purchase'] = $this->reports_model->getPurchase();
		$data['purchase_items'] = $this->reports_model->getPurchaseItems();
		$data['products'] = $this->reports_model->getProduct();
		$html = $this->load->view('reports/purchase_pdf',$data,true);

		include(APPPATH.'third_party/mpdf/mpdf.php');
        $mpdf = new mPDF();
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';
        $mpdf->WriteHTML($html);
        $mpdf->Output('purchase.pdf','I');
	}
	public function purchase_return(){
		$data['purchase_return'] = $this->reports_model->getPurchaseReturn();
		$data['purchase_return_items'] = $this->reports_model->getPurchaseReturnItems();
		$data['products'] = $this->reports_model->getProduct();
		$data['user'] = $this->reports_model->getUsers();
		$data['supplier'] = $this->reports_model->getSuppliers();
		$data['warehouse'] = $this->warehouse_model->getWarehouse();
		$this->load->view('reports/purchase_return',$data);
	}
	public function purchase_return_report(){
		$reference_no = $this->input->post('reference_no');
		$user_id = $this->input->post('user');
		$supplier_id = $this->input->post('supplier');
		$warehouse_id = $this->input->post('warehouse');
	 	$start_date =  $this->input->post('start_date');
		$end_date =  $this->input->post('end_date');
		if($this->input->post('submit') == "Submit"){
			$data['purchase_return'] = $this->reports_model->getPurchaseReturnDetails($reference_no,$user_id,$supplier_id,$warehouse_id,$start_date,$end_date);
			$data['purchase_return_items'] = $this->reports_model->getPurchaseReturnItems();
			$data['products'] = $this->reports_model->getProduct();
			$data['user'] = $this->reports_model->getUsers();
			$data['supplier'] = $this->reports_model->getSuppliers();
			$data['warehouse'] = $this->warehouse_model->getWarehouse();
			$this->load->view('reports/purchase_return',$data);
		}
		else if($this->input->post('submit') == "PDF"){
			$log_data = array(
					'user_id'  => $this->session->userdata('user_id'),
					'table_id' => 0,
					'message'  => 'Purchase Return PDF Generated'
				);
			$this->log_model->insert_log($log_data);
			ob_start();
			$html = ob_get_clean();
			$html = utf8_encode($html);

			$data['purchase_return'] = $this->reports_model->getPurchaseReturnDetails($reference_no,$user_id,$supplier_id,$warehouse_id,$start_date,$end_date);

			$data['purchase_return_items'] = $this->reports_model->getPurchaseReturnItems();
			$data['products'] = $this->reports_model->getProduct();
			$html = $this->load->view('reports/purchase_return_pdf',$data,true);

			include(APPPATH.'third_party/mpdf/mpdf.php');
	        $mpdf = new mPDF();
	        $mpdf->allow_charset_conversion = true;
	        $mpdf->charset_in = 'UTF-8';
	        $mpdf->WriteHTML($html);
	        $mpdf->Output('purchase_return.pdf','I');
		}
		else if($this->input->post('submit') == "CSV"){
			$log_data = array(
					'user_id'  => $this->session->userdata('user_id'),
					'table_id' => 0,
					'message'  => 'Purchase Return CSV Generated'
				);
			$this->log_model->insert_log($log_data);
			ob_start();
			$this->load->helper('download');
			$this->load->dbutil();
			$this->load->helper('file');
			$delimiter = ",";
	        $newline = "\r\n";
	        $filename = "products_report.csv";
			$data  = $this->reports_model->getPurchaseReturnDetailsForCSV($reference_no,$user_id,$supplier_id,$warehouse_id,$start_date,$end_date);
			/*$products = $this->reports_model->getPurchaseReturnProduct();
			foreach ($data as  $key => $row) {
				foreach ($products as $value) {
					if($row->id == $value->purchase_return_id){
						if(!isset($data[$key]->product)){
							$data[$key]->product = $value->name.'('.$value->quantity.")";
						}
						else{
							$data[$key]->product .= ','.$value->name.'('.$value->quantity.")";
						}
					}
				}
			}*/
			$data = $this->dbutil->csv_from_result($data, $delimiter, $newline);
        	force_download($filename, $data);
		}

		else if($this->input->post('submit') == "Print"){
			$log_data = array(
					'user_id'  => $this->session->userdata('user_id'),
					'table_id' => 0,
					'message'  => 'Purchase Return Print Generated'
				);
			$this->log_model->insert_log($log_data);

			$data['purchase_return'] = $this->reports_model->getPurchaseReturnDetails($reference_no,$user_id,$supplier_id,$warehouse_id,$start_date,$end_date);

			$data['purchase_return_items'] = $this->reports_model->getPurchaseReturnItems();
			$data['products'] = $this->reports_model->getProduct();
			$html = $this->load->view('reports/purchase_return_pdf',$data);

			
		}
		else{
			redirect('reports/purchase_return','refresh');
		}
	}
	public function purchase_return_pdf(){
		ob_start();
		$html = ob_get_clean();
		$html = utf8_encode($html);

		$data['purchase_return'] = $this->reports_model->getPurchaseReturn();
		$data['purchase_return_items'] = $this->reports_model->getPurchaseReturnItems();
		$data['products'] = $this->reports_model->getProduct();
		$html = $this->load->view('reports/purchase_return_pdf',$data,true);

		include(APPPATH.'third_party/mpdf/mpdf.php');
        $mpdf = new mPDF();
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';
        $mpdf->WriteHTML($html);
        $mpdf->Output('purchase_return.pdf','I');
	}
	public function sales(){
		$data['sales'] = $this->reports_model->getSales();
		$data['sales_items'] = $this->reports_model->getSalesItems();
		$data['products'] = $this->reports_model->getProduct();
		$data['biller'] = $this->reports_model->getBillers();
		$data['user'] = $this->reports_model->getUsers();
		$data['warehouse'] = $this->warehouse_model->getWarehouse();
		$data['customer'] = $this->reports_model->getCustomers();
		$data['discount'] = $this->reports_model->getDiscounts();
		$this->load->view('reports/sales',$data);
	}
	public function sales_report(){
		$reference_no = $this->input->post('reference_no');
		$user_id = $this->input->post('user');
		$biller_id = $this->input->post('biller');
		$warehouse_id = $this->input->post('warehouse');
		$customer_id = $this->input->post('customer');
		$discount_id = $this->input->post('discount');
	 	$start_date =  $this->input->post('start_date');
		$end_date =  $this->input->post('end_date');
		if($this->input->post('submit') == "Submit"){
			$data['sales'] = $this->reports_model->getSalesDetails($reference_no,$user_id,$biller_id,$warehouse_id,$customer_id,$discount_id,$start_date,$end_date);

			$data['sales_items'] = $this->reports_model->getSalesItems();
			$data['products'] = $this->reports_model->getProduct();
			$data['biller'] = $this->reports_model->getBillers();
			$data['user'] = $this->reports_model->getUsers();
			$data['warehouse'] = $this->warehouse_model->getWarehouse();
			$data['customer'] = $this->reports_model->getCustomers();
			$data['discount'] = $this->reports_model->getDiscounts();
			// echo '<pre>';
			// print_r($data);
			// exit;
			$this->load->view('reports/sales',$data);
		}
		else if($this->input->post('submit') == "PDF"){
			$log_data = array(
					'user_id'  => $this->session->userdata('user_id'),
					'table_id' => 0,
					'message'  => 'Sales PDF Generated'
				);
			$this->log_model->insert_log($log_data);
			ob_start();
			$html = ob_get_clean();
			$html = utf8_encode($html);

			$data['sales'] = $this->reports_model->getSalesDetails($reference_no,$user_id,$biller_id,$warehouse_id,$customer_id,$discount_id,$start_date,$end_date);

			$data['sales_items'] = $this->reports_model->getSalesItems();
			$data['products'] = $this->reports_model->getProduct();
			$html = $this->load->view('reports/sales_pdf',$data,true);

			include(APPPATH.'third_party/mpdf/mpdf.php');
	        $mpdf = new mPDF();
	        $mpdf->allow_charset_conversion = true;
	        $mpdf->charset_in = 'UTF-8';
	        $mpdf->WriteHTML($html);
	        $mpdf->Output('sales.pdf','I');
		}
		else if($this->input->post('submit') == "CSV"){
			$log_data = array(
					'user_id'  => $this->session->userdata('user_id'),
					'table_id' => 0,
					'message'  => 'Sales CSV Generated'
				);
			$this->log_model->insert_log($log_data);
			ob_start();
			$this->load->helper('download');
			$this->load->dbutil();
			$this->load->helper('file');
			$delimiter = ",";
	        $newline = "\r\n";
	        $filename = "products_report.csv";
			$data  = $this->reports_model->getSalesDetailsForCSV($reference_no,$user_id,$biller_id,$warehouse_id,$customer_id,$discount_id,$start_date,$end_date);
			/*$products = $this->reports_model->getSalesProduct();
			foreach ($data as  $key => $row) {
				foreach ($products as $value) {
					if($row->sales_id == $value->sales_id){
						if(!isset($data[$key]->product)){
							$data[$key]->product = $value->name.'('.$value->quantity.")";
						}
						else{
							$data[$key]->product .= ','.$value->name.'('.$value->quantity.")";
						}
					}
				}
			}*/
			$data = $this->dbutil->csv_from_result($data, $delimiter, $newline);
        	force_download($filename, $data);
		}
		else if($this->input->post('submit') == "Print"){
			$log_data = array(
					'user_id'  => $this->session->userdata('user_id'),
					'table_id' => 0,
					'message'  => 'Sales Print Generated'
				);
			$this->log_model->insert_log($log_data);

			$data['sales'] = $this->reports_model->getSalesDetails($reference_no,$user_id,$biller_id,$warehouse_id,$customer_id,$discount_id,$start_date,$end_date);

			$data['sales_items'] = $this->reports_model->getSalesItems();
			$data['products'] = $this->reports_model->getProduct();
			$html = $this->load->view('reports/sales_pdf.pdf',$data);

			
		}
		else{
			redirect('reports/sales','refresh');
		}
	}
	public function sales_pdf(){
		ob_start();
		$html = ob_get_clean();
		$html = utf8_encode($html);

		$data['sales'] = $this->reports_model->getSales();
		$data['sales_items'] = $this->reports_model->getSalesItems();
		$data['products'] = $this->reports_model->getProduct();
		$html = $this->load->view('reports/sales_pdf',$data,true);

		include(APPPATH.'third_party/mpdf/mpdf.php');
        $mpdf = new mPDF();
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';
        $mpdf->WriteHTML($html);
        $mpdf->Output('sales.pdf','I');
	}
	public function sales_return(){
		$data['sales_return'] = $this->reports_model->getSalesReturn();
		$data['sales_return_items'] = $this->reports_model->getSalesReturnItems();
		$data['products'] = $this->reports_model->getProduct();
		$data['biller'] = $this->reports_model->getBillers();
		$data['user'] = $this->reports_model->getUsers();
		$data['warehouse'] = $this->warehouse_model->getWarehouse();
		$data['customer'] = $this->reports_model->getCustomers();
		// $data['discount'] = $this->reports_model->getDiscounts();
		$this->load->view('reports/sales_return',$data);
	}
	public function sales_return_report(){
		$reference_no = $this->input->post('reference_no');
		$user_id =$this->input->post('user');
		$biller_id = $this->input->post('biller');
		$warehouse_id = $this->input->post('warehouse');
		$customer_id = $this->input->post('customer');
		// $discount_id = $this->input->post('discount');
	 	$start_date =  $this->input->post('start_date');
		$end_date =  $this->input->post('end_date');
		if($this->input->post('submit') == "Submit"){
			$data['sales_return'] = $this->reports_model->getSalesReturnDetails($reference_no,$user_id,$biller_id,$warehouse_id,$customer_id,$start_date,$end_date);

			$data['sales_return_items'] = $this->reports_model->getSalesReturnItems();
			$data['products'] = $this->reports_model->getProduct();
			$data['biller'] = $this->reports_model->getBillers();
			$data['user'] = $this->reports_model->getUsers();
			$data['warehouse'] = $this->warehouse_model->getWarehouse();
			$data['customer'] = $this->reports_model->getCustomers();
			// $data['discount'] = $this->reports_model->getDiscounts();
			$this->load->view('reports/sales_return',$data);
		}
		else if($this->input->post('submit') == "PDF"){
			$log_data = array(
					'user_id'  => $this->session->userdata('user_id'),
					'table_id' => 0,
					'message'  => 'Sales Return PDF Generated'
				);
			$this->log_model->insert_log($log_data);
			ob_start();
			$html = ob_get_clean();
			$html = utf8_encode($html);

			$data['sales_return'] = $this->reports_model->getSalesReturnDetails($reference_no,$user_id,$biller_id,$warehouse_id,$customer_id,$start_date,$end_date);

			$data['sales_return_items'] = $this->reports_model->getSalesReturnItems();
			$data['products'] = $this->reports_model->getProduct();
			$html = $this->load->view('reports/sales_return_pdf',$data,true);

			include(APPPATH.'third_party/mpdf/mpdf.php');
	        $mpdf = new mPDF();
	        $mpdf->allow_charset_conversion = true;
	        $mpdf->charset_in = 'UTF-8';
	        $mpdf->WriteHTML($html);
	        $mpdf->Output('sales_return.pdf','I');
		}
		else if($this->input->post('submit') == "CSV"){
			$log_data = array(
					'user_id'  => $this->session->userdata('user_id'),
					'table_id' => 0,
					'message'  => 'Sales Return CSV Generated'
				);
			$this->log_model->insert_log($log_data);
			ob_start();
			$this->load->helper('download');
			$this->load->dbutil();
			$this->load->helper('file');
			$delimiter = ",";
	        $newline = "\r\n";
	        $filename = "products_report.csv";
			$data  = $this->reports_model->getSalesReturnDetailsForCSV($reference_no,$user_id,$biller_id,$warehouse_id,$customer_id,$start_date,$end_date);
			/*$products = $this->reports_model->getSalesReturnProduct();
			foreach ($data as  $key => $row) {
				foreach ($products as $value) {
					if($row->id == $value->sale_return_id){
						if(!isset($data[$key]->product)){
							$data[$key]->product = $value->name.'('.$value->quantity.")";
						}
						else{
							$data[$key]->product .= ','.$value->name.'('.$value->quantity.")";
						}
					}
				}
			}*/
			$data = $this->dbutil->csv_from_result($data, $delimiter, $newline);
        	force_download($filename, $data);
		}

		else if($this->input->post('submit') == "Print"){
			$log_data = array(
					'user_id'  => $this->session->userdata('user_id'),
					'table_id' => 0,
					'message'  => 'Sales Return Print Generated'
				);
			$this->log_model->insert_log($log_data);
			$data['sales_return'] = $this->reports_model->getSalesReturnDetails($reference_no,$user_id,$biller_id,$warehouse_id,$customer_id,$start_date,$end_date);

			$data['sales_return_items'] = $this->reports_model->getSalesReturnItems();
			$data['products'] = $this->reports_model->getProduct();
			$html = $this->load->view('reports/sales_return_pdf',$data);

			
		}
		else{
			redirect('reports/sales_return','refresh');
		}
	}
	public function sales_return_pdf(){
		ob_start();
		$html = ob_get_clean();
		$html = utf8_encode($html);

		$data['sales_return'] = $this->reports_model->getSalesReturn();
		$data['sales_return_items'] = $this->reports_model->getSalesReturnItems();
		$data['products'] = $this->reports_model->getProduct();
		$html = $this->load->view('reports/sales_return_pdf',$data,true);

		include(APPPATH.'third_party/mpdf/mpdf.php');
        $mpdf = new mPDF();
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';
        $mpdf->WriteHTML($html);
        $mpdf->Output('sales_return.pdf','I');
	}
	public function products(){
		$data['data'] = $this->reports_model->getPurchaseSales();
		$data['warehouse'] = $this->warehouse_model->getWarehouse();
		$data['products'] = $this->reports_model->getProduct();
		$this->load->view('reports/products',$data);
	}
	public function products_report(){
		$product_id = $this->input->post('product');
		$warehouse_id = $this->input->post('warehouse');
	 	$start_date =  $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		if($this->input->post('submit') == "Submit"){
			$data['data'] = $this->reports_model->getProductsDetails($product_id,$warehouse_id,$start_date,$end_date);
			$data['products'] = $this->reports_model->getProduct();
			$data['warehouse'] = $this->warehouse_model->getWarehouse();
			$this->load->view('reports/products',$data);
		}
		else if($this->input->post('submit') == "PDF"){
			$log_data = array(
					'user_id'  => $this->session->userdata('user_id'),
					'table_id' => 0,
					'message'  => 'Product PDF Generated'
				);
			$this->log_model->insert_log($log_data);
			ob_start();
			$html = ob_get_clean();
			$html = utf8_encode($html);

			$data['data'] = $this->reports_model->getProductsDetails($product_id,$warehouse_id,$start_date,$end_date);
			$html = $this->load->view('reports/products_pdf',$data,true);

			include(APPPATH.'third_party/mpdf/mpdf.php');
	        $mpdf = new mPDF();
	        $mpdf->allow_charset_conversion = true;
	        $mpdf->charset_in = 'UTF-8';
	        $mpdf->WriteHTML($html);
	        $mpdf->Output('products.pdf','I');
		}
		else if($this->input->post('submit') == "CSV"){
			$log_data = array(
					'user_id'  => $this->session->userdata('user_id'),
					'table_id' => 0,
					'message'  => 'Product CSV Generated'
				);
			$this->log_model->insert_log($log_data);
            $this->load->dbutil();
	        $delimiter = ",";
	        $newline = "\r\n";
	        $filename = "products.csv";
	        $result = $this->reports_model->getProductsDetailsForCSV($product_id,$warehouse_id,$start_date,$end_date);
	        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
	        force_download($filename, $data);
		}
		else{
			redirect('reports/products','refresh');
		}
	}
	public function daily(){
		$this->load->model('ion_auth_model');
		$data['current_month_sales'] = $this->reports_model->currentMonthSales();
		$data['profit'] = $this->reports_model->profit();
		$data['day_profit'] = $this->reports_model->dayProfit();
		$data['sales'] =  $this->reports_model->daySales();
		$data['sales_return'] =  $this->reports_model->daySalesReturn();
		$this->load->view('reports/daily',$data);
	}

	public function receivable(){
		$data['data'] = $this->reports_model->receivable();
		$this->load->view('reports/receivable',$data);
	}
	public function receivable_report(){
		$start_date =  $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		if($this->input->post('submit') == "Submit"){
			$data['data'] = $this->reports_model->getReceivableAmountDetails($start_date,$end_date);
			$this->load->view('reports/receivable',$data);
		}
	}
	public function payable(){
		$data['data'] = $this->reports_model->payable();
		$this->load->view('reports/payable',$data);
	}
	public function payable_report(){
		
		$start_date =  $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		if($this->input->post('submit') == "Submit"){
			$data['data'] = $this->reports_model->getPayableAmountDetails($start_date,$end_date);
			$this->load->view('reports/payable',$data);
		}
	}
	public function warehouse_report($warehouse_id = NULL){
		
		if($warehouse_id == NULL && $warehouse_id == 0)
		{
			$data['data'] = $this->reports_model->getWarehouseWiseProducts(0);
			$data['warehouse'] = $this->transfer_model->getWarehouse();	
			$data['selected_warehouse'] = 0;
		}
		else
		{
			$data['data'] = $this->reports_model->getWarehouseWiseProducts($warehouse_id);
			$data['warehouse'] = $this->transfer_model->getWarehouse();
			$data['selected_warehouse'] = $warehouse_id;
		}
		
		$this->load->view('reports/warehouse_report',$data);
	}

	public function balance_sheet(){


	}

	public function pl_report(){

		// $data['company'] 				= $this->purchase_model->getCompany();
		// $data['sales'] 					= $this->reports_model->getFilterSales($from_date, $to_date);
		// $data['sales_cost'] 			= $this->reports_model->getTotalSalesCost();
		// $data['sales_profit']			= $data['sales'] - $data['sales_cost'];
		// $data['sales_tax']				= $this->reports_model->getTotalSalesTax();
		// $data['sales_after_tax']		= $data['sales'] - $data['sales_tax'];
		// $data['sales_profit_after_tax'] = $data['sales_profit'] - $data['sales_tax'];
		// $data['sales_profit_before_tax']= $data['sales_profit'];
		// $data['expense']				= $this->reports_model->getTotalExpense();			


		// echo '<pre>';
		// print_r($data);		
		// exit;
		if($_POST)
		{	
			$branch 	= $this->input->post('branch');
			$start_date	= $this->input->post('start_date');
			$end_date	= $this->input->post('end_date');

			$data['sales'] 			= $this->reports_model->getFilterSales("2018-10-01",$end_date);
			$data['sales_return'] 	= $this->reports_model->getFilterSalesReturns("2018-10-01",$end_date);
			$data['purchases'] 		= $this->reports_model->getFilterPurchases("2018-10-01",$end_date);
			$data['purchase_return']= $this->reports_model->getFilterPurchasesReturns("2018-10-01",$end_date);

			$data['total_sales']			= $this->reports_model->getTotalSales("2018-10-01", $end_date);
			$data['total_sales_return']		= $this->reports_model->getTotalSalesReturn("2018-10-01", $end_date);
			$data['total_purchase']			= $this->reports_model->getTotalPurchases("2018-10-01", $end_date);
			$data['total_purchase_return']	= $this->reports_model->getTotalPurchasesReturn("2018-10-01", $end_date);
			$data['closing_stock'] 			= $this->reports_model->getClosingStock();
			$data['opening_stock'] 			= $this->reports_model->getOpeningStock();

			$data['start_date'] 	= $start_date;
			$data['end_date'] 		= $end_date;

			$data['expenses']		= $this->reports_model->getExpense();
			$data['taxes']			= $this->reports_model->getTaxes();
			$data['discount']		= $this->reports_model->getSalesDiscount();

			// echo '<pre>';
			// print_r($data);
			// exit;

			$this->load->view('reports/pl_report_print',$data);
		}
		else
		{
			$data['branch']		= $this->branch_model->getBranch();
			$this->load->view('reports/pl_report',$data);
		}
		
		
	}

	public function pl_report_print(){

		// $data['company'] 				= $this->purchase_model->getCompany();
		// $data['sales'] 					= $this->reports_model->getFilterSales($from_date, $to_date);
		// $data['sales_cost'] 			= $this->reports_model->getTotalSalesCost();
		// $data['sales_profit']			= $data['sales'] - $data['sales_cost'];
		// $data['sales_tax']				= $this->reports_model->getTotalSalesTax();
		// $data['sales_after_tax']		= $data['sales'] - $data['sales_tax'];
		// $data['sales_profit_after_tax'] = $data['sales_profit'] - $data['sales_tax'];
		// $data['sales_profit_before_tax']= $data['sales_profit'];
		// $data['expense']				= $this->reports_model->getTotalExpense();			


		// echo '<pre>';
		// print_r($data);
		// exit;

		$data['branch']		= $this->branch_model->getBranch();


		$this->load->view('reports/pl_report_print',$data);
		
	}
	
}
?>