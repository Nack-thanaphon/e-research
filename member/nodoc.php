<?php
session_start();
Header("Content-Type: text/html; charset=UTF-8");
require_once "../include/config.php";
require_once "../include/convars.php";
if (!defined('_web_path')) {
	exit;
}

if (!isset($_SESSION["username"]) || !isset($_SESSION["membername"]) || !isset($_SESSION["memberid"]) || empty($_SESSION["username"])) 
{
  echo "<Script language=\"javascript\">window.location=\"login.php\"</script>";
  exit();
}
$username = $_SESSION['username'];
$membername = $_SESSION['membername'];
$memberid = $_SESSION["memberid"];

require_once "../include/config_db.php";

if( isset($_POST["chk_edit"]) ){$chk_edit = $_POST["chk_edit"];} else {$chk_edit="";}
if(isset($_GET["c_id"])){	$c_id = $_GET["c_id"];} else {if(isset($_POST["c_id"])){$c_id = $_POST["c_id"];}}

$c_username = '';$c_title_th_v = 0;$c_title_th = '';$c_firstname = '';$c_lastname = '';$c_gender = '';$c_institution = '';
$c_institution_type = 0;$c_institution_other = '';$c_phone = '';$c_email = '';$c_address = '';$c_institution_name = "";
if(isset($memberid)){
	if(!empty($memberid)){
		$sql = "select * from `ers_member` where (`id`='$memberid')";
		$dbquery = $mysqli->query($sql);
		$tRows = $dbquery->num_rows;
		if($tRows>0){
			$row = $dbquery->fetch_assoc();
			$c_username = $row["em_username"];
			$c_title_th = $row["em_title"];
			if($c_title_th=="นาย"){ $c_title_th_v = '1';} elseif($c_title_th=="นาง"){ $c_title_th_v = '2';} elseif($c_title_th=="นางสาว"){ $c_title_th_v = '3';} elseif(!empty($c_title_th)){ $c_title_th_v = '4';}
			$c_firstname = $row["em_firstname"];
			$c_lastname = $row["em_lastname"];
			$c_name = $c_title_th.$c_firstname." ".$c_lastname;
			$c_gender = $row["em_gender"];
			$c_institution = $row["em_institution"];
			$c_institution_type = $row["em_institution_type"];
			$c_institution_other = $row["em_institution_other"];
			$c_phone = $row["em_phone"];
			$c_email = $row["em_email"];
			$c_address = $row["em_address"];
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
		}
		$dbquery->free();
	} 
}
$c_ed_name_th = 'งานวิจัยนอกระบบ';

if($chk_edit=="1")
{	
	$s_er_request_text = $_POST["s_er_request_text"];

	$sql = "select * from `ers_member_request_nodoc` where (`id`='".$c_id."')";
	$dbquery = $mysqli->query($sql) or die("Can't send query !!");
	$num_rows = $dbquery->num_rows;
	$dbquery->close();
	if($num_rows > 0) {
		$sql = "update `ers_member_request_nodoc` set `er_request_text`='$s_er_request_text',`er_request_date`=now(),`er_request_cancel`='0',`update_date`=now(),`update_user`='$username' where (`id`='".$c_id."') and (`er_answer`='0') and (`member_id`='$memberid')";
		$dbquery = $mysqli->query($sql) or die("ไม่สามารถบันทึกข้อมูลได้ !1");
	}else {
		if(!empty($s_er_request_text)){
			$sql = "insert into `ers_member_request_nodoc` (`member_id`,`document_id`,`er_request_text`,`er_request_date`,`update_date`,`update_user`) values ('$memberid','0','$s_er_request_text',now(),now(),'$username')";
			$dbquery = $mysqli->query($sql) or die("ไม่สามารถบันทึกข้อมูลได้ !2");
		}
	}
	/*

	$c_con_email = "aggasit1972@outlook.com";
	//$c_con_email_cc1 = "";
	//$c_con_email_cc2 = "";
	//$c_con_email_bcc1 = "";
	//$c_con_email_bcc2 = "";
	$s_con_title = "สมาชิกร้องขอเอกสารผลงานวิจัย";
	$c_con_admin_email = "aggasith.r@gmail.com";
	$c_con_admin_email_password = "";
	$c_con_admin_email_server = "smtp.gmail.com";
	$s_con_message = "ขอเอกสารผลงานวิจัย : ".$c_ed_name_th."<br>ชื่อ : ".$c_name."<br>เบอร์โทร : ".$c_phone."<br>อีเมล์ : ".$c_email."<br>หน่วยงานที่สังกัด : ".$c_institution." ".$c_institution_name."<br>ที่อยู่ : ".$c_address."<br>ข้อความร้องขอ : ".$s_er_request_text;
	$s_con_name = $c_name;
	require("../PHPMailer_v5.1/class.phpmailer.php");
	function smtpmail( $email , $subject , $body, $uemail, $uemailpass, $uemailserver, $uname )
	{
		$mail = new PHPMailer();
		$mail->IsSMTP();          
		$mail->CharSet = "utf-8";  // ในส่วนนี้ ถ้าระบบเราใช้ tis-620 หรือ windows-874 สามารถแก้ไขเปลี่ยนได้                         
		$mail->Host = $uemailserver; //  mail server ของเรา
		$mail->SMTPAuth = true;     //  เลือกการใช้งานส่งเมล์ แบบ SMTP
		$mail->Username = $uemail;   //  account e-mail ของเราที่ต้องการจะส่ง
		$mail->Password = $uemailpass;  //  รหัสผ่าน e-mail ของเราที่ต้องการจะส่ง
		//$mail->SMTPDebug = 2;                     // enables SMTP debug information (for testing)
		//$mail->SMTPAuth = true;                  // enable SMTP authentication
		//$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
		//$mail->Host = "smtp.gmail.com";      // sets GMAIL as the SMTP server
		//$mail->Port = 465;      

		$mail->From = $uemail;  //  account e-mail ของเราที่ใช้ในการส่งอีเมล
		$mail->FromName = $uname; //  ชื่อผู้ส่งที่แสดง เมื่อผู้รับได้รับเมล์ของเรา
		$mail->AddAddress($email);            // Email ปลายทางที่เราต้องการส่ง(ไม่ต้องแก้ไข)
		//$mail->AddCC($email_cc1);            // CC Email ปลายทางที่เราต้องการส่ง(ไม่ต้องแก้ไข)
		//$mail->AddCC($email_cc2);            // CC Email ปลายทางที่เราต้องการส่ง(ไม่ต้องแก้ไข)
		$mail->AddBCC($email_bcc1);            // BCC Email ปลายทางที่เราต้องการส่ง(ไม่ต้องแก้ไข)
		$mail->AddBCC($email_bcc2);            // BCC Email ปลายทางที่เราต้องการส่ง(ไม่ต้องแก้ไข)
		$mail->IsHTML(true);                  // ถ้า E-mail นี้ มีข้อความในการส่งเป็น tag html ต้องแก้ไข เป็น true
		$mail->Subject = $subject;        // หัวข้อที่จะส่ง(ไม่ต้องแก้ไข)
		$mail->Body = $body;                   // ข้อความ ที่จะส่ง(ไม่ต้องแก้ไข)
		$result = $mail->send();        
		return $result;
	}
	if(!empty($c_con_email) && isset($c_con_email))
	{
		$result = smtpmail( $c_con_email , $s_con_title , $s_con_message, $c_con_admin_email, $c_con_admin_email_password, $c_con_admin_email_server, $s_con_name );
		//if($result){echo "Success<br>";}else{echo "Faild<br>";}
		echo $result;
		include("../include/close_db.php");
		die();
	}
	*/
	$c_id = "";
}

$_menu_id = 1;

$c_er_request_text = '';

if(isset($c_id)){
	if($c_id!=''){
		$sql = "select * from `ers_member_request_nodoc` where (`id`='$c_id')";
		$dbquery = $mysqli->query($sql) or die("Can't send query!");
		$tRows = $dbquery->num_rows;
		if($tRows>0){
			$row = $dbquery->fetch_assoc();
			$c_er_request_text = $row["er_request_text"];
		}
		$dbquery->free();
		unset($dbquery);
	} 
}

?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="KeyWords" content="">
<meta name="Description" content="">
<meta name="ROBOTS" content="index, follow">
<title>ระบบคลังข้อมูลงานวิจัย <?if(defined('__EC_NAME__')){echo __EC_NAME__;}?></title>
<link href="../images/<?if(defined('__EC_FAVICON__')){echo __EC_FAVICON_ICO__;}?>" rel="icon" type="image/ico">
<link href="../images/<?if(defined('__EC_FAVICON__')){echo __EC_FAVICON__;}?>" rel="icon" type="image/png" sizes="32x32">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css?v=<?php echo filemtime('../bootstrap/css/bootstrap.min.css');?>">
<script src="../js/jquery.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="../admin/style_admin.css?v=<?php echo filemtime('../admin/style_admin.css');?>">
<link href="https://fonts.googleapis.com/css?family=Prompt" rel="stylesheet">
<script type="text/javascript" src="ajax.js"></script>
<script type="text/javascript" src="ajax_content.js"></script>
<script>

	$(window).resize(function() { 
		var windowWidth = $(window).width();
		var pw1 = (windowWidth * 80) / 100;
		var pw2 = 0;
		if(windowWidth > 1367){
			pw2 = 1300;
		} else {
			pw2 = pw1;
		}
		pw3 = pw2 + 10;
		var pleft = (windowWidth)?(windowWidth-pw3)/2:100;
		if(windowWidth < 768){
			document.getElementById('ersshow').style.left = '20px';
		} else {
			document.getElementById('ersshow').style.left = (pleft) + "px";
		}
	}); 
	
	$(window).scroll(function() { 
		var sct = $(window).scrollTop(); 
		document.getElementById('ersshow').style.top = (sct + 25) + "px";
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
		}
	}); 
	
	function hide_detail(){

		$('document').unbind('keyup');
		$('#detailoverlay').unbind('click');
			
		$('#ersshow').fadeOut();
		$('#detailoverlay').fadeOut();
		$('#ersshow').html('');
		
	}

	function changebckr(){
		document.getElementById('btnclose').style.backgroundColor = "#ff0000";
	}
	function changebckb(){
		document.getElementById('btnclose').style.backgroundColor = "#000";
	}
	</SCRIPT>
	<style>
	.footrow{display:none;}
	#detailoverlay{
		position:fixed;
		width:100%;
		height:100%;
		top:0;
		left:0;
		opacity:0.6;
		display: none;
		background-color: #ccc;
		z-index: 9998;
	}
	#ersshow{
		border: solid 1px #616161;
		border-radius: 5px;
		position: absolute;
		background-color: #fff;
		z-index: 9999;
		display: none;
		box-shadow: 0 0 5px #000;
	}	
	.hSelect {
		padding-top:10px;
		padding-bottom:10px;
	}
	.ontop1 {z-index: 991;}	.ontop2 {z-index: 992;}	.ontop3 {z-index: 993;}	.ontop4 {z-index: 994;}	.ontop5 {z-index: 995;}
	.ontop6 {z-index: 996;}	.ontop7 {z-index: 997;}	.ontop8 {z-index: 998;}	.ontop9 {z-index: 999;}	.ontop10 {z-index: 1000;}
	.col-lg-12 ,.col-lg-9 ,.col-lg-3 { margin:0;padding:0; }
	.col-md-12 ,.col-md-9 ,.col-md-3 { margin:0;padding:0; }
	.col-sm-12 ,.col-sm-9 ,.col-sm-3 { margin:0;padding:0; }
	.h1member {	font-size: 24px; font-size: 1.5rem; font-weight: bold;padding-top: 1em;padding-left:10px;}
	.h2member {	font-size: 24px; font-size: 1.5rem; font-weight: bold;padding-top: .4em; margin-bottom: .1em;padding-left:10px;}
	.h3member {	font-size: 20px; font-size: 1.25rem; font-weight: bold;line-height: 20px; padding-left:10px;}
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
	@media (max-width: 768px) {
		.h1member {	font-size: 16px; padding-top: 10px;padding-left:5px;}
		.h2member {	font-size: 16px; padding-top: 0; margin-bottom: 5px;padding-left:5px;}
		.h3member {	font-size: 13px; line-height: 18px; padding-left:5px;}
	}
	</style>
</head>
<body role="document" style="background: url('../images/<?if(defined('__EC_PICHOME__')){echo __EC_PICHOME__;}?>') no-repeat;background-attachment:fixed; background-size: 60% auto;height:auto;width:auto;background-position: center;">
<a name="detailtop"></a>
<div id="detailoverlay"></div>
<a href="#0" class="cd-top">Top</a>
<!-- cd-top JS -->
<script src="../js/main.js"></script>

<div class="container-fluid" style="width:100vw; height:100vh;-moz-opacity: 0.97;-khtml-opacity: 0.97;opacity: 0.97; background-color: #ffffff;">

	<? require_once "../headerpb.php"; ?>

	<div class="row" style="padding-top:50px;padding-bottom:10px;">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 palignr" style="padding-top:10px;padding-left:5px;padding-right:10px;">
			
			<div><a href="../index.php" class="btn btn-success" style="width:160px;margin-top:2px;">ขอเอกสารวิจัย(ในระบบ)</a>&nbsp;<a href="nodoc.php" class="btn btn-warning" style="width:170px;margin-top:2px;">ขอเอกสารวิจัย(นอกระบบ)</a>&nbsp;<a href="editprofile.php" class="btn btn-primary" style="width:110px;margin-top:2px;">แก้ไขประวัติ</a>&nbsp;<a href="changepw.php" class="btn btn-info" style="width:110px;margin-top:2px;">เปลี่ยนรหัสผ่าน</a>&nbsp;
			</div>

		</div><!-- /.col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right -->

	</div><!-- /.row -->

	<div class="row">

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-left">
				<div class="h1member">ยีนดีต้อนรับคุณ <font color="#000099"><?=$c_firstname," ".$c_lastname;?></font></div>
				<div class="h2member">ชื่อ Login&nbsp;:&nbsp;<font color="#000099"><?=$c_username;?></font>&nbsp;<?if(isset($_SESSION["memberpass"])){echo ",รหัสผ่าน : <font color='#000099'>".$_SESSION["memberpass"]."</font>";}?></div>
				<div class="h3member">หน่วยงานที่สังกัด : <?=$c_institution;?>&nbsp;<?=$c_institution_name;?></div>
				<div class="h3member">โทรศัพท์ : <?=$c_phone;?>&nbsp;,อีเมล : <?=$c_email;?></div>
				<div class="h3member">ที่อยู่ : <?=$c_address;?></div>
			</div>

	</div><!-- /.row -->

	<div class="row">
		<div class="col-lg-6 col-md- 8 col-sm-10 col-xs-12 col-lg-offset-3 col-md-offset-2 col-sm-offset-1" style="padding-top:5px;padding-bottom:20px;">

		  <form name="form1"  method="post" action="nodoc.php" onSubmit="return c_check();" role="form">
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
				<div class="h1member">&nbsp;:&nbsp;ข้อความร้องขอเอกสารงานวิจัย (งานวิจัยนอกระบบ)&nbsp;:&nbsp;</div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
				<?
				if(!empty($c_ed_name_th)){
					//echo $c_ed_name_th;
				}
				?>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
				<div><textarea name="s_er_request_text" id="s_er_request_text" rows="5" class="form-control" style="border-radius:5px;border:1px solid #ccc;" maxlength="500" placeholder="ระบุข้อความรายละเอียด ที่ต้องการเอกสารงานวิจัย"><?=$c_er_request_text;?></textarea></div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >&nbsp;</div>
		      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
   					<input type="hidden" name="chk_edit" value="1"> 
					<input type="hidden" name="c_id" value="<? if(isset($c_id)){ echo $c_id;}else{ echo '';}?>">
					<input type="submit" name="Submit" value=" ส่งข้อมูล " class="btn btn-warning" style="width:100px;font-size:18px;">&nbsp;
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >&nbsp;</div>			  
		  </form>

		</div><!-- /.col-lg-6 col-md- 8 col-sm-10 col-xs-12 col-lg-offset-3 col-md-offset-2 col-sm-offset-1 -->
	</div><!-- /.row -->
	
	<div class="row bgw">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="background-color:#<?echo __EC_BGSHOW__;?>;color:#<?echo __EC_FONTSHOW__;?>;"><h4 class="sub-header">แสดงข้อมูล ข้อความร้องขอเอกสารงานวิจัย (งานวิจัยนอกระบบ)</h4></div>
		 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

			<?
			  if(isset($_GET["sh_order"])){	$sh_order = $_GET["sh_order"];} else {$sh_order=0;}
			  if(!isset($_GET["Page"])){
				if($sh_order==1){$sh_order=0;}else{$sh_order=1;}
			  }
			  if($sh_order==1){ $asort="<span class='glyphicon glyphicon-arrow-up' style='font-size:8px;'></span>"; } else { $asort="<span class='glyphicon glyphicon-arrow-down' style='font-size:8px;'></span>"; }
			  if(isset($_GET["sd"])){$sd = $_GET["sd"];} else { $sd = 0;}
			  $a1sort = $a2sort = $a3sort = $a4sort = $a5sort = $a6sort = $a7sort = $a8sort = "<span class='glyphicon glyphicon-sort' style='font-size:8px;'></span>";
			  switch ($sd) {
					case '1': $a1sort = $asort; break;
					case '2': $a2sort = $asort; break;
					case '3': $a3sort = $asort; break;
					case '4': $a4sort = $asort; break;
					case '5': $a5sort = $asort; break;
					case '6': $a6sort = $asort; break;
					case '7': $a7sort = $asort; break;
					case '8': $a7sort = $asort; break;
				}
			?>

			<div id="tblResponsive">

			<table class="table table-striped table-bordered sticky-header" id="table-1">
				<thead>
					<tr id="ontop1" style="background-color:#e5e5e5">
						  <th id="ontop2">&nbsp;</th>
						  <th id="ontop3">&nbsp;</th>
						  <th style="text-align:center;" id="ontop4">&nbsp;<a href="nodoc.php?sd=1&sh_order=<?=$sh_order;?>" target="_parent">ID <?=$a1sort;?></a>&nbsp;</th>
						  <th style="text-align:center;" id="ontop5">&nbsp;ข้อความร้องขอ&nbsp;</th>
						  <th style="text-align:center;" id="ontop7">&nbsp;<a href="nodoc.php?sd=3&sh_order=<?=$sh_order;?>" target="_parent" style="white-space: nowrap;">วันที่ร้องขอ <?=$a3sort;?></a>&nbsp;</th>
						  <th style="text-align:center;" id="ontop8">&nbsp;<a href="index.php?sd=4&sh_order=<?=$sh_order;?>" target="_parent" >ผลการดำเนินการ <?=$a4sort;?></a>&nbsp;</th>
						  <th style="text-align:center;" id="ontop9">&nbsp;ข้อความที่ตอบ&nbsp;</th>
						  <th style="text-align:center;" id="ontop10">&nbsp;วันที่ตอบ&nbsp;</th>
					</tr>
				</thead>
				<tbody class="bgw">
				<?
			$sql = "select * From `ers_member_request_nodoc` where (`member_id`='".$memberid."') ";

			if(isset($_GET["sd"])){$sd = trim($_GET["sd"]);} else {	$sd = 0;}
			switch ($sd) {
				case '1': $sql .= "Order by `id` "; break;
				case '2': $sql .= "Order by `er_request_text` "; break;
				case '3': $sql .= "Order by `er_request_date` "; break;
				case '4': $sql .= "Order by `er_answer` "; break;
				case '5': $sql .= "Order by `er_answer_text` "; break;
				case '6': $sql .= "Order by `er_answer_date` "; break;
				case '7': $sql .= "Order by `er_answer_name` "; break;
				case '8': $sql .= "Order by `document_id` "; break;
				default : $sql .= "Order by `id` ";
			}
			if($sh_order==1){$sql .= "DESC ";} else {$sql .= "ASC ";}
			$ch = array("2","3","4","5","6","7","8");
			if(in_array($sd,$ch)){$sql .= ",`id` Desc ";}
			$res = $mysqli->query($sql);
			$totalRows = $res->num_rows;

			$Per_Page = 20;
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

			if($totalRows!="0"){

				if($Page==1){$jk=0;}
				else{
					$a=$Page * $Per_Page;
					$jk=$a-$Per_Page;
				}

				while($result = $res->fetch_assoc()){
					$jk++;
					$c_id = $result["id"];
					$doc_id = $result["document_id"];
					$c_er_request_text = $result["er_request_text"];					
					$c_er_request_date = dateThai_edo(substr($result["er_request_date"],0,10));
					$c_er_request_read = $result["er_request_read"];
					$c_er_request_read_text = "";
					if($c_er_request_read=='1'){
						$c_er_request_read_text = "<span style='color:#ff0000;'>อ่านแล้ว</span>";
					} else {
						$c_er_request_read_text = "ยังไม่อ่าน";
					}
					$c_er_answer = $result["er_answer"];
					$c_er_answer_approve = "<span class='glyphicon glyphicon-check' style='color:#ff0099;font-size:14px;'></span>&nbsp;รอตรวจสอบ";
					if($c_er_answer==1){
						$c_er_answer_approve = "<span class='glyphicon glyphicon-ok' style='color:#00cc00;font-size:14px;'></span>&nbsp;ตอบกลับแล้ว";
					}
					$c_er_request_cancel = $result["er_request_cancel"];
					if($c_er_request_cancel==1){
						$c_er_answer_approve = "<span class='glyphicon glyphicon-remove-sign' style='color:#ff9933;font-size:14px;'></span>&nbsp;ยกเลิกแล้ว";
					}

					$c_er_answert_text = $result["er_answer_text"];
					
					if(!empty($result["er_answer_date"])) {
						$c_er_answer_date = dateThai_edo(substr($result["er_answer_date"],0,10));
					} else {
						$c_er_answer_date = "";
					}
					
					$code_1 = "ขอเอกสารวิจัยนอกระบบ : ".$c_er_request_text." ,ID : ".$c_id;

					?>
					<tr>
						<td style="text-align:center;width:100px;min-width:100px;">
							<?
							if($c_er_answer==0){
							echo "<a href='nodoc.php?c_id=$c_id&dcid=$doc_id' style='color:green;font-size:16px;' title='แก้ไข'><span class='glyphicon glyphicon-edit'></span>&nbsp;<span style='font-size:14px;'>แก้ไข</span></a>";
							}
							?>
						</td>
						<td style="text-align:center;width:100px;min-width:100px;">
							<?
							if($c_er_answer==0){
							$dfile =  'dfile'.$jk;
							?>
							<a href="javascript:void(0)" style="color:red;font-size:16px;" title="ยกเลิก" onclick="confirmCancel('<?=$dfile;?>','<?=$c_id;?>','<?=$code_1;?>','1')"><span class="glyphicon glyphicon-minus-sign"></span>&nbsp;<span style="font-size:14px;">ยกเลิก</span><span id="<?=$dfile;?>"></span></a>
							<?}?>
						</td>
						<td style="text-align:center;"><?=$c_id;?></td>
						<td style="text-align:left;">&nbsp;
							<?
							echo "<a href=\"javascript:void(0)\" title='' style='color:#000000;'>".$c_er_request_text."</a>";
							?>&nbsp;
						</td>
						<td style="text-align:center;"><?=$c_er_request_date;?></td>
						<td style="text-align:center;"><?=$c_er_answer_approve;?></td>
						<td style="text-align:left;">&nbsp;
							<?
								echo "<a href=\"javascript:void(0)\" title='' style='color:#000000;'>".$c_er_answert_text."</a>";
							?>&nbsp;
						</td>
						<td style="text-align:center;"><?=$c_er_answer_date;?></td>
					</tr>
					<?
					}//while
					$res->free();
				} else {
					echo "<tr class='text-center' style='background-color:#ffffff'><td colspan='8'><br><span style='color:#ff0000;font-size:16px;'>..ไม่พบข้อมูล..</span></td></tr>";
				}
				?>					
				</tbody>
			</table>
			</div>

		 </div>
	</div>
   
	<div class="row bgw">			  	
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-left:5px;padding-right:5px;">
			<div style="font-size:14px;">หน้า :
				<?
					$pages = new Paginator;
					$pages->items_total = $totalRows;
					$pages->mid_range = 7;
					$pages->current_page = $Page;
					$pages->default_ipp = $Per_Page;
					$pages->url_next = $_SERVER["PHP_SELF"]."?&sd=$sd&sh_order=$sh_order&dcid=$dcid&Page=";
					$pages->paginate();
					echo $pages->display_pages();
					unset($Paginator);
				?>
			</div>		
	    </div>

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div align="center"><? require_once("../admin/footer.php") ?></div>
	    </div>

	</div><!-- /.row -->
</div>
<div id="ersshow"></div>
<?include("../include/close_db.php");?>
</body>
</html>
<script>
function confirmCancel(span_id,id_order,filename,content_id) {
  if (confirm("ยืนยันการยกเลิก "+filename)) {
	ajax_loadContent(span_id,'can_req_nodoc.php',id_order,'member_request',content_id);
	window.location.reload(true);
	/*window.location.replace("nodoc.php");*/
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
function c_check(){
		if(document.getElementById('s_er_request_text').value == "")
		{
			alert("ระบุข้อความรายละเอียด ที่ต้องการเอกสารงานวิจัย");
			document.getElementById('s_er_request_text').select();
			return false;
		}
	}
function FocusOnInput() {
	  var element = document.getElementById('s_er_request_text');
	  element.focus();
	  setTimeout(function () { element.focus(); }, 1);
	}
FocusOnInput();
</script>
