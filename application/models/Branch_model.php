<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Branch_model extends CI_Model
{
	function __construct() {
		parent::__construct();
		
	}
	public function index(){
		
	} 
	
	

	public function get_records(){
		return $this->db->select('b.*,ct.name as city')
						->from('branch b')
						->join('cities ct','ct.id = b.city_id')
						->where('b.delete_status',0)
						->get()
						->result();
	}

	public function get_single_record($id)
	{
		$sql = "select * from branch where branch_id = ?";
		if($query = $this->db->query($sql,array($id)))
		{
			return $query->row();
		}
		else
		{
			return FALSE;
		}
	}


	public function add_record($data)
	{
		if($this->db->insert('branch',$data))
		{
			return  $this->db->insert_id();
		}
		else
		{
			return FALSE;
		}
	}

	public function edit_record($data,$id)
	{
		$this->db->where('branch_id',$id);
		if($this->db->update('branch',$data))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function delete_record($id)
	{	
		$this->db->where('branch_id',$id);
		if($this->db->update('branch',array('delete_status'=>1)))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	
	/* return branch count */
	public function get_record_count()
	{
		$this->db->select('*');
		$query = $this->db->get_where('branch',array('delete_status'=>0));
		
		return $query->result();		
	}

	/* return branch count */
	public function getBranchCount()
	{
		$this->db->select('*');
		$query = $this->db->get_where('branch',array('delete_status'=>0));
		// $data = $query->result();

		// if($data != null){
		// 	return sizeof($data);
		// }else{
		// 	return 0;
		// }
		return $query->result();		
	}

	/* 
		return all branch data  
	*/
	public function getBranch(){
		return $this->db->select('b.*,ct.name as city')
						->from('branch b')
						->join('cities ct','ct.id = b.city_id')
						->where('b.delete_status',0)
						->get()
						->result();
	}
		
	public function addModel($data)
	{
		if($this->db->insert('branch',$data)){
			return  $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	/* 
		return branch record specified id 
	*/
	public function getRecord($id){
		$sql = "select * from branch where branch_id = ?";
		if($query = $this->db->query($sql,array($id))){
		/*$this->db->where('branch_id',$id);
		if($query = $this->db->get('branch')){*/
			return $query->result();
		}
		else{
			return FALSE;
		}
	}
	/* 
		edit branch specified id 
	*/
	public function editModel($data,$id){
		$this->db->where('branch_id',$id);
		if($this->db->update('branch',$data)){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*
		delete branch specified id 
	*/
	public function deleteModel($id){
		/*$sql = "delete from branch where branch_id = ?";
		if($this->db->query($sql,array($id))){*/
		$this->db->where('branch_id',$id);
		if($this->db->update('branch',array('delete_status'=>1))){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>