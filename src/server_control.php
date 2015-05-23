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

if(!isset($_POST["Action"]))
	die;

$legalaccess = true;
require_once('config.php');

//$serverid = -1;
$serverid = (int)$_POST["ServerID"];

if((bool)$_POST["Confirm"] == true)
{
	if($_POST["Action"] == "Start_Server")
	{
		$return = Start_Server();
		die($return);
	}
	else
	if($_POST["Action"] == "Stop_Server")
	{
		$return = Stop_Server();
		die($return);
	}
	else
	if($_POST["Action"] == "GMX_Server")
	{
		$return = GMX_Server();
		die($return);
	}
	else
	if($_POST["Action"] == "Restart_Server")
	{
		$return = Restart_Server();
		die($return);
	}
	else
	if($_POST["Action"] == "GetServerCFG")
	{
		$return = GetServerCFG();
		if(!$return)
			die("_:Fail");
		else
			die($return);
	}
	else
	if($_POST["Action"] == "GetServerLog")
	{
		$return = GetServerLog();
		if(!$return)
			die("_:Fail");
		else
			die($return);
	}
	else
	if($_POST["Action"] == "GetServerBAN")
	{
		$return = GetServerBAN();
		if(!$return)
			die("_:Fail");
		else
			die($return);
	}
	/*else
	if($_POST["Action"] == "SaveCFG")
	{
		$return = SaveCFG();
		if(!$return)
			die("_:Fail");
		else
			die("_:Success");
	}*/
	else if($_POST["Action"][0] == '{')
	{
		/*if(explode("||", $_POST["Action"])[0] == "rcon")
		{
			Send_RCON(explode("||", $_POST["Action"])[1]);
		}
		else
		if(explode("||", $_POST["Action"])[0] == "SaveCFG")
		{
			SaveCFG(explode("||", $_POST["Action"])[1]);
		}
		else
		if(explode("||", $_POST["Action"])[0] == "SaveBAN")
		{
			SaveBAN(explode("||", $_POST["Action"])[1]);
		}
		else
		if(explode("|", $_POST["Action"])[0] == "ChangeVersion")
		{
			ChangeVersion(json_decode(explode("|", $_POST["Action"])[1]));
		}*/

		$decoded = json_decode($_POST["Action"]);
		switch($decoded->action)
		{
			case "rcon":
			{
				Send_RCON($decoded->data);
				break;
			}
			case "SaveCFG":
			{
				SaveCFG($decoded->data);
				break;
			}
			case "SaveBAN":
			{
				SaveBAN($decoded->data);
				break;
			}
			case "ChangeVersion":
			{
				ChangeVersion(json_decode($decoded->data));
				break;
			}
		}
	}
	return true;
}

function Send_RCON($_rconcmd)
{
	global
			$Accounts,
			$Server_Infos,
			$SSH_Data,
			$_SESSION,
			$_GET,
			$serverid,
			$serverquery,
			$serverstatus,
			$ServerPlayers,
			$ServerInfo,
			$ServerHostname,
			$ServerPassworded,
			$ServerPlayer,
			$ServerMode,
			$ServerMap
	;
	
	$rcon = new SampRconAPI($Server_Infos[$serverid][0], $Server_Infos[$serverid][1], $Server_Infos[$serverid][3]);
	if(!$rcon->isOnline())
	{
		die("Server is already off");
		return false;
	}
	die(str_replace("\t", "", implode("<br/>", $rcon->Call($_rconcmd))));
	//die(print_r($rcon->Call($_rconcmd), true));
	return true;
}

function Start_Server()
{
	global
			$Accounts,
			$Server_Infos,
			$SSH_Data,
			$_SESSION,
			$_GET,
			$serverid,
			$serverquery,
			$serverstatus,
			$ServerPlayers,
			$ServerInfo,
			$ServerHostname,
			$ServerPassworded,
			$ServerPlayer,
			$ServerMode,
			$ServerMap
	;
	$serverquery = new SampQueryAPI($Server_Infos[$serverid][0], $Server_Infos[$serverid][1]);
	
	if($serverquery->isOnline())
		return json_encode(array("status"=>"fail", "response"=>"Server is online."));
	/*$ssh2stream = ssh2_connect($SSH_Data[$serverid][0], (int)$SSH_Data[$serverid][1]);
	if(!$ssh2stream) return false;
	if
	(
		!ssh2_auth_password($ssh2stream, $SSH_Data[$serverid][2], $SSH_Data[$serverid][3])
	) return false;
	
	$ssh2exec = ssh2_exec($ssh2stream, "cd " . $SSH_Data[$serverid][4] . " \n " . $SSH_Data[$serverid][5] . " & \n\n");
	sleep(5);
	fclose($ssh2exec);*/

	$ssh2stream = new Net_SSH2($SSH_Data[$serverid][0], (int)$SSH_Data[$serverid][1]);
	if(!$ssh2stream) return json_encode(array("status"=>"fail", "response"=>"Failed to initialize SSH connection."));
	if
	(
		!$ssh2stream->login($SSH_Data[$serverid][2], $SSH_Data[$serverid][3])
	) return json_encode(array("status"=>"fail", "response"=>"Failed to log into SSH"));
	
	//$ssh2stream->exec("cd " . $SSH_Data[$serverid][4] . " \n " . $SSH_Data[$serverid][5] . " & \n\n");
	$ssh2stream->exec("cd " . $SSH_Data[$serverid][4] . " \n " . $SSH_Data[$serverid][5] . " >> /dev/null 2>&1 &\n");
	sleep(5);
	$ssh2stream->disconnect();
	
	$serverquery = new SampQueryAPI($Server_Infos[$serverid][0], $Server_Infos[$serverid][1]);

	$serverstatus = $serverquery->isOnline();
	
	if(!$serverstatus)
		return json_encode(array("status"=>"fail", "response"=>"Server failed to start."));
	
	return json_encode(array("status"=>"success", "response"=>"Server started successfully."));
}

function Stop_Server()
{
	global
			$Accounts,
			$Server_Infos,
			$SSH_Data,
			$_SESSION,
			$_GET,
			$serverid,
			$serverquery,
			$serverstatus,
			$ServerPlayers,
			$ServerInfo,
			$ServerHostname,
			$ServerPassworded,
			$ServerPlayer,
			$ServerMode,
			$ServerMap
	;
	
	$rcon = new SampRconAPI($Server_Infos[$serverid][0], $Server_Infos[$serverid][1], $Server_Infos[$serverid][3]);
	if(!$rcon->isOnline())
		return json_encode(array("status"=>"fail", "response"=>"Server is already offline."));
	$rcon->Call($SSH_Data[$serverid][6]!= NULL ? $SSH_Data[$serverid][6] : "exit");
	sleep(5);
	$serverquery = new SampQueryAPI($Server_Infos[$serverid][0], $Server_Infos[$serverid][1]);

	$serverstatus = $serverquery->isOnline();
	
	if($serverstatus)
		return json_encode(array("status"=>"fail", "response"=>"Server failed to stop."));
	return json_encode(array("status"=>"success", "response"=>"Server stopped successfully."));
}

function GMX_Server()
{
	global
			$Accounts,
			$Server_Infos,
			$SSH_Data,
			$_SESSION,
			$_GET,
			$serverid,
			$serverquery,
			$serverstatus,
			$ServerPlayers,
			$ServerInfo,
			$ServerHostname,
			$ServerPassworded,
			$ServerPlayer,
			$ServerMode,
			$ServerMap
	;
	
	$rcon = new SampRconAPI($Server_Infos[$serverid][0], $Server_Infos[$serverid][1], $Server_Infos[$serverid][3]);
	if(!$rcon->isOnline())
		return json_encode(array("status"=>"fail", "response"=>"Server is already offline."));
	$rcon->Call($SSH_Data[$serverid][7]!= NULL ? $SSH_Data[$serverid][7] : "gmx");
	return json_encode(array("status"=>"success", "response"=>"Server GMX'd successfully."));
}

function Restart_Server()
{
	global
			$Accounts,
			$Server_Infos,
			$SSH_Data,
			$_SESSION,
			$_GET,
			$serverid,
			$serverquery,
			$serverstatus,
			$ServerPlayers,
			$ServerInfo,
			$ServerHostname,
			$ServerPassworded,
			$ServerPlayer,
			$ServerMode,
			$ServerMap
	;
	
	/*if(Stop_Server())
	{
		return Start_Server();
	}
	return false;*/

	$ret = Stop_Server();
	$ret = json_decode($ret);
	if($ret.status == "success")
	{
		$ret = Start_Server();
		return $ret;
	}
	else return json_encode(array("status"=>"fail", "response"=>"Server failed to stop."));
}

function GetServerCFG()
{
	global
			$Accounts,
			$Server_Infos,
			$SSH_Data,
			$_SESSION,
			$_GET,
			$serverid,
			$serverquery,
			$serverstatus,
			$ServerPlayers,
			$ServerInfo,
			$ServerHostname,
			$ServerPassworded,
			$ServerPlayer,
			$ServerMode,
			$ServerMap
	;


	$ssh2stream = new Net_SSH2($SSH_Data[$serverid][0], (int)$SSH_Data[$serverid][1]);
	if(!$ssh2stream) return false;
	if
	(
		!$ssh2stream->login($SSH_Data[$serverid][2], $SSH_Data[$serverid][3])
	) return false;
	
	$ret = $ssh2stream->exec("cd " . $SSH_Data[$serverid][4] . " \n cat server.cfg\n");
	sleep(2);
	$ssh2stream->disconnect();

	return empty($ret) ? false : (string)$ret;
}

function SaveCFG($data)
{
	global
			$Accounts,
			$Server_Infos,
			$SSH_Data,
			$_SESSION,
			$_GET,
			$serverid,
			$serverquery,
			$serverstatus,
			$ServerPlayers,
			$ServerInfo,
			$ServerHostname,
			$ServerPassworded,
			$ServerPlayer,
			$ServerMode,
			$ServerMap
	;


	$ssh2stream = new Net_SSH2($SSH_Data[$serverid][0], (int)$SSH_Data[$serverid][1]);
	if(!$ssh2stream) die("_:Fail");
	if
	(
		!$ssh2stream->login($SSH_Data[$serverid][2], $SSH_Data[$serverid][3])
	) die("_:Fail");
	
	$ssh2stream->exec("cd " . $SSH_Data[$serverid][4] . " \n echo '".$data."' > server.cfg\n");
	sleep(2);
	$ret = $ssh2stream->exec("cd " . $SSH_Data[$serverid][4] . " \n cat server.cfg\n");
	$ssh2stream->disconnect();

	die($ret);
}

function GetServerLog()
{
	global
			$Accounts,
			$Server_Infos,
			$SSH_Data,
			$_SESSION,
			$_GET,
			$serverid,
			$serverquery,
			$serverstatus,
			$ServerPlayers,
			$ServerInfo,
			$ServerHostname,
			$ServerPassworded,
			$ServerPlayer,
			$ServerMode,
			$ServerMap,
			$max_svrlog_lines
	;

	//$max_svrlog_lines = 100; // ^ in config.php now


	$ssh2stream = new Net_SSH2($SSH_Data[$serverid][0], (int)$SSH_Data[$serverid][1]);
	if(!$ssh2stream) return false;
	if
	(
		!$ssh2stream->login($SSH_Data[$serverid][2], $SSH_Data[$serverid][3])
	) return false;
	
	$ret = $ssh2stream->exec("cd " . $SSH_Data[$serverid][4] . " \n tail server_log.txt -n {$max_svrlog_lines}\n");
	sleep(2);
	$ssh2stream->disconnect();

	return empty($ret) ? false : (string)implode("\n", array_reverse(explode("\n", $ret)));
}

function GetServerBAN()
{
	global
			$Accounts,
			$Server_Infos,
			$SSH_Data,
			$_SESSION,
			$_GET,
			$serverid,
			$serverquery,
			$serverstatus,
			$ServerPlayers,
			$ServerInfo,
			$ServerHostname,
			$ServerPassworded,
			$ServerPlayer,
			$ServerMode,
			$ServerMap
	;


	$ssh2stream = new Net_SSH2($SSH_Data[$serverid][0], (int)$SSH_Data[$serverid][1]);
	if(!$ssh2stream) return false;
	if
	(
		!$ssh2stream->login($SSH_Data[$serverid][2], $SSH_Data[$serverid][3])
	) return false;
	
	$ret = $ssh2stream->exec("cd " . $SSH_Data[$serverid][4] . " \n cat samp.ban\n");
	sleep(2);
	$ssh2stream->disconnect();

	if(empty($ret))
	{
		$ret = "This file was empty. Please edit me";
	}

	return /*empty($ret)*/ strpos($ret, "No such file or directory")!==false ? false : (string)$ret;
}

function SaveBAN($data)
{
	global
			$Accounts,
			$Server_Infos,
			$SSH_Data,
			$_SESSION,
			$_GET,
			$serverid,
			$serverquery,
			$serverstatus,
			$ServerPlayers,
			$ServerInfo,
			$ServerHostname,
			$ServerPassworded,
			$ServerPlayer,
			$ServerMode,
			$ServerMap
	;


	$ssh2stream = new Net_SSH2($SSH_Data[$serverid][0], (int)$SSH_Data[$serverid][1]);
	if(!$ssh2stream) die("_:Fail");
	if
	(
		!$ssh2stream->login($SSH_Data[$serverid][2], $SSH_Data[$serverid][3])
	) die("_:Fail");
	
	$ssh2stream->exec("cd " . $SSH_Data[$serverid][4] . " \n echo '".$data."' > samp.ban\n");
	sleep(2);
	$ret = $ssh2stream->exec("cd " . $SSH_Data[$serverid][4] . " \n cat samp.ban\n");
	$ssh2stream->disconnect();

	die($ret);
}

function ChangeVersion($data)
{
	global
			$Accounts,
			$Server_Infos,
			$SSH_Data,
			$_SESSION,
			$_GET,
			$serverid,
			$serverquery,
			$serverstatus,
			$ServerPlayers,
			$ServerInfo,
			$ServerHostname,
			$ServerPassworded,
			$ServerPlayer,
			$ServerMode,
			$ServerMap
	;


	$ssh2stream = new Net_SSH2($SSH_Data[$serverid][0], (int)$SSH_Data[$serverid][1]);
	if(!$ssh2stream) die("_:Fail");
	if
	(
		!$ssh2stream->login($SSH_Data[$serverid][2], $SSH_Data[$serverid][3])
	) die("_:Fail");
	
	$ret = $ssh2stream->exec
	(
		"cd " . $SSH_Data[$serverid][4] . " \n ".
		"mkdir -p tmppackages && mkdir -p tmppackages/{$data->PackageName} && cd tmppackages/{$data->PackageName}\n"
	);

	if(!empty($ret))
		die(json_encode(array("status"=>"Fail", "response"=>$ret)));

	$ret = $ssh2stream->exec
	(
		"cd " . $SSH_Data[$serverid][4] . "/tmppackages/{$data->PackageName}\n".
		"wget http://files.sa-mp.com/".$data->PackageName."\n"
	);

	$matches = array();

	if(preg_match_all("/(awaiting response... ([\d]{3}))/", $ret, $matches) == false)
	{
		die(json_encode(array("status"=>"Fail", "response"=>"Failed to download. (Reason: Unknown)")));
	}
	else
	{
		if((int)$matches[2][0] != 200)
		{
			die(json_encode(array("status"=>"Fail", "response"=>"Failed to download. (response code: {$matches[2][0]})")));
		}
		else
		{
			$ret = $ssh2stream->exec
			(
				"cd " . $SSH_Data[$serverid][4] . "/tmppackages/{$data->PackageName}\n".
				"tar -zxf {$data->PackageName}\n".
				"cd samp03\n".
				"mv announce samp-npc samp03svr {$SSH_Data[$serverid][4]}\n".
				( ($data->IGFP == "true") ? ("cp -R filterscripts/ gamemodes/ include/ npcmodes/ scriptfiles/ {$SSH_Data[$serverid][4]}\n") : ("")  )
			);

			if(!empty($ret))
			{
				die(json_encode(array("status"=>"Fail", "response"=>"Failed to update.")));
			}
			else
			{
				$ret = $ssh2stream->exec
				(
					"cd " . $SSH_Data[$serverid][4] . "/tmppackages/\n".
					"rm -R {$data->PackageName}\n"
				);
				die(json_encode(array("status"=>"Success", "response"=>"Update Completed!")));
			}
		}
	}
}

?>