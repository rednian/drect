<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Personal_info_deactivation extends MY_Model{

		const DB_TABLE = 'Personal_info_deactivation';
    	const DB_TABLE_PK = 'deact_id';
    	
    	const FIRST_NAME = 'firstname';
    	const LAST_NAME = 'lastname';
    	const MIDDLE_NAME = 'middlename';
    	
    	public $deact_id;
		public $pi_id;
		public $reason;
		public $deactivation_status;
		public $remarks;

	}

?>