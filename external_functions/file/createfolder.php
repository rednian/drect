<?php
	
	session_start();
	
	require_once('db.php');
	require_once('file.php');

	$file = new file();

	$folder_name = strtolower($_GET['name']);

	if(!empty($folder_name)){
		if(create_folder($folder_name) == true){
			save_folder_db($folder_name);
			echo json_encode(array('result'=>1));
		}
		elseif(create_folder($folder_name) == false){
			echo json_encode(array('result'=>0));
		}
	}
	
	function save_folder_db($folder_name){
		global $file;
		global $id;

		date_default_timezone_set("Asia/Manila");
		$date_uploaded = date("Y-m-d h:i:s a");

		$data = array(
	  					"folder_name"=>$folder_name,
	  					"folder_path"=>$_SESSION['active_path'],
	  					"type"=>"folder",
	  					"folder_date"=>$date_uploaded
	  				 );

		$file->bind($data);
		$file->create_folder();
	}

	function create_folder($folder_name){
		$path = $_SESSION['active_path'].$folder_name;

		if (!file_exists($path)) {
		    $result = mkdir($path, 0777, true);
		    if($result){
		    	return true;
		    }
		}
		elseif(file_exists($path)){
			return false;
		}
	}

?>

