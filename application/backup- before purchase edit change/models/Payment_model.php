<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Payment_model extends CI_Model
{
	public function getPayment(){
		
		// if($this->session->userdata('type')=='admin')
		// {
			return $this->db->select('th.*,ar.invoice_no,ar.purchase_id,ar.sales_return_id,l.title as from_account,ll.title as to_account')
							 ->from('transaction_header th')
							 ->join('account_receipts ar','ar.receipt_voucher_no = th.receipt_id','left')
							 ->join('ledger l','l.id=th.from_account')
							 ->join('ledger ll','ll.id=th.to_account')
							 ->where('th.type','P')
							 ->get()
							 ->result();
		// }
		// else
		// {
		// 	return $this->db->select('th.*,ar.invoice_no,ar.purchase_id,ar.sales_return_id,l.title as from_account,ll.title as to_account')
		// 					 ->from('transaction_header th')
		// 					 ->join('account_receipts ar','ar.receipt_voucher_no = th.receipt_id','left')
		// 					 ->join('ledger l','l.id=th.from_account')
		// 					 ->join('ledger ll','ll.id=th.to_account')
		// 					 ->where('th.type','P')
		// 					 ->where('b.id',$this->session->userdata('user_id'))
		// 					 ->get()
		// 					 ->result();
		// }
	} 
	/*

	*/
	public function getReceipt(){
		return $this->db->get('account_receipts')->result();
	}
	/*
		return invoice total
	*/
	public function getAmount($id){
		return $this->db->select('ar.*,su.supplier_name,(ar.receipt_amount-ar.paid_amount) as amount')
						->from('account_receipts ar')
						->join('purchases p','p.purchase_id = ar.purchase_id')
						->join('suppliers su','su.supplier_id = p.supplier_id')
						->where('ar.receipt_voucher_no',$id)
						->get()
						->row();
	}

	public function getFromAccount($branch_id){
		return $this->db->select('l.*,ag.group_title')
			      	    ->from('ledger l')
			      	    ->join('account_group ag','ag.id=l.accountgroup_id')
			      	    ->join('account_group_branch agb','agb.account_group_id = ag.id')
			      	    ->join('branch b','b.branch_id=agb.branch_id')			      	 
			      	    ->where_in('ag.category',array('Income','Assets'))
			      	    ->where('b.branch_id',$branch_id)
			      	    ->get()
			      	    ->result();
	}

	public function getToAccount($branch_id){
		return $this->db->select('l.*,ag.group_title')
			      	    ->from('ledger l')
			      	    ->join('account_group ag','ag.id=l.accountgroup_id')
			      	    ->join('account_group_branch agb','agb.account_group_id = ag.id')
			      	    ->join('branch b','b.branch_id=agb.branch_id')			      	 
			      	    ->where_in('ag.category',array('Expense','Liabilities'))
			      	    ->where('b.branch_id',$branch_id)
			      	    ->get()
			      	    ->result();
	}

	public function getAccountGroupID($id){
		return $this->db->get_where('ledger',array('id'=>$id))->row()->accountgroup_id;
	}
}