<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Division extends MY_Model{

		const DB_TABLE = 'division';
    	const DB_TABLE_PK = 'div_id';
    	
		public $div_id;
		public $division;

	}

?>