<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Department_event_involve extends MY_Model{

		const DB_TABLE = 'department_event_involve';
    	const DB_TABLE_PK = 'dep_involve_id';
    	
    	public $dep_involve_id;
    	public $id;
    	public $event_id;
    	public $type;
		
	}

?>