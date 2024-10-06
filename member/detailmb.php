<? 
session_start();
Header("Content-Type: text/html; charset=UTF-8");
require_once "../include/config.php";
if (!defined('_web_path')) {
	exit();
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
     //   window.opener.location.reload();
    //}
	//window.close();
	parent.location.href = "login.php";
	</script>
	<?
	exit();
}

if(isset($_GET["m"])){
	$c_id = $_GET["m"];
} else {
	die();exit();
}
require_once "../include/convars.php";
require_once("../include/config_db.php");

$c_edf_filename1 = '';$c_edf_filename2 = '';$c_edf_filename3 = '';$c_edf_filename4 = '';$c_edf_filename5 = '';
$chk_expire = 0;
$sql = "select * from `ers_member_request` where (`id`='".$c_id."') and (`er_answer`='1') ";
$dbquery = $mysqli->query($sql) or die("Can't send query!");
$tRows = $dbquery->num_rows;
if($tRows > 0){
	$row = $dbquery->fetch_assoc();
	$c_document_id = $row["document_id"];
	$c_member_id = $row["member_id"];
	$c_er_request_text = $row["er_request_text"];
	$c_er_request_date = dateThai_edo(substr($row["er_request_date"],0,10));
	$c_er_answer = $row["er_answer"];
	$c_er_answer_text = $row["er_answer_text"];
	if(!empty($row["er_answer_expire"])) {
		$c_er_answer_expire = dateThai_edo(substr($row["er_answer_expire"],0,10));
	}

	$c_er_answer_name = $row["er_answer_name"];
	$sql_d = "select * from `ers_document` where `id`='".$c_document_id."' ";
	$dbquery_d = $mysqli->query($sql_d);
	$nRows_d = $dbquery_d->num_rows;
	if($nRows_d>0){
		$result_d = $dbquery_d->fetch_assoc();
		$c_ed_name_th = $result_d["ed_name_th"];
	}
	$sql_d = "select * from `ers_member` where `id`='".$c_member_id."' ";
	$dbquery_d = $mysqli->query($sql_d);
	$nRows_d = $dbquery_d->num_rows;
	if($nRows_d>0){
		$result_d = $dbquery_d->fetch_assoc();
		$c_username = $result_d["em_username"];
		$c_name = $result_d["em_title"];
		$c_name .= $result_d["em_firstname"];
		$c_name .= " ".$result_d["em_lastname"];
	}
	$now_date = date('Y-m-d H:i:s');
	$now_timestamp = strtotime($now_date);
	if(!empty($row["er_answer_expire"])) {
		$expire_date = $row["er_answer_expire"];
		$expire_timestamp = strtotime($expire_date);
	} else {$expire_timestamp = 0;}
	if($now_timestamp < $expire_timestamp){
		$chk_expire = 1;
	}
} else {include("../include/close_db.php"); echo "<div style='text-align:center;padding-top:50px;color:#ff0000;font-size:20px;'><strong>...ไม่พบข้อมูล...</strong></div>"; die();}
$mysqli->query("update `ers_member_request` set `er_answer_read`='1',`er_answer_counter`=`er_answer_counter`+1 where `id` ='".$c_id."'");
?>
<!DOCTYPE html>
<html lang="th" class="no-js">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="KeyWords" content="">
	<meta name="Description" content="">
	<meta name="ROBOTS" content="index, follow">
     <title>ระบบคลังข้อมูลงานวิจัย <?php if(defined('__EC_NAME__')){echo __EC_NAME__;}?></title>
	<link href="../images/<?php if(defined('__EC_FAVICON__')){echo __EC_FAVICON_ICO__;}?>" rel="icon" type="image/ico">
	<link href="../images/<?php if(defined('__EC_FAVICON__')){echo __EC_FAVICON__;}?>" rel="icon" type="image/png" sizes="32x32">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css?v=<?php echo filemtime('../bootstrap/css/bootstrap.min.css');?>">
    <script src="../js/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="../admin/style_admin.css?v=<?php echo filemtime('../admin/style_admin.css');?>">
	<link href="https://fonts.googleapis.com/css?family=Prompt" rel="stylesheet">
	<style>
	body{
		font-size:16px;
	}
	@media (max-width: 767px) {
		body{
			font-size:13px;
		}
	}
	.col-lg-12 ,.col-lg-8 ,.col-lg-4 { margin:0;padding:0; }
	.col-md-12 ,.col-md-8 ,.col-md-4 { margin:0;padding:0; }
	.col-sm-12 ,.col-sm-8 ,.col-sm-4 { margin:0;padding:0; }
	</style>
	<script>
	/*
	$(window).resize(function() { 
		var windowWidth = $(window).width();
		if(windowWidth < 768){
			document.body.style = 'font-size:13px';
		} else {
			document.body.style = 'font-size:16px';
		}
	}); */
	</script>
</head>

<body role="document" style="background: url('../images/<?php if(defined('__EC_PICHOME__')){echo __EC_PICHOME__;}?>') no-repeat; background-attachment:fixed; background-size:contain;height:100%;width:100%;background-position: left center;">

<div class="container bgw1">
	<div class="row bgw2">

	   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="margin-bottom:30px;background-color:#<?echo __EC_BGSHOW__;?>;color:#<?echo __EC_FONTSHOW__;?>;font-weight: bold;"><h4><?php if(defined('__EC_NAME__')){echo "การอนุมัติ การร้องขอเอกสารผลงานวิจัย";} else echo "ระบบคลังข้อมูลงานวิจัย";?></h4></div>

	  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right" style="">ชือสมาชิก&nbsp;:&nbsp;</div>
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><?echo $c_name;?></div>
	  </div>
	  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ชือ login&nbsp;:&nbsp;</div>
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><?echo $c_username;?></div>
	  </div>
	  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ผลงานวิจัย&nbsp;:&nbsp;</div>
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><?echo $c_ed_name_th;?></div>
	  </div>
	  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ข้อความร้องขอ&nbsp;:&nbsp;</div>
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><?echo $c_er_request_text;?></div>
	  </div>
	  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ข้อความตอบกลับ&nbsp;:&nbsp;</div>
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><?= $c_er_answer_text;?></div>
	  </div>
	  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">วันที่หมดอายุ&nbsp;:&nbsp;</div>
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" style="margin:0;padding:0;"><?= $c_er_answer_expire;?></div>
	  </div>
	  <?
		$sql = "select * from `ers_member_files` where (`request_id`='".$c_id."') and (`emf_approve`='1') order by `id`ASC";
		$dbquery = $mysqli->query($sql);
		$num_rows = $dbquery->num_rows;
		if($num_rows>0)
		{
			$item = 0;
			While($row= $dbquery->fetch_assoc()){
				$item += 1;
				//$c_edf_id = $row["id"];
				$c_document_files_id = $row["document_files_id"];
				$c_edf_filename = $row["emf_filename"];
				$c_edf_link = $row["emf_link"];
				//$c_emf_expire_date = $row["emf_expire_date"];
				//$c_emf_counter_open = $row["emf_counter_open"];
				//$c_emf_counter_download =$row["emf_counter_download"];
				$sql_d = "select * from `ers_document_files` where (`id`='".$c_document_files_id."')";
				$dbquery_d = $mysqli->query($sql_d);
				$num_rows_d = $dbquery_d->num_rows;
				if($num_rows_d > 0) {
					$result_d = $dbquery_d->fetch_assoc();
					$c_edf_filename = $result_d["edf_filename"];
					$c_edf_link = $result_d["edf_link"];
					if($chk_expire == 1){
						if($item==1){
						?>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
							<hr align="center" width="95%" noshade size="1" color="#cccccc">
						</div>
						<?}?>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right" style="font-weight: bold;white-space: nowrap;">เอกสาร <?= $item;?>&nbsp;:&nbsp;</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
								<?
									echo "<div id=\"view$item\"><span class='glyphicon glyphicon-file' style='color:#000000'></span>&nbsp;".$c_edf_filename."<br><a href='../include/filedownloadmb.php?m=".$c_document_files_id."&r=".$c_id."' target='_blank' style='white-space: nowrap;'><span class='glyphicon glyphicon-download-alt'></span>&nbsp;ดาวน์โหลด</a>&nbsp;&nbsp;</div>";
									//echo "<div id=\"view$item\"><span class='glyphicon glyphicon-file' style='color:#000000'></span>&nbsp;".$c_edf_filename."<br><a href='../include/filedownloadmb.php?fn=".$c_edf_filename."&m=".$c_document_files_id."&t=1&s=".$c_edf_link."' target='_blank' style='white-space: nowrap;'><span class='glyphicon glyphicon-folder-open'></span>&nbsp;&nbsp;เปิดอ่าน</a>&nbsp;&nbsp;<a href='../include/filedownloadmb.php?fn=".$c_edf_filename."&m=".$c_document_files_id."&s=".$c_edf_link."' target='_blank' style='white-space: nowrap;'><span class='glyphicon glyphicon-download-alt'></span>&nbsp;ดาวน์โหลด</a>&nbsp;&nbsp;</div>";
								?>
							</div>
						</div>
					<?
					}
				}
			}//while
		}
	  ?>
		
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">&nbsp;</div>
	
	</div><!-- /.row -->

</div><!-- /.container -->

</body>
</html>
<?include("../include/close_db.php");?>