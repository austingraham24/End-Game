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
	if(($password == $row["password"])&&$password!=''){
		header('Location: lobby.php');
	}
}

$action="";
if(isset($_REQUEST["action"])){
	$action = $_REQUEST["action"];
}
else{
	$action = "none";
}


if($action == "add_user")
    {
        $fname = $_POST["fname"];
        $lname = $_POST["lname"];
        $email = $_POST["email"];
        $display = $_POST["dname"];
        $password = $_POST["password"];
        
        $fname = htmlentities($link->real_escape_string($fname));
        $lname = htmlentities($link->real_escape_string($lname));
        $email = htmlentities($link->real_escape_string($email));
        $display = htmlentities($link->real_escape_string($display));
        $password = htmlentities($link->real_escape_string($password));
        $password = crypt ($password,"Hufflepuff");
        $result = $link->query("INSERT INTO players (first_name,last_name,displayName,email,password) VALUES ('$fname', '$lname', '$display', '$email', '$password')");

        $loggedIn = true;
        print($result);

        if(!$result)
            die ('Can\'t add user because: ' . $link->error);
        else{
        	print("Adding");
			$_SESSION["user"] = $email;
			$_SESSION[$email] = $password;
            header('Location: lobby.php');
        }
    }

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
				header('Location: lobby.php');
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
		<script src="js/main.js"></script>

		<!-- local stylesheet-->
		<link href="css/main.css" rel="stylesheet" />
		<link href="css/modal.css" rel="stylesheet" />

		<script>
			function validateSignIn(){
				var email = document.getElementById('logInEmail').value
				var password = document.getElementById('logInPassword').value
				var error = document.getElementById('loginError')
				/*if (email!=""){
					if(password!=""){
						var result="";
						var url = 'ajax.php?action=getPassword&email='
						url = url + email+ '&pass='+password;
						$.get(url,function(data,status){
							alert('Status: ' + status);
							alert('Data: ' + data);
							result = data;
						});
						alert(result);
						if(result=='true'){
								return true;
						}else{
							$("#loginError").slideDown("medium");
							return false;
						}
					}else{
						return false;
					}
				}else{
					return false;
				}*/
			}

		</script>

	</head>
	<body role="document" id='index'>
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
                <li role="presentation"><a class="site-login" href="#"> Log In</a></li>

	          </ul>
	        </div><!--/.nav-collapse -->
	      </div>
	    </nav>
	    <!--end nav section-->

		<!--<div class="container">-->
			<div class="main index-main">
            <div class="container sign-up-container">
                <div class="text main-nav">
                    <h1>Underland Chess</h1> 
                    <h4>Welcome to the best chess game in all of Underland!</h4>
                    <p class="lead "></p>
                    <p><a id="join-button" class="btn btn-lg btn-danger site-signUp" href="#" role="button">Sign Up Today!</a></p>
                </div>
            </div>
        </div>

        <div id="sign-in-modal" class="sign-in-modal">
          	<div class="sign-in-modal-container">
                <ul class="modal-switcher">
                    <li><a href="#">Sign In</a></li>
                    <li><a href="#">New Account</a></li>
                </ul>
                <div id="modal-login"> <!-- log in form -->
                	<div id="loginError" role="alert" class="alert alert-danger modal-alert alert-hide"><p>That username and password did not match our records. Please, try again.<p></div>
                    <form class="modal-form" name="signIn" onsubmit="return validateSignIn()" method="post" action="#">
                    	<input type="hidden" name="action" value="login">
                        <p class="fieldset"><label class="modal-label">Email:</label><input required class="modal-input" id="logInEmail" name="email" type="email" placeholder="E-mail"></p>
                        <p class="fieldset"><label class="modal-label">Password:</label><input required class="modal-input" id="logInPassword" name="password" type="password"  placeholder="Password"></p>
                        <input class="modal-input" type="submit" value="Login">
                    </form>
                </div>
                <div id="modal-signup"> <!-- sign up form -->
                    <form class="modal-form" name="signUp" onsubmit="return validateSignUp()" method="post" action="#">
                    	<input type="hidden" name="action" value="add_user">
                        <p class="fieldset"><label class="modal-label">First Name:</label><input required class="modal-input" id="fname" name="fname" type="text" placeholder="First Name"></p>
                        <p class="fieldset"><label class="modal-label">Last Name:</label><input required class="modal-input" id="lname" name="lname" type="text" placeholder="Last Name"></p>
                        <p class="fieldset"><label class="modal-label">Display Name:</label><input required class="modal-input" id="dname" name="dname" type="text" placeholder="Display Name"></p>
                        <p class="fieldset"><label class="modal-label">Email:</label><input required class="modal-input" id="email" name="email" type="email" placeholder="E-mail"></p>
                        <p class="fieldset"><label class="modal-label">Password:</label><input required class="modal-input" id="password" name="password" type="password"  placeholder="Password"></p>
                        <p class="fieldset"><label id="pass2Label" class="modal-label">Enter Password Again:</label><input required class="modal-input" id="password2" name="password2" type="password"  placeholder="Retype Password"></p>
                        <input class="modal-input" type="submit" value="Create Account">
                    </form>
                </div>
            </div>
          </div>

	    <script>

	    </script>

	</body>
</html>