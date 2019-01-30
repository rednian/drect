<?php
	 
   require_once('db.php');

  	$zip = new ZipArchive;

  	$sql = "SELECT * FROM file_archive";
  	$result = $dBAccountUser->query($sql);

  	$files = array();

  	while($row = $dBAccountUser->fetch_array($result)){
  		$files[] = $row['af_path'];
  	}

  	foreach($files as $path){
  		add_archive($path);
  	}

  	function add_archive($path){
  		
  		global $zip;
  		$res = $zip->open('..'.ds.'..'.ds.'FILES'.ds.'archive_files.zip', ZipArchive::CREATE);

		if ($res === TRUE) {
			$content = file_get_contents("..".ds."..".ds."FILES".ds."archive".ds.$path);
		    $zip->addFromString($path, $content);
		    $zip->close();
		} else {
		    
		}
  	}

?>