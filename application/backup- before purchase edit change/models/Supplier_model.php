<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Supplier_model extends CI_Model
{
	function __construct() {
		parent::__construct();

		
	}

	public function get_records(){
         return $this->db->select('s.*')
                         ->from('users s')
                         
                         ->get()
                         ->result();
    }

	public function getCountry(){
		return $this->db->get('countries')->result();
	}
	
	public function getState($id){	
		return $this->db->select('s.*')
		                 ->from('states s')
		                 ->join('countries c','c.id = s.country_id')
		                 ->where('s.country_id',$id)
		                 ->get()
		                 ->result();
	}
	
	public function getCity($id){
		return $this->db->select('c.*')
		                 ->from('cities c')
		                 ->join('states s','s.id = c.state_id')
		                 ->where('c.state_id',$id)
		                 ->get()
		                 ->result();
	}
}
?>