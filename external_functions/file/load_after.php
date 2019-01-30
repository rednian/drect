<?php
session_start();
	require_once('db.php');
	require_once('file.php');

	if(isset($_GET['result_in'])){
		$path = $_SESSION['active_path'];
		$slice = explode("/",$path);
		$count = count($slice);
		
		$display = "Search results in ".$slice[$count-2];
		$arrDisplay = array("display"=>$display);

		echo json_encode($arrDisplay);

	}
	elseif(!isset($_GET['result_in'])){
		$path = $_SESSION['active_path'];
		$newpath = str_replace('../../', '', $path);

		$trimmed_path = explode("/",$newpath);
		$openPath = "";
		$display = "";
		$count = count($trimmed_path);
		for($x = 0;$x<$count;$x++){
			$openPath .= $trimmed_path[$x]."/";
			$display .= " <a href='#view?folder=../../".$openPath."' onclick=\"load_folders_files('../../".$openPath."')\">".$trimmed_path[$x]."</a> /";
		}

		echo json_encode(array('path'=>$path,'display'=>rtrim($display, "/")));
	}
?>