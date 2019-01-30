<?php
	session_start();

	if(isset($_GET['element'])){
		$element = $_GET['element'];
		$id = $_GET['id'];

		$_SESSION['select'] = array("element"=>$element,"id"=>$id);
	}

	if(isset($_GET["reset"])){
		$_SESSION['select'] = array("element"=>"","id"=>"");
	}
	

?> 