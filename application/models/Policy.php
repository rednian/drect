<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Policy extends MY_Model{

		const DB_TABLE = 'policy';
    	const DB_TABLE_PK = 'policy_id';
    	
    	public $policy_id;
		public $policy_no;
		public $policy_title;
		public $policy_content;
		
	}

?>