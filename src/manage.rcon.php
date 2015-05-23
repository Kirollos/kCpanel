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

if(!isset($_SESSION['logged']) || !$_SESSION['logged'])
{
	echo "<script>window.close();</script>";
	die;
}

if(!isset($_GET["svrid"]))
{
	echo "<script>window.close();</script>";
	die;
}

$isinaxx = false;

foreach($Server_Infos[$_GET["svrid"]][4] as $access)
{
	if($access == $_SESSION["Username"])
		{$isinaxx = true; break;}
	$isinaxx = false;
}

if(!$isinaxx)
{
	echo "<script>window.close();</script>";
	die;
}
?>

<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">

<link rel="stylesheet" type="text/css" href="libs/bootstrap/css/bootstrap.css">
<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.2.js"></script>
<script src="libs/jquery.terminal.js"></script>
<link rel="stylesheet" type="text/css" href="libs/jquery.terminal.css" />
<title>RCON Window - Server ID <?php echo $_GET["svrid"]; ?></title>
<style>
	
	#laTerminal
	{
		/*width:500px;*/
		width:auto;
		height:500px;
	}
	
</style>
<script>
$(document).ready(function(){
	var RCONListener = {
	    commands : {
	        'hello' : [
	            'Gives greetings',
	            '',
	            'Usage: \tbhello\tb [\tuwho\tu]',
	            '',
	            'If \tuwho\tu is not given, greets the world.'
	        ],
	        'help' : [ 'Show help.', '', 'Usage: \tbhelp\tb [\tucommand\tu]', '', 'If \tucommand\tu is not given,shows the available commands.', '', 'If \tucommand\tu is given,shows help of this command.' ],
	    },
	    complete: function(args) {

	    },
	    execute: function(args) {
	    	if(args[0] == 'exit')
	    	{
	    		setTimeout(function(){window.close();}, 1500);
	    		return 'Bye! \th<img width="100px" height="100px" src="ajax-loader-large.gif"></img>\th';
	    	} else
	    	{
				var mreturn = "";
				$.ajax({
					type: "POST",
					url: "server_control.php",
					data:
					{
						//Action:"rcon||"+args.join(' '),
						Action:JSON.stringify({action: "rcon", data:args.join(' ')}),
						ServerID:"<?php echo $_GET['svrid']; ?>",
						Confirm:"true"
					},
					success: function(data)
					{
						mreturn = data;
					},
					async:false
				});

				return mreturn.split('<br/>');
	    	}
	    }
	};
	//$("body").terminal({welcome:"Welcome to your server RCON console!\r\nServer ID: <?php echo $_GET['svrid']; ?>\r\nNote that you can not execute commands while server is offline!", listeners:[RCONListener], history:99999});
	$("#laTerminal").terminal({welcome:"Welcome to your server RCON console!\r\nServer ID: <?php echo $_GET['svrid']; ?>\r\nIP: <?php echo $Server_Infos[$_GET['svrid']][0] . ':' . $Server_Infos[$_GET['svrid']][1]; ?>\r\nNote that you can not execute commands while server is offline!", listeners:[RCONListener], history:350});
});
</script>

<body style="margin:0px;">
	<div id="laTerminal">
	</div>
</body>