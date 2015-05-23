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
include('config.php');
if(!isset($_SESSION["logged"])) {
	$_SESSION["logged"] = 0;
	$_SESSION["Username"][0] = "\0";
}
?>
<!DOCTYPE html>
<html>

	<head>
		<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
		<meta content="utf-8" http-equiv="encoding">
		<link rel="stylesheet" type="text/css" href="libs/bootstrap/css/bootstrap.css">
		
		<link rel="stylesheet" type="text/css" href="style.css">
		
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
		<script type="text/javascript" src="libs/bootstrap/js/bootstrap.js"></script>
		<script type="text/javascript" src="libs/bootstrap/js/alert.js"></script>
		<script type="text/javascript" src="libs/bootstrap/js/modal.js"></script>
		<script type="text/javascript" src="libs/bootstrap/js/button.js"></script>
		<script type="text/javascript" src="libs/bootstrap/js/tooltip.js"></script>
		<title>kCpanel - <?php echo $_GET['Page'] ?></title>
		
		<script>
			$( document ).ready(function() {
			
			});
		</script>
	</head>
	
	<body>
		
		<nav class="navbar navbar-default" id="lewhiteurl" style="padding-top:5px;margin-top:50px;margin-right:100px;margin-left:100px;background-color:rgba(77, 77, 73, 0.6);border-color:rgba(77, 77, 73, 0.6);" role="navigation">
			<ul class="nav nav-pills navbar-left">
				<li></li><li></li>
				<?php
				if(isset($_GET['Page']))
				{
					if($_SESSION['logged'])
					{
						$serverlists = "";
						$count = 0;
						foreach($Server_Infos as $svrs)
						{
							$count ++;
							$isinaxx = false;
							
							foreach($svrs[4] as $access)
							{
								if($access == $_SESSION["Username"])
									{$isinaxx = true; break;}
								$isinaxx = false;
							}
							
							if(!$isinaxx) continue;
							
							$serverlists .= "<li><a href='?Page=Manage&svrid=" . (int) ($count-1) . "'>";
							$serverlists .= $svrs[2];
							$serverlists .= "</a></li>";
						}
					}
					
					switch($_GET['Page'])
					{
						case 'Home':
						{
							foreach($Accounts as $isadmincheck)
							{
								if($isadmincheck[0] == $_SESSION["Username"] && $isadmincheck[2] == true)
									{$isadmin = true; break;}
								$isadmin = false;
							}

							echo '<li class="active"><a href="?Page=Home">Home</a></li>';
							echo '
							<li class="dropdown' . ((!$_SESSION["logged"]) ? (' disabled') : ('')) . '">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#">Server Lists <span class="caret"></span></a>
								';
								if($_SESSION['logged']) {
								echo '<ul class="dropdown-menu" id="ledropdown">
									<!--<li><a>hi</a></li>
									<li><a>hi2</a></li>-->
									' . $serverlists . '
								</ul>';
								}
							echo '</li>
								';
							if(!$usingMySQL || !$isadmin)
							{
								break;
							}
							echo '<li><a href="?Page=Accounts">Accounts</a></li>';
							break;
						}
						case 'Manage':
						{
							foreach($Accounts as $isadmincheck)
							{
								if($isadmincheck[0] == $_SESSION["Username"] && $isadmincheck[2] == true)
									{$isadmin = true; break;}
								$isadmin = false;
							}

							echo '<li><a href="?Page=Home">Home</a></li>';
							echo '
							<li class="dropdown active' . ((!$_SESSION["logged"]) ? (' disabled') : ('')) . '">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#">Server Lists <span class="caret"></span></a>
								';
								if($_SESSION['logged']) {
								echo '<ul class="dropdown-menu" id="ledropdown">
									<!--<li><a>hi</a></li>
									<li><a>hi2</a></li>-->
									' . $serverlists . '
								</ul>';
								}
							echo '</li>
								';
							if(!$usingMySQL || !$isadmin)
							{
								break;
							}
							echo '<li><a href="?Page=Accounts">Accounts</a></li>';
							break;
						}
						case 'Accounts':
						{
							foreach($Accounts as $isadmincheck)
							{
								if($isadmincheck[0] == $_SESSION["Username"] && $isadmincheck[2] == true)
									{$isadmin = true; break;}
								$isadmin = false;
							}
							if(!$usingMySQL || !$isadmin)
							{
								header("Location: ?Page=Home");
								break;
							}
							echo '<li><a href="?Page=Home">Home</a></li>';
							echo '
							<li class="dropdown' . ((!$_SESSION["logged"]) ? (' disabled') : ('')) . '">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#">Server Lists <span class="caret"></span></a>
								';
								if($_SESSION['logged']) {
								echo '<ul class="dropdown-menu" id="ledropdown">
									<!--<li><a>hi</a></li>
									<li><a>hi2</a></li>-->
									' . $serverlists . '
								</ul>';
								}
							echo '</li>
								';
							echo '<li class="active"><a href="?Page=Accounts">Accounts</a></li>';
							break;
						}
						default:
						{
							foreach($Accounts as $isadmincheck)
							{
								if($isadmincheck[0] == $_SESSION["Username"] && $isadmincheck[2] == true)
									{$isadmin = true; break;}
								$isadmin = false;
							}

							echo '<li><a href="?Page=Home">Home</a></li>';
							echo '
							<li class="dropdown' . ((!$_SESSION["logged"]) ? (' disabled') : ('')) . '">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#">Server Lists <span class="caret"></span></a>
								';
								if($_SESSION['logged']) {
								echo '<ul class="dropdown-menu" id="ledropdown">
									<!--<li><a>hi</a></li>
									<li><a>hi2</a></li>-->
									' . $serverlists . '
								</ul>';
								}
							echo '</li>
								';
							if(!$usingMySQL || !$isadmin)
							{
								break;
							}
							echo '<li><a href="?Page=Accounts">Accounts</a></li>';
							break;
						}
					}
				}
				else{header("?Page=Home");}
				?>
			</ul>
			<ul class="nav nav-pills navbar-right">
				<?php
				if(isset($_GET['Page']))
				{
					switch($_GET['Page'])
					{
						case 'Login':
						{
							echo (($_SESSION["logged"]) ? ('<li><a href="?Page=Logout">Log Out</a></li>') : ('<li class="active"><a href="?Page=Login">Log In</a></li>'));
							break;
						}
						case 'Logout':
						{
							echo (($_SESSION["logged"]) ? ('<li class="active"><a href="?Page=Logout">Log Out</a></li>') : ('<li><a href="?Page=Login">Log In</a></li>'));
							break;
						}
						default:
						{
							echo (($_SESSION["logged"]) ? ('<li><a href="?Page=Logout">Log Out</a></li>') : ('<li><a href="?Page=Login">Log In</a></li>'));
						}
					}
				}
				else
				{
					echo (($_SESSION["logged"]) ? ('<li><a href="?Page=Logout">Log Out</a></li>') : ('<li><a href="?Page=Login">Log In</a></li>'));
				}
				?>
				<li></li><li></li>
			</ul>
		</nav>
	
		<div class="lediv"><br />
			<div class= <?php echo "\"" . ( ( isset($_GET['Page']) ) ? ( "lediv2-" . $_GET['Page'] ) : ("lediv2-Home") ) . "\"";?>>
				<div id="PossibleMsg" class="PossibleMSG">
				</div>
				<?php
				if(isset($_GET['Page']))
				{
					switch($_GET['Page'])
					{
						case 'Home':
						{
							?>
							<div class="panel panel-primary">
								<div class="panel-heading" style="text-align:center;">
									Welcome
								</div>
								<div class="panel-body">
								<?php
									if(isset($_SESSION['logged']) && $_SESSION['logged'] == 1) {
									echo "Welcome {$_SESSION['Username']}!";
									}
									else {
									echo "Welcome Guest! Please <a href='?Page=Login' style='text-decoration:none;'><kbd>log in</kbd></a> to have access to your servers!";
									}
									?>
								</div>
							</div>
							
							<div class="panel panel-primary">
								<div class="panel-heading" style="text-align:center;">
									Servers Summary
								</div>
								<div class="panel-body">
									<table class="table table-stuff" style="text-align:center">
										<tr>
											<td>#</td>
											<td>Server Name</td>
											<td>Hostname</td>
											<td>Players</td>
											<td>Server Status</td>
											<td>Access</td>
										</tr>
										<?php
										
										$count = 0;
										foreach($Server_Infos as $svrs)
										{
											$txt = "";
											$svr = new SampQueryAPI($svrs[0], (int) $svrs[1]);
											if($svr->isOnline() == true)
											{
												$isonline = true;
												$sinfo = $svr->getInfo();
												$hostname = $sinfo['hostname'];
												$players = $sinfo['players'];
												$maxplayers = $sinfo['maxplayers'];
												$ispassworded = $sinfo['password'];
											}
											else
											{
												$isonline = false;
												$hostname = 'N/A';
												$players = -1;
												$maxplayers = -1;
												$ispassworded = false;
											}
											
											$isinaxx = false;
											
											foreach($svrs[4] as $access)
											{
												if($access == $_SESSION["Username"])
													{$isinaxx = true; break;}
												$isinaxx = false;
											}
											
											$txt .= "<tr>";
											$txt .= "<td>" . ++$count . "</td>";
											$txt .= "<td>" . $svrs[2] . " (" . $svrs[0] . ":" . $svrs[1] . ")</td>";
											$txt .= "<td>" . $hostname . "</td>";
											$txt .= "<td>" . ( (!$isonline) ? ( "N/A" ) : ( $players . "/" . $maxplayers ) ) . "</td>";
											$txt .= "<td>" . ( ( $isonline ) ? ( ( $ispassworded == true ) ? ( "<span class='text-warning' style='font-weight:bold;'>Passworded</span>" ) : ( "<span style='color:green;font-weight:bold;'>Online</span>" ) ) : ( "<span style='color:red;font-weight:bold;'>Offline</span>" ) ) . "</td>";
											$txt .= "<td><a role='button' href='?Page=Manage&svrid=" . ((int) $count-1) . "' class='btn btn-" . ( ( $isinaxx ) ? ( "success" ) : ( "danger" ) ) . "' " . ( ( $isinaxx ) ? ( "" ) : ( "disabled='disabled'" ) ) . ">Manage</button></td>";
											$txt .= "</tr>";
											
											echo $txt;
											$txt = "";
										}
										
										?>
									</table>
								</div>
							</div>
							<?php
							break;
						}
						case 'Login': {if(file_exists('login.php')) include('login.php'); else echo "Error: Failed to load '{$_GET['Page']}' content."; break;}
						case 'Logout': {if(file_exists('logout.php')) include('logout.php'); else echo "Error: Failed to load '{$_GET['Page']}' content."; break;}
						case 'Manage': {if(file_exists('manage.php')) include('manage.php'); else echo "Error: Failed to load '{$_GET['Page']}' content."; break;}
						case 'Accounts': {if(!$usingMySQL) break; if(file_exists('accounts.php')) include('accounts.php'); else echo "Error: Failed to load '{$_GET['Page']}' content."; break;}
						default: die;
					}
				}
				else
					header("Location: ?Page=Home");
				?>
			</div>
			
		</div>
		
	</body>



</html>