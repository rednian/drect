<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Policy_attachment extends MY_Model{

		const DB_TABLE = 'policy_attachment';
    	const DB_TABLE_PK = 'pol_at_id';
    	
    	public $pol_at_id;
		public $policy_id;
		public $path;
		
	}

?>