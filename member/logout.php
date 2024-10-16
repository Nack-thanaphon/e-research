<?php
session_start();
Header("Content-Type: text/html; charset=UTF-8");
require_once "../include/config.php";
if (!defined('_web_path')) {
	exit;
}
if ( isset($_SESSION["username"]) && isset($_SESSION["membername"]) && isset($_SESSION["memberid"]) )
{
	include("../include/config_db.php");
	$username = $_SESSION["username"];
	$u_ip = $_SERVER["REMOTE_ADDR"];
	//$now = date("Y-m-d H:i:s",time());
	$query_m = "insert into `ers_session`  (`id`,`user_name`,`ip_address`,`log_time`,`log_status`,`user_type`) values ('','$username','$u_ip',now(),'o','1') ";
	$result_d = $mysqli->query($query_m);
	include("../include/close_db.php");
}

unset($_SESSION["username"]);
unset($_SESSION["membername"]);
unset($_SESSION["memberid"]);
session_unset();session_destroy();

//echo "<br><br><p align=\"center\"><font face=\"ms sans serif\" size=4 color=red><b>...ออกจากระบบ...</b></font></p>";	  

include("../include/close_db.php");
echo "<meta http-equiv=\"Refresh\" content=\"0; URL=login.php\" >";
?>