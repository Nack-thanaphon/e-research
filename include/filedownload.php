<?php
session_start();
Header("Content-Type: text/html; charset=UTF-8");
require_once "config.php";
if (!defined('SECRET_KEY')) {
	exit;
}

if ( !isset($_SESSION["admin"]) || !isset($_SESSION["userlevel"]) || ($_SESSION["admin"]=="") )
{
	session_unset();session_destroy();
	echo "<br><br><center><a href='login.php'><span style='font-size:16px;font-weight:bold;color:#ff0000;'>Session Expired กรุณาเข้าระบบใหม่เพื่อใช้งาน</span></a></center>";
	//echo "<script>alert('Session Expired กรุณาเข้าระบบใหม่เพื่อใช้งาน');parent.location='login.php';</script>";
	?>
	<script>
	alert("Session Expired กรุณาเข้าระบบใหม่");
	//window.onunload = refreshParent;
    //function refreshParent() {
     //   window.opener.location.reload();
   // }
	//window.close();
	parent.location.href = "login.php";
	</script>
	<?php
	exit();
}

if(isset($_GET['m'])){
	$code_id = $_GET['m'];
} else { exit(); }
if(empty($code_id)){
	exit();
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
	if(isset($_GET['t']) and ($_GET['t']=='1')){ //open
		if($lp=='1'){
			if(isset($code_id)){
				$mysqli->query("update `ers_document_files` set `edf_counter_open`=`edf_counter_open`+1 where `id` ='".$code_id."'");
			}
			echo "<meta http-equiv='refresh' content='0;URL=".$filename_path."'>"; 
		} else {
			if (file_exists($filename_upload)) {
				if(isset($code_id)){
					$mysqli->query("update `ers_document_files` set `edf_counter_open`=`edf_counter_open`+1 where `id` ='".$code_id."'");
				}
				echo "<meta http-equiv='refresh' content='0;URL=".$filename_path."'>"; 
			} else {
				include("close_db.php"); 
				echo $file."<br>";
				die("Error: ไม่พบไฟล์นี้ ไฟล์อาจถูกลบไปแล้ว");
			}
		}
	} else { //download
		if($lp=='1'){
			if(isset($code_id)){
				$code_id = $code_id;
				include("config_db.php"); 
				$mysqli->query("update `ers_document_files` set `edf_counter_download`=`edf_counter_download`+1 where `id` ='".$code_id."'");
				include("close_db.php"); 
			}
			echo "<meta http-equiv='refresh' content='0;URL=".$filename_path."'>"; 
		} else {
			$size = filesize($filename_upload);
			if (file_exists($filename_upload)) {
				if(isset($code_id)){
					$mysqli->query("update `ers_document_files` set `edf_counter_download`=`edf_counter_download`+1 where `id` ='".$code_id."'");
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