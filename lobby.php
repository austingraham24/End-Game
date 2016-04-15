<?php
session_start();
//connect to database
$link = new mysqli("localhost","root","p@ssword","end-game");
if ($link->connect_errno) 
{
   printf("Connect failed: %s\n", $link->connect_error);
   exit();
}
if(isset($_SESSION['user'])){
	$email = $_SESSION["user"];
	$password = $_SESSION[$email];
	$result = $link->query("SELECT password FROM players where email='$email'");
	$row = $result->fetch_assoc();
	if($password != $row["password"]){
		header('Location: index.php');
	}
}else{
	header('Location: index.php');
}

if(isset($_REQUEST["action"]))
	$action = $_REQUEST["action"];
else
	$action = "none";

$email = $_SESSION["user"];
$fullname="";
$result = $link->query("SELECT first_name, last_name, id, displayName FROM players where email='$email'");
$row = $result->fetch_assoc();
$first = $row['first_name'];
$last = $row['last_name'];
$fullname = $first." ".$last;
$displayName = $row['displayName'];
$userID = $row['id'];

    if($action == "login")
    {
        $email = $_POST["email"];
        $password = $_POST["password"];
        
        $email = htmlentities($link->real_escape_string($email));
        $password = htmlentities($link->real_escape_string($password));
        $password = crypt ($password,"Hufflepuff");
        $result = $link->query("SELECT password FROM players where email='$email'");
		$row = $result->fetch_assoc();
		if(!$result)
            die ('Can\'t add user because: ' . $link->error);
        else
			if(($password == $row["password"])&&$password!=''){
				$_SESSION['user'] = $email;
				$_SESSION[$email] = $row['password'];
				header('Location: game.php');
			}else{
				print("Failed");
			}

    }


?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>arg12c Test Blog</title>
		<!--from jquery.com-->
		<script src="http://code.jquery.com/jquery-1.12.0.min.js"></script>
		<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

		<!--local javascript files-->
		<script src="js/lobby.js"></script>
		<script src="http://150.252.35.128:9000/socket.io/socket.io.js"></script>

		<!-- local stylesheet-->
		<link href="css/main.css" rel="stylesheet" />
		<link href="css/modal.css" rel="stylesheet" />

		<script>
			var playerDisplay = '<?php print($displayName); ?>';
			var playerId = '<?php print($userID); ?>';
			function setup(){
				var createButton = document.getElementById("createButton");
				createButton.addEventListener("click", addGame);
			}

			function addGame(){
				socket.emit('addGame',playerDisplay);
				//alert('Create!');
			}

			function addGameDB(playerId){
				$.ajax({url: "ajax.php?action=createGame&id="+playerId, success: function(data){
					//alert(data);
					$('#lobbyAlert').html("You created a game!");
					$('#createButton').slideUp();
					$('#lobbyAlert').delay("slow").slideDown();
					socket.emit('updateAvailableGames',data);
					appendToGameTable("<tr><td>$displayName</td><td>This is your game</td></tr>");
					//$('#availableGameTableBody').append(data);
				}});
			}

			function joinGame(gameId){
				$.ajax({url: "ajax.php?action=joinGame&id="+playerId+'gameId='+gameId, success: function(data){
					//alert(data);
					socket.emit('joinGame',gameId);
					window.location = "game.php";
				}});
				//window.location = "game.php";
			}


			function appendToGameTable(data){
				//alert('Hello!');
				$('#availableGameTableBody').append(data);
			}

			function error(){
				//alert('Hello');
				$('#lobbyAlert').html("The Socket Server is down. Please try again later.");
				//$('#lobbyAlert').removeClass('alert-success');
				//$('#lobbyAlert').addClass('alert-danger');
				$('#createButton').slideUp();
				$('#lobbyAlert').delay("slow").slideDown();
			}

			//window.location = "www.abc.com"

			try{
				var socket = io.connect('http://150.252.35.128:9000');

				socket.on('connect', function(){
				    //var uname = prompt("What's your name: ");
				    //console.log(uname);
				    //socket.emit('adduser', uname);
				    socket.emit('adduser',playerDisplay);
				});

				socket.on('addGame',function(message){
					//$('#lobbyAlert').html(message);
					//$('#createButton').slideUp();
					//$('#lobbyAlert').delay("slow").slideDown();
					//alert('addGame');
					addGameDB(playerId);
				});

				socket.on('updateAvailableGames', function (data){
					//alert('socket');
			        appendToGameTable(data);
			    });

			    socket.on('updateGames',function(message){
			    	alert(message);
			    });

			    socket.on('startGame',function(creator){
			    	alert('start');
			    	if(creator == playerDisplay){
			    		window.location = "game.php";
			    	}
			    });
			}
			catch(err){
				alert(err);
				error();

			}
		</script>

	</head>
	<body role="document">
	    <!-- Fixed navbar--><!--taken from a bootstrap.com theme example and modified-->
	    <nav class="navbar navbar-inverse navbar-static-top">
	      <div class="container">
	        <div class="navbar-header">
	          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	            <span class="sr-only">Toggle navigation</span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </button>
	          <a class="navbar-brand" href="#">Underland Chess</a>
	        </div>
	        <div id="navbar" class="navbar-collapse collapse">
	          <ul class="nav navbar-nav pull-right">
	          	<li role="presentation"><a href='#' class='inactive'>Hello, <?php print($fullname);?></a></li>
                <li role="presentation"><a class="site-login" href="logOut.php"> Log Out</a></li>

	          </ul>
	        </div><!--/.nav-collapse -->
	      </div>
	    </nav>
	    <!--end nav section-->

	    <div class='container lobbyContainer'>
	    	<div id='lobbyAlert' class="alert alert-success lobby-alert" align='center'>
			  Default
			</div><br/>
	    	<button class='btn btn-success' id='createButton'>Create A Game!</button><br/>
	    	<section>
	    		<h3>Available Games:</h3>
	    		<table id = 'availableGameTable' class="table table-hover">
                    <thead>
                      <tr>
                        <th>Player</th>
                        <th>Join!</th>
                      </tr>
                    </thead>
                    <tbody id='availableGameTableBody'>

                    </tbody>
	    		</table>
	    	</section>

			<section>
	    		<h3>Active Games:</h3>
	    	</section>

	    </div>
	    <script>setup();</script>
	</body>
	</html>