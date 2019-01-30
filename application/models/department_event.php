<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Department_event extends MY_Model{

		const DB_TABLE = 'department_event';
    	const DB_TABLE_PK = 'event_id';
    	
    	const FIRST_NAME = 'firstname';
    	const LAST_NAME = 'lastname';
    	const MIDDLE_NAME = 'middlename';
    	
    	public $event_id;
    	public $pi_id;
    	public $dep_id;
		public $title;
		public $from_date;
		public $from_time;
		public $until_date;
		public $until_time;
		public $place;
		public $description;
		public $color;

	}

?>