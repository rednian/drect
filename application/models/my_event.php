<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class My_event extends MY_Model{

		const DB_TABLE = 'my_event';
    	const DB_TABLE_PK = 'event_id';
    	
    	public $event_id;
    	public $calendar_id;
    	public $pi_id;
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