<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Module extends MY_Model{

		const DB_TABLE = 'module';
    	const DB_TABLE_PK = 'mod_id';
    	
    	public $mod_id;
		public $module;
		
	}

?>