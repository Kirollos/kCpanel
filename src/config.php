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

if(!isset($legalaccess))
	$legalaccess = false;
	
if(!$legalaccess)
	die("lol nope.");

$usingMySQL = true; // true = use mysql | false = use static arrays

include('libcheck.php');

@session_start();


$MySQLData = (object) array // MySQL Data || MySQL Structure tables can be found at the root directory
(
	"host"	=>	""	,
	"user"	=>	""	,
	"pass"	=>	""	,
	"db"	=>	""	//,
	//"port"	=>	3306		// Comment this line (and the above comma of course ^) if your default port is 3306.
);

$Accounts = array();
$Server_Infos = array();
$SSH_Data = array();

if(!$usingMySQL) { // Modify the arrays below If you are not using MySQL.

$Accounts = array(
	/*array(	ACCOUNT_NAME,		ACCOUNT_PASSWORD,	IS_ADMIN)*/
	array(		"TestAcc"	,		"myPass"		,	true	) // Example
);

$Server_Infos = array(
	/*array(		IP			,		PORT			,		NAME			,		RCON_PASSWORD	,		ALLOW_ACCESS=array(		USERS		))*/
	array(			'127.0.0.1'	,		7777			,		"My Test Server",		"myrcon"		,		array("TestAcc", "acc2")) // Example
);

$SSH_Data = array(
	/*array(		IP/HOST		,		PORT			,		USERNAME		,		PASSWORD		,		SERVER_DIR		,		SERVER_EXEC		,		STOP_RCONCMD="exit"		,		RESTART_RCONCMD="gmx")*/
	array(			'127.0.0.1'	,		22				,		'myssh'			,		'mysshpass'		,		"~/samp03"		,		"./samp03svr"	,		"exit"					,		"gmx"				) // Example
);

}
else
{
	$mysqlhandle = new mysqli($MySQLData->host, $MySQLData->user, $MySQLData->pass, $MySQLData->db, isset($MySQLData->port) ? $MySQLData->port : 3306);

	if($mysqlhandle->connect_error)
	{
		die("Connection Error ({$mysqlhandle->connect_errno}): {$mysqlhandle->connect_error}");
	}

	// Accounts

	$query = $mysqlhandle->query("SELECT * FROM `Accounts`");

	while(($leAccounts = $query->fetch_array(MYSQLI_ASSOC)) != null)
	{
		//echo '<script>alert("ok");</script>';
		//$Accounts	+=	array(array($leAccounts["Username"], $leAccounts["Password"], (bool)$leAccounts["IsAdmin"]));
		$Accounts	=	array_merge($Accounts, array(array($leAccounts["Username"], $leAccounts["Password"], (bool)$leAccounts["IsAdmin"])));
	}

	$query->free();

	// -------------------------------------------------------------------------------------------------------------

	// Server Infos && SSH Data

	$query = $mysqlhandle->query("SELECT * FROM `Server_Infos`");

	while(($leAccounts = $query->fetch_array(MYSQLI_ASSOC)) != null)
	{
		//$Server_Infos	+=	array(array($leAccounts["Server_IP"], (int)$leAccounts["Server_Port"], $leAccounts["Server_Name"], $leAccounts["Server_RCON"], explode(",", $leAccounts["Allow_Access"])));
		//$SSH_Data		+=	array(array($leAccounts["Server_IP"], (int)$leAccounts["SSH_Port"], $leAccounts["SSH_Username"], $leAccounts["SSH_Password"], $leAccounts["ServerDir"], $leAccounts["ServerExec"], $leAccounts["Stop_RCONCMD"], $leAccounts["Restart_RCONCMD"]));
		$Server_Infos	=	array_merge($Server_Infos, array(array($leAccounts["Server_IP"], (int)$leAccounts["Server_Port"], $leAccounts["Server_Name"], $leAccounts["Server_RCON"], explode(",", $leAccounts["Allow_Access"]))));
		$SSH_Data		=	array_merge($SSH_Data, array(array($leAccounts["Server_IP"], (int)$leAccounts["SSH_Port"], $leAccounts["SSH_Username"], $leAccounts["SSH_Password"], $leAccounts["ServerDir"], $leAccounts["ServerExec"], $leAccounts["Stop_RCONCMD"], $leAccounts["Restart_RCONCMD"])));
	}

	$query->free();

	// -------------------------------------------------------------------------------------------------------------

	$mysqlhandle->close();
}

//echo'<pre>';var_dump($Server_Infos);echo'</pre>';

$max_svrlog_lines = 100; // Maximum amount of server log lines to load

?>