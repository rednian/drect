<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Procedure_delete extends MY_Model{

		const DB_TABLE = 'procedure_delete';
    	const DB_TABLE_PK = 'delete_id';
    	
    	public $delete_id;
		public $procedure_id;
		public $delete_description;
		public $delete_status;
		public $delete_date;
		public $delete_remarks;
		public $delete_type;
		public $remarks_from;
	}

?>