<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Receipt_model extends CI_Model
{
	function __construct() {
		parent::__construct();
		
	}
	/* 
		return all Receipt data 
	*/
	public function getReceipt(){
		if($this->session->userdata('type')=='admin'){
			return $this->db->select('th.*,i.invoice_no,i.sales_id,i.purchase_return_id,l.title as from_account,ll.title as to_account')
						 ->from('transaction_header th')
						 ->join('invoice i','i.id=th.invoice_id','left')
						 ->join('purchase_return pr','pr.id = i.purchase_return_id','left')	
						 ->join('ledger l','l.id=th.from_account')
						 ->join('ledger ll','ll.id=th.to_account')
						 ->where('th.type','R')
						 ->get()
						 ->result();
		}
		else{
			return $this->db->select('th.*,i.invoice_no,i.sales_id,i.purchase_return_id,l.title as from_account,ll.title as to_account')
						 ->from('transaction_header th')
						 ->join('invoice i','i.id=th.invoice_id','left')
						 ->join('sales s','s.sales_id = i.sales_id','left')
						 ->join('purchase_return pr','pr.id = i.purchase_return_id','left')	
						 ->join('users b','b.id = s.biller_id','left')
						 ->join('users c','c.id = pr.user','left')
						 ->join('ledger l','l.id=th.from_account')
						 ->join('ledger ll','ll.id=th.to_account')
						 ->where('th.type','R')
						 ->where('b.id',$this->session->userdata('user_id'))
						 ->or_where('c.id',$this->session->userdata('user_id'))
						 ->get()
						 ->result();
		}
	}
	/*
		return ledger data
	*/
	public function getLedger(){
		return $this->db->get('ledger')->result();
	}
	/*
		return invoice data
	*/
	public function getInvoice(){
		return $this->db->query('
									SELECT 
										i.*,
										CONCAT(c.first_name," ",c.last_name) AS customer_name 
									FROM invoice i 
									INNER JOIN sales s ON s.sales_id = i.sales_id 
									INNER JOIN users c ON c.id = s.customer_id 
										WHERE paid_amount < sales_amount
								')->result();
	}
	/*
		return ledger accounts
	*/
	public function getAccount($id){
		return $this->db->select('l.*,ag.group_title')
			      	    ->from('ledger l')
			      	    ->join('account_group ag','ag.id=l.accountgroup_id')
			      	    ->join('branch b','b.branch_id=ag.branch_id')
			      	    ->where('b.branch_id',$id)
			      	    ->get()
			      	    ->result();
	}
	/*
		return invoice total
	*/
	public function getAmount($id){
		return $this->db->select('i.*,CONCAT(c.first_name," ",c.last_name) AS customer_name,(i.sales_amount-i.paid_amount-s.flat_discount+s.shipping_charge) as amount')
						->from('invoice i')
						->join('sales s','s.sales_id=i.sales_id')
						->join('users c','c.id=s.customer_id')
						->where('i.id',$id)
						->get()
						->row();
	}
	/*

	*/
	public function getAccountGroupID($id){
		return $this->db->get_where('ledger',array('id'=>$id))->row()->accountgroup_id;
	}
	/* 
		return max id from category table 
	*/
	public function getMaxId(){
		$id =  $this->db->select_max('category_id')->get('category')->row()->category_id;
		if($id==null){
            $category_code = 'CAT-'.sprintf('%06d',intval(1));
        }
        else{
            $category_code = 'CAT-'.sprintf('%06d',intval($id)+1); 
        }
        return $category_code;
	}
	/* 
		insert new categoty record in Database 
	*/
	public function addModel($data){
		/*$sql = "insert into category (category_code,category_name) values(?,?)";
		if($this->db->query($sql,$data)){*/
		if($this->db->insert('category',$data)){
			return  $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	/* 
		return selected id record use in edit page 
	*/
	public function getRecord($id){
		$sql = "select * from category where category_id = ?";
		if($query = $this->db->query($sql,array($id))){
			return $query->result();
		}
		else{
			return FALSE;
		}
	}
	/* 
		this function is used to save edited record in database 
	*/
	public function editModel($data,$id){
		$sql = "update category set category_name = ? where category_id = ?";
		if($this->db->query($sql,array($data,$id))){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/* 
		this function delete category from database  
	*/
	public function deleteModel($id){	
		/*$sql = "delete from category where category_id = ?";
		if($this->db->query($sql,array($id))){*/
		$this->db->where('category_id',$id);
		if($this->db->update('category',array('delete_status'=>1))){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*	
		generate payment reference no
	*/
	public function generateReferenceNo(){
		$query = $this->db->query("SELECT * FROM transaction_header ORDER BY id DESC LIMIT 1");
		$result = $query->result();
		return $result;
	}
	/*
		return details for payment
	*/
	public function getDetailsPayment($id){
		return  $this->db->select('s.*,
								   i.invoice_no,
								   i.invoice_date,
								   i.paid_amount,
								   CONCAT(c.first_name," ",c.last_name) AS customer_name,
								   c.address as customer_address,
								   c.mobile as customer_mobile,
								   c.email as customer_email,
								   c.gstid as customer_gstid,
								   ct.name as customer_city,
								   cco.name as customer_country,
								   b.biller_name,
								   b.address as biller_address,
								   cb.name as biller_city,
								   co.name as biller_country,
								   b.mobile as biller_mobile,
								   b.email as biller_email,
								   b.gstid as biller_gstid,
								   w.warehouse_name,
								   br.address as branch_address,
								   br.city as branch_city,
								   u.first_name,
								   u.last_name')
						 ->from('sales s')
						 ->join('invoice i','i.sales_id = s.sales_id')
						 ->join('users c','s.customer_id = c.id')
						 ->join('cities ct','c.city_id = ct.id')
						 ->join('countries cco','c.country_id = cco.id')
						 ->join('biller b','s.biller_id = b.biller_id')
						 ->join('cities cb','b.city_id = cb.id')
						 ->join('countries co','b.country_id = co.id')
						 ->join('warehouse w','s.warehouse_id = w.warehouse_id')
						 ->join('branch br','w.branch_id = br.branch_id')
						 ->join('users u','s.user = u.id')
						 ->where('s.sales_id',$id)
						 ->get()
						 ->result();
	}

	
	/*
		add payment details
	*/
	public function addReceipt($data,$invoice_id=null)
	{
		//$this->db->insert('transaction_header',$data);
		// echo $this->db->last_query();
		// print_r($data);
		// exit;

		if($this->db->insert('transaction_header',$data)){

			$id = $this->db->insert_id();

			// ***** Update invoice paid amount *****//
			if($data['invoice_id']!=null || $data['receipt_id']!=null || $invoice_id!=null){
				if($data['type']=="R"){
					$paid_amount =  $this->db->get_where('invoice',array('id'=>$data['invoice_id']))->row()->paid_amount;
					$this->db->where('id',$data['invoice_id']);
					$this->db->update('invoice',array("paid_amount"=>$data['amount']+$paid_amount));
				}
				else if($data['type']=="P"){
					$paid_amount =  $this->db->get_where('account_receipts',array('receipt_voucher_no'=>$data['receipt_id']))->row()->paid_amount;
					$this->db->where('receipt_voucher_no',$data['receipt_id']);
					$this->db->update('account_receipts',array("paid_amount"=>$data['amount']+$paid_amount));

					// echo $this->db->last_query();
					// exit;

				}
				else if($data['type']=="C"){
					$this->db->where('id',$invoice_id);
					$this->db->update('invoice',array("paid_amount"=>0.00));
				}
			}

			//***** Ledger *****//
			$balance = $this->db->get_where('ledger',array('id'=>$data['from_account']))->row()->closing_balance;
			$this->db->where('id',$data['from_account']);
			$this->db->update('ledger',array('closing_balance'=>$balance - $data['amount']));
			$balance = $this->db->get_where('ledger',array('id'=>$data['to_account']))->row()->closing_balance;
			$this->db->where('id',$data['to_account']);
			$this->db->update('ledger',array('closing_balance'=>$balance + $data['amount']));

			//***** Add CR nd DR in Transaction Details table *****//
			$dr = array(
				'transaction_id' => $id,
				'voucher_type' => 'C',
				'ledger_no' => $data['to_account'],
				'dr_amount' => $data['amount']
			);
			$cr = array(
				'transaction_id' => $id,
				'voucher_type' => 'D',
				'ledger_no' => $data['from_account'],
				'cr_amount' => $data['amount']
			);
			$this->db->insert('transaction_detail',$dr);
			$this->db->insert('transaction_detail',$cr);
			return $id;
		}else{
			return FALSE;
		}
	}

	public function getFromAccount(){
		return $this->db->select('l.*,ag.group_title')
			      	    ->from('ledger l')
			      	    ->join('account_group ag','ag.id=l.accountgroup_id')
			      	    // ->join('account_group_branch agb','agb.account_group_id = ag.id')
			      	    // ->join('branch b','b.branch_id=agb.branch_id')			      	 
			      	    ->where_in('ag.category',array('Income','Assets'))
			      	    // ->where('b.branch_id',$branch_id)
			      	    ->get()
			      	    ->result();
	}

	public function getToAccount(){
		return $this->db->select('l.*,ag.group_title')
			      	    ->from('ledger l')
			      	    ->join('account_group ag','ag.id=l.accountgroup_id')
			      	    // ->join('account_group_branch agb','agb.account_group_id = ag.id')
			      	    // ->join('branch b','b.branch_id=agb.branch_id')			      	 
			      	    ->where_in('ag.category',array('Expense','Liabilities'))
			      	    // ->where('b.branch_id',$branch_id)
			      	    ->get()
			      	    ->result();
	}
}
?>