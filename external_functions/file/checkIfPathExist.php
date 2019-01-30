<?php
session_start();
	require_once('db.php');
	require_once('file.php');

	if(isset($_GET['url'])){
		$url = $_GET['url'];
		//$url = "../../FILES/";
		$sql = "SELECT * FROM file_folder where `folder_path` like '%{$url}%'";
		$result = $dBAccountUser->query($sql);
		if($dBAccountUser->num_rows($result) > 0){
			echo json_encode(array("result"=>true));
		}
		else{
			echo json_encode(array("result"=>false));
		}
	}
		
?>