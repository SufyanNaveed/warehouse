<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User_model extends CI_Model
{
   	function __construct() 
   	{
   		parent::__construct();
   		$CI =& get_instance();
         $CI->load->model('ion_auth_model');
   	}	

   	/******* User Function ***********/

   	function get_user_records()
   	{
			return $this->db->select('u.*,g.id as group_id, g.name as group_name')	
						->from('users u')
						->join('users_groups us','us.user_id = u.id')
						->join('groups g','g.id = us.group_id')
						->get()
						->result();	
   	}

   	function get_user_records_by_role($role_name)
   	{
			return $this->db->select('u.*,g.id as group_id,st.name as state_name,c.name as cname,ct.name as ctname,CONCAT(u.first_name," ",u.last_name) as user_name')	
						->from('users u')
						->join('users_groups us','us.user_id = u.id')
						->join('groups g','g.id = us.group_id')
						->join('countries c','c.id = u.country_id')
            ->join('states st','st.id = u.state_id')
           	->join('cities ct','ct.id = u.city_id')
						->where('g.name',$role_name)
						->get()
						->result();	
   	}

   	function get_single_user_record($id)
   	{
   			return $this->db->select('u.*,g.id as group_id, g.name as group_name, g.account_group_id as account_group_id,cu.name as country_name,st.name as state_name,ct.name as city_name,u.state_id as shipping_state_id')	
						->from('users u')
						->join('users_groups us','us.user_id = u.id')
						->join('groups g','g.id = us.group_id')
						->join('countries cu','cu.id = u.country_id','left')
		            ->join('states st','st.id = u.state_id','left')
		            ->join('cities ct','ct.id = u.city_id','left')
						->where('u.id',$id)
						->get()
						->row();							
   	}	

      function is_user($role_name)
      {
            $data = $this->db->select('u.*,g.id as group_id, g.name as group_name, g.account_group_id as account_group_id,cu.name as country_name,st.name as state_name,ct.name as city_name,u.state_id as shipping_state_id') 
                  ->from('users u')
                  ->join('users_groups us','us.user_id = u.id')
                  ->join('groups g','g.id = us.group_id')
                  ->where('g.name',$role_name)
                  ->get()
                  ->row();  
                                   
            if($data != null)
            {
               return true;
            }
            else
            {
               return false;
            }
      } 

      function is_user_exist_in_transaction($user_id)
      {
         if(($this->is_user_exist_in_sales($user_id) == null) && ($this->is_user_exist_in_sales_return($user_id) == null) && ($this->is_user_exist_in_purchase($user_id) == null) && ($this->is_user_exist_in_purchase_return($user_id) == null) && ($this->is_user_exist_in_quotation($user_id) == null))
         {
            return false;
         }
         else
         {
            return true;
         }
      } 

      function is_user_exist_in_sales($user_id)
      {
         return $this->db->select('s.*')
                         ->from('sales s')
                         ->where('s.biller_id',$user_id)
                         ->or_where('s.customer_id',$user_id)
                         ->or_where('s.user',$user_id)
                         ->get()
                         ->result();  
      } 

      function is_user_exist_in_sales_return($user_id)
      {
         return $this->db->select('s.*')
                         ->from('sales_return s')
                         ->where('s.biller_id',$user_id)
                         ->or_where('s.customer_id',$user_id)
                         ->or_where('s.user',$user_id)
                         ->get()
                         ->result();  
      } 

      function is_user_exist_in_purchase($user_id)
      {
         return $this->db->select('s.*')
                         ->from('purchases s')
                         ->where('s.biller_id',$user_id)
                         ->or_where('s.supplier_id',$user_id)
                         ->or_where('s.user',$user_id)
                         ->get()
                         ->result();  
      } 

      function is_user_exist_in_purchase_return($user_id)
      {
         return $this->db->select('s.*')
                         ->from('purchase_return s')                         
                         ->where('s.supplier_id',$user_id)
                         ->or_where('s.user',$user_id)
                         ->get()
                         ->result();  
      } 

      function is_user_exist_in_quotation($user_id)
      {
         return $this->db->select('s.*')
                         ->from('quotation s')                         
                         ->where('s.biller_id',$user_id)
                         ->or_where('s.customer_id',$user_id)
                         ->or_where('s.user',$user_id)
                         ->get()
                         ->result();  
      } 

      


   	function edit_user_record($data,$id)
   	{    
   		$this->db->where('id',$id);		
   		if($this->db->update('users',$data)){
   			return true;
   		}else{
   			return false;
   		}
   	}
      
      function delete_user_record($user_id)
      {   
         $this->db->where('id', $user_id);
         if($this->db->delete('users'))
         {
            return TRUE;
         }
         else
         {
            return FALSE;
         }
      }

   	
   	function assigned_warehouse($user_id)
   	{
   		return $this->db->select('w.*')	
						->from('warehouse_management wm')
						->join('warehouse w','wm.warehouse_id = w.warehouse_id')
						->where('wm.user_id',$user_id)
						->get()
						->row();	
		
   	}

   	function assign_warehouse($user_id, $warehouse_id)
   	{
   		$old_warehouse = $this->db->get_where('warehouse_management',array('user_id'=>$user_id))->result();

   		if($old_warehouse == NULL)
   		{
   			$this->add_user_warehouse($user_id,$warehouse_id);
   		}
   		else
   		{
   			$this->remove_user_warehouse($user_id,$warehouse_id);
   			$this->add_user_warehouse($user_id,$warehouse_id);
   		}
   	}

   	function add_user_warehouse($user_id,$warehouse_id)
   	{
   		$data = array(	
   					"user_id"		=>	$user_id,
   					"warehouse_id"	=>	$warehouse_id
   				);

   		if($this->db->insert('warehouse_management',$data))
   		{
   			return TRUE;
   		}
   		else
   		{
   			return FALSE;
   		}

   	}

   	function remove_user_warehouse($user_id,$warehouse_id)
   	{
   		$this->db->where('user_id',$user_id);
   		if($this->db->delete('warehouse_management'))
   		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
   	}

   	



   	/******* Role Function ***********/

   	function get_role_records()
   	{
		return $this->db->select("g.*,ag.group_title as group_title")
						->from('groups g')
						->join('account_group ag','ag.id = g.account_group_id')
                  ->get()
						->result();
			// return $this->db->get("groups")->result();
   	}

   	function get_single_role_record($id)
   	{
   		return $this->db->select('g.*,ag.group_title as group_title')
   					->from('groups g')
   					->join('account_group ag','ag.id = g.account_group_id')
            ->where('g.id',$id)
   					->get()
   					->row();
   					
   		//return $this->db->get_where("groups",array('id'=>$id))->row();
   	}

   	function get_single_role_record_by_name($role_name)
   	{
   		return $this->db->select('g.*,ag.group_title as group_title')
	   					->from('groups g')
	   					->join('account_group ag','ag.id = g.account_group_id')
              ->where('g.name',$role_name)
	   					->get()
	   					->row();
   					
   		//return $this->db->get_where("groups",array('id'=>$id))->row();
   	}	

   	function add_role_record($data)
   	{
		if($this->db->insert('groups',$data))
		{
			return  $this->db->insert_id();
		}
		else
		{
			return FALSE;
		}
   	}

	function edit_role_record($data,$id)
	{    
		$this->db->where('id',$id);		
		if($this->db->update('groups',$data)){
			return true;
		}else{
			return false;
		}
   	}
   	
   	function delete_role_record($id)
   	{   
	    $user_groups = $this->db->get_where('users_groups',array('group_id'=>$id))->result();
	    $no_users 	 = sizeof($user_groups);

	    // echo $no_users;
	    // exit;

		if($no_users > 0)
		{    
			return $no_users;
		}
		else
		{
			$this->db->where('id', $id);
			if($this->db->delete('groups')){
				return true;
			}else{
				return false;
			}
		}
   	}

   	/******* Permission Function ***********/

   	function get_permission_records()
   	{
		return $this->db->get("permissions")->result();
   	}

   	function get_permission_records_by_role($role_id)
   	{
   		return $this->db->select('p.*')	
						->from('permissions p')
						->join('permission_role pr','pr.permission_id = p.id')
						->where('pr.role_id',$role_id)
						->get()
						->result();	
   	}

   	function get_permission_records_by_user($user_id)
   	{
   		return $this->db->select('p.*')	
						->from('permissions p')
						->join('permission_role pr','pr.permission_id = p.id')
						->join('groups g','g.id = pr.role_id')
						->join('users_groups ug','ug.group_id = g.id')
						->where('ug.user_id',$user_id)
						->get()
						->result();	
   	}

   	function has_permission($permission_name)
   	{
   		$permission = $this->session->userdata('permission');
   		
   		if(!in_array($permission_name, $permission))
   		{
   			return false;
   		}
   		else
   		{
   			return true;
   		}

   	}

   	function has_module_permission($permission_name)
   	{
   		$permission_m = $this->session->userdata('permission_m');
   		
   		if(!in_array($permission_name, $permission_m))
   		{
   			return false;
   		}
   		else
   		{
   			return true;
   		}

   	}

   	function get_permission_records_by_module($module)
   	{
   		return $this->db->select('p.*')	
						->from('permissions p')
						->where('p.module',$module)
						->get()
						->result();	
   	}

   	function add_permission_role_record($permission_id,$role_id)
   	{
   		if($this->db->insert('permission_role',array('permission_id'=>$permission_id,'role_id'=>$role_id)))
   		{
			return $this->db->insert_id();
		}
		else
		{
			return FALSE;
		}
   	}

   	function edit_permission_record()
   	{
		return $this->db->get("permissions")->result();
   	}

   	function delete_permission_record($permission_id,$role_id)
   	{
   		$this->db->where('permission_id',$permission_id);		
   		$this->db->where('role_id',$role_id);		
   		if($this->db->delete('permission_role'))
   		{
   			return TRUE;	
   		}
   		else
   		{
   			return FALSE;
   		}
		
   	}
}
?>