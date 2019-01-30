<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Temp_personal_info_accessibility extends MY_Model{

		const DB_TABLE = 'temp_personal_info_accessibility';
    	const DB_TABLE_PK = 'tpa_id';
    	
    	public $tpa_id;
		public $am_id;
		public $tpi_id;

	}
?>