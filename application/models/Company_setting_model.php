<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Company_setting_model extends CI_Model
{
	public function getCountry(){
		return $this->db->get('countries')->result();
	}
	public function getState($id = null){
		if($id == null){
			$setting = $this->getData();
			if($setting != null){
				$id = $setting[0]->country_id;
			}
			
		}
		
		return $this->db->select('s.*')
		                 ->from('states s')
		                 ->join('countries c','c.id = s.country_id')
		                 ->where('s.country_id',$id)
		                 ->get()
		                 ->result();
	}
	public function getCity($id = null){
		if($id == null){
			$setting = $this->getData();
			if($setting != null){
				$id = $setting[0]->state_id;
			}
		}
		return $this->db->select('c.*')
		                 ->from('cities c')
		                 ->join('states s','s.id = c.state_id')
		                 ->where('c.state_id',$id)
		                 ->get()
		                 ->result();
	}

	public function getCompanyAddress(){
	
		return $this->db->select('cs.*,c.name as city_name, s.name as state_name, ce.name as country_name')
						->from('company_settings cs')
		                ->join('cities c','c.id = cs.city_id')
		                ->join('states s','s.id = c.state_id')
		                ->join('countries ce','ce.id = cs.country_id')
		                ->get()
		                ->row();
	}
	public function getCurrency()
	{
		return $this->db->get('currency')->result();
	}

	public function getData()
	{
		return $this->db->get('company_settings')->result();
	}
	public function add($data)
	{
		$d = $this->db->get('company_settings')->result();
		if($d != null){
			return $this->db->update('company_settings',$data);
		}
		else{
			return $this->db->insert('company_settings',$data);	
		}
	}
}
?>
