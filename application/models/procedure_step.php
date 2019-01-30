<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Procedure_step extends MY_Model{

		const DB_TABLE = 'procedure_step';
    	const DB_TABLE_PK = 'pro_sig_id';
    	
    	public $pro_sig_id;
		public $procedure_id;
		public $procedure_signatory;
		public $step;
		public $duration;
		public $duration_type;

		public function get_steps($procedure_id){
			
			$this->db->select("*");
			$this->db->from("personal_info as per_info");
			$this->db->from("procedure_step as pro_step");
			$this->db->from("procedure as pro");
			$this->db->where("pro.procedure_id", $procedure_id);
			$this->db->where("pro.procedure_id = pro_step.procedure_id");
			$this->db->where("pro_step.procedure_signatory = per_info.pi_id");
			$query = $this->db->get();

			if ($query->num_rows() > 0)
			{
			   return $query->result();
			}

		}

	}

?>