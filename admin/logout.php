<?php
session_start();
Header("Content-Type: text/html; charset=UTF-8");
require_once "../include/config.php";
if (!defined('_web_path')) {
    exit;
}
if (isset($_SESSION["admin"]) && isset($_SESSION["userlevel"])) {
    include("../include/config_db.php");
    $admin = $_SESSION["admin"];
    $u_ip = $_SERVER["REMOTE_ADDR"];
    //$now = date("Y-m-d H:i:s",time());
    $query_m = "INSERT INTO ers_session (user_name, ip_address, log_time, log_status) VALUES ('$admin', '$u_ip', NOW(), 'o')";
    $result_d = $mysqli->query($query_m);
    include("../include/close_db.php");
}

unset($_SESSION["admin"]);
unset($_SESSION["userlevel"]);
unset($_SESSION["userid"]);
session_unset();
session_destroy();

//echo "<br><br><p align=\"center\"><font face=\"ms sans serif\" size=4 color=red><b>...ออกจากระบบ...</b></font></p>";	  

include("../include/close_db.php");
echo "<meta http-equiv=\"Refresh\" content=\"0; URL=login.php\" >";
?>