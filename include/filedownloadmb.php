<?
session_start();
Header("Content-Type: text/html; charset=UTF-8");
require_once "config.php";
if (!defined('SECRET_KEY')) {
	exit;
}
if (!isset($_SESSION["username"]) || !isset($_SESSION["membername"]) || !isset($_SESSION["memberid"]) || empty($_SESSION["username"])) 
{
	session_unset();session_destroy();
	echo "<br><br><center><a href='login.php'><span style='font-size:16px;font-weight:bold;color:#ff0000;'>Session Expired กรุณาเข้าระบบใหม่เพื่อใช้งาน</span></a></center>";
	//echo "<script>alert('Session Expired กรุณาเข้าระบบใหม่เพื่อใช้งาน');parent.location='login.php';</script>";
	?>
	<script>
	alert("Session Expired กรุณาเข้าระบบใหม่");
	//window.onunload = refreshParent;
    //function refreshParent() {
    //    window.opener.location.reload();
    //}
	//window.close();
	parent.location.href = "login.php";
	</script>
	<?
	exit();
}

if(isset($_GET['m'])){
	$code_id = $_GET['m'];
} else { exit(); }
if(empty($code_id)){
	exit();
}
$cr = '0';
if(isset($_GET["r"])){
	$cr = $_GET["r"];
}
//$filename_upload = iconv("utf-8","tis-620",$file);
//$file = str_replace(Array("\n", "\r", "\n\r"), '', $file);
//$filename_upload = urlencode($filename_upload);
//echo $filename_path;
//die();
//$quoted = sprintf('"%s"', addcslashes(basename($filename_upload), '"\\'));
include("config_db.php"); 
$sql = "select * from `ers_document_files` where (`id`='".$code_id."') ";
$dbquery = $mysqli->query($sql);
$num_rows = $dbquery->num_rows;
if($num_rows>0){
	$row = $dbquery->fetch_assoc();
	$file = $row['edf_filename'];
	$lp = $row["edf_link"];
	if($lp=='1'){
		$filename_upload = $file;
		$filename_path = $file;
	} else {
		$filename_upload = _home_path.'/files/'.$file;
		$filename_path = _web_path.'/files/'.$file;
	}
}
$sql = "select * from `ers_member_files` where (`member_id`='".$_SESSION["memberid"]."') and (`document_files_id`='".$code_id."') and (`emf_approve`='1') ";
$dbquery = $mysqli->query($sql);
$num_rows = $dbquery->num_rows;
if($num_rows>0)
{
	if(isset($_GET['t']) and ($_GET['t']=='1')){ //open
		if($lp=='1'){
			if(isset($code_id)){
				$mysqli->query("update `ers_document_files` set `edf_counter_open_member`=`edf_counter_open_member`+1 where `id` ='".$code_id."'");
				$mysqli->query("update `ers_member_files` set `emf_counter_open`=`emf_counter_open`+1 where  (`member_id`='".$_SESSION["memberid"]."') and (`document_files_id`='".$code_id."') and (`request_id`='".$cr."')");
				$mysqli->query("update `ers_member_request` set `er_open_counter`=`er_open_counter`+1 where `id` ='".$cr."'");
			}
			echo "<meta http-equiv='refresh' content='0;URL=".$filename_path."'>"; 
			//file_put_contents("$filename_path", fopen("$filename_path", 'r'));
		} else {
			if (file_exists($filename_upload)) {
				if(isset($code_id)){
					$mysqli->query("update `ers_document_files` set `edf_counter_open_member`=`edf_counter_open_member`+1 where `id` ='".$code_id."'");
					$mysqli->query("update `ers_member_files` set `emf_counter_open`=`emf_counter_open`+1 where  (`member_id`='".$_SESSION["memberid"]."') and (`document_files_id`='".$code_id."') and (`request_id`='".$cr."')");
					$mysqli->query("update `ers_member_request` set `er_open_counter`=`er_open_counter`+1 where `id` ='".$cr."'");
				}
				echo "<meta http-equiv='refresh' content='0;URL=".$filename_path."'>"; 
				//file_put_contents("$filename_path", fopen("$filename_path", 'r'));
			} else {
				include("close_db.php"); 
				echo $file."<br>";
				die("Error: ไม่พบไฟล์นี้ ไฟล์อาจถูกลบไปแล้ว");
			}
		}
	} else { //download
		if($lp=='1'){
			if(isset($code_id)){
				$mysqli->query("update `ers_document_files` set `edf_counter_download_member`=`edf_counter_download_member`+1 where `id` ='".$code_id."'");
				$mysqli->query("update `ers_member_files` set `emf_counter_download`=`emf_counter_download`+1 where  (`member_id`='".$_SESSION["memberid"]."') and (`document_files_id`='".$code_id."') and (`request_id`='".$cr."')");
				$mysqli->query("update `ers_member_request` set `er_download_counter`=`er_download_counter`+1 where `id` ='".$cr."'");
			}
			echo "<meta http-equiv='refresh' content='0;URL=".$filename_path."'>"; 
			//file_put_contents("$filename_path", fopen("$filename_path", 'r'));
		} else {
			$size   = filesize($filename_upload);
			if (file_exists($filename_upload)) {
				if(isset($code_id)){
					$mysqli->query("update `ers_document_files` set `edf_counter_download_member`=`edf_counter_download_member`+1 where `id` ='".$code_id."'");
					$mysqli->query("update `ers_member_files` set `emf_counter_download`=`emf_counter_download`+1 where  (`member_id`='".$_SESSION["memberid"]."') and (`document_files_id`='".$code_id."') and (`request_id`='".$cr."')");
					$mysqli->query("update `ers_member_request` set `er_download_counter`=`er_download_counter`+1 where `id` ='".$cr."'");
				}
				header( "Content-type: application/octet-stream" );
				header( "Content-Disposition: attachment; filename={$file}" );
				header( "Content-length: " . $size );
				header( "Pragma: no-cache" );
				header( "Expires: 0" );
				readfile( "{$filename_upload}" );
			} else {
				include("close_db.php"); 
				echo $file."<br>";
				die("Error: ไม่พบไฟล์นี้ ไฟล์อาจถูกลบไปแล้ว");
			}
		}
	}
}
include("close_db.php");
?>