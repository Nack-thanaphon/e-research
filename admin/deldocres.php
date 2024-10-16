<?
session_start();
Header("Content-Type: text/html; charset=utf-8");
if ( !isset($_SESSION["admin"]) || !isset($_SESSION["userlevel"]) || ($_SESSION["admin"]=="") ) {
  echo "<Script language=\"javascript\">window.location=\"login.php\"</script>";
} else {
	require_once('../include/config_db.php');
	$p_path = $_GET['c_file_path'];
	$p_path = trim($p_path);
	$cid = $_GET['c_id'];
	$contentid = $_GET['content_id'];
	if($p_path != '')
	{
		$sql = "select * from `ers_document_researcher` where (`id`='".$cid."') LIMIT 1";
		$dbquery = $mysqli->query($link,$sql);
		$nRows = $dbquery->num_rows;
		if($nRows>0){
			$sql = "delete from `ers_document_researcher` where (`id`='".$cid."') LIMIT 1";
			$dbquery = $mysqli->query($link,$sql);
			$admin = $_SESSION["admin"];
			$s_description = "Table : ers_document_researcher ,ID : ".$cid." ,Document : ".$contentid ;
			$u_ip = $_SERVER["REMOTE_ADDR"];
			$query_m = "insert into `ers_delete` (`user_name`,`ip_address`,`del_time`,`description`) values ('$admin','$u_ip',now(),'$s_description') ";
			$result_d = $mysqli->query($link,$query_m);
		}
	}
	include("../include/close_db.php");
}
?>