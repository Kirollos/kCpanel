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
require_once("config.php");

if(!isset($_SESSION['logged']) || !$_SESSION['logged'])
{
	header("Location: ?Page=Home");
	die;
}
?>

<script>
$( document ).ready(function() {

	document.getElementById('leprogressbar').style.display = "block";
	
	setTimeout(function() {
	document.getElementById('leprogressbar').style.display = "none";
	document.getElementById("PossibleMsg").innerHTML = "<div class='alert alert-info'>You have logged out Successfully!</p>";
	<?php @session_unset(); ?>
	setTimeout('location.href = "?Page=Home";', /*1000*/ 500);
	}, /*5000*/ 1000);

});
</script>

<div class="panel panel-primary">
	<div class="panel-heading">
		Log out
	</div>
	<div class="panel-body">
	
	Logging you out now. Please wait....
	
	</div>
	<br />
	<div id="leprogressbar" style="display:none;">
		<div class='progress progress-striped active'> <div class='progress-bar' role='progressbar' aria-valuenow='1' aria-valuemin='0' aria-valuemax='1' style='width:100%;'></div> </div>
	</div>
</div>