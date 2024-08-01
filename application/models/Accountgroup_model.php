<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Account_group_model extends CI_Model{
    
    function __construct()
	{
		parent::__construct();		
	}

    function get_records()
    {
            return $this->db->get("account_group")->result();
    }

    function get_single_record($id)
    {
        return $this->db->get_where("account_group",array('id'=>$id))->row();
    }   

    function add_record($data)
    {
        if($this->db->insert('account_group',$data)){
            return  $this->db->insert_id();
        }else{
            return FALSE;
        }
    }

    function edit_record($data,$id)
    {    
        $this->db->where('id',$id);     
        if($this->db->update('account_group',$data)){
            return true;
        }else{
            return false;
        }
    }
    
    function delete_record($id)
    {   
        $ledger         = $this->db->get_where('ledger',array('accountgroup_id'=>$id))->result();
        $no_ledger      = sizeof($ledger);

        if($no_ledger > 0)
        {    
            return $no_ledger;
        }
        else
        {
            $this->db->where('id', $id);
            if($this->db->delete('account_group')){
                return true;
            }else{
                return false;
            }
        }
    }

}
?>