<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bank_account_model extends CI_Model
{
    /*
        Get All Bank account Data
    */


    function get_records()
    {
        return $this->db->select('ba.*')
                         ->from('bank_account ba')                         
                         ->get()
                         ->result();
            
    }

    function get_single_record($id)
    {
        return $this->db->get_where("bank_account",array('id'=>$id))->row();
    }   

    function add_record($data)
    {
        if($this->db->insert('bank_account',$data))
        {
            return  $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }
    }

    function edit_record($data,$id)
    {    
        $this->db->where('id',$id);     
        if($this->db->update('bank_account',$data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    function delete_record($id)
    {   
        // $ledger = $this->db->get_where('ledger',array('accountgroup_id'=>$id))->result();
        // $no_ledger    = sizeof($ledger);

        // echo $no_users;
        // exit;

        // if($no_ledger > 0)
        // {    
        //     return $no_ledger;
        // }
        // else
        // {
            $this->db->where('id', $id);
            if($this->db->delete('bank_account'))
            {
                return true;
            }
            else
            {
                return false;
            }
        // }
    }
    public function getBankAccount()
    {
    	return $this->db->get_where('bank_account',array('delete_status'=>0))->result();
    }


    /*
        Add Bank Account Data 
    */
    public function addModel($data)
    {
    	if($this->db->insert('bank_account',$data)){
        return $this->db->insert_id();
      }
      return false;
    }
   
   /*
        Delete Bank Account Data 
    */
	 public function delete($id)
	 {
      $this->db->where('id',$id);
      return $this->db->update('bank_account',array('delete_status'=>1));
	 }

   /*
        Update Bank Account Data 
    */
	 public function editBankAccount($id,$data)
	 {
      $this->db->where('id',$id);
  		return $this->db->update('bank_account',$data);
	 }

   /*
     Get Bank Account Data for Update 
  */
	public function getRecord($id)
	{
		return $this->db->get_where('bank_account',array('id'=>$id))->row();
	}
}
