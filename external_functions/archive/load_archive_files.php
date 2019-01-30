<?php
	   require_once('db.php');
  
  	try{
  		$data = array();

  		$sql = "SELECT * FROM file_archive";
  		$result = $dBAccountUser->query($sql);

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
      
      $icon = "";

  		while($row = $dBAccountUser->fetch_assoc($result)){

      $type = $row['af_type'];

      if (array_key_exists($type, $image)) {
          $icon = "<i class='".$image[$type]."'></i>&nbsp;&nbsp;&nbsp;&nbsp;";
      }
      else{
        $icon = "<i class='fa fa-file-o'></i>&nbsp;&nbsp;&nbsp;&nbsp;";
      }

  		$size = $row['af_size'];
			$KBsize = $size / 1024;
			$DisplaySize = "";

			if($KBsize < 1024){
				$DisplaySize = round($KBsize,2)." KB";
			}
			elseif($KBsize >= 1024){
				$DisplaySize = round(($KBsize/1024),2)." MB";
			}

  			$data[] = array(
  						'af_name'=>$icon." ".$row['af_name'],
  						'af_version'=>"v".$row['af_version'],
  						'af_type'=>$row['af_type'],
  						'af_size'=>$DisplaySize,
  						'af_date_modified'=>$row['af_date_modified'],
              'af_remark'=>$row['af_remark'],
              'action'=>"<button onclick=\"window.open('".base_url()."FILES/archive/".$row['af_path']."')\" type='button' class='btn btn-default btn-xs'><i class='fa fa-download'></i> Download</button>"
  						);

  		}
  		echo json_encode($data);
  	}
  	catch(Exception $e){

  	}
  	
?>