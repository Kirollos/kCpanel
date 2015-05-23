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

require_once('config.php');

if(!isset($_SESSION['logged']) || !$_SESSION['logged'])
{
	header("Location: ?Page=Home");
	die;
}

if(!isset($_GET["svrid"]))
{
	header("Location: ?Page=Home");
	die;
}

// For some reason array_search doesn't work correctly, therefore I am using another method.

/*if(!array_search($_SESSION["Username"], $Server_Infos[$_GET["svrid"]][4])){
	header("Location: ?Page=Home");
	die;
}*/

$isinaxx = false;

foreach($Server_Infos[$_GET["svrid"]][4] as $access)
{
	if($access == $_SESSION["Username"])
		{$isinaxx = true; break;}
	$isinaxx = false;
}

if(!$isinaxx)
{
	header("Location: ?Page=Home");
	die;
}

$serverid = $_GET["svrid"];
/*
$serverquery = new SampQueryAPI($Server_Infos[$serverid][0], $Server_Infos[$serverid][1]);

$serverstatus = $serverquery->isOnline();

if($serverstatus)
{

	$ServerPlayers = $serverquery->getDetailedPlayers();
	$ServerInfo = $serverquery->getInfo();
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
$ServerMap = $ServerInfo == NULL ? "N/A" : $ServerInfo["mapname"];*/

?>

<script>
	var action = null;
	var ison = false;
	$(document).ready(function() {
		ison = false;
	refreshPage();
	$('[data-toggle="tooltip"]').tooltip();
	$('[data-toggle="popover"]').popover();
	});
	var pagelink = "fetch_info.php";
	function popoverlol(toggle) {
	if(toggle==true){
		if(ison == true) return;
		
		$.get(pagelink, {
		svrid:<?php echo '"'.$serverid.'"'; ?>,
		fetchinfo:"fetchplayers"
		},
		function(data) {
		//document.getElementById('players_popover').setAttribute("data-content", data);
		$('#players_popover').attr("data-content", data);
		$('#players_popover').popover('show');
		ison = true;
		});
		
		//document.getElementById('popover').setAttribute("data-content", '');
		//$('#players_popover').popover('show'); // ^
	}else
	if(toggle==false){$('#players_popover').popover('hide');ison = false;}
	}
	
	function DoAction()
	{
		if(action == "Start")
			StartServer();
		else
		if(action == "Stop")
			StopServer();
		else
		if(action == "GMX")
			GMXServer();
		else
		if(action == "Restart")
			RestartServer();
	}
	
	function StartServer()
	{
		$('#Button_ServerStart').button("loading");
		$.post("server_control.php",
		{
			Action:"Start_Server",
			ServerID:"<?php echo $serverid; ?>",
			Confirm:"true"
		},
		function(data)
		{
			/*if(data != "_:Fail")
			{
				//alert("Server started successfully!");
				//document.getElementById('leInfoModalLabelBODY').innerHTML = "Server started successfully!";
				$('#leInfoModalLabelBODY').html("Server started successfully!");
				$('#InfoModal').modal('show');
			}
			else
			{
				//alert("Some error occurred!");
				//document.getElementById('leInfoModalLabelBODY').innerHTML = "Some error occurred!";
				$('#leInfoModalLabelBODY').html("Some error occurred!");
				$('#InfoModal').modal('show');
			}
			$('#Button_ServerStart').button("reset");
			action = null;*/

			data = JSON.parse(data);
			if(data.status != "fail")
			{
				$('#leInfoModalLabelBODY').html(data.response);
				$('#InfoModal').modal('show');
			}
			else
			{
				$('#leInfoModalLabelBODY').html("Error: "+data.response);
				$('#InfoModal').modal('show');
			}
			$('#Button_ServerStart').button("reset");
			action = null;
		});
	}
	
	function StopServer()
	{
		$('#Button_ServerStop').button("loading");
		$.post("server_control.php",
		{
			Action:"Stop_Server",
			ServerID:"<?php echo $serverid; ?>",
			Confirm:"true"
		},
		function(data)
		{
			/*if(data != "_:Fail")
			{
				//alert("Server stopped successfully!");
				//document.getElementById('leInfoModalLabelBODY').innerHTML = "Server stopped successfully!";
				$('#leInfoModalLabelBODY').html("Server stopped successfully!");
				$('#InfoModal').modal('show');
			}
			else
			{
				//alert("Some error occurred!");
				//document.getElementById('leInfoModalLabelBODY').innerHTML = "Some error occurred!";
				$('#leInfoModalLabelBODY').html("Some error occurred!");
				$('#InfoModal').modal('show');
			}
			$('#Button_ServerStop').button("reset");
			action = null;*/

			data = JSON.parse(data);
			if(data.status != "fail")
			{
				$('#leInfoModalLabelBODY').html(data.response);
				$('#InfoModal').modal('show');
			}
			else
			{
				$('#leInfoModalLabelBODY').html("Error: "+data.response);
				$('#InfoModal').modal('show');
			}
			$('#Button_ServerStop').button("reset");
			action = null;
		});
	}
	
	function GMXServer()
	{
		$('#Button_ServerGMX').button("loading");
		$.post("server_control.php",
		{
			Action:"GMX_Server",
			ServerID:"<?php echo $serverid; ?>",
			Confirm:"true"
		},
		function(data)
		{
			/*if(data != "_:Fail")
			{
				//alert("Server GMX successfully!");
				//document.getElementById('leInfoModalLabelBODY').innerHTML = "Server GMXed successfully!";
				$('#leInfoModalLabelBODY').html("Server GMXed successfully!");
				$('#InfoModal').modal('show');
			}
			else
			{
				//alert("Some error occurred!");
				//document.getElementById('leInfoModalLabelBODY').innerHTML = "Some error occurred!";
				$('#leInfoModalLabelBODY').html("Some error occurred!");
				$('#InfoModal').modal('show');
			}
			$('#Button_ServerGMX').button("reset");
			action = null;*/

			data = JSON.parse(data);
			if(data.status != "fail")
			{
				$('#leInfoModalLabelBODY').html(data.response);
				$('#InfoModal').modal('show');
			}
			else
			{
				$('#leInfoModalLabelBODY').html("Error: "+data.response);
				$('#InfoModal').modal('show');
			}
			$('#Button_ServerGMX').button("reset");
			action = null;
		});
	}
	
	function RestartServer()
	{
		$('#Button_ServerRestart').button("loading");
		$.post("server_control.php",
		{
			Action:"Restart_Server",
			ServerID:"<?php echo $serverid; ?>",
			Confirm:"true"
		},
		function(data)
		{
			/*if(data != "_:Fail")
			{
				//alert("Server restarted successfully!");
				//document.getElementById('leInfoModalLabelBODY').innerHTML = "Server restarted successfully!";
				$('#leInfoModalLabelBODY').html("Server restarted successfully!");
				$('#InfoModal').modal('show');
			}
			else
			{
				//alert("Some error occurred!");
				//document.getElementById('leInfoModalLabelBODY').innerHTML = "Some error occurred!";
				$('#leInfoModalLabelBODY').html("Some error occurred!");
				$('#InfoModal').modal('show');
			}
			$('#Button_ServerRestart').button("reset");
			action = null;*/

			data = JSON.parse(data);
			if(data.status != "fail")
			{
				$('#leInfoModalLabelBODY').html(data.response);
				$('#InfoModal').modal('show');
			}
			else
			{
				$('#leInfoModalLabelBODY').html("Error: "+data.response);
				$('#InfoModal').modal('show');
			}
			$('#Button_ServerRestart').button("reset");
			action = null;
		});
	}

	function GetServerCFG()
	{
		$.post("server_control.php",
		{
			Action:"GetServerCFG",
			ServerID:"<?php echo $serverid; ?>",
			Confirm:"true"
		},
		function(data)
		{
			if(data != "_:Fail")
			{
				/*document.getElementById('VEServerCFGNotice').innerHTML='';
				document.getElementById('VEServerCFGVALUE').value = data;
				document.getElementById('VEServerCFGVALUE').disabled = false;
				document.getElementById('VEServerCFGSave_Button').disabled = false;*/

				$('#VEServerCFGNotice').html("");
				$('#VEServerCFGVALUE').val(data);
				$('#VEServerCFGVALUE').attr('disabled', false);
				$('#VEServerCFGSave_Button').attr('disabled', false);
			}
			else
			{
				/*document.getElementById('VEServerCFGNotice').innerHTML = "An Error has occurred while loading server.cfg.";
				document.getElementById('VEServerCFGVALUE').disabled = true;*/

				$('#VEServerCFGNotice').html("An Error has occurred while loading server.cfg.");
				$('#VEServerCFGVALUE').attr('disabled', true);
			}
		});
	}

	function SaveCFG(confirm)
	{
		if(!confirm)
			return false;

		$.post("server_control.php",
		{
			//Action:"SaveCFG||"+$("#VEServerCFGVALUE").val()/*document.getElementById("VEServerCFGVALUE").value*/,
			Action:JSON.stringify({action:"SaveCFG", data:$("#VEServerCFGVALUE").val()}),
			ServerID:"<?php echo $serverid; ?>",
			Confirm:"true"
		},
		function(data)
		{
			if(data != "_:Fail")
			{
				//if(data.trim() != document.getElementById("VEServerCFGVALUE").value.trim())
				if(data.trim() != $("#VEServerCFGVALUE").val().trim())
				{
					// document.getElementById('VEServerCFGNotice').innerHTML = "An Error has occurred while saving server.cfg.";
					// document.getElementById('VEServerCFGVALUE').disabled = true;
					$('#VEServerCFGNotice').html("An Error has occurred while saving server.cfg.");
					$('#VEServerCFGVALUE').attr('disabled', true);
				}
				else
				{
					// document.getElementById('VEServerCFGVALUE').disabled = true;
					// document.getElementById('VEServerCFGSave_Button').disabled = true;
					// document.getElementById('VEServerCFGNotice').innerHTML="Saved. This page will be closed in 2 seconds.";

					$('#VEServerCFGVALUE').attr('disabled', true);
					$('#VEServerCFGSave_Button').attr('disabled', true);
					$('#VEServerCFGNotice').html("Saved. This page will be closed in 2 seconds.");

					setTimeout(function()
					{
						$("#VEServerCFGModal").modal('hide');
						// document.getElementById('VEServerCFGNotice').innerHTML="";
						// document.getElementById('VEServerCFGVALUE').value="";

						$('#VEServerCFGNotice').html("");
						$('#VEServerCFGVALUE').val("");
					}, 2000);
				}
			}
			else
			{
				// document.getElementById('VEServerCFGNotice').innerHTML = "An Error has occurred while saving server.cfg.";
				// document.getElementById('VEServerCFGVALUE').disabled = true;

				$('#VEServerCFGNotice').html("An Error has occurred while saving server.cfg.");
				$('#VEServerCFGVALUE').attr('disabled', true);
			}
		});
	}

	function GetServerLog()
	{
		$.post("server_control.php",
		{
			Action:"GetServerLog",
			ServerID:"<?php echo $serverid; ?>",
			Confirm:"true"
		},
		function(data)
		{
			if(data != "_:Fail")
			{
				/*document.getElementById('VEServerCFGNotice').innerHTML='';
				document.getElementById('VEServerCFGVALUE').value = data;
				document.getElementById('VEServerCFGVALUE').disabled = false;
				document.getElementById('VEServerCFGSave_Button').disabled = false;*/

				$('#VServerLogNotice').html("Loaded the latest <?php echo $max_svrlog_lines; ?> lines from server_log.txt");
				$('#VServerLogVALUE').val(data);
				$('#VServerLogVALUE').attr('disabled', false);
				$('#VServerLogLoad_Button').attr('disabled', false);
				$('#VServerLogLoad_Button').html('Reload'); // Useless shit but lel
			}
			else
			{
				/*document.getElementById('VEServerCFGNotice').innerHTML = "An Error has occurred while loading server.cfg.";
				document.getElementById('VEServerCFGVALUE').disabled = true;*/

				$('#VServerLogNotice').html("An Error has occurred while loading server_log.txt.");
				$('#VServerLogVALUE').attr('disabled', true);
			}
		});
	}

	function GetServerBAN()
	{
		$.post("server_control.php",
		{
			Action:"GetServerBAN",
			ServerID:"<?php echo $serverid; ?>",
			Confirm:"true"
		},
		function(data)
		{
			if(data != "_:Fail")
			{
				/*document.getElementById('VEServerCFGNotice').innerHTML='';
				document.getElementById('VEServerCFGVALUE').value = data;
				document.getElementById('VEServerCFGVALUE').disabled = false;
				document.getElementById('VEServerCFGSave_Button').disabled = false;*/

				$('#VEServerBANNotice').html("");
				$('#VEServerBANVALUE').val(data);
				$('#VEServerBANVALUE').attr('disabled', false);
				$('#VEServerBANSave_Button').attr('disabled', false);
			}
			else
			{
				/*document.getElementById('VEServerCFGNotice').innerHTML = "An Error has occurred while loading server.cfg.";
				document.getElementById('VEServerCFGVALUE').disabled = true;*/

				$('#VEServerBANNotice').html("An Error has occurred while loading samp.ban.");
				$('#VEServerBANVALUE').attr('disabled', true);
			}
		});
	}

	function SaveBAN(confirm)
	{
		if(!confirm)
			return false;

		$.post("server_control.php",
		{
			//Action:"SaveBAN||"+$("#VEServerBANVALUE").val()/*document.getElementById("VEServerCFGVALUE").value*/,
			Action:JSON.stringify({action:"SaveBAN", data:$("#VEServerBANVALUE").val()}),
			ServerID:"<?php echo $serverid; ?>",
			Confirm:"true"
		},
		function(data)
		{
			if(data != "_:Fail")
			{
				//if(data.trim() != document.getElementById("VEServerCFGVALUE").value.trim())
				if(data.trim() != $("#VEServerBANVALUE").val().trim())
				{
					// document.getElementById('VEServerCFGNotice').innerHTML = "An Error has occurred while saving server.cfg.";
					// document.getElementById('VEServerCFGVALUE').disabled = true;
					$('#VEServerBANNotice').html("An Error has occurred while saving samp.ban.");
					$('#VEServerBANVALUE').attr('disabled', true);
				}
				else
				{
					// document.getElementById('VEServerCFGVALUE').disabled = true;
					// document.getElementById('VEServerCFGSave_Button').disabled = true;
					// document.getElementById('VEServerCFGNotice').innerHTML="Saved. This page will be closed in 2 seconds.";

					$('#VEServerBANVALUE').attr('disabled', false);
					$('#VEServerBANSave_Button').attr('disabled', false);
					//$('#VEServerBANNotice').html("Saved. This page will be closed in 2 seconds.");
					$('#VEServerBANNotice').html("Saved.");

					setTimeout(function()
					{
						//$("#VEServerBANModal").modal('hide');
						// document.getElementById('VEServerCFGNotice').innerHTML="";
						// document.getElementById('VEServerCFGVALUE').value="";

						$('#VEServerBANNotice').html("");
						//$('#VEServerBANVALUE').val("");
					}, 2000);
				}
			}
			else
			{
				// document.getElementById('VEServerCFGNotice').innerHTML = "An Error has occurred while saving server.cfg.";
				// document.getElementById('VEServerCFGVALUE').disabled = true;

				$('#VEServerBANNotice').html("An Error has occurred while saving samp.ban.");
				$('#VEServerBANVALUE').attr('disabled', true);
			}
		});
	}

	function ChangeServerVersion()
	{
		if($('#VServerVersion_Select_List').val() == null)
		{
			$('#VServerVersionNotice_DIV').prop('class', 'alert alert-danger');
			$("#VServerVersionNotice_DIV").fadeIn();
			$("#VServerVersionNotice").html("<strong>Oh Snap!</strong> No package was selected!");
			return;
		}
		if($('#ServerHostname').html() != "N/A")
		{
			$('#VServerVersionNotice_DIV').prop('class', 'alert alert-danger');
			$("#VServerVersionNotice_DIV").fadeIn();
			$("#VServerVersionNotice").html("<strong>Oh Snap!</strong> I suggest shutting down the server first!");
			return;
		}

		$.post("server_control.php",
		{
			//Action:"ChangeVersion|" + JSON.stringify({PackageName: $('#VServerVersion_Select_List').val(), IGFP: $('#VServerVersion_CheckBox_IGFP').prop('checked')}),
			Action:JSON.stringify({action:"ChangeVersion", data:JSON.stringify({PackageName: $('#VServerVersion_Select_List').val(), IGFP: $('#VServerVersion_CheckBox_IGFP').prop('checked')})}),
			ServerID:"<?php echo $serverid; ?>",
			Confirm:"true"
		},
		function(data)
		{
			/*$("[class='alert alert-danger']").fadeIn();
			$("#VServerVersionNotice").html("<strong>Oh Snap!</strong> Response: "+data);
			return;*/

			data = JSON.parse(data);

			if(data.status == "Fail")
			{
				if(!data.response.length)
				{
					$('#VServerVersionNotice_DIV').prop('class', 'alert alert-danger');
					$("#VServerVersionNotice_DIV").fadeIn();
					$("#VServerVersionNotice").html("<strong>Oh Snap!</strong> An Unknown error had occurred!");
					return;
				}
				else
				{
					$('#VServerVersionNotice_DIV').prop('class', 'alert alert-danger');
					$("#VServerVersionNotice_DIV").fadeIn();
					$("#VServerVersionNotice").html("<strong>Oh Snap!</strong> An error has occurred!<br />Response:"+data.response);
					return;
				}
			}
			else
			{
				$('#VServerVersionNotice_DIV').prop('class', 'alert alert-success');
				$("#VServerVersionNotice_DIV").fadeIn();
				$("#VServerVersionNotice").html("<strong>Awesome!</strong> Update completed!");
			}
		});
	}
	
	function refreshPage()
	{
		//$('#ServerHostname').fadeOut();
		//$('#ServerPassworded').fadeOut();
		//$('#ServerPlayers').fadeOut();
		//$('#ServerMode').fadeOut();
		//$('#ServerMap').fadeOut();
		//var pagelink = "fetch_info.php?svrid=" + <?php echo '"'.$serverid.'"'; ?> + "&fetchinfo=";
		//var pagelink = "fetch_info.php"; // Above ^
		/*$('#ServerHostname').load(pagelink+"serverhostname");
		$('#ServerPassworded').load(pagelink+"serverpassworded");
		$('#ServerPlayers').load(pagelink+"serverplayer");
		$('#ServerMode').load(pagelink+"servermode");
		$('#ServerMap').load(pagelink+"servermap");*/
		//$('#ServerHostname').fadeIn();
		
		$.get(pagelink, {
		svrid:<?php echo '"'.$serverid.'"'; ?>,
		fetchinfo:"all"
		},
		function(data) {
		var leData = data.split('|');
		/*var ServerHostname;
		var ServerPassworded;
		var ServerPlayer;
		var ServerMode;
		var ServerMap;
		
		ServerHostname = document.getElementById('ServerHostname');
		ServerPassworded = document.getElementById('ServerPassworded');
		ServerPlayer = document.getElementById('players_popover');
		ServerMode = document.getElementById('ServerMode');
		ServerMap = document.getElementById('ServerMap');*/
		
		$('#ServerHostname').html(leData[1]);
		$('#ServerPassworded').html(leData[2]);
		//ServerPlayer.innerHTML = '<span id="players_popover" data-container="body" data-toggle="popover" data-html="true" data-placement="bottom" data-content="" onmouseover="popoverlol(true)" ondblclick="popoverlol(false)">' + leData[3] + '</span>';
		$('#players_popover').html(leData[3]);
		$('#ServerMode').html(leData[4]);
		$('#ServerMap').html(leData[5]);
		$('#ServerLanguage').html(leData[6]);
		$('#ServerVersion').html(leData[7]);
		
		
		if(action != null)
		{
			/*document.getElementById('Button_ServerStart').disabled = 'true';
			document.getElementById('Button_ServerStop').disabled = 'true';
			document.getElementById('Button_ServerGMX').disabled = 'true';
			document.getElementById('Button_ServerRestart').disabled = 'true';*/
			$('#Button_ServerStart').attr('disabled', true);
			$('#Button_ServerStop').attr('disabled', true);
			$('#Button_ServerGMX').attr('disabled', true);
			$('#Button_ServerRestart').attr('disabled', true);
		}
		else {
		if(leData[0] == 0)
		{
			/*document.getElementById('Button_ServerStart').removeAttribute("disabled");
			document.getElementById('Button_ServerStop').disabled = 'true';
			document.getElementById('Button_ServerGMX').disabled = 'true';
			document.getElementById('Button_ServerRestart').disabled = 'true';*/
			$('#Button_ServerStart').removeAttr('disabled');
			$('#Button_ServerStop').attr('disabled', true);
			$('#Button_ServerGMX').attr('disabled', true);
			$('#Button_ServerReStart').attr('disabled', true);
		}
		else
		{
			/*document.getElementById('Button_ServerStart').disabled = 'true';
			document.getElementById('Button_ServerStop').removeAttribute("disabled");
			document.getElementById('Button_ServerGMX').removeAttribute("disabled");
			document.getElementById('Button_ServerRestart').removeAttribute("disabled");*/
			$('#Button_ServerStart').attr('disabled', true);
			$('#Button_ServerStop').removeAttr('disabled');
			$('#Button_ServerGMX').removeAttr('disabled');
			$('#Button_ServerRestart').removeAttr('disabled');
		}
		}
		});
		
		setTimeout(refreshPage, 500);
	}

	$(document).on('click', '#players_popover', function(){popoverlol(true);});
	$(document).on('dblclick', '#players_popover', function(){popoverlol(false);});
	$(document).on('hide.bs.modal', "#VEServerCFGModal", function(e) {
		$('#VEServerCFGVALUE').attr('disabled', true);
		$('#VEServerCFGVALUE').val('');
		$('#VEServerCFGNotice').html('');
	});
	$(document).on('hide.bs.modal', "#VServerLogModal", function(e) {
		$('#VServerLogVALUE').attr('disabled', true);
		$('#VServerLogVALUE').val('');
		$('#VServerLogNotice').html('');
	});
</script>
<!-- Modals -->
<div class="modal fade" id="ConfirmModal" tabindex="-1" role="dialog" aria-labelledby="Confirm" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="leConfirmModalLabelTITLE">Confirm</h4>
      </div>
      <div class="modal-body" id="leConfirmModalLabelBODY">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn-lrg" data-dismiss="modal" onclick="DoAction()">Yes</button>
        <button type="button" class="btn btn-default btn-lrg" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="InfoModal" tabindex="-1" role="dialog" aria-labelledby="leInfo" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="leInfoModalLabelTITLE">Information Box</h4>
      </div>
      <div class="modal-body" id="leInfoModalLabelBODY">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-lrg" data-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="VEServerCFGModal" tabindex="-1" role="dialog" aria-labelledby="VEServerCFG" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="VEServerCFGModalLabelTITLE">View/Edit server.cfg</h4>
      </div>
      <div class="modal-body" id="VEServerCFGModalLabelBODY" style="width:auto;">
      	<span id="VEServerCFGNotice"></span>
      	<textarea id="VEServerCFGVALUE" style="width: 558px; height: 375px;"></textarea>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-success btn-lrg" id="VEServerCFGSave_Button" onclick="document.getElementById('VEServerCFGVALUE').disabled = true; document.getElementById('VEServerCFGNotice').innerHTML='Saving...'; SaveCFG(true);">Save</button>-->
        <button type="button" class="btn btn-success btn-lrg" id="VEServerCFGSave_Button" onclick="$('#VEServerCFGVALUE').attr('disabled', true); $('#VEServerCFGSave_Button').attr('disabled', true); $('#VEServerCFGNotice').html('Saving...'); SaveCFG(true);">Save</button>
        <button type="button" class="btn btn-default btn-lrg" data-dismiss="modal" onclick="$('#VEServerCFGVALUE').attr('disabled', true); $('#VEServerCFGVALUE').val(''); $('#VEServerCFGNotice').html('');">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="VServerLogModal" tabindex="-1" role="dialog" aria-labelledby="VServerLog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="VServerLogModalLabelTITLE">View server_log.txt</h4>
      </div>
      <div class="modal-body" id="VServerLogModalLabelBODY" style="width:auto;">
      	<span id="VServerLogNotice"></span>
      	<textarea id="VServerLogVALUE" style="width: 558px; height: 375px;" readonly="true"></textarea>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-success btn-lrg" id="VEServerCFGSave_Button" onclick="document.getElementById('VEServerCFGVALUE').disabled = true; document.getElementById('VEServerCFGNotice').innerHTML='Saving...'; SaveCFG(true);">Save</button>-->
        <button type="button" class="btn btn-success btn-lrg" id="VServerLogLoad_Button" onclick="$('#VServerLogVALUE').attr('disabled', true); $('#VServerLogLoad_Button').attr('disabled', true); $('#VServerLogVALUE').val(''); $('#VServerLogNotice').html('Loading...'); GetServerLog();">Load</button>
        <button type="button" class="btn btn-default btn-lrg" data-dismiss="modal" onclick="$('#VServerLogVALUE').attr('disabled', true); $('#VServerLogVALUE').val(''); $('#VServerLogNotice').html('');">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="VEServerBANModal" tabindex="-1" role="dialog" aria-labelledby="VEServerBAN" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="VEServerBANModalLabelTITLE">View/Edit samp.ban</h4>
      </div>
      <div class="modal-body" id="VEServerBANModalLabelBODY" style="width:auto;">
      	<span id="VEServerBANNotice"></span>
      	<textarea id="VEServerBANVALUE" style="width: 558px; height: 375px;"></textarea>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-success btn-lrg" id="VEServerCFGSave_Button" onclick="document.getElementById('VEServerCFGVALUE').disabled = true; document.getElementById('VEServerCFGNotice').innerHTML='Saving...'; SaveCFG(true);">Save</button>-->
        <button type="button" class="btn btn-success btn-lrg" id="VEServerBANSave_Button" onclick="$('#VEServerBANVALUE').attr('disabled', true); $('#VEServerBANSave_Button').attr('disabled', true); $('#VEServerBANNotice').html('Saving...'); SaveBAN(true);">Save</button>
        <button type="button" class="btn btn-default btn-lrg" data-dismiss="modal" onclick="$('#VEServerBANVALUE').attr('disabled', true); $('#VEServerBANVALUE').val(''); $('#VEServerBANNotice').html('');">Close</button>
        <button type="button" class="btn btn-default btn-lrg" onclick="$('#VEServerBANNotice').html('Executing \'rcon reloadbans\'...');  $.post('server_control.php', {Action:'rcon||reloadbans',ServerID:'<?php echo $_GET['svrid']; ?>',Confirm:'true'}, function(data) { $('#VEServerBANNotice').html('\'rcon reloadbans\' executed...'); });">RCON reloadbans</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="VServerVersionModal" tabindex="-1" role="dialog" aria-labelledby="VServerVersion" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="VServerVersionModalLabelTITLE">Update/Downgrade Server Version</h4>
      </div>
      <div class="modal-body" id="VServerVersionModalLabelBODY" style="width:auto;">
      	<div id="VServerVersionNotice_DIV" class='alert ' style="display:none;">
      		<button type="button" onclick="$('#VServerVersionNotice_DIV').fadeOut(); $('#VServerVersionNotice_DIV').prop('class', 'alert ');" class="close alert"><span aria-hidden="true">&times;</span></button>
      		<span id="VServerVersionNotice" class="alert"></span>
      	</div>
      	<table class="table table-stuff" style="text-align:center;">
      		<tbody>
      			<tr>
      				<td>Server Version<span style="color:red;">*</span></td>
      				<td>
				      	<select id="VServerVersion_Select_List">
				      		<option selected disabled>SA-MP Available Versions</option>
				      		<?php
				      			$raw = file_get_contents("http://files.sa-mp.com/");

				      			$matches = array();

				      			if(preg_match_all("/\x3esamp[0-9]+[a-zA-Z0-9]svr(_|)[R C 0-9 -]+\.tar\.gz\x3c/", $raw, $matches))
				      			{
				      				foreach($matches[0] as $v)
				      				{
				      					$v = str_replace("<", "", $v);
				      					$v = str_replace(">", "", $v);

				      					echo "<option value='{$v}'>{$v}</option>";
				      				}
				      			}
				      		?>
				      	</select>
				    </td>
				</tr>
				<tr>
					<td>Include the gamemodes/filterscripts in the package</td>
					<td><input type="checkbox" id="VServerVersion_CheckBox_IGFP"></input></td>
				</tr>
			</tbody>
		</table>
		<span style="color:red">* This list is automatically fetched from <a href="http://files.sa-mp.com/" target="_blank">official sa-mp mirror</a>.</span>
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-success btn-lrg" id="Button_VServerVersionProceed" onclick="ChangeServerVersion();">Proceed</button>
        <button type="button" class="btn btn-default btn-lrg" data-dismiss="modal" onclick="">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modals/end -->

<div class="panel panel-primary">
	<div class="panel-heading">Server Information</div>

	<div class="panel-body">

		<table class="table table-stuff" style="text-align:center;">
		<tbody>
			<tr>
				<td>Title</td>
				<td>Info</td>
			</tr>
			<tr>
				<td><span class="glyphicon glyphicon-header"></span> Hostname</td>
				<td id="ServerHostname">Loading...</td>
			</tr>
			<tr>
				<td><span class="glyphicon glyphicon-lock"></span> Passworded</td>
				<td id="ServerPassworded">Loading...</td>
			</tr>
			<tr>
				<td><span class="glyphicon glyphicon-user"></span> Players</td>
				<!--<td id="ServerPlayers"><span id="players_popover" data-container="body" data-toggle="popover" data-html="true" data-placement="bottom" data-content="" onmouseover="popoverlol(true)" ondblclick="popoverlol(false)">Loading...</span></td>-->
				<td id="ServerPlayers"><span data-toggle="tooltip" data-placement="left" title="Click to show/hide player list"><span id="players_popover" data-container="body" data-toggle="popover" data-html="true" data-placement="bottom" data-content="">Loading...</span></span></td>
			</tr>
			<tr>
				<td><span class="glyphicon glyphicon-home"></span> Mode</td>
				<td id="ServerMode">Loading...</td>
			</tr>
			<tr>
				<td><span class="glyphicon glyphicon-map-marker"></span> Map</td>
				<td id="ServerMap">Loading...</td>
			</tr>
			<tr>
				<td><span class="glyphicon glyphicon-phone-alt"></span> Language</td>
				<td id="ServerLanguage">Loading...</td>
			</tr>
			<tr>
				<td><span class="glyphicon glyphicon-qrcode"></span> Version</td>
				<td id="ServerVersion">Loading...</td>
			</tr>
		</tbody>
		</table>
	</div>
</div>

<div class="panel panel-primary">
	
	<div class="panel-heading">Fetch Information</div>
	
	<div class="panel-body">
		
		<table class="table table-stuff" style="text-align:center;">
			<tbody>
				
				<tr>
					
					<!--<td><button type="button" class="btn btn-success btn-lg" id="Button_VEServerCFG" data-toggle="modal" data-target="#VEServerCFGModal" onclick="document.getElementById('VEServerCFGVALUE').disabled = true; document.getElementById('VEServerCFGNotice').innerHTML='Loading....'; document.getElementById('VEServerCFGSave_Button').disabled=true; GetServerCFG();" data-loading-text="Please Wait...">View/Edit server.cfg</button></td>-->
					<td><button type="button" class="btn btn-success btn-lg" id="Button_VEServerCFG" data-toggle="modal" data-target="#VEServerCFGModal" onclick="$('#VEServerCFGVALUE').attr('disabled', true); $('#VEServerCFGNotice').html('Loading....'); $('#VEServerCFGSave_Button').attr('disabled', true); GetServerCFG();" data-loading-text="Please Wait...">View/Edit server.cfg</button></td>
					<td><button type="button" class="btn btn-success btn-lg" id="Button_VServerLog" data-toggle="modal" data-target="#VServerLogModal" onclick="$('#VServerLogVALUE').attr('disabled', true); $('#VServerLogLoad_Button').attr('disabled', false);" data-loading-text="Please Wait...">View server_log.txt</button></td>
					<td><button type="button" class="btn btn-success btn-lg" onclick="window.open('manage.rcon.php?svrid=<?php echo $serverid; ?>', '', 'width=500, height=500');">RCON Console</button></td>
					
				</tr>

				<tr>

					<td><button type="button" class="btn btn-success btn-lg" id="Button_VEServerBAN" data-toggle="modal" data-target="#VEServerBANModal" onclick="$('#VEServerBANVALUE').attr('disabled', true); $('#VEServerBANNotice').html('Loading....'); $('#VEServerBANSave_Button').attr('disabled', true); GetServerBAN();" data-loading-text="Please Wait...">View/Edit samp.ban</button></td>
					<td><button type="button" class="btn btn-success btn-lg" id="Button_VServerVersion" data-toggle="modal" data-target="#VServerVersionModal" onclick="" data-loading-text="Please Wait...">Update/Downgrade Server Version</button></td>

				</tr>
				
			</tbody>
		</table>
		
	</div>
	
</div>

<div class="panel panel-primary">
	
	<div class="panel-heading">Server Control</div>
	
	<div class="panel-body">
	
		<table class="table table-stuff" style="text-align:center;">
			<tbody>
				
				<tr>
					<td><button type="button" class="btn btn-success btn-lg" id="Button_ServerStart" data-toggle="modal" data-target="#ConfirmModal" onclick="action='Start',$('#leConfirmModalLabelBODY').html('Are you sure you want to start the server?');" data-loading-text="Please Wait...">Server Start</button></td>
					<td><button type="button" class="btn btn-danger btn-lg" id="Button_ServerStop" data-toggle="modal" data-target="#ConfirmModal" onclick="action='Stop',$('#leConfirmModalLabelBODY').html('Are you sure you want to stop the server?')" data-loading-text="Please Wait...">Server Stop</button></td>
					<td><button type="button" class="btn btn-info btn-lg" id="Button_ServerGMX" data-toggle="modal" data-target="#ConfirmModal" onclick="action='GMX',$('#leConfirmModalLabelBODY').html('Are you sure you want to GMX the server?')" data-loading-text="Please Wait...">Server GMX</button></td>
					<td><button type="button" class="btn btn-primary btn-lg" id="Button_ServerRestart" data-toggle="modal" data-target="#ConfirmModal" onclick="action='Restart',$('#leConfirmModalLabelBODY').html('Are you sure you want to restart the server?')" data-loading-text="Please Wait...">Server Restart</button></td>
				</tr>
			</tbody>
		</table>
		
	</div>
	
</div>