<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Department extends MY_Model{

		const DB_TABLE = 'department';
    	const DB_TABLE_PK = 'dep_id';
    	
    	public $dep_id;
		public $div_id;
		public $department;

	}

?>