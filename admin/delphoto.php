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
		switch ($contentid) {
			case '1': $sql = "select * from `ers_researcher` where (`id`='".$cid."') LIMIT 1";
				$dbquery = $mysqli->query($sql);
				$nRows = $dbquery->num_rows;
				if($nRows>0){
					$row = $dbquery->fetch_assoc();
					$c_filename = $row["ec_photopath"];
					$file_del = "../photo/".$c_filename;
					//$file_del_icon = iconv("tis-620","utf-8",$file_del);
					$file_del_icon = $file_del;
					if(@file_exists($file_del_icon))
					{
						@chmod($file_del_icon,0777);
						unlink($file_del_icon);
					}
					$sql = "update `ers_researcher` set `ec_photopath`='' where (`id`='".$cid."') LIMIT 1";
					$dbquery = $mysqli->query($sql);
				}
				break;			
		}
		$admin = $_SESSION["admin"];
		$s_description = "Table : ers_researcher ,ID : ".$cid." ,Image : ".$p_path;
		$u_ip = $_SERVER["REMOTE_ADDR"];
		$query_m = "insert into `ers_delete` (`user_name`,`ip_address`,`del_time`,`description`) values ('$admin','$u_ip',now(),'$s_description') ";
		$result_d = $mysqli->query($query_m);
	}
	include("../include/close_db.php");
}
?>