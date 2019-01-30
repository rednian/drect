<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Procedure_edit extends MY_Model{

		const DB_TABLE = 'procedure_edit';
    	const DB_TABLE_PK = 'edit_id';
    	
    	public $edit_id;
    	public $procedure_id;
		public $edit_title;
		public $edit_date;
		public $edit_form;
		public $edit_description;
		public $edit_status;
		public $edit_remarks;
		public $edit_type;
		public $edit_version;
		public $policy_id;
		public $remarks_from;
	}

?>