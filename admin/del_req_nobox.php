<?
session_start();
Header("Content-Type: text/html; charset=utf-8");
if ( !isset($_SESSION["admin"]) || !isset($_SESSION["userlevel"]) || ($_SESSION["admin"]=="") ){
  echo "<Script language=\"javascript\">window.location=\"login.php\"</script>";
} else {
	require_once('../include/config_db.php');
	$p_path = $_GET['c_file_path'];
	$p_path = trim($p_path);
	$cid = $_GET['c_id'];
	$contentid = $_GET['content_id'];
	if($p_path != '')
	{
		switch ($contentid) {
			case '1': $sql = "delete from `ers_member_request_nodoc` where (`id`='".$cid."') LIMIT 1";
					$dbquery = $mysqli->query($link,$sql);
				break;			
		}
		$admin = $_SESSION["username"];
		$s_description = "Table : ers_member_request_nodoc ,ID : ".$cid." ,Document : ".$p_path;
		$u_ip = $_SERVER["REMOTE_ADDR"];
		$query_m = "insert into `ers_delete` (`user_name`,`ip_address`,`del_time`,`description`) values ('$admin','$u_ip',now(),'$s_description') ";
		$result_d = $mysqli->query($link,$query_m);
	}
	include("../include/close_db.php");
}
?>