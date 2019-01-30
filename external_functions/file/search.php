<?php
session_start();
	require_once('db.php');
	require_once('file.php');

	$file = new file();

	$path = $_SESSION['active_path'];
	$name = $_GET['search'];

	load_empty($path);
	search_folder($path);
	search_files($path);

	function search_folder($path){
		global $file;
		global $dBAccountUser;
		global $name;

		$table_name = "file_folder";
		$search = array("folder_path"=>$path,"folder_name"=>$name);

		$file->bind($search);
		$result = $file->search($table_name,$search);

		while($row = $dBAccountUser->fetch_array($result)){

			$folderPath = $row['folder_path'];
			$folder_name = $row['folder_name'];
			$openPath = $folderPath."".$folder_name."/";
			$select = $row['folder_id'];

			echo "<tr>
					<td colspan='2'><a href='#view?folder=".$openPath."' onclick=\"load_folders_files('".$openPath."')\"><i style='color:#DED44A' class='fa fa-folder-open fa-2x'></i>&nbsp;&nbsp;&nbsp;&nbsp;<span style='font-size:15px'>".$row['folder_name']."</span></a><br><span style='color:#9B9B9B;font-size:11px'>Date modified:</span> ".$row['folder_date']."</td>
					<td></td>
					<td colspan='3'>".$folderPath."</td>
					<td style='text-align:right'>
						<button onclick=\"info_folder('".$select."')\" class=\"btn btn-xs btn-default no-radius btn-sm\" title=\"Properties\">
                        	<i class=\"fa fa-info\"></i>
                      	</button>
					</td>
				</tr>";
		}
	}

	function search_files($path){
		global $file;
		global $dBAccountUser;
		global $name;

		$table_name = "file";
		$search = array("path"=>$path,"form_name"=>$name);

		$file->bind($search);
		$result = $file->search($table_name,$search);

		$image = array(
			"jpg"=>"fa fa-file-image-o fa-2x",
			"docx"=>"fa fa-file-word-o fa-2x docx",
			"doc"=>"fa fa-file-word-o fa-2x docx",
			"ppt"=>"fa fa-file-powerpoint-o fa-2x ppt",
			"pptx"=>"fa fa-file-powerpoint-o fa-2x ppt",
			"xlsx"=>"fa fa-file-excel-o fa-2x xlsx",
			"xls"=>"fa fa-file-excel-o fa-2x xlsx",
			"pdf"=>"fa fa-file-pdf-o fa-2x pdf",
			"txt"=>"fa fa-file-text-o fa-2x",
			"rar"=>"fa fa-file-archive-o fa-2x",
			"zip"=>"fa fa-file-archive-o fa-2x",
			"png"=>"fa fa-file-image-o fa-2x",
			"gif"=>"fa fa-file-image-o fa-2x"
			);
		
		while($row = $dBAccountUser->fetch_array($result)){
			$type = $row['file_type'];
			$size = $row['file_size'];
			$KBsize = $size / 1024;
			$pathFile = $row['path'];
			$file_id = $row['f_id'];
			$form_name = $row['form_name'];

			$file_path = "../".str_replace("../../", "", $pathFile.$form_name.".".$type);

			if (array_key_exists($type, $image)) {
		   		$icon = "<i class='".$image[$type]."'></i>&nbsp;&nbsp;&nbsp;&nbsp;";
			}
			else{
				$icon = "<i class='fa fa-file-o'></i>&nbsp;&nbsp;&nbsp;&nbsp;";
			}

			if($KBsize < 1024){
				$DisplaySize = round($KBsize,2)." KB";
			}
			elseif($KBsize >= 1024){
				$DisplaySize = round(($KBsize/1024),2)." MB";
			}

			// echo "<tr>
			// 		<td colspan='2'>".$icon." ".$row['form_name'].".".$type."</td>
			// 		<td>".$row['date_uploaded']."</td>
			// 		<td>".$DisplaySize."</td>
			// 		<td>".$row['file_type']."</td>
			// 	</tr>";
			echo "<tr>
					<td colspan='2'>".$icon."<span style='font-size:15px'>".$row['form_name']."</span><br><span style='color:#9B9B9B;font-size:11px'>Date modified:</span> ".$row['date_uploaded']."</td>
					<td>v".$row['version']."</td>
					<td>".$pathFile."</td>
					<td colspan='2'><span style='color:#9B9B9B;font-size:11px'>Size:</span> ".$DisplaySize."<br><span style='color:#9B9B9B;font-size:11px'>Type:</span> ".$row['file_type']."</td>
					<td style='text-align:right'>
						<button onclick=\"info_files('".$file_id."')\" class=\"btn btn-xs btn-default no-radius btn-sm\" title=\"Get info\">
                        	<i class=\"fa fa-info\"></i>
                      	</button>
                      	<button onclick=\"edit_files('".$file_id."')\" class=\"btn btn-xs btn-default no-radius btn-sm\" title=\"Edit\">
                        	<i class=\"fa fa-edit\"></i>
                      	</button>
                      	<button onclick=\"location.href='".base_url().$file_path."'\" class=\"btn btn-xs btn-default no-radius btn-sm\" title=\"Download\">
                          <i class=\"fa fa-download\"></i>
                        </button>";
                        
                        if($type == "pdf" || $type == "jpg" || $type == "png" || $type == "gif"){
                        	echo "<button onclick=\"view_file('".$file_id."')\" class=\"btn btn-xs btn-default no-radius btn-sm\" title=\"Preview\">
	                        	<i class=\"fa fa-eye\"></i>
	                      	</button>";
                        }
						
				echo	"</td>
				</tr>";
		}
	}

	function load_empty($path){
		global $file;
		global $dBAccountUser;
		global $name;

		$table_folder = "file_folder";
		$table_file = "file";

		$search_folder = array("folder_path"=>$path,"folder_name"=>$name);
		$search_file = array("path"=>$path,"form_name"=>$name);

		$file->bind($search_folder);
		$file->bind($search_file);

		$result_folder = $file->search($table_folder,$search_folder);
		$result_file = $file->search($table_file,$search_file);

		$count_files = $dBAccountUser->num_rows($result_file);
		$count_folder = $dBAccountUser->num_rows($result_folder);

		if($count_folder == 0 && $count_files == 0){
			echo "
				<tr>					
					<td colspan='7' style='text-align:center'>No items match your search.</td>
				</tr>";
		}
		elseif($count_folder == 0){

		}
		elseif($count_files == 0){

		}
	}
?>