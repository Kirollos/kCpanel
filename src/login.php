<?php
/*

Copyright 2015 Kirollos

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

*/

$legalaccess = true;

require_once('config.php');

if(isset($_SESSION["logged"]) && $_SESSION["logged"] == 1)
{
	header("Location: index.php?Page=Home");
	die;
}

if(isset($_POST['Action']) && $_POST['Action'] == 'login')
{
	foreach($Accounts as $useraccs)
	{
		if($_POST["Username"] == $useraccs[0])
		{
			if($_POST["Password"] == $useraccs[1])
			{
				$_SESSION['logged'] = true;
				$_SESSION['Username'] = $_POST["Username"];
				die('_:Success');
			}
		}
	}
	
	die('_:Fail');
}

?>

<script>
	function check_login()
	{
		$.post("login.php", 
		{
			Action:'login',
			Username:document.getElementById("username_box").value,
			Password:document.getElementById("password_box").value
		}, 
		function(data)
		{
			document.getElementById("leprogressbar").style.display = "block";
			setTimeout( function() {
			if(data != "_:Success")
			{
				document.getElementById("PossibleMsg").innerHTML = "<div class='alert alert-danger'><strong>Oh Snap!</strong> Incorrect Username/Password!</p>";
				document.getElementById("leprogressbar").style.display = "none";
				//Show error
			}
			else
			{
				document.getElementById("PossibleMsg").innerHTML = "<div class='alert alert-success'><strong>Awesome!</strong> You have logged in Successfully!</p>";
				document.getElementById("leprogressbar").style.display = "none";
				setTimeout('location.href = "index.php?Page=Home";', /*5000*/ 1000);
			}
			
			}, /*5000*/ 1000);
		});
	}
</script>

<div class="panel panel-primary">
	<div class="panel-heading">
		Login
	</div>
	<div class="panel-body">
		<div class="form form-group">
			<label class="sr-only" for="leBoxToHash">Username</label>
			<input type="text" class="form-control" id="username_box" placeholder="Username">
		</div>
		<div class="form-group">
			<label class="sr-only" for="leBoxToHash">Text</label>
			<input type="password" class="form-control" id="password_box" placeholder="Password">
		</div>
		<button type="submit" class="btn btn-primary" id="button" name="button" onclick="check_login()">Log in  <span class="glyphicon glyphicon-ok"></span></button>
	</div>
	<br />
	<div id="leprogressbar" style="display:none;">
		<div class='progress progress-striped active'> <div class='progress-bar' role='progressbar' aria-valuenow='1' aria-valuemin='0' aria-valuemax='1' style='width:100%;'></div> </div>
	</div>
</div>