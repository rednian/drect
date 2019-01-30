<?php
session_start();
	require_once('db.php');
	require_once('file.php');

	$action = $_GET['action'];
	$element = $_SESSION['select']['element'];
	$id = $_SESSION['select']['id'];

	$file = new file;

	if($element != "" && $id != ""){
		$_SESSION['selected_function'] = array("action"=>$action,"element"=>$element,"id"=>$id);
	}

	if(isset($_GET["reset"])){
		$_SESSION['selected_function'] = array("action"=>"","element"=>"","id"=>"");
	}
	
	if($action == "delete"){

	}

	function delete_file_db($id){
		global $file;
		global $dBAccountUser;

		$sql = "DELETE FROM FILE";
	}

?> 