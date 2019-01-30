<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Institutional_calendar extends MY_Model{

		const DB_TABLE = 'Institutional_calendar';
    	const DB_TABLE_PK = 'calendar_id';
    	
    	public $calendar_id;
		public $calendar_name;
		public $calendar_description;
		public $pi_id;
		public $calendar_date_created;

	}

?>