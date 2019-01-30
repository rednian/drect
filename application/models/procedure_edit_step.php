<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Procedure_edit_step extends MY_Model{

		const DB_TABLE = 'procedure_edit_step';
    	const DB_TABLE_PK = 'edit_step_id';
    	
    	public $edit_step_id;
		public $edit_id;
		public $edit_signatory;
		public $edit_step;
		public $edit_duration;
		public $edit_duration_type;

		public function get_steps($procedure_id){
			
			$this->db->select("*");
			$this->db->from("personal_info as per_info");
			$this->db->from("procedure_edit_step as pro_step");
			$this->db->from("procedure_edit as pro");
			$this->db->where("pro.procedure_id", $procedure_id);
			$this->db->where("pro.edit_id = pro_step.edit_id");
			$this->db->where("pro_step.edit_signatory = per_info.pi_id");
			$query = $this->db->get();

			if ($query->num_rows() > 0)
			{
			   return $query->result();
			}

		}
	}

?>