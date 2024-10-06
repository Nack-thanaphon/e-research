<?php
session_start();
Header("Content-Type: text/html; charset=UTF-8");
require_once "../include/config.php";
if (!defined('_web_path')) {
	exit();
}
$_menu_id = 5;
include("../include/config_db.php");
if(isset($_GET["dcid"])){	$dcid = $_GET["dcid"];} else {if(isset($_POST["dcid"])){$dcid = $_POST["dcid"];}}
if(isset($_POST['username'])){$username = $_POST['username'];}
if(isset($_POST['u_password'])){$u_password= $_POST['u_password'];}
if(isset($_POST['username']) && isset($_POST['u_password']))
{
 
	if(isset($_POST['g-recaptcha-response'])){
	  $captcha=$_POST['g-recaptcha-response'];
	}
	if(!$captcha){
		include("../include/close_db.php");
		?>
		<SCRIPT LANGUAGE="JavaScript">
		alert("กรุณายืนยัน ไม่ใช่โปรแกรมอัตโนมัติ");
	    window.history.back();
		//window.location="register.php?dcid=<?=$dcid;?>";
		</SCRIPT> 
		<?
		die();
	}
	$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Ldyp90dAAAAAEG1ZjI0lKiMo10qJPTEf-roqPXq&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
	if($response.success==false)            
	{
		include("../include/close_db.php");
		?>
		<SCRIPT LANGUAGE="JavaScript">
		alert("กรุณายืนยัน ไม่ใช่โปรแกรมอัตโนมัติ");
	    window.history.back();
		//window.location="register.php?dcid=<?=$dcid;?>";
		</SCRIPT> 
		<?
		die();
	}

	$pass5 = md5($u_password.SECRET_KEY);
	//include("../include/close_db.php");
	//echo $username."<br>";echo $pass5."<br>";echo SECRET_KEY."<br>";echo $u_password."<br>";die();exit();
	$username = trim($username);
	if(empty($username) or !isset($username) or empty($u_password) or !isset($u_password)){
		include("../include/close_db.php");
		?>
		<SCRIPT LANGUAGE="JavaScript">
		alert("ชื่อผู้ใช้งาน หรือ รหัสผ่าน ไม่ถูกต้อง กรุณาลงทะเบียนใหม่");
	    window.history.back();
		//window.location="register.php?dcid=<?=$dcid;?>";
		</SCRIPT> 
		<?
		//echo "<Script language=\"javascript\">window.location=\""._web_path."\"</script>";
		//echo "<p><h4>ชื่อผู้ใช้งาน หรือ รหัสผ่าน ไม่ถูกต้อง กรุณาลงทะเบียนใหม่</h4></a>&nbsp;<a href='register.php' style='color:#ff0000;'><h3>ลงทะเบียนใหม่</h3></a></p>"; 
		die();
	}


	$sql = "select `em_username` from `ers_member` where (`em_username`='$username') ";
	$dbquery = $mysqli->query($sql) or die("Can't send query !!");
	$num_rows = $dbquery->num_rows;
	$dbquery->close();
	if($num_rows > 0) {
		include("../include/close_db.php");
		?>
		<Script language="javascript">
			alert("ชื่อล็อกอิน : <?=$username;?> มีอยู่ในระบบ ไม่สามารถใช้ซ้ำกันได้");
			window.history.back();
			//window.location="register.php?dcid=<?=$dcid;?>";
		</script>
		<?
		die();
	}

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

	$sql = "select `em_username` from `ers_member` where (`em_username`='".$username."')";
	$dbquery = $mysqli->query($sql) or die("Can't send query !!");
	$num_rows = $dbquery->num_rows;
	$dbquery->free();
	if($num_rows <= 0) {
		if($username != ''){
			$sql = "insert into `ers_member` (`em_username`,`em_password`,`em_title`,`em_firstname`,`em_lastname`,`em_gender`,`em_institution`,`em_institution_type`,`em_institution_other`,`em_email`,`em_phone`,`em_address`,`em_news_mail`,`SID`,`em_active`,`em_ip`,`em_reg_date`) values ('$username','$pass5','$s_title','$s_firstname','$s_lastname','$s_gender','$s_institution','$s_institution_type','$s_institution_other','$s_email','$s_phone','$s_address','$s_news_mail','".session_id()."','$s_active','$s_ip',now())";
			$dbquery = $mysqli->query($sql) or die("ไม่สามารถบันทึกข้อมูลได้ !2");
		}
	}  
	$query_m = "select * from `ers_member` where `em_username`='".$username."' and `em_password`='".$pass5."' ";
	$result_m = $mysqli->query($query_m);
	$num_rows = $result_m->num_rows;
	if ($num_rows > 0 )
	{
		$_SESSION["username"] = $username;
		$row = $result_m->fetch_assoc();
		$_SESSION["membername"] = $row["em_firstname"];
		$_SESSION["memberid"] = $row["id"];
		$_SESSION["memberpass"] = $u_password;
		$result_m->free();
		$query_m = "insert into `ers_session`  (`id`,`user_name`,`ip_address`,`log_time`,`log_status`,`user_type`) values ('','$username','$s_ip',now(),'i','1') ";
		$result_d = $mysqli->query($query_m);
		include("../include/close_db.php");
		echo "<meta http-equiv='refresh' content='0;URL=index.php?dcid=$dcid'>";
	} else {
		include("../include/close_db.php");
		echo "<meta http-equiv='refresh' content='0;URL=login.php?dcid=$dcid'>";
	}
	exit();
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
	<link rel="stylesheet" href="../css/font-awesome.css">
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
	.g-recaptcha {
		transform:scale(0.9);
		-webkit-transform:scale(0.9);
		transform-origin:0 0;
		-webkit-transform-origin:0 0;
	}
	</style>
</head>
<body role="document" style="background: url('../images/<?php if(defined('__EC_PICHOME__')){echo __EC_PICHOME__;}?>') no-repeat;background-attachment:fixed; background-size: 60% auto;height:auto;width:auto;background-position: center;">

<div class="container-fluid" style="margin:0;padding:0;">
	<? require_once "../headerpb.php"; ?>
</div>
<div class="container" style="width:100vw; height:100vh;-moz-opacity: 0.97;-khtml-opacity: 0.97;opacity: 0.97; background-color: #ffffff;">
	
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="height:10px;">&nbsp;
		</div><!-- /.col-lg-12 col-md-12 col-sm-12 col-xs-12 -->
	</div><!-- /.row -->

	<div class="row">
		<div class="col-lg-2 col-md-1 col-sm-1 col-xs-12"></div>
		<div class="col-lg-8 col-md-10 col-sm-10 col-xs-12">
			<div class="col-lg-7 col-md-10 col-sm-10 col-xs-12 col-lg-offset-2 col-md-offset-1 col-sm-offset-1 text-center">

				<div class="panel panel-primary" style="border-width: 10px 20px 20px 20px;border-radius: 10px;background-color: #ffce00;">
					<div class="panel-heading">
						<h3 class="panel-title"><strong>สมัครสมาชิก</strong></h3>			   
					</div>
					<div class="panel-body text-center" style="border-radius: 10px;border: 1px solid #ffce00;background-color: #ffffff;">

						<form method="post" role="form" action="register.php" onSubmit="return checkSubmit();">
						<div class="col-xs-12 col-sm-12">
						<div class="col-xs-5 col-sm-3 text-right" style="padding: 3px 5px 3px 0px;">คำนำหน้าชื่อ&nbsp;</div>
						<div class="col-xs-7 col-sm-9 text-left" style="padding: 3px 5px 3px 0px;">
							<input name="title_v" type="radio" value="1" onclick="chkother('1')">&nbsp;นาย&nbsp;
							<input name="title_v" type="radio" value="2" onclick="chkother('2')">&nbsp;นาง&nbsp;
							<input name="title_v" type="radio" value="3" onclick="chkother('3')">&nbsp;นางสาว&nbsp;
							<input name="title_v" type="radio" value="4" onclick="chkother('4')">&nbsp;อื่นๆ
							<input type="text" name="title_other" id="title_other" maxlength="30" class="form-control" style="display:none;" placeholder="คำนำหน้าชื่อ">
						</div>
						</div>						
						<div class="col-xs-12 col-sm-12">
						<div class="col-xs-5 col-sm-3 text-right" style="padding: 3px 5px 3px 0px;"><font color="#FF0000">*</font>&nbsp;ชื่อจริง&nbsp;</div>
						<div class="col-xs-7 col-sm-9 text-left" style="padding: 3px 5px 3px 0px;"><input name="firstname" id="firstname" type="text" maxlength="120" class="form-control"></div>
						</div>
						<div class="col-xs-12 col-sm-12">
						<div class="col-xs-5 col-sm-3 text-right" style="padding: 3px 5px 3px 0px;"><font color="#FF0000">*</font>&nbsp;นามสกุล&nbsp;</div>
						<div class="col-xs-7 col-sm-9 text-left" style="padding: 3px 5px 3px 0px;"><input name="lastname" id="lastname" type="text" maxlength="120" class="form-control"></div>
						</div>
						<div class="col-xs-12 col-sm-12">
						<div class="col-xs-5 col-sm-3 text-right" style="padding: 3px 5px 3px 0px;">เพศ&nbsp;</div>
						<div class="col-xs-7 col-sm-9 text-left" style="padding: 3px 5px 3px 0px;">
							<select name="gender" id="gender" class="form-control">
								<option value="0">เลือกเพศ</option>
								<option value="1">ชาย</option>
								<option value="2">หญิง</option>
							</select>
						</div>
						</div>
						<div class="col-xs-12 col-sm-12">
						<div class="col-xs-5 col-sm-3 text-right" style="padding: 3px 5px 3px 0px;"><font color="#FF0000">*</font>&nbsp;หน่วยงานที่สังกัด&nbsp;</div>
						<div class="col-xs-7 col-sm-9 text-left" style="padding: 3px 5px 3px 0px;"><input name="institution" id="institution" type="text" maxlength="255" class="form-control" value="<?=$c_institution;?>"></div>
						</div>
						<div class="col-xs-12 col-sm-12">
						<div class="col-xs-5 col-sm-3 text-right" style="padding: 3px 5px 3px 0px;">ประเภทหน่วยงาน&nbsp;</div>
						<div class="col-xs-7 col-sm-9 text-left" style="padding: 3px 5px 3px 0px;">
							<select name="institution_type" id="institution_type" class="form-control" onchange="chkins()">
								<option value="0">เลือกประเภท</option>
								<option value="1">หน่วยงานภาครัฐ/วิสาหกิจ</option>
								<option value="2">หน่วยงานภาคเอกชน</option>
								<option value="3">องค์กรอิสระ</option>
								<option value="4">สถาบันการศึกษาภาครัฐ</option>
								<option value="5">สถาบันการศึกษาเอกชน</option>
								<option value="6">ประชาชน</option>
								<option value="7">อื่น ๆ</option>
							</select>
							<input type="text" name="institution_other" id="institution_other" maxlength="255" class="form-control" style="display:none;" placeholder="อื่นๆระบุ">
						</div>
						</div>
						<div class="col-xs-12 col-sm-12">
						<div class="col-xs-5 col-sm-3 text-right" style="padding: 3px 5px 3px 0px;"><font color="#FF0000">*</font>&nbsp;โทรศัพท์&nbsp;</div>
						<div class="col-xs-7 col-sm-9 text-left" style="padding: 3px 5px 3px 0px;"><input name="phone" id="phone" type="tel" maxlength="30" class="form-control"></div>
						</div>
						<div class="col-xs-12 col-sm-12">
						<div class="col-xs-5 col-sm-3 text-right" style="padding: 3px 5px 3px 0px;"><font color="#FF0000">*</font>&nbsp;อีเมล&nbsp;</div>
						<div class="col-xs-7 col-sm-9 text-left" style="padding: 3px 5px 3px 0px;"><input type="text" name="email" id="email" maxlength="120" class="form-control"></div>
						</div>
						<div class="col-xs-12 col-sm-12">
						<div class="col-xs-5 col-sm-3 text-right" style="padding: 3px 5px 3px 0px;"><font color="#FF0000">*</font>&nbsp;ที่อยู่&nbsp;</div>
						<div class="col-xs-7 col-sm-9 text-left" style="padding: 3px 5px 3px 0px;"><textarea name="address" id="address" rows="3" class="form-control" style="border-radius:5px;border:1px solid #ccc;" maxlength="500"></textarea></div>
						</div>
						<div class="col-xs-12 col-sm-12">
						<div class="col-xs-5 col-sm-3 text-right" style="padding: 3px 5px 3px 0px;"><font color="#FF0000">*</font>&nbsp;ชื่อล็อกอิน&nbsp;</div>
						<div class="col-xs-7 col-sm-9 text-left" style="padding: 3px 5px 3px 0px;"><input name="username" type="text" id="username" maxlength="30" class="form-control" placeholder="Username"><font color="#FF0000">*ตัวอักษรภาษาอังกฤษและ ตัวเลข 6 ถึง 30 ตัวอักษร</font></div>
						</div>
						<div class="col-xs-12 col-sm-12">
						<div class="col-xs-5 col-sm-3 text-right" style="padding: 3px 5px 3px 0px;"><font color="#FF0000">*</font>&nbsp;รหัสผ่าน&nbsp;</div>
						<div class="col-xs-7 col-sm-9 text-left" style="padding: 3px 5px 3px 0px;"><input name="u_password" id="u_password" type="password" maxlength="30" class="form-control3" placeholder="Password" required="" autocomplete="current-password"><i class="fa fa-eye" id="togglePassword" style="margin-left: -30px; cursor: pointer;"></i></div>
						</div>
						<div class="col-xs-12 col-sm-12">
						<div class="col-xs-5 col-sm-3 text-right" style="padding: 3px 5px 3px 0px;"><font color="#FF0000">*</font>&nbsp;ยืนยัน รหัสผ่าน&nbsp;</div>
						<div class="col-xs-7 col-sm-9 text-left" style="padding: 3px 5px 3px 0px;"><input name="a_password" id="a_password" type="password" maxlength="30" class="form-control" placeholder="Confirm password"></div>
						</div>
						<!--
						<div class="col-xs-12 col-sm-12">
						<div class="col-xs-5 col-sm-3" style="padding: 3px 5px 3px 0px;">รับข่าวสารหรือไม่&nbsp;</div>
						<div class="col-xs-7 col-sm-9 text-left" style="padding: 3px 5px 3px 0px;">
							<input type="radio" name="mail" value="0">&nbsp;ไม่ยินดีรับข่าวสาร&nbsp;&nbsp;<input name="mail" type="radio" value="1" checked="checked">&nbsp;ยินดีรับข่าวสาร
						</div>
						</div>
						-->

						<div class="col-xs-12 col-sm-12" style="text-align:center;padding-top:5px;padding-right:5px;padding-left:5px;">
						<center>
								<script src='https://www.google.com/recaptcha/api.js'></script>
								<div class="g-recaptcha" data-sitekey="6Ldyp90dAAAAACoiaddtQb1cMYJlzOJanwg6dfBu"></div>
						</center>
						</div>

						<div class="col-xs-12 col-sm-12">&nbsp;</div>
						<div class="col-xs-12 col-sm-12" style="text-align:center;padding: 3px 5px 3px 0px;">
							<input name="Submit" type="submit" value="ส่งข้อมูล" class="btn btn-info" style="width:100px;">
							<input type="hidden" name="dcid" value="<? if(isset($dcid)){ echo $dcid;}else{ echo '';}?>">
							<!--<input name="&nbsp;Reset&nbsp;" type="reset" value="Reset" class="btn btn-warning">-->
						</div>
						<div class="col-xs-12 col-sm-12">&nbsp;</div>
						</form>

					</div>
				</div>

			</div><!-- /.col-lg-8 col-md-10 col-sm-10 col-xs-12 col-lg-offset-2 col-md-offset-1 col-sm-offset-1 text-center -->

		</div>
		<div class="col-lg-2 col-md-1 col-sm-1 col-xs-12"></div>

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div align="center"><? require_once("../admin/footer.php") ?></div>
		</div>

	</div><!-- /.row -->
</div>
</body>
</html>
<?

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
	if(document.getElementById('username').value == "") 
	{
		alert("ชื่อล็อกอิน' จำเป็นต้องมีข้อมูล");	
		document.getElementById('username').focus();
		return false;
	}
	var valun = document.getElementById('username').value;
	var stl = valun.length;
	if(!valun.match(/^([a-z0-9\_])+$/i))
	{
		alert("ชื่อล็อกอิน กรอกได้เฉพาะ a-Z, A-Z, 0-9 และ _ (underscore)");
		document.getElementById('username').value = "";
		document.getElementById('username').focus();
		return false;
	}
	if((stl < 6) || (stl > 30)){
		alert("ชื่อล็อกอิน ต้องอยู่ระหว่าง 6-30 ตัวอักษร");
		document.getElementById('username').focus();
		return false;
	}
	if(document.getElementById('u_password').value == "") 
	{
		alert("'รหัสผ่าน' จำเป็นต้องมีข้อมูล");	
		document.getElementById('u_password').focus();
		return false;
	}
	var valun = document.getElementById('u_password').value;
	var stl = valun.length;
	if((stl < 6) || (stl > 50)){
		alert("รหัสผ่าน ต้องอยู่ระหว่าง 6 ถึง 30 ตัวอักษร");
		document.getElementById('u_password').focus();
		return false;
	}
	if(document.getElementById('u_password').value != document.getElementById('a_password').value)
	{
		alert("รหัสผ่าน-ยืนยันรหัสผ่าน ไม่เหมือนกัน ? กรุณาตรวจสอบ");		
		document.getElementById("a_password").value = '';
		document.getElementById("a_password").select();	
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
const togglePassword = document.querySelector('#togglePassword');
const password = document.querySelector('#u_password');
togglePassword.addEventListener('click', function (e) {
    // toggle the type attribute
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    // toggle the eye slash icon
    this.classList.toggle('fa-eye-slash');
});
FocusOnInput();
</script>
