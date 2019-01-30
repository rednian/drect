<?php
	require_once('db.php');

	if($_SERVER['REQUEST_METHOD'] == "POST"){
		$email_id = $_POST['email_id'];

		if($email_id == ""){
			$sql = "";
			$values="";
			$keys="";

			$data = array(
						"email"=>$_POST['email'],
						"display_name"=>$_POST['display_name'],
						"email_password"=>$_POST['email_password'],
						"type"=>$_POST['type'],
						);

			$sql .= "INSERT INTO official_email_domain";
					foreach($data as $key=>$value){
						$keys .= $key.",";
						$values .= '"'.$dBAccountUser->escape_value($value).'"'.',';
					}
					$sql .= "(".substr($keys, 0, -1).") VALUES(".substr($values, 0,-1).")";

			if(!is_exist($_POST['email'])){
				$result = $dBAccountUser->query($sql);
				if($result){
					echo json_encode(array("result"=>$result,"type"=>"success"));
				}
				else{
					echo json_encode(array("result"=>$result,"type"=>"error"));
				}
			}
			elseif(is_exist($_POST['email'])){
				echo json_encode(array("result"=>"exist","type"=>"error"));
			}
		}
		elseif($email_id != ""){
			//UPDATE
			$sql = "";
			$values="";
			$keys="";

			$data = array(
						"email"=>$_POST['email'],
						"display_name"=>$_POST['display_name'],
						"email_password"=>$_POST['email_password'],
						);

			$sql .= "UPDATE official_email_domain SET ";
					foreach($data as $key=>$value){
						$values .= $key."='".$value."',";
					}
					$sql .= substr($values, 0,-1)." WHERE email_id = $email_id";

			$result = $dBAccountUser->query($sql);
			if($result){
				echo json_encode(array("result"=>"updated","type"=>"success"));
			}
			else{
				echo json_encode(array("result"=>"not updated","type"=>"error"));
			}
		}
		
	}

	function is_exist($email){
		global $dBAccountUser;
		$sql = "SELECT * FROM official_email_domain where email = '$email' LIMIT 1";
		$result = $dBAccountUser->query($sql);
		if($dBAccountUser->num_rows($result) == 1){
			return true;
		}
		else{
			return false;
		}
	}
?>