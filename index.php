<?php
/**
 * @file	index.php 
 * @author	Dominik Ziegenhagel <domi@domisseite.de>
 * @version	2.0 COMIS ICECUBE	from 2014/11/06 -dz-
 * @date		2015-03-06
 *
 */

$COMIS = 42;
//define("LOC",file_get_contents("etc/url_to_comis"));
define("LOC","");
session_start();

include("lib/index_functions.php");
$index=new index();

$allowed_do=array("logout","login","unsubscribe","back_to_admin","autologin");
if(in_array(@$_GET["do"],$allowed_do)) {
	$index->do_what($_GET["do"]);
	return;
}
if(file_exists("etc/db.conf"))
  include('db.php');
else {
	$index->no_db();
	return;
}
if(isset($_SESSION["rename"])&&$_SESSION["rename"]===true)
	if(is_file("installation/index.php"))rename("installation/index.php","installation/reinstall.php");$_SESSION["rename"]=false;
if(file_exists("installation/index.php")) {
	$index->confirm_install();
	$continue=true;
}
else
	$continue=true;
if($continue) {
	include_once('lib/global_functions.php');
	include('includes/header.php');
	echo "\n<body>\n";
	if(is_sudo() || pref("maintenance")!="yes") {
		$addon=glob("addons/*.mainpage.php");if($addon){foreach($addon as $file)include$file;}
		require("bin/sethooks.php");
		if(is_file("templates/".pref('website_template')."/template.json")) {
			$template_json=file_get_contents("templates/".pref('website_template')."/template.json");
			$template_json=json_decode($template_json,true);
			require("templates/".pref('website_template')."/".$template_json["mainpage"]);
		}
	} else include("includes/maintenance.php");
	if(is_allowed("articles")) echo '<script type="text/javascript" src="lib/ckeditor/ckeditor.js"></script><link href="lib/ckeditor/contents.css" rel="stylesheet" type="text/css">';	
}
$db->close();
?>
