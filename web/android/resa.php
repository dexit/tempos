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
		if(!$check){
			$id = intval($_REQUEST['id']);
			$sql=mysql_query("SELECT R.id, RP.name AS RoomProfile, A.name AS Activity, R.date, R.duration, Ro.name AS Salle, RR.name AS ReservationReason, R.comment, UG.name AS Groupe,
						CONCAT(U.family_name, ' ', U.surname) AS User, R.members_count, R.guests_count, R.price, R.custom_1, R.custom_2, R.custom_3, R.UserGroup_id
						FROM Reservation AS R
						LEFT OUTER JOIN RoomProfile AS RP ON R.RoomProfile_id = RP.id
						LEFT OUTER JOIN Activity AS A ON R.Activity_id = A.id
						LEFT OUTER JOIN ReservationReason AS RR ON R.ReservationReason_id = RR.id
						LEFT OUTER JOIN User AS U ON R.User_id = U.id
						LEFT OUTER JOIN Room AS Ro ON RP.Room_id=Ro.id
						LEFT OUTER JOIN UserGroup UG ON R.userGroup_id=UG.id
						WHERE (R.userGroup_id IN ( SELECT UserGroup_id FROM UserGroup_has_User WHERE User_id=".$id.") AND R.date >= CURDATE())
						OR (R.userGroup_id IS NULL AND User_id=".$id." AND R.date >= CURDATE()) 
						");
			while($row=mysql_fetch_array($sql)){
				$users = "";
				if(isset($row['UserGroup_id'])){
					$sql2=mysql_query("SELECT CONCAT(U.family_name, ' ', U.surname) AS User
								FROM UserGroup_has_User AS UG
								INNER JOIN User AS U ON UG.User_id=U.id
								WHERE UG.UserGroup_id=".$row['UserGroup_id']);
					while($row2=mysql_fetch_array($sql2)){
						$users = $users.$row2['User'].':';
					}
				}
				echo $row['id'].';'.$row['RoomProfile'].';'.$row['Activity'].';'.$row['User'].';'.$row['ReservationReason'].';'.$row['date'].';'.$row['duration'].
				';'.$row['Salle'].';'.$row['members_count'].';'.$row['guests_count'].';'.$row['comment'].';'.$row['price'].';'.$row['custom_1'].';'.$row['custom_2'].
				';'.$row['custom_3'].';'.$row['Groupe'].';'.$users.';fin!';
			}
		}else{
			echo 'auth erreur';
		}
		mysql_close();
	}else{
		echo 'erreur';
	}
?>