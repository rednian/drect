<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Access_menu extends MY_Model{

		const DB_TABLE = 'access_menu';
    	const DB_TABLE_PK = 'am_id';
    	
    	public $am_id;
		public $menu;
		public $mod_id;

	}

?>