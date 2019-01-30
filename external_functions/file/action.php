<?php
	session_start();
	require_once('db.php');
	require_once('file.php');

	$file = new file();

	if(isset($_GET['action'])){

		$action = $_SESSION['selected_function']['action'];
		$element = $_SESSION['selected_function']['element'];
		$id = $_SESSION['selected_function']['id'];
		$active_path = $_SESSION['active_path'];

		if($_GET['action']=="paste"){
			if($action != "" && $element != "" && $id !=""){
				if($action == "copy"){
					copy_file_folder();
				}
				elseif($action == "cut"){

				}
			}
		}
	}

	function copy_file_folder(){
		global $element;
		global $id;

		if($element == "file"){
			copy_file($id);
		}
	}

	function copy_file($id){
		global $file;
		global $dBAccountUser;
		global $active_path;

		$search = array('f_id'=>$id);
		$file->bind($search);

		$result = $file->load_files($search);

		if($row = $dBAccountUser->fetch_array($result)){

			date_default_timezone_set("Asia/Manila");
			$date_uploaded = date("Y-m-d h:i:s a");

			$form_name = $row['form_name'];
			$classification = $row['classification'];
			$version = $row['version'];
			$div = $row['div_id'];
			$dep = $row['dep_id'];
			$file_path = $row['path'];
			$type = $row['file_type'];
			$file_size = $row['file_size'];

			$dir = $active_path;

			$count = $file->count_filename($form_name,$active_path);

			if($count > 0){
				$new_form_name = $form_name."_".($count+1);
			}
			elseif($count == 0){
				$new_form_name = $form_name;
			}

			$copyPath = $file_path.$form_name.".".$type;
			$pathToPaste = $dir.$new_form_name.".".$type;

			$data = array(
			  			"form_name"=>$new_form_name,
			  			"classification"=>$classification,
			  			"version"=>$version,
			  			"div_id"=>$div,
			  			"dep_id"=>$dep,
			  			"path"=>$dir,
			  			"file_type"=>$type,
			  			"file_size"=>$file_size,
			  			"date_uploaded"=>$date_uploaded
			  			);

			$file->bind($data);
			$result = $file->insert_file();

			if($dBAccountUser->affected_rows() == 1){
				copy($copyPath,$pathToPaste);
			}
		}

	}
	
?>