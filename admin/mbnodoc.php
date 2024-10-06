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
	echo "<div style='text-align:center;padding-top:30px;font-size:18px;'>Session Expired กรุณาเข้าระบบใหม่</div>";
	?>
	<script>
	alert("Session Expired กรุณาเข้าระบบใหม่");
	window.onunload = refreshParent;
    function refreshParent() {
        window.opener.location.reload();
    }
	window.close();
	</script>
	<?
	exit();
 }

$admin = $_SESSION['admin'];
$userlevel = $_SESSION['userlevel'];

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

if(isset($_GET["req_id"])){	$req_id = $_GET["req_id"];} else {if(isset($_POST["req_id"])){$req_id = $_POST["req_id"];}}

include("../include/config_db.php");

if( isset($_POST["chk_edit"]) ){$chk_edit = $_POST["chk_edit"];} else {$chk_edit="";}

if($chk_edit=="1")
{
	$s_date = $_POST["s_year"]."-".$_POST["s_month"]."-".$_POST["s_day"];
	$s_er_answer_text = $_POST["s_er_answer_text"];

	$s_approve = 0;	
	if(!empty($s_er_answer_text)){
		$s_approve = 1;
		$sql = "update `ers_member_request_nodoc` set `er_answer`='$s_approve',`er_answer_expire`='$s_date',`er_answer_text`='$s_er_answer_text',`er_answer_date`=now(),`er_answer_name`='$admin',`update_date`=now(),`update_user`='$admin' where (`id`='".$req_id."')";
		$dbquery = $mysqli->query($sql);

		include("../include/close_db.php");
		?>
		<script>
		alert("บันทึกการตอบกลับสมาชิกสำเร็จ");
		window.onunload = refreshParent;
		function refreshParent() {
			window.opener.location.reload();
		}
		window.close();
		</script>
	<?
	}
	exit();
}

$c_member_id = 0;
$c_er_request_text = '';
$c_er_request_date = '';
$c_er_answer = 0;
$c_er_answer_approve = '';
$c_date = explode("-",date("Y-m-d"));
$c_year = $c_date[0];
$c_month = $c_date[1];
$c_day = $c_date[2];
$c_er_answer_text = '';
$c_er_answer_date = '';
$c_er_answer_name = '';
$c_name = '';
$c_username = '';
$c_institution = '';
$c_institution_type = 0;
$c_institution_other = '';
$c_phone = '';
$c_email = '';
$c_address = '';
$c_institution_name = "";

if(isset($req_id)){
	if(!empty($req_id)){
		$sql = "select * from `ers_member_request_nodoc` where (`id`='$req_id')";
		$dbquery = $mysqli->query($sql) or die("Can't send query!");
		$tRows = $dbquery->num_rows;
		if($tRows>0){
			$row = $dbquery->fetch_assoc();
			$c_member_id = $row["member_id"];
			$c_er_request_text = $row["er_request_text"];
			$c_er_request_date = dateThai_edo(substr($row["er_request_date"],0,10));
			$c_er_answer = $row["er_answer"];
			$c_er_answer_approve = "<span class='glyphicon glyphicon-remove' style='color:#ff0000;font-size:14px;'></span>";
			if($c_er_answer==1){
				$c_er_answer_approve = "<span class='glyphicon glyphicon-ok' style='color:#00cc00;font-size:14px;'></span>";
			}
			$c_er_answer_text = $row["er_answer_text"];
			if(!empty($row["er_answer_date"])) {
				$c_er_answer_date = dateThai_edo(substr($row["er_answer_date"],0,10));
			}
			$c_er_answer_name = $row["er_answer_name"];
			
			$sql_d = "select * from `ers_member` where `id`='".$c_member_id."' ";
			$dbquery_d = $mysqli->query($sql_d);
			$nRows_d = $dbquery_d->num_rows;
			if($nRows_d>0){
				$result_d = $dbquery_d->fetch_assoc();
				$c_username = $result_d["em_username"];
				$c_name = $result_d["em_title"];
				$c_name .= $result_d["em_firstname"];
				$c_name .= " ".$result_d["em_lastname"];
				$c_name2 = $result_d["em_firstname"]." ".$result_d["em_lastname"];
				$c_institution = $result_d["em_institution"];
				$c_institution_type = $result_d["em_institution_type"];
				$c_institution_other = $result_d["em_institution_other"];
				$c_phone = $result_d["em_phone"];
				$c_email = $result_d["em_email"];
				$c_address = $result_d["em_address"];
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
			}
		} 
	}
} 
$mysqli->query("update `ers_member_request_nodoc` set `er_request_read`='1',`er_request_counter`=`er_request_counter`+1 where `id` ='".$req_id."'");
//echo $c_title_th_v." AAAAAA<br>";
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
	<link href="../images/<?php if(defined('__EC_FAVICON__')){echo __FAVICON_ICO__;}?>" rel="icon" type="image/ico">
	<link href="../images/<?php if(defined('__EC_FAVICON__')){echo __EC_FAVICON__;}?>" rel="icon" type="image/png" sizes="32x32">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css?v=<?php echo filemtime('../bootstrap/css/bootstrap.min.css');?>">
    <script src="../js/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="./style_admin.css?v=<?php echo filemtime('./style_admin.css');?>">
	<link href="https://fonts.googleapis.com/css?family=Prompt" rel="stylesheet">
	<style>
	.col-lg-12 ,.col-lg-8 ,.col-lg-4 ,.col-lg-3 ,.col-lg-1 { margin:0;padding:0; }
	.col-md-12 ,.col-md-8 ,.col-md-4 ,.col-md-3 ,.col-md-1 { margin:0;padding:0; }
	.col-sm-12 ,.col-sm-8 ,.col-sm-4 ,.col-sm-3 ,.col-sm-1 { margin:0;padding:0; }
	</style>
</head>
<body role="document" style="background: url('../images/<?php if(defined('__EC_PICHOME__')){echo __EC_PICHOME__;}?>') no-repeat; background-attachment:fixed; background-size:contain;height:100%;width:100%;background-position: left center;">

<div class="container bgw1">

	<div class="row  bgw2">
		
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="margin-bottom:30px;background-color:#<?echo __EC_BGSHOW__;?>;color:#<?echo __EC_FONTSHOW__;?>;font-weight: bold;"><h4><?php if(defined('__EC_NAME__')){echo "การตอบกลับ การร้องขอเอกสารผลงานวิจัย";} else echo "ระบบคลังข้อมูลงานวิจัย";?></h4></div>

		<div style="padding-top:20px;">

		  <form name="form1"  method="post" action="mbnodoc.php" role="form">

			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right" style="">ชือสมาชิก&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><?echo $c_name;?></div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ชือ login&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><?echo $c_username;?></div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">หน่วยงานที่สังกัด&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><?= $c_institution;?>&nbsp;<?= $c_institution_name;?></div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">โทรศัพท์&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><a href='tel:<?= $c_phone;?>' target="_blank" title='<?= $c_phone;?>'><?= $c_phone;?></a></div>
			  </div>
			  <?
			    $subject_text = "ผลการร้องขอเอกสารผลงานวิจัย ".__EC_NAME__;
			    $encodedSubject = htmlspecialchars($subject_text);
			    $body_text = "สวัสดีคุณ ".$c_name2;
				if($c_er_answer==1){
					$body_text .= " ,ผลการดำเนินการ : เจ้าหน้าที่ได้ตอบกลับการร้องขอ ".$c_er_request_text;
				}
			    $encodedBody = htmlspecialchars($body_text);
			  ?>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">อีเมล&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><a href='mailto:<?= $c_email;?>?subject=<?= $encodedSubject;?>&body=<?= $encodedBody;?>' target='_blank' title="<?= $c_email;?>"><?= $c_email;?></a></div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ที่อยู่&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><?= $c_address;?></div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ข้อความร้องขอ&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><?echo $c_er_request_text;?></div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ข้อความตอบกลับ&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
				<textarea name="s_er_answer_text" id="s_er_answer_text" rows="10" class="form-control" style="border-radius:5px;border:1px solid #ccc;max-width:500px;" maxlength="500" placeholder="ระบุข้อความรายละเอียด ตอบกลับการขอเอกสารงานวิจัย"><?= $c_er_answer_text;?></textarea>
				</div>
			  </div>
			  
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>

		      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
					<input type="hidden" name="req_id" value="<? if(isset($req_id)){ echo $req_id;}else{ echo '';}?>">
   					<input type="hidden" name="chk_edit" value="1"> 
					<input type="submit" name="Submit" value=" บันทึกการตอบกลับ " class="btn btn-warning" style="width:150px;font-size:16px;">&nbsp;
			  </div>

			  <div class="ccol-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>

		  </form>

		</div>

	</div><!-- /.row -->

</div><!-- /.container -->

</body>
</html>
<? include("../include/close_db.php"); ?>
<script>
document.getElementById('s_er_answer_text').focus();
</script>