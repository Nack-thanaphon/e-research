<?
session_start();
Header("Content-Type: text/html; charset=utf-8");
if (!isset($_SESSION["username"]) || !isset($_SESSION["membername"]) || !isset($_SESSION["memberid"]) || empty($_SESSION["username"])) {
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
			case '1': $sql = "update `ers_member_request_nodoc` set `er_request_cancel`='1',`update_date`=now(),`update_user`='".$_SESSION["username"]."'  where (`id`='".$cid."') and (`er_answer`='0') and (`member_id`='".$_SESSION["memberid"]."') LIMIT 1";
					$dbquery = $mysqli->query($sql);
				break;			
		}
	}
	include("../include/close_db.php");
}
?>