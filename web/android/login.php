<?php
	if(isset($_REQUEST['user']) && isset($_REQUEST['password']) && isset($_REQUEST['bdduser']) && isset($_REQUEST['bddpassword'])){
		$user = $_REQUEST['user'];
		$password = $_REQUEST['password'];
		$bdduser = $_REQUEST['bdduser'];
		$bddpassword = $_REQUEST['bddpassword'];
		$check = true;
		$id = 0;
		mysql_connect("localhost",$bdduser,$bddpassword) or die(mysql_error());
		mysql_select_db("tempos") or die(mysql_error());
		$sql=mysql_query("SELECT id, login, password_hash FROM User");
		while($row=mysql_fetch_array($sql)){
			if($row['login']==$user){
				if($row['password_hash']==sha1($password)){
					$check = false;
					$id = $row['id'];
				}
			}
		}
		mysql_close();
		if($check){
			echo 'false;0;';
		}else{
			echo 'true;'.$id.';';
		}
	}else{
		echo 'false;0;';
	}
?>