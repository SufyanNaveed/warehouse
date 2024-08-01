<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Expense_category_model extends CI_Model
{


	public function get_records()
	{
       	return $this->db->get_where('expense_category',array('delete_status'=>0))->result();
    }

    function get_single_record($id)
    {
        return $this->db->get_where("expense_category",array('id'=>$id))->row();
    }   

    function add_record($data)
    {
        if($this->db->insert('expense_category',$data))
        {
            return  $this->db->insert_id();
        }else
        {
            return FALSE;
        }
    }

    function edit_record($data,$id)
    {    
        $this->db->where('id',$id);     
        if($this->db->update('expense_category',$data))
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
        
        $ledger_id = $this->db->get_where('expense_category',array('id'=>$id))->row()->ledger_id;
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
            if($this->db->delete('expense_category'))
            {
            	$this->db->where('id',$ledger_id);
            	$this->db->delete('ledger');	
                return true;
            }
            else
            {
                return false;
            }
        // }
    }


	/* 
		return all category data 
	*/
	public function getExpenseCategory(){
		$data = $this->db->get_where('expense_category',array('delete_status'=>0));
		return $data->result();
	}
	/* 
		insert new categoty record in Database 
	*/
	public function addModel($data){
		if($this->db->insert('expense_category',$data)){
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
		if($query = $this->db->get_where('expense_category',array('id'=>$id))){
			return $query->row();
		}
		else{
			return FALSE;
		}
	}
	/* 
		this function is used to save edited record in database 
	*/
	public function editModel($data,$id){
		$this->db->where('id',$id);
		return $this->db->update('expense_category',$data);
	}
	/* 
		this function delete category from database  
	*/
	public function deleteModel($id){	
		$this->db->where('id',$id);
		return $this->db->update('expense_category',array('delete_status'=>1));
	}
}
?>