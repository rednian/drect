<?php
	session_start();
	require_once('db.php');
	require_once('file.php');

	if(isset($_GET['del'])){

		$f_id = $_GET['del'];
		$remarks = $_GET['remarks'];
		$date = date("Y-m-d H:i:s");
		$file_info = get_file_info($f_id);

		$sql = "UPDATE file set on_delete = '$date' where f_id = $f_id";
		$result = $dBAccountUser->query($sql);

		if($result){

			$arcPath = "..".ds."..".ds."FILES".ds."archive".ds.$f_id."-".$file_info['form_name']." - v".($file_info['version']).".".$file_info['file_type'];
			$oldPath = $file_info['path'].$file_info['form_name'].".".$file_info['file_type'];

			$filename = $f_id."-".$file_info['form_name']." - v".($file_info['version']).".".$file_info['file_type'];

			copy($oldPath, $arcPath);
			unlink($oldPath);

			archive_db($file_info['form_name'],$file_info['version'],$filename,$file_info['description'],$file_info['file_size'],$file_info['file_type'],$remarks);
			
			echo json_encode(array("result"=>"deleted","path"=>$file_info['path']));
		}
		else{
			echo json_encode(array("result"=>"not deleted"));
		}


	}

	function get_file_info($f_id){
		global $dBAccountUser;
		$sql = "SELECT * FROM file where f_id = $f_id";
		$result = $dBAccountUser->query($sql);

		if($dBAccountUser->num_rows($result) > 0){
			$row = $dBAccountUser->fetch_array($result);
			return $row;
		}
	}

	function archive_db($name,$version,$path,$desc,$size,$type,$remarks){
		global $dBAccountUser;
		$date = date('Y-m-d h:i:s a');
		$sql = "INSERT INTO file_archive (af_name, af_version, af_path, af_description, af_date_modified, af_size, af_type, af_remark)
				VALUES('$name',$version,'$path','$desc','$date',$size,'$type','$remarks')";
		$result = $dBAccountUser->query($sql);
	}
?>