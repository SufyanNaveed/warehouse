<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Composite_model extends CI_Model 
{
	
	public function _consruct(){
		parent::_construct();
   	}
	
	function getData(){
		return $this->db->select('*')
						->from('composite')
						->get()
						->result();
   	}

   	function getDataEntry($composite_id){
		return $this->db->select('si.*,p.name')
						->from('composite_products si')
						->join('composite s','s.composite_id = si.composite_id')
						->join('products p','p.product_id = si.product_id')
						->where('si.composite_id',$composite_id)
						->get()
						->result();
   	}

   	function getRecord($composite_id){
		$record = $this->db->get_where('composite',array('composite_id'=>$composite_id))->row();
		return $record;
   	}

   	function getRecordItem($composite_product_id){
		$record = $this->db->get_where('composite_products',array('composite_product_id'=>$composite_product_id))->row();
		return $record;
   	}

   	function addModel($data){
	     
		if($this->db->insert('composite',$data)){
			return  $this->db->insert_id();
		}
		else{
			return FALSE;
		}
   	}

   	function addModelItem($data){
	     
		if($this->db->insert('composite_products',$data)){
			return  $this->db->insert_id();
		}
		else{
			return FALSE;
		}
   	}

	function editModel($data,$composite_id){

		$this->db->where('composite_id',$composite_id);
		
		if($this->db->update('composite',$data)){
			return true;
		}
		else{
			return false;
		}
   	}

   	function editModelItem($data,$composite_product_id){
	     
		$this->db->where('composite_product_id',$composite_product_id);
		
		if($this->db->update('composite_products',$data)){
			return true;
		}
		else{
			return false;
		}
   	}
   	
   	function deleteModel($composite_id){
	     
		$this->db->where('composite_id', $composite_id);

		if($this->db->delete('composite')){

			$this->db->where('composite_id', $composite_id);

			if($this->db->delete('composite_products')){
				return true;	
			}
		}
		else{
			return FALSE;
		}
   	}
   	
   	function deleteModelItem($composite_product_id){
	     
		$this->db->where('composite_product_id', $composite_product_id);

		if($this->db->delete('composite_products')){
			return true;	
		}
		else{
			return FALSE;
		}
   	}
	
}
?>