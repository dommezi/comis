<?php
if(is_file("db.php"))$path="";elseif(is_file("../db.php"))$path="../";elseif(is_file("../../db.php"))$path="../../";
function addsyslog($msg,$xpath=null) {global $path;if($xpath)$path=$xpath;file_put_contents($path."log/system.log",$msg."\n".@file_get_contents($path."log/system.log"));}
require_once($path."lib/import_function.php");
if(isset($_POST["php"]))$PHPCODE=str_replace("'","&#039;",str_replace("<","&lt;",$_POST["php"]));
$pathventsqlinj=true;
if($pathventsqlinj) {
	function prevsqlinj (&$value, $key) {
		$value = trim(htmlspecialchars($value, ENT_QUOTES));
	}
	array_walk ($_GET, 'prevsqlinj');
	array_walk ($_POST, 'prevsqlinj');
}
if(isset($_GET["inc"]))$_GET["inc"]=str_replace("../","",$_GET["inc"]);
$db_conf=import_conf($path."etc/db.conf");
$db_username=$db_conf["db_username"];
$db_password=$db_conf["db_password"];
$db_host=$db_conf["db_host"];
$db_name=$db_conf["db_name"];
$db_prefix=$db_conf["db_prefix"]."_";
if(isset($db_conf["timezone"]))date_default_timezone_set($db_conf["timezone"]);
if(mysqli_connect($db_host,$db_username,$db_password,$db_name)) {
	$db=mysqli_connect($db_host,$db_username,$db_password,$db_name);
	addsyslog("Database connection established on ".date("M").", the ".date("d")." at ".date("H:i:s")." ...");
	if(file_exists($path."lib/global_functions.php"))
		require_once($path."lib/global_functions.php");
}
else {
	if(file_exists($path."installation/index.php"))
		die("<meta http-equiv=refresh content='0,".$path."installation/index.php'><script>window.location.href='".$path."installation/index.php';</script>");
	elseif(rename("installation/reinstall.php","installation/index.php")) {
		die("<body style=background:#457c9a><br><br><center><img src=".$path."images/logo_1000.png width=400 style=margin:100px><h1 style=color:white;font-family:arial>COULD NOT CONNECT TO DATABASE!<br><br> PLEASE CREATE A DATABASE CONNECTION OR <a href=".$path."installation/index.php style=color:white;font-weight:italic;text-decoration:none;color:orange>CLICK HERE</a> TO REINSTALL COMIS<div style=display:none; id=errors>");error(201502241345);
	}
}
$private=import_conf($path."etc/private.conf");$SYSID=$private["id"];
if(file_exists($path.'language/'.pref("language").'.php'))
	include($path.'language/'.pref("language").'.php');
elseif(file_exists('language/en.php'))
	include($path.'language/en.php');
?>