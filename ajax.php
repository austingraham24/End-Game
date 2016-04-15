<?php

	//===========================
	//	Database connections
	//===========================	
	$mysqli = new mysqli("localhost","root","p@ssword","end-game");
	if ($mysqli->connect_error) {
		die('Connect Error (' . $mysqli->connect_errno . ') '
				. $mysqli->connect_error);
	}
	//===========================

	$response = "";
	
	$action = $mysqli->real_escape_string($_GET["action"]);
	//print($action);

	if($action=='createGame'){
		$id = $_GET["id"];
		$result = $mysqli->query("INSERT into game (creator,active) values ($id,1)");
		//$game = $mysqli->query("SELECT id FROM game where creator='$id' and active=1");
		$game = $mysqli->query("SELECT game.id, players.displayName FROM game inner join players on game.creator = players.id where creator=$id and active=1");
		$row = $game->fetch_assoc();
		$gameId = $row['id'];
		$displayName = $row['displayName'];
		print("<tr><td>$displayName</td><td><button class='btn btn-success' onclick='joinGame($gameId)'>Join!</button></td></tr>");
		//print($result);
	}

	if($action=='joinGame'){
		$id = $_GET["id"];
		$gameId = $_GET["gameId"];
		$result = $mysqli->query("UPDATE game set opponent = $id where id = $gameId");
		//print($result);
	}

	/*$id = $mysqli->real_escape_string($_GET["id"]);
	$user = $mysqli->real_escape_string($_GET["user"]);
	
	$result = $mysqli->query("SELECT * FROM shopping_cart WHERE user_id='".$user."'");
	if(!$result){
		$response = "Can't use query last name because: " . $mysqli->connect_errno . ':' . $mysqli->connect_error;
		$result = $mysqli->query("INSERT into shopping_cart (user_id, book_id) values($user,$id)");
	}else
	{
		$row = mysqli_fetch_assoc($result);
		if(!$row['book_id']){
			$result = $mysqli->query("INSERT into shopping_cart (user_id, book_id) values($user,$id)");
		}else{
			$cart = $row["book_id"];
			$cart .= ",$id";
			//print 'Stuff '.$cart;
			//print("UPDATE shopping_cart SET book_id=$cart WHERE user_id=$user");
			$result = $mysqli->query("UPDATE shopping_cart SET book_id='$cart' WHERE user_id=$user");
		}

		$result = $mysqli->query("SELECT book_id from shopping_cart WHERE user_id=$user");
		$row = $result->fetch_assoc();
		$list = explode(",", $row["book_id"]);
		
	}*/

	//$response= $id.' '.$user;
	print $response;

?>