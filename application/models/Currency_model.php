<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Currency_model extends CI_Model
{
	function __construct() 
	{
		parent::__construct();
		$CI =& get_instance();
        $CI->load->model('ion_auth_model');
	}	
   	

   	function get_records()
   	{
			return $this->db->get("currency")->result();
   	}

   	function get_single_record($id)
   	{
   		return $this->db->get_where("currency",array('id'=>$id))->row();
   	}	

   	function add_record($data)
   	{
		if($this->db->insert('currency',$data)){
			return  $this->db->insert_id();
		}else{
			return FALSE;
		}
   	}

	function edit_record($data,$id)
	{    
		$this->db->where('id',$id);		
		if($this->db->update('currency',$data)){
			return true;
		}else{
			return false;
		}
   	}
   	
   	function delete_record($id)
   	{   
		$this->db->where('id', $id);
		if($this->db->delete('currency'))
		{
			return true;
		}
		else
		{
			return false;
		}
   	}


}
?>