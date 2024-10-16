<?php
session_start();
Header("Content-Type: text/html; charset=UTF-8");
require_once "../include/config.php";
if (!defined('_web_path')) {
	exit();
}

if (!isset($_SESSION["username"]) || !isset($_SESSION["membername"]) || !isset($_SESSION["memberid"]))
{
	echo "<meta http-equiv='refresh' content='0;URL=login.php'>";
	exit();
}

if(isset($_SESSION["memberid"])){$c_id = $_SESSION["memberid"];}

include("../include/config_db.php");

if( isset($_POST["chk_edit"]) ){$chk_edit = $_POST["chk_edit"];} else {$chk_edit="";}

if($chk_edit=="1")
{
	/*$o_name = $_POST["o_name"];
	if($o_name != $username) {
		$sql = "select `em_username` from `ers_member` where (`em_username`='$username') ";
		$dbquery = $mysqli->query($link,$sql) or die("Can't send query !!");
		$num_rows = $dbquery->num_rows;
		$dbquery->close();
		if($num_rows > 0) {
			include("../include/close_db.php");*/
			?>
			<!--<Script language="javascript">
				alert("ชื่อล็อกอิน : <?= $username;?> มีอยู่ในระบบ ไม่สามารถใช้ซ้ำกันได้");
				window.history.back();
			</script>-->
			<?php
			/*die();
		}
	}*/

	$s_title_v = $_POST["title_v"];
	$s_title = "";
	if($s_title_v=="1"){$s_title = "นาย";}
	if($s_title_v=="2"){$s_title = "นาง";}
	if($s_title_v=="3"){$s_title = "นางสาว";}
	if($s_title_v=="4"){$s_title = $_POST["title_other"];}
	$s_firstname = $_POST["firstname"];
	$s_lastname = $_POST["lastname"];
	$s_gender = $_POST["gender"];
	$s_institution = $_POST["institution"];
	$s_institution_type = $_POST["institution_type"];
	$s_phone = $_POST["phone"];
	$s_email = $_POST["email"];
	$s_address = $_POST["address"];
	$s_institution_other = "";
	if($s_institution_type=="7"){$s_institution_other = $_POST["institution_other"];}
	$s_news_mail = 1;
	$s_active = "Yes";
	$s_ip = $_SERVER["REMOTE_ADDR"];

	$sql = "select * from `ers_member` where (`id`='".$c_id."')";
	$dbquery = $mysqli->query($link,$sql) or die("Can't send query !!");
	$num_rows = $dbquery->num_rows;
	$dbquery->free();
	if($num_rows > 0) {
		$sql = "update `ers_member` set `em_title`='$s_title',`em_firstname`='$s_firstname',`em_lastname`='$s_lastname',`em_gender`='$s_gender',`em_institution`='$s_institution',`em_institution_type`='$s_institution_type',`em_institution_other`='$s_institution_other',`em_email`='$s_email',`em_phone`='$s_phone',`em_address`='$s_address',`em_ip`='$s_ip',`update_date`=now(),`update_user`='".$_SESSION["username"]."' where (`id`='".$c_id."')";
		$dbquery = $mysqli->query($link,$sql) or die("ไม่สามารถบันทึกข้อมูลได้ !1");
	}
	$_SESSION["membername"] = $s_firstname;
	include("../include/close_db.php");
	echo "<meta http-equiv='refresh' content='0;URL=index.php'>";
	exit();
}

$c_username = '';
$c_title_v = '0';
$c_title = '';
$c_firstname = '';
$c_lastname = '';
$c_gender = '';
$c_institution = '';
$c_institution_type = 0;
$c_institution_other = '';
$c_phone = '';
$c_email = '';
$c_address = '';
if(isset($c_id)){
	if(!empty($c_id)){
		$sql = "select * from `ers_member` where (`id`='$c_id')";
		$dbquery = $mysqli->query($link,$sql) or die("Can't send query!");
		$tRows = $dbquery->num_rows;
		if($tRows>0){
			$row = $dbquery->fetch_assoc();
			$c_username = $row["em_username"];
			$c_title = $row["em_title"];
			if($c_title=="นาย"){ $c_title_v = '1';} elseif($c_title=="นาง"){ $c_title_v = '2';} elseif($c_title=="นางสาว"){ $c_title_v = '3';} elseif(!empty($c_title)){ $c_title_v = '4';}
			$c_firstname = $row["em_firstname"];
			$c_lastname = $row["em_lastname"];
			$c_gender = $row["em_gender"];
			$c_institution = $row["em_institution"];
			$c_institution_type = $row["em_institution_type"];
			$c_institution_other = $row["em_institution_other"];
			$c_phone = $row["em_phone"];
			$c_email = $row["em_email"];
			$c_address = $row["em_address"];
		}
		$dbquery->free();
		//echo $c_id." BBBBB<br>";
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
    <title>ระบบคลังข้อมูลงานวิจัย <?php if(defined('__EC_NAME__')){echo __EC_NAME__;}?></title>
	<link href="../images/<?php if(defined('__EC_FAVICON__')){echo __EC_FAVICON_ICO__;}?>" rel="icon" type="image/ico">
	<link href="../images/<?php if(defined('__EC_FAVICON__')){echo __EC_FAVICON__;}?>" rel="icon" type="image/png" sizes="32x32">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css?v=<?php echo filemtime('../bootstrap/css/bootstrap.min.css');?>">
    <script src="../js/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="../admin/style_admin.css?v=<?php echo filemtime('../admin/style_admin.css');?>">
	<link href="https://fonts.googleapis.com/css?family=Prompt" rel="stylesheet">
	<script>
	function chkother(v){
		if(v == "4")
		{
			document.getElementById('title_other').style.display="block";
			document.getElementById('title_other').focus();
		} else {
			document.getElementById('title_other').style.display="none";
		}
	}
	function chkins(){
		if(document.getElementById('institution_type').value=='7')
		{
			document.getElementById('institution_other').style.display="block";
			document.getElementById('institution_other').focus();
		} else {
			document.getElementById('institution_other').style.display="none";
		}
	}
	</script>
	<style>
	.panel-primary>.panel-heading {
		color: #000;
		background-color: #ffce00;
		border-color: #ffce00;
	}
	.panel-primary {
		border-color: #ffce00;
	}
	</style>
</head>
<body role="document" style="background: url('../images/<?php if(defined('__EC_PICHOME__')){echo __EC_PICHOME__;}?>') no-repeat;background-attachment:fixed; background-size: 60% auto;height:auto;width:auto;background-position: center;">

<div class="container-fluid" style="margin:0;padding:0;">
	<?php require_once "../headerpb.php"; ?>
</div>
<div class="container" style="width:100vw; height:100vh;-moz-opacity: 0.97;-khtml-opacity: 0.97;opacity: 0.97; background-color: #ffffff;">
	
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="height:10px;">&nbsp;
		</div><!-- /.col-lg-12 col-md-12 col-sm-12 col-xs-12 -->
	</div><!-- /.row -->

	<div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-lg-offset-4 col-md-offset-4 col-sm-offset-3 text-center">

			<div class="panel panel-primary" style="box-shadow: 5px 10px #7c7c7c;border-width: 0 5px 10px 5px;border-radius: 10px;background-color: #ffce00;">
				<div class="panel-heading">
					<h3 class="panel-title"><strong>แก้ไขข้อมูลสมาชิก</strong></h3>			   
				</div>
				<div class="panel-body text-center" style="border-radius: 10px;border: 1px solid #ffce00;background-color: #ffffff;">

					<form method="post" role="form" action="editprofile.php" onSubmit="return checkSubmit();">

					<div class="col-xs-5 col-sm-3" style="text-align:right;padding: 3px 5px 3px 0px;">คำนำหน้าชื่อ&nbsp;</div>
					<div class="col-xs-7 col-sm-9 text-left" style="padding: 3px 5px 3px 0px;">
						<input name="title_v" type="radio" value="1" <?php if($c_title_v == "1"){echo "checked";}?> onclick="chkother('1')">&nbsp;นาย&nbsp;
						<input name="title_v" type="radio" value="2" <?php if($c_title_v == "2"){echo "checked";}?> onclick="chkother('2')">&nbsp;นาง&nbsp;
						<input name="title_v" type="radio" value="3" <?php if($c_title_v == "3"){echo "checked";}?> onclick="chkother('3')">&nbsp;นางสาว&nbsp;
						<input name="title_v" type="radio" value="4" <?php if($c_title_v == "4"){echo "checked";}?> onclick="chkother('4')">&nbsp;อื่นๆ
						<?php if($c_title_v == "4"){?>
							<input type="text" name="title_other" id="title_other" maxlength="30" class="form-control" value="<?= $c_title;?>" placeholder="คำนำหน้าชื่อ">
						<?php} else {?>
							<input type="text" name="title_other" id="title_other" maxlength="30" class="form-control" value="<?= $c_title;?>" style="display:none;" placeholder="คำนำหน้าชื่อ">
						<?php } ?>
					</div>

					<div class="col-xs-5 col-sm-3" style="text-align:right;padding: 3px 5px 3px 0px;"><font color="#FF0000">*</font>&nbsp;ชื่อจริง&nbsp;</div>
					<div class="col-xs-7 col-sm-9" style="padding: 3px 5px 3px 0px;"><input name="firstname" id="firstname" type="text" maxlength="120" class="form-control"  value="<?= $c_firstname;?>"></div>

					<div class="col-xs-5 col-sm-3" style="text-align:right;padding: 3px 5px 3px 0px;"><font color="#FF0000">*</font>&nbsp;นามสกุล&nbsp;</div>
					<div class="col-xs-7 col-sm-9" style="padding: 3px 5px 3px 0px;"><input name="lastname" id="lastname" type="text" maxlength="120" class="form-control"  value="<?= $c_lastname;?>"></div>

					<div class="col-xs-5 col-sm-3" style="text-align:right;padding: 3px 5px 3px 0px;">เพศ&nbsp;</div>
					<div class="col-xs-7 col-sm-9" style="padding: 3px 5px 3px 0px;">
						<select name="gender" id="gender" class="form-control">
							<option value="0" <?php if($c_gender == "0"){echo "selected";}?>>เลือกเพศ</option>
							<option value="1" <?php if($c_gender == "1"){echo "selected";}?>>ชาย</option>
							<option value="2" <?php if($c_gender == "2"){echo "selected";}?>>หญิง</option>
						</select>
					</div>

					<div class="col-xs-5 col-sm-3" style="text-align:right;padding: 3px 5px 3px 0px;"><font color="#FF0000">*</font>&nbsp;หน่วยงานที่สังกัด&nbsp;</div>
					<div class="col-xs-7 col-sm-9" style="padding: 3px 5px 3px 0px;"><input name="institution" id="institution" type="text" maxlength="255" class="form-control" value="<?= $c_institution;?>"></div>

					<div class="col-xs-5 col-sm-3" style="text-align:right;padding: 3px 5px 3px 0px;">ประเภทหน่วยงาน&nbsp;</div>
					<div class="col-xs-7 col-sm-9" style="padding: 3px 5px 3px 0px;">
						<select name="institution_type" id="institution_type" class="form-control" onchange="chkins()">
							<option value="0" <?php if($c_institution_type == "0"){echo "selected";}?>>เลือกประเภท</option>
							<option value="1" <?php if($c_institution_type == "1"){echo "selected";}?>>หน่วยงานภาครัฐ/วิสาหกิจ</option>
							<option value="2" <?php if($c_institution_type == "2"){echo "selected";}?>>หน่วยงานภาคเอกชน</option>
							<option value="3" <?php if($c_institution_type == "3"){echo "selected";}?>>องค์กรอิสระ</option>
							<option value="4" <?php if($c_institution_type == "4"){echo "selected";}?>>สถาบันการศึกษาภาครัฐ</option>
							<option value="5" <?php if($c_institution_type == "5"){echo "selected";}?>>สถาบันการศึกษาเอกชน</option>
							<option value="6" <?php if($c_institution_type == "6"){echo "selected";}?>>ประชาชน</option>
							<option value="7" <?php if($c_institution_type == "7"){echo "selected";}?>>อื่น ๆ</option>
						</select>
						<?php if($c_institution_type == "7"){?>
							<input type="text" name="institution_other" id="institution_other" maxlength="255" class="form-control" value="<?= $c_institution_other;?>" placeholder="อื่นๆระบุ">
						<?php} else {?>
							<input type="text" name="institution_other" id="institution_other" maxlength="255" class="form-control" value="<?= $c_institution_other;?>" style="display:none;" placeholder="อื่นๆระบุ">
						<?php } ?>
					</div>

					<div class="col-xs-5 col-sm-3" style="text-align:right;padding: 3px 5px 3px 0px;"><font color="#FF0000">*</font>&nbsp;โทรศัพท์&nbsp;</div>
					<div class="col-xs-7 col-sm-9" style="padding: 3px 5px 3px 0px;"><input name="phone" id="phone" type="tel" maxlength="30" class="form-control" value="<?= $c_phone;?>"></div>

					<div class="col-xs-5 col-sm-3" style="text-align:right;padding: 3px 5px 3px 0px;"><font color="#FF0000">*</font>&nbsp;อีเมล&nbsp;</div>
					<div class="col-xs-7 col-sm-9" style="padding: 3px 5px 3px 0px;"><input type="text" name="email" id="email" maxlength="120" class="form-control" value="<?= $c_email;?>"></div>

					<div class="col-xs-5 col-sm-3" style="text-align:right;padding: 3px 5px 3px 0px;"><font color="#FF0000">*</font>&nbsp;ที่อยู่&nbsp;</div>
					<div class="col-xs-7 col-sm-9" style="padding: 3px 5px 3px 0px;"><textarea name="address" id="address" rows="3" class="form-control" style="border-radius:5px;border:1px solid #ccc;" maxlength="500"><?= $c_address;?></textarea></div>
					
					<!--<div class="col-xs-5 col-sm-3" style="text-align:right;padding: 3px 5px 3px 0px;"><font color="#FF0000">*</font>&nbsp;ชื่อล็อกอิน&nbsp;</div>
					<div class="col-xs-7 col-sm-9" style="padding: 3px 5px 3px 0px;"><input name="username" type="text" id="username"  value="<?php//=$c_username;?>" maxlength="30" class="form-control" placeholder="Username"><font color="#FF0000">*ตัวอักษรภาษาอังกฤษและ ตัวเลข ไม่เกิน 30 ตัวอักษร</font></div>-->

					<div class="col-xs-12 col-sm-12">&nbsp;</div>
					<div class="col-xs-12 col-sm-12" style="text-align:center;padding: 3px 5px 3px 0px;">
						<input name="Submit" type="submit" value="ส่งข้อมูล" class="btn btn-info" style="width:100px;">
						<input type="hidden" name="chk_edit" value="1"> 
						<!--<input type="hidden" name="o_name" value="<?php//echo $c_username;?>">-->
						<!--<input name="&nbsp;Reset&nbsp;" type="reset" value="Reset" class="btn btn-warning">-->
					</div>
					<div class="col-xs-12 col-sm-12">&nbsp;</div>
					</form>

				</div>
			</div>

		</div><!-- /.col-lg-2 col-md-4 col-sm-6 col-xs-12 col-lg-offset-5 col-md-offset-4 col-sm-offset-3 text-center -->

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div align="center"><?php require_once("../admin/footer.php") ?></div>
	    </div>

	</div><!-- /.row -->
</div>
</body>
</html>
<?php

include("../include/close_db.php"); 

?>

<script language="javascript">
function checkSubmit(){
	if(document.getElementById('firstname').value == "")
	{
		alert("'ชื่อจริง' จำเป็นต้องมีข้อมูล");	
		document.getElementById('firstname').focus();
		return false;
	}
	if(document.getElementById('lastname').value == "")
	{
		alert("'นามสกุล' จำเป็นต้องมีข้อมูล");	
		document.getElementById('lastname').focus();
		return false;
	}
	if(document.getElementById('institution').value == "")
	{
		alert("'หน่วยงานที่สังกัด' จำเป็นต้องมีข้อมูล");	
		document.getElementById('institution').focus();
		return false;
	}
	if(document.getElementById('phone').value == "")
	{
		alert("'โทรศัพท์' จำเป็นต้องมีข้อมูล");	
		document.getElementById('phone').focus();
		return false;
	}
	var emailf = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	function validEmail(str) {
		return emailf.test(str);
	}
	if(document.getElementById('email').value == "")
	{
		alert("กรุณาให้ข้อมูล 'อีเมล");	
		document.getElementById('email').select();
		return false;
	}
	var str=document.getElementById('email').value;
	if(validEmail(str)==false)
	{
		alert("อีเมล ไม่ถูกต้อง");
		document.getElementById('email').focus();
		return false;
	}
	if(document.getElementById('address').value == "")
	{
		alert("'ที่อยู่' จำเป็นต้องมีข้อมูล");	
		document.getElementById('address').focus();
		return false;
	}
}
var sw = screen.width;
if(sw < 768)
{
	document.getElementById("headernd_mobile").style.display = "";
}
function FocusOnInput() {
	  var element = document.getElementById('firstname');
	  element.focus();
	  setTimeout(function () { element.focus(); }, 1);
	}
FocusOnInput();
</script>
