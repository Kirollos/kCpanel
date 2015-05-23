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

//$legalaccess = true;

require_once('config.php');

if(!isset($_SESSION['logged']) || !$_SESSION['logged'])
{
	header("Location: index.php?Page=Home");
	die;
}

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

if(isset($_POST['Action']) && $_POST['Action'] == 'ControlMySQL')
{
	if($_POST['Type'] == "Add")
	{
		$data = json_decode($_POST["data"]);
		$data->username = trim($data->username);

		$mysqlhandle = new mysqli($MySQLData->host, $MySQLData->user, $MySQLData->pass, $MySQLData->db, isset($MySQLData->port) ? $MySQLData->port : 3306);

		if($mysqlhandle->connect_error)
		{
			die(json_encode(array("status"=>"Fail", "response"=>"Failed to connect to database.")));
		}

		$query = $mysqlhandle->query("SELECT * FROM `Accounts` WHERE Username = '".$mysqlhandle->escape_string($data->username)."';");

		if($query->num_rows != 0)
		{
			$query->free();
			die(json_encode(array("status"=>"Fail", "response"=>"User '{$data->username}' already exists!")));
		}

		$query->free();

		$mysqlhandle->query("INSERT INTO `Accounts` VALUES ('".$mysqlhandle->escape_string($data->username)."', '".$mysqlhandle->escape_string($data->password)."', '". (string) $data->isadmin . "');");

		$query = $mysqlhandle->query("SELECT * FROM `Accounts` WHERE Username = '".$mysqlhandle->escape_string($data->username)."' AND Password = '".$mysqlhandle->escape_string($data->password)."';");

		if(!$query->num_rows)
		{
			$query->free();
			$mysqlhandle->close();
			die(json_encode(array("status"=>"Fail", "response"=>"Failed to Add User. (Reason: Num rows returned 0)")));
		}
		else
		{
			$query->free();
			$mysqlhandle->close();
			die(json_encode(array("status"=>"Success", "response"=>"User Added!")));
		}
	}
	else
	if($_POST['Type'] == "Del")
	{
		$data = json_decode($_POST["data"]);
		$data->username = trim($data->username);

		$mysqlhandle = new mysqli($MySQLData->host, $MySQLData->user, $MySQLData->pass, $MySQLData->db, isset($MySQLData->port) ? $MySQLData->port : 3306);

		if($mysqlhandle->connect_error)
		{
			die(json_encode(array("status"=>"Fail", "response"=>"Failed to connect to database.")));
		}

		$query = $mysqlhandle->query("SELECT * FROM `Accounts` WHERE Username = '".$mysqlhandle->escape_string($data->username)."';");

		if(!$query->num_rows)
		{
			$query->free();
			die(json_encode(array("status"=>"Fail", "response"=>"User '{$data->username}' not found!")));
		}

		$query->free();

		if($data->confirm == false)
		{
			die(json_encode(array("status"=>"Fail", "response"=>"You need to confirm the remove of user '{$data->username}'!")));
		}

		$mysqlhandle->query("DELETE FROM `Accounts` WHERE Username = '".$mysqlhandle->escape_string($data->username)."';");

		$query = $mysqlhandle->query("SELECT * FROM `Accounts` WHERE Username = '".$mysqlhandle->escape_string($data->username)."';");

		if($query->num_rows != 0)
		{
			$query->free();
			die(json_encode(array("status"=>"Fail", "response"=>"Failed to remove user '{$data->username}'!")));
		}

		$query->free();
		$mysqlhandle->close();
		die(json_encode(array("status"=>"Success", "response"=>"User '{$data->username}' deleted!")));

	}
	else
	if($_POST['Type'] == "Edit")
	{
		$data = json_decode($_POST["data"]);
		$data->username = trim($data->username);

		$mysqlhandle = new mysqli($MySQLData->host, $MySQLData->user, $MySQLData->pass, $MySQLData->db, isset($MySQLData->port) ? $MySQLData->port : 3306);

		if($mysqlhandle->connect_error)
		{
			die(json_encode(array("status"=>"Fail", "response"=>"Failed to connect to database.")));
		}

		$query = $mysqlhandle->query("SELECT * FROM `Accounts` WHERE Username = '".$mysqlhandle->escape_string($data->username)."';");

		if(!$query->num_rows)
		{
			$query->free();
			die(json_encode(array("status"=>"Fail", "response"=>"User '{$data->username}' not found!")));
		}

		$ledata = $query->fetch_array(MYSQLI_ASSOC);
		if($ledata == null)
		{
			$query->free();
			$mysqlhandle->close();
			die(json_encode(array("status"=>"Fail", "response"=>"Failed to retrieve data of user '{$data->username}'!")));
		}

		if($data->newpass != $data->retypepass)
		{
			$query->free();
			$mysqlhandle->close();
			die(json_encode(array("status"=>"Fail", "response"=>"Password and Retype do not match!")));
		}

		if($data->newpass == $ledata["Password"])
		{
			$query->free();
			$mysqlhandle->query("UPDATE `Accounts` SET `IsAdmin` = '". (string) $data->isadmin ."' WHERE Username = '".$mysqlhandle->escape_string($data->username)."';");
			$mysqlhandle->close();
			die(json_encode(array("status"=>"Success", "response"=>"Password unchanged.")));
		}
		else
		{
			$query->free();
			$mysqlhandle->query("UPDATE `Accounts` SET `Password` = '".$mysqlhandle->escape_string($data->newpass)."', `IsAdmin` = '". (string) $data->isadmin ."' WHERE Username = '".$mysqlhandle->escape_string($data->username)."';");
			$mysqlhandle->close();
			die(json_encode(array("status"=>"Success", "response"=>"User data changed!")));
		}
	}
	else
	if($_POST['Type'] == "Get")
	{
		$data = json_decode($_POST["data"]);
		$data->username = trim($data->username);

		$mysqlhandle = new mysqli($MySQLData->host, $MySQLData->user, $MySQLData->pass, $MySQLData->db, isset($MySQLData->port) ? $MySQLData->port : 3306);

		if($mysqlhandle->connect_error)
		{
			die(json_encode(array("status"=>"Fail", "response"=>"Failed to connect to database.")));
		}

		$query = $mysqlhandle->query("SELECT * FROM `Accounts` WHERE Username = '".$mysqlhandle->escape_string($data->username)."';");

		if($query->num_rows == 0)
		{
			$query->free();
			die(json_encode(array("status"=>"Fail", "response"=>"User '{$data->username}' not found!")));
		}
		else
		{
			$thedata = $query->fetch_array(MYSQLI_ASSOC);
			if($thedata != null)
			{
				die(json_encode(array("status"=>"Success", "isadmin" => $thedata["IsAdmin"])));
			}
		}

		$query->free();
	}

	die('_:Fail');
}

?>

<script>
	function AddUser()
	{
		// Test
		/*$('#VServerVersionNotice_DIV').prop('class', 'alert alert-danger');
		$("#VServerVersionNotice_DIV").fadeIn();
		$("#VServerVersionNotice").html("<strong>Oh Snap!</strong> No package was selected!");
		return;*/

		if($("#username_box").val() == '' || $("#password_box").val() == '')
		{
			$('#AddUserNotice_DIV').prop('class', 'alert alert-danger');
			$("#AddUserNotice_DIV").fadeIn();
			$("#AddUserNotice").html("<strong>Oh Snap!</strong> Username or Password is empty!");
			return;
		}

		$.post("accounts.php",
		{
			Action: "ControlMySQL",
			Type: "Add",
			data: JSON.stringify({username: $("#username_box").val(), password: $("#password_box").val(), isadmin: ($("#isadmin_box").prop('checked')|0).toString()})
		},
		function(data)
		{
			data = JSON.parse(data);
			//console.log(data)

			if(data.status == "Fail")
			{
				$('#AddUserNotice_DIV').prop('class', 'alert alert-danger');
				$("#AddUserNotice_DIV").fadeIn();
				$("#AddUserNotice").html("<strong>Oh Snap!</strong> Error: "+data.response);
				return;
			}
			else
			{
				$('#AddUserNotice_DIV').prop('class', 'alert alert-success');
				$("#AddUserNotice_DIV").fadeIn();
				$("#AddUserNotice").html("<strong>Awesome!</strong> "+data.response);
			}

		});
	}

	function DelUser()
	{
		// Test
		/*$('#VServerVersionNotice_DIV').prop('class', 'alert alert-danger');
		$("#VServerVersionNotice_DIV").fadeIn();
		$("#VServerVersionNotice").html("<strong>Oh Snap!</strong> No package was selected!");
		return;*/

		if($("#Username_SelectList").val() == null)
		{
			$('#DelUserNotice_DIV').prop('class', 'alert alert-danger');
			$("#DelUserNotice_DIV").fadeIn();
			$("#DelUserNotice").html("<strong>Oh Snap!</strong> Username not selected!");
			return;
		}

		$.post("accounts.php",
		{
			Action: "ControlMySQL",
			Type: "Del",
			data: JSON.stringify({username: $("#Username_SelectList").val(), confirm: ($("#confirm_box").prop('checked')|0).toString()})
		},
		function(data)
		{
			data = JSON.parse(data);
			//console.log(data)

			if(data.status == "Fail")
			{
				$('#DelUserNotice_DIV').prop('class', 'alert alert-danger');
				$("#DelUserNotice_DIV").fadeIn();
				$("#DelUserNotice").html("<strong>Oh Snap!</strong> Error: "+data.response);
				return;
			}
			else
			{
				$('#DelUserNotice_DIV').prop('class', 'alert alert-success');
				$("#DelUserNotice_DIV").fadeIn();
				$("#DelUserNotice").html("<strong>Awesome!</strong> "+data.response);
			}

		});
	}

	function EditUser()
	{
		// Test
		/*$('#VServerVersionNotice_DIV').prop('class', 'alert alert-danger');
		$("#VServerVersionNotice_DIV").fadeIn();
		$("#VServerVersionNotice").html("<strong>Oh Snap!</strong> No package was selected!");
		return;*/

		if($("#EUsername_SelectList").val() == null)
		{
			$('#EditUserNotice_DIV').prop('class', 'alert alert-danger');
			$("#EditUserNotice_DIV").fadeIn();
			$("#EditUserNotice").html("<strong>Oh Snap!</strong> Username not selected!");
			return;
		}

		if($("#Epassword_box").val().trim() == '' || $("#Eretypepassword_box").val().trim() == '')
		{
			$('#EditUserNotice_DIV').prop('class', 'alert alert-danger');
			$("#EditUserNotice_DIV").fadeIn();
			$("#EditUserNotice").html("<strong>Oh Snap!</strong> Password/Retype Password is empty!");
			return;
		}

		$.post("accounts.php",
		{
			Action: "ControlMySQL",
			Type: "Edit",
			data: JSON.stringify({username: $("#EUsername_SelectList").val(), newpass: $("#Epassword_box").val(), retypepass: $("#Eretypepassword_box").val(), isadmin: ($("#Eisadmin_box").prop('checked')|0).toString()})
		},
		function(data)
		{
			data = JSON.parse(data);
			//console.log(data)

			if(data.status == "Fail")
			{
				$('#EditUserNotice_DIV').prop('class', 'alert alert-danger');
				$("#EditUserNotice_DIV").fadeIn();
				$("#EditUserNotice").html("<strong>Oh Snap!</strong> Error: "+data.response);
				return;
			}
			else
			{
				$('#EditUserNotice_DIV').prop('class', 'alert alert-success');
				$("#EditUserNotice_DIV").fadeIn();
				$("#EditUserNotice").html("<strong>Awesome!</strong> "+data.response);
			}

		});
	}

	$(document).on("change", "#EUsername_SelectList", function() {

		$.post("accounts.php", 
		{
			Action: "ControlMySQL",
			Type: "Get",
			data: JSON.stringify({username: $("#EUsername_SelectList").val()})
		}, function(data) {
			data = JSON.parse(data);
			//console.log(data);

			if(data.status != "Fail")
			{
				$('#Epassword_box').val("");
				$('#Eretypepassword_box').val("");
				$('#Eisadmin_box').prop('checked', parseInt(data.isadmin));
			}
		});

		/*$('#Epassword_box').val("");
		$('#Eretypepassword_box').val("");
		$('#Eisadmin_box').prop('checked', false);*/
	});

	function ClearData(type)
	{
		switch(type)
		{
			case "Add":
				$("#username_box").val("");
				$("#password_box").val("");
				$("#isadmin_box").prop("checked", false);
			case "Del":
				$("#Username_SelectList").val("User List");
				$("#confirm_box").prop("checked", false);
			case "Edit":
				$("#EUsername_SelectList").val("User List");
				$("#Epassword_box").val("");
				$("#Eretypepassword_box").val("");
				$("#Eisadmin_box").prop("checked", false);
		}
	}
</script>

<!-- Modals -->
<div class="modal fade" id="AddUserModal" tabindex="-1" role="dialog" aria-labelledby="AddUser" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="AddUserModalLabelTITLE">Add User</h4>
      </div>
      <div class="modal-body" id="AddUserModalLabelBODY">
		<div id="AddUserNotice_DIV" class='alert ' style="display:none;">
      		<button type="button" onclick="$('#AddUserNotice_DIV').fadeOut(); $('#AddUserNotice_DIV').prop('class', 'alert ');" class="close alert"><span aria-hidden="true">&times;</span></button>
      		<span id="AddUserNotice" class="alert"></span>
      	</div>
            <table class="table table-stuff" style="text-align:center;">
              <tbody>
                <tr>
                  <td style="font-weight:bold;">Username</td>
                  <td><input type="text" id="username_box" maxlength="20" /></td>
                </tr>
                <tr>
                  <td style="font-weight:bold;">Password</td>
                  <td><input type="password" id="password_box" /></td>
                </tr>
                <tr>
                  <td style="font-weight:bold;">Admin</td>
                  <td><input type="checkbox" id="isadmin_box" /></td>
                </tr>
              </tbody>
            </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn-lrg" id="Button_AddUser" onclick="AddUser()">Add</button>
        <button type="button" class="btn btn-default btn-lrg" data-dismiss="modal" onclick="ClearData('Add');">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="DelUserModal" tabindex="-1" role="dialog" aria-labelledby="DelUser" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="DelUserModalLabelTITLE">Delete User</h4>
      </div>
      <div class="modal-body" id="DelUserModalLabelBODY">
      	<div id="DelUserNotice_DIV" class='alert ' style="display:none;">
      		<button type="button" onclick="$('#DelUserNotice_DIV').fadeOut(); $('#DelUserNotice_DIV').prop('class', 'alert ');" class="close alert"><span aria-hidden="true">&times;</span></button>
      		<span id="DelUserNotice" class="alert"></span>
      	</div>
  			<table class="table table-stuff" style="text-align:center;">
              <tbody>
                <tr>
                  <td style="font-weight:bold;">Username</td>
                  <!--<td><input type="text" id="username_box" maxlength="20" /></td>-->
                  <td>
                  	<select id="Username_SelectList">
                  		<option selected disabled>User List</option>
                  		<?php
                  			$mysqlhandle = new mysqli($MySQLData->host, $MySQLData->user, $MySQLData->pass, $MySQLData->db, isset($MySQLData->port) ? $MySQLData->port : 3306);

							if($mysqlhandle->connect_error)
							{
								echo "<option selected disabled>Unable to retrieve list...</options>";
								//$mysqlhandle->close();
							}
							else
							{
								$query = $mysqlhandle->query("SELECT * FROM `Accounts`;");
								if(!$query->num_rows)
								{
									echo "<option selected disabled>No users found...</options>";
									//$query->free();
									//$mysqlhandle->close();
								}
								else
								{
									while(($leAccounts = $query->fetch_array(MYSQLI_ASSOC)) != null)
									{
										echo "<option value='{$leAccounts['Username']}'>{$leAccounts['Username']}</option>";
									}
								}

								$query->free();
							}

							$mysqlhandle->close();
                  		?>
                  	</select>
                  </td>
                </tr>
                <tr>
                  <td style="font-weight:bold;">Confirm</td>
                  <td><input type="checkbox" id="confirm_box" /></td>
                </tr>
              </tbody>
            </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-lrg" id="Button_DelUser" onclick="DelUser()">Delete</button>
        <button type="button" class="btn btn-default btn-lrg" data-dismiss="modal" onclick="ClearData('Del');">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="EditUserModal" tabindex="-1" role="dialog" aria-labelledby="EditUser" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="EditUserModalLabelTITLE">Edit User</h4>
      </div>
      <div class="modal-body" id="EditUserModalLabelBODY">
      	<div id="EditUserNotice_DIV" class='alert ' style="display:none;">
      		<button type="button" onclick="$('#EditUserNotice_DIV').fadeOut(); $('#EditUserNotice_DIV').prop('class', 'alert ');" class="close alert"><span aria-hidden="true">&times;</span></button>
      		<span id="EditUserNotice" class="alert"></span>
      	</div>
      		<table class="table table-stuff" style="text-align:center;">
              <tbody>
                <tr>
                  <td style="font-weight:bold;">Username</td>
                  <!--<td><input type="text" id="username_box" maxlength="20" /></td>-->
                  <td>
                  	<select id="EUsername_SelectList">
                  		<option selected disabled>User List</option>
                  		<?php
                  			$mysqlhandle = new mysqli($MySQLData->host, $MySQLData->user, $MySQLData->pass, $MySQLData->db, isset($MySQLData->port) ? $MySQLData->port : 3306);

							if($mysqlhandle->connect_error)
							{
								echo "<option selected disabled>Unable to retrieve list...</options>";
								//$mysqlhandle->close();
							}
							else
							{
								$query = $mysqlhandle->query("SELECT * FROM `Accounts`;");
								if(!$query->num_rows)
								{
									echo "<option selected disabled>No users found...</options>";
									//$query->free();
									//$mysqlhandle->close();
								}
								else
								{
									while(($leAccounts = $query->fetch_array(MYSQLI_ASSOC)) != null)
									{
										echo "<option value='{$leAccounts['Username']}'>{$leAccounts['Username']}</option>";
									}
								}

								$query->free();
							}

							$mysqlhandle->close();
                  		?>
                  	</select>
                  </td>
                </tr>
                <tr>
                  <td style="font-weight:bold;">New Password</td>
                  <td><input type="password" id="Epassword_box" /></td>
                </tr>
                <tr>
                  <td style="font-weight:bold;">Retype Password</td>
                  <td><input type="password" id="Eretypepassword_box" /></td>
                </tr>
                <tr>
                  <td style="font-weight:bold;">Admin</td>
                  <td><input type="checkbox" id="Eisadmin_box" /></td>
                </tr>
              </tbody>
            </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn-lrg" id="Button_EditUser" onclick="EditUser()">Save</button>
        <button type="button" class="btn btn-default btn-lrg" data-dismiss="modal" onclick="ClearData('Edit');">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- End -->

<div class="panel panel-primary">
	<div class="panel-heading">
		User Management
	</div>
	<div class="panel-body">
		<table class="table table-stuff" style="text-align:center;">
			<tbody>
				
				<tr>
					
					<td><button type="button" class="btn btn-success btn-lg" id="" data-toggle="modal" data-target="#AddUserModal" onclick="" data-loading-text="Please Wait...">Add User</button></td>
					<td><button type="button" class="btn btn-danger btn-lg" id="" data-toggle="modal" data-target="#DelUserModal" onclick="" data-loading-text="Please Wait...">Del User</button></td>
					<td><button type="button" class="btn btn-warning btn-lg" id="" data-toggle="modal" data-target="#EditUserModal" onclick="" data-loading-text="Please Wait...">Edit User</button></td>
					
				</tr>
				
			</tbody>
		</table>
	</div>
</div>

<div class="panel panel-primary">
	<div class="panel-heading">
		Server Management
	</div>
	<div class="panel-body">
		<h1>Coming Soon</h1>
	</div>
</div>