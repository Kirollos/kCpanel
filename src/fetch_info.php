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
require('config.php');

if(!isset($_GET["fetchinfo"]) || !isset($_GET["svrid"]))
	die;


$serverid = $_GET["svrid"];

$serverquery = new SampQueryAPI($Server_Infos[$serverid][0], $Server_Infos[$serverid][1]);

$serverstatus = $serverquery->isOnline();

if($serverstatus)
{

	$ServerPlayers = $serverquery->getDetailedPlayers();
	$ServerInfo = $serverquery->getInfo();
	$ServerInfo += $serverquery->getRules();

	if($ServerInfo["language"] == "-")
	{
		$ServerInfo["language"] = "N/A";
	}
}
else
{
	
	$ServerPlayers = NULL;
	$ServerInfo = NULL;
	$serverstatus = 0;
}

$ServerHostname = $ServerInfo == NULL ? "N/A" : $ServerInfo["hostname"];
$ServerPassworded = isset($ServerInfo["password"]) ? ($ServerInfo["password"] == 1 ? "True" : "False") : ("N/A");
$ServerPlayer = $ServerInfo == NULL ? "N/A" : "{$ServerInfo["players"]}/{$ServerInfo["maxplayers"]}";
$ServerMode = $ServerInfo == NULL ? "N/A" : $ServerInfo["gamemode"];
$ServerMap = $ServerInfo == NULL ? "N/A" : $ServerInfo["mapname"];
$ServerLanguage = $ServerInfo == NULL ? "N/A" : $ServerInfo["language"];
$ServerVersion = $ServerInfo == NULL ? "N/A" : $ServerInfo["version"];

if($_GET["fetchinfo"] == "serveronline") die((bool)$serverstatus);

if($_GET["fetchinfo"] == "serverhostname") die($ServerHostname);
if($_GET["fetchinfo"] == "serverpassworded") die($ServerPassworded);
if($_GET["fetchinfo"] == "serverplayer") die($ServerPlayer);
if($_GET["fetchinfo"] == "servermode") die($ServerMode);
if($_GET["fetchinfo"] == "servermap") die($ServerMap);
if($_GET["fetchinfo"] == "serverlanguage") die($ServerLanguage);
if($_GET["fetchinfo"] == "serverversion") die($ServerVersion);

//var_dump($ServerInfo);die;

$split = '|';
if($_GET["fetchinfo"] == "all") die (
										(string)$serverstatus . $split . $ServerHostname . $split . $ServerPassworded . $split . $ServerPlayer . $split . $ServerMode . $split . $ServerMap . $split . $ServerLanguage . $split . $ServerVersion
									);

if($_GET["fetchinfo"] == "fetchplayers") {
if($serverstatus == true){
$szTemp = "";
$szTemp .= '<table class="table table-stuff"><tbody><tr><td>#</td><td>Player Name</td><td>Score</td><td>Ping</td><td><span class="glyphicon glyphicon-signal"></span></td></tr>';
$thecount = 0;
foreach($ServerPlayers as $leplayer)
{
	if($leplayer['ping'] <= 100)
		$pingcolour = "green";
	else
	if($leplayer['ping'] > 100 && $leplayer['ping'] <= 250)
		//$pingcolour = "yellow";
		$pingcolour = "orange"; // orange is quite better lol
	else
	if($leplayer['ping'] > 250)
		$pingcolour = "red";
	$szTemp .= '<tr><td>'.$leplayer['playerid'].'</td><td>'.$leplayer['nickname'].'</td><td>'.$leplayer['score'].'</td><td>'.$leplayer['ping'].'</td><td><span class="glyphicon glyphicon-signal" style="color:'.($pingcolour).';"></span></td></tr>';
	$thecount ++;
}
if(!$thecount)
{
	$szTemp .= '<tr><td>N/A</td><td>N/A</td><td>N/A</td><td>N/A</td></tr>';
}
$szTemp .= '</tbody></table>';

}
die($szTemp != NULL ? $szTemp : "");
}
?>