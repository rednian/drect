<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Pos_type_access extends MY_Model{

		const DB_TABLE = 'pos_type_access';
    	const DB_TABLE_PK = 'pta_ID';
    	
    	public $pta_ID;
    	public $am_id;
		public $position_type;
		
	}

?>