<?php
	session_start();
	
	require_once('db.php');
	require_once('file.php');

	$file = new file();

	$path = $_GET['load'];

	$_SESSION['active_path'] = $path;

	if($path != "../../FILES/"){

		$explode = explode("/",$path);
		$sliced = array_slice($explode, 0, -2); // array ( "Hello", "World" )
		$string = implode("/", $sliced);  // "Hello World";

		$BackPath = $string."/";
		echo "<tr>
					<td colspan='7'><a href='#view?folder=".$BackPath."' onclick=\"load_folders_files('".$BackPath."')\"><i class='fa fa-arrow-circle-up fa-1x'></i> back to top</a></td>					
				</tr>";
	}
	
	load_empty($path);
	load_folder($path);	
	load_files($path);
	
	function load_folder($folderPath){
		global $file;
		global $dBAccountUser;

		$path = array("folder_path"=>$folderPath);
		$file->bind($path);
		$result = $file->load_folder($path);

		while($row = $dBAccountUser->fetch_array($result)){
			$folder_name = $row['folder_name'];
			$openPath = $folderPath."".$folder_name."/";
			$select = $row['folder_id'];
			$folder_id = $row['folder_id'];

			echo "<tr class='folder_".$folder_id."' onclick=\"select('folder_".$folder_id."');select_id('folder','".$folder_id."')\">
					<td style='width:3%'><input id='folder_".$folder_id."' class='check_select hide' type='radio' name='check_select' value='".$select."'> </td>
					<td><a href='#view?folder=".$openPath."' onclick=\"load_folders_files('".$openPath."')\"><i style='color:#DED44A' class='fa fa-folder-open fa-1x'></i>&nbsp;&nbsp;&nbsp;&nbsp;".$row['folder_name']."</a></td>
					<td></td>
					<td>".$row['folder_date']."</td>
					<td></td>
					<td>".$row['type']."</td>
					<td style='text-align:right'>
						<button onclick=\"info_folder('".$select."')\" class=\"btn btn-xs btn-default no-radius btn-sm\" title=\"Properties\">
                        	<i class=\"fa fa-info\"></i>
                      	</button>
					</td>
				</tr>";
		}
	}

	function load_files($path){
		global $file;
		global $dBAccountUser;

		$path = array("path"=>$path);
		$file->bind($path);
		$result = $file->load_files($path);

		$image = array(
			"jpg"=>"fa fa-file-image-o fa-1x",
			"docx"=>"fa fa-file-word-o fa-1x docx",
			"doc"=>"fa fa-file-word-o fa-1x docx",
			"ppt"=>"fa fa-file-powerpoint-o fa-1x ppt",
			"pptx"=>"fa fa-file-powerpoint-o fa-1x ppt",
			"xlsx"=>"fa fa-file-excel-o fa-1x xlsx",
			"xls"=>"fa fa-file-excel-o fa-1x xlsx",
			"pdf"=>"fa fa-file-pdf-o fa-1x pdf",
			"txt"=>"fa fa-file-text-o fa-1x",
			"rar"=>"fa fa-file-archive-o fa-1x",
			"zip"=>"fa fa-file-archive-o fa-1x",
			"png"=>"fa fa-file-image-o fa-1x",
			"gif"=>"fa fa-file-image-o fa-1x"
			);
		
		while($row = $dBAccountUser->fetch_array($result)){

			$file_id = $row['f_id'];
			
			$type = $row['file_type'];
			$size = $row['file_size'];
			$KBsize = $size / 1024;

			$form_name	= $row['form_name'];
			$path = $row['path'];

			$file_path = "../".str_replace("../../", "", $path.$form_name.".".$type);

			$select = $row['f_id'];

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

			echo "<tr class='file_".$file_id."' onclick=\"select('file_".$file_id."');select_id('file','".$file_id."')\">
					<td style='width:3%'><input id='file_".$file_id."' class='check_select hide' type='radio' name='check_select' value='".$select."'></td>
					<td>".$icon." ".$row['form_name'].".".$type."</td>
					<td>v".$row['version']."</td>
					<td>".$row['date_uploaded']."</td>
					<td>".$DisplaySize."</td>
					<td>".$row['file_type']."</td>
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

		$path_file = array("path"=>$path);
		$path_folder = array("folder_path"=>$path);

		$file->bind($path_file);
		$file->bind($path_folder);

		$files = $file->load_files($path_file);
		$folder = $file->load_folder($path_folder);

		$count_files = $dBAccountUser->num_rows($files);
		$count_folder = $dBAccountUser->num_rows($folder);

		if($count_folder == 0 && $count_files == 0){
			echo "
				<tr>					
					<td colspan='7' style='text-align:center'>This folder is empty.</td>
				</tr>";
		}
		elseif($count_folder == 0){

		}
		elseif($count_files == 0){

		}
	}

	

?>