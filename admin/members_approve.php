<?
session_start();
Header("Content-Type: text/html; charset=UTF-8");
require_once "../include/config.php";
require_once "../include/convars.php";
if (!defined('_web_path')) {
	exit();
}

if ( !isset($_SESSION["admin"]) || !isset($_SESSION["userlevel"]) || ($_SESSION["admin"]=="") )
{
	session_unset();session_destroy();
	echo "<Script language=\"javascript\">window.location=\"login.php\"</script>";
	exit();
}
$admin = $_SESSION['admin'];
$userlevel = $_SESSION['userlevel'];

$_menu_id = 2;

if(isset($_GET["iRegister"])){
	if($_GET["iRegister"] =="1"){
		unset($_SESSION["u_code_1"]);
	}
}
if(isset($_POST["iRegister"])){
	if($_POST["iRegister"] =="1"){
		unset($_SESSION["u_code_1"]);
	}
}

include("../include/config_db.php");

if(isset($_POST["c_code_1"]) ){$c_code_1 = $_POST["c_code_1"];	}else{$c_code_1 = $_GET["c_code_1"];}
if(trim($c_code_1)!=""){
  $_SESSION["u_code_1"] = $c_code_1;
} else {
  if(isset($_POST["c_code_1"])){$_SESSION["u_code_1"]="";}
}
$sql = "select m.*,d.*,s.id,s.ed_name_th,s.ed_name_en,s.ed_detail,m.id as req_id FROM (`ers_member_request` m JOIN `ers_document` s ON m.document_id=s.id) JOIN `ers_member` d ON m.member_id=d.id where (`er_request_cancel`='0') and (`er_answer`='1') and (`document_id`>'0') and (d.id > '0') ";
if(isset($_SESSION["u_code_1"]) and !empty($_SESSION["u_code_1"]))
{
	  $u_code_1 = $_SESSION["u_code_1"];
	  $u_code_1 = trim($u_code_1);
	  $sql .= "and ((m.id = '$u_code_1')";
	  if(strpos( $u_code_1," ")>=1){
		  $u_code_array = explode(" ",$u_code_1);
		  $u_search = $u_code_array[0];
		  $sql .= " or (`er_request_text` like '%$u_search%') or (`er_answer_text` like '%$u_search%') or (`em_username` like '%$u_search%') or (`em_title` like '%$u_search%') or (`em_firstname` like '%$u_search%') or (`em_lastname` like '%$u_search%') or (`em_institution` like '%$u_search%') or (`em_institution_other` like '%$u_search%') or (`em_phone` like '%$u_search%') or (`em_email` like '%$u_search%') or (`em_address` like '%$u_search%') or (`ed_name_th` like '%$u_search%') or (`ed_name_en` like '%$u_search%') or (`ed_detail` like '%$u_search%')";
		  for($i = 1, $size = count($u_code_array); $i < $size; ++$i) {
			   $u_search = $u_code_array[$i];
			   $sql .= ") and ((`er_request_text` like '%$u_search%') or (`er_answer_text` like '%$u_search%') or (`em_username` like '%$u_search%') or (`em_title` like '%$u_search%') or (`em_firstname` like '%$u_search%') or (`em_lastname` like '%$u_search%') or (`em_institution` like '%$u_search%') or (`em_institution_other` like '%$u_search%') or (`em_phone` like '%$u_search%') or (`em_email` like '%$u_search%') or (`em_address` like '%$u_search%') or (`ed_name_th` like '%$u_search%') or (`ed_name_en` like '%$u_search%') or (`ed_detail` like '%$u_search%')";
		  }
	  } else {
		  $sql .= " or (`er_request_text` like '%$u_code_1%') or (`er_answer_text` like '%$u_code_1%') or (`em_username` like '%$u_code_1%') or (`em_title` like '%$u_code_1%') or (`em_firstname` like '%$u_code_1%') or (`em_lastname` like '%$u_code_1%') or (`em_institution` like '%$u_code_1%') or (`em_institution_other` like '%$u_code_1%') or (`em_phone` like '%$u_code_1%') or (`em_email` like '%$u_code_1%') or (`em_address` like '%$u_code_1%') or (`ed_name_th` like '%$u_code_1%') or (`ed_name_en` like '%$u_code_1%') or (`ed_detail` like '%$u_code_1%')";
	  }
	  $sql .= ") ";
}
$dbquery = $mysqli->query($sql) or die("Can't send query!");
$tRows_y = $dbquery->num_rows;
$sql = "select m.*,d.*,s.id,s.ed_name_th,s.ed_name_en,s.ed_detail,m.id as req_id FROM (`ers_member_request` m JOIN `ers_document` s ON m.document_id=s.id) JOIN `ers_member` d ON m.member_id=d.id where (`er_request_cancel`='0') and (`er_answer`='0') and (`document_id`>'0') and (d.id > '0') ";
if(isset($_SESSION["u_code_1"]) and !empty($_SESSION["u_code_1"]))
{
	  $u_code_1 = $_SESSION["u_code_1"];
	  $u_code_1 = trim($u_code_1);
	  $sql .= "and ((m.id = '$u_code_1')";
	  if(strpos( $u_code_1," ")>=1){
		  $u_code_array = explode(" ",$u_code_1);
		  $u_search = $u_code_array[0];
		  $sql .= " or (`er_request_text` like '%$u_search%') or (`er_answer_text` like '%$u_search%') or (`em_username` like '%$u_search%') or (`em_title` like '%$u_search%') or (`em_firstname` like '%$u_search%') or (`em_lastname` like '%$u_search%') or (`em_institution` like '%$u_search%') or (`em_institution_other` like '%$u_search%') or (`em_phone` like '%$u_search%') or (`em_email` like '%$u_search%') or (`em_address` like '%$u_search%') or (`ed_name_th` like '%$u_search%') or (`ed_name_en` like '%$u_search%') or (`ed_detail` like '%$u_search%')";
		  for($i = 1, $size = count($u_code_array); $i < $size; ++$i) {
			   $u_search = $u_code_array[$i];
			   $sql .= ") and ((`er_request_text` like '%$u_search%') or (`er_answer_text` like '%$u_search%') or (`em_username` like '%$u_search%') or (`em_title` like '%$u_search%') or (`em_firstname` like '%$u_search%') or (`em_lastname` like '%$u_search%') or (`em_institution` like '%$u_search%') or (`em_institution_other` like '%$u_search%') or (`em_phone` like '%$u_search%') or (`em_email` like '%$u_search%') or (`em_address` like '%$u_search%') or (`ed_name_th` like '%$u_search%') or (`ed_name_en` like '%$u_search%') or (`ed_detail` like '%$u_search%')";
		  }
	  } else {
		  $sql .= " or (`er_request_text` like '%$u_code_1%') or (`er_answer_text` like '%$u_code_1%') or (`em_username` like '%$u_code_1%') or (`em_title` like '%$u_code_1%') or (`em_firstname` like '%$u_code_1%') or (`em_lastname` like '%$u_code_1%') or (`em_institution` like '%$u_code_1%') or (`em_institution_other` like '%$u_code_1%') or (`em_phone` like '%$u_code_1%') or (`em_email` like '%$u_code_1%') or (`em_address` like '%$u_code_1%') or (`ed_name_th` like '%$u_code_1%') or (`ed_name_en` like '%$u_code_1%') or (`ed_detail` like '%$u_code_1%')";
	  }
	  $sql .= ") ";
}
$dbquery = $mysqli->query($sql) or die("Can't send query!");
$tRows_w = $dbquery->num_rows;
$sql = "select m.*,d.*,s.id,s.ed_name_th,s.ed_name_en,s.ed_detail,m.id as req_id FROM (`ers_member_request` m JOIN `ers_document` s ON m.document_id=s.id) JOIN `ers_member` d ON m.member_id=d.id where (`er_request_cancel`='1') and (`document_id`>'0') and (d.id > '0') ";
if(isset($_SESSION["u_code_1"]) and !empty($_SESSION["u_code_1"]))
{
	  $u_code_1 = $_SESSION["u_code_1"];
	  $u_code_1 = trim($u_code_1);
	  $sql .= "and ((m.id = '$u_code_1')";
	  if(strpos( $u_code_1," ")>=1){
		  $u_code_array = explode(" ",$u_code_1);
		  $u_search = $u_code_array[0];
		  $sql .= " or (`er_request_text` like '%$u_search%') or (`er_answer_text` like '%$u_search%') or (`em_username` like '%$u_search%') or (`em_title` like '%$u_search%') or (`em_firstname` like '%$u_search%') or (`em_lastname` like '%$u_search%') or (`em_institution` like '%$u_search%') or (`em_institution_other` like '%$u_search%') or (`em_phone` like '%$u_search%') or (`em_email` like '%$u_search%') or (`em_address` like '%$u_search%') or (`ed_name_th` like '%$u_search%') or (`ed_name_en` like '%$u_search%') or (`ed_detail` like '%$u_search%')";
		  for($i = 1, $size = count($u_code_array); $i < $size; ++$i) {
			   $u_search = $u_code_array[$i];
			   $sql .= ") and ((`er_request_text` like '%$u_search%') or (`er_answer_text` like '%$u_search%') or (`em_username` like '%$u_search%') or (`em_title` like '%$u_search%') or (`em_firstname` like '%$u_search%') or (`em_lastname` like '%$u_search%') or (`em_institution` like '%$u_search%') or (`em_institution_other` like '%$u_search%') or (`em_phone` like '%$u_search%') or (`em_email` like '%$u_search%') or (`em_address` like '%$u_search%') or (`ed_name_th` like '%$u_search%') or (`ed_name_en` like '%$u_search%') or (`ed_detail` like '%$u_search%')";
		  }
	  } else {
		  $sql .= " or (`er_request_text` like '%$u_code_1%') or (`er_answer_text` like '%$u_code_1%') or (`em_username` like '%$u_code_1%') or (`em_title` like '%$u_code_1%') or (`em_firstname` like '%$u_code_1%') or (`em_lastname` like '%$u_code_1%') or (`em_institution` like '%$u_code_1%') or (`em_institution_other` like '%$u_code_1%') or (`em_phone` like '%$u_code_1%') or (`em_email` like '%$u_code_1%') or (`em_address` like '%$u_code_1%') or (`ed_name_th` like '%$u_code_1%') or (`ed_name_en` like '%$u_code_1%') or (`ed_detail` like '%$u_code_1%')";
	  }
	  $sql .= ") ";
}
$dbquery = $mysqli->query($sql) or die("Can't send query!");
$tRows_c = $dbquery->num_rows;
$sql = "select m.*,d.*,s.id,s.ed_name_th,s.ed_name_en,s.ed_detail,m.id as req_id FROM (`ers_member_request` m JOIN `ers_document` s ON m.document_id=s.id) JOIN `ers_member` d ON m.member_id=d.id where (`er_request_cancel`='0') and (`er_answer`='2') and (`document_id`>'0') and (d.id > '0') ";
if(isset($_SESSION["u_code_1"]) and !empty($_SESSION["u_code_1"]))
{
	  $u_code_1 = $_SESSION["u_code_1"];
	  $u_code_1 = trim($u_code_1);
	  $sql .= "and ((m.id = '$u_code_1')";
	  if(strpos( $u_code_1," ")>=1){
		  $u_code_array = explode(" ",$u_code_1);
		  $u_search = $u_code_array[0];
		  $sql .= " or (`er_request_text` like '%$u_search%') or (`er_answer_text` like '%$u_search%') or (`em_username` like '%$u_search%') or (`em_title` like '%$u_search%') or (`em_firstname` like '%$u_search%') or (`em_lastname` like '%$u_search%') or (`em_institution` like '%$u_search%') or (`em_institution_other` like '%$u_search%') or (`em_phone` like '%$u_search%') or (`em_email` like '%$u_search%') or (`em_address` like '%$u_search%') or (`ed_name_th` like '%$u_search%') or (`ed_name_en` like '%$u_search%') or (`ed_detail` like '%$u_search%')";
		  for($i = 1, $size = count($u_code_array); $i < $size; ++$i) {
			   $u_search = $u_code_array[$i];
			   $sql .= ") and ((`er_request_text` like '%$u_search%') or (`er_answer_text` like '%$u_search%') or (`em_username` like '%$u_search%') or (`em_title` like '%$u_search%') or (`em_firstname` like '%$u_search%') or (`em_lastname` like '%$u_search%') or (`em_institution` like '%$u_search%') or (`em_institution_other` like '%$u_search%') or (`em_phone` like '%$u_search%') or (`em_email` like '%$u_search%') or (`em_address` like '%$u_search%') or (`ed_name_th` like '%$u_search%') or (`ed_name_en` like '%$u_search%') or (`ed_detail` like '%$u_search%')";
		  }
	  } else {
		  $sql .= " or (`er_request_text` like '%$u_code_1%') or (`er_answer_text` like '%$u_code_1%') or (`em_username` like '%$u_code_1%') or (`em_title` like '%$u_code_1%') or (`em_firstname` like '%$u_code_1%') or (`em_lastname` like '%$u_code_1%') or (`em_institution` like '%$u_code_1%') or (`em_institution_other` like '%$u_code_1%') or (`em_phone` like '%$u_code_1%') or (`em_email` like '%$u_code_1%') or (`em_address` like '%$u_code_1%') or (`ed_name_th` like '%$u_code_1%') or (`ed_name_en` like '%$u_code_1%') or (`ed_detail` like '%$u_code_1%')";
	  }
	  $sql .= ") ";
}
$dbquery = $mysqli->query($sql) or die("Can't send query!");
$tRows_d = $dbquery->num_rows;
$dbquery->free();
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
	<script type="text/javascript" src="ajax.js"></script>
	<script type="text/javascript" src="ajax_content.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Prompt" rel="stylesheet">
	<link rel="stylesheet" href="../admin/style_admin.css?v=<?php echo filemtime('../admin/style_admin.css');?>">
	<SCRIPT LANGUAGE="JavaScript">	
	$(window).scroll(function() { 
		var sct = $(window).scrollTop(); 
		if(sct>100){
			document.getElementById('ontop1').classList.add("ontop1");
			document.getElementById('ontop2').classList.add("ontop2");
			document.getElementById('ontop3').classList.add("ontop3");
			document.getElementById('ontop4').classList.add("ontop4");
			document.getElementById('ontop5').classList.add("ontop5");
			document.getElementById('ontop6').classList.add("ontop6");
			document.getElementById('ontop7').classList.add("ontop7");
			document.getElementById('ontop8').classList.add("ontop8");
			document.getElementById('ontop9').classList.add("ontop9");
			document.getElementById('ontop10').classList.add("ontop10");
			document.getElementById('ontop11').classList.add("ontop11");
			document.getElementById('ontop12').classList.add("ontop12");
			document.getElementById('ontop13').classList.add("ontop13");
			document.getElementById('ontop14').classList.add("ontop14");
			document.getElementById('ontop15').classList.add("ontop15");
		} else {
			document.getElementById('ontop1').classList.remove("ontop1");
			document.getElementById('ontop2').classList.remove("ontop2");
			document.getElementById('ontop3').classList.remove("ontop3");
			document.getElementById('ontop4').classList.remove("ontop4");
			document.getElementById('ontop5').classList.remove("ontop5");
			document.getElementById('ontop6').classList.remove("ontop6");
			document.getElementById('ontop7').classList.remove("ontop7");
			document.getElementById('ontop8').classList.remove("ontop8");
			document.getElementById('ontop9').classList.remove("ontop9");
			document.getElementById('ontop10').classList.remove("ontop10");
			document.getElementById('ontop11').classList.remove("ontop11");
			document.getElementById('ontop12').classList.remove("ontop12");
			document.getElementById('ontop13').classList.remove("ontop13");
			document.getElementById('ontop14').classList.remove("ontop14");
			document.getElementById('ontop15').classList.remove("ontop15");
		}
	}); 
	</SCRIPT>
	<style>
	@media (min-width: 768px) {
		/*body {
			padding-top:50px;
		}*/
		table.floatThead-table {
			border-top: none;
			border-bottom: none;
		}
		th {
		  position: sticky;
		  top: 50px;
		  background: #e5e5e5;
		}
	}
	.hSelect {
		padding-top:10px;
		padding-bottom:10px;
	}
	.ontop1 {z-index: 991;}
	.ontop2 {z-index: 992;}
	.ontop3 {z-index: 993;}
	.ontop4 {z-index: 994;}
	.ontop5 {z-index: 995;}
	.ontop6 {z-index: 996;}
	.ontop7 {z-index: 997;}
	.ontop8 {z-index: 998;}
	.ontop9 {z-index: 999;}
	.ontop10 {z-index: 1000;}
	.ontop11 {z-index: 1001;}
	.ontop12 {z-index: 1002;}
	.ontop13 {z-index: 1003;}
	.ontop14 {z-index: 1004;}
	.ontop15 {z-index: 1005;}
	.col-lg-12 ,.col-lg-9 ,.col-lg-3 { margin:0;padding:0; }
	.col-md-12 ,.col-md-9 ,.col-md-3 { margin:0;padding:0; }
	.col-sm-12 ,.col-sm-9 ,.col-sm-3 { margin:0;padding:0; }
	</style>
</head>
<body role="document">
<a href="#0" class="cd-top">Top</a>
<!-- cd-top JS -->
<script src="../js/main.js"></script>

<div class="container-fluid">
	<? require_once "header.php"; ?>
	<div class="row" style="padding-top:50px;padding-bottom:10px;">			  
		<div id="hSelect" class="hSelect">

			<form name="form2" method="post" action="members_approve.php" role="form">
			    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
					  <span class="sbreak2">
					  <input type="text" name="c_code_1" id="c_code_1" class="searchbox-control" maxlength="30" placeholder="ข้อความที่ต้องการค้นหา" value="<?= $_SESSION["u_code_1"];?>">&nbsp;
					  <input type="submit" name="Submit" id="Submit" value="ค้นหา" class="btn btn-success" style="width:70px; margin:2px;">&nbsp;<input type="button" name="Clear" id="Clear" value="ยกเลิก" class="btn btn-info" style="width:70px;margin-top:0;" onclick="window.location='members_approve.php?iRegister=1';"></span>
			    </div>
			</form>

		</div>
	</div><!-- /.row -->
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<!--<span style="font-size:14px;font-weight: bold;margin-left:10px;">-->
			<div style="margin-left:10px;">
			<span class="glyphicon glyphicon-flag" style="color:#00cc00;"></span>&nbsp;อนุมัติแล้ว : <font style='color:#00cc00;'><?= number_format($tRows_y);?></font>&nbsp;&nbsp;
			<span class="glyphicon glyphicon-flag" style="color:#ff0099;"></span>&nbsp;รอตรวจสอบ : <font style='color:#ff0099;'><?= number_format($tRows_w);?></font>&nbsp;&nbsp;
			<span class="glyphicon glyphicon-flag" style="color:#ff9933;"></span>&nbsp;สมาชิกยกเลิก : <font style='color:#ff9933;'><?= number_format($tRows_c);?></font>&nbsp;&nbsp;
			<span class="glyphicon glyphicon-flag" style="color:#ff0000;"></span>&nbsp;ไม่อนุมัติ : <font style='color:#ff0000;'><?= number_format($tRows_d);?></font>
			</div>
		</div>
	</div><!-- /.row -->
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="background-color:#<?echo __EC_BGSHOW__;?>;color:#<?echo __EC_FONTSHOW__;?>;"><h4 class="sub-header">อนุมัติการร้องขอเอกสารผลงานวิจัย</h4></div>
		 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

			<?
			  if(isset($_GET["sh_order"])){	$sh_order = $_GET["sh_order"];} else {$sh_order=0;}
			  if(!isset($_GET["Page"])){
				if($sh_order==1){$sh_order=0;}else{$sh_order=1;}
			  }
			  if($sh_order==1){ $asort="<span class='glyphicon glyphicon-arrow-up' style='font-size:8px;'></span>"; } else { $asort="<span class='glyphicon glyphicon-arrow-down' style='font-size:8px;'></span>"; }
			  if(isset($_GET["sd"])){$sd = $_GET["sd"];} else { $sd=0;}
			  $a1sort = $a2sort = $a3sort = $a4sort = $a5sort = $a6sort = $a7sort = "<span class='glyphicon glyphicon-sort' style='font-size:8px;'></span>";
			  switch ($sd) {
					case '1': $a1sort = $asort; break;
					case '2': $a2sort = $asort; break;
					case '3': $a3sort = $asort; break;
					case '4': $a4sort = $asort; break;
					case '5': $a5sort = $asort; break;
					case '6': $a6sort = $asort; break;
					case '7': $a7sort = $asort; break;
				}
			?>

			<div id="tblResponsive">

			<table class="table table-striped table-bordered sticky-header" id="table-1" style="background: url('../images/<?php if(defined('__EC_PICHOME__')){echo __EC_PICHOME__;}?>') repeat-y; background-attachment:fixed; background-size:contain;height:100%;width:100%;">
				<thead>
					<tr id="ontop1" style="background-color:#e5e5e5">
						  <th id="ontop2">&nbsp;</th>
						  <th style="vertical-align:middle;text-align:center;" id="ontop3"><a href="members_approve.php?sd=1&sh_order=<?= $sh_order;?>" target="_parent" style="white-space: nowrap;">ID <?= $a1sort;?></a></th>
						  <th style="vertical-align:middle;text-align:center;"  id="ontop4"><a href="members_approve.php?sd=2&sh_order=<?= $sh_order;?>" target="_parent" style="white-space: nowrap;">ชื่อ-นามสกุล <?= $a2sort;?></a></th>
						  <th style="vertical-align:middle;text-align:center;"  id="ontop5"><a href="members_approve.php?sd=3&sh_order=<?= $sh_order;?>" target="_parent" style="white-space: nowrap;">ชื่อ login <?= $a3sort;?></a></th>
						  <th style="vertical-align:middle;text-align:center;" id="ontop6">โทรศัพท์</th>
						  <th style="vertical-align:middle;text-align:center;" id="ontop7">อีเมล</th>
						  <th style="vertical-align:middle;text-align:center;" id="ontop8">&nbsp;ข้อความร้องขอ&nbsp;</th>
						  <th style="vertical-align:middle;text-align:center;" id="ontop9">&nbsp;ชื่อผลงานวิจัย&nbsp;</th>
						  <th style="vertical-align:middle;text-align:center;" id="ontop10">&nbsp;<a href="members_approve.php?sd=4&sh_order=<?= $sh_order;?>" target="_parent" style="white-space: nowrap;">วันที่ร้องขอ <?= $a3sort;?></a>&nbsp;</th>
						  <th style="vertical-align:middle;text-align:center;" id="ontop11">&nbsp;<a href="members_approve.php?sd=5&sh_order=<?= $sh_order;?>" target="_parent" >อนุมัติ <?= $a4sort;?></a>&nbsp;</th>
						  <th style="vertical-align:middle;text-align:center;" id="ontop12">&nbsp;วันที่หมดอายุ&nbsp;</th>
						  <th style="vertical-align:middle;text-align:center;" id="ontop13">&nbsp;ข้อความที่ตอบ&nbsp;</th>
						  <th style="vertical-align:middle;text-align:center;" id="ontop14">&nbsp;วันที่ตอบ&nbsp;</th>
						  <th style="vertical-align:middle;text-align:center;" id="ontop15">&nbsp;ดาวน์โหลด&nbsp;</th>
					</tr>
				</thead>
				<tbody class="bgw">
				<?
				$sql = "SELECT m . * , d . *,s.id,s.ed_name_th,s.ed_name_en,s.ed_detail,m.id as req_id FROM (`ers_member_request` m JOIN `ers_document` s ON m.document_id=s.id) JOIN `ers_member` d ON m.member_id = d.id WHERE (d.id > 0) and (m.document_id >0) ";
				if(isset($_SESSION["u_code_1"]) and !empty($_SESSION["u_code_1"]))
				{
					  $u_code_1 = $_SESSION["u_code_1"];
					  $u_code_1 = trim($u_code_1);
					  $sql .= "and ((m.id = '$u_code_1')";
					  if(strpos( $u_code_1," ")>=1){
						  $u_code_array = explode(" ",$u_code_1);
						  $u_search = $u_code_array[0];
						  $sql .= " or (`er_request_text` like '%$u_search%') or (`er_answer_text` like '%$u_search%') or (`em_username` like '%$u_search%') or (`em_title` like '%$u_search%') or (`em_firstname` like '%$u_search%') or (`em_lastname` like '%$u_search%') or (`em_institution` like '%$u_search%') or (`em_institution_other` like '%$u_search%') or (`em_phone` like '%$u_search%') or (`em_email` like '%$u_search%') or (`em_address` like '%$u_search%') or (`ed_name_th` like '%$u_search%') or (`ed_name_en` like '%$u_search%') or (`ed_detail` like '%$u_search%')";
						  for($i = 1, $size = count($u_code_array); $i < $size; ++$i) {
							   $u_search = $u_code_array[$i];
							   $sql .= ") and ((`er_request_text` like '%$u_search%') or (`er_answer_text` like '%$u_search%') or (`em_username` like '%$u_search%') or (`em_title` like '%$u_search%') or (`em_firstname` like '%$u_search%') or (`em_lastname` like '%$u_search%') or (`em_institution` like '%$u_search%') or (`em_institution_other` like '%$u_search%') or (`em_phone` like '%$u_search%') or (`em_email` like '%$u_search%') or (`em_address` like '%$u_search%') or (`ed_name_th` like '%$u_search%') or (`ed_name_en` like '%$u_search%') or (`ed_detail` like '%$u_search%')";
						  }
					  } else {
						  $sql .= " or (`er_request_text` like '%$u_code_1%') or (`er_answer_text` like '%$u_code_1%') or (`em_username` like '%$u_code_1%') or (`em_title` like '%$u_code_1%') or (`em_firstname` like '%$u_code_1%') or (`em_lastname` like '%$u_code_1%') or (`em_institution` like '%$u_code_1%') or (`em_institution_other` like '%$u_code_1%') or (`em_phone` like '%$u_code_1%') or (`em_email` like '%$u_code_1%') or (`em_address` like '%$u_code_1%') or (`ed_name_th` like '%$u_code_1%') or (`ed_name_en` like '%$u_code_1%') or (`ed_detail` like '%$u_code_1%')";
					  }
					  $sql .= ") ";
				}
				//echo $sql."<br>";
				
				if(isset($_GET["sd"])){$sd = $_GET["sd"];} else {	$sd = 0;}
				switch ($sd) {
					case '1': $sql .= "Order by req_id "; break;
					case '2': $sql .= "Order by `em_firstname` "; break;
					case '3': $sql .= "Order by `em_username` "; break;
					case '4': $sql .= "Order by `er_request_date` "; break;
					case '5': $sql .= "Order by `er_answer` "; break;
					default : $sql .= "Order by req_id ";
				}
				if($sh_order==1){$sql .= "DESC ";} else {$sql .= "ASC ";}
				$ch = array("2","3","4","5");
				if(in_array($sd,$ch)){$sql .= ",req_id Desc ";}
				$res = $mysqli->query($sql);
				$totalRows = $res->num_rows;

				if(isset($_POST["Per_Page"])) {
					$Per_Page = $_POST["Per_Page"];
				} else { $Per_Page = $_GET["Per_Page"];}
				if(!isset($Per_Page) or empty($Per_Page)) { $Per_Page = 18; }
				$Page = $_GET["Page"];
				if(!$_GET["Page"]){$Page=1;}

				$Page_Start = (($Per_Page*$Page)-$Per_Page);
				if($totalRows<=$Per_Page)	{$Num_Pages =1;}
				else if(($totalRows % $Per_Page)==0)
				{
					$Num_Pages =($totalRows/$Per_Page) ;
				}
				else
				{
					$Num_Pages =($totalRows/$Per_Page)+1;
					$Num_Pages = (int)$Num_Pages;
				}
				if(!($Page_Start)){ $Page_Start = 0;}

				$sql .= " LIMIT $Page_Start,$Per_Page";
				$res = $mysqli->query($sql);

				if($totalRows > 0){

					if($Page==1){$jk=0;}
					else{
						$a=$Page * $Per_Page;
						$jk=$a-$Per_Page;
					}

					while($row = $res->fetch_assoc()){
						$jk++;
						$c_id = $row["req_id"];
						$doc_id = $row["document_id"];
						$c_er_request_text = $row["er_request_text"];
						$c_er_request_cancel = $row["er_request_cancel"];
						$c_er_answer = $row["er_answer"];
						$reqlen = mb_strlen($c_er_request_text,'UTF-8');
						$reqlen2 = 25;
						$c_er_request_text2 = $c_er_request_text;
						if($reqlen > $reqlen2) {
							$c_er_request_text2 = utf8_substr($c_er_request_text,0,$reqlen2)."..";
						}
						$c_er_request_date = dateThai_edo(substr($row["er_request_date"],0,10));
						$c_er_answer_approve = "<span class='glyphicon glyphicon-check' style='color:#ff0099;font-size:14px;'></span>&nbsp;<span class='blink'>รอตรวจสอบ</span>&nbsp;";
						if($c_er_answer==1){
							$c_er_answer_approve = "<span class='glyphicon glyphicon-ok' style='color:#00cc00;font-size:14px;'></span>&nbsp;อนุมัติ&nbsp;";
						}
						if($c_er_answer==2){
							$c_er_answer_approve = "<span class='glyphicon glyphicon-remove' style='color:#ff0000;font-size:14px;'></span>&nbsp;ไม่อนุมัติ";
						}
						if(!empty($row["er_answer_expire"])) {
							$c_er_answer_expire = dateThai_edo(substr($row["er_answer_expire"],0,10));
						} else {
							$c_er_answer_expire = "";
						}
						$c_er_answer_text = $row["er_answer_text"];
						$anslen = mb_strlen($c_er_answer_text,'UTF-8');
						$anslen2 = 20;
						if($anslen > $anslen2) {
							$c_er_answer_text2 = utf8_substr($c_er_answer_text,0,$anslen2)."..";
						}
						if(!empty($row["er_answer_date"])) {
							$c_er_answer_date = dateThai_edo(substr($row["er_answer_date"],0,10));
						} else {
							$c_er_answer_date = "";
						}
						$c_er_download_counter = $row["er_download_counter"];

						$c_username = $row["em_username"];
						$c_name = $row["em_title"];
						$c_name .= $row["em_firstname"];
						$c_name .= " ".$row["em_lastname"];
						$c_name2 = $row["em_firstname"]." ".$row["em_lastname"];
						$c_gender = $row["em_gender"];
						$c_institution = $row["em_institution"];
						$c_institution_type = $row["em_institution_type"];
						$c_institution_other = $row["em_institution_other"];
						$c_phone = $row["em_phone"];
						$c_email = $row["em_email"];
						$c_address = $row["em_address"];
						$adrlen = mb_strlen($c_address,'UTF-8');
						$adrlen2 = 20;
						$c_address2 = $c_address;
						if($adrlen > $adrlen2) {
							$c_address2 = utf8_substr($c_address,0,$adrlen2)."..";
						}
						$c_institution_name = "";
						switch ($c_institution_type) {
							case '1': $c_institution_name = "หน่วยงานภาครัฐ/วิสาหกิจ"; break;
							case '2': $c_institution_name = "หน่วยงานภาคเอกชน"; break;
							case '3': $c_institution_name = "องค์กรอิสระ"; break;
							case '4': $c_institution_name = "สถาบันการศึกษาภาครัฐ"; break;
							case '5': $c_institution_name = "สถาบันการศึกษาเอกชน"; break;
							case '6': $c_institution_name = "ประชาชน"; break;
							case '7': $c_institution_name = $c_institution_other; break;
							default : $c_institution_name = "";
						}
						//$sql_d = "select * from `ers_document` where `id`='".$doc_id."' ";
						//$dbquery_d = $mysqli->query($sql_d);
						//$nRows_d = $dbquery_d->num_rows;
						$c_ed_name_th = "";
						//if($nRows_d>0){
						//	$result_d = $dbquery_d->fetch_assoc();
							$c_ed_name_th = $row["ed_name_th"];
							$doclen = mb_strlen($c_ed_name_th,'UTF-8');
							$doclen2 = 30;
							if($doclen > $doclen2) {
								$c_ed_name_th2 = utf8_substr($c_ed_name_th,0,$doclen2)."..";
							}
						//}
						$now_date = date('Y-m-d H:i:s');
						$now_timestamp = strtotime($now_date);
						if(!empty($row["er_answer_expire"])) {
							$expire_date = $row["er_answer_expire"];
							$expire_timestamp = strtotime($expire_date);
						} else {$expire_timestamp = 0;}
						$chk_expire = 1;
						if($c_er_answer==1){
							if($now_timestamp > $expire_timestamp){
								$chk_expire = 0;
								$c_er_answer_approve = "<span class='glyphicon glyphicon-floppy-remove' style='color:#000000;font-size:14px;'></span>&nbsp;หมดอายุ&nbsp;";
							}
						}
						$code_1 = $c_name." ,ID : ".$c_id;

						$subject_text = "ผลการร้องขอเอกสารผลงานวิจัย ".__EC_NAME__;
						$encodedSubject = htmlspecialchars($subject_text);
						$body_text = "สวัสดีคุณ ".$c_name2;
						if($c_er_answer==2){
							$body_text .= " ,ผลการอนุมัติ : ไม่อนุมัติ ";
						}
						if($c_er_answer==1){
							if($chk_expire==1){
								$body_text .= " ,ผลการอนุมัติ : อนุมัติ  ,ดาวน์โหลดเอกสารได้ที่ "._web_path."/member/";
							}
						}
						$encodedBody = htmlspecialchars($body_text);

					?>
					<tr>
						<td style="text-align:center;width:100px;min-width:100px;">
							<?$dfile =  'dfile'.$jk;?>
							<a href="javascript:void(0)" style="color:red;font-size:16px;" title="ลบ" onclick="confirmDelete('<?= $dfile;?>','<?= $c_id;?>','<?= $code_1;?>','1')"><span class="glyphicon glyphicon-trash"></span>&nbsp;<span style="font-size:14px;">ลบ</span><span id="<?= $dfile;?>"></span></a>
						</td>
						<td style="text-align:center;padding-left:0;padding-right:0;">&nbsp;<?= $c_id;?>&nbsp;</td>
						<td style="text-align:left;padding-left:0;padding-right:0;white-space: nowrap;">&nbsp;<?= $c_name;?>&nbsp;</td>
						<td style="text-align:center;padding-left:0;padding-right:0;white-space: nowrap;">&nbsp;<?= $c_username;?>&nbsp;</td>
						<td style="text-align:center;padding-left:0;padding-right:0;white-space: nowrap;">
							<?php if($c_er_request_cancel>=1){
								echo "&nbsp;".$c_phone."&nbsp;";
							} else {?>
								<a href='tel:<?= $c_phone;?>' target="_blank" title='<?= $c_phone;?>'>&nbsp;<?= $c_phone;?>&nbsp;</a>
							<?}?>
						</td>
						<td style="text-align:center;padding-left:0;padding-right:0;white-space: nowrap;">
						<?php if($c_er_request_cancel>=1){
								echo "&nbsp;".$c_email."&nbsp;";
							} else {?>
								<a href='mailto:<?= $c_email;?>?subject=<?= $encodedSubject;?>&body=<?= $encodedBody;?>' target='_blank' title="<?= $c_email;?>">&nbsp;<?= $c_email;?>&nbsp;</a>
							<?}?>
						</td>
						<td style="text-align:left;">
							<?
							if($c_er_request_cancel>=1){
								if($reqlen > $reqlen2) {
									echo "&nbsp;<a href='javascript:void(0)' style='color:#000000;' title='".$c_er_request_text."'>".$c_er_request_text2."</a>&nbsp;";
								} else {
									echo "&nbsp;<a href='javascript:void(0)' style='color:#000000;' title='".$c_er_request_text."'>".$c_er_request_text."</a>&nbsp;";
								}
							} else {
								if($reqlen > $reqlen2) {
									echo "&nbsp;<a href='javascript:void(0)' onclick='openWindow(".$c_id.")' style='' title='".$c_er_request_text."'>".$c_er_request_text2."</a>&nbsp;";
								} else {
									echo "&nbsp;<a href='javascript:void(0)' onclick='openWindow(".$c_id.")' style='' title='".$c_er_request_text."'>".$c_er_request_text."</a>&nbsp;";
								}
							}
							?>
						</td>
						<td style="text-align:left;">&nbsp;
							<?
							if($doclen > $doclen2) {
								echo "<a href=\"javascript:void(0)\" title='".$c_ed_name_th."' style='color:#000000;'>".$c_ed_name_th2."</a>";
							} else {
								echo "<a href=\"javascript:void(0)\" title='' style='color:#000000;'>".$c_ed_name_th."</a>";
							}
							?>&nbsp;
						</td>
						<td style="text-align:center;">&nbsp;<?= $c_er_request_date;?>&nbsp;</td>
						<td style="text-align:left;">
							<?
							if($c_er_request_cancel>=1){
									echo "<div style='color:#fff9900;'>&nbsp;<span class='glyphicon glyphicon-remove-sign' style='color:#ff9933;font-size:14px;'></span>&nbsp;สมาชิกยกเลิก</div>";
							} else {
							?>
								<div>&nbsp;<a href='javascript:void(0)' onclick='openWindow(<?= $c_id;?>)' title='อนุมัติ/ไม่อนุมัติ'><?= $c_er_answer_approve;?></a>&nbsp;</div>
							<?}?>
						</td>
						<td style="text-align:center;">&nbsp;<?php if($c_er_answer==1){echo $c_er_answer_expire;}?>&nbsp;</td>
						<td style="text-align:left;">&nbsp;
							<?
							if($anslen > $anslen2) {
								echo "<a href=\"javascript:void(0)\" title='".$c_er_answer_text."' style='color:#000000;'>".$c_er_answer_text2."</a>";
							} else {
								echo "<a href=\"javascript:void(0)\" title='' style='color:#000000;'>".$c_er_answer_text."</a>";
							}
							?>&nbsp;
						</td>
						<td style="text-align:center;">&nbsp;<?= $c_er_answer_date;?>&nbsp;</td>
						<td style="text-align:center;">&nbsp;<?= number_format($c_er_download_counter);?>&nbsp;</td>
					</tr>
					<?
					}//while
					$res->free();
				} else {
					echo "<tr class='text-center' style='background-color:#ffffff'><td colspan='14'><br><span style='color:#ff0000;font-size:18px;'>..ไม่พบข้อมูล..</span></td></tr>";
				}
				?>					
				</tbody>
			</table>
			</div>

		 </div>
	</div><!-- /.row -->
   
	<div class="row" style="margin:0;padding:0;">			  	
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="col-lg-9 col-md-9 col-sm-9 col-xs-7" style="font-size:14px;">หน้า :
				<?
					$pages = new Paginator;
					$pages->items_total = $totalRows;
					$pages->mid_range = 7;
					$pages->current_page = $Page;
					$pages->default_ipp = $Per_Page;
					$pages->url_next = $_SERVER["PHP_SELF"]."?sd=$sd&sh_order=$sh_order&Page=";
					$pages->paginate();
					echo $pages->display_pages();
					unset($Paginator);
				?>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 text-right">
				<form name="form3" method="post" action="members_approve.php?sd=<?= $sd;?>&sh_order=<?= $sh_order;?>&Page=1" role="form">
					<select name="Per_Page" id="Per_Page" style="width:130px;border-radius:5px;border:1px solid #<?echo __EC_BGSHOW__;?>;padding:5px;" onchange="document.form3.submit()">
					<option value="18" <?php if($Per_Page=='18'){echo "selected";}?>>18 รายการ/หน้า</option>
					<option value="30"<?php if($Per_Page=='30'){echo "selected";}?>>30 รายการ/หน้า</option>
					<option value="60"<?php if($Per_Page=='60'){echo "selected";}?>>60 รายการ/หน้า</option>
					<option value="90"<?php if($Per_Page=='90'){echo "selected";}?>>90 รายการ/หน้า</option>
					</select>
				</form>
			</div>
		</div>

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div align="center" style="padding-bottom:<?php if($Per_Page=='18'){echo "10px";}else{echo "80px";}?>;"><? require_once "./footer.php"; ?></div>
	    </div>
	</div><!-- /.row -->

</div>
<div id="ersshow"></div>
</body>
</html>
<? include("../include/close_db.php"); ?>
<script>
function openWindow(idp) {
	var url='mbapprove.php?req_id='+idp;
	var sw=document.body.clientWidth;
	var sh=screen.height;
	var sw3=0;
	if(sw < 769){
		sw = (sw * 80) / 100;
		sh = (sh * 80) / 100;
	} else {
		sw = (sw * 70) / 100;
		sh = (sh * 80) / 100;
	}
	sw3 = sw + 10;

	var myleft=(document.body.clientWidth)?(document.body.clientWidth-sw3)/2:100;   
	var sct = $(window).scrollTop(); 
	var left = (myleft) + "px";
	var tops = (sct + 25) + "px";
	var win=window.open(url,'staff','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,,width='+sw+', height='+sh+', top='+tops+', left='+left);
	win.focus();
}
function confirmDelete(span_id,id_member,filename,content_id) {
  if (confirm("ยืนยันการลบข้อมูลการร้องขอเอกสารของ "+filename)) {
	ajax_loadContent(span_id,'del_req.php',id_member,'member',content_id);
	window.location.reload(true);
	/*window.location.replace("members_approve.php?iRegister=1");*/
  }
}
document.getElementById("fmnavbar").className = "navbar navbar-default navbar-fixed-top";
if( document.body.clientWidth > 767) {
	document.getElementById('tblResponsive').classList.remove("table-responsive");
	/*$(document).ready(function(){
	  $(".sticky-header").floatThead({top:50});
	});*/
} else {
	document.getElementById('tblResponsive').classList.add("table-responsive");
	document.getElementById('table-1').classList.remove("sticky-header");
}
document.getElementById('c_code_1').focus();
</script>