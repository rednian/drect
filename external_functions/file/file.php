<?php
// If it's going to need the dBAccountUser, then it's 
// probably smart to require it before we start.

class File{
	
	private $table_name = "file";
	
	
	public function bind($data)
	{
		foreach($data as $key=>$value)
		{
			$this->$key = $value;
		}
	}

	public function insert_file() {
	  	global $dBAccountUser;
	  	$fields = array(
	  					"form_name"=>"",
	  					"description"=>"",
	  					"version"=>"",
	  					"path"=>"",
	  					"file_type"=>"",
	  					"file_size"=>"",
	  					"date_uploaded"=>""
	  					);

	  	$sql = "";
	  	$keys = "";
		$values = "";
	  	$sql .= "Insert into {$this->table_name}";
						foreach($fields as $key=>$value)
						{
							$keys .= "{$key},";
							$values .= "'{$this->$key}',";
						}

						$sql .= "(".substr($keys, 0, -1).") values(".substr($values, 0, -1).")";

		$result = $dBAccountUser->query($sql);
		return $result;

	}

	public function load_folder($path){

		global $dBAccountUser;

		$sql = "";
		$sql .= "SELECT * FROM file_folder where ";
				foreach($path as $key=>$value)
				{	
					
					$sql .= "{$key} = '{$this->$key}' ";

				}
		$sql .= "ORDER BY folder_name";
		$result = $dBAccountUser->query($sql);
		return $result;
	}

	public function load_files($path){

		global $dBAccountUser;

		$sql = "";
		$sql .= "SELECT * FROM file where ";
				foreach($path as $key=>$value)
				{	
					
					$sql .= "{$key} = '{$this->$key}' ";

				}
		$sql .= "AND (on_delete is null or on_delete = '')ORDER BY form_name";
		$result = $dBAccountUser->query($sql);
		return $result;

	}

	public function create_folder(){
		global $dBAccountUser;
	  	$fields = array(
	  					"folder_name"=>"",
	  					"folder_path"=>"",
	  					"type"=>"",
	  					"folder_date"=>"",
	  					);

	  	$sql = "";
	  	$keys = "";
		$values = "";
	  	$sql .= "Insert into file_folder";
						foreach($fields as $key=>$value)
						{
							$keys .= "{$key},";
							$values .= "'{$this->$key}',";
						}

						$sql .= "(".substr($keys, 0, -1).") values(".substr($values, 0, -1).")";

		$result = $dBAccountUser->query($sql);
		return $result;
	}

	public function  search($table_name,$data){
		global $dBAccountUser;

		$and = "and";
		$sql = "";
		$i = 0;
		$count = count($data);
		$sql .= "SELECT * FROM {$table_name} where ";
				foreach($data as $key=>$value)
				{	
					if($i == $count - 1) {
				        $and = "";
				    }

					$sql .= "{$key} like '{$this->$key}%' $and ";

					$i++;
				}		
		$result = $dBAccountUser->query($sql);
		return $result;
	}

	public function count_filename($name,$path){
		global $dBAccountUser;
		$sql = "SELECT * from file where (form_name = '{$name}' OR form_name like '{$name}_%') AND path = '{$path}'";
		$result = $dBAccountUser->query($sql);
		$count = $dBAccountUser->num_rows($result);
		return $count;
	}

	public function count_foldername($name,$path){
		global $dBAccountUser;
		$sql = "SELECT * from file_folder where (folder_name = '{$name}' OR folder_name like '{$name}_%') AND folder_path = '{$path}'";
		$result = $dBAccountUser->query($sql);
		$count = $dBAccountUser->num_rows($result);
		return $count;
	}

	public function file_exists($form_name,$path){
		global $dBAccountUser;
		$sql = "SELECT * from file where form_name = '{$form_name}' and path = '{$path}'";
		$result = $dBAccountUser->query($sql);
		return $dBAccountUser->num_rows($result);
	}

}

?>