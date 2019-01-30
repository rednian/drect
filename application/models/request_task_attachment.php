<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Request_task_attachment extends MY_Model{

		const DB_TABLE = 'request_task_attachment';
    	const DB_TABLE_PK = 'rta_id';
    	
    	public $rta_id;
    	public $rt_id;
		public $rta_attachment;
		
	}

?>