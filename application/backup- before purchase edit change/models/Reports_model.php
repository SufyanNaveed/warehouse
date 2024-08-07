<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Reports_model extends CI_Model
{
	function __construct() {
		parent::__construct();
		$this->load->model('user_model');
		
	}
	public function index(){
		
	} 
	public function getPurchase(){
		$this->db->select('p.date,p.reference_no,p.total,w.warehouse_name,s.first_name as supplier_name,pi.purchase_id,pi.quantity,pr.name')
		         ->from('purchases p')
		         ->join('warehouse w','w.warehouse_id = p.warehouse_id')
		         ->join('users s','s.id = p.supplier_id')
		         ->join('purchase_items pi','pi.purchase_id = p.purchase_id')
		         ->join('products pr','pr.product_id = pi.product_id')
		         ->group_by('p.reference_no');
		return $this->db->get()->result();
	}
	public function getPurchaseItems(){
		return $this->db->get('purchase_items')->result();
	}
	public function getProduct(){
		return $this->db->get('products')->result();
	}
	public function getUsers(){
		return $this->db->get('users')->result();
	}
	public function getSuppliers(){
		return $this->user_model->get_user_records_by_role('supplier');
	}
	public function getWarehouses(){
		return $this->db->get('warehouse')->result();
	}
	public function getBillers(){
		return $this->user_model->get_user_records_by_role('sales_person');
	}
	public function getCustomers(){
		return $this->user_model->get_user_records_by_role('customer');
	}
	public function getDiscounts(){
		return $this->db->get('discount')->result();
	}

	public function getExpense(){
		
		$group_title = array("Purchase Accounts","Sundry Creditors");
		return $this->db->select('l.title,l.closing_balance')
	                    ->from('ledger l')
	                    ->join('account_group ag','ag.id = l.accountgroup_id')
						->where_not_in('ag.group_title',$group_title)
						->where('ag.category',"Expense")
						// ->or_where('ag.category',"Liabilities")
						->get()
						->result();
	}

	public function getTaxes(){

		$sales_tax =  $this->db->select('SUM(s.tax_value) as sales_tax')
	                    	   ->from('sales s')
	                    	   ->get()
							   ->row()
							   ->sales_tax;

		$sales_return_tax =  $this->db->select('SUM(s.tax_value) as sales_return_tax')
	                    	   ->from('sales_return s')
	                    	   ->get()
							   ->row()
							   ->sales_return_tax;

		return $sales_tax - $sales_return_tax;

	}

	public function getSalesDiscount(){
		
		return  $this->db->select('SUM(s.discount_value) as discount_value')
	                    	   ->from('sales s')
	                    	   ->get()
							   ->row()
							   ->discount_value;


	}

	public function getTotalSales($from_date,$to_date){
		return $this->db->select('SUM(s.total) as total_sales')
						->from('sales s')
						->where('s.date >=',$from_date)
						->where('s.date <=',$to_date)
						->get()
						->row()
						->total_sales;
	}

	public function getClosingStock(){
		return $this->db->select('SUM(p.quantity*p.cost) as closing_stock')
						->from('products p')
						->get()
						->row()
						->closing_stock;
	}

	public function getOpeningStock(){
		
		$closing_stock = $this->getClosingStock();
		
		$selling_stock = $this->db->select('SUM(si.quantity*p.cost) as selling_stock')
								  ->from('sales_items si')
								  ->join('products p','si.product_id = p.product_id')
								  ->get()
								  ->row()
								  ->selling_stock;

		return $selling_stock + $closing_stock;
	}

	public function getTotalSalesReturn($from_date,$to_date){
		return $this->db->select('SUM(s.total) as total_sales_return')
						->from('sales_return s')
						->where('s.date >=',$from_date)
						->where('s.date <=',$to_date)
						->get()
						->row()
						->total_sales_return;
	}

	public function getFilterSales($from_date, $to_date){

		return $this->db->select('s.*')
						->from('sales s')
						->where('s.date >=',$from_date)
						->where('s.date <=',$to_date)
						->get()
						->result();
	}

	public function getFilterSalesReturns($from_date, $to_date){

		return $this->db->select('s.*')
						->from('sales_return s')
						->where('s.date >=',$from_date)
						->where('s.date <=',$to_date)
						->get()
						->result();
	}

	public function getFilterPurchases($from_date, $to_date){

		return $this->db->select('s.*')
						->from('purchases s')
						->where('s.date >=',$from_date)
						->where('s.date <=',$to_date)
						->get()
						->result();
	}

	public function getFilterPurchasesReturns($from_date, $to_date){

		return $this->db->select('s.*')
						->from('sales_return s')
						->where('s.date >=',$from_date)
						->where('s.date <=',$to_date)
						->get()
						->result();
	}

	public function getTotalSalesCost(){
		return $this->db->select('SUM(p.cost) as total_sales_cost')
						->from('sales_items si')
						->join('products p','p.product_id = si.product_id')
						->get()
						->row()
						->total_sales_cost;
	}

	public function getTotalPurchases($from_date,$to_date){
		return $this->db->select('SUM(s.total) as total_purchases')
						->from('purchases s')
						->where('s.date >=',$from_date)
						->where('s.date <=',$to_date)
						->get()
						->row()
						->total_purchases;
	}

	public function getTotalPurchasesReturn($from_date,$to_date){
		return $this->db->select('SUM(s.total) as total_purchases_return')
						->from('purchase_return s')
						->where('s.date >=',$from_date)
						->where('s.date <=',$to_date)
						->get()
						->row()
						->total_purchases_return;
	}

	public function getTotalSalesTax(){
		return $this->db->select('SUM(s.tax_value) as total_sales_tax')
						->from('sales s')						
						->get()
						->row()
						->total_sales_tax;
	}

	public function getTotalExpense(){
		return $this->db->select('l.*,ag.category')
						->from('ledger l')						
						->join('account_group ag','ag.id = l.accountgroup_id')
						->where('ag.category','Expense')
						->get()
						->result();
						
	}
	public function getPurchaseDetails($reference_no,$user_id,$supplier_id,$warehouse_id,$start_date,$end_date){

		$data = $this->db->query('
									SELECT DISTINCT 
										p.date,
										p.reference_no,
										w.warehouse_name,
										s.first_name as supplier_name,
										p.purchase_id,
										p.total 
									FROM purchases p 
									INNER JOIN warehouse w ON w.warehouse_id = p.warehouse_id 
									INNER JOIN users s ON s.id = p.supplier_id 
									WHERE 
										(p.warehouse_id = ? OR ? IN ("",NULL)) 
									AND 
										(p.supplier_id = ? OR ? IN ("",NULL))
									AND
										(p.reference_no = ? OR ? IN ("",NULL))
									AND
										(p.user = ? OR ? IN ("",NULL))
									AND
										(p.date >= ? OR ? IN ("",NULL))
									AND
										(p.date <= ? OR ? IN ("",NULL))
								',
									array(
											$warehouse_id,
											$warehouse_id,
											$supplier_id,
											$supplier_id,
											$reference_no,
											$reference_no,
											$user_id,
											$user_id,
											$start_date,
											$start_date,
											$end_date,
											$end_date
										)
								);
		return $data->result();		
	}
	public function getPurchaseDetailsForCSV($reference_no,$user_id,$supplier_id,$warehouse_id,$start_date,$end_date){

		return $this->db->query('
									SELECT DISTINCT 
										p.date,
										p.reference_no,
										w.warehouse_name,
										s.first_name as supplier_name,
										p.purchase_id,
										p.total 
									FROM purchases p 
									INNER JOIN warehouse w ON w.warehouse_id = p.warehouse_id 
									INNER JOIN users s ON s.supplier_id = p.supplier_id 
									WHERE 
										(p.warehouse_id = ? OR ? IN ("",NULL)) 
									AND 
										(p.supplier_id = ? OR ? IN ("",NULL))
									AND
										(p.reference_no = ? OR ? IN ("",NULL))
									AND
										(p.user = ? OR ? IN ("",NULL))
									AND
										(p.date >= ? OR ? IN ("",NULL))
									AND
										(p.date <= ? OR ? IN ("",NULL))
								',
									array(
											$warehouse_id,
											$warehouse_id,
											$supplier_id,
											$supplier_id,
											$reference_no,
											$reference_no,
											$user_id,
											$user_id,
											$start_date,
											$start_date,
											$end_date,
											$end_date
										)
								);	
	}
	public function getPurchaseProduct(){
		$data = $this->db->query('
									SELECT 
										pi.purchase_id,
										pr.name,
										sum(pi.quantity) as quantity
									FROM products pr
									INNER JOIN purchase_items pi ON pi.product_id = pr.product_id
									GROUP BY pr.product_id
								');
		return $data->result();
	}
	public function getPurchaseReturn(){
		$this->db->select('p.date,p.reference_no,p.tax_value,p.total,w.warehouse_name,s.first_name as supplier_name,p.id,pi.quantity,pr.name')
		         ->from('purchase_return p')
		         ->join('warehouse w','w.warehouse_id = p.warehouse_id')
		         ->join('users s','s.id = p.supplier_id')
		         ->join('purchase_return_items pi','pi.purchase_return_id = p.id')
		         ->join('products pr','pr.product_id = pi.product_id')
		         ->group_by('p.reference_no');
		return $this->db->get()->result();
	}
	public function getPurchaseReturnDetails($reference_no,$user_id,$supplier_id,$warehouse_id,$start_date,$end_date){
		$data = $this->db->query('
									SELECT DISTINCT 
										p.date,
										p.reference_no,
										p.id,
										w.warehouse_name,
										s.first_name as supplier_name,
										p.total
									FROM purchase_return p 
									INNER JOIN warehouse w ON w.warehouse_id = p.warehouse_id 
									INNER JOIN users s ON s.id = p.supplier_id 
									INNER JOIN purchase_return_items pi ON pi.purchase_return_id = p.id
									INNER JOIN products pr ON pr.product_id = pi.product_id

									WHERE 
										(p.warehouse_id = ? OR ? IN ("",NULL)) 
									AND 
										(p.supplier_id = ? OR ? IN ("",NULL))
									AND
										(p.reference_no = ? OR ? IN ("",NULL))
									AND
										(p.user = ? OR ? IN ("",NULL))
									AND
										(p.date >= ? OR ? IN ("",NULL))
									AND
										(p.date <= ? OR ? IN ("",NULL))
									
								',
									array(
											$warehouse_id,
											$warehouse_id,
											$supplier_id,
											$supplier_id,
											$reference_no,
											$reference_no,
											$user_id,
											$user_id,
											$start_date,
											$start_date,
											$end_date,
											$end_date
										)
								);
		return $data->result();
	}
	public function getPurchaseReturnDetailsForCSV($reference_no,$user_id,$supplier_id,$warehouse_id,$start_date,$end_date){
		return $this->db->query('
									SELECT DISTINCT 
										p.date,
										p.reference_no,
										p.id,
										w.warehouse_name,
										s.first_name as supplier_name,
										p.total
									FROM purchase_return p 
									INNER JOIN warehouse w ON w.warehouse_id = p.warehouse_id 
									INNER JOIN users s ON s.id = p.supplier_id 
									INNER JOIN purchase_return_items pi ON pi.purchase_return_id = p.id
									INNER JOIN products pr ON pr.product_id = pi.product_id

									WHERE 
										(p.warehouse_id = ? OR ? IN ("",NULL)) 
									AND 
										(p.supplier_id = ? OR ? IN ("",NULL))
									AND
										(p.reference_no = ? OR ? IN ("",NULL))
									AND
										(p.user = ? OR ? IN ("",NULL))
									AND
										(p.date >= ? OR ? IN ("",NULL))
									AND
										(p.date <= ? OR ? IN ("",NULL))
									
								',
									array(
											$warehouse_id,
											$warehouse_id,
											$supplier_id,
											$supplier_id,
											$reference_no,
											$reference_no,
											$user_id,
											$user_id,
											$start_date,
											$start_date,
											$end_date,
											$end_date
										)
								);
	}
	public function getPurchaseReturnProduct(){
		$data = $this->db->query('
									SELECT 
										pi.purchase_return_id,
										pr.name,
										sum(pi.quantity) as quantity
									FROM  purchase_return_items pi 
									INNER JOIN products pr ON pr.product_id = pi.product_id
									GROUP BY pi.purchase_return_id, pr.product_id
								');
		return $data->result();
	}
	public function getPurchaseReturnItems(){
		return $this->db->get('purchase_return_items')->result();
	}
	public function getSales(){
		$this->db->select('s.*,b.first_name as biller_name,c.first_name as customer_name,si.sales_id,si.quantity,pr.name')
		         ->from('sales s')
		         ->join('users b','b.id = s.biller_id')
		         ->join('users c','c.id = s.customer_id')
		         ->join('sales_items si','si.sales_id = s.sales_id')
		         ->join('products pr','pr.product_id = si.product_id')
		         ->group_by('s.reference_no');
		return $this->db->get()->result();
	}
	public function getSalesItems(){
		return $this->db->get('sales_items')->result();
	}
	public function getSalesDetails($reference_no,$user_id,$biller_id,$warehouse_id,$customer_id,$discount_id,$start_date,$end_date)
	{
									
		$data = $this->db->query('
									SELECT DISTINCT s.*,b.first_name as biller_name,c.first_name as customer_name
									FROM sales s 
									INNER JOIN warehouse w ON w.warehouse_id = s.warehouse_id
									INNER JOIN users b ON b.id = s.biller_id
									INNER JOIN users c ON c.id = s.customer_id
									INNER JOIN sales_items si ON si.sales_id = s.sales_id
									INNER JOIN products pr ON pr.product_id = si.product_id
									
									WHERE  
										(s.warehouse_id = ? OR ? IN ("",NULL))
									AND
										(s.biller_id = ? OR ? IN ("",NULL))
									AND
										(s.customer_id = ? OR ? IN ("",NULL))
									AND
										(s.discount_id = ? OR ? IN ("",NULL))
									AND
										(s.reference_no = ? OR ? IN ("",NULL))
									AND
										(s.user = ? OR ? IN ("",NULL))
									AND
										(s.date >= ? OR ? IN ("",NULL))
									AND
										(s.date <= ? OR ? IN ("",NULL))
								',
									array(
											$warehouse_id,
											$warehouse_id,
											$biller_id,
											$biller_id,
											$customer_id,
											$customer_id,
											$discount_id,
											$discount_id,
											$reference_no,
											$reference_no,
											$user_id,
											$user_id,
											$start_date,
											$start_date,
											$end_date,
											$end_date
										)
								);
		return $data->result();
	}
	public function getSalesDetailsForCSV($reference_no,$user_id,$biller_id,$warehouse_id,$customer_id,$discount_id,$start_date,$end_date){
									
		return $this->db->query('
									SELECT DISTINCT s.date,s.reference_no,s.sales_id,b.first_name as biller_name,c.first_name as customer_name,s.total
									FROM sales s 
									INNER JOIN warehouse w ON w.warehouse_id = s.warehouse_id
									INNER JOIN users b ON b.id = s.biller_id
									INNER JOIN users c ON c.id = s.customer_id
									INNER JOIN sales_items si ON si.sales_id = s.sales_id
									INNER JOIN products pr ON pr.product_id = si.product_id
									
									WHERE  
										(s.warehouse_id = ? OR ? IN ("",NULL))
									AND
										(s.biller_id = ? OR ? IN ("",NULL))
									AND
										(s.customer_id = ? OR ? IN ("",NULL))
									AND
										(s.discount_id = ? OR ? IN ("",NULL))
									AND
										(s.reference_no = ? OR ? IN ("",NULL))
									AND
										(s.user = ? OR ? IN ("",NULL))
									AND
										(s.date >= ? OR ? IN ("",NULL))
									AND
										(s.date <= ? OR ? IN ("",NULL))
								',
									array(
											$warehouse_id,
											$warehouse_id,
											$biller_id,
											$biller_id,
											$customer_id,
											$customer_id,
											$discount_id,
											$discount_id,
											$reference_no,
											$reference_no,
											$user_id,
											$user_id,
											$start_date,
											$start_date,
											$end_date,
											$end_date
										)
								);
	}
	public function getSalesProduct(){
		$data = $this->db->query('
									SELECT 
										si.sales_id,
										pr.name,
										sum(si.quantity) as quantity
									FROM products pr
									INNER JOIN sales_items si ON si.product_id = pr.product_id
									GROUP BY si.sales_id,pr.product_id
								');
		return $data->result();
	}
	public function getSalesReturn(){
		$this->db->select('s.date,s.reference_no,s.tax_value,s.total,b.first_name as biller_name,c.first_name as customer_name,s.id,si.quantity,pr.name')
		         ->from('sales_return s')
		         ->join('users b','b.id = s.biller_id')
		         ->join('users c','c.id = s.customer_id')
		         ->join('sale_return_items si','si.sale_return_id = s.id')
		         ->join('products pr','pr.product_id = si.product_id')
		         ->group_by('s.reference_no');
		return $this->db->get()->result();
	}
	public function getSalesReturnDetails($reference_no,$user_id,$biller_id,$warehouse_id,$customer_id,$start_date,$end_date){

		$data = $this->db->query('
									SELECT DISTINCT s.date,s.reference_no,s.id,b.first_name as biller_name,c.first_name as customer_name,s.total
									FROM sales_return s 
									INNER JOIN warehouse w ON w.warehouse_id = s.warehouse_id
									INNER JOIN users b ON b.id = s.biller_id
									INNER JOIN users c ON c.id = s.customer_id
									INNER JOIN sale_return_items si ON si.sale_return_id = s.id
									INNER JOIN products pr ON pr.product_id = si.product_id
									
									WHERE  
										(s.warehouse_id = ? OR ? IN ("",NULL))
									AND
										(s.biller_id = ? OR ? IN ("",NULL))
									AND
										(s.customer_id = ? OR ? IN ("",NULL))
									AND
										(s.reference_no = ? OR ? IN ("",NULL))
									AND
										(s.user = ? OR ? IN ("",NULL))
									AND
										(s.date >= ? OR ? IN ("",NULL))
									AND
										(s.date <= ? OR ? IN ("",NULL))
								',
									array(
											$warehouse_id,
											$warehouse_id,
											$biller_id,
											$biller_id,
											$customer_id,
											$customer_id,
											$reference_no,
											$reference_no,
											$user_id,
											$user_id,
											$start_date,
											$start_date,
											$end_date,
											$end_date
										)
								);
		return $data->result();
	}
	public function getSalesReturnDetailsForCSV($reference_no,$user_id,$biller_id,$warehouse_id,$customer_id,$discount_id,$start_date,$end_date){

		return $this->db->query('
									SELECT DISTINCT s.date,s.reference_no,s.id,b.first_name as biller_name,c.first_name as customer_name,s.total
									FROM sales_return s 
									INNER JOIN warehouse w ON w.warehouse_id = s.warehouse_id
									INNER JOIN users b ON b.id = s.biller_id
									INNER JOIN users c ON c.id = s.customer_id
									INNER JOIN sale_return_items si ON si.sale_return_id = s.id
									INNER JOIN products pr ON pr.product_id = si.product_id
									
									WHERE  
										(s.warehouse_id = ? OR ? IN ("",NULL))
									AND
										(s.biller_id = ? OR ? IN ("",NULL))
									AND
										(s.customer_id = ? OR ? IN ("",NULL))
									AND
										(s.discount_id = ? OR ? IN ("",NULL))
									AND
										(s.reference_no = ? OR ? IN ("",NULL))
									AND
										(s.user = ? OR ? IN ("",NULL))
									AND
										(s.date >= ? OR ? IN ("",NULL))
									AND
										(s.date <= ? OR ? IN ("",NULL))
								',
									array(
											$warehouse_id,
											$warehouse_id,
											$biller_id,
											$biller_id,
											$customer_id,
											$customer_id,
											$discount_id,
											$discount_id,
											$reference_no,
											$reference_no,
											$user_id,
											$user_id,
											$start_date,
											$start_date,
											$end_date,
											$end_date
										)
								);
	}
	public function getSalesReturnProduct(){
		$data = $this->db->query('
									SELECT 
										si.sale_return_id,
										pr.name,
										sum(si.quantity) as quantity
									FROM products pr
									INNER JOIN sale_return_items si ON si.product_id = pr.product_id
									GROUP BY si.sale_return_id,pr.product_id
								');
		return $data->result();
	}
	public function getSalesReturnItems(){
		return $this->db->get('sale_return_items')->result();
	}
	public function getPurchaseSales(){
		return $this->db->select('pr.product_id,pr.code,pr.name,sum(pi.quantity) as pquantity,sum(pi.quantity*pi.cost) as pptotal,sum(si.quantity) as squantity,sum(si.quantity*si.price) as sptotal,sum(si.quantity*si.price - si.quantity*pi.cost) as profit')
						 ->from('products pr')
						 ->join('purchase_items pi','pi.product_id = pr.product_id')
						 ->join('sales_items si','si.product_id = pr.product_id','left')
						 ->group_by('pr.product_id')
						 ->get()
						 ->result();
	}
	public function getProductsDetails($product_id,$warehouse_id,$start_date,$end_date){
			return $this->db->query('
											SELECT 
												pr.product_id,
												pr.code,
												pr.name,
												sum(pi.quantity) as pquantity,
												sum(pi.quantity*pi.cost) as pptotal, 
												sum(si.quantity) as squantity,
												sum(si.quantity*si.price) as sptotal,
												sum(si.quantity*si.price - si.quantity*pi.cost) as profit
											FROM products pr
											INNER JOIN purchase_items pi ON pi.product_id = pr.product_id
											LEFT JOIN sales_items si ON si.product_id = pr.product_id
											LEFT JOIN purchases p ON p.purchase_id = pi.purchase_id
											LEFT JOIN warehouse w ON w.warehouse_id = p.warehouse_id
											
											WHERE 
												(pr.date >= ? OR ? IN ("",NULL))
											AND
												(pr.date <= ? OR ? IN ("",NULL))
											AND
												(pr.product_id = ? OR ? IN ("",NULL))
											AND
												(w.warehouse_id = ? OR ? IN ("",NULL))
											GROUP BY pr.product_id
									   ',
									    array(
									    	$start_date,
									    	$start_date,
									    	$end_date,
									    	$end_date,
									    	$product_id,
									    	$product_id,
									    	$warehouse_id,
									    	$warehouse_id
										)
									   )->result();
			
	}
	public function getProductsDetailsForCSV($product_id,$start_date,$end_date){
				return $this->db->query('
											SELECT 
												pr.code as "Code",
												pr.name as "Product Name",
												sum(pi.quantity) as "Purchase Quantity",
												sum(si.quantity) as "Sales Quantity",
												sum(si.quantity*si.price) as "Total",
												sum(si.quantity*si.price - si.quantity*pi.cost) as "Profit"
											FROM products pr
											INNER JOIN purchase_items pi ON pi.product_id = pr.product_id
											LEFT JOIN sales_items si ON si.product_id = pr.product_id
											
											WHERE 
												(pr.date >= ? OR ? IN ("",NULL))
											AND
												(pr.date <= ? OR ? IN ("",NULL))
											AND
												(pr.product_id = ? OR ? IN ("",NULL))
											GROUP BY pr.product_id
									   ',
									    array(
									    	$start_date,
									    	$start_date,
									    	$end_date,
									    	$end_date,
									    	$product_id,
									    	$product_id
										)
									   );
	}
	public function getTax(){
		$data['igst'] =  $this->db->select('si.igst,sum(si.igst_tax) as igst_tax')
						->from('sales_items si')
						->group_by('si.igst')
						->get()
						->result();
		$data['cgst'] = $this->db->select('si.cgst,sum(si.cgst_tax) as cgst_tax')
						->from('sales_items si')
						->group_by('si.cgst')
						->get()
						->result();
		$data['sgst'] = $this->db->select('si.sgst,sum(si.sgst_tax) as sgst_tax')
						->from('sales_items si')
						->group_by('si.sgst')
						->get()
						->result();
		return $data;
	}
	public function currentMonthSales(){
		return  $this->db->select('sum(total) as total')
						   ->where('MONTH(date)',date('m'))
						   ->where('YEAR(date)',date('Y'))
						   ->get('sales')
						   ->row()
						   ->total;	
	}
	public function profit(){
		return $this->db->select('sum((si.price-pr.cost)*si.quantity) as month_profit')
						   ->from('sales s')
						   ->join('sales_items si','si.sales_id = s.sales_id')
						   ->join('products pr','pr.product_id = si.product_id')
						   ->where('MONTH(s.date)',date('m'))
						   ->where('YEAR(s.date)',date('Y'))
						   ->get()
						   ->row()
						   ->month_profit;
	}
	public function dayProfit(){
		return  $this->db->select('s.date,sum((si.price-pr.cost)*si.quantity) as profit')
						   ->from('sales s')
						   ->join('sales_items si','si.sales_id = s.sales_id')
						   ->join('products pr','pr.product_id = si.product_id')
						   ->group_by('s.date')
						   ->get()
						   ->result();
	}
	public function daySales(){
		$result = $this->db->query('SELECT sum(s.total) AS total, date FROM sales s GROUP BY s.date');
		return  $result->result();
	}
	public function daySalesReturn(){
		$result = $this->db->query('SELECT sum(s.total) AS total, date FROM sales_return s GROUP BY s.date');
		return  $result->result();
	}
	public function receivable(){
		return $this->db->select('i.*,c.first_name as customer_name,c.id as customer_id,s.sales_id')
						->from('invoice i')
						->join('sales s','s.sales_id = i.sales_id')
						->join('users c','c.id = s.customer_id')
						->where('s.delete_status',0)
					    ->get()
					    ->result();
	}
	public function getReceivableAmountDetails($start_date,$end_date){
		return $this->db->query('
											SELECT 
												i.*,
												c.first_name as customer_name,
												c.id as customer_id,
												s.sales_id
											FROM invoice i
											INNER JOIN sales s ON s.sales_id = i.sales_id
											LEFT JOIN users c ON c.id = s.customer_id
											
											WHERE 
												(i.invoice_date >= ? OR ? IN ("",NULL))
											AND
												(i.invoice_date <= ? OR ? IN ("",NULL))
											AND
												s.delete_status = 0
									   ',
									    array(
									    	$start_date,
									    	$start_date,
									    	$end_date,
									    	$end_date
										)
									   )->result();
	}
	public function payable(){
		return $this->db->select('ar.*,s.first_name as supplier_name,p.purchase_id')
						->from('account_receipts ar')
						->join('purchases p','p.purchase_id=ar.purchase_id')
						->join('users s','s.id=p.supplier_id')
						->where('p.delete_status',0)
						->get()
						->result();
	}
	public function getPayableAmountDetails($start_date,$end_date){
		return $this->db->query('
											SELECT 
												ar.*,
												s.first_name as supplier_name,
												p.purchase_id
											FROM account_receipts ar
											INNER JOIN purchases p ON p.purchase_id=ar.purchase_id
											LEFT JOIN users s ON s.id=p.supplier_id
											
											WHERE 
												(ar.receipt_voucher_date >= ? OR ? IN ("",NULL))
											AND
												(ar.receipt_voucher_date <= ? OR ? IN ("",NULL))
											AND
												p.delete_status = 0
									   ',
									    array(
									    	$start_date,
									    	$start_date,
									    	$end_date,
									    	$end_date
										)
									   )->result();
	}
	public function getWarehouseReport($warehouse_id){
		if($warehouse_id == 0){
			return  $this->db->select('*')
					 ->from('products p')
					 ->join('warehouses_products wp','p.product_id = wp.product_id')
					 ->join('category c','c.category_id = p.category_id')					 
					 ->where('p.delete_status',0)
				     ->get()
				     ->result();
		}else{
			return  $this->db->select('*')
					 ->from('products p')
					 ->join('warehouses_products wp','p.product_id = wp.product_id')
					 ->join('category c','c.category_id = p.category_id')
					 ->where('wp.warehouse_id',$warehouse_id)
					 ->where('p.delete_status',0)
				     ->get()
				     ->result();
		}
		
		return $this->db->get()->result();
	}

	/* 
		return  warehouse wise product 
	*/
	public function getWarehouseWiseProducts($warehouse_id){
		if($warehouse_id == 0){
			return  $this->db->select('p.*,p.quantity as warehouse_quantity,c.category_name')
					 ->from('products p')
					 ->join('category c','c.category_id = p.category_id')
					 ->where('p.delete_status',0)
				     ->get()
				     ->result();	
		}else{
			return  $this->db->select('p.*, wp.quantity as warehouse_quantity,c.category_name')
					 ->from('products p')
					 ->join('warehouses_products wp','p.product_id = wp.product_id')
					 ->join('category c','c.category_id = p.category_id')
					 ->where('wp.warehouse_id',$warehouse_id)
					 ->where('p.delete_status',0)
				     ->get()
				     ->result();	
		}
		
	}
}
?>
