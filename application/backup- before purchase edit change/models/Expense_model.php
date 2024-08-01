<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Expense_model extends CI_Model
{
  
    function __construct()
    {
        parent::__construct();
        $this->load->model('ledger_model');
    }

    /*

    this function used for add new expense record

    */
    public function add_record($data)
    {  
        if($this->db->insert("expense",$data))
        {
           return $this->db->insert_id(); 
        }
        else
        {
            return FALSE;
        }    
    }

    public function edit_record($data,$id)
    {
        $this->db->where("id",$id);
        if($this->db->update('expense',$data))            
        {
            return TRUE;
        }
        else
        {
            return FALSE;    
        }
        
    }

    function get_single_record($id)
    {
        return $this->db->get_where('expense',array("id"=>$id))->row();
    }
     /*
       this function used for display expense data
    */
    public function expensesData()
    {
       $this->db->where('delete_status','0');   
       $query=$this->db->get('expense');
        return $query->result();    
    }

     /*
       this function used for display expense data where delete_status is 0.
    */
    public  function expUserData()
    {               
        /*$query = $this->db->get('expense');
        $result = $this->db->query('SELECT e.id,a.account_name, a.account_no,
                  e.description,  e.amount, e.date FROM expense AS e
                  INNER JOIN bank_account as a ON a.id = e.account_id 
                  WHERE e.delete_status=0');
             return $result->result(); */
        if($this->session->userdata('type') == "admin")
        {   
        
             return $this->db->select('e.*')
                             ->from('expense e')
                             ->where('e.delete_status',0)
                             ->get()
                             ->result();
                // return  $this->db->get()->result();
        } 
        else
        {
            $this->db->select('e.*')
                 ->from('expense e')
                 ->where('e.delete_status',0)
                 ->where('e.user_id',$this->session->userdata('user_id'));
            return  $this->db->get()->result();
        }
        
    } 

     /*

       this function used for get account data 

    */

    // public function get_ledger_by_branch($branch_id)
    // {
    //     return $this->db->select('l.*,ag.category as category')
    //                     ->from('ledger l')  
    //                     ->join('account_group ag','l.accountgroup_id = ag.id')
    //                     ->join('account_group_branch agb','agb.account_group_id = l.accountgroup_id')
    //                     ->join('branch b','b.branch_id = agb.branch_id')
    //                     ->where('b.branch_id',$branch_id)
    //                     ->get()
    //                     ->result();
    // }

    public function get_income_assets_ledger()
    {
        return $this->db->select('l.*')
                        ->from('ledger l')  
                        ->join('account_group ag','l.accountgroup_id = ag.id')
                        ->where('ag.category','Income')
                        ->or_where('ag.category','Assets')
                        ->get()
                        ->result();
    }

    public function get_expense_liabilities_ledger()
    {
        return $this->db->select('l.*')
                        ->from('ledger l')  
                        ->join('account_group ag','l.accountgroup_id = ag.id')
                        ->where('ag.category','Expense')
                        ->or_where('ag.category','Liabilities')
                        ->get()
                        ->result();
    }
    public function getAccount()
    {
        return $this->db->select('l.*')
                        ->from('ledger l')  
                        ->join('account_group ag','l.accountgroup_id = ag.id')
                        ->where('ag.category','Income')
                        ->or_where('ag.category','Assets')
                        ->get()
                        ->result();
    }

     /*

       this function used for get payment data 

    */
    public function getPayment()
    {
         $query=$this->db->get_where('payment_method',array('delete_status'=>0));
        return $query->result();    
    }

     /*

       this function used for get category data 

    */
    public function getCategory()
    {
        // $this->db->where('delete_status',0);
        // $query=$this->db->get('expense_category');
        // return $query->result();  
        return $this->db->select('l.*')
                        ->from('ledger l')  
                        ->where('ag.category','Expense')
                        ->or_where('ag.category','Liabilities')
                        ->get()
                        ->result();

        // echo $this->db->last_query();
        // exit;
    }

     /*

       this function used for get last insert id  

    */
    public function getLastID()
    {
       $sql1="SELECT id FROM  `expense` ORDER BY `id` DESC LIMIT 1";
       $query=$this->db->query($sql1);
       if( $query->num_rows() > 0 )
       {
           return $query->row()->id;      
       } 
       return FALSE;
    }  

     /*
       this function used for fetch data 
    */
    public  function getContents() 
    {
        $this->db->select('*');
        $this->db->from('expense');
        $query = $this->db->get();
        return $result = $query->result();
        $this->load->view('edit_content', $result);
    }

    /*
     this function used for fetch data at update time
    */
    public  function getData($id) 
    {
        $this->db->select('*');
        $this->db->from('expense');
        $this->db->where('id',$id);
        $query = $this->db->get();
        return $query->row();
            
    }

    /*
       this function used for delete expense data 
    */
    public function delete($id)
    {
        $expense = $this->db->get_where('expense',array("id"=>$id))->row();
        $transaction_header = $this->db->get_where("transaction_header",array("expense_id"=>$id))->row();
    
        // get the current ledger detail
        $from_ledger    = $this->ledger_model->get_single_record($expense->account_id);
        $to_ledger      = $this->ledger_model->get_single_record($expense->category_id); 

        // update closing balance
        $from_ledger->closing_balance   = floatval($from_ledger->closing_balance + $expense->amount);
        $to_ledger->closing_balance     = floatval($to_ledger->closing_balance - $expense->amount);

        // update ledger details
        $this->ledger_model->edit_record($from_ledger,$from_ledger->id);
        $this->ledger_model->edit_record($to_ledger,$to_ledger->id);

        $transaction_header = $this->db->get_where("transaction_header",array("expense_id"=>$expense->id))->row();

        $this->db->where("expense_id",$expense->id);
        $this->db->delete("transaction_header");

        $this->db->where("transaction_id",$transaction_header->id);
        $this->db->delete("transaction_detail");  
        
        $this->db->where("id",$id);
        $this->db->delete("expense"); 

        return true;

    }

    /*

    this function used for update expense details

    */
    

}
