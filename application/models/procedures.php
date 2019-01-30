<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Procedures extends MY_Model{

		const DB_TABLE = 'procedure';
    	const DB_TABLE_PK = 'procedure_id';
    	
    	public $procedure_id;
		public $procedure;
		public $procedure_version;
		public $procedure_date_modified;
		public $status;
		public $procedure_form;
		public $procedure_type;
		public $policy_id;
		public $remarks;
		public $remarks_from;
		
		public function load_procedures($type, $stat){
			$this->db->where("status = '{$stat}'");
			$this->db->where("procedure_type = '{$type}'");
			$list = $this->get();
			return $list;
		}
	}

?>