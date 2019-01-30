<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Position extends MY_Model{

		const DB_TABLE = 'positions';
    	const DB_TABLE_PK = 'pos_id';
    	
    	public $pos_id;
		public $dep_id;
		public $position;
		public $div_id;
		public $position_type;

		public function get_dep_id($pos_id){
			$dep_id = $this->search(array("pos_id"=>$pos_id));
			return $dep_id[$pos_id]->dep_id;
		}
	}

?>