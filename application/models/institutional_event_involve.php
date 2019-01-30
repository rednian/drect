<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Institutional_event_involve extends MY_Model{

		const DB_TABLE = 'institutional_event_involve';
    	const DB_TABLE_PK = 'ins_involve_id';
    	
    	public $ins_involve_id;
    	public $id;
    	public $event_id;
    	public $type;
		
	}

?>