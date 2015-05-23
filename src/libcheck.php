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
define("LIBS_PATH", getcwd()."/libs/"); // Full path of library (getcwd returns the directory of libcheck.php) [default]
set_include_path(ini_get('include_path'). ":".LIBS_PATH."/phpseclib/"); // phpseclib needs an include path (do not touch unless you know what you are doing)
//if(!extension_loaded('ssh2')) // php-ssh2 is not needed anymore
if(!file_exists('libs/phpseclib/'))
{
	die('<div>Error! You need <b><a href="http://phpseclib.sourceforge.net/">phpseclib</a></b> library!</div>');
}

require_once('Net/SSH2.php'); // phpseclib ssh2 lib

if(!file_exists('libs/SampQueryAPI.php'))
{
	die('
	<div>Error! <b><a href="http://forum.sa-mp.com/showthread.php?t=104299">SampQueryAPI.php</a></b> is missing!</div>
	');
}
if(!file_exists('libs/SampRconAPI.php'))
{
	die('
	<div>Error! <b><a href="http://forum.sa-mp.com/showthread.php?t=104299">SampRconAPI.php</a></b> is missing!</div>
	');
}

if($usingMySQL) {

if(!extension_loaded('mysqli'))
{
	die('<div>Error! You need PHP <b>mysqli</b> library!</div>');
}

}

require_once('libs/SampQueryAPI.php'); // Westie's SampQueryAPI	[http://forum.sa-mp.com/showthread.php?t=104299]
require_once('libs/SampRconAPI.php'); //  Westie's SampRconAPI	[http://forum.sa-mp.com/showthread.php?t=104299]
?>